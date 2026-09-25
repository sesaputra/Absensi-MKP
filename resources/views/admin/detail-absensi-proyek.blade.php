@extends('layouts.admin')

@section('title', 'Detail Absensi & Laporan Proyek')

@section('content')

<!-- ================= BANNER HALAMAN & DETAIL PROYEK ================= -->
<div class="relative bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 lg:p-8 overflow-hidden mb-6">
    <!-- Background Image & Gradient Overlay -->
    <div class="absolute inset-0 bg-cover bg-center bg-no-repeat opacity-100 pointer-events-none" 
         style="background-image: url('{{ asset('images/flower-mkp.jpg') }}');"></div>
    <div class="absolute inset-0 bg-gradient-to-r from-white/95 via-white/85 to-white/50 pointer-events-none"></div>
    
    <div class="relative z-10">
        <!-- Top Row: Badge, Judul Proyek, & Tombol Kembali -->
        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 mb-6">
            <div class="max-w-2xl">
                <!-- Badge Modul & Status -->
                <div class="inline-flex items-center space-x-2 bg-slate-100/90 border border-slate-200 px-3 py-1 rounded-full text-xs font-semibold text-slate-600 mb-3 backdrop-blur-sm shadow-2xs">
                    <span class="w-2 h-2 rounded-full bg-slate-500 animate-pulse"></span>
                    <span>Modul Validasi Operasional</span>
                    <span class="text-slate-300">•</span>
                    <span class="text-amber-700 font-bold">Detail Lapangan</span>
                </div>

                <!-- Nama Proyek Dinamis -->
                <h1 class="text-2xl lg:text-3xl font-bold text-slate-900 tracking-tight">
                    {{ $proyek->nama_proyek }}
                </h1>

                <!-- Lokasi Proyek -->
                <p class="mt-1 text-xs sm:text-sm text-slate-600 font-semibold flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span>Lokasi: <strong class="font-semibold text-slate-800">{{ $proyek->lokasi }}</strong></span>
                </p>

                <!-- Subtitle / Deskripsi Modul -->
                <p class="mt-2 text-slate-600 text-xs sm:text-sm leading-relaxed">
                    Tinjau foto progres, lokasi GPS Mandor, dan rekapitulasi data kehadiran harian pekerja sebelum melakukan validasi (persetujuan).
                </p>
            </div>

            <!-- Tombol Kembali -->
            <a href="{{ route('admin.absensi.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-white/90 hover:bg-white text-slate-700 hover:text-slate-900 text-xs font-bold rounded-xl border border-slate-200/80 shadow-2xs backdrop-blur-md transition-all shrink-0">
                <svg class="w-4 h-4 mr-1.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Daftar
            </a>
        </div>

        <!-- Ringkasan Statistik Cepat (KPI Widgets - Glassmorphism Effect) -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3.5 pt-5 border-t border-slate-200/60">
            <div class="bg-white/70 backdrop-blur-md rounded-xl p-3.5 border border-slate-200/80 shadow-2xs">
                <span class="text-[11px] font-semibold text-slate-500 block uppercase tracking-wider">Total Laporan</span>
                <span class="text-lg font-bold text-slate-800">{{ $riwayatLaporan->count() }} Hari</span>
            </div>
            <div class="bg-emerald-50/70 backdrop-blur-md rounded-xl p-3.5 border border-emerald-200/60 shadow-2xs">
                <span class="text-[11px] font-semibold text-emerald-700 block uppercase tracking-wider">Laporan Valid</span>
                <span class="text-lg font-bold text-emerald-800">{{ $riwayatLaporan->where('status_validasi', 'Disetujui')->count() }}</span>
            </div>
            <div class="bg-amber-50/70 backdrop-blur-md rounded-xl p-3.5 border border-amber-200/60 shadow-2xs">
                <span class="text-[11px] font-semibold text-amber-700 block uppercase tracking-wider">Menunggu Validasi</span>
                <span class="text-lg font-bold text-amber-800">{{ $riwayatLaporan->where('status_validasi', '!=', 'Disetujui')->count() }}</span>
            </div>
            <div class="bg-white/70 backdrop-blur-md rounded-xl p-3.5 border border-slate-200/80 shadow-2xs">
                <span class="text-[11px] font-semibold text-slate-500 block uppercase tracking-wider">Bulan Terpilih</span>
                <span class="text-lg font-bold text-slate-800">{{ \Carbon\Carbon::parse($bulanFilter)->translatedFormat('F Y') }}</span>
            </div>
        </div>
    </div>
</div>

