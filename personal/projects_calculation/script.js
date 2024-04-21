/**
 * Created by nadida on 09.10.2019.
 */

//табы помещений
$('.pc-project').off('click','[data-type="open-tab"]');
$('.pc-project').on('click','[data-type="open-tab"]',function() {
  var currenTabNumber = $(this).closest('[data-type="room-item"]').index('[data-type="room-item"]');
  $('.pc-project').find('[data-type="room-item"]').each(function(i,el){
    if(i == currenTabNumber && !$(el).find('.pc-room-tab').hasClass('active')) {
        $(el).find('.pc-room-tab').addClass('active');
        /*if($(['data-type="map-notes-wrap"']).length > 0) {
            $('[data-type="map-notes"]').css({
                'position':'fixed',
                'top':'160px',
                'bottom':'unset'
            })
        }*/
        scrollMapNotes(false);
        $(el).find('.pc-room-content').slideDown(function() {scrollMapNotes(true)});
    } else {
      var wrap = $(el).find('.pc-room-tab');
      var inpName = wrap.find('[name="title"]');
      inpName.val(inpName.attr('data-val'));
      wrap.find('.pc-room-tab-left').addClass('no-active');
      wrap.removeClass('active');
      $(el).find('.pc-room-content').slideUp(function() {scrollMapNotes(true)});
    }
  })

})
//переименовать помещение
$('.pc-project').on('click','[data-type="change-room-name"]',function() {
  var wrap = $(this).closest('.pc-room-tab');
  wrap.find('.pc-room-tab-left').removeClass('no-active');
})

//сохранить
$('.pc-project').on('click','[data-type="title-save"]',function() {
  var wrap = $(this).closest('.pc-room-tab');
  var inpName = wrap.find('[name="title"]');
  if(inpName.val() == '') {
    inpName.addClass('error');
  } else {
    inpName.attr('data-val',inpName.val());
    wrap.find('.pc-room-tab-left').addClass('no-active');
  }
})
//отменить
$('.pc-project').on('click','[data-type="title-cancel"]',function() {
  var wrap = $(this).closest('.pc-room-tab');
  var inpName = wrap.find('[name="title"]');
  inpName.val(inpName.attr('data-val'));
  wrap.find('.pc-room-tab-left').addClass('no-active');
})

$('.pc-project').on('focus','[name="title"]',function() {
  $(this).removeClass('error');
})

//показать всплывающее окно - выбор карниза
$('.pc-project').on('click','[data-type="choose-cornice"]',function() {
  var wrap = $(this).closest('[data-type="room-item"]');
  var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
  scrollTop = parseInt(scrollTop) + 50;
  $('[data-val="choose-cornice"]').css('top',scrollTop+'px');
  $('[data-val="choose-cornice"]').fadeIn();
  $('[data-type="overlay"]').fadeIn();
  $('.e-new-catalogue-item').off('click');
  $('.e-new-catalogue-item').on('click',function() {
    var corniceArticul = $(this).attr('data-articul');
    if(wrap.find('.pc-room-cornice-active').length != 0 && corniceArticul != wrap.find('.pc-room-cornice-active').attr('data-val')) {
      wrap.find('.pc-wall-item').each(function() {
        if(!$(this).hasClass('no-active')) {
          $(this).remove();
        } else {
          var wallClone = $(this).clone(true);
          $(this).find('.pc-wall-title').html('Стена № '+1+' (мм)');
          $(this).removeClass('no-active');
          $(this).find('[data-type="left-corner"]').removeClass('no-clickable');
          $(this).find('[data-type="right-corner"]').removeClass('no-clickable');
          $(this).find('[data-type="wall-length"]').removeClass('no-clickable');
          $(this).removeAttr('data-type');
          var wrapWall =  wrap.find('.pc-wall-choose');
          wallClone.find('.pc-wall-title').html('Стена № '+2+' (мм)');
          wallClone.appendTo(wrapWall);
        }
      })


    }

    //$.post('/personal/projects_calculation/ajax.php', {type:'change_cornice',cornice:corniceArticul}, function (data) {

      //var elems = $.parseJSON(data);

      //$('[data-val="corner"]').find('[data-type="window-content"]').html(elems);

      var corniceItem = '<div class="pc-room-cornice-active" data-val="'+corniceArticul+'">';
      corniceItem += '<img class = "cloudzoom" src="/cron_responsive/catalog/data/images/100/'+corniceArticul+'.100.png" alt="" data-cloudzoom = "zoomImage: \'/personal/projects_calculation/img/cloudzoom/'+corniceArticul+'.png\',zoomPosition:4,tintOpacity:0,hoverIntentDelay:500">';
      corniceItem += '<div class="pc-room-cornice-label">Карниз '+corniceArticul+'</div>';
      corniceItem += '</div>';
      wrap.find('.pc-room-cornice-active').remove();
      wrap.find('[data-type="choose-cornice"]').before(corniceItem);
      $('[data-type="overlay"]').fadeOut();
      $('[data-val="choose-cornice"]').fadeOut();
      $('.pc-project').find('.cloudzoom').CloudZoom();
    })

  //})
})
//закрыть всплывающее окно
$('[data-type="close-pc-window"]').on('click', function() {
  var wrap = $(this).closest('.pc-window');
  wrap.fadeOut();
  $('[data-type="overlay"]').fadeOut();
})
$(document).mousedown(function (e) {
  var container = $('[data-val="choose-cornice"]');
  if (container.css('display')!='none' && container.has(e.target).length === 0 && !$(event.target).is('[data-val="choose-cornice"]')){
    $('[data-type="overlay"]').fadeOut();
    $('[data-val="choose-cornice"]').fadeOut();
  }
});

