'use strict';

jQuery(document).ready(function() {
    
    // precess tooltip on the page
    $('.app-tooltip').hover(function( event ) {
        if (!$(this).hasClass('loaded')) {
            myappUtil.loadTooltip(this);
        }
    });
});