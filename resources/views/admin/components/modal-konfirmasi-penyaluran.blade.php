<!-- Modal Pop-up: Konfirmasi Penyaluran (Admin SSC) -->
<div
    x-show="showConfirmModal"
    x-cloak
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs overscroll-contain"
    role="dialog"
    aria-modal="true"
    aria-labelledby="modal-konfirmasi-title"
    @keydown.escape.window="if (!isSubmittingForward) showConfirmModal = false"
>
    <div
        @click.away="if (!isSubmittingForward) showConfirmModal = false"
        x-show="showConfirmModal"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-2"
        class="bg-[#FFFFFF] rounded-2xl max-w-md w-full p-6 shadow-2xl border border-[#E5E7EB]"
        style="font-family: 'Plus Jakarta Sans', sans-serif;"
    >
        <!-- Header Modal: Ikon Perisai Centang (Kiri) & Tombol Tutup X (Kanan) -->
        <div class="flex items-start justify-between">
            <div class="w-10 h-10 rounded-xl bg-[#FCE8E8] text-[#B01116] flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    <path d="m9 12 2 2 4-4"/>
                </svg>
            </div>

            <button
                type="button"
                :disabled="isSubmittingForward"
                @click="showConfirmModal = false"
                class="p-1.5 -mr-1.5 -mt-1.5 rounded-lg text-[#B01116] hover:bg-[#FCE8E8]/60 disabled:opacity-50 transition cursor-pointer"
                title="Tutup"
                aria-label="Tutup Modal"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Konten Teks -->
        <div class="mt-4">
            <h3 id="modal-konfirmasi-title" class="text-lg font-extrabold text-[#252525] tracking-tight leading-snug">
                Konfirmasi Penyaluran
            </h3>
            <p class="text-xs sm:text-sm text-[#6B7280] mt-1.5 leading-relaxed">
                Pastikan unit tujuan sudah sesuai. Setelah diteruskan, laporan akan muncul pada dashboard petugas unit yang dipilih.
            </p>
        </div>

        <!-- Kotak Info Tujuan Penyaluran (Review Info) -->
        <div class="mt-5 rounded-xl bg-[#FFFFFF] border border-[#E5E7EB] p-4">
            <p class="text-[10px] font-bold uppercase tracking-wider text-[#6B7280]">
                UNIT TUJUAN
            </p>
            <p
                class="text-sm font-bold text-[#252525] mt-1"
                x-text="unitPenangan || @js($laporan['rekomendasi_unit'])"
            ></p>
            <div class="mt-2.5">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-[#F3F4F6] text-[11px] font-semibold text-[#B01116]">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#2563EB]"></span>
                    <span x-text="'Prioritas ' + (prioritas || 'Tinggi')"></span>
                </span>
            </div>
        </div>

        <!-- Footer Modal (Tombol Aksi Kanan Bawah) -->
        <div class="mt-6 flex items-center justify-end gap-2.5">
            <button
                type="button"
                :disabled="isSubmittingForward"
                @click="showConfirmModal = false"
                class="px-4 py-2.5 rounded-xl bg-[#FFFFFF] border border-[#E5E7EB] text-xs sm:text-sm font-bold text-[#252525] hover:bg-gray-50 disabled:opacity-50 transition cursor-pointer"
            >
                Periksa Kembali
            </button>
            <button
                type="button"
                :disabled="isSubmittingForward"
                @click="confirmPenyaluran()"
                class="px-5 py-2.5 rounded-xl bg-[#B01116] hover:bg-[#970e13] disabled:opacity-75 text-xs sm:text-sm font-bold text-[#FFFFFF] shadow-sm transition cursor-pointer inline-flex items-center gap-2"
            >
                <svg x-show="isSubmittingForward" x-cloak class="w-4 h-4 animate-spin text-[#FFFFFF]" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                <span x-text="isSubmittingForward ? 'Meneruskan...' : 'Ya, Teruskan'"></span>
            </button>
        </div>
    </div>
</div>