//показать всплывающее окно - выбор левого угла
$('.pc-project').on('click','[data-type="left-corner"]',function() {
  var btnNumb = $(this).index('.pc-wall-choose-corner');
  openCornerWindow($(this),'left');
})
//закрыть всплывающее окно
$('[data-type="close-pc-window"]').on('click', function() {
  var wrap = $(this).closest('.pc-window');
  wrap.fadeOut();
  $('[data-type="overlay"]').fadeOut();
})
$(document).mousedown(function (e) {
  var container = $('[data-val="corner"]');
  if (container.css('display')!='none' && container.has(e.target).length === 0 && !$(event.target).is('[data-val="corner"]')){
    $('[data-type="overlay"]').fadeOut();
    $('[data-val="corner"]').fadeOut();
  }
});
//показать всплывающее окно - выбор правого угла
$('.pc-project').on('click','[data-type="right-corner"]',function() {
  var btnNumb = $(this).index('.pc-wall-choose-corner');
  openCornerWindow($(this),'right');
})
//табы для всплывающего окна
$('.pc-project').on('click','[data-el="corner-tab"]',function() {
  var wrap = $(this).closest('.pc-window');
  wrap.find('[data-el="corner-tab"]').each(function() {
    $(this).removeClass('active');
  })
  wrap.find('[data-el="corner-tab-wrap"]').each(function() {
    $(this).removeClass('active');
  })
  $(this).addClass('active');
  var tabVal = $(this).attr('data-type');
  $('[data-val='+tabVal+']').addClass('active');
})
//проверка длины стены
$('.pc-project').on('blur','[data-type="wall-length"]',function() {
  if($(this).val() != parseInt($(this).val())) {
    $(this).addClass('error');
    var wrap = $(this).closest('.pc-wall-item');
    wrap.find('.pc-wall-item-err').html('Длина стены должна быть целым числом');
    wrap.find('.pc-wall-item-err').show();
  }
})
$('.pc-project').on('click','[data-type="wall-length"]',function() {
    $(this).removeClass('error');
    var wrap = $(this).closest('.pc-wall-item');
    wrap.find('.pc-wall-item-err').hide();
})
//добавить стену
$('.pc-project').on('click','[data-type="add-wall"]',function() {
  var prevWall = $(this).prev('.pc-wall-item');
  if(prevWall.length == 0 || checkPrevWall(prevWall)) {
    var wrap = $(this).closest('.pc-wall-choose');
    var wallNumberNext = wrap.find('.pc-wall-item').length;
    wallNumberNext = parseInt(wallNumberNext) + 1;
    var wallClone = $(this).clone(true);
    wallClone.find('.pc-wall-title').html('Стена № '+wallNumberNext+' (мм)');
    wallClone.appendTo(wrap);
    $(this).removeClass('no-active');
    $(this).find('[data-type="left-corner"]').removeClass('no-clickable');
    $(this).find('[data-type="right-corner"]').removeClass('no-clickable');
    $(this).find('[data-type="wall-length"]').removeClass('no-clickable');
    $(this).removeAttr('data-type');
    if(prevWall && prevWall.find('[data-type="right-corner"]').attr('data-corner-type') != 'trimming') {
      $(this).find('[data-type="left-corner"]').attr('data-corner-type',prevWall.find('[data-type="right-corner"]').attr('data-corner-type'));
      $(this).find('[data-type="left-corner"]').attr('data-corner-numb',prevWall.find('[data-type="right-corner"]').attr('data-corner-numb'));
      $(this).find('[data-type="left-corner"]').attr('data-corner-title',prevWall.find('[data-type="right-corner"]').attr('data-corner-title'));
      $(this).find('[data-type="left-corner"]').html(prevWall.find('[data-type="right-corner"]').html());
    }
    var elem = '<div class="pc-remove-wall" data-type="pc-remove-wall" title="Удалить стену"><i class="new-icomoon icon-close"></i></div>';
    $(this).find('[data-type="right-corner"]').after(elem);
  }
})
//удалить стену
$('.pc-project').on('click','[data-type="pc-remove-wall"]',function() {
  if(checkCutWall($(this).closest('.pc-wall-item'),false)) {
    $(this).closest('.pc-wall-item').remove();
  }
})
//добавить помещение
$('.pc-project').off('click','[data-type="add-room"]');
$('.pc-project').on('click','[data-type="add-room"]',function() {

  var lastRoom = $('.pc-project').find('.pc-room-item').last();
  var emptyWall = 0;
  if(lastRoom.find('.pc-wall-item').length == 1 && lastRoom.find('.pc-wall-item').hasClass('no-active')) {
    emptyWall++;
    var cont = 'Сначала введите данные по стенам для предшествующего помещения';
    openAlert(cont);
  } else {
    lastRoom.find('.pc-wall-item').each(function() {
      if(!$(this).hasClass('no-active')) {
        if(!checkPrevWall($(this))) {
          emptyWall++;
        }
      }
    })
  }
  if(emptyWall == 0) {
    var roomNumber = $('[data-type="room-item"]').length;
    roomNumber = parseInt(roomNumber) + 1;
    var room = window.newRoom.clone(true,true);
    room.find('.pc-room-title').attr('data-val','Помещение '+roomNumber);
    room.find('.pc-room-title').val('Помещение '+roomNumber);
    $('.pc-project').find('[data-type="room-item"]').each(function(i,el){
      var wrap = $(el).find('.pc-room-tab');
      var inpName = wrap.find('[name="title"]');
      inpName.val(inpName.attr('data-val'));
      wrap.find('.pc-room-tab-left').addClass('no-active');
      wrap.removeClass('active');
      $(el).find('.pc-room-content').slideUp();
    })
    $(this).before(room);
  }
})
//удалить помещение
$('.pc-project').off('click','[data-type="remove-room"]');
$('.pc-project').on('click','[data-type="remove-room"]',function() {
  $(this).closest('[data-type="room-item"]').remove();
})
//очистить поля
$('[data-type="pc-clear"]').on('click',function() {
  var cont = 'Вы точно хотите удалить все данные, внесенные в проект?';
  openAttention(cont);
  $('[data-type="att-yes"]').on('click', function() {
    $('.pc-project').find('.pc-room-item').each(function() {
      $(this).remove();
    })
    var room = window.newRoom.clone(true,true);
    room.find('[name="title"]').attr('data-val','Помещение 1');
    room.find('[name="title"]').val('Помещение 1');
    $('[data-type="add-room"]').before(room);
    var getParam = '';
    if($('.pc-project').find('.pc-room-cornice-active').length != 0) {
      getParam = '?cornice='+$('.pc-project').find('.pc-room-cornice-active').attr('data-val');
    }
    var url = '/personal/'+getParam +'#projects_calculation';
    history.pushState(null, null, url);
    $('[data-type="attention-window"]').fadeOut();
    $('[data-type="alert-overlay"]').fadeOut();
  })
  $('[data-type="att-no"]').on('click', function() {
    $('[data-type="attention-window"]').fadeOut();
    $('[data-type="alert-overlay"]').fadeOut();
    return false;
  })


})
//сообщить о нетипичной стене
$('[data-type="pc-unusuall"]').on('click',function(){
  $('[data-type="overlay"]').fadeIn();
  $('[data-type="q-popup"]').fadeIn();
})
$('[data-type="pc-unusuall"]').on('click',function(){
  yaCounter22165486.reachGoal('question');
})
//закрыть alert
$('[data-type="close-alert"]').on('click',function() {
  $('[data-type="alert-window"]').fadeOut();
  $('[data-type="alert-overlay"]').fadeOut();
  return false;
})

