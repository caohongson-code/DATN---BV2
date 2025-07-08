(function ($) {
    "use strict";

    jQuery(document).ready(function ($) {

        $('.electio-add-to-cart').on('click', function (e) {
            e.preventDefault();
            var self = $(this);
            var name = "Success";
            if ($(this).data('name')) {
                name = $(this).data('name');
            }
            var link = $(this).attr('data-link');
            var img = false;
            if (self.data('img')) {
                img = self.data('img');
            }
            var id = $(this).data('product_id');
            $(this).find('.btn-icon').removeClass('fa-solid fa-basket-shopping');
            $(this).find('.btn-icon').removeClass('ele-icon electio-shopping-bag');
            var loading_icon = '<svg class="electio-icon-loading bi bi-arrow-repeat" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M2.854 7.146a.5.5 0 00-.708 0l-2 2a.5.5 0 10.708.708L2.5 8.207l1.646 1.647a.5.5 0 00.708-.708l-2-2zm13-1a.5.5 0 00-.708 0L13.5 7.793l-1.646-1.647a.5.5 0 00-.708.708l2 2a.5.5 0 00.708 0l2-2a.5.5 0 000-.708z" clip-rule="evenodd"/><path fill-rule="evenodd" d="M8 3a4.995 4.995 0 00-4.192 2.273.5.5 0 01-.837-.546A6 6 0 0114 8a.5.5 0 01-1.001 0 5 5 0 00-5-5zM2.5 7.5A.5.5 0 013 8a5 5 0 009.192 2.727.5.5 0 11.837.546A6 6 0 012 8a.5.5 0 01.501-.5z" clip-rule="evenodd"/></svg>';
            $(this).find('.btn-icon').addClass('').html(loading_icon);
            var html = '<div class="toast electio-notification bg-white shadow-lg border-0 rounded-lg w-100" role="alert" aria-live="assertive" aria-atomic="true">';
            html += '<div class="toast-header">';
            if (img) {
                html += '<img src="' + img + '" class="rounded-lg electio-ppover-image" alt="" style="width:45px;height:45px;">';
            }
            html += '<strong class="mr-auto">' + name + '</strong>';
            html += '<button type="button" class="electio-popover-cart-close" data-dismiss="toast" aria-label="Close">';
            html += '<span aria-hidden="true">&times;</span>';
            html += '</button>';
            html += '</div>';
            html += '<div class="toast-body text-body-default font-weight-bold">';
            if (electio_main_object.hasOwnProperty('dataAddCartMsg')) {
                html += electio_main_object.dataAddCartMsg;
            } else {
                html += 'You have added the item to your shopping cart!';
            }
            html += '</div>';
            html += '</div>';
            var data = {
                product_id: id,
                quantity: 1
            }
            return $.ajax({
                url: link,
                method: 'GET'
            }).done(function (data) {
                $(document.body).trigger('wc_fragment_refresh');
                var el = $(html);
                $('.electio-notifications-area').append(el);
                el.toast({
                    autohide: true,
                    animation: true,
                    delay: 3000
                });
                el.toast('show');
                self.find('.btn-icon').replaceWith('<i class="btn-icon text-success electioicon-check-circle-1 align-self-center"></i>');
                self.find('.btn-icon').removeClass('electioicon-rotate-right electio-icon-loading').html('');
                self.find('.btn-icon').addClass('text-success electioicon-check-circle-1');
                setTimeout(function () {
                    self.find('.btn-icon').removeClass('text-success electioicon-check-circle-1');
                    self.find('.btn-icon').addClass('fa-solid fa-basket-shopping');
                }, 1000);

            }).fail(function () {
                // console.log('Something went wrong, please try again.');
                self.find('.btn-icon').removeClass('electioicon-rotate-right electio-icon-loading').html('');
                self.find('.btn-icon').addClass('text-red electioicon-close-circle');
                setTimeout(function () {
                    self.find('.btn-icon').removeClass('text-red electioicon-close-circle');
                    self.find('.btn-icon').addClass('fa-solid fa-basket-shopping');
                }, 1000);
            });
        });

        $('.electio-add-to-wishlist').on('click', function (e) {
            e.preventDefault();
            var self = $(this);
            var id = false;
            id = self.data('id');
            if (!id) return false;
            var link = $(this).attr('data-wishlist-link');
            $(this).find('.btn-icon').removeClass('ele-icon electio-heart-2');
            var loading_icon = '<svg class="electio-icon-loading" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M2.854 7.146a.5.5 0 00-.708 0l-2 2a.5.5 0 10.708.708L2.5 8.207l1.646 1.647a.5.5 0 00.708-.708l-2-2zm13-1a.5.5 0 00-.708 0L13.5 7.793l-1.646-1.647a.5.5 0 00-.708.708l2 2a.5.5 0 00.708 0l2-2a.5.5 0 000-.708z" clip-rule="evenodd"/><path fill-rule="evenodd" d="M8 3a4.995 4.995 0 00-4.192 2.273.5.5 0 01-.837-.546A6 6 0 0114 8a.5.5 0 01-1.001 0 5 5 0 00-5-5zM2.5 7.5A.5.5 0 013 8a5 5 0 009.192 2.727.5.5 0 11.837.546A6 6 0 012 8a.5.5 0 01.501-.5z" clip-rule="evenodd"/></svg>';
            $(this).find('.btn-icon').addClass('').html(loading_icon);
            var data = {
                add_to_wishlist: id,
                product_type: 'simple',
                action: 'add_to_wishlist'
            }
            if (self.hasClass('remove-item')) {
                link = yith_wcwl_l10n.ajax_url;
                data = {
                    remove_from_wishlist: id,
                    product_type: 'simple',
                    action: yith_wcwl_l10n.actions.remove_from_wishlist_action,
                    nonce: yith_wcwl_l10n.nonce.remove_from_wishlist_nonce,
                    context: 'frontend',
                    fragments: retrieve_fragments(id)
                }
            }
            return $.ajax({
                url: link,
                data: data,
                method: 'POST'
            }).done(function (data) {

                if (self.hasClass('remove-item')) {
                    self.removeClass('remove-item');
                    self.find('.btn-icon').removeClass('electioicon-rotate-right electio-icon-loading text-red').html('');
                    self.find('.btn-icon').addClass('text-body-default fa-regular fa-heart');
                    let wishCount = Number($('.yith-wcwl-items-count').html());
                    if (wishCount && wishCount > 0) {
                        wishCount--;
                        $('.yith-wcwl-items-count').html(wishCount);
                    }
                } else {
                    self.find('.btn-icon').removeClass('electioicon-rotate-right electio-icon-loading text-body-default').html('');
                    self.find('.btn-icon').addClass('ele-icon electio-heart-2');
                    self.addClass('remove-item');
                    let wishCount = Number($('.yith-wcwl-items-count').html());
                    if (wishCount) {
                        wishCount++;
                        $('.yith-wcwl-items-count').html(wishCount);
                    }
                }
                $(document).trigger('yith_wcwl_init_after_ajax');
            }).fail(function () {
                // console.log('Something went wrong, please try again.');
                self.find('.btn-icon').removeClass('electioicon-rotate-right electio-icon-loading').html('');
                self.find('.btn-icon').addClass('text-red electioicon-close-circle');
                setTimeout(function () {
                    self.find('.btn-icon').removeClass('text-red electioicon-close-circle').html('');
                    self.find('.btn-icon').addClass('fa-regular fa-heart');
                }, 1000);
            });
        });

        function retrieve_fragments(search) {
            var options = {},
                fragments = null;

            if (search) {
                if (typeof search === 'object') {
                    search = $.extend({
                        fragments: null,
                        s: '',
                        container: $(document),
                        firstLoad: false
                    }, search);

                    if (!search.fragments) {
                        fragments = search.container.find('.wishlist-fragment');
                    } else {
                        fragments = search.fragments;
                    }

                    if (search.s) {
                        fragments = fragments.not('[data-fragment-ref]').add(fragments.filter('[data-fragment-ref="' + search.s + '"]'));
                    }

                    if (search.firstLoad) {
                        fragments = fragments.filter('.on-first-load');
                    }
                } else {
                    fragments = $('.wishlist-fragment');

                    if (typeof search === 'string' || typeof search === 'number') {
                        fragments = fragments.not('[data-fragment-ref]').add(fragments.filter('[data-fragment-ref="' + search + '"]'));
                    }
                }
            } else {
                fragments = $('.wishlist-fragment');
            }

            if (fragments.length) {
                fragments.each(function () {
                    var t = $(this),
                        id = t.attr('class').split(' ').filter((val) => {
                            return val.length && val !== 'exists';
                        }).join(yith_wcwl_l10n.fragments_index_glue);

                    options[id] = t.data('fragment-options');
                });
            } else {
                return null;
            }

            return options;
        }


        $('.category-wrapper select').niceSelect();

        /*    $('#electiotimerselected').countDown({
                label_mm: 'm',
                label_ss: 's',
                separator: ':',

            });*/


    });
})(jQuery);


