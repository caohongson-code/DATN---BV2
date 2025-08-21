@extends('admin.layouts.app')
@section('content')

<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    body {
        background: #f8f9fa;
    }

    .profile-section {
        background: #fff;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        margin-bottom: 40px;
    }

    .profile-section h3, .profile-section h4 {
        margin-bottom: 25px;
        font-weight: 700;
        font-size: 1.6rem;
        color: #333;
    }

    .profile-section label {
        font-weight: 600;
        font-size: 1.1rem;
        color: #444;
    }

    .form-control {
        height: 50px;
        font-size: 1rem;
        padding: 0.55rem 1rem;
    }

    .form-control:focus {
        box-shadow: 0 0 0 0.25rem rgba(13,110,253,.25);
    }

    img.avatar-preview {
        width: 140px;
        height: 140px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #ccc;
        display: block;
        margin: 0 auto 15px auto;
    }

    .btn-gradient-primary {
        background: linear-gradient(90deg, #28a745, #20c997);
        color: #fff;
        font-weight: 600;
        font-size: 1.05rem;
        border-radius: 10px;
        padding: 10px 22px;
        transition: all 0.3s ease;
        border: none;
    }
    .btn-gradient-primary:hover {
        background: linear-gradient(90deg, #20c997, #28a745);
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.15);
    }

    .btn-gradient-warning {
        background: linear-gradient(90deg, #ffc107, #e0a800);
        color: #fff;
        font-weight: 600;
        font-size: 1.05rem;
        border-radius: 10px;
        padding: 10px 22px;
        border: none;
        transition: all 0.3s ease;
    }
    .btn-gradient-warning:hover {
        background: linear-gradient(90deg, #e0a800, #ffc107);
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.15);
    }

</style>

<div class="container py-5">

    <!-- SweetAlert thông báo -->
    @if(session('success'))
        <script>
            Swal.fire({icon: 'success', title: 'Thành công', text: '{{ session('success') }}', confirmButtonColor: '#3085d6'});
        </script>
    @endif
    @if(session('error'))
        <script>
            Swal.fire({icon: 'error', title: 'Lỗi', text: '{{ session('error') }}', confirmButtonColor: '#d33'});
        </script>
    @endif
    @if ($errors->any())
        <script>
            Swal.fire({icon: 'error', title: 'Có lỗi xảy ra', html: `{!! implode('<br>', $errors->all()) !!}`, confirmButtonColor: '#d33'});
        </script>
    @endif

    <!-- Thông tin cá nhân -->
    <div class="profile-section">
        <h3><i class="bx bx-user-circle"></i> Thông tin cá nhân</h3>
        <form action="{{ route('admin.updateProfile') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Avatar --}}
            <div class="mb-4 text-center">
                @if(Auth::user()->avatar)
                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="avatar-preview shadow-sm">
                @else
                    <img src="https://via.placeholder.com/140x140?text=Avatar" class="avatar-preview">
                @endif
                <input type="file" name="avatar" class="form-control mt-2">
                @error('avatar')
                    <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                @enderror
            </div>

            {{-- Họ tên & SĐT --}}
            <div class="row">
                <div class="col-md-6 mb-4">
                    <label><i class='bx bx-user'></i> Họ tên</label>
                    <input type="text" name="full_name" class="form-control" value="{{ old('full_name', $account->full_name) }}">
                    @error('full_name')<div class="text-danger mt-1"><small>{{ $message }}</small></div>@enderror
                </div>
                <div class="col-md-6 mb-4">
                    <label><i class='bx bx-phone'></i> Số điện thoại</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $account->phone) }}">
                    @error('phone')<div class="text-danger mt-1"><small>{{ $message }}</small></div>@enderror
                </div>
            </div>

            {{-- Giới tính & Ngày sinh --}}
            <div class="row">
                <div class="col-md-6 mb-4">
                    <label><i class='bx bx-user-pin'></i> Giới tính</label>
                    <select name="gender" class="form-control">
                        <option value="">--Chọn--</option>
                        <option value="1" {{ $account->gender == 1 ? 'selected' : '' }}>Nam</option>
                        <option value="0" {{ $account->gender == 0 ? 'selected' : '' }}>Nữ</option>
                    </select>
                    @error('gender')<div class="text-danger mt-1"><small>{{ $message }}</small></div>@enderror
                </div>
                <div class="col-md-6 mb-4">
                    <label><i class='bx bx-calendar'></i> Ngày sinh</label>
                    <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $account->date_of_birth) }}">
                    @error('date_of_birth')<div class="text-danger mt-1"><small>{{ $message }}</small></div>@enderror
                </div>
            </div>

            {{-- Địa chỉ --}}
            <div class="mb-4">
                <label><i class='bx bx-map'></i> Địa chỉ</label>
                <input type="text" name="address" class="form-control" value="{{ old('address', $account->address) }}">
                @error('address')<div class="text-danger mt-1"><small>{{ $message }}</small></div>@enderror
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-gradient-primary"><i class="bx bx-save"></i> Cập nhật</button>
            </div>
        </form>
    </div>

    <!-- Đổi mật khẩu -->
    <div class="profile-section">
        <h4><i class="bx bx-lock"></i> Đổi mật khẩu</h4>
        <form action="{{ route('admin.updatePassword') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label><i class='bx bx-lock-alt'></i> Mật khẩu hiện tại</label>
                <input type="password" name="current_password" class="form-control">
                @error('current_password')<div class="text-danger mt-1"><small>{{ $message }}</small></div>@enderror
            </div>
            <div class="mb-4">
                <label><i class='bx bx-key'></i> Mật khẩu mới</label>
                <input type="password" name="new_password" class="form-control">
                @error('new_password')<div class="text-danger mt-1"><small>{{ $message }}</small></div>@enderror
            </div>
            <div class="mb-4">
                <label><i class='bx bx-key'></i> Nhập lại mật khẩu mới</label>
                <input type="password" name="new_password_confirmation" class="form-control">
                @error('new_password_confirmation')<div class="text-danger mt-1"><small>{{ $message }}</small></div>@enderror
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-gradient-warning"><i class="bx bx-refresh"></i> Đổi mật khẩu</button>
            </div>
        </form>
    </div>

</div>
@endsection
