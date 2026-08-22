@extends('layouts.admin')

@section('title', 'Manajemen Pegawai')

@section('content')

<!-- Notifikasi Pesan Sukses -->
@if(session('success'))
<div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg relative" role="alert">
    <span class="block sm:inline font-medium">{{ session('success') }}</span>
</div>
@endif

<!-- BANNER HALAMAN -->
<div class="relative bg-white rounded-xl shadow-sm border border-slate-100 p-6 lg:p-8 overflow-hidden mb-6">
    <div class="absolute inset-0 bg-cover bg-center bg-no-repeat opacity-30"
        style="background-image: url('{{ asset('images/flower-mkp.jpg') }}');">
    </div>
    <div class="relative z-10">
        <h1 class="text-2xl font-bold text-slate-800">Halaman Manajemen Pegawai</h1>
        <p class="mt-2 text-slate-600 max-w-2xl">
            Kelola data pekerja lapangan dan pengawas proyek Anda. Tambahkan pegawai baru dan buatkan akun akses sistem khusus untuk jabatan pengawas.
        </p>
    </div>
</div>

<!-- BAGIAN 2: KATEGORI JABATAN & GAJI (Sekarang di atas agar alurnya logis: Buat kategori dulu, baru rekrut orang) -->
<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 lg:p-8 mb-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h2 class="text-lg font-bold text-slate-800 border-b-2 border-amber-500 inline-block pb-1">Kategori Jabatan dan Gaji</h2>
            <p class="text-sm text-slate-500 mt-2">Atur kategori jabatan beserta standar gaji harian sebelum merekrut pegawai.</p>
        </div>
        @if(auth()->check() && auth()->user()->role === 'admin')
        <button onclick="toggleModal('modalTambahJabatan')" class="inline-flex items-center px-4 py-2 bg-slate-800 text-white text-sm font-medium rounded-lg transition-all duration-300 shadow-sm hover:bg-slate-700 hover:-translate-y-1 hover:shadow-md active:translate-y-0">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Tambah Kategori
        </button>
        @endif
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[500px]">
            <thead>
                <tr class="border-b border-slate-200">
                    <th class="py-3 px-4 text-sm font-semibold text-slate-600 bg-slate-50 rounded-tl-lg">Nama Jabatan</th>
                    <th class="py-3 px-4 text-sm font-semibold text-slate-600 bg-slate-50">Gaji Harian</th>
                    @if(auth()->check() && auth()->user()->role === 'admin')
                    <th class="py-3 px-4 text-sm font-semibold text-slate-600 bg-slate-50 text-right rounded-tr-lg">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody class="text-sm text-slate-700">
                <!-- Looping Data Jabatan Dinamis -->
                @forelse ($jabatans as $jabatan)
                <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                    <td class="py-4 px-4 font-medium text-slate-800">{{ $jabatan->nama_jabatan }}</td>
                    <td class="py-4 px-4 font-medium text-green-600">Rp. {{ number_format($jabatan->gaji_harian, 0, ',', '.') }}</td>
                    @if(auth()->check() && auth()->user()->role === 'admin')
                    <td class="py-4 px-4 text-right">
                        <form action="{{ route('jabatan.destroy', $jabatan->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus kategori gaji ini?');">
                            @csrf

                            <!-- Tombol Edit (Memanggil JS dengan melempar ID, Nama, dan Gaji) -->
                            <button type="button" onclick="openEditJabatanModal({{ $jabatan->id }}, '{{ $jabatan->nama_jabatan }}', {{ $jabatan->gaji_harian }})" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded transition-colors" title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <!-- Form Hapus Kategori -->
                            <form id="form-hapus-jabatan-{{ $jabatan->id }}" action="{{ route('jabatan.destroy', $jabatan->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <!-- Ubah type menjadi button dan panggil fungsi SweetAlert -->
                                <button type="button" onclick="konfirmasiHapus('form-hapus-jabatan-{{ $jabatan->id }}', 'Kategori {{ $jabatan->nama_jabatan }}')" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded transition-colors" title="Hapus">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </form>
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="py-8 text-center text-slate-500">Belum ada kategori jabatan yang ditambahkan. Silakan tambah kategori terlebih dahulu.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- BAGIAN 1: DAFTAR PEGAWAI -->
<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 lg:p-8 mb-6">
    <!-- Header Tabel & Tombol Tambah -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h2 class="text-lg font-bold text-slate-800 border-b-2 border-amber-500 inline-block pb-1">Daftar Pegawai Aktif</h2>
            <p class="text-sm text-slate-500 mt-2">Bagian ini menampilkan daftar pekerja lapangan Anda.</p>
        </div>

        @if(auth()->check() && auth()->user()->role === 'admin')
        <!-- Logika Pencegahan: Jangan biarkan tambah pegawai kalau Kategori Jabatan masih kosong -->
        @if($jabatans->count() > 0)
        <button onclick="toggleModal('modalTambahPegawai')" class="inline-flex items-center px-4 py-2 bg-slate-800 text-white text-sm font-medium rounded-lg transition-all duration-300 shadow-sm hover:bg-slate-700 hover:-translate-y-1 hover:shadow-md active:translate-y-0">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Tambah Pegawai
        </button>
        @else
        <button disabled class="inline-flex items-center px-4 py-2 bg-slate-300 text-slate-500 text-sm font-medium rounded-lg cursor-not-allowed shadow-sm" title="Harap buat Kategori Jabatan terlebih dahulu">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Tambah Pegawai
        </button>
        @endif
        @endif
    </div>

    <!-- Tabel Pegawai -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[600px]">
            <thead>
                <tr class="border-b border-slate-200">
                    <th class="py-3 px-4 text-sm font-semibold text-slate-600 bg-slate-50 rounded-tl-lg">Nama</th>
                    <th class="py-3 px-4 text-sm font-semibold text-slate-600 bg-slate-50">Jabatan</th>
                    <th class="py-3 px-4 text-sm font-semibold text-slate-600 bg-slate-50 {{ auth()->user()->role !== 'admin' ? 'rounded-tr-lg' : '' }}">Nomor Telepon</th>

                    @if(auth()->check() && auth()->user()->role === 'admin')
                    <th class="py-3 px-4 text-sm font-semibold text-slate-600 bg-slate-50 text-right rounded-tr-lg">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody class="text-sm text-slate-700">
                <!-- Looping Data Pegawai -->
                @forelse ($pegawais as $pegawai)
                <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                    <td class="py-4 px-4 font-medium text-slate-800">
                        {{ $pegawai->nama }}
                        @if($pegawai->user_id)
                        <span class="ml-2 inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-700" title="Memiliki Akun Sistem">Aktif</span>
                        @endif
                    </td>
                    <td class="py-4 px-4">
                        <span class="px-2.5 py-1 {{ in_array(strtolower($pegawai->jabatan->nama_jabatan), ['mandor', 'pengawas']) ? 'bg-blue-50 text-blue-700' : 'bg-amber-50 text-amber-700' }} rounded-md text-xs font-medium">
                            {{ $pegawai->jabatan->nama_jabatan }}
                        </span>
                    </td>
                    <td class="py-4 px-4">{{ $pegawai->no_telp }}</td>

                    @if(auth()->check() && auth()->user()->role === 'admin')
                    <td class="py-4 px-4 text-right">
                        <div class="flex justify-end space-x-2">
                            <!-- Tombol Detail (Ikon Mata) -->
                            <button type="button" onclick="openDetailPegawaiModal('{{ $pegawai->nama }}', '{{ $pegawai->jabatan->nama_jabatan }}', '{{ $pegawai->no_telp }}', '{{ $pegawai->user ? $pegawai->user->email : '-' }}')" class="p-1.5 text-slate-400 hover:text-green-600 hover:bg-green-50 rounded transition-colors" title="Lihat Detail">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>

                            <!-- Tombol Edit (Ikon Pensil) -->
                            <button type="button" onclick="openEditPegawaiModal({{ $pegawai->id }}, '{{ addslashes($pegawai->nama) }}', '{{ $pegawai->no_telp }}', {{ $pegawai->jabatan_id }}, '{{ $pegawai->jabatan->nama_jabatan }}', '{{ $pegawai->user ? $pegawai->user->email : '' }}')" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded transition-colors" title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>

                            <!-- Form Hapus Pegawai -->
                            <form id="form-hapus-pegawai-{{ $pegawai->id }}" action="{{ route('pegawai.destroy', $pegawai->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="konfirmasiHapus('form-hapus-pegawai-{{ $pegawai->id }}', 'Pegawai {{ $pegawai->nama }}')" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded transition-colors" title="Hapus">
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
                    <td colspan="4" class="py-8 text-center text-slate-500">Belum ada data pegawai yang ditambahkan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


