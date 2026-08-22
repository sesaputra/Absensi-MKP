<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanHarian extends Model
{
    protected $fillable = [
        'proyek_id',
        'pegawai_id',
        'tanggal',
        'foto_pagi',
        'foto_sore',
        'kegiatan',
        'status_validasi',
        'latitude',      // <-- Tambahan
        'longitude',     // <-- Tambahan
        'waktu_absen',   // <-- Tambahan
    ];

    public function proyek()
    {
        return $this->belongsTo(Proyek::class);
    }
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }
    public function absensis()
    {
        return $this->hasMany(Absensi::class, 'laporan_harian_id');
    }
    public function pembuatLaporan()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }
}
