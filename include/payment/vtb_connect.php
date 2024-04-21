<?
// доступ из вне
$vtbUser = "man_site@dekor.demo";
$vtbPassword = "123123123";
$headers = array("Content-Type: application/json;charset=UTF-8", "Authorization: Basic ".base64_encode($vtbUser.":".$vtbPassword));

// Конвертор даты под формат bitrix -> 1c
function Date_OUT_1C($date) {
			$date = explode(" ",$date);
			$date_rev = explode(".",$date[0]);
			$data_doc = $date_rev[2].'-'.$date_rev[1].'-'.$date_rev[0].'T'.$date[1];		
return $data_doc;
}

// Конвертор даты под формат 1c -> bitrix
function Date_IN_1C($date) {
			$date = explode("T",$date);
			$date_rev = explode("-",$date[0]);
			$data_doc = $date_rev[2].'.'.$date_rev[1].'.'.$date_rev[0].' '.$date[1];
return $data_doc;
}

// Конвертор даты под формат PS -> bitrix
function Date_OUT_PS($date) {
			$date = explode("T",$date);
			$date_rev = explode("-",$date[0]); $time_cur = explode(".",$date[1]);
			$data_doc = $date_rev[2].'.'.$date_rev[1].'.'.$date_rev[0].' '.$time_cur[0];
return $data_doc;
}

// Запрос состояния транзакции
function RequestTransactions($ID_Trans) {
global $headers;

$ch_url = 'https://processing-demo.cardport.net/api/v1/payment/'.$ID_Trans;
		$ch = curl_init();  
			if(strtolower((substr($ch_url,0,5))=='https')) { // если соединяемся с https
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			}
			
            curl_setopt($ch, CURLOPT_URL, $ch_url);
			curl_setopt($ch, CURLOPT_REFERER, $ch_url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT,60);
			curl_setopt($ch, CURLOPT_POST, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			
			$response = curl_exec($ch); 
			$out = json_decode($response);
			$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);   			
	
return array('out' => $out, 'code' => $code);
}

// Создание новой транзакции
function NewTransactions($ExtID,$ID_Trans,$amount,$phone,$email,$description,$DeviceID,$CustomerName,$CustomerINN) {
global $headers;	
$data = array(
	"GMT" => 3,
	"AppFramework" => "java",
	"Lang" => "ru",
	"AcqTran" => array(
		"AcquirerCode" => "BOXPLAT"
	),
	"Receipt" => array(
		"Phone" => '',
		"Email" => $email
	),
	"DeviceInfo" => array(
		"DeviceID" => $DeviceID
	),
	"Location" => array(
		"Latitude" => 0,
		"Longitude" => 0
	),
	"BasicTran" => array(
		"InputType" => 30,
		"CurrencyID" => "RUB",
		"Amount" => $amount,
		"ServiceID" => "CARDPORT-PRO.ACCEPT-PAYMENT",
		"ID" => $ID_Trans,
		"ExtID" =>$ExtID,
		"Description" => $description,
		"AuxDataInput" => array(
			"AuxData" => array(
				"Purchases" => array(
					array(
					"Title" => "Аванс на поставку товара",
					"Price" => 1,
					"Quantity" => $amount,
					"TaxCode" => array("VAT2000_12000"),
					"1212" => 10,
					"1214" => 3	
					)
				)
			)	
		)
		
	)
	
);

if ($CustomerName || $CustomerINN) {
$data['BasicTran']['AuxDataInput']['AuxData']['Tags']['1227'] = $CustomerName;
$data['BasicTran']['AuxDataInput']['AuxData']['Tags']['1228'] = $CustomerINN;
}

$curl_data = json_encode($data);
$ch_url = 'https://processing-demo.cardport.net/api/v1/payment/submit';
		$ch = curl_init();  
			if(strtolower((substr($ch_url,0,5))=='https')) { // если соединяемся с https
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			}
			
            curl_setopt($ch, CURLOPT_URL, $ch_url);
			curl_setopt($ch, CURLOPT_REFERER, $ch_url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT,60);
			curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			
			$response = curl_exec($ch); 
			$out = json_decode($response);
			$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);   		
	
return array('out' => $out, 'code' => $code);		
}
?>