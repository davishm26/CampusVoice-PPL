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

        // Buat notifikasi untuk mahasiswa pelapor
        AppNotification::create([
            'user_id' => $user->id,
            'laporan_id' => $laporan->id,
            'tiket' => $laporan->nomor_tiket,
            'icon' => 'submitted',
            'title' => 'Laporan berhasil dikirim',
            'desc' => "Laporan {$laporan->nomor_tiket} telah diterima dan menunggu verifikasi SSC.",
            'is_read' => false,
        ]);

        // Buat notifikasi untuk Admin SSC
        $this->notifyRole('Admin SSC', [
            'laporan_id' => $laporan->id,
            'tiket' => $laporan->nomor_tiket,
            'icon' => 'submitted',
            'title' => 'Laporan baru masuk',
            'desc' => "Laporan {$laporan->nomor_tiket} ({$laporan->judul}) menunggu verifikasi SSC.",
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

        // Notifikasi untuk Admin SSC
        $this->notifyRole('Admin SSC', [
            'laporan_id' => $laporan->id,
            'tiket' => $laporan->nomor_tiket,
            'icon' => 'submitted',
            'title' => 'Pengajuan buka kembali laporan',
            'desc' => "Pelapor mengajukan buka kembali untuk tiket {$laporan->nomor_tiket}.",
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

    /**
     * Halaman Laporan Ditugaskan untuk Role Petugas Unit.
     */
    public function laporanDitugaskan(Request $request)
    {
        $authUser = Auth::user();
        if (!$authUser || $authUser->role !== 'Petugas Unit') {
            $authUser = User::where('role', 'Petugas Unit')->first() ?? $authUser ?? new User([
                'name' => 'Budi Santoso',
                'role' => 'Petugas Unit',
                'email' => 'budi.santoso@telkomuniversity.ac.id',
                'prodi' => 'Logistik & Sarana',
                'fakultas' => 'Direktorat Logistik dan Aset',
            ]);
        }

        // Ambil laporan yang diteruskan / ditugaskan dari database
        $dbLaporans = Laporan::with('user')
            ->whereIn('status', ['Diteruskan', 'Diproses', 'Selesai'])
            ->orderBy('created_at', 'desc')
            ->get();

        $items = [];
        foreach ($dbLaporans as $l) {
            $kategoriShort = match ($l->kategori) {
                'sarana_prasarana' => 'Satpras',
                'ssc' => 'SSC',
                'akademik' => 'Akademik',
                'kemahasiswaan' => 'Kemahasiswaan',
                'administrasi_keuangan' => 'Keuangan',
                default => ucfirst(str_replace('_', ' ', $l->kategori ?? 'Lainnya')),
            };

            $prioritasShort = match ($l->prioritas) {
                'Prioritas Tinggi', 'Tinggi' => 'Tinggi',
                'Prioritas Sedang', 'Sedang' => 'Sedang',
                'Prioritas Rendah', 'Rendah' => 'Rendah',
                default => 'Sedang',
            };

            $items[] = [
                'tiket' => $l->nomor_tiket,
                'tanggal' => $l->created_at ? $l->created_at->locale('id')->translatedFormat('d M Y') : '18 Okt 2026',
                'judul' => $l->judul,
                'kategori' => $kategoriShort,
                'prioritas' => $prioritasShort,
                'status' => $l->status,
                'progres' => (int) ($l->progres ?? 0),
                'url' => route('unit.laporan-ditugaskan.detail', $l->nomor_tiket),
            ];
        }

        $unreadCount = AppNotification::where('user_id', $authUser?->id)
            ->where('is_read', false)
            ->count();

        return view('unit.laporan-ditugaskan', [
            'authUser' => $authUser,
            'laporans' => $items,
            'unreadCount' => $unreadCount,
        ]);
    }

    /**
     * Halaman Detail Penanganan (Tangani Laporan) untuk Role Petugas Unit.
     */
    public function tanganiLaporan(Request $request, $tiket)
    {
        $authUser = Auth::user();
        if (!$authUser || $authUser->role !== 'Petugas Unit') {
            $authUser = User::where('role', 'Petugas Unit')->first() ?? $authUser ?? new User([
                'name' => 'Budi Santoso',
                'role' => 'Petugas Unit',
                'email' => 'budi.santoso@telkomuniversity.ac.id',
                'prodi' => 'Logistik & Sarana',
                'fakultas' => 'Direktorat Logistik dan Aset',
            ]);
        }

        $dbLaporan = Laporan::with(['user', 'tanggapans', 'trackings'])
            ->where('nomor_tiket', $tiket)
            ->first();

        if ($dbLaporan) {
            $kategoriLabel = match ($dbLaporan->kategori) {
                'sarana_prasarana' => 'Satpras',
                'ssc' => 'SSC',
                'akademik' => 'Akademik',
                'kemahasiswaan' => 'Kemahasiswaan',
                'administrasi_keuangan' => 'Keuangan',
                default => ucfirst(str_replace('_', ' ', $dbLaporan->kategori ?? 'Lainnya')),
            };

            $prioritasLabel = str_starts_with((string) $dbLaporan->prioritas, 'Prioritas')
                ? $dbLaporan->prioritas
                : 'Prioritas ' . ($dbLaporan->prioritas ?: 'Sedang');

            $laporan = [
                'is_db' => true,
                'tiket' => $dbLaporan->nomor_tiket,
                'judul' => $dbLaporan->judul,
                'status' => $dbLaporan->status ?: 'Diproses',
                'prioritas' => $prioritasLabel,
                'is_anonim' => (bool) $dbLaporan->is_anonim,
                'kategori' => $kategoriLabel,
                'lokasi' => $dbLaporan->lokasi ?: 'Kampus Telkom University Surabaya',
                'pelapor' => $dbLaporan->is_anonim ? 'Identitas disembunyikan' : ($dbLaporan->user->name ?? 'Mahasiswa'),
                'deskripsi' => $dbLaporan->deskripsi,
                'berkas_nama' => $dbLaporan->berkas_nama ?: 'foto_lampiran_laporan.jpg',
                'berkas_meta' => $dbLaporan->berkas_ukuran ? ($dbLaporan->berkas_ukuran . ' • FILE') : '1,8 MB • JPG',
                'berkas_url' => $dbLaporan->berkas_bukti ? asset('storage/' . $dbLaporan->berkas_bukti) : null,
                'progres' => (int) ($dbLaporan->progres ?? 65),
            ];

            $riwayats = [];
            foreach ($dbLaporan->tanggapans as $t) {
                $riwayats[] = [
                    'inisial' => $t->pengirim_role === 'Admin SSC' ? 'SS' : ($authUser->initials ?? 'BS'),
                    'nama' => $t->pengirim_nama ?? 'Admin SSC',
                    'role' => $t->pengirim_role ?? 'Admin SSC',
                    'pesan' => $t->pesan,
                    'waktu' => $t->created_at ? $t->created_at->diffForHumans() : 'Baru saja',
                ];
            }

            if (empty($riwayats)) {
                $riwayats[] = [
                    'inisial' => 'SS',
                    'nama' => 'Admin SSC',
                    'role' => 'Admin SSC',
                    'pesan' => 'Laporan telah diverifikasi dan diteruskan ke unit Anda.',
                    'waktu' => 'Baru saja',
                ];
            }
        } else {
            return redirect()->route('unit.laporan-ditugaskan')
                ->with('error', 'Laporan dengan tiket ' . $tiket . ' tidak ditemukan di database.');
        }

        $unreadCount = AppNotification::where('user_id', $authUser?->id)
            ->where('is_read', false)
            ->count();

        return view('unit.tangani-laporan', compact('authUser', 'laporan', 'riwayats', 'unreadCount'));
    }

    /**
     * Proses simpan pembaruan penanganan, tanggapan, atau kembalikan ke SSC.
     */
    public function updatePenangananUnit(Request $request, $tiket)
    {
        $request->validate([
            'status' => 'required|string|max:50',
            'progres' => 'required|integer|min:0|max:100',
            'catatan' => 'nullable|string|max:2000',
            'action_type' => 'nullable|string',
            'bukti_penyelesaian' => 'nullable|file|max:5120',
        ]);

        $authUser = Auth::user();
        if (!$authUser || $authUser->role !== 'Petugas Unit') {
            $authUser = User::where('role', 'Petugas Unit')->first() ?? $authUser;
        }

        $actionType = $request->input('action_type', 'simpan');
        $statusBaru = $actionType === 'kembalikan' ? 'Menunggu Verifikasi' : $request->input('status');
        $progresBaru = $actionType === 'kembalikan' ? 10 : (int) $request->input('progres');
        $catatan = trim((string) $request->input('catatan'));

        if ($actionType === 'kembalikan' && $catatan === '') {
            $catatan = 'Laporan dikembalikan ke Admin SSC untuk verifikasi ulang unit penangan.';
        } elseif ($catatan === '') {
            $catatan = "Status diperbarui menjadi {$statusBaru} dengan progres {$progresBaru}%.";
        }

        $dbLaporan = Laporan::where('nomor_tiket', $tiket)->first();
        if ($dbLaporan) {
            $updateData = [
                'status' => $statusBaru,
                'progres' => $progresBaru,
            ];

            if ($request->hasFile('bukti_penyelesaian')) {
                $path = $request->file('bukti_penyelesaian')->store('bukti-penyelesaian', 'public');
                $updateData['berkas_bukti'] = $path;
                $updateData['berkas_nama'] = $request->file('bukti_penyelesaian')->getClientOriginalName();
            }

            $dbLaporan->update($updateData);

            LaporanTanggapan::create([
                'laporan_id' => $dbLaporan->id,
                'pengirim_nama' => $authUser->name ?? 'Petugas Unit',
                'pengirim_role' => 'Petugas Unit',
                'pesan' => $catatan,
            ]);

            LaporanTracking::create([
                'laporan_id' => $dbLaporan->id,
                'status' => $actionType === 'kembalikan' ? 'Dikembalikan ke Admin SSC' : $statusBaru,
                'keterangan' => $catatan,
                'waktu' => now(),
            ]);

            // Notifikasi untuk Admin SSC
            $this->notifyRole('Admin SSC', [
                'laporan_id' => $dbLaporan->id,
                'tiket' => $dbLaporan->nomor_tiket,
                'icon' => $actionType === 'kembalikan' ? 'forward' : ($statusBaru === 'Selesai' ? 'completed' : 'progress'),
                'title' => $actionType === 'kembalikan'
                    ? 'Laporan dikembalikan ke SSC'
                    : ($statusBaru === 'Selesai' ? 'Laporan selesai ditangani unit' : 'Progres laporan diperbarui'),
                'desc' => $actionType === 'kembalikan'
                    ? "{$dbLaporan->nomor_tiket} dikembalikan oleh unit ke Admin SSC."
                    : "Unit memperbarui {$dbLaporan->nomor_tiket} menjadi {$statusBaru} ({$progresBaru}%).",
            ]);

            // Notifikasi untuk mahasiswa pelapor
            if ($dbLaporan->user_id) {
                AppNotification::create([
                    'user_id' => $dbLaporan->user_id,
                    'laporan_id' => $dbLaporan->id,
                    'tiket' => $dbLaporan->nomor_tiket,
                    'icon' => $statusBaru === 'Selesai' ? 'completed' : 'progress',
                    'title' => $statusBaru === 'Selesai' ? 'Laporan telah selesai' : 'Progres laporan diperbarui',
                    'desc' => "Laporan {$dbLaporan->nomor_tiket} kini berstatus {$statusBaru} ({$progresBaru}%).",
                    'is_read' => false,
                ]);
            }
        }

        $msg = match ($actionType) {
            'kembalikan' => "Laporan {$tiket} berhasil dikembalikan ke Admin SSC.",
            'tanggapan' => "Tanggapan untuk laporan {$tiket} berhasil dikirim.",
            default => "Pembaruan penanganan laporan {$tiket} berhasil disimpan ({$statusBaru}, {$progresBaru}%).",
        };

        return redirect()->route('unit.laporan-ditugaskan.detail', $tiket)->with('success', $msg);
    }

    /**
     * Admin SSC: Daftar Verifikasi Laporan.
     */
    public function adminVerifikasi()
    {
        $authUser = Auth::user();
        if (!$authUser || $authUser->role !== 'Admin SSC') {
            $authUser = User::where('role', 'Admin SSC')->first() ?? $authUser ?? new User([
                'name' => 'Admin SSC',
                'role' => 'Admin SSC',
                'email' => 'admin.ssc@telkomuniversity.ac.id',
            ]);
        }

        $laporans = Laporan::with('user')
            ->where('status', 'Menunggu Verifikasi')
            ->orderBy('created_at', 'desc')
            ->get();

        $unreadCount = AppNotification::where('user_id', $authUser?->id)
            ->where('is_read', false)
            ->count();

        return view('admin.verifikasi', compact('authUser', 'laporans', 'unreadCount'));
    }

    /**
     * Admin SSC: Detail Verifikasi Laporan.
     */
    public function adminVerifikasiDetail($tiket)
    {
        $authUser = Auth::user();
        if (!$authUser || $authUser->role !== 'Admin SSC') {
            $authUser = User::where('role', 'Admin SSC')->first() ?? $authUser ?? new User([
                'name' => 'Admin SSC',
                'role' => 'Admin SSC',
                'email' => 'admin.ssc@telkomuniversity.ac.id',
            ]);
        }

        $dbLaporan = Laporan::with('user')->where('nomor_tiket', $tiket)->first();
        if (!$dbLaporan) {
            return redirect()->route('admin.verifikasi')
                ->with('error', 'Laporan dengan tiket ' . $tiket . ' tidak ditemukan.');
        }

        $laporan = [
            'tiket' => $dbLaporan->nomor_tiket,
            'judul' => $dbLaporan->judul,
            'waktu' => $dbLaporan->created_at ? 'Diterima ' . $dbLaporan->created_at->locale('id')->translatedFormat('d M Y, H.i') . ' WIB' : '-',
            'anonim' => (bool) $dbLaporan->is_anonim,
            'kategori' => $dbLaporan->short_kategori ?? $dbLaporan->kategori_label ?? $dbLaporan->kategori,
            'lokasi' => $dbLaporan->lokasi ?? '-',
            'pelapor' => trim(($dbLaporan->user->name ?? 'Mahasiswa') . ($dbLaporan->user?->nim ? ' • ' . $dbLaporan->user->nim : '')),
            'prodi' => $dbLaporan->user->prodi ?? '-',
            'deskripsi' => $dbLaporan->deskripsi,
            'lampiran_nama' => $dbLaporan->berkas_nama ?? '',
            'lampiran_meta' => $dbLaporan->berkas_ukuran ?? '',
            'lampiran_url' => $dbLaporan->berkas_bukti ? asset('storage/' . $dbLaporan->berkas_bukti) : null,
            'rekomendasi_unit' => $dbLaporan->unit_penangan ?? 'Belum ditentukan',
            'rekomendasi_alasan' => 'Berdasarkan kategori laporan dan program studi pelapor.',
            'dasar_penyaluran' => 'Penyaluran disesuaikan dengan kategori laporan.',
            'status' => $dbLaporan->status,
        ];

        $unreadCount = AppNotification::where('user_id', $authUser?->id)
            ->where('is_read', false)
            ->count();

        return view('admin.verifikasi-detail', compact('authUser', 'laporan', 'tiket', 'unreadCount'));
    }

    /**
     * Admin SSC: Proses Verifikasi & Penyaluran / Penolakan Laporan.
     */
    public function adminVerifikasiSubmit(Request $request, $tiket)
    {
        $request->validate([
            'action' => 'required|in:teruskan,tolak',
            'prioritas' => 'nullable|string|max:50',
            'unit_penangan' => 'nullable|string|max:255',
            'catatan' => 'nullable|string|max:2000',
        ]);

        $dbLaporan = Laporan::where('nomor_tiket', $tiket)->firstOrFail();
        $action = $request->input('action');

        if ($action === 'teruskan') {
            $unitPenangan = $request->input('unit_penangan') ?: ($dbLaporan->unit_penangan ?: 'Unit Penangan Terkait');
            $prioritas = $request->input('prioritas') ?: 'Sedang';
            $catatan = $request->input('catatan');

            $dbLaporan->update([
                'status' => 'Diteruskan',
                'prioritas' => $prioritas,
                'unit_penangan' => $unitPenangan,
                'progres' => max(20, (int) ($dbLaporan->progres ?? 0)),
            ]);

            LaporanTracking::create([
                'laporan_id' => $dbLaporan->id,
                'status' => 'Diteruskan ke Unit',
                'keterangan' => "Laporan diverifikasi oleh Admin SSC dan diteruskan ke {$unitPenangan}." . ($catatan ? " Catatan: {$catatan}" : ''),
                'waktu' => now(),
            ]);

            if ($dbLaporan->user_id) {
                AppNotification::create([
                    'user_id' => $dbLaporan->user_id,
                    'laporan_id' => $dbLaporan->id,
                    'icon' => 'forward',
                    'title' => 'Laporan telah diteruskan',
                    'desc' => "{$tiket} telah diverifikasi dan diteruskan ke {$unitPenangan}.",
                    'tiket' => $tiket,
                    'is_read' => false,
                ]);
            }

            // Notifikasi untuk Petugas Unit terkait
            $this->notifyRole('Petugas Unit', [
                'laporan_id' => $dbLaporan->id,
                'tiket' => $tiket,
                'icon' => 'forward',
                'title' => 'Laporan baru ditugaskan',
                'desc' => "{$tiket} ({$dbLaporan->judul}) telah diteruskan ke {$unitPenangan}.",
            ], $unitPenangan);

            $message = "Laporan {$tiket} berhasil diverifikasi dan diteruskan ke {$unitPenangan}.";
        } else {
            $alasan = $request->input('catatan') ?: 'Laporan ditolak oleh Admin SSC.';

            $dbLaporan->update([
                'status' => 'Ditolak',
            ]);

            LaporanTracking::create([
                'laporan_id' => $dbLaporan->id,
                'status' => 'Ditolak',
                'keterangan' => $alasan,
                'waktu' => now(),
            ]);

            if ($dbLaporan->user_id) {
                AppNotification::create([
                    'user_id' => $dbLaporan->user_id,
                    'icon' => 'rejected',
                    'title' => 'Laporan ditolak',
                    'desc' => "{$tiket} ditolak oleh Admin SSC: {$alasan}",
                    'tiket' => $tiket,
                    'is_read' => false,
                ]);
            }

            $message = "Laporan {$tiket} telah ditolak.";
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'redirect' => route('admin.verifikasi'),
            ]);
        }

        return redirect()->route('admin.verifikasi')->with('success', $message);
    }

    /**
     * Admin SSC: Monitoring Laporan.
     */
    public function adminMonitoring()
    {
        $authUser = Auth::user();
        if (!$authUser || $authUser->role !== 'Admin SSC') {
            $authUser = User::where('role', 'Admin SSC')->first() ?? $authUser ?? new User([
                'name' => 'Admin SSC',
                'role' => 'Admin SSC',
                'email' => 'admin.ssc@telkomuniversity.ac.id',
            ]);
        }

        $allLaporans = Laporan::orderBy('created_at', 'desc')->get();

        $rows = $allLaporans->map(function ($l) {
            $prioritasShort = match ($l->prioritas) {
                'Prioritas Tinggi', 'Tinggi' => 'Tinggi',
                'Prioritas Sedang', 'Sedang' => 'Sedang',
                'Prioritas Rendah', 'Rendah' => 'Rendah',
                default => '',
            };

            $days = $l->created_at ? (int) $l->created_at->diffInDays(now()) : 0;
            $overdue = $days >= 7 && !in_array($l->status, ['Selesai', 'Ditolak']);

            return [
                'tiket' => $l->nomor_tiket,
                'judul' => $l->judul,
                'kategori' => $l->short_kategori ?? $l->kategori_label ?? $l->kategori ?? 'Umum',
                'prioritas' => $prioritasShort,
                'unit' => $l->unit_penangan ?? 'Belum Ditentukan',
                'dikirim' => $l->created_at ? $l->created_at->locale('id')->translatedFormat('d M Y') : '-',
                'tanggal_ymd' => $l->created_at ? $l->created_at->format('Y-m-d') : '',
                'diperbarui' => $l->updated_at ? 'Update: ' . $l->updated_at->locale('id')->translatedFormat('d M Y, H.i') : '-',
                'durasi' => $days . ' hari',
                'overdue' => $overdue,
                'status' => $l->status,
            ];
        })->values()->all();

        $stats = [
            'total' => $allLaporans->count(),
            'menunggu' => $allLaporans->where('status', 'Menunggu Verifikasi')->count(),
            'diteruskan' => $allLaporans->where('status', 'Diteruskan')->count(),
            'diproses' => $allLaporans->where('status', 'Diproses')->count(),
            'selesai' => $allLaporans->where('status', 'Selesai')->count(),
            'ditolak' => $allLaporans->where('status', 'Ditolak')->count(),
            'overdueCount' => collect($rows)->where('overdue', true)->count(),
        ];

        $unreadCount = AppNotification::where('user_id', $authUser?->id)
            ->where('is_read', false)
            ->count();

        return view('admin.monitoring', compact('authUser', 'rows', 'stats', 'unreadCount'));
    }

    /**
     * Admin SSC: Rekapitulasi Pengaduan.
     */
    public function adminRekapitulasi()
    {
        $authUser = Auth::user();
        if (!$authUser || $authUser->role !== 'Admin SSC') {
            $authUser = User::where('role', 'Admin SSC')->first() ?? $authUser ?? new User([
                'name' => 'Admin SSC',
                'role' => 'Admin SSC',
                'email' => 'admin.ssc@telkomuniversity.ac.id',
            ]);
        }

        $laporans = Laporan::all();
        $rows = $laporans->groupBy(fn ($l) => $l->unit_penangan ?: 'Belum Ditentukan')
            ->map(function ($group, $unit) {
                $total = $group->count();
                $selesai = $group->where('status', 'Selesai')->count();
                $diproses = $group->whereIn('status', ['Diproses', 'Diteruskan', 'Menunggu Verifikasi'])->count();
                $avgDays = $group->avg(fn ($l) => $l->created_at ? max(1, $l->created_at->diffInDays($l->updated_at ?? now())) : 1) ?? 0;
                $avgDaysRounded = round($avgDays, 1);
                $rate = $total > 0 ? round(($selesai / $total) * 100, 1) : 0;
                $firstKategori = $group->first()?->kategori_label ?? $group->first()?->kategori ?? 'Umum';

                return [
                    'unit' => $unit,
                    'shortUnit' => strlen($unit) > 22 ? substr($unit, 0, 20) . '…' : $unit,
                    'kategori' => $firstKategori,
                    'total' => $total,
                    'diproses' => $diproses,
                    'selesai' => $selesai,
                    'durasi' => number_format($avgDaysRounded, 1, ',', '.') . ' hari',
                    'durasiNum' => $avgDaysRounded,
                    'tingkat' => number_format($rate, 1, ',', '.') . '%',
                ];
            })->values()->all();

        $unreadCount = AppNotification::where('user_id', $authUser?->id)
            ->where('is_read', false)
            ->count();

        return view('admin.rekapitulasi', compact('authUser', 'rows', 'unreadCount'));
    }

    /**
     * Petugas Unit: Riwayat Penanganan.
     */
    public function petugasRiwayat()
    {
        $authUser = Auth::user();
        if (!$authUser || $authUser->role !== 'Petugas Unit') {
            $authUser = User::where('role', 'Petugas Unit')->first() ?? $authUser ?? new User([
                'name' => 'Petugas Unit',
                'role' => 'Petugas Unit',
                'email' => 'petugas.unit@telkomuniversity.ac.id',
            ]);
        }

        $query = Laporan::whereIn('status', ['Diteruskan', 'Diproses', 'Selesai']);
        if (!empty($authUser->unit)) {
            $query->where(function ($q) use ($authUser) {
                $q->where('unit_penangan', $authUser->unit)
                  ->orWhereNull('unit_penangan');
            });
        }

        $rows = $query->orderBy('updated_at', 'desc')->get()->map(function ($l) {
            $prioritasShort = match ($l->prioritas) {
                'Prioritas Tinggi', 'Tinggi' => 'Tinggi',
                'Prioritas Sedang', 'Sedang' => 'Sedang',
                'Prioritas Rendah', 'Rendah' => 'Rendah',
                default => 'Sedang',
            };

            $days = $l->created_at ? max(1, (int) $l->created_at->diffInDays($l->status === 'Selesai' ? ($l->updated_at ?? now()) : now())) : 1;

            return [
                'tiket' => $l->nomor_tiket,
                'judul' => $l->judul,
                'diterima' => $l->created_at ? $l->created_at->locale('id')->translatedFormat('d M Y') : '-',
                'selesai' => $l->status === 'Selesai' && $l->updated_at ? $l->updated_at->locale('id')->translatedFormat('d M Y') : '—',
                'status' => $l->status,
                'durasi' => $l->status === 'Selesai' ? "{$days} hari" : "{$days} hari berjalan",
                'kategori' => $l->kategori_label ?? $l->kategori ?? 'Umum',
                'prioritas' => $prioritasShort,
            ];
        })->values()->all();

        $unreadCount = AppNotification::where('user_id', $authUser?->id)
            ->where('is_read', false)
            ->count();

        return view('petugas.riwayat-penanganan', compact('authUser', 'rows', 'unreadCount'));
    }

    /**
     * Kirim notifikasi ke seluruh user dengan role tertentu (opsional filter unit).
     */
    private function notifyRole(string $role, array $payload, ?string $unit = null): void
    {
        $query = User::where('role', $role);
        if ($unit) {
            $query->where(function ($q) use ($unit) {
                $q->where('unit', $unit)->orWhereNull('unit');
            });
        }

        foreach ($query->get() as $recipient) {
            AppNotification::create(array_merge($payload, [
                'user_id' => $recipient->id,
                'is_read' => false,
            ]));
        }
    }
}
