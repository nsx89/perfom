<?
/**
 * выгрузка товаров для qr кодов
 */
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}
$arFilter = Array(
    "IBLOCK_ID"=>IB_CATALOGUE,
    "ACTIVE"=>"Y",
    "PROPERTY_COMPOSITEPART"=>false
    );
$res = CIBlockElement::GetList(Array("PROPERTY_ARTICUL"=>"ASC"), $arFilter, false, Array(), Array());
$n = 1;
$list = Array("0"=>array(
    "ID",
    "Наименование",
    "Артикул",
    "Гибкий",
    "Ссылка"
));
while($ob = $res->GetNextElement()) {
    $item = array_merge($arFields = $ob->GetFields(),$arFields = $ob->GetProperties());

    $list[$n++] = Array(
        $item['ID'],
        $item['NAME'],
        $item['ARTICUL']['VALUE'],
        $item['FLEX']['VALUE'],
        'https://evroplast.ru'.__get_product_link($item)
    );

}
//print_r($list);
$fp = fopen($_SERVER["DOCUMENT_ROOT"].'/reports/products_qr.csv', 'w');
foreach ($list as $fields) {
    //fputs($fp, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM
    fputcsv($fp, $fields, ';', ' ');
}
fclose($fp);

?>
<h1>Выгрузка товаров для qr кодов</h1>
<a href="/reports/products_qr.csv?v=<?=rand()?>">Скачать</a>
