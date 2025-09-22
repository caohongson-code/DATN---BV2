@extends('client.user.dashboard')

@section('dashboard-content')
    <h3 class="mb-4">📦 Chi tiết đơn hàng </h3>

    <div class="panel panel-primary">
        <div class="panel-heading">
            <strong>Trạng thái đơn hàng:</strong>
            <span class="text-warning">{{ $order->orderStatus->status_name ?? 'Không rõ' }}</span><br>

            <strong>Ngày đặt hàng:</strong> {{ $order->created_at->format('d/m/Y H:i') }} <br>
            <strong>Lý do hủy hàng:</strong> 
            <span class="text-warning">{{ $order->cancel_reason}}</span>

            @if ($order->order_status_id == 7)
                <br><strong>Ngày huỷ:</strong>
                <span class="text-danger">{{ $order->updated_at->format('d/m/Y H:i') }}</span>
            @endif
        </div>

        <div class="panel-body">
            @foreach ($order->orderDetails as $item)
                @php
                    $variant = $item->productVariant;
                    $product = $variant?->product;
                    $image = $product?->image ? asset('storage/' . $product->image) : asset('images/default.jpg');

                    // Giá: ưu tiên discount_price, nếu không có thì dùng price
                    $variantPrice =
                        $variant?->discount_price && $variant->discount_price > 0
                            ? $variant->discount_price
                            : $variant->price;

                    // Tổng tiền
                    $totalPrice = $variantPrice * $item->quantity;

                    // Biến thể
                    $ramValue = $variant?->ram?->value ?? null;
                    $storageValue = $variant?->storage?->value ?? null;
                    $colorValue = $variant?->color?->value ?? null;
                    $colorCode = $variant?->color?->code ?? null;
                @endphp

                <div class="media" style="border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 15px;">
                    <div class="media-left">
                        <img class="media-object img-thumbnail" src="{{ $image }}" alt="Ảnh sản phẩm"
                            style="width: 90px; height: 90px; object-fit: cover;">
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading">{{ $product->product_name ?? 'Không rõ sản phẩm' }}</h4>
                        <p>Số lượng: <strong>{{ $item->quantity }}</strong></p>

                        {{-- Giá hiển thị --}}
                        <p>Giá:
                            @if ($variant->discount_price && $variant->discount_price > 0)
                                <strong>{{ number_format($variant->discount_price, 0, ',', '.') }}₫</strong>
                                <del style="color:#999;">{{ number_format($variant->price, 0, ',', '.') }}₫</del>
                            @else
                                <strong>{{ number_format($variant->price, 0, ',', '.') }}₫</strong>
                            @endif
                        </p>

                        {{-- Tổng tiền --}}
                        <p>Tổng: <strong>{{ number_format($totalPrice, 0, ',', '.') }}₫</strong></p>

                        {{-- Biến thể --}}
                        @if ($ramValue || $storageValue || $colorValue)
                            <p>Biến thể:
                                @if ($ramValue)
                                    RAM: <strong>{{ $ramValue }}</strong>
                                @endif
                                @if ($storageValue)
                                    , Storage: <strong>{{ $storageValue }}</strong>
                                @endif
                                @if ($colorValue)
                                    , Màu: <strong>{{ $colorValue }}</strong>
                                    <span
                                        style="display:inline-block;width:15px;height:15px;background:{{ $colorCode }};border:1px solid #000;margin-left:5px;"></span>
                                @endif
                            </p>
                        @endif
                    </div>
                </div>
            @endforeach



            <hr>

            {{-- Thông tin giao hàng --}}
            <h4 class="mb-3">🚚 Thông tin giao hàng</h4>
            <p><strong>Người nhận:</strong> {{ $order->recipient_name }}</p>
            <p><strong>SĐT:</strong> {{ $order->recipient_phone }}</p>
            <p><strong>Địa chỉ:</strong> {{ $order->recipient_address }}</p>
            @if ($order->shippingZone)
                {{-- <p><strong>Khu vực giao hàng:</strong> {{ $order->shippingZone->name }}</p> --}}
            @endif
            @if ($order->tracking_number)
                <p><strong>Mã vận chuyển:</strong> <span class="text-primary">{{ $order->tracking_number }}</span></p>
            @endif


            <hr>

           {{-- Thông tin thanh toán --}}
<h4 class="mb-3">💳 Thông tin thanh toán</h4>

<p><strong>Phương thức thanh toán:</strong>
    {{ $order->paymentMethod->method_name ?? 'Không rõ' }}
</p>

