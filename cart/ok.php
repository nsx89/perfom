<?ini_set('display_errors', 1);
error_reporting(E_ALL);
    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
    if (!CModule::IncludeModule('iblock')) exit;
    $APPLICATION->SetTitle("Оплата прошла успешно");
    $APPLICATION->SetPageProperty("description", "Европласт - производство полиуретановых изделий, лидер на российском рынке");
    if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
        exit;
    }
    require($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");
	
$userName = 'evroplast-api';
$password = 'f2UiCsv6eboArg(x';
	
$PAY_orderID = $_GET['orderId'];
$pay_status = false;

$arFilter = Array("IBLOCK_CODE"=>"keep_order","ACTIVE"=>'Y',"PROPERTY_PAY_orderID"=>$PAY_orderID);
$res = CIBlockElement::GetList(Array(),$arFilter);
if (($item = $res->GetNextElement()) && $PAY_orderID) {
	$item = array_merge($item->GetFields(), $item->GetProperties());
	
	// запрашиваем состояние
$url_status = 'https://securepayments.sberbank.ru/payment/rest/getOrderStatusExtended.do?userName='.$userName.'&password='.$password.'&orderId=';
$url_receiptstatus = 'https://securepayments.sberbank.ru/payment/rest/getReceiptStatus.do?userName='.$userName.'&password='.$password.'&orderId=';


if( $curl = curl_init() ) {
    curl_setopt($curl, CURLOPT_URL, $url_status.$item['PAY_orderID']['VALUE']);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
	curl_setopt($curl, CURLOPT_TIMEOUT,1200);
    $out = json_decode(curl_exec($curl));
	
	$info_test = $out;
	
    curl_close($curl);

	if ($out->orderStatus == 2) { // Оплата прошла
		$pay_status = true;
		if ($item['PAY_STATUS']['VALUE'] == 'Y') {
		
		$date_time_authDateTime = mktime(0,0,0,1,1,1970)+(int)($out->authDateTime/1000+3*3600);
		$date_time_date = mktime(0,0,0,1,1,1970)+(int)($out->date/1000+3*3600);
		$sum = $out->amount/100;	
			
		CIBlockElement::SetPropertyValueCode($item['ID'], 'PAY_TOTAL', $item['PAY_TOTAL']['VALUE']+$sum);
		CIBlockElement::SetPropertyValueCode($item['ID'], 'POST_1C', 'N');
		CIBlockElement::SetPropertyValueCode($item['ID'], 'PAY_STATUS', "N");
		CIBlockElement::SetPropertyValueCode($item['ID'], 'PAYMENT_STATUS', "оплачено");
		
				// Формирование записи чека
				$arFilter_receipt = Array("IBLOCK_CODE"=>"receipt","ACTIVE"=>'Y',"PROPERTY_PAY_orderID"=>$item['PAY_orderID']['VALUE']);
				$res_receipt = CIBlockElement::GetList(Array(),$arFilter_receipt);
				if (!$item_receipt = $res_receipt->GetNextElement()) { // нет записи, формирование маски
					$PROD = array();
					$PROD['PAY_orderID'] = $item['PAY_orderID']['VALUE'];
					$PROD['UUID_PAY'] = $item['UUID_PAY']['VALUE'];
					$PROD['RefOrderUUID'] = $item['UUID']['VALUE'];
					$PROD['PAY_URL'] = $item['PAY_URL']['VALUE'];
					$PROD['orderStatus'] = $out->orderStatus;
					$PROD['authDateTime'] = ConvertTimeStamp($date_time_authDateTime,"FULL");
					$PROD['expirationDate'] = ConvertTimeStamp($date_time_date+1200,"FULL");
					$PROD['Sum'] = $sum;
					$PROD['FNSWebSite'] = 'nalog.ru';
					$PROD['POST_1C'] = 'N';
					$PROD['STATUS'] = 'Y';
					
						$el = new CIBlockElement;
						$resc = CIBlock::GetList(Array(), Array('CODE' => 'receipt'));
						while($arrc = $resc->Fetch())
						{
						$blockid = $arrc["ID"];
						}
					$save_el = Array(
					"IBLOCK_SECTION_ID" => false,
					"IBLOCK_ID"      => $blockid,
					"PROPERTY_VALUES"=> $PROD,
					"NAME"           => $item['ID'],
					"ACTIVE"         => "Y"
					);
					$el->Add($save_el);
					} 	
		}

	} // Оплата прошла
}	// Init
	
$d_info = ''; $d_time = '';
switch ($item["MAIL_DEALER"]['VALUE']) {
		case 'kdvor@decor-evroplast.ru' : { $d_info = 'г. Москва, Каширское ш., д. 19, корп.1, ТК "Каширский двор" 2-й этаж, павильон 2-С90'; $d_time = 'с 9:00 до 21:00'; break;  }
		case 'nahim@decor-evroplast.ru' : { $d_info = 'г. Москва, Нахимовский проспект, д. 24, «ЦДиИ Экспострой на Нахимовском» павильон №3 стенд 195'; $d_time = 'с 10:00 до 20:00'; break; }
		case 'shop@decor-evroplast.ru' : { $d_info = 'г. Москва, Нахимовский проспект, д. 24, «ЦДиИ Экспострой на Нахимовском» павильон №2 стенд 158'; $d_time = 'с 10:00 до 20:00'; break; }
		case 'salonn@decor-evroplast.ru' : { $d_info = 'г. Москва, Нахимовский проспект, д. 24, «ЦДиИ Экспострой на Нахимовском» павильон №3 стенд 49/2'; $d_time = 'с 10:00 до 20:00'; break; }
}

} // item

if ($pay_status) {
?>

    <div class="content-wrapper">
        <section class="cont-success cont-success-ok">
            <div class="order-resp-result">Оплата прошла успешно!</div>
            <div class="order-resp-desc">Спасибо, что выбрали нас!<br>
										Менеджер свяжется с Вами в течении 30&nbsp;минут</div>
            <div class="order-resp-details">
                <div class="succ-nmbr">
                    Заказ <?=$item["NAME"]?>
                </div>
                <div class="order-resp-details-dealer">
                    Принял: фирменный магазин «Европласт»<br>
					<?=$d_info?>
                    </div>
                <div class="order-resp-details-email">
				    Время работы: <?=$d_time?><br>
                    E-mail: <?=$item["MAIL_DEALER"]['VALUE']?><br>
					Телефон: <?=$item["PHONE_DEALER"]['VALUE']?><br>
                </div>
            </div>
            <a class="order-resp-btn succ-cat" href="/catalogue">в каталог</a>
        </section>
    </div>

<?
} else {
?>
<div class="content-wrapper">
    <section class="order-resp-sec">
        <div class="order-resp-details">
        Ошибка системы. Требуется уточнение причины.
        </div>
        <a class="order-resp-btn succ-cat" href="/catalogue">Назад в каталог</a>
    </section>
</div>
<?
}

    require($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");
    if(defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED===true)
    {
        require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog.php");
    }

