<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'order_id',
        'user_id',
        'status',
        'total',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->order_id = (string) Str::uuid();
        });
    }

    public function user(){
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function orderItems(){
        return $this->hasMany(OrderItem::class, 'order_id', 'order_id');
    }
}
