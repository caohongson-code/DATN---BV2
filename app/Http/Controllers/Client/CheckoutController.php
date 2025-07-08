<?php

namespace App\Http\Controllers\Client;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Promotion;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\CartDetail;
use App\Models\Cart;
use App\Models\MomoTransaction;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để thanh toán.');
        }

        $buyNow = session('buy_now');
        $selectedItems = $request->input('selected_items', []);
        $cartItems = [];
        $subtotal = 0;

        if ($buyNow) {
            $product = Product::find($buyNow['product_id']);
            if (!$product) return redirect()->route('home')->with('error', 'Sản phẩm không tồn tại.');

            $variant = $buyNow['variant_id'] ? ProductVariant::find($buyNow['variant_id']) : null;
            $price = $variant ? $variant->price : $product->price;
            $quantity = $buyNow['quantity'];
            $subtotal = $price * $quantity;
            $cartItems[] = compact('product', 'variant', 'quantity', 'price', 'subtotal');
        } elseif (!empty($selectedItems)) {
            $cart = Cart::where('account_id', $user->id)->where('cart_status_id', 1)->first();
            if (!$cart) return redirect()->route('cart.show')->with('error', 'Giỏ hàng trống.');

            $cartDetails = $cart->details()->whereIn('id', $selectedItems)->with(['product', 'variant'])->get();
            if ($cartDetails->isEmpty()) return redirect()->route('cart.show')->with('error', 'Không có sản phẩm để thanh toán.');

            foreach ($cartDetails as $item) {
                $variant = $item->variant;

                // ✅ Ưu tiên lấy giá từ sản phẩm biến thể
                if ($variant && $variant->price) {
                    $price = $variant->price;
                } else {
                    $price = $item->product->discount_price ?? $item->product->price;
                }

                $lineTotal = $price * $item->quantity;
                $subtotal += $lineTotal;

                $cartItems[] = [
                    'cart_detail_id' => $item->id,
                    'product' => $item->product,
                    'variant' => $variant,
                    'quantity' => $item->quantity,
                    'price' => $price,
                    'subtotal' => $lineTotal
                ];
            }
        } else {
            return redirect()->route('cart.show')->with('error', 'Không có sản phẩm để thanh toán.');
        }

        $shippingFee = 30000;
        $discount = 0;
        $vouchers = Promotion::active()->get();
        $selectedVoucherId = session('selected_voucher_id');

        if ($selectedVoucherId) {
            $voucher = Promotion::active()->find($selectedVoucherId);
            if ($voucher) {
                $discount = $voucher->discount_type === 'percent'
                    ? $subtotal * ($voucher->discount_value / 100)
                    : $voucher->discount_value;
            }
        }

        $total = $subtotal + $shippingFee - $discount;
        $request_id = time() . uniqid();

        return view('client.checkout.index', compact(
            'buyNow',
            'cartItems',
            'vouchers',
            'subtotal',
            'shippingFee',
            'discount',
            'total',
            'selectedVoucherId',
            'request_id'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'voucher_id' => ['nullable', 'exists:promotions,id'],
            'payment_method' => ['required', 'in:cod,bank,momo'],
            'selected_items' => ['nullable', 'array'],
        ]);

        $user = Auth::user();
        if (!$user || !$user->phone || !$user->address) {
            return redirect()->back()->with('error', 'Vui lòng cập nhật thông tin.');
        }

        $buyNow = session('buy_now');
        $selectedItems = $request->input('selected_items', []);
        $cartItems = [];
        $subtotal = 0;

        if ($buyNow) {
            $product = Product::find($buyNow['product_id']);
            $variant = $buyNow['variant_id'] ? ProductVariant::find($buyNow['variant_id']) : null;
            $price = $variant ? $variant->price : $product->price;
            $quantity = $buyNow['quantity'];
            $subtotal = $price * $quantity;
            $cartItems[] = compact('product', 'variant', 'quantity', 'price', 'subtotal');
        } elseif (!empty($selectedItems)) {
            $cart = Cart::where('account_id', $user->id)->where('cart_status_id', 1)->first();
            $cartDetails = $cart->details()->whereIn('id', $selectedItems)->with(['product', 'variant'])->get();
            foreach ($cartDetails as $item) {
                $variant = $item->variant;

                if ($variant && $variant->price) {
                    $price = $variant->price;
                } else {
                    $price = $item->product->discount_price ?? $item->product->price;
                }

                $lineTotal = $price * $item->quantity;
                $subtotal += $lineTotal;

                $cartItems[] = [
                    'cart_detail_id' => $item->id,
                    'product' => $item->product,
                    'variant' => $variant,
                    'quantity' => $item->quantity,
                    'price' => $price,
                    'subtotal' => $lineTotal
                ];
            }
        }

        $shippingFee = 30000;
        $discount = 0;
        $voucher = null;

        if ($request->filled('voucher_id')) {
            $voucher = Promotion::active()->find($request->voucher_id);
            if ($voucher) {
                $discount = $voucher->discount_type === 'percent'
                    ? $subtotal * ($voucher->discount_value / 100)
                    : $voucher->discount_value;
            }
        }

        $total = $subtotal + $shippingFee - $discount;
        $requestId = $request->input('request_id') ?? time() . uniqid();
        $paymentMethod = $request->payment_method;

        $orderId = $this->createOrder(
            $user,
            $cartItems,
            $subtotal,
            $discount,
            $shippingFee,
            $voucher,
            $selectedItems,
            $paymentMethod,
            $requestId
        );

        if ($paymentMethod === 'momo') {
            return view('client.checkout.momo_redirect', [
                'request_id' => $requestId,
                'total' => $total,
                'orderId' => $orderId,
            ]);
        }


        return redirect()->route('home')->with('success', '✅ Đặt hàng thành công!');
    }

    public function createOrder($user, $cartItems, $subtotal, $discount, $shippingFee, $voucher = null, $selectedItems = [], $paymentMethod = 'momo', $requestId = null)
    {
        $total = $subtotal + $shippingFee - $discount;
        $payment_status = $paymentMethod === 'momo' ? 1 : 2;

        $orderId = DB::table('orders')->insertGetId([
            'account_id' => $user->id,
            'payment_method_id' => $this->getPaymentMethodId($paymentMethod),
            'shipping_zone_id' => 1,
            'order_status_id' => 1,
            'payment_status_id' => $payment_status,
            'voucher_id' => $voucher?->id,
            'voucher_code' => $voucher?->code,
            'shipping_fee' => $shippingFee,
            'recipient_name' => $user->full_name,
            'recipient_phone' => $user->phone,
            'recipient_email' => $user->email,
            'recipient_address' => $user->address,
            'total_amount' => $total,
            'order_date' => now(),
            'momo_request_id' => $requestId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach ($cartItems as $item) {
            DB::table('order_details')->insert([
                'order_id' => $orderId,
                'product_variant_id' => $item['variant']?->id,
                'quantity' => $item['quantity'],
                'unit_price' => $item['price'],
                'total_price' => $item['subtotal'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (!empty($selectedItems)) {
            CartDetail::whereIn('id', $selectedItems)->delete();
        }

        session()->forget('buy_now');
        return $orderId;
    }

    private function getPaymentMethodId($code)
    {
        return DB::table('payment_methods')->where('code', $code)->value('id') ?? 1;
    }
  public function momoResult($orderId)
{
    $momo_trans = MomoTransaction::where('order_id', $orderId)->first();
    $order = DB::table('orders')->where('id', $orderId)->first();

    $order_details = DB::table('order_details')
        ->join('product_variants', 'order_details.product_variant_id', '=', 'product_variants.id')
        ->join('products', 'product_variants.product_id', '=', 'products.id')
        ->leftJoin('rams', 'product_variants.ram_id', '=', 'rams.id')
        ->leftJoin('storages', 'product_variants.storage_id', '=', 'storages.id')
        ->leftJoin('colors', 'product_variants.color_id', '=', 'colors.id')
        ->select(
            'products.product_name as product_name',
            'rams.value as ram',
            'storages.value as storage',
            'colors.value as color',
            'order_details.quantity',
            'order_details.unit_price',
            'order_details.total_price'
        )
        ->where('order_details.order_id', $orderId)
        ->get();

    $result_code = $momo_trans->result_code ?? 99;

    return view('client.checkout.momo_result', compact('momo_trans', 'result_code', 'order', 'order_details'));
}


}
