<?
/*ini_set('display_errors', 1);
error_reporting(E_ALL);*/

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}

$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
require_once($_SERVER["DOCUMENT_ROOT"] . "/catalogue/filter.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/catalogue/sort.php");

$sections = $_SERVER['REQUEST_URI'];
$sections = explode('?', $sections);
$sections = $sections[0];
$sections = explode('/', $sections);
$sections = array_diff($sections,array(''));

if(!empty($sections)) $sections = explode("|", implode("|", $sections));


global $main_section;
global $APPLICATION;
$main_section = 'interernyj-dekor';//для подсветки меню

$section_first = $sections[count($sections)-1];

if($section_first == 'klei-90') LocalRedirect('/adhesive');

if ($section_first == 'catalogue') {$sections[0] = 'interernyj-dekor'; $sections[1] = 'karnizy';}
if ($section_first == 'interernyj-dekor') {$sections[1] = 'karnizy';}
if ($section_first == 'fasadnyj-dekor') {$sections[1] = 'antablementy'; $sections[2] = 'karnizi';}

for ($i = count($sections)-1; $i >= 0; $i--) {
    if($i == count($sections)-1) {
        $sec_arr = explode('_', $sections[$i]);
    } else {
        $sec_arr = Array($sections[$i]);
    }

    /*if (!empty($_GET['test'])) {
        echo '<pre>';
        print_r($sec_arr);
        echo '</pre>';
    }*/

    //$arFilter = Array('IBLOCK_ID'=>IB_CATALOGUE, 'CODE'=>$sec_arr, 'ACTIVE'=>'Y');
    $arFilter = Array('IBLOCK_ID'=>IB_CATALOGUE, 'CODE'=>$sec_arr);
    $db_list = CIBlockSection::GetList(Array(), $arFilter, false, array('UF_*'));
    $last_section = Array();
    if (intval($db_list->SelectedRowsCount())>0) {
        //если раздел
        $section_id = Array();
        $sec_info = Array();
        $sec_sort = Array();
        while($section_item = $db_list->GetNext()) {

            /*if ($section_item['ACTIVE'] <> 'Y') {
                $APPLICATION->SetPageProperty("robots", "noindex, nofollow");
            }*/

            if(empty($last_section)) $last_section = $section_item;
            if ($section_item['UF_HIDECATALOG'] == 1 || $section_item['ACTIVE'] == 'N') {
                require_once($_SERVER["DOCUMENT_ROOT"] . "/404.php"); exit;
            }
            //закрываем композиты если на каталог попытка перехода
            /*if($section_item['IBLOCK_SECTION_ID'] == 1614 || $section_item['ID'] == 1614) {
                $user_id = $USER->GetID();
                $user_group_arr = [];
                $res = CUser::GetUserGroupList($user_id);
                while ($arGroup = $res->Fetch()) {
                    $user_group_arr[] = $arGroup['GROUP_ID'];
                }
                if(!$USER->IsAuthorized() || in_array(5,$user_group_arr)) {
                    require_once($_SERVER["DOCUMENT_ROOT"] . "/404.php"); exit;
                }
            }*/
            $section_id[] = $section_item['ID'];

               //print_r($section_item);
            $sec_info[$section_item['ID']] = Array('name'=>$section_item['NAME']);
       }
       break;
    } else {
        //проверим, может товар
        if (count($sections)-1 == $i) {
            //$arFilter = Array('IBLOCK_ID'=>IB_CATALOGUE, 'CODE'=>$sec_arr, 'ACTIVE'=>'Y');
            $arFilter = Array('IBLOCK_ID'=>IB_CATALOGUE, 'CODE'=>$sec_arr);
            $db_list = CIBlockElement::GetList(Array('NAME'=>'ASC'), $arFilter);
            $product_item = $db_list->GetNextElement();
            if (!$product_item) {
                require_once($_SERVER["DOCUMENT_ROOT"] . "/404.php"); exit;
            }

            $is_product = array_merge($product_item->GetFields(), $product_item->GetProperties());
            $section_id = Array($is_product['IBLOCK_SECTION_ID']);

            if ($is_product['ACTIVE'] == 'N') {
                $APPLICATION->SetPageProperty("robots", "noindex, nofollow");
            }

            // Вырезаем композит у товара даже при кривом линке
            /*if($is_product['IBLOCK_SECTION_ID'] == 1614 || $is_product['IBLOCK_SECTION_ID'] == 1615 || $is_product['IBLOCK_SECTION_ID'] == 1616 || $is_product['IBLOCK_SECTION_ID'] == 1617) {
                $user_id = $USER->GetID();
                $user_group_arr = [];
                $res = CUser::GetUserGroupList($user_id);
                while ($arGroup = $res->Fetch()) {
                    $user_group_arr[] = $arGroup['GROUP_ID'];
                }
                if(!$USER->IsAuthorized() || in_array(5,$user_group_arr)) {
                    require_once($_SERVER["DOCUMENT_ROOT"] . "/404.php"); exit;
                }
            }*/
            break;
        } else {
            require_once($_SERVER["DOCUMENT_ROOT"] . "/404.php"); exit;
        }
    }

}
$sections_list = CIBlockSection::GetNavChain(IB_CATALOGUE, $section_id[0], array(), true);
foreach ($sections_list as $i=>$section_item) {
    if ($i == 0) {
        $main_section = $section_item['CODE'];
        $mother_section = $section_item['NAME'];
        break;
    }
}

