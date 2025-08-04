@extends('client.layouts.app')

@section('content')
<style>
    /* Modern Category Page Styles */
    .category-page {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 40px 0;
    }
    
    .category-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    
    .category-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .category-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        opacity: 0.3;
    }
    
    .category-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0;
        position: relative;
        z-index: 1;
    }
    
    .category-header p {
        font-size: 1.1rem;
        opacity: 0.9;
        margin: 10px 0 0 0;
        position: relative;
        z-index: 1;
    }
    
    .category-content-wrapper {
        display: flex;
        min-height: 600px;
    }
    
    .category-sidebar {
        width: 280px;
        background: #f8f9fa;
        border-right: 1px solid #e9ecef;
        padding: 0;
    }
    
    .sidebar-header {
        background: #343a40;
        color: white;
        padding: 20px;
        text-align: center;
        font-weight: 600;
        font-size: 1.1rem;
    }
    
    .category-sidebar .list-group {
        border: none;
        border-radius: 0;
    }
    
    .category-sidebar .list-group-item {
        border: none;
        border-bottom: 1px solid #e9ecef;
        padding: 16px 20px;
        font-weight: 500;
        font-size: 15px;
        transition: all 0.3s ease;
        position: relative;
        background: transparent;
    }
    
    .category-sidebar .list-group-item:last-child {
        border-bottom: none;
    }
    
    .category-sidebar .list-group-item:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        transform: translateX(5px);
    }
    
    .category-sidebar .list-group-item.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }
    
    .category-sidebar .list-group-item a {
        color: inherit;
        text-decoration: none;
        display: block;
        width: 100%;
    }
    
    .category-sidebar .list-group-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }
    
    .category-sidebar .list-group-item:hover::before,
    .category-sidebar .list-group-item.active::before {
        transform: scaleY(1);
    }
    
    .category-main {
        flex: 1;
        padding: 30px;
        background: white;
    }
    
    .category-toolbar {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        border: 1px solid #e9ecef;
    }
    
    .toolbar-row {
        display: flex;
        gap: 20px;
        align-items: center;
        flex-wrap: wrap;
    }
    
    .search-group {
        flex: 1;
        min-width: 300px;
        position: relative;
    }
    
    .search-group .form-control {
        height: 50px;
        border-radius: 25px;
        border: 2px solid #e9ecef;
        padding-left: 50px;
        font-size: 16px;
        transition: all 0.3s ease;
        background: white;
    }
    
    .search-group .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    
    .search-icon {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
        z-index: 10;
    }
    
    .sort-group {
        min-width: 200px;
    }
    
    .sort-group .form-control {
        height: 50px;
        border-radius: 25px;
        border: 2px solid #e9ecef;
        font-size: 16px;
        transition: all 0.3s ease;
        background: white;
    }
    
    .sort-group .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    
    .filter-btn {
        height: 50px;
        border-radius: 25px;
        padding: 0 30px;
        font-weight: 600;
        font-size: 16px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }
    
    .filter-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        color: white;
    }
    
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }
    
    .product-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: 1px solid #f1f3f4;
        position: relative;
    }
    
    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    }
    
    .product-image-container {
        position: relative;
        overflow: hidden;
        height: 250px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }
    
    .product-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .product-card:hover .product-image {
        transform: scale(1.05);
    }
    
    .product-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        z-index: 10;
    }
    
    .product-content {
        padding: 25px;
    }
    
    .product-title {
        font-size: 18px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 15px;
        line-height: 1.4;
        height: 50px;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }
    
    .product-price {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
    }
    
    .current-price {
        font-size: 22px;
        font-weight: 700;
        color: #e74c3c;
    }
    
    .old-price {
        font-size: 16px;
        color: #95a5a6;
        text-decoration: line-through;
        font-weight: 500;
    }
    
    .discount-badge {
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
        color: white;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .product-actions {
        display: flex;
        gap: 10px;
    }
    
    .btn-details {
        flex: 1;
        height: 45px;
        border-radius: 22px;
        font-weight: 600;
        font-size: 14px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        transition: all 0.3s ease;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .btn-details:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        color: white;
        text-decoration: none;
    }
    
    .btn-cart {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
        border: none;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        font-size: 18px;
    }
    
    .btn-cart:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 15px rgba(46, 204, 113, 0.4);
        color: white;
    }
    
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #6c757d;
    }
    
    .empty-state i {
        font-size: 4rem;
        color: #dee2e6;
        margin-bottom: 20px;
    }
    
    .empty-state h3 {
        font-size: 1.5rem;
        margin-bottom: 10px;
        color: #495057;
    }
    
    .empty-state p {
        font-size: 1.1rem;
        color: #6c757d;
    }
    
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: 40px;
    }
    
    .pagination .page-link {
        border-radius: 10px;
        margin: 0 5px;
        border: none;
        color: #667eea;
        font-weight: 600;
        padding: 12px 18px;
        transition: all 0.3s ease;
    }
    
    .pagination .page-link:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        transform: translateY(-2px);
    }
    
    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }
    
    /* Responsive Design */
    @media (max-width: 1200px) {
        .category-content-wrapper {
            flex-direction: column;
        }
        
        .category-sidebar {
            width: 100%;
            border-right: none;
            border-bottom: 1px solid #e9ecef;
        }
        
        .sidebar-header {
            display: none;
        }
        
        .category-sidebar .list-group {
            display: flex;
            overflow-x: auto;
            flex-wrap: nowrap;
            padding: 15px;
        }
        
        .category-sidebar .list-group-item {
            flex-shrink: 0;
            border: 1px solid #e9ecef;
            border-radius: 25px;
            margin-right: 10px;
            white-space: nowrap;
        }
        
        .category-sidebar .list-group-item:last-child {
            margin-right: 0;
        }
    }
    
    @media (max-width: 768px) {
        .category-header h1 {
            font-size: 2rem;
        }
        
        .toolbar-row {
            flex-direction: column;
            gap: 15px;
        }
        
        .search-group {
            min-width: 100%;
        }
        
        .products-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        
        .category-main {
            padding: 20px;
        }
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
                <div class="category-sidebar">
                    <div class="sidebar-header">
                        <i class="fas fa-th-large me-2"></i>
                        Danh mục sản phẩm
                    </div>
                    <ul class="list-group">
                        <li class="list-group-item {{ !$selectedCategory ? 'active' : '' }}">
                            <a href="{{ route('client.categories') }}">
                                <i class="fas fa-th me-2"></i>
                                Tất cả sản phẩm
                            </a>
                        </li>
                        @foreach($categories as $category)
                            <li class="list-group-item {{ $selectedCategory == $category->id ? 'active' : '' }}">
                                <a href="{{ route('client.categories.filter', $category->id) }}">
                                    <i class="fas fa-tag me-2"></i>
                                    {{ $category->category_name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="category-main">
                    <form method="GET" class="category-toolbar">
                        <div class="toolbar-row">
                            <div class="search-group">
                                <i class="fas fa-search search-icon"></i>
                                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm sản phẩm..." value="{{ request('search') }}">
                            </div>
                            <div class="sort-group">
                                <select name="sort_price" class="form-control" onchange="this.form.submit()">
                                    <option value="">Sắp xếp theo giá</option>
                                    <option value="asc" {{ request('sort_price') == 'asc' ? 'selected' : '' }}>Giá thấp đến cao</option>
                                    <option value="desc" {{ request('sort_price') == 'desc' ? 'selected' : '' }}>Giá cao đến thấp</option>
                                </select>
                            </div>
                            <button type="submit" class="btn filter-btn">
                                <i class="fas fa-filter me-2"></i>
                                Lọc
                            </button>
                        </div>
                    </form>

                    @if($products->count() > 0)
                        <div class="products-grid">
                            @foreach($products as $product)
                                <div class="product-card">
                                    <div class="product-image-container">
                                        @if($product->discount_price)
                                            <div class="product-badge">
                                                <i class="fas fa-tag me-1"></i>
                                                Giảm giá
                                            </div>
                                        @endif
                                        <img src="{{ asset('storage/' . $product->image) }}" class="product-image" alt="{{ $product->product_name }}">
                                    </div>
                                    <div class="product-content">
                                        <h5 class="product-title">{{ $product->product_name }}</h5>
                                        <div class="product-price">
                                            <span class="current-price">
                                                {{ number_format($product->discount_price ?? $product->price, 0, ',', '.') }} ₫
                                            </span>
                                            @if($product->discount_price)
                                                <span class="old-price">{{ number_format($product->price, 0, ',', '.') }} ₫</span>
                                                <span class="discount-badge">
                                                    -{{ number_format((($product->price - $product->discount_price) / $product->price) * 100, 0) }}%
                                                </span>
                                            @endif
                                        </div>
                                        <div class="product-actions">
                                            <a href="{{ route('product.show', $product->id) }}" class="btn-details">
                                                <i class="fas fa-eye me-2"></i>
                                                Xem chi tiết
                                            </a>
                                            <button class="btn-cart" title="Thêm vào giỏ hàng">
                                                <i class="fas fa-shopping-cart"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-search"></i>
                            <h3>Không tìm thấy sản phẩm</h3>
                            <p>Hãy thử thay đổi từ khóa tìm kiếm hoặc danh mục khác</p>
                        </div>
                    @endif

                    @if($products->count() > 0)
                        <div class="pagination-wrapper">
                            {{ $products->withQueryString()->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add to cart functionality
    const cartButtons = document.querySelectorAll('.btn-cart');
    cartButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Add animation
            this.style.transform = 'scale(0.9)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
            
            // Here you can add AJAX call to add item to cart
            // For now, just show a notification
            if (typeof toastr !== 'undefined') {
                toastr.success('Đã thêm sản phẩm vào giỏ hàng!');
            } else {
                alert('Đã thêm sản phẩm vào giỏ hàng!');
            }
        });
    });
    
    // Smooth scroll for category links
    const categoryLinks = document.querySelectorAll('.category-sidebar a');
    categoryLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Add loading state
            document.body.style.cursor = 'wait';
        });
    });
});
</script>
@endsection