@php
    // Trạng thái thanh toán
    $paymentStatus = $order->paymentStatus;
    $paymentStatusName = $paymentStatus->name ?? 'Không xác định';

    $paymentStatusColor = match ($order->payment_status_id) {
        1 => 'text-warning', // Chờ thanh toán
        2 => 'text-success', // Đã thanh toán
        3 => 'text-danger',  // Thanh toán thất bại
        4 => 'text-info',    // Hoàn tiền
        default => 'text-secondary',
    };

    // Tính tổng tiền hàng (subtotal) từ chi tiết đơn hàng
    $orderSubtotal = $order->orderDetails->sum(function ($item) {
        $variant = $item->productVariant;
        $price = ($variant && $variant->discount_price > 0)
                    ? $variant->discount_price
                    : ($variant->price ?? 0);
        return $price * $item->quantity;
    });

    // Tính số tiền giảm
    $discountAmount = 0;
    $promotion = $order->voucher; // Quan hệ Eloquent đến bảng promotions

    if ($promotion) {
        if ($promotion->discount_type === 'percentage') {
            // Giảm theo %
            $discountAmount = $orderSubtotal * ($promotion->discount_value / 100);
        } else {
            // Giảm số tiền cố định
            $discountAmount = $promotion->discount_value;
        }
    }
@endphp

<p><strong>Trạng thái thanh toán:</strong>
    <span class="{{ $paymentStatusColor }}">{{ $paymentStatusName }}</span>
</p>

{{-- Hiển thị tổng tiền hàng trước giảm --}}
<p><strong>Tổng tiền hàng:</strong> {{ number_format($orderSubtotal, 0, ',', '.') }}₫</p>

{{-- Hiển thị giảm giá nếu có --}}
@if ($promotion)
    <p><strong>Mã giảm giá:</strong> {{ $promotion->code }}</p>
    <p><strong>Giảm giá:</strong>
        <span class="text-success">-{{ number_format($discountAmount, 0, ',', '.') }}₫</span>
        @if ($promotion->discount_type === 'percentage')
            <small class="text-muted">
                ({{ rtrim(rtrim($promotion->discount_value, '0'), '.') }}%)
            </small>
        @endif
    </p>
@endif

{{-- Phí vận chuyển --}}
<p><strong>Phí vận chuyển:</strong> {{ number_format($order->shipping_fee, 0, ',', '.') }}₫</p>

{{-- Tổng tiền phải thanh toán --}}
<p><strong>Tổng tiền sau giảm:</strong>
    <span class="text-danger fw-bold">{{ number_format($order->total_amount, 0, ',', '.') }}₫</span>
</p>


            @if ($order->note)
                <hr>
                <h4 class="mb-3">📝 Ghi chú đơn hàng</h4>
                <p>{{ $order->note }}</p>
            @endif
        </div>
    </div>
    @if ($order->order_status_id == 6 && $returnRequest)
        <hr>
        <h4 class="mb-3">🔁 Yêu cầu trả hàng / hoàn tiền</h4>
        <p><strong>Lý do:</strong> {{ $returnRequest->reason }}</p>

        @php
            $images = json_decode($returnRequest->images ?? '[]', true);
        @endphp

        @if (!empty($images))
            <p><strong>Ảnh minh hoạ:</strong></p>
            <div class="row">
                @foreach ($images as $img)
                    <div class="col-md-3 mb-2">
                        <img src="{{ asset('storage/' . $img) }}" class="img-fluid rounded border" alt="Ảnh trả hàng">
                    </div>
                @endforeach
            </div>
        @endif

        @if ($returnRequestProgresses && $returnRequestProgresses->count())
            @php
                $refundProgress = $returnRequestProgresses->firstWhere('status', 'refunded');

                // Xử lý images an toàn
                $refundImages = [];
                if ($refundProgress) {
                    $imagesData = $refundProgress->images;
                    if (is_string($imagesData)) {
                        $refundImages = json_decode($imagesData, true) ?? [];
                    } elseif (is_array($imagesData)) {
                        $refundImages = $imagesData;
                    }
                }
            @endphp

            @if ($refundProgress)
                <div class="mt-4 p-3 border rounded bg-light">
                    <h5 class="mb-3">💸 Thông tin hoàn tiền</h5>

                    @if ($refundProgress->note)
                        <p><strong>Nội dung hoàn tiền:</strong> {{ $refundProgress->note }}</p>
                    @endif

                    @if (!empty($refundImages))
                        <p><strong>Ảnh hoá đơn / minh chứng:</strong></p>
                        <div class="row">
                            @foreach ($refundImages as $img)
                                <div class="col-md-3 mb-2">
                                    <img src="{{ asset('storage/' . $img) }}" class="img-fluid rounded border"
                                        alt="Ảnh hoàn tiền">
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="mt-3">
                        @if ($refundProgress->refunded_by_name)
                            <p><strong>Người hoàn tiền:</strong> {{ $refundProgress->refunded_by_name }}</p>
                        @endif
                        @if ($refundProgress->refunded_by_email)
                            <p><strong>Email liên hệ:</strong> {{ $refundProgress->refunded_by_email }}</p>
                        @endif
                        @if ($refundProgress->refunded_bank_name)
                            <p><strong>Ngân hàng:</strong> {{ $refundProgress->refunded_bank_name }}</p>
                        @endif
                        @if ($refundProgress->refunded_account_number)
                            <p><strong>Tài khoản nhận hoàn:</strong> {{ $refundProgress->refunded_account_number }}</p>
                        @endif
                        <p><strong>Thời gian hoàn:</strong>
                            {{ \Carbon\Carbon::parse($refundProgress->completed_at)->format('d/m/Y H:i') }}
                        </p>
                    </div>
                </div>
            @endif
        @endif

    @endif

    <a href="{{ route('user.orders') }}" class="btn btn-default">
        ← Quay lại danh sách đơn hàng
    </a>
