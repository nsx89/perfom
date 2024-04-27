<?

function array_sort($array, $on, $order=SORT_ASC)
{
    $new_array = array();
    $sortable_array = array();

    if (count($array) > 0) {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    if ($k2 == $on) {
                        $sortable_array[$k] = $v2;
                    }
                }
            } else {
                $sortable_array[$k] = $v;
            }
        }

        switch ($order) {
            case SORT_ASC:
                asort($sortable_array);
            break;
            case SORT_DESC:
                arsort($sortable_array);
            break;
        }

        foreach ($sortable_array as $k => $v) {
            $new_array[$k] = $array[$k];
        }
    }

    return $new_array;
}

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}

   $resc = CIBlock::GetList(Array(), Array('CODE' => '3d_email'));
   while($arrc = $resc->Fetch()) 
   {
      $blockid = $arrc["ID"];
   }

$items = array();
$_max = 0;
$_gsm = 0;
$_dwg = 0;
$_3ds = 0;
$_obj = 0;


$arFilter = Array('IBLOCK_ID' => $blockid, 'ACTIVE' => 'Y', array(
        "LOGIC" => "AND",
        array('>=DATE_CREATE' => "01.01.2021 00:00:00"),
        array('<=DATE_CREATE' => "28.10.2021 23:59:59"),
    ));
$db_list = CIBlockElement::GetList(Array(), $arFilter);
while ($fcontact = $db_list->GetNextElement()) {
    $fcontact = array_merge($fcontact->GetFields(), $fcontact->GetProperties());
    //$items[] = $fcontact;
    $email = $fcontact['EMAIL']['VALUE'];
    //$items[$email] = $email;
    if (!isset($items[$email]['FIO']))
    	$items[$email]['FIO'] = $fcontact['FIO']['VALUE'];
    if ($fcontact['region']['VALUE']) 
    	$items[$email]['region'] = $fcontact['region']['VALUE'];
    	else $items[$email]['region'] = ' - ';
    
    if ((!isset($items[$email]['distribution_item'])) || ($fcontact['distribution_item']['VALUE'] == 'Y'))
    $items[$email]['distribution_item'] = $fcontact['distribution_item']['VALUE'];

    if ((!isset($items[$email]['distribution'])) || ($fcontact['distribution']['VALUE'] == 'Y'))
    $items[$email]['distribution'] = $fcontact['distribution']['VALUE'];

    $items[$email]['data'] = $fcontact['DATE_CREATE'];
    $items[$email]['data_t'] = strtotime($fcontact['DATE_CREATE']);

	$items[$email]['max'] += substr_count($fcontact['request_array']['VALUE'],'max');
	$items[$email]['gsm'] += substr_count($fcontact['request_array']['VALUE'],'gsm');
	$items[$email]['dwg'] += substr_count($fcontact['request_array']['VALUE'],'dwg');
	$items[$email]['3ds'] += substr_count($fcontact['request_array']['VALUE'],'3ds');
	$items[$email]['obj'] += substr_count($fcontact['request_array']['VALUE'],'obj');

	$_max += substr_count($fcontact['request_array']['VALUE'],'max');
	$_gsm += substr_count($fcontact['request_array']['VALUE'],'gsm');
    	$_dwg += substr_count($fcontact['request_array']['VALUE'],'dwg');
	$_3ds += substr_count($fcontact['request_array']['VALUE'],'3ds');
	$_obj += substr_count($fcontact['request_array']['VALUE'],'obj');
    
}
 $items = array_sort($items, 'data_t');

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
            <th style="width: 200px; text-align: left;">e-mail</th>
		<th style="width: 150px;">дата</th>
		<th style="width: 150px;">обнов. продукта</th>
		<th style="width: 150px;">обнов. 3d мод.</th>
		<th style="width: 200px; text-align: left;">Имя</th>
		<th style="width: 200px; text-align: left;">Регион</th>
		<th style="width: 100px;">max</th>
		<th style="width: 100px;">3ds</th>
		<th style="width: 100px;">gsm</th>
		<th style="width: 100px;">dwg</th>
		<th style="width: 100px;">obj</th>
	</tr>
	</thead>

<tbody>
	<? $ii = 1; foreach ($items as $key => $item) { ?>
	<tr>
	<td style="text-align: right;">
	<?=$ii++?>
	</td>
	<td style="text-align: left;">
	<?//=$key?>
	<?	$change_str = "";
		for ($x=0; $x++<strlen($key)-8;) {$change_str .= "*";}

		echo substr_replace($key,$change_str,4,count($key)-7);
	?>
	</td>
	<td>
	<?=$item['data']?>
	<?//=FormatDateFromDB($item['data'], 'SHORT')?>
	</td>
	<td>
	<?=$item['distribution_item']?>
	</td>
	<td>
	<?=$item['distribution']?>
	</td>
	<td style="text-align: left;">
	<?=$item['FIO']?>
	</td>
	<td style="text-align: left;">
	<?=$item['region']?>
	</td>
	<td><?=$item['max']?></td>
	<td><?=$item['3ds']?></td>
	<td><?=$item['gsm']?></td>
	<td><?=$item['dwg']?></td>
	<td><?=$item['obj']?></td>

	</tr>
	<?}?>
	</tr>
	<td colspan="7" style="text-align: right;">
	итого:  <?=$_max+$_3ds+$_gsm+$_dwg+$_obj?>
	</td>
	<td><?=$_max?></td>
	<td><?=$_3ds?></td>
	<td><?=$_gsm?></td>
	<td><?=$_dwg?></td>
	<td><?=$_obj?></td>
	</tr>
 </tbody>
</table>

