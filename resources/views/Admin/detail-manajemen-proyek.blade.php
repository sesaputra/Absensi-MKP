@extends('layouts.admin')

@section('title', 'Detail Proyek')

@section('content')

<!-- Notifikasi Pesan Sukses -->
@if(session('success'))
<div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl relative flex items-center justify-between shadow-sm" role="alert">
    <div class="flex items-center">
        <svg class="w-5 h-5 mr-2.5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span class="text-sm font-semibold">{{ session('success') }}</span>
    </div>
    <button type="button" onclick="this.parentElement.remove()" class="text-green-500 hover:text-green-700 p-1" aria-label="Tutup notifikasi">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>
</div>
@endif

<!-- ============================================== -->
<!-- HEADER PROYEK -->
<!-- ============================================== -->
{{-- =========================================================
    DETAIL PROYEK
========================================================= --}}

<div class="space-y-6">

    {{-- =====================================================
        HERO DETAIL PROYEK
    ====================================================== --}}
    <section
        class="relative isolate overflow-hidden rounded-[30px] bg-slate-950 text-white shadow-xl shadow-slate-200/50">

        {{-- Background Image --}}
        @if($proyek->gambar)
        <div
            class="absolute inset-0 -z-20 bg-cover bg-center opacity-[0.24]"
            style="background-image: url('{{ asset('storage/' . $proyek->gambar) }}');"></div>
        @else
        <div
            class="absolute inset-0 -z-20 bg-cover bg-center opacity-[0.18]"
            style="background-image: url('{{ asset('images/flower-mkp.jpg') }}');"></div>
        @endif

        {{-- Main Gradient --}}
        <div
            class="absolute inset-0 -z-10 bg-gradient-to-br from-slate-950 via-slate-950/[0.96] to-blue-950/[0.92]"></div>

        {{-- Decorative Glow --}}
        <div
            class="absolute -right-24 -top-24 -z-10 h-80 w-80 rounded-full bg-blue-500/15 blur-3xl"></div>

        <div
            class="absolute -bottom-40 left-1/3 -z-10 h-80 w-80 rounded-full bg-indigo-500/10 blur-3xl"></div>

        {{-- Subtle Grid --}}
        <div
            class="absolute inset-0 -z-10 opacity-[0.035]"
            style="
                background-image:
                    linear-gradient(rgba(255,255,255,.8) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(255,255,255,.8) 1px, transparent 1px);
                background-size: 32px 32px;
            "></div>

        {{-- =================================================
            HERO CONTENT
        ================================================== --}}
        <div class="relative px-6 py-7 sm:px-8 lg:px-10 lg:py-9">

            {{-- Top Navigation --}}
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                {{-- Back --}}
                <a
                    href="{{ route('proyek.index') }}"
                    class="group inline-flex w-fit items-center gap-2.5 text-[11px] font-medium text-slate-300 transition-colors hover:text-white">
                    <span
                        class="flex h-8 w-8 items-center justify-center rounded-full border border-white/10 bg-white/[0.06] backdrop-blur-md transition-all duration-300 group-hover:-translate-x-0.5 group-hover:bg-white/[0.10]">
                        <svg
                            class="h-4 w-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.7"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </span>

                    Kembali ke Daftar Proyek
                </a>

                {{-- Action Buttons --}}
                @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'super_admin']))
                <div class="flex items-center gap-2">

                    {{-- Edit --}}
                    <button
                        type="button"
                        onclick="toggleModal('modalEditProyek')"
                        class="inline-flex items-center gap-2 rounded-xl border border-white/10 bg-white/[0.07] px-3.5 py-2 text-[11px] font-semibold text-slate-200 backdrop-blur-md transition-all duration-300 hover:bg-white/[0.12] hover:text-white">
                        <svg
                            class="h-3.5 w-3.5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.7"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>

                        Edit Proyek
                    </button>

                    {{-- Delete --}}
                    <form
                        id="form-hapus-proyek"
                        action="{{ route('proyek.destroy', $proyek->id) }}"
                        method="POST"
                        class="m-0">
                        @csrf
                        @method('DELETE')

                        <button
                            type="button"
                            onclick="konfirmasiHapusProyek()"
                            class="inline-flex items-center gap-2 rounded-xl border border-rose-400/10 bg-rose-500/[0.08] px-3.5 py-2 text-[11px] font-semibold text-rose-300 backdrop-blur-md transition-all duration-300 hover:bg-rose-500/[0.15] hover:text-rose-200">
                            <svg
                                class="h-3.5 w-3.5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.7"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>

                            Hapus
                        </button>
                    </form>

                </div>
                @endif

            </div>


            {{-- =================================================
                PROJECT IDENTITY
            ================================================== --}}
            <div class="mt-8 grid grid-cols-1 gap-7 lg:grid-cols-[240px_1fr] lg:items-center">

                {{-- Project Image --}}
                <div
                    class="group relative h-48 overflow-hidden rounded-2xl border border-white/10 bg-white/[0.05] shadow-2xl lg:h-44">

                    @if($proyek->gambar)

                    <img
                        src="{{ asset('storage/' . $proyek->gambar) }}"
                        alt="{{ $proyek->nama_proyek }}"
                        class="h-full w-full object-cover transition duration-700 group-hover:scale-105" />

                    <div
                        class="absolute inset-0 bg-gradient-to-t from-slate-950/60 via-transparent to-transparent"></div>

                    @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'super_admin']))
                    <button
                        type="button"
                        onclick="toggleModal('modalEditProyek')"
                        class="absolute bottom-3 left-3 inline-flex items-center gap-1.5 rounded-lg border border-white/10 bg-slate-950/60 px-3 py-1.5 text-[10px] font-medium text-white backdrop-blur-md opacity-0 transition duration-300 group-hover:opacity-100">
                        <svg
                            class="h-3 w-3"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.7"
                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0118.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.7"
                                d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>

                        Ubah Foto
                    </button>
                    @endif

                    @else

                    <div class="flex h-full flex-col items-center justify-center text-slate-500">

                        <div
                            class="mb-3 flex h-12 w-12 items-center justify-center rounded-xl border border-white/10 bg-white/[0.05]">
                            <svg
                                class="h-6 w-6"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.4"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>

                        <span class="text-[10px] text-slate-500">
                            Belum ada foto proyek
                        </span>

                    </div>

                    @endif

                </div>


                {{-- Project Information --}}
                <div class="min-w-0">

                    {{-- Badge --}}
                    <div class="mb-3 flex flex-wrap items-center gap-2">

                        <span
                            class="inline-flex items-center gap-2 rounded-full border border-blue-400/10 bg-blue-400/[0.08] px-3 py-1.5 text-[9px] font-medium uppercase tracking-[0.16em] text-blue-300">
                            <span class="h-1.5 w-1.5 rounded-full bg-blue-400"></span>
                            Detail Proyek
                        </span>

                        @php
                        $badgeClass = '';
                        $dotClass = '';

                        if($proyek->status == 'Berjalan') {
                        $badgeClass = 'border-emerald-400/10 bg-emerald-400/[0.08] text-emerald-300';
                        $dotClass = 'bg-emerald-400 animate-pulse';
                        }
                        elseif($proyek->status == 'Akan Dimulai') {
                        $badgeClass = 'border-blue-400/10 bg-blue-400/[0.08] text-blue-300';
                        $dotClass = 'bg-blue-400';
                        }
                        elseif($proyek->status == 'Ditunda') {
                        $badgeClass = 'border-amber-400/10 bg-amber-400/[0.08] text-amber-300';
                        $dotClass = 'bg-amber-400';
                        }
                        else {
                        $badgeClass = 'border-slate-400/10 bg-slate-400/[0.08] text-slate-300';
                        $dotClass = 'bg-slate-400';
                        }
                        @endphp

                        <span
                            class="inline-flex items-center gap-2 rounded-full border px-3 py-1.5 text-[9px] font-medium uppercase tracking-[0.12em] {{ $badgeClass }}">
                            <span class="h-1.5 w-1.5 rounded-full {{ $dotClass }}"></span>
                            {{ $proyek->status }}
                        </span>

                    </div>


                    {{-- Project Name --}}
                    <h1
                        class="max-w-4xl text-[28px] font-semibold leading-[1.08] tracking-[-0.035em] text-white sm:text-[32px] lg:text-[36px]">
                        {{ $proyek->nama_proyek }}
                    </h1>


                    {{-- Location --}}
                    <div
                        class="mt-3 flex items-start gap-2 text-[12px] leading-5 text-slate-300">
                        <svg
                            class="mt-0.5 h-4 w-4 shrink-0 text-blue-300"
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

                        <span>{{ $proyek->lokasi }}</span>
                    </div>


                    {{-- Project Stats --}}
                    <div class="mt-7 grid grid-cols-2 gap-3 xl:grid-cols-4">

                        {{-- Client --}}
                        <div
                            class="rounded-2xl border border-white/10 bg-white/[0.055] p-4 backdrop-blur-md">
                            <p
                                class="text-[9px] font-medium uppercase tracking-[0.12em] text-slate-400">
                                Pemilik / Klien
                            </p>

                            <p
                                class="mt-2 truncate text-[13px] font-semibold text-white">
                                {{ $proyek->nama_pemilik ?? 'Belum Diatur' }}
                            </p>

                            <p
                                class="mt-1 truncate text-[10px] text-slate-400">
                                {{ $proyek->kontak_pemilik ?? '-' }}
                            </p>
                        </div>


                        {{-- Contract Value --}}
                        <div
                            class="rounded-2xl border border-white/10 bg-white/[0.055] p-4 backdrop-blur-md">
                            <p
                                class="text-[9px] font-medium uppercase tracking-[0.12em] text-slate-400">
                                Nilai Kontrak
                            </p>

                            <p
                                class="mt-2 text-[18px] font-semibold tracking-[-0.02em] text-emerald-300">
                                Rp {{ number_format($proyek->anggaran, 0, ',', '.') }}
                            </p>

                            <p class="mt-1 text-[10px] text-slate-400">
                                Anggaran / RAB
                            </p>
                        </div>


                        {{-- Timeline --}}
                        <div
                            class="rounded-2xl border border-white/10 bg-white/[0.055] p-4 backdrop-blur-md">
                            <p
                                class="text-[9px] font-medium uppercase tracking-[0.12em] text-slate-400">
                                Timeline
                            </p>

                            <p
                                class="mt-2 text-[12px] font-semibold text-white">
                                {{ \Carbon\Carbon::parse($proyek->tanggal_mulai)->format('d M Y') }}
                            </p>

                            <p class="mt-1 text-[10px] text-slate-400">
                                s/d
                                {{ $proyek->estimasi_selesai
                                    ? \Carbon\Carbon::parse($proyek->estimasi_selesai)->format('d M Y')
                                    : 'Belum Ditentukan'
                                }}
                            </p>
                        </div>


                        {{-- Team --}}
                        <div
                            class="rounded-2xl border border-white/10 bg-white/[0.055] p-4 backdrop-blur-md">
                            <p
                                class="text-[9px] font-medium uppercase tracking-[0.12em] text-slate-400">
                                Tim Lapangan
                            </p>

                            <div class="mt-2 flex items-center gap-2">

                                <span
                                    class="flex h-7 w-7 items-center justify-center rounded-lg border border-blue-400/10 bg-blue-400/[0.08]">
                                    <svg
                                        class="h-3.5 w-3.5 text-blue-300"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1.7"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </span>

                                <span
                                    class="text-[18px] font-semibold tracking-[-0.02em] text-white">
                                    {{ $proyek->pegawais->count() }}
                                </span>

                                <span class="text-[10px] text-slate-400">
                                    Orang
                                </span>

                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>


    {{-- =====================================================
        QUICK ACCESS
    ====================================================== --}}
    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

        {{-- Buku Harian --}}
        <a
            href="{{ route('proyek.laporan.admin', $proyek->id) }}"
            class="group relative block overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md">

            <div
                class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-blue-50 transition-transform duration-500 group-hover:scale-150"></div>

            <div class="relative flex items-start gap-4">

                <div
                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-blue-100 bg-blue-50 text-blue-600 transition-colors duration-300 group-hover:bg-blue-600 group-hover:text-white">
                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.6"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>

                <div class="min-w-0">
                    <h3
                        class="text-[14px] font-semibold tracking-[-0.01em] text-slate-900 transition-colors group-hover:text-blue-700">
                        Buku Harian Lapangan
                    </h3>

                    <p
                        class="mt-1 text-[12px] leading-5 text-slate-500">
                        Catatan cuaca, foto, dan aktivitas lapangan.
                    </p>
                </div>

            </div>

            <div
                class="absolute bottom-5 right-5 flex h-7 w-7 translate-x-2 items-center justify-center rounded-full bg-blue-50 text-blue-600 opacity-0 transition-all duration-300 group-hover:translate-x-0 group-hover:opacity-100">
                <svg
                    class="h-3.5 w-3.5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.7"
                        d="M9 5l7 7-7 7" />
                </svg>
            </div>

        </a>


        {{-- Keuangan --}}
        <a
            href="{{ route('proyek.keuangan', $proyek->id) }}"
            class="group relative block overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:border-emerald-200 hover:shadow-md">

            <div
                class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-emerald-50 transition-transform duration-500 group-hover:scale-150"></div>

            <div class="relative flex items-start gap-4">

                <div
                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-emerald-100 bg-emerald-50 text-emerald-600 transition-colors duration-300 group-hover:bg-emerald-600 group-hover:text-white">
                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.6"
                            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>

                <div class="min-w-0">
                    <h3
                        class="text-[14px] font-semibold tracking-[-0.01em] text-slate-900 transition-colors group-hover:text-emerald-700">
                        Laporan Keuangan
                    </h3>

                    <p
                        class="mt-1 text-[12px] leading-5 text-slate-500">
                        Kelola dana termin dan pengeluaran proyek.
                    </p>
                </div>

            </div>

            <div
                class="absolute bottom-5 right-5 flex h-7 w-7 translate-x-2 items-center justify-center rounded-full bg-emerald-50 text-emerald-600 opacity-0 transition-all duration-300 group-hover:translate-x-0 group-hover:opacity-100">
                <svg
                    class="h-3.5 w-3.5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.7"
                        d="M9 5l7 7-7 7" />
                </svg>
            </div>

        </a>


        {{-- AI Forecasting --}}
        <div
            class="group relative overflow-hidden rounded-2xl border border-slate-700 bg-gradient-to-br from-slate-950 to-blue-950 p-5 shadow-sm">

            <div
                class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-amber-400/10 blur-2xl transition-all duration-500 group-hover:bg-amber-400/20"></div>

            <div class="relative flex items-start gap-4">

                <div
                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-amber-400/10 bg-amber-400/[0.08] text-amber-300">
                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.7"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>

                <div>
                    <h3
                        class="text-[14px] font-semibold tracking-[-0.01em] text-amber-300">
                        AI Forecasting
                    </h3>

                    <p
                        class="mt-1 text-[11px] leading-5 text-slate-400">
                        Prediksi stok material dengan metode SES.
                    </p>

                    <span
                        class="mt-3 inline-flex rounded-full border border-amber-400/10 bg-amber-400/[0.06] px-2.5 py-1 text-[8px] font-medium uppercase tracking-[0.12em] text-amber-300">
                        Segera Hadir
                    </span>
                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
        PROJECT TABS
    ====================================================== --}}
    <section
        class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="px-5 pt-5 sm:px-6 lg:px-8 lg:pt-7">

            {{-- Tab Navigation --}}
            <div class="overflow-x-auto border-b border-slate-200">

                <nav
                    class="flex min-w-max gap-6"
                    aria-label="Navigasi proyek">

                    {{-- Tim --}}
                    <button
                        id="tab-btn-tim"
                        onclick="switchTabProyek('tim')"
                        class="inline-flex items-center gap-2 border-b-2 border-blue-500 px-1 py-3.5 text-[12px] font-semibold text-slate-900 transition-colors focus:outline-none">

                        <svg
                            class="h-4 w-4 text-blue-500"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.7"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>

                        Tim Proyek Lapangan

                        <span
                            class="rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-medium text-slate-600">
                            {{ $proyek->pegawais->count() }}
                        </span>

                    </button>


                    {{-- Pekerjaan --}}
                    <button
                        id="tab-btn-pekerjaan"
                        onclick="switchTabProyek('pekerjaan')"
                        class="inline-flex items-center gap-2 border-b-2 border-transparent px-1 py-3.5 text-[12px] font-medium text-slate-500 transition-colors hover:border-slate-300 hover:text-slate-800 focus:outline-none">

                        <svg
                            class="h-4 w-4 text-slate-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.7"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>

                        Item Pekerjaan & Bobot

                        <span
                            class="rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-medium text-slate-600">
                            {{ $proyek->itemPekerjaans->count() }}
                        </span>

                    </button>

                </nav>

            </div>


            {{-- =================================================
                TAB TIM PROYEK
            ================================================== --}}
            <div
                id="tab-content-tim"
                class="block py-6">

                <div class="mb-5 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                    <div>

                        <h2
                            class="text-[15px] font-semibold tracking-[-0.01em] text-slate-900">
                            Tim Proyek Lapangan
                        </h2>

                        <p class="mt-1 text-[11px] text-slate-500">
                            Daftar pekerja yang ditugaskan pada proyek ini.
                        </p>

                    </div>


                    <div class="flex flex-col gap-3 sm:flex-row">

                        {{-- Search --}}
                        <div class="relative w-full sm:w-64">

                            <div
                                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <svg
                                    class="h-3.5 w-3.5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.7"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>

                            <input
                                type="text"
                                id="searchTimProyek"
                                onkeyup="filterTimProyek()"
                                placeholder="Cari nama pekerja..."
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-9 pr-4 text-[11px] text-slate-800 placeholder-slate-400 outline-none transition-all focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-500/10" />

                        </div>


                        @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'super_admin']))

                        <button
                            type="button"
                            onclick="toggleModal('modalTambahPekerja')"
                            class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2.5 text-[11px] font-semibold text-white shadow-sm transition-colors hover:bg-blue-950">

                            <svg
                                class="mr-2 h-3.5 w-3.5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.7"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>

                            Tugaskan Pekerja

                        </button>

                        @endif

                    </div>

                </div>


                @if($proyek->pegawais->count() > 0)

                <div id="timProyekContainer">

                    @foreach($pekerjaPerJabatan as $namaJabatan => $daftarPekerja)

                    <div class="kelompok-jabatan mb-6 last:mb-0">

                        <div class="mb-2.5 flex items-center">

                            <span
                                class="mr-2 h-1.5 w-1.5 rounded-full bg-blue-500"></span>

                            <h4
                                class="text-[10px] font-semibold uppercase tracking-[0.12em] text-slate-500">
                                {{ $namaJabatan }}
                            </h4>

                            <span
                                class="ml-2 text-[10px] text-slate-400">
                                ({{ $daftarPekerja->count() }} orang)
                            </span>

                        </div>


                        <div class="overflow-x-auto rounded-xl border border-slate-200">

                            <table class="w-full min-w-[500px] text-left">

                                <tbody
                                    class="divide-y divide-slate-100 text-[12px] text-slate-700">

                                    @foreach($daftarPekerja as $pekerja)

                                    <tr
                                        class="row-pekerja transition-colors hover:bg-slate-50">

                                        <td
                                            class="cell-nama-pekerja px-4 py-3.5 font-medium text-slate-800">
                                            {{ $pekerja->nama }}
                                        </td>

                                        <td
                                            class="px-4 py-3.5 text-slate-500">
                                            {{ $pekerja->no_telp ?? '-' }}
                                        </td>

                                        @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'super_admin']))

                                        <td
                                            class="px-4 py-3.5 text-right">

                                            <form
                                                action="{{ route('proyek.remove', ['proyek' => $proyek->id, 'pegawai' => $pekerja->id]) }}"
                                                method="POST"
                                                class="inline"
                                                onsubmit="return confirm('Keluarkan {{ addslashes($pekerja->nama) }} dari proyek ini?');">

                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="rounded-lg p-1.5 text-slate-400 transition-colors hover:bg-rose-50 hover:text-rose-600"
                                                    title="Copot Penugasan"
                                                    aria-label="Copot {{ $pekerja->nama }}">

                                                    <svg
                                                        class="h-3.5 w-3.5"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="1.7"
                                                            d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6" />
                                                    </svg>

                                                </button>

                                            </form>

                                        </td>

                                        @endif

                                    </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    </div>

                    @endforeach

                </div>


                <p
                    id="pesanTimKosong"
                    class="hidden py-8 text-center text-[12px] text-slate-400">
                    Tidak ada pekerja yang cocok dengan pencarian.
                </p>

                @else

                <div
                    class="rounded-xl border border-dashed border-slate-200 py-12 text-center">

                    <div
                        class="mx-auto mb-3 flex h-10 w-10 items-center justify-center rounded-full bg-slate-50">
                        <svg
                            class="h-5 w-5 text-slate-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.5"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857" />
                        </svg>
                    </div>

                    <p class="text-[12px] font-medium text-slate-500">
                        Belum ada pekerja yang ditugaskan.
                    </p>

                </div>

                @endif

            </div>


            {{-- =================================================
                TAB ITEM PEKERJAAN
            ================================================== --}}
            <div
                id="tab-content-pekerjaan"
                class="hidden py-6">

                @php
                $totalBobot = $proyek->itemPekerjaans->sum('bobot');
                $sisaBobot = max(0, 100 - $totalBobot);
                $sisaBobotFormated = number_format($sisaBobot, 2, '.', '');
                @endphp


                <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                    <div>

                        <h2
                            class="text-[15px] font-semibold tracking-[-0.01em] text-slate-900">
                            Item Pekerjaan & Bobot
                        </h2>

                        <p class="mt-1 text-[11px] text-slate-500">
                            Total bobot pekerjaan harus mencapai 100%.
                        </p>

                    </div>


                    <div
                        class="flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5">

                        <span
                            class="text-[9px] font-medium uppercase tracking-[0.12em] text-slate-500">
                            Total Terisi
                        </span>

                        <span
                            class="text-[20px] font-semibold tracking-[-0.03em] {{ $totalBobot >= 100 ? 'text-emerald-600' : 'text-slate-800' }}">
                            {{ number_format($totalBobot, 2, ',', '.') }}

                            <span class="text-[11px] font-medium text-slate-400">
                                /100%
                            </span>
                        </span>

                    </div>

                </div>


                @if($totalBobot < 100 && auth()->check() && in_array(auth()->user()->role, ['admin', 'super_admin']))

                    <form
                        action="{{ route('item-pekerjaan.store', $proyek->id) }}"
                        method="POST"
                        class="mb-8">

                        @csrf

                        <div
                            id="dynamic-form-container"
                            class="space-y-4">

                            <div
                                class="row-item group flex flex-col items-end gap-4 sm:flex-row">

                                <div class="w-full flex-1">

                                    <label
                                        class="mb-1.5 block text-[9px] font-medium uppercase tracking-[0.12em] text-slate-500">
                                        Nama Pekerjaan
                                    </label>

                                    <input
                                        type="text"
                                        name="nama_pekerjaan[]"
                                        placeholder="Contoh: Pekerjaan Atap..."
                                        required
                                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-[12px] text-slate-800 shadow-sm outline-none transition-all focus:border-blue-400 focus:ring-2 focus:ring-blue-500/10">

                                </div>


                                <div class="w-full sm:w-36">

                                    <label
                                        class="mb-1.5 block text-[9px] font-medium uppercase tracking-[0.12em] text-slate-500">
                                        Bobot (%)
                                    </label>

                                    <input
                                        type="number"
                                        name="bobot[]"
                                        step="0.01"
                                        min="0.01"
                                        max="{{ $sisaBobotFormated }}"
                                        placeholder="Maks: {{ number_format($sisaBobot, 2, ',', '.') }}"
                                        required
                                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-[12px] text-slate-800 shadow-sm outline-none transition-all focus:border-blue-400 focus:ring-2 focus:ring-blue-500/10">

                                </div>


                                <button
                                    type="button"
                                    onclick="hapusBaris(this)"
                                    class="btn-remove hidden rounded-xl border border-transparent p-2.5 text-slate-400 transition-colors hover:border-rose-100 hover:bg-rose-50 hover:text-rose-500"
                                    title="Hapus Baris">

                                    <svg
                                        class="h-4 w-4"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1.7"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 01-1 1v3M4 7h16" />
                                    </svg>

                                </button>

                            </div>

                        </div>


                        <div
                            class="mt-6 flex gap-3 border-t border-slate-100 pt-6">

                            <button
                                type="button"
                                onclick="tambahBaris()"
                                class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-[11px] font-medium text-slate-600 transition-colors hover:bg-slate-50 hover:text-slate-900">
                                + Tambah Baris
                            </button>

                            <button
                                type="submit"
                                class="rounded-xl bg-slate-900 px-5 py-2.5 text-[11px] font-semibold text-white shadow-sm transition-colors hover:bg-blue-950">
                                Simpan Data
                            </button>

                        </div>

                    </form>

                    @elseif($totalBobot >= 100)

                    <div
                        class="mb-8 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-[11px] font-medium text-emerald-800">

                        <div
                            class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-100">
                            <svg
                                class="h-4 w-4 text-emerald-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.7"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </div>

                        Total bobot telah mencapai 100%. Daftar pekerjaan siap dilaksanakan.

                    </div>

                    @endif


                    <div class="overflow-x-auto rounded-xl border border-slate-200">

                        <table class="w-full min-w-[500px] text-left">

                            <thead
                                class="border-b border-slate-200 bg-slate-50">

                                <tr>

                                    <th
                                        class="px-5 py-3.5 text-[9px] font-semibold uppercase tracking-[0.12em] text-slate-500">
                                        Nama Tahapan
                                    </th>

                                    <th
                                        class="px-5 py-3.5 text-center text-[9px] font-semibold uppercase tracking-[0.12em] text-slate-500">
                                        Bobot (%)
                                    </th>

                                    <th
                                        class="px-5 py-3.5 text-center text-[9px] font-semibold uppercase tracking-[0.12em] text-slate-500">
                                        Progres Fisik (%)
                                    </th>

                                    @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'super_admin']))

                                    <th
                                        class="px-5 py-3.5 text-right text-[9px] font-semibold uppercase tracking-[0.12em] text-slate-500">
                                        Aksi
                                    </th>

                                    @endif

                                </tr>

                            </thead>


                            <tbody class="divide-y divide-slate-100">

                                @forelse($proyek->itemPekerjaans as $item)

                                <tr class="transition-colors hover:bg-slate-50">

                                    <td
                                        class="px-5 py-3.5 text-[12px] font-medium text-slate-800">
                                        {{ $item->nama_pekerjaan }}
                                    </td>

                                    <td class="px-5 py-3.5 text-center">

                                        <span
                                            class="inline-flex items-center rounded-lg bg-slate-100 px-2.5 py-1 text-[10px] font-medium text-slate-700">
                                            {{ number_format($item->bobot, 2, ',', '.') }}%
                                        </span>

                                    </td>

                                    <td class="px-5 py-3.5 text-center">

                                        <span
                                            class="text-[12px] font-semibold {{ $item->progres_sekarang == 100 ? 'text-emerald-600' : 'text-blue-600' }}">
                                            {{ number_format($item->progres_sekarang, 2, ',', '.') }}%
                                        </span>

                                    </td>

                                    @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'super_admin']))

                                    <td class="px-5 py-3.5 text-right">

                                        <form
                                            action="{{ route('item-pekerjaan.destroy', $item->id) }}"
                                            method="POST"
                                            class="inline-block"
                                            onsubmit="return confirm('Hapus tahapan pekerjaan {{ addslashes($item->nama_pekerjaan) }}?')">

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="rounded-lg p-1.5 text-slate-400 transition-colors hover:bg-rose-50 hover:text-rose-600"
                                                title="Hapus Item">

                                                <svg
                                                    class="h-3.5 w-3.5"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="1.7"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 01-1-1h-4a1 1 0 01-1 1v3M4 7h16" />
                                                </svg>

                                            </button>

                                        </form>

                                    </td>

                                    @endif

                                </tr>

                                @empty

                                <tr>

                                    <td
                                        colspan="{{ auth()->check() && in_array(auth()->user()->role, ['admin', 'super_admin']) ? 4 : 3 }}"
                                        class="px-5 py-12 text-center">

                                        <div
                                            class="mx-auto mb-3 flex h-10 w-10 items-center justify-center rounded-full bg-slate-50">
                                            <svg
                                                class="h-5 w-5 text-slate-400"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="1.5"
                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                            </svg>
                                        </div>

                                        <p class="text-[12px] font-medium text-slate-500">
                                            Belum ada item pekerjaan.
                                        </p>

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


