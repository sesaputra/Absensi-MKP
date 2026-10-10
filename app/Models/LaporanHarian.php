<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'progres_snapshot',
        'latitude',
        'longitude',
        'waktu_absen',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'progres_snapshot' => 'array',
        'waktu_absen' => 'datetime',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }

    public function proyek()
    {
        return $this->belongsTo(Proyek::class, 'proyek_id');
    }

    public function absensis(): HasMany
    {
        return $this->hasMany(Absensi::class, 'laporan_harian_id');
    }
}
