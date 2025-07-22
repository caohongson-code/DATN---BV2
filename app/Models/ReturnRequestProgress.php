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
    ];

    protected $dates = ['completed_at'];

    public function returnRequest()
    {
        return $this->belongsTo(ReturnRequest::class);
    }
}
