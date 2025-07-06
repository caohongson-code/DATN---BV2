<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatus;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function show()
    {
        $accountId = Auth::id(); // id của người dùng hiện tại

        $orders = Order::with([
            'orderStatus',
            'orderDetails.productVariant.product'
        ])
            ->where('account_id', Auth::id())
            ->latest()
            ->get();


        $statuses = OrderStatus::all(); // nếu bạn cần render các tab theo trạng thái

        return view('client.user.orders', compact('orders', 'statuses'));
    }
  public function ajaxCancel($id)
{
    $order = Order::where('id', $id)->where('account_id', auth()->id())->first();

    if (!$order) {
        return response()->json(['success' => false, 'message' => 'Không tìm thấy đơn hàng'], 404);
    }

    if ($order->order_status_id != 1) {
        return response()->json(['success' => false, 'message' => 'Chỉ có thể huỷ đơn hàng khi đang chờ xác nhận.'], 400);
    }

    $order->order_status_id = 7; // Đã huỷ
    $order->save();

    return response()->json(['success' => true, 'message' => 'Đã huỷ đơn hàng thành công.']);
} 
// Chi tiết đơn hàng
    public function detail($id)
    {
        $order = Order::with([
    'orderDetails.productVariant.product',
    'orderStatus',
    'paymentStatus', // chỉ để eager load
    'shippingZone',
    'voucher'
])
->where('id', $id)
->where('account_id', auth()->id()) // điều kiện ở bảng `orders`
->firstOrFail();


        return view('client.user.order-detail', compact('order'));
    }


}
