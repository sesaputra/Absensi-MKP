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

        <!-- Tombol Aksi Input - Hanya untuk Admin -->
        @if(auth()->check() && auth()->user()->role === 'admin')
        <div class="flex items-center gap-2.5">
            <!-- Tombol Pemasukan -->
            <button onclick="toggleModal('modalCatatPemasukan')" class="inline-flex items-center px-3.5 py-1.5 bg-white border border-slate-200 text-green-600 hover:bg-green-50 hover:border-green-200 text-sm font-bold rounded-xl transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Termin Klien
            </button>

            <!-- Tombol Pengeluaran -->
            <button onclick="toggleModal('modalCatatPengeluaran')" class="inline-flex items-center px-3.5 py-1.5 bg-slate-900 border border-slate-900 text-white hover:bg-slate-800 text-sm font-bold rounded-xl transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                </svg>
                Biaya Lapangan
            </button>

            <!-- Tombol Catat Kasbon (Disatukan di sini agar sejajar & rapi) -->
            <button onclick="toggleModal('modalCatatKasbon')" class="inline-flex items-center px-3.5 py-1.5 bg-amber-500 border border-amber-500 text-white hover:bg-amber-600 text-sm font-bold rounded-xl transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                Catat Kasbon
            </button>

            <!-- Tombol Bayar Gaji -->
            <button onclick="toggleModal('modalGaji')" class="inline-flex items-center px-3.5 py-1.5 bg-blue-600 border border-blue-600 text-white hover:bg-blue-700 text-sm font-bold rounded-xl transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                </svg>
                Bayar Upah
            </button>

            <!-- Tombol Bayar Gaji (Diubah jadi Link/Href) -->
            <a href="{{ route('proyek.payroll', $proyek->id) }}" class="inline-flex items-center px-3.5 py-1.5 bg-blue-600 border border-blue-600 text-white hover:bg-blue-700 text-sm font-bold rounded-xl transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Rincian Upah
            </a>
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
            <div class="mb-3">
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

            <!-- Kartu 3: Kas Tersedia (Liquid Cash) -->
            <div>
                <div class="flex justify-between items-end mb-1">
                    <p class="text-[11px] text-slate-400 font-bold uppercase tracking-wider" title="Total Termin Cair dikurangi Pengeluaran">Dana Kas Tersedia</p>

                    <!-- Status Cerdas Langsung dari Controller -->
                    <span class="text-[10px] font-bold {{ $statusColor }}">
                        {{ $statusText }}
                    </span>
                </div>

                <p class="text-lg font-black {{ $kasTersedia < 0 ? 'text-red-600' : $statusColor }} leading-tight">
                    {{ $kasTersedia < 0 ? '-' : '' }}Rp {{ number_format(abs($kasTersedia), 0, ',', '.') }}
                </p>

                <!-- Progress Bar Uang Kas -->
                <div class="w-full bg-slate-100 rounded-full h-1 mt-2 relative overflow-hidden">
                    <div class="{{ $progressColor }} h-1 rounded-full transition-all duration-500" style="width: {{ $persentaseKasTerpakai > 100 ? 100 : $persentaseKasTerpakai }}%;"></div>
                </div>

                <p class="text-[9px] text-slate-400 mt-1 font-medium text-right">
                    Dari total Rp {{ number_format($totalPemasukan, 0, ',', '.') }} termin cair.
                </p>
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
    </div>

    @if($transaksi->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[600px]">
            <thead>
                <tr class="border-b border-slate-200">
                    <th class="py-3 px-3 text-sm font-semibold text-slate-600 bg-slate-50 rounded-tl-lg">Tanggal</th>
                    <th class="py-3 px-3 text-sm font-semibold text-slate-600 bg-slate-50">Tipe Transaksi</th>
                    <th class="py-3 px-3 text-sm font-semibold text-slate-600 bg-slate-50">Keterangan</th>
                    <th class="py-3 px-3 text-sm font-semibold text-slate-600 bg-slate-50 text-right">Nominal</th>
                    <th class="py-3 px-3 text-sm font-semibold text-slate-600 bg-slate-50 text-center rounded-tr-lg">Bukti</th>
                </tr>
            </thead>
            <tbody class="text-sm text-slate-700">
                @foreach($transaksi as $item)
                <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
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
                    <td class="py-3 px-3 min-w-[200px]">
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
<div id="modalCatatKasbon" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
        <h3 class="text-lg font-bold text-slate-900 mb-4">Catat Kasbon Pekerja</h3>
        <form action="{{ route('kasbon.store', $proyek->id) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Pilih Pekerja</label>
                <select name="pegawai_id" class="w-full border-slate-200 rounded-xl" required>
                    @foreach($proyek->pegawais as $p)
                    <option value="{{ $p->id }}">{{ $p->nama }} ({{ $p->jabatan->nama_jabatan ?? 'Pekerja' }})</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nominal (Rp)</label>
                    <input type="number" name="nominal" class="w-full border-slate-200 rounded-xl" placeholder="50000" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="w-full border-slate-200 rounded-xl" required>
                </div>
            </div>
            <div class="mb-6">
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Keterangan</label>
                <input type="text" name="keterangan" class="w-full border-slate-200 rounded-xl" placeholder="Misal: Beli kebutuhan dapur">
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
<div id="modalCatatPemasukan" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
        <h3 class="text-lg font-bold text-slate-900 mb-4">Catat Termin Klien (Pemasukan)</h3>
        <!-- Perhatikan: enctype="multipart/form-data" wajib ada untuk upload file bukti -->
        <form action="{{ route('proyek.keuangan.store', $proyek->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="tipe" value="Pemasukan">
            <input type="hidden" name="kategori" value="Termin Pembayaran">

            <div class="mb-4">
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nominal Termin (Rp)</label>
                <input type="number" name="nominal" class="w-full border-slate-200 rounded-xl" placeholder="Contoh: 50000000" required>
            </div>
            <div class="mb-4">
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tanggal Pembayaran</label>
                <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="w-full border-slate-200 rounded-xl" required>
            </div>
            <div class="mb-4">
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Keterangan / Catatan</label>
                <input type="text" name="keterangan" class="w-full border-slate-200 rounded-xl" placeholder="Misal: Termin 1 (30%) dari Bpk. Budi" required>
            </div>
            <div class="mb-6">
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Bukti Transfer (Opsional)</label>
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
<div id="modalCatatPengeluaran" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
        <h3 class="text-lg font-bold text-slate-900 mb-4">Catat Biaya Lapangan</h3>
        <form action="{{ route('proyek.keuangan.store', $proyek->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="tipe" value="Pengeluaran">

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Kategori Biaya</label>
                    <select name="kategori" class="w-full border-slate-200 rounded-xl" required>
                        <option value="Material & Bahan">Material & Bahan</option>
                        <option value="Sewa Alat">Sewa Alat</option>
                        <option value="Operasional Lapangan">Operasional (BBM, Makan)</option>
                        <option value="Lain-lain">Lain-lain</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="w-full border-slate-200 rounded-xl" required>
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nominal (Rp)</label>
                <input type="number" name="nominal" class="w-full border-slate-200 rounded-xl" placeholder="Contoh: 1500000" required>
            </div>
            <div class="mb-4">
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Keterangan / Beli Apa?</label>
                <input type="text" name="keterangan" class="w-full border-slate-200 rounded-xl" placeholder="Misal: Beli Semen 50 Sak di UD Ema Kencana" required>
            </div>
            <div class="mb-6">
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Foto Nota/Kwitansi (Opsional)</label>
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
<div id="modalGaji" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden flex flex-col max-h-[90vh]">

        <!-- Header Modal -->
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center shrink-0">
            <h3 class="text-lg font-bold text-slate-900">Hitung & Bayar Upah</h3>
            <button onclick="toggleModal('modalGaji')" class="text-slate-400 hover:text-red-500 transition-colors">
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
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Pilih Pekerja</label>
                    <select id="inputPegawaiGaji" name="pegawai_id" class="w-full border-slate-200 rounded-xl bg-slate-50" required>
                        @foreach($proyek->pegawais as $p)
                        <option value="{{ $p->id }}">{{ $p->nama }} ({{ $p->jabatan->nama_jabatan ?? '-' }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Dari Tanggal</label>
                        <input type="date" id="inputStartGaji" name="start_date" class="w-full border-slate-200 rounded-xl bg-slate-50" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Sampai Tanggal</label>
                        <input type="date" id="inputEndGaji" name="end_date" class="w-full border-slate-200 rounded-xl bg-slate-50" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                <button type="submit" id="btnCekGaji" class="w-full py-2.5 bg-slate-800 text-white font-bold rounded-xl hover:bg-black transition-colors shadow-sm flex justify-center items-center">
                    Tarik Data Kehadiran & Kasbon
                </button>
            </form>

            <!-- TAHAP 2: HASIL PERHITUNGAN (Disembunyikan awalnya) -->
            <div id="areaHasilGaji" class="hidden border-t border-slate-200 pt-6 mt-2">
                <h4 class="text-sm font-bold text-slate-800 mb-3 border-l-4 border-blue-500 pl-2">Rincian Slip Gaji: <span id="labelNamaPegawai" class="text-blue-600"></span></h4>

                <div class="bg-slate-50 rounded-xl p-4 mb-4 border border-slate-100 text-sm">
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
                        <span class="font-black text-slate-900" id="txtUpahKotor">Rp 0</span>
                    </div>
                    <div class="flex justify-between mb-1 mt-2 text-red-500">
                        <span>Potongan Kasbon Lama</span>
                        <span class="font-bold" id="txtPotonganKasbon">- Rp 0</span>
                    </div>
                </div>

                <!-- FORM FINAL PEMBAYARAN -->
                <form action="{{ route('proyek.keuangan.bayar-gaji', $proyek->id) }}" method="POST">
                    @csrf
                    <!-- Data Tersembunyi (Hidden) dikirim ke Controller final -->
                    <input type="hidden" name="pegawai_id" id="finalPegawaiId">
                    <input type="hidden" name="start_date" id="finalStartDate">
                    <input type="hidden" name="end_date" id="finalEndDate">
                    <input type="hidden" name="estimasi_gaji_sistem" id="finalEstimasiSistem">

                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-5">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-xs font-bold text-blue-800 uppercase">Hak Gaji Bersih (Sistem)</span>
                            <span class="text-xl font-black text-blue-700" id="txtGajiBersih">Rp 0</span>
                        </div>

                        <div class="mt-4">
                            <label class="block text-xs font-bold text-slate-700 mb-1">Nominal Uang di Amplop (Yang Sebenarnya Dibayar)</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 font-bold text-slate-400">Rp</span>
                                <input type="number" id="inputNominalDibayar" name="nominal_dibayar" class="w-full pl-9 border-slate-300 rounded-lg font-bold text-lg text-slate-900 focus:ring-blue-500 focus:border-blue-500" required>
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

    // 2. FUNGSI AJAX UNTUK HITUNG GAJI (MENCEGAH PINDAH HALAMAN KE JSON)
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

                    // Fungsi simpel untuk format angka ke Rupiah
                    const formatRp = (angka) => new Intl.NumberFormat('id-ID').format(angka);

                    // Suntikkan data dari Controller ke dalam HTML Modal Anda
                    document.getElementById('labelNamaPegawai').innerText = data.nama_pegawai;
                    document.getElementById('txtHariFull').innerText = data.hari_full;
                    document.getElementById('txtHariSetengah').innerText = data.hari_setengah;
                    document.getElementById('txtUpahKotor').innerText = 'Rp ' + formatRp(data.total_upah_kotor);
                    document.getElementById('txtPotonganKasbon').innerText = '- Rp ' + formatRp(data.total_potongan_kasbon);
                    document.getElementById('txtGajiBersih').innerText = 'Rp ' + formatRp(data.estimasi_gaji_bersih);

                    // Isi otomatis input nominal uang amplop (Tukang tidak boleh digaji minus)
                    document.getElementById('inputNominalDibayar').value = Math.max(0, data.estimasi_gaji_bersih);

                    // Set input hidden untuk disubmit saat finalisasi pembayaran
                    document.getElementById('finalPegawaiId').value = formData.get('pegawai_id');
                    document.getElementById('finalStartDate').value = formData.get('start_date');
                    document.getElementById('finalEndDate').value = formData.get('end_date');
                    document.getElementById('finalEstimasiSistem').value = data.estimasi_gaji_bersih;

                    // Munculkan area hasil perhitungannya!
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