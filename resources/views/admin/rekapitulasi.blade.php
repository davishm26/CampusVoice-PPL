<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#FFFFFF]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Rekapitulasi Pengaduan - Admin SSC CampusVoice</title>

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
            background: linear-gradient(to top right, #B01116 0%, #58595B 100%);
        }
    </style>
</head>
<body
    class="h-full antialiased text-[#252525] bg-[#B01116]"
    x-data="rekapitulasiApp()"
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
                    <img
                        src="{{ asset('images/logo-campusvoice.png') }}"
                        alt="CampusVoice Logo"
                        class="w-8 h-8 object-contain shrink-0"
                        onerror="this.onerror=null; this.src='https://placehold.co/32x32/B01116/white?text=CV';"
                    />
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

                        <!-- Menu 4: Rekapitulasi (AKTIF) -->
                        <a
                            href="{{ route('admin.rekapitulasi') }}"
                            aria-current="page"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold bg-[#FCE8E8] text-[#B01116] transition shadow-xs"
                        >
                            <svg class="w-5 h-5 text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
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
        <div
            x-show="mobileSidebarOpen"
            x-cloak
            @click="mobileSidebarOpen = false"
            class="fixed inset-0 z-40 bg-black/40 backdrop-blur-xs lg:hidden"
        ></div>

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
                        class="relative p-2 rounded-lg border border-[#E5E7EB] text-[#252525] hover:text-[#B01116] hover:bg-gray-50 transition cursor-pointer"
                        title="Notifikasi"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        @if (($unreadCount ?? 0) > 0)
                            <span class="absolute -top-1.5 -right-1.5 w-4 h-4 rounded-full bg-[#B01116] text-[#FFFFFF] text-[10px] font-bold flex items-center justify-center">
                                {{ $unreadCount ?? 0 }}
                            </span>
                        @endif
                    </a>

                    <!-- Pemisah Vertikal -->
                    <div class="h-6 w-px bg-[#E5E7EB]"></div>

                    <!-- Profil Admin SSC: Siti Rahmawati -->
                    <a href="{{ route('admin.profil') }}" class="flex items-center gap-2.5 group">
                        <div class="w-9 h-9 rounded-lg bg-[#FCE8E8] text-[#B01116] font-bold text-xs flex items-center justify-center shrink-0 ring-1 ring-[#B01116]/15">
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
                <div class="max-w-7xl mx-auto space-y-5">

                    <!-- Header Halaman (Atas) -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h1 class="text-2xl sm:text-[28px] font-extrabold text-[#FFFFFF] tracking-tight leading-tight">
                                Rekapitulasi Pengaduan
                            </h1>
                            <p class="text-xs sm:text-sm text-[#FFFFFF]/85 mt-1 font-normal">
                                Susun data evaluasi berdasarkan rentang tanggal, kategori, dan unit penangan.
                            </p>
                        </div>

                        <div class="shrink-0">
                            <button
                                type="button"
                                @click="downloadExcel()"
                                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#991015]/75 hover:bg-[#8A0D12] border border-[#FFFFFF]/25 text-[#FFFFFF] text-xs sm:text-sm font-semibold shadow-sm backdrop-blur-xs transition cursor-pointer"
                            >
                                <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                    <polyline points="7 10 12 15 17 10"/>
                                    <line x1="12" y1="15" x2="12" y2="3"/>
                                </svg>
                                <span>Unduh Excel</span>
                            </button>
                        </div>
                    </div>

                    <!-- Baris Filter Utama (Kartu Putih Memanjang) -->
                    <div class="bg-[#FFFFFF] rounded-2xl border border-[#E5E7EB] p-3 sm:p-3.5 shadow-sm">
                        <div class="flex flex-wrap items-center gap-2.5">
                            <!-- Rentang Tanggal -->
                            <div class="relative w-full sm:w-auto sm:min-w-[215px]" @click.outside="datePopoverOpen = false">
                                <button
                                    type="button"
                                    @click="datePopoverOpen = !datePopoverOpen"
                                    aria-label="Filter Rentang Tanggal"
                                    class="w-full bg-[#FFFFFF] border border-[#E5E7EB] rounded-xl py-2.5 px-3.5 text-xs sm:text-sm font-medium text-[#252525] flex items-center justify-between gap-2 hover:bg-gray-50 focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116] focus:outline-none transition cursor-pointer"
                                >
                                    <span class="flex items-center gap-2 truncate">
                                        <svg class="w-4 h-4 text-[#6B7280] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                            <line x1="16" y1="2" x2="16" y2="6"></line>
                                            <line x1="8" y1="2" x2="8" y2="6"></line>
                                            <line x1="3" y1="10" x2="21" y2="10"></line>
                                        </svg>
                                        <span x-text="dateLabel" class="truncate"></span>
                                    </span>
                                    <svg class="w-4 h-4 text-[#6B7280] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>

                                <div
                                    x-show="datePopoverOpen"
                                    x-cloak
                                    x-transition
                                    class="absolute left-0 mt-2 w-72 bg-[#FFFFFF] rounded-2xl shadow-xl border border-[#E5E7EB] p-4 z-30 text-[#252525] space-y-3"
                                >
                                    <div>
                                        <label class="block text-[11px] font-bold text-[#6B7280] uppercase tracking-wider mb-1">Dari Tanggal</label>
                                        <input
                                            type="date"
                                            x-model="draftTanggalDari"
                                            class="w-full rounded-xl border border-[#E5E7EB] py-2 px-3 text-xs text-[#252525] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116]"
                                        />
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-[#6B7280] uppercase tracking-wider mb-1">Sampai Tanggal</label>
                                        <input
                                            type="date"
                                            x-model="draftTanggalSampai"
                                            class="w-full rounded-xl border border-[#E5E7EB] py-2 px-3 text-xs text-[#252525] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116]"
                                        />
                                    </div>
                                    <div class="flex items-center justify-between pt-1">
                                        <button
                                            type="button"
                                            @click="draftTanggalDari = ''; draftTanggalSampai = ''; datePopoverOpen = false;"
                                            class="text-xs font-semibold text-[#6B7280] hover:text-[#B01116] transition cursor-pointer"
                                        >
                                            Reset
                                        </button>
                                        <button
                                            type="button"
                                            @click="datePopoverOpen = false"
                                            class="px-3 py-1.5 rounded-lg bg-[#B01116] text-[#FFFFFF] text-xs font-bold hover:bg-[#970e13] transition cursor-pointer"
                                        >
                                            Selesai
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Dropdown: Semua Kategori -->
                            <div class="relative w-full sm:w-auto sm:min-w-[205px]">
                                <select
                                    x-model="draftKategori"
                                    aria-label="Filter Kategori"
                                    class="w-full appearance-none bg-[#FFFFFF] border border-[#E5E7EB] rounded-xl py-2.5 pl-3.5 pr-9 text-xs sm:text-sm font-medium text-[#252525] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116] focus:outline-none transition cursor-pointer"
                                >
                                    <option value="">Semua Kategori</option>
                                    <option value="sarana_prasarana">Sarana &amp; Prasarana</option>
                                    <option value="kemahasiswaan">Kemahasiswaan</option>
                                    <option value="ssc">Student Service Center</option>
                                    <option value="akademik">Akademik</option>
                                    <option value="administrasi_keuangan">Administrasi &amp; Keuangan</option>
                                    <option value="lainnya">Lainnya</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-[#252525]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>

                            <!-- Dropdown: Semua Unit -->
                            <div class="relative w-full sm:w-auto sm:min-w-[240px] sm:max-w-[300px]">
                                <select
                                    x-model="draftUnit"
                                    aria-label="Filter Unit"
                                    class="w-full appearance-none bg-[#FFFFFF] border border-[#E5E7EB] rounded-xl py-2.5 pl-3.5 pr-9 text-xs sm:text-sm font-medium text-[#252525] truncate focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116] focus:outline-none transition cursor-pointer"
                                >
                                    <option value="">Semua Unit</option>
                                    @foreach ($unitGroups ?? [] as $groupLabel => $groupUnits)
                                        <optgroup label="{{ $groupLabel }}">
                                            @foreach ($groupUnits as $unitOption)
                                                <option value="{{ $unitOption }}">{{ $unitOption }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-[#252525]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>

                            <!-- Tombol Putih Ber-border Tebal: Terapkan Filter -->
                            <button
                                type="button"
                                @click="applyFilters()"
                                class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-[#B01116] hover:bg-[#970e13] text-xs sm:text-sm font-bold text-[#FFFFFF] shadow-xs transition cursor-pointer"
                            >
                                Terapkan Filter
                            </button>

                            <button
                                type="button"
                                x-show="hasActiveFilters"
                                x-cloak
                                @click="resetFilters()"
                                class="w-full sm:w-auto px-4 py-2.5 rounded-xl bg-[#FFFFFF] hover:bg-[#FCE8E8] border border-[#E5E7EB] text-xs sm:text-sm font-bold text-[#B01116] transition cursor-pointer"
                            >
                                Reset
                            </button>
                        </div>
                    </div>

                    <!-- Grid 4 Kartu Metrik (Background Putih) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Kartu 1: Total Laporan -->
                        <div class="bg-[#FFFFFF] rounded-2xl border border-[#E5E7EB] p-5 shadow-sm flex items-center gap-4">
                            <div class="w-11 h-11 rounded-xl bg-[#FCE8E8] text-[#B01116] flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <polyline points="14 2 14 8 20 8"/>
                                    <line x1="16" y1="13" x2="8" y2="13"/>
                                    <line x1="16" y1="17" x2="8" y2="17"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-medium text-[#6B7280]">Total Laporan</p>
                                <p class="text-2xl font-extrabold text-[#252525] tracking-tight mt-0.5" x-text="metrics.total">0</p>
                            </div>
                        </div>

                        <!-- Kartu 2: Tingkat Penyelesaian -->
                        <div class="bg-[#FFFFFF] rounded-2xl border border-[#E5E7EB] p-5 shadow-sm flex items-center gap-4">
                            <div class="w-11 h-11 rounded-xl bg-[#ECFDF5] text-[#16A34A] flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                    <path d="m9 12 2 2 4-4"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-medium text-[#6B7280]">Tingkat Penyelesaian</p>
                                <p class="text-2xl font-extrabold text-[#252525] tracking-tight mt-0.5" x-text="metrics.completionRate">0,0%</p>
                            </div>
                        </div>

                        <!-- Kartu 3: Rata-rata Penanganan -->
                        <div class="bg-[#FFFFFF] rounded-2xl border border-[#E5E7EB] p-5 shadow-sm flex items-center gap-4">
                            <div class="w-11 h-11 rounded-xl bg-[#EFF6FF] text-[#2563EB] flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="12" y1="20" x2="12" y2="10"/>
                                    <line x1="18" y1="20" x2="18" y2="4"/>
                                    <line x1="6" y1="20" x2="6" y2="14"/>
                                    <line x1="3" y1="20" x2="21" y2="20"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-medium text-[#6B7280]">Rata-rata Penanganan</p>
                                <p class="text-2xl font-extrabold text-[#252525] tracking-tight mt-0.5" x-text="metrics.avgDuration">0,0 hari</p>
                            </div>
                        </div>

                        <!-- Kartu 4: Unit Teraktif -->
                        <div class="bg-[#FFFFFF] rounded-2xl border border-[#E5E7EB] p-5 shadow-sm flex items-center gap-4">
                            <div class="w-11 h-11 rounded-xl bg-[#F5F3FF] text-[#7C3AED] flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="5" r="2"/>
                                    <circle cx="5" cy="17" r="2"/>
                                    <circle cx="19" cy="17" r="2"/>
                                    <circle cx="12" cy="12" r="2.5"/>
                                    <line x1="12" y1="7" x2="12" y2="9.5"/>
                                    <line x1="6.7" y1="15.8" x2="9.8" y2="13.5"/>
                                    <line x1="17.3" y1="15.8" x2="14.2" y2="13.5"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-medium text-[#6B7280]">Unit Teraktif</p>
                                <p class="text-lg sm:text-xl font-extrabold text-[#252525] tracking-tight mt-0.5 truncate" x-text="metrics.topUnit">-</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tabel Rincian per Unit (Kartu Putih Besar di Bawah) -->
                    <section class="bg-[#FFFFFF] rounded-2xl border border-[#E5E7EB] shadow-sm overflow-hidden">
                        <!-- Header Kartu -->
                        <div class="px-6 py-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                <h2 class="text-base sm:text-lg font-bold text-[#252525] tracking-tight">
                                    Rincian per Unit
                                </h2>
                                <p class="text-xs text-[#6B7280] mt-0.5">
                                    Ringkasan data pengaduan dari database
                                </p>
                            </div>

                            <button
                                type="button"
                                @click="downloadCsv()"
                                class="inline-flex items-center gap-2 self-start sm:self-auto px-4 py-2 rounded-xl bg-[#FFFFFF] hover:bg-gray-50 border border-[#E5E7EB] text-xs font-bold text-[#252525] shadow-xs transition cursor-pointer"
                            >
                                <svg class="w-4 h-4 text-[#252525] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                    <polyline points="7 10 12 15 17 10"/>
                                    <line x1="12" y1="15" x2="12" y2="3"/>
                                </svg>
                                <span>Unduh CSV</span>
                            </button>
                        </div>

                        <!-- Wrapper Scrollable Horizontal -->
                        <div class="px-6 pb-6">
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-[720px] text-left border-collapse">
                                    <thead>
                                        <tr class="bg-[#F9FAFB] border-y border-[#E5E7EB]">
                                            <th scope="col" class="py-3.5 px-4 text-[11px] font-bold uppercase tracking-wider text-[#6B7280]">
                                                UNIT PENANGAN
                                            </th>
                                            <th scope="col" class="py-3.5 px-4 text-[11px] font-bold uppercase tracking-wider text-[#6B7280]">
                                                TOTAL
                                            </th>
                                            <th scope="col" class="py-3.5 px-4 text-[11px] font-bold uppercase tracking-wider text-[#6B7280]">
                                                DIPROSES
                                            </th>
                                            <th scope="col" class="py-3.5 px-4 text-[11px] font-bold uppercase tracking-wider text-[#6B7280]">
                                                SELESAI
                                            </th>
                                            <th scope="col" class="py-3.5 px-4 text-[11px] font-bold uppercase tracking-wider text-[#6B7280]">
                                                RATA-RATA DURASI
                                            </th>
                                            <th scope="col" class="py-3.5 px-4 text-[11px] font-bold uppercase tracking-wider text-[#6B7280]">
                                                TINGKAT SELESAI
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[#E5E7EB] text-xs sm:text-sm text-[#252525]">
                                        <template x-for="row in filteredRows" :key="row.unit">
                                            <tr class="hover:bg-[#F9FAFB]/70 transition">
                                                <td class="py-4 px-4 font-bold text-[#252525]" x-text="row.unit"></td>
                                                <td class="py-4 px-4 font-medium text-[#252525]" x-text="row.total"></td>
                                                <td class="py-4 px-4 font-medium text-[#252525]" x-text="row.diproses"></td>
                                                <td class="py-4 px-4 font-medium text-[#252525]" x-text="row.selesai"></td>
                                                <td class="py-4 px-4 font-medium text-[#252525]" x-text="row.durasi"></td>
                                                <td class="py-4 px-4 font-medium text-[#252525]" x-text="row.tingkat"></td>
                                            </tr>
                                        </template>
                                        <tr x-show="filteredRows.length === 0" x-cloak>
                                            <td colspan="6" class="py-8 px-4 text-center text-xs sm:text-sm text-[#6B7280]">
                                                Tidak ada data unit yang sesuai dengan filter yang dipilih.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>

                </div>
            </main>
        </div>
    </div>

    <script>
        function rekapitulasiApp() {
            const laporanItems = @js($laporanItems ?? []);

            return {
                mobileSidebarOpen: false,
                searchHeader: '',
                datePopoverOpen: false,
                draftTanggalDari: '',
                draftTanggalSampai: '',
                draftKategori: '',
                draftUnit: '',
                activeTanggalDari: '',
                activeTanggalSampai: '',
                activeKategori: '',
                activeUnit: '',
                items: laporanItems,

                get dateLabel() {
                    if (!this.draftTanggalDari && !this.draftTanggalSampai) return 'Semua Periode';
                    const fmt = (d) => {
                        if (!d) return '...';
                        const dt = new Date(d + 'T00:00:00');
                        return dt.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
                    };
                    return fmt(this.draftTanggalDari) + ' — ' + fmt(this.draftTanggalSampai);
                },

                get hasActiveFilters() {
                    return Boolean(
                        this.activeTanggalDari ||
                        this.activeTanggalSampai ||
                        this.activeKategori ||
                        this.activeUnit ||
                        this.searchHeader.trim()
                    );
                },

                applyFilters() {
                    this.activeTanggalDari = this.draftTanggalDari;
                    this.activeTanggalSampai = this.draftTanggalSampai;
                    this.activeKategori = this.draftKategori;
                    this.activeUnit = this.draftUnit;
                    this.datePopoverOpen = false;
                },

                resetFilters() {
                    this.searchHeader = '';
                    this.draftTanggalDari = '';
                    this.draftTanggalSampai = '';
                    this.draftKategori = '';
                    this.draftUnit = '';
                    this.activeTanggalDari = '';
                    this.activeTanggalSampai = '';
                    this.activeKategori = '';
                    this.activeUnit = '';
                    this.datePopoverOpen = false;
                },

                get filteredItems() {
                    const query = this.searchHeader.trim().toLowerCase();
                    return this.items.filter((item) => {
                        if (this.activeTanggalDari && (!item.tanggal_ymd || item.tanggal_ymd < this.activeTanggalDari)) return false;
                        if (this.activeTanggalSampai && (!item.tanggal_ymd || item.tanggal_ymd > this.activeTanggalSampai)) return false;
                        if (this.activeKategori && item.kategori_key !== this.activeKategori) return false;
                        if (this.activeUnit && item.unit !== this.activeUnit) return false;
                        if (query) {
                            const matched = item.tiket.toLowerCase().includes(query)
                                || item.judul.toLowerCase().includes(query)
                                || item.unit.toLowerCase().includes(query)
                                || item.kategori_label.toLowerCase().includes(query);
                            if (!matched) return false;
                        }
                        return true;
                    });
                },

                get filteredRows() {
                    const groups = new Map();
                    for (const item of this.filteredItems) {
                        if (!groups.has(item.unit)) {
                            groups.set(item.unit, {
                                unit: item.unit,
                                shortUnit: item.shortUnit,
                                total: 0,
                                diproses: 0,
                                selesai: 0,
                                durasiSum: 0,
                            });
                        }
                        const g = groups.get(item.unit);
                        g.total += 1;
                        if (item.status === 'Selesai') {
                            g.selesai += 1;
                        } else if (['Diproses', 'Diteruskan', 'Menunggu Verifikasi'].includes(item.status)) {
                            g.diproses += 1;
                        }
                        g.durasiSum += item.durasi_hari || 1;
                    }

                    return Array.from(groups.values()).map((g) => {
                        const avgDays = g.total > 0 ? g.durasiSum / g.total : 0;
                        const rate = g.total > 0 ? (g.selesai / g.total) * 100 : 0;
                        return {
                            unit: g.unit,
                            shortUnit: g.shortUnit,
                            total: g.total,
                            diproses: g.diproses,
                            selesai: g.selesai,
                            durasiSum: g.durasiSum,
                            durasi: avgDays.toFixed(1).replace('.', ',') + ' hari',
                            durasiNum: avgDays,
                            tingkat: rate.toFixed(1).replace('.', ',') + '%',
                        };
                    });
                },

                get metrics() {
                    const items = this.filteredItems;
                    const rows = this.filteredRows;
                    if (items.length === 0 || rows.length === 0) {
                        return {
                            total: '0',
                            completionRate: '0,0%',
                            avgDuration: '0,0 hari',
                            topUnit: '-'
                        };
                    }

                    const total = items.length;
                    const selesaiAll = items.filter((i) => i.status === 'Selesai').length;
                    const rate = ((selesaiAll / total) * 100).toFixed(1).replace('.', ',') + '%';
                    const totalDays = items.reduce((sum, i) => sum + (i.durasi_hari || 1), 0);
                    const avgDur = (totalDays / total).toFixed(1).replace('.', ',') + ' hari';
                    const top = [...rows].sort((a, b) => b.total - a.total)[0];

                    return {
                        total: String(total),
                        completionRate: rate,
                        avgDuration: avgDur,
                        topUnit: top.shortUnit
                    };
                },

                get exportFilenameBase() {
                    return 'rekapitulasi-pengaduan-' + new Date().toISOString().slice(0, 10);
                },

                downloadCsv() {
                    const headers = ['UNIT PENANGAN', 'TOTAL', 'DIPROSES', 'SELESAI', 'RATA-RATA DURASI', 'TINGKAT SELESAI'];
                    const lines = [
                        headers.join(','),
                        ...this.filteredRows.map((row) => [
                            `"${row.unit}"`,
                            row.total,
                            row.diproses,
                            row.selesai,
                            `"${row.durasi}"`,
                            `"${row.tingkat}"`
                        ].join(','))
                    ];

                    const blob = new Blob([lines.join('\n')], { type: 'text/csv;charset=utf-8;' });
                    this.triggerDownload(blob, this.exportFilenameBase + '.csv');
                },

                downloadExcel() {
                    const rowsHtml = this.filteredRows.map((row) => `
                        <tr>
                            <td>${row.unit}</td>
                            <td>${row.total}</td>
                            <td>${row.diproses}</td>
                            <td>${row.selesai}</td>
                            <td>${row.durasi}</td>
                            <td>${row.tingkat}</td>
                        </tr>
                    `).join('');

                    const html = `
                        <html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
                        <head><meta charset="utf-8" /></head>
                        <body>
                            <table border="1">
                                <thead>
                                    <tr>
                                        <th>UNIT PENANGAN</th>
                                        <th>TOTAL</th>
                                        <th>DIPROSES</th>
                                        <th>SELESAI</th>
                                        <th>RATA-RATA DURASI</th>
                                        <th>TINGKAT SELESAI</th>
                                    </tr>
                                </thead>
                                <tbody>${rowsHtml}</tbody>
                            </table>
                        </body>
                        </html>
                    `;

                    const blob = new Blob([html], { type: 'application/vnd.ms-excel;charset=utf-8;' });
                    this.triggerDownload(blob, this.exportFilenameBase + '.xls');
                },

                triggerDownload(blob, filename) {
                    const url = URL.createObjectURL(blob);
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', filename);
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    URL.revokeObjectURL(url);
                }
            };
        }
    </script>
</body>
</html>

