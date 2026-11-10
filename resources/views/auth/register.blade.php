<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar Akun Mahasiswa - CampusVoice Telkom University Surabaya</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

    <!-- Tailwind CSS via CDN -->
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
        * { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; }

        /* Panel kiri: gradient dari abu-abu gelap (kiri atas) ke merah (kanan bawah) */
        .left-panel-gradient {
            background: linear-gradient(135deg, #58595B 0%, #7A1419 50%, #B01116 100%);
        }

        .decorative-circle-outer {
            position: absolute;
            bottom: -80px;
            right: -80px;
            width: 320px;
            height: 320px;
            border-radius: 50%;
            border: 1px solid rgba(176, 17, 22, 0.5);
            background: rgba(140, 10, 15, 0.3);
        }
        .decorative-circle-inner {
            position: absolute;
            bottom: -20px;
            right: -20px;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: rgba(100, 5, 10, 0.4);
        }

        input[type="text"]:focus,
        input[type="password"]:focus,
        input[type="email"]:focus,
        input[type="tel"]:focus,
        select:focus {
            outline: none;
            border-color: #B01116;
            box-shadow: 0 0 0 3px rgba(176, 17, 22, 0.1);
        }
    </style>
</head>
<body class="h-full bg-white antialiased" x-data="{ 
    showPassword: false, 
    showConfirmPassword: false,
    isLoading: false 
}">

    <div class="min-h-screen flex flex-col lg:flex-row">

        <!-- ============================================================= -->
        <!-- KOLOM KIRI — Panel Informasi & Branding -->
        <!-- ============================================================= -->
        <div class="relative overflow-hidden left-panel-gradient lg:w-5/12 xl:w-1/2 flex flex-col px-10 py-10 lg:px-14 lg:py-12 min-h-[280px] lg:min-h-screen">
            <!-- Lingkaran Dekoratif -->
            <div class="decorative-circle-outer"></div>
            <div class="decorative-circle-inner"></div>

            <!-- Header: Logo CampusVoice -->
            <div class="relative z-10 flex items-center gap-3">
                <img
                    src="{{ asset('images/logo-campusvoice.png') }}"
                    alt="CampusVoice Logo"
                    class="w-10 h-10 object-contain"
                    onerror="this.style.display='none'"
                />
                <div>
                    <p class="text-white font-extrabold text-lg leading-tight tracking-tight">CampusVoice</p>
                    <p class="text-white/70 text-xs font-medium leading-tight">Telkom University Surabaya</p>
                </div>
            </div>

            <!-- Konten Tengah -->
            <div class="relative z-10 flex flex-col justify-center flex-1 mt-10 lg:mt-0 lg:py-12">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-xs text-white/90 text-xs font-bold tracking-widest uppercase mb-5 w-fit">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    REGISTRASI MAHASISWA / PELAPOR
                </div>

                <h1 class="text-white font-extrabold text-3xl sm:text-4xl lg:text-[46px] leading-tight tracking-tight mb-4">
                    Suara Anda,<br>Kawal Perubahan<br>Kampus Nyata<span class="text-white">.</span>
                </h1>

                <p class="text-white/80 text-sm lg:text-base leading-relaxed max-w-sm">
                    Daftarkan akun mahasiswa Anda untuk menyampaikan pengaduan sarana, akademik, dan layanan kampus dengan mudah, aman, dan dapat dilacak kapan saja.
                </p>

                <!-- Keunggulan Registrasi Akun Pelapor -->
                <div class="mt-8 space-y-3">
                    <div class="flex items-center gap-3 text-white/90 text-xs sm:text-sm">
                        <div class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <span>Akses penuh pelaporan aspirasi dan pemantauan tiket</span>
                    </div>
                    <div class="flex items-center gap-3 text-white/90 text-xs sm:text-sm">
                        <div class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <span>Opsi proteksi laporan anonim untuk privasi pelapor</span>
                    </div>
                    <div class="flex items-center gap-3 text-white/90 text-xs sm:text-sm">
                        <div class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <span>Notifikasi pembaruan langsung dari unit penangan</span>
                    </div>
                </div>
            </div>

            <!-- Footer Kiri -->
            <div class="relative z-10 flex items-start gap-4 mt-auto pt-6 lg:pt-0 pb-2">
                <div class="w-0.5 bg-white/50 self-stretch min-h-[50px] rounded-full shrink-0"></div>
                <div>
                    <p class="text-white text-xs sm:text-sm leading-relaxed">
                        "Partisipasi aktif mahasiswa adalah kunci peningkatan fasilitas dan mutu kampus."
                    </p>
                    <p class="text-white/60 text-xs mt-1.5 font-medium">— Direktorat Telkom University Surabaya</p>
                </div>
            </div>
        </div>

        <!-- ============================================================= -->
        <!-- KOLOM KANAN — Formulir Registrasi Mahasiswa -->
        <!-- ============================================================= -->
        <div class="bg-white lg:w-7/12 xl:w-1/2 flex flex-col min-h-screen">

            <!-- Navigasi Atas -->
            <div class="px-8 sm:px-12 lg:px-16 pt-8 pb-3 flex items-center justify-between">
                <a
                    href="{{ url('/') }}"
                    class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold text-[#B01116] hover:text-[#8e0d12] transition-colors group"
                >
                    <svg
                        class="w-4 h-4 transition-transform group-hover:-translate-x-0.5"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                    >
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                    <span>Kembali ke Beranda</span>
                </a>

                <span class="text-xs text-[#6B7280]">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="font-bold text-[#B01116] hover:underline ml-1">Masuk</a>
                </span>
            </div>

            <!-- Area Form Tengah -->
            <div class="flex-1 flex flex-col justify-center px-8 sm:px-12 lg:px-16 py-6">
                <div class="w-full max-w-lg mx-auto">

                    <!-- Header Formulir -->
                    <div class="mb-6">
                        <span class="inline-block px-2.5 py-1 rounded bg-[#FCE8E8] text-[#B01116] text-[11px] font-bold uppercase tracking-wider mb-2">
                            Role: Mahasiswa / Pelapor
                        </span>
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-[#252525] tracking-tight">
                            Daftar Akun Baru
                        </h2>
                        <p class="text-xs sm:text-sm text-[#6B7280] mt-1">
                            Lengkapi data identitas kemahasiswaan Anda untuk mulai membuat laporan.
                        </p>
                    </div>

                    <!-- Error Alert Global -->
                    @if (isset($errors) && $errors->any())
                        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-red-600 shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <p class="text-xs font-bold text-red-800">Terdapat kesalahan pada data yang Anda masukkan:</p>
                                    <ul class="mt-1 list-disc list-inside text-xs text-red-700 space-y-0.5">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Formulir Pendaftaran -->
                    <form method="POST" action="{{ route('register') }}" @submit="isLoading = true" class="space-y-4">
                        @csrf

                        <!-- 1. Nama Lengkap -->
                        <div>
                            <label for="name" class="block text-xs font-bold text-[#252525] mb-1.5">
                                Nama Lengkap Mahasiswa <span class="text-[#B01116]">*</span>
                            </label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                autofocus
                                placeholder="Contoh: Davista Hana"
                                class="w-full rounded-xl border border-[#E5E7EB] bg-white px-3.5 py-2.5 text-xs sm:text-sm text-[#252525] placeholder-[#9CA3AF] transition"
                            />
                        </div>

                        <!-- 2 Kolom: NIM & Program Studi -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- 2. NIM -->
                            <div>
                                <label for="nim" class="block text-xs font-bold text-[#252525] mb-1.5">
                                    NIM (Nomor Induk Mahasiswa) <span class="text-[#B01116]">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="nim"
                                    name="nim"
                                    value="{{ old('nim') }}"
                                    required
                                    placeholder="Contoh: 102062400113"
                                    class="w-full rounded-xl border border-[#E5E7EB] bg-white px-3.5 py-2.5 text-xs sm:text-sm text-[#252525] placeholder-[#9CA3AF] transition"
                                />
                            </div>

                            <!-- 3. Program Studi -->
                            <div>
                                <label for="prodi" class="block text-xs font-bold text-[#252525] mb-1.5">
                                    Program Studi <span class="text-[#B01116]">*</span>
                                </label>
                                <select
                                    id="prodi"
                                    name="prodi"
                                    required
                                    class="w-full rounded-xl border border-[#E5E7EB] bg-white px-3.5 py-2.5 text-xs sm:text-sm text-[#252525] transition cursor-pointer"
                                >
                                    <option value="" disabled {{ old('prodi') ? '' : 'selected' }}>Pilih Program Studi</option>
                                    <option value="S1 Sistem Informasi" {{ old('prodi') === 'S1 Sistem Informasi' ? 'selected' : '' }}>S1 Sistem Informasi</option>
                                    <option value="S1 Informatika" {{ old('prodi') === 'S1 Informatika' ? 'selected' : '' }}>S1 Informatika</option>
                                    <option value="S1 Rekayasa Perangkat Lunak" {{ old('prodi') === 'S1 Rekayasa Perangkat Lunak' ? 'selected' : '' }}>S1 Rekayasa Perangkat Lunak</option>
                                    <option value="S1 Teknologi Informasi" {{ old('prodi') === 'S1 Teknologi Informasi' ? 'selected' : '' }}>S1 Teknologi Informasi</option>
                                    <option value="S1 Bisnis Digital" {{ old('prodi') === 'S1 Bisnis Digital' ? 'selected' : '' }}>S1 Bisnis Digital</option>
                                    <option value="S1 Sains Data" {{ old('prodi') === 'S1 Sains Data' ? 'selected' : '' }}>S1 Sains Data</option>
                                    <option value="S1 Teknik Telekomunikasi" {{ old('prodi') === 'S1 Teknik Telekomunikasi' ? 'selected' : '' }}>S1 Teknik Telekomunikasi</option>
                                    <option value="S1 Teknik Komputer" {{ old('prodi') === 'S1 Teknik Komputer' ? 'selected' : '' }}>S1 Teknik Komputer</option>
                                    <option value="S1 Desain Komunikasi Visual" {{ old('prodi') === 'S1 Desain Komunikasi Visual' ? 'selected' : '' }}>S1 Desain Komunikasi Visual</option>
                                    <option value="Lainnya" {{ old('prodi') === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                            </div>
                        </div>

                        <!-- 2 Kolom: Email Mahasiswa & Nomor Telepon -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- 4. Email Mahasiswa -->
                            <div>
                                <label for="email" class="block text-xs font-bold text-[#252525] mb-1.5">
                                    Email Mahasiswa <span class="text-[#B01116]">*</span>
                                </label>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    placeholder="nama@student.telkomuniversity.ac.id"
                                    class="w-full rounded-xl border border-[#E5E7EB] bg-white px-3.5 py-2.5 text-xs sm:text-sm text-[#252525] placeholder-[#9CA3AF] transition"
                                />
                            </div>

                            <!-- 5. Nomor WhatsApp / Telepon -->
                            <div>
                                <label for="phone" class="block text-xs font-bold text-[#252525] mb-1.5">
                                    No. WhatsApp / Telepon <span class="text-[#B01116]">*</span>
                                </label>
                                <input
                                    type="tel"
                                    id="phone"
                                    name="phone"
                                    value="{{ old('phone') }}"
                                    required
                                    placeholder="Contoh: 081234567890"
                                    class="w-full rounded-xl border border-[#E5E7EB] bg-white px-3.5 py-2.5 text-xs sm:text-sm text-[#252525] placeholder-[#9CA3AF] transition"
                                />
                            </div>
                        </div>

                        <!-- Fakultas (Hidden / Default Direktorat Kampus Surabaya) -->
                        <input type="hidden" name="fakultas" value="Direktorat Kampus Surabaya">

                        <!-- 2 Kolom: Kata Sandi & Konfirmasi -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
                            <!-- 6. Password -->
                            <div>
                                <label for="password" class="block text-xs font-bold text-[#252525] mb-1.5">
                                    Kata Sandi <span class="text-[#B01116]">*</span>
                                </label>
                                <div class="relative">
                                    <input
                                        :type="showPassword ? 'text' : 'password'"
                                        id="password"
                                        name="password"
                                        required
                                        autocomplete="new-password"
                                        placeholder="Min. 8 karakter"
                                        class="w-full rounded-xl border border-[#E5E7EB] bg-white px-3.5 py-2.5 pr-10 text-xs sm:text-sm text-[#252525] placeholder-[#9CA3AF] transition"
                                    />
                                    <button
                                        type="button"
                                        @click="showPassword = !showPassword"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none"
                                        tabindex="-1"
                                    >
                                        <svg x-show="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <svg x-show="showPassword" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                                    </button>
                                </div>
                            </div>

                            <!-- 7. Konfirmasi Password -->
                            <div>
                                <label for="password_confirmation" class="block text-xs font-bold text-[#252525] mb-1.5">
                                    Konfirmasi Kata Sandi <span class="text-[#B01116]">*</span>
                                </label>
                                <div class="relative">
                                    <input
                                        :type="showConfirmPassword ? 'text' : 'password'"
                                        id="password_confirmation"
                                        name="password_confirmation"
                                        required
                                        autocomplete="new-password"
                                        placeholder="Ulangi kata sandi"
                                        class="w-full rounded-xl border border-[#E5E7EB] bg-white px-3.5 py-2.5 pr-10 text-xs sm:text-sm text-[#252525] placeholder-[#9CA3AF] transition"
                                    />
                                    <button
                                        type="button"
                                        @click="showConfirmPassword = !showConfirmPassword"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none"
                                        tabindex="-1"
                                    >
                                        <svg x-show="!showConfirmPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <svg x-show="showConfirmPassword" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Submit Pendaftaran -->
                        <div class="pt-3">
                            <button
                                type="submit"
                                :disabled="isLoading"
                                class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-[#B01116] hover:bg-[#8e0d12] text-white text-xs sm:text-sm font-bold shadow-md hover:shadow-lg transition-all duration-200 cursor-pointer disabled:opacity-75 disabled:cursor-not-allowed"
                            >
                                <svg x-show="isLoading" x-cloak class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>Daftar Akun Mahasiswa</span>
                            </button>
                        </div>
                    </form>

                    <!-- Link Sudah Punya Akun -->
                    <div class="mt-6 text-center text-xs text-[#6B7280]">
                        Sudah memiliki akun mahasiswa?
                        <a href="{{ route('login') }}" class="font-bold text-[#B01116] hover:text-[#8e0d12] hover:underline ml-1">
                            Masuk di sini
                        </a>
                    </div>

                </div>
            </div>

            <!-- Footer Kanan -->
            <div class="px-8 sm:px-12 lg:px-16 pb-6 text-center">
                <p class="text-xs text-[#6B7280]">
                    Pusat Bantuan & Layanan Mahasiswa:
                    <a href="mailto:ssc@telkomuniversity.ac.id" class="font-bold text-[#6B7280] hover:text-[#252525] transition-colors underline-offset-2 hover:underline">
                        Student Service Center
                    </a>
                </p>
            </div>
        </div>

    </div>
</body>
</html>
