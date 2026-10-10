<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    protected $casts = [
        'tanggal_mulai' => 'date',
        'estimasi_selesai' => 'date',
        'anggaran' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Pegawai
    |--------------------------------------------------------------------------
    */

    public function pegawais(): BelongsToMany
    {
        return $this->belongsToMany(
            Pegawai::class,
            'pegawai_proyek',
            'proyek_id',
            'pegawai_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Item Pekerjaan
    |--------------------------------------------------------------------------
    */

    public function itemPekerjaans(): HasMany
    {
        return $this->hasMany(
            ItemPekerjaan::class,
            'proyek_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Absensi
    |--------------------------------------------------------------------------
    */

    public function absensis(): HasMany
    {
        return $this->hasMany(
            Absensi::class,
            'proyek_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Laporan Harian
    |--------------------------------------------------------------------------
    */

    public function laporanHarians(): HasMany
    {
        return $this->hasMany(
            LaporanHarian::class,
            'proyek_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Keuangan
    |--------------------------------------------------------------------------
    */

    public function keuangans(): HasMany
    {
        return $this->hasMany(
            KeuanganProyek::class,
            'proyek_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Kasbon
    |--------------------------------------------------------------------------
    */

    public function kasbons(): HasMany
    {
        return $this->hasMany(
            Kasbon::class,
            'proyek_id'
        );
    }
}
