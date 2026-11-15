<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#FFFFFF]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Manajemen Akun - Super Admin CampusVoice</title>

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

        /* Linear Gradient Konten Utama: Merah #B01116 ke Abu-abu Gelap #58595B (dari kiri bawah ke kanan atas) */
        .content-gradient-bg {
            background: linear-gradient(to top right, #B01116 0%, #58595B 100%);
        }
    </style>
</head>
<body
    class="h-full antialiased text-[#252525] bg-[#B01116]"
    x-data="manajemenAkunApp()"
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
                        <!-- Menu 1: Dashboard -->
                        <a
                            href="{{ route('superadmin.dashboard') }}"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#252525] hover:bg-gray-100 hover:text-[#B01116] transition group"
                        >
                            <svg class="w-5 h-5 text-[#252525] group-hover:text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="7" height="7" rx="1.5"/>
                                <rect x="14" y="3" width="7" height="7" rx="1.5"/>
                                <rect x="14" y="14" width="7" height="7" rx="1.5"/>
                                <rect x="3" y="14" width="7" height="7" rx="1.5"/>
                            </svg>
                            <span>Dashboard</span>
                        </a>

                        <!-- Menu 2: Manajemen Akun (AKTIF) -->
                        <a
                            href="{{ route('superadmin.akun') }}"
                            aria-current="page"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold bg-[#FCE8E8] text-[#B01116] transition"
                        >
                            <svg class="w-5 h-5 text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                            <span>Manajemen Akun</span>
                        </a>

                        <!-- Menu 3: Unit Penangan -->
                        <a
                            href="{{ route('superadmin.unit-penangan') }}"
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
                            href="{{ route('superadmin.kategori') }}"
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
                            href="{{ route('superadmin.log') }}"
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

                        <!-- Menu 6: Profil Saya -->
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
                    </nav>
                </div>

                <!-- Bagian Bawah: Keluar -->
                <div class="px-4 py-3 border-t border-[#E5E7EB] space-y-1">

                    @if (Route::has('logout'))
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
                    @else
                        <a
                            href="#"
                            class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#B01116] hover:bg-red-50 transition text-left"
                        >
                            <svg class="w-5 h-5 text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
            <div class="p-4 border-t border-[#E5E7EB]">
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
        <div x-show="mobileSidebarOpen" x-cloak @click="mobileSidebarOpen = false" class="fixed inset-0 z-40 bg-black/40 backdrop-blur-xs lg:hidden"></div>

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
                    <div class="relative w-52 sm:w-80 md:w-96">
                        <input
                            type="text"
                            x-model="searchHeader"
                            placeholder="Cari nomor tiket atau laporan.."
                            aria-label="Cari nomor tiket atau laporan"
                            class="w-full bg-[#F9FAFB] border border-[#E5E7EB] rounded-lg py-2 px-3.5 text-xs sm:text-sm text-[#252525] placeholder-[#9CA3AF] focus:bg-[#FFFFFF] focus:border-[#B01116] focus:outline-none focus:ring-1 focus:ring-[#B01116] transition"
                        />
                    </div>
                </div>

                <!-- Bagian Kanan: Lonceng Notifikasi & Profil Super Admin -->
                <div class="flex items-center gap-3 sm:gap-4">
                    <!-- Tombol Lonceng Notifikasi -->
                    <button
                        type="button"
                        class="relative p-2 rounded-lg border border-[#E5E7EB] text-[#252525] hover:text-[#B01116] hover:bg-gray-50 transition cursor-pointer"
                        title="Notifikasi"
                        aria-label="Notifikasi"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span class="absolute -top-1.5 -right-1.5 w-4 h-4 rounded-full bg-[#B01116] text-[#FFFFFF] text-[10px] font-bold flex items-center justify-center shadow-xs">
                            3
                        </span>
                    </button>

                    <!-- Profil Super Admin -->
                    <div class="flex items-center gap-2.5 pl-1">
                        <div class="w-9 h-9 rounded-lg bg-[#FCE8E8] text-[#B01116] font-bold text-xs flex items-center justify-center shrink-0">
                            {{ $authUser->initials ?? 'RP' }}
                        </div>
                        <div class="hidden sm:block text-left">
                            <p class="text-xs font-bold text-[#252525] leading-tight">{{ $authUser->name ?? 'Rizky Pratama' }}</p>
                            <p class="text-[11px] text-[#6B7280] leading-tight">{{ $authUser->role ?? 'Super Admin' }}</p>
                        </div>
                    </div>
                </div>
            </header>

            <!-- ========================================================= -->
            <!-- KONTEN UTAMA MANAJEMEN AKUN: Linear Gradient #B01116 ke #58595B -->
            <!-- ========================================================= -->
            <main class="flex-1 content-gradient-bg px-4 sm:px-8 lg:px-10 py-7">
                <div class="max-w-7xl mx-auto space-y-5">

                    <!-- Toast Notifikasi -->
                    <div
                        x-show="toastMessage"
                        x-cloak
                        x-transition
                        class="rounded-xl px-4 py-3 text-xs sm:text-sm font-semibold flex items-center justify-between border shadow-xs"
                        :class="toastType === 'error'
                            ? 'bg-[#FEF2F2] border-[#DC2626]/25 text-[#DC2626]'
                            : 'bg-[#ECFDF5] border-[#16A34A]/25 text-[#16A34A]'"
                    >
                        <span x-text="toastMessage"></span>
                        <button type="button" @click="toastMessage = ''" class="hover:opacity-75 cursor-pointer text-base leading-none">&times;</button>
                    </div>

                    @if (session('success'))
                        <div class="rounded-xl bg-[#ECFDF5] border border-[#16A34A]/25 text-[#16A34A] px-4 py-3 text-xs sm:text-sm font-semibold">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Baris Filter, Pencarian & Tombol Tambah Akun (Sejajar) -->
                    <div class="flex flex-wrap items-center justify-between gap-3 pt-1">
                        <div class="flex flex-wrap items-center gap-3 flex-1 min-w-0">
                            <!-- Input Pencarian -->
                            <div class="w-full sm:w-72">
                                <input
                                    type="text"
                                    x-model="searchQuery"
                                    placeholder="Cari nama, email, atau unit..."
                                    aria-label="Cari akun"
                                    class="w-full bg-[#FFFFFF] border border-[#E5E7EB] rounded-xl py-2.5 px-3.5 text-xs sm:text-sm text-[#252525] placeholder-[#9CA3AF] focus:border-[#B01116] focus:outline-none focus:ring-1 focus:ring-[#B01116] transition"
                                />
                            </div>

                            <!-- Filter Status -->
                            <div>
                                <select
                                    x-model="filterStatus"
                                    aria-label="Filter Status"
                                    class="bg-[#FFFFFF] border border-[#E5E7EB] rounded-xl py-2.5 pl-3.5 pr-9 text-xs sm:text-sm font-medium text-[#252525] focus:border-[#B01116] focus:outline-none focus:ring-1 focus:ring-[#B01116] transition cursor-pointer"
                                >
                                    <option value="">Semua Status</option>
                                    <option value="Aktif">Aktif</option>
                                    <option value="Nonaktif">Nonaktif</option>
                                </select>
                            </div>

                            <!-- Filter Peran -->
                            <div>
                                <select
                                    x-model="filterRole"
                                    aria-label="Filter Peran"
                                    class="bg-[#FFFFFF] border border-[#E5E7EB] rounded-xl py-2.5 pl-3.5 pr-9 text-xs sm:text-sm font-medium text-[#252525] focus:border-[#B01116] focus:outline-none focus:ring-1 focus:ring-[#B01116] transition cursor-pointer"
                                >
                                    <option value="">Semua Peran</option>
                                    <option value="Admin SSC">Admin SSC</option>
                                    <option value="Petugas Unit">Petugas Unit</option>
                                    <option value="Super Admin">Super Admin</option>
                                    <option value="Mahasiswa">Mahasiswa</option>
                                </select>
                            </div>

                            <!-- Tombol Hapus Filter -->
                            <div x-show="searchQuery || searchHeader || filterStatus || filterRole" x-cloak>
                                <button
                                    type="button"
                                    @click="resetFilters()"
                                    class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl bg-[#FFFFFF] hover:bg-gray-50 text-[#B01116] text-xs sm:text-sm font-semibold border border-[#E5E7EB] transition cursor-pointer"
                                >
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="18" y1="6" x2="6" y2="18"/>
                                        <line x1="6" y1="6" x2="18" y2="18"/>
                                    </svg>
                                    <span>Hapus Filter</span>
                                </button>
                            </div>
                        </div>

                        <!-- Tombol + Tambah Akun -->
                        <div class="shrink-0">
                            <button
                                type="button"
                                @click="openAddModal()"
                                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#FFFFFF] hover:bg-gray-50 text-[#B01116] text-xs sm:text-sm font-bold border border-[#E5E7EB] shadow-md transition cursor-pointer"
                            >
                                <span class="text-base leading-none font-bold">+</span>
                                <span>Tambah Akun</span>
                            </button>
                        </div>
                    </div>

                    <!-- Tabel Akun Staf -->
                    <section class="bg-[#FFFFFF] rounded-2xl border border-[#E5E7EB] shadow-xl overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-[#F9FAFB] border-b border-[#E5E7EB]">
                                        <th class="py-3.5 px-6 text-[11px] font-bold uppercase tracking-wider text-[#6B7280]">NAMA</th>
                                        <th class="py-3.5 px-4 text-[11px] font-bold uppercase tracking-wider text-[#6B7280]">EMAIL INSTITUSI</th>
                                        <th class="py-3.5 px-4 text-[11px] font-bold uppercase tracking-wider text-[#6B7280]">PERAN</th>
                                        <th class="py-3.5 px-4 text-[11px] font-bold uppercase tracking-wider text-[#6B7280]">UNIT PENUGASAN</th>
                                        <th class="py-3.5 px-4 text-[11px] font-bold uppercase tracking-wider text-[#6B7280]">STATUS</th>
                                        <th class="py-3.5 px-4 text-[11px] font-bold uppercase tracking-wider text-[#6B7280]">AKTIVITAS TERAKHIR</th>
                                        <th class="py-3.5 px-6 text-right text-[11px] font-bold uppercase tracking-wider text-[#6B7280]"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#F3F4F6]">
                                    <template x-for="akun in filteredAccounts" :key="akun.id">
                                        <tr class="hover:bg-[#F9FAFB]/70 transition">
                                            <td class="py-4 px-6 text-xs sm:text-[13px] font-bold text-[#252525] whitespace-nowrap" x-text="akun.name"></td>
                                            <td class="py-4 px-4 text-xs sm:text-[13px] text-[#6B7280] whitespace-nowrap" x-text="akun.email"></td>
                                            <td class="py-4 px-4 whitespace-nowrap">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-[#F1F5F9] text-[#334155] text-[11px] font-semibold">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-[#475569]"></span>
                                                    <span x-text="akun.role"></span>
                                                </span>
                                            </td>
                                            <td class="py-4 px-4 text-xs sm:text-[13px] text-[#4B5563]" x-text="akun.unit"></td>
                                            <td class="py-4 px-4 whitespace-nowrap">
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold"
                                                    :class="akun.status === 'Aktif'
                                                        ? 'bg-[#ECFDF5] text-[#16A34A]'
                                                        : 'bg-[#FEF2F2] text-[#DC2626]'"
                                                >
                                                    <span
                                                        class="w-1.5 h-1.5 rounded-full"
                                                        :class="akun.status === 'Aktif' ? 'bg-[#16A34A]' : 'bg-[#DC2626]'"
                                                    ></span>
                                                    <span x-text="akun.status"></span>
                                                </span>
                                            </td>
                                            <td class="py-4 px-4 text-xs sm:text-[13px] text-[#6B7280] whitespace-nowrap" x-text="akun.last_activity"></td>
                                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                                <button
                                                    type="button"
                                                    @click="openManageModal(akun)"
                                                    class="text-xs font-bold text-[#B01116] hover:text-[#8F0D12] inline-flex items-center gap-1 cursor-pointer transition"
                                                >
                                                    <span>Kelola</span>
                                                    <span aria-hidden="true">&rarr;</span>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>

                                    <tr x-show="filteredAccounts.length === 0" x-cloak>
                                        <td colspan="7" class="py-10 px-6 text-center text-xs sm:text-sm text-[#6B7280]">
                                            Tidak ada akun staf yang sesuai dengan filter pencarian.
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

    <!-- ========================================================= -->
    <!-- MODAL: TAMBAH AKUN STAF -->
    <!-- ========================================================= -->
    <div
        x-show="showAddModal"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/45 backdrop-blur-xs"
        @keydown.escape.window="showAddModal = false"
    >
        <div
            @click.outside="showAddModal = false"
            class="w-full max-w-lg bg-[#FFFFFF] rounded-2xl border border-[#E5E7EB] shadow-2xl overflow-hidden"
        >
            <div class="px-6 py-4 border-b border-[#E5E7EB] flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-[#252525]">Tambah Akun</h2>
                    <p class="text-xs text-[#6B7280] mt-0.5">Tambahkan akun Admin SSC, Petugas Unit, Super Admin, atau Mahasiswa baru.</p>
                </div>
                <button type="button" @click="showAddModal = false" class="text-[#6B7280] hover:text-[#252525] text-xl leading-none cursor-pointer">&times;</button>
            </div>

            <form @submit.prevent="submitAddAccount()" class="p-6 space-y-4">
                <div
                    x-show="formError"
                    x-cloak
                    class="rounded-xl bg-[#FEF2F2] border border-[#DC2626]/25 text-[#DC2626] px-3.5 py-2.5 text-xs font-semibold"
                    x-text="formError"
                ></div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-[#374151] mb-1">Nama Lengkap</label>
                        <input
                            type="text"
                            required
                            x-model="addForm.name"
                            placeholder="Contoh: Maya Prameswari"
                            class="w-full rounded-xl border border-[#E5E7EB] py-2 px-3 text-sm text-[#252525] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116]"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#374151] mb-1">Email Institusi</label>
                        <input
                            type="email"
                            required
                            x-model="addForm.email"
                            placeholder="nama@telkomuniversity.ac.id"
                            class="w-full rounded-xl border border-[#E5E7EB] py-2 px-3 text-sm text-[#252525] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116]"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#374151] mb-1" x-text="addForm.role === 'Mahasiswa' ? 'NIM' : 'NIP / ID Staf'"></label>
                        <input
                            type="text"
                            x-model="addForm.nip"
                            :placeholder="addForm.role === 'Mahasiswa' ? 'Contoh: 102062400113' : 'Contoh: 994004'"
                            class="w-full rounded-xl border border-[#E5E7EB] py-2 px-3 text-sm text-[#252525] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116]"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#374151] mb-1">Peran</label>
                        <select
                            required
                            x-model="addForm.role"
                            @change="addForm.unit = ''"
                            class="w-full rounded-xl border border-[#E5E7EB] py-2 px-3 text-sm text-[#252525] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116]"
                        >
                            <option value="Admin SSC">Admin SSC</option>
                            <option value="Petugas Unit">Petugas Unit</option>
                            <option value="Super Admin">Super Admin</option>
                            <option value="Mahasiswa">Mahasiswa</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#374151] mb-1">Status Akses</label>
                        <select
                            required
                            x-model="addForm.status_akun"
                            class="w-full rounded-xl border border-[#E5E7EB] py-2 px-3 text-sm text-[#252525] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116]"
                        >
                            <option value="Aktif">Aktif</option>
                            <option value="Nonaktif">Nonaktif</option>
                        </select>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-[#374151] mb-1" x-text="addForm.role === 'Mahasiswa' ? 'Program Studi' : 'Unit Penugasan'"></label>
                        <select
                            required
                            x-model="addForm.unit"
                            class="w-full rounded-xl border border-[#E5E7EB] py-2 px-3 text-sm text-[#252525] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116] cursor-pointer"
                        >
                            <option value="" disabled x-text="addForm.role === 'Mahasiswa' ? 'Pilih Program Studi' : 'Pilih Unit Penugasan'"></option>
                            <template x-for="opt in (addForm.role === 'Mahasiswa' ? prodiOptions : unitOptions)" :key="opt">
                                <option :value="opt" x-text="opt"></option>
                            </template>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#374151] mb-1">Nomor Telepon</label>
                        <input
                            type="text"
                            x-model="addForm.phone"
                            placeholder="08xxxxxxxxxx"
                            class="w-full rounded-xl border border-[#E5E7EB] py-2 px-3 text-sm text-[#252525] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116]"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#374151] mb-1">Password Awal <span class="font-normal text-[#6B7280]">(opsional)</span></label>
                        <input
                            type="password"
                            x-model="addForm.password"
                            placeholder="Default: 12345678"
                            class="w-full rounded-xl border border-[#E5E7EB] py-2 px-3 text-sm text-[#252525] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116]"
                        />
                    </div>
                </div>

                <div class="pt-3 border-t border-[#E5E7EB] flex items-center justify-end gap-2.5">
                    <button
                        type="button"
                        @click="showAddModal = false"
                        class="px-4 py-2 rounded-xl border border-[#E5E7EB] text-xs sm:text-sm font-semibold text-[#374151] hover:bg-gray-50 cursor-pointer"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        :disabled="isSubmitting"
                        class="px-4 py-2 rounded-xl bg-[#B01116] hover:bg-[#8F0D12] text-[#FFFFFF] text-xs sm:text-sm font-bold shadow-xs disabled:opacity-60 cursor-pointer transition"
                    >
                        <span x-text="isSubmitting ? 'Menyimpan...' : 'Simpan Akun'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================================= -->
    <!-- MODAL: KELOLA AKUN STAF -->
    <!-- ========================================================= -->
    <div
        x-show="showManageModal"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/45 backdrop-blur-xs"
        @keydown.escape.window="showManageModal = false"
    >
        <div
            @click.outside="showManageModal = false"
            class="w-full max-w-lg bg-[#FFFFFF] rounded-2xl border border-[#E5E7EB] shadow-2xl overflow-hidden"
        >
            <div class="px-6 py-4 border-b border-[#E5E7EB] flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-[#252525]">Kelola Akun Staf</h2>
                    <p class="text-xs text-[#6B7280] mt-0.5">Perbarui peran, unit penugasan, atau status akses akun.</p>
                </div>
                <button type="button" @click="showManageModal = false" class="text-[#6B7280] hover:text-[#252525] text-xl leading-none cursor-pointer">&times;</button>
            </div>

            <form @submit.prevent="submitUpdateAccount()" class="p-6 space-y-4">
                <div
                    x-show="formError"
                    x-cloak
                    class="rounded-xl bg-[#FEF2F2] border border-[#DC2626]/25 text-[#DC2626] px-3.5 py-2.5 text-xs font-semibold"
                    x-text="formError"
                ></div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-[#374151] mb-1">Nama Lengkap</label>
                        <input
                            type="text"
                            required
                            x-model="editForm.name"
                            class="w-full rounded-xl border border-[#E5E7EB] py-2 px-3 text-sm text-[#252525] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116]"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#374151] mb-1">Email Institusi</label>
                        <input
                            type="email"
                            required
                            x-model="editForm.email"
                            class="w-full rounded-xl border border-[#E5E7EB] py-2 px-3 text-sm text-[#252525] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116]"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#374151] mb-1" x-text="editForm.role === 'Mahasiswa' ? 'NIM' : 'NIP / ID Staf'"></label>
                        <input
                            type="text"
                            x-model="editForm.nip"
                            class="w-full rounded-xl border border-[#E5E7EB] py-2 px-3 text-sm text-[#252525] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116]"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#374151] mb-1">Peran</label>
                        <select
                            required
                            x-model="editForm.role"
                            class="w-full rounded-xl border border-[#E5E7EB] py-2 px-3 text-sm text-[#252525] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116]"
                        >
                            <option value="Admin SSC">Admin SSC</option>
                            <option value="Petugas Unit">Petugas Unit</option>
                            <option value="Super Admin">Super Admin</option>
                            <option value="Mahasiswa">Mahasiswa</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#374151] mb-1">Status Akses</label>
                        <select
                            required
                            x-model="editForm.status_akun"
                            class="w-full rounded-xl border border-[#E5E7EB] py-2 px-3 text-sm text-[#252525] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116]"
                        >
                            <option value="Aktif">Aktif</option>
                            <option value="Nonaktif">Nonaktif</option>
                        </select>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-[#374151] mb-1" x-text="editForm.role === 'Mahasiswa' ? 'Program Studi' : 'Unit Penugasan'"></label>
                        <select
                            required
                            x-model="editForm.unit"
                            class="w-full rounded-xl border border-[#E5E7EB] py-2 px-3 text-sm text-[#252525] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116] cursor-pointer"
                        >
                            <option value="" disabled x-text="editForm.role === 'Mahasiswa' ? 'Pilih Program Studi' : 'Pilih Unit Penugasan'"></option>
                            <template x-for="opt in (editForm.role === 'Mahasiswa' ? prodiOptions : unitOptions)" :key="opt">
                                <option :value="opt" x-text="opt" :selected="editForm.unit === opt"></option>
                            </template>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#374151] mb-1">Nomor Telepon</label>
                        <input
                            type="text"
                            x-model="editForm.phone"
                            class="w-full rounded-xl border border-[#E5E7EB] py-2 px-3 text-sm text-[#252525] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116]"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#374151] mb-1">Reset Password <span class="font-normal text-[#6B7280]">(opsional)</span></label>
                        <input
                            type="password"
                            x-model="editForm.password"
                            placeholder="Kosongkan jika tetap"
                            class="w-full rounded-xl border border-[#E5E7EB] py-2 px-3 text-sm text-[#252525] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116]"
                        />
                    </div>
                </div>

                <div class="pt-3 border-t border-[#E5E7EB] flex items-center justify-between gap-2.5">
                    <div>
                        <button
                            type="button"
                            x-show="!confirmDelete"
                            @click="confirmDelete = true"
                            class="px-3 py-2 rounded-xl text-xs font-bold text-[#DC2626] hover:bg-[#FEF2F2] cursor-pointer transition"
                        >
                            Hapus Akun
                        </button>
                        <div x-show="confirmDelete" x-cloak class="flex items-center gap-2">
                            <button
                                type="button"
                                @click="submitDeleteAccount()"
                                :disabled="isSubmitting"
                                class="px-3 py-1.5 rounded-lg bg-[#DC2626] text-white text-xs font-bold hover:bg-red-700 cursor-pointer"
                            >
                                Ya, Hapus
                            </button>
                            <button
                                type="button"
                                @click="confirmDelete = false"
                                class="px-2.5 py-1.5 rounded-lg border border-[#E5E7EB] text-xs font-semibold text-[#374151] cursor-pointer"
                            >
                                Batal
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center gap-2.5">
                        <button
                            type="button"
                            @click="showManageModal = false"
                            class="px-4 py-2 rounded-xl border border-[#E5E7EB] text-xs sm:text-sm font-semibold text-[#374151] hover:bg-gray-50 cursor-pointer"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            :disabled="isSubmitting"
                            class="px-4 py-2 rounded-xl bg-[#B01116] hover:bg-[#8F0D12] text-[#FFFFFF] text-xs sm:text-sm font-bold shadow-xs disabled:opacity-60 cursor-pointer transition"
                        >
                            <span x-text="isSubmitting ? 'Menyimpan...' : 'Simpan Perubahan'"></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function manajemenAkunApp() {
            return {
                unitOptions: [
                    'Student Service Center',
                    'Administrasi Sistem',
                    'Direktorat Pusat Teknologi Informasi',
                    'Direktorat Logistik dan Aset',
                    'Direktorat Akademik',
                    'Direktorat Kemahasiswaan',
                    'Direktorat Keuangan',
                    'Direktorat Sumber Daya Manusia',
                    'Direktorat Sekretariat & Perencanaan Strategis',
                    'Direktorat Bandung Techno Park',
                    'Direktorat Pasca Sarjana dan Advance Learning',
                    'Direktorat Pemasaran & Admisi',
                    'Direktorat Pengembangan Karir, Alumni, & Endowment',
                    'Direktorat Kerjasama Strategis & Kantor Urusan Internasional',
                    'Direktorat Penelitian dan Pengabdian Masyarakat',
                    'Satpras'
                ],
                prodiOptions: [
                    'S1 Sistem Informasi',
                    'S1 Teknologi Informasi',
                    'S1 Teknik Informatika',
                    'S1 Rekayasa Perangkat Lunak',
                    'S1 Sains Data',
                    'S1 Teknik Elektro',
                    'S1 Teknik Industri',
                    'S1 Teknik Komputer',
                    'S1 Teknik Logistik',
                    'S1 Bisnis Digital',
                    'S1 Teknik Telekomunikasi'
                ],
                mobileSidebarOpen: false,
                searchHeader: '',
                searchQuery: '',
                filterStatus: '',
                filterRole: '',
                accounts: @json($accounts),
                showAddModal: false,
                showManageModal: false,
                confirmDelete: false,
                isSubmitting: false,
                formError: '',
                toastMessage: '',
                toastType: 'success',
                addForm: {
                    name: '',
                    email: '',
                    nip: '',
                    role: 'Petugas Unit',
                    unit: '',
                    phone: '',
                    status_akun: 'Aktif',
                    password: ''
                },
                editForm: {
                    id: null,
                    name: '',
                    email: '',
                    nip: '',
                    role: 'Petugas Unit',
                    unit: '',
                    phone: '',
                    status_akun: 'Aktif',
                    password: ''
                },
                get filteredAccounts() {
                    const q = (this.searchQuery || this.searchHeader).trim().toLowerCase();
                    return this.accounts.filter(item => {
                        if (this.filterStatus && item.status !== this.filterStatus) return false;
                        if (this.filterRole && item.role !== this.filterRole) return false;
                        if (q) {
                            const hay = `${item.name} ${item.email} ${item.role} ${item.unit}`.toLowerCase();
                            if (!hay.includes(q)) return false;
                        }
                        return true;
                    });
                },
                csrfToken() {
                    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                },
                resetFilters() {
                    this.searchHeader = '';
                    this.searchQuery = '';
                    this.filterStatus = '';
                    this.filterRole = '';
                },
                notify(msg, type = 'success') {
                    this.toastMessage = msg;
                    this.toastType = type;
                    setTimeout(() => { this.toastMessage = ''; }, 3500);
                },
                openAddModal() {
                    this.formError = '';
                    this.addForm = {
                        name: '',
                        email: '',
                        nip: '',
                        role: 'Petugas Unit',
                        unit: '',
                        phone: '',
                        status_akun: 'Aktif',
                        password: ''
                    };
                    this.showAddModal = true;
                },
                openManageModal(akun) {
                    this.formError = '';
                    this.confirmDelete = false;
                    this.editForm = {
                        id: akun.id,
                        name: akun.name,
                        email: akun.email,
                        nip: akun.nip || '',
                        role: akun.role,
                        unit: akun.unit,
                        phone: akun.phone || '',
                        status_akun: akun.status,
                        password: ''
                    };
                    this.showManageModal = true;
                },
                async submitAddAccount() {
                    this.isSubmitting = true;
                    this.formError = '';
                    try {
                        const res = await fetch('{{ route("superadmin.akun.store") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': this.csrfToken()
                            },
                            body: JSON.stringify(this.addForm)
                        });
                        const data = await res.json();
                        if (!res.ok) {
                            const firstError = data.errors ? Object.values(data.errors)[0][0] : null;
                            this.formError = firstError || data.message || 'Gagal menambahkan akun staf.';
                            return;
                        }
                        this.accounts.push(data.account);
                        this.showAddModal = false;
                        this.notify(data.message || 'Akun staf berhasil ditambahkan.');
                    } catch (e) {
                        this.formError = 'Terjadi kesalahan jaringan saat menyimpan akun.';
                    } finally {
                        this.isSubmitting = false;
                    }
                },
                async submitUpdateAccount() {
                    this.isSubmitting = true;
                    this.formError = '';
                    try {
                        const res = await fetch(`/super-admin/manajemen-akun/${this.editForm.id}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': this.csrfToken()
                            },
                            body: JSON.stringify(this.editForm)
                        });
                        const data = await res.json();
                        if (!res.ok) {
                            const firstError = data.errors ? Object.values(data.errors)[0][0] : null;
                            this.formError = firstError || data.message || 'Gagal memperbarui akun staf.';
                            return;
                        }
                        const idx = this.accounts.findIndex(a => a.id === this.editForm.id);
                        if (idx !== -1) {
                            this.accounts.splice(idx, 1, data.account);
                        }
                        this.showManageModal = false;
                        this.notify(data.message || 'Perubahan akun berhasil disimpan.');
                    } catch (e) {
                        this.formError = 'Terjadi kesalahan jaringan saat memperbarui akun.';
                    } finally {
                        this.isSubmitting = false;
                    }
                },
                async submitDeleteAccount() {
                    this.isSubmitting = true;
                    this.formError = '';
                    try {
                        const res = await fetch(`/super-admin/manajemen-akun/${this.editForm.id}`, {
                            method: 'DELETE',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': this.csrfToken()
                            }
                        });
                        const data = await res.json();
                        if (!res.ok) {
                            this.formError = data.message || 'Gagal menghapus akun staf.';
                            return;
                        }
                        this.accounts = this.accounts.filter(a => a.id !== this.editForm.id);
                        this.showManageModal = false;
                        this.notify(data.message || 'Akun staf berhasil dihapus.');
                    } catch (e) {
                        this.formError = 'Terjadi kesalahan jaringan saat menghapus akun.';
                    } finally {
                        this.isSubmitting = false;
                    }
                }
            };
        }
    </script>
</body>
</html>

