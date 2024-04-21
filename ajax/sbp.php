<?
ini_set('display_errors', 1);
//error_reporting(E_ALL);

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/include/payment/sbp_connect.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}

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

/*function generateUUID() { // Не вынести, где то накладка.
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

	$uuid = mb_strtoupper($uuid);
    return ($uuid);
}*/


// Запрос ID и Frame
if (isset($_GET['id'])) $UUID = $_GET['id'];
if (isset($_GET['frame'])) $fFrame = $_GET['frame'];
if (isset($_GET['mobile'])) $fMobile = $_GET['mobile'];

// тех запрос
if (strcmp($_SERVER['REQUEST_METHOD'],'POST') == 0) { // Пост запрос /// надо еще разобраться в ременных задержках от ПС, несоответсвуют тайминги

	
	// установка инфоблока keep_order
	$res = CIBlock::GetList( Array(), Array('CODE'=>'keep_order'));
	while($ar_res = $res->Fetch()) $iblock_id = $ar_res['ID'];
	// Проверка ExtID
	$res = CIBlockElement::GetList(Array(),Array("IBLOCK_ID"=>$iblock_id,'PROPERTY_UUID'=>$UUID), false, Array(), Array());
	while($ob = $res->GetNextElement()) $item = array_merge($ob->GetFields(), $ob->GetProperties());
	
	if (isset($item)) { // Существует
	
		// проверка платежа
		$check_out = check_QR_ID($item['QR_ID']['VALUE']);
		if ($check_out['out']->NSPK_JSON_RESP_DATA->data[0]->code != 'RQ00000') $trans_status = 1;
		else $trans_status = 1;
		
		
	} else { // 1c ID
		// установка инфоблока 1с
		$res = CIBlock::GetList( Array(), Array('CODE'=>'Doc1C'));
		while($ar_res = $res->Fetch()) $iblock_id = $ar_res['ID'];

		// Проверка ExtID
		$res_1C = CIBlockElement::GetList(Array(),Array("IBLOCK_ID"=>$iblock_id,"NAME"=>$UUID), false, Array(), Array());
		while($ob = $res_1C->GetNextElement()) $item_1C = array_merge($ob->GetFields(), $ob->GetProperties());

			if (isset($item_1C)) { // Существует	
			// проверка платежа
			$check_out = check_QR_ID($item_1C['QR_ID']['VALUE']);
			if ($check_out['out']->NSPK_JSON_RESP_DATA->data[0]->code != 'RQ00000') $trans_status = 1;
			else $trans_status = 1;
			}
	}

echo $trans_status;
die;
}


// Тест режим
// $UUID = 'fed7eb7b-acb6-40fe-88a9-44380fce40c3';

