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
        'images', // thêm dòng này
        'completed_at',
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
