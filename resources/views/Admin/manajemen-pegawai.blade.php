@extends('layouts.admin')

@section('title', 'Manajemen Pegawai')

@section('content')

<div class="space-y-6">

    {{-- =========================================================
        SUCCESS NOTIFICATION
    ========================================================== --}}
    @if(session('success'))
    <div
        id="successAlert"
        class="flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">

        <svg
            class="mt-0.5 h-4 w-4 shrink-0"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24">
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M5 13l4 4L19 7" />
        </svg>

        <div class="flex-1">

            <p class="text-[12px] font-semibold">
                Berhasil
            </p>

            <p class="mt-0.5 text-[11px] leading-5">
                {{ session('success') }}
            </p>

        </div>

        <button
            type="button"
            onclick="document.getElementById('successAlert')?.remove()"
            class="text-emerald-600 transition hover:text-emerald-800">

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
        VALIDATION ERROR
    ========================================================== --}}
    @if($errors->any())
    <div
        class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-rose-800">

        <div class="flex items-start gap-3">

            <svg
                class="mt-0.5 h-4 w-4 shrink-0"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24">
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 9v3m0 4h.01M10.29 3.86l-8.82 15A2 2 0 003.2 22h17.6a2 2 0 001.73-3.14l-8.82-15a2 2 0 00-3.42 0z" />
            </svg>

            <div>

                <p class="text-[12px] font-semibold">
                    Terjadi kesalahan
                </p>

                <ul class="mt-1 list-disc pl-4 text-[11px] leading-5">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>

        </div>

    </div>
    @endif


    {{-- =========================================================
    MODULE HERO — MANAJEMEN PEGAWAI
========================================================= --}}
    <section
        class="relative isolate overflow-hidden rounded-[30px] bg-slate-950 text-white shadow-xl shadow-slate-200/50">
        {{-- Background Image --}}
        <div
            class="absolute inset-0 -z-20 bg-cover bg-center opacity-[0.20]"
            style="background-image: url('{{ asset('images/flower-mkp.jpg') }}');"></div>

        {{-- Main Gradient --}}
        <div
            class="absolute inset-0 -z-10 bg-gradient-to-br from-slate-950 via-slate-950/[0.96] to-blue-950/[0.92]"></div>

        {{-- Decorative Glow --}}
        <div
            class="absolute -right-24 -top-24 -z-10 h-72 w-72 rounded-full bg-blue-500/15 blur-3xl"></div>

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

        {{-- Content --}}
        <div class="relative px-6 py-8 sm:px-8 lg:px-10 lg:py-9">

            {{-- Header --}}
            <div class="max-w-3xl">

                {{-- Badge --}}
                <div
                    class="mb-4 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/[0.07] px-3 py-1.5 backdrop-blur-md">
                    <span class="h-1.5 w-1.5 rounded-full bg-blue-400"></span>

                    <span
                        class="text-[9px] font-medium uppercase tracking-[0.18em] text-slate-300">
                        Modul Manajemen Pegawai
                    </span>
                </div>

                {{-- Heading --}}
                <h1
                    class="max-w-2xl text-[28px] font-semibold leading-[1.08] tracking-[-0.035em] text-white sm:text-[32px] lg:text-[34px]">
                    Kelola Data Pegawai
                    <span class="text-blue-300">
                        dengan Lebih Terstruktur
                    </span>
                </h1>

                {{-- Description --}}
                <p
                    class="mt-3 max-w-2xl text-[12px] leading-6 text-slate-300 sm:text-[13px]">
                    Kelola informasi pegawai, jabatan, kategori pekerjaan,
                    serta struktur gaji dalam satu sistem yang terorganisir.
                </p>
            </div>


            {{-- Statistics --}}
            <div class="mt-7 grid grid-cols-2 gap-3 sm:grid-cols-4">

                {{-- Total Pegawai --}}
                <div
                    class="group rounded-2xl border border-white/10 bg-white/[0.065] p-4 backdrop-blur-md transition duration-300 hover:bg-white/[0.10]">
                    <div class="flex items-center justify-between">

                        <p
                            class="text-[9px] font-medium uppercase tracking-[0.12em] text-slate-400">
                            Total Pegawai
                        </p>

                        <div
                            class="flex h-7 w-7 items-center justify-center rounded-lg border border-white/10 bg-white/[0.06]">
                            <svg
                                class="h-3.5 w-3.5 text-blue-300"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.7"
                                viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M15 19a4 4 0 0 0-8 0m4-8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm7 8a4 4 0 0 0-3-3.87M17 5.13a3 3 0 0 1 0 5.74" />
                            </svg>
                        </div>
                    </div>

                    <p
                        class="mt-3 text-[25px] font-semibold tracking-[-0.04em] text-white">
                        {{ $totalPegawai ?? 0 }}
                    </p>

                    <p class="mt-1 text-[10px] text-slate-400">
                        Pegawai terdaftar
                    </p>
                </div>


                {{-- Pegawai Aktif --}}
                <div
                    class="group rounded-2xl border border-white/10 bg-white/[0.065] p-4 backdrop-blur-md transition duration-300 hover:bg-white/[0.10]">
                    <div class="flex items-center justify-between">

                        <p
                            class="text-[9px] font-medium uppercase tracking-[0.12em] text-slate-400">
                            Pegawai Aktif
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
                                    d="M5 12.5 9.5 17 19 7.5" />
                            </svg>
                        </div>
                    </div>

                    <p
                        class="mt-3 text-[25px] font-semibold tracking-[-0.04em] text-white">
                        {{ $pegawaiAktif ?? 0 }}
                    </p>

                    <p class="mt-1 text-[10px] text-slate-400">
                        Status aktif
                    </p>
                </div>


                {{-- Jabatan --}}
                <div
                    class="group rounded-2xl border border-white/10 bg-white/[0.065] p-4 backdrop-blur-md transition duration-300 hover:bg-white/[0.10]">
                    <div class="flex items-center justify-between">

                        <p
                            class="text-[9px] font-medium uppercase tracking-[0.12em] text-slate-400">
                            Jabatan
                        </p>

                        <div
                            class="flex h-7 w-7 items-center justify-center rounded-lg border border-white/10 bg-white/[0.06]">
                            <svg
                                class="h-3.5 w-3.5 text-indigo-300"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.7"
                                viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 3v18m9-9H3" />
                            </svg>
                        </div>
                    </div>

                    <p
                        class="mt-3 text-[25px] font-semibold tracking-[-0.04em] text-white">
                        {{ $totalJabatan ?? 0 }}
                    </p>

                    <p class="mt-1 text-[10px] text-slate-400">
                        Kategori jabatan
                    </p>
                </div>


                {{-- Struktur Gaji --}}
                <div
                    class="group rounded-2xl border border-white/10 bg-white/[0.065] p-4 backdrop-blur-md transition duration-300 hover:bg-white/[0.10]">
                    <div class="flex items-center justify-between">

                        <p
                            class="text-[9px] font-medium uppercase tracking-[0.12em] text-slate-400">
                            Struktur Gaji
                        </p>

                        <div
                            class="flex h-7 w-7 items-center justify-center rounded-lg border border-white/10 bg-white/[0.06]">
                            <svg
                                class="h-3.5 w-3.5 text-cyan-300"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.7"
                                viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 8c-2.21 0-4 .9-4 2s1.79 2 4 2 4 .9 4 2-1.79 2-4 2m0-8V6m0 12v-2m8-4a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z" />
                            </svg>
                        </div>
                    </div>

                    <p
                        class="mt-3 text-[25px] font-semibold tracking-[-0.04em] text-white">
                        {{ $totalKategoriGaji ?? 0 }}
                    </p>

                    <p class="mt-1 text-[10px] text-slate-400">
                        Kategori gaji
                    </p>
                </div>

            </div>

        </div>
    </section>


    {{-- =========================================================
        MAIN CONTENT
    ========================================================== --}}
    <section
        class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        {{-- =====================================================
            TAB NAVIGATION
        ====================================================== --}}
        <div class="border-b border-slate-200 px-4 sm:px-6">

            <div class="flex gap-6 overflow-x-auto">

                {{-- Pegawai --}}
                <button
                    type="button"
                    id="tabPegawai"
                    onclick="switchTab('pegawai')"
                    class="tab-button whitespace-nowrap border-b-2 border-slate-900 px-1 py-3.5 text-[12px] font-semibold text-slate-900">

                    Daftar Pegawai

                    <span
                        class="ml-1.5 rounded-full bg-slate-100 px-1.5 py-0.5 text-[9px] font-medium text-slate-600">

                        {{ $pegawais->count() }}

                    </span>

                </button>


                {{-- Jabatan --}}
                <button
                    type="button"
                    id="tabJabatan"
                    onclick="switchTab('jabatan')"
                    class="tab-button whitespace-nowrap border-b-2 border-transparent px-1 py-3.5 text-[12px] font-medium text-slate-500 transition hover:text-slate-800">

                    Kategori Jabatan & Gaji

                    <span
                        class="ml-1.5 rounded-full bg-slate-100 px-1.5 py-0.5 text-[9px] font-medium text-slate-600">

                        {{ $jabatans->count() }}

                    </span>

                </button>

            </div>

        </div>


        {{-- =====================================================
            TAB PEGAWAI
        ====================================================== --}}
        <div
            id="contentPegawai"
            class="p-4 sm:p-6">

            {{-- Header --}}
            <div
                class="mb-5 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                <div>

                    <h2
                        class="text-[14px] font-semibold tracking-[-0.01em] text-slate-900">

                        Daftar Pegawai

                    </h2>

                    <p class="mt-1 text-[11px] leading-5 text-slate-500">

                        Kelola informasi pegawai, status kepegawaian,
                        dan akses akun sistem.

                    </p>

                </div>


                @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'super_admin']))

                @if($jabatans->count() > 0)

                <button
                    type="button"
                    onclick="toggleModal('modalTambahPegawai', true)"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-slate-900 px-3.5 py-2 text-[11px] font-semibold text-white transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2">

                    <svg
                        class="h-3.5 w-3.5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 4v16m8-8H4" />

                    </svg>

                    Tambah Pegawai Baru

                </button>

                @else

                <button
                    type="button"
                    disabled
                    class="inline-flex cursor-not-allowed items-center justify-center gap-2 rounded-lg bg-slate-200 px-3.5 py-2 text-[11px] font-semibold text-slate-500">

                    Tambahkan Jabatan Terlebih Dahulu

                </button>

                @endif

                @endif

            </div>


            {{-- =================================================
                SEARCH & FILTER
            ================================================== --}}
            <div
                class="mb-5 grid grid-cols-1 gap-2.5 md:grid-cols-[1fr_180px_180px]">

                {{-- Search --}}
                <div class="relative">

                    <div
                        class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">

                        <svg
                            class="h-4 w-4 text-slate-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="m21 21-4.35-4.35m2.1-5.4a7.5 7.5 0 11-15 0 7.5 7.5 0 0115 0z" />

                        </svg>

                    </div>

                    <input
                        type="text"
                        id="searchPegawai"
                        onkeyup="filterPegawaiTable()"
                        placeholder="Cari nama, nomor telepon, atau jabatan..."
                        class="w-full rounded-lg border border-slate-300 bg-white py-2.5 pl-10 pr-3.5 text-[11px] text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-slate-500 focus:ring-2 focus:ring-slate-200">

                </div>


                {{-- Status --}}
                <select
                    id="filterStatus"
                    onchange="filterPegawaiTable()"
                    class="rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-[11px] text-slate-700 outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200">

                    <option value="">Semua Status</option>
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>

                </select>


                {{-- Jabatan --}}
                <select
                    id="filterJabatan"
                    onchange="filterPegawaiTable()"
                    class="rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-[11px] text-slate-700 outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200">

                    <option value="">Semua Jabatan</option>

                    @foreach($jabatans as $jabatan)

                    <option value="{{ strtolower($jabatan->nama_jabatan) }}">
                        {{ $jabatan->nama_jabatan }}
                    </option>

                    @endforeach

                </select>

            </div>


            {{-- =================================================
                EMPLOYEE TABLE
            ================================================== --}}
            <div class="overflow-hidden rounded-xl border border-slate-200">

                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-slate-200">

                        <thead class="bg-slate-50">

                            <tr>

                                <th
                                    class="px-4 py-3 text-left text-[9px] font-semibold uppercase tracking-[0.08em] text-slate-500">
                                    Nama Lengkap
                                </th>

                                <th
                                    class="px-4 py-3 text-left text-[9px] font-semibold uppercase tracking-[0.08em] text-slate-500">
                                    Jabatan
                                </th>

                                <th
                                    class="px-4 py-3 text-left text-[9px] font-semibold uppercase tracking-[0.08em] text-slate-500">
                                    Status
                                </th>

                                <th
                                    class="px-4 py-3 text-left text-[9px] font-semibold uppercase tracking-[0.08em] text-slate-500">
                                    Nomor Telepon
                                </th>

                                <th
                                    class="px-4 py-3 text-left text-[9px] font-semibold uppercase tracking-[0.08em] text-slate-500">
                                    Akun
                                </th>

                                <th
                                    class="px-4 py-3 text-right text-[9px] font-semibold uppercase tracking-[0.08em] text-slate-500">
                                    Aksi
                                </th>

                            </tr>

                        </thead>


                        <tbody
                            id="pegawaiTableBody"
                            class="divide-y divide-slate-200 bg-white">

                            @forelse($pegawais as $pegawai)

                            @php
                            $status = $pegawai->status ?? 'aktif';
                            $isActive = $status === 'aktif';
                            @endphp

                            <tr
                                class="pegawai-row transition hover:bg-slate-50"
                                data-nama="{{ strtolower($pegawai->nama) }}"
                                data-telp="{{ strtolower($pegawai->no_telp ?? '') }}"
                                data-jabatan="{{ strtolower($pegawai->jabatan->nama_jabatan ?? '') }}"
                                data-status="{{ $status }}">

                                {{-- Nama --}}
                                <td class="whitespace-nowrap px-4 py-3.5">

                                    <div class="flex items-center gap-2.5">

                                        <div
                                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-[11px] font-semibold text-slate-600">

                                            {{ strtoupper(substr($pegawai->nama, 0, 1)) }}

                                        </div>

                                        <div>

                                            <p
                                                class="text-[12px] font-semibold text-slate-900">

                                                {{ $pegawai->nama }}

                                            </p>

                                            <p
                                                class="mt-0.5 text-[10px] text-slate-500">

                                                ID Pegawai #{{ $pegawai->id }}

                                            </p>

                                        </div>

                                    </div>

                                </td>


                                {{-- Jabatan --}}
                                <td class="whitespace-nowrap px-4 py-3.5">

                                    <span
                                        class="inline-flex items-center rounded-md border border-slate-200 bg-slate-100 px-2 py-1 text-[10px] font-medium text-slate-700">

                                        {{ $pegawai->jabatan->nama_jabatan ?? '-' }}

                                    </span>

                                </td>


                                {{-- Status --}}
                                <td class="whitespace-nowrap px-4 py-3.5">

                                    @if($isActive)

                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-md border border-emerald-200 bg-emerald-50 px-2 py-1 text-[10px] font-medium text-emerald-700">

                                        <span
                                            class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>

                                        Aktif

                                    </span>

                                    @else

                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-md border border-slate-200 bg-slate-100 px-2 py-1 text-[10px] font-medium text-slate-500">

                                        <span
                                            class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>

                                        Nonaktif

                                    </span>

                                    @endif

                                </td>


                                {{-- Telepon --}}
                                <td
                                    class="whitespace-nowrap px-4 py-3.5 text-[11px] text-slate-600">

                                    {{ $pegawai->no_telp ?: '-' }}

                                </td>


                                {{-- Akun --}}
                                <td class="whitespace-nowrap px-4 py-3.5">

                                    @if($pegawai->user_id)

                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-md border border-emerald-200 bg-emerald-50 px-2 py-1 text-[10px] font-medium text-emerald-700">

                                        <span
                                            class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>

                                        Memiliki Akun

                                    </span>

                                    @else

                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-md border border-slate-200 bg-slate-50 px-2 py-1 text-[10px] font-medium text-slate-500">

                                        <span
                                            class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>

                                        Tanpa Akun

                                    </span>

                                    @endif

                                </td>


                                {{-- Actions --}}
                                <td class="whitespace-nowrap px-4 py-3.5">

                                    <div class="flex items-center justify-end gap-0.5">

                                        {{-- Detail --}}
                                        <button
                                            type="button"
                                            title="Detail"
                                            data-id="{{ $pegawai->id }}"
                                            data-nama="{{ $pegawai->nama }}"
                                            data-telp="{{ $pegawai->no_telp }}"
                                            data-jabatan="{{ $pegawai->jabatan->nama_jabatan ?? '-' }}"
                                            data-status="{{ $status }}"
                                            data-email="{{ $pegawai->user?->email }}"
                                            data-has-account="{{ $pegawai->user_id ? '1' : '0' }}"
                                            onclick="openDetailPegawaiModal(this)"
                                            class="rounded-lg p-1.5 text-slate-500 transition hover:bg-slate-100 hover:text-slate-800">

                                            <svg
                                                class="h-3.5 w-3.5"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24">

                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M2.25 12s3.75-6 9.75-6 9.75 6 9.75 6-3.75 6-9.75 6-9.75-6-9.75-6z" />

                                                <circle
                                                    cx="12"
                                                    cy="12"
                                                    r="2.5"
                                                    stroke-width="2" />

                                            </svg>

                                        </button>


                                        @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'super_admin']))

                                        {{-- Edit --}}
                                        <button
                                            type="button"
                                            title="Edit"
                                            data-id="{{ $pegawai->id }}"
                                            data-nama="{{ $pegawai->nama }}"
                                            data-telp="{{ $pegawai->no_telp }}"
                                            data-status="{{ $status }}"
                                            data-jabatan-id="{{ $pegawai->jabatan_id }}"
                                            data-jabatan-name="{{ $pegawai->jabatan->nama_jabatan ?? '' }}"
                                            data-email="{{ $pegawai->user?->email }}"
                                            data-has-account="{{ $pegawai->user_id ? '1' : '0' }}"
                                            onclick="openEditPegawaiModal(this)"
                                            class="rounded-lg p-1.5 text-slate-500 transition hover:bg-slate-100 hover:text-slate-800">

                                            <svg
                                                class="h-3.5 w-3.5"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24">

                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M16.862 3.487a2.25 2.25 0 013.182 3.182L8.25 18.463 4.5 19.5l1.037-3.75L16.862 3.487z" />

                                            </svg>

                                        </button>


                                        {{-- Delete --}}
                                        <form
                                            action="{{ route('pegawai.destroy', $pegawai->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus pegawai ini?')">

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                title="Hapus"
                                                class="rounded-lg p-1.5 text-slate-400 transition hover:bg-rose-50 hover:text-rose-600">

                                                <svg
                                                    class="h-3.5 w-3.5"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24">

                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M6 7h12M10 11v6m4-6v6M9 7V4h6v3m-8 0 1 13h8l1-13" />

                                                </svg>

                                            </button>

                                        </form>

                                        @endif

                                    </div>

                                </td>

                            </tr>

                            @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="px-5 py-12 text-center">

                                    <div
                                        class="mx-auto flex max-w-sm flex-col items-center">

                                        <div
                                            class="flex h-11 w-11 items-center justify-center rounded-full bg-slate-100">

                                            <svg
                                                class="h-5 w-5 text-slate-400"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24">

                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4-5a4 4 0 100-8 4 4 0 000 8z" />

                                            </svg>

                                        </div>

                                        <h3
                                            class="mt-3 text-[12px] font-semibold text-slate-900">

                                            Belum ada data pegawai

                                        </h3>

                                        <p
                                            class="mt-1 text-[10px] leading-5 text-slate-500">

                                            Silakan tambahkan pegawai baru untuk mulai mengelola data.

                                        </p>

                                    </div>

                                </td>

                            </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>


                {{-- Search Empty State --}}
                <div
                    id="searchEmptyState"
                    class="hidden px-5 py-12 text-center">

                    <div
                        class="mx-auto flex max-w-sm flex-col items-center">

                        <div
                            class="flex h-11 w-11 items-center justify-center rounded-full bg-slate-100">

                            <svg
                                class="h-5 w-5 text-slate-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="m21 21-4.35-4.35m2.1-5.4a7.5 7.5 0 11-15 0 7.5 7.5 0 0115 0z" />

                            </svg>

                        </div>

                        <h3
                            class="mt-3 text-[12px] font-semibold text-slate-900">

                            Data tidak ditemukan

                        </h3>

                        <p
                            class="mt-1 text-[10px] leading-5 text-slate-500">

                            Tidak ada pegawai yang sesuai dengan pencarian atau filter Anda.

                        </p>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            TAB JABATAN
        ====================================================== --}}
        <div
            id="contentJabatan"
            class="hidden p-4 sm:p-6">

            <div
                class="mb-5 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <h2
                        class="text-[14px] font-semibold tracking-[-0.01em] text-slate-900">

                        Kategori Jabatan & Gaji

                    </h2>

                    <p
                        class="mt-1 text-[11px] leading-5 text-slate-500">

                        Atur jabatan, standar gaji harian, dan hak akses akun.

                    </p>

                </div>


                @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'super_admin']))

                <button
                    type="button"
                    onclick="toggleModal('modalTambahJabatan', true)"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-slate-900 px-3.5 py-2 text-[11px] font-semibold text-white transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2">

                    <svg
                        class="h-3.5 w-3.5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 4v16m8-8H4" />

                    </svg>

                    Tambah Jabatan

                </button>

                @endif

            </div>


            <div class="overflow-hidden rounded-xl border border-slate-200">

                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-slate-200">

                        <thead class="bg-slate-50">

                            <tr>

                                <th
                                    class="px-4 py-3 text-left text-[9px] font-semibold uppercase tracking-[0.08em] text-slate-500">

                                    Nama Jabatan

                                </th>

                                <th
                                    class="px-4 py-3 text-left text-[9px] font-semibold uppercase tracking-[0.08em] text-slate-500">

                                    Standar Gaji Harian

                                </th>

                                <th
                                    class="px-4 py-3 text-left text-[9px] font-semibold uppercase tracking-[0.08em] text-slate-500">

                                    Akses Sistem

                                </th>

                                <th
                                    class="px-4 py-3 text-right text-[9px] font-semibold uppercase tracking-[0.08em] text-slate-500">

                                    Aksi

                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-slate-200 bg-white">

                            @forelse($jabatans as $jabatan)

                            <tr class="transition hover:bg-slate-50">

                                {{-- Nama --}}
                                <td class="px-4 py-3.5">

                                    <div class="flex items-center gap-2.5">

                                        <div
                                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-600">

                                            <svg
                                                class="h-3.5 w-3.5"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24">

                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M20 21a8 8 0 00-16 0m12-13a4 4 0 11-8 0 4 4 0 018 0z" />

                                            </svg>

                                        </div>

                                        <div>

                                            <p
                                                class="text-[12px] font-semibold text-slate-900">

                                                {{ $jabatan->nama_jabatan }}

                                            </p>

                                            <p
                                                class="text-[10px] text-slate-500">

                                                ID Jabatan #{{ $jabatan->id }}

                                            </p>

                                        </div>

                                    </div>

                                </td>


                                {{-- Gaji --}}
                                <td class="px-4 py-3.5">

                                    <span
                                        class="text-[12px] font-semibold text-slate-800">

                                        Rp {{ number_format($jabatan->gaji_harian, 0, ',', '.') }}

                                    </span>

                                    <span
                                        class="ml-1 text-[10px] text-slate-500">

                                        / hari

                                    </span>

                                </td>


                                {{-- Akses --}}
                                <td class="px-4 py-3.5">

                                    @if($jabatan->can_login ?? false)

                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-md border border-slate-200 bg-slate-100 px-2 py-1 text-[10px] font-medium text-slate-700">

                                        Dapat Login

                                    </span>

                                    @else

                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-md border border-slate-200 bg-slate-50 px-2 py-1 text-[10px] font-medium text-slate-500">

                                        Tanpa Login

                                    </span>

                                    @endif

                                </td>


                                {{-- Actions --}}
                                <td class="px-4 py-3.5">

                                    <div class="flex items-center justify-end gap-0.5">

                                        @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'super_admin']))

                                        {{-- Edit --}}
                                        <button
                                            type="button"
                                            title="Edit"
                                            data-id="{{ $jabatan->id }}"
                                            data-name="{{ $jabatan->nama_jabatan }}"
                                            data-salary="{{ $jabatan->gaji_harian }}"
                                            data-can-login="{{ ($jabatan->can_login ?? false) ? '1' : '0' }}"
                                            onclick="openEditJabatanModal(this)"
                                            class="rounded-lg p-1.5 text-slate-500 transition hover:bg-slate-100 hover:text-slate-800">

                                            <svg
                                                class="h-3.5 w-3.5"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24">

                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M16.862 3.487a2.25 2.25 0 013.182 3.182L8.25 18.463 4.5 19.5l1.037-3.75L16.862 3.487z" />

                                            </svg>

                                        </button>


                                        {{-- Delete --}}
                                        <form
                                            action="{{ route('jabatan.destroy', $jabatan->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus jabatan ini?')">

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                title="Hapus"
                                                class="rounded-lg p-1.5 text-slate-400 transition hover:bg-rose-50 hover:text-rose-600">

                                                <svg
                                                    class="h-3.5 w-3.5"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24">

                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M6 7h12M10 11v6m4-6v6M9 7V4h6v3m-8 0 1 13h8l-1-13" />

                                                </svg>

                                            </button>

                                        </form>

                                        @endif

                                    </div>

                                </td>

                            </tr>

                            @empty

                            <tr>

                                <td
                                    colspan="4"
                                    class="px-5 py-12 text-center">

                                    <div
                                        class="mx-auto flex max-w-sm flex-col items-center">

                                        <div
                                            class="flex h-11 w-11 items-center justify-center rounded-full bg-slate-100">

                                            <svg
                                                class="h-5 w-5 text-slate-400"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24">

                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l5 5v11a2 2 0 01-2 2z" />

                                            </svg>

                                        </div>

                                        <h3
                                            class="mt-3 text-[12px] font-semibold text-slate-900">

                                            Belum ada jabatan

                                        </h3>

                                        <p
                                            class="mt-1 text-[10px] leading-5 text-slate-500">

                                            Tambahkan kategori jabatan terlebih dahulu.

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

    </section>

