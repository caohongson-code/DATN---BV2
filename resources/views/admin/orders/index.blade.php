@extends('admin.layouts.app')

@section('title', 'Quản lý đơn hàng')

@section('content')
<div class="container-fluid px-4">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
            <h4 class="fw-bold mb-0"><i class="fas fa-shopping-cart me-2 text-primary"></i> Quản lý đơn hàng</h4>
        </div>

        <div class="card-body">
            {{-- Thông báo --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- Form tìm kiếm --}}
            <form action="{{ route('admin.orders.index') }}" method="GET" class="mb-4">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Tìm kiếm</label>
                        <input type="text" name="search" class="form-control"
                            placeholder="Tên, email khách hàng..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Trạng thái đơn</label>
                        <select name="order_status_id" class="form-select">
                            <option value="">-- Tất cả trạng thái --</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status->id }}" {{ request('order_status_id') == $status->id ? 'selected' : '' }}>
                                    {{ $status->status_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Ngày đặt</label>
                        <input type="date" name="order_date" class="form-control" value="{{ request('order_date') }}">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary w-100">
                            <i class="fas fa-search me-1"></i> Tìm kiếm
                        </button>
                    </div>
                </div>
            </form>

            {{-- Tổng đơn và tổng tiền --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="badge bg-success">Tổng đơn: {{ $orders->count() }}</span>
                <span class="badge bg-info">Tổng tiền: {{ number_format($orders->sum('total_amount'), 0, ',', '.') }}đ</span>
            </div>

            {{-- Bảng danh sách --}}
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Mã đơn</th>
                            <th>Khách hàng</th>
                            <th>Thanh toán</th>
                            <th>Trạng thái đơn</th>
                            <th>Trạng thái giỏ</th>
                            <th>Ngày đặt</th>
                            <th>Tổng tiền</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td class="text-start">
                                    <strong>{{ $order->account->full_name ?? 'Không rõ' }}</strong><br>
                                    <small class="text-muted">{{ $order->account->email ?? '---' }}</small>
                                </td>
                                <td>{{ $order->paymentMethod->method_name ?? 'Chưa chọn' }}</td>
                                <td>
                                    <span class="badge
                                        @switch($order->orderStatus->status_name)
                                            @case('Chưa xác nhận') bg-warning @break
                                            @case('Đã xác nhận') bg-info @break
                                            @case('Đã thanh toán') bg-primary @break
                                            @case('Đang giao') bg-success @break
                                            @case('Thành công') bg-success @break
                                            @case('Hủy đơn') bg-danger @break
                                            @default bg-secondary
                                        @endswitch
                                    ">
                                        {{ $order->orderStatus->status_name }}
                                    </span>
                                </td>
                                <td>
                                    @if ($order->cart && $order->cart->statusModel)
                                        <span class="badge
                                            @switch($order->cart->statusModel->name)
                                                @case('active') bg-success @break
                                                @case('ordered') bg-primary @break
                                                @case('cancelled') bg-danger @break
                                                @default bg-secondary
                                            @endswitch
                                        ">
                                            {{ $order->cart->statusModel->display_name ?? $order->cart->statusModel->name }}
                                        </span>
                                    @else
                                        <span class="text-muted">Không có</span>
                                    @endif
                                </td>
                                <td>{{ $order->order_date ? $order->order_date->format('d/m/Y H:i') : '---' }}</td>
                                <td class="text-end">{{ number_format($order->total_amount, 0, ',', '.') }}đ</td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order->id) }}"
                                       class="btn btn-sm btn-info" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    @if($order->order_status_id == 1)
                                        <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="order_status_id" value="2">
                                            <button type="submit" class="btn btn-sm btn-success"
                                                    onclick="return confirm('Xác nhận đơn hàng này?')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-muted text-center">Không có đơn hàng nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-center mt-4">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
