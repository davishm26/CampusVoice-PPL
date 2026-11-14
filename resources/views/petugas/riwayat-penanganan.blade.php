<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#FFFFFF]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Riwayat Penanganan - Petugas Unit CampusVoice</title>

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

        /* Linear Gradient Konten Utama: Merah #B01116 ke Abu-abu Gelap #58595B */
        .content-gradient-bg {
            background: linear-gradient(to left, #B01116 0%, #58595B 100%);
        }
    </style>
</head>
<body
    class="h-full antialiased text-[#252525] bg-[#B01116]"
    x-data="riwayatPenangananApp()"
>
    <div class="min-h-screen flex flex-col lg:flex-row">

        <!-- ========================================================= -->
        <!-- SIDEBAR KIRI (PETUGAS UNIT) -->
        <!-- ========================================================= -->
        <aside
            class="fixed inset-y-0 left-0 z-50 w-64 bg-[#FFFFFF] flex flex-col justify-between border-r border-[#E5E7EB] transition-transform duration-200 lg:static lg:translate-x-0 shrink-0"
            :class="mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        >
            <div class="flex flex-col flex-1 overflow-y-auto">
                <!-- Logo -->
                <div class="px-6 py-5 flex items-center gap-3">
                    <img
                        src="{{ asset('images/logo-campusvoice.png') }}"
                        alt="CampusVoice Logo"
                        class="w-8 h-8 object-contain shrink-0"
                        onerror="this.onerror=null; this.src='https://placehold.co/32x32/B01116/white?text=CV';"
                    />
                    <div>
                        <span class="text-[17px] font-extrabold text-[#B01116] tracking-tight block leading-none">CampusVoice</span>
                        <p class="text-[10px] text-[#6B7280] font-medium leading-tight mt-0.5">Telkom University Surabaya</p>
                    </div>
                </div>

                <!-- Navigasi Menu Petugas Unit -->
                <div class="px-4 py-3 flex-1">
                    <p class="px-3 text-[11px] font-bold text-[#252525] tracking-wide mb-2.5">Petugas Unit</p>

                    <nav class="space-y-1">
                        <!-- Menu 1: Dashboard Unit -->
                        <a
                            href="#"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#252525] hover:bg-gray-100 hover:text-[#B01116] transition group"
                        >
                            <svg class="w-5 h-5 text-[#252525] group-hover:text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="7" height="7" rx="1.5"/>
                                <rect x="14" y="3" width="7" height="7" rx="1.5"/>
                                <rect x="14" y="14" width="7" height="7" rx="1.5"/>
                                <rect x="3" y="14" width="7" height="7" rx="1.5"/>
                            </svg>
                            <span>Dashboard Unit</span>
                        </a>

                        <!-- Menu 2: Laporan Ditugaskan -->
                        <a
                            href="#"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#252525] hover:bg-gray-100 hover:text-[#B01116] transition group"
                        >
                            <svg class="w-5 h-5 text-[#252525] group-hover:text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                                <line x1="16" y1="13" x2="8" y2="13"/>
                                <line x1="16" y1="17" x2="8" y2="17"/>
                            </svg>
                            <span>Laporan Ditugaskan</span>
                        </a>

                        <!-- Menu 3: Riwayat Penanganan (AKTIF) -->
                        <a
                            href="{{ route('petugas.riwayat') }}"
                            aria-current="page"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold bg-[#FCE8E8] text-[#B01116] transition"
                        >
                            <svg class="w-5 h-5 text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="20" x2="18" y2="10"/>
                                <line x1="12" y1="20" x2="12" y2="4"/>
                                <line x1="6" y1="20" x2="6" y2="14"/>
                            </svg>
                            <span>Riwayat Penanganan</span>
                        </a>

                        <!-- Menu 4: Notifikasi (Badge 3) -->
                        <a
                            href="{{ route('unit.notifikasi') }}"
                            class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#252525] hover:bg-gray-100 hover:text-[#B01116] transition group"
                        >
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-[#252525] group-hover:text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                                    <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                                </svg>
                                <span>Notifikasi</span>
                            </div>
                            <span class="w-5 h-5 rounded-full bg-[#B01116] text-[#FFFFFF] text-[11px] font-bold flex items-center justify-center">
                                3
                            </span>
                        </a>

                        <!-- Menu 5: Profil Unit -->
                        <a
                            href="{{ route('unit.profil') }}"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#252525] hover:bg-gray-100 hover:text-[#B01116] transition group"
                        >
                            <svg class="w-5 h-5 text-[#252525] group-hover:text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                            <span>Profil Unit</span>
                        </a>
                    </nav>
                </div>

                <!-- Bagian Bawah: Keluar -->
                <div class="px-4 py-3 border-t border-[#E5E7EB]">
                    @if (Route::has('logout'))
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button
                                type="submit"
                                class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#B01116] hover:bg-[#FCE8E8]/60 transition cursor-pointer text-left"
                            >
                                <svg class="w-5 h-5 text-[#B01116] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                    <polyline points="16 17 21 12 16 7"/>
                                    <line x1="21" y1="12" x2="9" y2="12"/>
                                </svg>
                                <span>Keluar</span>
                            </button>
                        </form>
                    @else
                        <a
                            href="#"
                            class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#B01116] hover:bg-[#FCE8E8]/60 transition"
                        >
                            <svg class="w-5 h-5 text-[#B01116] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                <polyline points="16 17 21 12 16 7"/>
                                <line x1="21" y1="12" x2="9" y2="12"/>
                            </svg>
                            <span>Keluar</span>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Kartu Bantuan -->
            <div class="p-4 pt-2">
                <div class="rounded-xl bg-[#B01116] p-4 text-[#FFFFFF] shadow-sm">
                    <h4 class="text-sm font-bold tracking-tight text-[#FFFFFF]">Butuh bantuan?</h4>
                    <p class="text-[11px] text-[#FFFFFF]/85 mt-1 leading-snug">Hubungi pusat layanan CampusVoice.</p>
                    <a
                        href="{{ Route::has('pusat.bantuan') ? route('pusat.bantuan') : '#' }}"
                        class="mt-3 inline-block py-1.5 px-3.5 bg-[#FFFFFF] hover:bg-gray-100 text-[#B01116] text-xs font-bold rounded-lg text-center shadow-xs transition"
                    >
                        Pusat Bantuan
                    </a>
                </div>
            </div>
        </aside>

        <!-- Overlay Mobile -->
        <div
            x-show="mobileSidebarOpen"
            x-cloak
            @click="mobileSidebarOpen = false"
            class="fixed inset-0 z-40 bg-black/40 backdrop-blur-xs lg:hidden"
        ></div>

        <!-- ========================================================= -->
        <!-- AREA UTAMA (Header + Konten) -->
        <!-- ========================================================= -->
        <div class="flex-1 flex flex-col min-w-0">

            <!-- HEADER ATAS (NAVBAR) -->
            <header class="bg-[#FFFFFF] border-b border-[#E5E7EB] px-4 sm:px-8 py-3.5 flex items-center justify-between sticky top-0 z-30">
                <!-- Hamburger Mobile & Search Bar Header -->
                <div class="flex items-center gap-3">
                    <button
                        type="button"
                        @click="mobileSidebarOpen = !mobileSidebarOpen"
                        class="p-2 -ml-2 rounded-lg text-[#6B7280] hover:text-[#252525] hover:bg-gray-100 lg:hidden transition"
                        title="Buka Menu"
                        aria-label="Buka Menu"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <!-- Search Bar Header -->
                    <div class="relative w-48 sm:w-80 md:w-96">
                        <input
                            type="text"
                            x-model="search"
                            placeholder="Cari nomor tiket atau laporan..."
                            aria-label="Cari nomor tiket atau laporan"
                            class="w-full bg-[#F9FAFB] border border-[#E5E7EB] rounded-lg py-2 px-3.5 text-xs sm:text-sm text-[#252525] placeholder-[#6B7280] focus:bg-[#FFFFFF] focus:border-[#B01116] focus:outline-none focus:ring-1 focus:ring-[#B01116] transition"
                        />
                    </div>
                </div>

                <!-- Bagian Kanan: Lonceng Notifikasi & Profil Petugas Unit -->
                <div class="flex items-center gap-3 sm:gap-4">
                    <!-- Tombol Lonceng Notifikasi -->
                    <a
                        href="{{ route('unit.notifikasi') }}"
                        class="relative p-2 rounded-lg border border-[#E5E7EB] bg-[#FFFFFF] text-[#252525] hover:text-[#B01116] hover:bg-gray-50 transition cursor-pointer"
                        title="Notifikasi"
                        aria-label="Notifikasi"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span class="absolute -top-1.5 -right-1.5 w-4 h-4 rounded-full bg-[#B01116] text-[#FFFFFF] text-[10px] font-bold flex items-center justify-center">
                            3
                        </span>
                    </a>

                    <!-- Profil Petugas Unit: Budi Santoso -->
                    <a href="{{ route('unit.profil') }}" class="flex items-center gap-2.5 group">
                        <div class="w-9 h-9 rounded-lg bg-[#FCE8E8] text-[#B01116] font-bold text-xs flex items-center justify-center shrink-0">
                            BS
                        </div>
                        <div class="hidden sm:block text-left">
                            <p class="text-xs font-bold text-[#252525] leading-tight group-hover:text-[#B01116] transition">Budi Santoso</p>
                            <p class="text-[11px] text-[#6B7280] leading-tight">Petugas Unit</p>
                        </div>
                    </a>
                </div>
            </header>

            <!-- ========================================================= -->
            <!-- AREA KONTEN UTAMA: Linear Gradient #B01116 ke #58595B -->
            <!-- ========================================================= -->
            <main class="flex-1 content-gradient-bg px-4 sm:px-8 lg:px-10 py-8 text-[#FFFFFF]">
                <div class="max-w-7xl mx-auto space-y-5">

                    <!-- Header Halaman (Atas) -->
                    <div>
                        <h1 class="text-2xl sm:text-[28px] font-bold text-[#FFFFFF] tracking-tight leading-tight">
                            Riwayat Penanganan
                        </h1>
                        <p class="text-xs sm:text-sm text-[#FFFFFF]/85 mt-1 font-normal">
                            Arsip laporan yang pernah ditangani oleh unit Anda.
                        </p>
                    </div>

                    <!-- Baris Filter & Pencarian (5 Elemen Sejajar) -->
                    <div class="bg-[#FFFFFF] rounded-2xl border border-[#E5E7EB] p-3 shadow-sm">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-2.5 items-center">
                            <!-- 1. Input Pencarian -->
                            <div class="relative lg:col-span-4">
                                <svg class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-[#6B7280]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="7"/>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                                </svg>
                                <input
                                    type="text"
                                    x-model="search"
                                    placeholder="Cari nomor tiket atau judul..."
                                    aria-label="Cari nomor tiket atau judul"
                                    class="w-full h-11 bg-[#FFFFFF] border border-[#E5E7EB] rounded-xl pl-10 pr-3.5 text-xs sm:text-sm text-[#252525] placeholder-[#6B7280] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116] focus:outline-none transition"
                                />
                            </div>

                            <!-- 2. Dropdown Status -->
                            <div class="relative lg:col-span-2">
                                <select
                                    x-model="status"
                                    aria-label="Filter Status"
                                    class="w-full h-11 appearance-none bg-[#FFFFFF] bg-none border border-[#E5E7EB] rounded-xl pl-3.5 pr-9 text-xs sm:text-sm font-medium text-[#252525] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116] focus:outline-none transition cursor-pointer"
                                >
                                    <option value="">Semua Status</option>
                                    <option value="Diproses">Diproses</option>
                                    <option value="Selesai">Selesai</option>
                                </select>
                                <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[#252525]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>

                            <!-- 3. Dropdown Kategori -->
                            <div class="relative lg:col-span-2">
                                <select
                                    x-model="kategori"
                                    aria-label="Filter Kategori"
                                    class="w-full h-11 appearance-none bg-[#FFFFFF] bg-none border border-[#E5E7EB] rounded-xl pl-3.5 pr-9 text-xs sm:text-sm font-medium text-[#252525] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116] focus:outline-none transition cursor-pointer"
                                >
                                    <option value="">Semua Kategori</option>
                                    <option value="Sarana & Prasarana">Sarana &amp; Prasarana</option>
                                    <option value="Layanan IT">Layanan IT</option>
                                    <option value="Akademik">Akademik</option>
                                </select>
                                <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[#252525]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>

                            <!-- 4. Dropdown Prioritas -->
                            <div class="relative lg:col-span-2">
                                <select
                                    x-model="prioritas"
                                    aria-label="Filter Prioritas"
                                    class="w-full h-11 appearance-none bg-[#FFFFFF] bg-none border border-[#E5E7EB] rounded-xl pl-3.5 pr-9 text-xs sm:text-sm font-medium text-[#252525] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116] focus:outline-none transition cursor-pointer"
                                >
                                    <option value="">Semua Prioritas</option>
                                    <option value="Tinggi">Tinggi</option>
                                    <option value="Sedang">Sedang</option>
                                    <option value="Rendah">Rendah</option>
                                </select>
                                <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[#252525]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>

                            <!-- 5. Range Tanggal -->
                            <div class="lg:col-span-2">
                                <input
                                    type="text"
                                    x-model="dateRange"
                                    aria-label="Range Tanggal"
                                    class="w-full h-11 bg-[#FFFFFF] border border-[#E5E7EB] rounded-xl px-3.5 text-xs sm:text-sm font-medium text-[#252525] placeholder-[#6B7280] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116] focus:outline-none transition"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Tabel Arsip Riwayat Penanganan (Kartu Putih Besar di Bawah) -->
                    <section class="bg-[#FFFFFF] rounded-2xl border border-[#E5E7EB] p-4 sm:p-6 shadow-xl">
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[820px] text-left border-collapse">
                                <thead>
                                    <tr class="bg-[#F9FAFB] border-b border-[#E5E7EB]">
                                        <th scope="col" class="py-3.5 px-4 text-[11px] font-bold uppercase tracking-wider text-[#252525] rounded-l-lg">
                                            NOMOR TIKET
                                        </th>
                                        <th scope="col" class="py-3.5 px-4 text-[11px] font-bold uppercase tracking-wider text-[#252525]">
                                            JUDUL
                                        </th>
                                        <th scope="col" class="py-3.5 px-4 text-[11px] font-bold uppercase tracking-wider text-[#252525]">
                                            DITERIMA
                                        </th>
                                        <th scope="col" class="py-3.5 px-4 text-[11px] font-bold uppercase tracking-wider text-[#252525]">
                                            SELESAI
                                        </th>
                                        <th scope="col" class="py-3.5 px-4 text-[11px] font-bold uppercase tracking-wider text-[#252525]">
                                            STATUS AKHIR
                                        </th>
                                        <th scope="col" class="py-3.5 px-4 text-[11px] font-bold uppercase tracking-wider text-[#252525]">
                                            DURASI
                                        </th>
                                        <th scope="col" class="py-3.5 px-4 rounded-r-lg text-right">
                                            <span class="sr-only">Aksi</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="row in filteredRows" :key="row.tiket">
                                        <tr class="border-b border-[#E5E7EB] last:border-b-0 hover:bg-[#F9FAFB]/70 transition">
                                            <!-- Nomor Tiket -->
                                            <td class="py-5 px-4 text-sm font-bold text-[#252525] whitespace-nowrap" x-text="row.tiket"></td>

                                            <!-- Judul -->
                                            <td class="py-5 px-4 text-sm font-semibold text-[#252525]" x-text="row.judul"></td>

                                            <!-- Diterima -->
                                            <td class="py-5 px-4 text-sm text-[#6B7280] whitespace-nowrap" x-text="row.diterima"></td>

                                            <!-- Selesai -->
                                            <td class="py-5 px-4 text-sm text-[#6B7280] whitespace-nowrap" x-text="row.selesai"></td>

                                            <!-- Status Akhir -->
                                            <td class="py-5 px-4 whitespace-nowrap">
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold"
                                                    :class="row.status === 'Diproses'
                                                        ? 'bg-[#F5F3FF] text-[#7C3AED]'
                                                        : 'bg-[#ECFDF5] text-[#16A34A]'"
                                                >
                                                    <span
                                                        class="w-1.5 h-1.5 rounded-full"
                                                        :class="row.status === 'Diproses' ? 'bg-[#7C3AED]' : 'bg-[#16A34A]'"
                                                    ></span>
                                                    <span x-text="row.status"></span>
                                                </span>
                                            </td>

                                            <!-- Durasi -->
                                            <td class="py-5 px-4 text-sm text-[#6B7280] whitespace-nowrap" x-text="row.durasi"></td>

                                            <!-- Aksi -->
                                            <td class="py-5 px-4 text-right whitespace-nowrap">
                                                <button
                                                    type="button"
                                                    @click="openDetail(row)"
                                                    class="inline-flex items-center gap-1 text-sm font-bold text-[#B01116] hover:text-[#8A0D12] transition cursor-pointer"
                                                >
                                                    <span>Detail &rarr;</span>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>

                                    <!-- Empty State jika hasil filter kosong -->
                                    <tr x-show="filteredRows.length === 0" x-cloak>
                                        <td colspan="7" class="py-12 px-4 text-center">
                                            <p class="text-sm font-semibold text-[#252525]">Tidak ada riwayat penanganan yang sesuai filter.</p>
                                            <p class="text-xs text-[#6B7280] mt-1">Coba ubah kata kunci pencarian atau atur ulang filter.</p>
                                            <button
                                                type="button"
                                                @click="resetFilters()"
                                                class="mt-3 inline-flex items-center px-3.5 py-2 rounded-lg border border-[#E5E7EB] text-xs font-bold text-[#B01116] hover:bg-[#FCE8E8]/50 transition cursor-pointer"
                                            >
                                                Reset Filter
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </section>

                </div>
            </main>
        </div>
    </div>

    <!-- Modal Pratinjau Detail Penanganan -->
    <div
        x-show="selectedRow"
        x-cloak
        @keydown.escape.window="selectedRow = null"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs"
    >
        <div
            @click.outside="selectedRow = null"
            class="w-full max-w-lg bg-[#FFFFFF] rounded-2xl border border-[#E5E7EB] shadow-2xl p-6 text-[#252525]"
        >
            <div class="flex items-start justify-between gap-4 pb-4 border-b border-[#E5E7EB]">
                <div>
                    <span class="text-xs font-bold text-[#B01116]" x-text="selectedRow?.tiket"></span>
                    <h3 class="text-lg font-bold text-[#252525] mt-0.5" x-text="selectedRow?.judul"></h3>
                </div>
                <button
                    type="button"
                    @click="selectedRow = null"
                    class="p-1.5 rounded-lg text-[#6B7280] hover:text-[#252525] hover:bg-gray-100 transition cursor-pointer"
                    aria-label="Tutup modal"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="py-4 grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-xs text-[#6B7280]">Kategori</p>
                    <p class="font-semibold text-[#252525] mt-0.5" x-text="selectedRow?.kategori"></p>
                </div>
                <div>
                    <p class="text-xs text-[#6B7280]">Prioritas</p>
                    <p class="font-semibold text-[#252525] mt-0.5" x-text="selectedRow?.prioritas"></p>
                </div>
                <div>
                    <p class="text-xs text-[#6B7280]">Tanggal Diterima</p>
                    <p class="font-semibold text-[#252525] mt-0.5" x-text="selectedRow?.diterima"></p>
                </div>
                <div>
                    <p class="text-xs text-[#6B7280]">Tanggal Selesai</p>
                    <p class="font-semibold text-[#252525] mt-0.5" x-text="selectedRow?.selesai"></p>
                </div>
                <div>
                    <p class="text-xs text-[#6B7280]">Status Akhir</p>
                    <span
                        class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold mt-1"
                        :class="selectedRow?.status === 'Diproses' ? 'bg-[#F5F3FF] text-[#7C3AED]' : 'bg-[#ECFDF5] text-[#16A34A]'"
                    >
                        <span class="w-1.5 h-1.5 rounded-full" :class="selectedRow?.status === 'Diproses' ? 'bg-[#7C3AED]' : 'bg-[#16A34A]'"></span>
                        <span x-text="selectedRow?.status"></span>
                    </span>
                </div>
                <div>
                    <p class="text-xs text-[#6B7280]">Durasi Penanganan</p>
                    <p class="font-semibold text-[#252525] mt-0.5" x-text="selectedRow?.durasi"></p>
                </div>
            </div>

            <div class="pt-4 border-t border-[#E5E7EB] flex justify-end">
                <button
                    type="button"
                    @click="selectedRow = null"
                    class="px-4 py-2 rounded-xl bg-[#B01116] hover:bg-[#8A0D12] text-[#FFFFFF] text-xs font-bold transition cursor-pointer"
                >
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <script>
        function riwayatPenangananApp() {
            return {
                mobileSidebarOpen: false,
                search: '',
                status: '',
                kategori: '',
                prioritas: '',
                dateRange: '19 Sep — 19 Okt 2026',
                selectedRow: null,

                rows: [
                    {
                        tiket: 'CV-2026-00124',
                        judul: 'Permohonan Perbaikan AC Ruang Kelas',
                        diterima: '18 Okt 2026',
                        selesai: '—',
                        status: 'Diproses',
                        durasi: '1 hari berjalan',
                        kategori: 'Sarana & Prasarana',
                        prioritas: 'Tinggi'
                    },
                    {
                        tiket: 'CV-2026-00119',
                        judul: 'Kendala Akses Portal Akademik',
                        diterima: '16 Okt 2026',
                        selesai: '18 Okt 2026',
                        status: 'Selesai',
                        durasi: '3 hari',
                        kategori: 'Layanan IT',
                        prioritas: 'Sedang'
                    },
                    {
                        tiket: 'CV-2026-00108',
                        judul: 'Permasalahan Transkrip Nilai',
                        diterima: '12 Okt 2026',
                        selesai: '18 Okt 2026',
                        status: 'Selesai',
                        durasi: '4 hari',
                        kategori: 'Akademik',
                        prioritas: 'Rendah'
                    }
                ],

                get filteredRows() {
                    const q = this.search.trim().toLowerCase();
                    return this.rows.filter((row) => {
                        const okSearch = !q
                            || row.tiket.toLowerCase().includes(q)
                            || row.judul.toLowerCase().includes(q);
                        const okStatus = !this.status || row.status === this.status;
                        const okKategori = !this.kategori || row.kategori === this.kategori;
                        const okPrioritas = !this.prioritas || row.prioritas === this.prioritas;
                        return okSearch && okStatus && okKategori && okPrioritas;
                    });
                },

                openDetail(row) {
                    this.selectedRow = row;
                },

                resetFilters() {
                    this.search = '';
                    this.status = '';
                    this.kategori = '';
                    this.prioritas = '';
                    this.dateRange = '19 Sep — 19 Okt 2026';
                }
            };
        }
    </script>
</body>
</html>

