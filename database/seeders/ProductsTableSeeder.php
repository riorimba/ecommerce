<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'category_id' => 1,
                'name' => 'Product 1',
                'description' => 'Description 1',
                'price' => 100,
                'stock' => 10,
            ],
            [
                'category_id' => 2,
                'name' => 'Product 2',
                'description' => 'Description 2',
                'price' => 200,
                'stock' => 20,
            ],
            [
                'category_id' => 3,
                'name' => 'Product 3',
                'description' => 'Description 3',
                'price' => 300,
                'stock' => 30,
            ],
        ]);
    }
}
