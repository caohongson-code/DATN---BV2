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
    Schema::table('comments', function (Blueprint $table) {
        $table->boolean('status')->default(1); // 1 = Hiện, 0 = Ẩn
    });
}

public function down()
{
    Schema::table('comments', function (Blueprint $table) {
        $table->dropColumn('status');
    });
}
};
