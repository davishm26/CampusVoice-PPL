<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk - CampusVoice Telkom University Surabaya</title>

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
        input[type="email"]:focus {
            outline: none;
            border-color: #B01116;
            box-shadow: 0 0 0 3px rgba(176, 17, 22, 0.1);
        }

        input[type="checkbox"] {
            accent-color: #B01116;
        }
    </style>
</head>
<body class="h-full bg-white antialiased" x-data="{ 
    showPassword: false, 
    isLoading: false
}">

    <div class="min-h-screen flex flex-col lg:flex-row">

        <!-- ============================================================= -->
        <!-- KOLOM KIRI — Panel Informasi & Branding -->
        <!-- ============================================================= -->
        <div class="relative overflow-hidden left-panel-gradient lg:w-1/2 flex flex-col px-10 py-10 lg:px-14 lg:py-12 min-h-[300px] lg:min-h-screen">
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
            <div class="relative z-10 flex flex-col justify-center flex-1 mt-14 lg:mt-0 lg:py-16">
                <p class="text-white/80 text-xs font-bold tracking-widest uppercase mb-5">
                    SATU AKUN, SATU KANAL RESMI
                </p>

                <h1 class="text-white font-extrabold text-4xl lg:text-5xl xl:text-[52px] leading-tight tracking-tight mb-5">
                    Kelola aspirasi<br>dengan<br>transparan<span class="text-white">.</span>
                </h1>

                <p class="text-white/75 text-sm lg:text-base leading-relaxed max-w-xs">
                    Setiap suara tercatat, setiap proses dapat dipantau, dan setiap unit bertanggung jawab.
                </p>
            </div>

            <!-- Footer Kiri: Kutipan -->
            <div class="relative z-10 flex items-start gap-4 mt-auto pt-8 lg:pt-0 pb-2">
                <div class="w-0.5 bg-white/50 self-stretch min-h-[60px] rounded-full shrink-0"></div>
                <div>
                    <p class="text-white text-sm leading-relaxed">
                        "CampusVoice menjadikan proses pengaduan lebih terarah dan mudah dipantau."
                    </p>
                    <p class="text-white/55 text-xs mt-2 font-medium">— Student Service Center</p>
                </div>
            </div>
        </div>

        <!-- ============================================================= -->
        <!-- KOLOM KANAN — Formulir Login -->
        <!-- ============================================================= -->
        <div class="bg-white lg:w-1/2 flex flex-col min-h-screen">

            <!-- Navigasi Atas: Kembali ke beranda -->
            <div class="px-8 sm:px-12 lg:px-16 pt-8 pb-4 flex items-center">
                <a
                    href="{{ url('/') }}"
                    class="inline-flex items-center gap-1.5 text-sm font-bold text-[#B01116] hover:text-[#8e0d12] transition-colors group"
                >
                    <svg
                        class="w-4 h-4 transition-transform group-hover:-translate-x-0.5"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                    >
                        <line x1="19" y1="12" x2="5" y2="12"/>
                        <polyline points="12 19 5 12 12 5"/>
                    </svg>
                    Kembali ke beranda
                </a>
            </div>

            <!-- Area Form — Terpusat Vertikal -->
            <div class="flex-1 flex flex-col items-center justify-center px-8 sm:px-12 lg:px-16 py-8">
                <div class="w-full max-w-sm">

                    <!-- Session Status / Error -->
                    @if (session('status'))
                        <div class="mb-4 p-3 rounded-lg bg-green-50 border border-green-200 text-sm text-green-700 font-medium">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if (isset($errors) && $errors->any())
                        <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200">
                            <ul class="text-xs text-red-600 space-y-0.5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Judul Form -->
                    <h2 class="text-[28px] sm:text-[32px] font-extrabold text-[#252525] leading-tight tracking-tight mb-2">
                        Masuk ke CampusVoice
                    </h2>
                    <p class="text-sm text-[#6B7280] mb-8 leading-relaxed">
                        Gunakan akun resmi kampus untuk melanjutkan.
                    </p>

                    <!-- Formulir -->
                    <form
                        method="POST"
                        action="{{ route('login') }}"
                        class="space-y-5"
                        @submit="isLoading = true"
                    >
                        @csrf

                        <!-- Input Username -->
                        <div>
                            <label for="email" class="block text-sm font-bold text-[#252525] mb-1.5">
                                Username<span class="text-[#B01116] ml-0.5">*</span>
                            </label>
                            <input
                                id="email"
                                name="email"
                                type="text"
                                autocomplete="username"
                                value="{{ old('email') }}"
                                placeholder="username sso"
                                required
                                autofocus
                                class="block w-full rounded-lg border border-[#E5E7EB] bg-white px-4 py-3 text-sm text-[#252525] placeholder-[#9CA3AF] transition-all duration-150 hover:border-gray-300 @error('email') border-red-400 @enderror"
                            />
                            @error('email')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Input Kata Sandi -->
                        <div>
                            <label for="password" class="block text-sm font-bold text-[#252525] mb-1.5">
                                Kata Sandi<span class="text-[#B01116] ml-0.5">*</span>
                            </label>
                            <div class="relative">
                                <input
                                    id="password"
                                    name="password"
                                    :type="showPassword ? 'text' : 'password'"
                                    autocomplete="current-password"
                                    placeholder="Masukkan kata sandi"
                                    required
                                    class="block w-full rounded-lg border border-[#E5E7EB] bg-white px-4 py-3 pr-12 text-sm text-[#252525] placeholder-[#9CA3AF] transition-all duration-150 hover:border-gray-300 @error('password') border-red-400 @enderror"
                                />
                                <!-- Toggle Show/Hide Password -->
                                <button
                                    type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 text-[#6B7280] hover:text-[#252525] transition-colors cursor-pointer"
                                    tabindex="-1"
                                >
                                    <svg x-show="!showPassword" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    <svg x-show="showPassword" x-cloak class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                                        <line x1="1" y1="1" x2="23" y2="23"/>
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Checkbox: Ingat Saya -->
                        <div class="flex items-center gap-2.5">
                            <input
                                id="remember_me"
                                name="remember"
                                type="checkbox"
                                class="w-4 h-4 rounded border-[#E5E7EB] text-[#B01116] cursor-pointer"
                            />
                            <label for="remember_me" class="text-sm text-[#252525] font-medium cursor-pointer select-none">
                                Ingat Saya
                            </label>
                        </div>

                        <!-- Tombol Masuk -->
                        <button
                            type="submit"
                            :disabled="isLoading"
                            class="w-full flex items-center justify-center gap-2 rounded-xl bg-[#B01116] hover:bg-[#8e0d12] active:bg-[#780b0f] text-white font-bold text-sm py-3.5 transition-colors duration-150 shadow-sm disabled:opacity-70 disabled:cursor-not-allowed cursor-pointer"
                        >
                            <svg x-show="isLoading" x-cloak class="animate-spin w-4 h-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                            <span x-text="isLoading ? 'Memproses...' : 'Masuk'">Masuk</span>
                        </button>
                    </form>

                    <!-- Kotak Informasi Akses Resmi (Sesuai Desain: Tidak Ada Pendaftaran Publik) -->
                    <div class="mt-5 rounded-xl border border-[#DBEAFE] bg-[#F8FAFC] p-4 flex items-start gap-3">
                        <div class="shrink-0 mt-0.5">
                            <svg class="w-5 h-5 text-[#2563EB]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                <path d="m9 12 2 2 4-4"/>
                            </svg>
                        </div>
                        <p class="text-xs text-[#6B7280] leading-relaxed">
                            Akses dilindungi dan dikelola menggunakan akun resmi kampus. Tidak ada pendaftaran akun publik.
                        </p>
                    </div>

                </div>
            </div>

            <!-- Footer Kanan -->
            <div class="px-8 sm:px-12 lg:px-16 pb-8 text-center">
                <p class="text-xs text-[#6B7280]">
                    Mengalami kendala? Hubungi
                    <a href="javascript:void(0)" class="font-bold text-[#6B7280] hover:text-[#252525] transition-colors underline-offset-2 hover:underline">
                        Student Service Center
                    </a>
                </p>
            </div>
        </div>

    </div>
</body>
</html>
