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

{{-- <h2 class="lineHeight05"><a href="#" class="tp_title">Tin tức điện thoại</a></h2>
</div>

<div class="sectionContentTab styleArrowSlick">
    <div class="news-lists-home owl-carousel owl-theme owl-loaded">
        <div class="owl-stage-outer">
            <div class="owl-stage" style="transform: translate3d(0px, 0px, 0px); transition: all; width: 1405px;">
                
                <div class="owl-item active" style="width: 341.25px; margin-right: 10px;">
                    <div class="">
                        <div class="news-img">
                            <a href="#">
                                <img loading="lazy" src="client/img/anh3.jpg" alt="iPhone 15 chính thức ra mắt">
                            </a>
                        </div>
                        <div class="news-detail clearfix">
                            <div class="box-news-detail">
                                <h3 class="news-title tp_title">
                                    <a href="#">iPhone 15 chính thức ra mắt với chip A17 Bionic</a>
                                </h3>
                                <div class="news-desc">Apple ra mắt iPhone 15 với thiết kế mới, camera cải tiến và hiệu năng vượt trội nhờ chip A17 Bionic.</div>
                                <div class="news-btn">
                                    <a href="#" class="text-danger">Xem thêm</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="owl-item active" style="width: 341.25px; margin-right: 10px;">
                    <div class="">
                        <div class="news-img">
                            <a href="#">
                                <img loading="lazy" src="client/img/anh7.webp" alt="Samsung ra mắt Galaxy S25 Ultra">
                            </a>
                        </div>
                        <div class="news-detail clearfix">
                            <div class="box-news-detail">
                                <h3 class="news-title tp_title">
                                    <a href="#">Samsung Galaxy S25 Ultra lộ diện với camera 200MP</a>
                                </h3>
                                <div class="news-desc">Galaxy S25 Ultra hứa hẹn tạo nên đột phá với công nghệ chụp ảnh chuyên nghiệp và pin dung lượng lớn.</div>
                                <div class="news-btn">
                                    <a href="#" class="text-danger">Xem thêm</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="owl-item active" style="width: 341.25px; margin-right: 10px;">
                    <div class="">
                        <div class="news-img">
                            <a href="#">
                                <img loading="lazy"src="client/img/anh8.webp" alt="So sánh Xiaomi và Realme">
                            </a>
                        </div>
                        <div class="news-detail clearfix">
                            <div class="box-news-detail">
                                <h3 class="news-title tp_title">
                                    <a href="#">So sánh Xiaomi 14 và Realme GT6: Đâu là lựa chọn tốt?</a>
                                </h3>
                                <div class="news-desc">Hai mẫu flagship giá rẻ nổi bật nhất hiện nay: hiệu năng, camera và thời lượng pin – ai sẽ chiến thắng?</div>
                                <div class="news-btn">
                                    <a href="#" class="text-danger">Xem thêm</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="owl-item active" style="width: 341.25px; margin-right: 10px;">
                    <div class="">
                        <div class="news-img">
                            <a href="#">
                                <img loading="lazy" src="client/img/anh6.jpg" alt="Top điện thoại giá rẻ">
                            </a>
                        </div>
                        <div class="news-detail clearfix">
                            <div class="box-news-detail">
                                <h3 class="news-title tp_title">
                                    <a href="#">Top 5 điện thoại dưới 5 triệu đáng mua nhất 2025</a>
                                </h3>
                                <div class="news-desc">Danh sách những chiếc điện thoại giá rẻ nhưng vẫn mang lại hiệu năng mạnh mẽ và thiết kế đẹp mắt.</div>
                                <div class="news-btn">
                                    <a href="#" class="text-danger">Xem thêm</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="owl-controls">
            <div class="owl-nav">
                <div class="owl-prev" style="display: none;"><i class="fa fa-chevron-left"></i></div>
                <div class="owl-next" style="display: none;"><i class="fa fa-chevron-right"></i></div>
            </div>
            <div class="owl-dots" style="display: none;"></div>
        </div>
    </div>
</div> --}}


