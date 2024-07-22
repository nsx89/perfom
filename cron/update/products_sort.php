<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}

exit;



$NOT_FOUND = array();


$i = 5000;
$g = -100000;

$ARTICUL  = file($_SERVER["DOCUMENT_ROOT"].'/cron/update/sort/sort.txt');
foreach ($ARTICUL as $line_num => $articul) {
	$articul = preg_replace('/[^0-9.-]/', '', $articul);
	
	if (empty($articul)) continue;

	$arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, 'PROPERTY_ARTICUL' => $articul);
	$res = CIBlockElement::GetList(Array(), $arFilter);
	if ($res->selectedRowsCount() > 0) {
		while ($row = $res->GetNextElement()) {
			$item = $row->getFields();
			$prop = $row->getProperties();

		   	echo 'ID:'.$item['ID'].'  =   Артикул: '.$prop['ARTICUL']['VALUE'].' = active ='.$item['ACTIVE'].' = SORT = '.$i.' = VAL = '.$item['SORT'].' = NEW SORT = '.$g.'<br>';

		   	CIBlockElement::SetPropertyValueCode($item['ID'], "SORT", $i);
		   	
		   	$el = new CIBlockElement;
			$el->Update($item['ID'], Array(
				"SORT" => $g,   
			));

			//exit;
		}
	}
	else {
		$NOT_FOUND[] = $articul;
		echo 'Артикул: '.$articul.' не найден<br>';
	}

	$i -= 10;
	$g += 100;
}



$i = 5000;

$ARTICUL  = file($_SERVER["DOCUMENT_ROOT"].'/cron/update/sort/sort1.txt');
foreach ($ARTICUL as $line_num => $articul) {
	$articul = preg_replace('/[^0-9.-]/', '', $articul);
	
	if (empty($articul)) continue;

	$arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, 'PROPERTY_ARTICUL' => $articul);
	$res = CIBlockElement::GetList(Array(), $arFilter);
	if ($res->selectedRowsCount() > 0) {
		while ($row = $res->GetNextElement()) {
			$item = $row->getFields();
			$prop = $row->getProperties();
		
		   	echo 'ID:'.$item['ID'].'  =   Артикул: '.$prop['ARTICUL']['VALUE'].' =  active ='.$item['ACTIVE'].'= no_order ='.$prop['NO_ORDER']['VALUE'].' = SORT = '.$i.'<br>';

		   	CIBlockElement::SetPropertyValueCode($item['ID'], "SORT1", $i);

			//exit;
		}
	}
	else {
		$NOT_FOUND[] = $articul;
		echo 'Артикул: '.$articul.' не найден<br>';
	}

	$i -= 10;
}



$i = 5000;

$ARTICUL  = file($_SERVER["DOCUMENT_ROOT"].'/cron/update/sort/sort2.txt');
foreach ($ARTICUL as $line_num => $articul) {
	$articul = preg_replace('/[^0-9.-]/', '', $articul);
	
	if (empty($articul)) continue;

	$arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, 'PROPERTY_ARTICUL' => $articul);
	$res = CIBlockElement::GetList(Array(), $arFilter);
	if ($res->selectedRowsCount() > 0) {
		while ($row = $res->GetNextElement()) {
			$item = $row->getFields();
			$prop = $row->getProperties();

		   	echo 'ID:'.$item['ID'].'  =   Артикул: '.$prop['ARTICUL']['VALUE'].'   =   active ='.$item['ACTIVE'].'= no_order ='.$prop['NO_ORDER']['VALUE'].' = SORT = '.$i.'<br>';

		   	CIBlockElement::SetPropertyValueCode($item['ID'], "SORT2", $i);

			//exit;
		}
	}
	else {
		$NOT_FOUND[] = $articul;
		echo 'Артикул: '.$articul.' не найден<br>';
	}

	$i -= 10;
}


$i = 5000;

$ARTICUL  = file($_SERVER["DOCUMENT_ROOT"].'/cron/update/sort/sort3.txt');
foreach ($ARTICUL as $line_num => $articul) {
	$articul = preg_replace('/[^0-9.-]/', '', $articul);
	
	if (empty($articul)) continue;

	$arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, 'PROPERTY_ARTICUL' => $articul);
	$res = CIBlockElement::GetList(Array(), $arFilter);
	if ($res->selectedRowsCount() > 0) {
		while ($row = $res->GetNextElement()) {
			$item = $row->getFields();
			$prop = $row->getProperties();

		   	echo 'ID:'.$item['ID'].'  =   Артикул: '.$prop['ARTICUL']['VALUE'].'  =   active ='.$item['ACTIVE'].'= no_order ='.$prop['NO_ORDER']['VALUE'].' = SORT = '.$i.'<br>';

		   	CIBlockElement::SetPropertyValueCode($item['ID'], "SORT3", $i);

			//exit;
		}
	}
	else {
		$NOT_FOUND[] = $articul;
		echo 'Артикул: '.$articul.' не найден<br>';
	}

	$i -= 10;
}



$i = 5000;

$ARTICUL  = file($_SERVER["DOCUMENT_ROOT"].'/cron/update/sort/sort4.txt');
foreach ($ARTICUL as $line_num => $articul) {
	$articul = preg_replace('/[^0-9.-]/', '', $articul);
	
	if (empty($articul)) continue;

	$arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, 'PROPERTY_ARTICUL' => $articul);
	$res = CIBlockElement::GetList(Array(), $arFilter);
	if ($res->selectedRowsCount() > 0) {
		while ($row = $res->GetNextElement()) {
			$item = $row->getFields();
			$prop = $row->getProperties();
			
		   	echo 'ID:'.$item['ID'].'  =   Артикул: '.$prop['ARTICUL']['VALUE'].'   =   active ='.$item['ACTIVE'].'= no_order ='.$prop['NO_ORDER']['VALUE'].' = SORT = '.$i.'<br>';

		   	CIBlockElement::SetPropertyValueCode($item['ID'], "SORT4", $i);

			//exit;
		}
	}
	else {
		$NOT_FOUND[] = $articul;
		echo 'Артикул: '.$articul.' не найден<br>';
	}

	$i -= 10;
}


echo '=====================<br>';

$NOT_FOUND = array_unique($NOT_FOUND);

foreach ($NOT_FOUND AS $art) {
	echo $art.'<br>';
}


?>