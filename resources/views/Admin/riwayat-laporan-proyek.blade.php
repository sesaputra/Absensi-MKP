@extends('layouts.admin')

@section('title', 'Buku Harian Lapangan')

@section('content')

@php
/*
|--------------------------------------------------------------------------
| DATA RINGKASAN
|--------------------------------------------------------------------------
*/
$jumlahLaporan = $riwayatLaporan->count();

$bulanLabel = isset($selectedMonth)
? \Carbon\Carbon::createFromFormat('Y-m', $selectedMonth)->translatedFormat('F Y')
: now()->translatedFormat('F Y');
@endphp


{{-- =========================================================
    HERO — BUKU HARIAN LAPANGAN
========================================================= --}}
<section
    class="relative isolate mb-8 overflow-hidden rounded-[30px] bg-slate-950 text-white shadow-xl shadow-slate-200/50">

    {{-- Background Foto Proyek --}}
    @if($proyek->gambar)

    <div
        class="absolute inset-0 -z-30 bg-cover bg-center opacity-[0.28]"
        style="background-image: url('{{ asset('storage/' . $proyek->gambar) }}');"></div>

    @else

    <div class="absolute inset-0 -z-30 bg-slate-950"></div>

    @endif


    {{-- Overlay Utama --}}
    <div
        class="absolute inset-0 -z-20 bg-gradient-to-br from-slate-950 via-slate-950/[0.94] to-blue-950/[0.92]"></div>


    {{-- Decorative Glow --}}
    <div
        class="absolute -right-24 -top-24 -z-10 h-80 w-80 rounded-full bg-blue-500/15 blur-3xl"></div>

    <div
        class="absolute -bottom-32 left-1/3 -z-10 h-72 w-72 rounded-full bg-indigo-500/10 blur-3xl"></div>


    {{-- Subtle Grid --}}
    <div
        class="absolute inset-0 -z-10 opacity-[0.035]"
        style="
            background-image:
                linear-gradient(rgba(255,255,255,.8) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.8) 1px, transparent 1px);
            background-size: 32px 32px;
        "></div>


    {{-- =====================================================
        HERO CONTENT
    ====================================================== --}}
    <div class="relative px-6 py-7 sm:px-8 lg:px-10 lg:py-9">


        {{-- =================================================
            TOP NAVIGATION
        ================================================== --}}
        <div
            class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            {{-- Tombol Kembali --}}
            <a
                href="{{ route('proyek.show', $proyek->id) }}"
                class="group inline-flex w-fit items-center gap-2.5 text-[11px] font-medium text-slate-300 transition-colors hover:text-white">

                <span
                    class="flex h-8 w-8 items-center justify-center rounded-xl border border-white/10 bg-white/[0.06] backdrop-blur-md transition-all duration-300 group-hover:-translate-x-0.5 group-hover:bg-white/[0.10]">
                    <svg
                        class="h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.7"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </span>

                Kembali ke Detail Proyek

            </a>


            {{-- Module Label --}}
            <div
                class="inline-flex w-fit items-center gap-2 rounded-full border border-white/10 bg-white/[0.06] px-3 py-1.5 backdrop-blur-md">
                <span
                    class="h-1.5 w-1.5 rounded-full bg-blue-400"></span>

                <span
                    class="text-[9px] font-medium uppercase tracking-[0.18em] text-slate-300">
                    Buku Harian Lapangan
                </span>
            </div>

        </div>



        {{-- =================================================
            PROJECT INFORMATION
        ================================================== --}}
        <div class="mt-8 max-w-3xl">

            {{-- Eyebrow --}}
            <p
                class="mb-3 text-[9px] font-medium uppercase tracking-[0.2em] text-blue-300">
                Monitoring Aktivitas Proyek
            </p>


            {{-- Project Name --}}
            <h1
                class="text-[28px] font-semibold leading-[1.08] tracking-[-0.035em] text-white sm:text-[32px] lg:text-[36px]">
                {{ $proyek->nama_proyek }}
            </h1>


            {{-- Location --}}
            <div
                class="mt-3 flex items-center gap-2 text-[12px] text-slate-300">

                <svg
                    class="h-4 w-4 shrink-0 text-blue-300"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.7"
                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.7"
                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>

                <span>
                    {{ $proyek->lokasi }}
                </span>

            </div>

        </div>



        {{-- =================================================
            HERO METRICS
        ================================================== --}}
        <div
            class="mt-8 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">

            {{-- =================================================
                PERIODE
            ================================================== --}}
            <div
                class="rounded-2xl border border-white/10 bg-white/[0.065] p-4 backdrop-blur-md">

                <p
                    class="text-[9px] font-medium uppercase tracking-[0.14em] text-slate-400">
                    Periode Laporan
                </p>

                <div class="mt-2 flex items-center gap-2">

                    <svg
                        class="h-4 w-4 text-blue-300"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.7"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>

                    <p
                        class="text-[15px] font-semibold tracking-[-0.02em] text-white">
                        {{ $bulanLabel }}
                    </p>

                </div>

            </div>



            {{-- =================================================
                JUMLAH LAPORAN
            ================================================== --}}
            <div
                class="rounded-2xl border border-white/10 bg-white/[0.065] p-4 backdrop-blur-md">

                <p
                    class="text-[9px] font-medium uppercase tracking-[0.14em] text-slate-400">
                    Catatan Bulan Ini
                </p>

                <div class="mt-2 flex items-baseline gap-2">

                    <span
                        class="text-[25px] font-semibold leading-none tracking-[-0.04em] text-white">
                        {{ $jumlahLaporan }}
                    </span>

                    <span
                        class="text-[11px] font-medium text-slate-400">
                        Hari laporan
                    </span>

                </div>

            </div>



            {{-- =================================================
                FILTER BULAN
            ================================================== --}}
            @if(isset($availableMonths) && $availableMonths->count() > 0)

            <div
                class="rounded-2xl border border-white/10 bg-white/[0.065] p-4 backdrop-blur-md">

                <p
                    class="mb-2 text-[9px] font-medium uppercase tracking-[0.14em] text-slate-400">
                    Pilih Periode
                </p>

                <form
                    method="GET"
                    action="{{ route('proyek.laporan.admin', $proyek->id) }}">

                    <div class="relative">

                        <select
                            name="bulan"
                            onchange="this.form.submit()"
                            class="w-full appearance-none rounded-xl border border-white/10 bg-white/[0.07] px-3 py-2.5 pr-9 text-[12px] font-medium text-white outline-none transition-all hover:bg-white/[0.10] focus:border-blue-400/50 focus:ring-2 focus:ring-blue-400/10">

                            @foreach($availableMonths as $monthYear)

                            <option
                                value="{{ $monthYear }}"
                                class="bg-slate-900 text-white"
                                {{ $selectedMonth == $monthYear ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::createFromFormat('Y-m', $monthYear)->translatedFormat('F Y') }}
                            </option>

                            @endforeach

                        </select>


                        <div
                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">

                            <svg
                                class="h-4 w-4 text-slate-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.7"
                                    d="M19 9l-7 7-7-7" />
                            </svg>

                        </div>

                    </div>

                </form>

            </div>

            @else

            {{-- Fallback --}}
            <div
                class="rounded-2xl border border-white/10 bg-white/[0.065] p-4 backdrop-blur-md">

                <p
                    class="text-[9px] font-medium uppercase tracking-[0.14em] text-slate-400">
                    Status Monitoring
                </p>

                <div class="mt-2 flex items-center gap-2">

                    <span
                        class="h-2 w-2 rounded-full bg-emerald-400"></span>

                    <p
                        class="text-[13px] font-semibold text-white">
                        Monitoring Aktif
                    </p>

                </div>

            </div>

            @endif

        </div>

    </div>

