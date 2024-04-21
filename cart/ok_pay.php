<?
//ini_set('display_errors', 1);
//error_reporting(E_ALL);

/* подпись */
function P_SIGN($str_P_SIGN) { 
//$SKEY = 'C50E41160302E0F5D6D59F1AA3925C45'; // Тест
$SKEY = '824D435A32B0F695F9E217040A0767B9';
return strtoupper(hash_hmac('sha256', $str_P_SIGN, pack('H*', $SKEY)));		
}

$fSBP = '';
$f2Can = '';
$fcheckPaySBP = '';
$fcheckPayVTB = '';
$ID_check = '';

if (isset($_GET['notifySBP'])) $fSBP = $_GET['notifySBP'];
if (isset($_GET['notify2Can'])) $f2Can = $_GET['notify2Can'];
if (isset($_GET['checkPaySBP']) || isset($_GET['checkPayVTB'])) { // проверка состояния оплаты
		if (isset($_GET['checkPaySBP'])) $fcheckPaySBP = $_GET['checkPaySBP'];
		if (isset($_GET['checkPayVTB'])) $fcheckPayVTB = $_GET['checkPayVTB'];	
		if (isset($_GET['id'])) $ID_check= $_GET['id'];
		}
		
if (($fcheckPaySBP || $fcheckPayVTB) && $ID_check) { // запрос проверки платежа, предположительный период 5 сек
	require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
	if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
        exit;
    }
	
	$statusPay = 'false'; // первоначальный статус false
	
		$res = CIBlock::GetList( Array(), Array('CODE'=>'keep_order'));
		while($ar_res = $res->Fetch()) $iblock_id = $ar_res['ID'];

		// Проверка ExtID
		$res = CIBlockElement::GetList(Array(),Array("IBLOCK_ID"=>$iblock_id,'PROPERTY_UUID'=>$ID_check), false, Array(), Array());
		while($ob = $res->GetNextElement()) $item = array_merge($ob->GetFields(), $ob->GetProperties());
		
		if ($item) { // есть оплата с сайта
				$QR_Value = $item['QR_ID']['VALUE'];
				if ($item['PAYMENT_STATUS']['VALUE'] == 'оплачено') $statusPay = 'true';
				
				$Trans_DeadlineEnd = $item['Trans_DeadlineEnd']['VALUE'];
				
		
		} else { // проверка по базе 1С
				
			$res = CIBlock::GetList( Array(), Array('CODE'=>'Doc1C'));
			while($ar_res = $res->Fetch()) $iblock_id = $ar_res['ID'];

			// Проверка ExtID
			$res_1C = CIBlockElement::GetList(Array(),Array("IBLOCK_ID"=>$iblock_id,"NAME"=>$ID_check), false, Array(), Array());
			while($ob = $res_1C->GetNextElement()) $item_1C = array_merge($ob->GetFields(), $ob->GetProperties());
		
			if (isset($item_1C)) { // Существует в 1C
				$QR_Value = $item_1C['QR_ID']['VALUE'];
				if ($item_1C['PAYMENT_STATUS']['VALUE'] == 'оплачено') $statusPay = 'true';
				
				$Trans_DeadlineEnd = $item_1C['Trans_DeadlineEnd']['VALUE'];
				
				
			}
		}
	if ($Trans_DeadlineEnd) {
		$stmp = MakeTimeStamp($Trans_DeadlineEnd, "DD.MM.YYYY HH:MI:SS");
		if ($QR_Value) $pay_interval = $stmp - time();
		else $pay_interval = $stmp - time()-5;	
	}
	
	echo json_encode(array('pay' => $statusPay, 'interval' => $pay_interval));
	die();
}
			