</div>

{{-- =============================================================
    MODAL TAMBAH JABATAN
============================================================= --}}
<div
    id="modalTambahJabatan"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 p-4">

    <div class="w-full max-w-md rounded-2xl bg-white shadow-2xl">

        <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">

            <div>

                <h3 class="text-lg font-semibold text-slate-900">
                    Tambah Kategori Jabatan
                </h3>

                <p class="mt-0.5 text-sm text-slate-500">
                    Masukkan informasi jabatan baru.
                </p>

            </div>

            <button
                type="button"
                onclick="toggleModal('modalTambahJabatan', false)"
                class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-700">
                ✕
            </button>

        </div>


        <form
            action="{{ route('jabatan.store') }}"
            method="POST">

            @csrf

            <div class="space-y-5 px-6 py-6">

                {{-- Nama --}}
                <div>

                    <label
                        for="nama_jabatan"
                        class="mb-1.5 block text-sm font-medium text-slate-700">
                        Nama Jabatan
                    </label>

                    <input
                        type="text"
                        id="nama_jabatan"
                        name="nama_jabatan"
                        value="{{ old('nama_jabatan') }}"
                        required
                        class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                        placeholder="Contoh: Mandor">

                </div>


                {{-- Gaji --}}
                <div>

                    <label
                        for="gaji_harian"
                        class="mb-1.5 block text-sm font-medium text-slate-700">
                        Standar Gaji Harian
                    </label>

                    <div class="relative">

                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-sm text-slate-500">
                            Rp
                        </span>

                        <input
                            type="number"
                            id="gaji_harian"
                            name="gaji_harian"
                            value="{{ old('gaji_harian') }}"
                            min="0"
                            required
                            class="w-full rounded-lg border border-slate-300 py-2.5 pl-10 pr-3.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                            placeholder="150000">

                    </div>

                </div>


                {{-- Can Login --}}
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">

                    <label class="flex cursor-pointer items-start gap-3">

                        <input
                            type="checkbox"
                            name="can_login"
                            value="1"
                            {{ old('can_login') ? 'checked' : '' }}
                            class="mt-0.5 h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-400">

                        <span>

                            <span class="block text-sm font-medium text-slate-800">
                                Jabatan dapat memiliki akun
                            </span>

                            <span class="mt-1 block text-xs leading-5 text-slate-500">
                                Aktifkan jika pegawai dengan jabatan ini membutuhkan akses login ke sistem.
                            </span>

                        </span>

                    </label>

                </div>

            </div>


            <div class="flex justify-end gap-3 border-t border-slate-200 px-6 py-4">

                <button
                    type="button"
                    onclick="toggleModal('modalTambahJabatan', false)"
                    class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">
                    Batal
                </button>

                <button
                    type="submit"
                    class="rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">
                    Simpan Jabatan
                </button>

            </div>

        </form>

    </div>

