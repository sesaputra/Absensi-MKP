<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PegawaiController extends Controller
{
    public function index()
{
    $pegawais = Pegawai::with(['user', 'jabatan'])
        ->latest()
        ->get();

    $jabatans = Jabatan::latest()->get();

    // Statistik Pegawai
    $totalPegawai = $pegawais->count();

    $pegawaiAktif = $pegawais
        ->where('status', 'aktif')
        ->count();

    $pegawaiNonaktif = $pegawais
        ->where('status', 'nonaktif')
        ->count();

    // Statistik Jabatan
    $totalJabatan = $jabatans->count();

    return view(
        'Admin.manajemen-pegawai',
        compact(
            'pegawais',
            'jabatans',
            'totalPegawai',
            'pegawaiAktif',
            'pegawaiNonaktif',
            'totalJabatan'
        )
    );
}

    public function store(Request $request)
    {
        if (
            !Auth::check() ||
            !in_array(Auth::user()->role, ['admin', 'super_admin'])
        ) {
            abort(403, 'Anda tidak memiliki hak akses.');
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan_id' => 'required|exists:jabatans,id',
            'no_telp' => 'required|string|max:20',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $jabatan = Jabatan::findOrFail($request->jabatan_id);

        DB::transaction(function () use ($request, $jabatan) {

            $userId = null;

            /*
            |--------------------------------------------------------------------------
            | CEK APAKAH JABATAN BOLEH LOGIN
            |--------------------------------------------------------------------------
            */

            if ($jabatan->can_login) {

                $request->validate([
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required|string|min:6',
                ]);

                $user = User::create([
                    'name' => $request->nama,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role' => 'pengawas',
                ]);

                $userId = $user->id;
            }

            /*
            |--------------------------------------------------------------------------
            | SIMPAN PEGAWAI
            |--------------------------------------------------------------------------
            */

            Pegawai::create([
                'user_id' => $userId,
                'nama' => $request->nama,
                'jabatan_id' => $request->jabatan_id,
                'no_telp' => $request->no_telp,
                'status' => $request->status,
            ]);
        });

        return back()->with(
            'success',
            'Pegawai berhasil ditambahkan!'
        );
    }

    public function update(Request $request, Pegawai $pegawai)
    {
        if (
            !Auth::check() ||
            !in_array(Auth::user()->role, ['admin', 'super_admin'])
        ) {
            abort(403, 'Anda tidak memiliki hak akses.');
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan_id' => 'required|exists:jabatans,id',
            'no_telp' => 'required|string|max:20',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $jabatan = Jabatan::findOrFail($request->jabatan_id);

        DB::transaction(function () use ($request, $pegawai, $jabatan) {

            /*
            |--------------------------------------------------------------------------
            | JABATAN MEMBUTUHKAN AKUN LOGIN
            |--------------------------------------------------------------------------
            */

            if ($jabatan->can_login) {

                /*
                |--------------------------------------------------------------------------
                | PEGAWAI SUDAH MEMILIKI AKUN
                |--------------------------------------------------------------------------
                */

                if ($pegawai->user_id) {

                    $request->validate([
                        'email' => 'required|email|unique:users,email,' . $pegawai->user_id,
                        'password' => 'nullable|string|min:6',
                    ]);

                    $user = User::findOrFail($pegawai->user_id);

                    $user->name = $request->nama;
                    $user->email = $request->email;

                    if ($request->filled('password')) {
                        $user->password = Hash::make(
                            $request->password
                        );
                    }

                    $user->save();
                }

                /*
                |--------------------------------------------------------------------------
                | PEGAWAI BELUM MEMILIKI AKUN
                |--------------------------------------------------------------------------
                */ else {

                    $request->validate([
                        'email' => 'required|email|unique:users,email',
                        'password' => 'required|string|min:6',
                    ]);

                    $user = User::create([
                        'name' => $request->nama,
                        'email' => $request->email,
                        'password' => Hash::make($request->password),
                        'role' => 'pengawas',
                    ]);

                    $pegawai->user_id = $user->id;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | JABATAN TIDAK MEMBUTUHKAN AKUN LOGIN
            |--------------------------------------------------------------------------
            |
            | PENTING:
            | Kita TIDAK menghapus akun lama secara otomatis.
            |
            */ else {

                /*
                 * Untuk sementara user_id tetap dipertahankan.
                 *
                 * Alasannya:
                 * - histori tetap aman
                 * - akun tidak hilang
                 * - nanti kita bisa mengatur status akses secara terpisah
                 */
            }

            /*
            |--------------------------------------------------------------------------
            | UPDATE DATA PEGAWAI
            |--------------------------------------------------------------------------
            */

            $pegawai->update([
                'user_id' => $pegawai->user_id,
                'nama' => $request->nama,
                'jabatan_id' => $request->jabatan_id,
                'no_telp' => $request->no_telp,
                'status' => $request->status,
            ]);
        });

        return back()->with(
            'success',
            'Data pegawai berhasil diperbarui!'
        );
    }

    public function destroy(Pegawai $pegawai)
    {
        if (
            !Auth::check() ||
            !in_array(Auth::user()->role, ['admin', 'super_admin'])
        ) {
            abort(403, 'Anda tidak memiliki hak akses.');
        }

        DB::transaction(function () use ($pegawai) {

            /*
            |--------------------------------------------------------------------------
            | HAPUS AKUN USER
            |--------------------------------------------------------------------------
            |
            | Penghapusan akun hanya dilakukan ketika pegawai
            | benar-benar dihapus.
            |
            */

            if ($pegawai->user_id) {
                User::find($pegawai->user_id)?->delete();
            }

            $pegawai->delete();
        });

        return back()->with(
            'success',
            'Pegawai berhasil dihapus!'
        );
    }
}
