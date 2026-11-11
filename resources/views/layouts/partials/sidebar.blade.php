@php
    $role = auth()->user()->role ?? null;
@endphp

<!-- Sidebar -->
<aside id="sidebar"
    class="fixed inset-y-0 left-0 z-40 w-64 bg-brandDark shadow-2xl
           transform -translate-x-full lg:translate-x-0
           transition-transform duration-300 flex flex-col">

    <!-- ================= BRAND ================= -->
    <div class="flex items-center gap-3 px-6 py-5 border-b border-black/10 shrink-0">
        <img src="{{ asset('assets/images/LOGOS.svg') }}" alt="Logo" class="h-10 w-10 object-contain">

        <div class="leading-tight">
            <h1 class="text-lg font-semibold tracking-wide">
                Admin Panel
            </h1>
            <p class="text-xs text-textmuted">
                Management System
            </p>
        </div>
    </div>

    <!-- ================= MENU ================= -->
    <nav class="flex-1 px-3 py-5 text-sm space-y-6 overflow-y-auto scrollbar-hide">

        <!-- ===== MAIN ===== -->
        <div>
            <p class="px-3 mb-2 text-xs font-semibold uppercase text-textmuted">
                Main
            </p>

            <a href="{{ route('dashboard') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium
               {{ request()->routeIs('dashboard') ? 'bg-surface shadow-sm text-accent' : 'hover:bg-surface' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 12l9-9 9 9M4 10v10a1 1 0 001 1h4m10-11v11a1 1 0 01-1 1h-4" />
                </svg>
                <span>Dashboard</span>
            </a>
        </div>

        <!-- ===== MASTER DATA ===== -->
        <div>
            <p class="px-3 mb-2 text-xs font-semibold uppercase text-textmuted">
                Master Data
            </p>

            {{-- KODE KRITERIA (ADMIN & DOSEN) --}}
            @if (in_array($role, ['admin', 'dosen']))
                <a href="{{ route('kode_kriteria.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg
                   {{ request()->routeIs('kode_kriteria.*') ? 'bg-surface text-accent' : 'hover:bg-surface' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h8M8 12h8M8 17h8" />
                    </svg>
                    <span>Kode Kriteria</span>
                </a>

                <a href="{{ route('kriteria.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg
                   {{ request()->routeIs('kriteria.*') ? 'bg-surface text-accent' : 'hover:bg-surface' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h6M9 16h6M9 8h6M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Kriteria</span>
                </a>
            @endif

            {{-- ALTERNATIF (SEMUA ROLE) --}}
            <a href="{{ route('alternatif.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg
               {{ request()->routeIs('alternatif.*') ? 'bg-surface text-accent' : 'hover:bg-surface' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5V4H2v16h5m10 0V10H7v10m10 0H7" />
                </svg>
                <span>Alternatif</span>
            </a>

            <a href="{{ route('alternatif-nilai.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg
               {{ request()->routeIs('alternatif-nilai.*') ? 'bg-surface text-accent' : 'hover:bg-surface' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18M3 17h18" />
                </svg>
                <span>Nilai Alternatif</span>
            </a>
        </div>

        <!-- ===== PROSES ===== -->
        <div>
            <p class="px-3 mb-2 text-xs font-semibold uppercase text-textmuted">
                Proses
            </p>

            <a href="{{ route('wawancara.create') }}"
   class="flex items-center gap-3 px-3 py-2.5 rounded-lg
   {{ request()->routeIs('wawancara.create') ? 'bg-surface text-accent' : 'hover:bg-surface' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M12 4v16m8-8H4m5-7h6a2 2 0 012 2v4h-4a2 2 0 00-2 2v4H9a2 2 0 01-2-2V7a2 2 0 012-2z" />
    </svg>
    <span>Formulir Wawancara</span>
</a>

<a href="{{ route('wawancara.index') }}"
   class="flex items-center gap-3 px-3 py-2.5 rounded-lg
   {{ request()->routeIs('wawancara.index') ? 'bg-surface text-accent' : 'hover:bg-surface' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M9 5h6m-7 4h8m-8 4h8M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
    </svg>
    <span>Daftar Wawancara</span>
</a>

            <a href="{{ route('perhitungan.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg
               {{ request()->routeIs('perhitungan.*') ? 'bg-surface text-accent' : 'hover:bg-surface' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m-8 4h8m-8 4h8M3 7h4v10H3z" />
                </svg>
                <span>Perhitungan</span>
            </a>

            <a href="{{ route('perhitungan-sessions.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg
               {{ request()->routeIs('perhitungan-sessions.*') ? 'bg-surface text-accent' : 'hover:bg-surface' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                </svg>
                <span>Riwayat Perhitungan</span>
            </a>
        </div>
    </nav>

    <!-- ================= FOOTER ================= -->
    <div class="px-6 py-4 border-t border-black/10 text-xs text-textmuted shrink-0">
        © 2026 Himtif
    </div>
</aside>
