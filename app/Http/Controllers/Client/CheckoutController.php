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
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    /**
     * Lấy giá ưu tiên (ưu tiên giảm giá)
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
     * Hiển thị trang xác nhận đơn hàng
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để thanh toán.');
        }

        $buyNow        = session('buy_now');
        $selectedItems = session('checkout_selected', []);
        Session::forget('checkout_selected');
        $cartItems     = [];
        $subtotal      = 0;

        // ===== MUA NGAY =====
        if ($buyNow) {
            $product = Product::select('id', 'product_name', 'price', 'discount_price', 'quantity')
                ->find($buyNow['product_id']);
            if (!$product) {
                return redirect()->route('home')->with('error', 'Sản phẩm không tồn tại.');
            }

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
        // ===== THANH TOÁN GIỎ HÀNG =====
        elseif (!empty($selectedItems)) {
            $cart = Cart::with(['details.product', 'details.variant'])
                ->where('account_id', $user->id)
                ->where('cart_status_id', 1)
                ->first();

            if (!$cart) {
                return redirect()->route('cart.show')->with('error', 'Giỏ hàng trống.');
            }

            $cartDetails = CartDetail::with(['product', 'variant'])
            ->where('cart_id', $cart->id)
            ->whereIn('id', $selectedItems)
            ->get();


            if ($cartDetails->isEmpty()) {
return redirect()->route('cart.show')->with('error', 'Không có sản phẩm để thanh toán.');
            }

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
        } else {
            return redirect()->route('cart.show')->with('error', 'Không có sản phẩm để thanh toán.');
        }

        // ===== Voucher & tổng tiền =====
        $shippingFee       = 30000;
        $vouchers          = Promotion::active()->get();
        $selectedVoucherId = session('selected_voucher_id');

        $discount = 0;
        if ($selectedVoucherId) {
            $voucher = Promotion::active()->find($selectedVoucherId);
            if ($voucher) {
                $discount = $voucher->discount_type === 'percent'
                    ? $subtotal * ($voucher->discount_value / 100)
                    : $voucher->discount_value;
            }
        }

        $total      = $subtotal + $shippingFee - $discount;
        $request_id = time() . uniqid();

        return view('client.checkout.index', compact(
            'buyNow', 'cartItems', 'vouchers', 'subtotal',
            'shippingFee', 'discount', 'total', 'selectedVoucherId', 'request_id'
        ));
    }

    /**
     * Xử lý đặt hàng
     */
    public function store(Request $request)
    {
        $request->validate([
            'voucher_id'     => ['nullable', 'exists:promotions,id'],
            'payment_method' => ['required', 'in:cod,bank,momo'],
            'selected_items' => ['nullable', 'array'],
            'quantities'     => ['nullable', 'array'], // thêm
        ]);

        $user = Auth::user();
        if (!$user || !$user->phone || !$user->address) {
            return redirect()->back()->with('error', 'Vui lòng cập nhật thông tin.');
        }

        $buyNow        = session('buy_now');
        $selectedItems = $request->input('selected_items', []);
        $quantities    = $request->input('quantities', []);
        $cartItems     = [];
        $subtotal      = 0;

        // ===== MUA NGAY =====
        if ($buyNow) {
            $product = Product::find($buyNow['product_id']);
            $variant = !empty($buyNow['variant_id']) ? ProductVariant::find($buyNow['variant_id']) : null;

            $price     = $this->getFinalPrice($product, $variant);
            $quantity  = max(1, (int)($buyNow['quantity'] ?? 1));
$lineTotal = $price * $quantity;

            $subtotal += $lineTotal;
            $cartItems[] = compact('product', 'variant', 'quantity', 'price') + [
                'subtotal'  => $lineTotal,
                'from_cart' => false,
            ];
        }
        // ===== TỪ GIỎ HÀNG =====
        elseif (!empty($selectedItems)) {
            $cart = Cart::with(['details.product', 'details.variant'])
                ->where('account_id', $user->id)
                ->where('cart_status_id', 1)
                ->first();

            foreach ($cart->details->whereIn('id', $selectedItems) as $item) {
                // lấy số lượng mới từ form, nếu không có thì dùng cũ
                $qty = isset($quantities[$item->id]) ? max(1, (int)$quantities[$item->id]) : $item->quantity;
                $price     = $this->getFinalPrice($item->product, $item->variant);
                $lineTotal = $price * $qty;
                $subtotal += $lineTotal;

                $cartItems[] = [
                    'cart_detail_id' => $item->id,
                    'product'        => $item->product,
                    'variant'        => $item->variant,
                    'quantity'       => $qty,
                    'price'          => $price,
                    'subtotal'       => $lineTotal,
                    'from_cart'      => true,
                ];
            }
        }

        // Voucher & phí ship
        $shippingFee = 30000;
        $discount    = 0;
        $voucher     = null;
        if ($request->filled('voucher_id')) {
            $voucher = Promotion::active()->find($request->voucher_id);
            if ($voucher) {
                $discount = $voucher->discount_type === 'percent'
                    ? $subtotal * ($voucher->discount_value / 100)
                    : $voucher->discount_value;
            }
        }

        $paymentMethod = $request->payment_method;
        $requestId     = $request->input('request_id') ?? time() . uniqid();

        try {
            DB::beginTransaction();
            $orderId = $this->createOrder(
                $user, $cartItems, $subtotal, $discount, $shippingFee, $voucher, $paymentMethod, $requestId
            );
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }

        Session::forget('buy_now');

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
        // Kiểm tra tồn kho
foreach ($cartItems as $item) {
            $availableQty = $item['variant']
                ? DB::table('product_variants')->where('id', $item['variant']->id)->value('quantity')
                : DB::table('products')->where('id', $item['product']->id)->value('quantity');

            if ($availableQty < $item['quantity']) {
                throw new \Exception("Sản phẩm {$item['product']->product_name} không đủ hàng");
            }
        }

        $total         = $subtotal + $shippingFee - $discount;
        $paymentStatus = 1;

        $orderId = DB::table('orders')->insertGetId([
            'account_id'        => $user->id,
            'payment_method_id' => $this->getPaymentMethodId($paymentMethod),
            'shipping_zone_id'  => 1,
            'order_status_id'   => 1,
            'payment_status_id' => $paymentStatus,
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

            // Trừ tồn kho
            if ($item['variant']) {
                DB::table('product_variants')
                    ->where('id', $item['variant']->id)
                    ->decrement('quantity', $item['quantity']);
            } else {
                DB::table('products')
                    ->where('id', $item['product']->id)
                    ->decrement('quantity', $item['quantity']);
            }

            if (!empty($item['cart_detail_id'] ?? null)) {
                CartDetail::where('id', $item['cart_detail_id'])->delete();
            }
        }

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
            ->select('products.product_name','rams.value as ram','storages.value as storage',
                    'colors.value as color','order_details.quantity',
                    'order_details.unit_price','order_details.total_price')
            ->where('order_details.order_id', $orderId)
            ->get();

        return view('client.checkout.momo_result', compact('momo_trans','result_code','order','order_details'));
    }
}