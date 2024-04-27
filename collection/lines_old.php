<?
    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
    if (!CModule::IncludeModule('iblock')) exit;
    $APPLICATION->SetTitle("LINES");
    $APPLICATION->SetPageProperty("description", "Европласт - производство полиуретановых изделий, лидер на российском рынке");

    $prod_arr = array();
    $arFilter = Array("IBLOCK_ID"=>IB_CATALOGUE,"PROPERTY_LINES"=>"Y","ACTIVE"=>"Y");
    $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), Array());
    $coll_qty = $res->SelectedRowsCount();
    while($ob = $res->GetNextElement()){
        $arFields =  array_merge($ob->GetFields(), $ob->GetProperties());
        //print_r($arFields);
        $db_groups = CIBlockElement::GetElementGroups($arFields['ID']);
        while($ar_group = $db_groups->Fetch()) {
            $group = $ar_group;
        }
        $prod_arr[$group['NAME']]['info'] = $group;
        $prod_arr[$group['NAME']]['prod'][] = $arFields;
    }
    //print_r($prod_arr);

require($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");
?>

<div class="main-slider-wrap">
    <!--noindex--><div class="main-slider-preloader"><img src="/img/preloader.gif" alt="Подождите..."></div><!--/noindex-->
    <div class="main-slider collection-slider" data-type="main-slider">
        <div class="main-slide">
            <div class="main-slide-caption white">lines</div>
            <img class="img-load" src="/img/1.png" data-src="/collection/img/slider/lines/3.jpg" alt="lines">
        </div>
        <div class="main-slide">
            <div class="main-slide-caption white">lines</div>
            <img class="img-load" src="/img/1.png" data-src="/collection/img/slider/lines/2.jpg" alt="lines">
        </div>
        <div class="main-slide">
            <div class="main-slide-caption white">lines</div>
            <img class="img-load" src="/img/1.png" data-src="/collection/img/slider/lines/1.jpg" alt="lines">
        </div>
    </div>
</div>

<section class="collection-wrap">
    <div class="content-wrapper">
        <?if($coll_qty > 0) { ?>
        <div class="col-prod-nav" data-type="col-prod-nav">
            <?foreach($prod_arr as $item) { ?>
                <a class="col-prod-nav-item" href="#<?=$item['info']['CODE']?>" data-type="nav"><?=$item['info']['NAME']?></a>
            <? } ?>
        </div>
        <div class="col-prod-tabs-wrap">
            <?foreach($prod_arr as $item) { ?>
                <div class="col-prod-tab" id="#<?=$item['info']['CODE']?>" data-type="tab">
                    <div>
                        <?foreach($item['prod'] as $prod) {
                            echo get_product_preview($prod,false,false,false);
                        } ?>
                    </div>
                </div>
            <? } ?>
        </div>
        <? } else {?>
            <div class="empty-collection">Не найдено товаров, относящихся к&nbsp;этой&nbsp;коллекции.</div>
        <? } ?>
    </div>
</section>

<section class="catalogue-collection">
    <div class="content-wrapper">
        <h2 class="main-blocks-title">Сделайте интерьер&nbsp;лучше</h2>
        <div class="first-block">
            <?
            $dir = $_SERVER["DOCUMENT_ROOT"].'/collection/img/lines/';
            $path = '/collection/img/lines/';
            if(is_dir($dir) && file_exists($dir)) {
            $images = scandir($dir);
            $images = preg_grep('~\.(jpeg|jpg|png)$~', $images);
            $images = (array_values($images));
            for($i=0; $i < count($images); $i++) {
            $image = $path.$images[$i];
            $coll_class = 'coll-img-h';
            $img_info = getimagesize($dir.$images[$i]);
            if($img_info[0] < $img_info[1]) $coll_class = 'coll-img-v';
            ?>
            <?/*if($i == 9) {?>
        </div>
        <div class="second-block">
            <? } */?>
            <div class="collection-add-img">
                <a href="<?=$path.$images[$i]?>" data-fancybox="slider" class="cover-link"></a>
                <img src="/img/1x1.png" data-src="<?=$path.$images[$i]?>" alt="lines" class="img-load <?=$coll_class?>">
            </div>
            <? } ?>
            <? } ?>
        </div>
    </div>
</section>

<script defer src="/collection/collection.js"></script>
<?
require($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");
if(defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED===true)
{
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog.php");
}
