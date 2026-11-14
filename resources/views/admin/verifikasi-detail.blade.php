@php
    $tiketId = $tiket ?? request()->route('tiket') ?? 'CV-2026-00132';

    $daftarLaporan = [
        'CV-2026-00132' => [
            'tiket' => 'CV-2026-00132',
            'judul' => 'Jadwal kuliah belum sinkron',
            'waktu' => 'Diterima hari ini, 09.42 WIB',
            'anonim' => true,
            'kategori' => 'SSC',
            'lokasi' => 'Portal Akademik',
            'pelapor' => 'Nadia Putri • 102062400089',
            'prodi' => 'S1 Sistem Informasi',
            'deskripsi' => 'Jadwal mata kuliah Analisis Proses Bisnis pada portal belum sesuai dengan pengumuman program studi. Mohon dibantu verifikasi agar tidak terjadi kekeliruan ruang dan waktu.',
            'lampiran_nama' => 'tangkapan_layar_jadwal.pdf',
            'lampiran_meta' => '820 KB • PDF',
            'rekomendasi_unit' => 'Program Studi S1 Sistem Informasi',
            'rekomendasi_alasan' => 'Berdasarkan isu jadwal kuliah dan program studi pelapor.',
            'dasar_penyaluran' => 'Isu jadwal perkuliahan dikelola program studi pelapor.',
        ],
        'CV-2026-00131' => [
            'tiket' => 'CV-2026-00131',
            'judul' => 'Lampu koridor Gedung A mati',
            'waktu' => 'Diterima hari ini, 08.15 WIB',
            'anonim' => true,
            'kategori' => 'Satpras',
            'lokasi' => 'Koridor Lantai 2, Gedung A',
            'pelapor' => 'Bima Pratama • 102042400045',
            'prodi' => 'S1 Teknik Industri',
            'deskripsi' => 'Lampu penerangan di sepanjang koridor lantai 2 Gedung A padam sejak kemarin sore sehingga area cukup gelap saat perkuliahan malam.',
            'lampiran_nama' => 'foto_koridor_gedung_a.jpg',
            'lampiran_meta' => '1.4 MB • JPG',
            'rekomendasi_unit' => 'SATPRAS (Sarana Prasarana)',
            'rekomendasi_alasan' => 'Berdasarkan kategori fasilitas gedung dan lokasi fisik.',
            'dasar_penyaluran' => 'Perbaikan fasilitas listrik dan penerangan gedung dikelola oleh Satpras.',
        ],
        'CV-2026-00130' => [
            'tiket' => 'CV-2026-00130',
            'judul' => 'Akses Wi-Fi laboratorium terputus',
            'waktu' => 'Diterima kemarin, 16.30 WIB',
            'anonim' => false,
            'kategori' => 'SSC',
            'lokasi' => 'Laboratorium Komputer B-204',
            'pelapor' => 'Raka Aditya • 102012400112',
            'prodi' => 'S1 Teknik Informatika',
            'deskripsi' => 'Koneksi jaringan Wi-Fi di Laboratorium Komputer B-204 terputus secara berkala dan tidak dapat terhubung ke server praktikum.',
            'lampiran_nama' => 'log_koneksi_lab.pdf',
            'lampiran_meta' => '540 KB • PDF',
            'rekomendasi_unit' => 'Direktorat Pusat Teknologi Informasi',
            'rekomendasi_alasan' => 'Berdasarkan kendala infrastruktur jaringan Wi-Fi laboratorium.',
            'dasar_penyaluran' => 'Gangguan konektivitas jaringan kampus ditangani oleh Direktorat Pusat Teknologi Informasi.',
        ],
    ];

    $laporan = $daftarLaporan[$tiketId] ?? $daftarLaporan['CV-2026-00132'];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#FFFFFF]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Detail Verifikasi {{ $laporan['tiket'] }} - Admin SSC CampusVoice</title>

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
        searchHeader: '',
        check1: true,
        check2: true,
        check3: true,
        prioritas: '',
        catatanInternal: '',
        jenisUnit: '',
        unitPenangan: '',
        unitOptions: {
            'Prodi': [
                'Program Studi S1 Sistem Informasi',
                'Program Studi S1 Teknologi Informasi',
                'Program Studi S1 Teknik Informatika',
                'Program Studi S1 Rekayasa Perangkat Lunak',
                'Program Studi S1 Sains Data',
                'Program Studi S1 Teknik Elektro',
                'Program Studi S1 Teknik Industri',
                'Program Studi S1 Teknik Komputer',
                'Program Studi S1 Teknik Logistik',
                'Program Studi S1 Bisnis Digital',
                'Program Studi S1 Teknik Telekomunikasi'
            ],
            'Direktorat': [
                'Direktorat Sekretariat & Perencanaan Strategis',
                'Direktorat Bandung Techno Park',
                'Direktorat Akademik',
                'Direktorat Pasca Sarjana dan Advance Learning',
                'Direktorat Logistik dan Aset',
                'Direktorat Keuangan',
                'Direktorat Sumber Daya Manusia',
                'Direktorat Pusat Teknologi Informasi',
                'Direktorat Pemasaran & Admisi',
                'Direktorat Pengembangan Karir, Alumni, & Endowment',
                'Direktorat Kemahasiswaan',
                'Direktorat Kerjasama Strategis & Kantor Urusan Internasional',
                'Direktorat Penelitian dan Pengabdian Masyarakat'
            ],
            'Satpras': [
                'SATPRAS (Sarana Prasarana)'
            ]
        },
        get availableUnits() {
            if (this.jenisUnit && this.unitOptions[this.jenisUnit]) {
                return this.unitOptions[this.jenisUnit];
            }
            return [
                ...this.unitOptions['Prodi'],
                ...this.unitOptions['Direktorat'],
                ...this.unitOptions['Satpras']
            ];
        },
        onJenisUnitChange() {
            if (this.jenisUnit === 'Satpras') {
                this.unitPenangan = 'SATPRAS (Sarana Prasarana)';
            } else if (!this.availableUnits.includes(this.unitPenangan)) {
                this.unitPenangan = '';
            }
        },
        dasarPenyaluran: @js($laporan['dasar_penyaluran']),
        showPreviewModal: false,
        showRejectModal: false,
        showConfirmModal: false,
        isSubmittingForward: false,
        alasanTolak: '',
        actionMessage: '',
        actionType: '',
        handleTeruskan() {
            this.showConfirmModal = true;
        },
        confirmPenyaluran() {
            this.isSubmittingForward = true;
            setTimeout(() => {
                const targetUnit = this.unitPenangan || @js($laporan['rekomendasi_unit']);
                this.isSubmittingForward = false;
                this.showConfirmModal = false;
                this.actionType = 'success';
                this.actionMessage = 'Laporan {{ $laporan['tiket'] }} berhasil diverifikasi dan diteruskan ke ' + targetUnit + '.';
            }, 600);
        },
        handleTolak() {
            this.showRejectModal = false;
            this.actionType = 'rejected';
            this.actionMessage = 'Laporan {{ $laporan['tiket'] }} telah ditolak dan dikembalikan dengan catatan.';
        }
    }"
    x-effect="
        const locked = showConfirmModal || showPreviewModal || showRejectModal;
        document.documentElement.classList.toggle('overflow-hidden', locked);
        document.body.classList.toggle('overflow-hidden', locked);
    "
