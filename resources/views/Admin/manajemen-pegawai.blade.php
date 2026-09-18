@extends('layouts.admin')

@section('title', 'Manajemen Pegawai')

@section('content')

<!-- Notifikasi Pesan Sukses -->
@if(session('success'))
<div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center justify-between shadow-sm" role="alert">
    <div class="flex items-center space-x-3">
        <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span class="font-medium text-sm">{{ session('success') }}</span>
    </div>
    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>
</div>
@endif

<!-- ================= BANNER HALAMAN ================= -->
<div class="relative bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 lg:p-8 overflow-hidden mb-6">
    <!-- Background Image (Opacity dinaikkan ke 40% agar gambar muncul) -->
    <div class="absolute inset-0 bg-cover bg-center bg-no-repeat opacity-90 pointer-events-none"
        style="background-image: url('{{ asset('images/flower-mkp.jpg') }}');">
    </div>
    <!-- White Gradient Mask (Gradasi lebih tipis agar gambar di belakangnya tetap terlihat) -->
    <div class="absolute inset-0 bg-gradient-to-r from-white/95 via-white/70 to-white/30 pointer-events-none"></div>
    <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
        <!-- Teks Informasi Kiri -->
        <div class="max-w-xl">
            <!-- Sub-tag Badge Netral -->
            <div class="inline-flex items-center space-x-2 bg-slate-100/90 border border-slate-200 px-3 py-1 rounded-full text-xs font-semibold text-slate-600 mb-3 backdrop-blur-sm">
                <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                <span>Modul SDM & Personalia</span>
            </div>
            <h1 class="text-2xl lg:text-3xl font-bold text-slate-900 tracking-tight">
                Manajemen Pegawai
            </h1>
            <p class="mt-2 text-slate-600 text-sm leading-relaxed">
                Kelola data pekerja lapangan dan pengawas proyek Anda. Tambahkan pegawai baru dan buatkan akun akses sistem khusus untuk jabatan pengawas secara terpusat.
            </p>
        </div>
        <!-- Kartu Ringkasan Statistik Kanan -->
        <div class="flex items-center gap-3">
            <div class="bg-white/80 backdrop-blur-sm border border-slate-200/80 px-4 py-3 rounded-xl min-w-[125px] text-center sm:text-left shadow-sm">
                <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Total Pegawai</p>
                <p class="text-xl font-bold text-slate-800 mt-0.5">
                    {{ $pegawais->count() }} <span class="text-xs font-normal text-slate-500">orang</span>
                </p>
            </div>
            <div class="bg-white/80 backdrop-blur-sm border border-slate-200/80 px-4 py-3 rounded-xl min-w-[125px] text-center sm:text-left shadow-sm">
                <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Jabatan</p>
                <p class="text-xl font-bold text-slate-800 mt-0.5">
                    {{ $jabatans->count() }} <span class="text-xs font-normal text-slate-500">jenis</span>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- SISTEM TAB NAVIGASI -->
<div class="mb-6 border-b border-slate-200">
    <nav class="flex space-x-8" aria-label="Tabs">
        <button id="tab-btn-pegawai" onclick="switchTab('pegawai')" class="inline-flex items-center py-4 px-1 border-b-2 border-amber-500 font-semibold text-sm text-slate-900 transition-colors focus:outline-none">
            <svg class="w-5 h-5 mr-2 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            Daftar Pegawai Aktif
            <span class="ml-2 bg-slate-100 text-slate-700 py-0.5 px-2 rounded-full text-xs font-medium">{{ $pegawais->count() }}</span>
        </button>
        <button id="tab-btn-jabatan" onclick="switchTab('jabatan')" class="inline-flex items-center py-4 px-1 border-b-2 border-transparent font-medium text-sm text-slate-500 hover:text-slate-700 hover:border-slate-300 transition-colors focus:outline-none">
            <svg class="w-5 h-5 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
            Kategori Jabatan & Gaji
            <span class="ml-2 bg-slate-100 text-slate-600 py-0.5 px-2 rounded-full text-xs font-medium">{{ $jabatans->count() }}</span>
        </button>
    </nav>
</div>

