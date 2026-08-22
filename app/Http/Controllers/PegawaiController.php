<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PegawaiController extends Controller
{
    public function index()
    {
        $pegawais = Pegawai::with(['user', 'jabatan'])->latest()->get();

        $jabatans = Jabatan::latest()->get();

        return view('Admin.manajemen-pegawai', compact('pegawais', 'jabatans'));
    }

    public function store(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki hak akses.');
        }

        // 1. UBAH VALIDASI
        $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan_id' => 'required|exists:jabatans,id', // Harus berupa ID yang ada di tabel jabatans
            'no_telp' => 'required|string|max:20',
        ]);

        // 2. CEK NAMA JABATAN DARI DATABASE
        $jabatan = Jabatan::find($request->jabatan_id);
        $namaJabatan = strtolower($jabatan->nama_jabatan); // Misal: "pengawas" atau "tukang"

        $userId = null;

        // 3. UBAH LOGIKA PENGECEKAN ROLE
        if (in_array($namaJabatan, ['pengawas', 'mandor'])) {
            $request->validate([
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6'
            ]);

            $user = User::create([
                'name' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'pengawas',
            ]);

            $userId = $user->id;
        }

        // 4. SIMPAN DENGAN JABATAN_ID
        Pegawai::create([
            'user_id' => $userId,
            'nama' => $request->nama,
            'jabatan_id' => $request->jabatan_id,
            'no_telp' => $request->no_telp,
        ]);

        return back()->with('success', 'Pegawai berhasil ditambahkan!');
    }

    public function update(Request $request, Pegawai $pegawai)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') abort(403);

        // 1. UBAH VALIDASI
        $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan_id' => 'required|exists:jabatans,id',
            'no_telp' => 'required|string|max:20',
        ]);

        // 2. CEK NAMA JABATAN DARI DATABASE
        $jabatan = Jabatan::find($request->jabatan_id);
        $namaJabatan = strtolower($jabatan->nama_jabatan);

        // 3. UBAH LOGIKA PENGECEKAN ROLE
        $butuhLogin = in_array($namaJabatan, ['pengawas', 'mandor']);

        if ($butuhLogin) {
            if ($pegawai->user_id) {
                // Skenario A: Dia sudah punya akun, kita update datanya
                $request->validate([
                    'email' => 'required|email|unique:users,email,' . $pegawai->user_id,
                    'password' => 'nullable|min:6'
                ]);

                $user = User::find($pegawai->user_id);
                $user->name = $request->nama;
                $user->email = $request->email;
                if ($request->filled('password')) {
                    $user->password = Hash::make($request->password);
                }
                $user->save();
            } else {
                // Skenario B: Dia Tukang yang naik jabatan jadi Mandor (Buatkan akun baru)
                $request->validate([
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required|min:6'
                ]);

                $user = User::create([
                    'name' => $request->nama,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role' => 'pengawas',
                ]);
                $pegawai->user_id = $user->id;
            }
        } else {
            // Skenario C: Dia turun jabatan jadi Tukang. Hapus akun loginnya jika ada.
            if ($pegawai->user_id) {
                User::find($pegawai->user_id)->delete();
                $pegawai->user_id = null;
            }
        }

        // 4. UPDATE DENGAN JABATAN_ID
        $pegawai->update([
            'nama' => $request->nama,
            'jabatan_id' => $request->jabatan_id,
            'no_telp' => $request->no_telp,
        ]);

        return back()->with('success', 'Data pegawai berhasil diperbarui!');
    }

    public function destroy(Pegawai $pegawai)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki hak akses.');
        }

        if ($pegawai->user_id) {
            User::find($pegawai->user_id)->delete();
        } else {
            $pegawai->delete();
        }

        return back()->with('success', 'Pegawai berhasil dihapus!');
    }
}
