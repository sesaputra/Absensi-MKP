
<aside class="hidden w-64 shrink-0 flex-col border-r border-white/[0.06] bg-[#0F172A] text-slate-300 md:flex">

    {{-- LOGO AREA --}}
    <div class="px-5 pb-6 pt-3">
        <div class="flex items-center justify-center rounded-2xl border border-white/[0.08] bg-white px-4 py-4 shadow-lg shadow-black/10">
            <img
                src="{{ asset('images/logo-mkp.png') }}"
                alt="Logo MKP"
                class="h-20 w-full max-w-[190px] object-contain"
            >
        </div>

        <div class="mt-5 px-1">
            <!-- <p class="text-[10px] font-semibold uppercase tracking-[0.22em] text-slate-500">
                Workspace
            </p> -->

            <h2 class="mt-1.5 text-sm font-semibold tracking-wide text-white">
                Sistem Administrasi
            </h2>

            <p class="mt-1 text-xs text-slate-500">
                Kegiatan Proyek MKP
            </p>
        </div>
    </div>

    {{-- MENU NAVIGASI --}}
    <nav class="flex-1 space-y-1 overflow-y-auto px-3 pb-5">

        <p class="mb-3 px-3 pt-1 text-[10px] font-semibold uppercase tracking-[0.18em] text-slate-500">
            Menu Utama
        </p>

        {{-- DASHBOARD --}}
        <a
            href="{{ url('/dashboard') }}"
            @if(request()->is('dashboard'))
                aria-current="page"
            @endif
            class="group relative flex items-center gap-3 overflow-hidden rounded-xl px-3.5 py-3 text-[13px] font-medium transition-all duration-200
            {{ request()->is('dashboard')
                ? 'bg-white/[0.09] text-white shadow-sm ring-1 ring-inset ring-white/[0.06]'
                : 'text-slate-400 hover:bg-white/[0.045] hover:text-slate-100' }}"
        >
            @if(request()->is('dashboard'))
                <span class="absolute bottom-2.5 left-0 top-2.5 w-[3px] rounded-r-full bg-amber-400"></span>
            @endif

            <svg
                class="h-[19px] w-[19px] shrink-0 transition-colors duration-200
                {{ request()->is('dashboard')
                    ? 'text-amber-400'
                    : 'text-slate-500 group-hover:text-slate-300' }}"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                aria-hidden="true"
            >
                <rect x="3.5" y="3.5" width="7" height="7" rx="1.5" stroke-width="1.7"/>
                <rect x="13.5" y="3.5" width="7" height="7" rx="1.5" stroke-width="1.7"/>
                <rect x="3.5" y="13.5" width="7" height="7" rx="1.5" stroke-width="1.7"/>
                <rect x="13.5" y="13.5" width="7" height="7" rx="1.5" stroke-width="1.7"/>
            </svg>

            <span>Dashboard</span>

            @if(request()->is('dashboard'))
                <span class="ml-auto h-1.5 w-1.5 rounded-full bg-amber-400"></span>
            @endif
        </a>

        {{-- MANAJEMEN PEGAWAI --}}
        <a
            href="{{ url('/manajemen-pegawai') }}"
            @if(request()->is('manajemen-pegawai*'))
                aria-current="page"
            @endif
            class="group relative flex items-center gap-3 overflow-hidden rounded-xl px-3.5 py-3 text-[13px] font-medium transition-all duration-200
            {{ request()->is('manajemen-pegawai*')
                ? 'bg-white/[0.09] text-white shadow-sm ring-1 ring-inset ring-white/[0.06]'
                : 'text-slate-400 hover:bg-white/[0.045] hover:text-slate-100' }}"
        >
            @if(request()->is('manajemen-pegawai*'))
                <span class="absolute bottom-2.5 left-0 top-2.5 w-[3px] rounded-r-full bg-amber-400"></span>
            @endif

            <svg
                class="h-[19px] w-[19px] shrink-0 transition-colors duration-200
                {{ request()->is('manajemen-pegawai*')
                    ? 'text-amber-400'
                    : 'text-slate-500 group-hover:text-slate-300' }}"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                aria-hidden="true"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.7"
                    d="M16 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2m12-13a4 4 0 11-8 0 4 4 0 018 0zm2 3a4 4 0 010 7m4 3v-2a4 4 0 00-3-3.87"
                />
            </svg>

            <span>Manajemen Pegawai</span>

            @if(request()->is('manajemen-pegawai*'))
                <span class="ml-auto h-1.5 w-1.5 rounded-full bg-amber-400"></span>
            @endif
        </a>

        {{-- MANAJEMEN ABSENSI --}}
        <a
            href="{{ url('/manajemen-absensi') }}"
            @if(request()->is('manajemen-absensi*'))
                aria-current="page"
            @endif
            class="group relative flex items-center gap-3 overflow-hidden rounded-xl px-3.5 py-3 text-[13px] font-medium transition-all duration-200
            {{ request()->is('manajemen-absensi*')
                ? 'bg-white/[0.09] text-white shadow-sm ring-1 ring-inset ring-white/[0.06]'
                : 'text-slate-400 hover:bg-white/[0.045] hover:text-slate-100' }}"
        >
            @if(request()->is('manajemen-absensi*'))
                <span class="absolute bottom-2.5 left-0 top-2.5 w-[3px] rounded-r-full bg-amber-400"></span>
            @endif

            <svg
                class="h-[19px] w-[19px] shrink-0 transition-colors duration-200
                {{ request()->is('manajemen-absensi*')
                    ? 'text-amber-400'
                    : 'text-slate-500 group-hover:text-slate-300' }}"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                aria-hidden="true"
            >
                <rect x="4" y="5" width="16" height="16" rx="2" stroke-width="1.7"/>
                <path d="M8 3v4m8-4v4M4 10h16m-11 5 2 2 4-4" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"/>
            </svg>

            <span>Manajemen Absensi</span>

            @if(request()->is('manajemen-absensi*'))
                <span class="ml-auto h-1.5 w-1.5 rounded-full bg-amber-400"></span>
            @endif
        </a>

        {{-- MANAJEMEN PROYEK --}}
        <a
            href="{{ route('proyek.index') }}"
            @if(request()->is('manajemen-proyek*', 'proyek', 'proyek/*', 'admin/proyek/*'))
                aria-current="page"
            @endif
            class="group relative flex items-center gap-3 overflow-hidden rounded-xl px-3.5 py-3 text-[13px] font-medium transition-all duration-200
            {{ request()->is('manajemen-proyek*', 'proyek', 'proyek/*', 'admin/proyek/*')
                ? 'bg-white/[0.09] text-white shadow-sm ring-1 ring-inset ring-white/[0.06]'
                : 'text-slate-400 hover:bg-white/[0.045] hover:text-slate-100' }}"
        >
            @if(request()->is('manajemen-proyek*', 'proyek', 'proyek/*', 'admin/proyek/*'))
                <span class="absolute bottom-2.5 left-0 top-2.5 w-[3px] rounded-r-full bg-amber-400"></span>
            @endif

            <svg
                class="h-[19px] w-[19px] shrink-0 transition-colors duration-200
                {{ request()->is('manajemen-proyek*', 'proyek', 'proyek/*', 'admin/proyek/*')
                    ? 'text-amber-400'
                    : 'text-slate-500 group-hover:text-slate-300' }}"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                aria-hidden="true"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.7"
                    d="M3 21h18M5 21V5a2 2 0 012-2h10a2 2 0 012 2v16M9 7h2m2 0h2M9 11h2m2 0h2M9 15h2m2 0h2M10 21v-3a2 2 0 014 0v3"
                />
            </svg>

            <span>Manajemen Proyek</span>

            @if(request()->is('manajemen-proyek*', 'proyek', 'proyek/*', 'admin/proyek/*'))
                <span class="ml-auto h-1.5 w-1.5 rounded-full bg-amber-400"></span>
            @endif
        </a>

    </nav>

    {{-- FOOTER --}}
    <div class="mt-auto border-t border-white/[0.07] px-5 py-5">
        <div class="flex items-center gap-3">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-amber-400/15 bg-amber-400/[0.08]">
                <svg
                    class="h-4 w-4 text-amber-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    aria-hidden="true"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.7"
                        d="M12 3l8 4v5c0 5-3.5 8-8 9-4.5-1-8-4-8-9V7l8-4z"
                    />
                    <path d="M9 12l2 2 4-4" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"/>
                </svg>
            </div>

            <div class="min-w-0">
                <p class="text-xs font-semibold tracking-wide text-slate-200">
                    SAKP
                </p>

                <p class="mt-1 text-[10px] leading-4 text-slate-500">
                    Sistem Administrasi Kegiatan Proyek
                </p>
            </div>
        </div>

        <div class="mt-4 flex items-center justify-between border-t border-white/[0.06] pt-3">
            <span class="text-[10px] text-slate-500">
                &copy; {{ now()->year }} MKP
            </span>

            <span class="inline-flex items-center gap-1.5 text-[10px] text-slate-500">
                <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                Sistem Aktif
            </span>
        </div>
    </div>

</aside>