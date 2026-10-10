@extends('layouts.admin')

@section('title', 'Detail Absensi & Laporan Proyek')

@section('content')

@php
    $totalLaporan = $riwayatLaporan->count();
    $laporanValid = $riwayatLaporan->where('status_validasi', 'Disetujui')->count();
    $laporanPending = $totalLaporan - $laporanValid;
@endphp

<div class="space-y-6">

    {{-- =====================================================
        PAGE HEADER / BANNER
    ====================================================== --}}
    <section class="relative isolate overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        @if($proyek->gambar)
            <div
                class="absolute inset-0 -z-20 bg-cover bg-center opacity-[0.07]"
                style="background-image: url('{{ asset('storage/' . $proyek->gambar) }}');">
            </div>
        @else
            <div
                class="absolute inset-0 -z-20 bg-cover bg-center opacity-[0.06]"
                style="background-image: url('{{ asset('images/flower-mkp.jpg') }}');">
            </div>
        @endif

        <div class="absolute inset-0 -z-10 bg-white/90"></div>

        <div
            class="absolute inset-0 -z-10 opacity-[0.015]"
            style="background-image: linear-gradient(rgba(15,23,42,.8) 1px, transparent 1px), linear-gradient(90deg, rgba(15,23,42,.8) 1px, transparent 1px); background-size: 32px 32px;">
        </div>

        <div class="relative px-5 py-6 sm:px-8 sm:py-8">

            {{-- Breadcrumb dan tombol kembali --}}
            <div class="mb-6 flex flex-wrap items-center justify-between gap-3">

                <nav class="flex flex-wrap items-center gap-2 text-xs text-slate-400">
                    <a
                        href="{{ route('admin.absensi.index') }}"
                        class="font-medium transition hover:text-slate-800">
                        Manajemen Absensi
                    </a>

                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="1.8" d="m9 18 6-6-6-6" />
                    </svg>

                    <span class="font-semibold text-slate-700">
                        Detail Proyek
                    </span>
                </nav>

                <a
                    href="{{ route('admin.absensi.index') }}"
                    class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm font-semibold text-slate-600 shadow-sm transition hover:border-slate-300 hover:bg-slate-50 hover:text-slate-900">

                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="1.8" d="M10 19l-7-7m0 0 7-7m-7 7h18" />
                    </svg>

                    Kembali
                </a>
            </div>

            {{-- Heading --}}
            <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">

                <div class="min-w-0">
                    <div class="mb-3 flex items-center gap-2.5">
                        <span class="h-2 w-2 rounded-full bg-amber-500"></span>

                        <span class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-400">
                            Administrasi & Monitoring Lapangan
                        </span>
                    </div>

                    <h1 class="break-words text-2xl font-semibold tracking-tight text-slate-900 sm:text-3xl">
                        {{ $proyek->nama_proyek }}
                    </h1>

                    <div class="mt-3 flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-slate-500">

                        <p class="flex items-center gap-2">
                            <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="M17.657 16.657 13.414 20.9a2 2 0 0 1-2.828 0l-4.243-4.243a8 8 0 1 1 11.314 0Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="M15 11a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>

                            {{ $proyek->lokasi ?: 'Lokasi belum ditentukan' }}
                        </p>

                        <span class="hidden h-1 w-1 rounded-full bg-slate-300 sm:block"></span>

                        <p class="text-sm leading-6 text-slate-500">
                            Dokumentasi harian, lokasi pengawas, dan rekap kehadiran pekerja.
                        </p>
                    </div>
                </div>

                <div class="inline-flex w-fit shrink-0 items-center gap-2 rounded-xl border border-amber-200 bg-amber-50 px-3.5 py-2.5">
                    <svg class="h-4 w-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>

                    <span class="text-xs font-semibold text-amber-800">
                        Monitoring Absensi
                    </span>
                </div>
            </div>

            {{-- Statistics --}}
            <div class="mt-7 grid grid-cols-2 gap-3 sm:grid-cols-4">

                {{-- Total laporan --}}
                <div class="rounded-xl border border-slate-200/80 bg-white/90 px-4 py-4 backdrop-blur-sm">
                    <div class="flex items-center justify-between gap-2">
                        <p class="text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">
                            Total Laporan
                        </p>
                        <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                    </div>

                    <p class="mt-2 text-xl font-semibold tracking-tight text-slate-900 sm:text-2xl">
                        {{ number_format($totalLaporan) }}
                    </p>

                    <p class="mt-1 text-xs leading-5 text-slate-500 sm:text-sm">
                        Laporan harian
                    </p>
                </div>

                {{-- Laporan disetujui --}}
                <div class="rounded-xl border border-slate-200/80 bg-white/90 px-4 py-4 backdrop-blur-sm">
                    <div class="flex items-center justify-between gap-2">
                        <p class="text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">
                            Disetujui
                        </p>
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    </div>

                    <p class="mt-2 text-xl font-semibold tracking-tight text-slate-900 sm:text-2xl">
                        {{ number_format($laporanValid) }}
                    </p>

                    <p class="mt-1 text-xs leading-5 text-slate-500 sm:text-sm">
                        Laporan tervalidasi
                    </p>
                </div>

                {{-- Menunggu validasi --}}
                <div class="rounded-xl border border-slate-200/80 bg-white/90 px-4 py-4 backdrop-blur-sm">
                    <div class="flex items-center justify-between gap-2">
                        <p class="text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">
                            Menunggu
                        </p>
                        <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                    </div>

                    <p class="mt-2 text-xl font-semibold tracking-tight text-slate-900 sm:text-2xl">
                        {{ number_format($laporanPending) }}
                    </p>

                    <p class="mt-1 text-xs leading-5 text-slate-500 sm:text-sm">
                        Belum disetujui
                    </p>
                </div>

                {{-- Periode --}}
                <div class="rounded-xl border border-slate-200/80 bg-white/90 px-4 py-4 backdrop-blur-sm">
                    <div class="flex items-center justify-between gap-2">
                        <p class="text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">
                            Periode
                        </p>
                        <span class="h-2 w-2 rounded-full bg-violet-500"></span>
                    </div>

                    <p class="mt-2 text-base font-semibold tracking-tight text-slate-900 sm:text-lg">
                        {{ \Carbon\Carbon::parse($bulanFilter)->translatedFormat('F') }}
                    </p>

                    <p class="mt-1 text-xs leading-5 text-slate-500 sm:text-sm">
                        {{ \Carbon\Carbon::parse($bulanFilter)->format('Y') }}
                    </p>
                </div>

            </div>
        </div>
    </section>


    {{-- =====================================================
        ARSIP LAPORAN HARIAN
    ====================================================== --}}
    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        {{-- Section Header --}}
        <div class="flex flex-col gap-4 border-b border-slate-100 px-5 py-5 sm:px-6 lg:flex-row lg:items-center lg:justify-between">

            <div>
                <div class="flex items-center gap-2.5">
                    <span class="h-2 w-2 rounded-full bg-blue-500"></span>

                    <h2 class="text-base font-semibold tracking-tight text-slate-800 sm:text-lg">
                        Arsip Laporan Harian
                    </h2>
                </div>

                <p class="mt-1.5 text-sm leading-6 text-slate-500">
                    Tinjau dokumentasi proyek, identitas pengawas, dan kehadiran pekerja berdasarkan tanggal.
                </p>
            </div>

            {{-- Filter bulan --}}
            <form
                action="{{ route('admin.absensi.detail', $proyek->id) }}"
                method="GET"
                class="flex flex-col gap-2 sm:flex-row sm:items-center">

                <label for="filter_bulan" class="text-sm font-medium text-slate-600">
                    Periode laporan
                </label>

                <input
                    type="month"
                    id="filter_bulan"
                    name="filter_bulan"
                    value="{{ $bulanFilter }}"
                    onchange="this.form.submit()"
                    class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-3 text-sm text-slate-700 outline-none transition focus:border-amber-400 focus:ring-4 focus:ring-amber-100 sm:w-auto">
            </form>
        </div>

        <div class="p-4 sm:p-6">

            {{-- Ringkasan arsip --}}
            <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h3 class="text-sm font-semibold text-slate-800">
                        Riwayat Dokumentasi
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        Periode {{ \Carbon\Carbon::parse($bulanFilter)->translatedFormat('F Y') }}
                    </p>
                </div>

                <span class="inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-600">
                    {{ number_format($totalLaporan) }} laporan
                </span>
            </div>

            <div class="space-y-3">

                @forelse($riwayatLaporan as $laporan)

                    @php
                        $hadir = $laporan->absensis->where('status', 'Hadir')->count();
                        $absen = $laporan->absensis->whereIn('status', ['Sakit', 'Izin', 'Alfa'])->count();
                        $isValid = $laporan->status_validasi === 'Disetujui';

                        $namaPengawas = $laporan->pegawai?->user?->name
                            ?? $laporan->pegawai?->nama
                            ?? 'Pengawas tidak ditemukan';
                    @endphp

                    <details
                        class="group overflow-hidden rounded-xl border border-slate-200 bg-white transition hover:border-slate-300">

                        {{-- Accordion header --}}
                        <summary class="flex cursor-pointer list-none flex-col gap-4 p-4 transition hover:bg-slate-50/80 sm:flex-row sm:items-center sm:justify-between sm:px-5 [&::-webkit-details-marker]:hidden">

                            <div class="flex min-w-0 items-start gap-3">

                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-slate-50 text-slate-500 transition group-open:border-slate-800 group-open:bg-slate-900 group-open:text-white">
                                    <svg class="h-4 w-4 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="1.8" d="m6 9 6 6 6-6" />
                                    </svg>
                                </div>

                                <div class="min-w-0">
                                    <h3 class="text-sm font-semibold leading-5 text-slate-800">
                                        {{ \Carbon\Carbon::parse($laporan->tanggal)->translatedFormat('l, d F Y') }}
                                    </h3>

                                    <div class="mt-1.5 flex flex-wrap items-center gap-x-2 gap-y-1 text-xs text-slate-500">
                                        <span>Pengawas:</span>

                                        <span class="font-semibold text-slate-700">
                                            {{ $namaPengawas }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-wrap items-center gap-2 pl-[52px] sm:pl-0">

                                <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                    {{ $hadir }} Hadir
                                </span>

                                @if($absen > 0)
                                    <span class="inline-flex items-center gap-1.5 rounded-full border border-rose-200 bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                                        {{ $absen }} Absen
                                    </span>
                                @endif

                                @if($isValid)
                                    <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700">
                                        Disetujui
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full border border-amber-200 bg-amber-50 px-3 py-1.5 text-xs font-semibold text-amber-800">
                                        Menunggu
                                    </span>
                                @endif
                            </div>
                        </summary>

                        {{-- Accordion body --}}
                        <div class="border-t border-slate-100 bg-slate-50/50 p-4 sm:p-5">

                            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

                                {{-- Dokumentasi --}}
                                <div class="space-y-5">

                                    <div>
                                        <h4 class="text-sm font-semibold text-slate-800">
                                            Dokumentasi Proyek
                                        </h4>

                                        <p class="mt-1 text-sm leading-5 text-slate-500">
                                            Foto aktivitas lapangan dan catatan kegiatan.
                                        </p>
                                    </div>

                                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">

                                        {{-- Foto pagi --}}
                                        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white p-2">
                                            <p class="mb-2 text-xs font-semibold text-slate-500">
                                                Foto Pagi
                                            </p>

                                            @if($laporan->foto_pagi)
                                                <a
                                                    href="{{ asset('storage/' . $laporan->foto_pagi) }}"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    aria-label="Buka foto pagi di tab baru">

                                                    <img
                                                        src="{{ asset('storage/' . $laporan->foto_pagi) }}"
                                                        alt="Dokumentasi pagi {{ $proyek->nama_proyek }}"
                                                        loading="lazy"
                                                        class="h-36 w-full rounded-lg object-cover transition duration-200 hover:opacity-90 sm:h-32">
                                                </a>

                                                <p class="mt-2 text-xs text-slate-400">
                                                    Klik foto untuk memperbesar.
                                                </p>
                                            @else
                                                <div class="flex h-36 flex-col items-center justify-center rounded-lg border border-dashed border-slate-200 bg-slate-50 text-slate-400 sm:h-32">
                                                    <svg class="h-6 w-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1.5"
                                                            d="M4 16l4.586-4.586a2 2 0 0 1 2.828 0L16 16m-2-2 1.586-1.586a2 2 0 0 1 2.828 0L20 14m-6-6h.01M6 20h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2Z" />
                                                    </svg>

                                                    <span class="mt-2 text-xs">
                                                        Foto tidak tersedia
                                                    </span>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Foto sore --}}
                                        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white p-2">
                                            <p class="mb-2 text-xs font-semibold text-slate-500">
                                                Foto Sore
                                            </p>

                                            @if($laporan->foto_sore)
                                                <a
                                                    href="{{ asset('storage/' . $laporan->foto_sore) }}"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    aria-label="Buka foto sore di tab baru">

                                                    <img
                                                        src="{{ asset('storage/' . $laporan->foto_sore) }}"
                                                        alt="Dokumentasi sore {{ $proyek->nama_proyek }}"
                                                        loading="lazy"
                                                        class="h-36 w-full rounded-lg object-cover transition duration-200 hover:opacity-90 sm:h-32">
                                                </a>

                                                <p class="mt-2 text-xs text-slate-400">
                                                    Klik foto untuk memperbesar.
                                                </p>
                                            @else
                                                <div class="flex h-36 flex-col items-center justify-center rounded-lg border border-dashed border-slate-200 bg-slate-50 text-slate-400 sm:h-32">
                                                    <svg class="h-6 w-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1.5"
                                                            d="M4 16l4.586-4.586a2 2 0 0 1 2.828 0L16 16m-2-2 1.586-1.586a2 2 0 0 1 2.828 0L20 14m-6-6h.01M6 20h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2Z" />
                                                    </svg>

                                                    <span class="mt-2 text-xs">
                                                        Foto tidak tersedia
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Catatan kegiatan --}}
                                    <div>
                                        <h4 class="mb-2 text-sm font-semibold text-slate-800">
                                            Catatan Kegiatan
                                        </h4>

                                        @if($laporan->kegiatan)
                                            <div class="whitespace-pre-line rounded-xl border border-slate-200 bg-white p-4 text-sm leading-6 text-slate-600">{{ $laporan->kegiatan }}</div>
                                        @else
                                            <div class="rounded-xl border border-dashed border-slate-200 bg-white px-4 py-5 text-sm text-slate-400">
                                                Belum ada catatan kegiatan.
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Rekap kehadiran --}}
                                <div class="lg:col-span-2">

                                    <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                                        <div>
                                            <h4 class="text-sm font-semibold text-slate-800">
                                                Daftar Kehadiran Pekerja
                                            </h4>

                                            <p class="mt-1 text-sm text-slate-500">
                                                Rekap status kehadiran pada tanggal laporan.
                                            </p>
                                        </div>

                                        <span class="text-xs font-medium text-slate-400">
                                            {{ $laporan->absensis->count() }} data absensi
                                        </span>
                                    </div>

                                    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
                                        <div class="overflow-x-auto">
                                            <table class="min-w-[620px] w-full divide-y divide-slate-200">

                                                <thead class="bg-slate-50">
                                                    <tr>
                                                        <th class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">
                                                            Nama Pekerja
                                                        </th>

                                                        <th class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">
                                                            Status
                                                        </th>

                                                        <th class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">
                                                            Durasi
                                                        </th>

                                                        <th class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">
                                                            Keterangan
                                                        </th>
                                                    </tr>
                                                </thead>

                                                <tbody class="divide-y divide-slate-100">

                                                    @forelse($laporan->absensis as $absen)
                                                        @php
                                                            $namaPekerja = $absen->pegawai?->nama ?? 'Pegawai tidak ditemukan';
                                                            $statusAbsen = $absen->status;
                                                        @endphp

                                                        <tr class="transition hover:bg-slate-50/80">

                                                            <td class="whitespace-nowrap px-4 py-3.5">
                                                                <div class="flex items-center gap-3">
                                                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-slate-50 text-xs font-semibold text-slate-600">
                                                                        {{ strtoupper(substr($namaPekerja, 0, 1)) }}
                                                                    </div>

                                                                    <div>
                                                                        <p class="text-sm font-semibold text-slate-800">
                                                                            {{ $namaPekerja }}
                                                                        </p>

                                                                        <p class="mt-1 text-xs text-slate-400">
                                                                            ID #{{ $absen->pegawai_id ?? '-' }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </td>

                                                            <td class="whitespace-nowrap px-4 py-3.5">
                                                                @if($statusAbsen === 'Hadir')
                                                                    <span class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700">
                                                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                                                        Hadir
                                                                    </span>
                                                                @elseif($statusAbsen === 'Sakit')
                                                                    <span class="inline-flex items-center gap-2 rounded-full border border-amber-200 bg-amber-50 px-3 py-1.5 text-xs font-semibold text-amber-800">
                                                                        <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                                                        Sakit
                                                                    </span>
                                                                @elseif($statusAbsen === 'Izin')
                                                                    <span class="inline-flex items-center gap-2 rounded-full border border-blue-200 bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700">
                                                                        <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                                                                        Izin
                                                                    </span>
                                                                @else
                                                                    <span class="inline-flex items-center gap-2 rounded-full border border-rose-200 bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-700">
                                                                        <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                                                                        {{ $statusAbsen ?: 'Alfa' }}
                                                                    </span>
                                                                @endif
                                                            </td>

                                                            <td class="whitespace-nowrap px-4 py-3.5 text-sm text-slate-600">
                                                                {{ $absen->durasi ?: '-' }}
                                                            </td>

                                                            <td class="px-4 py-3.5 text-sm leading-5 text-slate-500">
                                                                {{ $absen->keterangan ?: '-' }}
                                                            </td>
                                                        </tr>

                                                    @empty
                                                        <tr>
                                                            <td colspan="4" class="px-4 py-12 text-center">
                                                                <div class="mx-auto flex max-w-xs flex-col items-center">
                                                                    <div class="flex h-12 w-12 items-center justify-center rounded-xl border border-slate-200 bg-slate-50">
                                                                        <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                                stroke-width="1.5"
                                                                                d="M17 20h5v-2a4 4 0 0 0-4-4h-1M9 20H4v-2a4 4 0 0 1 4-4h1m4-5a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" />
                                                                        </svg>
                                                                    </div>

                                                                    <p class="mt-3 text-sm font-semibold text-slate-800">
                                                                        Belum Ada Data Absensi
                                                                    </p>

                                                                    <p class="mt-1 text-sm leading-5 text-slate-500">
                                                                        Belum ada data kehadiran pekerja untuk laporan ini.
                                                                    </p>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforelse

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </details>

                @empty

                    {{-- Empty state --}}
                    <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50/50 px-5 py-14 text-center sm:py-16">

                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-400">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="1.5"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2Z" />
                            </svg>
                        </div>

                        <p class="mt-4 text-xs font-semibold uppercase tracking-[0.12em] text-slate-400">
                            Arsip Laporan
                        </p>

                        <h3 class="mt-2 text-base font-semibold text-slate-800">
                            Belum Ada Laporan Harian
                        </h3>

                        <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">
                            Belum ditemukan laporan harian untuk proyek ini pada periode
                            {{ \Carbon\Carbon::parse($bulanFilter)->translatedFormat('F Y') }}.
                        </p>

                    </div>

                @endforelse

            </div>
        </div>
    </section>
</div>

@endsection

