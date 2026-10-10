@extends('layouts.mobile')

@section('title', 'Dashboard Pengawas')

@section('content')

<div class="min-h-screen bg-slate-100">

    <div class="mx-auto min-h-screen w-full max-w-md bg-slate-50 pb-24 shadow-sm md:max-w-3xl lg:max-w-6xl">

        {{-- =========================================================
            HEADER & PROFILE
        ========================================================== --}}
        <header class="relative overflow-hidden rounded-b-[2rem] bg-[#0c2340] px-5 pb-6 pt-7 shadow-lg sm:px-6 lg:px-8 lg:pb-8 lg:pt-8">

            {{-- Dekorasi --}}
            <div class="pointer-events-none absolute -right-10 -top-10 h-40 w-40 rounded-full bg-white/5 blur-2xl"></div>

            <div class="pointer-events-none absolute -bottom-10 -left-10 h-32 w-32 rounded-full bg-white/5 blur-2xl"></div>


            {{-- PROFILE --}}
            <div class="relative z-10 flex items-center justify-between gap-4">

                {{-- LEFT --}}
                <div class="flex min-w-0 items-center gap-3 sm:gap-4">

                    {{-- Avatar --}}
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full border-4 border-white/20 bg-slate-100 shadow-inner sm:h-14 sm:w-14">

                        <span class="text-lg font-black text-[#0c2340] sm:text-xl">
                            {{ substr($pegawai->nama, 0, 1) }}
                        </span>

                    </div>


                    {{-- Informasi --}}
                    <div class="min-w-0">

                        <p class="text-[10px] font-medium uppercase tracking-[0.14em] text-slate-400 sm:text-[11px]">
                            Selamat bekerja,
                        </p>

                        <h1 class="mt-0.5 truncate text-base font-bold leading-tight text-white sm:text-lg">
                            {{ $pegawai->nama }}
                        </h1>

                        <span class="mt-1.5 inline-flex max-w-full rounded-md border border-white/10 bg-white/10 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider text-slate-200">
                            {{ $pegawai->jabatan->nama_jabatan }}
                        </span>

                    </div>

                </div>


                {{-- LOGOUT --}}
                <form method="POST" action="{{ route('logout') }}" class="shrink-0">

                    @csrf

                    <button
                        type="submit"
                        title="Keluar"
                        class="flex h-10 w-10 items-center justify-center rounded-full border border-white/10 bg-white/5 text-slate-300 shadow-sm transition-all hover:border-rose-500 hover:bg-rose-500/80 hover:text-white sm:h-11 sm:w-11">

                        <svg
                            class="h-5 w-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>

                    </button>

                </form>

            </div>


            {{-- =====================================================
                SUMMARY
            ====================================================== --}}
            <div class="relative z-10 mt-6 grid grid-cols-2 gap-3">

                {{-- TOTAL TUGAS --}}
                <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur-md">

                    <p class="text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-400">
                        Total Tugas
                    </p>

                    <div class="mt-2 flex items-end gap-1.5">

                        <span class="text-2xl font-black leading-none text-white">
                            {{ $proyekDitugaskan->count() }}
                        </span>

                        <span class="mb-0.5 text-[11px] font-medium text-slate-300">
                            Lokasi
                        </span>

                    </div>

                </div>


                {{-- TANGGAL --}}
                <div class="rounded-2xl border border-white/10 bg-white/10 p-4 text-right backdrop-blur-md">

                    <p class="text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-400">
                        Tanggal
                    </p>

                    <p class="mt-2 text-sm font-bold text-white sm:text-base">
                        {{ now()->translatedFormat('d M Y') }}
                    </p>

                </div>

            </div>

        </header>


        {{-- =========================================================
            SESSION MESSAGE
        ========================================================== --}}
        @if(session('success'))

        <div class="mx-5 mt-5 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3.5 text-emerald-700 shadow-sm sm:mx-6 lg:mx-8">

            <svg
                class="mt-0.5 h-5 w-5 shrink-0 text-emerald-500"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24">
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M5 13l4 4L19 7" />
            </svg>

            <span class="text-sm font-semibold leading-5">
                {{ session('success') }}
            </span>

        </div>

        @endif


        @if(session('error'))

        <div class="mx-5 mt-5 flex items-start gap-3 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3.5 text-rose-700 shadow-sm sm:mx-6 lg:mx-8">

            <svg
                class="mt-0.5 h-5 w-5 shrink-0 text-rose-500"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24">
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0z" />
            </svg>

            <span class="text-sm font-semibold leading-5">
                {{ session('error') }}
            </span>

        </div>

        @endif


        {{-- =========================================================
            PROJECT SECTION
        ========================================================== --}}
        <main class="px-5 pt-7 sm:px-6 lg:px-8 lg:pt-8">


            {{-- =========================================================
        RIWAYAT ABSENSI
    ========================================================== --}}
            <a
                href="{{ route('pengawas.riwayat.absensi') }}"
                class="group mb-6 flex items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition-all duration-200 hover:border-[#0c2340]/20 hover:shadow-md sm:p-5">
                <div class="flex min-w-0 items-center gap-3">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#0c2340]/5 text-[#0c2340] transition-colors group-hover:bg-[#0c2340] group-hover:text-white">
                        <svg
                            class="h-6 w-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.7"
                                d="M8 7h12M8 12h12M8 17h12M4 7h.01M4 12h.01M4 17h.01" />
                        </svg>
                    </div>

                    <div class="min-w-0">
                        <h2 class="text-sm font-bold text-slate-800 sm:text-base">
                            Riwayat Absensi
                        </h2>

                        <p class="mt-1 text-xs leading-5 text-slate-500 sm:text-sm">
                            Lihat catatan dan status absensi sebelumnya.
                        </p>
                    </div>
                </div>

                <svg
                    class="h-5 w-5 shrink-0 text-slate-400 transition-transform group-hover:translate-x-1 group-hover:text-[#0c2340]"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
                        d="m9 18 6-6-6-6" />
                </svg>
            </a>
            {{-- SECTION TITLE --}}
            <div class="mb-5 flex items-center gap-3">

                <div class="h-px flex-1 bg-slate-200"></div>

                <span class="shrink-0 px-1 text-[11px] font-bold uppercase tracking-[0.15em] text-[#0c2340]">
                    Tugas Lapangan Anda
                </span>

                <div class="h-px flex-1 bg-slate-200"></div>

            </div>


            {{-- =====================================================
                PROJECT GRID
            ====================================================== --}}
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">

                @forelse($proyekDitugaskan as $proyek)

                {{-- =================================================
                    PROJECT CARD
                ================================================== --}}
                <article class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition-all hover:border-slate-300 hover:shadow-md">

                    {{-- Status Indicator --}}
                    <div
                        class="absolute bottom-0 left-0 top-0 w-1
                            {{ $proyek->status == 'Berjalan'
                                ? 'bg-emerald-500'
                                : 'bg-slate-400' }}">
                    </div>


                    {{-- PROJECT HEADER --}}
                    <div class="flex items-start justify-between gap-4 pl-2">

                        <div class="min-w-0 flex-1">

                            <h3 class="text-base font-bold leading-snug text-slate-800 sm:text-lg">
                                {{ $proyek->nama_proyek }}
                            </h3>


                            {{-- Lokasi --}}
                            <p class="mt-2 flex items-start text-xs font-medium leading-5 text-slate-500 sm:text-sm">

                                <svg
                                    class="mr-1.5 mt-0.5 h-4 w-4 shrink-0 text-slate-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>

                                <span>
                                    {{ $proyek->lokasi }}
                                </span>

                            </p>

                        </div>


                        {{-- STATUS --}}
                        <span
                            class="shrink-0 rounded-lg border px-2 py-1 text-[10px] font-bold uppercase tracking-wider
                                {{ $proyek->status == 'Berjalan'
                                    ? 'border-emerald-100 bg-emerald-50 text-emerald-600'
                                    : 'border-slate-200 bg-slate-50 text-slate-600' }}">
                            {{ $proyek->status }}
                        </span>

                    </div>


                    {{-- =================================================
                        ABSENSI ACTION
                    ================================================== --}}
                    <div class="mt-5 border-t border-slate-100 pl-2 pt-5">

                        @php

                        /*
                        * ==================================================
                        * Ambil laporan harian proyek hari ini.
                        *
                        * Status utama menggunakan LaporanHarian karena
                        * satu proyek dapat memiliki banyak record Absensi.
                        * ==================================================
                        */

                        $laporanHariIni = \App\Models\LaporanHarian::where(
                        'proyek_id',
                        $proyek->id
                        )
                        ->whereDate(
                        'tanggal',
                        \Carbon\Carbon::now()->format('Y-m-d')
                        )
                        ->latest('id')
                        ->first();

                        $statusValidasi = $laporanHariIni?->status_validasi;

                        @endphp


                        {{-- =================================================
                            BELUM ABSEN
                        ================================================== --}}
                        @if(!$laporanHariIni)

                        <a
                            href="{{ route('absensi.create', $proyek->id) }}"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#0c2340] px-4 py-3 text-sm font-bold text-white shadow-[0_4px_12px_-4px_rgba(12,35,64,0.45)] transition-all hover:bg-[#102d52] focus:outline-none focus:ring-4 focus:ring-[#0c2340]/10">

                            <svg
                                class="h-4 w-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>

                            Buka Absensi Pagi

                        </a>


                        {{-- =================================================
                            SUDAH ABSEN PAGI
                        ================================================== --}}
                        @elseif($statusValidasi === 'Draft Pagi')

                        <a
                            href="{{ route('absensi.create', $proyek->id) }}"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-slate-800 px-4 py-3 text-sm font-bold text-white shadow-sm transition-all hover:bg-slate-900 focus:outline-none focus:ring-4 focus:ring-slate-500/10">

                            <svg
                                class="h-4 w-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>

                            Tutup Absensi Sore

                        </a>


                        {{-- =================================================
                            MENUNGGU VALIDASI
                        ================================================== --}}
                        @elseif($statusValidasi === 'Menunggu Persetujuan')

                        <div class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-blue-100 bg-blue-50 px-4 py-3 text-sm font-bold text-blue-700">

                            <svg
                                class="h-4 w-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>

                            Menunggu Validasi Admin

                        </div>


                        {{-- =================================================
                            DITOLAK
                        ================================================== --}}
                        @elseif($statusValidasi === 'Ditolak')

                        <a
                            href="{{ route('absensi.create', $proyek->id) }}"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-bold text-rose-700 shadow-sm transition-all hover:border-rose-300 hover:bg-rose-100 focus:outline-none focus:ring-4 focus:ring-rose-500/10">

                            <svg
                                class="h-4 w-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 9v4m0 4h.01M10.29 3.86l-7.82 13.5A2 2 0 004.2 20.4h15.6a2 2 0 001.73-3.04l-7.82-13.5a2 2 0 00-3.46 0z" />
                            </svg>

                            Perbaiki & Kirim Ulang

                        </a>


                        {{-- =================================================
                            DISETUJUI
                        ================================================== --}}
                        @elseif($statusValidasi === 'Disetujui')

                        <div class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-700">

                            <svg
                                class="h-4 w-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>

                            Absensi Disetujui

                        </div>


                        {{-- =================================================
                            FALLBACK
                        ================================================== --}}
                        @else

                        <div class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-600">

                            <svg
                                class="h-4 w-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>

                            {{ $statusValidasi ?: 'Status Belum Tersedia' }}

                        </div>

                        @endif

                    </div>

                </article>

                @empty

                {{-- =================================================
                    EMPTY STATE
                ================================================== --}}
                <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-12 text-center shadow-sm lg:col-span-2">

                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full border border-slate-100 bg-slate-50">

                        <svg
                            class="h-6 w-6 text-slate-300"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.5"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>

                    </div>


                    <p class="mt-4 text-sm font-bold text-slate-700">
                        Belum Ada Tugas Aktif
                    </p>

                    <p class="mx-auto mt-1.5 max-w-sm text-xs leading-5 text-slate-500">
                        Anda belum ditugaskan ke proyek manapun.
                        Hubungi Admin kantor pusat.
                    </p>

                </div>

                @endforelse

            </div>

        </main>

    </div>

</div>

@endsection