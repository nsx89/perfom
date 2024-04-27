<?
/* --- TIMER Временная заглушка --- */

global $USER;

$new_time = strtotime('2024-04-26 10:00');
if (!empty($_GET['new']) || (!$USER->IsAdmin() && time() >= $new_time)) {
	require_once( $_SERVER['DOCUMENT_ROOT'] . '/timer/index2.php'); exit; 
}

if (!$USER->IsAdmin() && empty($_GET['test'])) {
    require_once( $_SERVER['DOCUMENT_ROOT'] . '/timer/index.php'); exit;
}

/* --- // --- */
?>
<!DOCTYPE html>
<html lang="ru">
<head <?= !empty($prefix) ? $prefix : '' ?>>
    <?
    $APPLICATION->ShowHead(false);
    $release = 1;
    $random = 'v='.rand();
    ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="format-detection" content="telephone=no"> <!-- for iOS and Android -->
    <? if (stripos($_SERVER['HTTP_USER_AGENT'],"BlackBerry")) { ?> <!-- Error Fix in W3C html -->
    	<meta http-equiv="x-rim-auto-match" content="none"> <!-- for BlackBerry -->
    <? } ?>
    <meta property="og:type" content="website">
    <? if (!$fbq_ViewContent) { ?>
        <meta property="og:title" content="<?=$APPLICATION->GetTitle()?>">
    <? } ?>
    <meta property="og:description" content="<?=$APPLICATION->GetPageProperty("description")?>">
    <meta property="og:site_name" content="Перфом - производство полиуретановых изделий, лидер на российском рынке">
    <meta property="og:url" content= "https://perfom-decor.ru<?=$APPLICATION->GetCurPage()?>">
    <? if ($fbq_ViewContent) {
        echo $fb_og;
    } else { ?>
        <?/*TODO: need new img*/?>
        <meta property="og:image" content="https://perfom-decor.ru/img/e-logo.jpg">
    <? } ?>
    <meta name="facebook-domain-verification" content="f8pn62cx7hpw8u0zrezspmv6e15bkq">
    <meta name="facebook-domain-verification" content="ikmqqtg4wq8nmw1f0sxw4nxdv3z18b">

    <title><?=$APPLICATION->GetTitle()?></title>

    <link rel="icon" href="/img/favicon/fav.png" type="image/x-icon">
    <link rel="shortcut icon" href="/img/favicon/fav.png" type="image/x-icon">

    <link rel="preload" href="/css/fonts/Nekst/Nekst-Medium.woff" as="font" type="font/woff" crossorigin>
    <link rel="preload" href="/css/fonts/Nekst/Nekst-Regular.woff" as="font" type="font/woff" crossorigin>
    <link rel="preload" href="/css/fonts/Nekst/Nekst-SemiBold.woff" as="font" type="font/woff" crossorigin>

    <?
    use Bitrix\Main\Page\Asset;

    Asset::getInstance()->addCss("/css/fonts/Nekst/style.css");
    Asset::getInstance()->addCss("/css/fonts/icomoon/style.css");
    Asset::getInstance()->addCss("/js/noUiSlider/noui_slider_base.css");
    Asset::getInstance()->addCss("/js/slick-1.8.1/slick.css");
    Asset::getInstance()->addCss("/js/slider-pro/dist/css/slider-pro.min.css");
    Asset::getInstance()->addCss("/js/lightbox/lightbox.min.css");
    Asset::getInstance()->addCss("/js/fancybox-5.0/fancybox.css");
    Asset::getInstance()->addCss("/js/jscrollpane/jscrollpane.css");
    Asset::getInstance()->addCss("/js/formstyler/jquery.formstyler.css");
    Asset::getInstance()->addCss("/js/formstyler/jquery.formstyler.theme.css");
    Asset::getInstance()->addCss("/js/simple-calendar/tcal.css");
    Asset::getInstance()->addCss("/js/tinyscrollbar/tinyscrollbar.css");
    Asset::getInstance()->addCss("/js/ymCal/ymCal.css");

    Asset::getInstance()->addCss("/css/defaults.css");
    Asset::getInstance()->addCss("/css/main.css");
    Asset::getInstance()->addCss("/css/responsive.css");
    Asset::getInstance()->addCss("/css/congratulation.css");

    Asset::getInstance()->addJs("/js/jquery-3.5.1.min.js");
    Asset::getInstance()->addJs("/js/slick-1.8.1/slick.min.js");
    Asset::getInstance()->addJs("/js/wNumb.js");
    Asset::getInstance()->addJs("/js/noUiSlider/nouislider.min.js");
    Asset::getInstance()->addJs("/js/simplePagination.js?".$random);
    Asset::getInstance()->addJs("/js/pagination.js");
    Asset::getInstance()->addJs("/js/slider-pro/dist/js/jquery.sliderPro.min.js");
    Asset::getInstance()->addJs("/js/jquery.cookie.js");
    Asset::getInstance()->addJs("/js/lightbox/lightbox.min.js");
    Asset::getInstance()->addJs("/js/fancybox-5.0/fancybox.js");
    Asset::getInstance()->addJs("/js/jquery.sticky.js");
    Asset::getInstance()->addJs("/js/jquery.mousewheel.js");
    Asset::getInstance()->addJs("/js/jscrollpane/jscrollpane.js");
    Asset::getInstance()->addJs("/js/jquery.maskedinput.min.js");
    Asset::getInstance()->addJs("/js/formstyler/jquery.formstyler.js");
    Asset::getInstance()->addJs("/js/md5.min.js");
    Asset::getInstance()->addJs("/js/jquery.ns-autogrow.min.js");
    Asset::getInstance()->addJs("/js/simple-calendar/tcal.js");
    Asset::getInstance()->addJs("/js/tinyscrollbar/jquery.tinyscrollbar.min.js");
    Asset::getInstance()->addJs("/js/tinyscrollbar/tinyscrollbar.min.js");
    Asset::getInstance()->addJs("/js/ymCal/ymCal.js");
    Asset::getInstance()->addJs("/js/modernizr.js");
    Asset::getInstance()->addJs("/js/questionService.js");
    /*Asset::getInstance()->addJs("/js/search.js");*/
    Asset::getInstance()->addJs("/js/sliders.js");
    Asset::getInstance()->addJs("/js/main.js");
    ?>

    <script defer src="https://zachestnyibiznes.ru/js/zchb-widget.js"></script>

    <script>
        let baseUrl = '/',
            domain = "<?='.'.$_SERVER['HTTP_HOST']?>",
            mainPhoneNumber = '+7 495 315 30 40',
            basketExpires = <?=BASKET_EXPIRES?>;
    </script>