/*if (!empty($_GET['test'])) {
    echo $main_section;
}*/

$breadcrumbs_arr = get_breadcrumbs_arr($sections_list);

if (!$is_product) { // каталог

    $data_onpage = 12;
    //$data_onpage = 100;

    $filter = $_GET['filter'];
    $classes = $_GET['classes'];
    $styles = $_GET['styles'];

    require($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");
    global $my_city;

    //сортировка
    $wtf = getSort($section_id);
    $sort = renderSort($section_id);

    // стартовый фильтр
    $CATALOG_FILTER_FULL = CatalogFullFilter($section_id, $filter, $classes, $styles);

    //для фильтров
    $db_list_full = CIBlockElement::GetList($wtf, $CATALOG_FILTER_FULL, false);
    while($ob = $db_list_full->GetNextElement()) {
        $product = array_merge($ob->GetFields(), $ob->GetProperties());
        $product_items_full[] = $product;
    }
   ?>
<div class="content-wrapper catalogue">
    <? require_once($_SERVER["DOCUMENT_ROOT"] . "/include/breadcrumbs.php"); ?>
    <div class="cat-top">
        <div class="cat-sections">
            <a href="/karnizy/" class="cat-sections-item<?if($main_section == 'interernyj-dekor') echo ' active'?>">
                Интерьерная лепнина
            </a>
            <? /*
            <a href="/composite/" class="cat-sections-item<?if($main_section == 'composite') echo ' active'?>">
                Композиты
            </a>
            */ ?>
            <a href="/adhesive/" class="cat-sections-item">
                Клей
            </a>
            <a href="/collection/new_art_deco/" class="cat-sections-item collection">
                <p>новая коллекция <span>NEW&nbsp;ART&nbsp;DECO</span></p> <i class="icon-angle-right"></i>
            </a>
        </div>
        <div class="cat-mob-panel">
            <div class="cat-filters-mob-wrap">
                <div class="cat-filters-mob" data-type="show-filters-mob"><i class="icon-filters"></i>фильтры</div>
                <div class="cat-filters-mob" data-type="show-category-mob"><i class="icon-category"></i>категории</div>
            </div>
            <div class="mob-preview-type">
                <div class="mob-preview-type-item" data-type="show-prev" data-val="1"></div>
                <div class="mob-preview-type-item active" data-type="show-prev" data-val="4"></div>
            </div>
        </div>

    </div>

    <div class="cat-wrap">
        <div class="cat-filters">
            <i class="icon-close close-filt-mob" data-type="close-filt-mob"></i>
            <? /* <a href="/collection/new_art_deco/" class="filt-collection-link">новая коллекция <span>NEW&nbsp;ART&nbsp;DECO</span> <i class="icon-angle-right"></i></a> */ ?>

            <? if ($main_section == 'composite') { ?>
                <? /*
                <div class="cat-filt-item mob-filt-wrap active cat-filt-item-category" data-type="filt-item">
                    <div class="cat-filt-item-title mob-filters-title" data-type="filt-title">Категории <i class="icon-angle-down-2"></i></div>
                    <div class="cat-filt-item-cont mob-filt-cont category" data-type="filt-cont">
                        <?= build_drop_categories('composite') ?>
                    </div>
                </div>
                */ ?>
            <? } else { ?>
                <div class="cat-filt-item mob-filt-wrap active cat-filt-item-category" data-type="filt-item">
                    <div class="cat-filt-item-title mob-filters-title" data-type="filt-title">Элементы лепнины <i class="icon-angle-down-2"></i></div>
                    <div class="cat-filt-item-cont mob-filt-cont category" data-type="filt-cont">
                        <? if ($main_section == 'interernyj-dekor') {
                            echo build_drop_categories('interernyj-dekor',true);
                        } else {
                            echo build_drop_categories('fasadnyj-dekor');
                        }
                        ?>
                    </div>
                </div>
            <? } ?>

            <?=renderFilters($section_id,$product_items_full,$filter,$classes,$styles)?>



            <div class="cat-sort-mob mob-filt-wrap" data-type="filt-item">
                <div class="cat-sort-title mob-filters-title" data-type="filt-title">Сортировать по <i class="icon-angle-down-2"></i></div>
                <div class="mob-filt-cont" data-type="filt-cont">
                    <div data-type="e-sort" data-main-param="1">
                        <?=$sort;?>
                    </div>
                </div>
            </div>

        </div>
        <div class="cat-products">

            <? if (!empty($last_section['NAME']) && in_array($last_section['ID'], ['1622', '1589', '1590', '1624', '1548', '1575', '1549', '1552', '1623', '1550'])) { ?>
                <h1 class="hidden"><?= mb_ucfirst($last_section['NAME']) ?></h1>
            <? } ?>

            <div class="cat-sort">
                <div class="cat-sort-title" data-type="e-sort" data-main-param="1">Сортировать по</div>
                <div class="cat-sort-items">
                    <?=$sort ?>
                </div>
            </div>
            <?
            $sec_id_row = '';
            if(is_array($section_id)) {
                foreach($section_id as $sec) {
                    $sec_id_row .= $sec.',';
                }
                $sec_id_row = mb_substr($sec_id_row,0,-1);
            } else {
                $sec_id_row = $section_id;
            }

            ?>
            <div class="cat-items" data-type="items-list" data-id="<?=$sec_id_row?>" data-val="catalogue">
                <?/* см. /ajax/catalogue.php */?>
                <img src="/img/preloader.gif" alt="wait...">
            </div>
            <div class="pagination"<?if(!$item_count || $item_count<=12) echo ' style="display:none"'?>>
                <div class="pag-title">Страницы</div>
                <div class="pag-wrap"<?if($page == 'all') echo ' style="display:none"'?>>
                    <div class="pag" data-items="<?=$item_count?>" data-onpage="12" data-type="pag" data-current="<?=$page?>"></div>
                    <div class="show-all-btn" data-type="show-all">Показать все</div>
                    <div class="show-wait">
                        <img src="/img/preloader.gif" alt="loading">
                    </div>
                </div>
                <div class="show-per-page<?if($page == 'all') echo ' active'?>">
                    <div class="show-per-page-txt">
                        Показаны все товары
                    </div>
                    <div class="show-per-page-btn" data-type="per-page">
                        Показать постранично
                    </div>
                </div>
            </div>

                <? 
                $section_id = (int)$section_id;
                /*if (!empty($_GET['test'])) {
                    print_r($section_id);
                }*/
                $section_ids = array('1601', '1542', '1544', '1546');
                $section_text_show = false;
                if (in_array($section_id, $section_ids)) $section_text_show = true;
                if($last_section['~DESCRIPTION'] && $section_text_show) { ?>
                    <div class="last-sec-desc" data-type="last-sec-desc">
                        <? echo $last_section['~DESCRIPTION']; ?>
                    </div>
                <? } ?>

        </div>
    </div>
</div>

<? } else { // Продукт

    require($_SERVER["DOCUMENT_ROOT"]."/catalogue/product.php");

} ?>

<script src="/catalogue/catalogue.js?<?=$random?>"></script>
<? require($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");
if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog.php");
}