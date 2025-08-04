<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    // Thêm sản phẩm vào giỏ
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $userId = Auth::id();
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để thêm giỏ hàng.');
        }

        $cart = Cart::firstOrCreate(
            ['account_id' => $userId, 'cart_status_id' => 1],
            []
        );

        CartDetail::create([
            'cart_id' => $cart->id,
            'product_id' => $request->product_id,
            'product_variant_id' => $request->product_variant_id,
            'quantity' => $request->quantity,
        ]);

        return redirect()->back()->with('success', 'Đã thêm vào giỏ hàng!');
    }

    // Mua ngay
    public function buyNow(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để mua hàng.');
        }

        $product = Product::findOrFail($request->product_id);
        $variant = $request->variant_id ? ProductVariant::findOrFail($request->variant_id) : null;

        // Lấy giá khuyến mãi
        if ($variant && $variant->discount_price && $variant->discount_price < $variant->price) {
            $finalPrice = $variant->discount_price;
        } elseif ($variant) {
            $finalPrice = $variant->price;
        } elseif ($product->discount_price && $product->discount_price < $product->price) {
            $finalPrice = $product->discount_price;
        } else {
            $finalPrice = $product->price;
        }

        Session::put('buy_now', [
            'product_id' => $product->id,
            'variant_id' => $variant?->id,
            'quantity'   => (int)$request->quantity,
            'price'      => $finalPrice,
        ]);

        return redirect()->route('checkout');
    }

    // Hiển thị giỏ hàng
    public function show()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để xem giỏ hàng.');
        }
$cart = Cart::with(['details.product', 'details.variant.ram', 'details.variant.storage', 'details.variant.color'])
            ->where('account_id', Auth::id())
            ->where('cart_status_id', 1)
            ->first();

        return view('client.cart.show', compact('cart'));
    }

    // Xóa 1 sản phẩm
    public function remove($id, Request $request)
    {
        try {
            $detail = CartDetail::with('cart')->find($id);

            if (!$detail) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Không tìm thấy sản phẩm'
                ], 404);
            }

            if (!$detail->cart || $detail->cart->account_id != Auth::id()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Không có quyền xóa sản phẩm này'
                ], 403);
            }

            $detail->delete();

            return response()->json([
                'success' => true, 
                'message' => 'Đã xóa sản phẩm thành công'
            ]);

        } catch (\Exception $e) {
            Log::error('Lỗi xóa sản phẩm giỏ hàng: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Đã xảy ra lỗi khi xóa sản phẩm'
            ], 500);
        }
    }


    // Cập nhật số lượng
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

    // Xóa nhiều sản phẩm

    public function updateBeforeCheckout(Request $request)
{
    // Lưu danh sách sản phẩm được chọn vào session
    $selectedItems = $request->input('selected_items', []);
    $quantities    = $request->input('quantities', []);

    if (empty($selectedItems)) {
        return redirect()->route('cart.show')->with('error', 'Vui lòng chọn ít nhất một sản phẩm.');
    }

    // Cập nhật số lượng sản phẩm
    foreach ($quantities as $id => $qty) {
        CartDetail::where('id', $id)
            ->whereHas('cart', function ($q) {
                $q->where('account_id', Auth::id());
            })
            ->update(['quantity' => max(1, (int)$qty)]);
    }

    // Lưu danh sách đã chọn vào session để sang trang thanh toán
    session(['checkout_selected' => $selectedItems]);

    return redirect()->route('checkout');
}

}