<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\ProductImage;

class ProductImagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductImage::insert([
            [
                'product_id' => 1,
                'image_path' => 'product1.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => 2,
                'image_path' => 'product2.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => 3,
                'image_path' => 'product3.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
