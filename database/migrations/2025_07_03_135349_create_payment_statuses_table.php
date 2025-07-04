<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreatePaymentStatusesTable extends Migration
{
    public function up()
    {
        Schema::create('payment_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Tên trạng thái thanh toán
            $table->timestamps();
        });

        // Thêm dữ liệu mẫu
        DB::table('payment_statuses')->insert([
            ['name' => 'Chờ thanh toán', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Đã thanh toán', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Thanh toán thất bại', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hoàn tiền', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('payment_statuses');
    }
}

