<?
ini_set('display_errors', 1);
//error_reporting(E_ALL);

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/include/payment/vtb_connect.php");

?>
<?
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
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

    return ($uuid);
}*/

// Запрос ID
if (isset($_GET['id'])) $UUID = $_GET['id'];

// Тест блок
//if (strpos($_GET['id'],'Doc1C_') === false) die();

global $headers;

// тех запрос
if (strcmp($_SERVER['REQUEST_METHOD'],'POST') == 0) { // Пост запрос


	// установка инфоблока keep_order
	$res = CIBlock::GetList( Array(), Array('CODE'=>'keep_order'));
	while($ar_res = $res->Fetch()) $iblock_id = $ar_res['ID'];

	if ($_POST['param'] == 'check') { // Запрос с ЛК менеджера
	
		$res = CIBlockElement::GetList(Array(),Array("IBLOCK_ID"=>$iblock_id,'ID'=>$_POST['id']), false, Array(), Array());
		while($ob = $res->GetNextElement()) $item = array_merge($ob->GetFields(), $ob->GetProperties());
		if (isset($item)) { // Существует
			$stmp = MakeTimeStamp($item['Trans_DeadlineEnd']['VALUE'], "DD.MM.YYYY HH:MI:SS");
			$pay_interval = $stmp - time();
			
			if ($pay_interval > 0) { // редактирование невозможно
					echo json_encode(array('stat' => 0, 'time' => $pay_interval));
			} else {
					echo json_encode(array('stat' => 1, 'time' => ''));
					CIBlockElement::SetPropertyValueCode($item['ID'], 'M_Block', 'Y');
			}	
		}
		
	die();
	}

	// Проверка ExtID
	$res = CIBlockElement::GetList(Array(),Array("IBLOCK_ID"=>$iblock_id,'PROPERTY_UUID'=>$UUID), false, Array(), Array());
	while($ob = $res->GetNextElement()) $item = array_merge($ob->GetFields(), $ob->GetProperties());
	
	if (isset($item)) { // Существует
	
		$Trans_OUT = RequestTransactions($item['UUID_PAY']['VALUE']);
		if (($Trans_OUT['out']->InProcess) && ($Trans_OUT['out']->InProcess[0]->ExternalPayment->ExternalProcessingStatus == 10)) $trans_status = 1;
		else $trans_status = 2;
		
	} else { // 1c ID
		// установка инфоблока 1с
		$res = CIBlock::GetList( Array(), Array('CODE'=>'Doc1C'));
		while($ar_res = $res->Fetch()) $iblock_id = $ar_res['ID'];

		// Проверка ExtID
		$res_1C = CIBlockElement::GetList(Array(),Array("IBLOCK_ID"=>$iblock_id,"NAME"=>$UUID), false, Array(), Array());
		while($ob = $res_1C->GetNextElement()) $item_1C = array_merge($ob->GetFields(), $ob->GetProperties());

			if (isset($item_1C)) { // Существует	
			$Trans_OUT = RequestTransactions($item_1C['ID_Trans']['VALUE']);
			if (($Trans_OUT['out']->InProcess) && ($Trans_OUT['out']->InProcess[0]->ExternalPayment->ExternalProcessingStatus == 10)) $trans_status = 1;
			else $trans_status = 2;
			}
	}

echo $trans_status;
die;
}

$pay_url ='';
$err_out = 'Нет такого заказа!!!';

