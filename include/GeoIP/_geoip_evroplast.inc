<?php

function GetRegion($r_ip){
$r_ip = ip2long($r_ip);

if (($fp = fopen($_SERVER["DOCUMENT_ROOT"] . "/include/GeoIP/GeoIP_Evroplast.csv", "r")) !== FALSE) {
	while (($data = fgetcsv($fp, 0, ";")) !== FALSE) {
	$ip_base[] = $data;
	}
fclose($fp);
}

$region = null;

 	foreach ($ip_base as $ip) {
		$ip_start = ip2long($ip[0]);
		$ip_end = ip2long($ip[1]);

		if (($r_ip >= $ip_start) && ($r_ip <= $ip_end)) {
		$region = $ip[2];
		break;
		} 
	}	

return $region;
}

function PutRegionIP($r_ip) {
global $APPLICATION;
$f_name = $_SERVER["DOCUMENT_ROOT"].'/sv/RegionIP_Evroplast';
$my_city_k = $APPLICATION->get_cookie('my_city');
$ip_city_k = $APPLICATION->get_cookie('ip_city');

	// загрузка текущей таблицы
	if (($fp = fopen($f_name.'.csv', "r")) !== FALSE) {
		while (($data = fgetcsv($fp, 0, ";")) !== FALSE) {
		$ip_base[] = $data;
		}
	fclose($fp);
	}

if ($my_city_k) {
	$arCityFilter = Array('IBLOCK_ID' => 7, 'ACTIVE' => 'Y', 'ID' => $my_city_k);
	$db_city_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arCityFilter);
	$my_city_k = $db_city_list->GetNextElement();
	if ($my_city_k) $my_city_k = array_merge($my_city_k->GetFields(), $my_city_k->GetProperties());
}
if ($ip_city_k) {
	$arCityFilter = Array('IBLOCK_ID' => 7, 'ACTIVE' => 'Y', 'ID' => $ip_city_k);
	$db_city_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arCityFilter);
	$ip_city_k = $db_city_list->GetNextElement();
	if ($ip_city_k) $ip_city_k = array_merge($ip_city_k->GetFields(), $ip_city_k->GetProperties());
}

if ($my_city_k['CODE'] != $ip_city_k['CODE']) { // тогда обработка

$sr = false;
foreach ($ip_base as $ip) {
		$ip_start = ip2long($ip[0]);
		$ip_end = ip2long($ip[1]);
		$rsr_ip = ip2long($r_ip);
			if (($rsr_ip >= $ip_start) && ($rsr_ip <= $ip_end) && ($ip[2] == $my_city_k['CODE']) && 
				($ip[3] == $ip_city_k['CODE'])) {
			$sr = true;
			break;
			} 	
}

if (!$sr) {
$exp_ip = explode('.',$r_ip);

$ip_start = $exp_ip[0].'.'.$exp_ip[1].'.'.$exp_ip[2].'.'.'0';
$ip_end	  = $exp_ip[0].'.'.$exp_ip[1].'.'.$exp_ip[2].'.'.'255';

$regin_str = array();
$regin_str[0] = $ip_start;
$regin_str[1] = $ip_end;
$regin_str[3] = $my_city_k['CODE'];
$regin_str[4] = $ip_city_k['CODE'];

$fp = fopen($f_name.'.csv', 'a');
fputcsv($fp, $regin_str, ';', '"');
fclose($fp);

}
} else { // обработка когда попали фиксируем ip
$sr = false;
foreach ($ip_base as $ip) {
		$ip_start = ip2long($ip[0]);
		$ip_end = ip2long($ip[1]);
		$rsr_ip = ip2long($r_ip);
			if (($rsr_ip >= $ip_start) && ($rsr_ip <= $ip_end) && ($ip[2] == $my_city_k['CODE']) && 
				($ip[3] == $ip_city_k['CODE'])) {
			$sr = true;
			break;
			} 	
}

if (!$sr) {
$exp_ip = explode('.',$r_ip);

$ip_start = $exp_ip[0].'.'.$exp_ip[1].'.'.$exp_ip[2].'.'.'0';
$ip_end	  = $exp_ip[0].'.'.$exp_ip[1].'.'.$exp_ip[2].'.'.'255';

$regin_str = array();
$regin_str[0] = $ip_start;
$regin_str[1] = $ip_end;
$regin_str[3] = $my_city_k['CODE'];
$regin_str[4] = $ip_city_k['CODE'];

$fp = fopen($f_name.'_fix.csv', 'a');
fputcsv($fp, $regin_str, ';', '"');
fclose($fp);

}
}
}

?>
