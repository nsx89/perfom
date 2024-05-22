<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}


/*
1577 - подоконные элементы
1574 - наличники
1610 - оконные обрамления -> дополнительные элементы
*/

/*
ID:5957 = Артикул: 4.82.001 = active =Y= TAGS =Y= CATEGORY =1577 = NEW CATEGORY =1623
ID:5958 = Артикул: 4.82.002 = active =Y= TAGS =Y= CATEGORY =1623 = NEW CATEGORY =1623
ID:5959 = Артикул: 4.82.003 = active =Y= TAGS =Y= CATEGORY =1623 = NEW CATEGORY =1623
ID:6658 = Артикул: 4.82.031 = active =Y= TAGS =Y= CATEGORY =1610 = NEW CATEGORY =1625
ID:43862 = Артикул: 4.82.032 = active =Y= TAGS =Y= CATEGORY =1610 = NEW CATEGORY =1625
ID:43868 = Артикул: 4.82.033 = active =Y= TAGS =Y= CATEGORY =1610 = NEW CATEGORY =1625
ID:5960 = Артикул: 4.82.101 = active =Y= TAGS =Y= CATEGORY =1577 = NEW CATEGORY =1623
ID:43863 = Артикул: 4.82.131 = active =Y= TAGS =Y= CATEGORY =1610 = NEW CATEGORY =1625
ID:5961 = Артикул: 4.82.201 = active =Y= TAGS =Y= CATEGORY =1623 = NEW CATEGORY =1623
ID:5962 = Артикул: 4.82.202 = active =Y= TAGS =Y= CATEGORY =1577 = NEW CATEGORY =1623
ID:43864 = Артикул: 4.82.231 = active =Y= TAGS =Y= CATEGORY =1610 = NEW CATEGORY =1625
ID:43865 = Артикул: 4.82.232 = active =Y= TAGS =Y= CATEGORY =1610 = NEW CATEGORY =1625
ID:5963 = Артикул: 4.82.301 = active =Y= TAGS =Y= CATEGORY =1577 = NEW CATEGORY =1623
ID:5964 = Артикул: 4.82.302 = active =Y= TAGS =Y= CATEGORY =1577 = NEW CATEGORY =1623
ID:43867 = Артикул: 4.82.331 = active =Y= TAGS =Y= CATEGORY =1610 = NEW CATEGORY =1625
ID:43866 = Артикул: 4.82.332 = active =Y= TAGS =Y= CATEGORY =1610 = NEW CATEGORY =1625
ID:5945 = Артикул: 4.84.001 = active =Y= TAGS =Y= CATEGORY =1574 = NEW CATEGORY =1624
ID:5946 = Артикул: 4.84.002 = active =Y= TAGS =Y= CATEGORY =1574 = NEW CATEGORY =1624
ID:5947 = Артикул: 4.84.003 = active =Y= TAGS =Y= CATEGORY =1574 = NEW CATEGORY =1624
ID:6211 = Артикул: 4.84.004 = active =Y= TAGS =Y= CATEGORY =1574 = NEW CATEGORY =1624
ID:6201 = Артикул: 4.84.005 = active =Y= TAGS =Y= CATEGORY =1574 = NEW CATEGORY =1624
ID:6212 = Артикул: 4.84.006 = active =Y= TAGS =Y= CATEGORY =1574 = NEW CATEGORY =1624
ID:5948 = Артикул: 4.84.051 = active =Y= TAGS =Y= CATEGORY =1574 = NEW CATEGORY =1624
ID:5949 = Артикул: 4.84.052 = active =Y= TAGS =Y= CATEGORY =1574 = NEW CATEGORY =1624
ID:5950 = Артикул: 4.84.053 = active =Y= TAGS =Y= CATEGORY =1574 = NEW CATEGORY =1624
*/

/*
$ARTS = array();
$ARTS[] = '4.82.001';
$ARTS[] = '4.82.002';
$ARTS[] = '4.82.003';
$ARTS[] = '4.82.031';
$ARTS[] = '4.82.032';
$ARTS[] = '4.82.033';
$ARTS[] = '4.82.033';
$ARTS[] = '4.82.101';
$ARTS[] = '4.82.131';
$ARTS[] = '4.82.201';
$ARTS[] = '4.82.202';
$ARTS[] = '4.82.231';
$ARTS[] = '4.82.232';
$ARTS[] = '4.82.301';
$ARTS[] = '4.82.302';
$ARTS[] = '4.82.331';
$ARTS[] = '4.82.332';
$ARTS[] = '4.84.001';
$ARTS[] = '4.84.002';
$ARTS[] = '4.84.003';
$ARTS[] = '4.84.004';
$ARTS[] = '4.84.005';
$ARTS[] = '4.84.006';
$ARTS[] = '4.84.051';
$ARTS[] = '4.84.052';
$ARTS[] = '4.84.053';

$arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, 'PROPERTY_ARTICUL' => $ARTS);
$res = CIBlockElement::GetList(Array(), $arFilter);
while ($row = $res->GetNextElement()) {
	$item = $row->getFields();
	$prop = $row->getProperties();

	$SECTION_ID = '';

	switch ($item['IBLOCK_SECTION_ID']) {
		case '1577': case '1623': 
			$SECTION_ID = 1623;
			break;
		case '1574': 
			$SECTION_ID = 1624;
			break;
		case '1610': 
			$SECTION_ID = 1625;
			break;
	}

	switch ($item['IBLOCK_SECTION_ID']) {
		case '1625': 
			$SECTION_ID = 1623;
			break;
		default:
			$SECTION_ID = $item['IBLOCK_SECTION_ID'];
			break;
	}

   	echo 'ID:'.$item['ID'].'  =   Артикул: '.$prop['ARTICUL']['VALUE'].'  =   active ='.$item['ACTIVE'].'= TAGS ='.$item['TAGS'].'= CATEGORY ='.$item['IBLOCK_SECTION_ID'].' = NEW CATEGORY ='.$SECTION_ID.'<br>';

   	$el = new CIBlockElement;
	$el->Update($item['ID'], Array(
		"IBLOCK_SECTION_ID" => $SECTION_ID,   
	));

	//exit;
}


?>