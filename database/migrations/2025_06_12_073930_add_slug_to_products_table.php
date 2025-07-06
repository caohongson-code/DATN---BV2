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
{if (!Schema::hasColumn('products', 'slug')) {
    Schema::table('products', function (Blueprint $table) {
        $table->string('slug')->nullable()->after('product_name'); // bỏ unique tạm

    });
}
}

public function down()
{
    if (Schema::hasColumn('products', 'slug')) {
    Schema::table('products', function (Blueprint $table) {
        $table->dropColumn('slug');
    });
}
}
};
