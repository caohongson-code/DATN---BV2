(function ($) {
    $(document).on('ready', function () {

        if (XPSC_ajax_data.stillValid && null !== XPSC_ajax_data.endDate) {
            $('#electiotimer').countDown();
        } else {
            $(".xpsc-product-coutdown-wrapper").hide();
        }
        $('.xpsc-product-coutdown-wrapper-alt #electiotimer').countDown({
            label_mm: 'mins',
            label_ss: 'secs',
            separator: '|',
        });

    })

})(jQuery);
