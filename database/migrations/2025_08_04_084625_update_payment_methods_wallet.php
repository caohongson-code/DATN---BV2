<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('payment_methods')->where('code', 'bank')->delete();

        DB::table('payment_methods')->insert([
            'method_name' => 'Ví nội bộ',
            'code' => 'wallet',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('payment_methods')->where('code', 'wallet')->delete();

        DB::table('payment_methods')->insert([
            'method_name' => 'Chuyển khoản ngân hàng',
            'code' => 'bank',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
};
