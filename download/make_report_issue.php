<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}

   $resc = CIBlock::GetList(Array(), Array('CODE' => 'questionnaire_3d'));
   while($arrc = $resc->Fetch()) 
   {
      $blockid = $arrc["ID"];
   }

$summ_contact = 0;

$report_10 = array();
$report_05 = array();
$report_01 = array( 
'MAX' =>array(
	'MAX (AutoDesk 3D Studio Max)' => array(
		'2017' => 0,
		'2016' => 0,
		'2015' => 0,
		'2014' => 0,
	),
),
'GSM' =>array(
	'GSM (ArchiCAD)' => array(
		'21' => 0,
		'20' => 0,
		'19' => 0,
		'18' => 0,
	),
),
'DWG' =>array(
	'DWG (AutoCAD)' => array(
		'2018' => 0,
		'2017' => 0,
		'2016' => 0,
		'2015' => 0,
	),
),
'3DS' =>array(
	'3DS (Межплатформенный формат)' => 0,
),
'OBJ' =>array(
	'OBJ (Межплатформенный формат)' => 0,
),

);

$report_true = array(

'q2' => 0,
'q3' => 0,
'q6' => 0,
'q7' => 0,
'q8' => 0,
'q9' => 0,

);

$report_false = array(

'q2' => 0,
'q3' => 0,
'q6' => 0,
'q7' => 0,
'q8' => 0,
'q9' => 0,

);

$report_04 = array();

$arFilter = Array('IBLOCK_ID' => $blockid, 'ACTIVE' => 'Y');
$db_list = CIBlockElement::GetList(Array(), $arFilter);
while ($fcontact = $db_list->GetNextElement()) {
    $fcontact = array_merge($fcontact->GetFields(), $fcontact->GetProperties());
	$summ_contact += 1;

	// q01
	$q01 = htmlspecialchars_decode($fcontact['q1']['VALUE']);
	//$json = preg_replace("#(/\*([^*]|[\r\n]|(\*+([^*/]|[\r\n])))*\*+/)|([\s\t]//.*)|(^//.*)#", '', $q01);
	$arr = json_decode($q01,true); 
	//echo $q01."<br>";
	//print_r($arr);
	//echo '<br>';
	//echo $json[0]['name'].'<br>';

	foreach ($arr as $type_model) {
		$cur_name = '';
		foreach ($type_model as $model) {		
			foreach ($model as $key_name => $name) {
				//echo $key_name.' : '.$name.'<br>';
					if ($key_name == 'name') {
					$cur_name = $name;
					}
				
				if (($key_name == 'versions') && (stripos($cur_name,'max') !==false)) {
					foreach ($name as $ver) {
					$report_01['MAX'][$cur_name][$ver] += 1;
					}
				}
				if (($key_name == 'versions') && (stripos($cur_name,'gsm') !==false)) {
					foreach ($name as $ver) {
					$report_01['GSM'][$cur_name][$ver] += 1;
					}
				}
				if (($key_name == 'versions') && (stripos($cur_name,'dwg') !==false)) {
					foreach ($name as $ver) {
					$report_01['DWG'][$cur_name][$ver] += 1;
					}
				}
				if (($key_name == 'name') && (stripos($cur_name,'3ds') !==false)) {
					$report_01['3DS'][$cur_name] += 1;
					//echo $cur_name.' - '.$ver.'<br>';
				}
				if (($key_name == 'name') && (stripos($cur_name,'obj') !==false)) {
					$report_01['OBJ'][$cur_name] += 1;
					//echo $cur_name.' - '.$ver.'<br>';
				}
				
			}
		}
	}

	$q05 = htmlspecialchars_decode($fcontact['q5']['VALUE']);
	$arr = json_decode($q05,true);
	//print_r($arr); echo '<br>';
	$q05_other = '';
	foreach ($arr as $key => $model) {
	if ((stripos($model,'Формат:') !==false) || (stripos($model,'Программа:') !==false)) $q05_other.= $model.' ';
	else $report_05[$model]	+= 1;
	}
	if (strlen($q05_other) > 0)$report_05_other .= '('.$q05_other.' : '.$fcontact['region']['VALUE'].') ';

	if ($fcontact['q2']['VALUE'] == 'Y') $report_true['q2'] += 1; else $report_false['q2'] += 1;	
	if ($fcontact['q3']['VALUE'] == 'Y') $report_true['q3'] += 1; else $report_false['q3'] += 1;
	if ($fcontact['q6']['VALUE'] == 'Y') $report_true['q6'] += 1; else $report_false['q6'] += 1;
	if ($fcontact['q7']['VALUE'] == 'Y') $report_true['q7'] += 1; else $report_false['q7'] += 1;
	if ($fcontact['q8']['VALUE'] == 'Y') $report_true['q8'] += 1; else $report_false['q8'] += 1;
	if ($fcontact['q9']['VALUE'] == 'Y') $report_true['q9'] += 1; else $report_false['q9'] += 1;

	$report_04[$fcontact['q4']['VALUE']] += 1;
	
	if ($fcontact['q10']['VALUE']) $report_10[] = $fcontact['q10']['VALUE'];
	

}
//echo $report_05_other.'<br>';

