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
            3 => 'Đang chuẩn bị hàng',
            4 => 'Đang giao hàng',
            5 => 'Đã giao hàng',
            6 => 'Trả hàng / Hoàn tiền',
            7 => 'Đã huỷ',
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
