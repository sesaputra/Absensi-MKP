<header
    id="admin-navbar"
    class="sticky top-0 z-50 flex h-16 items-center justify-between border-b border-slate-200/80 bg-white/95 px-4 backdrop-blur-xl transition-all duration-300 sm:px-6 lg:px-8"
>

    {{-- =========================================================
        LEFT : BREADCRUMB / PAGE CONTEXT
    ========================================================== --}}
    <div class="flex min-w-0 items-center">

        <nav
            aria-label="Breadcrumb"
            class="flex min-w-0 items-center text-sm"
        >

            @php
                $currentTitle = trim($__env->yieldContent('title'));
            @endphp


            {{-- PARENT TITLE --}}
            @if($currentTitle && strtolower($currentTitle) !== 'dashboard')

                <span
                    class="hidden font-medium text-slate-400 transition-colors hover:text-slate-600 sm:inline"
                >
                    <a href="{{ url('/dashboard') }}">
                        Dashboard
                    </a>
                </span>


                <svg
                    class="mx-2 hidden h-4 w-4 shrink-0 text-slate-300 sm:block"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    aria-hidden="true"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 5l7 7-7 7"
                    />
                </svg>

            @endif


            {{-- CURRENT PAGE TITLE --}}
            <span
                class="max-w-[200px] truncate font-semibold text-slate-800 sm:max-w-none"
            >
                {{ $currentTitle ?: 'Dashboard' }}
            </span>

        </nav>

    </div>


    {{-- =========================================================
        RIGHT : NOTIFICATION + USER INFO + DIRECT LOGOUT
    ========================================================== --}}
    <div class="ml-4 flex items-center gap-2 sm:gap-3">


        {{-- =====================================================
            NOTIFICATION BUTTON
        ====================================================== --}}
        <button
            type="button"
            aria-label="Notifikasi"
            class="relative flex h-9 w-9 items-center justify-center rounded-lg text-slate-500 transition-all duration-200 hover:bg-amber-50 hover:text-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-500"
        >

            <svg
                class="h-5 w-5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                aria-hidden="true"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                />
            </svg>


            {{-- NOTIFICATION DOT --}}
            <span class="absolute right-2 top-2 flex h-2 w-2">

                <span
                    class="absolute inline-flex h-full w-full animate-ping rounded-full bg-amber-400 opacity-75"
                ></span>

                <span
                    class="relative inline-flex h-2 w-2 rounded-full bg-amber-500"
                ></span>

            </span>

        </button>


        {{-- =====================================================
            SEPARATOR
        ====================================================== --}}
        <div class="mx-0.5 h-5 w-px bg-slate-200"></div>


        {{-- =====================================================
            USER PROFILE DATA
        ====================================================== --}}
        @php
            $name = auth()->user()->name ?? 'Administrator';

            $initials = collect(explode(' ', trim($name)))
                ->filter()
                ->take(2)
                ->map(fn ($word) => strtoupper(substr($word, 0, 1)))
                ->implode('');

            $initials = $initials ?: 'A';
        @endphp


        {{-- USER PROFILE --}}
        <div class="flex items-center gap-2.5 px-1 py-1">

            {{-- AVATAR --}}
            <div
                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full border border-slate-200 bg-slate-100 text-xs font-bold text-slate-600"
            >
                {{ $initials }}
            </div>


            {{-- NAME & ROLE --}}
            <div class="hidden min-w-0 text-left md:block">

                <p
                    class="max-w-[130px] truncate text-sm font-semibold text-slate-700"
                >
                    {{ $name }}
                </p>

                <p
                    class="max-w-[130px] truncate text-[11px] font-medium text-slate-400"
                >
                    {{ auth()->user()->role === 'super_admin'
                        ? 'Super Administrator'
                        : 'Admin Proyek' }}
                </p>

            </div>

        </div>


        {{-- =====================================================
            SEPARATOR
        ====================================================== --}}
        <div class="mx-0.5 h-5 w-px bg-slate-200"></div>


        {{-- =====================================================
            DIRECT LOGOUT
        ====================================================== --}}
        <form
            action="{{ url('/logout') }}"
            method="POST"
            class="flex items-center"
        >

            @csrf

            <button
                type="submit"
                aria-label="Keluar Sistem"
                title="Keluar Sistem"
                class="flex items-center gap-2 rounded-lg px-2.5 py-2 text-xs font-semibold text-rose-600 transition-colors hover:bg-rose-50 hover:text-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-500 sm:px-3"
            >

                <svg
                    class="h-4 w-4 shrink-0"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    aria-hidden="true"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                    />
                </svg>


                {{-- TEXT --}}
                <span class="hidden sm:inline">
                    Keluar
                </span>

            </button>

        </form>

    </div>

</header>


{{-- =========================================================
    NAVBAR SCROLL EFFECT
========================================================= --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const navbar = document.getElementById('admin-navbar');

        if (!navbar) {
            return;
        }

        const updateNavbar = () => {

            if (window.scrollY > 8) {

                navbar.classList.add(
                    'bg-white/90',
                    'shadow-sm',
                    'border-slate-200'
                );

                navbar.classList.remove(
                    'bg-white/95'
                );

            } else {

                navbar.classList.remove(
                    'bg-white/90',
                    'shadow-sm',
                    'border-slate-200'
                );

                navbar.classList.add(
                    'bg-white/95'
                );

            }

        };


        updateNavbar();

        window.addEventListener(
            'scroll',
            updateNavbar,
            { passive: true }
        );

    });
</script>