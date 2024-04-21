<? require($_SERVER["DOCUMENT_ROOT"] . "/include/title_ceo.php");?>
<? require($_SERVER["DOCUMENT_ROOT"] . "/include/start.php");?>
<? require($_SERVER["DOCUMENT_ROOT"] . "/include/top-current-location.php");?>
<? //require($_SERVER["DOCUMENT_ROOT"] . "/include/catalogue/drop-down-categories.php");?>
<?
$user_id = $USER->GetID();
$user_group_arr = [];
$res = CUser::GetUserGroupList($user_id);
while ($arGroup = $res->Fetch()) {
    $user_group_arr[] = $arGroup['GROUP_ID'];
}
$rsUser = CUser::GetByID($user_id);
$user = $rsUser->Fetch();
$favorite = json_decode($_COOKIE['favorite']);
if ($USER->IsAuthorized() && in_array(5,$user_group_arr)) {
    $favorite = json_decode($user['PERSONAL_NOTES']);
}
$cart = json_decode($_COOKIE['basket']);
$cart_qty = 0;
foreach($cart as $citem) {
    $cart_qty += $citem->qty;
}
$curr = getCurrency($loc['ID']);
//require($_SERVER["DOCUMENT_ROOT"] . "/include/banners/congratulation.php");
?>
<body>
<div class="wrapper">
    <div class="content">