if ($UUID) { 
$err_out = 'Ошибка платежной системы, обновите браузер';
	// установка инфоблока keep_order
	$res = CIBlock::GetList( Array(), Array('CODE'=>'keep_order'));
	while($ar_res = $res->Fetch()) $iblock_id = $ar_res['ID'];

	// Проверка ExtID
	$res = CIBlockElement::GetList(Array(),Array("IBLOCK_ID"=>$iblock_id,'PROPERTY_UUID'=>$UUID), false, Array(), Array());
	while($ob = $res->GetNextElement()) $item = array_merge($ob->GetFields(), $ob->GetProperties());

/*
	$arFilter = Array("IBLOCK_CODE"=>"keep_order","ACTIVE"=>'Y','PROPERTY_UUID'=>$UUID);
	$res = CIBlockElement::GetList(Array(),$arFilter);
if ($res->SelectedRowsCount() > 0) {
	$item = $res->GetNextElement();
	$item = array_merge($item->GetFields(), $item->GetProperties());
*/
if (isset($item)) { // Существует

	// проверка на предмет оплачено
	if ($item['PAYMENT_STATUS']['VALUE'] == 'оплачено')
	{ // оплата прошла, редирект на страницу оплаты 
			echo json_encode(array('success'=>'true'));	
			die;
	}	

	// проверка состояния другой платежной системы
	if ((strtotime(date('d.m.Y H:i:s')) < MakeTimeStamp($item['Trans_DeadlineEnd']['VALUE'], "DD.MM.YYYY HH:MI:SS")) && ($item['TYPE_PAY']['VALUE'] == 'S')) 
	{ // оплата сформирована, позиционирование на нужныю оплату
			echo json_encode(array('url'=>'&pay=2'));	
			die;
	}	

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
}

$pay_ok = false;
	
	$Trans_OUT = RequestTransactions($item['UUID_PAY']['VALUE']);
	//echo '<pre>';
	//print_r($Trans_OUT['out']);
	//echo '</pre>';
	
	if ($item['M_Block']['VALUE'] == 'Y') { // Блокировано менеджером
		$err_out = 'Оплата блокирована Менеджером,<br>идет редактирование заказа';
		
	} elseif ((!$Trans_OUT['out']->InProcess) || (!$item['UUID_PAY']['VALUE'])) { // ссылка потухла или не создана - создаем новую на базе данных
		//if (($Trans_OUT['out']->Transactions) && ($Trans_OUT['out']->Transactions[0]->Result->Code == 0)){ // Оплата прошла
		if (($Trans_OUT['out']->Transactions) && ($Trans_OUT['out']->Transactions[0]->Result->Code == 0) && ($Trans_OUT['out']->Transactions[0]->State == 400) && ($Trans_OUT['out']->Transactions[0]->Substate == 411)){ // Оплата прошла
		//if ($item['PAYMENT_STATUS']['VALUE'] == 'оплачено') {
				$pay_ok = true;
		} else {
			// Количесво попыток не более 5
			if ($item['Count']['VALUE'] > 5) {
				$err_out = 'Количество попыток израсходовано, обратитесь к&nbsp;менеджеру';
			} elseif (strtotime(date('d.m.Y H:i:s')) > MakeTimeStamp($item['DeadlineEnd']['VALUE'], "DD.MM.YYYY HH:MI:SS")) {
				$err_out = 'Время предназначенное для&nbsp;оплаты вышло, обратитесь к&nbsp;менеджеру';
			} elseif (((strtotime(date('d.m.Y H:i:s')) < MakeTimeStamp($item['PAY_DATE']['VALUE'], "DD.MM.YYYY HH:MI:SS")) && $item['PAY_DATE']['VALUE']) || ($item['TYPE_PAY']['VALUE'] == 'S')) {
				$err_out = 'Идет формаривание платежа, обновите страницу';
				$err_build = true;					
			} else { // Создание новой транзакции
			
		// первоначальное время для задержки формирования	
		$stmp = MakeTimeStamp(date("d.m.Y H:i:s"), "DD.MM.YYYY HH:MI:SS");
		$stmp = AddToTimeStamp(array("SS" => 20),$stmp);	
		CIBlockElement::SetPropertyValueCode($item['ID'], 'PAY_DATE', date("d.m.Y H:i:s", $stmp));
			
		$ID_Trans = generateUUID();
		
		if ($item["TOTAL_SALE"]["VALUE"]) $price = $item["TOTAL_SALE"]["VALUE"];
		else $price = $item["TOTAL"]["VALUE"];
		if ($price > $item["PAY_TOTAL"]["VALUE"]) $price = $price - $item["PAY_TOTAL"]["VALUE"];
		else exit; // !!! доработать на ошибку генерации

		$orderNumber = $item["NAME"];
		$orderData = explode(" ",$item['DATE']['VALUE']);
		$orderData = $orderData[0];

		$amount = $price;
		$taxSum = round($price*20/120,2)*100;
		
		$new_amount = $price;
		$new_phone = preg_replace('/^\+?(8|7)/', '7', $item["USER_PHONE"]["VALUE"]);
		$new_email = $item["USER_MAIL"]["VALUE"];
		$new_description = "Заказ‎ ".$orderNumber."‎ от‎ ".$orderData;
		$CustomerName = '';
		$CustomerINN = '';
	
		//$Trans_NEW = NewTransactions($item['UUID']['VALUE'],$ID_Trans,$new_amount,$new_phone,$new_email,$new_description,'evroplast.ru',$CustomerName,$CustomerINN);
		$Trans_NEW = NewTransactions($ID_Trans,$ID_Trans,$new_amount,$new_phone,$new_email,$new_description,'perfom.ru',$CustomerName,$CustomerINN);
		$pay_url = $Trans_NEW['out']->Transaction->ExternalPayment->Link;
		if (!$pay_url) $err_out = 'Ошибка при создании новой транзакции';
		
		//echo $ID_Trans.' - '.$ID_Trans.' - '.$new_amount.' - '.$new_phone.' - '.$new_email.' - '.$new_description.' - '.'evroplast.ru'.' - '.$CustomerName.' - '.$CustomerINN;
		//echo '<pre>';
	    //print_r($Trans_NEW['out']);
		//echo '</pre>';
		
			$DateTrans = Date_OUT_PS($Trans_NEW['out']->Transaction->Deadline);
			$stmp = MakeTimeStamp($DateTrans, "DD.MM.YYYY HH:MI:SS");
			// $stmp = AddToTimeStamp(array("HH" => 3), $stmp); // Не понятно почему позврят по времени клиента.
			$Trans_DeadlineEnd = date("d.m.Y H:i:s", $stmp);
		
		if (MakeTimeStamp($DateTrans, "DD.MM.YYYY HH:MI:SS") > MakeTimeStamp($item['DeadlineEnd']['VALUE'], "DD.MM.YYYY HH:MI:SS")) {
				// Двигаем дедлайн
				CIBlockElement::SetPropertyValueCode($item['ID'], 'DeadlineEnd', $Trans_DeadlineEnd);
		}
	
		CIBlockElement::SetPropertyValueCode($item['ID'], 'UUID_PAY', $ID_Trans);
		//CIBlockElement::SetPropertyValueCode($item['ID'], 'PAY_DATE', Date_OUT_PS($Trans_NEW['out']->Transaction->Date));
		CIBlockElement::SetPropertyValueCode($item['ID'], 'PAY_DATE', date("d.m.Y H:i:s"));
		CIBlockElement::SetPropertyValueCode($item['ID'], 'Trans_DeadlineEnd', $Trans_DeadlineEnd);
		CIBlockElement::SetPropertyValueCode($item['ID'], 'PAY_URL', $Trans_NEW['out']->Transaction->ExternalPayment->Link);
		CIBlockElement::SetPropertyValueCode($item['ID'], 'Count', $item['Count']['VALUE']+1);
		CIBlockElement::SetPropertyValueCode($item['ID'], 'PAY_STATUS', "Y");
		CIBlockElement::SetPropertyValueCode($item['ID'], 'PAYMENT_STATUS', "ожидание оплаты"); 
		// Удаление QR оплаты если такая была
		CIBlockElement::SetPropertyValueCode($item['ID'], 'QR_ID', ''); 
		CIBlockElement::SetPropertyValueCode($item['ID'], 'QR_IMG_DATA', ''); 
		}}
	
	} else { // в процессе
	$pay_url = $Trans_OUT['out']->InProcess[0]->ExternalPayment->Link;	
	$Trans_DeadlineEnd = $item["Trans_DeadlineEnd"]["VALUE"];
		/*if (!$pay_url) $err_out = 'Завершите оплату, если она открыта у Вас в другой вкладке, или повторите вход через '.ceil((MakeTimeStamp($Trans_DeadlineEnd, "DD.MM.YYYY HH:MI:SS")-time())/60).' минут для получения результата оплаты или повторной оплаты';*/
      if (!$pay_url) $err_out = 'Завершите оплату, если она открыта у&nbsp;Вас в&nbsp;другой вкладке, или&nbsp;повторите вход через <span data-type="timer" data-val="error-timer" data-interval="'.(MakeTimeStamp($Trans_DeadlineEnd, "DD.MM.YYYY HH:MI:SS")-time()).'"></span> для&nbsp;получения результата оплаты или&nbsp;повторной оплаты';
	}

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
	if ((strtotime(date('d.m.Y H:i:s')) < MakeTimeStamp($item_1C['Trans_DeadlineEnd']['VALUE'], "DD.MM.YYYY HH:MI:SS")) && ($item_1C['TYPE_PAY']['VALUE'] == 'S')) 
	{ // оплата сформирована, позиционирование на нужныю оплату
			echo json_encode(array('url'=>'&pay=2'));	
			die;
	}	

$err_out = 'Ошибка платежной системы, обновите браузер';

	if ($item_1C['Block']['VALUE'] == 'Y') { // Транзакция отменена
		$err_out = 'Платеж недействителен';
	} else {
	
	$Trans_OUT = RequestTransactions($item_1C['ID_Trans']['VALUE']);
	//echo '<pre>';
	//    print_r($Trans_OUT['out']);
	//echo '</pre>';
		
		if ((!$Trans_OUT['out']->InProcess) || (!$item_1C['ID_Trans']['VALUE'])) { // ссылка потухла или не создана - создаем новую на базе данных
		if (($Trans_OUT['out']->Transactions) && ($Trans_OUT['out']->Transactions[0]->Result->Code == 0) && ($Trans_OUT['out']->Transactions[0]->State == 400) && ($Trans_OUT['out']->Transactions[0]->Substate == 411)){ // Оплата прошла
				$pay_ok = true;
		} else {
			// Количесво попыток не более 5
			if ($item_1C['Count']['VALUE'] > 5) {
				$err_out = 'Количество попыток израсходовано, обратитесь к&nbsp;менеджеру';
			} elseif (strtotime(date('d.m.Y H:i:s')) > MakeTimeStamp($item_1C['DeadlineEnd']['VALUE'], "DD.MM.YYYY HH:MI:SS")) {
				$err_out = 'Время предназначенное для&nbsp;оплаты вышло, обратитесь к&nbsp;менеджеру';
			} elseif (((strtotime(date('d.m.Y H:i:s')) < MakeTimeStamp($item_1C['PAY_DATE']['VALUE'], "DD.MM.YYYY HH:MI:SS")) && $item_1C['PAY_DATE']['VALUE']) || ($item_1C['TYPE_PAY']['VALUE'] == 'S')) {
				$err_out = 'Идет формаривание платежа, обновите страницу';	
				$err_build = true;	
			} else { // Создание новой транзакции
		
		// первоначальное время для задержки формирования	
		$stmp = MakeTimeStamp(date("d.m.Y H:i:s"), "DD.MM.YYYY HH:MI:SS");
		$stmp = AddToTimeStamp(array("SS" => 20),$stmp);
		CIBlockElement::SetPropertyValueCode($item_1C['ID'], 'PAY_DATE', date("d.m.Y H:i:s", $stmp));
			
		$ID_Trans = generateUUID();
		
		/* старый формат
		$new_amount = $Trans_OUT['out']->Transactions[0]->Amount;
		$new_phone = $Trans_OUT['out']->Transactions[0]->ReceiptPhone;
		$new_email = $Trans_OUT['out']->Transactions[0]->ReceiptEmail;
		$new_description = $Trans_OUT['out']->Transactions[0]->Description;
		*/
		$new_amount = $item_1C['Sum']['VALUE'];
		$new_phone = ''; // Телефон не предусмотрен
		$new_email = $item_1C['CustomerEmail']['VALUE'];
		$new_description = "Заказ‎ ".$item_1C['OrderNumber']['VALUE']."‎ от‎ ".$item_1C['OrderDate']['VALUE'];
		$CustomerName = $item_1C['CustomerName']['VALUE'];
		$CustomerINN = $item_1C['CustomerINN']['VALUE'];
	
		$Trans_NEW = NewTransactions($item_1C['NAME'],$ID_Trans,$new_amount,$new_phone,$new_email,$new_description,'1C_P',$CustomerName,$CustomerINN);
		$pay_url = $Trans_NEW['out']->Transaction->ExternalPayment->Link;
		if (!$pay_url) { 
			$err_out = 'Ошибка при&nbsp;создании новой транзакции';
			$ID_Trans = '';
		} else {
		
		//echo '<pre>';
	    //print_r($Trans_NEW['out']);
		//echo '</pre>';
		
			$DateTrans = Date_OUT_PS($Trans_NEW['out']->Transaction->Deadline);
			$stmp = MakeTimeStamp($DateTrans, "DD.MM.YYYY HH:MI:SS");
			// $stmp = AddToTimeStamp(array("HH" => 3), $stmp); // Не понятно почему позврят по времени клиента.
			$Trans_DeadlineEnd = date("d.m.Y H:i:s", $stmp);
		
		// echo $DateTrans.' ---- '.$item_1C['DeadlineEnd']['VALUE']; // проверка дедлайна
		if (MakeTimeStamp($DateTrans, "DD.MM.YYYY HH:MI:SS") > MakeTimeStamp($item_1C['DeadlineEnd']['VALUE'], "DD.MM.YYYY HH:MI:SS")) {
				// Двигаем дедлайн
				CIBlockElement::SetPropertyValueCode($item_1C['ID'], 'DeadlineEnd', $Trans_DeadlineEnd);
		}
	
		CIBlockElement::SetPropertyValueCode($item_1C['ID'], 'ID_Trans', $ID_Trans);
		CIBlockElement::SetPropertyValueCode($item_1C['ID'], 'PAY_DATE', date("d.m.Y H:i:s"));
		CIBlockElement::SetPropertyValueCode($item_1C['ID'], 'Trans_DeadlineEnd', $Trans_DeadlineEnd);
		CIBlockElement::SetPropertyValueCode($item_1C['ID'], 'PAY_URL', $Trans_NEW['out']->Transaction->ExternalPayment->Link);
		CIBlockElement::SetPropertyValueCode($item_1C['ID'], 'Count', $item_1C['Count']['VALUE']+1);
		// Удаление QR оплаты если такая была
		CIBlockElement::SetPropertyValueCode($item_1C['ID'], 'QR_ID', ''); 
		CIBlockElement::SetPropertyValueCode($item_1C['ID'], 'QR_IMG_DATA', ''); 
		}}}
	
	} else { // в процессе
	$pay_url = $Trans_OUT['out']->InProcess[0]->ExternalPayment->Link;	
	$Trans_DeadlineEnd = $item_1C['Trans_DeadlineEnd']['VALUE'];
		//if (!$pay_url) $err_out = 'Завершите оплату, если она открыта у Вас в другой вкладке, или повторите вход через '.ceil((MakeTimeStamp($Trans_DeadlineEnd, "DD.MM.YYYY HH:MI:SS")-time())/60).' минут для получения результата оплаты или повторной оплаты';
        if (!$pay_url) $err_out = 'Завершите оплату, если она открыта у&nbsp;Вас в&nbsp;другой вкладке, или&nbsp;повторите вход через <span data-type="timer" data-val="error-timer" data-interval="'.(MakeTimeStamp($Trans_DeadlineEnd, "DD.MM.YYYY HH:MI:SS")-time()).'"></span> для&nbsp;получения результата оплаты или&nbsp;повторной оплаты';
	}	
	} // block
			
} // isset

}} // !item

