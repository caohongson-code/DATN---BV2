<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('return_requests', function (Blueprint $table) {
            $table->string('bank_name')->nullable()->after('shipping_images');
            $table->string('bank_account')->nullable()->after('bank_name');
        });
    }

    public function down(): void
    {
        Schema::table('return_requests', function (Blueprint $table) {
            $table->dropColumn(['bank_name', 'bank_account']);
        });
    }
};
