@extends('client.layouts.app')

@section('title', 'Kết quả thanh toán MoMo')

@section('content')
    @php
        $resultMessages = [
            0 => ['message' => '✅ Giao dịch thành công.', 'type' => 'success'],
            9000 => ['message' => '✅ Thanh toán thành công.', 'type' => 'success'],
            1000 => ['message' => '⏳ Đang chờ người dùng xác nhận.', 'type' => 'info'],
            1001 => ['message' => '❌ Không đủ tiền trong tài khoản.', 'type' => 'danger'],
            1002 => ['message' => '❌ Giao dịch đã bị huỷ.', 'type' => 'danger'],
            1006 => ['message' => '❌ Giao dịch đã bị huỷ bởi người dùng.', 'type' => 'danger'],
            1003 => ['message' => '⏰ Giao dịch quá hạn.', 'type' => 'warning'],
            1005 => ['message' => '⚠️ QR Code đã hết hạn.', 'type' => 'warning'],
            1004 => ['message' => '⚠️ Người dùng chưa liên kết tài khoản ngân hàng.', 'type' => 'warning'],
            5 => ['message' => '🔐 Lỗi xác thực chữ ký.', 'type' => 'danger'],
            6 => ['message' => '❌ Sai partnerCode.', 'type' => 'danger'],
            7 => ['message' => '⚠️ Không tìm thấy giao dịch.', 'type' => 'warning'],
            8 => ['message' => '⚠️ Đơn hàng đã hết hạn.', 'type' => 'warning'],
            9 => ['message' => '⚠️ Mã đơn hàng đã được sử dụng.', 'type' => 'warning'],
            10 => ['message' => '🚫 MoMo từ chối giao dịch.', 'type' => 'danger'],
            11 => ['message' => '⚠️ Phương thức thanh toán không khả dụng.', 'type' => 'warning'],
            13 => ['message' => '❌ Ví MoMo không đủ tiền.', 'type' => 'danger'],
            21 => ['message' => '🔁 Giao dịch đang xử lý.', 'type' => 'info'],
            49 => ['message' => '❗ Lỗi hệ thống MoMo.', 'type' => 'warning'],
            51 => ['message' => '⚠️ Đơn vị tiền tệ không được hỗ trợ.', 'type' => 'warning'],
            94 => ['message' => '🔁 Giao dịch đang được xử lý bởi hệ thống.', 'type' => 'info'],
            97 => ['message' => '🔐 Sai checksum hoặc chữ ký.', 'type' => 'danger'],
            98 => ['message' => '❌ Giao dịch thất bại.', 'type' => 'danger'],
            99 => ['message' => '❗ Lỗi không xác định.', 'type' => 'warning'],
            7002 => ['message' => '🚫 Merchant chưa được cấp quyền thanh toán.', 'type' => 'danger'],
        ];

        $info = $resultMessages[$result_code] ?? ['message' => '❗ Mã lỗi không xác định.', 'type' => 'warning'];
    @endphp

    <div class="container py-5">
        {{-- Thông báo kết quả giao dịch --}}
        {{-- <pre>{{ $result_code }}</pre>
<pre>{{ print_r($info, true) }}</pre> --}}

        <div class="alert alert-{{ $info['type'] }}">
            <h4 class="mb-0">{{ $info['message'] }}</h4>
        </div>

        {{-- Thông tin đơn hàng --}}
        @if ($order)
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
                        @switch($order->payment_status_id)
                            @case(1) <span class="badge bg-warning text-dark">Chờ thanh toán</span> @break
                            @case(2) <span class="badge bg-success">Đã thanh toán</span> @break
                            @case(3) <span class="badge bg-danger">Thanh toán thất bại</span> @break
                            @case(4) <span class="badge bg-secondary">Hoàn tiền</span> @break
                            @default <span class="badge bg-dark">Không rõ trạng thái</span>
                        @endswitch
                    </p>

                    <a href="{{ route('user.orders.detail', ['id' => $order->id]) }}" class="btn btn-outline-secondary mt-3">
                        📄 Xem chi tiết đơn hàng
                    </a>
                </div>
            </div>
        @endif

        {{-- Nút retry chỉ khi thất bại --}}
    @if ($order && $order->payment_status_id != 2)
    <div>
        <form id="retryForm" action="{{ route('client.momo.retry', $order->id) }}" method="post">
            @csrf
            <button type="submit" class="btn btn-primary">Quay lại thanh toán</button>
        </form>
    </div>
@endif


        <a href="{{ route('home') }}" class="btn btn-primary mt-4">🔙 Quay về trang chủ</a>
    </div>
@endsection
