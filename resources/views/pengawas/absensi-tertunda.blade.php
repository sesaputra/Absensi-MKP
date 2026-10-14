@extends('layouts.mobile')

@section('title', 'Absensi Sore Tertunda')

@section('content')
<div class="min-h-screen bg-slate-100">
    <div class="mx-auto min-h-screen w-full max-w-md bg-slate-50 pb-24 shadow-sm md:max-w-3xl lg:max-w-6xl">

        {{-- HEADER --}}
        <header class="relative overflow-hidden rounded-b-[2rem] bg-[#0c2340] px-5 pb-7 pt-7 shadow-lg sm:px-6 lg:px-8">

            {{-- DEKORASI --}}
            <div class="pointer-events-none absolute -right-10 -top-10 h-32 w-32 rounded-full bg-white/5"></div>
            <div class="pointer-events-none absolute -bottom-16 -left-10 h-40 w-40 rounded-full bg-white/5"></div>

            <div class="relative">
                <a
                    href="{{ route('pengawas.dashboard') }}"
                    class="mb-5 inline-flex items-center gap-2 text-sm font-medium text-white/70 transition hover:text-white"
                >
                    <svg
                        class="h-4 w-4"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M15 19l-7-7 7-7"
                        />
                    </svg>

                    Kembali ke Dashboard
                </a>

                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-white/60">
                            Pengawas
                        </p>

                        <h1 class="mt-1 text-2xl font-bold tracking-tight text-white">
                            Absensi Sore Tertunda
                        </h1>

                        <p class="mt-2 max-w-md text-sm leading-relaxed text-white/60">
                            Selesaikan laporan sore yang belum lengkap agar dapat diperiksa oleh Admin.
                        </p>
                    </div>

                    {{-- TOTAL LAPORAN --}}
                    <div class="flex h-14 w-14 shrink-0 flex-col items-center justify-center rounded-2xl border border-white/10 bg-white/10">
                        <span class="text-xl font-bold leading-none text-white">
                            {{ $laporanTertunda->count() }}
                        </span>

                        <span class="mt-1 text-[9px] font-semibold uppercase tracking-wide text-white/60">
                            Laporan
                        </span>
                    </div>
                </div>
            </div>
        </header>

        <main class="px-5 pt-6 sm:px-6 lg:px-8 lg:pt-8">

            {{-- NOTIFIKASI SUKSES --}}
            @if(session('success'))
                <div class="mb-5 flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                        <svg
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="m5 12 4 4L19 6"
                            />
                        </svg>
                    </div>

                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-bold text-emerald-800">
                            Berhasil
                        </p>

                        <p class="mt-1 text-sm leading-relaxed text-emerald-700">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            @endif

            {{-- NOTIFIKASI ERROR --}}
            @if(session('error'))
                <div class="mb-5 flex items-start gap-3 rounded-2xl border border-rose-200 bg-rose-50 p-4">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-rose-100 text-rose-700">
                        <svg
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 8v4m0 4h.01M10.3 3.9 2.5 17.5A2 2 0 004.2 20h15.6a2 2 0 001.7-2.5L13.7 3.9a2 2 0 00-3.4 0z"
                            />
                        </svg>
                    </div>

                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-bold text-rose-800">
                            Terjadi Kesalahan
                        </p>

                        <p class="mt-1 text-sm leading-relaxed text-rose-700">
                            {{ session('error') }}
                        </p>
                    </div>
                </div>
            @endif

            {{-- VALIDASI FORM --}}
            @if($errors->any())
                <div class="mb-5 rounded-2xl border border-rose-200 bg-rose-50 p-4">
                    <div class="flex items-start gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-rose-100 text-rose-700">
                            <svg
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 8v4m0 4h.01"
                                />
                                <circle cx="12" cy="12" r="9"/>
                            </svg>
                        </div>

                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-bold text-rose-800">
                                Periksa Kembali Data Anda
                            </p>

                            <ul class="mt-2 list-inside list-disc space-y-1 text-sm leading-relaxed text-rose-700">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            {{-- RINGKASAN --}}
            @php
                $totalTertunda = $laporanTertunda->count();

                $totalDitolak = $laporanTertunda
                    ->where('status_validasi', 'Ditolak')
                    ->count();

                $totalBelumLengkap = $totalTertunda - $totalDitolak;
            @endphp

            <section class="mb-7 grid grid-cols-2 gap-3 sm:gap-4">

                {{-- TOTAL TERTUNDA --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
                    <div class="flex items-center gap-2 text-slate-500">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-50 text-amber-700">
                            <svg
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <circle cx="12" cy="12" r="9"/>
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 7v5l3 2"
                                />
                            </svg>
                        </div>

                        <span class="text-xs font-semibold">
                            Laporan Tertunda
                        </span>
                    </div>

                    <p class="mt-4 text-2xl font-bold text-slate-800">
                        {{ $totalTertunda }}
                    </p>

                    <p class="mt-1 text-xs leading-relaxed text-slate-500">
                        Perlu diselesaikan
                    </p>
                </div>

                {{-- LAPORAN DITOLAK --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
                    <div class="flex items-center gap-2 text-slate-500">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-rose-50 text-rose-700">
                            <svg
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <circle cx="12" cy="12" r="9"/>
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="m9 9 6 6m0-6-6 6"
                                />
                            </svg>
                        </div>

                        <span class="text-xs font-semibold">
                            Perlu Diperbaiki
                        </span>
                    </div>

                    <p class="mt-4 text-2xl font-bold text-slate-800">
                        {{ $totalDitolak }}
                    </p>

                    <p class="mt-1 text-xs leading-relaxed text-slate-500">
                        Laporan ditolak Admin
                    </p>
                </div>
            </section>

            {{-- JUDUL DAFTAR --}}
            <section class="mb-4">
                <div class="flex items-end justify-between gap-3">
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">
                            Daftar Laporan
                        </h2>

                        <p class="mt-1 text-xs leading-relaxed text-slate-500">
                            Laporan sore yang membutuhkan tindak lanjut.
                        </p>
                    </div>

                    <span class="shrink-0 rounded-full bg-slate-200/70 px-3 py-1.5 text-xs font-bold text-slate-600">
                        {{ $totalTertunda }} laporan
                    </span>
                </div>
            </section>

            {{-- DAFTAR LAPORAN --}}
            <section class="space-y-4">
                @forelse($laporanTertunda as $laporan)

                    @php
                        $statusDitolak = $laporan->status_validasi === 'Ditolak';

                        $statusClass = $statusDitolak
                            ? 'bg-rose-50 text-rose-700 ring-rose-200'
                            : 'bg-amber-50 text-amber-700 ring-amber-200';

                        $statusLabel = $statusDitolak
                            ? 'Perlu Diperbaiki'
                            : 'Belum Lengkap';

                        $jumlahPegawai = $laporan->absensis->count();

                        $fotoSoreTersedia = filled($laporan->foto_sore);
                    @endphp

                    <article class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:border-slate-300 hover:shadow-md">

                        {{-- INFORMASI PROYEK --}}
                        <div class="p-4 sm:p-5">
                            <div class="flex items-start justify-between gap-3">

                                <div class="min-w-0 flex-1">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                        {{ \Carbon\Carbon::parse($laporan->tanggal)->translatedFormat('l, d F Y') }}
                                    </p>

                                    <h3 class="mt-1 break-words text-base font-bold leading-snug text-slate-800">
                                        {{ $laporan->proyek?->nama_proyek ?? 'Proyek tidak tersedia' }}
                                    </h3>

                                    <p class="mt-1 text-xs leading-relaxed text-slate-500">
                                        Laporan absensi sore
                                    </p>
                                </div>

                                <span class="inline-flex max-w-[48%] shrink-0 items-center gap-1.5 rounded-full px-2.5 py-1.5 text-[10px] font-bold ring-1 sm:text-[11px] {{ $statusClass }}">
                                    @if($statusDitolak)
                                        <svg
                                            class="h-3.5 w-3.5 shrink-0"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="m9 9 6 6m0-6-6 6"
                                            />
                                        </svg>
                                    @else
                                        <svg
                                            class="h-3.5 w-3.5 shrink-0"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <circle cx="12" cy="12" r="9"/>
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M12 7v5l3 2"
                                            />
                                        </svg>
                                    @endif

                                    <span>{{ $statusLabel }}</span>
                                </span>
                            </div>

                            {{-- RINGKASAN DETAIL --}}
                            <div class="mt-5 grid grid-cols-2 gap-3">

                                {{-- JUMLAH PEGAWAI --}}
                                <div class="rounded-xl border border-slate-100 bg-slate-50 p-3">
                                    <div class="flex items-center gap-2 text-slate-400">
                                        <svg
                                            class="h-4 w-4 shrink-0"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2m16 0v-2a4 4 0 0 0-3-3.87M14 3.13a4 4 0 0 1 0 7.75M14 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z"
                                            />
                                        </svg>

                                        <span class="text-[10px] font-semibold uppercase tracking-wide">
                                            Pegawai
                                        </span>
                                    </div>

                                    <p class="mt-2 text-sm font-bold text-slate-800">
                                        {{ $jumlahPegawai }} orang
                                    </p>

                                    <p class="mt-1 text-[11px] text-slate-500">
                                        Tercatat pada laporan
                                    </p>
                                </div>

                                {{-- FOTO SORE --}}
                                <div class="rounded-xl border border-slate-100 bg-slate-50 p-3">
                                    <div class="flex items-center gap-2 text-slate-400">
                                        <svg
                                            class="h-4 w-4 shrink-0"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                        >
                                            <rect x="3" y="5" width="18" height="15" rx="2"/>
                                            <circle cx="8.5" cy="10.5" r="1.5"/>
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="m21 15-5-5L5 20"
                                            />
                                        </svg>

                                        <span class="text-[10px] font-semibold uppercase tracking-wide">
                                            Foto Sore
                                        </span>
                                    </div>

                                    <p class="mt-2 text-sm font-bold {{ $fotoSoreTersedia ? 'text-emerald-700' : 'text-amber-700' }}">
                                        {{ $fotoSoreTersedia ? 'Sudah tersedia' : 'Belum tersedia' }}
                                    </p>

                                    <p class="mt-1 text-[11px] text-slate-500">
                                        Bukti kegiatan sore
                                    </p>
                                </div>
                            </div>

                            {{-- INFORMASI PENOLAKAN --}}
                            @if($statusDitolak)
                                <div class="mt-4 rounded-xl border border-rose-100 bg-rose-50 p-3">
                                    <div class="flex items-start gap-2.5">
                                        <svg
                                            class="mt-0.5 h-4 w-4 shrink-0 text-rose-600"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M12 8v4m0 4h.01M10.3 3.9 2.5 17.5A2 2 0 0 0 4.2 20h15.6a2 2 0 0 0 1.7-2.5L13.7 3.9a2 2 0 0 0-3.4 0Z"
                                            />
                                        </svg>

                                        <div>
                                            <p class="text-xs font-bold text-rose-800">
                                                Laporan Perlu Diperbaiki
                                            </p>

                                            <p class="mt-1 text-xs leading-relaxed text-rose-700">
                                                Laporan ini ditolak Admin. Periksa kembali data dan lengkapi informasi yang diperlukan sebelum mengirim ulang.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- TOMBOL AKSI --}}
                        <div class="border-t border-slate-100 bg-slate-50/70 p-4 sm:px-5">
                            <a
                                href="{{ route('pengawas.absensi.sore-susulan.edit', [
                                    'proyek' => $laporan->proyek_id,
                                    'laporan' => $laporan->id,
                                ]) }}"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#0c2340] px-4 py-3 text-sm font-semibold text-white transition hover:bg-[#102d52] active:scale-[0.99] focus:outline-none focus:ring-2 focus:ring-[#0c2340]/30 focus:ring-offset-2"
                            >
                                <svg
                                    class="h-4 w-4"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M12 20h9M16.5 3.5a2.12 2.12 0 0 1 3 3L9 17l-4 1 1-4Z"
                                    />
                                </svg>

                                {{ $statusDitolak ? 'Perbaiki Laporan' : 'Lengkapi Laporan' }}

                                <svg
                                    class="h-4 w-4"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="m9 18 6-6-6-6"
                                    />
                                </svg>
                            </a>
                        </div>
                    </article>

                @empty

                    {{-- EMPTY STATE --}}
                    <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-5 py-12 text-center sm:px-8">

                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600">
                            <svg
                                class="h-7 w-7"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="m5 12 4 4L19 6"
                                />
                            </svg>
                        </div>

                        <h3 class="mt-4 text-sm font-bold text-slate-800">
                            Tidak Ada Laporan Tertunda
                        </h3>

                        <p class="mx-auto mt-2 max-w-sm text-xs leading-relaxed text-slate-500">
                            Semua laporan sore telah diselesaikan atau dikirim untuk diperiksa Admin. Saat ini tidak ada laporan yang memerlukan tindak lanjut.
                        </p>

                        <a
                            href="{{ route('pengawas.dashboard') }}"
                            class="mt-5 inline-flex items-center justify-center gap-2 rounded-xl bg-[#0c2340] px-4 py-3 text-xs font-semibold text-white transition hover:bg-[#102d52] focus:outline-none focus:ring-2 focus:ring-[#0c2340]/20 focus:ring-offset-2"
                        >
                            <svg
                                class="h-4 w-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M15 19l-7-7 7-7"
                                />
                            </svg>

                            Kembali ke Dashboard
                        </a>
                    </div>

                @endforelse
            </section>

            {{-- FOOTER --}}
            @if($totalTertunda > 0)
                <p class="mt-6 text-center text-xs leading-relaxed text-slate-400">
                    Pastikan seluruh informasi dan foto kegiatan sudah sesuai sebelum mengirim laporan.
                </p>
            @endif

        </main>
    </div>
</div>
@endsection