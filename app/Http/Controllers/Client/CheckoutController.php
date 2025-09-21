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
use App\Jobs\CancelOrderJob;
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

    public function index(Request $request)
    {
        /** @var \App\Models\Account $user */
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để thanh toán.');
        }

        $buyNow = session('buy_now');
        $selectedItems = session('checkout_selected', []);

        // Thanh toán giỏ hàng → xóa mua ngay ngay lập tức
        if ($request->has('from_cart')) {
            $buyNow = null;
            Session::forget('buy_now');
        }

        // Thanh toán mua ngay → xóa giỏ hàng ngay lập tức
        if ($request->has('buy_now')) {
            $buyNow = $request->input('buy_now') ?: session('buy_now');
            $selectedItems = [];
            Session::forget('checkout_selected');
        }

        $cartItems = [];
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

        $shippingFee = 30000;
        $discount = 0;

        $allVouchers = $user->savedPromotions()->active()->with(['products', 'categories'])->get();

        $vouchers = $allVouchers->filter(function ($voucher) use ($cartItems) {
            $productIds = $voucher->products->pluck('id')->toArray();
            $categoryIds = $voucher->categories->pluck('id')->toArray();

            foreach ($cartItems as $item) {
                $product = $item['product'];
                if (
                    in_array($product->id, $productIds) ||
                    in_array($product->category_id, $categoryIds)
                ) {
                    return true; // Có sản phẩm phù hợp
                }
            }

            return false; // Không có sản phẩm phù hợp => loại bỏ
        })->values(); // reset key


        $selectedVoucherId = session('selected_voucher_id');

        $discount = 0;
        if ($selectedVoucherId) {
            $voucher = $user->savedPromotions()->active()->find($selectedVoucherId);
            if ($voucher) {
                // Danh sách sản phẩm và danh mục áp dụng
                $applicableProductIds = $voucher->products->pluck('id')->toArray();
                $applicableCategoryIds = $voucher->categories->pluck('id')->toArray();

                $isApplicable = false;

                foreach ($cartItems as $item) {
                    $product = $item['product'];
                    if (
                        in_array($product->id, $applicableProductIds) ||
                        in_array($product->category_id, $applicableCategoryIds)
                    ) {
                        $isApplicable = true;
                        break;
                    }
                }

                if ($isApplicable) {
                    // ✅ Tính discount chỉ áp dụng trên những sản phẩm thuộc phạm vi voucher
                    $applicableSubtotal = 0;

                    foreach ($cartItems as $item) {
                        $product = $item['product'];
                        if (
                            in_array($product->id, $applicableProductIds) ||
                            in_array($product->category_id, $applicableCategoryIds)
                        ) {
                            $applicableSubtotal += $item['subtotal'];
                        }
                    }

                    $discount = $voucher->discount_type === 'percentage'
                        ? $applicableSubtotal * ($voucher->discount_value / 100)
                        : min($voucher->discount_value, $applicableSubtotal); // tránh giảm hơn tổng
                    // dd($applicableSubtotal, $discount, $voucher->discount_value);

                } else {
                    // Không hợp lệ -> báo lỗi hoặc bỏ áp dụng
                    if ($request->isMethod('post')) {
                        return redirect()->back()->with('error', 'Mã giảm giá không áp dụng cho các sản phẩm trong giỏ.');
                    } else {
                        $voucher = null;
                        $discount = 0;
                    }
                }
            }
        }

        $total      = $subtotal + $shippingFee - $discount;
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

    /**
     * Xử lý đặt hàng
     */
    public function store(Request $request)
    {

        $request->validate([
            'voucher_id' => ['nullable', 'exists:promotions,id'],
            'payment_method' => ['required', 'in:cod,bank,momo,wallet'], // thêm wallet

            'selected_items' => ['nullable', 'array'],
            'quantities'     => ['nullable', 'array'], // thêm
        ]);
        /** @var \App\Models\Account $user */
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
            $voucher = $user->savedPromotions()->active()->find($request->voucher_id);

            if ($voucher) {
                // Danh sách sản phẩm và danh mục áp dụng
                $applicableProductIds = $voucher->products->pluck('id')->toArray();
                $applicableCategoryIds = $voucher->categories->pluck('id')->toArray();

                $isApplicable = false;

                foreach ($cartItems as $item) {
                    $product = $item['product'];
                    if (
                        in_array($product->id, $applicableProductIds) ||
                        in_array($product->category_id, $applicableCategoryIds)
                    ) {
                        $isApplicable = true;
                        break;
                    }
                }

                if ($isApplicable) {
                    // ✅ Tính discount chỉ áp dụng trên những sản phẩm thuộc phạm vi voucher
                    $applicableSubtotal = 0;

                    foreach ($cartItems as $item) {
                        $product = $item['product'];
                        if (
                            in_array($product->id, $applicableProductIds) ||
                            in_array($product->category_id, $applicableCategoryIds)
                        ) {
                            $applicableSubtotal += $item['subtotal'];
                        }
                    }

                    $discount = $voucher->discount_type === 'percentage'
                        ? $applicableSubtotal * ($voucher->discount_value / 100)
                        : min($voucher->discount_value, $applicableSubtotal); // tránh giảm hơn tổng
                    // dd($applicableSubtotal, $discount, $voucher->discount_value);

                } else {
                    // Không hợp lệ -> báo lỗi hoặc bỏ áp dụng
                    if ($request->isMethod('post')) {
                        return redirect()->back()->with('error', 'Mã giảm giá không áp dụng cho các sản phẩm trong giỏ.');
                    } else {
                        $voucher = null;
                        $discount = 0;
                    }
                }
            }
        }

        $total = $subtotal + $shippingFee - $discount;
        $maxAmount = 100000000; // 100 triệu VNĐ

        if ($total > $maxAmount) {
            return redirect()->back()->with('error', 'Số lượng hàng hoặc tổng tiền quá lớn. Vui lòng đến chi nhánh gần nhất để trao đổi.');
        }


        $paymentMethod = $request->payment_method;
        $requestId     = $request->input('request_id') ?? time() . uniqid();

        try {
            DB::beginTransaction();
            $orderId = $this->createOrder(
                $user,
                $cartItems,
                $subtotal,
                $discount,
                $shippingFee,
                $voucher,
                $paymentMethod,
                $requestId,
                $selectedItems
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


private function createOrder(
    $user,
    $cartItems,
    $subtotal,
    $discount,
    $shippingFee,
    $voucher = null,
    $paymentMethod = 'cod',
    $requestId = null,
    $selectedItems = []
) {
    // ✅ Kiểm tra tồn kho (như cũ)
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
    $paymentMethodId = $this->getPaymentMethodId($paymentMethod);

    // ⏱️ Set thời gian hết hạn nếu là MoMo
    $paymentExpiresAt = null;
    if ($paymentMethod === 'momo') {
        $paymentExpiresAt = now()->addMinute(3);
    }

    // ✅ Tạo đơn hàng
    $orderId = DB::table('orders')->insertGetId([
        'account_id'         => $user->id,
        'payment_method_id'  => $paymentMethodId,
        'shipping_zone_id'   => 1,
        'order_status_id'    => 1, // chờ xác nhận
        'payment_status_id'  => $paymentStatus,
        'voucher_id'         => $voucher?->id,
        'voucher_code'       => $voucher?->code,
        'shipping_fee'       => $shippingFee,
        'recipient_name'     => $user->full_name,
        'recipient_phone'    => $user->phone,
        'recipient_email'    => $user->email,
        'recipient_address'  => $user->address,
        'total_amount'       => $total,
        'order_date'         => now(),
        'momo_request_id'    => $requestId,
        'payment_expires_at' => $paymentExpiresAt, // 👈 thêm cột này
        'created_at'         => now(),
        'updated_at'         => now(),
    ]);

    // ✅ Lưu chi tiết đơn + trừ tồn kho (giữ nguyên)
    foreach ($cartItems as $item) {
        DB::table('order_details')->insert([
            'order_id'         => $orderId,
            'product_variant_id' => $item['variant']?->id,
            'quantity'         => $item['quantity'],
            'unit_price'       => $item['price'],
            'total_price'      => $item['subtotal'],
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        if ($item['variant']) {
            DB::table('product_variants')
                ->where('id', $item['variant']->id)
                ->decrement('quantity', $item['quantity']);
        } else {
            DB::table('products')
                ->where('id', $item['product']->id)
                ->decrement('quantity', $item['quantity']);
        }
    }

    // ✅ Xóa sản phẩm khỏi giỏ nếu cần
    if (!empty($selectedItems)) {
        CartDetail::whereIn('id', $selectedItems)->delete();
    }

    session()->forget('buy_now');

    // 🚀 Nếu là MoMo → dispatch job hủy sau 1 phút
    if ($paymentMethod === 'momo') {
        CancelOrderJob::dispatch($orderId)->delay(now()->addMinute(3));
    }

    return $orderId;
}

    private function getPaymentMethodId($code)
    {
        return DB::table('payment_methods')->where('code', $code)->value('id') ?? 1;
    }
    public function momoResult(Request $request)
    {
        $orderId = $request->input('orderId'); // ✅ Lấy orderId từ query string

        if (!$orderId) {
            return redirect()->route('home')->with('error', 'Không tìm thấy mã đơn hàng.');
        }

        $momo_trans = MomoTransaction::where('order_id', $orderId)
            ->orderByDesc('id') // hoặc ->latest('created_at')
            ->first();
        $order = DB::table('orders')->where('id', $orderId)->first();

        if (!$order) {
            return redirect()->route('home')->with('error', 'Đơn hàng không tồn tại.');
        }

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
