<style>
  @import url('https://fonts.googleapis.com/css?family=Caveat:400,700&subset=cyrillic');
  /*font-family: 'Caveat', cursive;*/
  .opbnr-wrap {
    position: fixed;
    background-color: rgba(255,255,255,1);
    z-index: 100;
    width: 448px;
    height: 448px;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
    padding: 50px 0 50px 60px;
    top: 144px;
    left: 50%;
    margin-left: -224px;
    font-size: 14px;
    font-family: 'Open Sans';
    display: none;
    background: #fff;
    background-size: cover;
    -webkit-box-shadow: 0px 0px 72.24px 11.76px rgba(94, 94, 94, 0.22);
    -moz-box-shadow: 0px 0px 72.24px 11.76px rgba(94, 94, 94, 0.22);
    box-shadow: 0px 0px 72.24px 11.76px rgba(94, 94, 94, 0.22);
  }
  .opnbnr-cont {
      width: 100%;
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      align-items: flex-start;
  }
  .opbnr-wrap .icon-close {
    position: absolute;
    top: 0px;
    right: 0px;
    margin-top: -18px;
    margin-right: -18px;
    font-size: 16px;
    color: #61858d;
    cursor: pointer;
    -webkit-transition: .2s;
    -moz-transition: .2s;
    -ms-transition: .2s;
    -o-transition: .2s;
    transition: .2s;
  }
  .opbnr-wrap .icon-close:hover {
    color: #4e4e4e;
  }
  .opbnr-mess-title {
      font-size: 44px;
      font-weight: 600;
      color: #61858d;
      line-height: 1;

  }
  .opbnr-mess {
      margin: 0;
      font-size: 16px;
      line-height: 22px;
  }
  .opbnr-mess span {
      font-weight: 600;
      color: #61858d;
  }
    .opbnr-mess-highlight {
        background-color: #61858d;
        display: table;
        color: #fff;
        font-size: 32px;
        font-weight: 600;
        padding: 5px 10px 10px;
        margin-top: 5px;
        line-height: 1;

    }
</style>

<div class="opbnr-wrap">
    <i class="new-icomoon icon-close" data-type="opbnr-close"></i>
    <div class="opnbnr-cont">
        <div class="opbnr-mess-title">Уважаемые покупатели! </div>
        <div class="opbnr-mess">Мы рады сообщить Вам, что наши<br>
            фирменные магазины, расположенные<br>
            в <span>ЦДиИ Экспострой на Нахимовском</span><br>
            и <span>МФТК Каширский двор</span></div>
        <div class="opnbnr-highlight">
            <div class="opbnr-mess-highlight">с 1 июня вновь</div>
            <div class="opbnr-mess-highlight">работают для Вас</div>
        </div>
    </div>
</div>

<script>

  function showBanner() {
    if($('.opbnr-wrap').length > 0 && $('#top_fix_region').length == 0) {
      setTimeout(function() {
        $('.opbnr-wrap').fadeIn();
        $('[data-type="overlay"]').fadeIn();
      }, 1000);
    }
    else if($('.opbnr-wrap').length > 0 && $('#top_fix_region').length > 0) {
      $('.top_fix_button_ok').click(function() {
        setTimeout(function() {
          $('.opbnr-wrap').fadeIn();
          $('[data-type="overlay"]').fadeIn();
        }, 1000);
      })
    }
  }

  $('document').ready(function(){

    $('[data-type="opbnr-close"]').click(function(){
      $('.opbnr-wrap').fadeOut();
      $('[data-type="overlay"]').fadeOut();
      $.cookie('opbnr', '1', {
        expires: 1,
        domain: domain,
        path: '/',
      });
    });
    $(document).mouseup(function (e) {
      var container = $('.opbnr-wrap');
      var target = e.target;
      if (container.css('display')!='none' && container.has(e.target).length === 0 && !$(target).is('.opbnr-wrap')){
        $('.opbnr-wrap').fadeOut();
        $('[data-type="overlay"]').fadeOut();
        $.cookie('opbnr', '1', {
          expires: 1,
          domain: domain,
          path: '/',
        });
        return false;
      }
        var pathname = window.location.pathname;
        var hash = location.hash;
      if($(target).is('[data-type="banner-link"]')) {
          $('.opbnr-wrap').fadeOut();
          $('[data-type="overlay"]').fadeOut();
          $.cookie('opbnr', '1', {
              expires: 1,
              domain: domain,
              path: '/',
          });
      }
    });

      var opbnr = $.cookie('opbnr');
      //opbnr = 0;
        if(opbnr != 1)
              showBanner();
          })
	
</script>