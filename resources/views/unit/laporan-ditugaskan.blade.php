<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#FFFFFF]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Laporan Ditugaskan - Petugas Unit CampusVoice</title>

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

        /* Linear Gradient Konten Utama: Merah #B01116 ke Abu-abu Gelap #58595B (sama seperti fitur lain) */
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
        filterStatus: '',
        filterKategori: '',
        filterPrioritas: '',
        currentPage: 1,
        perPage: 6,
        items: {{ \Illuminate\Support\Js::from($laporans) }},
        get filteredItems() {
            const q = this.searchQuery.trim().toLowerCase();
            return this.items.filter(item => {
                if (this.filterStatus && item.status !== this.filterStatus) return false;
                if (this.filterKategori && item.kategori !== this.filterKategori) return false;
                if (this.filterPrioritas && item.prioritas !== this.filterPrioritas) return false;
                if (q) {
                    const matchTiket = item.tiket && item.tiket.toLowerCase().includes(q);
                    const matchJudul = item.judul && item.judul.toLowerCase().includes(q);
                    const matchKategori = item.kategori && item.kategori.toLowerCase().includes(q);
                    if (!matchTiket && !matchJudul && !matchKategori) return false;
                }
                return true;
            });
        },
        get totalPages() {
            return Math.max(1, Math.ceil(this.filteredItems.length / this.perPage));
        },
        get paginatedItems() {
            if (this.currentPage > this.totalPages) {
                this.currentPage = 1;
            }
            const start = (this.currentPage - 1) * this.perPage;
            return this.filteredItems.slice(start, start + this.perPage);
        },
        get rangeStart() {
            if (this.filteredItems.length === 0) return 0;
            return (this.currentPage - 1) * this.perPage + 1;
        },
        get rangeEnd() {
            return Math.min(this.currentPage * this.perPage, this.filteredItems.length);
        },
        prevPage() {
            if (this.currentPage > 1) this.currentPage--;
        },
        nextPage() {
            if (this.currentPage < this.totalPages) this.currentPage++;
        },
        goToPage(page) {
            if (page >= 1 && page <= this.totalPages) this.currentPage = page;
        }
    }"
