jQuery(document).ready(function($){
	'use strict';
    $('#wcctp-thankyou-products').select2();
	//Tooltips
	$('.wcctp-tooltips').qtip({
        content: function(){
	        return $(this).data('tip');
	    },
        show: {
            effect: function() {
                $(this).slideDown();
            }
        },
        hide: {
            effect: function() {
                $(this).slideUp();
            }
        }
    });
});