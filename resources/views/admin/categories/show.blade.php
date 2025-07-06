@extends('admin.layouts.app')

@section('title', 'Chi tiết danh mục')

@section('content')
<div class="container-fluid px-5 py-4">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3 rounded-top">
            <h3 class="mb-0 fw-bold">
                <i class="fas fa-tags me-2"></i> Thông tin chi tiết danh mục
            </h3>
            <a href="{{ route('categories.index') }}" class="btn btn-light btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
            </a>
        </div>

        <div class="card-body bg-light fs-5 rounded-bottom">
            <div class="mb-4">
                <label class="form-label fw-semibold text-muted">Tên danh mục:</label>
                <div class="border p-3 bg-white rounded">{{ $category->category_name }}</div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold text-muted">Mô tả:</label>
                <div class="border p-3 bg-white rounded">
                    {{ $category->description ? $category->description : 'Không có mô tả' }}
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold text-muted">Ngày tạo:</label>
                <div class="border p-3 bg-white rounded">{{ $category->created_at->format('d/m/Y H:i') }}</div>
            </div>

            @isset($category->status)
            <div class="mb-2">
                <label class="form-label fw-semibold text-muted">Trạng thái:</label>
                <div>
                    <span class="badge fs-6 px-3 py-2 {{ $category->status ? 'bg-success' : 'bg-secondary' }}">
                        {{ $category->status ? 'Hiển thị' : 'Đã ẩn' }}
                    </span>
                </div>
            </div>
            @endisset
        </div>
    </div>
</div>
@endsection
