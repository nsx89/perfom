<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}

/*

$ARTICUL  = file($_SERVER["DOCUMENT_ROOT"].'/cron/update/off/off.txt');
foreach ($ARTICUL as $line_num => $articul) {
	$articul = preg_replace('/[^0-9.-]/', '', $articul);
	
	if (empty($articul)) continue;

	$arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, 'PROPERTY_ARTICUL' => $articul);
	$res = CIBlockElement::GetList(Array(), $arFilter);
	if ($res->selectedRowsCount() > 0) {
		while ($row = $res->GetNextElement()) {
			$item = $row->getFields();

			$prop = $row->getProperties();

		   	echo 'ID:'.$item['ID'].'  =   Артикул: '.$prop['ARTICUL']['VALUE'].' = active ='.$item['ACTIVE'].' = TAGS = '.$item['TAGS'].'<br>';

		   	//CIBlockElement::SetPropertyValueCode($item['ID'], "SORT", $i);
		   	
		   	$el = new CIBlockElement;
			$el->Update($item['ID'], Array(
				"TAGS" => 'OFF',   
			));
		}
	}
	else {
		$NOT_FOUND[] = $articul;
		echo 'Артикул: '.$articul.' не найден<br>';
	}
}






?>