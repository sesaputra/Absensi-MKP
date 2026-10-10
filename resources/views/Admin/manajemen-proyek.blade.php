@extends('layouts.admin')

@section('title', 'Manajemen Proyek')

@section('content')

@php
$totalProyek = $proyeks->count();

$proyekBerjalan = $proyeks->where('status', 'Berjalan')->count();

$proyekAkanDimulai = $proyeks->where('status', 'Akan Dimulai')->count();

$proyekSelesai = $proyeks->where('status', 'Selesai')->count();

$isAdmin = auth()->check()
&& in_array(auth()->user()->role, ['admin', 'super_admin']);
@endphp


<div class="space-y-6">


    {{-- =========================================================
        PAGE HEADER / BANNER
    ========================================================== --}}
    <section
        class="relative isolate overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <!-- {{-- Background Image --}}
        <div
            class="absolute inset-0 -z-20 bg-cover bg-center opacity-[0.14]"
            style="background-image: url('{{ asset('images/flower-mkp.jpg') }}');">
        </div> -->

        {{-- Soft Overlay --}}
        <div class="absolute inset-0 -z-10 bg-white/90"></div>

        {{-- Subtle Grid --}}
        <div
            class="absolute inset-0 -z-10 opacity-[0.015]"
            style="
                background-image:
                    linear-gradient(rgba(15,23,42,.8) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(15,23,42,.8) 1px, transparent 1px);
                background-size: 32px 32px;
            ">
        </div>


        <div class="relative px-6 py-7 sm:px-8 sm:py-8">


            {{-- Header --}}
            <div
                class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">


                {{-- Heading --}}
                <div class="min-w-0">

                    {{-- Eyebrow --}}
                    <div class="mb-3 flex items-center gap-2.5">

                        <span class="h-2 w-2 rounded-full bg-amber-500"></span>

                        <span
                            class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-400">
                            Portofolio & Monitoring Konstruksi
                        </span>

                    </div>


                    {{-- Heading --}}
                    <h1
                        class="text-2xl font-semibold tracking-tight text-slate-900 sm:text-3xl">
                        Manajemen Proyek
                    </h1>


                    {{-- Description --}}
                    <p
                        class="mt-2 max-w-2xl text-sm leading-6 text-slate-500 sm:text-[15px]">
                        Kelola proyek, tim lapangan, informasi klien,
                        serta anggaran dalam satu ruang kerja operasional.
                    </p>

                </div>


                {{-- Action --}}
                @if($isAdmin)

                <button
                    type="button"
                    onclick="toggleModal('modalTambahProyek')"
                    class="inline-flex shrink-0 items-center justify-center gap-2.5 rounded-xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-slate-800 hover:shadow-md active:translate-y-0">

                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 4v16m8-8H4" />

                    </svg>

                    Tambah Proyek

                </button>

                @endif

            </div>


            {{-- Project Summary --}}
            <div class="mt-7 grid grid-cols-2 gap-3 sm:grid-cols-4">


                {{-- Total --}}
                <div
                    class="rounded-xl border border-slate-200/80 bg-white/85 px-4 py-4 backdrop-blur-sm">

                    <div class="flex items-center justify-between gap-2">

                        <p
                            class="text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">
                            Total
                        </p>

                        <span class="h-2 w-2 rounded-full bg-blue-500"></span>

                    </div>

                    <p
                        class="mt-2 text-xl font-semibold tracking-tight text-slate-900 sm:text-2xl">
                        {{ number_format($totalProyek) }}
                    </p>

                    <p class="mt-1 text-xs text-slate-500 sm:text-sm">
                        Proyek terdaftar
                    </p>

                </div>


                {{-- Berjalan --}}
                <div
                    class="rounded-xl border border-slate-200/80 bg-white/85 px-4 py-4 backdrop-blur-sm">

                    <div class="flex items-center justify-between gap-2">

                        <p
                            class="text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">
                            Berjalan
                        </p>

                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>

                    </div>

                    <p
                        class="mt-2 text-xl font-semibold tracking-tight text-slate-900 sm:text-2xl">
                        {{ number_format($proyekBerjalan) }}
                    </p>

                    <p class="mt-1 text-xs text-slate-500 sm:text-sm">
                        Sedang dikerjakan
                    </p>

                </div>


                {{-- Akan Dimulai --}}
                <div
                    class="rounded-xl border border-slate-200/80 bg-white/85 px-4 py-4 backdrop-blur-sm">

                    <div class="flex items-center justify-between gap-2">

                        <p
                            class="text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">
                            Akan Dimulai
                        </p>

                        <span class="h-2 w-2 rounded-full bg-amber-500"></span>

                    </div>

                    <p
                        class="mt-2 text-xl font-semibold tracking-tight text-slate-900 sm:text-2xl">
                        {{ number_format($proyekAkanDimulai) }}
                    </p>

                    <p class="mt-1 text-xs text-slate-500 sm:text-sm">
                        Menunggu pelaksanaan
                    </p>

                </div>


                {{-- Selesai --}}
                <div
                    class="rounded-xl border border-slate-200/80 bg-white/85 px-4 py-4 backdrop-blur-sm">

                    <div class="flex items-center justify-between gap-2">

                        <p
                            class="text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">
                            Selesai
                        </p>

                        <span class="h-2 w-2 rounded-full bg-violet-500"></span>

                    </div>

                    <p
                        class="mt-2 text-xl font-semibold tracking-tight text-slate-900 sm:text-2xl">
                        {{ number_format($proyekSelesai) }}
                    </p>

                    <p class="mt-1 text-xs text-slate-500 sm:text-sm">
                        Proyek selesai
                    </p>

                </div>

            </div>

        </div>

    </section>



    {{-- =========================================================
        PROJECT DIRECTORY
    ========================================================== --}}
    <section
        class="rounded-2xl border border-slate-200 bg-white shadow-sm">


        {{-- Section Header --}}
        <div
            class="flex flex-col gap-3 border-b border-slate-100 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">

            <div>

                <div class="flex items-center gap-2.5">

                    <span class="h-2 w-2 rounded-full bg-blue-500"></span>

                    <h2
                        class="text-base font-semibold tracking-tight text-slate-800 sm:text-lg">
                        Daftar Proyek
                    </h2>

                </div>


                <p
                    class="mt-1.5 text-sm leading-6 text-slate-500">
                    Pilih proyek untuk melihat detail, tim, absensi,
                    laporan, dan pengelolaan keuangan.
                </p>

            </div>


            <div
                class="text-sm font-medium text-slate-400">
                {{ number_format($totalProyek) }} proyek
            </div>

        </div>



        {{-- Project Grid --}}
        <div class="p-6">

            @if($proyeks->count())

            <div
                class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">


                @foreach($proyeks as $proyek)

                @php

                $status = $proyek->status;

                $statusConfig = match ($status) {

                'Berjalan' => [
                'badge' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
                'dot' => 'bg-emerald-500',
                ],

                'Akan Dimulai' => [
                'badge' => 'border-blue-200 bg-blue-50 text-blue-700',
                'dot' => 'bg-blue-500',
                ],

                'Ditunda' => [
                'badge' => 'border-amber-200 bg-amber-50 text-amber-700',
                'dot' => 'bg-amber-500',
                ],

                'Selesai' => [
                'badge' => 'border-slate-200 bg-slate-100 text-slate-600',
                'dot' => 'bg-slate-400',
                ],

                default => [
                'badge' => 'border-slate-200 bg-slate-100 text-slate-600',
                'dot' => 'bg-slate-400',
                ],

                };

                @endphp



                {{-- Project Card --}}
                <a
                    href="{{ route('proyek.show', ['proyek' => $proyek->id]) }}"
                    class="group flex min-h-full flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white transition duration-300 hover:-translate-y-0.5 hover:border-slate-300 hover:shadow-lg">


                    {{-- Image --}}
                    <div
                        class="relative h-44 overflow-hidden bg-slate-100 sm:h-48">

                        @if($proyek->gambar)

                        <img
                            src="{{ asset('storage/' . $proyek->gambar) }}"
                            alt="{{ $proyek->nama_proyek }}"
                            class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]">

                        <div
                            class="absolute inset-0 bg-gradient-to-t from-slate-900/50 via-transparent to-transparent">
                        </div>

                        @else

                        <div
                            class="flex h-full flex-col items-center justify-center bg-slate-50 text-slate-400">

                            <div
                                class="mb-2.5 flex h-11 w-11 items-center justify-center rounded-xl border border-slate-200 bg-white">

                                <svg
                                    class="h-5.5 w-5.5 text-slate-300"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />

                                </svg>

                            </div>

                            <span class="text-xs font-medium">
                                Foto belum tersedia
                            </span>

                        </div>

                        @endif



                        {{-- Status --}}
                        <div class="absolute left-4 top-4">

                            <span
                                class="inline-flex items-center gap-2 rounded-full border px-3 py-1.5 text-xs font-semibold backdrop-blur-md {{ $statusConfig['badge'] }}">

                                <span
                                    class="h-1.5 w-1.5 rounded-full {{ $statusConfig['dot'] }}">
                                </span>

                                {{ $status }}

                            </span>

                        </div>



                        {{-- Start Date --}}
                        <div class="absolute bottom-4 right-4">

                            <span
                                class="rounded-lg bg-slate-900/70 px-3 py-1.5 text-xs font-medium text-white backdrop-blur-md">

                                {{ \Carbon\Carbon::parse($proyek->tanggal_mulai)->format('d M Y') }}

                            </span>

                        </div>

                    </div>



                    {{-- Content --}}
                    <div
                        class="flex flex-1 flex-col p-5">


                        {{-- Project Name --}}
                        <div>

                            <h3
                                class="line-clamp-2 text-base font-semibold leading-6 tracking-tight text-slate-800 transition-colors group-hover:text-blue-600">

                                {{ $proyek->nama_proyek }}

                            </h3>


                            {{-- Location --}}
                            <div
                                class="mt-2 flex items-start gap-2 text-sm leading-5 text-slate-500">

                                <svg
                                    class="mt-0.5 h-4 w-4 shrink-0 text-slate-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.8"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.8"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />

                                </svg>

                                <span class="line-clamp-2">
                                    {{ $proyek->lokasi }}
                                </span>

                            </div>

                        </div>



                        {{-- Client --}}
                        @if($proyek->nama_pemilik)

                        <div
                            class="mt-5 rounded-xl border border-slate-100 bg-slate-50/70 px-4 py-3.5">

                            <div
                                class="flex items-center justify-between gap-3">

                                <span
                                    class="text-xs font-semibold uppercase tracking-[0.08em] text-slate-400">
                                    Klien
                                </span>

                                @if($proyek->kontak_pemilik)

                                <span
                                    class="truncate text-xs font-medium text-slate-500">

                                    {{ $proyek->kontak_pemilik }}

                                </span>

                                @endif

                            </div>

                            <p
                                class="mt-1.5 truncate text-sm font-semibold text-slate-700">

                                {{ $proyek->nama_pemilik }}

                            </p>

                        </div>

                        @endif



                        {{-- Footer --}}
                        <div
                            class="mt-auto border-t border-slate-100 pt-5">

                            <div
                                class="flex items-end justify-between gap-4">


                                <div>

                                    <span
                                        class="block text-xs font-semibold uppercase tracking-[0.08em] text-slate-400">
                                        Nilai Proyek
                                    </span>

                                    <span
                                        class="mt-1 block text-base font-semibold tracking-tight text-slate-800">

                                        Rp
                                        {{ number_format($proyek->anggaran, 0, ',', '.') }}

                                    </span>

                                </div>



                                <span
                                    class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-400 transition-colors group-hover:text-blue-600">

                                    Detail

                                    <svg
                                        class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-0.5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24">

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9 5l7 7-7 7" />

                                    </svg>

                                </span>

                            </div>

                        </div>

                    </div>

                </a>

                @endforeach

            </div>


            @else

            {{-- Empty State --}}
            <div
                class="flex flex-col items-center justify-center rounded-2xl border border-dashed border-slate-200 bg-slate-50/50 px-6 py-16 text-center">


                <div
                    class="flex h-16 w-16 items-center justify-center rounded-2xl border border-slate-200 bg-white shadow-sm">

                    <svg
                        class="h-7 w-7 text-slate-300"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.5"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />

                    </svg>

                </div>


                <h3
                    class="mt-5 text-base font-semibold text-slate-800">
                    Belum Ada Proyek
                </h3>


                <p
                    class="mt-2 max-w-sm text-sm leading-6 text-slate-500">

                    Tambahkan proyek pertama untuk mulai mengelola
                    tim, pekerjaan, absensi, dan keuangan.

                </p>


                @if($isAdmin)

                <button
                    type="button"
                    onclick="toggleModal('modalTambahProyek')"
                    class="mt-6 inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">

                    <svg
                        class="h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 4v16m8-8H4" />

                    </svg>

                    Tambah Proyek

                </button>

                @endif

            </div>
            @endif
        </div>
    </section>
