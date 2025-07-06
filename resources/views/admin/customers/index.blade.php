@extends('admin.layouts.app')

@section('title', 'Quản lý khách hàng')

@section('content')
<div class="container-fluid">
    {{-- Tiêu đề và nút --}}
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-white border-bottom">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h4 class="mb-0 fw-bold">Quản lý khách hàng</h4>
                </div>
            </div>
        </div>

        <div class="card-body">
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

            {{-- Nút chức năng + Tìm kiếm --}}
            <div class="row mb-3 align-items-center">
                <div class="col-md-8">
                    <a href="{{ route('customers.create') }}" class="btn btn-success btn-sm">+ Tạo mới</a>
                    <button class="btn btn-warning btn-sm">Tải từ file</button>
                    <button class="btn btn-primary btn-sm">In dữ liệu</button>
                    <button class="btn btn-info btn-sm">Sao chép</button>
                    <button class="btn btn-success btn-sm">Xuất Excel</button>
                    <button class="btn btn-danger btn-sm">Xuất PDF</button>
                    <button class="btn btn-secondary btn-sm">Xóa tất cả</button>
                </div>
                <div class="col-md-4">
                    <form method="GET" action="{{ route('customers.index') }}">
                        <div class="input-group input-group-sm">
                            <input type="text" name="keyword" class="form-control" placeholder="Tìm theo mã hoặc tên" value="{{ request('keyword') }}">
                            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Bảng dữ liệu --}}
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center">
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
                                @if ($customer->avatar)
                                    <img src="{{ asset('storage/' . $customer->avatar) }}" alt="avatar" class="img-thumbnail" width="100">
                                @else
                                    <span class="text-muted">Chưa có ảnh</span>
                                @endif
                            </td>
                            <td>{{ $customer->full_name }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->role->role_name }}</td>
                            <td>
                                <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>Sửa</a>
                                <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" style="display:inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Bạn chắc chắn muốn xóa?')" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Xoá</button>
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
@endsection
