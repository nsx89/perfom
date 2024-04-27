<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}

/* 
$arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, '!PROPERTY_HIDE_GENERAL' => 'Y');
$res = CIBlockElement::GetList(Array(), $arFilter);
while ($row = $res->GetNextElement()) {
	$item = $row->getFields();
	$prop = $row->getProperties();

   	echo 'ID:'.$item['ID'].'  =   Артикул: '.$prop['ARTICUL']['VALUE'].'  =   active ='.$prop['ACTIVE']['VALUE'].'
   		= PROPERTY_HIDE_GENERAL ='.$prop['PROPERTY_HIDE_GENERAL']['VALUE'].'
   		= PROPERTY_NEW_ART_DECO ='.$prop['PROPERTY_NEW_ART_DECO']['VALUE'].'<br>';

   	$el = new CIBlockElement;
	$el->Update($item['ID'], Array(
		"TAGS" => "Y",    
	));
}
*/


/*
$arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, '!TAGS' => 'Y');
$res = CIBlockElement::GetList(Array(), $arFilter);
while ($row = $res->GetNextElement()) {
	$item = $row->getFields();
	$prop = $row->getProperties();

   	echo 'ID:'.$item['ID'].'  =   Артикул: '.$prop['ARTICUL']['VALUE'].'  =   active ='.$prop['ACTIVE']['VALUE'].'
   		= PROPERTY_HIDE_GENERAL ='.$prop['PROPERTY_HIDE_GENERAL']['VALUE'].'
   		= PROPERTY_NEW_ART_DECO ='.$prop['PROPERTY_NEW_ART_DECO']['VALUE'].'<br>';

   	$el = new CIBlockElement;
	$el->Update($item['ID'], Array(
		"ACTIVE" => "N",    
	));

   	CIBlockElement::SetPropertyValueCode($item['ID'], "HIDE_GENERAL", 'Y');
   	CIBlockElement::SetPropertyValueCode($item['ID'], "NEW_ART_DECO", '');

   	CIBlockElement::SetPropertyValueCode($item['ID'], "NO_ORDER", '');
}


?>