<script>
    let storeId = $('#storeId').val(),
        nav = false,
        autoplay = true,
        loop = false;
    if (in_array(storeId, [187852, 15113])) {
        nav = true;
        autoplay = true;
        loop = true;
    }
    var news_home = $('.news-lists-home');
    news_home.owlCarousel({
        items: 1,
        nav: nav,
        dots: false,
        autoplay: autoplay,
        lazyLoad: true,
        margin: 10,
        touchDrag: true,
        navText: ['<i class="fa fa-chevron-left"></i>', '<i class="fa fa-chevron-right"></i>'],
        loop: loop,
        responsive: {
            0: {
                items: 1,
            },
            767: {
                items: 2,
            },
            991: {
                nav: nav,
                items: 4,
            }
        }
    });
</script>
</section>
</main>
<section id="sectionInfoFooter" class="clearfix">
    <div class="wrapper-new clearfix">
        <div class="innerInfoFooter clearfix">
            <div class="col-xs-12 col-sm-3 nth-1">
                <h4>GỌI MUA HÀNG ( 08:30-21:30 )</h4>
                <div class="infoContent">
                    <p>
                        <span class="iconFooter "><i class="fa fa-phone "></i></span> <span class="titleHotline"><a
                                aria-label="zalo" class="btnHL" href="tel:"></a></span>
                        <span class="moreInfoFooter">Tất cả các ngày trong tuần</span>
                    </p>
                </div>
            </div>

            <div class="col-xs-12 col-sm-3 site-animation block-newsletter">
                <h4>ĐĂNG KÝ NHẬN THÔNG TIN MỚI</h4>
                <div id="mc_embed_signup" class="newsletter-form">
                    <form id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate">
                        <div id="mc_embed_signup_scroll">
                            <input type="email" value="" name="EMAIL"
                                class="newsletter-input form-control input-lg email" id="mce-EMAIL"
                                placeholder="Nhập email của bạn tại đây...">
                            <button aria-label="newsletter" type="button" name="subscribe" id="mc-embedded-subscribe"
                                class="btn-newsletter">
                                <span>Đăng ký</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-xs-12 col-sm-3 site-animation">
                <h4>THEO DÕI CHÚNG TÔI</h4>
                <ul class="navbar-social">
                    <li class="social">
                        <a aria-label="facebook" href="https://www.facebook.com/nhanh.vn" target="_blank"
                            rel="nofollow">
                            <i class="fa fa-facebook"></i>
                        </a>
                    </li>



                </ul>
            </div>
        </div>
    </div>
</section>

<footer id="footerBottom" class="clearfix tp_footer ">
    <div class="wrapper-new clearfix">
        <div class="innerInfoFooter">
            <div class="col-xs-12 col-sm-3 footer-div footer-active">
                <h4 class="footer-title">HỖ TRỢ KHÁCH HÀNG</h4>
                <div class="footer-content">

                    <ul>
                        <li><a href="http://t0298.store.nhanh.vn/">Hướng dẫn chọn size</a></li>
                        <li><a href="http://t0298.store.nhanh.vn/">Chính sách khách hàng thân thiết</a></li>
                        <li><a href="http://t0298.store.nhanh.vn/">Chính sách đổi/Trả</a></li>
                        <li><a href="http://t0298.store.nhanh.vn/">Chính sách bảo mật</a></li>
                        <li><a href="http://t0298.store.nhanh.vn/">Thanh toán, Giao nhận</a></li>
                        <li><a href="http://t0298.store.nhanh.vn/">Câu hỏi thường gặp</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-xs-12 col-sm-3 footer-div">
                <h4 class="footer-title">VỀ CHÚNG TÔI</h4>
                <div class="footer-content">

                    <ul>
                        <li><a href="http://t0298.store.nhanh.vn/">Giới thiệu</a></li>
                        <li><a href="http://t0298.store.nhanh.vn/">Liên hệ</a></li>
                        <li><a href="http://t0298.store.nhanh.vn/">Tìm đại lý</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-xs-12 col-sm-3 footer-div footerHidden">
                <h4 class="footer-title">HỆ THỐNG CỬA HÀNG</h4>
                <div class="footer-content footer-content-cus">

                    <ul>
                        <li><a href="http://t0298.store.nhanh.vn/" class="a-link">Facebook</a></li>
                        <li><a href="http://t0298.store.nhanh.vn/" class="a-link">Pinterest</a></li>
                        <li><a href="http://t0298.store.nhanh.vn/" class="a-link">Instagram</a></li>
                        <li><a href="http://t0298.store.nhanh.vn/" class="a-link">Spotify</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-xs-12 col-sm-3 footer-div">
                <h4 class="footer-title text-ft"></h4>
                <div class="footer-content">
                    <iframe loading="lazy" src="./FURLA_files/page.html" title="facebook fanpage"></iframe>
                </div>
            </div>
        </div>
    </div>
