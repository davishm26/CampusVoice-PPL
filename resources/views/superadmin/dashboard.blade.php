<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#FFFFFF]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Dashboard Super Admin - CampusVoice</title>

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

        /* Linear Gradient Konten Utama: Abu-abu Gelap #58595B (kiri bawah) ke Merah #B01116 (kanan atas) */
        .content-gradient-bg {
            background: linear-gradient(to top right, #58595B 0%, #B01116 100%);
        }
    </style>
</head>
<body
    class="h-full antialiased text-[#252525] bg-[#B01116]"
    x-data="superAdminDashboardApp()"
>
    <div class="min-h-screen flex flex-col lg:flex-row">

        <!-- ========================================================= -->
        <!-- SIDEBAR KIRI (SUPER ADMIN) -->
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

                <!-- Navigasi Menu Super Admin -->
                <div class="px-4 py-3 flex-1">
                    <p class="px-3 text-[11px] font-bold text-[#252525] tracking-wide mb-2.5">Super Admin</p>

                    <nav class="space-y-1">
                        <!-- Menu 1: Dashboard (AKTIF) -->
                        <a
                            href="{{ route('superadmin.dashboard') }}"
                            aria-current="page"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold bg-[#FCE8E8] text-[#B01116] transition"
                        >
                            <svg class="w-5 h-5 text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="7" height="7" rx="1.5"/>
                                <rect x="14" y="3" width="7" height="7" rx="1.5"/>
                                <rect x="14" y="14" width="7" height="7" rx="1.5"/>
                                <rect x="3" y="14" width="7" height="7" rx="1.5"/>
                            </svg>
                            <span>Dashboard</span>
                        </a>

                        <!-- Menu 2: Manajemen Akun -->
                        <a
                            href="#"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#252525] hover:bg-gray-100 hover:text-[#B01116] transition group"
                        >
                            <svg class="w-5 h-5 text-[#252525] group-hover:text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
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
                            <svg class="w-5 h-5 text-[#252525] group-hover:text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
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
                            <svg class="w-5 h-5 text-[#252525] group-hover:text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"/>
                                <polyline points="12 5 19 12 12 19"/>
                            </svg>
                            <span>Kategori &amp; Penyaluran</span>
                        </a>

                        <!-- Menu 5: Log Aktivitas -->
                        <a
                            href="{{ Route::has('superadmin.log') ? route('superadmin.log') : '#' }}"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#252525] hover:bg-gray-100 hover:text-[#B01116] transition group"
                        >
                            <svg class="w-5 h-5 text-[#252525] group-hover:text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
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
                        <svg class="w-5 h-5 text-[#252525] group-hover:text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        <span>Profil Saya</span>
                    </a>

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
                            x-model="searchQuery"
                            placeholder="Cari nomor tiket atau laporan..."
                            aria-label="Cari nomor tiket atau laporan"
                            class="w-full bg-[#F9FAFB] border border-[#E5E7EB] rounded-lg py-2 px-3.5 text-xs sm:text-sm text-[#252525] placeholder-[#6B7280] focus:bg-[#FFFFFF] focus:border-[#B01116] focus:outline-none focus:ring-1 focus:ring-[#B01116] transition"
                        />
                    </div>
                </div>

                <!-- Bagian Kanan: Lonceng Notifikasi & Profil Super Admin -->
                <div class="flex items-center gap-3 sm:gap-4">
                    <!-- Tombol Lonceng Notifikasi -->
                    <button
                        type="button"
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
                    </button>

                    <!-- Profil Super Admin: Rizky Pratama -->
                    <div class="flex items-center gap-2.5">
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
            <!-- AREA KONTEN UTAMA: Linear Gradient #58595B ke #B01116 -->
            <!-- ========================================================= -->
            <main class="flex-1 content-gradient-bg px-4 sm:px-8 lg:px-10 py-8 text-[#FFFFFF]">
                <div class="max-w-7xl mx-auto space-y-5">

                    <!-- Header Halaman (Atas) -->
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-[#FFFFFF]/80">
                            SUPER ADMIN
                        </p>
                        <h1 class="text-2xl sm:text-[28px] font-bold text-[#FFFFFF] tracking-tight leading-tight mt-1">
                            Administrasi Sistem
                        </h1>
                        <p class="text-xs sm:text-sm text-[#FFFFFF]/85 mt-1 font-normal">
                            Kelola akses, data master, aturan penyaluran, dan audit aktivitas CampusVoice.
                        </p>
                    </div>

                    <!-- Grid Kartu Metrik Ringkasan Atas (5 Kartu Putih) -->
                    <section aria-label="Ringkasan Metrik Sistem" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
                        <!-- 1. Total Pengguna -->
                        <div class="bg-[#FFFFFF] rounded-2xl border border-[#E5E7EB] p-5 shadow-sm flex items-center gap-4">
                            <div class="w-11 h-11 rounded-xl bg-[#FCE8E8] text-[#B01116] flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-medium text-[#6B7280] truncate">Total Pengguna</p>
                                <p class="text-2xl font-extrabold text-[#252525] tracking-tight mt-0.5">5.842</p>
                            </div>
                        </div>

                        <!-- 2. Admin SSC Aktif -->
                        <div class="bg-[#FFFFFF] rounded-2xl border border-[#E5E7EB] p-5 shadow-sm flex items-center gap-4">
                            <div class="w-11 h-11 rounded-xl bg-[#EFF6FF] text-[#2563EB] flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                    <path d="m9 12 2 2 4-4"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-medium text-[#6B7280] truncate">Admin SSC Aktif</p>
                                <p class="text-2xl font-extrabold text-[#252525] tracking-tight mt-0.5">8</p>
                            </div>
                        </div>

                        <!-- 3. Petugas Unit Aktif -->
                        <div class="bg-[#FFFFFF] rounded-2xl border border-[#E5E7EB] p-5 shadow-sm flex items-center gap-4">
                            <div class="w-11 h-11 rounded-xl bg-[#F5F3FF] text-[#7C3AED] flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="18" r="2.5"/>
                                    <circle cx="6" cy="6" r="2.5"/>
                                    <circle cx="18" cy="6" r="2.5"/>
                                    <path d="M18 8.5V10a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V8.5"/>
                                    <path d="M12 12v3.5"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-medium text-[#6B7280] truncate">Petugas Unit Aktif</p>
                                <p class="text-2xl font-extrabold text-[#252525] tracking-tight mt-0.5">67</p>
                            </div>
                        </div>

                        <!-- 4. Unit Penangan -->
                        <div class="bg-[#FFFFFF] rounded-2xl border border-[#E5E7EB] p-5 shadow-sm flex items-center gap-4">
                            <div class="w-11 h-11 rounded-xl bg-[#FFF7ED] text-[#EA580C] flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="3" width="7" height="7" rx="1"/>
                                    <rect x="14" y="3" width="7" height="7" rx="1"/>
                                    <rect x="14" y="14" width="7" height="7" rx="1"/>
                                    <rect x="3" y="14" width="7" height="7" rx="1"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-medium text-[#6B7280] truncate">Unit Penangan</p>
                                <p class="text-2xl font-extrabold text-[#252525] tracking-tight mt-0.5">24</p>
                            </div>
                        </div>

                        <!-- 5. Kategori Aktif -->
                        <div class="bg-[#FFFFFF] rounded-2xl border border-[#E5E7EB] p-5 shadow-sm flex items-center gap-4">
                            <div class="w-11 h-11 rounded-xl bg-[#ECFDF5] text-[#16A34A] flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <polyline points="14 2 14 8 20 8"/>
                                    <line x1="16" y1="13" x2="8" y2="13"/>
                                    <line x1="16" y1="17" x2="8" y2="17"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-medium text-[#6B7280] truncate">Kategori Aktif</p>
                                <p class="text-2xl font-extrabold text-[#252525] tracking-tight mt-0.5">10</p>
                            </div>
                        </div>
                    </section>

                    <!-- Grid 4 Kartu Shortcut Navigasi Cepat -->
                    <section aria-label="Shortcut Navigasi Cepat" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
                        <!-- Shortcut 1: Manajemen Akun -->
                        <a
                            href="#"
                            class="group bg-[#FFFFFF] rounded-2xl border border-[#E5E7EB] p-5 shadow-sm hover:border-[#B01116]/40 hover:shadow-md transition flex items-center justify-between gap-3"
                        >
                            <div class="flex items-start gap-3.5 min-w-0">
                                <div class="w-10 h-10 rounded-xl bg-[#FCE8E8] text-[#B01116] flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                        <circle cx="12" cy="7" r="4"/>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <h2 class="text-sm font-bold text-[#252525] group-hover:text-[#B01116] transition">
                                        Manajemen Akun
                                    </h2>
                                    <p class="text-xs text-[#6B7280] mt-1 leading-relaxed">
                                        Kelola staf, peran, unit, dan status akun.
                                    </p>
                                </div>
                            </div>
                            <span class="text-base font-semibold text-[#6B7280] group-hover:text-[#B01116] group-hover:translate-x-0.5 transition shrink-0">
                                &rarr;
                            </span>
                        </a>

                        <!-- Shortcut 2: Unit Penangan -->
                        <a
                            href="#"
                            class="group bg-[#FFFFFF] rounded-2xl border border-[#E5E7EB] p-5 shadow-sm hover:border-[#B01116]/40 hover:shadow-md transition flex items-center justify-between gap-3"
                        >
                            <div class="flex items-start gap-3.5 min-w-0">
                                <div class="w-10 h-10 rounded-xl bg-[#FCE8E8] text-[#B01116] flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="18" r="2.5"/>
                                        <circle cx="6" cy="6" r="2.5"/>
                                        <circle cx="18" cy="6" r="2.5"/>
                                        <path d="M18 8.5V10a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V8.5"/>
                                        <path d="M12 12v3.5"/>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <h2 class="text-sm font-bold text-[#252525] group-hover:text-[#B01116] transition">
                                        Unit Penangan
                                    </h2>
                                    <p class="text-xs text-[#6B7280] mt-1 leading-relaxed">
                                        13 direktorat dan 11 program studi.
                                    </p>
                                </div>
                            </div>
                            <span class="text-base font-semibold text-[#6B7280] group-hover:text-[#B01116] group-hover:translate-x-0.5 transition shrink-0">
                                &rarr;
                            </span>
                        </a>

                        <!-- Shortcut 3: Aturan Penyaluran -->
                        <a
                            href="#"
                            class="group bg-[#FFFFFF] rounded-2xl border border-[#E5E7EB] p-5 shadow-sm hover:border-[#B01116]/40 hover:shadow-md transition flex items-center justify-between gap-3"
                        >
                            <div class="flex items-start gap-3.5 min-w-0">
                                <div class="w-10 h-10 rounded-xl bg-[#FCE8E8] text-[#B01116] flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="5" y1="12" x2="19" y2="12"/>
                                        <polyline points="12 5 19 12 12 19"/>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <h2 class="text-sm font-bold text-[#252525] group-hover:text-[#B01116] transition">
                                        Aturan Penyaluran
                                    </h2>
                                    <p class="text-xs text-[#6B7280] mt-1 leading-relaxed">
                                        Atur rekomendasi tujuan per kategori.
                                    </p>
                                </div>
                            </div>
                            <span class="text-base font-semibold text-[#6B7280] group-hover:text-[#B01116] group-hover:translate-x-0.5 transition shrink-0">
                                &rarr;
                            </span>
                        </a>

                        <!-- Shortcut 4: Log Aktivitas -->
                        <a
                            href="{{ Route::has('superadmin.log') ? route('superadmin.log') : '#' }}"
                            class="group bg-[#FFFFFF] rounded-2xl border border-[#E5E7EB] p-5 shadow-sm hover:border-[#B01116]/40 hover:shadow-md transition flex items-center justify-between gap-3"
                        >
                            <div class="flex items-start gap-3.5 min-w-0">
                                <div class="w-10 h-10 rounded-xl bg-[#FCE8E8] text-[#B01116] flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                        <polyline points="14 2 14 8 20 8"/>
                                        <line x1="16" y1="13" x2="8" y2="13"/>
                                        <line x1="16" y1="17" x2="8" y2="17"/>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <h2 class="text-sm font-bold text-[#252525] group-hover:text-[#B01116] transition">
                                        Log Aktivitas
                                    </h2>
                                    <p class="text-xs text-[#6B7280] mt-1 leading-relaxed">
                                        Audit aktivitas penting sistem.
                                    </p>
                                </div>
                            </div>
                            <span class="text-base font-semibold text-[#6B7280] group-hover:text-[#B01116] group-hover:translate-x-0.5 transition shrink-0">
                                &rarr;
                            </span>
                        </a>
                    </section>

                    <!-- Tabel "Aktivitas Sistem Terbaru" (Kartu Putih Besar di Bawah) -->
                    <section class="bg-[#FFFFFF] rounded-2xl border border-[#E5E7EB] p-5 sm:p-6 shadow-xl">
                        <!-- Header Kartu -->
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
                            <div>
                                <h2 class="text-base sm:text-lg font-bold text-[#252525] tracking-tight">
                                    Aktivitas Sistem Terbaru
                                </h2>
                                <p class="text-xs text-[#6B7280] mt-0.5">
                                    Audit konfigurasi dan akses pengguna.
                                </p>
                            </div>

                            <a
                                href="{{ Route::has('superadmin.log') ? route('superadmin.log') : '#' }}"
                                class="inline-flex items-center gap-1 text-xs sm:text-sm font-bold text-[#B01116] hover:text-[#8A0D12] transition self-start sm:self-auto"
                            >
                                <span>Lihat semua &rarr;</span>
                            </a>
                        </div>

                        <!-- Tabel Aktivitas -->
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[760px] text-left border-collapse">
                                <thead>
                                    <tr class="bg-[#F9FAFB] border-y border-[#E5E7EB]">
                                        <th scope="col" class="py-3.5 px-4 text-[11px] font-bold uppercase tracking-wider text-[#6B7280]">
                                            TIMESTAMP
                                        </th>
                                        <th scope="col" class="py-3.5 px-4 text-[11px] font-bold uppercase tracking-wider text-[#6B7280]">
                                            PENGGUNA
                                        </th>
                                        <th scope="col" class="py-3.5 px-4 text-[11px] font-bold uppercase tracking-wider text-[#6B7280]">
                                            PERAN
                                        </th>
                                        <th scope="col" class="py-3.5 px-4 text-[11px] font-bold uppercase tracking-wider text-[#6B7280]">
                                            AKTIVITAS
                                        </th>
                                        <th scope="col" class="py-3.5 px-4 text-[11px] font-bold uppercase tracking-wider text-[#6B7280]">
                                            RECORD TERKAIT
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#E5E7EB]">
                                    <template x-for="item in filteredActivities" :key="item.id">
                                        <tr class="hover:bg-[#F9FAFB]/70 transition">
                                            <!-- TIMESTAMP -->
                                            <td class="py-4 px-4 text-xs sm:text-sm text-[#6B7280] whitespace-nowrap" x-text="item.timestamp"></td>

                                            <!-- PENGGUNA -->
                                            <td class="py-4 px-4 text-xs sm:text-sm font-bold text-[#252525] whitespace-nowrap" x-text="item.pengguna"></td>

                                            <!-- PERAN -->
                                            <td class="py-4 px-4 whitespace-nowrap">
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold"
                                                    :class="{
                                                        'bg-[#EEF2FF] text-[#252525]': item.peran === 'Admin SSC',
                                                        'bg-[#F3F4F6] text-[#252525]': item.peran === 'Petugas Unit',
                                                        'bg-[#EFF6FF] text-[#252525]': item.peran === 'Super Admin'
                                                    }"
                                                >
                                                    <span
                                                        class="w-1.5 h-1.5 rounded-full"
                                                        :class="{
                                                            'bg-[#6366F1]': item.peran === 'Admin SSC',
                                                            'bg-[#64748B]': item.peran === 'Petugas Unit',
                                                            'bg-[#2563EB]': item.peran === 'Super Admin'
                                                        }"
                                                    ></span>
                                                    <span x-text="item.peran"></span>
                                                </span>
                                            </td>

                                            <!-- AKTIVITAS -->
                                            <td class="py-4 px-4 text-xs sm:text-sm text-[#6B7280] whitespace-nowrap" x-text="item.aktivitas"></td>

                                            <!-- RECORD TERKAIT -->
                                            <td class="py-4 px-4 text-xs sm:text-sm font-bold text-[#252525] whitespace-nowrap" x-text="item.record"></td>
                                        </tr>
                                    </template>

                                    <!-- Empty State -->
                                    <tr x-show="filteredActivities.length === 0" x-cloak>
                                        <td colspan="5" class="py-10 px-4 text-center text-xs sm:text-sm text-[#6B7280]">
                                            Tidak ada aktivitas yang cocok dengan pencarian Anda.
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

    <script>
        function superAdminDashboardApp() {
            return {
                mobileSidebarOpen: false,
                searchQuery: '',

                activities: [
                    {
                        id: 1,
                        timestamp: '19 Okt 2026, 10.42',
                        pengguna: 'Siti Rahmawati',
                        peran: 'Admin SSC',
                        aktivitas: 'Penyaluran laporan',
                        record: 'CV-2026-00132'
                    },
                    {
                        id: 2,
                        timestamp: '19 Okt 2026, 10.31',
                        pengguna: 'Budi Santoso',
                        peran: 'Petugas Unit',
                        aktivitas: 'Pembaruan progres',
                        record: 'CV-2026-00124'
                    },
                    {
                        id: 3,
                        timestamp: '19 Okt 2026, 09.18',
                        pengguna: 'Rizky Pratama',
                        peran: 'Super Admin',
                        aktivitas: 'Perubahan kategori',
                        record: 'Fasilitas dan Sarana'
                    }
                ],

                get filteredActivities() {
                    const q = this.searchQuery.trim().toLowerCase();
                    if (!q) return this.activities;
                    return this.activities.filter((item) =>
                        item.pengguna.toLowerCase().includes(q)
                        || item.peran.toLowerCase().includes(q)
                        || item.aktivitas.toLowerCase().includes(q)
                        || item.record.toLowerCase().includes(q)
                    );
                }
            };
        }
    </script>
</body>
</html>

