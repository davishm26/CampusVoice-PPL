<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#FFFFFF]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Notifikasi Unit - Petugas Unit CampusVoice</title>

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
        activeTab: 'all',
        totalBadge: 6,
        items: [
            {
                id: 1,
                icon: 'progress',
                title: 'Progres laporan diperbarui',
                desc: 'Tim unit telah memperbarui progres CV-2026-00124 menjadi 65%.',
                time: '5 menit lalu',
                unread: true
            },
            {
                id: 2,
                icon: 'forward',
                title: 'Laporan telah diteruskan',
                desc: 'CV-2026-00119 diteruskan ke Direktorat Pusat Teknologi Informasi.',
                time: '2 jam lalu',
                unread: true
            },
            {
                id: 3,
                icon: 'response',
                title: 'Tanggapan baru dari unit',
                desc: 'Budi Santoso memberikan tanggapan pada laporan CV-2026-00124.',
                time: 'Kemarin, 16.20',
                unread: true
            },
            {
                id: 4,
                icon: 'completed',
                title: 'Laporan telah selesai',
                desc: 'CV-2026-00108 telah ditandai selesai. Anda dapat memberikan penilaian.',
                time: '12 Okt 2026',
                unread: false
            },
            {
                id: 5,
                icon: 'submitted',
                title: 'Laporan berhasil dikirim',
                desc: 'Laporan CV-2026-00097 telah diterima dan menunggu verifikasi SSC.',
                time: '8 Okt 2026',
                unread: false
            }
        ],
        get unreadCount() {
            return this.items.filter(i => i.unread).length;
        },
        get filteredItems() {
            const q = this.searchHeader.trim().toLowerCase();
            return this.items.filter(i => {
                if (this.activeTab === 'unread' && !i.unread) return false;
                if (q && !i.title.toLowerCase().includes(q) && !i.desc.toLowerCase().includes(q)) return false;
                return true;
            });
        },
        markAsRead(id) {
            const item = this.items.find(i => i.id === id);
            if (item) item.unread = false;
        },
        markAllAsRead() {
            this.items.forEach(i => { i.unread = false; });
        }
    }"
