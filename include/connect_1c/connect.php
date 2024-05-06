<?
$_SERVER["DOCUMENT_ROOT"] = '/home/bitrix/www';

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS",true);
define('CHK_EVENT', true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php" );

@set_time_limit(0);
@ignore_user_abort(true);

if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
exit;
}

/*
function generateUUID() {
        $uuid = '';
        $uuid .= sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),

            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,

            // 48 bits for "node"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );

    return ($uuid);
}
*/

$SiteID = '4ec55941-480d-42ea-b410-45680124abcd'; 

$discount1C = array('' =>'ВР0000001',
'0'=>'ВР0000001',
'3'=>'ВР0000014',
'5'=>'ВР0000002',
'7'=>'ВР0000013',
'10'=>'ВР0000003',
'13'=>'ВР0000020',
'15'=>'ВР0000004',
'18'=>'ВР0000026',
'20'=>'ВР0000005',
'11'=>'ВР0000022',
'12'=>'ВР0000023',
'14'=>'ВР0000021',
'16'=>'ВР0000024',
'17'=>'ВР0000025',
'19'=>'ВР0000027',
'23'=>'ВР0000119',
'25'=>'ВР0000006',
'27'=>'ВР0000120',
'30'=>'ВР0000007',
'34'=>'ВР0000245',
'35'=>'ВР0000008',
'36'=>'ВР0000249',
'37'=>'ВР0000140',
'32.2'=>'ВР0000016',
'40'=>'ВР0000009',
'42'=>'ВР0000117',
'43'=>'ВР0000111',
'43.5'=>'ВР0000237',
'45'=>'ВР0000010',
'46'=>'ВР0000116',
'48'=>'ВР0000029',
'49'=>'ВР0000015',
'49.15'=>'ВР0000019',
'50'=>'ВР0000011',
);

// запрос
function SoapRequest($xml_post_string) {
$soapUrl = "https://ese.decor-evroplast.ru:4848/Work/ESE.1cws";
$soapUser = "ese_user";
$soapPassword = "yaDWcjx8k%HT!Snd";

//$xml_post_string = iconv('UTF-8','windows-1251',$xml_post_string);

ob_start();
echo $xml_post_string;
$response = ob_get_contents();
$Clength = ob_get_length();
ob_end_clean();
	//$dom = new DomDocument('1.0', 'UTF-8');
    //$dom->preserveWhiteSpace = false;
    //$dom->loadXML($response); 
	//$response = $dom->saveXML(); 

echo $response;
//echo strlen($response);

$headers = array(
                        "Content-Type: application/soap+xml; charset=utf-8;",
						"Accept-Charset: utf-8",
						"Content-Language: ru",
						"Content-Charset: utf-8",
						"Accept-Language: ru-RU",
                        "Accept: text/xml",
                        "Cache-Control: no-cache",
                        "Pragma: no-cache",
                        //"SOAPAction: \"run\"",
                        //"Content-length: ".$Clength, // Не выдавать, сомнительно при кодировках
                    );
					
			$ch = curl_init();
			//curl_setopt($ch, CURLOPT_VERBOSE, 1);
			//curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
			
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1); 
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);  
			curl_setopt($ch, CURLOPT_SSLCERT, $_SERVER["DOCUMENT_ROOT"] . "/include/connect_1c/client.pem"); 
			curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM'); 
			curl_setopt($ch, CURLOPT_SSLKEY, $_SERVER["DOCUMENT_ROOT"] . "/include/connect_1c/key.pem"); 
            curl_setopt($ch, CURLOPT_URL, $soapUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERPWD, $soapUser.":".$soapPassword);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $response);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

           //converting
            $response = curl_exec($ch); 
			$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			echo curl_getinfo($ch, CURLINFO_HEADER_OUT);
            curl_close($ch);   

return array('res' => $response, 'code' => $code);
}

// Конвертор даты под формат
function ConvDate1C($date) {
			$date = explode(" ",$date);
			$date_rev = explode(".",$date[0]);
			if (!$date[1]) $date[1] = '00:00:00';
			$data_doc = $date_rev[2].'-'.$date_rev[1].'-'.$date_rev[0].'T'.$date[1];
			
return $data_doc;
}

