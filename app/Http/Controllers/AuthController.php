<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Menyimpan data peran ke dalam session
        $request->session()->put('user_id', 101);
        $request->session()->put('user_role', 'admin');

        return response()->json(['message' => 'Login berhasil, session telah dibuat.']);
    }

    public function logout(Request $request)
    {
        // Menghapus seluruh data session
        $request->session()->flush();

        // Flash data: hanya tersedia pada satu request berikutnya
        $request->session()->flash('status', 'Anda telah keluar dari sistem');

        return response()->json([
            'message' => 'Logout berhasil, session dihapus.',
            'status' => $request->session()->get('status'),
        ]);
    }
}
