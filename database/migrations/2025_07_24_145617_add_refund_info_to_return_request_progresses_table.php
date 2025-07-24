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
        $table->string('refunded_by_name')->nullable();
        $table->string('refunded_by_email')->nullable();
        $table->string('refunded_account_number')->nullable();
    });
}

public function down()
{
    Schema::table('return_request_progresses', function (Blueprint $table) {
        $table->dropColumn(['refunded_by_name', 'refunded_by_email', 'refunded_account_number']);
    });
}

};
