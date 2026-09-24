@extends('layouts.admin')

@section('title', 'Detail Absensi & Laporan Proyek')

@section('content')

@php
    $totalLaporan = $riwayatLaporan->count();
    $laporanValid = $riwayatLaporan->where('status_validasi', 'Disetujui')->count();
    $laporanPending = $riwayatLaporan->where('status_validasi', '!=', 'Disetujui')->count();
@endphp

{{-- =========================================================
    HERO — DETAIL ABSENSI & VALIDASI OPERASIONAL
========================================================== --}}
<section
    class="relative isolate overflow-hidden rounded-[30px] bg-slate-950 text-white shadow-xl shadow-slate-200/50"
>

    {{-- PROJECT IMAGE --}}
    @if($proyek->gambar)

        <div
            class="absolute inset-0 -z-30 bg-cover bg-center opacity-[0.20]"
            style="background-image: url('{{ asset('storage/' . $proyek->gambar) }}');"
        ></div>

    @else

        <div class="absolute inset-0 -z-30 bg-slate-950"></div>

    @endif


    {{-- DARK OVERLAY --}}
    <div
        class="absolute inset-0 -z-20 bg-gradient-to-br from-slate-950 via-slate-950/[0.95] to-blue-950/[0.94]"
    ></div>


    {{-- DECORATIVE GLOW --}}
    <div
        class="absolute -right-32 -top-32 -z-10 h-80 w-80 rounded-full bg-blue-500/10 blur-3xl"
    ></div>

    <div
        class="absolute -bottom-40 -left-20 -z-10 h-80 w-80 rounded-full bg-emerald-500/10 blur-3xl"
    ></div>


    {{-- SUBTLE GRID --}}
    <div
        class="absolute inset-0 -z-10 opacity-[0.035]"
        style="background-image: linear-gradient(rgba(255,255,255,.8) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.8) 1px, transparent 1px); background-size: 34px 34px;"
    ></div>


    <div class="relative p-5 sm:p-7 lg:p-8">

        {{-- =====================================================
            TOP NAVIGATION
        ====================================================== --}}
        <div class="flex flex-wrap items-center justify-between gap-4">

            <div class="flex flex-wrap items-center gap-2">

                {{-- BACK --}}
                <a
                    href="{{ route('admin.absensi.index') }}"
                    class="group inline-flex items-center gap-2.5 text-[11px] font-medium text-slate-300 transition-colors hover:text-white"
                >
                    <span
                        class="flex h-8 w-8 items-center justify-center rounded-xl border border-white/10 bg-white/[0.06] transition-all duration-200 group-hover:-translate-x-0.5 group-hover:bg-white/10"
                    >
                        <svg
                            class="h-4 w-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"
                            />
                        </svg>
                    </span>

                    Kembali ke Daftar
                </a>

            </div>


            {{-- MODULE BADGE --}}
            <div
                class="inline-flex items-center gap-2 rounded-xl border border-white/10 bg-white/[0.06] px-3 py-2 backdrop-blur-sm"
            >
                <span class="relative flex h-2 w-2">

                    <span
                        class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-60"
                    ></span>

                    <span
                        class="relative inline-flex h-2 w-2 rounded-full bg-emerald-400"
                    ></span>

                </span>

                <span
                    class="text-[9px] font-semibold uppercase tracking-[0.16em] text-slate-300"
                >
                    Validasi Operasional
                </span>
            </div>

        </div>


        {{-- =====================================================
            HERO CONTENT
        ====================================================== --}}
        <div class="mt-8 max-w-3xl">

            <p
                class="text-[9px] font-semibold uppercase tracking-[0.20em] text-emerald-300"
            >
                Detail Absensi & Laporan
            </p>

            <h1
                class="mt-2 text-[30px] font-semibold tracking-[-0.035em] text-white sm:text-[34px]"
            >
                {{ $proyek->nama_proyek }}
            </h1>

            <div class="mt-3 flex flex-wrap items-center gap-x-4 gap-y-2">

                {{-- LOCATION --}}
                <p
                    class="flex items-center gap-2 text-[12px] font-medium text-slate-300"
                >
                    <svg
                        class="h-4 w-4 text-slate-500"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                        />
                    </svg>

                    {{ $proyek->lokasi }}
                </p>

                <span
                    class="hidden h-1 w-1 rounded-full bg-slate-600 sm:block"
                ></span>

                <p class="text-[11px] text-slate-400">
                    Monitoring dokumentasi lapangan, GPS mandor, dan kehadiran pekerja.
                </p>

            </div>

        </div>


        {{-- =====================================================
            KPI
        ====================================================== --}}
        <div class="mt-7 grid grid-cols-2 gap-3 lg:grid-cols-4">

            {{-- TOTAL LAPORAN --}}
            <div
                class="rounded-2xl border border-white/10 bg-white/[0.055] p-4 backdrop-blur-md"
            >

                <p
                    class="text-[9px] font-semibold uppercase tracking-[0.15em] text-slate-400"
                >
                    Total Laporan
                </p>

                <div class="mt-2 flex items-end justify-between gap-3">

                    <p
                        class="text-[21px] font-semibold tracking-tight text-white"
                    >
                        {{ $totalLaporan }}

                        <span class="text-[11px] font-medium text-slate-400">
                            Hari
                        </span>
                    </p>

                    <div
                        class="flex h-8 w-8 items-center justify-center rounded-xl bg-white/[0.06] text-slate-300"
                    >
                        <svg
                            class="h-4 w-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                            />
                        </svg>
                    </div>

                </div>

            </div>


            {{-- LAPORAN VALID --}}
            <div
                class="rounded-2xl border border-emerald-400/10 bg-emerald-500/[0.055] p-4 backdrop-blur-md"
            >

                <p
                    class="text-[9px] font-semibold uppercase tracking-[0.15em] text-emerald-300/80"
                >
                    Laporan Valid
                </p>

                <div class="mt-2 flex items-end justify-between gap-3">

                    <p
                        class="text-[21px] font-semibold tracking-tight text-emerald-300"
                    >
                        {{ $laporanValid }}
                    </p>

                    <div
                        class="flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-500/10 text-emerald-300"
                    >
                        <svg
                            class="h-4 w-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5 13l4 4L19 7"
                            />
                        </svg>
                    </div>

                </div>

            </div>


            {{-- PENDING --}}
            <div
                class="rounded-2xl border border-amber-400/10 bg-amber-500/[0.055] p-4 backdrop-blur-md"
            >

                <p
                    class="text-[9px] font-semibold uppercase tracking-[0.15em] text-amber-300/80"
                >
                    Menunggu Validasi
                </p>

                <div class="mt-2 flex items-end justify-between gap-3">

                    <p
                        class="text-[21px] font-semibold tracking-tight text-amber-300"
                    >
                        {{ $laporanPending }}
                    </p>

                    <div
                        class="flex h-8 w-8 items-center justify-center rounded-xl bg-amber-500/10 text-amber-300"
                    >
                        <svg
                            class="h-4 w-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                    </div>

                </div>

            </div>


            {{-- BULAN --}}
            <div
                class="rounded-2xl border border-white/10 bg-white/[0.055] p-4 backdrop-blur-md"
            >

                <p
                    class="text-[9px] font-semibold uppercase tracking-[0.15em] text-slate-400"
                >
                    Bulan Terpilih
                </p>

                <div class="mt-2">

                    <p
                        class="text-[16px] font-semibold tracking-tight text-white"
                    >
                        {{ \Carbon\Carbon::parse($bulanFilter)->translatedFormat('F') }}
                    </p>

                    <p class="mt-0.5 text-[10px] text-slate-400">
                        {{ \Carbon\Carbon::parse($bulanFilter)->translatedFormat('Y') }}
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>


