<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantImage;
use App\Models\Ram;
use App\Models\StorageOption;
use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage as StorageFacade;

class ProductVariantController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['variants.ram', 'variants.storage', 'variants.color'])
            ->orderByDesc('created_at');

        if ($request->color_id) {
            $query->whereHas('variants', fn($q) => $q->where('color_id', $request->color_id));
        }
        if ($request->ram_id) {
            $query->whereHas('variants', fn($q) => $q->where('ram_id', $request->ram_id));
        }
        if ($request->storage_id) {
            $query->whereHas('variants', fn($q) => $q->where('storage_id', $request->storage_id));
        }
        if ($request->search) {
            $query->where('product_name', 'like', "%{$request->search}%");
        }

        return view('admin.variants.index', [
            'products' => $query->get(),
            'variants' => ProductVariant::with(['product', 'ram', 'storage', 'color'])->get(),
            'colors' => Color::all(),
            'rams' => Ram::all(),
            'storages' => StorageOption::all(),
            'request' => $request
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        if (!$request->has('color_id')) {
            return back()->withErrors('Bạn chưa chọn thuộc tính để tạo biến thể.')->withInput();
        }

        foreach ($request->color_id as $colorIndex => $colorId) {

            $imagePath = null;
            if ($request->hasFile("color_image.$colorIndex")) {
                $imagePath = $request->file("color_image.$colorIndex")->store('uploads/variants', 'public');
            }

            foreach ($request->ram_id as $key => $ramId) {
                $parts = explode('_', $key);
                if ($parts[0] == $colorId) {
                    $variant = ProductVariant::create([
                        'product_id' => $request->product_id,
                        'ram_id' => $request->ram_id[$key],
                        'storage_id' => $request->storage_id[$key],
                        'color_id' => $colorId,
                        'price' => $request->price[$key],
                        'discount_price' => $request->discount_price[$key] ?? null,
                        'quantity' => $request->quantity[$key],
                        'image' => $imagePath,
                    ]);

                    if ($request->hasFile("album_images.$colorIndex")) {
                        foreach ($request->file("album_images.$colorIndex") as $position => $imgFile) {
                            $path = $imgFile->store('uploads/variants', 'public');
                            $variant->images()->create([
                                'image' => $path,
                                'position' => $position,
                            ]);
                        }
                    }
                }
            }
        }

        return redirect()->route('variants.index')->with('success', 'Tạo biến thể thành công.');
    }



    public function edit(ProductVariant $variant)
    {
        return view('admin.variants.edit', [
            'variant' => $variant,
            'products' => Product::all(),
            'rams' => Ram::all(),
            'storages' => StorageOption::all(),
            'colors' => Color::all(),
        ]);
    }

    public function update(Request $request, ProductVariant $variant)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'ram_id' => 'required|exists:rams,id',
            'storage_id' => 'required|exists:storages,id',
            'color_id' => 'required|exists:colors,id',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lte:price',
            'quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        $exists = ProductVariant::where('product_id', $request->product_id)
            ->where('ram_id', $request->ram_id)
            ->where('storage_id', $request->storage_id)
            ->where('color_id', $request->color_id)
            ->where('id', '!=', $variant->id)
            ->exists();

        if ($exists) {
            return back()->withErrors('Tổ hợp này đã tồn tại!')->withInput();
        }

        $data = $request->only(['product_id', 'ram_id', 'storage_id', 'color_id', 'price', 'discount_price', 'quantity']);

        if ($request->hasFile('image')) {
            if ($variant->image && StorageFacade::disk('public')->exists($variant->image)) {
                StorageFacade::disk('public')->delete($variant->image);
            }
            $data['image'] = $request->file('image')->store('uploads/variants', 'public');
        }

        $variant->update($data);

        return redirect()->route('variants.index')->with('success', 'Cập nhật biến thể thành công.');
    }


    public function destroy(ProductVariant $variant)
    {
        if ($variant->image && StorageFacade::disk('public')->exists($variant->image)) {
            StorageFacade::disk('public')->delete($variant->image);
        }

        foreach ($variant->images as $img) {
            if ($img->image && StorageFacade::disk('public')->exists($img->image)) {
                StorageFacade::disk('public')->delete($img->image);
            }
            $img->delete();
        }

        $variant->delete();

        return redirect()->route('variants.index')->with('success', 'Xóa biến thể thành công.');
    }
}
