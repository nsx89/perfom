<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/include/phone.php");
global $USER, $mb5_crc;

$currency_infо = get_currency_info($loc['country']['VALUE']);
$curr_abbr = $currency_infо['abbr'];


$date = date('d.m.Y H:i:s');
$order = array();
$res = getObjectItems();
$cart = $res['items'];
$money = 0;
$sample_money = 0;
$samplesCount = 0;

foreach ($cart as $citem) {
    if($citem['COUNT'] > 0) {
        if ($citem['COMPOSITEPART']['VALUE']) {
            $ids = $citem['COMPOSITEPART']['VALUE'];
            $ids['LOGIC'] = 'OR';
            //$arFilter = Array('IBLOCK_ID' => $iblockid, 'ACTIVE' => 'Y', 'ID' => $ids);
            $arFilter = Array('IBLOCK_ID' => $iblockid, 'ID' => $ids);
            $db_list = CIBlockElement::GetList(Array("SORT" => "ASC"), $arFilter);
            $parts = array();
            while ($ob = $db_list->GetNextElement()) {
                $ob = array_merge($ob->GetFields(), $ob->GetProperties());
                $cost = _makeprice(CPrice::GetBasePrice($ob['ID']));
                $ob['price'] = $cost['PRICE'];
                $arr = array();
                $arr['id'] = $ob['ID'];
                $arr['qty'] = $citem['COUNT'];
                $arr['price'] = $ob['price'];
                $PROD = array();
                $PROD['ORDER_NUMBER'] = $number_r;
                $PROD['ORDER_DATE'] = $date;
                $PROD['QTY'] = $citem['COUNT'];
                $PROD['PRICE'] = $ob['price'];
                $PROD['PROD_PRICE'] = $ob['price'];
                $PROD['INNERCODE'] = $ob['INNERCODE']['VALUE'];
                if($citem['prodId'] != '') $PROD['INNERCODE'] = $ob['SAMPLE_CODE']['VALUE'];
                if($curr_abbr != 'RUB') {
                    $base_price = CPrice::GetBasePrice($ob['ID']);
                    $part['base_price'] = $base_price['PRICE'];
                    $PROD['BASE_PRICE'] = $base_price['PRICE'];
                }
                array_push($order, $arr);
                $el = new CIBlockElement;
                $save_prod = Array(
                    "IBLOCK_SECTION_ID" => false,
                    "IBLOCK_ID"      => 44,
                    "PROPERTY_VALUES"=> $PROD,
                    "NAME"           => $ob['ID'],
                    "ACTIVE"         => "Y"
                );
                $el->Add($save_prod);
                if($citem['prodId'] != '') {
                    $sample_money += $citem['COUNT']*$citem['price'];
                    $samplesCount++;
                } else {
                    $money += $citem['COUNT']*$citem['price'];
                }
            }
            $arr['parts'] = $parts;
        } else {
            $arr = array();
            $arr['id'] = $citem['ID'];
            $arr['qty'] = $citem['COUNT'];
            $arr['price'] = $citem['price'];
            $PROD = array();
            $PROD['ORDER_NUMBER'] = $number_r;
            $PROD['ORDER_DATE'] = $date;
            $PROD['QTY'] = $citem['COUNT'];
            $PROD['PRICE'] = $citem['price'];
            $PROD['INNERCODE'] = $citem['INNERCODE']['VALUE'];
            if($citem['prodId'] != '') $PROD['INNERCODE'] = $citem['SAMPLE_CODE']['VALUE'];
            if($curr_abbr != 'RUB') {
                $base_price = CPrice::GetBasePrice($citem['ID']);
                $arr['base_price'] = $base_price['PRICE'];
                $PROD['BASE_PRICE'] = $base_price['PRICE'];
            }
            array_push($order, $arr);
            $el = new CIBlockElement;
            $resc = CIBlock::GetList(Array(), Array('CODE' => 'order_products'));
            while($arrc = $resc->Fetch())
            {
                $blockid = $arrc["ID"];
            }
            $save_prod = Array(
                "IBLOCK_SECTION_ID" => false,
                "IBLOCK_ID"      => $blockid,
                "PROPERTY_VALUES"=> $PROD,
                "NAME"           => $citem['ID'],
                "ACTIVE"         => "Y"
            );
            $el->Add($save_prod);
            if($citem['prodId'] != '') {
                $sample_money += $citem['COUNT']*$citem['price'];
                $samplesCount++;
            } else {
                $money += $citem['COUNT']*$citem['price'];
            }
        }
    }
}

$PROP = array();

$PROP['DATE'] = $date;
if(isset($cart_dealer)) {
    $PROP['ID_DEALER'] = $cart_dealer['ID'];
    $PROP['MAIL_DEALER'] = $email;
    $PROP['PHONE_DEALER'] = $phone;
}
elseif($email_number > 0) {
    //if($email_number == 1) $email_number = count($email_manager);
    $PROP['ID_DEALER'] = "";
    $PROP['MAIL_DEALER'] = $email_manager[$email_number];
    $PROP['PHONE_DEALER'] = $email_info[$email_number];
}
else {
    $PROP['MAIL_DEALER'] = "store@decor-evroplast.ru";
}
$discount =  __discount_mob($money);
$PROP['AUTO_REG'] = $ip_loc['ID'];
$PROP['CHOOSEN_REG'] = $new_loc == '3109' ? '3109' : $loc['ID'];
$PROP['IP_USER'] = GetIP();
$PROP['BROWSER'] = $_SERVER['HTTP_USER_AGENT'];
$PROP['VERS'] = 'desktop';
$PROP['PRODUCTS'] = json_encode($order);
$PROP['CURR'] = $curr_abbr;

