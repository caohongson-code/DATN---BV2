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
    Schema::table('payment_methods', function (Blueprint $table) {
        $table->string('code', 50)->unique()->after('method_name');
    });
}

public function down(): void
{
    Schema::table('payment_methods', function (Blueprint $table) {
        $table->dropColumn('code');
    });
}

};
