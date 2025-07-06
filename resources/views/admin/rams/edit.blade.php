@extends('admin.layouts.app')
@section('title', 'Sửa RAM')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-bold">Chỉnh sửa RAM</h4>
            <a href="{{ route('rams.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
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

            {{-- Form sửa --}}
            <form action="{{ route('rams.update', $rams->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="value" class="form-label fw-semibold">Tên RAM <span class="text-danger">*</span></label>
                    <input type="text" name="value" id="value" class="form-control" value="{{ old('value', $rams->value) }}" required>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Cập nhật RAM
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
