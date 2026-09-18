@extends('layouts.admin')

@section('title', 'Manajemen Dana Proyek - ' . $proyek->nama_proyek)

@section('content')

<!-- ============================================== -->
<!-- HEADER KEUANGAN (Desain Menyatu ala Detail Proyek) -->
<!-- ============================================== -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 md:p-8 mb-8">

    <!-- Baris Atas: Tombol Kembali (Kiri) & Tombol Aksi (Kanan) -->
    <div class="flex flex-wrap items-center justify-between mb-6 pb-6 border-b border-slate-100 gap-4">

        <!-- Tombol Kembali Terintegrasi -->
        <a href="{{ route('proyek.show', $proyek->id) }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-900 transition-colors group">
            <div class="w-8 h-8 rounded-full bg-slate-50 border border-slate-200 flex items-center justify-center mr-3 group-hover:bg-slate-100 transition-colors">
                <svg class="w-4 h-4 text-slate-500 group-hover:text-slate-800 transform group-hover:-translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </div>
            Kembali ke Detail Proyek
        </a>

        <!-- Tombol Aksi Input - Hanya untuk Super Admin, dikelompokkan per kategori -->
        @if(auth()->check() && auth()->user()->role === 'super_admin')
        <div class="flex flex-wrap items-center gap-2.5">
            <!-- Grup 1: Transaksi Dana Proyek -->
            <div class="flex items-center gap-2.5 pr-2.5 sm:border-r border-slate-200">
                <button type="button" onclick="toggleModal('modalCatatPemasukan')" class="inline-flex items-center px-3.5 py-1.5 bg-white border border-slate-200 text-green-600 hover:bg-green-50 hover:border-green-200 text-sm font-bold rounded-xl transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Termin Klien
                </button>

                <button type="button" onclick="toggleModal('modalCatatPengeluaran')" class="inline-flex items-center px-3.5 py-1.5 bg-slate-900 border border-slate-900 text-white hover:bg-slate-800 text-sm font-bold rounded-xl transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                    </svg>
                    Biaya Lapangan
                </button>
            </div>

            <!-- Grup 2: Upah & Kasbon Tukang -->
            <div class="flex items-center gap-2.5">
                <button type="button" onclick="toggleModal('modalCatatKasbon')" class="inline-flex items-center px-3.5 py-1.5 bg-amber-500 border border-amber-500 text-white hover:bg-amber-600 text-sm font-bold rounded-xl transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    Catat Kasbon
                </button>

                <button type="button" onclick="toggleModal('modalGaji')" class="inline-flex items-center px-3.5 py-1.5 bg-blue-600 border border-blue-600 text-white hover:bg-blue-700 text-sm font-bold rounded-xl transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                    </svg>
                    Bayar Upah
                </button>

                <a href="{{ route('proyek.payroll', $proyek->id) }}" class="inline-flex items-center px-3.5 py-1.5 bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:border-slate-300 text-sm font-bold rounded-xl transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Rincian Upah
                </a>
            </div>
        </div>
        @endif
    </div>

    <!-- Baris Bawah: Info Detail & Metrik Keuangan -->
    <div class="flex flex-col md:flex-row gap-6 md:gap-8 md:items-center">

        <!-- Gambar Placeholder Keuangan -->
        <div class="w-full md:w-48 h-36 bg-green-50/50 rounded-2xl flex-shrink-0 flex items-center justify-center border border-green-100 shadow-inner">
            <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
        </div>

        <!-- Info Detail (Judul & Grid Metrik) -->
        <div class="flex-1">

            <!-- Judul Halaman -->
            <div class="mb-4">
                <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                    <h1 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight">Manajemen Dana</h1>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border border-slate-200 bg-slate-100 text-slate-600 w-max">
                        {{ $proyek->nama_proyek }}
                    </span>
                </div>

                <p class="text-sm text-slate-500 mt-2 flex items-center font-medium">
                    <svg class="w-4 h-4 mr-1.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    {{ $proyek->lokasi }}
                </p>
            </div>

            <!-- Ringkasan 3 Metrik: Pemasukan, Pengeluaran, Kas Tersedia -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 border-t border-slate-100 pt-4">
                <div>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">Total Termin Masuk</p>
                    <p class="text-base font-black text-emerald-600">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">Total Pengeluaran</p>
                    <p class="text-base font-black text-slate-700">Rp {{ number_format($totalPengeluaran ?? ($totalPemasukan - $kasTersedia), 0, ',', '.') }}</p>
                </div>
                <div>
                    <div class="flex justify-between items-end mb-1">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider" title="Total Termin Cair dikurangi Pengeluaran">Dana Kas Tersedia</p>
                        <span class="text-[10px] font-bold {{ $statusColor }}">{{ $statusText }}</span>
                    </div>
                    <p class="text-base font-black {{ $kasTersedia < 0 ? 'text-red-600' : $statusColor }} leading-tight">
                        {{ $kasTersedia < 0 ? '-' : '' }}Rp {{ number_format(abs($kasTersedia), 0, ',', '.') }}
                    </p>
                    <div class="w-full bg-slate-100 rounded-full h-1 mt-2 relative overflow-hidden">
                        <div class="{{ $progressColor }} h-1 rounded-full transition-all duration-500" style="width: {{ $persentaseKasTerpakai > 100 ? 100 : $persentaseKasTerpakai }}%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============================================== -->
