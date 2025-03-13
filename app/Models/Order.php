<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code',
        'user_id',
        'product_id',
        'product_name',
        'color',
        'size',
        'quantity',
        'price',
        'payment_method'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->order_code = 'OD' . strtoupper(Str::random(6)); // Ví dụ: OD3XG7FZ
        });
    }
}
