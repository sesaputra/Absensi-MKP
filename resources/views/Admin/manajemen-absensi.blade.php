@extends('layouts.admin')

@section('title', 'Manajemen Absensi & Laporan')

@section('content')

{{-- =========================================================
    HERO — MANAJEMEN ABSENSI & LAPORAN
========================================================= --}}

<section
    class="relative isolate overflow-hidden rounded-[30px] bg-slate-950 text-white shadow-xl shadow-slate-200/50 mb-6">

    <div
        class="absolute inset-0 -z-20 bg-cover bg-center opacity-[0.20]"
        style="background-image: url('{{ asset('images/flower-mkp.jpg') }}');">
    </div>

    <div
        class="absolute inset-0 -z-10 bg-gradient-to-br from-slate-950 via-slate-950/[0.96] to-blue-950/[0.92]">
    </div>

    <div
        class="absolute -right-24 -top-24 -z-10 h-72 w-72 rounded-full bg-blue-500/15 blur-3xl">
    </div>

    <div
        class="absolute -bottom-32 left-1/3 -z-10 h-72 w-72 rounded-full bg-indigo-500/10 blur-3xl">
    </div>

    <div
        class="absolute inset-0 -z-10 opacity-[0.035]"
        style="
            background-image:
                linear-gradient(rgba(255,255,255,.8) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.8) 1px, transparent 1px);
            background-size: 32px 32px;
        ">
    </div>

    <div class="relative px-6 py-8 sm:px-8 lg:px-10 lg:py-9">

        <div
            class="mb-4 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/[0.07] px-3 py-1.5 backdrop-blur-md">

            <span class="h-1.5 w-1.5 rounded-full bg-amber-400"></span>

            <span
                class="text-[9px] font-medium uppercase tracking-[0.18em] text-slate-300">
                Modul Validasi Operasional
            </span>

        </div>

        <h1
            class="max-w-3xl text-[28px] font-semibold leading-[1.08] tracking-[-0.035em] text-white sm:text-[32px] lg:text-[34px]">

            Manajemen Absensi

            <span class="text-blue-300">
                & Laporan Lapangan
            </span>

        </h1>

        <p
            class="mt-3 max-w-2xl text-[12px] leading-6 text-slate-300 sm:text-[13px]">

            Tinjau foto progres, lokasi GPS mandor, serta rekapitulasi
            kehadiran pekerja sebelum laporan divalidasi dan diproses
            ke tahap berikutnya.

        </p>

        <div class="mt-7 grid grid-cols-1 gap-3 sm:grid-cols-3">

            {{-- Draft --}}
            <div
                class="group rounded-2xl border border-white/10 bg-white/[0.065] p-4 backdrop-blur-md transition duration-300 hover:bg-white/[0.10]">

                <div class="flex items-center justify-between">

                    <p
                        class="text-[9px] font-medium uppercase tracking-[0.12em] text-slate-400">
                        Menunggu Validasi
                    </p>

                    <div
                        class="flex h-7 w-7 items-center justify-center rounded-lg border border-amber-400/10 bg-amber-400/[0.08]">

                        <svg
                            class="h-3.5 w-3.5 text-amber-300"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.7"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 8v4l2.5 2.5M21 12a9 9 0 1 1-18 0 9 9 0 0118 0Z" />

                        </svg>

                    </div>

                </div>

                <p
                    class="mt-3 text-[25px] font-semibold tracking-[-0.04em] text-white">

                    {{ count($draftLaporan) }}

                </p>

                <p class="mt-1 text-[10px] text-slate-400">
                    Draft laporan
                </p>

            </div>

            {{-- Laporan Valid --}}
            <div
                class="group rounded-2xl border border-white/10 bg-white/[0.065] p-4 backdrop-blur-md transition duration-300 hover:bg-white/[0.10]">

                <div class="flex items-center justify-between">

                    <p
                        class="text-[9px] font-medium uppercase tracking-[0.12em] text-slate-400">
                        Laporan Disetujui
                    </p>

                    <div
                        class="flex h-7 w-7 items-center justify-center rounded-lg border border-emerald-400/10 bg-emerald-400/[0.08]">

                        <svg
                            class="h-3.5 w-3.5 text-emerald-300"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.7"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="m5 12.5 4.5 4.5L19 7.5" />

                        </svg>

                    </div>

                </div>

                <p
                    class="mt-3 text-[25px] font-semibold tracking-[-0.04em] text-white">

                    {{ count($laporanValid) }}

                </p>

                <p class="mt-1 text-[10px] text-slate-400">
                    Laporan tervalidasi
                </p>

            </div>

            {{-- Status --}}
            <div
                class="group rounded-2xl border border-white/10 bg-white/[0.065] p-4 backdrop-blur-md transition duration-300 hover:bg-white/[0.10]">

                <div class="flex items-center justify-between">

                    <p
                        class="text-[9px] font-medium uppercase tracking-[0.12em] text-slate-400">
                        Status Sistem
                    </p>

                    <div
                        class="flex h-7 w-7 items-center justify-center rounded-lg border border-blue-400/10 bg-blue-400/[0.08]">

                        <svg
                            class="h-3.5 w-3.5 text-blue-300"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.7"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0118 0Z" />

                        </svg>

                    </div>

                </div>

                <p
                    class="mt-3 text-[20px] font-semibold tracking-[-0.03em] text-white">
                    Operasional
                </p>

                <p class="mt-1 text-[10px] text-slate-400">
                    Validasi berjalan normal
                </p>

            </div>

        </div>

    </div>