<!-- ================= LOG LAPORAN BULANAN ================= -->
<div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 p-6">

    <!-- Filter & Header Section -->
    <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-6 pb-4 border-b border-slate-100 gap-4">
        <div>
            <h2 class="text-base font-bold text-slate-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Arsip Laporan Harian
            </h2>
            <p class="text-xs text-slate-400 mt-0.5">Klik pada tanggal untuk melihat dokumentasi foto dan rincian absensi.</p>
        </div>

        <form action="{{ route('admin.absensi.detail', $proyek->id) }}" method="GET" class="flex items-center gap-2 bg-slate-50 p-1.5 rounded-xl border border-slate-200">
            <label for="filter_bulan" class="text-xs font-semibold text-slate-500 pl-2">Filter:</label>
            <input type="month" id="filter_bulan" name="filter_bulan" value="{{ $bulanFilter }}" onchange="this.form.submit()"
                class="rounded-lg border-slate-200 bg-white py-1.5 px-3 text-xs text-slate-700 font-semibold shadow-2xs focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none">
        </form>
    </div>

    <!-- Data Riwayat per Tanggal (Model Buka-Tutup / Accordion) -->
    <div class="space-y-3">
        @forelse($riwayatLaporan as $laporan)
        @php
            $hadir = $laporan->absensis->where('status', 'Hadir')->count();
            $absen = $laporan->absensis->whereIn('status', ['Sakit', 'Izin', 'Alfa'])->count();
            $isValid = $laporan->status_validasi == 'Disetujui';
        @endphp

        <!-- Item Accordion Card -->
        <details class="group bg-white border border-slate-200/80 rounded-xl shadow-2xs transition-all overflow-hidden [&_summary::-webkit-details-marker]:hidden border-l-4 {{ $isValid ? 'border-l-emerald-500' : 'border-l-amber-500' }}">

            <!-- SUMMARY HEADER -->
            <summary class="flex flex-col sm:flex-row sm:items-center justify-between p-4 cursor-pointer hover:bg-slate-50/80 transition-colors gap-3">
                <div class="flex items-center gap-3.5">
                    <span class="p-1.5 rounded-lg bg-slate-100 text-slate-400 group-open:bg-amber-100 group-open:text-amber-600 transition-colors shrink-0">
                        <svg class="w-4 h-4 transition-transform duration-300 group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </span>
                    <div>
                        <h3 class="font-bold text-slate-800 text-sm">
                            {{ \Carbon\Carbon::parse($laporan->tanggal)->translatedFormat('l, d F Y') }}
                        </h3>
                        <p class="text-xs text-slate-400 font-medium mt-0.5 flex items-center gap-1">
                            <span>Pengawas:</span>
                            <span class="text-slate-600 font-semibold">{{ $laporan->pembuatLaporan->nama ?? '-' }}</span>
                        </p>
                    </div>
                </div>

                <!-- Status Pills Ringkas -->
                <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap">
                    <span class="text-[11px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200/60 px-2.5 py-1 rounded-lg">
                        {{ $hadir }} Hadir
                    </span>
                    @if($absen > 0)
                    <span class="text-[11px] font-bold text-rose-700 bg-rose-50 border border-rose-200/60 px-2.5 py-1 rounded-lg">
                        {{ $absen }} Absen
                    </span>
                    @endif
                    
                    <span class="h-4 w-px bg-slate-200 hidden sm:block"></span>

                    @if($isValid)
                    <span class="text-[11px] font-bold text-emerald-700 bg-emerald-100/70 px-2.5 py-1 rounded-lg inline-flex items-center gap-1">
                        <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg> 
                        Valid
                    </span>
                    @else
                    <span class="text-[11px] font-bold text-amber-700 bg-amber-100/70 px-2.5 py-1 rounded-lg inline-flex items-center gap-1">
                        <svg class="w-3 h-3 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> 
                        Pending
                    </span>
                    @endif
                </div>
            </summary>

            <!-- KONTEN DETAIL -->
            <div class="border-t border-slate-100 p-5 bg-slate-50/40">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    <!-- Kiri: Foto Bukti & Catatan -->
                    <div class="space-y-4">
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Dokumentasi Proyek</p>
                        
                        <div class="grid grid-cols-2 gap-3">
                            <!-- Foto Pagi -->
                            <div class="group/img relative rounded-xl overflow-hidden border border-slate-200 bg-white p-1">
                                <p class="text-[10px] font-bold text-slate-400 px-1 py-0.5">FOTO PAGI</p>
                                @if($laporan->foto_pagi)
                                <div class="relative overflow-hidden rounded-lg">
                                    <img src="{{ asset('storage/' . $laporan->foto_pagi) }}" class="w-full h-28 object-cover group-hover/img:scale-105 transition-transform duration-300">
                                    <a href="{{ asset('storage/' . $laporan->foto_pagi) }}" target="_blank" class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover/img:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-semibold">
                                        Perbesar ↗
                                    </a>
                                </div>
                                @else
                                <div class="w-full h-28 bg-slate-100/80 rounded-lg flex flex-col items-center justify-center text-slate-400 gap-1 border border-dashed border-slate-200">
                                    <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="text-[10px]">Kosong</span>
                                </div>
                                @endif
                            </div>

                            <!-- Foto Sore -->
                            <div class="group/img relative rounded-xl overflow-hidden border border-slate-200 bg-white p-1">
                                <p class="text-[10px] font-bold text-slate-400 px-1 py-0.5">FOTO SORE</p>
                                @if($laporan->foto_sore)
                                <div class="relative overflow-hidden rounded-lg">
                                    <img src="{{ asset('storage/' . $laporan->foto_sore) }}" class="w-full h-28 object-cover group-hover/img:scale-105 transition-transform duration-300">
                                    <a href="{{ asset('storage/' . $laporan->foto_sore) }}" target="_blank" class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover/img:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-semibold">
                                        Perbesar ↗
                                    </a>
                                </div>
                                @else
                                <div class="w-full h-28 bg-slate-100/80 rounded-lg flex flex-col items-center justify-center text-slate-400 gap-1 border border-dashed border-slate-200">
                                    <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="text-[10px]">Kosong</span>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Catatan Progress -->
                        @if($laporan->kegiatan)
                        <div>
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Catatan Progress</p>
                            <div class="text-xs text-slate-700 bg-white p-3 rounded-xl border border-slate-200 leading-relaxed whitespace-pre-line shadow-2xs">
                                {{ $laporan->kegiatan }}
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Kanan: Tabel Absensi Pekerja -->
                    <div class="lg:col-span-2">
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-3">Daftar Kehadiran Pekerja</p>
                        <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-2xs">
                            <table class="w-full text-xs text-left">
                                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider border-b border-slate-100 font-bold">
                                    <tr>
                                        <th class="py-3 px-4">Nama Pekerja</th>
                                        <th class="py-3 px-4 w-28 text-center">Status</th>
                                        <th class="py-3 px-4 w-24 text-center">Durasi</th>
                                        <th class="py-3 px-4">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($laporan->absensis as $absen)
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <!-- Nama & Avatar Initials -->
                                        <td class="py-2.5 px-4">
                                            <div class="flex items-center gap-2.5">
                                                <div class="w-7 h-7 rounded-full bg-slate-100 text-slate-600 font-bold flex items-center justify-center text-[10px] shrink-0 border border-slate-200">
                                                    {{ strtoupper(substr($absen->pegawai->nama ?? 'P', 0, 2)) }}
                                                </div>
                                                <span class="font-bold text-slate-800">{{ $absen->pegawai->nama }}</span>
                                            </div>
                                        </td>
                                        <!-- Status Badge -->
                                        <td class="py-2.5 px-4 text-center">
                                            @if($absen->status == 'Hadir')
                                            <span class="inline-block px-2.5 py-0.5 text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-md">HADIR</span>
                                            @elseif($absen->status == 'Sakit')
                                            <span class="inline-block px-2.5 py-0.5 text-[10px] font-bold text-amber-700 bg-amber-50 border border-amber-200 rounded-md">SAKIT</span>
                                            @elseif($absen->status == 'Izin')
                                            <span class="inline-block px-2.5 py-0.5 text-[10px] font-bold text-blue-700 bg-blue-50 border border-blue-200 rounded-md">IZIN</span>
                                            @else
                                            <span class="inline-block px-2.5 py-0.5 text-[10px] font-bold text-rose-700 bg-rose-50 border border-rose-200 rounded-md">ALFA</span>
                                            @endif
                                        </td>
                                        <td class="py-2.5 px-4 text-center text-slate-500 font-medium">{{ $absen->durasi ?? '-' }}</td>
                                        <td class="py-2.5 px-4 text-slate-500 italic">{{ $absen->keterangan ?? '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </details>
        @empty
        <!-- Empty State -->
        <div class="text-center py-16 bg-slate-50/50 rounded-2xl border border-dashed border-slate-200">
            <div class="w-12 h-12 bg-white rounded-xl shadow-2xs border border-slate-200 flex items-center justify-center mx-auto mb-3 text-slate-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <h3 class="text-sm font-bold text-slate-700 mb-1">Belum Ada Data Laporan</h3>
            <p class="text-xs text-slate-400 max-w-sm mx-auto">Tidak ditemukan arsip laporan harian dan absensi untuk bulan {{ \Carbon\Carbon::parse($bulanFilter)->translatedFormat('F Y') }}.</p>
        </div>
        @endforelse
    </div>
</div>

@endsection