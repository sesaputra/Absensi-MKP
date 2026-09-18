@extends('layouts.admin')

@section('title', 'Manajemen Proyek')

@section('content')

<!-- ================= BANNER HALAMAN ================= -->
<div class="relative bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 lg:p-8 overflow-hidden mb-8">
    <div class="absolute inset-0 bg-cover bg-center bg-no-repeat opacity-100 pointer-events-none"
        style="background-image: url('{{ asset('images/flower-mkp.jpg') }}');"></div>
    <div class="absolute inset-0 bg-gradient-to-r from-white/95 via-white/85 to-white/40 pointer-events-none"></div>

    <div class="relative z-10 max-w-2xl">
        <div class="inline-flex items-center space-x-2 bg-slate-100/90 border border-slate-200/80 px-3 py-1 rounded-full text-xs font-semibold text-slate-600 mb-3 backdrop-blur-sm">
            <span class="w-2 h-2 rounded-full bg-amber-400"></span>
            <span>Portofolio & Monitoring Konstruksi</span>
        </div>
        <h1 class="text-2xl lg:text-3xl font-bold text-slate-900 tracking-tight">Manajemen Proyek Pembangunan</h1>
        <p class="mt-2 text-slate-600 text-sm leading-relaxed">
            Kelola seluruh direktori proyek, pantau status pengerjaan, alokasi anggaran, dan informasi klien dalam satu panel terintegrasi.
        </p>
    </div>
</div>

<!-- ================= BAGIAN KONTEN: DAFTAR PROYEK ================= -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 lg:p-8">

    <!-- Header, Toolbar, & Tombol Tambah -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-8 pb-6 border-b border-slate-100">
        <div>
            <h2 class="text-xl font-bold text-slate-800 tracking-tight">Daftar Proyek Aktif</h2>
            <p class="text-xs text-slate-500 mt-1">Pilih kartu proyek untuk mengelola tim, memvalidasi absensi, dan memantau progres lapangan.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'super_admin']))
            <button onclick="toggleModal('modalTambahProyek')" 
                class="inline-flex items-center justify-center px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl transition-all duration-200 shadow-sm hover:shadow-md hover:-translate-y-0.5 active:translate-y-0 shrink-0 gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Tambah Proyek Baru</span>
            </button>
            @endif
        </div>
    </div>

    <!-- Grid Card Proyek -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 lg:gap-8">

        @forelse ($proyeks as $proyek)
        @php
            $status = $proyek->status;
            $badgeClass = '';
            $dotClass = '';

            if($status == 'Berjalan') {
                $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200/80';
                $dotClass = 'bg-emerald-500 animate-pulse';
            } elseif($status == 'Akan Dimulai') {
                $badgeClass = 'bg-sky-50 text-sky-700 border-sky-200/80';
                $dotClass = 'bg-sky-500';
            } elseif($status == 'Ditunda') {
                $badgeClass = 'bg-amber-50 text-amber-700 border-amber-200/80';
                $dotClass = 'bg-amber-500';
            } else {
                $badgeClass = 'bg-slate-100 text-slate-700 border-slate-200';
                $dotClass = 'bg-slate-400';
            }
        @endphp

        <!-- KARTU PROYEK -->
        <div class="group bg-white border border-slate-200/90 rounded-2xl overflow-hidden hover:shadow-xl hover:border-slate-300 transition-all duration-300 flex flex-col relative">
            <a href="{{ route('proyek.show', $proyek->id) }}" class="flex-1 flex flex-col">

                <!-- AREA GAMBAR PROYEK -->
                <div class="h-48 relative overflow-hidden bg-slate-100">
                    @if($proyek->gambar)
                        <img src="{{ asset('storage/' . $proyek->gambar) }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out" 
                             alt="{{ $proyek->nama_proyek }}">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent opacity-60 group-hover:opacity-40 transition-opacity"></div>
                    @else
                        <div class="absolute inset-0 bg-slate-100/80 flex flex-col items-center justify-center text-slate-400 group-hover:bg-slate-100 transition-colors">
                            <svg class="w-10 h-10 mb-1 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-[11px] font-semibold">Foto Belum Tersedia</span>
                        </div>
                    @endif

                    <!-- Floating Status Badge (Pojok Kiri Atas Gambar) -->
                    <div class="absolute top-3 left-3 z-10">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wide uppercase border backdrop-blur-md shadow-2xs {{ $badgeClass }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $dotClass }} mr-1.5"></span> 
                            {{ $proyek->status }}
                        </span>
                    </div>

                    <!-- Tanggal Mulai (Pojok Kanan Bawah Gambar) -->
                    <div class="absolute bottom-3 right-3 z-10 bg-slate-900/70 backdrop-blur-md px-2.5 py-1 rounded-lg text-[10px] font-medium text-white/90">
                        Mulai: {{ \Carbon\Carbon::parse($proyek->tanggal_mulai)->format('d M Y') }}
                    </div>
                </div>

                <!-- DETAIL KONTEN PROYEK -->
                <div class="p-5 flex-1 flex flex-col justify-between">
                    <div>
                        <!-- Judul Proyek -->
                        <h3 class="text-base font-bold text-slate-800 group-hover:text-amber-600 transition-colors line-clamp-1 mb-1">
                            {{ $proyek->nama_proyek }}
                        </h3>

                        <!-- Lokasi Proyek -->
                        <p class="text-xs text-slate-500 mb-4 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="line-clamp-1 font-medium">{{ $proyek->lokasi }}</span>
                        </p>

                        <!-- Box Informasi Klien -->
                        @if($proyek->nama_pemilik)
                        <div class="bg-slate-50/80 rounded-xl p-3 border border-slate-100 mb-4 text-xs">
                            <div class="flex items-center justify-between mb-0.5">
                                <span class="text-slate-400 text-[10px] font-bold uppercase tracking-wider">Pemilik / Klien</span>
                                @if($proyek->kontak_pemilik)
                                    <span class="text-[11px] font-semibold text-slate-600">{{ $proyek->kontak_pemilik }}</span>
                                @endif
                            </div>
                            <p class="text-slate-800 font-bold text-xs truncate">{{ $proyek->nama_pemilik }}</p>
                        </div>
                        @endif
                    </div>

                    <!-- Anggaran Proyek & Action Link -->
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                        <div>
                            <span class="block text-[10px] text-slate-400 uppercase font-bold tracking-wider">Nilai Anggaran</span>
                            <span class="text-sm font-extrabold text-emerald-600">
                                Rp {{ number_format($proyek->anggaran, 0, ',', '.') }}
                            </span>
                        </div>
                        
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-100 group-hover:bg-amber-500 group-hover:text-white text-slate-500 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </span>
                    </div>

                </div>
            </a>
        </div>

        @empty
        <!-- STATE KOSONG -->
        <div class="col-span-1 md:col-span-2 xl:col-span-3 text-center py-16 px-4 rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50/50">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-white shadow-xs border border-slate-200 mb-3">
                <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <h3 class="text-base font-bold text-slate-800">Belum Ada Proyek Terdaftar</h3>
            <p class="text-slate-500 text-xs max-w-sm mx-auto mt-1 mb-4">Tambahkan proyek baru untuk mulai membagikan tugas pekerja dan mencatat laporan bulanan.</p>
            @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'super_admin']))
            <button onclick="toggleModal('modalTambahProyek')" class="inline-flex items-center px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl transition-all shadow-xs">
                + Tambah Proyek Pertama
            </button>
            @endif
        </div>
        @endforelse

    </div>
