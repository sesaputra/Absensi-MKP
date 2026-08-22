<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon; // Pastikan import Carbon

class Proyek extends Model
{
    protected $fillable = [
        'nama_proyek',
        'nama_pemilik',
        'kontak_pemilik',
        'lokasi',
        'tanggal_mulai',
        'estimasi_selesai',
        'anggaran',
        'gambar', 
        'status',
    ];
    // Menambahkan Accessor untuk mendapatkan status yang "hidup"
    public function getStatusAttribute($value)
    {
        $hariIni = Carbon::now();
        $mulai = Carbon::parse($this->tanggal_mulai);

        // Jika status di DB adalah 'Akan Dimulai' tapi tanggalnya sudah hari ini atau lewat
        if ($value == 'Akan Dimulai' && $hariIni->greaterThanOrEqualTo($mulai)) {
            return 'Berjalan'; // Sistem menganggapnya sedang berjalan
        }

        return $value;
    }

    public function pegawais()
    {
        // Memberitahu Laravel bahwa 1 Proyek punya Banyak Pegawai
        // melalui tabel perantara 'pegawai_proyek'
        return $this->belongsToMany(Pegawai::class, 'pegawai_proyek');
    }
    // Satu proyek punya banyak rekapan absen harian
    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }

    // Satu proyek punya banyak laporan harian
    public function laporanHarians()
    {
        return $this->hasMany(LaporanHarian::class);
    }

    public function itemPekerjaans()
    {
        return $this->hasMany(ItemPekerjaan::class, 'proyek_id');
    }
    public function keuangans()
    {
        // Kita urutkan transaksi dari tanggal terbaru agar Admin mudah membaca
        return $this->hasMany(KeuanganProyek::class, 'proyek_id')->orderBy('tanggal', 'desc');
    }
}
