<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->unsignedBigInteger('product_variant_id')->after('product_id');
            $table->unsignedBigInteger('order_id')->after('product_variant_id');
            $table->string('image')->nullable()->after('comment'); // ảnh đánh giá
        });
    }

    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['product_variant_id', 'order_id', 'image']);
        });
    }
};

