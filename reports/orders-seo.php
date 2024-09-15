<?
/**
 * выгрузка заказов по регионам для seo
 */
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/include/phone.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}
set_time_limit(6000);
$now_date = date('Y-m-d H:i:s');
$arFilter = Array(
    "IBLOCK_ID"=>43,
    "ACTIVE"=>"Y",
    array(
        "LOGIC" => "AND",
        array('>=PROPERTY_DATE' => "2020-01-01 00:00:00"),
        array('<=PROPERTY_DATE' => $now_date),
    ),);
$res = CIBlockElement::GetList(Array("NAME"=>"ASC"), $arFilter, false, Array(), Array());
$n = 1;
$list = Array("0"=>array(
    "Телефон",
    "Email",
    "Статус",
    "Город"
));
while($ob = $res->GetNextElement()) {
    $item = array_merge($arFields = $ob->GetFields(),$arFields = $ob->GetProperties());
    //if($item['CHOOSEN_REG']['VALUE'] == 3109) continue;

    //city
    $res_reg = CIBlockElement::GetList(Array(), Array("IBLOCK_ID"=>7, "ACTIVE"=>"Y", "ID"=>$item['CHOOSEN_REG']['VALUE']), false, Array(), Array());
    while($ob_reg = $res_reg->GetNextElement()) $reg = array_merge($ob_reg->GetFields(),$ob_reg->GetProperties());

    $list[$n++] = Array(
        str_replace(';',',',htmlspecialchars_decode(str_phone($item['USER_PHONE']['VALUE']))),
        str_replace(';',',',htmlspecialchars_decode($item['USER_MAIL']['VALUE'])),
        str_replace(';',',',htmlspecialchars_decode(get_order_status($item['STATUS']['VALUE']))),
        str_replace(';',',',htmlspecialchars_decode($reg['NAME']))
    );

}
$fp = fopen($_SERVER["DOCUMENT_ROOT"].'/reports/orders-seo.csv', 'w');
foreach ($list as $fields) {
    fputs($fp, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM
    fputcsv($fp, $fields, ';', ' ');
}
fclose($fp);

?>
<h1>Выгрузка заказов по регионам за период с 01.01.2021 по <?=date('d.m.Y')?></h1>
<a href="/reports/orders-seo.csv">Скачать</a>