</section>


{{-- =========================================================
    BAGIAN 1: DRAFT LAPORAN
========================================================= --}}

<div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 lg:p-8 mb-6">

    <div
        class="mb-6 pb-4 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-2">

        <div>

            <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">

                <span>
                    Draft Laporan Menunggu Validasi
                </span>

                @if(count($draftLaporan) > 0)

                    <span
                        class="bg-amber-100 text-amber-800 text-xs font-bold px-2.5 py-0.5 rounded-full">

                        {{ count($draftLaporan) }}

                    </span>

                @endif

            </h2>

            <p class="text-xs text-slate-500 mt-1">
                Periksa foto lapangan dan daftar kehadiran sebelum menyetujui.
                Data yang disetujui akan masuk ke rekap gaji.
            </p>

        </div>

    </div>


    <div class="space-y-4">

        @forelse($draftLaporan as $laporan)

            @php

                $proyek = $laporan->proyek;

                $totalHadir = $laporan->absensis
                    ->where('status', 'Hadir')
                    ->count();

                $totalTidakHadir = $laporan->absensis
                    ->whereIn('status', ['Sakit', 'Izin', 'Alfa'])
                    ->count();

            @endphp


            <details
                class="group bg-white border border-slate-200/90 rounded-xl shadow-xs overflow-hidden transition-all duration-200 [&_summary::-webkit-details-marker]:hidden">


                {{-- =================================================
                    SUMMARY
                ================================================== --}}

                <summary
                    class="flex flex-col md:flex-row md:items-center justify-between p-4 cursor-pointer hover:bg-slate-50/80 transition-colors select-none">

                    <div class="flex items-center space-x-4 mb-3 md:mb-0">

                        <span
                            class="transition duration-300 group-open:-rotate-180 text-slate-400 bg-slate-100 border border-slate-200 rounded-full p-1 shrink-0">

                            <svg
                                class="w-4 h-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 9l-7 7-7-7">
                                </path>

                            </svg>

                        </span>


                        <div>

                            <h3 class="font-bold text-slate-800 text-base">

                                {{ $proyek->nama_proyek ?? 'Proyek Tanpa Nama' }}

                            </h3>

                            <p class="text-xs font-medium text-slate-500 mt-0.5">

                                {{ \Carbon\Carbon::parse($laporan->tanggal)->translatedFormat('l, d F Y') }}

                                •

                                <span class="text-slate-700 font-semibold">

                                    Mandor:
                                    {{ $laporan->pembuatLaporan->nama ?? '-' }}

                                </span>

                            </p>

                        </div>

                    </div>


                    <div
                        class="flex items-center justify-between md:justify-end w-full md:w-auto space-x-4">

                        <div class="flex space-x-2">

                            <span
                                class="text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200/60 px-2.5 py-1 rounded-lg">

                                {{ $totalHadir }} Hadir

                            </span>

                            @if($totalTidakHadir > 0)

                                <span
                                    class="text-xs font-semibold text-rose-700 bg-rose-50 border border-rose-200/60 px-2.5 py-1 rounded-lg">

                                    {{ $totalTidakHadir }} Absen

                                </span>

                            @endif

                        </div>


                        {{-- =================================================
                            ACTION BUTTONS
                        ================================================== --}}

                        <div
                            class="flex space-x-2"
                            onclick="event.stopPropagation();">

                            {{-- SETUJUI --}}
                            <form
                                action="{{ route('admin.absensi.setujui', ['proyek_id' => $proyek->id, 'tanggal' => $laporan->tanggal]) }}"
                                method="POST"
                                class="form-setujui">

                                @csrf

                                <button
                                    type="submit"
                                    class="btn-setujui flex items-center space-x-1.5 px-3 py-1.5 text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 rounded-lg border border-emerald-200 transition-colors shadow-2xs">

                                    <svg
                                        class="w-3.5 h-3.5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24">

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2.5"
                                            d="M5 13l4 4L19 7">
                                        </path>

                                    </svg>

                                    <span>
                                        Setujui
                                    </span>

                                </button>

                            </form>


                            {{-- TOLAK --}}
                            <form
                                action="{{ route('admin.absensi.tolak', ['proyek_id' => $proyek->id, 'tanggal' => $laporan->tanggal]) }}"
                                method="POST"
                                class="form-tolak">

                                @csrf

                                <button
                                    type="submit"
                                    class="btn-tolak flex items-center space-x-1.5 px-3 py-1.5 text-xs font-bold text-rose-700 bg-rose-50 hover:bg-rose-100 rounded-lg border border-rose-200 transition-colors shadow-2xs">

                                    <svg
                                        class="w-3.5 h-3.5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24">

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2.5"
                                            d="M6 18L18 6M6 6l12 12">
                                        </path>

                                    </svg>

                                    <span>
                                        Tolak
                                    </span>

                                </button>

                            </form>

                        </div>

                    </div>

                </summary>


                {{-- =================================================
                    DETAIL
                ================================================== --}}

                <div class="border-t border-slate-200/80 p-5 bg-slate-50/50">

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                        {{-- FOTO & LAPORAN --}}
                        <div class="lg:col-span-1 space-y-4">

                            <div
                                class="bg-white p-4 rounded-xl border border-slate-200 shadow-2xs">

                                <h4
                                    class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-3">

                                    Foto Lapangan

                                </h4>


                                <div class="space-y-3">

                                    {{-- FOTO PAGI --}}
                                    <div>

                                        <p
                                            class="text-[10px] font-bold text-slate-500 mb-1">

                                            FOTO PAGI / BRIEFING

                                        </p>

                                        @if($laporan->foto_pagi)

                                            <a
                                                href="{{ asset('storage/' . $laporan->foto_pagi) }}"
                                                target="_blank">

                                                <img
                                                    src="{{ asset('storage/' . $laporan->foto_pagi) }}"
                                                    class="w-full h-32 object-cover rounded-lg border border-slate-200 hover:opacity-90 transition-opacity"
                                                    alt="Foto Pagi">

                                            </a>

                                        @else

                                            <div
                                                class="w-full h-28 bg-slate-100 rounded-lg flex items-center justify-center text-xs text-slate-400 border border-slate-200/60">

                                                Tidak ada foto pagi

                                            </div>

                                        @endif

                                    </div>


                                    {{-- FOTO SORE --}}
                                    <div>

                                        <p
                                            class="text-[10px] font-bold text-slate-500 mb-1">

                                            FOTO SORE / PROGRES

                                        </p>

                                        @if($laporan->foto_sore)

                                            <a
                                                href="{{ asset('storage/' . $laporan->foto_sore) }}"
                                                target="_blank">

                                                <img
                                                    src="{{ asset('storage/' . $laporan->foto_sore) }}"
                                                    class="w-full h-32 object-cover rounded-lg border border-slate-200 hover:opacity-90 transition-opacity"
                                                    alt="Foto Sore">

                                            </a>

                                        @else

                                            <div
                                                class="w-full h-28 bg-slate-100 rounded-lg flex items-center justify-center text-xs text-slate-400 border border-slate-200/60">

                                                Belum ada foto sore

                                            </div>

                                        @endif

                                    </div>

                                </div>


                                {{-- GPS --}}
                                @if($laporan->latitude && $laporan->longitude)

                                    <a
                                        href="https://www.google.com/maps/search/?api=1&query={{ $laporan->latitude }},{{ $laporan->longitude }}"
                                        target="_blank"
                                        class="mt-4 flex items-center justify-center space-x-2 w-full bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold py-2 rounded-lg border border-slate-300/70 transition-colors">

                                        <svg
                                            class="w-4 h-4 text-slate-500"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24">

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                            </path>

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z">
                                            </path>

                                        </svg>

                                        <span>
                                            Cek Titik GPS Mandor
                                        </span>

                                    </a>

                                @endif

                            </div>


                            {{-- JURNAL --}}
                            <div
                                class="bg-white p-4 rounded-xl border border-slate-200 shadow-2xs">

                                <h4
                                    class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">

                                    Jurnal Harian

                                </h4>

                                <div
                                    class="text-xs text-slate-700 leading-relaxed whitespace-pre-line bg-slate-50 p-3 rounded-lg border border-slate-200/60">

                                    {{ $laporan->kegiatan ?? 'Mandor belum menulis jurnal kegiatan harian.' }}

                                </div>

                            </div>

                        </div>


                        {{-- DAFTAR PEGAWAI --}}
                        <div class="lg:col-span-2">

                            <h4
                                class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-3">

                                Daftar Kehadiran Pegawai

                            </h4>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

                                @foreach($laporan->absensis as $absen)

                                    <div
                                        class="bg-white p-3 rounded-xl border border-slate-200/80 flex justify-between items-center shadow-2xs">

                                        <div>

                                            <p class="text-xs font-bold text-slate-800">
                                                {{ $absen->pegawai->nama ?? 'Pegawai' }}
                                            </p>

                                            <p class="text-[11px] text-slate-500 font-medium">
                                                Durasi: {{ $absen->durasi ?? '-' }}
                                            </p>

                                            @if($absen->keterangan)

                                                <p class="text-[10px] text-amber-700 italic mt-0.5">
                                                    Note: {{ $absen->keterangan }}
                                                </p>

                                            @endif

                                        </div>

                                        <div>

                                            @if($absen->status == 'Hadir')

                                                <span
                                                    class="inline-block px-2 py-0.5 text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-md">
                                                    HADIR
                                                </span>

                                            @elseif($absen->status == 'Sakit')

                                                <span
                                                    class="inline-block px-2 py-0.5 text-[10px] font-bold text-amber-700 bg-amber-50 border border-amber-200 rounded-md">
                                                    SAKIT
                                                </span>

                                            @elseif($absen->status == 'Izin')

                                                <span
                                                    class="inline-block px-2 py-0.5 text-[10px] font-bold text-sky-700 bg-sky-50 border border-sky-200 rounded-md">
                                                    IZIN
                                                </span>

                                            @else

                                                <span
                                                    class="inline-block px-2 py-0.5 text-[10px] font-bold text-rose-700 bg-rose-50 border border-rose-200 rounded-md">
                                                    ALFA
                                                </span>

                                            @endif

                                        </div>

                                    </div>

                                @endforeach

                            </div>

                        </div>

                    </div>

                </div>

            </details>

        @empty

            <div
                class="text-center py-10 bg-slate-50/60 rounded-xl border border-dashed border-slate-300">

                <svg
                    class="w-10 h-10 text-slate-300 mx-auto mb-2"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.5"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>

                </svg>

                <p class="text-slate-600 text-sm font-semibold">
                    Semua Draft Terproses!
                </p>

                <p class="text-slate-400 text-xs mt-1">
                    Tidak ada draft absensi atau laporan yang menunggu validasi saat ini.
                </p>

            </div>

        @endforelse

    </div>

