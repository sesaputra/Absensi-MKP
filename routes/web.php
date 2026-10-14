<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemPekerjaanController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\KasbonController;
use App\Http\Controllers\KeuanganProyekController;
use App\Http\Controllers\LaporanHarianController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PengawasController;
use App\Http\Controllers\ProyekController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public / Guest Routes
|--------------------------------------------------------------------------
*/

// Root diarahkan ke halaman login.
Route::get('/', function () {
    return redirect()->route('login');
});

// Hanya guest yang boleh mengakses halaman dan proses login.
Route::middleware('guest')->group(function () {

    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/login', [AuthController::class, 'authenticate'])
        ->name('login.post');
});


/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');


    /*
    |--------------------------------------------------------------------------
    | Admin & Super Admin
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:admin,super_admin')->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');


        /*
        |--------------------------------------------------------------------------
        | Item Pekerjaan
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/proyek/{proyek}/item-pekerjaan',
            [ItemPekerjaanController::class, 'store']
        )->name('item-pekerjaan.store');

        Route::put(
            '/item-pekerjaan/{itemPekerjaan}',
            [ItemPekerjaanController::class, 'update']
        )->name('item-pekerjaan.update');

        Route::delete(
            '/item-pekerjaan/{itemPekerjaan}',
            [ItemPekerjaanController::class, 'destroy']
        )->name('item-pekerjaan.destroy');


        /*
        |--------------------------------------------------------------------------
        | Manajemen Absensi
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/manajemen-absensi',
            [AbsensiController::class, 'indexAbsensiGlobal']
        )->name('admin.absensi.index');

        Route::get(
            '/proyek/{proyek}/detail-absensi',
            [AbsensiController::class, 'detailAbsensi']
        )->name('admin.absensi.detail');

        Route::post('absensi/setujui/{proyek}/{tanggal}', [AbsensiController::class, 'setujuiAbsensi'])
            ->where('tanggal', '\d{4}-\d{2}-\d{2}')
            ->name('admin.absensi.setujui');

        Route::post('absensi/tolak/{proyek}/{tanggal}', [AbsensiController::class, 'tolakAbsensi'])
            ->where('tanggal', '\d{4}-\d{2}-\d{2}')
            ->name('admin.absensi.tolak');


        /*
        |--------------------------------------------------------------------------
        | Manajemen Pegawai
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/manajemen-pegawai',
            [PegawaiController::class, 'index']
        )->name('pegawai.index');

        Route::post(
            '/manajemen-pegawai',
            [PegawaiController::class, 'store']
        )->name('pegawai.store');

        Route::put(
            '/manajemen-pegawai/{pegawai}',
            [PegawaiController::class, 'update']
        )->name('pegawai.update');

        Route::delete(
            '/manajemen-pegawai/{pegawai}',
            [PegawaiController::class, 'destroy']
        )->name('pegawai.destroy');


        /*
        |--------------------------------------------------------------------------
        | Manajemen Proyek
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/manajemen-proyek',
            [ProyekController::class, 'index']
        )->name('proyek.index');

        Route::post(
            '/manajemen-proyek',
            [ProyekController::class, 'store']
        )->name('proyek.store');

        Route::put(
            '/manajemen-proyek/{proyek}',
            [ProyekController::class, 'update']
        )->name('proyek.update');


        // Detail proyek
        Route::get(
            '/manajemen-proyek/detail/{proyek}',
            [ProyekController::class, 'show']
        )->name('proyek.show');


        // Riwayat laporan harian
        Route::get(
            '/proyek/{proyek}/riwayat-laporan',
            [LaporanHarianController::class, 'indexAdmin']
        )->name('proyek.laporan.admin');


        // Assign pegawai ke proyek
        Route::post(
            '/manajemen-proyek/detail/{proyek}/assign',
            [ProyekController::class, 'assignPegawai']
        )->name('proyek.assign');


        // Remove pegawai dari proyek
        Route::delete(
            '/manajemen-proyek/detail/{proyek}/remove/{pegawai}',
            [ProyekController::class, 'removePegawai']
        )->name('proyek.remove');
    });


    /*
    |--------------------------------------------------------------------------
    | Super Admin
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:super_admin')->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Manajemen Jabatan
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/manajemen-jabatan',
            [JabatanController::class, 'store']
        )->name('jabatan.store');

        Route::put(
            '/manajemen-jabatan/{jabatan}',
            [JabatanController::class, 'update']
        )->name('jabatan.update');

        Route::delete(
            '/manajemen-jabatan/{jabatan}',
            [JabatanController::class, 'destroy']
        )->name('jabatan.destroy');


        /*
        |--------------------------------------------------------------------------
        | Hapus Proyek
        |--------------------------------------------------------------------------
        */

        Route::delete(
            '/manajemen-proyek/{proyek}',
            [ProyekController::class, 'destroy']
        )->name('proyek.destroy');


        /*
        |--------------------------------------------------------------------------
        | Keuangan & Payroll
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/admin/proyek/{proyek}/payroll',
            [KeuanganProyekController::class, 'payrollIndex']
        )->name('proyek.payroll');

        Route::post(
            '/admin/proyek/{proyek}/payroll/bayar',
            [KeuanganProyekController::class, 'payrollStore']
        )->name('proyek.payroll.store');

        Route::get(
            '/admin/proyek/{proyek}/keuangan',
            [KeuanganProyekController::class, 'index']
        )->name('proyek.keuangan');

        Route::post(
            '/admin/proyek/{proyek}/kasbon',
            [KasbonController::class, 'store']
        )->name('kasbon.store');

        Route::post(
            '/admin/proyek/{proyek}/keuangan/store',
            [KeuanganProyekController::class, 'store']
        )->name('proyek.keuangan.store');

        Route::post(
            '/admin/proyek/{proyek}/keuangan/preview-gaji',
            [KeuanganProyekController::class, 'previewGaji']
        )->name('proyek.keuangan.preview-gaji');

        Route::post(
            '/admin/proyek/{proyek}/keuangan/bayar-gaji',
            [KeuanganProyekController::class, 'bayarGaji']
        )->name('proyek.keuangan.bayar-gaji');

        // PDF laporan keuangan
        Route::get(
            '/proyek/{proyek}/keuangan/pdf',
            [KeuanganProyekController::class, 'unduhPdf']
        )->name('keuangan.pdf');


        Route::put(
            '/admin/proyek/{proyek}/keuangan/{transaksi}',
            [KeuanganProyekController::class, 'update']
        )->name('proyek.keuangan.update');
    });



    /*
|--------------------------------------------------------------------------
| Pengawas
|--------------------------------------------------------------------------
*/

    Route::middleware('role:pengawas')->group(function () {

        /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

        Route::get(
            '/pengawas/dashboard',
            [PengawasController::class, 'dashboard']
        )->name('pengawas.dashboard');


        /*
    |--------------------------------------------------------------------------
    | Riwayat Absensi
    |--------------------------------------------------------------------------
    */

        Route::get(
            '/pengawas/riwayat-absensi',
            [PengawasController::class, 'riwayatAbsensi']
        )->name('pengawas.riwayat.absensi');


        /*
    |--------------------------------------------------------------------------
    | Absensi Pagi
    |--------------------------------------------------------------------------
    */

        Route::get(
            '/proyek/{proyek}/absensi',
            [AbsensiController::class, 'create']
        )->name('absensi.create');

        Route::post(
            '/proyek/{proyek}/absensi',
            [AbsensiController::class, 'store']
        )->name('absensi.store');


        /*
    |--------------------------------------------------------------------------
    | Absensi Sore
    |--------------------------------------------------------------------------
    */

        Route::post(
            '/absensi/{proyek}/sore',
            [AbsensiController::class, 'storeSore']
        )->name('absensi.storeSore');

        /*
    |--------------------------------------------------------------------------
    | Absensi Tertunda
    |--------------------------------------------------------------------------
    */
        Route::get('/pengawas/absensi-tertunda', [
            AbsensiController::class,
            'indexAbsensiTertunda',
        ])->name('pengawas.absensi-tertunda');

        Route::get('/pengawas/absensi/{proyek}/sore-susulan/{laporan}', [
            AbsensiController::class,
            'editSoreSusulan',
        ])->name('pengawas.absensi.sore-susulan.edit');

        Route::post('/pengawas/absensi/{proyek}/sore-susulan/{laporan}', [
            AbsensiController::class,
            'storeSore',
        ])->name('pengawas.absensi.sore-susulan.store');
    });
});
