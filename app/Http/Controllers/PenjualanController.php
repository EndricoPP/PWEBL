<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Praktikum 3
class PenjualanController extends Controller
{
    public function index()
    {
        // 1. Data Skalar (Tunggal)
        $noNota   = "INV-2026-0091";
        $kasir    = "Siti Rahma";
        $potongan = 10000;

        // 2. Data Array 1 Dimensi (Kategori Pembayaran)
        $metodeBayar = ['Cash', 'QRIS Mandiri', 'Transfer BCA', 'Debit Card'];

        // 3. Data Multi Array 2 Dimensi (Daftar Item Terjual)
        $items = [
            [
                'kode'  => 'ITM-01',
                'nama'  => 'Flashdisk Sandisk 32GB',
                'harga' => 65000,
                'qty'   => 2,
                'stok'  => 10
            ],
            [
                'kode'  => 'ITM-02',
                'nama'  => 'Headset Bluetooth JBL',
                'harga' => 250000,
                'qty'   => 1,
                'stok'  => 3
            ],
            [
                'kode'  => 'ITM-03',
                'nama'  => 'Mousepad Gaming XL',
                'harga' => 85000,
                'qty'   => 1,
                'stok'  => 0
            ]
        ];

        // 4. Data Format JSON (Konfigurasi Ringkasan Toko)
        $infoToko = json_encode([
            'nama_toko' => 'TechMedia Computer Store',
            'cabang'    => 'Kampus Utama',
            'status'    => 'Open'
        ]);

        return view('penjualan.index', compact('noNota', 'kasir', 'potongan', 'metodeBayar', 'items', 'infoToko'));
    }

    public function katalog()
    {
        // Data Multi Array Katalog Barang
        $katalog = [
            [
                'kode'          => 'KTG-01',
                'nama_item'     => 'Keyboard Mechanical Rexus',
                'kategori'      => 'Aksesoris',
                'harga'         => 450000,
                'diskon_persen' => 10
            ],
            [
                'kode'          => 'KTG-02',
                'nama_item'     => 'Mouse Wireless Logitech M331',
                'kategori'      => 'Aksesoris',
                'harga'         => 180000,
                'diskon_persen' => 25
            ],
            [
                'kode'          => 'KTG-03',
                'nama_item'     => 'SSD Samsung 500GB',
                'kategori'      => 'Storage',
                'harga'         => 850000,
                'diskon_persen' => 20
            ],
            [
                'kode'          => 'KTG-04',
                'nama_item'     => 'Monitor LG 24 Inch',
                'kategori'      => 'Display',
                'harga'         => 1750000,
                'diskon_persen' => 5
            ],
            [
                'kode'          => 'KTG-05',
                'nama_item'     => 'RAM Kingston 8GB DDR4',
                'kategori'      => 'Komponen',
                'harga'         => 320000,
                'diskon_persen' => 30
            ],
            [
                'kode'          => 'KTG-06',
                'nama_item'     => 'Webcam Logitech C270',
                'kategori'      => 'Aksesoris',
                'harga'         => 275000,
                'diskon_persen' => 0
            ]
        ];

        return view('penjualan.katalog', compact('katalog'));
    }
}
