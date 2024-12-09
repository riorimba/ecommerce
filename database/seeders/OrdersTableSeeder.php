<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('orders')->insert([
            [
                'user_id' => 1,
                'status' => 'paid',
            ],
            [
                'user_id' => 2,
                'status' => 'shipped',
            ],
            [
                'user_id' => 1,
                'status' => 'completed',
            ],
        ]);
    }
}
