@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

@php
    $jamSekarang = now()->hour;

    $sapaan = $jamSekarang < 11
        ? 'Selamat Pagi'
        : ($jamSekarang < 15
            ? 'Selamat Siang'
            : ($jamSekarang < 19 ? 'Selamat Sore' : 'Selamat Malam'));

    $tanggalHariIni = now()
        ->locale('id')
        ->translatedFormat('l, d F Y');

    $adaNotifikasi =
        (isset($totalPendingValidasi) && $totalPendingValidasi > 0) ||
        (isset($totalProyekKritis) && $totalProyekKritis > 0) ||
        (isset($totalPegawaiTanpaAkun) && $totalPegawaiTanpaAkun > 0);
@endphp

{{-- =========================================================
    DASHBOARD WRAPPER
========================================================== --}}

{{-- =========================================================
    HERO
========================================================== --}}

<section
    class="relative overflow-hidden rounded-[30px] bg-slate-950 text-white shadow-xl shadow-slate-200/50">

    {{-- Background --}}
    <div
        class="absolute inset-0 bg-cover bg-center opacity-[0.22]"
        style="background-image: url('{{ asset('images/flower-mkp.jpg') }}');">
    </div>

    {{-- Gradient --}}
    <div
        class="absolute inset-0 bg-gradient-to-br from-slate-950 via-slate-950/[0.96] to-blue-950/[0.92]">
    </div>

    {{-- Decorative elements --}}
    <div
        class="absolute -right-24 -top-24 h-72 w-72 rounded-full bg-blue-500/10 blur-3xl">
    </div>

    <div
        class="absolute -bottom-32 right-20 h-72 w-72 rounded-full bg-indigo-500/10 blur-3xl">
    </div>

    <div class="relative px-6 py-9 sm:px-8 lg:px-10 lg:py-10">

        <div
            class="flex flex-col gap-9 lg:flex-row lg:items-center lg:justify-between">

            {{-- Hero Content --}}
            <div class="max-w-2xl">

                {{-- Date --}}
                <div
                    class="mb-5 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/[0.08] px-3.5 py-1.5 text-[11px] font-medium tracking-[0.01em] text-slate-300 backdrop-blur-md">

                    <span
                        class="h-1.5 w-1.5 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.8)]">
                    </span>

                    {{ $tanggalHariIni }}

                </div>


                {{-- Heading --}}
                <h1
                    class="text-[30px] font-semibold leading-tight tracking-[-0.025em] text-white sm:text-[38px]">

                    {{ $sapaan }}, {{ auth()->user()->name ?? 'Admin' }}

                </h1>


                {{-- Description --}}
                <p
                    class="mt-4 max-w-xl text-[13px] leading-6 tracking-[0.005em] text-slate-300 sm:text-sm">

                    Pantau kondisi proyek, keuangan, pegawai, dan aktivitas operasional
                    perusahaan Anda dari satu tempat.

                </p>

            </div>


            {{-- Hero Summary --}}
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:w-[390px]">

                {{-- Active --}}
                <div
                    class="rounded-2xl border border-white/10 bg-white/[0.08] p-4 backdrop-blur-md">

                    <p
                        class="text-[10px] font-medium uppercase tracking-[0.08em] text-slate-400">
                        Proyek Aktif
                    </p>

                    <p
                        class="mt-1.5 text-[26px] font-semibold tracking-[-0.03em] text-white tabular-nums">
                        {{ $totalProyekAktif }}
                    </p>

                </div>


                {{-- Completed --}}
                <div
                    class="rounded-2xl border border-white/10 bg-white/[0.08] p-4 backdrop-blur-md">

                    <p
                        class="text-[10px] font-medium uppercase tracking-[0.08em] text-slate-400">
                        Selesai
                    </p>

                    <p
                        class="mt-1.5 text-[26px] font-semibold tracking-[-0.03em] text-white tabular-nums">
                        {{ $totalProyekSelesai }}
                    </p>

                </div>


                {{-- Employees --}}
                <div
                    class="col-span-2 rounded-2xl border border-white/10 bg-white/[0.08] p-4 backdrop-blur-md sm:col-span-1">

                    <p
                        class="text-[10px] font-medium uppercase tracking-[0.08em] text-slate-400">
                        Pegawai
                    </p>

                    <p
                        class="mt-1.5 text-[26px] font-semibold tracking-[-0.03em] text-white tabular-nums">
                        {{ $totalPegawai }}
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>


