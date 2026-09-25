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
            <h3 class="text-amber-900 font-bold text-sm md:text-base">Tugas Validasi Menunggu!</h3>
            <!-- Penyesuaian: Mengubah font-black menjadi font-bold agar lebih bersih -->
            <p class="text-amber-800 text-xs md:text-sm mt-0.5">Terdapat <span class="font-bold text-amber-950 underline decoration-amber-300">{{ $totalPendingValidasi }} Laporan Harian & Absensi</span> dari mandor yang belum Anda setujui hari ini.</p>
        </div>
    </div>
    <a href="{{ route('admin.absensi.index') }}" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-xs md:text-sm font-bold rounded-lg transition-colors whitespace-nowrap text-center shadow-sm">
        Periksa Laporan &rarr;
    </a>
</div>
@endif

<!-- ============================================== -->
<!-- KARTU RINGKASAN METRIK -->
<!-- ============================================== -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
    <!-- Kartu 1: Proyek Selesai -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200/60 flex items-center justify-between hover:shadow-md hover:border-slate-300 transition-all group relative overflow-hidden">
        <div class="absolute -right-4 -top-4 w-20 h-20 bg-blue-50/60 rounded-full transition-transform group-hover:scale-110"></div>
        <div class="relative z-10">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Proyek Selesai</p>
            <h3 class="text-3xl font-extrabold text-slate-800 tracking-tight tabular-nums">{{ $totalProyekSelesai }}</h3>
        </div>
        <div class="p-3.5 bg-blue-50 text-blue-600 rounded-2xl relative z-10 border border-blue-100/80 group-hover:bg-blue-600 group-hover:text-white transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
    </div>

    <!-- Kartu 2: Proyek Aktif -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200/60 flex items-center justify-between hover:shadow-md hover:border-slate-300 transition-all group relative overflow-hidden">
        <div class="absolute -right-4 -top-4 w-20 h-20 bg-indigo-50/60 rounded-full transition-transform group-hover:scale-110"></div>
        <div class="relative z-10">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Proyek Aktif</p>
            <h3 class="text-3xl font-extrabold text-slate-800 tracking-tight tabular-nums">{{ $totalProyekAktif }}</h3>
        </div>
        <div class="p-3.5 bg-indigo-50 text-indigo-600 rounded-2xl relative z-10 border border-indigo-100/80 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
        </div>
    </div>

    <!-- Kartu 3: Total Tukang/Pegawai -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200/60 flex items-center justify-between hover:shadow-md hover:border-slate-300 transition-all group relative overflow-hidden">
        <div class="absolute -right-4 -top-4 w-20 h-20 bg-emerald-50/60 rounded-full transition-transform group-hover:scale-110"></div>
        <div class="relative z-10">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Total Tukang</p>
            <div class="flex items-baseline gap-1.5">
                <h3 class="text-3xl font-extrabold text-slate-800 tracking-tight tabular-nums">{{ $totalPegawai }}</h3>
                <span class="text-xs font-semibold text-slate-400">Orang</span>
            </div>
        </div>
        <div class="p-3.5 bg-emerald-50 text-emerald-600 rounded-2xl relative z-10 border border-emerald-100/80 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
        </div>
    </div>
</div>

