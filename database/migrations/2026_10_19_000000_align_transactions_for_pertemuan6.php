<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->text('description')->nullable()->after('name');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->string('description', 200)->nullable()->after('invoice_number');
        });

        Schema::table('transaction_details', function (Blueprint $table) {
            $table->renameColumn('transaction_id', 'id_transaction');
        });
    }

    public function down(): void
    {
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->renameColumn('id_transaction', 'transaction_id');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('description');
        });

        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }
};
