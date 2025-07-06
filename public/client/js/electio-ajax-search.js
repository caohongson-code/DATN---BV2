(function($){

    "use strict";

    function productSearch(form,query,currentQuery,timeout){

        var search   = form.find('.search'),
            category = form.find('.category');

        form.next('.search-results').html('').removeClass('active');
         $( ".electio-ajax-s-offcanvas" ).removeClass( "offcanvas-full-height" );

        query = query.trim();

        if (query.length >= 3) {

            if (timeout) {
                clearTimeout(timeout);
            }

            form.next('.search-results').removeClass('empty');

            search.parent().addClass('loading');
            if (query != currentQuery) {
                timeout = setTimeout(function() {

                    $.ajax({
                        url:opt.ajaxUrl,
                        type: 'post',
                        data: { action: 'search_product', keyword: query, category: category.val() },
                        success: function(data) {
                            currentQuery = query;
                            search.parent().removeClass('loading');

                            if (!form.next('.search-results').hasClass('empty')) {

                                if (data.length) {
                                    form.next('.search-results').html('<div>'+data+'</div>').addClass('active');
                                    $( ".electio-ajax-s-offcanvas" ).addClass( "offcanvas-full-height" );
                                    
                                } else {
                                    form.next('.search-results').html('<div class="electio-ajax-not-found">'+opt.noResults+'</div>').addClass('active');
                                   
                                }

                            }

                            clearTimeout(timeout);
                            timeout = false;


                        }
                    });

                }, 500);
            }
        } else {

            search.parent().removeClass('loading');
            form.next('.search-results').empty().removeClass('active').addClass('empty');

            clearTimeout(timeout);
            timeout = false;

        }
    }

    $('form[name="product-search"]').each(function(){

        var form          = $(this),
            search        = form.find('.search'),
            category      = form.find('.category'),
            currentQuery  = '',
            timeout       = false;

        category.on('change',function(){
            currentQuery  = '';
            var query = search.val();
            productSearch(form,query,currentQuery,timeout);
        });

        search.keyup(function(){
            var query = $(this).val();
            productSearch(form,query,currentQuery,timeout);
        });

    });

})(jQuery);
