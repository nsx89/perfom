<?
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/smart_search/functions.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog") || !CModule::IncludeModule("search")) {
    exit;
}
if($type == 'get_list') {
    $my_city = $APPLICATION->get_cookie('my_city');
    /* взято из top-current-location.php */
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
            $my_city_fix = true; // Дать перевыбрать регион или зависнет на Москве
        }
        $loc = array_merge($loc->GetFields(), $loc->GetProperties());
    }

    $products = createProductCache($loc['country']['VALUE'],true);

    print json_encode($products);
}

if ($type == 'get_list_by_articul') {
    $FIND_BY_ARTICUL_VALUE = htmlspecialcharsbx($_REQUEST['FIND_BY_ARTICUL_VALUE']);
    $products = createProductCache($loc['country']['VALUE'],true);

    print json_encode($products);
}

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
