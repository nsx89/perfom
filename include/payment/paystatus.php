<?
$_SERVER["DOCUMENT_ROOT"] = '/home/bitrix/www';

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS",true);
define('CHK_EVENT', true);

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php" );
require_once($_SERVER["DOCUMENT_ROOT"]."/include/payment/vtb_connect.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/include/payment/sbp_connect.php");

@set_time_limit(0);
@ignore_user_abort(true);

if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
exit;
}

// установка инфоблока keep_order
$res = CIBlock::GetList( Array(), Array('CODE'=>'keep_order'));
while($ar_res = $res->Fetch()) $iblock_id = $ar_res['ID'];

$res = CIBlockElement::GetList(Array(),Array("IBLOCK_ID"=>$iblock_id,"PROPERTY_PAY_STATUS"=>'Y'), false, Array(), Array());

while ($ob = $res->GetNextElement()) {
	$item = array_merge($ob->GetFields(), $ob->GetProperties());
	
if (!$item['QR_ID']['VALUE']) {	// Оплата ВТБ
	
	if ($item['UUID_PAY']['VALUE']) $tranId = $item['UUID_PAY']['VALUE'];
	else echo 'id='.$item['ID'].' - Какой то касяк сброса флага оплаты, пустое значение ID транзакции!<BR>';
	
	$Trans_OUT = RequestTransactions($tranId);
	
	if ($Trans_OUT['out']->Transactions && $Trans_OUT['out']->Transactions[0]->Date && ($Trans_OUT['out']->Transactions[0]->Result->Code == 0) && ($Trans_OUT['out']->Transactions[0]->State == 400) && ($Trans_OUT['out']->Transactions[0]->Substate == 411)) { // Оплата прошла
		
			$amount = $Trans_OUT['out']->Transactions[0]->Amount;
			
			$DateTrans = Date_OUT_PS($Trans_OUT['out']->Transactions[0]->Date);
			$stmp = MakeTimeStamp($DateTrans, "DD.MM.YYYY HH:MI:SS");
			$stmp = AddToTimeStamp(array("HH" => 3), $stmp);
			$trans_date = date("d.m.Y H:i:s", $stmp);
			
			$DateTrans = Date_OUT_PS($Trans_OUT['out']->Transactions[0]->Deadline);
			$stmp = MakeTimeStamp($DateTrans, "DD.MM.YYYY HH:MI:SS");
			$stmp = AddToTimeStamp(array("HH" => 3), $stmp);
			$trans_deadline = date("d.m.Y H:i:s", $stmp);
			
		
		
		
				// Формирование записи оплаты
				$arFilter_receipt = Array("IBLOCK_CODE"=>"payment","ACTIVE"=>'Y',"PROPERTY_PAY_orderID"=>$tranId);
				$res_receipt = CIBlockElement::GetList(Array(),$arFilter_receipt);
				if (!$item_receipt = $res_receipt->GetNextElement()) { // нет записи, формирование маски
					$PROD = array();
					$PROD['PAY_orderID'] = $tranId;
					$PROD['UUID_PAY'] = $tranId;
					$PROD['Date_Trans'] = $trans_date;
					$PROD['expirationDate'] = $trans_deadline;
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
						
						echo 'id='.$item['ID'].' - Новая оплата оплачена!<BR>';
						} else echo 'id='.$item['ID'].' - Ошибка записи базы!<BR>';
		
					} else echo 'id='.$item['ID'].' - Есть такая оплата!<BR>'; // Так вообще не должно быть
					
		} else { // Состояние не успешно, почему? :)
				if ($Trans_OUT['out']->Transactions && $Trans_OUT['out']->Transactions[0]->Date) { // оплата не прошла видимо, проверка после дедлайна
					
					if (strtotime(date('d.m.Y H:i:s')) > MakeTimeStamp($item['Trans_DeadlineEnd']['VALUE'], "DD.MM.YYYY HH:MI:SS")) { // Время транзакции вышло
						CIBlockElement::SetPropertyValueCode($item['ID'], 'PAY_STATUS', "N");
						CIBlockElement::SetPropertyValueCode($item['ID'], 'PAYMENT_STATUS', "не оплачено");
						echo 'id='.$item['ID'].' - Установка статуса --не плачено--!<BR>';
					}
				}
		}
} else { // Оплата СБП

		$check_out = check_QR_ID($item['QR_ID']['VALUE']);
	
		/*
			echo '<pre>';
			print_r($check_out['out']);
			echo '</pre>';
			echo '<br>';
			echo $check_out['error'];
			echo '<br>';
			//echo $check_out['out']->NSPK_JSON_RESP_DATA->data[0]->code;
		*/		
	
	if ($check_out['out']->NSPK_JSON_RESP_DATA->data[0]->status == 'ACWP') { // Оплата прошла
	
				$tranId = preg_replace('/-/','',$item['UUID']['VALUE']); 
	
				if ($item["TOTAL_SALE"]["VALUE"]) $amount = $item["TOTAL_SALE"]["VALUE"];
				else $amount = $item["TOTAL"]["VALUE"];
	
				// Формирование записи оплаты
				$arFilter_receipt = Array("IBLOCK_CODE"=>"payment","ACTIVE"=>'Y',"PROPERTY_PAY_orderID"=>$tranId);
				$res_receipt = CIBlockElement::GetList(Array(),$arFilter_receipt);	
				
				$stmp = MakeTimeStamp($item['PAY_DATE']['VALUE'], "DD.MM.YYYY HH:MI:SS");
				$stmp = AddToTimeStamp(array("MI" => 10), $stmp);
				$expirationDate = date("d.m.Y H:i:s", $stmp);
				
				if (!$item_receipt = $res_receipt->GetNextElement()) { // нет записи, формирование маски
					$PROD = array();
					$PROD['PAY_orderID'] = $tranId;
					$PROD['UUID_PAY'] = $item['UUID']['VALUE'];
					$PROD['QR_ID'] = $item['QR_ID']['VALUE'];
					$PROD['Date_Trans'] = $item['PAY_DATE']['VALUE'];
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
						
						} else echo 'id='.$item['ID'].' - Ошибка записи базы!';
					
					} else echo 'id='.$item['ID'].' - Есть такая оплата!'; // Так вообще не должно быть
					
					
	} else { // Состояние не успешно, почему? :)
				
					if (strtotime(date('d.m.Y H:i:s')) > MakeTimeStamp($item['DeadlineEnd']['VALUE'], "DD.MM.YYYY HH:MI:SS")) { // Время транзакции вышло
						CIBlockElement::SetPropertyValueCode($item['ID'], 'PAY_STATUS', "N");
						CIBlockElement::SetPropertyValueCode($item['ID'], 'PAYMENT_STATUS', "не оплачено");
						echo 'id='.$item['ID'].' - Установка статуса --не плачено--!<BR>';
					} else echo 'id='.$item['ID'].' - Ждем!'; //
		   }
}
}


if (!$res->SelectedRowsCount()) {
	echo '<BR>Нет оплаты в ожидании!!! :(<BR>';
	exit;
}



?>