>
    <div class="min-h-screen flex flex-col lg:flex-row">

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
                            href="{{ route('unit.dashboard') }}"
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

                        <!-- Menu 2: Laporan Ditugaskan -->
                        <a
                            href="#"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#252525] hover:bg-gray-100 hover:text-[#B01116] transition group"
                        >
                            <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                                <line x1="16" y1="13" x2="8" y2="13"/>
                                <line x1="16" y1="17" x2="8" y2="17"/>
                            </svg>
                            <span>Laporan Ditugaskan</span>
                        </a>

                        <!-- Menu 3: Riwayat Penanganan -->
                        <a
                            href="#"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#252525] hover:bg-gray-100 hover:text-[#B01116] transition group"
                        >
                            <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="20" x2="12" y2="10"/>
                                <line x1="18" y1="20" x2="18" y2="4"/>
                                <line x1="6" y1="20" x2="6" y2="16"/>
                            </svg>
                            <span>Riwayat Penanganan</span>
                        </a>

                        <!-- Menu 4: Notifikasi (AKTIF) -->
                        <a
                            href="{{ route('unit.notifikasi') }}"
                            class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-bold bg-[#FCE8E8] text-[#B01116] transition shadow-xs"
                        >
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                                    <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                                </svg>
                                <span>Notifikasi</span>
                            </div>
                            <span
                                x-show="unreadCount > 0"
                                x-text="unreadCount"
                                class="w-5 h-5 rounded-full bg-[#B01116] text-[#FFFFFF] text-[11px] font-bold flex items-center justify-center"
                            >
                                3
                            </span>
                        </a>

                        <!-- Menu 5: Profil Unit -->
                        <a
                            href="#"
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

                <!-- Bagian Kanan: Lonceng Notifikasi & Profil Petugas Unit -->
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
                        <span
                            x-show="unreadCount > 0"
                            x-text="unreadCount"
                            class="absolute -top-1.5 -right-1.5 w-4 h-4 rounded-full bg-[#B01116] text-[#FFFFFF] text-[10px] font-bold flex items-center justify-center shadow-xs"
                        >
                            3
                        </span>
                    </button>

                    <!-- Profil Petugas Unit: Budi Santoso -->
                    <div class="flex items-center gap-2.5 pl-1">
                        <div class="w-9 h-9 rounded-lg bg-[#FCE8E8] text-[#B01116] font-bold text-xs flex items-center justify-center shrink-0">
                            BS
                        </div>
                        <div class="hidden sm:block text-left">
                            <p class="text-xs font-bold text-[#252525] leading-tight">Budi Santoso</p>
                            <p class="text-[11px] text-[#6B7280] leading-tight">Petugas Unit</p>
                        </div>
                    </div>
                </div>
            </header>

            <!-- ========================================================= -->
            <!-- AREA KONTEN UTAMA: Linear Gradient #B01116 ke #58595B -->
            <!-- ========================================================= -->
            <main class="flex-1 content-gradient-bg px-4 sm:px-8 lg:px-10 py-7 overflow-y-auto">
                <div class="max-w-7xl mx-auto">

                    <!-- Header Halaman -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#FFFFFF] tracking-tight">
                                Notifikasi
                            </h1>
                            <p class="text-xs sm:text-sm text-[#FFFFFF]/85 mt-1 font-normal">
                                Pembaruan penting terkait laporan dan aktivitas akun Anda.
                            </p>
                        </div>

                        <div>
                            <button
                                type="button"
                                @click="markAllAsRead()"
                                class="px-5 py-2.5 rounded-xl bg-[#FFFFFF] hover:bg-gray-50 text-xs sm:text-sm font-bold text-[#252525] shadow-sm transition cursor-pointer"
                            >
                                Tandai semua telah dibaca
                            </button>
                        </div>
                    </div>

                    <!-- TATA LETAK GRID (Kolom Kiri Lebar, Kolom Kanan Sempit) -->
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                        <!-- ===================================================== -->
                        <!-- KOLOM KIRI: KARTU DAFTAR NOTIFIKASI (#FFFFFF) -->
                        <!-- ===================================================== -->
                        <div class="lg:col-span-8 xl:col-span-9 w-full bg-[#FFFFFF] rounded-2xl shadow-xl overflow-hidden border border-[#E5E7EB] min-w-0">

                            <!-- Navigasi Tab -->
                            <div class="px-6 pt-4 border-b border-[#E5E7EB] flex items-center gap-6 bg-[#FFFFFF]">
                                <!-- Tab 1: Semua -->
                                <button
                                    type="button"
                                    @click="activeTab = 'all'"
                                    class="pb-3.5 text-xs sm:text-sm font-bold transition flex items-center gap-2 relative cursor-pointer"
                                    :class="activeTab === 'all' ? 'text-[#B01116] border-b-2 border-[#B01116] -mb-[1px]' : 'text-[#6B7280] hover:text-[#252525]'"
                                >
                                    <span>Semua</span>
                                    <span
                                        class="inline-flex items-center justify-center px-2 py-0.5 text-[11px] font-bold rounded-full transition"
                                        :class="activeTab === 'all' ? 'bg-[#FCE8E8] text-[#B01116]' : 'bg-gray-100 text-[#6B7280]'"
                                        x-text="totalBadge"
                                    >
                                        6
                                    </span>
                                </button>

                                <!-- Tab 2: Belum dibaca -->
                                <button
                                    type="button"
                                    @click="activeTab = 'unread'"
                                    class="pb-3.5 text-xs sm:text-sm font-bold transition flex items-center gap-2 relative cursor-pointer"
                                    :class="activeTab === 'unread' ? 'text-[#B01116] border-b-2 border-[#B01116] -mb-[1px]' : 'text-[#6B7280] hover:text-[#252525]'"
                                >
                                    <span>Belum dibaca</span>
                                    <span
                                        class="inline-flex items-center justify-center px-2 py-0.5 text-[11px] font-bold rounded-full transition"
                                        :class="activeTab === 'unread' ? 'bg-[#FCE8E8] text-[#B01116]' : 'bg-gray-100 text-[#6B7280]'"
                                        x-text="unreadCount"
                                    >
                                        3
                                    </span>
                                </button>
                            </div>

                            <!-- Daftar Item Notifikasi (Dipisah border horizontal #E5E7EB) -->
                            <div class="divide-y divide-[#E5E7EB]">
                                <template x-for="item in filteredItems" :key="item.id">
                                    <div
                                        @click="markAsRead(item.id)"
                                        class="px-6 py-5 transition cursor-pointer group"
                                        :class="item.unread ? 'bg-[#FEF2F2]/60 hover:bg-[#FEEAEA]/70' : 'bg-[#FFFFFF] hover:bg-[#F9FAFB]'"
                                    >
                                        <div class="flex items-start gap-4">
                                            <!-- Ikon Kiri berlatar abu terang -->
                                            <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center shrink-0 text-[#6B7280] group-hover:scale-105 transition">
                                                <!-- Icon 1: Network/Proses -->
                                                <template x-if="item.icon === 'progress'">
                                                    <svg class="w-5 h-5 text-[#6B7280]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                        <circle cx="12" cy="18" r="3"/>
                                                        <circle cx="6" cy="6" r="3"/>
                                                        <circle cx="18" cy="6" r="3"/>
                                                        <path d="M18 9v1a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V9"/>
                                                        <path d="M12 12v3"/>
                                                    </svg>
                                                </template>

                                                <!-- Icon 2: Panah Kanan -->
                                                <template x-if="item.icon === 'forward'">
                                                    <svg class="w-5 h-5 text-[#6B7280]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <line x1="5" y1="12" x2="19" y2="12"/>
                                                        <polyline points="12 5 19 12 12 19"/>
                                                    </svg>
                                                </template>

                                                <!-- Icon 3: Dokumen -->
                                                <template x-if="item.icon === 'response'">
                                                    <svg class="w-5 h-5 text-[#6B7280]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                                        <polyline points="14 2 14 8 20 8"/>
                                                        <line x1="16" y1="13" x2="8" y2="13"/>
                                                        <line x1="16" y1="17" x2="8" y2="17"/>
                                                    </svg>
                                                </template>

                                                <!-- Icon 4: Centang -->
                                                <template x-if="item.icon === 'completed'">
                                                    <svg class="w-5 h-5 text-[#6B7280]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                        <polyline points="20 6 9 17 4 12"/>
                                                    </svg>
                                                </template>

                                                <!-- Icon 5: Perisai Centang -->
                                                <template x-if="item.icon === 'submitted'">
                                                    <svg class="w-5 h-5 text-[#6B7280]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                                        <path d="m9 12 2 2 4-4"/>
                                                    </svg>
                                                </template>
                                            </div>

                                            <!-- Konten Teks -->
                                            <div class="flex-1 min-w-0">
                                                <h3 class="text-sm sm:text-[15px] font-bold text-[#252525] tracking-tight" x-text="item.title"></h3>
                                                <p class="text-xs sm:text-[13px] text-[#6B7280] mt-1 leading-snug" x-text="item.desc"></p>
                                                <p class="text-[11px] sm:text-xs text-[#6B7280] mt-1.5" x-text="item.time"></p>
                                            </div>

                                            <!-- Titik Merah Penanda Belum Dibaca (#B01116) -->
                                            <div class="shrink-0 pt-1.5 pl-2">
                                                <template x-if="item.unread">
                                                    <span class="block w-2 h-2 rounded-full bg-[#B01116]"></span>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <!-- State Kosong -->
                                <div x-show="filteredItems.length === 0" x-cloak class="py-12 px-6 text-center bg-[#FFFFFF]">
                                    <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mx-auto text-[#6B7280] mb-3">
                                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                                            <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-bold text-[#252525]">Tidak ada notifikasi</p>
                                    <p class="text-xs text-[#6B7280] mt-1">Semua pembaruan laporan unit sudah Anda baca.</p>
                                </div>
                            </div>
                        </div>

                        <!-- ===================================================== -->
                        <!-- KOLOM KANAN: KARTU INFO SIDEBAR (#FFFFFF) -->
                        <!-- ===================================================== -->
                        <div class="lg:col-span-4 xl:col-span-3 w-full bg-[#FFFFFF] rounded-2xl p-6 shadow-xl border border-[#E5E7EB] min-w-0">
                            <div class="text-[#B01116] mb-3">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                                    <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                                </svg>
                            </div>
                            <h3 class="text-sm font-bold text-[#252525] leading-snug">
                                Tetap terinformasi
                            </h3>
                            <p class="text-xs text-[#6B7280] mt-1.5 leading-relaxed">
                                Notifikasi dikirim untuk perubahan status, tanggapan, penyelesaian, dan pembukaan kembali laporan.
                            </p>
                        </div>

                    </div>

                </div>
            </main>

        </div>
    </div>
</body>
</html>

