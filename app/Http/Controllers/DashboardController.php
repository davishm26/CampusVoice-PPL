<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use App\Models\Laporan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
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

    public function index(Request $request)
    {
        $authUser = $this->getActiveUser();

        // Jika user memiliki role Admin SSC, arahkan langsung ke dashboard Admin SSC
        if ($authUser && $authUser->role === 'Admin SSC') {
            return $this->adminDashboard($request);
        }

        // Jika user memiliki role Petugas Unit, arahkan langsung ke dashboard Petugas Unit
        if ($authUser && $authUser->role === 'Petugas Unit') {
            return redirect()->route('unit.dashboard');
        }

        // Jika user memiliki role Super Admin, arahkan langsung ke dashboard Super Admin
        if ($authUser && $authUser->role === 'Super Admin') {
            return redirect()->route('superadmin.dashboard');
        }

        $userId = $authUser->id;

        $totalLaporan = Laporan::where('user_id', $userId)->count();
        $menungguVerifikasi = Laporan::where('user_id', $userId)->where('status', 'Menunggu Verifikasi')->count();
        $sedangDiproses = Laporan::where('user_id', $userId)->whereIn('status', ['Diproses', 'Diteruskan'])->count();
        $laporanSelesai = Laporan::where('user_id', $userId)->where('status', 'Selesai')->count();

        $laporanTerbaru = Laporan::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $unreadCount = AppNotification::where('user_id', $userId)
            ->where('is_read', false)
            ->count();

        // Cek jika ada tiket yang dipilih untuk inline detail di dashboard
        $selectedTiket = $request->query('tiket');
        $detailLaporan = null;
        if ($selectedTiket) {
            $detailLaporan = Laporan::with(['tanggapans', 'trackings', 'user'])
                ->where('nomor_tiket', $selectedTiket)
                ->first();
        }

        if (!$detailLaporan && $laporanTerbaru->isNotEmpty()) {
            $detailLaporan = Laporan::with(['tanggapans', 'trackings', 'user'])
                ->find($laporanTerbaru->first()->id);
        }

        return view('dashboard', compact(
            'authUser',
            'totalLaporan',
            'menungguVerifikasi',
            'sedangDiproses',
            'laporanSelesai',
            'laporanTerbaru',
            'unreadCount',
            'detailLaporan'
        ));
    }

    public function adminDashboard(Request $request)
    {
        $authUser = Auth::user();
        if (!$authUser || $authUser->role !== 'Admin SSC') {
            $authUser = User::where('role', 'Admin SSC')->first() ?? $authUser ?? new User([
                'name' => 'Admin SSC',
                'role' => 'Admin SSC',
                'email' => 'admin.ssc@telkomuniversity.ac.id',
            ]);
        }

        // Hitung data real dari database
        $totalLaporan = Laporan::count();
        $menungguVerifikasi = Laporan::where('status', 'Menunggu Verifikasi')->count();
        $diteruskan = Laporan::where('status', 'Diteruskan')->count();
        $diproses = Laporan::where('status', 'Diproses')->count();
        $selesai = Laporan::where('status', 'Selesai')->count();
        $ditolak = Laporan::where('status', 'Ditolak')->count();
        $laporanAktif = $menungguVerifikasi + $diteruskan + $diproses;

        // Data tren 7 hari terakhir dari database
        $dayMap = ['Mon' => ['Sen', 'Senin'], 'Tue' => ['Sel', 'Selasa'], 'Wed' => ['Rab', 'Rabu'], 'Thu' => ['Kam', 'Kamis'], 'Fri' => ['Jum', 'Jumat'], 'Sat' => ['Sab', 'Sabtu'], 'Sun' => ['Min', 'Minggu']];
        $rawCounts = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = Laporan::whereDate('created_at', $date->toDateString())->count();
            $dKey = $date->format('D');
            $rawCounts[] = [
                'hari' => $dayMap[$dKey][0] ?? $dKey,
                'label' => $dayMap[$dKey][1] ?? $dKey,
                'jumlah' => $count,
            ];
        }
        $maxCount = max(1, collect($rawCounts)->max('jumlah'));
        $trenHarian = collect($rawCounts)->map(function ($item) use ($maxCount) {
            $item['persen'] = $item['jumlah'] > 0 ? (int) round(($item['jumlah'] / $maxCount) * 100) : 0;
            return $item;
        })->all();

        // Laporan dari database
        $dbLaporans = Laporan::with('user')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $unreadCount = AppNotification::where('user_id', $authUser?->id)
            ->where('is_read', false)
            ->count();

        return view('admin.dashboard', compact(
            'authUser',
            'totalLaporan',
            'menungguVerifikasi',
            'diteruskan',
            'diproses',
            'selesai',
            'ditolak',
            'laporanAktif',
            'trenHarian',
            'dbLaporans',
            'unreadCount'
        ));
    }

    public function unitDashboard(Request $request)
    {
        $authUser = Auth::user();
        if (!$authUser || $authUser->role !== 'Petugas Unit') {
            $authUser = User::where('role', 'Petugas Unit')->first() ?? $authUser ?? new User([
                'name' => 'Petugas Unit',
                'role' => 'Petugas Unit',
                'email' => 'petugas.unit@telkomuniversity.ac.id',
            ]);
        }

        $unitQuery = Laporan::whereIn('status', ['Diteruskan', 'Diproses', 'Selesai']);
        if (!empty($authUser->unit)) {
            $unitQuery->where(function ($q) use ($authUser) {
                $q->where('unit_penangan', $authUser->unit)
                  ->orWhereNull('unit_penangan');
            });
        }

        $totalMasuk = (clone $unitQuery)->count();
        $belumDitangani = (clone $unitQuery)->where('status', 'Diteruskan')->count();
        $sedangDiproses = (clone $unitQuery)->where('status', 'Diproses')->count();
        $selesai = (clone $unitQuery)->where('status', 'Selesai')->count();

        $dbLaporans = (clone $unitQuery)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $laporansList = $dbLaporans->map(function ($lap) {
            $prioritasClass = match ($lap->prioritas) {
                'Prioritas Tinggi', 'Tinggi' => 'bg-red-50 text-red-600',
                'Prioritas Sedang', 'Sedang' => 'bg-amber-50 text-amber-600',
                default => 'bg-gray-100 text-gray-700',
            };

            $statusClass = match ($lap->status) {
                'Diproses' => 'text-purple-600',
                'Diteruskan' => 'text-blue-600',
                'Selesai' => 'text-emerald-600',
                default => 'text-amber-600',
            };

            $statusDot = match ($lap->status) {
                'Diproses' => 'bg-purple-600',
                'Diteruskan' => 'bg-blue-600',
                'Selesai' => 'bg-emerald-600',
                default => 'bg-amber-600',
            };

            return [
                'tiket' => $lap->nomor_tiket,
                'tanggal' => $lap->created_at ? $lap->created_at->locale('id')->translatedFormat('d M Y') : '-',
                'judul' => $lap->judul,
                'kategori' => $lap->short_kategori ?? $lap->kategori_label ?? $lap->kategori ?? 'Umum',
                'prioritas' => $lap->prioritas ?? 'Sedang',
                'prioritasClass' => $prioritasClass,
                'status' => $lap->status,
                'statusDot' => $statusDot,
                'statusClass' => $statusClass,
                'progres' => (int) ($lap->progres ?? 0),
                'url' => route('unit.laporan-ditugaskan.detail', $lap->nomor_tiket),
            ];
        })->values()->all();

        $unreadCount = AppNotification::where('user_id', $authUser?->id)
            ->where('is_read', false)
            ->count();

        $aktivitasUnit = \App\Models\LaporanTracking::with('laporan')
            ->whereIn('laporan_id', (clone $unitQuery)->pluck('id'))
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('unit.dashboard', compact(
            'authUser',
            'totalMasuk',
            'belumDitangani',
            'sedangDiproses',
            'selesai',
            'laporansList',
            'aktivitasUnit',
            'unreadCount'
        ));
    }

    public function superAdminDashboard(Request $request)
    {
        $authUser = Auth::user();
        if (!$authUser || $authUser->role !== 'Super Admin') {
            $authUser = User::where('role', 'Super Admin')->first() ?? $authUser ?? new User([
                'name' => 'Super Admin',
                'role' => 'Super Admin',
                'email' => 'superadmin@telkomuniversity.ac.id',
            ]);
        }

        $totalPengguna = User::count();
        $totalAktif = User::where('status_akun', 'Aktif')->count();
        $totalMahasiswa = User::where('role', 'Mahasiswa')->count();
        $totalAdminSsc = User::where('role', 'Admin SSC')->count();
        $totalPetugasUnit = User::where('role', 'Petugas Unit')->count();
        $totalSuperAdmin = User::where('role', 'Super Admin')->count();
        $totalLaporan = Laporan::count();
        $totalUnitPenangan = User::where('role', 'Petugas Unit')->whereNotNull('unit')->distinct('unit')->count('unit');
        $totalKategori = \App\Models\KategoriPenyaluran::count();

        $activities = \App\Models\LaporanTracking::with('laporan.user')
            ->orderBy('created_at', 'desc')
            ->take(15)
            ->get()
            ->map(function ($t) {
                return [
                    'id' => $t->id,
                    'timestamp' => $t->formatted_waktu,
                    'pengguna' => $t->laporan?->user?->name ?? 'Sistem',
                    'peran' => $t->laporan?->user?->role ?? 'Sistem',
                    'aktivitas' => $t->status ?? 'Aktivitas Laporan',
                    'record' => $t->laporan?->nomor_tiket ?? '—',
                ];
            })->values()->all();

        return view('superadmin.dashboard', compact(
            'authUser',
            'totalPengguna',
            'totalAktif',
            'totalMahasiswa',
            'totalAdminSsc',
            'totalPetugasUnit',
            'totalSuperAdmin',
            'totalLaporan',
            'totalUnitPenangan',
            'totalKategori',
            'activities'
        ));
    }

    public function superAdminLog(Request $request)
    {
        $authUser = Auth::user();
        if (!$authUser || $authUser->role !== 'Super Admin') {
            $authUser = User::where('role', 'Super Admin')->first() ?? $authUser ?? new User([
                'name' => 'Super Admin',
                'role' => 'Super Admin',
                'email' => 'superadmin@telkomuniversity.ac.id',
            ]);
        }

        $logs = \App\Models\LaporanTracking::with('laporan.user')
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get()
            ->map(function ($t) {
                return [
                    'id' => $t->id,
                    'timestamp' => $t->formatted_waktu,
                    'pengguna' => $t->laporan?->user?->name ?? 'Sistem',
                    'peran' => $t->laporan?->user?->role ?? 'Sistem',
                    'aktivitas' => $t->status . ($t->keterangan ? ': ' . $t->keterangan : ''),
                    'kategori' => str_contains(strtolower($t->status ?? ''), 'terus') ? 'Penyaluran' : 'Progres',
                    'status' => 'Berhasil',
                    'record' => $t->laporan?->nomor_tiket ?? '—',
                    'ip' => $request->ip() ?? '-',
                ];
            })->values()->all();

        return view('superadmin.log-aktivitas', compact('authUser', 'logs'));
    }

    public function superAdminUnitPenangan(Request $request)
    {
        $authUser = Auth::user();
        if (!$authUser || $authUser->role !== 'Super Admin') {
            $authUser = User::where('role', 'Super Admin')->first() ?? $authUser ?? new User([
                'name' => 'Super Admin',
                'role' => 'Super Admin',
                'email' => 'superadmin@telkomuniversity.ac.id',
            ]);
        }

        $direktoratUnits = User::where('role', 'Petugas Unit')
            ->whereNotNull('unit')
            ->where('unit', 'not like', 'Program Studi%')
            ->get()
            ->groupBy('unit')
            ->values()
            ->map(function ($group, $idx) {
                $unitName = $group->first()->unit;
                return [
                    'id' => $idx + 1,
                    'nama' => $unitName,
                    'tipe' => 'Direktorat',
                    'jumlahLabel' => $group->count() . ' petugas',
                    'status' => $group->contains('status_akun', 'Aktif') ? 'Aktif' : 'Nonaktif',
                ];
            })->all();

        $prodiUnits = User::where('role', 'Mahasiswa')
            ->whereNotNull('prodi')
            ->get()
            ->groupBy('prodi')
            ->values()
            ->map(function ($group, $idx) {
                $prodiName = $group->first()->prodi;
                return [
                    'id' => $idx + 1,
                    'nama' => str_starts_with($prodiName, 'Program Studi') ? $prodiName : 'Program Studi ' . $prodiName,
                    'tipe' => 'Program Studi',
                    'jumlahLabel' => $group->count() . ' mahasiswa',
                    'status' => 'Aktif',
                ];
            })->all();

        return view('superadmin.unit-penangan', compact('authUser', 'direktoratUnits', 'prodiUnits'));
    }
}

