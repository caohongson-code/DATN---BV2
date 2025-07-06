<!DOCTYPE html>
<html lang="vi-VN" data-nhanh.vn-template="T0298">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ</title>

    <!-- Animation Style -->
    <style>
        .animateText {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 1s ease-out forwards;
            animation-delay: 0.3s;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        body {
            background: #f5f7fa;
            font-family: 'Segoe UI', sans-serif;
        }

        .section-newsHome,
        .section-collection {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            margin-top: 30px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        }

        .product-card {
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            text-align: center;
            padding: 15px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
        }

        .product-card img {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
        }

        .product-card h5 {
            font-size: 1rem;
            margin: 10px 0;
        }

        .btn-detail {
            display: inline-block;
            margin-top: 10px;
            padding: 6px 12px;
            background: #0d6efd;
            color: #fff;
            border-radius: 4px;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .btn-detail:hover {
            background: #0056b3;
        }
    </style>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('client/css/0wplpl_sAxn.css') }}">
    <link rel="stylesheet" href="{{ asset('client/css/appLib.css') }}">
    <link rel="stylesheet" href="{{ asset('client/css/bootstrap-3.3.5.min.css') }}">

    <link rel="stylesheet" href="{{ asset('client/css/flipclock.css') }}">
    <link rel="stylesheet" href="{{ asset('client/css/font-awesome-4.7.0.min.css') }}">
    <link rel="stylesheet" href="{{ asset('client/css/jquery.fancybox.css') }}">
    <link rel="stylesheet" href="{{ asset('client/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('client/css/mycss.css') }}">
    <link rel="stylesheet" href="{{ asset('client/css/n3cFsplx3-W.css') }}">
    <link rel="stylesheet" href="{{ asset('client/css/Nc0-ch9ANgu.css') }}">
    <link rel="stylesheet" href="{{ asset('client/css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('client/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('client/css/Ye5WURwlpsv.css') }}">
    <link rel="stylesheet" href="{{ asset('client/css/zh-SzDq3GxE.css') }}">

</head>

