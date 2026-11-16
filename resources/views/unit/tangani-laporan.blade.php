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
        syncProgresWithStatus() {
            const p = Number(this.progres);
            if (this.status === 'Selesai') {
                this.progres = 100;
            } else if (this.status === 'Diteruskan') {
                this.progres = 35;
            } else if (this.status === 'Diproses' && (p < 40 || p >= 100)) {
                this.progres = 60;
            }
        },
        syncStatusWithProgres() {
            const p = Number(this.progres);
            if (p >= 100) {
                this.status = 'Selesai';
            } else if (p < 40) {
                this.status = 'Diteruskan';
            } else {
                this.status = 'Diproses';
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

                    <!-- Flash Message Sukses / Error -->
                    @if (session('success'))
                        <div class="bg-[#ECFDF5] border border-[#A7F3D0] text-[#065F46] px-4 py-3 rounded-xl text-sm font-semibold flex items-center justify-between shadow-sm">
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="bg-[#FEF2F2] border border-[#FECACA] text-[#B91C1C] px-4 py-3 rounded-xl text-sm font-semibold flex items-center justify-between shadow-sm">
                            <span>{{ session('error') }}</span>
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
                                <p class="text-xs text-[#6B7280] mt-0.5">Komunikasi dan catatan penanganan terkait laporan ini.</p>

                                <div class="mt-5 space-y-4">
                                    @foreach ($riwayats as $r)
                                        <div class="flex items-start gap-3.5 pb-4 border-b border-[#F3F4F6] last:border-b-0 last:pb-0">
                                            <div class="w-9 h-9 rounded-xl bg-[#FCE8E8] text-[#B01116] font-bold text-xs flex items-center justify-center shrink-0">
                                                {{ $r['inisial'] }}
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex flex-wrap items-center justify-between gap-2">
                                                    <div class="flex flex-wrap items-center gap-2">
                                                        <span class="text-sm font-bold text-[#252525]">{{ $r['nama'] }}</span>
                                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-[#F1F5F9] text-[#475569]">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-[#64748B]"></span>
                                                            <span>{{ $r['role'] }}</span>
                                                        </span>
                                                    </div>
                                                    @if (!empty($r['waktu']))
                                                        <span class="text-[11px] text-[#6B7280]">{{ $r['waktu'] }}</span>
                                                    @endif
                                                </div>
                                                <p class="text-sm text-[#4B5563] mt-1 leading-relaxed">
                                                    {{ $r['pesan'] }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                @if (in_array($laporan['status'], ['Diteruskan', 'Diproses'], true))
                                    <!-- Form Kirim Tanggapan Terpisah (Tanpa Mengubah Status/Progres) -->
                                    <form
                                        method="POST"
                                        action="{{ route('unit.laporan-ditugaskan.update', $laporan['tiket']) }}"
                                        class="mt-5 pt-4 border-t border-[#E5E7EB]"
                                    >
                                        @csrf
                                        <input type="hidden" name="action_type" value="tanggapan">
                                        <label for="pesan_tanggapan" class="block text-xs font-bold text-[#252525] mb-1.5">
                                            Tulis Tanggapan
                                        </label>
                                        <textarea
                                            id="pesan_tanggapan"
                                            name="catatan"
                                            rows="3"
                                            required
                                            placeholder="Tulis pesan atau tanggapan untuk pelapor / Admin SSC tanpa mengubah status..."
                                            class="w-full bg-[#FFFFFF] border border-[#E5E7EB] rounded-xl py-2.5 px-3.5 text-sm text-[#252525] placeholder-[#9CA3AF] focus:border-[#B01116] focus:outline-none focus:ring-1 focus:ring-[#B01116] transition resize-none"
                                        ></textarea>
                                        <div class="mt-3 flex items-center justify-between gap-3">
                                            <p class="text-[11px] text-[#6B7280]">
                                                Tanggapan akan dikirim sebagai {{ $authUser->name ?? 'Petugas Unit' }} (Petugas Unit).
                                            </p>
                                            <button
                                                type="submit"
                                                class="px-4 py-2 rounded-xl bg-[#B01116] hover:bg-[#8C0D11] text-[#FFFFFF] text-xs sm:text-sm font-bold shadow-sm transition cursor-pointer shrink-0"
                                            >
                                                Kirim Tanggapan
                                            </button>
                                        </div>
                                    </form>
                                @endif
                            </div>

                        </div>

                        <!-- ================================================= -->
                        <!-- KOLOM KANAN: PEMBARUAN PENANGANAN / STATUS TERKUNCI -->
                        <!-- ================================================= -->
                        <div class="lg:col-span-4 space-y-6">
                            @if (!in_array($laporan['status'], ['Diteruskan', 'Diproses'], true))
                                @if ($laporan['status'] === 'Selesai')
                                    <!-- Kartu Informasi Penanganan Selesai -->
                                    <div class="bg-[#FFFFFF] rounded-2xl p-6 shadow-xl border border-[#E5E7EB] space-y-4">
                                        <div class="flex items-start gap-3.5">
                                            <div class="w-10 h-10 rounded-xl bg-[#ECFDF5] text-[#16A34A] flex items-center justify-center shrink-0">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <h3 class="text-base font-bold text-[#252525]">Penanganan Selesai</h3>
                                                <p class="text-xs text-[#6B7280] mt-0.5">
                                                    Laporan ini telah selesai ditangani dan diarsipkan.
                                                </p>
                                            </div>
                                        </div>

                                        <div class="bg-[#F9FAFB] rounded-xl p-4 border border-[#F3F4F6] space-y-2.5">
                                            <div class="flex items-center justify-between text-xs">
                                                <span class="font-semibold text-[#6B7280]">Progres Akhir</span>
                                                <span class="font-extrabold text-[#16A34A]">100%</span>
                                            </div>
                                            <div class="w-full h-1.5 bg-[#E5E7EB] rounded-full overflow-hidden">
                                                <div class="h-full bg-[#16A34A] rounded-full w-full"></div>
                                            </div>
                                            <div class="pt-1 flex items-center justify-between text-xs">
                                                <span class="font-semibold text-[#6B7280]">Diselesaikan pada</span>
                                                <span class="font-bold text-[#252525]">{{ $laporan['selesai_pada'] ?? '-' }}</span>
                                            </div>
                                        </div>

                                        <a
                                            href="{{ route('petugas.riwayat') }}"
                                            class="block w-full py-2.5 px-4 rounded-xl bg-[#B01116] hover:bg-[#8C0D11] text-[#FFFFFF] text-xs sm:text-sm font-bold text-center shadow-sm transition"
                                        >
                                            Lihat di Riwayat Penanganan
                                        </a>
                                    </div>
                                @else
                                    <!-- Kartu Informasi Dikembalikan ke Admin SSC -->
                                    <div class="bg-[#FFFFFF] rounded-2xl p-6 shadow-xl border border-[#E5E7EB] space-y-4">
                                        <div class="flex items-start gap-3.5">
                                            <div class="w-10 h-10 rounded-xl bg-[#FEF3C7] text-[#D97706] flex items-center justify-center shrink-0">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <h3 class="text-base font-bold text-[#252525]">Dikembalikan ke Admin SSC</h3>
                                                <p class="text-xs text-[#6B7280] mt-0.5">
                                                    Laporan ini sedang menunggu verifikasi ulang unit penangan oleh Admin SSC dan tidak dapat diperbarui oleh unit.
                                                </p>
                                            </div>
                                        </div>

                                        <div class="bg-[#F9FAFB] rounded-xl p-4 border border-[#F3F4F6] space-y-2.5">
                                            <div class="flex items-center justify-between text-xs">
                                                <span class="font-semibold text-[#6B7280]">Status Saat Ini</span>
                                                <span class="font-extrabold text-[#D97706]">{{ $laporan['status'] }}</span>
                                            </div>
                                            <div class="pt-1 flex items-center justify-between text-xs">
                                                <span class="font-semibold text-[#6B7280]">Dikembalikan pada</span>
                                                <span class="font-bold text-[#252525]">{{ $laporan['selesai_pada'] ?? '-' }}</span>
                                            </div>
                                        </div>

                                        <a
                                            href="{{ route('unit.laporan-ditugaskan') }}"
                                            class="block w-full py-2.5 px-4 rounded-xl bg-[#B01116] hover:bg-[#8C0D11] text-[#FFFFFF] text-xs sm:text-sm font-bold text-center shadow-sm transition"
                                        >
                                            Kembali ke Laporan Ditugaskan
                                        </a>
                                    </div>
                                @endif

                                <!-- Kartu Riwayat Status -->
                                <div class="bg-[#FFFFFF] rounded-2xl p-6 shadow-xl border border-[#E5E7EB]">
                                    <h3 class="text-base font-bold text-[#252525] mb-5">Riwayat Status</h3>

                                    <div class="space-y-6 relative">
                                        @forelse ($trackings ?? [] as $index => $track)
                                            <div class="flex items-start gap-3.5 relative">
                                                @if ($index === 0 && $laporan['status'] !== 'Selesai')
                                                    <div class="w-6 h-6 rounded-full border-2 border-[#B01116] flex items-center justify-center bg-white shrink-0 mt-0.5 z-10">
                                                        <span class="w-2 h-2 rounded-full bg-[#B01116]"></span>
                                                    </div>
                                                @else
                                                    <div class="w-6 h-6 rounded-full bg-[#DCFCE7] text-[#16A34A] flex items-center justify-center shrink-0 mt-0.5 z-10">
                                                        <svg class="w-3.5 h-3.5 text-[#16A34A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                                            <polyline points="20 6 9 17 4 12"/>
                                                        </svg>
                                                    </div>
                                                @endif

                                                @if (!$loop->last)
                                                    <div class="absolute left-3 top-6 w-0.5 bg-[#E5E7EB] h-14 -translate-x-1/2"></div>
                                                @endif

                                                <div>
                                                    <h4 class="text-sm font-bold text-[#252525]">{{ $track->status }}</h4>
                                                    <p class="text-[11px] text-[#6B7280] mt-0.5">{{ $track->formatted_waktu }}</p>
                                                    @if ($track->keterangan)
                                                        <p class="text-xs text-[#6B7280] mt-1 leading-snug">
                                                            {{ $track->keterangan }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        @empty
                                            <p class="text-xs text-[#6B7280]">Belum ada riwayat status.</p>
                                        @endforelse
                                    </div>
                                </div>

                                @if (!empty($laporan['rating']))
                                    <div class="bg-[#FFFFFF] rounded-2xl p-6 shadow-xl border border-[#E5E7EB] space-y-3">
                                        <h3 class="text-sm font-bold text-[#252525]">Penilaian Layanan Pelapor</h3>
                                        <div class="flex items-center text-amber-400">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= $laporan['rating'] ? 'fill-current' : 'text-gray-300 fill-current' }}" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            @endfor
                                            <span class="ml-1.5 text-xs font-bold text-[#252525]">({{ $laporan['rating'] }}/5)</span>
                                        </div>
                                        @if (!empty($laporan['ulasan']))
                                            <p class="text-xs text-[#252525] italic bg-[#F9FAFB] p-3 rounded-xl border border-[#F3F4F6]">
                                                "{{ $laporan['ulasan'] }}"
                                            </p>
                                        @endif
                                    </div>
                                @endif
                            @else
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
                                            @change="syncProgresWithStatus()"
                                            class="w-full bg-[#FFFFFF] border border-[#E5E7EB] rounded-xl py-2.5 px-3.5 text-sm font-medium text-[#252525] focus:border-[#B01116] focus:outline-none focus:ring-1 focus:ring-[#B01116] transition cursor-pointer"
                                        >
                                            <option value="Diteruskan">Diteruskan</option>
                                            <option value="Diproses">Diproses</option>
                                            <option value="Selesai">Selesai</option>
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
                                            x-model.number="progres"
                                            @input="syncStatusWithProgres()"
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
                                    <div class="pt-1">
                                        <button
                                            type="submit"
                                            @click="actionType = 'simpan'"
                                            class="w-full py-2.5 px-4 rounded-xl bg-[#B01116] hover:bg-[#8C0D11] text-[#FFFFFF] text-sm font-bold shadow-sm transition cursor-pointer"
                                        >
                                            Simpan Pembaruan
                                        </button>
                                    </div>

                                    <div class="border-t border-[#E5E7EB] pt-4">
                                        <button
                                            type="submit"
                                            @click="actionType = 'kembalikan'"
                                            class="w-full py-2.5 px-4 rounded-xl bg-[#FCE8E8] hover:bg-[#F8D1D1] text-[#B01116] text-sm font-bold transition cursor-pointer"
                                        >
                                            Kembalikan ke SSC
                                        </button>
                                    </div>
                                </form>
                            </div>
                            @endif
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

