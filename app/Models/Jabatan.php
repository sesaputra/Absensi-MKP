<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jabatan extends Model
{
    protected $fillable = [
        'nama_jabatan',
        'gaji_harian',
        'can_login',
    ];

    protected $casts = [
        'gaji_harian' => 'integer',
        'can_login' => 'boolean',
    ];

    public function pegawais(): HasMany
    {
        return $this->hasMany(Pegawai::class);
    }

    /**
     * Apakah jabatan ini boleh memiliki akun login.
     * Jabatan "Pengawas" selalu boleh, sisanya mengikuti kolom can_login.
     */
    public function allowsLogin(): bool
    {
        return $this->can_login
            || strtolower(trim($this->nama_jabatan)) === 'pengawas';
    }
}