@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<!-- ============================================== -->
<!-- BANNER ACTIONABLE (NOTIFIKASI TUGAS ADMIN) -->
<!-- ============================================== -->
@if(isset($totalPendingValidasi) && $totalPendingValidasi > 0)
<div class="mb-8 bg-amber-50 border-l-4 border-amber-500 p-4 md:p-5 rounded-r-xl shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4 animate-pulse-slow">
    <div class="flex items-start sm:items-center gap-3">
        <div class="p-2 bg-amber-100 text-amber-600 rounded-lg shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
        </div>
        <div>
            <h3 class="text-amber-800 font-bold text-sm md:text-base">Tugas Validasi Menunggu!</h3>
            <p class="text-amber-700 text-xs md:text-sm mt-0.5">Terdapat <span class="font-black text-amber-900">{{ $totalPendingValidasi }} Laporan Harian & Absensi</span> dari mandor yang belum Anda setujui hari ini.</p>
        </div>
    </div>
    <a href="{{ route('admin.absensi.index') }}" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold rounded-lg transition-colors whitespace-nowrap text-center shadow-sm">
        Periksa Laporan &rarr;
    </a>
</div>
@endif

<!-- ============================================== -->
<!-- KARTU RINGKASAN (Diperbarui) -->
<!-- ============================================== -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Kartu 1: Proyek Selesai -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 flex items-center justify-between hover:shadow-md transition-shadow relative overflow-hidden">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-50/50 rounded-full"></div>
        <div class="relative z-10">
            <p class="text-sm font-bold text-slate-500 mb-1 uppercase tracking-wider">Proyek Selesai</p>
            <h3 class="text-3xl font-black text-slate-800">{{ $totalProyekSelesai }}</h3>
        </div>
        <div class="p-3 bg-blue-50 text-blue-600 rounded-xl relative z-10 border border-blue-100">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
    </div>

    <!-- Kartu 2: Proyek Aktif -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 flex items-center justify-between hover:shadow-md transition-shadow relative overflow-hidden">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-indigo-50/50 rounded-full"></div>
        <div class="relative z-10">
            <p class="text-sm font-bold text-slate-500 mb-1 uppercase tracking-wider">Proyek Aktif</p>
            <h3 class="text-3xl font-black text-slate-800">{{ $totalProyekAktif }}</h3>
        </div>
        <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl relative z-10 border border-indigo-100">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
        </div>
    </div>

    <!-- Kartu 3: Absensi Hari Ini (Diubah dari Total Pegawai) -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 flex items-center justify-between hover:shadow-md transition-shadow relative overflow-hidden">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-50/50 rounded-full"></div>
        <div class="relative z-10">
            <p class="text-sm font-bold text-slate-500 mb-1 uppercase tracking-wider">Total Tukang</p>
            <div class="flex items-baseline gap-2">
                <h3 class="text-3xl font-black text-slate-800">{{ $totalPegawai }}</h3>
                <span class="text-xs font-semibold text-slate-400">Orang</span>
            </div>
        </div>
        <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl relative z-10 border border-emerald-100">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
        </div>
    </div>
</div>