<!-- ============================================== -->
<!-- ANALISIS KESEHATAN PROYEK -->
<!-- ============================================== -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6 lg:p-8 mb-8">
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-2">
        <div>
            <h2 class="text-lg font-bold text-slate-800 tracking-tight">Analisis Kesehatan Proyek Aktif</h2>
            <p class="text-xs md:text-sm text-slate-500 mt-0.5">Ringkasan kondisi keuangan dan progress proyek yang sedang berjalan.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse($proyekKesehatan as $proyek)
        <!-- Card Proyek Dinamis -->
        <a href="{{ route('proyek.keuangan', $proyek->id) }}" class="group flex flex-col bg-white rounded-2xl border border-slate-200/80 hover:border-blue-300 hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] transition-all duration-300 overflow-hidden relative h-full">

            <!-- Indikator Kesehatan Kas -->
            <div class="absolute top-4 right-4 z-10">
                <span class="flex h-3 w-3 relative" title="{{ $proyek->health_status }}">
                    @if($proyek->kas_tersedia <= 5000000)
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $proyek->health_dot }} opacity-75"></span>
                    @endif
                    <span class="relative inline-flex rounded-full h-3 w-3 {{ $proyek->health_dot }} shadow-sm border border-white"></span>
                </span>
            </div>

            <!-- Area Foto Proyek -->
            <div class="h-40 w-full bg-slate-50 relative overflow-hidden shrink-0">
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

                <div class="mb-5 flex flex-col gap-2">

                    <!-- Nama Proyek & Status -->
                    <div class="flex items-start justify-between gap-3">
                        <h4 class="text-base font-bold text-slate-800 group-hover:text-blue-600 transition-colors line-clamp-2 leading-snug" title="{{ $proyek->nama_proyek }}">
                            {{ $proyek->nama_proyek }}
                        </h4>
                        <!-- Penyesuaian: text-[10px] menggantikan text-[9px] -->
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-blue-50 text-blue-600 border border-blue-100 shrink-0 mt-0.5 tracking-wide">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-1.5 animate-pulse"></span>
                            Berjalan
                        </span>
                    </div>

                    <!-- Metadata: Lokasi & RAB -->
                    <div class="flex flex-col gap-1.5 mt-0.5">
                        <!-- Info Lokasi (Penyesuaian: text-xs menggantikan text-[11px]) -->
                        <div class="flex items-center text-xs font-medium text-slate-500">
                            <svg class="w-3.5 h-3.5 mr-1.5 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            </svg>
                            <span class="truncate">{{ $proyek->lokasi ?? 'Lokasi belum diatur' }}</span>
                        </div>

                        <!-- Info RAB -->
                        <div class="flex items-center text-xs font-medium text-slate-500">
                            <svg class="w-3.5 h-3.5 mr-1.5 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            <span class="truncate">
                                Nilai RAB: <span class="font-bold text-slate-700 tabular-nums">Rp {{ number_format($proyek->anggaran, 0, ',', '.') }}</span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Metrik Kas -->
                <div class="pt-2 border-t border-slate-100">
                    <div class="flex items-end justify-between mb-2">
                        <div>
                            <!-- Penyesuaian: text-[10px] menggantikan text-[9px] -->
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-0.5">Dana Kas Tersedia</p>
                            <div class="flex items-baseline gap-1">
                                <span class="text-xs font-semibold {{ $proyek->kas_tersedia < 0 ? 'text-red-500' : 'text-slate-400' }}">
                                    {{ $proyek->kas_tersedia < 0 ? '-' : '' }}Rp
                                </span>
                                <span class="text-xl font-bold {{ $proyek->kas_tersedia < 0 ? 'text-red-600' : 'text-slate-800' }} tabular-nums tracking-tight">
                                    {{ number_format(abs($proyek->kas_tersedia), 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                        <div class="text-right pb-0.5">
                            <span class="text-xs font-bold {{ $proyek->health_color }} tabular-nums">{{ number_format($proyek->persentase_terpakai, 0) }}%</span>
                        </div>
                    </div>

                    <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                        <div class="h-1.5 rounded-full {{ $proyek->health_dot }} transition-all duration-500" style="width: {{ min($proyek->persentase_terpakai, 100) }}%;"></div>
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
        <a href="{{ route('proyek.index') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-slate-50 hover:bg-slate-100 text-slate-700 text-xs md:text-sm font-bold rounded-xl border border-slate-200 transition-all active:scale-[0.98]">
            <span>Lihat Semua Proyek</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
            </svg>
        </a>
    </div>
</div>

<!-- ============================================== -->
<!-- DAFTAR PEGAWAI TERBARU -->
<!-- ============================================== -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6 lg:p-8">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-slate-800 tracking-tight">Daftar Pegawai</h2>
            <p class="text-xs md:text-sm text-slate-500 mt-0.5">Staf operasional dan tukang yang terdaftar di sistem.</p>
        </div>
        <a href="{{ route('pegawai.index') }}" class="text-xs md:text-sm font-bold text-blue-600 hover:text-blue-700 hover:underline flex items-center gap-1">
            <span>Lihat Semua</span>
            <span>&rarr;</span>
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50/80">
                    <th class="py-3.5 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider rounded-l-xl">Nama Pegawai</th>
                    <th class="py-3.5 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Jabatan</th>
                    <th class="py-3.5 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider rounded-r-xl">Nomor Telepon</th>
                </tr>
            </thead>
            <tbody class="text-xs md:text-sm text-slate-700 divide-y divide-slate-100">
                @forelse($pegawais as $pegawai)
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="py-3.5 px-4 font-semibold text-slate-800">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-600 border border-slate-200 flex items-center justify-center font-bold text-xs shrink-0">
                                {{ strtoupper(substr($pegawai->nama ?? 'P', 0, 1)) }}
                            </div>
                            <span class="truncate">{{ $pegawai->nama }}</span>
                        </div>
                    </td>
                    <td class="py-3.5 px-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold {{ strtolower($pegawai->jabatan->nama_jabatan ?? '') == 'mandor' ? 'bg-blue-50 text-blue-700 border border-blue-100' : 'bg-slate-100 text-slate-600 border border-slate-200/60' }}">
                            {{ $pegawai->jabatan->nama_jabatan ?? 'Belum Diatur' }}
                        </span>
                    </td>
                    <td class="py-3.5 px-4 font-medium text-slate-600 tabular-nums">
                        {{ $pegawai->no_telp ?? '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="py-10 text-center text-slate-400 font-medium">Belum ada data pegawai yang terdaftar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection