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
        array('>=PROPERTY_DATE' => "2024-02-01 00:00:00"),
        array('<=PROPERTY_DATE' => "2024-02-12 23:59:59"),
    ),);
$res = CIBlockElement::GetList(Array("NAME"=>"ASC"), $arFilter, false, Array(), Array());
$n = 1;
$list = Array("0"=>array(
    "Дата заказа",
    "Номер заказа",
	"Регион",
	"Десктоп/Моб.",
	"Телефон",
	"E-mail",
	"Имя",
    "Сумма без скидки",
	"Сумма со скидкой",
	"Валюта",
	"Тип оплаты",
	"Дилер",
	"Тел. Дилер",
));
while($ob = $res->GetNextElement()) {
    $item = array_merge($arFields = $ob->GetFields(),$arFields = $ob->GetProperties());
    //if($item['CHOOSEN_REG']['VALUE'] == 3109) continue;

    //city
    $res_reg = CIBlockElement::GetList(Array(), Array("IBLOCK_ID"=>7, "ACTIVE"=>"Y", "ID"=>$item['CHOOSEN_REG']['VALUE']), false, Array(), Array());
    while($ob_reg = $res_reg->GetNextElement()) $reg = array_merge($ob_reg->GetFields(),$ob_reg->GetProperties());

	if ($item["TOTAL_SALE"]["VALUE"]) $price = $item["TOTAL_SALE"]["VALUE"];
	else $price = $item["TOTAL"]["VALUE"];
	if ($price > $item["PAY_TOTAL"]["VALUE"]) $price = $price - $item["PAY_TOTAL"]["VALUE"];
	
	$price = number_format($price, 2,'.','');
	$save_summ = number_format($item["SALE_SUM"]["VALUE"], 2,'.','');

    //date
    $date = explode(' ',$item['DATE']['VALUE'])[0];
	$USER_PHONE = preg_replace('/[^0-9]/', '', $item['USER_PHONE']['VALUE']);
	$PHONE_DEALER = preg_replace('/[^0-9]/', '', $item['PHONE_DEALER']['VALUE']);
	

    //echo $n++.' - '.$date.' - '.$item['NAME'].' - '.$price.' - '.$reg['NAME'].'<br>';

    $list[$n++] = Array(
        str_replace(';',',',htmlspecialchars_decode($date)),
        str_replace(';',',',htmlspecialchars_decode($item['NAME'])),
		str_replace(';',',',htmlspecialchars_decode($reg['NAME'])),
		str_replace(';',',',htmlspecialchars_decode($item['VERS']['VALUE'])),
		str_replace(';',',',htmlspecialchars_decode($item['USER_PHONE']['VALUE'])),
		str_replace(';',',',htmlspecialchars_decode($item['USER_MAIL']['VALUE'])),
		str_replace(';',',',htmlspecialchars_decode($item['USER_NAME']['VALUE'])),
		str_replace(';',',',htmlspecialchars_decode($save_summ)),	
        htmlspecialchars_decode($price),
		htmlspecialchars_decode($item['CURR']['VALUE']),
		str_replace(';',',',htmlspecialchars_decode($item['PAYMENT']['VALUE'])),
		str_replace(';',',',htmlspecialchars_decode($item['MAIL_DEALER']['VALUE'])),
		str_replace(';',',',htmlspecialchars_decode($item['PHONE_DEALER']['VALUE'])),	
    );

}
$fp = fopen($_SERVER["DOCUMENT_ROOT"].'/reports/orders_evroplast.csv', 'w');
foreach ($list as $fields) {
    //fputs($fp, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM
    fputcsv($fp, $fields, ';', ' ');
}
fclose($fp);

?>
<h1>Выгрузка заказов по регионам за период с 01.01.2020 по 30.04.2021</h1>
<a href="/reports/orders_evroplast.csv">Скачать</a>
