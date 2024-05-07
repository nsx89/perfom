<?

$TERMINAL='39002555';

/* подпись */
function P_SIGN($str_P_SIGN) { 
$SKEY = '824D435A32B0F695F9E217040A0767B9';
return strtoupper(hash_hmac('sha256', $str_P_SIGN, pack('H*', $SKEY)));		
}

/* регистрация платежа */
//function reg_QR_ID($item) {	// ($ExtID,$ID_Trans,$amount,$phone,$email,$description,$DeviceID,$CustomerName,$CustomerINN)
function reg_QR_ID($ExtID,$ID_Trans,$amount,$email,$description,$DeviceID,$order_number) {
$reg_error ='';
global $TERMINAL;

$AMOUNT = $amount;
$CURRENCY='RUB'; // валюта платежа, всегда RUB
$ORDER=$order_number; // 0220210811000030000238 уникальный числовой номер заказа длиной 20 символов, формируется по схеме: «02» + ГГГГММДДччммссНННН, ГГГГММДДччммсс – дата и время создания платежной ссылки к СБП, НННН – последние четыре цифра номера заказа на сайте. Я думаю, что должны пролезть с уникальностью
						// для "ХД" использовать 03 вместо 02 и для "РД" 04 вместо 02, и соответственно последние 4 цифры (ХД30000009 или РД30000009)
$EMAIL=$email;

$TRTYPE='1'; // всегда 1
$MERCH_NAME='EVROPLAST';

$DESC=$description;  // Описание, которое уйдет в платежную систему. Если оплата заказа на сайте, то «Оплата по заявке», если оплата платежной ссылки из 1с, то «Оплата заказа». 
																		//Соответственно номер и дата заказа сайта или номер и дата заказа из платежной ссылки																	
$MERCHANT='000523139002555';

$TIMESTAMP=date("YmdHis"); // дата время формирования запроса в формате ГГГГММДДччммсс – время московское
$QR_TTL='10'; // время жизни ссылки в минутах
$NONCE=$ID_Trans; // UUID оплаты без «-», тот что потом будет UUID
$BACKREF='';// всегда пустой

$NOTIFY_URL='https://psb.decor-evroplast.ru/sbp/Work/getdata.php?DeviceIdSBP='.$DeviceID;

$SBP_ID='LA0000283154';

$SBP_ACCOUNT_NUMBER='40702810900000271398';

$SBP_MERCHANT='MA0000242367';

$REGIME='desktop'; // если desktop, то кроме ссылки возвращает еще и картинку с QR-кодом, mobile – только ссылка
$SBP_QR_IMG_WIDTH='300'; // ширина в пикселях картинки с QR-кодом, максимум 300
$SBP_QR_IMG_HEIGHT='300'; // высота в пикселях картинки с QR-кодом, максимум 300
$SBP_QR_IMG_TYPE='image/png'; // тип возвращаемой картинки, может еще image/svg+xml

$str_P_SIGN = strlen($AMOUNT).$AMOUNT.strlen($CURRENCY).$CURRENCY.strlen($ORDER).$ORDER.strlen($MERCH_NAME).$MERCH_NAME.strlen($MERCHANT).$MERCHANT.strlen($TERMINAL).$TERMINAL.((strlen($EMAIL) == 0)?'-':strlen($EMAIL).$EMAIL).
				strlen($TRTYPE).$TRTYPE.strlen($TIMESTAMP).$TIMESTAMP.strlen($NONCE).$NONCE.((strlen($BACKREF) == 0)?'-':strlen($BACKREF).$BACKREF);
$P_SIGN = P_SIGN($str_P_SIGN);



$sbp_url = 'https://3ds.payment.ru/cgi-bin/SBP/reg_qr';
$sbp_data = 'AMOUNT='.$AMOUNT
		.'&CURRENCY='.$CURRENCY
		.'&ORDER='.$ORDER
		.'&DESC='.$DESC
		.'&TERMINAL='.$TERMINAL
		.'&TRTYPE='.$TRTYPE
		.'&MERCH_NAME='.$MERCH_NAME
		.'&MERCHANT='.$MERCHANT
		.'&EMAIL='.$EMAIL
		.'&TIMESTAMP='.$TIMESTAMP
		.'&QR_TTL='.$QR_TTL
		.'&NONCE='.$NONCE
		.'&BACKREF='.$BACKREF
		.'&NOTIFY_URL='.$NOTIFY_URL
		.'&SBP_ID='.$SBP_ID
		.'&SBP_ACCOUNT_NUMBER='.$SBP_ACCOUNT_NUMBER
		.'&SBP_MERCHANT='.$SBP_MERCHANT
		.'&REGIME='.$REGIME
		.'&SBP_QR_IMG_WIDTH='.$SBP_QR_IMG_WIDTH
		.'&SBP_QR_IMG_HEIGHT='.$SBP_QR_IMG_HEIGHT
		.'&SBP_QR_IMG_TYPE='.$SBP_QR_IMG_TYPE
		.'&P_SIGN='.$P_SIGN
		;
		
/*	
echo $sbp_url;
echo '<br>';	
echo $sbp_data;
echo '<br>';
echo $str_P_SIGN;
echo '<br>';
echo $P_SIGN;
echo '<br>';
*/
	
	
if ($ch = curl_init()) { 

			if(strtolower((substr($sbp_url,0,5))=='https')) { // если соединяемся с https
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			}
			curl_setopt($ch, CURLOPT_URL, $sbp_url);
			curl_setopt($ch, CURLOPT_REFERER, $sbp_url);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.139 Safari/537.36');
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $sbp_data);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded"));
			
			$response = curl_exec($ch); 
			$out = json_decode($response);
			$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);   			

 /*		
	echo $code;
	echo '<br>';
	var_dump($response);
	echo '<pre>';
	print_r($out);
	echo '</pre>';	
	echo '<br>';
 */
	
	$QR_ID = $out->QR_ID;
	$QR_DATA = $out->QR_DATA;
	$QR_IMG_REF = $out->QR_IMG_REF;
	$QR_PSB_ID = $out->QR_PSB_ID;
	$P_SIGN_OUT = $out->P_SIGN;
	$QR_IMG_DATA = $out->QR_IMG_DATA; // Вывод QR кода
	
	// проверка подписи
	$str_P_SIGN = strlen($QR_ID).$QR_ID.strlen($QR_DATA).$QR_DATA;
	$P_SIGN = P_SIGN($str_P_SIGN);
	
	// Регистрация платежа сформировано
	
	
	
	
	if (strnatcasecmp($P_SIGN,$P_SIGN_OUT)) $reg_error .= '!подпись НЕверна!';
	$reg_out = array('QR_ID'=>$QR_ID,'QR_DATA'=>$QR_DATA,'QR_IMG_REF'=>$QR_IMG_REF,'QR_PSB_ID'=>$QR_PSB_ID,'QR_IMG_DATA'=>$QR_IMG_DATA);
	
	
} else $reg_error .= 'Ошибка передачи';

