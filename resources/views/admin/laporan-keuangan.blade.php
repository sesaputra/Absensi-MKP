@extends('layouts.admin')

@section('title', 'Manajemen Dana Proyek - ' . $proyek->nama_proyek)

@section('content')

@php
    $isAdmin = auth()->check()
        && in_array(auth()->user()->role, ['admin', 'super_admin'], true);

    $isSuperAdmin = auth()->check()
        && auth()->user()->role === 'super_admin';

    /*
    |--------------------------------------------------------------------------
    | STATUS KAS
    |--------------------------------------------------------------------------
    | >= 10.000.000 : Aman
    | >= 5.000.000  : Terbatas
    | > 0           : Kas Menipis
    | <= 0          : Defisit
    */
    if ($kasTersedia <= 0) {
        $statusText        = 'Defisit';
        $statusColor       = 'text-rose-600';
        $statusDotColor    = 'bg-rose-500';
        $statusBgColor     = 'bg-rose-50';
        $statusBorderColor = 'border-rose-100';
    } elseif ($kasTersedia < 5000000) {
        $statusText        = 'Kas Menipis';
        $statusColor       = 'text-amber-600';
        $statusDotColor    = 'bg-amber-500';
        $statusBgColor     = 'bg-amber-50';
        $statusBorderColor = 'border-amber-100';
    } elseif ($kasTersedia < 10000000) {
        $statusText        = 'Terbatas';
        $statusColor       = 'text-blue-600';
        $statusDotColor    = 'bg-blue-500';
        $statusBgColor     = 'bg-blue-50';
        $statusBorderColor = 'border-blue-100';
    } else {
        $statusText        = 'Aman';
        $statusColor       = 'text-emerald-600';
        $statusDotColor    = 'bg-emerald-500';
        $statusBgColor     = 'bg-emerald-50';
        $statusBorderColor = 'border-emerald-100';
    }
@endphp


