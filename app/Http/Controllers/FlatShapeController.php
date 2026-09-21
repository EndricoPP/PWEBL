<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FlatShapeController extends Controller
{
    public function index()
    {
        return view('flat-shape');
    }

    public function persegi(Request $request)
    {
        $sisi = (float) $request->sisi;
        $luas = $sisi * $sisi;
        $keliling = 4 * $sisi;

        return "Persegi (sisi={$sisi}) => Luas: {$luas}, Keliling: {$keliling}";
    }

    public function lingkaran(Request $request)
    {
        $jariJari = (float) $request->jari_jari;
        $luas = pi() * $jariJari * $jariJari;
        $keliling = 2 * pi() * $jariJari;

        return "Lingkaran (jari-jari={$jariJari}) => Luas: " . round($luas, 2) . ", Keliling: " . round($keliling, 2);
    }
}
