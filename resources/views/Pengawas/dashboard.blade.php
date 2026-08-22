@extends('layouts.mobile')

@section('title', 'Dashboard Pengawas')

@section('content')
<div class="max-w-md mx-auto bg-slate-100 min-h-screen pb-24 shadow-xl border-x border-slate-200">

    <!-- HEADER & PROFILE SECTION -->
    <div class="bg-slate-800 rounded-b-[2.5rem] shadow-lg relative overflow-hidden pb-6 pt-8 px-6">
        <!-- Dekorasi -->
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/5 rounded-full blur-2xl"></div>

        <div class="flex items-center justify-between relative z-10 mb-6">
            <div class="flex items-center space-x-4">
                <!-- Avatar Profil -->
                <div class="w-14 h-14 bg-amber-400 rounded-full flex items-center justify-center border-4 border-slate-700 shadow-inner">
                    <span class="text-xl font-bold text-slate-900">{{ substr($pegawai->nama, 0, 1) }}</span>
                </div>
                <!-- Info Pengawas -->
                <div>
                    <p class="text-sm text-slate-300">Selamat bekerja,</p>
                    <h1 class="text-lg font-bold text-white">{{ $pegawai->nama }}</h1>
                    <span class="text-xs font-medium px-2 py-0.5 bg-blue-500/20 text-blue-300 rounded-md mt-1 inline-block">{{ $pegawai->jabatan->nama_jabatan }}</span>
                </div>
            </div>

            <!-- Tombol Logout -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="p-2 bg-slate-700/50 rounded-full text-slate-300 hover:text-white hover:bg-red-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                </button>
            </form>
        </div>

        <!-- Kartu Info Singkat -->
        <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl p-4 relative z-10 flex justify-between items-center">
            <div>
                <p class="text-xs text-slate-300">Total Proyek Aktif</p>
                <p class="text-2xl font-bold text-amber-400">{{ $proyekDitugaskan->count() }} <span class="text-sm font-normal text-slate-200">Lokasi</span></p>
            </div>
            <div class="text-right">
                <p class="text-xs text-slate-300">Tanggal Hari Ini</p>
                <p class="text-sm font-semibold text-white">{{ now()->translatedFormat('d M Y') }}</p>
            </div>
        </div>
    </div>

    <!-- TAMPILAN PESAN ERROR/SUKSES DARI CONTROLLER -->
    @if(session('success'))
    <div class="mx-5 mt-6 bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-xl flex items-start shadow-sm">
        <svg class="w-5 h-5 mr-2 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        <span class="text-sm font-medium">{{ session('success') }}</span>
    </div>
    @endif
    @if(session('error'))
    <div class="mx-5 mt-6 bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-xl flex items-start shadow-sm">
        <svg class="w-5 h-5 mr-2 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span class="text-sm font-medium">{{ session('error') }}</span>
    </div>
    @endif

    <!-- DAFTAR PROYEK YANG DIAWASI -->
    <div class="px-5 mt-6">
        <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4 flex items-center">
            <svg class="w-4 h-4 mr-2 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            Tugas Lapangan Anda
        </h2>

        @forelse($proyekDitugaskan as $proyek)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 mb-4 hover:shadow-md transition-shadow relative overflow-hidden group">

            <!-- Indikator Warna Status -->
            <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $proyek->status == 'Berjalan' ? 'bg-green-500' : 'bg-blue-500' }}"></div>

            <div class="flex justify-between items-start mb-3 pl-2">
                <div>
                    <h3 class="font-bold text-slate-800 text-lg leading-tight">{{ $proyek->nama_proyek }}</h3>
                    <p class="text-xs text-slate-500 mt-1 flex items-center">
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        {{ $proyek->lokasi }}
                    </p>
                </div>
                <span class="text-[10px] font-bold px-2 py-1 rounded-md {{ $proyek->status == 'Berjalan' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                    {{ $proyek->status }}
                </span>
            </div>

            <!-- ========================================== -->
            <!-- LOGIKA TOMBOL CERDAS (PAGI / SORE / SELESAI) -->
            <!-- ========================================== -->
            <div class="pl-2 mt-4 pt-4 border-t border-slate-100">
                @php
                // CEK KE DATABASE: Apakah proyek ini sudah diabsen hari ini?
                $absenHariIni = \App\Models\Absensi::where('proyek_id', $proyek->id)
                ->where('tanggal', \Carbon\Carbon::now()->format('Y-m-d'))
                ->first();
                @endphp

                @if(!$absenHariIni)
                <!-- KONDISI 1: Belum Absen Sama Sekali (Tampilkan Tombol Pagi) -->
                <a href="{{ route('absensi.create', $proyek->id) }}" class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-slate-800 hover:bg-[#0c2340] text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    Buka Absen Pagi
                </a>

                @elseif($absenHariIni->status_validasi === 'Draft Pagi')
                <!-- KONDISI 2: Sudah Absen Pagi, Tunggu Sore (Tampilkan Tombol Sore Biru Tua) -->
                <a href="{{ route('absensi.create', $proyek->id) }}" class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-800 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                    </svg>
                    Tutup Absen Sore
                </a>

                @else
                <!-- KONDISI 3: Sudah Absen Sore (Tombol Dihilangkan & Diganti Info Hijau Selesai) -->
                <div class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-green-50 border border-green-200 text-green-700 text-sm font-semibold rounded-xl">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Absensi Hari Ini Selesai
                </div>
                @endif
                <a href="{{ route('laporan.create', $proyek->id) }}" class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-semibold rounded-xl transition-colors shadow-sm mt-2">
                    Buat Laporan Progres Fisik
                </a>
            </div>
            <!-- ========================================== -->

        </div>
        @empty
        <div class="bg-white rounded-2xl border border-dashed border-slate-300 p-8 text-center mt-6">
            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
            </div>
            <p class="text-sm font-bold text-slate-700">Belum Ada Proyek Aktif</p>
            <p class="text-xs text-slate-500 mt-1">Anda belum ditugaskan ke proyek manapun. Hubungi Admin kantor.</p>
        </div>
        @endforelse
    </div>

</div>
@endsection