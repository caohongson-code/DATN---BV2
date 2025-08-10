<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Builder;

class Promotion extends Model
{
    use HasFactory;

    protected $table = 'promotions';

    protected $fillable = [
        'code',
        'description',
        'discount_type',
        'discount_value',
        'start_date',
        'end_date',
        'usage_limit',
        'used_count',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date'   => 'datetime',
        'is_active'  => 'boolean',
    ];


    // // 💡 Local scope `active`
    // public function scopeActive(Builder $query)
    // {
    //     return $query->where('is_active', true)
    //                  ->where('start_date', '<=', now())
    //                  ->where('end_date', '>=', now());
    // }


    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_promotion');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_promotion');
    }

    /**
     * Scope lọc khuyến mãi đang hoạt động
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1)
                     ->whereDate('start_date', '<=', now())
                     ->whereDate('end_date', '>=', now());
    }
    public function users()
{
    return $this->belongsToMany(Account::class, 'promotion_user')->withTimestamps();
}

}
