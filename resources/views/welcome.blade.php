<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>CampusVoice - Kanal Resmi Aspirasi Mahasiswa Telkom University Surabaya</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

    <!-- Tailwind CSS with Forms Plugin via CDN -->
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#B01116',
                        'primary-hover': '#950e12',
                        maroon: '#B01116',
                        'dark-text': '#252525',
                        'sub-text': '#6B7280',
                        'border-gray': '#E5E7EB',
                        'stat-bg': '#F7F7F8',
                        'pale-pink': '#FCE8E8',
                        'check-green': '#16A34A',
                        'badge-green-bg': '#DCFCE7',
                        'status-purple': '#7C3AED',
                        'status-purple-bg': '#F5F3FF',
                        'bell-blue': '#2563EB',
                        'hero-dark': '#58595B',
                    },
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
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #252525;
            background-color: #FFFFFF;
        }

        /* Diagonal Hero Slice Style (#B01116 ke #58595B) */
        .hero-diagonal-slice {
            clip-path: polygon(18% 0%, 100% 0%, 100% 100%, 0% 100%);
            background: linear-gradient(135deg, #B01116 0%, #7d161a 50%, #58595B 100%);
        }

        @media (min-width: 1024px) {
            .hero-diagonal-slice {
                clip-path: polygon(24% 0%, 100% 0%, 100% 100%, 0% 100%);
            }
        }

        /* Footer Gradient Background (#B01116 ke #58595B) */
        .footer-exact-bg {
            background: linear-gradient(135deg, #4A4B4E 0%, #6E1B1E 45%, #B01116 100%);
        }
    </style>
</head>
<body class="min-h-screen flex flex-col antialiased selection:bg-[#B01116] selection:text-white" x-data="{ mobileMenuOpen: false }">

    <!-- ========================================================= -->
    <!-- 1. NAVBAR (Background #FFFFFF Full-Width)                  -->
    <!-- ========================================================= -->
    <header class="w-full bg-[#FFFFFF] border-b border-[#E5E7EB] sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <!-- Sisi Kiri: Logo merah-putih CampusVoice + Telkom University Surabaya -->
            <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                <img src="{{ asset('images/logo-campusvoice.png') }}"
                     alt="CampusVoice Logo"
                     class="w-9 h-9 object-contain shrink-0"
                     onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-9 h-9 rounded-full bg-[#B01116] flex items-center justify-center shrink-0\'><svg class=\'w-5 h-5 text-white\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'2\' stroke-linecap=\'round\' stroke-linejoin=\'round\'><path d=\'M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z\' fill=\'#B01116\'/><path d=\'M8 9h.01M12 9h.01M16 9h.01\' stroke=\'#FFFFFF\' stroke-width=\'2.5\'/></svg></div>';">
                <div>
                    <span class="text-lg sm:text-xl font-extrabold text-[#252525] tracking-tight block leading-tight">CampusVoice</span>
                    <span class="text-xs font-medium text-[#6B7280] block leading-tight">Telkom University Surabaya</span>
                </div>
            </a>

            <!-- Sisi Tengah: Menu Navigasi Rapi & Berjarak Longgar (#252525) -->
            <nav class="hidden md:flex items-center gap-10 lg:gap-14">
                <a href="{{ url('/') }}" class="text-[15px] font-semibold text-[#252525] hover:text-[#B01116] transition-colors">
                    Beranda
                </a>
                <a href="#alur-penanganan" class="text-[15px] font-medium text-[#252525] hover:text-[#B01116] transition-colors">
                    Tentang
                </a>
                <a href="#statistik" class="text-[15px] font-medium text-[#252525] hover:text-[#B01116] transition-colors">
                    Statistik Pengaduan
                </a>
            </nav>

            <!-- Sisi Kanan: Tombol Merah Solid (#B01116) Masuk dengan Akun Kampus -->
            <div class="hidden md:flex items-center">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}"
                           class="inline-flex items-center justify-center px-6 py-2.5 rounded-xl bg-[#B01116] text-white text-sm font-bold hover:bg-[#950e12] shadow-sm hover:shadow transition-all duration-200">
                            Buka Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center justify-center px-6 py-2.5 rounded-xl bg-[#B01116] text-white text-sm font-bold hover:bg-[#950e12] shadow-sm hover:shadow transition-all duration-200">
                            Masuk dengan Akun Kampus
                        </a>
                    @endauth
                @else
                    <a href="{{ url('/login') }}"
                       class="inline-flex items-center justify-center px-6 py-2.5 rounded-xl bg-[#B01116] text-white text-sm font-bold hover:bg-[#950e12] shadow-sm hover:shadow transition-all duration-200">
                        Masuk dengan Akun Kampus
                    </a>
                @endif
            </div>

            <!-- Hamburger Button for Mobile -->
            <div class="md:hidden flex items-center">
                <button @click="mobileMenuOpen = !mobileMenuOpen"
                        type="button"
                        class="p-2 rounded-lg text-[#252525] hover:bg-gray-100 focus:outline-none"
                        aria-label="Buka menu navigasi">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path x-show="mobileMenuOpen" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Drawer Menu -->
        <div x-show="mobileMenuOpen"
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="md:hidden bg-white border-b border-[#E5E7EB] px-5 pt-3 pb-6 space-y-3">
            <a href="{{ url('/') }}" @click="mobileMenuOpen = false" class="block py-2 text-base font-semibold text-[#252525] hover:text-[#B01116]">
                Beranda
            </a>
            <a href="#alur-penanganan" @click="mobileMenuOpen = false" class="block py-2 text-base font-medium text-[#252525] hover:text-[#B01116]">
                Tentang
            </a>
            <a href="#statistik" @click="mobileMenuOpen = false" class="block py-2 text-base font-medium text-[#252525] hover:text-[#B01116]">
                Statistik Pengaduan
            </a>
            <div class="pt-3">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="w-full inline-flex items-center justify-center px-5 py-3 rounded-xl bg-[#B01116] text-white font-semibold text-center hover:bg-[#950e12]">
                            Buka Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="w-full inline-flex items-center justify-center px-5 py-3 rounded-xl bg-[#B01116] text-white font-semibold text-center hover:bg-[#950e12]">
                            Masuk dengan Akun Kampus
                        </a>
                    @endauth
                @else
                    <a href="{{ url('/login') }}" class="w-full inline-flex items-center justify-center px-5 py-3 rounded-xl bg-[#B01116] text-white font-semibold text-center hover:bg-[#950e12]">
                        Masuk dengan Akun Kampus
                    </a>
                @endif
            </div>
        </div>
    </header>


    <!-- ========================================================= -->
    <!-- 2. HERO SECTION (Diagonal Split Style "1v1" / Gradient)    -->
    <!-- ========================================================= -->
    <section class="relative bg-white overflow-hidden py-14 lg:py-24">
        <!-- Angled Background Backdrop / Gradient "1v1" -->
        <div class="absolute inset-y-0 right-0 w-full lg:w-3/5 hero-diagonal-slice pointer-events-none"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">

                <!-- SISI KIRI: Headline, Deskripsi, CTA, Checklist -->
                <div class="lg:col-span-7 flex flex-col items-start z-10">

                    <!-- Label kecil di atas: "Kanal resmi aspirasi mahasiswa" -->
                    <div class="inline-flex items-center gap-2 mb-5">
                        <!-- Shield Check Icon Merah #B01116 -->
                        <svg class="w-4 h-4 text-[#B01116]" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 1.944A11.954 11.954 0 012.166 5C2.056 5.649 2 6.319 2 7c0 5.225 3.34 9.67 8 11.317C14.66 16.67 18 12.225 18 7c0-.682-.057-1.35-.166-2.001A11.954 11.954 0 0110 1.944zM13.707 8.707a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-xs sm:text-sm font-bold text-[#B01116] tracking-wide">
                            Kanal resmi aspirasi mahasiswa
                        </span>
                    </div>

                    <!-- Headline Besar: "Suara Mahasiswa, Perubahan Nyata." -->
                    <h1 class="text-4xl sm:text-5xl lg:text-[58px] font-extrabold tracking-tight leading-[1.12] mb-6">
                        <span class="text-[#252525] block">Suara Mahasiswa,</span>
                        <span class="text-[#B01116] block mt-1">Perubahan Nyata.</span>
                    </h1>

                    <!-- Deskripsi Paragraf: (#6B7280) -->
                    <p class="text-base sm:text-lg text-[#6B7280] leading-relaxed max-w-xl mb-8">
                        CampusVoice adalah platform terpusat untuk menyampaikan pengaduan dan aspirasi mahasiswa secara terstruktur, transparan, dan mudah dipantau hingga proses penanganan selesai.
                    </p>

                    <!-- Tombol Aksi: Merah #B01116 & Putih Border Abu-abu -->
                    <div class="flex flex-wrap items-center gap-4 mb-8 w-full sm:w-auto">
                        <!-- Tombol Merah: Masuk dengan Akun Kampus + Panah Putih -->
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}"
                                   class="w-full sm:w-auto inline-flex items-center justify-center gap-3 px-6 py-3.5 rounded-xl bg-[#B01116] text-white text-[15px] font-semibold hover:bg-[#950e12] shadow-sm hover:shadow-md transition-all duration-200">
                                    <span>Buka Dashboard</span>
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                                    </svg>
                                </a>
                            @else
                                <a href="{{ route('login') }}"
                                   class="w-full sm:w-auto inline-flex items-center justify-center gap-3 px-6 py-3.5 rounded-xl bg-[#B01116] text-white text-[15px] font-semibold hover:bg-[#950e12] shadow-sm hover:shadow-md transition-all duration-200">
                                    <span>Masuk dengan Akun Kampus</span>
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                                    </svg>
                                </a>
                            @endauth
                        @else
                            <a href="{{ url('/login') }}"
                               class="w-full sm:w-auto inline-flex items-center justify-center gap-3 px-6 py-3.5 rounded-xl bg-[#B01116] text-white text-[15px] font-semibold hover:bg-[#950e12] shadow-sm hover:shadow-md transition-all duration-200">
                                <span>Masuk dengan Akun Kampus</span>
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                                </svg>
                            </a>
                        @endif

                        <!-- Tombol Putih: Lihat Statistik Pengaduan -->
                        <a href="#statistik"
                           class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3.5 rounded-xl bg-white border border-[#E5E7EB] text-[#252525] text-[15px] font-semibold hover:bg-gray-50 hover:border-gray-300 transition-all duration-200">
                            Lihat Statistik Pengaduan
                        </a>
                    </div>

                    <!-- Checklist Bawah: Dua Item dengan Centang Hijau #16A34A -->
                    <div class="flex flex-wrap items-center gap-6 sm:gap-8 pt-2">
                        <!-- Checklist Item 1 -->
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#16A34A] shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                            </svg>
                            <span class="text-sm font-medium text-[#252525]">Akun kampus terverifikasi</span>
                        </div>

                        <!-- Checklist Item 2 -->
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#16A34A] shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                            </svg>
                            <span class="text-sm font-medium text-[#252525]">Status dapat dilacak</span>
                        </div>
                    </div>

                </div>

                <!-- SISI KANAN: Visual Abstrak Glassmorphism (Layered Floating Cards) -->
                <div class="lg:col-span-5 relative mt-10 lg:mt-0 flex justify-center items-center py-6 lg:py-0">

                    <!-- WRAPPER KARTU MELAYANG -->
                    <div class="relative w-full max-w-[440px]">

                        <!-- Layer Belakang: Glassmorphism Frosted Plate -->
                        <div class="absolute -top-6 -left-6 sm:-left-10 w-72 h-44 bg-white/20 backdrop-blur-xl rounded-3xl border border-white/30 shadow-2xl -rotate-6 pointer-events-none"></div>

                        <!-- Floating Badge 1 (Atas Kanan): "Teruskan ke Unit" -->
                        <div class="absolute -top-6 -right-2 sm:-right-6 z-20 bg-white/95 backdrop-blur-md rounded-2xl px-4 py-3 shadow-[0_15px_35px_-5px_rgba(0,0,0,0.25)] border border-white/80 flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-[#DCFCE7] flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-[#16A34A]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-xs sm:text-sm font-bold text-[#252525] leading-tight">Teruskan ke Unit</h4>
                                <p class="text-[11px] text-[#6B7280] leading-tight mt-0.5">Routing laporan terverifikasi</p>
                            </div>
                        </div>

                        <!-- KARTU TERATAS (PALING DEPAN): Kartu Tiket Aktif "Permohonan Perbaikan AC" -->
                        <div class="relative z-10 bg-white/95 backdrop-blur-md rounded-3xl shadow-[0_25px_60px_-12px_rgba(0,0,0,0.35)] border border-white/80 p-6 sm:p-7 space-y-4">

                            <!-- Header Kartu: Mini Logo & Status Verifikasi -->
                            <div class="flex items-center justify-between pb-3 border-b border-[#E5E7EB]/70">
                                <div class="flex items-center gap-2">
                                    <img src="{{ asset('images/logo-campusvoice.png') }}"
                                         alt=""
                                         class="w-6 h-6 object-contain"
                                         onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-6 h-6 rounded-full bg-[#B01116] flex items-center justify-center\'><svg class=\'w-3.5 h-3.5 text-white\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'2\'><path d=\'M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z\' fill=\'#B01116\'/></svg></div>';">
                                    <div>
                                        <span class="text-xs font-bold text-[#B01116] block leading-tight">CampusVoice</span>
                                        <span class="text-[9px] text-[#6B7280] block leading-tight">Telkom University Surabaya</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1.5 text-[11px] font-semibold text-[#16A34A] bg-[#DCFCE7] px-2.5 py-0.5 rounded-full">
                                    <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 1.944A11.954 11.954 0 012.166 5C2.056 5.649 2 6.319 2 7c0 5.225 3.34 9.67 8 11.317C14.66 16.67 18 12.225 18 7c0-.682-.057-1.35-.166-2.001A11.954 11.954 0 0110 1.944zM13.707 8.707a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Terverifikasi</span>
                                </div>
                            </div>

                            <!-- Baris Nomor Tiket & Badge Status Diproses -->
                            <div>
                                <div class="flex items-center justify-between gap-2">
                                    <div>
                                        <p class="text-[10px] font-extrabold text-[#6B7280] tracking-wider uppercase">LAPORAN AKTIF</p>
                                        <p class="text-xs font-bold text-[#252525]">CV-2026-00124</p>
                                    </div>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold text-[#7C3AED] bg-[#F5F3FF]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#7C3AED]"></span>
                                        Diproses
                                    </span>
                                </div>

                                <!-- Judul Laporan & Subtitle -->
                                <h3 class="text-base sm:text-lg font-extrabold text-[#252525] mt-2 leading-snug">
                                    Permohonan Perbaikan AC
                                </h3>
                                <p class="text-xs text-[#6B7280] mt-0.5">
                                    Ruang Kelas A-301 • Satpras
                                </p>
                            </div>

                            <!-- Progress Bar Merah (Progres 65%) -->
                            <div class="space-y-1.5 pt-1">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-[#6B7280] font-medium">Progres penanganan</span>
                                    <span class="text-[#252525] font-extrabold">65%</span>
                                </div>
                                <div class="w-full bg-[#E5E7EB] rounded-full h-2.5 overflow-hidden">
                                    <div class="bg-[#B01116] h-2.5 rounded-full transition-all duration-500" style="width: 65%;"></div>
                                </div>
                            </div>

                            <!-- Step Indikator Alur 4 Steps: Dikirim, Diverifikasi, Diproses, Selesai -->
                            <div class="pt-4 pb-1">
                                <div class="relative">
                                    <!-- Garis Penghubung Track -->
                                    <div class="absolute top-3 left-4 right-4 h-0.5 bg-[#E5E7EB] -translate-y-1/2">
                                        <div class="h-0.5 bg-[#B01116] w-[66%]"></div>
                                    </div>

                                    <!-- Stepper Dots & Label -->
                                    <div class="relative flex justify-between items-start text-center">
                                        <!-- Step 1: Dikirim (Selesai Aktif) -->
                                        <div class="flex flex-col items-center">
                                            <div class="w-6 h-6 rounded-full bg-[#B01116] text-white flex items-center justify-center text-[10px] font-bold shadow-xs">
                                                <svg class="w-3.5 h-3.5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                            <span class="text-[10px] font-bold text-[#252525] mt-1.5">Dikirim</span>
                                        </div>

                                        <!-- Step 2: Diverifikasi (Selesai Aktif) -->
                                        <div class="flex flex-col items-center">
                                            <div class="w-6 h-6 rounded-full bg-[#B01116] text-white flex items-center justify-center text-[10px] font-bold shadow-xs">
                                                <svg class="w-3.5 h-3.5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                            <span class="text-[10px] font-bold text-[#252525] mt-1.5">Diverifikasi</span>
                                        </div>

                                        <!-- Step 3: Diproses (Sedang Aktif) -->
                                        <div class="flex flex-col items-center">
                                            <div class="w-6 h-6 rounded-full bg-white border-2 border-[#B01116] flex items-center justify-center shadow-xs ring-4 ring-[#FCE8E8]">
                                                <div class="w-2 h-2 rounded-full bg-[#B01116]"></div>
                                            </div>
                                            <span class="text-[10px] font-bold text-[#B01116] mt-1.5">Diproses</span>
                                        </div>

                                        <!-- Step 4: Selesai (Belum Aktif) -->
                                        <div class="flex flex-col items-center">
                                            <div class="w-6 h-6 rounded-full bg-white border-2 border-gray-300 flex items-center justify-center shadow-xs">
                                                <div class="w-1.5 h-1.5 rounded-full bg-gray-300"></div>
                                            </div>
                                            <span class="text-[10px] font-medium text-[#6B7280] mt-1.5">Selesai</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Floating Badge 2 (Bawah Kiri): Notifikasi "Progres diperbarui Hari ini, 09.24" -->
                        <div class="absolute -bottom-6 -left-3 sm:-left-8 z-20 bg-white/95 backdrop-blur-md rounded-2xl px-4 py-3 shadow-[0_15px_35px_-5px_rgba(0,0,0,0.25)] border border-white/80 flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-[#FCE8E8] text-[#B01116] flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-[#B01116]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-xs sm:text-sm font-bold text-[#252525] leading-tight">Progres diperbarui</h4>
                                <p class="text-[11px] text-[#6B7280] leading-tight mt-0.5">Hari ini, 09.24</p>
                            </div>
                        </div>

                        <!-- Floating Badge 3 (Bawah Kanan): Circle Checkmark Glass Badge -->
                        <div class="absolute -bottom-4 -right-2 sm:-right-6 z-20 bg-white/95 backdrop-blur-md rounded-full p-2.5 shadow-[0_12px_30px_-5px_rgba(0,0,0,0.25)] border border-white/80 flex items-center justify-center text-[#16A34A]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                            </svg>
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </section>


    <!-- ========================================================= -->
    <!-- 3. SECTION 2: ALUR YANG TRANSPARAN (Background #FFFFFF)    -->
    <!-- ========================================================= -->
    <section id="alur-penanganan" class="bg-[#FFFFFF] py-20 lg:py-24 border-t border-[#E5E7EB]/40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header Section (Center Aligned) -->
            <div class="text-center max-w-3xl mx-auto mb-14 lg:mb-16">
                <!-- Label Atas: Merah #B01116, Tebal -->
                <p class="text-xs sm:text-sm font-extrabold text-[#B01116] tracking-widest uppercase mb-3">
                    ALUR YANG TRANSPARAN
                </p>
                <!-- Judul Utama: #252525 -->
                <h2 class="text-3xl sm:text-4xl font-extrabold text-[#252525] tracking-tight mb-4">
                    Satu kanal, penanganan yang jelas
                </h2>
                <!-- Subjudul: #6B7280 -->
                <p class="text-base sm:text-lg text-[#6B7280] leading-relaxed">
                    Setiap laporan melewati proses resmi dengan peran dan akuntabilitas yang terdefinisi.
                </p>
            </div>

            <!-- Grid 4 Kartu Langkah (Border #E5E7EB, Icon Pink #FCE8E8) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                <!-- KARTU 01: Kirim Laporan -->
                <div class="bg-white border border-[#E5E7EB] rounded-2xl p-6 lg:p-7 relative flex flex-col justify-between hover:shadow-lg hover:border-gray-300 transition-all duration-200">
                    <!-- Angka Besar Abu-abu Terang di Pojok Kanan Atas -->
                    <span class="absolute top-6 right-6 text-xl sm:text-2xl font-bold text-gray-300 select-none">
                        01
                    </span>

                    <div>
                        <!-- Background Ikon: Pink Pudar #FCE8E8, Ikon Merah #B01116 -->
                        <div class="w-12 h-12 rounded-xl bg-[#FCE8E8] flex items-center justify-center mb-6">
                            <!-- Icon Dokumen -->
                            <svg class="w-6 h-6 text-[#B01116]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                            </svg>
                        </div>

                        <!-- Judul Kartu -->
                        <h3 class="text-lg font-bold text-[#252525] mb-2">
                            Kirim Laporan
                        </h3>

                        <!-- Deskripsi -->
                        <p class="text-sm text-[#6B7280] leading-relaxed">
                            Isi formulir pengaduan atau aspirasi dengan informasi yang jelas.
                        </p>
                    </div>
                </div>

                <!-- KARTU 02: Verifikasi Laporan -->
                <div class="bg-white border border-[#E5E7EB] rounded-2xl p-6 lg:p-7 relative flex flex-col justify-between hover:shadow-lg hover:border-gray-300 transition-all duration-200">
                    <!-- Angka Besar Abu-abu Terang di Pojok Kanan Atas -->
                    <span class="absolute top-6 right-6 text-xl sm:text-2xl font-bold text-gray-300 select-none">
                        02
                    </span>

                    <div>
                        <!-- Background Ikon: Pink Pudar #FCE8E8, Ikon Merah #B01116 -->
                        <div class="w-12 h-12 rounded-xl bg-[#FCE8E8] flex items-center justify-center mb-6">
                            <!-- Icon Perisai Centang -->
                            <svg class="w-6 h-6 text-[#B01116]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                            </svg>
                        </div>

                        <!-- Judul Kartu -->
                        <h3 class="text-lg font-bold text-[#252525] mb-2">
                            Verifikasi Laporan
                        </h3>

                        <!-- Deskripsi -->
                        <p class="text-sm text-[#6B7280] leading-relaxed">
                            Laporan diperiksa, ditetapkan prioritasnya, dan diarahkan ke unit yang bertanggung jawab.
                        </p>
                    </div>
                </div>

                <!-- KARTU 03: Penanganan Unit -->
                <div class="bg-white border border-[#E5E7EB] rounded-2xl p-6 lg:p-7 relative flex flex-col justify-between hover:shadow-lg hover:border-gray-300 transition-all duration-200">
                    <!-- Angka Besar Abu-abu Terang di Pojok Kanan Atas -->
                    <span class="absolute top-6 right-6 text-xl sm:text-2xl font-bold text-gray-300 select-none">
                        03
                    </span>

                    <div>
                        <!-- Background Ikon: Pink Pudar #FCE8E8, Ikon Merah #B01116 -->
                        <div class="w-12 h-12 rounded-xl bg-[#FCE8E8] flex items-center justify-center mb-6">
                            <!-- Icon Penanganan Unit / Workflow -->
                            <svg class="w-6 h-6 text-[#B01116]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
                            </svg>
                        </div>

                        <!-- Judul Kartu -->
                        <h3 class="text-lg font-bold text-[#252525] mb-2">
                            Penanganan Unit
                        </h3>

                        <!-- Deskripsi -->
                        <p class="text-sm text-[#6B7280] leading-relaxed">
                            Unit terkait menangani laporan dan memperbarui progres.
                        </p>
                    </div>
                </div>

                <!-- KARTU 04: Pantau Hasil -->
                <div class="bg-white border border-[#E5E7EB] rounded-2xl p-6 lg:p-7 relative flex flex-col justify-between hover:shadow-lg hover:border-gray-300 transition-all duration-200">
                    <!-- Angka Besar Abu-abu Terang di Pojok Kanan Atas -->
                    <span class="absolute top-6 right-6 text-xl sm:text-2xl font-bold text-gray-300 select-none">
                        04
                    </span>

                    <div>
                        <!-- Background Ikon: Pink Pudar #FCE8E8, Ikon Merah #B01116 -->
                        <div class="w-12 h-12 rounded-xl bg-[#FCE8E8] flex items-center justify-center mb-6">
                            <!-- Icon Grafik / Pantau Hasil -->
                            <svg class="w-6 h-6 text-[#B01116]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/>
                            </svg>
                        </div>

                        <!-- Judul Kartu -->
                        <h3 class="text-lg font-bold text-[#252525] mb-2">
                            Pantau Hasil
                        </h3>

                        <!-- Deskripsi -->
                        <p class="text-sm text-[#6B7280] leading-relaxed">
                            Pantau status, berikan tanggapan, dan nilai penanganan.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>


    <!-- ========================================================= -->
    <!-- 4. SECTION 3: TRANSPARANSI DALAM ANGKA (Bg #F7F7F8)        -->
    <!-- ========================================================= -->
    <section id="statistik" class="bg-[#F7F7F8] py-20 lg:py-24 border-t border-[#E5E7EB]/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">

                <!-- Sisi Kiri: Label Merah, Judul Besar, Deskripsi -->
                <div class="lg:col-span-5">
                    <p class="text-xs sm:text-sm font-bold text-[#B01116] tracking-wider uppercase mb-3">
                        DATA DEMONSTRASI • OKTOBER 2026
                    </p>
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-[#252525] tracking-tight mb-4">
                        Transparansi dalam angka
                    </h2>
                    <p class="text-base sm:text-lg text-[#6B7280] leading-relaxed max-w-lg">
                        Ringkasan agregat tanpa menampilkan identitas atau detail laporan mahasiswa.
                    </p>
                </div>

                <!-- Sisi Kanan: Grid 4 Kotak Putih Statistik -->
                <div class="lg:col-span-7">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-5">

                        <!-- Kotak 1: Total Laporan -->
                        <div class="bg-white rounded-2xl p-6 lg:p-7 shadow-xs border border-[#E5E7EB]/60 hover:shadow-md transition-shadow">
                            <span class="text-4xl lg:text-[42px] font-extrabold text-[#252525] tracking-tight block mb-2 leading-none">
                                1.248
                            </span>
                            <span class="text-sm font-medium text-[#6B7280]">
                                Total Laporan
                            </span>
                        </div>

                        <!-- Kotak 2: Laporan Selesai -->
                        <div class="bg-white rounded-2xl p-6 lg:p-7 shadow-xs border border-[#E5E7EB]/60 hover:shadow-md transition-shadow">
                            <span class="text-4xl lg:text-[42px] font-extrabold text-[#252525] tracking-tight block mb-2 leading-none">
                                892
                            </span>
                            <span class="text-sm font-medium text-[#6B7280]">
                                Laporan Selesai
                            </span>
                        </div>

                        <!-- Kotak 3: Dalam Proses -->
                        <div class="bg-white rounded-2xl p-6 lg:p-7 shadow-xs border border-[#E5E7EB]/60 hover:shadow-md transition-shadow">
                            <span class="text-4xl lg:text-[42px] font-extrabold text-[#252525] tracking-tight block mb-2 leading-none">
                                267
                            </span>
                            <span class="text-sm font-medium text-[#6B7280]">
                                Dalam Proses
                            </span>
                        </div>

                        <!-- Kotak 4: Menunggu Verifikasi -->
                        <div class="bg-white rounded-2xl p-6 lg:p-7 shadow-xs border border-[#E5E7EB]/60 hover:shadow-md transition-shadow">
                            <span class="text-4xl lg:text-[42px] font-extrabold text-[#252525] tracking-tight block mb-2 leading-none">
                                89
                            </span>
                            <span class="text-sm font-medium text-[#6B7280]">
                                Menunggu Verifikasi
                            </span>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>


    <!-- ========================================================= -->
    <!-- 5. FOOTER (Gradient Merah-Gelap Diagonal #B01116 ke #58595B)-->
    <!-- ========================================================= -->
    <footer class="w-full text-white pt-14 pb-8 sm:pt-16 sm:pb-10" style="background: linear-gradient(135deg, #58595B 0%, #7d171c 45%, #B01116 100%);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Konten Utama: 2 Kolom Seimbang -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 md:gap-12 items-start">

                <!-- Sisi Kiri: Logo Putih CampusVoice & Deskripsi Singkat -->
                <div class="md:col-span-7 space-y-3">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/logo-campusvoice.png') }}"
                             alt="Logo CampusVoice"
                             class="w-9 h-9 object-contain shrink-0"
                             onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-9 h-9 rounded-full bg-white/20 flex items-center justify-center shrink-0\'><svg class=\'w-5 h-5 text-white\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'2\' stroke-linecap=\'round\' stroke-linejoin=\'round\'><path d=\'M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z\' fill=\'#FFFFFF\'/><path d=\'M8 9h.01M12 9h.01M16 9h.01\' stroke=\'#B01116\' stroke-width=\'2.5\'/></svg></div>';">
                        <div>
                            <span class="text-xl sm:text-2xl font-extrabold text-white tracking-tight block leading-tight">CampusVoice</span>
                            <span class="text-xs font-medium text-white/80 block leading-tight">Telkom University Surabaya</span>
                        </div>
                    </div>

                    <p class="text-sm font-normal text-white/85 leading-relaxed max-w-md pt-1">
                        Kanal resmi pengaduan dan aspirasi mahasiswa Telkom University Surabaya.
                    </p>
                </div>

                <!-- Sisi Kanan: Judul NAVIGASI & Menu Tautan Sejajar Horizontal -->
                <div class="md:col-span-5 md:flex md:flex-col md:items-end space-y-2.5">
                    <p class="text-xs font-bold tracking-widest text-white/60 uppercase">
                        NAVIGASI
                    </p>
                    <div class="flex items-center gap-6 sm:gap-8 flex-wrap">
                        <a href="{{ url('/') }}" class="text-sm font-medium text-white/85 hover:text-white transition-colors">
                            Beranda
                        </a>
                        <a href="#alur-penanganan" class="text-sm font-medium text-white/85 hover:text-white transition-colors">
                            Tentang
                        </a>
                        <a href="mailto:ssc@telkomuniversity.ac.id" class="text-sm font-medium text-white/85 hover:text-white transition-colors">
                            Pusat Bantuan
                        </a>
                    </div>
                </div>

            </div>

            <!-- Garis Pembatas Horizontal Tipis (border-t warna putih transparan) -->
            <div class="w-full border-t border-white/15 mt-10 sm:mt-12 pt-6">
                <p class="text-xs text-white/50">
                    © 2026 CampusVoice. Hak cipta dilindungi.
                </p>
            </div>

        </div>
    </footer>

</body>
</html>