</div>


{{-- =============================================================
    MODAL EDIT JABATAN
============================================================= --}}
<div
    id="modalEditJabatan"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 p-4">

    <div class="w-full max-w-md rounded-2xl bg-white shadow-2xl">

        <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">

            <div>

                <h3 class="text-lg font-semibold text-slate-900">
                    Edit Jabatan
                </h3>

                <p class="mt-0.5 text-sm text-slate-500">
                    Perbarui informasi jabatan.
                </p>

            </div>

            <button
                type="button"
                onclick="toggleModal('modalEditJabatan', false)"
                class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-700">
                ✕
            </button>

        </div>


        <form
            id="formEditJabatan"
            method="POST">

            @csrf
            @method('PUT')

            <div class="space-y-5 px-6 py-6">

                <div>

                    <label
                        for="edit_nama_jabatan"
                        class="mb-1.5 block text-sm font-medium text-slate-700">
                        Nama Jabatan
                    </label>

                    <input
                        type="text"
                        id="edit_nama_jabatan"
                        name="nama_jabatan"
                        required
                        class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">

                </div>


                <div>

                    <label
                        for="edit_gaji_harian"
                        class="mb-1.5 block text-sm font-medium text-slate-700">
                        Standar Gaji Harian
                    </label>

                    <div class="relative">

                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-sm text-slate-500">
                            Rp
                        </span>

                        <input
                            type="number"
                            id="edit_gaji_harian"
                            name="gaji_harian"
                            min="0"
                            required
                            class="w-full rounded-lg border border-slate-300 py-2.5 pl-10 pr-3.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">

                    </div>

                </div>


                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">

                    <label class="flex cursor-pointer items-start gap-3">

                        <input
                            type="checkbox"
                            id="edit_can_login"
                            name="can_login"
                            value="1"
                            class="mt-0.5 h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-400">

                        <span>

                            <span class="block text-sm font-medium text-slate-800">
                                Jabatan dapat memiliki akun
                            </span>

                            <span class="mt-1 block text-xs leading-5 text-slate-500">
                                Tentukan apakah pegawai dengan jabatan ini dapat memiliki akses login.
                            </span>

                        </span>

                    </label>

                </div>

            </div>


            <div class="flex justify-end gap-3 border-t border-slate-200 px-6 py-4">

                <button
                    type="button"
                    onclick="toggleModal('modalEditJabatan', false)"
                    class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">
                    Batal
                </button>

                <button
                    type="submit"
                    class="rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

