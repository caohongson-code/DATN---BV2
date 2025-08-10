@extends('admin.layouts.app')

@section('title', 'Chi tiết tin tức')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Chi tiết tin tức</h3>
                    <div class="card-tools">
                        <a href="{{ route('news.edit', $news->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Chỉnh sửa
                        </a>
                        <a href="{{ route('news.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <!-- Basic Information -->
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Thông tin cơ bản</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Tiêu đề:</label>
                                                <p class="form-control-plaintext">{{ $news->title }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Tác giả:</label>
                                                <p class="form-control-plaintext">{{ $news->author ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="font-weight-bold">Tóm tắt:</label>
                                        <p class="form-control-plaintext">{{ $news->summary }}</p>
                                    </div>

                                    <div class="form-group">
                                        <label class="font-weight-bold">Nội dung:</label>
                                        <div class="border rounded p-3 bg-light">
                                            {!! nl2br(e($news->content)) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- SEO Information -->
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h5 class="card-title">Thông tin SEO</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Meta Title:</label>
                                        <p class="form-control-plaintext">{{ $news->meta_title ?? 'N/A' }}</p>
                                    </div>

                                    <div class="form-group">
                                        <label class="font-weight-bold">Meta Description:</label>
                                        <p class="form-control-plaintext">{{ $news->meta_description ?? 'N/A' }}</p>
                                    </div>

                                    <div class="form-group">
                                        <label class="font-weight-bold">Meta Keywords:</label>
                                        <p class="form-control-plaintext">{{ $news->meta_keywords ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <!-- Status and Settings -->
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Trạng thái & Cài đặt</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Trạng thái:</label>
                                        <div class="mt-1">
                                            <span class="badge {{ $news->status_badge }} badge-lg">
                                                {{ $news->status_text }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="font-weight-bold">Thời gian xuất bản:</label>
                                        <p class="form-control-plaintext">
                                            {{ $news->published_at ? $news->published_at->format('d/m/Y H:i') : 'Chưa xuất bản' }}
                                        </p>
                                    </div>

                                    <div class="form-group">
                                        <label class="font-weight-bold">Thứ tự sắp xếp:</label>
                                        <p class="form-control-plaintext">{{ $news->sort_order }}</p>
                                    </div>

                                    <div class="form-group">
                                        <label class="font-weight-bold">Đánh dấu:</label>
                                        <div class="mt-1">
                                            @if($news->is_featured)
                                                <span class="badge badge-warning mr-2">
                                                    <i class="fas fa-star"></i> Nổi bật
                                                </span>
                                            @endif
                                            @if($news->is_hot)
                                                <span class="badge badge-danger">
                                                    <i class="fas fa-fire"></i> Hot
                                                </span>
                                            @endif
                                            @if(!$news->is_featured && !$news->is_hot)
                                                <span class="text-muted">Không có</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Featured Image -->
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h5 class="card-title">Hình ảnh đại diện</h5>
                                </div>
                                <div class="card-body text-center">
                                    @if($news->featured_image)
                                        <img src="{{ asset('storage/' . $news->featured_image) }}" 
                                             alt="{{ $news->title }}" 
                                             class="img-fluid rounded" 
                                             style="max-width: 100%; height: auto;">
                                        <div class="mt-2">
                                            <small class="text-muted">{{ $news->featured_image }}</small>
                                        </div>
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" 
                                             style="height: 200px;">
                                            <div class="text-center">
                                                <i class="fas fa-image text-muted fa-3x mb-2"></i>
                                                <p class="text-muted">Không có hình ảnh</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Statistics -->
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h5 class="card-title">Thống kê</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <div class="info-box">
                                                <span class="info-box-icon bg-info">
                                                    <i class="fas fa-eye"></i>
                                                </span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Lượt xem</span>
                                                    <span class="info-box-number">{{ number_format($news->view_count) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info-box">
                                                <span class="info-box-icon bg-success">
                                                    <i class="fas fa-calendar"></i>
                                                </span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Ngày tạo</span>
                                                    <span class="info-box-number">{{ $news->created_at->format('d/m/Y') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row text-center mt-3">
                                        <div class="col-6">
                                            <div class="info-box">
                                                <span class="info-box-icon bg-warning">
                                                    <i class="fas fa-clock"></i>
                                                </span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Cập nhật</span>
                                                    <span class="info-box-number">{{ $news->updated_at->format('d/m/Y') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info-box">
                                                <span class="info-box-icon bg-secondary">
                                                    <i class="fas fa-link"></i>
                                                </span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Slug</span>
                                                    <span class="info-box-number">{{ $news->slug }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Actions -->
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h5 class="card-title">Thao tác nhanh</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <button type="button" 
                                                class="btn {{ $news->is_featured ? 'btn-warning' : 'btn-outline-warning' }}"
                                                onclick="toggleFeatured({{ $news->id }})">
                                            <i class="fas fa-star"></i> 
                                            {{ $news->is_featured ? 'Bỏ đánh dấu nổi bật' : 'Đánh dấu nổi bật' }}
                                        </button>
                                        
                                        <button type="button" 
                                                class="btn {{ $news->is_hot ? 'btn-danger' : 'btn-outline-danger' }}"
                                                onclick="toggleHot({{ $news->id }})">
                                            <i class="fas fa-fire"></i> 
                                            {{ $news->is_hot ? 'Bỏ đánh dấu hot' : 'Đánh dấu hot' }}
                                        </button>

                                        @if($news->status === 'draft')
                                            <button type="button" 
                                                    class="btn btn-success"
                                                    onclick="publishNews({{ $news->id }})">
                                                <i class="fas fa-paper-plane"></i> Xuất bản ngay
                                            </button>
                                        @endif

                                        @if($news->status === 'published')
                                            <button type="button" 
                                                    class="btn btn-secondary"
                                                    onclick="draftNews({{ $news->id }})">
                                                <i class="fas fa-edit"></i> Chuyển về bản nháp
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
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
            alert('Có lỗi xảy ra!');
        }
    });
}

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
            alert('Có lỗi xảy ra!');
        }
    });
}

function publishNews(newsId) {
    if (confirm('Bạn có chắc chắn muốn xuất bản tin tức này?')) {
        $.ajax({
            url: '/admin/news/bulk-action',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                action: 'publish',
                ids: [newsId]
            },
            success: function(response) {
                location.reload();
            },
            error: function() {
                alert('Có lỗi xảy ra!');
            }
        });
    }
}

function draftNews(newsId) {
    if (confirm('Bạn có chắc chắn muốn chuyển tin tức này về bản nháp?')) {
        $.ajax({
            url: '/admin/news/bulk-action',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                action: 'draft',
                ids: [newsId]
            },
            success: function(response) {
                location.reload();
            },
            error: function() {
                alert('Có lỗi xảy ra!');
            }
        });
    }
}
</script>
@endpush 