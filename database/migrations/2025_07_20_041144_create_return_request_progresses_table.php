<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturnRequestProgressesTable extends Migration
{
    public function up(): void
    {
        Schema::create('return_request_progresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('return_request_id')->constrained()->onDelete('cascade');
            $table->string('status'); // Ví dụ: approved, shipped_back, received, checking, refunded
            $table->text('note')->nullable(); // Ghi chú từ admin hoặc hệ thống
            $table->timestamp('completed_at')->nullable(); // Thời điểm hoàn tất bước này
            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('return_request_progresses');
    }
}
