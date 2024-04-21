function validateEmail(email) {
  var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(email);
}
function applySticky() {
  if($('[data-type="personal-tabs"]').length > 0) {
    if(window.innerWidth > 1000) {
      $('[data-type="personal-tabs"]').sticky({
        topSpacing: $('header').outerHeight() + 20,
        bottomSpacing: $('footer').outerHeight() + 50
      });
    } else {
      $('[data-type="personal-tabs"]').unstick();
    }
  }
  return false;
}
$(window).on('resize', function() {
  if($('[data-val="profile"]').hasClass('active')) {
    if($(window).width() > 1000) {
      $('[data-type="profile-nav-mob"]').hide();
      $('[data-type="profile-nav"]').show();
    } else {
      $('[data-type="profile-nav-mob"]').css('display','flex');
      $('[data-type="profile-nav"]').hide();
    }
  }
  applySticky();
})
$(document).ready(function() {
  applySticky();
  //tabs
  let locHash = location.hash;
  if(locHash != '') {
    locHash = locHash.substr(1);
    $('[data-type="main-tab"]').each(function() {
      $(this).removeClass('active');
    })
    $('[data-type="main-tab-cont"]').each(function() {
      $(this).removeClass('active');
    })
    $('[data-val="'+locHash+'"]').addClass('active');
    $('#'+locHash).addClass('active');
    if(locHash != 'profile') {
      $('[data-type="profile-nav"]').hide();
      $('[data-type="profile-nav-mob"]').hide();
    }
  }
  //i agree
  $('[data-type="user-pers-data"]').on('click',function() {
    $(this).removeClass('error');
    $(this).toggleClass('active');
    $('[data-type="ok-btn"]').removeAttr('disabled');
  })

  //check pass length
  $('[name="password"]').blur(function() {
    if($(this).val().length < 6) {
      $(this).addClass('error');
    }
  })

  //confirm pass check
  $('[name="confirm_password"]').blur(function() {
    var wrap = $(this).closest('form');
    var val_1 = wrap.find('[name="password"]').val();
    var val_2 = $(this).val();
    if(val_1 != val_2) $(this).addClass('error');
  })

  //click on input
  $('.user-form').on('click','input',function() {
    $('[data-type="ok-btn"]').removeAttr('disabled');
    $(this).removeClass('error');
    $('[data-type="server-error"]').hide();
    $('[data-type="server-error"]').html('');
    return false;
  })

  $('.user-form').on('change','[data-type="required"]',function() {
    $('[data-type="ok-btn"]').removeAttr('disabled');
    $(this).removeClass('error');
    return false;
  })

  //click registration btn
  $('[data-act="reg"]').on('click',function() {

    $(this).attr('disabled','disabled');
    var btn = $(this);
    var wrap = $(this).closest('form');
    var err = 0;

    wrap.find('[data-type="required"]').each(function() {
      if($(this).val() == '') {
        $(this).addClass('error');
        err++;
      }
    })

    if(!validateEmailOrder(wrap.find('[name="email"]').val())) {
      wrap.find('[name="email"]').addClass('error');
      err++;
    }

    if(wrap.find('[name="password"]').val() !== wrap.find('[name="confirm_password"]').val()) {
      wrap.find('[name="confirm_password"]').addClass('error');
      err++;
    }

    if(wrap.find('[name="password"]').val().length < 6) {
      wrap.find('[name="password"]').addClass('error');
      err++;
    }

    if(!wrap.find('[data-type="user-pers-data"]').hasClass('active')) {
      wrap.find('[data-type="user-pers-data"]').addClass('error');
      err++;
    }

    if(err == 0) {

      $.ajax({
        type: "POST",
        url: "/personal/ajax.php",
        data: wrap.serialize(),
        success: function(resp){
          var resp = JSON.parse(resp);
          if(resp.type == 'ERROR') {
            $('[data-type="server-error"]').html(resp.mess);
            $('[data-type="server-error"]').fadeIn();
          }
          if(resp.type == 'OK') {
            var mess = '<div class="reg-succ-mess-wrap">\n' +
        '                <div class="user-form-title">Вы успешно <br>зарегистрированы</div>\n' +
        '                <div class="reg-succ-mess">\n' +
        '                    Для подтверждения регистрации перейдите по ссылке,\n' +
        '                    указанной в письме, которое придёт на указанный\n' +
        '                    при регистрации email.\n' +
        '                </div>\n' +
        '                <a href="/">на главную страницу</a>\n' +
        '            </div>';
            $('.reg-cont').html(mess);
          }
        }
      });
    }

  })

  //enter registration

  $('[data-act="reg-enter"]').on('click',function() {
    $(this).attr('disabled','disabled');
    var btn = $(this);
    var wrap = $(this).closest('form');
    var err = 0;

    wrap.find('[data-type="required"]').each(function() {
      if($(this).val() == '') {
        $(this).addClass('error');
        err++;
      }
    })
      var rememb = '';

    if(wrap.find('[data-type="user-pers-data"]').hasClass('active')) {
      rememb = '&rememb=yes'
    }

    if(err == 0) {
      var addr = '&addr='+window.location.href;
      $.ajax({
        type: "POST",
        url: "/personal/ajax.php",
        data: wrap.serialize() + rememb+addr,
        success: function(resp){
          var resp = JSON.parse(resp);
          if(resp.type == 'ERROR') {
            $('[data-type="server-error"]').html(resp.mess);
            $('[data-type="server-error"]').fadeIn();
          }
          if(resp.type == 'OK') {
            if(btn.attr('data-val')=='reg'){
                document.location.href = "/personal/";
            } else {
                window.location.reload();
              }
            //document.location.href = "/personal/";
            //window.location.reload();
          }
        }
      });
    }
  });

  //forget pass

  $('[data-act="forget"]').on('click',function() {
    $(this).attr('disabled','disabled');
    var btn = $(this);
    var wrap = $(this).closest('form');
    var err = 0;

    wrap.find('[data-type="required"]').each(function() {
      if($(this).val() == '') {
        $(this).addClass('error');
        err++;
      }
    })

    if(!validateEmailOrder(wrap.find('[name="email"]').val())) {
      wrap.find('[name="email"]').addClass('error');
      err++;
    }

    if(err == 0) {

      $.ajax({
        type: "POST",
        url: "/personal/ajax.php",
        data: wrap.serialize(),
        success: function(resp){
          var resp = JSON.parse(resp);
          if(resp.type == 'ERROR') {
            $('[data-type="server-error"]').html(resp.mess);
            $('[data-type="server-error"]').fadeIn();
          }
          if(resp.type == 'OK') {
            var mess = resp.mess;
            $('[data-type="succ-mess"]').find('.succ-content').html(mess);
            wrap.hide();
            $('[data-type="succ-mess"]').fadeIn();
          }
        }
      });
    }

  });

  //change forget pass


  $('[data-act="forget-change"]').on('click',function() {
    $(this).attr('disabled','disabled');
    var btn = $(this);
    var wrap = $(this).closest('form');
    var err = 0;

    wrap.find('[data-type="required"]').each(function() {
      if($(this).val() == '') {
        $(this).addClass('error');
        err++;
      }
    })

    if(wrap.find('[name="password"]').val() !== wrap.find('[name="confirm_password"]').val()) {
      wrap.find('[name="confirm_password"]').addClass('error');
      err++;
    }

    if(wrap.find('[name="password"]').val().length < 6) {
      wrap.find('[name="password"]').addClass('error');
      err++;
    }

    if(err == 0) {

      $.ajax({
        type: "POST",
        url: "/personal/ajax.php",
        data: wrap.serialize(),
        success: function(resp){
          var resp = JSON.parse(resp);
          if(resp.type == 'ERROR') {
            $('[data-type="server-error"]').html(resp.mess);
            $('[data-type="server-error"]').fadeIn();
          }
          if(resp.type == 'OK') {
            var mess = resp.mess;
            $('[data-type="succ-mess"]').find('.succ-content').html(mess);
            wrap.hide();
            $('[data-type="succ-mess"]').fadeIn();
          }
        }
      });
    }

  });

  //authorization

  $('[data-act="auth"]').on('click',function() {
    $(this).attr('disabled','disabled');
    var btn = $(this);
    var wrap = $(this).closest('form');
    var err = 0;

    wrap.find('[data-type="required"]').each(function() {
      if($(this).val() == '') {
        $(this).addClass('error');
        err++;
      }
    })

    var rememb = '';

    if(wrap.find('[data-type="user-pers-data"]').hasClass('active')) {
      rememb = '&rememb=yes'
    }

    if(err == 0) {

      $.ajax({
        type: "POST",
        url: "/personal/ajax.php",
        data: wrap.serialize() + rememb,
        success: function(resp){
          var resp = JSON.parse(resp);
          if(resp.type == 'ERROR') {
            $('[data-type="server-error"]').html(resp.mess);
            $('[data-type="server-error"]').fadeIn();
          }
          if(resp.type == 'OK') {
            var mess = resp.mess;
            $('[data-type="succ-mess"]').find('.suc-content').html(mess);
            wrap.hide();
            $('[data-type="succ-mess"]').fadeIn();
          }
        }
      });
    }

  });
  $('[data-type="edit"]').on('click', function() {
    $('.profile-change-pass').hide();
    $('.profile-data-wrap').show();
    $('.profile-data-form').find('input').each(function() {
      if(!$(this).hasClass('unchangeable')) {
        $(this).removeAttr('readonly');
      }
        $(this).attr('oldval',$(this).val());
    })
    $('.profile-data-form').addClass('edited');
    $(this).closest('[data-type="profile-nav"]').find('.active').removeClass('active');
    $(this).closest('[data-type="profile-nav-mob"]').find('.active').removeClass('active');
    $(this).addClass('active');
  })

  $('[data-type="reset"]').on('click',function() {
    $('.profile-data-form').find('input').each(function() {
      if(!$(this).hasClass('unchangeable')) {
        $(this).attr('readonly',true);
        $(this).val($(this).attr('oldval'));
        $('.personal-data-form-error').hide();
      }
    })
    $('.profile-data-form').removeClass('edited');
  })

  //click on input
  $('.profile-data-form').on('click','[data-type="required"]',function() {
    $('[data-type="save"]').removeAttr('disabled');
    $(this).removeClass('error');
    $('.personal-data-form-error').hide();
    return false;
  })

  $('.profile-data-form').on('change','[data-type="required"]',function() {
    $('[data-type="save"]').removeAttr('disabled');
    $('.personal-data-form-error').hide();
    $(this).removeClass('error');
    return false;
  })

  $('[data-type="save"]').on('click',function() {
    $(this).attr('disabled','disabled');
    var btn = $(this);
    var wrap = $(this).closest('form');
    var err = 0;

    var change = 0;
    wrap.find('input').each(function() {
      if($(this).val() != $(this).attr('oldval')) {
        change++;
      }
    })
    if(change == 0) {
      btn.removeAttr('disabled');
      err++;
      $('.personal-data-form-error').html('Данные не изменялись!');
      $('.personal-data-form-error').fadeIn();
    }

    wrap.find('[data-type="required"]').each(function() {
      if($(this).val() == '') {
        $(this).addClass('error');
        err++;
      }
    })

    if(err == 0) {

      $.ajax({
        type: "POST",
        url: "/personal/ajax.php",
        data: wrap.serialize(),
        success: function(resp){
          var resp = JSON.parse(resp);
          if(resp.type == 'ERROR') {
            $('.personal-data-form-error').html(resp.mess);
            $('.personal-data-form-error').fadeIn();
          }
          if(resp.type == 'OK') {
            $('.profile-data-form').find('input').each(function() {
              if(!$(this).hasClass('unchangeable')) {
                $(this).attr('readonly',true);
                $(this).attr('oldval',$(this).val());
                $('.personal-data-form-error').hide();
              }
            })
            $('.profile-data-form').removeClass('edited');
            $('.personal-data-form-success').fadeIn();
            setTimeout(function() {
              $('.personal-data-form-success').fadeOut();
            },4000);
          };
        }
      });

    }
  });

  //change password

  $('[data-type="pass"]').on('click',function() {
  $('.profile-data-wrap').hide();
  $('.profile-change-pass').show();
    $(this).closest('[data-type="profile-nav"]').find('.active').removeClass('active');
    $(this).closest('[data-type="profile-nav-mob"]').find('.active').removeClass('active');
    $(this).addClass('active');
  });

  //check pass length
  $('[name="old_password"]').blur(function() {
    if($(this).val().length < 6) {
      $(this).addClass('error');
    }
  })
  $('[data-act="change-pass"]').on('click',function() {
    $(this).attr('disabled', 'disabled');
    var btn = $(this);
    var wrap = $(this).closest('form');
    var err = 0;

    wrap.find('[data-type="required"]').each(function() {
      if($(this).val() == '' || $(this).val().length < 6) {
        $(this).addClass('error');
        err++;
      }
    })

    if(err == 0) {

      $.ajax({
        type: "POST",
        url: "/personal/ajax.php",
        data: wrap.serialize(),
        success: function(resp){
          var resp = JSON.parse(resp);
          if(resp.type == 'ERROR') {
            $('[data-type="change-pass"]').find('[data-type="server-error"]').html(resp.mess);
            $('[data-type="change-pass"]').find('[data-type="server-error"]').fadeIn();
          }
          if(resp.type == 'OK') {
            $('[data-type="change-pass"]').trigger("reset");
            $('[data-type="change-pass"]').find('.personal-data-form-success').fadeIn();
            setTimeout(function() {
              $('[data-type="change-pass"]').find('.personal-data-form-success').fadeOut();
            },4000);
          };
        }
      });
    }
  });

  //очистить избранное
  $('[data-type="clear-wishlist"]').on('click',function() {
    var user = $('[data-type="personal-data"]').attr('data-id');
    if(user && $('[data-type="clear-wishlist"]').attr('data-user') == 'user') {
      $.ajax({
        type: "POST",
        url: "/personal/ajax.php",
        data: 'type=favorite_clear&user_id='+user,
        success: function(resp){
          var resp = JSON.parse(resp);
          if(resp.type == 'OK') {
            $('.fav-wrap').hide();
            $('.wishlist').hide();
            $('.pagination').hide();
            $('.fav-content').html('<div class="wishlist-desc"><div class="no-fav">нет избранных товаров</div><div class="no-fav-desc">подберите для&nbsp;себя подходящий товар у&nbsp;нас&nbsp;в&nbsp;каталоге</div><a href="/karnizy/">в каталог</a></div>');
          }
        }
      });
    } else {
      var domain = location.hostname;
      $.cookie('favorite', JSON.stringify([]), {
        domain: domain,
        path: '/'
      });
      $('.fav-wrap').hide();
      $('.wishlist').hide();
      $('.pagination').hide();
      $('.fav-content').html('<div class="wishlist-desc"><div class="no-fav">нет избранных товаров</div><div class="no-fav-desc">подберите для&nbsp;себя подходящий товар у&nbsp;нас&nbsp;в&nbsp;каталоге</div><a href="/karnizy/">в каталог</a></div>');
    }

  })


  $('[data-type="copy-link"]').on('click',function() {
    let $temp = $("<input>");
    $("body").append($temp);
    $temp.val($('[data-type="link"]').attr('href')).select();
    document.execCommand("copy");
    console.log("Copied the text: " + $temp.val());
    $temp.remove();
    $(this).addClass('copied');
    setTimeout(function() {
      $('[data-type="copy-link"]').removeClass('copied');
    },4000)
  })

  $('[data-type="send-link"]').on('click',function() {
    let wrap = $(this).closest('.pacc-main-info-copy-link-wrap'),
        form = wrap.find('form');
    form.slideToggle();
  })
  $(document).mousedown(function (e) {
    var container = $('[data-type="send-link-form"]');
    if (container.css('display')!='none' && container.has(e.target).length === 0 && !$(event.target).is('[data-type="send-link-form"]') && !$(event.target).is('[data-type="send-link"]') && $('[data-type="send-link"]').has(e.target).length === 0){
      $('[data-type="send-link-form"]').fadeOut();
    }
  });
  $('[data-type="send-link-btn"]').on('click',function() {
    let btn = $(this),
        mail = $('[name="send-email"]').val(),
        id = $('[data-type="order-id"]').attr('data-id'),
        wrap = $(this).closest('.pacc-main-info-copy-link-wrap');
    if(mail == '' || validateEmail(mail) !== true) {
      $('[name="send-email"]').addClass('error');
      return false;
    } else {
      btn.attr('disabled','disabled');
      $.get('/personal/ajax.php',{type:'send_pay_link',mail:mail,id:id},function(data) {
          btn.closest('[data-type="send-link-form"]').hide();
          btn.removeAttr('disabled');
          wrap.find('.pacc-main-info-copy-link').addClass('copied');
          setTimeout(function() {
            wrap.find('.pacc-main-info-copy-link').removeClass('copied');
          },4000);
      })
    }

  })
  $('[name="send-email"]').on('click',function(){
    $(this).removeClass('error');
  })
  $('[name="send-email"]').on('change',function(){
    $(this).removeClass('error');
  })


})//document.ready