<!-- ============================================== -->
<!-- ANALISIS KESEHATAN PROYEK -->
<!-- ============================================== -->
<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 lg:p-8 mb-8">
    <div class="mb-6">
        <h2 class="text-lg font-bold text-slate-800">Analisis Kesehatan Proyek Aktif</h2>
        <p class="text-sm text-slate-500 mt-1">Bagian ini menampilkan analisis cepat proyek aktif Anda.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse($proyekKesehatan as $proyek)
        <!-- Card Proyek Dinamis -->
        <a href="{{ route('proyek.keuangan', $proyek->id) }}" class="group flex flex-col bg-white rounded-2xl border border-slate-100 hover:border-blue-200 hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] transition-all duration-300 overflow-hidden relative h-full">

            <!-- Indikator Kesehatan Kas (Melayang di Kanan Atas Foto) -->
            <div class="absolute top-4 right-4 z-10">
                <span class="flex h-3 w-3 relative" title="{{ $proyek->health_status }}">
                    @if($proyek->kas_tersedia <= 5000000)
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $proyek->health_dot }} opacity-75"></span>
                @endif
                <span class="relative inline-flex rounded-full h-3 w-3 {{ $proyek->health_dot }} shadow-sm border border-white"></span>
                </span>
            </div>

            <!-- Area Foto Proyek (Lebih Bersih Tanpa Teks) -->
            <div class="h-40 w-full bg-slate-50 relative overflow-hidden shrink-0">
                <!-- PERBAIKAN: Ubah $proyek->foto menjadi $proyek->gambar -->
                @if(!empty($proyek->gambar))
                <img src="{{ asset('storage/' . $proyek->gambar) }}" alt="{{ $proyek->nama_proyek }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out">
                @else
                <div class="w-full h-full bg-gradient-to-br from-slate-50 to-slate-100 flex items-center justify-center group-hover:scale-105 transition-transform duration-700 ease-out">
                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-b from-black/5 to-transparent"></div>
            </div>

            <!-- Area Konten & Metrik -->
            <div class="p-5 flex-1 flex flex-col justify-between">

                <div class="mb-5 flex flex-col gap-1.5">

                    <!-- Nama Proyek & Status (Kini Berdampingan) -->
                    <div class="flex items-start justify-between gap-3 mb-1">
                        <h4 class="text-lg font-bold text-slate-800 group-hover:text-blue-600 transition-colors line-clamp-2 leading-tight" title="{{ $proyek->nama_proyek }}">
                            {{ $proyek->nama_proyek }}
                        </h4>
                        <!-- Label Sedang Berjalan dipindah ke sini -->
                        <span class="inline-flex items-center px-2 py-1 rounded-md text-[9px] font-bold bg-blue-50 text-blue-600 border border-blue-100 shrink-0 mt-0.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-1.5 animate-pulse"></span>
                            Berjalan
                        </span>
                    </div>

                    <!-- Metadata: Lokasi & RAB -->
                    <div class="flex flex-col gap-1.5 mt-1">
                        <!-- Info Lokasi -->
                        <div class="flex items-center text-[11px] font-medium text-slate-400">
                            <svg class="w-3.5 h-3.5 mr-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            </svg>
                            <span class="truncate">{{ $proyek->lokasi ?? 'Lokasi belum diatur' }}</span>
                        </div>

                        <!-- Info RAB -->
                        <div class="flex items-center text-[11px] font-medium text-slate-400">
                            <svg class="w-3.5 h-3.5 mr-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            <span class="truncate">
                                Nilai RAB: <span class="font-semibold text-slate-500">Rp {{ number_format($proyek->anggaran, 0, ',', '.') }}</span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Metrik Kas -->
                <div>
                    <div class="flex items-end justify-between mb-2.5">
                        <div>
                            <p class="text-[9px] font-bold uppercase tracking-widest text-slate-400 mb-1">Dana Kas Tersedia</p>
                            <div class="flex items-baseline gap-1">
                                <span class="text-xs font-semibold {{ $proyek->kas_tersedia < 0 ? 'text-red-400' : 'text-slate-400' }}">
                                    {{ $proyek->kas_tersedia < 0 ? '-' : '' }}Rp
                                </span>
                                <span class="text-2xl font-bold {{ $proyek->kas_tersedia < 0 ? 'text-red-600' : 'text-slate-800' }} tabular-nums tracking-normal">
                                    {{ number_format(abs($proyek->kas_tersedia), 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                        <div class="text-right pb-1">
                            <span class="text-[11px] font-bold {{ $proyek->health_color }} bg-transparent px-0">{{ number_format($proyek->persentase_terpakai, 0) }}%</span>
                        </div>
                    </div>

                    <div class="w-full bg-slate-100/80 rounded-full h-1.5 overflow-hidden">
                        <div class="h-1.5 rounded-full {{ $proyek->health_dot }} transition-all duration-500" style="width: {{ $proyek->persentase_terpakai }}%;"></div>
                    </div>
                </div>
            </div>
        </a>
        @empty
        <div class="col-span-3 text-center py-10">
            <p class="text-slate-500 font-medium">Belum ada proyek yang berstatus aktif.</p>
        </div>
        @endforelse
    </div>

    <!-- Tombol Selengkapnya -->
    <div class="mt-8 text-center">
        <a href="{{ route('proyek.index') }}" class="inline-block px-6 py-2 bg-slate-50 border border-slate-200 hover:bg-slate-100 text-slate-700 text-sm font-bold rounded-lg transition-colors">
            Lihat Semua Proyek
        </a>
    </div>
</div>

<!-- ============================================== -->
<!-- DAFTAR PEGAWAI TERBARU -->
<!-- ============================================== -->
<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 lg:p-8">
    <div class="mb-6 flex justify-between items-end">
        <div>
            <h2 class="text-lg font-bold text-slate-800">Daftar Pegawai</h2>
            <p class="text-sm text-slate-500 mt-1">Staf operasional dan tukang yang terdaftar di sistem.</p>
        </div>
        <a href="{{ route('pegawai.index') }}" class="text-sm font-bold text-blue-600 hover:text-blue-700">Lihat Semua &rarr;</a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-slate-200">
                    <th class="py-3 px-4 text-sm font-semibold text-slate-600 bg-slate-50 rounded-tl-lg">Nama Pegawai</th>
                    <th class="py-3 px-4 text-sm font-semibold text-slate-600 bg-slate-50">Jabatan</th>
                    <th class="py-3 px-4 text-sm font-semibold text-slate-600 bg-slate-50 rounded-tr-lg">Nomor Telepon</th>
                </tr>
            </thead>
            <tbody class="text-sm text-slate-700 divide-y divide-slate-100">
                @forelse($pegawais as $pegawai)
                <tr class="hover:bg-slate-50/70 transition-colors">
                    <td class="py-4 px-4 font-bold text-slate-800">{{ $pegawai->nama }}</td>
                    <td class="py-4 px-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold {{ strtolower($pegawai->jabatan->nama_jabatan ?? '') == 'mandor' ? 'bg-blue-50 text-blue-700' : 'bg-slate-100 text-slate-600' }}">
                            {{ $pegawai->jabatan->nama_jabatan ?? 'Belum Diatur' }}
                        </span>
                    </td>
                    <td class="py-4 px-4 font-medium text-slate-600">{{ $pegawai->no_telp ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="py-8 text-center text-slate-500 font-medium">Belum ada data pegawai yang terdaftar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection