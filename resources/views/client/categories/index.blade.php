@extends('client.layouts.app')

@section('content')
<style>
    .category-page-row {
        display: flex;
        flex-wrap: wrap;
        gap: 24px;
        align-items: flex-start;
    }
    .category-sidebar {
        min-width: 220px;
        max-width: 260px;
        flex: 0 0 240px;
    }
    .category-sidebar .list-group {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
    }
    .category-sidebar .list-group-item {
        border: none;
        border-bottom: 1px solid #eee;
        font-weight: 600;
        font-size: 15px;
        padding: 14px 20px;
        transition: background 0.3s, color 0.3s;
        cursor: pointer;
    }
    .category-sidebar .list-group-item:last-child {
        border-bottom: none;
    }
    .category-sidebar .list-group-item.active, .category-sidebar .list-group-item:hover {
        background: #0d6efd;
        color: #fff;
    }
    .category-sidebar .list-group-item a {
        color: inherit;
        text-decoration: none;
        display: block;
    }

    .category-content {
        flex: 1 1 0%;
        min-width: 0;
    }

    .category-toolbar {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
        align-items: center;
        background: #f9f9f9;
        padding: 16px;
        border-radius: 12px;
        box-shadow: 0 1px 5px rgba(0, 0, 0, 0.05);
    }
    .category-toolbar .form-control {
        height: 44px;
        border-radius: 8px;
        font-size: 15px;
    }
    .category-toolbar button {
        height: 44px;
        border-radius: 8px;
        min-width: 100px;
        font-size: 15px;
    }

    .product-card .card {
        border-radius: 14px;
        box-shadow: 0 3px 12px rgba(0, 0, 0, 0.05);
        border: none;
        transition: transform 0.2s ease-in-out;
    }
    .product-card .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
    }
    .product-card .card-img-top {
        border-radius: 14px 14px 0 0;
        object-fit: cover;
        height: 200px;
        background: #f1f1f1;
    }
    .product-card .card-title {
        font-size: 17px;
        font-weight: 600;
        margin-bottom: 8px;
    }
    .product-card .card-text {
        font-size: 18px;
        color: #e11d48;
        font-weight: 600;
    }
    .product-card .old-price {
        font-size: 14px;
        color: #888;
        text-decoration: line-through;
        margin-left: 6px;
    }

    @media (max-width: 991px) {
        .category-page-row {
            flex-direction: column;
        }
        .category-sidebar {
            max-width: 100%;
            flex: 1 1 100%;
        }
        .category-content {
            width: 100%;
        }
        .category-toolbar {
            flex-direction: column;
            gap: 12px;
        }
    }
</style>

<div class="container my-5">
    <div class="category-page-row">
        <div class="category-sidebar">
            <ul class="list-group">
                <li class="list-group-item {{ !$selectedCategory ? 'active' : '' }}">
                    <a href="{{ route('client.categories') }}">Tất cả</a>
                </li>
                @foreach($categories as $category)
                    <li class="list-group-item {{ $selectedCategory == $category->id ? 'active' : '' }}">
                        <a href="{{ route('client.categories.filter', $category->id) }}">
                            {{ $category->category_name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="category-content">
            <form method="GET" class="category-toolbar">
                <input type="text" name="search" class="form-control flex-grow-1" placeholder="Tìm kiếm sản phẩm..." value="{{ request('search') }}">
                <select name="sort_price" class="form-control" style="width: 200px;" onchange="this.form.submit()">
                    <option value="">Sắp xếp theo giá</option>
                    <option value="asc" {{ request('sort_price') == 'asc' ? 'selected' : '' }}>Giá thấp đến cao</option>
                    <option value="desc" {{ request('sort_price') == 'desc' ? 'selected' : '' }}>Giá cao đến thấp</option>
                </select>
                <button type="submit" class="btn btn-primary">Lọc</button>
            </form>

            <div class="row">
                @forelse($products as $product)
                    <div class="col-md-4 mb-4 product-card">
                        <div class="card h-100">
                            <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->product_name }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $product->product_name }}</h5>
                                <p class="card-text">
                                    {{ number_format($product->discount_price ?? $product->price, 0, ',', '.') }} VND
                                    @if($product->discount_price)
                                        <span class="old-price">{{ number_format($product->price, 0, ',', '.') }} VND</span>
                                    @endif
                                </p>
                                <a href="{{ route('product.show', $product->id) }}" class="btn btn-outline-primary w-100">Xem chi tiết</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info">Không có sản phẩm nào.</div>
                    </div>
                @endforelse
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $products->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
