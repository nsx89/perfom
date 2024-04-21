<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}


exit;

/*
$arts = array();
$arts[] = '1.50.101';
$arts[] = '1.50.103';
$arts[] = '1.50.154';
$arts[] = '1.50.155';
$arts[] = '1.50.165';
$arts[] = '1.50.172';
$arts[] = '1.53.102';
$arts[] = '1.53.108';
$arts[] = '1.53.103';
$arts[] = '1.53.112';
$arts[] = '1.53.106';
$arts[] = '1.53.101';
$arts[] = '1.53.110';
$arts[] = '1.53.109';
$arts[] = '1.53.108';
$arts[] = '1.53.104';
$arts[] = '1.53.105';
$arts[] = '1.53.107';
$arts[] = '1.51.301';
$arts[] = '1.51.322';
$arts[] = '1.51.308';
$arts[] = '1.51.400';
$arts[] = '1.51.321';
$arts[] = '1.51.302';
$arts[] = '1.51.323';
$arts[] = '1.51.363';
$arts[] = '1.51.362';
$arts[] = '1.51.307';
$arts[] = '1.51.385';
$arts[] = '1.51.384';
*/

$arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, 'PROPERTY_ARTICUL' => $arts, 'ACTIVE');
$res = CIBlockElement::GetList(Array(), $arFilter);
while ($row = $res->GetNextElement()) {
	$item = $row->getFields();
	$prop = $row->getProperties();
	//echo '<pre>'; print_r($prop); echo '</pre>';
   	echo 'ID:'.$item['ID'].'  =   Артикул: '.$prop['ARTICUL']['VALUE'].'                       =                               active ='.$item['ACTIVE'].'= no_order ='.$prop['NO_ORDER']['VALUE'].'<br>';

   	//CIBlockElement::SetPropertyValueCode($item['ID'], "NO_ORDER", '');

   	$el = new CIBlockElement;
	$el->Update($item['ID'], Array(
		"ACTIVE" => "N",   
	));
}



?>