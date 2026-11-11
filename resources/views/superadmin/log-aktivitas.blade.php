<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#FFFFFF]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Log Aktivitas - Super Admin CampusVoice</title>

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

        /* Linear Gradient Konten Utama: Merah #B01116 ke Abu-abu Gelap #58595B (dari kiri bawah ke kanan atas) */
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
        searchLog: '',
        filterStatus: '',
        filterKategori: '',
        logs: [
            {
                id: 1,
                timestamp: '19 Okt 2026, 10.42',
                pengguna: 'Siti Rahmawati',
                peran: 'Admin SSC',
                aktivitas: 'Penyaluran laporan',
                kategori: 'Penyaluran',
                status: 'Berhasil',
                record: 'CV-2026-00132',
                ip: '10.20.14.8'
            },
            {
                id: 2,
                timestamp: '19 Okt 2026, 10.31',
                pengguna: 'Budi Santoso',
                peran: 'Petugas Unit',
                aktivitas: 'Pembaruan progres',
                kategori: 'Progres',
                status: 'Berhasil',
                record: 'CV-2026-00124',
                ip: '10.20.8.21'
            },
            {
                id: 3,
                timestamp: '19 Okt 2026, 09.18',
                pengguna: 'Rizky Pratama',
                peran: 'Super Admin',
                aktivitas: 'Perubahan kategori',
                kategori: 'Sistem',
                status: 'Berhasil',
                record: 'Fasilitas dan Sarana',
                ip: '10.20.2.4'
            },
            {
                id: 4,
                timestamp: '19 Okt 2026, 08.57',
                pengguna: 'Maya Prameswari',
                peran: 'Petugas Unit',
                aktivitas: 'User login',
                kategori: 'Autentikasi',
                status: 'Berhasil',
                record: '—',
                ip: '10.20.9.7'
            }
        ],
        get filteredLogs() {
            const q = (this.searchLog || this.searchHeader).trim().toLowerCase();
            return this.logs.filter(item => {
                if (this.filterStatus && item.status !== this.filterStatus) return false;
                if (this.filterKategori && item.kategori !== this.filterKategori) return false;
                if (q &&
                    !item.pengguna.toLowerCase().includes(q) &&
                    !item.aktivitas.toLowerCase().includes(q) &&
                    !item.record.toLowerCase().includes(q) &&
                    !item.ip.toLowerCase().includes(q)
                ) return false;
                return true;
            });
        }
    }"