</footer>




<div id="site-nav--mobile" class="site-nav style--sidebar">
    <!-- use ajaxLoadview fill cart content -->
    <div id="site-cart" class="site-nav-container" tabindex="-1">
        <div class="site-nav-container-last">
            <input type="hidden" id="totalCartItems_hidden" value="0">

            <p class="title">Giỏ hàng</p>
            <span class="textCartSide">Bạn đang có <b>0</b> sản phẩm trong giỏ hàng.</span>
            <div class="cart-view clearfix">
                <table id="clone-item-cart" class="table-clone-cart">
                    <tbody>
                        <tr class="item_2 hidden">
                            <td class="img"><a href="http://t0298.store.nhanh.vn/" title=""><img
                                        src="http://t0298.store.nhanh.vn/" alt="cart"></a></td>
                            <td>
                                <a class="pro-title-view" href="http://t0298.store.nhanh.vn/" title=""></a>
                                <span class="pro-price-view"></span>
                                <span class="variant"></span>
                                <span class="pro-quantity-view"></span>
                                <span class="remove_link remove-cart"></span>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table id="cart-view">
                    <tbody>
                        <tr>
                            <td>Hiện chưa có sản phẩm</td>
                        </tr>
                    </tbody>
                </table>
                <span class="line"></span>
                <table class="table-total">
                    <tbody>
                        <tr>
                        </tr>
                        <tr>
                            <td class="text-left"><b>TỔNG TIỀN TẠM TÍNH:</b></td>
                            <td class="text-right" id="total-view-cart">
                                0₫
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"><a href="http://t0298.store.nhanh.vn/cart/checkout"
                                    class="checkLimitCart linktocheckout button dark">Tiến hành đặt
                                    hàng</a></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <a href="http://t0298.store.nhanh.vn/cart" class="linktocart button dark">Xem chi tiết
                                    giỏ hàng <i class="fa fa-arrow-right"></i></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>


            <script>
                var $storeId = $('#storeId').val();
                var totalCartItems_hidden = document.getElementById('totalCartItems_hidden').value;
                $('.icon-cart .count').html(totalCartItems_hidden);

                if (in_array($storeId, [100699, 3138])) {
                    $('.cart-header .count-cart').html(totalCartItems_hidden);
                }
            </script>
        </div>
    </div>

    <div id="site-search" class="site-nav-container" tabindex="-1">
        <div class="site-nav-container-last">
            <p class="title">Tìm kiếm</p>
            <div class="search-box wpo-wrapper-search">
                <form action="http://t0298.store.nhanh.vn/search"
                    class="searchform searchform-categoris ultimate-search navbar-form">
                    <div class="wpo-search-inner">
                        <input id="inputSearchAuto" name="q" maxlength="40" autocomplete="off"
                            class="searchinput input-search search-input" type="text" size="20"
                            placeholder="Tìm kiếm...">
                    </div>
                    <button aria-label="submit" type="submit" class="btn-search btn" id="search-header-btn">
                        <svg version="1.1" class="svg search" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
                            viewBox="0 0 24 27" style="enable-background:new 0 0 24 27;" xml:space="preserve">
                            <path
                                d="M10,2C4.5,2,0,6.5,0,12s4.5,10,10,10s10-4.5,10-10S15.5,2,10,2z M10,19c-3.9,0-7-3.1-7-7s3.1-7,7-7s7,3.1,7,7S13.9,19,10,19z">
                            </path>
                            <rect x="17" y="17" transform="matrix(0.7071 -0.7071 0.7071 0.7071 -9.2844 19.5856)"
                                width="4" height="8"></rect>
                        </svg>
                    </button>
                </form>
                <div id="ajaxSearchResults" class="smart-search-wrapper ajaxSearchResults" style="display: none">
                    <div class="resultsContent"></div>
                </div>
            </div>
        </div>
    </div>
    <button aria-label="close" id="site-close-handle" class="site-close-handle" title="Đóng">
        <img width="20" height="20" src="./FURLA_files/iconclose.png" alt="Đóng">
    </button>
</div>

<div id="site-overlay" class="site-overlay"></div>

<div id="quickview-cart" class="modal fade" role="dialog">
    <div id="quickview-cart-desktop" class="clearfix"></div>
