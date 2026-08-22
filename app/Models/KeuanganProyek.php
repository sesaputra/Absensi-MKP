<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeuanganProyek extends Model
{
    use HasFactory;

    // Definisikan nama tabel (jika berbeda dengan bentuk jamak bahasa inggris)
    protected $table = 'keuangan_proyeks';

    // Kolom yang boleh diisi secara massal
    protected $fillable = [
        'proyek_id',
        'tipe',
        'kategori',
        'nominal',
        'tanggal',
        'keterangan',
        'bukti_file'
    ];

    /**
     * Relasi Balik ke Proyek (One-to-Many Inverse)
     * Setiap 1 transaksi keuangan, PASTI dimiliki oleh 1 Proyek.
     */
    public function proyek()
    {
        return $this->belongsTo(Proyek::class, 'proyek_id');
    }
}
