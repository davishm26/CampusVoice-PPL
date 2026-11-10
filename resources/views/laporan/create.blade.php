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
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#FFFFFF]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Buat Laporan Baru - CampusVoice Telkom University Surabaya</title>

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

        
        /* Linear Gradient Konten Utama: Merah #B01116 ke Abu-abu Gelap #58595B */


        /* Linear Gradient Konten Utama: Merah #B01116 (0%) ke Abu-abu Gelap #58595B (100%) */

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
            class="fixed inset-y-0 left-0 z-50 w-64 bg-[#FFFFFF] flex flex-col justify-between border-r border-[#E5E7EB] transition-transform duration-200 lg:static lg:translate-x-0 shadow-xs"
            :class="mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        >
            <div class="flex flex-col flex-1 overflow-y-auto">
                <!-- Logo -->
                <div class="px-6 py-5 flex items-center gap-3 border-b border-transparent">
                    <img src="{{ asset('images/logo-campusvoice.png') }}" alt="CampusVoice Logo" class="w-8 h-8 object-contain shrink-0" onerror="this.onerror=null; this.src='https://placehold.co/32x32/B01116/white?text=CV';" />
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
                        {{ $authRole }}
                    </p>

                    <nav class="space-y-1">
                        <!-- Menu 1: Dashboard -->
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-[#252525] hover:text-[#000000] hover:bg-gray-50 transition group">
                            <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#252525]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="7" height="7" x="3" y="3" rx="1.5"/>
                                <rect width="7" height="7" x="14" y="3" rx="1.5"/>
                                <rect width="7" height="7" x="14" y="14" rx="1.5"/>
                                <rect width="7" height="7" x="3" y="14" rx="1.5"/>
                            </svg>
                            <span>Dashboard</span>
                        </a>


                        <!-- Menu 2: Buat Laporan (AKTIF) -->
                        <a href="{{ route('laporan.create') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold bg-[#FCE8E8] text-[#B01116] transition">
                            <svg class="w-5 h-5 text-[#B01116]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">

                        <!-- Menu 2: Buat Laporan (Aktif saat di form, netral saat sukses) -->
                        <a
                            href="javascript:void(0)"
                            @click="currentView = 'form'"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold transition"
                            :class="currentView === 'form' ? 'bg-[#FCE8E8] text-[#B01116]' : 'text-[#252525] hover:bg-gray-50 font-medium'"
                        >
                            <svg class="w-5 h-5" :class="currentView === 'form' ? 'text-[#B01116]' : 'text-[#6B7280]'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round">

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

                        <!-- Menu 4: Notifikasi -->
                        <a href="{{ route('notifikasi') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-medium text-[#252525] hover:text-[#000000] hover:bg-gray-50 transition group">
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
                        <a href="{{ route('profil') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-[#252525] hover:text-[#000000] hover:bg-gray-50 transition group">
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

                    <h4 class="text-sm font-bold tracking-tight text-[#FFFFFF]">Butuh bantuan?</h4>
                    <p class="text-[11px] text-[#FFFFFF]/85 mt-1 leading-snug">Hubungi pusat layanan CampusVoice.</p>
                    <a href="{{ route('pusat.bantuan') }}" class="mt-3 block w-full py-1.5 px-3 bg-[#FFFFFF] hover:bg-gray-100 text-[#B01116] text-xs font-bold rounded-lg text-center shadow-xs transition">

                        Pusat Bantuan
                    </a>
                </div>
            </div>
        </aside>

        <!-- Overlay Mobile -->

        <div x-show="mobileSidebarOpen" x-cloak @click="mobileSidebarOpen = false" class="fixed inset-0 z-40 bg-black/40 lg:hidden"></div>

        <div
            x-show="mobileSidebarOpen"
            x-cloak
            @click="mobileSidebarOpen = false"
            class="fixed inset-0 z-40 bg-black/40 backdrop-blur-xs lg:hidden"
        ></div>


        <!-- ========================================================= -->
        <!-- AREA UTAMA (Header + Konten) -->
        <!-- ========================================================= -->
        <div class="flex-1 flex flex-col min-w-0">

            <!-- HEADER ATAS -->
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

                        <form @submit.prevent="handleSearch()">
                            <input 
                                type="text" 
                                x-model="searchQuery"
                                placeholder="Cari nomor tiket atau laporan..." 
                                class="w-full bg-[#FFFFFF] border border-[#E5E7EB] rounded-lg py-2 px-3.5 text-xs sm:text-sm text-[#252525] placeholder-[#6B7280] focus:border-[#B01116] focus:outline-none focus:ring-1 focus:ring-[#B01116] transition"
                            />
                        </form>

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
            <!-- AREA KONTEN UTAMA: Linear Gradient #B01116 ke #58595B -->
            <!-- ========================================================= -->
            <main class="flex-1 content-gradient-bg px-4 sm:px-8 lg:px-10 py-7 text-[#FFFFFF]">
                <div class="max-w-7xl mx-auto">
                    <!-- Header Bagian Konten -->
                    <div class="mb-6">
                        <p class="text-[11px] font-bold uppercase tracking-widest text-[#FFFFFF]/85">
                            LAPORAN BARU
                        </p>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-[#FFFFFF] tracking-tight mt-0.5">
                            Buat Laporan Baru
                        </h1>
                        <p class="text-xs sm:text-sm text-[#FFFFFF]/85 mt-1 font-normal">
                            Sampaikan pengaduan atau aspirasi secara jelas agar dapat ditangani oleh unit yang tepat.
                        </p>
                    </div>

                    <!-- Tampilkan Pesan Error Validasi Jika Ada -->
                    @if (isset($errors) && $errors->any())
                        <div class="mb-6 rounded-2xl bg-red-500/90 backdrop-blur-md p-4 text-white shadow-lg">
                            <div class="flex items-center gap-2 font-bold text-sm mb-1">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" stroke-width="2"/>
                                    <line x1="12" y1="8" x2="12" y2="12" stroke-width="2"/>
                                    <line x1="12" y1="16" x2="12.01" y2="16" stroke-width="2"/>
                                </svg>
                                <span>Mohon lengkapi formulir dengan benar:</span>
                            </div>
                            <ul class="list-disc list-inside text-xs space-y-0.5 ml-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Grid Layout: Formulir (Kiri) & Sidebar Info (Kanan) -->
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">


                        <!-- FORMULIR UTAMA -->
                        <div 

                        <!-- FORMULIR UTAMA (KARTU PUTIH SOLID #FFFFFF) -->
                        <div

                            class="lg:col-span-8 bg-[#FFFFFF] rounded-2xl p-6 sm:p-8 text-[#252525] shadow-xl border border-[#E5E7EB]"
                            x-data="{
                                deskripsi: @js(old('deskripsi', '')),
                                fileSelected: null,
                                fileName: '',
                                fileSize: '',
                                isDragging: false,
                                handleFileDrop(e) {
                                    this.isDragging = false;
                                    const files = e.dataTransfer.files;
                                    if (files.length > 0) {
                                        this.processFile(files[0]);
                                    }
                                },
                                handleFileSelect(e) {
                                    const files = e.target.files;
                                    if (files.length > 0) {
                                        this.processFile(files[0]);
                                    }
                                },
                                processFile(file) {
                                    this.fileSelected = file;
                                    this.fileName = file.name;
                                    this.fileSize = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
                                },
                                removeFile() {
                                    this.fileSelected = null;
                                    this.fileName = '';
                                    this.fileSize = '';
                                    if (this.$refs.fileInput) {
                                        this.$refs.fileInput.value = '';
                                    }
                                }
                            }"
                        >
                            <form action="{{ route('laporan.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <!-- BAGIAN 1: INFORMASI LAPORAN -->
                                <div class="space-y-4 sm:space-y-5">
                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 rounded-full bg-[#FCE8E8] text-[#B01116] font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">
                                            1
                                        </div>
                                        <div>
                                            <h2 class="text-base font-bold text-[#252525] leading-tight">Informasi Laporan</h2>
                                            <p class="text-xs text-[#6B7280] mt-0.5">Pilih kategori dan jelaskan laporan Anda.</p>
                                        </div>
                                    </div>

                                    <!-- Field 1: Kategori Laporan -->
                                    <div class="pt-1">
                                        <label for="kategori" class="block text-xs font-bold text-[#252525] mb-1.5">
                                            Kategori Laporan <span class="text-[#B01116]">*</span>
                                        </label>
                                        <div class="relative">

                                            <select 
                                                id="kategori" 
                                                name="kategori" 

                                            <select
                                                id="kategori"
                                                name="kategori"
                                                x-model="kategori"

                                                required
                                                class="w-full appearance-none rounded-lg border border-[#E5E7EB] bg-[#FFFFFF] py-2.5 px-3.5 pr-10 text-xs sm:text-sm text-[#252525] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116] focus:outline-none transition cursor-pointer"
                                            >
                                                <option value="" disabled {{ old('kategori') ? '' : 'selected' }} class="text-[#6B7280]">Pilih kategori laporan</option>
                                                <option value="sarana_prasarana" {{ old('kategori') === 'sarana_prasarana' ? 'selected' : '' }}>Sarana dan prasarana kampus</option>
                                                <option value="kemahasiswaan" {{ old('kategori') === 'kemahasiswaan' ? 'selected' : '' }}>Kemahasiswaan</option>
                                                <option value="ssc" {{ old('kategori') === 'ssc' ? 'selected' : '' }}>Student Service Center</option>
                                                <option value="akademik" {{ old('kategori') === 'akademik' ? 'selected' : '' }}>Akademik</option>
                                                <option value="administrasi_keuangan" {{ old('kategori') === 'administrasi_keuangan' ? 'selected' : '' }}>Administrasi dan keuangan</option>
                                                <option value="lainnya" {{ old('kategori') === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                            </select>
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3.5 text-[#6B7280]">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Field 2: Judul Laporan -->
                                    <div>
                                        <label for="judul" class="block text-xs font-bold text-[#252525] mb-1.5">
                                            Judul Laporan <span class="text-[#B01116]">*</span>
                                        </label>

                                        <input 
                                            type="text" 
                                            id="judul" 
                                            name="judul" 
                                            value="{{ old('judul') }}"
                                        <input
                                            type="text"
                                            id="judul"
                                            name="judul"
                                            x-model="judul"
                                            required
                                            placeholder="Contoh: AC Ruang Kelas A-301 Tidak Berfungsi"
                                            class="w-full rounded-lg border border-[#E5E7EB] py-2.5 px-3.5 text-xs sm:text-sm text-[#252525] placeholder-[#6B7280] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116] focus:outline-none transition"
                                        />
                                        <p class="text-[11px] text-[#6B7280] mt-1">Gunakan judul singkat dan spesifik.</p>
                                    </div>

                                    <!-- Field 3: Deskripsi Laporan -->
                                    <div>
                                        <div class="flex items-center justify-between mb-1.5">
                                            <label for="deskripsi" class="block text-xs font-bold text-[#252525]">
                                                Deskripsi Laporan <span class="text-[#B01116]">*</span>
                                            </label>
                                            <span
                                                class="text-[11px] font-medium"
                                                :class="deskripsi.length < 30 ? 'text-[#6B7280]' : 'text-[#16A34A] font-semibold'"
                                                x-text="deskripsi.length + ' karakter'"
                                            ></span>
                                        </div>
                                        <textarea
                                            id="deskripsi"
                                            name="deskripsi"
                                            rows="5"
                                            x-model="deskripsi"
                                            required
                                            minlength="30"
                                            placeholder="Jelaskan kronologi, waktu kejadian, dan dampak yang Anda alami..."
                                            class="w-full rounded-lg border border-[#E5E7EB] p-3.5 text-xs sm:text-sm text-[#252525] placeholder-[#6B7280] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116] focus:outline-none transition resize-y"
                                        >{{ old('deskripsi') }}</textarea>
                                        <p class="text-[11px] text-[#6B7280] mt-1">Minimum 30 karakter. Jangan mencantumkan data sensitif.</p>
                                    </div>

                                    <!-- Field 4: Lokasi Kejadian -->
                                    <div>
                                        <label for="lokasi" class="block text-xs font-bold text-[#252525] mb-1.5">
                                            Lokasi Kejadian <span class="text-[#B01116]">*</span>
                                        </label>
                                        <input 
                                            type="text" 
                                            id="lokasi" 
                                            name="lokasi" 
                                            value="{{ old('lokasi') }}"
                                            required
                                            placeholder="Contoh: Gedung A, Ruang A-301"
                                            class="w-full rounded-lg border border-[#E5E7EB] py-2.5 px-3.5 text-xs sm:text-sm text-[#252525] placeholder-[#6B7280] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116] focus:outline-none transition"
                                        />
                                    </div>
                                </div>

                                <!-- Divider -->
                                <hr class="my-7 border-[#E5E7EB]" />

                                <!-- BAGIAN 2: BUKTI DAN PRIVASI -->
                                <div class="space-y-4 sm:space-y-5">
                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 rounded-full bg-[#FCE8E8] text-[#B01116] font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">
                                            2
                                        </div>
                                        <div>
                                            <h2 class="text-base font-bold text-[#252525] leading-tight">Bukti dan Privasi</h2>
                                            <p class="text-xs text-[#6B7280] mt-0.5">Lampirkan bukti pendukung bila tersedia.</p>
                                        </div>
                                    </div>

                                    <!-- Area Drag & Drop Unggah Berkas -->
                                    <div
                                        @dragover.prevent="isDragging = true"
                                        @dragleave.prevent="isDragging = false"
                                        @drop.prevent="handleFileDrop($event)"
                                        class="rounded-xl border-2 border-dashed p-6 sm:p-7 text-center transition-all duration-200"
                                        :class="isDragging ? 'border-[#B01116] bg-[#FCE8E8]/40' : 'border-[#E5E7EB] bg-[#FAFAFA] hover:border-gray-300'"
                                    >
                                        <template x-if="!fileSelected">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="w-8 h-8 flex items-center justify-center text-[#B01116] mb-1">
                                                    <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                                        <polyline points="17 8 12 3 7 8"/>
                                                        <line x1="12" x2="12" y1="3" y2="15"/>
                                                    </svg>
                                                </div>

                                                <p class="text-xs sm:text-sm font-semibold text-[#252525]">
                                                    Tarik berkas ke sini atau pilih dari perangkat
                                                </p>
                                                <p class="text-[11px] text-[#6B7280] mt-0.5">
                                                    JPG, PNG, atau PDF • Maksimum 10 MB
                                                </p>

                                                <input
                                                    type="file"
                                                    id="berkas_bukti"
                                                    name="berkas_bukti"
                                                    accept=".jpg,.jpeg,.png,.pdf"
                                                    x-ref="fileInput"
                                                    @change="handleFileSelect($event)"
                                                    class="hidden"
                                                />

                                                <button
                                                    type="button"
                                                    @click="$refs.fileInput.click()"
                                                    class="mt-3.5 px-4 py-1.5 rounded-lg border border-[#E5E7EB] bg-[#FFFFFF] text-xs font-semibold text-[#252525] shadow-xs hover:bg-gray-50 transition cursor-pointer"
                                                >
                                                    Pilih Berkas
                                                </button>
                                            </div>
                                        </template>

                                        <template x-if="fileSelected">
                                            <div class="flex items-center justify-between p-3 bg-[#FFFFFF] rounded-lg border border-[#E5E7EB] max-w-md mx-auto shadow-xs">
                                                <div class="flex items-center gap-3 truncate text-left">
                                                    <div class="w-8 h-8 rounded-lg bg-[#FCE8E8] text-[#B01116] flex items-center justify-center shrink-0">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                        </svg>
                                                    </div>
                                                    <div class="truncate">
                                                        <p class="text-xs font-semibold text-[#252525] truncate" x-text="fileName"></p>
                                                        <p class="text-[10px] text-[#6B7280]" x-text="fileSize"></p>
                                                    </div>
                                                </div>
                                                <button
                                                    type="button"
                                                    @click="removeFile()"
                                                    class="text-[#6B7280] hover:text-[#B01116] p-1 rounded-md transition cursor-pointer"
                                                    title="Hapus berkas"
                                                >
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </template>
                                    </div>

                                    <!-- Kotak Checkbox Laporan Anonim -->
                                    <div class="rounded-xl border border-[#E5E7EB] p-4 bg-[#FFFFFF] hover:border-gray-300 transition">
                                        <label for="anonim" class="flex items-start gap-3.5 cursor-pointer">
                                            <input 
                                                type="checkbox" 
                                                id="anonim" 
                                                name="is_anonim" 
                                                value="1"
                                                {{ old('is_anonim') ? 'checked' : '' }}
                                                class="w-4 h-4 mt-0.5 rounded border-[#E5E7EB] text-[#B01116] focus:ring-[#B01116] transition cursor-pointer"
                                            />
                                            <div>
                                                <span class="block text-xs sm:text-sm font-semibold text-[#252525] leading-tight">
                                                    Kirim sebagai laporan anonim
                                                </span>
                                                <span class="block text-[11px] text-[#6B7280] mt-1 leading-relaxed">
                                                    Identitas Anda disembunyikan dari petugas unit penangan. Admin SSC tetap dapat melihat identitas untuk verifikasi dan penyaluran.
                                                </span>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <!-- Tombol Aksi di Bawah Form -->
                                <div class="flex items-center justify-end gap-3 mt-8 pt-2">
                                    <a 
                                        href="{{ route('dashboard') }}"
                                        class="px-5 sm:px-6 py-2.5 rounded-lg border border-[#E5E7EB] bg-[#FFFFFF] text-xs sm:text-sm font-semibold text-[#252525] hover:bg-gray-50 transition shadow-xs text-center cursor-pointer"
                                    >
                                        Kembali
                                    </a>
                                    <button 
                                        type="submit" 
                                        class="flex items-center gap-2 px-5 sm:px-6 py-2.5 rounded-lg bg-[#B01116] hover:bg-[#970e13] text-xs sm:text-sm font-semibold text-[#FFFFFF] shadow-sm transition cursor-pointer"
                                    >
                                        <span class="text-[#FFFFFF]">Kirim Laporan</span>
                                        <svg class="w-4 h-4 text-[#FFFFFF]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="5" y1="12" x2="19" y2="12"/>
                                            <polyline points="12 5 19 12 12 19"/>
                                        </svg>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- KOLOM INFORMASI SAMPING KANAN -->
                        <div class="lg:col-span-4 space-y-4">
                            <!-- KARTU 1: Sebelum mengirim -->
                            <div class="bg-[#FFFFFF] rounded-2xl p-5 text-[#252525] shadow-xl border border-[#E5E7EB]">
                                <h3 class="text-xs sm:text-sm font-bold text-[#252525] mb-3">
                                    Sebelum mengirim
                                </h3>
                                <ul class="space-y-2.5 text-[11px] sm:text-xs text-[#252525]">
                                    <li class="flex items-start gap-2.5">
                                        <svg class="w-4 h-4 text-[#16A34A] shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12"/>
                                        </svg>
                                        <span class="leading-relaxed">Gunakan informasi yang faktual dan jelas.</span>
                                    </li>
                                    <li class="flex items-start gap-2.5">
                                        <svg class="w-4 h-4 text-[#16A34A] shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12"/>
                                        </svg>
                                        <span class="leading-relaxed">Pastikan lokasi kejadian sesuai.</span>
                                    </li>
                                    <li class="flex items-start gap-2.5">
                                        <svg class="w-4 h-4 text-[#16A34A] shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12"/>
                                        </svg>
                                        <span class="leading-relaxed">Hindari data pribadi pihak lain.</span>
                                    </li>
                                </ul>
                            </div>

                            <!-- KARTU 2: Penyaluran oleh SSC -->
                            <div class="bg-[#FFFFFF] rounded-2xl p-5 text-[#252525] shadow-xl border border-[#E5E7EB]">
                                <div class="mb-2">
                                    <div class="w-6 h-6 text-[#B01116]">
                                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                            <path d="m9 12 2 2 4-4"/>
                                        </svg>
                                    </div>
                                </div>
                                <h3 class="text-xs sm:text-sm font-bold text-[#252525] mb-1.5">
                                    Penyaluran oleh SSC
                                </h3>
                                <p class="text-[11px] sm:text-xs text-[#6B7280] leading-relaxed">
                                    Anda tidak perlu memilih unit atau prioritas. Admin SSC akan memverifikasi dan meneruskan laporan ke unit yang sesuai.
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            </main>

        </div>
    </div>
</body>
</html>
