@extends('client.layouts.app')

@section('title', 'Tin tức - PowPow')

@section('content')
<div class="container">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 mb-0">Tin tức</h1>
                    <p class="text-muted mb-0">Cập nhật những tin tức mới nhất về công nghệ</p>
                </div>
                <div class="d-flex gap-2">
                    <form class="d-flex" method="GET" action="{{ route('client.news.index') }}">
                        <input type="text" 
                               class="form-control me-2" 
                               name="search" 
                               placeholder="Tìm kiếm tin tức..." 
                               value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured News Section -->
    @if($featuredNews->count() > 0)
    <div class="row mb-5">
        <div class="col-12">
            <h3 class="h4 mb-3">Tin tức nổi bật</h3>
            <div class="row">
                @foreach($featuredNews as $featured)
                <div class="col-md-4 mb-3">
                    <div class="card h-100 shadow-sm">
                        @if($featured->featured_image)
                        <img src="{{ asset('storage/' . $featured->featured_image) }}" 
                             class="card-img-top" 
                             alt="{{ $featured->title }}"
                             style="height: 200px; object-fit: cover;">
                        @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                             style="height: 200px;">
                            <i class="fas fa-newspaper fa-3x text-muted"></i>
                        </div>
                        @endif
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge bg-primary">Nổi bật</span>
                                <small class="text-muted">{{ $featured->published_at->format('d/m/Y') }}</small>
                            </div>
                            <h5 class="card-title">
                                <a href="{{ route('client.news.show', $featured->slug) }}" 
                                   class="text-decoration-none text-dark">
                                    {{ Str::limit($featured->title, 60) }}
                                </a>
                            </h5>
                            <p class="card-text text-muted">
                                {{ Str::limit($featured->summary, 100) }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="fas fa-user me-1"></i>
                                    {{ $featured->author ?: 'Admin' }}
                                </small>
                                <small class="text-muted">
                                    <i class="fas fa-eye me-1"></i>
                                    {{ $featured->view_count }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Main Content -->
    <div class="row">
        <!-- News List -->
        <div class="col-lg-8">
            @if($news->count() > 0)
                <div class="row">
                    @foreach($news as $item)
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 shadow-sm">
                            @if($item->featured_image)
                            <img src="{{ asset('storage/' . $item->featured_image) }}" 
                                 class="card-img-top" 
                                 alt="{{ $item->title }}"
                                 style="height: 180px; object-fit: cover;">
                            @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                 style="height: 180px;">
                                <i class="fas fa-newspaper fa-2x text-muted"></i>
                            </div>
                            @endif
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    @if($item->is_hot)
                                    <span class="badge bg-danger">Hot</span>
                                    @endif
                                    <small class="text-muted">{{ $item->published_at->format('d/m/Y') }}</small>
                                </div>
                                <h6 class="card-title">
                                    <a href="{{ route('client.news.show', $item->slug) }}" 
                                       class="text-decoration-none text-dark">
                                        {{ Str::limit($item->title, 50) }}
                                    </a>
                                </h6>
                                <p class="card-text text-muted small">
                                    {{ Str::limit($item->summary, 80) }}
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="fas fa-user me-1"></i>
                                        {{ $item->author ?: 'Admin' }}
                                    </small>
                                    <small class="text-muted">
                                        <i class="fas fa-eye me-1"></i>
                                        {{ $item->view_count }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $news->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Không có tin tức nào</h5>
                    <p class="text-muted">Hãy quay lại sau để xem tin tức mới nhất</p>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Hot News Sidebar -->
            @if($hotNews->count() > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-fire text-danger me-2"></i>
                        Tin tức hot
                    </h5>
                </div>
                <div class="card-body p-0">
                    @foreach($hotNews as $hot)
                    <div class="p-3 border-bottom">
                        <div class="d-flex">
                            @if($hot->featured_image)
                            <img src="{{ asset('storage/' . $hot->featured_image) }}" 
                                 class="rounded me-3" 
                                 alt="{{ $hot->title }}"
                                 style="width: 60px; height: 60px; object-fit: cover;">
                            @else
                            <div class="rounded me-3 bg-light d-flex align-items-center justify-content-center" 
                                 style="width: 60px; height: 60px;">
                                <i class="fas fa-newspaper text-muted"></i>
                            </div>
                            @endif
                            <div class="flex-grow-1">
                                <h6 class="mb-1">
                                    <a href="{{ route('client.news.show', $hot->slug) }}" 
                                       class="text-decoration-none text-dark">
                                        {{ Str::limit($hot->title, 40) }}
                                    </a>
                                </h6>
                                <small class="text-muted">
                                    {{ $hot->published_at->format('d/m/Y') }}
                                </small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Categories (if needed) -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-tags me-2"></i>
                        Danh mục
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('client.news.index') }}" 
                           class="badge bg-primary text-decoration-none">
                            Tất cả
                        </a>
                        <a href="{{ route('client.news.index', ['category' => 'technology']) }}" 
                           class="badge bg-secondary text-decoration-none">
                            Công nghệ
                        </a>
                        <a href="{{ route('client.news.index', ['category' => 'gaming']) }}" 
                           class="badge bg-success text-decoration-none">
                            Gaming
                        </a>
                        <a href="{{ route('client.news.index', ['category' => 'reviews']) }}" 
                           class="badge bg-info text-decoration-none">
                            Đánh giá
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-5px);
}

.card-title a:hover {
    color: #007bff !important;
}

.badge {
    font-size: 0.75rem;
}

.pagination {
    justify-content: center;
}

.pagination .page-link {
    color: #007bff;
    border-color: #dee2e6;
}

.pagination .page-item.active .page-link {
    background-color: #007bff;
    border-color: #007bff;
}
</style>
@endpush 