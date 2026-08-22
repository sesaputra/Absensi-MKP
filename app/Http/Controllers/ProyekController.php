<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Pegawai;
use App\Models\Proyek;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProyekController extends Controller
{
    // Menampilkan halaman daftar proyek
    public function index()
    {
        $proyeks = Proyek::latest()->get();
        return view('Admin.manajemen-proyek', compact('proyeks'));
    }

    public function store(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') abort(403);

        $request->validate([
            'nama_proyek' => 'required|string|max:255',
            'nama_pemilik' => 'nullable|string|max:255',
            'kontak_pemilik' => 'nullable|string|max:255',
            'lokasi' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'estimasi_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'anggaran' => 'required|numeric|min:0',
            'status' => 'required|in:Akan Dimulai,Berjalan,Ditunda,Selesai',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Maks 2MB
        ]);

        $dataProyek = $request->except(['_token']); // Ambil semua data kecuali token csrf

        // Cek apakah Admin mengunggah gambar
        if ($request->hasFile('gambar')) {
            // Simpan gambar ke folder 'public/gambar_proyek'
            $pathGambar = $request->file('gambar')->store('gambar_proyek', 'public');
            $dataProyek['gambar'] = $pathGambar; // Masukkan alamat file ke dalam array database
        }

        Proyek::create($dataProyek);

        return back()->with('success', 'Proyek baru beserta gambar berhasil ditambahkan!');
    }

    public function update(Request $request, Proyek $proyek)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') abort(403);

        $request->validate([
            'nama_proyek' => 'required|string|max:255',
            'nama_pemilik' => 'nullable|string|max:255',
            'kontak_pemilik' => 'nullable|string|max:255',
            'lokasi' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'estimasi_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:Akan Dimulai,Berjalan,Ditunda,Selesai',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Maks 2MB
        ]);

        $dataUpdate = $request->except(['_token', '_method']);

        // Cek apakah Admin mengunggah gambar BARU saat edit
        if ($request->hasFile('gambar')) {
            // 1. Hapus gambar lama (jika ada) agar hardisk server tidak penuh
            if ($proyek->gambar && Storage::disk('public')->exists($proyek->gambar)) {
                Storage::disk('public')->delete($proyek->gambar);
            }

            // 2. Simpan gambar baru
            $pathGambarBaru = $request->file('gambar')->store('gambar_proyek', 'public');
            $dataUpdate['gambar'] = $pathGambarBaru;
        }

        $proyek->update($dataUpdate);

        return back()->with('success', 'Data proyek dan gambar berhasil diperbarui!');
    }

    // Menghapus proyek
    public function destroy(Proyek $proyek)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') abort(403);
        if ($proyek->gambar && Storage::disk('public')->exists($proyek->gambar)) {
            Storage::disk('public')->delete($proyek->gambar);
        }
        $proyek->delete();
        return back()->with('success', 'Proyek beserta fotonya berhasil dihapus!');
    }

    public function show(Proyek $proyek)
    {
        // 1. Memuat data proyek sekaligus daftar pekerja DAN item pekerjaannya
        $proyek->load(['pegawais', 'itemPekerjaans']);
        // 2. Mencari pegawai yang nganggur
        $pegawaiTersedia = \App\Models\Pegawai::whereDoesntHave('proyeks', function ($query) {
            $query->whereIn('status', ['Akan Dimulai', 'Berjalan']);
        })->get();

        return view('Admin.detail-manajemen-proyek', compact('proyek', 'pegawaiTersedia'));
    }

    // 2. Menugaskan pekerja ke proyek (Plotting)
    public function assignPegawai(Request $request, Proyek $proyek)
    {
        $request->validate([
            'pegawai_id' => 'required|exists:pegawais,id'
        ]);
        $pegawai = Pegawai::find($request->pegawai_id);

        // CEK STATUS: Apakah pegawai ini sudah ada di proyek lain yang sedang aktif?
        $proyekAktifLain = $pegawai->proyeks()->whereIn('status', ['Akan Dimulai', 'Berjalan'])->first();
        if ($proyekAktifLain) {
            return back()->with('error', "Gagal! {$pegawai->nama} sedang ditugaskan di proyek: {$proyekAktifLain->nama_proyek}. Keluarkan pekerja ini dari proyek tersebut terlebih dahulu.");
        }
        $proyek->pegawais()->attach($request->pegawai_id);

        return back()->with('success', 'Pegawai berhasil ditugaskan ke proyek!');
    }

    // 3. Menghapus pekerja dari proyek
    public function removePegawai(Proyek $proyek, Pegawai $pegawai)
    {
        $proyek->pegawais()->detach($pegawai->id);
        return back()->with('success', 'Pekerja berhasil dikeluarkan dari proyek ini.');
    }

    public function showabsensi(Proyek $proyek)
    {
        // Memuat pegawai yang ada di proyek ini
        $proyek->load('pegawais');
        $riwayatAbsensi = Absensi::with('pegawai')
            ->where('proyek_id', $proyek->id)
            ->orderBy('tanggal', 'desc')
            ->get()
            ->groupBy('tanggal');

        // Mengirim data ke view Detail Proyek Admin
        return view('Admin.proyek.show', compact('proyek', 'riwayatAbsensi'));
    }
}
