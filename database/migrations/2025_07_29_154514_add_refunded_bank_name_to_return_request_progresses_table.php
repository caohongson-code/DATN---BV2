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
    Schema::table('return_request_progresses', function (Blueprint $table) {
        $table->string('refunded_bank_name')->nullable()->after('refunded_account_number');
    });
}

public function down()
{
    Schema::table('return_request_progresses', function (Blueprint $table) {
        $table->dropColumn('refunded_bank_name');
    });
}

};
