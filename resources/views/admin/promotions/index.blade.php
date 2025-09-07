@extends('admin.layouts.app')
@section('title', 'Quản lý khuyến mãi')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0 rounded-3">

        {{-- Header --}}
        <div class="card-header bg-white border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fw-bold">Quản lý khuyến mãi</h4>
                <a href="{{ route('promotions.create') }}"
                   class="btn btn-sm text-white"
                   style="background: linear-gradient(135deg, #28a745, #20c997); border: none; font-weight: 500;">
                    <i class='bx bx-gift'></i> Thêm khuyến mãi
                </a>
            </div>
        </div>

        <div class="card-body">
            {{-- Tìm kiếm mã khuyến mãi --}}
            <form method="GET" action="{{ route('promotions.index') }}" class="mb-3">
                <div class="row g-2 align-items-center">
                    <div class="col-auto">
                        <label for="code" class="col-form-label">Tìm mã:</label>
                    </div>
                    <div class="col-auto">
                        <input type="text" id="code" name="code" value="{{ request('code') }}" class="form-control" placeholder="Nhập mã giảm giá">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">
                            <i class='bx bx-search'></i> Tìm kiếm
                        </button>
                        @if(request('code'))
                            <a href="{{ route('promotions.index') }}" class="btn btn-outline-secondary">
                                Làm mới
                            </a>
                        @endif
                    </div>
                </div>
            </form>
            {{-- Thông báo --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            {{-- Bảng danh sách --}}
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-primary">
                        <tr>
                            <th>#</th>
                            <th>Mã KM</th>
                            <th>Loại giảm</th>
                            <th>Giá trị</th>
                            <th>Thời gian</th>
                            <th>Giới hạn</th>
                            <th>Áp dụng theo giá đơn</th> {{-- ✅ Cột mới --}}
                            <th>Trạng thái</th>
                            <th>Sản phẩm áp dụng</th>
                            <th>Danh mục áp dụng</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($promotions as $promotion)
                            <tr>
                                <td>{{ $loop->iteration + ($promotions->currentPage() - 1) * $promotions->perPage() }}</td>
                                <td><strong>{{ $promotion->code }}</strong></td>
                                <td>{{ $promotion->discount_type == 'percentage' ? 'Phần trăm' : 'Cố định' }}</td>
                                <td>
                                    {{ number_format($promotion->discount_value, 0, ',', '.') }}
                                    {{ $promotion->discount_type == 'percentage' ? '%' : 'VNĐ' }}
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($promotion->start_date)->format('d/m/Y H:i') }} <br>
                                    → {{ \Carbon\Carbon::parse($promotion->end_date)->format('d/m/Y H:i') }}
                                </td>
                                <td>{{ $promotion->usage_limit ?? 'Không giới hạn' }}</td>

                                {{-- ✅ Giá trị đơn hàng áp dụng --}}
                                <td>
                                    @if($promotion->min_order_amount || $promotion->max_order_amount)
                                        @if($promotion->min_order_amount)
                                            ≥ {{ number_format($promotion->min_order_amount, 0, ',', '.') }}đ
                                        @endif
                                        @if($promotion->min_order_amount && $promotion->max_order_amount)
                                            <br>
                                        @endif
                                        @if($promotion->max_order_amount)
                                            ≤ {{ number_format($promotion->max_order_amount, 0, ',', '.') }}đ
                                        @endif
                                    @else
                                        <span class="text-muted fst-italic">Không giới hạn</span>
                                    @endif
                                </td>

                                {{-- Trạng thái --}}
                                <td>
                                    <span class="badge bg-{{ $promotion->is_active ? 'success' : 'secondary' }}">
                                        {{ $promotion->is_active ? 'Đang áp dụng' : 'Đã tắt' }}
                                    </span>
                                </td>

                                {{-- ✅ Sản phẩm áp dụng --}}
                                <td>
                                    @if($promotion->products->isNotEmpty())
                                        <div class="d-flex flex-column gap-1">
                                            @foreach ($promotion->products as $product)
                                                <span class="badge bg-info text-dark">{{ $product->product_name }}</span>
                                            @endforeach
                                        </div>
                                    @elseif($promotion->categories->isNotEmpty())
                                        <span class="text-muted fst-italic">Theo danh mục</span>
                                    @else
                                        <span class="text-muted fst-italic">Tất cả sản phẩm</span>
                                    @endif
                                </td>

                                {{-- ✅ Danh mục áp dụng --}}
                                <td>
                                    @if($promotion->categories->isEmpty())
                                        <span class="text-muted fst-italic">Không chọn danh mục</span>
                                    @else
                                        @foreach ($promotion->categories as $category)
                                            <span class="badge bg-warning text-dark mb-1">{{ $category->category_name }}</span>
                                        @endforeach
                                    @endif
                                </td>

                                {{-- ✅ Hành động --}}
                                <td>
                                    <a href="{{ route('promotions.edit', $promotion) }}"
                                       class="btn btn-warning btn-sm mb-1">
                                       <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('promotions.destroy', $promotion) }}" method="POST" style="display:inline">
                                        @csrf @method('DELETE')
                                        <button onclick="return confirm('Xóa khuyến mãi này?')"
                                                class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-muted">Không có khuyến mãi nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Phân trang --}}
            <div class="mt-3 d-flex justify-content-center">
                {{ $promotions->appends(request()->only('code'))->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
