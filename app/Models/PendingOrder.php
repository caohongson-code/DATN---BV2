<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendingOrder extends Model
{
    protected $table = 'pending_orders';

    protected $fillable = [
        'request_id',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];
}