</section>



{{-- =========================================================
    TIMELINE LAPORAN
========================================================= --}}
<div class="max-w-5xl">

    @if($riwayatLaporan->count() > 0)

    {{-- =================================================
            TIMELINE
        ================================================== --}}
    <div
        class="relative ml-4 space-y-8 border-l-2 border-slate-200 pb-8 md:ml-6">

        @foreach($riwayatLaporan as $laporan)

        {{-- =================================================
                    TIMELINE ITEM
                ================================================== --}}
        <div
            class="group relative pl-8 md:pl-10">

            {{-- Timeline Dot --}}
            <div
                class="absolute -left-[9px] top-5 z-10 h-4 w-4 rounded-full border-[3px] border-blue-600 bg-white shadow-sm transition-all duration-300 group-hover:scale-125 group-hover:border-blue-400"></div>



            {{-- =================================================
                        REPORT CARD
                    ================================================== --}}
            <div
                class="mb-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition-all duration-300 hover:border-slate-300 hover:shadow-md">

                {{-- =================================================
                            CARD HEADER
                        ================================================== --}}
                <div
                    class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-100 bg-slate-50/70 px-5 py-4 md:px-6">

                    {{-- Reporter --}}
                    <div class="flex items-center gap-3">

                        <div class="relative">

                            {{-- Avatar --}}
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-tr from-slate-900 to-blue-900 text-sm font-semibold text-white shadow-sm">
                                {{ substr($laporan->pegawai->nama ?? 'A', 0, 1) }}
                            </div>

                            {{-- Online Indicator --}}
                            <div
                                class="absolute -bottom-0.5 -right-0.5 h-3.5 w-3.5 rounded-full border-2 border-white bg-emerald-500"></div>

                        </div>


                        <div>

                            <h3
                                class="text-[13px] font-semibold leading-none text-slate-800">
                                {{ $laporan->pegawai->nama ?? 'Sistem / Admin' }}
                            </h3>

                            <p
                                class="mt-1 text-[10px] font-medium text-slate-500">
                                {{ $laporan->pegawai->jabatan->nama_jabatan ?? 'Pengawas Lapangan' }}
                            </p>

                        </div>

                    </div>



                    {{-- Date --}}
                    <div
                        class="flex-shrink-0 text-right">

                        <p
                            class="mb-1 text-[13px] font-semibold leading-none text-slate-800">
                            {{ \Carbon\Carbon::parse($laporan->tanggal)->translatedFormat('d M Y') }}
                        </p>

                        <p
                            class="inline-flex items-center rounded-md bg-slate-100 px-2 py-1 text-[10px] font-medium text-slate-500">

                            <svg
                                class="mr-1 h-3 w-3"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.7"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>

                            {{ $laporan->created_at->format('H:i') }} WITA

                        </p>

                    </div>

                </div>



                {{-- =================================================
                            CARD BODY
                        ================================================== --}}
                <div class="p-5 md:p-6">


                    {{-- =================================================
                                1. JURNAL LAPANGAN
                            ================================================== --}}
                    <div
                        class="mb-6 flex gap-4 border-b border-slate-100 pb-6">

                        {{-- Icon --}}
                        <div
                            class="mt-1 hidden h-8 w-8 shrink-0 items-center justify-center rounded-full border border-slate-200 bg-slate-50 sm:flex">
                            <svg
                                class="h-4 w-4 text-slate-500"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.7"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>


                        <div class="flex-1">

                            <h4
                                class="mb-2 text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-400">
                                Jurnal Lapangan
                            </h4>


                            <div class="relative">

                                <div
                                    class="absolute bottom-0 left-0 top-0 w-1 rounded-full bg-slate-200"></div>

                                <p
                                    class="whitespace-pre-line pl-4 text-[13px] leading-6 text-slate-600">
                                    {{ $laporan->kegiatan ?: 'Tidak ada catatan kegiatan.' }}
                                </p>

                            </div>

                        </div>

                    </div>



                    {{-- =================================================
                                2. PROGRES FISIK
                            ================================================== --}}
                    <div
                        class="mb-6 flex gap-4">

                        {{-- Icon --}}
                        <div
                            class="mt-1 hidden h-8 w-8 shrink-0 items-center justify-center rounded-full border border-slate-200 bg-slate-50 sm:flex">

                            <svg
                                class="h-4 w-4 text-blue-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.7"
                                    d="M9 19V9a2 2 0 012-2h2a2 2 0 012 2v10m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2zM9 19V13a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2z" />
                            </svg>

                        </div>


                        <div class="flex-1">

                            <h4
                                class="mb-3 text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-400">
                                Pencapaian Fisik Proyek
                            </h4>


                            @php
                            $snapshot = $laporan->progres_snapshot
                            ? json_decode($laporan->progres_snapshot, true)
                            : [];
                            @endphp


                            <div
                                class="grid grid-cols-1 gap-3 sm:grid-cols-2">

                                @forelse($snapshot as $item)

                                <div
                                    class="rounded-xl border border-slate-100 bg-slate-50 p-3.5 transition-colors hover:border-slate-200 hover:bg-slate-100/70">

                                    <div
                                        class="mb-2 flex items-end justify-between gap-3">

                                        <span
                                            class="truncate pr-2 text-[12px] font-semibold text-slate-700"
                                            title="{{ $item['nama_pekerjaan'] }}">
                                            {{ $item['nama_pekerjaan'] }}
                                        </span>


                                        <span
                                            class="shrink-0 text-[12px] font-semibold {{ $item['progres'] > 0 ? 'text-blue-600' : 'text-slate-400' }}">
                                            {{ $item['progres'] }}%
                                        </span>

                                    </div>


                                    <div
                                        class="h-1.5 w-full overflow-hidden rounded-full bg-slate-200">

                                        <div
                                            class="h-1.5 rounded-full transition-all duration-500 {{ $item['progres'] > 0 ? 'bg-blue-600' : 'bg-slate-300' }}"
                                            style="width: {{ max(0, min(100, $item['progres'])) }}%"></div>

                                    </div>

                                </div>

                                @empty

                                <p
                                    class="pl-1 text-[11px] italic text-slate-400">
                                    Tidak ada catatan progres pada laporan hari ini.
                                </p>

                                @endforelse

                            </div>

                        </div>

                    </div>



                    {{-- =================================================
                                3. DOKUMENTASI VISUAL
                            ================================================== --}}
                    @if($laporan->foto_pagi || $laporan->foto_sore)

                    <div
                        class="mt-2 border-t border-slate-100 pt-5">

                        {{-- Section Header --}}
                        <div
                            class="mb-3 flex flex-wrap items-center justify-between gap-3">

                            <h4
                                class="text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-400">
                                Dokumentasi Visual
                            </h4>


                            {{-- GPS --}}
                            @if($laporan->latitude && $laporan->longitude)

                            <a
                                href="https://www.google.com/maps/search/?api=1&query={{ $laporan->latitude }},{{ $laporan->longitude }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-1.5 text-[10px] font-semibold text-slate-600 transition-colors hover:border-blue-200 hover:bg-blue-50 hover:text-blue-600">

                                <svg
                                    class="mr-1.5 h-3 w-3"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.7"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.7"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>

                                Buka Titik GPS

                            </a>

                            @endif

                        </div>



                        {{-- Photo Grid --}}
                        <div
                            class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                            {{-- Foto Pagi --}}
                            @if($laporan->foto_pagi)

                            <div
                                class="group/foto relative cursor-zoom-in overflow-hidden rounded-xl border border-slate-200 bg-slate-50 shadow-sm">

                                <div
                                    class="absolute left-2 top-2 z-10 rounded-md bg-black/60 px-2 py-1 text-[9px] font-semibold uppercase tracking-[0.12em] text-white backdrop-blur-md">
                                    Briefing Pagi
                                </div>


                                <img
                                    src="{{ asset('storage/' . $laporan->foto_pagi) }}"
                                    class="h-48 w-full object-cover transition-transform duration-500 group-hover/foto:scale-105"
                                    alt="Foto Briefing Pagi">

                            </div>

                            @endif



                            {{-- Foto Sore --}}
                            @if($laporan->foto_sore)

                            <div
                                class="group/foto relative cursor-zoom-in overflow-hidden rounded-xl border border-slate-200 bg-slate-50 shadow-sm">

                                <div
                                    class="absolute left-2 top-2 z-10 rounded-md bg-slate-950/75 px-2 py-1 text-[9px] font-semibold uppercase tracking-[0.12em] text-white backdrop-blur-md">
                                    Progres Sore
                                </div>


                                <img
                                    src="{{ asset('storage/' . $laporan->foto_sore) }}"
                                    class="h-48 w-full object-cover transition-transform duration-500 group-hover/foto:scale-105"
                                    alt="Foto Progres Sore">

                            </div>

                            @endif

                        </div>

                    </div>

                    @endif

                </div>

            </div>

        </div>

        @endforeach

    </div>


    @else

    {{-- =================================================
            EMPTY STATE
        ================================================== --}}
    <div
        class="max-w-2xl rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center shadow-sm sm:p-12">

        <div
            class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-2xl border border-slate-100 bg-slate-50">

            <svg
                class="h-8 w-8 text-slate-300"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24">
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.7"
                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>

        </div>


        <h3
            class="mb-1.5 text-[15px] font-semibold tracking-[-0.01em] text-slate-800">
            Belum Ada Catatan Lapangan
        </h3>


        <p
            class="mx-auto max-w-lg text-[12px] leading-6 text-slate-500">
            Pengawas lapangan belum mengirimkan laporan harian untuk
            bulan ini. Data akan otomatis muncul di sini setelah laporan
            dikirim.
        </p>

    </div>

    @endif

</div>

@endsection