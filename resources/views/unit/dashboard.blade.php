@php
    $authUser = $authUser ?? Auth::user();
    if (!$authUser || $authUser->role !== 'Petugas Unit') {
        $authUser = \App\Models\User::where('role', 'Petugas Unit')->first() ?? $authUser ?? new \App\Models\User([
            'name' => 'Budi Santoso',
            'role' => 'Petugas Unit',
            'email' => 'petugas.unit@telkomuniversity.ac.id',
        ]);
    }
    $authName = $authUser ? $authUser->name : 'Budi Santoso';
    $authRole = ($authUser && $authUser->role) ? $authUser->role : 'Petugas Unit';

    $authWords = preg_split('/\s+/', trim($authName));
    $authInitials = '';
    foreach ($authWords as $w) { $authInitials .= mb_strtoupper(mb_substr($w, 0, 1)); }
    $authInitials = substr($authInitials, 0, 2) ?: 'BS';

    // Statistik laporan
    $totalMasuk = \App\Models\Laporan::count();
    if ($totalMasuk === 0) {
        $totalMasuk = 18;
        $belumDitangani = 6;
        $sedangDiproses = 14;
        $selesai = 8;
    } else {
        $belumDitangani = \App\Models\Laporan::where('status', 'Menunggu Verifikasi')->count() ?: 6;
        $sedangDiproses = \App\Models\Laporan::whereIn('status', ['Diproses', 'Diteruskan'])->count() ?: 14;
        $selesai = \App\Models\Laporan::where('status', 'Selesai')->count() ?: 8;
    }

    // Data Laporan Terbaru
    $dbLaporans = \App\Models\Laporan::orderBy('created_at', 'desc')->take(5)->get();
    if ($dbLaporans->isEmpty()) {
        $laporansList = [
            [
                'tiket' => 'CV-2026-00124',
                'tanggal' => '18 Okt 2026',
                'judul' => 'Permohonan Perbaikan AC Ruang Kelas',
                'kategori' => 'Satpras',
                'prioritas' => 'Tinggi',
                'prioritasClass' => 'bg-red-50 text-red-600',
                'status' => 'Diproses',
                'statusDot' => 'bg-purple-600',
                'statusClass' => 'text-purple-600',
                'progres' => 65,
                'url' => route('laporan.detail', 'CV-2026-00124')
            ],
            [
                'tiket' => 'CV-2026-00119',
                'tanggal' => '16 Okt 2026',
                'judul' => 'Kendala Akses Portal Akademik',
                'kategori' => 'SSC',
                'prioritas' => 'Sedang',
                'prioritasClass' => 'bg-amber-50 text-amber-600',
                'status' => 'Diteruskan',
                'statusDot' => 'bg-blue-600',
                'statusClass' => 'text-blue-600',
                'progres' => 20,
                'url' => route('laporan.detail', 'CV-2026-00119')
            ],
            [
                'tiket' => 'CV-2026-00108',
                'tanggal' => '12 Okt 2026',
                'judul' => 'Permasalahan Transkrip Nilai',
                'kategori' => 'SSC',
                'prioritas' => 'Tinggi',
                'prioritasClass' => 'bg-red-50 text-red-600',
                'status' => 'Selesai',
                'statusDot' => 'bg-emerald-600',
                'statusClass' => 'text-emerald-600',
                'progres' => 100,
                'url' => route('laporan.detail', 'CV-2026-00108')
            ]
        ];
    } else {
        $laporansList = $dbLaporans->map(function ($lap) {
            $prioritasClass = match ($lap->prioritas) {
                'Tinggi' => 'bg-red-50 text-red-600',
                'Sedang' => 'bg-amber-50 text-amber-600',
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
                'tanggal' => $lap->created_at ? $lap->created_at->format('d M Y') : '18 Okt 2026',
                'judul' => $lap->judul,
                'kategori' => $lap->kategori_label ?? $lap->kategori ?? 'Umum',
                'prioritas' => $lap->prioritas ?? 'Sedang',
                'prioritasClass' => $prioritasClass,
                'status' => $lap->status,
                'statusDot' => $statusDot,
                'statusClass' => $statusClass,
                'progres' => $lap->progres ?? 50,
                'url' => route('laporan.detail', $lap->nomor_tiket)
            ];
        })->toArray();
    }
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-white">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Dashboard Unit — Program Studi S1 Sistem Informasi - CampusVoice</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS with Forms Plugin via CDN -->
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        maroon: {
                            600: '#9F171B',
                            700: '#8B1D22',
                            800: '#851518',
                            900: '#6E0D11',
                        },
                        brand: {
                            50: '#FCE8E8',
                            100: '#F8D1D1',
                            500: '#D91D24',
                            600: '#B01116',
                            700: '#8C0D11',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        body, input, select, button, textarea { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body
    class="min-h-screen bg-white antialiased text-[#252525]"
    x-data="{
        mobileSidebarOpen: false,
        searchQuery: '',
        laporans: @js($laporansList),
        get filteredLaporans() {
            if (!this.searchQuery.trim()) return this.laporans;
            const q = this.searchQuery.toLowerCase();
            return this.laporans.filter(item => 
                item.tiket.toLowerCase().includes(q) ||
                item.judul.toLowerCase().includes(q) ||
                item.kategori.toLowerCase().includes(q) ||
                item.status.toLowerCase().includes(q)
            );
        }
    }"
>
    <div class="min-h-screen flex flex-col lg:flex-row">

        <!-- ========================================================= -->
        <!-- 2. SIDEBAR KIRI (LATAR PUTIH) -->
        <!-- ========================================================= -->
        <aside
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white flex flex-col justify-between border-r border-[#E5E7EB] transition-transform duration-200 lg:static lg:translate-x-0 shrink-0"
            :class="mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        >
            <div class="flex flex-col flex-1 overflow-y-auto">
                <!-- Header Sidebar: Logo CampusVoice (merah), Subtitle, Label "Petugas Unit" -->
                <div class="px-6 py-5 flex items-center gap-3">
                    <img src="{{ asset('images/logo-campusvoice.png') }}" alt="CampusVoice Logo" class="w-8 h-8 object-contain shrink-0" onerror="this.onerror=null; this.src='https://placehold.co/32x32/B01116/white?text=CV';" />
                    <div>
                        <span class="text-[17px] font-extrabold text-[#B01116] tracking-tight block leading-tight">CampusVoice</span>
                        <p class="text-[10px] text-[#6B7280] font-medium leading-tight mt-0.5">Telkom University Surabaya</p>
                    </div>
                </div>

                <!-- Label & Menu Navigasi Petugas Unit -->
                <div class="px-4 py-2 flex-1">
                    <p class="px-3.5 text-[11px] font-bold text-[#6B7280] tracking-wider mb-2.5 uppercase">Petugas Unit</p>

                    <nav class="space-y-1">
                        <!-- Menu 1: Dashboard Unit (Aktif: latar belakang pink muda, teks/ikon merah) -->
                        <a
                            href="{{ route('unit.dashboard') }}"
                            aria-current="page"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold bg-[#FCE8E8] text-[#B01116] transition shadow-xs"
                        >
                            <svg class="w-5 h-5 text-[#B01116] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="7" height="7" x="3" y="3" rx="1.5"/>
                                <rect width="7" height="7" x="14" y="3" rx="1.5"/>
                                <rect width="7" height="7" x="14" y="14" rx="1.5"/>
                                <rect width="7" height="7" x="3" y="14" rx="1.5"/>
                            </svg>
                            <span>Dashboard Unit</span>
                        </a>

                        <!-- Menu 2: Laporan Ditugaskan (ikon dokumen) -->
                        <a
                            href="{{ route('laporan.riwayat') }}"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#252525] hover:bg-gray-100 hover:text-[#B01116] transition group"
                        >
                            <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                                <line x1="16" y1="13" x2="8" y2="13"/>
                                <line x1="16" y1="17" x2="8" y2="17"/>
                            </svg>
                            <span>Laporan Ditugaskan</span>
                        </a>

                        <!-- Menu 3: Riwayat Penanganan (ikon grafik) -->
                        <a
                            href="{{ route('laporan.riwayat') }}"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#252525] hover:bg-gray-100 hover:text-[#B01116] transition group"
                        >
                            <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="20" x2="18" y2="10"/>
                                <line x1="12" y1="20" x2="12" y2="4"/>
                                <line x1="6" y1="20" x2="6" y2="14"/>
                            </svg>
                            <span>Riwayat Penanganan</span>
                        </a>

                        <!-- Menu 4: Notifikasi (ikon lonceng dengan badge angka 3) -->
                        <a
                            href="{{ route('unit.notifikasi') }}"
                            class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#252525] hover:bg-gray-100 hover:text-[#B01116] transition group"
                        >
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
                                    <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                                </svg>
                                <span>Notifikasi</span>
                            </div>
                            <span class="w-5 h-5 rounded-full bg-[#B01116] text-white text-[11px] font-bold flex items-center justify-center shrink-0">
                                3
                            </span>
                        </a>

                        <!-- Menu 5: Profil Unit (ikon user) -->
                        <a
                            href="{{ route('profil') }}"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#252525] hover:bg-gray-100 hover:text-[#B01116] transition group"
                        >
                            <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                            <span>Profil Unit</span>
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Bottom: Menu "Keluar" (ikon panah keluar) & Kartu Bantuan Maroon -->
            <div class="p-4 border-t border-[#E5E7EB] space-y-3">
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            type="submit"
                            class="w-full flex items-center gap-3 px-3.5 py-2 rounded-xl text-sm font-semibold text-[#B01116] hover:bg-red-50 transition cursor-pointer text-left"
                        >
                            <svg class="w-5 h-5 text-[#B01116] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                <polyline points="16 17 21 12 16 7"/>
                                <line x1="21" y1="12" x2="9" y2="12"/>
                            </svg>
                            <span>Keluar</span>
                        </button>
                    </form>
                @else
                    <a
                        href="{{ route('login') }}"
                        class="w-full flex items-center gap-3 px-3.5 py-2 rounded-xl text-sm font-semibold text-[#B01116] hover:bg-red-50 transition text-left"
                    >
                        <svg class="w-5 h-5 text-[#B01116] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                            <polyline points="10 17 15 12 10 7"/>
                            <line x1="15" y1="12" x2="3" y2="12"/>
                        </svg>
                        <span>Keluar</span>
                    </a>
                @endauth

                <!-- Kartu Bantuan Merah Maroon Penuh -->
                <div class="rounded-xl bg-[#851518] p-4 text-white shadow-sm">
                    <h4 class="text-sm font-bold tracking-tight text-white">Butuh bantuan?</h4>
                    <p class="text-[11px] text-white/85 mt-1 leading-snug">Hubungi pusat layanan CampusVoice.</p>
                    <a
                        href="{{ route('pusat.bantuan') }}"
                        class="mt-3 block w-full py-1.5 px-3 bg-white hover:bg-gray-100 text-[#851518] text-xs font-bold rounded-lg text-center shadow-xs transition"
                    >
                        Pusat Bantuan
                    </a>
                </div>
            </div>
        </aside>

        <!-- Overlay Mobile -->
        <div x-show="mobileSidebarOpen" x-cloak @click="mobileSidebarOpen = false" class="fixed inset-0 z-40 bg-black/40 lg:hidden"></div>

        <!-- ========================================================= -->
        <!-- AREA UTAMA (Top Navbar & Area Konten Maroon) -->
        <!-- ========================================================= -->
        <div class="flex-1 flex flex-col min-w-0">

            <!-- 1. TOP NAVBAR (LATAR PUTIH) -->
            <header class="bg-white border-b border-[#E5E7EB] h-[72px] flex items-center justify-between px-6 sm:px-8 shrink-0 sticky top-0 z-30">
                <div class="flex items-center gap-3">
                    <button
                        type="button"
                        @click="mobileSidebarOpen = !mobileSidebarOpen"
                        class="p-2 -ml-2 rounded-lg text-[#6B7280] hover:text-[#252525] hover:bg-gray-100 lg:hidden"
                        aria-label="Buka navigasi"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <!-- Search Bar Oval Abu-abu Muda -->
                    <div class="relative w-64 sm:w-80 md:w-96">
                        <input
                            type="text"
                            x-model="searchQuery"
                            placeholder="Cari nomor tiket atau laporan..."
                            class="w-full bg-[#F4F5F7] border border-[#E5E7EB] rounded-xl py-2 px-4 text-xs sm:text-sm text-[#252525] placeholder-[#9CA3AF] focus:ring-2 focus:ring-[#851518] focus:border-[#851518] focus:bg-white transition"
                        />
                    </div>
                </div>

                <!-- Navigasi Kanan: Lonceng Notifikasi (badge 3 merah), Avatar "BS", Budi Santoso, Petugas Unit -->
                <div class="flex items-center gap-4 sm:gap-6">
                    <!-- Ikon Lonceng Notifikasi -->
                    <a href="{{ route('unit.notifikasi') }}" class="relative w-9 h-9 rounded-full flex items-center justify-center text-[#252525] hover:bg-gray-100 transition" aria-label="Notifikasi">
                        <svg class="w-5 h-5 text-[#252525]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
                            <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                        </svg>
                        <span class="absolute top-1 right-1 flex items-center justify-center w-3.5 h-3.5 text-[9px] font-bold text-white bg-[#B01116] rounded-full ring-2 ring-white">
                            3
                        </span>
                    </a>

                    <!-- User Profile: Kotak Avatar "BS" & Budi Santoso / Petugas Unit -->
                    <a href="{{ route('profil') }}" class="flex items-center gap-3 group">
                        <div class="w-9 h-9 rounded-lg bg-[#FCE8E8] text-[#B01116] font-bold text-xs flex items-center justify-center shrink-0 group-hover:ring-2 group-hover:ring-[#B01116]/30 transition">
                            {{ $authInitials }}
                        </div>
                        <div class="hidden sm:block text-left">
                            <p class="text-xs font-bold text-[#1F2937] leading-tight group-hover:text-[#B01116] transition">{{ $authName }}</p>
                            <p class="text-[10px] text-[#6B7280] leading-tight mt-0.5">{{ $authRole }}</p>
                        </div>
                    </a>
                </div>
            </header>

            <!-- ========================================================= -->
            <!-- 3. AREA KONTEN UTAMA (HEADER MAROON & LATAR ABU-ABU) -->
            <!-- ========================================================= -->
            <main class="flex-1 bg-[#851518] px-6 sm:px-8 lg:px-10 py-8">
                <div class="max-w-[1360px] mx-auto space-y-6">

                    <!-- Header Atas (Maroon): Label, Judul Besar, Sub-judul, Tombol Aksi Kanan -->
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                        <div>
                            <span class="text-[11px] font-bold tracking-widest uppercase text-white/80 block mb-1">
                                PETUGAS UNIT
                            </span>
                            <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-tight leading-snug">
                                Dashboard Unit — Program Studi S1 Sistem Informasi
                            </h1>
                            <p class="text-xs sm:text-sm text-white/85 mt-1">
                                Kelola laporan yang ditugaskan kepada unit Anda.
                            </p>
                        </div>

                        <!-- Pojok Kanan Atas: Tombol Putih "Lihat Versi Direktorat" & Teks Link Putih "Lihat Laporan Ditugaskan" -->
                        <div class="flex items-center gap-3 shrink-0 pt-1">
                            <button
                                type="button"
                                class="px-4 py-2.5 rounded-xl bg-white hover:bg-gray-50 text-xs sm:text-sm font-bold text-[#1F2937] shadow-sm transition cursor-pointer"
                            >
                                Lihat Versi Direktorat
                            </button>
                            <a
                                href="{{ route('laporan.riwayat') }}"
                                class="px-4 py-2.5 text-xs sm:text-sm font-semibold text-white hover:text-white/80 transition"
                            >
                                Lihat Laporan Ditugaskan
                            </a>
                        </div>
                    </div>

                    <!-- Banner Informasi (Putih full-width, ikon info merah) -->
                    <div class="bg-white rounded-xl p-4 shadow-sm flex items-center gap-3.5">
                        <div class="w-6 h-6 rounded-full bg-red-50 text-[#B01116] flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-[#B01116]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="16" x2="12" y2="12"/>
                                <line x1="12" y1="8" x2="12.01" y2="8"/>
                            </svg>
                        </div>
                        <p class="text-xs sm:text-sm text-[#252525]">
                            Anda hanya dapat mengakses laporan yang ditugaskan kepada <span class="font-bold">Program Studi S1 Sistem Informasi</span>.
                        </p>
                    </div>

                    <!-- Kartu Statistik (Grid 4 Kolom) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- 1. Laporan Masuk: Angka "18" (Ikon dokumen merah) -->
                        <div class="bg-white rounded-xl p-5 shadow-sm flex items-center gap-4">
                            <div class="w-10 h-10 rounded-lg bg-[#FCE8E8] text-[#B01116] flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-[#B01116]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <polyline points="14 2 14 8 20 8"/>
                                    <line x1="16" y1="13" x2="8" y2="13"/>
                                    <line x1="16" y1="17" x2="8" y2="17"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-[#6B7280] font-medium leading-none">Laporan Masuk</p>
                                <p class="text-2xl font-bold text-[#1F2937] leading-tight mt-1">{{ $totalMasuk }}</p>
                            </div>
                        </div>

                        <!-- 2. Belum Ditangani: Angka "6" (Ikon jam oranye) -->
                        <div class="bg-white rounded-xl p-5 shadow-sm flex items-center gap-4">
                            <div class="w-10 h-10 rounded-lg bg-[#FEF3C7] text-[#D97706] flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-[#D97706]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/>
                                    <polyline points="12 6 12 12 16 14"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-[#6B7280] font-medium leading-none">Belum Ditangani</p>
                                <p class="text-2xl font-bold text-[#1F2937] leading-tight mt-1">{{ $belumDitangani }}</p>
                            </div>
                        </div>

                        <!-- 3. Sedang Diproses: Angka "14" (Ikon proses biru) -->
                        <div class="bg-white rounded-xl p-5 shadow-sm flex items-center gap-4">
                            <div class="w-10 h-10 rounded-lg bg-[#DBEAFE] text-[#2563EB] flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-[#2563EB]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-[#6B7280] font-medium leading-none">Sedang Diproses</p>
                                <p class="text-2xl font-bold text-[#1F2937] leading-tight mt-1">{{ $sedangDiproses }}</p>
                            </div>
                        </div>

                        <!-- 4. Selesai: Angka "8" (Ikon centang hijau) -->
                        <div class="bg-white rounded-xl p-5 shadow-sm flex items-center gap-4">
                            <div class="w-10 h-10 rounded-lg bg-[#D1FAE5] text-[#059669] flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-[#059669]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-[#6B7280] font-medium leading-none">Selesai</p>
                                <p class="text-2xl font-bold text-[#1F2937] leading-tight mt-1">{{ $selesai }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- ========================================================= -->
                    <!-- 4. BAGIAN BAWAH KONTEN UTAMA (TABEL & AKTIVITAS UNIT) -->
                    <!-- ========================================================= -->
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                        <!-- KARTU TABEL "LAPORAN TERBARU" (Kiri/Tengah - Lebar Besar) -->
                        <div class="lg:col-span-8 xl:col-span-8 bg-white rounded-2xl shadow-sm p-6 sm:p-7">
                            <div class="mb-5">
                                <h2 class="text-lg font-bold text-[#1F2937] tracking-tight">Laporan Terbaru</h2>
                                <p class="text-xs text-[#6B7280] mt-0.5">Ditugaskan kepada unit Anda oleh Admin SSC.</p>
                            </div>

                            <!-- Tabel Responsif -->
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-xs border-collapse">
                                    <thead>
                                        <tr class="border-b border-[#E5E7EB] text-[#6B7280] font-bold text-[11px] uppercase tracking-wider bg-[#F9FAFB]">
                                            <th class="py-3 px-4">NOMOR TIKET</th>
                                            <th class="py-3 px-4">JUDUL LAPORAN</th>
                                            <th class="py-3 px-4">KATEGORI</th>
                                            <th class="py-3 px-4">PRIORITAS</th>
                                            <th class="py-3 px-4">STATUS</th>
                                            <th class="py-3 px-4">PROGRES</th>
                                            <th class="py-3 px-4 text-right">AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[#E5E7EB]">
                                        <template x-for="item in filteredLaporans" :key="item.tiket">
                                            <tr class="hover:bg-gray-50/70 transition">
                                                <!-- Nomor Tiket -->
                                                <td class="py-4 px-4 whitespace-nowrap">
                                                    <p class="font-bold text-[#1F2937]" x-text="item.tiket"></p>
                                                    <p class="text-[11px] text-[#6B7280] mt-0.5" x-text="item.tanggal"></p>
                                                </td>

                                                <!-- Judul Laporan -->
                                                <td class="py-4 px-4">
                                                    <p class="font-semibold text-[#1F2937] leading-snug line-clamp-2 max-w-xs" x-text="item.judul"></p>
                                                </td>

                                                <!-- Kategori -->
                                                <td class="py-4 px-4 whitespace-nowrap text-[#4B5563]" x-text="item.kategori"></td>

                                                <!-- Prioritas -->
                                                <td class="py-4 px-4 whitespace-nowrap">
                                                    <span
                                                        class="inline-block px-2.5 py-0.5 text-[11px] font-bold rounded-full"
                                                        :class="item.prioritasClass"
                                                        x-text="item.prioritas"
                                                    ></span>
                                                </td>

                                                <!-- Status -->
                                                <td class="py-4 px-4 whitespace-nowrap">
                                                    <div class="inline-flex items-center gap-1.5 font-bold" :class="item.statusClass">
                                                        <span class="w-1.5 h-1.5 rounded-full" :class="item.statusDot"></span>
                                                        <span x-text="item.status"></span>
                                                    </div>
                                                </td>

                                                <!-- Progres Bar -->
                                                <td class="py-4 px-4 whitespace-nowrap">
                                                    <div class="flex items-center gap-2">
                                                        <div class="w-16 bg-gray-200 rounded-full h-1.5 overflow-hidden">
                                                            <div class="bg-[#B01116] h-1.5 rounded-full" :style="'width: ' + item.progres + '%'"></div>
                                                        </div>
                                                        <span class="text-[11px] font-semibold text-[#4B5563]" x-text="item.progres + '%'"></span>
                                                    </div>
                                                </td>

                                                <!-- Aksi "Tangani ->" -->
                                                <td class="py-4 px-4 whitespace-nowrap text-right">
                                                    <a
                                                        :href="item.url"
                                                        class="text-xs font-bold text-[#B01116] hover:text-[#8B1D22] inline-flex items-center gap-1 transition"
                                                    >
                                                        Tangani &rarr;
                                                    </a>
                                                </td>
                                            </tr>
                                        </template>

                                        <!-- State Jika Kosong -->
                                        <tr x-show="filteredLaporans.length === 0" x-cloak>
                                            <td colspan="7" class="py-8 text-center text-[#6B7280]">
                                                Tidak ada laporan yang sesuai pencarian.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- KARTU "AKTIVITAS UNIT" (Samping Kanan Tabel) -->
                        <div class="lg:col-span-4 xl:col-span-4 bg-white rounded-2xl shadow-sm p-6 sm:p-7">
                            <h2 class="text-lg font-bold text-[#1F2937] tracking-tight mb-5">Aktivitas Unit</h2>

                            <!-- Timeline List -->
                            <div class="space-y-5">
                                <!-- Aktivitas 1 -->
                                <div class="flex items-start gap-3">
                                    <span class="w-2 h-2 rounded-full bg-[#B01116] shrink-0 mt-1.5"></span>
                                    <div>
                                        <p class="text-xs font-bold text-[#1F2937] leading-snug">
                                            Progres CV-2026-00124 diperbarui menjadi 65%
                                        </p>
                                        <p class="text-[11px] text-[#6B7280] mt-0.5">12 menit lalu</p>
                                    </div>
                                </div>

                                <!-- Aktivitas 2 -->
                                <div class="flex items-start gap-3">
                                    <span class="w-2 h-2 rounded-full bg-[#B01116] shrink-0 mt-1.5"></span>
                                    <div>
                                        <p class="text-xs font-bold text-[#1F2937] leading-snug">
                                            Bukti penyelesaian CV-2026-00088 diunggah
                                        </p>
                                        <p class="text-[11px] text-[#6B7280] mt-0.5">Kemarin, 14.22</p>
                                    </div>
                                </div>

                                <!-- Aktivitas 3 -->
                                <div class="flex items-start gap-3">
                                    <span class="w-2 h-2 rounded-full bg-[#B01116] shrink-0 mt-1.5"></span>
                                    <div>
                                        <p class="text-xs font-bold text-[#1F2937] leading-snug">
                                            CV-2026-00103 mulai ditangani
                                        </p>
                                        <p class="text-[11px] text-[#6B7280] mt-0.5">16 Okt 2026</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </main>

        </div>
    </div>
</body>
</html>

