<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SolidShapeController extends Controller
{
    public function index()
    {
        return view('solid-shape');
    }

    public function kubus(Request $request)
    {
        $sisi = (float) $request->sisi;
        $volume = $sisi ** 3;
        $luasPermukaan = 6 * ($sisi * $sisi);

        return "Kubus (sisi={$sisi}) => Volume: {$volume}, Luas Permukaan: {$luasPermukaan}";
    }

    public function balok(Request $request)
    {
        $panjang = (float) $request->panjang;
        $lebar = (float) $request->lebar;
        $tinggi = (float) $request->tinggi;

        $volume = $panjang * $lebar * $tinggi;
        $luasPermukaan = 2 * (($panjang * $lebar) + ($panjang * $tinggi) + ($lebar * $tinggi));

        return "Balok (p={$panjang}, l={$lebar}, t={$tinggi}) => Volume: {$volume}, Luas Permukaan: {$luasPermukaan}";
    }

    public function tabung(Request $request)
    {
        $jariJari = (float) $request->jari_jari;
        $tinggi = (float) $request->tinggi;

        $volume = pi() * $jariJari * $jariJari * $tinggi;
        $luasPermukaan = (2 * pi() * $jariJari * $tinggi) + (2 * pi() * $jariJari * $jariJari);

        return "Tabung (r={$jariJari}, t={$tinggi}) => Volume: " . round($volume, 2) . ", Luas Permukaan: " . round($luasPermukaan, 2);
    }
}
