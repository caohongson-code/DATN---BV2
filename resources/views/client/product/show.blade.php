@extends('client.layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Tổng thể container sản phẩm */
        .container.my-5 {
            background: #fff;
            padding: 32px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
            font-family: 'Segoe UI', sans-serif;
        }

        /* Ảnh sản phẩm chính */

        #mainImageWrapper {
            width: 100%;
            height: 420px;
            overflow: hidden;
            border-radius: 12px;

            border: 1px solid #e0e0e0;
            box-shadow: inset 0 0 4px rgba(0, 0, 0, 0.05);

        }

        #mainImage {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        #mainImageWrapper:hover #mainImage {
            transform: scale(1.05);

        }

        /* Tiêu đề sản phẩm */
        .product-title {

            margin-bottom: 16px;
            font-size: 24px;
            font-weight: 600;
            color: #1a1a1a;

        }

        /* Giá sản phẩm */
        .product-price {
            margin-bottom: 16px;
            font-size: 20px;
            color: #d70018;
        }

        .product-price s {
            color: #999;
            font-size: 16px;

        }

        /* Bảng thông số kỹ thuật */
        .table.table-sm th {
            width: 100px;
            background: #f8f9fa;
            font-weight: 500;
        }

        .table.table-sm td {
            background: #fff;
        }

        .table.table-sm {
            border-radius: 8px;
            overflow: hidden;
        }

        /* Phiên bản sản phẩm dạng chip */
        .variant-option {
            padding: 8px 14px;
            border-radius: 20px;
            border: 1px solid #ccc;
            background-color: #f8f9fa;
            font-size: 14px;
            transition: all 0.3s;
            min-width: 80px;
            text-align: center;
            cursor: pointer;
        }

        .variant-option:hover {
            background-color: #e0f0ff;
            border-color: #1a73e8;
            color: #1a73e8;
        }

        .variant-option.active {
            background-color: #1a73e8;
            color: #fff;
            border-color: #1a73e8;
        }

        /* Nút số lượng */
        .input-group input {
            font-size: 16px;
            font-weight: 600;
        }

        /* Nút hành động chính */
        button.btn-primary,
        button.btn-success {
            padding: 12px 24px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 8px;
            min-width: 160px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08);
        }

        .btn-primary {
            background-color: #d70018;
            border: none;
            color: #fff;
        }

        .btn-primary:hover {
            background-color: #b30014;
        }

        .btn-success {
            background-color: #28a745;
            border: none;
            color: #fff;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        /* Phần mô tả */
        .bg-light.p-3 {
            background-color: #f8f9fa !important;
            border-left: 4px solid #1a73e8;
            padding: 20px;
            line-height: 1.6;
            border-radius: 8px;
        }

        /* Form đánh giá */
        form select.form-select,
        form textarea.form-control {
            border-radius: 8px;
            font-size: 14px;
        }

        /* Card sản phẩm liên quan */
        .card.h-100 {
            transition: 0.3s ease;
            border-radius: 12px;
        }

        .card.h-100:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }

        .card-img-top {
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            transition: 0.3s;
        }

        .card-img-top:hover {
            transform: scale(1.03);
        }

        .card-title a {
            font-size: 15px;
            font-weight: 500;
            color: #000;
            text-decoration: none;
        }

        /* Banner khuyến mãi cố định */
        .promo-fixed {
            position: fixed;
            right: 20px;
            bottom: 100px;
            width: 240px;
            background: linear-gradient(135deg, #fff9d6, #ffe8b3);
            border: 2px solid #ffc107;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            padding: 16px;
            z-index: 9999;
            animation: fadeInUp 0.8s ease;
        }

        .promo-fixed .promo-content {
            font-family: 'Segoe UI', sans-serif;
        }

        .promo-fixed h6 {
            margin: 8px 0;
            font-size: 18px;
        }

        .promo-fixed .btn-warning {
            border-radius: 8px;
        }

        @keyframes fadeInUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Responsive cho mobile */
        @media (max-width: 768px) {
            .product-title {
                font-size: 20px;
            }

            .product-price {
                font-size: 18px;
            }

            .variant-option {
                font-size: 13px;
                padding: 6px 12px;
                min-width: 70px;
            }

            .btn-primary,
            .btn-success {
                width: 100%;
            }

            .promo-fixed {
                display: none;
            }
        }

        #albumWrapper {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
        }

        .variant-album-img-wrapper {
            width: 70px;
            height: 70px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            cursor: pointer;
            display: none;
            transition: transform 0.3s;
        }

        .variant-album-img-wrapper:hover {
            transform: scale(1.05);
        }

        .variant-album-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .promo-fixed {
            position: fixed;
            width: 200px;
            /* Chiều rộng thống nhất */
            height: 300px;
            /* Chiều cao thống nhất */
            z-index: 9999;
            padding: 0;
            background: none;
            border: none;
            box-shadow: none;
        }

        .promo-left {
            left: 20px;
            bottom: 100px;
        }

        .promo-right {
            right: 20px;
            bottom: 100px;
        }

        .promo-fixed img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* Cắt đều, không méo, tràn khung */
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s;
        }

        .promo-fixed img:hover {
            transform: scale(1.05);
        }

        @media (max-width: 768px) {

            .promo-left,
            .promo-right {
                display: none;
            }
        }

        .product-price-main {
            font-size: 30px;
            color: #e11d48;
            font-weight: 670;
            display: inline-block;
            margin-right: 8px;
        }

        .product-price-old {
            font-size: 17px;
            color: #888;
            text-decoration: line-through;
            display: inline-block;
            margin-left: 2px;
            vertical-align: middle;
        }

        //bình luân đánh giá
        /* Container tổng */
        .review-container {
            max-width: 900px;
            margin: 0 auto;
        }

        /* Tab buttons */
        .nav-tabs .nav-link {
            font-weight: 600;
            font-size: 1.1rem;
            padding: 0.6rem 1.3rem;
            border-radius: 0.5rem 0.5rem 0 0;
            transition: background-color 0.3s ease;
        }

        .nav-tabs .nav-link.active {
            background-color: #0d6efd;
            color: #fff;
            font-weight: 700;
            box-shadow: 0 4px 8px rgb(13 110 253 / 0.3);
        }

        /* List group items (bình luận, đánh giá) */
        .list-group-item {
            background-color: #f8f9fa;
            border: none;
            padding: 1.25rem 1.5rem;
            box-shadow: 0 1px 4px rgb(0 0 0 / 0.05);
            border-radius: 0.6rem;
            transition: box-shadow 0.3s ease;
        }

        .list-group-item:hover {
            box-shadow: 0 4px 12px rgb(0 0 0 / 0.1);
        }

        /* Tên người dùng và thời gian */
        .list-group-item .d-flex strong {
            font-size: 1.1rem;
            color: #212529;
        }

        .list-group-item .d-flex small {
            font-size: 0.85rem;
            color: #6c757d;
        }

        /* Nội dung bình luận / đánh giá */
        .list-group-item p {
            font-size: 1rem;
            line-height: 1.4;
            color: #343a40;
        }

        /* Ảnh thumbnail */
        .img-thumbnail {
            margin-top: 0.5rem;
            border-radius: 0.5rem;
            object-fit: cover;
        }

        /* Đánh giá sao */
        .text-danger.fs-5 {
            font-size: 1.3rem !important;
        }

        .text-muted {
            font-size: 1.3rem;
        }

        /* Biến thể sản phẩm */
        .mb-2>.fw-semibold {
            color: #495057;
        }

        /* Form bình luận */
        form.mt-4 {
            background-color: #fefefe;
            padding: 1.5rem;
            border-radius: 0.6rem;
            box-shadow: 0 2px 6px rgb(0 0 0 / 0.1);
        }

        form.mt-4 label {
            font-weight: 600;
            color: #495057;
        }

        form.mt-4 textarea,
        form.mt-4 input[type="file"] {
            border-radius: 0.4rem;
            border: 1px solid #ced4da;
        }

        form.mt-4 button[type="submit"] {
            font-weight: 600;
            font-size: 1rem;
            border-radius: 0.4rem;
        }

        /* Chữ ghi chú (chưa có bình luận / đánh giá) */
        .text-muted.fst-italic {
            font-size: 1rem;
            color: #6c757d;
            font-style: italic;
            margin-top: 1rem;
        }

        /* Responsive nhỏ */
        @media (max-width: 576px) {
            .nav-tabs .nav-link {
                font-size: 1rem;
                padding: 0.5rem 1rem;
            }

            .list-group-item p {
                font-size: 0.95rem;
            }
        }

        /* Gọn toàn bộ item bình luận/đánh giá */
        .list-group-item {
            padding: 10px 15px;
            font-size: 14px;
            line-height: 1.4;
        }

        /* Tên người dùng và thời gian */
        .list-group-item strong {
            font-size: 14px;
            color: #333;
        }

        .list-group-item small.text-muted {
            font-size: 13px;
            color: #888;
        }

        /* Nội dung bình luận/đánh giá */
        .list-group-item p {
            font-size: 13px;
            margin-bottom: 5px;
        }

        /* Ảnh trong bình luận/đánh giá */
        .list-group-item img {
            max-width: 120px;
            max-height: 120px;
            border-radius: 5px;
        }

        /* Ngày tháng rõ ràng hơn */
        .date-time {
            font-weight: 500;
            color: #555;
            font-size: 13px;
        }

        /* Thêm khoảng cách giữa các item */
        .list-group-item+.list-group-item {
            margin-top: 5px;
        }
    </style>

    <div class="container my-5">
        <div class="row g-4">
            <div class="col-md-5">
                <div id="mainImageWrapper">
                    <img id="mainImage" src="{{ asset('storage/' . $product->image) }}" class="img-fluid"
                        alt="{{ $product->product_name }}">
                </div>

                <div id="albumWrapper" class="d-flex flex-wrap gap-2 mt-3">
                    @foreach ($product->variants as $variant)
                        @foreach ($variant->images as $img)
                            <div class="variant-album-img-wrapper" data-variant="{{ $variant->id }}"
                                style="display: none;">
                                <img src="{{ asset('storage/' . $img->image) }}" alt="Ảnh phụ" class="variant-album-img"
                                    data-image="{{ asset('storage/' . $img->image) }}">
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>



            <div class="col-md-7">
                <h2 class="fw-bold product-title">{{ $product->product_name }}</h2>

                <div class="product-price">
                    <strong class="d-block text-muted mb-1">Giá:</strong>
                    <div id="priceBlock" class="d-flex flex-column align-items-start">
                        @if ($product->discount_price)
                            <span class="product-price-main">{{ number_format($product->discount_price, 0, ',', '.') }}
                                đ</span>
                            <span class="product-price-old">{{ number_format($product->price, 0, ',', '.') }} đ</span>
                        @else
                            <span class="product-price-main">{{ number_format($product->price, 0, ',', '.') }} đ</span>
                        @endif
                    </div>
                </div>

                <div class="mb-3">
                    <strong class="d-block mb-1">Số lượng còn:</strong>
                    <span id="stock" class="fs-6">{{ $product->quantity }}</span>
                </div>

                <table class="table table-bordered table-sm w-75">
                    <tbody>
                        <tr>
                            <th>RAM</th>
                            <td id="ram">-</td>
                        </tr>
                        <tr>
                            <th>Lưu trữ</th>
                            <td id="storage">-</td>
                        </tr>
                        <tr>
                            <th>Màu</th>
                            <td id="color">-</td>
                        </tr>
                    </tbody>
                </table>

                @foreach ($product->variants as $variant)
                    <button type="button" class="btn btn-outline-secondary btn-sm variant-option"
                        data-id="{{ $variant->id }}"
                        data-image="{{ asset('storage/' . ($variant->image ?? $product->image)) }}"
                        data-price="{{ $variant->price }}" data-discount-price="{{ $variant->discount_price }}"
                        data-ram="{{ $variant->ram->value ?? '-' }}" data-storage="{{ $variant->storage->value ?? '-' }}"
                        data-color="{{ $variant->color->value ?? '-' }}" data-quantity="{{ $variant->quantity }}">
                        {{ $variant->ram->value ?? '?' }} / {{ $variant->storage->value ?? '?' }} /
                        {{ $variant->color->value ?? '?' }}
                    </button>
                @endforeach
            </div>

            <div class="mt-4 d-flex gap-3 align-items-end">
                <form action="{{ route('cart.add') }}" method="POST" id="addToCartForm">

                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="product_variant_id" id="addToCartVariantId">
                    <div class="input-group" ;>
                        <button class="btn btn-outline-secondary" type="button" onclick="changeQty(-1)">-</button>
                        <input type="number" name="quantity" id="quantityInput" value="1" min="1"
                            max="{{ $product->quantity }}" class="form-control text-center">
                        <button class="btn btn-outline-secondary" type="button" onclick="changeQty(1)">+</button>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">
                        <i class="fa fa-cart-plus"></i> Thêm vào giỏ hàng
                    </button>
                </form>


                <form action="{{ route('cart.buyNow') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="variant_id" id="selectedVariantId">
                    <input type="hidden" name="quantity" id="buyNowQuantity">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-bolt"></i> Mua ngay
                    </button>
                </form>
            </div>
        </div>
    </div>

    <hr class="my-5">
    <h4 class="fw-bold mb-3">Mô tả chi tiết</h4>
    <pre class="bg-light p-3 rounded" style="white-space: pre-wrap; font-family: inherit;">
    {!! $product->description ?? 'Đang cập nhật...' !!}
