@extends('layouts.mobile')

@section('title', 'Tutup Absen & Laporan Sore')

@section('content')
<div class="max-w-md mx-auto bg-slate-50 min-h-screen pb-24 shadow-xl border-x border-slate-100">

    <!-- HEADER SORE -->
    <div class="bg-indigo-900 text-white p-6 rounded-b-3xl shadow-md relative overflow-hidden">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
        <div class="flex items-center justify-between mb-3 relative z-10">
            <h1 class="text-xl font-bold">Laporan Akhir Hari</h1>
            <a href="{{ route('pengawas.dashboard') }}" class="text-indigo-300 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </a>
        </div>
        <div class="relative z-10">
            <p class="text-indigo-300 text-sm font-medium">{{ $proyek->nama_proyek }}</p>
            <p class="text-amber-400 text-sm mt-1 font-bold">{{ now()->translatedFormat('l, d F Y') }} (Sore)</p>
        </div>
    </div>

    <!-- FORM SORE (Ditambah enctype) -->
    <form action="{{ route('absensi.storeSore', $proyek->id) }}" method="POST" enctype="multipart/form-data" class="p-4 mt-2" onsubmit="return confirm('Kirim Laporan Final Sore? Seluruh data akan dikunci dan dikirim ke Admin.');">
        @csrf

        <!-- KARTU 1: FOTO PROGRES SORE -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4 mb-4 border-l-4 border-l-indigo-500">
            <h2 class="text-sm font-bold text-slate-800 mb-2">📸 Foto Progres Sore</h2>
            <p class="text-[11px] text-slate-500 mb-3 leading-tight">Ambil foto hasil pekerjaan yang telah diselesaikan hari ini sebelum tukang pulang.</p>
            <input type="file" name="foto_sore" accept="image/*" capture="environment" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required>
        </div>

        <!-- KARTU 2: UPDATE PROGRES PEKERJAAN (SLIDER) -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4 mb-4">
            <h2 class="text-sm font-bold text-slate-800 mb-3">📈 Update Progres Fisik</h2>
            
            @forelse($proyek->itemPekerjaans as $item)
            <div class="mb-4 last:mb-0 bg-slate-50 p-3 rounded-xl border border-slate-100">
                <div class="flex justify-between items-center mb-2">
                    <label class="text-xs font-bold text-slate-700">{{ $item->nama_pekerjaan }}</label>
                    <span class="text-xs font-black text-indigo-600 bg-indigo-100 px-2 py-0.5 rounded-md" id="val_{{ $item->id }}">{{ $item->progres_sekarang }}%</span>
                </div>
                <!-- Input Range Slider -->
                <input type="range" name="progres[{{ $item->id }}]" min="0" max="100" value="{{ $item->progres_sekarang }}" class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-indigo-600" oninput="document.getElementById('val_{{ $item->id }}').innerText = this.value + '%'">
            </div>
            @empty
            <p class="text-[11px] text-slate-400 italic">Belum ada item pekerjaan yang didaftarkan.</p>
            @endforelse
        </div>

        <!-- KARTU 3: CATATAN KEGIATAN / CUACA -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4 mb-5">
            <h2 class="text-sm font-bold text-slate-800 mb-2">📝 Catatan Lapangan</h2>
            <textarea name="kegiatan" rows="3" class="w-full text-sm border-slate-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50 p-3" placeholder="Contoh: Pekerjaan pengecoran lantai 2 selesai. Cuaca hujan dari jam 2 siang sampai jam 4 sore..." required></textarea>
        </div>

        <hr class="border-slate-200 mb-4 border-dashed">
        <h2 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-4 px-1">Koreksi Absen Kepulangan</h2>

        <!-- KARTU 4: LOOPING ABSENSI PEKERJA -->
        @foreach($proyek->pegawais as $pekerja)
        @php $absenPagi = $dataAbsen[$pekerja->id]; @endphp

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4 mb-4">
            <div class="flex justify-between items-start mb-4 border-b border-slate-100 pb-3">
                <div>
                    <h3 class="font-bold text-slate-800 text-base">{{ $pekerja->nama }}</h3>
                    <p class="text-xs text-slate-500 font-medium">{{ $pekerja->jabatan->nama_jabatan }}</p>
                </div>
                <div class="text-right">
                    <p class="text-[9px] text-slate-400 font-bold mb-1">STATUS PAGI:</p>
                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase {{ $absenPagi->status == 'Hadir' ? 'bg-green-100 text-green-700' : ($absenPagi->status == 'Sakit' ? 'bg-amber-100 text-amber-700' : ($absenPagi->status == 'Izin' ? 'bg-blue-100 text-blue-700' : 'bg-red-100 text-red-700')) }}">
                        {{ $absenPagi->status }}
                    </span>
                </div>
            </div>

            <!-- Opsi Status (Radio) -->
            <div class="grid grid-cols-4 gap-2 mb-3">
                <label class="cursor-pointer">
                    <input type="radio" name="absensi[{{ $pekerja->id }}][status]" value="Hadir" class="peer sr-only" onchange="toggleDurasi({{ $pekerja->id }}, true)" {{ $absenPagi->status == 'Hadir' ? 'checked' : '' }}>
                    <div class="text-center py-2 rounded-xl border-2 border-slate-100 bg-slate-50 peer-checked:bg-green-500 peer-checked:text-white peer-checked:border-green-500 transition-all text-[11px] font-bold text-slate-500">Hadir</div>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="absensi[{{ $pekerja->id }}][status]" value="Sakit" class="peer sr-only" onchange="toggleDurasi({{ $pekerja->id }}, false)" {{ $absenPagi->status == 'Sakit' ? 'checked' : '' }}>
                    <div class="text-center py-2 rounded-xl border-2 border-slate-100 bg-slate-50 peer-checked:bg-amber-500 peer-checked:text-white peer-checked:border-amber-500 transition-all text-[11px] font-bold text-slate-500">Sakit</div>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="absensi[{{ $pekerja->id }}][status]" value="Izin" class="peer sr-only" onchange="toggleDurasi({{ $pekerja->id }}, false)" {{ $absenPagi->status == 'Izin' ? 'checked' : '' }}>
                    <div class="text-center py-2 rounded-xl border-2 border-slate-100 bg-slate-50 peer-checked:bg-blue-500 peer-checked:text-white peer-checked:border-blue-500 transition-all text-[11px] font-bold text-slate-500">Izin</div>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="absensi[{{ $pekerja->id }}][status]" value="Alfa" class="peer sr-only" onchange="toggleDurasi({{ $pekerja->id }}, false)" {{ $absenPagi->status == 'Alfa' ? 'checked' : '' }}>
                    <div class="text-center py-2 rounded-xl border-2 border-slate-100 bg-slate-50 peer-checked:bg-red-500 peer-checked:text-white peer-checked:border-red-500 transition-all text-[11px] font-bold text-slate-500">Alfa</div>
                </label>
            </div>

            <!-- Opsi Durasi -->
            <div id="durasi_box_{{ $pekerja->id }}" class="{{ $absenPagi->status == 'Hadir' ? 'block' : 'hidden' }} bg-indigo-50 p-3 rounded-xl border border-indigo-100 mt-2">
                <p class="text-[10px] text-indigo-800 font-bold mb-2">DURASI KERJA HARI INI:</p>
                <div class="grid grid-cols-2 gap-2">
                    <label class="cursor-pointer">
                        <input type="radio" name="absensi[{{ $pekerja->id }}][durasi]" value="Full" class="peer sr-only" checked>
                        <div class="text-center py-1.5 rounded-lg border border-indigo-200 bg-white peer-checked:bg-indigo-600 peer-checked:text-white transition-all text-[11px] font-bold text-indigo-500">Full (1 Hari)</div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="absensi[{{ $pekerja->id }}][durasi]" value="Setengah Hari" class="peer sr-only">
                        <div class="text-center py-1.5 rounded-lg border border-indigo-200 bg-white peer-checked:bg-amber-500 peer-checked:text-white transition-all text-[11px] font-bold text-indigo-500">Setengah Hari</div>
                    </label>
                </div>
            </div>

            <input type="text" name="absensi[{{ $pekerja->id }}][keterangan]" value="{{ $absenPagi->keterangan }}" placeholder="Tambahkan keterangan jika perlu..." class="mt-3 w-full text-xs border-slate-200 rounded-lg focus:ring-indigo-500 bg-slate-50 py-2">
        </div>
        @endforeach

        <div class="fixed bottom-0 left-0 right-0 p-4 bg-white/90 backdrop-blur-md border-t border-slate-200 z-50 flex justify-center shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
            <div class="max-w-md w-full">
                <button type="submit" class="w-full bg-indigo-900 text-white font-bold text-sm py-3.5 rounded-xl shadow-lg hover:bg-indigo-950 transition-colors">
                    Kirim Laporan Final
                </button>
            </div>
        </div>
    </form>
</div>

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
</script>
@endsection