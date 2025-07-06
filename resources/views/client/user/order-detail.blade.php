@extends('client.user.dashboard')

@section('dashboard-content')
    <h3 class="mb-4">📦 Chi tiết đơn hàng </h3>

    <div class="panel panel-primary">
        <div class="panel-heading">
            <strong>Trạng thái đơn hàng:</strong> 
            <span class="text-warning">{{ $order->orderStatus->status_name ?? 'Không rõ' }}</span><br>

            <strong>Ngày đặt hàng:</strong> {{ $order->created_at->format('d/m/Y H:i') }}

            @if ($order->order_status_id == 7)
                <br><strong>Ngày huỷ:</strong> 
                <span class="text-danger">{{ $order->updated_at->format('d/m/Y H:i') }}</span>
            @endif
        </div>

        <div class="panel-body">
            {{-- Danh sách sản phẩm --}}
            <h4 class="mb-3">🛒 Sản phẩm trong đơn</h4>
            @foreach ($order->orderDetails as $item)
                @php
                    $variant = $item->productVariant;
                    $product = $variant?->product;
                    $image = $product?->image ? asset('storage/' . $product->image) : asset('images/default.jpg');
                    $variantPrice = $variant?->price ?? 0;
                @endphp
                <div class="media" style="border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 15px;">
                    <div class="media-left">
                        <img class="media-object img-thumbnail" src="{{ $image }}" alt="Ảnh sản phẩm"
                            style="width: 90px; height: 90px; object-fit: cover;">
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading">{{ $product->product_name ?? 'Không rõ sản phẩm' }}</h4>
                        <p>Số lượng: <strong>{{ $item->quantity }}</strong></p>
                        <p>Giá: <strong>{{ number_format($variantPrice, 0, ',', '.') }}₫</strong></p>
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
                <p><strong>Khu vực giao hàng:</strong> {{ $order->shippingZone->name }}</p>
            @endif

            <hr>

            {{-- Thông tin thanh toán --}}
            <h4 class="mb-3">💳 Thông tin thanh toán</h4>
            <p><strong>Phương thức thanh toán:</strong> {{ $order->paymentMethod->method_name ?? 'Không rõ' }}</p>

            @php
                $methodCode = $order->paymentMethod->code ?? '';
                $orderStatusId = $order->order_status_id;
                $isPaid = false;

                if ($methodCode === 'momo') {
                    $isPaid = true;
                } elseif ($methodCode === 'cod' && $orderStatusId == 5) {
                    $isPaid = true;
                }
            @endphp
            <p><strong>Trạng thái thanh toán:</strong>
                <span class="{{ $isPaid ? 'text-success' : 'text-warning' }}">
                    {{ $isPaid ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                </span>
            </p>

            @if ($order->voucher)
                <p><strong>Mã giảm giá:</strong> {{ $order->voucher->code ?? $order->voucher_code }}</p>
            @endif
            <p><strong>Phí vận chuyển:</strong> {{ number_format($order->shipping_fee, 0, ',', '.') }}₫</p>
            <p><strong>Tổng tiền:</strong> 
                <span class="text-danger">{{ number_format($order->total_amount, 0, ',', '.') }}₫</span>
            </p>

            @if ($order->note)
                <hr>
                <h4 class="mb-3">📝 Ghi chú đơn hàng</h4>
                <p>{{ $order->note }}</p>
            @endif
        </div>
    </div>

    <a href="{{ route('user.orders') }}" class="btn btn-default">
        ← Quay lại danh sách đơn hàng
    </a>
@endsection
