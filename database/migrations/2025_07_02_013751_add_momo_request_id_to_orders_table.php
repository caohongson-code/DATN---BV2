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
    Schema::table('orders', function (Blueprint $table) {
        $table->string('momo_request_id')->nullable();
    });
}

public function down()
{
        Schema::table('orders', function (Blueprint $table) {
        $table->dropColumn('momo_request_id');
    });
}
};
