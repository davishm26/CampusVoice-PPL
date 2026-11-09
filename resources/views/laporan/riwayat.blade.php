@php
    // Data statis riwayat laporan (sementara, sebelum terhubung ke database)
    $laporans = [
        ['tiket' => 'CV-2026-00124', 'tanggal' => '18 Okt 2026', 'judul' => 'Permohonan Perbaikan AC Ruang Kelas', 'kategori' => 'Satpras', 'unit' => 'Direktorat Logistik dan Aset', 'status' => 'Diproses'],
        ['tiket' => 'CV-2026-00119', 'tanggal' => '16 Okt 2026', 'judul' => 'Kendala Akses Portal Akademik', 'kategori' => 'SSC', 'unit' => 'Direktorat Pusat Teknologi Informasi', 'status' => 'Diteruskan'],
        ['tiket' => 'CV-2026-00108', 'tanggal' => '12 Okt 2026', 'judul' => 'Permasalahan Transkrip Nilai', 'kategori' => 'SSC', 'unit' => 'Program Studi S1 Sistem Informasi', 'status' => 'Selesai'],
        ['tiket' => 'CV-2026-00097', 'tanggal' => '8 Okt 2026', 'judul' => 'Usulan Penambahan Fasilitas Belajar', 'kategori' => 'Satpras', 'unit' => 'Belum Ditentukan', 'status' => 'Menunggu Verifikasi'],
        ['tiket' => 'CV-2026-00083', 'tanggal' => '16 Okt 2026', 'judul' => 'Kendala Akses Portal Akademik', 'kategori' => 'SSC', 'unit' => 'Direktorat Pusat Teknologi Informasi', 'status' => 'Diteruskan'],
        ['tiket' => 'CV-2026-00074', 'tanggal' => '12 Okt 2026', 'judul' => 'Permasalahan Transkrip Nilai', 'kategori' => 'SSC', 'unit' => 'Program Studi S1 Sistem Informasi', 'status' => 'Selesai'],
    ];

    // Warna badge status: [teks, background]
    $statusStyles = [
        'Diproses'            => ['#7C3AED', '#F5F3FF'],
        'Diteruskan'          => ['#2563EB', '#EFF6FF'],
        'Selesai'             => ['#16A34A', '#ECFDF5'],
        'Menunggu Verifikasi' => ['#D97706', '#FFF7ED'],
    ];

    $detailUrl = fn ($tiket) => \Illuminate\Support\Facades\Route::has('laporan.detail')
        ? route('laporan.detail', $tiket)
        : (\Illuminate\Support\Facades\Route::has('laporan.show')
            ? route('laporan.show', $tiket)
            : url('/laporan/detail/' . $tiket));
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#FFFFFF]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Riwayat Laporan - CampusVoice Telkom University Surabaya</title>

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

        /* Linear Gradient Konten Utama: Merah #B01116 (kiri bawah) ke Abu-abu Gelap #58595B (kanan atas) */
        .content-gradient-bg {
            background: linear-gradient(to top right, #B01116 0%, #58595B 100%);
        }
    </style>
</head>
<body
    class="h-full antialiased text-[#252525] bg-[#B01116]"
    x-data="{
        mobileSidebarOpen: false,
        currentView: (new URLSearchParams(window.location.search).get('view') === 'detail' || new URLSearchParams(window.location.search).get('tiket')) ? 'detail' : 'table',
        selectedTiket: new URLSearchParams(window.location.search).get('tiket') || 'CV-2026-00124',
        search: '',
        status: '',
        kategori: '',
        matches(row) {
            const q = this.search.trim().toLowerCase();
            const okSearch = !q || row.tiket.toLowerCase().includes(q) || row.judul.toLowerCase().includes(q);
            const okStatus = !this.status || row.status === this.status;
            const okKategori = !this.kategori || row.kategori === this.kategori;
            return okSearch && okStatus && okKategori;
        },
        showDetail(tiket) {
            this.selectedTiket = tiket || 'CV-2026-00124';
            this.currentView = 'detail';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },
        backToTable() {
            this.currentView = 'table';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }"
>
    <div class="min-h-screen flex flex-col lg:flex-row">

        <!-- ========================================================= -->
        <!-- SIDEBAR KIRI -->
        <!-- ========================================================= -->
        <aside
            class="fixed inset-y-0 left-0 z-50 w-64 bg-[#FFFFFF] flex flex-col justify-between border-r border-[#E5E7EB] transition-transform duration-200 lg:static lg:translate-x-0"
            :class="mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        >
            <div class="flex flex-col flex-1 overflow-y-auto">
                <!-- Logo -->
                <div class="px-6 py-5 flex items-center gap-3">
                    <img src="{{ asset('images/logo-campusvoice.png') }}" alt="CampusVoice Logo" class="w-8 h-8 object-contain shrink-0" />
                    <div>
                        <span class="text-[17px] font-extrabold text-[#B01116] tracking-tight">CampusVoice</span>
                        <p class="text-[10px] text-[#6B7280] font-medium leading-tight">Telkom University Surabaya</p>
                    </div>
                </div>

                <!-- Navigasi Menu -->
                <div class="px-4 py-3 flex-1">
                    <p class="px-3 text-[11px] font-bold text-[#252525] tracking-wider mb-2 uppercase">Mahasiswa</p>

                    <nav class="space-y-1">
                        <!-- Menu 1: Dashboard -->
                        <a href="{{ url('/dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-[#252525] hover:bg-gray-50 transition group">
                            <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#252525]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="7" height="7" x="3" y="3" rx="1.5"/>
                                <rect width="7" height="7" x="14" y="3" rx="1.5"/>
                                <rect width="7" height="7" x="14" y="14" rx="1.5"/>
                                <rect width="7" height="7" x="3" y="14" rx="1.5"/>
                            </svg>
                            <span>Dashboard</span>
                        </a>

                        <!-- Menu 2: Buat Laporan -->
                        <a href="{{ route('laporan.create') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-[#252525] hover:bg-gray-50 transition group">
                            <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#252525]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            <span>Buat Laporan</span>
                        </a>

                        <!-- Menu 3: Riwayat Laporan (AKTIF) -->
                        <a 
                            href="{{ route('laporan.riwayat') }}" 
                            @click="backToTable()"
                            aria-current="page" 
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold bg-[#FCE8E8] text-[#B01116] transition"
                        >
                            <svg class="w-5 h-5 text-[#B01116]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                                <line x1="16" y1="13" x2="8" y2="13"/>
                                <line x1="16" y1="17" x2="8" y2="17"/>
                                <polyline points="10 9 9 9 8 9"/>
                            </svg>
                            <span>Riwayat Laporan</span>
                        </a>

                        <!-- Menu 4: Notifikasi -->
                        <a href="{{ route('notifikasi') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-medium text-[#252525] hover:bg-gray-50 transition group">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-[#6B7280] group-hover:text-[#252525]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
                                    <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                                </svg>
                                <span>Notifikasi</span>
                            </div>
                            <span class="inline-flex items-center justify-center w-5 h-5 text-[11px] font-bold text-[#FFFFFF] bg-[#B01116] rounded-full">3</span>
                        </a>

                        <!-- Menu 5: Profil Saya -->
                        <a href="{{ route('profil') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-[#252525] hover:bg-gray-50 transition group">
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
                <button type="button" class="w-full flex items-center gap-2 px-3 py-2 text-sm font-semibold text-[#B01116] hover:bg-[#FCE8E8] rounded-xl transition">
                    <svg class="w-5 h-5 text-[#B01116]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                    <span>Keluar</span>
                </button>

                <div class="rounded-xl bg-[#B01116] p-4 text-[#FFFFFF] shadow-sm">
                    <h4 class="text-sm font-bold tracking-tight">Butuh bantuan?</h4>
                    <p class="text-[11px] text-[#FFFFFF]/85 mt-1 leading-snug">Hubungi pusat layanan CampusVoice.</p>
                    <a href="javascript:void(0)" class="mt-3 inline-block py-1.5 px-3 bg-[#FFFFFF] hover:bg-gray-100 text-[#B01116] text-xs font-bold rounded-lg text-center transition">
                        Pusat Bantuan
                    </a>
                </div>
            </div>
        </aside>

        <!-- Overlay Mobile -->
        <div x-show="mobileSidebarOpen" x-cloak @click="mobileSidebarOpen = false" class="fixed inset-0 z-40 bg-black/40 lg:hidden"></div>

        <!-- ========================================================= -->
        <!-- AREA UTAMA (Header + Konten) -->
        <!-- ========================================================= -->
        <div class="flex-1 flex flex-col min-w-0">

            <!-- HEADER ATAS -->
            <header class="bg-[#FFFFFF] border-b border-[#E5E7EB] h-16 flex items-center justify-between px-4 sm:px-8 shrink-0">
                <div class="flex items-center gap-3">
                    <button @click="mobileSidebarOpen = !mobileSidebarOpen" class="p-2 -ml-2 rounded-lg text-[#6B7280] hover:text-[#252525] hover:bg-gray-100 lg:hidden" type="button">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <div class="relative w-48 sm:w-80 md:w-96">
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
                        <span class="absolute -top-1 -right-1 flex items-center justify-center w-4 h-4 text-[10px] font-bold text-[#FFFFFF] bg-[#B01116] rounded-full ring-2 ring-[#FFFFFF]">3</span>
                    </a>

                    <a href="{{ route('profil') }}" class="flex items-center gap-2.5 group">
                        <div class="w-9 h-9 rounded-lg bg-[#FCE8E8] text-[#B01116] font-bold text-xs flex items-center justify-center shrink-0 group-hover:ring-2 group-hover:ring-[#B01116]/30 transition">DA</div>
                        <div class="hidden sm:block text-left">
                            <p class="text-xs font-bold text-[#252525] leading-tight group-hover:text-[#B01116] transition">Dimas Arya</p>
                            <p class="text-[11px] text-[#6B7280] leading-tight">Mahasiswa</p>
                        </div>
                    </a>
                </div>
            </header>

            <!-- ========================================================= -->
            <!-- KONTEN: RIWAYAT LAPORAN ATAU DETAIL LAPORAN               -->
            <!-- ========================================================= -->
            <main class="flex-1 content-gradient-bg px-4 sm:px-8 lg:px-10 py-8">
                <div class="max-w-7xl mx-auto space-y-6">

                    <!-- ===================================================== -->
                    <!-- STATE 1: TABEL RIWAYAT LAPORAN                        -->
                    <!-- ===================================================== -->
                    <div x-show="currentView === 'table'" class="space-y-6">

                        <!-- Judul Halaman -->
                        <div class="mb-6">
                            <h1 class="text-2xl sm:text-3xl font-bold text-[#FFFFFF] tracking-tight">Riwayat Laporan</h1>
                            <p class="text-xs sm:text-sm text-[#FFFFFF] mt-1">Cari dan pantau seluruh laporan yang pernah Anda kirimkan.</p>
                        </div>

                        <!-- Baris Filter & Pencarian -->
                        <div class="flex flex-col sm:flex-row sm:flex-wrap lg:flex-nowrap gap-3 mb-5">
                            <!-- Input Pencarian -->
                            <div class="relative w-full lg:w-[360px] lg:shrink-0">
                                <svg class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-[#6B7280]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="7"/>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                                </svg>
                                <input
                                    type="text"
                                    x-model="search"
                                    placeholder="Cari nomor tiket atau judul..."
                                    class="w-full h-11 bg-[#FFFFFF] border border-[#E5E7EB] rounded-lg pl-10 pr-3.5 text-sm text-[#252525] placeholder-[#6B7280] focus:border-[#B01116] focus:outline-none focus:ring-1 focus:ring-[#B01116] transition"
                                />
                            </div>

                            <!-- Dropdown Status -->
                            <div class="relative w-full sm:w-auto">
                                <select
                                    x-model="status"
                                    class="w-full sm:w-44 h-11 appearance-none bg-[#FFFFFF] bg-none border border-[#E5E7EB] rounded-lg pl-3.5 pr-10 text-sm text-[#6B7280] focus:border-[#B01116] focus:outline-none focus:ring-1 focus:ring-[#B01116] transition cursor-pointer"
                                >
                                    <option value="">Semua Status</option>
                                    @foreach (array_keys($statusStyles) as $s)
                                        <option value="{{ $s }}">{{ $s }}</option>
                                    @endforeach
                                </select>
                                <svg class="pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-[#6B7280]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>

                            <!-- Dropdown Kategori -->
                            <div class="relative w-full sm:w-auto">
                                <select
                                    x-model="kategori"
                                    class="w-full sm:w-44 h-11 appearance-none bg-[#FFFFFF] bg-none border border-[#E5E7EB] rounded-lg pl-3.5 pr-10 text-sm text-[#6B7280] focus:border-[#B01116] focus:outline-none focus:ring-1 focus:ring-[#B01116] transition cursor-pointer"
                                >
                                    <option value="">Semua Kategori</option>
                                    <option value="Satpras">Satpras</option>
                                    <option value="SSC">SSC</option>
                                </select>
                                <svg class="pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-[#6B7280]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>

                            <!-- Rentang Tanggal -->
                            <button
                                type="button"
                                class="w-full sm:w-auto h-11 inline-flex items-center gap-2 bg-[#FFFFFF] border border-[#E5E7EB] rounded-lg px-3.5 text-sm text-[#6B7280] whitespace-nowrap hover:border-gray-300 focus:border-[#B01116] focus:outline-none focus:ring-1 focus:ring-[#B01116] transition"
                            >
                                <svg class="w-4 h-4 text-[#6B7280]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="18" rx="2"/>
                                    <line x1="16" y1="2" x2="16" y2="6"/>
                                    <line x1="8" y1="2" x2="8" y2="6"/>
                                    <line x1="3" y1="10" x2="21" y2="10"/>
                                </svg>
                                <span>19 Sep — 19 Okt 2026</span>
                            </button>
                        </div>

                        <!-- Kartu Tabel Riwayat -->
                        <div class="bg-[#FFFFFF] rounded-2xl shadow-xl p-4 sm:p-5">
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-[860px] text-left">
                                    <thead>
                                        <tr class="bg-[#F9FAFB]">
                                            <th class="px-4 py-3.5 text-[11px] font-semibold uppercase tracking-wider text-[#252525] rounded-l-lg">Nomor Tiket</th>
                                            <th class="px-4 py-3.5 text-[11px] font-semibold uppercase tracking-wider text-[#252525]">Judul Laporan</th>
                                            <th class="px-4 py-3.5 text-[11px] font-semibold uppercase tracking-wider text-[#252525]">Kategori</th>
                                            <th class="px-4 py-3.5 text-[11px] font-semibold uppercase tracking-wider text-[#252525]">Unit Penangan</th>
                                            <th class="px-4 py-3.5 text-[11px] font-semibold uppercase tracking-wider text-[#252525]">Status</th>
                                            <th class="px-4 py-3.5 rounded-r-lg"><span class="sr-only">Aksi</span></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($laporans as $row)
                                            @php [$fg, $bg] = $statusStyles[$row['status']]; @endphp
                                            <tr
                                                x-show="matches({{ \Illuminate\Support\Js::from($row) }})"
                                                class="border-b border-[#E5E7EB] hover:bg-[#FAFAFA] transition"
                                            >
                                                <td class="px-4 py-4 align-middle">
                                                    <p class="text-sm font-bold text-[#252525] whitespace-nowrap">{{ $row['tiket'] }}</p>
                                                    <p class="text-xs text-[#6B7280] mt-1">{{ $row['tanggal'] }}</p>
                                                </td>
                                                <td class="px-4 py-4 align-middle text-sm font-bold text-[#252525]">{{ $row['judul'] }}</td>
                                                <td class="px-4 py-4 align-middle text-sm text-[#252525]">{{ $row['kategori'] }}</td>
                                                <td class="px-4 py-4 align-middle text-sm text-[#252525]">{{ $row['unit'] }}</td>
                                                <td class="px-4 py-4 align-middle">
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold whitespace-nowrap" style="color: {{ $fg }}; background-color: {{ $bg }};">
                                                        <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $fg }};"></span>
                                                        {{ $row['status'] }}
                                                    </span>
                                                </td>
                                                <!-- Tombol Detail (Beralih ke State Detail Laporan) -->
                                                <td class="px-4 py-4 align-middle text-right">
                                                    <button
                                                        type="button"
                                                        @click="showDetail('{{ $row['tiket'] }}')"
                                                        class="inline-flex items-center gap-1 text-sm font-bold text-[#B01116] hover:text-[#970e13] whitespace-nowrap transition cursor-pointer"
                                                    >
                                                        <span>Detail</span>
                                                        <svg class="w-4 h-4 text-[#B01116]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                                                            <line x1="5" y1="12" x2="19" y2="12"/>
                                                            <polyline points="12 5 19 12 12 19"/>
                                                        </svg>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Footer Tabel: Pagination -->
                            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-5 px-1">
                                <p class="text-xs text-[#6B7280]">Menampilkan 1-6 dari 8 laporan</p>

                                <nav class="flex items-center gap-2" aria-label="Pagination">
                                    <button type="button" aria-label="Sebelumnya" class="w-9 h-9 flex items-center justify-center rounded-lg bg-[#FFFFFF] border border-[#E5E7EB] text-[#252525] hover:bg-gray-50 transition">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="19" y1="12" x2="5" y2="12"/>
                                            <polyline points="12 19 5 12 12 5"/>
                                        </svg>
                                    </button>
                                    <button type="button" aria-current="page" class="w-9 h-9 flex items-center justify-center rounded-lg bg-[#B01116] text-[#FFFFFF] text-sm font-bold shadow-sm">1</button>
                                    <button type="button" class="w-9 h-9 flex items-center justify-center rounded-lg bg-[#FFFFFF] border border-[#E5E7EB] text-[#252525] text-sm font-semibold hover:bg-gray-50 transition">2</button>
                                    <button type="button" aria-label="Berikutnya" class="w-9 h-9 flex items-center justify-center rounded-lg bg-[#FFFFFF] border border-[#E5E7EB] text-[#252525] hover:bg-gray-50 transition">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="5" y1="12" x2="19" y2="12"/>
                                            <polyline points="12 5 19 12 12 19"/>
                                        </svg>
                                    </button>
                                </nav>
                            </div>
                        </div>

                    </div>

                    <!-- ===================================================== -->
                    <!-- STATE 2: DETAIL LAPORAN (Muncul saat tekan Detail ->) -->
                    <!-- ===================================================== -->
                    <div x-show="currentView === 'detail'" x-cloak class="space-y-6">

                        <!-- Header Detail Laporan (Teks Putih di atas Gradient) -->
                        <div>
                            <!-- Tombol Kembali ke Riwayat Laporan -->
                            <button
                                type="button"
                                @click="backToTable()"
                                class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-lg bg-black/20 hover:bg-black/30 text-white text-xs font-semibold mb-3 transition cursor-pointer backdrop-blur-xs"
                            >
                                <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="19" y1="12" x2="5" y2="12"/>
                                    <polyline points="12 19 5 12 12 5"/>
                                </svg>
                                <span>Kembali ke Riwayat Laporan</span>
                            </button>

                            <!-- Nomor Tiket -->
                            <p class="text-xs sm:text-sm font-semibold tracking-wider text-white/90 uppercase" x-text="selectedTiket">
                                CV-2026-00124
                            </p>

                            <!-- Judul Besar -->
                            <h1 class="text-2xl sm:text-3xl lg:text-[34px] font-extrabold text-white tracking-tight mt-1">
                                Permohonan Perbaikan AC Ruang Kelas
                            </h1>

                            <!-- Waktu Pengiriman -->
                            <p class="text-xs sm:text-sm text-white/85 mt-1.5">
                                Dikirim pada 18 Oktober 2026, 09.14 WIB
                            </p>
                        </div>

                        <!-- Grid 2 Kolom: Kolom Kiri (Lebar) & Kolom Kanan (Sempit) -->
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                            <!-- ================================================= -->
                            <!-- KOLOM KIRI (2 KARTU BESAR)                        -->
                            <!-- ================================================= -->
                            <div class="lg:col-span-8 space-y-6">

                                <!-- KARTU 1: Detail Utama -->
                                <div class="bg-[#FFFFFF] rounded-2xl shadow-xl p-6 sm:p-7 space-y-5">

                                    <!-- Baris Badge & Progres -->
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                        <div class="flex items-center gap-2.5 flex-wrap">
                                            <!-- Badge Status: Diproses (Ungu) -->
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold text-[#7C3AED] bg-[#F5F3FF]">
                                                <span class="w-1.5 h-1.5 rounded-full bg-[#7C3AED]"></span>
                                                Diproses
                                            </span>

                                            <!-- Badge Prioritas: Prioritas Tinggi (Pink/Merah) -->
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold text-[#B01116] bg-[#FCE8E8]">
                                                Prioritas Tinggi
                                            </span>
                                        </div>

                                        <!-- Teks Progres 65% -->
                                        <p class="text-xs sm:text-sm text-[#6B7280]">
                                            Progres <span class="font-extrabold text-[#252525]">65%</span>
                                        </p>
                                    </div>

                                    <!-- Progress Bar Merah Tua (65%) -->
                                    <div class="w-full bg-[#E5E7EB] h-2 rounded-full overflow-hidden">
                                        <div class="bg-[#B01116] h-full rounded-full w-[65%]"></div>
                                    </div>

                                    <!-- Grid 2x2 Info (Background Abu-abu Sangat Terang) -->
                                    <div class="bg-[#F9FAFB] rounded-xl p-4 sm:p-5 border border-[#F3F4F6] grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-[11px] font-semibold uppercase tracking-wider text-[#6B7280]">KATEGORI</p>
                                            <p class="text-sm font-bold text-[#252525] mt-1">Satpras</p>
                                        </div>
                                        <div>
                                            <p class="text-[11px] font-semibold uppercase tracking-wider text-[#6B7280]">LOKASI</p>
                                            <p class="text-sm font-bold text-[#252525] mt-1">Gedung A, Ruang A-301</p>
                                        </div>
                                        <div>
                                            <p class="text-[11px] font-semibold uppercase tracking-wider text-[#6B7280]">UNIT PENANGAN</p>
                                            <p class="text-sm font-bold text-[#252525] mt-1">Direktorat Logistik dan Aset</p>
                                        </div>
                                        <div>
                                            <p class="text-[11px] font-semibold uppercase tracking-wider text-[#6B7280]">PELAPOR</p>
                                            <p class="text-sm font-bold text-[#252525] mt-1">Dimas Arya Irwansyah • 102062400113</p>
                                        </div>
                                    </div>

                                    <!-- Deskripsi Laporan -->
                                    <div class="pt-1">
                                        <h3 class="text-sm font-bold text-[#252525] mb-2">Deskripsi laporan</h3>
                                        <p class="text-xs sm:text-sm text-[#6B7280] leading-relaxed">
                                            AC di ruang kelas A-301 tidak berfungsi sejak dua hari terakhir. Kondisi ruangan menjadi panas dan mengganggu proses perkuliahan pada siang hari. Mohon dilakukan pemeriksaan dan perbaikan.
                                        </p>
                                    </div>

                                    <!-- Bukti Pendukung -->
                                    <div class="pt-1">
                                        <h3 class="text-sm font-bold text-[#252525] mb-2">Bukti pendukung</h3>
                                        <div class="border border-[#E5E7EB] rounded-xl p-3.5 flex items-center justify-between hover:bg-gray-50/70 transition">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center shrink-0 text-[#6B7280]">
                                                    <svg class="w-5 h-5 text-[#6B7280]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                                        <polyline points="14 2 14 8 20 8"/>
                                                        <line x1="16" y1="13" x2="8" y2="13"/>
                                                        <line x1="16" y1="17" x2="8" y2="17"/>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-xs sm:text-sm font-bold text-[#252525]">foto_ac_ruang_a301.jpg</p>
                                                    <p class="text-[11px] text-[#6B7280] mt-0.5">1.8 MB • JPG</p>
                                                </div>
                                            </div>

                                            <button
                                                type="button"
                                                class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold text-[#B01116] hover:text-[#8e0d12] transition cursor-pointer"
                                            >
                                                <svg class="w-4 h-4 text-[#B01116]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                                    <circle cx="12" cy="12" r="3"/>
                                                </svg>
                                                <span>Lihat</span>
                                            </button>
                                        </div>
                                    </div>

                                </div>

                                <!-- KARTU 2: Diskusi dan Tanggapan -->
                                <div class="bg-[#FFFFFF] rounded-2xl shadow-xl p-6 sm:p-7 space-y-6">

                                    <!-- Header Diskusi -->
                                    <div>
                                        <h2 class="text-base sm:text-lg font-bold text-[#252525] tracking-tight">
                                            Diskusi dan Tanggapan
                                        </h2>
                                        <p class="text-xs sm:text-sm text-[#6B7280] mt-0.5">
                                            Komunikasi terkait penanganan laporan.
                                        </p>
                                    </div>

                                    <!-- Daftar Tanggapan / Chat -->
                                    <div class="space-y-5">
                                        <!-- Chat 1: Admin SSC -->
                                        <div>
                                            <div class="flex items-start justify-between gap-3">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-9 h-9 rounded-xl bg-[#FCE8E8] text-[#B01116] font-bold text-xs flex items-center justify-center shrink-0">
                                                        AS
                                                    </div>
                                                    <div class="flex items-center gap-2 flex-wrap">
                                                        <p class="text-sm font-bold text-[#252525]">Admin SSC</p>
                                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold text-[#2563EB] bg-[#EFF6FF]">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-[#2563EB]"></span>
                                                            Admin SSC
                                                        </span>
                                                    </div>
                                                </div>
                                                <span class="text-xs text-[#6B7280] shrink-0">18 Okt, 11.20</span>
                                            </div>
                                            <p class="text-xs sm:text-sm text-[#252525] mt-2.5 pl-12 leading-relaxed">
                                                Laporan telah diverifikasi dan diteruskan ke Direktorat Logistik dan Aset.
                                            </p>
                                        </div>

                                        <!-- Garis Pembatas -->
                                        <div class="border-t border-[#E5E7EB]"></div>

                                        <!-- Chat 2: Budi Santoso (Petugas Unit) -->
                                        <div>
                                            <div class="flex items-start justify-between gap-3">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-9 h-9 rounded-xl bg-[#FCE8E8] text-[#B01116] font-bold text-xs flex items-center justify-center shrink-0">
                                                        BS
                                                    </div>
                                                    <div class="flex items-center gap-2 flex-wrap">
                                                        <p class="text-sm font-bold text-[#252525]">Budi Santoso</p>
                                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold text-[#4B5563] bg-[#F3F4F6]">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-[#6B7280]"></span>
                                                            Petugas Unit
                                                        </span>
                                                    </div>
                                                </div>
                                                <span class="text-xs text-[#6B7280] shrink-0">19 Okt, 08.45</span>
                                            </div>
                                            <p class="text-xs sm:text-sm text-[#252525] mt-2.5 pl-12 leading-relaxed">
                                                Tim teknis telah melakukan pengecekan. Komponen pendingin perlu diganti dan sedang kami siapkan.
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Form Tulis Tanggapan -->
                                    <div class="pt-4 border-t border-[#E5E7EB]">
                                        <label for="input-tanggapan-riwayat" class="block text-sm font-bold text-[#252525] mb-2">
                                            Tulis tanggapan
                                        </label>
                                        <textarea
                                            id="input-tanggapan-riwayat"
                                            rows="4"
                                            placeholder="Tulis pesan yang relevan dengan laporan..."
                                            class="w-full rounded-xl border border-[#E5E7EB] bg-white p-3.5 text-xs sm:text-sm text-[#252525] placeholder-[#6B7280] focus:border-[#B01116] focus:outline-none focus:ring-1 focus:ring-[#B01116] transition resize-none"
                                        ></textarea>

                                        <div class="mt-3">
                                            <button
                                                type="button"
                                                class="px-5 py-2.5 rounded-xl bg-[#B01116] hover:bg-[#8e0d12] text-xs sm:text-sm font-bold text-white shadow-sm transition inline-flex items-center gap-2 cursor-pointer"
                                            >
                                                <span>Kirim Tanggapan</span>
                                            </button>
                                        </div>
                                    </div>

                                </div>

                            </div>

                            <!-- ================================================= -->
                            <!-- KOLOM KANAN (2 KARTU SAMPING)                     -->
                            <!-- ================================================= -->
                            <div class="lg:col-span-4 space-y-6">

                                <!-- KARTU 1: Riwayat Status (Stepper Vertikal) -->
                                <div class="bg-[#FFFFFF] rounded-2xl shadow-xl p-6 sm:p-7">
                                    <h3 class="text-base font-bold text-[#252525] mb-6">
                                        Riwayat Status
                                    </h3>

                                    <div class="relative space-y-6">

                                        <!-- Step 1: Diproses (Aktif / Lingkaran Ring Merah) -->
                                        <div class="flex items-start gap-3.5 relative">
                                            <!-- Icon Ring Merah -->
                                            <div class="w-6 h-6 rounded-full border-2 border-[#B01116] flex items-center justify-center bg-white shrink-0 mt-0.5 z-10">
                                                <span class="w-2 h-2 rounded-full bg-[#B01116]"></span>
                                            </div>
                                            <!-- Garis vertikal ke bawah -->
                                            <div class="absolute left-3 top-6 w-0.5 bg-[#E5E7EB] h-14 -translate-x-1/2"></div>

                                            <div>
                                                <h4 class="text-sm font-bold text-[#252525]">Diproses</h4>
                                                <p class="text-[11px] text-[#6B7280] mt-0.5">19 Okt 2026, 08.45</p>
                                                <p class="text-xs text-[#6B7280] mt-1 leading-snug">
                                                    Pemeriksaan teknis sedang berlangsung.
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Step 2: Diteruskan ke Unit (Centang Hijau) -->
                                        <div class="flex items-start gap-3.5 relative">
                                            <!-- Icon Centang Hijau -->
                                            <div class="w-6 h-6 rounded-full bg-[#DCFCE7] text-[#16A34A] flex items-center justify-center shrink-0 mt-0.5 z-10">
                                                <svg class="w-3.5 h-3.5 text-[#16A34A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="20 6 9 17 4 12"/>
                                                </svg>
                                            </div>
                                            <!-- Garis vertikal ke bawah -->
                                            <div class="absolute left-3 top-6 w-0.5 bg-[#E5E7EB] h-14 -translate-x-1/2"></div>

                                            <div>
                                                <h4 class="text-sm font-bold text-[#252525]">Diteruskan ke Unit</h4>
                                                <p class="text-[11px] text-[#6B7280] mt-0.5">18 Okt 2026, 11.20</p>
                                                <p class="text-xs text-[#6B7280] mt-1 leading-snug">
                                                    Diteruskan ke Direktorat Logistik dan Aset.
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Step 3: Menunggu Verifikasi (Centang Hijau) -->
                                        <div class="flex items-start gap-3.5 relative">
                                            <!-- Icon Centang Hijau -->
                                            <div class="w-6 h-6 rounded-full bg-[#DCFCE7] text-[#16A34A] flex items-center justify-center shrink-0 mt-0.5 z-10">
                                                <svg class="w-3.5 h-3.5 text-[#16A34A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="20 6 9 17 4 12"/>
                                                </svg>
                                            </div>
                                            <!-- Garis vertikal ke bawah -->
                                            <div class="absolute left-3 top-6 w-0.5 bg-[#E5E7EB] h-14 -translate-x-1/2"></div>

                                            <div>
                                                <h4 class="text-sm font-bold text-[#252525]">Menunggu Verifikasi</h4>
                                                <p class="text-[11px] text-[#6B7280] mt-0.5">18 Okt 2026, 09.14</p>
                                                <p class="text-xs text-[#6B7280] mt-1 leading-snug">
                                                    Laporan diterima oleh sistem.
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Step 4: Dikirim (Centang Hijau) -->
                                        <div class="flex items-start gap-3.5 relative">
                                            <!-- Icon Centang Hijau -->
                                            <div class="w-6 h-6 rounded-full bg-[#DCFCE7] text-[#16A34A] flex items-center justify-center shrink-0 mt-0.5 z-10">
                                                <svg class="w-3.5 h-3.5 text-[#16A34A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="20 6 9 17 4 12"/>
                                                </svg>
                                            </div>

                                            <div>
                                                <h4 class="text-sm font-bold text-[#252525]">Dikirim</h4>
                                                <p class="text-[11px] text-[#6B7280] mt-0.5">18 Okt 2026, 09.14</p>
                                                <p class="text-xs text-[#6B7280] mt-1 leading-snug">
                                                    Nomor tiket berhasil dibuat.
                                                </p>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <!-- KARTU 2: Aksi Lanjutan -->
                                <div class="bg-[#FFFFFF] rounded-2xl shadow-xl p-6 sm:p-7 space-y-4">
                                    <h3 class="text-sm font-bold text-[#252525]">
                                        Belum sepenuhnya selesai?
                                    </h3>
                                    <p class="text-xs text-[#6B7280] leading-relaxed">
                                        Setelah laporan selesai, Anda dapat mengajukan pembukaan kembali dengan alasan yang jelas.
                                    </p>

                                    <!-- Tombol Buka Kembali Laporan -->
                                    <button
                                        type="button"
                                        class="w-full py-2.5 rounded-xl border border-[#E5E7EB] bg-[#FFFFFF] hover:bg-gray-50 text-xs sm:text-sm font-bold text-[#252525] shadow-xs text-center transition cursor-pointer"
                                    >
                                        Buka Kembali Laporan
                                    </button>

                                    <!-- Link Beri Penilaian -->
                                    <div class="pt-1 text-center">
                                        <button
                                            type="button"
                                            class="text-xs sm:text-sm font-bold text-[#B01116] hover:underline cursor-pointer"
                                        >
                                            Beri Penilaian
                                        </button>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>
            </main>
        </div>
    </div>
</body>
</html>
