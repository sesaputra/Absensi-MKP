<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemPekerjaan extends Model
{
    protected $fillable = [
        'proyek_id',
        'nama_pekerjaan',
        'bobot',
        'progres_sekarang',
    ];

    protected $casts = [
        'bobot' => 'decimal:2',
        'progres_sekarang' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Proyek
    |--------------------------------------------------------------------------
    */

    public function proyek(): BelongsTo
    {
        return $this->belongsTo(
            Proyek::class,
            'proyek_id'
        );
    }
}
