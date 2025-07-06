@extends('admin.layouts.app')
@section('title', 'Thêm màu')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white border-bottom">
                    <h4 class="mb-0 fw-bold">Thêm màu mới</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('colors.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="value" class="form-label">Tên màu <span class="text-danger">*</span></label>
                            <input type="text" name="value" class="form-control" placeholder="VD: Đỏ, Xanh dương" required>
                        </div>

                        <div class="mb-3">
                            <label for="code" class="form-label">Mã màu (HEX)</label>
                            <input type="color" name="code" class="form-control form-control-color" value="#000000">
                        </div>

                        <div class="text-end">
                            <a href="{{ route('colors.index') }}" class="btn btn-outline-secondary">Huỷ</a>
                            <button type="submit" class="btn btn-success">Thêm màu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
