<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#FFFFFF]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Monitoring Laporan - Admin SSC CampusVoice</title>

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

        /* Linear Gradient Konten Utama: Merah #B01116 ke Abu-abu Gelap #58595B */
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
        searchQuery: '',
        filterStatus: '',
        filterPrioritas: '',
        filterKategori: '',
        filterUnit: '',
        onlyOverdue: false,
        rows: [
            {
                tiket: 'CV-2026-00124',
                judul: 'Permohonan Perbaikan AC Ruang Kelas',
                kategori: 'Satpras',
                prioritas: 'Tinggi',
                unit: 'Direktorat Logistik dan Aset',
                dikirim: '18 Okt 2026',
                diperbarui: 'Update: 19 Okt 2026, 08.45',
                durasi: '1 hari',
                overdue: false,
                status: 'Diproses'
            },
            {
                tiket: 'CV-2026-00119',
                judul: 'Kendala Akses Portal Akademik',
                kategori: 'SSC',
                prioritas: 'Sedang',
                unit: 'Direktorat Pusat Teknologi Informasi',
                dikirim: '16 Okt 2026',
                diperbarui: 'Update: 18 Okt 2026, 14.20',
                durasi: '3 hari',
                overdue: false,
                status: 'Diteruskan'
            },
            {
                tiket: 'CV-2026-00108',
                judul: 'Permasalahan Transkrip Nilai',
                kategori: 'SSC',
                prioritas: 'Tinggi',
                unit: 'Program Studi S1 Sistem Informasi',
                dikirim: '12 Okt 2026',
                diperbarui: 'Update: 16 Okt 2026, 10.05',
                durasi: '4 hari',
                overdue: false,
                status: 'Selesai'
            },
            {
                tiket: 'CV-2026-00097',
                judul: 'Usulan Penambahan Fasilitas Belajar',
                kategori: 'Satpras',
                prioritas: '',
                unit: 'Belum Ditentukan',
                dikirim: '8 Okt 2026',
                diperbarui: 'Update: 8 Okt 2026, 09.18',
                durasi: '11 hari',
                overdue: true,
                status: 'Menunggu Verifikasi'
            },
            {
                tiket: 'CV-2026-00088',
                judul: 'Perbaikan akses ruang laboratorium',
                kategori: 'Satpras',
                prioritas: 'Sedang',
                unit: 'Direktorat Logistik dan Aset',
                dikirim: '10 Okt 2026',
                diperbarui: 'Update: 11 Okt 2026, 13.10',
                durasi: '9 hari',
                overdue: true,
                status: 'Diteruskan'
            },
            {
                tiket: 'CV-2026-00081',
                judul: 'Permohonan koreksi data akademik',
                kategori: 'SSC',
                prioritas: 'Sedang',
                unit: 'Program Studi S1 Sistem Informasi',
                dikirim: '6 Okt 2026',
                diperbarui: 'Update: 7 Okt 2026, 15.42',
                durasi: '1 hari',
                overdue: false,
                status: 'Ditolak'
            }
        ],
        get filteredRows() {
            const q = (this.searchQuery || this.searchHeader).trim().toLowerCase();
            return this.rows.filter(r => {
                if (this.onlyOverdue && !r.overdue) return false;
                if (this.filterStatus && r.status !== this.filterStatus) return false;
                if (this.filterPrioritas && r.prioritas !== this.filterPrioritas) return false;
                if (this.filterKategori && r.kategori !== this.filterKategori) return false;
                if (this.filterUnit && r.unit !== this.filterUnit) return false;
                if (q && !r.tiket.toLowerCase().includes(q) && !r.judul.toLowerCase().includes(q)) return false;
                return true;
            });
        },
        resetFilters() {
            this.searchHeader = '';
            this.searchQuery = '';
            this.filterStatus = '';
            this.filterPrioritas = '';
            this.filterKategori = '';
            this.filterUnit = '';
            this.onlyOverdue = false;
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

                        <!-- Menu 3: Monitoring Laporan (AKTIF) -->
                        <a
                            href="{{ route('admin.monitoring') }}"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold bg-[#FCE8E8] text-[#B01116] transition shadow-xs"
                        >
                            <svg class="w-5 h-5 text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
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
                            <span class="w-5 h-5 rounded-full bg-[#B01116] text-[#FFFFFF] text-[11px] font-bold flex items-center justify-center">
                                3
                            </span>
                        </a>
                    </nav>
                </div>

                <!-- Bagian Bawah: Profil & Keluar -->
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
                    <a
                        href="{{ route('admin.notifikasi') }}"
                        class="relative p-2 rounded-lg border border-[#E5E7EB] text-[#6B7280] hover:text-[#B01116] hover:bg-gray-50 transition cursor-pointer"
                        title="Notifikasi"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span class="absolute -top-1.5 -right-1.5 w-4 h-4 rounded-full bg-[#B01116] text-[#FFFFFF] text-[10px] font-bold flex items-center justify-center shadow-xs">
                            3
                        </span>
                    </a>

                    <!-- Profil Admin SSC: Siti Rahmawati -->
                    <a href="{{ route('admin.profil') }}" class="flex items-center gap-2.5 pl-1 group">
                        <div class="w-9 h-9 rounded-lg bg-[#FCE8E8] text-[#B01116] font-bold text-xs flex items-center justify-center shrink-0">
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
            <main class="flex-1 content-gradient-bg px-4 sm:px-8 lg:px-10 py-7 text-[#FFFFFF]">
                <div class="max-w-7xl mx-auto space-y-5">

                    <!-- Header Halaman (Atas) -->
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-[#FFFFFF] tracking-tight">
                            Monitoring Seluruh Laporan
                        </h1>
                        <p class="text-xs sm:text-sm text-[#FFFFFF]/85 mt-1 font-normal">
                            Cari dan pantau status, unit penangan, riwayat, serta durasi seluruh laporan.
                        </p>
                    </div>

                    <!-- Grid 6 Kartu Metrik Ringkasan -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-6 gap-3.5">
                        <!-- 1. Total Laporan -->
                        <div class="bg-[#FFFFFF] rounded-2xl p-4 shadow-lg border border-[#E5E7EB] flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-[#FEF2F2] text-[#B01116] flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[11px] font-medium text-[#6B7280] leading-tight">Total Laporan</p>
                                <p class="text-xl font-extrabold text-[#252525] mt-0.5 leading-none">1.248</p>
                            </div>
                        </div>

                        <!-- 2. Menunggu Verifikasi -->
                        <div class="bg-[#FFFFFF] rounded-2xl p-4 shadow-lg border border-[#E5E7EB] flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-[#FFF7ED] text-[#D97706] flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                    <path d="m9 12 2 2 4-4"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[11px] font-medium text-[#6B7280] leading-tight">Menunggu Verifikasi</p>
                                <p class="text-xl font-extrabold text-[#252525] mt-0.5 leading-none">89</p>
                            </div>
                        </div>

                        <!-- 3. Diteruskan ke Unit -->
                        <div class="bg-[#FFFFFF] rounded-2xl p-4 shadow-lg border border-[#E5E7EB] flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-[#EFF6FF] text-[#2563EB] flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[11px] font-medium text-[#6B7280] leading-tight">Diteruskan ke Unit</p>
                                <p class="text-xl font-extrabold text-[#252525] mt-0.5 leading-none">145</p>
                            </div>
                        </div>

                        <!-- 4. Sedang Diproses -->
                        <div class="bg-[#FFFFFF] rounded-2xl p-4 shadow-lg border border-[#E5E7EB] flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-[#F5F3FF] text-[#7C3AED] flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[11px] font-medium text-[#6B7280] leading-tight">Sedang Diproses</p>
                                <p class="text-xl font-extrabold text-[#252525] mt-0.5 leading-none">122</p>
                            </div>
                        </div>

                        <!-- 5. Laporan Selesai -->
                        <div class="bg-[#FFFFFF] rounded-2xl p-4 shadow-lg border border-[#E5E7EB] flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-[#ECFDF5] text-[#16A34A] flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[11px] font-medium text-[#6B7280] leading-tight">Laporan Selesai</p>
                                <p class="text-xl font-extrabold text-[#252525] mt-0.5 leading-none">892</p>
                            </div>
                        </div>

                        <!-- 6. Laporan Ditolak -->
                        <div class="bg-[#FFFFFF] rounded-2xl p-4 shadow-lg border border-[#E5E7EB] flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-[#FEF2F2] text-[#DC2626] flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[11px] font-medium text-[#6B7280] leading-tight">Laporan Ditolak</p>
                                <p class="text-xl font-extrabold text-[#252525] mt-0.5 leading-none">34</p>
                            </div>
                        </div>
                    </div>

                    <!-- Baris Filter & Pencarian (Dua Baris Kompak di dalam Kotak Putih) -->
                    <div class="bg-[#FFFFFF] rounded-2xl p-4 shadow-lg border border-[#E5E7EB] space-y-3">
                        <!-- Baris Filter 1 -->
                        <div class="flex flex-wrap items-center gap-3">
                            <!-- Input Pencarian -->
                            <div class="flex-1 min-w-[240px]">
                                <input
                                    type="text"
                                    x-model="searchQuery"
                                    placeholder="Cari nomor tiket atau judul..."
                                    class="w-full bg-[#FFFFFF] rounded-xl border border-[#E5E7EB] py-2 px-3.5 text-xs sm:text-sm text-[#252525] placeholder-[#6B7280] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116] focus:outline-none transition"
                                />
                            </div>

                            <!-- Dropdown Status -->
                            <div class="relative min-w-[155px] shrink-0">
                                <select
                                    x-model="filterStatus"
                                    class="w-full appearance-none bg-[#FFFFFF] rounded-xl border border-[#E5E7EB] py-2 pl-3.5 pr-9 text-xs sm:text-sm font-medium text-[#252525] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116] focus:outline-none transition cursor-pointer"
                                >
                                    <option value="">Semua Status</option>
                                    <option value="Menunggu Verifikasi">Menunggu Verifikasi</option>
                                    <option value="Diteruskan">Diteruskan</option>
                                    <option value="Diproses">Diproses</option>
                                    <option value="Selesai">Selesai</option>
                                    <option value="Ditolak">Ditolak</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-[#6B7280]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>

                            <!-- Dropdown Prioritas -->
                            <div class="relative min-w-[155px] shrink-0">
                                <select
                                    x-model="filterPrioritas"
                                    class="w-full appearance-none bg-[#FFFFFF] rounded-xl border border-[#E5E7EB] py-2 pl-3.5 pr-9 text-xs sm:text-sm font-medium text-[#252525] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116] focus:outline-none transition cursor-pointer"
                                >
                                    <option value="">Semua Prioritas</option>
                                    <option value="Tinggi">Tinggi</option>
                                    <option value="Sedang">Sedang</option>
                                    <option value="Rendah">Rendah</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-[#6B7280]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>

                            <!-- Dropdown Kategori -->
                            <div class="relative min-w-[155px] shrink-0">
                                <select
                                    x-model="filterKategori"
                                    class="w-full appearance-none bg-[#FFFFFF] rounded-xl border border-[#E5E7EB] py-2 pl-3.5 pr-9 text-xs sm:text-sm font-medium text-[#252525] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116] focus:outline-none transition cursor-pointer"
                                >
                                    <option value="">Semua Kategori</option>
                                    <option value="Satpras">Satpras</option>
                                    <option value="SSC">SSC</option>
                                    <option value="Akademik">Akademik</option>
                                    <option value="Kemahasiswaan">Kemahasiswaan</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-[#6B7280]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                        </div>

                        <!-- Baris Filter 2 -->
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div class="flex flex-wrap items-center gap-3">
                                <!-- Dropdown Unit -->
                                <div class="relative min-w-[260px] sm:min-w-[320px]">
                                    <select
                                        x-model="filterUnit"
                                        class="w-full appearance-none bg-[#FFFFFF] rounded-xl border border-[#E5E7EB] py-2 pl-3.5 pr-9 text-xs sm:text-sm font-medium text-[#252525] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116] focus:outline-none transition cursor-pointer"
                                    >
                                        <option value="">Semua Unit</option>
                                        <option value="Direktorat Logistik dan Aset">Direktorat Logistik dan Aset</option>
                                        <option value="Direktorat Pusat Teknologi Informasi">Direktorat Pusat Teknologi Informasi</option>
                                        <option value="Program Studi S1 Sistem Informasi">Program Studi S1 Sistem Informasi</option>
                                        <option value="Belum Ditentukan">Belum Ditentukan</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-[#6B7280]">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                </div>

                                <!-- Range Tanggal -->
                                <div class="bg-[#FFFFFF] rounded-xl border border-[#E5E7EB] py-2 px-4 text-xs sm:text-sm font-medium text-[#252525] whitespace-nowrap flex items-center gap-2 cursor-pointer hover:bg-gray-50 transition">
                                    <span>19 Sep — 19 Okt 2026</span>
                                </div>
                            </div>

                            <!-- Indikator/Reset Filter Jika Aktif -->
                            <button
                                x-show="onlyOverdue || searchQuery || searchHeader || filterStatus || filterPrioritas || filterKategori || filterUnit"
                                x-cloak
                                type="button"
                                @click="resetFilters()"
                                class="text-xs font-bold text-[#B01116] hover:underline cursor-pointer"
                            >
                                Reset Filter
                            </button>
                        </div>
                    </div>

                    <!-- Tabel "Daftar Seluruh Laporan" (Kartu Putih Besar) -->
                    <div class="bg-[#FFFFFF] rounded-2xl shadow-xl overflow-hidden border border-[#E5E7EB]">
                        <!-- Header Kartu -->
                        <div class="px-6 pt-5 pb-4">
                            <h2 class="text-base font-bold text-[#252525] leading-tight">Daftar Seluruh Laporan</h2>
                            <p class="text-xs text-[#6B7280] mt-0.5">Data lintas unit dengan pembaruan dan durasi penanganan terkini.</p>
                        </div>

                        <!-- Tabel -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-y border-[#E5E7EB] bg-[#F9FAFB]/70">
                                        <th scope="col" class="py-3.5 px-6 text-[11px] font-bold text-[#6B7280] uppercase tracking-wider">
                                            NOMOR TIKET / LAPORAN
                                        </th>
                                        <th scope="col" class="py-3.5 px-4 text-[11px] font-bold text-[#6B7280] uppercase tracking-wider">
                                            KATEGORI
                                        </th>
                                        <th scope="col" class="py-3.5 px-4 text-[11px] font-bold text-[#6B7280] uppercase tracking-wider">
                                            PRIORITAS
                                        </th>
                                        <th scope="col" class="py-3.5 px-4 text-[11px] font-bold text-[#6B7280] uppercase tracking-wider">
                                            UNIT PENANGAN
                                        </th>
                                        <th scope="col" class="py-3.5 px-4 text-[11px] font-bold text-[#6B7280] uppercase tracking-wider">
                                            DIKIRIM / DIPERBARUI
                                        </th>
                                        <th scope="col" class="py-3.5 px-4 text-[11px] font-bold text-[#6B7280] uppercase tracking-wider">
                                            DURASI
                                        </th>
                                        <th scope="col" class="py-3.5 px-4 text-[11px] font-bold text-[#6B7280] uppercase tracking-wider">
                                            STATUS
                                        </th>
                                        <th scope="col" class="py-3.5 px-6 text-[11px] font-bold text-[#6B7280] uppercase tracking-wider text-right">
                                            <span class="sr-only">Aksi</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#E5E7EB]">
                                    <template x-for="row in filteredRows" :key="row.tiket">
                                        <tr
                                            class="transition"
                                            :class="row.overdue ? 'bg-[#FEFCE8] hover:bg-[#fef9c3]/70' : 'bg-[#FFFFFF] hover:bg-gray-50/80'"
                                        >
                                            <!-- NOMOR TIKET / LAPORAN -->
                                            <td class="py-4 px-6">
                                                <div class="text-xs sm:text-sm font-bold text-[#252525]" x-text="row.tiket"></div>
                                                <div class="text-[11px] text-[#6B7280] mt-0.5 leading-snug" x-text="row.judul"></div>
                                            </td>

                                            <!-- KATEGORI -->
                                            <td class="py-4 px-4 whitespace-nowrap text-xs sm:text-sm text-[#252525]" x-text="row.kategori"></td>

                                            <!-- PRIORITAS -->
                                            <td class="py-4 px-4 whitespace-nowrap">
                                                <template x-if="row.prioritas === 'Tinggi'">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md bg-[#FEF2F2] text-[#B01116] text-[11px] font-bold">
                                                        Tinggi
                                                    </span>
                                                </template>
                                                <template x-if="row.prioritas === 'Sedang'">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md bg-[#FFF7ED] text-[#D97706] text-[11px] font-bold">
                                                        Sedang
                                                    </span>
                                                </template>
                                                <template x-if="row.prioritas === 'Rendah'">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md bg-[#F3F4F6] text-[#252525] text-[11px] font-bold">
                                                        Rendah
                                                    </span>
                                                </template>
                                                <template x-if="!row.prioritas">
                                                    <span class="text-xs font-semibold text-[#6B7280]">&mdash;</span>
                                                </template>
                                            </td>

                                            <!-- UNIT PENANGAN -->
                                            <td class="py-4 px-4 text-xs sm:text-sm text-[#252525]" x-text="row.unit"></td>

                                            <!-- DIKIRIM / DIPERBARUI -->
                                            <td class="py-4 px-4 whitespace-nowrap">
                                                <div class="text-xs sm:text-sm text-[#252525]" x-text="row.dikirim"></div>
                                                <div class="text-[11px] text-[#6B7280] mt-0.5" x-text="row.diperbarui"></div>
                                            </td>

                                            <!-- DURASI -->
                                            <td class="py-4 px-4 whitespace-nowrap">
                                                <div
                                                    class="text-xs sm:text-sm"
                                                    :class="row.overdue ? 'font-extrabold text-[#DC2626]' : 'text-[#252525]'"
                                                    x-text="row.durasi"
                                                ></div>
                                                <template x-if="row.overdue">
                                                    <div class="text-[11px] font-medium text-[#DC2626] mt-0.5">Perlu ditinjau</div>
                                                </template>
                                            </td>

                                            <!-- STATUS -->
                                            <td class="py-4 px-4 whitespace-nowrap">
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold"
                                                    :class="{
                                                        'bg-[#F5F3FF] text-[#7C3AED]': row.status === 'Diproses',
                                                        'bg-[#EFF6FF] text-[#2563EB]': row.status === 'Diteruskan',
                                                        'bg-[#ECFDF5] text-[#16A34A]': row.status === 'Selesai',
                                                        'bg-[#FFF7ED] text-[#D97706]': row.status === 'Menunggu Verifikasi',
                                                        'bg-[#FEF2F2] text-[#DC2626]': row.status === 'Ditolak'
                                                    }"
                                                >
                                                    <span
                                                        class="w-1.5 h-1.5 rounded-full"
                                                        :class="{
                                                            'bg-[#7C3AED]': row.status === 'Diproses',
                                                            'bg-[#2563EB]': row.status === 'Diteruskan',
                                                            'bg-[#16A34A]': row.status === 'Selesai',
                                                            'bg-[#D97706]': row.status === 'Menunggu Verifikasi',
                                                            'bg-[#DC2626]': row.status === 'Ditolak'
                                                        }"
                                                    ></span>
                                                    <span x-text="row.status"></span>
                                                </span>
                                            </td>

                                            <!-- AKSI -->
                                            <td class="py-4 px-6 whitespace-nowrap text-right">
                                                <a
                                                    :href="'{{ url('/admin/verifikasi-laporan') }}/' + row.tiket"
                                                    class="inline-flex items-center text-xs sm:text-sm font-bold text-[#B01116] hover:text-[#970e13] transition cursor-pointer"
                                                >
                                                    Detail &rarr;
                                                </a>
                                            </td>
                                        </tr>
                                    </template>

                                    <!-- Empty State jika filter tidak cocok -->
                                    <tr x-show="filteredRows.length === 0" x-cloak>
                                        <td colspan="8" class="py-10 px-6 text-center text-xs sm:text-sm text-[#6B7280] bg-[#FFFFFF]">
                                            Tidak ada laporan yang cocok dengan filter pencarian.
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
                                <button
                                    type="button"
                                    class="w-8 h-8 rounded-lg border border-[#E5E7EB] bg-[#FFFFFF] flex items-center justify-center text-[#6B7280] hover:bg-gray-50 transition cursor-pointer shadow-2xs"
                                    title="Halaman Sebelumnya"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                </button>
                                <button
                                    type="button"
                                    class="w-8 h-8 rounded-lg bg-[#B01116] text-[#FFFFFF] font-bold text-xs flex items-center justify-center shadow-xs"
                                >
                                    1
                                </button>
                                <button
                                    type="button"
                                    class="w-8 h-8 rounded-lg border border-[#E5E7EB] bg-[#FFFFFF] text-[#252525] font-semibold text-xs flex items-center justify-center hover:bg-gray-50 transition cursor-pointer shadow-2xs"
                                >
                                    2
                                </button>
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

                    <!-- Banner Peringatan Bawah (Card Putih Terpisah) -->
                    <div class="bg-[#FFFFFF] rounded-2xl p-4 sm:px-6 shadow-xl border border-[#E5E7EB] flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div class="flex items-start sm:items-center gap-3.5">
                            <div class="text-[#D97706] shrink-0 mt-0.5 sm:mt-0">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                    <path d="m9 12 2 2 4-4"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs sm:text-sm font-bold text-[#252525]">
                                    12 laporan berada pada status yang sama lebih dari 7 hari
                                </p>
                                <p class="text-[11px] sm:text-xs text-[#6B7280] mt-0.5">
                                    Durasi ditampilkan berdasarkan riwayat timestamp untuk membantu pemantauan SSC. Tidak ada eskalasi otomatis.
                                </p>
                            </div>
                        </div>

                        <button
                            type="button"
                            @click="onlyOverdue = !onlyOverdue"
                            class="px-4 py-2 rounded-xl border border-[#E5E7EB] bg-[#FFFFFF] hover:bg-gray-50 text-xs font-bold text-[#252525] transition shrink-0 cursor-pointer shadow-2xs"
                            x-text="onlyOverdue ? 'Tampilkan semua' : 'Tinjau laporan'"
                        ></button>
                    </div>

                </div>
            </main>

        </div>
    </div>
</body>
</html>

