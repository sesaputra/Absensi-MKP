<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemPekerjaan extends Model
{
    protected $fillable = [
        'proyek_id',
        'nama_pekerjaan',
        'bobot',
        'progres_sekarang'
    ];

    public function proyek()
    {
        return $this->belongsTo(Proyek::class);
    }
}
