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
use App\Events\OrderStatusUpdated;
use App\Models\ReturnRequest;
use Illuminate\Support\Str;
use App\Models\ReturnRequestProgress;
use Illuminate\Support\Carbon;

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
            $query->whereHas('account', function ($q) use ($request) {
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
        $paymentStatus = PaymentStatus::all();

        return view('admin.orders.show', compact('order', 'statuses', 'paymentMethods', 'shippingZones', 'paymentStatus'));
    }
    public function update(Request $request, $id)
    {
        $order = Order::with('orderStatus', 'orderDetails')->findOrFail($id);

        $request->validate([
            'order_status_id' => 'required|exists:order_statuses,id',
        ]);

        $newStatusId = (int) $request->order_status_id;
        $oldStatusId = $order->order_status_id;
        if ($newStatusId === 5) {
            $order->payment_status_id = 2; // Đã thanh toán
        }

        $FINAL_STATUS_IDS = [5, 6, 7]; // 5: Đã giao, 6: Trả hàng / Hoàn tiền, 7: Đã huỷ

        // Không cho phép update nếu đã vào trạng thái cuối
        if (in_array($oldStatusId, $FINAL_STATUS_IDS)) {
            return back()->with('error', 'Đơn hàng đã hoàn tất hoặc bị huỷ. Không thể cập nhật nữa.');
        }

        // Chỉ cho phép cập nhật tuần tự
        $allowedNextStatus = [];

        switch ($oldStatusId) {
            case 1:
                $allowedNextStatus = [2, 7]; // từ Chờ xác nhận → Đã xác nhận hoặc Hủy
                break;
            case 2:
                $allowedNextStatus = [3];    // từ Đã xác nhận → Đang chuẩn bị hàng
                break;
            case 3:
                $allowedNextStatus = [4];    // từ Đang chuẩn bị hàng → Đang giao
                break;
            case 4:
                $allowedNextStatus = [5];    // từ Đang giao → Đã giao
                break;
            case 5:
                $allowedNextStatus = [6];    // từ Đã giao → Trả hàng
                break;
        }

        if (!in_array($newStatusId, $allowedNextStatus)) {
            return back()->with('error', 'Chuyển trạng thái không hợp lệ. Vui lòng tuân thủ quy trình.');
        }

        DB::beginTransaction();

        try {
            // Cập nhật trạng thái mới
            $order->order_status_id = $newStatusId;

            // ✅ Nếu chuyển sang "Đang chuẩn bị hàng" thì tạo mã vận chuyển nếu chưa có
            if ($newStatusId === 3 && !$order->shipping_code) {
                $order->tracking_number = 'PPGH' . strtoupper(Str::random(7));
            }
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

            // Cập nhật tổng tiền đơn hàng
            $order->total_amount = $totalProductAmount + $order->shipping_fee;

            // ✅ Nếu chuyển sang "Đã giao" và người dùng đã xác nhận → cập nhật trạng thái thanh toán
            if ($newStatusId === 5 && $order->user_confirmed_delivery) {
                $order->payment_status_id = 2; // Đã thanh toán
            }

            $order->save();
            // OrderStatusUpdated::dispatch($order);

            DB::commit();

            return redirect()->route('admin.orders.show', $order->id)
                ->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
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
    public function listReturnRequests()
    {
        $requests = ReturnRequest::with('order.account')->orderBy('created_at', 'desc')->get();

        return view('admin.orders.return-requests', compact('requests'));
    }
    public function approveReturnRequest($id)
    {
        $request = ReturnRequest::findOrFail($id);
        $order = $request->order;

        DB::beginTransaction();

        try {
            $order->order_status_id = 6; // Trả hàng / Hoàn tiền
            $order->save();

            $request->status = 'approved';
            $request->save();

            // ✅ Ghi nhận tiến trình
            ReturnRequestProgress::create([
                'return_request_id' => $request->id,
                'status' => 'approved',
                'note' => 'Yêu cầu đã được duyệt bởi admin.',
                'completed_at' => Carbon::now(),
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Đã duyệt yêu cầu trả hàng.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }
    public function rejectReturnRequest($id)
    {
        $request = ReturnRequest::findOrFail($id);
        $request->status = 'rejected';
        $request->save();

        return redirect()->back()->with('error', 'Đã từ chối yêu cầu trả hàng.');
    }
    public function updateReturnProgress(Request $request, $returnRequestId)
    {
        $returnRequest = ReturnRequest::with('order')->findOrFail($returnRequestId);

        $status = $request->input('status');
        $note = $request->input('note');

        // Không ghi trùng bước
        $exists = ReturnRequestProgress::where('return_request_id', $returnRequestId)
            ->where('status', $status)
            ->exists();

        if (!$exists) {
            ReturnRequestProgress::create([
                'return_request_id' => $returnRequestId,
                'status' => $status,
                'note' => $note,
                'completed_at' => now(),
            ]);

            // Nếu là hoàn tiền thì cập nhật đơn hàng
            if ($status === 'refunded') {
                $returnRequest->status = 'refunded';
                $returnRequest->save();

                $returnRequest->order->update([
                    'payment_status_id' => 4,
                ]);
            }
        }

        return back()->with('success', 'Tiến trình trả hàng đã được cập nhật.');
    }
}
