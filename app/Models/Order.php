<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $primaryKey = 'order_id'; // Khóa chính là order_id

    protected $fillable = [
        'order_date',
        'total_amount',
        'status',
        'payment_method',
        'shipping_cost'
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'total_amount' => 'decimal:10,2',
        'shipping_cost' => 'decimal:10,2',
    ];
}
