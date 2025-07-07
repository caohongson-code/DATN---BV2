@extends('admin.layouts.app')

@section('title', 'Danh sách dung lượng')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow border-0 rounded-3">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-bold">
                <i class="fas fa-database text-primary me-2"></i>
                Danh sách dung lượng
            </h4>
            <a href="{{ route('storages.create') }}" class="btn btn-success btn-sm">
                <i class="fas fa-plus"></i> Tạo mới
            </a>
        </div> 

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row mb-3 g-2 align-items-center">
                <div class="col-md-8 d-flex flex-wrap gap-2">
                    <button class="btn btn-warning btn-sm"><i class="fas fa-file-upload"></i> Tải từ file</button>
                    <button class="btn btn-primary btn-sm"><i class="fas fa-print"></i> In dữ liệu</button>
                    <button class="btn btn-info btn-sm"><i class="fas fa-copy"></i> Sao chép</button>
                    <button class="btn btn-success btn-sm"><i class="fas fa-file-excel"></i> Xuất Excel</button>
                    <button class="btn btn-danger btn-sm"><i class="fas fa-file-pdf"></i> Xuất PDF</button>
                    <button class="btn btn-secondary btn-sm"><i class="fas fa-trash"></i> Xóa tất cả</button>
                </div>
                <div class="col-md-4">
                    <form method="GET" action="{{ route('storages.index') }}">
                        <div class="input-group input-group-sm">
                            <input
                                type="text"
                                name="keyword"
                                class="form-control"
                                placeholder="Tìm theo giá trị"
                                value="{{ request('keyword') }}">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th style="width: 60px;">ID</th>
                            <th>Giá trị</th>
                            <th style="width: 160px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($storages as $storage)
                            <tr>
                                <td>{{ $storage->id }}</td>
                                <td>{{ $storage->value }}</td>
                                <td>
                                    <a href="{{ route('storages.edit', $storage->id) }}" class="btn btn-sm btn-warning me-1">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('storages.destroy', $storage->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn chắc chắn muốn xóa?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash-alt"></i>
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

            <div class="mt-3">
                {{ $storages->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