if ($fSBP == 'yes') { // Прилетело ответка SBP
	$sbp_error ='';
	$sbp_result = true;
	require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
	if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
        exit;
    }
	
	$body_post = file_get_contents("php://input"); // проверка на оплату SBP
	// тест вариант
	/*
	$body_post = '{"QR_ID":"AD100062K1HE9PEF9KDBU2HLU5IRKHJL",
	"SBP_TRAN_ID":"A229807203905501000005268EEA632B",
	"NONCE":"CDF6B5CD841147A6ADFB955B509CAE8C",
	"TERMINAL":"79036777",
	"RND_NUMBER":"8070162097",
	"P_SIGN":"DCC5F58FA70166986879520146E11C994D5406183288C34D9BBCB44CB09F6808",
	"Date":"20221025072038+03"}';
	
	*/
			
	$body_post = json_decode($body_post,true);
        if (!$body_post) {
            $error_1c = 'Ошибка парсера';
        }
	
		$sbp_error .= $error_1c;
		
		// проверка подписи
		$str_P_SIGN = strlen($body_post['RND_NUMBER']).$body_post['RND_NUMBER'].strlen($body_post['TERMINAL']).$body_post['TERMINAL'];
		$P_SIGN = P_SIGN($str_P_SIGN);
	
		if (strnatcasecmp($P_SIGN,$body_post['P_SIGN'])) { $sbp_result = false; $sbp_error .= '!подпись НЕверна!'; }
	
		$res = CIBlock::GetList( Array(), Array('CODE'=>'keep_order'));
		while($ar_res = $res->Fetch()) $iblock_id = $ar_res['ID'];

		// Проверка QR_ID
		$res = CIBlockElement::GetList(Array(),Array("IBLOCK_ID"=>$iblock_id,'PROPERTY_QR_ID'=>$body_post['QR_ID']), false, Array(), Array());
		while($ob = $res->GetNextElement()) $item = array_merge($ob->GetFields(), $ob->GetProperties());
		
		if ($item) {
			
			if ($item["TOTAL_SALE"]["VALUE"]) $amount = $item["TOTAL_SALE"]["VALUE"];
			else $amount = $item["TOTAL"]["VALUE"];
			
			$DT = $body_post['Date']; $DT_Plus = substr($DT,14,3); // смещение со знаком
			$trans_date = substr($DT,6,2).'.'.substr($DT,4,2).'.'.substr($DT,0,4).' '.substr($DT,8,2).':'.substr($DT,10,2).':'.substr($DT,12,2);
			$stmp = MakeTimeStamp($trans_date, "DD.MM.YYYY HH:MI:SS");
			$stmp = AddToTimeStamp(array("HH" => $DT_Plus), $stmp);
			$trans_date = date("d.m.Y H:i:s", $stmp);
			
				$tranId = $body_post['SBP_TRAN_ID'];
				// формирование UUID_PAY с -
				$NONCE = substr($body_post['NONCE'],0,8).'-'.substr($body_post['NONCE'],8,4).'-'.substr($body_post['NONCE'],12,4).'-'.substr($body_post['NONCE'],16,4).'-'.substr($body_post['NONCE'],20,12);
			
				// Формирование записи оплаты
				$arFilter_receipt = Array("IBLOCK_CODE"=>"payment","ACTIVE"=>'Y',"PROPERTY_PAY_orderID"=>$tranId);
				$res_receipt = CIBlockElement::GetList(Array(),$arFilter_receipt);	
				
				$stmp = MakeTimeStamp($item['PAY_DATE']['VALUE'], "DD.MM.YYYY HH:MI:SS");
				$stmp = AddToTimeStamp(array("MI" => 10), $stmp);
				$expirationDate = date("d.m.Y H:i:s", $stmp);
				
				if (!$item_receipt = $res_receipt->GetNextElement()) { // нет записи, формирование маски
					$PROD = array();
					$PROD['PAY_orderID'] = $tranId;
					$PROD['UUID_PAY'] = $NONCE;
					$PROD['QR_ID'] = $body_post['QR_ID'];
					$PROD['Date_Trans'] = $trans_date;
					$PROD['expirationDate'] = $expirationDate;
					$PROD['Sum'] = $amount;
					$PROD['PAY_URL'] = 'https://'.$_SERVER['HTTP_HOST'].'/cart/pay.php?id='.$item['UUID']['VALUE'];
					$PROD['POST_1C'] = 'Y';
					
						$el = new CIBlockElement;
						$resc = CIBlock::GetList(Array(), Array('CODE' => 'payment'));
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
					
						if ($el->Add($save_el)) { // Данные зафиксированы, чистим буфер
						
						CIBlockElement::SetPropertyValueCode($item['ID'], 'UUID_PAY', "");	
						CIBlockElement::SetPropertyValueCode($item['ID'], 'PAY_URL', "");
						//CIBlockElement::SetPropertyValueCode($item['ID'], 'DeadlineEnd', "");
						CIBlockElement::SetPropertyValueCode($item['ID'], 'Trans_DeadlineEnd', "");
						
						CIBlockElement::SetPropertyValueCode($item['ID'], 'PAY_TOTAL', $item['PAY_TOTAL']['VALUE']+$amount);
						CIBlockElement::SetPropertyValueCode($item['ID'], 'POST_1C', 'Y');
						CIBlockElement::SetPropertyValueCode($item['ID'], 'PAY_STATUS', "N");
						CIBlockElement::SetPropertyValueCode($item['ID'], 'PAYMENT_STATUS', "оплачено");
						
						} else $sbp_error .= 'id='.$item['ID'].' - Ошибка записи базы!';
					
					} else $sbp_error .= 'id='.$item['ID'].' - Есть такая оплата!'; // Так вообще не должно быть
				
				// !Памятка
				//$item_receipt['UUID_PAY']['VALUE'] = 'NONCE';
				//$item_receipt['PAY_orderID']['VALUE'] = 'SBP_TRAN_ID';
					
			
		} else {
			
			// установка инфоблока 1с
			$res = CIBlock::GetList( Array(), Array('CODE'=>'Doc1C'));
			while($ar_res = $res->Fetch()) $iblock_id = $ar_res['ID'];

			// Проверка ExtID
			$res_1C = CIBlockElement::GetList(Array(),Array("IBLOCK_ID"=>$iblock_id,'PROPERTY_QR_ID'=>$body_post['QR_ID']), false, Array(), Array());
			while($ob = $res_1C->GetNextElement()) $item_1C = array_merge($ob->GetFields(), $ob->GetProperties());
		
			if (isset($item_1C)) { // Существует в 1C
			
			CIBlockElement::SetPropertyValueCode($item_1C['ID'], 'PAYMENT_STATUS', "оплачено");
			
			} else {$sbp_result = false; $sbp_error .= 'Нет такого заказа.';}
		} 
				
	
	echo json_encode(array('result' => $sbp_result, 'error' => $sbp_error));
	die();
}

