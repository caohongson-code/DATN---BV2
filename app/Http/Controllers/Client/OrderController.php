<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\ReturnRequest;
use App\Models\Review;

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



        $reviewedMap = Review::where('account_id', auth()->id())
            ->get()
            ->groupBy(function ($r) {
                return $r->order_id . '-' . $r->product_variant_id;
            });
        $statuses = OrderStatus::all(); // nếu bạn cần render các tab theo trạng thái

        return view('client.user.orders', compact('orders', 'statuses', 'reviewedMap'));
    }
    public function ajaxCancel($id)
    {
        $order = Order::where('id', $id)
            ->where('account_id', auth()->id())
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy đơn hàng'
            ], 404);
        }

        if ($order->order_status_id != 1) {
            return response()->json([
                'success' => false,
                'message' => 'Chỉ có thể huỷ đơn hàng khi đang chờ xác nhận.'
            ], 400);
        }

        $order->order_status_id = 7; // Đã huỷ

        // Nếu phương thức thanh toán là MoMo (id = 3) thì cập nhật trạng thái hoàn tiền
        if ($order->payment_method_id == 3) {
            $order->payment_status_id = 4; // Hoàn tiền
        }

        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Đã huỷ đơn hàng thành công.'
        ]);
    }

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

    public function requestReturnRefund(Request $request, $id)
    {
        $order = Order::where('id', $id)
            ->where('account_id', auth()->id())
            ->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy đơn hàng'], 404);
        }

        if ($order->order_status_id != 5) {
            return response()->json(['success' => false, 'message' => 'Chỉ có thể trả hàng khi đơn hàng đã giao.'], 400);
        }

        // Lưu ảnh
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('return_images', 'public');
            }
        }

        // Tạo yêu cầu trả hàng
        ReturnRequest::create([
            'order_id' => $order->id,
            'reason' => $request->input('reason'),
            'images' => json_encode($imagePaths),
        ]);

        // Cập nhật trạng thái đơn hàng và trạng thái thanh toán
        $order->order_status_id = 6; // Trả hàng / Hoàn tiền
        $order->payment_status_id = 4; // Hoàn tiền
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Đã gửi yêu cầu trả hàng và hoàn tiền.'
        ]);
    }
}
