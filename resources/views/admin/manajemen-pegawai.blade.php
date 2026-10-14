@extends('layouts.admin')

@section('title', 'Manajemen Pegawai')

@section('content')

@php
$totalPegawai = $totalPegawai ?? $pegawais->count();
$pegawaiAktif = $pegawaiAktif ?? $pegawais->where('status', 'aktif')->count();
$totalJabatan = $totalJabatan ?? $jabatans->count();
$totalKategoriGaji = $totalKategoriGaji ?? $jabatans->count();

$isAdmin = auth()->check()
&& in_array(auth()->user()->role, ['admin', 'super_admin']);
@endphp

<div class="space-y-6">

    @include('components.alerts')

    {{-- VALIDATION ERROR --}}
    @if($errors->any())
    <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3.5">
        <div class="flex items-start gap-3">
            <svg class="mt-0.5 h-5 w-5 shrink-0 text-rose-500"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 9v3m0 4h.01M10.29 3.86l-8.82 15A2 2 0 003.2 22h17.6a2 2 0 001.73-3.14l-8.82-15a2 2 0 00-3.42 0z" />
            </svg>

            <div>
                <p class="text-sm font-semibold text-rose-700">
                    Periksa kembali data yang dimasukkan
                </p>

                <ul class="mt-2 list-disc space-y-1 pl-4 text-sm leading-5 text-rose-600">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif


    {{-- =========================================================
        PAGE HEADER / BANNER
    ========================================================== --}}
    <section class="relative isolate overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        {{-- Background image dibuat lebih halus --}}
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

        <div class="relative px-6 py-7 sm:px-8 sm:py-8">

            {{-- Heading --}}
            <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">

                <div class="min-w-0">
                    <div class="mb-3 flex items-center gap-2.5">
                        <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                        <span class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-400">
                            Administrasi & Manajemen SDM
                        </span>
                    </div>

                    <h1 class="text-2xl font-semibold tracking-tight text-slate-900 sm:text-3xl">
                        Manajemen Pegawai
                    </h1>

                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500 sm:text-[15px]">
                        Kelola informasi pegawai, kategori jabatan, standar gaji,
                        serta akses akun dalam satu ruang kerja operasional.
                    </p>
                </div>

                @if($isAdmin)
                <button
                    type="button"
                    onclick="switchTab('pegawai'); toggleModal('modalTambahPegawai', true)"
                    @if($jabatans->isEmpty()) disabled @endif
                    class="inline-flex shrink-0 items-center justify-center gap-2.5 rounded-xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-slate-800 hover:shadow-md active:translate-y-0 disabled:cursor-not-allowed disabled:bg-slate-300 disabled:hover:translate-y-0 disabled:hover:shadow-sm">

                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>

                    Tambah Pegawai
                </button>
                @endif
            </div>


            {{-- Statistics --}}
            <div class="mt-7 grid grid-cols-2 gap-3 sm:grid-cols-4">

                {{-- Total Pegawai --}}
                <div class="rounded-xl border border-slate-200/80 bg-white/85 px-4 py-4 backdrop-blur-sm">
                    <div class="flex items-center justify-between gap-2">
                        <p class="text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">
                            Total Pegawai
                        </p>
                        <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                    </div>

                    <p class="mt-2 text-xl font-semibold tracking-tight text-slate-900 sm:text-2xl">
                        {{ number_format($totalPegawai) }}
                    </p>

                    <p class="mt-1 text-xs text-slate-500 sm:text-sm">
                        Pegawai terdaftar
                    </p>
                </div>

                {{-- Pegawai Aktif --}}
                <div class="rounded-xl border border-slate-200/80 bg-white/85 px-4 py-4 backdrop-blur-sm">
                    <div class="flex items-center justify-between gap-2">
                        <p class="text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">
                            Pegawai Aktif
                        </p>
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    </div>

                    <p class="mt-2 text-xl font-semibold tracking-tight text-slate-900 sm:text-2xl">
                        {{ number_format($pegawaiAktif) }}
                    </p>

                    <p class="mt-1 text-xs text-slate-500 sm:text-sm">
                        Masih aktif bekerja
                    </p>
                </div>

                {{-- Jabatan --}}
                <div class="rounded-xl border border-slate-200/80 bg-white/85 px-4 py-4 backdrop-blur-sm">
                    <div class="flex items-center justify-between gap-2">
                        <p class="text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">
                            Jabatan
                        </p>
                        <span class="h-2 w-2 rounded-full bg-violet-500"></span>
                    </div>

                    <p class="mt-2 text-xl font-semibold tracking-tight text-slate-900 sm:text-2xl">
                        {{ number_format($totalJabatan) }}
                    </p>

                    <p class="mt-1 text-xs text-slate-500 sm:text-sm">
                        Kategori jabatan
                    </p>
                </div>

                {{-- Struktur Gaji --}}
                <div class="rounded-xl border border-slate-200/80 bg-white/85 px-4 py-4 backdrop-blur-sm">
                    <div class="flex items-center justify-between gap-2">
                        <p class="text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">
                            Struktur Gaji
                        </p>
                        <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                    </div>

                    <p class="mt-2 text-xl font-semibold tracking-tight text-slate-900 sm:text-2xl">
                        {{ number_format($totalKategoriGaji) }}
                    </p>

                    <p class="mt-1 text-xs text-slate-500 sm:text-sm">
                        Standar gaji terdaftar
                    </p>
                </div>

            </div>
        </div>
    </section>


    {{-- =========================================================
        MAIN CONTENT
    ========================================================== --}}
    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        {{-- Section Header --}}
        <div class="flex flex-col gap-3 border-b border-slate-100 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center gap-2.5">
                    <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                    <h2 class="text-base font-semibold tracking-tight text-slate-800 sm:text-lg">
                        Direktori Pegawai
                    </h2>
                </div>

                <p class="mt-1.5 text-sm leading-6 text-slate-500">
                    Kelola data pegawai, jabatan, gaji harian, dan hak akses sistem.
                </p>
            </div>

            <div class="text-sm font-medium text-slate-400">
                {{ number_format($totalPegawai) }} pegawai
            </div>
        </div>


        {{-- Tab Navigation --}}
        <div class="border-b border-slate-200 px-4 sm:px-6">
            <div class="flex gap-6 overflow-x-auto">

                <button
                    type="button"
                    id="tabPegawai"
                    onclick="switchTab('pegawai')"
                    class="tab-button whitespace-nowrap border-b-2 border-slate-900 px-1 py-4 text-sm font-semibold text-slate-900 transition">

                    Daftar Pegawai

                    <span class="ml-1.5 rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600">
                        {{ $pegawais->count() }}
                    </span>
                </button>

                <button
                    type="button"
                    id="tabJabatan"
                    onclick="switchTab('jabatan')"
                    class="tab-button whitespace-nowrap border-b-2 border-transparent px-1 py-4 text-sm font-medium text-slate-500 transition hover:text-slate-800">

                    Kategori Jabatan & Gaji

                    <span class="ml-1.5 rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600">
                        {{ $jabatans->count() }}
                    </span>
                </button>

            </div>
        </div>


        {{-- =====================================================
            TAB PEGAWAI
        ====================================================== --}}
        <div id="contentPegawai" class="p-4 sm:p-6">

            {{-- Tab Header --}}
            <div class="mb-5 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h3 class="text-base font-semibold tracking-tight text-slate-800">
                        Daftar Pegawai
                    </h3>

                    <p class="mt-1.5 text-sm leading-6 text-slate-500">
                        Informasi pegawai, status kepegawaian, dan akses akun sistem.
                    </p>
                </div>

                @if($isAdmin)
                @if($jabatans->count() > 0)
                <button
                    type="button"
                    onclick="toggleModal('modalTambahPegawai', true)"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">

                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>

                    Tambah Pegawai
                </button>
                @else
                <button
                    type="button"
                    onclick="switchTab('jabatan')"
                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-amber-200 bg-amber-50 px-4 py-2.5 text-sm font-semibold text-amber-800 transition hover:bg-amber-100">

                    Tambahkan Jabatan Terlebih Dahulu
                </button>
                @endif
                @endif
            </div>


            {{-- Search & Filter --}}
            <div class="mb-5 grid grid-cols-1 gap-3 md:grid-cols-[minmax(0,1fr)_180px_200px]">

                {{-- Search --}}
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2"
                                d="m21 21-4.35-4.35m2.1-5.4a7.5 7.5 0 11-15 0 7.5 7.5 0 0115 0z" />
                        </svg>
                    </div>

                    <input
                        type="text"
                        id="searchPegawai"
                        onkeyup="filterPegawaiTable()"
                        placeholder="Cari nama, telepon, atau jabatan..."
                        class="w-full rounded-xl border border-slate-200 bg-white py-3 pl-10 pr-3.5 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-amber-400 focus:ring-4 focus:ring-amber-100">
                </div>

                {{-- Status --}}
                <select
                    id="filterStatus"
                    onchange="filterPegawaiTable()"
                    class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-3 text-sm text-slate-700 outline-none transition focus:border-amber-400 focus:ring-4 focus:ring-amber-100">

                    <option value="">Semua Status</option>
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                </select>

                {{-- Jabatan --}}
                <select
                    id="filterJabatan"
                    onchange="filterPegawaiTable()"
                    class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-3 text-sm text-slate-700 outline-none transition focus:border-amber-400 focus:ring-4 focus:ring-amber-100">

                    <option value="">Semua Jabatan</option>

                    @foreach($jabatans as $jabatan)
                    <option value="{{ strtolower($jabatan->nama_jabatan) }}">
                        {{ $jabatan->nama_jabatan }}
                    </option>
                    @endforeach
                </select>

            </div>


            {{-- Employee Table --}}
            <div class="overflow-hidden rounded-2xl border border-slate-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">

                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">
                                    Nama Lengkap
                                </th>

                                <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">
                                    Jabatan
                                </th>

                                <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">
                                    Status
                                </th>

                                <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">
                                    Nomor Telepon
                                </th>

                                <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">
                                    Akun Sistem
                                </th>

                                <th class="px-5 py-4 text-right text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody id="pegawaiTableBody" class="divide-y divide-slate-100 bg-white">

                            @forelse($pegawais as $pegawai)
                            @php
                            $status = $pegawai->status ?? 'aktif';
                            $isActive = $status === 'aktif';
                            @endphp

                            <tr
                                class="pegawai-row transition hover:bg-slate-50/80"
                                data-nama="{{ strtolower($pegawai->nama) }}"
                                data-telp="{{ strtolower($pegawai->no_telp ?? '') }}"
                                data-jabatan="{{ strtolower($pegawai->jabatan->nama_jabatan ?? '') }}"
                                data-status="{{ $status }}">

                                {{-- Name --}}
                                <td class="whitespace-nowrap px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-slate-50 text-sm font-semibold text-slate-600">
                                            {{ strtoupper(substr($pegawai->nama, 0, 1)) }}
                                        </div>

                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-slate-800">
                                                {{ $pegawai->nama }}
                                            </p>

                                            <p class="mt-1 text-xs text-slate-400">
                                                ID Pegawai #{{ $pegawai->id }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Position --}}
                                <td class="whitespace-nowrap px-5 py-4">
                                    <span class="inline-flex items-center rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-1.5 text-xs font-medium text-slate-700">
                                        {{ $pegawai->jabatan->nama_jabatan ?? '-' }}
                                    </span>
                                </td>

                                {{-- Status --}}
                                <td class="whitespace-nowrap px-5 py-4">
                                    @if($isActive)
                                    <span class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                        Aktif
                                    </span>
                                    @else
                                    <span class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-600">
                                        <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                        Nonaktif
                                    </span>
                                    @endif
                                </td>

                                {{-- Phone --}}
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600">
                                    {{ $pegawai->no_telp ?: '-' }}
                                </td>

                                {{-- Account --}}
                                <td class="whitespace-nowrap px-5 py-4">
                                    @if($pegawai->user_id)
                                    <span class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                        Memiliki Akun
                                    </span>
                                    @else
                                    <span class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-medium text-slate-500">
                                        <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                        Tanpa Akun
                                    </span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="whitespace-nowrap px-5 py-4">
                                    <div class="flex items-center justify-end gap-1">

                                        {{-- Detail --}}
                                        <button
                                            type="button"
                                            title="Lihat detail"
                                            aria-label="Lihat detail pegawai {{ $pegawai->nama }}"
                                            data-id="{{ $pegawai->id }}"
                                            data-nama="{{ $pegawai->nama }}"
                                            data-telp="{{ $pegawai->no_telp }}"
                                            data-jabatan="{{ $pegawai->jabatan->nama_jabatan ?? '-' }}"
                                            data-status="{{ $status }}"
                                            data-email="{{ $pegawai->user?->email }}"
                                            data-has-account="{{ $pegawai->user_id ? '1' : '0' }}"
                                            onclick="openDetailPegawaiModal(this)"
                                            class="rounded-lg p-2 text-slate-500 transition hover:bg-slate-100 hover:text-slate-900">

                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="1.8"
                                                    d="M2.25 12s3.75-6 9.75-6 9.75 6 9.75 6-3.75 6-9.75 6-9.75-6-9.75-6z" />
                                                <circle cx="12" cy="12" r="2.5" stroke-width="1.8" />
                                            </svg>
                                        </button>

                                        @if($isAdmin)
                                        {{-- Edit --}}
                                        {{-- Edit --}}
                                        <button
                                            type="button"
                                            title="Edit pegawai"
                                            aria-label="Edit pegawai {{ $pegawai->nama }}"
                                            data-id="{{ $pegawai->id }}"
                                            data-nama="{{ $pegawai->nama }}"
                                            data-telp="{{ $pegawai->no_telp }}"
                                            data-status="{{ $status }}"
                                            data-jabatan-id="{{ $pegawai->jabatan_id }}"
                                            data-jabatan-name="{{ $pegawai->jabatan->nama_jabatan ?? '' }}"
                                            data-email="{{ $pegawai->user?->email ?? '' }}"
                                            data-has-account="{{ $pegawai->user_id ? '1' : '0' }}"
                                            data-update-url="{{ route('pegawai.update', ['pegawai' => $pegawai->id]) }}"
                                            onclick="openEditPegawaiModal(this)"
                                            class="rounded-lg p-2 text-slate-500 transition hover:bg-amber-50 hover:text-amber-700">

                                            <svg
                                                class="h-4 w-4"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24">

                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="1.8"
                                                    d="M16.862 3.487a2.25 2.25 0 013.182 3.182L8.25 18.463 4.5 19.5l1.037-3.75L16.862 3.487z" />
                                            </svg>
                                        </button>

                                        {{-- Delete --}}
                                        <form
                                            id="deleteForm-pegawai-{{ $pegawai->id }}"
                                            action="{{ route('pegawai.destroy', $pegawai->id) }}"
                                            method="POST"
                                            onsubmit="return false;">

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="button"
                                                title="Hapus pegawai"
                                                aria-label="Hapus pegawai {{ $pegawai->nama }}"
                                                onclick="confirmDelete('deleteForm-pegawai-{{ $pegawai->id }}', 'pegawai ini')"
                                                class="rounded-lg p-2 text-slate-400 transition hover:bg-rose-50 hover:text-rose-600">

                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1.8"
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
                                <td colspan="6" class="px-5 py-16 text-center">
                                    <div class="mx-auto flex max-w-sm flex-col items-center">
                                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl border border-slate-200 bg-slate-50">
                                            <svg class="h-6 w-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="1.5"
                                                    d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4-5a4 4 0 100-8 4 4 0 000 8z" />
                                            </svg>
                                        </div>

                                        <h3 class="mt-4 text-sm font-semibold text-slate-800">
                                            Belum Ada Data Pegawai
                                        </h3>

                                        <p class="mt-2 max-w-xs text-sm leading-6 text-slate-500">
                                            Tambahkan pegawai untuk mulai mengelola data dan akses sistem.
                                        </p>

                                        @if($isAdmin && $jabatans->isNotEmpty())
                                        <button
                                            type="button"
                                            onclick="toggleModal('modalTambahPegawai', true)"
                                            class="mt-5 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">
                                            Tambah Pegawai
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>

                {{-- Search Empty State --}}
                <div id="searchEmptyState" class="hidden border-t border-slate-100 px-5 py-16 text-center">
                    <div class="mx-auto flex max-w-sm flex-col items-center">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl border border-slate-200 bg-slate-50">
                            <svg class="h-6 w-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="m21 21-4.35-4.35m2.1-5.4a7.5 7.5 0 11-15 0 7.5 7.5 0 0115 0z" />
                            </svg>
                        </div>

                        <h3 class="mt-4 text-sm font-semibold text-slate-800">
                            Data Tidak Ditemukan
                        </h3>

                        <p class="mt-2 max-w-xs text-sm leading-6 text-slate-500">
                            Coba ubah kata kunci atau filter pencarian Anda.
                        </p>
                    </div>
                </div>
            </div>
        </div>


        {{-- =====================================================
            TAB JABATAN
        ====================================================== --}}
        <div id="contentJabatan" class="hidden p-4 sm:p-6">

            <div class="mb-5 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-base font-semibold tracking-tight text-slate-800">
                        Kategori Jabatan & Gaji
                    </h3>

                    <p class="mt-1.5 text-sm leading-6 text-slate-500">
                        Atur kategori jabatan, standar gaji harian, dan hak akses akun.
                    </p>
                </div>

                @if($isAdmin)
                <button
                    type="button"
                    onclick="toggleModal('modalTambahJabatan', true)"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">

                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>

                    Tambah Jabatan
                </button>
                @endif
            </div>


            {{-- Position Table --}}
            <div class="overflow-hidden rounded-2xl border border-slate-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">

                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">
                                    Nama Jabatan
                                </th>

                                <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">
                                    Standar Gaji Harian
                                </th>

                                <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">
                                    Akses Sistem
                                </th>

                                <th class="px-5 py-4 text-right text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100 bg-white">

                            @forelse($jabatans as $jabatan)
                            <tr class="transition hover:bg-slate-50/80">

                                <td class="whitespace-nowrap px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-slate-50 text-slate-500">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="1.8"
                                                    d="M20 21a8 8 0 00-16 0m12-13a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                        </div>

                                        <div>
                                            <p class="text-sm font-semibold text-slate-800">
                                                {{ $jabatan->nama_jabatan }}
                                            </p>

                                            <p class="mt-1 text-xs text-slate-400">
                                                ID Jabatan #{{ $jabatan->id }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="whitespace-nowrap px-5 py-4">
                                    <p class="text-sm font-semibold text-slate-800">
                                        Rp {{ number_format($jabatan->gaji_harian, 0, ',', '.') }}
                                    </p>

                                    <p class="mt-1 text-xs text-slate-400">
                                        Per hari
                                    </p>
                                </td>

                                <td class="whitespace-nowrap px-5 py-4">
                                    @if($jabatan->can_login ?? false)
                                    <span class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                        Dapat Login
                                    </span>
                                    @else
                                    <span class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-medium text-slate-500">
                                        <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                        Tanpa Login
                                    </span>
                                    @endif
                                </td>

                                <td class="whitespace-nowrap px-5 py-4">
                                    <div class="flex items-center justify-end gap-1">

                                        @if($isAdmin)
                                        {{-- Edit --}}
                                        <button
                                            type="button"
                                            title="Edit jabatan"
                                            aria-label="Edit jabatan {{ $jabatan->nama_jabatan }}"
                                            data-id="{{ $jabatan->id }}"
                                            data-name="{{ $jabatan->nama_jabatan }}"
                                            data-salary="{{ $jabatan->gaji_harian }}"
                                            data-can-login="{{ ($jabatan->can_login ?? false) ? '1' : '0' }}"
                                            data-update-url="{{ route('jabatan.update', ['jabatan' => $jabatan->id]) }}"
                                            onclick="openEditJabatanModal(this)"
                                            class="rounded-lg p-2 text-slate-500 transition hover:bg-amber-50 hover:text-amber-700">

                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="1.8"
                                                    d="M16.862 3.487a2.25 2.25 0 013.182 3.182L8.25 18.463 4.5 19.5l1.037-3.75L16.862 3.487z" />
                                            </svg>
                                        </button>

                                        {{-- Delete --}}
                                        <form
                                            id="deleteForm-jabatan-{{ $jabatan->id }}"
                                            action="{{ route('jabatan.destroy', $jabatan->id) }}"
                                            method="POST"
                                            onsubmit="return false;">

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="button"
                                                title="Hapus jabatan"
                                                aria-label="Hapus jabatan {{ $jabatan->nama_jabatan }}"
                                                onclick="confirmDelete('deleteForm-jabatan-{{ $jabatan->id }}', 'jabatan ini')"
                                                class="rounded-lg p-2 text-slate-400 transition hover:bg-rose-50 hover:text-rose-600">

                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1.8"
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
                                <td colspan="4" class="px-5 py-16 text-center">
                                    <div class="mx-auto flex max-w-sm flex-col items-center">
                                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl border border-slate-200 bg-slate-50">
                                            <svg class="h-6 w-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="1.5"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l5 5v11a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>

                                        <h3 class="mt-4 text-sm font-semibold text-slate-800">
                                            Belum Ada Kategori Jabatan
                                        </h3>

                                        <p class="mt-2 max-w-xs text-sm leading-6 text-slate-500">
                                            Tambahkan jabatan untuk mengatur standar gaji dan akses pegawai.
                                        </p>

                                        @if($isAdmin)
                                        <button
                                            type="button"
                                            onclick="toggleModal('modalTambahJabatan', true)"
                                            class="mt-5 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">
                                            Tambah Jabatan
                                        </button>
                                        @endif
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
@if($isAdmin)
<x-modal
    id="modalTambahJabatan"
    title="Tambah Kategori Jabatan"
    description="Lengkapi informasi jabatan dan standar gaji harian.">

    <form action="{{ route('jabatan.store') }}" method="POST">
        @csrf

        <div class="space-y-5 px-6 py-6">

            <div>
                <label for="nama_jabatan" class="mb-2 block text-sm font-semibold text-slate-700">
                    Nama Jabatan <span class="text-rose-500">*</span>
                </label>

                <input
                    type="text"
                    id="nama_jabatan"
                    name="nama_jabatan"
                    value="{{ old('nama_jabatan') }}"
                    required
                    placeholder="Contoh: Mandor"
                    class="w-full rounded-xl border border-slate-200 px-3.5 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-amber-400 focus:ring-4 focus:ring-amber-100">
            </div>

            <div>
                <label for="gaji_harian" class="mb-2 block text-sm font-semibold text-slate-700">
                    Standar Gaji Harian <span class="text-rose-500">*</span>
                </label>

                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-sm font-semibold text-slate-400">
                        Rp
                    </span>

                    <input
                        type="number"
                        id="gaji_harian"
                        name="gaji_harian"
                        value="{{ old('gaji_harian') }}"
                        min="0"
                        required
                        placeholder="150000"
                        class="w-full rounded-xl border border-slate-200 py-3 pl-10 pr-3.5 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-amber-400 focus:ring-4 focus:ring-amber-100">
                </div>
            </div>

            <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-4">
                <label class="flex cursor-pointer items-start gap-3">
                    <input
                        type="checkbox"
                        id="can_login"
                        name="can_login"
                        value="1"
                        {{ old('can_login') ? 'checked' : '' }}
                        class="mt-0.5 h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-amber-400">

                    <span>
                        <span class="block text-sm font-semibold text-slate-800">
                            Jabatan dapat memiliki akun
                        </span>

                        <span class="mt-1 block text-sm leading-5 text-slate-500">
                            Aktifkan jika pegawai dengan jabatan ini membutuhkan akses login ke sistem.
                        </span>
                    </span>
                </label>
            </div>
        </div>

        <div class="flex justify-end gap-3 border-t border-slate-100 px-6 py-5">
            <button
                type="button"
                onclick="toggleModal('modalTambahJabatan', false)"
                class="rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                Batal
            </button>

            <button
                type="submit"
                class="rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">
                Simpan Jabatan
            </button>
        </div>
    </form>
