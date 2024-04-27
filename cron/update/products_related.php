<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}

/*
$arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, 'ACTIVE' => 'Y');
$res = CIBlockElement::GetList(Array(), $arFilter);
while ($row = $res->GetNextElement()) {
	$item = $row->getFields();
	$prop = $row->getProperties();

	$RELATED = $prop['RELATED']['VALUE'];
	if (empty($RELATED)) {
		continue;
	}

	$RELATED_ARR = explode(',', $RELATED);
    $RELATED_ARTICULS = array();
    foreach ($RELATED_ARR AS $RELATED_ARTICUL) {
        $RELATED_ARTICUL = trim($RELATED_ARTICUL);

        if ($prop['ARTICUL']['VALUE'] == $RELATED_ARTICUL) continue;

        $RELATED_ARTICULS[] = $RELATED_ARTICUL;
    }

	//echo '<br>Артикул:'.$prop['ARTICUL']['VALUE'].'  =   related ='.$RELATED.'<br>';
	
	$RELATED_ARTICULS = array_unique($RELATED_ARTICULS);
	
	if (empty($RELATED_ARTICULS)) continue;

	$NEW_RELATED_ARTICULS = implode(', ', $RELATED_ARTICULS);

	CIBlockElement::SetPropertyValueCode($item['ID'], "RELATED", $NEW_RELATED_ARTICULS);
}
*/


/*
$arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, 'ACTIVE' => 'Y');
$res = CIBlockElement::GetList(Array(), $arFilter);
while ($row = $res->GetNextElement()) {

	$item = $row->getFields();
	$prop = $row->getProperties();

	//echo '<pre>'; print_r($item); exit;

	$ARTICUL = $prop['ARTICUL']['VALUE'];

	//echo $ARTICUL; exit;

	$RELATED = $prop['RELATED']['VALUE'];
	if (empty($RELATED)) {
		continue;
	}

	$RELATED_ARR = explode(',', $RELATED);
    $RELATED_ARTICULS = array();
    foreach ($RELATED_ARR AS $RELATED_ARTICUL) {
        $RELATED_ARTICUL = trim($RELATED_ARTICUL);

        $arFilter2 = Array('IBLOCK_ID' => IB_CATALOGUE, 'PROPERTY_ARTICUL' => $RELATED_ARTICUL);
        $res2 = CIBlockElement::GetList(Array(), $arFilter2);
		while ($row2 = $res2->GetNextElement()) {
			$item2 = $row2->getFields();
			$prop2 = $row->getProperties();

			$ARTICUL2 = $prop2['ARTICUL']['VALUE'];

			//print_r($item2);

			//echo $item2['ID']; exit;
		
			$RELATED_NEW = $RELATED.', '.$ARTICUL;

			CIBlockElement::SetPropertyValueCode($item2['ID'], "RELATED", $RELATED_NEW);
			//exit;
		}

		//exit;
    }

	echo 'ID:'.$item['ID'].'  =   related ='.$RELATED.'<br>';
}


?>