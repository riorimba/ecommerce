<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('order_items')->insert([
            [
                'order_id' => 1,
                'product_id' => 1,
                'quantity' => 2,
                'price' => 100,
                'total' => 200,
            ],
            [
                'order_id' => 2,
                'product_id' => 2,
                'quantity' => 1,
                'price' => 200,
                'total' => 200,
            ],
            [
                'order_id' => 3,
                'product_id' => 3,
                'quantity' => 3,
                'price' => 300,
                'total' => 900,
            ],
        ]);
    }
}
