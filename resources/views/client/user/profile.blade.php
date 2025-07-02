@extends('client.layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Chỉnh sửa thông tin cá nhân</h3>

    <form action="{{ route('user.profile.update') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="full_name" class="form-label">Họ tên</label>
            <input type="text" name="full_name" class="form-control" value="{{ old('full_name', Auth::user()->full_name) }}">
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Số điện thoại</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone', Auth::user()->phone) }}">
        </div>
        <div class="mb-3">
            <label for="gender" class="form-label">Giới tính</label>
            <select name="gender" class="form-control">
                <option value="">-- Chọn --</option>
                <option value="1" {{ Auth::user()->gender == 1 ? 'selected' : '' }}>Nam</option>
                <option value="2" {{ Auth::user()->gender == 2 ? 'selected' : '' }}>Nữ</option>

            </select>
        </div>
        <div class="mb-3">
            <label for="date_of_birth" class="form-label">Ngày sinh</label>
            <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', Auth::user()->date_of_birth) }}">
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Địa chỉ</label>
            <input type="text" name="address" class="form-control" value="{{ old('address', Auth::user()->address) }}">
        </div>
        <button type="submit" class="btn btn-success">Cập nhật</button>
        <a href="{{ route('user.profile') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