</pre>


   <hr class="my-5">

@php
    $user = auth()->user();
    // Lọc dữ liệu
    $comments = $comments->filter(fn($c) => !empty($c->comment));
    $ratings  = $reviews->filter(fn($r) => !empty($r->rating));

    // Giới hạn mặc định
    $limit = $limit ?? 6;
    $totalComments = $totalComments ?? $comments->count();

    $ratingLimit = request('rating_limit', 6);
    $totalRatings = $ratings->count();
    $displayRatings = $ratings->sortByDesc('created_at')->take($ratingLimit);
@endphp

<ul class="nav nav-tabs" id="reviewTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="comments-tab" data-bs-toggle="tab" data-bs-target="#comments"
            type="button" role="tab" aria-controls="comments" aria-selected="true">
            Bình luận ({{ $totalComments }})
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="ratings-tab" data-bs-toggle="tab" data-bs-target="#ratings"
            type="button" role="tab" aria-controls="ratings" aria-selected="false">
            Đánh giá ({{ $totalRatings }})
        </button>
    </li>
</ul>

<div class="tab-content mt-4" id="reviewTabContent">
    {{-- Tab Bình luận --}}
    <div class="tab-pane fade show active" id="comments" role="tabpanel">
        <h5 class="mb-4">Danh sách bình luận</h5>

        @if ($comments->count() > 0)
            <ul class="list-group">
                @foreach ($comments as $comment)
                    <li class="list-group-item mb-3 rounded shadow-sm p-3">
                        <div class="d-flex justify-content-between mb-2">
                            <strong class="text-primary">{{ $comment->account->full_name ?? 'Người dùng ẩn danh' }}</strong>
                            <small class="text-muted">
                                {{ $comment->created_at->format('H:i d/m/Y') }}
                            </small>
                        </div>
                        <p class="mb-2" style="font-size: 0.95rem;">{{ $comment->comment }}</p>

                        @if ($comment->image)
                            <img src="{{ asset('storage/' . $comment->image) }}" alt="Ảnh bình luận"
                                class="img-thumbnail" style="max-width:130px; max-height:130px;" />
                        @endif
                    </li>
                @endforeach
            </ul>

            {{-- Nút xem thêm / ẩn bớt --}}
            <div class="text-center mt-3">
                @if ($totalComments > $limit)
                    <a href="{{ request()->fullUrlWithQuery(['limit' => $limit + 6]) }}"
                        class="btn btn-sm btn-outline-primary">
                        Xem thêm
                    </a>
                @endif
                @if ($limit > 6)
                    <a href="{{ request()->fullUrlWithQuery(['limit' => 6]) }}"
                        class="btn btn-sm btn-outline-secondary">
                        Ẩn bớt
                    </a>
                @endif
            </div>
        @else
            <p class="text-muted fst-italic">Chưa có bình luận nào.</p>
        @endif

        <hr>

        {{-- Form gửi bình luận --}}
        @if ($user)
            <form action="{{ route('client.comments.store') }}" method="POST" enctype="multipart/form-data" class="mt-4">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nội dung bình luận:</label>
                    <textarea name="comment" rows="4" class="form-control" placeholder="Viết bình luận của bạn..." required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Ảnh đính kèm (nếu có):</label>
                    <input type="file" name="image" accept="image/*" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary px-4">Gửi bình luận</button>
            </form>
        @else
            <p class="mt-4">Vui lòng <a href="{{ route('taikhoan.login') }}">đăng nhập</a> để bình luận.</p>
        @endif
    </div>

    {{-- Tab Đánh giá --}}
    <div class="tab-pane fade" id="ratings" role="tabpanel">
        @if ($totalRatings > 0)
            <ul class="list-group">
                @foreach ($displayRatings as $rating)
                    <li class="list-group-item mb-3 rounded shadow-sm p-3">
                        <div class="d-flex justify-content-between mb-2">
                            <strong class="text-success">{{ $rating->account->full_name ?? 'Người dùng ẩn danh' }}</strong>
                            <small class="text-muted">
                                {{ $rating->created_at->format('H:i d/m/Y') }}
                            </small>
                        </div>

                        <div class="mb-2">
                            <span class="fw-semibold">Sản phẩm:</span>
                            {{ $rating->product->product_name ?? 'N/A' }}
                            <span class="ms-3">
                                {{ $rating->variant->ram->value ?? '' }} /
                                {{ $rating->variant->storage->value ?? '' }} /
                                {{ $rating->variant->color->value ?? '' }}
                            </span>
                        </div>

                        <div class="mb-2">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $rating->rating)
                                    <span class="text-warning">&#9733;</span>
                                @else
                                    <span class="text-muted">&#9733;</span>
                                @endif
                            @endfor
                            <span class="ms-2 fw-bold">{{ $rating->rating }} sao</span>
                        </div>

                        @if (!empty($rating->comment))
                            <p class="mb-2" style="font-size: 0.95rem;">
                                <strong>Nội dung:</strong> {{ $rating->comment }}
                            </p>
                        @endif

                        @if (!empty($rating->image))
                            <img src="{{ asset('storage/' . $rating->image) }}" alt="Ảnh đánh giá"
                                class="img-thumbnail" style="max-width:130px; max-height:130px;" />
                        @endif
                    </li>
                @endforeach
            </ul>

            {{-- Nút xem thêm / ẩn bớt --}}
            <div class="text-center mt-3">
                @if ($totalRatings > $ratingLimit)
                    <a href="{{ request()->fullUrlWithQuery(['rating_limit' => $ratingLimit + 6]) }}"
                        class="btn btn-sm btn-outline-primary">
                        Xem thêm
                    </a>
                @endif
                @if ($ratingLimit > 6)
                    <a href="{{ request()->fullUrlWithQuery(['rating_limit' => 6]) }}"
                        class="btn btn-sm btn-outline-secondary">
                        Ẩn bớt
                    </a>
                @endif
            </div>
        @else
            <p class="text-muted fst-italic">Chưa có đánh giá nào.</p>
        @endif
    </div>
