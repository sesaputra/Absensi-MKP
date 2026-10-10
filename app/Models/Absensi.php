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
        'status_pembayaran',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    /**
     * Absensi ini tercatat pada proyek apa?
     */
    public function proyek(): BelongsTo
    {
        return $this->belongsTo(Proyek::class);
    }

    /**
     * Absensi ini milik pegawai siapa?
     */
    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class);
    }

    /**
     * Absensi ini tercatat dalam laporan harian apa?
     */
   public function laporanHarian()
{
    return $this->belongsTo(
        LaporanHarian::class,
        'laporan_harian_id'
    );
}
}