//рассчитать проект
$('.pc-project').off('click','[data-type="calculate-project"]');
$('.pc-project').on('click','[data-type="calculate-project"]',function() {

  //100500 проверок
  if($('.pc-project').find('.pc-room-item').length == 0) {
    var cont = 'В вашем проекте нет ни одного помещения. <br>Сначала добавьте помещение и внесите данные для расчета.'
    openAlert(cont);
    return false;
  } else {
    var err = 0;
    $('.pc-project').find('.pc-room-item').each(function() {
      if($(this).find('.pc-wall-item').length == 1 && $(this).find('.pc-wall-item').hasClass('no-active')) {
       $(this).find('.pc-room-tab').addClass('active');
        $(this).find('.pc-room-content').slideDown();
        var cont = 'В вашем проекте найдено помещение, <br>в котором нет внесенных данных по стенам. <br>Исправьте данные и затем снова нажмите <br>кнопку "Рассчитать проект".'
        openAlert(cont);
        return false;
      } else {
        $(this).find('.pc-wall-item').each(function() {
          if(!$(this).hasClass('no-active')) {
            if(!checkPrevWall($(this))) {
              err++;
            }
          }
        })
      }
    })
    if(err > 0) {
      var cont = 'В вашем проекте некоторые данные введены некоррето. <br>Исправьте данные и затем снова нажмите <br>кнопку "Рассчитать проект".'
      openAlert(cont);
      return false;
    }
  }
  $(this).closest('.loader-wrap').find('img').show();
  $(this).attr('disabled','disabled');
  var arr = [];
  //var url = '';
    var params = window
        .location
        .search
        .replace('?','')
        .split('&')
        .reduce(
            function(p,e){
                var a = e.split('=');
                p[ decodeURIComponent(a[0])] = decodeURIComponent(a[1]);
                return p;
            },
            {}
        );

    var url = getUrl();

    if(params['proj_numb']) {
        if(url == oldUrl) {
            url = '&proj_numb='+params['proj_numb'];
        } else {
            url += '&proj_numb='+params['proj_numb'];
        }
    }
    //console.log(oldUrl);


    /*if(params['proj_numb']) {
        url += '&proj_numb='+params['proj_numb'];
    }*/



  var resultHref = '/personal/?type=result'+url+'#projects_calculation';
  document.location.href = resultHref;


})




