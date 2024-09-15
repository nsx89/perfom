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

$sort = 'sum';
if (isset($_GET['sort'])) $sort = $_GET['sort'];


$arFilter = Array('IBLOCK_ID' => $blockid, 'ACTIVE' => 'Y', array(
        "LOGIC" => "AND",
        array('>=DATE_CREATE' => "01.01.2021 00:00:00"),
        array('<=DATE_CREATE' => "28.10.2021 23:59:59"),
    ));
$db_list = CIBlockElement::GetList(Array(), $arFilter);
while ($fcontact = $db_list->GetNextElement()) {
    $fcontact = array_merge($fcontact->GetFields(), $fcontact->GetProperties());
    $arr = explode(";",$fcontact['request_array']['VALUE']);
	foreach ($arr as $item) {
		if ($item) {
			$art = substr($item,0,8);
			$ext = substr($item,9,3);
			$items[$art][$ext]++;
			$items[$art]['sum']++;
		}
	}

}
	
	$items = array_sort($items, $sort, SORT_DESC);

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
font: normal 14px Arial;
}
</style>
сортировать по 
<a href="/downloads/stat3d_art.php?sort=max"><span>max</span></a>
<a href="/downloads/stat3d_art.php?sort=3ds"><span>3ds</span></a>
<a href="/downloads/stat3d_art.php?sort=gsm"><span>gsm</span></a>
<a href="/downloads/stat3d_art.php?sort=dwg"><span>dwg</span></a>
<a href="/downloads/stat3d_art.php?sort=obj"><span>obj</span></a>
<a href="/downloads/stat3d_art.php?sort=sum"><span>итого</span></a>

<table style="text-align: center; font: normal 14px Arial, Helvetica, sans-serif;">

	<thead>
        <tr>
		<th style="width: 30px; text-align: right;">№</th>
            <th style="width: 200px; text-align: left;">Артикул</th>
		<th style="width: 100px; font-weight: <?=$sort=='max'?'bold':'normal'?>">max</th>
		<th style="width: 100px; font-weight: <?=$sort=='3ds'?'bold':'normal'?>">3ds</th>
		<th style="width: 100px; font-weight: <?=$sort=='gsm'?'bold':'normal'?>">gsm</th>
		<th style="width: 100px; font-weight: <?=$sort=='dwg'?'bold':'normal'?>">dwg</th>
		<th style="width: 100px; font-weight: <?=$sort=='obj'?'bold':'normal'?>">obj</th>
		<th style="width: 100px; font-weight: <?=$sort=='sum'?'bold':'normal'?>">итого</th>
	</tr>
	</thead>

<tbody>

	<? $ii = 1;foreach ($items as $key => $item) { ?>
	<tr>
	<td style="text-align: right;">
	<?=$ii++?>
	</td>
	<td style="text-align: left;">
	<?=$key?>
	</td>
	<td><?=$item['max']?></td>
	<td><?=$item['3ds']?></td>
	<td><?=$item['gsm']?></td>
	<td><?=$item['dwg']?></td>
	<td><?=$item['obj']?></td>
	<td><?=$item['sum']?></td>
	<? } ?>

</tbody>
</table>
