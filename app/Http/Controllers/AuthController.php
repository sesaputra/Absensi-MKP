<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Fungsi untuk memproses data dari form login
    public function authenticate(Request $request)
    {
        // 1. Validasi input (pastikan email dan password tidak kosong)
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Coba cocokkan dengan data di database
        if (Auth::attempt($credentials)) {

            // Buat sesi login baru agar aman
            $request->session()->regenerate();

            // 3. Cek Role dan Arahkan ke halaman yang sesuai
            if (Auth::user()->role === 'super_admin' || Auth::user()->role === 'admin') {
                return redirect()->intended('/dashboard');
            } elseif (Auth::user()->role === 'pengawas') {
                return redirect()->intended('pengawas/dashboard');
            }
        }

        // 4. Jika login gagal (email/password salah), kembalikan ke halaman login dengan pesan error
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    // Fungsi untuk memproses Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
