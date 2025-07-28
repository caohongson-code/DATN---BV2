@extends('admin.layouts.app')
@section('content')

<!-- Link Boxicons & SweetAlert2 -->
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    .form-section {
        background: #fff;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
    }

    .form-section h3, .form-section h4 {
        margin-bottom: 20px;
        font-weight: 600;
        color: #333;
    }

    .form-section label {
        font-weight: 500;
        color: #555;
    }

    .form-control:focus {
        box-shadow: 0 0 0 0.2rem rgba(13,110,253,.25);
    }

    img.avatar-preview {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #ccc;
        display: block;
        margin-bottom: 10px;
    }
</style>

<div class="container py-4">
    <!-- Thông tin cá nhân -->
    <h3 class="mb-4"><i class="bx bx-user-circle"></i> Thông tin cá nhân</h3>

    {{-- Thông báo SweetAlert --}}
    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Thành công',
                text: '{{ session('success') }}',
                confirmButtonColor: '#3085d6'
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Lỗi',
                text: '{{ session('error') }}',
                confirmButtonColor: '#d33'
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Có lỗi xảy ra',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonColor: '#d33'
            });
        </script>
    @endif

    <form action="{{ route('admin.updateProfile') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Avatar --}}
        <div class="mb-3">
            <label><i class='bx bx-image'></i> Ảnh đại diện</label><br>
            @if(Auth::user()->avatar)
                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="avatar" width="100" class="rounded mb-2 border shadow">
            @else
                <p>Chưa có ảnh</p>
            @endif
            <input type="file" name="avatar" class="form-control">
            @error('avatar')
                <div class="text-danger mt-1"><small>{{ $message }}</small></div>
            @enderror
        </div>

        {{-- Họ tên --}}
        <div class="mb-3">
            <label><i class='bx bx-user'></i> Họ tên</label>
            <input type="text" name="full_name" class="form-control" value="{{ old('full_name', $account->full_name) }}">
            @error('full_name')
                <div class="text-danger mt-1"><small>{{ $message }}</small></div>
            @enderror
        </div>

        {{-- SĐT --}}
        <div class="mb-3">
            <label><i class='bx bx-phone'></i> Số điện thoại</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone', $account->phone) }}">
            @error('phone')
                <div class="text-danger mt-1"><small>{{ $message }}</small></div>
            @enderror
        </div>

        {{-- Giới tính --}}
        <div class="mb-3">
            <label><i class='bx bx-user-pin'></i> Giới tính</label>
            <select name="gender" class="form-control">
                <option value="">--Chọn--</option>
                <option value="1" {{ $account->gender == 1 ? 'selected' : '' }}>Nam</option>
                <option value="0" {{ $account->gender == 0 ? 'selected' : '' }}>Nữ</option>
            </select>
            @error('gender')
                <div class="text-danger mt-1"><small>{{ $message }}</small></div>
            @enderror
        </div>

        {{-- Ngày sinh --}}
        <div class="mb-3">
            <label><i class='bx bx-calendar'></i> Ngày sinh</label>
            <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $account->date_of_birth) }}">
            @error('date_of_birth')
                <div class="text-danger mt-1"><small>{{ $message }}</small></div>
            @enderror
        </div>

        {{-- Địa chỉ --}}
        <div class="mb-3">
            <label><i class='bx bx-map'></i> Địa chỉ</label>
            <input type="text" name="address" class="form-control" value="{{ old('address', $account->address) }}">
            @error('address')
                <div class="text-danger mt-1"><small>{{ $message }}</small></div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary"><i class="bx bx-save"></i> Cập nhật</button>
    </form>

    <hr class="my-5">

    <h4 class="mb-4"><i class="bx bx-lock"></i> Đổi mật khẩu</h4>

    <form action="{{ route('admin.updatePassword') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label><i class='bx bx-lock-alt'></i> Mật khẩu hiện tại</label>
            <input type="password" name="current_password" class="form-control">
            @error('current_password')
                <div class="text-danger mt-1"><small>{{ $message }}</small></div>
            @enderror
        </div>
        <div class="mb-3">
            <label><i class='bx bx-key'></i> Mật khẩu mới</label>
            <input type="password" name="new_password" class="form-control">
            @error('new_password')
                <div class="text-danger mt-1"><small>{{ $message }}</small></div>
            @enderror
        </div>
        <div class="mb-3">
            <label><i class='bx bx-key'></i> Nhập lại mật khẩu mới</label>
            <input type="password" name="new_password_confirmation" class="form-control">
            @error('new_password_confirmation')
                <div class="text-danger mt-1"><small>{{ $message }}</small></div>
            @enderror
        </div>
        <button type="submit" class="btn btn-warning"><i class="bx bx-refresh"></i> Đổi mật khẩu</button>
    </form>
</div>
@endsection