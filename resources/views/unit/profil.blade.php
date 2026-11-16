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
    <div class="h-screen overflow-hidden flex flex-col lg:flex-row">

        @include('unit.partials.sidebar', [
            'activeMenu' => 'profil',
            'unreadCount' => $unreadCount,
        ])

        <!-- ========================================================= -->
        <!-- AREA UTAMA (Header + Konten) -->
        <!-- ========================================================= -->
        <div class="flex-1 flex flex-col min-w-0 min-h-0 overflow-hidden">

            @include('unit.partials.header', [
                'authUser' => $authUser,
                'unreadCount' => $unreadCount,
            ])

            <!-- ========================================================= -->
            <!-- AREA KONTEN UTAMA -->
            <!-- ========================================================= -->
            <main class="flex-1 min-h-0 overflow-y-auto content-gradient-bg px-4 sm:px-8 lg:px-10 py-7 text-[#FFFFFF]">
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

