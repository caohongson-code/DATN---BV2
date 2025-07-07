@extends('admin.layouts.app')

@section('title', 'Danh sách màu')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow rounded-4 border-0">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center rounded-top-4">
            <h5 class="mb-0 fw-bold text-primary">
                <i class="fas fa-palette me-2"></i> Danh sách màu
            </h5>
            <a href="{{ route('colors.create') }}" class="btn btn-success btn-sm">
                <i class="fas fa-plus"></i> Thêm màu
            </a>
        </div>

        <div class="card-body">
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif


            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th style="width: 10%;">#</th>
                            <th style="width: 30%;">Tên màu</th>
                            <th style="width: 30%;">Mã màu</th>
                            <th style="width: 30%;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($colors as $color)
                            <tr>
                                <td>{{ $color->id }}</td>
                                <td class="fw-semibold text-start">{{ $color->value }}</td>
                                <td class="text-start">
                                    @if($color->code)
                                        <div class="d-flex align-items-center gap-2">
                                            <div style="width: 24px; height: 24px; border-radius: 50%; background-color: {{ $color->code }}; border: 1px solid #999;"></div>
                                            <code>{{ $color->code }}</code>
                                        </div>
                                    @else
                                        <span class="text-muted fst-italic">Chưa có mã màu</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('colors.edit', $color->id) }}" class="btn btn-warning btn-sm me-1" title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('colors.destroy', $color->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa màu này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Xóa">
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


            <div class="mt-3 d-flex justify-content-end">
                {{ $colors->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
