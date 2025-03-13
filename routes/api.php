<?php

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});




Route::post('/vietqr/webhook', function (Request $request) {
    Log::info('Nhận webhook từ VietQR:', $request->all());

    $orderCode = $request->input('order_code');
    $status = $request->input('status');

    $order = Order::where('order_code', $orderCode)->first();

    if ($order) {
        if ($status === 'success') {
            $order->update(['payment_status' => 'completed']);
            return response()->json(['message' => 'Thanh toán cập nhật thành công']);
        } else {
            return response()->json(['message' => 'Thanh toán thất bại'], 400);
        }
    }

    return response()->json(['message' => 'Không tìm thấy đơn hàng'], 404);
});
