@extends('layouts.admin')

@section('title', 'Manajemen Absensi & Laporan')

@section('content')

<!-- BANNER HALAMAN -->
<div class="relative bg-white rounded-xl shadow-sm border border-slate-100 p-6 lg:p-8 overflow-hidden mb-6">
    <div class="absolute inset-0 bg-cover bg-center bg-no-repeat opacity-20" style="background-image: url('{{ asset('images/flower-mkp.jpg') }}');"></div>
    <div class="relative z-10">
        <h1 class="text-2xl font-bold text-slate-800">Manajemen Absensi & Laporan Lapangan</h1>
        <p class="mt-2 text-slate-600 max-w-2xl">
            Tinjau foto progres, lokasi GPS Mandor, dan rekapitulasi data kehadiran harian pekerja sebelum melakukan validasi (persetujuan).
        </p>
    </div>
</div>

<!-- ===================================================================================== -->
<!-- BAGIAN 1: DRAFT LAPORAN (Menunggu Persetujuan) -->
<!-- ===================================================================================== -->
<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 lg:p-8 mb-6">
    <div class="mb-6">
        <h2 class="text-lg font-bold text-slate-800 border-b-2 border-amber-500 inline-block pb-1">Draft Laporan Menunggu Validasi</h2>
        <p class="text-sm text-slate-500 mt-2">Periksa foto lapangan dan daftar kehadiran sebelum menyetujui. Data yang disetujui akan masuk ke rekap gaji.</p>
    </div>

    <div class="space-y-4">
        <!-- PERBAIKAN 1: Variabel diubah menjadi $draftLaporan -->
        @forelse($draftLaporan as $laporan)
        @php
            $proyek = $laporan->proyek;
            $totalHadir = $laporan->absensis->where('status', 'Hadir')->count();
            $totalTidakHadir = $laporan->absensis->whereIn('status', ['Sakit', 'Izin', 'Alfa'])->count();
        @endphp

        <!-- KARTU BUKA-TUTUP (ACCORDION) -->
        <details class="group bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden [&_summary::-webkit-details-marker]:hidden">
            
            <!-- SUMMARY (Bagian yang selalu terlihat) -->
            <summary class="flex flex-col md:flex-row md:items-center justify-between p-4 cursor-pointer hover:bg-slate-50 transition-colors">
                
                <div class="flex items-center space-x-4 mb-3 md:mb-0">
                    <span class="transition duration-300 group-open:-rotate-180 text-slate-400 bg-white border border-slate-200 rounded-full p-1 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </span>
                    <div>
                        <h3 class="font-bold text-slate-800 text-base">{{ $proyek->nama_proyek }}</h3>
                        <p class="text-xs font-medium text-slate-500">
                            {{ \Carbon\Carbon::parse($laporan->tanggal)->translatedFormat('l, d F Y') }} • 
                            <span class="text-blue-600 font-semibold">Mandor: {{ $laporan->pembuatLaporan->nama }}</span>
                        </p>
                    </div>
                </div>

                <div class="flex items-center justify-between md:justify-end w-full md:w-auto space-x-4 ml-10 md:ml-0">
                    <div class="flex space-x-2 mr-2">
                        <span class="text-xs font-bold text-green-700 bg-green-100 px-2.5 py-1 rounded-md">{{ $totalHadir }} Hadir</span>
                        @if($totalTidakHadir > 0)
                        <span class="text-xs font-bold text-red-700 bg-red-100 px-2.5 py-1 rounded-md">{{ $totalTidakHadir }} Absen</span>
                        @endif
                    </div>

                    <!-- Tombol Setujui/Tolak -->
                    <div class="flex space-x-2">
                        <form action="{{ route('admin.absensi.setujui', ['proyek_id' => $proyek->id, 'tanggal' => $laporan->tanggal]) }}" method="POST">
                            @csrf
                            <button type="submit" class="flex items-center space-x-1 px-3 py-1.5 text-xs font-bold text-green-700 bg-green-50 hover:bg-green-100 rounded-lg border border-green-200" onclick="return confirm('Setujui Laporan & Absensi ini?')">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span>Setujui</span>
                            </button>
                        </form>
                        <form action="{{ route('admin.absensi.tolak', ['proyek_id' => $proyek->id, 'tanggal' => $laporan->tanggal]) }}" method="POST">
                            @csrf
                            <button type="submit" class="flex items-center space-x-1 px-3 py-1.5 text-xs font-bold text-red-700 bg-red-50 hover:bg-red-100 rounded-lg border border-red-200" onclick="return confirm('Tolak Laporan ini? Mandor harus mengulangnya.')">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                <span>Tolak</span>
                            </button>
                        </form>
                    </div>
                </div>
            </summary>

            <!-- KONTEN DETAIL (Muncul saat diklik) -->
            <div class="border-t border-slate-200 p-5 bg-slate-50">
                
                <!-- GRID 2 KOLOM: Kiri (Bukti Lapangan) & Kanan (Daftar Hadir) -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    <!-- Kiri: Foto & Laporan Kegiatan (Ambil 1 Kolom) -->
                    <div class="lg:col-span-1 space-y-4">
                        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                            <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Foto Lapangan</h4>
                            
                            <div class="space-y-3">
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 mb-1">FOTO PAGI / BRIEFING</p>
                                    @if($laporan->foto_pagi)
                                    <img src="{{ asset('storage/' . $laporan->foto_pagi) }}" class="w-full h-32 object-cover rounded-lg border border-slate-100" alt="Foto Pagi">
                                    @else
                                    <div class="w-full h-32 bg-slate-100 rounded-lg flex items-center justify-center text-xs text-slate-400">Tidak ada foto pagi</div>
                                    @endif
                                </div>
                                
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 mb-1">FOTO SORE / PROGRES</p>
                                    @if($laporan->foto_sore)
                                    <img src="{{ asset('storage/' . $laporan->foto_sore) }}" class="w-full h-32 object-cover rounded-lg border border-slate-100" alt="Foto Sore">
                                    @else
                                    <div class="w-full h-32 bg-slate-100 rounded-lg flex items-center justify-center text-xs text-slate-400">Belum ada foto sore</div>
                                    @endif
                                </div>
                            </div>

                            <!-- Tombol Cek GPS -->
                            @if($laporan->latitude && $laporan->longitude)
                            <a href="https://www.google.com/maps/search/?api=1&query={{ $laporan->latitude }},{{ $laporan->longitude }}" target="_blank" class="mt-4 flex items-center justify-center space-x-2 w-full bg-blue-50 hover:bg-blue-100 text-blue-600 text-xs font-bold py-2 rounded-lg border border-blue-200 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <span>Cek Titik Lokasi Mandor</span>
                            </a>
                            @endif
                        </div>

                        <!-- Catatan Laporan Harian -->
                        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                            <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Jurnal Harian</h4>
                            <div class="text-sm text-slate-700 whitespace-pre-line bg-slate-50 p-3 rounded-lg border border-slate-100">
                                {{ $laporan->kegiatan ?? 'Mandor belum menulis jurnal kegiatan harian.' }}
                            </div>
                        </div>
                    </div>

                    <!-- Kanan: Daftar Tukang (Ambil 2 Kolom) -->
                    <div class="lg:col-span-2">
                        <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Daftar Kehadiran Pegawai</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <!-- Loop Data Absensi yang nempel di Laporan ini -->
                            @foreach($laporan->absensis as $absen)
                            <div class="bg-white p-3 rounded-xl border border-slate-200 flex justify-between items-center shadow-sm">
                                <div>
                                    <p class="text-sm font-bold text-slate-800">{{ $absen->pegawai->nama }}</p>
                                    <p class="text-[10px] text-slate-500 font-medium">Durasi: {{ $absen->durasi ?? '-' }}</p>
                                    @if($absen->keterangan)
                                    <p class="text-[10px] text-amber-600 italic mt-0.5">Catatan: {{ $absen->keterangan }}</p>
                                    @endif
                                </div>
                                <div class="text-right">
                                    @if($absen->status == 'Hadir')
                                    <span class="text-green-600 font-black text-xs">HADIR</span>
                                    @elseif($absen->status == 'Sakit')
                                    <span class="text-amber-500 font-black text-xs">SAKIT</span>
                                    @elseif($absen->status == 'Izin')
                                    <span class="text-blue-600 font-black text-xs">IZIN</span>
                                    @else
                                    <span class="text-red-600 font-black text-xs">ALFA</span>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                </div> <!-- End Grid -->
            </div>
        </details>
        @empty
        <div class="text-center py-10 bg-slate-50 rounded-xl border border-dashed border-slate-300">
            <svg class="w-10 h-10 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-slate-500 text-sm font-medium">Bagus! Semua draft absensi dan laporan sudah divalidasi.</p>
        </div>
        @endforelse
    </div>
