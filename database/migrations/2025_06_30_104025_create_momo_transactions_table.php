<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMomoTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('momo_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('partner_code');
            $table->string('order_id')->index();
            $table->string('request_id');
            $table->bigInteger('amount');
            $table->string('order_info');
            $table->string('order_type');
            $table->string('trans_id')->nullable();
            $table->tinyInteger('result_code');
            $table->string('message');
            $table->string('pay_type')->nullable();
            $table->timestamp('response_time')->nullable();
            $table->text('extra_data')->nullable();
            $table->string('signature');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('momo_transactions');
    }
}
