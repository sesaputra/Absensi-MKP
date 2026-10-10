<?php

namespace App\Http\Controllers;

use App\Models\LaporanHarian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengawasController extends Controller
{
    /**
     * Dashboard Pengawas
     */
    public function dashboard()
    {
        $user = Auth::user();
        $pegawai = $user->pegawai;

        if (!$pegawai) {
            abort(403, 'Halaman ini khusus untuk tim lapangan.');
        }

        $proyekDitugaskan = $pegawai->proyeks()
            ->whereIn('status', ['Akan Dimulai', 'Berjalan'])
            ->get();

        return view(
            'Pengawas.dashboard',
            compact(
                'pegawai',
                'proyekDitugaskan'
            )
        );
    }




    public function riwayatAbsensi(Request $request)
    {
        $user = Auth::user();
        $pegawai = $user->pegawai;

        if (!$pegawai) {
            abort(403, 'Halaman ini khusus untuk tim lapangan.');
        }

        /*
    |--------------------------------------------------------------------------
    | Ambil Proyek yang Ditangani Pengawas
    |--------------------------------------------------------------------------
    */

        $proyek = $pegawai->proyeks()
            ->orderBy('nama_proyek')
            ->get();

        $proyekIds = $proyek->pluck('id');

        /*
    |--------------------------------------------------------------------------
    | Query Seluruh Laporan pada Proyek yang Ditangani
    |--------------------------------------------------------------------------
    */

        $query = LaporanHarian::query()
            ->with([
                'proyek',
                'pegawai.jabatan',
                'absensis.pegawai.jabatan',
            ])
            ->whereIn('proyek_id', $proyekIds);

        /*
    |--------------------------------------------------------------------------
    | Filter Tanggal
    |--------------------------------------------------------------------------
    */

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        /*
    |--------------------------------------------------------------------------
    | Filter Bulan
    |--------------------------------------------------------------------------
    */

        if ($request->filled('bulan')) {
            $tanggalBulan = \Carbon\Carbon::createFromFormat(
                'Y-m',
                $request->bulan
            );

            $query->whereYear('tanggal', $tanggalBulan->year)
                ->whereMonth('tanggal', $tanggalBulan->month);
        }

        /*
    |--------------------------------------------------------------------------
    | Filter Proyek
    |--------------------------------------------------------------------------
    |
    | Query utama sudah dibatasi pada proyek milik Pengawas.
    | Jadi, proyek di luar tanggung jawabnya tidak akan muncul.
    |
    */

        if ($request->filled('proyek_id')) {
            $query->where('proyek_id', $request->proyek_id);
        }

        /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */

        $laporan = $query
            ->latest('tanggal')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view(
            'Pengawas.riwayat-absensi',
            compact('pegawai', 'laporan', 'proyek')
        );
    }



    public function detailAbsensi(LaporanHarian $laporan)
    {
        $user = Auth::user();
        $pegawai = $user->pegawai;

        if (!$pegawai) {
            abort(403, 'Halaman ini khusus untuk tim lapangan.');
        }

        /*
    |--------------------------------------------------------------------------
    | Validasi Akses Berdasarkan Proyek
    |--------------------------------------------------------------------------
    */

        $memilikiAkses = $pegawai->proyeks()
            ->where('proyeks.id', $laporan->proyek_id)
            ->exists();

        if (!$memilikiAkses) {
            abort(403, 'Anda tidak memiliki akses ke laporan ini.');
        }

        /*
    |--------------------------------------------------------------------------
    | Load Relationship
    |--------------------------------------------------------------------------
    */

        $laporan->load([
            'proyek',
            'pegawai.jabatan',
            'absensis.pegawai.jabatan',
        ]);

        return view(
            'Pengawas.detail-absensi',
            compact('pegawai', 'laporan')
        );
    }
}
