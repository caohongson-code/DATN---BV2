<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProductClientController extends Controller
{
    public function index()
    {
        
        $products = Product::where('status', 1)->orderByDesc('created_at')->paginate(12);
        return view('client.home', compact('products'));
    }

public function show($id)
{
    $product = Product::with(['variants.images','variants.ram', 'variants.storage', 'variants.color'])->findOrFail($id);

    // Lấy các sản phẩm liên quan (trừ chính nó)
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

    return view('client.product.show', compact('product', 'relatedProducts', 'reviews'));
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