@extends('layouts.admin')

@section('title', 'Buku Harian Lapangan')

@section('content')

<!-- ============================================== -->
<!-- HEADER HALAMAN (DESAIN MINIMALIS & MENYATU) -->
<!-- ============================================== -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 md:p-8 mb-8">

    <!-- Baris Atas: Tombol Kembali & Label -->
    <div class="flex items-center justify-between mb-6 pb-6 border-b border-slate-100">
        <!-- Tombol Kembali Terintegrasi -->
        <a href="{{ route('proyek.show', $proyek->id) }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-900 transition-colors group">
            <div class="w-8 h-8 rounded-full bg-slate-50 border border-slate-200 flex items-center justify-center mr-3 group-hover:bg-slate-100 transition-colors">
                <svg class="w-4 h-4 text-slate-500 group-hover:text-slate-800 transform group-hover:-translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </div>
            Kembali ke Detail Proyek
        </a>

        <!-- Label Status -->
        <span class="hidden sm:inline-flex px-3 py-1.5 rounded-lg bg-slate-50 border border-slate-100 text-slate-500 text-[10px] font-bold uppercase tracking-widest">
            Buku Harian Lapangan
        </span>
    </div>

    <!-- Baris Bawah: Info Utama Proyek, Filter Bulan & Statistik -->
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6">
        <!-- Sisi Kiri: Judul -->
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900 mb-2 tracking-tight">{{ $proyek->nama_proyek }}</h1>
            <p class="text-sm text-slate-500 flex items-center font-medium">
                <svg class="w-4 h-4 mr-1.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                {{ $proyek->lokasi }}
            </p>
        </div>

        <!-- Sisi Kanan: Filter Bulan & Metrik -->
        <div class="flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-6 border-t border-slate-100 pt-4 lg:border-t-0 lg:pt-0">

            <!-- FORM FILTER PER BULAN -->
            @if(isset($availableMonths) && $availableMonths->count() > 0)
            <form method="GET" action="{{ route('proyek.laporan.admin', $proyek->id) }}" class="relative w-full sm:w-auto">
                <select name="bulan" onchange="this.form.submit()" class="appearance-none bg-slate-50 border border-slate-200 text-slate-700 text-sm font-bold rounded-xl focus:ring-slate-800 focus:border-slate-800 outline-none block w-full py-2.5 pl-4 pr-10 shadow-sm cursor-pointer hover:bg-slate-100 transition-colors">
                    @foreach($availableMonths as $monthYear)
                    <option value="{{ $monthYear }}" {{ $selectedMonth == $monthYear ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::createFromFormat('Y-m', $monthYear)->translatedFormat('F Y') }}
                    </option>
                    @endforeach
                </select>
                <!-- Ikon Panah Dropdown -->
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </form>
            @endif

            <!-- Metrik Total (Bulan Ini) -->
            <div class="text-left sm:text-right sm:pl-6 sm:border-l border-slate-200">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Catatan Bulan Ini</p>
                <div class="flex items-baseline justify-start sm:justify-end gap-1.5">
                    <p class="text-3xl md:text-4xl font-black text-slate-800 leading-none">{{ $riwayatLaporan->count() }}</p>
                    <p class="text-sm font-semibold text-slate-500">Hari</p>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- ============================================== -->
