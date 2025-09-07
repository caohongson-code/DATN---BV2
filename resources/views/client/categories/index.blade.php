@extends('client.layouts.app')

@section('content')
<style>
    /* Category Page Modern UI */
    .category-page {
        background: #f4f6fb;
        min-height: 100vh;
        padding: 50px 0;
    }

    .category-container {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        overflow: hidden;
    }

    /* Header */
    .category-header {
        background: linear-gradient(135deg, #4e73df 0%, #764ba2 100%);
        color: #fff;
        padding: 40px 20px;
        text-align: center;
        position: relative;
    }
    .category-header h1 {
        font-size: 2.2rem;
        font-weight: 700;
        margin: 0;
    }
    .category-header p {
        font-size: 1rem;
        opacity: 0.9;
        margin-top: 8px;
    }

    /* Layout */
    .category-content-wrapper {
        display: flex;
    }
    .category-sidebar {
        width: 260px;
        background: #fafafa;
        border-right: 1px solid #eaeaea;
    }
    .sidebar-header {
        background: #4e73df;
        color: #fff;
        text-align: center;
        font-weight: 600;
        padding: 18px;
        font-size: 1.05rem;
    }
    .list-group {
        padding: 0;
        margin: 0;
        list-style: none;
    }
    .list-group-item {
        padding: 14px 20px;
        border-bottom: 1px solid #eee;
        font-weight: 500;
        color: #333;
        cursor: pointer;
        transition: 0.3s;
    }
    .list-group-item:hover {
        background: #eef2ff;
        color: #4e73df;
        padding-left: 25px;
    }
    .list-group-item.active {
        background: #4e73df;
        color: #fff;
        font-weight: 600;
    }
    .list-group-item a {
        color: inherit;
        text-decoration: none;
        display: block;
    }

    /* Main */
    .category-main {
        flex: 1;
        padding: 30px;
    }

    /* Toolbar */
    .category-toolbar {
        background: #fff;
        border: 1px solid #eaeaea;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 30px;
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    .search-group {
        flex: 1;
        position: relative;
    }
    .search-group input {
        border-radius: 25px;
        border: 1px solid #ccc;
        padding: 12px 45px;
        width: 100%;
        font-size: 15px;
    }
    .search-group i {
        position: absolute;
        top: 50%;
        left: 18px;
        transform: translateY(-50%);
        color: #888;
    }
    .sort-group select {
        border-radius: 25px;
        padding: 12px 18px;
        border: 1px solid #ccc;
        font-size: 15px;
    }
    .filter-btn {
        border-radius: 25px;
        padding: 12px 25px;
        border: none;
        background: linear-gradient(135deg, #4e73df 0%, #764ba2 100%);
        color: #fff;
        font-weight: 600;
        transition: 0.3s;
    }
    .filter-btn:hover {
        opacity: 0.9;
        transform: translateY(-2px);
    }

    /* Products Grid */
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 25px;
    }
    .product-card {
        border: 1px solid #eee;
        border-radius: 15px;
        background: #fff;
        overflow: hidden;
        transition: 0.4s ease;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        opacity: 0;
        transform: translateY(20px);
        animation: fadeInUp 0.6s ease forwards;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 25px rgba(78, 115, 223, 0.25);
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .product-image-container {
        height: 200px;
        background: #f9f9f9;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }
    .product-image {
        max-height: 90%;
        max-width: 100%;
        object-fit: contain;
    }
    .product-badge {
        position: absolute;
        top: 12px;
        left: 12px;
        background: #e74c3c;
        color: #fff;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }
    .product-content {
        padding: 18px;
    }
    .product-title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 12px;
        color: #2c3e50;
        min-height: 42px;
    }
    .product-price {
        margin-bottom: 15px;
        display: flex;
        gap: 10px;
        align-items: center;
    }
    .current-price {
        font-size: 18px;
        font-weight: 700;
        color: #e74c3c;
    }
    .old-price {
        font-size: 14px;
        color: #999;
        text-decoration: line-through;
    }
    .discount-badge {
        font-size: 12px;
        color: #fff;
        background: #e67e22;
        padding: 2px 6px;
        border-radius: 8px;
    }

    /* Product actions: two buttons ngang */
    .product-actions {
        display: flex;
        gap: 10px;
    }
    .btn-details, .btn-add-cart {
        flex: 1;
        text-align: center;
        padding: 8px 0;
        border-radius: 25px;
        font-weight: 600;
        transition: 0.3s;
        font-size: 14px;
    }
    .btn-details {
        background: #4e73df;
        color: #fff;
        text-decoration: none;
    }
    .btn-details:hover {
        background: #3b5bcc;
    }
    .btn-add-cart {
        background: #27ae60;
        color: #fff;
        border: none;
    }
    .btn-add-cart:hover {
        background: #1e8f4e;
        transform: scale(1.05);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #888;
    }
    .empty-state i {
        font-size: 3rem;
        margin-bottom: 10px;
        color: #ccc;
    }

    /* Pagination */
    .pagination-wrapper {
        margin-top: 30px;
        display: flex;
        justify-content: center;
    }
    .pagination .page-link {
        border-radius: 10px;
        margin: 0 5px;
        border: none;
        color: #4e73df;
        font-weight: 600;
    }
    .pagination .page-item.active .page-link {
        background: #4e73df;
        color: #fff;
    }

    @media(max-width:992px){
        .category-content-wrapper { flex-direction: column; }
        .category-sidebar { width: 100%; border-right: none; border-bottom: 1px solid #eee; }
        .product-actions { flex-direction: column; gap: 8px; }
    }

    /* Form giỏ hàng ẩn mặc định */
    .cart-form-popup {
        display: none;
        background: #fff;
        border: 1px solid #eee;
        border-radius: 12px;
        padding: 15px;
        position: absolute;
        bottom: 55px;
        right: 0;
        width: 240px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        animation: fadeInUp 0.3s ease forwards;
        z-index: 10;
    }
    .cart-form-popup.active {
        display: block;
    }
    .cart-form-popup select,
    .cart-form-popup .quantity-box input {
        border-radius: 8px;
    }
    .cart-form-popup button[type="submit"] {
        margin-top: 8px;
        border-radius: 8px;
        background: #4e73df;
        border: none;
        color: #fff;
        font-weight: 600;
        transition: 0.3s;
    }
    .cart-form-popup button[type="submit"]:hover {
        background: #3b5bcc;
    }
</style>

<div class="category-page">
    <div class="container">
        <div class="category-container">
            <div class="category-header">
                <h1>Khám phá sản phẩm</h1>
                <p>Chọn danh mục và tìm kiếm sản phẩm yêu thích của bạn</p>
            </div>

            <div class="category-content-wrapper">
                <!-- Sidebar -->
                <div class="category-sidebar">
                    <div class="sidebar-header">Danh mục sản phẩm</div>
                    <ul class="list-group">
                        <li class="list-group-item {{ !$selectedCategory ? 'active' : '' }}">
                            <a href="{{ route('client.categories') }}">Tất cả sản phẩm</a>
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

                <!-- Main -->
                <div class="category-main">
                    <form method="GET" class="category-toolbar">
                        <div class="search-group">
                            <i class="fas fa-search"></i>
                            <input type="text" name="search" placeholder="Tìm kiếm sản phẩm..." value="{{ request('search') }}">
                        </div>
                        <div class="sort-group">
                            <select name="sort_price" onchange="this.form.submit()">
                                <option value="">Sắp xếp theo giá</option>
                                <option value="asc" {{ request('sort_price') == 'asc' ? 'selected' : '' }}>Giá thấp đến cao</option>
                                <option value="desc" {{ request('sort_price') == 'desc' ? 'selected' : '' }}>Giá cao đến thấp</option>
                            </select>
                        </div>
                        <button type="submit" class="filter-btn">
                            <i class="fas fa-filter me-2"></i> Lọc
                        </button>
                    </form>

                    @if($products->count() > 0)
                        <div class="products-grid">
                            @foreach($products as $product)
                                <div class="product-card" style="position:relative;">
                                    <div class="product-image-container">
                                        @if($product->discount_price)
                                            <div class="product-badge">Giảm giá</div>
                                        @endif
                                        <img src="{{ asset('storage/' . $product->image) }}" class="product-image" alt="{{ $product->product_name }}">
                                    </div>
                                    <div class="product-content">
                                        <h5 class="product-title">{{ $product->product_name }}</h5>
                                        <div class="product-price">
                                            <span class="current-price">{{ number_format($product->discount_price ?? $product->price, 0, ',', '.') }} ₫</span>
                                            @if($product->discount_price)
                                                <span class="old-price">{{ number_format($product->price, 0, ',', '.') }} ₫</span>
                                                <span class="discount-badge">-{{ number_format((($product->price - $product->discount_price) / $product->price) * 100, 0) }}%</span>
                                            @endif
                                        </div>

                                        <!-- Nút xem chi tiết + Thêm vào giỏ hàng -->
                                        <div class="product-actions">
                                            <a href="{{ route('product.show', $product->id) }}" class="btn-details">Xem chi tiết</a>
                                            <button type="button" class="btn-add-cart" onclick="toggleCartForm(this)">Thêm vào giỏ</button>
                                        </div>
                                    </div>

                                    <!-- Form popup -->
                                    <form action="{{ route('cart.add') }}" method="POST" class="cart-form-popup">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                                        @if ($product->variants->count() > 1)
                                            <select name="product_variant_id" class="form-select form-select-sm mb-2" required>
                                                <option value="">-- Chọn phiên bản --</option>
                                                @foreach($product->variants as $variant)
                                                    <option value="{{ $variant->id }}">
                                                        {{ $variant->ram->value ?? '' }} /
                                                        {{ $variant->storage->value ?? '' }} /
                                                        {{ $variant->color->value ?? '' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @else
                                            <input type="hidden" name="product_variant_id" value="{{ $product->variants->first()->id ?? '' }}">
                                        @endif

                                        <div class="input-group input-group-sm mb-2 quantity-box">
                                            <button class="btn btn-outline-secondary" type="button" onclick="this.nextElementSibling.stepDown()">-</button>
                                            <input type="number" name="quantity" value="1" min="1" max="99" class="form-control text-center quantity-input">
                                            <button class="btn btn-outline-secondary" type="button" onclick="this.previousElementSibling.stepUp()">+</button>
                                        </div>

                                        <button type="submit" class="btn btn-primary btn-sm w-100">
                                            <i class="fa fa-cart-plus me-1"></i> Xác nhận
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-search"></i>
                            <h3>Không tìm thấy sản phẩm</h3>
                            <p>Hãy thử thay đổi từ khóa hoặc chọn danh mục khác</p>
                        </div>
                    @endif

                   @if($products->count() > 0)
    <div class="my-custom-pagination">
        {{ $products->withQueryString()->links() }}
    </div>
@endif

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleCartForm(btn) {
        const form = btn.closest('.product-card').querySelector('.cart-form-popup');
        form.classList.toggle('active');
    }
</script>
@endsection