</div>

<!-- ===================================================================================== -->
<!-- BAGIAN 2: TABEL ABSENSI PEGAWAI (Data Valid) -->
<!-- ===================================================================================== -->
<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 lg:p-8">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-6 gap-4">
        <div>
            <h2 class="text-lg font-bold text-slate-800 border-b-2 border-amber-500 inline-block pb-1">Tabel Laporan Proyek (Disetujui)</h2>
            <p class="text-sm text-slate-500 mt-2">Data absensi dan progres lapangan yang sudah sah dan siap dihitung dalam penggajian.</p>
        </div>

        <form action="{{ route('admin.absensi.index') }}" method="GET" class="flex items-center space-x-3">
            <label for="filter_tanggal" class="text-sm font-medium text-slate-600">Pilih Tanggal:</label>
            <input type="date" id="filter_tanggal" name="filter_tanggal" value="{{ $tanggalFilter }}" onchange="this.form.submit()"
                class="rounded-lg border-slate-300 py-2 px-3 text-sm text-slate-700 shadow-sm focus:ring-amber-500 focus:border-amber-500 outline-none ring-1 ring-inset ring-slate-300">
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[800px]">
            <thead>
                <tr class="border-b border-slate-200">
                    <th class="py-3 px-4 text-sm font-semibold text-slate-600 bg-slate-50 rounded-tl-lg">Nama Proyek</th>
                    <th class="py-3 px-4 text-sm font-semibold text-slate-600 bg-slate-50">Pengawas</th>
                    <th class="py-3 px-4 text-sm font-semibold text-slate-600 bg-slate-50 text-center">Tukang Hadir</th>
                    <th class="py-3 px-4 text-sm font-semibold text-slate-600 bg-slate-50 text-center">Sakit/Izin/Alfa</th>
                    <th class="py-3 px-4 text-sm font-semibold text-slate-600 bg-slate-50 text-right rounded-tr-lg">Detail Bulanan</th>
                </tr>
            </thead>
            <tbody class="text-sm text-slate-700">

                <!-- PERBAIKAN 2: Variabel diubah menjadi $laporanValid -->
                @forelse($laporanValid as $laporan)
                @php
                    $proyek = $laporan->proyek;
                    $totalHadir = $laporan->absensis->where('status', 'Hadir')->count();
                    $totalTidakHadir = $laporan->absensis->whereIn('status', ['Sakit', 'Izin', 'Alfa'])->count();
                @endphp

                <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                    <td class="py-4 px-4 font-bold text-slate-800">{{ $proyek->nama_proyek }}</td>
                    <td class="py-4 px-4 text-slate-600">{{ $laporan->pembuatLaporan->nama }}</td>
                    <td class="py-4 px-4 text-center font-bold text-green-600">{{ $totalHadir }} Orang</td>
                    <td class="py-4 px-4 text-center font-bold text-amber-600">{{ $totalTidakHadir }} Orang</td>
                    <td class="py-4 px-4 text-right">
                        <!-- Tombol Lihat Detail -->
                        <a href="{{ route('admin.absensi.detail', $proyek->id) }}" class="inline-flex items-center space-x-2 px-3 py-1.5 text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors border border-blue-200" title="Lihat Riwayat 1 Bulan">
                            <span class="text-xs font-bold">Buka Riwayat</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-8 text-center text-slate-500">
                        Tidak ada laporan proyek yang valid untuk tanggal {{ \Carbon\Carbon::parse($tanggalFilter)->translatedFormat('d F Y') }}.
                    </td>
                </tr>
                @endforelse

            </tbody>
        </table>
    </div>
</div>

@endsection