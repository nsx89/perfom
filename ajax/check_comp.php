<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");

if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}

$arr = Array(
    'qty' => 0, // колич-во недоступных частей
    'name' => Array() //названия
);
$id = $_REQUEST['id'];
$comp_arr = Array();

$arFilter = Array("IBLOCK_ID"=>12, "ID"=>$id);
$res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), Array("PROPERTY_COMPOSITEPART"));
while($ob = $res->GetNextElement()) {
    if($ob->fields['PROPERTY_COMPOSITEPART_VALUE'] != '') $comp_arr[] = $ob->fields['PROPERTY_COMPOSITEPART_VALUE'];
}
if(!empty($comp_arr)) {
    $arFilter = Array("IBLOCK_ID"=>12, "ID"=>$comp_arr);
    $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), Array());
    while($ob = $res->GetNextElement()) {
        $item = array_merge($arFields = $ob->GetFields(),$arFields = $ob->GetProperties());
        if($item['NO_ORDER']['VALUE'] == 'Y') {
            $arr['qty']++;
            $arr['name'][] = __get_product_name($item);
        }
    }
}

print json_encode($arr);