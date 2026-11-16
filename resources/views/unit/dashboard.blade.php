@php
    $authUser = $authUser ?? Auth::user();
    if (!$authUser || $authUser->role !== 'Petugas Unit') {
        $authUser = \App\Models\User::where('role', 'Petugas Unit')->first() ?? $authUser ?? new \App\Models\User([
            'name' => 'Petugas Unit',
            'role' => 'Petugas Unit',
            'email' => 'petugas.unit@telkomuniversity.ac.id',
        ]);
    }
    $authName = $authUser ? $authUser->name : 'Petugas Unit';
    $authRole = ($authUser && $authUser->role) ? $authUser->role : 'Petugas Unit';

    $authWords = preg_split('/\s+/', trim($authName));
    $authInitials = '';
    foreach ($authWords as $w) { $authInitials .= mb_strtoupper(mb_substr($w, 0, 1)); }
    $authInitials = substr($authInitials, 0, 2) ?: 'PU';

    $totalMasuk = $totalMasuk ?? 0;
    $belumDitangani = $belumDitangani ?? 0;
    $sedangDiproses = $sedangDiproses ?? 0;
    $selesai = $selesai ?? 0;
    $laporansList = $laporansList ?? [];
    $unreadCount = $unreadCount ?? 0;
    $unitName = $authUser?->unit ?: 'Unit Penangan';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-white">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Dashboard Unit — {{ $unitName }} - CampusVoice</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS with Forms Plugin via CDN -->
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        maroon: {
                            600: '#9F171B',
                            700: '#8B1D22',
                            800: '#851518',
                            900: '#6E0D11',
                        },
                        brand: {
                            50: '#FCE8E8',
                            100: '#F8D1D1',
                            500: '#D91D24',
                            600: '#B01116',
                            700: '#8C0D11',
                        }
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
    class="h-full bg-[#B01116] antialiased text-[#252525]"
    x-data="{
        mobileSidebarOpen: false,
        searchQuery: '',
        laporans: @js($laporansList),
        get filteredLaporans() {
            if (!this.searchQuery.trim()) return this.laporans;
            const q = this.searchQuery.toLowerCase();
            return this.laporans.filter(item => 
                item.tiket.toLowerCase().includes(q) ||
                item.judul.toLowerCase().includes(q) ||
                item.kategori.toLowerCase().includes(q) ||
                item.status.toLowerCase().includes(q)
            );
        }
    }"