<!-- KONTEN TAB 1: DAFTAR PEGAWAI -->
<div id="tab-content-pegawai" class="block">
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 lg:p-8">
        <!-- Header Tabel & Tombol Tambah -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
            <div>
                <h2 class="text-lg font-bold text-slate-800">Daftar Pegawai</h2>
                <p class="text-xs text-slate-500 mt-0.5">Kelola data seluruh pekerja lapangan dan pengawas aktif Anda.</p>
            </div>

            @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'super_admin']))
            @if($jabatans->count() > 0)
            <button type="button" onclick="toggleModal('modalTambahPegawai')" class="inline-flex items-center justify-center px-4 py-2.5 bg-slate-800 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm hover:bg-slate-700 hover:-translate-y-0.5 hover:shadow focus:ring-2 focus:ring-slate-400 focus:ring-offset-1">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah Pegawai Baru
            </button>
            @else
            <button type="button" disabled class="inline-flex items-center justify-center px-4 py-2.5 bg-slate-200 text-slate-400 text-sm font-medium rounded-lg cursor-not-allowed shadow-none" title="Harap buat Kategori Jabatan terlebih dahulu">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah Pegawai
            </button>
            @endif
            @endif
        </div>

        <!-- Filter & Search Bar Toolbar -->
        <div class="flex flex-col sm:flex-row gap-3 mb-6">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" id="searchPegawai" onkeyup="filterPegawaiTable()" placeholder="Cari nama atau nomor telepon..." class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all">
            </div>
            <div class="w-full sm:w-48">
                <select id="filterJabatan" onchange="filterPegawaiTable()" class="w-full py-2 px-3 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all">
                    <option value="">Semua Jabatan</option>
                    @foreach($jabatans as $jabatan)
                    <option value="{{ strtolower($jabatan->nama_jabatan) }}">{{ $jabatan->nama_jabatan }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Tabel Pegawai -->
        <div class="overflow-x-auto rounded-lg border border-slate-100">
            <table class="w-full text-left border-collapse min-w-[600px]" id="tablePegawai">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/80 text-xs font-semibold uppercase tracking-wider text-slate-600">
                        <th class="py-3.5 px-4">Nama Lengkap</th>
                        <th class="py-3.5 px-4">Jabatan</th>
                        <th class="py-3.5 px-4">Nomor Telepon</th>
                        @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'super_admin']))
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="text-sm text-slate-700 divide-y divide-slate-100">
                    @forelse ($pegawais as $pegawai)
                    <tr class="hover:bg-slate-50/80 transition-colors row-pegawai">
                        <td class="py-3.5 px-4 font-medium text-slate-800 cell-nama">
                            {{ $pegawai->nama }}
                            @if($pegawai->user_id)
                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-800" title="Memiliki Akun Login Sistem">
                                <span class="w-1.5 h-1.5 mr-1 bg-emerald-500 rounded-full"></span>Aktif
                            </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 cell-jabatan">
                            <span class="px-2.5 py-1 {{ in_array(strtolower($pegawai->jabatan->nama_jabatan), ['mandor', 'pengawas']) ? 'bg-blue-50 text-blue-700 border border-blue-100' : 'bg-amber-50 text-amber-700 border border-amber-100' }} rounded-md text-xs font-medium">
                                {{ $pegawai->jabatan->nama_jabatan }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-slate-600 cell-telp">{{ $pegawai->no_telp }}</td>

                        @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'super_admin']))
                        <td class="py-3.5 px-4 text-right">
                            <div class="flex justify-end space-x-1">
                                <!-- Detail -->
                                <button type="button"
                                    onclick="openDetailPegawaiModal('{{ addslashes($pegawai->nama) }}', '{{ addslashes($pegawai->jabatan->nama_jabatan) }}', '{{ $pegawai->no_telp }}', '{{ $pegawai->user ? $pegawai->user->email : '-' }}')"
                                    class="p-1.5 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors"
                                    title="Lihat Detail"
                                    aria-label="Detail {{ $pegawai->nama }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>

                                <!-- Edit -->
                                <button type="button"
                                    onclick="openEditPegawaiModal({{ $pegawai->id }}, '{{ addslashes($pegawai->nama) }}', '{{ $pegawai->no_telp }}', {{ $pegawai->jabatan_id }}, '{{ addslashes($pegawai->jabatan->nama_jabatan) }}', '{{ $pegawai->user ? $pegawai->user->email : '' }}')"
                                    class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                    title="Edit Pegawai"
                                    aria-label="Edit {{ $pegawai->nama }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>

                                <!-- Hapus -->
                                <form id="form-hapus-pegawai-{{ $pegawai->id }}" action="{{ route('pegawai.destroy', $pegawai->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                        onclick="konfirmasiHapus('form-hapus-pegawai-{{ $pegawai->id }}', 'Pegawai {{ addslashes($pegawai->nama) }}')"
                                        class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors"
                                        title="Hapus Pegawai"
                                        aria-label="Hapus {{ $pegawai->nama }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-12 text-center text-slate-400">
                            <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <p class="font-medium text-slate-600">Belum ada data pegawai</p>
                            <p class="text-xs text-slate-400 mt-1">Silakan tambahkan data pegawai baru untuk memulai.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- KONTEN TAB 2: KATEGORI JABATAN & GAJI -->
