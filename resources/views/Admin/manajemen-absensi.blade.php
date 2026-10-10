@extends('layouts.admin')

@section('title', 'Manajemen Absensi')

@section('content')

@php
$totalMenunggu = $draftLaporan->count();
$totalDisetujui = $laporanValid->count();

$isAdmin = auth()->check()
&& in_array(auth()->user()->role, ['admin', 'super_admin']);
@endphp

<div class="space-y-6">

    {{-- =========================================================
        PAGE HEADER / BANNER
    ========================================================== --}}
    <section class="relative isolate overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        {{-- Subtle Grid --}}
        <div
            class="absolute inset-0 -z-10 opacity-[0.025]"
            style="
                background-image:
                    linear-gradient(rgba(15,23,42,.8) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(15,23,42,.8) 1px, transparent 1px);
                background-size: 32px 32px;
            ">
        </div>

        <div class="relative px-5 py-6 sm:px-8 sm:py-8">

            <div class="flex flex-col gap-6">

                {{-- Heading --}}
                <div class="min-w-0">
                    <div class="mb-3 flex items-center gap-2.5">
                        <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                        <span class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-400">
                            Validasi Operasional
                        </span>
                    </div>

                    <h1 class="text-2xl font-semibold tracking-tight text-slate-900 sm:text-3xl">
                        Manajemen Absensi
                    </h1>

                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500 sm:text-[15px]">
                        Tinjau laporan harian, periksa kehadiran tim lapangan,
                        dan validasi aktivitas proyek dalam satu ruang kerja.
                    </p>
                </div>

                {{-- Summary --}}
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">

                    {{-- Menunggu --}}
                    <div class="rounded-xl border border-slate-200/80 bg-white/90 px-4 py-4">
                        <div class="flex items-center justify-between gap-2">
                            <p class="text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">
                                Menunggu Validasi
                            </p>
                            <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                        </div>

                        <p class="mt-2 text-2xl font-semibold tracking-tight text-slate-900">
                            {{ number_format($totalMenunggu) }}
                        </p>

                        <p class="mt-1 text-xs text-slate-500 sm:text-sm">
                            Laporan perlu diperiksa
                        </p>
                    </div>

                    {{-- Disetujui --}}
                    <div class="rounded-xl border border-slate-200/80 bg-white/90 px-4 py-4">
                        <div class="flex items-center justify-between gap-2">
                            <p class="text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">
                                Disetujui
                            </p>
                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        </div>

                        <p class="mt-2 text-2xl font-semibold tracking-tight text-slate-900">
                            {{ number_format($totalDisetujui) }}
                        </p>

                        <p class="mt-1 text-xs text-slate-500 sm:text-sm">
                            Laporan tervalidasi
                        </p>
                    </div>

                    {{-- Sistem --}}
                    <div class="rounded-xl border border-slate-200/80 bg-white/90 px-4 py-4">
                        <div class="flex items-center justify-between gap-2">
                            <p class="text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">
                                Sistem Operasional
                            </p>
                            <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                        </div>

                        <p class="mt-2 text-lg font-semibold tracking-tight text-slate-900">
                            Absensi Harian
                        </p>

                        <p class="mt-1 text-xs text-slate-500 sm:text-sm">
                            Monitoring laporan lapangan
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </section>


    {{-- =========================================================
        NOTIFICATION
    ========================================================== --}}
    @if(session('success'))
    <div class="flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3.5">
        <span class="mt-1 h-2 w-2 shrink-0 rounded-full bg-emerald-500"></span>
        <p class="text-sm font-medium text-emerald-800">
            {{ session('success') }}
        </p>
    </div>
    @endif

    @if(session('error'))
    <div class="flex items-start gap-3 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3.5">
        <span class="mt-1 h-2 w-2 shrink-0 rounded-full bg-rose-500"></span>
        <p class="text-sm font-medium text-rose-800">
            {{ session('error') }}
        </p>
    </div>
    @endif


    {{-- =========================================================
        PENDING VALIDATION
    ========================================================== --}}
    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        {{-- Section Header --}}
        <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <div>
                <div class="flex items-center gap-2.5">
                    <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                    <h2 class="text-base font-semibold tracking-tight text-slate-800 sm:text-lg">
                        Menunggu Validasi
                    </h2>
                </div>

                <p class="mt-1.5 text-sm leading-6 text-slate-500">
                    Periksa dokumentasi, catatan kegiatan, dan daftar kehadiran sebelum mengambil keputusan.
                </p>
            </div>

            <span class="inline-flex w-fit items-center rounded-full border border-amber-200 bg-amber-50 px-3 py-1.5 text-xs font-semibold text-amber-700">
                {{ number_format($totalMenunggu) }} laporan
            </span>
        </div>

        <div class="space-y-4 p-4 sm:p-6">

            @forelse($draftLaporan as $laporan)

            @php
            $tanggalLaporan = \Carbon\Carbon::parse($laporan->tanggal);
            $jumlahHadir = $laporan->absensis
            ->where('status', 'Hadir')
            ->count();

            $jumlahTidakHadir = $laporan->absensis
            ->whereIn('status', ['Sakit', 'Izin', 'Alfa', 'Alpa'])
            ->count();

            $detailId = 'detail-laporan-' . $laporan->id;
            @endphp

            <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white transition duration-200 hover:border-slate-300 hover:shadow-sm">

                {{-- Report Header --}}
                <div class="flex flex-col gap-4 p-4 sm:p-5 lg:flex-row lg:items-start lg:justify-between">

                    <div class="min-w-0 flex-1">

                        <div class="mb-3 flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center gap-2 rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">
                                <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                Menunggu Validasi
                            </span>

                            <span class="text-xs font-medium text-slate-400">
                                {{ $tanggalLaporan->translatedFormat('l, d F Y') }}
                            </span>
                        </div>

                        <h3 class="text-base font-semibold leading-6 tracking-tight text-slate-800 sm:text-lg">
                            {{ $laporan->proyek->nama_proyek ?? 'Proyek tidak tersedia' }}
                        </h3>

                        <div class="mt-2 flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-slate-500">

                            <span class="inline-flex items-center gap-1.5">
                                <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v13a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z" />
                                </svg>
                                {{ $tanggalLaporan->format('d/m/Y') }}
                            </span>

                            <span class="inline-flex items-center gap-1.5">
                                <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H2v-2a4 4 0 014-4h3m6-7a4 4 0 11-8 0 4 4 0 018 0zm5 3a3 3 0 10-2-5.2" />
                                </svg>
                                {{ $laporan->absensis->count() }} anggota
                            </span>

                            <span class="inline-flex items-center gap-1.5">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                {{ $jumlahHadir }} hadir
                            </span>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex shrink-0 flex-wrap gap-2 lg:justify-end">

                        <button
                            type="button"
                            onclick="toggleDetail('{{ $detailId }}', this)"
                            class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                            <svg class="h-4 w-4 transition-transform" data-chevron fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 9l-7 7-7-7" />
                            </svg>
                            Detail Laporan
                        </button>

                        @if($isAdmin)
                        <button
                            type="button"
                            onclick="konfirmasiAbsensi('setujui', '{{ $laporan->proyek_id }}', '{{ $tanggalLaporan->format('Y-m-d') }}')"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Setujui
                        </button>

                        <button
                            type="button"
                            onclick="konfirmasiAbsensi('tolak', '{{ $laporan->proyek_id }}', '{{ $tanggalLaporan->format('Y-m-d') }}')"
                            class="inline-flex items-center justify-center gap-2 rounded-xl border border-rose-200 bg-white px-3.5 py-2.5 text-sm font-semibold text-rose-600 transition hover:bg-rose-50">
                            Tolak
                        </button>
                        @endif

                    </div>
                </div>

                {{-- Expandable Details --}}
                <div id="{{ $detailId }}" class="hidden border-t border-slate-100 bg-slate-50/50">

                    <div class="space-y-5 p-4 sm:p-5">

                        {{-- Documentation --}}
                        <div>
                            <div class="mb-3 flex items-center gap-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                                <h4 class="text-sm font-semibold text-slate-800">
                                    Dokumentasi Lapangan
                                </h4>
                            </div>

                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                                {{-- Foto Pagi --}}
                                <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
                                    <div class="border-b border-slate-100 px-4 py-3">
                                        <p class="text-sm font-semibold text-slate-700">Dokumentasi Pagi</p>
                                    </div>

                                    @if($laporan->foto_pagi)
                                    <a href="{{ asset('storage/' . $laporan->foto_pagi) }}" target="_blank" rel="noopener noreferrer">
                                        <img
                                            src="{{ asset('storage/' . $laporan->foto_pagi) }}"
                                            alt="Dokumentasi pagi"
                                            loading="lazy"
                                            class="h-52 w-full bg-slate-100 object-cover transition hover:opacity-90">
                                    </a>
                                    @else
                                    <div class="flex h-40 items-center justify-center bg-slate-50 px-4 text-sm text-slate-400">
                                        Foto pagi belum tersedia
                                    </div>
                                    @endif
                                </div>

                                {{-- Foto Sore --}}
                                <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
                                    <div class="border-b border-slate-100 px-4 py-3">
                                        <p class="text-sm font-semibold text-slate-700">Dokumentasi Sore</p>
                                    </div>

                                    @if($laporan->foto_sore)
                                    <a href="{{ asset('storage/' . $laporan->foto_sore) }}" target="_blank" rel="noopener noreferrer">
                                        <img
                                            src="{{ asset('storage/' . $laporan->foto_sore) }}"
                                            alt="Dokumentasi sore"
                                            loading="lazy"
                                            class="h-52 w-full bg-slate-100 object-cover transition hover:opacity-90">
                                    </a>
                                    @else
                                    <div class="flex h-40 items-center justify-center bg-slate-50 px-4 text-sm text-slate-400">
                                        Foto sore belum tersedia
                                    </div>
                                    @endif
                                </div>

                            </div>
                        </div>

                        {{-- GPS --}}
                        @if($laporan->latitude && $laporan->longitude)
                        <div class="flex flex-col gap-3 rounded-xl border border-slate-200 bg-white p-4 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm font-semibold text-slate-700">Lokasi Absensi</p>
                                <p class="mt-1 break-all text-xs text-slate-500">
                                    {{ $laporan->latitude }}, {{ $laporan->longitude }}
                                </p>
                            </div>

                            <a
                                href="https://www.google.com/maps?q={{ $laporan->latitude }},{{ $laporan->longitude }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 21s7-4.4 7-11a7 7 0 10-14 0c0 6.6 7 11 7 11z" />
                                    <circle cx="12" cy="10" r="2.3" stroke-width="1.8" />
                                </svg>
                                Buka Google Maps
                            </a>
                        </div>
                        @endif

                        {{-- Journal --}}
                        <div class="rounded-xl border border-slate-200 bg-white p-4">
                            <div class="mb-2 flex items-center gap-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                <h4 class="text-sm font-semibold text-slate-800">Catatan Kegiatan</h4>
                            </div>

                            <p class="whitespace-pre-line text-sm leading-6 text-slate-600">{{ $laporan->kegiatan ?: 'Tidak ada catatan kegiatan.' }}</p>
                        </div>

                        {{-- Attendance List --}}
                        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">

                            <div class="flex flex-col gap-2 border-b border-slate-100 px-4 py-4 sm:flex-row sm:items-center sm:justify-between">
                                <h4 class="text-sm font-semibold text-slate-800">
                                    Rincian Kehadiran
                                </h4>

                                <div class="flex flex-wrap gap-2 text-xs font-semibold">
                                    <span class="rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-emerald-700">
                                        {{ $jumlahHadir }} Hadir
                                    </span>
                                    <span class="rounded-full border border-rose-200 bg-rose-50 px-2.5 py-1 text-rose-700">
                                        {{ $jumlahTidakHadir }} Tidak Hadir
                                    </span>
                                </div>
                            </div>

                            @if($laporan->absensis->count())
                            <div class="divide-y divide-slate-100">

                                @foreach($laporan->absensis as $absen)
                                @php
                                $statusAbsen = $absen->status ?? '-';

                                $statusClass = match ($statusAbsen) {
                                'Hadir' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
                                'Sakit', 'Izin', 'Alfa', 'Alpa' => 'border-amber-200 bg-amber-50 text-amber-700',
                                default => 'border-slate-200 bg-slate-50 text-slate-600',
                                };
                                @endphp

                                <div class="flex flex-col gap-3 px-4 py-3.5 sm:flex-row sm:items-center sm:justify-between">
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-slate-700">
                                            {{ $absen->pegawai->nama ?? 'Pegawai tidak tersedia' }}
                                        </p>

                                        <p class="mt-1 text-xs leading-5 text-slate-500">
                                            Durasi:
                                            {{ $absen->durasi ?? '-' }}
                                            @if(!empty($absen->keterangan))
                                            <span class="mx-1 text-slate-300">·</span>
                                            {{ $absen->keterangan }}
                                            @endif
                                        </p>
                                    </div>

                                    <span class="inline-flex w-fit items-center rounded-full border px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                        {{ $statusAbsen }}
                                    </span>
                                </div>
                                @endforeach

                            </div>
                            @else
                            <div class="px-4 py-8 text-center text-sm text-slate-400">
                                Belum ada rincian kehadiran.
                            </div>
                            @endif

                        </div>
                    </div>
                </div>
            </article>

            @empty

            {{-- Empty State --}}
            <div class="flex flex-col items-center justify-center rounded-2xl border border-dashed border-slate-200 bg-slate-50/50 px-6 py-12 text-center sm:py-16">

                <div class="flex h-14 w-14 items-center justify-center rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <svg class="h-7 w-7 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5-3v10a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2h12a2 2 0 012 2z" />
                    </svg>
                </div>

                <h3 class="mt-5 text-base font-semibold text-slate-800">
                    Tidak Ada Laporan Menunggu
                </h3>

                <p class="mt-2 max-w-sm text-sm leading-6 text-slate-500">
                    Semua laporan yang masuk telah diproses atau belum ada laporan baru dari lapangan.
                </p>
            </div>

            @endforelse

        </div>
    </section>


    {{-- =========================================================
        APPROVED REPORTS
    ========================================================== --}}
    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        {{-- Section Header --}}
        <div class="flex flex-col gap-4 border-b border-slate-100 px-5 py-5 sm:px-6 lg:flex-row lg:items-center lg:justify-between">

            <div>
                <div class="flex items-center gap-2.5">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    <h2 class="text-base font-semibold tracking-tight text-slate-800 sm:text-lg">
                        Laporan Disetujui
                    </h2>
                </div>

                <p class="mt-1.5 text-sm leading-6 text-slate-500">
                    Riwayat laporan yang telah divalidasi untuk pemantauan kehadiran.
                </p>
            </div>


            {{-- Date Filter --}}
            <form
                id="filterAbsensiForm"
                action="{{ route('admin.absensi.index') }}"
                method="GET"
                class="flex flex-col gap-2 sm:flex-row sm:items-end">

                <div>
                    <label
                        for="filter_tanggal"
                        class="mb-1.5 block text-xs font-semibold text-slate-500">
                        Filter Tanggal
                    </label>

                    <input
                        type="date"
                        id="filter_tanggal"
                        name="filter_tanggal"
                        value="{{ request('filter_tanggal') }}"
                        onchange="document.getElementById('filterAbsensiForm').requestSubmit()"
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-700 outline-none transition focus:border-amber-400 focus:ring-4 focus:ring-amber-100 sm:w-auto">
                </div>

                @if(request()->filled('filter_tanggal'))
                <a
                    href="{{ route('admin.absensi.index') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                    Reset
                </a>
                @endif

            </form>
        </div>

        {{-- Table --}}
        <div class="p-4 sm:p-6">

            @if($laporanValid->count())

            <div class="overflow-x-auto rounded-xl border border-slate-200">
                <table class="min-w-[700px] w-full divide-y divide-slate-100 text-left">

                    <thead class="bg-slate-50/80">
                        <tr>
                            <th class="px-4 py-3.5 text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">
                                Proyek
                            </th>
                            <th class="px-4 py-3.5 text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">
                                Pengawas
                            </th>
                            <th class="px-4 py-3.5 text-center text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">
                                Hadir
                            </th>
                            <th class="px-4 py-3.5 text-center text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">
                                Tidak Hadir
                            </th>
                            <th class="px-4 py-3.5 text-right text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 bg-white">

                        @foreach($laporanValid as $laporan)
                        @php
                        $jumlahHadirValid = $laporan->absensis
                        ->where('status', 'Hadir')
                        ->count();

                        $jumlahTidakHadirValid = $laporan->absensis
                        ->whereIn('status', ['Sakit', 'Izin', 'Alfa', 'Alpa'])
                        ->count();
                        @endphp

                        <tr class="transition hover:bg-slate-50/70">

                            <td class="px-4 py-4">
                                <p class="text-sm font-semibold text-slate-800">
                                    {{ $laporan->proyek->nama_proyek ?? 'Proyek tidak tersedia' }}
                                </p>
                                <p class="mt-1 text-xs text-slate-400">
                                    {{ \Carbon\Carbon::parse($laporan->tanggal)->translatedFormat('d F Y') }}
                                </p>
                            </td>

                            <td class="px-4 py-4">
                                <p class="text-sm font-medium text-slate-700">
                                    {{ $laporan->pegawai?->user?->name
            ?? $laporan->pegawai?->nama
            ?? 'Pengawas tidak ditemukan' }}
                                </p>
                            </td>

                            <td class="px-4 py-4 text-center">
                                <span class="inline-flex min-w-9 items-center justify-center rounded-lg border border-emerald-200 bg-emerald-50 px-2.5 py-1.5 text-xs font-semibold text-emerald-700">
                                    {{ $jumlahHadirValid }}
                                </span>
                            </td>

                            <td class="px-4 py-4 text-center">
                                <span class="inline-flex min-w-9 items-center justify-center rounded-lg border border-amber-200 bg-amber-50 px-2.5 py-1.5 text-xs font-semibold text-amber-700">
                                    {{ $jumlahTidakHadirValid }}
                                </span>
                            </td>

                            <td class="px-4 py-4 text-right">
                                <a
                                    href="{{ route('admin.absensi.detail', ['proyek' => $laporan->proyek_id]) }}"
                                    class="inline-flex items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 transition hover:border-slate-300 hover:bg-slate-50 hover:text-slate-900">
                                    Detail
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </td>

                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

            @else

            <div class="flex flex-col items-center justify-center rounded-2xl border border-dashed border-slate-200 bg-slate-50/50 px-6 py-12 text-center">

                <div class="flex h-12 w-12 items-center justify-center rounded-xl border border-slate-200 bg-white">
                    <svg class="h-6 w-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5-3v10a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2h12a2 2 0 012 2z" />
                    </svg>
                </div>

                <h3 class="mt-4 text-sm font-semibold text-slate-800">
                    Belum Ada Laporan Disetujui
                </h3>

                <p class="mt-1.5 text-sm leading-6 text-slate-500">
                    Laporan yang telah disetujui akan ditampilkan di bagian ini.
                </p>
            </div>

            @endif

        </div>
    </section>

