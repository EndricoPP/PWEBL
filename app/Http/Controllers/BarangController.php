<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Praktikum 5: barang milik masing-masing akun (dibatasi oleh user_id di session)
class BarangController extends Controller
{
    public function index(Request $request)
    {
        $barang = DB::table('barang')
            ->where('user_id', $request->session()->get('user_id'))
            ->orderBy('id')
            ->get(['id', 'nama', 'harga', 'stok']);

        return response()->json([
            'jumlah' => $barang->count(),
            'data'   => $barang,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'  => 'required|string|max:100',
            'harga' => 'required|integer|min:0',
            'stok'  => 'required|integer|min:0',
        ]);

        $id = DB::table('barang')->insertGetId($data + [
            'user_id'    => $request->session()->get('user_id'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => 'Barang berhasil ditambahkan.',
            'data'    => ['id' => $id] + $data,
        ], 201);
    }
}
