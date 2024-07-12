/* --- Dots --- */

function dots(){
	$('.dotdotdot').dotdotdot({});
}
dots();
$(document).ready(function(){
	dots();
});

/* --- Video --- */

$(document).ready(function(){
	setTimeout(function(){
		/*if ($('.m-media-video').length) {
			$('.m-media-video').each(function(){
				var video = $(this).attr('data-video');
				var html = '<iframe class="m-media-video-iframe" width="100%" height="100%" src="https://www.youtube.com/embed/'+video+'" frameborder="0" allowfullscreen=""></iframe>';
				$(this).html(html);
			});
		}*/
		if ($('.m-media-video').length) {
			$('.m-media-video').each(function(){
				var video = $(this).attr('data-video');
				var html = '<video preload="" controls controlsList="nodownload"><source src="'+video+'" type="video/mp4"></video>';
				$(this).html(html);
			});
		}
		/*
	
		*/
	}, 700);
});

/* --- Scroll top --- */

$(function(){
	var scroll_top = $('.m-media-scroll-top');
	var window_height = Number($(window).height() / 1.5);
	
	function scroll_top_show(){
		if($(window).scrollTop() > window_height) {
			scroll_top.fadeIn(200);
		} else {
			scroll_top.fadeOut(100);
		}
	}

	scroll_top_show();
	$(window).scroll(function(){
		scroll_top_show();
	});
 
	scroll_top.click(function(){
		$('html, body').animate({scrollTop: 0}, 300);
		return false;
	});
});

/* --- Scroll menu fixed --- */

$(function(){
	var menu = $('.m-media-menu-fixed');
	
	function scroll_menu_show(){
		var start = Number($('.m-media-line').outerHeight());
		var window_scroll = $(window).scrollTop();
		var menu_search = $('.m-media-menu-fixed-search');
		if(window_scroll > start) {
			menu.show();
			var header_height = Number($('header').outerHeight());
			var left_width = Number($('.m-media-left').width());
			menu.css('top', header_height+'px');
			menu.css('width', left_width+'px');
			menu_search.fadeIn(200);
		} else {
			menu.hide();
			menu_search.hide();
		}
	}

	scroll_menu_show();
	$(window).scroll(function(){
		scroll_menu_show();
	});
	$(window).resize(function(){
		scroll_menu_show();
	});
});

/* --- Audio --- */

var player;
$(document).ready(function(){
	if ($('.m-media-audio-file').length) {
		var controls =
		[
		    'play-large', // The large play button in the center
		    //'restart', // Restart playback
		    //'rewind', // Rewind by the seek time (default 10 seconds)
		    'play', // Play/pause playback
		    //'fast-forward', // Fast forward by the seek time (default 10 seconds)
		    'progress', // The progress bar and scrubber for playback and buffering
		    'current-time', // The current time of playback
		    //'duration', // The full duration of the media
		    'mute', // Toggle mute
		    'volume', // Volume control
		    'captions', // Toggle captions
		    'settings', // Settings menu
		    'pip', // Picture-in-picture (currently Safari only)
		    'airplay', // Airplay (currently Safari only)
		    'download', // Show a download button with a link to either the current source or a custom URL you specify in your options
		    'fullscreen' // Toggle fullscreen
		];
		$('.m-media-audio-file').each(function(){
			var id = $(this).attr('id');
		    player = new Plyr('#'+id,  { controls });
		});
	}
});

/* --- Mobile --- */

$('body').on('click', '.m-mobile-menu-item', function(){
	$('.m-mobile-menu-item').removeClass('m-mobile-menu-item-active');
	$(this).addClass('m-mobile-menu-item-active');
});

$('body').on('click', '.m-mobile-menu-dots', function(){
	var menu = $('.m-mobile-menu');
	if (menu.css('display') == 'none') {
		menu.fadeIn(300);
	}
	else {
		menu.fadeOut(100);
	}
});

$('body').on('click', '.m-media-mobile-close', function(){
	$('.m-mobile-menu').fadeOut(100);
});

$(document).click(function(event){
	if ($(event.target).closest('.m-mobile-menu-dots').length) return;
	if ($(event.target).closest('.m-media-left').length) return;

	if ($('.m-mobile-menu-items').css('display') != 'none') {
		$('.m-mobile-menu').fadeOut(100);
	}

	event.stopPropagation();
});

$('body').on('click', '.m-mobile-gallery-show-more', function(){
	$('.m-media-gallery-item-hide').fadeIn(200);
	$(this).hide();
});

