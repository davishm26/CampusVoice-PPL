@php
    $authUser = $authUser ?? Auth::user() ?? \App\Models\User::first();
    $authName = $authUser ? $authUser->name : 'Pengguna';
    $authRole = ($authUser && $authUser->role) ? $authUser->role : 'Mahasiswa';
    $authNim = ($authUser && $authUser->nim) ? $authUser->nim : '-';

    $authWords = preg_split('/\s+/', trim($authName));
    $authInitials = '';
    foreach ($authWords as $w) { $authInitials .= mb_strtoupper(mb_substr($w, 0, 1)); }
    $authInitials = substr($authInitials, 0, 2) ?: 'CV';

    $authFirstName = $authWords[0] ?? $authName;
    $unreadCount = $unreadCount ?? \App\Models\AppNotification::where('user_id', $authUser?->id)->where('is_read', false)->count();

    // Ambil data laporan
    if (!isset($laporan) || !$laporan) {
        $tiketParam = $tiket ?? request()->route('tiket');
        $laporan = $tiketParam 
            ? \App\Models\Laporan::with(['tanggapans', 'trackings', 'user'])->where('nomor_tiket', $tiketParam)->first()
            : \App\Models\Laporan::with(['tanggapans', 'trackings', 'user'])->latest()->first();
    }

    $tiket = $laporan ? $laporan->nomor_tiket : '-';
    $statusBadge = $laporan ? $laporan->status_badge : ['text' => '#7C3AED', 'bg' => '#F5F3FF', 'dot' => '#7C3AED'];
    $trackings = $laporan ? $laporan->trackings : collect();
    $tanggapans = $laporan ? $laporan->tanggapans : collect();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#FFFFFF]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Detail Laporan {{ $tiket }} - CampusVoice Telkom University Surabaya</title>

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
    x-data="{ mobileSidebarOpen: false }"
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
                        <!-- Menu 1: Dashboard -->
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-[#252525] hover:bg-gray-50 transition group">
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

                        <!-- Menu 3: Riwayat Laporan (Aktif) -->
                        <a href="{{ route('laporan.riwayat') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold bg-[#FCE8E8] text-[#B01116] transition">
                            <svg class="w-5 h-5 text-[#B01116]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
                            onkeydown="if(event.key === 'Enter'){ window.location.href='{{ route('laporan.riwayat') }}?q=' + encodeURIComponent(this.value); }"
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
            <!-- KONTEN: DETAIL LAPORAN (Background Gradient)              -->
            <!-- ========================================================= -->
            <main class="flex-1 content-gradient-bg px-4 sm:px-8 lg:px-10 py-8">
                <div class="max-w-7xl mx-auto space-y-6">

                    <!-- Alert Sukses Flash -->
                    @if (session('success'))
                        <div class="rounded-xl bg-emerald-500/90 text-white p-4 shadow-lg backdrop-blur-xs flex items-center gap-3">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-sm font-semibold">{{ session('success') }}</span>
                        </div>
                    @endif

                    <!-- Header Detail Laporan -->
                    <div>
                        <!-- Tombol Kembali ke Riwayat / Dashboard -->
                        <a
                            href="{{ route('laporan.riwayat') }}"
                            class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-lg bg-black/20 hover:bg-black/30 text-white text-xs font-semibold mb-3 transition cursor-pointer backdrop-blur-xs"
                        >
                            <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="19" y1="12" x2="5" y2="12"/>
                                <polyline points="12 19 5 12 12 5"/>
                            </svg>
                            <span>Kembali ke Riwayat Laporan</span>
                        </a>

                        <!-- Nomor Tiket -->
                        <p class="text-xs sm:text-sm font-semibold tracking-wider text-white/90 uppercase">
                            {{ $tiket }}
                        </p>

                        <!-- Judul Besar -->
                        <h1 class="text-2xl sm:text-3xl lg:text-[34px] font-extrabold text-white tracking-tight mt-1">
                            {{ $laporan ? $laporan->judul : 'Detail Laporan' }}
                        </h1>

                        <!-- Waktu Pengiriman -->
                        <p class="text-xs sm:text-sm text-white/85 mt-1.5">
                            Dikirim pada {{ $laporan ? $laporan->formatted_date_time : '-' }}
                        </p>
                    </div>

                    <!-- Grid 2 Kolom -->
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                        <!-- KOLOM KIRI (2 KARTU BESAR) -->
                        <div class="lg:col-span-8 space-y-6">

                            <!-- KARTU 1: Detail Utama -->
                            <div class="bg-[#FFFFFF] rounded-2xl shadow-xl p-6 sm:p-7 space-y-5">

                                <!-- Baris Badge & Progres -->
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                    <div class="flex items-center gap-2.5 flex-wrap">
                                        <!-- Badge Status -->
                                        <span 
                                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold"
                                            style="color: {{ $statusBadge['text'] }}; background-color: {{ $statusBadge['bg'] }};"
                                        >
                                            <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $statusBadge['dot'] }};"></span>
                                            {{ $laporan ? $laporan->status : 'Menunggu Verifikasi' }}
                                        </span>

                                        <!-- Badge Prioritas -->
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold text-[#B01116] bg-[#FCE8E8]">
                                            {{ $laporan ? $laporan->prioritas : 'Normal' }}
                                        </span>
                                    </div>

                                    <!-- Teks Progres -->
                                    <p class="text-xs sm:text-sm text-[#6B7280]">
                                        Progres <span class="font-extrabold text-[#252525]">{{ $laporan ? $laporan->progres : 0 }}%</span>
                                    </p>
                                </div>

                                <!-- Progress Bar -->
                                <div class="w-full bg-[#E5E7EB] h-2 rounded-full overflow-hidden">
                                    <div class="bg-[#B01116] h-full rounded-full transition-all duration-300" style="width: {{ $laporan ? $laporan->progres : 0 }}%;"></div>
                                </div>

                                <!-- Grid 2x2 Info -->
                                <div class="bg-[#F9FAFB] rounded-xl p-4 sm:p-5 border border-[#F3F4F6] grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-[11px] font-semibold uppercase tracking-wider text-[#6B7280]">KATEGORI</p>
                                        <p class="text-sm font-bold text-[#252525] mt-1">{{ $laporan ? $laporan->short_kategori : '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[11px] font-semibold uppercase tracking-wider text-[#6B7280]">LOKASI</p>
                                        <p class="text-sm font-bold text-[#252525] mt-1">{{ $laporan ? $laporan->lokasi : '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[11px] font-semibold uppercase tracking-wider text-[#6B7280]">UNIT PENANGAN</p>
                                        <p class="text-sm font-bold text-[#252525] mt-1">{{ $laporan ? $laporan->unit_penangan : 'Belum Ditentukan' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[11px] font-semibold uppercase tracking-wider text-[#6B7280]">PELAPOR</p>
                                        <p class="text-sm font-bold text-[#252525] mt-1">{{ $laporan ? $laporan->display_pelapor : '-' }}</p>
                                    </div>
                                </div>

                                <!-- Deskripsi Laporan -->
                                <div class="pt-1">
                                    <h3 class="text-sm font-bold text-[#252525] mb-2">Deskripsi laporan</h3>
                                    <p class="text-xs sm:text-sm text-[#6B7280] leading-relaxed whitespace-pre-line">
                                        {{ $laporan ? $laporan->deskripsi : '-' }}
                                    </p>
                                </div>

                                <!-- Bukti Pendukung -->
                                <div class="pt-1">
                                    <h3 class="text-sm font-bold text-[#252525] mb-2">Bukti pendukung</h3>
                                    @if ($laporan && ($laporan->berkas_bukti || $laporan->berkas_nama))
                                        <div class="border border-[#E5E7EB] rounded-xl p-3.5 flex items-center justify-between hover:bg-gray-50/70 transition">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center shrink-0 text-[#6B7280]">
                                                    <svg class="w-5 h-5 text-[#6B7280]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                                        <polyline points="14 2 14 8 20 8"/>
                                                        <line x1="16" y1="13" x2="8" y2="13"/>
                                                        <line x1="16" y1="17" x2="8" y2="17"/>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-xs sm:text-sm font-bold text-[#252525]">{{ $laporan->berkas_nama ?: 'Berkas_Lampiran_Laporan' }}</p>
                                                    <p class="text-[11px] text-[#6B7280] mt-0.5">{{ $laporan->berkas_ukuran ?: 'Dokumen pendukung' }}</p>
                                                </div>
                                            </div>

                                            @if ($laporan->berkas_bukti)
                                                <a
                                                    href="{{ asset('storage/' . $laporan->berkas_bukti) }}"
                                                    target="_blank"
                                                    class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold text-[#B01116] hover:text-[#8e0d12] transition cursor-pointer"
                                                >
                                                    <svg class="w-4 h-4 text-[#B01116]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                                        <circle cx="12" cy="12" r="3"/>
                                                    </svg>
                                                    <span>Lihat Berkas</span>
                                                </a>
                                            @else
                                                <span class="text-xs text-gray-500 italic">Terlampir</span>
                                            @endif
                                        </div>
                                    @else
                                        <div class="border border-dashed border-[#E5E7EB] rounded-xl p-4 text-center">
                                            <p class="text-xs text-[#6B7280]">Tidak ada berkas bukti pendukung yang dilampirkan pada laporan ini.</p>
                                        </div>
                                    @endif
                                </div>

                            </div>

                            <!-- KARTU 2: Diskusi dan Tanggapan Dinamis -->
                            <div class="bg-[#FFFFFF] rounded-2xl shadow-xl p-6 sm:p-7 space-y-6">

                                <!-- Header Diskusi -->
                                <div>
                                    <h2 class="text-base sm:text-lg font-bold text-[#252525] tracking-tight">
                                        Diskusi dan Tanggapan
                                    </h2>
                                    <p class="text-xs sm:text-sm text-[#6B7280] mt-0.5">
                                        Komunikasi langsung terkait penanganan laporan.
                                    </p>
                                </div>

                                <!-- Daftar Tanggapan / Chat -->
                                <div class="space-y-5">
                                    @forelse ($tanggapans as $tanggapan)
                                        <div>
                                            <div class="flex items-start justify-between gap-3">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-9 h-9 rounded-xl bg-[#FCE8E8] text-[#B01116] font-bold text-xs flex items-center justify-center shrink-0">
                                                        {{ $tanggapan->initials }}
                                                    </div>
                                                    <div class="flex items-center gap-2 flex-wrap">
                                                        <p class="text-sm font-bold text-[#252525]">{{ $tanggapan->nama_pengirim }}</p>
                                                        @php
                                                            $roleColor = match ($tanggapan->role_pengirim) {
                                                                'Admin SSC' => ['text' => '#2563EB', 'bg' => '#EFF6FF', 'dot' => '#2563EB'],
                                                                'Petugas Unit' => ['text' => '#4B5563', 'bg' => '#F3F4F6', 'dot' => '#6B7280'],
                                                                default => ['text' => '#B01116', 'bg' => '#FCE8E8', 'dot' => '#B01116'],
                                                            };
                                                        @endphp
                                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold" style="color: {{ $roleColor['text'] }}; background-color: {{ $roleColor['bg'] }};">
                                                            <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $roleColor['dot'] }};"></span>
                                                            {{ $tanggapan->role_pengirim }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <span class="text-xs text-[#6B7280] shrink-0">{{ $tanggapan->formatted_waktu }}</span>
                                            </div>
                                            <p class="text-xs sm:text-sm text-[#252525] mt-2.5 pl-12 leading-relaxed">
                                                {{ $tanggapan->pesan }}
                                            </p>
                                        </div>

                                        @if (!$loop->last)
                                            <div class="border-t border-[#E5E7EB]"></div>
                                        @endif
                                    @empty
                                        <div class="py-6 text-center text-xs text-[#6B7280] bg-[#FAFAFA] rounded-xl border border-dashed border-[#E5E7EB]">
                                            Belum ada tanggapan atau pesan diskusi pada laporan ini.
                                        </div>
                                    @endforelse
                                </div>

                                <!-- Form Tulis Tanggapan Dinamis -->
                                <div class="pt-4 border-t border-[#E5E7EB]">
                                    <form action="{{ route('laporan.tanggapan', $tiket) }}" method="POST">
                                        @csrf
                                        <label for="input-tanggapan-detail" class="block text-sm font-bold text-[#252525] mb-2">
                                            Tulis tanggapan
                                        </label>
                                        <textarea
                                            id="input-tanggapan-detail"
                                            name="pesan"
                                            rows="3"
                                            required
                                            placeholder="Tulis pesan yang relevan dengan laporan..."
                                            class="w-full rounded-xl border border-[#E5E7EB] bg-white p-3.5 text-xs sm:text-sm text-[#252525] placeholder-[#6B7280] focus:border-[#B01116] focus:outline-none focus:ring-1 focus:ring-[#B01116] transition resize-none"
                                        ></textarea>

                                        <div class="mt-3 flex items-center justify-between">
                                            <p class="text-[11px] text-[#6B7280]">Pesan akan terkirim sebagai {{ $authName }} ({{ $authRole }}).</p>
                                            <button
                                                type="submit"
                                                class="px-5 py-2.5 rounded-xl bg-[#B01116] hover:bg-[#8e0d12] text-xs sm:text-sm font-bold text-white shadow-sm transition inline-flex items-center gap-2 cursor-pointer"
                                            >
                                                <span>Kirim Tanggapan</span>
                                            </button>
                                        </div>
                                    </form>
                                </div>

                            </div>

                        </div>

                        <!-- KOLOM KANAN (2 KARTU SAMPING) -->
                        <div class="lg:col-span-4 space-y-6">

                            <!-- KARTU 1: Riwayat Status (Stepper Vertikal Dinamis) -->
                            <div class="bg-[#FFFFFF] rounded-2xl shadow-xl p-6 sm:p-7">
                                <h3 class="text-base font-bold text-[#252525] mb-6">
                                    Riwayat Status
                                </h3>

                                <div class="relative space-y-6">
                                    @forelse ($trackings as $index => $track)
                                        <div class="flex items-start gap-3.5 relative">
                                            @if ($index === 0)
                                                <!-- Step Aktif / Terbaru (Lingkaran Ring Merah) -->
                                                <div class="w-6 h-6 rounded-full border-2 border-[#B01116] flex items-center justify-center bg-white shrink-0 mt-0.5 z-10">
                                                    <span class="w-2 h-2 rounded-full bg-[#B01116]"></span>
                                                </div>
                                            @else
                                                <!-- Step Sebelumnya (Centang Hijau) -->
                                                <div class="w-6 h-6 rounded-full bg-[#DCFCE7] text-[#16A34A] flex items-center justify-center shrink-0 mt-0.5 z-10">
                                                    <svg class="w-3.5 h-3.5 text-[#16A34A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                                        <polyline points="20 6 9 17 4 12"/>
                                                    </svg>
                                                </div>
                                            @endif

                                            @if (!$loop->last)
                                                <!-- Garis vertikal ke bawah -->
                                                <div class="absolute left-3 top-6 w-0.5 bg-[#E5E7EB] h-14 -translate-x-1/2"></div>
                                            @endif

                                            <div>
                                                <h4 class="text-sm font-bold text-[#252525]">{{ $track->status }}</h4>
                                                <p class="text-[11px] text-[#6B7280] mt-0.5">{{ $track->formatted_waktu }}</p>
                                                @if ($track->keterangan)
                                                    <p class="text-xs text-[#6B7280] mt-1 leading-snug">
                                                        {{ $track->keterangan }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-xs text-[#6B7280]">Belum ada riwayat status.</p>
                                    @endforelse
                                </div>
                            </div>

                            <!-- KARTU 2: Aksi Lanjutan & Penilaian Dinamis -->
                            <div x-data="{ showReopenModal: false, showRatingModal: false, rating: {{ $laporan && $laporan->rating ? $laporan->rating : 5 }} }" class="bg-[#FFFFFF] rounded-2xl shadow-xl p-6 sm:p-7 space-y-4">
                                <h3 class="text-sm font-bold text-[#252525]">
                                    Aksi & Kepuasan Layanan
                                </h3>

                                @if ($laporan && $laporan->rating)
                                    <!-- Sudah Dinilai -->
                                    <div class="rounded-xl bg-[#F9FAFB] border border-[#E5E7EB] p-4 space-y-2">
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs font-semibold text-[#6B7280]">Penilaian Anda:</span>
                                            <div class="flex items-center text-amber-400">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= $laporan->rating)
                                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                    @else
                                                        <svg class="w-4 h-4 text-gray-300 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                    @endif
                                                @endfor
                                                <span class="ml-1 text-xs font-bold text-[#252525]">({{ $laporan->rating }}/5)</span>
                                            </div>
                                        </div>
                                        @if ($laporan->ulasan)
                                            <p class="text-xs text-[#252525] italic bg-white p-2.5 rounded-lg border border-[#F3F4F6]">
                                                "{{ $laporan->ulasan }}"
                                            </p>
                                        @endif
                                        <button @click="showRatingModal = true" class="text-[11px] font-semibold text-[#B01116] hover:underline">
                                            Perbarui Penilaian
                                        </button>
                                    </div>
                                @else
                                    <p class="text-xs text-[#6B7280] leading-relaxed">
                                        Setelah laporan ditangani, berikan penilaian dan evaluasi Anda untuk meningkatkan mutu layanan kampus.
                                    </p>
                                    <button
                                        type="button"
                                        @click="showRatingModal = true"
                                        class="w-full py-2.5 rounded-xl border border-amber-300 bg-amber-50 hover:bg-amber-100 text-xs sm:text-sm font-bold text-amber-800 shadow-xs text-center transition cursor-pointer flex items-center justify-center gap-2"
                                    >
                                        <svg class="w-4 h-4 fill-amber-500" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        <span>Beri Penilaian Layanan</span>
                                    </button>
                                @endif

                                <div class="pt-2 border-t border-[#F3F4F6]">
                                    <p class="text-[11px] text-[#6B7280] mb-2 leading-relaxed">
                                        Masalah belum terselesaikan atau muncul kembali? Anda dapat mengajukan pembukaan kembali tiket laporan ini.
                                    </p>
                                    <button
                                        type="button"
                                        @click="showReopenModal = true"
                                        class="w-full py-2 rounded-xl border border-[#E5E7EB] bg-[#FFFFFF] hover:bg-gray-50 text-xs font-bold text-[#252525] shadow-xs text-center transition cursor-pointer"
                                    >
                                        Buka Kembali Laporan
                                    </button>
                                </div>

                                <!-- Modal Buka Kembali Laporan -->
                                <div x-show="showReopenModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs">
                                    <div @click.away="showReopenModal = false" class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
                                        <div class="flex items-center justify-between pb-3 border-b border-[#E5E7EB]">
                                            <h4 class="text-sm font-bold text-[#252525]">Buka Kembali Laporan {{ $tiket }}</h4>
                                            <button @click="showReopenModal = false" class="text-gray-400 hover:text-gray-600 text-lg leading-none">&times;</button>
                                        </div>
                                        <form method="POST" action="{{ route('laporan.reopen', $tiket) }}" class="space-y-4">
                                            @csrf
                                            <div>
                                                <label class="block text-xs font-semibold text-[#252525] mb-1.5">Alasan Pembukaan Kembali <span class="text-[#B01116]">*</span></label>
                                                <textarea name="alasan" rows="3" required placeholder="Jelaskan kendala yang masih terjadi setelah penanganan..." class="w-full rounded-xl border border-[#E5E7EB] p-3 text-xs text-[#252525] focus:border-[#B01116] focus:outline-none focus:ring-1 focus:ring-[#B01116]"></textarea>
                                            </div>
                                            <div class="flex items-center justify-end gap-2.5">
                                                <button type="button" @click="showReopenModal = false" class="px-4 py-2 rounded-lg border border-[#E5E7EB] text-xs font-semibold text-[#6B7280] hover:bg-gray-50">Batal</button>
                                                <button type="submit" class="px-4 py-2 rounded-lg bg-[#B01116] hover:bg-[#8e0d12] text-xs font-bold text-white shadow-sm">Kirim Pengajuan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <!-- Modal Penilaian Layanan -->
                                <div x-show="showRatingModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs">
                                    <div @click.away="showRatingModal = false" class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
                                        <div class="flex items-center justify-between pb-3 border-b border-[#E5E7EB]">
                                            <h4 class="text-sm font-bold text-[#252525]">Penilaian Layanan {{ $tiket }}</h4>
                                            <button @click="showRatingModal = false" class="text-gray-400 hover:text-gray-600 text-lg leading-none">&times;</button>
                                        </div>
                                        <form method="POST" action="{{ route('laporan.rating', $tiket) }}" class="space-y-4">
                                            @csrf
                                            <input type="hidden" name="rating" :value="rating">
                                            <div>
                                                <label class="block text-xs font-semibold text-[#252525] mb-2 text-center">Berapa tingkat kepuasan Anda?</label>
                                                <div class="flex items-center justify-center gap-2">
                                                    <template x-for="star in [1, 2, 3, 4, 5]" :key="star">
                                                        <button type="button" @click="rating = star" class="p-1 cursor-pointer transition transform hover:scale-110">
                                                            <svg class="w-8 h-8" :class="star <= rating ? 'text-amber-400 fill-current' : 'text-gray-300 fill-current'" viewBox="0 0 20 20">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                            </svg>
                                                        </button>
                                                    </template>
                                                </div>
                                                <p class="text-center text-xs font-bold text-[#252525] mt-1" x-text="rating + ' dari 5 Bintang'"></p>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-semibold text-[#252525] mb-1.5">Ulasan / Masukan (Opsional)</label>
                                                <textarea name="ulasan" rows="3" placeholder="Tuliskan apresiasi atau saran untuk tim penanganan..." class="w-full rounded-xl border border-[#E5E7EB] p-3 text-xs text-[#252525] focus:border-[#B01116] focus:outline-none focus:ring-1 focus:ring-[#B01116]">{{ $laporan ? $laporan->ulasan : '' }}</textarea>
                                            </div>
                                            <div class="flex items-center justify-end gap-2.5">
                                                <button type="button" @click="showRatingModal = false" class="px-4 py-2 rounded-lg border border-[#E5E7EB] text-xs font-semibold text-[#6B7280] hover:bg-gray-50">Batal</button>
                                                <button type="submit" class="px-4 py-2 rounded-lg bg-[#B01116] hover:bg-[#8e0d12] text-xs font-bold text-white shadow-sm">Kirim Penilaian</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>
            </main>
        </div>
    </div>
</body>
</html>
