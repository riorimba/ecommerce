<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'subtotal',
        'product_name',
        'product_price',
    ];

    public function order(){
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function product(){
        return $this->belongsTo(Product::class)->withTrashed();
    }
}
