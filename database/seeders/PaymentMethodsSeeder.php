<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PaymentMethodsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('payment_methods')->insert([
            [
                'id' => 1,
                'method_name' => 'Thanh toán khi nhận hàng',
                'code' => 'cod',
                'created_at' => Carbon::parse('2025-06-22 09:12:14'),
                'updated_at' => Carbon::parse('2025-06-22 09:12:14'),
            ],
            [
                'id' => 3,
                'method_name' => 'Ví MoMo',
                'code' => 'momo',
                'created_at' => Carbon::parse('2025-06-22 09:12:14'),
                'updated_at' => Carbon::parse('2025-06-22 09:12:14'),
            ],
            [
                'id' => 4,
                'method_name' => 'Ví nội bộ',
                'code' => 'wallet',
                'created_at' => Carbon::parse('2025-08-04 08:47:27'),
                'updated_at' => Carbon::parse('2025-08-04 08:47:27'),
            ],
        ]);
    }
}
