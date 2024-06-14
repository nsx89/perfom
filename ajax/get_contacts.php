<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}
require_once($_SERVER["DOCUMENT_ROOT"] . "/include/phone.php");

$my_city = $APPLICATION->get_cookie('my_city');

if ($my_city) {
	$arFilter = Array('IBLOCK_ID' => 7, 'ACTIVE' => 'Y', 'ID' => $my_city);
	$db_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter);
	$loc = $db_list->GetNextElement();
	$loc = array_merge($loc->GetFields(), $loc->GetProperties());
} else { // Нет региона???
	$err = Array('qty' => 323,'mess' => 'Регион отсутствует в куки');
	print json_encode(array('err'=>$err, 'dealers'=>''));
	die();
}

if ($loc) {
	$arFilter = Array('IBLOCK_ID' => 6, 'ACTIVE' => 'Y', 'PROPERTY_city' => $loc);
	$db_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter);
} else {
	$err = Array('qty' => 324,'mess' => 'Регион отсутсвует в базе');
	print json_encode(array('err'=>$err, 'dealers'=>''));
	die();
}

list($lat, $lon) = explode(",", $loc['map']['VALUE']);
$list = Array('position' => array('zoom' => $loc['zoom']['VALUE'],'lat' => $lat,'lon' => $lon));

//print_r($list);

// Москва, порядок дилеров первыми
if (($loc['CODE'] == 'moskva') || ($loc['CODE'] == 'moskovskaya-oblast')) $moscow_dealers_list = array(3714, 6806, 3715, 3716, 30875, 27546);

$moscow_dealers = array(); // Первые дилеры Москвы в списке
$main_dealers = array(); // если больше одного на регионе отдельно вынесен (обработка логики выдачи может быть разной)
$other_dealers = array();

while ($dealer = $db_list->GetNextElement()) {
$dealer = array_merge($dealer->GetFields(), $dealer->GetProperties());	

list($d_lat, $d_lon) = explode(",", $dealer['map']['VALUE']);

$item_dealer = array(
'city' => $loc['NAME'],
'id' => $dealer['ID'],
'org' => $dealer['organization']['VALUE'],
'point' => $dealer['trade_point']['VALUE'],
'phones' => str_phone($dealer['phones']['VALUE']),
'email' => $dealer['email']['VALUE'],
'url' => $dealer['href']['VALUE'],
'url2' => $dealer['href2']['VALUE'],
'addr' => $dealer['address']['VALUE'],
'mall' => $dealer['trading_center']['VALUE'],
'mark' => $dealer['orientation']['VALUE'],
'weekdays' => $dealer['workday']['VALUE'],
'saturday' => $dealer['saturday']['VALUE'],
'sunday' => $dealer['sunday']['VALUE'],
'without' => $dealer['without']['VALUE'],
'weekend' => $dealer['weekend']['VALUE'],
'lat' => $d_lat,
'lon' => $d_lon,
);

if ($moscow_dealers_list)
	if (in_array($dealer['ID'],$moscow_dealers_list)) $moscow_dealers[] = $item_dealer;
	else $other_dealers[] = $item_dealer;
elseif ($loc['dealers_list']['VALUE'] != '') 
	if (in_array($dealer['ID'],$loc['dealers_list']['VALUE'])) $main_dealers[] = $item_dealer;
	else $other_dealers[] = $item_dealer;
else $other_dealers[] = $item_dealer;
	
}

if ($moscow_dealers_list) {
	$temp_sort = array();	
	foreach ($moscow_dealers_list as $d_item_list) {
		foreach ($moscow_dealers as $d_item) {	
			if ($d_item['id'] == $d_item_list) array_push($temp_sort, $d_item);
		}
	}	
$moscow_dealers = $temp_sort;
$list['moscow_dealers'] = $moscow_dealers;
$list['main_dealers'] = $main_dealers;
$list['items'] = array_merge($moscow_dealers,$other_dealers);

} else {

shuffle($main_dealers); // случайный порядок
$list['main_dealers'] = $main_dealers;
$list['items'] = array_merge($main_dealers,$other_dealers);

}

//echo '<pre>';
//print_r($list);
//echo '</pre>';

$err = Array('qty' => 0,'mess' => '');
/*$list = Array (
    'position' => Array (
        'zoom' => 10,
        'lat' => 53.906810658189,
        'lon' => 27.553660586914,
    ),
    'items' => Array (
        '0' => Array (
        'city' => 'Минск',
        'id' => '3244',
        'org' => 'ЧТУП "Салон интерьера"',
        'point' => 'Салон интерьера',
        'phones' =>' +375 (17) 373-20-00, +375 (44) 575-06-97',
        'email' => 'dekor-si@mail.ru',
        'url' => 'www.dekorus.by',
        'addr' => 'улица Пономаренко, 35А, этаж 1, оф. 103',
        'mall' => 'Товарищество собственников Пономаренко, 35 А',
        'mark' => 'Ресторан "СОЧИ"',
        'weekdays' => '09:00 - 18:00',
        'saturday' => 'Выходной',
        'sunday' => 'Выходной',
        'without' => 'N',
        'weekend' => '',
        'lat' => '53.892886',
        'lon' => '27.493559',
    ),
        '1' => Array (
        'city' => 'Минск',
        'id' => '3245',
        'org' => 'ЧТУП "Салон интерьера"',
        'point' => 'Европласт',
        'phones' => '+375 (17) 259-63-16, +375 (29) 106-85-60',
        'email' => 'dekor-si@mail.ru',
        'url' => 'www.domdekor.by',
        'addr' => 'ул. Тимирязева, 123, кор. 2, 2-й этаж',
        'mall' => 'ТЦ "Град"',
        'mark' => 'Рынок Ждановичи',
        'weekdays' => '10:00 - 17:00',
        'saturday' => '10:00 - 17:00',
        'sunday' => '10:00 - 17:00',
        'without' => 'N',
        'weekend' => 'Понедельник',
        'lat' => '53.933',
        'lon' => '27.457',
    ),
    ),
);
*/

print json_encode(array('err'=>$err, 'dealers'=>$list));