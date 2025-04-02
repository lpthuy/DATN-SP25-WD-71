<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Mail\OrderSuccessMail;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{

    public function store(Request $request)
    {
        $user = Auth::user();
        $checkoutItems = session('checkout_items', []);
        $orderCode = 'OD' . strtoupper(Str::random(8));

        if (!$checkoutItems || !$user) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy giỏ hàng hoặc chưa đăng nhập!']);
        }

        $order = Order::create([
            'order_code'      => $orderCode,
            'user_id'         => $user->id,
            'payment_method'  => 'cod',
            'status'          => 'processing',
        ]);

        foreach ($checkoutItems as $item) {
            // Ghi lại chi tiết đơn hàng
            OrderItem::create([
                'order_id'      => $order->id,
                'product_id'    => $item['product_id'] ?? null,
                'product_name'  => $item['name'] ?? 'Không rõ tên',
                'color'         => $item['color'] ?? 'Không chọn',
                'size'          => $item['size'] ?? 'Không chọn',
                'quantity'      => $item['quantity'] ?? 1,
                'price'         => $item['price'] ?? 0,
            ]);

            // ✅ Trừ số lượng tồn kho
            if (!empty($item['variant_id'])) {
                $variant = \App\Models\ProductVariant::find($item['variant_id']);
                if ($variant) {
                    $variant->stock_quantity = max(0, $variant->stock_quantity - $item['quantity']);
                    $variant->save();
                }
            }
        }

        // Xóa session giỏ hàng sau khi lưu đơn hàng
        session()->forget(['checkout_items']);

        // ✅ Gửi email xác nhận đơn hàng
        try {
            Mail::to($user->email)->send(new OrderSuccessMail($order));
        } catch (\Exception $e) {
            \Log::error('Lỗi gửi mail xác nhận đơn hàng: ' . $e->getMessage());
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Đặt hàng thành công!',
            'order_code' => $orderCode,
            'redirect' => route('order') // Hoặc route('order.detail', $order->id)
        ]);
    }







    public function index()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)->orderBy('created_at', 'desc')->paginate(10);
        return view('client.pages.order', compact('user', 'orders'));
    }


    public function checkPaymentStatus(Request $request)
    {
        $productId = $request->query('product_id');

        if (!$productId) {
            return response()->json(['error' => 'Thiếu product_id'], 400);
        }

        // Kiểm tra xem có đơn hàng nào với product_id tương ứng hay không
        $order = Order::where('product_id', $productId)->first();

        if (!$order) {
            return response()->json(['error' => 'Không tìm thấy đơn hàng'], 404);
        }

        return response()->json([
            'payment_status' => $order->payment_status,
            'order_code' => $order->order_code
        ]);
    }



    public function show($id)
    {
        $user = Auth::user();

        // Lấy đơn hàng
        $order = Order::where('id', $id)->where('user_id', $user->id)->firstOrFail();

        // Lấy các sản phẩm trong đơn hàng đó
        $items = OrderItem::where('order_id', $order->id)->get();

        return view('client.pages.order_detail', compact('order', 'items'));
    }


    public function cancelOrder(Request $request)
    {
        $orderId = $request->input('order_id');
        $cancelReason = $request->input('cancel_reason');

        $order = Order::find($orderId);

        if (!$order) {
            return response()->json(['status' => 'error', 'message' => 'Đơn hàng không tồn tại.']);
        }

        if ($order->status === 'cancelled') {
            return response()->json(['status' => 'error', 'message' => 'Đơn hàng đã bị huỷ trước đó.']);
        }

        // ✅ Chỉ được huỷ nếu đang ở trạng thái "processing"
        if ($order->status !== 'processing') {
            return response()->json([
                'status' => 'error',
                'message' => 'Chỉ những đơn hàng đang xử lý mới có thể huỷ. Hiện tại đơn hàng đang ở trạng thái: ' . strtoupper($order->status)
            ]);
        }

        // ✅ Tiến hành huỷ đơn
        $order->status = 'cancelled';
        $order->cancel_reason = $cancelReason;
        $order->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Huỷ đơn hàng thành công!'
        ]);
    }

    public function exportPDF($id)
{
    $order = Order::with('user')->findOrFail($id);
    $items = $order->items;

    $pdf = Pdf::loadView('admin.orders.pdf', compact('order', 'items'));
    return $pdf->download('don-hang-'.$order->order_code.'.pdf');
}
}
