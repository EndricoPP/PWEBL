<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('items')->insert([
            ['name' => 'Laptop ASUS Vivobook', 'price' => 8500000, 'stock' => 10, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Mouse Wireless Logitech', 'price' => 150000, 'stock' => 25, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Keyboard Mechanical', 'price' => 450000, 'stock' => 15, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Monitor LG 24 Inch', 'price' => 1800000, 'stock' => 8, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Flashdisk Sandisk 64GB', 'price' => 85000, 'stock' => 50, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
