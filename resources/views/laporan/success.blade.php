<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#FFFFFF]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Laporan Berhasil Dikirim - CampusVoice Telkom University Surabaya</title>

    <!-- Google Fonts: Plus Jakarta Sans / Figtree -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS with Forms Plugin via CDN -->
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#B01116',
                        'primary-hover': '#970e13',
                        maroon: '#713134',
                        'active-pink': '#FCE8E8',
                        'dark-text': '#252525',
                        'sub-text': '#6B7280',
                        'border-gray': '#E5E7EB',
                        'check-green': '#16A34A',
                        'light-green': '#DCFCE7',
                        'pending-orange': '#D97706',
                        'pending-bg': '#FFF7ED',
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'Figtree', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Plus Jakarta Sans', Figtree, sans-serif; }
        
        /* Linear Gradient Konten Utama: Merah #B01116 (0%) ke Abu-abu Gelap #58595B (100%) */
        .content-gradient-bg {
            background: linear-gradient(to top right, #B01116 0%, #58595B 100%);
        }
    </style>
</head>
<body class="h-full antialiased text-[#252525] bg-[#B01116]" x-data="{ mobileSidebarOpen: false }">
    <div class="min-h-screen flex flex-col lg:flex-row">

        <!-- ========================================================= -->
        <!-- SIDEBAR KIRI (Lebar Tetap - Fill Solid White #FFFFFF) -->
        <!-- ========================================================= -->
        <aside 
            class="fixed inset-y-0 left-0 z-50 w-64 bg-[#FFFFFF] flex flex-col justify-between border-r border-[#E5E7EB] transition-transform duration-200 lg:static lg:translate-x-0 shadow-xs"
            :class="mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        >
            <div class="flex flex-col flex-1 overflow-y-auto">
                <!-- Logo CampusVoice Telkom University Surabaya -->
                <div class="px-6 py-5 flex items-center gap-3 border-b border-transparent">
                    <img src="{{ asset('images/logo-campusvoice.png') }}" alt="CampusVoice Logo" class="w-8 h-8 object-contain shrink-0" />
                    <div>
                        <div class="flex items-center gap-1">
                            <span class="text-[17px] font-extrabold text-[#B01116] tracking-tight">CampusVoice</span>
                        </div>
                        <p class="text-[10px] text-[#6B7280] font-medium leading-tight">Telkom University Surabaya</p>
                    </div>
                </div>

                <!-- Navigasi Menu -->
                <div class="px-4 py-3 flex-1">
                    <p class="px-3 text-[11px] font-bold text-[#252525] tracking-wider mb-2 uppercase">
                        Mahasiswa
                    </p>

                    <nav class="space-y-1">
                        <!-- Menu 1: Dashboard -->
                        <a href="{{ url('/dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-[#252525] hover:text-[#000000] hover:bg-gray-50 transition group">
                            <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#252525]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="7" height="7" x="3" y="3" rx="1.5"/>
                                <rect width="7" height="7" x="14" y="3" rx="1.5"/>
                                <rect width="7" height="7" x="14" y="14" rx="1.5"/>
                                <rect width="7" height="7" x="3" y="14" rx="1.5"/>
                            </svg>
                            <span>Dashboard</span>
                        </a>

                        <!-- Menu 2: Buat Laporan -->
                        <a href="{{ url('/laporan-baru') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-[#252525] hover:text-[#000000] hover:bg-gray-50 transition group">
                            <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#252525]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            <span>Buat Laporan</span>
                        </a>

                        <!-- Menu 3: Riwayat Laporan -->
                        <a href="{{ route('laporan.riwayat') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-[#252525] hover:text-[#000000] hover:bg-gray-50 transition group">
                            <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#252525]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                                <line x1="16" y1="13" x2="8" y2="13"/>
                                <line x1="16" y1="17" x2="8" y2="17"/>
                                <polyline points="10 9 9 9 8 9"/>
                            </svg>
                            <span>Riwayat Laporan</span>
                        </a>

                        <!-- Menu 4: Notifikasi (Badge '3') -->
                        <a href="{{ route('notifikasi') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-medium text-[#252525] hover:text-[#000000] hover:bg-gray-50 transition group">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#252525]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
                                    <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                                </svg>
                                <span>Notifikasi</span>
                            </div>
                            <span class="inline-flex items-center justify-center w-5 h-5 text-[11px] font-bold text-[#FFFFFF] bg-[#B01116] rounded-full">
                                3
                            </span>
                        </a>

                        <!-- Menu 5: Profil Saya -->
                        <a href="{{ url('/profil') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-[#252525] hover:text-[#000000] hover:bg-gray-50 transition group">
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

                <!-- Kotak Butuh Bantuan Merah (#B01116) -->
                <div class="rounded-xl bg-[#B01116] p-4 text-[#FFFFFF] shadow-sm">
                    <h4 class="text-sm font-bold tracking-tight text-[#FFFFFF]">Butuh bantuan?</h4>
                    <p class="text-[11px] text-[#FFFFFF]/85 mt-1 leading-snug">Hubungi pusat layanan CampusVoice.</p>
                    <a href="javascript:void(0)" class="mt-3 block w-full py-1.5 px-3 bg-[#FFFFFF] hover:bg-gray-100 text-[#B01116] text-xs font-bold rounded-lg text-center shadow-xs transition">
                        Pusat Bantuan
                    </a>
                </div>
            </div>
        </aside>

        <!-- Overlay Mobile -->
        <div 
            x-show="mobileSidebarOpen" 
            x-cloak
            @click="mobileSidebarOpen = false" 
            class="fixed inset-0 z-40 bg-black/40 backdrop-blur-xs lg:hidden"
        ></div>

        <!-- ========================================================= -->
        <!-- AREA UTAMA (Header + Konten Utama) -->
        <!-- ========================================================= -->
        <div class="flex-1 flex flex-col min-w-0">

            <!-- HEADER ATAS (Search Bar & Profil - Background Solid White #FFFFFF) -->
            <header class="bg-[#FFFFFF] border-b border-[#E5E7EB] h-16 flex items-center justify-between px-4 sm:px-8 shrink-0">
                <div class="flex items-center gap-3">
                    <button 
                        @click="mobileSidebarOpen = !mobileSidebarOpen"
                        class="p-2 -ml-2 rounded-lg text-[#6B7280] hover:text-[#252525] hover:bg-gray-100 lg:hidden"
                        type="button"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <div class="relative w-64 sm:w-80 md:w-96">
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
                        <span class="absolute -top-1 -right-1 flex items-center justify-center w-4 h-4 text-[10px] font-bold text-[#FFFFFF] bg-[#B01116] rounded-full ring-2 ring-[#FFFFFF]">
                            3
                        </span>
                    </a>

                    <a href="{{ url('/profil') }}" class="flex items-center gap-2.5 group">
                        <div class="w-9 h-9 rounded-lg bg-[#FCE8E8] text-[#B01116] font-bold text-xs flex items-center justify-center shrink-0 group-hover:ring-2 group-hover:ring-[#B01116]/30 transition">
                            DA
                        </div>
                        <div class="hidden sm:block text-left">
                            <p class="text-xs font-bold text-[#252525] leading-tight group-hover:text-[#B01116] transition">Dimas Arya</p>
                            <p class="text-[11px] text-[#6B7280] leading-tight">Mahasiswa</p>
                        </div>
                    </a>
                </div>
            </header>

            <!-- ========================================================= -->
            <!-- AREA KONTEN UTAMA: Linear Gradient #B01116 ke #58595B -->
            <!-- ========================================================= -->
            <main class="flex-1 content-gradient-bg px-4 sm:px-8 lg:px-12 py-10 flex items-center justify-center text-[#252525]">
                <div class="w-full max-w-4xl mx-auto">

                    <!-- ========================================================= -->
                    <!-- KARTU UTAMA SUKSES (Solid White #FFFFFF) -->
                    <!-- ========================================================= -->
                    <div 
                        class="bg-[#FFFFFF] rounded-3xl p-6 sm:p-10 md:p-12 shadow-2xl border border-[#E5E7EB] text-center"
                        x-data="{
                            copied: false,
                            ticketNumber: 'CV-2026-00124',
                            copyTicket() {
                                navigator.clipboard.writeText(this.ticketNumber).then(() => {
                                    this.copied = true;
                                    setTimeout(() => this.copied = false, 2000);
                                });
                            }
                        }"
                    >

                        <!-- BAGIAN ATAS: Status Sukses & Icon Centang -->
                        <div class="mb-8">
                            <!-- Lingkaran Hijau Muda #DCFCE7 dengan Centang Hijau #16A34A -->
                            <div class="w-16 h-16 rounded-full bg-[#DCFCE7] flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-[#16A34A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                            </div>

                            <!-- Label Kecil Hijau -->
                            <p class="text-xs font-extrabold uppercase tracking-widest text-[#16A34A] mb-1.5">
                                LAPORAN TERKIRIM
                            </p>

                            <!-- Judul Besar -->
                            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#252525] tracking-tight mb-2">
                                Laporan Berhasil Dikirim
                            </h1>

                            <!-- Deskripsi -->
                            <p class="text-xs sm:text-sm text-[#6B7280] max-w-lg mx-auto leading-relaxed">
                                Admin SSC akan memeriksa kelengkapan dan menentukan unit penangan yang sesuai.
                            </p>
                        </div>

                        <!-- KOTAK NOMOR TIKET (Dashed Border #E5E7EB) -->
                        <div class="rounded-2xl border-2 border-dashed border-[#E5E7EB] bg-[#FFFFFF] p-6 max-w-xl mx-auto mb-8 shadow-xs">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-[#6B7280] mb-1.5">
                                NOMOR TIKET ANDA
                            </p>
                            <h2 class="text-3xl sm:text-4xl font-extrabold text-[#252525] tracking-tight mb-2" x-text="ticketNumber">
                                CV-2026-00124
                            </h2>
                            <button 
                                type="button" 
                                @click="copyTicket()"
                                class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold text-[#B01116] hover:text-[#970e13] transition cursor-pointer"
                            >
                                <span x-show="!copied">Salin nomor</span>
                                <span x-show="copied" x-cloak class="text-[#16A34A] flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <polyline points="20 6 9 17 4 12" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    Tersalin!
                                </span>
                            </button>
                        </div>

                        <!-- GRID INFORMASI LAPORAN (3 Kolom Bergaris Tepi #E5E7EB) -->
                        <div class="rounded-2xl border border-[#E5E7EB] bg-[#FFFFFF] max-w-2xl mx-auto mb-10 overflow-hidden">
                            <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-[#E5E7EB]">
                                <!-- Kolom 1: Status Awal -->
                                <div class="py-4 px-3 flex flex-col items-center justify-center">
                                    <span class="text-xs text-[#6B7280] mb-1.5">Status awal</span>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#FFF7ED] text-[#D97706] text-xs font-semibold">
                                        <span class="w-2 h-2 rounded-full bg-[#D97706]"></span>
                                        Menunggu Verifikasi
                                    </span>
                                </div>

                                <!-- Kolom 2: Waktu Pengiriman -->
                                <div class="py-4 px-3 flex flex-col items-center justify-center">
                                    <span class="text-xs text-[#6B7280] mb-1.5">Waktu pengiriman</span>
                                    <span class="text-xs sm:text-sm font-bold text-[#252525]">
                                        19 Oktober 2026, 10.24 WIB
                                    </span>
                                </div>

                                <!-- Kolom 3: Unit Penangan -->
                                <div class="py-4 px-3 flex flex-col items-center justify-center">
                                    <span class="text-xs text-[#6B7280] mb-1.5">Unit penangan</span>
                                    <span class="text-xs sm:text-sm font-bold text-[#252525]">
                                        Belum ditentukan
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- PROGRESS BAR / STEPPER (Alur Laporan 5 Langkah) -->
                        <div class="max-w-2xl mx-auto mb-10 px-2 sm:px-4">
                            <div class="relative flex items-center justify-between">
                                <!-- Garis Latar Stepper -->
                                <div class="absolute left-4 right-4 top-4 h-[2px] bg-[#E5E7EB] -z-0"></div>

                                <!-- Step 1: Dikirim (Aktif - Hijau #16A34A) -->
                                <div class="flex flex-col items-center relative z-10">
                                    <div class="w-8 h-8 rounded-full bg-[#16A34A] text-[#FFFFFF] flex items-center justify-center text-xs font-bold ring-4 ring-white shadow-xs">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12"/>
                                        </svg>
                                    </div>
                                    <span class="text-[11px] font-semibold text-[#252525] mt-2 whitespace-nowrap">
                                        Dikirim
                                    </span>
                                </div>

                                <!-- Step 2: Verifikasi Laporan -->
                                <div class="flex flex-col items-center relative z-10">
                                    <div class="w-8 h-8 rounded-full bg-[#FFFFFF] border-2 border-[#E5E7EB] text-[#6B7280] flex items-center justify-center text-xs font-semibold ring-4 ring-white">
                                        2
                                    </div>
                                    <span class="text-[11px] text-[#6B7280] mt-2 whitespace-nowrap">
                                        Verifikasi Laporan
                                    </span>
                                </div>

                                <!-- Step 3: Diteruskan -->
                                <div class="flex flex-col items-center relative z-10">
                                    <div class="w-8 h-8 rounded-full bg-[#FFFFFF] border-2 border-[#E5E7EB] text-[#6B7280] flex items-center justify-center text-xs font-semibold ring-4 ring-white">
                                        3
                                    </div>
                                    <span class="text-[11px] text-[#6B7280] mt-2 whitespace-nowrap">
                                        Diteruskan
                                    </span>
                                </div>

                                <!-- Step 4: Diproses -->
                                <div class="flex flex-col items-center relative z-10">
                                    <div class="w-8 h-8 rounded-full bg-[#FFFFFF] border-2 border-[#E5E7EB] text-[#6B7280] flex items-center justify-center text-xs font-semibold ring-4 ring-white">
                                        4
                                    </div>
                                    <span class="text-[11px] text-[#6B7280] mt-2 whitespace-nowrap">
                                        Diproses
                                    </span>
                                </div>

                                <!-- Step 5: Selesai -->
                                <div class="flex flex-col items-center relative z-10">
                                    <div class="w-8 h-8 rounded-full bg-[#FFFFFF] border-2 border-[#E5E7EB] text-[#6B7280] flex items-center justify-center text-xs font-semibold ring-4 ring-white">
                                        5
                                    </div>
                                    <span class="text-[11px] text-[#6B7280] mt-2 whitespace-nowrap">
                                        Selesai
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- TOMBOL AKSI (Bagian Bawah) -->
                        <div class="flex flex-col sm:flex-row items-center justify-center gap-3.5">
                            <!-- Tombol Merah: Lihat Detail Laporan -->
                            <a 
                                href="javascript:void(0)" 
                                class="w-full sm:w-auto px-7 py-3 rounded-xl bg-[#B01116] hover:bg-[#970e13] text-xs sm:text-sm font-bold text-[#FFFFFF] shadow-md transition text-center cursor-pointer"
                            >
                                Lihat Detail Laporan
                            </a>

                            <!-- Tombol Putih: Kembali ke Dashboard -->
                            <a 
                                href="{{ url('/dashboard') }}" 
                                class="w-full sm:w-auto px-7 py-3 rounded-xl bg-[#FFFFFF] border border-[#E5E7EB] hover:bg-gray-50 text-xs sm:text-sm font-bold text-[#252525] shadow-xs transition text-center cursor-pointer"
                            >
                                Kembali ke Dashboard
                            </a>
                        </div>

                    </div>
                </div>
            </main>

        </div>
    </div>
</body>
</html>