</x-modal>


{{-- =============================================================
    MODAL EDIT JABATAN
============================================================= --}}
<x-modal
    id="modalEditJabatan"
    title="Edit Kategori Jabatan"
    description="Perbarui informasi jabatan dan standar gaji.">

    <form id="formEditJabatan" method="POST">
        @csrf
        @method('PUT')

        <div class="space-y-5 px-6 py-6">

            <div>
                <label for="edit_nama_jabatan" class="mb-2 block text-sm font-semibold text-slate-700">
                    Nama Jabatan <span class="text-rose-500">*</span>
                </label>

                <input
                    type="text"
                    id="edit_nama_jabatan"
                    name="nama_jabatan"
                    required
                    class="w-full rounded-xl border border-slate-200 px-3.5 py-3 text-sm text-slate-800 outline-none transition focus:border-amber-400 focus:ring-4 focus:ring-amber-100">
            </div>

            <div>
                <label for="edit_gaji_harian" class="mb-2 block text-sm font-semibold text-slate-700">
                    Standar Gaji Harian <span class="text-rose-500">*</span>
                </label>

                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-sm font-semibold text-slate-400">
                        Rp
                    </span>

                    <input
                        type="number"
                        id="edit_gaji_harian"
                        name="gaji_harian"
                        min="0"
                        required
                        class="w-full rounded-xl border border-slate-200 py-3 pl-10 pr-3.5 text-sm text-slate-800 outline-none transition focus:border-amber-400 focus:ring-4 focus:ring-amber-100">
                </div>
            </div>

            <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-4">
                <label class="flex cursor-pointer items-start gap-3">
                    <input
                        type="checkbox"
                        id="edit_can_login"
                        name="can_login"
                        value="1"
                        class="mt-0.5 h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-amber-400">

                    <span>
                        <span class="block text-sm font-semibold text-slate-800">
                            Jabatan dapat memiliki akun
                        </span>

                        <span class="mt-1 block text-sm leading-5 text-slate-500">
                            Tentukan apakah pegawai dengan jabatan ini dapat memiliki akses login.
                        </span>
                    </span>
                </label>
            </div>
        </div>

        <div class="flex justify-end gap-3 border-t border-slate-100 px-6 py-5">
            <button
                type="button"
                onclick="toggleModal('modalEditJabatan', false)"
                class="rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                Batal
            </button>

            <button
                type="submit"
                class="rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">
                Simpan Perubahan
            </button>
        </div>
    </form>
