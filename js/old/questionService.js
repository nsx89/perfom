$('[data-type="q-form-select"]').styler();
$('[data-type="q-form-file"]').styler();

/**
 * форма задать вопрос
 */
$(document).mouseup(function(e) {
	var container = $('[data-type="q-popup"]');
	var target = e.target;
	if (container.css('display')!='none' && container.has(e.target).length === 0 && !$(target).is('[data-type="q-popup"]')) {
		$('[data-type="overlay"]').fadeOut();
		$('[data-type="q-popup"]').fadeOut();
		return false;
	}
});
$('[data-type="q-popup-close"]').on('click', function() {
	$('[data-type="overlay"]').fadeOut();
	$('[data-type="q-popup"]').fadeOut();
})
/**
 * окно результата
 */
$(document).mouseup(function(e) {
	var container = $('[data-type="q-popup-res"]');
	var target = e.target;
	if (container.css('display')!='none' && container.has(e.target).length === 0 && !$(target).is('[data-type="q-popup-res"]')) {
		$('[data-type="overlay"]').fadeOut();
		$('[data-type="q-popup-res"]').fadeOut();
		return false;
	}
});
$('[data-type="q-popup-res-close"]').on('click', function() {
	$('[data-type="overlay"]').fadeOut();
	$('[data-type="q-popup-res"]').fadeOut();
})
$('body').on('click', '[data-type="q-popup-open"]', function() {
	console.log('open');
	$('[data-type="q-popup"]').fadeIn();
	if(window.innerWidth > 700) $('[data-type="overlay"]').fadeIn();
})
$('body').on('click', '[data-type="q-popup-open"]', function() {
	yaCounter22165486.reachGoal('question');
})

//textarea autoresize
$('.autogrow').click(function() {
	$(this).autogrow({vertical: true, horizontal: false});
})

function validateEmailOrder(email) {
	var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	return re.test(email);
}

