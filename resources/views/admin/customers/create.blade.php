@extends('admin.layouts.app')

@section('title', 'Tạo tài khoản khách hàng')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        {{-- Header với tiêu đề và nút quay lại --}}
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
            <h4 class="fw-bold mb-0">Tạo tài khoản khách hàng</h4>
            <a href="{{ route('customers.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
            </a>
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

            <form action="{{ route('customers.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    {{-- Họ và tên --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Họ và tên</label>
                        <input type="text" name="full_name" value="{{ old('full_name') }}" class="form-control">
                        @error('full_name') <p class="text-danger">{{ $message }}</p> @enderror
                    </div>

                    {{-- Ảnh đại diện --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Ảnh đại diện</label>
                        <input type="file" name="avatar" class="form-control">
                        @error('avatar') <p class="text-danger">{{ $message }}</p> @enderror
                    </div>

                    {{-- Ngày sinh --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Ngày sinh</label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" class="form-control">
                        @error('date_of_birth') <p class="text-danger">{{ $message }}</p> @enderror
                    </div>

                    {{-- Email --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control">
                        @error('email') <p class="text-danger">{{ $message }}</p> @enderror
                    </div>

                    {{-- Số điện thoại --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Số điện thoại</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" maxlength="10" class="form-control">
                        @error('phone') <p class="text-danger">{{ $message }}</p> @enderror
                    </div>

                    {{-- Giới tính --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold d-block">Giới tính</label>
                        <div class="d-flex gap-3">
                            <input type="radio" class="btn-check" name="gender" id="gender-male" value="1" {{ old('gender') == '1' ? 'checked' : '' }}>
                            <label class="gender-option" for="gender-male">👨 Nam</label>

                            <input type="radio" class="btn-check" name="gender" id="gender-female" value="0" {{ old('gender') == '0' ? 'checked' : '' }}>
                            <label class="gender-option" for="gender-female">👩 Nữ</label>
                        </div>
                        @error('gender') <p class="text-danger mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Địa chỉ --}}
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-semibold">Địa chỉ</label>
                        <textarea name="address" class="form-control">{{ old('address') }}</textarea>
                        @error('address') <p class="text-danger">{{ $message }}</p> @enderror
                    </div>

                    {{-- Mật khẩu --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Mật khẩu</label>
                        <input type="password" name="password" class="form-control">
                        @error('password') <p class="text-danger">{{ $message }}</p> @enderror
                    </div>

                    {{-- Nhập lại mật khẩu --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Nhập lại mật khẩu</label>
                        <input type="password" name="password_confirmation" class="form-control">
                        @error('password_confirmation') <p class="text-danger">{{ $message }}</p> @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-2">
                    <i class="fas fa-user-plus me-1"></i> Tạo tài khoản
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

{{-- CSS custom cho gender-option --}}
<style>
    .gender-option {
        display: inline-block;
        padding: 10px 18px;
        border-radius: 50px;
        border: 2px solid #ccc;
        cursor: pointer;
        transition: all 0.3s;
        font-weight: 500;
        user-select: none;
    }

    .btn-check:checked + .gender-option {
        background-color: #0d6efd;
        color: #fff;
        border-color: #0d6efd;
        box-shadow: 0 0 8px rgba(13, 110, 253, 0.5);
    }

    .gender-option:hover {
        border-color: #0d6efd;
    }
</style>