function cataloguePagination() {
  var newPage = $('[data-type="collection-page"]').pagination('getCurrentPage');
  var user_id = '';
  if($('[data-type="personal-data"]').length != 0) {
    user_id = $('[data-type="personal-data"]').attr('data-id');
  }
  var req = '/personal/ajax.php?type=next_wish&page='+newPage+'&user_id='+user_id;
  $.get(req, function(data){
    var elems = $.parseJSON(data);
    $('[data-type="wishlist-cont"]').html(elems);
    $('html,body').animate({scrollTop: 0}, 500);
  });
}
/**
 * показать все
 */
$('[data-type="show-all"]').on('click', function() {
  $('.show-wait').show();
  var newPage = 1;
  var user_id = '';
  if($('[data-type="personal-data"]').length != 0) {
    user_id = $('[data-type="personal-data"]').attr('data-id');
  }
  var req = '/personal/ajax.php?type=next_wish&page=1&user_id='+user_id+'&all=true';
  $.get(req, function(data){
    $('html,body').animate({scrollTop: 0}, 500);
    var elems = $.parseJSON(data);
    $('[data-type="wishlist-cont"]').html(elems);
    $('.e-new-catalogue-pagination').hide();
  });
})
/**
 * products on page
 *
 * file.php - заменить на свой
 */
