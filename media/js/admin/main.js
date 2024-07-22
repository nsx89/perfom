$(document).ready(function(){
	$('body').on('click', '.main-ui-filter-search .main-ui-search', function(event){
		var form = $(this).closest('form');
		$('.main-ui-filter-submit', form).click();
	});

	$('body').on('click', '.js-main-ui-delete', function(event){
		var form = $(this).closest('form');
		$('.main-ui-filter-search-filter', form).val('');
		if ($('.js-filter').length) {
			$('.js-filter').val('');
		}
		$('.main-ui-item-icon-block', form).removeClass('main-ui-show');
		$('.main-ui-filter-submit', form).click();
	});

	$('body').on('click', '.js-adm-detail-toolbar-events', function(event){

		var add = $(this).attr('data-add');
		var del = $(this).attr('data-del');

		this.blur();BX.adminShowMenu(this, [
			{'LINK': add ,'GLOBAL_ICON':'adm-menu-edit','TEXT':'Добавить элемент'},
        	{
        		'LINK':"javascript:if(confirm('Будет удалена вся информация, связанная с этой записью. Продолжить?')) top.window.location.href='"+del+"';",
        		'GLOBAL_ICON':'adm-menu-delete',
        		'TEXT':'Удалить элемент'
        	}
        ], {
			active_class: 'adm-btn-active', 
			public_frame: '0'
		}); 
		return false;	
	});

	$('body').on('click', '.js-linked', function(event){
		var from = document.getElementById('js-linked-name');
		var to = document.getElementById('js-linked-code');

		to.value = translit(from.value);
	});

	$('body').on('keyup', '#js-linked-name', function(event){
		var from = $('#js-linked-name');
		var to = $('#js-linked-code');

		if (to.attr('data-value') == '') to.val(translit(from.val()));
	});

	$('body').on('click', '.js-adm-detail-tab', function(event){

		var id = $(this).attr('data-id');
		var form = $(this).closest('form');

		if ($('.adm-detail-tab-active').attr('data-id') == 1 && $('input[name=id]', form).val() == 0) {
			$('input[name=tab]', form).val(id);
			$('input[name=apply]', form).click();
			return false;
		}

		$('.adm-detail-tab').removeClass('adm-detail-tab-active');
		$(this).addClass('adm-detail-tab-active');
		$('.adm-detail-content').hide();
		var tab = $('#tab'+id);
		if (tab.length) tab.fadeIn(300);
		
		$('input[name=tab]', form).val(id);
		var element_id = $('input[name=id]', form).val();
		History.pushState("object or string", $('title').html(), '?lang=ru&edit='+element_id+'&tab='+id);
	});

	$('body').on('change keyup', '.js-input-sort, .js-input-number', function(event){
		if (this.value.match(/[^0-9.]/g)) {
			this.value = this.value.replace(/[^0-9]./g, '');
		}
	});

	/* --- Save Fixed --- */
	
	$('body').on('click', '.adm-btn-save-fixed', function(event){
		var btn = $(".adm-detail-content-btns input[name=apply]");
		if (btn.length) btn.click();
	});

	$(function(){
		var save_fixed = $('.adm-btn-save-fixed');
		var window_height = 110;
		
		function save_fixed_show(){
			if($(window).scrollTop() > window_height) {
				save_fixed.fadeIn(200);
			} else {
				save_fixed.fadeOut(100);
			}
		}

		save_fixed_show();
		$(window).scroll(function(){
			save_fixed_show();
		});
	});

	/* --- Image change --- */

	$('body').on('click', '.js-file-del', function(event){
		var wrapper = $(this).closest('.adm-fileinput-wrapper');
		$('.js-adm-fileview', wrapper).fadeOut(50);
		$('.js-adm-fileinput', wrapper).fadeIn(200);
		$('.js-adm-fileinput-img', wrapper).attr('src', '');
		var id = $('.js-file-img-del', wrapper).attr('data-id');
		$('.js-file-img-del', wrapper).val(id);
	});

	$('body').on('change', '.js-adm-fileinput-input', function(event){
		var val = $(this).val();
		val = val.replace("fakepath", "...");
		var wrapper = $(this).closest('.adm-fileinput-wrapper');
		$('.js-adm-fileinput', wrapper).fadeOut(50);
		$('.js-adm-fileview', wrapper).fadeIn(200);
		
		var input = this;
		if (input.files && input.files[0]) {
	    	var reader = new FileReader();

	    	reader.onload = function(e) {
	    		var img = $('.js-adm-fileinput-img', wrapper);
	    		img.attr('src', e.target.result);
	    		if (img.hasClass('js-adm-fileinput-img-crop')) {
	    			var id = img.attr('id');
	    			var wrap = img.closest('.cropper-image');
	    			$('.cropper-container', wrap).remove();
	    			$('.cropper-hidden', wrap).removeClass('cropper-hidden');
	    			wrap.html(wrap.html());
	    			$('img', wrap).css('width', '100%');
	    			setTimeout(function(){
						image_crop(id);
	    			}, 100);
	    		}
	    	}

	    	reader.readAsDataURL(input.files[0]);
	    	$('.js-file-img-del', wrapper).val(0);
	   	}
	});

	/* --- Video change --- */

	$('body').on('click', '.js-video-del', function(event){
		var wrapper = $(this).closest('.js-adm-video');
		$('.js-adm-video-view', wrapper).fadeOut(50);
		$('.js-adm-video-input', wrapper).fadeIn(200);
		$('.js-adm-video-view-name', wrapper).html('');
		var id = $('.js-file-video-del', wrapper).attr('data-id');
		$('.js-file-video-del', wrapper).val(id);
	});

	$('body').on('change', '.js-adm-video-input-val', function(event){
		var val = $(this).val();
		val = val.replace("fakepath", "...");
		var wrapper = $(this).closest('.js-adm-video');
		if (val != '') {
			$('.js-adm-video-input', wrapper).fadeOut(50);
			$('.js-adm-video-view', wrapper).fadeIn(200);
			$('.js-adm-video-view-name', wrapper).html(val);
			$('.js-file-video-del', wrapper).val(0);
		}
	});

	/* --- // --- */
});

