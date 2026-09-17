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
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 md:p-8 mb-8">

    <!-- Baris Atas: Tombol Kembali & Tombol Aksi -->
    <div class="flex flex-wrap items-center justify-between mb-6 pb-6 border-b border-slate-100 gap-4">

        <!-- Tombol Kembali -->
        <a href="{{ route('proyek.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-900 transition-colors group">
            <div class="w-8 h-8 rounded-full bg-slate-50 border border-slate-200 flex items-center justify-center mr-3 group-hover:bg-slate-100 transition-colors">
                <svg class="w-4 h-4 text-slate-500 group-hover:text-slate-800 transform group-hover:-translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </div>
            Kembali ke Daftar Proyek
        </a>

        <!-- Tombol Aksi (Edit & Hapus) - Hanya Admin/Super Admin -->
        @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'super_admin']))
        <div class="flex items-center gap-2.5">
            <button type="button" onclick="toggleModal('modalEditProyek')" class="inline-flex items-center px-3.5 py-1.5 bg-white border border-slate-200 text-blue-600 hover:bg-blue-50 hover:border-blue-200 text-sm font-bold rounded-xl transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Proyek
            </button>

            <form id="form-hapus-proyek" action="{{ route('proyek.destroy', $proyek->id) }}" method="POST" class="m-0">
                @csrf
                @method('DELETE')
                <button type="button" onclick="konfirmasiHapusProyek()" class="inline-flex items-center px-3.5 py-1.5 bg-white border border-slate-200 text-rose-600 hover:bg-rose-50 hover:border-rose-200 text-sm font-bold rounded-xl transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Hapus
                </button>
            </form>
        </div>
        @endif
    </div>

    <!-- Baris Bawah: Info Detail Proyek -->
    <div class="flex flex-col md:flex-row gap-6 md:gap-8 md:items-center">

        <!-- Area Gambar Proyek -->
        <div class="w-full md:w-64 h-40 bg-slate-50 rounded-2xl flex-shrink-0 border border-slate-200 shadow-sm overflow-hidden relative flex items-center justify-center group">
            @if($proyek->gambar)
            <img src="{{ asset('storage/' . $proyek->gambar) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="{{ $proyek->nama_proyek }}">
            @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'super_admin']))
            <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-[2px] cursor-pointer"
                role="button" tabindex="0"
                onclick="toggleModal('modalEditProyek')"
                onkeydown="if(event.key==='Enter'||event.key===' ') toggleModal('modalEditProyek')">
                <span class="bg-white/90 text-slate-800 text-[10px] font-bold px-3 py-1.5 rounded-lg flex items-center shadow-lg">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Ubah Foto
                </span>
            </div>
            @endif
            @else
            <div class="flex flex-col items-center justify-center text-slate-300">
                <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span class="text-[11px] font-medium">Belum ada foto</span>
            </div>
            @endif
        </div>

        <!-- Info Detail (Judul, Lokasi, Grid Data) -->
        <div class="flex-1">
            <div class="mb-3">
                <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                    <h1 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight">{{ $proyek->nama_proyek }}</h1>

                    @php
                    $badgeClass = ''; $dotClass = '';
                    if($proyek->status == 'Berjalan') { $badgeClass = 'bg-emerald-100 text-emerald-700 border-emerald-200'; $dotClass = 'bg-emerald-600 animate-pulse'; }
                    elseif($proyek->status == 'Akan Dimulai') { $badgeClass = 'bg-blue-100 text-blue-700 border-blue-200'; $dotClass = 'bg-blue-600'; }
                    elseif($proyek->status == 'Ditunda') { $badgeClass = 'bg-amber-100 text-amber-700 border-amber-200'; $dotClass = 'bg-amber-600'; }
                    else { $badgeClass = 'bg-slate-100 text-slate-700 border-slate-200'; $dotClass = 'bg-slate-500'; }
                    @endphp
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ $badgeClass }} w-max">
                        <span class="w-2 h-2 rounded-full {{ $dotClass }} mr-2"></span> {{ $proyek->status }}
                    </span>
                </div>

                <p class="text-sm text-slate-500 mt-2 flex items-center font-medium">
                    <svg class="w-4 h-4 mr-1.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    {{ $proyek->lokasi }}
                </p>
            </div>

            <!-- Grid Data Proyek -->
            <div class="grid grid-cols-2 xl:grid-cols-4 gap-6 mt-5 border-t border-slate-100 pt-5">
                <div>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">Pemilik / Klien</p>
                    <p class="text-sm font-bold text-slate-800 leading-tight">{{ $proyek->nama_pemilik ?? 'Belum Diatur' }}</p>
                    <p class="text-[11px] font-medium text-slate-500 mt-0.5">{{ $proyek->kontak_pemilik ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">Nilai Kontrak</p>
                    <p class="text-base font-black text-emerald-600">Rp {{ number_format($proyek->anggaran, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">Timeline Proyek</p>
                    <p class="text-sm font-bold text-slate-800">
                        {{ \Carbon\Carbon::parse($proyek->tanggal_mulai)->format('d M Y') }} - {{ $proyek->estimasi_selesai ? \Carbon\Carbon::parse($proyek->estimasi_selesai)->format('d M Y') : 'Belum Ditentukan' }}
                    </p>
                </div>
                <div>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">Tim Lapangan</p>
                    <p class="text-sm font-bold text-slate-800 flex items-center">
                        <svg class="w-4 h-4 mr-1.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        {{ $proyek->pegawais->count() }} Orang
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============================================== -->
<!-- WIDGET RINGKASAN METRIK & JALAN PINTAS -->
<!-- ============================================== -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

    <!-- WIDGET 1: BUKU HARIAN LAPANGAN -->
    <a href="{{ route('proyek.laporan.admin', $proyek->id) }}" class="group block bg-white rounded-2xl border border-slate-200 p-6 shadow-sm hover:shadow-md hover:border-blue-300 transition-all duration-300 relative overflow-hidden">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-50 rounded-full group-hover:scale-150 transition-transform duration-500 -z-10"></div>
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 border border-blue-100 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-base font-bold text-slate-900 mb-1 group-hover:text-blue-700 transition-colors">Buku Harian Lapangan</h3>
                <p class="text-[13px] text-slate-500 leading-relaxed">Catatan cuaca, foto, & aktivitas fisik dari Pengawas.</p>
            </div>
        </div>
        <div class="absolute bottom-6 right-6 opacity-0 translate-x-4 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-300">
            <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>
        </div>
    </a>

    <!-- WIDGET 2: LAPORAN KEUANGAN -->
    <a href="{{ route('proyek.keuangan', $proyek->id) }}" class="group block bg-white rounded-2xl border border-slate-200 p-6 shadow-sm hover:shadow-md hover:border-emerald-300 transition-all duration-300 relative overflow-hidden">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-50 rounded-full group-hover:scale-150 transition-transform duration-500 -z-10"></div>
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-100 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-base font-bold text-slate-900 mb-1 group-hover:text-emerald-700 transition-colors">Laporan Keuangan</h3>
                <p class="text-[13px] text-slate-500 leading-relaxed">Kelola dana termin dan pantau pengeluaran lapangan.</p>
            </div>
        </div>
        <div class="absolute bottom-6 right-6 opacity-0 translate-x-4 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-300">
            <div class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>
        </div>
    </a>

    <!-- WIDGET 3: AI FORECASTING -->
    <div class="bg-gradient-to-br from-[#0c2340] to-[#1a365d] rounded-2xl shadow-sm border border-slate-700 p-6 flex flex-col justify-center items-center relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-20 h-20 bg-amber-400/10 rounded-full blur-2xl group-hover:bg-amber-400/20 transition-all duration-500"></div>
        <svg class="w-8 h-8 text-amber-400 mb-3 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
        </svg>
        <h3 class="text-sm font-bold text-amber-400 text-center relative z-10 mb-1">AI Forecasting (SES)</h3>
        <p class="text-[11px] text-slate-400 text-center relative z-10 font-medium">Prediksi Stok Material (Segera Hadir)</p>
    </div>
</div>

<!-- ============================================== -->
<!-- TAB NAVIGASI: TIM PROYEK vs ITEM PEKERJAAN -->
<!-- ============================================== -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 lg:p-8 mb-6">

    <div class="mb-6 border-b border-slate-200">
        <nav class="flex space-x-8" aria-label="Tabs">
            <button id="tab-btn-tim" onclick="switchTabProyek('tim')" class="inline-flex items-center py-4 px-1 border-b-2 border-amber-500 font-semibold text-sm text-slate-900 transition-colors focus:outline-none">
                <svg class="w-5 h-5 mr-2 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Tim Proyek Lapangan
                <span class="ml-2 bg-slate-100 text-slate-700 py-0.5 px-2 rounded-full text-xs font-medium">{{ $proyek->pegawais->count() }}</span>
            </button>
            <button id="tab-btn-pekerjaan" onclick="switchTabProyek('pekerjaan')" class="inline-flex items-center py-4 px-1 border-b-2 border-transparent font-medium text-sm text-slate-500 hover:text-slate-700 hover:border-slate-300 transition-colors focus:outline-none">
                <svg class="w-5 h-5 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
                Item Pekerjaan & Bobot
                <span class="ml-2 bg-slate-100 text-slate-600 py-0.5 px-2 rounded-full text-xs font-medium">{{ $proyek->itemPekerjaans->count() }}</span>
            </button>
        </nav>
    </div>

    <!-- ============================================== -->
    <!-- KONTEN TAB 1: TIM PROYEK LAPANGAN -->
    <!-- ============================================== -->
    <div id="tab-content-tim" class="block">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-5 gap-4">
            <div class="relative flex-1 w-full sm:max-w-xs">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" id="searchTimProyek" onkeyup="filterTimProyek()" placeholder="Cari nama pekerja..." class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all">
            </div>
            @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'super_admin']))
            <button type="button" onclick="toggleModal('modalTambahPekerja')" class="inline-flex items-center justify-center px-4 py-2 bg-slate-800 text-white text-sm font-medium rounded-lg hover:bg-[#0c2340] transition-colors shadow-sm shrink-0 w-full sm:w-auto">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tugaskan Pekerja
            </button>
            @endif
        </div>

        @if($proyek->pegawais->count() > 0)
        <div id="timProyekContainer">
            @foreach($pekerjaPerJabatan as $namaJabatan => $daftarPekerja)
            <div class="mb-6 last:mb-0 kelompok-jabatan">
                <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 flex items-center">
                    <span class="w-2 h-2 rounded-full bg-blue-500 mr-2"></span>
                    {{ $namaJabatan }}
                    <span class="ml-2 text-slate-400 font-normal normal-case">({{ $daftarPekerja->count() }} orang)</span>
                </h4>
                <div class="overflow-x-auto rounded-lg border border-slate-100">
                    <table class="w-full text-left border-collapse min-w-[500px]">
                        <tbody class="text-sm text-slate-700 divide-y divide-slate-100">
                            @foreach($daftarPekerja as $pekerja)
                            <tr class="hover:bg-slate-50 transition-colors row-pekerja">
                                <td class="py-3 px-4 font-medium text-slate-800 cell-nama-pekerja">{{ $pekerja->nama }}</td>
                                <td class="py-3 px-4 text-slate-500">{{ $pekerja->no_telp ?? '-' }}</td>
                                @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'super_admin']))
                                <td class="py-3 px-4 text-right">
                                    <form action="{{ route('proyek.remove', ['proyek' => $proyek->id, 'pegawai' => $pekerja->id]) }}" method="POST" class="inline" onsubmit="return confirm('Keluarkan {{ addslashes($pekerja->nama) }} dari proyek ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Copot Penugasan" aria-label="Copot {{ $pekerja->nama }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6"></path>
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
        <p id="pesanTimKosong" class="hidden text-center text-sm text-slate-400 py-8">Tidak ada pekerja yang cocok dengan pencarian.</p>
        @else
        <div class="text-center py-12 border border-dashed border-slate-200 rounded-xl">
            <p class="text-slate-500 font-medium">Belum ada pekerja yang ditugaskan ke proyek ini.</p>
        </div>
        @endif
    </div>

    <!-- ============================================== -->
    <!-- KONTEN TAB 2: ITEM PEKERJAAN & BOBOT -->
    <!-- ============================================== -->
    <div id="tab-content-pekerjaan" class="hidden">
        @php
        $totalBobot = $proyek->itemPekerjaans->sum('bobot');
        $sisaBobot = max(0, 100 - $totalBobot);
        $sisaBobotFormated = number_format($sisaBobot, 2, '.', '');
        @endphp

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <p class="text-xs text-slate-500">Daftarkan tahapan pekerjaan proyek. Total bobot harus mencapai 100%.</p>
            <div class="flex items-center gap-3 bg-slate-50 px-4 py-2 rounded-xl border border-slate-100 shrink-0">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Terisi:</span>
                <span class="text-xl font-bold {{ $totalBobot >= 100 ? 'text-emerald-600' : 'text-slate-800' }}">
                    {{ number_format($totalBobot, 2, ',', '.') }}<span class="text-sm font-medium text-slate-400">/100%</span>
                </span>
            </div>
        </div>

        @if($totalBobot < 100 && auth()->check() && in_array(auth()->user()->role, ['admin', 'super_admin']))
        <form action="{{ route('item-pekerjaan.store', $proyek->id) }}" method="POST" class="mb-8">
            @csrf
            <div id="dynamic-form-container" class="space-y-4">
                <div class="flex flex-col sm:flex-row gap-4 items-end row-item group">
                    <div class="flex-1 w-full">
                        <label class="block text-[11px] font-semibold text-slate-500 mb-1.5 uppercase tracking-wide">Nama Pekerjaan</label>
                        <input type="text" name="nama_pekerjaan[]" placeholder="Contoh: Pekerjaan Atap..." required class="w-full bg-white rounded-lg border border-slate-300 shadow-sm py-2.5 px-3 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 transition-all">
                    </div>
                    <div class="w-full sm:w-36">
                        <label class="block text-[11px] font-semibold text-slate-500 mb-1.5 uppercase tracking-wide">Bobot (%)</label>
                        <input type="number" name="bobot[]" step="0.01" min="0.01" max="{{ $sisaBobotFormated }}" placeholder="Maks: {{ number_format($sisaBobot, 2, ',', '.') }}" required class="w-full bg-white rounded-lg border border-slate-300 shadow-sm py-2.5 px-3 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 transition-all">
                    </div>
                    <button type="button" onclick="hapusBaris(this)" class="p-2.5 text-slate-400 hover:text-rose-500 rounded-lg hover:bg-rose-50 border border-transparent hover:border-rose-100 transition-colors hidden btn-remove" title="Hapus Baris" aria-label="Hapus baris item">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="flex gap-3 mt-6 pt-6 border-t border-slate-100">
                <button type="button" onclick="tambahBaris()" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 text-sm font-medium rounded-lg hover:bg-slate-50 transition-colors hover:text-slate-900">+ Tambah Baris</button>
                <button type="submit" class="px-6 py-2.5 bg-slate-900 text-white text-sm font-medium rounded-lg hover:bg-slate-800 transition-colors shadow-sm">Simpan Data</button>
            </div>
        </form>
        @elseif($totalBobot >= 100)
        <div class="bg-emerald-50 text-emerald-800 p-4 rounded-xl border border-emerald-200 text-sm font-medium mb-8 flex items-center">
            <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center mr-3 shrink-0">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            Total bobot telah mencapai 100%. Daftar pekerjaan siap dilaksanakan.
        </div>
        @endif

        <div class="overflow-x-auto rounded-xl border border-slate-200">
            <table class="w-full text-left text-sm min-w-[500px]">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-600 font-semibold">
                    <tr>
                        <th class="px-5 py-3.5">Nama Tahapan</th>
                        <th class="px-5 py-3.5 text-center">Bobot (%)</th>
                        <th class="px-5 py-3.5 text-center">Progres Fisik (%)</th>
                        @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'super_admin']))
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($proyek->itemPekerjaans as $item)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-5 py-4 font-medium text-slate-800">{{ $item->nama_pekerjaan }}</td>
                        <td class="px-5 py-4 text-center">
                            <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-md bg-slate-100 text-slate-700 font-semibold text-xs">
                                {{ number_format($item->bobot, 2, ',', '.') }}%
                            </span>
                        </td>
                        <td class="px-5 py-4 text-center">
                            <span class="font-bold {{ $item->progres_sekarang == 100 ? 'text-emerald-600' : 'text-blue-600' }}">
                                {{ number_format($item->progres_sekarang, 2, ',', '.') }}%
                            </span>
                        </td>
                        @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'super_admin']))
                        <td class="px-5 py-4 text-right">
                            <form action="{{ route('item-pekerjaan.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus tahapan pekerjaan {{ addslashes($item->nama_pekerjaan) }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus Item" aria-label="Hapus {{ $item->nama_pekerjaan }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ auth()->check() && in_array(auth()->user()->role, ['admin', 'super_admin']) ? 4 : 3 }}" class="px-5 py-12 text-center">
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-50 mb-3">
                                <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                            </div>
                            <p class="text-slate-500 font-medium">Belum ada item pekerjaan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- ============================================== -->