$().ready(function() { 

	//jTruncate initialization
	$('[ data-type="short-answ"]').jTruncate({  
		length: 218,  
		minTrail: 20,  
		moreText: "Раскрыть ответ полностью ",  
		lessText: "Спяртать полный ответ ",  
		ellipsisText: "...",  
		moreAni: 0,  
		lessAni: 0  
	});
	//accordion
	$('[ data-type="subj"]').click(function(){
		$(".e-aqs-subj-wrap").each(function(){
			$(this).slideUp();
		})
		var items = $(this).parents(".e-aqs-subj").find(".e-aqs-subj-wrap");
		if(items.is(":visible")) {
			items.slideUp();
		}
		else {
			items.slideDown();
		}		
	})


//mod mobile
	$('[data-type="mod-qst"]').jTruncate({  
		length: 150,  
		minTrail: 20,  
		moreText: "Подробнее",  
		lessText: "Свернуть",  
		ellipsisText: "...",  
		moreAni: 0,  
		lessAni: 0  
	});

	$('.truncate_more_link').click(function(){
		var answer = $(this).parents('.e-qm-quest-item').find('.e-qm-quest-item-answ');
		if($(this).hasClass('truncate_more_i')) {
			answer.slideUp();
		}
		if($(this).hasClass('truncate_less_i')) {
			
			answer.slideDown();
		}
	})

	//input style
	 $('[name="aqs-name"]').click(function(){
		 $(this).closest('form').find('.e-aqs-form-button').prop( "disabled", false );
	 	if($(this).val()=="Введите ваше имя") {
	 		$(this).val('');
	 		$(this).removeClass("e-aqs-form-err");
	 	}
	 })
	 $('[name="aqs-email"]').click(function(){
	 	$('.e-aqs-form-button').prop( "disabled", false );
	 	if($(this).val()=="Введите ваш e-mail" || $(this).val()=="Неверный формат e-mail") {
	 		$(this).val('');
	 		$(this).removeClass("e-aqs-form-err");
	 	}
	 })
  $('[name="aqs-email"]').change(function(){
		$(this).closest('form').find('.e-aqs-form-button').prop( "disabled", false );
      $(this).removeClass("e-aqs-form-err");
  })
  $('[name="aqs-loc"]').click(function(){
    $('.e-aqs-form-button').prop( "disabled", false );
    $(this).removeClass("e-aqs-form-err");
    if($(this).val()=="Введите город") {
      $(this).val('');
    }
  })
  $('[name="aqs-loc"]').change(function(){
		$(this).closest('form').find('.e-aqs-form-button').prop( "disabled", false );
    $(this).removeClass("e-aqs-form-err");
    if($(this).val()=="Введите город") {
      $(this).val('');
    }
  })
	 /*$('[name="aqs-tel"]').click(function(){
		 $(this).closest('form').find('.e-aqs-form-button').prop( "disabled", false );
	 	$(this).mask('P000000000000');
	 	$(this).removeClass("e-aqs-form-err");
	 	if($(this).val()=="Введите номер телефона" || $(this).val()=="Неверный формат номера") {
			$(this).val('');
	 		$(this).removeClass("e-aqs-form-err");
	 	}
	 })*/
	 $('.e-aqs-select-wrap').click(function(){
		 $(this).closest('form').find('.e-aqs-form-button').prop( "disabled", false );
	 	$(this).removeClass("e-aqs-form-err");
	 })
	 $('[name="aqs-qst"]').click(function(){
		 $(this).closest('form').find('.e-aqs-form-button').prop( "disabled", false );
	 	if($(this).val()=="Введите вопрос" || $(this).val()=="Вопрос должен содержать более 10 символов") {
			$(this).closest('form').find('[name="aqs-qst"]').val('');
	 		$(this).removeClass("e-aqs-form-err");
	 	}
	 })

	var fileErr = 0;
	 $('[name="aqs-file"]').change(function() {
		 $(this).closest('form').find('.e-aqs-form-button').prop( "disabled", false );
		 $(this).closest('form').find('.e-aqs-file-lbl').hide();
		 var color='#000';
     var filename = $(this).val().replace(/.*\\/, "");
     if(filename=="") {
     	filename = "";
     	color="#000";
     	fileErr = 0;
		 $(this).closest('form').find('.e-aqs-file-lbl').show();
     }
      else {
			 var parts = filename.split('.');
			 var fileExt = parts.pop();
			 var fileSize = this.files[0].size/1024/1024;
        //проверяем тип на случай, если не работает accept
        if(fileExt.toUpperCase()=='JPEG'||fileExt.toUpperCase()=="JPG"||fileExt.toUpperCase()=="PNG"||fileExt.toUpperCase()=="PDF"||fileExt.toUpperCase()=="RAR"||fileExt.toUpperCase()=="ZIP"){
          //проверяем размер
          if (fileSize > 10) {
            filename = "размер превышает 10МБ";
            color = "#ED5C5C";
            $(this).val('');
            fileErr++;
		  } else {
			fileErr = 0;
          }
        }
        else {
          filename = "недопустимый формат";
          color = "#ED5C5C";
          $(this).val('');
          fileErr++;
        }
     }
		$(this).closest('form').find(".e-aqs-file-name").html(filename).css('color',color);
});

$('.aqs_policy_label').click(function() {
	$(this).closest('form').find('.e-aqs-form-button').prop( "disabled", false );
	$(this).removeClass("e-aqs-form-err");
})

//запрет Enter
$('[data-event="no-enter"]').keypress(function (event) {
  if (event.which == '13') {
      return false;
  }
})

//получаем ссылку на текущую страницу
	$(document).ready(function(){
    var href = location.href;
    $('[name="aqs-page"]').val(href);
    $('#cb-page').val(href);
	})
	$('.e-aqs-form-button').click(function(){		
		$(this).prop( "disabled", true );
		if (typeof yaCounter22165486 !== 'undefined') {
			yaCounter22165486.reachGoal('question_send');
		}

		var key = Math.floor(Math.random()*10000);
		var form = $(this).closest('form');
		$.cookie("rand", key, {domain: domain, path: '/'});		

		//form validation
		var err = 0;

		if(form.find('[name="aqs-name"]').val()==""||form.find('[name="aqs-name"]').val()=="Введите ваше имя") {
			form.find('[name="aqs-name"]').addClass("e-aqs-form-err");
			form.find('[name="aqs-name"]').val("Введите ваше имя");
			err++;
		}

		if(form.find('[name="aqs-email"]').val()=="" || form.find('[name="aqs-email"]').val()=="Введите ваш e-mail") {
			form.find('[name="aqs-email"]').addClass("e-aqs-form-err");
			form.find('[name="aqs-email"]').val("Введите ваш e-mail");
			err++;
		}
		else {
			if(validateEmailOrder(form.find('[name="aqs-email"]').val())){
			 	
			}
			else {
				form.find('[name="aqs-email"]').addClass("e-aqs-form-err");
				form.find('[name="aqs-email"]').val("Неверный формат e-mail");
				err++;
			}
		}

		if(form.find('[name="aqs-tel"]').val().length==0 || form.find('[name="aqs-tel"]').val()=="Введите номер телефона") {
			// $('[name="aqs-tel"]').addClass("e-aqs-form-err");
			// $('[name="aqs-tel"]').val("Введите номер телефона");
			// err++;
		}
		else {
			/*if(form.find('[name="aqs-tel"]').val().length < 11 || form.find('[name="aqs-tel"]').val().length > 13) {
				form.find('[name="aqs-tel"]').addClass("e-aqs-form-err");
				form.find('[name="aqs-tel"]').val("Неверный формат номера");
			err++;
			}*/
		}

		if(checkTelNumber(form.find('[data-tel="yes"]')) === false) {
			err++;
		}
		if(form.find('[name="aqs-subj"]').val()=="" || !form.find('[name="aqs-subj"]').val()) {
			form.find('.e-aqs-select-wrap').addClass("e-aqs-form-err");
			err++;
		}

		if(form.find('[name="aqs-qst"]').val()=="" || form.find('[name="aqs-qst"]').val()=="Введите вопрос") {
			form.find('[name="aqs-qst"]').addClass("e-aqs-form-err");
			form.find('[name="aqs-qst"]').val("Введите вопрос");
			err++;
		}
		else {
			if (form.find('[name="aqs-qst"]').val().length < 10) {
				form.find('[name="aqs-qst"]').addClass("e-aqs-form-err");
				form.find('[name="aqs-qst"]').val("Вопрос должен содержать более 10 символов");
				err++;
			}
		}
		if (form.find('[name="aqs_policy"]').prop('checked') == false) {
			form.find('.aqs_policy_label').addClass("e-aqs-form-err");
			err++;
		}
    if(form.find('[name="aqs-loc"]').val()==""||form.find('[name="aqs-loc"]').val()=="Введите город") {
			form.find('[name="aqs-loc"]').addClass("e-aqs-form-err");
			form.find('[name="aqs-loc"]').val("Введите город");
      err++;
    }

		//send form
		if( fileErr == 0 && err == 0) {
			form.find('.e-aqs-form-button').hide();
			form.find('.e-aqs-form-loader').show();
		if(form.find('[name="aqs-page"]').val() == ""){
			form.find('[name="aqs-page"]').val('https://evroplast.ru/');
		};
			var new_val = hash_data(form.find('[name="aqs-qst"]').val(),key);
			if(form.find('[name="aqs-file"]').val()!='') {
				//var form = document.forms.aqsMainForm;
				var formCurrent = form[0];
				var formData = new FormData(formCurrent);
				var xhr = new XMLHttpRequest();
				xhr.open("POST", "/ajax/question_service.php?type=qst&new_val="+new_val);          
				xhr.onreadystatechange = function () {
				  if (xhr.readyState == 4) {
				    if (xhr.status == 200) {
						var data = xhr.responseText;
						$.cookie("rand", null, {domain: domain, path: '/'});
						$('[data-type="q-popup"]').hide();
						$('[data-type="q-popup-res"]').find('[data-type="aqs-rqst"]').html(data);
						$('[data-type="q-popup-res"]').fadeIn();
						form.find('.e-aqs-form-loader').hide();
						form.find('.e-aqs-form-button').show();
						form.trigger("reset");
						form.find('.e-aqs-file-lbl').show();
						form.find('[data-type="add-file"]').html('');
						form.find('[data-type="add-file"]').css("color","#000");
						form.find('.e-aqs-select-wrap').find('.jq-selectbox__select-text').html('тема вопроса*');
						form.find('.e-aqs-form-button').prop( "disabled", false );
						setTimeout(function(){
							$('[data-type="q-popup-res"]').fadeOut();
							$('[data-type="overlay"]').fadeOut();
						},8000);
				    }
				  }
				};			
				xhr.send(formData);
			}
			else {
				$.ajax({
					type: "POST",
					url: "/ajax/question_service.php?type=qst&new_val="+new_val,
					data: form.serialize(),
					success: function(data){
           				$.cookie("rand", null, {domain: domain, path: '/'});
						$('[data-type="q-popup"]').hide();
						$('[data-type="q-popup-res"]').find('[data-type="aqs-rqst"]').html(data);
						$('[data-type="q-popup-res"]').fadeIn();
						form.find('.e-aqs-form-loader').hide();
						form.find('.e-aqs-form-button').show();
						form.trigger("reset");
						form.find('.e-aqs-select-wrap').find('.jq-selectbox__select-text').html('тема вопроса*');
						form.find('.e-aqs-form-button').prop( "disabled", false );
						setTimeout(function(){
							$('[data-type="q-popup-res"]').fadeOut();
							$('[data-type="overlay"]').fadeOut();
						},8000);
					}        
				})
			}				
			
		}
	});

//choose new subject

$('[data-type="new-subj"]').click(function() {
	var wrap = $(this).parents('.e-ap-redirect-btn');
	if( wrap.hasClass("e-ap-redirect-btn-act")) {
		wrap.removeClass("e-ap-redirect-btn-act");
	}
	else {
		wrap.addClass("e-ap-redirect-btn-act");		
	}
})
$('[data-type="new-subj-item"]').click(function() {
	var wrap = $(this).parents('.e-ap-redirect-btn');
	var wrapItem = $(this).parents('.e-ap-new-subj-items');
	var item = wrapItem.find('.e-ap-new-subj-items-value');
	if( wrapItem.hasClass("active")) {
		wrapItem.removeClass("active");
		item.slideUp();
	}
	else {
		var act = $('.e-ap-new-subj').find(".active");
		var actItem = act.find('.e-ap-new-subj-items-value');
		act.removeClass("active");
		actItem.slideUp();
		wrapItem.addClass("active");
		item.slideDown();
	}
})
$('[data-type="new-subj-form-subm"]').click(function(){
	$(this).prop( "disabled", true );
	var checked = 0;
	$('[name="ap-subj"]').each(function(){
		if($(this).prop("checked")) {
			checked++;
		}
	})
	if(checked == 0) {
		alert("Выберите новую тему!");
		$('[data-type="new-subj-form-subm"]').prop( "disabled", false );
	}
	else {
		var name = $('.e-qs-user').attr('data-user');	
		$.ajax({			
			type: "POST",
			url: "/ajax/question_service.php?type=rdrct&name="+name,
			data: $('[data-type="new-subj-form"]').serialize(),
			error: function () {
         var statusCode = request.status;
         alert('Произошла ошибка, повторите попытку еще раз.');
     		},
        	success: function(data){
        		if (data!="error") {
        			$('[data-type="new-subj-form"]').trigger("reset");        			
        			$('.e-ap-redirect-btn').removeClass("e-ap-redirect-btn-act");
        			$('.e-ap-redirect-btn').hide();
        			$('.e-ap-new-subj-message').html(data);
        			$('.e-ap-new-subj-message').show();
        			$('[data-type="ap-putoff"]').hide(); 
        			$('.e-ap-answ').hide();
        			$('.e-ap-headers-buttons-stat').html("Вопрос перенаправлен");
        			$('.e-ap-headers-buttons-stat').addClass("black");
        			$('[data-type="new-subj-form-subm"]').prop( "disabled", false );
        			//$('.e-ap-comments').hide();
     				//$('.e-ap-mod-comment').hide();      			
        		}
        		else {
        			alert("Произошла ошибка, повторите попытку еще раз.");
        		}           
     		    
        }        
	    })
	}
})
$('.e-ap-redirect-btn [type="reset"]').click(function(){
	var wrap = $(this).parents('.e-ap-redirect-btn');
	wrap.removeClass("e-ap-redirect-btn-act");
})
$('[data-type="reset-new-subj"]').click(function(){
	var wrap = $(this).parents('.e-ap-redirect-btn');
	var act = $('.e-ap-new-subj').find(".active");
	//actItem = act.find('.e-ap-new-subj-items-value');
	act.removeClass("active");
	wrap.removeClass("e-ap-redirect-btn-act");
	//actItem.slideUp();
})

//put off answer
$('[data-type="ap-putoff"]').click(function() {
	$(this).prop( "disabled", true );
	var qst_id = $('[data-type="ap-title"]').attr("data-id");
	//console.log(qst_id);
	$.ajax({
		type: "POST",
		url: "/ajax/question_service.php?type=putoff&id="+qst_id,
		error: function () {
      var statusCode = request.status;
      alert('Произошла ошибка, повторите попытку еще раз.');
  		},
     	success: function(data){
     		if (data!="error") {
     			$('[data-type="ap-putoff"]').addClass("e-ap-putoff-btn-act");
     			$('[data-type="ap-putoff-txt"]').html("Ответ на вопрос отложен");
     			$('[data-type="ap-putoff"]').css("cursor","default");   			
     			$('.new-subj-title').addClass("new-subj-title-notactive");
     			$('.e-ap-answ').addClass("e-ap-answ-putoff");
        		$('.e-ap-renew').show();
        		$('.e-ap-headers-buttons-stat').html("Вопрос отложен");
        		$('.e-ap-headers-buttons-stat').addClass("black");
        		$('[data-type="ap-putoff"]').prop( "disabled", false );
     		}
     		else {
     			alert("Произошла ошибка, повторите попытку еще раз.");
     		}           
  		    
     }        
    })
})

//renew answer
$('[data-type="ap-renew"]').click(function() {
var qst_id = $('[data-type="ap-title"]').attr("data-id");
	$.ajax({
		type: "POST",
		url: "/ajax/question_service.php?type=puton&id="+qst_id,
		error: function () {
      var statusCode = request.status;
      alert('Произошла ошибка, повторите попытку еще раз.');
  		},
     	success: function(data){
     		if (data!="error") {
     			$('.e-ap-putoff-btn').removeClass("e-ap-putoff-btn-act");
				$('.e-ap-putoff-btn span').html("Отложить вопрос"); 		
				$('.e-ap-putoff-btn').css("cursor","pointer");
				$('.new-subj-title').removeClass("new-subj-title-notactive");
				$('.e-ap-answ').removeClass("e-ap-answ-putoff");
				$('.e-ap-renew').hide();				
     		}
     		else {
     			alert("Произошла ошибка, повторите попытку еще раз.");
     		}           
  		    
     }        
    })
});

//delete question

$('[data-type="ap-del"]').click(function() {
	$(this).prop( "disabled", true );
	var qst_id = $('[data-type="ap-title"]').attr("data-id");
	$.ajax({
		type: "POST",
		url: "/ajax/question_service.php?type=del&id="+qst_id,
		error: function () {
      var statusCode = request.status;
      alert('Произошла ошибка, повторите попытку еще раз.');
  		},
     	success: function(data){
     		// mes = '<div class="bx-qs-auth e-aqs-del-qst">Вопрос успешно удален!</div>';
     		// mes += '<a href="/question_service/moderation.php" class="e-ap-mod-return-del"><i class="icon-arrow-left"></i>Перейти на страницу модерации</a>';
     		// $('.answ-page').html(mes);
     		location.reload()
     }        
    })
})

//check question

$('[data-type="ap-check"]').click(function() {
	$(this).prop( "disabled", true );
	var qst_id = $('[data-type="ap-title"]').attr("data-id");
	var who = $('.e-qs-user').attr('data-user');
	$.ajax({
		type: "POST",
		url: "/ajax/question_service.php?type=check&id="+qst_id,
		error: function () {
      var statusCode = request.status;
      alert('Произошла ошибка, повторите попытку еще раз.');
  		},
     	success: function(data){
     		// $('[data-type="ap-check"]').hide();
     		// $('[data-type="ap-del"]').hide();
     		// $('.e-ap-headers-buttons-stat').html("Новый вопрос");
     		location.reload()
     }        
    })
})

//answer
$('[name="ap-answ-text"]').focus(function() {
	$('[data-type="answ-plchldr"]').css('opacity','0');
	$('[data-type="ap-answ-send"]').prop( "disabled", false );
})
$('[name="ap-answ-text"]').blur(function() {
	if($(this).val()=="") {
		$('[data-type="answ-plchldr"]').css('opacity','1');
	}	
})
$('[data-type="ap-answ-reset"]').click(function(){
	$('[data-type="answ-plchldr"]').css('opacity','1');
	$('[data-type="ap-answ-send"]').prop( "disabled", false );
	$('[data-type="ap-answ-send"]').html("Отправить ответ");
})

$('[name="ap-answ-file"]').change(function() {
	var wrap = $(this).closest('.answ-wrap');
	wrap.find('[data-type="ap-answ-send"]').prop( "disabled", false );
	 var filepath = $(this).val();
	 if(filepath!="") {
	 	var filename = $(this).val().replace(/.*\\/, "");
	 	wrap.find('.e-ap-answ-file>span').html(filename);
	 	wrap.find('.e-ap-answ-file').show();
	 }
	 else {
	 	wrap.find('.e-ap-answ-file>span').html("");
	 	wrap.find('.e-ap-answ-file').hide();
	 }
});

//reset
$('[data-type="ap-answ-reset"]').click(function() {
	$('.e-ap-answ-right textarea').val("");
	$('[name="ap-file"]').val("");
	$('.e-ap-answ-file>span').html("");
	$('.e-ap-answ-file').hide();
	$('[data-type="ap-answ-send"]').prop( "disabled", false );
})

//send answer
$('[data-type="ap-answ-send"]').click(function() {

	$(this).prop( "disabled", true );

	if($('.e-ap-answ-right textarea').val() == "") {
		alert("Поле для ответа не заполнено.")
	}
	else {
		var name = $('.e-qs-user').attr('data-user');
		var id = $('[data-type="ap-title"]').attr("data-id");
		var text = $('.e-ap-answ-right textarea').val();
		text = text.replace(/\n/g, "<br>");
		var filepath = $('[name="ap-answ-file"]').val();
		if(filepath != "") {
			var filename = filepath.replace(/.*\\/, "");
		}
		if($('[name="ap-answ-file"]').val()!='') {
			var form = document.forms.apAnsw;
			var formData = new FormData(form);
			var xhr = new XMLHttpRequest();		
			xhr.open("POST", "/ajax/question_service.php?type=answ&id="+id+"&name="+name);          
			xhr.onreadystatechange = function () {
			  if (xhr.readyState == 4) {
			    if (xhr.status == 200) {
			      data = xhr.responseText;		      
					$('.e-ap-answ-file').hide();
					$('.e-ap-answ').hide();					
					$('.e-ap-spec-answ-text').html(text);
					if(filepath != "") {
						$('.e-ap-spec-answ-file a').attr("href",filepath);
						$('.e-ap-spec-answ-file a').html(filename);
						$('.e-ap-spec-answ-file').show();
					}
					$('.e-ap-spec-answ-attr').html(data);
					$('.for-ajax').show();
					$('#apAnsw').trigger("reset");
			      $('.e-ap-answ-file>span').html("");
			      $('.e-ap-putoff-btn').hide();
			      $('.e-ap-redirect-btn').hide();
			      $('.e-ap-headers-buttons-stat').html("Ответ отправлен");
	        		$('.e-ap-headers-buttons-stat').addClass("green");
	        		$('.e-ap-headers-buttons-stat').removeClass("black");
	        		$('.e-ap-headers-buttons-stat').removeClass("red");
	        		$('[data-type="ap-answ-send"]').prop( "disabled", false );
			    }
			  }
			};
			xhr.send(formData);
		}
		else {
			$.ajax({
				type: "POST",
				url: "/ajax/question_service.php?type=answ&id="+id+"&name="+name,
				data: $("#apAnsw").serialize(),
			success: function(data){
					$('.e-ap-answ-file').hide();
					$('.e-ap-answ').hide();
					$('.e-ap-spec-answ-text').html(text);
					if(filepath != "") {
						$('.e-ap-spec-answ-file a').attr("href",filepath);
						$('.e-ap-spec-answ-file a').html(filename);
						$('.e-ap-spec-answ-file').show();
					}
					$('.e-ap-spec-answ-attr').html(data);
					$('.for-ajax').show();
					$('#apAnsw').trigger("reset");
			      $('.e-ap-answ-file>span').html("");
			      $('.e-ap-putoff-btn').hide();
			      $('.e-ap-redirect-btn').hide();
			      $('.e-ap-headers-buttons-stat').html("Ответ отправлен");
	        		$('.e-ap-headers-buttons-stat').addClass("green");
	        		$('.e-ap-headers-buttons-stat').removeClass("black");
	        		$('.e-ap-headers-buttons-stat').removeClass("red");
	        		$('[data-type="ap-answ-send"]').prop( "disabled", false );
				}        
			})
		}
		
	}
});

//send answer mob
$('[data-type="ap-answ-send-mob"]').click(function() {

	$(this).prop( "disabled", true );

	if($('.e-ap-answ-right textarea').val() == "") {
		alert("Поле для ответа не заполнено.")
	}
	else {
		var name = $('.e-qs-user').attr('data-user');
		var id = $('[data-type="ap-title"]').attr("data-id");
		var text = $('.e-ap-answ-right textarea').val();
		text = text.replace(/\n/g, "<br>");
		var filepath = $('[name="ap-answ-file"]').val();
		if(filepath != "") {
			var filename = filepath.replace(/.*\\/, "");
		}
		if($('[name="ap-answ-file"]').val()!='') {
			form = document.forms.apAnsw;      
			var formData = new FormData(form);
			var xhr = new XMLHttpRequest();		
			xhr.open("POST", "/ajax/question_service.php?type=answ&id="+id+"&name="+name);          
			xhr.onreadystatechange = function () {
			  if (xhr.readyState == 4) {
			    if (xhr.status == 200) {
			      data = xhr.responseText;		      
					$('.e-ap-answ-file').hide();
					$('.e-ap-answ').hide();					
					$('.e-ap-spec-answ-text').html(text);
					if(filepath != "") {
						$('.e-ap-spec-answ-file a').attr("href",filepath);
						$('.e-ap-spec-answ-file a').html(filename);
						$('.e-ap-spec-answ-file').show();
					}
					$('.e-ap-spec-answ-attr').html(data);
					$('.for-ajax').show();
					$('#apAnsw').trigger("reset");
			      $('.e-ap-answ-file>span').html("");
			      $('.e-ap-putoff-btn').hide();
			      $('.e-ap-redirect-btn').hide();
			      $('.e-ap-headers-buttons').hide();
			      $('.e-ap-headers-buttons-stat').html("Ответ отправлен");
	        		$('.e-ap-headers-buttons-stat').addClass("green");
	        		$('.e-ap-headers-buttons-stat').removeClass("black");
	        		$('.e-ap-headers-buttons-stat').removeClass("red");
	        		$('[data-type="ap-answ-send-mob"]').prop( "disabled", false );

			    }
			  }
			};
			xhr.send(formData);
		}
		else {
			$.ajax({
				type: "POST",
				url: "/ajax/question_service.php?type=answ&id="+id+"&name="+name,
				data: $("#apAnsw").serialize(),
			success: function(data){
					$('.e-ap-answ-file').hide();
					$('.e-ap-answ').hide();
					$('.e-ap-spec-answ-text').html(text);
					if(filepath != "") {
						$('.e-ap-spec-answ-file a').attr("href",filepath);
						$('.e-ap-spec-answ-file a').html(filename);
						$('.e-ap-spec-answ-file').show();
					}
					$('.e-ap-spec-answ-attr').html(data);
					$('.for-ajax').show();
					$('#apAnsw').trigger("reset");
			      $('.e-ap-answ-file>span').html("");
			      $('.e-ap-putoff-btn').hide();
			      $('.e-ap-redirect-btn').hide();
			      $('.e-ap-headers-buttons').hide();
			      $('.e-ap-headers-buttons-stat').html("Ответ отправлен");
	        		$('.e-ap-headers-buttons-stat').addClass("green");
	        		$('.e-ap-headers-buttons-stat').removeClass("black");
	        		$('.e-ap-headers-buttons-stat').removeClass("red");
	        		$('[data-type="ap-answ-send-mob"]').prop( "disabled", false );
				}        
			})
		}
		
	}
});

//comments

$('[data-type="comm-text"]').focus(function() {
	$('[data-type="comm-plchldr"]').css('opacity','0');
	$('[data-type="mod-comment-send"]').prop( "disabled", false );
})
$('[data-type="comm-text"]').blur(function() {
	if($(this).val()=="") {
		$('[data-type="comm-plchldr"]').css('opacity','1');
	}	
})
$('[data-type="mod-comment-reset"]').click(function(){
	$('[data-type="comm-plchldr"]').css('opacity','1');
})
$('[data-type="mod-comment-send"]').click(function() {
	event.preventDefault();	
	$(this).prop( "disabled", true );
	var form = $(this).parents('.apComm');
	var text = $(this).parents('.apComm').find("[data-type='comm-text']");
	var check = 0;
	var comm_for = '<div class="e-ap-comment-for">Комментарий для: ';
	$(this).parents('.apComm').find('.e-ap-edit-input').each(function(i,item){
		if($(item).prop("checked") == true) {
			check ++;
			comm_for += $(item).closest("div").find('.e-ap-edit-label').html();
			comm_for += ', ';
		}		
	})
	comm_for = comm_for.substring(0, comm_for.length -2);
	comm_for += '</div>';
	if(text.val()=="") {
		alert("Введите комментарий!");
		$('[data-type="mod-comment-send"]').prop( "disabled", false );
	}
	else if(check == 0) {
		alert ("Выберите, кому отправить комментарий!");
		$('[data-type="mod-comment-send"]').prop( "disabled", false );
	} else {
		var name = $('.e-qs-user').attr('data-user');	
		if(form.attr('data-pos')=="report") {
			var id = form.attr('data-id');
			var wrap = $(this).parents('.e-qm-quest-item-comments').find('.e-qm-quest-item-comment');
		}
		if(form.attr('data-pos')=="answer") {
			var id = $('[data-type="ap-title"]').attr("data-id");
			var wrap = $('[data-type="comm-wrap"]');
		}
		var msg  = form.serialize();
		var text = text.val().replace(/\n/g, "<br>");
		$.ajax({
		type: "POST",
		url: "/ajax/question_service.php?type=comm&id="+id+"&name="+name,
		data: msg,
		error: function () {
      var statusCode = request.status;
      alert('Произошла ошибка, повторите попытку еще раз.');
  		},
     	success: function(data){
     		if (data!="error") {
     			var comm = '<div class="e-ap-comment e-ap-comment-' + $('[name="comm-stat"]').val() + '">';
     			comm += '<div class="e-ap-comment-name">'  + data + '</div>';
     			comm += '<div class="e-ap-comment-text">'+ text + '</div>';     			
     			comm += comm_for;
     			comm += '</div>';
     			wrap.append(comm);
     			form.trigger("reset");
     			$('[data-type="comm-plchldr"]').css('opacity','1');
     			$('[data-type="mod-comment-send"]').prop( "disabled", false );
     		}
     		else {
     			alert("Произошла ошибка, повторите попытку еще раз.");
     			$('[data-type="mod-comment-send"]').prop( "disabled", false );
     		}           
  		    
     }        
    })
	}
})

//moderation
$.mask.definitions['9'] = false;
$.mask.definitions['X'] = "[0-9]";
$('.tcal').mask("XX.XX.XXXX");


$('.e-qm-more').click(function(){
	var item = $(this).parents(".e-qm-quest-item-main").find(".e-qm-quest-item-answ");
	var more = $(this).parents(".e-qm-quest-item-main").find(".e-qm-more");
	var comm = $(this).parents(".e-qm-quest-item-main").find(".e-qm-dialog");
	if(item.is(":visible")) {
			item.slideUp();
			more.html('Подробнее <i class="icon-angle-down"></i>');
		}
		else {
			item.slideDown();
			more.html('Свернуть <i class="icon-angle-up"></i>');
			if(comm.hasClass("not-seen")) {
				comm.removeClass("not-seen");
				var qst_numb = $(this).parents(".e-qm-quest-item-main").find(".apComm").attr("data-numb");
				$.ajax({
					type: "POST",
					url: "/ajax/question_service.php?type=commseen&numb="+qst_numb,
			     	success: function(data){
			     }        
			    })
			}
		}
})

$('[data-type="more"]').click(function() {
	var main = $(this).parents('.e-qm-quest-item').find('.e-qm-quest-item-main').css('height','auto');
	console.log('here');
	//$(this).parents('.e-qm-quest-item-main').css('height','auto');
})

$('.e-qm-dialog').click(function(){
	$(this).removeClass("not-seen");
})

$('.e-qm-filter-stat-list-wrap li').click(function(){
   var id_sort = $(this).attr('data-val');
   $.cookie('qm_mod_stat', id_sort, {domain: domain, path: '/'});
   location.reload();
})

$('[data-type="show-date"]').click(function(){
	var dateFrom = $('[name="qm-from"]').val();
	var dateTo = $('[name="qm-to"]').val();
	if(dateFrom == "" && dateTo == "") {
		alert ("Введите дату!")
	}
	else {
		var qmDate = {};
		qmDate['from'] = dateFrom;
		qmDate['to'] = dateTo;
		qmDate['val'] = "0";
		var qm_mod_date = JSON.stringify(qmDate);
		$.cookie('qm_mod_date', qm_mod_date, {domain: domain, path: '/'});
   	location.reload();
	}
})

$('.e-qm-filter-period-segment li').click(function() {
	var qmDate = {};
	var val = $(this).attr('data-val');
	qmDate['val'] = val; 	

	if(val == "1" || val == "2" || val == "3") {
		qmDate['from'] = $(this).attr('data-from');	
	}
	else if (val == "4") {
		qmDate['from'] = "";
	}
	qmDate['to'] = "";
	var qm_mod_date = JSON.stringify(qmDate);
	$.cookie('qm_mod_date', qm_mod_date, {domain: domain, path: '/'});
	location.reload();
})

//feedback

//score
$('[data-type="feedb-but"]').click(function() {
	$(this).prop( "disabled", true );
	var val = $(this).attr("data-val");
	var id = $('[data-type="ap-title"]').attr("data-id");
	var score = '';
	if(val == "yes") {
		score = '<div class="e-ap-score-value green">Да</div>';		
	}
	else {
		score = '<div class="e-ap-score-value red">Нет</div>';
	}
	$.ajax({
		type: "POST",
		url: "/ajax/question_service.php?type=fdbk_score&id="+id+'&val='+val,
		error: function () {
      var statusCode = request.status;
      alert('Произошла ошибка, повторите попытку еще раз.');
  		},
     	success: function(data){
     		if (data!="error") {
     			$('*[data-type="feedb-but"]').hide();
     			$('.e-ap-score').append(score);
     		}
     		else {
     			alert("Произошла ошибка, повторите попытку еще раз.");
     		}
     }        
    })
})
//reset
$('[data-type="mod-feedb-reset"]').click(function() {
	$('.e-ap-answ-right textarea').val("");
	$('[name="ap-file"]').val("");
	$('.e-ap-answ-file>span').html("");
	$('.e-ap-answ-file').hide();
	$('[data-type="ap-answ-send"]').prop( "disabled", false );
})
//comment
$('[data-type="feedb-text"]').focus(function() {
	$('[data-type="feedb-plchldr"]').css('opacity','0');
	$('[data-type="mod-feedb-send"]').prop( "disabled", false );
})
$('[data-type="feedb-text"]').blur(function() {
	if($(this).val()=="") {
		$('[data-type="feedb-plchldr"]').css('opacity','1');
	}	
})
$('[data-type="mod-feedb-reset"]').click(function() {
	$('[data-type="feedb-plchldr"]').css('opacity','1');
	$('[data-type="mod-feedb-send"]').prop( "disabled", false );
})
$('[data-type="mod-feedb-send"]').click(function() {
	$(this).prop( "disabled", true );
	var form = $(this).parents('.apFeedb');
	var text = $(this).parents('.apFeedb').find("[data-type='feedb-text']");
	if(text.val() == "") {
		alert("Введите комментарий!");
	}
	else {
		text = text.val().replace(/\n/g, "<br>");		
		var id = $('[data-type="ap-title"]').attr("data-id");
		var wrap = $('[data-type="feedb-comm"]');
		var stat = $('[name="aqs-stat"]').val();
		//var stat = "spec";
		if(stat == "spec") {
			var name = $('.e-qs-user').attr('data-user');
			var tit = "Ответ";
		}
		else {
			var name = "";
			var tit = "вопрос";
		}
		if($('#e-aqs-input-page').val() == ""){
			$('#e-aqs-input-page').val('https://evroplast.ru/question_service/answer.php');
		};

		var filepath = $('#apFeedb').find('[name="ap-answ-file"]').val();
		if(filepath != "") {
			var filename = filepath.replace(/.*\\/, "");
		}
		if(filepath != '') {
			form = document.forms.apFeedb;      
			var formData = new FormData(form);
			var xhr = new XMLHttpRequest();		
			xhr.open("POST", "/ajax/question_service.php?type=fdbk_comm&id="+id+"&who="+name);          
			//xhr.open("POST", "/ajax/question_service.php?"+id);          
			xhr.onreadystatechange = function () {
			  if (xhr.readyState == 4) {
			    if (xhr.status == 200) {
			      data = xhr.responseText;	
	     			$('#apFeedb').trigger("reset");
	     			$('#apFeedb').find(".e-ap-answ-file span").html("");
	     			$('#apFeedb').find(".e-ap-answ-file").hide();
	     			// form.hide();
	     			wrap.append(data);
	     			$('[data-type="mod-feedb-send"]').prop( "disabled", false );
			    }
			  }
			};
			xhr.send(formData);
		}
		else {
			var msg  = form.serialize();		
			$.ajax({
				type: "POST",
				url: "/ajax/question_service.php?type=fdbk_comm&id="+id+"&who="+name,
				//url: "/ajax/question_service.php?"+id,
				data: msg,
				error: function () {
		      var statusCode = request.status;
		      alert('Произошла ошибка, повторите попытку еще раз.');
		      $('[data-type="mod-feedb-send"]').prop( "disabled", false );
		  		},
		     	success: function(data){
		     		if (data!="error") {   			
		     			form.trigger("reset");
		     			// form.hide();
		     			wrap.append(data);
		     			$('[data-type="mod-feedb-send"]').prop( "disabled", false );
		     		}
		     		else {
		     			alert("Произошла ошибка, повторите попытку еще раз.");
		     			$('[data-type="mod-feedb-send"]').prop( "disabled", false );
		     		} 
		     	}        
	    	})
		}
		}


		
})

//send dealer 


$('[name="dealer-mail"]').click(function() {
	$('[data-type="send-dealer"]').prop( "disabled", false );
	if($(this).val()=="Введите e-mail дилера" || $(this).val()=="Неверный формат e-mail") {
 		$('[name="dealer-mail"]').val('');
 		$(this).removeClass("e-aqs-form-err");
 	}
})

$('[data-type="send-dealer"]').click(function(){
		$(this).prop( "disabled", true );

		var mail = $('[name="dealer-mail"]').val();
		var err = 0;

		if(mail == "" || mail == "Введите e-mail дилера") {
			$('[name="dealer-mail"]').addClass("e-aqs-form-err");
			$('[name="dealer-mail"]').val("Введите e-mail дилера");
			err++;
		}
		else {
			if(validateEmailOrder(mail)){
			 	
			}
			else {
				$('[name="dealer-mail"]').addClass("e-aqs-form-err");
				$('[name="dealer-mail"]').val("Неверный формат e-mail");
				err++;
			}
		}

		if(err == 0) {
			var id = $('[data-type="ap-title"]').attr("data-id");
			var who = $('.e-qs-user').attr('data-user');
			var msg  = $('#dealerForm').serialize();		
			$.ajax({
			type: "POST",
			url: "/ajax/question_service.php?type=dealer&id="+id+"&who="+who,
			data: msg,
			error: function () {
	      var statusCode = request.status;
	      alert('Произошла ошибка, повторите попытку еще раз.');
	  		},
	     	success: function(data){
	     		if (data!="error") {
	     			$('[data-type="new-subj-form"]').trigger("reset");        			
        			$('.e-ap-redirect-btn').removeClass("e-ap-redirect-btn-act");
        			$('.e-ap-redirect-btn').hide();
        			var text = '<div class="e-ap-dealer-message-text">Вопрос переадресован дилеру</div>';
        			text += '<div class="e-ap-dealer-message-attr">'+data+'</div>';
        			$('.e-ap-dealer-message').html(text);
        			$('.e-ap-dealer-message').show();
        			$('[data-type="ap-putoff"]').hide(); 
        			$('.e-ap-answ').hide();
        			$('#dealerForm').hide();
        			$('.e-ap-headers-buttons-stat').html("Вопрос переадресован");
        			$('.e-ap-headers-buttons-stat').addClass("black");
        			$('[data-type="send-dealer"]').prop( "disabled", false );
	     		}
	     		else {
	     			alert("Произошла ошибка, повторите попытку еще раз.");
	     		} 
	     }        
	    })

		}

})

//edit empty answer 
$('.e-ap-edit-label').click(function() {
	if($('#e-ap-edit').prop('checked') == false) {
		$('[data-type="ap-answ-send"]').html("Записать в базу");
	}
	else {
		$('[data-type="ap-answ-send"]').html("Отправить ответ");
	}
})

//calculate height textarea
var height = $('.e-ap-spec-answ-text').height();
height = height > 50 ? height : 50;
$('.e-ap-spec-answ-edit').css('height',height);

//edit answer 
	$('.e-ap-edit-label-answ').click(function() {
		var wrap = $(this).closest('.edit-answer');
		var answerOld = wrap.find('.e-ap-spec-answ-text');
		var removeFile = "no";
		if(wrap.find('.e-ap-edit-input-answ').prop('checked') == false) {
			answerOld.hide();
			wrap.find('.e-ap-spec-answ-file').hide();
			var text = wrap.find('.e-ap-spec-answ-edit').html();
			text = text.replace(/&lt;br\/&gt;/g, "\n");
			wrap.find('.e-ap-spec-answ-edit').html(text);
			wrap.find('.e-ap-spec-answ-edit').show();
			wrap.find('[data-type="edit-answ-btn"]').show();
			wrap.find('[data-type="edit-answ-reset-btn"]').show();
			wrap.find('.e-ap-spec-answ-file-edit').show();
			wrap.find('.e-ap-answ-edit-panel').css('display','flex');

			//reset
			$('[data-type="edit-answ-reset-btn"]').click(function() {
				wrap = $(this).closest('.edit-answer');
				wrap.find('.e-ap-spec-answ-edit').html("");
				wrap.find('.e-ap-spec-answ-edit').val("");
				var isFile = wrap.find(".e-ap-spec-answ-file-edit");
				if(isFile.length > 0) {
					wrap.find('.e-ap-spec-answ-file-edit').html("");
					removeFile = "yes";
				}
			})

			//browse file
			$('[name="ap-answ-file"]').change(function() {
				wrap = $(this).closest('.edit-answer');
				var filename = $(this).val().replace(/.*\\/, "");
				var isFile = wrap.find(".e-ap-spec-answ-file-edit");
				if(isFile.length > 0) {
					var mess = "прикрепленный файл: <span>" + filename + '<span>';
					wrap.find('.e-ap-spec-answ-file-edit').html(mess);
				}
				else {
					var mess = '<div class="e-ap-spec-answ-file-edit">прикрепленный файл: <span>' + filename + '<span></div>';
					wrap.find(".e-ap-spec-answ-edit").after(mess);
					wrap.find('.e-ap-spec-answ-file-edit').show();
				}
			})

			//save edit
			$('[data-type="edit-answ-btn"]').click(function() {
				var wrap = $(this).closest('.edit-answer');
				event.preventDefault();	
				$(this).prop( "disabled", true );
				if(wrap.find('.e-ap-spec-answ-edit').val() == "" && wrap.find('[name="ap-answ-file"]').val() == "") {
					alert("Перед сохранением необходимо внести изменения!");
					$(this).prop( "disabled", false );
				}
				else {
					var id = $('[data-type="ap-title"]').attr("data-id");
					var text = wrap.find('.e-ap-spec-answ-edit').val();
					text = text.replace(/\n/g, "<br>");
					var filepath = wrap.find('[name="ap-answ-file"]').val();
					if(filepath != "") {
						var filename = filepath.replace(/.*\\/, "");
					}
					if(wrap.find('[name="ap-answ-file"]').val()!='') {
						var form = document.forms.editAnswer;
						var formData = new FormData(form);
						var xhr = new XMLHttpRequest();		
						xhr.open("POST", "/ajax/question_service.php?type=edit_answ&id="+id);          
						xhr.onreadystatechange = function () {
						  if (xhr.readyState == 4) {
						    if (xhr.status == 200) {
						      var data = xhr.responseText;
						      answerOld.html(text);
						      if(wrap.find("div").is(".e-ap-spec-answ-file")) {
						      	wrap.find('.e-ap-spec-answ-file a').attr("href",filepath);
						      	wrap.find('.e-ap-spec-answ-file a').html(filename);
						      	wrap.find('.e-ap-spec-answ-file').show();
						      }
						      else {
						      	var file = '<div class="e-ap-spec-answ-file">прикрепленный файл: <a href="'+filepath+'" download>'+filename+'</a></div>';
						      	answerOld.after(file);
						      }						      
						      answerOld.show();						      
						      wrap.find('.e-ap-spec-answ-edit').hide();
								wrap.find('[data-type="edit-answ-btn"]').hide();
								wrap.find('[data-type="edit-answ-reset-btn"]').hide();
								wrap.find('.e-ap-spec-answ-file-edit').hide();
								wrap.find('.e-ap-answ-edit-panel').hide();
								wrap.find('.e-ap-edit-input-answ').removeAttr("checked");
				        		wrap.find('[data-type="edit-answ-btn"]').prop( "disabled", false );

						    }
						  }
						};
						xhr.send(formData);
					}
					else {
						$.ajax({
							type: "POST",
							url: "/ajax/question_service.php?type=edit_answ&id="+id+"&remove="+removeFile,
							data: $("#editAnswer").serialize(),
						success: function(data){
								answerOld.html(text);
								answerOld.show();
								wrap.find('.e-ap-spec-answ-edit').hide();
								wrap.find('[data-type="edit-answ-btn"]').hide();
								wrap.find('[data-type="edit-answ-reset-btn"]').hide();
								wrap.find('.e-ap-spec-answ-file-edit').hide();
								wrap.find('.e-ap-answ-edit-panel').hide();
								wrap.find('.e-ap-edit-input-answ').removeAttr("checked");
				        		wrap.find('[data-type="edit-answ-btn"]').prop( "disabled", false );
							}        
						})
					}
				}
			})
		}
		else {
			answerOld.show();
			wrap.find('.e-ap-spec-answ-edit').hide();
			wrap.find('[data-type="edit-answ-btn"]').hide();
			wrap.find('[data-type="edit-answ-reset-btn"]').hide();
			wrap.find('.e-ap-spec-answ-file-edit').hide();
			wrap.find('.e-ap-answ-edit-panel').hide();
		}
	})

//edit add question

//edit empty add answer 
$('.e-ap-edit-label-add-answ').click(function() {
	var wrap = $(this).closest('.apFeedb');
	if($('#answAdd').prop('checked') == false) {
		wrap.find('[data-type="mod-feedb-send"]').html("Записать в базу");
	}
	else {
		wrap.find('[data-type="mod-feedb-send"]').html("Отправить");
	}
})

//calculate height textarea
$('[data-type="add-answ"]').each(function() {
	height = $(this).height();
	height = height > 50 ? height : 50;
	$(this).closest('.e-ap-comment').find('.e-ap-add-text-edit').css('height',height);
})

//edit add answer 
$('.e-ap-edit-label-add').click(function() {
	var wrap = $(this).closest('.e-ap-comment');
	var answerOld = wrap.find('.e-ap-comment-text');
	var removeFile = "no";
	if(wrap.find('.e-ap-edit-input-add').prop('checked') == false) {
		answerOld.hide();
		wrap.find('.e-ap-ask-file-current').hide();
		var text = wrap.find('.e-ap-add-text-edit').html();
		text = text.replace(/&lt;br\/&gt;/g, "\n");
		wrap.find('.e-ap-add-text-edit').html(text);
		wrap.find('.e-ap-add-text-edit').show();
		wrap.find('[data-type="edit-add-btn"]').show();
		wrap.find('[data-type="edit-add-reset-btn"]').show();
		wrap.find('.e-ap-ask-file-edit').show();
		wrap.find('.e-ap-add-edit-panel').css('display','flex');

		//reset
		$('[data-type="edit-add-reset-btn"]').click(function() {
			var wrap = $(this).closest('.e-ap-comment');
			wrap.find('.e-ap-add-text-edit').html("");
			wrap.find('.e-ap-add-text-edit').val("");
			var isFile = wrap.find(".e-ap-ask-file-edit");
			if(isFile.length > 0) {
				wrap.find('.e-ap-ask-file-edit').html("");
				removeFile = "yes";
			}
		})

		//browse file
		$('[name="ap-add-file"]').change(function() {
			var wrap = $(this).closest('.e-ap-comment');
			var filename = $(this).val().replace(/.*\\/, "");
			var isFile = wrap.find(".e-ap-ask-file-edit");
			if( isFile.length > 0 ) {
				var mess = "прикрепленный файл: <span>" + filename + '</span>';
				wrap.find('.e-ap-ask-file-edit').html(mess);
			}
			else {
				var mess = '<div class="e-ap-ask-file e-ap-ask-file-edit">прикрепленный файл: <span>' + filename + '</span></div>';
				wrap.find(".e-ap-add-text-edit").after(mess);
				wrap.find('.e-ap-ask-file-edit').show();
			}
		})

		//save edit
		$('[data-type="edit-add-btn"]').click(function() {
			event.preventDefault();
			var wrap = $(this).closest('.e-ap-comment');
			var form = wrap.find('.editAdd');
			$(this).prop( "disabled", true );
			if(wrap.find('.e-ap-add-text-edit').val() == "" && wrap.find('[name="ap-add-file"]').val() == "") {
				alert("Перед сохранением необходимо внести изменения!");
				$(this).prop( "disabled", false );
			}
			else {
				var id = $('[data-type="ap-title"]').attr("data-id");
				var text = wrap.find('.e-ap-add-text-edit').val();
				var id_add = wrap.find('[value="add-edit"]').attr("id");
				text = text.replace(/\n/g, "<br>");
				var filepath = wrap.find('[name="ap-add-file"]').val();
				if(filepath != "") {
					var filename = filepath.replace(/.*\\/, "");
				}
				if(wrap.find('[name="ap-add-file"]').val()!='') {
					var formId = "form"+id_add;
					form = document.getElementById(formId);      
					var formData = new FormData(form);
					console.log(formData);
					var xhr = new XMLHttpRequest();		
					xhr.open("POST", "/ajax/question_service.php?type=edit_add&id="+id+"&id_add="+id_add);          
					xhr.onreadystatechange = function () {
					  if (xhr.readyState == 4) {
					    if (xhr.status == 200) {
					      data = xhr.responseText;
					      answerOld.html(text);
					      isFile = wrap.find(".e-ap-ask-file-current");
					      if(isFile.length > 0) {
					      	wrap.find('.e-ap-ask-file-current a').attr("href",filepath);
					      	wrap.find('.e-ap-ask-file-current a').html(filename);
					      	wrap.find('.e-ap-ask-file-current').show();
					      }
					      else {
					      	file = '<div class="e-ap-ask-file e-ap-ask-file-current">прикрепленный файл: <a href="'+filepath+'" download>'+filename+'</a></div>';
					      	answerOld.after(file);
					      }						      
					      answerOld.show();						      
					      wrap.find('.e-ap-add-text-edit').hide();
							wrap.find('[data-type="edit-add-btn"]').hide();
							wrap.find('[data-type="edit-add-reset-btn"]').hide();
							wrap.find('.e-ap-ask-file-edit').hide();
							wrap.find('.e-ap-add-edit-panel').hide();
							wrap.find('.e-ap-edit-input-add').removeAttr("checked");
			        		wrap.find('[data-type="edit-add-btn"]').prop( "disabled", false );

					    }
					  }
					};
					xhr.send(formData);
				}
				else {
					$.ajax({
						type: "POST",
						url: "/ajax/question_service.php?type=edit_add&id="+id+"&remove="+removeFile+"&id_add="+id_add,
						data: form.serialize(),
					success: function(data){
							answerOld.html(text);
							answerOld.show();
							if(removeFile != "yes")	wrap.find('.e-ap-ask-file-current').show();
							wrap.find('.e-ap-add-text-edit').hide();
							wrap.find('[data-type="edit-add-btn"]').hide();
							wrap.find('[data-type="edit-add-reset-btn"]').hide();
							wrap.find('.e-ap-ask-file-edit').hide();
							wrap.find('.e-ap-add-edit-panel').hide();
							wrap.find('.e-ap-edit-input-add').removeAttr("checked");
			        		wrap.find('[data-type="edit-add-btn"]').prop( "disabled", false );
						}        
					})
				}
			}
		})
	}
	else {
		answerOld.show();
		wrap.find('.e-ap-ask-file-current').show();
		wrap.find('.e-ap-add-text-edit').hide();
		wrap.find('[data-type="edit-add-btn"]').hide();
		wrap.find('[data-type="edit-add-reset-btn"]').hide();
		wrap.find('.e-ap-ask-file-edit').hide();
		wrap.find('.e-ap-add-edit-panel').hide();
	}
})



//call-back
 $('[name="cb-name"]').click(function(){
 	$('[data-type="cb-but"]').prop( "disabled", false );
 	if($(this).val()=="Введите ваше имя") {
 		$('[name="cb-name"]').val('');
 		$(this).removeClass("e-aqs-form-err");
 	}
 })
$('[name="cb-tel"]').click(function() {
	$('[data-type="cb-but"]').prop( "disabled", false );
	$(this).mask('P000000000000');	
	$(this).removeClass("e-aqs-form-err");
 	if($(this).val()=="Введите номер телефона" || $(this).val()=="Неверный формат номера") {
 		$('[name="cb-tel"]').val('');
 		$(this).removeClass("e-aqs-form-err");
 	}
})

$('.cb_policy_label').click(function() {
	$('[data-type="cb-but"]').prop( "disabled", false );
	$(this).removeClass("e-aqs-form-err");
}) 

$('[data-type="cb-but"]').click(function(e) {
	e.preventDefault();
	var key = Math.floor(Math.random()*10000);
		$.cookie("rand", key, {domain: domain, path: '/'});
	var err = 0;
	if($('[name="cb-name"]').val()==""||$('[name="cb-name"]').val()=="Введите ваше имя") {
		$('[name="cb-name"]').addClass("e-aqs-form-err");
		$('[name="cb-name"]').val("Введите ваше имя");
		$(this).prop( "disabled", false );
		err++;
	}
	$(this).prop( "disabled", true );
	if($('[name="cb-tel"]').val().length==0 || $('[name="cb-tel"]').val()=="Введите номер телефона") {
		$('[name="cb-tel"]').addClass("e-aqs-form-err");
		$('[name="cb-tel"]').val("Введите номер телефона");
		$(this).prop( "disabled", false );
		err++;
	}
	else {
		if($('[name="cb-tel"]').val().length < 11 || $('[name="cb-tel"]').val().length > 13) {
			$('[name="cb-tel"]').addClass("e-aqs-form-err");
			$('[name="cb-tel"]').val("Неверный формат номера");
			$(this).prop( "disabled", false );
			err++;
		}
	}
	if ($('#cb_policy').prop('checked') == false) {
			$('.cb_policy_label').addClass("e-aqs-form-err");
			err++;
		}

	if( err == 0) {
		var new_val = hash_data($('[name="cb-tel"]').val(),key);
		if($('#cb-page').val() == ""){
			$('#cb-page').val('https://evroplast.ru/question_service/ask.php');
		};
		$.ajax({
			type: "POST",
			url: "/ajax/question_service.php?type=call_back&new_val="+new_val,
			data: $("#callBackForm").serialize(),
		success: function(data){
			console.log(data);
				$("#callBackForm").trigger('reset');
				$('[data-type="cb-but"]').hide();
				$('.call-back-rqst').show();
				$('.call-back-rqst').css('z-index','5');
		      $('.call-back-rqst').animate({opacity:1},1000);
		      setTimeout(function(){
		      	$('.call-back-rqst').animate({opacity:0},1000);
		      	$('.call-back-rqst').css('z-index','-1');
		      },5000);
		      setInterval(function(){
		      	$('.call-back-rqst').hide();
		      	$('[data-type="cb-but"]').show();
		      },5000);		      
        		$('[data-type="cb-but"]').prop( "disabled", false );
			}        
		})
	}
})
    //send to dealer
	$('[data-type="dealer-send"]').click(function(){
        $(this).prop( "disabled", true );
				var checked = 0;
        $('[name="ap-subj"]').each(function(){
            if($(this).prop("checked")) {
              checked++;
							currInp = $(this);
							console.log('ololo');
            }
        });
        if(checked == 0) {
            alert("Выберите новую тему!");
            $('[data-type="dealer-send"]').prop( "disabled", false );
        }
        else {
        	var type = currInp.attr("data-type");
          var name = $('.e-qs-user').attr('data-user');
        	console.log(currInp.val());
        	if(type == "send-subj") { //send to new subj
				type = "rdrct";
						var sendTo = "subj";
						var stat = "Вопрос перенаправлен";
			} else if(type == "send-spec") { //send to new spec
				type = "rdrct";
						var sendTo = "spec";
                stat = "Вопрос перенаправлен";
            } else if(type == "send-dlr") { //send to dealer
				type = "dealer";
						var sendTo = "";
                stat = "Вопрос перенаправлен дилеру";
            }
            $.ajax ({
                type: "POST",
                url: "/ajax/question_service.php?type="+type+"&send="+sendTo+"&name="+name,
                data: $('[data-type="new-subj-form"]').serialize(),
                error: function () {
                    var statusCode = request.status;
                    alert('Произошла ошибка, повторите попытку еще раз.');
                },
                success: function(data){
                    if (data!="error") {
                        $('[data-type="new-subj-form"]').trigger("reset");
                        $('.e-ap-redirect-btn').removeClass("e-ap-redirect-btn-act");
                        $('.e-ap-redirect-btn').hide();
                        $('.e-ap-headers-buttons').hide();
                        $('.e-ap-new-subj-message').html(data);
                        $('.e-ap-new-subj-message').show();
                        $('[data-type="ap-putoff"]').hide();
                        $('.e-ap-answ').hide();
                        $('.e-ap-headers-buttons-stat').html(stat);
                        $('.e-ap-headers-buttons-stat').addClass("black");
                        $('*.e-ap-new-subj-items-value').hide();
                        $('[data-type="dealer-send"]').prop( "disabled", false );
                    }
                    else {
                        alert("Произошла ошибка, повторите попытку еще раз.");
                    }

                }
            })

		}
    });
    $('[data-type="dealer-send-reset"]').click(function(){
			var act = $('.e-ap-new-subj').find(".active");
			var wrap = $(this).parents('.e-ap-redirect-btn');
        act.removeClass("active");
			wrap.removeClass("e-ap-redirect-btn-act");
        $('*.e-ap-new-subj-items-value').hide();
        $('[data-type="dealer-send"]').prop( "disabled", false );
        //actItem.slideUp();
    });

//report
$('[name="dealer-report"]').focus(function() {
    $('[data-type="answ-plchldr"]').css('opacity','0');
    $('[data-type="report-send"]').prop( "disabled", false );
})
$('[name="dealer-report"]').blur(function() {
   if($(this).val()=="") {
        $('[data-type="answ-plchldr"]').css('opacity','1');
   }
})
$('[data-type="report-reset"]').click(function(){
    $('[data-type="answ-plchldr"]').css('opacity','1');
    $('[data-type="report-send"]').prop( "disabled", false );
})

$('[data-type="report-send"]').click(function(){
    $(this).prop( "disabled", true );
	if($('[name="dealer-report"]').val() == "") {
		alert("Необходимо заполнить поле комментария!");
        $('[data-type="report-send"]').prop( "disabled", false );
	}
	else {
		var id = $('[data-type="ap-title"]').attr('data-id');
		let params = (new URL(document.location)).searchParams,
			d = !params.get("d") ? '' : params.get("d");
        $.ajax ({
            type: "POST",
            url: "/ajax/question_service.php?type=report&id="+id+"&d="+d,
            data: $('[data-type="ap-report"]').serialize(),
            error: function () {
                var statusCode = request.status;
                alert('Произошла ошибка, повторите попытку еще раз.');
            },
            success: function(data){
               if (data!="error") {
                    location.reload();
                }
                else {
                    alert("Произошла ошибка, повторите попытку еще раз.");
                }

            }
        })
	}
})

//send to region
$('[data-type="new-subj-reg"]').click(function(){
	$('[data-type="reg-list"]').find('a').each(function() {
		$(this).attr('data-type','region_change_link');
	})
	$('[data-type="geo-open"]').removeClass('icon-geo');
	$('[data-type="geo-open"]').addClass('icon-close');
	$('[data-type="geo-open"]').css('z-index','10');
	$('[data-type="geo-open"]').attr("data-type","qs-geo-close");
	$('[data-type="reg-list"]').slideDown();
	if(typeof regScroll === 'undefined') {
		var regScroll = $('[data-type="reg-list-scroll"]').jScrollPane({
			showArrows: false,
			maintainPosition: false
		}).data('jsp');
	} else {
		regScroll.reinitialise();
	}
	$('body').addClass('disabled');
})

$('header').on('click','[data-type="qs-geo-close"]',function() {
	$(this).css('z-index','0');
	$(this).attr("data-type","geo-open");
	$(this).addClass('icon-geo');
	$(this).removeClass('icon-close');
	$('[data-type="reg-list"]').slideUp();
	$('[data-type="reg-list"]').find('a').each(function() {
		$(this).attr('data-type','choose-reg');
	})
	$('[data-type="curr-reg"]').html();
	$('body').removeClass('disabled');
	$('*[data-type="reg-count-wrap"]').removeClass('active');
	$('*[data-type="reg-city"]').hide();
})

$('header').on('click','[data-type="region_change_link"]',function(){
	let val = $(this).attr('data-value'),
		qstId = $('[data-type="ap-title"]').attr('data-id'),
		type = "rdrct",
		sendTo = "reg",
		stat = "Вопрос перенаправлен",
    	name = $('.e-qs-user').attr('data-user');
    $.ajax ({
        type: "POST",
        url: "/ajax/question_service.php?type="+type+"&send="+sendTo+"&name="+name,
        data: {'reg': val,'ap-subj-id':qstId},
        error: function () {
            var statusCode = request.status;
            alert('Произошла ошибка, повторите попытку еще раз.');
        },
        success: function(data){
            if (data!="error") {
                $('[data-type="new-subj-form"]').trigger("reset");
                $('.e-ap-redirect-btn').removeClass("e-ap-redirect-btn-act");
                $('.e-ap-redirect-btn').hide();
                $('.e-ap-headers-buttons').hide();
                $('.e-ap-new-subj-message').html(data);
                $('.e-ap-new-subj-message').show();
                $('[data-type="ap-putoff"]').hide();
                $('.e-ap-answ').hide();
                $('.e-ap-headers-buttons-stat').html(stat);
                $('.e-ap-headers-buttons-stat').addClass("black");
                $('*.e-ap-new-subj-items-value').hide();
                $('[data-type="dealer-send"]').prop( "disabled", false );
                let closeBtn = $('header').find('[data-type="qs-geo-close"]');
				closeBtn.css('z-index','0');
				closeBtn.attr("data-type","geo-open");
				closeBtn.addClass('icon-geo');
				closeBtn.removeClass('icon-close');
				$('[data-type="reg-list"]').slideUp();
				$('[data-type="reg-list"]').find('a').each(function() {
					$(this).attr('data-type','choose-reg');
				})
				$('[data-type="curr-reg"]').html();
				$('body').removeClass('disabled');
				$('*[data-type="reg-count-wrap"]').removeClass('active');
				$('*[data-type="reg-city"]').hide();
            }
            else {
                alert("Произошла ошибка, повторите попытку еще раз.");
            }
        }
    })
})


});//end $




