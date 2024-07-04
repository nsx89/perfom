<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}


exit;
?>

<table>
	<tr>
		<th>Город</th>
		<th>Регион</th>
		<th>Скидка или наценка по региону</th>
	</tr>
<?


$arFilter = Array('IBLOCK_ID' => 7, 'ACTIVE' => 'Y');
$res = CIBlockElement::GetList(Array('NAME' => 'ASC'), $arFilter);
while ($row = $res->GetNextElement()) {
	$item = $row->getFields();
	$prop = $row->getProperties();
	$discountregion = $prop['discountregion']['VALUE'];

	$discount = '';
	$arFilter = Array('IBLOCK_ID' => 8, 'ACTIVE' => 'Y', 'ID' => $discountregion);
    $db_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter);
    $region = $db_list->GetNextElement();
    $region = array_merge($region->GetFields(), $region->GetProperties());
    if ($region) {
        $discount = $region['discount']['VALUE'];
    }
	?>
		<tr>
			<td><?= $item['NAME'] ?></td>
			<td><?= $region['NAME'] ?></td>
			<td><?= str_replace('-', '_+', $discount) ?></td>
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