@extends('admin.layouts.app')

@section('title', 'Chi tiết hoàn tiền')

@section('content')
<div class="container-fluid px-4 mt-3">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-white border-bottom">
            <h4 class="fw-bold mb-0">
                <i class="fas fa-money-bill-wave text-success me-2"></i> Chi tiết hoàn tiền
            </h4>
        </div>

        <div class="card-body">

            {{-- Thông tin đơn hoàn --}}
            <h5 class="fw-bold text-primary mb-3">📦 Thông tin đơn hoàn</h5>
            <table class="table table-bordered align-middle">
                <tr>
                    <th class="w-25 bg-light">Mã đơn hàng</th>
                    <td>#{{ $request->order->id }}</td>
                </tr>
                <tr>
                    <th class="bg-light">Khách hàng</th>
                    <td>
                        <strong>{{ $request->order->account->full_name }}</strong><br>
                        <small class="text-muted">{{ $request->order->account->email }}</small>
                    </td>
                </tr>
                <tr>
                    <th class="bg-light">Lý do hoàn</th>
                    <td>{{ $request->reason }}</td>
                </tr>

                {{-- Ảnh lý do khách gửi --}}
                <tr>
                    <th class="bg-light">Ảnh minh chứng lý do</th>
                    <td>
@php
// Lấy ảnh lý do khách gửi trực tiếp từ bảng return_requests
$customerReasonImages = json_decode($request->getOriginal('images') ?? '[]', true);
@endphp

@if (!empty($customerReasonImages))
    <div class="d-flex flex-wrap gap-2">
        @foreach ($customerReasonImages as $img)
            <div class="border rounded p-1" style="width:120px; height:120px; overflow:hidden;">
                <img src="{{ asset('storage/' . ltrim($img, '/')) }}" 
                     alt="Ảnh lý do khách gửi"
                     class="img-fluid w-100 h-100 object-fit-cover rounded">
            </div>
        @endforeach
    </div>
@else
    <em class="text-muted">Khách không gửi ảnh minh chứng</em>
