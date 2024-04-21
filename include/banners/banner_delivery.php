<style>
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

    .bnr-mess {
        margin: 0;
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
    <div class="bnr-mess" style="margin-bottom: 25px;">Уважаемые покупатели! </div>
    <div class="bnr-mess" style="margin-bottom: 15px;">В период с 28.10.2021 по 07.11.2021 наш интернет магазин <br>
        будет принимать заказы только на доставку.</div>
    <div class="bnr-mess" style="margin-bottom: 45px;">Подробнее в разделе <a href="/online-store/#etc3" data-type="banner-link">ДОСТАВКА</a></div>
    <div class="bnr-mess">Приносим свои извинения.</div>
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
            $.cookie('bnr', '1', {
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
                $.cookie('bnr', '1', {
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
                $.cookie('bnr', '1', {
                    expires: 1,
                    domain: domain,
                    path: '/',
                });
            }
        });

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

        var bnr = $.cookie('bnr');
        //bnr = 0;
        if(bnr != 1)
            showBanner();
    })



</script>