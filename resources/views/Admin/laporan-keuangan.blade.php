@extends('layouts.admin')

@section('title', 'Manajemen Dana Proyek - ' . $proyek->nama_proyek)

@section('content')

{{-- =========================================================
    HERO — MANAJEMEN DANA PROYEK
========================================================= --}}
<section
    class="relative isolate overflow-hidden rounded-[30px] bg-slate-950 text-white shadow-xl shadow-slate-200/50 mb-6">

    {{-- Background Project Image --}}
    @if($proyek->gambar)
    <div
        class="absolute inset-0 -z-30 bg-cover bg-center opacity-[0.24]"
        style="background-image: url('{{ asset('storage/' . $proyek->gambar) }}');"></div>
    @else
    <div class="absolute inset-0 -z-30 bg-slate-950"></div>
    @endif

    {{-- Main Gradient --}}
    <div
        class="absolute inset-0 -z-20 bg-gradient-to-br from-slate-950 via-slate-950/[0.96] to-blue-950/[0.92]"></div>

    {{-- Financial Green Glow --}}
    <div
        class="absolute -right-24 -top-24 -z-10 h-80 w-80 rounded-full bg-emerald-500/10 blur-3xl"></div>

    <div
        class="absolute -bottom-32 left-1/3 -z-10 h-80 w-80 rounded-full bg-blue-500/10 blur-3xl"></div>

    {{-- Subtle Grid --}}
    <div
        class="absolute inset-0 -z-10 opacity-[0.035]"
        style="
            background-image:
                linear-gradient(rgba(255,255,255,.8) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.8) 1px, transparent 1px);
            background-size: 32px 32px;
        "></div>

    {{-- =====================================================
        CONTENT
    ====================================================== --}}
    <div class="relative px-6 py-7 sm:px-8 sm:py-8 lg:px-10 lg:py-9">

        {{-- TOP NAVIGATION --}}
        <div class="flex flex-wrap items-center justify-between gap-4 mb-8">

            {{-- Back --}}
            <a
                href="{{ route('proyek.show', $proyek->id) }}"
                class="group inline-flex items-center gap-2.5 text-[11px] font-medium text-slate-300 transition-colors hover:text-white">
                <span
                    class="flex h-8 w-8 items-center justify-center rounded-xl border border-white/10 bg-white/[0.06] backdrop-blur-md transition-all group-hover:bg-white/[0.10]">
                    <svg
                        class="h-4 w-4 transition-transform duration-300 group-hover:-translate-x-0.5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </span>

                Kembali ke Detail Proyek
            </a>

            {{-- Page Label --}}
            <div
                class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/[0.06] px-3 py-1.5 backdrop-blur-md">
                <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>

                <span
                    class="text-[9px] font-medium uppercase tracking-[0.18em] text-slate-300">
                    Manajemen Keuangan
                </span>
            </div>

        </div>


        {{-- =====================================================
            TITLE
        ====================================================== --}}
        <div class="max-w-4xl">

            <div class="flex flex-wrap items-center gap-3 mb-3">

                <span
                    class="inline-flex items-center gap-2 rounded-full border border-emerald-300/10 bg-emerald-400/[0.08] px-3 py-1 text-[9px] font-medium uppercase tracking-[0.16em] text-emerald-200">
                    <svg
                        class="h-3 w-3"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.7"
                        viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 8c-3.314 0-6 1.343-6 3s2.686 3 6 3 6-1.343 6-3-2.686-3-6-3Z" />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M6 11v3c0 1.657 2.686 3 6 3s6-1.343 6-3v-3" />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M6 14v3c0 1.657 2.686 3 6 3s6-1.343 6-3v-3" />
                    </svg>

                    Keuangan Proyek
                </span>

            </div>

            <h1
                class="text-[30px] font-semibold leading-[1.08] tracking-[-0.035em] text-white sm:text-[34px] lg:text-[38px]">
                Manajemen Dana
            </h1>

            <div class="mt-3 flex flex-wrap items-center gap-x-4 gap-y-2">

                <p class="flex items-center text-[12px] text-slate-300 sm:text-[13px]">
                    <svg
                        class="mr-1.5 h-4 w-4 shrink-0 text-slate-500"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.7"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.7"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>

                    {{ $proyek->lokasi }}
                </p>

                <span class="hidden h-1 w-1 rounded-full bg-slate-700 sm:block"></span>

                <p class="text-[12px] font-medium text-slate-400 sm:text-[13px]">
                    {{ $proyek->nama_proyek }}
                </p>

            </div>

        </div>


        {{-- =====================================================
            FINANCIAL METRICS
        ====================================================== --}}
        <div class="mt-8 grid grid-cols-1 gap-3 sm:grid-cols-3">

            {{-- TOTAL PEMASUKAN --}}
            <div
                class="rounded-2xl border border-white/10 bg-white/[0.065] p-4 backdrop-blur-md transition duration-300 hover:bg-white/[0.09]">
                <div class="flex items-center justify-between">

                    <p
                        class="text-[9px] font-medium uppercase tracking-[0.13em] text-slate-400">
                        Total Termin Masuk
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
                                d="M12 5v14m7-7H5" />
                        </svg>
                    </div>

                </div>

                <p
                    class="mt-3 text-[19px] font-semibold tracking-[-0.03em] text-emerald-300">
                    Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
                </p>

                <p class="mt-1 text-[10px] text-slate-500">
                    Dana yang telah diterima dari klien
                </p>
            </div>


            {{-- TOTAL PENGELUARAN --}}
            <div
                class="rounded-2xl border border-white/10 bg-white/[0.065] p-4 backdrop-blur-md transition duration-300 hover:bg-white/[0.09]">
                <div class="flex items-center justify-between">

                    <p
                        class="text-[9px] font-medium uppercase tracking-[0.13em] text-slate-400">
                        Total Pengeluaran
                    </p>

                    <div
                        class="flex h-7 w-7 items-center justify-center rounded-lg border border-white/10 bg-white/[0.06]">
                        <svg
                            class="h-3.5 w-3.5 text-slate-300"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.7"
                            viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M5 12h14" />
                        </svg>
                    </div>

                </div>

                <p
                    class="mt-3 text-[19px] font-semibold tracking-[-0.03em] text-white">
                    Rp {{ number_format($totalPengeluaran ?? ($totalPemasukan - $kasTersedia), 0, ',', '.') }}
                </p>

                <p class="mt-1 text-[10px] text-slate-500">
                    Material, operasional, upah & kasbon
                </p>
            </div>


            {{-- KAS TERSEDIA --}}
            <div
                class="rounded-2xl border border-white/10 bg-white/[0.065] p-4 backdrop-blur-md transition duration-300 hover:bg-white/[0.09]">

                <div class="flex items-center justify-between gap-3">

                    <p
                        class="text-[9px] font-medium uppercase tracking-[0.13em] text-slate-400">
                        Dana Kas Tersedia
                    </p>

                    <span
                        class="rounded-full border border-white/10 bg-white/[0.06] px-2 py-0.5 text-[9px] font-medium {{ $statusColor }}">
                        {{ $statusText }}
                    </span>

                </div>

                <p
                    class="mt-3 text-[19px] font-semibold tracking-[-0.03em] {{ $kasTersedia < 0 ? 'text-rose-300' : 'text-emerald-300' }}">
                    {{ $kasTersedia < 0 ? '-' : '' }}Rp {{ number_format(abs($kasTersedia), 0, ',', '.') }}
                </p>

                <div class="mt-3">

                    <div class="mb-1 flex items-center justify-between">
                        <span class="text-[9px] text-slate-500">
                            Pemakaian dana
                        </span>

                        <span class="text-[9px] font-medium text-slate-400">
                            {{ number_format($persentaseKasTerpakai, 1, ',', '.') }}%
                        </span>
                    </div>

                    <div class="h-1 overflow-hidden rounded-full bg-white/10">
                        <div
                            class="{{ $progressColor }} h-1 rounded-full transition-all duration-500"
                            style="width: {{ $persentaseKasTerpakai > 100 ? 100 : $persentaseKasTerpakai }}%;"></div>
                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            ACTION BUTTONS
        ====================================================== --}}
        @if(auth()->check() && auth()->user()->role === 'super_admin')

        <div
            class="mt-6 flex flex-wrap items-center gap-2 border-t border-white/10 pt-5">

            <span class="mr-1 text-[9px] font-medium uppercase tracking-[0.13em] text-slate-500">
                Aksi Keuangan
            </span>

            <div class="flex flex-wrap items-center gap-2">

                {{-- Termin Klien --}}
                <button
                    type="button"
                    onclick="toggleModal('modalCatatPemasukan')"
                    class="inline-flex items-center gap-2 rounded-xl
               bg-emerald-500 px-3.5 py-2.5
               text-[11px] font-semibold text-white
               shadow-lg shadow-emerald-950/20
               transition-all duration-200
               hover:bg-emerald-400 hover:-translate-y-0.5
               active:translate-y-0">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M12 6v12m6-6H6" />
                    </svg>
                    Termin Klien
                </button>

                {{-- Biaya Lapangan --}}
                <button
                    type="button"
                    onclick="toggleModal('modalCatatPengeluaran')"
                    class="inline-flex items-center gap-2 rounded-xl
               border border-white/15
               bg-white/10 px-3.5 py-2.5
               text-[11px] font-semibold text-white
               backdrop-blur-sm
               transition-all duration-200
               hover:bg-white/15 hover:border-white/25
               hover:-translate-y-0.5">
                    <svg class="h-4 w-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M12 6v12m6-6H6" />
                    </svg>
                    Biaya Lapangan
                </button>

                {{-- Kasbon --}}
                <button
                    type="button"
                    onclick="toggleModal('modalCatatKasbon')"
                    class="inline-flex items-center gap-2 rounded-xl
               border border-white/15
               bg-white/10 px-3.5 py-2.5
               text-[11px] font-semibold text-white
               backdrop-blur-sm
               transition-all duration-200
               hover:bg-amber-500/15
               hover:border-amber-400/30
               hover:-translate-y-0.5">
                    <svg class="h-4 w-4 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M12 6v12m6-6H6" />
                    </svg>
                    Catat Kasbon
                </button>

                {{-- Bayar Upah --}}
                <button
                    type="button"
                    onclick="toggleModal('modalGaji')"
                    class="inline-flex items-center gap-2 rounded-xl
               border border-white/15
               bg-white/10 px-3.5 py-2.5
               text-[11px] font-semibold text-white
               backdrop-blur-sm
               transition-all duration-200
               hover:bg-blue-500/15
               hover:border-blue-400/30
               hover:-translate-y-0.5">
                    <svg class="h-4 w-4 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M12 6v12m6-6H6" />
                    </svg>
                    Bayar Upah
                </button>

                {{-- Rincian Upah --}}
                <a
                    href="{{ route('proyek.payroll', $proyek->id) }}"
                    class="inline-flex items-center gap-2 rounded-xl
               border border-white/15
               bg-white/10 px-3.5 py-2.5
               text-[11px] font-semibold text-white
               backdrop-blur-sm
               transition-all duration-200
               hover:bg-white/15 hover:border-white/25
               hover:-translate-y-0.5">
                    <svg class="h-4 w-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M9 5l7 7-7 7" />
                    </svg>
                    Rincian Upah
                </a>

            </div>

        </div>

        @endif

    </div>

