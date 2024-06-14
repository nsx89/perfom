<?php
/*ini_set('display_errors', 1);
error_reporting(E_ALL);*/
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/smart_search/functions.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog") || !CModule::IncludeModule("search")) {
    exit;
}
set_time_limit(15000);


CIBlock::clearIblockTagCache(12);

$list = file_get_contents('https://perfom-decor.ru/smart_search/ajax.php?type=get_list');
echo $list;

/*
$arFilterCountry = Array('IBLOCK_ID' => 7, 'ACTIVE' => 'Y');
$db_list_country = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilterCountry, false, Array());
while ($ob = $db_list_country->GetNextElement()) {

    $loc = array_merge($ob->GetFields(), $ob->GetProperties());

    print_r($loc['country']['VALUE']);
    echo '<br>';

    //createProductCache($loc['country']['VALUE'], false);

}
echo 'end';