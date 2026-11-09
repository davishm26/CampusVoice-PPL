@php
    // Data statis notifikasi
    $notifications = [
        [
            'id' => 1,
            'icon' => 'progress',
            'title' => 'Progres laporan diperbarui',
            'desc' => 'Tim unit telah memperbarui progres CV-2026-00124 menjadi 65%.',
            'time' => '5 menit lalu',
            'unread' => true,
            'tiket' => 'CV-2026-00124',
        ],
        [
            'id' => 2,
            'icon' => 'forward',
            'title' => 'Laporan telah diteruskan',
            'desc' => 'CV-2026-00119 diteruskan ke Direktorat Pusat Teknologi Informasi.',
            'time' => '2 jam lalu',
            'unread' => true,
            'tiket' => 'CV-2026-00119',
        ],
        [
            'id' => 3,
            'icon' => 'response',
            'title' => 'Tanggapan baru dari unit',
            'desc' => 'Budi Santoso memberikan tanggapan pada laporan CV-2026-00124.',
            'time' => 'Kemarin, 16.20',
            'unread' => true,
            'tiket' => 'CV-2026-00124',
        ],
        [
            'id' => 4,
            'icon' => 'completed',
            'title' => 'Laporan telah selesai',
            'desc' => 'CV-2026-00108 telah ditandai selesai. Anda dapat memberikan penilaian.',
            'time' => '12 Okt 2026',
            'unread' => false,
            'tiket' => 'CV-2026-00108',
        ],
        [
            'id' => 5,
            'icon' => 'submitted',
            'title' => 'Laporan berhasil dikirim',
            'desc' => 'Laporan CV-2026-00097 telah diterima dan menunggu verifikasi SSC.',
            'time' => '8 Okt 2026',
            'unread' => false,
            'tiket' => 'CV-2026-00097',
        ],
    ];

    // Helper url detail laporan
    $detailUrl = fn ($tiket) => \Illuminate\Support\Facades\Route::has('laporan.detail')
        ? route('laporan.detail', $tiket)
        : (\Illuminate\Support\Facades\Route::has('laporan.show')
            ? route('laporan.show', $tiket)
            : url('/laporan/detail/' . $tiket));
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#FFFFFF]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Notifikasi - CampusVoice Telkom University Surabaya</title>

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
        body, input, select, button { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* Linear Gradient Konten Utama: Merah #B01116 (kiri bawah) ke Abu-abu Gelap #58595B (kanan atas) */
        .content-gradient-bg {
            background: linear-gradient(to top right, #B01116 0%, #58595B 100%);
        }
    </style>
</head>
<body
    class="h-full antialiased text-[#252525] bg-[#B01116]"
    x-data="{
        mobileSidebarOpen: false,
        activeTab: 'all',
        items: {{ \Illuminate\Support\Js::from($notifications) }},
        get unreadCount() {
            return this.items.filter(i => i.unread).length;
        },
        get totalCount() {
            return this.items.length;
        },
        get filteredItems() {
            if (this.activeTab === 'unread') {
                return this.items.filter(i => i.unread);
            }
            return this.items;
        },
        markAllAsRead() {
            this.items = this.items.map(i => ({ ...i, unread: false }));
        },
        goToDetail(tiket) {
            window.location.href = '{{ url('/laporan/detail') }}/' + tiket;
        }
    }"
>
    <div class="min-h-screen flex flex-col lg:flex-row">

        <!-- ========================================================= -->
        <!-- SIDEBAR KIRI -->
        <!-- ========================================================= -->
        <aside
            class="fixed inset-y-0 left-0 z-50 w-64 bg-[#FFFFFF] flex flex-col justify-between border-r border-[#E5E7EB] transition-transform duration-200 lg:static lg:translate-x-0"
            :class="mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        >
            <div class="flex flex-col flex-1 overflow-y-auto">
                <!-- Logo -->
                <div class="px-6 py-5 flex items-center gap-3">
                    <img src="{{ asset('images/logo-campusvoice.png') }}" alt="CampusVoice Logo" class="w-8 h-8 object-contain shrink-0" />
                    <div>
                        <span class="text-[17px] font-extrabold text-[#B01116] tracking-tight">CampusVoice</span>
                        <p class="text-[10px] text-[#6B7280] font-medium leading-tight">Telkom University Surabaya</p>
                    </div>
                </div>

                <!-- Navigasi Menu -->
                <div class="px-4 py-3 flex-1">
                    <p class="px-3 text-[11px] font-bold text-[#252525] tracking-wider mb-2 uppercase">Mahasiswa</p>

                    <nav class="space-y-1">
                        <!-- Menu 1: Dashboard -->
                        <a href="{{ url('/dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-[#252525] hover:bg-gray-50 transition group">
                            <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#252525]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="7" height="7" x="3" y="3" rx="1.5"/>
                                <rect width="7" height="7" x="14" y="3" rx="1.5"/>
                                <rect width="7" height="7" x="14" y="14" rx="1.5"/>
                                <rect width="7" height="7" x="3" y="14" rx="1.5"/>
                            </svg>
                            <span>Dashboard</span>
                        </a>

                        <!-- Menu 2: Buat Laporan -->
                        <a href="{{ route('laporan.create') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-[#252525] hover:bg-gray-50 transition group">
                            <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#252525]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            <span>Buat Laporan</span>
                        </a>

                        <!-- Menu 3: Riwayat Laporan -->
                        <a href="{{ route('laporan.riwayat') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-[#252525] hover:bg-gray-50 transition group">
                            <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#252525]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                                <line x1="16" y1="13" x2="8" y2="13"/>
                                <line x1="16" y1="17" x2="8" y2="17"/>
                                <polyline points="10 9 9 9 8 9"/>
                            </svg>
                            <span>Riwayat Laporan</span>
                        </a>

                        <!-- Menu 4: Notifikasi (AKTIF - Background #FCE8E8 & Teks/Ikon #B01116) -->
                        <a href="{{ route('notifikasi') }}" aria-current="page" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-bold bg-[#FCE8E8] text-[#B01116] transition">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-[#B01116]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
                                    <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                                </svg>
                                <span>Notifikasi</span>
                            </div>
                            <span 
                                class="inline-flex items-center justify-center w-5 h-5 text-[11px] font-bold text-[#FFFFFF] bg-[#B01116] rounded-full"
                                x-text="unreadCount"
                                x-show="unreadCount > 0"
                            >
                                3
                            </span>
                        </a>

                        <!-- Menu 5: Profil Saya -->
                        <a href="{{ route('profil') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-[#252525] hover:bg-gray-50 transition group">
                            <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#252525]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                            <span>Profil Saya</span>
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Footer Sidebar: Keluar & Kotak Butuh Bantuan (#B01116) -->
            <div class="p-4 border-t border-[#E5E7EB] space-y-3">
                <button type="button" class="w-full flex items-center gap-2 px-3 py-2 text-sm font-semibold text-[#B01116] hover:bg-[#FCE8E8] rounded-xl transition">
                    <svg class="w-5 h-5 text-[#B01116]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                    <span>Keluar</span>
                </button>

                <div class="rounded-xl bg-[#B01116] p-4 text-[#FFFFFF] shadow-sm">
                    <h4 class="text-sm font-bold tracking-tight">Butuh bantuan?</h4>
                    <p class="text-[11px] text-[#FFFFFF]/85 mt-1 leading-snug">Hubungi pusat layanan CampusVoice.</p>
                    <a href="javascript:void(0)" class="mt-3 inline-block py-1.5 px-3 bg-[#FFFFFF] hover:bg-gray-100 text-[#B01116] text-xs font-bold rounded-lg text-center transition">
                        Pusat Bantuan
                    </a>
                </div>
            </div>
        </aside>

        <!-- Overlay Mobile -->
        <div x-show="mobileSidebarOpen" x-cloak @click="mobileSidebarOpen = false" class="fixed inset-0 z-40 bg-black/40 lg:hidden"></div>

        <!-- ========================================================= -->
        <!-- AREA UTAMA (Header + Konten) -->
        <!-- ========================================================= -->
        <div class="flex-1 flex flex-col min-w-0">

            <!-- HEADER ATAS -->
            <header class="bg-[#FFFFFF] border-b border-[#E5E7EB] h-16 flex items-center justify-between px-4 sm:px-8 shrink-0">
                <div class="flex items-center gap-3">
                    <button @click="mobileSidebarOpen = !mobileSidebarOpen" class="p-2 -ml-2 rounded-lg text-[#6B7280] hover:text-[#252525] hover:bg-gray-100 lg:hidden" type="button">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <div class="relative w-48 sm:w-80 md:w-96">
                        <input
                            type="text"
                            placeholder="Cari nomor tiket atau laporan..."
                            class="w-full bg-[#FFFFFF] border border-[#E5E7EB] rounded-lg py-2 px-3.5 text-xs sm:text-sm text-[#252525] placeholder-[#6B7280] focus:border-[#B01116] focus:outline-none focus:ring-1 focus:ring-[#B01116] transition"
                        />
                    </div>
                </div>

                <div class="flex items-center gap-4 sm:gap-5">
                    <a href="{{ route('notifikasi') }}" class="relative w-9 h-9 rounded-full border border-[#E5E7EB] flex items-center justify-center text-[#6B7280] hover:text-[#252525] hover:bg-gray-50 transition">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
                            <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                        </svg>
                        <span 
                            class="absolute -top-1 -right-1 flex items-center justify-center w-4 h-4 text-[10px] font-bold text-[#FFFFFF] bg-[#B01116] rounded-full ring-2 ring-[#FFFFFF]"
                            x-text="unreadCount"
                            x-show="unreadCount > 0"
                        >
                            3
                        </span>
                    </a>

                    <a href="{{ route('profil') }}" class="flex items-center gap-2.5 group">
                        <div class="w-9 h-9 rounded-lg bg-[#FCE8E8] text-[#B01116] font-bold text-xs flex items-center justify-center shrink-0 group-hover:ring-2 group-hover:ring-[#B01116]/30 transition">DA</div>
                        <div class="hidden sm:block text-left">
                            <p class="text-xs font-bold text-[#252525] leading-tight group-hover:text-[#B01116] transition">Dimas Arya</p>
                            <p class="text-[11px] text-[#6B7280] leading-tight">Mahasiswa</p>
                        </div>
                    </a>
                </div>
            </header>

            <!-- ========================================================= -->
            <!-- KONTEN: NOTIFIKASI (Background Gradient) -->
            <!-- ========================================================= -->
            <main class="flex-1 content-gradient-bg px-4 sm:px-8 lg:px-10 py-8">
                <div class="max-w-7xl mx-auto">

                    <!-- Header Notifikasi: Judul & Tombol "Tandai semua telah dibaca" -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-[#FFFFFF] tracking-tight">Notifikasi</h1>
                            <p class="text-xs sm:text-sm text-[#FFFFFF]/90 mt-1">Pembaruan penting terkait laporan dan aktivitas akun Anda.</p>
                        </div>

                        <div>
                            <button
                                type="button"
                                @click="markAllAsRead()"
                                class="px-5 py-2.5 rounded-xl bg-[#FFFFFF] hover:bg-gray-50 text-xs sm:text-sm font-bold text-[#252525] shadow-sm transition inline-flex items-center gap-2 cursor-pointer"
                            >
                                Tandai semua telah dibaca
                            </button>
                        </div>
                    </div>

                    <!-- Layout Grid 2 Kolom: Kolom Kiri Lebar & Kolom Kanan Sempit -->
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                        <!-- ===================================================== -->
                        <!-- KOLOM KIRI (Kartu Daftar Notifikasi - Background Putih) -->
                        <!-- ===================================================== -->
                        <div class="lg:col-span-8 xl:col-span-9 bg-[#FFFFFF] rounded-2xl shadow-xl overflow-hidden">

                            <!-- Navigasi Tab -->
                            <div class="px-6 pt-4 border-b border-[#E5E7EB] flex items-center gap-6">
                                <!-- Tab 1: Semua -->
                                <button
                                    type="button"
                                    @click="activeTab = 'all'"
                                    class="pb-3.5 text-sm font-bold transition flex items-center gap-2 relative cursor-pointer"
                                    :class="activeTab === 'all' ? 'text-[#B01116] border-b-2 border-[#B01116] -mb-[1px]' : 'text-[#6B7280] hover:text-[#252525]'"
                                >
                                    <span>Semua</span>
                                    <span
                                        class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold rounded-full transition"
                                        :class="activeTab === 'all' ? 'bg-[#FCE8E8] text-[#B01116]' : 'bg-gray-100 text-[#6B7280]'"
                                        x-text="totalCount"
                                    >
                                        6
                                    </span>
                                </button>

                                <!-- Tab 2: Belum dibaca -->
                                <button
                                    type="button"
                                    @click="activeTab = 'unread'"
                                    class="pb-3.5 text-sm font-bold transition flex items-center gap-2 relative cursor-pointer"
                                    :class="activeTab === 'unread' ? 'text-[#B01116] border-b-2 border-[#B01116] -mb-[1px]' : 'text-[#6B7280] hover:text-[#252525]'"
                                >
                                    <span>Belum dibaca</span>
                                    <span
                                        class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold rounded-full transition"
                                        :class="activeTab === 'unread' ? 'bg-[#FCE8E8] text-[#B01116]' : 'bg-gray-100 text-[#6B7280]'"
                                        x-text="unreadCount"
                                    >
                                        3
                                    </span>
                                </button>
                            </div>

                            <!-- Daftar Item Notifikasi -->
                            <div class="divide-y divide-[#E5E7EB]">
                                <template x-for="item in filteredItems" :key="item.id">
                                    <a
                                        :href="'{{ url('/laporan/detail') }}/' + item.tiket"
                                        class="block px-6 py-5 transition cursor-pointer group"
                                        :class="item.unread ? 'bg-[#FEF2F2] hover:bg-[#FEEAEA]' : 'bg-[#FFFFFF] hover:bg-[#F9FAFB]'"
                                    >
                                        <div class="flex items-start gap-4">
                                            <!-- Ikon Kiri Berlatar Abu Terang -->
                                            <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center shrink-0 text-[#6B7280] group-hover:scale-105 transition">
                                                <!-- Ikon 1: Progres (Network/Sliders/Branch) -->
                                                <template x-if="item.icon === 'progress'">
                                                    <svg class="w-5 h-5 text-[#6B7280]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                        <circle cx="12" cy="18" r="3"/>
                                                        <circle cx="6" cy="6" r="3"/>
                                                        <circle cx="18" cy="6" r="3"/>
                                                        <path d="M18 9v1a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V9"/>
                                                        <path d="M12 12v3"/>
                                                    </svg>
                                                </template>

                                                <!-- Ikon 2: Panah Kanan (Diteruskan) -->
                                                <template x-if="item.icon === 'forward'">
                                                    <svg class="w-5 h-5 text-[#6B7280]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <line x1="5" y1="12" x2="19" y2="12"/>
                                                        <polyline points="12 5 19 12 12 19"/>
                                                    </svg>
                                                </template>

                                                <!-- Ikon 3: Dokumen (Tanggapan) -->
                                                <template x-if="item.icon === 'response'">
                                                    <svg class="w-5 h-5 text-[#6B7280]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                                        <polyline points="14 2 14 8 20 8"/>
                                                        <line x1="16" y1="13" x2="8" y2="13"/>
                                                        <line x1="16" y1="17" x2="8" y2="17"/>
                                                    </svg>
                                                </template>

                                                <!-- Ikon 4: Centang (Selesai) -->
                                                <template x-if="item.icon === 'completed'">
                                                    <svg class="w-5 h-5 text-[#6B7280]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                        <polyline points="20 6 9 17 4 12"/>
                                                    </svg>
                                                </template>

                                                <!-- Ikon 5: Perisai Centang (Terkirim) -->
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

                                            <!-- Titik Merah (#B01116) untuk yang Belum Dibaca -->
                                            <div class="shrink-0 pt-1.5 pl-2">
                                                <template x-if="item.unread">
                                                    <span class="block w-2.5 h-2.5 rounded-full bg-[#B01116] shadow-xs"></span>
                                                </template>
                                            </div>
                                        </div>
                                    </a>
                                </template>

                                <!-- State Kosong (Jika tidak ada item belum dibaca) -->
                                <div x-show="filteredItems.length === 0" x-cloak class="py-12 px-6 text-center">
                                    <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mx-auto text-[#6B7280] mb-3">
                                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
                                            <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-bold text-[#252525]">Tidak ada notifikasi belum dibaca</p>
                                    <p class="text-xs text-[#6B7280] mt-1">Seluruh notifikasi Anda telah ditandai sebagai dibaca.</p>
                                </div>
                            </div>
                        </div>

                        <!-- ===================================================== -->
                        <!-- KOLOM KANAN (Kartu Info Sidebar - Background Putih) -->
                        <!-- ===================================================== -->
                        <div class="lg:col-span-4 xl:col-span-3 bg-[#FFFFFF] rounded-2xl shadow-xl p-6 sm:p-7">
                            <!-- Ikon Lonceng Warna Merah #B01116 -->
                            <div class="w-6 h-6 text-[#B01116] mb-3">
                                <svg class="w-6 h-6 text-[#B01116]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
                                    <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                                </svg>
                            </div>

                            <!-- Judul Gelap #252525 -->
                            <h2 class="text-sm sm:text-base font-bold text-[#252525] tracking-tight">
                                Tetap terinformasi
                            </h2>

                            <!-- Deskripsi Abu-abu #6B7280 -->
                            <p class="text-xs text-[#6B7280] leading-relaxed mt-2">
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

