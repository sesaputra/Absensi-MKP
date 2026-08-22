<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absensi extends Model
{
    protected $fillable = [
        'laporan_harian_id',
        'proyek_id',
        'pegawai_id',
        'tanggal',
        'status',
        'durasi',
        'keterangan',
        'status_validasi',
        'status_pembayaran'
    ];

    // Relasi: Absensi ini milik Proyek apa?
    public function proyek(): BelongsTo
    {
        return $this->belongsTo(Proyek::class);
    }

    // Relasi: Absensi ini milik Pegawai siapa?
    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function laporanHarian()
    {
        return $this->belongsTo(LaporanHarian::class, 'laporan_harian_id');
    }
}
