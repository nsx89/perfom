<style>
    @import url('https://fonts.googleapis.com/css?family=Caveat:400,700&subset=cyrillic');
    /*font-family: 'Caveat', cursive;*/
    .bnr_reg-wrap {
        position: fixed;
        border: 1px solid #4e4e4e;
        background-color: rgba(255,255,255,1);
        z-index: 100;
        width: 564px;
        height: 261px;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        bottom: calc(100vh - 405px);
        right: 50%;
        margin-right: -282px;
        text-align: center;
        font-size: 14px;
        color: #040604;
        font-family: 'Open Sans';
        display: none;
        background: #fff;
        background-size: cover;
    }
    .bnr_reg-wrap .icon-close {
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
    .bnr_reg-wrap .icon-close:hover {
        color: #4e4e4e;
    }
    .bnr_reg-wrap .icon-logo {
        color: #fe5000;
        font-size: 25px;
    }
    .bnr_reg-main {
        font-family: 'Caveat', cursive;
        color: #fe5000;
        font-size: 24px;
        padding-top: 15px;
        padding-bottom: 25px;
        position: relative;
        margin-bottom: 5px;
    }
    .bnr_reg-main:after {
        content: '';
        height: 1px;
        width: 122px;
        background-color: #fe5000;
        bottom: 0;
        position: absolute;
        left: 50%;
        margin-left: -61px;
    }
    .bnr_reg-mess {
        margin: 0;
    }
    .bnr_reg-mess-bold {
        font-weight: 600;
    }
    .bnr_reg-mess-footer {
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
    .bnr_reg-mess a {
        color: #fe5000;
        border-bottom: 1px solid #fe5000;
    }
    .bnr_reg-mess a:hover {
        color: #fe5000;
    }
    .bnr-reg {
        position: fixed;
        bottom: 120px;
        right: 0;
        font-size: 14px;
        font-weight: 600;
        color: #fff;
        background-color: #fe5000;
        width: 100px;
        height: 30px;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        cursor: pointer;
        line-height: 30px;
        text-align: center;
        -webkit-transition: .2s;
        -moz-transition: .2s;
        -ms-transition: .2s;
        -o-transition: .2s;
        transition: .2s;
        border: 1px solid #fe5000;
        display: none;
        z-index: 1;
    }
    .bnr-reg:hover {
        color: #fe5000;
        background: #fff;
    }
</style>

<?
$d_day = date('z');
$add_n = $d_day % 2 == 0 ? '101' : '201';
//$add_n = '201';
?>
<div class="bnr_reg-wrap">
    <i class="new-icomoon icon-close" data-type="bnr_reg-close"></i>
    <div class="bnr_reg-mess" style="margin-bottom: 25px;margin-top:50px">Уважаемые клиенты! </div>
    <div class="bnr_reg-mess" style="margin-bottom: 15px;">Если вам не удалось связаться <br>с нашим представителем в вашем регионе, просьба <br>перезвонить по номеру <span style="font-weight:600;text-decoration:none;color:#4e4e4e;">8 (495) 315-30-40 доб. <?=$add_n?></span> <br>или написать нам в <a data-type="bnr-popup-open">тех. поддержку</a> на сайте.</div>
    <div class="bnr_reg-mess">Приносим свои извинения.</div>
</div>

<div class="bnr-reg" data-type="show-bnr-reg">Внимание!</div>

<script>

    function showBanner() {
        if($('.bnr_reg-wrap').length > 0 && $('#top_fix_region').length == 0) {
            setTimeout(function() {
                $('.bnr_reg-wrap').fadeIn();
                $('[data-type="overlay"]').fadeIn();
            }, 1000);
        }
        else if($('.bnr_reg-wrap').length > 0 && $('#top_fix_region').length > 0) {
            $('.top_fix_button_ok').click(function() {
                setTimeout(function() {
                    $('.bnr_reg-wrap').fadeIn();
                    $('[data-type="overlay"]').fadeIn();
                }, 1000);
            })
        }
    }

    function clickBnrRegBtn() {
        $('[data-type="show-bnr-reg"]').hide();
        var bnrBottom = parseInt(window.innerHeight) - 405;

        $('.bnr_reg-wrap').css({
            width: "0px",
            height: "0px",
            right: 0,
            bottom: "120px",
            marginRight: 0,
            display: "block"
        })
        $('.bnr_reg-wrap').animate({
            width: "564px",
            height: "261px",
            right: "50%",
            bottom: bnrBottom+"px",
            marginRight: "-282px"
        },300,'swing',function(){

        })
        $('[data-type="overlay"]').fadeIn();
    }

    function hideBanner() {
        $('.bnr_reg-wrap').animate({
            width: "0px",
            height: "0px",
            right: 0,
            bottom: "120px",
            marginRight: 0
        },300,'swing',function(){
            $(this).hide();
            $('[data-type="show-bnr-reg"]').show();
        })
        $('[data-type="overlay"]').fadeOut();
    }

    $('document').ready(function(){

        $('[data-type="bnr_reg-close"]').click(function(){
            hideBanner();
            var new_minut = new Date();
            new_minut.setMinutes( 60 + new_minut.getMinutes());
            if($.cookie('bnr_reg') != 1) {
                $.cookie('bnr_reg', '1', {
                    expires: new_minut,
                    domain: domain,
                    path: '/',
                });
            }
        });
        $(document).mouseup(function (e) {
            var container = $('.bnr_reg-wrap');
            var target = e.target;
            if (container.css('display')!='none' && container.has(e.target).length === 0 && !$(target).is('.bnr_reg-wrap')){
                hideBanner();
                $('[data-type="overlay"]').fadeOut();
                var new_minut = new Date();
                new_minut.setMinutes( 60 + new_minut.getMinutes());
                if($.cookie('bnr_reg') != 1) {
                    $.cookie('bnr_reg', '1', {
                        expires: new_minut,
                        domain: domain,
                        path: '/',
                    });
                }
                return false;
            }
        });

        $('[data-type="show-bnr-reg"]').click(function() {
            clickBnrRegBtn();
        })
        $('[data-type="bnr-popup-open"]').click(function() {
            $('.bnr_reg-wrap').hide();
            $('[data-type="show-bnr-reg"]').show();
            $('[data-type="q-popup"]').fadeIn();
        })


        var bnr_reg = $.cookie('bnr_reg');
        //bnr_reg = 0;
        if(bnr_reg != 1) {
            showBanner();
        } else {
            $('[data-type="show-bnr-reg"]').show();
        }

    })

</script>