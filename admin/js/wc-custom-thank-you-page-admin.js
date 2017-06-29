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

    var acc = document.getElementsByClassName("wcctp-accordion");
    var i;
    for (i = 0; i < acc.length; i++) {
        acc[i].onclick = function() {
            this.classList.toggle("active");
            var panel = this.nextElementSibling;
            if (panel.style.maxHeight){
                panel.style.maxHeight = null;
            } else {
                panel.style.maxHeight = panel.scrollHeight + "px";
            } 
        }
    }

    $(document).on('click', '.wcctp-accordion', function(){
        return false;
    });

    //Remove the logo
    $(document).on('click', '.wcctp-remove-logo', function(){
        var data = {
            'action' : 'wcctp_remove_thankyou_logo'
        }
        $.ajax({
            url: wcctp_admin_js_object.ajaxurl,
            type: 'POST',
            data: data,
            success: function( response ) {
                if( response == 'wcctp-logo-removed-successfully' ) {
                    $('.wcctp-preview-logo-box').html( '<p style="color: green;">Thank You Logo Is Removed!</p>' );
                }
            },
        });
    });
});