var idNum = 0, data = 'elastic'; 
$('body').on('keyup', 'textarea[data^="'+data+'"]', function(){ 
if($(this).attr('data')==''+data+''){$(this).attr({style:'overflow:hidden;'+$(this).attr('style')+'',data:''+$(this).attr('data')+''+idNum+''});idNum++;} 
tData = $(this).attr('data'); 
if($('div[data="'+tData.replace(''+data+'','clone')+'"]').size()==0){ 
attr = 'style="display:none;padding:'+$(this).css('padding')+';width:'+$(this).css('width')+';min-height:'+$(this).css('height')+';font-size:'+$(this).css('font-size')+';line-height:'+$(this).css('line-height')+';font-family:'+$(this).css('font-family')+';white-space:'+$(this).css('white-space')+';word-wrap:'+$(this).css('word-wrap')+';letter-spacing:0.2px;" data="'+tData.replace(''+data+'','clone')+'"'; 
clone = '<div '+attr+'>'+$(this).val()+'</div>'; 
$('body').prepend(clone); 
idNum++; 
}else{ 
$('div[data="'+tData.replace(''+data+'','clone')+'"]').html($(this).val()); 
$(this).css('height',''+$('div[data="'+tData.replace(''+data+'','clone')+'"]').css('height')+''); 
} 
});