<header>
    <div class="content-wrapper">
        <div class="top-header">
            <a href="/" class="header-logo"><span class="icon-logo-medium">перфом</span></a>
            <div class="top-header-central">
                <? if($phone) { ?>
                    <a href="tel:<?=$link_phone?>" class="header-phone"><?=$phone?></a>
                <? } ?>
                <? if($header_time && $header_time != '') { ?>
                    <div class="header-timetable"><?=$header_time?></div>
                <? } ?>
                <div class="header-qst-btn" data-type="q-popup-open"><i class="icon-question"></i> задать вопрос</div>
            </div>
            <div class="top-header-right">
                <div class="header-search-wrap">
                    <a class="header-search" title="Поиск" data-type="search-open">
                        <i class="icon-search"></i>
                    </a>
                    <form action="/search" data-type="search-form" class="search-form" style="/*display:none;*/">
                        <input data-type="search" type="text" placeholder="поиск" class="search-input" name="q" id="q">
                        <button type="reset" data-type="search-reset" class="search-reset"><i class="icon-close"></i></button>
                        <img src="/img/preloader.gif" alt="wait" class="search-wait">
                    </form>
                    <div class="search-mess" data-type="search-mess">по данному запросу нет&nbsp;результатов</div>
                </div>
                <a href="/favorite/" class="header-favorite<?if($favorite) echo ' not-empty'?>" title="Избранное">
                    <i class="icon-favorite"></i>
                    <span class="header-icon-qty"><?=count($favorite)?></span>
                </a>
                <a href="/cart/" class="header-cart<?if($cart_qty > 0) echo ' not-empty'?>" title="Корзина" data-reg="<?=$loc['ID']?>" data-curr="<?=$curr?>" data-type="header-cart">
                    <i class="icon-cart"></i>
                    <span class="header-icon-qty" data-type="header-cart-qty"><?=$cart_qty?></span>
                </a>
                <a href="/personal/" class="header-personal" title="Личный кабинет" data-type="personal-data" data-id="<?=$user_id?>">
                    <i class="icon-personal"></i>
                </a>
                <?  
                //$USER->IsAdmin() && !in_array(5,$user_group_arr) &&
                if (!empty($_GET['test'])) {  ?>
                <a title="Выбрать регион" class="header-geo">
                    <i class="icon-geo" data-type="geo-open"></i>
                </a>
                <? } ?>
                <div class="menu-btn" data-type="menu-btn">
                    <span class="menu-icon closed"></span>
                </div>
            </div>
        </div>
        <div class="bottom-header">
            <nav class="top-menu">
                <ul>
                    <? $active = $_SERVER['REQUEST_URI']; ?>
                    <li><a href="/karnizy/" class="header-catalogue" data-type="header-catalogue"><i class="icon-menu"></i>каталог</a></li>
					<li <? if(strpos($active, 'collection')) echo 'class="active"'?>><a href="/collection/">коллекции</a></li>
                    <li <? if(strpos($active, 'designer')) echo'class="active"'?>><a href="/designer/">дизайнерам и архитекторам</a></li>
                    <li <? if(strpos($active, 'professional')&&!strpos($tmp, 'question_service')) echo'class="active"'?>><a href="/professional/">строителям</a></li>
                    <li <? if(strpos($active, 'mag')||strpos($active, 'news')) echo 'class="active"'?>><a href="/mag/">медиацентр</a></li>
                    <li <? if(strpos($active, 'install')) echo 'class="active"'?>><a href="/install/">монтаж</a></li>
                    <li <? if(strpos($active, 'gallery')) echo 'class="active"'?>><a href="/gallery/">проекты</a></li>
                    <li <? if(strpos($active, 'download')) echo 'class="active"'?>><a href="/download/">загрузки</a></li>
                    <li <? if(strpos($active, 'factory')) echo 'class="active"'?>><a href="/factory/">производство</a></li>
                    <li <? if(strpos($active, 'wheretobuy')) echo 'class="active"'?>><a href="/wheretobuy/">где&nbsp;купить</a></li>
					<li <? if(strpos($active, 'contact')) echo'class="active"'?>><a href="/contact/">контакты</a></li>
                </ul>
            </nav>
            <div class="dropdown-menu" data-type="dropdown-menu">
                <div class="dropdown-menu-wrap">
                    <div class="dropdown-menu-column">
                        <a href="/karnizy/" class="dropdown-menu-main-link">Интерьерная лепнина</a>
                        <ul>
                            <li><a href="/karnizy/">карнизы</a></li>
                            <li><a href="/moldingi/">молдинги</a></li>
                            <li><a href="/plintusy/">плинтусы</a></li>
                            <li><a href="/rozetki/">розетки</a></li>
                            <li><a href="/dekorativnii-paneli/">декоративные панели</a></li>
                            <li><a href="/karnizy/?all=1" class="btn-small-link">показать все</a></li>
                        </ul>
                    </div>
                    <? /*
                    <div class="dropdown-menu-column">
                        <a href="/composite/" class="dropdown-menu-main-link">Композиты</a>
                        <ul>
                            <li><a href="/composite/cornices_composite/">карнизы</a></li>
                            <li><a href="/composite/mouldings_composite/">молдинги</a></li>
                            <li><a href="/composite/floor_mouldings_composite/">плинтусы</a></li>
                        </ul>
                    </div>
                    */ ?>
                    <? /*
                    <div class="dropdown-menu-column">
                        <a href="/antablementy/karnizi/" class="dropdown-menu-main-link">Фасадная лепнина</a>
                        <ul>
                            <li><a href="/nalichniki/">наличники</a></li>
                            <li><a href="/otkosy/">откосы</a></li>
                            <li><a href="/rusty/">рустовые камни</a></li>
                            <li><a href="/baljasiny/">балясины</a></li>
                            <li><a href="/podokonnye-jelementy/">подоконные элементы</a></li>
                        </ul>
                    </div>
                    */ ?>
                    <?
                    $menu_adh = array();
                    //print_r(get_glue_arr(true));
                    $arFilterMenuAdh = Array("IBLOCK_ID"=>12,"ACTIVE"=>"Y","ID"=>get_glue_arr(true));
                    $ar_res_menu_adh = CIBlockElement::GetList(Array(),$arFilterMenuAdh,false,Array(),Array('DETAIL_PAGE_URL','PROPERTY_ALTNAME'));
                    while ($ob_menu_adh = $ar_res_menu_adh->GetNextElement()) {
                        $menu_adh[] = $ob_menu_adh;
                    }
                    if(!empty($menu_adh)) {
                    ?>
                    <div class="dropdown-menu-column">
                        <a href="/adhesive/" class="dropdown-menu-main-link">Клей</a>
                        <ul>
                            <?foreach($menu_adh as $menu_adh_item) { ?>
                                <li><a href="<?=$menu_adh_item->fields['DETAIL_PAGE_URL']?>"><?=$menu_adh_item->fields['~PROPERTY_ALTNAME_VALUE']?></a></li>
                            <? } ?>
                        </ul>
                    </div>
                    <? } ?>
                    <div class="dropdown-menu-column">
                        <a href="/collection/" class="dropdown-menu-main-link">Коллекции</a>
                        <ul>
                            <li><a href="/collection/new_art_deco/">new art deco</a></li>
                            <? /*
                            <li><a href="/collection/mauritania/">mauritania</a></li>
                            <li><a href="/collection/lines/">lines</a></li>
                            <li><a href="/collection/2018/">modern</a></li>
                            */ ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="mobile-menu" data-type="mobile-menu">
            <div class="content-wrapper">
                <div class="top-mobile-menu">
                    <a href="/karnizy/" class="header-catalogue"><i class="icon-menu"></i>каталог</a>
                    <div class="header-qst-btn" data-type="q-popup-open"><i class="icon-question"></i> задать вопрос</div>
                </div>
                <div class="central-mobile-menu">
                    <div class="mobile-menu-main">
						<div class="mobile-menu-point"><a href="/collection/">коллекции</a></div>
                        <div class="mobile-menu-point"><a href="/designer/">дизайнерам <br>и&nbsp;архитекторам</a></div>
                        <div class="mobile-menu-point"><a href="/professional/">строителям</a></div>
						<div class="mobile-menu-point"><a href="/factory/">производство</a></div>                      
                    </div>
                    <div class="mobile-menu-add">
                        <div>
                            <div class="mobile-menu-point"><a href="/install/">монтаж</a></div>
                            <div class="mobile-menu-point"><a href="/download/">загрузки</a></div>
                            <? /* <div class="mobile-menu-point"><a href="/mag/">блог</a></div> */ ?>
                        </div>
                        <div>
                            <div class="mobile-menu-point"><a href="/gallery/">проекты</a></div>
                            <div class="mobile-menu-point"><a href="/wheretobuy/">где&nbsp;купить</a></div>
							<div class="mobile-menu-point"><a href="/contact/">контакты</a></div>
                        </div>
                    </div>
                </div>
                <div class="bottom-mobile">
                    <? if($phone) { ?>
                        <a href="tel:<?=$link_phone?>" class="header-phone"><?=$phone?></a>
                    <? } ?>
                    <? if($header_time && $header_time != '') { ?>
                        <div class="header-timetable"><?=$header_time?></div>
                    <? } ?>
                </div>
            </div>
        </div>
    </div>
    <? require($_SERVER["DOCUMENT_ROOT"] . "/include/region_list.php");?>
    <div class="search-res-wrap" data-type="search-wrap">
        <div class="content-wrapper">
            <form action="/search" data-type="search-form" class="search-form" style="/*display:none;*/">
                <input data-type="search" type="text" placeholder="поиск" class="search-input" name="q" id="q_second">
                <button type="reset" data-type="search-reset" class="search-reset"><i class="icon-close"></i></button>
                <img src="/img/preloader.gif" alt="wait" class="search-wait">
                <i class="icon-search"></i>
            </form>
            <div class="search-mess" data-type="search-mess">по данному запросу нет&nbsp;результатов</div>
            <div class="search-result" data-type="search-res"></div>
        </div>
    </div>
</header>
<? include_once($_SERVER["DOCUMENT_ROOT"] . "/smart_search/header_search.php");?>
<?
if (strpos($_SERVER['REQUEST_URI'],'cart') === false) {
    require($_SERVER["DOCUMENT_ROOT"] . "/include/top-fix-region.php");
}
?>