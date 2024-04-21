<style>
  @import url('https://fonts.googleapis.com/css?family=Caveat:400,700&subset=cyrillic');
  /*font-family: 'Caveat', cursive;*/
  .bnr-wrap {
    position: fixed;
    border: 1px solid #4e4e4e;
    background-color: rgba(255,255,255,1);
    z-index: 100;
    width: 564px;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
    top: 144px;
    left: 50%;
    margin-left: -282px;
    padding: 60px;
    padding-bottom: 45px;
    text-align: center;
    font-size: 14px;
    color: #040604;
    font-family: 'Open Sans';
    display: none;
    background: #fff;
    background-size: cover;
  }
  .bnr-wrap .icon-close {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 15px;
    color: #4e4e4e;
    cursor: pointer;
    -webkit-transition: .2s;
    -moz-transition: .2s;
    -ms-transition: .2s;
    -o-transition: .2s;
    transition: .2s;
  }
  .bnr-wrap .icon-close:hover {
    color: #4e4e4e;
  }
  .bnr-wrap .icon-logo {
    color: #fe5000;
    font-size: 25px;
  }
  .bnr-main {
    font-family: 'Caveat', cursive;
    color: #fe5000;
    font-size: 24px;
    padding-top: 15px;
    padding-bottom: 25px;
    position: relative;
    margin-bottom: 5px;
  }
  .bnr-main:after {
    content: '';
    height: 1px;
    width: 122px;
    background-color: #fe5000;
    bottom: 0;
    position: absolute;
    left: 50%;
    margin-left: -61px;
  }
  .bnr-mess {
      margin: 0;
  }
  .bnr-mess-bold {
    font-weight: 600;
  }
  .bnr-mess-footer {
    font-size: 12px;
    color: #929292;
    background-color: #f1f1f1;
    position: absolute;
    top: 100%;
    margin-top: 1px;
    left: -1px;
    width: 564px;
    padding: 10px;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
    text-align: left;
  }
  .bnr-mess a {
      color: #fe5000;
      border-bottom: 1px solid #fe5000;
  }
  .bnr-mess a:hover {
      color: #fe5000;
  }
</style>

<div class="bnr-wrap">
    <i class="new-icomoon icon-close" data-type="bnr-close"></i>
    <div class="bnr-mess" style="margin-bottom: 25px;">Уважаемые клиенты! </div>
    <div class="bnr-mess" style="margin-bottom: 15px;">Перед оплатой заказа просьба <span style="color: #fe5000">созвониться с менеджером</span>.</div>
</div>

<script>

  function showBanner() {
    if($('.bnr-wrap').length > 0 && $('#top_fix_region').length == 0) {
      setTimeout(function() {
        $('.bnr-wrap').fadeIn();
        $('[data-type="overlay"]').fadeIn();
      }, 1000);
    }
    else if($('.bnr-wrap').length > 0 && $('#top_fix_region').length > 0) {
      $('.top_fix_button_ok').click(function() {
        setTimeout(function() {
          $('.bnr-wrap').fadeIn();
          $('[data-type="overlay"]').fadeIn();
        }, 1000);
      })
    }
  }

  $('document').ready(function(){

    $('[data-type="bnr-close"]').click(function(){
      $('.bnr-wrap').fadeOut();
      $('[data-type="overlay"]').fadeOut();
      $.cookie('p_bnr', '1', {
        expires: 1,
        domain: domain,
        path: '/',
      });
    });
    $(document).mouseup(function (e) {
      var container = $('.bnr-wrap');
      var target = e.target;
      if (container.css('display')!='none' && container.has(e.target).length === 0 && !$(target).is('.bnr-wrap')){
        $('.bnr-wrap').fadeOut();
        $('[data-type="overlay"]').fadeOut();
        $.cookie('p_bnr', '1', {
          expires: 1,
          domain: domain,
          path: '/',
        });
        return false;
      }
        var pathname = window.location.pathname;
        var hash = location.hash;
      if($(target).is('[data-type="banner-link"]')) {
          $('.bnr-wrap').fadeOut();
          $('[data-type="overlay"]').fadeOut();
          $.cookie('p_bnr', '1', {
              expires: 1,
              domain: domain,
              path: '/',
          });
      }
    });
      $('[data-type="payment"]').on('click',function() {
          if($(this).attr('data-val') == 'online') {
              $('.bnr-wrap').fadeIn();
              $('[data-type="overlay"]').fadeIn();
          }
      })

    /*var pathname = window.location.pathname;
    var hash = location.hash;
    if(pathname == '/online-store/' && hash == '#etc3' || pathname == '/cart/'){
      showBanner();
    }

    $('[data-ed="tabs-switcher"]').on('click',function() {
      if($(this).attr('data-target') == 'etc3') {
        showBanner();
      }
    })*/

      var bnr = $.cookie('p_bnr');
     //bnr = 0;
        if(bnr != 1)
              showBanner();
          })
	
</script>