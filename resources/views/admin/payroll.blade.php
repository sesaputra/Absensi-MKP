@extends('layouts.admin')

@section('title', 'Rekap Gaji Massal - ' . $proyek->nama_proyek)

@section('content')

<!-- ============================================== -->
<!-- HEADER & NAVIGASI (Desain Menyatu ala Halaman Lain) -->
<!-- ============================================== -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 md:p-8 mb-8">

    <div class="flex flex-wrap items-center justify-between mb-6 pb-6 border-b border-slate-100 gap-4">
        <a href="{{ route('proyek.keuangan', $proyek->id) }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-900 transition-colors group">
            <div class="w-8 h-8 rounded-full bg-slate-50 border border-slate-200 flex items-center justify-center mr-3 group-hover:bg-slate-100 transition-colors">
                <svg class="w-4 h-4 text-slate-500 group-hover:text-slate-800 transform group-hover:-translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </div>
            Kembali ke Keuangan
        </a>

        <span class="hidden sm:inline-flex px-3 py-1.5 rounded-lg bg-slate-50 border border-slate-100 text-slate-500 text-[10px] font-bold uppercase tracking-widest">
            Rekap Gaji Massal
        </span>
    </div>

    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight">Rekapitulasi Penggajian</h1>
            <p class="text-sm text-slate-500 mt-2 flex items-center font-medium">
                <svg class="w-4 h-4 mr-1.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                {{ $proyek->nama_proyek }}
            </p>
        </div>
    </div>
</div>

<!-- ============================================== -->
<!-- WIDGET FILTER TANGGAL -->
<!-- ============================================== -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6 flex flex-col md:flex-row items-center justify-between gap-6">
    <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
        </div>
        <div>
            <h3 class="text-sm font-bold text-slate-800">Periode Kehadiran</h3>
            <p class="text-xs text-slate-500 mt-0.5">Sistem hanya menarik absensi yang <span class="font-bold text-blue-600">Disetujui</span> & <span class="font-bold text-red-500">Belum Dibayar</span>.</p>
        </div>
    </div>

    <form action="{{ route('proyek.payroll', $proyek->id) }}" method="GET" class="w-full md:w-auto flex flex-col sm:flex-row items-end gap-3">
        <div>
            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Dari Tanggal</label>
            <input type="date" name="start_date" value="{{ $startDate }}" class="w-full sm:w-auto rounded-xl border-slate-300 py-2.5 px-3 text-[15px] text-slate-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none ring-1 ring-inset ring-slate-300 bg-slate-50" required>
        </div>
        <div>
            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Sampai Tanggal</label>
            <input type="date" name="end_date" value="{{ $endDate }}" class="w-full sm:w-auto rounded-xl border-slate-300 py-2.5 px-3 text-[15px] text-slate-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none ring-1 ring-inset ring-slate-300 bg-slate-50" required>
        </div>
        <button type="submit" class="w-full sm:w-auto px-5 py-2.5 bg-slate-800 text-white font-bold rounded-xl hover:bg-black transition-colors shadow-sm">
            Tampilkan
        </button>
    </form>
</div>

