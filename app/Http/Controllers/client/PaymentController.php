<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function vnpay_payment(Request $request)
{
    $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
    $vnp_Returnurl = route('vnpay.return');
    $vnp_TmnCode = "8KRHG0YN";
    $vnp_HashSecret = "BJM1MZ1B4BZ946AJ0BPBWZQL6YOLF4T7";

    $price = (int) $request->input('price'); 
    $quantity = (int) $request->input('quantity'); 
    $totalAmount = $price * $quantity; 

    if (!$price || !$quantity || $price <= 0 || $quantity <= 0) {
        return response()->json(["code" => "01", "message" => "Dữ liệu không hợp lệ!"]);
    }

    $vnp_TxnRef = time(); // Mã giao dịch duy nhất
    $vnp_OrderInfo = "Thanh toán đơn hàng";
    $vnp_Amount = $totalAmount * 100; // Chuyển sang đơn vị VNĐ
    $vnp_Locale = "VN";
    $vnp_BankCode = $request->input('bank_code', "NCB");
    $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

    // 🛑 LƯU THÔNG TIN ĐƠN HÀNG VÀO SESSION 🛑
    session([
        'product_id' => $request->input('product_id'),
        'product_name' => $request->input('product_name'),
        'color' => $request->input('color'),
        'size' => $request->input('size'),
        'quantity' => $quantity,
        'price' => $price, 
        'total_amount' => $totalAmount
    ]);

    // Tạo dữ liệu gửi đi
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

    // Sắp xếp và tạo URL VNPay
    ksort($inputData);
    $query = "";
    $hashdata = "";
    foreach ($inputData as $key => $value) {
        $hashdata .= ($hashdata ? '&' : '') . urlencode($key) . "=" . urlencode($value);
        $query .= urlencode($key) . "=" . urlencode($value) . "&";
    }

    $vnp_Url .= "?" . $query;
    $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
    $vnp_Url .= "vnp_SecureHash=" . $vnpSecureHash;

    return response()->json([
        'code' => '00',
        'message' => 'success',
        'data' => $vnp_Url,
        'transaction_code' => $vnp_TxnRef
    ]);
}



public function vnpayReturn(Request $request)
{
    $vnp_TxnRef = $request->input('vnp_TxnRef');
    $vnp_ResponseCode = $request->input('vnp_ResponseCode');
    $vnp_Amount = $request->input('vnp_Amount') / 100;

    // 🛑 THÊM LOG ĐỂ KIỂM TRA SESSION 🛑
    Log::info("VNPay Callback - Session Data: ", session()->all());

    if ($vnp_ResponseCode == "00") { // Giao dịch thành công
        // 🛑 KIỂM TRA LẠI SESSION TRƯỚC KHI LƯU 🛑
        if (!session()->has('product_id') || !session('product_id')) {
            return redirect()->route('order')->with('error', "Lỗi: Không tìm thấy thông tin sản phẩm trong session!");
        }

        $order = Order::create([
            'order_code' => $vnp_TxnRef,
            'user_id' => auth()->id(),
            'product_id' => session('product_id'),
            'product_name' => session('product_name'),
            'color' => session('color'),
            'size' => session('size'),
            'quantity' => session('quantity'),
            'price' => $vnp_Amount,
            'payment_status' => 'completed',
            'payment_method' => 'VNPAY',
            'paid_amount' => $vnp_Amount,
        ]);

        // Xóa session sau khi lưu đơn hàng
        session()->forget(['product_id', 'product_name', 'color', 'size', 'quantity']);

        return redirect()->route('order')->with('success', "Thanh toán thành công! Mã đơn hàng: $order->order_code");
    } else {
        return redirect()->route('order')->with('error', "Thanh toán thất bại. Mã lỗi: $vnp_ResponseCode");
    }
}



}

// try {
        //     \Log::info('Bắt đầu xử lý thanh toán VNPAY', ['data' => $request->all()]);
            
        //     $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        //     $vnp_Returnurl = route('vnpay.return'); // Cập nhật route hợp lệ
        //     $vnp_TmnCode = "8KRHG0YN"; // Mã website tại VNPAY 
        //     $vnp_HashSecret = "BJM1MZ1B4BZ946AJ0BPBWZQL6YOLF4T7"; // Chuỗi bí mật
    
        //     $vnp_TxnRef = "1000000"; 
        //     $vnp_OrderInfo = "Thanh Toán Hoá Đơn";
        //     $vnp_OrderType = "RainBowShop";
        //     $vnp_Amount = 10000 * 100;
        //     $vnp_Locale = "VN";
        //     $vnp_BankCode = "NCB";
        //     $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
    
        //     $inputData = array(
        //         "vnp_Version" => "2.1.0",
        //         "vnp_TmnCode" => $vnp_TmnCode,
        //         "vnp_Amount" => $vnp_Amount,
        //         "vnp_Command" => "pay",
        //         "vnp_CreateDate" => date('YmdHis'),
        //         "vnp_CurrCode" => "VND",
        //         "vnp_IpAddr" => $vnp_IpAddr,
        //         "vnp_Locale" => $vnp_Locale,
        //         "vnp_OrderInfo" => $vnp_OrderInfo,
        //         "vnp_OrderType" => $vnp_OrderType,
        //         "vnp_ReturnUrl" => $vnp_Returnurl,
        //         "vnp_TxnRef" => $vnp_TxnRef,
                
        //     );

        //     if (!empty($vnp_BankCode)) {
        //         $inputData['vnp_BankCode'] = $vnp_BankCode;
        //     }
    
        //     ksort($inputData);
        //     $query = http_build_query($inputData);
        //     $hashData = urldecode($query);
        //     $vnpSecureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
            
        //     $vnp_Url .= "?" . $query . "&vnp_SecureHash=" . $vnpSecureHash;
    
        //     \Log::info('VNPAY URL: ' . $vnp_Url);
    
        //     return response()->json([
        //         'status' => 'success',
        //         'payment_url' => $vnp_Url
        //     ]);
        // } catch (\Exception $e) {
        //     \Log::error('Lỗi khi tạo thanh toán VNPAY: ' . $e->getMessage());
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Lỗi hệ thống, vui lòng thử lại!'
        //     ], 500);
        // }