<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#FFFFFF]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Unit Penangan - Super Admin CampusVoice</title>

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

        /* Linear Gradient Konten Utama: Merah #B01116 ke Abu-abu Gelap #58595B (dari kiri bawah ke kanan atas) */
        .content-gradient-bg {
            background: linear-gradient(to top right, #B01116 0%, #58595B 100%);
        }
    </style>
</head>
<body
    class="h-full antialiased text-[#252525] bg-[#B01116]"
    x-data="{
        mobileSidebarOpen: false,
        activeTab: 'direktorat',
        searchHeader: '',
        direktoratBadgeOffset: -1,
        direktoratUnits: [
            { id: 1, nama: 'Direktorat Sekretariat & Perencanaan Strategis', tipe: 'Direktorat', jumlahLabel: '1 petugas', status: 'Aktif' },
            { id: 2, nama: 'Direktorat Bandung Techno Park', tipe: 'Direktorat', jumlahLabel: '2 petugas', status: 'Aktif' },
            { id: 3, nama: 'Direktorat Akademik', tipe: 'Direktorat', jumlahLabel: '3 petugas', status: 'Aktif' },
            { id: 4, nama: 'Direktorat Pasca Sarjana dan Advance Learning', tipe: 'Direktorat', jumlahLabel: '4 petugas', status: 'Aktif' },
            { id: 5, nama: 'Direktorat Logistik dan Aset', tipe: 'Direktorat', jumlahLabel: '1 petugas', status: 'Aktif' },
            { id: 6, nama: 'Direktorat Keuangan', tipe: 'Direktorat', jumlahLabel: '2 petugas', status: 'Aktif' },
            { id: 7, nama: 'Direktorat Sumber Daya Manusia', tipe: 'Direktorat', jumlahLabel: '3 petugas', status: 'Aktif' },
            { id: 8, nama: 'Direktorat Pusat Teknologi Informasi', tipe: 'Direktorat', jumlahLabel: '4 petugas', status: 'Aktif' },
            { id: 9, nama: 'Direktorat Pemasaran & Admisi', tipe: 'Direktorat', jumlahLabel: '1 petugas', status: 'Aktif' },
            { id: 10, nama: 'Direktorat Pengembangan Karir, Alumni, & Endowment', tipe: 'Direktorat', jumlahLabel: '2 petugas', status: 'Aktif' },
            { id: 11, nama: 'Direktorat Kemahasiswaan', tipe: 'Direktorat', jumlahLabel: '3 petugas', status: 'Aktif' },
            { id: 12, nama: 'Direktorat Kerjasama Strategis & Kantor Urusan Internasional', tipe: 'Direktorat', jumlahLabel: '4 petugas', status: 'Aktif' },
            { id: 13, nama: 'Direktorat Penelitian dan Pengabdian Masyarakat', tipe: 'Direktorat', jumlahLabel: '1 petugas', status: 'Aktif' },
            { id: 14, nama: 'Satpras', tipe: 'Direktorat', jumlahLabel: '2 petugas', status: 'Aktif' }
        ],
        prodiUnits: [
            { id: 1, nama: 'Program Studi S1 Sistem Informasi', tipe: 'Program Studi', jumlahLabel: '35 mahasiswa', status: 'Aktif' },
            { id: 2, nama: 'Program Studi S1 Teknologi Informasi', tipe: 'Program Studi', jumlahLabel: '28 mahasiswa', status: 'Aktif' },
            { id: 3, nama: 'Program Studi S1 Teknik Informatika', tipe: 'Program Studi', jumlahLabel: '42 mahasiswa', status: 'Aktif' },
            { id: 4, nama: 'Program Studi S1 Rekayasa Perangkat Lunak', tipe: 'Program Studi', jumlahLabel: '30 mahasiswa', status: 'Aktif' },
            { id: 5, nama: 'Program Studi S1 Sains Data', tipe: 'Program Studi', jumlahLabel: '25 mahasiswa', status: 'Aktif' },
            { id: 6, nama: 'Program Studi S1 Teknik Elektro', tipe: 'Program Studi', jumlahLabel: '22 mahasiswa', status: 'Aktif' },
            { id: 7, nama: 'Program Studi S1 Teknik Industri', tipe: 'Program Studi', jumlahLabel: '29 mahasiswa', status: 'Aktif' },
            { id: 8, nama: 'Program Studi S1 Teknik Komputer', tipe: 'Program Studi', jumlahLabel: '20 mahasiswa', status: 'Aktif' },
            { id: 9, nama: 'Program Studi S1 Teknik Logistik', tipe: 'Program Studi', jumlahLabel: '18 mahasiswa', status: 'Aktif' },
            { id: 10, nama: 'Program Studi S1 Bisnis Digital', tipe: 'Program Studi', jumlahLabel: '33 mahasiswa', status: 'Aktif' },
            { id: 11, nama: 'Program Studi S1 Teknik Telekomunikasi', tipe: 'Program Studi', jumlahLabel: '24 mahasiswa', status: 'Aktif' }
        ],
        showAddModal: false,
        showManageModal: false,
        toastMessage: '',
        newUnit: { nama: '', tipe: 'Direktorat', jumlahLabel: '1 petugas', status: 'Aktif' },
        selectedUnit: null,
        editForm: { id: null, nama: '', tipe: 'Direktorat', jumlahLabel: '', status: 'Aktif' },
        get direktoratBadgeCount() {
            return Math.max(0, this.direktoratUnits.length + this.direktoratBadgeOffset);
        },
        get prodiBadgeCount() {
            return this.prodiUnits.length;
        },
        get displayedUnits() {
            const list = this.activeTab === 'direktorat' ? this.direktoratUnits : this.prodiUnits;
            const q = this.searchHeader.trim().toLowerCase();
            if (!q) return list;
            return list.filter(u =>
                u.nama.toLowerCase().includes(q) ||
                u.tipe.toLowerCase().includes(q) ||
                u.jumlahLabel.toLowerCase().includes(q)
            );
        },
        formatNo(num) {
            return String(num).padStart(2, '0');
        },
        openAddModal() {
            this.newUnit = {
                nama: '',
                tipe: this.activeTab === 'direktorat' ? 'Direktorat' : 'Program Studi',
                jumlahLabel: this.activeTab === 'direktorat' ? '1 petugas' : '20 mahasiswa',
                status: 'Aktif'
            };
            this.showAddModal = true;
        },
        saveNewUnit() {
            if (!this.newUnit.nama.trim()) return;
            const targetList = this.newUnit.tipe === 'Direktorat' ? this.direktoratUnits : this.prodiUnits;
            const nextId = targetList.length ? Math.max(...targetList.map(u => u.id)) + 1 : 1;
            targetList.push({
                id: nextId,
                nama: this.newUnit.nama.trim(),
                tipe: this.newUnit.tipe,
                jumlahLabel: this.newUnit.jumlahLabel.trim() || (this.newUnit.tipe === 'Direktorat' ? '1 petugas' : '20 mahasiswa'),
                status: this.newUnit.status
            });
            this.activeTab = this.newUnit.tipe === 'Direktorat' ? 'direktorat' : 'prodi';
            this.showAddModal = false;
            this.notify('Unit ' + this.newUnit.nama.trim() + ' berhasil ditambahkan.');
        },
        openManageModal(unit) {
            this.selectedUnit = unit;
            this.editForm = { ...unit };
            this.showManageModal = true;
        },
        saveManageUnit() {
            if (!this.selectedUnit || !this.editForm.nama.trim()) return;
            this.selectedUnit.nama = this.editForm.nama.trim();
            this.selectedUnit.jumlahLabel = this.editForm.jumlahLabel.trim() || this.selectedUnit.jumlahLabel;
            this.selectedUnit.status = this.editForm.status;
            this.showManageModal = false;
            this.notify('Perubahan unit ' + this.selectedUnit.nama + ' berhasil disimpan.');
        },
        notify(msg) {
            this.toastMessage = msg;
            setTimeout(() => { this.toastMessage = ''; }, 3000);
        }
    }"
