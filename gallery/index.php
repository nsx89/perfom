<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock')) exit;
$APPLICATION->SetTitle("Галерея объетов");
$APPLICATION->SetPageProperty("description", "Европласт - производство полиуретановых изделий, лидер на российском рынке");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}
require($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");
$quant_obj = 8;
$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
$filter = $_REQUEST['filter'];
$filter = explode(',',$filter);

$breadcrumbs_arr = Array(
    Array(
        'name' => 'проекты',
        'link' => '/gallery/',
        'title' => 'проекты',
    ),
);
?>

<div class="main-slider-wrap">
    <!--noindex-->
    <div class="main-slider-preloader"><img src="/img/preloader.gif" alt="Подождите..."></div>
    <!--/noindex-->
    <div class="main-slider" data-type="main-slider">
        <div class="main-slide">
            <div class="main-slide-caption">подсветка там, <br>где&nbsp;нужно</div>
            <img src="/img/gallery/slider/1.jpg" alt="подсветка там, где нужно">
        </div>
        <div class="main-slide">
            <div class="main-slide-caption">яркие акценты <br>для&nbsp;интерьера</div>
            <img src="/img/gallery/slider/2.jpg" alt="яркие акценты для интерьера">
        </div>
        <div class="main-slide">
            <div class="main-slide-caption">разные стили, <br>один декор</div>
            <img src="/img/gallery/slider/3.jpg" alt="разные стили, один декор">
        </div>
    </div>
</div>

<section class="gallery-section">
    <div class="content-wrapper">
        <? require_once($_SERVER["DOCUMENT_ROOT"] . "/include/breadcrumbs.php"); ?>
        <?
        $arOrder = Array('PROPERTY_DATE'=>'desc');
        $arFilter = Array("IBLOCK_CODE"=>"gallery_articles","ACTIVE"=>"Y");
        if($_REQUEST['filter'] != '' && !in_array('1',$filter)) {
            $ar_res = CIBlockElement::GetList($arOrder,$arFilter,false,Array(),Array());
            $all_item_count = $ar_res->SelectedRowsCount();
            $arFilter['PROPERTY_TAGS'] = $filter;
        }
        $arNavStartParams = Array("nPageSize"=>$quant_obj);
        $arSelect = Array();
        $ar_res = CIBlockElement::GetList($arOrder,$arFilter,false,$arNavStartParams,$arSelect);
        $item_count = $ar_res->SelectedRowsCount();
        if($_REQUEST['filter'] == '' || in_array('1',$filter)) $all_item_count = $item_count;
        ?>

        <h1>Реализованные проекты</h1>
        <div class="gallery-tabs">
            <ul data-type="gallery-tab-slider">
                <li <?if ($_REQUEST['filter'] == '' || in_array('1',$filter)) echo 'class="active"'?> data-type="g-filter" data-val="1" data-count="<?=$all_item_count?>">
                    <a>Все</a>
                </li>
                <?
                $resc = CIBlock::GetList(Array(), Array('CODE' => 'gallery_articles'));
                while($arrc = $resc->Fetch())
                {
                    $blockid = $arrc["ID"];
                }
                $property_enums = CIBlockPropertyEnum::GetList(Array("SORT"=>"ASC"), Array("IBLOCK_ID"=>$blockid));

                while($enum_fields = $property_enums->GetNext()) {?>
                    <?
                    $arFilterTag = Array("IBLOCK_CODE"=>"gallery_articles","ACTIVE"=>"Y","PROPERTY_TAGS"=>$enum_fields['ID']);
                    $ar_res_tag = CIBlockElement::GetList(Array(),$arFilterTag,false,Array(),Array());
                    if($ar_res_tag->SelectedRowsCount() > 0) {
                        ?>
                        <li <?if (in_array($enum_fields['ID'],$filter)) echo 'class="active"'?> data-type="g-filter" data-val="<?=$enum_fields['ID']?>" data-count="<?=$ar_res_tag->SelectedRowsCount()?>">
                            <a><?=$enum_fields['VALUE']?></a>
                        </li>
                    <? } ?>
                <? } ?>
            </ul>
        </div>
        <div class="gallery-wrapper" data-type="items-list" data-val="gallery">
            <img src="/img/preloader.gif" alt="wait...">
        </div>
        <div class="pagination"<?if(!$item_count || $item_count<=8) echo ' style="display:none"'?>>
            <div class="pag-title">Страницы</div>
            <div class="pag-wrap"<?if($page == 'all') echo ' style="display:none"'?>>
                <div class="pag" data-items="<?=$item_count?>" data-onpage="<?=$quant_obj?>" data-type="pag" data-current="<?=$page?>" data-all="<?=$item_count?>"></div>
                <div class="show-all-btn" data-type="show-all">Показать все</div>
                <div class="show-wait">
                    <img src="/img/preloader.gif" alt="wait...">
                </div>
            </div>
            <div class="show-per-page<?if($page == 'all') echo ' active'?>">
                <div class="show-per-page-txt">
                    Показаны все проекты
                </div>
                <div class="show-per-page-btn" data-type="per-page">
                    Показать постранично
                </div>
            </div>
        </div>

    </div>
</section>




<script src="/gallery/gallery.js?<?=$random?>"></script>

<?
require($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");
if(defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED===true)
{
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog.php");
}