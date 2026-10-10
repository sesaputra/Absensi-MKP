<?php

namespace App\Http\Controllers;

use App\Models\Kasbon;
use App\Models\KeuanganProyek;
use App\Models\Pegawai;
use App\Models\Proyek;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KasbonController extends Controller
{
    /**
     * Pastikan hanya admin dan super admin
     * yang dapat mengelola kasbon.
     */
    private function authorizeAdmin(): void
    {
        abort_unless(
            Auth::check() &&
                in_array(
                    Auth::user()->role,
                    ['admin', 'super_admin'],
                    true
                ),
            403
        );
    }

    /**
     * Simpan kasbon baru.
     */
    public function store(
        Request $request,
        Proyek $proyek
    ): RedirectResponse {
        $this->authorizeAdmin();

        /*
        |--------------------------------------------------------------------------
        | 1. Pastikan proyek masih dapat menerima transaksi
        |--------------------------------------------------------------------------
        */

        if (!in_array(
            $proyek->status,
            ['Akan Dimulai', 'Berjalan'],
            true
        )) {
            return back()->with(
                'error',
                'Kasbon tidak dapat dicatat karena proyek sudah tidak aktif.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 2. Validasi input
        |--------------------------------------------------------------------------
        */

        $data = $request->validate([
            'pegawai_id' => [
                'required',
                'integer',
                'exists:pegawais,id',
            ],

            'nominal' => [
                'required',
                'numeric',
                'min:1',
            ],

            'tanggal' => [
                'required',
                'date',
            ],

            'keterangan' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 3. Pastikan tanggal transaksi valid terhadap periode proyek
        |--------------------------------------------------------------------------
        |
        | Jika proyek memiliki tanggal mulai, kasbon tidak boleh dicatat
        | sebelum proyek dimulai.
        |
        */

        if (
            $proyek->tanggal_mulai &&
            $data['tanggal'] < $proyek->tanggal_mulai->format('Y-m-d')
        ) {
            return back()->with(
                'error',
                'Tanggal kasbon tidak boleh sebelum tanggal mulai proyek.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 4. Pastikan pegawai aktif
        |--------------------------------------------------------------------------
        */

        $pegawai = Pegawai::query()
            ->whereKey($data['pegawai_id'])
            ->where('status', 'aktif')
            ->first();

        if (!$pegawai) {
            return back()->with(
                'error',
                'Pegawai tidak ditemukan atau sudah tidak aktif.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 5. Pastikan pegawai terdaftar pada proyek
        |--------------------------------------------------------------------------
        */

        $terdaftarDiProyek = $proyek
            ->pegawais()
            ->whereKey($pegawai->id)
            ->exists();

        if (!$terdaftarDiProyek) {
            return back()->with(
                'error',
                "Pegawai {$pegawai->nama} tidak terdaftar pada proyek ini."
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 6. Simpan kasbon + transaksi keuangan
        |--------------------------------------------------------------------------
        |
        | Kedua operasi HARUS berada dalam satu transaction.
        |
        | Jika salah satu gagal, keduanya dibatalkan.
        |
        */

        DB::transaction(function () use (
            $data,
            $proyek,
            $pegawai
        ) {
            /*
            |--------------------------------------------------------------------------
            | Buat kasbon
            |--------------------------------------------------------------------------
            */

            $kasbon = Kasbon::create([
                'proyek_id' => $proyek->id,
                'pegawai_id' => $pegawai->id,
                'tanggal' => $data['tanggal'],
                'nominal' => $data['nominal'],
                'sisa_nominal' => $data['nominal'],
                'keterangan' => $data['keterangan'] ?? null,
                'status' => 'Belum Lunas',
            ]);

            /*
            |--------------------------------------------------------------------------
            | Catat pencairan kasbon pada buku kas proyek
            |--------------------------------------------------------------------------
            |
            | kasbon_id menjadi foreign key langsung sehingga transaksi
            | keuangan dapat ditelusuri kembali ke kasbon asal.
            |
            */

            KeuanganProyek::create([
                'proyek_id' => $proyek->id,
                'kasbon_id' => $kasbon->id,
                'tipe' => 'Pengeluaran',
                'kategori' => 'Kasbon Tukang',
                'nominal' => $data['nominal'],
                'tanggal' => $data['tanggal'],
                'keterangan' =>
                    'Pencairan Kasbon a.n. ' .
                    $pegawai->nama .
                    (
                        !empty($data['keterangan'])
                            ? ' (' . $data['keterangan'] . ')'
                            : ''
                    ),
            ]);
        });

        /*
        |--------------------------------------------------------------------------
        | 7. Response
        |--------------------------------------------------------------------------
        */

        return back()->with(
            'success',
            'Kasbon berhasil dicatat dan dana kas proyek telah dipotong otomatis.'
        );
    }
}