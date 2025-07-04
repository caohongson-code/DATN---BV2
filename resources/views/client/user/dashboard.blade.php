@extends('client.layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm rounded-3">
                <div class="card-header bg-dark text-white fw-bold">
                    Xin chào, {{ Auth::user()->name }}
                </div>
                <div class="list-group list-group-flush">
                    <button class="list-group-item list-group-item-action" onclick="showSection('profile')">👉 Thông tin cá nhân</button>
                    <button class="list-group-item list-group-item-action" onclick="showSection('orders')">👉 Quản lý đơn hàng</button>
                    <a href="#" class="list-group-item list-group-item-action text-danger"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        👉 Đăng xuất
                    </a>
                    <form id="logout-form" action="{{ route('taikhoan.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="col-md-9">
            <div class="card shadow-sm rounded-3">
                <div class="card-body">
                    {{-- Section: Thông tin cá nhân --}}
                    <div id="section-profile">
                        <h3>Chỉnh sửa thông tin cá nhân</h3>

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form id="profile-form" action="{{ route('user.profile.update') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Họ tên</label>
                                <input type="text" name="full_name" class="form-control"
                                       value="{{ old('full_name', Auth::user()->full_name) }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Số điện thoại</label>
                                <input type="text" name="phone" class="form-control"
                                       value="{{ old('phone', Auth::user()->phone) }}">
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
                                <input type="date" name="date_of_birth" class="form-control"
                                       value="{{ old('date_of_birth', Auth::user()->date_of_birth) }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Địa chỉ</label>
                                <input type="text" name="address" class="form-control"
                                       value="{{ old('address', Auth::user()->address) }}">
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="submit" id="submit-btn" class="btn btn-success">✔️ Xác nhận</button>
                                @if (session('user_return_url'))
                                    <a href="{{ session('user_return_url') }}" class="btn btn-outline-secondary">
                                        ⬅️ Quay lại trang trước
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>

                    {{-- Section: Quản lý đơn hàng --}}
                    <div id="section-orders" style="display: none;">
                        <h3>Danh sách đơn hàng</h3>
                        <p>Chức năng đang được phát triển...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function showSection(section) {
        document.getElementById('section-profile').style.display = 'none';
        document.getElementById('section-orders').style.display = 'none';
        document.getElementById('section-' + section).style.display = 'block';
    }

    document.addEventListener("DOMContentLoaded", function () {
        showSection('profile');

        const form = document.getElementById('profile-form');
        const submitBtn = document.getElementById('submit-btn');

        // Lưu dữ liệu ban đầu
        const originalValues = {};
        Array.from(form.elements).forEach(el => {
            if (el.name) {
                originalValues[el.name] = el.value;
            }
        });

        // Gắn sự kiện kiểm tra thay đổi
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
@endsection
