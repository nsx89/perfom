<?require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");

if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}

global $iblockid;
$iblockid = 12;

$cart = json_decode($_COOKIE['basket']); 

$res = array();

    $all_price = 0;
    $all_sample_price = 0;
    $count = 0;
    foreach ($cart as $citem) {
        $tempId = '';
        $citemId = $citem->id;
        $isSample = false;
        if(strpos($citem->id,'s') !== false) {
            $citemId = substr($citem->id, 1);
            $tempId = $citem->id;
            $isSample = true;
        }
        //$arFilter = Array('IBLOCK_ID' => $iblockid, 'ACTIVE' => 'Y', 'ID' => $citemId);
        $arFilter = Array('IBLOCK_ID' => $iblockid, 'ID' => $citemId);
        $db_list = CIBlockElement::GetList(Array("SORT" => "ASC"), $arFilter);
        $ob = $db_list->GetNextElement();
        if (!$ob) continue;
	$ob = array_merge($ob->GetFields(), $ob->GetProperties());
	if($isSample) {
	    $sample_price = $ob['SAMPLE_PRICE']['VALUE'] == '' ? DEFAULT_SAMPLE_PRICE : $ob['SAMPLE_PRICE']['VALUE'];
        $all_sample_price += round($sample_price*$citem->qty,2);
    } else {
        $all_price += round(__get_product_cost($ob)*$citem->qty,2);
    }
    $count += $citem->qty;
    }

    $res['all_price'] = __cost_format($all_price + $all_sample_price);
    $res['count'] = $count;

    // Для активного региона
    $discount = __discount_mob($all_price);
    $res['discount'] = $discount['discount'];
    $res['discount_price'] = __cost_format($discount['discount_price']);
    if($discount['discount'] == 0 || $discount['discount'] == null) {
        $res['total'] =  __cost_format($all_price + $all_sample_price);
    } else {
        $res['total'] =  __cost_format($discount['total'] + $all_sample_price);
    }

print json_encode($res);

?>
