<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReturnRequestProgress extends Model
{
    use HasFactory;
    protected $table = 'return_request_progresses';

  protected $fillable = [
    'return_request_id',
    'status',
    'note',
    'completed_at',
    'images',
    'refunded_by_name',
    'refunded_by_email',
    'refunded_account_number',
    'refunded_bank_name',
];
    protected $casts = [
        'images' => 'array', // giúp Laravel tự decode JSON -> array
    ];
    protected $dates = ['completed_at'];

    public function returnRequest()
    {
        return $this->belongsTo(ReturnRequest::class);
    }
}
