<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DiscountController extends Controller
{
    public function index()
    {
        return view('discount');
    }

    public function calculate(Request $request)
    {
        $harga = (float) $request->harga;
        $persenDiskon = (float) $request->persen_diskon;

        $nilaiDiskon = $harga * ($persenDiskon / 100);
        $hargaSetelahDiskon = $harga - $nilaiDiskon;
        $hargaAkhir = $this->hitungPajak($hargaSetelahDiskon);

        $this->catatLog("Hitung diskon: harga={$harga}, diskon={$persenDiskon}%, hasil akhir={$hargaAkhir}");

        return "Harga Awal: Rp" . number_format($harga, 0, ',', '.')
            . " | Diskon: Rp" . number_format($nilaiDiskon, 0, ',', '.')
            . " | Harga Setelah Pajak: Rp" . number_format($hargaAkhir, 0, ',', '.');
    }

    protected function hitungPajak(float $harga, float $persenPajak = 11): float
    {
        return $harga + ($harga * ($persenPajak / 100));
    }

    private function catatLog(string $aktivitas): void
    {
        Log::info('[DiscountController] ' . $aktivitas);
    }
}
