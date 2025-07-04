<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddPaymentStatusToOrderStatusesTable extends Migration
{
    public function up()
    {
        Schema::table('order_statuses', function (Blueprint $table) {
            $table->tinyInteger('payment_status')->nullable()->after('status_name'); // 1 = Chờ thanh toán, 2 = Đã thanh toán
        });

        // Gán giá trị cho 2 trạng thái thanh toán
        DB::table('order_statuses')->where('id', 3)->update(['payment_status' => 1]); // Chờ thanh toán
        DB::table('order_statuses')->where('id', 4)->update(['payment_status' => 2]); // Đã thanh toán
    }

    public function down()
    {
        Schema::table('order_statuses', function (Blueprint $table) {
            $table->dropColumn('payment_status');
        });
    }
}
