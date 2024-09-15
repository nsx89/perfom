<?
/**
 * выгрузка заказов по регионам
 */
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}
$arFilter = Array(
    "IBLOCK_ID"=>43,
    "ACTIVE"=>"Y",
    array(
        "LOGIC" => "AND",
        array('>=PROPERTY_DATE' => "2020-01-01 00:00:00"),
        array('<=PROPERTY_DATE' => "2021-04-30 23:59:59"),
    ),);
$res = CIBlockElement::GetList(Array("NAME"=>"ASC"), $arFilter, false, Array(), Array());
$n = 1;
$list = Array("0"=>array(
    "Дата заказа",
    "Номер заказа",
    "Сумма, руб.",
    "Город"
));
while($ob = $res->GetNextElement()) {
    $item = array_merge($arFields = $ob->GetFields(),$arFields = $ob->GetProperties());
    if($item['CHOOSEN_REG']['VALUE'] == 3109) continue;

    //city
    $res_reg = CIBlockElement::GetList(Array(), Array("IBLOCK_ID"=>7, "ACTIVE"=>"Y", "ID"=>$item['CHOOSEN_REG']['VALUE']), false, Array(), Array());
    while($ob_reg = $res_reg->GetNextElement()) $reg = array_merge($ob_reg->GetFields(),$ob_reg->GetProperties());

    //order total sum
    $sum = 0;
    $res_prod = CIBlockElement::GetList(Array(), Array("IBLOCK_ID"=>44, "ACTIVE"=>"Y", "PROPERTY_ORDER_NUMBER"=>$item['NAME']), false, Array(), Array());
    while($ob_prod = $res_prod->GetNextElement()) {
        $prod = array_merge($ob_prod->GetFields(),$ob_prod->GetProperties());
        $sum += $prod['BASE_PRICE']['VALUE'] != '' ? $prod['BASE_PRICE']['VALUE'] : $prod['PRICE']['VALUE'];
    }
    $sum = number_format($sum, 2, '.', ' ');

    //date
    $date = explode(' ',$item['DATE']['VALUE'])[0];

    //echo $n++.' - '.$date.' - '.$item['NAME'].' - '.$sum.' - '.$reg['NAME'].'<br>';

    $list[$n++] = Array(
        str_replace(';',',',htmlspecialchars_decode($date)),
        str_replace(';',',',htmlspecialchars_decode($item['NAME'])),
        str_replace(';',',',htmlspecialchars_decode($sum)),
        str_replace(';',',',htmlspecialchars_decode($reg['NAME']))
    );

}
$fp = fopen($_SERVER["DOCUMENT_ROOT"].'/reports/orders.csv', 'w');
foreach ($list as $fields) {
    fputs($fp, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM
    fputcsv($fp, $fields, ';', ' ');
}
fclose($fp);

?>
<h1>Выгрузка заказов по регионам за период с 01.01.2020 по 30.04.2021</h1>
<a href="/reports/orders.csv">Скачать</a>
