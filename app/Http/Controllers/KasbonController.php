<?php

namespace App\Http\Controllers;

use App\Models\Kasbon;
use App\Models\KeuanganProyek;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KasbonController extends Controller
{
    public function store(Request $request, Proyek $proyek)
    {
        $request->validate([
            'pegawai_id' => 'required|exists:pegawais,id',
            'nominal' => 'required|numeric|min:1',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $proyek) {
            Kasbon::create([
                'proyek_id' => $proyek->id,
                'pegawai_id' => $request->pegawai_id,
                'tanggal' => $request->tanggal,
                'nominal' => $request->nominal,
                'keterangan' => $request->keterangan,
                'status' => 'Belum Lunas',
            ]);

            $pegawai = Pegawai::find($request->pegawai_id);

            KeuanganProyek::create([
                'proyek_id' => $proyek->id,
                'tipe' => 'Pengeluaran',
                'kategori' => 'Kasbon Tukang',
                'nominal' => $request->nominal,
                'tanggal' => $request->tanggal,
                'keterangan' => 'Pencairan Kasbon a.n ' . $pegawai->nama . ($request->keterangan ? ' (' . $request->keterangan . ')' : ''),
            ]);
        });

        return back()->with('success', 'Kasbon berhasil dicatat dan dana kas telah dipotong otomatis.');
    }
}
