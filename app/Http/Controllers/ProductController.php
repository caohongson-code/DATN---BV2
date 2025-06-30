<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Color;
use App\Models\Ram;
use App\Models\StorageOption;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage as StorageFacade;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%")
                  ->orWhereHas('category', function ($q) use ($search) {
                      $q->where('category_name', 'like', "%{$search}%");
                  });
            });
        }

        $products = $query->get();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $colors = Color::all();
        $rams = Ram::all();
        $storages = StorageOption::all();
        return view('admin.products.create', compact('categories', 'colors', 'rams', 'storages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lte:price',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'quantity' => 'required|integer|min:0',
            'status' => 'required|boolean',
            'description' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $product = Product::create([
                'product_name' => $request->product_name,
                'category_id' => $request->category_id,
                'price' => $request->price,
                'discount_price' => $request->discount_price,
                'quantity' => $request->quantity,
                'status' => $request->status,
                'description' => $request->description,
            ]);

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('products', 'public');
                $product->update(['image' => $path]);
            }

            $variantsData = $request->variants ?? [];
            foreach ($variantsData as $variantData) {
                $variant = ProductVariant::create([
                    'product_id' => $product->id,
                    'color_id' => $variantData['color_id'],
                    'ram_id' => $variantData['ram_id'],
                    'storage_id' => $variantData['storage_id'],
                    'price' => $variantData['price'],
                    'quantity' => $variantData['quantity'] ?? 0,
                ]);

                if (isset($variantData['images'])) {
                    foreach ($variantData['images'] as $imgFile) {
                        $path = $imgFile->store('uploads/variants', 'public');
                        ProductVariantImage::create([
                            'product_variant_id' => $variant->id,
                            'image' => $path,
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('products.index')->with('success', 'Thêm sản phẩm và biến thể thành công!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function show($id)
    {
        $product = Product::with(['variants.images', 'variants.ram', 'variants.storage', 'variants.color'])->findOrFail($id);
        return view('admin.products.show', compact('product'));
    }

    public function edit($id)
    {
        $categories = Category::all();
        $colors = Color::all();
        $rams = Ram::all();
        $storages = StorageOption::all();
        $product = Product::with(['variants.images', 'variants.ram', 'variants.storage', 'variants.color'])->findOrFail($id);
        return view('admin.products.edit', compact('product', 'categories', 'colors', 'rams', 'storages'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'quantity' => 'required|integer|min:0',
            'status' => 'required|boolean',
            'description' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $product = Product::findOrFail($id);

            $validated = $request->only(['product_name', 'category_id', 'price', 'discount_price', 'quantity', 'status', 'description']);

            if ($request->hasFile('image')) {
                if ($product->image) {
                    StorageFacade::disk('public')->delete($product->image);
                }
                $path = $request->file('image')->store('products', 'public');
                $validated['image'] = $path;
            }

            $product->update($validated);

            $product->variants()->delete();

            $variantsData = $request->variants ?? [];
            foreach ($variantsData as $variantData) {
                $variant = ProductVariant::create([
                    'product_id' => $product->id,
                    'color_id' => $variantData['color_id'],
                    'ram_id' => $variantData['ram_id'],
                    'storage_id' => $variantData['storage_id'],
                    'price' => $variantData['price'],
                    'quantity' => $variantData['quantity'] ?? 0,
                ]);

                if (isset($variantData['images'])) {
                    foreach ($variantData['images'] as $imgFile) {
                        $path = $imgFile->store('uploads/variants', 'public');
                        ProductVariantImage::create([
                            'product_variant_id' => $variant->id,
                            'image' => $path,
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('products.index')->with('success', 'Cập nhật sản phẩm và biến thể thành công!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function destroy($id)
    {
        $product = Product::with(['variants.images'])->findOrFail($id);

        if ($product->image) {
            StorageFacade::disk('public')->delete($product->image);
        }

        foreach ($product->variants as $variant) {
            foreach ($variant->images as $img) {
                StorageFacade::disk('public')->delete($img->image);
            }
        }

        $product->variants()->delete();
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Đã xóa sản phẩm.');
    }
}
