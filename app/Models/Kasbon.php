<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kasbon extends Model
{
    use HasFactory;

    protected $fillable = [
        'pegawai_id',
        'proyek_id',
        'tanggal',
        'nominal',
        'keterangan',
        'status'
    ];

    // Relasi ke Pekerja
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    // Relasi ke Proyek
    public function proyek()
    {
        return $this->belongsTo(Proyek::class);
    }
}