>
    <div class="min-h-screen flex flex-col lg:flex-row">

        <!-- ========================================================= -->
        <!-- SIDEBAR KIRI (ADMIN SSC) -->
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

                <!-- Navigasi Menu Admin SSC -->
                <div class="px-4 py-3 flex-1">
                    <p class="px-3 text-[11px] font-bold text-[#252525] tracking-wider mb-2 uppercase">Admin SSC</p>

                    <nav class="space-y-1">
                        <!-- Menu 1: Dashboard -->
                        <a
                            href="{{ route('admin.dashboard') }}"
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

                        <!-- Menu 2: Verifikasi Laporan (AKTIF) -->
                        <a
                            href="{{ route('admin.verifikasi') }}"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold bg-[#FCE8E8] text-[#B01116] transition shadow-xs"
                        >
                            <svg class="w-5 h-5 text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                <path d="m9 12 2 2 4-4"/>
                            </svg>
                            <span>Verifikasi Laporan</span>
                        </a>

                        <!-- Menu 3: Monitoring Laporan -->
                        <a
                            href="{{ route('admin.monitoring') }}"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#252525] hover:bg-gray-100 hover:text-[#B01116] transition group"
                        >
                            <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="20" x2="12" y2="10"/>
                                <line x1="18" y1="20" x2="18" y2="4"/>
                                <line x1="6" y1="20" x2="6" y2="16"/>
                            </svg>
                            <span>Monitoring Laporan</span>
                        </a>

                        <!-- Menu 4: Rekapitulasi -->
                        <a
                            href="{{ route('admin.rekapitulasi') }}"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#252525] hover:bg-gray-100 hover:text-[#B01116] transition group"
                        >
                            <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="7 10 12 15 17 10"/>
                                <line x1="12" y1="15" x2="12" y2="3"/>
                            </svg>
                            <span>Rekapitulasi</span>
                        </a>

                        <!-- Menu 5: Notifikasi (Badge 3) -->
                        <a
                            href="{{ route('admin.notifikasi') }}"
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
                                3
                            </span>
                        </a>
                    </nav>
                </div>

                <!-- Bagian Bawah: Profil & Keluar -->
                <div class="px-4 py-3 border-t border-[#E5E7EB] space-y-1">
                    <a
                        href="{{ route('admin.profil') }}"
                        class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#252525] hover:bg-gray-100 hover:text-[#B01116] transition group"
                    >
                        <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        <span>Profil Saya</span>
                    </a>

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
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <!-- Search Bar Header -->
                    <div class="relative w-64 sm:w-80 md:w-96">
                        <svg class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-[#6B7280]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input
                            type="text"
                            x-model="searchHeader"
                            placeholder="Cari nomor tiket atau laporan..."
                            class="w-full bg-[#FFFFFF] border border-[#E5E7EB] rounded-lg py-2 pl-10 pr-3.5 text-xs sm:text-sm text-[#252525] placeholder-[#6B7280] focus:border-[#B01116] focus:outline-none focus:ring-1 focus:ring-[#B01116] transition"
                        />
                    </div>
                </div>

                <!-- Bagian Kanan: Lonceng Notifikasi & Profil Admin SSC -->
                <div class="flex items-center gap-3 sm:gap-4">
                    <!-- Tombol Lonceng Notifikasi -->
                    <a
                        href="{{ route('admin.notifikasi') }}"
                        class="relative p-2 rounded-lg border border-[#E5E7EB] text-[#6B7280] hover:text-[#B01116] hover:bg-gray-50 transition cursor-pointer"
                        title="Notifikasi"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span class="absolute -top-1.5 -right-1.5 w-4 h-4 rounded-full bg-[#B01116] text-[#FFFFFF] text-[10px] font-bold flex items-center justify-center shadow-xs">
                            3
                        </span>
                    </a>

                    <!-- Profil Admin SSC: Siti Rahmawati -->
                    <a href="{{ route('admin.profil') }}" class="flex items-center gap-2.5 pl-1 group">
                        <div class="w-9 h-9 rounded-lg bg-[#FCE8E8] text-[#B01116] font-bold text-xs flex items-center justify-center shrink-0">
                            SR
                        </div>
                        <div class="hidden sm:block text-left">
                            <p class="text-xs font-bold text-[#252525] leading-tight group-hover:text-[#B01116] transition">Siti Rahmawati</p>
                            <p class="text-[11px] text-[#6B7280] leading-tight">Admin SSC</p>
                        </div>
                    </a>
                </div>
            </header>

            <!-- ========================================================= -->
            <!-- AREA KONTEN UTAMA: Linear Gradient #B01116 ke #58595B -->
            <!-- ========================================================= -->
            <main class="flex-1 content-gradient-bg px-4 sm:px-8 lg:px-10 py-7 text-[#FFFFFF] overflow-y-auto">
                <div class="max-w-7xl mx-auto space-y-6">

                    <!-- Header Bagian Konten & Navigasi Kembali -->
                    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-widest text-[#FFFFFF]/85">
                                VERIFIKASI &bull; {{ $laporan['tiket'] }}
                            </p>
                            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#FFFFFF] tracking-tight mt-0.5">
                                {{ $laporan['judul'] }}
                            </h1>
                            <p class="text-xs sm:text-sm text-[#FFFFFF]/85 mt-1 font-normal">
                                {{ $laporan['waktu'] }}
                            </p>
                        </div>

                        <a
                            href="{{ route('admin.verifikasi') }}"
                            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-xs font-semibold text-[#FFFFFF] border border-white/20 backdrop-blur-xs transition self-start sm:self-auto"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            <span>Kembali ke Daftar</span>
                        </a>
                    </div>

                    <!-- Banner Notifikasi Aksi (Alpine.js) -->
                    <div
                        x-show="actionMessage"
                        x-cloak
                        x-transition
                        class="rounded-2xl p-4 shadow-lg flex items-center justify-between gap-3 border"
                        :class="{
                            'bg-emerald-50 border-emerald-200 text-emerald-800': actionType === 'success',
                            'bg-red-50 border-red-200 text-[#B01116]': actionType === 'error' || actionType === 'rejected'
                        }"
                    >
                        <div class="flex items-center gap-3 text-xs sm:text-sm font-semibold">
                            <svg x-show="actionType === 'success'" class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <svg x-show="actionType !== 'success'" class="w-5 h-5 text-[#B01116] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span x-text="actionMessage"></span>
                        </div>
                        <button type="button" @click="actionMessage = ''" class="text-xs font-bold opacity-70 hover:opacity-100 cursor-pointer">&times;</button>
                    </div>

                    <!-- TATA LETAK GRID 2 KOLOM (Kiri: Kartu 1 Detail Laporan | Kanan: Kartu 2 & 3 Form Verifikasi) -->
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                        <!-- ===================================================== -->
                        <!-- KOLOM KIRI: KARTU PUTIH 1 - DETAIL LAPORAN -->
                        <!-- ===================================================== -->
                        <div class="lg:col-span-6 w-full bg-[#FFFFFF] rounded-2xl p-6 text-[#252525] shadow-xl border border-[#E5E7EB] min-w-0">
                            <!-- Judul Kartu dengan Badge Huruf "A" -->
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-lg bg-[#FCE8E8] text-[#B01116] font-bold text-sm flex items-center justify-center shrink-0">
                                    A
                                </div>
                                <div>
                                    <h2 class="text-base font-bold text-[#252525] leading-tight">Detail Laporan</h2>
                                    <p class="text-xs text-[#6B7280] mt-0.5">Informasi laporan dan identitas akademik.</p>
                                </div>
                            </div>

                            <!-- Badge Kecil: Laporan Anonim -->
                            @if ($laporan['anonim'])
                                <div class="mt-5">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#F3F4F6] text-[11px] font-semibold text-[#252525]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#2563EB]"></span>
                                        Laporan Anonim
                                    </span>
                                </div>
                            @endif

                            <!-- Grid 2x2 Info Pelapor & Kategori -->
                            <div class="mt-3 rounded-xl bg-[#F9FAFB] border border-[#F3F4F6] p-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-[#6B7280]">KATEGORI</p>
                                    <p class="text-xs sm:text-sm font-bold text-[#252525] mt-0.5">{{ $laporan['kategori'] }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-[#6B7280]">LOKASI</p>
                                    <p class="text-xs sm:text-sm font-bold text-[#252525] mt-0.5">{{ $laporan['lokasi'] }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-[#6B7280]">
                                        PELAPOR <span class="text-[#6B7280] font-semibold">&bull; KHUSUS SSC</span>
                                    </p>
                                    <p class="text-xs sm:text-sm font-bold text-[#252525] mt-0.5">{{ $laporan['pelapor'] }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-[#6B7280]">PROGRAM STUDI</p>
                                    <p class="text-xs sm:text-sm font-bold text-[#252525] mt-0.5">{{ $laporan['prodi'] }}</p>
                                </div>
                            </div>

                            <!-- Bagian Deskripsi -->
                            <div class="mt-5">
                                <h3 class="text-xs font-bold text-[#252525] mb-1.5">Deskripsi</h3>
                                <p class="text-xs sm:text-sm text-[#6B7280] leading-relaxed">
                                    {{ $laporan['deskripsi'] }}
                                </p>
                            </div>

                            <!-- Kotak Bukti Pendukung -->
                            <div class="mt-5 rounded-xl border border-[#E5E7EB] bg-[#FFFFFF] p-3.5 flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-9 h-9 rounded-lg bg-[#F9FAFB] border border-[#E5E7EB] flex items-center justify-center text-[#6B7280] shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs sm:text-sm font-bold text-[#252525] truncate">{{ $laporan['lampiran_nama'] }}</p>
                                        <p class="text-[11px] text-[#6B7280]">{{ $laporan['lampiran_meta'] }}</p>
                                    </div>
                                </div>
                                <button
                                    type="button"
                                    @click="showPreviewModal = true"
                                    class="text-xs sm:text-sm font-bold text-[#B01116] hover:text-[#970e13] hover:underline shrink-0 cursor-pointer px-2 py-1"
                                >
                                    Lihat
                                </button>
                            </div>
                        </div>

                        <!-- ===================================================== -->
                        <!-- KOLOM KANAN: KARTU PUTIH 2 (VERIFIKASI) & 3 (TUJUAN) -->
                        <!-- ===================================================== -->
                        <div class="lg:col-span-6 w-full space-y-6 min-w-0">

                            <!-- KARTU PUTIH 2: VERIFIKASI -->
                            <div class="bg-[#FFFFFF] rounded-2xl p-6 text-[#252525] shadow-xl border border-[#E5E7EB]">
                                <!-- Judul Kartu dengan Badge Huruf "B" -->
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-[#FCE8E8] text-[#B01116] font-bold text-sm flex items-center justify-center shrink-0">
                                        B
                                    </div>
                                    <div>
                                        <h2 class="text-base font-bold text-[#252525] leading-tight">Verifikasi</h2>
                                        <p class="text-xs text-[#6B7280] mt-0.5">Pastikan laporan lengkap dan layak diteruskan.</p>
                                    </div>
                                </div>

                                <!-- 3 Item Checklist (Kotak Centang Merah #B01116) -->
                                <div class="mt-5 space-y-2.5">
                                    <label class="flex items-center gap-3 p-3 rounded-xl bg-[#F9FAFB] border border-[#F3F4F6] hover:border-[#E5E7EB] transition cursor-pointer">
                                        <input
                                            type="checkbox"
                                            x-model="check1"
                                            class="w-4 h-4 rounded border-[#D1D5DB] text-[#B01116] focus:ring-[#B01116] cursor-pointer"
                                        />
                                        <span class="text-xs sm:text-sm font-medium text-[#252525]">Judul dan deskripsi cukup jelas</span>
                                    </label>

                                    <label class="flex items-center gap-3 p-3 rounded-xl bg-[#F9FAFB] border border-[#F3F4F6] hover:border-[#E5E7EB] transition cursor-pointer">
                                        <input
                                            type="checkbox"
                                            x-model="check2"
                                            class="w-4 h-4 rounded border-[#D1D5DB] text-[#B01116] focus:ring-[#B01116] cursor-pointer"
                                        />
                                        <span class="text-xs sm:text-sm font-medium text-[#252525]">Kategori dan lokasi sesuai</span>
                                    </label>

                                    <label class="flex items-center gap-3 p-3 rounded-xl bg-[#F9FAFB] border border-[#F3F4F6] hover:border-[#E5E7EB] transition cursor-pointer">
                                        <input
                                            type="checkbox"
                                            x-model="check3"
                                            class="w-4 h-4 rounded border-[#D1D5DB] text-[#B01116] focus:ring-[#B01116] cursor-pointer"
                                        />
                                        <span class="text-xs sm:text-sm font-medium text-[#252525]">Bukti pendukung dapat dibuka</span>
                                    </label>
                                </div>

                                <!-- Dropdown Prioritas * -->
                                <div class="mt-5">
                                    <label for="prioritas" class="block text-xs font-bold text-[#252525] mb-1.5">
                                        Prioritas <span class="text-[#B01116]">*</span>
                                    </label>
                                    <div class="relative w-full">
                                        <select
                                            id="prioritas"
                                            x-model="prioritas"
                                            class="w-full appearance-none rounded-xl border border-[#E5E7EB] bg-[#FFFFFF] py-2.5 px-3.5 pr-10 text-xs sm:text-sm text-[#252525] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116] focus:outline-none transition cursor-pointer"
                                        >
                                            <option value="" disabled selected>Pilih prioritas</option>
                                            <option value="Rendah">Rendah — Penanganan reguler (3-5 hari kerja)</option>
                                            <option value="Sedang">Sedang — Perlu perhatian dalam 1-2 hari kerja</option>
                                            <option value="Tinggi">Tinggi — Mendesak / mengganggu aktivitas akademik</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3.5 text-[#6B7280]">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- Textarea Catatan Internal -->
                                <div class="mt-4">
                                    <label for="catatan_internal" class="block text-xs font-bold text-[#252525] mb-1.5">
                                        Catatan internal
                                    </label>
                                    <textarea
                                        id="catatan_internal"
                                        rows="3"
                                        x-model="catatanInternal"
                                        placeholder="Catatan hanya untuk staf SSC..."
                                        class="w-full rounded-xl border border-[#E5E7EB] p-3.5 text-xs sm:text-sm text-[#252525] placeholder-[#6B7280] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116] focus:outline-none transition resize-y"
                                    ></textarea>
                                </div>
                            </div>

                            <!-- KARTU PUTIH 3: TUJUAN PENYALURAN -->
                            <div class="bg-[#FFFFFF] rounded-2xl p-6 text-[#252525] shadow-xl border border-[#E5E7EB]">
                                <!-- Judul Kartu dengan Badge Huruf "C" -->
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-[#FCE8E8] text-[#B01116] font-bold text-sm flex items-center justify-center shrink-0">
                                        C
                                    </div>
                                    <div>
                                        <h2 class="text-base font-bold text-[#252525] leading-tight">Tujuan Penyaluran</h2>
                                        <p class="text-xs text-[#6B7280] mt-0.5">Konfirmasi rekomendasi sebelum diteruskan.</p>
                                    </div>
                                </div>

                                <!-- Kotak Rekomendasi Sistem (#EFF6FF) -->
                                <div class="mt-5 rounded-xl bg-[#EFF6FF] border border-[#DBEAFE] p-4 flex items-start gap-3">
                                    <div class="text-[#2563EB] shrink-0 mt-0.5">
                                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                            <path d="m9 12 2 2 4-4"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs sm:text-sm font-bold text-[#252525] leading-snug">
                                            Rekomendasi sistem: {{ $laporan['rekomendasi_unit'] }}
                                        </p>
                                        <p class="text-[11px] text-[#6B7280] mt-0.5">
                                            {{ $laporan['rekomendasi_alasan'] }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Dropdown Jenis Unit * -->
                                <div class="mt-5">
                                    <label for="jenis_unit" class="block text-xs font-bold text-[#252525] mb-1.5">
                                        Jenis Unit <span class="text-[#B01116]">*</span>
                                    </label>
                                    <div class="relative w-full">
                                        <select
                                            id="jenis_unit"
                                            x-model="jenisUnit"
                                            @change="onJenisUnitChange()"
                                            class="w-full appearance-none rounded-xl border border-[#E5E7EB] bg-[#FFFFFF] py-2.5 px-3.5 pr-10 text-xs sm:text-sm text-[#252525] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116] focus:outline-none transition cursor-pointer"
                                        >
                                            <option value="" disabled selected>Pilih jenis unit</option>
                                            <option value="Prodi">Prodi</option>
                                            <option value="Direktorat">Direktorat</option>
                                            <option value="Satpras">Satpras</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3.5 text-[#6B7280]">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- Dropdown Unit Penangan * -->
                                <div class="mt-4">
                                    <label for="unit_penangan" class="block text-xs font-bold text-[#252525] mb-1.5">
                                        Unit Penangan <span class="text-[#B01116]">*</span>
                                    </label>
                                    <div class="relative w-full">
                                        <select
                                            id="unit_penangan"
                                            x-model="unitPenangan"
                                            @change="if (!jenisUnit) { for (const [k, list] of Object.entries(unitOptions)) { if (list.includes(unitPenangan)) { jenisUnit = k; break; } } }"
                                            class="w-full appearance-none rounded-xl border border-[#E5E7EB] bg-[#FFFFFF] py-2.5 px-3.5 pr-10 text-xs sm:text-sm text-[#252525] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116] focus:outline-none transition cursor-pointer"
                                        >
                                            <option value="" disabled selected>Pilih unit penangan</option>
                                            <template x-for="unit in availableUnits" :key="unit">
                                                <option :value="unit" x-text="unit"></option>
                                            </template>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3.5 text-[#6B7280]">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- Input Teks Dasar Penyaluran -->
                                <div class="mt-4">
                                    <label for="dasar_penyaluran" class="block text-xs font-bold text-[#252525] mb-1.5">
                                        Dasar penyaluran
                                    </label>
                                    <input
                                        type="text"
                                        id="dasar_penyaluran"
                                        x-model="dasarPenyaluran"
                                        class="w-full rounded-xl border border-[#E5E7EB] bg-[#FFFFFF] py-2.5 px-3.5 text-xs sm:text-sm text-[#252525] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116] focus:outline-none transition"
                                    />
                                </div>

                                <!-- Area Tombol Bawah Kanan -->
                                <div class="mt-6 pt-2 flex flex-wrap items-center justify-end gap-3">
                                    <button
                                        type="button"
                                        @click="showRejectModal = true"
                                        class="px-5 py-2.5 rounded-xl bg-[#FCE8E8] hover:bg-[#fbd5d5] text-xs sm:text-sm font-bold text-[#B01116] transition cursor-pointer"
                                    >
                                        Tolak Laporan
                                    </button>
                                    <button
                                        type="button"
                                        @click="handleTeruskan()"
                                        class="px-5 py-2.5 rounded-xl bg-[#B01116] hover:bg-[#970e13] text-xs sm:text-sm font-bold text-[#FFFFFF] shadow-sm transition cursor-pointer"
                                    >
                                        Teruskan ke Unit
                                    </button>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>
            </main>

        </div>
    </div>

    <!-- Modal Pratinjau Bukti Pendukung -->
    <div
        x-show="showPreviewModal"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs"
        @keydown.escape.window="showPreviewModal = false"
    >
        <div @click.away="showPreviewModal = false" class="bg-[#FFFFFF] rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-[#E5E7EB]">
            <div class="flex items-center justify-between border-b border-[#E5E7EB] pb-3">
                <div>
                    <h3 class="text-sm font-bold text-[#252525]">Pratinjau Bukti Pendukung</h3>
                    <p class="text-[11px] text-[#6B7280]">{{ $laporan['lampiran_nama'] }} ({{ $laporan['lampiran_meta'] }})</p>
                </div>
                <button type="button" @click="showPreviewModal = false" class="p-1 rounded-lg text-[#6B7280] hover:text-[#252525] hover:bg-gray-100 cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="my-5 rounded-xl bg-[#F9FAFB] border border-dashed border-[#E5E7EB] p-8 text-center">
                <svg class="w-12 h-12 mx-auto text-[#B01116] mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-sm font-bold text-[#252525]">{{ $laporan['lampiran_nama'] }}</p>
                <p class="text-xs text-[#6B7280] mt-1">Dokumen lampiran laporan siap diperiksa oleh Admin SSC.</p>
            </div>
            <div class="flex justify-end">
                <button type="button" @click="showPreviewModal = false" class="px-4 py-2 rounded-xl bg-[#B01116] text-xs font-bold text-[#FFFFFF] hover:bg-[#970e13] transition cursor-pointer">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Tolak Laporan -->
    <div
        x-show="showRejectModal"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs"
        @keydown.escape.window="showRejectModal = false"
    >
        <div @click.away="showRejectModal = false" class="bg-[#FFFFFF] rounded-2xl max-w-md w-full p-6 shadow-2xl border border-[#E5E7EB]">
            <h3 class="text-base font-bold text-[#252525]">Tolak Laporan {{ $laporan['tiket'] }}?</h3>
            <p class="text-xs text-[#6B7280] mt-1">Berikan alasan penolakan yang jelas agar pelapor dapat memperbaiki atau memahami keputusan SSC.</p>
            <textarea
                rows="3"
                x-model="alasanTolak"
                placeholder="Tuliskan alasan penolakan laporan..."
                class="w-full mt-4 rounded-xl border border-[#E5E7EB] p-3 text-xs sm:text-sm text-[#252525] placeholder-[#6B7280] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116] focus:outline-none"
            ></textarea>
            <div class="mt-5 flex justify-end gap-2.5">
                <button type="button" @click="showRejectModal = false" class="px-4 py-2 rounded-xl border border-[#E5E7EB] bg-[#FFFFFF] text-xs font-bold text-[#252525] hover:bg-gray-50 transition cursor-pointer">
                    Batal
                </button>
                <button type="button" @click="handleTolak()" class="px-4 py-2 rounded-xl bg-[#B01116] text-xs font-bold text-[#FFFFFF] hover:bg-[#970e13] transition cursor-pointer">
                    Konfirmasi Tolak
                </button>
            </div>
        </div>
    </div>

    @include('admin.components.modal-konfirmasi-penyaluran')
</body>
</html>

