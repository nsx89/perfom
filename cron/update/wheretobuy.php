<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}

/*
$arFilter = Array('IBLOCK_ID' => 6, 'PROPERTY_phones' => '%br%');
$res = CIBlockElement::GetList(Array(), $arFilter);
while ($row = $res->GetNextElement()) {
	$item = $row->getFields();
	$prop = $row->getProperties();
		
	$phones = $prop['phones']['VALUE'];
	//$phones = str_replace('&lt;br&gt;', '', $phones);
	$phones = str_replace('br', '', $phones);
	$phones = str_replace('&', '', $phones);
	$phones = str_replace('amp;', '', $phones);
	$phones = str_replace('lt;', '', $phones);
	$phones = str_replace('gt;', '', $phones);

	echo $item['ID'].' = '.$prop['phones']['VALUE'].' ==== '.$phones.'<br>';

	if (!empty($phones)) {
		CIBlockElement::SetPropertyValueCode($item['ID'], "phones", $phones);
	}

	//exit;
}

?>