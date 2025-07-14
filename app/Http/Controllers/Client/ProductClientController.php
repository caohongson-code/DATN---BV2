<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

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

    return view('client.product.show', compact('product', 'relatedProducts'));
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


}
