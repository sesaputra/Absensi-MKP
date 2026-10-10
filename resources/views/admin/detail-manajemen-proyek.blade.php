@extends('layouts.admin')

@section('title', 'Detail Proyek')

@section('content')

@php
    $isAdmin = auth()->check() &&
        in_array(auth()->user()->role, ['admin', 'super_admin']);

    $jumlahTim = $proyek->pegawais->count();
    $jumlahPekerjaan = $proyek->itemPekerjaans->count();

    $totalBobot = (float) $proyek->itemPekerjaans->sum('bobot');
    $sisaBobot = max(0, 100 - $totalBobot);

    $totalBobotFormatted = number_format($totalBobot, 2, ',', '.');
    $sisaBobotFormatted = number_format($sisaBobot, 2, ',', '.');

    $statusConfig = match ($proyek->status) {
        'Berjalan' => [
            'badge' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
            'dot' => 'bg-emerald-500',
            'label' => 'Berjalan',
        ],

        'Akan Dimulai' => [
            'badge' => 'border-blue-200 bg-blue-50 text-blue-700',
            'dot' => 'bg-blue-500',
            'label' => 'Akan Dimulai',
        ],

        'Ditunda' => [
            'badge' => 'border-amber-200 bg-amber-50 text-amber-700',
            'dot' => 'bg-amber-500',
            'label' => 'Ditunda',
        ],

        'Selesai' => [
            'badge' => 'border-slate-200 bg-slate-100 text-slate-600',
            'dot' => 'bg-slate-400',
            'label' => 'Selesai',
        ],

        default => [
            'badge' => 'border-slate-200 bg-slate-50 text-slate-600',
            'dot' => 'bg-slate-400',
            'label' => $proyek->status,
        ],
    };
@endphp


{{-- =========================================================
    FLASH MESSAGE
========================================================= --}}

@if(session('success'))
    <div
        class="mb-6 flex items-center justify-between rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700 shadow-sm"
        role="alert">

        <div class="flex items-center gap-2.5">

            <svg
                class="h-5 w-5 shrink-0 text-emerald-600"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24">

                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />

            </svg>

            <span class="text-sm font-medium">
                {{ session('success') }}
            </span>

        </div>

        <button
            type="button"
            onclick="this.parentElement.remove()"
            class="rounded-lg p-1 text-emerald-500 transition hover:bg-emerald-100 hover:text-emerald-700"
            aria-label="Tutup notifikasi">

            <svg
                class="h-4 w-4"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24">

                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M6 18L18 6M6 6l12 12" />

            </svg>

        </button>

    </div>
@endif


@if(session('error'))
    <div
        class="mb-6 flex items-center justify-between rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-rose-700 shadow-sm"
        role="alert">

        <div class="flex items-center gap-2.5">

            <svg
                class="h-5 w-5 shrink-0 text-rose-600"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24">

                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 9v4m0 4h.01M10.29 3.86l-8.82 15a2 2 0 001.71 3h17.64a2 2 0 001.71-3l-8.82-15a2 2 0 00-3.42 0z" />

            </svg>

            <span class="text-sm font-medium">
                {{ session('error') }}
            </span>

        </div>

        <button
            type="button"
            onclick="this.parentElement.remove()"
            class="rounded-lg p-1 text-rose-500 transition hover:bg-rose-100 hover:text-rose-700"
            aria-label="Tutup notifikasi">

            <svg
                class="h-4 w-4"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24">

                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M6 18L18 6M6 6l12 12" />

            </svg>

        </button>

    </div>
@endif


{{-- =========================================================
    PAGE HEADER
========================================================= --}}

<div class="mb-6">

    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

        {{-- LEFT --}}
        <div class="min-w-0">

            {{-- Breadcrumb --}}
            <div class="mb-2 flex items-center gap-2 text-[11px]">

                <a
                    href="{{ route('proyek.index') }}"
                    class="font-medium text-slate-400 transition hover:text-slate-700">
                    Manajemen Proyek
                </a>

                <svg
                    class="h-3 w-3 text-slate-300"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
                        d="m9 18 6-6-6-6" />

                </svg>

                <span class="font-medium text-slate-500">
                    Detail Proyek
                </span>

            </div>


            {{-- Title --}}
            <div class="flex flex-wrap items-center gap-2.5">

                <h1 class="text-xl font-semibold tracking-tight text-slate-900 sm:text-2xl">
                    {{ $proyek->nama_proyek }}
                </h1>

                <span
                    class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-[11px] font-semibold {{ $statusConfig['badge'] }}">

                    <span
                        class="h-1.5 w-1.5 rounded-full {{ $statusConfig['dot'] }}">
                    </span>

                    {{ $statusConfig['label'] }}

                </span>

            </div>


            <p class="mt-1 text-xs text-slate-500 sm:text-sm">
                Kelola informasi, tim, dan pekerjaan proyek.
            </p>

        </div>


        {{-- RIGHT --}}
        <div class="flex shrink-0 items-center gap-2">

            <a
                href="{{ route('proyek.index') }}"
                class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-xs font-semibold text-slate-600 shadow-sm transition hover:border-slate-300 hover:bg-slate-50 hover:text-slate-900">

                <svg
                    class="h-4 w-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
                        d="M15 18l-6-6 6-6" />

                </svg>

                Kembali

            </a>


            @if($isAdmin)

                <button
                    type="button"
                    onclick="openModal('modalEditProyek')"
                    class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-3.5 py-2.5 text-xs font-semibold text-white shadow-sm transition hover:bg-slate-800">

                    <svg
                        class="h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M12 20h9" />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M16.5 3.5a2.12 2.12 0 0 1 3 3L8 18l-4 1 1-4Z" />

                    </svg>

                    Edit Proyek

                </button>

            @endif

        </div>

    </div>

</div>


{{-- =========================================================
    PROJECT COVER
========================================================= --}}