</div>


        @if ($relatedProducts->count())
            <hr class="my-5">
            <h4 class="fw-bold mb-4">Sản phẩm liên quan</h4>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
                @foreach ($relatedProducts as $item)
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm">
                            <a href="{{ route('product.show', $item->id) }}">
                                <img src="{{ asset('storage/' . $item->image) }}" class="card-img-top"
                                    alt="{{ $item->product_name }}" style="height: 200px; object-fit: cover;">
                            </a>
                            <div class="card-body p-2">
                                <h6 class="card-title mb-1">
                                    <a href="{{ route('product.show', $item->id) }}"
                                        class="text-dark text-decoration-none">{{ $item->product_name }}</a>
                                </h6>
                                <p class="mb-0 text-danger fw-semibold">
                                    @if ($item->discount_price)
                                        {{ number_format($item->discount_price, 0, ',', '.') }} đ
                                        <small class="text-muted text-decoration-line-through d-block">
                                            {{ number_format($item->price, 0, ',', '.') }} đ
                                        </small>
                                    @else
                                        {{ number_format($item->price, 0, ',', '.') }} đ
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Nội dung trang sản phẩm giữ nguyên như bạn đã có --}}

    <div class="container my-5">
        {{-- Nội dung chi tiết sản phẩm, mô tả, đánh giá, sản phẩm liên quan... --}}
        {{-- Mình không lặp lại để tránh quá dài, bạn giữ nguyên nội dung sản phẩm như trước --}}




        {{-- Nút gọi nhanh cố định --}}
        <div class="call-fixed">
            <a href="tel:0123456789" class="btn btn-success shadow">
                <i class="fa fa-phone" style="font-size: 24px; color: #fff;"></i>
            </a>
        </div>
        <!-- Banner bên trái -->
        <div class="promo-fixed promo-left">
            <a href="#">
                <img src="https://png.pngtree.com/template/20200517/ourlarge/pngtree-summer-sale-banner-promotion-template-in-portrait-position-with-bright-design-image_372761.jpg"
                    alt="Summer Sale">
            </a>
        </div>

        <!-- Banner bên phải -->
        <div class="promo-fixed promo-right">
            <a href="#">
                <img src="https://img.pikbest.com/origin/09/06/37/13NpIkbEsTGT5.jpg!w700wp" alt="Flash Sale">
            </a>
        </div>
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1080">
            <div id="marketingToast" class="toast align-items-center text-white bg-primary border-0" role="alert"
                aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        🎁 Đăng ký tài khoản để nhận mã giảm giá 10% cho đơn hàng đầu tiên!
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>

    @endsection
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const qtyInput = document.getElementById('quantityInput');
                const buyNowQty = document.getElementById('buyNowQuantity');
                const cartQty = document.getElementById('cartQuantity');
                const variantButtons = document.querySelectorAll('.variant-option');
                const selectedVariantInput = document.getElementById('selectedVariantId');
                const addToCartVariantInput = document.getElementById('addToCartVariantId');
                const buyNowForm = document.querySelector('form[action="{{ route('cart.buyNow') }}"]');
                const addToCartForm = document.getElementById('addToCartForm');
                const albumImages = document.querySelectorAll('.variant-album-img-wrapper');
                const mainImage = document.getElementById('mainImage');

                function syncQty() {
                    let value = parseInt(qtyInput.value) || 1;
                    if (value < 1) value = 1;
                    const max = parseInt(qtyInput.max) || 9999;
                    if (value > max - 1) value = max - 1;
                    qtyInput.value = value;
                    buyNowQty.value = value;
                    if (cartQty) cartQty.value = value;
                }

                window.changeQty = function(change) {
                    qtyInput.value = parseInt(qtyInput.value || 1) + change;
                    syncQty();
                };

                qtyInput.addEventListener('input', syncQty);
                syncQty();

                // Handle variant button click
                variantButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const variantId = this.dataset.id;
                        const price = parseInt(this.dataset.price || 0);
                        const discountPrice = parseInt(this.dataset.discountPrice || 0);
                        const ram = this.dataset.ram || '-';
                        const storage = this.dataset.storage || '-';
                        const color = this.dataset.color || '-';
                        const quantity = parseInt(this.dataset.quantity || 0);

                        // Cập nhật hình ảnh chính
                        mainImage.src = this.dataset.image;

                        // Cập nhật block giá
                        const priceBlock = document.getElementById('priceBlock');
                        if (discountPrice && discountPrice < price) {
                            priceBlock.innerHTML = `
                    <span class="product-price-main text-danger">${discountPrice.toLocaleString('vi-VN')} đ</span>
                    <span class="product-price-old text-muted text-decoration-line-through">${price.toLocaleString('vi-VN')} đ</span>
                `;
                        } else {
                            priceBlock.innerHTML = `
                    <span class="product-price-main text-danger">${price.toLocaleString('vi-VN')} đ</span>
                `;
                        }

                        // Cập nhật thông tin khác
                        document.getElementById('ram').innerText = ram;
                        document.getElementById('storage').innerText = storage;
                        document.getElementById('color').innerText = color;
                        document.getElementById('stock').innerText = quantity;
                        qtyInput.max = quantity;
                        qtyInput.value = 1;
                        buyNowQty.value = 1;

                        selectedVariantInput.value = variantId;
                        addToCartVariantInput.value = variantId;

                        // Active class
                        variantButtons.forEach(btn => btn.classList.remove('active', 'btn-primary'));
                        this.classList.add('active', 'btn-primary');

                        // Hiển thị ảnh phụ theo biến thể
                        albumImages.forEach(img => {
                            img.style.display = img.dataset.variant === variantId ? 'block' :
                                'none';
                        });
                    });
                });

                // Click ảnh nhỏ → đổi ảnh lớn
                document.addEventListener('click', function(e) {
                    if (e.target.classList.contains('variant-album-img')) {
                        mainImage.src = e.target.dataset.image;
                    }
                });

                // Validate khi chưa chọn phiên bản
                buyNowForm.addEventListener('submit', function(e) {
                    if (!selectedVariantInput.value) {
                        e.preventDefault();
                        alert('Vui lòng chọn phiên bản trước khi mua ngay.');
                    }
                });

                addToCartForm.addEventListener('submit', function(e) {
                    if (!addToCartVariantInput.value) {
                        e.preventDefault();
                        alert('Vui lòng chọn phiên bản trước khi thêm vào giỏ hàng.');
                    }
                });

                // Thông báo marketing popup nhẹ
                setTimeout(() => {
                    const toastEl = document.getElementById('marketingToast');
                    const toast = new bootstrap.Toast(toastEl);
                    toast.show();
                }, 4000);

            });
        </script>
    @endpush
