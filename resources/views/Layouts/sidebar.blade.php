<aside class="hidden w-64 flex-col bg-slate-950 text-white md:flex">

    {{-- =========================================================
        LOGO AREA
    ========================================================== --}}
    <div
        class="mb-2 flex items-center justify-center border-b border-white/10 px-4 py-6"
    >

        <img
            src="{{ asset('images/logo-mkp.png') }}"
            alt="Logo MKP"
            class="h-24 w-35 max-w-[85%] rounded-xl bg-white object-contain p-2 shadow-md"
        >

    </div>


    {{-- =========================================================
        MENU NAVIGASI
    ========================================================== --}}
    <nav class="flex-1 space-y-1.5 overflow-y-auto px-3 py-5">

        {{-- =====================================================
            DASHBOARD
        ====================================================== --}}
        <a
            href="{{ url('/dashboard') }}"
            class="group flex items-center rounded-xl px-4 py-3 transition-all duration-200
            {{ request()->is('dashboard')
                ? 'bg-white/[0.08] text-white shadow-sm'
                : 'text-slate-400 hover:bg-white/[0.05] hover:text-slate-100' }}"
        >

            <svg
                class="mr-3 h-5 w-5 shrink-0
                {{ request()->is('dashboard')
                    ? 'text-emerald-400'
                    : 'text-slate-500 group-hover:text-slate-300' }}"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                aria-hidden="true"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"
                />
            </svg>

            <span class="font-medium">
                Dashboard
            </span>

        </a>


        {{-- =====================================================
            MANAJEMEN PEGAWAI
        ====================================================== --}}
        <a
            href="{{ url('/manajemen-pegawai') }}"
            class="group flex items-center rounded-xl px-4 py-3 transition-all duration-200
            {{ request()->is('manajemen-pegawai')
                ? 'bg-white/[0.08] text-white shadow-sm'
                : 'text-slate-400 hover:bg-white/[0.05] hover:text-slate-100' }}"
        >

            <svg
                class="mr-3 h-5 w-5 shrink-0
                {{ request()->is('manajemen-pegawai')
                    ? 'text-emerald-400'
                    : 'text-slate-500 group-hover:text-slate-300' }}"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                aria-hidden="true"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"
                />
            </svg>

            <span class="font-medium">
                Manajemen Pegawai
            </span>

        </a>


        {{-- =====================================================
            MANAJEMEN ABSENSI
        ====================================================== --}}
        <a
            href="{{ url('/manajemen-absensi') }}"
            class="group flex items-center rounded-xl px-4 py-3 transition-all duration-200
            {{ request()->is('manajemen-absensi')
                ? 'bg-white/[0.08] text-white shadow-sm'
                : 'text-slate-400 hover:bg-white/[0.05] hover:text-slate-100' }}"
        >

            <svg
                class="mr-3 h-5 w-5 shrink-0
                {{ request()->is('manajemen-absensi')
                    ? 'text-emerald-400'
                    : 'text-slate-500 group-hover:text-slate-300' }}"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                aria-hidden="true"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a3 3 0 006 0M9 14l2 2 4-4"
                />
            </svg>

            <span class="font-medium">
                Manajemen Absensi
            </span>

        </a>


        {{-- =====================================================
            MANAJEMEN PROYEK
        ====================================================== --}}
        <a
            href="{{ route('proyek.index') }}"
            class="group flex items-center rounded-xl px-4 py-3 transition-all duration-200
            {{ request()->is('manajemen-proyek*', 'proyek/*', 'admin/proyek/*')
                ? 'bg-white/[0.08] text-white shadow-sm'
                : 'text-slate-400 hover:bg-white/[0.05] hover:text-slate-100' }}"
        >

            <svg
                class="mr-3 h-5 w-5 shrink-0
                {{ request()->is('manajemen-proyek*', 'proyek/*', 'admin/proyek/*')
                    ? 'text-emerald-400'
                    : 'text-slate-500 group-hover:text-slate-300' }}"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                aria-hidden="true"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                />
            </svg>

            <span class="font-medium">
                Manajemen Proyek
            </span>

        </a>

    </nav>


    {{-- =========================================================
        SIDEBAR FOOTER
    ========================================================== --}}
    <div class="border-t border-white/10 px-4 py-4">

        <p class="text-center text-[11px] leading-5 text-slate-500">
            &copy; {{ now()->year }} SAKP
        </p>

        <p class="mt-0.5 text-center text-[10px] leading-4 text-slate-600">
            Sistem Administrasi Kegiatan Proyek
        </p>

    </div>

</aside>