<div id="tab-content-jabatan" class="hidden">
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 lg:p-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <div>
                <h2 class="text-lg font-bold text-slate-800">Kategori Jabatan dan Gaji Harian</h2>
                <p class="text-xs text-slate-500 mt-0.5">Konfigurasi standar gaji harian berdasarkan jenis peran kerja.</p>
            </div>

            @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'super_admin']))
            <button type="button" onclick="toggleModal('modalTambahJabatan')" class="inline-flex items-center px-4 py-2.5 bg-slate-800 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm hover:bg-slate-700 hover:-translate-y-0.5 hover:shadow focus:ring-2 focus:ring-slate-400 focus:ring-offset-1">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah Kategori Jabatan
            </button>
            @endif
        </div>

        <div class="overflow-x-auto rounded-lg border border-slate-100">
            <table class="w-full text-left border-collapse min-w-[500px]">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/80 text-xs font-semibold uppercase tracking-wider text-slate-600">
                        <th class="py-3.5 px-4">Nama Jabatan</th>
                        <th class="py-3.5 px-4">Standar Gaji Harian</th>
                        @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'super_admin']))
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="text-sm text-slate-700 divide-y divide-slate-100">
                    @forelse ($jabatans as $jabatan)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="py-3.5 px-4 font-medium text-slate-800">{{ $jabatan->nama_jabatan }}</td>
                        <td class="py-3.5 px-4 font-semibold text-emerald-600">
                            Rp {{ number_format($jabatan->gaji_harian, 0, ',', '.') }}
                            <span class="text-xs font-normal text-slate-400">/ hari</span>
                        </td>

                        @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'super_admin']))
                        <td class="py-3.5 px-4 text-right">
                            <div class="flex justify-end space-x-1">
                                <button type="button"
                                    onclick="openEditJabatanModal({{ $jabatan->id }}, '{{ addslashes($jabatan->nama_jabatan) }}', {{ $jabatan->gaji_harian }})"
                                    class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                    title="Edit Kategori"
                                    aria-label="Edit {{ $jabatan->nama_jabatan }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>

                                <form id="form-hapus-jabatan-{{ $jabatan->id }}" action="{{ route('jabatan.destroy', $jabatan->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                        onclick="konfirmasiHapus('form-hapus-jabatan-{{ $jabatan->id }}', 'Kategori {{ addslashes($jabatan->nama_jabatan) }}')"
                                        class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors"
                                        title="Hapus Kategori"
                                        aria-label="Hapus {{ $jabatan->nama_jabatan }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="py-12 text-center text-slate-400">
                            <p class="font-medium text-slate-600">Belum ada kategori jabatan</p>
                            <p class="text-xs text-slate-400 mt-1">Buat kategori jabatan terlebih dahulu sebelum menambah pegawai.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ================= AREA MODAL ================= -->
@if(auth()->check() && in_array(auth()->user()->role, ['admin', 'super_admin']))

