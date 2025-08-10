{{-- resources/views/client/user/profile.blade.php --}}
@extends('client.user.dashboard')

@section('dashboard-content')
<style>
    .profile-edit-card {
        max-width: 600px;
        margin: 40px auto;
        padding: 30px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        font-family: 'Segoe UI', sans-serif;
    }

    .profile-edit-card h3 {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 25px;
        text-align: center;
        color: #333;
    }

    .profile-edit-card .form-label {
        font-weight: 500;
        color: #555;
    }

    .profile-edit-card .form-control {
        border-radius: 8px;
    }

    .profile-edit-card button {
        min-width: 120px;
        font-weight: 500;
        border-radius: 8px;
    }

    .profile-edit-card .btn-success {
        background-color: #28a745;
        border: none;
    }

    .profile-edit-card .btn-success:hover {
        background-color: #218838;
    }

    .profile-edit-card .btn-secondary {
        border: none;
    }
</style>

<div class="profile-edit-card">
    <h3>✏️ Chỉnh sửa thông tin cá nhân</h3>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form id="profile-form" action="{{ route('user.profile.update') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="full_name" class="form-label">Họ tên</label>
            <input type="text" name="full_name" class="form-control" 
                   value="{{ old('full_name', Auth::user()->full_name) }}">
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Số điện thoại</label>
            <input type="text" name="phone" class="form-control" 
                   value="{{ old('phone', Auth::user()->phone) }}">
        </div>
        <div class="mb-3">
            <label for="gender" class="form-label">Giới tính</label>
            @php
            $gender = Auth::user()->gender;
            $genderValue = $gender === 1 ? 'male' : ($gender === 0 ? 'female' : '');
        @endphp

        <select name="gender" class="form-control">
            <option value="">-- Chọn --</option>
            <option value="male" {{ $genderValue === 'male' ? 'selected' : '' }}>Nam</option>
            <option value="female" {{ $genderValue === 'female' ? 'selected' : '' }}>Nữ</option>
        </select>

        </div>
        <div class="mb-3">
            <label for="date_of_birth" class="form-label">Ngày sinh</label>
            <input type="date" name="date_of_birth" class="form-control" 
                   value="{{ old('date_of_birth', Auth::user()->date_of_birth) }}">
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Địa chỉ</label>
            <input type="text" name="address" class="form-control" 
                   value="{{ old('address', Auth::user()->address) }}">
        </div>
        <div class="d-flex justify-content-between mt-4">
            <button type="submit" id="submit-btn" class="btn btn-success">
                <i class="fa fa-save"></i> ✔️ Xác nhận
            </button>
            @if (session('user_return_url'))
                <a href="{{ session('user_return_url') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Quay lại
                </a>
            @else
                <a href="{{ url()->previous() }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Quay lại
                </a>
            @endif
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.getElementById('profile-form');
        const submitBtn = document.getElementById('submit-btn');
        const originalValues = {};

        Array.from(form.elements).forEach(el => {
            if (el.name) originalValues[el.name] = el.value;
        });

        form.addEventListener('input', function () {
            let changed = false;
            for (const name in originalValues) {
                const input = form.elements[name];
                if (input && input.value !== originalValues[name]) {
                    changed = true;
                    break;
                }
            }
            submitBtn.innerHTML = changed ? '✅ Cập nhật' : '✔️ Xác nhận';
        });
    });
</script>
@endpush
