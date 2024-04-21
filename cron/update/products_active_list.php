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

	//echo '<pre>'; print_r($item); echo '</pre>'; exit;

	$SECTION_ID = $item['IBLOCK_SECTION_ID'];

	$SECTION_PAGE_URL = '';

   	$arFilter = Array('IBLOCK_ID'=>IB_CATALOGUE, 'ID' => $item['IBLOCK_SECTION_ID']);
	$db_list = CIBlockSection::GetList(Array(), $arFilter, false, array('UF_*'));
	$section_item = $db_list->GetNext();

	if (!empty($section_item) && $section_item['IBLOCK_SECTION_ID'] == 1614) {
		$arFilter = Array('IBLOCK_ID'=>IB_CATALOGUE, 'ID' => $section_item['IBLOCK_SECTION_ID']);
		$db_list = CIBlockSection::GetList(Array(), $arFilter, false, array('UF_*'));
		$section_item_main = $db_list->GetNext();
		$SECTION_PAGE_URL = $section_item_main['SECTION_PAGE_URL'];
	}

	if (!empty($SECTION_PAGE_URL)) {
		$SECTION_PAGE_URL = '/'.trim($SECTION_PAGE_URL, '/');
	}

	$SECTION_ID_NEW = 0;
	if ($section_item['IBLOCK_SECTION_ID'] == 1614) {
		switch ($SECTION_ID) {
			case 1615: $SECTION_ID_NEW = 1542; break;
			case 1616: $SECTION_ID_NEW = 1544; break;
			case 1617: $SECTION_ID_NEW = 1546; break;
		}
	}

	echo 'https://'.$_SERVER['SERVER_NAME'].$SECTION_PAGE_URL.$item['DETAIL_PAGE_URL'].'/'.$section_item['IBLOCK_SECTION_ID'].' => '.$SECTION_ID_NEW.'<br>';

	if (!empty($SECTION_ID_NEW)) {
		$el = new CIBlockElement;
		$el->Update($item['ID'], Array(
			"IBLOCK_SECTION_ID" => $SECTION_ID_NEW,   
		));
	}
	//exit;

	//exit;
}



?>