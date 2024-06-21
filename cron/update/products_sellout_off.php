<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}


exit;

/*
6.50.701
6.50.702
6.50.703
6.50.704
6.50.705
6.50.706
6.50.708
6.51.704
6.51.705
6.51.706
6.51.708
6.53.702
6.53.702
6.53.703
6.53.703
6.53.704
6.53.704
*/

echo '<table>';
$arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, "PROPERTY_SELLOUT" => 'Y');
$res = CIBlockElement::GetList(Array('PROPERTY_ARTICUL' => 'ASC'), $arFilter);
while ($row = $res->GetNextElement()) {
	$item = $row->getFields();
	$prop = $row->getProperties();

	//if ($item['TAGS'] == 'OFF') continue;

	echo '<tr>
		<td>'.$prop['ARTICUL']['VALUE'].'</td>
		<td>'.$prop['SELLOUT']['VALUE'].'</td>
		<td>'.$item['TAGS'].'</td>
	</tr>';

	//echo $prop['ARTICUL']['VALUE'].'<br>';

	/*$el = new CIBlockElement;
	$el->Update($item['ID'], Array(
		"TAGS" => 'OFF',   
	));*/
}
echo '</table>';

echo '<style>
table {
	border-collapse: collapse;
}
table td {
	border: 1px solid #a9a9a9;
	padding: 5px 5px;
}
</style>';
?>