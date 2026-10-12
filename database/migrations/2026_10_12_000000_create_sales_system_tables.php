<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Master Barang
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 12, 2);
            $table->integer('stock');
            $table->timestamps();
        });

        // 2. Tabel Header Transaksi
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->date('transaction_date');
            $table->decimal('subtotal_amount', 12, 2)->default(0);
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->decimal('total_amount', 12, 2);
            $table->timestamps();
        });

        // 3. Tabel Detail Transaksi
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id');
            $table->unsignedBigInteger('item_id');
            $table->decimal('price', 12, 2);
            $table->integer('quantity');
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();

            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('items');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_details');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('items');
    }
};
