@php
    $activeMenu = $activeMenu ?? '';
    $unreadCount = $unreadCount ?? 0;
    $alpineUnread = $alpineUnread ?? false;

    $navLinkClass = function (bool $isActive): string {
        return $isActive
            ? 'flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold bg-[#FCE8E8] text-[#B01116] transition shadow-xs'
            : 'flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#252525] hover:bg-gray-100 hover:text-[#B01116] transition group';
    };

    $iconClass = function (bool $isActive): string {
        return $isActive
            ? 'w-5 h-5 text-[#B01116] transition shrink-0'
            : 'w-5 h-5 text-[#6B7280] group-hover:text-[#B01116] transition shrink-0';
    };
@endphp

<!-- ========================================================= -->
<!-- SIDEBAR KIRI (PETUGAS UNIT) -->
<!-- ========================================================= -->
<aside
    class="fixed inset-y-0 left-0 z-50 w-64 bg-[#FFFFFF] flex flex-col justify-between border-r border-[#E5E7EB] transition-transform duration-200 lg:static lg:translate-x-0 shrink-0"
    :class="mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
>
    <div class="flex flex-col flex-1 overflow-y-auto">
        <!-- Logo -->
        <div class="px-6 py-5 flex items-center gap-3">
            <img src="{{ asset('images/logo-campusvoice.png') }}" alt="CampusVoice Logo" class="w-8 h-8 object-contain shrink-0" onerror="this.onerror=null; this.src='https://placehold.co/32x32/B01116/white?text=CV';" />
            <div>
                <span class="text-[17px] font-extrabold text-[#B01116] tracking-tight block leading-tight">CampusVoice</span>
                <p class="text-[10px] text-[#6B7280] font-medium leading-tight mt-0.5">Telkom University Surabaya</p>
            </div>
        </div>

        <!-- Navigasi Menu Petugas Unit -->
        <div class="px-4 py-3 flex-1">
            <p class="px-3 text-[11px] font-bold text-[#252525] tracking-wider mb-2 uppercase">Petugas Unit</p>

            <nav class="space-y-1">
                <!-- Menu 1: Dashboard Unit -->
                <a
                    href="{{ route('unit.dashboard') }}"
                    @if ($activeMenu === 'dashboard') aria-current="page" @endif
                    class="{{ $navLinkClass($activeMenu === 'dashboard') }}"
                >
                    <svg class="{{ $iconClass($activeMenu === 'dashboard') }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="{{ $activeMenu === 'dashboard' ? '2.2' : '2' }}" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                    <span>Dashboard Unit</span>
                </a>

                <!-- Menu 2: Laporan Ditugaskan -->
                <a
                    href="{{ route('unit.laporan-ditugaskan') }}"
                    @if ($activeMenu === 'ditugaskan') aria-current="page" @endif
                    class="{{ $navLinkClass($activeMenu === 'ditugaskan') }}"
                >
                    <svg class="{{ $iconClass($activeMenu === 'ditugaskan') }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="{{ $activeMenu === 'ditugaskan' ? '2.2' : '2' }}" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                    </svg>
                    <span>Laporan Ditugaskan</span>
                </a>

                <!-- Menu 3: Riwayat Penanganan -->
                <a
                    href="{{ route('petugas.riwayat') }}"
                    @if ($activeMenu === 'riwayat') aria-current="page" @endif
                    class="{{ $navLinkClass($activeMenu === 'riwayat') }}"
                >
                    <svg class="{{ $iconClass($activeMenu === 'riwayat') }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="{{ $activeMenu === 'riwayat' ? '2.2' : '2' }}" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="20" x2="12" y2="10"/>
                        <line x1="18" y1="20" x2="18" y2="4"/>
                        <line x1="6" y1="20" x2="6" y2="16"/>
                    </svg>
                    <span>Riwayat Penanganan</span>
                </a>

                <!-- Menu 4: Notifikasi -->
                <a
                    href="{{ route('unit.notifikasi') }}"
                    @if ($activeMenu === 'notifikasi') aria-current="page" @endif
                    class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm {{ $activeMenu === 'notifikasi' ? 'font-bold bg-[#FCE8E8] text-[#B01116] shadow-xs' : 'font-semibold text-[#252525] hover:bg-gray-100 hover:text-[#B01116]' }} transition group"
                >
                    <div class="flex items-center gap-3">
                        <svg class="{{ $iconClass($activeMenu === 'notifikasi') }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="{{ $activeMenu === 'notifikasi' ? '2.2' : '2' }}" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                        </svg>
                        <span>Notifikasi</span>
                    </div>
                    @if ($alpineUnread)
                        <span
                            x-show="unreadCount > 0"
                            x-text="unreadCount"
                            class="w-5 h-5 rounded-full bg-[#B01116] text-[#FFFFFF] text-[11px] font-bold flex items-center justify-center shrink-0"
                        >
                            {{ $unreadCount }}
                        </span>
                    @elseif ($unreadCount > 0)
                        <span class="w-5 h-5 rounded-full bg-[#B01116] text-[#FFFFFF] text-[11px] font-bold flex items-center justify-center shrink-0">
                            {{ $unreadCount }}
                        </span>
                    @endif
                </a>

                <!-- Menu 5: Profil Unit -->
                <a
                    href="{{ route('unit.profil') }}"
                    @if ($activeMenu === 'profil') aria-current="page" @endif
                    class="{{ $navLinkClass($activeMenu === 'profil') }}"
                >
                    <svg class="{{ $iconClass($activeMenu === 'profil') }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="{{ $activeMenu === 'profil' ? '2.2' : '2' }}" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    <span>Profil Unit</span>
                </a>
            </nav>
        </div>

        <!-- Bagian Bawah: Keluar -->
        <div class="px-4 py-3 border-t border-[#E5E7EB]">
            @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                        type="submit"
                        class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#B01116] hover:bg-red-50 transition cursor-pointer text-left"
                    >
                        <svg class="w-5 h-5 text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                            <polyline points="16 17 21 12 16 7"/>
                            <line x1="21" y1="12" x2="9" y2="12"/>
                        </svg>
                        <span>Keluar</span>
                    </button>
                </form>
            @else
                <a
                    href="{{ route('login') }}"
                    class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#B01116] hover:bg-red-50 transition text-left"
                >
                    <svg class="w-5 h-5 text-[#B01116] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                        <polyline points="10 17 15 12 10 7"/>
                        <line x1="15" y1="12" x2="3" y2="12"/>
                    </svg>
                    <span>Masuk Akun</span>
                </a>
            @endauth
        </div>
    </div>

    <!-- Kartu Bantuan -->
    <div class="p-4 border-t border-[#E5E7EB]">
        <div class="rounded-xl bg-[#B01116] p-4 text-[#FFFFFF] shadow-sm">
            <h4 class="text-sm font-bold tracking-tight text-[#FFFFFF]">Butuh bantuan?</h4>
            <p class="text-[11px] text-[#FFFFFF]/85 mt-1 leading-snug">Hubungi pusat layanan CampusVoice.</p>
            <a href="{{ route('pusat.bantuan') }}" class="mt-3 block w-full py-1.5 px-3 bg-[#FFFFFF] hover:bg-gray-100 text-[#B01116] text-xs font-bold rounded-lg text-center shadow-xs transition">
                Pusat Bantuan
            </a>
        </div>
    </div>
</aside>

<!-- Overlay Mobile -->
<div x-show="mobileSidebarOpen" x-cloak @click="mobileSidebarOpen = false" class="fixed inset-0 z-40 bg-black/40 backdrop-blur-xs lg:hidden"></div>
