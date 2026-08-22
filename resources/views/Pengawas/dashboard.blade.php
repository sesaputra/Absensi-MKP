@extends('layouts.mobile')

@section('title', 'Dashboard Pengawas')

@section('content')
<div class="max-w-md mx-auto bg-[#f8fafc] min-h-screen pb-24 shadow-2xl border-x border-slate-200">

    <!-- HEADER & PROFILE SECTION (Tema #0c2340) -->
    <div class="bg-[#0c2340] rounded-b-[2.5rem] shadow-lg relative overflow-hidden pb-6 pt-8 px-6">
        <!-- Dekorasi -->
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/5 rounded-full blur-2xl"></div>
        <div class="absolute -left-10 bottom-0 w-32 h-32 bg-white/5 rounded-full blur-2xl"></div>

        <div class="flex items-center justify-between relative z-10 mb-6">
            <div class="flex items-center space-x-4">
                <!-- Avatar Profil -->
                <div class="w-14 h-14 bg-slate-100 rounded-full flex items-center justify-center border-4 border-white/20 shadow-inner">
                    <span class="text-xl font-black text-[#0c2340]">{{ substr($pegawai->nama, 0, 1) }}</span>
                </div>
                <!-- Info Pengawas -->
                <div>
                    <p class="text-[11px] text-slate-400 font-medium uppercase tracking-wider mb-0.5">Selamat bekerja,</p>
                    <h1 class="text-lg font-bold text-white leading-tight">{{ $pegawai->nama }}</h1>
                    <span class="text-[10px] font-bold px-2 py-0.5 bg-white/10 text-slate-200 border border-white/10 rounded uppercase tracking-widest mt-1.5 inline-block">{{ $pegawai->jabatan->nama_jabatan }}</span>
                </div>
            </div>

            <!-- Tombol Logout -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="p-2.5 bg-white/5 border border-white/10 rounded-full text-slate-300 hover:text-white hover:bg-rose-500/80 hover:border-rose-500 transition-all shadow-sm" title="Keluar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                </button>
            </form>
        </div>

        <!-- Kartu Info Singkat -->
        <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl p-4 relative z-10 flex justify-between items-center shadow-sm">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Tugas</p>
                <p class="text-2xl font-black text-white leading-none">{{ $proyekDitugaskan->count() }} <span class="text-[11px] font-semibold text-slate-300 ml-0.5">Lokasi</span></p>
            </div>
            <div class="text-right">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Tanggal</p>
                <p class="text-sm font-bold text-white">{{ now()->translatedFormat('d M Y') }}</p>
            </div>
        </div>
    </div>

    <!-- TAMPILAN PESAN ERROR/SUKSES DARI CONTROLLER -->
    @if(session('success'))
    <div class="mx-5 mt-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3.5 rounded-xl flex items-start shadow-sm">
        <svg class="w-5 h-5 mr-2.5 shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        <span class="text-[13px] font-bold leading-tight pt-0.5">{{ session('success') }}</span>
    </div>
    @endif
    @if(session('error'))
    <div class="mx-5 mt-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3.5 rounded-xl flex items-start shadow-sm">
        <svg class="w-5 h-5 mr-2.5 shrink-0 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span class="text-[13px] font-bold leading-tight pt-0.5">{{ session('error') }}</span>
    </div>
    @endif

    <!-- DAFTAR PROYEK YANG DIAWASI -->
    <div class="px-5 mt-8">
        <div class="flex items-center mb-5">
            <div class="h-px bg-slate-200 flex-1"></div>
            <span class="px-3 text-[11px] font-bold text-[#0c2340] uppercase tracking-widest">Tugas Lapangan Anda</span>
            <div class="h-px bg-slate-200 flex-1"></div>
        </div>

        @forelse($proyekDitugaskan as $proyek)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 mb-5 hover:shadow-md transition-shadow relative overflow-hidden group">

            <!-- Indikator Warna Status (Lebih Subtle) -->
            <div class="absolute left-0 top-0 bottom-0 w-1 {{ $proyek->status == 'Berjalan' ? 'bg-emerald-500' : 'bg-slate-400' }}"></div>

            <div class="flex justify-between items-start mb-4 pl-2">
                <div class="pr-2">
                    <h3 class="font-bold text-slate-800 text-base leading-snug mb-1.5">{{ $proyek->nama_proyek }}</h3>
                    <p class="text-[11px] font-medium text-slate-500 flex items-start leading-relaxed">
                        <svg class="w-3.5 h-3.5 mr-1 shrink-0 mt-0.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        {{ $proyek->lokasi }}
                    </p>
                </div>
                <span class="text-[9px] font-bold px-2 py-1 rounded border uppercase tracking-wider shrink-0 mt-0.5 {{ $proyek->status == 'Berjalan' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-slate-50 text-slate-600 border-slate-200' }}">
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
                <!-- KONDISI 1: Belum Absen Sama Sekali (Tampilkan Tombol Pagi Biru Navy) -->
                <a href="{{ route('absensi.create', $proyek->id) }}" class="w-full inline-flex justify-center items-center px-4 py-3 bg-[#0c2340] hover:bg-opacity-90 text-white text-sm font-bold rounded-xl transition-colors shadow-[0_4px_12px_-4px_rgba(12,35,64,0.5)]">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    Buka Absensi Pagi
                </a>

                @elseif($absenHariIni->status_validasi === 'Draft Pagi')
                <!-- KONDISI 2: Sudah Absen Pagi, Tunggu Sore (Tampilkan Tombol Sore Abu-Abu Tua) -->
                <a href="{{ route('absensi.create', $proyek->id) }}" class="w-full inline-flex justify-center items-center px-4 py-3 bg-slate-800 hover:bg-slate-900 text-white text-sm font-bold rounded-xl transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                    </svg>
                    Tutup Absensi Sore
                </a>

                @else
                <!-- KONDISI 3: Sudah Absen Sore (Tombol Dihilangkan & Diganti Info Hijau Selesai) -->
                <div class="w-full inline-flex justify-center items-center px-4 py-3 bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm font-bold rounded-xl">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Tugas Hari Ini Selesai
                </div>
                @endif
                
                <!-- NOTE: Tombol Laporan Fisik Terpisah Dihilangkan karena sudah digabung di Sore -->
            </div>
            <!-- ========================================== -->

        </div>
        @empty
        <div class="bg-white rounded-2xl border border-dashed border-slate-300 p-8 text-center mt-2 shadow-sm">
            <div class="w-14 h-14 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
            </div>
            <p class="text-sm font-bold text-slate-700">Belum Ada Tugas Aktif</p>
            <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">Anda belum ditugaskan ke proyek manapun. Hubungi Admin kantor pusat.</p>
        </div>
        @endforelse
    </div>

</div>
@endsection