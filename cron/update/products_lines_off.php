<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}


exit;

$arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, 'PROPERTY_LINES' => 'Y');
$res = CIBlockElement::GetList(Array('PROPERTY_ARTICUL'=>'ASC'), $arFilter);
if ($res->selectedRowsCount() > 0) {
	while ($row = $res->GetNextElement()) {
		$item = $row->getFields();

		$prop = $row->getProperties();

	   	echo 'ID:'.$item['ID'].'  =   Артикул: '.$prop['ARTICUL']['VALUE'].' = active ='.$item['ACTIVE'].' = TAGS = '.$item['TAGS'].'<br>';
	   	
	   	//echo $prop['ARTICUL']['VALUE'].'<br>';

	   	
	   	/*$el = new CIBlockElement;
		$el->Update($item['ID'], Array(
			"TAGS" => 'OFF',   
		));*/
	}
}



?>