<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Praktikum 5: barang disimpan di session (tanpa database)
class BarangController extends Controller
{
    public function index(Request $request)
    {
        $barang = $request->session()->get('barang', []);

        return response()->json([
            'jumlah' => count($barang),
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

        $barang = $request->session()->get('barang', []);
        $barang[] = $data;
        $request->session()->put('barang', $barang);

        return response()->json([
            'message' => 'Barang berhasil ditambahkan.',
            'data'    => $data,
        ], 201);
    }
}
