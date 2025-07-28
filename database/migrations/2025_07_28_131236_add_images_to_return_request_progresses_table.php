<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImagesToReturnRequestProgressesTable extends Migration
{
    public function up()
    {
        Schema::table('return_request_progresses', function (Blueprint $table) {
            $table->json('images')->nullable()->after('note');
        });
    }

    public function down()
    {
        Schema::table('return_request_progresses', function (Blueprint $table) {
            $table->dropColumn('images');
        });
    }
}