$(document).ready(function() {
  if($('[data-type="calculate-project"]').length > 0) {
      window.oldUrl = getUrl();
  }
})

//собрать адрес для страницы результата
function getUrl() {
  var url = '';
    $('.pc-project').find('.pc-room-item').each(function(i){
        url += "&arr[" + i + "][name]=" + $(this).find('.pc-room-title').val();
        url += "&arr[" + i + "][cornice_article]=" + $(this).find('.pc-room-cornice-active').attr('data-val');
        $(this).find('.pc-wall-item').each(function(index,element) {

            if(!$(element).hasClass('no-active')) {
                //левый угол
                url += "&arr[" + i + "][walls][" + index + "][wall_info][corner_1][type]=" + $(element).find('[data-type="left-corner"]').attr('data-corner-type');
                url += "&arr[" + i + "][walls][" + index + "][wall_info][corner_1][number]=" + $(element).find('[data-type="left-corner"]').attr('data-corner-numb');
                if($(element).find('[data-type="left-corner"]').attr('data-corner-type') == 'trimming' && $(element).find('[data-type="left-corner"]').attr('data-corner-numb') !== undefined && $(element).find('[data-type="left-corner"]').attr('data-corner-numb')!== false && $(element).find('[data-type="left-corner"]').attr('data-corner-numb') !== '') {
                    url += "&arr[" + i + "][walls][" + index + "][wall_info][corner_1][trimming_fit]=yes";
                    url += "&arr[" + i + "][walls][" + index + "][wall_info][corner_1][trimming_fit_wall]=" + $(element).find('[data-type="left-corner"]').attr('data-corner-numb');
                }
                //правый угол
                url += "&arr[" + i + "][walls][" + index + "][wall_info][corner_2][type]=" + $(element).find('[data-type="right-corner"]').attr('data-corner-type');
                url += "&arr[" + i + "][walls][" + index + "][wall_info][corner_2][number]=" + $(element).find('[data-type="right-corner"]').attr('data-corner-numb');
                if($(element).find('[data-type="right-corner"]').attr('data-corner-type') == 'trimming' && $(element).find('[data-type="right-corner"]').attr('data-corner-numb') !== undefined && $(element).find('[data-type="left-corner"]').attr('data-corner-numb')!== false && $(element).find('[data-type="right-corner"]').attr('data-corner-numb') !== '') {
                    url += "&arr[" + i + "][walls][" + index + "][wall_info][corner_2][trimming_fit]=yes";
                    url += "&arr[" + i + "][walls][" + index + "][wall_info][corner_2][trimming_fit_wall]=" + $(element).find('[data-type="right-corner"]').attr('data-corner-numb');
                }
                url += "&arr[" + i + "][walls][" + index + "][wall_info][length]=" + $(element).find('[data-type="wall-length"]').val()
            }
        })
    })
    return url;
}

//открыть alert window
function openAlert(cont) {
  var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
  scrollTop = parseInt(scrollTop) + 50;
  $('[data-type="alert-window"]').css('top',scrollTop+'px');
  $('[data-type="alert-window"]').find('[data-type="alert-content"]').html(cont);
  $('[data-type="alert-window"]').fadeIn();
  $('[data-type="alert-overlay"]').fadeIn();
  return false;
}
//открыть attention window
function openAttention(cont) {
  var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
  scrollTop = parseInt(scrollTop) + 50;
  $('[data-type="attention-window"]').css('top',scrollTop+'px');
  $('[data-type="attention-window"]').find('[data-type="alert-content"]').html(cont);
  $('[data-type="attention-window"]').fadeIn();
  $('[data-type="alert-overlay"]').fadeIn();
  return false;
}
//проверяем, была ли указана данная стена в качестве образца для подгоночного участка
function checkCutWall(wall,corner) {
    var number = wall.index();
    var err = 0;
    var walls = '';
    wall.closest('.pc-wall-choose').find('.pc-wall-item').each(function(i,el) {
      if(corner == 'left' || !corner ) {
        if($(el).find('[data-type="right-corner"]').attr('data-corner-type') == 'trimming' && $(el).find('[data-type="right-corner"]').attr('data-corner-numb') === String(number)) {
          err++;
          var wallNumber = parseInt(i)+1;
          walls += ' стена '+wallNumber+' (правый угол)<br>';
        }
      }
      if(corner == 'right' || !corner ) {
        if($(el).find('[data-type="left-corner"]').attr('data-corner-type') == 'trimming' && $(el).find('[data-type="left-corner"]').attr('data-corner-numb') === String(number)) {

          err++;
          var wallNumber = parseInt(i)+1;
          walls += 'стена '+wallNumber+' (левый угол)<br>';
        }
      }
    })

  if(err == 0) {
      return true;
  } else {
    walls = walls.substring(0, walls.length - 4);
    var cont = 'Данная стена выбрана вами в качестве подгонки для следующих стен: <div class="trim-walls">'+walls+'</div>Совершаемые вами действия приведут к некорректному расчету. Пожалуйста, сначала измените тип угла для указанных стен, <br>затем повторите операцию.';
    openAlert(cont);
      return false;
  }

}

