<header
    id="admin-navbar"
    class="sticky top-0 z-50 flex h-16 items-center justify-between
           border-b border-slate-200/80 bg-white/90 px-4
           backdrop-blur-xl transition-all duration-300
           sm:px-6 lg:px-8">

    {{-- =========================================================
        LEFT : BREADCRUMB
    ========================================================== --}}
    <div class="flex min-w-0 items-center">
        <nav
            aria-label="Breadcrumb"
            class="flex min-w-0 items-center gap-2 text-sm">

            @php
                $currentTitle = trim($__env->yieldContent('title'));

                $currentTitle = html_entity_decode(
                    $currentTitle,
                    ENT_QUOTES | ENT_HTML5,
                    'UTF-8'
                );
            @endphp

            {{-- PARENT PAGE --}}
            @if($currentTitle && strtolower($currentTitle) !== 'dashboard')
                <a
                    href="{{ url('/dashboard') }}"
                    class="hidden items-center gap-2 rounded-lg px-2 py-1.5
                           text-slate-500 transition-colors
                           hover:bg-slate-100 hover:text-blue-600 sm:inline-flex">

                    <svg
                        class="h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        aria-hidden="true">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="m3 10 9-7 9 7M5 9v11h14V9M9 20v-6h6v6" />
                    </svg>

                    <span class="font-medium">Dashboard</span>
                </a>

                <svg
                    class="hidden h-4 w-4 shrink-0 text-slate-300 sm:block"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    aria-hidden="true">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
                        d="m9 18 6-6-6-6" />
                </svg>
            @endif

            {{-- CURRENT PAGE --}}
            <span
                class="max-w-[180px] truncate font-semibold
                       tracking-tight text-slate-800 sm:max-w-none">
                {{ $currentTitle ?: 'Dashboard' }}
            </span>
        </nav>
    </div>


    {{-- =========================================================
        RIGHT : NOTIFICATION, PROFILE, LOGOUT
    ========================================================== --}}
    <div class="ml-3 flex shrink-0 items-center gap-2 sm:gap-3">

        {{-- NOTIFICATION --}}
        <button
            type="button"
            aria-label="Notifikasi"
            title="Notifikasi"
            class="relative flex h-10 w-10 items-center justify-center
                   rounded-xl border border-transparent text-slate-500
                   transition-all duration-200
                   hover:border-blue-100 hover:bg-blue-50
                   hover:text-blue-600
                   focus:outline-none focus:ring-2
                   focus:ring-blue-500/30">

            <svg
                class="h-5 w-5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                aria-hidden="true">
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.7"
                    d="M15 17h5l-1.405-1.405A2.032
                       2.032 0 0118 14.158V11a6.002
                       6.002 0 00-4-5.659V5a2 2
                       0 10-4 0v.341C7.67 6.165
                       6 8.388 6 11v3.159c0
                       .538-.214 1.055-.595
                       1.436L4 17h5m6 0v1a3
                       3 0 11-6 0v-1m6 0H9" />
            </svg>

            {{-- NOTIFICATION INDICATOR --}}
            <span
                class="absolute right-2 top-2 h-2 w-2
                       rounded-full bg-blue-500
                       ring-2 ring-white">
            </span>
        </button>


        {{-- SEPARATOR --}}
        <div class="h-7 w-px bg-slate-200"></div>


        {{-- USER DATA --}}
        @php
            $name = auth()->user()->name ?? 'Administrator';

            $initials = collect(explode(' ', trim($name)))
                ->filter()
                ->take(2)
                ->map(fn ($word) => strtoupper(substr($word, 0, 1)))
                ->implode('');

            $initials = $initials ?: 'A';
        @endphp


        {{-- PROFILE --}}
        <div class="flex min-w-0 items-center gap-2.5 rounded-xl
                    px-1.5 py-1 transition-colors
                    sm:px-2">

            {{-- AVATAR --}}
            <div
                class="flex h-9 w-9 shrink-0 items-center justify-center
                       rounded-xl border border-blue-100
                       bg-blue-50 text-xs font-bold
                       tracking-wide text-blue-700">
                {{ $initials }}
            </div>

            {{-- NAME & ROLE --}}
            <div class="hidden min-w-0 md:block">
                <p
                    class="max-w-[140px] truncate text-sm
                           font-semibold leading-5 text-slate-800">
                    {{ $name }}
                </p>

                <p
                    class="mt-0.5 max-w-[140px] truncate text-[11px]
                           font-medium text-slate-500">
                    {{ auth()->user()->role === 'super_admin'
                        ? 'Super Administrator'
                        : 'Admin Proyek' }}
                </p>
            </div>
        </div>


        {{-- SEPARATOR --}}
        <div class="mx-0.5 h-7 w-px bg-slate-200"></div>


        {{-- LOGOUT --}}
        <form
            action="{{ url('/logout') }}"
            method="POST"
            class="flex items-center">

            @csrf

            <button
                type="submit"
                aria-label="Keluar Sistem"
                title="Keluar Sistem"
                class="group flex h-10 items-center justify-center
                       gap-2 rounded-xl border border-transparent
                       px-2.5 text-slate-500
                       transition-all duration-200
                       hover:border-rose-100 hover:bg-rose-50
                       hover:text-rose-600
                       focus:outline-none focus:ring-2
                       focus:ring-rose-500/30
                       sm:px-3">

                <svg
                    class="h-[18px] w-[18px] shrink-0
                           transition-transform duration-200
                           group-hover:translate-x-0.5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    aria-hidden="true">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
                        d="M17 16l4-4m0 0-4-4m4 4H7
                           m6 4v1a3 3 0 01-3 3H6
                           a3 3 0 01-3-3V7a3 3
                           0 013-3h4a3 3 0 013 3v1" />
                </svg>

                <span class="hidden text-xs font-semibold sm:inline">
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
                    'bg-white/95',
                    'shadow-sm',
                    'border-slate-200'
                );

                navbar.classList.remove('bg-white/90');
            } else {
                navbar.classList.remove(
                    'bg-white/95',
                    'shadow-sm',
                    'border-slate-200'
                );

                navbar.classList.add('bg-white/90');
            }
        };

        updateNavbar();

        window.addEventListener('scroll', updateNavbar, {
            passive: true
        });
    });
</script>
