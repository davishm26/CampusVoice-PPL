<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#FFFFFF]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Riwayat Penanganan - Petugas Unit CampusVoice</title>

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
    x-data="riwayatPenangananApp()"
>
    <div class="h-screen overflow-hidden flex flex-col lg:flex-row">

        @include('unit.partials.sidebar', [
            'activeMenu' => 'riwayat',
            'unreadCount' => $unreadCount ?? 0,
        ])

        <!-- ========================================================= -->
        <!-- AREA UTAMA (Header + Konten) -->
        <!-- ========================================================= -->
        <div class="flex-1 flex flex-col min-w-0 min-h-0 overflow-hidden">

            @include('unit.partials.header', [
                'authUser' => $authUser ?? null,
                'unreadCount' => $unreadCount ?? 0,
                'searchModel' => 'search',
            ])

            <!-- ========================================================= -->
            <!-- AREA KONTEN UTAMA: Linear Gradient #B01116 ke #58595B -->
            <!-- ========================================================= -->
            <main class="flex-1 min-h-0 overflow-y-auto content-gradient-bg px-4 sm:px-8 lg:px-10 py-8 text-[#FFFFFF]">
                <div class="max-w-7xl mx-auto space-y-5">

                    <!-- Header Halaman (Atas) -->
                    <div>
                        <h1 class="text-2xl sm:text-[28px] font-bold text-[#FFFFFF] tracking-tight leading-tight">
                            Riwayat Penanganan
                        </h1>
                        <p class="text-xs sm:text-sm text-[#FFFFFF]/85 mt-1 font-normal">
                            Arsip laporan yang pernah ditangani oleh unit Anda.
                        </p>
                    </div>

                    <!-- Baris Filter & Pencarian (5 Elemen Sejajar) -->
                    <div class="bg-[#FFFFFF] rounded-2xl border border-[#E5E7EB] p-3 shadow-sm">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-2.5 items-center">
                            <!-- 1. Input Pencarian -->
                            <div class="relative lg:col-span-4">
                                <svg class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-[#6B7280]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="7"/>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                                </svg>
                                <input
                                    type="text"
                                    x-model="search"
                                    placeholder="Cari nomor tiket atau judul..."
                                    aria-label="Cari nomor tiket atau judul"
                                    class="w-full h-11 bg-[#FFFFFF] border border-[#E5E7EB] rounded-xl pl-10 pr-3.5 text-xs sm:text-sm text-[#252525] placeholder-[#6B7280] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116] focus:outline-none transition"
                                />
                            </div>

                            <!-- 2. Dropdown Status -->
                            <div class="relative lg:col-span-2">
                                <select
                                    x-model="status"
                                    aria-label="Filter Status"
                                    class="w-full h-11 appearance-none bg-[#FFFFFF] bg-none border border-[#E5E7EB] rounded-xl pl-3.5 pr-9 text-xs sm:text-sm font-medium text-[#252525] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116] focus:outline-none transition cursor-pointer"
                                >
                                    <option value="">Semua Status</option>
                                    <option value="Diteruskan">Diteruskan</option>
                                    <option value="Diproses">Diproses</option>
                                    <option value="Selesai">Selesai</option>
                                </select>
                                <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[#252525]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>

                            <!-- 3. Dropdown Kategori -->
                            <div class="relative lg:col-span-2">
                                <select
                                    x-model="kategori"
                                    aria-label="Filter Kategori"
                                    class="w-full h-11 appearance-none bg-[#FFFFFF] bg-none border border-[#E5E7EB] rounded-xl pl-3.5 pr-9 text-xs sm:text-sm font-medium text-[#252525] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116] focus:outline-none transition cursor-pointer"
                                >
                                    <option value="">Semua Kategori</option>
                                    <option value="Sarana dan prasarana kampus">Sarana &amp; Prasarana</option>
                                    <option value="Layanan kemahasiswaan">Kemahasiswaan</option>
                                    <option value="Akademik">Akademik</option>
                                    <option value="Layanan SSC">Layanan SSC</option>
                                    <option value="Administrasi & keuangan">Administrasi &amp; Keuangan</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                                <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[#252525]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>

                            <!-- 4. Dropdown Prioritas -->
                            <div class="relative lg:col-span-2">
                                <select
                                    x-model="prioritas"
                                    aria-label="Filter Prioritas"
                                    class="w-full h-11 appearance-none bg-[#FFFFFF] bg-none border border-[#E5E7EB] rounded-xl pl-3.5 pr-9 text-xs sm:text-sm font-medium text-[#252525] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116] focus:outline-none transition cursor-pointer"
                                >
                                    <option value="">Semua Prioritas</option>
                                    <option value="Tinggi">Tinggi</option>
                                    <option value="Sedang">Sedang</option>
                                    <option value="Rendah">Rendah</option>
                                </select>
                                <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[#252525]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>

                            <!-- 5. Range Tanggal -->
                            <div class="relative lg:col-span-2" @click.outside="datePopoverOpen = false">
                                <button
                                    type="button"
                                    @click="datePopoverOpen = !datePopoverOpen"
                                    aria-label="Range Tanggal"
                                    class="w-full h-11 bg-[#FFFFFF] border border-[#E5E7EB] rounded-xl px-3.5 text-xs sm:text-sm font-medium text-[#252525] flex items-center justify-between gap-2 hover:bg-gray-50 transition cursor-pointer"
                                >
                                    <span class="truncate" x-text="dateLabel"></span>
                                    <svg class="w-4 h-4 text-[#6B7280] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                </button>

                                <div
                                    x-show="datePopoverOpen"
                                    x-cloak
                                    x-transition
                                    class="absolute right-0 mt-2 w-72 bg-[#FFFFFF] rounded-2xl shadow-xl border border-[#E5E7EB] p-4 z-30 text-[#252525] space-y-3"
                                >
                                    <div>
                                        <label class="block text-[11px] font-bold text-[#6B7280] uppercase tracking-wider mb-1">Dari Tanggal</label>
                                        <input
                                            type="date"
                                            x-model="tanggalDari"
                                            class="w-full rounded-xl border border-[#E5E7EB] py-2 px-3 text-xs text-[#252525] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116]"
                                        />
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-[#6B7280] uppercase tracking-wider mb-1">Sampai Tanggal</label>
                                        <input
                                            type="date"
                                            x-model="tanggalSampai"
                                            class="w-full rounded-xl border border-[#E5E7EB] py-2 px-3 text-xs text-[#252525] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116]"
                                        />
                                    </div>
                                    <div class="flex items-center justify-between pt-1">
                                        <button
                                            type="button"
                                            @click="tanggalDari = ''; tanggalSampai = ''; datePopoverOpen = false;"
                                            class="text-xs font-semibold text-[#6B7280] hover:text-[#B01116] transition cursor-pointer"
                                        >
                                            Reset
                                        </button>
                                        <button
                                            type="button"
                                            @click="datePopoverOpen = false"
                                            class="px-3 py-1.5 rounded-lg bg-[#B01116] text-[#FFFFFF] text-xs font-semibold hover:bg-[#8D0D11] transition cursor-pointer"
                                        >
                                            Terapkan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabel Arsip Riwayat Penanganan (Kartu Putih Besar di Bawah) -->
                    <section class="bg-[#FFFFFF] rounded-2xl border border-[#E5E7EB] p-4 sm:p-6 shadow-xl">
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[820px] text-left border-collapse">
                                <thead>
                                    <tr class="bg-[#F9FAFB] border-b border-[#E5E7EB]">
                                        <th scope="col" class="py-3.5 px-4 text-[11px] font-bold uppercase tracking-wider text-[#252525] rounded-l-lg">
                                            NOMOR TIKET
                                        </th>
                                        <th scope="col" class="py-3.5 px-4 text-[11px] font-bold uppercase tracking-wider text-[#252525]">
                                            JUDUL
                                        </th>
                                        <th scope="col" class="py-3.5 px-4 text-[11px] font-bold uppercase tracking-wider text-[#252525]">
                                            DITERIMA
                                        </th>
                                        <th scope="col" class="py-3.5 px-4 text-[11px] font-bold uppercase tracking-wider text-[#252525]">
                                            SELESAI
                                        </th>
                                        <th scope="col" class="py-3.5 px-4 text-[11px] font-bold uppercase tracking-wider text-[#252525]">
                                            STATUS AKHIR
                                        </th>
                                        <th scope="col" class="py-3.5 px-4 text-[11px] font-bold uppercase tracking-wider text-[#252525]">
                                            DURASI
                                        </th>
                                        <th scope="col" class="py-3.5 px-4 rounded-r-lg text-right">
                                            <span class="sr-only">Aksi</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="row in filteredRows" :key="row.tiket">
                                        <tr class="border-b border-[#E5E7EB] last:border-b-0 hover:bg-[#F9FAFB]/70 transition">
                                            <!-- Nomor Tiket -->
                                            <td class="py-5 px-4 text-sm font-bold text-[#252525] whitespace-nowrap" x-text="row.tiket"></td>

                                            <!-- Judul -->
                                            <td class="py-5 px-4 text-sm font-semibold text-[#252525]" x-text="row.judul"></td>

                                            <!-- Diterima -->
                                            <td class="py-5 px-4 text-sm text-[#6B7280] whitespace-nowrap" x-text="row.diterima"></td>

                                            <!-- Selesai -->
                                            <td class="py-5 px-4 text-sm text-[#6B7280] whitespace-nowrap" x-text="row.selesai"></td>

                                            <!-- Status Akhir -->
                                            <td class="py-5 px-4 whitespace-nowrap">
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold"
                                                    :class="row.status === 'Diproses'
                                                        ? 'bg-[#F5F3FF] text-[#7C3AED]'
                                                        : 'bg-[#ECFDF5] text-[#16A34A]'"
                                                >
                                                    <span
                                                        class="w-1.5 h-1.5 rounded-full"
                                                        :class="row.status === 'Diproses' ? 'bg-[#7C3AED]' : 'bg-[#16A34A]'"
                                                    ></span>
                                                    <span x-text="row.status"></span>
                                                </span>
                                            </td>

                                            <!-- Durasi -->
                                            <td class="py-5 px-4 text-sm text-[#6B7280] whitespace-nowrap" x-text="row.durasi"></td>

                                            <!-- Aksi -->
                                            <td class="py-5 px-4 text-right whitespace-nowrap">
                                                <button
                                                    type="button"
                                                    @click="openDetail(row)"
                                                    class="inline-flex items-center gap-1 text-sm font-bold text-[#B01116] hover:text-[#8A0D12] transition cursor-pointer"
                                                >
                                                    <span>Detail &rarr;</span>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>

                                    <!-- Empty State jika hasil filter kosong -->
                                    <tr x-show="filteredRows.length === 0" x-cloak>
                                        <td colspan="7" class="py-12 px-4 text-center">
                                            <p class="text-sm font-semibold text-[#252525]">Tidak ada riwayat penanganan yang sesuai filter.</p>
                                            <p class="text-xs text-[#6B7280] mt-1">Coba ubah kata kunci pencarian atau atur ulang filter.</p>
                                            <button
                                                type="button"
                                                @click="resetFilters()"
                                                class="mt-3 inline-flex items-center px-3.5 py-2 rounded-lg border border-[#E5E7EB] text-xs font-bold text-[#B01116] hover:bg-[#FCE8E8]/50 transition cursor-pointer"
                                            >
                                                Reset Filter
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </section>

                </div>
            </main>
        </div>
    </div>

    <!-- Modal Pratinjau Detail Penanganan -->
    <div
        x-show="selectedRow"
        x-cloak
        @keydown.escape.window="selectedRow = null"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs"
    >
        <div
            @click.outside="selectedRow = null"
            class="w-full max-w-lg bg-[#FFFFFF] rounded-2xl border border-[#E5E7EB] shadow-2xl p-6 text-[#252525]"
        >
            <div class="flex items-start justify-between gap-4 pb-4 border-b border-[#E5E7EB]">
                <div>
                    <span class="text-xs font-bold text-[#B01116]" x-text="selectedRow?.tiket"></span>
                    <h3 class="text-lg font-bold text-[#252525] mt-0.5" x-text="selectedRow?.judul"></h3>
                </div>
                <button
                    type="button"
                    @click="selectedRow = null"
                    class="p-1.5 rounded-lg text-[#6B7280] hover:text-[#252525] hover:bg-gray-100 transition cursor-pointer"
                    aria-label="Tutup modal"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="py-4 grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-xs text-[#6B7280]">Kategori</p>
                    <p class="font-semibold text-[#252525] mt-0.5" x-text="selectedRow?.kategori"></p>
                </div>
                <div>
                    <p class="text-xs text-[#6B7280]">Prioritas</p>
                    <p class="font-semibold text-[#252525] mt-0.5" x-text="selectedRow?.prioritas"></p>
                </div>
                <div>
                    <p class="text-xs text-[#6B7280]">Tanggal Diterima</p>
                    <p class="font-semibold text-[#252525] mt-0.5" x-text="selectedRow?.diterima"></p>
                </div>
                <div>
                    <p class="text-xs text-[#6B7280]">Tanggal Selesai</p>
                    <p class="font-semibold text-[#252525] mt-0.5" x-text="selectedRow?.selesai"></p>
                </div>
                <div>
                    <p class="text-xs text-[#6B7280]">Status Akhir</p>
                    <span
                        class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold mt-1"
                        :class="selectedRow?.status === 'Diproses' ? 'bg-[#F5F3FF] text-[#7C3AED]' : 'bg-[#ECFDF5] text-[#16A34A]'"
                    >
                        <span class="w-1.5 h-1.5 rounded-full" :class="selectedRow?.status === 'Diproses' ? 'bg-[#7C3AED]' : 'bg-[#16A34A]'"></span>
                        <span x-text="selectedRow?.status"></span>
                    </span>
                </div>
                <div>
                    <p class="text-xs text-[#6B7280]">Durasi Penanganan</p>
                    <p class="font-semibold text-[#252525] mt-0.5" x-text="selectedRow?.durasi"></p>
                </div>
            </div>

            <div class="pt-4 border-t border-[#E5E7EB] flex justify-end">
                <button
                    type="button"
                    @click="selectedRow = null"
                    class="px-4 py-2 rounded-xl bg-[#B01116] hover:bg-[#8A0D12] text-[#FFFFFF] text-xs font-bold transition cursor-pointer"
                >
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <script>
        function riwayatPenangananApp() {
            return {
                mobileSidebarOpen: false,
                search: '',
                status: '',
                kategori: '',
                prioritas: '',
                tanggalDari: '',
                tanggalSampai: '',
                datePopoverOpen: false,
                selectedRow: null,

                rows: @js($rows ?? []),

                get dateLabel() {
                    if (this.tanggalDari && this.tanggalSampai) return `${this.tanggalDari} — ${this.tanggalSampai}`;
                    if (this.tanggalDari) return `Sejak ${this.tanggalDari}`;
                    if (this.tanggalSampai) return `Hingga ${this.tanggalSampai}`;
                    return 'Semua Periode';
                },

                get filteredRows() {
                    const q = this.search.trim().toLowerCase();
                    return this.rows.filter((row) => {
                        const okSearch = !q
                            || row.tiket.toLowerCase().includes(q)
                            || row.judul.toLowerCase().includes(q);
                        const okStatus = !this.status || row.status === this.status;
                        const okKategori = !this.kategori || row.kategori === this.kategori;
                        const okPrioritas = !this.prioritas || row.prioritas === this.prioritas;
                        const okDari = !this.tanggalDari || (row.tanggal_ymd && row.tanggal_ymd >= this.tanggalDari);
                        const okSampai = !this.tanggalSampai || (row.tanggal_ymd && row.tanggal_ymd <= this.tanggalSampai);
                        return okSearch && okStatus && okKategori && okPrioritas && okDari && okSampai;
                    });
                },

                openDetail(row) {
                    this.selectedRow = row;
                },

                resetFilters() {
                    this.search = '';
                    this.status = '';
                    this.kategori = '';
                    this.prioritas = '';
                    this.tanggalDari = '';
                    this.tanggalSampai = '';
                    this.datePopoverOpen = false;
                }
            };
        }
    </script>
</body>
</html>

