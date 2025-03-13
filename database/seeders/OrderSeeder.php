<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use Faker\Factory as Faker;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Tạo 100 đơn hàng giả
        for ($i = 0; $i < 100; $i++) {
            Order::create([
                'order_date' => $faker->dateTimeBetween('-1 year', 'now'),
                'total_amount' => $faker->randomFloat(2, 50, 1000), // Số tiền từ 50 đến 1000
                'status' => $faker->randomElement(['pending', 'processing', 'shipped', 'delivered', 'cancelled']),
                'payment_method' => $faker->randomElement(['credit_card', 'cash_on_delivery', 'bank_transfer']),
                'shipping_cost' => $faker->randomFloat(2, 5, 50), // Phí vận chuyển từ 5 đến 50
                'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
                'updated_at' => now(),
            ]);
        }
    }
}