/* --- Icons click --- */

$('body').on('click', '.m-media-item-icon-fav', function(){
	var width = $(this).outerWidth();
	$(this).css('width', width+'px');
	var num = Number($(this).html());
	if (isNaN(num)) num = 0;

	if ($(this).hasClass('m-media-item-icon-fav-active')) {
		$(this).removeClass('m-media-item-icon-fav-active');
		num--;
		value = 0;
	}	
	else {
		$(this).addClass('m-media-item-icon-fav-active');
		num++;
		value = 1;
	}
	if (num == 0) num = '&nbsp;';
	$(this).html(num);

	var item_id = $(this).attr('data-id');
	var media = $('.m-media').attr('data-url');
	$.ajax({
        url: media+'/ajax.php',
        type: 'POST',
        data: {
            'method': 'media_likes',
            'item_id' : item_id,
            'value' : value
        },
        success: function(html) {
        	console.log(html);
		},
		error: function(html) {
        	console.log(html);
		},
    });
});

$('body').on('click', '.m-media-item-icon-flag', function(){
	if ($(this).hasClass('m-media-item-icon-flag-active')) {
		$(this).removeClass('m-media-item-icon-flag-active');
		value = 0;
	}	
	else {
		$(this).addClass('m-media-item-icon-flag-active');
		value = 1;
	}

	var item_id = $(this).attr('data-id');
	var media = $('.m-media').attr('data-url');
	$.ajax({
        url: media+'/ajax.php',
        type: 'POST',
        data: {
            'method': 'media_flag',
            'item_id' : item_id,
            'value' : value
        },
        success: function(html) {
        	console.log(html);
		},
		error: function(html) {
        	console.log(html);
		},
    });
});

/* --- // --- */

$('body').on('click', '.m-media-detail-menu-link', function(){
	var link = $(this).attr('data-link');
	var element = $('#'+link);
	if (element.length) {
		var header_height = $('header').height();
		var element_top = element.offset().top - header_height - 80;
		$('html, body').animate({scrollTop: element_top}, 400);
	}
});

/* --- Items loading --- */

function items_loading(){
	var btn = $('.js-m-media-load');
	if (!btn.length) return;
	var all = btn.attr('data-all');
	var step = Number(btn.attr('data-step'));
	var start = Number(btn.attr('data-start'));
	var order = btn.attr('data-order');
	var where = btn.attr('data-where');
	var media = btn.attr('data-media');
	if (btn.hasClass('m-media-loading-show')) return;
	if (btn.hasClass('m-media-loading-end')) return;
	btn.addClass('m-media-loading-show');
	setTimeout(function(){
		$.ajax({
	        url: media+'/ajax.php',
	        type: 'POST',
	        data: {
	            'method': 'items_load',
	            'start' : start,
	            'step' : step,
	            'order' : order,
	            'where' : where
	        },
	        success: function(html) {
	        	btn.removeClass('m-media-loading-show');
	        	$('.m-media-items-list').append(html);
	        	start = start + step;
	        	btn.attr('data-start', start);
	        	if (start >= all) btn.addClass('m-media-loading-end');
	        	share_reload();
	        	dots();
			},
			error: function(html){
				console.log(html);
			}
	    });
	}, 400);
}

function items_loading_init(){
	start = $(window).scrollTop();
    end = Number($('.m-media-items').height()) - Number($('.m-media-items').offset().top) - 400;
    if(start > end) {
        items_loading();
    }
}

function share_reload() {
	$('.m-media-yandex-share').html('<script src="https://yastatic.net/share2/share.js"><\/script>');
}

$(document).scroll(function () {
    items_loading_init();
});
$(document).ready(function(){
	items_loading_init();
});

/* --- question save --- */ 

$('body').on('change', '.js-media-answer', function(){
	var question_id = $(this).attr('data-question_id');
	var answer_id = $(this).val();
	var value = 0;
	if ($(this).prop('checked')) value = 1;
	var media = $(this).attr('data-media');
	$.ajax({
        url: media+'/ajax.php',
        type: 'POST',
        data: {
            'method': 'question_history',
            'question_id' : question_id,
            'answer_id' : answer_id,
            'value' : value
        },
        success: function(html) {
        	console.log(html);
		},
		error: function(html) {
        	console.log(html);
		},
    });
});