</div>


{{-- =========================================================
    HIDDEN VALIDATION FORMS
========================================================= --}}
@if($isAdmin)

<form id="formSetujuiAbsensi" method="POST" class="hidden">
    @csrf
</form>

<form id="formTolakAbsensi" method="POST" class="hidden">
    @csrf
</form>

@endif


{{-- =========================================================
    SCRIPTS
========================================================= --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function toggleDetail(id, button) {
        const panel = document.getElementById(id);

        if (!panel) return;

        const willOpen = panel.classList.contains('hidden');
        panel.classList.toggle('hidden');

        const chevron = button.querySelector('[data-chevron]');

        if (chevron) {
            chevron.classList.toggle('rotate-180', willOpen);
        }

        button.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
    }

    @if($isAdmin)

    function konfirmasiAbsensi(aksi, proyek, tanggal) {
        const isSetujui = aksi === 'setujui';

        const form = document.getElementById(
            isSetujui ? 'formSetujuiAbsensi' : 'formTolakAbsensi'
        );

        const routeBase = isSetujui ?
            @json(route('admin.absensi.setujui', ['proyek' => '__PROYEK__', 'tanggal' => '__TANGGAL__'])) :
            @json(route('admin.absensi.tolak', ['proyek' => '__PROYEK__', 'tanggal' => '__TANGGAL__']));

        form.action = routeBase
            .replace('__PROYEK__', encodeURIComponent(proyek))
            .replace('__TANGGAL__', encodeURIComponent(tanggal));

        Swal.fire({
            title: isSetujui ? 'Setujui laporan absensi?' : 'Tolak laporan absensi?',
            text: isSetujui ?
                'Pastikan laporan kegiatan dan kehadiran telah diperiksa.' : 'Laporan untuk proyek dan tanggal ini akan ditolak.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: isSetujui ? 'Ya, setujui' : 'Ya, tolak',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            focusCancel: true,
            buttonsStyling: false,
            customClass: {
                popup: 'rounded-2xl',
                title: 'text-lg font-semibold text-slate-800',
                htmlContainer: 'text-sm text-slate-500',
                confirmButton: isSetujui ?
                    'rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white mx-1' : 'rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white mx-1',
                cancelButton: 'rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 mx-1'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }
    @endif

    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: @json(session('success')),
        confirmButtonText: 'Mengerti',
        buttonsStyling: false,
        customClass: {
            popup: 'rounded-2xl',
            title: 'text-lg font-semibold text-slate-800',
            htmlContainer: 'text-sm text-slate-500',
            confirmButton: 'rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white'
        }
    });
    @elseif(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Terjadi Kesalahan',
        text: @json(session('error')),
        confirmButtonText: 'Mengerti',
        buttonsStyling: false,
        customClass: {
            popup: 'rounded-2xl',
            title: 'text-lg font-semibold text-slate-800',
            htmlContainer: 'text-sm text-slate-500',
            confirmButton: 'rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white'
        }
    });
    @endif
</script>

@endsection