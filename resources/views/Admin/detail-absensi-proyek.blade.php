@extends('layouts.admin')

@section('title', 'Detail Absensi & Laporan Proyek')

@section('content')
<!-- Header Proyek -->
<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 lg:p-8 mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Detail Riwayat Lapangan</h1>
            <p class="mt-1 text-slate-500 font-medium">Proyek: <span class="text-amber-600 font-bold">{{ $proyek->nama_proyek }}</span> - {{ $proyek->lokasi }}</p>
        </div>
        <a href="{{ route('admin.absensi.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-lg text-sm font-semibold transition-colors flex items-center shrink-0">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
    </div>
</div>

<!-- Log Laporan Bulanan -->
<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 lg:p-8">

    <!-- Filter Bulan -->
    <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-6 pb-4 border-b border-slate-100 gap-4">
        <h2 class="text-lg font-bold text-slate-800 flex items-center">
            <svg class="w-5 h-5 mr-2 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            Arsip Laporan Harian
        </h2>

        <form action="{{ route('admin.absensi.detail', $proyek->id) }}" method="GET" class="flex items-center space-x-3">
            <label for="filter_bulan" class="text-sm font-medium text-slate-600">Pilih Bulan:</label>
            <input type="month" id="filter_bulan" name="filter_bulan" value="{{ $bulanFilter }}" onchange="this.form.submit()"
                class="rounded-lg border-slate-300 py-2 px-3 text-sm text-slate-700 shadow-sm focus:ring-amber-500 focus:border-amber-500 outline-none ring-1 ring-inset ring-slate-300">
        </form>
    </div>

    <!-- Data Riwayat per Tanggal (Model Buka-Tutup) -->
    <div class="space-y-4">
        
        <!-- PERBAIKAN: Menggunakan $riwayatLaporan -->
        @forelse($riwayatLaporan as $laporan)
        @php
            // Hitung data langsung dari relasi absensis di dalam laporan ini
            $hadir = $laporan->absensis->where('status', 'Hadir')->count();
            $absen = $laporan->absensis->whereIn('status', ['Sakit', 'Izin', 'Alfa'])->count();
        @endphp

        <!-- Tag <details> akan membuat fitur buka-tutup otomatis -->
        <details class="group bg-white border border-slate-200 rounded-xl shadow-sm [&_summary::-webkit-details-marker]:hidden">

            <!-- SUMMARY: Bagian yang selalu terlihat -->
            <summary class="flex flex-col sm:flex-row sm:items-center justify-between p-4 cursor-pointer hover:bg-slate-50 rounded-xl transition-colors gap-3">
                <div class="flex items-center space-x-4">
                    <span class="transition duration-300 group-open:-rotate-180 text-slate-400 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </span>
                    <div>
                        <h3 class="font-bold text-slate-800 text-sm">
                            {{ \Carbon\Carbon::parse($laporan->tanggal)->translatedFormat('l, d F Y') }}
                        </h3>
                        <p class="text-xs text-slate-500 font-medium mt-0.5">Pengawas: {{ $laporan->pembuatLaporan->nama ?? '-' }}</p>
                    </div>
                </div>

                <div class="flex items-center space-x-2">
                    <span class="text-[11px] font-bold text-green-700 bg-green-100 px-2.5 py-1 rounded-md">{{ $hadir }} Hadir</span>
                    @if($absen > 0)
                    <span class="text-[11px] font-bold text-red-700 bg-red-100 px-2.5 py-1 rounded-md">{{ $absen }} Absen</span>
                    @endif
                    
                    <!-- Indikator Status Laporan -->
                    @if($laporan->status_validasi == 'Disetujui')
                    <span class="text-[11px] font-bold text-blue-700 bg-blue-100 px-2.5 py-1 rounded-md flex items-center"><svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Valid</span>
                    @else
                    <span class="text-[11px] font-bold text-amber-700 bg-amber-100 px-2.5 py-1 rounded-md flex items-center"><svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Pending</span>
                    @endif
                </div>
            </summary>

            <!-- KONTEN: Detail Foto & Tabel yang muncul saat panah diklik -->
            <div class="border-t border-slate-100 p-5 bg-slate-50/50">
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                    <!-- Kiri: Foto Bukti -->
                    <div class="space-y-4">
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Dokumentasi Proyek</p>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 mb-1">FOTO PAGI</p>
                                @if($laporan->foto_pagi)
                                <img src="{{ asset('storage/' . $laporan->foto_pagi) }}" class="w-full h-24 object-cover rounded-lg border border-slate-200">
                                @else
                                <div class="w-full h-24 bg-slate-100 rounded-lg flex items-center justify-center text-[10px] text-slate-400 border border-slate-200">Kosong</div>
                                @endif
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 mb-1">FOTO SORE</p>
                                @if($laporan->foto_sore)
                                <img src="{{ asset('storage/' . $laporan->foto_sore) }}" class="w-full h-24 object-cover rounded-lg border border-slate-200">
                                @else
                                <div class="w-full h-24 bg-slate-100 rounded-lg flex items-center justify-center text-[10px] text-slate-400 border border-slate-200">Kosong</div>
                                @endif
                            </div>
                        </div>
                        
                        @if($laporan->kegiatan)
                        <div class="mt-4">
                            <p class="text-[10px] font-bold text-slate-400 mb-1">CATATAN KEGIATAN & PROGRES</p>
                            <div class="text-xs text-slate-600 bg-white p-3 rounded-lg border border-slate-200 whitespace-pre-line">
                                {{ $laporan->kegiatan }}
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Kanan: Tabel Absensi (2 Kolom) -->
                    <div class="lg:col-span-2">
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Daftar Kehadiran Pegawai</p>
                        <div class="overflow-x-auto rounded-lg border border-slate-200 shadow-sm">
                            <table class="w-full text-sm text-left bg-white">
                                <thead class="bg-slate-100 text-slate-600 text-xs uppercase">
                                    <tr>
                                        <th class="py-2.5 px-4 font-semibold">Nama Pekerja</th>
                                        <th class="py-2.5 px-4 font-semibold w-24 text-center">Status</th>
                                        <th class="py-2.5 px-4 font-semibold w-28 text-center">Durasi</th>
                                        <th class="py-2.5 px-4 font-semibold">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($laporan->absensis as $absen)
                                    <tr class="hover:bg-slate-50">
                                        <td class="py-2.5 px-4 font-bold text-slate-800">{{ $absen->pegawai->nama }}</td>
                                        <td class="py-2.5 px-4 text-center">
                                            @if($absen->status == 'Hadir')
                                            <span class="text-green-600 font-black text-xs">HADIR</span>
                                            @elseif($absen->status == 'Sakit')
                                            <span class="text-amber-600 font-black text-xs">SAKIT</span>
                                            @elseif($absen->status == 'Izin')
                                            <span class="text-blue-600 font-black text-xs">IZIN</span>
                                            @else
                                            <span class="text-red-600 font-black text-xs">ALFA</span>
                                            @endif
                                        </td>
                                        <td class="py-2.5 px-4 text-center text-xs font-medium text-slate-500">{{ $absen->durasi ?? '-' }}</td>
                                        <td class="py-2.5 px-4 text-slate-500 text-xs italic">{{ $absen->keterangan ?? '-' }}</td>
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
        <div class="text-center py-12 bg-slate-50 rounded-xl border border-dashed border-slate-300">
            <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <p class="text-slate-500 text-sm font-medium">Belum ada catatan laporan & absensi untuk bulan ini.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection