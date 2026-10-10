<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PegawaiController extends Controller
{
    /**
     * Role yang boleh mengelola pegawai.
     */
    private const ALLOWED_ROLES = ['admin', 'super_admin'];

    private function authorizeManage(): void
    {
        if (
            !Auth::check() ||
            !in_array(Auth::user()->role, self::ALLOWED_ROLES)
        ) {
            abort(403, 'Anda tidak memiliki hak akses.');
        }
    }

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
        $this->authorizeManage();

        $validated = $request->validate([
            'nama'       => ['required', 'string', 'max:255'],
            'jabatan_id' => ['required', 'exists:jabatans,id'],
            'no_telp'    => ['nullable', 'string', 'max:20'],
            'status'     => ['required', 'in:aktif,nonaktif'],
        ]);

        $jabatan = Jabatan::findOrFail($validated['jabatan_id']);

        // Validasi akun dilakukan SEBELUM transaksi dimulai.
        // Email & password wajib hanya jika jabatan boleh login.
        if ($jabatan->allowsLogin()) {
            $request->validate([
                'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
                'password' => ['required', 'string', 'min:8'],
            ]);
        }

        DB::transaction(function () use ($request, $validated, $jabatan) {

            $userId = null;

            if ($jabatan->allowsLogin()) {
                $user = User::create([
                    'name'     => $validated['nama'],
                    'email'    => $request->email,
                    'password' => Hash::make($request->password),
                    'role'     => 'pengawas',
                ]);

                $userId = $user->id;
            }

            Pegawai::create([
                'user_id'    => $userId,
                'nama'       => $validated['nama'],
                'jabatan_id' => $validated['jabatan_id'],
                'no_telp'    => $validated['no_telp'] ?? null,
                'status'     => $validated['status'],
            ]);
        });

        return back()->with('success', 'Pegawai berhasil ditambahkan!');
    }

    public function update(Request $request, Pegawai $pegawai)
    {
        $this->authorizeManage();

        $validated = $request->validate([
            'nama'       => ['required', 'string', 'max:255'],
            'jabatan_id' => ['required', 'exists:jabatans,id'],
            'no_telp'    => ['nullable', 'string', 'max:20'],
            'status'     => ['required', 'in:aktif,nonaktif'],
        ]);

        $jabatan = Jabatan::findOrFail($validated['jabatan_id']);

        /*
        |--------------------------------------------------------------------------
        | VALIDASI AKUN (sebelum transaksi)
        |--------------------------------------------------------------------------
        | - Pegawai sudah punya akun  : email wajib, password opsional.
        | - Pegawai belum punya akun
        |   dan jabatan boleh login   : email & password wajib (akun baru).
        | - Selain itu                : tidak ada akun yang diproses.
        */
        $hasAccount = !empty($pegawai->user_id);

        if ($hasAccount) {
            $request->validate([
                'email' => [
                    'required',
                    'email',
                    'max:255',
                    'unique:users,email,' . $pegawai->user_id,
                ],
                'password' => ['nullable', 'string', 'min:8'],
            ]);
        } elseif ($jabatan->allowsLogin()) {
            $request->validate([
                'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
                'password' => ['required', 'string', 'min:8'],
            ]);
        }

        DB::transaction(function () use ($request, $pegawai, $jabatan, $validated, $hasAccount) {

            if ($hasAccount) {
                // 1. Akun lama: tetap bisa diperbarui walau jabatan baru tidak can_login.
                $user = User::findOrFail($pegawai->user_id);

                $user->name  = $validated['nama'];
                $user->email = $request->email;

                // Password kosong = tidak mengubah password lama.
                if ($request->filled('password')) {
                    $user->password = Hash::make($request->password);
                }

                $user->save();
            } elseif ($jabatan->allowsLogin()) {
                // 2. Belum punya akun & jabatan baru boleh login (mis. Tukang -> Pengawas).
                $user = User::create([
                    'name'     => $validated['nama'],
                    'email'    => $request->email,
                    'password' => Hash::make($request->password),
                    'role'     => 'pengawas',
                ]);

                $pegawai->user_id = $user->id;
            }

            // 3. Simpan data pegawai.
            $pegawai->nama       = $validated['nama'];
            $pegawai->jabatan_id = $validated['jabatan_id'];
            $pegawai->no_telp    = $validated['no_telp'] ?? null;
            $pegawai->status     = $validated['status'];

            $pegawai->save();
        });

        return back()->with('success', 'Data pegawai berhasil diperbarui!');
    }

    public function destroy(Pegawai $pegawai)
    {
        $this->authorizeManage();

        DB::transaction(function () use ($pegawai) {

            // Akun hanya dihapus ketika pegawai benar-benar dihapus.
            if ($pegawai->user_id) {
                User::find($pegawai->user_id)?->delete();
            }

            $pegawai->delete();
        });

        return back()->with('success', 'Pegawai berhasil dihapus!');
    }
}