// connect
function connect_SoapRequest() {
global $SiteID; 

$xml_post_string =
'<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:evr="http://www.evroplast.ru/XDTO/EvroplastSiteExchange">
   <soap:Header/>
   <soap:Body>
      <evr:Connect>
         <evr:SiteID>'.$SiteID.'</evr:SiteID>
      </evr:Connect>
   </soap:Body>
</soap:Envelope>';

$result = SoapRequest($xml_post_string);

$xml_back_string=htmlspecialchars_decode($result['res']);

echo 'res: '.iconv('windows-1251','UTF-8',$result['res']).'<BR>';
echo 'code: '.$result['code'].'<BR><BR>';	
	
}

// передача заказа
function order_SoapRequest($item, $UUID = null) { // $UUID под вопросом
global $SiteID; 
global $discount1C;   

/*
$xml_post_string = 
'<?xml version="1.0" encoding="UTF-8"?>';*/
$xml_post_string = 
'<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:evr="http://www.evroplast.ru/XDTO/EvroplastSiteExchange" xmlns:ns="http://www.evroplast.ru/XDTO/EvroplastSiteExchange/1.0.0.0">
<soap:Header/>
<soap:Body>
<evr:PostDocs>
<evr:SiteID>'.$SiteID.'</evr:SiteID>
<evr:Document>';

	// Заказ или заказы - пока по конкретно одному заказу. // !!! DeliveryAddress увеличить длинну если реально Тип строки маленький
	$xml_post_string .= '
	<ns:Orders>';
	$xml_post_string .= '<ns:Order UUID="'.$UUID.'" Number="'.(int)$item['NAME'].'" Date="'.ConvDate1C($item['DATE']['VALUE']).'">';
	$xml_post_string .= '<ns:DeliveryAddress>'.'г.'.$item['USER_CITY']['VALUE'].' ул.'.$item['USER_STREET']['VALUE'].' д.'.$item['USER_HOUSE']['VALUE'].' кв.'.$item['USER_APRT']['VALUE'].'</ns:DeliveryAddress>';
	$xml_post_string .= '<ns:ClientName>'.$item["USER_NAME"]["VALUE"].'</ns:ClientName>';
	$xml_post_string .= '<ns:Comment>'.htmlspecialchars_decode(strip_tags($item['USER_NOTE']['~VALUE']['TEXT'])).'</ns:Comment>';
	$xml_post_string .= '<ns:Phone>'.$item['USER_PHONE']['VALUE'].'</ns:Phone>';
	$xml_post_string .= '<ns:Email>'.$item['USER_MAIL']['VALUE'].'</ns:Email>';
	//$xml_post_string .= '<ns:DeliveryDate>'.ConvDate1C($item['DATE']['VALUE']).'</ns:DeliveryDate>'; // Дата доставки надо отработать
		
	$xml_post_string .= '<ns:Goods>';
		$clear_sample = 0;
		$arFilter = Array("IBLOCK_CODE"=>"order_products","PROPERTY_ORDER_NUMBER"=>$item['NAME'],"ACTIVE"=>"Y");
        $res = CIBlockElement::GetList(Array(),$arFilter);
		while($product = $res->GetNextElement()) {
            $product = array_merge($product->GetFields(), $product->GetProperties());
			
			// фильтр образца
			if ($product['NAME'][0] == 's') { // образец
			$s = true; $product['NAME'] = preg_replace("/[^,.0-9]/", '', $product['NAME']);
			if ($clear_sample == 0) $clear_sample = 1;
			} else { 
			$s = false; $clear_sample = 2;
			}
			
            $arProdFilter = Array("IBLOCK_ID"=>12,"ID"=>$product['NAME'],"ACTIVE"=>"Y");
            $resProd = CIBlockElement::GetList(Array(),$arProdFilter);
            $prod_arr = $resProd->GetNextElement();
            $prod_arr = array_merge($prod_arr->GetFields(), $prod_arr->GetProperties());
			$discount = $item['SALE']['VALUE'];
			
			if ($prod_arr['FLEX']['VALUE'] == 'Y') { // for flex
				$ob_cost = $product['PRICE']['VALUE'];
				$discount_cost = ceil($ob_cost - $ob_cost/100*$discount);
				// $ob_cost = $product['PRICE']['VALUE']/2;
				// $discount_cost = ceil($ob_cost - $ob_cost/100*$discount)*2;
			} else {
			$ob_cost = $product['PRICE']['VALUE'];
			$discount_cost = ceil($ob_cost - $ob_cost/100*$discount);
			if($prod_arr['ID'] == '6497' || $prod_arr['ID'] == '6501') $discount_cost = $ob_cost; //если монтажный комплект
			}
			if ($s) { // смена кода передачи если образец
				$prod_arr['INNERCODE']['VALUE'] = $prod_arr['SAMPLE_CODE']['VALUE']; 
				$discount_cost = $ob_cost; // без скидки
			}
			$ob_cost = $discount_cost;
            $total = $ob_cost*$product['QTY']['VALUE'];
			
		$xml_post_string .= '<ns:Good Code="'.$prod_arr['INNERCODE']['VALUE'].'" Quantity="'.$product['QTY']['VALUE'].'" Price="'.$ob_cost.'" Sum="'.$total.'"/>';	
		
		}
		/* Доставка - ВР002029961 – Доставка внутри МКАД цена 800 руб за ед., ВР000000009 – Доставка за Мкад, км – 30 руб за ед. */
		/* if ($item["DELIVERY_PRICE"]["VALUE"] > 0) {
			$d_start = 0; $d_end = 0;
			if ($item["TOTAL_SALE"]["VALUE"]) $price = $item["TOTAL_SALE"]["VALUE"]; // нехорошее место нянется, надо учитывать
			else $price = $item["TOTAL"]["VALUE"];
			if ($price < 30000) $d_start = 800;
			$d_end = $item["DELIVERY_PRICE"]["VALUE"] - $d_start;
			
			if ($d_start) $xml_post_string .= '<ns:Good Code="ВР002029961" Quantity="1" Price="'.$d_start.'" Sum="'.$d_start.'"/>';
			if ($d_end) $xml_post_string .= '<ns:Good Code="ВР000000009" Quantity="'.($d_end/30).'" Price="30" Sum="'.$d_end.'"/>';
		} */
		/* доставка */
		
		/* Доставка 2020 - ВР099022180, тариф 1 000руб., ВР099022182, тариф 30 руб. за км., ВР099022181, тариф 50 руб. за км. */
		if ($item["DELIVERY_PRICE"]["VALUE"] > 0) {
			$d_start = 0; $d_end = 0;
		if ($clear_sample == 1) { // чистые образцы
			if ($item["DELIVERY_KM"]["VALUE"] > 0) { // за мкад по 50
				$xml_post_string .= '<ns:Good Code="ВР099022181" Quantity="'.$item["DELIVERY_KM"]["VALUE"].'" Price="50" Sum="'.($item["DELIVERY_KM"]["VALUE"]*50).'"/>';
			} else { // до мкада по 500
				$xml_post_string .= '<ns:Good Code="ВР099022726" Quantity="1" Price="500" Sum="500"/>';
			}
			
		} else { // стандарт передача 
			if ($item["TOTAL_SALE"]["VALUE"]) $price = $item["TOTAL_SALE"]["VALUE"]; // нехорошее место нянется, надо учитывать
			else $price = $item["TOTAL"]["VALUE"];
			if (($item["TOTAL"]["VALUE"] - $item["SALE_SUM"]["VALUE"]) < 10000) {
				$d_start = 1000;
				$d_end = $item["DELIVERY_PRICE"]["VALUE"] - $d_start;
				$xml_post_string .= '<ns:Good Code="ВР099022180" Quantity="1" Price="'.$d_start.'" Sum="'.$d_start.'"/>';
				if ($d_end) $xml_post_string .= '<ns:Good Code="ВР099022181" Quantity="'.($d_end/50).'" Price="50" Sum="'.$d_end.'"/>';
			} else {
				$d_end = $item["DELIVERY_PRICE"]["VALUE"];
				if ($d_end) $xml_post_string .= '<ns:Good Code="ВР099022182" Quantity="'.($d_end/30).'" Price="30" Sum="'.$d_end.'"/>';
			}
		}	
		}
		/* доставка 2020 */
		
	$xml_post_string .= '</ns:Goods>';

	$xml_post_string .= '<ns:PriceType Code="'.$discount1C[$item['SALE']['VALUE']].'"/>';  
   
   // Закрытие Order / Orders
   $xml_post_string .= '
   </ns:Order>
   </ns:Orders>';
   
   // Оплата
   
   $arFilter_receipt = Array("IBLOCK_CODE"=>"payment","ACTIVE"=>'Y',"NAME"=>$item['ID'],"PROPERTY_POST_1C"=>'Y');
   $res_receipt = CIBlockElement::GetList(Array(),$arFilter_receipt); 
   $res_receipt_count = $res_receipt->SelectedRowsCount();
   if ($item_receipt = $res_receipt->GetNextElement()) {
	   $item_receipt = array_merge($item_receipt->GetFields(), $item_receipt->GetProperties());
	   
	   $uuid_pay = $item_receipt['UUID_PAY']['VALUE'];
	   if ($item_receipt['QR_ID']['VALUE']) $PaymentType = 'ЧХ0000007';
	   else $PaymentType = 'ЧХ0000005';
	   
	   
	   $xml_post_string .=
	   '<ns:Payments>
			<ns:Payment UUID="'.$uuid_pay.'" 
			Date="'.ConvDate1C($item_receipt['Date_Trans']['VALUE']).'" 
			RefOrderUUID="'.$item["UUID"]["VALUE"].'" 
			PaymentID="'.$item_receipt['PAY_orderID']['VALUE'].'" 
			errorCodeReceipt="0" 
			expirationDate="'.ConvDate1C($item_receipt['expirationDate']['VALUE']).'" 
			orderStatus="2" 
			ReceiptStatus="0" 
			PaymentType="'.$PaymentType.'">';
											
		$xml_post_string .=	
						'<ns:Sum>'.$item_receipt['Sum']['VALUE'].'</ns:Sum>
						<ns:formUrl>'.$item_receipt['PAY_URL']['VALUE'].'</ns:formUrl>';
						
		if ($item_receipt['QR_ID']['VALUE']) $xml_post_string .= '<ns:QR_ID>'.$item_receipt['QR_ID']['VALUE'].'</ns:QR_ID>';
	
		/*
		if (($item_receipt['errorCodeReceipt']['VALUE'] == 0) && ($item_receipt['receiptStatus']['VALUE'] == 1)) {
		$xml_post_string .=				
                '<ns:ParametersOfCheck NumberCheck="'.$item_receipt['NumberCheck']['VALUE'].'" 
				SumReceipt="'.$item_receipt['SumReceipt']['VALUE'].'" 
				FiscalSign="'.$item_receipt['FiscalSign']['VALUE'].'" 
				KKTNumber="'.$item_receipt['KKTNumber']['VALUE'].'" 
				FNSerialNumber="'.$item_receipt['FNSerialNumber']['VALUE'].'" 
				FNSWebSite="nalog.ru" 
				FDNumber="'.$item_receipt['FDNumber']['VALUE'].'" 
				UUID="'.$item_receipt['UUID_receipt']['VALUE'].'" 
				DateReceipt="'.ConvDate1C($item_receipt['DateReceipt']['VALUE']).'"/>';
		}
		*/
		$xml_post_string .=
			'</ns:Payment>
		</ns:Payments>';
		
   }
 
$xml_post_string .= '
</evr:Document>
</evr:PostDocs>
</soap:Body>
</soap:Envelope>';   

//echo $xml_post_string;



$result = SoapRequest($xml_post_string);

$xml_back_string=htmlspecialchars_decode($result['res']);

echo 'res: '.iconv('windows-1251','UTF-8',$result['res']).'<BR>';
//echo 'res: '.$result['res'].'<BR>';
echo 'code: '.$result['code'].'<BR><BR>';

$soap_back = array();
preg_match_all('|<IsSuccess>(.+)</IsSuccess>|isU', $xml_back_string, $arr); $soap_back['IsSuccess'] = $arr[1][0];
preg_match_all('|<Description>(.+)</Description>|isU', $xml_back_string, $arr); $soap_back['Description'] = $arr[1][0];
$soap_back['Description'] = str_replace(PHP_EOL, '', $soap_back['Description']);
preg_match_all('|<UUID>(.+)</UUID>|isU', $xml_back_string, $arr); $soap_back['UUID'] = $arr[1];

$log_1s =  'Дата: '.ConvertTimeStamp(time(),"FULL").PHP_EOL;
$log_1s .= 'IsSuccess : '.$soap_back['IsSuccess'].PHP_EOL;
$log_1s .= 'Description : '.$soap_back['Description'].PHP_EOL;

foreach ($soap_back['UUID'] as $soap_uuid) {
$log_1s .= 'UUID : '.$soap_uuid.PHP_EOL;	
}
$log_1s .= PHP_EOL;

echo $res_receipt_count;

$log_1s_last = $item['log_1s']['~VALUE']['TEXT'];

CIBlockElement::SetPropertyValueCode($item['ID'], 'log_1s', $log_1s_last.$log_1s);

if (($result['code'] == '200') && ($soap_back['IsSuccess'] == 'true')) { 
		
		if ($res_receipt_count <= 1) {
		CIBlockElement::SetPropertyValueCode($item['ID'], 'POST_1C', 'N'); // Сброс флага передачи
		CIBlockElement::SetPropertyValueCode($item['ID'], 'EXCHANGE_1C', 'Принято');
		}
		CIBlockElement::SetPropertyValueCode($item_receipt['ID'], 'POST_1C', 'N'); // Сброс флага передачи
} else { 
CIBlockElement::SetPropertyValueCode($item['ID'], 'POST_1C_ERROR', 'Y'); // Ошибка передачи, требуется проверка
CIBlockElement::SetPropertyValueCode($item['ID'], 'EXCHANGE_1C', 'Ошибка');
}

// логирование - временно
$response = date("m.d.y").' - '.date("H:i:s").' -:-code- '.$result['code'].' -N- '.(int)$item['NAME'].' ---- '.$result['res'].PHP_EOL;

$fp = fopen($_SERVER["DOCUMENT_ROOT"].'/include/connect_1c/response.log','a');
fwrite($fp, $response);
fclose($fp);

}

