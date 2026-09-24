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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function jabatan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }

    // Relasi: Satu Pegawai bisa terlibat di banyak Proyek
    public function proyeks(): BelongsToMany
    {
        return $this->belongsToMany(Proyek::class, 'pegawai_proyek');
    }
}
