<?php

namespace App\Http\Controllers;

use App\Models\Proyek;
use App\Models\LaporanHarian;
use App\Models\ItemPekerjaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class LaporanHarianController extends Controller
{
    // 1. Menampilkan Form Laporan di HP Pengawas
    public function create(Proyek $proyek)
    {
        $tanggalHariIni = Carbon::now()->format('Y-m-d');

        // Tarik semua daftar item pekerjaan yang harus digarap di proyek ini
        $proyek->load('itemPekerjaans');

        // Cek apakah Pengawas sudah pernah bikin laporan hari ini (untuk mode Edit)
        $laporanHariIni = LaporanHarian::where('proyek_id', $proyek->id)
            ->where('tanggal', $tanggalHariIni)
            ->first();

        return view('Pengawas.laporan-harian', compact('proyek', 'laporanHariIni', 'tanggalHariIni'));
    }
    
    // 2. Menyimpan Laporan Teks dan Mengubah Progres Slider (Smart Change-Log)
    public function store(Request $request, Proyek $proyek)
    {
        $tanggalHariIni = Carbon::now()->format('Y-m-d');

        $request->validate([
            'kegiatan' => 'required|string',
            'progres' => 'array',
            'progres.*' => 'numeric|min:0|max:100'
        ]);

        $pegawai_id = $request->user()->pegawai->id;

        try {
            DB::transaction(function () use ($request, $proyek, $tanggalHariIni, $pegawai_id) {

                // 1. Deteksi PERUBAHAN Slider
                $catatanProgres = "";
                if ($request->has('progres')) {
                    foreach ($request->progres as $item_id => $nilai_progres) {
                        $item = ItemPekerjaan::find($item_id);
                        if ($item) {
                            // LOGIKA PINTAR: Hanya catat jika angkanya BERUBAH (Slider digeser)
                            if ($item->progres_sekarang != $nilai_progres) {
                                // Contoh output: 🔹 Pekerjaan Atap: 0% ➔ 20%
                                $catatanProgres .= "\n🔹 " . $item->nama_pekerjaan . " : " . $item->progres_sekarang . "% ➔ " . $nilai_progres . "%";

                                // Update database fisik
                                $item->update(['progres_sekarang' => $nilai_progres]);
                            }
                        }
                    }
                }

                $kegiatanFinal = $request->kegiatan;

                // Pisahkan teks kegiatan murni dengan blok progres (jika Pengawas sedang mengedit laporan)
                $bagianAtas = explode('📋 [PERGERAKAN PROGRES HARI INI]:', $kegiatanFinal)[0];

                // Jika hari ini ADA slider yang digeser, kita buat/perbarui blok laporannya
                if ($catatanProgres != "") {
                    $kegiatanFinal = trim($bagianAtas) . "\n\n📋 [PERGERAKAN PROGRES HARI INI]:" . $catatanProgres;
                }
                // Jika tidak ada slider yang digeser (mungkin Pengawas cuma ngedit teks cuaca), biarkan teks aslinya utuh
                else {
                    $kegiatanFinal = $request->kegiatan;
                }

                // 2. Simpan ke Database
                LaporanHarian::updateOrCreate(
                    [
                        'proyek_id' => $proyek->id,
                        'tanggal' => $tanggalHariIni
                    ],
                    [
                        'pegawai_id' => $pegawai_id,
                        'kegiatan' => $kegiatanFinal
                    ]
                );
            });

            return redirect()->route('pengawas.dashboard')->with('success', 'Laporan progres fisik harian berhasil dikirim!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    // 3. Menampilkan Riwayat Laporan Harian untuk ADMIN (Sistem Filter Per Bulan)
    public function indexAdmin(Request $request, Proyek $proyek)
    {
        // 1. Ambil daftar bulan & tahun unik yang punya laporan dari database
        $availableMonths = LaporanHarian::where('proyek_id', $proyek->id)
            ->orderBy('tanggal', 'desc')
            ->pluck('tanggal')
            ->map(function ($date) {
                return Carbon::parse($date)->format('Y-m'); // Format: 2026-08
            })
            ->unique()
            ->values();

        // 2. Tentukan bulan apa yang sedang dibuka (Default: Bulan dari laporan terbaru, atau request URL)
        $selectedMonth = $request->input('bulan');

        if (!$selectedMonth) {
            $selectedMonth = $availableMonths->first() ?? Carbon::now()->format('Y-m');
        }

        // Pecah Tahun dan Bulan (Contoh: "2026-08" menjadi "2026" dan "08")
        $year = substr($selectedMonth, 0, 4);
        $month = substr($selectedMonth, 5, 2);

        // 3. Tarik data HANYA untuk bulan dan tahun yang terpilih (TIDAK PERLU PAGINASI)
        $riwayatLaporan = LaporanHarian::with('pegawai')
            ->where('proyek_id', $proyek->id)
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('Admin.riwayat-laporan-proyek', compact('proyek', 'riwayatLaporan', 'availableMonths', 'selectedMonth'));
    }
}