<!-- TABEL TRANSAKSI -->
<!-- ============================================== -->
<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 lg:p-8 mb-6">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h2 class="text-lg font-bold text-slate-800 border-b-2 border-green-500 inline-block pb-1">Buku Kas Proyek</h2>
            <p class="text-sm text-slate-500 mt-2">Daftar riwayat transaksi pemasukan termin klien dan pengeluaran biaya material proyek.</p>
        </div>
        <a href="{{ route('keuangan.pdf', $proyek->id) }}" target="_blank" class="bg-[#0c2340] text-white px-4 py-2 rounded-xl text-xs font-bold flex items-center shadow-md hover:bg-opacity-90 transition-colors shrink-0">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Unduh PDF Laporan
        </a>
    </div>

    @if($transaksi->count() > 0)
    <!-- Filter & Search Buku Kas -->
    <div class="flex flex-col sm:flex-row gap-3 mb-5">
        <div class="relative flex-1">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input type="text" id="cariTransaksi" onkeyup="filterBukuKas()" placeholder="Cari keterangan transaksi..." class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all">
        </div>
        <div class="w-full sm:w-48">
            <select id="filterTipeTransaksi" onchange="filterBukuKas()" class="w-full py-2 px-3 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all">
                <option value="">Semua Tipe</option>
                <option value="pemasukan">Pemasukan</option>
                <option value="pengeluaran">Pengeluaran</option>
            </select>
        </div>
    </div>

    <div class="overflow-x-auto rounded-lg border border-slate-100">
        <table class="w-full text-left border-collapse min-w-[600px]" id="tabelBukuKas">
            <thead>
                <tr class="border-b border-slate-200">
                    <th class="py-3 px-3 text-sm font-semibold text-slate-600 bg-slate-50">Tanggal</th>
                    <th class="py-3 px-3 text-sm font-semibold text-slate-600 bg-slate-50">Tipe Transaksi</th>
                    <th class="py-3 px-3 text-sm font-semibold text-slate-600 bg-slate-50">Keterangan</th>
                    <th class="py-3 px-3 text-sm font-semibold text-slate-600 bg-slate-50 text-right">Nominal</th>
                    <th class="py-3 px-3 text-sm font-semibold text-slate-600 bg-slate-50 text-center">Bukti</th>
                </tr>
            </thead>
            <tbody class="text-sm text-slate-700">
                @foreach($transaksi as $item)
                <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors row-transaksi" data-tipe="{{ strtolower($item->tipe) }}">
                    <td class="py-3 px-3 font-medium text-slate-800 whitespace-nowrap">
                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}
                    </td>
                    <td class="py-3 px-3">
                        <div class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full {{ $item->tipe == 'Pemasukan' ? 'bg-green-500' : 'bg-slate-400' }}"></span>
                            <span class="font-medium text-slate-700">
                                @if($item->tipe == 'Pemasukan')
                                Termin Klien
                                @elseif($item->kategori == 'Kasbon Tukang')
                                Pencairan Kasbon
                                @elseif($item->kategori == 'Upah Tukang')
                                Pembayaran Gaji
                                @else
                                Biaya Lapangan
                                @endif
                            </span>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-0.5 ml-3.5">{{ $item->kategori }}</p>
                    </td>
                    <td class="py-3 px-3 min-w-[200px] cell-keterangan">
                        {{ $item->keterangan }}
                    </td>
                    <td class="py-3 px-3 text-right whitespace-nowrap">
                        <span class="font-bold {{ $item->tipe == 'Pemasukan' ? 'text-green-600' : 'text-slate-900' }}">
                            {{ $item->tipe == 'Pengeluaran' ? '-' : '+' }} Rp {{ number_format($item->nominal, 0, ',', '.') }}
                        </span>
                    </td>
                    <td class="py-3 px-3 text-center">
                        @if($item->bukti_file)
                        <a href="{{ asset('storage/' . $item->bukti_file) }}" target="_blank" class="p-1.5 inline-flex text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded transition-colors" title="Lihat Bukti">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                            </svg>
                        </a>
                        @else
                        <span class="text-slate-300">-</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <p id="pesanTransaksiKosong" class="hidden text-center text-sm text-slate-400 py-8">Tidak ada transaksi yang cocok dengan pencarian.</p>
    @else
    <!-- Empty State -->
    <div class="text-center py-12 px-6">
        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-50 mb-3">
            <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12V8H6a2 2 0 01-2-2c0-1.1.9-2 2-2h12v4"></path>
            </svg>
        </div>
        <p class="text-slate-500 font-medium">Buku Kas Kosong</p>
        <p class="text-xs text-slate-400 mt-1">Mulai catat arus kas proyek dengan menekan tombol di kanan atas.</p>
    </div>
    @endif