</div>

<!-- ================= MODAL TAMBAH PROYEK ================= -->
@if(auth()->check() && in_array(auth()->user()->role, ['admin', 'super_admin']))

<div id="modalTambahProyek" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-xs p-4 sm:p-6 md:p-20">
    <div class="relative mx-auto w-full max-w-2xl bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden my-auto">
        
        <!-- Modal Header -->
        <div class="flex justify-between items-center px-6 py-4 border-b border-slate-100 bg-slate-50/50">
            <div>
                <h3 class="text-base font-bold text-slate-800">Tambah Proyek Konstruksi Baru</h3>
                <p class="text-xs text-slate-500">Lengkapi formulir di bawah ini untuk membuat master data proyek.</p>
            </div>
            <button type="button" onclick="toggleModal('modalTambahProyek')" class="text-slate-400 hover:text-slate-600 hover:bg-slate-200/60 p-1.5 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Form Modal -->
        <form action="{{ route('proyek.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf

            <!-- Validation Errors -->
            @if ($errors->any())
            <div class="bg-rose-50 border border-rose-200 text-rose-700 p-3 rounded-xl text-xs space-y-1">
                @foreach ($errors->all() as $error)
                    <p class="flex items-center gap-1.5">
                        <span class="w-1 h-1 rounded-full bg-rose-500"></span>
                        {{ $error }}
                    </p>
                @endforeach
            </div>
            @endif

            <!-- UPLOAD GAMBAR PROYEK WITH LIVE PREVIEW -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Foto Rencana Proyek <span class="text-slate-400 font-normal">(Opsional)</span></label>
                <div class="relative border-2 border-dashed border-slate-200 hover:border-amber-400 rounded-xl bg-slate-50/60 p-4 transition-colors cursor-pointer text-center group" 
                     onclick="document.getElementById('file-upload').click()">
                    
                    <input id="file-upload" name="gambar" type="file" class="hidden" accept="image/png, image/jpeg, image/jpg" onchange="previewImage(this)">
                    
                    <!-- Preview Container -->
                    <div id="preview-container" class="hidden mb-2">
                        <img id="img-preview" class="mx-auto h-32 w-full object-cover rounded-lg border border-slate-200 shadow-2xs" src="" alt="Preview">
                    </div>

                    <!-- Placeholder Default -->
                    <div id="placeholder-container" class="space-y-1">
                        <svg class="mx-auto h-8 w-8 text-slate-400 group-hover:text-amber-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-xs font-semibold text-slate-700">Klik untuk unggah foto proyek</p>
                        <p class="text-[10px] text-slate-400" id="file-name">PNG, JPG hingga 2MB</p>
                    </div>
                </div>
            </div>

            <!-- Nama Proyek & Lokasi -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Proyek <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_proyek" placeholder="Contoh: Villa Canggu Resort" required 
                        class="w-full rounded-xl border border-slate-200 py-2 px-3 text-xs text-slate-800 placeholder-slate-400 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Lokasi Lengkap <span class="text-rose-500">*</span></label>
                    <input type="text" name="lokasi" placeholder="Contoh: Jl. Batu Bolong No. 8" required 
                        class="w-full rounded-xl border border-slate-200 py-2 px-3 text-xs text-slate-800 placeholder-slate-400 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition-all">
                </div>
            </div>

            <!-- Klien & Telepon -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Pemilik / Klien</label>
                    <input type="text" name="nama_pemilik" placeholder="Contoh: Bapak Made Kencana" 
                        class="w-full rounded-xl border border-slate-200 py-2 px-3 text-xs text-slate-800 placeholder-slate-400 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">No. WhatsApp / Telp Klien</label>
                    <input type="text" name="kontak_pemilik" placeholder="Contoh: 08123456789" 
                        class="w-full rounded-xl border border-slate-200 py-2 px-3 text-xs text-slate-800 placeholder-slate-400 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition-all">
                </div>
            </div>

            <!-- Anggaran Proyek -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Anggaran / Total Nilai Proyek <span class="text-rose-500">*</span></label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-xs font-bold text-slate-400">Rp</span>
                    <input type="number" name="anggaran" placeholder="150000000" min="0" required 
                        class="w-full rounded-xl border border-slate-200 py-2 pl-9 pr-3 text-xs text-slate-800 font-semibold placeholder-slate-400 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition-all">
                </div>
            </div>

            <!-- Tanggal & Status -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Mulai <span class="text-rose-500">*</span></label>
                    <input type="date" name="tanggal_mulai" required 
                        class="w-full rounded-xl border border-slate-200 py-2 px-3 text-xs text-slate-800 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Estimasi Selesai</label>
                    <input type="date" name="estimasi_selesai" 
                        class="w-full rounded-xl border border-slate-200 py-2 px-3 text-xs text-slate-800 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Status Awal <span class="text-rose-500">*</span></label>
                    <select name="status" required class="w-full rounded-xl border border-slate-200 py-2 px-3 text-xs text-slate-800 bg-white focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition-all">
                        <option value="Akan Dimulai">Akan Dimulai</option>
                        <option value="Berjalan" selected>Berjalan</option>
                        <option value="Ditunda">Ditunda</option>
                        <option value="Selesai">Selesai</option>
                    </select>
                </div>
            </div>

            <!-- Footer Modal Buttons -->
            <div class="flex items-center justify-end space-x-2 pt-4 border-t border-slate-100">
                <button type="button" onclick="toggleModal('modalTambahProyek')" class="px-4 py-2 bg-white border border-slate-200 text-slate-600 text-xs font-bold rounded-xl hover:bg-slate-50 transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl transition-all shadow-xs">
                    Simpan Proyek
                </button>
            </div>
        </form>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Live Image Preview Function
    function previewImage(input) {
        const previewContainer = document.getElementById('preview-container');
        const imgPreview = document.getElementById('img-preview');
        const fileNameText = document.getElementById('file-name');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                imgPreview.src = e.target.result;
                previewContainer.classList.remove('hidden');
            }

            reader.readAsDataURL(input.files[0]);
            fileNameText.innerText = "Terpilih: " + input.files[0].name;
            fileNameText.classList.add('text-amber-600', 'font-bold');
        }
    }

    // Toggle Modal Function
    function toggleModal(modalID) {
        const modal = document.getElementById(modalID);
        modal.classList.toggle("hidden");
        modal.classList.toggle("flex");
    }

    // SweetAlert Success Notification
    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: "{{ session('success') }}",
        confirmButtonColor: '#0f172a',
        timer: 2500
    });
    @endif
</script>

@endif

@endsection