//проверяем данные предшествующей стены
function checkPrevWall(wall) {
  var leftCorner = wall.find('[data-type="left-corner"]');
  var wallLength = wall.find('[data-type="wall-length"]');
  var rightCorner = wall.find('[data-type="right-corner"]');
  var err = 0;
  if(leftCorner.attr('data-corner-type')=== undefined || leftCorner.attr('data-corner-type')=== false || leftCorner.attr('data-corner-type') === '') {
    err++;
    leftCorner.addClass('error');
  }
  if(rightCorner.attr('data-corner-type')=== undefined || rightCorner.attr('data-corner-type')=== false || rightCorner.attr('data-corner-type') === '') {
    err++;
    rightCorner.addClass('error');
  }
  if(wallLength.val() == '') {
    err++;
    wallLength.addClass('error');
  }
  if(err == 0) {
    return true;
  } else {
    wall.closest('.pc-room-item').find('.pc-room-tab').addClass('active');
    wall.closest('.pc-room-item').find('.pc-room-content').slideDown();
    wall.find('.pc-wall-item-err').html('Корректно введите все данные');
    wall.find('.pc-wall-item-err').show();
    return false;
  }
}

//формируем окно выбора угла
function openCornerWindow(button,typeWindow) {

  if(button.closest('.pc-room-item').find('.pc-room-cornice-active').length == 0) {
    var cont = 'Сначала выберите карниз для помещения в п.1';
    openAlert(cont);
    return false;
  }
  var corniceArticul = button.closest('.pc-room-item').find('.pc-room-cornice-active').attr('data-val');
  $.post('/personal/projects_calculation/ajax.php', {type:'change_cornice',cornice:corniceArticul}, function (data) {

    var elems = $.parseJSON(data);

    $('[data-val="corner"]').find('[data-type="window-content"]').html(elems);

    $('.pc-project').find('.cloudzoom').CloudZoom();

    button.removeClass('error');
  button.closest('.pc-wall-item').find('.pc-wall-item-err').hide();
  var wrapWindow = $('[data-val="corner"]');
  wrapWindow.find('[data-el="corner-tab"]').each(function() {
    $(this).removeClass('active');
  })
  wrapWindow.find('[data-el="corner-tab-wrap"]').each(function() {
    $(this).removeClass('active');
  })
  wrapWindow.find('[data-el="corner-tab"]').first().addClass('active');
  wrapWindow.find('[data-el="corner-tab-wrap"]').first().addClass('active');
  wrapWindow.find('.trimming-option-wrap').each(function() {
    $(this).removeClass('active');
  })
  wrapWindow.find('[data-type="pc-choose-wall"]').val('');
  wrapWindow.find('.jq-selectbox__select-text').html('Как у стены');
  wrapWindow.find('.jq-selectbox__dropdown').find('li').each(function() {
    $(this).removeAttr('class');
  })
  wrapWindow.find('[data-type="pc-choose-wall"]').styler();
  wrapWindow.find('[data-type="choose-type-corner"]').each(function() {
    if($(this).hasClass('choosen')) {
      $(this).html('<i class="new-icomoon icon-plus"></i>Выбрать');
      $(this).removeClass('choosen');
    }
  })
  if(button.closest('.pc-wall-item').index() == 0) {
    wrapWindow.find('[data-val="yes"]').closest('.trimming-option-wrap').addClass('no-active');
  } else {
    wrapWindow.find('[data-val="yes"]').closest('.trimming-option-wrap').removeClass('no-active');
    //варианты возможных стен
    var optionSet = '<option></option>';
    button.closest('.pc-wall-choose').find('.pc-wall-item').each(function(i,el) {
      if(i < button.closest('.pc-wall-item').index()) {
        var number = parseInt(i)+1;
        if(typeWindow == 'left' && $(el).find('[data-type="right-corner"]').attr('data-corner-type') != 'trimming') {
          optionSet += '<option value="'+i+'">Стена '+number+'</option>';
        }
        if(typeWindow == 'right' && $(el).find('[data-type="left-corner"]').attr('data-corner-type') != 'trimming') {
          optionSet += '<option value="'+i+'">Стена '+number+'</option>';
        }
      }
    })
    if(optionSet == '<option></option>') wrapWindow.find('[data-val="yes"]').closest('.trimming-option-wrap').addClass('no-active');
    wrapWindow.find('[data-type="pc-choose-wall"]').html(optionSet);
    $('[data-type="pc-choose-wall"]').trigger('refresh');
  }


  if(button.attr('data-corner-type')!== undefined && button.attr('data-corner-type')!== false && button.attr('data-corner-type') !== '') {
    wrapWindow.find('[data-el="corner-tab"]').each(function() {
      $(this).removeClass('active');
    })
    wrapWindow.find('[data-el="corner-tab-wrap"]').each(function() {
      $(this).removeClass('active');
    })
    wrapWindow.find('[data-type="'+button.attr('data-corner-type')+'"]').addClass('active');
    wrapWindow.find('[data-val="'+button.attr('data-corner-type')+'"]').addClass('active');
    if(button.attr('data-corner-type') == 'trimming') {
      if(button.attr('data-corner-numb')!== undefined && button.attr('data-corner-numb')!== false && button.attr('data-corner-numb') !== '') {
        $('[data-val="trimming"]').find('[data-val="yes"]').closest('.trimming-option-wrap').addClass('active');
        var wall = button.attr('data-corner-numb');
        wall = wrapWindow.find('[data-type="pc-choose-wall"]').find('[value="'+wall+'"]').index();
        wrapWindow.find('[data-type="pc-choose-wall"]').val(wall);
        //wall = parseInt(wall)+1;
        wrapWindow.find('.jq-selectbox__dropdown').find('li:eq('+wall+')').addClass('selected sel');
        var wallTitle = wrapWindow.find('.jq-selectbox__dropdown').find('li:eq('+wall+')').html();
        wrapWindow.find('.jq-selectbox__select-text').html(wallTitle);
      } else {
        $('[data-val="trimming"]').find('[data-val="no"]').closest('.trimming-option-wrap').addClass('active');
      }
    } else {
      //todo: заментиь data-title на data-numb
      //var btnAct = wrapWindow.find('[data-val="'+button.attr('data-corner-type')+'"]').find('[data-numb="'+button.attr('data-corner-numb')+'"]').find('[data-type="choose-type-corner"]');
      var btnAct = wrapWindow.find('[data-val="'+button.attr('data-corner-type')+'"]').find('[data-title="'+button.attr('data-corner-title')+'"]').find('[data-type="choose-type-corner"]');
      btnAct.html('Выбран');
      btnAct.addClass('choosen');
    }
  }

  var wrap = $(this).closest('[data-type="room-item"]');
  var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
  scrollTop = parseInt(scrollTop) + 50;
  $('[data-val="corner"]').css('top',scrollTop+'px');
  $('[data-val="corner"]').fadeIn();
  $('[data-type="overlay"]').fadeIn();
  //выбрать угол
  $('.pc-project').off('click','[data-type="choose-type-corner"]');
  $('.pc-project').on('click','[data-type="choose-type-corner"]',function() {
    var corner = $(this).closest('[data-type="corner-prew"]');
    var type = corner.closest('[data-el="corner-tab-wrap"]').attr('data-val');
    var number = corner.attr('data-numb');
    var title = corner.attr('data-title');
    button.attr('data-corner-numb',number);
    button.attr('data-corner-type',type);
    button.attr('data-corner-title',title);
    button.html('Угол '+title+' <i class="new-icomoon icon-settings"></i>');
    $('[data-type="overlay"]').fadeOut();
    $('[data-val="corner"]').fadeOut();
  })
  //выбрать торцовку
  $('.pc-project').off('click', '[data-type="trimming-option"]');
  $('.pc-project').on('click','[data-type="trimming-option"]',function() {
    if(checkCutWall(button.closest('.pc-wall-item'),typeWindow)) {
      var wrapOpt = $(this).closest('.trimming-option-wrap');
      wrapWindow.find('.trimming-option-wrap').each(function() {
        $(this).removeClass('active');
      })
      wrapOpt.addClass('active');
      if($(this).attr('data-val') == 'no') {
        var type = $(this).closest('[data-el="corner-tab-wrap"]').attr('data-val');
        var number = '';
        button.attr('data-corner-numb',number);
        button.attr('data-corner-type',type);
        button.html('Торцовка <i class="new-icomoon icon-settings"></i>');
        setTimeout(function() {
          $('[data-type="overlay"]').fadeOut();
          $('[data-val="corner"]').fadeOut();
        },200);
      }
      if($(this).attr('data-val') == 'yes') {
        $('.pc-project').on('change','[data-type="pc-choose-wall"]',function() {
          var type = $(this).closest('[data-el="corner-tab-wrap"]').attr('data-val');
          var number = $(this).val();
          button.attr('data-corner-numb',number);
          button.attr('data-corner-type',type);
          button.html('Торцовка <i class="new-icomoon icon-settings"></i>');
          setTimeout(function() {
            $('[data-type="overlay"]').fadeOut();
            $('[data-val="corner"]').fadeOut();
          },200);
        })
      }
    }

  })
  })
  return false;
}

