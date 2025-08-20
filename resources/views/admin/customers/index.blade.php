@extends('admin.layouts.app')

@section('title', 'Quản lý khách hàng')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0 rounded-3">

        {{-- Tiêu đề + nút thêm --}}
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap"
             style="background: rgba(241, 243, 245, 0.8); backdrop-filter: blur(6px); padding: 12px 20px;">
            <h5 class="mb-0 fw-bold text-dark">
                <i class="bx bx-user me-2"></i> Quản lý khách hàng
            </h5>
            <div>
                <a href="{{ route('customers.create') }}" class="btn btn-create">
                    <i class="bx bx-plus me-1"></i> Tạo mới
                </a>

            </div>
        </div>

        <div class="card-body">

            {{-- Form tìm kiếm --}}
            <form method="GET" action="{{ route('customers.index') }}" class="mb-4">
                <div class="row g-2 align-items-center" style="max-width: 500px;">
                    <div class="col-9">
                        <input type="text" name="keyword" class="form-control form-control-sm"
                               placeholder="Tìm theo mã hoặc tên" value="{{ request('keyword') }}">
                    </div>
                    <div class="col-3">
                        <button type="submit" class="btn btn-sm btn-primary w-100">
                            <i class="bx bx-search"></i> Tìm
                        </button>
                    </div>
                </div>
            </form>

            {{-- Thông báo --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>{{ session('success') }}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Bảng dữ liệu --}}
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Avatar</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($listKH as $customer)
                        <tr>
                            <td>{{ $customer->id }}</td>
                            <td>
                                <img src="{{ $customer->avatar ? asset('storage/' . $customer->avatar) : asset('images/default-avatar.png') }}"
                                     class="img-thumbnail rounded-circle"
                                     alt="Avatar"
                                     width="60" height="60">
                            </td>
                            <td>{{ $customer->full_name }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->role->role_name }}</td>
                            <td>
                                <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-warning btn-sm me-1">
                                    <i class="fas fa-edit"></i> Sửa
                                </a>
                                <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Bạn chắc chắn muốn xóa?')" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash-alt"></i> Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-muted">Không có dữ liệu.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Phân trang --}}
            <div class="mt-3 d-flex justify-content-center">
                {{ $listKH->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
<style>
    .btn-create {
        background: linear-gradient(90deg, #28a745, #20c997);
        color: #fff;
        font-weight: 500;
        border-radius: 8px;
        padding: 6px 14px;
        display: inline-flex;
        align-items: center;
        transition: all 0.3s ease;
        border: none;
    }
    .btn-create:hover {
        background: linear-gradient(90deg, #20c997, #28a745);
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(0,0,0,0.15);
        color: #fff;
        text-decoration: none;
    }
    .btn-create i {
        font-size: 14px;
    }
    </style>
@endsection
