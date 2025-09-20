<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        DB::table('permissions')->insert([
            ['slug' => 'staff-manage',     'permission_name' => 'Quản lý nhân viên'],
            ['slug' => 'customer-manage',  'permission_name' => 'Quản lý khách hàng'],
            ['slug' => 'product-manage',   'permission_name' => 'Quản lý sản phẩm'],
            ['slug' => 'order-manage',     'permission_name' => 'Quản lý đơn hàng'],
            ['slug' => 'role-manage',      'permission_name' => 'Quản lý chức vụ'],
            // ['slug' => 'permission-manage','permission_name' => 'Phân quyền'],
            // ['slug' => 'voucher-manage',   'permission_name' => 'Quản lý voucher'],
            ['slug' => 'category-manage',  'permission_name' => 'Quản lý danh mục'],
            ['slug' => 'news-manage',      'permission_name' => 'Quản lý tin tức'],
            ['slug' => 'contact-manage',   'permission_name' => 'Quản lý liên hệ'],
            ['slug' => 'promotion-manage', 'permission_name' => 'Quản lý khuyến mãi'],
            ['slug' => 'comment-manage',   'permission_name' => 'Quản lý bình luận'],
            ['slug' => 'product-attribute','permission_name' => 'Thuộc tính sản phẩm'],

            ['slug' => 'profile-view',     'permission_name' => 'Thông tin cá nhân'],
            ['slug' => 'pos-sell',         'permission_name' => 'POS Bán Hàng'],
        ]);
    }
}
