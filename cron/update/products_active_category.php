<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}


/*
$arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, '!ACTIVE' => 'Y');
$res = CIBlockSection::GetList(Array(), $arFilter, false, Array('UF_*'));
while ($row = $res->GetNextElement()) {

	$item = $row->getFields();

	//echo '<pre>'; print_r($item); exit;

	echo 'ID:'.$item['ID'].'  =   active ='.$item['ACTIVE']['VALUE'].'<br>';

	$el = new CIBlockSection;
	$el->Update($item['ID'], Array(
		"ACTIVE" => 'Y',   
	));

}


?>