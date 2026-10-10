<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Proses autentikasi pengguna.
     */
    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials)) {
            return back()
                ->withErrors([
                    'email' => 'Email atau password yang Anda masukkan salah.',
                ])
                ->onlyInput('email');
        }

        // Regenerasi session setelah login berhasil
        // untuk mencegah session fixation.
        $request->session()->regenerate();

        /** @var User $user */
        $user = Auth::user();

        // Super Admin dan Admin masuk ke dashboard utama.
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            return redirect()->intended('/dashboard');
        }

        // Pengawas masuk ke dashboard pengawas.
        if ($user->isPengawas()) {
            return redirect()->intended('/pengawas/dashboard');
        }

        // Pengaman jika terdapat role yang tidak dikenali.
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return back()->withErrors([
            'email' => 'Akun Anda tidak memiliki akses ke sistem.',
        ]);
    }

    /**
     * Logout pengguna.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        // Hapus session login.
        $request->session()->invalidate();

        // Regenerasi CSRF token.
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