if ($f2Can == 'yes') { // Прилетело ответка 2Can
	$vtb_error ='';
	$vtb_result = true;
	require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
	if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
        exit;
    }
	
	$body_post = file_get_contents("php://input"); // проверка на оплату VTB
	// тест вариант
	/*
	$body_post = '{"Date":"20221031140628+03",
				"Amount":"3090.00",
				"Status":"Completed",
				"Id":"876C14D0-2C30-401D-8401-BA8C32CE1588",
				"ExtId":"Doc1C_ef3a9a04-5904-11ed-80d5-ac1f6bba5a52",
				"P_SIGN":"D3908CF8DFB4FD43F736007A76BF91754752574EFBD4B64D972C5424FA247AC1"}';
	*/
			
	$body_post = json_decode($body_post,true);
        if (!$body_post) {
            $error_1c = 'Ошибка парсера';
        }
		
		// проверка подписи
		//$str_P_SIGN = strlen($body_post['Status']).$body_post['Status'].strlen($body_post['Date']).$body_post['Date'].strlen($body_post['Id']).$body_post['Id'].strlen($body_post['Amount']).$body_post['Amount'].strlen($body_post['ExtId']).$body_post['ExtId'];
		$str_P_SIGN = strlen($body_post['Status']).$body_post['Status'].strlen($body_post['Date']).$body_post['Date'].strlen($body_post['Id']).$body_post['Id'].strlen($body_post['Amount']).$body_post['Amount'].
						((strlen($body_post['ExtId']) == 0)?'-':strlen($body_post['ExtId']).$body_post['ExtId']);
		$P_SIGN = P_SIGN($str_P_SIGN);
		
		if (strnatcasecmp($P_SIGN,$body_post['P_SIGN'])) { $vtb_result = false; $vtb_error .= '!подпись НЕверна!'; }
		
		$res = CIBlock::GetList( Array(), Array('CODE'=>'keep_order'));
		while($ar_res = $res->Fetch()) $iblock_id = $ar_res['ID'];

		// Проверка ExtID
		$res = CIBlockElement::GetList(Array(),Array("IBLOCK_ID"=>$iblock_id,'PROPERTY_UUID_PAY'=>$body_post['Id']), false, Array(), Array());
		while($ob = $res->GetNextElement()) $item = array_merge($ob->GetFields(), $ob->GetProperties());
		
		if ($item) {
			
			if ($item["TOTAL_SALE"]["VALUE"]) $amount = $item["TOTAL_SALE"]["VALUE"];
			else $amount = $item["TOTAL"]["VALUE"];
			
			$DT = $body_post['Date']; $DT_Plus = substr($DT,14,3); // смещение со знаком
			$trans_date = substr($DT,6,2).'.'.substr($DT,4,2).'.'.substr($DT,0,4).' '.substr($DT,8,2).':'.substr($DT,10,2).':'.substr($DT,12,2);
			$stmp = MakeTimeStamp($trans_date, "DD.MM.YYYY HH:MI:SS");
			$stmp = AddToTimeStamp(array("HH" => $DT_Plus), $stmp);
			$trans_date = date("d.m.Y H:i:s", $stmp);
			
				$stmp = MakeTimeStamp($item['PAY_DATE']['VALUE'], "DD.MM.YYYY HH:MI:SS");
				$stmp = AddToTimeStamp(array("MI" => 10), $stmp);
				$expirationDate = date("d.m.Y H:i:s", $stmp);
			
				$tranId = $body_post['Id'];
			
				// Формирование записи оплаты
				$arFilter_receipt = Array("IBLOCK_CODE"=>"payment","ACTIVE"=>'Y',"PROPERTY_PAY_orderID"=>$tranId);
				$res_receipt = CIBlockElement::GetList(Array(),$arFilter_receipt);	
				if (!$item_receipt = $res_receipt->GetNextElement()) { // нет записи, формирование маски
					$PROD = array();
					$PROD['PAY_orderID'] = $tranId;
					$PROD['UUID_PAY'] = $tranId;
					$PROD['Date_Trans'] = $trans_date;
					$PROD['expirationDate'] = $expirationDate;
					$PROD['Sum'] = $amount;
					$PROD['PAY_URL'] = 'https://'.$_SERVER['HTTP_HOST'].'/cart/pay.php?id='.$item['UUID']['VALUE'];
					$PROD['POST_1C'] = 'Y';
					
						$el = new CIBlockElement;
						$resc = CIBlock::GetList(Array(), Array('CODE' => 'payment'));
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
					
						if ($el->Add($save_el)) { // Данные зафиксированы, чистим буфер
						
						CIBlockElement::SetPropertyValueCode($item['ID'], 'UUID_PAY', "");	
						CIBlockElement::SetPropertyValueCode($item['ID'], 'PAY_URL', "");
						//CIBlockElement::SetPropertyValueCode($item['ID'], 'DeadlineEnd', "");
						CIBlockElement::SetPropertyValueCode($item['ID'], 'Trans_DeadlineEnd', "");
						
						CIBlockElement::SetPropertyValueCode($item['ID'], 'PAY_TOTAL', $item['PAY_TOTAL']['VALUE']+$amount);
						CIBlockElement::SetPropertyValueCode($item['ID'], 'POST_1C', 'Y');
						CIBlockElement::SetPropertyValueCode($item['ID'], 'PAY_STATUS', "N");
						CIBlockElement::SetPropertyValueCode($item['ID'], 'PAYMENT_STATUS', "оплачено");
						
						} else $vtb_error .= 'id='.$item['ID'].' - Ошибка записи базы!';
					
					} else $vtb_error .= 'id='.$item['ID'].' - Есть такая оплата!'; // Так вообще не должно быть				
			
		} else {
			
			// установка инфоблока 1с
			$res = CIBlock::GetList( Array(), Array('CODE'=>'Doc1C'));
			while($ar_res = $res->Fetch()) $iblock_id = $ar_res['ID'];

			// Проверка ExtID
			$res_1C = CIBlockElement::GetList(Array(),Array("IBLOCK_ID"=>$iblock_id,'PROPERTY_ID_Trans'=>$body_post['Id']), false, Array(), Array());
			while($ob = $res_1C->GetNextElement()) $item_1C = array_merge($ob->GetFields(), $ob->GetProperties());
		
			if (isset($item_1C)) { // Существует в 1C
			
			CIBlockElement::SetPropertyValueCode($item_1C['ID'], 'PAYMENT_STATUS', "оплачено");
			
			} else {$sbp_result = false; $vtb_error .= 'Нет такого заказа.';}
		} 

	echo json_encode(array('result' => $vtb_result, 'error' => $vtb_error));
	die();
}

    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
    if (!CModule::IncludeModule('iblock')) exit;
    $APPLICATION->SetTitle("Оплата прошла успешно");
    $APPLICATION->SetPageProperty("description", "Европласт - производство полиуретановых изделий, лидер на российском рынке");
    if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
        exit;
    }
    require($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");
?>

    <div class="content-wrapper">
        <section class="cont-success cont-success-ok">
            <div class="order-resp-result">Оплата прошла успешно!</div>
            <div class="order-resp-desc">Спасибо, что выбрали нас!</div>
            <div class="order-resp-details">
                <div class="order-resp-details-dealer">Пожалуйста свяжитесь с менеджером, <br>который отправил вам ссылку.</div>
            </div>
            <a class="order-resp-btn succ-cat" href="/catalogue">в каталог</a>
        </section>
    </div>

<?
require($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");
if(defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED===true)
{
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog.php");
}

