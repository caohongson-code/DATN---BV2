<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            [
                'id' => 1,
                'role_name' => 'Admin',
                'description' => 'Quản trị toàn bộ hệ thống',
                'created_at' => Carbon::parse('2025-06-21 16:25:02'),
                'updated_at' => Carbon::parse('2025-06-21 16:25:02'),
            ],
            [
                'id' => 2,
                'role_name' => 'Quản trị viên',
                'description' => 'Quản lý sản phẩm, đơn hàng...',
                'created_at' => Carbon::parse('2025-06-21 16:25:02'),
                'updated_at' => Carbon::parse('2025-06-21 16:25:02'),
            ],
            [
                'id' => 3,
                'role_name' => 'Người dùng',
                'description' => 'Khách hàng thông thường',
                'created_at' => Carbon::parse('2025-06-21 16:25:02'),
                'updated_at' => Carbon::parse('2025-06-21 16:25:02'),
            ],
        ]);
    }
}
