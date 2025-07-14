<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDeliveryIssue extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'account_id',
        'reason',
        'note',
    ];

    // Quan hệ
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
