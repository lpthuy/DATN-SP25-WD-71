<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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

        // ✅ Gửi email xác nhận đơn hàng
        try {
            Mail::to($user->email)->send(new OrderSuccessMail($order));
        } catch (\Exception $e) {
            Log::error('Lỗi gửi mail xác nhận đơn hàng: ' . $e->getMessage());
        }

//     $order = Order::create([
//         'order_code' => $orderCode,
//         'user_id' => $user->id,
//         'payment_method' => 'cod',
//         'status' => 'processing',
//     ]);


    foreach ($checkoutItems as $item) {
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $item['product_id'] ?? null,
            'product_name' => $item['name'] ?? '',
            'color' => $item['color'] ?? 'Không có',
            'size' => $item['size'] ?? 'Không có',
            'quantity' => $item['quantity'] ?? 1,
            'price' => $item['price'] ?? 0,
        ]);
    }

    session()->forget(['checkout_items']);

    return response()->json([
        'status' => 'success',
        'message' => 'Đặt hàng thành công!',
        'order_code' => $orderCode,
        'redirect' => route('order') // hoặc route('order.show', $order->id) nếu có route riêng
    ]);
}


    


    public function index() {
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


        // ✅ Trả lại số lượng cho từng sản phẩm (theo product_id + size_name + color_name)
        foreach ($order->items as $item) {
            // Tìm ID của size & color theo tên
            $sizeId = Size::where('size_name', trim(strtolower($item->size)))->value('id');
            $colorId = Color::where('color_name', trim(ucfirst(strtolower($item->color))))->value('id');

            if (!$sizeId || !$colorId) {
                Log::warning("Không tìm thấy size hoặc color cho OrderItem #{$item->id} - size: {$item->size}, color: {$item->color}");
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
                Log::warning("Không tìm thấy biến thể cho OrderItem #{$item->id} (product_id={$item->product_id}, size_id={$sizeId}, color_id={$colorId})");
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
        Log::error('Lỗi khi huỷ đơn hàng: ' . $e->getMessage());
        return response()->json([
            'status' => 'error',
            'message' => 'Lỗi server: ' . $e->getMessage()
        ], 500);
    }

//     // Lấy các sản phẩm trong đơn hàng đó
//     $items = OrderItem::where('order_id', $order->id)->get();

//     return view('client.pages.order_detail', compact('order', 'items'));

}


    



}
public function markAsReceived($id)
{
    $order = Order::findOrFail($id);
    $order->status = 'received'; // Cập nhật trạng thái theo logic của bạn
    $order->save();


    return redirect()->back()->with('success', 'Đơn hàng đã được xác nhận là đã nhận.');
}
}

