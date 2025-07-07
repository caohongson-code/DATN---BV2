<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
protected $fillable = [
    'account_id',
    'product_id',
    'product_variant_id',
    'order_id',
    'rating',
    'comment',
    'image'
];



    public function user()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
