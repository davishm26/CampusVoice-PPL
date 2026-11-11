<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#FFFFFF]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Dashboard Admin SSC - CampusVoice Telkom University Surabaya</title>

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
                    },
                    colors: {
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

        /* Linear Gradient Konten Utama: Merah #B01116 ke Abu-abu Gelap #58595B (sama seperti Dashboard Mahasiswa) */
        .content-gradient-bg {
            background: linear-gradient(to top right, #B01116 0%, #58595B 100%);
        }
    </style>
</head>
<body
    class="h-full antialiased text-[#252525] bg-[#B01116]"
    x-data="{
        mobileSidebarOpen: false,
        searchQuery: '',
        matchesQuery(item) {
            if (!this.searchQuery.trim()) return true;
            const q = this.searchQuery.toLowerCase();
            return (item.tiket && item.tiket.toLowerCase().includes(q)) ||
                   (item.judul && item.judul.toLowerCase().includes(q)) ||
                   (item.pelapor && item.pelapor.toLowerCase().includes(q)) ||
                   (item.prodi && item.prodi.toLowerCase().includes(q)) ||
                   (item.kategori && item.kategori.toLowerCase().includes(q));
        }
    }"
>
    <div class="min-h-screen flex flex-col lg:flex-row">

        <!-- ========================================================= -->
        <!-- SIDEBAR KIRI (ADMIN SSC) -->
        <!-- ========================================================= -->
        <aside
            class="fixed inset-y-0 left-0 z-50 w-64 bg-[#FFFFFF] flex flex-col justify-between border-r border-[#E5E7EB] transition-transform duration-200 lg:static lg:translate-x-0"
            :class="mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        >
            <div class="flex flex-col flex-1 overflow-y-auto">
                <!-- Logo -->
                <div class="px-6 py-5 flex items-center gap-3">
                    <img src="{{ asset('images/logo-campusvoice.png') }}" alt="CampusVoice Logo" class="w-8 h-8 object-contain shrink-0" onerror="this.onerror=null; this.src='https://placehold.co/32x32/B01116/white?text=CV';" />
                    <div>
                        <span class="text-[17px] font-extrabold text-[#B01116] tracking-tight">CampusVoice</span>
                        <p class="text-[10px] text-[#6B7280] font-medium leading-tight">Telkom University Surabaya</p>
                    </div>
                </div>

                <!-- Navigasi Menu Admin SSC -->
                <div class="px-4 py-3 flex-1">
                    <p class="px-3 text-[11px] font-bold text-[#6B7280] tracking-wider mb-2 uppercase">Menu Utama</p>

                    <nav class="space-y-1">
                        <!-- Menu 1: Dashboard (AKTIF) -->
                        <a
                            href="{{ route('admin.dashboard') }}"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold bg-[#FCE8E8] text-[#B01116] transition shadow-xs"
                        >
                            <svg class="w-5 h-5 text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="7" height="7"></rect>
                                <rect x="14" y="3" width="7" height="7"></rect>
                                <rect x="14" y="14" width="7" height="7"></rect>
                                <rect x="3" y="14" width="7" height="7"></rect>
                            </svg>
                            <span>Dashboard</span>
                        </a>

                        <!-- Menu 2: Verifikasi Laporan -->
                        <a
                            href="{{ route('admin.verifikasi') }}"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#252525] hover:bg-gray-100 hover:text-[#B01116] transition group"
                        >
                            <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                <path d="m9 12 2 2 4-4"/>
                            </svg>
                            <span>Verifikasi Laporan</span>
                        </a>

                        <!-- Menu 3: Monitoring Laporan -->
                        <a
                            href="{{ route('admin.monitoring') }}"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#252525] hover:bg-gray-100 hover:text-[#B01116] transition group"
                        >
                            <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="20" x2="12" y2="10"/>
                                <line x1="18" y1="20" x2="18" y2="4"/>
                                <line x1="6" y1="20" x2="6" y2="16"/>
                            </svg>
                            <span>Monitoring Laporan</span>
                        </a>

                        <!-- Menu 4: Rekapitulasi -->
                        <a
                            href="{{ route('admin.rekapitulasi') }}"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#252525] hover:bg-gray-100 hover:text-[#B01116] transition group"
                        >
                            <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="7 10 12 15 17 10"/>
                                <line x1="12" y1="15" x2="12" y2="3"/>
                            </svg>
                            <span>Rekapitulasi</span>
                        </a>

                        <!-- Menu 5: Notifikasi -->
                        <a
                            href="{{ route('admin.notifikasi') }}"
                            class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#252525] hover:bg-gray-100 hover:text-[#B01116] transition group"
                        >
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                                    <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                                </svg>
                                <span>Notifikasi</span>
                            </div>
                            <span class="w-5 h-5 rounded-full bg-[#B01116] text-[#FFFFFF] text-[11px] font-bold flex items-center justify-center">
                                {{ $unreadCount }}
                            </span>
                        </a>
                    </nav>
                </div>

                <!-- Profil & Keluar -->
                <div class="px-4 py-3 border-t border-[#E5E7EB] space-y-1">
                    <a
                        href="{{ route('admin.profil') }}"
                        class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#252525] hover:bg-gray-100 hover:text-[#B01116] transition group"
                    >
                        <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        <span>Profil Saya</span>
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            type="submit"
                            class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#B01116] hover:bg-red-50 transition cursor-pointer text-left"
                        >
                            <svg class="w-5 h-5 text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                <polyline points="16 17 21 12 16 7"/>
                                <line x1="21" y1="12" x2="9" y2="12"/>
                            </svg>
                            <span>Keluar</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Kartu Bantuan -->
            <div class="p-4 border-t border-[#E5E7EB]">
                <div class="rounded-xl bg-[#B01116] p-4 text-[#FFFFFF] shadow-sm">
                    <h4 class="text-sm font-bold tracking-tight text-[#FFFFFF]">Butuh bantuan?</h4>
                    <p class="text-[11px] text-[#FFFFFF]/85 mt-1 leading-snug">Hubungi pusat layanan CampusVoice.</p>
                    <a href="{{ route('pusat.bantuan') }}" class="mt-3 block w-full py-1.5 px-3 bg-[#FFFFFF] hover:bg-gray-100 text-[#B01116] text-xs font-bold rounded-lg text-center shadow-xs transition">
                        Pusat Bantuan
                    </a>
                </div>
            </div>
        </aside>

        <!-- Overlay Mobile -->
        <div x-show="mobileSidebarOpen" x-cloak @click="mobileSidebarOpen = false" class="fixed inset-0 z-40 bg-black/40 backdrop-blur-xs lg:hidden"></div>

        <!-- ========================================================= -->
        <!-- AREA UTAMA (Header + Konten Dashboard) -->
        <!-- ========================================================= -->
        <div class="flex-1 flex flex-col min-w-0">

            <!-- HEADER ATAS (NAVBAR) -->
            <header class="bg-[#FFFFFF] border-b border-[#E5E7EB] px-4 sm:px-8 py-3.5 flex items-center justify-between sticky top-0 z-30 shadow-xs">
                <!-- Hamburger Mobile & Search Bar Header -->
                <div class="flex items-center gap-3">
                    <button
                        type="button"
                        @click="mobileSidebarOpen = !mobileSidebarOpen"
                        class="p-2 -ml-2 rounded-lg text-[#6B7280] hover:text-[#252525] hover:bg-gray-100 lg:hidden transition"
                        title="Buka Menu"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <!-- Search Bar Header -->
                    <div class="relative w-64 sm:w-80 md:w-96">
                        <input
                            type="text"
                            x-model="searchQuery"
                            placeholder="Cari nomor tiket atau laporan..."
                            class="w-full bg-[#F9FAFB] border border-[#E5E7EB] rounded-xl py-2 px-3.5 text-xs sm:text-sm text-[#252525] placeholder-[#9CA3AF] focus:bg-white focus:border-[#B01116] focus:outline-none focus:ring-1 focus:ring-[#B01116] transition"
                        />
                    </div>
                </div>

                <!-- Bagian Kanan: Lonceng Notifikasi & Profil Admin SSC -->
                <div class="flex items-center gap-3 sm:gap-4">
                    <!-- Tombol Lonceng Notifikasi -->
                    <a
                        href="{{ route('admin.notifikasi') }}"
                        class="relative p-2 rounded-lg text-[#6B7280] hover:text-[#B01116] hover:bg-gray-100 transition cursor-pointer"
                        title="Notifikasi"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span class="absolute top-1 right-1 w-4 h-4 rounded-full bg-[#B01116] text-[#FFFFFF] text-[10px] font-bold flex items-center justify-center">
                            {{ $unreadCount }}
                        </span>
                    </a>

                    <!-- Pemisah Vertikal -->
                    <div class="h-6 w-px bg-[#E5E7EB]"></div>

                    <!-- Profil Admin SSC: Siti Rahmawati -->
                    <a href="{{ route('admin.profil') }}" class="flex items-center gap-2.5 group">
                        <div class="w-9 h-9 rounded-xl bg-[#FCE8E8] text-[#B01116] font-bold text-xs flex items-center justify-center shrink-0 ring-1 ring-[#B01116]/20">
                            {{ $authUser->initials ?? 'SR' }}
                        </div>
                        <div class="hidden sm:block text-left">
                            <p class="text-xs font-bold text-[#252525] leading-tight group-hover:text-[#B01116] transition">{{ $authUser->name ?? 'Siti Rahmawati' }}</p>
                            <p class="text-[11px] text-[#6B7280] leading-tight">{{ $authUser->role ?? 'Admin SSC' }}</p>
                        </div>
                    </a>
                </div>
            </header>

            <!-- ========================================================= -->
            <!-- AREA KONTEN UTAMA DASHBOARD ADMIN SSC -->
            <!-- ========================================================= -->
            <main class="flex-1 content-gradient-bg px-4 sm:px-8 lg:px-10 py-7 space-y-6">

                <!-- Header Action: Judul Halaman & Tombol Buka Antrian Verifikasi -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <p class="text-[11px] sm:text-xs font-bold tracking-wider uppercase text-white/80 mb-0.5">
                            OPERASIONAL SSC
                        </p>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                            Dashboard Admin SSC
                        </h1>
                        <p class="text-xs sm:text-sm text-white/90 mt-0.5">
                            Ringkasan tren, distribusi penanganan, dan antrean laporan masuk mahasiswa.
                        </p>
                    </div>

                    <div>
                        <a
                            href="{{ route('admin.verifikasi') }}"
                            class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-[#FFFFFF] hover:bg-gray-50 text-xs sm:text-sm font-bold text-[#B01116] shadow-md transition cursor-pointer"
                        >
                            Buka Antrian Verifikasi
                        </a>
                    </div>
                </div>

                <!-- ========================================================= -->
                <!-- 1. 6 KARTU STATISTIK LAPORAN -->
                <!-- ========================================================= -->
                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-3.5">
                    
                    <!-- Kartu 1: Total Laporan -->
                    <div class="bg-white rounded-2xl p-4 shadow-xl flex items-center gap-3.5 transition hover:shadow-2xl">
                        <div class="w-11 h-11 rounded-xl bg-red-50 flex items-center justify-center shrink-0 text-[#B01116]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs text-gray-500 font-medium truncate">Total Laporan</p>
                            <p class="text-xl sm:text-2xl font-extrabold text-gray-900 tracking-tight leading-tight">
                                {{ number_format($totalLaporan, 0, ',', '.') }}
                            </p>
                            <p class="text-[11px] font-semibold text-emerald-600 leading-tight">+12% bulan ini</p>
                        </div>
                    </div>

                    <!-- Kartu 2: Menunggu Verifikasi -->
                    <div class="bg-white rounded-2xl p-4 shadow-xl flex items-center gap-3.5 transition hover:shadow-2xl">
                        <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center shrink-0 text-amber-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs text-gray-500 font-medium truncate">Menunggu Verifikasi</p>
                            <p class="text-xl sm:text-2xl font-extrabold text-gray-900 tracking-tight leading-tight">
                                {{ number_format($menungguVerifikasi, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    <!-- Kartu 3: Diteruskan -->
                    <div class="bg-white rounded-2xl p-4 shadow-xl flex items-center gap-3.5 transition hover:shadow-2xl">
                        <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center shrink-0 text-blue-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs text-gray-500 font-medium truncate">Diteruskan</p>
                            <p class="text-xl sm:text-2xl font-extrabold text-gray-900 tracking-tight leading-tight">
                                {{ number_format($diteruskan, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    <!-- Kartu 4: Diproses -->
                    <div class="bg-white rounded-2xl p-4 shadow-xl flex items-center gap-3.5 transition hover:shadow-2xl">
                        <div class="w-11 h-11 rounded-xl bg-purple-50 flex items-center justify-center shrink-0 text-purple-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs text-gray-500 font-medium truncate">Diproses</p>
                            <p class="text-xl sm:text-2xl font-extrabold text-gray-900 tracking-tight leading-tight">
                                {{ number_format($diproses, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    <!-- Kartu 5: Selesai -->
                    <div class="bg-white rounded-2xl p-4 shadow-xl flex items-center gap-3.5 transition hover:shadow-2xl">
                        <div class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center shrink-0 text-emerald-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs text-gray-500 font-medium truncate">Selesai</p>
                            <p class="text-xl sm:text-2xl font-extrabold text-gray-900 tracking-tight leading-tight">
                                {{ number_format($selesai, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    <!-- Kartu 6: Ditolak -->
                    <div class="bg-white rounded-2xl p-4 shadow-xl flex items-center gap-3.5 transition hover:shadow-2xl">
                        <div class="w-11 h-11 rounded-xl bg-rose-50 flex items-center justify-center shrink-0 text-rose-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs text-gray-500 font-medium truncate">Ditolak</p>
                            <p class="text-xl sm:text-2xl font-extrabold text-gray-900 tracking-tight leading-tight">
                                {{ number_format($ditolak, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                </div>

                <!-- ========================================================= -->
                <!-- 2. GRAFIK TREN LAPORAN & DISTRIBUSI STATUS -->
                <!-- ========================================================= -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">

                    <!-- Kartu Kiri: Tren Laporan (Bar Chart 7 Hari Terakhir) -->
                    <div class="col-span-12 lg:col-span-8 bg-white rounded-2xl p-6 shadow-xl flex flex-col justify-between">
                        <!-- Header Grafik -->
                        <div class="flex items-start justify-between gap-4 mb-6">
                            <div>
                                <h3 class="text-base font-bold text-gray-900">Tren Laporan</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Jumlah laporan dalam 7 hari terakhir.</p>
                            </div>
                            <!-- Filter Periode -->
                            <div class="relative">
                                <button
                                    type="button"
                                    class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-700 bg-gray-50 hover:bg-gray-100 border border-gray-200 px-3 py-1.5 rounded-lg transition"
                                >
                                    <span>7 hari terakhir</span>
                                    <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Area Batang Diagram (Bar Chart) -->
                        <div class="relative pt-6 pb-2">
                            <!-- Garis Panduan Horizontal -->
                            <div class="absolute inset-x-0 top-6 bottom-8 flex flex-col justify-between pointer-events-none">
                                <div class="border-b border-gray-100 w-full"></div>
                                <div class="border-b border-gray-100 w-full"></div>
                                <div class="border-b border-gray-100 w-full"></div>
                            </div>

                            <!-- 7 Kolom Batang -->
                            <div class="relative grid grid-cols-7 gap-2 sm:gap-4 h-48 sm:h-52 items-end px-2 sm:px-6">
                                @foreach ($trenHarian as $bar)
                                    <div class="flex flex-col items-center h-full justify-end group">
                                        <!-- Tooltip Hover Angka -->
                                        <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-150 mb-1.5 px-2 py-0.5 bg-gray-900 text-white text-[11px] font-bold rounded shadow-sm whitespace-nowrap pointer-events-none">
                                            {{ $bar['jumlah'] }} laporan
                                        </div>

                                        <!-- Batang Merah -->
                                        <div
                                            class="w-8 sm:w-12 bg-[#991B1B] group-hover:bg-[#B01116] rounded-t-md transition-all duration-300"
                                            style="height: {{ $bar['persen'] }}%;"
                                        ></div>

                                        <!-- Label Hari -->
                                        <span class="text-xs font-medium text-gray-500 mt-3">{{ $bar['hari'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Kartu Kanan: Distribusi Status Laporan Aktif -->
                    <div class="col-span-12 lg:col-span-4 bg-white rounded-2xl p-6 shadow-xl flex flex-col justify-between">
                        <!-- Header Distribusi -->
                        <div>
                            <h3 class="text-base font-bold text-gray-900">Distribusi Status</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Komposisi seluruh laporan aktif.</p>
                        </div>

                        <!-- Konten Tengah: Angka Total Aktif & Indikator Komposisi -->
                        <div class="my-auto py-8 flex items-center justify-around gap-4">
                            <!-- Angka Tengah -->
                            <div class="text-center">
                                <p class="text-4xl sm:text-5xl font-extrabold text-gray-900 tracking-tight">
                                    {{ number_format($laporanAktif, 0, ',', '.') }}
                                </p>
                                <p class="text-xs font-medium text-gray-500 mt-1">Laporan aktif</p>
                            </div>

                            <!-- Indikator Legend & Angka -->
                            <div class="space-y-3">
                                <!-- Menunggu -->
                                <div class="flex items-center gap-2.5 text-xs text-gray-700">
                                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500 shrink-0"></span>
                                    <span class="font-medium text-gray-600">Menunggu</span>
                                    <span class="font-extrabold text-gray-900 ml-auto">{{ number_format($menungguVerifikasi, 0, ',', '.') }}</span>
                                </div>
                                <!-- Diteruskan -->
                                <div class="flex items-center gap-2.5 text-xs text-gray-700">
                                    <span class="w-2.5 h-2.5 rounded-full bg-blue-600 shrink-0"></span>
                                    <span class="font-medium text-gray-600">Diteruskan</span>
                                    <span class="font-extrabold text-gray-900 ml-auto">{{ number_format($diteruskan, 0, ',', '.') }}</span>
                                </div>
                                <!-- Diproses -->
                                <div class="flex items-center gap-2.5 text-xs text-gray-700">
                                    <span class="w-2.5 h-2.5 rounded-full bg-purple-600 shrink-0"></span>
                                    <span class="font-medium text-gray-600">Diproses</span>
                                    <span class="font-extrabold text-gray-900 ml-auto">{{ number_format($diproses, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-100 flex items-center justify-between text-xs text-gray-400">
                            <span>Total Laporan Aktif</span>
                            <span class="font-bold text-gray-700">{{ number_format($laporanAktif, 0, ',', '.') }} tiket</span>
                        </div>
                    </div>

                </div>

                <!-- ========================================================= -->
                <!-- 3. TABEL LAPORAN MASUK TERBARU -->
                <!-- ========================================================= -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <!-- Header Tabel -->
                    <div class="p-6 border-b border-gray-100 flex items-center justify-between gap-4">
                        <div>
                            <h3 class="text-base font-bold text-gray-900">Laporan Masuk Terbaru</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Perlu diperiksa dan ditentukan unit penangan.</p>
                        </div>
                        <a
                            href="{{ route('admin.verifikasi') }}"
                            class="inline-flex items-center gap-1 text-sm font-bold text-[#B01116] hover:text-[#8C0D11] hover:underline transition"
                        >
                            <span>Lihat semua</span>
                            <span>&rarr;</span>
                        </a>
                    </div>

                    <!-- Daftar Tabel -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/80 border-b border-gray-100 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                                    <th scope="col" class="py-3.5 px-6">NOMOR TIKET</th>
                                    <th scope="col" class="py-3.5 px-6">JUDUL LAPORAN</th>
                                    <th scope="col" class="py-3.5 px-6">PELAPOR</th>
                                    <th scope="col" class="py-3.5 px-6">PROGRAM STUDI</th>
                                    <th scope="col" class="py-3.5 px-6">WAKTU MASUK</th>
                                    <th scope="col" class="py-3.5 px-6 text-right"><span class="sr-only">Aksi</span></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm">

                                <!-- Baris Data Riil Database (jika ada) -->
                                @foreach ($dbLaporans as $item)
                                    <tr
                                        class="hover:bg-gray-50/80 transition"
                                        x-show="matchesQuery({
                                            tiket: '{{ $item->nomor_tiket }}',
                                            judul: '{{ addslashes($item->judul) }}',
                                            pelapor: '{{ addslashes($item->user->name ?? '') }}',
                                            prodi: '{{ addslashes($item->user->prodi ?? '') }}',
                                            kategori: '{{ addslashes($item->kategori) }}'
                                        })"
                                    >
                                        <td class="py-4 px-6 whitespace-nowrap font-bold text-gray-900">
                                            {{ $item->nomor_tiket }}
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="font-bold text-gray-900 leading-snug">{{ $item->judul }}</div>
                                            <div class="text-xs text-gray-500 mt-0.5">
                                                {{ $item->kategori_label ?? ucfirst(str_replace('_', ' ', $item->kategori)) }}
                                            </div>
                                        </td>
                                        <td class="py-4 px-6 whitespace-nowrap">
                                            @if ($item->is_anonim)
                                                <div class="text-gray-900 leading-snug">Pelapor anonim</div>
                                                <div class="text-[11px] font-medium text-[#B01116] mt-0.5">SSC dapat melihat identitas</div>
                                            @else
                                                <div class="text-gray-900">{{ $item->user->name ?? 'Mahasiswa' }}</div>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6 whitespace-nowrap text-gray-600">
                                            {{ $item->user->prodi ?? 'S1 Sistem Informasi' }}
                                        </td>
                                        <td class="py-4 px-6 whitespace-nowrap text-gray-600">
                                            {{ $item->created_at ? $item->created_at->diffForHumans() : 'Hari ini' }}
                                        </td>
                                        <td class="py-4 px-6 whitespace-nowrap text-right">
                                            <a
                                                href="{{ route('admin.verifikasi') }}?tiket={{ $item->nomor_tiket }}"
                                                class="inline-flex items-center text-sm font-bold text-[#B01116] hover:text-[#8C0D11] hover:underline transition"
                                            >
                                                Verifikasi &rarr;
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach

                                <!-- Baris Data Mockup Persis Gambar Desain -->
                                @foreach ($mockLaporans as $mock)
                                    <tr
                                        class="hover:bg-gray-50/80 transition"
                                        x-show="matchesQuery({
                                            tiket: '{{ $mock['nomor_tiket'] }}',
                                            judul: '{{ addslashes($mock['judul']) }}',
                                            pelapor: '{{ addslashes($mock['pelapor']) }}',
                                            prodi: '{{ addslashes($mock['prodi']) }}',
                                            kategori: '{{ addslashes($mock['kategori']) }}'
                                        })"
                                    >
                                        <td class="py-4 px-6 whitespace-nowrap font-bold text-gray-900">
                                            {{ $mock['nomor_tiket'] }}
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="font-bold text-gray-900 leading-snug">{{ $mock['judul'] }}</div>
                                            <div class="text-xs text-gray-500 mt-0.5">{{ $mock['kategori'] }}</div>
                                        </td>
                                        <td class="py-4 px-6 whitespace-nowrap">
                                            @if ($mock['is_anonim'])
                                                <div class="text-gray-900 leading-snug">Pelapor anonim</div>
                                                <div class="text-[11px] font-medium text-[#B01116] mt-0.5">SSC dapat melihat identitas</div>
                                            @else
                                                <div class="text-gray-900">{{ $mock['pelapor'] }}</div>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6 whitespace-nowrap text-gray-600">
                                            {{ $mock['prodi'] }}
                                        </td>
                                        <td class="py-4 px-6 whitespace-nowrap text-gray-600">
                                            {{ $mock['waktu'] }}
                                        </td>
                                        <td class="py-4 px-6 whitespace-nowrap text-right">
                                            <a
                                                href="{{ route('admin.verifikasi') }}?tiket={{ $mock['nomor_tiket'] }}"
                                                class="inline-flex items-center text-sm font-bold text-[#B01116] hover:text-[#8C0D11] hover:underline transition"
                                            >
                                                Verifikasi &rarr;
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>

            </main>
        </div>

    </div>
</body>
</html>

