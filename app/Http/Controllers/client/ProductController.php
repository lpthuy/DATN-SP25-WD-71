<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show($id)
    {
        // Lấy sản phẩm theo ID kèm theo danh sách kích thước và màu sắc
        $product = Product::with(['sizes', 'colors'])->findOrFail($id);

        return view('client.products.detail', compact('product'));
    }
}
