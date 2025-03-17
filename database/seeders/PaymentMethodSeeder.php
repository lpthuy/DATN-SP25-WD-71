<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!PaymentMethod::where('method_name', 'VNPay')->exists()) {
            PaymentMethod::create([
                'method_name' => 'VNPay',
                'description' => 'Thanh toán qua cổng VNPay',
                'image_path' => 'images/payments/vnpay.png'
            ]);
        }
    }
}
