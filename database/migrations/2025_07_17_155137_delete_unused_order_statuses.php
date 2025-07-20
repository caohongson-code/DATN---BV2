<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Bước 1: Cập nhật orders -> chuyển status_id 3 -> 1, 4 -> 2 (ví dụ)
        DB::table('orders')->where('order_status_id', 3)->update(['order_status_id' => 1]);
        DB::table('orders')->where('order_status_id', 4)->update(['order_status_id' => 2]);

        // Bước 2: Xoá 2 trạng thái không còn dùng
        DB::table('order_statuses')->whereIn('id', [3, 4])->delete();

        // Bước 3: Cập nhật lại danh sách đầy đủ các trạng thái
        DB::statement('ALTER TABLE order_statuses AUTO_INCREMENT = 1');
        DB::table('order_statuses')->upsert([
            ['id' => 1, 'status_name' => 'Chờ xác nhận',         'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'status_name' => 'Đã xác nhận',          'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'status_name' => 'Đang chuẩn bị hàng',   'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'status_name' => 'Đang giao hàng',        'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'status_name' => 'Đã giao hàng',          'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'status_name' => 'Trả hàng / Hoàn tiền', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'status_name' => 'Đã huỷ',                'created_at' => now(), 'updated_at' => now()],
        ], ['id'], ['status_name', 'updated_at']);
    }

    public function down(): void
    {
        // Không nên rollback để insert lại ID 3 và 4 nếu không cần thiết
        // Có thể ghi logic ngược nếu cần
    }
};

