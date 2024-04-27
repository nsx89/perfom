<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}

echo '<table>';
$arFilter = Array('IBLOCK_ID' => IB_CATALOGUE);
$res = CIBlockElement::GetList(Array('PROPERTY_ARTICUL' => 'ASC'), $arFilter);
while ($row = $res->GetNextElement()) {
	$item = $row->getFields();
	$prop = $row->getProperties();

	$section_name = '';
	$arFilter2 = Array('IBLOCK_ID' => IB_CATALOGUE, "ID" => $item['IBLOCK_SECTION_ID']);
	$res2 = CIBlockSection::GetList(Array(), $arFilter2);
	while ($row2 = $res2->GetNextElement()) {
		$item2 = $row2->getFields();
		$section_name = $item2['NAME'];
	}

	//if (empty($section_name)) continue;

	echo '<tr>
		<td>'.$prop['ARTICUL']['VALUE'].'</td>
		<td>'.$section_name.'</td>
	</tr>';

	//if ($prop['ARTICUL']['VALUE'] == '0-1.61.101-1.61.110') continue;
	//if ($prop['ARTICUL']['VALUE'] == '0-1.61.102-1.61.120') continue;

   	//echo 'ID:'.$item['ID'].'  =   Артикул: '.$prop['ARTICUL']['VALUE'];
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