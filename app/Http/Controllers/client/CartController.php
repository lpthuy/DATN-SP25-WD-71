<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Hiển thị giỏ hàng
     */
    public function showCart()
    {
        $cartItems = session()->get('cart', []);
        $totalPrice = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cartItems));

        return view('client.pages.cart', compact('cartItems', 'totalPrice'));
    }

    /**
     * Thêm sản phẩm vào giỏ hàng
     */
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'color_id'   => 'required|exists:colors,id',
            'size_id'    => 'required|exists:sizes,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        // 1. Lấy sản phẩm
        $product = Product::findOrFail($request->product_id);

        // 2. Lấy variant theo color_id + size_id
        $variant = ProductVariant::where('product_id', $product->id)
            ->where('color_id', $request->color_id)
            ->where('size_id', $request->size_id)
            ->first();

        if (!$variant) {
            return redirect()->back()->with('error', 'Sản phẩm với màu sắc và size này không tồn tại.');
        }

        // 3. Lấy giỏ hàng từ session
        $cart = session()->get('cart', []);

        // 4. Tạo khóa duy nhất cho sản phẩm
        $cartKey = $product->id . '-' . $variant->id;

        // 5. Nếu đã có, cộng dồn số lượng
        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $request->quantity;
        } else {
            // 6. Tạo mới mục giỏ hàng
            $cart[$cartKey] = [
                'product_id'   => $product->id,
                'product_name' => $product->name,
                'color'        => $variant->color->color_name,
                'size'         => $variant->size->size_name,
                'price'        => $variant->discount_price ?? $variant->price,
                'quantity'     => $request->quantity,
                'image'        => $product->image,
            ];
        }

        // 7. Lưu giỏ hàng vào session
        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Sản phẩm đã được thêm vào giỏ hàng!');
    }

    /**
     * Xóa 1 mục khỏi giỏ hàng
     */
    public function removeItem(Request $request)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$request->cartKey])) {
            unset($cart[$request->cartKey]);
            session()->put('cart', $cart);
        }

        $totalPrice = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));

        return response()->json([
            'success'     => true,
            'total_price' => number_format($totalPrice, 0, ',', '.'),
            'totalItems'  => count($cart),
        ]);
    }

    /**
     * Cập nhật số lượng sản phẩm
     */
    public function updateCart(Request $request)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$request->cartKey])) {
            $cart[$request->cartKey]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }

        $totalPrice = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
        $itemTotal  = number_format($cart[$request->cartKey]['price'] * $cart[$request->cartKey]['quantity'], 0, ',', '.');

        return response()->json([
            'success'     => true,
            'total_price' => number_format($totalPrice, 0, ',', '.'),
            'item_total'  => $itemTotal,
        ]);
    }

    /**
     * Đếm số mục trong giỏ hàng
     */
    public function countCart()
    {
        $cartCount = session('cart') ? count(session('cart')) : 0;
        return response()->json(['cart_count' => $cartCount]);
    }
}
