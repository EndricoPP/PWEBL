<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CekAksesAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Memeriksa keberadaan dan nilai session
        if ($request->session()->get('user_role') !== 'admin') {
            return response()->json(['message' => 'Akses ditolak. Anda bukan Admin.'], 403);
        }

        return $next($request);
    }
}
