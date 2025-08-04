<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Promotion;
use Illuminate\Http\Request;
use App\Http\Requests\PromotionRequest;

class PromotionController extends Controller
{
    public function index()
    {
        // Nạp sản phẩm và danh mục liên quan để hiển thị
        $promotions = Promotion::with(['products', 'categories'])->latest()->paginate(10);
        return view('admin.promotions.index', compact('promotions'));
    }

    public function create()
    {
        $products = Product::all();     // Danh sách sản phẩm để chọn
        $categories = Category::all();  // Danh sách danh mục để chọn
        return view('admin.promotions.create', compact('products', 'categories'));
    }

    public function store(PromotionRequest $request)
    {
        $promotion = Promotion::create($request->validated());

        // Gán sản phẩm và danh mục nếu có
        $promotion->products()->sync($request->input('product_ids', []));
        $promotion->categories()->sync($request->input('category_ids', []));

        return redirect()->route('promotions.index')->with('success', 'Tạo khuyến mãi thành công!');
    }

    public function edit(Promotion $promotion)
    {
        $products = Product::all();
        $categories = Category::all();

        // Lấy danh sách ID đã chọn
        $selectedProductIds = $promotion->products->pluck('id')->toArray();
        $selectedCategoryIds = $promotion->categories->pluck('id')->toArray();

        return view('admin.promotions.edit', compact(
            'promotion',
            'products',
            'categories',
            'selectedProductIds',
            'selectedCategoryIds'
        ));
    }

    public function update(PromotionRequest $request, Promotion $promotion)
    {
        $promotion->update($request->validated());

        // Cập nhật sản phẩm và danh mục được áp dụng
        $promotion->products()->sync($request->input('product_ids', []));
        $promotion->categories()->sync($request->input('category_ids', []));

        return redirect()->route('promotions.index')->with('success', 'Cập nhật thành công!');
    }

    public function destroy(Promotion $promotion)
    {
        $promotion->delete();
        return redirect()->route('promotions.index')->with('success', 'Đã xóa khuyến mãi.');
    }
    public function rules()
{
    return [
        'code' => 'required|string|unique:promotions,code,' . $this->id,
        'description' => 'nullable|string',
        'discount_type' => 'required|in:percentage,fixed',
        'discount_value' => 'required|numeric|min:0',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'usage_limit' => 'nullable|integer|min:1',
        'is_active' => 'boolean',

        // 👇 Thêm 2 dòng mới:
        'min_order_amount' => 'nullable|numeric|min:0',
        'max_order_amount' => 'nullable|numeric|min:0|gte:min_order_amount',

        'product_ids' => 'nullable|array',
        'product_ids.*' => 'integer|exists:products,id',

        'category_ids' => 'nullable|array',
        'category_ids.*' => 'integer|exists:categories,id',
    ];
}

}