</div>


{{-- =========================================================
    BAGIAN 2: LAPORAN DISETUJUI
========================================================= --}}

<div
    class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 lg:p-8">

    <div
        class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4 pb-4 border-b border-slate-100">

        <div>

            <h2 class="text-lg font-bold text-slate-800">
                Laporan Proyek Disetujui
            </h2>

            <p class="text-xs text-slate-500 mt-1">
                Data absensi dan progres harian yang sudah sah dan siap diproses ke penggajian.
            </p>

        </div>


        <form
            action="{{ route('admin.absensi.index') }}"
            method="GET"
            class="flex items-center space-x-3 bg-slate-50 p-1.5 rounded-xl border border-slate-200/80">

            <label
                for="filter_tanggal"
                class="text-xs font-semibold text-slate-600 pl-2">

                Tanggal:

            </label>

            <input
                type="date"
                id="filter_tanggal"
                name="filter_tanggal"
                value="{{ $tanggalFilter }}"
                onchange="this.form.submit()"
                class="rounded-lg border-slate-200 py-1.5 px-3 text-xs text-slate-700 font-semibold shadow-2xs focus:ring-2 focus:ring-slate-400 outline-none">

        </form>

    </div>


    <div class="overflow-x-auto">

        <table class="w-full text-left border-collapse min-w-[700px]">

            <thead>

                <tr
                    class="border-b border-slate-200 text-xs font-bold text-slate-400 uppercase tracking-wider bg-slate-50/80">

                    <th class="py-3 px-4 rounded-l-lg">
                        Nama Proyek
                    </th>

                    <th class="py-3 px-4">
                        Pengawas
                    </th>

                    <th class="py-3 px-4 text-center">
                        Tukang Hadir
                    </th>

                    <th class="py-3 px-4 text-center">
                        Tidak Hadir
                    </th>

                    <th class="py-3 px-4 text-right rounded-r-lg">
                        Aksi
                    </th>

                </tr>

            </thead>


            <tbody
                class="text-xs text-slate-700 divide-y divide-slate-100">

                @forelse($laporanValid as $laporan)

                    @php

                        $proyek = $laporan->proyek;

                        $totalHadir = $laporan->absensis
                            ->where('status', 'Hadir')
                            ->count();

                        $totalTidakHadir = $laporan->absensis
                            ->whereIn('status', ['Sakit', 'Izin', 'Alfa'])
                            ->count();

                    @endphp


                    <tr class="hover:bg-slate-50/80 transition-colors">

                        <td class="py-3.5 px-4 font-bold text-slate-800">
                            {{ $proyek->nama_proyek ?? '-' }}
                        </td>

                        <td class="py-3.5 px-4 text-slate-600 font-medium">
                            {{ $laporan->pembuatLaporan->nama ?? '-' }}
                        </td>

                        <td class="py-3.5 px-4 text-center">

                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/60">

                                {{ $totalHadir }} Orang

                            </span>

                        </td>

                        <td class="py-3.5 px-4 text-center">

                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">

                                {{ $totalTidakHadir }} Orang

                            </span>

                        </td>

                        <td class="py-3.5 px-4 text-right">

                            <a
                                href="{{ route('admin.absensi.detail', $proyek->id) }}"
                                class="inline-flex items-center space-x-1.5 px-3 py-1.5 text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors border border-slate-300/70 font-semibold text-xs">

                                <span>
                                    Buka Riwayat
                                </span>

                                <svg
                                    class="w-3.5 h-3.5 text-slate-500"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3">
                                    </path>

                                </svg>

                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="5"
                            class="py-10 text-center text-slate-400">

                            Tidak ada laporan proyek yang disetujui pada tanggal

                            <strong class="text-slate-600">

                                {{ \Carbon\Carbon::parse($tanggalFilter)->translatedFormat('d F Y') }}

                            </strong>.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>


