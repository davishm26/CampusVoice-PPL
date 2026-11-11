@php
    $authUser = $authUser ?? Auth::user() ?? \App\Models\User::where('role', 'Petugas Unit')->first() ?? \App\Models\User::first();
    $name = $authUser ? $authUser->name : 'Budi Santoso';
    $email = $authUser ? $authUser->email : 'unit@telkomuniversity.ac.id';
    $nip = ($authUser && $authUser->nim) ? $authUser->nim : '992002';
    $unitBagian = ($authUser && $authUser->prodi) ? $authUser->prodi : 'Logistik & Sarana';
    $instansi = ($authUser && $authUser->fakultas) ? $authUser->fakultas : 'Direktorat Logistik dan Aset';
    $phone = ($authUser && $authUser->phone) ? $authUser->phone : '-';
    $role = ($authUser && $authUser->role) ? $authUser->role : 'Petugas Unit';

    $words = preg_split('/\s+/', trim($name));
    $initials = '';
    foreach ($words as $w) {
        $initials .= mb_strtoupper(mb_substr($w, 0, 1));
    }
    $initials = substr($initials, 0, 2) ?: 'BS';

    $unreadCount = $unreadCount ?? \App\Models\AppNotification::where('user_id', $authUser?->id)->where('is_read', false)->count();
    if ($unreadCount === 0) {
        $unreadCount = 3;
    }
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#FFFFFF]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Profil Unit - Petugas Unit CampusVoice</title>

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
    x-data="{
        mobileSidebarOpen: false,
        isEditing: false,
        saving: false,
        saveSuccessMsg: '',
        nomorTelepon: @js($phone),
        tempNomorTelepon: @js($phone),
        startEdit() {
            this.tempNomorTelepon = this.nomorTelepon;
            this.isEditing = true;
        },
        cancelEdit() {
            this.tempNomorTelepon = this.nomorTelepon;
            this.isEditing = false;
        },
        saveEdit() {
            this.saving = true;
            fetch('{{ route('profil.phone.update') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ phone: this.tempNomorTelepon })
            })
            .then(res => res.json())
            .then(data => {
                this.nomorTelepon = this.tempNomorTelepon;
                this.isEditing = false;
                this.saving = false;
                this.saveSuccessMsg = 'Nomor telepon berhasil diperbarui!';
                setTimeout(() => this.saveSuccessMsg = '', 3000);
            })
            .catch(err => {
                this.saving = false;
                alert('Gagal memperbarui nomor telepon.');
            });
        }
    }"