</div>

<!-- ============================================== -->
<!-- MODAL: CATAT KASBON -->
<!-- ============================================== -->
<div id="modalCatatKasbon" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm overflow-y-auto w-full h-full flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 my-8">
        <h3 class="text-xl font-bold text-slate-900 mb-4">Catat Kasbon Pekerja</h3>
        <form action="{{ route('kasbon.store', $proyek->id) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Pilih Pekerja</label>
                <select name="pegawai_id" class="w-full rounded-xl border-slate-300 py-2.5 px-3 text-[15px] text-slate-900 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none ring-1 ring-inset ring-slate-300 bg-white" required>
                    @foreach($proyek->pegawais as $p)
                    <option value="{{ $p->id }}">{{ $p->nama }} ({{ $p->jabatan->nama_jabatan ?? 'Pekerja' }})</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Nominal (Rp)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 font-bold text-slate-400 text-sm">Rp</span>
                        <input type="number" name="nominal" class="w-full pl-9 rounded-xl border-slate-300 py-2.5 pr-3 text-base font-bold text-slate-900 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none ring-1 ring-inset ring-slate-300" placeholder="50.000" required>
                    </div>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="w-full rounded-xl border-slate-300 py-2.5 px-3 text-[15px] text-slate-900 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none ring-1 ring-inset ring-slate-300" required>
                </div>
            </div>
            <div class="mb-6">
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Keterangan</label>
                <input type="text" name="keterangan" class="w-full rounded-xl border-slate-300 py-2.5 px-3 text-[15px] text-slate-900 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none ring-1 ring-inset ring-slate-300" placeholder="Misal: Beli kebutuhan dapur">
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="toggleModal('modalCatatKasbon')" class="flex-1 py-2.5 bg-slate-100 text-slate-700 font-bold rounded-xl hover:bg-slate-200 transition-colors">Batal</button>
                <button type="submit" class="flex-1 py-2.5 bg-amber-500 text-white font-bold rounded-xl hover:bg-amber-600 transition-colors shadow-sm">Simpan Kasbon</button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================== -->
