@extends('admin.layouts.app')

@section('title', 'Danh sách RAM')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-white border-bottom d-flex flex-wrap justify-content-between align-items-center">
            <h4 class="mb-0 fw-bold">Danh sách RAM</h4>

        </div>

        <div class="card-body">
            {{-- Thông báo thành công --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="row mb-3 align-items-center">
                <div class="col-md-8">
                    <a href="{{ route('rams.create') }}" class="btn btn-success btn-sm">+ Tạo mới</a>
                    <button class="btn btn-warning btn-sm">Tải từ file</button>
                    <button class="btn btn-primary btn-sm">In dữ liệu</button>
                    <button class="btn btn-info btn-sm">Sao chép</button>
                    <button class="btn btn-success btn-sm">Xuất Excel</button>
                    <button class="btn btn-danger btn-sm">Xuất PDF</button>
                    <button class="btn btn-secondary btn-sm">Xóa tất cả</button>
                </div>
                <div class="col-md-4">
                    <form method="GET" action="{{ route('rams.index') }}">
                        <div class="input-group input-group-sm">
                            <input type="text" name="keyword" class="form-control" placeholder="Tìm theo mã hoặc tên" value="{{ request('keyword') }}">
                            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Bảng dữ liệu --}}
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 5%;">ID</th>
                        <th>Giá trị</th>
                        <th style="width: 20%;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rams as $ram)
                        <tr>
                            <td>{{ $ram->id }}</td>
                            <td>{{ $ram->value }}</td>
                            <td>
                                <a href="{{ route('rams.edit', $ram->id) }}" class="btn btn-warning btn-sm me-1">
                                    <i class="fas fa-edit"></i> Sửa
                                </a>
                                <form action="{{ route('rams.destroy', $ram->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa RAM này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash-alt"></i> Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">Không có RAM nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Phân trang --}}
            <div class="d-flex justify-content-end mt-3">
                {{ $rams->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
