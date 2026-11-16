<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#FFFFFF]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Laporan Ditugaskan - Petugas Unit CampusVoice</title>

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

        /* Linear Gradient Konten Utama: Merah #B01116 ke Abu-abu Gelap #58595B (sama seperti fitur lain) */
        .content-gradient-bg {
            background: linear-gradient(to top right, #B01116 0%, #58595B 100%);
        }
    </style>
</head>
<body
    class="h-full antialiased text-[#252525] bg-[#B01116]"
    x-data="{
        mobileSidebarOpen: false,
        searchQuery: @js(request('q', '')),
        filterStatus: '',
        filterKategori: '',
        filterPrioritas: '',
        filterTanggalDari: '',
        filterTanggalSampai: '',
        datePopoverOpen: false,
        currentPage: 1,
        perPage: 6,
        items: {{ \Illuminate\Support\Js::from($laporans) }},
        get hasActiveFilters() {
            return Boolean(
                this.searchQuery.trim() ||
                this.filterStatus ||
                this.filterKategori ||
                this.filterPrioritas ||
                this.filterTanggalDari ||
                this.filterTanggalSampai
            );
        },
        resetFilters() {
            this.searchQuery = '';
            this.filterStatus = '';
            this.filterKategori = '';
            this.filterPrioritas = '';
            this.filterTanggalDari = '';
            this.filterTanggalSampai = '';
            this.datePopoverOpen = false;
            this.currentPage = 1;
        },
        get dateLabel() {
            if (!this.filterTanggalDari && !this.filterTanggalSampai) return 'Semua Tanggal';
            const fmt = (d) => {
                if (!d) return '...';
                const dt = new Date(d + 'T00:00:00');
                return dt.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
            };
            return fmt(this.filterTanggalDari) + ' — ' + fmt(this.filterTanggalSampai);
        },
        get filteredItems() {
            const q = this.searchQuery.trim().toLowerCase();
            return this.items.filter(item => {
                if (this.filterStatus && item.status !== this.filterStatus) return false;
                if (this.filterKategori && item.kategori !== this.filterKategori) return false;
                if (this.filterPrioritas && item.prioritas !== this.filterPrioritas) return false;
                if (this.filterTanggalDari && (!item.tanggal_ymd || item.tanggal_ymd < this.filterTanggalDari)) return false;
                if (this.filterTanggalSampai && (!item.tanggal_ymd || item.tanggal_ymd > this.filterTanggalSampai)) return false;
                if (q) {
                    const matchTiket = item.tiket && item.tiket.toLowerCase().includes(q);
                    const matchJudul = item.judul && item.judul.toLowerCase().includes(q);
                    const matchKategori = item.kategori && item.kategori.toLowerCase().includes(q);
                    if (!matchTiket && !matchJudul && !matchKategori) return false;
                }
                return true;
            });
        },
        get totalPages() {
            return Math.max(1, Math.ceil(this.filteredItems.length / this.perPage));
        },
        get paginatedItems() {
            if (this.currentPage > this.totalPages) {
                this.currentPage = 1;
            }
            const start = (this.currentPage - 1) * this.perPage;
            return this.filteredItems.slice(start, start + this.perPage);
        },
        get rangeStart() {
            if (this.filteredItems.length === 0) return 0;
            return (this.currentPage - 1) * this.perPage + 1;
        },
        get rangeEnd() {
            return Math.min(this.currentPage * this.perPage, this.filteredItems.length);
        },
        prevPage() {
            if (this.currentPage > 1) this.currentPage--;
        },
        nextPage() {
            if (this.currentPage < this.totalPages) this.currentPage++;
        },
        goToPage(page) {
            if (page >= 1 && page <= this.totalPages) this.currentPage = page;
        }
    }"
