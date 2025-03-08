<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function showCart()
    {
        $cartItems = session()->get('cart', []);
        $totalPrice = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cartItems));

        return view('client.pages.cart', compact('cartItems', 'totalPrice'));
    }

    public function addToCart(Request $request)
    {
        $product = Product::find($request->product_id);

        if (!$product) {
            return response()->json(['message' => 'Sản phẩm không tồn tại!'], 404);
        }

        // Tạo khóa duy nhất cho sản phẩm dựa trên ID, màu sắc, size
        $cartKey = "{$product->id}-{$request->color}-{$request->size}";

        $cart = session()->get('cart', []);

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $request->quantity;
        } else {
            $cart[$cartKey] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->discount_price ?? $product->price,
                'quantity' => $request->quantity,
                'color' => $request->color,
                'size' => $request->size,
                'image' => explode(',', $product->image)[0] ?? null
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Sản phẩm đã được thêm vào giỏ hàng!',
            'totalItems' => count($cart)
        ]);
    }

    public function removeItem(Request $request)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$request->cartKey])) {
            unset($cart[$request->cartKey]);
            session()->put('cart', $cart);
        }

        $totalPrice = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));

        return response()->json([
            'success' => true,
            'total_price' => number_format($totalPrice, 0, ',', '.'),
            'totalItems' => count($cart)
        ]);
    }
}
