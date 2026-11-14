<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAccountIsActive
{
    /**
     * Pastikan akun yang sedang login berstatus Aktif; jika Nonaktif, keluarkan paksa.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            if (strcasecmp($user->status_akun ?? 'Aktif', 'Aktif') !== 0) {
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'Akun Anda berstatus Nonaktif dan tidak dapat digunakan.',
                    ], 403);
                }

                return redirect()
                    ->route('login')
                    ->withErrors([
                        'email' => 'Akun Anda berstatus Nonaktif dan tidak dapat digunakan. Silakan hubungi pusat layanan kampus.',
                    ]);
            }
        }

        return $next($request);
    }
}

