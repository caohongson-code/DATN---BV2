<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShippingZonesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('shipping_zones')->insert([
            ['zone_name' => 'Nội thành Hà Nội', 'fee' => 30000, 'created_at' => now(), 'updated_at' => now()],
            ['zone_name' => 'Ngoại thành Hà Nội', 'fee' => 50000, 'created_at' => now(), 'updated_at' => now()],
            ['zone_name' => 'Các tỉnh khác', 'fee' => 80000, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
