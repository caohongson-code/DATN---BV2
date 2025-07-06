<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyResultCodeTypeInMomoTransactionsTable extends Migration
{
    public function up()
    {
        Schema::table('momo_transactions', function (Blueprint $table) {
            $table->integer('result_code')->change();
        });
    }

    public function down()
    {
        Schema::table('momo_transactions', function (Blueprint $table) {
            $table->tinyInteger('result_code')->change();
        });
    }
}