echo '<snan style="text-align: center; font: normal 14px Arial, Helvetica, sans-serif;">Пользователей ответивших на вопросы по 3D моделям: '.$summ_contact.'</snan><br>';
?>
<style type="text/css">
td {  
border-width: 0px;
border-bottom: 1px #f89200 solid;
border-right: 1px #f89200 dotted;
padding: 6px 12px;
}

th {
border-width: 0px;
border-bottom: 1px #f89200 solid;
border-right: 1px #f89200 dotted;
padding: 6px 12px;
}
</style>

<table style="text-align: center; font: normal 14px Arial, Helvetica, sans-serif;">

	<thead>
        <tr>
		<th style="width: 30px; text-align: right;">№</th>
            	<th style="width: 100px; text-align: center;">тип</th>
		<th style="width: 250px; text-align: left;">название</th>
		<th style="width: 200px; text-align: left;">версия</th>
		<th style="width: 200px; text-align: right;">количество пользователей</th>
	</tr>
	</thead>

<tbody>
<?
$ii = 0;
foreach ($report_01 as $key_up => $type_model) {
	$ii++;
	if (($key_up == '3DS') || ($key_up == 'OBJ')) {
		foreach ($type_model as $key => $item) { ?>
			<tr>
			<td style="text-align: right;">
			<?=$ii++?>
			</td>
			<td style="text-align: center;">
			<?=$key_up?>
			</td>
			<td style="text-align: left;">
			<?=$key?>
			</td>
			<td style="text-align: left;">
			 -
			</td>
			<td style="text-align: right;">
			<?=$item?>
			</td>
			</tr>
			
	<?	}
	} else {
     		foreach ($type_model as $key => $item) {
			foreach ($item as $key2 => $ver) { ?>
			<? if (!preg_match("/^([0-9])+$/",$key2)) continue; ?>
			<tr>
			<td style="text-align: right;">
			<?=$ii++?>
			</td>
			<td style="text-align: center;">
			<?=$key_up?>
			</td>
			<td style="text-align: left;">
			<?=$key?>
			</td>
			<td style="text-align: left;">
			<?=$key2?>
			</td>
			<td style="text-align: right;">
			<?=$ver?>
			</td>
			</tr>
		<?	}
		}
	}
}
?>
</tbody>
</table>
<br>
<table style="text-align: center; font: normal 14px Arial, Helvetica, sans-serif;">

	<thead>
        <tr>
		<th style="width: 500px; text-align: left;">вопрос</th>
		<th style="width: 200px; text-align: right;">количество "Да"</th>
		<th style="width: 200px; text-align: right;">количество "Нет"</th>
	</tr>
	</thead>