>
    <div class="min-h-screen flex flex-col lg:flex-row">

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

                        <!-- Menu 2: Laporan Ditugaskan -->
                        <a
                            href="#"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#252525] hover:bg-gray-100 hover:text-[#B01116] transition group"
                        >
                            <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
                            <span class="w-5 h-5 rounded-full bg-[#B01116] text-[#FFFFFF] text-[11px] font-bold flex items-center justify-center">
                                {{ $unreadCount }}
                            </span>
                        </a>

                        <!-- Menu 5: Profil Unit (AKTIF) -->
                        <a
                            href="{{ route('unit.profil') }}"
                            aria-current="page"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold bg-[#FCE8E8] text-[#B01116] transition shadow-xs"
                        >
                            <svg class="w-5 h-5 text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                            <span>Profil Unit</span>
                        </a>
                    </nav>
                </div>

                <!-- Bagian Bawah: Keluar -->
                <div class="px-4 py-3 border-t border-[#E5E7EB]">
                    @auth
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
                    @else
                        <a href="{{ route('login') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#B01116] hover:bg-red-50 transition text-left">
                            <svg class="w-5 h-5 text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                                <polyline points="10 17 15 12 10 7"/>
                                <line x1="15" y1="12" x2="3" y2="12"/>
                            </svg>
                            <span>Masuk Akun</span>
                        </a>
                    @endauth
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
        <!-- AREA UTAMA (Header + Konten) -->
        <!-- ========================================================= -->
        <div class="flex-1 flex flex-col min-w-0">

            <!-- HEADER ATAS (NAVBAR) -->
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

                    <div class="relative w-64 sm:w-80 md:w-96">
                        <input
                            type="text"
                            placeholder="Cari nomor tiket atau laporan..."
                            onkeydown="if(event.key === 'Enter'){ window.location.href='{{ route('petugas.riwayat') }}'; }"
                            class="w-full bg-[#FFFFFF] border border-[#E5E7EB] rounded-lg py-2 px-3.5 text-xs sm:text-sm text-[#252525] placeholder-[#6B7280] focus:border-[#B01116] focus:outline-none focus:ring-1 focus:ring-[#B01116] transition"
                        />
                    </div>
                </div>

                <!-- Bagian Kanan: Lonceng Notifikasi & Profil Petugas Unit -->
                <div class="flex items-center gap-3 sm:gap-4">
                    <a
                        href="{{ route('unit.notifikasi') }}"
                        class="relative p-2 rounded-lg border border-[#E5E7EB] text-[#6B7280] hover:text-[#B01116] hover:bg-gray-50 transition cursor-pointer"
                        title="Notifikasi"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span class="absolute -top-1.5 -right-1.5 w-4 h-4 rounded-full bg-[#B01116] text-[#FFFFFF] text-[10px] font-bold flex items-center justify-center shadow-xs">
                            {{ $unreadCount }}
                        </span>
                    </a>

                    <a href="{{ route('unit.profil') }}" class="flex items-center gap-2.5 pl-1 group">
                        <div class="w-9 h-9 rounded-lg bg-[#FCE8E8] text-[#B01116] font-bold text-xs flex items-center justify-center shrink-0">
                            {{ $initials }}
                        </div>
                        <div class="hidden sm:block text-left">
                            <p class="text-xs font-bold text-[#252525] leading-tight group-hover:text-[#B01116] transition">{{ $name }}</p>
                            <p class="text-[11px] text-[#6B7280] leading-tight">{{ $role }}</p>
                        </div>
                    </a>
                </div>
            </header>

            <!-- ========================================================= -->
            <!-- AREA KONTEN UTAMA -->
            <!-- ========================================================= -->
            <main class="flex-1 content-gradient-bg px-4 sm:px-8 lg:px-10 py-7 text-[#FFFFFF]">
                <div class="max-w-7xl mx-auto">

                    <!-- Alert Notifikasi Update Kontak -->
                    <div x-show="saveSuccessMsg" x-cloak class="mb-4 p-4 rounded-xl bg-emerald-500/90 text-white font-semibold text-sm flex items-center gap-2 shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span x-text="saveSuccessMsg"></span>
                    </div>

                    <!-- Header Halaman -->
                    <div class="mb-6">
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-[#FFFFFF] tracking-tight">
                            Profil Unit
                        </h1>
                        <p class="text-xs sm:text-sm text-[#FFFFFF]/85 mt-1 font-normal">
                            Kelola informasi kontak akun Petugas Unit yang sedang Anda gunakan.
                        </p>
                    </div>

                    <!-- KARTU PROFIL UTAMA -->
                    <div class="bg-[#FFFFFF] rounded-2xl sm:rounded-3xl p-6 sm:p-8 md:p-10 text-[#252525] shadow-xl border border-[#E5E7EB]">

                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2">
                            <div class="flex items-center gap-4 sm:gap-5">
                                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-[#FCE8E8] text-[#B01116] font-bold text-xl sm:text-2xl flex items-center justify-center shrink-0 shadow-xs">
                                    {{ $initials }}
                                </div>
                                <div>
                                    <h2 class="text-lg sm:text-xl font-bold text-[#252525] leading-tight">
                                        {{ $name }}
                                    </h2>
                                    <p class="text-xs sm:text-sm text-[#6B7280] mt-1 font-normal">
                                        {{ $role }} • {{ $instansi }}
                                    </p>
                                    <div class="mt-2">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-[#ECFDF5] text-[#16A34A] text-xs font-semibold">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#16A34A]"></span>
                                            Akun Aktif
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div x-show="!isEditing" x-transition>
                                <button
                                    type="button"
                                    @click="startEdit()"
                                    class="inline-flex items-center gap-2 px-4 py-2 sm:py-2.5 rounded-xl border border-[#E5E7EB] bg-[#FFFFFF] text-xs sm:text-sm font-semibold text-[#252525] hover:bg-gray-50 transition shadow-xs cursor-pointer"
                                >
                                    <svg class="w-4 h-4 text-[#252525]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/>
                                        <path d="m15 5 4 4"/>
                                    </svg>
                                    <span>Edit Profil</span>
                                </button>
                            </div>
                        </div>

                        <!-- BANNER INFORMASI SINKRONISASI -->
                        <div class="rounded-xl border border-[#E5E7EB] bg-[#F8FAFC] p-3.5 sm:p-4 my-6 flex items-center gap-3">
                            <div class="text-[#2563EB] shrink-0">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                    <path d="m9 12 2 2 4-4"/>
                                </svg>
                            </div>
                            <p class="text-xs text-[#6B7280] leading-relaxed">
                                Identitas utama disinkronkan dari akun staf Telkom University Surabaya dan tidak dapat diubah melalui CampusVoice.
                            </p>
                        </div>

                        <!-- GRID FORMULIR DATA DIRI -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
                            <div>
                                <label class="block text-xs font-bold text-[#252525] mb-1.5">
                                    Nama Lengkap
                                </label>
                                <input
                                    type="text"
                                    readonly
                                    value="{{ $name }}"
                                    class="w-full rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] py-2.5 sm:py-3 px-4 text-xs sm:text-sm text-[#6B7280] cursor-not-allowed select-none focus:outline-none"
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-[#252525] mb-1.5">
                                    NIP / ID Pegawai
                                </label>
                                <input
                                    type="text"
                                    readonly
                                    value="{{ $nip }}"
                                    class="w-full rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] py-2.5 sm:py-3 px-4 text-xs sm:text-sm text-[#6B7280] cursor-not-allowed select-none focus:outline-none"
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-[#252525] mb-1.5">
                                    Email
                                </label>
                                <input
                                    type="text"
                                    readonly
                                    value="{{ $email }}"
                                    class="w-full rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] py-2.5 sm:py-3 px-4 text-xs sm:text-sm text-[#6B7280] cursor-not-allowed select-none focus:outline-none"
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-[#252525] mb-1.5">
                                    Peran Akun
                                </label>
                                <input
                                    type="text"
                                    readonly
                                    value="{{ $role }}"
                                    class="w-full rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] py-2.5 sm:py-3 px-4 text-xs sm:text-sm text-[#6B7280] cursor-not-allowed select-none focus:outline-none"
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-[#252525] mb-1.5">
                                    Unit / Bagian
                                </label>
                                <input
                                    type="text"
                                    readonly
                                    value="{{ $unitBagian }}"
                                    class="w-full rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] py-2.5 sm:py-3 px-4 text-xs sm:text-sm text-[#6B7280] cursor-not-allowed select-none focus:outline-none"
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-[#252525] mb-1.5">
                                    Unit Penangan
                                </label>
                                <input
                                    type="text"
                                    readonly
                                    value="{{ $instansi }}"
                                    class="w-full rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] py-2.5 sm:py-3 px-4 text-xs sm:text-sm text-[#6B7280] cursor-not-allowed select-none focus:outline-none"
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-[#252525] mb-1.5">
                                    Nomor Telepon
                                </label>
                                <input
                                    type="text"
                                    :readonly="!isEditing"
                                    x-model="tempNomorTelepon"
                                    :class="isEditing
                                        ? 'bg-[#FFFFFF] text-[#252525] border-[#E5E7EB] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116] focus:outline-none cursor-text'
                                        : 'bg-[#F9FAFB] text-[#6B7280] border-[#E5E7EB] cursor-not-allowed select-none focus:outline-none'"
                                    class="w-full rounded-xl border py-2.5 sm:py-3 px-4 text-xs sm:text-sm transition duration-150"
                                />
                                <p
                                    x-show="isEditing"
                                    x-cloak
                                    class="text-[11px] sm:text-xs text-[#6B7280] mt-1.5"
                                >
                                    Gunakan nomor yang aktif untuk kebutuhan koordinasi penanganan laporan.
                                </p>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-[#252525] mb-1.5">
                                    Status Sinkronisasi
                                </label>
                                <input
                                    type="text"
                                    readonly
                                    value="Tersinkronisasi SSO Telkom University"
                                    class="w-full rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] py-2.5 sm:py-3 px-4 text-xs sm:text-sm text-[#6B7280] cursor-not-allowed select-none focus:outline-none"
                                />
                            </div>
                        </div>

                        <!-- FOOTER FORMULIR (Saat Mode Edit) -->
                        <div x-show="isEditing" x-cloak x-transition class="border-t border-[#E5E7EB] mt-8 pt-5">
                            <div class="flex items-center justify-end gap-3">
                                <button
                                    type="button"
                                    @click="cancelEdit()"
                                    :disabled="saving"
                                    class="px-5 sm:px-6 py-2.5 rounded-xl border border-[#E5E7EB] bg-[#FFFFFF] text-xs sm:text-sm font-bold text-[#252525] hover:bg-gray-50 transition shadow-xs cursor-pointer"
                                >
                                    Batal
                                </button>
                                <button
                                    type="button"
                                    @click="saveEdit()"
                                    :disabled="saving"
                                    class="px-5 sm:px-6 py-2.5 rounded-xl bg-[#B01116] hover:bg-[#970e13] text-xs sm:text-sm font-bold text-[#FFFFFF] shadow-sm transition cursor-pointer flex items-center gap-2"
                                >
                                    <span x-show="!saving">Simpan Perubahan</span>
                                    <span x-show="saving" x-cloak>Menyimpan...</span>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </main>

        </div>
    </div>
</body>
</html>

