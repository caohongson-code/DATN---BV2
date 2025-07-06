<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function buyNow(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $user = Auth::user();
        if (!$user) {
            // return redirect()->route('taikhoan.showLoginForm')->with('error', 'Bạn cần đăng nhập để mua hàng.');
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để mua hàng.');
        }

        $product = Product::findOrFail($request->product_id);
        $variant = $request->variant_id ? ProductVariant::findOrFail($request->variant_id) : null;

        session()->put('buy_now', [
            'product_id' => $product->id,
            'variant_id' => $variant?->id,
            'quantity'   => $request->quantity,
        ]);

        return redirect()->route('checkout');
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $userId = Auth::id();

        $cart = Cart::firstOrCreate(
            ['account_id' => $userId, 'cart_status_id' => 1],
            []
        );

        $query = CartDetail::where('cart_id', $cart->id)
            ->where('product_id', $request->product_id);

        if ($request->filled('product_variant_id')) {
            $query->where('product_variant_id', $request->product_variant_id);
        } else {
            $query->whereNull('product_variant_id');
        }

        $detail = $query->first();

        if ($detail) {
            $detail->quantity += $request->quantity;
            $detail->save();
        } else {
            CartDetail::create([
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
                'product_variant_id' => $request->product_variant_id,
                'quantity' => $request->quantity,
            ]);
        }

        return redirect()->back()->with('success', 'Đã thêm vào giỏ hàng!');
    }

    public function show()
    {
        // $user = auth()->user();
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để xem giỏ hàng.');
        }

        $cart = Cart::with(['details.product', 'details.variant', 'status'])
                    ->where('account_id', $user->id)
                    ->where('cart_status_id', 1)
                    ->first();

        if ($cart) {
            $grouped = [];

            foreach ($cart->details as $item) {
                $key = $item->product_id . '-' . ($item->product_variant_id ?? 0);

                if (!isset($grouped[$key])) {
                    $grouped[$key] = $item;
                } else {
                    $grouped[$key]->quantity += $item->quantity;
                    $grouped[$key]->save();
                    $item->delete();
                }
            }

            // Refresh lại giỏ hàng để đảm bảo dữ liệu đồng bộ sau khi xóa dòng trùng
            $cart->refresh();
            $cart->load(['details.product', 'details.variant', 'status']);
        }

        return view('client.cart.show', compact('cart'));
    }

    public function remove($id)
    {
        $detail = CartDetail::with('cart')->findOrFail($id);

        if (!$detail->cart || $detail->cart->account_id != Auth::id()) {
            abort(403);
        }

        $detail->delete();

        return redirect()->back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }

    public function updateQuantity(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $detail = CartDetail::with('cart')->findOrFail($id);

        if (!$detail->cart || $detail->cart->account_id != Auth::id()) {
            abort(403);
        }

        $detail->quantity = $request->quantity;
        $detail->save();

        return redirect()->back()->with('success', 'Đã cập nhật số lượng.');
    }
}