<body class="tp_background tp_text_color">
    <input type="hidden" value="53724" id="storeId">

    <div id="root">
        <!-- Topbar -->
        <div class="fixed_scroll">
            <div id="topbar" class="clearfix hidden-xs tp_header">
                <div class="container-fluid clearfix topbar-top">
                    <div class="no-padding col-xs-12 col-sm-8 col-md-6 hidden-xs top_bar_left">
                        <div class="innerTopLeft">
                            <ul>
                                <li>
                                    <a aria-label="hotline" class="btnHL" href="tel:19002812">
                                        <span class="iconTop icon-1-top"></span>
                                        <span class="title-info-top"><b>Cao đẳng FPT POLYTECHNIC</b></span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="no-padding col-xs-12 col-sm-4 col-md-6 top_bar_right">
                        <div class="innerTopRight">
                            <ul>
                                <li id="cart_header_top">
                                    <span id="site-cart-handle" class="icon-cart" title="Giỏ hàng">
                                        <a href="{{ url('/cart') }}" class="count-holder">
                                            <span class="iconTop icon-5-top"></span>
                                            <span class="title-info-top">
                                                <i class="">Giỏ Hàng</i>
                                                @php
                                                    $cartCount = 0;
                                                    if (Auth::check()) {
                                                        $cart = \App\Models\Cart::with('details')
                                                            ->where('account_id', Auth::id())
                                                            ->where('cart_status_id', 1)
                                                            ->first();
                                                        if ($cart) {
                                                            $cartCount = $cart->details->sum('quantity');
                                                        }
                                                    }
                                                @endphp

                                                <span class="count">({{ $cartCount }})</span>

                                            </span>
                                        </a>
                                    </span>
                                </li>

                                @php
                                    $user = auth()->user();
                                @endphp

                                @if ($user)
                                    <li class="dropdown">
                                        <a href="{{ route('user.dashboard') }}">
                                            Xin chào, {{ Auth::user()->name ?? 'Khách' }}
                                        </a>

                                        <ul class="dropdown-menu" style="padding: 10px;">
                                            <li>
                                                <form action="{{ route('taikhoan.logout') }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-link p-0 m-0 text-danger"
                                                        style="text-decoration: none;">
                                                        <i class="fa fa-sign-out"></i> Đăng xuất
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </li>
                                @else
                                    <li>
                                        <a href="{{ route('login') }}">
                                            <span class="iconTop icon-4-top"></span>
                                            <span class="title-info-top user_tk">Đăng nhập</span>
                                        </a>
                                    </li>
                                @endif

                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Header -->
            <div class="outerHeightHeader" style="min-height: 80px;">
                <header id="site-header" class="main-header clearfix tp_header">
                    <div class="no-padding hidden-xs col-sm-12 col-md-2">
                        <div class="header-mid wrap-flex-align">
                            <div class="wrap-logo">
                                <a href="{{ route('home') }}">
                                    <img src="{{ asset('client/img/anh1.png') }}" alt="Logo"
                                        style="width: 300px; height: auto;" class="logoimg">
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Menu -->
                    <div class="no-padding col-xs-12 hidden-sm hidden-xs col-md-8 menu-pc">
                        <div class="menu-desktop">
                            <div class="wrap-logo wrap-logp-mb">
                                <a href="{{ route('home') }}">
                                    <img src="{{ asset('client/img/anh1.png') }}" width="150" height="23"
                                        alt="logo" class="img-responsive logoimg">
                                </a>
                            </div>
                            <div id="nav">
                                <nav class="main-nav tp_menu text-center">
                                    <ul class="clearfix">
                                        <li class="li-menu"><a class="tp_menu_item" href="#">Trang chủ</a></li>
                                        <li class="li-menu"><a class="tp_menu_item" href="#">Điện thoại</a>
                                        </li>
                                        <li class="li-menu"><a class="tp_menu_item" href="#">Máy tính bảng</a>
                                        </li>
                                        <li class="li-menu"><a class="tp_menu_item" href="#">Phụ kiện</a></li>
                                        <li class="li-menu"><a class="tp_menu_item" href="#">Khuyến mãi</a>
                                        </li>
                                        <li class="li-menu"><a class="tp_menu_item" href="#">Hỗ trợ</a></li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>

                    <!-- Search -->
                    <div class="no-padding col-xs-12 hidden-sm hidden-xs col-md-2">
                        <div class="searchFormHeader">
                            <form class="searchHeader searchDesktop" action="{{ url('/search') }}" method="get">
                                <input type="text" name="q" class="searchInput" placeholder="Tìm kiếm..."
                                    autocomplete="off">
                                <input type="submit" class="btnSearch" value="Tìm">
                            </form>
                            <div id="resultSearchDesktop" class="resultDesktop">
                                <div class="innerResultDesktop"></div>
                            </div>
                        </div>
                    </div>
                </header>
            </div>
        </div>

        <!-- Banner -->
        <main class="main-index">
            <div class="styleArrowOwl">
                <div class="trackingBannerHome1 slider-banner-main owl-carousel owl-theme tp_banner_main">
                    <div class="owl-stage-outer">
                        <div class="owl-stage owl-grab">
                            <div class="owl-item active">
                                <a href="#">
                                    <img src="{{ asset('client/img/anh2.jpg') }}" alt="Banner điện thoại"
                                        width="1520" height="340">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Best Seller Section (ẩn mặc định) -->
        <section class="clearfix section bestseller-pro section-collection tp_product_betseller"
            style="display: none;">
            <div class="sectionTitleTab clearfix">
                {{-- Nội dung sản phẩm bán chạy --}}
            </div>
        </section>

        <!-- Tin tức -->
        <section class="sectionTitleTab clearfix section-newsHome">
            {{-- Nội dung tin tức --}}
        </section>
    </div>

     <!-- JS bắt buộc -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('client/js/bootstrap-3.3.5.min.js') }}"></script>

    <!-- Các script khác -->
    <script defer src="{{ asset('client/js/slick.js') }}"></script>
    <script defer src="{{ asset('client/js/flipclock.js') }}"></script>
    <script defer src="{{ asset('client/js/promotion.js') }}"></script>
    <script defer src="{{ asset('client/js/index.js') }}"></script>

    <!-- Scripts đẩy từ các view con -->
    @stack('scripts')
    @yield('scripts')
</body>

</html>
