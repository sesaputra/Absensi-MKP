<?php

namespace App\Http\Controllers;

use App\Models\LaporanHarian;
use App\Models\Pegawai;
use App\Models\Proyek;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        /*
        |--------------------------------------------------------------------------
        | Statistik Utama
        |--------------------------------------------------------------------------
        */

        $totalProyekAktif = Proyek::query()
            ->where('status', 'Berjalan')
            ->count();

        $totalProyekSelesai = Proyek::query()
            ->where('status', 'Selesai')
            ->count();

        $totalPegawai = Pegawai::query()
            ->where('status', 'aktif')
            ->count();

        $totalPendingValidasi = LaporanHarian::query()
            ->where('status_validasi', 'Menunggu Persetujuan')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Konfigurasi Kesehatan Keuangan
        |--------------------------------------------------------------------------
        */

        $batasKasKritis = 5_000_000;
        $batasKasMenipis = 20_000_000;

        $batasRABWaspada = 75;
        $batasRABKritis = 90;


        /*
        |--------------------------------------------------------------------------
        | Proyek Aktif + Ringkasan Keuangan
        |--------------------------------------------------------------------------
        |
        | Pemasukan  = dana/termin yang benar-benar diterima.
        | Pengeluaran = uang yang sudah dibelanjakan.
        |
        */

        $proyekAktif = Proyek::query()
            ->where('status', 'Berjalan')
            ->withSum([
                'keuangans as total_pemasukan' => function ($query) {
                    $query->where('tipe', 'Pemasukan');
                },
            ], 'nominal')
            ->withSum([
                'keuangans as total_pengeluaran' => function ($query) {
                    $query->where('tipe', 'Pengeluaran');
                },
            ], 'nominal')
            ->latest()
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Hitung Kondisi Keuangan Setiap Proyek
        |--------------------------------------------------------------------------
        */

        foreach ($proyekAktif as $proyek) {

            $totalPemasukan = (float) ($proyek->total_pemasukan ?? 0);

            $totalPengeluaran = (float) ($proyek->total_pengeluaran ?? 0);

            $anggaran = (float) ($proyek->anggaran ?? 0);


            /*
            |--------------------------------------------------------------------------
            | Cash Flow
            |--------------------------------------------------------------------------
            */

            $kasTersedia = $totalPemasukan - $totalPengeluaran;


            /*
            |--------------------------------------------------------------------------
            | Penggunaan RAB
            |--------------------------------------------------------------------------
            */

            $persentasePengeluaran = $anggaran > 0
                ? ($totalPengeluaran / $anggaran) * 100
                : 0;


            /*
            |--------------------------------------------------------------------------
            | Health Status
            |--------------------------------------------------------------------------
            */

            if ($anggaran <= 0) {

                $healthStatus = 'Anggaran Belum Diatur';
                $healthLevel = 'neutral';
            } elseif ($kasTersedia < 0) {

                $healthStatus = 'Kas Negatif';
                $healthLevel = 'danger';
            } elseif ($kasTersedia <= $batasKasKritis) {

                $healthStatus = 'Dana Kritis';
                $healthLevel = 'danger';
            } elseif ($kasTersedia <= $batasKasMenipis) {

                $healthStatus = 'Dana Menipis';
                $healthLevel = 'warning';
            } elseif ($persentasePengeluaran >= $batasRABKritis) {

                $healthStatus = 'Pengeluaran Kritis';
                $healthLevel = 'danger';
            } elseif ($persentasePengeluaran >= $batasRABWaspada) {

                $healthStatus = 'Awas Menipis';
                $healthLevel = 'warning';
            } else {

                $healthStatus = 'Kas Aman';
                $healthLevel = 'safe';
            }


            /*
            |--------------------------------------------------------------------------
            | Simpan Hasil Perhitungan
            |--------------------------------------------------------------------------
            */

            $proyek->kas_tersedia = $kasTersedia;

            $proyek->persentase_pengeluaran = round(
                $persentasePengeluaran,
                1
            );

            $proyek->health_status = $healthStatus;

            $proyek->health_level = $healthLevel;
        }


        /*
        |--------------------------------------------------------------------------
        | Proyek yang Ditampilkan di Dashboard
        |--------------------------------------------------------------------------
        */

        $proyekKesehatan = $proyekAktif->take(3);


        /*
        |--------------------------------------------------------------------------
        | Proyek Kritis
        |--------------------------------------------------------------------------
        */

        $totalProyekKritis = $proyekAktif
            ->where('health_level', 'danger')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Proyek Dana Menipis
        |--------------------------------------------------------------------------
        */

        $totalProyekDanaMenipis = $proyekAktif
            ->where('health_level', 'warning')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Pegawai Terbaru
        |--------------------------------------------------------------------------
        */

        $pegawais = Pegawai::query()
            ->with('jabatan')
            ->latest()
            ->take(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Pegawai Aktif Tanpa Akun
        |--------------------------------------------------------------------------
        */

        $totalPegawaiTanpaAkun = Pegawai::query()
            ->where('status', 'aktif')
            ->whereNull('user_id')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Aktivitas Terbaru
        |--------------------------------------------------------------------------
        */

        $recentActivities = collect();


        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return view('admin.dashboard', compact(
            'totalProyekAktif',
            'totalProyekSelesai',
            'totalPegawai',
            'totalPendingValidasi',
            'totalProyekKritis',
            'totalProyekDanaMenipis',
            'totalPegawaiTanpaAkun',
            'proyekKesehatan',
            'pegawais',
            'recentActivities',
        ));
    }
}
