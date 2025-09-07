<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Promotion;
use Illuminate\Support\Facades\Session;
use App\Models\News; 
class ProductClientController extends Controller
{
    // thêm dòng này ở đầu file

public function index()
{
    // Sản phẩm mới nhất (phân trang 12 sp)
    $products = Product::where('status', 1)
        ->orderByDesc('created_at')
        ->paginate(12);

    // 3 sản phẩm nổi bật
    $featuredProducts = Product::where('status', 1)
        ->orderByDesc('created_at')
        ->take(3)
        ->get();

    // 3 khuyến mãi mới nhất
    $latestPromotions = Promotion::active()
        ->orderByDesc('created_at')
        ->take(3)
        ->get();

    // 📰 Tin tức mới nhất (lấy 5 bài)
    $latestNews = News::orderByDesc('created_at')
        ->take(5)
        ->get();

    Session::forget('buy_now');
    Session::forget('checkout_selected');

    // Truyền thêm $latestNews ra view
    return view('client.home', compact(
        'products',
        'featuredProducts',
        'latestPromotions',
        'latestNews'
    ));
}

public function show($id, Request $request)
{
    

    $limit = $request->get('limit', 6); // Lấy limit từ query, mặc định 6

    $product = Product::with(['variants.images','variants.ram', 'variants.storage', 'variants.color'])
        ->findOrFail($id);
    $product->increment('views');

    $relatedProducts = Product::where('category_id', $product->category_id)
        ->where('id', '!=', $product->id)
        ->where('status', 1)
        ->latest()
        ->take(4)
        ->get();

    $reviews = Review::with(['account', 'variant.ram', 'variant.storage', 'variant.color'])
        ->where('product_id', $product->id)
        ->latest()
        ->get();

    // Đếm tổng số bình luận
    $totalComments = Comment::where('product_id', $product->id)->count();

    // Chỉ lấy số lượng bình luận theo limit
    $comments = Comment::with('account')
        ->where('product_id', $product->id)
        ->latest()
        ->take($limit)
        ->get();

    return view('client.product.show', compact(
        'product',
        'relatedProducts',
        'reviews',
        'comments',
        'totalComments',
        'limit'
    ));
}



    public function categoryPage($id = null)
    {
        $categories = \App\Models\Category::all();
        if ($id) {
            $products = \App\Models\Product::where('category_id', $id)->get();
            $selectedCategory = $id;
        } else {
            $products = \App\Models\Product::all();
            $selectedCategory = null;
        }
        return view('client.categories.index', compact('categories', 'products', 'selectedCategory'));
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $sort = $request->input('sort', 'newest');
        $query = \App\Models\Product::where(function($query) use ($keyword) {
            $query->where('product_name', 'like', '%' . $keyword . '%')
                  ->orWhere('description', 'like', '%' . $keyword . '%');
        })->where('status', 1);

        if ($sort === 'price_desc') {
            $query->orderByDesc('discount_price')->orderByDesc('price');
        } elseif ($sort === 'price_asc') {
            $query->orderByRaw('COALESCE(discount_price, price) ASC');
        } else { // newest
            $query->orderByDesc('created_at');
        }

        $products = $query->paginate(12);
        return view('client.product.search', compact('products', 'keyword'));
    }



}
