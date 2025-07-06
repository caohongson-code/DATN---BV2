<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MomoTransaction extends Model
{
    protected $table = 'momo_transactions'; // chỉ rõ nếu tên bảng không chuẩn

    protected $fillable = [
        'partner_code',
        'order_id',
        'request_id',
        'amount',
        'order_info',
        'order_type',
        'trans_id',
        'result_code',
        'message',
        'pay_type',
        'response_time',
        'extra_data',
        'signature',
    ];

    public $timestamps = true;
}
