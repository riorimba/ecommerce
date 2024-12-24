<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use Illuminate\Support\Str;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Order::insert([
            [
                'order_id' => Str::uuid(),
                'user_id' => 1,
                'status' => 'paid',
                'total' => 200,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'order_id' => Str::uuid(),
                'user_id' => 2,
                'status' => 'shipped',
                'total' => 1100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'order_id' => Str::uuid(),
                'user_id' => 2,
                'status' => 'completed',
                'total' => 600,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
