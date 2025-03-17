<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // Kiểm tra đăng nhập (nếu dự án yêu cầu)
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để mua hàng!');
        }

        // Validate cơ bản
        $request->validate([
            'payment_method'   => 'required|string',         // 'cod' | 'vnpay'
            'shipping_address' => 'required|string|max:255', // địa chỉ giao hàng
            'items'            => 'required|array|min:1',    // ít nhất 1 sản phẩm
        ]);

        // Tính tổng tiền
        $items = $request->input('items', []);
        $totalPrice = 0;
        foreach ($items as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }

        // Tạo đơn hàng (bảng `orders`)
        $order = Order::create([
            'order_code'       => 'OD' . date('YmdHis') . strtoupper(Str::random(4)),
            'user_id'          => Auth::id(),
            'price'            => $totalPrice,
            'payment_method'   => $request->payment_method,
            'payment_status'   => 'pending',
            'shipping_address' => $request->shipping_address,
        ]);

        // Lưu chi tiết sản phẩm (bảng `order_details`)
        // Chỉ làm nếu bạn có bảng order_details và Model OrderDetail
        foreach ($items as $item) {
            // Tuỳ dự án, bạn lấy tên sản phẩm, màu sắc, size... từ DB hoặc form
            // Ở đây giả định form đã có product_name, color, size
            // Nếu chưa có, bạn phải query từ bảng products
            OrderDetail::create([
                'order_id'      => $order->id,
                'product_id'    => $item['product_id'] ?? null,
                'product_name'  => $item['product_name'] ?? 'Sản phẩm',
                'color'         => $item['color'] ?? null,
                'size'          => $item['size'] ?? null,
                'quantity'      => $item['quantity'],
                'price'         => $item['price'],
            ]);
        }

        // Xoá giỏ hàng nếu muốn
        session()->forget('cart');

        // Nếu user chọn thanh toán VNPay
        if ($request->payment_method === 'vnpay') {
            // Gọi PaymentController để tạo URL thanh toán
            return app(\App\Http\Controllers\Admin\PaymentController::class)
                ->processVNPayPayment($order);
        }

        // Nếu COD thì trả về trang chi tiết đơn hàng
        return redirect()->route('orders.show', $order->id)
            ->with('success', 'Đặt hàng COD thành công!');
    }

    public function show(Order $order)
    {
        // Nếu cần kiểm tra quyền truy cập:
        // if ($order->user_id !== Auth::id()) {
        //     abort(403);
        // }
        return view('client.pages.order_detail', compact('order'));
    }
}
