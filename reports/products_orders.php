<?
/**
 * выгрузка статистики товаров из заказов
 */
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}
set_time_limit(72000);
$arFilter = Array(
    "IBLOCK_ID"=>44,
    "ACTIVE"=>"Y",
    array(
        '>=DATE_CREATE' => "01.01.2023 00:00:00",
        '<=DATE_CREATE' => "31.12.2023 23:59:59"
    ),);

$res = CIBlockElement::GetList(Array("NAME"=>"ASC"), $arFilter, false, Array(),
    Array(
    "PROPERTY_QTY",
    "PROPERTY_BASE_PRICE",
    "PROPERTY_PRICE"
));
$n = 1;
$list = Array("0"=>array(
    "Наименование",
    "Артикул",
    "Количество",
    "Сумма, руб.",
));
$arr = Array();
$ids = Array();
while($ob = $res->GetNextElement()) {
    $item = array_merge($arFields = $ob->GetFields(),$arFields = $ob->GetProperties());
    $item_price_rub = $item['PROPERTY_BASE_PRICE_VALUE'] != '' ? $item['PROPERTY_BASE_PRICE_VALUE'] : $item['PROPERTY_PRICE_VALUE'];
    $item_total = $item_price_rub * $item['PROPERTY_QTY_VALUE'];
    $ids[] = $item['NAME'];
    if($arr[$item['NAME']]) {
        $arr[$item['NAME']]['qty'] += $item['PROPERTY_QTY_VALUE'];
        $arr[$item['NAME']]['total'] += $item_total;
    } else {
        $arr[$item['NAME']] = Array('qty' => $item['PROPERTY_QTY_VALUE'], 'total' => $item_total);
    }

}
$res = CIBlockElement::GetList(Array(), Array("IBLOCK_ID"=>IB_CATALOGUE,"ID"=>$ids), false, Array(), Array());
while($ob = $res->GetNextElement()) {
    $prod = array_merge($arFields = $ob->GetFields(),$arFields = $ob->GetProperties());
    $arr[$prod['ID']]['name'] = __get_product_name($prod);
    $arr[$prod['ID']]['art'] = $prod['ARTICUL']['VALUE'];

    print_r($prod['ID']."<br>");
    print_r($prod['NAME']);
    echo("<br><br>");

}
//print_r($arr);
foreach($arr as $i=>$item) {
    if(!$item['art']) {
        if(mb_stripos($i,'s') == 0) {
            $id = mb_substr( $i, 1);
            $res = CIBlockElement::GetList(Array(), Array("IBLOCK_ID"=>IB_CATALOGUE,"ID"=>$id), false, Array(), Array());
            while($ob = $res->GetNextElement()) {
                $prod = array_merge($arFields = $ob->GetFields(),$arFields = $ob->GetProperties());
                $item['name'] = __get_product_name($prod)." (образец)";
                $item['art'] = $prod['ARTICUL']['VALUE'];
            }
        } else {
            print_r("Ooops!.... ".$i."<br>");
        }
    }
    $list[$n++] = Array(
        str_replace(';',',',htmlspecialchars_decode($item['name'])),
        $item['art'],
        $item['qty'],
        $item['total']
    );
}

$fp = fopen($_SERVER["DOCUMENT_ROOT"].'/reports/prod2023.csv', 'w');
foreach ($list as $fields) {
    fputs($fp, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM
    fputcsv($fp, $fields, ';', ' ');
}
fclose($fp);

?>
<h1>Выгрузка товаров из заказов за период с 01.01.2023 по 31.12.2023</h1>
<a href="/reports/prod2023.csv">Скачать</a>
