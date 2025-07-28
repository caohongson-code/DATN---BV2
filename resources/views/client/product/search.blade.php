@extends('client.layouts.app')

@section('content')
<div class="container my-5">
    <div class="mb-4">
        <h2 class="fw-bold">Tìm kiếm từ khóa "{{ $keyword }}"</h2>
        <div class="text-muted mb-2">Tìm thấy {{ $products->total() }} sản phẩm cho từ khóa "{{ $keyword }}"</div>
        <form method="get" class="d-flex align-items-center gap-3 flex-wrap">
            <input type="hidden" name="keyword" value="{{ $keyword }}">
            <span class="fw-semibold">Sắp xếp theo:</span>
            <button type="submit" name="sort" value="price_desc" class="btn btn-light btn-sm {{ request('sort') == 'price_desc' ? 'border-primary' : '' }}">Giá Cao - Thấp</button>
            <button type="submit" name="sort" value="price_asc" class="btn btn-light btn-sm {{ request('sort') == 'price_asc' ? 'border-primary' : '' }}">Giá Thấp - Cao</button>
            <button type="submit" name="sort" value="newest" class="btn btn-light btn-sm {{ request('sort', 'newest') == 'newest' ? 'border-primary' : '' }}">Mới Nhất</button>
        </form>
    </div>
    <div class="row mt-4">
        @forelse($products as $product)
            <div class="col-md-3 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <a href="{{ route('product.show', $product->id) }}">
                        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->product_name }}" style="height:180px;object-fit:cover;">
                    </a>
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title mb-2" style="min-height:40px;">
                            <a href="{{ route('product.show', $product->id) }}" class="text-dark text-decoration-none">{{ $product->product_name }}</a>
                        </h6>
                        <div class="mb-2">
                            @if ($product->discount_price)
                                <span class="fw-bold text-danger">{{ number_format($product->discount_price, 0, ',', '.') }}đ</span>
                                <span class="text-muted text-decoration-line-through ms-1">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                            @else
                                <span class="fw-bold text-danger">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                            @endif
                        </div>
                        <a href="{{ route('product.show', $product->id) }}" class="btn btn-primary btn-sm mt-auto">Xem Chi Tiết</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <p>Không tìm thấy sản phẩm phù hợp.</p>
            </div>
        @endforelse
    </div>
    <div>
        {{ $products->appends(request()->query())->links() }}
    </div>
</div>
@endsection 