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
      Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('account_id');
    $table->unsignedBigInteger('payment_method_id')->nullable();
    $table->unsignedBigInteger('order_status_id')->default(1);

    $table->string('voucher_code', 225)->nullable();
    $table->string('recipient_name', 225);
    $table->string('recipient_email', 225)->nullable();
    $table->string('recipient_phone', 20);
    $table->text('recipient_address');
    $table->decimal('total_amount', 10, 2);
    $table->text('note')->nullable();
    $table->dateTime('order_date')->useCurrent();

    $table->foreign('account_id')->references('id')->on('accounts');
    $table->foreign('payment_method_id')->references('id')->on('payment_methods');
    $table->foreign('order_status_id')->references('id')->on('order_statuses');
    $table->foreignId('voucher_id')->nullable()->constrained('promotions')->onDelete('set null');

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
