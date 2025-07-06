@extends('admin.layouts.app')

@section('title', 'Quản lý danh mục')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0 rounded-3">

        {{-- Header --}}
        <div class="card-header bg-white border-bottom">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h4 class="mb-0 fw-bold">Quản lý danh mục</h4>
                </div>

        </div>

        {{-- Body --}}
        <div class="card-body">

            {{-- Thông báo --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <div class="row mb-3 align-items-center">
                <div class="col-md-8">
                    <a href="{{ route('categories.create') }}" class="btn btn-success btn-sm">+ Tạo mới</a>
                    <button class="btn btn-warning btn-sm">Tải từ file</button>
                    <button class="btn btn-primary btn-sm">In dữ liệu</button>
                    <button class="btn btn-info btn-sm">Sao chép</button>
                    <button class="btn btn-success btn-sm">Xuất Excel</button>
                    <button class="btn btn-danger btn-sm">Xuất PDF</button>
                    <button class="btn btn-secondary btn-sm">Xóa tất cả</button>
                </div>
                <div class="col-md-4">
                    <form method="GET" action="{{ route('categories.index') }}">
                        <div class="input-group input-group-sm">
                            <input type="text" name="keyword" class="form-control" placeholder="Tìm theo mã hoặc tên" value="{{ request('keyword') }}">
                            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Bảng danh sách --}}
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Tên danh mục</th>
                            <th>Mô tả</th>
                            <th>Ngày tạo</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td class="text-start">{{ $category->category_name }}</td>
                            <td class="text-start">{{ $category->description ?? '-' }}</td>
                            <td>{{ $category->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('categories.show', $category->id) }}" class="btn btn-info btn-sm">Xem</a>
                                <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                                <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Xóa</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-muted">Không có danh mục nào.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3 d-flex justify-content-center">
                {{ $categories->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
