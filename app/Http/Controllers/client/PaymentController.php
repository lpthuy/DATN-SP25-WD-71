<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function vnpay_payment(Request $request)
    {
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = route('vnpay.return');
        $vnp_TmnCode = "42KMOEEN";
        $vnp_HashSecret = "L6BDRBS2Y4JH5VO6SZYWJX4UEQ6U5UKB";

        $price = (int) $request->input('price');
        $quantity = (int) $request->input('quantity');
        $totalAmount = $price * $quantity;

        if (!$price || !$quantity || $price <= 0 || $quantity <= 0) {
            return response()->json(["code" => "01", "message" => "Dữ liệu không hợp lệ!"]);
        }

        $vnp_TxnRef = time();
        $vnp_OrderInfo = "Thanh toán đơn hàng";
        $vnp_Amount = $totalAmount * 100; // nhân 100 vì VNPAY tính đơn vị là VND x 100
        $vnp_Locale = "VN";
        $vnp_BankCode = $request->input('bank_code', "NCB");
        $vnp_IpAddr = $request->ip(); // Lấy IP an toàn hơn

        // Lưu vào session
        session([
            'order_code' => $vnp_TxnRef,
            'checkout_items' => session('checkout_items', []),
        ]);

        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => "billpayment",
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        ];

        if (!empty($vnp_BankCode)) {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        ksort($inputData);
        $hashdata = urldecode(http_build_query($inputData));
        $query = http_build_query($inputData);
        $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
        $vnp_Url .= '?' . $query . '&vnp_SecureHash=' . $vnpSecureHash;

        return response()->json([
            'code' => '00',
            'message' => 'success',
            'data' => $vnp_Url,
            'transaction_code' => $vnp_TxnRef
        ]);
    }


    public function vnpayReturn(Request $request)
    {
        $vnp_HashSecret = "L6BDRBS2Y4JH5VO6SZYWJX4UEQ6U5UKB";
        $vnp_SecureHash = $request->input('vnp_SecureHash');
        $inputData = $request->except(['vnp_SecureHash', 'vnp_SecureHashType']);

        ksort($inputData);
        $hashData = urldecode(http_build_query($inputData));
        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        if ($secureHash !== $vnp_SecureHash) {
            return redirect()->route('order')->with('error', "Dữ liệu không hợp lệ (chữ ký sai)!");
        }

        $vnp_TxnRef = $request->input('vnp_TxnRef');
        $vnp_ResponseCode = $request->input('vnp_ResponseCode');
        $vnp_Amount = $request->input('vnp_Amount') / 100;

        Log::info("VNPay Callback - Session Data: ", session()->all());

        $checkoutItems = session('checkout_items', []);
        $orderCode = session('order_code');
        $userId = auth()->id();

        if ($vnp_ResponseCode == "00" && $checkoutItems && $userId) {
            $order = Order::create([
                'order_code' => $orderCode,
                'user_id' => $userId,
                'payment_method' => 'VNPAY',
                'status' => 'processing',
                'total' => $vnp_Amount,
            ]);

            foreach ($checkoutItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'] ?? null,
                    'product_name' => $item['name'] ?? '',
                    'color' => $item['color'] ?? '',
                    'size' => $item['size'] ?? '',
                    'quantity' => $item['quantity'] ?? 1,
                    'price' => $item['price'] ?? 0,
                ]);
            }

            session()->forget(['checkout_items', 'order_code']);

            return redirect()->route('order')->with('success', "Thanh toán thành công! Mã đơn hàng: $order->order_code");
        } else {
            return redirect()->route('order')->with('error', "Thanh toán thất bại. Mã lỗi: $vnp_ResponseCode");
        }
    }
}
