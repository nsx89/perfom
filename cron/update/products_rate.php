<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}

exit;


$arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, 'PROPERTY_ARTICUL' => '6.%', 'ACTIVE');
$res = CIBlockElement::GetList(Array(), $arFilter);
while ($row = $res->GetNextElement()) {
	$item = $row->getFields();
	$prop = $row->getProperties();
	//echo '<pre>'; print_r($prop); echo '</pre>';
   	echo 'ID:'.$item['ID'].'  =   Артикул: '.$prop['ARTICUL']['VALUE'].'                       =                               active ='.$item['ACTIVE'].'= no_order ='.$prop['NO_ORDER']['VALUE'].' = SORT = '.$item['SORT'].'<br>';

   	//CIBlockElement::SetPropertyValueCode($item['ID'], "SORT_FIRST", 1);

   	/*
   	$SORT = abs($item['SORT']);
   	$SORT = '-'.$SORT;

   	$el = new CIBlockElement;
	$el->Update($item['ID'], Array(
		"SORT" => $SORT,   
	));
	*/

	//exit;
}



?>