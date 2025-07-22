<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     */
   

   public function run(): void
{
    $this->call([
        ShippingZonesTableSeeder::class,
        OrderStatusSeeder::class,
    ]);

    // Seed shop PowPow
    DB::table('shop_infos')->insert([
        'name' => 'Shop PowPow',
        'phone' => '024 7300 1955',
        'email' => 'powpow@fpt.edu.vn',
        'address' => 'Tòa nhà FPT Polytechnic, Trịnh Văn Bô, Nam Từ Liêm, Hà Nội',
        'return_address' => 'Tòa nhà FPT Polytechnic, Trịnh Văn Bô, Nam Từ Liêm, Hà Nội',
        'support_time' => '08:00 – 17:30 (Thứ 2 – Thứ 7)',
        'shipping_policy' => 'Giao hàng toàn quốc, cho kiểm hàng trước khi thanh toán.',
        'return_policy' => 'Trả hàng trong 7 ngày nếu sản phẩm lỗi hoặc không đúng mô tả.',
        'note' => 'Hỗ trợ qua email và hotline 24/7.',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}


}
