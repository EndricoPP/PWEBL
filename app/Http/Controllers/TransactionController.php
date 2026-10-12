<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

// Praktikum 4
class TransactionController extends Controller
{
    // Render Halaman Utama Transaksi
    public function create()
    {
        return view('transactions.create');
    }

    // Endpoint AJAX: Ambil data barang untuk Modal
    public function getItems(Request $request)
    {
        $search = $request->get('search');

        $query = DB::table('items')->where('stock', '>', 0);

        if (!empty($search)) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $items = $query->orderBy('name', 'asc')->get();

        return response()->json([
            'status' => true,
            'data'   => $items
        ]);
    }

    // Endpoint AJAX: Simpan Transaksi Penjualan
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'items'            => 'required|array|min:1',
            'items.*.item_id'  => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price'    => 'required|numeric|min:0',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validasi gagal, periksa item yang diinput.',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $subtotalAmount   = 0;
            $transactionItems = $request->items;
            $discountPercent  = (float) ($request->discount_percent ?? 0);

            // Validasi stok barang di tingkat server
            foreach ($transactionItems as $item) {
                $dbItem = DB::table('items')->where('id', $item['item_id'])->lockForUpdate()->first();

                if (!$dbItem) {
                    throw new \Exception("Item ID {$item['item_id']} tidak ditemukan.");
                }

                if ($dbItem->stock < $item['quantity']) {
                    throw new \Exception("Stok barang '{$dbItem->name}' tidak mencukupi. Sisa: {$dbItem->stock}");
                }

                $subtotalAmount += $item['price'] * $item['quantity'];
            }

            // Tantangan 1: total setelah diskon persentase
            $totalAmount = $subtotalAmount - ($subtotalAmount * $discountPercent / 100);

            // 1. Insert Header Transaksi
            $invoiceNumber = 'TRX-' . date('Ymd') . '-' . rand(1000, 9999);
            $transactionId = DB::table('transactions')->insertGetId([
                'invoice_number'   => $invoiceNumber,
                'transaction_date' => now()->toDateString(),
                'subtotal_amount'  => $subtotalAmount,
                'discount_percent' => $discountPercent,
                'total_amount'     => $totalAmount,
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);

            // 2. Insert Detail Transaksi & Potong Stok
            foreach ($transactionItems as $item) {
                $subtotal = $item['price'] * $item['quantity'];

                DB::table('transaction_details')->insert([
                    'transaction_id' => $transactionId,
                    'item_id'        => $item['item_id'],
                    'price'          => $item['price'],
                    'quantity'       => $item['quantity'],
                    'subtotal'       => $subtotal,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);

                // Kurangi stok menggunakan decrement
                DB::table('items')
                    ->where('id', $item['item_id'])
                    ->decrement('stock', $item['quantity']);
            }

            DB::commit();

            return response()->json([
                'status'      => true,
                'message'     => 'Transaksi berhasil disimpan!',
                'invoice'     => $invoiceNumber,
                'receipt_url' => route('transactions.receipt', $invoiceNumber),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Tantangan 3: Struk penjualan berdasarkan nomor invoice
    public function receipt(string $invoice)
    {
        $transaction = DB::table('transactions')->where('invoice_number', $invoice)->first();
        abort_if(!$transaction, 404);

        $details = DB::table('transaction_details')
            ->join('items', 'items.id', '=', 'transaction_details.item_id')
            ->where('transaction_details.transaction_id', $transaction->id)
            ->select('transaction_details.*', 'items.name')
            ->get();

        return view('transactions.receipt', compact('transaction', 'details'));
    }
}
