@extends('admin.layouts.app')

@section('title', 'Cập nhật tài khoản')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0 rounded-3">
        {{-- Header --}}
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-bold">Cập nhật tài khoản: {{ $customers->full_name }}</h4>
            <a href="{{ route('customers.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
            </a>
        </div>

        <div class="card-body">
            {{-- Thông báo lỗi session --}}
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            {{-- Thông báo lỗi validate --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('customers.update', $customers->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    {{-- Họ tên --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Họ tên</label>
                        <input type="text" name="full_name" value="{{ old('full_name', $customers->full_name) }}"
                               class="form-control @error('full_name') is-invalid @enderror">
                        @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Ngày sinh --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Ngày sinh</label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $customers->date_of_birth) }}"
                               class="form-control @error('date_of_birth') is-invalid @enderror">
                        @error('date_of_birth')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Email --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" value="{{ old('email', $customers->email) }}"
                               class="form-control @error('email') is-invalid @enderror">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Điện thoại --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Số điện thoại</label>
                        <input type="text" name="phone" value="{{ old('phone', $customers->phone) }}" maxlength="10"
                               class="form-control @error('phone') is-invalid @enderror">
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Giới tính --}}

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold d-block">Giới tính</label>
                        <div class="d-flex gap-3 flex-wrap">
                            <input type="radio" class="btn-check" name="gender" id="gender-male" value="1"
                                {{ old('gender', $customers->gender) == '1' ? 'checked' : '' }}>
                            <label class="gender-option" for="gender-male">
                                👨 Nam
                            </label>

                            <input type="radio" class="btn-check" name="gender" id="gender-female" value="0"
                                {{ old('gender', $customers->gender) == '0' ? 'checked' : '' }}>
                            <label class="gender-option" for="gender-female">
                                👩 Nữ
                            </label>
                        </div>
                        @error('gender') <p class="text-danger mt-1">{{ $message }}</p> @enderror
                    </div>


                    {{-- Avatar --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Ảnh đại diện</label>
                        <input type="file" name="avatar" class="form-control @error('avatar') is-invalid @enderror">
                        @error('avatar')<div class="invalid-feedback">{{ $message }}</div>@enderror

                        <div class="mt-2">
                            <img src="{{ asset('storage/' . ($customers->avatar ?? 'uploads/quantri/default.jpg')) }}"
                                 alt="Avatar" style="max-height: 100px; object-fit: contain;">
                        </div>
                    </div>

                    {{-- Địa chỉ --}}
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-semibold">Địa chỉ</label>
                        <textarea name="address" class="form-control @error('address') is-invalid @enderror"
                                  rows="2">{{ old('address', $customers->address) }}</textarea>
                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Mật khẩu --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Mật khẩu cũ</label>
                        <input type="password" name="old_password" class="form-control @error('old_password') is-invalid @enderror">
                        @error('old_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Mật khẩu mới</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Nhập lại mật khẩu</label>
                        <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror">
                        @error('password_confirmation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="card-footer bg-white border-top text-end pt-3">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> Cập nhật tài khoản
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('styles')
<style>
    .gender-option {
        display: inline-block;
        padding: 10px 20px;
        border: 2px solid #ced4da;
        border-radius: 50px;
        cursor: pointer;
        transition: all 0.3s ease;
        background-color: #f8f9fa;
        font-weight: 500;
        user-select: none;
    }

    .btn-check:checked + .gender-option {
        background-color: #0d6efd;
        color: #fff;
        border-color: #0d6efd;
        box-shadow: 0 0 0.3rem rgba(13, 110, 253, 0.5);
    }

    .gender-option:hover {
        border-color: #0d6efd;
    }

    .gender-option i {
        margin-right: 5px;
    }

    img.avatar-preview {
        border-radius: 8px;
        border: 1px solid #dee2e6;
        max-width: 100px;
        max-height: 100px;
        object-fit: cover;
    }
</style>
@endpush
