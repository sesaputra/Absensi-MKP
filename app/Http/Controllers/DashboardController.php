<?php

namespace App\Http\Controllers;

use App\Models\KeuanganProyek;
use App\Models\LaporanHarian;
use App\Models\Proyek;
use App\Models\Pegawai;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProyekSelesai = Proyek::where('status', 'Selesai')->count();
        $totalProyekAktif = Proyek::where('status', 'Berjalan')->count();
        $totalPegawai = Pegawai::count();

        // Ambil 3 Proyek Teratas untuk dipantau
        $proyekKesehatan = Proyek::where('status', 'Berjalan')
            ->latest()
            ->take(3)
            ->get();

        // ==========================================
        // MENGHITUNG KESEHATAN KAS SETIAP PROYEK
        // ==========================================
        foreach ($proyekKesehatan as $proyek) {
            $totalPemasukan = KeuanganProyek::where('proyek_id', $proyek->id)
                ->where('tipe', 'Pemasukan')->sum('nominal');

            $totalPengeluaran = KeuanganProyek::where('proyek_id', $proyek->id)
                ->where('tipe', 'Pengeluaran')->sum('nominal');

            $kasTersedia = $totalPemasukan - $totalPengeluaran;

            $persentaseTerpakai = 0;
            if ($totalPemasukan > 0) {
                $persentaseTerpakai = ($totalPengeluaran / $totalPemasukan) * 100;
            }

            $batasAmanNominal = 5000000; // Peringatan nyala jika kas di bawah Rp 5 Juta

            // Menentukan Warna dan Status (Sama seperti halaman detail)
            if ($kasTersedia < 0) {
                $proyek->health_status = 'Nombok!';
                $proyek->health_color = 'bg-red-100 text-red-700';
                $proyek->health_dot = 'bg-red-600';
                $persentaseTerpakai = 100;
            } elseif ($kasTersedia > 0 && $kasTersedia <= $batasAmanNominal) {
                $proyek->health_status = 'Kritis (Kas < 5 Jt)';
                $proyek->health_color = 'bg-red-100 text-red-700';
                $proyek->health_dot = 'bg-red-500';
            } elseif ($persentaseTerpakai >= 90) {
                $proyek->health_status = 'Kritis (> 90%)';
                $proyek->health_color = 'bg-red-100 text-red-700';
                $proyek->health_dot = 'bg-red-500';
            } elseif ($persentaseTerpakai >= 75) {
                $proyek->health_status = 'Awas Menipis';
                $proyek->health_color = 'bg-amber-100 text-amber-700';
                $proyek->health_dot = 'bg-amber-500';
            } else {
                $proyek->health_status = 'Kas Aman';
                $proyek->health_color = 'bg-green-100 text-green-700';
                $proyek->health_dot = 'bg-green-500';
            }

            // Simpan variabel untuk dikirim ke view
            $proyek->kas_tersedia = $kasTersedia;
            $proyek->persentase_terpakai = $persentaseTerpakai > 100 ? 100 : $persentaseTerpakai;
        }

        $pegawais = Pegawai::with('jabatan')->latest()->take(5)->get();
        // Tambahkan di dalam DashboardController Anda (di fungsi index)
        $totalPendingValidasi = LaporanHarian::where('status_validasi', 'Menunggu Persetujuan')->count();

        return view('admin.dashboard', compact(
            'totalProyekSelesai',
            'totalProyekAktif',
            'totalPegawai',
            'proyekKesehatan',
            'pegawais',
            'totalPendingValidasi'
        ));
    }
}
