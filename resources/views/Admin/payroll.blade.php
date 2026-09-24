@extends('layouts.admin')

@section('title', 'Rekap Gaji Massal - ' . $proyek->nama_proyek)

@section('content')

@php
$jumlahPekerja = count($rekapGaji);
$kasPayroll = 0;

foreach ($rekapGaji as $data) {
    $kasPayroll += max(0, $data['upah_bersih']);
}

@endphp

<div class="space-y-6">
{{-- =========================================================
    HERO / HEADER PAYROLL
========================================================== --}}
<section
    class="relative isolate overflow-hidden rounded-[30px] bg-slate-950 text-white shadow-xl shadow-slate-200/50"
>

    {{-- PROJECT IMAGE --}}
    @if($proyek->gambar)
        <div
            class="absolute inset-0 -z-30 bg-cover bg-center opacity-[0.20]"
            style="background-image: url('{{ asset('storage/' . $proyek->gambar) }}');"
        ></div>
    @else
        <div class="absolute inset-0 -z-30 bg-slate-950"></div>
    @endif

    {{-- DARK OVERLAY --}}
    <div
        class="absolute inset-0 -z-20 bg-gradient-to-br from-slate-950 via-slate-950/[0.95] to-blue-950/[0.94]"
    ></div>

    {{-- BLUE GLOW --}}
    <div
        class="absolute -top-32 -right-32 -z-10 h-80 w-80 rounded-full bg-blue-500/10 blur-3xl"
    ></div>

    <div
        class="absolute -bottom-40 -left-20 -z-10 h-80 w-80 rounded-full bg-indigo-500/10 blur-3xl"
    ></div>

    {{-- SUBTLE GRID --}}
    <div
        class="absolute inset-0 -z-10 opacity-[0.035]"
        style="background-image: linear-gradient(rgba(255,255,255,.8) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.8) 1px, transparent 1px); background-size: 34px 34px;"
    ></div>

    <div class="relative p-5 sm:p-7 lg:p-8">

        {{-- TOP NAVIGATION --}}
        <div class="flex flex-wrap items-center justify-between gap-4">

            <a
                href="{{ route('proyek.keuangan', $proyek->id) }}"
                class="group inline-flex items-center gap-2.5 text-[11px] font-medium text-slate-300 transition-colors hover:text-white"
            >
                <span
                    class="flex h-8 w-8 items-center justify-center rounded-xl border border-white/10 bg-white/[0.06] transition-all duration-200 group-hover:-translate-x-0.5 group-hover:bg-white/10"
                >
                    <svg
                        class="h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"
                        />
                    </svg>
                </span>

                Kembali ke Keuangan
            </a>

            <span
                class="inline-flex items-center rounded-xl border border-white/10 bg-white/[0.06] px-3 py-2 text-[9px] font-semibold uppercase tracking-[0.16em] text-slate-300 backdrop-blur-sm"
            >
                Rekap Gaji Massal
            </span>

        </div>


        {{-- HERO CONTENT --}}
        <div class="mt-8 max-w-3xl">

            <p
                class="text-[9px] font-semibold uppercase tracking-[0.20em] text-blue-300"
            >
                Rekapitulasi Penggajian
            </p>

            <h1
                class="mt-2 text-[30px] font-semibold tracking-[-0.035em] text-white sm:text-[34px]"
            >
                Payroll Proyek
            </h1>

            <div class="mt-3 flex flex-wrap items-center gap-x-4 gap-y-2">

                <p class="flex items-center gap-2 text-[12px] font-medium text-slate-300">
                    <svg
                        class="h-4 w-4 text-slate-500"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                        />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                        />
                    </svg>

                    {{ $proyek->nama_proyek }}
                </p>

                <span class="hidden h-1 w-1 rounded-full bg-slate-600 sm:block"></span>

                <p class="text-[11px] text-slate-400">
                    Tentukan periode absensi untuk menghitung pembayaran pekerja.
                </p>

            </div>

        </div>


        {{-- KPI --}}
        <div class="mt-7 grid grid-cols-1 gap-3 sm:grid-cols-3">

            {{-- JUMLAH PEKERJA --}}
            <div
                class="rounded-2xl border border-white/10 bg-white/[0.055] p-4 backdrop-blur-md"
            >
                <p class="text-[9px] font-semibold uppercase tracking-[0.15em] text-slate-400">
                    Pekerja
                </p>

                <div class="mt-2 flex items-end justify-between gap-3">
                    <p class="text-[21px] font-semibold tracking-tight text-white">
                        {{ $jumlahPekerja }}
                        <span class="text-[12px] font-medium text-slate-400">Orang</span>
                    </p>

                    <div
                        class="flex h-8 w-8 items-center justify-center rounded-xl bg-blue-500/10 text-blue-300"
                    >
                        <svg
                            class="h-4 w-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2m7-8a4 4 0 100-8 4 4 0 000 8zm7-3a3 3 0 00-2.4 1.2M22 21v-2a4 4 0 00-3-3.87"
                            />
                        </svg>
                    </div>
                </div>
            </div>


            {{-- PERIODE --}}
            <div
                class="rounded-2xl border border-white/10 bg-white/[0.055] p-4 backdrop-blur-md"
            >
                <p class="text-[9px] font-semibold uppercase tracking-[0.15em] text-slate-400">
                    Periode
                </p>

                <div class="mt-2">
                    <p class="text-[14px] font-semibold text-white">
                        {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') }}
                    </p>

                    <p class="mt-0.5 text-[10px] text-slate-400">
                        sampai
                        {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') }}
                    </p>
                </div>
            </div>


            {{-- TOTAL PAYROLL --}}
            <div
                class="rounded-2xl border border-emerald-400/10 bg-emerald-500/[0.055] p-4 backdrop-blur-md"
            >
                <p class="text-[9px] font-semibold uppercase tracking-[0.15em] text-emerald-300/70">
                    Total Pembayaran
                </p>

                <div class="mt-2 flex items-end justify-between gap-3">
                    <p class="text-[20px] font-semibold tracking-tight text-emerald-300">
                        Rp {{ number_format($kasPayroll, 0, ',', '.') }}
                    </p>

                    <div
                        class="flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-500/10 text-emerald-300"
                    >
                        <svg
                            class="h-4 w-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V6m0 12v-2m0 2c-1.11 0-2.08-.402-2.599-1M12 18c-1.657 0-3-.895-3-2m6-6c0-1.105-1.343-2-3-2s-3 .895-3 2"
                            />
                        </svg>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>


{{-- =========================================================
    FILTER PERIODE
========================================================== --}}
<section
    class="rounded-[24px] border border-slate-200 bg-white p-5 shadow-sm sm:p-6"
>

    <div class="flex flex-col gap-6 xl:flex-row xl:items-center xl:justify-between">

        {{-- INFO --}}
        <div class="flex items-start gap-3.5">

            <div
                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-blue-100 bg-blue-50 text-blue-600"
            >
                <svg
                    class="h-5 w-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                    />
                </svg>
            </div>

            <div>
                <p class="text-[9px] font-semibold uppercase tracking-[0.16em] text-blue-600">
                    Periode Penggajian
                </p>

                <h2 class="mt-1 text-[15px] font-semibold tracking-tight text-slate-900">
                    Tentukan rentang absensi
                </h2>

                <p class="mt-1 max-w-xl text-[12px] leading-5 text-slate-500">
                    Sistem hanya mengambil absensi yang
                    <span class="font-semibold text-emerald-600">Disetujui</span>
                    dan
                    <span class="font-semibold text-rose-500">Belum Dibayar</span>.
                </p>
            </div>

        </div>


        {{-- FORM --}}
        <form
            action="{{ route('proyek.payroll', $proyek->id) }}"
            method="GET"
            class="flex w-full flex-col gap-3 sm:flex-row sm:items-end xl:w-auto"
        >

            <div class="w-full sm:w-auto">
                <label
                    class="mb-1.5 block text-[9px] font-semibold uppercase tracking-[0.14em] text-slate-500"
                >
                    Dari Tanggal
                </label>

                <input
                    type="date"
                    name="start_date"
                    value="{{ $startDate }}"
                    required
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-[13px] font-medium text-slate-800 outline-none transition-all focus:border-blue-400 focus:bg-white focus:ring-4 focus:ring-blue-500/10 sm:w-[160px]"
                >
            </div>

            <div class="w-full sm:w-auto">
                <label
                    class="mb-1.5 block text-[9px] font-semibold uppercase tracking-[0.14em] text-slate-500"
                >
                    Sampai Tanggal
                </label>

                <input
                    type="date"
                    name="end_date"
                    value="{{ $endDate }}"
                    required
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-[13px] font-medium text-slate-800 outline-none transition-all focus:border-blue-400 focus:bg-white focus:ring-4 focus:ring-blue-500/10 sm:w-[160px]"
                >
            </div>

            <button
                type="submit"
                class="inline-flex h-[42px] w-full items-center justify-center gap-2 rounded-xl bg-slate-900 px-5 text-[11px] font-semibold text-white shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:bg-slate-800 hover:shadow-md sm:w-auto"
            >
                <svg
                    class="h-4 w-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
                        d="M21 21l-4.35-4.35m1.35-5.65a7 7 0 11-14 0 7 7 0 0114 0z"
                    />
                </svg>

                Tampilkan
            </button>

        </form>

    </div>

</section>


{{-- =========================================================
    TABEL REKAP GAJI
========================================================== --}}
<section
    class="overflow-hidden rounded-[24px] border border-slate-200 bg-white shadow-sm"
>

    @if(count($rekapGaji) > 0)

        {{-- FORM EKSEKUSI PEMBAYARAN --}}
        <form
            action="{{ route('proyek.payroll.store', $proyek->id) }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf

            {{-- Hidden Input --}}
            <input
                type="hidden"
                name="periode_start"
                value="{{ $startDate }}"
            >

            <input
                type="hidden"
                name="periode_end"
                value="{{ $endDate }}"
            >


            {{-- TABLE HEADER INFO --}}
            <div class="border-b border-slate-100 px-5 py-5 sm:px-6">

                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">

                    <div>
                        <p class="text-[9px] font-semibold uppercase tracking-[0.16em] text-slate-400">
                            Daftar Pembayaran
                        </p>

                        <h2 class="mt-1 text-[16px] font-semibold tracking-tight text-slate-900">
                            Rekap Gaji Pekerja
                        </h2>

                        <p class="mt-1 text-[12px] text-slate-500">
                            Periksa nominal sistem sebelum pembayaran dikonfirmasi.
                        </p>
                    </div>

                    <div
                        class="inline-flex w-fit items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2"
                    >
                        <span class="h-2 w-2 rounded-full bg-blue-500"></span>

                        <span class="text-[10px] font-medium text-slate-500">
                            {{ $jumlahPekerja }} pekerja dalam rekap
                        </span>
                    </div>

                </div>

            </div>


            {{-- TABLE --}}
            <div class="overflow-x-auto">
                <table class="w-full min-w-[1000px] border-collapse text-left">

                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50/80">

                            <th class="px-5 py-3.5 text-[9px] font-semibold uppercase tracking-[0.15em] text-slate-500">
                                Nama Pekerja
                            </th>

                            <th class="px-5 py-3.5 text-center text-[9px] font-semibold uppercase tracking-[0.15em] text-slate-500">
                                Kehadiran
                            </th>

                            <th class="px-5 py-3.5 text-right text-[9px] font-semibold uppercase tracking-[0.15em] text-slate-500">
                                Gaji Kotor
                            </th>

                            <th class="px-5 py-3.5 text-right text-[9px] font-semibold uppercase tracking-[0.15em] text-slate-500">
                                Potongan Kasbon
                            </th>

                            <th class="bg-blue-50/50 px-5 py-3.5 text-right text-[9px] font-semibold uppercase tracking-[0.15em] text-blue-600">
                                Hak Sistem
                            </th>

                            <th class="w-52 px-5 py-3.5 text-left text-[9px] font-semibold uppercase tracking-[0.15em] text-slate-600">
                                Uang di Amplop
                            </th>

                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        @php
                            $totalSemuaGaji = 0;
                        @endphp

                        @foreach($rekapGaji as $pegawaiId => $data)

                            {{-- Hidden Data --}}
                            <input
                                type="hidden"
                                name="pembayaran[{{ $pegawaiId }}][estimasi_sistem]"
                                value="{{ $data['upah_bersih'] }}"
                            >

                            <input
                                type="hidden"
                                name="pembayaran[{{ $pegawaiId }}][id_absensi]"
                                value="{{ implode(',', $data['id_absensi']) }}"
                            >

                            <tr
                                class="group transition-colors hover:bg-slate-50/70"
                            >

                                {{-- PEGAWAI --}}
                                <td class="px-5 py-4">

                                    <div>
                                        <p class="text-[13px] font-semibold text-slate-900">
                                            {{ $data['pegawai']->nama }}
                                        </p>

                                        <p class="mt-1 text-[10px] font-medium text-slate-500">
                                            {{ $data['pegawai']->jabatan->nama_jabatan ?? '-' }}

                                            <span class="mx-1 text-slate-300">•</span>

                                            Rp {{ number_format($data['pegawai']->jabatan->gaji_harian ?? 0, 0, ',', '.') }}/hari
                                        </p>
                                    </div>

                                </td>


                                {{-- KEHADIRAN --}}
                                <td class="px-5 py-4 text-center">

                                    <div class="flex items-center justify-center gap-1.5">

                                        <span
                                            class="inline-flex items-center rounded-lg border border-emerald-100 bg-emerald-50 px-2 py-1 text-[10px] font-semibold text-emerald-700"
                                            title="Hadir Full"
                                        >
                                            {{ $data['hari_full'] }} F
                                        </span>

                                        @if($data['hari_setengah'] > 0)

                                            <span
                                                class="inline-flex items-center rounded-lg border border-amber-100 bg-amber-50 px-2 py-1 text-[10px] font-semibold text-amber-700"
                                                title="Setengah Hari"
                                            >
                                                {{ $data['hari_setengah'] }} H
                                            </span>

                                        @endif

                                    </div>

                                </td>


                                {{-- GAJI KOTOR --}}
                                <td class="px-5 py-4 text-right">

                                    <span class="text-[12px] font-medium text-slate-700">
                                        Rp {{ number_format($data['upah_kotor'], 0, ',', '.') }}
                                    </span>

                                </td>


                                {{-- KASBON --}}
                                <td class="px-5 py-4 text-right">

                                    <span class="text-[12px] font-medium text-rose-600">
                                        - Rp {{ number_format($data['potongan_kasbon'], 0, ',', '.') }}
                                    </span>

                                </td>


                                {{-- HAK SISTEM --}}
                                <td class="bg-blue-50/30 px-5 py-4 text-right">

                                    <span class="text-[13px] font-semibold text-blue-600">
                                        Rp {{ number_format($data['upah_bersih'], 0, ',', '.') }}
                                    </span>

                                </td>


                                {{-- UANG DI AMPLOP --}}
                                <td class="px-5 py-4">

                                    <div
                                        class="relative rounded-xl border border-emerald-200 bg-emerald-50/30 transition-all focus-within:border-emerald-400 focus-within:bg-emerald-50/60 focus-within:ring-4 focus-within:ring-emerald-500/10"
                                    >

                                        <span
                                            class="absolute left-3 top-1/2 -translate-y-1/2 text-[11px] font-semibold text-emerald-600"
                                        >
                                            Rp
                                        </span>

                                        <input
                                            type="number"
                                            name="pembayaran[{{ $pegawaiId }}][nominal]"
                                            value="{{ max(0, $data['upah_bersih']) }}"
                                            min="0"
                                            required
                                            class="w-full border-0 bg-transparent py-2.5 pl-9 pr-3 text-[13px] font-semibold text-slate-900 outline-none focus:ring-0"
                                        >

                                    </div>

                                </td>

                            </tr>

                            @php
                                $totalSemuaGaji += max(0, $data['upah_bersih']);
                            @endphp

                        @endforeach

                    </tbody>

                </table>
            </div>


            {{-- =================================================
                FOOTER KONFIRMASI
            ================================================== --}}
            <div class="border-t border-slate-200 bg-slate-50/80 p-5 sm:p-6">

                <div class="grid grid-cols-1 gap-6 lg:grid-cols-[1fr_auto] lg:items-end">

                    {{-- BUKTI PEMBAYARAN --}}
                    <div>

                        <label
                            class="mb-2 block text-[9px] font-semibold uppercase tracking-[0.15em] text-slate-500"
                        >
                            Bukti Pembayaran / Serah Terima
                            <span class="text-rose-500">*</span>
                        </label>

                        <input
                            type="file"
                            name="bukti_file"
                            accept="image/*,.pdf"
                            required
                            class="block w-full cursor-pointer rounded-xl border border-slate-200 bg-white p-1 text-[12px] text-slate-500 file:mr-3 file:rounded-lg file:border-0 file:bg-blue-50 file:px-3 file:py-2 file:text-[11px] file:font-semibold file:text-blue-700 hover:file:bg-blue-100"
                        >

                        @error('bukti_file')
                            <p class="mt-1.5 text-[11px] font-medium text-rose-500">
                                {{ $message }}
                            </p>
                        @enderror

                        <p class="mt-2 max-w-2xl text-[10px] leading-4 text-slate-400">
                            Wajib dilampirkan. Dapat berupa foto lembar tanda tangan tukang,
                            kuitansi, atau dokumentasi penyerahan amplop.
                        </p>

                    </div>


                    {{-- TOTAL --}}
                    <div class="lg:min-w-[280px] lg:text-right">

                        <p
                            class="text-[9px] font-semibold uppercase tracking-[0.15em] text-slate-400"
                        >
                            Total Dana Kas yang Akan Dikeluarkan
                        </p>

                        <p
                            class="mt-1.5 text-[24px] font-semibold tracking-[-0.025em] text-slate-900"
                        >
                            Rp {{ number_format($totalSemuaGaji, 0, ',', '.') }}
                        </p>

                    </div>

                </div>


                {{-- ACTION --}}
                <div class="mt-6 flex justify-end">

                    <button
                        type="submit"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-6 py-3 text-[11px] font-semibold text-white shadow-sm shadow-emerald-900/10 transition-all duration-200 hover:-translate-y-0.5 hover:bg-emerald-500 hover:shadow-md sm:w-auto"
                    >

                        <svg
                            class="h-4 w-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"
                            />
                        </svg>

                        Konfirmasi & Potong Saldo Kas

                    </button>

                </div>

            </div>

        </form>

    @else

        {{-- =====================================================
            EMPTY STATE
        ====================================================== --}}
        <div class="px-6 py-20 text-center">

            <div
                class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-2xl border border-slate-200 bg-slate-50"
            >
                <svg
                    class="h-7 w-7 text-slate-300"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.7"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a3 3 0 006 0M9 14l2 2 4-4"
                    />
                </svg>
            </div>

            <p
                class="text-[9px] font-semibold uppercase tracking-[0.16em] text-emerald-600"
            >
                Tidak Ada Pembayaran
            </p>

            <h3
                class="mt-2 text-[18px] font-semibold tracking-tight text-slate-900"
            >
                Semua Tagihan Lunas
            </h3>

            <p
                class="mx-auto mt-2 max-w-md text-[12px] leading-5 text-slate-500"
            >
                Tidak ada catatan kehadiran yang berstatus
                <span class="font-semibold text-slate-700">Belum Dibayar</span>
                pada rentang tanggal tersebut, atau mandor belum melakukan absensi.
            </p>

        </div>

    @endif

</section>
</div>

@endsection