<!-- AREA MODAL (ADMIN & SUPER ADMIN) -->
<!-- ============================================== -->
@if(auth()->check() && in_array(auth()->user()->role, ['admin', 'super_admin']))

<!-- 1. MODAL EDIT PROYEK -->
<div id="modalEditProyek" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm overflow-y-auto w-full h-full flex items-center justify-center p-4"
    role="dialog" aria-modal="true" aria-labelledby="modalEditProyekTitle"
    onclick="closeModalOutside(event, 'modalEditProyek')">
    <div class="relative mx-auto p-6 w-full max-w-xl shadow-2xl rounded-2xl bg-white border border-slate-100 my-8">
        <div class="flex justify-between items-center mb-6 border-b border-slate-100 pb-4">
            <h3 id="modalEditProyekTitle" class="text-lg font-bold text-slate-800">Edit Data Proyek</h3>
            <button type="button" onclick="toggleModal('modalEditProyek')" class="text-slate-400 hover:text-slate-600 p-1 bg-slate-50 hover:bg-slate-100 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form action="{{ route('proyek.update', $proyek->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Upload Gambar Proyek -->
            <div class="mb-5">
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-2">Ganti Gambar Proyek <span class="text-xs font-normal text-slate-400 lowercase">(biarkan kosong jika tidak diganti)</span></label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-xl bg-slate-50 hover:bg-slate-100 transition-colors cursor-pointer" onclick="document.getElementById('file-upload-edit').click()">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-10 w-10 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-slate-600 justify-center">
                            <label for="file-upload-edit" class="relative cursor-pointer rounded-md font-medium text-blue-600 hover:text-blue-500">
                                <span>Pilih Gambar Baru</span>
                                <input id="file-upload-edit" name="gambar" type="file" class="sr-only" accept="image/png, image/jpeg, image/jpg" onchange="previewTextEdit(this)">
                            </label>
                        </div>
                        <p class="text-xs text-slate-500" id="file-name-edit">Format JPG/PNG (Maks 2MB)</p>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Nama Proyek</label>
                <input type="text" name="nama_proyek" value="{{ $proyek->nama_proyek }}" required class="w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm text-slate-900 focus:ring-blue-500 focus:border-blue-500 outline-none ring-1 ring-inset ring-slate-300">
            </div>

            <div class="mb-4">
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Lokasi Proyek</label>
                <input type="text" name="lokasi" value="{{ $proyek->lokasi }}" required class="w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm text-slate-900 focus:ring-blue-500 focus:border-blue-500 outline-none ring-1 ring-inset ring-slate-300">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Nama Pemilik / Klien</label>
                    <input type="text" name="nama_pemilik" value="{{ $proyek->nama_pemilik }}" class="w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm text-slate-900 focus:ring-blue-500 focus:border-blue-500 outline-none ring-1 ring-inset ring-slate-300">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Kontak Pemilik</label>
                    <input type="text" name="kontak_pemilik" value="{{ $proyek->kontak_pemilik }}" class="w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm text-slate-900 focus:ring-blue-500 focus:border-blue-500 outline-none ring-1 ring-inset ring-slate-300">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Anggaran / RAB (Rp)</label>
                <input type="number" name="anggaran" value="{{ $proyek->anggaran }}" min="0" required class="w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm text-slate-900 focus:ring-blue-500 focus:border-blue-500 outline-none ring-1 ring-inset ring-slate-300">
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" value="{{ $proyek->tanggal_mulai }}" required class="w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm text-slate-900 focus:ring-blue-500 focus:border-blue-500 outline-none ring-1 ring-inset ring-slate-300">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Estimasi Selesai</label>
                    <input type="date" name="estimasi_selesai" value="{{ $proyek->estimasi_selesai }}" class="w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm text-slate-900 focus:ring-blue-500 focus:border-blue-500 outline-none ring-1 ring-inset ring-slate-300">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Status Pengerjaan</label>
                <select name="status" required class="w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm text-slate-900 focus:ring-blue-500 focus:border-blue-500 outline-none ring-1 ring-inset ring-slate-300 bg-white">
                    <option value="Akan Dimulai" {{ $proyek->status == 'Akan Dimulai' ? 'selected' : '' }}>Akan Dimulai</option>
                    <option value="Berjalan" {{ $proyek->status == 'Berjalan' ? 'selected' : '' }}>Berjalan</option>
                    <option value="Ditunda" {{ $proyek->status == 'Ditunda' ? 'selected' : '' }}>Ditunda</option>
                    <option value="Selesai" {{ $proyek->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            <div class="flex justify-end space-x-3 pt-5 border-t border-slate-100">
                <button type="button" onclick="toggleModal('modalEditProyek')" class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 text-sm font-semibold rounded-xl hover:bg-slate-50 transition-colors">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition-colors shadow-md">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- 2. MODAL TUGASKAN PEKERJA -->
<div id="modalTambahPekerja" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm overflow-y-auto w-full h-full flex items-center justify-center p-4"
    role="dialog" aria-modal="true" aria-labelledby="modalTambahPekerjaTitle"
    onclick="closeModalOutside(event, 'modalTambahPekerja')">
    <div class="relative mx-auto p-6 w-full max-w-md shadow-2xl rounded-2xl bg-white border border-slate-100 my-8">
        <div class="flex justify-between items-center mb-6 border-b border-slate-100 pb-4">
            <h3 id="modalTambahPekerjaTitle" class="text-lg font-bold text-slate-800">Tugaskan Pekerja</h3>
            <button type="button" onclick="toggleModal('modalTambahPekerja')" class="text-slate-400 hover:text-slate-600 p-1 bg-slate-50 hover:bg-slate-100 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form action="{{ route('proyek.assign', $proyek->id) }}" method="POST">
            @csrf
            <div class="mb-6">
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Pilih Pekerja <span class="text-xs font-normal text-slate-400 lowercase">(belum ditugaskan)</span></label>
                <select name="pegawai_id" required class="w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm text-slate-900 focus:ring-amber-500 focus:border-amber-500 outline-none ring-1 ring-inset ring-slate-300 bg-white">
                    <option value="" disabled selected>-- Pilih Pekerja --</option>
                    @foreach ($pegawaiTersedia as $tersedia)
                    <option value="{{ $tersedia->id }}">{{ $tersedia->nama }} ({{ $tersedia->jabatan->nama_jabatan ?? 'Staf' }})</option>
                    @endforeach
                </select>
            </div>
            <div class="flex justify-end space-x-3 pt-5 border-t border-slate-100">
                <button type="button" onclick="toggleModal('modalTambahPekerja')" class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 text-sm font-semibold rounded-xl hover:bg-slate-50 transition-colors">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-slate-800 text-white text-sm font-semibold rounded-xl hover:bg-[#0c2340] transition-colors shadow-md">Tugaskan ke Tim</button>
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