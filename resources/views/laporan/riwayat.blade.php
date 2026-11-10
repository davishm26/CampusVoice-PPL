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

    // Data riwayat laporan dinamis dari database
    $rows = $laporans->map(function ($lap) {
        return [
            'id' => $lap->id,
            'tiket' => $lap->nomor_tiket,
            'tanggal' => $lap->formatted_tanggal,
            'judul' => $lap->judul,
            'kategori' => $lap->short_kategori,
            'unit' => $lap->unit_penangan,
            'status' => $lap->status,
        ];
    })->values();

    $statusStyles = [
        'Diproses'            => ['#7C3AED', '#F5F3FF'],
        'Diteruskan'          => ['#2563EB', '#EFF6FF'],
        'Selesai'             => ['#16A34A', '#ECFDF5'],
        'Menunggu Verifikasi' => ['#D97706', '#FFF7ED'],
        'Ditolak'             => ['#DC2626', '#FEF2F2'],
    ];
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
        search: @js(request('q', '')),
        status: @js(request('status', '')),
        kategori: @js(request('kategori', '')),
        rows: @js($rows),
        matches(row) {
            const q = this.search.trim().toLowerCase();
            const okSearch = !q || row.tiket.toLowerCase().includes(q) || row.judul.toLowerCase().includes(q);
            const okStatus = !this.status || row.status === this.status;
            const okKategori = !this.kategori || row.kategori.toLowerCase() === this.kategori.toLowerCase();
            return okSearch && okStatus && okKategori;
        },
        hasVisibleRows() {
            return this.rows.some(r => this.matches(r));
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
                    <img src="{{ asset('images/logo-campusvoice.png') }}" alt="CampusVoice Logo" class="w-8 h-8 object-contain shrink-0" onerror="this.onerror=null; this.src='https://placehold.co/32x32/B01116/white?text=CV';" />
                    <div>
                        <span class="text-[17px] font-extrabold text-[#B01116] tracking-tight">CampusVoice</span>
                        <p class="text-[10px] text-[#6B7280] font-medium leading-tight">Telkom University Surabaya</p>
                    </div>
                </div>

                <!-- Navigasi Menu -->
                <div class="px-4 py-3 flex-1">
                    <p class="px-3 text-[11px] font-bold text-[#252525] tracking-wider mb-2 uppercase">{{ $authRole }}</p>

                    <nav class="space-y-1">
                        <!-- Menu 1: Dashboard -->
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-[#252525] hover:bg-gray-50 transition group">
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
                        <a href="{{ route('notifikasi') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-medium text-[#252525] hover:bg-gray-50 transition group">
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
                            x-model="search"
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
            <!-- KONTEN: RIWAYAT LAPORAN (Background Gradient)             -->
            <!-- ========================================================= -->
            <main class="flex-1 content-gradient-bg px-4 sm:px-8 lg:px-10 py-8">
                <div class="max-w-7xl mx-auto space-y-6">

                    <!-- Judul Halaman -->
                    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-[#FFFFFF] tracking-tight">Riwayat Laporan</h1>
                            <p class="text-xs sm:text-sm text-[#FFFFFF]/85 mt-1">Cari dan pantau seluruh laporan yang pernah Anda kirimkan.</p>
                        </div>
                        <div>
                            <a
                                href="{{ route('laporan.create') }}"
                                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white text-[#B01116] hover:bg-gray-100 text-xs sm:text-sm font-bold shadow transition"
                            >
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="12" y1="5" x2="12" y2="19"/>
                                    <line x1="5" y1="12" x2="19" y2="12"/>
                                </svg>
                                <span>Buat Laporan Baru</span>
                            </a>
                        </div>
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
                                class="w-full sm:w-48 h-11 appearance-none bg-[#FFFFFF] bg-none border border-[#E5E7EB] rounded-lg pl-3.5 pr-10 text-sm text-[#252525] focus:border-[#B01116] focus:outline-none focus:ring-1 focus:ring-[#B01116] transition cursor-pointer"
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
                                class="w-full sm:w-48 h-11 appearance-none bg-[#FFFFFF] bg-none border border-[#E5E7EB] rounded-lg pl-3.5 pr-10 text-sm text-[#252525] focus:border-[#B01116] focus:outline-none focus:ring-1 focus:ring-[#B01116] transition cursor-pointer"
                            >
                                <option value="">Semua Kategori</option>
                                <option value="Satpras">Satpras</option>
                                <option value="SSC">SSC</option>
                                <option value="Kemahasiswaan">Kemahasiswaan</option>
                                <option value="Akademik">Akademik</option>
                                <option value="Keuangan">Keuangan</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                            <svg class="pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-[#6B7280]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
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
                                        <th class="px-4 py-3.5 rounded-r-lg text-right"><span class="sr-only">Aksi</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rows as $row)
                                        @php 
                                            [$fg, $bg] = $statusStyles[$row['status']] ?? ['#D97706', '#FFF7ED'];
                                        @endphp
                                        <tr
                                            x-show="matches({{ \Illuminate\Support\Js::from($row) }})"
                                            class="border-b border-[#E5E7EB] hover:bg-[#FAFAFA] transition"
                                        >
                                            <td class="px-4 py-4 align-middle">
                                                <p class="text-sm font-bold text-[#252525] whitespace-nowrap">{{ $row['tiket'] }}</p>
                                                <p class="text-xs text-[#6B7280] mt-1">{{ $row['tanggal'] }}</p>
                                            </td>
                                            <td class="px-4 py-4 align-middle text-sm font-bold text-[#252525]">
                                                <a href="{{ route('laporan.detail', $row['tiket']) }}" class="hover:text-[#B01116] transition">
                                                    {{ $row['judul'] }}
                                                </a>
                                            </td>
                                            <td class="px-4 py-4 align-middle text-sm text-[#252525]">
                                                <span class="px-2.5 py-1 rounded-md bg-gray-100 text-xs font-medium text-gray-700">
                                                    {{ $row['kategori'] }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-4 align-middle text-sm text-[#252525]">{{ $row['unit'] }}</td>
                                            <td class="px-4 py-4 align-middle whitespace-nowrap">
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold"
                                                    style="color: {{ $fg }}; background-color: {{ $bg }};"
                                                >
                                                    <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $fg }};"></span>
                                                    {{ $row['status'] }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-4 align-middle text-right whitespace-nowrap">
                                                <a
                                                    href="{{ route('laporan.detail', $row['tiket']) }}"
                                                    class="inline-flex items-center gap-1.5 text-sm font-bold text-[#B01116] hover:text-[#8e0d12] transition"
                                                >
                                                    <span>Detail</span>
                                                    <svg class="w-4 h-4 text-[#B01116]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
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

                        <!-- Empty State saat data tidak cocok dengan filter / kosong -->
                        <div x-show="!hasVisibleRows()" x-cloak class="py-16 text-center">
                            <div class="w-14 h-14 rounded-2xl bg-[#F9FAFB] border border-[#E5E7EB] flex items-center justify-center mx-auto mb-3 text-gray-400">
                                <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <circle cx="11" cy="11" r="8"/>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                                </svg>
                            </div>
                            <h4 class="text-sm font-bold text-[#252525]">Tidak ada laporan yang cocok</h4>
                            <p class="text-xs text-[#6B7280] mt-1">Coba gunakan kata kunci lain atau ubah filter status & kategori.</p>
                            <button 
                                type="button" 
                                @click="search = ''; status = ''; kategori = ''"
                                class="mt-4 px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-xs font-bold text-[#252525] transition"
                            >
                                Reset Filter
                            </button>
                        </div>
                    </div>

                </div>
            </main>

        </div>
    </div>
</body>
</html>
