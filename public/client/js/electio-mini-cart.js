jQuery(function($){
    "use strict";
    /* Change cart item quantity */
    $(document).on('change', '.ts-tiny-cart-wrapper .qty', function(){
        var qty = parseFloat($(this).val());
        var max = parseFloat($(this).attr('max'));
        if( max !== 'NaN' && max < qty ){
            qty = max;
            $(this).val( max );
        }
        var cart_item_key = $(this).attr('name').replace('cart[', '').replace('][qty]', '');
        $(this).parents('.woocommerce-mini-cart-item').addClass('loading');
        $('.shopping-cart-wrapper').addClass('updating');
        $('.woocommerce-message').remove();
        $.ajax({
            type : 'POST'
            ,url : electio_params.ajax_url
            ,data : {action : 'electio_update_cart_quantity', qty: qty, cart_item_key: cart_item_key}
            ,success : function(response){
                if( !response ){
                    return;
                }
                $( document.body ).trigger( 'added_to_cart', [ response.fragments, response.cart_hash ] );
                if( !$('.shopping-cart-wrapper').is(':hover') ){
                    $('.shopping-cart-wrapper').removeClass('updating');
                }
            }
        });
    });


});