//прокрутка легенды
$(window).scroll(function() {
  scrollMapNotes(true)
  });

function scrollMapNotes(par) {
  var includeBottom = par;
  if($('[data-type="map-notes"]').length != 0) {
    var mapTop = $('[data-type="map-notes"]').offset().top + $('[data-type="map-notes"]').innerHeight()+1;//+1 для глюка в експлорере
    var mapTopWindow = $('[data-type="map-notes"]')[0].getBoundingClientRect().top;
    var wrapperTop = $('[data-type="map-notes-wrap"]').offset().top + $('[data-type="map-notes-wrap"]').innerHeight();
    if ($(this).scrollTop() > 750)  {
      if(parseInt(mapTop) >= parseInt(wrapperTop) && mapTopWindow <= 161 && includeBottom ) {
        $('[data-type="map-notes"]').css({
          'position':'absolute',
          'top':'unset',
          'bottom': '0'
        })
        return false;
      } else {
        $('[data-type="map-notes"]').css({
          'position':'fixed',
          'top':'160px',
          'bottom':'unset'
        })
        return false;
      }
    } else {
      $('[data-type="map-notes"]').css({
        'position':'relative',
        'top':'unset',
        'bottom':'unset'
      })
      return false;
    }
    return false;
  }
}

/**
 * сохранить проект
 */
