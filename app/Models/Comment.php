<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'account_id', 'product_id', 'product_variant_id', 'order_id',
        'comment', 'image',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

}
