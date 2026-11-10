@php
    $authUser = $authUser ?? Auth::user() ?? \App\Models\User::first();
    $authName = $authUser ? $authUser->name : 'Pengguna';
    $authRole = ($authUser && $authUser->role) ? $authUser->role : 'Mahasiswa';
    $authNim = ($authUser && $authUser->nim) ? $authUser->nim : '-';
    $authProdi = ($authUser && $authUser->prodi) ? $authUser->prodi : '-';

    $authWords = preg_split('/\s+/', trim($authName));
    $authInitials = '';
    foreach ($authWords as $w) { $authInitials .= mb_strtoupper(mb_substr($w, 0, 1)); }
    $authInitials = substr($authInitials, 0, 2) ?: 'CV';

    $authFirstName = $authWords[0] ?? $authName;
    $hariIni = now()->locale('id')->isoFormat('dddd, D MMMM YYYY');

    $unreadCount = $unreadCount ?? \App\Models\AppNotification::where('user_id', $authUser?->id)->where('is_read', false)->count();
    $totalLaporan = $totalLaporan ?? \App\Models\Laporan::where('user_id', $authUser?->id)->count();
    $menungguVerifikasi = $menungguVerifikasi ?? \App\Models\Laporan::where('user_id', $authUser?->id)->where('status', 'Menunggu Verifikasi')->count();
    $sedangDiproses = $sedangDiproses ?? \App\Models\Laporan::where('user_id', $authUser?->id)->whereIn('status', ['Diproses', 'Diteruskan'])->count();
    $laporanSelesai = $laporanSelesai ?? \App\Models\Laporan::where('user_id', $authUser?->id)->where('status', 'Selesai')->count();
    $laporanTerbaru = $laporanTerbaru ?? \App\Models\Laporan::where('user_id', $authUser?->id)->orderBy('created_at', 'desc')->take(5)->get();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#FFFFFF]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Dashboard Mahasiswa - CampusVoice Telkom University Surabaya</title>

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
        searchQuery: '',
        handleSearch() {
            if (this.searchQuery.trim()) {
                window.location.href = '{{ route('laporan.riwayat') }}?q=' + encodeURIComponent(this.searchQuery.trim());
            }
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
                    <img src="{{ asset('images/logo-campusvoice.png') }}" alt="CampusVoice Logo" class="w-8 h-8 object-contain shrink-0" onerror="this.onerror=null; this.src='https://placehold.co/32x32/B01116/white?text=CV';" />
                    <div>
                        <span class="text-[17px] font-extrabold text-[#B01116] tracking-tight">CampusVoice</span>
                        <p class="text-[10px] text-[#6B7280] font-medium leading-tight">Telkom University Surabaya</p>
                    </div>
                </div>

                <!-- Navigasi Menu -->
                <div class="px-4 py-3 flex-1">
                    <p class="px-3 text-[11px] font-bold text-[#252525] tracking-wider mb-2 uppercase">{{ $authRole }}</p>

                    <nav class="space-y-1">
                        <!-- Menu 1: Dashboard (AKTIF) -->

                        <a 
                            href="{{ route('dashboard') }}" 
                            aria-current="page" 

                        <a
                            href="{{ url('/dashboard') }}"
                            @click="backToDashboard()"
                            aria-current="page"

                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold bg-[#FCE8E8] text-[#B01116] transition"
                        >
                            <svg class="w-5 h-5 text-[#B01116]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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

                        <!-- Menu 4: Notifikasi -->
                        <a href="{{ route('notifikasi') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-medium text-[#252525] hover:bg-gray-50 transition group">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#252525]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
                                    <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                                </svg>
                                <span>Notifikasi</span>
                            </div>
                            @if ($unreadCount > 0)
                                <span class="inline-flex items-center justify-center px-2 py-0.5 text-[11px] font-bold text-[#FFFFFF] bg-[#B01116] rounded-full">
                                    {{ $unreadCount }}
                                </span>
                            @endif
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

            <!-- Footer Sidebar -->
            <div class="p-4 border-t border-[#E5E7EB] space-y-3">
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-sm font-semibold text-[#B01116] hover:bg-[#FCE8E8] rounded-xl transition">
                            <svg class="w-5 h-5 text-[#B01116]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                <polyline points="16 17 21 12 16 7"/>
                                <line x1="21" y1="12" x2="9" y2="12"/>
                            </svg>
                            <span>Keluar</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="w-full flex items-center gap-2 px-3 py-2 text-sm font-semibold text-[#B01116] hover:bg-[#FCE8E8] rounded-xl transition">
                        <svg class="w-5 h-5 text-[#B01116]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                            <polyline points="10 17 15 12 10 7"/>
                            <line x1="15" y1="12" x2="3" y2="12"/>
                        </svg>
                        <span>Masuk Akun</span>
                    </a>
                @endauth

                <div class="rounded-xl bg-[#B01116] p-4 text-[#FFFFFF] shadow-sm">
                    <h4 class="text-sm font-bold tracking-tight">Butuh bantuan?</h4>

                    <p class="text-[11px] text-[#FFFFFF]/85 mt-1 leading-snug">Hubungi pusat layanan Student Service Center.</p>
                    <a href="https://wa.me/62811333000" target="_blank" class="mt-3 inline-block py-1.5 px-3 bg-[#FFFFFF] hover:bg-gray-100 text-[#B01116] text-xs font-bold rounded-lg text-center transition">

                    <p class="text-[11px] text-[#FFFFFF]/85 mt-1 leading-snug">Hubungi pusat layanan CampusVoice.</p>
                    <a href="{{ route('pusat.bantuan') }}" class="mt-3 inline-block py-1.5 px-3 bg-[#FFFFFF] hover:bg-gray-100 text-[#B01116] text-xs font-bold rounded-lg text-center transition">

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
                        <form @submit.prevent="handleSearch()">
                            <input
                                type="text"
                                x-model="searchQuery"
                                placeholder="Cari nomor tiket atau laporan..."
                                class="w-full bg-[#FFFFFF] border border-[#E5E7EB] rounded-lg py-2 px-3.5 text-xs sm:text-sm text-[#252525] placeholder-[#6B7280] focus:border-[#B01116] focus:outline-none focus:ring-1 focus:ring-[#B01116] transition"
                            />
                        </form>
                    </div>
                </div>

                <div class="flex items-center gap-4 sm:gap-5">
                    <a href="{{ route('notifikasi') }}" class="relative w-9 h-9 rounded-full border border-[#E5E7EB] flex items-center justify-center text-[#6B7280] hover:text-[#252525] hover:bg-gray-50 transition">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
                            <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                        </svg>
                        @if ($unreadCount > 0)
                            <span class="absolute -top-1 -right-1 flex items-center justify-center min-w-4 h-4 px-1 text-[10px] font-bold text-[#FFFFFF] bg-[#B01116] rounded-full ring-2 ring-[#FFFFFF]">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </a>

                    <a href="{{ route('profil') }}" class="flex items-center gap-2.5 group">
                        <div class="w-9 h-9 rounded-lg bg-[#FCE8E8] text-[#B01116] font-bold text-xs flex items-center justify-center shrink-0 group-hover:ring-2 group-hover:ring-[#B01116]/30 transition">
                            {{ $authInitials }}
                        </div>
                        <div class="hidden sm:block text-left">
                            <p class="text-xs font-bold text-[#252525] leading-tight group-hover:text-[#B01116] transition">{{ $authFirstName }}</p>
                            <p class="text-[11px] text-[#6B7280] leading-tight">{{ $authRole }}</p>
                        </div>
                    </a>
                </div>
            </header>

            <!-- ========================================================= -->
            <!-- KONTEN UTAMA: DASHBOARD (Gradient BG)                     -->
            <!-- ========================================================= -->
            <main class="flex-1 content-gradient-bg px-4 sm:px-8 lg:px-10 py-8">
                <div class="max-w-7xl mx-auto space-y-6">

                    <!-- Sapaan (Kiri) & Tombol Buat Laporan Baru (Kanan) -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <p class="text-[11px] sm:text-xs font-bold tracking-wider uppercase text-white/80 mb-1">
                                {{ strtoupper($hariIni) }}
                            </p>
                            <h1 class="text-2xl sm:text-3xl lg:text-[32px] font-extrabold text-white tracking-tight">
                                Halo, {{ $authFirstName }}!
                            </h1>
                            <p class="text-xs sm:text-sm text-white/90 mt-1">
                                Pantau laporan dan sampaikan aspirasi Anda melalui CampusVoice.
                            </p>
                        </div>

                        <div>
                            <a
                                href="{{ route('laporan.create') }}"
                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#FFFFFF] hover:bg-gray-50 text-xs sm:text-sm font-bold text-[#B01116] shadow-md transition cursor-pointer"
                            >
                                <svg class="w-4 h-4 text-[#B01116]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="12" y1="5" x2="12" y2="19"/>
                                    <line x1="5" y1="12" x2="19" y2="12"/>
                                </svg>
                                <span>Buat Laporan Baru</span>
                            </a>
                        </div>
                    </div>

                    <!-- Grid 4 Kartu Statistik Dinamis -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                        <!-- Kartu 1: Total Laporan -->
                        <div class="bg-[#FFFFFF] rounded-2xl p-5 shadow-xl flex items-center gap-4 transition hover:shadow-2xl">
                            <div class="w-12 h-12 rounded-xl bg-[#FCE8E8] flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-[#B01116]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <polyline points="14 2 14 8 20 8"/>
                                    <line x1="16" y1="13" x2="8" y2="13"/>
                                    <line x1="16" y1="17" x2="8" y2="17"/>
                                    <polyline points="10 9 9 9 8 9"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-[#6B7280]">Total Laporan</p>
                                <p class="text-2xl sm:text-3xl font-extrabold text-[#252525] mt-0.5 leading-none">{{ $totalLaporan }}</p>
                            </div>
                        </div>

                        <!-- Kartu 2: Menunggu Verifikasi -->
                        <div class="bg-[#FFFFFF] rounded-2xl p-5 shadow-xl flex items-center gap-4 transition hover:shadow-2xl">
                            <div class="w-12 h-12 rounded-xl bg-[#FFF7ED] flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-[#D97706]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/>
                                    <polyline points="12 6 12 12 16 14"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-[#6B7280]">Menunggu Verifikasi</p>
                                <p class="text-2xl sm:text-3xl font-extrabold text-[#252525] mt-0.5 leading-none">{{ $menungguVerifikasi }}</p>
                            </div>
                        </div>

                        <!-- Kartu 3: Sedang Diproses -->
                        <div class="bg-[#FFFFFF] rounded-2xl p-5 shadow-xl flex items-center gap-4 transition hover:shadow-2xl">
                            <div class="w-12 h-12 rounded-xl bg-[#EFF6FF] flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-[#2563EB]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="18" r="3"/>
                                    <circle cx="6" cy="6" r="3"/>
                                    <circle cx="18" cy="6" r="3"/>
                                    <path d="M18 9v1a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V9"/>
                                    <path d="M12 12v3"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-[#6B7280]">Sedang Diproses</p>
                                <p class="text-2xl sm:text-3xl font-extrabold text-[#252525] mt-0.5 leading-none">{{ $sedangDiproses }}</p>
                            </div>
                        </div>

                        <!-- Kartu 4: Laporan Selesai -->
                        <div class="bg-[#FFFFFF] rounded-2xl p-5 shadow-xl flex items-center gap-4 transition hover:shadow-2xl">
                            <div class="w-12 h-12 rounded-xl bg-[#ECFDF5] flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-[#16A34A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-[#6B7280]">Laporan Selesai</p>
                                <p class="text-2xl sm:text-3xl font-extrabold text-[#252525] mt-0.5 leading-none">{{ $laporanSelesai }}</p>
                            </div>
                        </div>

                    </div>

                    <!-- Tabel: Laporan Terbaru (Kartu Putih Besar) -->
                    <div class="bg-[#FFFFFF] rounded-2xl shadow-xl p-5 sm:p-6">

                        <!-- Header Tabel -->
                        <div class="flex items-center justify-between pb-5 border-b border-[#E5E7EB]">
                            <div>
                                <h2 class="text-base sm:text-lg font-bold text-[#252525] tracking-tight">
                                    Laporan Terbaru
                                </h2>
                                <p class="text-xs sm:text-sm text-[#6B7280] mt-0.5">
                                    Perkembangan laporan yang terakhir Anda kirimkan.
                                </p>
                            </div>

                            <a
                                href="{{ route('laporan.riwayat') }}"
                                class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold text-[#B01116] hover:text-[#8e0d12] transition"
                            >
                                <span>Lihat semua</span>
                                <svg class="w-4 h-4 text-[#B01116]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="5" y1="12" x2="19" y2="12"/>
                                    <polyline points="12 5 19 12 12 19"/>
                                </svg>
                            </a>
                        </div>

                        @if ($laporanTerbaru->isNotEmpty())
                            <!-- Tabel Responsif -->
                            <div class="overflow-x-auto mt-2">
                                <table class="w-full text-left text-xs sm:text-sm">
                                    <thead>
                                        <tr class="border-b border-[#E5E7EB] text-[#6B7280] font-semibold">
                                            <th class="py-3 px-3">Nomor Tiket</th>
                                            <th class="py-3 px-3">Tanggal</th>
                                            <th class="py-3 px-3">Judul Laporan</th>
                                            <th class="py-3 px-3 hidden md:table-cell">Kategori</th>
                                            <th class="py-3 px-3 hidden lg:table-cell">Unit Penangan</th>
                                            <th class="py-3 px-3">Status</th>
                                            <th class="py-3 px-3 text-right">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[#E5E7EB]">
                                        @foreach ($laporanTerbaru as $lap)
                                            @php $badge = $lap->status_badge; @endphp
                                            <tr class="hover:bg-gray-50/70 transition">
                                                <td class="py-3.5 px-3 font-bold text-[#252525] whitespace-nowrap">
                                                    {{ $lap->nomor_tiket }}
                                                </td>
                                                <td class="py-3.5 px-3 text-[#6B7280] whitespace-nowrap">
                                                    {{ $lap->formatted_tanggal }}
                                                </td>
                                                <td class="py-3.5 px-3 font-semibold text-[#252525]">
                                                    <a href="{{ route('laporan.detail', $lap->nomor_tiket) }}" class="hover:text-[#B01116] transition line-clamp-1">
                                                        {{ $lap->judul }}
                                                    </a>
                                                </td>
                                                <td class="py-3.5 px-3 text-[#6B7280] hidden md:table-cell whitespace-nowrap">
                                                    <span class="px-2 py-0.5 rounded-md bg-gray-100 text-xs font-medium text-gray-700">
                                                        {{ $lap->short_kategori }}
                                                    </span>
                                                </td>
                                                <td class="py-3.5 px-3 text-[#6B7280] hidden lg:table-cell">
                                                    {{ $lap->unit_penangan }}
                                                </td>
                                                <td class="py-3.5 px-3 whitespace-nowrap">
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold" style="color: {{ $badge['text'] }}; background-color: {{ $badge['bg'] }};">
                                                        <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $badge['dot'] }};"></span>
                                                        {{ $lap->status }}
                                                    </span>
                                                </td>
                                                <td class="py-3.5 px-3 text-right whitespace-nowrap">
                                                    <a
                                                        href="{{ route('laporan.detail', $lap->nomor_tiket) }}"
                                                        class="inline-flex items-center gap-1 text-xs font-bold text-[#B01116] hover:text-[#8e0d12] transition"
                                                    >
                                                        <span>Detail</span>
                                                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                            <line x1="5" y1="12" x2="19" y2="12"/>
                                                            <polyline points="12 5 19 12 12 19"/>
                                                        </svg>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <!-- Empty State: Belum Ada Laporan -->
                            <div class="py-14 flex flex-col items-center justify-center text-center">
                                <div class="w-14 h-14 rounded-2xl bg-[#F9FAFB] border border-[#E5E7EB] flex items-center justify-center mb-4">
                                    <svg class="w-7 h-7 text-[#D1D5DB]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                        <polyline points="14 2 14 8 20 8"/>
                                        <line x1="16" y1="13" x2="8" y2="13"/>
                                        <line x1="16" y1="17" x2="8" y2="17"/>
                                        <polyline points="10 9 9 9 8 9"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-bold text-[#252525]">Belum ada laporan</p>
                                <p class="text-xs text-[#6B7280] mt-1 max-w-xs leading-relaxed">
                                    Anda belum pernah mengajukan laporan. Mulai buat laporan pertama Anda sekarang.
                                </p>
                                <a
                                    href="{{ route('laporan.create') }}"
                                    class="mt-5 inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#B01116] hover:bg-[#970e13] text-white text-xs font-bold shadow transition"
                                >
                                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="12" y1="5" x2="12" y2="19"/>
                                        <line x1="5" y1="12" x2="19" y2="12"/>
                                    </svg>
                                    Buat Laporan Pertama
                                </a>
                            </div>
                        @endif

                    </div>

                    <!-- Banner Privasi & Perlindungan Identitas -->
                    <div class="bg-[#FFFFFF] rounded-2xl shadow-xl p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-[#EFF6FF] text-[#2563EB] flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-[#2563EB]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                    <path d="m9 12 2 2 4-4"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-[#252525]">
                                    Identitas Anda tetap terlindungi
                                </h3>
                                <p class="text-xs text-[#6B7280] mt-0.5 leading-relaxed">
                                    Pada laporan anonim, identitas hanya dapat dilihat Admin SSC untuk verifikasi dan tidak ditampilkan kepada petugas unit.
                                </p>
                            </div>
                        </div>

                        <div class="sm:shrink-0">
                            <a
                                href="{{ route('laporan.create') }}"
                                class="inline-flex items-center gap-1 text-xs sm:text-sm font-bold text-[#B01116] hover:text-[#8e0d12] transition whitespace-nowrap"
                            >
                                <span>Pelajari saat membuat laporan</span>
                                <svg class="w-4 h-4 text-[#B01116]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="5" y1="12" x2="19" y2="12"/>
                                    <polyline points="12 5 19 12 12 19"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                </div>
            </main>

        </div>
    </div>
</body>
</html>
