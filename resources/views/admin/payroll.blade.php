@extends('layouts.admin')

@section('title', 'Rekap Gaji - ' . $proyek->nama_proyek)

@section('content')

@php
    $jumlahPekerja = count($rekapGaji);

    $totalHakBersih = collect($rekapGaji)
        ->sum('upah_bersih');
@endphp

<div class="space-y-6">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <section class="overflow-hidden rounded-[24px] border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-100 px-5 py-5 sm:px-6 lg:px-7">

            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

                {{-- LEFT --}}
                <div class="min-w-0">

                    <div class="flex items-center gap-2 text-[11px] font-semibold uppercase tracking-[0.16em] text-blue-600">
                        <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                        Payroll Proyek
                    </div>

                    <h1 class="mt-2 text-[23px] font-semibold tracking-[-0.025em] text-slate-900 sm:text-[26px]">
                        Rekap Gaji Pekerja
                    </h1>

                    <p class="mt-2 max-w-2xl text-[13px] leading-5.5 text-slate-500">
                        Kelola pembayaran upah berdasarkan absensi yang telah disetujui
                        pada proyek
                        <span class="font-semibold text-slate-700">
                            {{ $proyek->nama_proyek }}
                        </span>.
                    </p>

                </div>

                {{-- RIGHT --}}
                <div class="flex shrink-0 items-center gap-2">

                    <a
                        href="{{ route('proyek.keuangan', $proyek->id) }}"
                        class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-[12px] font-semibold text-slate-600 transition-all hover:border-slate-300 hover:bg-slate-50 hover:text-slate-900"
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

                        Kembali
                    </a>

                </div>

            </div>

        </div>


        {{-- =====================================================
            SUMMARY
        ====================================================== --}}
        <div class="grid grid-cols-1 divide-y divide-slate-100 sm:grid-cols-3 sm:divide-x sm:divide-y-0">

            {{-- PEKERJA --}}
            <div class="px-5 py-5 sm:px-6">

                <p class="text-[10px] font-semibold uppercase tracking-[0.15em] text-slate-400">
                    Pekerja
                </p>

                <div class="mt-2 flex items-end gap-2">

                    <span class="text-[24px] font-semibold tracking-tight text-slate-900">
                        {{ $jumlahPekerja }}
                    </span>

                    <span class="mb-0.5 text-[12px] font-medium text-slate-500">
                        orang
                    </span>

                </div>

                <p class="mt-1.5 text-[11px] text-slate-400">
                    Memiliki absensi yang siap dibayar
                </p>

            </div>


            {{-- PERIODE --}}
            <div class="px-5 py-5 sm:px-6">

                <p class="text-[10px] font-semibold uppercase tracking-[0.15em] text-slate-400">
                    Periode
                </p>

                <p class="mt-2 text-[14px] font-semibold text-slate-800">
                    {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') }}
                </p>

                <p class="mt-0.5 text-[11px] text-slate-400">
                    sampai
                    {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') }}
                </p>

            </div>


            {{-- HAK BERSIH --}}
            <div class="bg-blue-50/40 px-5 py-5 sm:px-6">

                <p class="text-[10px] font-semibold uppercase tracking-[0.15em] text-blue-500">
                    Total Hak Bersih
                </p>

                <p class="mt-2 text-[21px] font-semibold tracking-tight text-blue-700 sm:text-[22px]">
                    Rp {{ number_format($totalHakBersih, 0, ',', '.') }}
                </p>

                <p class="mt-1.5 text-[11px] text-blue-500/70">
                    Berdasarkan perhitungan sistem
                </p>

            </div>

        </div>

    </section>


    {{-- =========================================================
        FILTER PERIODE
    ========================================================== --}}
    <section class="rounded-[24px] border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-100 px-5 py-4.5 sm:px-6">

            <div class="flex items-center gap-3">

                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-slate-50 text-slate-600">

                    <svg
                        class="h-4.5 w-4.5"
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

                    <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-400">
                        Periode Penggajian
                    </p>

                    <p class="mt-0.5 text-[14px] font-semibold text-slate-800">
                        Filter absensi yang akan dibayarkan
                    </p>

                </div>

            </div>

        </div>


        <div class="px-5 py-5 sm:px-6">

            <form
                action="{{ route('proyek.payroll', $proyek->id) }}"
                method="GET"
                class="flex flex-col gap-4 lg:flex-row lg:items-end"
            >

                {{-- START DATE --}}
                <div class="w-full lg:w-auto">

                    <label
                        for="start_date"
                        class="mb-1.5 block text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-500"
                    >
                        Dari Tanggal
                    </label>

                    <input
                        id="start_date"
                        type="date"
                        name="start_date"
                        value="{{ $startDate }}"
                        required
                        class="h-[44px] w-full rounded-xl border border-slate-200 bg-slate-50 px-3 text-[13px] font-medium text-slate-700 outline-none transition-all focus:border-blue-400 focus:bg-white focus:ring-4 focus:ring-blue-500/10 lg:w-[165px]"
                    >

                </div>


                {{-- END DATE --}}
                <div class="w-full lg:w-auto">

                    <label
                        for="end_date"
                        class="mb-1.5 block text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-500"
                    >
                        Sampai Tanggal
                    </label>

                    <input
                        id="end_date"
                        type="date"
                        name="end_date"
                        value="{{ $endDate }}"
                        required
                        class="h-[44px] w-full rounded-xl border border-slate-200 bg-slate-50 px-3 text-[13px] font-medium text-slate-700 outline-none transition-all focus:border-blue-400 focus:bg-white focus:ring-4 focus:ring-blue-500/10 lg:w-[165px]"
                    >

                </div>


                {{-- BUTTON --}}
                <button
                    type="submit"
                    class="inline-flex h-[44px] w-full items-center justify-center gap-2 rounded-xl bg-slate-900 px-5 text-[12px] font-semibold text-white shadow-sm transition-all hover:bg-slate-800 lg:w-auto"
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


            {{-- INFO --}}
            <div class="mt-4 flex items-start gap-2.5 rounded-xl border border-blue-100 bg-blue-50/60 px-3.5 py-3">

                <svg
                    class="mt-0.5 h-4 w-4 shrink-0 text-blue-500"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
                        d="M13 16h-1v-4h-1m1-4h.01M12 21a9 9 0 100-18 9 9 0 000 18z"
                    />
                </svg>

                <p class="text-[11px] leading-4.5 text-blue-700/80">
                    Sistem hanya menghitung absensi dengan status
                    <span class="font-semibold">Hadir</span>,
                    <span class="font-semibold">Disetujui</span>,
                    dan
                    <span class="font-semibold">Belum Dibayar</span>.
                </p>

            </div>

        </div>

    </section>


    {{-- =========================================================
        PAYROLL TABLE
    ========================================================== --}}
    <section class="overflow-hidden rounded-[24px] border border-slate-200 bg-white shadow-sm">

        @if(count($rekapGaji) > 0)

            <form
                action="{{ route('proyek.payroll.store', $proyek->id) }}"
                method="POST"
                enctype="multipart/form-data"
                id="payroll-form"
            >

                @csrf


                {{-- =================================================
                    TABLE HEADER
                ================================================== --}}
                <div class="border-b border-slate-100 px-5 py-5 sm:px-6">

                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                        <div>

                            <p class="text-[10px] font-semibold uppercase tracking-[0.15em] text-slate-400">
                                Daftar Penggajian
                            </p>

                            <h2 class="mt-1 text-[17px] font-semibold tracking-tight text-slate-900">
                                Pembayaran Pekerja
                            </h2>

                            <p class="mt-1.5 text-[12px] leading-5 text-slate-500">
                                Nominal awal mengikuti hak sistem dan dapat disesuaikan sesuai uang yang benar-benar diberikan.
                            </p>

                        </div>


                        <div class="inline-flex w-fit items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2">

                            <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>

                            <span class="text-[11px] font-medium text-slate-500">
                                {{ $jumlahPekerja }} pekerja
                            </span>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    HIDDEN PERIOD
                ================================================== --}}
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


                {{-- =================================================
                    TABLE
                ================================================== --}}
                <div class="overflow-x-auto">

                    <table class="w-full min-w-[1050px] border-collapse text-left">

                        <thead>

                            <tr class="border-b border-slate-200 bg-slate-50/80">

                                <th class="px-5 py-3.5 text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-500">
                                    Pekerja
                                </th>

                                <th class="px-5 py-3.5 text-center text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-500">
                                    Kehadiran
                                </th>

                                <th class="px-5 py-3.5 text-right text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-500">
                                    Gaji Kotor
                                </th>

                                <th class="px-5 py-3.5 text-right text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-500">
                                    Potongan Kasbon
                                </th>

                                <th class="bg-blue-50/60 px-5 py-3.5 text-right text-[10px] font-semibold uppercase tracking-[0.14em] text-blue-600">
                                    Hak Sistem
                                </th>

                                <th class="w-56 px-5 py-3.5 text-left text-[10px] font-semibold uppercase tracking-[0.14em] text-emerald-600">
                                    Uang di Amplop
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-slate-100">

                            @foreach($rekapGaji as $pegawaiId => $data)

                                <tr class="group transition-colors hover:bg-slate-50/60">

                                    {{-- =================================
                                        PEGAWAI
                                    ================================== --}}
                                    <td class="px-5 py-4.5">

                                        {{-- REQUIRED BY BACKEND --}}
                                        <input
                                            type="hidden"
                                            name="pembayaran[{{ $pegawaiId }}][pegawai_id]"
                                            value="{{ $pegawaiId }}"
                                        >

                                        <div>

                                            <p class="text-[14px] font-semibold text-slate-900">
                                                {{ $data['pegawai']->nama }}
                                            </p>

                                            <div class="mt-1.5 flex items-center gap-1.5 text-[11px] text-slate-500">

                                                <span>
                                                    {{ $data['pegawai']->jabatan->nama_jabatan ?? '-' }}
                                                </span>

                                                <span class="text-slate-300">
                                                    •
                                                </span>

                                                <span>
                                                    Rp {{ number_format($data['gaji_harian'], 0, ',', '.') }}/hari
                                                </span>

                                            </div>

                                        </div>

                                    </td>


                                    {{-- =================================
                                        KEHADIRAN
                                    ================================== --}}
                                    <td class="px-5 py-4.5 text-center">

                                        <div class="flex items-center justify-center gap-1.5">

                                            <span
                                                class="inline-flex items-center rounded-lg border border-emerald-100 bg-emerald-50 px-2.5 py-1.5 text-[11px] font-semibold text-emerald-700"
                                            >
                                                {{ $data['hari_full'] }} F
                                            </span>

                                            @if($data['hari_setengah'] > 0)

                                                <span
                                                    class="inline-flex items-center rounded-lg border border-amber-100 bg-amber-50 px-2.5 py-1.5 text-[11px] font-semibold text-amber-700"
                                                >
                                                    {{ $data['hari_setengah'] }} H
                                                </span>

                                            @endif

                                        </div>

                                    </td>


                                    {{-- =================================
                                        GAJI KOTOR
                                    ================================== --}}
                                    <td class="px-5 py-4.5 text-right">

                                        <span class="text-[13px] font-medium text-slate-700">
                                            Rp {{ number_format($data['upah_kotor'], 0, ',', '.') }}
                                        </span>

                                    </td>


                                    {{-- =================================
                                        KASBON
                                    ================================== --}}
                                    <td class="px-5 py-4.5 text-right">

                                        @if($data['potongan_kasbon'] > 0)

                                            <span class="text-[13px] font-medium text-rose-600">
                                                - Rp {{ number_format($data['potongan_kasbon'], 0, ',', '.') }}
                                            </span>

                                        @else

                                            <span class="text-[13px] text-slate-400">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- =================================
                                        HAK SISTEM
                                    ================================== --}}
                                    <td class="bg-blue-50/30 px-5 py-4.5 text-right">

                                        <span class="text-[14px] font-semibold text-blue-700">
                                            Rp {{ number_format($data['upah_bersih'], 0, ',', '.') }}
                                        </span>

                                    </td>


                                    {{-- =================================
                                        UANG AMPLOP
                                    ================================== --}}
                                    <td class="px-5 py-4.5">

                                        <div class="relative rounded-xl border border-emerald-200 bg-emerald-50/30 transition-all focus-within:border-emerald-400 focus-within:bg-emerald-50/60 focus-within:ring-4 focus-within:ring-emerald-500/10">

                                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[12px] font-semibold text-emerald-600">
                                                Rp
                                            </span>

                                            <input
                                                type="number"
                                                name="pembayaran[{{ $pegawaiId }}][nominal]"
                                                value="{{ $data['upah_bersih'] }}"
                                                min="{{ $data['upah_bersih'] }}"
                                                step="1"
                                                required
                                                data-payroll-nominal
                                                class="w-full border-0 bg-transparent py-2.5 pl-9 pr-3 text-[14px] font-semibold text-slate-900 outline-none focus:ring-0"
                                            >

                                        </div>

                                        <p class="mt-1.5 text-[10px] text-slate-400">
                                            Minimal Rp {{ number_format($data['upah_bersih'], 0, ',', '.') }}
                                        </p>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- =================================================
                    PAYMENT FOOTER
                ================================================== --}}
                <div class="border-t border-slate-200 bg-slate-50/70 p-5 sm:p-6">

                    <div class="grid grid-cols-1 gap-6 xl:grid-cols-[1fr_360px]">

                        {{-- BUKTI --}}
                        <div>

                            <label
                                for="bukti_file"
                                class="mb-2 block text-[11px] font-semibold uppercase tracking-[0.15em] text-slate-500"
                            >
                                Bukti Pembayaran / Serah Terima
                                <span class="text-rose-500">*</span>
                            </label>

                            <input
                                id="bukti_file"
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

                            <p class="mt-2 max-w-xl text-[11px] leading-4.5 text-slate-400">
                                Lampirkan foto tanda terima, kuitansi, atau dokumentasi
                                penyerahan pembayaran kepada pekerja.
                            </p>

                        </div>


                        {{-- TOTAL --}}
                        <div class="rounded-2xl border border-slate-200 bg-white p-4.5">

                            <div class="flex items-center justify-between gap-4">

                                <div>

                                    <p class="text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-400">
                                        Hak Sistem
                                    </p>

                                    <p class="mt-1 text-[14px] font-semibold text-slate-700">
                                        Rp {{ number_format($totalHakBersih, 0, ',', '.') }}
                                    </p>

                                </div>

                                <div class="h-8 w-px bg-slate-200"></div>

                                <div class="text-right">

                                    <p class="text-[10px] font-semibold uppercase tracking-[0.14em] text-emerald-600">
                                        Total Kas Keluar
                                    </p>

                                    <p
                                        id="total-kas-keluar"
                                        class="mt-1 text-[21px] font-semibold tracking-tight text-emerald-700"
                                    >
                                        Rp {{ number_format($totalHakBersih, 0, ',', '.') }}
                                    </p>

                                </div>

                            </div>

                            <div class="mt-3 border-t border-slate-100 pt-3">

                                <p class="text-[11px] leading-4.5 text-slate-400">
                                    Jika uang yang diberikan lebih besar dari hak sistem,
                                    selisihnya akan dicatat sebagai kasbon baru.
                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- ACTION --}}
                    <div class="mt-5 flex justify-end">

                        <button
                            type="submit"
                            id="submit-payroll"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-slate-900 px-6 py-3 text-[12px] font-semibold text-white shadow-sm transition-all hover:bg-slate-800 sm:w-auto"
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
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 002 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"
                                />
                            </svg>

                            Konfirmasi & Bayar

                        </button>

                    </div>

                </div>

            </form>

        @else

            {{-- =====================================================
                EMPTY STATE
            ====================================================== --}}
            <div class="px-6 py-20 text-center">

                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl border border-slate-200 bg-slate-50">

                    <svg
                        class="h-6 w-6 text-slate-300"
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

                <p class="mt-5 text-[10px] font-semibold uppercase tracking-[0.16em] text-emerald-600">
                    Tidak Ada Pembayaran
                </p>

                <h3 class="mt-2 text-[18px] font-semibold tracking-tight text-slate-900">
                    Tidak Ada Upah yang Perlu Dibayar
                </h3>

                <p class="mx-auto mt-2 max-w-md text-[12px] leading-5 text-slate-500">
                    Tidak ditemukan absensi
                    <span class="font-semibold text-slate-700">Hadir</span>,
                    <span class="font-semibold text-slate-700">Disetujui</span>,
                    dan
                    <span class="font-semibold text-slate-700">Belum Dibayar</span>
                    pada periode yang dipilih.
                </p>

            </div>

        @endif

    </section>

</div>


{{-- =========================================================
    PAYROLL SCRIPT
========================================================= --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const inputs = document.querySelectorAll('[data-payroll-nominal]');
    const totalElement = document.getElementById('total-kas-keluar');

    if (!inputs.length || !totalElement) {
        return;
    }

    const formatRupiah = (value) => {
        return new Intl.NumberFormat('id-ID').format(value);
    };

    const updateTotal = () => {

        let total = 0;

        inputs.forEach((input) => {

            const value = Number(input.value);

            if (!Number.isNaN(value) && value > 0) {
                total += value;
            }

        });

        totalElement.textContent =
            `Rp ${formatRupiah(total)}`;
    };

    inputs.forEach((input) => {
        input.addEventListener('input', updateTotal);
        input.addEventListener('change', updateTotal);
    });

    updateTotal();

});
</script>

@endsection