@php
    // Data statis laporan terbaru untuk dashboard mahasiswa
    $laporanTerbaru = [
        [
            'tiket' => 'CV-2026-00124',
            'tanggal' => '18 Okt 2026',
            'judul' => 'Permohonan Perbaikan AC Ruang Kelas',
            'kategori' => 'Satpras',
            'unit' => 'Direktorat Logistik dan Aset',
            'status' => 'Diproses',
            'badge_fg' => '#7C3AED',
            'badge_bg' => '#F5F3FF',
        ],
        [
            'tiket' => 'CV-2026-00119',
            'tanggal' => '16 Okt 2026',
            'judul' => 'Kendala Akses Portal Akademik',
            'kategori' => 'SSC',
            'unit' => 'Direktorat Pusat Teknologi Informasi',
            'status' => 'Diteruskan',
            'badge_fg' => '#2563EB',
            'badge_bg' => '#EFF6FF',
        ],
        [
            'tiket' => 'CV-2026-00108',
            'tanggal' => '12 Okt 2026',
            'judul' => 'Permasalahan Transkrip Nilai',
            'kategori' => 'SSC',
            'unit' => 'Program Studi S1 Sistem Informasi',
            'status' => 'Selesai',
            'badge_fg' => '#16A34A',
            'badge_bg' => '#ECFDF5',
        ],
        [
            'tiket' => 'CV-2026-00097',
            'tanggal' => '8 Okt 2026',
            'judul' => 'Usulan Penambahan Fasilitas Belajar',
            'kategori' => 'Satpras',
            'unit' => 'Belum Ditentukan',
            'status' => 'Menunggu Verifikasi',
            'badge_fg' => '#D97706',
            'badge_bg' => '#FFF7ED',
        ],
    ];

    $detailUrl = fn ($tiket) => \Illuminate\Support\Facades\Route::has('laporan.detail')
        ? route('laporan.detail', $tiket)
        : (\Illuminate\Support\Facades\Route::has('laporan.show')
            ? route('laporan.show', $tiket)
            : url('/laporan/detail/' . $tiket));
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#FFFFFF]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Dashboard Mahasiswa - CampusVoice Telkom University Surabaya</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS with Forms Plugin via CDN -->
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
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

        /* Linear Gradient Konten Utama: Merah #B01116 (kiri bawah) ke Abu-abu Gelap #58595B (kanan atas) */
        .content-gradient-bg {
            background: linear-gradient(to top right, #B01116 0%, #58595B 100%);
        }
    </style>
</head>
<body
    class="h-full antialiased text-[#252525] bg-[#B01116]"
    x-data="{
        mobileSidebarOpen: false,
        currentView: (new URLSearchParams(window.location.search).get('view') === 'detail' || new URLSearchParams(window.location.search).get('tiket')) ? 'detail' : 'dashboard',
        selectedTiket: new URLSearchParams(window.location.search).get('tiket') || 'CV-2026-00124',
        showDetail(tiket) {
            this.selectedTiket = tiket || 'CV-2026-00124';
            this.currentView = 'detail';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },
        backToDashboard() {
            this.currentView = 'dashboard';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }"
>
    <div class="min-h-screen flex flex-col lg:flex-row">

        <!-- ========================================================= -->
        <!-- SIDEBAR KIRI -->
        <!-- ========================================================= -->
        <aside
            class="fixed inset-y-0 left-0 z-50 w-64 bg-[#FFFFFF] flex flex-col justify-between border-r border-[#E5E7EB] transition-transform duration-200 lg:static lg:translate-x-0"
            :class="mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        >
            <div class="flex flex-col flex-1 overflow-y-auto">
                <!-- Logo -->
                <div class="px-6 py-5 flex items-center gap-3">
                    <img src="{{ asset('images/logo-campusvoice.png') }}" alt="CampusVoice Logo" class="w-8 h-8 object-contain shrink-0" />
                    <div>
                        <span class="text-[17px] font-extrabold text-[#B01116] tracking-tight">CampusVoice</span>
                        <p class="text-[10px] text-[#6B7280] font-medium leading-tight">Telkom University Surabaya</p>
                    </div>
                </div>

                <!-- Navigasi Menu -->
                <div class="px-4 py-3 flex-1">
                    <p class="px-3 text-[11px] font-bold text-[#252525] tracking-wider mb-2 uppercase">Mahasiswa</p>

                    <nav class="space-y-1">
                        <!-- Menu 1: Dashboard (AKTIF) -->
                        <a
                            href="{{ url('/dashboard') }}"
                            @click="backToDashboard()"
                            aria-current="page"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold bg-[#FCE8E8] text-[#B01116] transition"
                        >
                            <svg class="w-5 h-5 text-[#B01116]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="7" height="7" x="3" y="3" rx="1.5"/>
                                <rect width="7" height="7" x="14" y="3" rx="1.5"/>
                                <rect width="7" height="7" x="14" y="14" rx="1.5"/>
                                <rect width="7" height="7" x="3" y="14" rx="1.5"/>
                            </svg>
                            <span>Dashboard</span>
                        </a>

                        <!-- Menu 2: Buat Laporan -->
                        <a href="{{ route('laporan.create') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-[#252525] hover:bg-gray-50 transition group">
                            <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#252525]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            <span>Buat Laporan</span>
                        </a>

                        <!-- Menu 3: Riwayat Laporan -->
                        <a href="{{ route('laporan.riwayat') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-[#252525] hover:bg-gray-50 transition group">
                            <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#252525]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                                <line x1="16" y1="13" x2="8" y2="13"/>
                                <line x1="16" y1="17" x2="8" y2="17"/>
                                <polyline points="10 9 9 9 8 9"/>
                            </svg>
                            <span>Riwayat Laporan</span>
                        </a>

                        <!-- Menu 4: Notifikasi (Badge '3') -->
                        <a href="{{ route('notifikasi') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-medium text-[#252525] hover:bg-gray-50 transition group">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#252525]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
                                    <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                                </svg>
                                <span>Notifikasi</span>
                            </div>
                            <span class="inline-flex items-center justify-center w-5 h-5 text-[11px] font-bold text-[#FFFFFF] bg-[#B01116] rounded-full">
                                3
                            </span>
                        </a>

                        <!-- Menu 5: Profil Saya -->
                        <a href="{{ route('profil') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-[#252525] hover:bg-gray-50 transition group">
                            <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#252525]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                            <span>Profil Saya</span>
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Footer Sidebar: Keluar & Kotak Butuh Bantuan (#B01116) -->
            <div class="p-4 border-t border-[#E5E7EB] space-y-3">
                <button type="button" class="w-full flex items-center gap-2 px-3 py-2 text-sm font-semibold text-[#B01116] hover:bg-[#FCE8E8] rounded-xl transition">
                    <svg class="w-5 h-5 text-[#B01116]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                    <span>Keluar</span>
                </button>

                <div class="rounded-xl bg-[#B01116] p-4 text-[#FFFFFF] shadow-sm">
                    <h4 class="text-sm font-bold tracking-tight">Butuh bantuan?</h4>
                    <p class="text-[11px] text-[#FFFFFF]/85 mt-1 leading-snug">Hubungi pusat layanan CampusVoice.</p>
                    <a href="{{ route('pusat.bantuan') }}" class="mt-3 inline-block py-1.5 px-3 bg-[#FFFFFF] hover:bg-gray-100 text-[#B01116] text-xs font-bold rounded-lg text-center transition">
                        Pusat Bantuan
                    </a>
                </div>
            </div>
        </aside>

        <!-- Overlay Mobile -->
        <div x-show="mobileSidebarOpen" x-cloak @click="mobileSidebarOpen = false" class="fixed inset-0 z-40 bg-black/40 lg:hidden"></div>

        <!-- ========================================================= -->
        <!-- AREA UTAMA (Header + Konten) -->
        <!-- ========================================================= -->
        <div class="flex-1 flex flex-col min-w-0">

            <!-- HEADER ATAS -->
            <header class="bg-[#FFFFFF] border-b border-[#E5E7EB] h-16 flex items-center justify-between px-4 sm:px-8 shrink-0">
                <div class="flex items-center gap-3">
                    <button @click="mobileSidebarOpen = !mobileSidebarOpen" class="p-2 -ml-2 rounded-lg text-[#6B7280] hover:text-[#252525] hover:bg-gray-100 lg:hidden" type="button">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <div class="relative w-48 sm:w-80 md:w-96">
                        <input
                            type="text"
                            placeholder="Cari nomor tiket atau laporan..."
                            class="w-full bg-[#FFFFFF] border border-[#E5E7EB] rounded-lg py-2 px-3.5 text-xs sm:text-sm text-[#252525] placeholder-[#6B7280] focus:border-[#B01116] focus:outline-none focus:ring-1 focus:ring-[#B01116] transition"
                        />
                    </div>
                </div>

                <div class="flex items-center gap-4 sm:gap-5">
                    <a href="{{ route('notifikasi') }}" class="relative w-9 h-9 rounded-full border border-[#E5E7EB] flex items-center justify-center text-[#6B7280] hover:text-[#252525] hover:bg-gray-50 transition">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
                            <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                        </svg>
                        <span class="absolute -top-1 -right-1 flex items-center justify-center w-4 h-4 text-[10px] font-bold text-[#FFFFFF] bg-[#B01116] rounded-full ring-2 ring-[#FFFFFF]">
                            3
                        </span>
                    </a>

                    <a href="{{ route('profil') }}" class="flex items-center gap-2.5 group">
                        <div class="w-9 h-9 rounded-lg bg-[#FCE8E8] text-[#B01116] font-bold text-xs flex items-center justify-center shrink-0 group-hover:ring-2 group-hover:ring-[#B01116]/30 transition">
                            DA
                        </div>
                        <div class="hidden sm:block text-left">
                            <p class="text-xs font-bold text-[#252525] leading-tight group-hover:text-[#B01116] transition">Dimas Arya</p>
                            <p class="text-[11px] text-[#6B7280] leading-tight">Mahasiswa</p>
                        </div>
                    </a>
                </div>
            </header>

            <!-- ========================================================= -->
            <!-- KONTEN UTAMA: DASHBOARD ATAU DETAIL LAPORAN (Gradient BG) -->
            <!-- ========================================================= -->
            <main class="flex-1 content-gradient-bg px-4 sm:px-8 lg:px-10 py-8">
                <div class="max-w-7xl mx-auto space-y-6">

                    <!-- ===================================================== -->
                    <!-- STATE 1: DASHBOARD MAHASISWA (Statistik + Tabel)     -->
                    <!-- ===================================================== -->
                    <div x-show="currentView === 'dashboard'" class="space-y-6">

                        <!-- Sapaan (Kiri) & Tombol Buat Laporan Baru (Kanan) -->
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div>
                                <p class="text-[11px] sm:text-xs font-bold tracking-wider uppercase text-white/80 mb-1">
                                    SENIN, 19 OKTOBER 2026
                                </p>
                                <h1 class="text-2xl sm:text-3xl lg:text-[32px] font-extrabold text-white tracking-tight">
                                    Halo, Dimas!
                                </h1>
                                <p class="text-xs sm:text-sm text-white/90 mt-1">
                                    Pantau laporan dan sampaikan aspirasi Anda melalui CampusVoice.
                                </p>
                            </div>

                            <div>
                                <a
                                    href="{{ route('laporan.create') }}"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#FFFFFF] hover:bg-gray-50 text-xs sm:text-sm font-bold text-[#B01116] shadow-md transition cursor-pointer"
                                >
                                    <svg class="w-4 h-4 text-[#B01116]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="12" y1="5" x2="12" y2="19"/>
                                        <line x1="5" y1="12" x2="19" y2="12"/>
                                    </svg>
                                    <span>Buat Laporan Baru</span>
                                </a>
                            </div>
                        </div>

                        <!-- Grid 4 Kartu Statistik -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                            <!-- Kartu 1: Total Laporan -->
                            <div class="bg-[#FFFFFF] rounded-2xl p-5 shadow-xl flex items-center gap-4 transition hover:shadow-2xl">
                                <div class="w-12 h-12 rounded-xl bg-[#FCE8E8] flex items-center justify-center shrink-0">
                                    <svg class="w-6 h-6 text-[#B01116]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                        <polyline points="14 2 14 8 20 8"/>
                                        <line x1="16" y1="13" x2="8" y2="13"/>
                                        <line x1="16" y1="17" x2="8" y2="17"/>
                                        <polyline points="10 9 9 9 8 9"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-[#6B7280]">Total Laporan</p>
                                    <p class="text-2xl sm:text-3xl font-extrabold text-[#252525] mt-0.5 leading-none">8</p>
                                </div>
                            </div>

                            <!-- Kartu 2: Menunggu Verifikasi -->
                            <div class="bg-[#FFFFFF] rounded-2xl p-5 shadow-xl flex items-center gap-4 transition hover:shadow-2xl">
                                <div class="w-12 h-12 rounded-xl bg-[#FFF7ED] flex items-center justify-center shrink-0">
                                    <svg class="w-6 h-6 text-[#D97706]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"/>
                                        <polyline points="12 6 12 12 16 14"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-[#6B7280]">Menunggu Verifikasi</p>
                                    <p class="text-2xl sm:text-3xl font-extrabold text-[#252525] mt-0.5 leading-none">1</p>
                                </div>
                            </div>

                            <!-- Kartu 3: Sedang Diproses -->
                            <div class="bg-[#FFFFFF] rounded-2xl p-5 shadow-xl flex items-center gap-4 transition hover:shadow-2xl">
                                <div class="w-12 h-12 rounded-xl bg-[#EFF6FF] flex items-center justify-center shrink-0">
                                    <svg class="w-6 h-6 text-[#2563EB]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="18" r="3"/>
                                        <circle cx="6" cy="6" r="3"/>
                                        <circle cx="18" cy="6" r="3"/>
                                        <path d="M18 9v1a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V9"/>
                                        <path d="M12 12v3"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-[#6B7280]">Sedang Diproses</p>
                                    <p class="text-2xl sm:text-3xl font-extrabold text-[#252525] mt-0.5 leading-none">3</p>
                                </div>
                            </div>

                            <!-- Kartu 4: Laporan Selesai -->
                            <div class="bg-[#FFFFFF] rounded-2xl p-5 shadow-xl flex items-center gap-4 transition hover:shadow-2xl">
                                <div class="w-12 h-12 rounded-xl bg-[#ECFDF5] flex items-center justify-center shrink-0">
                                    <svg class="w-6 h-6 text-[#16A34A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-[#6B7280]">Laporan Selesai</p>
                                    <p class="text-2xl sm:text-3xl font-extrabold text-[#252525] mt-0.5 leading-none">4</p>
                                </div>
                            </div>

                        </div>

                        <!-- Tabel: Laporan Terbaru (Kartu Putih Besar) -->
                        <div class="bg-[#FFFFFF] rounded-2xl shadow-xl p-5 sm:p-6">

                            <!-- Header Tabel -->
                            <div class="flex items-center justify-between pb-5 border-b border-[#E5E7EB]">
                                <div>
                                    <h2 class="text-base sm:text-lg font-bold text-[#252525] tracking-tight">
                                        Laporan Terbaru
                                    </h2>
                                    <p class="text-xs sm:text-sm text-[#6B7280] mt-0.5">
                                        Perkembangan laporan yang terakhir Anda kirimkan.
                                    </p>
                                </div>

                                <a
                                    href="{{ route('laporan.riwayat') }}"
                                    class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold text-[#B01116] hover:text-[#8e0d12] transition"
                                >
                                    <span>Lihat semua</span>
                                    <svg class="w-4 h-4 text-[#B01116]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="5" y1="12" x2="19" y2="12"/>
                                        <polyline points="12 5 19 12 12 19"/>
                                    </svg>
                                </a>
                            </div>

                            <!-- Tabel -->
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-[760px] text-left">
                                    <thead>
                                        <tr class="border-b border-[#E5E7EB]">
                                            <th class="py-3.5 pr-4 text-[11px] font-semibold uppercase tracking-wider text-[#6B7280]">Nomor Tiket</th>
                                            <th class="py-3.5 px-4 text-[11px] font-semibold uppercase tracking-wider text-[#6B7280]">Judul Laporan</th>
                                            <th class="py-3.5 px-4 text-[11px] font-semibold uppercase tracking-wider text-[#6B7280]">Kategori</th>
                                            <th class="py-3.5 px-4 text-[11px] font-semibold uppercase tracking-wider text-[#6B7280]">Unit Penangan</th>
                                            <th class="py-3.5 px-4 text-[11px] font-semibold uppercase tracking-wider text-[#6B7280]">Status</th>
                                            <th class="py-3.5 pl-4 text-right"><span class="sr-only">Aksi</span></th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[#E5E7EB]">
                                        @foreach ($laporanTerbaru as $item)
                                            <tr class="hover:bg-gray-50/70 transition">
                                                <!-- Nomor Tiket & Tanggal -->
                                                <td class="py-4 pr-4 align-middle">
                                                    <p class="text-sm font-bold text-[#252525] whitespace-nowrap">{{ $item['tiket'] }}</p>
                                                    <p class="text-xs text-[#6B7280] mt-0.5">{{ $item['tanggal'] }}</p>
                                                </td>

                                                <!-- Judul Laporan -->
                                                <td class="py-4 px-4 align-middle">
                                                    <p class="text-sm font-bold text-[#252525]">{{ $item['judul'] }}</p>
                                                </td>

                                                <!-- Kategori -->
                                                <td class="py-4 px-4 align-middle text-sm text-[#252525] whitespace-nowrap">
                                                    {{ $item['kategori'] }}
                                                </td>

                                                <!-- Unit Penangan -->
                                                <td class="py-4 px-4 align-middle text-sm text-[#252525]">
                                                    {{ $item['unit'] }}
                                                </td>

                                                <!-- Status Badge -->
                                                <td class="py-4 px-4 align-middle whitespace-nowrap">
                                                    <span
                                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold"
                                                        style="color: {{ $item['badge_fg'] }}; background-color: {{ $item['badge_bg'] }};"
                                                    >
                                                        <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $item['badge_fg'] }};"></span>
                                                        {{ $item['status'] }}
                                                    </span>
                                                </td>

                                                <!-- Tombol Detail (Pemicu State Detail Laporan) -->
                                                <td class="py-4 pl-4 align-middle text-right whitespace-nowrap">
                                                    <button
                                                        type="button"
                                                        @click="showDetail('{{ $item['tiket'] }}')"
                                                        class="inline-flex items-center gap-1 text-sm font-bold text-[#B01116] hover:text-[#8e0d12] transition cursor-pointer"
                                                    >
                                                        <span>Detail</span>
                                                        <svg class="w-4 h-4 text-[#B01116]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                                                            <line x1="5" y1="12" x2="19" y2="12"/>
                                                            <polyline points="12 5 19 12 12 19"/>
                                                        </svg>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Banner Privasi & Perlindungan Identitas -->
                        <div class="bg-[#FFFFFF] rounded-2xl shadow-xl p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-[#EFF6FF] text-[#2563EB] flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-[#2563EB]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                        <path d="m9 12 2 2 4-4"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-[#252525]">
                                        Identitas Anda tetap terlindungi
                                    </h3>
                                    <p class="text-xs text-[#6B7280] mt-0.5 leading-relaxed">
                                        Pada laporan anonim, identitas hanya dapat dilihat Admin SSC untuk verifikasi dan tidak ditampilkan kepada petugas unit.
                                    </p>
                                </div>
                            </div>

                            <div class="sm:shrink-0">
                                <a
                                    href="{{ route('laporan.create') }}"
                                    class="inline-flex items-center gap-1 text-xs sm:text-sm font-bold text-[#B01116] hover:text-[#8e0d12] transition whitespace-nowrap"
                                >
                                    <span>Pelajari saat membuat laporan</span>
                                    <svg class="w-4 h-4 text-[#B01116]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="5" y1="12" x2="19" y2="12"/>
                                        <polyline points="12 5 19 12 12 19"/>
                                    </svg>
                                </a>
                            </div>
                        </div>

                    </div>

                    <!-- ===================================================== -->
                    <!-- STATE 2: DETAIL LAPORAN (Muncul saat tekan Detail ->) -->
                    <!-- ===================================================== -->
                    <div x-show="currentView === 'detail'" x-cloak class="space-y-6">

                        <!-- Header Detail Laporan (Teks Putih di atas Gradient) -->
                        <div>
                            <!-- Tombol Kembali ke Dashboard -->
                            <button
                                type="button"
                                @click="backToDashboard()"
                                class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-lg bg-black/20 hover:bg-black/30 text-white text-xs font-semibold mb-3 transition cursor-pointer backdrop-blur-xs"
                            >
                                <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="19" y1="12" x2="5" y2="12"/>
                                    <polyline points="12 19 5 12 12 5"/>
                                </svg>
                                <span>Kembali ke Dashboard</span>
                            </button>

                            <!-- Nomor Tiket -->
                            <p class="text-xs sm:text-sm font-semibold tracking-wider text-white/90 uppercase" x-text="selectedTiket">
                                CV-2026-00124
                            </p>

                            <!-- Judul Besar -->
                            <h1 class="text-2xl sm:text-3xl lg:text-[34px] font-extrabold text-white tracking-tight mt-1">
                                Permohonan Perbaikan AC Ruang Kelas
                            </h1>

                            <!-- Waktu Pengiriman -->
                            <p class="text-xs sm:text-sm text-white/85 mt-1.5">
                                Dikirim pada 18 Oktober 2026, 09.14 WIB
                            </p>
                        </div>

                        <!-- Grid 2 Kolom: Kolom Kiri (Lebar) & Kolom Kanan (Sempit) -->
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                            <!-- ================================================= -->
                            <!-- KOLOM KIRI (2 KARTU BESAR)                        -->
                            <!-- ================================================= -->
                            <div class="lg:col-span-8 space-y-6">

                                <!-- KARTU 1: Detail Utama -->
                                <div class="bg-[#FFFFFF] rounded-2xl shadow-xl p-6 sm:p-7 space-y-5">

                                    <!-- Baris Badge & Progres -->
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                        <div class="flex items-center gap-2.5 flex-wrap">
                                            <!-- Badge Status: Diproses (Ungu) -->
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold text-[#7C3AED] bg-[#F5F3FF]">
                                                <span class="w-1.5 h-1.5 rounded-full bg-[#7C3AED]"></span>
                                                Diproses
                                            </span>

                                            <!-- Badge Prioritas: Prioritas Tinggi (Pink/Merah) -->
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold text-[#B01116] bg-[#FCE8E8]">
                                                Prioritas Tinggi
                                            </span>
                                        </div>

                                        <!-- Teks Progres 65% -->
                                        <p class="text-xs sm:text-sm text-[#6B7280]">
                                            Progres <span class="font-extrabold text-[#252525]">65%</span>
                                        </p>
                                    </div>

                                    <!-- Progress Bar Merah Tua (65%) -->
                                    <div class="w-full bg-[#E5E7EB] h-2 rounded-full overflow-hidden">
                                        <div class="bg-[#B01116] h-full rounded-full w-[65%]"></div>
                                    </div>

                                    <!-- Grid 2x2 Info (Background Abu-abu Sangat Terang) -->
                                    <div class="bg-[#F9FAFB] rounded-xl p-4 sm:p-5 border border-[#F3F4F6] grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-[11px] font-semibold uppercase tracking-wider text-[#6B7280]">KATEGORI</p>
                                            <p class="text-sm font-bold text-[#252525] mt-1">Satpras</p>
                                        </div>
                                        <div>
                                            <p class="text-[11px] font-semibold uppercase tracking-wider text-[#6B7280]">LOKASI</p>
                                            <p class="text-sm font-bold text-[#252525] mt-1">Gedung A, Ruang A-301</p>
                                        </div>
                                        <div>
                                            <p class="text-[11px] font-semibold uppercase tracking-wider text-[#6B7280]">UNIT PENANGAN</p>
                                            <p class="text-sm font-bold text-[#252525] mt-1">Direktorat Logistik dan Aset</p>
                                        </div>
                                        <div>
                                            <p class="text-[11px] font-semibold uppercase tracking-wider text-[#6B7280]">PELAPOR</p>
                                            <p class="text-sm font-bold text-[#252525] mt-1">Dimas Arya Irwansyah • 102062400113</p>
                                        </div>
                                    </div>

                                    <!-- Deskripsi Laporan -->
                                    <div class="pt-1">
                                        <h3 class="text-sm font-bold text-[#252525] mb-2">Deskripsi laporan</h3>
                                        <p class="text-xs sm:text-sm text-[#6B7280] leading-relaxed">
                                            AC di ruang kelas A-301 tidak berfungsi sejak dua hari terakhir. Kondisi ruangan menjadi panas dan mengganggu proses perkuliahan pada siang hari. Mohon dilakukan pemeriksaan dan perbaikan.
                                        </p>
                                    </div>

                                    <!-- Bukti Pendukung -->
                                    <div class="pt-1">
                                        <h3 class="text-sm font-bold text-[#252525] mb-2">Bukti pendukung</h3>
                                        <div class="border border-[#E5E7EB] rounded-xl p-3.5 flex items-center justify-between hover:bg-gray-50/70 transition">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center shrink-0 text-[#6B7280]">
                                                    <svg class="w-5 h-5 text-[#6B7280]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                                        <polyline points="14 2 14 8 20 8"/>
                                                        <line x1="16" y1="13" x2="8" y2="13"/>
                                                        <line x1="16" y1="17" x2="8" y2="17"/>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-xs sm:text-sm font-bold text-[#252525]">foto_ac_ruang_a301.jpg</p>
                                                    <p class="text-[11px] text-[#6B7280] mt-0.5">1.8 MB • JPG</p>
                                                </div>
                                            </div>

                                            <button
                                                type="button"
                                                class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold text-[#B01116] hover:text-[#8e0d12] transition cursor-pointer"
                                            >
                                                <svg class="w-4 h-4 text-[#B01116]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                                    <circle cx="12" cy="12" r="3"/>
                                                </svg>
                                                <span>Lihat</span>
                                            </button>
                                        </div>
                                    </div>

                                </div>

                                <!-- KARTU 2: Diskusi dan Tanggapan -->
                                <div class="bg-[#FFFFFF] rounded-2xl shadow-xl p-6 sm:p-7 space-y-6">

                                    <!-- Header Diskusi -->
                                    <div>
                                        <h2 class="text-base sm:text-lg font-bold text-[#252525] tracking-tight">
                                            Diskusi dan Tanggapan
                                        </h2>
                                        <p class="text-xs sm:text-sm text-[#6B7280] mt-0.5">
                                            Komunikasi terkait penanganan laporan.
                                        </p>
                                    </div>

                                    <!-- Daftar Tanggapan / Chat -->
                                    <div class="space-y-5">
                                        <!-- Chat 1: Admin SSC -->
                                        <div>
                                            <div class="flex items-start justify-between gap-3">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-9 h-9 rounded-xl bg-[#FCE8E8] text-[#B01116] font-bold text-xs flex items-center justify-center shrink-0">
                                                        AS
                                                    </div>
                                                    <div class="flex items-center gap-2 flex-wrap">
                                                        <p class="text-sm font-bold text-[#252525]">Admin SSC</p>
                                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold text-[#2563EB] bg-[#EFF6FF]">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-[#2563EB]"></span>
                                                            Admin SSC
                                                        </span>
                                                    </div>
                                                </div>
                                                <span class="text-xs text-[#6B7280] shrink-0">18 Okt, 11.20</span>
                                            </div>
                                            <p class="text-xs sm:text-sm text-[#252525] mt-2.5 pl-12 leading-relaxed">
                                                Laporan telah diverifikasi dan diteruskan ke Direktorat Logistik dan Aset.
                                            </p>
                                        </div>

                                        <!-- Garis Pembatas -->
                                        <div class="border-t border-[#E5E7EB]"></div>

                                        <!-- Chat 2: Budi Santoso (Petugas Unit) -->
                                        <div>
                                            <div class="flex items-start justify-between gap-3">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-9 h-9 rounded-xl bg-[#FCE8E8] text-[#B01116] font-bold text-xs flex items-center justify-center shrink-0">
                                                        BS
                                                    </div>
                                                    <div class="flex items-center gap-2 flex-wrap">
                                                        <p class="text-sm font-bold text-[#252525]">Budi Santoso</p>
                                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold text-[#4B5563] bg-[#F3F4F6]">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-[#6B7280]"></span>
                                                            Petugas Unit
                                                        </span>
                                                    </div>
                                                </div>
                                                <span class="text-xs text-[#6B7280] shrink-0">19 Okt, 08.45</span>
                                            </div>
                                            <p class="text-xs sm:text-sm text-[#252525] mt-2.5 pl-12 leading-relaxed">
                                                Tim teknis telah melakukan pengecekan. Komponen pendingin perlu diganti dan sedang kami siapkan.
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Form Tulis Tanggapan -->
                                    <div class="pt-4 border-t border-[#E5E7EB]">
                                        <label for="input-tanggapan" class="block text-sm font-bold text-[#252525] mb-2">
                                            Tulis tanggapan
                                        </label>
                                        <textarea
                                            id="input-tanggapan"
                                            rows="4"
                                            placeholder="Tulis pesan yang relevan dengan laporan..."
                                            class="w-full rounded-xl border border-[#E5E7EB] bg-white p-3.5 text-xs sm:text-sm text-[#252525] placeholder-[#6B7280] focus:border-[#B01116] focus:outline-none focus:ring-1 focus:ring-[#B01116] transition resize-none"
                                        ></textarea>

                                        <div class="mt-3">
                                            <button
                                                type="button"
                                                class="px-5 py-2.5 rounded-xl bg-[#B01116] hover:bg-[#8e0d12] text-xs sm:text-sm font-bold text-white shadow-sm transition inline-flex items-center gap-2 cursor-pointer"
                                            >
                                                <span>Kirim Tanggapan</span>
                                            </button>
                                        </div>
                                    </div>

                                </div>

                            </div>

                            <!-- ================================================= -->
                            <!-- KOLOM KANAN (2 KARTU SAMPING)                     -->
                            <!-- ================================================= -->
                            <div class="lg:col-span-4 space-y-6">

                                <!-- KARTU 1: Riwayat Status (Stepper Vertikal) -->
                                <div class="bg-[#FFFFFF] rounded-2xl shadow-xl p-6 sm:p-7">
                                    <h3 class="text-base font-bold text-[#252525] mb-6">
                                        Riwayat Status
                                    </h3>

                                    <div class="relative space-y-6">

                                        <!-- Step 1: Diproses (Aktif / Lingkaran Ring Merah) -->
                                        <div class="flex items-start gap-3.5 relative">
                                            <!-- Icon Ring Merah -->
                                            <div class="w-6 h-6 rounded-full border-2 border-[#B01116] flex items-center justify-center bg-white shrink-0 mt-0.5 z-10">
                                                <span class="w-2 h-2 rounded-full bg-[#B01116]"></span>
                                            </div>
                                            <!-- Garis vertikal ke bawah -->
                                            <div class="absolute left-3 top-6 w-0.5 bg-[#E5E7EB] h-14 -translate-x-1/2"></div>

                                            <div>
                                                <h4 class="text-sm font-bold text-[#252525]">Diproses</h4>
                                                <p class="text-[11px] text-[#6B7280] mt-0.5">19 Okt 2026, 08.45</p>
                                                <p class="text-xs text-[#6B7280] mt-1 leading-snug">
                                                    Pemeriksaan teknis sedang berlangsung.
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Step 2: Diteruskan ke Unit (Centang Hijau) -->
                                        <div class="flex items-start gap-3.5 relative">
                                            <!-- Icon Centang Hijau -->
                                            <div class="w-6 h-6 rounded-full bg-[#DCFCE7] text-[#16A34A] flex items-center justify-center shrink-0 mt-0.5 z-10">
                                                <svg class="w-3.5 h-3.5 text-[#16A34A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="20 6 9 17 4 12"/>
                                                </svg>
                                            </div>
                                            <!-- Garis vertikal ke bawah -->
                                            <div class="absolute left-3 top-6 w-0.5 bg-[#E5E7EB] h-14 -translate-x-1/2"></div>

                                            <div>
                                                <h4 class="text-sm font-bold text-[#252525]">Diteruskan ke Unit</h4>
                                                <p class="text-[11px] text-[#6B7280] mt-0.5">18 Okt 2026, 11.20</p>
                                                <p class="text-xs text-[#6B7280] mt-1 leading-snug">
                                                    Diteruskan ke Direktorat Logistik dan Aset.
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Step 3: Menunggu Verifikasi (Centang Hijau) -->
                                        <div class="flex items-start gap-3.5 relative">
                                            <!-- Icon Centang Hijau -->
                                            <div class="w-6 h-6 rounded-full bg-[#DCFCE7] text-[#16A34A] flex items-center justify-center shrink-0 mt-0.5 z-10">
                                                <svg class="w-3.5 h-3.5 text-[#16A34A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="20 6 9 17 4 12"/>
                                                </svg>
                                            </div>
                                            <!-- Garis vertikal ke bawah -->
                                            <div class="absolute left-3 top-6 w-0.5 bg-[#E5E7EB] h-14 -translate-x-1/2"></div>

                                            <div>
                                                <h4 class="text-sm font-bold text-[#252525]">Menunggu Verifikasi</h4>
                                                <p class="text-[11px] text-[#6B7280] mt-0.5">18 Okt 2026, 09.14</p>
                                                <p class="text-xs text-[#6B7280] mt-1 leading-snug">
                                                    Laporan diterima oleh sistem.
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Step 4: Dikirim (Centang Hijau) -->
                                        <div class="flex items-start gap-3.5 relative">
                                            <!-- Icon Centang Hijau -->
                                            <div class="w-6 h-6 rounded-full bg-[#DCFCE7] text-[#16A34A] flex items-center justify-center shrink-0 mt-0.5 z-10">
                                                <svg class="w-3.5 h-3.5 text-[#16A34A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="20 6 9 17 4 12"/>
                                                </svg>
                                            </div>

                                            <div>
                                                <h4 class="text-sm font-bold text-[#252525]">Dikirim</h4>
                                                <p class="text-[11px] text-[#6B7280] mt-0.5">18 Okt 2026, 09.14</p>
                                                <p class="text-xs text-[#6B7280] mt-1 leading-snug">
                                                    Nomor tiket berhasil dibuat.
                                                </p>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <!-- KARTU 2: Aksi Lanjutan -->
                                <div class="bg-[#FFFFFF] rounded-2xl shadow-xl p-6 sm:p-7 space-y-4">
                                    <h3 class="text-sm font-bold text-[#252525]">
                                        Belum sepenuhnya selesai?
                                    </h3>
                                    <p class="text-xs text-[#6B7280] leading-relaxed">
                                        Setelah laporan selesai, Anda dapat mengajukan pembukaan kembali dengan alasan yang jelas.
                                    </p>

                                    <!-- Tombol Buka Kembali Laporan -->
                                    <button
                                        type="button"
                                        class="w-full py-2.5 rounded-xl border border-[#E5E7EB] bg-[#FFFFFF] hover:bg-gray-50 text-xs sm:text-sm font-bold text-[#252525] shadow-xs text-center transition cursor-pointer"
                                    >
                                        Buka Kembali Laporan
                                    </button>

                                    <!-- Link Beri Penilaian -->
                                    <div class="pt-1 text-center">
                                        <button
                                            type="button"
                                            class="text-xs sm:text-sm font-bold text-[#B01116] hover:underline cursor-pointer"
                                        >
                                            Beri Penilaian
                                        </button>
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