{{-- =========================================================
    ACTION CENTER
========================================================== --}}

@if($adaNotifikasi)

    <section>

        <div class="mb-4 flex items-center gap-3">

            <div
                class="flex h-8 w-8 items-center justify-center rounded-xl bg-amber-50 text-amber-600">

                <svg
                    class="h-4 w-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0l-6.93 12c-.77 1.33.19 3 1.73 3z" />

                </svg>

            </div>

            <div>

                <h2
                    class="text-[13px] font-semibold tracking-[-0.005em] text-slate-900">
                    Perlu Perhatian
                </h2>

                <p class="mt-0.5 text-[11px] font-normal text-slate-500">
                    Beberapa hal membutuhkan tindakan Anda.
                </p>

            </div>

        </div>


        <div class="grid grid-cols-1 gap-3 lg:grid-cols-3">

            {{-- Pending Validation --}}
            @if(isset($totalPendingValidasi) && $totalPendingValidasi > 0)

                <a
                    href="{{ route('admin.absensi.index') }}"
                    class="group rounded-2xl border border-amber-100 bg-amber-50/70 p-4 transition-all duration-200 hover:-translate-y-0.5 hover:border-amber-200 hover:bg-amber-50 hover:shadow-lg hover:shadow-amber-100/50">

                    <div class="flex items-start justify-between gap-4">

                        <div>

                            <div
                                class="mb-3 flex h-9 w-9 items-center justify-center rounded-xl bg-amber-100 text-amber-600">

                                <svg
                                    class="h-4 w-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />

                                </svg>

                            </div>

                            <p
                                class="text-[13px] font-semibold tracking-[-0.005em] text-amber-950">
                                Validasi Menunggu
                            </p>

                            <p class="mt-1 text-[11px] leading-5 text-amber-800">
                                {{ $totalPendingValidasi }} laporan harian & absensi
                                membutuhkan validasi.
                            </p>

                        </div>

                        <svg
                            class="mt-1 h-4 w-4 text-amber-500 transition-transform group-hover:translate-x-1"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5l7 7-7 7" />

                        </svg>

                    </div>

                </a>

            @endif


            {{-- Critical Projects --}}
            @if(isset($totalProyekKritis) && $totalProyekKritis > 0)

                <a
                    href="{{ route('proyek.index') }}"
                    class="group rounded-2xl border border-red-100 bg-red-50/70 p-4 transition-all duration-200 hover:-translate-y-0.5 hover:border-red-200 hover:bg-red-50 hover:shadow-lg hover:shadow-red-100/50">

                    <div class="flex items-start justify-between gap-4">

                        <div>

                            <div
                                class="mb-3 flex h-9 w-9 items-center justify-center rounded-xl bg-red-100 text-red-600">

                                <svg
                                    class="h-4 w-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />

                                </svg>

                            </div>

                            <p
                                class="text-[13px] font-semibold tracking-[-0.005em] text-red-950">
                                Kas Proyek Menipis
                            </p>

                            <p class="mt-1 text-[11px] leading-5 text-red-800">
                                {{ $totalProyekKritis }} proyek berada di bawah batas aman kas.
                            </p>

                        </div>

                        <svg
                            class="mt-1 h-4 w-4 text-red-500 transition-transform group-hover:translate-x-1"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5l7 7-7 7" />

                        </svg>

                    </div>

                </a>

            @endif


            {{-- Employees --}}
            @if(isset($totalPegawaiTanpaAkun) && $totalPegawaiTanpaAkun > 0)

                <a
                    href="{{ route('pegawai.index') }}"
                    class="group rounded-2xl border border-blue-100 bg-blue-50/70 p-4 transition-all duration-200 hover:-translate-y-0.5 hover:border-blue-200 hover:bg-blue-50 hover:shadow-lg hover:shadow-blue-100/50">

                    <div class="flex items-start justify-between gap-4">

                        <div>

                            <div
                                class="mb-3 flex h-9 w-9 items-center justify-center rounded-xl bg-blue-100 text-blue-600">

                                <svg
                                    class="h-4 w-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />

                                </svg>

                            </div>

                            <p
                                class="text-[13px] font-semibold tracking-[-0.005em] text-blue-950">
                                Akun Belum Lengkap
                            </p>

                            <p class="mt-1 text-[11px] leading-5 text-blue-800">
                                {{ $totalPegawaiTanpaAkun }} pegawai belum memiliki akun sistem.
                            </p>

                        </div>

                        <svg
                            class="mt-1 h-4 w-4 text-blue-500 transition-transform group-hover:translate-x-1"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5l7 7-7 7" />

                        </svg>

                    </div>

                </a>

            @endif

        </div>

    </section>

