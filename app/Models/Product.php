<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'product_name',
        'price',
        'discount_price',
        'image',
        'quantity',
        'views',
        'description',
        'status',
    ];

    // Quan hệ 1 sản phẩm thuộc 1 danh mục
    public function category()

{
    return $this->belongsTo(Category::class);
}
public function variants()
{
    return $this->hasMany(ProductVariant::class);
}



}