<!-- MODAL: CATAT PEMASUKAN (TERMIN KLIEN) -->
<!-- ============================================== -->
<div id="modalCatatPemasukan" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm overflow-y-auto w-full h-full flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 my-8">
        <h3 class="text-xl font-bold text-slate-900 mb-4">Catat Termin Klien (Pemasukan)</h3>
        <!-- Perhatikan: enctype="multipart/form-data" wajib ada untuk upload file bukti -->
        <form action="{{ route('proyek.keuangan.store', $proyek->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="tipe" value="Pemasukan">
            <input type="hidden" name="kategori" value="Termin Pembayaran">

            <div class="mb-4">
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Nominal Termin (Rp)</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 font-bold text-slate-400 text-sm">Rp</span>
                    <input type="number" name="nominal" class="w-full pl-9 rounded-xl border-slate-300 py-2.5 pr-3 text-base font-bold text-slate-900 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none ring-1 ring-inset ring-slate-300" placeholder="50.000.000" required>
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Tanggal Pembayaran</label>
                <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="w-full rounded-xl border-slate-300 py-2.5 px-3 text-[15px] text-slate-900 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none ring-1 ring-inset ring-slate-300" required>
            </div>
            <div class="mb-4">
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Keterangan / Catatan</label>
                <input type="text" name="keterangan" class="w-full rounded-xl border-slate-300 py-2.5 px-3 text-[15px] text-slate-900 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none ring-1 ring-inset ring-slate-300" placeholder="Misal: Termin 1 (30%) dari Bpk. Budi" required>
            </div>
            <div class="mb-6">
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Bukti Transfer (Opsional)</label>
                <input type="file" name="bukti_file" accept="image/*,.pdf" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 cursor-pointer">
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="toggleModal('modalCatatPemasukan')" class="flex-1 py-2.5 bg-slate-100 text-slate-700 font-bold rounded-xl hover:bg-slate-200 transition-colors">Batal</button>
                <button type="submit" class="flex-1 py-2.5 bg-green-600 text-white font-bold rounded-xl hover:bg-green-700 transition-colors shadow-sm">Simpan Pemasukan</button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================== -->
<!-- MODAL: CATAT PENGELUARAN (BIAYA LAPANGAN) -->
<!-- ============================================== -->
<div id="modalCatatPengeluaran" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm overflow-y-auto w-full h-full flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 my-8">
        <h3 class="text-xl font-bold text-slate-900 mb-4">Catat Biaya Lapangan</h3>
        <form action="{{ route('proyek.keuangan.store', $proyek->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="tipe" value="Pengeluaran">

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Kategori Biaya</label>
                    <select name="kategori" class="w-full rounded-xl border-slate-300 py-2.5 px-3 text-[15px] text-slate-900 focus:ring-2 focus:ring-slate-500 focus:border-slate-500 outline-none ring-1 ring-inset ring-slate-300 bg-white" required>
                        <option value="Material & Bahan">Material & Bahan</option>
                        <option value="Sewa Alat">Sewa Alat</option>
                        <option value="Operasional Lapangan">Operasional (BBM, Makan)</option>
                        <option value="Lain-lain">Lain-lain</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="w-full rounded-xl border-slate-300 py-2.5 px-3 text-[15px] text-slate-900 focus:ring-2 focus:ring-slate-500 focus:border-slate-500 outline-none ring-1 ring-inset ring-slate-300" required>
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Nominal (Rp)</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 font-bold text-slate-400 text-sm">Rp</span>
                    <input type="number" name="nominal" class="w-full pl-9 rounded-xl border-slate-300 py-2.5 pr-3 text-base font-bold text-slate-900 focus:ring-2 focus:ring-slate-500 focus:border-slate-500 outline-none ring-1 ring-inset ring-slate-300" placeholder="1.500.000" required>
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Keterangan / Beli Apa?</label>
                <input type="text" name="keterangan" class="w-full rounded-xl border-slate-300 py-2.5 px-3 text-[15px] text-slate-900 focus:ring-2 focus:ring-slate-500 focus:border-slate-500 outline-none ring-1 ring-inset ring-slate-300" placeholder="Misal: Beli Semen 50 Sak di UD Ema Kencana" required>
            </div>
            <div class="mb-6">
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Foto Nota/Kwitansi (Opsional)</label>
                <input type="file" name="bukti_file" accept="image/*,.pdf" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 cursor-pointer">
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="toggleModal('modalCatatPengeluaran')" class="flex-1 py-2.5 bg-slate-100 text-slate-700 font-bold rounded-xl hover:bg-slate-200 transition-colors">Batal</button>
                <button type="submit" class="flex-1 py-2.5 bg-slate-900 text-white font-bold rounded-xl hover:bg-black transition-colors shadow-sm">Simpan Pengeluaran</button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================== -->
