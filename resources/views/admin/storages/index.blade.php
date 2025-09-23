@extends('admin.layouts.app')

@section('title', 'Danh sách dung lượng')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0 rounded-3">

        {{-- Header --}}
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap"
             style="background: rgba(241, 243, 245, 0.8); backdrop-filter: blur(6px); padding: 12px 20px;">
            <h5 class="mb-0 fw-bold text-dark">
                <i class="bx bx-data me-2"></i> Danh sách dung lượng
            </h5>
            <a href="{{ route('storages.create') }}" class="btn btn-create">
                <i class="bx bx-plus me-1"></i> Tạo mới
            </a>
        </div>

        <div class="card-body">

            {{-- Form tìm kiếm --}}
            <form method="GET" action="{{ route('storages.index') }}" class="mb-4">
                <div class="row g-2 align-items-center" style="max-width: 500px;">
                    <div class="col-9">
                        <input type="text" name="keyword" class="form-control form-control-sm text-center"
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
                <div class="alert alert-success text-center alert-dismissible fade show" role="alert">
                    <strong>{{ session('success') }}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('error'))
    <div class="alert alert-danger text-center">
        {{ session('error') }}
    </div>
@endif

            {{-- Bảng danh sách --}}
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle text-center mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:5%;">ID</th>
                            <th>Giá trị</th>
                            <th style="width:15%;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($storages as $storage)
                        <tr>
                            <td class="text-center">{{ $storage->id }}</td>
                            <td class="text-center">{{ $storage->value }}</td>
                            <td class="d-flex justify-content-center gap-1">
                                <a href="{{ route('storages.edit', $storage->id) }}" class="btn btn-warning btn-sm" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('storages.destroy', $storage->id) }}" method="POST"
                                      onsubmit="return confirm('Bạn có chắc muốn xóa dung lượng này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" title="Xóa">
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

            {{-- Phân trang --}}
            <div class="mt-3 d-flex justify-content-center">
                {{ $storages->withQueryString()->links('pagination::bootstrap-5') }}
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