$('[data-type="save-proj"]').on('click',function() {
  var btn =  $(this);
  btn.attr('disabled','disabled');
  var arr = collectData();
  //console.log(arr);
  var userId = $('[data-type="personal-data"]').attr('data-id');
  btn.find('[data-type="loader"]').show();
  //console.log(arr);
  $.post('/personal/projects_calculation/ajax_save.php', {type:'save',id:userId,arr:arr}, function(data) {
      btn.find('[data-type="loader"]').hide();
      data = $.parseJSON(data);
      if(data['code'] == 0) {
        $('.pc-project-number').html('Проект №'+data['number']);
        $('.pc-project-date').html(data['date']+" <span>"+data['time']+"<span>");
        $('.pc-project-title').css('display','flex');
        $('[data-type="save-proj"]').addClass('no-active');
        $('[data-type="proj-saved"]').removeClass('no-active');
        window.history.replaceState("", "", "/personal/?type=result&proj_numb="+data['number']+"#projects_calculation");
        $('[data-type="pc-back"]').attr('href','/personal/?proj_numb='+data['number']+'#projects_calculation');
      }
  } )
})
/**
 * сохранить измения
 */
$('[data-type="save-changes"]').on('click',function() {
    var btn =  $(this);
    btn.attr('disabled','disabled');
    var arr = collectData();
    //console.log(arr);
    var userId = $('[data-type="personal-data"]').attr('data-id');
    var projNumb = $('.pc-project-number').attr('data-number');
    btn.find('[data-type="loader"]').show();
    //console.log(arr);
    $.post('/personal/projects_calculation/ajax_save.php', {type:'change',id:userId,arr:arr,numb:projNumb}, function(data) {
        btn.find('[data-type="loader"]').hide();
        data = $.parseJSON(data);
        if(data['code'] == 0) {
            $('.pc-project-title').css('display','flex');
            $('[data-type="save-changes"]').addClass('no-active');
            $('[data-type="proj-saved"]').removeClass('no-active');
            window.history.replaceState("", "", "/personal/?type=result&proj_numb="+projNumb+"#projects_calculation");
            $('[data-type="pc-back"]').attr('href','/personal/?proj_numb='+projNumb+'#projects_calculation');
        }
    } )
})

/**
 * открыть проект в окне рассчета
 */
$('[name="pc-choose"]').change(function() {
  $(this).closest('.select-wrap').find('img').show();
  var numb = $(this).val();
  var url = '/personal/?proj_numb='+numb+'#projects_calculation';
  document.location.href = url;
})

/**
 * создать новый проект
 */
$('[data-type="create-project"]').on('click',function() {
    $(this).closest('.select-wrap').find('img').show();
    var url = '/personal/?create=true#projects_calculation';
    document.location.href = url;
})

/**
 *
 * собираем инфу по стенам для сохранения в бд
 */