{{-- =========================================================
    MODAL AREA
========================================================= --}}
@if(auth()->check() && in_array(auth()->user()->role, ['admin', 'super_admin']))

{{-- =====================================================
        MODAL EDIT PROYEK
    ====================================================== --}}
<div
    id="modalEditProyek"
    class="fixed inset-0 z-50 hidden h-full w-full items-center justify-center overflow-y-auto bg-slate-900/60 p-4 backdrop-blur-sm"
    role="dialog"
    aria-modal="true"
    aria-labelledby="modalEditProyekTitle"
    onclick="closeModalOutside(event, 'modalEditProyek')">

    <div
        class="relative my-8 w-full max-w-xl rounded-2xl border border-slate-100 bg-white p-6 shadow-2xl">

        <div
            class="mb-6 flex items-center justify-between border-b border-slate-100 pb-4">

            <h3
                id="modalEditProyekTitle"
                class="text-[16px] font-semibold tracking-[-0.01em] text-slate-800">
                Edit Data Proyek
            </h3>

            <button
                type="button"
                onclick="toggleModal('modalEditProyek')"
                class="rounded-lg bg-slate-50 p-1.5 text-slate-400 transition-colors hover:bg-slate-100 hover:text-slate-600">

                <svg
                    class="h-4 w-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.7"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>

            </button>

        </div>


        <form
            action="{{ route('proyek.update', $proyek->id) }}"
            method="POST"
            enctype="multipart/form-data">

            @csrf
            @method('PUT')


            {{-- Upload --}}
            <div class="mb-5">

                <label
                    class="mb-2 block text-[9px] font-semibold uppercase tracking-[0.12em] text-slate-600">
                    Ganti Gambar Proyek

                    <span
                        class="text-[9px] font-normal normal-case tracking-normal text-slate-400">
                        (kosongkan jika tidak diganti)
                    </span>
                </label>

                <div
                    class="mt-1 flex cursor-pointer justify-center rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 px-6 pb-6 pt-5 transition-colors hover:bg-slate-100"
                    onclick="document.getElementById('file-upload-edit').click()">

                    <div class="space-y-1 text-center">

                        <svg
                            class="mx-auto h-9 w-9 text-slate-400"
                            stroke="currentColor"
                            fill="none"
                            viewBox="0 0 48 48">
                            <path
                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>

                        <div class="flex justify-center text-[11px] text-slate-600">

                            <label
                                for="file-upload-edit"
                                class="relative cursor-pointer rounded-md font-medium text-blue-600 hover:text-blue-500">
                                <span>Pilih Gambar Baru</span>

                                <input
                                    id="file-upload-edit"
                                    name="gambar"
                                    type="file"
                                    class="sr-only"
                                    accept="image/png, image/jpeg, image/jpg"
                                    onchange="previewTextEdit(this)" />

                            </label>

                        </div>

                        <p
                            class="text-[10px] text-slate-500"
                            id="file-name-edit">
                            Format JPG/PNG (Maks 2MB)
                        </p>

                    </div>

                </div>

            </div>


            {{-- Nama --}}
            <div class="mb-4">

                <label
                    class="mb-1.5 block text-[9px] font-semibold uppercase tracking-[0.12em] text-slate-600">
                    Nama Proyek
                </label>

                <input
                    type="text"
                    name="nama_proyek"
                    value="{{ $proyek->nama_proyek }}"
                    required
                    class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-[12px] text-slate-900 outline-none transition-all focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10">

            </div>


            {{-- Lokasi --}}
            <div class="mb-4">

                <label
                    class="mb-1.5 block text-[9px] font-semibold uppercase tracking-[0.12em] text-slate-600">
                    Lokasi Proyek
                </label>

                <input
                    type="text"
                    name="lokasi"
                    value="{{ $proyek->lokasi }}"
                    required
                    class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-[12px] text-slate-900 outline-none transition-all focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10">

            </div>


            {{-- Client --}}
            <div class="mb-4 grid grid-cols-1 gap-4 sm:grid-cols-2">

                <div>

                    <label
                        class="mb-1.5 block text-[9px] font-semibold uppercase tracking-[0.12em] text-slate-600">
                        Nama Pemilik / Klien
                    </label>

                    <input
                        type="text"
                        name="nama_pemilik"
                        value="{{ $proyek->nama_pemilik }}"
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-[12px] text-slate-900 outline-none transition-all focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10">

                </div>

                <div>

                    <label
                        class="mb-1.5 block text-[9px] font-semibold uppercase tracking-[0.12em] text-slate-600">
                        Kontak Pemilik
                    </label>

                    <input
                        type="text"
                        name="kontak_pemilik"
                        value="{{ $proyek->kontak_pemilik }}"
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-[12px] text-slate-900 outline-none transition-all focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10">

                </div>

            </div>


            {{-- Anggaran --}}
            <div class="mb-4">

                <label
                    class="mb-1.5 block text-[9px] font-semibold uppercase tracking-[0.12em] text-slate-600">
                    Anggaran / RAB (Rp)
                </label>

                <input
                    type="number"
                    name="anggaran"
                    value="{{ $proyek->anggaran }}"
                    min="0"
                    required
                    class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-[12px] text-slate-900 outline-none transition-all focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10">

            </div>


            {{-- Dates --}}
            <div class="mb-4 grid grid-cols-2 gap-4">

                <div>

                    <label
                        class="mb-1.5 block text-[9px] font-semibold uppercase tracking-[0.12em] text-slate-600">
                        Tanggal Mulai
                    </label>

                    <input
                        type="date"
                        name="tanggal_mulai"
                        value="{{ $proyek->tanggal_mulai }}"
                        required
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-[12px] text-slate-900 outline-none transition-all focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10">

                </div>

                <div>

                    <label
                        class="mb-1.5 block text-[9px] font-semibold uppercase tracking-[0.12em] text-slate-600">
                        Estimasi Selesai
                    </label>

                    <input
                        type="date"
                        name="estimasi_selesai"
                        value="{{ $proyek->estimasi_selesai }}"
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-[12px] text-slate-900 outline-none transition-all focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10">

                </div>

            </div>


            {{-- Status --}}
            <div class="mb-6">

                <label
                    class="mb-1.5 block text-[9px] font-semibold uppercase tracking-[0.12em] text-slate-600">
                    Status Pengerjaan
                </label>

                <select
                    name="status"
                    required
                    class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-[12px] text-slate-900 outline-none transition-all focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10">

                    <option value="Akan Dimulai" {{ $proyek->status == 'Akan Dimulai' ? 'selected' : '' }}>
                        Akan Dimulai
                    </option>

                    <option value="Berjalan" {{ $proyek->status == 'Berjalan' ? 'selected' : '' }}>
                        Berjalan
                    </option>

                    <option value="Ditunda" {{ $proyek->status == 'Ditunda' ? 'selected' : '' }}>
                        Ditunda
                    </option>

                    <option value="Selesai" {{ $proyek->status == 'Selesai' ? 'selected' : '' }}>
                        Selesai
                    </option>

                </select>

            </div>


            {{-- Actions --}}
            <div
                class="flex justify-end gap-3 border-t border-slate-100 pt-5">

                <button
                    type="button"
                    onclick="toggleModal('modalEditProyek')"
                    class="rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-[11px] font-semibold text-slate-700 transition-colors hover:bg-slate-50">
                    Batal
                </button>

                <button
                    type="submit"
                    class="rounded-xl bg-blue-600 px-5 py-2.5 text-[11px] font-semibold text-white shadow-sm transition-colors hover:bg-blue-700">
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

