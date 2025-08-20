<?php

namespace App\Http\Controllers\Client;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Promotion, Product, ProductVariant, CartDetail, Cart, MomoTransaction};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    /**
     * Lấy giá cuối cùng (ưu tiên giảm giá).
     */
    private function getFinalPrice($product, $variant = null)
    {
        if ($variant) {
            return ($variant->discount_price && $variant->discount_price < $variant->price)
                ? $variant->discount_price
                : $variant->price;
        }
        return ($product->discount_price && $product->discount_price < $product->price)
            ? $product->discount_price
            : $product->price;
    }

    /**
     * Tạo danh sách item từ session "buy_now" hoặc "checkout_selected".
     */
    private function buildCartItems($buyNow, $selectedItems, $user, &$subtotal)
    {
        $cartItems = [];

        // ===== MUA NGAY =====
        if ($buyNow) {
            $product = Product::select('id', 'product_name', 'price', 'discount_price', 'quantity')
                ->find($buyNow['product_id']);
            if (!$product) throw new \Exception('Sản phẩm không tồn tại.');

            $variant = !empty($buyNow['variant_id'])
                ? ProductVariant::with(['ram', 'storage', 'color'])->find($buyNow['variant_id'])
                : null;

            $price     = $this->getFinalPrice($product, $variant);
            $quantity  = max(1, (int)($buyNow['quantity'] ?? 1));
            $lineTotal = $price * $quantity;

            $subtotal += $lineTotal;
            $cartItems[] = compact('product', 'variant', 'quantity', 'price') + [
                'subtotal'  => $lineTotal,
                'from_cart' => false,
            ];
        }

        // ===== GIỎ HÀNG =====
        elseif (!empty($selectedItems)) {
            $cart = Cart::with(['details.product', 'details.variant'])
                ->where('account_id', $user->id)
                ->where('cart_status_id', 1)
                ->first();

            if (!$cart) throw new \Exception('Giỏ hàng trống.');

            $cartDetails = CartDetail::with(['product', 'variant'])
                ->where('cart_id', $cart->id)
                ->whereIn('id', $selectedItems)
                ->get();

            if ($cartDetails->isEmpty()) throw new \Exception('Không có sản phẩm để thanh toán.');

            foreach ($cartDetails as $item) {
                $price     = $this->getFinalPrice($item->product, $item->variant);
                $quantity  = max(1, (int)$item->quantity);
                $lineTotal = $price * $quantity;
                $subtotal += $lineTotal;

                $cartItems[] = [
                    'cart_detail_id' => $item->id,
                    'product'        => $item->product,
                    'variant'        => $item->variant,
                    'quantity'       => $quantity,
                    'price'          => $price,
                    'subtotal'       => $lineTotal,
                    'from_cart'      => true,
                ];
            }
        }

        return $cartItems;
    }

    /**
     * Trang xác nhận đơn hàng.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để thanh toán.');

        $buyNow        = session('buy_now');
        $selectedItems = session('checkout_selected', []);
        Session::forget('checkout_selected');

        try {
            $subtotal  = 0;
            $cartItems = $this->buildCartItems($buyNow, $selectedItems, $user, $subtotal);
        } catch (\Exception $e) {
            return redirect()->route('cart.show')->with('error', $e->getMessage());
        }

        // Voucher
        $shippingFee = 30000;
        $vouchers = Promotion::active()->get();
        $selectedVoucherId = $request->input('voucher_id', session('selected_voucher_id'));
        if ($selectedVoucherId) session(['selected_voucher_id' => $selectedVoucherId]);

        $discount = 0;
        $selectedVoucher = null;
        if ($selectedVoucherId) {
            $selectedVoucher = Promotion::active()->find($selectedVoucherId);
            if ($selectedVoucher) {
                $discount = $selectedVoucher->discount_type === 'percent'
                    ? $subtotal * ($selectedVoucher->discount_value / 100)
                    : $selectedVoucher->discount_value;
            }
        }

        $total      = $subtotal + $shippingFee - $discount;
        $request_id = time() . uniqid();

        return view('client.checkout.index', compact(
            'buyNow', 'cartItems', 'vouchers', 'subtotal',
            'shippingFee', 'discount', 'total', 'selectedVoucherId', 'request_id', 'selectedVoucher'
        ));
    }

    /**
     * Đặt hàng.
     */
    public function store(Request $request)
    {
        $request->validate([
            'voucher_id'     => ['nullable', 'exists:promotions,id'],
            'payment_method' => ['required', 'in:cod,bank,momo'],
            'selected_items' => ['nullable', 'array'],
            'quantities'     => ['nullable', 'array'],
        ]);

        $user = Auth::user();
        if (!$user) return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để thanh toán.');
        if (!$user->phone || !$user->address) {
            return redirect()->route('user.profile')->with('error', 'Cập nhật số điện thoại & địa chỉ trước khi thanh toán.');
        }

        $buyNow        = session('buy_now');
        $selectedItems = $request->input('selected_items', []);
        $quantities    = $request->input('quantities', []);

        try {
            $subtotal  = 0;
            $cartItems = $this->buildCartItems($buyNow, $selectedItems, $user, $subtotal);

            // cập nhật số lượng theo request (nếu có)
            foreach ($cartItems as &$item) {
                if ($item['from_cart'] && isset($quantities[$item['cart_detail_id']])) {
                    $item['quantity'] = max(1, (int)$quantities[$item['cart_detail_id']]);
                    $item['subtotal'] = $item['quantity'] * $item['price'];
                }
            }

            // Voucher
            $shippingFee = 30000;
            $discount    = 0;
            $voucher     = $request->filled('voucher_id')
                ? Promotion::active()->find($request->voucher_id)
                : null;
            if ($voucher) {
                $discount = $voucher->discount_type === 'percent'
                    ? $subtotal * ($voucher->discount_value / 100)
                    : $voucher->discount_value;
            }

            $paymentMethod = $request->payment_method;
            $requestId     = $request->input('request_id') ?? time() . uniqid();

            DB::beginTransaction();
            $orderId = $this->createOrder($user, $cartItems, $subtotal, $discount, $shippingFee, $voucher, $paymentMethod, $requestId);
            DB::commit();

            Session::forget(['buy_now', 'checkout_selected', 'selected_voucher_id']);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }

        if ($paymentMethod === 'momo') {
            return view('client.checkout.momo_redirect', [
                'request_id' => $requestId,
                'total'      => $subtotal + $shippingFee - $discount,
                'orderId'    => $orderId,
            ]);
        }

        return redirect()->route('home')->with('success', '✅ Đặt hàng thành công!');
    }

    private function createOrder($user, $cartItems, $subtotal, $discount, $shippingFee, $voucher = null, $paymentMethod = 'cod', $requestId = null)
    {
        // Check tồn kho
        foreach ($cartItems as $item) {
            $availableQty = $item['variant']
                ? DB::table('product_variants')->where('id', $item['variant']->id)->value('quantity')
                : DB::table('products')->where('id', $item['product']->id)->value('quantity');
            if ($availableQty < $item['quantity']) {
                throw new \Exception("Sản phẩm {$item['product']->product_name} không đủ hàng");
            }
        }

        $total = $subtotal + $shippingFee - $discount;
        $orderId = DB::table('orders')->insertGetId([
            'account_id'        => $user->id,
            'payment_method_id' => $this->getPaymentMethodId($paymentMethod),
            'shipping_zone_id'  => 1,
            'order_status_id'   => 1,
            'payment_status_id' => 1,
            'voucher_id'        => $voucher?->id,
            'voucher_code'      => $voucher?->code,
            'shipping_fee'      => $shippingFee,
            'recipient_name'    => $user->full_name,
            'recipient_phone'   => $user->phone,
            'recipient_email'   => $user->email,
            'recipient_address' => $user->address,
            'total_amount'      => $total,
            'order_date'        => now(),
            'momo_request_id'   => $requestId,
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        foreach ($cartItems as $item) {
            DB::table('order_details')->insert([
                'order_id'           => $orderId,
                'product_variant_id' => $item['variant']?->id,
                'quantity'           => $item['quantity'],
                'unit_price'         => $item['price'],
                'total_price'        => $item['subtotal'],
                'created_at'         => now(),
                'updated_at'         => now(),
            ]);

            // Trừ kho
            $table = $item['variant'] ? 'product_variants' : 'products';
            $id    = $item['variant']?->id ?? $item['product']->id;
            DB::table($table)->where('id', $id)->decrement('quantity', $item['quantity']);

            // Xóa khỏi giỏ
            if (!empty($item['cart_detail_id'])) {
                CartDetail::where('id', $item['cart_detail_id'])->delete();
            }
        }

        return $orderId;
    }

    private function getPaymentMethodId($code)
    {
        return DB::table('payment_methods')->where('code', $code)->value('id') ?? 1;
    }

    public function momoResult(Request $request, $orderId = null)
    {
        $orderId = $orderId ?? $request->input('orderId');
        if (!$orderId) return redirect()->route('home')->with('error', 'Không tìm thấy đơn hàng.');

        $momo_trans = MomoTransaction::where('order_id', $orderId)->first();
        $order = DB::table('orders')->where('id', $orderId)->first();
        $result_code = $momo_trans->result_code ?? 99;

        if (in_array($result_code, [0, 9000])) {
            DB::table('orders')->where('id', $orderId)->update(['payment_status_id' => 2]);
            $order->payment_status_id = 2;
        }

        $order_details = DB::table('order_details')
            ->join('product_variants', 'order_details.product_variant_id', '=', 'product_variants.id')
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->leftJoin('rams', 'product_variants.ram_id', '=', 'rams.id')
            ->leftJoin('storages', 'product_variants.storage_id', '=', 'storages.id')
            ->leftJoin('colors', 'product_variants.color_id', '=', 'colors.id')
            ->select(
                'products.product_name',
                'rams.value as ram',
                'storages.value as storage',
                'colors.value as color',
                'order_details.quantity',
                'order_details.unit_price',
                'order_details.total_price'
            )
            ->where('order_details.order_id', $orderId)
            ->get();

        return view('client.checkout.momo_result', compact('momo_trans', 'result_code', 'order', 'order_details'));
    }
}