<section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

    <div class="relative">

        @if($proyek->gambar)

            <img
                src="{{ asset('storage/' . $proyek->gambar) }}"
                alt="Foto {{ $proyek->nama_proyek }}"
                class="h-52 w-full object-cover sm:h-64 lg:h-72">

            <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent p-5">

                <div class="flex flex-wrap items-end justify-between gap-3">

                    <div class="min-w-0">

                        <p class="mb-1 text-[10px] font-medium uppercase tracking-wider text-white/70">
                            Proyek
                        </p>

                        <h2 class="truncate text-lg font-semibold text-white sm:text-xl">
                            {{ $proyek->nama_proyek }}
                        </h2>

                    </div>


                    <span
                        class="{{ $statusConfig['badge'] }} inline-flex shrink-0 items-center gap-1.5 rounded-full px-2.5 py-1 text-[11px] font-semibold">

                        <span
                            class="{{ $statusConfig['dot'] }} h-1.5 w-1.5 rounded-full">
                        </span>

                        {{ $statusConfig['label'] }}

                    </span>

                </div>

            </div>

        @else

            <div class="flex h-52 w-full items-center justify-center bg-slate-50 sm:h-64 lg:h-72">

                <div class="text-center">

                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-400 shadow-sm">

                        <svg
                            class="h-6 w-6"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.6">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3 16.5l4.5-4.5a2.25 2.25 0 013.182 0L15 16.318m-2.25-2.25l1.568-1.568a2.25 2.25 0 013.182 0L21 16m-9-7.5h.008v.008H12V8.5z" />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3.75 19.5h16.5A1.75 1.75 0 0022 17.75V6.25A1.75 1.75 0 0020.25 4.5H3.75A1.75 1.75 0 002 6.25v11.5a1.75 1.75 0 001.75 1.75z" />

                        </svg>

                    </div>

                    <p class="mt-3 text-sm font-medium text-slate-700">
                        Belum ada foto proyek
                    </p>

                    <p class="mt-1 text-xs text-slate-400">
                        Tambahkan foto proyek melalui menu Edit Proyek.
                    </p>

                </div>

            </div>

        @endif

    </div>


    {{-- INFORMASI PROYEK --}}
    <div class="grid grid-cols-1 divide-y divide-slate-100 sm:grid-cols-2 sm:divide-x sm:divide-y-0 lg:grid-cols-4">

        {{-- Lokasi --}}
        <div class="px-5 py-4">

            <p class="text-[10px] font-medium uppercase tracking-wide text-slate-400">
                Lokasi
            </p>

            <div class="mt-1.5 flex items-center gap-2">

                <svg
                    class="h-4 w-4 shrink-0 text-slate-400"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.7">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M19.5 10.5c0 7.142-7.5 10.5-7.5 10.5S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />

                </svg>

                <p class="truncate text-sm font-semibold text-slate-800">
                    {{ $proyek->lokasi ?: '-' }}
                </p>

            </div>

        </div>


        {{-- Pemilik --}}
        <div class="px-5 py-4">

            <p class="text-[10px] font-medium uppercase tracking-wide text-slate-400">
                Pemilik
            </p>

            <div class="mt-1.5 flex items-center gap-2">

                <svg
                    class="h-4 w-4 shrink-0 text-slate-400"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.7">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M4.5 20.25a8.25 8.25 0 0115 0" />

                </svg>

                <p class="truncate text-sm font-semibold text-slate-800">
                    {{ $proyek->nama_pemilik ?: '-' }}
                </p>

            </div>

        </div>


        {{-- Anggaran --}}
        <div class="px-5 py-4">

            <p class="text-[10px] font-medium uppercase tracking-wide text-slate-400">
                Anggaran
            </p>

            <div class="mt-1.5 flex items-center gap-2">

                <svg
                    class="h-4 w-4 shrink-0 text-slate-400"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.7">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 6v12m3-9.75h-4.125a2.625 2.625 0 100 5.25h2.25a2.625 2.625 0 110 5.25H9" />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M6.75 3.75h10.5A2.25 2.25 0 0119.5 6v12a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 18V6a2.25 2.25 0 012.25-2.25z" />

                </svg>

                <p class="truncate text-sm font-semibold text-slate-800">
                    Rp {{ number_format($proyek->anggaran ?? 0, 0, ',', '.') }}
                </p>

            </div>

        </div>


        {{-- Periode --}}
        <div class="px-5 py-4">

            <p class="text-[10px] font-medium uppercase tracking-wide text-slate-400">
                Periode Proyek
            </p>

            <div class="mt-1.5 flex items-center gap-2">

                <svg
                    class="h-4 w-4 shrink-0 text-slate-400"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.7">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M6.75 3v2.25M17.25 3v2.25M3.75 8.25h16.5" />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M5.25 5.25h13.5A2.25 2.25 0 0121 7.5v11.25A2.25 2.25 0 0118.75 21H5.25A2.25 2.25 0 013 18.75V7.5a2.25 2.25 0 012.25-2.25z" />

                </svg>

                <p class="truncate text-sm font-semibold text-slate-800">

                    {{ $proyek->tanggal_mulai
                        ? \Carbon\Carbon::parse($proyek->tanggal_mulai)->format('d M Y')
                        : '-' }}

                    <span class="font-normal text-slate-400">
                        —
                    </span>

                    {{ $proyek->estimasi_selesai
                        ? \Carbon\Carbon::parse($proyek->estimasi_selesai)->format('d M Y')
                        : '-' }}

                </p>

            </div>

        </div>

    </div>

</section>


{{-- =========================================================
    QUICK ACCESS
========================================================= --}}

