<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#FFFFFF]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Tangani Laporan {{ $laporan['tiket'] }} - Petugas Unit CampusVoice</title>

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
        status: '{{ $laporan['status'] }}',
        progres: {{ $laporan['progres'] }},
        catatan: '',
        actionType: 'simpan',
        selectedFileName: '',
        previewModalOpen: false,
        simulasikanPenyelesaian() {
            this.status = 'Selesai';
            this.progres = 100;
            if (!this.catatan.trim()) {
                this.catatan = 'Perbaikan dan penanganan laporan telah selesai dilakukan dan diverifikasi oleh tim unit.';
            }
        }
    }"
>
    <div class="h-screen overflow-hidden flex flex-col lg:flex-row">

        @include('unit.partials.sidebar', ['activeMenu' => 'ditugaskan', 'unreadCount' => $unreadCount])

        <!-- ========================================================= -->
        <!-- AREA UTAMA (Header + Konten Tangani Laporan) -->
        <!-- ========================================================= -->
        <div class="flex-1 flex flex-col min-w-0 min-h-0 overflow-hidden">

            @include('unit.partials.header', ['authUser' => $authUser, 'unreadCount' => $unreadCount])

            <!-- ========================================================= -->
            <!-- KONTEN UTAMA: DETAIL & PENANGANAN LAPORAN -->
            <!-- ========================================================= -->
            <main class="flex-1 min-h-0 overflow-y-auto content-gradient-bg px-4 sm:px-8 lg:px-10 py-7">
                <div class="max-w-7xl mx-auto space-y-5">

                    <!-- Baris Kembali & Nomor Tiket -->
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <a
                            href="{{ route('unit.laporan-ditugaskan') }}"
                            class="inline-flex items-center gap-2 text-xs sm:text-sm font-semibold text-white/90 hover:text-white transition"
                        >
                            <span>&larr;</span>
                            <span>Kembali ke Laporan Ditugaskan</span>
                        </a>
                        <span class="px-3 py-1 rounded-full bg-white/15 text-white text-xs font-bold tracking-wide">
                            {{ $laporan['tiket'] }}
                        </span>
                    </div>

                    <!-- Flash Message Sukses -->
                    @if (session('success'))
                        <div class="bg-[#ECFDF5] border border-[#A7F3D0] text-[#065F46] px-4 py-3 rounded-xl text-sm font-semibold flex items-center justify-between shadow-sm">
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    <!-- Grid 2 Kolom: Kiri (Detail + Riwayat) & Kanan (Pembaruan Penanganan) -->
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                        <!-- ================================================= -->
                        <!-- KOLOM KIRI (8 Kolom) -->
                        <!-- ================================================= -->
                        <div class="lg:col-span-8 space-y-6">

                            <!-- KARTU 1: DETAIL LAPORAN -->
                            <div class="bg-[#FFFFFF] rounded-2xl p-6 shadow-xl border border-[#E5E7EB]">
                                <!-- Badge Baris Atas -->
                                <div class="flex flex-wrap items-center gap-2">
                                    <!-- Badge Status -->
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold"
                                        :class="{
                                            'bg-[#F3E8FF] text-[#7C3AED]': status === 'Diproses',
                                            'bg-[#EFF6FF] text-[#2563EB]': status === 'Diteruskan',
                                            'bg-[#ECFDF5] text-[#16A34A]': status === 'Selesai',
                                            'bg-[#FEF3C7] text-[#D97706]': status === 'Menunggu Verifikasi'
                                        }"
                                    >
                                        <span
                                            class="w-1.5 h-1.5 rounded-full"
                                            :class="{
                                                'bg-[#7C3AED]': status === 'Diproses',
                                                'bg-[#2563EB]': status === 'Diteruskan',
                                                'bg-[#16A34A]': status === 'Selesai',
                                                'bg-[#D97706]': status === 'Menunggu Verifikasi'
                                            }"
                                        ></span>
                                        <span x-text="status">{{ $laporan['status'] }}</span>
                                    </span>

                                    <!-- Badge Prioritas -->
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-[#FEE2E2] text-[#B01116]">
                                        {{ $laporan['prioritas'] }}
                                    </span>

                                    <!-- Badge Laporan Anonim (jika anonim) -->
                                    @if ($laporan['is_anonim'])
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[#F1F5F9] text-[#475569]">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#64748B]"></span>
                                            <span>Laporan Anonim</span>
                                        </span>
                                    @endif
                                </div>

                                <!-- Box Info Kategori, Lokasi, Pelapor -->
                                <div class="mt-4 bg-[#F9FAFB] rounded-xl p-4 sm:p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-[10px] font-bold text-[#6B7280] uppercase tracking-wider">KATEGORI</p>
                                        <p class="text-sm font-bold text-[#252525] mt-1">{{ $laporan['kategori'] }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-[#6B7280] uppercase tracking-wider">LOKASI</p>
                                        <p class="text-sm font-bold text-[#252525] mt-1">{{ $laporan['lokasi'] }}</p>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <p class="text-[10px] font-bold text-[#6B7280] uppercase tracking-wider">PELAPOR</p>
                                        <p class="text-sm font-bold text-[#252525] mt-1">{{ $laporan['pelapor'] }}</p>
                                    </div>
                                </div>

                                <!-- Deskripsi Laporan -->
                                <div class="mt-5">
                                    <h3 class="text-sm font-bold text-[#252525]">Deskripsi laporan</h3>
                                    <p class="text-sm text-[#4B5563] mt-1.5 leading-relaxed">
                                        {{ $laporan['deskripsi'] }}
                                    </p>
                                </div>

                                <!-- Lampiran Bukti -->
                                @php
                                    $berkasUrl = $laporan['berkas_url'] ?? null;
                                    $berkasExt = strtolower($laporan['berkas_ext'] ?? ($berkasUrl ? pathinfo(parse_url($berkasUrl, PHP_URL_PATH), PATHINFO_EXTENSION) : ''));
                                    $berkasIsImage = in_array($berkasExt, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                    $berkasIsPdf = $berkasExt === 'pdf';
                                @endphp
                                <div class="mt-5 border border-[#E5E7EB] rounded-xl px-4 py-3 space-y-3">
                                    @if (!empty($berkasUrl) || !empty($laporan['berkas_nama']))
                                        <div class="flex items-center justify-between gap-3">
                                            <div class="flex items-center gap-3 min-w-0">
                                                @if ($berkasUrl && $berkasIsImage)
                                                    <button type="button" @click="previewModalOpen = true" class="w-11 h-11 rounded-lg overflow-hidden border border-[#E5E7EB] bg-[#F9FAFB] shrink-0 cursor-pointer hover:opacity-90 transition">
                                                        <img src="{{ $berkasUrl }}" alt="{{ $laporan['berkas_nama'] ?: 'Lampiran Bukti' }}" class="w-full h-full object-cover" />
                                                    </button>
                                                @else
                                                    <div class="w-9 h-9 rounded-lg bg-[#F9FAFB] border border-[#E5E7EB] flex items-center justify-center text-[#4B5563] shrink-0">
                                                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                        </svg>
                                                    </div>
                                                @endif
                                                <div class="min-w-0">
                                                    <p class="text-sm font-bold text-[#252525] truncate">{{ $laporan['berkas_nama'] ?: 'Lampiran Bukti' }}</p>
                                                    @if (!empty($laporan['berkas_meta']))
                                                        <p class="text-[11px] text-[#6B7280]">{{ $laporan['berkas_meta'] }}</p>
                                                    @endif
                                                </div>
                                            </div>

                                            @if ($berkasUrl)
                                                <button
                                                    type="button"
                                                    @click="previewModalOpen = true"
                                                    class="text-xs sm:text-sm font-bold text-[#B01116] hover:text-[#8C0D11] hover:underline shrink-0 cursor-pointer"
                                                >
                                                    Lihat
                                                </button>
                                            @else
                                                <span class="text-xs text-[#6B7280] italic shrink-0">Terlampir</span>
                                            @endif
                                        </div>

                                        @if ($berkasUrl && $berkasIsImage)
                                            <button type="button" @click="previewModalOpen = true" class="block w-full rounded-lg overflow-hidden border border-[#E5E7EB] bg-[#F9FAFB] p-2 hover:border-[#B01116]/40 transition cursor-pointer">
                                                <img src="{{ $berkasUrl }}" alt="{{ $laporan['berkas_nama'] ?: 'Bukti Pendukung' }}" class="max-h-48 mx-auto rounded-md object-contain" />
                                            </button>
                                        @endif
                                    @else
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-9 h-9 rounded-lg bg-[#F9FAFB] border border-[#E5E7EB] flex items-center justify-center text-[#9CA3AF] shrink-0">
                                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            </div>
                                            <p class="text-xs text-[#6B7280]">Tidak ada lampiran bukti pendukung.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- KARTU 2: RIWAYAT DAN TANGGAPAN -->
                            <div class="bg-[#FFFFFF] rounded-2xl p-6 shadow-xl border border-[#E5E7EB]">
                                <h3 class="text-base font-bold text-[#252525]">Riwayat dan Tanggapan</h3>

                                <div class="mt-5 space-y-4">
                                    @foreach ($riwayats as $r)
                                        <div class="flex items-start gap-3.5 pb-4 border-b border-[#F3F4F6] last:border-b-0 last:pb-0">
                                            <div class="w-9 h-9 rounded-xl bg-[#FCE8E8] text-[#B01116] font-bold text-xs flex items-center justify-center shrink-0">
                                                {{ $r['inisial'] }}
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex flex-wrap items-center gap-2">
                                                    <span class="text-sm font-bold text-[#252525]">{{ $r['nama'] }}</span>
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-[#F1F5F9] text-[#475569]">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-[#64748B]"></span>
                                                        <span>{{ $r['role'] }}</span>
                                                    </span>
                                                </div>
                                                <p class="text-sm text-[#4B5563] mt-1 leading-relaxed">
                                                    {{ $r['pesan'] }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        </div>

                        <!-- ================================================= -->
                        <!-- KOLOM KANAN: PEMBARUAN PENANGANAN (4 Kolom) -->
                        <!-- ================================================= -->
                        <div class="lg:col-span-4">
                            <div class="bg-[#FFFFFF] rounded-2xl p-6 shadow-xl border border-[#E5E7EB]">
                                <h3 class="text-base font-bold text-[#252525]">Pembaruan Penanganan</h3>
                                <p class="text-xs text-[#6B7280] mt-0.5">Perubahan akan tercatat dalam riwayat laporan.</p>

                                <form
                                    method="POST"
                                    action="{{ route('unit.laporan-ditugaskan.update', $laporan['tiket']) }}"
                                    enctype="multipart/form-data"
                                    class="mt-5 space-y-4"
                                >
                                    @csrf
                                    <input type="hidden" name="action_type" x-model="actionType">

                                    <!-- Status Laporan -->
                                    <div>
                                        <label for="status" class="block text-xs font-bold text-[#252525] mb-1.5">
                                            Status Laporan <span class="text-[#B01116]">*</span>
                                        </label>
                                        <select
                                            id="status"
                                            name="status"
                                            x-model="status"
                                            class="w-full bg-[#FFFFFF] border border-[#E5E7EB] rounded-xl py-2.5 px-3.5 text-sm font-medium text-[#252525] focus:border-[#B01116] focus:outline-none focus:ring-1 focus:ring-[#B01116] transition cursor-pointer"
                                        >
                                            <option value="Diproses">Diproses</option>
                                            <option value="Diteruskan">Diteruskan</option>
                                            <option value="Selesai">Selesai</option>
                                            <option value="Menunggu Verifikasi">Menunggu Verifikasi</option>
                                        </select>
                                    </div>

                                    <!-- Persentase Progres -->
                                    <div>
                                        <div class="flex items-center justify-between mb-1.5">
                                            <label for="progres" class="text-xs font-bold text-[#252525]">
                                                Persentase Progres: <span class="text-[#B01116]" x-text="progres + '%'"></span>
                                            </label>
                                        </div>
                                        <input
                                            id="progres"
                                            name="progres"
                                            type="range"
                                            min="0"
                                            max="100"
                                            step="5"
                                            x-model="progres"
                                            class="w-full h-1.5 bg-[#E5E7EB] rounded-lg appearance-none cursor-pointer accent-[#B01116]"
                                        />
                                    </div>

                                    <!-- Catatan Progres -->
                                    <div>
                                        <label for="catatan" class="block text-xs font-bold text-[#252525] mb-1.5">
                                            Catatan Progres <span class="text-[#B01116]">*</span>
                                        </label>
                                        <textarea
                                            id="catatan"
                                            name="catatan"
                                            rows="3"
                                            x-model="catatan"
                                            placeholder="Jelaskan tindakan yang telah dilakukan..."
                                            class="w-full bg-[#FFFFFF] border border-[#E5E7EB] rounded-xl py-2.5 px-3.5 text-sm text-[#252525] placeholder-[#9CA3AF] focus:border-[#B01116] focus:outline-none focus:ring-1 focus:ring-[#B01116] transition resize-none"
                                        ></textarea>
                                    </div>

                                    <!-- Unggah Bukti Penyelesaian -->
                                    <div>
                                        <label
                                            for="bukti_penyelesaian"
                                            class="block border-2 border-dashed border-[#E5E7EB] hover:border-[#B01116]/60 hover:bg-[#FCE8E8]/20 rounded-xl p-5 text-center cursor-pointer transition"
                                        >
                                            <div class="flex justify-center text-[#B01116] mb-1.5">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                                </svg>
                                            </div>
                                            <p class="text-xs font-bold text-[#252525]" x-text="selectedFileName ? selectedFileName : 'Unggah Bukti Penyelesaian'"></p>
                                            <p class="text-[11px] text-[#6B7280] mt-0.5">Wajib sebelum menandai selesai</p>
                                            <input
                                                id="bukti_penyelesaian"
                                                name="bukti_penyelesaian"
                                                type="file"
                                                class="hidden"
                                                @change="selectedFileName = $event.target.files[0] ? $event.target.files[0].name : ''"
                                            />
                                        </label>
                                    </div>

                                    <!-- Tombol Utama -->
                                    <div class="space-y-2.5 pt-1">
                                        <button
                                            type="submit"
                                            @click="actionType = 'simpan'"
                                            class="w-full py-2.5 px-4 rounded-xl bg-[#B01116] hover:bg-[#8C0D11] text-[#FFFFFF] text-sm font-bold shadow-sm transition cursor-pointer"
                                        >
                                            Simpan Pembaruan
                                        </button>

                                        <button
                                            type="submit"
                                            @click="actionType = 'tanggapan'"
                                            class="w-full py-2.5 px-4 rounded-xl bg-[#FFFFFF] border border-[#E5E7EB] hover:bg-gray-50 text-[#252525] text-sm font-bold transition cursor-pointer"
                                        >
                                            Kirim Tanggapan
                                        </button>
                                    </div>

                                    <div class="border-t border-[#E5E7EB] pt-4 space-y-3">
                                        <button
                                            type="submit"
                                            @click="actionType = 'kembalikan'"
                                            class="w-full py-2.5 px-4 rounded-xl bg-[#FCE8E8] hover:bg-[#F8D1D1] text-[#B01116] text-sm font-bold transition cursor-pointer"
                                        >
                                            Kembalikan ke SSC
                                        </button>

                                        <button
                                            type="button"
                                            @click="simulasikanPenyelesaian()"
                                            class="w-full text-center text-xs font-bold text-[#B01116] hover:underline transition cursor-pointer"
                                        >
                                            Simulasikan penyelesaian
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </main>
        </div>

    </div>

    <!-- Modal Preview Lampiran -->
    <div
        x-show="previewModalOpen"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-xs p-4"
        @click.self="previewModalOpen = false"
        @keydown.escape.window="previewModalOpen = false"
    >
        <div class="bg-[#FFFFFF] rounded-2xl max-w-2xl w-full p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-[#E5E7EB] pb-3">
                <div class="min-w-0">
                    <h4 class="text-sm font-bold text-[#252525]">Pratinjau Lampiran</h4>
                    @if (!empty($laporan['berkas_nama']))
                        <p class="text-[11px] text-[#6B7280] truncate">{{ $laporan['berkas_nama'] }}{{ !empty($laporan['berkas_meta']) ? ' (' . $laporan['berkas_meta'] . ')' : '' }}</p>
                    @endif
                </div>
                <button type="button" @click="previewModalOpen = false" class="p-1 rounded-lg text-[#6B7280] hover:text-[#252525] hover:bg-gray-100 cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="rounded-xl bg-[#F9FAFB] border border-[#E5E7EB] p-4 text-center overflow-hidden">
                @if ($berkasUrl && $berkasIsImage)
                    <img src="{{ $berkasUrl }}" alt="{{ $laporan['berkas_nama'] ?: 'Bukti Pendukung' }}" class="max-h-[28rem] mx-auto rounded-lg object-contain" />
                @elseif ($berkasUrl && $berkasIsPdf)
                    <iframe src="{{ $berkasUrl }}" class="w-full h-96 rounded-lg border border-[#E5E7EB]"></iframe>
                @elseif ($berkasUrl)
                    <p class="text-sm font-bold text-[#252525]">{{ $laporan['berkas_nama'] ?: 'Lampiran Bukti' }}</p>
                    <p class="text-xs text-[#6B7280] mt-1">Klik tombol di bawah untuk membuka berkas lampiran.</p>
                @else
                    <p class="text-xs text-[#6B7280] py-4">Tidak ada berkas bukti pendukung yang dilampirkan.</p>
                @endif
            </div>
            <div class="flex justify-end gap-2">
                @if ($berkasUrl)
                    <a href="{{ $berkasUrl }}" target="_blank" class="px-4 py-2 rounded-xl border border-[#E5E7EB] text-xs font-bold text-[#252525] hover:bg-gray-50 transition">
                        Buka di Tab Baru
                    </a>
                @endif
                <button
                    type="button"
                    @click="previewModalOpen = false"
                    class="px-4 py-2 rounded-xl bg-[#B01116] text-[#FFFFFF] text-xs font-bold hover:bg-[#8C0D11] transition cursor-pointer"
                >
                    Tutup
                </button>
            </div>
        </div>
    </div>
</body>
</html>

