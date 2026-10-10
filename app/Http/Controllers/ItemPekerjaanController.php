<?php

namespace App\Http\Controllers;

use App\Models\ItemPekerjaan;
use App\Models\Proyek;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ItemPekerjaanController extends Controller
{
    /**
     * Memastikan user memiliki hak akses admin.
     */
    private function authorizeAdmin(): void
    {
        abort_unless(
            Auth::check() &&
                in_array(Auth::user()->role, ['admin', 'super_admin']),
            403
        );
    }

    /**
     * Menyimpan item pekerjaan baru.
     */
    public function store(
        Request $request,
        Proyek $proyek
    ): RedirectResponse {
        $this->authorizeAdmin();

        $data = $request->validate([
            'nama_pekerjaan' => [
                'required',
                'array',
                'min:1',
            ],

            'nama_pekerjaan.*' => [
                'required',
                'string',
                'max:255',
            ],

            'bobot' => [
                'required',
                'array',
                'min:1',
            ],

            'bobot.*' => [
                'required',
                'numeric',
                'min:0.1',
                'max:100',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Pastikan jumlah nama dan bobot sama
        |--------------------------------------------------------------------------
        */

        if (
            count($data['nama_pekerjaan']) !==
            count($data['bobot'])
        ) {
            return back()->with(
                'error',
                'Data pekerjaan tidak valid. Jumlah pekerjaan dan bobot harus sama.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Hitung total bobot
        |--------------------------------------------------------------------------
        */

        $totalBobotSaatIni = (float) $proyek
            ->itemPekerjaans()
            ->sum('bobot');

        $totalBobotBaru = array_sum(
            array_map('floatval', $data['bobot'])
        );

        $totalBobotSetelahTambah =
            $totalBobotSaatIni + $totalBobotBaru;

        if ($totalBobotSetelahTambah > 100) {
            $sisaBobot = max(
                0,
                100 - $totalBobotSaatIni
            );

            return back()->with(
                'error',
                'Gagal menambahkan item pekerjaan. '
                    . 'Sisa bobot proyek hanya '
                    . number_format($sisaBobot, 2)
                    . '%, sedangkan total bobot yang dimasukkan adalah '
                    . number_format($totalBobotBaru, 2)
                    . '%.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Simpan seluruh item dalam satu transaksi
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use ($data, $proyek) {
            foreach ($data['nama_pekerjaan'] as $index => $nama) {
                ItemPekerjaan::create([
                    'proyek_id' => $proyek->id,
                    'nama_pekerjaan' => trim($nama),
                    'bobot' => $data['bobot'][$index],
                    'progres_sekarang' => 0,
                ]);
            }
        });

        return back()->with(
            'success',
            count($data['nama_pekerjaan'])
                . ' item pekerjaan berhasil ditambahkan.'
        );
    }

    /**
     * Memperbarui item pekerjaan.
     */
    public function update(
        Request $request,
        ItemPekerjaan $itemPekerjaan
    ): RedirectResponse {
        $this->authorizeAdmin();

        $data = $request->validate([
            'nama_pekerjaan' => [
                'required',
                'string',
                'max:255',
            ],

            'bobot' => [
                'required',
                'numeric',
                'min:0.1',
                'max:100',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Hitung total bobot item lain
        |--------------------------------------------------------------------------
        */

        $totalBobotLain = ItemPekerjaan::query()
            ->where('proyek_id', $itemPekerjaan->proyek_id)
            ->whereKeyNot($itemPekerjaan->id)
            ->sum('bobot');

        $bobotBaru = (float) $data['bobot'];

        if (($totalBobotLain + $bobotBaru) > 100) {
            $sisaBobot = max(
                0,
                100 - $totalBobotLain
            );

            return back()->with(
                'error',
                'Gagal memperbarui item pekerjaan. '
                    . 'Bobot maksimal untuk item ini adalah '
                    . number_format($sisaBobot, 2)
                    . '%.'
            );
        }

        $itemPekerjaan->update([
            'nama_pekerjaan' => trim($data['nama_pekerjaan']),
            'bobot' => $bobotBaru,
        ]);

        return back()->with(
            'success',
            'Data item pekerjaan berhasil diperbarui.'
        );
    }

    /**
     * Menghapus item pekerjaan.
     */
    public function destroy(
        ItemPekerjaan $itemPekerjaan
    ): RedirectResponse {
        $this->authorizeAdmin();

        /*
        |--------------------------------------------------------------------------
        | Jangan hapus pekerjaan yang sudah memiliki progres
        |--------------------------------------------------------------------------
        */

        if ((float) $itemPekerjaan->progres_sekarang > 0) {
            return back()->with(
                'error',
                'Item pekerjaan tidak dapat dihapus karena sudah memiliki progres pekerjaan.'
            );
        }

        $itemPekerjaan->delete();

        return back()->with(
            'success',
            'Item pekerjaan berhasil dihapus.'
        );
    }
}
