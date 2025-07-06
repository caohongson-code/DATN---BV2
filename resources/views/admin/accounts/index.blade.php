@extends('admin.layouts.app')
@section('title', 'Quản lý nội bộ')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0 rounded-3">
        {{-- Tiêu đề và nút --}}
        <div class="card-header bg-white border-bottom">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h4 class="mb-0 fw-bold">Quản lý nội bộ</h4>
                </div>

            </div>
        </div>

        <div class="card-body">
            {{-- Thông báo --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
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
            <div class="row mb-3 align-items-center">
                <div class="col-md-8">
                    <a href="{{ route('accounts.create') }}" class="btn btn-success btn-sm">+ Tạo mới</a>
                    <button class="btn btn-warning btn-sm">Tải từ file</button>
                    <button class="btn btn-primary btn-sm">In dữ liệu</button>
                    <button class="btn btn-info btn-sm">Sao chép</button>
                    <button class="btn btn-success btn-sm">Xuất Excel</button>
                    <button class="btn btn-danger btn-sm">Xuất PDF</button>
                    <button class="btn btn-secondary btn-sm">Xóa tất cả</button>
                </div>
                <div class="col-md-4">
                    <form method="GET" action="{{ route('accounts.index') }}">
                        <div class="input-group input-group-sm">
                            <input type="text" name="keyword" class="form-control"
                                placeholder="Tìm theo tên hoặc chức vụ" value="{{ request('keyword') }}">
                            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                        </div>
                    </form>
                </div>
            </div>
            {{-- Bảng danh sách --}}
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
                        @forelse($listQT as $nv)
                        <tr>
                            <td>{{ $nv->id }}</td>
                            <td><img src="{{ asset('storage/' . $nv->avatar)  }}"class="img-thumbnail" alt="hinh anh" width="100px" height= "auto"></td>
                            <td>{{ $nv->full_name }}</td>
                            <td>{{ $nv->email }}</td>
                            <td>{{ $nv->role->role_name }}</td>
                            <td>
                                {{-- @if (session('admin_id') == 3) --}}
                                @if ($admin && $admin->role_id == 1)
                                <a href="{{ route('accounts.edit', $nv->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>Sửa</a>
                                <form action="{{ route('accounts.destroy', $nv->id) }}" method="POST" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm"
                                            onclick="return confirm('Delete this account?')"><i class="fas fa-trash-alt"></i> Xoá</button>
                                </form>
                                {{-- @else --}}
                      {{-- <span class="text-muted">No permission</span> --}}
                    @endif
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

            {{-- Pagination --}}
            <div class="mt-3 d-flex justify-content-center">
                {{ $listQT->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
