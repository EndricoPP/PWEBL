<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Pertemuan 6
class TransactionController extends Controller
{
    public function index()
    {
        $transactions = DB::table('transactions')->get();

        return view('transactions.index', ['transactions' => $transactions]);
    }

    public function create()
    {
        $items = DB::table('items')->get();

        return view('transactions.create', ['items' => $items]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal'       => 'required|date',
            'keterangan'    => 'nullable|string|max:200',
            'items'         => 'required|array|min:1',
            'items.*.id'    => 'required|exists:items,id',
            'items.*.qty'   => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Grand total dihitung di sisi server
            $grandTotal = 0;
            foreach ($request->items as $item) {
                $grandTotal += $item['qty'] * $item['price'];
            }

            $invoiceNumber = 'TRX-' . date('YmdHis');
            $transactionId = DB::table('transactions')->insertGetId([
                'invoice_number'   => $invoiceNumber,
                'transaction_date' => $request->tanggal,
                'description'      => $request->keterangan,
                'total_amount'     => $grandTotal,
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);

            foreach ($request->items as $item) {
                DB::table('transaction_details')->insert([
                    'id_transaction' => $transactionId,
                    'item_id'        => $item['id'],
                    'quantity'       => $item['qty'],
                    'price'          => $item['price'],
                    'subtotal'       => $item['qty'] * $item['price'],
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);

                DB::table('items')->where('id', $item['id'])->decrement('stock', $item['qty']);
            }

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Transaksi berhasil disimpan!',
                'data'    => [
                    'id'             => $transactionId,
                    'invoice_number' => $invoiceNumber,
                    'total_amount'   => $grandTotal,
                ],
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        $transaction = DB::table('transactions')->where('id', $id)->first();

        if (!$transaction) {
            abort(404, 'Transaksi tidak ditemukan');
        }

        $details = DB::table('transaction_details')
            ->join('items', 'transaction_details.item_id', '=', 'items.id')
            ->select('transaction_details.*', 'items.name as item_name')
            ->where('transaction_details.id_transaction', $id)
            ->get();

        return view('transactions.show', compact('transaction', 'details'));
    }
}
