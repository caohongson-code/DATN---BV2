<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopInfo extends Model
{
    protected $table = 'shop_infos';
    protected $fillable = [
        'name', 'logo', 'email', 'phone', 'return_phone',
        'address', 'return_address', 'support_time',
        'shipping_policy', 'return_policy', 'note'
    ];
}
