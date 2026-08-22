<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengawasController extends Controller
{
    public function dashboard()
    {
        // 1. Ambil data user yang sedang login beserta data pegawainya
        $user = Auth::user();
        $pegawai = $user->pegawai;

        // Jika user ini bukan pegawai/pengawas, kembalikan (opsional, untuk keamanan)
        if (!$pegawai) {
            abort(403, 'Halaman ini khusus untuk tim lapangan.');
        }

        // 2. Ambil hanya proyek-proyek yang ditugaskan ke pengawas ini 
        // (Bisa difilter yang statusnya 'Berjalan' atau 'Akan Dimulai')
        $proyekDitugaskan = $pegawai->proyeks()
            ->whereIn('status', ['Akan Dimulai', 'Berjalan'])
            ->get();

        return view('Pengawas.dashboard', compact('pegawai', 'proyekDitugaskan'));
    }
}
