<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('product_variant_id')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();

            $table->text('comment');
            $table->string('image')->nullable();

            $table->timestamps();

            // Khóa ngoại (nếu bạn đã có bảng accounts, products, product_variants, orders)
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('product_variant_id')->references('id')->on('product_variants')->onDelete('set null');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');

            $table->index('product_id');
            $table->index('account_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
