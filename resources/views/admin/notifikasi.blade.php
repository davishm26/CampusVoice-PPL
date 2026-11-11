@php
    $authUser = $authUser ?? Auth::user();
    if (!$authUser) {
        $authUser = \App\Models\User::where('role', 'Admin SSC')->first() ?? \App\Models\User::first() ?? new \App\Models\User([
            'name' => 'Siti Rahmawati',
            'role' => 'Admin SSC',
            'email' => 'admin.ssc@telkomuniversity.ac.id',
        ]);
    }
    $authName = $authUser ? $authUser->name : 'Siti Rahmawati';
    $authRole = ($authUser && $authUser->role) ? $authUser->role : 'Admin SSC';

    $authWords = preg_split('/\s+/', trim($authName));
    $authInitials = '';
    foreach ($authWords as $w) { $authInitials .= mb_strtoupper(mb_substr($w, 0, 1)); }
    $authInitials = substr($authInitials, 0, 2) ?: 'SR';

    $notifications = $notifications ?? ($authUser?->id ? \App\Models\AppNotification::where('user_id', $authUser->id)->orderBy('created_at', 'desc')->get() : collect());

    if ($notifications->isEmpty()) {
        $notifItems = [
            [
                'id' => 1,
                'icon' => 'progress',
                'title' => 'Progres laporan diperbarui',
                'desc' => 'Tim unit telah memperbarui progres CV-2026-00124 menjadi 65%.',
                'time' => '5 menit lalu',
                'unread' => true,
                'tiket' => 'CV-2026-00124',
                'url' => '#',
            ],
            [
                'id' => 2,
                'icon' => 'forward',
                'title' => 'Laporan telah diteruskan',
                'desc' => 'CV-2026-00119 diteruskan ke Direktorat Pusat Teknologi Informasi.',
                'time' => '2 jam lalu',
                'unread' => true,
                'tiket' => 'CV-2026-00119',
                'url' => '#',
            ],
            [
                'id' => 3,
                'icon' => 'response',
                'title' => 'Tanggapan baru dari unit',
                'desc' => 'Budi Santoso memberikan tanggapan pada laporan CV-2026-00124.',
                'time' => 'Kemarin, 16.20',
                'unread' => true,
                'tiket' => 'CV-2026-00124',
                'url' => '#',
            ],
            [
                'id' => 4,
                'icon' => 'completed',
                'title' => 'Laporan telah selesai',
                'desc' => 'CV-2026-00108 telah ditandai selesai. Anda dapat memberikan penilaian.',
                'time' => '12 Okt 2026',
                'unread' => false,
                'tiket' => 'CV-2026-00108',
                'url' => '#',
            ],
            [
                'id' => 5,
                'icon' => 'submitted',
                'title' => 'Laporan berhasil dikirim',
                'desc' => 'Laporan CV-2026-00097 telah diterima dan menunggu verifikasi SSC.',
                'time' => '8 Okt 2026',
                'unread' => false,
                'tiket' => 'CV-2026-00097',
                'url' => '#',
            ],
        ];
        $unreadCount = 3;
    } else {
        $unreadCount = $notifications->where('is_read', false)->count();
        $notifItems = $notifications->map(function ($n) {
            return [
                'id' => $n->id,
                'icon' => $n->icon,
                'title' => $n->title,
                'desc' => $n->desc,
                'time' => $n->formatted_time,
                'unread' => !$n->is_read,
                'tiket' => $n->tiket,
                'url' => route('notifikasi.read', $n->id),
            ];
        })->values()->all();
    }
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-white">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Notifikasi Admin SSC - CampusVoice Telkom University Surabaya</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS with Forms Plugin via CDN -->
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        'primary-red': '#B01116',
                        'dark-red': '#8F1D22',
                        'light-red': '#FCE8E8',
                        'text-primary': '#252525',
                        'text-secondary': '#58595B',
                        'text-muted': '#6B7280',
                        'border-color': '#E5E7EB',
                        'bg-light': '#F8F8F9',
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #252525;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }

        /* Gradient Latar Utama sesuai spesifikasi */
        .main-gradient-bg {
            background: linear-gradient(
                135deg,
                #704247 0%,
                #9F171B 45%,
                #B01116 75%,
                #C31319 100%
            );
        }
    </style>
