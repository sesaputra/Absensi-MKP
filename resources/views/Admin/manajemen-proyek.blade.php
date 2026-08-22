@extends('layouts.admin')

@section('title', 'Manajemen Proyek')

@section('content')

<!-- BANNER HALAMAN -->
<div class="relative bg-white rounded-xl shadow-sm border border-slate-100 p-6 lg:p-8 overflow-hidden mb-8">
    <!-- Layer Background Pattern -->
    <div class="absolute inset-0 bg-cover bg-center bg-no-repeat opacity-20"
        style="background-image: url('{{ asset('images/flower-mkp.jpg') }}');">
    </div>

    <!-- Teks Konten -->
    <div class="relative z-10">
        <h1 class="text-2xl font-bold text-slate-800">Halaman Manajemen Proyek</h1>
        <p class="mt-2 text-slate-600 max-w-2xl">
            Kelola seluruh data proyek pembangunan beserta informasi pemilik, alokasi anggaran, dan status pengerjaannya. Tambahkan proyek baru sebelum menugaskan pekerja ke dalamnya.
        </p>
    </div>
</div>

<!-- BAGIAN KONTEN: DAFTAR PROYEK -->
<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 lg:p-8">

    <!-- Header & Tombol Tambah -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800 border-b-2 border-amber-500 inline-block pb-1">Daftar Proyek Anda</h2>
            <p class="text-sm text-slate-500 mt-2 max-w-xl">
                Klik kartu proyek untuk melihat detail penugasan pekerja, laporan absensi, dan progres pembangunan.
            </p>
        </div>

        @if(auth()->check() && auth()->user()->role === 'admin')
        <button onclick="toggleModal('modalTambahProyek')" class="inline-flex items-center px-4 py-2.5 bg-slate-800 hover:bg-[#0c2340] text-white text-sm font-medium rounded-lg transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5 shrink-0">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Tambah Proyek Baru
        </button>
        @endif
    </div>

    <!-- Grid Card Proyek -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6 lg:gap-8">

        <!-- LOOPING DATA PROYEK -->
        @forelse ($proyeks as $proyek)

        <!-- KARTU PROYEK (Bungkus Utama) -->
        <div class="relative bg-white border border-slate-200 rounded-2xl overflow-hidden hover:shadow-xl transition-shadow duration-300 group flex flex-col">

            <a href="{{ route('proyek.show', $proyek->id) }}" class="flex-1 flex flex-col cursor-pointer">

                <!-- AREA GAMBAR PROYEK (Diperbarui) -->
                <div class="h-48 relative overflow-hidden bg-slate-100 flex items-center justify-center">
                    @if($proyek->gambar)
                    <!-- Tampilkan Gambar jika ada -->
                    <img src="{{ asset('storage/' . $proyek->gambar) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-in-out" alt="{{ $proyek->nama_proyek }}">
                    <!-- Overlay gradient agar teks/badge di atasnya tetap terbaca -->
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    @else
                    <!-- Placeholder jika gambar kosong -->
                    <div class="absolute inset-0 bg-slate-100 flex flex-col items-center justify-center text-slate-300 group-hover:bg-slate-200 transition-colors">
                        <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-xs font-medium">Belum ada foto</span>
                    </div>
                    @endif
                </div>

                <!-- Detail Proyek -->
                <div class="p-6 flex-1 flex flex-col text-left">
                    <!-- Logika Warna Badge Status -->
                    @php
                    $status = $proyek->status;
                    $badgeClass = '';
                    $dotClass = '';

                    if($status == 'Berjalan') {
                    $badgeClass = 'bg-green-100 text-green-700';
                    $dotClass = 'bg-green-600 animate-pulse';
                    } elseif($status == 'Akan Dimulai') {
                    $badgeClass = 'bg-blue-100 text-blue-700';
                    $dotClass = 'bg-blue-600';
                    } elseif($status == 'Ditunda') {
                    $badgeClass = 'bg-amber-100 text-amber-700';
                    $dotClass = 'bg-amber-600';
                    } else {
                    $badgeClass = 'bg-slate-100 text-slate-700';
                    $dotClass = 'bg-slate-500';
                    }
                    @endphp

                    <div class="flex justify-between items-start mb-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide {{ $badgeClass }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $dotClass }} mr-1.5"></span> {{ $proyek->status }}
                        </span>
                        <p class="text-xs text-slate-400 font-medium">
                            Mulai: {{ \Carbon\Carbon::parse($proyek->tanggal_mulai)->format('d/m/Y') }}
                        </p>
                    </div>

                    <!-- Judul Proyek -->
                    <h3 class="text-lg font-bold text-slate-800 group-hover:text-amber-600 transition-colors mb-1 line-clamp-1">
                        {{ $proyek->nama_proyek }}
                    </h3>

                    <!-- Lokasi Proyek -->
                    <p class="text-sm text-slate-500 mb-4 flex items-center">
                        <svg class="w-4 h-4 mr-1 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="line-clamp-1">{{ $proyek->lokasi }}</span>
                    </p>

                    <!-- Informasi Pemilik Proyek -->
                    @if($proyek->nama_pemilik)
                    <div class="bg-slate-50 p-2.5 rounded-lg border border-slate-100 mb-4 text-xs">
                        <p class="text-slate-500 font-medium">Klien: <span class="text-slate-800 font-bold">{{ $proyek->nama_pemilik }}</span></p>
                        @if($proyek->kontak_pemilik)
                        <p class="text-slate-500 mt-0.5">Kontak: <span class="text-slate-700 font-semibold">{{ $proyek->kontak_pemilik }}</span></p>
                        @endif
                    </div>
                    @endif

                    <!-- Anggaran Proyek -->
                    <div class="mt-auto pt-4 border-t border-slate-100 w-full flex justify-between items-center">
                        <span class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Nilai Proyek</span>
                        <span class="text-sm font-bold text-green-600">
                            Rp. {{ number_format($proyek->anggaran, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </a>
        </div>

        @empty
        <!-- State Kosong jika belum ada data -->
        <div class="col-span-1 sm:col-span-2 xl:col-span-3 text-center py-12 px-4 rounded-xl border-2 border-dashed border-slate-200">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 mb-4">
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-1">Belum ada proyek</h3>
            <p class="text-slate-500 text-sm max-w-sm mx-auto mb-4">Anda belum menambahkan data proyek apa pun. Klik tombol "Tambah Proyek Baru" untuk memulai.</p>
        </div>
        @endforelse

    </div>
</div>

<!-- AREA MODAL & SCRIPT (Hanya Untuk Admin) -->
@if(auth()->check() && auth()->user()->role === 'admin')

<!-- MODAL TAMBAH PROYEK -->
<div id="modalTambahProyek" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm overflow-y-auto w-full h-full">
    <div class="relative top-10 mx-auto p-5 w-full max-w-xl shadow-lg rounded-2xl bg-white mb-20">
        <div class="mt-2">
            <div class="flex justify-between items-center mb-6 border-b border-slate-100 pb-4">
                <h3 class="text-lg leading-6 font-bold text-slate-800">Tambah Proyek Baru</h3>
                <button type="button" onclick="toggleModal('modalTambahProyek')" class="text-slate-400 hover:text-red-500 transition-colors p-1 bg-slate-50 hover:bg-red-50 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Pesan Error Validasi -->
            @if ($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
            @endif

            <!-- PERHATIKAN: enctype="multipart/form-data" DITAMBAHKAN -->
            <form action="{{ route('proyek.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- UPLOAD GAMBAR PROYEK -->
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Gambar / Foto Rencana Proyek <span class="text-xs font-normal text-slate-400">(Opsional)</span></label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-xl bg-slate-50 hover:bg-slate-100 transition-colors cursor-pointer" onclick="document.getElementById('file-upload').click()">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-slate-600 justify-center">
                                <label for="file-upload" class="relative cursor-pointer rounded-md font-medium text-amber-600 hover:text-amber-500">
                                    <span>Pilih Gambar</span>
                                    <input id="file-upload" name="gambar" type="file" class="sr-only" accept="image/png, image/jpeg, image/jpg" onchange="previewText(this)">
                                </label>
                            </div>
                            <p class="text-xs text-slate-500" id="file-name">PNG, JPG up to 2MB</p>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Proyek *</label>
                    <input type="text" name="nama_proyek" placeholder="Contoh: Pembangunan Villa Mr. Adam" required class="w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm text-slate-900 focus:ring-amber-500 focus:border-amber-500 outline-none ring-1 ring-inset ring-slate-300">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Lokasi Lengkap *</label>
                    <input type="text" name="lokasi" placeholder="Contoh: Jl. Pantai Batu Bolong, Canggu" required class="w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm text-slate-900 focus:ring-amber-500 focus:border-amber-500 outline-none ring-1 ring-inset ring-slate-300">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Pemilik / Klien</label>
                        <input type="text" name="nama_pemilik" placeholder="Contoh: Bp. Made" class="w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm text-slate-900 focus:ring-amber-500 focus:border-amber-500 outline-none ring-1 ring-inset ring-slate-300">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">No. Telp Klien</label>
                        <input type="text" name="kontak_pemilik" placeholder="Contoh: 08123456789" class="w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm text-slate-900 focus:ring-amber-500 focus:border-amber-500 outline-none ring-1 ring-inset ring-slate-300">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Anggaran / Nilai Proyek (Rp) *</label>
                    <input type="number" name="anggaran" placeholder="Contoh: 150000000" min="0" required class="w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm text-slate-900 focus:ring-amber-500 focus:border-amber-500 outline-none ring-1 ring-inset ring-slate-300">
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Tanggal Mulai *</label>
                        <input type="date" name="tanggal_mulai" required class="w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm text-slate-900 focus:ring-amber-500 focus:border-amber-500 outline-none ring-1 ring-inset ring-slate-300">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Estimasi Selesai <span class="text-xs font-normal text-slate-400">(Opsional)</span></label>
                        <input type="date" name="estimasi_selesai" class="w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm text-slate-900 focus:ring-amber-500 focus:border-amber-500 outline-none ring-1 ring-inset ring-slate-300">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Status Proyek *</label>
                    <select name="status" required class="w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm text-slate-900 focus:ring-amber-500 focus:border-amber-500 outline-none ring-1 ring-inset ring-slate-300 bg-white">
                        <option value="Akan Dimulai">Akan Dimulai (Belum dimulai)</option>
                        <option value="Berjalan" selected>Berjalan (Sedang dikerjakan)</option>
                        <option value="Ditunda">Ditunda (Terhenti sementara)</option>
                        <option value="Selesai">Selesai (Sudah serah terima)</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-3 pt-5 border-t border-slate-100">
                    <button type="button" onclick="toggleModal('modalTambahProyek')" class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 text-sm font-bold rounded-xl hover:bg-slate-50 transition-colors">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-slate-800 text-white text-sm font-bold rounded-xl hover:bg-[#0c2340] transition-colors shadow-md">Simpan Proyek</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Menampilkan nama file yang dipilih saat upload gambar
    function previewText(input) {
        if (input.files && input.files[0]) {
            document.getElementById('file-name').innerText = "File terpilih: " + input.files[0].name;
            document.getElementById('file-name').classList.add('text-amber-600', 'font-bold');
        }
    }

    // FUNGSI TOGGLE MODAL TAMBAH
    function toggleModal(modalID) {
        document.getElementById(modalID).classList.toggle("hidden");
    }

    // FUNGSI SWEETALERT HAPUS
    function konfirmasiHapus(formId, namaData) {
        Swal.fire({
            title: 'Hapus Proyek?',
            text: namaData + " beserta data plotting pegawainya akan ikut terhapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                confirmButton: 'rounded-lg',
                cancelButton: 'rounded-lg'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    }

    // SWEETALERT NOTIFIKASI SUKSES
    @if(session('success'))
    document.addEventListener("DOMContentLoaded", function() {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 2000
        });
    });
    @endif
</script>

@endif

@endsection