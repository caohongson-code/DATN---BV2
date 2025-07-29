<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class CategoryClientController extends Controller
{
    public function index(Request $request, $id = null)
    {
        $categories = Category::all();
        $query = Product::query();

        if ($id) {
            $query->where('category_id', $id);
            $selectedCategory = $id;
        } else {
            $selectedCategory = null;
        }

        // Tìm kiếm theo tên sản phẩm
        if ($request->filled('search')) {
            $query->where('product_name', 'like', '%' . $request->search . '%');
        }

        // Sắp xếp theo giá
        if ($request->sort_price == 'asc') {
            $query->orderBy('price', 'asc');
        } elseif ($request->sort_price == 'desc') {
            $query->orderBy('price', 'desc');
        }

        $products = $query->paginate(9);

        return view('client.categories.index', compact('categories', 'products', 'selectedCategory'));
    }
} 