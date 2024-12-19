<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Order::insert([
            [
                'user_id' => 1,
                'status' => 'paid',
                'total' => 200,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'status' => 'shipped',
                'total' => 1100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'status' => 'completed',
                'total' => 600,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
