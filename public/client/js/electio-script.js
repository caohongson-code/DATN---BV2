(function ($) {

    /*
        1. Data Background And Width Function
        2. Scroll top button
        3. Preloader
        4. Nice Select
        5. Wow Js
        6. Mobile Menu
        7. Mobile Category
        8. Cart Drawer
        9. Countdown
        10. Filter
        11. Sliders
        12. Fancybox
        13. Pricing Range Slider
        14. Others
    */


    //***** 1. Data Background And Width Function *****//
    $('[data-background]').each(function () {
        $(this).css('background-image', 'url(' + $(this).attr('data-background') + ')');
    });

    $('[data-width]').each(function () {
        $(this).css('width', $(this).data('width'));
    });


    //***** 2. Scroll top button *****//
    $(window).on("scroll", function () {
        var scrollBar = $(this).scrollTop();
        if (scrollBar > 150) {
            $(".scroll-top-btn").fadeIn();
        } else {
            $(".scroll-top-btn").fadeOut();
        }
    })
    $(".scroll-top-btn").on("click", function () {
        $("body,html").animate({
            scrollTop: 0
        });
    });

    //***** 3. Preloader *****//
    $(window).on("load", function () {
        $(".preloader").fadeOut();
    });

    //***** 4. Nice Select *****//
    $('.nice_select').niceSelect();

    //***** 5. Wow Js
    new WOW().init();

    //***** 6. Mobile Menu *****//
    $(".mobile-menu-toggle").on("click", function () {
        $(".el-mobile-menu-and-category-sidebar").addClass("active");
    });

    $(".mobile-menu-close").on("click", function () {
        $(".el-mobile-menu-and-category-sidebar").removeClass("active");
    });

    // Added menu right arrow for mobile menu start
    $('.mobile-menu.el-mobile-menu-wrapper .has-submenu').prepend('<i class="fas fa-angle-down icon-rotate"></i>');
    // Added menu right arrow for mobile menu End

    $(".el-mobile-menu-wrapper ul li.has-submenu i").each(function () {
        $(this).on("click", function () {
            $(this).siblings('.submenu-wrapper').slideToggle();
            $(this).toggleClass("icon-rotate");
        });
    });
    $(document).on("mouseup", function (e) {
        var offCanvusMenu = $(".el-mobile-menu-and-category-sidebar");

        if (!offCanvusMenu.is(e.target) && offCanvusMenu.has(e.target).length === 0) {
            $(".el-mobile-menu-and-category-sidebar").removeClass("active");
        }
    });

    //***** 7. Mobile Category *****//
    $(".el-mobile-category-wrapper ul li.has-submenu i").each(function () {
        $(this).on("click", function () {
            $(this).siblings('.submenu-wrapper').slideToggle();
            $(this).toggleClass("icon-rotate");
        });
    });

    // lg home 3 dropdown category
    $('.el3-cate-dropdown-trigger-btn').on('click', function () {
        $('.el3-cate-dropdown-target-el').slideToggle();
        $(this).toggleClass('active');
    });

    //***** 8. Cart Drawer *****//
    $(".open-cart-drawer").on("click", function () {
        $(".cart-drawer").addClass("active");
    });

    $(".drawer-close").on("click", function () {
        $(".cart-drawer").removeClass("active");
    });

    $(document).on("mouseup", function (e) {
        var offCanvusMenu = $(".cart-drawer");

        if (!offCanvusMenu.is(e.target) && offCanvusMenu.has(e.target).length === 0) {
            $(".cart-drawer").removeClass("active");
        }
    });


    // $(".el2-countdown-timer").downCount(
    //     {
    //         date: "06/28/2025 12:00:00",
    //         offset: +6,
    //     },
    //     function () {
    //         alert("Countdown done!");
    //     }
    // );


    //***** 10. Filter *****//
    var $project_grid = $('.el-hm1-grid');

    // add filter functionality
    $('.el-hm1-filter-btn-group').on('click', 'button', function () {
        var filterValue = $(this).attr('data-filter');
        $project_grid.isotope({ filter: filterValue });
        $(this).parents(".el-hm1-filter-btn-group").find("button.active").removeClass("active");
        $(this).addClass("active");

    });


    //*****  11.Sliders *****//
    // el hero section slider
    $(".el-hero-section-slider").slick({
        slidesToShow: 1,
        autoplay: false,
        speed: 2000,
        arrows: false,
        pauseOnHover: false,
        fade: true,
        dots: true,
        responsive: [
            {
                breakpoint: 768,
                settings: {
                    dots: false,
                },
            }
        ],
    });

    // home 2 gallery slider
//instagram slider
    $(".el2-gallery-slider #sbi_images").slick({
        slidesToShow: 6,
        autoplay: true,
        speed: 3000,
        arrows: false,
        responsive: [
            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 3,
                }
            },
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 2,
                }
            },
            {
                breakpoint: 576,
                settings: {
                    slidesToShow: 1,
                }
            },
        ]
    });



    // $(".el2-gallery-slider #sbi_images").slick({
    //     slidesToShow: 6,
    //     autoplay: true,
    //     arrows: false,
    //     responsive: [
    //         {
    //             breakpoint: 1200,
    //             settings: {
    //                 slidesToShow: 5,
    //             },
    //         },
    //         {
    //             breakpoint: 992,
    //             settings: {
    //                 slidesToShow: 4,
    //             },
    //         },
    //         {
    //             breakpoint: 768,
    //             settings: {
    //                 slidesToShow: 3,
    //             },
    //         },
    //         {
    //             breakpoint: 575,
    //             settings: {
    //                 slidesToShow: 2,
    //             },
    //         },
    //     ],
    // });

    // home 1 accordion-product-slider
    $('#el-pp-acordion').on('show.bs.collapse', function (e) {
        $(".accordion .accordion-product-slider").resize(),
            $('.accordion .accordion-product-slider').slick('refresh');
    });
    $(".accordion-product-slider").slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: false,
        arrows: true,
        prevArrow: '<button class="prev-btn slider-btn"><i class="fa-solid fa-arrow-left" ></i ></button>',
        nextArrow: '<button class="next-btn slider-btn"><i class="fa-solid fa-arrow-right" ></i ></button>',
        responsive: [
            {
                breakpoint: 576,
                settings: {
                    slidesToShow: 1,
                    slidesToShow: 1,
                },
            }
        ],
    });

    // product modal  with slider
    $('.modal').on('shown.bs.modal', function (e) {
        $('.slider-for-main-gallery').slick('setPosition');

        $('.slider-for-nav-gallery').slick('setPosition');
    })

    $('.slider-for-main-gallery').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: true,
        fade: true,
        centerMode: true,
        asNavFor: '.slider-for-nav-gallery',
        prevArrow: '<button class="prev-btn slider-btn"><i class="fa-solid fa-arrow-left" ></i ></button>',
        nextArrow: '<button class="next-btn slider-btn"><i class="fa-solid fa-arrow-right" ></i ></button>',
    });
    $('.slider-for-nav-gallery').slick({
        arrows: false,
        slidesToShow: 5,
        slidesToScroll: 1,
        asNavFor: '.slider-for-main-gallery',
        dots: false,
        vertical: true,
        focusOnSelect: true,
        responsive: [
            {
                breakpoint: 768,
                settings: {
                    vertical: false,
                    slidesToShow: 3,
                    verticalSwiping: false,
                },
            },
            {
                breakpoint: 425,
                settings: {
                    vertical: false,
                    slidesToShow: 2,
                    verticalSwiping: false,
                },
            }
        ],
    });

    //sidebar-customer-feedback-slider
    $(".sidebar-customer-feedback-slider").slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        speed: 1000,
        arrows: false,
        dots: true,
    });


    //product grid slider for single product details
    $(".product-grid-mobile-slider").slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        autoplay: true,
        speed: 1000,
        arrows: false,
        dots: false,
        responsive: [
            {
                breakpoint: 525,
                settings: {
                    slidesToShow: 2,
                },
            },
            {
                breakpoint: 425,
                settings: {
                    slidesToShow: 1,
                    centerMode: true
                },
            }
        ],
    });


    //product details trendy product slider
    $(".trendy-product-details-slider").slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        autoplay: true,
        speed: 1000,
        arrows: false,
        dots: false,
        responsive: [
            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 3,
                },
            },
            {
                breakpoint: 991,
                settings: {
                    slidesToShow: 2,
                },
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 1,
                },
            }

        ],
    });

    //product details page 2 product gallery slider
    $(".product-details-2-gallery-slider").slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        speed: 1000,
        arrows: true,
        prevArrow: '<button class="prev-btn slider-btn"><i class="fa-solid fa-arrow-left" ></i ></button>',
        nextArrow: '<button class="next-btn slider-btn"><i class="fa-solid fa-arrow-right" ></i ></button>',
        dots: false,
    });

    //***** 12.Fancybox *****//
    Fancybox.bind("[data-fancybox]", {});

    //***** 14.Pricing Range Slider *****//
    $(".ur-pricing-range").slider({
        range: true,
        min: 0,
        max: 500,
        values: [75, 300],
        slide: function (event, ui) {
            $("#amount").val("$" + ui.values[0] + " - $" + ui.values[1]);
        }
    });
    $("#amount").val("$" + $(".ur-pricing-range").slider("values", 0) + " - $" + $(".ur-pricing-range").slider("values", 1));


    //***** 14.Others *****//

    // hide show  home 1 account box
    $("#account-box").on("click", function () {
        $(this).toggleClass("active");
    });
    $(document).on("mouseup", function (e) {
        var offCanvusMenu = $("#account-box");

        if (!offCanvusMenu.is(e.target) && offCanvusMenu.has(e.target).length === 0) {
            $("#account-box").removeClass("active");
        }
    });

    // header search btn
    $(".el2-header-search-toggle").on("click", function () {
        $(".header-search-box").addClass("active");
    });

    $(".header-search-box .search-close").on("click", function () {
        $(".header-search-box").removeClass("active");
    });

    // show newsletter modal on page load
    if (document.getElementById("elNewsletterModal")) {
        var newletter_modal = document.getElementById("elNewsletterModal");
        var newsletterModal = new bootstrap.Modal(newletter_modal);
        if (newletter_modal) {
            newsletterModal.show();
        }
    }

    $(".countdown-timer").each(function () {
        var $data_date = $(this).data('date');
        $(this).countdown({
            date: $data_date
        });
    });

    setShareLinks();

    function socialWindow(url) {
        var left = (screen.width - 570) / 2;
        var top = (screen.height - 570) / 2;
        var params = "menubar=no,toolbar=no,status=no,width=570,height=570,top=" + top + ",left=" + left;
        // Setting 'params' to an empty string will launch
        // content in a new tab or window rather than a pop-up.
        // params = "";
        window.open(url,"NewWindow",params);
    }

    function setShareLinks() {
        var pageUrl = encodeURIComponent(document.URL);
        var tweet = encodeURIComponent($("meta[property='og:description']").attr("content"));

        $(".social-share.facebook").on("click", function() {
            url = "https://www.facebook.com/sharer.php?u=" + pageUrl; socialWindow(url);
        });

        $(".social-share.twitter").on("click", function() {
            url = "https://twitter.com/intent/tweet?url=" + pageUrl + "&text=" + tweet;
            socialWindow(url);
        });

        $(".social-share.linkedin").on("click", function() {
            url = "https://www.linkedin.com/shareArticle?mini=true&url=" + pageUrl; socialWindow(url);
        });
    }

    function vertical_nav_dropdown(){
        $("#electio-vertical-nav-trigger").click(function(){
            $("#electio-vertical-nav-menu").slideToggle(500);
        });
    }
    vertical_nav_dropdown();

})(jQuery)

