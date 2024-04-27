<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}

$cities = file('cities.txt');
$arr = [];
foreach ($cities AS $city) {
	$explode = explode(' - ', $city);
	$city_name = trim($explode[0]);
	$domain = trim($explode[1]).'.perfom-decor.ru';
	$arr[] = $city_name.' - '.$domain;
}

echo implode(', ', $arr);

?>