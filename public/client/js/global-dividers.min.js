jQuery(window).on('elementor/frontend/init', () => {
    const addHandler = ($element) => {
        jQuery('body')
            .find('.electio-shape-dividers')
            .each(function () {
                if (!jQuery(this).hasClass('loaded')) {
                    let divider = new dividerShapes(this);
                    divider.initPoints();
                    jQuery(this).addClass('loaded');
                }
            });
    };

    elementorFrontend.hooks.addAction(
        'frontend/element_ready/electio-dividers.default',
        addHandler
    );
});


jQuery(window).on('elementor/frontend/init', () => {
    const addSectionHandler = ( $element ) => {
        var attr = $element.attr('data-electio-dividers');
        if (typeof attr !== 'undefined' && attr !== false) {
            let isCol = $element.hasClass('elementor-column');
            if(isCol){
                $element.find('> .elementor-column-wrap').addClass('electio-elementor-section-dividers')
                $element.find('> .elementor-column-wrap').prepend($element.attr('data-electio-dividers'));
            }else{
                $element.addClass('electio-elementor-section-dividers')
                $element.prepend($element.attr('data-electio-dividers'));
            }
            $element.attr('data-electio-dividers', '');
                    $element
                        .find('.electio-shape-dividers')
                        .each(function () {
                            if (!jQuery(this).hasClass('loaded')) {
                                let divider = new dividerShapes(this);
                                divider.initPoints();
                                jQuery(this).addClass('loaded');
                            }
                        });
        }
        // $e.commands.getAll();
        // 
    };
 
    elementorFrontend.hooks.addAction( 'frontend/element_ready/section', addSectionHandler );
    elementorFrontend.hooks.addAction( 'frontend/element_ready/column', addSectionHandler );
    elementorFrontend.hooks.addAction( 'frontend/element_ready/container', addSectionHandler );
});