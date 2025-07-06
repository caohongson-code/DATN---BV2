<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

   protected $fillable = ['account_id', 'cart_status_id'];


    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function details()
    {
        return $this->hasMany(CartDetail::class);
    }
    public function status()
    {
        return $this->belongsTo(CartStatus::class, 'cart_status_id');
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function statusModel()
{
    return $this->belongsTo(CartStatus::class, 'cart_status_id');
}


}
