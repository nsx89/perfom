/**
 * Created by nadida on 14.05.2019.
 */
/**
 * поиск
 */
function getSearchProdQty() {
  if(window.innerWidth <= 800) {
    return 4;
  }
  else if(window.innerWidth <= 1000) {
    return 3;
  }
  else if(window.innerWidth <= 1279) {
    return 4;
  }
  else if(window.innerWidth <= 2259) {
    return 5;
  }
  else if(window.innerWidth <= 2610) {
    return 6;
  }
  else if(window.innerWidth <= 2959) {
    return 7;
  }
  else if(window.innerWidth > 2959) {
    return 8;
  }
  else if(window.innerWidth > 3309) {
    return 9;
  }
  else {
    return 10;
  }
}
function getSearchArtQty() {
  if(window.innerWidth <= 800) {
    return 2;
  }
  else if(window.innerWidth <= 1279) {
    return 3;
  }
  else {
    return 4;
  }
}
function debounce(send_request, ms) {
  var timer = null;
  return function(args) {
    const onComplete = function() {
      send_request(args);
      timer = null;
    }
    if (timer) {
      clearTimeout(timer);
    }
    timer = setTimeout(onComplete, ms);
  };
}

function send_request(req) {
  $.get('/ajax/search.php',req, function (data) {
    var result = JSON.parse(data);
    $('*[data-type="search-res"]').html('');
    $('*[data-type="search-mess"]').hide();
    if(req.all == 'y') {
      if(result.qty != 0) {
        $('[data-type="search-page"]').find('[data-type="search-res"]').html(result.cont);
      } else {
        $('[data-type="search-page"]').find('[data-type="search-mess"]').fadeIn();
      }
      $('[data-type="search-page"]').find('form').find('.icon-search').show();
    } else {
      if(result.qty == 0) {
        if(window.innerWidth > 950) {
          $('.header-search-wrap').find('[data-type="search-mess"]').fadeIn();
        } else {
          $('[data-type="search-wrap"]').find('[data-type="search-mess"]').fadeIn();
        }
      } else {
        $('[data-type="search-res"]').html(result.cont);
        $('[data-type="search-wrap"]').addClass('active');
      }
    }
    $('*.search-wait').hide();
    $('.header-search-wrap').find('[data-type="search-open"]').show();
    $('[data-type="search-wrap"]').find('form').find('.icon-search').show();
  });
}
var send_request = debounce(send_request, 1000);

function searchFull(inp) {
  var req = {};
  req.q = $(inp).val();
  req.prod = getSearchProdQty();
  req.art = getSearchArtQty();
  req.all = 'n';
  if($(inp).attr('data-val') == 'all'){
    let url = window.location.href;
    url = url.split('?');
    url = url[0] + '?q=' + $(inp).val();
    req.all = 'y';
    window.history.replaceState("", "", url);
  }
  if (req.q != '') {
    $('*[data-type="search-res"]').html('');
    $(inp).closest('.active').find('.search-wait').show();
    $(inp).closest('.header-search-wrap').find('[data-type="search-open"]').hide();
    $(inp).closest('form').find('.icon-search').hide();
    send_request(req);
  }
}

$(document).ready(function() {

  $('.header-search-wrap').on('click','[data-type="search-open"]',function() {
    if(window.innerWidth > 950) {
      let wrap = $('.header-search-wrap');
      if(!wrap.hasClass('active')) {
        wrap.addClass('active');
        wrap.find('input').focus();
      }
    } else {
      let wrap = $('[data-type="search-wrap"]');
      if(!wrap.hasClass('active')) {
        wrap.addClass('active');
        wrap.find('input').focus();
      }
    }

  })

  //сброс поиска
  $('[data-type="search-reset"]').on('click',function() {
    $('[data-type="search-res"]').html('');
    $('[data-type="search-wrap"]').removeClass('active');
    $('.header-search-wrap').removeClass('active');
    $('*[data-type="search-mess"]').hide();
    $('*.search-wait').hide();
    $(this).closest('.header-search-wrap').find('[data-type="search-open"]').show();
    $(this).closest('form').find('.icon-search').show();
  })

  //при нажатии enter
  $('[data-type="search"]').keydown(function(event) {
    if(event.keyCode == 13) {
      event.preventDefault();
      searchFull($(this));
      return false;
    }
  });

  $('[data-type="search"]').keyup(function(event) {
    $('*[data-type="search-mess"]').hide();
    //при вводе текста
    if(event.keyCode != 13) {
      var req = {};
      req.q = $(this).val();
      req.type = 'keyup';
      if (req.q != '') {
        $('*[data-type="search-res"]').html('');
        searchFull($(this));
        return false;
      }
    }
  });

  //закрытие поиска при клике вне поиска
  $(document).mouseup(function (e) {
    let container = $('.header-search-wrap');
    let target = e.target;
    if (container.hasClass('active') && !$('[data-type="search-wrap"]').hasClass('active') && container.has(e.target).length === 0 && !$(target).is('.header-search-wrap')){
      $('[data-type="search-wrap"]').removeClass('active');
      container.removeClass('active');
      container.find('input').val('');
      $('*[data-type="search-mess"]').hide();
    }
  });

  //табы в поиске
  $('.content-wrapper').on('click','[data-type="search-tab"]',function() {
    $('[data-type="search-tab"]').each(function() {
      $(this).removeClass('active');
    })
    $('.search-tab-cont').each(function() {
      $(this).removeClass('active');
    })
    var id = $(this).attr('data-id');
    $(this).addClass('active');
    $(id).addClass('active');
  })
})