</div>


{{-- =====================================================
        MODAL TUGASKAN PEKERJA
    ====================================================== --}}
<div
    id="modalTambahPekerja"
    class="fixed inset-0 z-50 hidden h-full w-full items-center justify-center overflow-y-auto bg-slate-900/60 p-4 backdrop-blur-sm"
    role="dialog"
    aria-modal="true"
    aria-labelledby="modalTambahPekerjaTitle"
    onclick="closeModalOutside(event, 'modalTambahPekerja')">

    <div
        class="relative my-8 w-full max-w-md rounded-2xl border border-slate-100 bg-white p-6 shadow-2xl">

        <div
            class="mb-6 flex items-center justify-between border-b border-slate-100 pb-4">

            <h3
                id="modalTambahPekerjaTitle"
                class="text-[16px] font-semibold tracking-[-0.01em] text-slate-800">
                Tugaskan Pekerja
            </h3>

            <button
                type="button"
                onclick="toggleModal('modalTambahPekerja')"
                class="rounded-lg bg-slate-50 p-1.5 text-slate-400 transition-colors hover:bg-slate-100 hover:text-slate-600">

                <svg
                    class="h-4 w-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.7"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>

            </button>

        </div>


        <form
            action="{{ route('proyek.assign', $proyek->id) }}"
            method="POST">

            @csrf

            <div class="mb-6">

                <label
                    class="mb-1.5 block text-[9px] font-semibold uppercase tracking-[0.12em] text-slate-600">
                    Pilih Pekerja

                    <span
                        class="text-[9px] font-normal normal-case tracking-normal text-slate-400">
                        (belum ditugaskan)
                    </span>
                </label>

                <select
                    name="pegawai_id"
                    required
                    class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-[12px] text-slate-900 outline-none transition-all focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10">

                    <option
                        value=""
                        disabled
                        selected>
                        -- Pilih Pekerja --
                    </option>

                    @foreach ($pegawaiTersedia as $tersedia)

                    <option value="{{ $tersedia->id }}">
                        {{ $tersedia->nama }}
                        ({{ $tersedia->jabatan->nama_jabatan ?? 'Staf' }})
                    </option>

                    @endforeach

                </select>

            </div>


            <div
                class="flex justify-end gap-3 border-t border-slate-100 pt-5">

                <button
                    type="button"
                    onclick="toggleModal('modalTambahPekerja')"
                    class="rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-[11px] font-semibold text-slate-700 transition-colors hover:bg-slate-50">
                    Batal
                </button>

                <button
                    type="submit"
                    class="rounded-xl bg-slate-900 px-5 py-2.5 text-[11px] font-semibold text-white shadow-sm transition-colors hover:bg-blue-950">
                    Tugaskan ke Tim
                </button>

            </div>

        </form>

    </div>