//put off answer - mob
function ap_putoff_mob() {
	var btn = $('[data-type="ap-putoff-mob"]');
		btn.prop( "disabled", true );

	var qst_id = $('[data-type="ap-title"]').attr("data-id");
	//console.log(qst_id);
	$.ajax({
		type: "POST",
		url: "/ajax/question_service.php?type=putoff&id="+qst_id,
		error: function () {
      var statusCode = request.status;
      alert('Произошла ошибка, повторите попытку еще раз.');
  		},
     	success: function(data){
     		if (data!="error") {
     			btn.remove();
					var new_btn = '<div class="e-ap-putoff-btn red" data-type="ap-renew-mob" onclick="ap_renew_mob()">Возобновить</div>';
     			$('.e-ap-redirect-btn').before(new_btn);
        		$('.e-ap-headers-buttons-stat').html("Вопрос отложен");
        		$('.e-ap-headers-buttons-stat').addClass("black");
     		}
     		else {
     			alert("Произошла ошибка, повторите попытку еще раз.");
     		}           
  		    
     }        
    })
}


//renew answer - mob
function ap_renew_mob() {
	var btn = $('[data-type="ap-renew-mob"]');
	var qst_id = $('[data-type="ap-title"]').attr("data-id");

	$.ajax({
		type: "POST",
		url: "/ajax/question_service.php?type=puton&id="+qst_id,
		error: function () {
      var statusCode = request.status;
      alert('Произошла ошибка, повторите попытку еще раз.');
  		},
     	success: function(data){
     		if (data!="error") { 
     			btn.remove();
					var new_btn = '<div class="e-ap-putoff-btn" data-type="ap-putoff-mob" onclick="ap_putoff_mob()">Отложить</div>';
     			$('.e-ap-redirect-btn').before(new_btn);
        		$('.e-ap-headers-buttons-stat').html("Вопрос прочитан");
        		$('.e-ap-headers-buttons-stat').addClass("black");
     		}
     		else {
     			alert("Произошла ошибка, повторите попытку еще раз.");
     		}     
     }        
    })
}

