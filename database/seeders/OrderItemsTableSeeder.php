<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\OrderItem;

class OrderItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OrderItem::insert([
            [
                'order_id' => 1,
                'product_id' => 1,
                'quantity' => 2,
                'price' => 100,
                'subtotal' => 200,
                'product_name' => 'Product 1',
                'product_price' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'order_id' => 2,
                'product_id' => 2,
                'quantity' => 1,
                'price' => 200,
                'subtotal' => 200,
                'product_name' => 'Product 2',
                'product_price' => 200,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'order_id' => 2,
                'product_id' => 3,
                'quantity' => 3,
                'price' => 300,
                'subtotal' => 900,
                'product_name' => 'Product 3',
                'product_price' => 300,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'order_id' => 3,
                'product_id' => 3,
                'quantity' => 2,
                'price' => 300,
                'subtotal' => 600,
                'product_name' => 'Product 3',
                'product_price' => 300,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
