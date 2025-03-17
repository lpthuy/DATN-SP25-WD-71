<?php
return [
    'vnp_TmnCode'   => env('VNPAY_TMN_CODE', '2KVYC04T'),
    'vnp_HashSecret'=> env('VNPAY_HASH_SECRET', '9B8NO8YE0169GWYPBP1EYGPK1KG2XCT0'),
    'vnp_Url'       => env('VNPAY_URL', '  http://sandbox.vnpayment.vn/tryitnow/Home/CreateOrder'),
    'vnp_ReturnUrl' => env('VNPAY_RETURN_URL', 'https://abc123.ngrok.io/payment/return'),
    'vnp_ApiUrl'    => env('VNPAY_API_URL', 'https://sandbox.vnpayment.vn/merchant_webapi/api/transaction'),
];
