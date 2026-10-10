<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Membatasi akses berdasarkan role user.
     */
    public function handle(
        Request $request,
        Closure $next,
        ...$roles
    ): Response {
        $user = $request->user();

        /*
         * User belum login.
         *
         * Normalnya kondisi ini sudah ditangani
         * middleware auth, tetapi tetap kita amankan
         * di sini sebagai defense in depth.
         */
        if (!$user) {
            abort(401, 'Anda harus login terlebih dahulu.');
        }

        /*
         * Pastikan role user termasuk role
         * yang diizinkan oleh route.
         */
        if (!in_array($user->role, $roles, true)) {
            abort(403, 'Anda tidak memiliki hak akses.');
        }

        return $next($request);
    }
}
