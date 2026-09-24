<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function pegawais()
    {
        return $this->hasMany(Pegawai::class, 'jabatan_id');
    }
}
