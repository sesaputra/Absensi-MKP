<?php

use App\Http\Controllers\AbsensiController;
use Illuminate\Support\Facades\Route;
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

// Rute Publik (Bisa diakses tanpa login)
Route::get('/', function () {
    return view('auth.login');
});
Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:admin,super_admin'])->group(function () {
    // Operasional harian — boleh diakses keduanya
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('/proyek/{proyek}/item-pekerjaan', [ItemPekerjaanController::class, 'store'])->name('item-pekerjaan.store');
    Route::put('/item-pekerjaan/{itemPekerjaan}', [ItemPekerjaanController::class, 'update'])->name('item-pekerjaan.update');
    Route::delete('/item-pekerjaan/{itemPekerjaan}', [ItemPekerjaanController::class, 'destroy'])->name('item-pekerjaan.destroy');

    Route::get('/manajemen-absensi', [AbsensiController::class, 'indexAbsensiGlobal'])->name('admin.absensi.index');
    Route::get('/proyek/{proyek}/detail-absensi', [AbsensiController::class, 'detailAbsensi'])->name('admin.absensi.detail');
    Route::post('/absensi/setujui/{proyek_id}/{tanggal}', [AbsensiController::class, 'setujuiAbsensi'])->name('admin.absensi.setujui');
    Route::post('/absensi/tolak/{proyek_id}/{tanggal}', [AbsensiController::class, 'tolakAbsensi'])->name('admin.absensi.tolak');
    Route::post('/absensi/{proyek}/sore', [AbsensiController::class, 'storeSore'])->name('absensi.storeSore');

    Route::get('/manajemen-pegawai', [PegawaiController::class, 'index'])->name('pegawai.index');
    Route::post('/manajemen-pegawai', [PegawaiController::class, 'store'])->name('pegawai.store');
    Route::put('/manajemen-pegawai/{pegawai}', [PegawaiController::class, 'update'])->name('pegawai.update');
    Route::delete('/manajemen-pegawai/{pegawai}', [PegawaiController::class, 'destroy'])->name('pegawai.destroy');

    Route::get('/manajemen-proyek', [ProyekController::class, 'index'])->name('proyek.index');
    Route::post('/manajemen-proyek', [ProyekController::class, 'store'])->name('proyek.store');
    Route::put('/manajemen-proyek/{proyek}', [ProyekController::class, 'update'])->name('proyek.update');
    // proyek.destroy DIPINDAH ke grup super_admin di bawah

    Route::get('/proyek/{proyek}/riwayat-laporan', [LaporanHarianController::class, 'indexAdmin'])->name('proyek.laporan.admin');
    Route::get('/manajemen-proyek/detail/{proyek}', [ProyekController::class, 'show'])->name('proyek.show');
    Route::post('/manajemen-proyek/detail/{proyek}/assign', [ProyekController::class, 'assignPegawai'])->name('proyek.assign');
    Route::delete('/manajemen-proyek/detail/{proyek}/remove/{pegawai}', [ProyekController::class, 'removePegawai'])->name('proyek.remove');
});

Route::middleware(['auth', 'role:super_admin'])->group(function () {
    // Kategori jabatan & gaji — basis perhitungan payroll
    Route::post('/manajemen-jabatan', [JabatanController::class, 'store'])->name('jabatan.store');
    Route::put('/manajemen-jabatan/{jabatan}', [JabatanController::class, 'update'])->name('jabatan.update');
    Route::delete('/manajemen-jabatan/{jabatan}', [JabatanController::class, 'destroy'])->name('jabatan.destroy');

    // Aksi destruktif
    Route::delete('/manajemen-proyek/{proyek}', [ProyekController::class, 'destroy'])->name('proyek.destroy');

    // Seluruh modul keuangan
    Route::get('/admin/proyek/{proyek}/payroll', [KeuanganProyekController::class, 'payrollIndex'])->name('proyek.payroll');
    Route::post('/admin/proyek/{proyek}/payroll/bayar', [KeuanganProyekController::class, 'payrollStore'])->name('proyek.payroll.store');
    Route::get('/admin/proyek/{proyek}/keuangan', [KeuanganProyekController::class, 'index'])->name('proyek.keuangan');
    Route::post('/admin/proyek/{proyek}/kasbon', [KasbonController::class, 'store'])->name('kasbon.store');
    Route::post('/admin/proyek/{proyek}/keuangan/store', [KeuanganProyekController::class, 'store'])->name('proyek.keuangan.store');
    Route::post('/admin/proyek/{proyek}/keuangan/preview-gaji', [KeuanganProyekController::class, 'previewGaji'])->name('proyek.keuangan.preview-gaji');
    Route::post('/admin/proyek/{proyek}/keuangan/bayar-gaji', [KeuanganProyekController::class, 'bayarGaji'])->name('proyek.keuangan.bayar-gaji');
    Route::get('/proyek/{id}/keuangan/pdf', [KeuanganProyekController::class, 'unduhPdf'])->name('keuangan.pdf');
});

Route::middleware(['auth', 'role:pengawas'])->group(function () {
    Route::get('/pengawas/dashboard', [PengawasController::class, 'dashboard'])->name('pengawas.dashboard');
    Route::get('/proyek/{proyek}/absensi', [AbsensiController::class, 'create'])->name('absensi.create');
    Route::post('/proyek/{proyek}/absensi', [AbsensiController::class, 'store'])->name('absensi.store');
});
