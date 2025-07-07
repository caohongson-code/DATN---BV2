@extends('admin.layouts.app')

@section('title', 'Danh sách màu')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-bold">Danh sách màu</h4>
            <a href="{{ route('colors.create') }}" class="btn btn-success btn-sm">
                <i class="fas fa-plus me-1"></i> Thêm màu
            </a>
        </div>

        <div class="card-body">
            {{-- Thông báo --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- Bảng --}}
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 10%">ID</th>
                            <th style="width: 30%">Tên màu</th>
                            <th style="width: 30%">Mã màu</th>
                            <th style="width: 30%">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($colors as $color)
                        <tr>
                            <td>{{ $color->id }}</td>
                            <td>{{ $color->value }}</td>
                            <td>
                                @if($color->code)
                                    <div class="d-flex align-items-center justify-content-start gap-2">
                                        <div style="width: 20px; height: 20px; border-radius: 50%; background-color: {{ $color->code }}; border: 1px solid #999;"></div>
                                        <span class="text-dark">{{ $color->code }}</span>
                                    </div>
                                @else
                                    <span class="text-muted">Chưa có mã màu</span>
                                @endif
                            </td>

                            <td>
                                <a href="{{ route('colors.edit', $color->id) }}" class="btn btn-warning btn-sm me-1">
                                    <i class="fas fa-edit"></i> Sửa
                                </a>
                                <form action="{{ route('colors.destroy', $color->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa màu này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash-alt"></i> Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-muted">Không có dữ liệu màu sắc.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
