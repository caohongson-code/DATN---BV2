<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class DeletePaymentStatusesFromOrderStatusesTable extends Migration
{
    public function up()
    {
        DB::table('order_statuses')->whereIn('id', [3, 4])->delete();
    }

    public function down()
    {
        DB::table('order_statuses')->insert([
            ['id' => 3, 'status_name' => 'Chờ thanh toán', 'payment_status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'status_name' => 'Đã thanh toán', 'payment_status' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