<!-- ============================================== -->
<!-- TABEL REKAP GAJI MASSAL -->
<!-- ============================================== -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    @if(count($rekapGaji) > 0)
    <!-- FORM EKSEKUSI PEMBAYARAN -->
    <form action="{{ route('proyek.payroll.store', $proyek->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Hidden Input Tanggal untuk dikirim ke keterangan Buku Kas -->
        <input type="hidden" name="periode_start" value="{{ $startDate }}">
        <input type="hidden" name="periode_end" value="{{ $endDate }}">

        <div class="overflow-x-auto rounded-t-2xl">
            <table class="w-full text-left border-collapse min-w-[900px]">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="p-4 text-[11px] font-bold text-slate-500 uppercase tracking-widest">Nama Pekerja</th>
                        <th class="p-4 text-[11px] font-bold text-slate-500 uppercase tracking-widest text-center">Kehadiran</th>
                        <th class="p-4 text-[11px] font-bold text-slate-500 uppercase tracking-widest text-right">Gaji Kotor</th>
                        <th class="p-4 text-[11px] font-bold text-slate-500 uppercase tracking-widest text-right">Potongan Kasbon</th>
                        <th class="p-4 text-[11px] font-bold text-blue-600 uppercase tracking-widest text-right bg-blue-50/50">Hak Sistem (Bersih)</th>
                        <th class="p-4 text-[11px] font-bold text-slate-900 uppercase tracking-widest w-48">Uang di Amplop (Rp)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">

                    @php $totalSemuaGaji = 0; @endphp

                    @foreach($rekapGaji as $pegawaiId => $data)
                    <tr class="hover:bg-slate-50/50 transition-colors">

                        <!-- Hidden Data per Pegawai -->
                        <input type="hidden" name="pembayaran[{{ $pegawaiId }}][estimasi_sistem]" value="{{ $data['upah_bersih'] }}">
                        <input type="hidden" name="pembayaran[{{ $pegawaiId }}][id_absensi]" value="{{ implode(',', $data['id_absensi']) }}">

                        <!-- 1. Info Pegawai -->
                        <td class="p-4">
                            <p class="text-[15px] font-bold text-slate-900">{{ $data['pegawai']->nama }}</p>
                            <p class="text-xs font-medium text-slate-500">{{ $data['pegawai']->jabatan->nama_jabatan ?? '-' }} &bull; Rp {{ number_format($data['pegawai']->jabatan->gaji_harian ?? 0, 0, ',', '.') }}/hari</p>
                        </td>

                        <!-- 2. Kehadiran -->
                        <td class="p-4 text-center">
                            <span class="inline-flex items-center justify-center px-2 py-1 rounded bg-green-50 text-green-700 text-xs font-bold border border-green-100 mr-1" title="Hadir Full">
                                {{ $data['hari_full'] }} F
                            </span>
                            @if($data['hari_setengah'] > 0)
                            <span class="inline-flex items-center justify-center px-2 py-1 rounded bg-amber-50 text-amber-700 text-xs font-bold border border-amber-100" title="Setengah Hari">
                                {{ $data['hari_setengah'] }} H
                            </span>
                            @endif
                        </td>

                        <!-- 3. Gaji Kotor -->
                        <td class="p-4 text-right text-[15px] font-semibold text-slate-700">
                            Rp {{ number_format($data['upah_kotor'], 0, ',', '.') }}
                        </td>

                        <!-- 4. Potongan Kasbon -->
                        <td class="p-4 text-right text-[15px] font-bold text-red-500">
                            - Rp {{ number_format($data['potongan_kasbon'], 0, ',', '.') }}
                        </td>

                        <!-- 5. Hak Bersih Sistem -->
                        <td class="p-4 text-right bg-blue-50/30">
                            <span class="text-base font-black text-blue-600">
                                Rp {{ number_format($data['upah_bersih'], 0, ',', '.') }}
                            </span>
                        </td>

                        <!-- 6. Input Nominal (Dapat diedit oleh Admin) -->
                        <td class="p-4">
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 font-bold text-slate-400 text-sm">Rp</span>
                                <input type="number" name="pembayaran[{{ $pegawaiId }}][nominal]" value="{{ max(0, $data['upah_bersih']) }}" min="0" class="w-full pl-9 rounded-xl border-slate-300 py-2.5 pr-3 text-base font-bold text-slate-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none ring-1 ring-inset ring-slate-300 bg-white shadow-sm" required>
                            </div>
                        </td>

                    </tr>
                    @php $totalSemuaGaji += max(0, $data['upah_bersih']); @endphp
                    @endforeach

                </tbody>
            </table>
        </div>

        <!-- AREA KONFIRMASI (FOOTER) -->
        <div class="bg-slate-50 p-6 border-t border-slate-200 flex flex-col items-end gap-4">

            <div class="w-full flex flex-col sm:flex-row justify-between items-center gap-6 mb-2">
                <div class="w-full sm:w-1/2">
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">
                        Bukti Pembayaran / Serah Terima <span class="text-red-500">*</span>
                    </label>
                    <input type="file" name="bukti_file" accept="image/*,.pdf" class="w-full text-[15px] text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer bg-white border border-slate-200 rounded-xl p-1" required>
                    @error('bukti_file')
                    <p class="text-xs text-red-500 mt-1.5 font-bold">{{ $message }}</p>
                    @enderror
                    <p class="text-[10px] text-slate-400 mt-1.5">*Wajib dilampirkan! Bisa berupa foto lembar tanda tangan tukang, kuitansi, atau foto penyerahan amplop.</p>
                </div>

                <div class="text-right">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Total dana kas yang akan dikeluarkan</p>
                    <p class="text-3xl font-black text-slate-900 tracking-tight">Rp {{ number_format($totalSemuaGaji, 0, ',', '.') }}</p>
                </div>
            </div>

            <button type="submit" class="w-full sm:w-auto px-8 py-3.5 bg-blue-600 text-white font-black rounded-xl hover:bg-blue-700 transition-colors shadow-md flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Konfirmasi & Potong Saldo Kas
            </button>
        </div>
    </form>

    @else
    <!-- Tampilan Jika Tidak Ada Pekerja yang Harus Digaji -->
    <div class="text-center py-20 px-6">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 mb-4 border border-slate-100">
            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
            </svg>
        </div>
        <h3 class="text-lg font-bold text-slate-900 mb-1">Semua Tagihan Lunas!</h3>
        <p class="text-sm text-slate-500 max-w-md mx-auto">Tidak ada catatan kehadiran yang berstatus <span class="font-bold">Belum Dibayar</span> pada rentang tanggal tersebut, atau mandor belum melakukan absensi.</p>
    </div>
    @endif
</div>

@endsection