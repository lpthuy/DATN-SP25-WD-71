<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    // Tên bảng trong database
    protected $table = 'order_details';

    // Các cột được phép gán dữ liệu hàng loạt
    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'color',
        'size',
        'quantity',
        'price',
        'created_at',
        'updated_at',
    ];

    // Nếu bạn không dùng timestamps tự động, có thể đặt $timestamps = false;
    // public $timestamps = false;

    /**
     * Quan hệ với model Order (một chi tiết thuộc về một đơn hàng)
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