<!-- DESAIN TIMELINE (GARIS WAKTU) DENGAN FOTO -->
<!-- ============================================== -->
<div class="max-w-4xl">

    @if($riwayatLaporan->count() > 0)
    <!-- Garis Vertikal Utama -->
    <div class="relative border-l-2 border-slate-200 ml-4 md:ml-6 space-y-8 pb-8">

        @foreach($riwayatLaporan as $laporan)
        <!-- Item Laporan (1 Hari) -->
        <div class="relative pl-8 md:pl-10 group">

            <!-- Titik Bulat pada Garis -->
            <div class="absolute -left-[9px] top-5 w-4 h-4 rounded-full bg-white border-[3px] border-[#0c2340] group-hover:border-amber-500 group-hover:scale-125 transition-all duration-300 shadow-sm z-10"></div>

            <!-- KARTU LAPORAN -->
            <div class="bg-white rounded-2xl shadow-sm hover:shadow-md border border-slate-200 transition-all duration-300 overflow-hidden mb-6">

                <!-- Header Kartu: Info Pelapor & Waktu -->
                <div class="bg-slate-50/80 px-5 py-4 border-b border-slate-100 flex flex-wrap items-center justify-between gap-4">
                    <!-- Kiri: Profil Pelapor -->
                    <div class="flex items-center gap-3">
                        <div class="relative">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-[#0c2340] to-slate-700 text-white flex items-center justify-center font-bold text-sm shadow-md">
                                {{ substr($laporan->pegawai->nama ?? 'A', 0, 1) }}
                            </div>
                            <div class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 bg-green-500 border-2 border-white rounded-full"></div>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-800 leading-none">{{ $laporan->pegawai->nama ?? 'Sistem / Admin' }}</h3>
                            <p class="text-[11px] font-medium text-slate-500 mt-1">{{ $laporan->pegawai->jabatan->nama_jabatan ?? 'Pengawas Lapangan' }}</p>
                        </div>
                    </div>

                    <!-- Kanan: Tanggal & Jam Kirim -->
                    <div class="text-right flex-shrink-0">
                        <p class="text-sm font-bold text-slate-800 leading-none mb-1">
                            {{ \Carbon\Carbon::parse($laporan->tanggal)->translatedFormat('d M Y') }}
                        </p>
                        <p class="text-[11px] font-semibold text-[#0c2340] flex items-center justify-end bg-slate-200/50 px-2 py-0.5 rounded-md inline-flex">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $laporan->created_at->format('H:i') }} WITA
                        </p>
                    </div>
                </div>

                <!-- Body Kartu: Isi Catatan, Progres, & Foto -->
                <div class="p-5 md:p-6">

                    <!-- 1. TEKS CATATAN -->
                    <div class="flex gap-4 mb-6 border-b border-slate-100 pb-6">
                        <div class="hidden sm:flex mt-1 w-8 h-8 rounded-full bg-slate-50 items-center justify-center border border-slate-200 shrink-0">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Jurnal Lapangan:</h4>
                            <div class="relative">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-slate-200 rounded-full"></div>
                                <p class="text-sm text-slate-700 leading-relaxed pl-4 whitespace-pre-line">
                                    {{ $laporan->kegiatan ?: 'Tidak ada catatan kegiatan.' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- 2. PROGRES FISIK (BARU DITAMBAHKAN) -->
                    <div class="flex gap-4 mb-6">
                        <div class="hidden sm:flex mt-1 w-8 h-8 rounded-full bg-slate-50 items-center justify-center border border-slate-200 shrink-0">
                            <svg class="w-4 h-4 text-[#0c2340]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-3">Pencapaian Fisik Proyek:</h4>

                            <!-- Grid Item Pekerjaan -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @php
                                $snapshot = $laporan->progres_snapshot ? json_decode($laporan->progres_snapshot, true) : [];
                                @endphp

                                @forelse($snapshot as $item)
                                <div class="bg-slate-50 border border-slate-100 p-3 rounded-xl">
                                    <div class="flex justify-between items-end mb-2">
                                        <span class="text-[13px] font-bold text-slate-700 truncate pr-2">{{ $item['nama_pekerjaan'] }}</span>
                                        <span class="text-xs font-black {{ $item['progres'] > 0 ? 'text-[#0c2340]' : 'text-slate-400' }}">{{ $item['progres'] }}%</span>
                                    </div>
                                    <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden">
                                        <div class="{{ $item['progres'] > 0 ? 'bg-[#0c2340]' : 'bg-slate-300' }} h-1.5 rounded-full" style="width: {{ $item['progres'] }}%"></div>
                                    </div>
                                </div>
                                @empty
                                <p class="text-xs text-slate-400 italic pl-1">Tidak ada catatan progres pada laporan hari ini.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- 3. GALERI FOTO -->
                    @if($laporan->foto_pagi || $laporan->foto_sore)
                    <div class="border-t border-slate-100 pt-5 mt-2">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Dokumentasi Visual:</h4>

                            <!-- Tombol GPS Kecil -->
                            @if($laporan->latitude && $laporan->longitude)
                            <a href="https://www.google.com/maps/search/?api=1&query={{ $laporan->latitude }},{{ $laporan->longitude }}" target="_blank" class="inline-flex items-center px-2.5 py-1 text-[10px] font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 border border-slate-200 rounded-md transition-colors">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Buka Titik GPS
                            </a>
                            @endif
                        </div>

                        <!-- Grid Foto 2 Kolom -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @if($laporan->foto_pagi)
                            <div class="group/foto relative rounded-xl overflow-hidden border border-slate-200 bg-slate-50 shadow-sm cursor-zoom-in">
                                <div class="absolute top-2 left-2 bg-black/60 backdrop-blur-md text-white text-[9px] font-bold px-2 py-1 rounded uppercase tracking-widest z-10">Briefing Pagi</div>
                                <img src="{{ asset('storage/' . $laporan->foto_pagi) }}" class="w-full h-48 object-cover group-hover/foto:scale-105 transition-transform duration-500" alt="Foto Pagi">
                            </div>
                            @endif

                            @if($laporan->foto_sore)
                            <div class="group/foto relative rounded-xl overflow-hidden border border-slate-200 bg-slate-50 shadow-sm cursor-zoom-in">
                                <div class="absolute top-2 left-2 bg-[#0c2340]/80 backdrop-blur-md text-white text-[9px] font-bold px-2 py-1 rounded uppercase tracking-widest z-10">Progres Sore</div>
                                <img src="{{ asset('storage/' . $laporan->foto_sore) }}" class="w-full h-48 object-cover group-hover/foto:scale-105 transition-transform duration-500" alt="Foto Sore">
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <!-- Tampilan Jika Kosong -->
    <div class="bg-white rounded-2xl border border-dashed border-slate-300 p-12 text-center max-w-2xl">
        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
            <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477-4.5 1.253"></path>
            </svg>
        </div>
        <h3 class="text-lg font-bold text-slate-800 mb-1">Belum Ada Catatan Lapangan</h3>
        <p class="text-sm text-slate-500">Pengawas lapangan belum mengirimkan laporan harian untuk bulan ini. Data akan otomatis muncul di sini setelah laporan dikirim.</p>
    </div>
    @endif

</div>

@endsection