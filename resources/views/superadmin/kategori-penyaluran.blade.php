<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#FFFFFF]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Kategori &amp; Aturan Penyaluran - Super Admin CampusVoice</title>

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
    x-data="kategoriPenyaluranApp()"
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
                            <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="7" height="7" rx="1.5"/>
                                <rect x="14" y="3" width="7" height="7" rx="1.5"/>
                                <rect x="14" y="14" width="7" height="7" rx="1.5"/>
                                <rect x="3" y="14" width="7" height="7" rx="1.5"/>
                            </svg>
                            <span>Dashboard</span>
                        </a>

                        <!-- Menu 2: Manajemen Akun -->
                        <a
                            href="{{ route('superadmin.akun') }}"
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
                            href="{{ route('superadmin.unit-penangan') }}"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#252525] hover:bg-gray-100 hover:text-[#B01116] transition group"
                        >
                            <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="18" r="3"/>
                                <circle cx="6" cy="6" r="3"/>
                                <circle cx="18" cy="6" r="3"/>
                                <path d="M18 9v1a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V9"/>
                                <path d="M12 12v3"/>
                            </svg>
                            <span>Unit Penangan</span>
                        </a>

                        <!-- Menu 4: Kategori & Penyaluran (AKTIF) -->
                        <a
                            href="{{ route('superadmin.kategori') }}"
                            aria-current="page"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold bg-[#FCE8E8] text-[#B01116] transition shadow-xs"
                        >
                            <svg class="w-5 h-5 text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
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
                            <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
            <header class="bg-[#FFFFFF] border-b border-[#E5E7EB] px-4 sm:px-8 py-3.5 flex items-center justify-between sticky top-0 z-30 shadow-xs">
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
            <!-- AREA KONTEN UTAMA: Linear Gradient #B01116 ke #58595B -->
            <!-- ========================================================= -->
            <main class="flex-1 content-gradient-bg px-4 sm:px-8 lg:px-10 py-7 text-[#FFFFFF]">
                <div class="max-w-7xl mx-auto space-y-5">

                    <!-- Toast Notifikasi -->
                    <div
                        x-show="toastMessage"
                        x-cloak
                        x-transition
                        class="rounded-xl px-4 py-3 text-xs sm:text-sm font-semibold flex items-center justify-between border shadow-md"
                        :class="toastType === 'error'
                            ? 'bg-[#FEF2F2] border-[#DC2626]/25 text-[#DC2626]'
                            : 'bg-[#ECFDF5] border-[#16A34A]/25 text-[#16A34A]'"
                    >
                        <span x-text="toastMessage"></span>
                        <button type="button" @click="toastMessage = ''" class="hover:opacity-75 cursor-pointer text-base leading-none">&times;</button>
                    </div>

                    <!-- Header Halaman (Atas) -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h1 class="text-2xl sm:text-[28px] font-bold text-[#FFFFFF] tracking-tight leading-tight">
                                Kategori dan Aturan Penyaluran
                            </h1>
                            <p class="text-xs sm:text-sm text-[#FFFFFF]/85 mt-1 font-normal">
                                Konfigurasi rekomendasi awal untuk membantu Admin SSC menentukan unit tujuan.
                            </p>
                        </div>

                        <div class="shrink-0">
                            <button
                                type="button"
                                @click="openAddModal()"
                                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#FFFFFF] hover:bg-gray-50 text-[#B01116] text-xs sm:text-sm font-bold shadow-sm transition cursor-pointer"
                            >
                                <span class="text-base leading-none font-bold">+</span>
                                <span>Tambah Aturan</span>
                            </button>
                        </div>
                    </div>

                    <!-- Banner Informasi Rekomendasi -->
                    <div class="bg-[#FFFFFF] rounded-2xl border border-[#E5E7EB] px-5 py-3.5 shadow-sm flex items-center gap-3 text-[#252525]">
                        <div class="w-7 h-7 rounded-lg bg-[#FCE8E8] text-[#B01116] flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                <path d="M9 12l2 2 4-4"/>
                            </svg>
                        </div>
                        <p class="text-xs sm:text-[13px] text-[#252525] leading-relaxed">
                            Aturan ini adalah <strong class="font-bold text-[#252525]">rekomendasi</strong>. Admin SSC tetap bertanggung jawab memverifikasi dan memilih tujuan akhir.
                        </p>
                    </div>

                    <!-- KARTU PUTIH BESAR: TABEL KATEGORI & ATURAN PENYALURAN -->
                    <section class="bg-[#FFFFFF] rounded-2xl border border-[#E5E7EB] shadow-xl overflow-hidden text-[#252525]">
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[780px] text-left border-collapse">
                                <thead class="bg-[#F9FAFB] border-b border-[#E5E7EB]">
                                    <tr>
                                        <th scope="col" class="py-3.5 px-6 text-[11px] font-bold uppercase tracking-wider text-[#6B7280]">
                                            KATEGORI / KATA KUNCI
                                        </th>
                                        <th scope="col" class="py-3.5 px-5 text-[11px] font-bold uppercase tracking-wider text-[#6B7280]">
                                            TIPE TUJUAN
                                        </th>
                                        <th scope="col" class="py-3.5 px-5 text-[11px] font-bold uppercase tracking-wider text-[#6B7280]">
                                            REKOMENDASI DEFAULT
                                        </th>
                                        <th scope="col" class="py-3.5 px-5 text-[11px] font-bold uppercase tracking-wider text-[#6B7280]">
                                            STATUS
                                        </th>
                                        <th scope="col" class="py-3.5 px-6 text-right">
                                            <span class="sr-only">Aksi</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#E5E7EB]">
                                    <template x-for="rule in filteredRules" :key="rule.id">
                                        <tr class="hover:bg-[#F9FAFB]/80 transition">
                                            <!-- Kategori / Kata Kunci -->
                                            <td class="py-4 px-6 text-xs sm:text-[13px] font-bold text-[#252525]" x-text="rule.kategori_kata_kunci"></td>

                                            <!-- Tipe Tujuan -->
                                            <td class="py-4 px-5 text-xs sm:text-[13px] text-[#6B7280] whitespace-nowrap" x-text="rule.tipe_tujuan"></td>

                                            <!-- Rekomendasi Default -->
                                            <td class="py-4 px-5 text-xs sm:text-[13px] text-[#6B7280]" x-text="rule.rekomendasi_default"></td>

                                            <!-- Status -->
                                            <td class="py-4 px-5 whitespace-nowrap">
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold"
                                                    :class="rule.status === 'Aktif'
                                                        ? 'bg-[#ECFDF5] text-[#16A34A]'
                                                        : 'bg-gray-100 text-[#6B7280]'"
                                                >
                                                    <span
                                                        class="w-1.5 h-1.5 rounded-full"
                                                        :class="rule.status === 'Aktif' ? 'bg-[#16A34A]' : 'bg-[#6B7280]'"
                                                    ></span>
                                                    <span x-text="rule.status"></span>
                                                </span>
                                            </td>

                                            <!-- Aksi: Edit -> -->
                                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                                <button
                                                    type="button"
                                                    @click="openEditModal(rule)"
                                                    class="inline-flex items-center gap-1 text-xs sm:text-[13px] font-bold text-[#B01116] hover:text-[#8A0D12] transition cursor-pointer"
                                                >
                                                    <span>Edit &rarr;</span>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>

                                    <!-- Empty state -->
                                    <tr x-show="filteredRules.length === 0" x-cloak>
                                        <td colspan="5" class="py-12 px-6 text-center text-sm text-[#6B7280]">
                                            Belum ada aturan penyaluran yang sesuai dengan pencarian.
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
    <!-- MODAL TAMBAH ATURAN PENYALURAN -->
    <!-- ========================================================= -->
    <div
        x-show="showAddModal"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs"
        @keydown.escape.window="showAddModal = false"
    >
        <div
            @click.away="showAddModal = false"
            class="bg-[#FFFFFF] rounded-2xl border border-[#E5E7EB] shadow-2xl w-full max-w-lg overflow-hidden text-[#252525]"
        >
            <div class="px-6 py-4 border-b border-[#E5E7EB] flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-[#252525]">Tambah Aturan Penyaluran</h3>
                    <p class="text-xs text-[#6B7280] mt-0.5">Tetapkan rekomendasi unit tujuan berdasarkan kategori atau kata kunci.</p>
                </div>
                <button type="button" @click="showAddModal = false" class="text-[#6B7280] hover:text-[#252525] text-xl leading-none cursor-pointer">&times;</button>
            </div>

            <form @submit.prevent="submitAddRule()" class="p-6 space-y-4">
                <div x-show="formError" x-cloak class="rounded-xl bg-[#FEF2F2] border border-[#DC2626]/25 text-[#DC2626] px-3.5 py-2.5 text-xs font-semibold" x-text="formError"></div>

                <div>
                    <label class="block text-xs font-bold text-[#252525] mb-1.5">Kategori / Kata Kunci <span class="text-[#B01116]">*</span></label>
                    <input
                        type="text"
                        x-model="addForm.kategori_kata_kunci"
                        required
                        placeholder="Contoh: Transkrip nilai, nilai, KRS"
                        class="w-full rounded-xl border border-[#E5E7EB] px-3.5 py-2.5 text-sm text-[#252525] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116] focus:outline-none"
                    />
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#252525] mb-1.5">Tipe Tujuan <span class="text-[#B01116]">*</span></label>
                    <select
                        x-model="addForm.tipe_tujuan"
                        @change="onTipeChange(addForm)"
                        class="w-full rounded-xl border border-[#E5E7EB] px-3.5 py-2.5 text-sm text-[#252525] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116] focus:outline-none"
                    >
                        <option value="Program Studi">Program Studi</option>
                        <option value="Direktorat">Direktorat</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#252525] mb-1.5">Rekomendasi Default <span class="text-[#B01116]">*</span></label>
                    <input
                        type="text"
                        list="unitOptionsList"
                        x-model="addForm.rekomendasi_default"
                        required
                        placeholder="Pilih atau ketik unit tujuan..."
                        class="w-full rounded-xl border border-[#E5E7EB] px-3.5 py-2.5 text-sm text-[#252525] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116] focus:outline-none"
                    />
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#252525] mb-1.5">Status <span class="text-[#B01116]">*</span></label>
                    <select
                        x-model="addForm.status"
                        class="w-full rounded-xl border border-[#E5E7EB] px-3.5 py-2.5 text-sm text-[#252525] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116] focus:outline-none"
                    >
                        <option value="Aktif">Aktif</option>
                        <option value="Nonaktif">Nonaktif</option>
                    </select>
                </div>

                <div class="pt-3 border-t border-[#E5E7EB] flex items-center justify-end gap-2.5">
                    <button
                        type="button"
                        @click="showAddModal = false"
                        class="px-4 py-2 rounded-xl border border-[#E5E7EB] text-xs sm:text-sm font-semibold text-[#6B7280] hover:bg-gray-50 transition cursor-pointer"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        :disabled="isSubmitting"
                        class="px-5 py-2 rounded-xl bg-[#B01116] hover:bg-[#8A0D12] disabled:opacity-60 text-white text-xs sm:text-sm font-bold shadow-xs transition cursor-pointer"
                    >
                        <span x-text="isSubmitting ? 'Menyimpan...' : 'Simpan Aturan'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================================= -->
    <!-- MODAL EDIT ATURAN PENYALURAN -->
    <!-- ========================================================= -->
    <div
        x-show="showEditModal"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs"
        @keydown.escape.window="showEditModal = false"
    >
        <div
            @click.away="showEditModal = false"
            class="bg-[#FFFFFF] rounded-2xl border border-[#E5E7EB] shadow-2xl w-full max-w-lg overflow-hidden text-[#252525]"
        >
            <div class="px-6 py-4 border-b border-[#E5E7EB] flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-[#252525]">Edit Aturan Penyaluran</h3>
                    <p class="text-xs text-[#6B7280] mt-0.5">Perbarui kata kunci, tipe tujuan, atau rekomendasi default.</p>
                </div>
                <button type="button" @click="showEditModal = false" class="text-[#6B7280] hover:text-[#252525] text-xl leading-none cursor-pointer">&times;</button>
            </div>

            <form @submit.prevent="submitEditRule()" class="p-6 space-y-4">
                <div x-show="formError" x-cloak class="rounded-xl bg-[#FEF2F2] border border-[#DC2626]/25 text-[#DC2626] px-3.5 py-2.5 text-xs font-semibold" x-text="formError"></div>

                <div>
                    <label class="block text-xs font-bold text-[#252525] mb-1.5">Kategori / Kata Kunci <span class="text-[#B01116]">*</span></label>
                    <input
                        type="text"
                        x-model="editForm.kategori_kata_kunci"
                        required
                        class="w-full rounded-xl border border-[#E5E7EB] px-3.5 py-2.5 text-sm text-[#252525] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116] focus:outline-none"
                    />
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#252525] mb-1.5">Tipe Tujuan <span class="text-[#B01116]">*</span></label>
                    <select
                        x-model="editForm.tipe_tujuan"
                        @change="onTipeChange(editForm)"
                        class="w-full rounded-xl border border-[#E5E7EB] px-3.5 py-2.5 text-sm text-[#252525] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116] focus:outline-none"
                    >
                        <option value="Program Studi">Program Studi</option>
                        <option value="Direktorat">Direktorat</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#252525] mb-1.5">Rekomendasi Default <span class="text-[#B01116]">*</span></label>
                    <input
                        type="text"
                        list="unitOptionsList"
                        x-model="editForm.rekomendasi_default"
                        required
                        class="w-full rounded-xl border border-[#E5E7EB] px-3.5 py-2.5 text-sm text-[#252525] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116] focus:outline-none"
                    />
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#252525] mb-1.5">Status <span class="text-[#B01116]">*</span></label>
                    <select
                        x-model="editForm.status"
                        class="w-full rounded-xl border border-[#E5E7EB] px-3.5 py-2.5 text-sm text-[#252525] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116] focus:outline-none"
                    >
                        <option value="Aktif">Aktif</option>
                        <option value="Nonaktif">Nonaktif</option>
                    </select>
                </div>

                <div class="pt-3 border-t border-[#E5E7EB] flex items-center justify-between gap-2.5">
                    <button
                        type="button"
                        @click="deleteRule()"
                        :disabled="isSubmitting"
                        class="px-3.5 py-2 rounded-xl border border-[#DC2626]/30 text-xs sm:text-sm font-semibold text-[#DC2626] hover:bg-[#FEF2F2] disabled:opacity-60 transition cursor-pointer"
                    >
                        Hapus Aturan
                    </button>

                    <div class="flex items-center gap-2.5">
                        <button
                            type="button"
                            @click="showEditModal = false"
                            class="px-4 py-2 rounded-xl border border-[#E5E7EB] text-xs sm:text-sm font-semibold text-[#6B7280] hover:bg-gray-50 transition cursor-pointer"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            :disabled="isSubmitting"
                            class="px-5 py-2 rounded-xl bg-[#B01116] hover:bg-[#8A0D12] disabled:opacity-60 text-white text-xs sm:text-sm font-bold shadow-xs transition cursor-pointer"
                        >
                            <span x-text="isSubmitting ? 'Menyimpan...' : 'Simpan Perubahan'"></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Datalist Saran Unit Tujuan -->
    <datalist id="unitOptionsList">
        <option value="Program Studi pelapor"></option>
        <option value="Direktorat Akademik"></option>
        <option value="Direktorat Logistik dan Aset"></option>
        <option value="Direktorat Pusat Teknologi Informasi"></option>
        <option value="Direktorat Keuangan"></option>
        <option value="Direktorat Kemahasiswaan"></option>
        <option value="Direktorat Pengembangan Karir, Alumni, & Endowment"></option>
        <option value="Direktorat Penelitian dan Pengabdian Masyarakat"></option>
        <option value="Direktorat Kerjasama Strategis & Kantor Urusan Internasional"></option>
        <option value="Direktorat Sumber Daya Manusia"></option>
        <option value="Direktorat Sekretariat & Perencanaan Strategis"></option>
        <option value="Direktorat Bandung Techno Park"></option>
        <option value="Direktorat Pasca Sarjana dan Advance Learning"></option>
        <option value="Direktorat Pemasaran & Admisi"></option>
        <option value="Satpras"></option>
    </datalist>

    <script>
        function kategoriPenyaluranApp() {
            return {
                mobileSidebarOpen: false,
                searchHeader: '',
                rules: @json($rules),
                showAddModal: false,
                showEditModal: false,
                isSubmitting: false,
                formError: '',
                toastMessage: '',
                toastType: 'success',
                addForm: {
                    kategori_kata_kunci: '',
                    tipe_tujuan: 'Direktorat',
                    rekomendasi_default: '',
                    status: 'Aktif',
                },
                editForm: {
                    id: null,
                    kategori_kata_kunci: '',
                    tipe_tujuan: 'Direktorat',
                    rekomendasi_default: '',
                    status: 'Aktif',
                },

                get filteredRules() {
                    const q = this.searchHeader.trim().toLowerCase();
                    if (!q) return this.rules;
                    return this.rules.filter(r =>
                        r.kategori_kata_kunci.toLowerCase().includes(q) ||
                        r.tipe_tujuan.toLowerCase().includes(q) ||
                        r.rekomendasi_default.toLowerCase().includes(q) ||
                        r.status.toLowerCase().includes(q)
                    );
                },

                showToast(message, type = 'success') {
                    this.toastMessage = message;
                    this.toastType = type;
                    setTimeout(() => {
                        if (this.toastMessage === message) {
                            this.toastMessage = '';
                        }
                    }, 4000);
                },

                onTipeChange(form) {
                    if (form.tipe_tujuan === 'Program Studi' && !form.rekomendasi_default) {
                        form.rekomendasi_default = 'Program Studi pelapor';
                    }
                },

                openAddModal() {
                    this.formError = '';
                    this.addForm = {
                        kategori_kata_kunci: '',
                        tipe_tujuan: 'Direktorat',
                        rekomendasi_default: '',
                        status: 'Aktif',
                    };
                    this.showAddModal = true;
                },

                openEditModal(rule) {
                    this.formError = '';
                    this.editForm = {
                        id: rule.id,
                        kategori_kata_kunci: rule.kategori_kata_kunci,
                        tipe_tujuan: rule.tipe_tujuan,
                        rekomendasi_default: rule.rekomendasi_default,
                        status: rule.status,
                    };
                    this.showEditModal = true;
                },

                async submitAddRule() {
                    this.formError = '';
                    this.isSubmitting = true;
                    try {
                        const res = await fetch('{{ route("superadmin.kategori.store") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify(this.addForm),
                        });
                        const data = await res.json();
                        if (!res.ok) {
                            this.formError = data.message || 'Gagal menyimpan aturan.';
                            return;
                        }
                        this.rules.push(data.rule);
                        this.showAddModal = false;
                        this.showToast(data.message || 'Aturan penyaluran berhasil ditambahkan.');
                    } catch (e) {
                        this.formError = 'Terjadi kesalahan jaringan.';
                    } finally {
                        this.isSubmitting = false;
                    }
                },

                async submitEditRule() {
                    this.formError = '';
                    this.isSubmitting = true;
                    try {
                        const res = await fetch(`/super-admin/kategori-penyaluran/${this.editForm.id}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify(this.editForm),
                        });
                        const data = await res.json();
                        if (!res.ok) {
                            this.formError = data.message || 'Gagal memperbarui aturan.';
                            return;
                        }
                        const idx = this.rules.findIndex(r => r.id === data.rule.id);
                        if (idx !== -1) {
                            this.rules[idx] = data.rule;
                        }
                        this.showEditModal = false;
                        this.showToast(data.message || 'Aturan penyaluran berhasil diperbarui.');
                    } catch (e) {
                        this.formError = 'Terjadi kesalahan jaringan.';
                    } finally {
                        this.isSubmitting = false;
                    }
                },

                async deleteRule() {
                    if (!confirm('Hapus aturan penyaluran ini?')) return;
                    this.formError = '';
                    this.isSubmitting = true;
                    try {
                        const res = await fetch(`/super-admin/kategori-penyaluran/${this.editForm.id}`, {
                            method: 'DELETE',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                        });
                        const data = await res.json();
                        if (!res.ok) {
                            this.formError = data.message || 'Gagal menghapus aturan.';
                            return;
                        }
                        this.rules = this.rules.filter(r => r.id !== this.editForm.id);
                        this.showEditModal = false;
                        this.showToast(data.message || 'Aturan penyaluran berhasil dihapus.');
                    } catch (e) {
                        this.formError = 'Terjadi kesalahan jaringan.';
                    } finally {
                        this.isSubmitting = false;
                    }
                },
            };
        }
    </script>
</body>
</html>
