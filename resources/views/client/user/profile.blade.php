{{-- resources/views/client/user/profile.blade.php --}}
@extends('client.user.dashboard')

@section('dashboard-content')
    <h3>Chỉnh sửa thông tin cá nhân</h3>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form id="profile-form" action="{{ route('user.profile.update') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Họ tên</label>
            <input type="text" name="full_name" class="form-control" value="{{ old('full_name', Auth::user()->full_name) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Số điện thoại</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone', Auth::user()->phone) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Giới tính</label>
            <select name="gender" class="form-control">
                <option value="">-- Chọn --</option>
                <option value="male" {{ Auth::user()->gender === 'male' ? 'selected' : '' }}>Nam</option>
                <option value="female" {{ Auth::user()->gender === 'female' ? 'selected' : '' }}>Nữ</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Ngày sinh</label>
            <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', Auth::user()->date_of_birth) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Địa chỉ</label>
            <input type="text" name="address" class="form-control" value="{{ old('address', Auth::user()->address) }}">
        </div>
        <div class="d-flex justify-content-between">
            <button type="submit" id="submit-btn" class="btn btn-success">✔️ Xác nhận</button>
            @if (session('user_return_url'))
                <a href="{{ session('user_return_url') }}" class="btn btn-outline-secondary">⬅️ Quay lại trang trước</a>
            @endif
        </div>
    </form>
@endsection
@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.getElementById('profile-form');
        const submitBtn = document.getElementById('submit-btn');
        const originalValues = {};

        // Lưu giá trị ban đầu của các trường nhập
        Array.from(form.elements).forEach(el => {
            if (el.name) originalValues[el.name] = el.value;
        });

        // Kiểm tra thay đổi và cập nhật nút
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