<!-- AREA MODAL (Hanya dirender jika Admin) -->
@if(auth()->check() && auth()->user()->role === 'admin')

<!-- MODAL TAMBAH KATEGORI GAJI -->
<div id="modalTambahJabatan" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm overflow-y-auto w-full h-full">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-xl bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg leading-6 font-bold text-slate-800">Tambah Kategori Gaji</h3>
                <button type="button" onclick="toggleModal('modalTambahJabatan')" class="text-slate-400 hover:text-red-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form action="{{ route('jabatan.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nama Jabatan</label>
                    <input type="text" name="nama_jabatan" placeholder="Contoh: Tukang Kayu" required class="w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm text-slate-900 focus:ring-amber-500 focus:border-amber-500 outline-none ring-1 ring-inset ring-slate-300">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Gaji Harian (Rp)</label>
                    <input type="number" name="gaji_harian" placeholder="Contoh: 120000" min="0" required class="w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm text-slate-900 focus:ring-amber-500 focus:border-amber-500 outline-none ring-1 ring-inset ring-slate-300">
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="toggleModal('modalTambahJabatan')" class="px-4 py-2 bg-white border border-slate-300 text-slate-700 text-sm font-medium rounded-lg hover:bg-slate-50">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-slate-800 text-white text-sm font-medium rounded-lg hover:bg-[#0c2340]">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH PEGAWAI -->
<div id="modalTambahPegawai" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm overflow-y-auto w-full h-full">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-xl bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg leading-6 font-bold text-slate-800">Tambah Pegawai Baru</h3>
                <button type="button" onclick="toggleModal('modalTambahPegawai')" class="text-slate-400 hover:text-red-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Pesan Error Validasi Form -->
            @if ($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('pegawai.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="nama" value="{{ old('nama') }}" required class="w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm text-slate-900 focus:ring-amber-500 focus:border-amber-500 outline-none ring-1 ring-inset ring-slate-300">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nomor Telepon</label>
                    <input type="text" name="no_telp" value="{{ old('no_telp') }}" required class="w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm text-slate-900 focus:ring-amber-500 focus:border-amber-500 outline-none ring-1 ring-inset ring-slate-300">
                </div>

                <!-- JABATAN SEKARANG DIAMBIL DARI DATABASE -->
                <div class="mb-4">
                    <label>Jabatan Pekerja</label>
                    <select name="jabatan_id" class="form-control" required>
                        <option value="">-- Pilih Jabatan --</option>
                        @foreach($jabatans as $jabatan)
                        <option value="{{ $jabatan->id }}">{{ $jabatan->nama_jabatan }} (Rp {{ number_format($jabatan->gaji, 0, ',', '.') }}/hari)</option>
                        @endforeach
                    </select>
                </div>

                <div id="formAkunContainer" class="hidden p-4 bg-slate-50 border border-slate-200 rounded-lg mb-4">
                    <p class="text-xs text-amber-600 font-bold mb-3 uppercase tracking-wider">Pembuatan Akun Login</p>
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Email Pengguna</label>
                        <input type="email" name="email" id="inputEmail" value="{{ old('email') }}" class="w-full rounded-lg border-slate-300 py-2 px-3 text-sm text-slate-900 focus:ring-amber-500 focus:border-amber-500 outline-none ring-1 ring-inset ring-slate-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Password Awal</label>
                        <input type="text" name="password" id="inputPassword" class="w-full rounded-lg border-slate-300 py-2 px-3 text-sm text-slate-900 focus:ring-amber-500 focus:border-amber-500 outline-none ring-1 ring-inset ring-slate-300">
                        <p class="text-xs text-slate-500 mt-1">Berikan ke pengawas untuk login pertama kali.</p>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="toggleModal('modalTambahPegawai')" class="px-4 py-2 bg-white border border-slate-300 text-slate-700 text-sm font-medium rounded-lg hover:bg-slate-50">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-slate-800 text-white text-sm font-medium rounded-lg hover:bg-[#0c2340]">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL EDIT KATEGORI GAJI -->
<div id="modalEditJabatan" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm overflow-y-auto w-full h-full">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-xl bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg leading-6 font-bold text-slate-800">Edit Kategori Gaji</h3>
                <button type="button" onclick="closeEditJabatanModal()" class="text-slate-400 hover:text-red-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Form Action akan diisi otomatis oleh JavaScript -->
            <form id="formEditJabatan" method="POST">
                @csrf
                @method('PUT') <!-- Wajib untuk proses Update di Laravel -->

                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nama Jabatan</label>
                    <input type="text" id="edit_nama_jabatan" name="nama_jabatan" required class="w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm text-slate-900 focus:ring-amber-500 focus:border-amber-500 outline-none ring-1 ring-inset ring-slate-300">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Gaji Harian (Rp)</label>
                    <input type="number" id="edit_gaji_harian" name="gaji_harian" min="0" required class="w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm text-slate-900 focus:ring-amber-500 focus:border-amber-500 outline-none ring-1 ring-inset ring-slate-300">
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="closeEditJabatanModal()" class="px-4 py-2 bg-white border border-slate-300 text-slate-700 text-sm font-medium rounded-lg hover:bg-slate-50">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-amber-500 text-white text-sm font-medium rounded-lg hover:bg-amber-600">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL DETAIL PEGAWAI -->
<div id="modalDetailPegawai" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm overflow-y-auto w-full h-full">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-xl bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg leading-6 font-bold text-slate-800">Detail Informasi Pegawai</h3>
                <button type="button" onclick="closeDetailPegawaiModal()" class="text-slate-400 hover:text-red-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="space-y-4">
                <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
                    <p class="text-xs text-slate-500 mb-1 uppercase tracking-wider font-semibold">Nama Lengkap</p>
                    <p id="detail_nama" class="text-slate-900 font-medium"></p>
                </div>
                <div class="flex gap-4">
                    <div class="flex-1 bg-slate-50 p-3 rounded-lg border border-slate-100">
                        <p class="text-xs text-slate-500 mb-1 uppercase tracking-wider font-semibold">Jabatan</p>
                        <p id="detail_jabatan" class="text-slate-900 font-medium"></p>
                    </div>
                    <div class="flex-1 bg-slate-50 p-3 rounded-lg border border-slate-100">
                        <p class="text-xs text-slate-500 mb-1 uppercase tracking-wider font-semibold">No. Telepon</p>
                        <p id="detail_notelp" class="text-slate-900 font-medium"></p>
                    </div>
                </div>
                <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
                    <p class="text-xs text-slate-500 mb-1 uppercase tracking-wider font-semibold">Email Akun Sistem</p>
                    <p id="detail_email" class="text-slate-900 font-medium"></p>
                </div>
            </div>
            <div class="mt-6">
                <button type="button" onclick="closeDetailPegawaiModal()" class="w-full px-4 py-2 bg-slate-800 text-white text-sm font-medium rounded-lg hover:bg-[#0c2340]">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL EDIT PEGAWAI -->
<div id="modalEditPegawai" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm overflow-y-auto w-full h-full">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-xl bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg leading-6 font-bold text-slate-800">Edit Data Pegawai</h3>
                <button type="button" onclick="closeEditPegawaiModal()" class="text-slate-400 hover:text-red-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="formEditPegawai" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="nama" id="edit_nama_pegawai" required class="w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm text-slate-900 focus:ring-amber-500 focus:border-amber-500 outline-none ring-1 ring-inset ring-slate-300">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nomor Telepon</label>
                    <input type="text" name="no_telp" id="edit_notelp_pegawai" required class="w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm text-slate-900 focus:ring-amber-500 focus:border-amber-500 outline-none ring-1 ring-inset ring-slate-300">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Jabatan</label>
                    <select name="jabatan_id" id="edit_jabatanSelect" onchange="checkEditJabatan()" required class="w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm text-slate-900 focus:ring-amber-500 focus:border-amber-500 outline-none ring-1 ring-inset ring-slate-300 bg-white">
                        <option value="" disabled>Pilih Jabatan...</option>
                        @foreach ($jabatans as $jabatan)
                        <!-- Ubah value menjadi ID, dan tambahkan data-nama untuk dibaca JavaScript -->
                        <option value="{{ $jabatan->id }}" data-nama="{{ strtolower($jabatan->nama_jabatan) }}">
                            {{ $jabatan->nama_jabatan }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Form Akun untuk Edit -->
                <div id="edit_formAkunContainer" class="hidden p-4 bg-slate-50 border border-slate-200 rounded-lg mb-4">
                    <p class="text-xs text-amber-600 font-bold mb-3 uppercase tracking-wider">Pengaturan Akun Login</p>
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Email Pengguna</label>
                        <input type="email" name="email" id="edit_inputEmail" class="w-full rounded-lg border-slate-300 py-2 px-3 text-sm text-slate-900 focus:ring-amber-500 focus:border-amber-500 outline-none ring-1 ring-inset ring-slate-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Password Baru (Opsional)</label>
                        <input type="text" name="password" id="edit_inputPassword" placeholder="Kosongkan jika tidak ingin diubah" class="w-full rounded-lg border-slate-300 py-2 px-3 text-sm text-slate-900 focus:ring-amber-500 focus:border-amber-500 outline-none ring-1 ring-inset ring-slate-300">
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="closeEditPegawaiModal()" class="px-4 py-2 bg-white border border-slate-300 text-slate-700 text-sm font-medium rounded-lg hover:bg-slate-50">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-amber-500 text-white text-sm font-medium rounded-lg hover:bg-amber-600">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Script Logika UI Modal -->
<script>
    function toggleModal(modalID) {
        document.getElementById(modalID).classList.toggle("hidden");
    }

    // --- SCRIPT TAMBAH PEGAWAI ---
    function checkJabatan() {
        const select = document.getElementById('jabatanSelect');
        const container = document.getElementById('formAkunContainer');
        const email = document.getElementById('inputEmail');
        const password = document.getElementById('inputPassword');

        // CARA BARU: Ambil teks dari atribut 'data-nama', bukan dari 'value'
        const selectedOption = select.options[select.selectedIndex];
        const val = selectedOption.getAttribute('data-nama') ? selectedOption.getAttribute('data-nama').toLowerCase() : '';

        // Jika jabatan mengandung kata "pengawas" atau "mandor", munculkan form akun
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
@endif

@endsection