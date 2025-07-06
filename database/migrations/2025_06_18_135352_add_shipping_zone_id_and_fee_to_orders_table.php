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
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('shipping_zone_id')->nullable()->after('payment_method_id');
            $table->integer('shipping_fee')->default(0)->after('shipping_zone_id');

            $table->foreign('shipping_zone_id')->references('id')->on('shipping_zones')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
     Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['shipping_zone_id']);
            $table->dropColumn(['shipping_zone_id', 'shipping_fee']);
        });
    }
};
