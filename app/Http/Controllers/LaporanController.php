<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use App\Models\KategoriPenyaluran;
use App\Models\Laporan;
use App\Models\LaporanTanggapan;
use App\Models\LaporanTracking;
use App\Models\LogAktivitas;
use App\Models\UnitPenangan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
            $bytes = $file->getSize();
            $fileSize = $bytes >= 1048576
                ? number_format($bytes / 1048576, 2) . ' MB'
                : number_format(max(1, $bytes / 1024), 0) . ' KB';
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

        // Catat ke log aktivitas
        LogAktivitas::catat(
            aktivitas: "Pengajuan laporan baru: {$laporan->judul}",
            recordTerkait: $laporan->nomor_tiket,
            kategori: $kategoriLabel,
            status: 'Berhasil',
            user: $user,
            ipAddress: $request->ip()
        );

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

    /**
     * Sajikan berkas bukti pendukung laporan dari storage public secara aman.
     */
    public function showBukti($tiket)
    {
        $laporan = Laporan::where('nomor_tiket', $tiket)->firstOrFail();

        if (!$laporan->berkas_bukti || !Storage::disk('public')->exists($laporan->berkas_bukti)) {
            abort(404, 'Berkas bukti tidak ditemukan.');
        }

        return response()->file(Storage::disk('public')->path($laporan->berkas_bukti));
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

        // Ambil laporan aktif yang ditugaskan ke unit (belum Selesai)
        $unitFilter = function ($q) use ($authUser) {
            if ($authUser && $authUser->unit_penangan_id) {
                $q->where(function ($sub) use ($authUser) {
                    $sub->where('unit_penangan_id', $authUser->unit_penangan_id)
                        ->orWhere('unit_penangan', $authUser->unitPenangan?->nama_unit ?? $authUser->fakultas);
                });
            } elseif ($authUser && $authUser->fakultas) {
                $q->where('unit_penangan', 'like', '%' . $authUser->fakultas . '%');
            }
        };

        $dbLaporans = Laporan::with(['user', 'unitPenangan'])
            ->whereIn('status', ['Diteruskan', 'Diproses'])
            ->tap($unitFilter)
            ->orderBy('created_at', 'desc')
            ->get();

        // Fallback hanya jika unit ini sama sekali belum punya laporan (termasuk yang Selesai) pada DB demo/testing
        $hasAnyUnitLaporan = Laporan::whereIn('status', ['Diteruskan', 'Diproses', 'Selesai'])
            ->tap($unitFilter)
            ->exists();

        if ($dbLaporans->isEmpty() && !$hasAnyUnitLaporan) {
            $dbLaporans = Laporan::with('user')
                ->whereIn('status', ['Diteruskan', 'Diproses'])
                ->orderBy('created_at', 'desc')
                ->get();
        }

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
                'tanggal' => $l->created_at ? $l->created_at->wib()->isoFormat('D MMM YYYY') : '18 Okt 2026',
                'tanggal_ymd' => $l->created_at ? $l->created_at->wib()->format('Y-m-d') : '',
                'judul' => $l->judul,
                'kategori' => $kategoriShort,
                'prioritas' => $prioritasShort,
                'status' => $l->status,
                'is_anonim' => (bool) $l->is_anonim,
                'progres' => $l->display_progres,
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

            $statusAktif = $dbLaporan->status ?: 'Diproses';

            $laporan = [
                'is_db' => true,
                'tiket' => $dbLaporan->nomor_tiket,
                'judul' => $dbLaporan->judul,
                'status' => $statusAktif,
                'prioritas' => $prioritasLabel,
                'is_anonim' => (bool) $dbLaporan->is_anonim,
                'kategori' => $kategoriLabel,
                'lokasi' => $dbLaporan->lokasi ?: 'Kampus Telkom University Surabaya',
                'pelapor' => $dbLaporan->is_anonim ? 'Identitas disembunyikan' : ($dbLaporan->user->name ?? 'Mahasiswa'),
                'deskripsi' => $dbLaporan->deskripsi,
                'berkas_nama' => $dbLaporan->berkas_nama ?: ($dbLaporan->berkas_bukti ? basename($dbLaporan->berkas_bukti) : ''),
                'berkas_meta' => $dbLaporan->berkas_ukuran ? ($dbLaporan->berkas_ukuran . ($dbLaporan->berkas_extension ? ' • ' . strtoupper($dbLaporan->berkas_extension) : '')) : '',
                'berkas_url' => $dbLaporan->berkas_url,
                'berkas_ext' => $dbLaporan->berkas_extension,
                'progres' => Laporan::resolveProgresForStatus($statusAktif, $dbLaporan->progres),
                'selesai_pada' => $dbLaporan->updated_at ? $dbLaporan->updated_at->wib()->isoFormat('D MMMM YYYY, HH.mm') . ' WIB' : '-',
                'rating' => $dbLaporan->rating,
                'ulasan' => $dbLaporan->ulasan,
            ];

            $trackings = $dbLaporan->trackings;

            $riwayats = [];
            foreach ($dbLaporan->tanggapans as $t) {
                $isPelaporAnonim = $dbLaporan->is_anonim && (
                    ($t->role_pengirim ?? '') === 'Mahasiswa'
                    || ($t->user_id && $t->user_id === $dbLaporan->user_id)
                );

                if ($isPelaporAnonim) {
                    $riwayats[] = [
                        'inisial' => 'PA',
                        'nama' => 'Pelapor Anonim',
                        'role' => 'Pelapor (Anonim)',
                        'pesan' => $t->pesan,
                        'waktu' => $t->created_at ? $t->created_at->wib()->diffForHumans() : 'Baru saja',
                    ];
                } else {
                    $riwayats[] = [
                        'inisial' => $t->initials,
                        'nama' => $t->nama_pengirim ?: 'Admin SSC',
                        'role' => $t->role_pengirim ?: 'Admin SSC',
                        'pesan' => $t->pesan,
                        'waktu' => $t->created_at ? $t->created_at->wib()->diffForHumans() : 'Baru saja',
                    ];
                }
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

        return view('unit.tangani-laporan', compact('authUser', 'laporan', 'riwayats', 'trackings', 'unreadCount'));
    }

    /**
     * Proses simpan pembaruan penanganan, tanggapan, atau kembalikan ke SSC.
     */
    public function updatePenangananUnit(Request $request, $tiket)
    {
        $dbLaporan = Laporan::where('nomor_tiket', $tiket)->first();
        if ($dbLaporan && !in_array($dbLaporan->status, ['Diteruskan', 'Diproses'], true)) {
            $alasanTerkunci = $dbLaporan->status === 'Selesai'
                ? "Laporan {$tiket} sudah selesai ditangani dan tidak dapat diperbarui lagi."
                : "Laporan {$tiket} telah dikembalikan ke Admin SSC dan tidak dapat diperbarui oleh unit.";

            return redirect()->route('unit.laporan-ditugaskan.detail', $tiket)
                ->with('error', $alasanTerkunci);
        }

        $actionType = $request->input('action_type', 'simpan');

        $authUser = Auth::user();
        if (!$authUser || $authUser->role !== 'Petugas Unit') {
            $authUser = User::where('role', 'Petugas Unit')->first() ?? $authUser;
        }

        if ($actionType === 'tanggapan') {
            $request->validate([
                'catatan' => 'required|string|max:2000',
            ]);

            $pesan = trim((string) $request->input('catatan'));

            if ($dbLaporan) {
                LaporanTanggapan::create([
                    'laporan_id' => $dbLaporan->id,
                    'user_id' => $authUser?->id,
                    'nama_pengirim' => $authUser->name ?? 'Petugas Unit',
                    'role_pengirim' => 'Petugas Unit',
                    'pesan' => $pesan,
                ]);

                $this->notifyRole('Admin SSC', [
                    'laporan_id' => $dbLaporan->id,
                    'tiket' => $dbLaporan->nomor_tiket,
                    'icon' => 'response',
                    'title' => 'Tanggapan baru dari unit',
                    'desc' => "Petugas unit mengirim tanggapan pada laporan {$dbLaporan->nomor_tiket}.",
                ]);

                if ($dbLaporan->user_id) {
                    AppNotification::create([
                        'user_id' => $dbLaporan->user_id,
                        'laporan_id' => $dbLaporan->id,
                        'tiket' => $dbLaporan->nomor_tiket,
                        'icon' => 'response',
                        'title' => 'Tanggapan baru dari unit',
                        'desc' => "Petugas unit memberikan tanggapan pada laporan {$dbLaporan->nomor_tiket}.",
                        'is_read' => false,
                    ]);
                }

                LogAktivitas::catat(
                    aktivitas: "Memberikan tanggapan pada laporan {$tiket}",
                    recordTerkait: $tiket,
                    kategori: 'Tanggapan',
                    status: 'Berhasil',
                    user: $authUser,
                    ipAddress: $request->ip()
                );
            }

            return redirect()->route('unit.laporan-ditugaskan.detail', $tiket)
                ->with('success', "Tanggapan untuk laporan {$tiket} berhasil dikirim.");
        }

        $request->validate([
            'status' => 'required|string|max:50',
            'progres' => 'required|integer|min:0|max:100',
            'catatan' => 'nullable|string|max:2000',
            'action_type' => 'nullable|string',
            'bukti_penyelesaian' => 'nullable|file|max:5120',
        ]);

        $statusBaru = $actionType === 'kembalikan' ? 'Menunggu Verifikasi' : $request->input('status');
        $progresInput = (int) $request->input('progres');

        if ($actionType === 'kembalikan') {
            $progresBaru = 10;
        } else {
            if ($statusBaru !== 'Selesai' && $progresInput >= 100) {
                $statusBaru = 'Selesai';
            }
            $progresBaru = $statusBaru === 'Menunggu Verifikasi'
                ? 10
                : Laporan::resolveProgresForStatus($statusBaru, $progresInput);
        }

        $catatan = trim((string) $request->input('catatan'));

        if ($actionType === 'kembalikan' && $catatan === '') {
            $catatan = 'Laporan dikembalikan ke Admin SSC untuk verifikasi ulang unit penangan.';
        } elseif ($catatan === '') {
            $catatan = "Status diperbarui menjadi {$statusBaru} dengan progres {$progresBaru}%.";
        }

        if ($dbLaporan) {
            $updateData = [
                'status' => $statusBaru,
                'progres' => $progresBaru,
            ];

            if ($actionType === 'kembalikan') {
                $updateData['unit_penangan'] = 'Belum Ditentukan';
                $updateData['unit_penangan_id'] = null;
            }

            if ($request->hasFile('bukti_penyelesaian')) {
                $file = $request->file('bukti_penyelesaian');
                $path = $file->store('bukti-penyelesaian', 'public');
                $bytes = $file->getSize();
                $updateData['berkas_bukti'] = $path;
                $updateData['berkas_nama'] = $file->getClientOriginalName();
                $updateData['berkas_ukuran'] = $bytes >= 1048576
                    ? number_format($bytes / 1048576, 2) . ' MB'
                    : number_format(max(1, $bytes / 1024), 0) . ' KB';
            }

            $dbLaporan->update($updateData);

            LaporanTanggapan::create([
                'laporan_id' => $dbLaporan->id,
                'user_id' => $authUser?->id,
                'nama_pengirim' => $authUser->name ?? 'Petugas Unit',
                'role_pengirim' => 'Petugas Unit',
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

            // Catat log aktivitas petugas unit
            $aktivitasLog = match ($actionType) {
                'kembalikan' => "Mengembalikan laporan {$tiket} ke Admin SSC",
                'tanggapan' => "Memberikan tanggapan pada laporan {$tiket}",
                default => $statusBaru === 'Selesai'
                    ? "Menyelesaikan penanganan laporan {$tiket}"
                    : "Pembaruan progres laporan {$tiket} ({$progresBaru}%)",
            };

            LogAktivitas::catat(
                aktivitas: $aktivitasLog,
                recordTerkait: $tiket,
                kategori: $actionType === 'kembalikan' ? 'Penyaluran' : 'Progres',
                status: 'Berhasil',
                user: $authUser,
                ipAddress: $request->ip()
            );
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
     * Tentukan rekomendasi unit penangan, jenis unit, alasan, dan dasar penyaluran
     * berdasarkan tabel KategoriPenyaluran yang dikelola oleh Super Admin.
     */
    protected function resolveRekomendasiPenyaluran(Laporan $dbLaporan): array
    {
        $prodiPelapor = $dbLaporan->user?->prodi ?: 'S1 Sistem Informasi';
        $prodiUnitName = str_starts_with($prodiPelapor, 'Program Studi')
            ? $prodiPelapor
            : 'Program Studi ' . $prodiPelapor;

        $matchedRule = null;

        try {
            if (KategoriPenyaluran::count() === 0 && class_exists(\Database\Seeders\KategoriPenyaluranSeeder::class)) {
                (new \Database\Seeders\KategoriPenyaluranSeeder())->run();
            }

            $activeRules = KategoriPenyaluran::where('status', 'Aktif')
                ->orderBy('urutan')
                ->orderBy('id')
                ->get();

            $haystack = mb_strtolower(trim(
                ($dbLaporan->judul ?? '') . ' ' .
                ($dbLaporan->deskripsi ?? '') . ' ' .
                ($dbLaporan->kategori_label ?? '')
            ));

            // 1. Cocokkan frasa kata kunci dari setiap aturan aktif terhadap judul & deskripsi laporan
            foreach ($activeRules as $rule) {
                $keywords = array_filter(array_map('trim', explode(',', mb_strtolower($rule->kategori_kata_kunci))));
                foreach ($keywords as $kw) {
                    if ($kw !== '' && mb_strlen($kw) >= 3 && str_contains($haystack, $kw)) {
                        $matchedRule = $rule;
                        break 2;
                    }
                }
            }

            // 2. Jika belum ada frasa spesifik yang cocok, cocokkan berdasarkan kategori laporan
            if (!$matchedRule && $activeRules->isNotEmpty()) {
                $catKey = mb_strtolower($dbLaporan->kategori ?? '');
                $categoryHints = match ($catKey) {
                    'sarana_prasarana' => ['fasilitas', 'sarana', 'logistik'],
                    'administrasi_keuangan' => ['keuangan', 'ukt', 'pembayaran'],
                    'kemahasiswaan' => ['kemahasiswaan', 'organisasi', 'beasiswa'],
                    'akademik' => ['akademik', 'perkuliahan', 'transkrip', 'krs', 'kurikulum'],
                    'ssc', 'lainnya' => ['aspirasi', 'umum', 'lainnya', 'pelayanan'],
                    default => [],
                };

                foreach ($activeRules as $rule) {
                    $ruleText = mb_strtolower($rule->kategori_kata_kunci . ' ' . $rule->rekomendasi_default);
                    foreach ($categoryHints as $hint) {
                        if (str_contains($ruleText, $hint)) {
                            $matchedRule = $rule;
                            break 2;
                        }
                    }
                }
            }
        } catch (\Throwable) {
            $matchedRule = null;
        }

        if ($matchedRule) {
            $rawDefault = trim($matchedRule->rekomendasi_default);
            if (strcasecmp($rawDefault, 'Satpras') === 0) {
                $rawDefault = 'SATPRAS (Sarana Prasarana)';
            }

            $isProdiPelapor = $matchedRule->tipe_tujuan === 'Program Studi'
                && (stripos($rawDefault, 'pelapor') !== false || strcasecmp($rawDefault, 'Program Studi') === 0);

            if ($isProdiPelapor || stripos($rawDefault, 'pelapor') !== false) {
                $rekomendasiUnit = $dbLaporan->is_anonim ? 'Direktorat Akademik' : $prodiUnitName;
            } else {
                $rekomendasiUnit = $rawDefault;
            }

            $jenisUnit = match (true) {
                str_starts_with($rekomendasiUnit, 'Program Studi') => 'Prodi',
                stripos($rekomendasiUnit, 'SATPRAS') !== false => 'Satpras',
                default => 'Direktorat',
            };

            return [
                'rekomendasi_unit' => $rekomendasiUnit,
                'rekomendasi_jenis_unit' => $jenisUnit,
                'rekomendasi_alasan' => "Sesuai aturan penyaluran aktif untuk kata kunci \"{$matchedRule->kategori_kata_kunci}\" ({$matchedRule->tipe_tujuan}).",
                'dasar_penyaluran' => "Aturan Penyaluran: {$matchedRule->kategori_kata_kunci} → {$rekomendasiUnit}",
            ];
        }

        // Fallback berdasarkan kategori laporan jika tabel aturan kosong atau semua aturan terkait nonaktif
        $existingUnit = $dbLaporan->unit_penangan;
        if ($existingUnit && !in_array($existingUnit, ['Belum Ditentukan', 'Belum ditentukan', '-'], true)) {
            $rekomendasiUnit = $existingUnit;
        } else {
            $rekomendasiUnit = match ($dbLaporan->kategori) {
                'sarana_prasarana' => 'Direktorat Logistik dan Aset',
                'administrasi_keuangan' => 'Direktorat Keuangan',
                'kemahasiswaan' => 'Direktorat Kemahasiswaan',
                'akademik' => $dbLaporan->is_anonim ? 'Direktorat Akademik' : $prodiUnitName,
                default => 'Direktorat Sekretariat & Perencanaan Strategis',
            };
        }

        $jenisUnit = match (true) {
            str_starts_with($rekomendasiUnit, 'Program Studi') => 'Prodi',
            stripos($rekomendasiUnit, 'SATPRAS') !== false => 'Satpras',
            default => 'Direktorat',
        };

        return [
            'rekomendasi_unit' => $rekomendasiUnit,
            'rekomendasi_jenis_unit' => $jenisUnit,
            'rekomendasi_alasan' => 'Berdasarkan kategori laporan dan program studi pelapor.',
            'dasar_penyaluran' => 'Penyaluran disesuaikan dengan kategori laporan.',
        ];
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

        if ($dbLaporan->status !== 'Menunggu Verifikasi') {
            return redirect()->route('admin.monitoring.detail', $dbLaporan->nomor_tiket);
        }

        $rekomendasi = $this->resolveRekomendasiPenyaluran($dbLaporan);

        $laporan = [
            'tiket' => $dbLaporan->nomor_tiket,
            'judul' => $dbLaporan->judul,
            'waktu' => $dbLaporan->created_at ? 'Diterima ' . $dbLaporan->created_at->wib()->isoFormat('D MMM YYYY, HH.mm') . ' WIB' : '-',
            'anonim' => (bool) $dbLaporan->is_anonim,
            'kategori' => $dbLaporan->short_kategori ?? $dbLaporan->kategori_label ?? $dbLaporan->kategori,
            'lokasi' => $dbLaporan->lokasi ?? '-',
            'pelapor' => $dbLaporan->is_anonim
                ? 'Identitas disembunyikan'
                : trim(($dbLaporan->user->name ?? 'Mahasiswa') . ($dbLaporan->user?->nim ? ' • ' . $dbLaporan->user->nim : '')),
            'prodi' => $dbLaporan->is_anonim ? '-' : ($dbLaporan->user->prodi ?? '-'),
            'deskripsi' => $dbLaporan->deskripsi,
            'lampiran_nama' => $dbLaporan->berkas_nama ?: ($dbLaporan->berkas_bukti ? basename($dbLaporan->berkas_bukti) : ''),
            'lampiran_meta' => $dbLaporan->berkas_ukuran ?? '',
            'lampiran_url' => $dbLaporan->berkas_url,
            'lampiran_ext' => $dbLaporan->berkas_extension,
            'rekomendasi_unit' => $rekomendasi['rekomendasi_unit'],
            'rekomendasi_jenis_unit' => $rekomendasi['rekomendasi_jenis_unit'],
            'rekomendasi_alasan' => $rekomendasi['rekomendasi_alasan'],
            'dasar_penyaluran' => $rekomendasi['dasar_penyaluran'],
            'status' => $dbLaporan->status,
        ];

        $unreadCount = AppNotification::where('user_id', $authUser?->id)
            ->where('is_read', false)
            ->count();

        // Ambil unit penangan aktif secara dinamis dari database (dengan fallback jika tabel belum di-seed)
        $activeUnits = UnitPenangan::where('status', 'Aktif')->orderBy('nama_unit')->get();
        $prodiList = $activeUnits->where('tipe', 'Program Studi')->pluck('nama_unit')->values()->all();
        $direktoratList = $activeUnits->where('tipe', 'Direktorat')->pluck('nama_unit')->values()->all();

        if (empty($prodiList)) {
            $prodiList = [
                'Program Studi S1 Sistem Informasi',
                'Program Studi S1 Teknologi Informasi',
                'Program Studi S1 Teknik Informatika',
                'Program Studi S1 Rekayasa Perangkat Lunak',
                'Program Studi S1 Sains Data',
                'Program Studi S1 Teknik Elektro',
                'Program Studi S1 Teknik Industri',
                'Program Studi S1 Teknik Komputer',
                'Program Studi S1 Teknik Logistik',
                'Program Studi S1 Bisnis Digital',
                'Program Studi S1 Teknik Telekomunikasi',
            ];
        }

        if (empty($direktoratList)) {
            $direktoratList = [
                'Direktorat Sekretariat & Perencanaan Strategis',
                'Direktorat Bandung Techno Park',
                'Direktorat Akademik',
                'Direktorat Pasca Sarjana dan Advance Learning',
                'Direktorat Logistik dan Aset',
                'Direktorat Keuangan',
                'Direktorat Sumber Daya Manusia',
                'Direktorat Pusat Teknologi Informasi',
                'Direktorat Pemasaran & Admisi',
                'Direktorat Pengembangan Karir, Alumni, & Endowment',
                'Direktorat Kemahasiswaan',
                'Direktorat Kerjasama Strategis & Kantor Urusan Internasional',
                'Direktorat Penelitian dan Pengabdian Masyarakat',
            ];
        }

        // Pastikan unit rekomendasi dari aturan Super Admin juga ada di dalam daftar opsi
        if ($rekomendasi['rekomendasi_jenis_unit'] === 'Prodi' && !in_array($rekomendasi['rekomendasi_unit'], $prodiList, true)) {
            $prodiList[] = $rekomendasi['rekomendasi_unit'];
        } elseif ($rekomendasi['rekomendasi_jenis_unit'] === 'Direktorat' && !in_array($rekomendasi['rekomendasi_unit'], $direktoratList, true)) {
            $direktoratList[] = $rekomendasi['rekomendasi_unit'];
        }

        $unitOptions = [
            'Prodi' => $prodiList,
            'Direktorat' => $direktoratList,
            'Satpras' => ['Direktorat Logistik dan Aset', 'SATPRAS (Sarana Prasarana)'],
        ];

        return view('admin.verifikasi-detail', compact('authUser', 'laporan', 'tiket', 'unreadCount', 'unitOptions'));
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
        $authUser = Auth::user() ?? User::where('role', 'Admin SSC')->first();

        if ($action === 'teruskan') {
            $unitPenangan = $request->input('unit_penangan') ?: ($dbLaporan->unit_penangan ?: 'Unit Penangan Terkait');
            $prioritas = $request->input('prioritas') ?: 'Sedang';
            $catatan = $request->input('catatan');

            $unitModel = UnitPenangan::where('nama_unit', $unitPenangan)->first();

            $dbLaporan->update([
                'status' => 'Diteruskan',
                'prioritas' => $prioritas,
                'unit_penangan' => $unitPenangan,
                'unit_penangan_id' => $unitModel?->id,
                'catatan_internal' => $catatan,
                'progres' => max(35, (int) ($dbLaporan->progres ?? 0)),
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
                'desc' => "{$tiket} ({$dbLaporan->judul}) telah diteruskan ke unit Anda ({$unitPenangan}).",
            ], $unitPenangan);

            // Catat ke log aktivitas audit
            LogAktivitas::catat(
                aktivitas: "Penyaluran laporan ke {$unitPenangan}",
                recordTerkait: $tiket,
                kategori: 'Penyaluran',
                status: 'Berhasil',
                user: $authUser,
                ipAddress: $request->ip()
            );

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

            // Catat penolakan ke log aktivitas audit
            LogAktivitas::catat(
                aktivitas: "Menolak laporan: {$alasan}",
                recordTerkait: $tiket,
                kategori: 'Verifikasi',
                status: 'Ditolak',
                user: $authUser,
                ipAddress: $request->ip()
            );

            $message = "Laporan {$tiket} telah ditolak.";
        }

        // Tandai notifikasi verifikasi laporan masuk untuk Admin SSC sebagai sudah dibaca
        AppNotification::whereIn('user_id', User::where('role', 'Admin SSC')->pluck('id'))
            ->where(function ($q) use ($dbLaporan, $tiket) {
                $q->where('laporan_id', $dbLaporan->id)
                    ->orWhere('tiket', $tiket);
            })
            ->whereIn('title', ['Laporan baru masuk', 'Pengajuan buka kembali laporan'])
            ->update(['is_read' => true]);

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
                'dikirim' => $l->created_at ? $l->created_at->wib()->isoFormat('D MMM YYYY') : '-',
                'tanggal_ymd' => $l->created_at ? $l->created_at->wib()->format('Y-m-d') : '',
                'diperbarui' => $l->updated_at ? 'Update: ' . $l->updated_at->wib()->isoFormat('D MMM YYYY, HH.mm') . ' WIB' : '-',
                'durasi' => $days . ' hari',
                'overdue' => $overdue,
                'status' => $l->status,
                'is_anonim' => (bool) $l->is_anonim,
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
     * Admin SSC: Detail Laporan (Monitoring).
     */
    public function adminMonitoringDetail($tiket)
    {
        $authUser = Auth::user();
        if (!$authUser || $authUser->role !== 'Admin SSC') {
            $authUser = User::where('role', 'Admin SSC')->first() ?? $authUser ?? new User([
                'name' => 'Admin SSC',
                'role' => 'Admin SSC',
                'email' => 'admin.ssc@telkomuniversity.ac.id',
            ]);
        }

        $laporan = Laporan::with(['user', 'tanggapans', 'trackings'])
            ->where('nomor_tiket', $tiket)
            ->first();

        if (!$laporan) {
            return redirect()->route('admin.monitoring')
                ->with('error', 'Laporan dengan tiket ' . $tiket . ' tidak ditemukan.');
        }

        $unreadCount = AppNotification::where('user_id', $authUser?->id)
            ->where('is_read', false)
            ->count();

        return view('admin.monitoring-detail', compact('authUser', 'laporan', 'tiket', 'unreadCount'));
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

        $laporans = Laporan::orderBy('created_at', 'desc')->get();
        $laporanItems = $laporans->map(function ($l) {
            $unit = $l->unit_penangan ?: 'Belum Ditentukan';
            $durasiDays = $l->created_at ? max(1, (int) $l->created_at->diffInDays($l->updated_at ?? now())) : 1;

            return [
                'tiket' => $l->nomor_tiket,
                'judul' => $l->judul,
                'unit' => $unit,
                'shortUnit' => strlen($unit) > 22 ? substr($unit, 0, 20) . '…' : $unit,
                'kategori_key' => $l->kategori ?? '',
                'kategori_label' => $l->kategori_label ?? $l->short_kategori ?? 'Umum',
                'status' => $l->status ?? 'Menunggu Verifikasi',
                'tanggal_ymd' => $l->created_at ? $l->created_at->wib()->format('Y-m-d') : '',
                'durasi_hari' => $durasiDays,
            ];
        })->values()->all();

        $unitGroups = [
            'Program Studi' => [
                'Program Studi S1 Sistem Informasi',
                'Program Studi S1 Teknologi Informasi',
                'Program Studi S1 Teknik Informatika',
                'Program Studi S1 Rekayasa Perangkat Lunak',
                'Program Studi S1 Sains Data',
                'Program Studi S1 Teknik Elektro',
                'Program Studi S1 Teknik Industri',
                'Program Studi S1 Teknik Komputer',
                'Program Studi S1 Teknik Logistik',
                'Program Studi S1 Bisnis Digital',
                'Program Studi S1 Teknik Telekomunikasi',
            ],
            'Direktorat' => [
                'Direktorat Sekretariat & Perencanaan Strategis',
                'Direktorat Bandung Techno Park',
                'Direktorat Akademik',
                'Direktorat Pasca Sarjana dan Advance Learning',
                'Direktorat Logistik dan Aset',
                'Direktorat Keuangan',
                'Direktorat Sumber Daya Manusia',
                'Direktorat Pusat Teknologi Informasi',
                'Direktorat Pemasaran & Admisi',
                'Direktorat Pengembangan Karir, Alumni, & Endowment',
                'Direktorat Kemahasiswaan',
                'Direktorat Kerjasama Strategis & Kantor Urusan Internasional',
                'Direktorat Penelitian dan Pengabdian Masyarakat',
            ],
            'Satpras' => [
                'SATPRAS (Sarana Prasarana)',
            ],
        ];

        $knownUnits = collect($unitGroups)->flatten()->all();
        $extraUnits = $laporans->map(fn ($l) => $l->unit_penangan ?: 'Belum Ditentukan')
            ->unique()
            ->reject(fn ($u) => in_array($u, $knownUnits, true))
            ->sort()
            ->values()
            ->all();

        if (!empty($extraUnits)) {
            $unitGroups['Lainnya'] = $extraUnits;
        }

        $unitOptions = collect($unitGroups)->flatten()->values()->all();

        $unreadCount = AppNotification::where('user_id', $authUser?->id)
            ->where('is_read', false)
            ->count();

        return view('admin.rekapitulasi', compact('authUser', 'laporanItems', 'unitOptions', 'unitGroups', 'unreadCount'));
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

        $query = Laporan::with('unitPenangan')->whereIn('status', ['Diteruskan', 'Diproses', 'Selesai']);
        if ($authUser && $authUser->unit_penangan_id) {
            $query->where(function ($q) use ($authUser) {
                $q->where('unit_penangan_id', $authUser->unit_penangan_id)
                  ->orWhere('unit_penangan', $authUser->unitPenangan?->nama_unit ?? $authUser->fakultas);
            });
        } elseif ($authUser && $authUser->fakultas) {
            $query->where(function ($q) use ($authUser) {
                $q->where('unit_penangan', 'like', '%' . $authUser->fakultas . '%');
            });
        }

        $allMatches = $query->orderBy('updated_at', 'desc')->get();
        if ($allMatches->isEmpty()) {
            $allMatches = Laporan::whereIn('status', ['Diteruskan', 'Diproses', 'Selesai'])->orderBy('updated_at', 'desc')->get();
        }

        $rows = $allMatches->map(function ($l) {
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
                'diterima' => $l->created_at ? $l->created_at->wib()->isoFormat('D MMM YYYY') : '-',
                'tanggal_ymd' => $l->created_at ? $l->created_at->wib()->format('Y-m-d') : '',
                'selesai' => $l->status === 'Selesai' && $l->updated_at ? $l->updated_at->wib()->isoFormat('D MMM YYYY') : '—',
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
                $q->where('fakultas', $unit)
                    ->orWhere('prodi', $unit)
                    ->orWhere(function ($q2) {
                        $q2->whereNull('fakultas')->whereNull('prodi');
                    });
            });
        }

        $recipients = $query->get();
        if ($recipients->isEmpty() && $role === 'Petugas Unit') {
            $recipients = User::where('role', 'Petugas Unit')->get();
        }

        foreach ($recipients as $recipient) {
            AppNotification::create(array_merge($payload, [
                'user_id' => $recipient->id,
                'is_read' => false,
            ]));
        }
    }
}
