@extends('admin.layouts.app')

@section('title', 'Hoàn tiền cho đơn hàng #' . $returnRequest->order_id)

@section('content')
    <div class="container py-4">
        <h3 class="mb-4">Hoàn tiền cho đơn hàng #{{ $returnRequest->order_id }}</h3>

        {{-- Hiển thị lỗi --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Thông tin người mua & người hoàn tiền --}}
        <div class="mb-4 p-3 border rounded bg-light">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="fw-bold mb-3">Thông tin người mua:</h5>
                    <p><strong>Họ tên:</strong> {{ $account->full_name }}</p>
                    <p><strong>Email:</strong> {{ $account->email }}</p>
                    <p><strong>SĐT:</strong> {{ $account->phone }}</p>
                    <p><strong>Ngân hàng:</strong> {{ $returnRequest->bank_name ?? 'Chưa cập nhật' }}</p>
                    <p><strong>Số tài khoản:</strong> {{ $returnRequest->bank_account ?? 'Chưa cập nhật' }}</p>
                </div>

                <div class="col-md-6">
                    <h5 class="fw-bold mb-3">Người thực hiện hoàn tiền:</h5>
                    <p><strong>Họ tên:</strong> {{ $refunder->full_name ?? 'N/A' }}</p>
                    <p><strong>Email:</strong> {{ $refunder->email ?? 'N/A' }}</p>
                    <p><strong>SĐT:</strong> {{ $refunder->phone ?? 'N/A' }}</p>
                    <form method="POST" action="{{ route('admin.orders.process_refund', $returnRequest->id) }}"
                        enctype="multipart/form-data">
                        @csrf

                </div>
            </div>
        </div>

        {{-- Thông tin yêu cầu trả hàng --}}
        <div class="mb-4 p-3 border rounded bg-light">
            <h5 class="fw-bold mb-3">Thông tin yêu cầu trả hàng:</h5>
            <p><strong>Lý do trả hàng:</strong> {{ $returnRequest->reason }}</p>
            <p><strong>Trạng thái hiện tại:</strong>
                @switch($returnRequest->status)
                    @case('pending')
                        <span class="badge bg-warning text-dark">Chờ duyệt</span>
                    @break

                    @case('approved')
                        <span class="badge bg-primary">Đã duyệt</span>
                    @break

                    @case('shipped_back')
                        <span class="badge bg-info text-dark">Khách đã gửi hàng</span>
                    @break

                    @case('refunded')
                        <span class="badge bg-success">Đã hoàn tiền</span>
                    @break

                    @default
                        <span class="badge bg-secondary">Không xác định</span>
                @endswitch
            </p>

            @php
                $images = json_decode($returnRequest->images, true) ?? [];
                $shippingImages = json_decode($returnRequest->shipping_images, true) ?? [];
            @endphp

            @if (count($images))
                <div class="mb-2">
                    <strong>Ảnh sản phẩm lỗi:</strong>
                    <div class="d-flex flex-wrap gap-2 mt-2">
                        @foreach ($images as $img)
                            <img src="{{ asset('storage/' . $img) }}" alt="return image" style="width: 100px;"
                                class="img-thumbnail">
                        @endforeach
                    </div>
                </div>
            @endif

            @if (count($shippingImages))
                <div class="mb-2">
                    <strong>Ảnh vận đơn trả hàng:</strong>
                    <div class="d-flex flex-wrap gap-2 mt-2">
                        @foreach ($shippingImages as $img)
                            <img src="{{ asset('storage/' . $img) }}" alt="shipping image" style="width: 100px;"
                                class="img-thumbnail">
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Thông tin hoàn tiền --}}
        <div class="mb-4 p-3 border rounded bg-light">
            <div class="mb-3">
                <label>Số tiền hoàn</label>
                <input type="number" name="refund_amount" value="{{ old('refund_amount', $order->total_amount - 30000) }}"
                    class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Ghi chú</label>
                <textarea name="note" class="form-control">{{ old('note') }}</textarea>
            </div>
            <div class="mb-3">
                <label for="transaction_images">Ảnh thông tin giao dịch (nếu có)</label>
                <input type="file" name="transaction_images[]" id="transaction_images" class="form-control" multiple
                    accept="image/*">
            </div>

            <button type="submit" class="btn btn-success">Xác nhận hoàn tiền</button>
            </form>
        </div>

        {{-- Nút quay lại --}}
        <div class="mt-4">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Quay lại
            </a>
        </div>
    </div>
@endsection