>
    <div class="min-h-screen flex flex-col lg:flex-row">

        <!-- ========================================================= -->
        <!-- SIDEBAR KIRI (SUPER ADMIN) -->
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
                        <span class="text-[17px] font-extrabold text-[#B01116] tracking-tight block leading-none">CampusVoice</span>
                        <p class="text-[10px] text-[#6B7280] font-medium leading-tight mt-0.5">Telkom University Surabaya</p>
                    </div>
                </div>

                <!-- Navigasi Menu Super Admin -->
                <div class="px-4 py-3 flex-1">
                    <p class="px-3 text-[11px] font-bold text-[#252525] tracking-wide mb-2">Super Admin</p>

                    <nav class="space-y-1">
                        <!-- Menu 1: Dashboard -->
                        <a
                            href="{{ Route::has('superadmin.dashboard') ? route('superadmin.dashboard') : '#' }}"
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

                        <!-- Menu 2: Manajemen Akun -->
                        <a
                            href="#"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#252525] hover:bg-gray-100 hover:text-[#B01116] transition group"
                        >
                            <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                            <span>Manajemen Akun</span>
                        </a>

                        <!-- Menu 3: Unit Penangan (AKTIF) -->
                        <a
                            href="{{ Route::has('superadmin.unit-penangan') ? route('superadmin.unit-penangan') : '#' }}"
                            aria-current="page"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold bg-[#FCE8E8] text-[#B01116] transition shadow-xs"
                        >
                            <svg class="w-5 h-5 text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="18" r="3"/>
                                <circle cx="6" cy="6" r="3"/>
                                <circle cx="18" cy="6" r="3"/>
                                <path d="M18 9v1a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V9"/>
                                <path d="M12 12v3"/>
                            </svg>
                            <span>Unit Penangan</span>
                        </a>

                        <!-- Menu 4: Kategori & Penyaluran -->
                        <a
                            href="#"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#252525] hover:bg-gray-100 hover:text-[#B01116] transition group"
                        >
                            <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"/>
                                <polyline points="12 5 19 12 12 19"/>
                            </svg>
                            <span>Kategori &amp; Penyaluran</span>
                        </a>

                        <!-- Menu 5: Log Aktivitas -->
                        <a
                            href="{{ Route::has('superadmin.log') ? route('superadmin.log') : '#' }}"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#252525] hover:bg-gray-100 hover:text-[#B01116] transition group"
                        >
                            <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                                <line x1="16" y1="13" x2="8" y2="13"/>
                                <line x1="16" y1="17" x2="8" y2="17"/>
                            </svg>
                            <span>Log Aktivitas</span>
                        </a>
                    </nav>
                </div>

                <!-- Bagian Bawah: Profil Saya & Keluar -->
                <div class="px-4 py-3 border-t border-[#E5E7EB] space-y-1">
                    <a
                        href="#"
                        class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#252525] hover:bg-gray-100 hover:text-[#B01116] transition group"
                    >
                        <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        <span>Profil Saya</span>
                    </a>

                    @if (Route::has('logout'))
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
                        <a
                            href="#"
                            class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#B01116] hover:bg-red-50 transition text-left"
                        >
                            <svg class="w-5 h-5 text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                <polyline points="16 17 21 12 16 7"/>
                                <line x1="21" y1="12" x2="9" y2="12"/>
                            </svg>
                            <span>Keluar</span>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Kartu Bantuan -->
            <div class="p-4 border-t border-[#E5E7EB]">
                <div class="rounded-xl bg-[#B01116] p-4 text-[#FFFFFF] shadow-sm">
                    <h4 class="text-sm font-bold tracking-tight text-[#FFFFFF]">Butuh bantuan?</h4>
                    <p class="text-[11px] text-[#FFFFFF]/85 mt-1 leading-snug">Hubungi pusat layanan CampusVoice.</p>
                    <a
                        href="{{ Route::has('pusat.bantuan') ? route('pusat.bantuan') : '#' }}"
                        class="mt-3 inline-block py-1.5 px-3.5 bg-[#FFFFFF] hover:bg-gray-100 text-[#B01116] text-xs font-bold rounded-lg text-center shadow-xs transition"
                    >
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
                <!-- Hamburger Mobile & Search Bar Header -->
                <div class="flex items-center gap-3">
                    <button
                        type="button"
                        @click="mobileSidebarOpen = !mobileSidebarOpen"
                        class="p-2 -ml-2 rounded-lg text-[#6B7280] hover:text-[#252525] hover:bg-gray-100 lg:hidden transition"
                        title="Buka Menu"
                        aria-label="Buka Menu"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <!-- Search Bar Header -->
                    <div class="relative w-52 sm:w-80 md:w-96">
                        <input
                            type="text"
                            x-model="searchHeader"
                            placeholder="Cari nomor tiket atau laporan.."
                            aria-label="Cari nomor tiket atau laporan"
                            class="w-full bg-[#F9FAFB] border border-[#E5E7EB] rounded-lg py-2 px-3.5 text-xs sm:text-sm text-[#252525] placeholder-[#6B7280] focus:bg-[#FFFFFF] focus:border-[#B01116] focus:outline-none focus:ring-1 focus:ring-[#B01116] transition"
                        />
                    </div>
                </div>

                <!-- Bagian Kanan: Lonceng Notifikasi & Profil Super Admin -->
                <div class="flex items-center gap-3 sm:gap-4">
                    <!-- Tombol Lonceng Notifikasi -->
                    <button
                        type="button"
                        class="relative p-2 rounded-lg border border-[#E5E7EB] text-[#252525] hover:text-[#B01116] hover:bg-gray-50 transition cursor-pointer"
                        title="Notifikasi"
                        aria-label="Notifikasi"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span class="absolute -top-1.5 -right-1.5 w-4 h-4 rounded-full bg-[#B01116] text-[#FFFFFF] text-[10px] font-bold flex items-center justify-center shadow-xs">
                            3
                        </span>
                    </button>

                    <!-- Profil Super Admin: Rizky Pratama -->
                    <div class="flex items-center gap-2.5 pl-1">
                        <div class="w-9 h-9 rounded-lg bg-[#FCE8E8] text-[#B01116] font-bold text-xs flex items-center justify-center shrink-0">
                            RP
                        </div>
                        <div class="hidden sm:block text-left">
                            <p class="text-xs font-bold text-[#252525] leading-tight">Rizky Pratama</p>
                            <p class="text-[11px] text-[#6B7280] leading-tight">Super Admin</p>
                        </div>
                    </div>
                </div>
            </header>

            <!-- ========================================================= -->
            <!-- AREA KONTEN UTAMA: Linear Gradient #B01116 ke #58595B -->
            <!-- ========================================================= -->
            <main class="flex-1 content-gradient-bg px-4 sm:px-8 lg:px-10 py-7 text-[#FFFFFF]">
                <div class="max-w-7xl mx-auto space-y-5">

                    <!-- Toast Notifikasi -->
                    <div
                        x-show="toastMessage"
                        x-cloak
                        x-transition
                        class="rounded-xl bg-[#ECFDF5] border border-[#16A34A]/25 text-[#16A34A] px-4 py-3 text-xs sm:text-sm font-semibold flex items-center justify-between shadow-md"
                    >
                        <span x-text="toastMessage"></span>
                        <button type="button" @click="toastMessage = ''" class="text-[#16A34A] hover:opacity-75 cursor-pointer">&times;</button>
                    </div>

                    <!-- Header Halaman (Atas) -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h1 class="text-2xl sm:text-[28px] font-extrabold text-[#FFFFFF] tracking-tight leading-tight">
                                Unit Penangan
                            </h1>
                            <p class="text-xs sm:text-sm text-[#FFFFFF]/85 mt-1 font-normal">
                                Data master unit dapat diperbarui tanpa mengubah struktur aplikasi.
                            </p>
                        </div>

                        <div class="shrink-0">
                            <button
                                type="button"
                                @click="openAddModal()"
                                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#FFFFFF] hover:bg-gray-50 text-[#B01116] text-xs sm:text-sm font-bold shadow-sm transition cursor-pointer"
                            >
                                <span class="text-base leading-none font-bold">+</span>
                                <span>Tambah Unit</span>
                            </button>
                        </div>
                    </div>

                    <!-- KARTU PUTIH BESAR: TAB + TABEL UNIT PENANGAN -->
                    <section class="bg-[#FFFFFF] rounded-2xl border border-[#E5E7EB] shadow-xl overflow-hidden text-[#252525]">

                        <!-- Navigasi Tab -->
                        <div class="px-5 sm:px-7 pt-4 border-b border-[#E5E7EB] flex items-center gap-7 bg-[#FFFFFF]">
                            <!-- Tab 1: Direktorat 13 -->
                            <button
                                type="button"
                                @click="activeTab = 'direktorat'"
                                class="pb-3.5 text-xs sm:text-sm font-bold transition flex items-center gap-2 border-b-2 -mb-[1px] cursor-pointer"
                                :class="activeTab === 'direktorat'
                                    ? 'text-[#B01116] border-[#B01116]'
                                    : 'text-[#6B7280] border-transparent hover:text-[#252525]'"
                            >
                                <span>Direktorat</span>
                                <span
                                    class="inline-flex items-center justify-center min-w-[22px] h-[20px] px-1.5 rounded-full text-[11px] font-bold transition"
                                    :class="activeTab === 'direktorat'
                                        ? 'bg-[#FCE8E8] text-[#B01116]'
                                        : 'bg-[#F3F4F6] text-[#6B7280]'"
                                    x-text="direktoratBadgeCount"
                                >
                                    13
                                </span>
                            </button>

                            <!-- Tab 2: Program Studi 11 -->
                            <button
                                type="button"
                                @click="activeTab = 'prodi'"
                                class="pb-3.5 text-xs sm:text-sm font-bold transition flex items-center gap-2 border-b-2 -mb-[1px] cursor-pointer"
                                :class="activeTab === 'prodi'
                                    ? 'text-[#B01116] border-[#B01116]'
                                    : 'text-[#6B7280] border-transparent hover:text-[#252525]'"
                            >
                                <span>Program Studi</span>
                                <span
                                    class="inline-flex items-center justify-center min-w-[22px] h-[20px] px-1.5 rounded-full text-[11px] font-bold transition"
                                    :class="activeTab === 'prodi'
                                        ? 'bg-[#FCE8E8] text-[#B01116]'
                                        : 'bg-[#F3F4F6] text-[#6B7280]'"
                                    x-text="prodiBadgeCount"
                                >
                                    11
                                </span>
                            </button>
                        </div>

                        <!-- Kontainer Tabel dengan Scroll Vertikal & Horizontal Rapi -->
                        <div class="p-4 sm:p-6">
                            <div class="overflow-x-auto overflow-y-auto max-h-[560px] pr-1">
                                <table class="w-full min-w-[760px] text-left border-collapse">
                                    <thead class="sticky top-0 z-10 bg-[#F9FAFB]">
                                        <tr class="border-b border-[#E5E7EB]">
                                            <th scope="col" class="py-3 px-4 text-[11px] font-bold uppercase tracking-wider text-[#6B7280] rounded-l-lg">
                                                NAMA UNIT
                                            </th>
                                            <th scope="col" class="py-3 px-4 text-[11px] font-bold uppercase tracking-wider text-[#6B7280]">
                                                TIPE
                                            </th>
                                            <th scope="col" class="py-3 px-4 text-[11px] font-bold uppercase tracking-wider text-[#6B7280]">
                                                PETUGAS
                                            </th>
                                            <th scope="col" class="py-3 px-4 text-[11px] font-bold uppercase tracking-wider text-[#6B7280]">
                                                STATUS
                                            </th>
                                            <th scope="col" class="py-3 px-4 rounded-r-lg text-right">
                                                <span class="sr-only">Aksi</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[#E5E7EB]">
                                        <template x-for="(unit, idx) in displayedUnits" :key="unit.tipe + '-' + unit.id">
                                            <tr class="hover:bg-[#F9FAFB]/80 transition">
                                                <!-- Nama Unit + Nomor Urut 01 s.d. 14 -->
                                                <td class="py-4 px-4">
                                                    <div class="flex items-center gap-3.5">
                                                        <span
                                                            class="w-7 h-7 rounded-lg bg-[#F3F4F6] border border-[#E5E7EB] text-[#6B7280] text-[11px] font-bold flex items-center justify-center shrink-0"
                                                            x-text="formatNo(idx + 1)"
                                                        ></span>
                                                        <span class="text-xs sm:text-[13px] font-bold text-[#252525]" x-text="unit.nama"></span>
                                                    </div>
                                                </td>

                                                <!-- Tipe Unit -->
                                                <td class="py-4 px-4 text-xs sm:text-[13px] text-[#6B7280] whitespace-nowrap" x-text="unit.tipe"></td>

                                                <!-- Petugas / Mahasiswa -->
                                                <td class="py-4 px-4 text-xs sm:text-[13px] text-[#6B7280] whitespace-nowrap" x-text="unit.jumlahLabel"></td>

                                                <!-- Status -->
                                                <td class="py-4 px-4 whitespace-nowrap">
                                                    <span
                                                        class="inline-flex items-center justify-center px-3.5 py-1 rounded-full text-xs font-semibold"
                                                        :class="unit.status === 'Aktif'
                                                            ? 'bg-[#ECFDF5] text-[#16A34A]'
                                                            : 'bg-gray-100 text-[#6B7280]'"
                                                        x-text="unit.status"
                                                    ></span>
                                                </td>

                                                <!-- Aksi: Kelola -> -->
                                                <td class="py-4 px-4 text-right whitespace-nowrap">
                                                    <button
                                                        type="button"
                                                        @click="openManageModal(unit)"
                                                        class="inline-flex items-center gap-1 text-xs sm:text-[13px] font-bold text-[#B01116] hover:text-[#8A0D12] transition cursor-pointer"
                                                    >
                                                        <span>Kelola &rarr;</span>
                                                    </button>
                                                </td>
                                            </tr>
                                        </template>

                                        <!-- Empty state pencarian -->
                                        <tr x-show="displayedUnits.length === 0" x-cloak>
                                            <td colspan="5" class="py-10 px-4 text-center text-xs sm:text-sm text-[#6B7280]">
                                                Tidak ada unit penangan yang cocok dengan kata kunci pencarian.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </section>
                </div>
            </main>

        </div>
    </div>

    <!-- ========================================================= -->
    <!-- MODAL: TAMBAH UNIT BARU -->
    <!-- ========================================================= -->
    <div
        x-show="showAddModal"
        x-cloak
        @keydown.escape.window="showAddModal = false"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs"
    >
        <div
            @click.outside="showAddModal = false"
            class="w-full max-w-md bg-[#FFFFFF] rounded-2xl border border-[#E5E7EB] shadow-2xl p-6 text-[#252525]"
        >
            <div class="flex items-center justify-between pb-4 border-b border-[#E5E7EB]">
                <div>
                    <h3 class="text-base font-bold text-[#252525]">Tambah Unit Penangan</h3>
                    <p class="text-xs text-[#6B7280] mt-0.5">Tambahkan data master Direktorat atau Program Studi.</p>
                </div>
                <button
                    type="button"
                    @click="showAddModal = false"
                    class="p-1.5 rounded-lg text-[#6B7280] hover:text-[#252525] hover:bg-gray-100 transition cursor-pointer"
                    aria-label="Tutup modal"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="py-4 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-[#252525] mb-1.5">Nama Unit</label>
                    <input
                        type="text"
                        x-model="newUnit.nama"
                        placeholder="Contoh: Direktorat Inovasi Kampus"
                        class="w-full rounded-xl border border-[#E5E7EB] bg-[#FFFFFF] px-3.5 py-2.5 text-xs sm:text-sm text-[#252525] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116] focus:outline-none"
                    />
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-[#252525] mb-1.5">Tipe Unit</label>
                        <select
                            x-model="newUnit.tipe"
                            class="w-full rounded-xl border border-[#E5E7EB] bg-[#FFFFFF] px-3 py-2.5 text-xs sm:text-sm text-[#252525] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116] focus:outline-none"
                        >
                            <option value="Direktorat">Direktorat</option>
                            <option value="Program Studi">Program Studi</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[#252525] mb-1.5">Jumlah Petugas</label>
                        <input
                            type="text"
                            x-model="newUnit.jumlahLabel"
                            placeholder="Contoh: 2 petugas"
                            class="w-full rounded-xl border border-[#E5E7EB] bg-[#FFFFFF] px-3.5 py-2.5 text-xs sm:text-sm text-[#252525] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116] focus:outline-none"
                        />
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-[#E5E7EB] flex items-center justify-end gap-2.5">
                <button
                    type="button"
                    @click="showAddModal = false"
                    class="px-4 py-2 rounded-xl border border-[#E5E7EB] text-xs font-bold text-[#252525] hover:bg-gray-50 transition cursor-pointer"
                >
                    Batal
                </button>
                <button
                    type="button"
                    @click="saveNewUnit()"
                    class="px-4 py-2 rounded-xl bg-[#B01116] hover:bg-[#8A0D12] text-xs font-bold text-[#FFFFFF] transition cursor-pointer"
                >
                    Simpan Unit
                </button>
            </div>
        </div>
    </div>

    <!-- ========================================================= -->
    <!-- MODAL: KELOLA UNIT -->
    <!-- ========================================================= -->
    <div
        x-show="showManageModal"
        x-cloak
        @keydown.escape.window="showManageModal = false"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs"
    >
        <div
            @click.outside="showManageModal = false"
            class="w-full max-w-md bg-[#FFFFFF] rounded-2xl border border-[#E5E7EB] shadow-2xl p-6 text-[#252525]"
        >
            <div class="flex items-center justify-between pb-4 border-b border-[#E5E7EB]">
                <div>
                    <h3 class="text-base font-bold text-[#252525]">Kelola Unit Penangan</h3>
                    <p class="text-xs text-[#6B7280] mt-0.5">Perbarui rincian atau status keaktifan unit.</p>
                </div>
                <button
                    type="button"
                    @click="showManageModal = false"
                    class="p-1.5 rounded-lg text-[#6B7280] hover:text-[#252525] hover:bg-gray-100 transition cursor-pointer"
                    aria-label="Tutup modal"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="py-4 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-[#252525] mb-1.5">Nama Unit</label>
                    <input
                        type="text"
                        x-model="editForm.nama"
                        class="w-full rounded-xl border border-[#E5E7EB] bg-[#FFFFFF] px-3.5 py-2.5 text-xs sm:text-sm text-[#252525] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116] focus:outline-none"
                    />
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-[#252525] mb-1.5">Kapasitas / Petugas</label>
                        <input
                            type="text"
                            x-model="editForm.jumlahLabel"
                            class="w-full rounded-xl border border-[#E5E7EB] bg-[#FFFFFF] px-3.5 py-2.5 text-xs sm:text-sm text-[#252525] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116] focus:outline-none"
                        />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[#252525] mb-1.5">Status</label>
                        <select
                            x-model="editForm.status"
                            class="w-full rounded-xl border border-[#E5E7EB] bg-[#FFFFFF] px-3 py-2.5 text-xs sm:text-sm text-[#252525] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116] focus:outline-none"
                        >
                            <option value="Aktif">Aktif</option>
                            <option value="Nonaktif">Nonaktif</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-[#E5E7EB] flex items-center justify-end gap-2.5">
                <button
                    type="button"
                    @click="showManageModal = false"
                    class="px-4 py-2 rounded-xl border border-[#E5E7EB] text-xs font-bold text-[#252525] hover:bg-gray-50 transition cursor-pointer"
                >
                    Batal
                </button>
                <button
                    type="button"
                    @click="saveManageUnit()"
                    class="px-4 py-2 rounded-xl bg-[#B01116] hover:bg-[#8A0D12] text-xs font-bold text-[#FFFFFF] transition cursor-pointer"
                >
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</body>
</html>

