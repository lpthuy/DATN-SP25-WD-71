<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'product_id', 'product_name', 'color', 'size', 'quantity', 'price'];

    public function promotion()
    {
        return $this->belongsTo(Promotion::class, 'promotion_id');
    }
}
