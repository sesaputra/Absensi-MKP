@extends('layouts.mobile')

@section('title', 'Laporan Akhir Hari')

@section('content')

<!-- ========================================== -->
<!-- CSS KHUSUS SLIDER (TEMA WARNA #0c2340) -->
<!-- ========================================== -->
<style>
    .slider-progres {
        -webkit-appearance: none;
        appearance: none;
        height: 6px;
        border-radius: 8px;
        background: #e2e8f0;
        outline: none;
        width: 100%;
        margin-top: 10px;
        margin-bottom: 10px;
    }

    .slider-progres::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #ffffff;
        border: 3px solid #0c2340; /* Menggunakan warna brand Anda */
        box-shadow: 0 2px 4px rgba(12, 35, 64, 0.3);
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .slider-progres::-webkit-slider-thumb:active {
        transform: scale(1.15);
        box-shadow: 0 0 0 5px rgba(12, 35, 64, 0.15);
    }

    .slider-progres::-moz-range-thumb {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #ffffff;
        border: 3px solid #0c2340;
        box-shadow: 0 2px 4px rgba(12, 35, 64, 0.3);
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .slider-progres::-moz-range-thumb:active {
        transform: scale(1.15);
        box-shadow: 0 0 0 5px rgba(12, 35, 64, 0.15);
    }
</style>

<div class="max-w-md mx-auto bg-[#f8fafc] min-h-screen pb-24 shadow-2xl border-x border-slate-200">

    <!-- HEADER SORE (Warna Background #0c2340) -->
    <div class="bg-[#0c2340] text-white p-6 rounded-b-3xl shadow-lg relative overflow-hidden">
        <div class="absolute -right-10 -top-10 w-32 h-32 bg-white/5 rounded-full blur-2xl"></div>
        <div class="flex items-center justify-between mb-4 relative z-10">
            <h1 class="text-xl font-bold tracking-tight">Laporan Akhir Hari</h1>
            <a href="{{ route('pengawas.dashboard') }}" class="text-slate-300 hover:text-white transition-colors bg-white/10 p-1.5 rounded-full backdrop-blur-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </a>
        </div>
        <div class="relative z-10 border-t border-white/10 pt-4">
            <p class="text-slate-300 text-sm font-medium">{{ $proyek->nama_proyek }}</p>
            <p class="text-amber-400 text-xs mt-1.5 font-bold uppercase tracking-wider">{{ now()->translatedFormat('l, d M Y') }} - SORE</p>
        </div>
    </div>

    <!-- FORM SORE -->
    <form id="formAbsenSore" action="{{ route('absensi.storeSore', $proyek->id) }}" method="POST" enctype="multipart/form-data" class="p-4 mt-2">
        @csrf

        <!-- KARTU 1: FOTO PROGRES -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 mb-4 group hover:border-[#0c2340]/30 transition-colors border-l-4 border-l-[#0c2340]">
            <div class="flex items-center mb-3">
                <div class="p-1.5 bg-slate-50 text-[#0c2340] rounded-lg mr-2 border border-slate-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <h2 class="text-sm font-bold text-slate-800">Dokumentasi Progres</h2>
            </div>
            <p class="text-[11px] text-slate-500 mb-3 leading-relaxed">Unggah foto hasil pekerjaan fisik yang telah diselesaikan hari ini.</p>
            <input type="file" name="foto_sore" accept="image/*" capture="environment" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#0c2340]/5 file:text-[#0c2340] hover:file:bg-[#0c2340]/10 transition-colors border border-slate-100 rounded-xl" required>
        </div>

        <!-- KARTU 2: UPDATE PROGRES FISIK -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 mb-4 border-l-4 border-l-slate-200 hover:border-l-[#0c2340] transition-colors">
            <div class="flex items-center mb-4 pb-3 border-b border-slate-100">
                <div class="p-1.5 bg-slate-50 text-[#0c2340] rounded-lg mr-2 border border-slate-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
                <h2 class="text-sm font-bold text-slate-800">Progres Pekerjaan Fisik</h2>
            </div>
            
            @forelse($proyek->itemPekerjaans as $item)
            <div class="mb-5 last:mb-0">
                <div class="flex justify-between items-end mb-1">
                    <div class="pr-3">
                        <label class="text-[13px] font-bold text-slate-700">{{ $item->nama_pekerjaan }}</label>
                        <p class="text-[9px] font-medium text-slate-400 mt-0.5 uppercase tracking-wider">Awal: {{ $item->progres_sekarang }}%</p>
                    </div>
                    <div class="bg-slate-50 border border-slate-200 shadow-sm px-2.5 py-1 rounded-md flex items-center justify-center min-w-[50px]">
                        <span class="text-[13px] font-black text-[#0c2340] tabular-nums" id="val_{{ $item->id }}">{{ $item->progres_sekarang }}%</span>
                    </div>
                </div>

                <div class="relative pt-2 pb-1">
                    <input type="range" 
                        name="progres[{{ $item->id }}]" 
                        min="{{ $item->progres_sekarang }}" 
                        max="100" 
                        value="{{ $item->progres_sekarang }}" 
                        class="slider-progres" 
                        id="slider_{{ $item->id }}"
                        oninput="document.getElementById('val_{{ $item->id }}').innerText = this.value + '%'; updateSliderVisual(this);">
                </div>
            </div>
            @empty
            <div class="text-center py-4 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                <p class="text-xs text-slate-500 font-medium">Belum ada item pekerjaan.</p>
            </div>
            @endforelse
        </div>

        <!-- KARTU 3: CATATAN LAPANGAN -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 mb-6 border-l-4 border-l-slate-200 hover:border-l-[#0c2340] transition-colors">
            <div class="flex items-center mb-3">
                <div class="p-1.5 bg-slate-50 text-[#0c2340] rounded-lg mr-2 border border-slate-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </div>
                <h2 class="text-sm font-bold text-slate-800">Jurnal Lapangan</h2>
            </div>
            <textarea name="kegiatan" rows="3" class="w-full text-sm border-slate-200 rounded-xl focus:ring-[#0c2340] focus:border-[#0c2340] bg-slate-50 p-3 placeholder:text-slate-400" placeholder="Ketik ringkasan kegiatan, cuaca hari ini, atau kendala lapangan..." required></textarea>
        </div>

        <div class="flex items-center mb-4 px-1">
            <div class="h-px bg-slate-200 flex-1"></div>
            <span class="px-3 text-[10px] font-bold text-[#0c2340] uppercase tracking-widest">Validasi Kepulangan</span>
            <div class="h-px bg-slate-200 flex-1"></div>
        </div>

        <!-- KARTU 4: ABSENSI PEKERJA -->
        @foreach($proyek->pegawais as $pekerja)
        @php $absenPagi = $dataAbsen[$pekerja->id]; @endphp

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 mb-4">
            <div class="flex justify-between items-center mb-4 border-b border-slate-100 pb-3">
                <div>
                    <h3 class="font-bold text-slate-800 text-sm">{{ $pekerja->nama }}</h3>
                    <p class="text-[10px] text-slate-500 font-medium uppercase tracking-wider">{{ $pekerja->jabatan->nama_jabatan }}</p>
                </div>
                <div class="text-right">
                    <p class="text-[8px] text-slate-400 font-bold mb-0.5">STATUS PAGI</p>
                    <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider {{ $absenPagi->status == 'Hadir' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : ($absenPagi->status == 'Sakit' ? 'bg-amber-50 text-amber-600 border border-amber-100' : ($absenPagi->status == 'Izin' ? 'bg-blue-50 text-blue-600 border border-blue-100' : 'bg-rose-50 text-rose-600 border border-rose-100')) }}">
                        {{ $absenPagi->status }}
                    </span>
                </div>
            </div>

            <!-- Opsi Status (Radio Minimalis) -->
            <div class="grid grid-cols-4 gap-2 mb-3">
                <label class="cursor-pointer">
                    <input type="radio" name="absensi[{{ $pekerja->id }}][status]" value="Hadir" class="peer sr-only" onchange="toggleDurasi({{ $pekerja->id }}, true)" {{ $absenPagi->status == 'Hadir' ? 'checked' : '' }}>
                    <div class="text-center py-2 rounded-lg border border-slate-200 bg-slate-50 peer-checked:bg-emerald-500 peer-checked:text-white peer-checked:border-emerald-500 transition-all text-[11px] font-bold text-slate-500">Hadir</div>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="absensi[{{ $pekerja->id }}][status]" value="Sakit" class="peer sr-only" onchange="toggleDurasi({{ $pekerja->id }}, false)" {{ $absenPagi->status == 'Sakit' ? 'checked' : '' }}>
                    <div class="text-center py-2 rounded-lg border border-slate-200 bg-slate-50 peer-checked:bg-amber-500 peer-checked:text-white peer-checked:border-amber-500 transition-all text-[11px] font-bold text-slate-500">Sakit</div>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="absensi[{{ $pekerja->id }}][status]" value="Izin" class="peer sr-only" onchange="toggleDurasi({{ $pekerja->id }}, false)" {{ $absenPagi->status == 'Izin' ? 'checked' : '' }}>
                    <div class="text-center py-2 rounded-lg border border-slate-200 bg-slate-50 peer-checked:bg-blue-500 peer-checked:text-white peer-checked:border-blue-500 transition-all text-[11px] font-bold text-slate-500">Izin</div>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="absensi[{{ $pekerja->id }}][status]" value="Alfa" class="peer sr-only" onchange="toggleDurasi({{ $pekerja->id }}, false)" {{ $absenPagi->status == 'Alfa' ? 'checked' : '' }}>
                    <div class="text-center py-2 rounded-lg border border-slate-200 bg-slate-50 peer-checked:bg-rose-500 peer-checked:text-white peer-checked:border-rose-500 transition-all text-[11px] font-bold text-slate-500">Alfa</div>
                </label>
            </div>

            <!-- Opsi Durasi -->
            <div id="durasi_box_{{ $pekerja->id }}" class="{{ $absenPagi->status == 'Hadir' ? 'block' : 'hidden' }} bg-slate-50 p-3 rounded-lg border border-slate-200 mt-3">
                <p class="text-[9px] text-slate-500 font-bold mb-2 uppercase tracking-wider">Durasi Kerja:</p>
                <div class="grid grid-cols-2 gap-2">
                    <label class="cursor-pointer">
                        <input type="radio" name="absensi[{{ $pekerja->id }}][durasi]" value="Full" class="peer sr-only" checked>
                        <div class="text-center py-1.5 rounded border border-slate-200 bg-white peer-checked:bg-[#0c2340] peer-checked:text-white transition-all text-[11px] font-bold text-slate-600 shadow-sm">1 Hari (Full)</div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="absensi[{{ $pekerja->id }}][durasi]" value="Setengah Hari" class="peer sr-only">
                        <div class="text-center py-1.5 rounded border border-slate-200 bg-white peer-checked:bg-slate-500 peer-checked:text-white transition-all text-[11px] font-bold text-slate-600 shadow-sm">Setengah Hari</div>
                    </label>
                </div>
            </div>

            <input type="text" name="absensi[{{ $pekerja->id }}][keterangan]" value="{{ $absenPagi->keterangan }}" placeholder="Ket. opsional (misal: lembur, izin mendadak)..." class="mt-3 w-full text-xs border-slate-200 rounded-lg focus:ring-[#0c2340] focus:border-[#0c2340] bg-slate-50 py-2.5 placeholder:text-slate-400">
        </div>
        @endforeach

        <!-- TOMBOL SUBMIT MELAYANG -->
        <div class="fixed bottom-0 left-0 right-0 p-4 bg-white/80 backdrop-blur-lg border-t border-slate-200 z-50 flex justify-center">
            <div class="max-w-md w-full">
                <!-- Warna Background disesuaikan #0c2340 -->
                <button type="submit" class="w-full bg-[#0c2340] text-white font-bold text-sm py-3.5 rounded-xl shadow-[0_8px_20px_-6px_rgba(12,35,64,0.6)] hover:bg-opacity-90 transition-colors flex items-center justify-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Kirim & Kunci Laporan
                </button>
            </div>
        </div>
    </form>
</div>

<!-- ========================================== -->
<!-- SCRIPT JS UNTUK WARNA SLIDER DINAMIS       -->
<!-- ========================================== -->
<script>
    function toggleDurasi(pegawaiId, isHadir) {
        const box = document.getElementById('durasi_box_' + pegawaiId);
        if (isHadir) {
            box.classList.remove('hidden');
            box.classList.add('block');
        } else {
            box.classList.remove('block');
            box.classList.add('hidden');
        }
    }

    function updateSliderVisual(element) {
        const val = parseFloat(element.value);
        // Desain slider terisi dengan warna utama #0c2340
        element.style.background = `linear-gradient(to right, #0c2340 ${val}%, #e2e8f0 ${val}%)`;
    }

    document.addEventListener("DOMContentLoaded", function() {
        const sliders = document.querySelectorAll('.slider-progres');
        sliders.forEach(function(slider) {
            updateSliderVisual(slider);
        });
    });

    document.getElementById('formAbsenSore').addEventListener('submit', function(e) {
        e.preventDefault(); // Hentikan submit otomatis
        
        Swal.fire({
            title: 'Kirim Laporan Final?',
            text: "Data progres dan absen akan dikunci untuk hari ini.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0c2340',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Kirim Sekarang!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit(); // Lanjutkan proses submit
            }
        });
    });
</>
@endsection