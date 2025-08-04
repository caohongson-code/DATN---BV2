<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderAmountRangeToPromotionsTable extends Migration
{
    public function up()
    {
        Schema::table('promotions', function (Blueprint $table) {
            $table->decimal('min_order_amount', 10, 2)->nullable()->after('usage_limit');
            $table->decimal('max_order_amount', 10, 2)->nullable()->after('min_order_amount');
        });
    }

    public function down()
    {
        Schema::table('promotions', function (Blueprint $table) {
            $table->dropColumn(['min_order_amount', 'max_order_amount']);
        });
    }
}