$('body').on('click', '.js-media-answer-result', function(){
	var btn = $(this);
	var question_id = btn.attr('data-question_id');
	var media = btn.attr('data-media');
	var wrap = btn.closest('.m-media-quiz');
	var container = $('.m-media-quiz-answers', wrap);
	if (btn.hasClass('media-answer-result-loading')) return;
	btn.addClass('media-answer-result-loading');
	$.ajax({
        url: media+'/ajax.php',
        type: 'POST',
        data: {
            'method': 'question_result',
            'question_id' : question_id
        },
        success: function(html) {
        	btn.removeClass('media-answer-result-loading');
        	btn.addClass('m-media-quiz-result-btn-hidden');
        	container.html(html);
		},
		error: function(html) {
        	console.log(html);
		},
    });
});

/* --- search --- */

$('body').on('keyup focus', '.js-media-search', function(){
	var form = $(this).closest('form');
	var search = $(this).val();
	if (search.length < 3) {
		$('.m-media-search-items').hide();
		return;
	}
	if (form.hasClass('media-form-loading')) return;
	form.addClass('media-form-loading');
	var media = $('.m-media').attr('data-url');
	console.log(search);
	$.ajax({
        url: media+'/ajax.php',
        type: 'POST',
        data: {
            'method': 'search_items',
            'search': search
        },
        success: function(html) {
        	//console.log(html);
        	form.removeClass('media-form-loading');
        	if ($('.m-media-search-items').length) {
        		$('.m-media-search-items').html(html).show();;
        	}
		},
		error: function(html) {
        	//console.log(html);
		},
    });
});

$('body').on('click', '.js-m-media-search-icon, .m-media-search-item-all', function(){
	var form = $(this).closest('form');
	var submit = $('input[type=submit]', form).click();
});

$(document).click(function(event){
	if ($(event.target).closest('.m-media-search-items').length) return;
	if ($(event.target).closest('.js-media-search').length) return;

	$('.m-media-search-items').hide();

	event.stopPropagation();
});

/* --- Modal --- */

$('body').on('click', '[data-type="complain-popup-open"]', function() {
	$('[data-type="complain-popup"]').fadeIn();
	if(window.innerWidth > 700) $('[data-type="complain-overlay"]').fadeIn();
});

$(document).click(function(event){
	if ($(event.target).closest('[data-type="complain-popup"]').length) return;
	if ($(event.target).closest('[data-type="complain-popup-open"]').length) return;

	$('[data-type="complain-overlay"]').fadeOut();
	$('[data-type="complain-popup"]').fadeOut();

	event.stopPropagation();
});

$('[data-type="complain-popup-close"]').on('click', function() {
	$('[data-type="complain-overlay"]').fadeOut();
	$('[data-type="complain-popup"]').fadeOut();
})

/* --- Complain --- */

$(document).ready(function(){
	$("body").on('click', '.js-complain-submit', function(event){
		event.preventDefault();
		var form = $(this).closest('form');
		$('.complain-alert', form).hide();
		$('input, textarea', form).removeClass('complain-input-red');
		if (form.hasClass('form-loading')) return;
		form_id = form.attr('id');
		var url = location.href;
		var data = form.serialize()+'&method=complain'+'&url='+url;
		var media = $('.m-media').attr('data-url');
		var name = $('input[name=name]', form).val();
		var email = $('input[name=email]', form).val();
		var text = $('textarea[name=text]', form).val();
		if (name == '' || email == '' || text == '') {
			$('.complain-alert', form).html('Заполните все обязательные поля').fadeIn(300);
			setTimeout(function(){
				if (name == '') $('input[name=name]', form).addClass('complain-input-red');
				if (email == '') $('input[name=email]', form).addClass('complain-input-red');
				if (text == '') $('textarea[name=text]', form).addClass('complain-input-red');
			}, 100);
			return false;
		}
		var policy = false;
		if ($('input[name=complain_policy]', form).prop('checked')) policy = true;
		if (!policy) {
			$('.complain-alert', form).html('Вы должны дать согласие на обработку персональных данных').fadeIn(300);
			return false;
		}
		form.addClass('form-loading');
		$.ajax({
			url: media+'/ajax.php',
			type: 'POST',
			data: data,
			success: function(html) {
				console.log(html);
				form.removeClass('form-loading');
				$('.complain-form-title').hide();
				$('.complain-form form').html('<span class="complain-result">Ваша жалоба отправлена<br>на рассмотрение</span>');
			},
			error: function(html) {
				console.log(html);
			}
		});
	});
});

/* --- // --- */