<!-- MODAL CERDAS: BAYAR UPAH TUKANG -->
<!-- ============================================== -->
<div id="modalGaji" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm overflow-y-auto w-full h-full flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden flex flex-col max-h-[90vh] my-8">

        <!-- Header Modal -->
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center shrink-0">
            <h3 class="text-xl font-bold text-slate-900">Hitung & Bayar Upah</h3>
            <button type="button" onclick="toggleModal('modalGaji')" class="text-slate-400 hover:text-red-500 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="p-6 overflow-y-auto">
            <!-- TAHAP 1: FORM FILTER -->
            <form id="formCekGaji" class="mb-6">
                @csrf
                <div class="mb-4">
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Pilih Pekerja</label>
                    <select id="inputPegawaiGaji" name="pegawai_id" class="w-full rounded-xl border-slate-300 py-2.5 px-3 text-[15px] text-slate-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none ring-1 ring-inset ring-slate-300 bg-slate-50" required>
                        @foreach($proyek->pegawais as $p)
                        <option value="{{ $p->id }}">{{ $p->nama }} ({{ $p->jabatan->nama_jabatan ?? '-' }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Dari Tanggal</label>
                        <input type="date" id="inputStartGaji" name="start_date" class="w-full rounded-xl border-slate-300 py-2.5 px-3 text-[15px] text-slate-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none ring-1 ring-inset ring-slate-300 bg-slate-50" required>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Sampai Tanggal</label>
                        <input type="date" id="inputEndGaji" name="end_date" class="w-full rounded-xl border-slate-300 py-2.5 px-3 text-[15px] text-slate-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none ring-1 ring-inset ring-slate-300 bg-slate-50" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                <button type="submit" id="btnCekGaji" class="w-full py-2.5 bg-slate-800 text-white font-bold rounded-xl hover:bg-black transition-colors shadow-sm flex justify-center items-center">
                    Tarik Data Kehadiran & Kasbon
                </button>
            </form>

            <!-- TAHAP 2: HASIL PERHITUNGAN (Disembunyikan awalnya) -->
            <div id="areaHasilGaji" class="hidden border-t border-slate-200 pt-6 mt-2">
                <h4 class="text-base font-bold text-slate-800 mb-3 border-l-4 border-blue-500 pl-2">Rincian Slip Gaji: <span id="labelNamaPegawai" class="text-blue-600"></span></h4>

                <div class="bg-slate-50 rounded-xl p-4 mb-4 border border-slate-100 text-[15px]">
                    <div class="flex justify-between mb-2">
                        <span class="text-slate-600">Hadir Penuh (Full)</span>
                        <span class="font-bold text-slate-800"><span id="txtHariFull">0</span> Hari</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="text-slate-600">Setengah Hari</span>
                        <span class="font-bold text-slate-800"><span id="txtHariSetengah">0</span> Hari</span>
                    </div>
                    <div class="flex justify-between mb-2 pt-2 border-t border-slate-200">
                        <span class="font-bold text-slate-800">Total Upah Kotor</span>
                        <span class="font-black text-slate-900 text-base" id="txtUpahKotor">Rp 0</span>
                    </div>
                    <div class="flex justify-between mb-1 mt-2 text-red-500">
                        <span>Potongan Kasbon Lama</span>
                        <span class="font-bold" id="txtPotonganKasbon">- Rp 0</span>
                    </div>
                </div>

                <!-- FORM FINAL PEMBAYARAN -->
                <form action="{{ route('proyek.keuangan.bayar-gaji', $proyek->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="pegawai_id" id="finalPegawaiId">
                    <input type="hidden" name="start_date" id="finalStartDate">
                    <input type="hidden" name="end_date" id="finalEndDate">
                    <input type="hidden" name="estimasi_gaji_sistem" id="finalEstimasiSistem">
                    <input type="hidden" name="id_absensi" id="finalIdAbsensi">

                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-5">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-[11px] font-bold text-blue-800 uppercase tracking-wider">Hak Gaji Bersih (Sistem)</span>
                            <span class="text-2xl font-black text-blue-700" id="txtGajiBersih">Rp 0</span>
                        </div>

                        <div class="mt-4">
                            <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nominal Uang di Amplop (Yang Sebenarnya Dibayar)</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 font-bold text-slate-400">Rp</span>
                                <input type="number" id="inputNominalDibayar" name="nominal_dibayar" class="w-full pl-9 border-slate-300 rounded-xl py-2.5 font-bold text-lg text-slate-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            </div>
                            <p class="text-[10px] text-slate-500 mt-1.5">*Ubah angka ini jika ada kebijakan lebih bayar/kurang bayar. Sistem akan mengkonversi selisihnya menjadi Kasbon baru otomatis.</p>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3 bg-blue-600 text-white font-black text-lg rounded-xl hover:bg-blue-700 transition-colors shadow-md">
                        Konfirmasi & Potong Saldo Kas
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- SCRIPT PENDUKUNG -->
<script>
    // 1. Fungsi untuk membuka/menutup modal dengan aman
    function toggleModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.toggle('hidden');
        } else {
            console.error("Modal dengan ID " + modalId + " tidak ditemukan!");
        }
    }

    // 2. FILTER & SEARCH BUKU KAS
    function filterBukuKas() {
        const keyword = document.getElementById('cariTransaksi').value.toLowerCase();
        const tipeFilter = document.getElementById('filterTipeTransaksi').value.toLowerCase();
        const rows = document.querySelectorAll('#tabelBukuKas .row-transaksi');
        let adaHasil = false;

        rows.forEach(row => {
            const keterangan = row.querySelector('.cell-keterangan').textContent.toLowerCase();
            const tipe = row.getAttribute('data-tipe');

            const cocokKeyword = keterangan.includes(keyword);
            const cocokTipe = tipeFilter === '' || tipe === tipeFilter;

            const tampil = cocokKeyword && cocokTipe;
            row.style.display = tampil ? '' : 'none';
            if (tampil) adaHasil = true;
        });

        const pesanKosong = document.getElementById('pesanTransaksiKosong');
        if (pesanKosong) {
            pesanKosong.classList.toggle('hidden', adaHasil);
        }
    }

    // 3. FUNGSI AJAX UNTUK HITUNG GAJI (MENCEGAH PINDAH HALAMAN KE JSON)
    const formCekGaji = document.getElementById('formCekGaji');
    if (formCekGaji) {
        formCekGaji.addEventListener('submit', async function(e) {
            // Ini adalah mantra ajaib untuk mencegah halaman pindah/reload!
            e.preventDefault();

            // Ambil elemen tombol dan area hasil
            const btnCek = document.getElementById('btnCekGaji');
            const areaHasil = document.getElementById('areaHasilGaji');

            // Ubah teks tombol jadi loading
            const textLama = btnCek.innerHTML;
            btnCek.innerHTML = 'Menghitung...';
            btnCek.disabled = true;

            // Siapkan data yang akan dikirim ke Controller
            const formData = new FormData(this);
            const proyekId = "{{ $proyek->id }}";

            try {
                // Tembak data ke Controller secara diam-diam (Background process)
                const response = await fetch(`/admin/proyek/${proyekId}/keuangan/preview-gaji`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                // Tangkap balasan JSON dari Controller
                const result = await response.json();

                if (result.status === 'success') {
                    const data = result.data;

                    const formatRp = (angka) => new Intl.NumberFormat('id-ID').format(angka);

                    document.getElementById('labelNamaPegawai').innerText = data.nama_pegawai;
                    document.getElementById('txtHariFull').innerText = data.hari_full;
                    document.getElementById('txtHariSetengah').innerText = data.hari_setengah;
                    document.getElementById('txtUpahKotor').innerText = 'Rp ' + formatRp(data.total_upah_kotor);
                    document.getElementById('txtPotonganKasbon').innerText = '- Rp ' + formatRp(data.total_potongan_kasbon);
                    document.getElementById('txtGajiBersih').innerText = 'Rp ' + formatRp(data.estimasi_gaji_bersih);

                    document.getElementById('inputNominalDibayar').value = Math.max(0, data.estimasi_gaji_bersih);

                    document.getElementById('finalPegawaiId').value = formData.get('pegawai_id');
                    document.getElementById('finalStartDate').value = formData.get('start_date');
                    document.getElementById('finalEndDate').value = formData.get('end_date');
                    document.getElementById('finalEstimasiSistem').value = data.estimasi_gaji_bersih;
                    document.getElementById('finalIdAbsensi').value = data.id_absensi; // <-- TAMBAHKAN BARIS INI

                    areaHasil.classList.remove('hidden');
                }
            } catch (error) {
                alert('Gagal mengambil data. Pastikan koneksi aman.');
                console.error(error);
            } finally {
                // Kembalikan tombol ke keadaan semula
                btnCek.innerHTML = textLama;
                btnCek.disabled = false;
            }
        });
    }
</script>

@endsection