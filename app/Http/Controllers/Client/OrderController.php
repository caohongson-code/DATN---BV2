<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDeliveryIssue;
use App\Models\OrderStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\ReturnRequest;
use App\Models\Review;
use Carbon\Carbon;
use App\Models\ShopInfo;
use App\Models\ReturnRequestProgress; 
class OrderController extends Controller
{

    public function show()
{
    $accountId = Auth::id();

    // Lấy danh sách đơn hàng
    $orders = Order::with([
        'orderStatus',
        'orderDetails.productVariant.product'
    ])
        ->where('account_id', $accountId)
        ->latest()
        ->get();

    // ✅ Tự động xác nhận đã nhận hàng nếu quá 3 ngày
    foreach ($orders as $order) {
        if (
            $order->order_status_id == 5 && // Đã giao
            !$order->user_confirmed_delivery &&
            $order->delivered_at &&
            Carbon::parse($order->delivered_at)->addDays(3)->lt(now())
        ) {
            $order->user_confirmed_delivery = true;
            $order->save();
        }
    }

    // ✅ Đánh giá đã thực hiện (map theo order_id-variant_id)
    $reviewedMap = Review::where('account_id', $accountId)
        ->get()
        ->groupBy(function ($r) {
            return $r->order_id . '-' . $r->product_variant_id;
        });

    // ✅ Lấy tất cả trạng thái đơn hàng (nếu bạn dùng trong view để lọc hoặc hiển thị)
    $statuses = OrderStatus::all();

    // ✅ Lấy các yêu cầu trả hàng
    $returnedRequests = ReturnRequest::whereIn('order_id', $orders->pluck('id'))->get();
    $returnedOrders = $returnedRequests->keyBy('order_id');

    // ✅ Lấy các phản hồi giao hàng nếu có
    $deliveryIssues = OrderDeliveryIssue::whereIn('order_id', $orders->pluck('id'))->get()->keyBy('order_id');
$progresses = ReturnRequestProgress::whereIn('return_request_id', $returnedOrders->pluck('id'))->get()->groupBy('return_request_id');

    // ✅ Truyền sang view
    return view('client.user.orders', compact(
        'orders',
        'statuses',
        'reviewedMap',
        'returnedOrders',
        'deliveryIssues', // ✅ truyền biến mới
        'progresses',
    ));
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
        'paymentStatus',
        'shippingZone',
        'voucher'
    ])
        ->where('id', $id)
        ->where('account_id', auth()->id())
        ->firstOrFail();

    // Truy xuất yêu cầu trả hàng nếu có
    $returnRequest = ReturnRequest::where('order_id', $order->id)->first();

    // Truy xuất các bước xử lý trả hàng (nếu có)
    $returnRequestProgresses = collect();

    if ($returnRequest) {
        $returnRequestProgresses = $returnRequest->progresses()->latest()->get();
    }

    return view('client.user.order-detail', compact('order', 'returnRequest', 'returnRequestProgresses'));
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

        if (ReturnRequest::where('order_id', $order->id)->exists()) {
            return response()->json(['success' => false, 'message' => 'Bạn đã gửi yêu cầu trả hàng cho đơn này.'], 400);
        }

        $request->validate([
            'reason' => 'required|string|max:1000',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('return_images', 'public');
            }
        }

        // ✅ Tạo yêu cầu trả hàng
        $returnRequest = ReturnRequest::create([
            'order_id' => $order->id,
            'reason' => $request->input('reason'),
            'images' => json_encode($imagePaths),
            'status' => 'pending'
        ]);

        // ✅ Cập nhật trạng thái đơn hàng và thanh toán
        $order->order_status_id = 6; // Trả hàng/Hoàn tiền
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Yêu cầu trả hàng của bạn đã được gửi. Đơn hàng đã chuyển sang trạng thái hoàn tiền.',
            'return_request_id' => $returnRequest->id
        ]);
    }

    public function cancelReturnRequest($id)
    {
        $returnRequest = ReturnRequest::where('id', $id)
            ->whereHas('order', function ($query) {
                $query->where('account_id', auth()->id());
            })
            ->first();

        if (!$returnRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy yêu cầu trả hàng.'
            ], 404);
        }

        if ($returnRequest->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Chỉ có thể hủy yêu cầu đang chờ xử lý.'
            ], 400);
        }

        // ✅ Lấy đơn hàng liên quan từ returnRequest
        $order = $returnRequest->order;

        // ✅ Cập nhật lại trạng thái đơn hàng và thanh toán nếu cần
        $order->order_status_id = 5; // Đã giao lại

        if ($order->payment_method_id == 3) { // Nếu là MoMo
            $order->payment_status_id = 3; // Đã thanh toán
        }

        $order->save();

        // ✅ Xoá yêu cầu trả hàng
        $returnRequest->delete(); // hoặc: $returnRequest->update(['status' => 'cancelled']);

        return response()->json([
            'success' => true,
            'message' => 'Đã hủy yêu cầu trả hàng.'
        ]);
    }
    public function confirmReceived($id)
    {
        $order = Order::where('id', $id)
            ->where('account_id', auth()->id())
            ->where('order_status_id', 5)
            ->firstOrFail();

        $order->update(['user_confirmed_delivery' => true]);

        return response()->json(['message' => 'Cảm ơn bạn đã xác nhận.']);
    }

    public function reportDeliveryIssue(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'reason' => 'required|string|max:255',
            'note' => 'nullable|string',
        ]);

        $order = Order::where('id', $request->order_id)
            ->where('account_id', auth()->id())
            ->where('order_status_id', 5)
            ->firstOrFail();

        OrderDeliveryIssue::create([
            'order_id' => $order->id,
            'account_id' => auth()->id(),
            'reason' => $request->reason,
            'note' => $request->note,
        ]);

        return response()->json(['message' => 'Chúng tôi đã ghi nhận phản hồi của bạn.']);
    }
 public function submitTrackingCode(Request $request, $returnRequestId)
{
    $request->validate([
        'tracking_number' => 'required|string|max:255',
        'shipping_images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        'bank_name' => 'required|string|max:100',
        'bank_account' => 'required|string|max:100',
    ]);

    $returnRequest = ReturnRequest::with('order')->findOrFail($returnRequestId);
    $order = $returnRequest->order;

    // ❗ Kiểm tra mã vận đơn có khớp với tracking của order không
    if ($order->tracking_number !== $request->tracking_number) {
        return redirect()->back()->withErrors(['tracking_number' => 'Mã vận đơn không khớp với thông tin đã cung cấp.']);
    }

    // ✅ Upload ảnh shipping
    $imagePaths = [];
    if ($request->hasFile('shipping_images')) {
        foreach ($request->file('shipping_images') as $image) {
            $path = $image->store('return_shipping', 'public');
            $imagePaths[] = $path;
        }
    }

    // ✅ Cập nhật return request
    $returnRequest->shipping_images = $imagePaths;
    $returnRequest->bank_name = $request->bank_name;
    $returnRequest->bank_account = $request->bank_account;
    $returnRequest->save();

    // ✅ Ghi tiến trình vào bảng return_request_progresses
    ReturnRequestProgress::create([
        'return_request_id' => $returnRequest->id,
        'status' => 'shipping_pending', // hoặc 'shipped_back'
        'note' => 'Khách đã gửi hàng với mã vận đơn hợp lệ',
        'completed_at' => now(),
    ]);

    return redirect()->route('user.orders')->with('success', 'Xác nhận gửi hàng thành công!');
}







public function showTrackingForm($id)
{
    $returnRequest = ReturnRequest::with('order.orderDetails.productVariant.product')->where('id', $id)
        ->whereHas('order', function ($query) {
            $query->where('account_id', auth()->id());
        })
        ->firstOrFail();

    if ($returnRequest->status !== 'approved') {
        return redirect()->route('user.orders')->withErrors(['msg' => 'Yêu cầu chưa được duyệt.']);
    }

    // Lấy thông tin shop
    $shopInfo = ShopInfo::first();

    return view('client.user.enter-tracking', compact('returnRequest', 'shopInfo'));
}




}