</div>


{{-- =============================================================
    MODAL TAMBAH PEGAWAI
============================================================= --}}
<div
    id="modalTambahPegawai"
    class="fixed inset-0 z-50 hidden items-center justify-center overflow-y-auto bg-slate-900/60 p-4">

    <div class="my-8 w-full max-w-lg rounded-2xl bg-white shadow-2xl">

        <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">

            <div>

                <h3 class="text-lg font-semibold text-slate-900">
                    Tambah Pegawai Baru
                </h3>

                <p class="mt-0.5 text-sm text-slate-500">
                    Masukkan informasi pegawai.
                </p>

            </div>

            <button
                type="button"
                onclick="toggleModal('modalTambahPegawai', false)"
                class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-700">
                ✕
            </button>

        </div>


        <form
            action="{{ route('pegawai.store') }}"
            method="POST">

            @csrf

            <div class="space-y-5 px-6 py-6">

                {{-- Nama --}}
                <div>

                    <label
                        for="pegawai_nama"
                        class="mb-1.5 block text-sm font-medium text-slate-700">
                        Nama Lengkap
                    </label>

                    <input
                        type="text"
                        id="pegawai_nama"
                        name="nama"
                        value="{{ old('nama') }}"
                        required
                        class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                        placeholder="Masukkan nama lengkap">

                </div>


                {{-- Telepon --}}
                <div>

                    <label
                        for="pegawai_telp"
                        class="mb-1.5 block text-sm font-medium text-slate-700">
                        Nomor Telepon
                    </label>

                    <input
                        type="text"
                        id="pegawai_telp"
                        name="no_telp"
                        value="{{ old('no_telp') }}"
                        class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                        placeholder="08xxxxxxxxxx">

                </div>


                {{-- Jabatan --}}
                <div>

                    <label
                        for="pegawai_jabatan"
                        class="mb-1.5 block text-sm font-medium text-slate-700">
                        Jabatan
                    </label>

                    <select
                        id="pegawai_jabatan"
                        name="jabatan_id"
                        onchange="checkJabatan(this)"
                        required
                        class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">

                        <option value="">
                            Pilih Jabatan
                        </option>

                        @foreach($jabatans as $jabatan)

                        <option
                            value="{{ $jabatan->id }}"
                            data-can-login="{{ ($jabatan->can_login ?? false) ? '1' : '0' }}"
                            {{ old('jabatan_id') == $jabatan->id ? 'selected' : '' }}>
                            {{ $jabatan->nama_jabatan }}
                        </option>

                        @endforeach

                    </select>

                </div>


                {{-- Status Pegawai --}}
                <div>

                    <label
                        for="pegawai_status"
                        class="mb-1.5 block text-sm font-medium text-slate-700">
                        Status Pegawai
                    </label>

                    <select
                        id="pegawai_status"
                        name="status"
                        required
                        class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">

                        <option
                            value="aktif"
                            {{ old('status', 'aktif') === 'aktif' ? 'selected' : '' }}>
                            Aktif
                        </option>

                        <option
                            value="nonaktif"
                            {{ old('status') === 'nonaktif' ? 'selected' : '' }}>
                            Nonaktif
                        </option>

                    </select>

                    <p class="mt-1.5 text-xs text-slate-500">
                        Status ini menunjukkan apakah pegawai masih aktif bekerja.
                    </p>

                </div>


                {{-- Account --}}
                <div
                    id="accountFields"
                    class="hidden rounded-xl border border-slate-200 bg-slate-50 p-4">

                    <div class="mb-4">

                        <p class="text-sm font-semibold text-slate-800">
                            Akses Akun Sistem
                        </p>

                        <p class="mt-1 text-xs leading-5 text-slate-500">
                            Jabatan ini dapat memiliki akun untuk login ke sistem.
                        </p>

                    </div>


                    <div class="space-y-4">

                        <div>

                            <label
                                for="pegawai_email"
                                class="mb-1.5 block text-sm font-medium text-slate-700">
                                Email
                            </label>

                            <input
                                type="email"
                                id="pegawai_email"
                                name="email"
                                value="{{ old('email') }}"
                                autocomplete="email"
                                class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                                placeholder="nama@email.com">

                        </div>


                        <div>

                            <label
                                for="pegawai_password"
                                class="mb-1.5 block text-sm font-medium text-slate-700">
                                Password
                            </label>

                            <input
                                type="password"
                                id="pegawai_password"
                                name="password"
                                autocomplete="new-password"
                                class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                                placeholder="Minimal 8 karakter">

                        </div>

                    </div>

                </div>

            </div>


            <div class="flex justify-end gap-3 border-t border-slate-200 px-6 py-4">

                <button
                    type="button"
                    onclick="toggleModal('modalTambahPegawai', false)"
                    class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">
                    Batal
                </button>

                <button
                    type="submit"
                    class="rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">
                    Simpan Pegawai
                </button>

            </div>

        </form>

    </div>