<section class="mt-6">

    <div class="mb-4">

        <h2 class="text-base font-semibold tracking-tight text-slate-900">
            Akses Cepat
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Akses informasi utama yang berkaitan dengan proyek.
        </p>

    </div>


    <div class="grid grid-cols-1 gap-3 md:grid-cols-3">

        {{-- BUKU HARIAN --}}
        <a
            href="{{ route('proyek.laporan.admin', $proyek->id) }}"
            class="group rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md">

            <div class="flex items-start gap-3.5">

                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">

                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.7"
                            d="M4 19.5A2.5 2.5 0 016.5 17H20M4 19.5A2.5 2.5 0 001.5 17V5A2.5 2.5 0 014 2.5h12A2.5 2.5 0 0118.5 5v12" />

                    </svg>

                </div>


                <div class="min-w-0 flex-1">

                    <div class="flex items-center justify-between gap-3">

                        <h3 class="text-sm font-semibold text-slate-900">
                            Buku Harian Lapangan
                        </h3>

                        <svg
                            class="h-4 w-4 shrink-0 text-slate-300 transition group-hover:translate-x-0.5 group-hover:text-blue-500"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="m9 18 6-6-6-6" />

                        </svg>

                    </div>

                    <p class="mt-1 text-xs leading-5 text-slate-500">
                        Pantau laporan, foto, aktivitas, dan progres harian.
                    </p>

                </div>

            </div>

        </a>


        {{-- KEUANGAN --}}
        <a
            href="{{ route('proyek.keuangan', $proyek->id) }}"
            class="group rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-emerald-200 hover:shadow-md">

            <div class="flex items-start gap-3.5">

                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">

                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.7"
                            d="M3 10h18M7 15h.01M11 15h2M5 6h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2Z" />

                    </svg>

                </div>


                <div class="min-w-0 flex-1">

                    <div class="flex items-center justify-between gap-3">

                        <h3 class="text-sm font-semibold text-slate-900">
                            Laporan Keuangan
                        </h3>

                        <svg
                            class="h-4 w-4 shrink-0 text-slate-300 transition group-hover:translate-x-0.5 group-hover:text-emerald-500"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="m9 18 6-6-6-6" />

                        </svg>

                    </div>

                    <p class="mt-1 text-xs leading-5 text-slate-500">
                        Pantau pemasukan, pengeluaran, kasbon, dan payroll.
                    </p>

                </div>

            </div>

        </a>


        {{-- AI FORECAST --}}
        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">

            <div class="flex items-start gap-3.5">

                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white text-slate-400 shadow-sm">

                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.7"
                            d="M13 10V3L4 14h7l-2 7 9-11h-7z" />

                    </svg>

                </div>


                <div class="min-w-0">

                    <div class="flex items-center gap-2">

                        <h3 class="text-sm font-semibold text-slate-700">
                            AI Forecasting
                        </h3>

                        <span class="rounded-full bg-white px-2 py-0.5 text-[9px] font-medium text-slate-400">
                            Segera
                        </span>

                    </div>

                    <p class="mt-1 text-xs leading-5 text-slate-400">
                        Prediksi kebutuhan material dan tren proyek.
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>


{{-- =========================================================
    PROJECT MANAGEMENT
========================================================= --}}

