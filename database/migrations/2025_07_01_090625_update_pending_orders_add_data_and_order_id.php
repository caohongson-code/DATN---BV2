<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePendingOrdersAddDataAndOrderId extends Migration
{
    public function up(): void
    {
        Schema::table('pending_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('pending_orders', 'data')) {
                $table->longText('data')->nullable()->after('request_id');
            }

            if (!Schema::hasColumn('pending_orders', 'order_id')) {
                $table->unsignedBigInteger('order_id')->nullable()->after('data');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pending_orders', function (Blueprint $table) {
            if (Schema::hasColumn('pending_orders', 'data')) {
                $table->dropColumn('data');
            }

            if (Schema::hasColumn('pending_orders', 'order_id')) {
                $table->dropColumn('order_id');
            }
        });
    }
}