$('*[data-type="on-page"]').on('click',function(){
  var onpage = $(this).attr('data-val');
  var user_id = '';
  if($('[data-type="personal-data"]').length != 0) {
    user_id = $('[data-type="personal-data"]').attr('data-id');
  }
  $('*[data-type="on-page"]').each(function(i,item){
    $(item).removeClass('active');
  })
  $(this).addClass('active');
  $.cookie('data_onpage', onpage, {domain: domain, path: '/'});
  var url = window.location.href.split('?');
  var filters = '';
  if(url.length>1) {
    filters = url[1];
  }
  var req = '/personal/ajax.php?type=next_wish&page=1&user_id='+user_id;
  $.get(req, function(data){
    var elems = $.parseJSON(data);
    $('[data-type="wishlist-cont"]').html(elems);
    $('html,body').animate({scrollTop: 0}, 500);
  });
  $('[data-type="collection-page"]').attr('data-onpage',onpage);
  pagination();
})

//restore saved order
$('[data-type="saved-restore"]').on('click',function() {
  var list = [];
  $('[data-type="saved-prod-list"]').find('[data-type="saved-prod"]').each(function() {
    list.push({
      'id': $(this).attr('data-id'),
      'qty': parseInt($(this).attr('data-qty'))
    });
  })
  $.cookie('basket', JSON.stringify(list), {
    expires: basketExpires,
    domain: domain,
    path: '/'
  });
  window.location.href = "/cart";
})
//remove saved order
$('[data-type="saved-remove"]').on('click',function() {
  var popup = '<div class="popup-mess-text">Вы уверены, что хотите удалить сохраненный&nbsp;заказ?</div>';
  popup += '<div class="popup-mess-btns">';
  popup += '<button type="button" class="popup-ok-btn" data-type="ok">Да</button>';
  popup += '<button type="button" class="popup-no-btn" data-type="no">Нет</button>';
  popup += '</div>';
  $('[data-type="popup-mess"]').html(popup);
  $('[data-type="popup-mess"]').fadeIn();
  $('[data-type="overlay"]').fadeIn();
  $('[data-type="overlay"]').css('pointer-events','none');
  $('[data-type="popup-mess"]').on('click','[data-type="ok"]',function() {
    var id = $('[data-type="order-id"]').attr('data-id');
    var req = '/personal/ajax.php?type=remove_saved&page=1&id='+id;
    $.get(req, function(data){
      popup = '<div class="popup-mess-text">Сохраненный заказ успешно удалён</div>';
      $('[data-type="popup-mess"]').html(popup);
      $('[data-type="overlay"]').css('pointer-events','auto');
      window.location.href = "/personal/#saved";
    });
  })
  $('[data-type="popup-mess"]').on('click','[data-type="no"]',function() {
    $('[data-type="popup-mess"]').fadeOut();
    $('[data-type="overlay"]').fadeOut();
    $('[data-type="overlay"]').css('pointer-events','auto');
  })
})