if ($UUID) { 
$err_out = '';
	// установка инфоблока keep_order
	$res = CIBlock::GetList( Array(), Array('CODE'=>'keep_order'));
	while($ar_res = $res->Fetch()) $iblock_id = $ar_res['ID'];

	// Проверка ExtID
	$res = CIBlockElement::GetList(Array(),Array("IBLOCK_ID"=>$iblock_id,'PROPERTY_UUID'=>$UUID), false, Array(), Array());
	while($ob = $res->GetNextElement()) $item = array_merge($ob->GetFields(), $ob->GetProperties());


if (isset($item)) { // Существует

	// проверка на предмет оплачено
	if ($item['PAYMENT_STATUS']['VALUE'] == 'оплачено')
	{ // оплата прошла, редирект на страницу оплаты 
			echo json_encode(array('success'=>'true'));	
			die;
	}	

	// проверка состояния другой платежной системы
	if ((strtotime(date('d.m.Y H:i:s')) < MakeTimeStamp($item['Trans_DeadlineEnd']['VALUE'], "DD.MM.YYYY HH:MI:SS")) && ($item['TYPE_PAY']['VALUE'] == 'V')) 
	{ // оплата сформирована, позиционирование на нужныю оплату
			echo json_encode(array('url'=>'&pay=1'));	
			die;
	}	
	
ob_start();	
	
$new_reg = false; // Новая регистрация
// установка ограничений на первом создании транзакции
if (!$item["DeadlineEnd"]["VALUE"]) {
	$DateTrans = $item['PAY_DateBegin']['VALUE'];
	$stmp = MakeTimeStamp($DateTrans, "DD.MM.YYYY HH:MI:SS");
	$stmp = AddToTimeStamp(array("HH" => 12), $stmp);
	$DeadlineEnd = date("d.m.Y H:i:s", $stmp);

	CIBlockElement::SetPropertyValueCode($item['ID'], 'DeadlineEnd', $DeadlineEnd);
	CIBlockElement::SetPropertyValueCode($item['ID'], 'Count', 1);
	
	$item["DeadlineEnd"]["VALUE"] = $DeadlineEnd;
	$item['Count']['VALUE'] = 1;
	
	$new_reg = true; // требуеться новая регистрация
}
	
	if ($item['QR_ID']['VALUE']) {
		// проверка платежа
		$check_out = check_QR_ID($item['QR_ID']['VALUE']);
		
		// Проверка состояния
		if ($check_out['out']->NSPK_JSON_RESP_DATA->data[0]->code == 'RQ00000') { // рабочая
			
			if ($check_out['out']->NSPK_JSON_RESP_DATA->data[0]->status == 'NTST') { // не оплачено
			
				$QR_DATA = $item['PAY_URL']['VALUE'];
				$QR_IMG_DATA = $item['QR_IMG_DATA']['VALUE'];
				$QR_ID = $item['QR_ID']['VALUE'];
				$Trans_DeadlineEnd = $item['Trans_DeadlineEnd']['VALUE'];
			
			} elseif ($check_out['out']->NSPK_JSON_RESP_DATA->data[0]->status == 'ACWP') { // ОПЛАЧЕНО
				echo json_encode(array('success'=>'true'));	
				die;
			} else $new_reg = true; // т.е. нет и не будет успешной оплаты. "status":"RJCT"
			
		} else $new_reg = true; // просрочено, новая регистрация
			
		/*
			echo '<pre>';
			print_r($check_out['out']);
			echo '</pre>';
			echo '<br>';
			echo $check_out['error'];
			echo '<br>';
			//echo $check_out['out']->NSPK_JSON_RESP_DATA->data[0]->code;
		*/		
		
	} else $new_reg = true;
	
	// Количесво попыток не более 5
	if ($item['Count']['VALUE'] > 5) {
	$err_out = 'Количество попыток израсходовано, обратитесь к&nbsp;менеджеру';
	} elseif (strtotime(date('d.m.Y H:i:s')) > MakeTimeStamp($item['DeadlineEnd']['VALUE'], "DD.MM.YYYY HH:MI:SS")) {
	$err_out = 'Время предназначенное для&nbsp;оплаты вышло, обратитесь к&nbsp;менеджеру';
	} elseif (((strtotime(date('d.m.Y H:i:s')) < MakeTimeStamp($item['PAY_DATE']['VALUE'], "DD.MM.YYYY HH:MI:SS")) && $item['PAY_DATE']['VALUE']) || ($item['TYPE_PAY']['VALUE'] == 'V')) {
	$err_out = 'Идет формаривание платежа, обновите страницу';	
	$err_build = true;
	} elseif ($new_reg) { // Создание новой транзакции
	
	// первоначальное время для задержки формирования	
	$stmp = MakeTimeStamp(date("d.m.Y H:i:s"), "DD.MM.YYYY HH:MI:SS");
	$stmp = AddToTimeStamp(array("SS" => 20),$stmp);
	CIBlockElement::SetPropertyValueCode($item['ID'], 'PAY_DATE', date("d.m.Y H:i:s", $stmp));
	
	// регистрация
	$ExtID = $item['UUID']['VALUE'];
	//$ID_Trans = preg_replace('/-/','',generateUUID()); 
	$ID_Trans = preg_replace('/-/','',$ExtID); 
	
	if ($item["TOTAL_SALE"]["VALUE"]) $price = $item["TOTAL_SALE"]["VALUE"];
	else $price = $item["TOTAL"]["VALUE"];
	$amount=number_format($price,2,'.',''); // сумма платежа
	
	$email=$item["USER_MAIL"]["VALUE"]; // email покупателя

	$orderData = explode(" ",$item['DATE']['VALUE']);
	$orderData = $orderData[0];

	$description = 'Оплата по заявке №'.$item["NAME"].' от '.$orderData.', в т.ч. НДС';
	
	$DeviceID = 'perfom.ru';
	
	$order_number='02'.date("YmdHis").mb_substr($item["NAME"],-4);
	
	$reg_out = reg_QR_ID($ExtID,$ID_Trans,$amount,$email,$description,$DeviceID,$order_number);
	
	$QR_DATA = $reg_out['QR_DATA'];
	$QR_IMG_DATA = $reg_out['QR_IMG_DATA'];
	$QR_ID = $reg_out['QR_ID'];
	$reg_out['error'];
	
	// Дата Время
	$now = time();
	$now_DeadlineEnd = AddToTimeStamp(array("MI" => 10), $now);
	$PAY_DATE = date("d.m.Y H:i:s",$now);
	$Trans_DeadlineEnd = date("d.m.Y H:i:s",$now_DeadlineEnd);
	
		// Если ошибок нет сохранение данных
		if (!$reg_out['error']) { 
			CIBlockElement::SetPropertyValueCode($item['ID'], 'QR_ID', $QR_ID);
			CIBlockElement::SetPropertyValueCode($item['ID'], 'QR_IMG_DATA', $QR_IMG_DATA);
			CIBlockElement::SetPropertyValueCode($item['ID'], 'PAY_DATE', date("d.m.Y H:i:s"));
			CIBlockElement::SetPropertyValueCode($item['ID'], 'Trans_DeadlineEnd', $Trans_DeadlineEnd);
			CIBlockElement::SetPropertyValueCode($item['ID'], 'PAY_URL', $QR_DATA);
			CIBlockElement::SetPropertyValueCode($item['ID'], 'Count', $item['Count']['VALUE']+1);
			CIBlockElement::SetPropertyValueCode($item['ID'], 'PAY_STATUS', "Y");
			CIBlockElement::SetPropertyValueCode($item['ID'], 'PAYMENT_STATUS', "ожидание оплаты"); 
			
		} else $err_out = $reg_out['error'];
		
		
	}
	if ($QR_IMG_DATA && !$err_out) { 
		$stmp = MakeTimeStamp($Trans_DeadlineEnd, "DD.MM.YYYY HH:MI:SS");
		$pay_interval = $stmp - time();
	?>
	<? if ($fFrame == 'yes') {?>
		 <div style="height: 100vh; overflow: hidden;" data-interval="<?=$pay_interval?>" data-type="iframe-wrap" class="pay-iframe-wrap">
			<iframe allowPaymentRequest="true" src="<?=$QR_DATA?>" style="height: 100%; width:100%;" type="text/html" frameborder="0" align="middle"></iframe>
		</div>
	<? } elseif ($fMobile == 'yes') {  
	if ($item["TOTAL_SALE"]["VALUE"]) $price = $item["TOTAL_SALE"]["VALUE"];
	else $price = $item["TOTAL"]["VALUE"];
	$amount=number_format($price,2,'.',' '); // сумма платежа
	$orderData = explode(" ",$item['DATE']['VALUE']);
	$orderData = $orderData[0];
	$description = 'Заказ №'.$item["NAME"].' от '.$orderData;
	?>
	
<style>
	.timer {
		position: relative;
		width: auto;
		display: block;
		padding: 10px 20px 0 20px;
		font-size: 13px !important;
		line-height: 18px;
		height: 25px;
		border-bottom: 1px solid #eee;
    }
	.timer span {
		float: right;
		font-size: 18px;
		font-weight: bold;
    }	
	.sbp-pay-order-info-sum {
			padding: 24px;
            font-size: 24px;
            font-weight: 600;
            text-align: center;
    }
	.sbp-pay-order-info-text {
			margin-bottom: 6px;
            font-size: 12px;
            text-align: center;
    }
	.sbp-pay-order-info-text2 {
			color: #000000;
			margin-bottom: 6px;
            font-size: 14px;
            text-align: center;
    }
	.sbp-pay-order-info-description {
            font-size: 16px;
            text-align: center;
			margin-bottom: 12px;
    }
	.sbp-pay-order-info-link {
			color: #DCDCDC;
            display: block;
			padding: 0 6px;
			margin: 12px;
            height: 40px;
            line-height: 40px;
            text-align: center;
            background-color: #00008B;
            font-size: 14px;
            margin-bottom: 40px;
            min-width: 200px;
    }
	.sbp-pay-order-info-link a:link {
			color: #DCDCDC;
			text-decoration: none;
	}
	.sbp-pay-order-info-QR img {
		    width: 180px;
	}
	
</style>
	
	<div style="height: 100vh; overflow: hidden;" data-interval="<?=$pay_interval?>" data-type="iframe-wrap" class="pay-iframe-wrap">
		<div class="timer">
			<div class="e-new-cont">
				<div class="timer-wrap">
					<div class="timer-desc">Время для оплаты: 
						<span data-type="timer"></span>
					</div>
				</div>
			</div>
		</div>
        <div class="pay-order-info">
            <div class="sbp-pay-order-info-sum"><span class="sbp-pay-order-sum"><?=$amount?></span> р.</div>
            <div class="sbp-pay-order-info-text">описание платежа:</div>
            <div class="sbp-pay-order-info-description"><?=$description?></div>
            <div class="sbp-pay-order-info-link"><a href='<?=$QR_DATA?>' target='_blank'>Перейти в мобильный банк для оплаты</a><div>
            <div class="sbp-pay-order-info-text2">или сканируйте QR-код</div>
            <div class="sbp-pay-order-info-QR"><a href='<?=$QR_DATA?>' target='_blank'><img src='data:image/png;base64,<?=$QR_IMG_DATA?>'></img></a></div>
        </div>
    </div>
	<? } else { ?>
	<div data-interval="<?=$pay_interval?>" data-type="iframe-wrap">
        <div class="pay-order-info">
            <img src='data:image/png;base64,<?=$QR_IMG_DATA?>'></img>
            <? /* <div class="sbp-pay-order-info-text2">сканируйте QR-код</div> */ ?>
        </div>
	</div>
	<? 
	} 
	} elseif ($err_build) { ?>
        <section class="order-resp-sec">
            <div class="order-resp-result">Что-то пошло не&nbsp;так...</div>
            <div class="order-resp-details">
              <div class="order-resp-details-dealer">
			   Оплата не&nbsp;может быть сформирована, т.к. Вы уже оплачиваете на&nbsp;другом устройстве или&nbsp;странице. Завершите оплату там или&nbsp;повторите платеж по&nbsp;окончанию времени для&nbsp;оплаты.
			  </div>
            </div>
        </section>
	<?} else { ?>
        <section class="order-resp-sec">
            <div class="order-resp-result">Что-то пошло не&nbsp;так...</div>
            <div class="order-resp-details">
              <div class="order-resp-details-dealer">При&nbsp;создании QR-кода произошла ошибка, обратитесь к&nbsp;менеджеру или&nbsp;поменяйте способ&nbsp;оплаты.</div>
            </div>
            <a class="order-resp-btn succ-cat" href="<?='/cart/pay.php?id='.$UUID?>">Вернуться к&nbsp;выбору оплаты</a>
        </section>
	<? }
	
$html = ob_get_clean();
print json_encode($html);	

} else { // Транзакция от 1С -----------------------------------------------------------------------------------------------

// установка инфоблока 1с
$res = CIBlock::GetList( Array(), Array('CODE'=>'Doc1C'));
while($ar_res = $res->Fetch()) $iblock_id = $ar_res['ID'];

// Проверка ExtID
$res_1C = CIBlockElement::GetList(Array(),Array("IBLOCK_ID"=>$iblock_id,"NAME"=>$UUID), false, Array(), Array());
while($ob = $res_1C->GetNextElement()) $item_1C = array_merge($ob->GetFields(), $ob->GetProperties());

if (isset($item_1C)) { // Существует

	// проверка на предмет оплачено
	if ($item_1C['PAYMENT_STATUS']['VALUE'] == 'оплачено')
	{ // оплата прошла, редирект на страницу оплаты 
			echo json_encode(array('success'=>'true'));	
			die;
	}	

	// проверка состояния другой платежной системы
	if ((strtotime(date('d.m.Y H:i:s')) < MakeTimeStamp($item_1C['Trans_DeadlineEnd']['VALUE'], "DD.MM.YYYY HH:MI:SS")) && ($item_1C['TYPE_PAY']['VALUE'] == 'V')) 
	{ // оплата сформирована, позиционирование на нужныю оплату
			echo json_encode(array('url'=>'&pay=1'));	
			die;
	}
	
ob_start();	

$new_reg = false; // Новая регистрация
	
	if ($item_1C['QR_ID']['VALUE']) {
		// проверка платежа
		$check_out = check_QR_ID($item_1C['QR_ID']['VALUE']);
		
		// Проверка состояния
		if ($check_out['out']->NSPK_JSON_RESP_DATA->data[0]->code == 'RQ00000') { // рабочая
			
			if ($check_out['out']->NSPK_JSON_RESP_DATA->data[0]->status == 'NTST') { // не оплачено
			
				$QR_DATA = $item_1C['PAY_URL']['VALUE'];
				$QR_IMG_DATA = $item_1C['QR_IMG_DATA']['VALUE'];
				$QR_ID = $item_1C['QR_ID']['VALUE'];
				$Trans_DeadlineEnd = $item_1C['Trans_DeadlineEnd']['VALUE'];
			
			} elseif ($check_out['out']->NSPK_JSON_RESP_DATA->data[0]->status == 'ACWP') { // ОПЛАЧЕНО
				echo json_encode(array('success'=>'true'));	
				die;
			} else $new_reg = true; // т.е. нет и не будет успешной оплаты. "status":"RJCT"
			
		} else $new_reg = true; // просрочено, новая регистрация
			
		/*
			echo '<pre>';
			print_r($check_out['out']);
			echo '</pre>';
			echo '<br>';
			echo $check_out['error'];
			echo '<br>';
			//echo $check_out['out']->NSPK_JSON_RESP_DATA->data[0]->code;
		*/		

	} else $new_reg = true;
	
	// Количесво попыток не более 5
	if ($item_1C['Count']['VALUE'] > 5) {
	$err_out = 'Количество попыток израсходовано, обратитесь к&nbsp;менеджеру';
	} elseif (strtotime(date('d.m.Y H:i:s')) > MakeTimeStamp($item_1C['DeadlineEnd']['VALUE'], "DD.MM.YYYY HH:MI:SS")) {
	$err_out = 'Время предназначенное для&nbsp;оплаты вышло, обратитесь к&nbsp;менеджеру';
	} elseif (((strtotime(date('d.m.Y H:i:s')) < MakeTimeStamp($item_1C['PAY_DATE']['VALUE'], "DD.MM.YYYY HH:MI:SS")) && $item_1C['PAY_DATE']['VALUE']) || ($item_1C['TYPE_PAY']['VALUE'] == 'V')) {
	$err_out = 'Идет формаривание платежа, обновите страницу';
	$err_build = true;	
	} elseif ($new_reg) { // Создание новой транзакции
	
	// первоначальное время для задержки формирования	
	$stmp = MakeTimeStamp(date("d.m.Y H:i:s"), "DD.MM.YYYY HH:MI:SS");
	$stmp = AddToTimeStamp(array("SS" => 20),$stmp);	
	CIBlockElement::SetPropertyValueCode($item_1C['ID'], 'PAY_DATE', date("d.m.Y H:i:s", $stmp));

	// регистрация
	$ExtID = $item_1C['NAME'];
	$ID_Trans = preg_replace('/-/','',mb_substr($ExtID,6)); 
	
	$amount = $item_1C['Sum']['VALUE'];
	$email = $item_1C['CustomerEmail']['VALUE'];
	$description = 'Оплата заказа №'.$item_1C['OrderNumber']['VALUE'].' от '.$item_1C['OrderDate']['VALUE'].', в т.ч. НДС';
	
	$DeviceID = '1C_P';
	
	if (mb_substr($item_1C['OrderNumber']['VALUE'],0,2) == 'ХД') $order_number_p = '03';
	else $order_number_p = '04';
	
	$order_number=$order_number_p.date("YmdHis").mb_substr($item_1C['OrderNumber']['VALUE'],-4);
	
	$reg_out = reg_QR_ID($ExtID,$ID_Trans,$amount,$email,$description,$DeviceID,$order_number);
	
	$QR_DATA = $reg_out['QR_DATA'];
	$QR_IMG_DATA = $reg_out['QR_IMG_DATA'];
	$QR_ID = $reg_out['QR_ID'];
	$reg_out['error'];
	
	// Дата Время
	$now = time();
	$now_DeadlineEnd = AddToTimeStamp(array("MI" => 10), $now);
	$PAY_DATE = date("d.m.Y H:i:s",$now);
	$Trans_DeadlineEnd = date("d.m.Y H:i:s",$now_DeadlineEnd);
	
		// Если ошибок нет сохранение данных
		if (!$reg_out['error']) { 
			CIBlockElement::SetPropertyValueCode($item_1C['ID'], 'QR_ID', $QR_ID);
			CIBlockElement::SetPropertyValueCode($item_1C['ID'], 'QR_IMG_DATA', $QR_IMG_DATA);
			CIBlockElement::SetPropertyValueCode($item_1C['ID'], 'PAY_DATE', date("d.m.Y H:i:s"));
			CIBlockElement::SetPropertyValueCode($item_1C['ID'], 'ID_Trans', $ID_Trans);
			CIBlockElement::SetPropertyValueCode($item_1C['ID'], 'Trans_DeadlineEnd', $Trans_DeadlineEnd);
			CIBlockElement::SetPropertyValueCode($item_1C['ID'], 'PAY_URL', $QR_DATA);
			CIBlockElement::SetPropertyValueCode($item_1C['ID'], 'Count', $item_1C['Count']['VALUE']+1);
		} else $err_out = $reg_out['error'];
		
		
	}
	
	if ($QR_IMG_DATA && !$err_out) { 
		$stmp = MakeTimeStamp($Trans_DeadlineEnd, "DD.MM.YYYY HH:MI:SS");
		$pay_interval = $stmp - time();
	?>
	<? if ($fFrame == 'yes') {?>
		 <div style="height: 100vh; overflow: hidden;" data-interval="<?=$pay_interval?>" data-type="iframe-wrap" class="pay-iframe-wrap">
			<iframe allowPaymentRequest="true" src="<?=$QR_DATA?>" style="height: 100%; width:100%;" type="text/html" frameborder="0" align="middle"></iframe>
		</div>
	<? } elseif ($fMobile == 'yes') { 
	$amount=number_format($item_1C['Sum']['VALUE'],2,'.',' '); // сумма платежа
	$description = 'Заказ №'.$item_1C['OrderNumber']['VALUE'].' от '.$item_1C['OrderDate']['VALUE'];
	?>
		
<style>
	.timer {
		position: relative;
		width: auto;
		display: block;
		padding: 10px 20px 0 20px;
		font-size: 13px !important;
		line-height: 18px;
		height: 25px;
		border-bottom: 1px solid #eee;
    }
	.timer span {
		float: right;
		font-size: 18px;
		font-weight: bold;
    }	
	.sbp-pay-order-info-sum {
			padding: 24px;
            font-size: 24px;
            font-weight: 600;
            text-align: center;
    }
	.sbp-pay-order-info-text {
			margin-bottom: 6px;
            font-size: 12px;
            text-align: center;
    }
	.sbp-pay-order-info-text2 {
			color: #000000;
			margin-bottom: 6px;
            font-size: 14px;
            text-align: center;
    }
	.sbp-pay-order-info-description {
            font-size: 16px;
            text-align: center;
			margin-bottom: 12px;
    }
	.sbp-pay-order-info-link {
			color: #DCDCDC;
            display: block;
			padding: 0 6px;
			margin: 12px;
            height: 40px;
            line-height: 40px;
            text-align: center;
            background-color: #00008B;
            font-size: 14px;
            margin-bottom: 40px;
            min-width: 200px;
    }
	.sbp-pay-order-info-link a:link {
			color: #DCDCDC;
			text-decoration: none;
	}
	.sbp-pay-order-info-QR img {
		    width: 180px;
	}
	
</style>
	
	<div style="height: 100vh; overflow: hidden;" data-interval="<?=$pay_interval?>" data-type="iframe-wrap" class="pay-iframe-wrap">
		<div class="timer">
			<div class="e-new-cont">
				<div class="timer-wrap">
					<div class="timer-desc">Время для оплаты: 
						<span data-type="timer"></span>
					</div>
				</div>
			</div>
		</div>
		
        <div class="pay-order-info">
            <div class="sbp-pay-order-info-sum"><span class="sbp-pay-order-sum"><?=$amount?></span> р.</div>
            <div class="sbp-pay-order-info-text">описание платежа:</div>
            <div class="sbp-pay-order-info-description"><?=$description?></div>
            <div class="sbp-pay-order-info-link"><a href='<?=$QR_DATA?>' target='_blank'>Перейти в мобильный банк для оплаты</a><div>
            <div class="sbp-pay-order-info-text2">или сканируйте QR-код</div>
            <div class="sbp-pay-order-info-QR"><a href='<?=$QR_DATA?>' target='_blank'><img src='data:image/png;base64,<?=$QR_IMG_DATA?>'></img></a></div>
        </div>
    </div>
	<? } else { ?>
	<div data-interval="<?=$pay_interval?>" data-type="iframe-wrap">
			<div class="pay-order-info">
				<img src='data:image/png;base64,<?=$QR_IMG_DATA?>'></img>
				<? /* <div class="sbp-pay-order-info-text2">сканируйте QR-код</div> */ ?>
			</div>	
	</div>
	<? 
	} 
	} elseif ($err_build) { ?>
        <section class="order-resp-sec">
            <i class="new-icomoon icon-sad-face"></i>
            <div class="order-resp-result">Что-то пошло не&nbsp;так...</div>
            <div class="order-resp-details">
              <div class="order-resp-details-dealer">
			   Оплата не&nbsp;может быть сформирована, т.к. Вы уже&nbsp;оплачиваете на&nbsp;другом устройстве или&nbsp;странице. Завершите оплату&nbsp;там или&nbsp;повторите платеж по&nbsp;окончанию времени для&nbsp;оплаты.
			  </div>
            </div>
        </section>
	<?} else { ?>
        <section class="order-resp-sec">
            <i class="new-icomoon icon-sad-face"></i>
            <div class="order-resp-result">Что-то пошло не так...</div>
            <div class="order-resp-details">
              <div class="order-resp-details-dealer">При создании QR-кода произошла ошибка, обратитесь к&nbsp;менеджеру или поменяйте способ оплаты.</div>
            </div>
            <a class="order-resp-btn succ-cat" style="width: 250px;" href="<?='/cart/pay.php?id='.$UUID?>">Вернуться к выбору оплаты</a>
        </section>
	<? }
	
$html = ob_get_clean();
print json_encode($html);	


} // isset

}



}







?>