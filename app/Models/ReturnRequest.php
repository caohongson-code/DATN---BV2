<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnRequest extends Model
{
    protected $fillable = ['order_id', 'reason', 'images'];

    protected $casts = [
        'images' => 'array', // để Laravel tự giải mã JSON thành mảng
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function progresses()
{
    return $this->hasMany(ReturnRequestProgress::class);
}

    
}

