<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductImagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('product_images')->insert([
            [
                'product_id' => 1,
                'image' => 'product1.jpg',
            ],
            [
                'product_id' => 2,
                'image' => 'product2.jpg',
            ],
            [
                'product_id' => 3,
                'image' => 'product3.jpg',
            ],
        ]);
    }
}
