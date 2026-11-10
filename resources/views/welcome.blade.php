@php
    $dbTotal = \App\Models\Laporan::count();
    $dbSelesai = \App\Models\Laporan::where('status', 'Selesai')->count();
    $dbProses = \App\Models\Laporan::whereIn('status', ['Diproses', 'Diteruskan'])->count();
    $dbMenunggu = \App\Models\Laporan::where('status', 'Menunggu Verifikasi')->count();

    $statTotal = $dbTotal;
    $statSelesai = $dbSelesai;
    $statProses = $dbProses;
    $statMenunggu = $dbMenunggu;

    $sampleReport = \App\Models\Laporan::whereIn('status', ['Diproses', 'Diteruskan'])->latest()->first() 
        ?? \App\Models\Laporan::latest()->first();
@endphp
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

        /* Diagonal Hero Slice 1v1 Style (#58595B & #B01116) */
        .hero-diagonal-slice {
            clip-path: polygon(18% 0%, 100% 0%, 100% 100%, 0% 100%);
            background: linear-gradient(135deg, rgba(88, 89, 91, 0.05) 0%, rgba(176, 17, 22, 0.10) 100%);
        }

        @media (min-width: 1024px) {
            .hero-diagonal-slice {
                clip-path: polygon(22% 0%, 100% 0%, 100% 100%, 5% 100%);
            }
        }

        /* Footer Background Matching Design #360608 */
        .footer-exact-bg {
            background-color: #360608;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col antialiased selection:bg-[#B01116] selection:text-white" x-data="{ mobileMenuOpen: false }">

    <!-- ========================================================= -->
    <!-- 1. NAVBAR (Background #FFFFFF)                             -->
    <!-- ========================================================= -->
    <header class="w-full bg-[#FFFFFF] border-b border-[#E5E7EB]/60 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <!-- Sisi Kiri: Logo CampusVoice Telkom University Surabaya -->
            <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                <!-- Red Voice Bubble Icon -->
                <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center shrink-0">
                    <img src="{{ asset('images/logo-campusvoice.png') }}" 
                         alt="Logo CampusVoice" 
                         class="w-10 h-10 object-contain"
                         onerror="this.onerror=null; this.parentElement.innerHTML='<svg class=\'w-9 h-9 text-[#B01116]\' viewBox=\'0 0 40 40\' fill=\'currentColor\'><path d=\'M6 18c0-6.627 5.373-12 12-12h4c6.627 0 12 5.373 12 12 0 6.627-5.373 12-12 12h-2.5l-6.5 4.5v-4.5H18C11.373 30 6 24.627 6 18z\' fill=\'#B01116\'/><path d=\'M15 15a3 3 0 0 1 0 6M19 12a6 6 0 0 1 0 12M23 9a9 9 0 0 1 0 18\' stroke=\'white\' stroke-width=\'2.2\' stroke-linecap=\'round\' fill=\'none\'/></svg>';">
                </div>
                <div>
                    <span class="text-xl font-extrabold text-[#252525] tracking-tight block leading-tight">CampusVoice</span>
                    <span class="text-xs font-medium text-[#6B7280] block leading-tight">Telkom University Surabaya</span>
                </div>
            </a>

            <!-- Sisi Tengah: Teks Navigasi (#252525) -->
            <nav class="hidden md:flex items-center gap-8 lg:gap-10">
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

            <!-- Sisi Kanan: Tombol Masuk & Daftar Mahasiswa -->
            <div class="hidden md:flex items-center gap-2.5">
                @auth
                    <a href="{{ url('/dashboard') }}" 
                       class="inline-flex items-center justify-center px-6 py-2.5 rounded-xl bg-[#B01116] text-white text-sm font-semibold hover:bg-[#950e12] shadow-sm hover:shadow transition-all duration-200">
                        Buka Dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}" 
                       class="inline-flex items-center justify-center px-4 py-2 rounded-xl border border-[#E5E7EB] text-[#252525] text-sm font-semibold hover:bg-gray-50 transition-all duration-200">
                        Daftar Mahasiswa
                    </a>
                    <a href="{{ route('login') }}" 
                       class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-[#B01116] text-white text-sm font-semibold hover:bg-[#950e12] shadow-sm hover:shadow transition-all duration-200">
                        Masuk Akun
                    </a>
                @endauth
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
                                <a href="{{ route('register') }}" 
                                   class="w-full sm:w-auto inline-flex items-center justify-center gap-3 px-6 py-3.5 rounded-xl bg-[#B01116] text-white text-[15px] font-semibold hover:bg-[#950e12] shadow-sm hover:shadow-md transition-all duration-200">
                                    <span>Daftar Akun Mahasiswa</span>
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                                    </svg>
                                </a>
                                <a href="{{ route('login') }}" 
                                   class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3.5 rounded-xl bg-white border border-[#E5E7EB] text-[#252525] text-[15px] font-semibold hover:bg-gray-50 hover:border-gray-300 transition-all duration-200">
                                    Masuk Akun
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

                <!-- SISI KANAN: Mockup Antarmuka (UI) Melayang & Floating Badges -->
                <div class="lg:col-span-5 relative mt-6 lg:mt-0 flex justify-center">
                    
                    <!-- WRAPPER KARTU MOCKUP -->
                    <div class="relative w-full max-w-[460px]">
                        
                        <!-- Floating Badge Atas Kanan: "Teruskan ke Unit" -->
                        <div class="absolute -top-6 -right-2 sm:-right-6 z-20 bg-white rounded-2xl p-3 sm:p-3.5 shadow-xl border border-gray-100/80 flex items-center gap-3">
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

                        <!-- KARTU UTAMA MOCKUP LAPORAN -->
                        <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden flex min-h-[300px]">
                            
                            <!-- Sidebar Mini Kiri Mockup (Maroon / Dark Red Bar) -->
                            <div class="w-16 sm:w-20 bg-[#421013] py-5 px-3 sm:px-4 flex flex-col items-center gap-3 shrink-0">
                                <div class="w-7 h-7 rounded-lg bg-white/20 flex items-center justify-center mb-2">
                                    <div class="w-3.5 h-3.5 rounded-full bg-white/70"></div>
                                </div>
                                <div class="w-full h-2 rounded-full bg-white/40"></div>
                                <div class="w-full h-2 rounded-full bg-white/20"></div>
                                <div class="w-3/4 h-2 rounded-full bg-white/20 self-start"></div>
                            </div>

                            <!-- Area Konten Mockup -->
                            <div class="flex-1 p-5 sm:p-6 lg:p-7 flex flex-col justify-between">
                                
                                <!-- Top Browser Dots -->
                                <div>
                                    <div class="flex items-center gap-1.5 mb-5">
                                        <div class="w-2.5 h-2.5 rounded-full bg-red-400"></div>
                                        <div class="w-2.5 h-2.5 rounded-full bg-amber-400"></div>
                                        <div class="w-2.5 h-2.5 rounded-full bg-emerald-400"></div>
                                    </div>

                                    <!-- Tag Laporan Aktif -->
                                    <p class="text-[10px] font-bold text-[#6B7280] tracking-wider uppercase mb-1.5">
                                        LAPORAN AKTIF {{ $sampleReport ? '• ' . $sampleReport->nomor_tiket : '' }}
                                    </p>

                                    <!-- Judul Laporan -->
                                    <h3 class="text-base sm:text-lg font-bold text-[#252525] leading-snug mb-3">
                                        {{ $sampleReport ? $sampleReport->judul : 'Permohonan Perbaikan AC' }}
                                    </h3>

                                    @php
                                        $badgeStyle = $sampleReport ? $sampleReport->status_badge : ['text' => '#7C3AED', 'bg' => '#F5F3FF', 'dot' => '#7C3AED'];
                                        $progressVal = $sampleReport ? $sampleReport->progres : 65;
                                        $statusText = $sampleReport ? $sampleReport->status : 'Diproses';
                                    @endphp

                                    <!-- Status Badge -->
                                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold" style="color: {{ $badgeStyle['text'] }}; background-color: {{ $badgeStyle['bg'] }};">
                                        <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $badgeStyle['dot'] }};"></span>
                                        <span>{{ $statusText }}</span>
                                    </div>

                                    <!-- Progress Bar Merah #B01116 -->
                                    <div class="w-full bg-[#E5E7EB] rounded-full h-2.5 mt-5 overflow-hidden">
                                        <div class="bg-[#B01116] h-2.5 rounded-full transition-all duration-500" style="width: {{ $progressVal }}%;"></div>
                                    </div>
                                </div>

                                <!-- Progress Stepper Timeline Bawah (4 Steps) -->
                                <div class="pt-6">
                                    <div class="relative flex items-center justify-between">
                                        <!-- Garis Penghubung Stepper -->
                                        <div class="absolute left-2.5 right-2.5 top-1/2 -translate-y-1/2 h-0.5 bg-[#E5E7EB] z-0">
                                            <!-- Bagian aktif (hijau ke merah) -->
                                            <div class="h-0.5 bg-[#16A34A] transition-all duration-500" style="width: {{ $progressVal }}%;"></div>
                                        </div>

                                        <!-- Node 1: Selesai / Terkirim -->
                                        <div class="relative z-10 w-5 h-5 rounded-full bg-[#16A34A] border-2 border-white shadow-xs flex items-center justify-center">
                                            <div class="w-2 h-2 rounded-full bg-white"></div>
                                        </div>

                                        <!-- Node 2: Terverifikasi -->
                                        <div class="relative z-10 w-5 h-5 rounded-full {{ $progressVal >= 35 ? 'bg-[#16A34A]' : 'bg-white border-2 border-gray-300' }} border-2 border-white shadow-xs flex items-center justify-center">
                                            @if ($progressVal >= 35)
                                                <div class="w-2 h-2 rounded-full bg-white"></div>
                                            @endif
                                        </div>

                                        <!-- Node 3: Sedang Diproses -->
                                        <div class="relative z-10 w-5 h-5 rounded-full {{ $progressVal >= 65 ? 'bg-[#16A34A]' : ($progressVal >= 40 ? 'bg-white border-2 border-[#B01116]' : 'bg-white border-2 border-gray-300') }} shadow-xs flex items-center justify-center">
                                            @if ($progressVal >= 65)
                                                <div class="w-2 h-2 rounded-full bg-white"></div>
                                            @elseif ($progressVal >= 40)
                                                <div class="w-2 h-2 rounded-full bg-[#B01116]"></div>
                                            @endif
                                        </div>

                                        <!-- Node 4: Selesai -->
                                        <div class="relative z-10 w-5 h-5 rounded-full {{ $progressVal >= 100 ? 'bg-[#16A34A]' : 'bg-white border-2 border-gray-300' }} shadow-xs flex items-center justify-center">
                                            @if ($progressVal >= 100)
                                                <div class="w-2 h-2 rounded-full bg-white"></div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Floating Badge Bawah Kiri: "Progres diperbarui" -->
                        <div class="absolute -bottom-5 -left-2 sm:-left-6 z-20 bg-white rounded-xl px-4 py-2.5 shadow-xl border border-gray-100/80 flex items-center gap-2.5">
                            <!-- Bell Icon Biru #2563EB -->
                            <svg class="w-5 h-5 text-[#2563EB] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                            </svg>
                            <span class="text-xs sm:text-sm font-semibold text-[#252525]">Progres diperbarui {{ $progressVal }}%</span>
                        </div>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                            </svg>
                            <span class="text-xs sm:text-sm font-semibold text-[#252525]">Progres diperbarui 65%</span>
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
                        DATA STATISTIK KAMPUS • {{ strtoupper(now()->locale('id')->isoFormat('MMMM Y')) }}
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
                                {{ number_format($statTotal, 0, ',', '.') }}
                            </span>
                            <span class="text-sm font-medium text-[#6B7280]">
                                Total Laporan
                            </span>
                        </div>

                        <!-- Kotak 2: Laporan Selesai -->
                        <div class="bg-white rounded-2xl p-6 lg:p-7 shadow-xs border border-[#E5E7EB]/60 hover:shadow-md transition-shadow">
                            <span class="text-4xl lg:text-[42px] font-extrabold text-[#252525] tracking-tight block mb-2 leading-none">
                                {{ number_format($statSelesai, 0, ',', '.') }}
                            </span>
                            <span class="text-sm font-medium text-[#6B7280]">
                                Laporan Selesai
                            </span>
                        </div>

                        <!-- Kotak 3: Dalam Proses -->
                        <div class="bg-white rounded-2xl p-6 lg:p-7 shadow-xs border border-[#E5E7EB]/60 hover:shadow-md transition-shadow">
                            <span class="text-4xl lg:text-[42px] font-extrabold text-[#252525] tracking-tight block mb-2 leading-none">
                                {{ number_format($statProses, 0, ',', '.') }}
                            </span>
                            <span class="text-sm font-medium text-[#6B7280]">
                                Dalam Proses
                            </span>
                        </div>

                        <!-- Kotak 4: Menunggu Verifikasi -->
                        <div class="bg-white rounded-2xl p-6 lg:p-7 shadow-xs border border-[#E5E7EB]/60 hover:shadow-md transition-shadow">
                            <span class="text-4xl lg:text-[42px] font-extrabold text-[#252525] tracking-tight block mb-2 leading-none">
                                {{ number_format($statMenunggu, 0, ',', '.') }}
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
    <!-- 5. FOOTER (Background #360608 Sesuai Desain Acuan)         -->
    <!-- ========================================================= -->
    <footer class="footer-exact-bg text-white pt-14 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Baris Atas: Logo CampusVoice (Merah) + Tagline -->
            <div class="flex flex-col md:flex-row items-center justify-center gap-5 md:gap-8 mb-7 text-center md:text-left">
                <!-- Logo CampusVoice -->
                <div class="flex items-center gap-3 shrink-0">
                    <img src="{{ asset('images/logo-campusvoice.png') }}" 
                         alt="Logo CampusVoice" 
                         class="w-10 h-10 object-contain shrink-0"
                         onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-10 h-10 rounded-full bg-[#B01116] flex items-center justify-center shrink-0\'><svg class=\'w-6 h-6 text-white\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'2\' stroke-linecap=\'round\' stroke-linejoin=\'round\'><path d=\'M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z\' fill=\'#B01116\'/><path d=\'M8 9h.01M12 9h.01M16 9h.01\' stroke=\'#FFFFFF\' stroke-width=\'2.5\'/></svg></div>';">
                    <div class="text-left">
                        <span class="text-xl font-extrabold text-white tracking-tight block leading-tight">CampusVoice</span>
                        <span class="text-xs font-normal text-white/90 block leading-tight">Telkom University Surabaya</span>
                    </div>
                </div>

                <!-- Tagline Resmi -->
                <p class="text-sm font-normal text-white/95 leading-normal">
                    Kanal resmi pengaduan dan aspirasi mahasiswa Telkom University Surabaya.
                </p>
            </div>

            <!-- Baris Tengah: Tautan Menu (Beranda, Tentang, ssc@telkomuniversity.ac.id) -->
            <div class="flex flex-wrap items-center justify-center gap-8 sm:gap-10 mb-9">
                <a href="{{ url('/') }}" class="text-[15px] font-medium text-white hover:text-white/80 transition-colors">
                    Beranda
                </a>
                <a href="#alur-penanganan" class="text-[15px] font-medium text-white hover:text-white/80 transition-colors">
                    Tentang
                </a>
                <a href="mailto:ssc@telkomuniversity.ac.id" class="text-[15px] font-medium text-white hover:text-white/80 transition-colors">
                    ssc@telkomuniversity.ac.id
                </a>
            </div>

            <!-- Garis Pembatas Tipis (#57292B) -->
            <div class="w-full border-t border-[#57292B] mb-6"></div>

            <!-- Baris Bawah: Hak Cipta -->
            <div>
                <p class="text-xs text-[#B39294]">
                    © 2026 CampusVoice. Hak cipta dilindungi.
                </p>
            </div>

        </div>
    </footer>

</body>
</html>