</div>

@endif

<!-- ============================================== -->
<!-- SCRIPT (SweetAlert, Modal, Tab, & Dynamic Row) -->
<!-- ============================================== -->
<script>
    // --- SWITCH TAB: TIM PROYEK vs ITEM PEKERJAAN ---
    function switchTabProyek(tabName) {
        const tabTim = document.getElementById('tab-content-tim');
        const tabPekerjaan = document.getElementById('tab-content-pekerjaan');
        const btnTim = document.getElementById('tab-btn-tim');
        const btnPekerjaan = document.getElementById('tab-btn-pekerjaan');

        const activeClass = 'inline-flex items-center py-4 px-1 border-b-2 border-amber-500 font-semibold text-sm text-slate-900 transition-colors focus:outline-none';
        const inactiveClass = 'inline-flex items-center py-4 px-1 border-b-2 border-transparent font-medium text-sm text-slate-500 hover:text-slate-700 hover:border-slate-300 transition-colors focus:outline-none';

        if (tabName === 'tim') {
            tabTim.classList.remove('hidden');
            tabPekerjaan.classList.add('hidden');
            btnTim.className = activeClass;
            btnPekerjaan.className = inactiveClass;
        } else {
            tabPekerjaan.classList.remove('hidden');
            tabTim.classList.add('hidden');
            btnPekerjaan.className = activeClass;
            btnTim.className = inactiveClass;
        }
    }

    // --- CARI PEKERJA DI TAB TIM PROYEK ---
    function filterTimProyek() {
        const keyword = document.getElementById('searchTimProyek').value.toLowerCase();
        const kelompokList = document.querySelectorAll('#timProyekContainer .kelompok-jabatan');
        let adaHasil = false;

        kelompokList.forEach(kelompok => {
            const rows = kelompok.querySelectorAll('.row-pekerja');
            let adaBarisTampil = false;

            rows.forEach(row => {
                const nama = row.querySelector('.cell-nama-pekerja').textContent.toLowerCase();
                const cocok = nama.includes(keyword);
                row.style.display = cocok ? '' : 'none';
                if (cocok) adaBarisTampil = true;
            });

            kelompok.style.display = adaBarisTampil ? '' : 'none';
            if (adaBarisTampil) adaHasil = true;
        });

        document.getElementById('pesanTimKosong').classList.toggle('hidden', adaHasil);
    }

    // --- PREVIEW GAMBAR EDIT PROYEK ---
    function previewTextEdit(input) {
        const label = document.getElementById('file-name-edit');
        if (input.files && input.files[0]) {
            label.innerText = "File terpilih: " + input.files[0].name;
            label.classList.add('text-blue-600', 'font-bold');
        } else {
            label.innerText = "Format JPG/PNG (Maks 2MB)";
            label.classList.remove('text-blue-600', 'font-bold');
        }
    }

    function toggleModal(modalID) {
        const modal = document.getElementById(modalID);
        if (modal) {
            modal.classList.toggle("hidden");
        }
    }

    function closeModalOutside(event, modalID) {
        if (event.target.id === modalID) {
            toggleModal(modalID);
        }
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            ['modalEditProyek', 'modalTambahPekerja'].forEach(id => {
                const modal = document.getElementById(id);
                if (modal && !modal.classList.contains('hidden')) {
                    modal.classList.add('hidden');
                }
            });
        }
    });

    function konfirmasiHapusProyek() {
        Swal.fire({
            title: 'Hapus Proyek Ini?',
            text: "Seluruh data penugasan, absensi, dan keuangan proyek ini akan ikut terhapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus Proyek!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                confirmButton: 'rounded-xl text-sm font-bold',
                cancelButton: 'rounded-xl text-sm font-bold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-hapus-proyek').submit();
            }
        });
    }

    function tambahBaris() {
        const container = document.getElementById('dynamic-form-container');
        const firstRow = container.querySelector('.row-item');
        if (!firstRow) return;

        const newRow = firstRow.cloneNode(true);
        newRow.querySelectorAll('input').forEach(input => input.value = '');

        const btnRemove = newRow.querySelector('.btn-remove');
        if (btnRemove) btnRemove.classList.remove('hidden');

        container.appendChild(newRow);
    }

    function hapusBaris(button) {
        const rows = document.querySelectorAll('#dynamic-form-container .row-item');
        if (rows.length > 1) {
            button.closest('.row-item').remove();
        }
    }
</script>

@endsection