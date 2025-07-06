<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\PaymentMethod;
use App\Models\OrderDetail;
use App\Models\Cart;
use App\Models\PaymentStatus;
use App\Models\ShippingZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with([
            'account',
            'paymentMethod',
            'orderStatus',
            'cart.statusModel',
            'shippingZone'
        ]);

        if ($request->search) {
            $query->whereHas('account', function($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->order_status_id) {
            $query->where('order_status_id', $request->order_status_id);
        }

        if ($request->order_date) {
            $query->whereDate('order_date', $request->order_date);
        }

        $orders = $query->orderBy('order_date', 'desc')->paginate(15);

        // Tính tổng tiền theo bộ lọc hiện tại (tách riêng để tránh ảnh hưởng query pagination)
        $totalAmountAll = (clone $query)->sum('total_amount');

        $statuses = OrderStatus::all();

        return view('admin.orders.index', compact('orders', 'statuses', 'totalAmountAll'));
    }

  public function show($id)
{
    $order = Order::with([
        'orderDetails.productVariant.product',
        'orderDetails.productVariant.ram',
        'orderDetails.productVariant.storage',
        'orderDetails.productVariant.color',
        'orderStatus',
        'paymentMethod',
        'shippingZone',
        'paymentStatus'
    ])->findOrFail($id);

    $statuses = OrderStatus::all();
    $paymentMethods = PaymentMethod::all();
    $shippingZones = ShippingZone::all();
    $paymentStatuses = PaymentStatus::all();

    return view('admin.orders.show', compact('order', 'statuses', 'paymentMethods', 'shippingZones','paymentStatuses'));
}
    public function update(Request $request, $id)
    {
        $order = Order::with('orderStatus', 'orderDetails')->findOrFail($id);

        $request->validate([
            'order_status_id' => 'required|exists:order_statuses,id',
        ]);
        // Nếu đơn hàng đã bị hủy → không được cập nhật nữa



        $newStatusId = (int) $request->order_status_id;
        $oldStatusId = $order->order_status_id;

        $shippingStatusStartId = 3; // trạng thái "Đang giao hàng"
        $cancelledStatusId = 5;     // trạng thái "Đã hủy"

        if ($oldStatusId == $cancelledStatusId) {
        return back()->with('error', 'Đơn hàng đã bị hủy và không thể cập nhật trạng thái nữa.');
        }

        // Không cho phép quay lại trạng thái thấp hơn
        if ($oldStatusId > 1 && $newStatusId == 1) {
            return back()->with('error', 'Không thể quay lại trạng thái "Chờ xác nhận" sau khi đã xác nhận.');
        }
        if ($oldStatusId > 2 && $newStatusId == 2) {
            return back()->with('error', 'Không thể quay lại trạng thái "Đang xác nhận" sau khi đã qua.');
        }
        if ($oldStatusId > 3 && $newStatusId == 3) {
            return back()->with('error', 'Không thể quay lại trạng thái "Đang giao" sau khi đã giao.');
        }
        // Không cho phép hủy đơn nếu trạng thái hiện tại là "Đang giao hàng" trở lên
        if ($oldStatusId >= $shippingStatusStartId && $newStatusId == $cancelledStatusId) {
            return back()->with('error', 'Không thể hủy đơn hàng khi đơn đang trong trạng thái "Đang giao" hoặc đã giao.');
        }

        DB::beginTransaction();

        try {
            // Cập nhật trạng thái mới
            $order->order_status_id = $newStatusId;

            // Cập nhật phí vận chuyển
            if ($order->shipping_zone_id) {
                $shippingZone = ShippingZone::find($order->shipping_zone_id);
                $order->shipping_fee = $shippingZone?->shipping_fee ?? 30000;
            } elseif (is_null($order->shipping_fee)) {
                $order->shipping_fee = 30000;
            }

            // Tính tổng tiền sản phẩm
            $totalProductAmount = $order->orderDetails->sum(function ($detail) {
                return $detail->quantity * ($detail->price ?? 0);
            }) ?? 0;

            // Cập nhật tổng tiền đơn hàng (sản phẩm + phí ship)
            $order->total_amount = $totalProductAmount + $order->shipping_fee;

            $order->save();

            DB::commit();

            return redirect()->route('admin.orders.show', $order->id)->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Lỗi cập nhật trạng thái đơn hàng #$id: " . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())->withInput();
        }
    }

    public function placeOrderFromCart($cartId)
    {
        DB::beginTransaction();

        try {
            $cart = Cart::with(['details.productVariant', 'account', 'shippingZone'])->findOrFail($cartId);

            if ($cart->status !== 'active') {
                return redirect()->back()->with('error', 'Giỏ hàng không hợp lệ hoặc đã được đặt.');
            }

            if ($cart->details->isEmpty()) {
                return redirect()->back()->with('error', 'Giỏ hàng không có sản phẩm.');
            }

            // Tính phí ship
            $shippingZoneId = $cart->shipping_zone_id ?? null;
            if ($shippingZoneId) {
                $shippingZone = ShippingZone::find($shippingZoneId);
                $shippingFee = $shippingZone?->shipping_fee ?? 30000;
            } else {
                $shippingFee = 30000;
            }

            // Tạo đơn hàng tạm với total_amount = 0
            $order = Order::create([
                'account_id' => $cart->account_id,
                'cart_id' => $cart->id,
                'order_status_id' => 1, // trạng thái "Chờ xác nhận"
                'payment_method_id' => null,
                'total_amount' => 0,
                'shipping_zone_id' => $shippingZoneId,
                'shipping_fee' => $shippingFee,
                'note' => null,
                'recipient_name' => $cart->account->full_name ?? 'Tên người nhận',
                'recipient_phone' => $cart->account->phone ?? 'SĐT',
                'recipient_address' => $cart->account->address ?? 'Địa chỉ',
            ]);

            $totalProductAmount = 0;

            foreach ($cart->details as $detail) {
                $price = $detail->productVariant->price ?? 0;
                $quantity = $detail->quantity;
                $totalPrice = $price * $quantity;

                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_variant_id' => $detail->product_variant_id,
                    'price' => $price,
                    'quantity' => $quantity,
                    'total_price' => $totalPrice, // nếu có cột total_price trong bảng order_details
                ]);

                $totalProductAmount += $totalPrice;
            }

            // Cập nhật lại tổng tiền đơn hàng
            $order->update([
                'total_amount' => $totalProductAmount + $shippingFee
            ]);

            // Đánh dấu giỏ hàng đã đặt
            $cart->update(['status' => 'ordered']);

            DB::commit();

            return redirect()->route('admin.orders.index')->with('success', 'Đặt hàng thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Lỗi đặt hàng: ' . $e->getMessage());
        }
    }
}