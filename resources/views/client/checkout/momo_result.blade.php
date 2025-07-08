@extends('client.layouts.app')

@section('title', 'Kết quả thanh toán MoMo')

@section('content')
@php
    $resultMessages = [
        0 => ['message' => '✅ Giao dịch thành công.', 'type' => 'success'],
        9000 => ['message' => '✅ Thanh toán thành công.', 'type' => 'success'],
        1000 => ['message' => '⏳ Đang chờ người dùng xác nhận.', 'type' => 'info'],
        1001 => ['message' => '❌ Không đủ tiền trong tài khoản.', 'type' => 'danger'],
        1003 => ['message' => '❌ Giao dịch đã bị huỷ.', 'type' => 'danger'],
        1005 => ['message' => '⚠️ QR Code đã hết hạn.', 'type' => 'warning'],
        99 => ['message' => '❗ Lỗi không xác định.', 'type' => 'warning'],
    ];

    $info = $resultMessages[$result_code] ?? ['message' => '❗ Mã lỗi không xác định.', 'type' => 'warning'];
@endphp

<div class="container py-5">
    {{-- Thông báo kết quả giao dịch --}}
    <div class="alert alert-{{ $info['type'] }}">
        <h4 class="mb-0">{{ $info['message'] }}</h4>
        <p class="mt-2">Mã giao dịch: <strong>{{ $momo_trans->order_id }}</strong></p>
        <p>Trạng thái mã: <code>{{ $result_code }}</code></p>
    </div>

    {{-- Thông tin đơn hàng --}}
    @if($order)
    <div class="card mt-4 shadow-sm">
        <div class="card-header bg-primary text-white">📦 Thông tin đơn hàng</div>
        <div class="card-body">
            <p><strong>👤 Người nhận:</strong> {{ $order->recipient_name }}</p>
            <p><strong>📞 Số điện thoại:</strong> {{ $order->recipient_phone }}</p>
            <p><strong>📧 Email:</strong> {{ $order->recipient_email }}</p>
            <p><strong>📍 Địa chỉ:</strong> {{ $order->recipient_address }}</p>
            <p><strong>💰 Tổng tiền:</strong> {{ number_format($order->total_amount, 0, ',', '.') }} VNĐ</p>
            <p><strong>💳 Phương thức thanh toán:</strong>
                {{ strtoupper($order->payment_method_id == 3 ? 'MoMo' : 'COD') }}
            </p>
            <p><strong>🧾 Trạng thái thanh toán:</strong>
                @if($order->payment_status_id == 2)
                    <span class="badge bg-success">Đã thanh toán</span>
                @else
                    <span class="badge bg-warning text-dark">Chưa thanh toán</span>
                @endif
            </p>

            <a href="{{ route('user.orders.detail', ['id' => $order->id]) }}" class="btn btn-outline-secondary mt-3">
                📄 Xem chi tiết đơn hàng
            </a>
        </div>
    </div>
    @endif

    <a href="{{ route('home') }}" class="btn btn-primary mt-4">🔙 Quay về trang chủ</a>
</div>
@endsection
