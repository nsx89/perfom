<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}


exit;

$arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, 'PROPERTY_NEW_ART_DECO' => 'Y', 'ACTIVE');
$res = CIBlockElement::GetList(Array(), $arFilter);
while ($row = $res->GetNextElement()) {
	$item = $row->getFields();
	$prop = $row->getProperties();
	//echo '<pre>'; print_r($prop); echo '</pre>';
   	echo 'ID:'.$item['ID'].'  =   Артикул: '.$prop['ARTICUL']['VALUE'].'                       =                               active ='.$item['ACTIVE'].'= no_order ='.$prop['NO_ORDER']['VALUE'].'<br>';

   	//CIBlockElement::SetPropertyValueCode($item['ID'], "NO_ORDER", '');

   	$el = new CIBlockElement;
	$el->Update($item['ID'], Array(
		"ACTIVE" => "Y",   
	));
}



?>