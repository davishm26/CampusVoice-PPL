<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#FFFFFF]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Verifikasi Laporan - Admin SSC CampusVoice</title>

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

        /* Linear Gradient Konten Utama: Merah #B01116 ke Abu-abu Gelap #58595B (kiri bawah ke kanan atas) */
        .content-gradient-bg {
            background: linear-gradient(to top right, #B01116 0%, #58595B 100%);
        }
    </style>
</head>
<body
    class="h-full antialiased text-[#252525] bg-[#B01116]"
    x-data="{
        mobileSidebarOpen: false,
        searchHeader: '',
        searchTable: '',
        filterStatus: '',
        filterKategori: '',
        filterPrioritas: ''
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
                    <p class="px-3 text-[11px] font-bold text-[#252525] tracking-wider mb-2 uppercase">Admin SSC</p>

                    <nav class="space-y-1">
                        <!-- Menu 1: Dashboard -->
                        <a
                            href="#"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#252525] hover:bg-gray-100 hover:text-[#B01116] transition group"
                        >
                            <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="7" height="7"></rect>
                                <rect x="14" y="3" width="7" height="7"></rect>
                                <rect x="14" y="14" width="7" height="7"></rect>
                                <rect x="3" y="14" width="7" height="7"></rect>
                            </svg>
                            <span>Dashboard</span>
                        </a>

                        <!-- Menu 2: Verifikasi Laporan (AKTIF) -->
                        <a
                            href="#"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold bg-[#FCE8E8] text-[#B01116] transition shadow-xs"
                        >
                            <svg class="w-5 h-5 text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                <path d="m9 12 2 2 4-4"/>
                            </svg>
                            <span>Verifikasi Laporan</span>
                        </a>

                        <!-- Menu 3: Monitoring Laporan -->
                        <a
                            href="#"
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
                            href="#"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#252525] hover:bg-gray-100 hover:text-[#B01116] transition group"
                        >
                            <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="7 10 12 15 17 10"/>
                                <line x1="12" y1="15" x2="12" y2="3"/>
                            </svg>
                            <span>Rekapitulasi</span>
                        </a>

                        <!-- Menu 5: Notifikasi (Badge 3) -->
                        <a
                            href="#"
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
                                3
                            </span>
                        </a>
                    </nav>
                </div>

                <!-- Bagian Bawah: Profil & Keluar -->
                <div class="px-4 py-3 border-t border-[#E5E7EB] space-y-1">
                    <a
                        href="#"
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
        <!-- AREA UTAMA (Header + Konten) -->
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
                            x-model="searchHeader"
                            placeholder="Cari nomor tiket atau laporan..."
                            class="w-full bg-[#FFFFFF] border border-[#E5E7EB] rounded-lg py-2 px-3.5 text-xs sm:text-sm text-[#252525] placeholder-[#6B7280] focus:border-[#B01116] focus:outline-none focus:ring-1 focus:ring-[#B01116] transition"
                        />
                    </div>
                </div>

                <!-- Bagian Kanan: Lonceng Notifikasi & Profil Admin SSC -->
                <div class="flex items-center gap-3 sm:gap-4">
                    <!-- Tombol Lonceng Notifikasi -->
                    <button
                        type="button"
                        class="relative p-2 rounded-lg text-[#6B7280] hover:text-[#B01116] hover:bg-gray-100 transition cursor-pointer"
                        title="Notifikasi"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span class="absolute top-1 right-1 w-4 h-4 rounded-full bg-[#B01116] text-[#FFFFFF] text-[10px] font-bold flex items-center justify-center">
                            3
                        </span>
                    </button>

                    <!-- Pemisah Vertikal -->
                    <div class="h-6 w-px bg-[#E5E7EB]"></div>

                    <!-- Profil Admin SSC: Siti Rahmawati -->
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-lg bg-[#FCE8E8] text-[#B01116] font-bold text-xs flex items-center justify-center shrink-0 ring-1 ring-[#B01116]/20">
                            SR
                        </div>
                        <div class="hidden sm:block text-left">
                            <p class="text-xs font-bold text-[#252525] leading-tight">Siti Rahmawati</p>
                            <p class="text-[11px] text-[#6B7280] leading-tight">Admin SSC</p>
                        </div>
                    </div>
                </div>
            </header>

            <!-- ========================================================= -->
            <!-- AREA KONTEN UTAMA: Linear Gradient #B01116 ke #58595B -->
            <!-- ========================================================= -->
            <main class="flex-1 content-gradient-bg px-4 sm:px-8 lg:px-10 py-7 text-[#FFFFFF]">
                <div class="max-w-7xl mx-auto space-y-6">

                    <!-- Header Bagian Konten -->
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-widest text-[#FFFFFF]/85">
                            OPERASIONAL SSC
                        </p>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-[#FFFFFF] tracking-tight mt-0.5">
                            Verifikasi dan Penyaluran Laporan
                        </h1>
                        <p class="text-xs sm:text-sm text-[#FFFFFF]/85 mt-1 font-normal">
                            Periksa kelengkapan, tetapkan prioritas, dan teruskan laporan ke unit yang sesuai.
                        </p>
                    </div>

                    <!-- Baris Filter & Pencarian (Elemen Berlatar Putih Sejajar) -->
                    <div class="flex flex-wrap items-center gap-3">
                        <!-- Input 1: Pencarian Lebar -->
                        <div class="flex-1 min-w-[240px]">
                            <div class="relative">
                                <input
                                    type="text"
                                    x-model="searchTable"
                                    placeholder="Cari nomor tiket atau judul..."
                                    class="w-full bg-[#FFFFFF] rounded-xl border border-transparent py-2.5 px-4 text-xs sm:text-sm text-[#252525] placeholder-[#6B7280] shadow-sm focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116] focus:outline-none transition"
                                />
                            </div>
                        </div>

                        <!-- Dropdown 1: Semua Status -->
                        <div class="relative min-w-[140px] shrink-0">
                            <select
                                x-model="filterStatus"
                                class="w-full appearance-none bg-[#FFFFFF] rounded-xl border border-transparent py-2.5 pl-4 pr-9 text-xs sm:text-sm font-medium text-[#252525] shadow-sm focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116] focus:outline-none transition cursor-pointer"
                            >
                                <option value="">Semua Status</option>
                                <option value="menunggu">Menunggu Verifikasi</option>
                                <option value="diproses">Diproses</option>
                                <option value="selesai">Selesai</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-[#6B7280]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Dropdown 2: Semua Kategori -->
                        <div class="relative min-w-[150px] shrink-0">
                            <select
                                x-model="filterKategori"
                                class="w-full appearance-none bg-[#FFFFFF] rounded-xl border border-transparent py-2.5 pl-4 pr-9 text-xs sm:text-sm font-medium text-[#252525] shadow-sm focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116] focus:outline-none transition cursor-pointer"
                            >
                                <option value="">Semua Kategori</option>
                                <option value="sarana">Sarana Prasarana</option>
                                <option value="akademik">Akademik</option>
                                <option value="kemahasiswaan">Kemahasiswaan</option>
                                <option value="ssc">SSC</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-[#6B7280]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Dropdown 3: Semua Prioritas -->
                        <div class="relative min-w-[150px] shrink-0">
                            <select
                                x-model="filterPrioritas"
                                class="w-full appearance-none bg-[#FFFFFF] rounded-xl border border-transparent py-2.5 pl-4 pr-9 text-xs sm:text-sm font-medium text-[#252525] shadow-sm focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116] focus:outline-none transition cursor-pointer"
                            >
                                <option value="">Semua Prioritas</option>
                                <option value="rendah">Rendah</option>
                                <option value="sedang">Sedang</option>
                                <option value="tinggi">Tinggi</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-[#6B7280]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Input/Teks Tanggal -->
                        <div class="bg-[#FFFFFF] rounded-xl border border-transparent py-2.5 px-4 text-xs sm:text-sm font-medium text-[#252525] shadow-sm whitespace-nowrap shrink-0 flex items-center gap-2 cursor-pointer hover:bg-gray-50 transition">
                            <span>19 Sep — 19 Okt 2026</span>
                        </div>
                    </div>

                    <!-- Tabel Daftar Laporan Masuk (Kartu Putih Besar #FFFFFF) -->
                    <div class="bg-[#FFFFFF] rounded-2xl shadow-xl overflow-hidden border border-[#E5E7EB]">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-[#E5E7EB] bg-[#FFFFFF]">
                                        <th scope="col" class="py-4 px-6 text-[11px] font-bold text-[#6B7280] uppercase tracking-wider">
                                            NOMOR TIKET
                                        </th>
                                        <th scope="col" class="py-4 px-6 text-[11px] font-bold text-[#6B7280] uppercase tracking-wider">
                                            JUDUL LAPORAN
                                        </th>
                                        <th scope="col" class="py-4 px-6 text-[11px] font-bold text-[#6B7280] uppercase tracking-wider">
                                            PELAPOR
                                        </th>
                                        <th scope="col" class="py-4 px-6 text-[11px] font-bold text-[#6B7280] uppercase tracking-wider">
                                            PROGRAM STUDI
                                        </th>
                                        <th scope="col" class="py-4 px-6 text-[11px] font-bold text-[#6B7280] uppercase tracking-wider">
                                            WAKTU MASUK
                                        </th>
                                        <th scope="col" class="py-4 px-6 text-[11px] font-bold text-[#6B7280] uppercase tracking-wider text-right">
                                            <span class="sr-only">Aksi</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#E5E7EB] bg-[#FFFFFF]">
                                    <!-- Baris 1: Nadia Putri -->
                                    <tr class="hover:bg-gray-50/80 transition">
                                        <td class="py-5 px-6 whitespace-nowrap text-sm font-bold text-[#252525]">
                                            CV-2026-00132
                                        </td>
                                        <td class="py-5 px-6">
                                            <div class="font-bold text-sm text-[#252525] leading-snug">Jadwal kuliah belum sinkron</div>
                                            <div class="text-xs text-[#6B7280] mt-0.5 font-normal">SSC</div>
                                        </td>
                                        <td class="py-5 px-6 whitespace-nowrap text-sm text-[#252525]">
                                            Nadia Putri
                                        </td>
                                        <td class="py-5 px-6 whitespace-nowrap text-sm text-[#252525]">
                                            S1 Sistem Informasi
                                        </td>
                                        <td class="py-5 px-6 whitespace-nowrap text-sm text-[#252525]">
                                            Hari ini, 09.42
                                        </td>
                                        <td class="py-5 px-6 whitespace-nowrap text-right">
                                            <a href="#" class="inline-flex items-center text-sm font-bold text-[#B01116] hover:text-[#970e13] transition cursor-pointer">
                                                Verifikasi &rarr;
                                            </a>
                                        </td>
                                    </tr>

                                    <!-- Baris 2: Pelapor Anonim -->
                                    <tr class="hover:bg-gray-50/80 transition">
                                        <td class="py-5 px-6 whitespace-nowrap text-sm font-bold text-[#252525]">
                                            CV-2026-00131
                                        </td>
                                        <td class="py-5 px-6">
                                            <div class="font-bold text-sm text-[#252525] leading-snug">Lampu koridor Gedung A mati</div>
                                            <div class="text-xs text-[#6B7280] mt-0.5 font-normal">Satpras</div>
                                        </td>
                                        <td class="py-5 px-6 whitespace-nowrap">
                                            <div class="text-sm text-[#252525] leading-snug">Pelapor anonim</div>
                                            <div class="text-[11px] font-medium text-[#B01116] mt-0.5">SSC dapat melihat identitas</div>
                                        </td>
                                        <td class="py-5 px-6 whitespace-nowrap text-sm text-[#252525]">
                                            S1 Teknik Industri
                                        </td>
                                        <td class="py-5 px-6 whitespace-nowrap text-sm text-[#252525]">
                                            Hari ini, 08.15
                                        </td>
                                        <td class="py-5 px-6 whitespace-nowrap text-right">
                                            <a href="#" class="inline-flex items-center text-sm font-bold text-[#B01116] hover:text-[#970e13] transition cursor-pointer">
                                                Verifikasi &rarr;
                                            </a>
                                        </td>
                                    </tr>

                                    <!-- Baris 3: Raka Aditya -->
                                    <tr class="hover:bg-gray-50/80 transition">
                                        <td class="py-5 px-6 whitespace-nowrap text-sm font-bold text-[#252525]">
                                            CV-2026-00130
                                        </td>
                                        <td class="py-5 px-6">
                                            <div class="font-bold text-sm text-[#252525] leading-snug">Akses Wi-Fi laboratorium terputus</div>
                                            <div class="text-xs text-[#6B7280] mt-0.5 font-normal">SSC</div>
                                        </td>
                                        <td class="py-5 px-6 whitespace-nowrap text-sm text-[#252525]">
                                            Raka Aditya
                                        </td>
                                        <td class="py-5 px-6 whitespace-nowrap text-sm text-[#252525]">
                                            S1 Teknik Informatika
                                        </td>
                                        <td class="py-5 px-6 whitespace-nowrap text-sm text-[#252525]">
                                            Kemarin, 16.30
                                        </td>
                                        <td class="py-5 px-6 whitespace-nowrap text-right">
                                            <a href="#" class="inline-flex items-center text-sm font-bold text-[#B01116] hover:text-[#970e13] transition cursor-pointer">
                                                Verifikasi &rarr;
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Footer Tabel (Pagination) -->
                        <div class="py-4 px-6 border-t border-[#E5E7EB] flex flex-col sm:flex-row items-center justify-between gap-4 bg-[#FFFFFF]">
                            <p class="text-xs text-[#6B7280]">
                                Menampilkan 1-6 dari 8 laporan
                            </p>
                            <div class="flex items-center gap-1.5">
                                <!-- Tombol Panah Kiri -->
                                <button
                                    type="button"
                                    class="w-8 h-8 rounded-lg border border-[#E5E7EB] bg-[#FFFFFF] flex items-center justify-center text-[#6B7280] hover:bg-gray-50 transition cursor-pointer shadow-2xs"
                                    title="Halaman Sebelumnya"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                </button>

                                <!-- Angka 1 (AKTIF: Merah #B01116) -->
                                <button
                                    type="button"
                                    class="w-8 h-8 rounded-lg bg-[#B01116] text-[#FFFFFF] font-bold text-xs flex items-center justify-center shadow-xs"
                                >
                                    1
                                </button>

                                <!-- Angka 2 (Putih) -->
                                <button
                                    type="button"
                                    class="w-8 h-8 rounded-lg border border-[#E5E7EB] bg-[#FFFFFF] text-[#252525] font-semibold text-xs flex items-center justify-center hover:bg-gray-50 transition cursor-pointer shadow-2xs"
                                >
                                    2
                                </button>

                                <!-- Tombol Panah Kanan -->
                                <button
                                    type="button"
                                    class="w-8 h-8 rounded-lg border border-[#E5E7EB] bg-[#FFFFFF] flex items-center justify-center text-[#6B7280] hover:bg-gray-50 transition cursor-pointer shadow-2xs"
                                    title="Halaman Selanjutnya"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </main>

        </div>
    </div>
</body>
</html>

