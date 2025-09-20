<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['role_name', 'description'];

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }
    public function permissions()
{
    return $this->belongsToMany(Permission::class, 'role_permission', 'role_id', 'permission_id');
}
}
