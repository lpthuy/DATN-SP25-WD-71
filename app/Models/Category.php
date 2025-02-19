<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'parent_category_id',
        'is_active',
        'image_url',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // public function products()
    // {
    //     return $this->hasMany(Product::class);
    // }
}
