<?require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}

//$http_host_temp = explode(":",$_SERVER['HTTP_HOST']);
//$_SERVER['HTTP_HOST'] = $http_host_temp[0];

$id = $_POST['regionId'];
if(!$id) {
    exit;
}
$arCityFilter = Array('IBLOCK_ID' => 7, 'ACTIVE' => 'Y', 'ID'=>$id);
$db_city_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arCityFilter);
$city = $db_city_list->GetNextElement();
if (!$city) {
    exit;
}
$city = array_merge($city->GetFields(), $city->GetProperties());
$APPLICATION->set_cookie('my_location', $city['map']['VALUE'], 0, '/', '.'.HTTP_HOST);
$APPLICATION->set_cookie('my_city', $city['ID'], 0, '/', '.'.HTTP_HOST);

print $city['ID'];

/* --- Не показываем больше окно выбора --- */

my_city_fixed();

/* --- // --- */

/*if (!empty($_GET['test'])) {
    echo '<pre>';
    print_r($_COOKIE);
    echo '</pre>';
}*/

if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog.php");
}
?>
