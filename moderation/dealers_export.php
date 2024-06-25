<?
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock')) exit;
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
exit;
}

$arSelect = Array();
$arFilter = Array("IBLOCK_ID"=>6, "ACTIVE"=>"Y");
$res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
$n = 1;
$new_list = Array("0"=>array(
    "ID",
    "Организация",
    "Точка продажи",
    "Город (для скидки)",
    "Адрес",
    "ТЦ Рынок и пр",
    "Ориентир для поиска",
    "Телефоны",
    "email",
    "email для заказа",
    "ссылка на сайт дилера",
    "Будни",
    "Суббота",
    "Воскресенье",
    "Доп. выходные",
    "Без выходных",
    "Карта",
    "Доставка",
    "Монтаж",
    "Обмен/возврат",
    "Склад",
    "Способы оплаты",
    "Дополнительно"
    ));
while($ob = $res->GetNextElement())
{
    $item = array_merge($ob->GetFields(), $ob->GetProperties());
    $city_id = $item['city']['~VALUE'];
    $res_city = CIBlockElement::GetByID($city_id);
    if($ar_res = $res_city->GetNext()) $city = $ar_res['NAME'];
    $phones = str_replace(';',',',$item['phones']['~VALUE']);
    $new_list[$n] = Array(
        $item['ID'],
        $item['organization']['~VALUE'],
        $item['trade_point']['~VALUE'],
        $city,
        $item['address']['~VALUE'],
        $item['trading_center']['~VALUE'],
        $item['orientation']['~VALUE'],
        $phones,
        $item['email']['~VALUE'],
        $item['orderemail']['~VALUE'],
        $item['href']['~VALUE'],
        $item['workday']['~VALUE'],
        $item['saturday']['~VALUE'],
        $item['sunday']['~VALUE'],
        $item['weekend']['~VALUE'],
        $item['without']['~VALUE'],
        $item['map']['~VALUE'],
        $item['delivery']['~VALUE'],
        $item['mounting']['~VALUE'],
        $item['return']['~VALUE'],
        $item['storage']['~VALUE'],
        $item['payment_method']['~VALUE'],
        $item['add']['~VALUE'],
        );
    $n++;
}

print_r($new_list);
$fp = fopen($_SERVER["DOCUMENT_ROOT"].'/moderation/dealers.csv', 'w');
foreach ($new_list as $fields) {
    fputs($fp, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM
    fputcsv($fp, $fields, ';', ' ');
}
fclose($fp);
print '<br>save';



if(defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED===true)
{
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog.php");
}

