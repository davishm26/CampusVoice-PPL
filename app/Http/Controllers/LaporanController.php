<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use App\Models\Laporan;
use App\Models\LaporanTanggapan;
use App\Models\LaporanTracking;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    protected function getActiveUser(): User
    {
        if (Auth::check()) {
            return Auth::user();
        }

        if (request()->filled('user_id')) {
            $u = User::find(request('user_id'));
            if ($u) return $u;
        }

        if (request()->filled('nim')) {
            $u = User::where('nim', request('nim'))->first();
            if ($u) return $u;
        }

        return User::first() ?? new User();
    }

    public function create()
    {
        $authUser = $this->getActiveUser();
        $unreadCount = AppNotification::where('user_id', $authUser->id)->where('is_read', false)->count();

        return view('laporan.create', compact('authUser', 'unreadCount'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori' => 'required|string',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string|min:30',
            'lokasi' => 'required|string|max:255',
            'berkas_bukti' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,pdf',
            'is_anonim' => 'nullable',
        ], [
            'deskripsi.min' => 'Deskripsi laporan minimal harus berisi 30 karakter.',
            'berkas_bukti.max' => 'Ukuran berkas bukti maksimal adalah 10 MB.',
            'berkas_bukti.mimes' => 'Format berkas bukti harus berupa JPG, PNG, atau PDF.',
        ]);

        $user = $this->getActiveUser();

        // Generate nomor tiket baru yang unik (format: CV-YYYY-XXXXX) dari nomor tertinggi
        $year = date('Y');
        $maxNumber = 0;
        foreach (Laporan::all() as $item) {
            if (preg_match('/CV-\d{4}-(\d+)/', $item->nomor_tiket, $matches)) {
                $num = intval($matches[1]);
                if ($num > $maxNumber) {
                    $maxNumber = $num;
                }
            }
        }
        $nextNumber = $maxNumber + 1;
        $nomorTiket = sprintf('CV-%s-%05d', $year, $nextNumber);

        // Upload berkas jika dilampirkan
        $filePath = null;
        $fileName = null;
        $fileSize = null;

        if ($request->hasFile('berkas_bukti')) {
            $file = $request->file('berkas_bukti');
            $fileName = $file->getClientOriginalName();
            $fileSize = number_format($file->getSize() / (1024 * 1024), 2) . ' MB';
            $filePath = $file->store('bukti_laporan', 'public');
        }

        $kategoriLabels = [
            'sarana_prasarana' => 'Sarana dan prasarana kampus',
            'kemahasiswaan' => 'Kemahasiswaan',
            'ssc' => 'Student Service Center',
            'akademik' => 'Akademik',
            'administrasi_keuangan' => 'Administrasi dan keuangan',
            'lainnya' => 'Lainnya',
        ];

        $kategoriKey = $request->kategori;
        $kategoriLabel = $kategoriLabels[$kategoriKey] ?? ucfirst(str_replace('_', ' ', $kategoriKey));

        $laporan = Laporan::create([
            'user_id' => $user->id,
            'nomor_tiket' => $nomorTiket,
            'kategori' => $kategoriKey,
            'kategori_label' => $kategoriLabel,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'lokasi' => $request->lokasi,
            'berkas_bukti' => $filePath,
            'berkas_nama' => $fileName,
            'berkas_ukuran' => $fileSize,
            'is_anonim' => $request->boolean('is_anonim'),
            'status' => 'Menunggu Verifikasi',
            'prioritas' => 'Normal',
            'unit_penangan' => 'Belum Ditentukan',
            'progres' => 10,
        ]);

        // Simpan log status awal
        LaporanTracking::create([
            'laporan_id' => $laporan->id,
            'status' => 'Menunggu Verifikasi',
            'keterangan' => 'Admin SSC akan memeriksa kelengkapan informasi laporan dan menentukan unit penangan.',
            'waktu' => now(),
        ]);

        LaporanTracking::create([
            'laporan_id' => $laporan->id,
            'status' => 'Laporan Terkirim',
            'keterangan' => 'Laporan berhasil didaftarkan ke sistem CampusVoice.',
            'waktu' => now(),
        ]);

        // Buat notifikasi
        AppNotification::create([
            'user_id' => $user->id,
            'laporan_id' => $laporan->id,
            'tiket' => $laporan->nomor_tiket,
            'icon' => 'submitted',
            'title' => 'Laporan berhasil dikirim',
            'desc' => "Laporan {$laporan->nomor_tiket} telah diterima dan menunggu verifikasi SSC.",
            'is_read' => false,
        ]);

        return redirect()->route('laporan.success', ['tiket' => $laporan->nomor_tiket])
            ->with('success', 'Laporan berhasil dikirim!');
    }

    public function success(Request $request, $tiket = null)
    {
        $tiket = $tiket ?: $request->query('tiket');
        $laporan = null;
        if ($tiket) {
            $laporan = Laporan::where('nomor_tiket', $tiket)->first();
        }

        if (!$laporan) {
            $laporan = Laporan::latest()->first();
            $tiket = $laporan ? $laporan->nomor_tiket : null;
        }

        if (!$laporan) {
            return redirect()->route('dashboard');
        }

        $authUser = $this->getActiveUser();
        $unreadCount = AppNotification::where('user_id', $authUser->id)->where('is_read', false)->count();

        return view('laporan.success', compact('laporan', 'tiket', 'authUser', 'unreadCount'));
    }

    public function riwayat(Request $request)
    {
        $authUser = $this->getActiveUser();
        $query = Laporan::where('user_id', $authUser->id)->orderBy('created_at', 'desc');

        if ($request->filled('q')) {
            $q = trim($request->q);
            $query->where(function ($sub) use ($q) {
                $sub->where('nomor_tiket', 'like', "%{$q}%")
                    ->orWhere('judul', 'like', "%{$q}%")
                    ->orWhere('lokasi', 'like', "%{$q}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('kategori')) {
            $query->where(function ($sub) use ($request) {
                $sub->where('kategori', $request->kategori)
                    ->orWhere('kategori_label', 'like', "%{$request->kategori}%");
            });
        }

        $laporans = $query->get();
        $unreadCount = AppNotification::where('user_id', $authUser->id)->where('is_read', false)->count();

        return view('laporan.riwayat', compact('laporans', 'authUser', 'unreadCount'));
    }

    public function detail(Request $request, $tiket = null)
    {
        $tiket = $tiket ?: $request->route('tiket') ?: $request->query('tiket');
        
        $laporan = null;
        if ($tiket) {
            $laporan = Laporan::with(['tanggapans', 'trackings', 'user'])
                ->where('nomor_tiket', $tiket)
                ->first();
        }

        if (!$laporan) {
            $laporan = Laporan::with(['tanggapans', 'trackings', 'user'])
                ->latest()
                ->first();
            $tiket = $laporan ? $laporan->nomor_tiket : null;
        }

        if (!$laporan) {
            return redirect()->route('laporan.riwayat');
        }

        $authUser = $this->getActiveUser();
        $unreadCount = AppNotification::where('user_id', $authUser->id)->where('is_read', false)->count();

        return view('laporan.detail', compact('laporan', 'tiket', 'authUser', 'unreadCount'));
    }

    public function storeTanggapan(Request $request, $tiket)
    {
        $request->validate([
            'pesan' => 'required|string|max:2000',
        ], [
            'pesan.required' => 'Pesan tanggapan tidak boleh kosong.',
        ]);

        $laporan = Laporan::where('nomor_tiket', $tiket)->firstOrFail();
        $authUser = $this->getActiveUser();

        $tanggapan = LaporanTanggapan::create([
            'laporan_id' => $laporan->id,
            'user_id' => $authUser->id,
            'nama_pengirim' => $authUser->name,
            'role_pengirim' => $authUser->role ?: 'Mahasiswa',
            'pesan' => $request->pesan,
        ]);

        // Berikan notifikasi
        AppNotification::create([
            'user_id' => $authUser->id,
            'laporan_id' => $laporan->id,
            'tiket' => $laporan->nomor_tiket,
            'icon' => 'response',
            'title' => 'Tanggapan Anda terkirim',
            'desc' => "Tanggapan baru telah ditambahkan pada laporan {$laporan->nomor_tiket}.",
            'is_read' => true,
        ]);

        return redirect()->route('laporan.detail', $tiket)
            ->with('success', 'Tanggapan berhasil dikirim!');
    }

    public function bukaKembali(Request $request, $tiket)
    {
        $request->validate([
            'alasan' => 'required|string|max:1000',
        ], [
            'alasan.required' => 'Mohon sertakan alasan pengajuan pembukaan kembali laporan.',
        ]);

        $laporan = Laporan::where('nomor_tiket', $tiket)->firstOrFail();
        $authUser = $this->getActiveUser();

        $laporan->update([
            'status' => 'Menunggu Verifikasi',
            'progres' => 30,
        ]);

        LaporanTracking::create([
            'laporan_id' => $laporan->id,
            'status' => 'Pengajuan Buka Kembali',
            'keterangan' => 'Mahasiswa mengajukan pembukaan kembali laporan: "' . $request->alasan . '"',
            'waktu' => now(),
        ]);

        LaporanTanggapan::create([
            'laporan_id' => $laporan->id,
            'user_id' => $authUser->id,
            'nama_pengirim' => $authUser->name,
            'role_pengirim' => $authUser->role ?: 'Mahasiswa',
            'pesan' => '[Pengajuan Buka Kembali] ' . $request->alasan,
        ]);

        AppNotification::create([
            'user_id' => $authUser->id,
            'laporan_id' => $laporan->id,
            'tiket' => $laporan->nomor_tiket,
            'icon' => 'submitted',
            'title' => 'Pengajuan buka kembali terkirim',
            'desc' => "Pengajuan pembukaan tiket {$laporan->nomor_tiket} sedang diverifikasi SSC.",
            'is_read' => true,
        ]);

        return redirect()->route('laporan.detail', $tiket)
            ->with('success', 'Pengajuan pembukaan kembali laporan berhasil diajukan ke Admin SSC.');
    }

    public function simpanPenilaian(Request $request, $tiket)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'ulasan' => 'nullable|string|max:1000',
        ]);

        $laporan = Laporan::where('nomor_tiket', $tiket)->firstOrFail();
        $authUser = $this->getActiveUser();

        $laporan->update([
            'rating' => $request->rating,
            'ulasan' => $request->ulasan,
        ]);

        AppNotification::create([
            'user_id' => $authUser->id,
            'laporan_id' => $laporan->id,
            'tiket' => $laporan->nomor_tiket,
            'icon' => 'completed',
            'title' => 'Penilaian berhasil disimpan',
            'desc' => "Terima kasih telah memberikan ulasan penilaian untuk {$laporan->nomor_tiket}.",
            'is_read' => true,
        ]);

        return redirect()->route('laporan.detail', $tiket)
            ->with('success', 'Terima kasih atas ulasan dan penilaian yang Anda berikan!');
    }
}