</section>


{{-- =========================================================
    BUKU KAS PROYEK
========================================================= --}}
<section
    class="rounded-[24px] border border-slate-200 bg-white p-5 shadow-sm sm:p-6 lg:p-7">

    {{-- HEADER SECTION --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>

            <div class="flex items-center gap-2">

                <div
                    class="flex h-8 w-8 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                    <svg
                        class="h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.7"
                        viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M4 19.5A2.5 2.5 0 016.5 17H20" />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2Z" />
                    </svg>
                </div>

                <h2 class="text-[15px] font-semibold tracking-[-0.01em] text-slate-900">
                    Buku Kas Proyek
                </h2>

            </div>

            <p class="mt-1.5 text-[12px] leading-5 text-slate-500">
                Riwayat pemasukan termin klien dan pengeluaran biaya proyek.
            </p>

        </div>

        <a
            href="{{ route('keuangan.pdf', $proyek->id) }}"
            target="_blank"
            class="inline-flex shrink-0 items-center justify-center gap-1.5 rounded-xl bg-slate-900 px-4 py-2.5 text-[11px] font-semibold text-white shadow-sm transition-all hover:bg-slate-800">
            <svg
                class="h-4 w-4"
                fill="none"
                stroke="currentColor"
                stroke-width="1.7"
                viewBox="0 0 24 24">
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>

            Unduh PDF
        </a>

    </div>


    @if($transaksi->count() > 0)

    {{-- FILTER --}}
    <div class="mb-5 flex flex-col gap-3 sm:flex-row">

        <div class="relative flex-1">

            <div
                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                <svg
                    class="h-4 w-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <input
                type="text"
                id="cariTransaksi"
                onkeyup="filterBukuKas()"
                placeholder="Cari keterangan transaksi..."
                class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-9 pr-4 text-[12px] text-slate-800 placeholder-slate-400 outline-none transition-all focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10">

        </div>

        <div class="w-full sm:w-48">

            <select
                id="filterTipeTransaksi"
                onchange="filterBukuKas()"
                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-[12px] text-slate-700 outline-none transition-all focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10">
                <option value="">Semua Tipe</option>
                <option value="pemasukan">Pemasukan</option>
                <option value="pengeluaran">Pengeluaran</option>
            </select>

        </div>

    </div>


    {{-- TABLE --}}
    <div class="overflow-x-auto rounded-2xl border border-slate-200">

        <table
            class="w-full min-w-[650px] border-collapse text-left"
            id="tabelBukuKas">

            <thead>

                <tr class="border-b border-slate-200 bg-slate-50">

                    <th class="px-4 py-3 text-[10px] font-semibold uppercase tracking-[0.08em] text-slate-500">
                        Tanggal
                    </th>

                    <th class="px-4 py-3 text-[10px] font-semibold uppercase tracking-[0.08em] text-slate-500">
                        Tipe Transaksi
                    </th>

                    <th class="px-4 py-3 text-[10px] font-semibold uppercase tracking-[0.08em] text-slate-500">
                        Keterangan
                    </th>

                    <th class="px-4 py-3 text-right text-[10px] font-semibold uppercase tracking-[0.08em] text-slate-500">
                        Nominal
                    </th>

                    <th class="px-4 py-3 text-center text-[10px] font-semibold uppercase tracking-[0.08em] text-slate-500">
                        Bukti
                    </th>

                </tr>

            </thead>


            <tbody class="text-[12px] text-slate-700">

                @foreach($transaksi as $item)

                <tr
                    class="row-transaksi border-b border-slate-100 transition-colors hover:bg-slate-50/70"
                    data-tipe="{{ strtolower($item->tipe) }}">

                    {{-- TANGGAL --}}
                    <td class="whitespace-nowrap px-4 py-3.5 font-medium text-slate-700">
                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}
                    </td>


                    {{-- TIPE --}}
                    <td class="px-4 py-3.5">

                        <div class="flex items-center gap-2">

                            <span
                                class="h-1.5 w-1.5 rounded-full {{ $item->tipe == 'Pemasukan' ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>

                            <span class="font-medium text-slate-700">

                                @if($item->tipe == 'Pemasukan')
                                Termin Klien
                                @elseif($item->kategori == 'Kasbon Tukang')
                                Pencairan Kasbon
                                @elseif($item->kategori == 'Upah Tukang')
                                Pembayaran Gaji
                                @else
                                Biaya Lapangan
                                @endif

                            </span>

                        </div>

                        <p class="ml-3.5 mt-0.5 text-[10px] text-slate-400">
                            {{ $item->kategori }}
                        </p>

                    </td>


                    {{-- KETERANGAN --}}
                    <td class="cell-keterangan min-w-[220px] px-4 py-3.5 text-slate-600">
                        {{ $item->keterangan }}
                    </td>


                    {{-- NOMINAL --}}
                    <td class="whitespace-nowrap px-4 py-3.5 text-right">

                        <span
                            class="font-semibold {{ $item->tipe == 'Pemasukan' ? 'text-emerald-600' : 'text-slate-800' }}">
                            {{ $item->tipe == 'Pengeluaran' ? '-' : '+' }}
                            Rp {{ number_format($item->nominal, 0, ',', '.') }}
                        </span>

                    </td>


                    {{-- BUKTI --}}
                    <td class="px-4 py-3.5 text-center">

                        @if($item->bukti_file)

                        <a
                            href="{{ asset('storage/' . $item->bukti_file) }}"
                            target="_blank"
                            class="inline-flex rounded-lg p-1.5 text-slate-400 transition-colors hover:bg-blue-50 hover:text-blue-600"
                            title="Lihat Bukti">
                            <svg
                                class="h-4 w-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                            </svg>
                        </a>

                        @else

                        <span class="text-slate-300">-</span>

                        @endif

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>


    <p
        id="pesanTransaksiKosong"
        class="hidden py-8 text-center text-[12px] text-slate-400">
        Tidak ada transaksi yang cocok dengan pencarian.
    </p>


    @else

    {{-- EMPTY STATE --}}
    <div class="py-12 text-center">

        <div
            class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-50 text-slate-300">
            <svg
                class="h-7 w-7"
                fill="none"
                stroke="currentColor"
                stroke-width="1.5"
                viewBox="0 0 24 24">
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M20 12V8H6a2.5 2.5 0 010-5h12v4" />
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M4 6v13a2 2 0 002 2h12a2 2 0 002-2V8" />
            </svg>
        </div>

        <p class="text-[13px] font-semibold text-slate-600">
            Buku Kas Kosong
        </p>

        <p class="mx-auto mt-1 max-w-md text-[11px] leading-5 text-slate-400">
            Mulai catat arus kas proyek dengan menggunakan aksi keuangan
            pada bagian atas halaman.
        </p>

    </div>

    @endif

</section>


{{-- =========================================================
    MODAL — CATAT KASBON
========================================================= --}}
<div
    id="modalCatatKasbon"
    class="fixed inset-0 z-50 hidden flex h-full w-full items-center justify-center overflow-y-auto bg-slate-900/50 p-4 backdrop-blur-sm">

    <div class="my-8 w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">

        <h3 class="mb-5 text-[17px] font-semibold tracking-tight text-slate-900">
            Catat Kasbon Pekerja
        </h3>

        <form action="{{ route('kasbon.store', $proyek->id) }}" method="POST">
            @csrf

            <div class="mb-4">

                <label class="mb-1.5 block text-[10px] font-semibold uppercase tracking-[0.08em] text-slate-500">
                    Pilih Pekerja
                </label>

                <select
                    name="pegawai_id"
                    class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-[13px] text-slate-900 outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/10"
                    required>

                    @foreach($proyek->pegawais as $p)

                    <option value="{{ $p->id }}">
                        {{ $p->nama }}
                        ({{ $p->jabatan->nama_jabatan ?? 'Pekerja' }})
                    </option>

                    @endforeach

                </select>

            </div>


            <div class="mb-4 grid grid-cols-1 gap-4 sm:grid-cols-2">

                <div>

                    <label class="mb-1.5 block text-[10px] font-semibold uppercase tracking-[0.08em] text-slate-500">
                        Nominal (Rp)
                    </label>

                    <div class="relative">

                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm font-semibold text-slate-400">
                            Rp
                        </span>

                        <input
                            type="number"
                            name="nominal"
                            class="w-full rounded-xl border border-slate-300 py-2.5 pl-9 pr-3 text-[14px] font-semibold text-slate-900 outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/10"
                            placeholder="50.000"
                            required>

                    </div>

                </div>


                <div>

                    <label class="mb-1.5 block text-[10px] font-semibold uppercase tracking-[0.08em] text-slate-500">
                        Tanggal
                    </label>

                    <input
                        type="date"
                        name="tanggal"
                        value="{{ date('Y-m-d') }}"
                        class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-[13px] text-slate-900 outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/10"
                        required>

                </div>

            </div>


            <div class="mb-6">

                <label class="mb-1.5 block text-[10px] font-semibold uppercase tracking-[0.08em] text-slate-500">
                    Keterangan
                </label>

                <input
                    type="text"
                    name="keterangan"
                    class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-[13px] text-slate-900 outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/10"
                    placeholder="Misal: Beli kebutuhan dapur">

            </div>


            <div class="flex gap-3">

                <button
                    type="button"
                    onclick="toggleModal('modalCatatKasbon')"
                    class="flex-1 rounded-xl bg-slate-100 py-2.5 text-[12px] font-semibold text-slate-700 transition-colors hover:bg-slate-200">
                    Batal
                </button>

                <button
                    type="submit"
                    class="flex-1 rounded-xl bg-amber-500 py-2.5 text-[12px] font-semibold text-white shadow-sm transition-colors hover:bg-amber-600">
                    Simpan Kasbon
                </button>

            </div>

        </form>

    </div>

</div>


{{-- =========================================================
    MODAL — CATAT PEMASUKAN
========================================================= --}}
<div
    id="modalCatatPemasukan"
    class="fixed inset-0 z-50 hidden flex h-full w-full items-center justify-center overflow-y-auto bg-slate-900/50 p-4 backdrop-blur-sm">

    <div class="my-8 w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">

        <h3 class="mb-5 text-[17px] font-semibold tracking-tight text-slate-900">
            Catat Termin Klien
        </h3>

        <form
            action="{{ route('proyek.keuangan.store', $proyek->id) }}"
            method="POST"
            enctype="multipart/form-data">

            @csrf

            <input type="hidden" name="tipe" value="Pemasukan">
            <input type="hidden" name="kategori" value="Termin Pembayaran">


            <div class="mb-4">

                <label class="mb-1.5 block text-[10px] font-semibold uppercase tracking-[0.08em] text-slate-500">
                    Nominal Termin (Rp)
                </label>

                <div class="relative">

                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm font-semibold text-slate-400">
                        Rp
                    </span>

                    <input
                        type="number"
                        name="nominal"
                        class="w-full rounded-xl border border-slate-300 py-2.5 pl-9 pr-3 text-[14px] font-semibold text-slate-900 outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10"
                        placeholder="50.000.000"
                        required>

                </div>

            </div>


            <div class="mb-4">

                <label class="mb-1.5 block text-[10px] font-semibold uppercase tracking-[0.08em] text-slate-500">
                    Tanggal Pembayaran
                </label>

                <input
                    type="date"
                    name="tanggal"
                    value="{{ date('Y-m-d') }}"
                    class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-[13px] text-slate-900 outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10"
                    required>

            </div>


            <div class="mb-4">

                <label class="mb-1.5 block text-[10px] font-semibold uppercase tracking-[0.08em] text-slate-500">
                    Keterangan / Catatan
                </label>

                <input
                    type="text"
                    name="keterangan"
                    class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-[13px] text-slate-900 outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10"
                    placeholder="Misal: Termin 1 (30%) dari Bpk. Budi"
                    required>

            </div>


            <div class="mb-6">

                <label class="mb-1.5 block text-[10px] font-semibold uppercase tracking-[0.08em] text-slate-500">
                    Bukti Transfer
                    <span class="normal-case tracking-normal text-slate-400">
                        (Opsional)
                    </span>
                </label>

                <input
                    type="file"
                    name="bukti_file"
                    accept="image/*,.pdf"
                    class="w-full cursor-pointer text-[11px] text-slate-500 file:mr-3 file:rounded-full file:border-0 file:bg-emerald-50 file:px-3 file:py-1.5 file:text-[11px] file:font-semibold file:text-emerald-700 hover:file:bg-emerald-100">

            </div>


            <div class="flex gap-3">

                <button
                    type="button"
                    onclick="toggleModal('modalCatatPemasukan')"
                    class="flex-1 rounded-xl bg-slate-100 py-2.5 text-[12px] font-semibold text-slate-700 transition-colors hover:bg-slate-200">
                    Batal
                </button>

                <button
                    type="submit"
                    class="flex-1 rounded-xl bg-emerald-600 py-2.5 text-[12px] font-semibold text-white shadow-sm transition-colors hover:bg-emerald-700">
                    Simpan Pemasukan
                </button>

            </div>

        </form>

    </div>

</div>


{{-- =========================================================
    MODAL — CATAT PENGELUARAN
========================================================= --}}
<div
    id="modalCatatPengeluaran"
    class="fixed inset-0 z-50 hidden flex h-full w-full items-center justify-center overflow-y-auto bg-slate-900/50 p-4 backdrop-blur-sm">

    <div class="my-8 w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">

        <h3 class="mb-5 text-[17px] font-semibold tracking-tight text-slate-900">
            Catat Biaya Lapangan
        </h3>

        <form
            action="{{ route('proyek.keuangan.store', $proyek->id) }}"
            method="POST"
            enctype="multipart/form-data">

            @csrf

            <input type="hidden" name="tipe" value="Pengeluaran">


            <div class="mb-4 grid grid-cols-1 gap-4 sm:grid-cols-2">

                <div>

                    <label class="mb-1.5 block text-[10px] font-semibold uppercase tracking-[0.08em] text-slate-500">
                        Kategori Biaya
                    </label>

                    <select
                        name="kategori"
                        class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-[13px] text-slate-900 outline-none focus:border-slate-700 focus:ring-2 focus:ring-slate-500/10"
                        required>

                        <option value="Material & Bahan">
                            Material & Bahan
                        </option>

                        <option value="Sewa Alat">
                            Sewa Alat
                        </option>

                        <option value="Operasional Lapangan">
                            Operasional (BBM, Makan)
                        </option>

                        <option value="Lain-lain">
                            Lain-lain
                        </option>

                    </select>

                </div>


                <div>

                    <label class="mb-1.5 block text-[10px] font-semibold uppercase tracking-[0.08em] text-slate-500">
                        Tanggal
                    </label>

                    <input
                        type="date"
                        name="tanggal"
                        value="{{ date('Y-m-d') }}"
                        class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-[13px] text-slate-900 outline-none focus:border-slate-700 focus:ring-2 focus:ring-slate-500/10"
                        required>

                </div>

            </div>


            <div class="mb-4">

                <label class="mb-1.5 block text-[10px] font-semibold uppercase tracking-[0.08em] text-slate-500">
                    Nominal (Rp)
                </label>

                <div class="relative">

                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm font-semibold text-slate-400">
                        Rp
                    </span>

                    <input
                        type="number"
                        name="nominal"
                        class="w-full rounded-xl border border-slate-300 py-2.5 pl-9 pr-3 text-[14px] font-semibold text-slate-900 outline-none focus:border-slate-700 focus:ring-2 focus:ring-slate-500/10"
                        placeholder="1.500.000"
                        required>

                </div>

            </div>


            <div class="mb-4">

                <label class="mb-1.5 block text-[10px] font-semibold uppercase tracking-[0.08em] text-slate-500">
                    Keterangan / Beli Apa?
                </label>

                <input
                    type="text"
                    name="keterangan"
                    class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-[13px] text-slate-900 outline-none focus:border-slate-700 focus:ring-2 focus:ring-slate-500/10"
                    placeholder="Misal: Beli Semen 50 Sak di UD Ema Kencana"
                    required>

            </div>


            <div class="mb-6">

                <label class="mb-1.5 block text-[10px] font-semibold uppercase tracking-[0.08em] text-slate-500">
                    Foto Nota / Kwitansi
                    <span class="normal-case tracking-normal text-slate-400">
                        (Opsional)
                    </span>
                </label>

                <input
                    type="file"
                    name="bukti_file"
                    accept="image/*,.pdf"
                    class="w-full cursor-pointer text-[11px] text-slate-500 file:mr-3 file:rounded-full file:border-0 file:bg-slate-100 file:px-3 file:py-1.5 file:text-[11px] file:font-semibold file:text-slate-700 hover:file:bg-slate-200">

            </div>


            <div class="flex gap-3">

                <button
                    type="button"
                    onclick="toggleModal('modalCatatPengeluaran')"
                    class="flex-1 rounded-xl bg-slate-100 py-2.5 text-[12px] font-semibold text-slate-700 transition-colors hover:bg-slate-200">
                    Batal
                </button>

                <button
                    type="submit"
                    class="flex-1 rounded-xl bg-slate-900 py-2.5 text-[12px] font-semibold text-white shadow-sm transition-colors hover:bg-slate-800">
                    Simpan Pengeluaran
                </button>

            </div>

        </form>

    </div>

</div>


{{-- =========================================================
    MODAL — BAYAR UPAH
========================================================= --}}
<div
    id="modalGaji"
    class="fixed inset-0 z-50 hidden flex h-full w-full items-center justify-center overflow-y-auto bg-slate-900/50 p-4 backdrop-blur-sm">

    <div
        class="my-8 flex max-h-[90vh] w-full max-w-lg flex-col overflow-hidden rounded-2xl bg-white shadow-xl">

        {{-- HEADER --}}
        <div class="flex shrink-0 items-center justify-between border-b border-slate-100 bg-slate-50 px-6 py-4">

            <div>

                <p class="text-[9px] font-medium uppercase tracking-[0.13em] text-blue-500">
                    Payroll Proyek
                </p>

                <h3 class="mt-1 text-[17px] font-semibold tracking-tight text-slate-900">
                    Hitung & Bayar Upah
                </h3>

            </div>

            <button
                type="button"
                onclick="toggleModal('modalGaji')"
                class="rounded-lg p-1.5 text-slate-400 transition-colors hover:bg-slate-100 hover:text-rose-500">
                <svg
                    class="h-5 w-5"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

        </div>


        <div class="overflow-y-auto p-6">

            {{-- TAHAP 1 --}}
            <form id="formCekGaji" class="mb-6">

                @csrf

                <div class="mb-4">

                    <label class="mb-1.5 block text-[10px] font-semibold uppercase tracking-[0.08em] text-slate-500">
                        Pilih Pekerja
                    </label>

                    <select
                        id="inputPegawaiGaji"
                        name="pegawai_id"
                        class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-[13px] text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10"
                        required>

                        @foreach($proyek->pegawais as $p)

                        <option value="{{ $p->id }}">
                            {{ $p->nama }}
                            ({{ $p->jabatan->nama_jabatan ?? '-' }})
                        </option>

                        @endforeach

                    </select>

                </div>


                <div class="mb-4 grid grid-cols-1 gap-4 sm:grid-cols-2">

                    <div>

                        <label class="mb-1.5 block text-[10px] font-semibold uppercase tracking-[0.08em] text-slate-500">
                            Dari Tanggal
                        </label>

                        <input
                            type="date"
                            id="inputStartGaji"
                            name="start_date"
                            class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-[13px] text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10"
                            required>

                    </div>


                    <div>

                        <label class="mb-1.5 block text-[10px] font-semibold uppercase tracking-[0.08em] text-slate-500">
                            Sampai Tanggal
                        </label>

                        <input
                            type="date"
                            id="inputEndGaji"
                            name="end_date"
                            value="{{ date('Y-m-d') }}"
                            class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-[13px] text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10"
                            required>

                    </div>

                </div>


                <button
                    type="submit"
                    id="btnCekGaji"
                    class="flex w-full items-center justify-center rounded-xl bg-slate-900 py-2.5 text-[12px] font-semibold text-white shadow-sm transition-colors hover:bg-slate-800">
                    Tarik Data Kehadiran & Kasbon
                </button>

            </form>


            {{-- TAHAP 2 --}}
            <div
                id="areaHasilGaji"
                class="mt-2 hidden border-t border-slate-200 pt-6">

                <h4 class="mb-4 text-[13px] font-semibold text-slate-800">

                    Rincian Slip Gaji:

                    <span
                        id="labelNamaPegawai"
                        class="text-blue-600"></span>

                </h4>


                <div
                    class="mb-4 rounded-xl border border-slate-100 bg-slate-50 p-4 text-[13px]">

                    <div class="mb-2 flex justify-between">
                        <span class="text-slate-600">
                            Hadir Penuh
                        </span>

                        <span class="font-semibold text-slate-800">
                            <span id="txtHariFull">0</span> Hari
                        </span>
                    </div>


                    <div class="mb-2 flex justify-between">
                        <span class="text-slate-600">
                            Setengah Hari
                        </span>

                        <span class="font-semibold text-slate-800">
                            <span id="txtHariSetengah">0</span> Hari
                        </span>
                    </div>


                    <div class="mb-2 flex justify-between border-t border-slate-200 pt-2">

                        <span class="font-semibold text-slate-800">
                            Total Upah Kotor
                        </span>

                        <span
                            class="text-[14px] font-semibold text-slate-900"
                            id="txtUpahKotor">
                            Rp 0
                        </span>

                    </div>


                    <div class="mt-2 flex justify-between text-rose-500">

                        <span>
                            Potongan Kasbon Lama
                        </span>

                        <span
                            class="font-semibold"
                            id="txtPotonganKasbon">
                            - Rp 0
                        </span>

                    </div>

                </div>


                {{-- FINAL PAYMENT --}}
                <form
                    action="{{ route('proyek.keuangan.bayar-gaji', $proyek->id) }}"
                    method="POST">

                    @csrf

                    <input type="hidden" name="pegawai_id" id="finalPegawaiId">
                    <input type="hidden" name="start_date" id="finalStartDate">
                    <input type="hidden" name="end_date" id="finalEndDate">
                    <input type="hidden" name="estimasi_gaji_sistem" id="finalEstimasiSistem">
                    <input type="hidden" name="id_absensi" id="finalIdAbsensi">


                    <div class="mb-5 rounded-xl border border-blue-100 bg-blue-50 p-4">

                        <div class="mb-3 flex items-center justify-between gap-4">

                            <span class="text-[10px] font-semibold uppercase tracking-[0.08em] text-blue-800">
                                Hak Gaji Bersih
                            </span>

                            <span
                                class="text-xl font-semibold tracking-tight text-blue-700"
                                id="txtGajiBersih">
                                Rp 0
                            </span>

                        </div>


                        <div class="mt-4">

                            <label
                                class="mb-1.5 block text-[10px] font-semibold uppercase tracking-[0.08em] text-slate-700">
                                Nominal Uang yang Dibayar
                            </label>

                            <div class="relative">

                                <span class="absolute left-3 top-1/2 -translate-y-1/2 font-semibold text-slate-400">
                                    Rp
                                </span>

                                <input
                                    type="number"
                                    id="inputNominalDibayar"
                                    name="nominal_dibayar"
                                    class="w-full rounded-xl border border-slate-300 py-2.5 pl-9 pr-3 text-[15px] font-semibold text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10"
                                    required>

                            </div>

                            <p class="mt-1.5 text-[10px] leading-4 text-slate-500">
                                Sistem akan mengkonversi selisih lebih bayar atau kurang bayar menjadi kasbon baru secara otomatis.
                            </p>

                        </div>

                    </div>


                    <button
                        type="submit"
                        class="w-full rounded-xl bg-blue-600 py-3 text-[13px] font-semibold text-white shadow-sm transition-colors hover:bg-blue-700">
                        Konfirmasi & Potong Saldo Kas
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
    SCRIPT
========================================================= --}}
<script>
    // =====================================================
    // MODAL
    // =====================================================

    function toggleModal(modalId) {

        const modal = document.getElementById(modalId);

        if (modal) {

            modal.classList.toggle('hidden');

        } else {

            console.error(
                "Modal dengan ID " + modalId + " tidak ditemukan!"
            );

        }

    }


    // =====================================================
    // FILTER & SEARCH BUKU KAS
    // =====================================================

    function filterBukuKas() {

        const searchInput =
            document.getElementById('cariTransaksi');

        const filterInput =
            document.getElementById('filterTipeTransaksi');

        if (!searchInput || !filterInput) {
            return;
        }

        const keyword =
            searchInput.value.toLowerCase();

        const tipeFilter =
            filterInput.value.toLowerCase();

        const rows =
            document.querySelectorAll(
                '#tabelBukuKas .row-transaksi'
            );

        let adaHasil = false;


        rows.forEach(row => {

            const keteranganElement =
                row.querySelector('.cell-keterangan');

            const keterangan =
                keteranganElement ?
                keteranganElement.textContent.toLowerCase() :
                '';

            const tipe =
                row.getAttribute('data-tipe');


            const cocokKeyword =
                keterangan.includes(keyword);

            const cocokTipe =
                tipeFilter === '' ||
                tipe === tipeFilter;


            const tampil =
                cocokKeyword &&
                cocokTipe;


            row.style.display =
                tampil ? '' : 'none';


            if (tampil) {
                adaHasil = true;
            }

        });


        const pesanKosong =
            document.getElementById(
                'pesanTransaksiKosong'
            );


        if (pesanKosong) {

            pesanKosong.classList.toggle(
                'hidden',
                adaHasil
            );

        }

    }


    // =====================================================
    // AJAX — HITUNG GAJI
    // =====================================================

    const formCekGaji =
        document.getElementById('formCekGaji');


    if (formCekGaji) {

        formCekGaji.addEventListener(
            'submit',
            async function(e) {

                e.preventDefault();


                const btnCek =
                    document.getElementById(
                        'btnCekGaji'
                    );

                const areaHasil =
                    document.getElementById(
                        'areaHasilGaji'
                    );


                const textLama =
                    btnCek.innerHTML;


                btnCek.innerHTML =
                    'Menghitung...';

                btnCek.disabled = true;


                const formData =
                    new FormData(this);


                const proyekId =
                    "{{ $proyek->id }}";


                try {

                    const response =
                        await fetch(
                            `/admin/proyek/${proyekId}/keuangan/preview-gaji`, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            }
                        );


                    const result =
                        await response.json();


                    if (result.status === 'success') {

                        const data =
                            result.data;


                        const formatRp =
                            angka =>
                            new Intl.NumberFormat(
                                'id-ID'
                            ).format(angka);


                        document.getElementById(
                                'labelNamaPegawai'
                            ).innerText =
                            data.nama_pegawai;


                        document.getElementById(
                                'txtHariFull'
                            ).innerText =
                            data.hari_full;


                        document.getElementById(
                                'txtHariSetengah'
                            ).innerText =
                            data.hari_setengah;


                        document.getElementById(
                                'txtUpahKotor'
                            ).innerText =
                            'Rp ' +
                            formatRp(
                                data.total_upah_kotor
                            );


                        document.getElementById(
                                'txtPotonganKasbon'
                            ).innerText =
                            '- Rp ' +
                            formatRp(
                                data.total_potongan_kasbon
                            );


                        document.getElementById(
                                'txtGajiBersih'
                            ).innerText =
                            'Rp ' +
                            formatRp(
                                data.estimasi_gaji_bersih
                            );


                        document.getElementById(
                                'inputNominalDibayar'
                            ).value =
                            Math.max(
                                0,
                                data.estimasi_gaji_bersih
                            );


                        document.getElementById(
                                'finalPegawaiId'
                            ).value =
                            formData.get(
                                'pegawai_id'
                            );


                        document.getElementById(
                                'finalStartDate'
                            ).value =
                            formData.get(
                                'start_date'
                            );


                        document.getElementById(
                                'finalEndDate'
                            ).value =
                            formData.get(
                                'end_date'
                            );


                        document.getElementById(
                                'finalEstimasiSistem'
                            ).value =
                            data.estimasi_gaji_bersih;


                        document.getElementById(
                                'finalIdAbsensi'
                            ).value =
                            data.id_absensi;


                        areaHasil.classList.remove(
                            'hidden'
                        );

                    } else {

                        alert(
                            result.message ||
                            'Data tidak dapat dihitung.'
                        );

                    }

                } catch (error) {

                    alert(
                        'Gagal mengambil data. Pastikan koneksi aman.'
                    );

                    console.error(error);

                } finally {

                    btnCek.innerHTML =
                        textLama;

                    btnCek.disabled =
                        false;

                }

            }
        );

    }
</script>

@endsection