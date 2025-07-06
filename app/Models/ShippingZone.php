<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'zone_name',
        'shipping_fee',
    ];

    // Quan hệ với đơn hàng
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
