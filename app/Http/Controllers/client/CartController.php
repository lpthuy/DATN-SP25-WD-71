<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function showCart()
{
    $cartItems = session()->get('cart', []);  // Lấy dữ liệu giỏ hàng từ session
    $totalPrice = 0;

    // Tính tổng giá trị của giỏ hàng
    foreach ($cartItems as $item) {
        $totalPrice += $item['price'] * $item['quantity'];
    }

    return view('client.pages.cart', compact('cartItems', 'totalPrice'));
}

    public function addToCart(Request $request)
    {
        $productId = $request->product_id;
        $product = Product::find($productId);
    
        if ($product) {
            $cart = session()->get('cart', []);
    
            // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
            if (isset($cart[$productId])) {
                $cart[$productId]['quantity']++;
            } else {
                $cart[$productId] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => 1,
                    'image' => $product->image, // Giả sử bạn lưu đường dẫn ảnh
                ];
            }
    
            session()->put('cart', $cart);
    
            return response()->json([
                'message' => 'Sản phẩm đã được thêm vào giỏ hàng!',
                'totalItems' => count($cart),
            ]);
        }
    
        return response()->json(['message' => 'Sản phẩm không tồn tại'], 404);
    }
    
    public function removeItem(Request $request)
{
    $productId = $request->input('product_id');
    $cart = session()->get('cart', []);

    // Lọc giỏ hàng, giữ lại sản phẩm không bị xóa
    $updatedCart = array_filter($cart, function ($item) use ($productId) {
        return $item['id'] != $productId;
    });

    // Reset lại chỉ mục của mảng
    $updatedCart = array_values($updatedCart);

    // Cập nhật session giỏ hàng
    if (empty($updatedCart)) {
        session()->forget('cart');
        $totalPrice = 0;
    } else {
        session()->put('cart', $updatedCart);
        $totalPrice = array_sum(array_map(function ($item) {
            return $item['price'] * $item['quantity'];
        }, $updatedCart));
    }

    return response()->json([
        'success' => true,
        'total_price' => number_format($totalPrice, 0, ',', '.')
    ]);
}




      

}
