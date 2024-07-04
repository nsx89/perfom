<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}

exit;
?>

<table>
	<tr>
		<th>Артикул</th>
		<th>Ссылка</th>
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

	$LINK = 'https://'.$_SERVER['SERVER_NAME'].'/'.$item['DETAIL_PAGE_URL'].'/';

	$i++;
	//if ($i > 1) continue;
	?>
		<tr>
			<td><?= $item['NAME'] ?> <?= $ARTICUL ?></td>
			<td><?= $LINK ?></td>
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