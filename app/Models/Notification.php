<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['account_id', 'type', 'message', 'read'];

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
}