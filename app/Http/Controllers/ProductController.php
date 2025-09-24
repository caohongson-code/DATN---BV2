<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Color;
use App\Models\Ram;
use App\Models\StorageOption;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage as StorageFacade;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'variants.color', 'variants.ram', 'variants.storage']);

        if ($request->has('low_stock')) {
            $query->whereHas('variants', function ($q) {
                $q->where('quantity', '<', 5);
            });
        }

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
            'product_name' => 'required|string|max:255|unique:products,product_name',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lte:price',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|boolean',
            'description' => 'nullable|string',
            // Validation biến thể (không bắt buộc)
            'variants' => 'nullable|array',
            'variants.*.color_id' => 'required_with:variants|exists:colors,id',
            'variants.*.ram_id' => 'required_with:variants|exists:rams,id',
            'variants.*.storage_id' => 'required_with:variants|exists:storages,id',
            'variants.*.price' => 'required_with:variants|numeric|min:0',
            'variants.*.discount_price' => 'nullable|numeric|min:0|lte:variants.*.price',
            'variants.*.quantity' => 'required_with:variants|integer|min:1',
            'color_images.*' => 'required_if:variants,!=,null|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'product_name.required' => 'Tên sản phẩm là bắt buộc.',
            'product_name.unique' => 'Tên sản phẩm đã tồn tại.',
            'category_id.required' => 'Danh mục là bắt buộc.',
            'category_id.exists' => 'Danh mục không hợp lệ.',
            'price.required' => 'Giá sản phẩm là bắt buộc.',
            'discount_price.lte' => 'Giá khuyến mãi phải nhỏ hơn hoặc bằng giá gốc.',
            'image.required' => 'Ảnh sản phẩm là bắt buộc.',
            'variants.*.color_id.required_with' => 'Bạn phải chọn màu cho biến thể.',
            'variants.*.ram_id.required_with' => 'Bạn phải chọn RAM cho biến thể.',
            'variants.*.storage_id.required_with' => 'Bạn phải chọn dung lượng cho biến thể.',
            'variants.*.price.required_with' => 'Giá biến thể là bắt buộc.',
            'variants.*.quantity.required_with' => 'Số lượng biến thể là bắt buộc.',
            'color_images.*.required_if' => 'Ảnh biến thể là bắt buộc khi có biến thể.',
            'color_images.*.image' => 'Ảnh biến thể phải là file ảnh.',
        ]);

        DB::beginTransaction();
        try {
            // Tạo sản phẩm
            $product = Product::create([
                'product_name' => $request->product_name,
                'category_id' => $request->category_id,
                'price' => $request->price,
                'discount_price' => $request->discount_price ?? 0,
                'status' => $request->status,
                'description' => $request->description,
            ]);

            // Lưu ảnh sản phẩm
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('products', 'public');
                $product->update(['image' => $path]);
            }

            // Xử lý biến thể nếu có
            if ($request->has('variants') && !empty($request->variants)) {
                $variantsData = $request->variants;
                $combinations = [];

                // Kiểm tra tổ hợp biến thể hiện có
                $existingCombinations = ProductVariant::where('product_id', $product->id)
                    ->selectRaw("CONCAT(color_id, '-', ram_id, '-', storage_id) as combo")
                    ->pluck('combo')
                    ->toArray();

                foreach ($variantsData as $index => $variantData) {
                    $colorId = $variantData['color_id'];
                    $comboKey = $colorId . '-' . $variantData['ram_id'] . '-' . $variantData['storage_id'];

                    // Kiểm tra trùng trong request
                    if (in_array($comboKey, $combinations)) {
                        throw new \Exception("Biến thể màu/RAM/Dung lượng đã bị trùng trong form.");
                    }
                    $combinations[] = $comboKey;

                    // Kiểm tra trùng trong DB
                    if (in_array($comboKey, $existingCombinations)) {
                        throw new \Exception("Biến thể với màu/RAM/Dung lượng đã tồn tại trong CSDL.");
                    }

                    // Kiểm tra ảnh biến thể
                    if (!$request->hasFile("color_images.$colorId")) {
                        throw new \Exception("Ảnh biến thể cho màu ID $colorId là bắt buộc.");
                    }

                    $variant = ProductVariant::create([
                        'product_id' => $product->id,
                        'color_id' => $variantData['color_id'],
                        'ram_id' => $variantData['ram_id'],
                        'storage_id' => $variantData['storage_id'],
                        'price' => $variantData['price'],
                        'discount_price' => $variantData['discount_price'] ?? 0,
                        'quantity' => $variantData['quantity'],
                        'image' => null, // ảnh chính nếu muốn, còn album lưu bên dưới
                    ]);

                    // Lưu album ảnh nhiều file
                    if (isset($variantData['images'])) {
                        foreach ($variantData['images'] as $imageFile) {
                            if ($imageFile) {
                                $path = $imageFile->store('uploads/variants', 'public');
                                $variant->images()->create(['image' => $path]);
                            }
                        }
                    }
                }
            }

            DB::commit();
            return redirect()->route('products.index')->with('success', 'Thêm sản phẩm thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function show($id)
    {
        $product = Product::with(['variants.ram', 'variants.storage', 'variants.color'])->findOrFail($id);
        return view('admin.products.show', compact('product'));
    }

    public function edit($id)
    {
        $categories = Category::all();
        $colors = Color::all();
        $rams = Ram::all();
        $storages = StorageOption::all();
        $product = Product::with(['variants.ram', 'variants.storage', 'variants.color'])->findOrFail($id);
        return view('admin.products.edit', compact('product', 'categories', 'colors', 'rams', 'storages'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::with('variants.images')->findOrFail($id);

        $request->validate([
            'product_name' => 'required|string|max:255|unique:products,product_name,' . $id,
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lte:price',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|boolean',
            'description' => 'nullable|string',
            // Validation biến thể (không bắt buộc)
            'variants' => 'nullable|array',
            'variants.*.id' => 'nullable|exists:product_variants,id',
            'variants.*.color_id' => 'required_with:variants|exists:colors,id',
            'variants.*.ram_id' => 'required_with:variants|exists:rams,id',
            'variants.*.storage_id' => 'required_with:variants|exists:storages,id',
            'variants.*.price' => 'required_with:variants|numeric|min:0',
            'variants.*.discount_price' => 'nullable|numeric|min:0|lte:variants.*.price',
            'variants.*.quantity' => 'required_with:variants|integer|min:0',
            'variants.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            // Validation cho ảnh biến thể mới
            'color_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'product_name.required' => 'Tên sản phẩm là bắt buộc.',
            'product_name.unique' => 'Tên sản phẩm đã tồn tại.',
            'category_id.required' => 'Danh mục là bắt buộc.',
            'category_id.exists' => 'Danh mục không hợp lệ.',
            'price.required' => 'Giá sản phẩm là bắt buộc.',
            'discount_price.lte' => 'Giá khuyến mãi phải nhỏ hơn hoặc bằng giá gốc.',
            'image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif.',
            'variants.*.color_id.required_with' => 'Bạn phải chọn màu cho biến thể.',
            'variants.*.ram_id.required_with' => 'Bạn phải chọn RAM cho biến thể.',
            'variants.*.storage_id.required_with' => 'Bạn phải chọn dung lượng cho biến thể.',
            'variants.*.price.required_with' => 'Giá biến thể là bắt buộc.',
            'variants.*.quantity.required_with' => 'Số lượng biến thể là bắt buộc.',
            'variants.*.image.image' => 'Ảnh biến thể phải là file ảnh.',
            'color_images.*.image' => 'Ảnh biến thể phải là file ảnh.',
        ]);

        DB::beginTransaction();
        try {
            // Cập nhật sản phẩm
            $product->update([
                'product_name' => $request->product_name,
                'category_id' => $request->category_id,
                'price' => $request->price,
                'discount_price' => $request->discount_price ?? 0,
                'status' => $request->status,
                'description' => $request->description,
            ]);

            // Cập nhật ảnh sản phẩm
            if ($request->hasFile('image')) {
                if ($product->image && StorageFacade::disk('public')->exists($product->image)) {
                    StorageFacade::disk('public')->delete($product->image);
                }
                $path = $request->file('image')->store('products', 'public');
                $product->update(['image' => $path]);
            }

            // Xử lý biến thể
            $processedVariantIds = [];
            $combinations = [];

            if ($request->has('variants') && !empty($request->variants)) {
                $variantsData = $request->variants;

                // Lấy tổ hợp biến thể hiện có (trừ những biến thể đang được cập nhật)
                $variantIds = array_filter(array_column($variantsData, 'id'));
                $existingCombinations = ProductVariant::where('product_id', $product->id)
                    ->whereNotIn('id', $variantIds)
                    ->selectRaw("CONCAT(color_id, '-', ram_id, '-', storage_id) as combo")
                    ->pluck('combo')
                    ->toArray();

                foreach ($variantsData as $index => $variantData) {
                    $variantId = $variantData['id'] ?? null;
                    $comboKey = $variantData['color_id'] . '-' . $variantData['ram_id'] . '-' . $variantData['storage_id'];

                    // Kiểm tra trùng trong request
                    if (in_array($comboKey, $combinations)) {
                        throw new \Exception("Biến thể màu/RAM/Dung lượng đã bị trùng trong form.");
                    }
                    $combinations[] = $comboKey;

                    // Kiểm tra trùng trong DB
                    if (in_array($comboKey, $existingCombinations)) {
                        throw new \Exception("Biến thể với màu/RAM/Dung lượng đã tồn tại trong CSDL.");
                    }

                    // Xử lý ảnh biến thể
                    $imagePath = $variantData['old_image'] ?? null;

                    // Ưu tiên sử dụng ảnh từ variants.*.image
                    if ($request->hasFile("variants.$index.image")) {
                        // Xóa ảnh cũ nếu có
                        if ($variantId) {
                            $variant = ProductVariant::find($variantId);
                            if ($variant && $variant->image && StorageFacade::disk('public')->exists($variant->image)) {
                                StorageFacade::disk('public')->delete($variant->image);
                            }
                        }
                        $imagePath = $request->file("variants.$index.image")->store('uploads/variants', 'public');
                    }
                    // Nếu không có ảnh từ variants.*.image, thử tìm trong color_images
                    elseif (!$variantId && $request->hasFile("color_images.new_$index")) {
                        $imagePath = $request->file("color_images.new_$index")->store('uploads/variants', 'public');
                    }
                    // Nếu là biến thể mới và không có ảnh nào
                    elseif (!$variantId && !$imagePath) {
                        throw new \Exception("Biến thể mới cần phải có ảnh.");
                    }

                    if ($variantId) {
                        // Cập nhật biến thể
                        $variant = ProductVariant::find($variantId);
                        if ($variant) {
                            $variant->update([
                                'color_id' => $variantData['color_id'],
                                'ram_id' => $variantData['ram_id'],
                                'storage_id' => $variantData['storage_id'],
                                'price' => $variantData['price'],
                                'discount_price' => $variantData['discount_price'] ?? 0,
                                'quantity' => $variantData['quantity'],
                                'image' => $imagePath,
                            ]);
                            $processedVariantIds[] = $variantId;
                            // Xóa các ảnh không còn giữ
                            $existingImages = $variantData['existing_images'] ?? [];
                            $variant->images()->whereNotIn('id', $existingImages)->get()->each(function ($img) {
                                if ($img->path && StorageFacade::disk('public')->exists($img->path)) {
                                    StorageFacade::disk('public')->delete($img->path);
                                }
                                $img->delete();
                            });

                            // Thêm ảnh mới
                            if (!empty($variantData['images'])) {
                                foreach ($variantData['images'] as $imageFile) {
                                    if ($imageFile) {
                                        $path = $imageFile->store('uploads/variants', 'public');
                                        $variant->images()->create(['image' => $path]);
                                    }
                                }
                            }
                        }
                    } else {
                        // Thêm mới biến thể
                        $newVariant = ProductVariant::create([
                            'product_id' => $product->id,
                            'color_id' => $variantData['color_id'],
                            'ram_id' => $variantData['ram_id'],
                            'storage_id' => $variantData['storage_id'],
                            'price' => $variantData['price'],
                            'discount_price' => $variantData['discount_price'] ?? 0,
                            'quantity' => $variantData['quantity'],
                            'image' => $imagePath,
                        ]);
                        $processedVariantIds[] = $newVariant->id;
                    }
                }
            }

            // Xóa các biến thể không còn trong danh sách
            $variantsToDelete = ProductVariant::where('product_id', $product->id)
                ->whereNotIn('id', $processedVariantIds)
                ->get();

            foreach ($variantsToDelete as $variant) {
                if ($variant->image && StorageFacade::disk('public')->exists($variant->image)) {
                    StorageFacade::disk('public')->delete($variant->image);
                }
                $variant->delete();
            }

            DB::commit();
            return redirect()->route('products.index')->with('success', 'Cập nhật sản phẩm thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function destroy($id)
    {
        $product = Product::with('variants')->findOrFail($id);

        // Xóa ảnh sản phẩm
        if ($product->image && StorageFacade::disk('public')->exists($product->image)) {
            StorageFacade::disk('public')->delete($product->image);
        }

        // Xóa ảnh biến thể
        foreach ($product->variants as $variant) {
            if ($variant->image && StorageFacade::disk('public')->exists($variant->image)) {
                StorageFacade::disk('public')->delete($variant->image);
            }
            $variant->delete();
        }

        $product->delete();
        return redirect()->route('products.index')->with('success', 'Đã xóa sản phẩm.');
    }
}
