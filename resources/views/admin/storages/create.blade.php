@extends('admin.layouts.app')
@section('title', 'Thêm dung lượng')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-bold">Thêm dung lượng</h4>
            <a href="{{ route('storages.index') }}" class="btn btn-secondary btn-sm">
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

            {{-- Form --}}
            <form action="{{ route('storages.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="value" class="form-label fw-semibold">Tên dung lượng <span class="text-danger">*</span></label>
                    <input type="text" name="value" id="value" class="form-control" value="{{ old('value') }}" required>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-plus me-1"></i> Thêm mới
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