/**
 * отправить на онлайн-оплату
 */
$('[data-type="pay-online"]').on('click',function() {
  $(this).attr('disabled','disabled');
  $('[data-type="online-preloader"]').show();
  var id = $('[data-type="order-id"]').attr('data-id');
  $.get('/ajax/sberbank.php', {id:id}, function (data) {
    if(data == '') {
      $('[data-type="online-preloader"]').hide();
      $('[data-type="pay-online"]').removeAttr('disabled');
      $('[data-type="online-err"]').addClass('error');
      $('[data-type="online-err"]').html('Произошла ошибка!');
      $('[data-type="online-err"]').fadeIn();
      setTimeout(function(){
        $('[data-type="online-err"]').fadeOut();
      },4000);
    } else {
     /* $.post('ajax.php', {id:id,type:'send_online',link:data}, function (data) {
        $('[data-type="online-preloader"]').hide();
        $('[data-type="send-online"]').removeAttr('disabled');
        $('[data-type="online-err"]').html('Письмо с сылкой на оплату отправлено пользователю');
        $('[data-type="online-err"]').fadeIn();
        setTimeout(function(){
          $('[data-type="online-err"]').fadeOut();
        },4000);
      });*/
      window.location.href = data;
      //window.open(data, "_blank");
      $('[data-type="online-preloader"]').hide();
      $('[data-type="pay-online"]').hide();
      $('<a href="'+data+'" target="_blank" class="user-online-payment">Онлайн оплата</a>').insertBefore('[data-type="pay-online"]');

    }
  })
})