{{-- =========================================================
    ARSIP LAPORAN HARIAN
========================================================== --}}
<section
    class="rounded-[24px] border border-slate-200 bg-white p-5 shadow-sm sm:p-6"
>

    {{-- HEADER --}}
    <div
        class="mb-6 flex flex-col gap-4 border-b border-slate-100 pb-5 sm:flex-row sm:items-center sm:justify-between"
    >

        <div>

            <div class="flex items-center gap-2">

                <span
                    class="flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600"
                >
                    <svg
                        class="h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                        />
                    </svg>
                </span>

                <div>

                    <p
                        class="text-[9px] font-semibold uppercase tracking-[0.16em] text-emerald-600"
                    >
                        Arsip Operasional
                    </p>

                    <h2
                        class="mt-0.5 text-[16px] font-semibold tracking-tight text-slate-900"
                    >
                        Laporan Harian
                    </h2>

                </div>

            </div>

            <p class="mt-2 text-[12px] leading-5 text-slate-500">
                Buka setiap tanggal untuk melihat dokumentasi foto,
                lokasi, dan rincian kehadiran pekerja.
            </p>

        </div>


        {{-- FILTER --}}
        <form
            action="{{ route('admin.absensi.detail', $proyek->id) }}"
            method="GET"
            class="flex w-full items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 p-1.5 sm:w-auto"
        >

            <label
                for="filter_bulan"
                class="pl-2 text-[9px] font-semibold uppercase tracking-[0.12em] text-slate-500"
            >
                Periode
            </label>

            <input
                type="month"
                id="filter_bulan"
                name="filter_bulan"
                value="{{ $bulanFilter }}"
                onchange="this.form.submit()"
                class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-[11px] font-medium text-slate-700 outline-none transition-all focus:border-emerald-400 focus:ring-4 focus:ring-emerald-500/10"
            >

        </form>

    </div>


    {{-- =====================================================
        ACCORDION LAPORAN
    ====================================================== --}}
    <div class="space-y-3">

        @forelse($riwayatLaporan as $laporan)

            @php
                $hadir = $laporan->absensis->where('status', 'Hadir')->count();
                $absen = $laporan->absensis->whereIn('status', ['Sakit', 'Izin', 'Alfa'])->count();
                $isValid = $laporan->status_validasi == 'Disetujui';
            @endphp


            {{-- ACCORDION --}}
            <details
                class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition-all [&_summary::-webkit-details-marker]:hidden {{ $isValid ? 'border-l-[3px] border-l-emerald-500' : 'border-l-[3px] border-l-amber-500' }}"
            >

                {{-- =================================================
                    SUMMARY
                ================================================== --}}
                <summary
                    class="flex cursor-pointer flex-col gap-3 p-4 transition-colors hover:bg-slate-50/70 sm:flex-row sm:items-center sm:justify-between"
                >

                    <div class="flex items-center gap-3.5">

                        {{-- TOGGLE --}}
                        <span
                            class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-slate-50 text-slate-400 transition-all group-open:bg-slate-900 group-open:text-white"
                        >
                            <svg
                                class="h-4 w-4 transition-transform duration-300 group-open:rotate-180"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="M19 9l-7 7-7-7"
                                />
                            </svg>
                        </span>


                        {{-- DATE --}}
                        <div>

                            <h3
                                class="text-[13px] font-semibold tracking-tight text-slate-900"
                            >
                                {{ \Carbon\Carbon::parse($laporan->tanggal)->translatedFormat('l, d F Y') }}
                            </h3>

                            <p
                                class="mt-1 flex items-center gap-1 text-[10px] font-medium text-slate-400"
                            >
                                <span>Pengawas:</span>

                                <span class="font-semibold text-slate-600">
                                    {{ $laporan->pembuatLaporan->nama ?? '-' }}
                                </span>
                            </p>

                        </div>

                    </div>


                    {{-- STATUS --}}
                    <div
                        class="flex flex-wrap items-center gap-1.5 sm:flex-nowrap"
                    >

                        {{-- HADIR --}}
                        <span
                            class="inline-flex items-center rounded-lg border border-emerald-100 bg-emerald-50 px-2.5 py-1 text-[10px] font-semibold text-emerald-700"
                        >
                            {{ $hadir }} Hadir
                        </span>


                        {{-- ABSEN --}}
                        @if($absen > 0)

                            <span
                                class="inline-flex items-center rounded-lg border border-rose-100 bg-rose-50 px-2.5 py-1 text-[10px] font-semibold text-rose-700"
                            >
                                {{ $absen }} Absen
                            </span>

                        @endif


                        <span
                            class="mx-1 hidden h-4 w-px bg-slate-200 sm:block"
                        ></span>


                        {{-- VALIDATION --}}
                        @if($isValid)

                            <span
                                class="inline-flex items-center gap-1 rounded-lg border border-emerald-100 bg-emerald-50 px-2.5 py-1 text-[10px] font-semibold text-emerald-700"
                            >
                                <svg
                                    class="h-3 w-3"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2.5"
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>

                                Valid
                            </span>

                        @else

                            <span
                                class="inline-flex items-center gap-1 rounded-lg border border-amber-100 bg-amber-50 px-2.5 py-1 text-[10px] font-semibold text-amber-700"
                            >
                                <svg
                                    class="h-3 w-3"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.8"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>

                                Pending
                            </span>

                        @endif

                    </div>

                </summary>


                {{-- =================================================
                    DETAIL CONTENT
                ================================================== --}}
                <div
                    class="border-t border-slate-100 bg-slate-50/50 p-5 sm:p-6"
                >

                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

                        {{-- =================================================
                            DOKUMENTASI
                        ================================================== --}}
                        <div class="space-y-5">

                            <div>

                                <p
                                    class="text-[9px] font-semibold uppercase tracking-[0.16em] text-slate-400"
                                >
                                    Dokumentasi Proyek
                                </p>

                                <p class="mt-1 text-[11px] text-slate-500">
                                    Bukti visual aktivitas lapangan.
                                </p>

                            </div>


                            {{-- FOTO --}}
                            <div class="grid grid-cols-2 gap-3">

                                {{-- FOTO PAGI --}}
                                <div
                                    class="group/img overflow-hidden rounded-xl border border-slate-200 bg-white p-1.5 shadow-sm"
                                >

                                    <p
                                        class="px-1 pb-1.5 text-[9px] font-semibold uppercase tracking-[0.12em] text-slate-400"
                                    >
                                        Foto Pagi
                                    </p>

                                    @if($laporan->foto_pagi)

                                        <div class="relative overflow-hidden rounded-lg">

                                            <img
                                                src="{{ asset('storage/' . $laporan->foto_pagi) }}"
                                                class="h-28 w-full object-cover transition-transform duration-300 group-hover/img:scale-105"
                                            >

                                            <a
                                                href="{{ asset('storage/' . $laporan->foto_pagi) }}"
                                                target="_blank"
                                                class="absolute inset-0 flex items-center justify-center bg-slate-950/50 text-[10px] font-semibold text-white opacity-0 transition-opacity group-hover/img:opacity-100"
                                            >
                                                Perbesar ↗
                                            </a>

                                        </div>

                                    @else

                                        <div
                                            class="flex h-28 w-full flex-col items-center justify-center gap-1 rounded-lg border border-dashed border-slate-200 bg-slate-50 text-slate-400"
                                        >

                                            <svg
                                                class="h-5 w-5 text-slate-300"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="1.5"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                                />
                                            </svg>

                                            <span class="text-[9px]">
                                                Tidak tersedia
                                            </span>

                                        </div>

                                    @endif

                                </div>


                                {{-- FOTO SORE --}}
                                <div
                                    class="group/img overflow-hidden rounded-xl border border-slate-200 bg-white p-1.5 shadow-sm"
                                >

                                    <p
                                        class="px-1 pb-1.5 text-[9px] font-semibold uppercase tracking-[0.12em] text-slate-400"
                                    >
                                        Foto Sore
                                    </p>

                                    @if($laporan->foto_sore)

                                        <div class="relative overflow-hidden rounded-lg">

                                            <img
                                                src="{{ asset('storage/' . $laporan->foto_sore) }}"
                                                class="h-28 w-full object-cover transition-transform duration-300 group-hover/img:scale-105"
                                            >

                                            <a
                                                href="{{ asset('storage/' . $laporan->foto_sore) }}"
                                                target="_blank"
                                                class="absolute inset-0 flex items-center justify-center bg-slate-950/50 text-[10px] font-semibold text-white opacity-0 transition-opacity group-hover/img:opacity-100"
                                            >
                                                Perbesar ↗
                                            </a>

                                        </div>

                                    @else

                                        <div
                                            class="flex h-28 w-full flex-col items-center justify-center gap-1 rounded-lg border border-dashed border-slate-200 bg-slate-50 text-slate-400"
                                        >

                                            <svg
                                                class="h-5 w-5 text-slate-300"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="1.5"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                                />
                                            </svg>

                                            <span class="text-[9px]">
                                                Tidak tersedia
                                            </span>

                                        </div>

                                    @endif

                                </div>

                            </div>


                            {{-- CATATAN --}}
                            @if($laporan->kegiatan)

                                <div>

                                    <p
                                        class="mb-1.5 text-[9px] font-semibold uppercase tracking-[0.16em] text-slate-400"
                                    >
                                        Catatan Progress
                                    </p>

                                    <div
                                        class="whitespace-pre-line rounded-xl border border-slate-200 bg-white p-3.5 text-[11px] leading-5 text-slate-600 shadow-sm"
                                    >
                                        {{ $laporan->kegiatan }}
                                    </div>

                                </div>

                            @endif

                        </div>


                        {{-- =================================================
                            TABEL ABSENSI
                        ================================================== --}}
                        <div class="lg:col-span-2">

                            <div class="mb-3">

                                <p
                                    class="text-[9px] font-semibold uppercase tracking-[0.16em] text-slate-400"
                                >
                                    Rekap Kehadiran
                                </p>

                                <h4
                                    class="mt-1 text-[15px] font-semibold tracking-tight text-slate-900"
                                >
                                    Daftar Kehadiran Pekerja
                                </h4>

                            </div>


                            <div
                                class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm"
                            >

                                <div class="overflow-x-auto">

                                    <table class="w-full min-w-[650px] text-left">

                                        <thead
                                            class="border-b border-slate-100 bg-slate-50"
                                        >

                                            <tr>

                                                <th
                                                    class="px-4 py-3 text-[9px] font-semibold uppercase tracking-[0.14em] text-slate-500"
                                                >
                                                    Nama Pekerja
                                                </th>

                                                <th
                                                    class="w-28 px-4 py-3 text-center text-[9px] font-semibold uppercase tracking-[0.14em] text-slate-500"
                                                >
                                                    Status
                                                </th>

                                                <th
                                                    class="w-24 px-4 py-3 text-center text-[9px] font-semibold uppercase tracking-[0.14em] text-slate-500"
                                                >
                                                    Durasi
                                                </th>

                                                <th
                                                    class="px-4 py-3 text-[9px] font-semibold uppercase tracking-[0.14em] text-slate-500"
                                                >
                                                    Keterangan
                                                </th>

                                            </tr>

                                        </thead>


                                        <tbody class="divide-y divide-slate-100">

                                            @foreach($laporan->absensis as $absen)

                                                <tr
                                                    class="transition-colors hover:bg-slate-50/70"
                                                >

                                                    {{-- PEKERJA --}}
                                                    <td class="px-4 py-3">

                                                        <div
                                                            class="flex items-center gap-2.5"
                                                        >

                                                            <div
                                                                class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-[9px] font-semibold text-slate-500"
                                                            >
                                                                {{ strtoupper(substr($absen->pegawai->nama ?? 'P', 0, 2)) }}
                                                            </div>

                                                            <span
                                                                class="text-[11px] font-semibold text-slate-800"
                                                            >
                                                                {{ $absen->pegawai->nama }}
                                                            </span>

                                                        </div>

                                                    </td>


                                                    {{-- STATUS --}}
                                                    <td class="px-4 py-3 text-center">

                                                        @if($absen->status == 'Hadir')

                                                            <span
                                                                class="inline-flex items-center rounded-lg border border-emerald-100 bg-emerald-50 px-2.5 py-1 text-[9px] font-semibold text-emerald-700"
                                                            >
                                                                HADIR
                                                            </span>

                                                        @elseif($absen->status == 'Sakit')

                                                            <span
                                                                class="inline-flex items-center rounded-lg border border-amber-100 bg-amber-50 px-2.5 py-1 text-[9px] font-semibold text-amber-700"
                                                            >
                                                                SAKIT
                                                            </span>

                                                        @elseif($absen->status == 'Izin')

                                                            <span
                                                                class="inline-flex items-center rounded-lg border border-blue-100 bg-blue-50 px-2.5 py-1 text-[9px] font-semibold text-blue-700"
                                                            >
                                                                IZIN
                                                            </span>

                                                        @else

                                                            <span
                                                                class="inline-flex items-center rounded-lg border border-rose-100 bg-rose-50 px-2.5 py-1 text-[9px] font-semibold text-rose-700"
                                                            >
                                                                ALFA
                                                            </span>

                                                        @endif

                                                    </td>


                                                    {{-- DURASI --}}
                                                    <td
                                                        class="px-4 py-3 text-center text-[10px] font-medium text-slate-500"
                                                    >
                                                        {{ $absen->durasi ?? '-' }}
                                                    </td>


                                                    {{-- KETERANGAN --}}
                                                    <td
                                                        class="px-4 py-3 text-[10px] leading-4 text-slate-500"
                                                    >
                                                        {{ $absen->keterangan ?? '-' }}
                                                    </td>

                                                </tr>

                                            @endforeach

                                        </tbody>

                                    </table>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </details>

        @empty

            {{-- =================================================
                EMPTY STATE
            ================================================== --}}
            <div
                class="rounded-2xl border border-dashed border-slate-200 bg-slate-50/50 px-6 py-16 text-center"
            >

                <div
                    class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-300 shadow-sm"
                >
                    <svg
                        class="h-6 w-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.5"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                        />
                    </svg>
                </div>

                <p
                    class="text-[9px] font-semibold uppercase tracking-[0.16em] text-slate-400"
                >
                    Arsip Kosong
                </p>

                <h3
                    class="mt-2 text-[16px] font-semibold tracking-tight text-slate-800"
                >
                    Belum Ada Data Laporan
                </h3>

                <p
                    class="mx-auto mt-2 max-w-sm text-[11px] leading-5 text-slate-400"
                >
                    Tidak ditemukan arsip laporan harian dan absensi untuk bulan
                    {{ \Carbon\Carbon::parse($bulanFilter)->translatedFormat('F Y') }}.
                </p>

            </div>

        @endforelse

    </div>

</section>

@endsection