>
    <div class="h-screen overflow-hidden flex flex-col lg:flex-row">

        @include('unit.partials.sidebar', ['activeMenu' => 'dashboard', 'unreadCount' => $unreadCount])

        <!-- ========================================================= -->
        <!-- AREA UTAMA (Top Navbar & Area Konten) -->
        <!-- ========================================================= -->
        <div class="flex-1 flex flex-col min-w-0 min-h-0 overflow-hidden">

            @include('unit.partials.header', ['authUser' => $authUser, 'unreadCount' => $unreadCount, 'searchModel' => 'searchQuery'])

            <!-- ========================================================= -->
            <!-- 3. AREA KONTEN UTAMA -->
            <!-- ========================================================= -->
            <main class="flex-1 min-h-0 overflow-y-auto content-gradient-bg px-4 sm:px-8 lg:px-10 py-8">
                <div class="max-w-[1360px] mx-auto space-y-6">

                    <!-- Header Atas (Maroon): Label, Judul Besar, Sub-judul, Tombol Aksi Kanan -->
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                        <div>
                            <span class="text-[11px] font-bold tracking-widest uppercase text-white/80 block mb-1">
                                PETUGAS UNIT
                            </span>
                            <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-tight leading-snug">
                                Dashboard Unit — {{ $unitName }}
                            </h1>
                            <p class="text-xs sm:text-sm text-white/85 mt-1">
                                Kelola laporan yang ditugaskan kepada unit Anda.
                            </p>
                        </div>

                        <!-- Pojok Kanan Atas: Tombol Lihat Laporan Ditugaskan -->
                        <div class="flex items-center gap-3 shrink-0 pt-1">
                            <a
                                href="{{ route('unit.laporan-ditugaskan') }}"
                                class="px-4 py-2.5 rounded-xl bg-white hover:bg-gray-50 text-xs sm:text-sm font-bold text-[#1F2937] shadow-sm transition"
                            >
                                Lihat Laporan Ditugaskan
                            </a>
                        </div>
                    </div>

                    <!-- Banner Informasi (Putih full-width, ikon info merah) -->
                    <div class="bg-white rounded-xl p-4 shadow-sm flex items-center gap-3.5">
                        <div class="w-6 h-6 rounded-full bg-red-50 text-[#B01116] flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-[#B01116]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="16" x2="12" y2="12"/>
                                <line x1="12" y1="8" x2="12.01" y2="8"/>
                            </svg>
                        </div>
                        <p class="text-xs sm:text-sm text-[#252525]">
                            Anda hanya dapat mengakses laporan yang ditugaskan kepada <span class="font-bold">{{ $unitName }}</span>.
                        </p>
                    </div>

                    <!-- Kartu Statistik (Grid 4 Kolom) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- 1. Laporan Masuk: Angka "18" (Ikon dokumen merah) -->
                        <div class="bg-white rounded-xl p-5 shadow-sm flex items-center gap-4">
                            <div class="w-10 h-10 rounded-lg bg-[#FCE8E8] text-[#B01116] flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-[#B01116]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <polyline points="14 2 14 8 20 8"/>
                                    <line x1="16" y1="13" x2="8" y2="13"/>
                                    <line x1="16" y1="17" x2="8" y2="17"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-[#6B7280] font-medium leading-none">Laporan Masuk</p>
                                <p class="text-2xl font-bold text-[#1F2937] leading-tight mt-1">{{ $totalMasuk }}</p>
                            </div>
                        </div>

                        <!-- 2. Belum Ditangani: Angka "6" (Ikon jam oranye) -->
                        <div class="bg-white rounded-xl p-5 shadow-sm flex items-center gap-4">
                            <div class="w-10 h-10 rounded-lg bg-[#FEF3C7] text-[#D97706] flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-[#D97706]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/>
                                    <polyline points="12 6 12 12 16 14"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-[#6B7280] font-medium leading-none">Belum Ditangani</p>
                                <p class="text-2xl font-bold text-[#1F2937] leading-tight mt-1">{{ $belumDitangani }}</p>
                            </div>
                        </div>

                        <!-- 3. Sedang Diproses: Angka "14" (Ikon proses biru) -->
                        <div class="bg-white rounded-xl p-5 shadow-sm flex items-center gap-4">
                            <div class="w-10 h-10 rounded-lg bg-[#DBEAFE] text-[#2563EB] flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-[#2563EB]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-[#6B7280] font-medium leading-none">Sedang Diproses</p>
                                <p class="text-2xl font-bold text-[#1F2937] leading-tight mt-1">{{ $sedangDiproses }}</p>
                            </div>
                        </div>

                        <!-- 4. Selesai: Angka "8" (Ikon centang hijau) -->
                        <div class="bg-white rounded-xl p-5 shadow-sm flex items-center gap-4">
                            <div class="w-10 h-10 rounded-lg bg-[#D1FAE5] text-[#059669] flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-[#059669]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-[#6B7280] font-medium leading-none">Selesai</p>
                                <p class="text-2xl font-bold text-[#1F2937] leading-tight mt-1">{{ $selesai }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- ========================================================= -->
                    <!-- 4. BAGIAN BAWAH KONTEN UTAMA (TABEL & AKTIVITAS UNIT) -->
                    <!-- ========================================================= -->
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                        <!-- KARTU TABEL "LAPORAN TERBARU" (Kiri/Tengah - Lebar Besar) -->
                        <div class="lg:col-span-8 xl:col-span-8 bg-white rounded-2xl shadow-sm p-6 sm:p-7">
                            <div class="mb-5">
                                <h2 class="text-lg font-bold text-[#1F2937] tracking-tight">Laporan Terbaru</h2>
                                <p class="text-xs text-[#6B7280] mt-0.5">Ditugaskan kepada unit Anda oleh Admin SSC.</p>
                            </div>

                            <!-- Tabel Responsif -->
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-xs border-collapse">
                                    <thead>
                                        <tr class="border-b border-[#E5E7EB] text-[#6B7280] font-bold text-[11px] uppercase tracking-wider bg-[#F9FAFB]">
                                            <th class="py-3 px-4">NOMOR TIKET</th>
                                            <th class="py-3 px-4">JUDUL LAPORAN</th>
                                            <th class="py-3 px-4">KATEGORI</th>
                                            <th class="py-3 px-4">PRIORITAS</th>
                                            <th class="py-3 px-4">STATUS</th>
                                            <th class="py-3 px-4">PROGRES</th>
                                            <th class="py-3 px-4 text-right">AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[#E5E7EB]">
                                        <template x-for="item in filteredLaporans" :key="item.tiket">
                                            <tr class="hover:bg-gray-50/70 transition">
                                                <!-- Nomor Tiket -->
                                                <td class="py-4 px-4 whitespace-nowrap">
                                                    <p class="font-bold text-[#1F2937]" x-text="item.tiket"></p>
                                                    <p class="text-[11px] text-[#6B7280] mt-0.5" x-text="item.tanggal"></p>
                                                </td>

                                                <!-- Judul Laporan -->
                                                <td class="py-4 px-4">
                                                    <p class="font-semibold text-[#1F2937] leading-snug line-clamp-2 max-w-xs" x-text="item.judul"></p>
                                                </td>

                                                <!-- Kategori -->
                                                <td class="py-4 px-4 whitespace-nowrap text-[#4B5563]" x-text="item.kategori"></td>

                                                <!-- Prioritas -->
                                                <td class="py-4 px-4 whitespace-nowrap">
                                                    <span
                                                        class="inline-block px-2.5 py-0.5 text-[11px] font-bold rounded-full"
                                                        :class="item.prioritasClass"
                                                        x-text="item.prioritas"
                                                    ></span>
                                                </td>

                                                <!-- Status -->
                                                <td class="py-4 px-4 whitespace-nowrap">
                                                    <div class="inline-flex items-center gap-1.5 font-bold" :class="item.statusClass">
                                                        <span class="w-1.5 h-1.5 rounded-full" :class="item.statusDot"></span>
                                                        <span x-text="item.status"></span>
                                                    </div>
                                                </td>

                                                <!-- Progres Bar -->
                                                <td class="py-4 px-4 whitespace-nowrap">
                                                    <div class="flex items-center gap-2">
                                                        <div class="w-16 bg-gray-200 rounded-full h-1.5 overflow-hidden">
                                                            <div class="bg-[#B01116] h-1.5 rounded-full" :style="'width: ' + item.progres + '%'"></div>
                                                        </div>
                                                        <span class="text-[11px] font-semibold text-[#4B5563]" x-text="item.progres + '%'"></span>
                                                    </div>
                                                </td>

                                                <!-- Aksi "Tangani ->" -->
                                                <td class="py-4 px-4 whitespace-nowrap text-right">
                                                    <a
                                                        :href="item.url"
                                                        class="text-xs font-bold text-[#B01116] hover:text-[#8B1D22] inline-flex items-center gap-1 transition"
                                                    >
                                                        Tangani &rarr;
                                                    </a>
                                                </td>
                                            </tr>
                                        </template>

                                        <!-- State Jika Kosong -->
                                        <tr x-show="filteredLaporans.length === 0" x-cloak>
                                            <td colspan="7" class="py-8 text-center text-[#6B7280]">
                                                Tidak ada laporan yang sesuai pencarian.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- KARTU "AKTIVITAS UNIT" (Samping Kanan Tabel) -->
                        <div class="lg:col-span-4 xl:col-span-4 bg-white rounded-2xl shadow-sm p-6 sm:p-7">
                            <h2 class="text-lg font-bold text-[#1F2937] tracking-tight mb-5">Aktivitas Unit</h2>

                            <!-- Timeline List -->
                            <div class="space-y-5">
                                @forelse ($aktivitasUnit ?? [] as $akt)
                                    <div class="flex items-start gap-3">
                                        <span class="w-2 h-2 rounded-full bg-[#B01116] shrink-0 mt-1.5"></span>
                                        <div>
                                            <p class="text-xs font-bold text-[#1F2937] leading-snug">
                                                {{ $akt->laporan?->nomor_tiket ? $akt->laporan->nomor_tiket . ' — ' : '' }}{{ $akt->status }}
                                            </p>
                                            <p class="text-[11px] text-[#6B7280] mt-0.5">{{ $akt->formatted_waktu }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-xs text-[#6B7280]">Belum ada aktivitas unit.</p>
                                @endforelse
                            </div>
                        </div>

                    </div>

                </div>
            </main>

        </div>
    </div>
</body>
</html>