</head>

<?
$my_dealer = null;
$my_location = null;
$my_city = null;
$my_city_fix = null;

global $my_dealer;
global $my_location;
global $my_city;
global $APPLICATION;
global $my_city;
global $my_city_fix;

	$evropa = array('AT','BG','ES','LI','MC','SI','HR','AL','BA','GR','LU','TR','ME','AD','VA','DK','CY','MK','NO',
	'SM','GB','IE','MT','PL','RS','FI','CH','HU','IS','PT','SK','SE','NL','BE','FR','DE','RO','IT','CZ'); // redirect evropa 'FR','DE','RO','IT'
	$baltic_region = array(); // Estonia, Latvia, Lithuania -> 'EE','LV','LT'


require_once ($_SERVER["DOCUMENT_ROOT"] . "/include/GeoIP/GeoIP2/vendor/autoload.php");
use GeoIp2\Database\Reader;
$reader = new Reader($_SERVER["DOCUMENT_ROOT"] . "/include/GeoIP/GeoIP2/GeoLite2-City.mmdb");

//require_once($_SERVER["DOCUMENT_ROOT"] . "/include/GeoIP/geoip_evroplast.inc"); // убрать

		$remote_ip = GetIP();
		$remote_ip = explode(',',$remote_ip);
		$remote_ip = $remote_ip[0];

		if ($remote_ip == '37.63.9.154') $remote_ip = "93.89.190.134"; // Локальное перенаправление разработчика

        //TODO: закрыть $remote_ip на продакшн
        //$remote_ip = "93.89.190.134";
		$record = $reader->dbReader->get($remote_ip);
		if ($record === null) {
			$remote_ip = "93.89.190.134";
		}
		$record = $reader->city($remote_ip);
		$gi_value = $record->country->isoCode;
		$gi_value_city = $record->location;

		//echo 'test '.$gi_value.' | ';

		if (!$gi_value) $gi_value = 'RU';
		if (!$gi_value_city) {$gi_value_city->latitude = 55.754334; $gi_value_city->longitude = 37.6263844326;}

		if (($gi_value == 'BY') && (!$USER->IsAuthorized())) {
			$arCityFilter = Array('IBLOCK_ID' => 7, 'ACTIVE' => 'Y', 'CODE' => 'minsk');
			$db_city_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arCityFilter);
			$city = $db_city_list->GetNextElement();
			if ($city) {
    				$city = array_merge($city->GetFields(), $city->GetProperties());
				$APPLICATION->set_cookie('my_location', $city['map']['VALUE'],0, '/', '.'.$_SERVER['HTTP_HOST']);
				$APPLICATION->set_cookie('my_city', $city['ID'],0, '/', '.'.$_SERVER['HTTP_HOST']);
				$APPLICATION->set_cookie('ip_city', $city['ID'],0, '/', '.'.$_SERVER['HTTP_HOST']);
			        $my_city = $city;
				$my_location = $city['map']['VALUE'];
			}
		} elseif (($gi_value == 'LT') && (!$USER->IsAuthorized())) { // Литва
			$arCityFilter = Array('IBLOCK_ID' => 7, 'ACTIVE' => 'Y', 'CODE' => 'vilnus');
			$db_city_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arCityFilter);
			$city = $db_city_list->GetNextElement();
			if ($city) {
    				$city = array_merge($city->GetFields(), $city->GetProperties());
				$APPLICATION->set_cookie('my_location', $city['map']['VALUE'],0, '/', '.'.$_SERVER['HTTP_HOST']);
				$APPLICATION->set_cookie('my_city', $city['ID'],0, '/', '.'.$_SERVER['HTTP_HOST']);
				$APPLICATION->set_cookie('ip_city', $city['ID'],0, '/', '.'.$_SERVER['HTTP_HOST']);
			        $my_city = $city;
				$my_location = $city['map']['VALUE'];
			}
		} elseif (($gi_value == 'LV') && (!$USER->IsAuthorized())) { // Латвия
			$arCityFilter = Array('IBLOCK_ID' => 7, 'ACTIVE' => 'Y', 'CODE' => 'riga');
			$db_city_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arCityFilter);
			$city = $db_city_list->GetNextElement();
			if ($city) {
    				$city = array_merge($city->GetFields(), $city->GetProperties());
				$APPLICATION->set_cookie('my_location', $city['map']['VALUE'],0, '/', '.'.$_SERVER['HTTP_HOST']);
				$APPLICATION->set_cookie('my_city', $city['ID'],0, '/', '.'.$_SERVER['HTTP_HOST']);
				$APPLICATION->set_cookie('ip_city', $city['ID'],0, '/', '.'.$_SERVER['HTTP_HOST']);
			        $my_city = $city;
				$my_location = $city['map']['VALUE'];
			}
		} elseif (($gi_value == 'EE') && (!$USER->IsAuthorized())) {
			$arCityFilter = Array('IBLOCK_ID' => 7, 'ACTIVE' => 'Y', 'CODE' => 'tallin');
			$db_city_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arCityFilter);
			$city = $db_city_list->GetNextElement();
			if ($city) {
    				$city = array_merge($city->GetFields(), $city->GetProperties());
				$APPLICATION->set_cookie('my_location', $city['map']['VALUE'],0, '/', '.'.$_SERVER['HTTP_HOST']);
				$APPLICATION->set_cookie('my_city', $city['ID'],0, '/', '.'.$_SERVER['HTTP_HOST']);
				$APPLICATION->set_cookie('ip_city', $city['ID'],0, '/', '.'.$_SERVER['HTTP_HOST']);
			        $my_city = $city;
				$my_location = $city['map']['VALUE'];
			}
		} elseif (($gi_value == 'GE') && (!$USER->IsAuthorized())) {
			$arCityFilter = Array('IBLOCK_ID' => 7, 'ACTIVE' => 'Y', 'CODE' => 'tbilisi');
			$db_city_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arCityFilter);
			$city = $db_city_list->GetNextElement();
			if ($city) {
    				$city = array_merge($city->GetFields(), $city->GetProperties());
				$APPLICATION->set_cookie('my_location', $city['map']['VALUE'],0, '/', '.'.$_SERVER['HTTP_HOST']);
				$APPLICATION->set_cookie('my_city', $city['ID'],0, '/', '.'.$_SERVER['HTTP_HOST']);
				$APPLICATION->set_cookie('ip_city', $city['ID'],0, '/', '.'.$_SERVER['HTTP_HOST']);
			        $my_city = $city;
				$my_location = $city['map']['VALUE'];
			}
		} elseif ($APPLICATION->get_cookie('my_city') && $APPLICATION->get_cookie('my_city') > 0 ) { //&& $USER->IsAuthorized()) {
			$my_location = $APPLICATION->get_cookie('my_location');
			$my_city = $APPLICATION->get_cookie('my_city');
			
        	$APPLICATION->set_cookie('my_city', $my_city,0, '/', '.'.$_SERVER['HTTP_HOST']);
			$APPLICATION->set_cookie('my_location', $my_location,0, '/', '.'.$_SERVER['HTTP_HOST']);
		} else {
			
			$lat = $gi_value_city->latitude;
			$lon = $gi_value_city->longitude;
			
			// Переход с поиска ближайшего дилера на ближайший регион, играем от региона.
			$items_city = array();
			$arFilter = Array('IBLOCK_ID' => 7, 'ACTIVE' => 'Y');
			$db_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter);
			while ($cur_item = $db_list->GetNextElement()) {
					$cur_item = array_merge($cur_item->GetFields(), $cur_item->GetProperties());
					list($lat_city, $lon_city) = explode(",", $cur_item['map']['VALUE']);
					if ($lat_city > 0 && $lon_city > 0) {
                	$distance = getDistanceBetweenPoints($lat, $lon, $lat_city, $lon_city);
                	$items_city[round($distance, 0)] = $cur_item;
            		}
			}
			ksort($items_city);
			$item_city = current($items_city); // Ближайший город
        	$APPLICATION->set_cookie('my_city', $item_city['ID'],0, '/', '.'.$_SERVER['HTTP_HOST']);
			$APPLICATION->set_cookie('my_location', $lat.','.$lon,0, '/', '.'.$_SERVER['HTTP_HOST']);
		
				$my_location = $lat.','.$lon;
        		$my_city = $item_city['ID'];
				$my_city_fix = true; // Выбор региона при первом заходе
			
		}
		// echo 'test '.$my_dealer.' | '.$my_location.' | '.$my_city;

?>