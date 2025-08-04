<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('wallets', function (Blueprint $table) {
            // ✅ Xóa foreign key trước
            $table->dropForeign(['user_id']);
            // ✅ Sau đó mới xóa cột
            $table->dropColumn('user_id');
        });
    }

    public function down()
    {
        Schema::table('wallets', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
        });
    }
};
