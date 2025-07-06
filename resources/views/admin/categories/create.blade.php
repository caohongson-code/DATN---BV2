@extends('admin.layouts.app')

@section('title', 'Thêm danh mục mới')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-bold">Thêm danh mục mới</h4>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Quay lại
            </a>
        </div>

        <div class="card-body">
            {{-- Hiển thị lỗi --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form --}}
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="category_name" class="form-label fw-semibold">Tên danh mục <span class="text-danger">*</span></label>
                    <input type="text" name="category_name" id="category_name" class="form-control"
                        value="{{ old('category_name') }}" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label fw-semibold">Mô tả</label>
                    <textarea name="description" id="description" rows="4" class="form-control">{{ old('description') }}</textarea>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="status" id="status" class="form-check-input"
                           {{ old('status', true) ? 'checked' : '' }}>
                    <label for="status" class="form-check-label">Hiển thị</label>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Lưu danh mục
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
