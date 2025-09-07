@extends('admin.layouts.app')

@section('title', 'Tạo tài khoản mới')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-bold">Tạo tài khoản mới</h4>
            <a href="{{ route('accounts.index') }}" class="btn btn-secondary btn-sm">
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

            <form action="{{ route('accounts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    {{-- Vai trò --}}
                <div class="col-md-12 mb-3 select-box-group">
                    <label class="form-label fw-semibold d-block">Vai trò</label>
                    <div class="d-flex flex-wrap gap-3">
                        @foreach($roles as $role)
                            <div>
                                <input type="radio" name="role_id" id="role-{{ $role->id }}" value="{{ $role->id }}"
                                    {{ old('role_id', $user->role_id ?? '') == $role->id ? 'checked' : '' }}>
                                <label class="select-box-label" for="role-{{ $role->id }}">
                                    <i class="{{ strtolower($role->role_name) === 'admin' ? 'fas fa-user-shield' : 'fas fa-user' }}"></i>
                                    {{ $role->role_name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    @error('role_id')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                </div>

                    {{-- Họ tên --}}
                    <div class="col-md-6 mb-3">
                        <label for="full_name" class="form-label fw-semibold">Họ và tên</label>
                        <input type="text" name="full_name" value="{{ old('full_name') }}"
                               class="form-control @error('full_name') is-invalid @enderror">
                        @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Avatar --}}
                    <div class="col-md-6 mb-3">
                        <label for="avatar" class="form-label fw-semibold">Ảnh đại diện</label>
                        <input type="file" name="avatar" class="form-control @error('avatar') is-invalid @enderror">
                        @error('avatar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Ngày sinh --}}
                    <div class="col-md-6 mb-3">
                        <label for="date_of_birth" class="form-label fw-semibold">Ngày sinh</label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}"
                               class="form-control @error('date_of_birth') is-invalid @enderror">
                        @error('date_of_birth')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Email --}}
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="form-control @error('email') is-invalid @enderror">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Điện thoại --}}
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label fw-semibold">Số điện thoại</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" maxlength="10"
                               class="form-control @error('phone') is-invalid @enderror">
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Giới tính --}}
                 
            <div class="col-md-6 mb-3 select-box-group">
                <label class="form-label fw-semibold d-block">Giới tính</label>
                <div class="d-flex gap-3">
                    <div>
                        <input type="radio" name="gender" id="gender-male" value="1"
                            {{ old('gender', $user->gender ?? '') == '1' ? 'checked' : '' }}>
                        <label class="select-box-label" for="gender-male">
                            👨 Nam
                        </label>
                    </div>
                    <div>
                        <input type="radio" name="gender" id="gender-female" value="0"
                            {{ old('gender', $user->gender ?? '') == '0' ? 'checked' : '' }}>
                        <label class="select-box-label" for="gender-female">
                            👩 Nữ
                        </label>
                    </div>
                </div>
                @error('gender')<div class="text-danger mt-1">{{ $message }}</div>@enderror
            </div>

                    {{-- Địa chỉ --}}
                    <div class="col-md-6 mb-3">
                        <label for="address" class="form-label fw-semibold">Địa chỉ</label>
                        <textarea name="address" class="form-control @error('address') is-invalid @enderror"
                                  rows="2">{{ old('address') }}</textarea>
                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3 text-muted fst-italic">
                    (*) Mật khẩu mặc định cho tài khoản mới là: <strong>1234</strong>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-user-plus me-1"></i> Tạo tài khoản
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
<style>
    .select-box-group input[type="radio"] {
        display: none;
    }

    .select-box-label {
        display: flex;
        align-items: center;
        justify-content: start;
        gap: 8px;
        padding: 12px 16px;
        border: 2px solid #dee2e6;
        border-radius: 40px;
        background-color: #f8f9fa;
        cursor: pointer;
        transition: 0.3s ease-in-out;
        font-weight: 500;
        user-select: none;
    }

    .select-box-label:hover {
        border-color: #0d6efd;
    }

    .select-box-group input[type="radio"]:checked + .select-box-label {
        background-color: #0d6efd;
        color: white;
        border-color: #0d6efd;
        box-shadow: 0 0 6px rgba(13, 110, 253, 0.4);
    }

    .select-box-label i {
        font-size: 1rem;
    }

    /* Responsive spacing */
    .gap-3 {
        gap: 1rem !important;
    }
</style>

