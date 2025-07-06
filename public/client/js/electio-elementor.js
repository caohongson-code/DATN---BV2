(function ($) {
    "use strict";

    /*----- ELEMENTOR LOAD FUNCTION CALL ---*/

    $(window).on("elementor/frontend/init", function () {

        var electioDataBackground = function () {
            $('[data-background]').each(function () {
                $(this).css('background-image', 'url(' + $(this).attr('data-background') + ')');
            });
        };


        // hero slider
        var electioHeroSlider = function () {
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
        }

        var electioCatSlidethree = function () {
            // home 3 category box
            $(".el-home-3-fea-slider").slick({
                slidesToShow: 3,
                autoplay: false,
                arrows: true,
                centerPadding: '50px',
                prevArrow: '<button class="prev-btn slider-btn"><i class="fa-solid fa-arrow-left" ></i ></button>',
                nextArrow: '<button class="next-btn slider-btn"><i class="fa-solid fa-arrow-right" ></i ></button>',
                responsive: [
                    {
                        breakpoint: 1400,
                        settings: {
                            slidesToShow: 2,
                        },
                    },
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 2,
                        },
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 1,
                        },
                    }
                ],
            });

        }

        // Brand Slider
        var electioBrandSlider = function () {
            $(".hm2-brand-slider").slick({
                slidesToShow: 5,
                autoplay: true,
                arrows: false,
                responsive: [
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: 4,
                        }
                    },
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 3,
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 2,
                        }
                    },
                    {
                        breakpoint: 400,
                        settings: {
                            slidesToShow: 1,
                        }
                    }
                ]
            });

            $(".hm3-brand-slider").slick({
                slidesToShow: 6,
                arrows: false,
                autoplay: true,
                autoplaySpeed: 0,
                speed: 5000,
                cssEase: 'linear',
                responsive: [
                    {
                        breakpoint: 1400,
                        setttings: {
                            slidesToShow: 5,
                        }
                    },
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: 4,
                        }
                    },
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 3,
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 2,
                        }
                    },
                    {
                        breakpoint: 500,
                        settings: {
                            slidesToShow: 1,
                        }
                    }
                ]
            });
        }

        //gallery slider
        var electioGallerySlider = function () {
            $(".hm2-gallery-slide-1").slick({
                slidesToShow: 5,
                slidesToScroll: 1,
                arrows: false,
                autoplay: true,
                autoplaySpeed: 0,
                speed: 6000,
                pauseOnHover: true,
                cssEase: 'linear',
                loop: true,
                responsive: [
                    {
                        breakpoint: 1400,
                        settings: {
                            slidesToShow: 4,
                        }
                    },
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: 3,
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 2,
                        }
                    },
                    {
                        breakpoint: 500,
                        settings: {
                            slidesToShow: 1,
                        }
                    },
                ]
            });

            $(".hm2-gallery-slide-2").slick({
                slidesToShow: 5,
                slidesToScroll: 1,
                arrows: false,
                autoplay: true,
                autoplaySpeed: 0,
                speed: 6000,
                pauseOnHover: true,
                cssEase: 'linear',
                loop: true,
                rtl: true,
                responsive: [
                    {
                        breakpoint: 1400,
                        settings: {
                            slidesToShow: 4,
                        }
                    },
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: 3,
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 2,
                        }
                    },
                    {
                        breakpoint: 500,
                        settings: {
                            slidesToShow: 1,
                        }
                    },
                ]
            });
        }

        var electioBlogSlider = function() {
            $(".hm3-blog-slider").slick({
                slidesToShow: 3,
                autoplay: true,
                speed: 600,
                prevArrow: '<button class="prev-arrow"><i class="me-3 fas fa-arrow-left"></i>Prev</button>',
                nextArrow: '<button class="next-arrow">Next<i class="ms-3 fas fa-arrow-right"></i></button>',
                responsive: [
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: 2,
                        }
                    },
                    {
                        breakpoint: 670,
                        settings: {
                            slidesToShow: 1,
                        }
                    }
                ]
            });
        }

        var electioProductSlider = function () {
            $(".vr5-collection-slider").slick({
                slidesToShow: 4,
                arrows: false,
                dots: true,
                responsive: [
                    {
                        breakpoint: 1400,
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
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 1,
                        }
                    },
                ]
            });

            $(".cmd-slider").slick({
                slidesToShow: 1,
                arrows: false,
                dots: true,
            });

            //arival slider
            $(".arrival-slider").slick({
                slidesToShow: 4,
                autoplay: true,
                loop: true,
                prevArrow: '<button class="prev-arrow"><i class="fas fa-arrow-left"></i>Prev</button>',
                nextArrow: '<button class="next-arrow">Next<i class="fas fa-arrow-right"></i></button>',
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
                        breakpoint: 575,
                        settings: {
                            slidesToShow: 1,
                        }
                    }
                ]
            });
        }

        // Electio Marquee
        var electioMarque = function () {
            $('.electio-marquee-wrapper').marquee({
                speed: 50,
                gap: 0,
                delayBeforeStart: 0,
                direction: 'left',
                duplicated: true,
                pauseOnHover: false,
                startVisible:true,
            });
        }

        var electioProductslide = function () {
            $(".vr5-filter-slider").slick({
                slidesToShow: 3,
                arrows: false,
                dots: true,
                responsive: [
                    {
                        breakpoint: 1100,
                        settings: {
                            slidesToShow: 2,
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 1,
                        }
                    }
                ]
            });
        }
        var electioProductfeedback = function () {
            //sidebar-customer-feedback-slider
            $(".sidebar-customer-feedback-slider").slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                autoplay: true,
                speed: 1000,
                arrows: false,
                dots: true,
            });
        }



        //Team Carousel
        elementorFrontend.hooks.addAction("frontend/element_ready/global", function ($scope, $) { electioDataBackground() });
        elementorFrontend.hooks.addAction("frontend/element_ready/electio-hero-section.default", function ($scope, $) { electioHeroSlider() });
        elementorFrontend.hooks.addAction("frontend/element_ready/electio_marquee.default", function ($scope, $) { electioMarque() });
        elementorFrontend.hooks.addAction("frontend/element_ready/electio_brand_logo.default", function ($scope, $) { electioBrandSlider() });
        elementorFrontend.hooks.addAction("frontend/element_ready/electio_gallery.default", function ($scope, $) { electioGallerySlider() });
        elementorFrontend.hooks.addAction("frontend/element_ready/electio_blog.default", function ($scope, $) { electioBlogSlider() });
        elementorFrontend.hooks.addAction("frontend/element_ready/electio_product_slider.default", function ($scope, $) { electioProductSlider() });
        elementorFrontend.hooks.addAction("frontend/element_ready/electio_collection_grid.default", function ($scope, $) { electioCollectionGrid() });
        elementorFrontend.hooks.addAction("frontend/element_ready/Electio_product_tabs.default", function ($scope, $) { electioProductslide() });
        elementorFrontend.hooks.addAction("frontend/element_ready/electio-category-section.default", function ($scope, $) { electioCatSlidethree() });
        elementorFrontend.hooks.addAction("frontend/element_ready/electio-feedback-section.default", function ($scope, $) { electioProductfeedback() });
    });


})(jQuery);