</div>


<div id="bttop">
    <span class="text-bttop">Về đầu trang</span>
    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
        viewBox="0 0 32.635 32.635" style="enable-background:new 0 0 32.635 32.635;" xml:space="preserve">
        <g>
            <path d="M32.135,16.817H0.5c-0.276,0-0.5-0.224-0.5-0.5s0.224-0.5,0.5-0.5h31.635c0.276,0,0.5,0.224,0.5,0.5
                                 S32.411,16.817,32.135,16.817z"></path>
            <path d="M19.598,29.353c-0.128,0-0.256-0.049-0.354-0.146c-0.195-0.195-0.195-0.512,0-0.707l12.184-12.184L19.244,4.136
                                 c-0.195-0.195-0.195-0.512,0-0.707s0.512-0.195,0.707,0l12.537,12.533c0.094,0.094,0.146,0.221,0.146,0.354
                                 s-0.053,0.26-0.146,0.354L19.951,29.206C19.854,29.304,19.726,29.353,19.598,29.353z">
            </path>
        </g>
    </svg>
</div>


<!--    SP đã xem    -->


<div id="modalShow" class="modal fade" role="dialog">
    <div class="modal-dialog ">
        <div class="modal-content"></div>
    </div>
</div>
<span class="loadings"><img style="max-width: 100px" src="./FURLA_files/lazyLoading.gif" alt="loading"></span>

<a class="loadChat" target="_blank" href="https://www.facebook.com/nhanh.vn"
    style="position: fixed;right: 20px;bottom: 20px;z-index: 1000;display: block;width: 60px;height: 60px;border-radius: 60px;color: #fff;
            background: rgb(0, 132, 255) url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAgCAYAAAAFQMh/AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAvNJREFUeNq8l1tIVFEUhv+5qE1UMmaCJF0JCSuxopewJ6mQLkQXuj1ED9FDBEV2sYeop4KS6CGRQCyzoLLAB0kki7CMLmZh053Sxiyh0DRzHHT3r84ZiPHMmT0y44LPc2b2dv977b3WXnscSilo2BSygqwkC0kWmWC29ZEO0kzqyG3SFXVEEbZhBjlN2pW++UkJmWU3tp3ofvJTjd66SRFx6AqnkpsqflZD0qIJe8lDFX97TNIjCSeROpU4u0OSrYRPqMTbyZCew0yneWY6JCGxNkQWkxan+cWRMRAVc5Gj8iIez+bzJRmPsbEAyRWP18cq2vRRr19nD9AbGPF1CtkgwstjEb1wH9hbBVQ2Re7zJ2j0e/Ae8FhvYIEI5+iKVj8DLjYCkzxA2V2g6tHIPvU+YFsZD+tfXEqGkdtpOdRcN/+k64g2vAbO1XOd6IGLg43js7TBeN+8hGH6BajgpO69AQoXAMWrGECRh5sswRW1PDW3AYeucwkH8W+0ZLfhydAwP/JzzlTAx/r0g3VqWTZwaiMn6LYfUxZi0K7DBxa44mqgu98Q2MeIyJgIDAQNb8VaOLF+jpI3HTi+LrqoaIrH/DdMs2rt6AZ2VRhe7VgKrMmjt8zEd9+5Atfo4W9jyWUSmanA2a3GU8P8IlzDl9VWqXDsFpCdCezMB7xhCff2G3CQ4l29DBJeCUq2AHMytJOjVs7N3eEHal9AqRtPlWrtsD94fV+V2nSepedTzGf2HvGYPqGVpIWmE+SJmuTSm7rkrCe2w7ZHUljCo5Nc/r9FV1TME/sJf0XCJ1SdskyvUxN8TjMiMJ+0hc4VPzkwBgXisIha3TJLE3gJKLe7c7lIVQJEr5pXK9tbZn6cReVe7gzXsaodKXHaz1dkrRk7w+GN7jgHj6TIE1JOKkl/pI66wgPkDPlMFpGZxGu2sfKinTwnjVIz9KY4co8LwvaoluRG+Y0VM1Z7HKrfcrPaTgrJi3gntDuC8CVSpPVzc5T2V4ABAMltT/3nScyOAAAAAElFTkSuQmCC) center no-repeat;box-shadow: 0 0 10px 0px rgb(0, 132, 255);"></a>