<div class="space-y-6">

    {{-- =========================================================
        PAGE HEADER / BANNER
    ========================================================== --}}
    <section class="relative isolate overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="absolute inset-0 -z-20 bg-gradient-to-br from-white via-white to-slate-50"></div>

        <div
            class="absolute inset-0 -z-10 opacity-[0.018]"
            style="
                background-image:
                    linear-gradient(rgba(15,23,42,.8) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(15,23,42,.8) 1px, transparent 1px);
                background-size: 32px 32px;
            ">
        </div>

        <div class="absolute -right-20 -top-20 h-64 w-64 rounded-full bg-emerald-100/40 blur-3xl"></div>

        <div class="relative px-6 py-7 sm:px-8 sm:py-8">

            {{-- TOP --}}
            <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">

                {{-- HEADING --}}
                <div class="min-w-0">

                    {{-- BACK --}}
                    <a
                        href="{{ route('proyek.show', $proyek->id) }}"
                        class="mb-5 inline-flex items-center gap-2 text-xs font-medium text-slate-400 transition hover:text-slate-700">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke Detail Proyek
                    </a>

                    {{-- EYEBROW --}}
                    <div class="mb-3 flex items-center gap-2.5">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        <span class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-400">
                            Keuangan & Arus Kas Proyek
                        </span>
                    </div>

                    <h1 class="text-2xl font-semibold tracking-tight text-slate-900 sm:text-3xl">
                        Manajemen Dana
                    </h1>

                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500 sm:text-[15px]">
                        Pantau pemasukan, pengeluaran, kasbon, dan pembayaran
                        upah dalam satu ruang kerja keuangan proyek.
                    </p>

                    {{-- PROJECT --}}
                    <div class="mt-4 flex flex-wrap items-center gap-x-4 gap-y-2 text-xs text-slate-400">
                        <span class="font-medium text-slate-600">
                            {{ $proyek->nama_proyek }}
                        </span>

                        <span class="hidden h-1 w-1 rounded-full bg-slate-300 sm:block"></span>

                        <span class="inline-flex items-center gap-1.5">
                            <svg class="h-3.5 w-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ $proyek->lokasi }}
                        </span>
                    </div>
                </div>

                {{-- PROJECT STATUS --}}
                <div class="shrink-0">
                    <span class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-3.5 py-2 text-xs font-semibold text-slate-600">
                        <span
                            class="h-1.5 w-1.5 rounded-full
                            {{ $proyek->status === 'Berjalan'
                                ? 'bg-emerald-500'
                                : ($proyek->status === 'Akan Dimulai'
                                    ? 'bg-blue-500'
                                    : 'bg-slate-400') }}">
                        </span>
                        {{ $proyek->status }}
                    </span>
                </div>

            </div>


            {{-- FINANCIAL SUMMARY --}}
            <div class="mt-7 grid grid-cols-1 gap-3 sm:grid-cols-3">

                {{-- TOTAL PEMASUKAN --}}
                <div class="rounded-xl border border-slate-200/80 bg-white/90 px-4 py-4 backdrop-blur-sm">
                    <div class="flex items-center justify-between gap-2">
                        <p class="text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">
                            Total Pemasukan
                        </p>
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    </div>

                    <p class="mt-2 text-xl font-semibold tracking-tight text-slate-900 sm:text-2xl">
                        Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
                    </p>

                    <p class="mt-1 text-xs text-slate-500 sm:text-sm">
                        Dana yang telah diterima
                    </p>
                </div>

                {{-- TOTAL PENGELUARAN --}}
                <div class="rounded-xl border border-slate-200/80 bg-white/90 px-4 py-4 backdrop-blur-sm">
                    <div class="flex items-center justify-between gap-2">
                        <p class="text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">
                            Total Pengeluaran
                        </p>
                        <span class="h-2 w-2 rounded-full bg-slate-500"></span>
                    </div>

                    <p class="mt-2 text-xl font-semibold tracking-tight text-slate-900 sm:text-2xl">
                        Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                    </p>

                    <p class="mt-1 text-xs text-slate-500 sm:text-sm">
                        Seluruh dana yang telah digunakan
                    </p>
                </div>

                {{-- KAS TERSEDIA --}}
                <div class="rounded-xl border {{ $statusBorderColor }} bg-white/90 px-4 py-4 backdrop-blur-sm">
                    <div class="flex items-center justify-between gap-2">
                        <p class="text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">
                            Kas Tersedia
                        </p>
                        <span class="h-2 w-2 rounded-full {{ $statusDotColor }}"></span>
                    </div>

                    <p
                        class="mt-2 text-xl font-semibold tracking-tight sm:text-2xl
                        {{ $kasTersedia < 0 ? 'text-rose-600' : 'text-slate-900' }}">
                        {{ $kasTersedia < 0 ? '-' : '' }}
                        Rp {{ number_format(abs($kasTersedia), 0, ',', '.') }}
                    </p>

                    <div class="mt-2 flex flex-wrap items-center justify-between gap-2">
                        <p class="text-xs text-slate-500">
                            Dana yang masih tersedia
                        </p>

                        <span class="inline-flex items-center gap-1.5 rounded-full {{ $statusBgColor }} px-2.5 py-1 text-xs font-semibold {{ $statusColor }}">
                            <span class="h-1.5 w-1.5 rounded-full {{ $statusDotColor }}"></span>
                            {{ $statusText }}
                        </span>
                    </div>
                </div>

            </div>


            {{-- FINANCIAL ACTIONS --}}
            @if($isSuperAdmin)
            <div class="mt-7 border-t border-slate-100 pt-6">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                    <div>
                        <div class="flex items-center gap-2.5">
                            <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                            <h2 class="text-sm font-semibold tracking-tight text-slate-800">
                                Aksi Keuangan
                            </h2>
                        </div>

                        <p class="mt-1 text-xs text-slate-500">
                            Catat transaksi dan kelola pembayaran proyek.
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-2">

                        {{-- TERMIN --}}
                        <button
                            type="button"
                            onclick="bukaModalPemasukan()"
                            class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-xs font-semibold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-slate-800 hover:shadow-md">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v16m8-8H4" />
                            </svg>
                            Termin Klien
                        </button>

                        {{-- BIAYA --}}
                        <button
                            type="button"
                            onclick="bukaModalPengeluaran()"
                            class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-semibold text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:bg-slate-50 hover:shadow-md">
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v16m8-8H4" />
                            </svg>
                            Biaya Lapangan
                        </button>

                        {{-- KASBON --}}
                        <button
                            type="button"
                            onclick="bukaModalKasbon()"
                            class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-semibold text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:border-amber-200 hover:bg-amber-50/40">
                            <svg class="h-4 w-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v16m8-8H4" />
                            </svg>
                            Catat Kasbon
                        </button>

                        {{-- BAYAR UPAH --}}
                        <button
                            type="button"
                            onclick="bukaModalGaji()"
                            class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-semibold text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:border-blue-200 hover:bg-blue-50/40">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v16m8-8H4" />
                            </svg>
                            Bayar Upah
                        </button>

                        {{-- PAYROLL --}}
                        <a
                            href="{{ route('proyek.payroll', $proyek->id) }}"
                            class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-semibold text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:bg-slate-50 hover:shadow-md">
                            Rincian Upah
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>

                    </div>
                </div>
            </div>
            @endif

        </div>
    </section>


    {{-- =========================================================
        BUKU KAS PROYEK
    ========================================================== --}}
    <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">

        {{-- SECTION HEADER --}}
        <div class="flex flex-col gap-4 border-b border-slate-100 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <div class="flex items-center gap-2.5">
                    <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                    <h2 class="text-base font-semibold tracking-tight text-slate-800 sm:text-lg">
                        Buku Kas Proyek
                    </h2>
                </div>

                <p class="mt-1.5 text-sm leading-6 text-slate-500">
                    Riwayat pemasukan dan pengeluaran yang tercatat
                    dalam arus kas proyek.
                </p>
            </div>

            <a
                href="{{ route('keuangan.pdf', $proyek->id) }}"
                target="_blank"
                class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-xs font-semibold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-slate-800 hover:shadow-md">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Unduh PDF
            </a>

        </div>


        <div class="p-6">

            @if($keuangans->count())

            {{-- FILTER (event ditangani oleh manajemen-dana-proyek.js) --}}
            <div class="mb-5 flex flex-col gap-3 sm:flex-row">

                <div class="relative flex-1">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 21l-6-6m2-5a7 7 0 11-14 0a7 7 0 0114 0z" />
                        </svg>
                    </div>

                    <input
                        type="text"
                        id="cariTransaksi"
                        placeholder="Cari keterangan transaksi..."
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-3 pl-9 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-amber-400 focus:bg-white focus:ring-4 focus:ring-amber-100">
                </div>

                <select
                    id="filterTipeTransaksi"
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-3 text-sm text-slate-700 outline-none transition focus:border-amber-400 focus:bg-white focus:ring-4 focus:ring-amber-100 sm:w-48">
                    <option value="">Semua Tipe</option>
                    <option value="pemasukan">Pemasukan</option>
                    <option value="pengeluaran">Pengeluaran</option>
                </select>

            </div>


            {{-- TABLE --}}
            <div class="overflow-x-auto rounded-2xl border border-slate-200">

                <table id="tabelBukuKas" class="w-full min-w-[700px] border-collapse text-left">

                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50">
                            <th class="px-4 py-3 text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">Tanggal</th>
                            <th class="px-4 py-3 text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">Transaksi</th>
                            <th class="px-4 py-3 text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">Keterangan</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">Nominal</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">Bukti</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="text-sm text-slate-700">

                        @foreach($keuangans as $item)

                        @php
                            $dapatDiedit = $isSuperAdmin
                                && is_null($item->kasbon_id)
                                && !in_array($item->kategori, ['Upah Tukang', 'Kasbon Tukang'], true)
                                && in_array($item->tipe, ['Pemasukan', 'Pengeluaran'], true);
                        @endphp

                        <tr
                            class="row-transaksi border-b border-slate-100 transition hover:bg-slate-50/70"
                            data-tipe="{{ strtolower($item->tipe) }}">

                            <td class="whitespace-nowrap px-4 py-4 font-medium text-slate-600">
                                {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                            </td>

                            <td class="px-4 py-4">
                                <div class="flex items-center gap-2.5">
                                    <span
                                        class="h-2 w-2 shrink-0 rounded-full
                                        {{ $item->tipe === 'Pemasukan' ? 'bg-emerald-500' : 'bg-slate-400' }}">
                                    </span>

                                    <div>
                                        <p class="font-semibold text-slate-700">
                                            @if($item->tipe === 'Pemasukan')
                                                Termin Klien
                                            @elseif($item->kategori === 'Kasbon Tukang')
                                                Pencairan Kasbon
                                            @elseif($item->kategori === 'Upah Tukang')
                                                Pembayaran Upah
                                            @else
                                                Biaya Lapangan
                                            @endif
                                        </p>

                                        <p class="mt-0.5 text-xs text-slate-400">
                                            {{ $item->kategori }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <td class="cell-keterangan min-w-[240px] px-4 py-4 text-slate-500">
                                {{ $item->keterangan }}
                            </td>

                            <td class="whitespace-nowrap px-4 py-4 text-right">
                                <span
                                    class="font-semibold
                                    {{ $item->tipe === 'Pemasukan' ? 'text-emerald-600' : 'text-slate-800' }}">
                                    {{ $item->tipe === 'Pemasukan' ? '+' : '-' }}
                                    Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                </span>
                            </td>

                            <td class="px-4 py-4 text-center">
                                @if($item->bukti_file)
                                <a
                                    href="{{ asset('storage/' . $item->bukti_file) }}"
                                    target="_blank"
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 transition hover:bg-blue-50 hover:text-blue-600"
                                    title="Lihat bukti">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                    </svg>
                                </a>
                                @else
                                <span class="text-slate-300">—</span>
                                @endif
                            </td>

                            <td class="whitespace-nowrap px-4 py-4 text-center">
                                @if($dapatDiedit)
                                <button
                                    type="button"
                                    onclick="bukaModalEditTransaksi(this)"
                                    data-url="{{ route('proyek.keuangan.update', [$proyek->id, $item->id]) }}"
                                    data-tipe="{{ $item->tipe }}"
                                    data-kategori="{{ $item->kategori }}"
                                    data-nominal="{{ $item->nominal }}"
                                    data-tanggal="{{ \Carbon\Carbon::parse($item->tanggal)->format('Y-m-d') }}"
                                    data-keterangan="{{ $item->keterangan }}"
                                    class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16.862 4.487l2.651 2.651M4 20l4.5-1 10.6-10.6a1.875 1.875 0 00-2.65-2.65L5.85 16.35 4 20z" />
                                    </svg>
                                    Edit
                                </button>
                                @else
                                <span
                                    class="text-xs text-slate-400"
                                    title="Transaksi upah atau kasbon tidak dapat diedit dari sini">
                                    —
                                </span>
                                @endif
                            </td>

                        </tr>

                        @endforeach

                    </tbody>
                </table>
            </div>

            <p
                id="pesanTransaksiKosong"
                class="hidden py-10 text-center text-sm text-slate-400">
                Tidak ada transaksi yang cocok dengan pencarian.
            </p>

            @else

            {{-- EMPTY STATE --}}
            <div class="flex flex-col items-center justify-center rounded-2xl border border-dashed border-slate-200 bg-slate-50/50 px-6 py-16 text-center">

                <div class="flex h-16 w-16 items-center justify-center rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <svg class="h-7 w-7 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>

                <h3 class="mt-5 text-base font-semibold text-slate-800">
                    Buku Kas Kosong
                </h3>

                <p class="mt-2 max-w-sm text-sm leading-6 text-slate-500">
                    Belum ada transaksi keuangan yang tercatat
                    untuk proyek ini.
                </p>

            </div>

            @endif

        </div>
    </section>

</div>


{{-- =========================================================
    MODALS (hanya super admin)
========================================================= --}}
@if($isSuperAdmin)


{{-- MODAL — CATAT KASBON --}}
<div
    id="modalCatatKasbon"
    data-modal="true"
    class="fixed inset-0 z-[999] hidden items-center justify-center p-4 sm:p-6"
    aria-hidden="true">

    <div class="absolute inset-0 bg-slate-950/50 backdrop-blur-sm" onclick="tutupModalKasbon()"></div>

    <div
        class="relative z-10 w-full max-w-md overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl"
        role="dialog"
        aria-modal="true">

        <div class="flex items-start justify-between gap-5 border-b border-slate-100 bg-slate-50/70 px-6 py-5">
            <div>
                <div class="flex items-center gap-2.5">
                    <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                    <h3 class="text-lg font-semibold tracking-tight text-slate-800">Catat Kasbon</h3>
                </div>

                <p class="mt-1.5 text-sm leading-5 text-slate-500">
                    Catat pencairan kasbon untuk pekerja proyek.
                </p>
            </div>

            <button
                type="button"
                onclick="tutupModalKasbon()"
                aria-label="Tutup modal"
                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-200/70 hover:text-slate-600">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form action="{{ route('kasbon.store', $proyek->id) }}" method="POST">
            @csrf

            <div class="space-y-5 p-6">

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Pekerja <span class="text-rose-500">*</span>
                    </label>

                    <select
                        name="pegawai_id"
                        required
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-3 text-sm text-slate-800 outline-none transition focus:border-amber-400 focus:ring-4 focus:ring-amber-100">
                        @foreach($proyek->pegawais as $p)
                        <option value="{{ $p->id }}">
                            {{ $p->nama }}
                            ({{ $p->jabatan->nama_jabatan ?? 'Pekerja' }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Nominal <span class="text-rose-500">*</span>
                        </label>

                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-sm font-semibold text-slate-400">Rp</span>

                            <input
                                type="number"
                                name="nominal"
                                min="1"
                                required
                                placeholder="500000"
                                class="w-full rounded-xl border border-slate-200 py-3 pl-10 pr-3.5 text-sm font-medium text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-amber-400 focus:ring-4 focus:ring-amber-100">
                        </div>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Tanggal <span class="text-rose-500">*</span>
                        </label>

                        <input
                            type="date"
                            name="tanggal"
                            value="{{ date('Y-m-d') }}"
                            required
                            class="w-full rounded-xl border border-slate-200 px-3.5 py-3 text-sm text-slate-800 outline-none transition focus:border-amber-400 focus:ring-4 focus:ring-amber-100">
                    </div>

                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Keterangan</label>

                    <input
                        type="text"
                        name="keterangan"
                        placeholder="Contoh: Kebutuhan operasional pekerja"
                        class="w-full rounded-xl border border-slate-200 px-3.5 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-amber-400 focus:ring-4 focus:ring-amber-100">
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-5">
                    <button
                        type="button"
                        onclick="tutupModalKasbon()"
                        class="rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                        Batal
                    </button>

                    <button
                        type="submit"
                        class="rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">
                        Simpan Kasbon
                    </button>
                </div>

            </div>
        </form>

    </div>
</div>


{{-- MODAL — PEMASUKAN --}}
<div
    id="modalCatatPemasukan"
    data-modal="true"
    class="fixed inset-0 z-[999] hidden items-center justify-center p-4 sm:p-6"
    aria-hidden="true">

    <div class="absolute inset-0 bg-slate-950/50 backdrop-blur-sm" onclick="tutupModalPemasukan()"></div>

    <div
        class="relative z-10 w-full max-w-md overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl"
        role="dialog"
        aria-modal="true">

        <div class="flex items-start justify-between gap-5 border-b border-slate-100 bg-slate-50/70 px-6 py-5">
            <div>
                <div class="flex items-center gap-2.5">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    <h3 class="text-lg font-semibold tracking-tight text-slate-800">Catat Termin Klien</h3>
                </div>

                <p class="mt-1.5 text-sm leading-5 text-slate-500">
                    Catat dana yang telah diterima dari klien proyek.
                </p>
            </div>

            <button
                type="button"
                onclick="tutupModalPemasukan()"
                aria-label="Tutup modal"
                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-200/70 hover:text-slate-600">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form
            action="{{ route('proyek.keuangan.store', $proyek->id) }}"
            method="POST"
            enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="tipe" value="Pemasukan">
            <input type="hidden" name="kategori" value="Termin Pembayaran">

            <div class="space-y-5 p-6">

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Nominal Termin <span class="text-rose-500">*</span>
                    </label>

                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-sm font-semibold text-slate-400">Rp</span>

                        <input
                            type="number"
                            name="nominal"
                            min="1"
                            required
                            placeholder="50000000"
                            class="w-full rounded-xl border border-slate-200 py-3 pl-10 pr-3.5 text-sm font-medium text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100">
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Tanggal Pembayaran <span class="text-rose-500">*</span>
                    </label>

                    <input
                        type="date"
                        name="tanggal"
                        value="{{ date('Y-m-d') }}"
                        required
                        class="w-full rounded-xl border border-slate-200 px-3.5 py-3 text-sm text-slate-800 outline-none transition focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Keterangan <span class="text-rose-500">*</span>
                    </label>

                    <input
                        type="text"
                        name="keterangan"
                        required
                        placeholder="Contoh: Termin 1 (30%)"
                        class="w-full rounded-xl border border-slate-200 px-3.5 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Bukti Transfer
                        <span class="font-normal text-slate-400">(Opsional)</span>
                    </label>

                    <input
                        type="file"
                        name="bukti_file"
                        accept="image/*,.pdf"
                        class="w-full cursor-pointer text-xs text-slate-500 file:mr-3 file:rounded-full file:border-0 file:bg-emerald-50 file:px-3 file:py-2 file:text-xs file:font-semibold file:text-emerald-700 hover:file:bg-emerald-100">
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-5">
                    <button
                        type="button"
                        onclick="tutupModalPemasukan()"
                        class="rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                        Batal
                    </button>

                    <button
                        type="submit"
                        class="rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">
                        Simpan Termin
                    </button>
                </div>

            </div>
        </form>

    </div>
</div>


{{-- MODAL — PENGELUARAN --}}
<div
    id="modalCatatPengeluaran"
    data-modal="true"
    class="fixed inset-0 z-[999] hidden items-center justify-center p-4 sm:p-6"
    aria-hidden="true">

    <div class="absolute inset-0 bg-slate-950/50 backdrop-blur-sm" onclick="tutupModalPengeluaran()"></div>

    <div
        class="relative z-10 w-full max-w-md overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl"
        role="dialog"
        aria-modal="true">

        <div class="flex items-start justify-between gap-5 border-b border-slate-100 bg-slate-50/70 px-6 py-5">
            <div>
                <div class="flex items-center gap-2.5">
                    <span class="h-2 w-2 rounded-full bg-slate-500"></span>
                    <h3 class="text-lg font-semibold tracking-tight text-slate-800">Catat Biaya Lapangan</h3>
                </div>

                <p class="mt-1.5 text-sm leading-5 text-slate-500">
                    Catat pengeluaran operasional yang terjadi di lapangan.
                </p>
            </div>

            <button
                type="button"
                onclick="tutupModalPengeluaran()"
                aria-label="Tutup modal"
                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-200/70 hover:text-slate-600">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form
            action="{{ route('proyek.keuangan.store', $proyek->id) }}"
            method="POST"
            enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="tipe" value="Pengeluaran">

            <div class="space-y-5 p-6">

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Kategori <span class="text-rose-500">*</span>
                        </label>

                        <select
                            name="kategori"
                            required
                            class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-3 text-sm text-slate-800 outline-none transition focus:border-slate-400 focus:ring-4 focus:ring-slate-100">
                            <option value="Material & Bahan">Material & Bahan</option>
                            <option value="Sewa Alat">Sewa Alat</option>
                            <option value="Operasional Lapangan">Operasional (BBM, Makan)</option>
                            <option value="Lain-lain">Lain-lain</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Tanggal <span class="text-rose-500">*</span>
                        </label>

                        <input
                            type="date"
                            name="tanggal"
                            value="{{ date('Y-m-d') }}"
                            required
                            class="w-full rounded-xl border border-slate-200 px-3.5 py-3 text-sm text-slate-800 outline-none transition focus:border-slate-400 focus:ring-4 focus:ring-slate-100">
                    </div>

                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Nominal <span class="text-rose-500">*</span>
                    </label>

                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-sm font-semibold text-slate-400">Rp</span>

                        <input
                            type="number"
                            name="nominal"
                            min="1"
                            required
                            placeholder="1500000"
                            class="w-full rounded-xl border border-slate-200 py-3 pl-10 pr-3.5 text-sm font-medium text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-slate-400 focus:ring-4 focus:ring-slate-100">
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Keterangan <span class="text-rose-500">*</span>
                    </label>

                    <input
                        type="text"
                        name="keterangan"
                        required
                        placeholder="Contoh: Beli semen 50 sak"
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-slate-400 focus:ring-4 focus:ring-slate-100">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Foto Nota / Kwitansi
                        <span class="font-normal text-slate-400">(Opsional)</span>
                    </label>

                    <input
                        type="file"
                        name="bukti_file"
                        accept="image/*,.pdf"
                        class="w-full cursor-pointer text-xs text-slate-500 file:mr-3 file:rounded-full file:border-0 file:bg-slate-100 file:px-3 file:py-2 file:text-xs file:font-semibold file:text-slate-700 hover:file:bg-slate-200">
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-5">
                    <button
                        type="button"
                        onclick="tutupModalPengeluaran()"
                        class="rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                        Batal
                    </button>

                    <button
                        type="submit"
                        class="rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">
                        Simpan Pengeluaran
                    </button>
                </div>

            </div>
        </form>

    </div>
</div>


{{-- MODAL — BAYAR UPAH --}}
<div
    id="modalGaji"
    data-modal="true"
    class="fixed inset-0 z-[999] hidden items-center justify-center p-4 sm:p-6"
    aria-hidden="true">

    {{-- BACKDROP --}}
    <div class="absolute inset-0 bg-slate-950/50 backdrop-blur-sm" onclick="tutupModalGaji()"></div>

    {{-- MODAL CONTENT --}}
    <div
        class="relative z-10 flex max-h-[92vh] w-full max-w-lg flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl"
        role="dialog"
        aria-modal="true"
        aria-labelledby="judulModalGaji">

        {{-- HEADER --}}
        <div class="flex shrink-0 items-start justify-between gap-5 border-b border-slate-100 bg-slate-50/70 px-6 py-5">
            <div>
                <div class="flex items-center gap-2.5">
                    <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                    <h3 id="judulModalGaji" class="text-lg font-semibold tracking-tight text-slate-800">
                        Hitung & Bayar Upah
                    </h3>
                </div>

                <p class="mt-1.5 text-sm leading-5 text-slate-500">
                    Hitung hak upah berdasarkan kehadiran dan kasbon pekerja.
                </p>
            </div>

            <button
                type="button"
                onclick="tutupModalGaji()"
                aria-label="Tutup modal"
                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-200/70 hover:text-slate-600">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- BODY --}}
        <div class="min-h-0 flex-1 overflow-y-auto p-5 sm:p-6">

            {{-- STEP 1 — HITUNG GAJI --}}
            <form id="formCekGaji" class="space-y-5">
                @csrf

                <div>
                    <label for="inputPegawaiGaji" class="mb-2 block text-sm font-semibold text-slate-700">
                        Pekerja <span class="text-rose-500">*</span>
                    </label>

                    <select
                        id="inputPegawaiGaji"
                        name="pegawai_id"
                        required
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-3 text-sm text-slate-800 outline-none transition focus:border-blue-400 focus:ring-4 focus:ring-blue-100">
                        @foreach($proyek->pegawais as $p)
                        <option value="{{ $p->id }}">
                            {{ $p->nama }}
                            ({{ $p->jabatan->nama_jabatan ?? '-' }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <label for="inputStartGaji" class="mb-2 block text-sm font-semibold text-slate-700">
                            Dari Tanggal <span class="text-rose-500">*</span>
                        </label>

                        <input
                            type="date"
                            id="inputStartGaji"
                            name="start_date"
                            required
                            class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-3 text-sm text-slate-800 outline-none transition focus:border-blue-400 focus:ring-4 focus:ring-blue-100">
                    </div>

                    <div>
                        <label for="inputEndGaji" class="mb-2 block text-sm font-semibold text-slate-700">
                            Sampai Tanggal <span class="text-rose-500">*</span>
                        </label>

                        <input
                            type="date"
                            id="inputEndGaji"
                            name="end_date"
                            value="{{ date('Y-m-d') }}"
                            required
                            class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-3 text-sm text-slate-800 outline-none transition focus:border-blue-400 focus:ring-4 focus:ring-blue-100">
                    </div>
                </div>

                <button
                    type="submit"
                    id="btnCekGaji"
                    class="w-full rounded-xl bg-slate-900 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-60">
                    Tarik Data Kehadiran & Kasbon
                </button>
            </form>


            {{-- STEP 2 — HASIL & PEMBAYARAN --}}
            <div id="areaHasilGaji" class="mt-7 hidden border-t border-slate-100 pt-6">

                <div class="mb-5 flex items-center gap-2.5">
                    <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                    <h4 class="text-sm font-semibold text-slate-800">Rincian Slip Gaji</h4>
                </div>

                {{-- RINCIAN GAJI --}}
                <div class="mb-5 rounded-2xl border border-slate-200 bg-slate-50/70 p-5">
                    <div class="mb-3 flex justify-between gap-4">
                        <span class="text-sm text-slate-500">Hadir Penuh</span>
                        <span class="text-sm font-semibold text-slate-800">
                            <span id="txtHariFull">0</span> Hari
                        </span>
                    </div>

                    <div class="mb-3 flex justify-between gap-4">
                        <span class="text-sm text-slate-500">Setengah Hari</span>
                        <span class="text-sm font-semibold text-slate-800">
                            <span id="txtHariSetengah">0</span> Hari
                        </span>
                    </div>

                    <div class="flex justify-between gap-4 border-t border-slate-200 pt-3">
                        <span class="text-sm font-semibold text-slate-700">Total Upah Kotor</span>
                        <span id="txtUpahKotor" class="text-sm font-semibold text-slate-900">Rp 0</span>
                    </div>

                    <div class="mt-3 flex justify-between gap-4 text-rose-600">
                        <span class="text-sm">Potongan Kasbon Lama</span>
                        <span id="txtPotonganKasbon" class="text-sm font-semibold">- Rp 0</span>
                    </div>
                </div>

                {{-- FORM PEMBAYARAN FINAL (bukti file harus berada di dalam form ini) --}}
                <form
                    id="formBayarGaji"
                    action="{{ route('proyek.keuangan.bayar-gaji', $proyek->id) }}"
                    method="POST"
                    enctype="multipart/form-data"
                    class="space-y-5">
                    @csrf

                    <input type="hidden" name="pegawai_id" id="finalPegawaiId">
                    <input type="hidden" name="start_date" id="finalStartDate">
                    <input type="hidden" name="end_date" id="finalEndDate">
                    <input type="hidden" name="estimasi_gaji_sistem" id="finalEstimasiSistem">
                    <input type="hidden" name="id_absensi" id="finalIdAbsensi">

                    {{-- TOTAL GAJI BERSIH --}}
                    <div class="rounded-2xl border border-blue-100 bg-blue-50/60 p-5">
                        <div class="flex items-center justify-between gap-4">
                            <span class="text-xs font-semibold uppercase tracking-[0.08em] text-blue-700">
                                Hak Gaji Bersih
                            </span>

                            <span id="txtGajiBersih" class="text-xl font-semibold tracking-tight text-blue-700">
                                Rp 0
                            </span>
                        </div>

                        <div class="mt-5">
                            <label for="inputNominalDibayar" class="mb-2 block text-sm font-semibold text-slate-700">
                                Nominal Uang yang Dibayar <span class="text-rose-500">*</span>
                            </label>

                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-sm font-semibold text-slate-400">Rp</span>

                                <input
                                    type="number"
                                    id="inputNominalDibayar"
                                    name="nominal"
                                    min="0"
                                    step="1"
                                    required
                                    class="w-full rounded-xl border border-slate-200 bg-white py-3 pl-10 pr-3.5 text-sm font-semibold text-slate-800 outline-none transition focus:border-blue-400 focus:ring-4 focus:ring-blue-100">
                            </div>

                            <p class="mt-2 text-xs leading-5 text-slate-500">
                                Pembayaran yang melebihi hak gaji akan dicatat
                                sebagai kasbon baru secara otomatis.
                            </p>
                        </div>
                    </div>

                    {{-- BUKTI PEMBAYARAN --}}
                    <div>
                        <label for="inputBuktiFileGaji" class="mb-2 block text-sm font-semibold text-slate-700">
                            Bukti Pembayaran <span class="text-rose-500">*</span>
                        </label>

                        <p class="mb-3 text-xs leading-5 text-slate-500">
                            Unggah bukti transfer atau pembayaran sebelum
                            mengonfirmasi transaksi.
                        </p>

                        <label
                            for="inputBuktiFileGaji"
                            class="flex cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50 px-5 py-6 text-center transition hover:border-blue-400 hover:bg-blue-50/50">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="mb-3 h-8 w-8 text-slate-400"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 16V4m0 0L7 9m5-5 5 5M4 16.5v2A1.5 1.5 0 005.5 20h13a1.5 1.5 0 001.5-1.5v-2" />
                            </svg>

                            <span class="text-sm font-semibold text-slate-700">Pilih file bukti pembayaran</span>
                            <span class="mt-1 text-xs text-slate-500">JPG, JPEG, PNG, atau PDF</span>

                            <input
                                type="file"
                                id="inputBuktiFileGaji"
                                name="bukti_file"
                                accept=".jpg,.jpeg,.png,.pdf"
                                required
                                class="sr-only">
                        </label>

                        {{-- NAMA FILE --}}
                        <div
                            id="namaBuktiFileGaji"
                            class="mt-3 hidden items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-3">
                            <svg class="h-5 w-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 13l4 4L19 7" />
                            </svg>

                            <span id="teksNamaBuktiFileGaji" class="min-w-0 flex-1 break-all text-sm text-slate-600"></span>
                        </div>

                        {{-- PRATINJAU GAMBAR --}}
                        <div id="pratinjauBuktiGaji" class="mt-3 hidden">
                            <p class="mb-2 text-xs font-medium text-slate-500">Pratinjau bukti</p>

                            <img
                                id="gambarBuktiGaji"
                                src=""
                                alt="Pratinjau bukti pembayaran"
                                class="max-h-64 w-full rounded-xl border border-slate-200 bg-slate-50 object-contain">
                        </div>
                    </div>

                    {{-- TOMBOL KONFIRMASI --}}
                    <div class="border-t border-slate-100 pt-5">
                        <button
                            type="submit"
                            id="btnKonfirmasiGaji"
                            class="w-full rounded-xl bg-slate-900 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-60">
                            Konfirmasi Pembayaran
                        </button>

                        <p class="mt-3 text-center text-xs leading-5 text-slate-500">
                            Pastikan nominal dan bukti pembayaran sudah benar
                            sebelum melanjutkan.
                        </p>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>


{{-- MODAL — EDIT TRANSAKSI --}}
<div
    id="modalEditTransaksi"
    data-modal="true"
    class="fixed inset-0 z-[1000] hidden items-center justify-center p-4 sm:p-6"
    aria-hidden="true">

    <div class="absolute inset-0 bg-slate-950/50 backdrop-blur-sm" onclick="tutupModalEditTransaksi()"></div>

    <div
        class="relative z-10 max-h-[92vh] w-full max-w-lg overflow-y-auto rounded-2xl border border-slate-200 bg-white shadow-2xl"
        role="dialog"
        aria-modal="true"
        aria-labelledby="judulModalEditTransaksi">

        <div class="flex items-start justify-between gap-4 border-b border-slate-100 bg-slate-50/70 px-6 py-5">
            <div>
                <div class="flex items-center gap-2.5">
                    <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                    <h3 id="judulModalEditTransaksi" class="text-lg font-semibold tracking-tight text-slate-800">
                        Edit Transaksi
                    </h3>
                </div>

                <p class="mt-1.5 text-sm leading-5 text-slate-500">
                    Perbarui data transaksi buku kas proyek.
                </p>
            </div>

            <button
                type="button"
                onclick="tutupModalEditTransaksi()"
                aria-label="Tutup modal"
                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-200/70 hover:text-slate-600">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form
            id="formEditTransaksi"
            action="#"
            method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-5 p-6">

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Jenis Transaksi</label>

                    <input
                        type="text"
                        id="editTipeTransaksi"
                        readonly
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-3 text-sm font-semibold text-slate-500">

                    <p class="mt-1.5 text-xs text-slate-400">
                        Jenis transaksi tidak dapat diubah melalui fitur edit.
                    </p>
                </div>
                
{{-- Kategori transaksi --}}
<div id="wrapperKategoriTransaksi">
    <label
        for="editKategoriTransaksi"
        class="mb-2 block text-sm font-semibold text-slate-700">
        Jenis Pengeluaran
    </label>

    <select
        id="editKategoriTransaksi"
        name="kategori"
        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">

        <option value="">Pilih jenis pengeluaran</option>
        <option value="Material & Bahan">Material & Bahan</option>
        <option value="Sewa Alat">Sewa Alat</option>
        <option value="Operasional Lapangan">Operasional Lapangan</option>
        <option value="Lain-lain">Lain-lain</option>
    </select>
</div>

                <div>
                    <label for="editNominalTransaksi" class="mb-2 block text-sm font-semibold text-slate-700">
                        Nominal <span class="text-rose-500">*</span>
                    </label>

                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-sm font-semibold text-slate-400">Rp</span>

                        <input
                            type="number"
                            id="editNominalTransaksi"
                            name="nominal"
                            min="1"
                            step="1"
                            required
                            class="w-full rounded-xl border border-slate-200 py-3 pl-10 pr-3.5 text-sm font-semibold text-slate-800 outline-none transition focus:border-blue-400 focus:ring-4 focus:ring-blue-100">
                    </div>
                </div>

                <div>
                    <label for="editTanggalTransaksi" class="mb-2 block text-sm font-semibold text-slate-700">
                        Tanggal Transaksi <span class="text-rose-500">*</span>
                    </label>

                    <input
                        type="date"
                        id="editTanggalTransaksi"
                        name="tanggal"
                        min="{{ \Carbon\Carbon::parse($proyek->tanggal_mulai)->format('Y-m-d') }}"
                        max="{{ now()->toDateString() }}"
                        required
                        class="w-full rounded-xl border border-slate-200 px-3.5 py-3 text-sm text-slate-800 outline-none transition focus:border-blue-400 focus:ring-4 focus:ring-blue-100">
                </div>

                <div>
                    <label for="editKeteranganTransaksi" class="mb-2 block text-sm font-semibold text-slate-700">
                        Keterangan
                    </label>

                    <textarea
                        id="editKeteranganTransaksi"
                        name="keterangan"
                        rows="3"
                        maxlength="1000"
                        class="w-full resize-y rounded-xl border border-slate-200 px-3.5 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-blue-400 focus:ring-4 focus:ring-blue-100"
                        placeholder="Keterangan transaksi..."></textarea>
                </div>

                <div>
                    <label for="editBuktiTransaksi" class="mb-2 block text-sm font-semibold text-slate-700">
                        Bukti Transaksi
                        <span class="font-normal text-slate-400">(Opsional)</span>
                    </label>

                    <input
                        type="file"
                        id="editBuktiTransaksi"
                        name="bukti_file"
                        accept=".jpg,.jpeg,.png,.pdf"
                        class="w-full cursor-pointer text-xs text-slate-500 file:mr-3 file:rounded-full file:border-0 file:bg-blue-50 file:px-3 file:py-2 file:text-xs file:font-semibold file:text-blue-700 hover:file:bg-blue-100">

                    <p class="mt-2 text-xs leading-5 text-slate-500">
                        Kosongkan jika ingin mempertahankan bukti yang lama.
                        Format JPG, JPEG, PNG, atau PDF; maksimal 5 MB.
                    </p>
                </div>

                <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
                    <button
                        type="button"
                        onclick="tutupModalEditTransaksi()"
                        class="rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                        Batal
                    </button>

                    <button
                        type="submit"
                        class="rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">
                        Simpan Perubahan
                    </button>
                </div>

            </div>
        </form>

    </div>
</div>

@endif
{{-- ========== AKHIR @if($isSuperAdmin) UNTUK MODAL ========== --}}


{{-- =========================================================
    PAGE JAVASCRIPT
    Dimuat untuk semua admin agar filter Buku Kas dan notifikasi
    tetap berfungsi. Elemen modal yang tidak ada akan diabaikan.
========================================================= --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    window.manajemenDanaProyekConfig = {
        proyekId: @json($proyek->id),
        previewGajiUrl: @json(url('/admin/proyek/' . $proyek->id . '/keuangan/preview-gaji')),
        hasValidationErrors: @json($errors->any()),
        validationErrors: @json($errors->all()),
        successMessage: @json(session('success')),
        errorMessage: @json(session('error')),
    };
</script>

<script src="{{ asset('js/manajemen-dana-proyek.js') }}"></script>

@endsection