@extends('admin.layouts.app')

@section('title', 'Danh sách dung lượng')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-bold">Danh sách dung lượng</h4>
        </div>

        <div class="card-body table-responsive">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="row mb-3 align-items-center">
                <div class="col-md-8">
                    <a href="{{ route('storages.create') }}" class="btn btn-success btn-sm">+ Tạo mới</a>
                    <button class="btn btn-warning btn-sm">Tải từ file</button>
                    <button class="btn btn-primary btn-sm">In dữ liệu</button>
                    <button class="btn btn-info btn-sm">Sao chép</button>
                    <button class="btn btn-success btn-sm">Xuất Excel</button>
                    <button class="btn btn-danger btn-sm">Xuất PDF</button>
                    <button class="btn btn-secondary btn-sm">Xóa tất cả</button>
                </div>
                <div class="col-md-4">
                    <form method="GET" action="{{ route('storages.index') }}">
                        <div class="input-group input-group-sm">
                            <input type="text" name="keyword" class="form-control" placeholder="Tìm theo mã hoặc tên" value="{{ request('keyword') }}">
                            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                        </div>
                    </form>
                </div>
            </div>

            <table class="table table-bordered table-hover align-middle text-center mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Giá trị</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($storages as $storage)
                        <tr>
                            <td>{{ $storage->id }}</td>
                            <td>{{ $storage->value }}</td>
                            <td>
                                <a href="{{ route('storages.edit', $storage->id) }}" class="btn btn-sm btn-warning me-1">
                                    <i class="fas fa-edit"></i> Sửa
                                </a>
                                <form action="{{ route('storages.destroy', $storage->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash-alt"></i> Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-muted text-center">Không có dung lượng nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
