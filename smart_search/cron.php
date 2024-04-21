<?php
/*ini_set('display_errors', 1);
error_reporting(E_ALL);*/
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/smart_search/functions.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog") || !CModule::IncludeModule("search")) {
    exit;
}
set_time_limit(6000);


//создаем кеш по странам (из-за разных цен)
$arFilterCountry = Array('IBLOCK_ID' => 9, 'ACTIVE' => 'Y');
$db_list_country = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilterCountry, false, Array(), Array("ID"));
while ($count_ob = $db_list_country->GetNext()) {
    print_r($count_ob['ID']);
    echo '<br>';

    createProductCache($count_ob['ID'],false);

}
echo 'end';