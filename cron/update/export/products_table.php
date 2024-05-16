<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}

exit;
?>

<table>
	<tr>
		<th>IE_XML_ID</th>
		<th>Наименование</th>
		<th>&nbsp;&nbsp;&nbsp;&nbsp;</th>
		<th>Артикул</th>
		<th>Гибкий</th>
		<th>Длина детали, мм</th>
		<th>Ширина, мм</th>
		<th>Высота, мм</th>
		<th>Глубина, мм</th>
		<th>Ширина по потолку, мм</th>
		<th>Высота по стене, мм</th>
		<th>Ширина по полу, мм</th>
		<th>Вертикаль, мм</th>
		<th>Горизонталь, мм</th>
		<th>Диаметр, мм</th>
		<th>Радиус, мм</th>
		<th>Толщина, мм</th>
		<th>Радиус изгиба выпуклый, мм</th>
		<th>Радиус изгиба вогнутый, мм</th>
		<th>Радиус изгиба арочный, мм</th>
		<th>Цена RUB</th>
		<th>Площ. Монт. См2</th>
		<th>Площ. Стык. См2</th>
		<th>IC_GROUP0</th>
		<th>IC_GROUP1</th>
		<th>IC_GROUP2</th>
	</tr>
<?

if (!empty($_GET['all'])) {
	$CATALOG_FILTER = Array('IBLOCK_ID' => IB_CATALOGUE, "!TAGS" => "OFF");
}
else {
	$CATALOG_FILTER = Array('IBLOCK_ID' => IB_CATALOGUE, 'INCLUDE_SUBSECTIONS' => 'Y', "ACTIVE" => "Y", "ACTIVE_DATE" => "Y", "TAGS" => "Y");
}
$res = CIBlockElement::GetList(Array('PROPERTY_ARTICUL'=>'ASC'), $CATALOG_FILTER);
$i = 0;
while ($row = $res->GetNextElement()) {
	$item = $row->getFields();
	$prop = $row->getProperties();
	$ARTICUL = $prop['ARTICUL']['VALUE'];

	$i++;
	//if ($i > 1) continue;

	$PRICE = CPrice::GetBasePrice($item['ID'])['PRICE'];

	$SECTIONS = ___get_product_sections_all($item, true);
	$SECTIONS = array_reverse($SECTIONS);
	$IC_GROUP0 = $SECTIONS[0];
	$IC_GROUP1 = $SECTIONS[1];
	$IC_GROUP2 = $SECTIONS[2];
	?>
		<tr>
			<td><?= $item['ID'] ?></td>
			<td><?= $item['NAME'] ?> <?= $ARTICUL ?></td>
			<td></td>
			<td><?= $ARTICUL ?></td>
			<td><?= $prop['FLEX']['VALUE'] ?></td>
			<td><?= $prop['S2']['VALUE'] ?></td>
			<td><?= $prop['S9']['VALUE'] ?></td>
			<td><?= $prop['S10']['VALUE'] ?></td>
			<td><?= $prop['S11']['VALUE'] ?></td>
			<td><?= $prop['S1']['VALUE'] ?></td>
			<td><?= $prop['S3']['VALUE'] ?></td>
			<td><?= $prop['S8']['VALUE'] ?></td>
			<td><?= $prop['S12']['VALUE'] ?></td>
			<td><?= $prop['S13']['VALUE'] ?></td>
			<td><?= $prop['S14']['VALUE'] ?></td>
			<td><?= $prop['S15']['VALUE'] ?></td>
			<td><?= $prop['S7']['VALUE'] ?></td>
			<td><?= $prop['S4']['VALUE'] ?></td>
			<td><?= $prop['S5']['VALUE'] ?></td>
			<td><?= $prop['S6']['VALUE'] ?></td>
			<td><?= number_format($PRICE, 2, '.', '') ?></td>
			<td><?= $prop['SQUARE_M']['VALUE'] ?></td>
			<td><?= $prop['SQUARE_S']['VALUE'] ?></td>
			<td><?= $IC_GROUP0 ?></td>
			<td><?= $IC_GROUP1 ?></td>
			<td><?= $IC_GROUP2 ?></td>
		</tr>
	<?
}
?>
</table>

<style>
table {
	border-collapse: collapse;
}
table th, table td {
	border: 1px solid #a9a9a9;
	padding: 2px 5px;
}
</style>