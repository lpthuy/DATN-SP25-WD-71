<?php
namespace App\Services;

use Illuminate\Support\Str;

class VNPayService
{
    public function generatePaymentData(array $data)
    {
        return [
            'vnp_Version' => '2.1.0',
            'vnp_TmnCode' => config('vnpay.tmn_code'),
            'vnp_Amount' => $data['amount'] * 100,
            'vnp_Command' => 'pay',
            'vnp_CreateDate' => now()->format('YmdHis'),
            'vnp_CurrCode' => config('vnpay.currency'),
            'vnp_IpAddr' => request()->ip(),
            'vnp_Locale' => config('vnpay.locale'),
            'vnp_OrderInfo' => $data['order_info'],
            'vnp_OrderType' => $data['order_type'] ?? 'other',
            'vnp_ReturnUrl' => config('vnpay.return_url'),
            'vnp_TxnRef' => Str::uuid(),
        ];
    }

    public function generateSecureHash(array $inputData)
    {
        ksort($inputData);
        return hash_hmac('sha512', http_build_query($inputData), config('vnpay.hash_secret'));
    }
}