</x-modal>


{{-- =============================================================
    MODAL TAMBAH PEGAWAI
============================================================= --}}
<x-modal
    id="modalTambahPegawai"
    title="Tambah Pegawai Baru"
    description="Lengkapi informasi pegawai dan pengaturan akses sistem.">

    <form action="{{ route('pegawai.store') }}" method="POST">
        @csrf

        <div class="space-y-5 px-6 py-6">

            <div>
                <label for="pegawai_nama" class="mb-2 block text-sm font-semibold text-slate-700">
                    Nama Lengkap <span class="text-rose-500">*</span>
                </label>

                <input
                    type="text"
                    id="pegawai_nama"
                    name="nama"
                    value="{{ old('nama') }}"
                    required
                    placeholder="Masukkan nama lengkap"
                    class="w-full rounded-xl border border-slate-200 px-3.5 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-amber-400 focus:ring-4 focus:ring-amber-100">
            </div>

            <div>
                <label for="pegawai_telp" class="mb-2 block text-sm font-semibold text-slate-700">
                    Nomor Telepon
                </label>

                <input
                    type="text"
                    id="pegawai_telp"
                    name="no_telp"
                    value="{{ old('no_telp') }}"
                    placeholder="08xxxxxxxxxx"
                    class="w-full rounded-xl border border-slate-200 px-3.5 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-amber-400 focus:ring-4 focus:ring-amber-100">
            </div>

            <div>
                <label for="pegawai_jabatan" class="mb-2 block text-sm font-semibold text-slate-700">
                    Jabatan <span class="text-rose-500">*</span>
                </label>

                <select
                    id="pegawai_jabatan"
                    name="jabatan_id"
                    onchange="checkJabatan(this)"
                    required
                    class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-3 text-sm text-slate-800 outline-none transition focus:border-amber-400 focus:ring-4 focus:ring-amber-100">

                    <option value="">Pilih Jabatan</option>

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

            <div>
                <label for="pegawai_status" class="mb-2 block text-sm font-semibold text-slate-700">
                    Status Pegawai <span class="text-rose-500">*</span>
                </label>

                <select
                    id="pegawai_status"
                    name="status"
                    required
                    class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-3 text-sm text-slate-800 outline-none transition focus:border-amber-400 focus:ring-4 focus:ring-amber-100">

                    <option value="aktif" {{ old('status', 'aktif') === 'aktif' ? 'selected' : '' }}>
                        Aktif
                    </option>

                    <option value="nonaktif" {{ old('status') === 'nonaktif' ? 'selected' : '' }}>
                        Nonaktif
                    </option>
                </select>

                <p class="mt-2 text-xs leading-5 text-slate-500">
                    Status ini menunjukkan apakah pegawai masih aktif bekerja.
                </p>
            </div>

            {{-- Account Fields --}}
            <div id="accountFields" class="hidden rounded-xl border border-slate-200 bg-slate-50/70 p-4">

                <div class="mb-4">
                    <div class="flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                        <p class="text-sm font-semibold text-slate-800">
                            Akses Akun Sistem
                        </p>
                    </div>

                    <p class="mt-1.5 text-sm leading-5 text-slate-500">
                        Jabatan ini dapat memiliki akun untuk login ke sistem.
                    </p>
                </div>

                <div class="space-y-4">
                    <div>
                        <label for="pegawai_email" class="mb-2 block text-sm font-semibold text-slate-700">
                            Email
                        </label>

                        <input
                            type="email"
                            id="pegawai_email"
                            name="email"
                            value="{{ old('email') }}"
                            autocomplete="email"
                            placeholder="nama@email.com"
                            class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-3 text-sm text-slate-800 outline-none transition focus:border-amber-400 focus:ring-4 focus:ring-amber-100">
                    </div>


                    <div>
                        <label for="pegawai_password"
                            class="mb-2 block text-sm font-semibold text-slate-700">
                            Password
                        </label>

                        <div class="relative">
                            <input
                                type="password"
                                id="pegawai_password"
                                name="password"
                                autocomplete="new-password"
                                placeholder="Minimal 8 karakter"
                                class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-3 pr-20 text-sm text-slate-800 outline-none transition focus:border-amber-400 focus:ring-4 focus:ring-amber-100">

                            <button
                                type="button"
                                onclick="togglePasswordVisibility('pegawai_password', this)"
                                class="absolute inset-y-0 right-3 my-auto h-fit rounded-lg px-2 py-1 text-xs font-semibold text-slate-500 transition hover:bg-slate-100 hover:text-slate-800"
                                aria-label="Lihat password"
                                aria-pressed="false">
                                Lihat
                            </button>
                        </div>

                        <p class="mt-2 text-xs text-slate-500">
                            Minimal 8 karakter. Gunakan tombol Lihat untuk memeriksa password.
                        </p>
                    </div>

                </div>
            </div>

        </div>

        <div class="flex justify-end gap-3 border-t border-slate-100 px-6 py-5">
            <button
                type="button"
                onclick="toggleModal('modalTambahPegawai', false)"
                class="rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                Batal
            </button>

            <button
                type="submit"
                class="rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">
                Simpan Pegawai
            </button>
        </div>
    </form>
