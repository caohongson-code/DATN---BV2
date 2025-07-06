<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartStatus extends Model
{
    protected $fillable = ['name', 'display_name', 'color'];

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
}