{{-- =========================================================
    SWEETALERT2
========================================================= --}}

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        /*
        |--------------------------------------------------------------------------
        | KONFIGURASI DASAR
        |--------------------------------------------------------------------------
        */

        const swalConfig = {
            customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'rounded-xl px-5 py-2.5 text-sm font-semibold',
                cancelButton: 'rounded-xl px-5 py-2.5 text-sm font-semibold'
            },
            buttonsStyling: true
        };


        /*
        |--------------------------------------------------------------------------
        | KONFIRMASI SETUJUI
        |--------------------------------------------------------------------------
        */

        document.querySelectorAll('.form-setujui').forEach(function (form) {

            form.addEventListener('submit', function (event) {

                event.preventDefault();

                const button = form.querySelector('.btn-setujui');

                Swal.fire({
                    ...swalConfig,

                    icon: 'question',

                    title: 'Setujui Laporan?',

                    html: `
                        <div class="text-sm text-slate-500 leading-relaxed">
                            Laporan absensi dan progres ini akan
                            <strong class="text-slate-700">
                                disahkan
                            </strong>
                            dan dapat digunakan untuk proses penggajian.
                        </div>
                    `,

                    showCancelButton: true,

                    confirmButtonText: 'Ya, Setujui',

                    cancelButtonText: 'Batal',

                    confirmButtonColor: '#059669',

                    cancelButtonColor: '#64748b',

                    reverseButtons: true,

                    focusCancel: true

                }).then(function (result) {

                    if (result.isConfirmed) {

                        /*
                        | Disable tombol agar tidak double submit
                        */

                        button.disabled = true;

                        button.classList.add(
                            'opacity-70',
                            'cursor-not-allowed'
                        );

                        button.innerHTML = `
                            <svg
                                class="w-3.5 h-3.5 animate-spin"
                                fill="none"
                                viewBox="0 0 24 24">

                                <circle
                                    class="opacity-25"
                                    cx="12"
                                    cy="12"
                                    r="10"
                                    stroke="currentColor"
                                    stroke-width="4">
                                </circle>

                                <path
                                    class="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                                </path>

                            </svg>

                            <span>
                                Memproses...
                            </span>
                        `;

                        form.submit();
                    }

                });

            });

        });


        /*
        |--------------------------------------------------------------------------
        | KONFIRMASI TOLAK
        |--------------------------------------------------------------------------
        */

        document.querySelectorAll('.form-tolak').forEach(function (form) {

            form.addEventListener('submit', function (event) {

                event.preventDefault();

                const button = form.querySelector('.btn-tolak');

                Swal.fire({
                    ...swalConfig,

                    icon: 'warning',

                    title: 'Tolak Laporan?',

                    html: `
                        <div class="text-sm text-slate-500 leading-relaxed">
                            Laporan ini akan ditandai sebagai
                            <strong class="text-rose-600">
                                ditolak
                            </strong>
                            dan mandor perlu melakukan perbaikan.
                        </div>
                    `,

                    showCancelButton: true,

                    confirmButtonText: 'Ya, Tolak',

                    cancelButtonText: 'Batal',

                    confirmButtonColor: '#e11d48',

                    cancelButtonColor: '#64748b',

                    reverseButtons: true,

                    focusCancel: true

                }).then(function (result) {

                    if (result.isConfirmed) {

                        button.disabled = true;

                        button.classList.add(
                            'opacity-70',
                            'cursor-not-allowed'
                        );

                        button.innerHTML = `
                            <svg
                                class="w-3.5 h-3.5 animate-spin"
                                fill="none"
                                viewBox="0 0 24 24">

                                <circle
                                    class="opacity-25"
                                    cx="12"
                                    cy="12"
                                    r="10"
                                    stroke="currentColor"
                                    stroke-width="4">
                                </circle>

                                <path
                                    class="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                                </path>

                            </svg>

                            <span>
                                Memproses...
                            </span>
                        `;

                        form.submit();
                    }

                });

            });

        });


        /*
        |--------------------------------------------------------------------------
        | NOTIFIKASI BERHASIL
        |--------------------------------------------------------------------------
        */

        @if(session('success'))

            Swal.fire({
                icon: 'success',

                title: 'Berhasil',

                text: @json(session('success')),

                confirmButtonText: 'Selesai',

                confirmButtonColor: '#0f172a',

                timer: 3200,

                timerProgressBar: true,

                showConfirmButton: false,

                customClass: {
                    popup: 'rounded-2xl'
                }

            });

        @endif


        /*
        |--------------------------------------------------------------------------
        | NOTIFIKASI ERROR
        |--------------------------------------------------------------------------
        */

        @if(session('error'))

            Swal.fire({
                icon: 'error',

                title: 'Tidak Berhasil',

                text: @json(session('error')),

                confirmButtonText: 'Mengerti',

                confirmButtonColor: '#e11d48',

                customClass: {
                    popup: 'rounded-2xl'
                }

            });

        @endif

    });
</script>

@endsection