</x-modal>


{{-- =============================================================
    MODAL DETAIL PEGAWAI
============================================================= --}}
<x-modal
    id="modalDetailPegawai"
    title="Detail Pegawai"
    description="Informasi kepegawaian dan status akun sistem.">

    <div class="space-y-5 px-6 py-6">

        {{-- Profile --}}
        <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-slate-50/70 p-4">
            <div
                id="detailAvatar"
                class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-lg font-semibold text-slate-600">
                -
            </div>

            <div class="min-w-0">
                <h4 id="detailNama" class="truncate text-base font-semibold text-slate-900">
                    -
                </h4>

                <p id="detailJabatan" class="mt-1 text-sm text-slate-500">
                    -
                </p>
            </div>
        </div>

        {{-- Detail information --}}
        <div class="overflow-hidden rounded-xl border border-slate-200 divide-y divide-slate-100">

            <div class="flex items-center justify-between gap-4 px-4 py-4">
                <span class="text-sm text-slate-500">Status Pegawai</span>
                <span id="detailStatusPegawai">-</span>
            </div>

            <div class="flex items-center justify-between gap-4 px-4 py-4">
                <span class="text-sm text-slate-500">Nomor Telepon</span>
                <span id="detailTelp" class="text-right text-sm font-medium text-slate-800">-</span>
            </div>

            <div class="flex items-center justify-between gap-4 px-4 py-4">
                <span class="text-sm text-slate-500">Status Akun</span>
                <span id="detailStatusAkun">-</span>
            </div>

            <div id="detailEmailWrapper" class="hidden items-center justify-between gap-4 px-4 py-4">
                <span class="text-sm text-slate-500">Email</span>
                <span id="detailEmail" class="max-w-[60%] break-all text-right text-sm font-medium text-slate-800">-</span>
            </div>

        </div>
    </div>

    <div class="flex justify-end border-t border-slate-100 px-6 py-5">
        <button
            type="button"
            onclick="toggleModal('modalDetailPegawai', false)"
            class="rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
            Tutup
        </button>
    </div>
