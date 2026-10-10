<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KeuanganProyek extends Model
{
    use HasFactory;

    protected $table = 'keuangan_proyeks';

    protected $fillable = [
        'proyek_id',
        'kasbon_id',
        'tipe',
        'kategori',
        'nominal',
        'tanggal',
        'keterangan',
        'bukti_file',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'tanggal' => 'date',
    ];

    /**
     * Transaksi ini milik proyek apa?
     */
    public function proyek(): BelongsTo
    {
        return $this->belongsTo(Proyek::class);
    }
    public function kasbon(): BelongsTo
    {
        return $this->belongsTo(
            Kasbon::class,
            'kasbon_id'
        );
    }
}