function translit(word){
	var converter = {
		'а': 'a',    'б': 'b',    'в': 'v',    'г': 'g',    'д': 'd',
		'е': 'e',    'ё': 'e',    'ж': 'zh',   'з': 'z',    'и': 'i',
		'й': 'y',    'к': 'k',    'л': 'l',    'м': 'm',    'н': 'n',
		'о': 'o',    'п': 'p',    'р': 'r',    'с': 's',    'т': 't',
		'у': 'u',    'ф': 'f',    'х': 'h',    'ц': 'c',    'ч': 'ch',
		'ш': 'sh',   'щ': 'sch',  'ь': '',     'ы': 'y',    'ъ': '',
		'э': 'e',    'ю': 'yu',   'я': 'ya'
	};
 
	word = word.toLowerCase();
  
	var answer = '';
	for (var i = 0; i < word.length; ++i ) {
		if (converter[word[i]] == undefined){
			answer += word[i];
		} else {
			answer += converter[word[i]];
		}
	}
 
	answer = answer.replace(/[^-0-9a-z]/g, '-');
	answer = answer.replace(/[-]+/g, '-');
	answer = answer.replace(/^\-|-$/g, ''); 
	return answer;
}

/* --- Cropper --- */

var cropper;
function image_crop(id){
	const image = document.getElementById(id);
	var element = $('#'+id);
	var crop1 = element.attr('data-crop1');
	var crop2 = element.attr('data-crop2');
	cropper = new Cropper(image, {
	  aspectRatio: crop1 / crop2,
	  background: false,
	  zoomable: false,
	  zoomOnTouch: false,
	  zoomOnWheel: false,
	  guides: false,
	  crop(event) {
	  	var wrapper = element.closest('.js-adm-cropper');
	  	$('.js-adm-cropper-x', wrapper).val(event.detail.x);
	  	$('.js-adm-cropper-y', wrapper).val(event.detail.y);
	  	$('.js-adm-cropper-width', wrapper).val(event.detail.width);
	  	$('.js-adm-cropper-height', wrapper).val(event.detail.height);
	  },
	});
}

