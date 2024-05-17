<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}

exit;

/*

$i = 1;
$ARTICUL  = file($_SERVER["DOCUMENT_ROOT"].'/cron/update/list/arts.txt');
foreach ($ARTICUL as $line_num => $articul) {
	$articul = preg_replace('/[^0-9.-]/', '', $articul);
	
	if (empty($articul)) continue;

	$arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, 'PROPERTY_ARTICUL' => $articul);
	$res = CIBlockElement::GetList(Array(), $arFilter);
	if ($res->selectedRowsCount() > 0) {
		while ($row = $res->GetNextElement()) {
			$item = $row->getFields();
			$prop = $row->getProperties();

			//echo '<pre>'; print_r($prop); echo '</pre>';

			$SEARCH = trim($item['TAGS']) == 'OFF' ? 'OFF' : '';

		   	echo '№'.$i.'. - ID:'.$item['ID'].'  -   Артикул: '.$prop['ARTICUL']['VALUE'].'  - В поиске ='.$SEARCH.'<br>';
		}
	}
	else {
		echo 'Не найдено  =   Артикул: '.$articul.' <br>';
	}

	$i++;
}


?>