</div>



{{-- =========================================================
    MODAL TAMBAH PROYEK
========================================================= --}}

@if($isAdmin)

<div
    id="modalTambahProyek"
    class="fixed inset-0 z-[999] hidden items-center justify-center p-4 sm:p-6"
    aria-hidden="true">


    {{-- FULL SCREEN OVERLAY --}}
    <div
        class="absolute inset-0 bg-slate-950/50 backdrop-blur-sm"
        onclick="toggleModal('modalTambahProyek')">
    </div>


    {{-- MODAL PANEL --}}
    <div
        class="relative z-10 flex max-h-[92vh] w-full max-w-3xl flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl"
        role="dialog"
        aria-modal="true"
        aria-labelledby="modalTambahProyekTitle">


        {{-- Modal Header --}}
        <div
            class="flex shrink-0 items-start justify-between gap-5 border-b border-slate-100 bg-slate-50/70 px-6 py-5 sm:px-7">

            <div>

                <div class="flex items-center gap-2.5">

                    <span class="h-2 w-2 rounded-full bg-amber-500"></span>

                    <h3
                        id="modalTambahProyekTitle"
                        class="text-lg font-semibold tracking-tight text-slate-800">

                        Tambah Proyek Baru

                    </h3>

                </div>

                <p
                    class="mt-1.5 text-sm leading-5 text-slate-500">

                    Lengkapi informasi dasar proyek sebelum mulai dikelola.

                </p>

            </div>


            <button
                type="button"
                onclick="toggleModal('modalTambahProyek')"
                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-200/70 hover:text-slate-600">

                <svg
                    class="h-5 w-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
                        d="M6 18L18 6M6 6l12 12" />

                </svg>

            </button>

        </div>



        {{-- FORM --}}
        <form
            action="{{ route('proyek.store') }}"
            method="POST"
            enctype="multipart/form-data"
            class="min-h-0 flex-1 overflow-y-auto">

            @csrf


            <div class="space-y-6 p-6 sm:p-7">


                {{-- Errors --}}
                @if($errors->any())

                <div
                    class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3.5">

                    <p
                        class="mb-2 text-sm font-semibold text-rose-700">
                        Periksa kembali data berikut:
                    </p>

                    <div class="space-y-1.5">

                        @foreach($errors->all() as $error)

                        <p class="text-sm leading-5 text-rose-600">
                            • {{ $error }}
                        </p>

                        @endforeach

                    </div>

                </div>

                @endif



                {{-- Upload --}}
                <div>

                    <label
                        class="mb-2 block text-sm font-semibold text-slate-700">

                        Foto Proyek

                        <span class="font-normal text-slate-400">
                            (Opsional)
                        </span>

                    </label>


                    <div
                        onclick="document.getElementById('file-upload').click()"
                        class="group cursor-pointer rounded-xl border-2 border-dashed border-slate-200 bg-slate-50/60 p-5 text-center transition hover:border-amber-300 hover:bg-amber-50/30">


                        <input
                            id="file-upload"
                            name="gambar"
                            type="file"
                            class="hidden"
                            accept="image/png,image/jpeg,image/jpg"
                            onchange="previewImage(this)">


                        <div
                            id="preview-container"
                            class="mb-3 hidden">

                            <img
                                id="img-preview"
                                src=""
                                alt="Preview"
                                class="mx-auto h-40 w-full rounded-xl border border-slate-200 object-cover">

                        </div>


                        <div id="placeholder-container">

                            <div
                                class="mx-auto mb-3 flex h-11 w-11 items-center justify-center rounded-xl bg-white">

                                <svg
                                    class="h-6 w-6 text-slate-300 transition group-hover:text-amber-500"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />

                                </svg>

                            </div>


                            <p
                                class="text-sm font-semibold text-slate-700">

                                Klik untuk memilih foto proyek

                            </p>


                            <p
                                id="file-name"
                                class="mt-1 text-xs text-slate-400">

                                PNG atau JPG, maksimal 2MB

                            </p>

                        </div>

                    </div>

                </div>



                {{-- Nama + Lokasi --}}
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">


                    <div>

                        <label
                            class="mb-2 block text-sm font-semibold text-slate-700">

                            Nama Proyek
                            <span class="text-rose-500">*</span>

                        </label>


                        <input
                            type="text"
                            name="nama_proyek"
                            value="{{ old('nama_proyek') }}"
                            placeholder="Contoh: Villa Canggu Resort"
                            required
                            class="w-full rounded-xl border border-slate-200 px-3.5 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-amber-400 focus:ring-4 focus:ring-amber-100">

                    </div>



                    <div>

                        <label
                            class="mb-2 block text-sm font-semibold text-slate-700">

                            Lokasi
                            <span class="text-rose-500">*</span>

                        </label>


                        <input
                            type="text"
                            name="lokasi"
                            value="{{ old('lokasi') }}"
                            placeholder="Contoh: Jl. Batu Bolong No. 8"
                            required
                            class="w-full rounded-xl border border-slate-200 px-3.5 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-amber-400 focus:ring-4 focus:ring-amber-100">

                    </div>

                </div>



                {{-- Client --}}
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">


                    <div>

                        <label
                            class="mb-2 block text-sm font-semibold text-slate-700">

                            Nama Pemilik / Klien

                        </label>


                        <input
                            type="text"
                            name="nama_pemilik"
                            value="{{ old('nama_pemilik') }}"
                            placeholder="Contoh: Bapak Made Kencana"
                            class="w-full rounded-xl border border-slate-200 px-3.5 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-amber-400 focus:ring-4 focus:ring-amber-100">

                    </div>



                    <div>

                        <label
                            class="mb-2 block text-sm font-semibold text-slate-700">

                            No. WhatsApp / Telp

                        </label>


                        <input
                            type="text"
                            name="kontak_pemilik"
                            value="{{ old('kontak_pemilik') }}"
                            placeholder="Contoh: 08123456789"
                            class="w-full rounded-xl border border-slate-200 px-3.5 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-amber-400 focus:ring-4 focus:ring-amber-100">

                    </div>

                </div>



                {{-- Budget --}}
                <div>

                    <label
                        class="mb-2 block text-sm font-semibold text-slate-700">

                        Anggaran / Nilai Proyek
                        <span class="text-rose-500">*</span>

                    </label>


                    <div class="relative">

                        <span
                            class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-sm font-semibold text-slate-400">
                            Rp
                        </span>


                        <input
                            type="number"
                            name="anggaran"
                            value="{{ old('anggaran') }}"
                            placeholder="150000000"
                            min="0"
                            required
                            class="w-full rounded-xl border border-slate-200 py-3 pl-10 pr-3.5 text-sm font-medium text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-amber-400 focus:ring-4 focus:ring-amber-100">

                    </div>

                </div>



                {{-- Date + Status --}}
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">


                    <div>

                        <label
                            class="mb-2 block text-sm font-semibold text-slate-700">

                            Tanggal Mulai
                            <span class="text-rose-500">*</span>

                        </label>


                        <input
                            type="date"
                            name="tanggal_mulai"
                            value="{{ old('tanggal_mulai') }}"
                            required
                            class="w-full rounded-xl border border-slate-200 px-3.5 py-3 text-sm text-slate-800 outline-none transition focus:border-amber-400 focus:ring-4 focus:ring-amber-100">

                    </div>



                    <div>

                        <label
                            class="mb-2 block text-sm font-semibold text-slate-700">

                            Estimasi Selesai

                        </label>


                        <input
                            type="date"
                            name="estimasi_selesai"
                            value="{{ old('estimasi_selesai') }}"
                            class="w-full rounded-xl border border-slate-200 px-3.5 py-3 text-sm text-slate-800 outline-none transition focus:border-amber-400 focus:ring-4 focus:ring-amber-100">

                    </div>



                    <div>

                        <label
                            class="mb-2 block text-sm font-semibold text-slate-700">

                            Status Awal
                            <span class="text-rose-500">*</span>

                        </label>


                        <select
                            name="status"
                            required
                            class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-3 text-sm text-slate-800 outline-none transition focus:border-amber-400 focus:ring-4 focus:ring-amber-100">

                            <option
                                value="Akan Dimulai"
                                {{ old('status', 'Akan Dimulai') === 'Akan Dimulai' ? 'selected' : '' }}>
                                Akan Dimulai
                            </option>

                            <option
                                value="Berjalan"
                                {{ old('status') === 'Berjalan' ? 'selected' : '' }}>
                                Berjalan
                            </option>

                            <option
                                value="Ditunda"
                                {{ old('status') === 'Ditunda' ? 'selected' : '' }}>
                                Ditunda
                            </option>

                            <option
                                value="Selesai"
                                {{ old('status') === 'Selesai' ? 'selected' : '' }}>
                                Selesai
                            </option>
                        </select>
                    </div>
                </div>
                {{-- Footer --}}
                <div
                    class="flex items-center justify-end gap-3 border-t border-slate-100 pt-6">
                    <button
                        type="button"
                        onclick="toggleModal('modalTambahProyek')"
                        class="rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">
                        Simpan Proyek
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif



@if($isAdmin)

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        window.proyekConfig = {
            hasValidationErrors: @json($errors->any()),
            successMessage: @json(session('success')),
            errorMessage: @json(session('error')),
        };
    </script>

    <script src="{{ asset('js/manajemen-proyek.js') }}"></script>

@endif

@endsection
