<?php 

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function show(Request $request)
{
    $user = Auth::user();

    // 👉 Kiểm tra nếu chưa có địa chỉ
    if (empty($user->address)) {
        return redirect()->route('profile')->with('error', 'Vui lòng cập nhật địa chỉ trước khi đặt hàng.');
    }

    $cart = session('cart', []);
    if (empty($cart)) {
        return redirect()->route('cart')->with('error', 'Không có sản phẩm nào trong giỏ hàng!');
    }

    $selectedProducts = json_decode($request->input('selected_products'), true);

    $checkoutItems = [];
    $total = 0;

    if ($selectedProducts) {
        foreach ($selectedProducts as $selected) {
            $cartKey = $selected['cartKey'];
            $quantity = (int)$selected['quantity'];

            if (isset($cart[$cartKey])) {
                $item = $cart[$cartKey];
                $item['quantity'] = $quantity;
                $item['total_price'] = $quantity * $item['price'];
                $checkoutItems[] = $item;
                $total += $item['total_price'];
            }
        }
    }

    // Lưu vào session để dùng sau khi thanh toán
    session(['checkout_items' => $checkoutItems]);

    return view('client.pages.checkout-confirm', compact('checkoutItems', 'total', 'user'));
}


    public function process(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:cod,vnpay',
        ]);

        $user = Auth::user();
        $checkoutItems = session('checkout_items', []);

        if (!$checkoutItems) {
            return redirect()->route('cart')->with('error', 'Không có sản phẩm nào trong giỏ hàng!');
        }

        try {
            $orderCode = 'OD' . strtoupper(Str::random(8));

            $order = Order::create([
                'order_code' => $orderCode,
                'user_id' => $user->id,
                'payment_method' => $request->payment_method,
                'status' => 'processing',
            ]);

            foreach ($checkoutItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'product_name' => $item['name'],
                    'color' => $item['color'],
                    'size' => $item['size'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            session()->forget(['checkout_items', 'cart']);

            return redirect()->route('orders.index')->with('success', 'Đặt hàng thành công!');

        } catch (\Exception $e) {
            Log::error("Lỗi khi đặt hàng: " . $e->getMessage());
            return redirect()->route('checkout.show')->with('error', 'Lỗi khi đặt hàng, vui lòng thử lại!');
        }
    }

    public function vnpayReturn(Request $request)
    {
        $vnp_TxnRef = $request->input('vnp_TxnRef');
        $vnp_ResponseCode = $request->input('vnp_ResponseCode');
        $vnp_Amount = $request->input('vnp_Amount') / 100;

        Log::info("VNPay Callback - Session Data: ", session()->all());

        $checkoutItems = session('checkout_items', []);
        $user = Auth::user();

        if ($vnp_ResponseCode == "00") {
            if (!$checkoutItems || !$user) {
                return redirect()->route('cart')->with('error', "Không tìm thấy dữ liệu đơn hàng trong phiên làm việc!");
            }

            $orderCode = 'OD' . strtoupper(Str::random(8));

            $order = Order::create([
                'order_code' => $vnp_TxnRef,
                'user_id' => $user->id,
                'payment_method' => 'vnpay',
                'status' => 'completed',
            ]);

            foreach ($checkoutItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'product_name' => $item['name'],
                    'color' => $item['color'],
                    'size' => $item['size'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            session()->forget(['checkout_items', 'cart']);

            return redirect()->route('orders.index')->with('success', 'Thanh toán thành công. Mã đơn hàng: ' . $order->order_code);
        } else {
            return redirect()->route('checkout.show')->with('error', 'Thanh toán thất bại. Mã lỗi: ' . $vnp_ResponseCode);
        }
    }
}