</div>
<div style="display: none;">
    <div id="dialogMessage"></div>
</div>





<script>
    const loadScriptsTimer = setTimeout(loadScripts, 5000);
    const userInteractionEvents = ['mouseover', 'keydown', 'touchmove', 'touchstart'];
    userInteractionEvents.forEach(function(event) {
        window.addEventListener(event, triggerScriptLoader, {
            passive: true
        });
    });

    function triggerScriptLoader() {
        loadScripts();
        clearTimeout(loadScriptsTimer);
        userInteractionEvents.forEach(function(event) {
            window.removeEventListener(event, triggerScriptLoader, {
                passive: true
            });
        });
    }

    function loadScripts() {
        document.querySelectorAll('script[data-type=lazy]').forEach(function(elem) {
            elem.setAttribute('src', elem.getAttribute('data-src'));
        });
        document.querySelectorAll('iframe[data-type=lazy]').forEach(function(elem) {
            elem.setAttribute('src', elem.getAttribute('data-src'));
        });
    }
</script>
<input type="hidden" class="fanpageId" value="">
<div id="fb-root" class=" fb_reset">
    <div style="position: absolute; top: -10000px; width: 0px; height: 0px;">
        <div></div>
    </div>
</div>
<script async="" defer="" crossorigin="anonymous" src="./FURLA_files/sdk(1).js.tải xuống"></script><input type="hidden" id="bussinessId" value="53724"><input type="hidden"
    value="YTI7ViqUEeSw77EiK0TjtkCs6H2Qy9GDNiTkz3F4rr3YgniNUv1exGdJbmHYAst2b7TJFczyzhp0UGuoCSGEF8NgCCgCaB1f3uFswcx2gK1lk5SwiJ9ZEiMAD3fwGPPOqmFoqcUMqHHMp4b176EHtzzPI7BEOJYOoeNfbU1nyvxs8BvoYy71Ns8IKivm1BLf"
    id="uctk" name="uctk"><input type="hidden" id="clienIp" value="14.177.184.251">

<div id="pbOverlay" style="display: none;">
    <div id="pbCloseBtn"></div>
</div><img id="pbImage" style="display: none;">
<div id="pbBottom" style="display: none;">
    <div id="pbCaption"></div>
    <div id="pbNav"><a id="pbPrevBtn" href="http://t0298.store.nhanh.vn/#"></a><a id="pbZoomBtn"
            href="http://t0298.store.nhanh.vn/#"></a><a id="pbNextBtn" href="http://t0298.store.nhanh.vn/#"></a>
    </div>
    <div id="pbNumber"></div>
</div>
</body><savior-host
    style="all: unset; position: absolute; top: 0; left: 0; z-index: 99999999999999; display: block !important; overflow: unset"><template
        shadowrootmode="open">
        <style>
            @import "chrome-extension://jdfkmiabjpfjacifcmihfdjhpnjpiick/css/content-script.css";
        </style>
        <div class="body"></div>
    </template></savior-host><en2vi-host class="corom-element" version="3"
    style="all: initial; position: absolute; top: 0; left: 0; right: 0; height: 0; margin: 0; text-align: left; z-index: 10000000000; pointer-events: none; border: none; display: block"><template
        shadowrootmode="open">
        <style>
            @import "chrome-extension://gfgbmghkdjckppeomloefmbphdfmokgd/css/common.css";
            @import "chrome-extension://gfgbmghkdjckppeomloefmbphdfmokgd/css/style.css";
        </style>
        <style id="svelte-1dp9t6n">
            .toast-container.svelte-1dp9t6n {
                position: fixed;
                pointer-events: none;
                width: 100%;
                height: 100%;
                display: flex;
                flex-direction: column-reverse;
                align-items: center;
                z-index: 1000;
                bottom: 0;
                padding: 16px;
                gap: 8px
            }
        </style>
        <div class="toast-container svelte-1dp9t6n"></div>
    </template></en2vi-host><savior-host
    style="all: unset; position: absolute; top: 0; left: 0; z-index: 99999999999999; display: block !important; overflow: unset"><template
        shadowrootmode="open">
        <style>
            @import "chrome-extension://jdfkmiabjpfjacifcmihfdjhpnjpiick/css/content-script.css";
        </style>
        <div class="body">
            <div class="turn-lights-overlay"></div>
            <toasts id="toasts-container"></toasts>
        </div>
    </template></savior-host>

</html>
