<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::latest()->get(); // 💥 lấy toàn bộ luôn
    
        return response()->json([
            'status' => 'success',
            'orders' => $orders,
        ]);
    }
    



    // Trong OrderController.php
    public function updateStatus(Request $request, $id)
{
    \Log::info('📦 [Shipper] Yêu cầu cập nhật trạng thái đơn hàng', [
        'shipper_id' => auth()->id(),
        'order_id' => $id,
        'new_status' => $request->status,
    ]);

    // ✅ Kiểm tra quyền
    if (auth()->user()->role !== 'shipper') {
        \Log::warning('🚫 Truy cập trái phép - không phải shipper', ['user_id' => auth()->id()]);
        return response()->json(['message' => 'Bạn không có quyền thực hiện thao tác này'], 403);
    }

    // ✅ Validate đầu vào
    $request->validate([
        'status' => 'required|string|in:shipping,completed,cancelled',
    ]);

    // ✅ Cập nhật đơn hàng
    $order = Order::findOrFail($id);
    $order->update([
        'status' => $request->status,
    ]);

    // ✅ Truy vấn lại trạng thái sau khi lưu
    $refreshedOrder = Order::find($order->id);

    \Log::info('✅ Trạng thái đã được cập nhật trong CSDL', [
        'order_id' => $order->id,
        'saved_status' => $refreshedOrder->status,
    ]);

    return response()->json([
        'status' => 'success',
        'message' => 'Đã cập nhật trạng thái',
        'order' => $refreshedOrder,
    ]);
}

    



    

}
