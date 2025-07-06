<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;

class CartController extends Controller
{
public function index(Request $request)
{
    $query = Cart::with(['account', 'details.productVariant', 'statusModel']);

    if ($request->filled('search')) {
        $query->whereHas('account', function ($q) use ($request) {
            $q->where('full_name', 'like', '%' . $request->search . '%')
              ->orWhere('email', 'like', '%' . $request->search . '%');
        });
    }

    if ($request->filled('status')) {
        $query->where('cart_status_id', $request->status); // dùng ID
    }

    if ($request->filled('date')) {
        $query->whereDate('created_at', $request->date);
    }

    $carts = $query->latest()->get();

    return view('admin.carts.index', [
        'carts' => $carts,
        'search' => $request->search,
        'status' => $request->status,
        'date' => $request->date,
    ]);
}


    public function show($id)
    {
        $cart = Cart::with([
            'account',
            'details.product',
            'details.productVariant.ram',
            'details.productVariant.storage',
            'details.productVariant.color'
        ])->findOrFail($id);

        return view('admin.carts.show', compact('cart'));
    }

    public function destroy($id)
    {
        $cart = Cart::findOrFail($id);
        $cart->details()->delete();
        $cart->delete();

        return redirect()->route('carts.index')->with('success', 'Đã xoá giỏ hàng');
    }
}