function hash_data(data,key) {
	data = data.replace (/[\n\r]/g, '');
	var arr = data.split('');
	var newStr = arr[2] + key + arr[5] + key + arr[8];
	var hash = md5(newStr);
	return(hash);
}



//jTruncate js
(function($){
$.fn.jTruncate = function(options) {
   
	var defaults = {
		length: 300,
		minTrail: 20,
		moreText: "more",
		lessText: "less",
		ellipsisText: "...",
		moreAni: "",
		lessAni: ""
	};
	
	var options = $.extend(defaults, options);
   
	return this.each(function() {
		var obj = $(this);
		var body = obj.html();
		
		if(body.length > options.length + options.minTrail) {
			var splitLocation = body.indexOf(' ', options.length);
			if(splitLocation != -1) {
				// truncate tip
				var splitLocation = body.indexOf(' ', options.length);
				var str1 = body.substring(0, splitLocation);
				var str2 = body.substring(splitLocation, body.length - 1);
				obj.html(str1 + '<span class="truncate_ellipsis">' + options.ellipsisText + 
					'</span>' + '<span class="truncate_more">' + str2 + '</span>');
				obj.find('.truncate_more').css("display", "none");
				
				// insert more link
				obj.append(
					'<div class="clearboth">' +
						'<a href="#" class="truncate_more_link truncate_more_i">' + options.moreText + '</a>' +
					'</div>'
				);

				// set onclick event for more/less link
				var moreLink = $('.truncate_more_link', obj);
				var moreContent = $('.truncate_more', obj);
				var ellipsis = $('.truncate_ellipsis', obj);
				moreLink.click(function() {
					if(moreLink.text() == options.moreText) {
						moreContent.show(options.moreAni);
						moreLink.text(options.lessText);
						moreLink.removeClass("truncate_more_i");
						moreLink.addClass("truncate_less_i");
						ellipsis.css("display", "none");
					} else {
						moreContent.hide(options.lessAni);
						moreLink.text(options.moreText);
						moreLink.addClass("truncate_more_i");
						moreLink.removeClass("truncate_less_i");
						ellipsis.css("display", "inline");
					}
					return false;
			  	});
			}
		} // end if
		else {
			obj.append(
					'<div class="clearboth">' +
						'<a href="#" class="truncate_more_link truncate_more_i">' + options.moreText + '</a>' +
					'</div>'
				);
			var moreLink = $('.truncate_more_link', obj);
			moreLink.click(function() {
				if(moreLink.hasClass("truncate_more_i")) {
					moreLink.text(options.lessText);
					moreLink.removeClass("truncate_more_i");
					moreLink.addClass("truncate_less_i");
				} else {
					moreLink.text(options.moreText);
					moreLink.addClass("truncate_more_i");
					moreLink.removeClass("truncate_less_i");
				}
				return false;
		  	});
		}
	});
};
})(jQuery);

