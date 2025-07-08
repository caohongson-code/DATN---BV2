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

        $products = $query->orderByDesc('id')->paginate(10);
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

    // Cập nhật sản phẩm
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
        ]);

        // Cập nhật thông tin sản phẩm
        $product->update([
            'product_name' => $request->product_name,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'description' => $request->description,
        ]);

        $variantsData = $request->variants ?? [];

        // Lưu danh sách ID biến thể đã được xử lý (dùng để xoá những cái không còn)
        $processedVariantIds = [];

        foreach ($variantsData as $index => $variantData) {
            $variantId = $variantData['id'] ?? null;

            // Kiểm tra xem có ảnh mới không
            $imagePath = null;
            if ($request->hasFile("color_images.{$variantData['color_id']}")) {
                $file = $request->file("color_images.{$variantData['color_id']}");
                $imagePath = $file->store('product_variants', 'public');
            } else {
                // Nếu không có ảnh mới, lấy ảnh cũ
                $imagePath = $variantData['old_image'] ?? null;
            }

            if ($variantId) {
                // Nếu có ID → Cập nhật biến thể cũ
                $variant = ProductVariant::find($variantId);
                if ($variant) {
                    $variant->update([
                        'color_id' => $variantData['color_id'],
                        'ram_id' => $variantData['ram_id'],
                        'storage_id' => $variantData['storage_id'],
                        'price' => $variantData['price'],
                        'quantity' => $variantData['quantity'],
                        'image' => $imagePath,
                    ]);
                    $processedVariantIds[] = $variantId;
                }
            } else {
                // Nếu không có ID → Thêm mới biến thể
                $newVariant = ProductVariant::create([
                    'product_id' => $product->id,
                    'color_id' => $variantData['color_id'],
                    'ram_id' => $variantData['ram_id'],
                    'storage_id' => $variantData['storage_id'],
                    'price' => $variantData['price'],
                    'quantity' => $variantData['quantity'],
                    'image' => $imagePath,
                ]);
                $processedVariantIds[] = $newVariant->id;
            }
        }


        $variantsToDelete = ProductVariant::where('product_id', $product->id)
            ->whereNotIn('id', $processedVariantIds)
            ->get();

        foreach ($variantsToDelete as $variant) {
            // Xoá ảnh cũ nếu có
            if ($variant->image && StorageFacade::disk('public')->exists($variant->image)) {
                StorageFacade::disk('public')->delete($variant->image);
            }
            $variant->delete();
        }
        return redirect()->route('products.index')->with('success', 'Cập nhật sản phẩm thành công!');
    }


    // Xóa sản phẩm
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
