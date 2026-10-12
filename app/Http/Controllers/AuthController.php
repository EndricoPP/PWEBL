<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|max:100|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $id = DB::table('users')->insertGetId([
            'name'       => $data['name'],
            'email'      => $data['email'],
            'password'   => Hash::make($data['password']),
            'role'       => 'user',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => 'Registrasi berhasil, silakan login.',
            'user'    => ['id' => $id, 'name' => $data['name'], 'email' => $data['email']],
        ], 201);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = DB::table('users')->where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Email atau password salah.'], 401);
        }

        // Mencegah session fixation
        $request->session()->regenerate();

        // Menyimpan data akun ke dalam session
        $request->session()->put('user_id', $user->id);
        $request->session()->put('user_name', $user->name);
        $request->session()->put('user_role', $user->role);

        return response()->json([
            'message' => 'Login berhasil, session telah dibuat.',
            'user'    => ['id' => $user->id, 'name' => $user->name, 'role' => $user->role],
        ]);
    }

    public function logout(Request $request)
    {
        // Menghapus seluruh data session
        $request->session()->flush();

        // Flash data: hanya tersedia pada satu request berikutnya
        $request->session()->flash('status', 'Anda telah keluar dari sistem');

        return response()->json([
            'message' => 'Logout berhasil, session dihapus.',
            'status'  => $request->session()->get('status'),
        ]);
    }
}
