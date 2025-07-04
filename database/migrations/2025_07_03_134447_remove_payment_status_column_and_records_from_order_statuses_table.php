<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class RemovePaymentStatusColumnAndRecordsFromOrderStatusesTable extends Migration
{
    public function up()
    {
        // Bước 1: Cập nhật các đơn hàng đang dùng status 3 và 4 → chuyển về status mặc định (ví dụ 1: Chờ xác nhận)
        DB::table('orders')->where('order_status_id', 3)->update(['order_status_id' => 1]);
        DB::table('orders')->where('order_status_id', 4)->update(['order_status_id' => 1]);

        // Bước 2: Xoá 2 dòng trạng thái
        DB::table('order_statuses')->whereIn('id', [3, 4])->delete();

        // Bước 3: Xoá cột payment_status
        Schema::table('order_statuses', function (Blueprint $table) {
            $table->dropColumn('payment_status');
        });
    }

    public function down()
    {
        // Bước 1: Thêm lại cột nếu rollback
        Schema::table('order_statuses', function (Blueprint $table) {
            $table->tinyInteger('payment_status')->nullable()->after('status_name');
        });

        // Bước 2: Thêm lại 2 dòng trạng thái
        DB::table('order_statuses')->insert([
            [
                'id' => 3,
                'status_name' => 'Chờ thanh toán',
                'payment_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 4,
                'status_name' => 'Đã thanh toán',
                'payment_status' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
