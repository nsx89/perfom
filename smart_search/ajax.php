<?
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/smart_search/functions.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog") || !CModule::IncludeModule("search")) {
    exit;
}
global $APPLICATION;
if($type == 'get_list') {
    $my_city = $APPLICATION->get_cookie('my_city');

    //взято из top-current-location.php
    if (!isset($my_city) || !$my_city) { // Вот так не должно быть
        $arFilter = Array('IBLOCK_ID' => 7, 'ACTIVE' => 'Y', 'CODE' => 'moskva');
        $db_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter);
        $loc = $db_list->GetNextElement();
        $loc = array_merge($loc->GetFields(), $loc->GetProperties());
        $my_city = $loc['ID'];
    } else {
        $arFilter = Array('IBLOCK_ID' => 7, 'ACTIVE' => 'Y', 'ID' => $my_city);
        $db_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter);
        $loc = $db_list->GetNextElement();
        if (!$loc) { // Вариант в случае отключения региона и прописанной позиции пользователя
            $arFilter = Array('IBLOCK_ID' => 7, 'ACTIVE' => 'Y', 'CODE' => 'moskva');
            $db_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter);
            $loc = $db_list->GetNextElement();
        }
        $loc = array_merge($loc->GetFields(), $loc->GetProperties());
    }
    $products = createProductCache($my_city, true);

    print json_encode($products);
}

/* --- Точный поиск по артикулу --- */
if ($type == 'get_list_by_articul') {

    $products = createProductList(0 ,true, false);

    print json_encode($products);
}
/* --- // --- */

if($type == 'set_stat') {
   $q = $_REQUEST['q'];
   $qty = $_REQUEST['qty'];
   if($q =='' || $qty == '') {
       print 'err';
       die();
   }
   $iblock_id = 66;
    if($_SERVER['HTTP_HOST'] == 'dev-evroplast.ru') {
        $iblock_id = 68;
    }
    $el = new CIBlockElement;
    $arLoadProductArray = Array(
        "IBLOCK_SECTION_ID" => false,
        "IBLOCK_ID"      => $iblock_id,
        "PROPERTY_VALUES"=> array("RES_QTY"=>$qty),
        "NAME"           => $q,
        "ACTIVE"         => "Y",
    );
    if($PRODUCT_ID = $el->Add($arLoadProductArray))
        echo "success";
    else
        print "Error: ".$el->LAST_ERROR;
}
