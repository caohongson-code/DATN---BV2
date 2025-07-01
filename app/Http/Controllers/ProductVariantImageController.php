<?php

namespace App\Http\Controllers;

use App\Models\ProductVariant;
use App\Models\ProductVariantImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductVariantImageController extends Controller
{
    public function storeImages(Request $request, $variantId)
    {
        $request->validate([
            'image_main' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'album.*'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $variant = ProductVariant::findOrFail($variantId);

        // Lưu ảnh chính
        if ($request->hasFile('image_main')) {
            $path = $request->file('image_main')->store('uploads/variants', 'public');
            $variant->update(['image' => $path]);
        }

        // Lưu album ảnh phụ
        if ($request->hasFile('album')) {
            foreach ($request->file('album') as $key => $imageFile) {
                ProductVariantImage::create([
                    'product_variant_id' => $variant->id,
                    'image' => $imageFile->store('uploads/variants', 'public'),
                    'position' => $key,
                ]);
            }
        }

        return back()->with('success', 'Thêm ảnh thành công!');
    }

    public function deleteImage($id)
    {
        $image = ProductVariantImage::findOrFail($id);

        // Xóa file vật lý
        Storage::disk('public')->delete($image->image);

        $image->delete();

        return back()->with('success', 'Xóa ảnh thành công!');
    }
}
