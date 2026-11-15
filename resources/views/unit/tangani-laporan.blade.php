<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#FFFFFF]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Tangani Laporan {{ $laporan['tiket'] }} - Petugas Unit CampusVoice</title>

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
        status: '{{ $laporan['status'] }}',
        progres: {{ $laporan['progres'] }},
        catatan: '',
        actionType: 'simpan',
        selectedFileName: '',
        previewModalOpen: false,
        simulasikanPenyelesaian() {
            this.status = 'Selesai';
            this.progres = 100;
            if (!this.catatan.trim()) {
                this.catatan = 'Perbaikan dan penanganan laporan telah selesai dilakukan dan diverifikasi oleh tim unit.';
            }
        }
    }"
>
    <div class="h-screen overflow-hidden flex flex-col lg:flex-row">

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
                            href="#"
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

                        <!-- Menu 2: Laporan Ditugaskan (AKTIF) -->
                        <a
                            href="{{ route('unit.laporan-ditugaskan') }}"
                            aria-current="page"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold bg-[#FCE8E8] text-[#B01116] transition shadow-xs"
                        >
                            <svg class="w-5 h-5 text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                                <line x1="16" y1="13" x2="8" y2="13"/>
                                <line x1="16" y1="17" x2="8" y2="17"/>
                            </svg>
                            <span>Laporan Ditugaskan</span>
                        </a>

                        <!-- Menu 3: Riwayat Penanganan -->
                        <a
                            href="{{ route('petugas.riwayat') }}"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#252525] hover:bg-gray-100 hover:text-[#B01116] transition group"
                        >
                            <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="20" x2="12" y2="10"/>
                                <line x1="18" y1="20" x2="18" y2="4"/>
                                <line x1="6" y1="20" x2="6" y2="16"/>
                            </svg>
                            <span>Riwayat Penanganan</span>
                        </a>

                        <!-- Menu 4: Notifikasi -->
                        <a
                            href="{{ route('unit.notifikasi') }}"
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

                        <!-- Menu 5: Profil Unit -->
                        <a
                            href="{{ route('unit.profil') }}"
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
        <!-- AREA UTAMA (Header + Konten Tangani Laporan) -->
        <!-- ========================================================= -->
        <div class="flex-1 flex flex-col min-w-0 min-h-0 overflow-hidden">

            <!-- HEADER ATAS (NAVBAR) -->
            <header class="bg-[#FFFFFF] border-b border-[#E5E7EB] px-4 sm:px-8 py-3.5 flex items-center justify-between sticky top-0 z-30">
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
                    <form action="{{ route('unit.laporan-ditugaskan') }}" method="GET" class="relative w-56 sm:w-80 md:w-96">
                        <input
                            type="text"
                            name="q"
                            placeholder="Cari nomor tiket atau laporan..."
                            class="w-full bg-[#F9FAFB] border border-[#E5E7EB] rounded-xl py-2 px-3.5 text-xs sm:text-sm text-[#252525] placeholder-[#9CA3AF] focus:bg-[#FFFFFF] focus:border-[#B01116] focus:outline-none focus:ring-1 focus:ring-[#B01116] transition"
                        />
                    </form>
                </div>

                <!-- Bagian Kanan: Lonceng Notifikasi & Profil Petugas Unit -->
                <div class="flex items-center gap-3 sm:gap-4">
                    <!-- Tombol Lonceng Notifikasi -->
                    <a
                        href="{{ route('unit.notifikasi') }}"
                        class="relative w-9 h-9 rounded-xl border border-[#E5E7EB] flex items-center justify-center text-[#4B5563] hover:text-[#B01116] hover:bg-gray-50 transition"
                        title="Notifikasi"
                    >
                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-[#B01116] text-[#FFFFFF] text-[10px] font-bold flex items-center justify-center">
                            {{ $unreadCount }}
                        </span>
                    </a>

                    <!-- Profil Petugas Unit: Budi Santoso -->
                    <a href="{{ route('unit.profil') }}" class="flex items-center gap-2.5 group">
                        <div class="w-9 h-9 rounded-xl bg-[#FCE8E8] text-[#B01116] font-bold text-xs flex items-center justify-center shrink-0">
                            {{ $authUser->initials ?? 'BS' }}
                        </div>
                        <div class="hidden sm:block text-left">
                            <p class="text-xs font-bold text-[#252525] leading-tight group-hover:text-[#B01116] transition">
                                {{ $authUser->name ?? 'Budi Santoso' }}
                            </p>
                            <p class="text-[11px] text-[#6B7280] leading-tight">
                                {{ $authUser->role ?? 'Petugas Unit' }}
                            </p>
                        </div>
                    </a>
                </div>
            </header>

            <!-- ========================================================= -->
            <!-- KONTEN UTAMA: DETAIL & PENANGANAN LAPORAN -->
            <!-- ========================================================= -->
            <main class="flex-1 min-h-0 overflow-y-auto content-gradient-bg px-4 sm:px-8 lg:px-10 py-7">
                <div class="max-w-7xl mx-auto space-y-5">

                    <!-- Baris Kembali & Nomor Tiket -->
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <a
                            href="{{ route('unit.laporan-ditugaskan') }}"
                            class="inline-flex items-center gap-2 text-xs sm:text-sm font-semibold text-white/90 hover:text-white transition"
                        >
                            <span>&larr;</span>
                            <span>Kembali ke Laporan Ditugaskan</span>
                        </a>
                        <span class="px-3 py-1 rounded-full bg-white/15 text-white text-xs font-bold tracking-wide">
                            {{ $laporan['tiket'] }}
                        </span>
                    </div>

                    <!-- Flash Message Sukses -->
                    @if (session('success'))
                        <div class="bg-[#ECFDF5] border border-[#A7F3D0] text-[#065F46] px-4 py-3 rounded-xl text-sm font-semibold flex items-center justify-between shadow-sm">
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    <!-- Grid 2 Kolom: Kiri (Detail + Riwayat) & Kanan (Pembaruan Penanganan) -->
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                        <!-- ================================================= -->
                        <!-- KOLOM KIRI (8 Kolom) -->
                        <!-- ================================================= -->
                        <div class="lg:col-span-8 space-y-6">

                            <!-- KARTU 1: DETAIL LAPORAN -->
                            <div class="bg-[#FFFFFF] rounded-2xl p-6 shadow-xl border border-[#E5E7EB]">
                                <!-- Badge Baris Atas -->
                                <div class="flex flex-wrap items-center gap-2">
                                    <!-- Badge Status -->
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold"
                                        :class="{
                                            'bg-[#F3E8FF] text-[#7C3AED]': status === 'Diproses',
                                            'bg-[#EFF6FF] text-[#2563EB]': status === 'Diteruskan',
                                            'bg-[#ECFDF5] text-[#16A34A]': status === 'Selesai',
                                            'bg-[#FEF3C7] text-[#D97706]': status === 'Menunggu Verifikasi'
                                        }"
                                    >
                                        <span
                                            class="w-1.5 h-1.5 rounded-full"
                                            :class="{
                                                'bg-[#7C3AED]': status === 'Diproses',
                                                'bg-[#2563EB]': status === 'Diteruskan',
                                                'bg-[#16A34A]': status === 'Selesai',
                                                'bg-[#D97706]': status === 'Menunggu Verifikasi'
                                            }"
                                        ></span>
                                        <span x-text="status">{{ $laporan['status'] }}</span>
                                    </span>

                                    <!-- Badge Prioritas -->
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-[#FEE2E2] text-[#B01116]">
                                        {{ $laporan['prioritas'] }}
                                    </span>

                                    <!-- Badge Laporan Anonim (jika anonim) -->
                                    @if ($laporan['is_anonim'])
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[#F1F5F9] text-[#475569]">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#64748B]"></span>
                                            <span>Laporan Anonim</span>
                                        </span>
                                    @endif
                                </div>

                                <!-- Box Info Kategori, Lokasi, Pelapor -->
                                <div class="mt-4 bg-[#F9FAFB] rounded-xl p-4 sm:p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-[10px] font-bold text-[#6B7280] uppercase tracking-wider">KATEGORI</p>
                                        <p class="text-sm font-bold text-[#252525] mt-1">{{ $laporan['kategori'] }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-[#6B7280] uppercase tracking-wider">LOKASI</p>
                                        <p class="text-sm font-bold text-[#252525] mt-1">{{ $laporan['lokasi'] }}</p>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <p class="text-[10px] font-bold text-[#6B7280] uppercase tracking-wider">PELAPOR</p>
                                        <p class="text-sm font-bold text-[#252525] mt-1">{{ $laporan['pelapor'] }}</p>
                                    </div>
                                </div>

                                <!-- Deskripsi Laporan -->
                                <div class="mt-5">
                                    <h3 class="text-sm font-bold text-[#252525]">Deskripsi laporan</h3>
                                    <p class="text-sm text-[#4B5563] mt-1.5 leading-relaxed">
                                        {{ $laporan['deskripsi'] }}
                                    </p>
                                </div>

                                <!-- Lampiran Bukti -->
                                <div class="mt-5 border border-[#E5E7EB] rounded-xl px-4 py-3 flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-9 h-9 rounded-lg bg-[#F9FAFB] border border-[#E5E7EB] flex items-center justify-center text-[#4B5563] shrink-0">
                                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-bold text-[#252525] truncate">{{ $laporan['berkas_nama'] }}</p>
                                            <p class="text-[11px] text-[#6B7280]">{{ $laporan['berkas_meta'] }}</p>
                                        </div>
                                    </div>

                                    @if ($laporan['berkas_url'])
                                        <a
                                            href="{{ $laporan['berkas_url'] }}"
                                            target="_blank"
                                            class="text-xs sm:text-sm font-bold text-[#B01116] hover:text-[#8C0D11] hover:underline shrink-0"
                                        >
                                            Lihat
                                        </a>
                                    @else
                                        <button
                                            type="button"
                                            @click="previewModalOpen = true"
                                            class="text-xs sm:text-sm font-bold text-[#B01116] hover:text-[#8C0D11] hover:underline shrink-0 cursor-pointer"
                                        >
                                            Lihat
                                        </button>
                                    @endif
                                </div>
                            </div>

                            <!-- KARTU 2: RIWAYAT DAN TANGGAPAN -->
                            <div class="bg-[#FFFFFF] rounded-2xl p-6 shadow-xl border border-[#E5E7EB]">
                                <h3 class="text-base font-bold text-[#252525]">Riwayat dan Tanggapan</h3>

                                <div class="mt-5 space-y-4">
                                    @foreach ($riwayats as $r)
                                        <div class="flex items-start gap-3.5 pb-4 border-b border-[#F3F4F6] last:border-b-0 last:pb-0">
                                            <div class="w-9 h-9 rounded-xl bg-[#FCE8E8] text-[#B01116] font-bold text-xs flex items-center justify-center shrink-0">
                                                {{ $r['inisial'] }}
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex flex-wrap items-center gap-2">
                                                    <span class="text-sm font-bold text-[#252525]">{{ $r['nama'] }}</span>
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-[#F1F5F9] text-[#475569]">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-[#64748B]"></span>
                                                        <span>{{ $r['role'] }}</span>
                                                    </span>
                                                </div>
                                                <p class="text-sm text-[#4B5563] mt-1 leading-relaxed">
                                                    {{ $r['pesan'] }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        </div>

                        <!-- ================================================= -->
                        <!-- KOLOM KANAN: PEMBARUAN PENANGANAN (4 Kolom) -->
                        <!-- ================================================= -->
                        <div class="lg:col-span-4">
                            <div class="bg-[#FFFFFF] rounded-2xl p-6 shadow-xl border border-[#E5E7EB]">
                                <h3 class="text-base font-bold text-[#252525]">Pembaruan Penanganan</h3>
                                <p class="text-xs text-[#6B7280] mt-0.5">Perubahan akan tercatat dalam riwayat laporan.</p>

                                <form
                                    method="POST"
                                    action="{{ route('unit.laporan-ditugaskan.update', $laporan['tiket']) }}"
                                    enctype="multipart/form-data"
                                    class="mt-5 space-y-4"
                                >
                                    @csrf
                                    <input type="hidden" name="action_type" x-model="actionType">

                                    <!-- Status Laporan -->
                                    <div>
                                        <label for="status" class="block text-xs font-bold text-[#252525] mb-1.5">
                                            Status Laporan <span class="text-[#B01116]">*</span>
                                        </label>
                                        <select
                                            id="status"
                                            name="status"
                                            x-model="status"
                                            class="w-full bg-[#FFFFFF] border border-[#E5E7EB] rounded-xl py-2.5 px-3.5 text-sm font-medium text-[#252525] focus:border-[#B01116] focus:outline-none focus:ring-1 focus:ring-[#B01116] transition cursor-pointer"
                                        >
                                            <option value="Diproses">Diproses</option>
                                            <option value="Diteruskan">Diteruskan</option>
                                            <option value="Selesai">Selesai</option>
                                            <option value="Menunggu Verifikasi">Menunggu Verifikasi</option>
                                        </select>
                                    </div>

                                    <!-- Persentase Progres -->
                                    <div>
                                        <div class="flex items-center justify-between mb-1.5">
                                            <label for="progres" class="text-xs font-bold text-[#252525]">
                                                Persentase Progres: <span class="text-[#B01116]" x-text="progres + '%'"></span>
                                            </label>
                                        </div>
                                        <input
                                            id="progres"
                                            name="progres"
                                            type="range"
                                            min="0"
                                            max="100"
                                            step="5"
                                            x-model="progres"
                                            class="w-full h-1.5 bg-[#E5E7EB] rounded-lg appearance-none cursor-pointer accent-[#B01116]"
                                        />
                                    </div>

                                    <!-- Catatan Progres -->
                                    <div>
                                        <label for="catatan" class="block text-xs font-bold text-[#252525] mb-1.5">
                                            Catatan Progres <span class="text-[#B01116]">*</span>
                                        </label>
                                        <textarea
                                            id="catatan"
                                            name="catatan"
                                            rows="3"
                                            x-model="catatan"
                                            placeholder="Jelaskan tindakan yang telah dilakukan..."
                                            class="w-full bg-[#FFFFFF] border border-[#E5E7EB] rounded-xl py-2.5 px-3.5 text-sm text-[#252525] placeholder-[#9CA3AF] focus:border-[#B01116] focus:outline-none focus:ring-1 focus:ring-[#B01116] transition resize-none"
                                        ></textarea>
                                    </div>

                                    <!-- Unggah Bukti Penyelesaian -->
                                    <div>
                                        <label
                                            for="bukti_penyelesaian"
                                            class="block border-2 border-dashed border-[#E5E7EB] hover:border-[#B01116]/60 hover:bg-[#FCE8E8]/20 rounded-xl p-5 text-center cursor-pointer transition"
                                        >
                                            <div class="flex justify-center text-[#B01116] mb-1.5">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                                </svg>
                                            </div>
                                            <p class="text-xs font-bold text-[#252525]" x-text="selectedFileName ? selectedFileName : 'Unggah Bukti Penyelesaian'"></p>
                                            <p class="text-[11px] text-[#6B7280] mt-0.5">Wajib sebelum menandai selesai</p>
                                            <input
                                                id="bukti_penyelesaian"
                                                name="bukti_penyelesaian"
                                                type="file"
                                                class="hidden"
                                                @change="selectedFileName = $event.target.files[0] ? $event.target.files[0].name : ''"
                                            />
                                        </label>
                                    </div>

                                    <!-- Tombol Utama -->
                                    <div class="space-y-2.5 pt-1">
                                        <button
                                            type="submit"
                                            @click="actionType = 'simpan'"
                                            class="w-full py-2.5 px-4 rounded-xl bg-[#B01116] hover:bg-[#8C0D11] text-[#FFFFFF] text-sm font-bold shadow-sm transition cursor-pointer"
                                        >
                                            Simpan Pembaruan
                                        </button>

                                        <button
                                            type="submit"
                                            @click="actionType = 'tanggapan'"
                                            class="w-full py-2.5 px-4 rounded-xl bg-[#FFFFFF] border border-[#E5E7EB] hover:bg-gray-50 text-[#252525] text-sm font-bold transition cursor-pointer"
                                        >
                                            Kirim Tanggapan
                                        </button>
                                    </div>

                                    <div class="border-t border-[#E5E7EB] pt-4 space-y-3">
                                        <button
                                            type="submit"
                                            @click="actionType = 'kembalikan'"
                                            class="w-full py-2.5 px-4 rounded-xl bg-[#FCE8E8] hover:bg-[#F8D1D1] text-[#B01116] text-sm font-bold transition cursor-pointer"
                                        >
                                            Kembalikan ke SSC
                                        </button>

                                        <button
                                            type="button"
                                            @click="simulasikanPenyelesaian()"
                                            class="w-full text-center text-xs font-bold text-[#B01116] hover:underline transition cursor-pointer"
                                        >
                                            Simulasikan penyelesaian
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </main>
        </div>

    </div>

    <!-- Modal Preview Lampiran Sampel -->
    <div
        x-show="previewModalOpen"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-xs p-4"
        @click.self="previewModalOpen = false"
    >
        <div class="bg-[#FFFFFF] rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between">
                <h4 class="text-sm font-bold text-[#252525]">Pratinjau Lampiran</h4>
                <button type="button" @click="previewModalOpen = false" class="text-[#6B7280] hover:text-[#252525] cursor-pointer">&times;</button>
            </div>
            <div class="rounded-xl bg-[#F9FAFB] border border-[#E5E7EB] p-6 text-center">
                <p class="text-sm font-bold text-[#252525]">{{ $laporan['berkas_nama'] }}</p>
                <p class="text-xs text-[#6B7280] mt-1">{{ $laporan['berkas_meta'] }}</p>
            </div>
            <button
                type="button"
                @click="previewModalOpen = false"
                class="w-full py-2 rounded-xl bg-[#B01116] text-[#FFFFFF] text-xs font-bold cursor-pointer"
            >
                Tutup
            </button>
        </div>
    </div>
</body>
</html>

