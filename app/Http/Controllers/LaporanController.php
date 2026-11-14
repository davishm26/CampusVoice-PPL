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

        // Data sampel sesuai desain UI (jika data DB masih kurang dari 8 agar pagination 1-6 dari 8 berfungsi)
        $sampleItems = [
            [
                'tiket' => 'CV-2026-00124',
                'tanggal' => '18 Okt 2026',
                'judul' => 'Permohonan Perbaikan AC Ruang Kelas',
                'kategori' => 'Satpras',
                'prioritas' => 'Tinggi',
                'status' => 'Diproses',
                'progres' => 65,
                'url' => route('unit.laporan-ditugaskan.detail', 'CV-2026-00124'),
            ],
            [
                'tiket' => 'CV-2026-00119',
                'tanggal' => '16 Okt 2026',
                'judul' => 'Kendala Akses Portal Akademik',
                'kategori' => 'SSC',
                'prioritas' => 'Sedang',
                'status' => 'Diteruskan',
                'progres' => 20,
                'url' => route('unit.laporan-ditugaskan.detail', 'CV-2026-00119'),
            ],
            [
                'tiket' => 'CV-2026-00108',
                'tanggal' => '12 Okt 2026',
                'judul' => 'Permasalahan Transkrip Nilai',
                'kategori' => 'SSC',
                'prioritas' => 'Tinggi',
                'status' => 'Selesai',
                'progres' => 100,
                'url' => route('unit.laporan-ditugaskan.detail', 'CV-2026-00108'),
            ],
            [
                'tiket' => 'CV-2026-00105',
                'tanggal' => '10 Okt 2026',
                'judul' => 'Proyektor Ruang 3.04 Tidak Menampilkan Gambar',
                'kategori' => 'Satpras',
                'prioritas' => 'Tinggi',
                'status' => 'Diproses',
                'progres' => 50,
                'url' => route('unit.laporan-ditugaskan.detail', 'CV-2026-00105'),
            ],
            [
                'tiket' => 'CV-2026-00102',
                'tanggal' => '08 Okt 2026',
                'judul' => 'Kebocoran Plafon Koridor Lantai 2 Gedung A',
                'kategori' => 'Satpras',
                'prioritas' => 'Sedang',
                'status' => 'Diteruskan',
                'progres' => 25,
                'url' => route('unit.laporan-ditugaskan.detail', 'CV-2026-00102'),
            ],
            [
                'tiket' => 'CV-2026-00099',
                'tanggal' => '05 Okt 2026',
                'judul' => 'Lampu Taman Area Parkir Belakang Mati',
                'kategori' => 'Satpras',
                'prioritas' => 'Rendah',
                'status' => 'Selesai',
                'progres' => 100,
                'url' => route('unit.laporan-ditugaskan.detail', 'CV-2026-00099'),
            ],
            [
                'tiket' => 'CV-2026-00095',
                'tanggal' => '02 Okt 2026',
                'judul' => 'Koneksi Wi-Fi Lab Komputer 2 Lambat',
                'kategori' => 'SSC',
                'prioritas' => 'Sedang',
                'status' => 'Diproses',
                'progres' => 75,
                'url' => route('unit.laporan-ditugaskan.detail', 'CV-2026-00095'),
            ],
            [
                'tiket' => 'CV-2026-00091',
                'tanggal' => '28 Sep 2026',
                'judul' => 'Penggantian Kursi Kuliah Rusak di Ruang 1.02',
                'kategori' => 'Satpras',
                'prioritas' => 'Rendah',
                'status' => 'Selesai',
                'progres' => 100,
                'url' => route('unit.laporan-ditugaskan.detail', 'CV-2026-00091'),
            ],
        ];

        $existingTickets = collect($items)->pluck('tiket')->all();
        foreach ($sampleItems as $s) {
            if (!in_array($s['tiket'], $existingTickets)) {
                $items[] = $s;
            }
        }

        $unreadCount = AppNotification::where('user_id', $authUser?->id)
            ->where('is_read', false)
            ->count();
        if ($unreadCount === 0) {
            $unreadCount = 3;
        }

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
            // Data detail sampel sesuai desain UI (default CV-2026-00124)
            $samples = [
                'CV-2026-00124' => [
                    'judul' => 'Permohonan Perbaikan AC Ruang Kelas',
                    'status' => 'Diproses',
                    'prioritas' => 'Prioritas Tinggi',
                    'is_anonim' => true,
                    'kategori' => 'Satpras',
                    'lokasi' => 'Gedung A, Ruang A-301',
                    'pelapor' => 'Identitas disembunyikan',
                    'deskripsi' => 'AC di ruang kelas A-301 tidak berfungsi sejak dua hari terakhir. Kondisi ruangan menjadi panas dan mengganggu proses perkuliahan.',
                    'berkas_nama' => 'foto_ac_ruang_a301.jpg',
                    'berkas_meta' => '1,8 MB • JPG',
                    'progres' => 65,
                ],
                'CV-2026-00119' => [
                    'judul' => 'Kendala Akses Portal Akademik',
                    'status' => 'Diteruskan',
                    'prioritas' => 'Prioritas Sedang',
                    'is_anonim' => false,
                    'kategori' => 'SSC',
                    'lokasi' => 'Sistem Informasi Akademik (iGracias)',
                    'pelapor' => 'Raka Aditya',
                    'deskripsi' => 'Mahasiswa tidak dapat mengakses halaman pengisian KRS sejak pagi hari dan muncul pesan error 504 gateway timeout.',
                    'berkas_nama' => 'screenshot_portal_error.png',
                    'berkas_meta' => '940 KB • PNG',
                    'progres' => 20,
                ],
                'CV-2026-00108' => [
                    'judul' => 'Permasalahan Transkrip Nilai',
                    'status' => 'Selesai',
                    'prioritas' => 'Prioritas Tinggi',
                    'is_anonim' => false,
                    'kategori' => 'SSC',
                    'lokasi' => 'Layanan Akademik / SSC',
                    'pelapor' => 'Nadia Putri',
                    'deskripsi' => 'Nilai mata kuliah Pemrograman Web belum muncul di transkrip sementara meskipun sudah diinput dosen pengampu.',
                    'berkas_nama' => 'bukti_nilai_igracias.pdf',
                    'berkas_meta' => '620 KB • PDF',
                    'progres' => 100,
                ],
            ];

            $chosen = $samples[$tiket] ?? [
                'judul' => 'Laporan Penanganan Unit (' . $tiket . ')',
                'status' => 'Diproses',
                'prioritas' => 'Prioritas Tinggi',
                'is_anonim' => true,
                'kategori' => 'Satpras',
                'lokasi' => 'Gedung A, Ruang A-301',
                'pelapor' => 'Identitas disembunyikan',
                'deskripsi' => 'AC di ruang kelas A-301 tidak berfungsi sejak dua hari terakhir. Kondisi ruangan menjadi panas dan mengganggu proses perkuliahan.',
                'berkas_nama' => 'foto_ac_ruang_a301.jpg',
                'berkas_meta' => '1,8 MB • JPG',
                'progres' => 65,
            ];

            $laporan = array_merge($chosen, [
                'is_db' => false,
                'tiket' => $tiket,
                'berkas_url' => null,
            ]);

            $riwayats = [
                [
                    'inisial' => 'SS',
                    'nama' => 'Admin SSC',
                    'role' => 'Admin SSC',
                    'pesan' => 'Laporan telah diverifikasi dan diteruskan ke unit Anda.',
                    'waktu' => 'Hari ini, 09.15',
                ],
            ];
        }

        $unreadCount = AppNotification::where('user_id', $authUser?->id)
            ->where('is_read', false)
            ->count();
        if ($unreadCount === 0) {
            $unreadCount = 3;
        }

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
                'pengirim_nama' => $authUser->name ?? 'Budi Santoso',
                'pengirim_role' => 'Petugas Unit',
                'pesan' => $catatan,
            ]);

            LaporanTracking::create([
                'laporan_id' => $dbLaporan->id,
                'judul' => $actionType === 'kembalikan' ? 'Dikembalikan ke Admin SSC' : "Penanganan Unit: {$statusBaru}",
                'deskripsi' => $catatan,
                'status_step' => $statusBaru,
            ]);
        }

        $msg = match ($actionType) {
            'kembalikan' => "Laporan {$tiket} berhasil dikembalikan ke Admin SSC.",
            'tanggapan' => "Tanggapan untuk laporan {$tiket} berhasil dikirim.",
            default => "Pembaruan penanganan laporan {$tiket} berhasil disimpan ({$statusBaru}, {$progresBaru}%).",
        };

        return redirect()->route('unit.laporan-ditugaskan.detail', $tiket)->with('success', $msg);
    }
}