<section class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

    {{-- TAB HEADER --}}
    <div class="border-b border-slate-200 px-5 sm:px-6">

        <div class="overflow-x-auto">

            <nav
                class="flex min-w-max gap-6"
                aria-label="Navigasi proyek">

                {{-- TIM --}}
                <button
                    id="tab-btn-tim"
                    type="button"
                    onclick="switchTabProyek('tim')"
                    class="inline-flex items-center gap-2 border-b-2 border-slate-900 px-1 py-4 text-sm font-semibold text-slate-900 transition-colors focus:outline-none">

                    <svg
                        class="h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.7"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />

                    </svg>

                    Tim Proyek

                    <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-600">
                        {{ $jumlahTim }}
                    </span>

                </button>


                {{-- PEKERJAAN --}}
                <button
                    id="tab-btn-pekerjaan"
                    type="button"
                    onclick="switchTabProyek('pekerjaan')"
                    class="inline-flex items-center gap-2 border-b-2 border-transparent px-1 py-4 text-sm font-medium text-slate-500 transition-colors hover:border-slate-300 hover:text-slate-800 focus:outline-none">

                    <svg
                        class="h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.7"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />

                    </svg>

                    Item Pekerjaan

                    <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-600">
                        {{ $jumlahPekerjaan }}
                    </span>

                </button>

            </nav>

        </div>

    </div>


    {{-- =====================================================
        TAB TIM
    ====================================================== --}}

    <div
        id="tab-content-tim"
        class="block p-5 sm:p-6 lg:p-7">

        <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">

            <div>

                <h2 class="text-base font-semibold tracking-tight text-slate-900">
                    Tim Proyek Lapangan
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Kelola pegawai yang ditugaskan pada proyek ini.
                </p>

            </div>


            <div class="flex flex-col gap-2 sm:flex-row">

                {{-- SEARCH --}}
                <div class="relative w-full sm:w-64">

                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">

                        <svg
                            class="h-4 w-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.7"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />

                        </svg>

                    </div>

                    <input
                        type="text"
                        id="searchTimProyek"
                        onkeyup="filterTimProyek()"
                        placeholder="Cari pekerja..."
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-9 pr-4 text-sm text-slate-800 placeholder-slate-400 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-500/10">

                </div>


                @if($isAdmin)

                    <button
                        type="button"
                        onclick="openModal('modalTambahPekerja')"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">

                        <svg
                            class="h-4 w-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M12 6v12m6-6H6" />

                        </svg>

                        Tugaskan Pekerja

                    </button>

                @endif

            </div>

        </div>


        @if($jumlahTim > 0)

            <div
                id="timProyekContainer"
                class="space-y-6">

                @foreach($pekerjaPerJabatan as $namaJabatan => $daftarPekerja)

                    <div class="kelompok-jabatan">

                        <div class="mb-3 flex items-center gap-2">

                            <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>

                            <h4 class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                {{ $namaJabatan }}
                            </h4>

                            <span class="text-xs text-slate-400">
                                {{ $daftarPekerja->count() }} orang
                            </span>

                        </div>


                        <div class="overflow-hidden rounded-xl border border-slate-200">

                            <div class="overflow-x-auto">

                                <table class="w-full min-w-[600px] text-left">

                                    <thead class="border-b border-slate-200 bg-slate-50">

                                        <tr>

                                            <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                                Nama Pekerja
                                            </th>

                                            <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                                Kontak
                                            </th>

                                            @if($isAdmin)

                                                <th class="w-20 px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">
                                                    Aksi
                                                </th>

                                            @endif

                                        </tr>

                                    </thead>


                                    <tbody class="divide-y divide-slate-100 text-sm text-slate-700">

                                        @foreach($daftarPekerja as $pekerja)

                                            <tr class="row-pekerja transition-colors hover:bg-slate-50/70">

                                                <td class="cell-nama-pekerja px-4 py-3.5">

                                                    <div class="flex items-center gap-3">

                                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-xs font-semibold text-slate-600">
                                                            {{ strtoupper(substr($pekerja->nama, 0, 1)) }}
                                                        </div>

                                                        <div class="min-w-0">

                                                            <p class="truncate font-semibold text-slate-800">
                                                                {{ $pekerja->nama }}
                                                            </p>

                                                            <p class="mt-0.5 text-xs text-slate-400">
                                                                {{ $namaJabatan }}
                                                            </p>

                                                        </div>

                                                    </div>

                                                </td>


                                                <td class="px-4 py-3.5 text-sm text-slate-500">
                                                    {{ $pekerja->no_telp ?? '-' }}
                                                </td>


                                                @if($isAdmin)

                                                    <td class="px-4 py-3.5 text-right">

                                                        <form
                                                            action="{{ route('proyek.remove', [
                                                                'proyek' => $proyek->id,
                                                                'pegawai' => $pekerja->id
                                                            ]) }}"
                                                            method="POST"
                                                            class="form-hapus-pekerja inline">

                                                            @csrf
                                                            @method('DELETE')

                                                            <button
                                                                type="button"
                                                                onclick="konfirmasiHapusPekerja(this)"
                                                                class="rounded-lg p-2 text-slate-400 transition hover:bg-rose-50 hover:text-rose-600"
                                                                title="Copot dari proyek">

                                                                <svg
                                                                    class="h-4 w-4"
                                                                    fill="none"
                                                                    stroke="currentColor"
                                                                    viewBox="0 0 24 24">

                                                                    <path
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        stroke-width="1.7"
                                                                        d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6" />

                                                                </svg>

                                                            </button>

                                                        </form>

                                                    </td>

                                                @endif

                                            </tr>

                                        @endforeach

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>


            <p
                id="pesanTimKosong"
                class="hidden py-8 text-center text-sm text-slate-400">
                Tidak ada pekerja yang cocok dengan pencarian.
            </p>

        @else

            <div class="rounded-xl border border-dashed border-slate-200 bg-slate-50/50 px-5 py-12 text-center">

                <div class="mx-auto mb-3 flex h-11 w-11 items-center justify-center rounded-xl bg-white shadow-sm">

                    <svg
                        class="h-5 w-5 text-slate-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.5"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857" />

                    </svg>

                </div>

                <p class="text-sm font-medium text-slate-500">
                    Belum ada pekerja yang ditugaskan.
                </p>

                @if($isAdmin)

                    <p class="mt-1 text-xs text-slate-400">
                        Tambahkan pekerja untuk mulai membentuk tim proyek.
                    </p>

                @endif

            </div>

        @endif

    </div>


    {{-- =====================================================
        TAB PEKERJAAN
    ====================================================== --}}

    <div
        id="tab-content-pekerjaan"
        class="hidden p-5 sm:p-6 lg:p-7">

        <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">

            <div>

                <h2 class="text-base font-semibold tracking-tight text-slate-900">
                    Item Pekerjaan & Bobot
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Total bobot seluruh pekerjaan maksimal 100%.
                </p>

            </div>


            {{-- PROGRESS --}}
            <div class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 sm:w-auto">

                <div class="flex items-center justify-between gap-8">

                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Bobot Terisi
                        </p>

                        <p class="mt-1 text-xl font-semibold tracking-tight {{ $totalBobot >= 100 ? 'text-emerald-600' : 'text-slate-800' }}">

                            {{ $totalBobotFormatted }}

                            <span class="text-xs font-medium text-slate-400">
                                / 100%
                            </span>

                        </p>

                    </div>


                    <div class="w-28">

                        <div class="h-1.5 overflow-hidden rounded-full bg-slate-200">

                            <div
                                class="h-full rounded-full transition-all {{ $totalBobot >= 100 ? 'bg-emerald-500' : 'bg-blue-500' }}"
                                style="width: {{ min($totalBobot, 100) }}%;">
                            </div>

                        </div>

                        <p class="mt-1.5 text-right text-xs text-slate-400">
                            Sisa {{ $sisaBobotFormatted }}%
                        </p>

                    </div>

                </div>

            </div>

        </div>


        {{-- ADD WORK --}}
        @if($totalBobot < 100 && $isAdmin)

            <form
                action="{{ route('item-pekerjaan.store', $proyek->id) }}"
                method="POST"
                class="mb-7 rounded-xl border border-slate-200 bg-slate-50/70 p-4 sm:p-5">

                @csrf

                <div class="mb-4">

                    <p class="text-sm font-semibold text-slate-700">
                        Tambah Item Pekerjaan
                    </p>

                    <p class="mt-1 text-xs text-slate-400">
                        Sisa bobot yang tersedia {{ $sisaBobotFormatted }}%.
                    </p>

                </div>


                <div
                    id="dynamic-form-container"
                    class="space-y-3">

                    <div class="row-item flex flex-col gap-3 rounded-xl border border-slate-200 bg-white p-3 sm:flex-row sm:items-end">

                        <div class="min-w-0 flex-1">

                            <label class="mb-1.5 block text-xs font-semibold text-slate-500">
                                Nama Pekerjaan
                            </label>

                            <input
                                type="text"
                                name="nama_pekerjaan[]"
                                placeholder="Contoh: Pekerjaan Atap"
                                required
                                class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-800 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-500/10">

                        </div>


                        <div class="sm:w-32">

                            <label class="mb-1.5 block text-xs font-semibold text-slate-500">
                                Bobot
                            </label>

                            <div class="relative">

                                <input
                                    type="number"
                                    name="bobot[]"
                                    step="0.01"
                                    min="0.01"
                                    max="{{ number_format($sisaBobot, 2, '.', '') }}"
                                    placeholder="{{ number_format($sisaBobot, 2, ',', '.') }}"
                                    required
                                    class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 pr-8 text-sm text-slate-800 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-500/10">

                                <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-xs text-slate-400">
                                    %
                                </span>

                            </div>

                        </div>


                        <button
                            type="button"
                            onclick="hapusBaris(this)"
                            class="btn-remove hidden shrink-0 rounded-lg border border-transparent p-2.5 text-slate-400 transition hover:border-rose-100 hover:bg-rose-50 hover:text-rose-500"
                            title="Hapus baris">

                            <svg
                                class="h-4 w-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.7"
                                    d="M6 18L18 6M6 6l12 12" />

                            </svg>

                        </button>

                    </div>

                </div>


                <div class="mt-4 flex flex-col gap-2.5 border-t border-slate-200 pt-4 sm:flex-row">

                    <button
                        type="button"
                        onclick="tambahBaris()"
                        class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-50 hover:text-slate-900">

                        <svg
                            class="h-4 w-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.7"
                                d="M12 6v12m6-6H6" />

                        </svg>

                        Tambah Baris

                    </button>


                    <button
                        type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">

                        <svg
                            class="h-4 w-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.7"
                                d="M5 13l4 4L19 7" />

                        </svg>

                        Simpan Item

                    </button>

                </div>

            </form>

        @elseif($totalBobot >= 100)

            <div class="mb-7 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4">

                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-emerald-100">

                    <svg
                        class="h-4 w-4 text-emerald-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.7"
                            d="M5 13l4 4L19 7" />

                    </svg>

                </div>

                <div>

                    <p class="text-sm font-semibold text-emerald-800">
                        Bobot pekerjaan sudah lengkap.
                    </p>

                    <p class="mt-0.5 text-xs leading-5 text-emerald-700">
                        Total bobot telah mencapai 100% dan tidak dapat menambahkan item baru.
                    </p>

                </div>

            </div>

        @endif


        {{-- WORK TABLE --}}
        <div class="overflow-hidden rounded-xl border border-slate-200">

            <div class="overflow-x-auto">

                <table class="w-full min-w-[620px] text-left">

                    <thead class="border-b border-slate-200 bg-slate-50">

                        <tr>

                            <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Tahapan Pekerjaan
                            </th>

                            <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Bobot
                            </th>

                            <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Progres
                            </th>

                            @if($isAdmin)

                                <th class="w-20 px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    Aksi
                                </th>

                            @endif

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-100">

                        @forelse($proyek->itemPekerjaans as $item)

                            <tr class="transition-colors hover:bg-slate-50/70">

                                <td class="px-4 py-3.5">

                                    <p class="text-sm font-semibold text-slate-800">
                                        {{ $item->nama_pekerjaan }}
                                    </p>

                                </td>


                                <td class="px-4 py-3.5 text-center">

                                    <span class="inline-flex rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                                        {{ number_format($item->bobot, 2, ',', '.') }}%
                                    </span>

                                </td>


                                <td class="px-4 py-3.5">

                                    <div class="mx-auto flex max-w-[150px] items-center gap-2">

                                        <div class="h-1.5 flex-1 overflow-hidden rounded-full bg-slate-100">

                                            <div
                                                class="h-full rounded-full {{ $item->progres_sekarang >= 100 ? 'bg-emerald-500' : 'bg-blue-500' }}"
                                                style="width: {{ min((float) $item->progres_sekarang, 100) }}%;">
                                            </div>

                                        </div>

                                        <span
                                            class="w-14 text-right text-xs font-semibold {{ $item->progres_sekarang >= 100 ? 'text-emerald-600' : 'text-blue-600' }}">

                                            {{ number_format($item->progres_sekarang, 2, ',', '.') }}%

                                        </span>

                                    </div>

                                </td>


                                @if($isAdmin)

                                    <td class="px-4 py-3.5 text-right">

                                        <form
                                            action="{{ route('item-pekerjaan.destroy', $item->id) }}"
                                            method="POST"
                                            class="form-hapus-item inline-block">

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="button"
                                                onclick="konfirmasiHapusItem(this)"
                                                class="rounded-lg p-2 text-slate-400 transition hover:bg-rose-50 hover:text-rose-600"
                                                title="Hapus item">

                                                <svg
                                                    class="h-4 w-4"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24">

                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="1.7"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />

                                                </svg>

                                            </button>

                                        </form>

                                    </td>

                                @endif

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="{{ $isAdmin ? 4 : 3 }}"
                                    class="px-5 py-12 text-center">

                                    <div class="mx-auto mb-3 flex h-11 w-11 items-center justify-center rounded-xl bg-slate-50">

                                        <svg
                                            class="h-5 w-5 text-slate-400"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24">

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="1.5"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />

                                        </svg>

                                    </div>

                                    <p class="text-sm font-medium text-slate-500">
                                        Belum ada item pekerjaan.
                                    </p>

                                    @if($isAdmin)

                                        <p class="mt-1 text-xs text-slate-400">
                                            Tambahkan tahapan pekerjaan untuk proyek ini.
                                        </p>

                                    @endif

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</section>


