<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}


@set_time_limit(0);

$arr_city = array();

// проходим по активным дилерам, собираем регионы.
$arFilter = Array('IBLOCK_ID' => 6, 'ACTIVE' => 'Y');
$db_list = CIBlockElement::GetList(Array(), $arFilter);
while ($fcontact = $db_list->GetNextElement()) {
    $fcontact = array_merge($fcontact->GetFields(), $fcontact->GetProperties());
	
	if (in_array($fcontact['city']['VALUE'], $arr_city)) continue;
	$arr_city[] = $fcontact['city']['VALUE'];
		
}


$fp = fopen('reg_stat.csv', 'w');
foreach ($arr_city as $fields) {
$arFilter = Array('IBLOCK_ID' => 7, 'ACTIVE' => 'Y', 'ID' => $fields);
$db_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter);
$loc = $db_list->GetNextElement();
if ($loc) {
	$loc = array_merge($loc->GetFields(), $loc->GetProperties());
	$arCFilter = Array('IBLOCK_ID' => 9, 'ACTIVE' => 'Y', 'ID'=>$loc['country']['VALUE']);
	$db_C_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arCFilter);
	$country = $db_C_list->GetNextElement();	
	$country = array_merge($country->GetFields(), $country->GetProperties());
}
fwrite($fp, $loc['NAME'] . ';' . $country['NAME'] . PHP_EOL);
// echo $loc['NAME'].' - '.$country['NAME'].'<br>';	
}
fclose($fp);

echo 'файл данных сформирован ...'.'<br>';
echo '<a href="/cron/reg_stat.csv">загрузить</a>';




?>