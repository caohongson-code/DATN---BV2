<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;
    protected $fillable = ['slug','permission_name', 'description'];
   // App\Models\Permission.php
public function roles()
{
    return $this->belongsToMany(Role::class, 'role_permission', 'permission_id', 'role_id');
}


}