@endif


{{-- =========================================================
    KPI CARDS
========================================================== --}}

<section class="grid grid-cols-1 gap-4 md:grid-cols-3">

    {{-- Completed --}}
    <div
        class="group relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg">

        <div
            class="absolute -right-10 -top-10 h-28 w-28 rounded-full bg-blue-50">
        </div>

        <div class="relative flex items-start justify-between">

            <div>

                <p
                    class="text-[10px] font-medium uppercase tracking-[0.08em] text-slate-400">
                    Proyek Selesai
                </p>

                <div class="mt-2 flex items-baseline gap-2">

                    <h3
                        class="text-[32px] font-semibold leading-none tracking-[-0.04em] text-slate-900 tabular-nums">
                        {{ $totalProyekSelesai }}
                    </h3>

                    @isset($trendProyekSelesai)

                        <span
                            class="text-[11px] font-medium {{ $trendProyekSelesai >= 0 ? 'text-emerald-600' : 'text-red-500' }}">

                            {{ $trendProyekSelesai >= 0 ? '+' : '' }}{{ $trendProyekSelesai }}

                        </span>

                    @endisset

                </div>

                <p class="mt-2 text-[11px] leading-5 text-slate-400">
                    Total proyek yang telah selesai
                </p>

            </div>

            <div
                class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 transition-colors group-hover:bg-blue-600 group-hover:text-white">

                <svg
                    class="h-5 w-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />

                </svg>

            </div>

        </div>

    </div>


    {{-- Active --}}
    <div
        class="group relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg">

        <div
            class="absolute -right-10 -top-10 h-28 w-28 rounded-full bg-indigo-50">
        </div>

        <div class="relative flex items-start justify-between">

            <div>

                <p
                    class="text-[10px] font-medium uppercase tracking-[0.08em] text-slate-400">
                    Proyek Aktif
                </p>

                <div class="mt-2 flex items-baseline gap-2">

                    <h3
                        class="text-[32px] font-semibold leading-none tracking-[-0.04em] text-slate-900 tabular-nums">
                        {{ $totalProyekAktif }}
                    </h3>

                    @isset($trendProyekAktif)

                        <span
                            class="text-[11px] font-medium {{ $trendProyekAktif >= 0 ? 'text-emerald-600' : 'text-red-500' }}">

                            {{ $trendProyekAktif >= 0 ? '+' : '' }}{{ $trendProyekAktif }}

                        </span>

                    @endisset

                </div>

                <p class="mt-2 text-[11px] leading-5 text-slate-400">
                    Proyek yang sedang berjalan
                </p>

            </div>

            <div
                class="flex h-11 w-11 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600 transition-colors group-hover:bg-indigo-600 group-hover:text-white">

                <svg
                    class="h-5 w-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />

                </svg>

            </div>

        </div>

    </div>


    {{-- Employees --}}
    <div
        class="group relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg">

        <div
            class="absolute -right-10 -top-10 h-28 w-28 rounded-full bg-emerald-50">
        </div>

        <div class="relative flex items-start justify-between">

            <div>

                <p
                    class="text-[10px] font-medium uppercase tracking-[0.08em] text-slate-400">
                    Total Pegawai
                </p>

                <div class="mt-2 flex items-baseline gap-2">

                    <h3
                        class="text-[32px] font-semibold leading-none tracking-[-0.04em] text-slate-900 tabular-nums">
                        {{ $totalPegawai }}
                    </h3>

                    <span class="text-[11px] font-medium text-slate-400">
                        Orang
                    </span>

                </div>

                <p class="mt-2 text-[11px] leading-5 text-slate-400">
                    Pegawai yang terdaftar
                </p>

            </div>

            <div
                class="flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 transition-colors group-hover:bg-emerald-600 group-hover:text-white">

                <svg
                    class="h-5 w-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2a5 5 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />

                </svg>

            </div>

        </div>

    </div>

</section>


