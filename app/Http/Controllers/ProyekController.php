<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Proyek;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProyekController extends Controller
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
     * Menampilkan daftar proyek.
     */
    public function index(): View
    {
        $proyeks = Proyek::query()
            ->latest()
            ->get();

        return view(
            'admin.manajemen-proyek',
            compact('proyeks')
        );
    }

    /**
     * Menyimpan proyek baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorizeAdmin();

        $dataProyek = $request->validate([
            'nama_proyek' => [
                'required',
                'string',
                'max:255',
            ],

            'nama_pemilik' => [
                'nullable',
                'string',
                'max:255',
            ],

            'kontak_pemilik' => [
                'nullable',
                'string',
                'max:255',
            ],

            'lokasi' => [
                'required',
                'string',
                'max:255',
            ],

            'tanggal_mulai' => [
                'required',
                'date',
            ],

            'estimasi_selesai' => [
                'nullable',
                'date',
                'after_or_equal:tanggal_mulai',
            ],

            'anggaran' => [
                'required',
                'numeric',
                'min:0',
            ],

            'status' => [
                'required',
                'in:Akan Dimulai,Berjalan,Ditunda,Selesai',
            ],

            'gambar' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg',
                'max:2048',
            ],
        ]);

        if ($request->hasFile('gambar')) {
            $dataProyek['gambar'] = $request
                ->file('gambar')
                ->store('gambar_proyek', 'public');
        }

        Proyek::create($dataProyek);

        return back()->with(
            'success',
            'Proyek baru berhasil ditambahkan.'
        );
    }

    /**
     * Menampilkan detail proyek.
     */
    public function show(Proyek $proyek): View
    {
        $proyek->load([
            'pegawais.jabatan',
            'itemPekerjaans',
        ]);

        $pekerjaPerJabatan = $proyek->pegawais
            ->groupBy(function ($pegawai) {
                return $pegawai->jabatan->nama_jabatan
                    ?? 'Tanpa Jabatan';
            });

        $pegawaiTersedia = Pegawai::query()
            ->where('status', 'aktif')
            ->whereDoesntHave('proyeks', function ($query) {
                $query->whereIn('status', [
                    'Akan Dimulai',
                    'Berjalan',
                ]);
            })
            ->with('jabatan')
            ->orderBy('nama')
            ->get();

        return view(
            'admin.detail-manajemen-proyek',
            compact(
                'proyek',
                'pekerjaPerJabatan',
                'pegawaiTersedia'
            )
        );
    }

    /**
     * Memperbarui data proyek.
     */
    public function update(
        Request $request,
        Proyek $proyek
    ): RedirectResponse {
        $this->authorizeAdmin();

        $dataUpdate = $request->validate([
            'nama_proyek' => [
                'required',
                'string',
                'max:255',
            ],

            'nama_pemilik' => [
                'nullable',
                'string',
                'max:255',
            ],

            'kontak_pemilik' => [
                'nullable',
                'string',
                'max:255',
            ],

            'lokasi' => [
                'required',
                'string',
                'max:255',
            ],

            'tanggal_mulai' => [
                'required',
                'date',
            ],

            'estimasi_selesai' => [
                'nullable',
                'date',
                'after_or_equal:tanggal_mulai',
            ],

            'anggaran' => [
                'required',
                'numeric',
                'min:0',
            ],

            'status' => [
                'required',
                'in:Akan Dimulai,Berjalan,Ditunda,Selesai',
            ],

            'gambar' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg',
                'max:2048',
            ],
        ]);

        if ($request->hasFile('gambar')) {
            if (
                $proyek->gambar &&
                Storage::disk('public')->exists($proyek->gambar)
            ) {
                Storage::disk('public')->delete(
                    $proyek->gambar
                );
            }

            $dataUpdate['gambar'] = $request
                ->file('gambar')
                ->store('gambar_proyek', 'public');
        }

        $proyek->update($dataUpdate);

        return back()->with(
            'success',
            'Data proyek berhasil diperbarui.'
        );
    }

    /**
     * Menghapus proyek.
     */
    public function destroy(
        Proyek $proyek
    ): RedirectResponse {
        $this->authorizeAdmin();

        if (
            $proyek->gambar &&
            Storage::disk('public')->exists($proyek->gambar)
        ) {
            Storage::disk('public')->delete(
                $proyek->gambar
            );
        }

        $proyek->delete();

        return redirect()
            ->route('proyek.index')
            ->with(
                'success',
                'Proyek berhasil dihapus.'
            );
    }

    /**
     * Menugaskan pegawai ke proyek.
     */
    public function assignPegawai(
        Request $request,
        Proyek $proyek
    ): RedirectResponse {
        $this->authorizeAdmin();

        $data = $request->validate([
            'pegawai_id' => [
                'required',
                'exists:pegawais,id',
            ],
        ]);

        $pegawai = Pegawai::query()
            ->findOrFail($data['pegawai_id']);

        /*
        |--------------------------------------------------------------------------
        | Pastikan pegawai aktif
        |--------------------------------------------------------------------------
        */

        if ($pegawai->status !== 'aktif') {
            return back()->with(
                'error',
                "Pegawai {$pegawai->nama} tidak dapat "
                    . "ditugaskan karena statusnya tidak aktif."
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Cek apakah pegawai sudah berada di proyek aktif lain
        |--------------------------------------------------------------------------
        */

        $proyekAktifLain = $pegawai
            ->proyeks()
            ->whereIn('status', [
                'Akan Dimulai',
                'Berjalan',
            ])
            ->where('proyeks.id', '!=', $proyek->id)
            ->first();

        if ($proyekAktifLain) {
            return back()->with(
                'error',
                "Gagal! {$pegawai->nama} sedang ditugaskan "
                    . "di proyek {$proyekAktifLain->nama_proyek}."
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Cek duplikasi assignment
        |--------------------------------------------------------------------------
        */

        $sudahDitugaskan = $proyek
            ->pegawais()
            ->whereKey($pegawai->id)
            ->exists();

        if ($sudahDitugaskan) {
            return back()->with(
                'error',
                "Pegawai {$pegawai->nama} sudah "
                    . "ditugaskan ke proyek ini."
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Assign pegawai
        |--------------------------------------------------------------------------
        */

        $proyek->pegawais()->syncWithoutDetaching([
            $pegawai->id,
        ]);

        return back()->with(
            'success',
            'Pegawai berhasil ditugaskan ke proyek.'
        );
    }

    /**
     * Mengeluarkan pegawai dari proyek.
     */
    public function removePegawai(
        Proyek $proyek,
        Pegawai $pegawai
    ): RedirectResponse {
        $this->authorizeAdmin();

        $terdaftar = $proyek
            ->pegawais()
            ->whereKey($pegawai->id)
            ->exists();

        if (!$terdaftar) {
            return back()->with(
                'error',
                'Pegawai tersebut tidak terdaftar '
                    . 'pada proyek ini.'
            );
        }

        $proyek->pegawais()->detach($pegawai->id);

        return back()->with(
            'success',
            'Pegawai berhasil dikeluarkan dari proyek.'
        );
    }
}
