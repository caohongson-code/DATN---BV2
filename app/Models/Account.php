<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Promotion;
use Illuminate\Auth\Passwords\CanResetPassword; // trait
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract; // interface

class Account extends Authenticatable implements CanResetPasswordContract
{
    use Notifiable, CanResetPassword; // dùng trait CanResetPassword

    protected $fillable = [
        'full_name', 'email', 'password', 'role_id',
        'avatar', 'date_of_birth', 'phone', 'gender', 'address',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
    public function wallet()
{
    return $this->hasOne(Wallet::class);
}
public function savedPromotions()
{
    return $this->belongsToMany(Promotion::class, 'promotion_user', 'account_id', 'promotion_id')
                ->withTimestamps();
}

}
