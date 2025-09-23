@extends('admin.layouts.app')

@section('title', 'Danh sách màu')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0 rounded-3">

        {{-- Header --}}
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap"
             style="background: rgba(241, 243, 245, 0.8); backdrop-filter: blur(6px); padding: 12px 20px;">
            <h5 class="mb-0 fw-bold text-dark">
                <i class="bx bx-palette me-2"></i> Danh sách màu
            </h5>
            <a href="{{ route('colors.create') }}" class="btn btn-create">
                <i class="bx bx-plus me-1"></i> Thêm màu
            </a>
        </div>

        <div class="card-body">

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
                            <th style="width:30%;">Tên màu</th>
                            <th style="width:30%;">Mã màu</th>
                            <th style="width:15%;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($colors as $color)
                        <tr>
                            <td class="text-center">{{ $color->id }}</td>
                            <td class="text-center">{{ $color->value }}</td>
                            <td class="text-center">
                                @if($color->code)
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        <div style="width: 20px; height: 20px; border-radius: 50%; background-color: {{ $color->code }}; border: 1px solid #999;"></div>
                                        <span class="text-dark">{{ $color->code }}</span>
                                    </div>
                                @else
                                    <span class="text-muted">Chưa có mã màu</span>
                                @endif
                            </td>
                            <td class="d-flex justify-content-center gap-1">
                                <a href="{{ route('colors.edit', $color->id) }}" class="btn btn-warning btn-sm" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('colors.destroy', $color->id) }}" method="POST"
                                      onsubmit="return confirm('Bạn có chắc muốn xóa màu này?');">
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
                            <td colspan="4" class="text-muted text-center">Không có dữ liệu màu sắc.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Phân trang --}}
            <div class="mt-3 d-flex justify-content-center">
                {{ $colors->links('pagination::bootstrap-5') }}
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