$reg_out['error'] = $reg_error;

return $reg_out;	
}

/* запрос состояние по QR_ID */
function check_QR_ID($QR_ID) {
$check_error ='';
global $TERMINAL;
$RND_NUMBER = rand(0, 9999999999);
$NSPK_JSON_DATA = json_encode(array('qrcIds' => array($QR_ID)));
$str_P_SIGN = strlen($RND_NUMBER).$RND_NUMBER.strlen($TERMINAL).$TERMINAL;
$P_SIGN = P_SIGN($str_P_SIGN);

$sbp_url = 'https://3ds.payment.ru/cgi-bin/SBP/get_qr_status';
$sbp_data = 'TERMINAL='.$TERMINAL
		.'&RND_NUMBER='.$RND_NUMBER
		.'&P_SIGN='.$P_SIGN
		.'&NSPK_JSON_DATA='.$NSPK_JSON_DATA
		;		
		
//echo '<br><br>';
//echo $sbp_data;

if ($ch = curl_init()) { 

			if(strtolower((substr($sbp_url,0,5))=='https')) { // если соединяемся с https
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			}
			curl_setopt($ch, CURLOPT_URL, $sbp_url);
			curl_setopt($ch, CURLOPT_REFERER, $sbp_url);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.139 Safari/537.36');
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $sbp_data);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded"));
			
			$response = curl_exec($ch); 
			$out = json_decode($response);
			$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);   			
	/*
	echo '<br>';
	echo $code;
	echo '<br>';
	var_dump($response);
	echo '<pre>';
	print_r($out);
	echo '</pre>';	
	echo '<br>';
	*/
	
	$fp = fopen($_SERVER["DOCUMENT_ROOT"].'/cart/response.log','a');
    fwrite($fp, date("Y-m-d H:i:s").' :: '.$response."\r\n");
    fclose($fp);
	
	$RND_NUMBER = $out->RND_NUMBER;
	$P_SIGN_OUT = $out->P_SIGN;
	
	// проверка подписи
	$str_P_SIGN = strlen($RND_NUMBER).$RND_NUMBER.strlen($TERMINAL).$TERMINAL;
	$P_SIGN = P_SIGN($str_P_SIGN);
	
	if (strnatcasecmp($P_SIGN,$P_SIGN_OUT)) $check_error .= '!подпись НЕверна!';
	
	
} else $check_error .= 'Ошибка передачи';

return array('out'=>$out, 'error'=>$check_error);
}
?>