ob_start();
 if ($pay_url) {
	 $stmp = MakeTimeStamp($Trans_DeadlineEnd, "DD.MM.YYYY HH:MI:SS");
     $pay_interval = $stmp - time()-5;
	 //echo '<pre>';
	 //   print_r($Trans_OUT['out']);
	 //echo '</pre>';
   ?>

    <div style="height: 700px; overflow: hidden;" data-interval="<?=$pay_interval?>" data-type="iframe-wrap" class="pay-iframe-wrap">
      <iframe allowPaymentRequest="true" src="<?=$pay_url?>" style="height: 100%; width:100%;" type="text/html" frameborder="0" align="middle"></iframe>
    </div>



     <?/*<div style="height: 100vh; overflow: hidden;" data-interval="<?=$pay_interval?>" data-type="iframe-wrap" class="pay-iframe-wrap">
      <iframe src="https://eplast.portu.by/cart/pay_vtb_status.php?orderId=0e7de396-ebc6-4eb3-8741-49004d2eea04&dm=0&operation=100&tranId=9D3322BA-3E8C-4B1C-827B-B977E4C8E830" style="height: 100%; width:100%;" type="text/html" frameborder="0" align="middle"></iframe>
      </div>*/?>

<? } else { ?>
<? if ($pay_ok) { ?>

<section class="order-resp-sec cont-success">
 <div class="order-resp-result">Оплата прошла успешно!</div>
 <div class="order-resp-desc">Спасибо, что выбрали нас!</div>
</section>

<? } elseif ($err_build) { ?>

<section class="order-resp-sec">
    <div class="order-resp-result">Что-то пошло не&nbsp;так...</div>
    <div class="order-resp-details">
      <div class="order-resp-details-dealer">
        Оплата не может быть сформирована, т.к.&nbsp;Вы уже оплачиваете на&nbsp; другом устройстве или&nbsp;странице. Завершите оплату там или&nbsp;повторите платеж по&nbsp;окончанию времени для&nbsp;оплаты.
      </div>
    </div>
</section>

<? } else { ?>

<section class="order-resp-sec">
    <i class="new-icomoon icon-sad-face"></i>
    <div class="order-resp-result">Что-то пошло не&nbsp;так...</div>
    <div class="order-resp-details">
      <div class="order-resp-details-dealer"><?=$err_out?></div>
    </div>
</section>

<? } ?>
<? }
$html = ob_get_clean();
print json_encode($html);