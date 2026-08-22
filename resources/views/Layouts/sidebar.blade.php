<aside class="w-64 bg-[#0c2340] text-white flex flex-col hidden md:flex">
    <!-- Logo Area (Diperbesar) -->
    <div class="flex items-center justify-center border-b border-slate-700/50 pt-6 pb-6 mb-2">
        <img src="{{ asset('images/logo-mkp.png') }}" alt="Logo MKP" class="h-24 w-35 max-w-[85%] object-contain bg-white rounded-xl p-2 shadow-md">
    </div>

    <!-- Menu Navigasi -->
    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">

        <!-- Menu Dashboard -->
        <a href="{{ url('/dashboard') }}"
            class="flex items-center px-4 py-3 rounded-lg group transition-colors {{ request()->is('dashboard') ? 'bg-[#1a365d] text-white' : 'text-slate-300 hover:bg-[#152e52] hover:text-white' }}">
            <svg class="w-5 h-5 mr-3 {{ request()->is('dashboard') ? 'text-amber-500' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
            </svg>
            <span class="font-medium">Dashboard</span>
        </a>

        <!-- Menu Pegawai -->
        <a href="{{ url('/manajemen-pegawai') }}"
            class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->is('manajemen-pegawai') ? 'bg-[#1a365d] text-white' : 'text-slate-300 hover:bg-[#152e52] hover:text-white' }}">
            <svg class="w-5 h-5 mr-3 {{ request()->is('manajemen-pegawai') ? 'text-amber-500' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
            <span class="font-medium">Manajemen Pegawai</span>
        </a>

        <!-- Menu Absensi -->
        <a href="{{ url('/manajemen-absensi') }}"
            class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->is('manajemen-absensi') ? 'bg-[#1a365d] text-white' : 'text-slate-300 hover:bg-[#152e52] hover:text-white' }}">
            <svg class="w-5 h-5 mr-3 {{ request()->is('manajemen-absensi') ? 'text-amber-500' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
            </svg>
            <span class="font-medium">Manajemen Absensi</span>
        </a>

        <!-- Menu Proyek (Aktif juga jika membuka Detail Proyek) -->
        <a href="{{ url('/manajemen-proyek') }}"
            class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->is('manajemen-proyek') ? 'bg-[#1a365d] text-white' : 'text-slate-300 hover:bg-[#152e52] hover:text-white' }}">
            <svg class="w-5 h-5 mr-3 {{ request()->is('manajemen-proyek') ? 'text-amber-500' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            <span class="font-medium">Manajemen Proyek</span>
        </a>
    </nav>
</aside>