jQuery(function ($) {

    /* global wc_add_to_cart_params */
    if (typeof wc_add_to_cart_params === 'undefined') {
        return false;
    }

    $(document).on('submit', 'form.cart', function (e) {

        var form = $(this),
            button = form.find('.single_add_to_cart_button');

        var formFields = form.find('input:not([name="product_id"]), select, button, textarea');

        // create the form data array
        var formData = [];
        formFields.each(function (i, field) {

            // store them so you don't override the actual field's data
            var fieldName = field.name,
                fieldValue = field.value;

            if (fieldName && fieldValue) {

                // set the correct product/variation id for single or variable products
                if (fieldName == 'add-to-cart') {
                    fieldName = 'product_id';
                    fieldValue = form.find('input[name=variation_id]').val() || fieldValue;
                }

                // if the fiels is a checkbox/radio and is not checked, skip it
                if ((field.type == 'checkbox' || field.type == 'radio') && field.checked == false) {
                    return;
                }

                // add the data to the array
                formData.push({
                    name: fieldName,
                    value: fieldValue
                });
            }

        });

        if (!formData.length) {
            return;
        }

        e.preventDefault();

        form.block({
            message: null,
            overlayCSS: {
                background: "#ffffff",
                opacity: 0.6
            }
        });

        $(document.body).trigger('adding_to_cart', [button, formData]);

        $.ajax({
            type: 'POST',
            url: woocommerce_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart'),
            data: formData,
            success: function (response) {
                if (!response) {
                    return;
                }

                if (response.error & response.product_url) {
                    window.location = response.product_url;
                    return;
                }

                $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, button]);

            },
            complete: function () {
                form.unblock();

            }
        });

        return false;

    });
});

jQuery(document).ready(function ($) {

    $(document.body).trigger('wc_fragment_refresh');
});


var timeout;

jQuery(function ($) {
    $('.woocommerce').on('change', 'input.qty', function () {

        if (timeout !== undefined) {
            clearTimeout(timeout);
        }

        timeout = setTimeout(function () {
            $("[name='update_cart']").trigger("click");
        }, 1000); // 1 second delay, half a second (500) seems comfortable too

    });
});


jQuery(".single_add_to_cart_button").on("click", (function () {
    let t = jQuery(this), e = t.closest("form.cart");
    e ? e.on("submit", (function () {
            t.addClass("loading")
        }))
        : t.hasClass("disabled") || t.addClass("loading"), jQuery(window).on("pageshow", (() => {
        t.removeClass("loading")
    }))
}))


