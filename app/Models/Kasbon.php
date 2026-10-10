<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\KeuanganProyek;

class Kasbon extends Model
{
    protected $fillable = [
        'pegawai_id',
        'proyek_id',
        'tanggal',
        'nominal',
        'sisa_nominal',
        'keterangan',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'nominal' => 'decimal:2',
        'sisa_nominal' => 'decimal:2',
    ];

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(
            Pegawai::class,
            'pegawai_id'
        );
    }

    public function proyek(): BelongsTo
    {
        return $this->belongsTo(
            Proyek::class,
            'proyek_id'
        );
    }
    public function keuangan(): HasOne
    {
        return $this->hasOne(
            KeuanganProyek::class,
            'kasbon_id'
        );
    }
}