{{-- =========================================================
    MODALS
========================================================= --}}

@if($isAdmin)

    {{-- =====================================================
        MODAL EDIT PROYEK
    ====================================================== --}}

    <div
        id="modalEditProyek"
        class="fixed inset-0 z-[999] hidden overflow-y-auto"
        role="dialog"
        aria-modal="true"
        aria-labelledby="modalEditProyekTitle">

        {{-- OVERLAY --}}
        <div
            class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm"
            onclick="closeModal('modalEditProyek')">
        </div>


        {{-- CENTER WRAPPER --}}
        <div class="relative flex min-h-full items-center justify-center p-4 sm:p-6">

            {{-- MODAL --}}
            <div
                class="relative w-full max-w-xl overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl"
                onclick="event.stopPropagation()">

                {{-- HEADER --}}
                <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4 sm:px-6">

                    <div>

                        <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">
                            Konfigurasi
                        </p>

                        <h3
                            id="modalEditProyekTitle"
                            class="mt-1 text-base font-semibold text-slate-900">
                            Edit Data Proyek
                        </h3>

                    </div>


                    <button
                        type="button"
                        onclick="closeModal('modalEditProyek')"
                        class="rounded-lg bg-slate-50 p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600">

                        <svg
                            class="h-4 w-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.7"
                                d="M6 18L18 6M6 6l12 12" />

                        </svg>

                    </button>

                </div>


                <form
                    action="{{ route('proyek.update', $proyek->id) }}"
                    method="POST"
                    enctype="multipart/form-data">

                    @csrf
                    @method('PUT')


                    {{-- BODY --}}
                    <div class="max-h-[calc(100vh-150px)] overflow-y-auto px-5 py-5 sm:px-6">

                        {{-- IMAGE --}}
                        <div class="mb-5">

                            <label class="mb-1.5 block text-xs font-semibold text-slate-500">
                                Gambar Proyek
                            </label>

                            <div class="overflow-hidden rounded-xl border border-dashed border-slate-300 bg-slate-50 transition hover:border-blue-300 hover:bg-blue-50/30">

                                @if($proyek->gambar)

                                    <div class="relative">

                                        <img
                                            src="{{ asset('storage/' . $proyek->gambar) }}"
                                            alt="Foto proyek"
                                            class="h-36 w-full object-cover">

                                        <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/60 to-transparent px-3 pb-3 pt-8">

                                            <p class="text-[11px] font-medium text-white">
                                                Foto proyek saat ini
                                            </p>

                                        </div>

                                    </div>

                                @endif


                                <input
                                    id="file-upload-edit"
                                    name="gambar"
                                    type="file"
                                    class="sr-only"
                                    accept="image/png, image/jpeg, image/jpg"
                                    onchange="previewTextEdit(this)">


                                <label
                                    for="file-upload-edit"
                                    class="flex cursor-pointer items-center gap-4 px-4 py-4">

                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white shadow-sm">

                                        <svg
                                            class="h-5 w-5 text-slate-400"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24">

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="1.6"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M14 6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2Z" />

                                        </svg>

                                    </div>


                                    <div class="min-w-0">

                                        <p class="text-sm font-semibold text-blue-600">
                                            Pilih gambar baru
                                        </p>

                                        <p
                                            id="file-name-edit"
                                            class="mt-0.5 truncate text-xs text-slate-400">
                                            JPG / PNG · Maks. 2 MB
                                        </p>

                                    </div>

                                </label>

                            </div>

                        </div>


                        {{-- NAMA --}}
                        <div class="mb-4">

                            <label class="mb-1.5 block text-xs font-semibold text-slate-500">
                                Nama Proyek
                            </label>

                            <input
                                type="text"
                                name="nama_proyek"
                                value="{{ $proyek->nama_proyek }}"
                                required
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-900 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-500/10">

                        </div>


                        {{-- LOKASI --}}
                        <div class="mb-4">

                            <label class="mb-1.5 block text-xs font-semibold text-slate-500">
                                Lokasi Proyek
                            </label>

                            <input
                                type="text"
                                name="lokasi"
                                value="{{ $proyek->lokasi }}"
                                required
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-900 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-500/10">

                        </div>


                        {{-- CLIENT --}}
                        <div class="mb-4 grid grid-cols-1 gap-4 sm:grid-cols-2">

                            <div>

                                <label class="mb-1.5 block text-xs font-semibold text-slate-500">
                                    Pemilik / Klien
                                </label>

                                <input
                                    type="text"
                                    name="nama_pemilik"
                                    value="{{ $proyek->nama_pemilik }}"
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-900 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-500/10">

                            </div>


                            <div>

                                <label class="mb-1.5 block text-xs font-semibold text-slate-500">
                                    Kontak
                                </label>

                                <input
                                    type="text"
                                    name="kontak_pemilik"
                                    value="{{ $proyek->kontak_pemilik }}"
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-900 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-500/10">

                            </div>

                        </div>


                        {{-- ANGGARAN --}}
                        <div class="mb-4">

                            <label class="mb-1.5 block text-xs font-semibold text-slate-500">
                                Anggaran / RAB
                            </label>

                            <div class="relative">

                                <span class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center text-xs font-medium text-slate-400">
                                    Rp
                                </span>

                                <input
                                    type="number"
                                    name="anggaran"
                                    value="{{ $proyek->anggaran }}"
                                    min="0"
                                    required
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-9 pr-3.5 text-sm text-slate-900 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-500/10">

                            </div>

                        </div>


                        {{-- DATES --}}
                        <div class="mb-4 grid grid-cols-1 gap-4 sm:grid-cols-2">

                            <div>

                                <label class="mb-1.5 block text-xs font-semibold text-slate-500">
                                    Tanggal Mulai
                                </label>

                                <input
                                    type="date"
                                    name="tanggal_mulai"
                                    value="{{ $proyek->tanggal_mulai?->format('Y-m-d') }}"
                                    required
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-900 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-500/10">

                            </div>


                            <div>

                                <label class="mb-1.5 block text-xs font-semibold text-slate-500">
                                    Estimasi Selesai
                                </label>

                                <input
                                    type="date"
                                    name="estimasi_selesai"
                                    value="{{ $proyek->estimasi_selesai?->format('Y-m-d') }}"
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-900 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-500/10">

                            </div>

                        </div>


                        {{-- STATUS --}}
                        <div>

                            <label class="mb-1.5 block text-xs font-semibold text-slate-500">
                                Status Proyek
                            </label>

                            <select
                                name="status"
                                required
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-900 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-500/10">

                                <option value="Akan Dimulai" {{ $proyek->status === 'Akan Dimulai' ? 'selected' : '' }}>
                                    Akan Dimulai
                                </option>

                                <option value="Berjalan" {{ $proyek->status === 'Berjalan' ? 'selected' : '' }}>
                                    Berjalan
                                </option>

                                <option value="Ditunda" {{ $proyek->status === 'Ditunda' ? 'selected' : '' }}>
                                    Ditunda
                                </option>

                                <option value="Selesai" {{ $proyek->status === 'Selesai' ? 'selected' : '' }}>
                                    Selesai
                                </option>

                            </select>

                        </div>

                    </div>


                    {{-- FOOTER --}}
                    <div class="flex justify-end gap-2.5 border-t border-slate-100 px-5 py-4 sm:px-6">

                        <button
                            type="button"
                            onclick="closeModal('modalEditProyek')"
                            class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50 hover:text-slate-900">

                            Batal

                        </button>


                        <button
                            type="submit"
                            class="rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">

                            Simpan Perubahan

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>


    {{-- =====================================================
        MODAL TUGASKAN PEKERJA
    ====================================================== --}}

    <div
        id="modalTambahPekerja"
        class="fixed inset-0 z-[999] hidden overflow-y-auto"
        role="dialog"
        aria-modal="true"
        aria-labelledby="modalTambahPekerjaTitle">

        {{-- OVERLAY --}}
        <div
            class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm"
            onclick="closeModal('modalTambahPekerja')">
        </div>


        {{-- CENTER WRAPPER --}}
        <div class="relative flex min-h-full items-center justify-center p-4 sm:p-6">

            {{-- MODAL --}}
            <div
                class="relative w-full max-w-md overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl"
                onclick="event.stopPropagation()">

                {{-- HEADER --}}
                <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">

                    <div>

                        <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">
                            Tim Proyek
                        </p>

                        <h3
                            id="modalTambahPekerjaTitle"
                            class="mt-1 text-base font-semibold text-slate-900">

                            Tugaskan Pekerja

                        </h3>

                    </div>


                    <button
                        type="button"
                        onclick="closeModal('modalTambahPekerja')"
                        class="rounded-lg bg-slate-50 p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600">

                        <svg
                            class="h-4 w-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.7"
                                d="M6 18L18 6M6 6l12 12" />

                        </svg>

                    </button>

                </div>


                <form
                    action="{{ route('proyek.assign', $proyek->id) }}"
                    method="POST">

                    @csrf

                    <div class="px-5 py-5">

                        <div class="mb-4 rounded-xl border border-blue-100 bg-blue-50/60 p-3.5">

                            <div class="flex items-start gap-3">

                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white">

                                    <svg
                                        class="h-4 w-4 text-blue-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24">

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1.7"
                                            d="M12 4v16m8-8H4" />

                                    </svg>

                                </div>


                                <div>

                                    <p class="text-sm font-semibold text-blue-800">
                                        Tambahkan anggota tim
                                    </p>

                                    <p class="mt-0.5 text-xs leading-5 text-blue-600">
                                        Hanya pegawai aktif yang belum memiliki penugasan pada proyek aktif yang tersedia.
                                    </p>

                                </div>

                            </div>

                        </div>


                        <label class="mb-1.5 block text-xs font-semibold text-slate-500">
                            Pilih Pekerja
                        </label>

                        <select
                            name="pegawai_id"
                            required
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-900 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-500/10">

                            <option value="" disabled selected>
                                Pilih pekerja...
                            </option>

                            @foreach($pegawaiTersedia as $tersedia)

                                <option value="{{ $tersedia->id }}">
                                    {{ $tersedia->nama }}
                                    · {{ $tersedia->jabatan->nama_jabatan ?? 'Staf' }}
                                </option>

                            @endforeach

                        </select>


                        @if($pegawaiTersedia->isEmpty())

                            <p class="mt-2 text-xs text-amber-600">
                                Tidak ada pegawai yang tersedia untuk ditugaskan.
                            </p>

                        @endif

                    </div>


                    <div class="flex justify-end gap-2.5 border-t border-slate-100 px-5 py-4">

                        <button
                            type="button"
                            onclick="closeModal('modalTambahPekerja')"
                            class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50 hover:text-slate-900">

                            Batal

                        </button>


                        <button
                            type="submit"
                            {{ $pegawaiTersedia->isEmpty() ? 'disabled' : '' }}
                            class="rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-40">

                            Tugaskan ke Tim

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endif