<!-- MODAL TAMBAH KATEGORI GAJI -->
<div id="modalTambahJabatan" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm overflow-y-auto w-full h-full" role="dialog" aria-modal="true">
    <div class="relative top-20 mx-auto p-6 w-full max-w-md shadow-2xl rounded-2xl bg-white border border-slate-100">
        <div class="flex justify-between items-center pb-3 border-b border-slate-100 mb-4">
            <h3 class="text-lg font-bold text-slate-800">Tambah Kategori Gaji</h3>
            <button type="button" onclick="toggleModal('modalTambahJabatan')" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form action="{{ route('jabatan.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Nama Jabatan</label>
                <input type="text" name="nama_jabatan" placeholder="Contoh: Mandor Utama" required class="w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm text-slate-900 focus:ring-amber-500 focus:border-amber-500 outline-none ring-1 ring-inset ring-slate-300">
            </div>

            <div class="mb-6">
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Gaji Harian (Rp)</label>
                <input type="number" name="gaji_harian" placeholder="Contoh: 150000" min="0" required class="w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm text-slate-900 focus:ring-amber-500 focus:border-amber-500 outline-none ring-1 ring-inset ring-slate-300">
            </div>

            <div class="flex justify-end space-x-3 pt-3 border-t border-slate-100">
                <button type="button" onclick="toggleModal('modalTambahJabatan')" class="px-4 py-2 bg-white border border-slate-300 text-slate-700 text-sm font-medium rounded-lg hover:bg-slate-50 transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 bg-slate-800 text-white text-sm font-medium rounded-lg hover:bg-slate-700 transition-colors shadow-sm">Simpan Kategori</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL TAMBAH PEGAWAI -->
<div id="modalTambahPegawai" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm overflow-y-auto w-full h-full" role="dialog" aria-modal="true">
    <div class="relative top-16 mx-auto p-6 w-full max-w-md shadow-2xl rounded-2xl bg-white border border-slate-100">
        <div class="flex justify-between items-center pb-3 border-b border-slate-100 mb-4">
            <h3 class="text-lg font-bold text-slate-800">Tambah Pegawai Baru</h3>
            <button type="button" onclick="toggleModal('modalTambahPegawai')" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        @if ($errors->any())
        <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-lg text-xs">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('pegawai.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Nama Lengkap</label>
                <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Masukkan nama lengkap" required class="w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm text-slate-900 focus:ring-amber-500 focus:border-amber-500 outline-none ring-1 ring-inset ring-slate-300">
            </div>

            <div class="mb-4">
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Nomor Telepon / WA</label>
                <input type="text" name="no_telp" value="{{ old('no_telp') }}" placeholder="08xxxxxxxxxx" required class="w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm text-slate-900 focus:ring-amber-500 focus:border-amber-500 outline-none ring-1 ring-inset ring-slate-300">
            </div>

            <div class="mb-4">
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Jabatan Pekerja</label>
                <select name="jabatan_id" id="jabatanSelect" onchange="checkJabatan()" required class="w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm text-slate-900 focus:ring-amber-500 focus:border-amber-500 outline-none ring-1 ring-inset ring-slate-300 bg-white">
                    <option value="">-- Pilih Jabatan --</option>
                    @foreach($jabatans as $jabatan)
                    <option value="{{ $jabatan->id }}" data-nama="{{ strtolower($jabatan->nama_jabatan) }}" {{ old('jabatan_id') == $jabatan->id ? 'selected' : '' }}>
                        {{ $jabatan->nama_jabatan }} (Rp {{ number_format($jabatan->gaji_harian, 0, ',', '.') }}/hari)
                    </option>
                    @endforeach
                </select>
            </div>

            <div id="formAkunContainer" class="hidden p-4 bg-slate-50 border border-slate-200 rounded-xl mb-4 transition-all">
                <p class="text-xs text-amber-600 font-bold mb-3 uppercase tracking-wider flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 0121 9z"></path>
                    </svg>
                    Pembuatan Akun Login Pengawas
                </p>
                <div class="mb-3">
                    <label class="block text-xs font-medium text-slate-700 mb-1">Email Pengguna</label>
                    <input type="email" name="email" id="inputEmail" value="{{ old('email') }}" placeholder="pengawas@email.com" class="w-full rounded-lg border-slate-300 py-2 px-3 text-sm text-slate-900 focus:ring-amber-500 focus:border-amber-500 outline-none ring-1 ring-inset ring-slate-300 bg-white">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">Password Awal</label>
                    <input type="password" name="password" id="inputPassword" placeholder="Minimal 6 karakter" class="w-full rounded-lg border-slate-300 py-2 px-3 text-sm text-slate-900 focus:ring-amber-500 focus:border-amber-500 outline-none ring-1 ring-inset ring-slate-300 bg-white">
                    <p class="text-[11px] text-slate-500 mt-1">Berikan kredensial ini kepada pengawas untuk login awal.</p>
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-3 border-t border-slate-100">
                <button type="button" onclick="toggleModal('modalTambahPegawai')" class="px-4 py-2 bg-white border border-slate-300 text-slate-700 text-sm font-medium rounded-lg hover:bg-slate-50 transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 bg-slate-800 text-white text-sm font-medium rounded-lg hover:bg-slate-700 transition-colors shadow-sm">Simpan Pegawai</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDIT KATEGORI GAJI -->
