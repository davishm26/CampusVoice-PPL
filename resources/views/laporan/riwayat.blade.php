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

    // Link ke halaman Detail Laporan (otomatis aktif bila route 'laporan.show' sudah didefinisikan)
    $detailUrl = fn ($tiket) => \Illuminate\Support\Facades\Route::has('laporan.show')
        ? route('laporan.show', $tiket)
        : 'javascript:void(0)';
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
        body, input, select, button { font-family: 'Plus Jakarta Sans', sans-serif; }

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
        search: '',
        status: '',
        kategori: '',
        matches(row) {
            const q = this.search.trim().toLowerCase();
            const okSearch = !q || row.tiket.toLowerCase().includes(q) || row.judul.toLowerCase().includes(q);
            const okStatus = !this.status || row.status === this.status;
            const okKategori = !this.kategori || row.kategori === this.kategori;
            return okSearch && okStatus && okKategori;
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
                        <a href="{{ route('laporan.riwayat') }}" aria-current="page" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold bg-[#FCE8E8] text-[#B01116] transition">
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
                        <a href="javascript:void(0)" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-medium text-[#252525] hover:bg-gray-50 transition group">
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
                        <a href="javascript:void(0)" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-[#252525] hover:bg-gray-50 transition group">
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
                    <button type="button" class="relative w-9 h-9 rounded-full border border-[#E5E7EB] flex items-center justify-center text-[#6B7280] hover:text-[#252525] hover:bg-gray-50 transition">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
                            <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                        </svg>
                        <span class="absolute -top-1 -right-1 flex items-center justify-center w-4 h-4 text-[10px] font-bold text-[#FFFFFF] bg-[#B01116] rounded-full ring-2 ring-[#FFFFFF]">3</span>
                    </button>

                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-lg bg-[#FCE8E8] text-[#B01116] font-bold text-xs flex items-center justify-center shrink-0">DA</div>
                        <div class="hidden sm:block text-left">
                            <p class="text-xs font-bold text-[#252525] leading-tight">Dimas Arya</p>
                            <p class="text-[11px] text-[#6B7280] leading-tight">Mahasiswa</p>
                        </div>
                    </div>
                </div>
            </header>

            <!-- ========================================================= -->
            <!-- KONTEN: RIWAYAT LAPORAN (Background Gradient) -->
            <!-- ========================================================= -->
            <main class="flex-1 content-gradient-bg px-4 sm:px-8 lg:px-10 py-8">
                <div class="max-w-7xl mx-auto">

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
                                            <td class="px-4 py-4 align-middle text-right">
                                                <a href="{{ $detailUrl($row['tiket']) }}" class="inline-flex items-center gap-1 text-sm font-bold text-[#B01116] hover:text-[#970e13] whitespace-nowrap transition">
                                                    Detail
                                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                                                        <line x1="5" y1="12" x2="19" y2="12"/>
                                                        <polyline points="12 5 19 12 12 19"/>
                                                    </svg>
                                                </a>
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
            </main>
        </div>
    </div>
</body>
</html>