function collectData() {
  var arr = {};
  var i = 0;
  $('[data-type="room-item"]').each(function() {
    var item = {};
    item.name = $(this).find('[name="title"]').val();
    item.cornice_article = $(this).find('.result-room-summary-info-title-name').attr('data-val');
    item.walls = {};
    $(this).find('.result-room-wall').each(function(k,wall) {
      var wall_arr = {};
      item.walls[k] = {};
      wall_arr['corner_1'] = {};
      var wallInfo1 = $(wall).find('[data-type="corner_1"]').find('[data-type="corner-info"]');
      wall_arr['corner_1'].type = wallInfo1.attr('type');
      if(wall_arr['corner_1'].type != 'trimming') {
          wall_arr['corner_1'].number = wallInfo1.attr('number');
      } else {
        if(wallInfo1.attr('trimming_fit')=='yes') {
            wall_arr['corner_1'].trimming_fit = wallInfo1.attr('trimming_fit');
            wall_arr['corner_1'].trimming_fit_wall = wallInfo1.attr('trimming_fit_wall');
        }
      }
        wall_arr['corner_2'] = {};
        var wallInfo2 = $(wall).find('[data-type="corner_2"]').find('[data-type="corner-info"]');
        wall_arr['corner_2'].type = wallInfo2.attr('type');
        if(wall_arr['corner_2'].type != 'trimming') {
            wall_arr['corner_2'].number = wallInfo2.attr('number');
        } else {
            if(wallInfo2.attr('trimming_fit')=='yes') {
                wall_arr['corner_2'].trimming_fit = wallInfo2.attr('trimming_fit');
                wall_arr['corner_2'].trimming_fit_wall = wallInfo2.attr('trimming_fit_wall');
            }
        }
        wall_arr['length'] = $(this).find('[data-type="length"]').attr('data-val');
        console.log(wall_arr);
      item.walls[k].wall_info = wall_arr;
    })

    arr[i] = item;
    i++;
  })
    return arr;
}

function pcInit() {
  $('[data-type="pc-choose"]').styler();
  CloudZoom.quickStart();

  if($('[data-type="room-item"]').length != 0) {
    window.newRoom = $('[data-type="room-item"]').first().clone(true,true);
  }
  if($('.pc-wall-item').first().find('[data-type="wall-length"]').val() != '') {
    var cont = '';
    cont += '<div class="pc-room-cornice-wrap">';
    cont += '<div class="pc-room-cornice-title">1. Наименование элемента</div>';
    cont += '<div class="pc-room-cornice-desc">Выберите карниз для помещения:</div>';
    cont += '<div class="pc-cornice-choose">';
    cont += '<div class="pc-room-cornice-choose-btn" data-type="choose-cornice" data-btn="open-pc-window">';
    cont += '<div class="choose-btn-title">Выбрать <br>другой элемент</div><i class="new-icomoon icon-plus-symbol"></i></div>';
    cont += '</div>';
    cont += '</div>';
    cont += '<div class="pc-room-walls-wrap">';
    cont += '<div class="pc-room-cornice-title">2. Введите размеры стен:</div>';
    cont += '<div class="pc-room-cornice-desc">Последовательно вводите стены помещения. <br>Для каждой стены выберите из предложенных вид левого и правого углов и введите длину стены в мм. <br>Длина стены должна быть целым числом.</div>';
    cont += '<div class="pc-wall-choose">';
    cont += '<div class="pc-wall-item">';
    cont += '<div class="pc-wall-title">Стена № 1 (мм)</div>';
    cont += '<div class="pc-wall-choose-corner" data-type="left-corner" data-act="add-left">Выбрать угол <i class="new-icomoon icon-plus"></i></div>';
    cont += '<input class="pc-wall-length" data-type="wall-length" title="Длина стены">';
    cont += '<div class="pc-wall-choose-corner" data-type="right-corner" data-act="add-right" data-val="">Выбрать угол <i class="new-icomoon icon-plus"></i></div>';
    cont += '<div class="pc-remove-wall" data-type="pc-remove-wall" title="Удалить стену"><i class="new-icomoon icon-close"></i></div>';
    cont += '<div class="pc-wall-item-err">Длина стены должна быть целым числом</div>';
    cont += '</div>';
    cont += '<div class="pc-wall-item no-active" data-type="add-wall">';
    cont += '<div class="pc-wall-title">Стена № 2 (мм)</div>';
    cont += '<div class="pc-wall-choose-corner no-clickable" data-type="left-corner" data-act="add-left" data-val="">Выбрать угол <i class="new-icomoon icon-plus"></i></div>';
    cont += '<input class="pc-wall-length no-clickable" data-type="wall-length" title="Длина стены">';
    cont += '<div class="pc-wall-choose-corner no-clickable" data-type="right-corner" data-act="add-right" data-val="">Выбрать угол <i class="new-icomoon icon-plus"></i></div>';
    cont += '<div class="pc-wall-item-err">Длина стены должна быть целым числом</div>';
    cont += '</div>';
    window.newRoom.find('.pc-room-content').html(cont);
  }

}
document.addEventListener("DOMContentLoaded", pcInit);