</div>


{{-- =============================================================
    MODAL DETAIL PEGAWAI
============================================================= --}}
<div
    id="modalDetailPegawai"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 p-4">

    <div class="w-full max-w-md rounded-2xl bg-white shadow-2xl">

        <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">

            <div>

                <h3 class="text-lg font-semibold text-slate-900">
                    Detail Pegawai
                </h3>

                <p class="mt-0.5 text-sm text-slate-500">
                    Informasi pegawai dan akun.
                </p>

            </div>

            <button
                type="button"
                onclick="toggleModal('modalDetailPegawai', false)"
                class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-700">
                ✕
            </button>

        </div>


        <div class="space-y-5 px-6 py-6">

            {{-- Profile --}}
            <div class="flex items-center gap-4">

                <div
                    id="detailAvatar"
                    class="flex h-14 w-14 items-center justify-center rounded-xl bg-slate-100 text-lg font-bold text-slate-600">
                    -
                </div>

                <div>

                    <h4
                        id="detailNama"
                        class="text-lg font-semibold text-slate-900">
                        -
                    </h4>

                    <p
                        id="detailJabatan"
                        class="mt-1 text-sm text-slate-500">
                        -
                    </p>

                </div>

            </div>


            {{-- Information --}}
            <div class="divide-y divide-slate-200 rounded-xl border border-slate-200">

                {{-- Status Pegawai --}}
                <div class="flex items-center justify-between px-4 py-3">

                    <span class="text-sm text-slate-500">
                        Status Pegawai
                    </span>

                    <span id="detailStatusPegawai">
                        -
                    </span>

                </div>


                {{-- Telepon --}}
                <div class="flex items-center justify-between px-4 py-3">

                    <span class="text-sm text-slate-500">
                        Nomor Telepon
                    </span>

                    <span
                        id="detailTelp"
                        class="text-sm font-medium text-slate-800">
                        -
                    </span>

                </div>


                {{-- Akun --}}
                <div class="flex items-center justify-between px-4 py-3">

                    <span class="text-sm text-slate-500">
                        Status Akun
                    </span>

                    <span id="detailStatusAkun">
                        -
                    </span>

                </div>


                {{-- Email --}}
                <div
                    id="detailEmailWrapper"
                    class="hidden items-center justify-between px-4 py-3">

                    <span class="text-sm text-slate-500">
                        Email
                    </span>

                    <span
                        id="detailEmail"
                        class="text-sm font-medium text-slate-800">
                        -
                    </span>

                </div>

            </div>

        </div>


        <div class="flex justify-end border-t border-slate-200 px-6 py-4">

            <button
                type="button"
                onclick="toggleModal('modalDetailPegawai', false)"
                class="rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">
                Tutup
            </button>

        </div>

    </div>

