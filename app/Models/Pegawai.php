<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Pegawai extends Model
{
    protected $fillable = [
        'nama',
        'no_telp',
        'jabatan_id',
        'user_id',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function jabatan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function proyeks(): BelongsToMany
    {
        return $this->belongsToMany(
            Proyek::class,
            'pegawai_proyek',
            'pegawai_id',
            'proyek_id'
        );
    }
}
