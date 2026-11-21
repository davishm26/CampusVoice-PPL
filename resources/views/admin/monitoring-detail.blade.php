@php
    $statusBadge = $laporan->status_badge;
    $trackings = $laporan->trackings;
    $tanggapans = $laporan->tanggapans;
    $berkasUrl = $laporan->berkas_url;
    $berkasExt = $laporan->berkas_extension;
    $berkasIsImage = in_array($berkasExt, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
    $berkasIsPdf = $berkasExt === 'pdf';
    $pelaporText = $laporan->is_anonim
        ? 'Identitas disembunyikan'
        : trim(($laporan->user->name ?? 'Mahasiswa') . ($laporan->user?->nim ? ' • ' . $laporan->user->nim : ''));
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#FFFFFF]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Detail Laporan {{ $laporan->nomor_tiket }} - Admin SSC CampusVoice</title>

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

        .content-gradient-bg {
            background: linear-gradient(to top right, #B01116 0%, #58595B 100%);
        }
    </style>
</head>
<body
    class="h-full antialiased text-[#252525] bg-[#B01116]"
    x-data="{ mobileSidebarOpen: false, showPreviewModal: false }"
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
                        <a
                            href="{{ route('admin.dashboard') }}"
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

                        <a
                            href="{{ route('admin.verifikasi') }}"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#252525] hover:bg-gray-100 hover:text-[#B01116] transition group"
                        >
                            <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                <path d="m9 12 2 2 4-4"/>
                            </svg>
                            <span>Verifikasi Laporan</span>
                        </a>

                        <a
                            href="{{ route('admin.monitoring') }}"
                            aria-current="page"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold bg-[#FCE8E8] text-[#B01116] transition shadow-xs"
                        >
                            <svg class="w-5 h-5 text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="20" x2="12" y2="10"/>
                                <line x1="18" y1="20" x2="18" y2="4"/>
                                <line x1="6" y1="20" x2="6" y2="16"/>
                            </svg>
                            <span>Monitoring Laporan</span>
                        </a>

                        <a
                            href="{{ route('admin.rekapitulasi') }}"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#252525] hover:bg-gray-100 hover:text-[#B01116] transition group"
                        >
                            <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="7 10 12 15 17 10"/>
                                <line x1="12" y1="15" x2="12" y2="3"/>
                            </svg>
                            <span>Rekapitulasi</span>
                        </a>

                        <a
                            href="{{ route('admin.notifikasi') }}"
                            class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#252525] hover:bg-gray-100 hover:text-[#B01116] transition group"
                        >
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                                    <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                                </svg>
                                <span>Notifikasi</span>
                            </div>
                            @if (($unreadCount ?? 0) > 0)
                                <span class="w-5 h-5 rounded-full bg-[#B01116] text-[#FFFFFF] text-[11px] font-bold flex items-center justify-center">
                                    {{ $unreadCount }}
                                </span>
                            @endif
                        </a>

                        <a
                            href="{{ route('admin.profil') }}"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#252525] hover:bg-gray-100 hover:text-[#B01116] transition group"
                        >
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
        <!-- AREA UTAMA -->
        <!-- ========================================================= -->
        <div class="flex-1 flex flex-col min-w-0 min-h-0 overflow-hidden">

            <!-- HEADER ATAS -->
            <header class="bg-[#FFFFFF] border-b border-[#E5E7EB] px-4 sm:px-8 py-3.5 flex items-center justify-between sticky top-0 z-30 shadow-xs">
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
                </div>

                <div class="flex items-center gap-3 sm:gap-4 ml-auto">
                    <a
                        href="{{ route('admin.notifikasi') }}"
                        class="relative p-2 rounded-lg border border-[#E5E7EB] text-[#6B7280] hover:text-[#B01116] hover:bg-gray-50 transition cursor-pointer"
                        title="Notifikasi"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        @if (($unreadCount ?? 0) > 0)
                            <span class="absolute -top-1.5 -right-1.5 w-4 h-4 rounded-full bg-[#B01116] text-[#FFFFFF] text-[10px] font-bold flex items-center justify-center shadow-xs">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </a>

                    <a href="{{ route('admin.profil') }}" class="flex items-center gap-2.5 pl-1 group">
                        <div class="w-9 h-9 rounded-lg bg-[#FCE8E8] text-[#B01116] font-bold text-xs flex items-center justify-center shrink-0">
                            {{ $authUser->initials ?? 'SR' }}
                        </div>
                        <div class="hidden sm:block text-left">
                            <p class="text-xs font-bold text-[#252525] leading-tight group-hover:text-[#B01116] transition">{{ $authUser->name ?? 'Siti Rahmawati' }}</p>
                            <p class="text-[11px] text-[#6B7280] leading-tight">{{ $authUser->role ?? 'Admin SSC' }}</p>
                        </div>
                    </a>
                </div>
            </header>

            <!-- ========================================================= -->
            <!-- KONTEN UTAMA: DETAIL LAPORAN -->
            <!-- ========================================================= -->
            <main class="flex-1 min-h-0 overflow-y-auto content-gradient-bg px-4 sm:px-8 lg:px-10 py-8">
                <div class="max-w-7xl mx-auto space-y-6">

                    <!-- Header Detail Laporan -->
                    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                        <div>
                            <a
                                href="{{ route('admin.monitoring') }}"
                                class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-lg bg-black/20 hover:bg-black/30 text-white text-xs font-semibold mb-3 transition cursor-pointer backdrop-blur-xs"
                            >
                                <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="19" y1="12" x2="5" y2="12"/>
                                    <polyline points="12 19 5 12 12 5"/>
                                </svg>
                                <span>Kembali ke Monitoring Laporan</span>
                            </a>

                            <p class="text-xs sm:text-sm font-semibold tracking-wider text-white/90 uppercase">
                                DETAIL LAPORAN &bull; {{ $laporan->nomor_tiket }}
                            </p>

                            <h1 class="text-2xl sm:text-3xl lg:text-[34px] font-extrabold text-white tracking-tight mt-1">
                                {{ $laporan->judul }}
                            </h1>

                            <p class="text-xs sm:text-sm text-white/85 mt-1.5">
                                Dikirim pada {{ $laporan->formatted_date_time }}
                            </p>
                        </div>

                        @if ($laporan->status === 'Menunggu Verifikasi')
                            <a
                                href="{{ route('admin.verifikasi.detail', $laporan->nomor_tiket) }}"
                                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#FFFFFF] hover:bg-gray-50 text-xs sm:text-sm font-bold text-[#B01116] shadow-md transition self-start sm:self-auto"
                            >
                                <span>Verifikasi &amp; Salurkan &rarr;</span>
                            </a>
                        @endif
                    </div>

                    <!-- Grid 2 Kolom -->
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                        <!-- KOLOM KIRI -->
                        <div class="lg:col-span-8 space-y-6">

                            <!-- KARTU 1: Detail Utama -->
                            <div class="bg-[#FFFFFF] rounded-2xl shadow-xl p-6 sm:p-7 space-y-5">

                                <!-- Baris Badge & Progres -->
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                    <div class="flex items-center gap-2.5 flex-wrap">
                                        <span
                                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold"
                                            style="color: {{ $statusBadge['text'] }}; background-color: {{ $statusBadge['bg'] }};"
                                        >
                                            <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $statusBadge['dot'] }};"></span>
                                            {{ $laporan->status }}
                                        </span>

                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold text-[#B01116] bg-[#FCE8E8]">
                                            {{ $laporan->prioritas ?: 'Normal' }}
                                        </span>

                                        @if ($laporan->is_anonim)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#F3F4F6] text-xs font-semibold text-[#252525]">
                                                <span class="w-1.5 h-1.5 rounded-full bg-[#2563EB]"></span>
                                                Laporan Anonim
                                            </span>
                                        @endif
                                    </div>

                                    <p class="text-xs sm:text-sm text-[#6B7280]">
                                        Progres <span class="font-extrabold text-[#252525]">{{ $laporan->display_progres }}%</span>
                                    </p>
                                </div>

                                <!-- Progress Bar -->
                                <div class="w-full bg-[#E5E7EB] h-2 rounded-full overflow-hidden">
                                    <div class="bg-[#B01116] h-full rounded-full transition-all duration-300" style="width: {{ $laporan->display_progres }}%;"></div>
                                </div>

                                <!-- Grid Info 2x2 -->
                                <div class="bg-[#F9FAFB] rounded-xl p-4 sm:p-5 border border-[#F3F4F6] grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-[11px] font-semibold uppercase tracking-wider text-[#6B7280]">KATEGORI</p>
                                        <p class="text-sm font-bold text-[#252525] mt-1">{{ $laporan->short_kategori }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[11px] font-semibold uppercase tracking-wider text-[#6B7280]">LOKASI</p>
                                        <p class="text-sm font-bold text-[#252525] mt-1">{{ $laporan->lokasi }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[11px] font-semibold uppercase tracking-wider text-[#6B7280]">UNIT PENANGAN</p>
                                        <p class="text-sm font-bold text-[#252525] mt-1">{{ $laporan->unit_penangan ?: 'Belum Ditentukan' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[11px] font-semibold uppercase tracking-wider text-[#6B7280]">
                                            PELAPOR
                                        </p>
                                        <p class="text-sm font-bold text-[#252525] mt-1">{{ $pelaporText ?: '-' }}</p>
                                        @if (!$laporan->is_anonim && !empty($laporan->user?->prodi))
                                            <p class="text-[11px] text-[#6B7280] mt-0.5">{{ $laporan->user->prodi }}</p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Deskripsi Laporan -->
                                <div class="pt-1">
                                    <h3 class="text-sm font-bold text-[#252525] mb-2">Deskripsi laporan</h3>
                                    <p class="text-xs sm:text-sm text-[#6B7280] leading-relaxed whitespace-pre-line">
                                        {{ $laporan->deskripsi }}
                                    </p>
                                </div>

                                <!-- Bukti Pendukung -->
                                <div class="pt-1">
                                    <h3 class="text-sm font-bold text-[#252525] mb-2">Bukti pendukung</h3>
                                    @if ($laporan->berkas_bukti || $laporan->berkas_nama)
                                        <div class="border border-[#E5E7EB] rounded-xl p-3.5 space-y-3">
                                            <div class="flex items-center justify-between gap-3">
                                                <div class="flex items-center gap-3 min-w-0">
                                                    @if ($berkasUrl && $berkasIsImage)
                                                        <button type="button" @click="showPreviewModal = true" class="w-12 h-12 rounded-lg overflow-hidden border border-[#E5E7EB] bg-gray-100 shrink-0 cursor-pointer hover:opacity-90 transition">
                                                            <img src="{{ $berkasUrl }}" alt="{{ $laporan->berkas_nama ?: 'Bukti Pendukung' }}" class="w-full h-full object-cover" />
                                                        </button>
                                                    @else
                                                        <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center shrink-0 text-[#6B7280]">
                                                            <svg class="w-5 h-5 text-[#6B7280]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                                                <polyline points="14 2 14 8 20 8"/>
                                                                <line x1="16" y1="13" x2="8" y2="13"/>
                                                                <line x1="16" y1="17" x2="8" y2="17"/>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                    <div class="min-w-0">
                                                        <p class="text-xs sm:text-sm font-bold text-[#252525] truncate">{{ $laporan->berkas_nama ?: 'Berkas_Lampiran_Laporan' }}</p>
                                                        <p class="text-[11px] text-[#6B7280] mt-0.5">{{ $laporan->berkas_ukuran ?: 'Dokumen pendukung' }}</p>
                                                    </div>
                                                </div>

                                                @if ($berkasUrl)
                                                    <button
                                                        type="button"
                                                        @click="showPreviewModal = true"
                                                        class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold text-[#B01116] hover:text-[#8e0d12] transition cursor-pointer shrink-0"
                                                    >
                                                        <svg class="w-4 h-4 text-[#B01116]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                                            <circle cx="12" cy="12" r="3"/>
                                                        </svg>
                                                        <span>Lihat Berkas</span>
                                                    </button>
                                                @else
                                                    <span class="text-xs text-gray-500 italic">Terlampir</span>
                                                @endif
                                            </div>

                                            @if ($berkasUrl && $berkasIsImage)
                                                <button type="button" @click="showPreviewModal = true" class="block w-full rounded-lg overflow-hidden border border-[#E5E7EB] bg-[#F9FAFB] p-2 hover:border-[#B01116]/40 transition cursor-pointer">
                                                    <img src="{{ $berkasUrl }}" alt="{{ $laporan->berkas_nama ?: 'Bukti Pendukung' }}" class="max-h-56 mx-auto rounded-md object-contain" />
                                                </button>
                                            @endif
                                        </div>
                                    @else
                                        <div class="border border-dashed border-[#E5E7EB] rounded-xl p-4 text-center">
                                            <p class="text-xs text-[#6B7280]">Tidak ada berkas bukti pendukung yang dilampirkan pada laporan ini.</p>
                                        </div>
                                    @endif
                                </div>

                            </div>

                            <!-- KARTU 2: Diskusi dan Tanggapan -->
                            <div class="bg-[#FFFFFF] rounded-2xl shadow-xl p-6 sm:p-7 space-y-5">
                                <div>
                                    <h2 class="text-base sm:text-lg font-bold text-[#252525] tracking-tight">
                                        Diskusi dan Tanggapan
                                    </h2>
                                    <p class="text-xs sm:text-sm text-[#6B7280] mt-0.5">
                                        Catatan penanganan dan komunikasi terkait laporan ini.
                                    </p>
                                </div>

                                <div class="space-y-4">
                                    @forelse ($tanggapans as $tanggapan)
                                        @php
                                            $maskAnonim = $laporan->is_anonim && (
                                                $tanggapan->role_pengirim === 'Mahasiswa'
                                                || ($tanggapan->user_id && $tanggapan->user_id === $laporan->user_id)
                                            );
                                            $tInitials = $maskAnonim ? 'PA' : $tanggapan->initials;
                                            $tNama = $maskAnonim ? 'Pelapor Anonim' : $tanggapan->nama_pengirim;
                                            $tRole = $maskAnonim ? 'Pelapor (Anonim)' : $tanggapan->role_pengirim;
                                        @endphp
                                        <div class="pb-4 border-b border-[#F3F4F6] last:border-b-0 last:pb-0">
                                            <div class="flex items-start justify-between gap-3">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-9 h-9 rounded-xl bg-[#FCE8E8] text-[#B01116] font-bold text-xs flex items-center justify-center shrink-0">
                                                        {{ $tInitials }}
                                                    </div>
                                                    <div>
                                                        <div class="flex items-center gap-2 flex-wrap">
                                                            <span class="text-sm font-bold text-[#252525]">{{ $tNama }}</span>
                                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-[#F3F4F6] text-[#252525]">
                                                                <span class="w-1.5 h-1.5 rounded-full bg-[#6B7280]"></span>
                                                                {{ $tRole }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <span class="text-[11px] text-[#6B7280] shrink-0">{{ $tanggapan->formatted_waktu }}</span>
                                            </div>
                                            <p class="text-xs sm:text-sm text-[#4B5563] mt-2 leading-relaxed pl-12">
                                                {{ $tanggapan->pesan }}
                                            </p>
                                        </div>
                                    @empty
                                        <p class="text-xs text-[#6B7280]">Belum ada tanggapan pada laporan ini.</p>
                                    @endforelse
                                </div>
                            </div>

                        </div>

                        <!-- KOLOM KANAN -->
                        <div class="lg:col-span-4 space-y-6">

                            <!-- KARTU: Riwayat Status -->
                            <div class="bg-[#FFFFFF] rounded-2xl shadow-xl p-6 sm:p-7">
                                <h3 class="text-base font-bold text-[#252525] mb-5">Riwayat Status</h3>

                                <div class="space-y-6 relative">
                                    @forelse ($trackings as $index => $track)
                                        <div class="flex items-start gap-3.5 relative">
                                            @if ($index === 0)
                                                <div class="w-6 h-6 rounded-full border-2 border-[#B01116] flex items-center justify-center bg-white shrink-0 mt-0.5 z-10">
                                                    <span class="w-2 h-2 rounded-full bg-[#B01116]"></span>
                                                </div>
                                            @else
                                                <div class="w-6 h-6 rounded-full bg-[#DCFCE7] text-[#16A34A] flex items-center justify-center shrink-0 mt-0.5 z-10">
                                                    <svg class="w-3.5 h-3.5 text-[#16A34A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                                        <polyline points="20 6 9 17 4 12"/>
                                                    </svg>
                                                </div>
                                            @endif

                                            @if (!$loop->last)
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

                            <!-- KARTU: Penilaian Mahasiswa (Jika Ada) -->
                            @if ($laporan->rating)
                                <div class="bg-[#FFFFFF] rounded-2xl shadow-xl p-6 sm:p-7 space-y-3">
                                    <h3 class="text-sm font-bold text-[#252525]">Penilaian Layanan Pelapor</h3>
                                    <div class="flex items-center text-amber-400">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $laporan->rating)
                                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            @else
                                                <svg class="w-4 h-4 text-gray-300 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            @endif
                                        @endfor
                                        <span class="ml-1.5 text-xs font-bold text-[#252525]">({{ $laporan->rating }}/5)</span>
                                    </div>
                                    @if ($laporan->ulasan)
                                        <p class="text-xs text-[#252525] italic bg-[#F9FAFB] p-3 rounded-xl border border-[#F3F4F6]">
                                            "{{ $laporan->ulasan }}"
                                        </p>
                                    @endif
                                </div>
                            @endif

                        </div>

                    </div>

                </div>
            </main>

        </div>
    </div>

    <!-- Modal Pratinjau Bukti Pendukung -->
    @if ($berkasUrl)
        <div
            x-show="showPreviewModal"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs"
            @keydown.escape.window="showPreviewModal = false"
        >
            <div @click.away="showPreviewModal = false" class="bg-[#FFFFFF] rounded-2xl max-w-2xl w-full p-6 shadow-2xl border border-[#E5E7EB]">
                <div class="flex items-center justify-between border-b border-[#E5E7EB] pb-3">
                    <div class="min-w-0">
                        <h3 class="text-sm font-bold text-[#252525]">Pratinjau Bukti Pendukung</h3>
                        <p class="text-[11px] text-[#6B7280] truncate">{{ $laporan->berkas_nama ?: 'Lampiran Bukti' }}{{ $laporan->berkas_ukuran ? ' (' . $laporan->berkas_ukuran . ')' : '' }}</p>
                    </div>
                    <button type="button" @click="showPreviewModal = false" class="p-1 rounded-lg text-[#6B7280] hover:text-[#252525] hover:bg-gray-100 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="my-5 rounded-xl bg-[#F9FAFB] border border-[#E5E7EB] p-4 text-center overflow-hidden">
                    @if ($berkasIsImage)
                        <img src="{{ $berkasUrl }}" alt="{{ $laporan->berkas_nama ?: 'Bukti Pendukung' }}" class="max-h-[28rem] mx-auto rounded-lg object-contain" />
                    @elseif ($berkasIsPdf)
                        <iframe src="{{ $berkasUrl }}" class="w-full h-96 rounded-lg border border-[#E5E7EB]"></iframe>
                    @else
                        <p class="text-sm font-bold text-[#252525]">{{ $laporan->berkas_nama ?: 'Lampiran Bukti' }}</p>
                        <p class="text-xs text-[#6B7280] mt-1">Klik tombol di bawah untuk membuka berkas lampiran.</p>
                    @endif
                </div>
                <div class="flex justify-end gap-2">
                    <a href="{{ $berkasUrl }}" target="_blank" class="px-4 py-2 rounded-xl border border-[#E5E7EB] text-xs font-bold text-[#252525] hover:bg-gray-50 transition">
                        Buka di Tab Baru
                    </a>
                    <button type="button" @click="showPreviewModal = false" class="px-4 py-2 rounded-xl bg-[#B01116] text-xs font-bold text-[#FFFFFF] hover:bg-[#970e13] transition cursor-pointer">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif
</body>
</html>
