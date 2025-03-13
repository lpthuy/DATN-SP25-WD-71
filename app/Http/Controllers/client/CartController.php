<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
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
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'color_id' => 'required|exists:colors,id',
        'size_id' => 'required|exists:sizes,id',
        'quantity' => 'required|integer|min:1',
    ]);

    $product = Product::findOrFail($request->product_id);
    $variant = ProductVariant::where('product_id', $product->id)
                            ->where('color_id', $request->color_id)
                            ->where('size_id', $request->size_id)
                            ->first();

    if (!$variant) {
        return redirect()->back()->with('error', 'Sản phẩm với màu sắc và size này không tồn tại.');
    }

    $cart = session()->get('cart', []);
    
    $cartKey = $product->id . '-' . $variant->id;

    if (isset($cart[$cartKey])) {
        $cart[$cartKey]['quantity'] += $request->quantity;
    } else {
        $cart[$cartKey] = [
            'product_id' => $product->id,
            'variant_id' => $variant->id,
            'name' => $product->name,
            'color' => $variant->color->color_name,
            'size' => $variant->size->size_name,
            'price' => $variant->discount_price ?? $variant->price,
            'quantity' => $request->quantity,
            'image' => $product->image
        ];
    }

    session()->put('cart', $cart);
// Tính tổng tiền
$totalPrice = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
    

    return redirect()->back()->with('success', 'Sản phẩm đã được thêm vào giỏ hàng!');
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


    public function updateCart(Request $request)
{
    $cart = session()->get('cart', []);

    if (isset($cart[$request->cartKey])) {
        $cart[$request->cartKey]['quantity'] = $request->quantity;
        session()->put('cart', $cart);
    }

    // Tính tổng tiền
    $totalPrice = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
    $itemTotal = number_format($cart[$request->cartKey]['price'] * $cart[$request->cartKey]['quantity'], 0, ',', '.');

    return response()->json([
        'success' => true,
        'total_price' => number_format($totalPrice, 0, ',', '.'),
        'item_total' => $itemTotal
    ]);
}
public function countCart()
{
    $cartCount = session('cart') ? count(session('cart')) : 0;
    return response()->json(['cart_count' => $cartCount]);
}

}
