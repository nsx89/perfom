<?
/*ini_set('display_errors', 1);
error_reporting(E_ALL);*/

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}
$APPLICATION->SetTitle("Купить клей Европласт");
$APPLICATION->SetPageProperty("description", "Купить клей Европласт");
$glue_arr = Array(6429,158448);
require($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");?>

<div class="content-wrapper catalogue">
    <div class="cat-top">
        <div class="cat-sections">
            <a href="/karnizy/" class="cat-sections-item">
                Интерьерная лепнина
            </a>
            <a href="/antablementy/karnizi/" class="cat-sections-item">
                Фасадная лепнина
            </a>
            <a href="/adhesive/" class="cat-sections-item active">
                Клей
            </a>
        </div>
    </div>
    <section class="adh-wrap full-screen">
        <?
        $arOrder = Array("SORT"=>"ASC");
        $arFilter = Array("IBLOCK_ID"=>IB_CATALOGUE,"SECTION_CODE"=>"klei-90","ACTIVE"=>"Y");
        $arNavStartParams = Array();
        $arSelect = Array();
        $ar_res = CIBlockElement::GetList($arOrder,$arFilter,false,$arNavStartParams,$arSelect);
        $prod_arr = Array();
        $prod_article_arr = Array();
        while ( $ob = $ar_res->GetNextElement() ) {
            $product = array_merge($ob->GetFields(), $ob->GetProperties());
            if(in_array($product['ID'],$glue_arr)) {
                echo get_product_preview($product);
            }
        } ?>
    </section>
</div>


<? require($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");
if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog.php");
}
