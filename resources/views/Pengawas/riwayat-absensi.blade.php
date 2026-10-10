
@extends('layouts.mobile')

@section('title', 'Riwayat Absensi')

@section('content')
<div class="min-h-screen bg-slate-100">
    <div class="mx-auto min-h-screen w-full max-w-md bg-slate-50 pb-24 shadow-sm md:max-w-3xl lg:max-w-6xl">

        {{-- HEADER --}}
        <header class="relative overflow-hidden rounded-b-[2rem] bg-[#0c2340] px-5 pb-7 pt-7 shadow-lg sm:px-6 lg:px-8">
            <div class="pointer-events-none absolute -right-10 -top-10 h-32 w-32 rounded-full bg-white/5"></div>
            <div class="pointer-events-none absolute -bottom-16 -left-10 h-40 w-40 rounded-full bg-white/5"></div>

            <div class="relative">
                <a
                    href="{{ route('pengawas.dashboard') }}"
                    class="mb-5 inline-flex items-center gap-2 text-sm font-medium text-white/70 transition hover:text-white"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke Dashboard
                </a>

                <p class="text-sm font-medium text-white/60">Pengawas</p>

                <h1 class="mt-1 text-2xl font-bold tracking-tight text-white">
                    Riwayat Absensi
                </h1>

                <p class="mt-1 max-w-md text-sm leading-relaxed text-white/60">
                    Pantau kehadiran dan laporan harian seluruh pegawai pada proyek yang Anda tangani.
                </p>
            </div>
        </header>

        <main class="px-5 pt-6 sm:px-6 lg:px-8 lg:pt-8">

            {{-- RINGKASAN --}}
            <section class="mb-6 grid grid-cols-2 gap-3">
                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                    <div class="flex items-center gap-2 text-slate-500">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2m16 0v-2a4 4 0 00-3-3.87M14 3.13a4 4 0 010 7.75M14 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span class="text-xs font-semibold">Total Laporan</span>
                    </div>

                    <p class="mt-3 text-2xl font-bold text-slate-800">
                        {{ $laporan->total() }}
                    </p>

                    <p class="mt-1 text-xs text-slate-500">
                        Sesuai filter yang dipilih
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                    <div class="flex items-center gap-2 text-slate-500">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M5 7l1 13h12l1-13M9 7V4h6v3" />
                        </svg>
                        <span class="text-xs font-semibold">Proyek Ditangani</span>
                    </div>

                    <p class="mt-3 text-2xl font-bold text-slate-800">
                        {{ $proyek->count() }}
                    </p>

                    <p class="mt-1 text-xs text-slate-500">
                        Proyek dalam penugasan
                    </p>
                </div>
            </section>

            {{-- FILTER --}}
            <section class="mb-6 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
                <div class="mb-4">
                    <h2 class="text-sm font-bold text-slate-800">
                        Filter Riwayat
                    </h2>

                    <p class="mt-1 text-xs leading-relaxed text-slate-500">
                        Cari laporan berdasarkan tanggal, bulan, atau proyek.
                    </p>
                </div>

                <form
                    method="GET"
                    action="{{ route('pengawas.riwayat.absensi') }}"
                    class="space-y-4"
                >
                    {{-- TANGGAL --}}
                    <div>
                        <label for="tanggal" class="mb-1.5 block text-xs font-semibold text-slate-600">
                            Tanggal
                        </label>

                        <input
                            type="date"
                            id="tanggal"
                            name="tanggal"
                            value="{{ request('tanggal') }}"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-[#0c2340] focus:ring-2 focus:ring-[#0c2340]/10"
                        >
                    </div>

                    {{-- BULAN --}}
                    <div>
                        <label for="bulan" class="mb-1.5 block text-xs font-semibold text-slate-600">
                            Bulan
                        </label>

                        <input
                            type="month"
                            id="bulan"
                            name="bulan"
                            value="{{ request('bulan') }}"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-[#0c2340] focus:ring-2 focus:ring-[#0c2340]/10"
                        >
                    </div>

                    {{-- PROYEK --}}
                    <div>
                        <label for="proyek_id" class="mb-1.5 block text-xs font-semibold text-slate-600">
                            Proyek
                        </label>

                        <select
                            id="proyek_id"
                            name="proyek_id"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-[#0c2340] focus:ring-2 focus:ring-[#0c2340]/10"
                        >
                            <option value="">Semua Proyek</option>

                            @foreach($proyek as $proyekItem)
                                <option
                                    value="{{ $proyekItem->id }}"
                                    @selected((string) request('proyek_id') === (string) $proyekItem->id)
                                >
                                    {{ $proyekItem->nama_proyek }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- TOMBOL FILTER --}}
                    <div class="flex flex-col gap-2 pt-1 sm:flex-row">
                        <button
                            type="submit"
                            class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl bg-[#0c2340] px-4 py-3 text-sm font-semibold text-white transition hover:bg-[#102d52] active:scale-[0.98]"
                        >
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="7" />
                                <path stroke-linecap="round" d="m16 16 4 4" />
                            </svg>
                            Cari Riwayat
                        </button>

                        @if(request()->hasAny(['tanggal', 'bulan', 'proyek_id']))
                            <a
                                href="{{ route('pengawas.riwayat.absensi') }}"
                                class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50"
                            >
                                Reset Filter
                            </a>
                        @endif
                    </div>
                </form>
            </section>

            {{-- HEADER LIST --}}
            <div class="mb-4 flex items-end justify-between gap-3">
                <div>
                    <h2 class="text-lg font-bold text-slate-800">
                        Riwayat Absensi Pegawai
                    </h2>

                    <p class="mt-1 text-xs text-slate-500">
                        {{ $laporan->total() }} laporan ditemukan
                    </p>
                </div>
            </div>

            {{-- LIST RIWAYAT --}}
            <section class="space-y-4">
                @forelse($laporan as $item)
                    @php
                        $statusValidasi = $item->status_validasi;

                        $statusClass = match($statusValidasi) {
                            'Disetujui' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
                            'Ditolak' => 'bg-rose-50 text-rose-700 ring-rose-200',
                            'Menunggu Persetujuan' => 'bg-blue-50 text-blue-700 ring-blue-200',
                            'Draft Pagi' => 'bg-amber-50 text-amber-700 ring-amber-200',
                            default => 'bg-slate-50 text-slate-600 ring-slate-200',
                        };

                        $statusIcon = match($statusValidasi) {
                            'Disetujui' => '✓',
                            'Ditolak' => '!',
                            'Menunggu Persetujuan' => '◷',
                            'Draft Pagi' => '◷',
                            default => '•',
                        };

                        $modalId = 'modal-absensi-' . $item->id;
                    @endphp

                    {{-- KARTU LAPORAN --}}
                    <article class="group rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:border-slate-300 hover:shadow-md sm:p-5">

                        {{-- TANGGAL DAN PROYEK --}}
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('l, d F Y') }}
                                </p>

                                <h3 class="mt-1 text-base font-bold leading-snug text-slate-800">
                                    {{ $item->proyek?->nama_proyek ?? 'Proyek tidak tersedia' }}
                                </h3>
                            </div>

                            <span class="inline-flex max-w-[48%] shrink-0 items-center gap-1.5 rounded-full px-2.5 py-1.5 text-[10px] font-bold ring-1 sm:text-[11px] {{ $statusClass }}">
                                <span>{{ $statusIcon }}</span>
                                <span>{{ $statusValidasi ?? 'Tidak diketahui' }}</span>
                            </span>
                        </div>

                        {{-- DAFTAR KEHADIRAN PEGAWAI --}}
                        <div class="mt-5 border-t border-slate-100 pt-4">
                            <div class="mb-3 flex items-center justify-between gap-2">
                                <h4 class="text-xs font-bold uppercase tracking-wide text-slate-500">
                                    Daftar Kehadiran Pegawai
                                </h4>

                                <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                                    {{ $item->absensis->count() }} pegawai
                                </span>
                            </div>

                            <div class="space-y-3">
                                @forelse($item->absensis as $absensi)
                                    @php
                                        $namaPegawai = $absensi->pegawai?->nama
                                            ?? 'Pegawai tidak diketahui';

                                        $namaJabatan = $absensi->pegawai?->jabatan?->nama_jabatan
                                            ?? 'Jabatan tidak tersedia';

                                        $inisial = \Illuminate\Support\Str::upper(
                                            \Illuminate\Support\Str::substr($namaPegawai, 0, 1)
                                        );

                                        $statusKehadiranClass = match($absensi->status) {
                                            'Hadir' => 'bg-emerald-50 text-emerald-700',
                                            'Izin', 'Sakit' => 'bg-amber-50 text-amber-700',
                                            'Alpa' => 'bg-rose-50 text-rose-700',
                                            default => 'bg-slate-100 text-slate-600',
                                        };

                                        $statusValidasiPegawai = $absensi->status_validasi;

                                        $validasiPegawaiClass = match($statusValidasiPegawai) {
                                            'Disetujui' => 'bg-emerald-50 text-emerald-700',
                                            'Ditolak' => 'bg-rose-50 text-rose-700',
                                            default => 'bg-amber-50 text-amber-700',
                                        };
                                    @endphp

                                    <div class="rounded-xl border border-slate-200 bg-white p-3 sm:p-4">

                                        {{-- IDENTITAS PEGAWAI --}}
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#0c2340]/10 text-sm font-bold text-[#0c2340]">
                                                {{ $inisial }}
                                            </div>

                                            <div class="min-w-0 flex-1">
                                                <p class="truncate text-sm font-bold text-slate-800">
                                                    {{ $namaPegawai }}
                                                </p>

                                                <p class="mt-0.5 text-xs text-slate-500">
                                                    {{ $namaJabatan }}
                                                </p>
                                            </div>

                                            <span class="shrink-0 rounded-full px-2.5 py-1 text-[11px] font-bold {{ $statusKehadiranClass }}">
                                                {{ $absensi->status ?? 'Belum tersedia' }}
                                            </span>
                                        </div>

                                        {{-- DURASI DAN VALIDASI --}}
                                        <div class="mt-3 grid grid-cols-2 gap-2">
                                            <div class="rounded-lg bg-slate-50 p-3">
                                                <p class="text-[10px] font-semibold uppercase tracking-wide text-slate-400">
                                                    Durasi
                                                </p>

                                                <p class="mt-1 text-sm font-bold text-slate-700">
                                                    {{ $absensi->durasi ?? '-' }}
                                                </p>
                                            </div>

                                            <div class="rounded-lg bg-slate-50 p-3">
                                                <p class="text-[10px] font-semibold uppercase tracking-wide text-slate-400">
                                                    Validasi Absensi
                                                </p>

                                                <span class="mt-1 inline-block rounded-full px-2 py-1 text-[11px] font-semibold {{ $validasiPegawaiClass }}">
                                                    {{ $statusValidasiPegawai ?? 'Belum tersedia' }}
                                                </span>
                                            </div>
                                        </div>

                                        {{-- KETERANGAN --}}
                                        @if($absensi->keterangan)
                                            <div class="mt-3 rounded-lg border border-slate-100 px-3 py-2">
                                                <p class="text-[10px] font-semibold uppercase tracking-wide text-slate-400">
                                                    Keterangan
                                                </p>

                                                <p class="mt-1 text-xs leading-relaxed text-slate-600">
                                                    {{ $absensi->keterangan }}
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                @empty
                                    <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-6 text-center">
                                        <p class="text-sm font-semibold text-slate-600">
                                            Belum ada data absensi pegawai
                                        </p>

                                        <p class="mt-1 text-xs text-slate-500">
                                            Belum ada pegawai yang tercatat pada laporan ini.
                                        </p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        {{-- INFORMASI PENOLAKAN LAPORAN --}}
                        @if($statusValidasi === 'Ditolak')
                            <div class="mt-4 rounded-xl border border-rose-100 bg-rose-50 px-3 py-2.5">
                                <p class="text-xs font-medium leading-relaxed text-rose-700">
                                    Laporan ini ditolak dan perlu diperiksa kembali.
                                </p>
                            </div>
                        @endif

                        {{-- TOMBOL DETAIL --}}
                        <div class="mt-4 flex justify-end border-t border-slate-100 pt-3">
                            <button
                                type="button"
                                onclick="document.getElementById('{{ $modalId }}').showModal()"
                                class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-bold text-[#0c2340] transition hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-[#0c2340]/20"
                            >
                                Lihat Detail

                                <svg class="h-4 w-4 transition group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </article>

                    {{-- MODAL DETAIL LAPORAN --}}
                    <dialog
                        id="{{ $modalId }}"
                        class="m-auto w-[calc(100%-2rem)] max-w-lg overflow-hidden rounded-2xl bg-white p-0 text-left shadow-2xl backdrop:bg-slate-950/60"
                    >
                        {{-- MODAL HEADER --}}
                        <div class="flex items-start justify-between gap-4 bg-[#0c2340] px-5 py-5">
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-semibold uppercase tracking-wider text-white/60">
                                    Detail Riwayat Absensi
                                </p>

                                <h2 class="mt-1 text-lg font-bold text-white">
                                    {{ $item->proyek?->nama_proyek ?? 'Proyek tidak tersedia' }}
                                </h2>

                                <p class="mt-2 text-xs text-white/70">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('l, d F Y') }}
                                </p>
                            </div>

                            <button
                                type="button"
                                onclick="this.closest('dialog').close()"
                                aria-label="Tutup modal"
                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-white/10 text-xl text-white transition hover:bg-white/20"
                            >
                                &times;
                            </button>
                        </div>

                        {{-- MODAL CONTENT --}}
                        <div class="max-h-[65vh] space-y-5 overflow-y-auto p-5">

                            {{-- STATUS VALIDASI LAPORAN --}}
                            <div>
                                <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-slate-400">
                                    Status Validasi Laporan
                                </p>

                                <span class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-xs font-bold ring-1 {{ $statusClass }}">
                                    <span>{{ $statusIcon }}</span>
                                    {{ $statusValidasi ?? 'Tidak diketahui' }}
                                </span>
                            </div>

                            {{-- SELURUH PEGAWAI DALAM MODAL --}}
                            <div>
                                <div class="mb-3 flex items-center justify-between gap-2">
                                    <h3 class="text-xs font-bold uppercase tracking-wide text-slate-400">
                                        Rincian Kehadiran Pegawai
                                    </h3>

                                    <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                                        {{ $item->absensis->count() }} pegawai
                                    </span>
                                </div>

                                <div class="space-y-3">
                                    @forelse($item->absensis as $absensiDetail)
                                        @php
                                            $namaDetail = $absensiDetail->pegawai?->nama
                                                ?? 'Pegawai tidak diketahui';

                                            $jabatanDetail = $absensiDetail->pegawai?->jabatan?->nama_jabatan
                                                ?? 'Jabatan tidak tersedia';

                                            $statusDetailClass = match($absensiDetail->status) {
                                                'Hadir' => 'bg-emerald-50 text-emerald-700',
                                                'Izin', 'Sakit' => 'bg-amber-50 text-amber-700',
                                                'Alpa' => 'bg-rose-50 text-rose-700',
                                                default => 'bg-slate-100 text-slate-600',
                                            };

                                            $validasiDetailClass = match($absensiDetail->status_validasi) {
                                                'Disetujui' => 'bg-emerald-50 text-emerald-700',
                                                'Ditolak' => 'bg-rose-50 text-rose-700',
                                                default => 'bg-amber-50 text-amber-700',
                                            };
                                        @endphp

                                        <div class="rounded-xl border border-slate-200 p-4">
                                            <div class="flex items-start justify-between gap-3">
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-sm font-bold text-slate-800">
                                                        {{ $namaDetail }}
                                                    </p>

                                                    <p class="mt-1 text-xs text-slate-500">
                                                        {{ $jabatanDetail }}
                                                    </p>
                                                </div>

                                                <span class="shrink-0 rounded-full px-2.5 py-1 text-xs font-bold {{ $statusDetailClass }}">
                                                    {{ $absensiDetail->status ?? 'Belum tersedia' }}
                                                </span>
                                            </div>

                                            <div class="mt-4 grid grid-cols-2 gap-3">
                                                <div class="rounded-lg bg-slate-50 p-3">
                                                    <p class="text-xs text-slate-500">
                                                        Durasi Kehadiran
                                                    </p>

                                                    <p class="mt-1 break-words text-sm font-bold text-slate-800">
                                                        {{ $absensiDetail->durasi ?? '-' }}
                                                    </p>
                                                </div>

                                                <div class="rounded-lg bg-slate-50 p-3">
                                                    <p class="text-xs text-slate-500">
                                                        Validasi Absensi
                                                    </p>

                                                    <span class="mt-1 inline-block rounded-full px-2 py-1 text-[11px] font-semibold {{ $validasiDetailClass }}">
                                                        {{ $absensiDetail->status_validasi ?? 'Belum tersedia' }}
                                                    </span>
                                                </div>
                                            </div>

                                            @if($absensiDetail->keterangan)
                                                <div class="mt-3 rounded-lg border border-slate-100 p-3">
                                                    <p class="text-xs font-semibold text-slate-500">
                                                        Keterangan
                                                    </p>

                                                    <p class="mt-1 text-sm leading-relaxed text-slate-700">
                                                        {{ $absensiDetail->keterangan }}
                                                    </p>
                                                </div>
                                            @endif
                                        </div>
                                    @empty
                                        <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 p-4 text-center">
                                            <p class="text-sm text-slate-600">
                                                Belum ada data kehadiran pegawai.
                                            </p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            {{-- KEGIATAN HARIAN --}}
                            <div>
                                <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-slate-400">
                                    Kegiatan Hari Ini
                                </p>

                                <div class="rounded-xl bg-slate-50 p-4 text-sm leading-6 text-slate-700">
                                    {{ $item->kegiatan ?: 'Tidak ada keterangan kegiatan.' }}
                                </div>
                            </div>

                            {{-- FOTO PAGI --}}
                            @if($item->foto_pagi)
                                <div>
                                    <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-slate-400">
                                        Foto Absensi Pagi
                                    </p>

                                    <a
                                        href="{{ asset('storage/' . $item->foto_pagi) }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="block"
                                    >
                                        <img
                                            src="{{ asset('storage/' . $item->foto_pagi) }}"
                                            alt="Foto absensi pagi"
                                            loading="lazy"
                                            class="max-h-72 w-full rounded-xl border border-slate-200 object-cover"
                                        >
                                    </a>
                                </div>
                            @endif

                            {{-- FOTO SORE --}}
                            @if($item->foto_sore)
                                <div>
                                    <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-slate-400">
                                        Foto Absensi Sore
                                    </p>

                                    <a
                                        href="{{ asset('storage/' . $item->foto_sore) }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="block"
                                    >
                                        <img
                                            src="{{ asset('storage/' . $item->foto_sore) }}"
                                            alt="Foto absensi sore"
                                            loading="lazy"
                                            class="max-h-72 w-full rounded-xl border border-slate-200 object-cover"
                                        >
                                    </a>
                                </div>
                            @endif

                            {{-- INFORMASI PENOLAKAN --}}
                            @if($statusValidasi === 'Ditolak')
                                <div class="rounded-xl border border-rose-100 bg-rose-50 p-4">
                                    <p class="text-sm font-semibold text-rose-800">
                                        Laporan perlu diperbaiki
                                    </p>

                                    <p class="mt-1 text-xs leading-5 text-rose-700">
                                        Silakan periksa kembali laporan ini sesuai arahan admin.
                                    </p>
                                </div>
                            @endif
                        </div>

                        {{-- MODAL FOOTER --}}
                        <div class="border-t border-slate-100 bg-white p-4">
                            <button
                                type="button"
                                onclick="this.closest('dialog').close()"
                                class="w-full rounded-xl bg-[#0c2340] px-4 py-3 text-sm font-semibold text-white transition hover:bg-[#102d52]"
                            >
                                Tutup Detail
                            </button>
                        </div>
                    </dialog>
                @empty
                    {{-- EMPTY STATE --}}
                    <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-5 py-12 text-center">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2m16 0v-2a4 4 0 00-3-3.87M14 3.13a4 4 0 010 7.75M14 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>

                        <h3 class="mt-4 text-sm font-bold text-slate-700">
                            Belum Ada Riwayat
                        </h3>

                        <p class="mx-auto mt-2 max-w-xs text-xs leading-relaxed text-slate-500">
                            Belum ditemukan laporan absensi pada proyek yang Anda tangani berdasarkan filter yang dipilih.
                        </p>

                        @if(request()->hasAny(['tanggal', 'bulan', 'proyek_id']))
                            <a
                                href="{{ route('pengawas.riwayat.absensi') }}"
                                class="mt-4 inline-flex items-center justify-center rounded-xl bg-[#0c2340] px-4 py-2.5 text-xs font-semibold text-white transition hover:bg-[#102d52]"
                            >
                                Tampilkan Semua Riwayat
                            </a>
                        @endif
                    </div>
                @endforelse
            </section>

            {{-- PAGINATION --}}
            @if($laporan->hasPages())
                <div class="mt-6">
                    {{ $laporan->links() }}
                </div>
            @endif
        </main>
    </div>
</div>
@endsection