@endsection
@push('styles')
    <style>
        body {
            background-color: #f5f7fa;
        }

        h3,
        h4 {
            font-weight: 600;
            color: #2c3e50;
        }

        .panel {
            background: #ffffff;
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            overflow: hidden;
        }

        .panel-heading {
            background: linear-gradient(to right, #00b4db, #0083b0);
            color: white;
            padding: 20px;
            font-size: 18px;
            font-weight: 500;
        }

        .panel-heading strong {
            display: inline-block;
            min-width: 140px;
            color: #ffeaa7;
        }

        .panel-body {
            padding: 25px 30px;
        }

        .media {
            display: flex;
            align-items: flex-start;
            gap: 16px;
        }

        .media-object {
            width: 90px;
            height: 90px;
            border-radius: 10px;
            object-fit: cover;
            border: 1px solid #ddd;
        }

        .media-body h4 {
            margin: 0 0 6px;
            font-size: 18px;
            color: #333;
        }

        .media-body p {
            margin: 2px 0;
            font-size: 14px;
            color: #555;
        }

        .text-warning {
            color: #f39c12 !important;
            font-weight: 600;
        }

        .text-success {
            color: #27ae60 !important;
            font-weight: 600;
        }

        .text-danger {
            color: #c0392b !important;
            font-weight: 600;
        }

        .text-primary {
            color: #2980b9 !important;
            font-weight: 600;
        }

        .btn-default {
            background-color: #ecf0f1;
            color: #2c3e50;
            border: none;
            padding: 10px 18px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease-in-out;
        }

        .btn-default:hover {
            background-color: #dcdde1;
            color: #000;
        }

        .badge {
            font-size: 13px;
            padding: 6px 12px;
            border-radius: 6px;
        }

        .img-fluid.rounded.border {
            border: 1px solid #ccc;
            padding: 3px;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .img-fluid.rounded.border:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
        }

        .section-divider {
            border-top: 1px dashed #ccc;
            margin: 25px 0;
        }

        .order-section-title {
            margin-bottom: 12px;
            font-weight: 600;
            color: #444;
        }

        p strong {
            color: #2c3e50;
        }

        /* Tổng thể wrapper */
        .order-detail-wrapper {
            padding: 20px;
            background-color: #f8f9fa;
        }

        /* Tiêu đề chính */
        .order-detail-wrapper .section-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 1rem;
        }

        /* Card chính */
        .order-detail-wrapper .card {
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border-radius: 12px;
        }

        /* Header đơn hàng */
        .order-detail-wrapper .card-header {
            background: linear-gradient(135deg, #6c5ce7, #a29bfe);
            color: #fff;
            font-weight: 500;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }

        /* Trạng thái đơn hàng */
        .order-detail-wrapper .text-warning {
            font-weight: 600;
            font-size: 1.05rem;
        }

        .order-detail-wrapper .text-primary {
            font-weight: 600;
        }

        /* Box ảnh minh chứng */
        .order-detail-wrapper .refund-images img {
            border-radius: 8px;
            border: 1px solid #dee2e6;
            transition: transform 0.3s ease;
        }

        .order-detail-wrapper .refund-images img:hover {
            transform: scale(1.05);
        }

        /* Phần hoàn tiền */
        .order-detail-wrapper .refund-info {
            background-color: #fff3cd;
            border-left: 5px solid #ffc107;
            padding: 1rem;
            border-radius: 0.5rem;
        }

        /* Chi tiết người hoàn tiền */
        .order-detail-wrapper .refund-info p {
            margin-bottom: 0.3rem;
        }
    </style>
@endpush
