@extends('admin.layouts.app')

@section('title', 'Quản lý tin tức')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                {{-- Header --}}
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Quản lý tin tức</h3>
                    <a href="{{ route('news.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Thêm tin tức mới
                    </a>
                </div>

                <div class="card-body">
                    {{-- Form lọc --}}
                    <form action="{{ route('news.index') }}" method="GET" class="mb-3">
                        <div class="row g-2">
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm tin tức..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <select name="status" class="form-control">
                                    <option value="">Tất cả trạng thái</option>
                                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Bản nháp</option>
                                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Đã xuất bản</option>
                                    <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Đã lưu trữ</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="is_featured" class="form-control">
                                    <option value="">Tất cả nổi bật</option>
                                    <option value="1" {{ request('is_featured') == '1' ? 'selected' : '' }}>Nổi bật</option>
                                    <option value="0" {{ request('is_featured') == '0' ? 'selected' : '' }}>Không nổi bật</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="per_page" class="form-control" onchange="this.form.submit()">
                                    <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15 tin/trang</option>
                                    <option value="25" {{ request('per_page', 15) == 25 ? 'selected' : '' }}>25 tin/trang</option>
                                    <option value="50" {{ request('per_page', 15) == 50 ? 'selected' : '' }}>50 tin/trang</option>
                                    <option value="100" {{ request('per_page', 15) == 100 ? 'selected' : '' }}>100 tin/trang</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-center">
                                <button type="submit" class="btn btn-outline-secondary me-2">
                                    <i class="fas fa-search"></i> Lọc
                                </button>
                                @if(request('search') || request('status') || request('is_featured'))
                                    <a href="{{ route('news.index') }}" class="btn btn-outline-danger">
                                        <i class="fas fa-times"></i> Xóa bộ lọc
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>

                    {{-- Bulk action --}}
                    <form id="bulk-form" action="{{ route('admin.news.bulk-action') }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-inline">
                                    <select name="action" class="form-control me-2" required>
                                        <option value="">Chọn hành động</option>
                                        <option value="delete">Xóa</option>
                                        <option value="publish">Xuất bản</option>
                                        <option value="draft">Chuyển về bản nháp</option>
                                    </select>
                                    <button type="submit" class="btn btn-warning" onclick="return confirm('Bạn có chắc chắn muốn thực hiện hành động này?')">
                                        Áp dụng
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Bảng danh sách tin tức --}}
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">
                                            <input type="checkbox" id="select-all">
                                        </th>
                                        <th style="width: 80px;">Hình ảnh</th>
                                        <th>Tiêu đề</th>
                                        <th>Tác giả</th>
                                        <th>Trạng thái</th>
                                        <th>Nổi bật</th>
                                        <th>Hot</th>
                                        <th>Lượt xem</th>
                                        <th>Ngày tạo</th>
                                        <th style="width: 150px;">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($news as $item)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="ids[]" value="{{ $item->id }}" class="news-checkbox">
                                        </td>
                                        <td>
                                            @if($item->featured_image)
                                                <img src="{{ asset('storage/' . $item->featured_image) }}"
                                                     alt="{{ $item->title }}"
                                                     class="img-thumbnail"
                                                     style="width: 60px; height: 60px; object-fit: cover;">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center"
                                                     style="width: 60px; height: 60px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $item->title }}</strong>
                                            @if($item->is_featured)
                                                <span class="badge badge-warning ms-1">Nổi bật</span>
                                            @endif
                                            @if($item->is_hot)
                                                <span class="badge badge-danger ms-1">Hot</span>
                                            @endif
                                            <br>
                                            <small class="text-muted">{{ Str::limit($item->summary, 100) }}</small>
                                        </td>
                                        <td>{{ $item->author ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge {{ $item->status_badge }}">
                                                {{ $item->status_text }}
                                            </span>
                                            @if($item->published_at)
                                                <br><small class="text-muted">{{ $item->published_at->format('d/m/Y H:i') }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button"
                                                    class="btn btn-sm {{ $item->is_featured ? 'btn-warning' : 'btn-outline-warning' }}"
                                                    onclick="toggleFeatured({{ $item->id }})"
                                                    title="Bật/tắt nổi bật">
                                                <i class="fas fa-star"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <button type="button"
                                                    class="btn btn-sm {{ $item->is_hot ? 'btn-danger' : 'btn-outline-danger' }}"
                                                    onclick="toggleHot({{ $item->id }})"
                                                    title="Bật/tắt hot">
                                                <i class="fas fa-fire"></i>
                                            </button>
                                        </td>
                                        <td>{{ number_format($item->view_count) }}</td>
                                        <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('news.show', $item->id) }}" class="btn btn-sm btn-info" title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('news.edit', $item->id) }}" class="btn btn-sm btn-warning" title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('news.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tin tức này?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Xóa">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="10" class="text-center">Không có tin tức nào</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </form>

                    {{-- Phân trang --}}
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="text-muted">
                                Hiển thị {{ $news->firstItem() ?? 0 }} - {{ $news->lastItem() ?? 0 }} trong tổng số {{ $news->total() }} tin tức
                                @if(request('search') || request('status') || request('is_featured'))
                                    (đã lọc)
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-end">
                                {{ $news->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.pagination {
    margin-bottom: 0;
}

.pagination .page-link {
    color: #007bff;
    border: 1px solid #dee2e6;
}

.pagination .page-item.active .page-link {
    background-color: #007bff;
    border-color: #007bff;
}

.pagination .page-item.disabled .page-link {
    color: #6c757d;
    pointer-events: none;
    background-color: #fff;
    border-color: #dee2e6;
}

.page-info {
    font-size: 0.9rem;
    color: #6c757d;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Chọn tất cả checkbox
    $('#select-all').change(function() {
        $('.news-checkbox').prop('checked', $(this).prop('checked'));
    });

    // Cập nhật checkbox "select all" khi từng checkbox thay đổi
    $('.news-checkbox').change(function() {
        if (!$(this).prop('checked')) {
            $('#select-all').prop('checked', false);
        } else {
            const allChecked = $('.news-checkbox').length === $('.news-checkbox:checked').length;
            $('#select-all').prop('checked', allChecked);
        }
    });
});

// Toggle trạng thái nổi bật (featured)
function toggleFeatured(newsId) {
    $.ajax({
        url: '/admin/news/' + newsId + '/toggle-featured',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                location.reload();
            }
        },
        error: function() {
            alert('Có lỗi xảy ra khi cập nhật trạng thái nổi bật!');
        }
    });
}

// Toggle trạng thái hot
function toggleHot(newsId) {
    $.ajax({
        url: '/admin/news/' + newsId + '/toggle-hot',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                location.reload();
            }
        },
        error: function() {
            alert('Có lỗi xảy ra khi cập nhật trạng thái hot!');
        }
    });
}
</script>
@endpush
