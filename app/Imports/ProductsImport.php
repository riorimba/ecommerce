<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\ProductImage;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class ProductsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $product = new Product([
            'name' => $row['name'],
            'description' => $row['description'],
            'price' => $row['price'],
            'stock' => $row['stock'],
            'category_id' => $row['category_id'],
        ]);

        $product->save();

        if (!empty($row['image_paths'])) {
            $imageUrls = explode(';', $row['image_paths']);
            foreach ($imageUrls as $imageUrl) {
                $imageContents = file_get_contents($imageUrl);
                $fileName = time() . '_' . uniqid() . '.jpg';
                $filePath = public_path('product_images/' . $fileName);
                file_put_contents($filePath, $imageContents);

                // Simpan jalur gambar ke database
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => 'product_images/' . $fileName,
                ]);
            }
        }

        return $product;
    }
}
