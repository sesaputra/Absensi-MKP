@extends('layouts.mobile')

@section('title', 'Absensi Lapangan (Pagi)')

@section('content')
<div class="max-w-md md:max-w-3xl lg:max-w-4xl mx-auto bg-[#f8fafc] min-h-screen pb-24 shadow-2xl border-x border-slate-200">

    <!-- HEADER PAGI (Sama dengan Sore: Tema #0c2340) -->
    <div class="bg-[#0c2340] text-white p-6 rounded-b-3xl shadow-lg relative overflow-hidden">
        <div class="absolute -right-10 -top-10 w-32 h-32 bg-white/5 rounded-full blur-2xl"></div>
        <div class="flex items-center justify-between mb-4 relative z-10">
            <h1 class="text-xl font-bold tracking-tight">Form Absen Pagi</h1>
            <a href="{{ route('pengawas.dashboard') }}" class="text-slate-300 hover:text-white transition-colors bg-white/10 p-1.5 rounded-full backdrop-blur-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </a>
        </div>
        <div class="relative z-10 border-t border-white/10 pt-4">
            <p class="text-slate-300 text-sm font-medium">{{ $proyek->nama_proyek }}</p>
            <p class="text-amber-400 text-xs mt-1.5 font-bold uppercase tracking-wider flex items-center">
                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                {{ now()->translatedFormat('l, d M Y') }} - PAGI
            </p>
        </div>
    </div>

    @if(session('error'))
    <div class="mx-4 mt-5 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl flex items-start shadow-sm">
        <svg class="w-5 h-5 mr-2 shrink-0 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span class="text-[13px] font-bold">{{ session('error') }}</span>
    </div>
    @endif

    <!-- FORM UTAMA -->
    <form id="formAbsenPagi" action="{{ route('absensi.store', $proyek->id) }}" method="POST" enctype="multipart/form-data" class="p-4 mt-2">
        @csrf

        <!-- INPUT TERSEMBUNYI UNTUK LOKASI & WAKTU -->
        <input type="hidden" name="latitude" id="inputLatitude" required>
        <input type="hidden" name="longitude" id="inputLongitude" required>
        <input type="hidden" name="waktu_absen" id="inputWaktu" required>

        <!-- KARTU 1: KAMERA BUKTI LAPANGAN -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 mb-5 group hover:border-[#0c2340]/30 transition-colors border-l-4 border-l-[#0c2340]">
            <div class="flex items-center mb-3">
                <div class="p-1.5 bg-slate-50 text-[#0c2340] rounded-lg mr-2 border border-slate-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <h2 class="text-sm font-bold text-slate-800">Foto Briefing Pagi</h2>
            </div>
            <p class="text-[11px] text-slate-500 mb-3 leading-relaxed">Ambil foto kondisi proyek atau saat briefing tukang pagi ini. Kamera akan terbuka otomatis.</p>

            <!-- Desain Input File Seragam dengan Sore -->
            <input type="file" name="foto_pagi" id="fotoPagiInput" accept="image/*" capture="environment" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#0c2340]/5 file:text-[#0c2340] hover:file:bg-[#0c2340]/10 transition-colors border border-slate-100 rounded-xl" required>

            <div class="mt-4 p-3 bg-slate-50 rounded-xl border border-slate-100 shadow-inner">
                <div class="flex items-center mb-1.5">
                    <svg class="w-3 h-3 mr-1 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <p id="lokasiTeks" class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Mencari lokasi GPS...</p>
                </div>
                <div class="flex items-center">
                    <svg class="w-3 h-3 mr-1 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p id="waktuAmbilTeks" class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Waktu jepret: Menunggu</p>
                </div>
            </div>
        </div>

        <div class="flex items-center mb-4 px-1">
            <div class="h-px bg-slate-200 flex-1"></div>
            <span class="px-3 text-[10px] font-bold text-[#0c2340] uppercase tracking-widest">Tim Lapangan ({{ $proyek->pegawais->count() }})</span>
            <div class="h-px bg-slate-200 flex-1"></div>
        </div>

        <!-- LOOPING DATA PEKERJA (Desain disamakan dengan Sore) -->
        @forelse($proyek->pegawais as $pekerja)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 mb-4">
            <div class="mb-4 border-b border-slate-100 pb-3">
                <h3 class="font-bold text-slate-800 text-sm">{{ $pekerja->nama }}</h3>
                <p class="text-[10px] text-slate-500 font-medium uppercase tracking-wider mt-0.5">{{ $pekerja->jabatan->nama_jabatan }}</p>
            </div>

            <!-- Opsi Status (Desain Minimalis Seragam) -->
            <div class="grid grid-cols-4 gap-2 mb-3">
                <label class="cursor-pointer">
                    <input type="radio" name="absensi[{{ $pekerja->id }}][status]" value="Hadir" class="peer sr-only" checked>
                    <div class="text-center py-2 rounded-lg border border-slate-200 bg-slate-50 peer-checked:bg-emerald-500 peer-checked:text-white peer-checked:border-emerald-500 transition-all text-[11px] font-bold text-slate-500">Hadir</div>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="absensi[{{ $pekerja->id }}][status]" value="Sakit" class="peer sr-only">
                    <div class="text-center py-2 rounded-lg border border-slate-200 bg-slate-50 peer-checked:bg-amber-500 peer-checked:text-white peer-checked:border-amber-500 transition-all text-[11px] font-bold text-slate-500">Sakit</div>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="absensi[{{ $pekerja->id }}][status]" value="Izin" class="peer sr-only">
                    <div class="text-center py-2 rounded-lg border border-slate-200 bg-slate-50 peer-checked:bg-blue-500 peer-checked:text-white peer-checked:border-blue-500 transition-all text-[11px] font-bold text-slate-500">Izin</div>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="absensi[{{ $pekerja->id }}][status]" value="Alfa" class="peer sr-only">
                    <div class="text-center py-2 rounded-lg border border-slate-200 bg-slate-50 peer-checked:bg-rose-500 peer-checked:text-white peer-checked:border-rose-500 transition-all text-[11px] font-bold text-slate-500">Alfa</div>
                </label>
            </div>

            <!-- Input Keterangan Seragam -->
            <input type="text" name="absensi[{{ $pekerja->id }}][keterangan]" placeholder="Keterangan (Wajib jika Sakit/Izin)..." class="mt-2 w-full text-xs border-slate-200 rounded-lg focus:ring-[#0c2340] focus:border-[#0c2340] bg-slate-50 py-2.5 placeholder:text-slate-400">
        </div>
        @empty
        <div class="bg-white rounded-2xl border border-dashed border-slate-300 p-8 text-center mt-2 shadow-sm">
            <p class="text-sm font-bold text-slate-700">Tidak Ada Pekerja</p>
            <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">Belum ada pekerja yang didaftarkan ke proyek ini.</p>
        </div>
        @endforelse

        @if($proyek->pegawais->count() > 0)
        <!-- TOMBOL SUBMIT MELAYANG (Tema #0c2340) -->
        <div class="fixed bottom-0 left-0 right-0 p-4 bg-white/80 backdrop-blur-lg border-t border-slate-200 z-50 flex justify-center">
            <div class="max-w-md w-full">
                <button type="submit" id="btnSubmit" class="w-full bg-[#0c2340] text-white font-bold text-sm py-3.5 rounded-xl shadow-[0_8px_20px_-6px_rgba(12,35,64,0.6)] hover:bg-opacity-90 transition-colors flex items-center justify-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                    Kirim Absensi Pagi
                </button>
            </div>
        </div>
        @endif
    </form>
</div>

<!-- SCRIPT KAMERA & GPS -->
<script>
    // 1. Tangani Submit dengan SweetAlert
    document.getElementById('formAbsenPagi').addEventListener('submit', function(e) {
        e.preventDefault(); // Hentikan submit langsung

        Swal.fire({
            title: 'Kirim Absensi Pagi?',
            text: "Pastikan foto briefing dan lokasi sudah akurat.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0c2340', // Warna navy brand Anda
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Kirim Absensi',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit(); // Lanjutkan proses submit form
            }
        });
    });
    // 1. Dapatkan GPS
    window.onload = function() {
        const lokasiTeks = document.getElementById("lokasiTeks");
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    document.getElementById("inputLatitude").value = position.coords.latitude;
                    document.getElementById("inputLongitude").value = position.coords.longitude;
                    lokasiTeks.innerHTML = `<span class="text-emerald-600">LOKASI TERKUNCI: ${position.coords.latitude.toFixed(5)}, ${position.coords.longitude.toFixed(5)}</span>`;
                },
                function(error) {
                    lokasiTeks.innerHTML = `<span class="text-rose-600">LOKASI DITOLAK. WAJIB DIIZINKAN!</span>`;
                    document.getElementById("btnSubmit").disabled = true;
                    document.getElementById("btnSubmit").classList.add('opacity-50', 'cursor-not-allowed');
                }, {
                    enableHighAccuracy: true
                }
            );
        }
    };

    // 2. Sensor Waktu Jepret Kamera
    document.getElementById('fotoPagiInput').addEventListener('change', function(event) {
        const teksWaktuTampil = document.getElementById('waktuAmbilTeks');
        const inputWaktuHidden = document.getElementById('inputWaktu');

        if (this.files && this.files.length > 0) {
            const waktuJepret = new Date();
            const tahun = waktuJepret.getFullYear();
            const bulan = String(waktuJepret.getMonth() + 1).padStart(2, '0');
            const hari = String(waktuJepret.getDate()).padStart(2, '0');
            const jam = String(waktuJepret.getHours()).padStart(2, '0');
            const menit = String(waktuJepret.getMinutes()).padStart(2, '0');
            const detik = String(waktuJepret.getSeconds()).padStart(2, '0');

            inputWaktuHidden.value = `${tahun}-${bulan}-${hari} ${jam}:${menit}:${detik}`;
            teksWaktuTampil.innerHTML = `<span class="text-emerald-600">DIFOTO PADA: ${jam}:${menit}:${detik} WITA</span>`;
        } else {
            inputWaktuHidden.value = "";
            teksWaktuTampil.innerHTML = "WAKTU JEPRET: MENUNGGU FOTO";
        }
    });

    // 4. NOTIFIKASI SUCCESS/ERROR (Sama seperti sebelumnya)
    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: "{{ session('success') }}",
        confirmButtonColor: '#0c2340',
        timer: 3000
    });
    @endif

    @if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: "{{ session('error') }}",
        confirmButtonColor: '#d33'
    });
    @endif
</script>
@endsection