<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}

?>

<table>
	<tr>
		<th>Дата заказа</th>
		<th>Номер заказа</th>
		<th>Регион</th>
		<th>Десктоп/Моб.</th>
		<th>Телефон</th>
		<th>E-mail</th>
		<th>Имя</th>
		<th>Сумма со скидкой</th>
		<th>Валюта</th>
		<th>Тип оплаты</th>
		<th>Дилер</th>
		<th>Тел. Дилер</th>
		<th>Статус заказа</th>
	</tr>
<?
$arFilter = Array('IBLOCK_ID' => 43);
$res = CIBlockElement::GetList(Array('ID'=>'DESC'), $arFilter);
while ($row = $res->GetNextElement()) {
	$item = $row->getFields();
	$prop = $row->getProperties();
	
	//echo '<pre>'; print_r($prop); echo '</pre>';
   	
   	$CHOOSEN_REG = $prop['CHOOSEN_REG'];
   	$REGION = '';
   	if (!empty($CHOOSEN_REG)) {
		$region_res = CIBlockElement::GetList(Array('ID'=>'DESC'), Array('IBLOCK_ID' => 7, 'ID' => $CHOOSEN_REG));
		$region_row = $region_res->GetNextElement();
		$region_item = $region_row->getFields();
		$REGION = $region_item['NAME'];
   	}
	?>
		<tr>
			<td><?= date('d.m.Y', strtotime($item['DATE_CREATE'])) ?></td>
			<td><?= $item['NAME'] ?></td>
			<td><?= $REGION ?></td>
			<td><?= $prop['VERS']['VALUE'] ?></td>
			<td><?= $prop['USER_PHONE']['VALUE'] ?></td>
			<td><?= $prop['USER_MAIL']['VALUE'] ?></td>
			<td><?= $prop['USER_NAME']['VALUE'] ?></td>
			<td><?= !empty($prop['TOTAL_SALE']['VALUE']) ? $prop['TOTAL_SALE']['VALUE'] : $prop['TOTAL']['VALUE']  ?></td>
			<td><?= $prop['CURR']['VALUE'] ?></td>
			<td><?= $prop['PAYMENT']['VALUE'] ?></td>
			<td><?= $prop['MAIL_DEALER']['VALUE'] ?></td>
			<td><?= $prop['PHONE_DEALER']['VALUE'] ?></td>
			<td><?= $prop['STATUS']['VALUE'] ?></td>
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