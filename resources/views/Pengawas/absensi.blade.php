@extends('layouts.mobile')

@section('title', 'Absensi Lapangan & Log Pekerjaan')

@section('content')
<div class="max-w-md mx-auto bg-slate-50 min-h-screen pb-24 shadow-xl border-x border-slate-100">

    <!-- HEADER PAGI -->
    <div class="bg-slate-800 text-white p-6 rounded-b-3xl shadow-md relative overflow-hidden">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
        <div class="flex items-center justify-between mb-3 relative z-10">
            <h1 class="text-xl font-bold">Form Absen Pagi</h1>
            <a href="{{ route('pengawas.dashboard') }}" class="text-slate-300 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </a>
        </div>
        <div class="relative z-10">
            <p class="text-slate-300 text-sm font-medium">{{ $proyek->nama_proyek }}</p>
            <p class="text-amber-400 text-sm mt-1 font-bold flex items-center">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                {{ now()->translatedFormat('l, d F Y') }}
            </p>
        </div>
    </div>

    @if(session('error'))
    <div class="m-4 bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-xl flex items-start shadow-sm">
        <span class="text-sm font-medium">{{ session('error') }}</span>
    </div>
    @endif

    <!-- FORM UTAMA (Ditambah enctype untuk Upload) -->
    <form action="{{ route('absensi.store', $proyek->id) }}" method="POST" enctype="multipart/form-data" class="p-4 mt-2" onsubmit="return confirm('Kirim Absensi Pagi? Pastikan foto dan lokasi sudah benar.');">
        @csrf

        <!-- INPUT TERSEMBUNYI UNTUK LOKASI & WAKTU -->
        <input type="hidden" name="latitude" id="inputLatitude" required>
        <input type="hidden" name="longitude" id="inputLongitude" required>
        <input type="hidden" name="waktu_absen" id="inputWaktu" required>

        <!-- KARTU 1: KAMERA BUKTI LAPANGAN -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4 mb-5">
            <h2 class="text-sm font-bold text-slate-800 mb-2 flex items-center">
                <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Foto Briefing Pagi
            </h2>
            <p class="text-[11px] text-slate-500 mb-3 leading-tight">Ambil foto kondisi proyek atau saat briefing tukang pagi ini. Kamera akan terbuka otomatis.</p>

            <input type="file" name="foto_pagi" id="fotoPagiInput" accept="image/*" capture="environment" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>

            <div class="mt-3 p-2 bg-slate-50 rounded-lg border border-slate-100">
                <p id="lokasiTeks" class="text-[10px] text-slate-500 font-medium">📍 Mencari lokasi GPS...</p>
                <p id="waktuAmbilTeks" class="text-[10px] text-slate-500 font-medium mt-1">⏱️ Waktu jepret: Belum ada foto</p>
            </div>
        </div>

        <div class="flex justify-between items-end mb-4 px-1">
            <h2 class="text-sm font-bold text-slate-500 uppercase tracking-wider">Tim Lapangan</h2>
            <span class="text-xs font-bold text-slate-700 bg-slate-200 px-2.5 py-1 rounded-full">Total: {{ $proyek->pegawais->count() }} Pekerja</span>
        </div>

        <!-- LOOPING DATA PEKERJA (TIDAK BERUBAH) -->
        @forelse($proyek->pegawais as $pekerja)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4 mb-4">
            <div class="mb-4">
                <h3 class="font-bold text-slate-800 text-base">{{ $pekerja->nama }}</h3>
                <p class="text-xs text-slate-500 font-medium mt-0.5">{{ $pekerja->jabatan->nama_jabatan }}</p>
            </div>

            <div class="grid grid-cols-4 gap-2">
                <!-- Hadir -->
                <label class="cursor-pointer">
                    <input type="radio" name="absensi[{{ $pekerja->id }}][status]" value="Hadir" class="peer sr-only" checked>
                    <div class="text-center py-2.5 rounded-xl border-2 border-slate-100 bg-slate-50 peer-checked:bg-green-500 peer-checked:text-white peer-checked:border-green-500 transition-all text-[11px] font-bold text-slate-500 peer-checked:shadow-md">Hadir</div>
                </label>
                <!-- Sakit -->
                <label class="cursor-pointer">
                    <input type="radio" name="absensi[{{ $pekerja->id }}][status]" value="Sakit" class="peer sr-only">
                    <div class="text-center py-2.5 rounded-xl border-2 border-slate-100 bg-slate-50 peer-checked:bg-amber-500 peer-checked:text-white peer-checked:border-amber-500 transition-all text-[11px] font-bold text-slate-500 peer-checked:shadow-md">Sakit</div>
                </label>
                <!-- Izin -->
                <label class="cursor-pointer">
                    <input type="radio" name="absensi[{{ $pekerja->id }}][status]" value="Izin" class="peer sr-only">
                    <div class="text-center py-2.5 rounded-xl border-2 border-slate-100 bg-slate-50 peer-checked:bg-blue-500 peer-checked:text-white peer-checked:border-blue-500 transition-all text-[11px] font-bold text-slate-500 peer-checked:shadow-md">Izin</div>
                </label>
                <!-- Alfa -->
                <label class="cursor-pointer">
                    <input type="radio" name="absensi[{{ $pekerja->id }}][status]" value="Alfa" class="peer sr-only">
                    <div class="text-center py-2.5 rounded-xl border-2 border-slate-100 bg-slate-50 peer-checked:bg-red-500 peer-checked:text-white peer-checked:border-red-500 transition-all text-[11px] font-bold text-slate-500 peer-checked:shadow-md">Alfa</div>
                </label>
            </div>
            <input type="text" name="absensi[{{ $pekerja->id }}][keterangan]" placeholder="Keterangan (Wajib jika Sakit/Izin)..." class="mt-3 w-full text-sm border-slate-200 rounded-lg focus:ring-amber-500 focus:border-amber-500 bg-slate-50 py-2">
        </div>
        @empty
        <div class="text-center py-10 bg-white rounded-2xl border border-dashed border-slate-300">
            <p class="text-slate-500 text-sm font-medium px-4">Belum ada pekerja di proyek ini.</p>
        </div>
        @endforelse

        @if($proyek->pegawais->count() > 0)
        <div class="fixed bottom-0 left-0 right-0 p-4 bg-white/90 backdrop-blur-md border-t border-slate-200 z-50 flex justify-center shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
            <div class="max-w-md w-full">
                <button type="submit" id="btnSubmit" class="w-full bg-slate-800 text-white font-bold text-sm py-3.5 rounded-xl shadow-lg hover:bg-[#0c2340] transition-colors flex justify-center items-center">
                    Kirim Absensi Pagi
                </button>
            </div>
        </div>
        @endif
    </form>
</div>

<!-- SCRIPT KAMERA & GPS -->
<script>
    // 1. Dapatkan GPS
    window.onload = function() {
        const lokasiTeks = document.getElementById("lokasiTeks");
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    document.getElementById("inputLatitude").value = position.coords.latitude;
                    document.getElementById("inputLongitude").value = position.coords.longitude;
                    lokasiTeks.innerHTML = `<span class="text-green-600">📍 Lokasi Terkunci: ${position.coords.latitude.toFixed(5)}, ${position.coords.longitude.toFixed(5)}</span>`;
                },
                function(error) {
                    lokasiTeks.innerHTML = `<span class="text-red-600">❌ Akses lokasi ditolak/gagal. Wajib diizinkan!</span>`;
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
            teksWaktuTampil.innerHTML = `<span class="text-green-600">⏱️ Difoto pada: ${jam}:${menit}:${detik} WITA</span>`;
        } else {
            inputWaktuHidden.value = "";
            teksWaktuTampil.innerHTML = "⏱️ Waktu jepret: Belum ada foto";
        }
    });
</script>
@endsection