@endif

                    </td>
                </tr>

                {{-- Ảnh khách gửi hàng --}}
                <tr>
                    <th class="bg-light">Ảnh khách gửi hàng</th>
                    <td>
                        @if (!empty($customerReturnImages))
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($customerReturnImages as $img)
                                    <div class="border rounded p-1" style="width:120px; height:120px; overflow:hidden;">
                                        <img src="{{ asset('storage/' . ltrim($img, '/')) }}" 
                                             alt="Ảnh khách gửi hàng"
                                             class="img-fluid w-100 h-100 object-fit-cover rounded">
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <em class="text-muted">Khách chưa gửi ảnh hàng</em>
                        @endif
                    </td>
                </tr>
            </table>

            {{-- Thông tin sản phẩm hoàn --}}
            <h5 class="fw-bold text-primary mt-4 mb-3">🛒 Thông tin sản phẩm hoàn</h5>
            @if ($request->order->orderDetails->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Ảnh</th>
                                <th>Tên sản phẩm</th>
                                <th>Biến thể</th>
                                <th>Số lượng</th>
                                <th>Đơn giá</th>
                                <th>Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($request->order->orderDetails as $item)
                                @php
                                    $variant = $item->productVariant;
                                    $product = $variant?->product;
                                    $variantName = [];
                                    if ($variant?->ram) $variantName[] = $variant->ram->value;
                                    if ($variant?->storage) $variantName[] = $variant->storage->value;
                                    if ($variant?->color) $variantName[] = $variant->color->value;
                                    $image = $product?->image ? asset('storage/' . $product->image) : asset('images/default.jpg');
                                @endphp
                                <tr>
                                    <td style="width: 80px;">
                                        <img src="{{ $image }}" alt="ảnh sản phẩm"
                                             class="img-thumbnail" style="width:70px; height:70px; object-fit:cover;">
                                    </td>
                                    <td>{{ $product->product_name ?? 'Sản phẩm' }}</td>
                                    <td>
                                        @if (!empty($variantName))
                                            {{ implode(' / ', $variantName) }}
                                        @else
                                            <em class="text-muted">Không có biến thể</em>
                                        @endif
                                    </td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ number_format($item->unit_price, 0, ',', '.') }} đ</td>
                                    <td>{{ number_format($item->total_price, 0, ',', '.') }} đ</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Mã giảm giá & tổng tiền --}}
                <table class="table table-bordered mt-3 w-100">
                    <tr>
                        <th class="bg-light w-25">Mã giảm giá</th>
                        <td>
                            @if ($request->order->coupon_code)
                                <span class="badge bg-info">{{ $request->order->coupon_code }}</span>
                                - {{ number_format($request->order->discount_amount, 0, ',', '.') }} đ
                            @else
                                <em class="text-muted">Không áp dụng</em>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="bg-light">Tổng tiền</th>
                        <td>
                            <strong class="text-danger fs-5">
                                {{ number_format($request->order->orderDetails->sum('total_price') - ($request->order->discount_amount ?? 0), 0, ',', '.') }} đ
                            </strong>
                        </td>
                    </tr>
                </table>
            @else
                <p class="text-muted">Không tìm thấy sản phẩm trong đơn hoàn.</p>
            @endif

            {{-- Thông tin hoàn tiền --}}
            <h5 class="fw-bold text-primary mt-4 mb-3">💰 Thông tin hoàn tiền</h5>
            @if ($refundProgress)
                <table class="table table-bordered align-middle">
                    <tr>
                        <th class="bg-light w-25">Trạng thái</th>
                        <td><span class="badge bg-success">Đã hoàn tiền</span></td>
                    </tr>
                    <tr>
                        <th class="bg-light">Ngày hoàn</th>
                        <td>{{ $refundProgress->completed_at ? \Carbon\Carbon::parse($refundProgress->completed_at)->format('d/m/Y H:i') : $refundProgress->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">Số tiền</th>
                        <td>{{ number_format($refundProgress->amount ?? ($request->order->orderDetails->sum('total_price') - ($request->order->discount_amount ?? 0)), 0, ',', '.') }} đ</td>
                    </tr>
                    <tr>
                        <th class="bg-light">Phương thức hoàn</th>
                        <td>{{ $refundProgress->refunded_bank_name ?? 'Không rõ' }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">Tài khoản nhận</th>
                        <td>{{ $refundProgress->refunded_account_number ?? 'Không có' }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">Người xử lý</th>
                        <td>{{ $refundProgress->refunded_by_name ?? 'Chưa xác định' }} ({{ $refundProgress->refunded_by_email ?? '---' }})</td>
                    </tr>
                    <tr>
                        <th class="bg-light">Ghi chú</th>
                        <td>{{ $refundProgress->note ?? 'Không có' }}</td>
                    </tr>
                    {{-- Ảnh admin --}}
                    <tr>
                        <th class="bg-light">Ảnh minh chứng (admin)</th>
                        <td>
                            @if (!empty($adminImages))
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach ($adminImages as $img)
                                        <div class="border rounded p-1" style="width:120px; height:120px; overflow:hidden;">
                                            <img src="{{ asset('storage/' . ltrim($img, '/')) }}" alt="ảnh hoàn tiền admin"
                                                 class="img-fluid w-100 h-100 object-fit-cover rounded">
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <em class="text-muted">Không có ảnh minh chứng admin</em>
                            @endif
                        </td>
                    </tr>
                </table>
            @else
                <p class="text-danger">⚠️ Không tìm thấy thông tin hoàn tiền cho yêu cầu này.</p>
            @endif

            <div class="mt-4">
                <a href="{{ route('admin.return_requests.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
                </a>
            </div>

        </div>
    </div>
</div>
@endsection
