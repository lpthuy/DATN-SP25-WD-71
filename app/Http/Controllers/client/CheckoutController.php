<?php 

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        $selectedProducts = json_decode($request->selected_products, true);
        
        if (!$selectedProducts || count($selectedProducts) === 0) {
            return redirect()->route('cart')->with('error', 'Không có sản phẩm nào được chọn!');
        }

        return view('client.pages.check-order', compact('selectedProducts'));
    }

    public function placeOrder(Request $request)
    {
        $user = Auth::user();
        $selectedProducts = session('selected_products');

        if (!$selectedProducts) {
            return redirect()->route('cart')->with('error', 'Không có sản phẩm nào được chọn!');
        }

        $paymentMethod = $request->payment_method;

        foreach ($selectedProducts as $product) {
            Order::create([
                'order_code' => 'OD' . strtoupper(Str::random(6)),
                'user_id' => $user->id,
                'product_id' => $product['id'],
                'product_name' => $product['name'],
                'color' => $product['color'],
                'size' => $product['size'],
                'quantity' => $product['quantity'],
                'price' => $product['price'] * $product['quantity'], // Tính tổng tiền đúng số lượng
                'payment_method' => $paymentMethod,
                'status' => 'processing',
            ]);
        }

        return redirect()->route('order')->with('success', 'Đơn hàng đã được tạo thành công!');
    }
}
