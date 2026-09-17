<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JabatanController extends Controller
{
    public function store(Request $request)
    {
        if (!Auth::check() || !in_array(Auth::user()->role, ['admin', 'super_admin'])) abort(403);

        $request->validate([
            'nama_jabatan' => 'required|string|max:255',
            'gaji_harian'  => 'required|numeric|min:0',
        ]);

        Jabatan::create($request->all());
        return back()->with('success', 'Kategori jabatan dan gaji berhasil ditambahkan!');
    }

    // Menambahkan fungsi update
    public function update(Request $request, Jabatan $jabatan)
    {
        // Pastikan hanya admin
        if (!Auth::check() || !in_array(Auth::user()->role, ['admin', 'super_admin'])) abort(403);

        // Validasi data
        $request->validate([
            'nama_jabatan' => 'required|string|max:255',
            'gaji_harian'  => 'required|numeric|min:0',
        ]);

        // Simpan pembaruan ke database
        $jabatan->update([
            'nama_jabatan' => $request->nama_jabatan,
            'gaji_harian' => $request->gaji_harian,
        ]);

        return back()->with('success', 'Kategori jabatan & gaji berhasil diperbarui!');
    }

    public function destroy(Jabatan $jabatan)
    {
        if (!Auth::check() || !in_array(Auth::user()->role, ['admin', 'super_admin'])) abort(403);

        $jabatan->delete();
        return back()->with('success', 'Kategori jabatan berhasil dihapus!');
    }
}