<div id="modalEditJabatan" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm overflow-y-auto w-full h-full" role="dialog" aria-modal="true">
    <div class="relative top-20 mx-auto p-6 w-full max-w-md shadow-2xl rounded-2xl bg-white border border-slate-100">
        <div class="flex justify-between items-center pb-3 border-b border-slate-100 mb-4">
            <h3 class="text-lg font-bold text-slate-800">Edit Kategori Gaji</h3>
            <button type="button" onclick="closeEditJabatanModal()" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form id="formEditJabatan" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Nama Jabatan</label>
                <input type="text" id="edit_nama_jabatan" name="nama_jabatan" required class="w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm text-slate-900 focus:ring-amber-500 focus:border-amber-500 outline-none ring-1 ring-inset ring-slate-300">
            </div>

            <div class="mb-6">
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Gaji Harian (Rp)</label>
                <input type="number" id="edit_gaji_harian" name="gaji_harian" min="0" required class="w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm text-slate-900 focus:ring-amber-500 focus:border-amber-500 outline-none ring-1 ring-inset ring-slate-300">
            </div>

            <div class="flex justify-end space-x-3 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeEditJabatanModal()" class="px-4 py-2 bg-white border border-slate-300 text-slate-700 text-sm font-medium rounded-lg hover:bg-slate-50 transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 bg-slate-800 text-white text-sm font-medium rounded-lg hover:bg-slate-700 transition-colors shadow-sm">Update Data</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL DETAIL PEGAWAI -->
<div id="modalDetailPegawai" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm overflow-y-auto w-full h-full" role="dialog" aria-modal="true">
    <div class="relative top-20 mx-auto p-6 w-full max-w-md shadow-2xl rounded-2xl bg-white border border-slate-100">
        <div class="flex justify-between items-center pb-3 border-b border-slate-100 mb-6">
            <h3 class="text-lg font-bold text-slate-800">Detail Informasi Pegawai</h3>
            <button type="button" onclick="closeDetailPegawaiModal()" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="space-y-4">
            <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                <p class="text-[11px] text-slate-400 uppercase tracking-wider font-semibold">Nama Lengkap</p>
                <p id="detail_nama" class="text-slate-800 font-semibold text-base mt-0.5"></p>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                    <p class="text-[11px] text-slate-400 uppercase tracking-wider font-semibold">Jabatan</p>
                    <p id="detail_jabatan" class="text-slate-800 font-medium text-sm mt-0.5"></p>
                </div>
                <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                    <p class="text-[11px] text-slate-400 uppercase tracking-wider font-semibold">No. Telepon</p>
                    <p id="detail_notelp" class="text-slate-800 font-medium text-sm mt-0.5"></p>
                </div>
            </div>
            <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                <p class="text-[11px] text-slate-400 uppercase tracking-wider font-semibold">Email Akun Sistem</p>
                <p id="detail_email" class="text-slate-800 font-medium text-sm mt-0.5"></p>
            </div>
        </div>

        <div class="mt-6 pt-3 border-t border-slate-100">
            <button type="button" onclick="closeDetailPegawaiModal()" class="w-full px-4 py-2.5 bg-slate-800 text-white text-sm font-medium rounded-lg hover:bg-slate-700 transition-colors">Tutup</button>
        </div>
    </div>
</div>

