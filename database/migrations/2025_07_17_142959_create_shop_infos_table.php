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
 Schema::create('shop_infos', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('logo')->nullable();
    $table->string('email')->nullable();
    $table->string('phone');
    $table->string('return_phone')->nullable();
    $table->string('address');
    $table->string('return_address')->nullable();
    $table->string('support_time')->nullable();
    $table->string('shipping_policy')->nullable();
    $table->string('return_policy')->nullable();
    $table->text('note')->nullable();
    $table->timestamps();
});


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_infos');
    }
};
