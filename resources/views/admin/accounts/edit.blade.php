@extends('admin.layouts.app')

@section('title', 'Cập nhật tài khoản')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        {{-- Tiêu đề + Quay lại --}}
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
            <h4 class="fw-bold mb-0">Cập nhật tài khoản</h4>
            <a href="{{ route('accounts.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
            </a>
        </div>

        <div class="card-body">
            {{-- Thông báo thành công --}}
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- Lỗi validate --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form --}}
            <form action="{{ route('accounts.update', $account->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    {{-- Vai trò --}}
                    <div class="col-md-12 mb-4">
                        <label class="form-label fw-semibold d-block mb-2">Vai trò</label>
                        <div class="select-box-group d-flex flex-wrap gap-3">
                            @foreach($roles as $role)
                                <div>
                                    <input type="radio" id="role-{{ $role->id }}" name="role_id" value="{{ $role->id }}"
                                        {{ $account->role_id == $role->id ? 'checked' : '' }} hidden>
                                    <label class="select-box-label" for="role-{{ $role->id }}">
                                        <i class="{{ strtolower($role->role_name) === 'admin' ? 'fas fa-user-shield' : 'fas fa-user' }} me-1"></i>
                                        {{ $role->role_name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('role_id')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                    </div>

                    {{-- Họ tên --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Họ và tên</label>
                        <input type="text" name="full_name" value="{{ $account->full_name }}"
                               class="form-control @error('full_name') is-invalid @enderror">
                        @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Ngày sinh --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Ngày sinh</label>
                        <input type="date" name="date_of_birth" value="{{ $account->date_of_birth }}"
                               class="form-control @error('date_of_birth') is-invalid @enderror">
                        @error('date_of_birth')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Email --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" value="{{ $account->email }}"
                               class="form-control @error('email') is-invalid @enderror" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Số điện thoại --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Số điện thoại</label>
                        <input type="text" name="phone" value="{{ $account->phone }}" maxlength="10"
                               class="form-control @error('phone') is-invalid @enderror">
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Giới tính --}}
                    <div class="col-md-12 mb-4">
                        <label class="form-label fw-semibold d-block mb-2">Giới tính</label>
                        <div class="select-box-group d-flex gap-3">
                            <div>
                                <input type="radio" name="gender" id="gender-male" value="1"
                                    {{ $account->gender == 1 ? 'checked' : '' }} hidden>
                                <label class="select-box-label" for="gender-male">👨 Nam</label>
                            </div>
                            <div>
                                <input type="radio" name="gender" id="gender-female" value="0"
                                    {{ $account->gender == 0 ? 'checked' : '' }} hidden>
                                <label class="select-box-label" for="gender-female">👩 Nữ</label>
                            </div>
                        </div>
                        @error('gender')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                    </div>

                    {{-- Ảnh đại diện --}}
                    <div class="col-md-12 mb-4">
                        <label class="form-label fw-semibold d-block mb-2">Ảnh đại diện</label>
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <input type="file" name="avatar" class="form-control w-auto @error('avatar') is-invalid @enderror">
                            <img src="{{ asset('storage/' . ($account->avatar ?? 'uploads/quantri/default.jpg')) }}" width="100" class="rounded border" alt="Avatar hiện tại">
                        </div>
                        @error('avatar')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    {{-- Nút cập nhật --}}
                    <div class="col-12 text-center mt-4">
                        <button type="submit" class="btn btn-success px-4 py-2">
                            <i class="fas fa-save me-1"></i> Cập nhật tài khoản
                        </button>
                    </div>
                </div>
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
        min-width: 110px;
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

    .gap-3 {
        gap: 1rem !important;
    }
</style>