>
    <div class="h-screen overflow-hidden flex flex-col lg:flex-row">

        <!-- ========================================================= -->
        <!-- SIDEBAR KIRI (PETUGAS UNIT) -->
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

                <!-- Navigasi Menu Petugas Unit -->
                <div class="px-4 py-3 flex-1">
                    <p class="px-3 text-[11px] font-bold text-[#252525] tracking-wide mb-2">Petugas Unit</p>

                    <nav class="space-y-1">
                        <!-- Menu 1: Dashboard Unit -->
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
                            <span>Dashboard Unit</span>
                        </a>

                        <!-- Menu 2: Laporan Ditugaskan (AKTIF) -->
                        <a
                            href="{{ route('unit.laporan-ditugaskan') }}"
                            aria-current="page"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold bg-[#FCE8E8] text-[#B01116] transition shadow-xs"
                        >
                            <svg class="w-5 h-5 text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                                <line x1="16" y1="13" x2="8" y2="13"/>
                                <line x1="16" y1="17" x2="8" y2="17"/>
                            </svg>
                            <span>Laporan Ditugaskan</span>
                        </a>

                        <!-- Menu 3: Riwayat Penanganan -->
                        <a
                            href="{{ route('petugas.riwayat') }}"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#252525] hover:bg-gray-100 hover:text-[#B01116] transition group"
                        >
                            <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="20" x2="12" y2="10"/>
                                <line x1="18" y1="20" x2="18" y2="4"/>
                                <line x1="6" y1="20" x2="6" y2="16"/>
                            </svg>
                            <span>Riwayat Penanganan</span>
                        </a>

                        <!-- Menu 4: Notifikasi -->
                        <a
                            href="{{ route('unit.notifikasi') }}"
                            class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#252525] hover:bg-gray-100 hover:text-[#B01116] transition group"
                        >
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                                    <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                                </svg>
                                <span>Notifikasi</span>
                            </div>
                            @if (($unreadCount ?? 0) > 0)
                                <span class="w-5 h-5 rounded-full bg-[#B01116] text-[#FFFFFF] text-[11px] font-bold flex items-center justify-center">
                                    {{ $unreadCount }}
                                </span>
                            @endif
                        </a>

                        <!-- Menu 5: Profil Unit -->
                        <a
                            href="{{ route('unit.profil') }}"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#252525] hover:bg-gray-100 hover:text-[#B01116] transition group"
                        >
                            <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                            <span>Profil Unit</span>
                        </a>
                    </nav>
                </div>

                <!-- Bagian Bawah: Keluar -->
                <div class="px-4 py-3 border-t border-[#E5E7EB]">
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
        <!-- AREA UTAMA (Header + Konten Laporan Ditugaskan) -->
        <!-- ========================================================= -->
        <div class="flex-1 flex flex-col min-w-0 min-h-0 overflow-hidden">

            <!-- HEADER ATAS (NAVBAR) -->
            <header class="bg-[#FFFFFF] border-b border-[#E5E7EB] px-4 sm:px-8 py-3.5 flex items-center justify-between sticky top-0 z-30">
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
                    <div class="relative w-56 sm:w-80 md:w-96">
                        <input
                            type="text"
                            x-model="searchQuery"
                            @input="currentPage = 1"
                            placeholder="Cari nomor tiket atau laporan..."
                            class="w-full bg-[#F9FAFB] border border-[#E5E7EB] rounded-xl py-2 px-3.5 text-xs sm:text-sm text-[#252525] placeholder-[#9CA3AF] focus:bg-[#FFFFFF] focus:border-[#B01116] focus:outline-none focus:ring-1 focus:ring-[#B01116] transition"
                        />
                    </div>
                </div>

                <!-- Bagian Kanan: Lonceng Notifikasi & Profil Petugas Unit -->
                <div class="flex items-center gap-3 sm:gap-4">
                    <!-- Tombol Lonceng Notifikasi -->
                    <a
                        href="{{ route('unit.notifikasi') }}"
                        class="relative w-9 h-9 rounded-xl border border-[#E5E7EB] flex items-center justify-center text-[#4B5563] hover:text-[#B01116] hover:bg-gray-50 transition"
                        title="Notifikasi"
                    >
                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-[#B01116] text-[#FFFFFF] text-[10px] font-bold flex items-center justify-center">
                            {{ $unreadCount }}
                        </span>
                    </a>

                    <!-- Profil Petugas Unit: Budi Santoso -->
                    <a href="{{ route('unit.profil') }}" class="flex items-center gap-2.5 group">
                        <div class="w-9 h-9 rounded-xl bg-[#FCE8E8] text-[#B01116] font-bold text-xs flex items-center justify-center shrink-0">
                            {{ $authUser->initials ?? 'BS' }}
                        </div>
                        <div class="hidden sm:block text-left">
                            <p class="text-xs font-bold text-[#252525] leading-tight group-hover:text-[#B01116] transition">
                                {{ $authUser->name ?? 'Budi Santoso' }}
                            </p>
                            <p class="text-[11px] text-[#6B7280] leading-tight">
                                {{ $authUser->role ?? 'Petugas Unit' }}
                            </p>
                        </div>
                    </a>
                </div>
            </header>

            <!-- ========================================================= -->
            <!-- KONTEN UTAMA: LAPORAN DITUGASKAN -->
            <!-- ========================================================= -->
            <main class="flex-1 min-h-0 overflow-y-auto content-gradient-bg px-4 sm:px-8 lg:px-10 py-8">
                <div class="max-w-7xl mx-auto space-y-5">

                    <!-- Baris Filter & Pencarian -->
                    <div class="flex flex-wrap items-center gap-3">
                        <!-- Input Pencarian Lebar -->
                        <div class="flex-1 min-w-[230px]">
                            <input
                                type="text"
                                x-model="searchQuery"
                                @input="currentPage = 1"
                                placeholder="Cari nomor tiket atau judul..."
                                class="w-full bg-[#FFFFFF] border border-[#E5E7EB] rounded-xl py-2.5 px-4 text-xs sm:text-sm text-[#252525] placeholder-[#9CA3AF] focus:border-[#B01116] focus:outline-none focus:ring-1 focus:ring-[#B01116] transition"
                            />
                        </div>

                        <!-- Dropdown: Semua Status -->
                        <div class="relative min-w-[140px]">
                            <select
                                x-model="filterStatus"
                                @change="currentPage = 1"
                                class="w-full appearance-none bg-[#FFFFFF] border border-[#E5E7EB] rounded-xl py-2.5 pl-4 pr-9 text-xs sm:text-sm font-medium text-[#252525] focus:border-[#B01116] focus:outline-none focus:ring-1 focus:ring-[#B01116] transition cursor-pointer"
                            >
                                <option value="">Semua Status</option>
                                <option value="Diproses">Diproses</option>
                                <option value="Diteruskan">Diteruskan</option>
                                <option value="Selesai">Selesai</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-[#6B7280]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Dropdown: Semua Kategori -->
                        <div class="relative min-w-[150px]">
                            <select
                                x-model="filterKategori"
                                @change="currentPage = 1"
                                class="w-full appearance-none bg-[#FFFFFF] border border-[#E5E7EB] rounded-xl py-2.5 pl-4 pr-9 text-xs sm:text-sm font-medium text-[#252525] focus:border-[#B01116] focus:outline-none focus:ring-1 focus:ring-[#B01116] transition cursor-pointer"
                            >
                                <option value="">Semua Kategori</option>
                                <option value="Satpras">Satpras</option>
                                <option value="SSC">SSC</option>
                                <option value="Akademik">Akademik</option>
                                <option value="Kemahasiswaan">Kemahasiswaan</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-[#6B7280]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Dropdown: Semua Prioritas -->
                        <div class="relative min-w-[150px]">
                            <select
                                x-model="filterPrioritas"
                                @change="currentPage = 1"
                                class="w-full appearance-none bg-[#FFFFFF] border border-[#E5E7EB] rounded-xl py-2.5 pl-4 pr-9 text-xs sm:text-sm font-medium text-[#252525] focus:border-[#B01116] focus:outline-none focus:ring-1 focus:ring-[#B01116] transition cursor-pointer"
                            >
                                <option value="">Semua Prioritas</option>
                                <option value="Tinggi">Tinggi</option>
                                <option value="Sedang">Sedang</option>
                                <option value="Rendah">Rendah</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-[#6B7280]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Rentang Tanggal -->
                        <div class="bg-[#FFFFFF] border border-[#E5E7EB] rounded-xl py-2.5 px-4 text-xs sm:text-sm font-medium text-[#252525] whitespace-nowrap">
                            19 Sep — 19 Okt 2026
                        </div>
                    </div>

                    <!-- Kartu Tabel Laporan Ditugaskan -->
                    <div class="bg-[#FFFFFF] rounded-2xl border border-[#E5E7EB] shadow-xl overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-[#F9FAFB] border-b border-[#E5E7EB] text-[11px] font-bold text-[#6B7280] uppercase tracking-wider">
                                        <th scope="col" class="py-4 px-6">NOMOR TIKET</th>
                                        <th scope="col" class="py-4 px-6">JUDUL LAPORAN</th>
                                        <th scope="col" class="py-4 px-6">KATEGORI</th>
                                        <th scope="col" class="py-4 px-6">PRIORITAS</th>
                                        <th scope="col" class="py-4 px-6">STATUS</th>
                                        <th scope="col" class="py-4 px-6">PROGRES</th>
                                        <th scope="col" class="py-4 px-6 text-right"><span class="sr-only">Aksi</span></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#F3F4F6] text-sm">
                                    <template x-for="item in paginatedItems" :key="item.tiket">
                                        <tr class="hover:bg-gray-50/80 transition">
                                            <!-- Nomor Tiket & Tanggal -->
                                            <td class="py-5 px-6 whitespace-nowrap">
                                                <div class="font-bold text-[#252525] text-sm" x-text="item.tiket"></div>
                                                <div class="text-xs text-[#6B7280] mt-0.5" x-text="item.tanggal"></div>
                                            </td>

                                            <!-- Judul Laporan -->
                                            <td class="py-5 px-6">
                                                <div class="font-bold text-[#252525] text-sm leading-snug" x-text="item.judul"></div>
                                            </td>

                                            <!-- Kategori -->
                                            <td class="py-5 px-6 whitespace-nowrap text-xs sm:text-sm text-[#4B5563]" x-text="item.kategori"></td>

                                            <!-- Prioritas Badge -->
                                            <td class="py-5 px-6 whitespace-nowrap">
                                                <span
                                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold"
                                                    :class="{
                                                        'bg-[#FEE2E2] text-[#B01116]': item.prioritas === 'Tinggi',
                                                        'bg-[#FEF3C7] text-[#D97706]': item.prioritas === 'Sedang',
                                                        'bg-[#F3F4F6] text-[#4B5563]': item.prioritas === 'Rendah'
                                                    }"
                                                    x-text="item.prioritas"
                                                ></span>
                                            </td>

                                            <!-- Status Badge -->
                                            <td class="py-5 px-6 whitespace-nowrap">
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold"
                                                    :class="{
                                                        'bg-[#F3E8FF] text-[#7C3AED]': item.status === 'Diproses',
                                                        'bg-[#EFF6FF] text-[#2563EB]': item.status === 'Diteruskan',
                                                        'bg-[#ECFDF5] text-[#16A34A]': item.status === 'Selesai'
                                                    }"
                                                >
                                                    <span
                                                        class="w-1.5 h-1.5 rounded-full"
                                                        :class="{
                                                            'bg-[#7C3AED]': item.status === 'Diproses',
                                                            'bg-[#2563EB]': item.status === 'Diteruskan',
                                                            'bg-[#16A34A]': item.status === 'Selesai'
                                                        }"
                                                    ></span>
                                                    <span x-text="item.status"></span>
                                                </span>
                                            </td>

                                            <!-- Progres Bar -->
                                            <td class="py-5 px-6 whitespace-nowrap">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-20 h-1.5 bg-[#E5E7EB] rounded-full overflow-hidden">
                                                        <div
                                                            class="h-full bg-[#B01116] rounded-full transition-all duration-300"
                                                            :style="'width: ' + item.progres + '%'"
                                                        ></div>
                                                    </div>
                                                    <span class="text-xs font-semibold text-[#4B5563]" x-text="item.progres + '%'"></span>
                                                </div>
                                            </td>

                                            <!-- Aksi Tangani -->
                                            <td class="py-5 px-6 whitespace-nowrap text-right">
                                                <a
                                                    :href="item.url"
                                                    class="inline-flex items-center gap-1 text-xs sm:text-sm font-bold text-[#B01116] hover:text-[#8C0D11] hover:underline transition"
                                                >
                                                    <span>Tangani</span>
                                                    <span>&rarr;</span>
                                                </a>
                                            </td>
                                        </tr>
                                    </template>

                                    <!-- Empty State -->
                                    <tr x-show="filteredItems.length === 0" x-cloak>
                                        <td colspan="7" class="py-12 px-6 text-center text-sm text-[#6B7280]">
                                            Tidak ada laporan ditugaskan yang sesuai dengan filter pencarian.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Footer Pagination -->
                        <div class="px-6 py-4 bg-[#FFFFFF] border-t border-[#F3F4F6] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <p class="text-xs text-[#6B7280]">
                                Menampilkan <span class="font-semibold text-[#252525]" x-text="rangeStart + '–' + rangeEnd"></span> dari <span class="font-semibold text-[#252525]" x-text="filteredItems.length"></span> laporan
                            </p>

                            <div class="flex items-center gap-1.5">
                                <!-- Tombol Prev -->
                                <button
                                    type="button"
                                    @click="prevPage()"
                                    :disabled="currentPage === 1"
                                    class="w-8 h-8 rounded-lg border border-[#E5E7EB] bg-[#FFFFFF] text-[#4B5563] hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed flex items-center justify-center text-xs transition cursor-pointer"
                                    title="Sebelumnya"
                                >
                                    &larr;
                                </button>

                                <!-- Nomor Halaman -->
                                <template x-for="page in totalPages" :key="page">
                                    <button
                                        type="button"
                                        @click="goToPage(page)"
                                        class="w-8 h-8 rounded-lg text-xs font-bold flex items-center justify-center transition cursor-pointer"
                                        :class="currentPage === page
                                            ? 'bg-[#B01116] text-[#FFFFFF] shadow-xs'
                                            : 'border border-[#E5E7EB] bg-[#FFFFFF] text-[#4B5563] hover:bg-gray-50'"
                                        x-text="page"
                                    ></button>
                                </template>

                                <!-- Tombol Next -->
                                <button
                                    type="button"
                                    @click="nextPage()"
                                    :disabled="currentPage === totalPages"
                                    class="w-8 h-8 rounded-lg border border-[#E5E7EB] bg-[#FFFFFF] text-[#4B5563] hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed flex items-center justify-center text-xs transition cursor-pointer"
                                    title="Berikutnya"
                                >
                                    &rarr;
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