>
    <div class="h-screen overflow-hidden flex flex-col lg:flex-row">

        @include('unit.partials.sidebar', ['activeMenu' => 'ditugaskan', 'unreadCount' => $unreadCount])

        <!-- ========================================================= -->
        <!-- AREA UTAMA (Header + Konten Laporan Ditugaskan) -->
        <!-- ========================================================= -->
        <div class="flex-1 flex flex-col min-w-0 min-h-0 overflow-hidden">

            @include('unit.partials.header', ['authUser' => $authUser, 'unreadCount' => $unreadCount])

            <!-- ========================================================= -->
            <!-- KONTEN UTAMA: LAPORAN DITUGASKAN -->
            <!-- ========================================================= -->
            <main class="flex-1 min-h-0 overflow-y-auto content-gradient-bg px-4 sm:px-8 lg:px-10 py-8">
                <div class="max-w-7xl mx-auto space-y-5">

                    <!-- Header Halaman -->
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-[#FFFFFF] tracking-tight">
                            Laporan Ditugaskan
                        </h1>
                        <p class="text-xs sm:text-sm text-[#FFFFFF]/85 mt-1 font-normal">
                            Daftar laporan yang diteruskan ke unit Anda untuk segera ditangani.
                        </p>
                    </div>

                    <!-- Baris Filter & Pencarian -->
                    <div class="flex flex-wrap items-center gap-3">
                        <!-- Input Pencarian Lebar -->
                        <div class="flex-1 min-w-[230px]">
                            <input
                                type="text"
                                x-model="searchQuery"
                                @input="currentPage = 1"
                                placeholder="Cari nomor tiket atau judul..."
                                class="w-full bg-[#FFFFFF] border border-[#E5E7EB] rounded-xl py-2.5 px-4 text-xs sm:text-sm text-[#252525] placeholder-[#9CA3AF] focus:border-[#B01116] focus:outline-none focus:ring-1 focus:ring-[#B01116] transition"
                            />
                        </div>

                        <!-- Dropdown: Semua Status -->
                        <div class="relative min-w-[140px]">
                            <select
                                x-model="filterStatus"
                                @change="currentPage = 1"
                                class="w-full appearance-none bg-none bg-[#FFFFFF] border border-[#E5E7EB] rounded-xl py-2.5 pl-4 pr-9 text-xs sm:text-sm font-medium text-[#252525] focus:border-[#B01116] focus:outline-none focus:ring-1 focus:ring-[#B01116] transition cursor-pointer"
                            >
                                <option value="">Semua Status</option>
                                <option value="Diteruskan">Diteruskan</option>
                                <option value="Diproses">Diproses</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-[#6B7280]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Dropdown: Semua Kategori -->
                        <div class="relative min-w-[150px]">
                            <select
                                x-model="filterKategori"
                                @change="currentPage = 1"
                                class="w-full appearance-none bg-none bg-[#FFFFFF] border border-[#E5E7EB] rounded-xl py-2.5 pl-4 pr-9 text-xs sm:text-sm font-medium text-[#252525] focus:border-[#B01116] focus:outline-none focus:ring-1 focus:ring-[#B01116] transition cursor-pointer"
                            >
                                <option value="">Semua Kategori</option>
                                <option value="Satpras">Satpras</option>
                                <option value="SSC">SSC</option>
                                <option value="Akademik">Akademik</option>
                                <option value="Kemahasiswaan">Kemahasiswaan</option>
                                <option value="Keuangan">Keuangan</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-[#6B7280]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Dropdown: Semua Prioritas -->
                        <div class="relative min-w-[150px]">
                            <select
                                x-model="filterPrioritas"
                                @change="currentPage = 1"
                                class="w-full appearance-none bg-none bg-[#FFFFFF] border border-[#E5E7EB] rounded-xl py-2.5 pl-4 pr-9 text-xs sm:text-sm font-medium text-[#252525] focus:border-[#B01116] focus:outline-none focus:ring-1 focus:ring-[#B01116] transition cursor-pointer"
                            >
                                <option value="">Semua Prioritas</option>
                                <option value="Tinggi">Tinggi</option>
                                <option value="Sedang">Sedang</option>
                                <option value="Rendah">Rendah</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-[#6B7280]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Rentang Tanggal -->
                        <div class="relative shrink-0" @click.outside="datePopoverOpen = false">
                            <button
                                type="button"
                                @click="datePopoverOpen = !datePopoverOpen"
                                class="bg-[#FFFFFF] border border-[#E5E7EB] rounded-xl py-2.5 px-4 text-xs sm:text-sm font-medium text-[#252525] whitespace-nowrap flex items-center gap-2 cursor-pointer hover:bg-gray-50 transition"
                            >
                                <svg class="w-4 h-4 text-[#6B7280]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                                <span x-text="dateLabel"></span>
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
                                        x-model="filterTanggalDari"
                                        :max="filterTanggalSampai || null"
                                        @change="currentPage = 1"
                                        class="w-full rounded-xl border border-[#E5E7EB] py-2 px-3 text-xs text-[#252525] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116]"
                                    />
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-[#6B7280] uppercase tracking-wider mb-1">Sampai Tanggal</label>
                                    <input
                                        type="date"
                                        x-model="filterTanggalSampai"
                                        :min="filterTanggalDari || null"
                                        @change="currentPage = 1"
                                        class="w-full rounded-xl border border-[#E5E7EB] py-2 px-3 text-xs text-[#252525] focus:border-[#B01116] focus:ring-1 focus:ring-[#B01116]"
                                    />
                                </div>
                                <div class="flex items-center justify-between pt-1">
                                    <button
                                        type="button"
                                        @click="filterTanggalDari = ''; filterTanggalSampai = ''; datePopoverOpen = false; currentPage = 1;"
                                        class="text-xs font-semibold text-[#6B7280] hover:text-[#B01116] transition cursor-pointer"
                                    >
                                        Reset
                                    </button>
                                    <button
                                        type="button"
                                        @click="datePopoverOpen = false"
                                        class="px-3 py-1.5 rounded-lg bg-[#B01116] text-[#FFFFFF] text-xs font-bold hover:bg-[#970e13] transition cursor-pointer"
                                    >
                                        Terapkan
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Hapus Semua Filter -->
                        <button
                            type="button"
                            x-show="hasActiveFilters"
                            x-cloak
                            x-transition
                            @click="resetFilters()"
                            class="bg-[#FFFFFF] hover:bg-[#FCE8E8] text-[#B01116] rounded-xl border border-[#E5E7EB] py-2.5 px-4 text-xs sm:text-sm font-bold shadow-sm whitespace-nowrap shrink-0 flex items-center gap-1.5 cursor-pointer transition"
                            title="Hapus semua filter"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <span>Hapus Filter</span>
                        </button>
                    </div>

                    <!-- Kartu Tabel Laporan Ditugaskan -->
                    <div class="bg-[#FFFFFF] rounded-2xl border border-[#E5E7EB] shadow-xl overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-[#F9FAFB] border-b border-[#E5E7EB] text-[11px] font-bold text-[#6B7280] uppercase tracking-wider">
                                        <th scope="col" class="py-4 px-6">NOMOR TIKET</th>
                                        <th scope="col" class="py-4 px-6">JUDUL LAPORAN</th>
                                        <th scope="col" class="py-4 px-6">KATEGORI</th>
                                        <th scope="col" class="py-4 px-6">PRIORITAS</th>
                                        <th scope="col" class="py-4 px-6">STATUS</th>
                                        <th scope="col" class="py-4 px-6">PROGRES</th>
                                        <th scope="col" class="py-4 px-6 text-right"><span class="sr-only">Aksi</span></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#F3F4F6] text-sm">
                                    <template x-for="item in paginatedItems" :key="item.tiket">
                                        <tr class="hover:bg-gray-50/80 transition">
                                            <!-- Nomor Tiket & Tanggal -->
                                            <td class="py-5 px-6 whitespace-nowrap">
                                                <div class="font-bold text-[#252525] text-sm" x-text="item.tiket"></div>
                                                <div class="text-xs text-[#6B7280] mt-0.5" x-text="item.tanggal"></div>
                                            </td>

                                            <!-- Judul Laporan -->
                                            <td class="py-5 px-6">
                                                <div class="font-bold text-[#252525] text-sm leading-snug" x-text="item.judul"></div>
                                                <template x-if="item.is_anonim">
                                                    <div class="mt-1 inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-[#F1F5F9] text-[10px] font-semibold text-[#475569]">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-[#64748B]"></span>
                                                        <span>Anonim &bull; Identitas disembunyikan</span>
                                                    </div>
                                                </template>
                                            </td>

                                            <!-- Kategori -->
                                            <td class="py-5 px-6 whitespace-nowrap text-xs sm:text-sm text-[#4B5563]" x-text="item.kategori"></td>

                                            <!-- Prioritas Badge -->
                                            <td class="py-5 px-6 whitespace-nowrap">
                                                <span
                                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold"
                                                    :class="{
                                                        'bg-[#FEE2E2] text-[#B01116]': item.prioritas === 'Tinggi',
                                                        'bg-[#FEF3C7] text-[#D97706]': item.prioritas === 'Sedang',
                                                        'bg-[#F3F4F6] text-[#4B5563]': item.prioritas === 'Rendah'
                                                    }"
                                                    x-text="item.prioritas"
                                                ></span>
                                            </td>

                                            <!-- Status Badge -->
                                            <td class="py-5 px-6 whitespace-nowrap">
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold"
                                                    :class="{
                                                        'bg-[#F3E8FF] text-[#7C3AED]': item.status === 'Diproses',
                                                        'bg-[#EFF6FF] text-[#2563EB]': item.status === 'Diteruskan',
                                                        'bg-[#ECFDF5] text-[#16A34A]': item.status === 'Selesai'
                                                    }"
                                                >
                                                    <span
                                                        class="w-1.5 h-1.5 rounded-full"
                                                        :class="{
                                                            'bg-[#7C3AED]': item.status === 'Diproses',
                                                            'bg-[#2563EB]': item.status === 'Diteruskan',
                                                            'bg-[#16A34A]': item.status === 'Selesai'
                                                        }"
                                                    ></span>
                                                    <span x-text="item.status"></span>
                                                </span>
                                            </td>

                                            <!-- Progres Bar -->
                                            <td class="py-5 px-6 whitespace-nowrap">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-20 h-1.5 bg-[#E5E7EB] rounded-full overflow-hidden">
                                                        <div
                                                            class="h-full bg-[#B01116] rounded-full transition-all duration-300"
                                                            :style="'width: ' + item.progres + '%'"
                                                        ></div>
                                                    </div>
                                                    <span class="text-xs font-semibold text-[#4B5563]" x-text="item.progres + '%'"></span>
                                                </div>
                                            </td>

                                            <!-- Aksi Tangani -->
                                            <td class="py-5 px-6 whitespace-nowrap text-right">
                                                <a
                                                    :href="item.url"
                                                    class="inline-flex items-center gap-1 text-xs sm:text-sm font-bold text-[#B01116] hover:text-[#8C0D11] hover:underline transition"
                                                >
                                                    <span>Tangani</span>
                                                    <span>&rarr;</span>
                                                </a>
                                            </td>
                                        </tr>
                                    </template>

                                    <!-- Empty State -->
                                    <tr x-show="filteredItems.length === 0" x-cloak>
                                        <td colspan="7" class="py-12 px-6 text-center text-sm text-[#6B7280]">
                                            Tidak ada laporan ditugaskan yang sesuai dengan filter pencarian.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Footer Pagination -->
                        <div class="px-6 py-4 bg-[#FFFFFF] border-t border-[#F3F4F6] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <p class="text-xs text-[#6B7280]">
                                Menampilkan <span class="font-semibold text-[#252525]" x-text="rangeStart + '–' + rangeEnd"></span> dari <span class="font-semibold text-[#252525]" x-text="filteredItems.length"></span> laporan
                            </p>

                            <div class="flex items-center gap-1.5">
                                <!-- Tombol Prev -->
                                <button
                                    type="button"
                                    @click="prevPage()"
                                    :disabled="currentPage === 1"
                                    class="w-8 h-8 rounded-lg border border-[#E5E7EB] bg-[#FFFFFF] text-[#4B5563] hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed flex items-center justify-center text-xs transition cursor-pointer"
                                    title="Sebelumnya"
                                >
                                    &larr;
                                </button>

                                <!-- Nomor Halaman -->
                                <template x-for="page in totalPages" :key="page">
                                    <button
                                        type="button"
                                        @click="goToPage(page)"
                                        class="w-8 h-8 rounded-lg text-xs font-bold flex items-center justify-center transition cursor-pointer"
                                        :class="currentPage === page
                                            ? 'bg-[#B01116] text-[#FFFFFF] shadow-xs'
                                            : 'border border-[#E5E7EB] bg-[#FFFFFF] text-[#4B5563] hover:bg-gray-50'"
                                        x-text="page"
                                    ></button>
                                </template>

                                <!-- Tombol Next -->
                                <button
                                    type="button"
                                    @click="nextPage()"
                                    :disabled="currentPage === totalPages"
                                    class="w-8 h-8 rounded-lg border border-[#E5E7EB] bg-[#FFFFFF] text-[#4B5563] hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed flex items-center justify-center text-xs transition cursor-pointer"
                                    title="Berikutnya"
                                >
                                    &rarr;
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

