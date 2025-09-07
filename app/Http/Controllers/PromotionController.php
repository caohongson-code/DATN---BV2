<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Promotion;
use Illuminate\Http\Request;
use App\Http\Requests\PromotionRequest;

class PromotionController extends Controller
{
    public function index(Request $request)
    {
        // Nạp sản phẩm và danh mục liên quan để hiển thị
        $query = Promotion::with(['products', 'categories'])->latest();

        // Tìm kiếm theo mã khuyến mãi
        if ($request->filled('code')) {
            $code = trim($request->input('code'));
            $query->where('code', 'like', "%{$code}%");
        }

        $promotions = $query->paginate(10)->appends($request->only('code'));

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

}
