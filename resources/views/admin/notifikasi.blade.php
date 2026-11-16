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

    $notifications = $notifications ?? ($authUser?->id ? \App\Models\AppNotification::with('laporan')->where('user_id', $authUser->id)->orderBy('created_at', 'desc')->get() : collect());

    $unreadCount = $notifications->where('is_read', false)->count();
    $notifItems = $notifications->map(function ($n) {
        $laporanStatus = $n->laporan?->status ?? ($n->tiket ? \App\Models\Laporan::where('nomor_tiket', $n->tiket)->value('status') : null);
        $isVerifNotif = in_array($n->title, ['Laporan baru masuk', 'Pengajuan buka kembali laporan'], true);
        $alreadyVerified = $isVerifNotif && $laporanStatus && $laporanStatus !== 'Menunggu Verifikasi';

        return [
            'id' => $n->id,
            'icon' => $n->icon,
            'title' => $n->title,
            'desc' => $n->desc,
            'time' => $n->formatted_time,
            'unread' => !$n->is_read,
            'tiket' => $n->tiket,
            'laporan_status' => $laporanStatus,
            'already_verified' => $alreadyVerified,
            'url' => route('notifikasi.read', $n->id),
        ];
    })->values()->all();
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

        /* Linear Gradient Konten Utama: Merah #B01116 ke Abu-abu Gelap #58595B */
        .content-gradient-bg {
            background: linear-gradient(to top right, #B01116 0%, #58595B 100%);
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
                        <!-- Dashboard -->
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#252525] hover:bg-gray-100 hover:text-[#B01116] transition group">
                            <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="7" height="7"></rect>
                                <rect x="14" y="3" width="7" height="7"></rect>
                                <rect x="14" y="14" width="7" height="7"></rect>
                                <rect x="3" y="14" width="7" height="7"></rect>
                            </svg>
                            <span>Dashboard</span>
                        </a>

                        <!-- Verifikasi Laporan -->
                        <a href="{{ route('admin.verifikasi') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#252525] hover:bg-gray-100 hover:text-[#B01116] transition group">
                            <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                <path d="m9 12 2 2 4-4"/>
                            </svg>
                            <span>Verifikasi Laporan</span>
                        </a>

                        <!-- Monitoring Laporan -->
                        <a href="{{ route('admin.monitoring') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#252525] hover:bg-gray-100 hover:text-[#B01116] transition group">
                            <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="20" x2="12" y2="10"/>
                                <line x1="18" y1="20" x2="18" y2="4"/>
                                <line x1="6" y1="20" x2="6" y2="16"/>
                            </svg>
                            <span>Monitoring Laporan</span>
                        </a>

                        <!-- Rekapitulasi -->
                        <a href="{{ route('admin.rekapitulasi') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#252525] hover:bg-gray-100 hover:text-[#B01116] transition group">
                            <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="7 10 12 15 17 10"/>
                                <line x1="12" y1="15" x2="12" y2="3"/>
                            </svg>
                            <span>Rekapitulasi</span>
                        </a>

                        <!-- Notifikasi (AKTIF) -->
                        <a
                            href="{{ route('admin.notifikasi') }}"
                            aria-current="page"
                            class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-bold bg-[#FCE8E8] text-[#B01116] transition shadow-xs"
                        >
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-[#B01116] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                                    <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                                </svg>
                                <span>Notifikasi</span>
                            </div>
                            <span
                                class="w-5 h-5 rounded-full bg-[#B01116] text-[#FFFFFF] text-[11px] font-bold flex items-center justify-center shrink-0"
                                x-text="unreadCount"
                                x-show="unreadCount > 0"
                            >
                                {{ $unreadCount }}
                            </span>
                        </a>

                        <!-- Profil Saya -->
                        <a href="{{ route('admin.profil') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#252525] hover:bg-gray-100 hover:text-[#B01116] transition group">
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
                    @auth
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#B01116] hover:bg-red-50 transition cursor-pointer text-left">
                                <svg class="w-5 h-5 text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                    <polyline points="16 17 21 12 16 7"/>
                                    <line x1="21" y1="12" x2="9" y2="12"/>
                                </svg>
                                <span>Keluar</span>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#B01116] hover:bg-red-50 transition text-left">
                            <svg class="w-5 h-5 text-[#B01116] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                                <polyline points="10 17 15 12 10 7"/>
                                <line x1="15" y1="12" x2="3" y2="12"/>
                            </svg>
                            <span>Masuk Akun</span>
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Kartu Butuh Bantuan -->
            <div class="p-4">
                <div class="bg-[#B01116] rounded-2xl p-4 text-[#FFFFFF] shadow-lg">
                    <h4 class="font-bold text-sm mb-1">Butuh bantuan?</h4>
                    <p class="text-xs text-white/85 leading-relaxed mb-3">
                        Hubungi pusat layanan CampusVoice.
                    </p>
                    <a href="{{ route('pusat.bantuan') }}" class="block w-full py-2 bg-[#FFFFFF] hover:bg-gray-50 text-[#B01116] text-xs font-bold rounded-xl text-center transition shadow-xs">
                        Pusat Bantuan
                    </a>
                </div>
            </div>
        </aside>

        <!-- Overlay Mobile -->
        <div x-show="mobileSidebarOpen" x-cloak @click="mobileSidebarOpen = false" class="fixed inset-0 z-40 bg-black/50 lg:hidden"></div>

        <!-- ========================================================= -->
        <!-- MAIN WRAPPER -->
        <!-- ========================================================= -->
        <div class="flex-1 flex flex-col min-w-0 min-h-0 overflow-hidden">

            <!-- HEADER -->
            <header class="bg-[#FFFFFF] border-b border-[#E5E7EB] px-4 sm:px-8 py-3.5 flex items-center justify-between sticky top-0 z-30 shadow-xs">
                <div class="flex items-center gap-4">
                    <button @click="mobileSidebarOpen = !mobileSidebarOpen" class="p-2 -ml-2 rounded-lg text-[#6B7280] hover:text-[#252525] hover:bg-gray-100 lg:hidden" type="button" aria-label="Buka menu navigasi">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <div class="relative w-64 sm:w-80 md:w-96">
                        <input
                            type="text"
                            placeholder="Cari nomor tiket atau laporan..."
                            onkeydown="if(event.key === 'Enter'){ window.location.href='{{ route('laporan.riwayat') }}?q=' + encodeURIComponent(this.value); }"
                            class="w-full bg-gray-100 rounded-xl border border-transparent py-2 px-4 text-xs sm:text-sm text-[#252525] placeholder-[#6B7280] focus:border-[#B01116] focus:bg-[#FFFFFF] focus:ring-1 focus:ring-[#B01116] focus:outline-none transition"
                        />
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.notifikasi') }}" class="relative p-2 rounded-lg text-[#6B7280] hover:text-[#B01116] hover:bg-gray-100 transition" aria-label="Notifikasi">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span
                            class="absolute top-1 right-1 w-4 h-4 rounded-full bg-[#B01116] text-[#FFFFFF] text-[10px] font-bold flex items-center justify-center"
                            x-text="unreadCount"
                            x-show="unreadCount > 0"
                        >
                            {{ $unreadCount }}
                        </span>
                    </a>

                    <div class="h-6 w-px bg-[#E5E7EB]"></div>

                    <a href="{{ route('admin.profil') }}" class="flex items-center gap-2.5 group">
                        <div class="w-9 h-9 rounded-xl bg-[#FCE8E8] text-[#B01116] font-bold text-xs flex items-center justify-center shrink-0 ring-1 ring-[#B01116]/20">
                            {{ $authInitials }}
                        </div>
                        <div class="hidden sm:block text-left">
                            <p class="text-xs font-bold text-[#252525] leading-tight group-hover:text-[#B01116] transition">{{ $authName }}</p>
                            <p class="text-[11px] text-[#6B7280] leading-tight">{{ $authRole }}</p>
                        </div>
                    </a>
                </div>
            </header>

            <!-- ========================================================= -->
            <!-- MAIN CONTENT -->
            <!-- ========================================================= -->
            <main class="flex-1 min-h-0 overflow-y-auto content-gradient-bg px-4 sm:px-8 lg:px-10 py-7 text-[#FFFFFF]">
                <div class="max-w-7xl mx-auto space-y-6">

                    <!-- Page Header -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Notifikasi</h1>
                            <p class="text-xs sm:text-sm text-white/90 mt-0.5">Pembaruan penting terkait laporan dan aktivitas akun Anda.</p>
                        </div>

                        <div>
                            <button
                                type="button"
                                @click="markAllAsRead()"
                                class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-[#FFFFFF] hover:bg-gray-50 text-xs sm:text-sm font-bold text-[#B01116] shadow-md transition cursor-pointer"
                            >
                                Tandai semua telah dibaca
                            </button>
                        </div>
                    </div>

                    <!-- Layout Dua Kolom -->
                    <div class="grid grid-cols-1 xl:grid-cols-12 gap-5 items-start">

                        <!-- NOTIFICATION CARD -->
                        <div class="xl:col-span-9 bg-[#FFFFFF] rounded-2xl p-6 shadow-xl text-[#252525]">

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
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <h3 class="text-[14px] font-semibold text-[#252525] leading-tight" x-text="item.title"></h3>
                                            <template x-if="item.already_verified">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-[#ECFDF5] text-[#16A34A] text-[10px] font-bold">
                                                    Sudah diverifikasi (<span x-text="item.laporan_status"></span>)
                                                </span>
                                            </template>
                                        </div>
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
                    <!-- INFORMATION CARD (Kolom Kanan) -->
                    <!-- ===================================================== -->
                    <div class="xl:col-span-3 bg-[#FFFFFF] rounded-2xl p-6 shadow-xl text-[#252525]">
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
    </div>
</body>
</html>