</head>
<body
    class="min-h-screen bg-[#F8F8F9] antialiased"
    x-data="{
        mobileSidebarOpen: false,
        activeTab: 'all',
        items: @js($notifItems),
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
            fetch('{{ route('notifikasi.markAll') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').getAttribute('content'),
                    'Accept': 'application/json'
                }
            }).then(() => {
                this.items = this.items.map(i => ({ ...i, unread: false }));
            }).catch(() => {
                this.items = this.items.map(i => ({ ...i, unread: false }));
            });
        }
    }"
>
    <!-- ========================================================= -->
    <!-- SIDEBAR KIRI FIXED (248px, Full Height, Background Putih) -->
    <!-- ========================================================= -->
    <aside
        class="fixed top-0 bottom-0 left-0 z-40 w-[248px] bg-white border-r border-[#E5E7EB] flex flex-col justify-between transition-transform duration-200 lg:translate-x-0"
        :class="mobileSidebarOpen ? 'translate-x-0 shadow-2xl' : '-translate-x-full lg:translate-x-0'"
    >
        <div class="flex flex-col flex-1 overflow-y-auto">
            <!-- Logo CampusVoice -->
            <div class="pt-[22px] px-[20px] pb-[16px] flex items-center gap-[10px]">
                <img src="{{ asset('images/logo-campusvoice.png') }}" alt="CampusVoice Logo" class="w-7 h-7 object-contain shrink-0" onerror="this.onerror=null; this.src='https://placehold.co/28x28/B01116/white?text=CV';" />
                <div class="min-w-0">
                    <span class="text-[16px] font-bold text-[#B01116] tracking-tight block leading-tight">CampusVoice</span>
                    <p class="text-[10px] text-[#6B7280] font-medium leading-tight mt-[2px] truncate">Telkom University Surabaya</p>
                </div>
            </div>

            <!-- Navigasi Menu Admin SSC -->
            <div class="px-[16px] flex-1">
                <span class="text-[11px] font-semibold text-[#58595B] uppercase tracking-wider px-[8px] mt-[12px] mb-[8px] block">
                    Admin SSC
                </span>

                <nav class="space-y-[4px]">
                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}" class="h-[44px] px-[12px] flex items-center gap-[12px] rounded-[7px] text-[13.5px] font-medium text-[#252525] hover:bg-[#F8F8F9] transition group">
                        <svg class="w-[18px] h-[18px] text-[#58595B] group-hover:text-[#252525] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="7" height="7" x="3" y="3" rx="1.5"/>
                            <rect width="7" height="7" x="14" y="3" rx="1.5"/>
                            <rect width="7" height="7" x="14" y="14" rx="1.5"/>
                            <rect width="7" height="7" x="3" y="14" rx="1.5"/>
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    <!-- Verifikasi Laporan -->
                    <a href="{{ route('admin.verifikasi') }}" class="h-[44px] px-[12px] flex items-center gap-[12px] rounded-[7px] text-[13.5px] font-medium text-[#252525] hover:bg-[#F8F8F9] transition group">
                        <svg class="w-[18px] h-[18px] text-[#58595B] group-hover:text-[#252525] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="9"/>
                            <polyline points="9 12 11.5 14.5 15.5 9.5"/>
                        </svg>
                        <span>Verifikasi Laporan</span>
                    </a>

                    <!-- Monitoring Laporan -->
                    <a href="{{ route('admin.monitoring') }}" class="h-[44px] px-[12px] flex items-center gap-[12px] rounded-[7px] text-[13.5px] font-medium text-[#252525] hover:bg-[#F8F8F9] transition group">
                        <svg class="w-[18px] h-[18px] text-[#58595B] group-hover:text-[#252525] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="20" x2="18" y2="10"/>
                            <line x1="12" y1="20" x2="12" y2="4"/>
                            <line x1="6" y1="20" x2="6" y2="14"/>
                        </svg>
                        <span>Monitoring Laporan</span>
                    </a>

                    <!-- Rekapitulasi -->
                    <a href="{{ route('admin.rekapitulasi') }}" class="h-[44px] px-[12px] flex items-center gap-[12px] rounded-[7px] text-[13.5px] font-medium text-[#252525] hover:bg-[#F8F8F9] transition group">
                        <svg class="w-[18px] h-[18px] text-[#58595B] group-hover:text-[#252525] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="7 10 12 15 17 10"/>
                            <line x1="12" y1="15" x2="12" y2="3"/>
                        </svg>
                        <span>Rekapitulasi</span>
                    </a>

                    <!-- Notifikasi (Menu Aktif: Background #FCE8E8, Color #B01116, border-radius 7px, badge circle 3) -->
                    <a
                        href="{{ route('admin.notifikasi') }}"
                        aria-current="page"
                        class="h-[44px] px-[12px] flex items-center justify-between rounded-[7px] text-[13.5px] font-medium bg-[#FCE8E8] text-[#B01116] transition"
                    >
                        <div class="flex items-center gap-[12px]">
                            <svg class="w-[18px] h-[18px] text-[#B01116] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
                                <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                            </svg>
                            <span>Notifikasi</span>
                        </div>
                        <span
                            class="w-[19px] h-[19px] rounded-full bg-[#B01116] text-white text-[11px] font-semibold flex items-center justify-center shrink-0"
                            x-text="unreadCount"
                            x-show="unreadCount > 0"
                        >
                            {{ $unreadCount }}
                        </span>
                    </a>
                </nav>
            </div>
        </div>

        <!-- Bottom Sidebar: Profil Saya, Keluar, dan Help Card -->
        <div class="px-[16px] pb-[18px] pt-[8px] space-y-[4px]">
            <!-- Profil Saya -->
            <a href="{{ route('admin.profil') }}" class="h-[38px] px-[12px] flex items-center gap-[12px] rounded-[7px] text-[13px] font-medium text-[#252525] hover:bg-[#F8F8F9] transition group">
                <svg class="w-[18px] h-[18px] text-[#58595B] group-hover:text-[#252525] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
                <span>Profil Saya</span>
            </a>

            <!-- Keluar -->
            @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full h-[38px] px-[12px] flex items-center gap-[12px] rounded-[7px] text-[13px] font-medium text-[#B01116] hover:bg-[#FCE8E8]/50 transition text-left cursor-pointer">
                        <svg class="w-[18px] h-[18px] text-[#B01116] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                            <polyline points="16 17 21 12 16 7"/>
                            <line x1="21" y1="12" x2="9" y2="12"/>
                        </svg>
                        <span>Keluar</span>
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="w-full h-[38px] px-[12px] flex items-center gap-[12px] rounded-[7px] text-[13px] font-medium text-[#B01116] hover:bg-[#FCE8E8]/50 transition text-left">
                    <svg class="w-[18px] h-[18px] text-[#B01116] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                        <polyline points="10 17 15 12 10 7"/>
                        <line x1="15" y1="12" x2="3" y2="12"/>
                    </svg>
                    <span>Masuk Akun</span>
                </a>
            @endauth

            <!-- Help Card (216px x 110px, background #B01116, border-radius 8px) -->
            <div class="w-[216px] min-h-[108px] mx-auto bg-[#B01116] rounded-[8px] p-[13px] text-white flex flex-col justify-between mt-[10px]">
                <div>
                    <h4 class="text-[13px] font-semibold text-white leading-none">Butuh bantuan?</h4>
                    <p class="text-[10px] font-normal text-white/85 leading-snug mt-[6px]">Hubungi pusat layanan CampusVoice.</p>
                </div>
                <a href="{{ route('pusat.bantuan') }}" class="mt-[10px] w-full py-[6px] bg-white text-[#B01116] text-[11px] font-semibold rounded-[6px] text-center hover:bg-gray-50 transition block leading-none">
                    Pusat Bantuan
                </a>
            </div>
        </div>
    </aside>

    <!-- Overlay Mobile -->
    <div x-show="mobileSidebarOpen" x-cloak @click="mobileSidebarOpen = false" class="fixed inset-0 z-30 bg-black/40 lg:hidden"></div>

    <!-- ========================================================= -->
    <!-- MAIN WRAPPER (Di sebelah kanan Sidebar fixed) -->
    <!-- ========================================================= -->
    <div class="lg:pl-[248px] min-h-screen flex flex-col">

        <!-- ========================================================= -->
        <!-- HEADER (Tinggi 78px, Background Putih) -->
        <!-- ========================================================= -->
        <header class="h-[78px] bg-white border-b border-[#E5E7EB] px-[32px] flex items-center justify-between shrink-0 sticky top-0 z-20">
            <div class="flex items-center gap-[16px]">
                <!-- Mobile toggle button -->
                <button @click="mobileSidebarOpen = !mobileSidebarOpen" class="p-2 -ml-2 rounded-lg text-[#58595B] hover:text-[#252525] hover:bg-[#F8F8F9] lg:hidden" type="button" aria-label="Buka menu navigasi">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <!-- Search bar (360px x 45px, background #F7F7F8, border 1px #E5E7EB, border-radius 8px) -->
                <div class="relative w-[240px] sm:w-[320px] md:w-[360px]">
                    <input
                        type="text"
                        placeholder="Cari nomor tiket atau laporan..."
                        onkeydown="if(event.key === 'Enter'){ window.location.href='{{ route('laporan.riwayat') }}?q=' + encodeURIComponent(this.value); }"
                        class="w-full h-[45px] bg-[#F7F7F8] border border-[#E5E7EB] rounded-[8px] px-[14px] text-[13px] text-[#252525] placeholder-[#9CA3AF] focus:outline-none focus:border-[#B01116] focus:bg-white transition"
                    />
                </div>
            </div>

            <!-- Header Kanan (Lonceng notifikasi badge 3, Avatar SR, Nama Siti Rahmawati, Role Admin SSC) -->
            <div class="flex items-center gap-[18px]">
                <!-- Lonceng Notifikasi -->
                <a href="{{ route('admin.notifikasi') }}" class="relative w-[38px] h-[38px] rounded-full flex items-center justify-center text-[#252525] hover:bg-[#F8F8F9] transition" aria-label="Notifikasi">
                    <svg class="w-5 h-5 text-[#252525]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
                        <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                    </svg>
                    <span
                        class="absolute top-[6px] right-[6px] w-[15px] h-[15px] rounded-full bg-[#B01116] text-white text-[9px] font-bold flex items-center justify-center"
                        x-text="unreadCount"
                        x-show="unreadCount > 0"
                    >
                        {{ $unreadCount }}
                    </span>
                </a>

                <!-- Profil Pengguna -->
                <a href="{{ route('admin.profil') }}" class="flex items-center gap-[10px] group">
                    <div class="w-[34px] h-[34px] rounded-[6px] bg-[#FCE8E8] text-[#B01116] font-semibold text-[12px] flex items-center justify-center shrink-0">
                        {{ $authInitials }}
                    </div>
                    <div class="hidden sm:block text-left">
                        <span class="text-[13px] font-semibold text-[#252525] leading-tight block group-hover:text-[#B01116] transition">{{ $authName }}</span>
                        <span class="text-[11px] font-normal text-[#6B7280] leading-tight block mt-[1px]">{{ $authRole }}</span>
                    </div>
                </a>
            </div>
        </header>

        <!-- ========================================================= -->
        <!-- MAIN CONTENT (Background Gradient Merah, Padding 32px) -->
        <!-- ========================================================= -->
        <main class="flex-1 main-gradient-bg p-[32px]">
            <div class="max-w-[1220px] mx-auto">

                <!-- Page Header: Judul (28px font-weight 400), Subtitle (14px), Tombol "Tandai semua telah dibaca" (42px) -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-[16px] mb-[24px]">
                    <div>
                        <h1 class="text-[28px] font-normal text-white tracking-tight leading-tight">Notifikasi</h1>
                        <p class="text-[14px] font-normal text-white/85 mt-[4px]">Pembaruan penting terkait laporan dan aktivitas akun Anda.</p>
                    </div>

                    <div>
                        <button
                            type="button"
                            @click="markAllAsRead()"
                            class="h-[42px] px-[18px] bg-white hover:bg-gray-50 text-[#252525] text-[14px] font-medium rounded-[9px] shadow-none transition cursor-pointer flex items-center justify-center active:scale-95"
                        >
                            Tandai semua telah dibaca
                        </button>
                    </div>
                </div>

                <!-- Layout Dua Kolom: Notification Card (~920px) & Information Card (~280px), Gap 18px -->
                <div class="flex flex-col xl:flex-row items-start gap-[18px]">

                    <!-- ===================================================== -->
                    <!-- NOTIFICATION CARD (Kolom Utama ~920px, Background Putih) -->
                    <!-- ===================================================== -->
                    <div class="w-full xl:w-[920px] max-w-[920px] bg-white rounded-[10px] p-[20px] shadow-sm">

                        <!-- Tab Header (Semua & Belum dibaca, jarak antar tab 30px) -->
                        <div class="flex items-center gap-[30px] border-b border-[#E5E7EB] pb-[12px] mb-[4px]">
                            <!-- Tab: Semua -->
                            <button
                                type="button"
                                @click="activeTab = 'all'"
                                class="text-[14px] transition flex items-center gap-[6px] relative pb-[12px] -mb-[13px] cursor-pointer"
                                :class="activeTab === 'all' ? 'text-[#B01116] font-semibold border-b-[2px] border-[#B01116]' : 'text-[#6B7280] font-medium hover:text-[#252525]'"
                            >
                                <span>Semua</span>
                                <span x-text="totalCount">{{ count($notifItems) }}</span>
                            </button>

                            <!-- Tab: Belum dibaca -->
                            <button
                                type="button"
                                @click="activeTab = 'unread'"
                                class="text-[14px] transition flex items-center gap-[6px] relative pb-[12px] -mb-[13px] cursor-pointer"
                                :class="activeTab === 'unread' ? 'text-[#B01116] font-semibold border-b-[2px] border-[#B01116]' : 'text-[#6B7280] font-medium hover:text-[#252525]'"
                            >
                                <span>Belum dibaca</span>
                                <span x-text="unreadCount">{{ $unreadCount }}</span>
                            </button>
                        </div>

                        <!-- Daftar Item Notifikasi (Border 1px solid #E5E7EB antar item) -->
                        <div class="divide-y divide-[#E5E7EB]">
                            <template x-for="item in filteredItems" :key="item.id">
                                <a
                                    :href="item.url"
                                    class="py-[16px] px-[4px] flex items-start gap-[14px] hover:bg-[#F8F8F9]/70 rounded-[6px] transition cursor-pointer group block"
                                >
                                    <!-- Icon Container (40px x 40px, background #F4F6F8, border-radius 9px) -->
                                    <div class="w-[40px] h-[40px] rounded-[9px] bg-[#F4F6F8] flex items-center justify-center shrink-0 text-[#58595B]">
                                        <!-- Item 1: Progres laporan -->
                                        <template x-if="item.icon === 'progress'">
                                            <svg class="w-5 h-5 text-[#58595B]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"/>
                                                <path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z"/>
                                                <path d="M9 12H4s.55-3.03 2-4.5c1.45-1.47 4.5-2 4.5-2"/>
                                                <path d="M12 9V4s3.03.55 4.5 2c1.47 1.45 2 4.5 2 4.5"/>
                                            </svg>
                                        </template>

                                        <!-- Item 2: Laporan telah diteruskan -->
                                        <template x-if="item.icon === 'forward'">
                                            <svg class="w-5 h-5 text-[#58595B]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                <line x1="5" y1="12" x2="19" y2="12"/>
                                                <polyline points="12 5 19 12 12 19"/>
                                            </svg>
                                        </template>

                                        <!-- Item 3: Tanggapan baru dari unit -->
                                        <template x-if="item.icon === 'response'">
                                            <svg class="w-5 h-5 text-[#58595B]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                                <polyline points="14 2 14 8 20 8"/>
                                                <line x1="16" y1="13" x2="8" y2="13"/>
                                                <line x1="16" y1="17" x2="8" y2="17"/>
                                            </svg>
                                        </template>

                                        <!-- Item 4: Laporan telah selesai -->
                                        <template x-if="item.icon === 'completed'">
                                            <svg class="w-5 h-5 text-[#58595B]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="20 6 9 17 4 12"/>
                                            </svg>
                                        </template>

                                        <!-- Item 5: Laporan berhasil dikirim -->
                                        <template x-if="item.icon === 'submitted'">
                                            <svg class="w-5 h-5 text-[#58595B]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                                <path d="m9 12 2 2 4-4"/>
                                            </svg>
                                        </template>
                                    </div>

                                    <!-- Content (Title ~14px font-semibold, Description ~14px font-normal, Time ~10px font-normal) -->
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-[14px] font-semibold text-[#252525] leading-tight" x-text="item.title"></h3>
                                        <p class="text-[14px] font-normal text-[#58595B] mt-[3px] leading-snug" x-text="item.desc"></p>
                                        <p class="text-[10px] font-normal text-[#6B7280] mt-[4px]" x-text="item.time"></p>
                                    </div>

                                    <!-- Unread Dot Merah (Hanya jika unread) -->
                                    <div class="shrink-0 pt-[4px] pl-[8px]">
                                        <template x-if="item.unread">
                                            <span class="block w-[7px] h-[7px] rounded-full bg-[#B01116]"></span>
                                        </template>
                                    </div>
                                </a>
                            </template>

                            <!-- State Kosong jika tidak ada item belum dibaca -->
                            <div x-show="filteredItems.length === 0" x-cloak class="py-[40px] px-[16px] text-center">
                                <div class="w-10 h-10 rounded-full bg-[#F4F6F8] flex items-center justify-center mx-auto text-[#6B7280] mb-[10px]">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
                                        <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                                    </svg>
                                </div>
                                <p class="text-[14px] font-semibold text-[#252525]">Tidak ada notifikasi belum dibaca</p>
                                <p class="text-[12px] font-normal text-[#6B7280] mt-[2px]">Semua notifikasi Anda telah ditandai sebagai dibaca.</p>
                            </div>
                        </div>

                    </div>

                    <!-- ===================================================== -->
                    <!-- INFORMATION CARD (Kolom Kanan ~280px x 145px, Background Putih) -->
                    <!-- ===================================================== -->
                    <div class="w-full xl:w-[280px] bg-white rounded-[10px] p-[20px] shadow-sm shrink-0 min-h-[145px]">
                        <!-- Icon Notification Merah -->
                        <div class="w-[20px] h-[20px] text-[#B01116] mb-[12px]">
                            <svg class="w-[20px] h-[20px] text-[#B01116]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
                                <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                            </svg>
                        </div>

                        <!-- Title -->
                        <h2 class="text-[15px] font-semibold text-[#252525] leading-tight">
                            Tetap terinformasi
                        </h2>

                        <!-- Description -->
                        <p class="text-[12px] font-normal text-[#58595B] leading-[1.6] mt-[8px]">
                            Notifikasi dikirim untuk perubahan status, tanggapan, penyelesaian, dan pembukaan kembali laporan.
                        </p>
                    </div>

                </div>

            </div>
        </main>

    </div>
</body>
</html>
