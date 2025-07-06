<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CartStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('cart_statuses')->insert([
            ['name' => 'active', 'display_name' => 'Đang hoạt động', 'color' => 'success'],
            ['name' => 'abandoned', 'display_name' => 'Bỏ dở', 'color' => 'secondary'],
            ['name' => 'ordered', 'display_name' => 'Đã đặt', 'color' => 'primary'],
        ]);
    }
}