</div>


{{-- =============================================================
    MODAL EDIT PEGAWAI
============================================================= --}}
<div
    id="modalEditPegawai"
    class="fixed inset-0 z-50 hidden items-center justify-center overflow-y-auto bg-slate-900/60 p-4">

    <div class="my-8 w-full max-w-lg rounded-2xl bg-white shadow-2xl">

        <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">

            <div>

                <h3 class="text-lg font-semibold text-slate-900">
                    Edit Pegawai
                </h3>

                <p class="mt-0.5 text-sm text-slate-500">
                    Perbarui informasi pegawai.
                </p>

            </div>

            <button
                type="button"
                onclick="toggleModal('modalEditPegawai', false)"
                class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-700">
                ✕
            </button>

        </div>


        <form
            id="formEditPegawai"
            method="POST">

            @csrf
            @method('PUT')

            <div class="space-y-5 px-6 py-6">

                {{-- Nama --}}
                <div>

                    <label
                        for="edit_pegawai_nama"
                        class="mb-1.5 block text-sm font-medium text-slate-700">
                        Nama Lengkap
                    </label>

                    <input
                        type="text"
                        id="edit_pegawai_nama"
                        name="nama"
                        required
                        class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">

                </div>


                {{-- Telepon --}}
                <div>

                    <label
                        for="edit_pegawai_telp"
                        class="mb-1.5 block text-sm font-medium text-slate-700">
                        Nomor Telepon
                    </label>

                    <input
                        type="text"
                        id="edit_pegawai_telp"
                        name="no_telp"
                        class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">

                </div>


                {{-- Jabatan --}}
                <div>

                    <label
                        for="edit_pegawai_jabatan"
                        class="mb-1.5 block text-sm font-medium text-slate-700">
                        Jabatan
                    </label>

                    <select
                        id="edit_pegawai_jabatan"
                        name="jabatan_id"
                        onchange="checkEditJabatan(this)"
                        required
                        class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">

                        @foreach($jabatans as $jabatan)

                        <option
                            value="{{ $jabatan->id }}"
                            data-can-login="{{ ($jabatan->can_login ?? false) ? '1' : '0' }}">
                            {{ $jabatan->nama_jabatan }}
                        </option>

                        @endforeach

                    </select>

                </div>


                {{-- Status --}}
                <div>

                    <label
                        for="edit_pegawai_status"
                        class="mb-1.5 block text-sm font-medium text-slate-700">
                        Status Pegawai
                    </label>

                    <select
                        id="edit_pegawai_status"
                        name="status"
                        required
                        class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">

                        <option value="aktif">
                            Aktif
                        </option>

                        <option value="nonaktif">
                            Nonaktif
                        </option>

                    </select>

                    <p class="mt-1.5 text-xs text-slate-500">
                        Mengubah status menjadi nonaktif tidak menghapus riwayat pegawai.
                    </p>

                </div>


                {{-- Account --}}
                <div
                    id="editAccountFields"
                    class="hidden rounded-xl border border-slate-200 bg-slate-50 p-4">

                    <div class="mb-4">

                        <p class="text-sm font-semibold text-slate-800">
                            Akses Akun Sistem
                        </p>

                        <p class="mt-1 text-xs leading-5 text-slate-500">
                            Kosongkan password jika tidak ingin mengubah password saat ini.
                        </p>

                    </div>


                    <div class="space-y-4">

                        <div>

                            <label
                                for="edit_pegawai_email"
                                class="mb-1.5 block text-sm font-medium text-slate-700">
                                Email
                            </label>

                            <input
                                type="email"
                                id="edit_pegawai_email"
                                name="email"
                                autocomplete="email"
                                class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">

                        </div>


                        <div>

                            <label
                                for="edit_pegawai_password"
                                class="mb-1.5 block text-sm font-medium text-slate-700">
                                Password Baru
                            </label>

                            <input
                                type="password"
                                id="edit_pegawai_password"
                                name="password"
                                autocomplete="new-password"
                                class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                                placeholder="Kosongkan jika tidak diubah">

                        </div>

                    </div>

                </div>

            </div>


            <div class="flex justify-end gap-3 border-t border-slate-200 px-6 py-4">

                <button
                    type="button"
                    onclick="toggleModal('modalEditPegawai', false)"
                    class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">
                    Batal
                </button>

                <button
                    type="submit"
                    class="rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

