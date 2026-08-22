@extends('layouts.mobile')

@section('title', 'Laporan Progres Proyek')

@section('content')
<div class="max-w-md mx-auto bg-slate-50 min-h-screen pb-24 shadow-xl border-x border-slate-100">

    <!-- HEADER SORE / LAPORAN -->
    <div class="bg-slate-800 text-white p-6 rounded-b-3xl shadow-md relative overflow-hidden">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
        <div class="flex items-center justify-between mb-3 relative z-10">
            <h1 class="text-xl font-bold">Laporan Progres Fisik</h1>
            <a href="{{ route('pengawas.dashboard') }}" class="text-slate-300 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </a>
        </div>
        <div class="relative z-10">
            <p class="text-slate-300 text-sm font-medium">{{ $proyek->nama_proyek }}</p>
            <p class="text-amber-400 text-sm mt-1 font-bold">{{ \Carbon\Carbon::parse($tanggalHariIni)->translatedFormat('l, d F Y') }}</p>
        </div>
    </div>

    <!-- FORM LAPORAN -->
    <form action="{{ route('laporan.store', $proyek->id) }}" method="POST" class="p-4 mt-2" onsubmit="return confirm('Kirim laporan progres ini ke Admin kantor?');">
        @csrf

        <!-- BAGIAN 1: CATATAN HARIAN -->
        <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-3 px-1 flex items-center mt-2">
            <svg class="w-4 h-4 mr-1.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Catatan Lapangan
        </h2>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4 mb-6">
            <label class="block text-xs font-semibold text-slate-500 mb-2">Aktivitas Hari Ini & Kondisi Cuaca</label>
            <textarea name="kegiatan" rows="4" required placeholder="Contoh: Pagi cerah, sore hujan rintik. Pekerjaan hari ini fokus pada pengecoran pondasi barat..." class="w-full rounded-xl border-slate-300 py-3 px-4 text-sm focus:ring-amber-500 focus:border-amber-500 bg-slate-50">{{ $laporanHariIni->kegiatan ?? '' }}</textarea>
        </div>

        <!-- BAGIAN 2: SLIDER PROGRES PEKERJAAN -->
        <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-3 px-1 flex items-center">
            <svg class="w-4 h-4 mr-1.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            Update Progres Fisik
        </h2>

        @forelse($proyek->itemPekerjaans as $item)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 mb-4">
            <div class="flex justify-between items-start mb-4">
                <h3 class="font-bold text-slate-800 text-sm leading-tight pr-4">{{ $item->nama_pekerjaan }}</h3>
                <!-- Output Angka Realtime -->
                <span id="output_{{ $item->id }}" class="text-lg font-black {{ $item->progres_sekarang == 100 ? 'text-green-500' : 'text-blue-600' }} shrink-0">
                    {{ $item->progres_sekarang }}%
                </span>
            </div>

            @if($item->progres_sekarang == 100)
            <div class="bg-green-50 text-green-600 text-xs font-bold px-3 py-2 rounded-lg flex items-center">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Pekerjaan ini sudah 100% selesai.
            </div>
            <input type="hidden" name="progres[{{ $item->id }}]" value="100">
            @else
            <!-- Input Slider (Range) -->
            <input type="range" name="progres[{{ $item->id }}]" min="0" max="100" value="{{ $item->progres_sekarang }}"
                class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-blue-600"
                oninput="document.getElementById('output_{{ $item->id }}').innerText = this.value + '%'">
            <div class="flex justify-between text-[10px] text-slate-400 font-bold mt-2">
                <span>0%</span>
                <span>50%</span>
                <span>100%</span>
            </div>
            @endif
        </div>
        @empty
        <div class="bg-white rounded-2xl border border-dashed border-slate-300 p-6 text-center">
            <p class="text-sm font-bold text-slate-600">Belum ada item pekerjaan.</p>
            <p class="text-xs text-slate-400 mt-1">Tunggu Admin kantor mendaftarkan item pekerjaan proyek ini.</p>
        </div>
        @endforelse

        <!-- TOMBOL SIMPAN MELAYANG -->
        @if($proyek->itemPekerjaans->count() > 0)
        <div class="fixed bottom-0 left-0 right-0 p-4 bg-white/90 backdrop-blur-md border-t border-slate-200 z-50 flex justify-center">
            <div class="max-w-md w-full">
                <button type="submit" class="w-full bg-slate-800 text-white font-bold text-sm py-3.5 rounded-xl shadow-lg hover:bg-[#0c2340] transition-colors">
                    Kirim Laporan Progres
                </button>
            </div>
        </div>
        @endif
    </form>
</div>
@endsection