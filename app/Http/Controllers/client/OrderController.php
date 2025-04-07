<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Mail\OrderSuccessMail;
use App\Models\Color;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\ProductVariant;
use App\Models\Size;
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
      // ✅ Lấy mã giảm giá từ session nếu có
    $appliedCode = session('applied_promo_code');
        $order = Order::create([
            'order_code'      => $orderCode,
            'user_id'         => $user->id,
            'payment_method'  => 'cod',
            'status'          => 'processing',
            'promotion_code'  => $appliedCode ?? null, // nếu không có sẽ là null
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
        session()->forget(['checkout_items', 'applied_promo_code']);

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
    try {
        $orderId = $request->input('order_id');
        $cancelReason = $request->input('cancel_reason');

        $order = Order::with('items')->find($orderId);

        if (!$order) {
            return response()->json(['status' => 'error', 'message' => 'Đơn hàng không tồn tại.']);
        }

        if ($order->status === 'cancelled') {
            return response()->json(['status' => 'error', 'message' => 'Đơn hàng đã bị huỷ trước đó.']);
        }

        if ($order->status !== 'processing') {
            return response()->json([
                'status' => 'error',
                'message' => 'Chỉ những đơn hàng đang xử lý mới có thể huỷ. Hiện tại đơn hàng đang ở trạng thái: ' . strtoupper($order->status)
            ]);
        }

        // ✅ Trả lại số lượng cho từng sản phẩm (theo product_id + size_name + color_name)
        foreach ($order->items as $item) {
            // Tìm ID của size & color theo tên
            $sizeId = Size::where('size_name', trim(strtolower($item->size)))->value('id');
            $colorId = Color::where('color_name', trim(ucfirst(strtolower($item->color))))->value('id');

            if (!$sizeId || !$colorId) {
                \Log::warning("Không tìm thấy size hoặc color cho OrderItem #{$item->id} - size: {$item->size}, color: {$item->color}");
                continue;
            }

            // Tìm biến thể sản phẩm
            $variant = ProductVariant::where('product_id', $item->product_id)
                ->where('size_id', $sizeId)
                ->where('color_id', $colorId)
                ->first();

            if ($variant) {
                $variant->stock_quantity += $item->quantity;
                $variant->save();
            } else {
                \Log::warning("Không tìm thấy biến thể cho OrderItem #{$item->id} (product_id={$item->product_id}, size_id={$sizeId}, color_id={$colorId})");
            }
        }

        $order->status = 'cancelled';
        $order->cancel_reason = $cancelReason;
        $order->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Huỷ đơn hàng thành công! Số lượng sản phẩm đã trả về kho.'
        ]);
    } catch (\Throwable $e) {
        \Log::error('Lỗi khi huỷ đơn hàng: ' . $e->getMessage());
        return response()->json([
            'status' => 'error',
            'message' => 'Lỗi server: ' . $e->getMessage()
        ], 500);
    }
}

    public function exportPDF($id)
{
    $order = Order::with('user')->findOrFail($id);
    $items = $order->items;

    $pdf = Pdf::loadView('admin.orders.pdf', compact('order', 'items'));
    return $pdf->download('don-hang-'.$order->order_code.'.pdf');
}

// Trong OrderController.php
public function markAsReturned(Request $request, $id)
{
    $order = Order::findOrFail($id);

    // Validate
    $request->validate([
        'return_reason' => 'required|string',
        'return_media' => 'required|file|mimes:jpeg,png,jpg,mp4,mov,avi|max:10240'
    ]);

    // Upload file
    $filePath = null;
    if ($request->hasFile('return_media')) {
        $file = $request->file('return_media');
        $fileName = time().'_'.$file->getClientOriginalName();
        $filePath = $file->storeAs('returns', $fileName, 'public');
    }

    $order->return_reason = $request->input('return_reason');
    $order->return_media = $filePath;
    $order->status = 'returning'; // nếu có trạng thái riêng
    $order->save();

    return redirect()->route('order')->with('success', 'Đã gửi yêu cầu hoàn hàng!');
}

}
