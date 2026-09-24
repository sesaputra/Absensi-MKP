<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JabatanController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_jabatan' => ['required', 'string', 'max:255'],
            'gaji_harian' => ['required', 'numeric', 'min:0'],
            'can_login' => ['nullable', 'boolean'],
        ]);

        $validated['can_login'] = $request->boolean('can_login');

        Jabatan::create($validated);

        return redirect()
            ->back()
            ->with('success', 'Kategori jabatan berhasil ditambahkan.');
    }

    // Menambahkan fungsi update
    public function update(Request $request, Jabatan $jabatan)
    {
        $validated = $request->validate([
            'nama_jabatan' => ['required', 'string', 'max:255'],
            'gaji_harian' => ['required', 'numeric', 'min:0'],
            'can_login' => ['nullable', 'boolean'],
        ]);

        $validated['can_login'] = $request->boolean('can_login');

        $jabatan->update($validated);

        return redirect()
            ->back()
            ->with('success', 'Kategori jabatan berhasil diperbarui.');
    }

    public function destroy(Jabatan $jabatan)
    {
        if (!Auth::check() || !in_array(Auth::user()->role, ['admin', 'super_admin'])) abort(403);

        $jabatan->delete();
        return back()->with('success', 'Kategori jabatan berhasil dihapus!');
    }
}
