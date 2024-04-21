/**
 * Created by nadida on 14.05.2019.
 */
/**
 * поиск
 */
function debounceSP(send_request_sp, ms) {
  var timer = null;
  return function(args) {
    const onComplete = function() {
      send_request_sp(args);
      timer = null;
    }
    if (timer) {
      clearTimeout(timer);
    }
    timer = setTimeout(onComplete, ms);
  };
}

function send_request_sp(req) {
  $.get('/ajax/search.php',req, function (data) {
    var result = JSON.parse(data);
    $('*[data-type="search-resSP"]').html('');
    $('*[data-type="search-messSP"]').hide();
    if(result.qty != 0) {
      $('[data-type="search-page"]').find('[data-type="search-resSP"]').html(result.cont);
    } else {
      $('[data-type="search-page"]').find('[data-type="search-messSP"]').fadeIn();
    }
    $('[data-type="search-page"]').find('form').find('.icon-search').show();
    $('[data-type="search-page"]').find('.search-wait').hide();
  });
}
var send_request_sp = debounceSP(send_request_sp, 1000);

function searchFullSP(inp) {
  var req = {};
  req.q = $(inp).val();
  req.all = 'y';
  if($(inp).attr('data-val') == 'all'){
    let url = window.location.href;
    url = url.split('?');
    url = url[0] + '?q=' + $(inp).val();
    window.history.replaceState("", "", url);
  }
  if (req.q != '') {
    $('*[data-type="search-resSP"]').html('');
    $(inp).closest('.active').find('.search-wait').show();
    $(inp).closest('form').find('.icon-search').hide();
    send_request_sp(req);
  }
}

$(document).ready(function() {

  let searchVal = $('[data-type="search-page"]').find('[data-type="searchSP"]').val()
  if(searchVal != '') {
    searchFullSP($('[data-type="search-page"]').find('[data-type="searchSP"]'));
  }

  //сброс поиска
  $('[data-type="search-resetSP"]').on('click', function () {
    $('[data-type="search-resSP"]').html('');
    $('[data-type="search-messSP"]').hide();
    $('[data-type="search-formSP"]').find('input').val('');
    let url = window.location.href;
    url = url.split('?');
    url = url[0] + '?q= ';
    window.history.replaceState("", "", url);
    $('[data-type="search-page"]').find('.search-wait').hide();
    $(this).closest('form').find('.icon-search').show();
  })

  //при нажатии enter
  $('[data-type="searchSP"]').keydown(function (event) {
    if (event.keyCode == 13) {
      event.preventDefault();
      searchFullSP($(this));
      return false;
    }
  });

  $('[data-type="searchSP"]').keyup(function (event) {
    $('*[data-type="search-messSP"]').hide();
    //при вводе текста
    if (event.keyCode != 13) {
      var req = {};
      req.q = $(this).val();
      req.type = 'keyup';
      if (req.q != '') {
        $('*[data-type="search-resSP"]').html('');
        searchFullSP($(this));
        return false;
      }
    }
  })

})