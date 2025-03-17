<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    // Xử lý chuyển hướng sang cổng VNPay
    public function processVNPayPayment(Order $order)
    {
        $vnp_Url       = config('vnpay.vnp_Url');
        $vnp_TmnCode   = config('vnpay.vnp_TmnCode');
        $vnp_HashSecret = config('vnpay.vnp_HashSecret');
        $vnp_ReturnUrl = config('vnpay.vnp_ReturnUrl');

        $inputData = [
            'vnp_Version'    => '2.1.0',
            'vnp_TmnCode'    => $vnp_TmnCode,
            'vnp_Amount'     => $order->price * 100,
            'vnp_Command'    => 'pay',
            'vnp_CreateDate' => now()->format('YmdHis'),
            'vnp_CurrCode'   => 'VND',
            'vnp_IpAddr'     => request()->ip(),
            'vnp_Locale'     => 'vn',
            'vnp_OrderInfo'  => 'Thanh toán đơn hàng ' . $order->order_code,
            'vnp_OrderType'  => 'other',
            'vnp_ReturnUrl'  => $vnp_ReturnUrl,
            'vnp_TxnRef'     => $order->order_code,
        ];

        ksort($inputData);
        $query = http_build_query($inputData);
        $hashData = urldecode($query);

        if ($vnp_HashSecret) {
            $vnpSecureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
            $query .= '&vnp_SecureHash=' . $vnpSecureHash;
        }

        $fullUrl = $vnp_Url . '?' . $query;
        return redirect($fullUrl);
    }




    // Xử lý kết quả trả về từ VNPay
    public function handleVNPayReturn(Request $request)
    {
        $vnp_SecureHash = $request->input('vnp_SecureHash');
        // Lấy tất cả tham số bắt đầu bằng vnp_ từ request
        $inputData = $request->only(array_filter(
            $request->keys(),
            fn($key) => strpos($key, 'vnp_') === 0
        ));

        // Loại bỏ vnp_SecureHash để tính lại chữ ký
        unset($inputData['vnp_SecureHash']);

        ksort($inputData);
        $hashData = urldecode(http_build_query($inputData));
        $vnp_HashSecret = config('vnpay.vnp_HashSecret');

        $secureHashCalculated = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        // Kiểm tra chữ ký
        if ($secureHashCalculated !== $vnp_SecureHash) {
            // Log lỗi nếu cần
            Log::error("VNPay: Invalid signature", ['inputData' => $inputData, 'vnp_SecureHash' => $vnp_SecureHash, 'calculated' => $secureHashCalculated]);
            return redirect()->route('orders.show', 0)->with('error', 'Chữ ký không hợp lệ.');
        }

        // Tìm đơn hàng theo vnp_TxnRef (order_code)
        $order = Order::where('order_code', $request->input('vnp_TxnRef'))->first();
        if (!$order) {
            return redirect()->route('orders.show', 0)->with('error', 'Không tìm thấy đơn hàng.');
        }

        // Kiểm tra kết quả thanh toán (ví dụ vnp_ResponseCode, vnp_TransactionStatus)
        if ($request->input('vnp_ResponseCode') === '00') {
            // Thanh toán thành công
            $order->update([
                'payment_status' => 'completed',
                'transaction_id' => $request->input('vnp_TransactionNo')
            ]);
            return redirect()->route('orders.show', $order->id)->with('success', 'Thanh toán VNPay thành công!');
        } else {
            // Thanh toán thất bại
            $order->update(['payment_status' => 'failed']);
            return redirect()->route('orders.show', $order->id)->with('error', 'Thanh toán VNPay thất bại.');
        }
    }
}
