<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\CalculatorController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\FlatShapeController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\SolidShapeController;
use App\Http\Controllers\TransactionController;
use App\Http\Middleware\CekAksesAdmin;
use App\Http\Middleware\CekLogin;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Calculator
Route::get('/calculator', [CalculatorController::class, 'index']);
Route::post('/calculator/add', [CalculatorController::class, 'add']);
Route::post('/calculator/subtract', [CalculatorController::class, 'subtract']);
Route::post('/calculator/multiply', [CalculatorController::class, 'multiply']);
Route::post('/calculator/divide', [CalculatorController::class, 'divide']);

// Diskon belanja
Route::get('/discount', [DiscountController::class, 'index']);
Route::post('/discount/calculate', [DiscountController::class, 'calculate']);

// Bangun datar
Route::get('/flat-shape', [FlatShapeController::class, 'index']);
Route::post('/flat-shape/persegi', [FlatShapeController::class, 'persegi']);
Route::post('/flat-shape/lingkaran', [FlatShapeController::class, 'lingkaran']);

// Bangun ruang
Route::get('/solid-shape', [SolidShapeController::class, 'index']);
Route::post('/solid-shape/kubus', [SolidShapeController::class, 'kubus']);
Route::post('/solid-shape/balok', [SolidShapeController::class, 'balok']);
Route::post('/solid-shape/tabung', [SolidShapeController::class, 'tabung']);


// Praktikum 3
// Penjualan & Katalog Item
Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
Route::get('/katalog', [PenjualanController::class, 'katalog'])->name('penjualan.katalog');

// Praktikum 5: Session & Middleware
// Rute Otentikasi
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

// Rute Terproteksi
Route::get('/admin/dashboard', function () {
    return response()->json(['message' => 'Selamat datang di Panel Admin']);
})->middleware(CekAksesAdmin::class);

// Pertemuan 6: Transaksi (header-detail, Query Builder + AJAX)
Route::get('/transactions', [TransactionController::class, 'index']);
Route::get('/transactions/create', [TransactionController::class, 'create']);
Route::get('/transactions/show/{id}', [TransactionController::class, 'show']);
Route::post('/transactions', [TransactionController::class, 'store']);

// Praktikum 5: Barang - hanya akun yang sudah login, data per akun
Route::middleware(CekLogin::class)->group(function () {
    Route::get('/barang', [BarangController::class, 'index']);
    Route::post('/barang', [BarangController::class, 'store']);
});
