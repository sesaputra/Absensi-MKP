@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

@php
$jamSekarang = now()->hour;

$sapaan = $jamSekarang < 11
    ? 'Selamat Pagi'
    : ($jamSekarang < 15
    ? 'Selamat Siang'
    : ($jamSekarang < 19
    ? 'Selamat Sore'
    : 'Selamat Malam' ));

    $tanggalHariIni=now()
    ->locale('id')
    ->translatedFormat('l, d F Y');

    /*
    |--------------------------------------------------------------------------
    | Default Values
    |--------------------------------------------------------------------------
    */

    $totalPendingValidasi = $totalPendingValidasi ?? 0;
    $totalProyekKritis = $totalProyekKritis ?? 0;
    $totalProyekDanaMenipis = $totalProyekDanaMenipis ?? 0;
    $totalPegawaiTanpaAkun = $totalPegawaiTanpaAkun ?? 0;


    /*
    |--------------------------------------------------------------------------
    | Notification State
    |--------------------------------------------------------------------------
    */

    $adaNotifikasi =
    $totalPendingValidasi > 0 ||
    $totalProyekKritis > 0 ||
    $totalProyekDanaMenipis > 0 ||
    $totalPegawaiTanpaAkun > 0;


    /*
    |--------------------------------------------------------------------------
    | Jumlah Jenis Peringatan
    |--------------------------------------------------------------------------
    */

    $jumlahPeringatan = collect([
    $totalPendingValidasi,
    $totalProyekKritis,
    $totalProyekDanaMenipis,
    $totalPegawaiTanpaAkun,
    ])->filter(fn ($value) => $value > 0)->count();
    @endphp


    <div class="space-y-7">


        {{-- ============================================================
        HEADER
    ============================================================ --}}
        <section class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white">

            <div class="absolute right-0 top-0 h-full w-72 overflow-hidden opacity-[0.07]">

                <img
                    src="{{ asset('images/flower-mkp.jpg') }}"
                    alt=""
                    class="h-full w-full object-cover object-center">

            </div>


            <div class="relative z-10 flex flex-col gap-5 px-6 py-6 sm:px-8 sm:py-7 lg:flex-row lg:items-center lg:justify-between">

                <div>

                    <div class="mb-2 flex items-center gap-2">

                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>

                        <span class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">
                            Sistem Manajemen MKP
                        </span>

                    </div>


                    <h1 class="text-2xl font-semibold tracking-tight text-slate-900 sm:text-3xl">
                        {{ $sapaan }}, {{ auth()->user()->name }}
                    </h1>


                    <p class="mt-1 text-sm text-slate-500">
                        Berikut ringkasan kondisi proyek dan aktivitas terbaru.
                    </p>

                </div>


                <div class="flex items-center gap-4">

                    <div class="hidden h-10 w-px bg-slate-200 sm:block"></div>

                    <div class="text-left sm:text-right">

                        <p class="text-xs font-medium text-slate-400">
                            Hari ini
                        </p>

                        <p class="mt-1 text-sm font-semibold text-slate-700">
                            {{ $tanggalHariIni }}
                        </p>

                    </div>

                </div>

            </div>

        </section>



        {{-- ============================================================
        KPI
    ============================================================ --}}
        <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">


            {{-- Proyek Aktif --}}
            <div class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-sm font-medium text-slate-500">
                            Proyek Aktif
                        </p>

                        <p class="mt-2 text-3xl font-semibold tracking-tight text-slate-900">
                            {{ number_format($totalProyekAktif) }}
                        </p>

                    </div>


                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">

                        <svg
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-7h6v7" />
                        </svg>

                    </div>

                </div>


                <div class="mt-4 flex items-center gap-2 text-xs text-slate-400">

                    <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>

                    Proyek dengan status berjalan

                </div>

            </div>

            {{-- Proyek Selesai --}}
            <div class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-sm font-medium text-slate-500">
                            Proyek Selesai
                        </p>

                        <p class="mt-2 text-3xl font-semibold tracking-tight text-slate-900">
                            {{ number_format($totalProyekSelesai) }}
                        </p>

                    </div>


                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">

                        <svg
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="m5 12 4 4L19 6" />
                        </svg>

                    </div>

                </div>


                <div class="mt-4 flex items-center gap-2 text-xs text-slate-400">

                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>

                    Proyek yang telah selesai

                </div>

            </div>



            {{-- Pegawai Aktif --}}
            <div class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-sm font-medium text-slate-500">
                            Pegawai Aktif
                        </p>

                        <p class="mt-2 text-3xl font-semibold tracking-tight text-slate-900">
                            {{ number_format($totalPegawai) }}
                        </p>

                    </div>


                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-50 text-violet-600">

                        <svg
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" />
                        </svg>

                    </div>

                </div>


                <div class="mt-4 flex items-center gap-2 text-xs text-slate-400">

                    <span class="h-1.5 w-1.5 rounded-full bg-violet-500"></span>

                    Pegawai dengan status aktif

                </div>

            </div>



            {{-- Menunggu Validasi --}}
            <div class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-sm font-medium text-slate-500">
                            Menunggu Validasi
                        </p>

                        <p class="mt-2 text-3xl font-semibold tracking-tight text-slate-900">
                            {{ number_format($totalPendingValidasi) }}
                        </p>

                    </div>


                    <div class="flex h-10 w-10 items-center justify-center rounded-xl
                    {{ $totalPendingValidasi > 0
                        ? 'bg-amber-50 text-amber-600'
                        : 'bg-slate-50 text-slate-500' }}">

                        <svg
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 8v4l2.5 2.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>

                    </div>

                </div>


                <div class="mt-4 flex items-center gap-2 text-xs
                {{ $totalPendingValidasi > 0
                    ? 'text-amber-600'
                    : 'text-slate-400' }}">

                    <span class="h-1.5 w-1.5 rounded-full
                    {{ $totalPendingValidasi > 0
                        ? 'bg-amber-500'
                        : 'bg-slate-300' }}"></span>

                    {{ $totalPendingValidasi > 0
                    ? 'Perlu segera diperiksa'
                    : 'Tidak ada laporan tertunda' }}

                </div>

            </div>

        </section>



        {{-- ============================================================
        PERLU DIPERHATIKAN
    ============================================================ --}}
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">


            {{-- Header --}}
            <div class="flex flex-col gap-3 border-b border-slate-100 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">

                <div class="flex items-start gap-3">

                    <div class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-xl
                    {{ $adaNotifikasi
                        ? 'bg-amber-50 text-amber-600'
                        : 'bg-slate-50 text-slate-400' }}">

                        <svg
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M10.3 4.2a2 2 0 0 1 3.4 0l7 12.1A2 2 0 0 1 19 19.3H5a2 2 0 0 1-1.7-3l7-12.1ZM12 9v4M12 16.5h.01" />
                        </svg>

                    </div>


                    <div>

                        <h2 class="text-base font-semibold text-slate-900">
                            Perlu Diperhatikan
                        </h2>

                        <p class="mt-0.5 text-sm text-slate-500">
                            Beberapa hal membutuhkan perhatian Anda.
                        </p>

                    </div>

                </div>


                @if($adaNotifikasi)

                <span class="inline-flex w-fit items-center rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">
                    {{ $jumlahPeringatan }} hal perlu dicek
                </span>

                @else

                <span class="inline-flex w-fit items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                    Semua aman
                </span>

                @endif

            </div>



            {{-- Notification Content --}}
            @if($adaNotifikasi)

            <div class="divide-y divide-slate-100">


                {{-- ====================================================
                    DANA KRITIS
                ===================================================== --}}
                @if($totalProyekKritis > 0)

                <a
                    href="{{ route('proyek.index') }}"
                    class="group flex items-center gap-4 bg-red-50/40 px-6 py-4 transition hover:bg-red-50">

                    <span class="h-2.5 w-2.5 shrink-0 rounded-full bg-red-500 ring-4 ring-red-50"></span>


                    <div class="min-w-0 flex-1">

                        <div class="flex flex-wrap items-center gap-x-2 gap-y-1">

                            <span class="text-sm font-semibold text-red-800">
                                {{ number_format($totalProyekKritis) }} proyek
                            </span>

                            <span class="text-sm text-red-700">
                                membutuhkan perhatian dana
                            </span>

                        </div>


                        <p class="mt-1 text-xs text-red-600/80">
                            Dana kritis, kas negatif, atau penggunaan RAB telah mencapai batas kritis.
                        </p>

                    </div>


                    <span class="shrink-0 text-xs font-semibold text-red-600 transition group-hover:translate-x-0.5">
                        Lihat proyek →
                    </span>

                </a>

                @endif



                {{-- ====================================================
                    DANA MENIPIS
                ===================================================== --}}
                @if($totalProyekDanaMenipis > 0)

                <a
                    href="{{ route('proyek.index') }}"
                    class="group flex items-center gap-4 bg-amber-50/40 px-6 py-4 transition hover:bg-amber-50">

                    <span class="h-2.5 w-2.5 shrink-0 rounded-full bg-amber-500 ring-4 ring-amber-50"></span>


                    <div class="min-w-0 flex-1">

                        <div class="flex flex-wrap items-center gap-x-2 gap-y-1">

                            <span class="text-sm font-semibold text-slate-800">
                                {{ number_format($totalProyekDanaMenipis) }} proyek
                            </span>

                            <span class="text-sm text-slate-600">
                                memiliki dana yang mulai menipis
                            </span>

                        </div>


                        <p class="mt-1 text-xs text-amber-700/80">
                            Pertimbangkan pengajuan termin berikutnya kepada client.
                        </p>

                    </div>


                    <span class="shrink-0 text-xs font-semibold text-amber-600 transition group-hover:translate-x-0.5">
                        Lihat proyek →
                    </span>

                </a>

                @endif



                {{-- ====================================================
                    ABSENSI
                ===================================================== --}}
                @if($totalPendingValidasi > 0)

                <a
                    href="{{ route('admin.absensi.index') }}"
                    class="group flex items-center gap-4 px-6 py-4 transition hover:bg-amber-50/40">

                    <span class="h-2.5 w-2.5 shrink-0 rounded-full bg-amber-500 ring-4 ring-amber-50"></span>


                    <div class="min-w-0 flex-1">

                        <div class="flex flex-wrap items-center gap-x-2 gap-y-1">

                            <span class="text-sm font-semibold text-slate-800">
                                {{ number_format($totalPendingValidasi) }} laporan absensi
                            </span>

                            <span class="text-sm text-slate-500">
                                menunggu validasi
                            </span>

                        </div>


                        <p class="mt-1 text-xs text-slate-400">
                            Periksa laporan sebelum disetujui.
                        </p>

                    </div>


                    <span class="shrink-0 text-xs font-semibold text-amber-600 transition group-hover:translate-x-0.5">
                        Periksa →
                    </span>

                </a>

                @endif



                {{-- ====================================================
                    PEGAWAI TANPA AKUN
                ===================================================== --}}
                @if($totalPegawaiTanpaAkun > 0)

                <a
                    href="{{ route('pegawai.index') }}"
                    class="group flex items-center gap-4 px-6 py-4 transition hover:bg-blue-50/40">

                    <span class="h-2.5 w-2.5 shrink-0 rounded-full bg-blue-500 ring-4 ring-blue-50"></span>


                    <div class="min-w-0 flex-1">

                        <div class="flex flex-wrap items-center gap-x-2 gap-y-1">

                            <span class="text-sm font-semibold text-slate-800">
                                {{ number_format($totalPegawaiTanpaAkun) }} pegawai
                            </span>

                            <span class="text-sm text-slate-500">
                                belum memiliki akun
                            </span>

                        </div>


                        <p class="mt-1 text-xs text-slate-400">
                            Buat akun jika pegawai tersebut membutuhkan akses sistem.
                        </p>

                    </div>


                    <span class="shrink-0 text-xs font-semibold text-blue-600 transition group-hover:translate-x-0.5">
                        Kelola →
                    </span>

                </a>

                @endif

            </div>


            @else

            {{-- Empty State --}}
            <div class="flex flex-col items-center justify-center px-6 py-10 text-center">

                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-50 text-emerald-600">

                    <svg
                        class="h-6 w-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="m5 12 4 4L19 6" />
                    </svg>

                </div>


                <h3 class="mt-3 text-sm font-semibold text-slate-800">
                    Tidak ada hal yang perlu diperhatikan
                </h3>


                <p class="mt-1 max-w-md text-xs text-slate-400">
                    Semua laporan, kondisi proyek, dan data pegawai saat ini dalam kondisi baik.
                </p>

            </div>

            @endif

        </section>



        {{-- ============================================================
        KESEHATAN PROYEK
    ============================================================ --}}
        <section>

            <div class="mb-4 flex items-end justify-between gap-4">

                <div>

                    <h2 class="text-lg font-semibold tracking-tight text-slate-900">
                        Kesehatan Proyek
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Ringkasan penggunaan anggaran dan kondisi dana proyek.
                    </p>

                </div>


                <a
                    href="{{ route('proyek.index') }}"
                    class="hidden text-sm font-medium text-blue-600 transition hover:text-blue-700 sm:block">
                    Lihat semua proyek →
                </a>

            </div>



            <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">

                @forelse($proyekKesehatan as $proyek)

                @php

                /*
                |--------------------------------------------------------------------------
                | Financial Data
                |--------------------------------------------------------------------------
                */

                $persentase = (float) ($proyek->persentase_pengeluaran ?? 0);

                $persentaseBar = max(
                0,
                min($persentase, 100)
                );

                $kasTersedia = (float) ($proyek->kas_tersedia ?? 0);

                $anggaran = (float) ($proyek->anggaran ?? 0);

                $totalPengeluaran = (float) ($proyek->total_pengeluaran ?? 0);

                $healthLevel = $proyek->health_level ?? 'neutral';


                /*
                |--------------------------------------------------------------------------
                | Color berdasarkan Health Level
                |--------------------------------------------------------------------------
                */

                switch ($healthLevel) {

                case 'danger':

                $progressClass = 'bg-red-500';
                $textClass = 'text-red-600';
                $badgeClass = 'bg-red-50 text-red-700';
                $dotClass = 'bg-red-500';

                break;


                case 'warning':

                $progressClass = 'bg-amber-500';
                $textClass = 'text-amber-600';
                $badgeClass = 'bg-amber-50 text-amber-700';
                $dotClass = 'bg-amber-500';

                break;


                case 'safe':

                $progressClass = 'bg-emerald-500';
                $textClass = 'text-emerald-600';
                $badgeClass = 'bg-emerald-50 text-emerald-700';
                $dotClass = 'bg-emerald-500';

                break;


                default:

                $progressClass = 'bg-slate-400';
                $textClass = 'text-slate-500';
                $badgeClass = 'bg-slate-100 text-slate-600';
                $dotClass = 'bg-slate-400';

                break;
                }

                @endphp



                {{-- ====================================================
                    PROJECT CARD
                ===================================================== --}}
                <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">

                    <a
                        href="{{ route('proyek.show', ['proyek' => $proyek->id]) }}">
                        {{-- Project Image --}}
                        <div class="relative h-24 overflow-hidden">

                            @if($proyek->gambar)

                            <img
                                src="{{ asset('storage/' . $proyek->gambar) }}"
                                alt="{{ $proyek->nama_proyek }}"
                                class="h-full w-full object-cover">

                            @else

                            <div class="h-full w-full bg-slate-100"></div>

                            @endif


                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/55 via-slate-950/10 to-transparent"></div>


                            <div class="absolute bottom-3 left-4 right-4 flex items-end justify-between gap-3">

                                <div class="min-w-0">

                                    <h3 class="truncate text-sm font-semibold text-white">
                                        {{ $proyek->nama_proyek }}
                                    </h3>

                                    <p class="mt-0.5 truncate text-xs text-white/75">
                                        {{ $proyek->lokasi }}
                                    </p>

                                </div>


                                <span class="shrink-0 rounded-full bg-white/90 px-2.5 py-1 text-[10px] font-semibold text-slate-700">
                                    Berjalan
                                </span>

                            </div>

                        </div>



                        {{-- Project Content --}}
                        <div class="p-5">


                            {{-- Health --}}
                            <div class="flex items-center justify-between gap-3">

                                <div class="flex items-center gap-2">

                                    <span class="h-2 w-2 rounded-full {{ $dotClass }}"></span>

                                    <span class="text-xs font-semibold uppercase tracking-wide {{ $textClass }}">
                                        {{ $proyek->health_status }}
                                    </span>

                                </div>


                                @if($persentase > 100)

                                <span class="rounded-full bg-red-50 px-2.5 py-1 text-[10px] font-bold text-red-700">
                                    Over Budget
                                </span>

                                @else

                                <span class="rounded-full px-2.5 py-1 text-[10px] font-semibold {{ $badgeClass }}">
                                    {{ number_format($persentase, 1) }}%
                                </span>

                                @endif

                            </div>



                            {{-- Financial Numbers --}}
                            <div class="mt-5 grid grid-cols-2 gap-4">

                                <div>

                                    <p class="text-xs text-slate-400">
                                        Dana Tersedia
                                    </p>

                                    <p class="mt-1 text-sm font-semibold
                                    {{ $kasTersedia < 0
                                        ? 'text-red-600'
                                        : 'text-slate-800' }}">
                                        Rp {{ number_format($kasTersedia, 0, ',', '.') }}
                                    </p>

                                </div>


                                <div class="text-right">

                                    <p class="text-xs text-slate-400">
                                        Terpakai
                                    </p>

                                    <p class="mt-1 text-sm font-semibold text-slate-800">
                                        Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                                    </p>

                                </div>

                            </div>



                            {{-- Progress --}}
                            <div class="mt-5">

                                <div class="mb-2 flex items-center justify-between">

                                    <span class="text-xs font-medium text-slate-500">
                                        Penggunaan RAB
                                    </span>


                                    <span class="text-xs font-semibold {{ $textClass }}">
                                        {{ number_format($persentase, 1) }}%
                                    </span>

                                </div>


                                <div class="h-2 w-full overflow-hidden rounded-full bg-slate-100">

                                    <div
                                        class="h-full rounded-full {{ $progressClass }} transition-all duration-700"
                                        style="width: {{ $persentaseBar }}%;"></div>

                                </div>

                            </div>

                            {{-- RAB --}}
                            <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-4">

                                <span class="text-xs text-slate-400">
                                    RAB Proyek
                                </span>


                                <span class="text-sm font-semibold text-slate-700">
                                    Rp {{ number_format($anggaran, 0, ',', '.') }}
                                </span>

                            </div>

                        </div>
                    </a>
                </article>


                @empty

                <div class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-12 text-center">

                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">

                        <svg
                            class="h-6 w-6"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3 21h18M5 21V7l7-4 7 4v14" />
                        </svg>

                    </div>


                    <h3 class="mt-3 text-sm font-semibold text-slate-800">
                        Belum ada proyek aktif
                    </h3>


                    <p class="mt-1 text-xs text-slate-400">
                        Proyek dengan status "Berjalan" akan muncul di sini.
                    </p>

                </div>

                @endforelse

            </div>

        </section>



        {{-- ============================================================
        PEGAWAI & AKTIVITAS
    ============================================================ --}}
        <section class="grid grid-cols-1 gap-5 xl:grid-cols-5">


            {{-- Pegawai --}}
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm xl:col-span-3">

                <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5">

                    <div>

                        <h2 class="text-base font-semibold text-slate-900">
                            Pegawai Terbaru
                        </h2>

                        <p class="mt-1 text-xs text-slate-400">
                            Data pegawai yang terakhir ditambahkan.
                        </p>

                    </div>


                    <a
                        href="{{ route('pegawai.index') }}"
                        class="text-xs font-semibold text-blue-600 hover:text-blue-700">
                        Lihat semua →
                    </a>

                </div>



                <div class="overflow-x-auto">

                    <table class="w-full min-w-[520px]">

                        <thead>

                            <tr class="border-b border-slate-100">

                                <th class="px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                                    Pegawai
                                </th>

                                <th class="px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                                    Jabatan
                                </th>

                                <th class="px-6 py-3 text-right text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                                    Status
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-slate-100">

                            @forelse($pegawais as $pegawai)

                            <tr class="transition hover:bg-slate-50/70">

                                <td class="px-6 py-4">

                                    <div class="flex items-center gap-3">

                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-slate-100 text-xs font-semibold text-slate-600">
                                            {{ strtoupper(substr($pegawai->nama, 0, 1)) }}
                                        </div>


                                        <div>

                                            <p class="text-sm font-medium text-slate-800">
                                                {{ $pegawai->nama }}
                                            </p>


                                            @if($pegawai->no_telp)

                                            <p class="mt-0.5 text-xs text-slate-400">
                                                {{ $pegawai->no_telp }}
                                            </p>

                                            @endif

                                        </div>

                                    </div>

                                </td>


                                <td class="px-6 py-4">

                                    <span class="text-sm text-slate-600">
                                        {{ $pegawai->jabatan->nama_jabatan ?? '-' }}
                                    </span>

                                </td>


                                <td class="px-6 py-4 text-right">

                                    @if($pegawai->status === 'aktif')

                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-600">

                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>

                                        Aktif

                                    </span>

                                    @else

                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-400">

                                        <span class="h-1.5 w-1.5 rounded-full bg-slate-300"></span>

                                        {{ ucfirst($pegawai->status) }}

                                    </span>

                                    @endif

                                </td>

                            </tr>

                            @empty

                            <tr>

                                <td
                                    colspan="3"
                                    class="px-6 py-10 text-center text-sm text-slate-400">
                                    Belum ada data pegawai.
                                </td>

                            </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>



            {{-- Aktivitas --}}
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm xl:col-span-2">

                <div class="border-b border-slate-100 px-6 py-5">

                    <h2 class="text-base font-semibold text-slate-900">
                        Aktivitas Terbaru
                    </h2>

                    <p class="mt-1 text-xs text-slate-400">
                        Aktivitas sistem terbaru.
                    </p>

                </div>


                @if($recentActivities->isNotEmpty())

                <div class="divide-y divide-slate-100">

                    @foreach($recentActivities as $activity)

                    <div class="flex gap-3 px-6 py-4">

                        <span class="mt-1.5 h-2 w-2 shrink-0 rounded-full bg-blue-500"></span>

                        <div>

                            <p class="text-sm text-slate-700">
                                {{ $activity->description }}
                            </p>

                            <p class="mt-1 text-xs text-slate-400">
                                {{ $activity->created_at }}
                            </p>

                        </div>

                    </div>

                    @endforeach

                </div>

                @else

                <div class="flex min-h-[220px] flex-col items-center justify-center px-6 text-center">

                    <div class="flex h-11 w-11 items-center justify-center rounded-full bg-slate-50 text-slate-400">

                        <svg
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 8v4l3 2M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>

                    </div>


                    <p class="mt-3 text-sm font-medium text-slate-600">
                        Belum ada aktivitas
                    </p>


                    <p class="mt-1 max-w-xs text-xs leading-5 text-slate-400">
                        Riwayat aktivitas sistem akan muncul setelah fitur activity log selesai dibuat.
                    </p>

                </div>

                @endif

            </div>

        </section>

    </div>

    @endsection