{{-- =========================================================
    JAVASCRIPT
========================================================= --}}

<script>
    /*
    |--------------------------------------------------------------------------
    | TAB PROYEK
    |--------------------------------------------------------------------------
    */

    function switchTabProyek(tabName) {

        const tabTim = document.getElementById('tab-content-tim');
        const tabPekerjaan = document.getElementById('tab-content-pekerjaan');

        const btnTim = document.getElementById('tab-btn-tim');
        const btnPekerjaan = document.getElementById('tab-btn-pekerjaan');

        if (!tabTim || !tabPekerjaan || !btnTim || !btnPekerjaan) {
            return;
        }

        const activeClass = [
            'inline-flex',
            'items-center',
            'gap-2',
            'border-b-2',
            'border-slate-900',
            'px-1',
            'py-4',
            'text-sm',
            'font-semibold',
            'text-slate-900',
            'transition-colors',
            'focus:outline-none'
        ].join(' ');

        const inactiveClass = [
            'inline-flex',
            'items-center',
            'gap-2',
            'border-b-2',
            'border-transparent',
            'px-1',
            'py-4',
            'text-sm',
            'font-medium',
            'text-slate-500',
            'transition-colors',
            'hover:border-slate-300',
            'hover:text-slate-800',
            'focus:outline-none'
        ].join(' ');


        if (tabName === 'tim') {

            tabTim.classList.remove('hidden');
            tabPekerjaan.classList.add('hidden');

            btnTim.className = activeClass;
            btnPekerjaan.className = inactiveClass;

        } else {

            tabTim.classList.add('hidden');
            tabPekerjaan.classList.remove('hidden');

            btnTim.className = inactiveClass;
            btnPekerjaan.className = activeClass;

        }
    }


    /*
    |--------------------------------------------------------------------------
    | FILTER TIM PROYEK
    |--------------------------------------------------------------------------
    */

    function filterTimProyek() {

        const searchInput = document.getElementById('searchTimProyek');

        if (!searchInput) {
            return;
        }

        const keyword = searchInput.value.toLowerCase().trim();

        const kelompokList = document.querySelectorAll(
            '#timProyekContainer .kelompok-jabatan'
        );

        const pesanKosong = document.getElementById('pesanTimKosong');

        let adaHasil = false;


        kelompokList.forEach(kelompok => {

            const rows = kelompok.querySelectorAll('.row-pekerja');

            let adaBarisTampil = false;


            rows.forEach(row => {

                const namaCell = row.querySelector('.cell-nama-pekerja');

                const nama = namaCell
                    ? namaCell.textContent.toLowerCase()
                    : '';

                const cocok = nama.includes(keyword);

                row.style.display = cocok ? '' : 'none';

                if (cocok) {
                    adaBarisTampil = true;
                }

            });


            kelompok.style.display = adaBarisTampil ? '' : 'none';

            if (adaBarisTampil) {
                adaHasil = true;
            }

        });


        if (pesanKosong) {
            pesanKosong.classList.toggle('hidden', adaHasil);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | MODAL
    |--------------------------------------------------------------------------
    */

    function openModal(modalID) {

        const modal = document.getElementById(modalID);

        if (!modal) {
            return;
        }

        modal.classList.remove('hidden');

        document.body.classList.add('overflow-hidden');
    }


    function closeModal(modalID) {

        const modal = document.getElementById(modalID);

        if (!modal) {
            return;
        }

        modal.classList.add('hidden');

        const masihAdaModalTerbuka = document.querySelector(
            '[id^="modal"]:not(.hidden)'
        );

        if (!masihAdaModalTerbuka) {
            document.body.classList.remove('overflow-hidden');
        }
    }


    function toggleModal(modalID) {

        const modal = document.getElementById(modalID);

        if (!modal) {
            return;
        }

        if (modal.classList.contains('hidden')) {
            openModal(modalID);
        } else {
            closeModal(modalID);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | ESC CLOSE MODAL
    |--------------------------------------------------------------------------
    */

    document.addEventListener('keydown', function(event) {

        if (event.key !== 'Escape') {
            return;
        }

        const modalTerbuka = document.querySelector(
            '[id^="modal"]:not(.hidden)'
        );

        if (!modalTerbuka) {
            return;
        }

        closeModal(modalTerbuka.id);
    });


    /*
    |--------------------------------------------------------------------------
    | PREVIEW FILE
    |--------------------------------------------------------------------------
    */

    function previewTextEdit(input) {

        const label = document.getElementById('file-name-edit');

        if (!label) {
            return;
        }


        if (input.files && input.files[0]) {

            const file = input.files[0];

            label.innerText =
                'File terpilih: ' + file.name;

            label.classList.remove('text-slate-400');

            label.classList.add(
                'text-blue-600',
                'font-semibold'
            );

        } else {

            label.innerText =
                'JPG / PNG · Maks. 2 MB';

            label.classList.remove(
                'text-blue-600',
                'font-semibold'
            );

            label.classList.add('text-slate-400');
        }
    }


    /*
    |--------------------------------------------------------------------------
    | TAMBAH BARIS PEKERJAAN
    |--------------------------------------------------------------------------
    */

    function tambahBaris() {

        const container =
            document.getElementById('dynamic-form-container');

        if (!container) {
            return;
        }

        const firstRow =
            container.querySelector('.row-item');

        if (!firstRow) {
            return;
        }

        const newRow =
            firstRow.cloneNode(true);


        newRow
            .querySelectorAll('input')
            .forEach(input => {
                input.value = '';
            });


        const btnRemove =
            newRow.querySelector('.btn-remove');

        if (btnRemove) {
            btnRemove.classList.remove('hidden');
        }

        container.appendChild(newRow);
    }


    /*
    |--------------------------------------------------------------------------
    | HAPUS BARIS PEKERJAAN
    |--------------------------------------------------------------------------
    */

    function hapusBaris(button) {

        const rows =
            document.querySelectorAll(
                '#dynamic-form-container .row-item'
            );

        if (rows.length <= 1) {
            return;
        }

        const row =
            button.closest('.row-item');

        if (row) {
            row.remove();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | KONFIRMASI COPOT PEKERJA
    |--------------------------------------------------------------------------
    */

    function konfirmasiHapusPekerja(button) {

        const form =
            button.closest('.form-hapus-pekerja');

        if (!form) {
            return;
        }


        if (typeof Swal === 'undefined') {
            return;
        }


        Swal.fire({

            title: 'Copot pekerja?',
            text: 'Pekerja akan dilepas dari tim proyek ini.',
            icon: 'warning',

            showCancelButton: true,

            confirmButtonColor: '#0f172a',
            cancelButtonColor: '#e2e8f0',

            confirmButtonText: 'Ya, copot',
            cancelButtonText: 'Batal',

            reverseButtons: true,

            customClass: {

                popup: 'rounded-2xl',

                confirmButton:
                    'rounded-xl px-4 py-2.5 text-sm font-semibold',

                cancelButton:
                    'rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-700'

            }

        }).then(result => {

            if (result.isConfirmed) {
                HTMLFormElement.prototype.submit.call(form);
            }

        });
    }


    /*
    |--------------------------------------------------------------------------
    | KONFIRMASI HAPUS ITEM
    |--------------------------------------------------------------------------
    */

    function konfirmasiHapusItem(button) {

        const form =
            button.closest('.form-hapus-item');

        if (!form) {
            return;
        }


        if (typeof Swal === 'undefined') {
            return;
        }


        Swal.fire({

            title: 'Hapus item pekerjaan?',
            text: 'Item pekerjaan akan dihapus dari proyek. Jika sudah memiliki progres, server akan menolaknya.',
            icon: 'warning',

            showCancelButton: true,

            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#e2e8f0',

            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal',

            reverseButtons: true,

            customClass: {

                popup: 'rounded-2xl',

                confirmButton:
                    'rounded-xl px-4 py-2.5 text-sm font-semibold',

                cancelButton:
                    'rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-700'

            }

        }).then(result => {

            if (result.isConfirmed) {
                HTMLFormElement.prototype.submit.call(form);
            }

        });
    }
</script>

@endsection