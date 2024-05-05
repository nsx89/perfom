<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}

global $DB;


/*
$i = 1;
$arFilter = Array('IBLOCK_ID' => 43, "<ID"=>"246875");
$res = CIBlockElement::GetList(Array("ID"=>"DESC"), $arFilter);
while ($row = $res->GetNextElement()) {

    $i++;
    if ($i > 10000) exit;

    $item = $row->getFields();
    $prop = $row->getProperties();

    $item_id = $item['ID'];

    $DB->Query("DELETE FROM `b_iblock_element_property` WHERE `IBLOCK_ELEMENT_ID` = '{$item_id}'");
    $DB->Query("DELETE FROM `b_iblock_element` WHERE `ID` = '{$item_id}'");

    echo("DELETE FROM `b_iblock_element` WHERE `ID` = '{$item_id}'");
    echo '<br>';
}


?>