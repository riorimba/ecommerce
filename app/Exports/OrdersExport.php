<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrdersExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Order::with('user', 'orderItems.product')
            ->get()
            ->map(function ($order) {
                $items = $order->orderItems->map(function ($item) {
                    return $item->product->name . ' (' . $item->quantity . ')';
                })->implode(', ');

                return [
                    'id' => $order->id,
                    'user' => $order->user->name,
                    'total' => $order->total,
                    'status' => $order->status,
                    'created_at' => $order->created_at,
                    'updated_at' => $order->updated_at,
                    'items' => $items,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'ID',
            'User',
            'Total',
            'Status',
            'Created At',
            'Updated At',
            'Items',
        ];
    }
}
