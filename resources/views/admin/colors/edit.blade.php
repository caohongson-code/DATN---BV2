@extends('admin.layouts.app')
@section('title', 'Sửa màu')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-white border-bottom">
            <h4 class="mb-0 fw-bold">Sửa màu</h4>
        </div>

        <div class="card-body">
            {{-- Hiển thị thông báo lỗi --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('colors.update', $color->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="value" class="form-label">Tên màu</label>
                    <input type="text" name="value" class="form-control" value="{{ $color->value }}" required>
                </div>

                <div class="mb-3">
                    <label for="code" class="form-label">Mã màu (hex)</label>
                    <input type="color" name="code" class="form-control form-control-color" value="{{ $color->code ?? '#000000' }}">
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Cập nhật
                </button>
                <a href="{{ route('colors.index') }}" class="btn btn-secondary ms-2">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                </a>
            </form>
        </div>
    </div>
</div>
@endsection