/**
 * FAQ
 */
$(document).ready(function() {
	$('.faq-answer-wrap').on('click', '.faq-answer-show', function() {
		if($(this).hasClass('active')) {
			$(this).closest('.faq-answer-item').find('.faq-answer').slideUp();
			$(this).html('<span>Показать ответ</span> <i class="icon-angle-down"></i>');
			$(this).removeClass('active');
		} else {
			$(this).closest('.faq-answer-item').find('.faq-answer').slideDown();
			$(this).html('<span>Свернуть ответ</span> <i class="icon-angle-up"></i>');
			$(this).addClass('active');
		}
	})
	$('.faq-tag').on('click', function() {
		$(this).closest('.faq-tags-wrap').find('.faq-tag').each(function() {
			$(this).removeClass('active');
		})
		$(this).addClass('active');
		$.get('/ajax/get_faq.php',{tag:$(this).attr('data-val')}, function(data){
			let elems = $.parseJSON(data);
			$('.faq-answer-wrap').html(elems);
		});
	})

	if($('#faq-aqsMainForm').length > 0) {
		$(".right-part-form").sticky({
			topSpacing:146,
			bottomSpacing: 366
		});
		$(".right-part-form").tinyscrollbar();
	}

	$('[data-type="search-faq"]').on('click', function() {
		searchFormHandler();
	})
	$(".faq-search-wrap input").keydown(function(event){
		if(event.keyCode == 13){
			event.preventDefault();
			searchFormHandler();
			return;
		}
	});
	function searchFormHandler() {
		let btn = $('[data-type="search-faq"]'),
			val = $('[name="faq"]').val();
		if(val.length > 0) {
			btn.attr('disabled','disabled');
			$('.faq-answer-wrap').html("<div class='faq-wait'><img src='/images/AjaxLoader.gif' alt='Wait...'></div>");
			$.get('/ajax/get_faq.php',{faq:val}, function(data){
				let elems = $.parseJSON(data);
				$('.faq-answer-wrap').html(elems);
				btn.closest('form').trigger("reset");
				btn.removeAttr('disabled','disabled');
				return;
			});
		} else {
			return;
		}
	}
})

$(document).ready(function() {
	$('[data-type="need-comm"]').on('click',function() {
		let wrap = $(this).closest('.e-ap-need-comm-wrap'),
				btn = $(this),
				mess = '<p class="need-comm-send"><i class="new-icomoon icon-check-1"></i>Запрошен комментарий</p>',
				id = btn.closest('[data-type="question-wrap"]').find('[data-type="ap-title"]').attr('data-id');
		wrap.html("ожидайте...");
		$.get( "/ajax/question_service.php", {id:id,type:'need_comm'}, function(data) {
			mess += '<div class="need-comm-info"><span>Информация по дилеру:</span>'+data+'</div>';
			wrap.html(mess);
		})
	})
})

