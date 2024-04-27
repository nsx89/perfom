<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}

echo '<table>';
$arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, "ACTIVE" => "Y", "ACTIVE_DATE" => "Y", "TAGS" => "Y", 'INCLUDE_SUBSECTIONS' => 'Y');
$res = CIBlockElement::GetList(Array('PROPERTY_ARTICUL' => 'ASC'), $arFilter);
while ($row = $res->GetNextElement()) {
	$item = $row->getFields();
	$prop = $row->getProperties();

	$section_name = '';
	$arFilter2 = Array('IBLOCK_ID' => IB_CATALOGUE, "ID" => $item['IBLOCK_SECTION_ID'], "ACTIVE" => 'Y');
	$res2 = CIBlockSection::GetList(Array(), $arFilter2);
	while ($row2 = $res2->GetNextElement()) {
		$item2 = $row2->getFields();
		$section_name = $item2['NAME'];
	}

	if (empty($section_name)) continue;

	if ($item['IBLOCK_SECTION_ID'] == '1587') {
		if (!in_array($prop['ARTICUL']['VALUE'], ['E01.M.290', 'E02.S.290', 'E03.S.60', 'E04.U.290', 'E12.S.290', 'E13.S.60'])) {
			continue;
		}
	}

	if ($section_name == 'декоративные элементы') continue;
	if ($section_name == 'подоконные элементы') continue;
	if ($section_name == 'дополнительные элементы') continue;
	if ($section_name == 'наличники') continue;
	if ($section_name == 'арочные обрамления') continue;

	echo '<tr>
		<td>'.$prop['ARTICUL']['VALUE'].'</td>
		<td>'.$section_name.'</td>
	</tr>';

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