$(document).ready(function(){
	if ($('.chosen').length > 0) {
		$('.chosen').chosen({
			no_results_text: "Ничего не найдено...",
			width:'100%',
			search_contains: true
		});
	}

	$('body').on('change', '.js-filter', function(event){
		$('.main-ui-filter-submit').click();
	});
});

/* --- Constructor --- */

$(document).ready(function(){
	$('body').on('click', '.constructor-create', function(event){
		var type_id = $('.constructor-select').val();
		var constructor = $('.constructor-create-wrap');
		if (type_id == 0) {
			alert('Выберите тип блока');
		}
		else {
			if (constructor.hasClass('constructor-loading')) return;
			constructor.addClass('constructor-loading');
			var media_id = $(this).attr('data-media_id');

			$('.constructor-select').val(0);

			$.ajax({
		        url: top.location.href,
		        type: 'POST',
		        data: {
		            'constructor_create': 1
		            , 'type_id': type_id
		            , 'media_id' : media_id
		        },
		        success: function(html) {
		        	constructor.removeClass('constructor-loading');
					$('.constructor-items').append(html);
					console.log(html);
				},
				error: function(html){
					console.log(html);
				}
		    });
		}
	});

	$('body').on('click', '.constructor-item-delete', function(event){
		if (confirm('Вы уверены что хотите удалить блок?')) {
			var id = $(this).attr('data-id');
			var item = $(this).closest('.constructor-item');
			$.ajax({
		        url: top.location.href,
		        type: 'POST',
		        data: {
		            'constructor_item_delete': 1
		            , 'id': id
		        },
		        success: function(html) {
		        	console.log(html);
		        	item.remove();
				},
				error: function(html){
					console.log(html);
				}
		    });
		}
		else {
			return false;
		}
	});

	/* --- Markers --- */

	$('body').on('click', '.constructor-marker-create', function(event){
		var wrapper = $(this).closest('.constructor-marker-wrap');
		var wrap = $(this).closest('.constructor-marker-create-wrap');
		if (wrap.hasClass('constructor-marker-loading')) return;

		wrap.addClass('constructor-marker-loading');

		var media_id = $(this).attr('data-media_id');
		var constructor_id = $(this).attr('data-constructor_id');
		var number = $(this).attr('data-number');

		$.ajax({
	        url: top.location.href,
	        type: 'POST',
	        data: {
	            'constructor_marker_create': 1
	            , 'media_id': media_id
	            , 'constructor_id': constructor_id
	            , 'number' : number
	        },
	        success: function(html) {
	        	wrap.removeClass('constructor-marker-loading');
				$('.constructor-marker-items', wrapper).append(html);
				console.log(html);
			},
			error: function(html){
				console.log(html);
			}
	    });
	});

	$('body').on('click', '.constructor-marker-item-delete', function(event){
		if (confirm('Вы уверены что хотите удалить маркер?')) {
			var id = $(this).attr('data-id');
			var item = $(this).closest('.constructor-marker-item');
			$.ajax({
		        url: top.location.href,
		        type: 'POST',
		        data: {
		            'constructor_marker_delete': 1
		            , 'id': id
		        },
		        success: function(html) {
		        	console.log(html);
		        	item.remove();
				},
				error: function(html){
					console.log(html);
				}
		    });
		}
		else {
			return false;
		}
	});

	/* --- questions --- */

	$('body').on('click', '.constructor-question-create', function(event){
		var wrapper = $(this).closest('.constructor-question-wrap');
		var wrap = $(this).closest('.constructor-question-create-wrap');
		if (wrap.hasClass('constructor-question-loading')) return;

		wrap.addClass('constructor-question-loading');

		var media_id = $(this).attr('data-media_id');
		var constructor_id = $(this).attr('data-constructor_id');

		$.ajax({
	        url: top.location.href,
	        type: 'POST',
	        data: {
	            'constructor_question_create': 1
	            , 'media_id': media_id
	            , 'constructor_id': constructor_id
	        },
	        success: function(html) {
	        	wrap.removeClass('constructor-question-loading');
				$('.constructor-question-items', wrapper).append(html);
				console.log(html);
			},
			error: function(html){
				console.log(html);
			}
	    });
	});

	$('body').on('click', '.constructor-question-item-delete', function(event){
		if (confirm('Вы уверены что хотите удалить вопрос?')) {
			var id = $(this).attr('data-id');
			var item = $(this).closest('.constructor-question-item');
			$.ajax({
		        url: top.location.href,
		        type: 'POST',
		        data: {
		            'constructor_question_delete': 1
		            , 'id': id
		        },
		        success: function(html) {
		        	console.log(html);
		        	item.remove();
				},
				error: function(html){
					console.log(html);
				}
		    });
		}
		else {
			return false;
		}
	});

	/* --- answers --- */

	$('body').on('click', '.constructor-answer-create', function(event){
		var wrapper = $(this).closest('.constructor-answer-wrap');
		var wrap = $(this).closest('.constructor-answer-create-wrap');
		if (wrap.hasClass('constructor-answer-loading')) return;

		wrap.addClass('constructor-answer-loading');

		var media_id = $(this).attr('data-media_id');
		var question_id = $(this).attr('data-question_id');

		$.ajax({
	        url: top.location.href,
	        type: 'POST',
	        data: {
	            'constructor_answer_create': 1
	            , 'media_id': media_id
	            , 'question_id': question_id
	        },
	        success: function(html) {
	        	wrap.removeClass('constructor-answer-loading');
				$('.constructor-answer-items', wrapper).append(html);
				console.log(html);
			},
			error: function(html){
				console.log(html);
			}
	    });
	});

	$('body').on('click', '.constructor-answer-item-delete', function(event){
		if (confirm('Вы уверены что хотите удалить ответ?')) {
			var id = $(this).attr('data-id');
			var item = $(this).closest('.constructor-answer-item');
			$.ajax({
		        url: top.location.href,
		        type: 'POST',
		        data: {
		            'constructor_answer_delete': 1
		            , 'id': id
		        },
		        success: function(html) {
		        	console.log(html);
		        	item.remove();
				},
				error: function(html){
					console.log(html);
				}
		    });
		}
		else {
			return false;
		}
	});

	/* --- links --- */

	$('body').on('click', '.links-create', function(event){
		var wrapper = $(this).closest('.links-wrap');
		var wrap = $(this).closest('.links-create-wrap');
		if (wrap.hasClass('links-loading')) return;

		wrap.addClass('links-loading');

		var media_id = $(this).attr('data-media_id');

		$.ajax({
	        url: top.location.href,
	        type: 'POST',
	        data: {
	            'links_create': 1
	            , 'media_id': media_id
	        },
	        success: function(html) {
	        	wrap.removeClass('links-loading');
				$('.links-items', wrapper).append(html);
				console.log(html);
			},
			error: function(html){
				console.log(html);
			}
	    });
	});

	$('body').on('click', '.links-item-delete', function(event){
		if (confirm('Вы уверены что хотите удалить якорь?')) {
			var id = $(this).attr('data-id');
			var item = $(this).closest('.links-item');
			$.ajax({
		        url: top.location.href,
		        type: 'POST',
		        data: {
		            'links_delete': 1
		            , 'id': id
		        },
		        success: function(html) {
		        	console.log(html);
		        	item.remove();
				},
				error: function(html){
					console.log(html);
				}
		    });
		}
		else {
			return false;
		}
	});

	/* --- Multiple search --- */

	$('body').on('keyup', '.multibox-search', function(event){
	    var search = $(this).val().toLowerCase();
	    var parent = $(this).closest('.multibox');
	    $('.multibox-search-not-found', parent).hide();
	    if (search.length < 1) {
	        $('.multibox-item', parent).show();
	        return;
	    }
	    var show_count = 0;
	    $('.multibox-item', parent).each(function(){
	        var name = $('.multibox-item-name', this).html().toLowerCase();
	        if (!isNaN(search) && $('.multibox-item-id', this).length) {
	            name = $('.multibox-item-id', this).html().toLowerCase();
	        }
	        if (name.indexOf(search) >= 0) {
	            $(this).show();
	            show_count++;
	        }
	        else {
	            $(this).hide();
	        }
	    });
	    if (show_count == 0) {
	        $('.multibox-search-not-found', parent).show();
	    }
	});

	/* --- // --- */
});