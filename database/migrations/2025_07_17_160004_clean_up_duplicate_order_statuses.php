<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Bước 1: Chuyển tất cả order_status_id 8 -> 6, 9 -> 7
        DB::table('orders')->where('order_status_id', 8)->update(['order_status_id' => 6]);
        DB::table('orders')->where('order_status_id', 9)->update(['order_status_id' => 7]);

        // Bước 2: Xoá các trạng thái trùng lặp
        DB::table('order_statuses')->whereIn('id', [8, 9])->delete();
    }

    public function down(): void
    {
        // Khôi phục nếu cần (không khuyến khích nếu dữ liệu cũ không cần)
        DB::table('order_statuses')->insert([
            ['id' => 8, 'status_name' => 'Trả hàng / Hoàn tiền', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 9, 'status_name' => 'Đã huỷ', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
};
