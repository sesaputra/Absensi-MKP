<?php

namespace App\Http\Controllers;

use App\Models\Proyek;
use App\Models\Absensi;
use App\Models\LaporanHarian; // Tambahkan ini
use App\Models\ItemPekerjaan; // Tambahkan ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage; // Untuk upload foto

class AbsensiController extends Controller
{
    // 1. Menampilkan Form Absensi (Cerdas: Deteksi Pagi / Sore)
    public function create(Proyek $proyek)
    {
        $hariIni = Carbon::now()->startOfDay();
        $tanggalMulai = Carbon::parse($proyek->tanggal_mulai)->startOfDay();

        if ($hariIni->lessThan($tanggalMulai)) {
            return redirect()->route('pengawas.dashboard')->with('error', 'Proyek ini baru akan dimulai pada ' . $tanggalMulai->translatedFormat('d F Y') . '.');
        }

        $tanggalHariIni = Carbon::now()->format('Y-m-d');
        $proyek->load(['pegawais', 'itemPekerjaans']); // Load pegawai dan item pekerjaan sekaligus

        // CEK APAKAH SUDAH ADA BUKU LAPORAN HARI INI?
        $laporanHariIni = LaporanHarian::where('proyek_id', $proyek->id)
            ->where('tanggal', $tanggalHariIni)
            ->first();

        // JIKA BELUM ADA SAMA SEKALI -> Buka Form Pagi (Foto Briefing & Absen Hadir)
        if (!$laporanHariIni) {
            return view('Pengawas.absensi', compact('proyek'));
        }

        // JIKA SUDAH ABSEN PAGI -> Buka Form Sore (Foto Progres, Absen Durasi, Kegiatan & Slider)
        if ($laporanHariIni->status_validasi === 'Draft Pagi') {
            $dataAbsen = Absensi::where('laporan_harian_id', $laporanHariIni->id)->get()->keyBy('pegawai_id');
            return view('Pengawas.absensi-sore', compact('proyek', 'laporanHariIni', 'dataAbsen'));
        }

        // JIKA SUDAH ABSEN SORE
        return redirect()->route('pengawas.dashboard')->with('success', 'Laporan & Absensi hari ini sudah dikunci menunggu persetujuan Admin.');
    }

    // 2. Menyimpan Absensi PAGI (Buat "Buku Laporan" & Isi Daftar Tukang)
    public function store(Request $request, Proyek $proyek)
    {
        $tanggalHariIni = Carbon::now()->format('Y-m-d');

        if (LaporanHarian::where('proyek_id', $proyek->id)->where('tanggal', $tanggalHariIni)->exists()) {
            return back()->with('error', 'Tim di proyek ini sudah diabsen pagi.');
        }

        // Validasi Foto & GPS ditambahkan di sini
        $request->validate([
            'foto_pagi' => 'required|image|max:5120', // Maks 5MB
            'latitude' => 'required',
            'longitude' => 'required',
            'waktu_absen' => 'required',
            'absensi' => 'required|array',
            'absensi.*.status' => 'required|in:Hadir,Sakit,Izin,Alfa',
        ]);

        // Simpan File Foto ke Storage Laravel (folder: public/bukti_lapangan)
        $pathFotoPagi = $request->file('foto_pagi')->store('bukti_lapangan', 'public');

        DB::transaction(function () use ($request, $proyek, $tanggalHariIni, $pathFotoPagi) {

            // A. BUAT SAMPUL BUKU (Laporan Harian)
            $laporan = LaporanHarian::create([
                'proyek_id' => $proyek->id,
                'pegawai_id' => $request->user()->pegawai->id, // Mandor
                'tanggal' => $tanggalHariIni,
                'foto_pagi' => $pathFotoPagi,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'waktu_absen' => $request->waktu_absen,
                'status_validasi' => 'Draft Pagi',
            ]);

            // B. ISI ISI BUKU (Data Absen Tukang)
            foreach ($request->absensi as $pegawai_id => $data) {
                Absensi::create([
                    'laporan_harian_id' => $laporan->id, // <<--- JEMBATAN PENGHUBUNG
                    'proyek_id' => $proyek->id,
                    'pegawai_id' => $pegawai_id,
                    'tanggal' => $tanggalHariIni,
                    'status' => $data['status'],
                    'durasi' => $data['status'] === 'Hadir' ? 'Full' : null,
                    'keterangan' => $data['keterangan'] ?? null,
                    'status_validasi' => 'Draft Pagi',
                ]);
            }
        });

        return redirect()->route('pengawas.dashboard')->with('success', 'Absen Pagi + Foto GPS berhasil! Lanjutkan progres nanti sore.');
    }

