<header class="h-16 bg-white shadow-sm flex items-center justify-between px-6 lg:px-8 z-10">
    <!-- Breadcrumb -->
    <div class="text-slate-500 text-sm font-medium">
        Admin <span class="mx-2">/</span> <span class="text-slate-900 font-bold">@yield('title', 'Dashboard')</span>
    </div>

    <!-- Profil & Notifikasi -->
    <div class="flex items-center space-x-4">

        <!-- Notifikasi -->
        <button class="text-slate-400 hover:text-amber-500 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
            </svg>
        </button>

        <!-- Wadah Profil (relative agar dropdown tidak berantakan) -->
        <div class="relative border-l pl-4 border-slate-200">

            <!-- Tombol Trigger Profil -->
            <button id="profil-button" onclick="toggleDropdown()" class="flex items-center space-x-2 focus:outline-none group cursor-pointer">
                <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-slate-600 font-bold group-hover:bg-slate-300 transition-colors">
                    A
                </div>
                <span class="text-sm font-semibold text-slate-700 group-hover:text-amber-600 transition-colors">Admin</span>

                <!-- Ikon Panah Bawah (Indikator Dropdown) -->
                <svg class="w-4 h-4 text-slate-400 group-hover:text-amber-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <!-- Menu Dropdown Pop-Up -->
            <div id="profil-menu" class="hidden absolute right-0 mt-4 w-48 bg-white rounded-lg shadow-lg ring-1 ring-slate-200 py-2 z-50 transition-all duration-200">
                <div class="px-4 py-2 border-b border-slate-100 mb-1">
                    <p class="text-xs text-slate-500">Login sebagai</p>
                    <p class="text-sm font-bold text-slate-800">Administrator</p>
                </div>

                <!-- Tombol Keluar mengarah ke /login -->
                <a href="{{ url('/login') }}" class="flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Keluar Sistem
                </a>
            </div>

        </div>
    </div>
</header>