</x-modal>


{{-- =============================================================
    MODAL EDIT PEGAWAI
============================================================= --}}
<x-modal
    id="modalEditPegawai"
    title="Edit Data Pegawai"
    description="Perbarui informasi pegawai dan pengaturan akses sistem.">

    <form id="formEditPegawai" method="POST">
        @csrf
        @method('PUT')

        <div class="space-y-5 px-6 py-6">

            <div>
                <label for="edit_pegawai_nama" class="mb-2 block text-sm font-semibold text-slate-700">
                    Nama Lengkap <span class="text-rose-500">*</span>
                </label>

                <input
                    type="text"
                    id="edit_pegawai_nama"
                    name="nama"
                    required
                    class="w-full rounded-xl border border-slate-200 px-3.5 py-3 text-sm text-slate-800 outline-none transition focus:border-amber-400 focus:ring-4 focus:ring-amber-100">
            </div>

            <div>
                <label for="edit_pegawai_telp" class="mb-2 block text-sm font-semibold text-slate-700">
                    Nomor Telepon
                </label>

                <input
                    type="text"
                    id="edit_pegawai_telp"
                    name="no_telp"
                    class="w-full rounded-xl border border-slate-200 px-3.5 py-3 text-sm text-slate-800 outline-none transition focus:border-amber-400 focus:ring-4 focus:ring-amber-100">
            </div>

            <div>
                <label for="edit_pegawai_jabatan" class="mb-2 block text-sm font-semibold text-slate-700">
                    Jabatan <span class="text-rose-500">*</span>
                </label>

                <select
                    id="edit_pegawai_jabatan"
                    name="jabatan_id"
                    onchange="checkEditJabatan(this)"
                    required
                    class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-3 text-sm text-slate-800 outline-none transition focus:border-amber-400 focus:ring-4 focus:ring-amber-100">

                    @foreach($jabatans as $jabatan)
                    <option
                        value="{{ $jabatan->id }}"
                        data-can-login="{{ ($jabatan->can_login ?? false) ? '1' : '0' }}">
                        {{ $jabatan->nama_jabatan }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="edit_pegawai_status" class="mb-2 block text-sm font-semibold text-slate-700">
                    Status Pegawai <span class="text-rose-500">*</span>
                </label>

                <select
                    id="edit_pegawai_status"
                    name="status"
                    required
                    class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-3 text-sm text-slate-800 outline-none transition focus:border-amber-400 focus:ring-4 focus:ring-amber-100">

                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                </select>

                <p class="mt-2 text-xs leading-5 text-slate-500">
                    Menonaktifkan pegawai tidak menghapus riwayat kepegawaiannya.
                </p>
            </div>

            {{-- Edit account fields --}}
            <div id="editAccountFields" class="hidden rounded-xl border border-slate-200 bg-slate-50/70 p-4">

                <div class="mb-4">
                    <div class="flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                        <p class="text-sm font-semibold text-slate-800">
                            Akses Akun Sistem
                        </p>
                    </div>

                    <p class="mt-1.5 text-sm leading-5 text-slate-500">
                        Kosongkan password jika tidak ingin mengubah password saat ini.
                    </p>
                </div>

                <div class="space-y-4">
                    <div>
                        <label for="edit_pegawai_email" class="mb-2 block text-sm font-semibold text-slate-700">
                            Email
                        </label>

                        <input
                            type="email"
                            id="edit_pegawai_email"
                            name="email"
                            autocomplete="email"
                            class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-3 text-sm text-slate-800 outline-none transition focus:border-amber-400 focus:ring-4 focus:ring-amber-100">
                    </div>

                    <div>
                        <label for="edit_pegawai_password" class="mb-2 block text-sm font-semibold text-slate-700">
                            Password Baru
                        </label>

                        <input
                            type="password"
                            id="edit_pegawai_password"
                            name="password"
                            autocomplete="new-password"
                            placeholder="Kosongkan jika tidak diubah"
                            class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-3 text-sm text-slate-800 outline-none transition focus:border-amber-400 focus:ring-4 focus:ring-amber-100">
                    </div>
                </div>
            </div>

        </div>

        <div class="flex justify-end gap-3 border-t border-slate-100 px-6 py-5">
            <button
                type="button"
                onclick="toggleModal('modalEditPegawai', false)"
                class="rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                Batal
            </button>

            <button
                type="submit"
                class="rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">
                Simpan Perubahan
            </button>
        </div>
    </form>
</x-modal>

@endif


{{-- =============================================================
    JAVASCRIPT
============================================================= --}}
<script>
    const jabatanCanLogin = @json(
        $jabatans -> mapWithKeys(fn($j) => [(string) $j -> id => $j -> allowsLogin()]) -> all(),
        JSON_FORCE_OBJECT
    );
</script>

{{-- Static cache-buster: bump ?v= when the file changes. Deliberately NOT
     filemtime(public_path(...)): a missing file must never 500 the page. --}}
<script src="{{ asset('js/manajemen-pegawai.js') }}?v=1"></script>
@endsection