>
    <div class="min-h-screen flex flex-col lg:flex-row">

        <!-- ========================================================= -->
        <!-- SIDEBAR KIRI (SUPER ADMIN) -->
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

                <!-- Navigasi Menu Super Admin -->
                <div class="px-4 py-3 flex-1">
                    <p class="px-3 text-[11px] font-bold text-[#252525] tracking-wide mb-2">Super Admin</p>

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

                        <!-- Menu 2: Manajemen Akun -->
                        <a
                            href="#"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#252525] hover:bg-gray-100 hover:text-[#B01116] transition group"
                        >
                            <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                            <span>Manajemen Akun</span>
                        </a>

                        <!-- Menu 3: Unit Penangan -->
                        <a
                            href="#"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#252525] hover:bg-gray-100 hover:text-[#B01116] transition group"
                        >
                            <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="18" r="3"/>
                                <circle cx="6" cy="6" r="3"/>
                                <circle cx="18" cy="6" r="3"/>
                                <path d="M18 9v1a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V9"/>
                                <path d="M12 12v3"/>
                            </svg>
                            <span>Unit Penangan</span>
                        </a>

                        <!-- Menu 4: Kategori & Penyaluran -->
                        <a
                            href="#"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#252525] hover:bg-gray-100 hover:text-[#B01116] transition group"
                        >
                            <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"/>
                                <polyline points="12 5 19 12 12 19"/>
                            </svg>
                            <span>Kategori &amp; Penyaluran</span>
                        </a>

                        <!-- Menu 5: Log Aktivitas (AKTIF) -->
                        <a
                            href="{{ route('superadmin.log') }}"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold bg-[#FCE8E8] text-[#B01116] transition shadow-xs"
                        >
                            <svg class="w-5 h-5 text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                                <line x1="16" y1="13" x2="8" y2="13"/>
                                <line x1="16" y1="17" x2="8" y2="17"/>
                            </svg>
                            <span>Log Aktivitas</span>
                        </a>
                    </nav>
                </div>

                <!-- Bagian Bawah: Profil Saya & Keluar -->
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

                <!-- Bagian Kanan: Lonceng Notifikasi & Profil Super Admin -->
                <div class="flex items-center gap-3 sm:gap-4">
                    <!-- Tombol Lonceng Notifikasi -->
                    <button
                        type="button"
                        class="relative p-2 rounded-lg border border-[#E5E7EB] text-[#6B7280] hover:text-[#B01116] hover:bg-gray-50 transition cursor-pointer"
                        title="Notifikasi"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span class="absolute -top-1.5 -right-1.5 w-4 h-4 rounded-full bg-[#B01116] text-[#FFFFFF] text-[10px] font-bold flex items-center justify-center shadow-xs">
                            3
                        </span>
                    </button>

                    <!-- Profil Super Admin: Rizky Pratama -->
                    <div class="flex items-center gap-2.5 pl-1">
                        <div class="w-9 h-9 rounded-lg bg-[#FCE8E8] text-[#B01116] font-bold text-xs flex items-center justify-center shrink-0">
                            RP
                        </div>
                        <div class="hidden sm:block text-left">
                            <p class="text-xs font-bold text-[#252525] leading-tight">Rizky Pratama</p>
                            <p class="text-[11px] text-[#6B7280] leading-tight">Super Admin</p>
                        </div>
                    </div>
                </div>
            </header>

            <!-- ========================================================= -->
            <!-- AREA KONTEN UTAMA: Linear Gradient #B01116 ke #58595B -->
            <!-- ========================================================= -->
            <main class="flex-1 content-gradient-bg px-4 sm:px-8 lg:px-10 py-7">
                <div class="max-w-7xl mx-auto space-y-6">

                    <!-- Header Halaman -->
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-[#FFFFFF] tracking-tight">
                            Log Aktivitas
                        </h1>
                        <p class="text-xs sm:text-sm text-[#FFFFFF]/85 mt-1 font-normal">
                            Jejak audit aktivitas penting pengguna dan perubahan sistem.
                        </p>
                    </div>

                    <!-- Baris Filter & Pencarian (4 Elemen Sejajar) -->
                    <div class="flex flex-wrap items-center gap-3">
                        <!-- 1. Input Pencarian -->
                        <div class="flex-1 min-w-[240px]">
                            <input
                                type="text"
                                x-model="searchLog"
                                placeholder="Cari nomor tiket atau judul..."
                                class="w-full bg-[#FFFFFF] rounded-xl border border-[#E5E7EB] py-2.5 px-4 text-xs sm:text-sm text-[#252525] placeholder-[#6B7280] shadow-sm focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116] focus:outline-none transition"
                            />
                        </div>

                        <!-- 2. Dropdown Status -->
                        <div class="relative min-w-[155px] shrink-0">
                            <select
                                x-model="filterStatus"
                                class="w-full appearance-none bg-[#FFFFFF] rounded-xl border border-[#E5E7EB] py-2.5 pl-4 pr-9 text-xs sm:text-sm font-medium text-[#252525] shadow-sm focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116] focus:outline-none transition cursor-pointer"
                            >
                                <option value="">Semua Status</option>
                                <option value="Berhasil">Berhasil</option>
                                <option value="Gagal">Gagal</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-[#6B7280]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>

                        <!-- 3. Dropdown Kategori -->
                        <div class="relative min-w-[160px] shrink-0">
                            <select
                                x-model="filterKategori"
                                class="w-full appearance-none bg-[#FFFFFF] rounded-xl border border-[#E5E7EB] py-2.5 pl-4 pr-9 text-xs sm:text-sm font-medium text-[#252525] shadow-sm focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116] focus:outline-none transition cursor-pointer"
                            >
                                <option value="">Semua Kategori</option>
                                <option value="Penyaluran">Penyaluran</option>
                                <option value="Progres">Progres</option>
                                <option value="Sistem">Sistem</option>
                                <option value="Autentikasi">Autentikasi</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-[#6B7280]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>

                        <!-- 4. Range Tanggal -->
                        <div class="bg-[#FFFFFF] rounded-xl border border-[#E5E7EB] py-2.5 px-4 text-xs sm:text-sm font-medium text-[#252525] shadow-sm whitespace-nowrap shrink-0 flex items-center cursor-pointer hover:bg-gray-50 transition">
                            <span>19 Sep — 19 Okt 2026</span>
                        </div>
                    </div>

                    <!-- Tabel Log Aktivitas (Kartu Putih Besar #FFFFFF) -->
                    <div class="bg-[#FFFFFF] rounded-2xl shadow-xl overflow-hidden border border-[#E5E7EB]">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-[#E5E7EB] bg-[#F9FAFB]/70">
                                        <th scope="col" class="py-4 px-6 text-[11px] font-bold text-[#6B7280] uppercase tracking-wider">
                                            TIMESTAMP
                                        </th>
                                        <th scope="col" class="py-4 px-6 text-[11px] font-bold text-[#6B7280] uppercase tracking-wider">
                                            PENGGUNA
                                        </th>
                                        <th scope="col" class="py-4 px-6 text-[11px] font-bold text-[#6B7280] uppercase tracking-wider">
                                            PERAN
                                        </th>
                                        <th scope="col" class="py-4 px-6 text-[11px] font-bold text-[#6B7280] uppercase tracking-wider">
                                            AKTIVITAS
                                        </th>
                                        <th scope="col" class="py-4 px-6 text-[11px] font-bold text-[#6B7280] uppercase tracking-wider">
                                            RECORD TERKAIT
                                        </th>
                                        <th scope="col" class="py-4 px-6 text-[11px] font-bold text-[#6B7280] uppercase tracking-wider">
                                            IP ADDRESS
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#E5E7EB] bg-[#FFFFFF]">
                                    <template x-for="log in filteredLogs" :key="log.id">
                                        <tr class="hover:bg-gray-50/80 transition">
                                            <!-- TIMESTAMP -->
                                            <td class="py-4 px-6 whitespace-nowrap text-xs sm:text-sm text-[#6B7280]" x-text="log.timestamp"></td>

                                            <!-- PENGGUNA -->
                                            <td class="py-4 px-6 whitespace-nowrap text-xs sm:text-sm font-bold text-[#252525]" x-text="log.pengguna"></td>

                                            <!-- PERAN (Role Badges) -->
                                            <td class="py-4 px-6 whitespace-nowrap">
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold"
                                                    :class="{
                                                        'bg-[#EEF2FF] text-[#252525]': log.peran === 'Admin SSC',
                                                        'bg-[#F3F4F6] text-[#252525]': log.peran === 'Petugas Unit',
                                                        'bg-[#EFF6FF] text-[#252525]': log.peran === 'Super Admin'
                                                    }"
                                                >
                                                    <span
                                                        class="w-1.5 h-1.5 rounded-full"
                                                        :class="{
                                                            'bg-[#6366F1]': log.peran === 'Admin SSC',
                                                            'bg-[#64748B]': log.peran === 'Petugas Unit',
                                                            'bg-[#2563EB]': log.peran === 'Super Admin'
                                                        }"
                                                    ></span>
                                                    <span x-text="log.peran"></span>
                                                </span>
                                            </td>

                                            <!-- AKTIVITAS -->
                                            <td class="py-4 px-6 whitespace-nowrap text-xs sm:text-sm text-[#252525]" x-text="log.aktivitas"></td>

                                            <!-- RECORD TERKAIT -->
                                            <td class="py-4 px-6 whitespace-nowrap text-xs sm:text-sm font-bold text-[#252525]" x-text="log.record"></td>

                                            <!-- IP ADDRESS -->
                                            <td class="py-4 px-6 whitespace-nowrap">
                                                <span
                                                    class="inline-block px-2.5 py-1 rounded-md bg-[#F3F4F6] text-[#6B7280] text-[11px] sm:text-xs font-mono font-medium"
                                                    x-text="log.ip"
                                                ></span>
                                            </td>
                                        </tr>
                                    </template>

                                    <!-- Empty State -->
                                    <tr x-show="filteredLogs.length === 0" x-cloak>
                                        <td colspan="6" class="py-10 px-6 text-center text-xs sm:text-sm text-[#6B7280] bg-[#FFFFFF]">
                                            Tidak ada data log aktivitas yang sesuai dengan pencarian.
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

                </div>
            </main>

        </div>
    </div>
</body>
</html>

