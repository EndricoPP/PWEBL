<?php

use App\Http\Controllers\CalculatorController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\FlatShapeController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\SolidShapeController;
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
