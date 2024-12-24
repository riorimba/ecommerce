<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\OrderItem;
use App\Models\Order;

class OrderItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = Order::all();

        foreach ($orders as $order) {
            OrderItem::insert([
                [
                    'order_id' => $order->order_id, // Menggunakan order_id dari tabel orders
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
                    'order_id' => $order->order_id, // Menggunakan order_id dari tabel orders
                    'product_id' => 2,
                    'quantity' => 1,
                    'price' => 200,
                    'subtotal' => 200,
                    'product_name' => 'Product 2',
                    'product_price' => 200,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }
}
