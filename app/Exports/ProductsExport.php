<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ProductsExport implements FromQuery, WithHeadings, WithMapping, WithChunkReading
{
    public function query()
    {
        return Product::query();
    }

    public function map($product): array
    {
        return [
            $product->id,
            $product->name,
            $product->description,
            $product->price,
            $product->stock,
            $product->created_at,
            $product->updated_at,
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Description',
            'Price',
            'Stock',
            'Created At',
            'Updated At',
        ];
    }

    public function chunkSize(): int
    {
        return 1000;
    }
};
