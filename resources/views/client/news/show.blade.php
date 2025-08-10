@extends('client.layouts.app')

@section('title', $news->title . ' - PowPow')

@section('content')
<div class="container">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}" class="text-decoration-none">Trang chủ</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('client.news.index') }}" class="text-decoration-none">Tin tức</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($news->title, 30) }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Article Header -->
            <article class="mb-5">
                <header class="mb-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            @if($news->is_featured)
                            <span class="badge bg-primary me-2">Nổi bật</span>
                            @endif
                            @if($news->is_hot)
                            <span class="badge bg-danger me-2">Hot</span>
                            @endif
                        </div>
                        <div class="text-end">
                            <small class="text-muted d-block">
                                <i class="fas fa-calendar me-1"></i>
                                {{ $news->published_at->format('d/m/Y H:i') }}
                            </small>
                            <small class="text-muted">
                                <i class="fas fa-eye me-1"></i>
                                {{ $news->view_count }} lượt xem
                            </small>
                        </div>
                    </div>
                    
                    <h1 class="h2 mb-3">{{ $news->title }}</h1>
                    
                    @if($news->summary)
                    <div class="lead text-muted mb-3">
                        {{ $news->summary }}
                    </div>
                    @endif
                    
                    <div class="d-flex align-items-center text-muted mb-3">
                        <i class="fas fa-user me-2"></i>
                        <span>{{ $news->author ?: 'Admin' }}</span>
                        @if($news->meta_keywords)
                        <span class="mx-3">•</span>
                        <i class="fas fa-tags me-2"></i>
                        <span>{{ $news->meta_keywords }}</span>
                        @endif
                    </div>
                </header>

                <!-- Featured Image -->
                @if($news->featured_image)
                <div class="mb-4">
                    <img src="{{ asset('storage/' . $news->featured_image) }}" 
                         class="img-fluid rounded shadow" 
                         alt="{{ $news->title }}">
                </div>
                @endif

                <!-- Article Content -->
                <div class="article-content">
                    {!! nl2br(e($news->content)) !!}
                </div>

                <!-- Article Footer -->
                <footer class="mt-5 pt-4 border-top">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary btn-sm" onclick="shareArticle()">
                                <i class="fas fa-share me-1"></i>
                                Chia sẻ
                            </button>
                            <button class="btn btn-outline-secondary btn-sm" onclick="printArticle()">
                                <i class="fas fa-print me-1"></i>
                                In bài viết
                            </button>
                        </div>
                        <div class="text-muted">
                            <small>Lần cập nhật cuối: {{ $news->updated_at->format('d/m/Y H:i') }}</small>
                        </div>
                    </div>
                </footer>
            </article>

            <!-- Related News -->
            @if($relatedNews->count() > 0)
            <section class="mb-5">
                <h3 class="h4 mb-4">Tin tức liên quan</h3>
                <div class="row">
                    @foreach($relatedNews as $related)
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            @if($related->featured_image)
                            <img src="{{ asset('storage/' . $related->featured_image) }}" 
                                 class="card-img-top" 
                                 alt="{{ $related->title }}"
                                 style="height: 150px; object-fit: cover;">
                            @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                 style="height: 150px;">
                                <i class="fas fa-newspaper fa-2x text-muted"></i>
                            </div>
                            @endif
                            <div class="card-body">
                                <h6 class="card-title">
                                    <a href="{{ route('client.news.show', $related->slug) }}" 
                                       class="text-decoration-none text-dark">
                                        {{ Str::limit($related->title, 50) }}
                                    </a>
                                </h6>
                                <p class="card-text text-muted small">
                                    {{ Str::limit($related->summary, 70) }}
                                </p>
                                <small class="text-muted">
                                    {{ $related->published_at->format('d/m/Y') }}
                                </small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Latest News Sidebar -->
            @if($latestNews->count() > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clock me-2"></i>
                        Tin tức mới nhất
                    </h5>
                </div>
                <div class="card-body p-0">
                    @foreach($latestNews as $latest)
                    <div class="p-3 border-bottom">
                        <div class="d-flex">
                            @if($latest->featured_image)
                            <img src="{{ asset('storage/' . $latest->featured_image) }}" 
                                 class="rounded me-3" 
                                 alt="{{ $latest->title }}"
                                 style="width: 60px; height: 60px; object-fit: cover;">
                            @else
                            <div class="rounded me-3 bg-light d-flex align-items-center justify-content-center" 
                                 style="width: 60px; height: 60px;">
                                <i class="fas fa-newspaper text-muted"></i>
                            </div>
                            @endif
                            <div class="flex-grow-1">
                                <h6 class="mb-1">
                                    <a href="{{ route('client.news.show', $latest->slug) }}" 
                                       class="text-decoration-none text-dark">
                                        {{ Str::limit($latest->title, 40) }}
                                    </a>
                                </h6>
                                <small class="text-muted">
                                    {{ $latest->published_at->format('d/m/Y') }}
                                </small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Social Share -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-share-alt me-2"></i>
                        Chia sẻ
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" 
                           target="_blank" 
                           class="btn btn-primary btn-sm">
                            <i class="fab fa-facebook me-2"></i>
                            Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($news->title) }}" 
                           target="_blank" 
                           class="btn btn-info btn-sm">
                            <i class="fab fa-twitter me-2"></i>
                            Twitter
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}" 
                           target="_blank" 
                           class="btn btn-secondary btn-sm">
                            <i class="fab fa-linkedin me-2"></i>
                            LinkedIn
                        </a>
                    </div>
                </div>
            </div>

            <!-- Back to News -->
            <div class="card">
                <div class="card-body text-center">
                    <a href="{{ route('client.news.index') }}" 
                       class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Quay lại tin tức
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.article-content {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #333;
}

.article-content p {
    margin-bottom: 1.5rem;
}

.article-content h2, .article-content h3, .article-content h4 {
    margin-top: 2rem;
    margin-bottom: 1rem;
    color: #2c3e50;
}

.article-content img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 1rem 0;
}

.article-content blockquote {
    border-left: 4px solid #007bff;
    padding-left: 1rem;
    margin: 1.5rem 0;
    font-style: italic;
    color: #6c757d;
}

.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-3px);
}

.card-title a:hover {
    color: #007bff !important;
}

.badge {
    font-size: 0.75rem;
}

.breadcrumb-item a:hover {
    color: #007bff !important;
}
</style>
@endpush

@push('scripts')
<script>
function shareArticle() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $news->title }}',
            text: '{{ $news->summary }}',
            url: window.location.href
        });
    } else {
        // Fallback for browsers that don't support Web Share API
        const url = window.location.href;
        const text = '{{ $news->title }}';
        
        if (navigator.clipboard) {
            navigator.clipboard.writeText(url).then(() => {
                alert('Đã sao chép link vào clipboard!');
            });
        } else {
            // Fallback for older browsers
            const textArea = document.createElement('textarea');
            textArea.value = url;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            alert('Đã sao chép link vào clipboard!');
        }
    }
}

function printArticle() {
    window.print();
}
</script>
@endpush 