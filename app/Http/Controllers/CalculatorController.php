<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CalculatorController extends Controller
{
    public function index()
    {
        return view('calculator');
    }

    public function add(Request $request)
    {
        return "Hasil Penjumlahan: " . ($request->number1 + $request->number2);
    }

    public function subtract(Request $request)
    {
        return "Hasil Pengurangan: " . ($request->number1 - $request->number2);
    }

    public function multiply(Request $request)
    {
        return "Hasil Perkalian: " . ($request->number1 * $request->number2);
    }

    public function divide(Request $request)
    {
        if ($request->number2 == 0) {
            return "Error: Tidak bisa membagi dengan nol!";
        }

        return "Hasil Pembagian: " . ($request->number1 / $request->number2);
    }
}