<!-- MODAL EDIT PEGAWAI -->
<div id="modalEditPegawai" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm overflow-y-auto w-full h-full" role="dialog" aria-modal="true">
    <div class="relative top-16 mx-auto p-6 w-full max-w-md shadow-2xl rounded-2xl bg-white border border-slate-100">
        <div class="flex justify-between items-center pb-3 border-b border-slate-100 mb-4">
            <h3 class="text-lg font-bold text-slate-800">Edit Data Pegawai</h3>
            <button type="button" onclick="closeEditPegawaiModal()" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form id="formEditPegawai" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Nama Lengkap</label>
                <input type="text" name="nama" id="edit_nama_pegawai" required class="w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm text-slate-900 focus:ring-amber-500 focus:border-amber-500 outline-none ring-1 ring-inset ring-slate-300">
            </div>

            <div class="mb-4">
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Nomor Telepon</label>
                <input type="text" name="no_telp" id="edit_notelp_pegawai" required class="w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm text-slate-900 focus:ring-amber-500 focus:border-amber-500 outline-none ring-1 ring-inset ring-slate-300">
            </div>

            <div class="mb-4">
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Jabatan</label>
                <select name="jabatan_id" id="edit_jabatanSelect" onchange="checkEditJabatan()" required class="w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm text-slate-900 focus:ring-amber-500 focus:border-amber-500 outline-none ring-1 ring-inset ring-slate-300 bg-white">
                    <option value="" disabled>Pilih Jabatan...</option>
                    @foreach ($jabatans as $jabatan)
                    <option value="{{ $jabatan->id }}" data-nama="{{ strtolower($jabatan->nama_jabatan) }}">
                        {{ $jabatan->nama_jabatan }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div id="edit_formAkunContainer" class="hidden p-4 bg-slate-50 border border-slate-200 rounded-xl mb-4 transition-all">
                <p class="text-xs text-amber-600 font-bold mb-3 uppercase tracking-wider">Pengaturan Akun Login</p>
                <div class="mb-3">
                    <label class="block text-xs font-medium text-slate-700 mb-1">Email Pengguna</label>
                    <input type="email" name="email" id="edit_inputEmail" class="w-full rounded-lg border-slate-300 py-2 px-3 text-sm text-slate-900 focus:ring-amber-500 focus:border-amber-500 outline-none ring-1 ring-inset ring-slate-300 bg-white">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">Password Baru (Opsional)</label>
                    <input type="text" name="password" id="edit_inputPassword" placeholder="Kosongkan jika tidak ingin diubah" class="w-full rounded-lg border-slate-300 py-2 px-3 text-sm text-slate-900 focus:ring-amber-500 focus:border-amber-500 outline-none ring-1 ring-inset ring-slate-300 bg-white">
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeEditPegawaiModal()" class="px-4 py-2 bg-white border border-slate-300 text-slate-700 text-sm font-medium rounded-lg hover:bg-slate-50 transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 bg-slate-800 text-white text-sm font-medium rounded-lg hover:bg-slate-700 transition-colors shadow-sm">Update Data</button>
            </div>
        </form>
    </div>
</div>
@endif

<!-- ================= HELPER JAVASCRIPT ================= -->
<script>
    function switchTab(tabName) {
        const tabPegawai = document.getElementById('tab-content-pegawai');
        const tabJabatan = document.getElementById('tab-content-jabatan');
        const btnPegawai = document.getElementById('tab-btn-pegawai');
        const btnJabatan = document.getElementById('tab-btn-jabatan');

        if (tabName === 'pegawai') {
            tabPegawai.classList.remove('hidden');
            tabJabatan.classList.add('hidden');
            btnPegawai.className = 'inline-flex items-center py-4 px-1 border-b-2 border-amber-500 font-semibold text-sm text-slate-900 transition-colors focus:outline-none';
            btnJabatan.className = 'inline-flex items-center py-4 px-1 border-b-2 border-transparent font-medium text-sm text-slate-500 hover:text-slate-700 hover:border-slate-300 transition-colors focus:outline-none';
        } else {
            tabJabatan.classList.remove('hidden');
            tabPegawai.classList.add('hidden');
            btnJabatan.className = 'inline-flex items-center py-4 px-1 border-b-2 border-amber-500 font-semibold text-sm text-slate-900 transition-colors focus:outline-none';
            btnPegawai.className = 'inline-flex items-center py-4 px-1 border-b-2 border-transparent font-medium text-sm text-slate-500 hover:text-slate-700 hover:border-slate-300 transition-colors focus:outline-none';
        }
    }

    function filterPegawaiTable() {
        const searchInput = document.getElementById('searchPegawai').value.toLowerCase();
        const filterJabatan = document.getElementById('filterJabatan').value.toLowerCase();
        const rows = document.querySelectorAll('#tablePegawai .row-pegawai');

        rows.forEach(row => {
            const nama = row.querySelector('.cell-nama').textContent.toLowerCase();
            const telp = row.querySelector('.cell-telp').textContent.toLowerCase();
            const jabatan = row.querySelector('.cell-jabatan').textContent.toLowerCase();

            const matchesSearch = nama.includes(searchInput) || telp.includes(searchInput);
            const matchesJabatan = filterJabatan === '' || jabatan.includes(filterJabatan);

            if (matchesSearch && matchesJabatan) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    function toggleModal(modalID) {
        document.getElementById(modalID).classList.toggle("hidden");
    }

    // --- SCRIPT TAMBAH PEGAWAI ---
    function checkJabatan() {
        const select = document.getElementById('jabatanSelect');
        const container = document.getElementById('formAkunContainer');
        const email = document.getElementById('inputEmail');
        const password = document.getElementById('inputPassword');
        if (!select || !container || !email || !password) {
            return;
        }
        const selectedOption = select.options[select.selectedIndex];
        const val = selectedOption.getAttribute('data-nama') ?
            selectedOption.getAttribute('data-nama').toLowerCase() :
            '';
        if (val.includes('pengawas') || val.includes('mandor')) {
            container.classList.remove('hidden');
            email.setAttribute('required', 'required');
            password.setAttribute('required', 'required');
        } else {
            container.classList.add('hidden');
            email.removeAttribute('required');
            password.removeAttribute('required');
            email.value = '';
            password.value = '';
        }
    }

    // --- SCRIPT MODAL EDIT JABATAN (Biarkan Tetap) ---
    function openEditJabatanModal(id, nama, gaji) {
        document.getElementById('modalEditJabatan').classList.remove("hidden");
        document.getElementById('edit_nama_jabatan').value = nama;
        document.getElementById('edit_gaji_harian').value = gaji;

        const form = document.getElementById('formEditJabatan');
        form.action = `/manajemen-jabatan/${id}`;
    }

    function closeEditJabatanModal() {
        document.getElementById('modalEditJabatan').classList.add("hidden");
    }

    // --- SCRIPT MODAL DETAIL PEGAWAI ---
    function openDetailPegawaiModal(nama, jabatan, notelp, email) {
        document.getElementById('modalDetailPegawai').classList.remove("hidden");
        document.getElementById('detail_nama').innerText = nama;
        document.getElementById('detail_jabatan').innerText = jabatan;
        document.getElementById('detail_notelp').innerText = notelp;
        document.getElementById('detail_email').innerText = email;
    }

    function closeDetailPegawaiModal() {
        document.getElementById('modalDetailPegawai').classList.add("hidden");
    }

    // --- SCRIPT MODAL EDIT PEGAWAI ---
    // PERHATIKAN: Parameter ditambah menjadi jabatan_id dan jabatan_nama
    function openEditPegawaiModal(id, nama, notelp, jabatan_id, jabatan_nama, email) {
        document.getElementById('modalEditPegawai').classList.remove("hidden");

        // Isi Form Edit
        document.getElementById('edit_nama_pegawai').value = nama;
        document.getElementById('edit_notelp_pegawai').value = notelp;

        // Pilih opsi berdasarkan ID
        document.getElementById('edit_jabatanSelect').value = jabatan_id;

        // Ubah URL action form
        document.getElementById('formEditPegawai').action = `/manajemen-pegawai/${id}`;

        // Cek apakah form akun perlu dimunculkan menggunakan jabatan_nama
        const val = jabatan_nama ? jabatan_nama.toLowerCase() : '';
        const container = document.getElementById('edit_formAkunContainer');
        const inputEmail = document.getElementById('edit_inputEmail');

        if (val.includes('pengawas') || val.includes('mandor')) {
            container.classList.remove('hidden');
            inputEmail.setAttribute('required', 'required');
            inputEmail.value = email; // Isi email lama
        } else {
            container.classList.add('hidden');
            inputEmail.removeAttribute('required');
            inputEmail.value = '';
        }
    }

    function closeEditPegawaiModal() {
        document.getElementById('modalEditPegawai').classList.add("hidden");
    }

    // Saat dropdown di modal edit diganti
    function checkEditJabatan() {
        const select = document.getElementById('edit_jabatanSelect');
        const container = document.getElementById('edit_formAkunContainer');
        const email = document.getElementById('edit_inputEmail');

        // CARA BARU: Ambil atribut data-nama dari opsi yang dipilih
        const selectedOption = select.options[select.selectedIndex];
        const val = selectedOption.getAttribute('data-nama') ? selectedOption.getAttribute('data-nama').toLowerCase() : '';

        if (val.includes('pengawas') || val.includes('mandor')) {
            container.classList.remove('hidden');
            email.setAttribute('required', 'required');
        } else {
            container.classList.add('hidden');
            email.removeAttribute('required');
            email.value = ''; // Reset email jika ganti ke Tukang
        }
    }
</script>


@endsection