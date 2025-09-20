@extends('admin.layouts.app')

@section('title', 'Chi tiết đơn hàng')

@section('content')
    <div class="container-fluid px-4">
        <div class="card shadow-sm border-0 rounded-3 mb-4">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                <h4 class="fw-bold mb-0"><i class="fas fa-file-invoice me-2"></i> Chi tiết đơn hàng #{{ $order->id }}</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Đơn hàng</a></li>
                    <li class="breadcrumb-item active">Chi tiết</li>
                </ol>
            </div>

            <div class="card-body">
                {{-- Thông báo --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="row g-4">

                    {{-- Thông tin người nhận --}}
                    <div class="col-12 col-md-6">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0">Thông tin người nhận</h6>
                            </div>
                            <div class="card-body">
                                <p><strong>Họ tên:</strong> {{ $order->recipient_name }}</p>
                                <p><strong>Email:</strong> {{ $order->recipient_email ?? 'Không có' }}</p>
                                <p><strong>Điện thoại:</strong> {{ $order->recipient_phone }}</p>
                                <p><strong>Địa chỉ:</strong> {{ $order->recipient_address }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Thông tin đơn hàng --}}
                    <div class="col-12 col-md-6">
                        <div class="card shadow-sm">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0">Thông tin đơn hàng</h6>
                            </div>
                            <div class="card-body">
                                <p><strong>Ngày đặt:</strong> {{ $order->order_date?->format('d/m/Y H:i') }}</p>
                                <p><strong>Trạng thái:</strong>
                                    <span>
                                        {{ $order->orderStatus->status_name }}
                                    </span>
                                </p>
                                <p><strong>Phương thức thanh toán:</strong>
                                    {{ $order->paymentMethod->method_name ?? 'Không có' }}</p>
                                <p><strong>Trạng thái thanh toán:</strong> {{ $order->paymentStatus->name ?? 'Không có' }}
                                </p>
                                @if ($order->voucher_code)
                                    <p><strong>Mã giảm giá:</strong> {{ $order->voucher_code }}</p>
                                @endif
                                <p><strong>Ghi chú:</strong> {{ $order->note ?? 'Không có' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Cập nhật trạng thái --}}
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-header bg-warning">
                                <h6 class="mb-0">Cập nhật trạng thái đơn</h6>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="row align-items-end">
                                        <div class="col-md-4">
                                            <label for="order_status_id" class="form-label">Trạng thái đơn</label>
                                            <select name="order_status_id"
                                                class="form-select @error('order_status_id') is-invalid @endif" id="order_status_id">
                                            @foreach ($statuses as $status)
                                                @php
                                                    $disabled = '';
                                                    // Không cho phép quay lại "Chờ xác nhận" (ID 1) nếu đã qua ID 1
                                                    if ($order->order_status_id > 1 && $status->id == 1) {
                                                        $disabled = 'disabled';
                                                    }
                                                    // Không cho phép quay lại "Đang xác nhận" (ID 2) nếu đã qua ID 2
                                                    if ($order->order_status_id > 2 && $status->id == 2) {
                                                        $disabled = 'disabled';
                                                    }
                                                    // Không cho phép quay lại "Đang giao" (ID 3) nếu đã qua ID 3
                                                    if ($order->order_status_id > 3 && $status->id == 3) {
                                                        $disabled = 'disabled';
                                                    }
                                                    // Không cho phép hủy nếu đã "Đang giao" (ID 3) trở lên
                                                    // Không cho hủy nếu đã từ "Đang giao" trở lên
if ($order->order_status_id >= 3 && $status->id == 7) {
    $disabled = 'disabled';
}

                                                    $selected = $order->order_status_id == $status->id ? 'selected' : '';
                                                @endphp
                                                <option value="{{ $status->id }}" {{ $selected }} {{ $disabled }}>
                                                    {{ $status->status_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('order_status_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @endif
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-primary w-100 mt-3 mt-md-0">Cập nhật</button>
                                    </div>
                                </div>
                            </form>
                            @if ($order->order_status_id > 1)
                                <div class="mt-2">
                                    <small class="text-danger">* Lưu ý: Không thể quay lại trạng thái thấp hơn sau khi đã xác nhận.</small>
                                </div>
                            @endif
                            @if ($order->order_status_id >= 3)
                                <div class="mt-2">
                                    <small class="text-danger">* Lưu ý: Đơn hàng đang trong trạng thái "Đang giao" hoặc cao hơn không thể chuyển thành "Đã hủy".</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Danh sách sản phẩm --}}
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-dark text-white">
                            <h6 class="mb-0">Sản phẩm đã đặt</h6>
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-striped table-bordered align-middle text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th>Ảnh</th>
                                        <th>Tên SP</th>
                                        <th>Phân loại</th>
                                        <th>Giá</th>
                                        <th>Số lượng</th>
                                        <th>Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalQty = 0;
                                        $total = 0;
                                    @endphp
                                 @foreach ($order->orderDetails as $d)
    @php
        $v = $d->productVariant;
        $product = $v->product;
        $qty = $d->quantity;

        // ✅ Ưu tiên lấy giá khuyến mãi nếu có
        $price = $v->discount_price && $v->discount_price > 0 ? $v->discount_price : $v->price;

        $subtotal = $price * $qty;
        $totalQty += $qty;
        $total += $subtotal;
        $image = $product?->image ? asset('storage/' . $product->image) : asset('images/default.jpg');
    @endphp
    <tr>
        <td><img src="{{ $image }}" width="60" class="rounded"></td>
        <td class="text-start">{{ $product->product_name }}</td>
        <td>{{ $v->ram->value ?? '-' }} / {{ $v->storage->value ?? '-' }} / {{ $v->color->value ?? '-' }}</td>
        <td>
            {{-- Hiển thị giá: nếu có khuyến mãi thì gạch giá gốc --}}
            @if($v->discount_price && $v->discount_price > 0)
                <span class="text-muted text-decoration-line-through me-1">{{ number_format($v->price, 0, ',', '.') }}đ</span>
                <span class="text-danger fw-bold">{{ number_format($v->discount_price, 0, ',', '.') }}đ</span>
            @else
                {{ number_format($v->price, 0, ',', '.') }}đ
            @endif
        </td>
        <td>{{ $qty }}</td>
        <td class="text-end">{{ number_format($subtotal, 0, ',', '.') }}đ</td>
    </tr>
@endforeach

                                </tbody>
                               <tfoot>
    <tr>
        <td colspan="4" class="text-end"><strong>Tổng SL:</strong></td>
        <td><strong>{{ $totalQty }}</strong></td>
        <td></td>
    </tr>
    <tr>
        <td colspan="5" class="text-end"><strong>Tổng sản phẩm:</strong></td>
        <td class="text-end"><strong>{{ number_format($total, 0, ',', '.') }}đ</strong></td>
    </tr>

    {{-- Khuyến mãi (nếu có) --}}
    @php
        $discountAmount = 0;
        if ($order->voucher_discount ?? false) {
            $discountAmount = $order->voucher_discount;
        } elseif ($order->promotion) {
            // Nếu có quan hệ promotion thì lấy giá trị từ promotion
            if ($order->promotion->discount_type === 'percentage') {
                $discountAmount = $total * ($order->promotion->discount_value / 100);
            } else {
                $discountAmount = $order->promotion->discount_value;
            }
        }
    @endphp

    @if ($discountAmount > 0)
        <tr>
            <td colspan="5" class="text-end text-success"><strong>Giảm giá:</strong></td>
            <td class="text-end text-success"><strong>-{{ number_format($discountAmount, 0, ',', '.') }}đ</strong></td>
        </tr>
    @endif

    <tr>
        <td colspan="5"
                                                class="text-end"><strong>Phí ship:</strong></td>
                                                <td class="text-end">
                                                    <strong>{{ number_format($order->shipping_fee ?? 30000, 0, ',', '.') }}đ</strong>
                                                </td>
                                                </tr>
                                                <tr class="table-primary">
                                                    <td colspan="5" class="text-end"><strong>Tổng đơn hàng:</strong></td>
                                                    <td class="text-end">
                                                        <strong>{{ number_format($total - $discountAmount + ($order->shipping_fee ?? 30000), 0, ',', '.') }}đ</strong>
                                                    </td>
                                                </tr>
                                                </tfoot>

                                                </table>
                                        </div>
                                    </div>
                            </div>

                        </div> <!-- row -->
                    </div> <!-- card-body -->
                </div> <!-- card -->
            </div>
        @endsection
