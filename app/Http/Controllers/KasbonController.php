<?php

namespace App\Http\Controllers;

use App\Models\Kasbon;
use App\Models\KeuanganProyek;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KasbonController extends Controller
{
    public function store(Request $request, $proyek_id)
    {
        $request->validate([
            'pegawai_id' => 'required',
            'nominal' => 'required|numeric|min:1',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        // Gunakan DB Transaction agar pencatatan Kasbon dan Buku Kas terjadi bersamaan
        DB::transaction(function () use ($request, $proyek_id) {

            // 1. Catat ke tabel Kasbon (sebagai pengingat hutang)
            Kasbon::create([
                'proyek_id' => $proyek_id,
                'pegawai_id' => $request->pegawai_id,
                'tanggal' => $request->tanggal,
                'nominal' => $request->nominal,
                'keterangan' => $request->keterangan,
                'status' => 'Belum Lunas',
            ]);

            // 2. POTONG UANG KAS PROYEK SECARA REAL-TIME!
            $pegawai = Pegawai::find($request->pegawai_id);

            KeuanganProyek::create([
                'proyek_id' => $proyek_id,
                'tipe' => 'Pengeluaran',
                'kategori' => 'Kasbon Tukang', // Kategori khusus agar mudah dilacak
                'nominal' => $request->nominal,
                'tanggal' => $request->tanggal,
                'keterangan' => 'Pencairan Kasbon a.n ' . $pegawai->nama . ($request->keterangan ? ' (' . $request->keterangan . ')' : ''),
            ]);
        });

        return back()->with('success', 'Kasbon berhasil dicatat dan dana kas telah dipotong otomatis.');
    }
}
