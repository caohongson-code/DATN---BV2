<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturnRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('return_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id'); // liên kết đến bảng orders
            $table->text('reason')->nullable(); // lý do trả hàng
            $table->json('images')->nullable(); // danh sách ảnh sản phẩm lỗi (JSON)
            $table->timestamps();

            // Thiết lập khóa ngoại
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('return_requests');
    }
}