// проверка флага передачи платежа (случай наложения времени создания с передачей)
$arFilter_payment = Array("IBLOCK_CODE"=>"payment","ACTIVE"=>'Y',array("LOGIC" => "AND", array("=PROPERTY_POST_1C"=>"Y")));
$res_payment = CIBlockElement::GetList(Array(),$arFilter_payment);
while ($item_payment = $res_payment->GetNextElement()) {
if ($item_payment) {
	$item_payment = $item_payment->GetFields();
	$arFilter_order = Array("IBLOCK_CODE"=>"keep_order","ACTIVE"=>'Y',"ID"=>$item_payment['NAME']);
	$res_order = CIBlockElement::GetList(Array(),$arFilter_order);
	if ($item_order = $res_order->GetNextElement()) {
		$item_order = array_merge($item_order->GetFields(), $item_order->GetProperties());
		if ($item_order['POST_1C']['VALUE'] != 'Y') CIBlockElement::SetPropertyValueCode($item_order['ID'], 'POST_1C', 'Y'); // Установка повторной передачи 
	}
}
}

// Основная передача
$arFilter = Array("IBLOCK_CODE"=>"keep_order","ACTIVE"=>'Y',array("LOGIC" => "AND", array("=PROPERTY_POST_1C"=>"Y")));
$res = CIBlockElement::GetList(Array(),$arFilter);
$trans_off = true;
while ($item = $res->GetNextElement()) {
if ($item) {
	$trans_off = false;
	$item = array_merge($item->GetFields(), $item->GetProperties());
	if (!($UUID = $item["UUID"]["VALUE"])) $UUID = generateUUID();
	
	//connect_SoapRequest();
	$result = order_SoapRequest($item,$UUID); // отключаем временно
	// CIBlockElement::SetPropertyValueCode($item['ID'], 'POST_1C', 'N'); // Сброс флага передачи для всех временно
	
	sleep(10);
}
}
if ($trans_off) echo 'Нет свежачка для передачи';

?>