<tbody>
<tr><td style="text-align: left;"><?='Используете ли Вы Autodesk Revit?'?>
</td><td style="text-align: right;"><?=$report_true['q2']?></td><td style="text-align: right;"><?=$report_false['q2']?></td></tr>	

<tr><td style="text-align: left;"><?='Планируете ли Вы использовать Autodesk Revit в будущем?'?>
</td><td style="text-align: right;"><?=$report_true['q3']?></td><td style="text-align: right;"><?=$report_false['q3']?></td></tr>

<tr><td style="text-align: left;"><?='Хотели ли бы вы в дополнение к каждой модели иметь 2D-чертеж в формате DWG?'?>
</td><td style="text-align: right;"><?=$report_true['q6']?></td><td style="text-align: right;"><?=$report_false['q6']?></td></tr>

<tr><td style="text-align: left;"><?='Хотели ли бы вы в дополнение к каждой модели иметь низкополигональную версию?'?>
</td><td style="text-align: right;"><?=$report_true['q7']?></td><td style="text-align: right;"><?=$report_false['q7']?></td></tr>

<tr><td style="text-align: left;"><?='Хотели ли бы вы в дополнение к каждой модели иметь упрощенную геометрическую версию (без рисунков и орнаментов)?'?>
</td><td style="text-align: right;"><?=$report_true['q8']?></td><td style="text-align: right;"><?=$report_false['q8']?></td></tr>

<tr><td style="text-align: left;"><?='Хотели ли бы вы иметь возможность посмотреть видеоурок по работе с базой и различными форматами?'?>
</td><td style="text-align: right;"><?=$report_true['q2']?></td><td style="text-align: right;"><?=$report_false['q9']?></td></tr>
</tbody>
</table>
<br>

<table style="text-align: center; font: normal 14px Arial, Helvetica, sans-serif;">

	<thead>
        <tr>
		<th style="width: 300px; text-align: left;">Оценка качества моделей по шкале:</th>
		<th style="width: 50px; text-align: center;">1</th>
		<th style="width: 50px; text-align: center;">2</th>
		<th style="width: 50px; text-align: center;">3</th>
		<th style="width: 50px; text-align: center;">4</th>
		<th style="width: 50px; text-align: center;">5</th>
		<th style="width: 50px; text-align: center;">6</th>
		<th style="width: 50px; text-align: center;">7</th>
		<th style="width: 50px; text-align: center;">8</th>
		<th style="width: 50px; text-align: center;">9</th>
		<th style="width: 50px; text-align: center;">10</th>
	</tr>
	</thead>
<tbody>

<tr>
<td style="text-align: left;">количество оценок</td>
<? for ($i=1; $i<=10; $i++) { ?>
	<td style="text-align: center;">
	<? if ($report_04[$i]) echo $report_04[$i];
	else echo '-'; ?>
	</td>
<? } ?>
</tr>
</tbody>
</table>
<br>
<table style="text-align: center; font: normal 14px Arial, Helvetica, sans-serif;">

	<thead>
        <tr>
		
		<th style="width: 600px; text-align: left;">Популярные форматы корорые хотят видеть среди моделей</th>
		<th style="width: 200px; text-align: right;">количество пользователей</th>
	</tr>
	</thead>

<tbody>
<?
foreach ($report_05 as $key => $model) { ?>
			<tr>
			<td style="text-align: left;">
			<?=$key?>
			</td>
			<td style="text-align: right;">
			<?=$model?>
			</td>
			</tr>
<? } ?>
<tr><td colspan="2" style="text-align: left;">Другие форматы: <?=$report_05_other?> </td></tr>

</tbody>
</table>
<br>
<table style="text-align: center; font: normal 14px Arial, Helvetica, sans-serif;">

	<thead>
        <tr>
		
		<th style="width: 1000px; text-align: left;">Комментарии</th>
		
	</tr>
	</thead>

<tbody>
<?
foreach ($report_10 as $coment) { ?>
			<tr>
			<td style="text-align: left;">
			<?=$coment?>
			</td>
			</tr>
<? } ?>

</tbody>
</table>
<br>
<br>

