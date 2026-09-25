<header
    class="relative z-50 h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 sm:px-6 lg:px-8">

    {{-- =========================================================
        LEFT : BREADCRUMB / PAGE CONTEXT
    ========================================================== --}}
    <div class="flex items-center min-w-0">

        <nav aria-label="Breadcrumb" class="flex items-center min-w-0 text-sm">
            @php $currentTitle = trim($__env->yieldContent('title')); @endphp

            {{-- Parent Title --}}
            @if($currentTitle && strtolower($currentTitle) !== 'dashboard')
                <span class="hidden sm:inline text-slate-400 font-medium hover:text-slate-600 transition-colors">
                    <a href="{{ url('/dashboard') }}">Dashboard</a>
                </span>

                <svg class="hidden sm:block w-4 h-4 mx-2 text-slate-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            @endif

            {{-- Current Page Title --}}
            <span class="font-semibold text-slate-800 truncate max-w-[200px] sm:max-w-none">
                {{ $currentTitle ?: 'Dashboard' }}
            </span>
        </nav>

    </div>


    {{-- =========================================================
        RIGHT : NOTIFICATION + USER INFO + DIRECT LOGOUT
    ========================================================== --}}
    <div class="flex items-center gap-2 sm:gap-3 ml-4">

        {{-- 1. NOTIFICATION BUTTON --}}
        <button
            type="button"
            aria-label="Notifikasi"
            class="relative flex items-center justify-center w-9 h-9 rounded-lg text-slate-500 hover:text-amber-600 hover:bg-amber-50 focus:outline-none focus:ring-2 focus:ring-amber-500 transition-all duration-200">
            
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
            </svg>

            {{-- Notification Dot --}}
            <span class="absolute top-2 right-2 flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
            </span>
        </button>


        {{-- Separator / Pembatas Halus --}}
        <div class="h-5 w-px bg-slate-200 mx-0.5"></div>


        {{-- 2. USER PROFILE INFO (Statik / Info Pengguna) --}}
        @php
            $name = auth()->user()->name ?? 'Administrator';
            $initials = collect(explode(' ', trim($name)))
                ->filter()
                ->take(2)
                ->map(fn ($word) => strtoupper(substr($word, 0, 1)))
                ->implode('');
            $initials = $initials ?: 'A';
        @endphp

        <div class="flex items-center gap-2.5 py-1 px-1">
            {{-- Avatar --}}
            <div class="shrink-0 w-9 h-9 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-xs font-bold text-slate-600">
                {{ $initials }}
            </div>

            {{-- Nama & Role --}}
            <div class="hidden md:block text-left min-w-0">
                <p class="text-sm font-semibold text-slate-700 truncate max-w-[130px]">
                    {{ $name }}
                </p>
                <p class="text-[11px] text-slate-400 font-medium truncate max-w-[130px]">
                    {{ auth()->user()->role === 'super_admin' ? 'Super Administrator' : 'Admin Proyek' }}
                </p>
            </div>
        </div>


        {{-- Separator / Pembatas Halus --}}
        <div class="h-5 w-px bg-slate-200 mx-0.5"></div>


        {{-- 3. DIRECT LOGOUT BUTTON --}}
        <form action="{{ url('/logout') }}" method="POST" class="flex items-center">
            @csrf
            <button
                type="submit"
                aria-label="Keluar Sistem"
                title="Keluar Sistem"
                class="flex items-center gap-2 px-2.5 py-2 sm:px-3 rounded-lg text-xs font-semibold text-rose-600 hover:text-rose-700 hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-rose-500 transition-colors">
                
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>

                {{-- Teks hanya muncul di layar HP sedang ke atas agar hemat ruang --}}
                <span class="hidden sm:inline">Keluar</span>
            </button>
        </form>

    </div>

</header>