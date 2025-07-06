<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderStatusSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $statuses = [
            1 => 'Chờ xác nhận',
            2 => 'Đã xác nhận',
            3 => 'Chờ thanh toán',
            4 => 'Đã thanh toán',
            5 => 'Đang chuẩn bị hàng',
            6 => 'Đang giao hàng',
            7 => 'Đã giao hàng',
            8 => 'Trả hàng / Hoàn tiền',
            9 => 'Đã huỷ',
        ];

        foreach ($statuses as $id => $name) {
            DB::table('order_statuses')->updateOrInsert(
                ['id' => $id],
                [
                    'status_name' => $name,
                    'updated_at' => $now,
                    'created_at' => $now
                ]
            );
        }
    }
}