</div>


{{-- =============================================================
    JAVASCRIPT
============================================================= --}}
<script>
    // Peta id jabatan => boolean can_login, dikirim langsung dari server.
    // Sumber kebenaran tunggal supaya tidak lagi bergantung pada
    // atribut data-* pada <option> yang bisa gagal ter-render / terbaca.
    const jabatanCanLogin = @json($jabatans -> pluck('can_login', 'id'));

    /*
    |--------------------------------------------------------------------------
    | TAB
    |--------------------------------------------------------------------------
    */

    function switchTab(tab) {

        const contentPegawai =
            document.getElementById('contentPegawai');

        const contentJabatan =
            document.getElementById('contentJabatan');

        const tabPegawai =
            document.getElementById('tabPegawai');

        const tabJabatan =
            document.getElementById('tabJabatan');


        if (tab === 'pegawai') {

            contentPegawai.classList.remove('hidden');
            contentJabatan.classList.add('hidden');

            tabPegawai.classList.add(
                'border-slate-900',
                'font-semibold',
                'text-slate-900'
            );

            tabPegawai.classList.remove(
                'border-transparent',
                'font-medium',
                'text-slate-500'
            );


            tabJabatan.classList.add(
                'border-transparent',
                'font-medium',
                'text-slate-500'
            );

            tabJabatan.classList.remove(
                'border-slate-900',
                'font-semibold',
                'text-slate-900'
            );

        } else {

            contentPegawai.classList.add('hidden');
            contentJabatan.classList.remove('hidden');

            tabJabatan.classList.add(
                'border-slate-900',
                'font-semibold',
                'text-slate-900'
            );

            tabJabatan.classList.remove(
                'border-transparent',
                'font-medium',
                'text-slate-500'
            );


            tabPegawai.classList.add(
                'border-transparent',
                'font-medium',
                'text-slate-500'
            );

            tabPegawai.classList.remove(
                'border-slate-900',
                'font-semibold',
                'text-slate-900'
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | FILTER PEGAWAI
    |--------------------------------------------------------------------------
    */

    function filterPegawaiTable() {

        const searchInput =
            document.getElementById('searchPegawai')
            ?.value
            .toLowerCase()
            .trim() || '';


        const filterStatus =
            document.getElementById('filterStatus')
            ?.value
            .toLowerCase()
            .trim() || '';


        const filterJabatan =
            document.getElementById('filterJabatan')
            ?.value
            .toLowerCase()
            .trim() || '';


        const rows =
            document.querySelectorAll('.pegawai-row');


        const emptyState =
            document.getElementById('searchEmptyState');


        let visibleRows = 0;


        rows.forEach(row => {

            const nama =
                row.dataset.nama || '';

            const telp =
                row.dataset.telp || '';

            const jabatan =
                row.dataset.jabatan || '';

            const status =
                row.dataset.status || '';


            const matchesSearch =
                nama.includes(searchInput) ||
                telp.includes(searchInput) ||
                jabatan.includes(searchInput);


            const matchesStatus = !filterStatus ||
                status === filterStatus;


            const matchesJabatan = !filterJabatan ||
                jabatan === filterJabatan;


            const visible =
                matchesSearch &&
                matchesStatus &&
                matchesJabatan;


            row.style.display =
                visible ? '' : 'none';


            if (visible) {
                visibleRows++;
            }

        });


        if (emptyState) {

            if (rows.length > 0 && visibleRows === 0) {

                emptyState.classList.remove('hidden');

            } else {

                emptyState.classList.add('hidden');

            }
        }
    }


    /*
    |--------------------------------------------------------------------------
    | MODAL
    |--------------------------------------------------------------------------
    */

    function toggleModal(id, show) {

        const modal =
            document.getElementById(id);

        if (!modal) {
            return;
        }


        if (show) {

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            document.body.classList.add('overflow-hidden');

        } else {

            modal.classList.add('hidden');
            modal.classList.remove('flex');


            const openedModals =
                document.querySelectorAll(
                    '[id^="modal"].flex'
                );


            if (openedModals.length === 0) {

                document.body.classList.remove('overflow-hidden');

            }
        }
    }


    /*
    |--------------------------------------------------------------------------
    | CHECK JABATAN - TAMBAH PEGAWAI
    |--------------------------------------------------------------------------
    | Menggunakan peta jabatanCanLogin (dikirim dari server via @@json())
    | alih-alih membaca atribut data-can-login pada <option>, supaya
    | lebih andal dan tidak tergantung parsing atribut HTML.
    |--------------------------------------------------------------------------
    */

    function checkJabatan(select) {

        const canLogin =
            jabatanCanLogin[select.value] === true;


        const accountFields =
            document.getElementById('accountFields');


        if (!accountFields) {
            return;
        }


        if (canLogin) {

            accountFields.classList.remove('hidden');

        } else {

            accountFields.classList.add('hidden');

            document.getElementById('pegawai_email').value = '';
            document.getElementById('pegawai_password').value = '';

        }
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT JABATAN
    |--------------------------------------------------------------------------
    */

    function openEditJabatanModal(button) {

        const id =
            button.dataset.id;


        const name =
            button.dataset.name || '';


        const salary =
            button.dataset.salary || '';


        const canLogin =
            button.dataset.canLogin === '1';


        const form =
            document.getElementById('formEditJabatan');


        form.action =
            `/jabatan/${id}`;


        document.getElementById('edit_nama_jabatan').value =
            name;


        document.getElementById('edit_gaji_harian').value =
            salary;


        document.getElementById('edit_can_login').checked =
            canLogin;


        toggleModal(
            'modalEditJabatan',
            true
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DETAIL PEGAWAI
    |--------------------------------------------------------------------------
    */

    function openDetailPegawaiModal(button) {

        const nama =
            button.dataset.nama || '-';


        const telp =
            button.dataset.telp || '-';


        const jabatan =
            button.dataset.jabatan || '-';


        const status =
            button.dataset.status || 'aktif';


        const email =
            button.dataset.email || '';


        const hasAccount =
            button.dataset.hasAccount === '1';


        document.getElementById('detailNama').textContent =
            nama;


        document.getElementById('detailJabatan').textContent =
            jabatan;


        document.getElementById('detailTelp').textContent =
            telp || '-';


        document.getElementById('detailAvatar').textContent =
            nama.charAt(0).toUpperCase();


        /*
        |--------------------------------------------------------------------------
        | STATUS PEGAWAI
        |--------------------------------------------------------------------------
        */

        const statusPegawaiElement =
            document.getElementById('detailStatusPegawai');


        if (status === 'aktif') {

            statusPegawaiElement.innerHTML = `
                <span class="inline-flex items-center gap-1.5 rounded-md border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                    Aktif
                </span>
            `;

        } else {

            statusPegawaiElement.innerHTML = `
                <span class="inline-flex items-center gap-1.5 rounded-md border border-slate-200 bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-500">
                    <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                    Nonaktif
                </span>
            `;
        }


        /*
        |--------------------------------------------------------------------------
        | STATUS AKUN
        |--------------------------------------------------------------------------
        */

        const statusElement =
            document.getElementById('detailStatusAkun');


        if (hasAccount) {

            statusElement.innerHTML = `
                <span class="inline-flex items-center gap-1.5 rounded-md border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                    Memiliki Akun
                </span>
            `;

        } else {

            statusElement.innerHTML = `
                <span class="inline-flex items-center gap-1.5 rounded-md border border-slate-200 bg-slate-50 px-2.5 py-1 text-xs font-medium text-slate-500">
                    <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                    Tanpa Akun
                </span>
            `;
        }


        /*
        |--------------------------------------------------------------------------
        | EMAIL
        |--------------------------------------------------------------------------
        */

        const emailWrapper =
            document.getElementById('detailEmailWrapper');


        const emailElement =
            document.getElementById('detailEmail');


        if (hasAccount && email) {

            emailWrapper.classList.remove('hidden');
            emailWrapper.classList.add('flex');

            emailElement.textContent =
                email;

        } else {

            emailWrapper.classList.add('hidden');
            emailWrapper.classList.remove('flex');

            emailElement.textContent =
                '-';
        }


        toggleModal(
            'modalDetailPegawai',
            true
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT PEGAWAI
    |--------------------------------------------------------------------------
    */

    function openEditPegawaiModal(button) {

        const id =
            button.dataset.id;


        const nama =
            button.dataset.nama || '';


        const telp =
            button.dataset.telp || '';


        const jabatanId =
            button.dataset.jabatanId || '';


        const status =
            button.dataset.status || 'aktif';


        const email =
            button.dataset.email || '';


        const form =
            document.getElementById('formEditPegawai');


        form.action =
            `/pegawai/${id}`;


        document.getElementById('edit_pegawai_nama').value =
            nama;


        document.getElementById('edit_pegawai_telp').value =
            telp;


        document.getElementById('edit_pegawai_email').value =
            email;


        document.getElementById('edit_pegawai_password').value =
            '';


        document.getElementById('edit_pegawai_status').value =
            status;


        const jabatanSelect =
            document.getElementById('edit_pegawai_jabatan');


        jabatanSelect.value =
            jabatanId;


        checkEditJabatan(
            jabatanSelect
        );


        toggleModal(
            'modalEditPegawai',
            true
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CHECK JABATAN - EDIT PEGAWAI
    |--------------------------------------------------------------------------
    | Sama seperti checkJabatan(), memakai peta jabatanCanLogin dari
    | server alih-alih dataset dari opsi terpilih.
    |--------------------------------------------------------------------------
    */

    function checkEditJabatan(select) {

        const canLogin =
            jabatanCanLogin[select.value] === true;


        const accountFields =
            document.getElementById('editAccountFields');


        if (!accountFields) {
            return;
        }


        if (canLogin) {

            accountFields.classList.remove('hidden');

        } else {

            accountFields.classList.add('hidden');

            document.getElementById('edit_pegawai_email').value = '';
            document.getElementById('edit_pegawai_password').value = '';

        }
    }


    /*
    |--------------------------------------------------------------------------
    | ESCAPE UNTUK MENUTUP MODAL
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'keydown',
        function(event) {

            if (event.key !== 'Escape') {
                return;
            }


            document
                .querySelectorAll('[id^="modal"].flex')
                .forEach(modal => {

                    modal.classList.add('hidden');
                    modal.classList.remove('flex');

                });


            document.body.classList.remove(
                'overflow-hidden'
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | AUTO CLOSE SUCCESS ALERT
    |--------------------------------------------------------------------------
    */

    setTimeout(() => {

        const alert =
            document.getElementById('successAlert');


        if (alert) {
            alert.remove();
        }

    }, 5000);


    /*
    |--------------------------------------------------------------------------
    | AUTO CHECK OLD JABATAN
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'DOMContentLoaded',
        function() {

            const addJabatan =
                document.getElementById('pegawai_jabatan');


            if (addJabatan && addJabatan.value) {

                checkJabatan(addJabatan);

            }

        }
    );
</script>

@endsection