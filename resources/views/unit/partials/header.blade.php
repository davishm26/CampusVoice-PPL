@php
    $unitUser = $authUser ?? Auth::user();
    if (!$unitUser || $unitUser->role !== 'Petugas Unit') {
        $unitUser = \App\Models\User::where('role', 'Petugas Unit')->first() ?? $unitUser;
    }

    $headerName = $unitUser?->name ?: 'Budi Santoso';
    $headerRole = $unitUser?->role ?: 'Petugas Unit';
    $headerInitials = $unitUser?->initials ?: 'PU';
    $unreadCount = $unreadCount ?? 0;
    $alpineUnread = $alpineUnread ?? false;
@endphp

<!-- HEADER ATAS (NAVBAR PETUGAS UNIT) -->
<header class="bg-[#FFFFFF] border-b border-[#E5E7EB] px-4 sm:px-8 py-3.5 flex items-center justify-between sticky top-0 z-30 shadow-xs">
    <!-- Hamburger Mobile & Search (Dashboard Only) -->
    <div class="flex items-center gap-3">
        <button
            type="button"
            @click="mobileSidebarOpen = !mobileSidebarOpen"
            class="p-2 -ml-2 rounded-lg text-[#6B7280] hover:text-[#252525] hover:bg-gray-100 lg:hidden transition"
            title="Buka Menu"
            aria-label="Buka Menu"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        @if (!empty($searchModel))
            <div class="relative w-48 sm:w-80 md:w-96">
                <input
                    type="text"
                    x-model="{{ $searchModel }}"
                    placeholder="Cari nomor tiket atau laporan..."
                    class="w-full bg-[#FFFFFF] border border-[#E5E7EB] rounded-lg py-2 px-3.5 text-xs sm:text-sm text-[#252525] placeholder-[#6B7280] focus:border-[#B01116] focus:outline-none focus:ring-1 focus:ring-[#B01116] transition"
                />
            </div>
        @endif
    </div>

    <!-- Bagian Kanan: Lonceng Notifikasi & Profil Petugas Unit -->
    <div class="flex items-center gap-3 sm:gap-4">
        <a
            href="{{ route('unit.notifikasi') }}"
            class="relative w-9 h-9 rounded-xl border border-[#E5E7EB] flex items-center justify-center text-[#6B7280] hover:text-[#B01116] hover:bg-gray-50 transition"
            title="Notifikasi"
            aria-label="Notifikasi"
        >
            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            @if ($alpineUnread)
                <span
                    x-show="unreadCount > 0"
                    x-text="unreadCount"
                    class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-[#B01116] text-[#FFFFFF] text-[10px] font-bold flex items-center justify-center"
                >
                    {{ $unreadCount }}
                </span>
            @elseif ($unreadCount > 0)
                <span class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-[#B01116] text-[#FFFFFF] text-[10px] font-bold flex items-center justify-center">
                    {{ $unreadCount }}
                </span>
            @endif
        </a>

        <a href="{{ route('unit.profil') }}" class="flex items-center gap-2.5 group">
            <div class="w-9 h-9 rounded-xl bg-[#FCE8E8] text-[#B01116] font-bold text-xs flex items-center justify-center shrink-0 group-hover:ring-2 group-hover:ring-[#B01116]/25 transition">
                {{ $headerInitials }}
            </div>
            <div class="hidden sm:block text-left">
                <p class="text-xs font-bold text-[#252525] leading-tight group-hover:text-[#B01116] transition">
                    {{ $headerName }}
                </p>
                <p class="text-[11px] text-[#6B7280] leading-tight">
                    {{ $headerRole }}
                </p>
            </div>
        </a>
    </div>
</header>
