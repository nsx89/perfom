<?
    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
    if (!CModule::IncludeModule('iblock')) exit;
    $APPLICATION->SetTitle("LINES");
    $APPLICATION->SetPageProperty("description", "Европласт - производство полиуретановых изделий, лидер на российском рынке");

    $prod_arr = array();
    $arFilter = Array("IBLOCK_CODE"=>"collection","ACTIVE"=>"Y","ID"=>56131);
    $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), Array());
    while($ob = $res->GetNextElement()){
        $arFields =  array_merge($ob->GetFields(), $ob->GetProperties());
        $ar_res = CIBlockElement::GetList(Array(),Array("IBLOCK_ID"=>IB_CATALOGUE,"ACTIVE"=>"Y","ID"=>$arFields['PRODUCTS']['VALUE']),false,Array(),Array());
        $coll_qty = $ar_res->SelectedRowsCount();
    }

require($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");
?>

<div class="main-banner collection-banner">
    <div class="main-slide-caption white">Modern</div>
    <img class="img-load" src="/img/1.png" data-src="/collection/img/slider/2018/1.jpg" alt="Modern">
</div>
<section class="collection-txt">
    <div class="content-wrapper">
        <p>
            Современный дизайн интерьера расширяет границы привычного. <br>
            Так&nbsp;и&nbsp;мы меняем ваше представление о&nbsp;лепнине&nbsp;— встречайте <br>
            новые широкие карнизы и&nbsp;молдинги. Применяя их в&nbsp;оформлении <br>
            пространства, вы сможете создавать яркие акценты или полноценные <br>
            элементы обстановки&nbsp;— камин, дверное обрамление или&nbsp;потолочные&nbsp;балки.
        </p>
        <p>
            Широкие элементы архитектурного декора помогут создать многослойный <br>
            дизайн помещения. Применяйте их так, как&nbsp;вам захочется&nbsp;— например, <br> используйте молдинг в&nbsp;качестве плинтуса с&nbsp;подсветкой.
        </p>
        <p>
            С коллекцией MODERN "Европласт" в&nbsp;вашем интерьере больше не&nbsp;будет&nbsp;границ.
        </p>
    </div>
</section>
<section class="collection-wrap">
    <div class="content-wrapper">
        <?if($coll_qty > 0) { ?>
        <div class="col-prod-tabs-wrap">
            <div class="col-prod-tab active" data-type="tab">
                <div>
                    <? while ( $ob = $ar_res->GetNextElement() ) {
                    $prod = array_merge($ob->GetFields(), $ob->GetProperties());
                        echo get_product_preview($prod);
                    } ?>
                </div>
            </div>
        </div>
        <? } else {?>
            <div class="empty-collection">Не найдено товаров, относящихся к&nbsp;этой&nbsp;коллекции.</div>
        <? } ?>
    </div>
</section>

<script defer src="/collection/collection.js"></script>
<?
require($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");
if(defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED===true)
{
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog.php");
}
