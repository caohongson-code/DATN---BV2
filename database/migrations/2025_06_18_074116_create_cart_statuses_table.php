<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('cart_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // active, abandoned, ordered
            $table->string('display_name');   // Đang hoạt động, Bỏ dở, Đã đặt
            $table->string('color')->default('secondary'); // bootstrap color: success, warning...
            $table->timestamps();
        });

        // Update bảng carts
        Schema::table('carts', function (Blueprint $table) {
            $table->unsignedBigInteger('cart_status_id')->nullable()->after('account_id');

            $table->foreign('cart_status_id')->references('id')->on('cart_statuses')->nullOnDelete();
        });
    }


    /**
     * Reverse the migrations.
     */
 public function down()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropForeign(['cart_status_id']);
            $table->dropColumn('cart_status_id');
        });

        Schema::dropIfExists('cart_statuses');
    }
};
