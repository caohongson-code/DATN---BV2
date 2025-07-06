<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage; // Phải có để xóa ảnh đúng
use App\Models\StorageOption;           // Model StorageOption mới

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'ram_id',
        'storage_id',
        'color_id',
        'price',
        'discount_price',
        'quantity',
        'image'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function ram()
    {
        return $this->belongsTo(Ram::class);
    }

    public function storage()
    {
        return $this->belongsTo(StorageOption::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function images()
    {
        return $this->hasMany(ProductVariantImage::class, 'product_variant_id');
    }

    protected static function booted()
    {
        static::deleting(function ($variant) {
            if ($variant->image) {
                Storage::disk('public')->delete($variant->image);
            }

            foreach ($variant->images as $img) {
                Storage::disk('public')->delete($img->image);
                $img->delete();
            }
        });
    }
}
