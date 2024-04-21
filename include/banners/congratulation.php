

<?if($loc['ID'] == 3109) {?>
<div class="congr-wrap">
    <i class="icon-close" data-type="congr-close"></i>
    <div class="congr-main">Европласт поздравляет <br>с&nbsp;Рождеством и&nbsp;Новым&nbsp;годом!</div>

    <div class="congr-mess">
        <p>Последний рабочий день: 31.12.2023&nbsp;с&nbsp;10.00&nbsp;до&nbsp;15.00.</p>
        <p>Первый рабочий день: 03.01.2024&nbsp;с&nbsp;10.00&nbsp;до&nbsp;20.00.</p>
        <p>Все&nbsp;заказы, оформленные позднее 12.00&nbsp;29.12.2023, <br>мы&nbsp;сможем доставить не&nbsp;ранее 10.01.2024.</p>
        <p>С&nbsp;03.01.2024&nbsp;по&nbsp;09.01.2024 возможен самовывоз <br>из&nbsp;наших розничных точек.</p>
        <p>Желаем отличного настроения и&nbsp;удачного преображения <br>интерьера в&nbsp;наступающем году!</p>
    </div>
    <?/* } else { ?>
        <div class="congr-mess">
            <p>Последний рабочий день: 29.12.2023.</p>
            <p>Первый рабочий день: 09.01.2024.</p>
            <p>Желаем отличного настроения и&nbsp;удачного преображения <br>интерьера в&nbsp;наступающем году!</p>

        </div>
    <? } */?>
</div>
<? } ?>

<script>

  function showBanner() {
    if($('.congr-wrap').length > 0 && $('#top_fix_region').length == 0) {
      setTimeout(function() {
        $('.congr-wrap').fadeIn();
        $('[data-type="overlay"]').fadeIn();
      }, 1000);
    }
    else if($('.congr-wrap').length > 0 && $('#top_fix_region').length > 0) {
      $('.top_fix_button_ok').click(function() {
        setTimeout(function() {
          $('.congr-wrap').fadeIn();
          $('[data-type="overlay"]').fadeIn();
        }, 1000);
      })
    }
  }

  $('document').ready(function(){
    let congr = $.cookie('congr');
    //congr = 0;
    if(congr != 1) {
        showBanner();
    }
    $('[data-type="congr-close"]').click(function(){
      $('.congr-wrap').fadeOut();
      $('[data-type="overlay"]').fadeOut();
      $.cookie('congr', '1', {
        expires: 1,
        domain: domain,
        path: '/',
      });
    });
    $(document).mouseup(function (e) {
      var container = $('.congr-wrap');
      var target = e.target;
      if (container.css('display')!='none' && container.has(e.target).length === 0 && !$(target).is('.congr-wrap')){
        $('.congr-wrap').fadeOut();
        $('[data-type="overlay"]').fadeOut();
       $.cookie('congr', '1', {
          expires: 1,
          domain: domain,
          path: '/',
        });
        return false;
      }
    });
  })

</script>