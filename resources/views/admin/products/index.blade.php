@extends('admin.layouts.app')

@section('title', 'Danh sách sản phẩm')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0 rounded-3">

        {{-- Tiêu đề + nút chức năng --}}
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap"
             style="background: rgba(241, 243, 245, 0.8); backdrop-filter: blur(6px); padding: 12px 20px;">
            <h5 class="mb-0 fw-bold text-dark">
                <i class="bx bx-package me-2"></i> Danh sách sản phẩm
            </h5>
            <div>
                <a href="{{ route('products.create') }}" class="btn btn-create">
                    <i class="bx bx-plus me-1"></i> Tạo mới
                </a>

            </div>
        </div>

        <div class="card-body">

            {{-- Form tìm kiếm --}}
            <form method="GET" action="{{ route('products.index') }}" class="mb-4">
                <div class="row g-2 align-items-center" style="max-width: 500px;">
                    <div class="col-9">
                        <input type="text" name="search" class="form-control form-control-sm"
                               placeholder="Tìm theo tên hoặc danh mục" value="{{ request('search') }}">
                    </div>
                    <div class="col-3">
                        <button type="submit" class="btn btn-sm btn-primary w-100">
                            <i class="bx bx-search"></i> Tìm
                        </button>
                    </div>
                </div>
            </form>

            {{-- Thông báo --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>{{ session('success') }}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Bảng dữ liệu --}}
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Danh mục</th>
                            <th>Ảnh</th>
                            <th>Tên SP</th>
                            <th>Giá</th>
                            <th>Giá KM</th>
                            <th>Số lượng</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th>Lượt xem</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>{{ $product->category->category_name ?? 'Không có' }}</td>
                                <td>
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" width="80"
                                             class="img-thumbnail" alt="Ảnh sản phẩm">
                                    @else
                                        <span class="text-muted">Chưa có ảnh</span>
                                    @endif
                                </td>
                                <td>{{ $product->product_name }}</td>
                                <td>{{ number_format($product->price, 0, ',', '.') }} đ</td>
                                <td>
                                    @if($product->discount_price)
                                        {{ number_format($product->discount_price, 0, ',', '.') }} đ
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $product->quantity }}</td>
                                <td>
                                    @if($product->status)
                                        <span class="badge bg-success">Hiển thị</span>
                                    @else
                                        <span class="badge bg-secondary">Ẩn</span>
                                    @endif
                                </td>
                                <td>{{ $product->created_at->format('d/m/Y') }}</td>
                                <td>{{ $product->views ?? 0 }}</td>
                                <td>
                                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-info btn-sm me-1">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm me-1">
                                        <i class="fas fa-edit"></i> Sửa
                                    </a>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash-alt"></i> Xóa
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-muted">Chưa có sản phẩm nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Phân trang --}}
            <div class="mt-3 d-flex justify-content-center">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
<style>
    .btn-create {
        background: linear-gradient(90deg, #28a745, #20c997);
        color: #fff;
        font-weight: 500;
        border-radius: 8px;
        padding: 6px 14px;
        display: inline-flex;
        align-items: center;
        transition: all 0.3s ease;
        border: none;
    }
    .btn-create:hover {
        background: linear-gradient(90deg, #20c997, #28a745);
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(0,0,0,0.15);
        color: #fff;
        text-decoration: none;
    }
    .btn-create i {
        font-size: 14px;
    }
    </style>
@endsection