{{-- =========================================================
    PROJECT HEALTH
========================================================== --}}

<section
    class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">

    {{-- Header --}}
    <div
        class="flex flex-col gap-4 border-b border-slate-100 px-6 py-5 sm:flex-row sm:items-center sm:justify-between lg:px-7">

        <div>

            <div class="flex items-center gap-2.5">

                <div
                    class="flex h-8 w-8 items-center justify-center rounded-xl bg-blue-50 text-blue-600">

                    <svg
                        class="h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 19v-6a2 2 0 012-2h2a2 2 0 012 2v6m4 0V5a2 2 0 00-2-2h-3a2 2 0 00-2 2v14m-4 0V9a2 2 0 00-2-2H5a2 2 0 00-2 2v10" />

                    </svg>

                </div>

                <h2
                    class="text-[14px] font-semibold tracking-[-0.01em] text-slate-900">
                    Kesehatan Proyek Aktif
                </h2>

            </div>

            <p class="mt-1.5 text-[11px] leading-5 text-slate-500">
                Ringkasan kondisi keuangan dan progress proyek yang sedang berjalan.
            </p>

        </div>

        <a
            href="{{ route('proyek.index') }}"
            class="inline-flex items-center gap-1.5 text-[11px] font-medium text-blue-600 transition hover:text-blue-700">

            Lihat semua proyek

            <svg
                class="h-3.5 w-3.5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24">

                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M5 12h14m-6-6l6 6-6 6" />

            </svg>

        </a>

    </div>


    {{-- Project Cards --}}
    <div class="grid grid-cols-1 gap-4 p-5 sm:grid-cols-2 lg:grid-cols-3 lg:p-6">

        @forelse($proyekKesehatan as $proyek)

            <a
                href="{{ route('proyek.keuangan', $proyek->id) }}"
                class="group overflow-hidden rounded-2xl border border-slate-200 bg-white transition-all duration-300 hover:-translate-y-1 hover:border-blue-200 hover:shadow-xl hover:shadow-slate-200/60">

                {{-- Image --}}
                <div class="relative h-40 overflow-hidden bg-slate-100">

                    @if(!empty($proyek->gambar))

                        <img
                            src="{{ asset('storage/' . $proyek->gambar) }}"
                            alt="{{ $proyek->nama_proyek }}"
                            class="h-full w-full object-cover transition duration-700 group-hover:scale-105">

                    @else

                        <div
                            class="flex h-full w-full items-center justify-center bg-gradient-to-br from-slate-100 to-slate-50">

                            <svg
                                class="h-9 w-9 text-slate-300"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.5"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h-2m2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1" />

                            </svg>

                        </div>

                    @endif

                    <div
                        class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent">
                    </div>


                    {{-- Health --}}
                    <div class="absolute right-3 top-3">

                        <div
                            class="flex items-center gap-1.5 rounded-full border border-white/20 bg-black/30 px-2.5 py-1 backdrop-blur-md">

                            <span class="relative flex h-2 w-2">

                                @if($proyek->kas_tersedia <= 5000000)

                                    <span
                                        class="absolute inline-flex h-full w-full animate-ping rounded-full {{ $proyek->health_dot }} opacity-75">
                                    </span>

                                @endif

                                <span
                                    class="relative inline-flex h-2 w-2 rounded-full {{ $proyek->health_dot }}">
                                </span>

                            </span>

                            <span
                                class="text-[10px] font-medium text-white">
                                {{ $proyek->health_status }}
                            </span>

                        </div>

                    </div>

                </div>


                {{-- Content --}}
                <div class="p-5">

                    <div class="flex items-start justify-between gap-3">

                        <h3
                            class="line-clamp-2 text-[13px] font-semibold leading-5 tracking-[-0.005em] text-slate-900 transition-colors group-hover:text-blue-600">

                            {{ $proyek->nama_proyek }}

                        </h3>

                        @if($proyek->persentase_terpakai > 100)

                            <span
                                class="shrink-0 rounded-full bg-red-50 px-2 py-1 text-[9px] font-semibold text-red-600">
                                Over Budget
                            </span>

                        @else

                            <span
                                class="shrink-0 rounded-full bg-blue-50 px-2 py-1 text-[9px] font-semibold text-blue-600">
                                Berjalan
                            </span>

                        @endif

                    </div>


                    {{-- Location --}}
                    <div
                        class="mt-3 flex items-center gap-1.5 text-[11px] text-slate-500">

                        <svg
                            class="h-3.5 w-3.5 shrink-0 text-slate-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.5"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.5"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />

                        </svg>

                        <span class="truncate">
                            {{ $proyek->lokasi ?? 'Lokasi belum diatur' }}
                        </span>

                    </div>


                    {{-- Budget --}}
                    <div
                        class="mt-1.5 flex items-center gap-1.5 text-[11px] text-slate-500">

                        <svg
                            class="h-3.5 w-3.5 shrink-0 text-slate-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.5"
                                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />

                        </svg>

                        <span>
                            RAB:
                            <strong class="font-medium text-slate-700">
                                Rp {{ number_format($proyek->anggaran, 0, ',', '.') }}
                            </strong>
                        </span>

                    </div>


                    <div class="my-4 border-t border-slate-100"></div>


                    {{-- Cash --}}
                    <div class="flex items-end justify-between">

                        <div>

                            <p
                                class="text-[9px] font-medium uppercase tracking-[0.08em] text-slate-400">
                                Kas Tersedia
                            </p>

                            <div class="mt-1 flex items-baseline gap-1">

                                <span
                                    class="text-[11px] font-medium {{ $proyek->kas_tersedia < 0 ? 'text-red-500' : 'text-slate-400' }}">

                                    {{ $proyek->kas_tersedia < 0 ? '-' : '' }}Rp

                                </span>

                                <span
                                    class="text-[18px] font-semibold tracking-[-0.025em] tabular-nums {{ $proyek->kas_tersedia < 0 ? 'text-red-600' : 'text-slate-900' }}">

                                    {{ number_format(abs($proyek->kas_tersedia), 0, ',', '.') }}

                                </span>

                            </div>

                        </div>


                        <div class="text-right">

                            <p
                                class="text-[9px] font-medium uppercase tracking-[0.08em] text-slate-400">
                                Terpakai
                            </p>

                            <p
                                class="mt-1 text-[13px] font-semibold tabular-nums {{ $proyek->health_color }}">
                                {{ number_format($proyek->persentase_terpakai, 0) }}%
                            </p>

                        </div>

                    </div>


                    {{-- Progress --}}
                    <div
                        class="mt-3 h-1.5 w-full overflow-hidden rounded-full bg-slate-100">

                        <div
                            class="h-full rounded-full transition-all duration-700 {{ $proyek->persentase_terpakai > 100 ? 'bg-red-500' : $proyek->health_dot }}"
                            style="width: {{ min($proyek->persentase_terpakai, 100) }}%;">
                        </div>

                    </div>

                </div>

            </a>

        @empty

            <div class="col-span-full py-12 text-center">

                <div
                    class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100">

                    <svg
                        class="h-6 w-6 text-slate-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.5"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h-2m2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1" />

                    </svg>

                </div>

                <p class="text-[13px] font-medium text-slate-500">
                    Belum ada proyek aktif
                </p>

                <p class="mt-1 text-[11px] text-slate-400">
                    Proyek yang sedang berjalan akan muncul di sini.
                </p>

            </div>

        @endforelse

    </div>


    {{-- Footer --}}
    <div
        class="border-t border-slate-100 px-6 py-4 text-center">

        <a
            href="{{ route('proyek.index') }}"
            class="inline-flex items-center gap-2 rounded-xl bg-slate-50 px-4 py-2.5 text-[11px] font-medium text-slate-700 transition hover:bg-slate-100">

            Lihat Semua Proyek

            <svg
                class="h-3.5 w-3.5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24">

                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M5 12h14m-6-6l6 6-6 6" />

            </svg>

        </a>

    </div>