    // 3. Menyimpan Absensi SORE & LAPORAN KEGIATAN (Kunci Final)
    public function storeSore(Request $request, Proyek $proyek)
    {
        $tanggalHariIni = Carbon::now()->format('Y-m-d');
        $laporan = LaporanHarian::where('proyek_id', $proyek->id)->where('tanggal', $tanggalHariIni)->firstOrFail();

        $request->validate([
            'foto_sore' => 'required|image|max:5120',
            'kegiatan' => 'required|string',
            'progres' => 'array',
            'progres.*' => 'numeric|min:0|max:100',
            'absensi' => 'required|array',
            'absensi.*.status' => 'required|in:Hadir,Sakit,Izin,Alfa',
        ]);

        $pathFotoSore = $request->file('foto_sore')->store('bukti_lapangan', 'public');

        DB::transaction(function () use ($request, $proyek, $laporan, $pathFotoSore) {

            // ==========================================
            // LOGIKA PINTAR ANDA: Deteksi Perubahan Slider
            // ==========================================
            $catatanProgres = "";
            if ($request->has('progres')) {
                foreach ($request->progres as $item_id => $nilai_progres) {
                    $item = ItemPekerjaan::find($item_id);
                    if ($item && $item->progres_sekarang != $nilai_progres) {
                        $catatanProgres .= "\n🔹 " . $item->nama_pekerjaan . " : " . $item->progres_sekarang . "% ➔ " . $nilai_progres . "%";
                        $item->update(['progres_sekarang' => $nilai_progres]);
                    }
                }
            }

            $kegiatanFinal = $request->kegiatan;
            $bagianAtas = explode('📋 [PERGERAKAN PROGRES HARI INI]:', $kegiatanFinal)[0];
            if ($catatanProgres != "") {
                $kegiatanFinal = trim($bagianAtas) . "\n\n📋 [PERGERAKAN PROGRES HARI INI]:" . $catatanProgres;
            }

            // A. UPDATE SAMPUL BUKU (Laporan Harian)
            $laporan->update([
                'foto_sore' => $pathFotoSore,
                'kegiatan' => $kegiatanFinal,
                'status_validasi' => 'Menunggu Persetujuan' // Kunci Laporan!
            ]);

            // B. UPDATE ISI BUKU (Revisi Absen & Durasi Sore)
            foreach ($request->absensi as $pegawai_id => $data) {
                $durasiFinal = ($data['status'] === 'Hadir') ? ($data['durasi'] ?? 'Full') : null;

                Absensi::where('laporan_harian_id', $laporan->id)
                    ->where('pegawai_id', $pegawai_id)
                    ->update([
                        'status' => $data['status'],
                        'durasi' => $durasiFinal,
                        'keterangan' => $data['keterangan'] ?? null,
                        'status_validasi' => 'Menunggu Persetujuan' // Kunci Absen!
                    ]);
            }
        });

        return redirect()->route('pengawas.dashboard')->with('success', 'Laporan Final, Foto Sore & Absensi dikirim ke Admin!');
    }

    // =======================================================================
    // FUNGSI ADMIN DI BAWAH INI
    // =======================================================================

    public function indexAbsensiGlobal(Request $request)
    {
        $tanggalFilter = $request->filter_tanggal ?? Carbon::now()->format('Y-m-d');

        // 1. Tarik Data dari SAMPUL BUKU (Laporan Harian) agar Foto & GPS ikut terbawa
        // Gunakan eager loading (with) untuk menarik relasi Proyek, Mandor, dan daftar Absensi Tukang sekaligus
        $draftLaporan = LaporanHarian::with(['proyek', 'pembuatLaporan', 'absensis.pegawai'])
            ->where('status_validasi', 'Menunggu Persetujuan')
            ->orderBy('tanggal', 'desc')
            ->get();

        $laporanValid = LaporanHarian::with(['proyek', 'pembuatLaporan', 'absensis.pegawai'])
            ->where('tanggal', $tanggalFilter)
            ->where('status_validasi', 'Disetujui')
            ->get();

        // Tidak perlu di-groupBy('proyek_id') lagi karena 1 Laporan sudah pasti mewakili 1 Proyek di hari itu
        return view('Admin.manajemen-absensi', compact('draftLaporan', 'laporanValid', 'tanggalFilter'));
    }

    public function setujuiAbsensi($proyek_id, $tanggal)
    {
        // Gunakan DB Transaction agar Laporan dan Absensi tersinkronisasi
        DB::transaction(function () use ($proyek_id, $tanggal) {
            // 1. Setujui Sampulnya (Laporan Harian)
            LaporanHarian::where('proyek_id', $proyek_id)
                ->where('tanggal', $tanggal)
                ->update(['status_validasi' => 'Disetujui']);

            // 2. Setujui Isinya (Semua data absensi tukang di dalam laporan tersebut)
            Absensi::where('proyek_id', $proyek_id)
                ->where('tanggal', $tanggal)
                ->update(['status_validasi' => 'Disetujui']);
        });

        return back()->with('success', 'Laporan Harian, Foto, dan Data Absensi berhasil disetujui!');
    }

    public function tolakAbsensi($proyek_id, $tanggal)
    {
        DB::transaction(function () use ($proyek_id, $tanggal) {
            LaporanHarian::where('proyek_id', $proyek_id)->where('tanggal', $tanggal)->update(['status_validasi' => 'Ditolak']);
            Absensi::where('proyek_id', $proyek_id)->where('tanggal', $tanggal)->update(['status_validasi' => 'Ditolak']);
        });

        return back()->with('error', 'Laporan dan Absensi ditolak. Mandor harus memperbaikinya.');
    }

    // Menampilkan Detail Riwayat Absensi Khusus 1 Proyek
    public function detailAbsensi(Request $request, Proyek $proyek)
    {
        $bulanFilter = $request->filter_bulan ?? Carbon::now()->format('Y-m');
        $tahun = date('Y', strtotime($bulanFilter));
        $bulan = date('m', strtotime($bulanFilter));

        // Tarik dari Laporan Harian agar riwayat absensi juga menampilkan foto per harinya
        $riwayatLaporan = LaporanHarian::with(['pembuatLaporan', 'absensis.pegawai'])
            ->where('proyek_id', $proyek->id)
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('Admin.detail-absensi-proyek', compact('proyek', 'riwayatLaporan', 'bulanFilter'));
    }
}
