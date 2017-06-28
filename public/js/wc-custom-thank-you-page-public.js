jQuery(document).ready(function($){
	'use strict';

	//Social Share Tabs
	$('ul.tabs li').click(function(){
		var tab_id = $(this).attr('data-tab');

		$('ul.tabs li').removeClass('current');
		$('.tab-content').removeClass('current');

		$(this).addClass('current');
		$("#"+tab_id).addClass('current');
	});

	//Purchase share on facebook
	$(document).on('click', '#wcctp-share-facebook', function(){
		var summary = $('#wcctp-purchase-share-facebook').val();
		var share_url = 'https://www.facebook.com/sharer.php?description='+summary;
		window.open( share_url, 500, 500 );
	});

	//Purchase tweet on twitter
	$(document).on('click', '#wcctp-tweet-twitter', function(){
		var summary = $('#wcctp-purchase-tweet-twitter').val();
		var tweet_url = 'https://twitter.com/intent/tweet?text='+summary;
		window.open( tweet_url, 500, 500 );
	});

	//Purchase share on google plus
	$(document).on('click', '#wcctp-share-google-plus', function(){
		var summary = $('#wcctp-purchase-share-google-plus').val();
		var share_url = 'https://plus.google.com/share?text='+summary;
		window.open( share_url, 500, 500 );
	});

	$('.wcctp-thank-you-products-display').bxSlider({
		mode: 'horizontal',
		moveSlides: 1,
		slideMargin: 10,
		infiniteLoop: true,
		slideWidth: 660,
		minSlides: 4,
		maxSlides: 99999,
		speed: 800,
		hideControlOnEnd: true,
		auto: true,
		controls: true,
		pager: false,
	});
});