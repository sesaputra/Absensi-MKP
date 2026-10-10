@extends('layouts.admin')

@section('title', 'Buku Harian Lapangan')

@section('content')

@php
    use Carbon\Carbon;

    $jumlahLaporan = $riwayatLaporan->count();

    $bulanLabel = !empty($selectedMonth)
        ? Carbon::createFromFormat('Y-m', $selectedMonth)->translatedFormat('F Y')
        : now()->translatedFormat('F Y');

    $isAdmin = auth()->check()
        && in_array(auth()->user()->role, ['admin', 'super_admin']);
@endphp

<div class="space-y-6">

    {{-- =========================================================
        PAGE HEADER / BANNER
    ========================================================== --}}
    <section class="relative isolate overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        {{-- Background image --}}
        <div
            class="absolute inset-0 -z-20 bg-cover bg-center opacity-[0.08]"
            style="background-image: url('{{ asset('images/flower-mkp.jpg') }}');">
        </div>

        <div class="absolute inset-0 -z-10 bg-white/90"></div>

        {{-- Subtle grid --}}
        <div
            class="absolute inset-0 -z-10 opacity-[0.015]"
            style="
                background-image:
                    linear-gradient(rgba(15,23,42,.8) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(15,23,42,.8) 1px, transparent 1px);
                background-size: 32px 32px;
            ">
        </div>

        <div class="relative px-5 py-6 sm:px-8 sm:py-8">

            {{-- Top navigation --}}
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <a
                    href="{{ route('proyek.show', $proyek->id) }}"
                    class="group inline-flex w-fit items-center gap-2 text-sm font-medium text-slate-500 transition hover:text-slate-900">

                    <span class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white transition group-hover:border-slate-300 group-hover:bg-slate-50">
                        <svg class="h-4 w-4 transition group-hover:-translate-x-0.5"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="1.8" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </span>

                    Kembali ke Detail Proyek
                </a>

                <span class="inline-flex w-fit items-center gap-2 rounded-full border border-amber-200 bg-amber-50 px-3 py-1.5 text-xs font-semibold text-amber-800">
                    <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                    Monitoring Proyek
                </span>

            </div>

            {{-- Heading --}}
            <div class="max-w-3xl">

                <div class="mb-3 flex items-center gap-2.5">
                    <span class="h-2 w-2 rounded-full bg-amber-500"></span>

                    <span class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-400">
                        Administrasi & Monitoring Lapangan
                    </span>
                </div>

                <h1 class="text-2xl font-semibold tracking-tight text-slate-900 sm:text-3xl">
                    Buku Harian Lapangan
                </h1>

                <p class="mt-2 text-sm leading-6 text-slate-500 sm:text-[15px]">
                    Pantau aktivitas harian, pencapaian pekerjaan, dan dokumentasi
                    pelaksanaan proyek dalam satu ruang monitoring.
                </p>

                <div class="mt-5 flex flex-col gap-3 sm:flex-row sm:items-center">

                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="1.7"
                                d="M3 7.5A2.5 2.5 0 015.5 5h13A2.5 2.5 0 0121 7.5v10a2.5 2.5 0 01-2.5 2.5h-13A2.5 2.5 0 013 17.5v-10z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="1.7" d="M3 10h18M8 5V3m8 2V3" />
                        </svg>
                    </div>

                    <div class="min-w-0">
                        <p class="text-xs font-medium text-slate-400">
                            Proyek yang dipantau
                        </p>

                        <h2 class="mt-0.5 break-words text-base font-semibold text-slate-800 sm:text-lg">
                            {{ $proyek->nama_proyek }}
                        </h2>

                        @if($proyek->lokasi)
                            <p class="mt-1 flex items-start gap-1.5 text-sm text-slate-500">
                                <svg class="mt-0.5 h-4 w-4 shrink-0"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="1.7"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="1.7"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>

                                <span>{{ $proyek->lokasi }}</span>
                            </p>
                        @endif
                    </div>

                </div>
            </div>

            {{-- Summary cards --}}
            <div class="mt-7 grid grid-cols-1 gap-3 sm:grid-cols-2">

                {{-- Period --}}
                <div class="rounded-xl border border-slate-200/80 bg-white/90 px-4 py-4 backdrop-blur-sm">
                    <div class="flex items-center justify-between gap-3">
                        <p class="text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">
                            Periode Laporan
                        </p>

                        <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                    </div>

                    <p class="mt-2 text-xl font-semibold tracking-tight text-slate-900 sm:text-2xl">
                        {{ $bulanLabel }}
                    </p>

                    <p class="mt-1 text-xs text-slate-500 sm:text-sm">
                        Periode monitoring yang dipilih
                    </p>
                </div>

                {{-- Total reports --}}
                <div class="rounded-xl border border-slate-200/80 bg-white/90 px-4 py-4 backdrop-blur-sm">
                    <div class="flex items-center justify-between gap-3">
                        <p class="text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">
                            Total Catatan
                        </p>

                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    </div>

                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="text-2xl font-semibold tracking-tight text-slate-900">
                            {{ number_format($jumlahLaporan) }}
                        </span>

                        <span class="text-sm text-slate-500">
                            laporan
                        </span>
                    </div>

                    <p class="mt-1 text-xs text-slate-500 sm:text-sm">
                        Catatan lapangan pada periode ini
                    </p>
                </div>

            </div>
        </div>
    </section>


    {{-- =========================================================
        FILTER PERIODE
    ========================================================== --}}
    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <div class="flex items-center gap-2.5">
                    <span class="h-2 w-2 rounded-full bg-amber-500"></span>

                    <h2 class="text-base font-semibold tracking-tight text-slate-800">
                        Filter Periode
                    </h2>
                </div>

                <p class="mt-1.5 text-sm leading-6 text-slate-500">
                    Pilih bulan untuk melihat riwayat laporan proyek.
                </p>
            </div>

            @if(isset($availableMonths) && $availableMonths->count() > 0)
                <form
                    method="GET"
                    action="{{ route('proyek.laporan.admin', $proyek->id) }}"
                    class="w-full sm:w-64">

                    <label for="filterBulan" class="mb-2 block text-xs font-semibold text-slate-600">
                        Bulan laporan
                    </label>

                    <div class="flex gap-2">
                        <div class="relative min-w-0 flex-1">

                            <select
                                id="filterBulan"
                                name="bulan"
                                onchange="this.form.submit()"
                                class="w-full appearance-none rounded-xl border border-slate-200 bg-white px-3.5 py-3 pr-10 text-sm text-slate-700 outline-none transition focus:border-amber-400 focus:ring-4 focus:ring-amber-100">

                                @foreach($availableMonths as $monthYear)
                                    <option
                                        value="{{ $monthYear }}"
                                        {{ ($selectedMonth ?? '') === $monthYear ? 'selected' : '' }}>
                                        {{ Carbon::createFromFormat('Y-m', $monthYear)->translatedFormat('F Y') }}
                                    </option>
                                @endforeach

                            </select>

                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3.5">
                                <svg class="h-4 w-4 text-slate-400"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="1.8" d="m6 9 6 6 6-6" />
                                </svg>
                            </div>

                        </div>
                    </div>
                </form>
            @else
                <div class="inline-flex items-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-3.5 py-3 text-sm font-medium text-emerald-700">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    Monitoring tersedia
                </div>
            @endif

        </div>
    </section>


    {{-- =========================================================
        RIWAYAT LAPORAN
    ========================================================== --}}
    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        {{-- Section header --}}
        <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">

            <div>
                <div class="flex items-center gap-2.5">
                    <span class="h-2 w-2 rounded-full bg-blue-500"></span>

                    <h2 class="text-base font-semibold tracking-tight text-slate-800 sm:text-lg">
                        Riwayat Laporan Lapangan
                    </h2>
                </div>

                <p class="mt-1.5 text-sm leading-6 text-slate-500">
                    Aktivitas harian, progres fisik, dan dokumentasi dari pengawas lapangan.
                </p>
            </div>

            <span class="inline-flex w-fit items-center rounded-full border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-600">
                {{ number_format($jumlahLaporan) }} laporan
            </span>

        </div>


        <div class="p-4 sm:p-6">

            @if($riwayatLaporan->count() > 0)

                <div class="relative space-y-6">

                    @foreach($riwayatLaporan as $laporan)

                        @php
                            $snapshot = $laporan->progres_snapshot ?? [];

                            if (is_string($snapshot)) {
                                $snapshot = json_decode($snapshot, true) ?? [];
                            }

                            if (!is_array($snapshot)) {
                                $snapshot = [];
                            }

                            $snapshot = collect($snapshot)
                                ->filter(fn ($item) => is_array($item))
                                ->values();
                        @endphp

                        <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white transition duration-200 hover:border-slate-300 hover:shadow-sm">

                            {{-- Report header --}}
                            <div class="flex flex-col gap-4 border-b border-slate-100 bg-slate-50/70 px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-5">

                                <div class="flex min-w-0 items-center gap-3">

                                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-600">
                                        {{ strtoupper(substr($laporan->pegawai->nama ?? 'A', 0, 1)) }}
                                    </div>

                                    <div class="min-w-0">
                                        <h3 class="truncate text-sm font-semibold text-slate-800">
                                            {{ $laporan->pegawai->nama ?? 'Sistem / Admin' }}
                                        </h3>

                                        <p class="mt-1 text-xs text-slate-500">
                                            {{ $laporan->pegawai->jabatan->nama_jabatan ?? 'Pengawas Lapangan' }}
                                        </p>
                                    </div>

                                </div>

                                <div class="flex flex-wrap items-center gap-2 sm:justify-end">

                                    <span class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700">
                                        <svg class="h-3.5 w-3.5 text-slate-400"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="1.7"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>

                                        {{ Carbon::parse($laporan->tanggal)->translatedFormat('d M Y') }}
                                    </span>

                                    @if($laporan->created_at)
                                        <span class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-medium text-slate-500">
                                            <svg class="h-3.5 w-3.5"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="1.7"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>

                                            {{ $laporan->created_at->format('H:i') }} WITA
                                        </span>
                                    @endif

                                </div>
                            </div>


                            {{-- Report body --}}
                            <div class="space-y-6 p-4 sm:p-5">

                                {{-- 1. Daily journal --}}
                                <div>
                                    <div class="mb-3 flex items-center gap-2.5">
                                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border border-slate-200 bg-slate-50">
                                            <svg class="h-4 w-4 text-slate-500"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="1.7"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </span>

                                        <h4 class="text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">
                                            Jurnal Lapangan
                                        </h4>
                                    </div>

                                    <div class="rounded-xl border border-slate-200 bg-white px-4 py-3.5">
                                        <p class="whitespace-pre-line text-sm leading-6 text-slate-600">{{ $laporan->kegiatan ?: 'Tidak ada catatan kegiatan.' }}</p>
                                    </div>
                                </div>


                                {{-- 2. Physical progress --}}
                                <div>
                                    <div class="mb-3 flex items-center gap-2.5">
                                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border border-slate-200 bg-slate-50">
                                            <svg class="h-4 w-4 text-blue-600"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="1.7"
                                                    d="M3 3v18h18M8 15l4-4 4 4 5-7" />
                                            </svg>
                                        </span>

                                        <h4 class="text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">
                                            Pencapaian Fisik Proyek
                                        </h4>
                                    </div>

                                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">

                                        @forelse($snapshot as $item)

                                            @php
                                                $namaPekerjaan = $item['nama_pekerjaan'] ?? 'Pekerjaan';
                                                $progres = max(0, min(100, (float) ($item['progres'] ?? 0)));
                                            @endphp

                                            <div class="rounded-xl border border-slate-200 bg-white p-4 transition hover:bg-slate-50/70">

                                                <div class="mb-3 flex items-start justify-between gap-3">
                                                    <p
                                                        class="min-w-0 break-words text-sm font-semibold text-slate-700"
                                                        title="{{ $namaPekerjaan }}">
                                                        {{ $namaPekerjaan }}
                                                    </p>

                                                    <span class="shrink-0 text-sm font-semibold {{ $progres > 0 ? 'text-blue-600' : 'text-slate-400' }}">
                                                        {{ rtrim(rtrim(number_format($progres, 2, '.', ''), '0'), '.') }}%
                                                    </span>
                                                </div>

                                                <div
                                                    class="h-2 w-full overflow-hidden rounded-full bg-slate-100"
                                                    role="progressbar"
                                                    aria-label="Progres {{ $namaPekerjaan }}"
                                                    aria-valuenow="{{ $progres }}"
                                                    aria-valuemin="0"
                                                    aria-valuemax="100">

                                                    <div
                                                        class="h-full rounded-full transition-all duration-500 {{ $progres > 0 ? 'bg-blue-500' : 'bg-slate-300' }}"
                                                        style="width: {{ $progres }}%">
                                                    </div>
                                                </div>

                                                <p class="mt-2 text-xs text-slate-400">
                                                    Progres pekerjaan
                                                </p>

                                            </div>

                                        @empty

                                            <div class="rounded-xl border border-dashed border-slate-200 bg-slate-50/50 px-4 py-6 sm:col-span-2">
                                                <p class="text-center text-sm text-slate-400">
                                                    Tidak ada catatan progres pada laporan hari ini.
                                                </p>
                                            </div>

                                        @endforelse

                                    </div>
                                </div>


                                {{-- 3. Visual documentation --}}
                                @if($laporan->foto_pagi || $laporan->foto_sore || ($laporan->latitude !== null && $laporan->longitude !== null))

                                    <div class="border-t border-slate-100 pt-5">

                                        <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                                            <div class="flex items-center gap-2.5">
                                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border border-slate-200 bg-slate-50">
                                                    <svg class="h-4 w-4 text-slate-500"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1.7"
                                                            d="M3 7a2 2 0 012-2h3l2 2h9a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />
                                                        <circle cx="12" cy="13" r="3" stroke-width="1.7" />
                                                    </svg>
                                                </span>

                                                <h4 class="text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">
                                                    Dokumentasi Lapangan
                                                </h4>
                                            </div>

                                            @if($laporan->latitude !== null && $laporan->longitude !== null)
                                                <a
                                                    href="https://www.google.com/maps/search/?api=1&query={{ urlencode($laporan->latitude . ',' . $laporan->longitude) }}"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    class="inline-flex w-fit items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-xs font-semibold text-slate-600 transition hover:border-amber-300 hover:bg-amber-50 hover:text-amber-800">

                                                    <svg class="h-4 w-4"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1.7"
                                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1.7"
                                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>

                                                    Lihat Lokasi GPS
                                                </a>
                                            @endif

                                        </div>


                                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                                            {{-- Morning photo --}}
                                            @if($laporan->foto_pagi)
                                                <a
                                                    href="{{ asset('storage/' . $laporan->foto_pagi) }}"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    class="group block overflow-hidden rounded-xl border border-slate-200 bg-slate-50">

                                                    <div class="flex items-center justify-between border-b border-slate-200 bg-white px-3.5 py-3">
                                                        <div class="flex items-center gap-2">
                                                            <span class="h-2 w-2 rounded-full bg-amber-500"></span>

                                                            <span class="text-sm font-semibold text-slate-700">
                                                                Briefing Pagi
                                                            </span>
                                                        </div>

                                                        <svg class="h-4 w-4 text-slate-400 transition group-hover:text-slate-700"
                                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="1.7"
                                                                d="M14 4h6v6m-11 3L20 4M18 13v5a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2h5" />
                                                        </svg>
                                                    </div>

                                                    <div class="overflow-hidden">
                                                        <img
                                                            src="{{ asset('storage/' . $laporan->foto_pagi) }}"
                                                            alt="Dokumentasi briefing pagi"
                                                            loading="lazy"
                                                            class="h-48 w-full object-cover transition duration-500 group-hover:scale-[1.03] sm:h-56">
                                                    </div>
                                                </a>
                                            @endif


                                            {{-- Afternoon photo --}}
                                            @if($laporan->foto_sore)
                                                <a
                                                    href="{{ asset('storage/' . $laporan->foto_sore) }}"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    class="group block overflow-hidden rounded-xl border border-slate-200 bg-slate-50">

                                                    <div class="flex items-center justify-between border-b border-slate-200 bg-white px-3.5 py-3">
                                                        <div class="flex items-center gap-2">
                                                            <span class="h-2 w-2 rounded-full bg-blue-500"></span>

                                                            <span class="text-sm font-semibold text-slate-700">
                                                                Progres Sore
                                                            </span>
                                                        </div>

                                                        <svg class="h-4 w-4 text-slate-400 transition group-hover:text-slate-700"
                                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="1.7"
                                                                d="M14 4h6v6m-11 3L20 4M18 13v5a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2h5" />
                                                        </svg>
                                                    </div>

                                                    <div class="overflow-hidden">
                                                        <img
                                                            src="{{ asset('storage/' . $laporan->foto_sore) }}"
                                                            alt="Dokumentasi progres sore"
                                                            loading="lazy"
                                                            class="h-48 w-full object-cover transition duration-500 group-hover:scale-[1.03] sm:h-56">
                                                    </div>
                                                </a>
                                            @endif

                                        </div>
                                    </div>
                                @endif

                            </div>
                        </article>

                    @endforeach

                </div>

            @else

                {{-- Empty state --}}
                <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-5 py-12 text-center sm:px-8 sm:py-16">

                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl border border-slate-200 bg-slate-50">
                        <svg class="h-6 w-6 text-slate-400"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="1.5"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>

                    <h3 class="mt-4 text-base font-semibold tracking-tight text-slate-800">
                        Belum Ada Catatan Lapangan
                    </h3>

                    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">
                        Belum ada laporan harian untuk proyek ini pada periode
                        <span class="font-semibold text-slate-700">{{ $bulanLabel }}</span>.
                        Pilih periode lain atau tunggu laporan dari pengawas lapangan.
                    </p>

                    @if(isset($availableMonths) && $availableMonths->count() > 0)
                        <a
                            href="{{ route('proyek.laporan.admin', $proyek->id) }}"
                            class="mt-5 inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50 hover:text-slate-900">

                            <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="1.8" d="M4 7h16M4 12h16M4 17h16" />
                            </svg>

                            Lihat Periode Terbaru
                        </a>
                    @endif

                </div>

            @endif

        </div>
    </section>

</div>

@endsection
