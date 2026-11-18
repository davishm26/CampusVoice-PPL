@php
    $verifikasiRows = collect($laporans ?? [])->map(function ($item) {
        return [
            'tiket' => $item->nomor_tiket,
            'judul' => $item->judul,
            'kategori_key' => $item->kategori ?? '',
            'kategori_display' => $item->short_kategori ?? $item->kategori_label ?? $item->kategori,
            'kategori' => trim(($item->kategori ?? '') . ' ' . ($item->kategori_label ?? '') . ' ' . ($item->short_kategori ?? '')),
            'is_anonim' => (bool) $item->is_anonim,
            'pelapor' => $item->is_anonim ? 'Pelapor Anonim' : ($item->user->name ?? 'Mahasiswa'),
            'prodi' => $item->is_anonim ? '-' : ($item->user->prodi ?? '-'),
            'tanggal' => $item->created_at ? $item->created_at->wib()->format('Y-m-d') : '',
            'waktu' => $item->created_at ? $item->created_at->wib()->diffForHumans() : '-',
            'url' => route('admin.verifikasi.detail', $item->nomor_tiket),
        ];
    })->values()->all();
@endphp
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
        filterKategori: '',
        filterTanggalDari: '',
        filterTanggalSampai: '',
        datePopoverOpen: false,
        currentPage: 1,
        perPage: 10,
        items: @js($verifikasiRows),
        get hasActiveFilters() {
            return Boolean(
                this.searchHeader.trim() ||
                this.searchTable.trim() ||
                this.filterKategori ||
                this.filterTanggalDari ||
                this.filterTanggalSampai
            );
        },
        resetFilters() {
            this.searchHeader = '';
            this.searchTable = '';
            this.filterKategori = '';
            this.filterTanggalDari = '';
            this.filterTanggalSampai = '';
            this.datePopoverOpen = false;
            this.currentPage = 1;
        },
        get dateLabel() {
            if (!this.filterTanggalDari && !this.filterTanggalSampai) return 'Semua Tanggal';
            const fmt = (d) => {
                if (!d) return '...';
                const dt = new Date(d + 'T00:00:00');
                return dt.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
            };
            return fmt(this.filterTanggalDari) + ' — ' + fmt(this.filterTanggalSampai);
        },
        matchesFilter(item) {
            const q = (this.searchTable || this.searchHeader).trim().toLowerCase();
            if (q) {
                const matchText = item.tiket.toLowerCase().includes(q)
                    || item.judul.toLowerCase().includes(q)
                    || (item.prodi && item.prodi.toLowerCase().includes(q))
                    || (!item.is_anonim && item.pelapor && item.pelapor.toLowerCase().includes(q));
                if (!matchText) return false;
            }
            if (this.filterKategori) {
                const keyMatch = (item.kategori_key || '').toLowerCase() === this.filterKategori.toLowerCase();
                const textMatch = item.kategori.toLowerCase().includes(this.filterKategori.toLowerCase());
                if (!keyMatch && !textMatch) return false;
            }
            if (this.filterTanggalDari && (!item.tanggal || item.tanggal < this.filterTanggalDari)) return false;
            if (this.filterTanggalSampai && (!item.tanggal || item.tanggal > this.filterTanggalSampai)) return false;
            return true;
        },
        get filteredItems() {
            return this.items.filter(item => this.matchesFilter(item));
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
        <!-- SIDEBAR KIRI (ADMIN SSC) -->
        <!-- ========================================================= -->
        <aside
            class="fixed inset-y-0 left-0 z-50 w-64 bg-[#FFFFFF] flex flex-col justify-between border-r border-[#E5E7EB] transition-transform duration-200 lg:static lg:translate-x-0 shrink-0"
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
                            href="{{ route('admin.dashboard') }}"
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
                            href="{{ route('admin.verifikasi') }}"
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

                        <!-- Menu 5: Notifikasi (Badge 3) -->
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
                            @if (($unreadCount ?? 0) > 0)
                                <span class="w-5 h-5 rounded-full bg-[#B01116] text-[#FFFFFF] text-[11px] font-bold flex items-center justify-center">
                                    {{ $unreadCount ?? 0 }}
                                </span>
                            @endif
                        </a>

                        <!-- Menu 6: Profil Saya -->
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
                    </nav>
                </div>

                <!-- Bagian Bawah: Keluar -->
                <div class="px-4 py-3 border-t border-[#E5E7EB] space-y-1">
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
        <div class="flex-1 flex flex-col min-w-0 min-h-0 overflow-hidden">

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
                </div>

                <!-- Bagian Kanan: Lonceng Notifikasi & Profil Admin SSC -->
                <div class="flex items-center gap-3 sm:gap-4 ml-auto">
                    <!-- Tombol Lonceng Notifikasi -->
                    <a
                        href="{{ route('admin.notifikasi') }}"
                        class="relative p-2 rounded-lg text-[#6B7280] hover:text-[#B01116] hover:bg-gray-100 transition cursor-pointer"
                        title="Notifikasi"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        @if (($unreadCount ?? 0) > 0)
                            <span class="absolute top-1 right-1 w-4 h-4 rounded-full bg-[#B01116] text-[#FFFFFF] text-[10px] font-bold flex items-center justify-center">
                                {{ $unreadCount ?? 0 }}
                            </span>
                        @endif
                    </a>

                    <!-- Pemisah Vertikal -->
                    <div class="h-6 w-px bg-[#E5E7EB]"></div>

                    <!-- Profil Admin SSC: Siti Rahmawati -->
                    <a href="{{ route('admin.profil') }}" class="flex items-center gap-2.5 group">
                        <div class="w-9 h-9 rounded-lg bg-[#FCE8E8] text-[#B01116] font-bold text-xs flex items-center justify-center shrink-0 ring-1 ring-[#B01116]/20">
                            SR
                        </div>
                        <div class="hidden sm:block text-left">
                            <p class="text-xs font-bold text-[#252525] leading-tight group-hover:text-[#B01116] transition">Siti Rahmawati</p>
                            <p class="text-[11px] text-[#6B7280] leading-tight">Admin SSC</p>
                        </div>
                    </a>
                </div>
            </header>

            <!-- ========================================================= -->
            <!-- AREA KONTEN UTAMA: Linear Gradient #B01116 ke #58595B -->
            <!-- ========================================================= -->
            <main class="flex-1 min-h-0 overflow-y-auto content-gradient-bg px-4 sm:px-8 lg:px-10 py-7 text-[#FFFFFF]">
                <div class="max-w-7xl mx-auto space-y-6">

                    <!-- Header Bagian Konten -->
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-[#FFFFFF] tracking-tight">
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
                                    @input="currentPage = 1"
                                    placeholder="Cari nomor tiket, judul, pelapor, atau prodi..."
                                    class="w-full bg-[#FFFFFF] rounded-xl border border-transparent py-2.5 px-4 text-xs sm:text-sm text-[#252525] placeholder-[#6B7280] shadow-sm focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116] focus:outline-none transition"
                                />
                            </div>
                        </div>

                        <!-- Dropdown: Semua Kategori -->
                        <div class="relative min-w-[190px] shrink-0">
                            <select
                                x-model="filterKategori"
                                @change="currentPage = 1"
                                class="w-full appearance-none bg-[#FFFFFF] rounded-xl border border-transparent py-2.5 pl-4 pr-9 text-xs sm:text-sm font-medium text-[#252525] shadow-sm focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116] focus:outline-none transition cursor-pointer"
                            >
                                <option value="">Semua Kategori</option>
                                <option value="sarana_prasarana">Sarana &amp; Prasarana</option>
                                <option value="kemahasiswaan">Kemahasiswaan</option>
                                <option value="ssc">Student Service Center</option>
                                <option value="akademik">Akademik</option>
                                <option value="administrasi_keuangan">Administrasi &amp; Keuangan</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-[#6B7280]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Filter Rentang Tanggal -->
                        <div class="relative shrink-0" @click.outside="datePopoverOpen = false">
                            <button
                                type="button"
                                @click="datePopoverOpen = !datePopoverOpen"
                                class="bg-[#FFFFFF] rounded-xl border border-transparent py-2.5 px-4 text-xs sm:text-sm font-medium text-[#252525] shadow-sm whitespace-nowrap flex items-center gap-2 cursor-pointer hover:bg-gray-50 transition"
                            >
                                <svg class="w-4 h-4 text-[#6B7280]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                                <span x-text="dateLabel"></span>
                            </button>

                            <div
                                x-show="datePopoverOpen"
                                x-cloak
                                x-transition
                                class="absolute right-0 mt-2 w-72 bg-[#FFFFFF] rounded-2xl shadow-xl border border-[#E5E7EB] p-4 z-30 text-[#252525] space-y-3"
                            >
                                <div>
                                    <label class="block text-[11px] font-bold text-[#6B7280] uppercase tracking-wider mb-1">Dari Tanggal</label>
                                    <input
                                        type="date"
                                        x-model="filterTanggalDari"
                                        class="w-full rounded-xl border border-[#E5E7EB] py-2 px-3 text-xs text-[#252525] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116]"
                                    />
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-[#6B7280] uppercase tracking-wider mb-1">Sampai Tanggal</label>
                                    <input
                                        type="date"
                                        x-model="filterTanggalSampai"
                                        class="w-full rounded-xl border border-[#E5E7EB] py-2 px-3 text-xs text-[#252525] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116]"
                                    />
                                </div>
                                <div class="flex items-center justify-between pt-1">
                                    <button
                                        type="button"
                                        @click="filterTanggalDari = ''; filterTanggalSampai = ''; datePopoverOpen = false;"
                                        class="text-xs font-semibold text-[#6B7280] hover:text-[#B01116] transition cursor-pointer"
                                    >
                                        Reset
                                    </button>
                                    <button
                                        type="button"
                                        @click="datePopoverOpen = false"
                                        class="px-3 py-1.5 rounded-lg bg-[#B01116] text-[#FFFFFF] text-xs font-bold hover:bg-[#970e13] transition cursor-pointer"
                                    >
                                        Terapkan
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Hapus Semua Filter -->
                        <button
                            type="button"
                            x-show="hasActiveFilters"
                            x-cloak
                            x-transition
                            @click="resetFilters()"
                            class="bg-[#FFFFFF] hover:bg-[#FCE8E8] text-[#B01116] rounded-xl border border-transparent py-2.5 px-4 text-xs sm:text-sm font-bold shadow-sm whitespace-nowrap shrink-0 flex items-center gap-1.5 cursor-pointer transition"
                            title="Hapus semua filter"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <span>Hapus Filter</span>
                        </button>
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
                                    <template x-for="item in paginatedItems" :key="item.tiket">
                                        <tr class="hover:bg-gray-50/80 transition">
                                            <td class="py-5 px-6 whitespace-nowrap text-sm font-bold text-[#252525]" x-text="item.tiket"></td>
                                            <td class="py-5 px-6">
                                                <div class="font-bold text-sm text-[#252525] leading-snug" x-text="item.judul"></div>
                                                <div class="text-xs text-[#6B7280] mt-0.5 font-normal" x-text="item.kategori_display"></div>
                                            </td>
                                            <td class="py-5 px-6 whitespace-nowrap">
                                                <template x-if="item.is_anonim">
                                                    <div>
                                                        <div class="text-sm text-[#252525] leading-snug">Pelapor anonim</div>
                                                        <div class="text-[11px] font-medium text-[#6B7280] mt-0.5">Identitas disembunyikan</div>
                                                    </div>
                                                </template>
                                                <template x-if="!item.is_anonim">
                                                    <div class="text-sm text-[#252525]" x-text="item.pelapor"></div>
                                                </template>
                                            </td>
                                            <td class="py-5 px-6 whitespace-nowrap text-sm text-[#252525]" x-text="item.prodi"></td>
                                            <td class="py-5 px-6 whitespace-nowrap text-sm text-[#252525]" x-text="item.waktu"></td>
                                            <td class="py-5 px-6 whitespace-nowrap text-right">
                                                <a :href="item.url" class="inline-flex items-center text-sm font-bold text-[#B01116] hover:text-[#970e13] transition cursor-pointer">
                                                    Verifikasi &rarr;
                                                </a>
                                            </td>
                                        </tr>
                                    </template>
                                    <tr x-show="filteredItems.length === 0" x-cloak>
                                        <td colspan="6" class="py-10 px-6 text-center text-sm text-[#6B7280]">
                                            Tidak ada laporan yang menunggu verifikasi saat ini.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Footer Tabel (Pagination) -->
                        <div class="py-4 px-6 border-t border-[#E5E7EB] flex flex-col sm:flex-row items-center justify-between gap-4 bg-[#FFFFFF]">
                            <p class="text-xs text-[#6B7280]">
                                Menampilkan <span class="font-semibold text-[#252525]" x-text="filteredItems.length ? (rangeStart + '–' + rangeEnd) : 0"></span> dari <span class="font-semibold text-[#252525]" x-text="filteredItems.length"></span> laporan
                            </p>
                            <div class="flex items-center gap-1.5">
                                <!-- Tombol Panah Kiri -->
                                <button
                                    type="button"
                                    @click="prevPage()"
                                    :disabled="currentPage === 1"
                                    class="w-8 h-8 rounded-lg border border-[#E5E7EB] bg-[#FFFFFF] flex items-center justify-center text-[#6B7280] hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition cursor-pointer shadow-2xs"
                                    title="Halaman Sebelumnya"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                </button>

                                <!-- Nomor Halaman Dinamis -->
                                <template x-for="page in totalPages" :key="page">
                                    <button
                                        type="button"
                                        @click="goToPage(page)"
                                        class="w-8 h-8 rounded-lg text-xs flex items-center justify-center transition cursor-pointer"
                                        :class="currentPage === page
                                            ? 'bg-[#B01116] text-[#FFFFFF] font-bold shadow-xs'
                                            : 'border border-[#E5E7EB] bg-[#FFFFFF] text-[#252525] font-semibold hover:bg-gray-50 shadow-2xs'"
                                        x-text="page"
                                    ></button>
                                </template>

                                <!-- Tombol Panah Kanan -->
                                <button
                                    type="button"
                                    @click="nextPage()"
                                    :disabled="currentPage === totalPages"
                                    class="w-8 h-8 rounded-lg border border-[#E5E7EB] bg-[#FFFFFF] flex items-center justify-center text-[#6B7280] hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition cursor-pointer shadow-2xs"
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

