<?php

namespace App\Http\Controllers;

use App\Models\Proyek;
use App\Models\ItemPekerjaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemPekerjaanController extends Controller
{
    // 1. Menyimpan Item Pekerjaan Baru
    public function store(Request $request, Proyek $proyek)
    {
        // Pastikan hanya admin yang bisa menambah
        if (!Auth::check() || Auth::user()->role !== 'admin') abort(403);

        // Validasi berbentuk Array karena inputan bisa lebih dari 1 baris
        $request->validate([
            'nama_pekerjaan' => 'required|array',
            'nama_pekerjaan.*' => 'required|string|max:255',
            'bobot' => 'required|array',
            'bobot.*' => 'required|numeric|min:0.1|max:100',
        ]);

        // Hitung total bobot lama dan total bobot baru yang diinput
        $totalBobotSaatIni = $proyek->itemPekerjaans()->sum('bobot');
        $totalBobotBaru = array_sum($request->bobot);
        $sisaBobot = 100 - $totalBobotSaatIni;

        // Jika bobot lama + total bobot baru melebihi 100%
        if (($totalBobotSaatIni + $totalBobotBaru) > 100) {
            return back()->with('error', "Gagal! Total bobot melebihi batas. Sisa kuota bobot proyek adalah {$sisaBobot}%, namun Anda mencoba memasukkan total {$totalBobotBaru}%.");
        }

        // Jika aman, lakukan perulangan untuk menyimpan semua baris sekaligus
        foreach ($request->nama_pekerjaan as $index => $nama) {
            ItemPekerjaan::create([
                'proyek_id' => $proyek->id,
                'nama_pekerjaan' => $nama,
                'bobot' => $request->bobot[$index],
                'progres_sekarang' => 0, // Awal mulai pasti progresnya 0%
            ]);
        }

        return back()->with('success', count($request->nama_pekerjaan) . ' Item pekerjaan berhasil ditambahkan!');
    }

    // 2. Mengubah Item Pekerjaan (Edit Bobot / Nama)
    public function update(Request $request, ItemPekerjaan $itemPekerjaan)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') abort(403);

        $request->validate([
            'nama_pekerjaan' => 'required|string|max:255',
            'bobot' => 'required|numeric|min:0.1|max:100',
        ]);

        // CEK KEAMANAN SAAT EDIT:
        // Total bobot proyek, DIKURANGI bobot lama item ini, DITAMBAH bobot baru
        $totalBobotLain = ItemPekerjaan::where('proyek_id', $itemPekerjaan->proyek_id)
            ->where('id', '!=', $itemPekerjaan->id)
            ->sum('bobot');

        $sisaBobot = 100 - $totalBobotLain;

        if (($totalBobotLain + $request->bobot) > 100) {
            return back()->with('error', "Gagal! Total bobot melebihi 100%. Maksimal bobot untuk item ini adalah {$sisaBobot}%.");
        }

        $itemPekerjaan->update([
            'nama_pekerjaan' => $request->nama_pekerjaan,
            'bobot' => $request->bobot,
        ]);

        return back()->with('success', 'Data item pekerjaan berhasil diperbarui!');
    }

    // 3. Menghapus Item Pekerjaan
    public function destroy(ItemPekerjaan $itemPekerjaan)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') abort(403);

        $itemPekerjaan->delete();

        return back()->with('success', 'Item pekerjaan berhasil dihapus!');
    }
}
