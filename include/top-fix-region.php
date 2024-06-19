<?
global $my_city;
global $my_city_fix;

$getReg = $_GET['region'];

//$my_city_fixed = $APPLICATION->get_cookie('my_city_fixed');
$my_city_fixed = $_SESSION['my_city_fixed'];

$show_window = true;
if ($_SERVER['HTTP_HOST'] <> HTTP_HOST) {
    $show_window = false;
}

if (($my_city_fix || $getReg == 'select' || empty($my_city_fixed)) && empty($_GET['sub_city']) && $show_window) {

    $arFilter = Array('IBLOCK_ID' => 7, 'ACTIVE' => 'Y', 'ID' => $my_city);
    $db_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter);
    $loc = $db_list->GetNextElement();
    if (!$loc) {
        $arFilter = Array('IBLOCK_ID' => 7, 'ACTIVE' => 'Y', 'CODE' => 'moskva');
        $db_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter);
        $loc = $db_list->GetNextElement();
    }
    $loc = array_merge($loc->GetFields(), $loc->GetProperties());

    $subdomen = _get_city_loc($my_city);
    if (empty($subdomen)) $subdomen = HTTP_HOST;
    ?>

    <!--noindex-->
    <div id="top_fix_region" data-my_city_fix="<?= $my_city_fix ?>" data-my_city_fixed="<?= $my_city_fixed ?>" data-sub_city="<?= $_GET['sub_city'] ?>">
        <div class="top_fix_title">
            Ваш&nbsp;регион <span class="top_fix_title_city"><?=trim($loc['NAME'])?></span>?
        </div>
        <div class="top_fix_buttons">
            <? if (1 == 1) { ?>
                <button class="top_fix_button_ok top_fix_button_ok_new" data-subdomain="<?= $subdomen ?>" data-host="<?= $_SERVER['HTTP_HOST'] ?>">
                    <a href="https://<?=$subdomen.strtok($_SERVER['REQUEST_URI'], '?')?>?sub_city=<?=$my_city?>" style="color: white;">Правильно</a>
                </button>
            <? } else { ?>
                <button class="top_fix_button_ok">Правильно</button>
            <? } ?>

            <button class="top_fix_button_change">Выбрать город из&nbsp;списка</button>
        </div>
		
        <script type="text/javascript">

            $(function () {
                $('#top_fix_region .top_fix_button_ok').click(function () {
                    $('#top_fix_region').fadeOut();

                    <? if ($subdomen == $_SERVER['HTTP_HOST']) { ?>
                        $.ajax({
                            type: "POST",
                            url: "/ajax/fixedregion.php",
                            data: {},
                            success: function(resp){
                                console.log(resp);
                            }
                        });
                        return false;                
                    <? } ?>
                });

                $('#top_fix_region .top_fix_button_change').click(function () {
                    $('#top_fix_region').fadeOut();
                    if($('[data-type="geo-open"]').length > 0) {
                        $('[data-type="geo-open"]').css('z-index','10');
                        $('[data-type="geo-open"]').removeClass('icon-geo');
                        $('[data-type="geo-open"]').addClass('icon-close');
                        $('[data-type="geo-open"]').attr("data-type","geo-close")
                    } else {
                        let btn = '<a class="header-geo" title="Закрыть"><i data-type="geo-open" class="icomoon icon-close" style="z-index: 10;"></i></a>';
                        $('[data-type="personal-data"]').after(btn);
                        $('header').on('click','[data-type="geo-open"]',function() {
                            $('header').find('.header-geo').remove()
                            $('[data-type="reg-list"]').slideUp();
                            return false;
                        })
                    }
                    $('[data-type="reg-list"]').slideDown();
                    //console.log(typeof regScroll);
                    if(typeof regScroll === 'undefined') {
                        let regScroll = $('[data-type="reg-list-scroll"]').jScrollPane({
                            showArrows: false,
                            maintainPosition: false
                        }).data('jsp');
                    } else {
                        regScroll.reinitialise();
                    }
                    $('body').addClass('disabled');
                    return false;
                });


            });
        </script>

    </div>
    <!--/noindex-->
<?}?>