$PROP['STATUS'] = 'new';

if($discount['discount'] != 0) {
    $PROP['SALE'] = $discount['discount'];
    $PROP['SALE_SUM'] = $discount['discount_price'];
    $PROP['TOTAL_SALE'] = $discount['total']+$sample_money;
}

$PROP['TOTAL'] = $money+$sample_money;
$PROP['DELIVERY'] = $_REQUEST['del'];
$PROP['PAYMENT'] = $_REQUEST['payment'];
$PROP['RECEIVING'] = $_REQUEST['receiving'];
$PROP['USER_NAME'] = $_REQUEST['name'];
$PROP['USER_LAST_NAME'] = $_REQUEST['lastname'];
$PROP['USER_MAIL'] = $_REQUEST['email'];
$PROP['USER_PHONE'] = $_REQUEST['phone'];
$PROP['USER_CITY'] = $_REQUEST['city'];
$PROP['USER_STREET'] = $_REQUEST['street'];
$PROP['USER_HOUSE'] = $_REQUEST['house'];
$PROP['USER_APRT'] = $_REQUEST['aprt'];
$PROP['DELIVERY_KM'] = $_REQUEST['km'];
$PROP['mb5'] = $mb5_crc;
$PROP['MOUNTING'] = $_REQUEST['mounting'];
$PROP['PERFOM'] = 'Y';

$delivery_price = 0;
if($_REQUEST['km'] != '' && $_REQUEST['del'] == 'del') {
    $onlySamples = $samplesCount == count($cart) ? true : false;
    $delivery = countDelivery($_REQUEST['km'],$discount['total']+$sample_money,$onlySamples);
    $delivery_price = $delivery['del'];
    $PROP['TOTAL_SALE'] = $delivery['total'];
}
$PROP['DELIVERY_PRICE'] = $delivery_price;
$PROP['USER_NOTE'] = str_replace(array("\r\n","\r","\n","\\r","\\n","\\r\\n"),"<br/>",$_REQUEST['comment']);
if($USER->IsAuthorized()) {
    $PROP['CLIENT_ID'] = $USER->GetID();
} else {
    $PROP['CLIENT_ID'] = $old_user_id; // из файла save_user
}

if (($loc['CODE'] == 'moskva') || ($loc['CODE'] == 'moskovskaya-oblast')) {
$PROP['POST_1C'] = 'Y';	//На передачу
}
$order_uuid =  generateUUID(); // Генерация кода  UUID для всех регионов
$PROP['UUID'] = $order_uuid;

if($_REQUEST['payment'] == 'online') {
	$PROP['PAY_DateBegin'] = date("d.m.Y H:i:s");	
}

$el = new CIBlockElement;

$resc = CIBlock::GetList(Array(), Array('CODE' => 'keep_order'));
while($arrc = $resc->Fetch())
{
    $blockid = $arrc["ID"];
}

$save_order = Array(
    "IBLOCK_SECTION_ID" => false,
    "IBLOCK_ID"      => $blockid,
    "PROPERTY_VALUES"=> $PROP,
    "NAME"           => $number_r,
    "ACTIVE"         => "Y"
);
$order_id = $el->Add($save_order);

//сохраняем адрес доставки в кабинет пользователя

if($_REQUEST['save'] == 'Y') {

    $user_id = $USER->GetID();

    $user = new CUser;
    $fields = Array(
        "PERSONAL_CITY"     => $_REQUEST['city'],
        "PERSONAL_ZIP"      => $_REQUEST['street'],
        "PERSONAL_STREET"   => $_REQUEST['house'],
        "PERSONAL_MAILBOX"  => $_REQUEST['aprt'],

    );
    $user->Update($user_id, $fields);

}

if($_REQUEST['payment'] == 'online') {
    /*if( $curl_link = curl_init() ) {
        curl_setopt($curl_link, CURLOPT_URL, $G_HTTPS.$_SERVER['HTTP_HOST'].'/ajax/sberbank.php?id='.$order_id);
        curl_setopt($curl_link, CURLOPT_RETURNTRANSFER,true);
        $payment_link = curl_exec($curl_link);
        curl_close($curl_link);
    }*/
    $payment_link = '/cart/pay.php?id='.$order_uuid;
    if($payment_link != '') {
        $dealer .= '<p style="margin-bottom:10px;">Для онлайн оплаты заказа перейдите по ссылке: </p>';
        $dealer .= '<a href="https://'.$_SERVER['HTTP_HOST'].$payment_link.'" style="background:#849795; color:#fff;text-decoration:none;display:block;width:185px;height:32px;line-height:32px;text-align:center;">Оплатить заказ</a><br>';
    } else {
        $dealer.= '<p style="margin-bottom:10px;max-width:720px;"><b>При формировании ссылки на оплату произошла ошибка. Для оплаты онлайн зайдите в ваш личный кабинет на сайте, выберите необходимый заказ и нажмите кнопку "Онлайн оплата".</b></p>';
    }
}


?>