</section>


{{-- =========================================================
    LOWER SECTION
========================================================== --}}

<section class="grid grid-cols-1 gap-5 lg:grid-cols-3">

    {{-- Employees --}}
    <div
        class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm lg:col-span-2">

        <div
            class="flex items-center justify-between border-b border-slate-100 px-6 py-5">

            <div>

                <h2
                    class="text-[14px] font-semibold tracking-[-0.01em] text-slate-900">
                    Pegawai Terbaru
                </h2>

                <p class="mt-1 text-[11px] text-slate-500">
                    Staf operasional dan tukang yang terdaftar.
                </p>

            </div>

            <a
                href="{{ route('pegawai.index') }}"
                class="inline-flex items-center gap-1 text-[11px] font-medium text-blue-600 hover:text-blue-700">

                Lihat Semua

                <svg
                    class="h-3.5 w-3.5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M5 12h14m-6-6l6 6-6 6" />

                </svg>

            </a>

        </div>


        <div class="overflow-x-auto">

            <table class="w-full">

                <thead>

                    <tr class="border-b border-slate-100 bg-slate-50/60">

                        <th
                            class="px-6 py-3 text-left text-[9px] font-medium uppercase tracking-[0.08em] text-slate-400">
                            Pegawai
                        </th>

                        <th
                            class="px-6 py-3 text-left text-[9px] font-medium uppercase tracking-[0.08em] text-slate-400">
                            Jabatan
                        </th>

                        <th
                            class="px-6 py-3 text-left text-[9px] font-medium uppercase tracking-[0.08em] text-slate-400">
                            Telepon
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-slate-100">

                    @forelse($pegawais as $pegawai)

                        <tr class="group transition hover:bg-slate-50/70">

                            <td class="px-6 py-3.5">

                                <div class="flex items-center gap-3">

                                    <div
                                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-[11px] font-semibold text-slate-600 transition group-hover:bg-blue-50 group-hover:text-blue-600">

                                        {{ strtoupper(substr($pegawai->nama ?? 'P', 0, 1)) }}

                                    </div>

                                    <span
                                        class="text-[12px] font-medium tracking-[-0.005em] text-slate-800">

                                        {{ $pegawai->nama }}

                                    </span>

                                </div>

                            </td>


                            <td class="px-6 py-3.5">

                                <span
                                    class="inline-flex rounded-lg px-2.5 py-1 text-[9px] font-medium
                                    {{ strtolower($pegawai->jabatan->nama_jabatan ?? '') == 'mandor'
                                        ? 'bg-blue-50 text-blue-700'
                                        : 'bg-slate-100 text-slate-600' }}">

                                    {{ $pegawai->jabatan->nama_jabatan ?? 'Belum Diatur' }}

                                </span>

                            </td>


                            <td
                                class="px-6 py-3.5 text-[11px] font-normal tabular-nums text-slate-500">

                                {{ $pegawai->no_telp ?? '-' }}

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="3"
                                class="px-6 py-12 text-center">

                                <p class="text-[12px] font-medium text-slate-400">
                                    Belum ada data pegawai.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- Activity --}}
    <div
        class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">

        <div
            class="border-b border-slate-100 px-6 py-5">

            <h2
                class="text-[14px] font-semibold tracking-[-0.01em] text-slate-900">
                Aktivitas Terbaru
            </h2>

            <p class="mt-1 text-[11px] text-slate-500">
                Aktivitas terakhir di dalam sistem.
            </p>

        </div>


        <div class="p-6">

            @forelse(($recentActivities ?? []) as $activity)

                <div
                    class="relative flex gap-3 pb-5 last:pb-0">

                    {{-- Timeline --}}
                    <div class="relative flex flex-col items-center">

                        <span
                            class="mt-1.5 h-2.5 w-2.5 shrink-0 rounded-full bg-blue-500 ring-4 ring-blue-50">
                        </span>

                        @unless($loop->last)

                            <span
                                class="absolute top-4 h-full w-px bg-slate-100">
                            </span>

                        @endunless

                    </div>


                    <div class="min-w-0">

                        <p
                            class="text-[11px] font-normal leading-5 text-slate-700">

                            {{ $activity->deskripsi ?? $activity['deskripsi'] ?? '-' }}

                        </p>

                        <p
                            class="mt-1 text-[9px] font-medium text-slate-400">

                            {{ \Carbon\Carbon::parse(
                                $activity->created_at ?? $activity['created_at']
                            )->locale('id')->diffForHumans() }}

                        </p>

                    </div>

                </div>

            @empty

                <div class="py-8 text-center">

                    <div
                        class="mx-auto mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100">

                        <svg
                            class="h-5 w-5 text-slate-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.5"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a3 3 0 003 3h0a3 3 0 003-3M9 5a2 2 0 012-2h2a2 2 0 012 2" />

                        </svg>

                    </div>

                    <p class="text-[11px] font-medium text-slate-400">
                        Belum ada aktivitas.
                    </p>

                </div>

            @endforelse

        </div>

    </div>

</section>

@endsection