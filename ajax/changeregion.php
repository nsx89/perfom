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

/* --- Смена города --- */

echo my_city_change($id);

/* --- // --- */


if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog.php");
}
?>
