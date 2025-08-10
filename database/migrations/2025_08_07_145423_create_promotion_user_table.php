<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('promotion_user', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('promotion_id');
    $table->unsignedBigInteger('account_id'); // Nếu dùng bảng accounts thay vì users
    $table->timestamps();

    $table->unique(['promotion_id', 'account_id']);

    $table->foreign('promotion_id')->references('id')->on('promotions')->onDelete('cascade');
    $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotion_user');
    }
};
