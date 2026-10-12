<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    private function seedItem(int $stock = 10): int
    {
        return DB::table('items')->insertGetId([
            'name' => 'Mouse', 'price' => 100000, 'stock' => $stock,
            'created_at' => now(), 'updated_at' => now(),
        ]);
    }

    public function test_transaksi_berhasil_memotong_stok_dan_menerapkan_diskon(): void
    {
        $id = $this->seedItem(10);

        $res = $this->postJson('/transactions', [
            'discount_percent' => 10,
            'items' => [['item_id' => $id, 'quantity' => 2, 'price' => 100000]],
        ])->assertOk()->assertJson(['status' => true]);

        $this->assertSame(8, DB::table('items')->where('id', $id)->value('stock'));
        $trx = DB::table('transactions')->first();
        $this->assertEquals(180000, $trx->total_amount);
        $this->assertEquals(10, $trx->discount_percent);

        $this->get($res->json('receipt_url'))->assertOk()->assertSee($trx->invoice_number);
    }

    public function test_stok_tidak_cukup_di_rollback(): void
    {
        $id = $this->seedItem(1);

        $this->postJson('/transactions', [
            'items' => [['item_id' => $id, 'quantity' => 5, 'price' => 100000]],
        ])->assertStatus(500)->assertJson(['status' => false]);

        $this->assertSame(0, DB::table('transactions')->count());
        $this->assertSame(1, DB::table('items')->where('id', $id)->value('stock'));
    }

    public function test_get_items_hanya_stok_tersedia_dan_bisa_dicari(): void
    {
        $this->seedItem(5);
        $this->seedItem(0);

        $this->getJson('/transactions/get-items?search=Mou')->assertJsonCount(1, 'data');
        $this->get('/transactions/create')->assertOk();
    }
}
