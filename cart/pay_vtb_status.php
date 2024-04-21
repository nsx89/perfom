<?
    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
	require_once($_SERVER["DOCUMENT_ROOT"] . "/include/payment/vtb_connect.php");
	
    if (!CModule::IncludeModule('iblock')) exit;
    $APPLICATION->SetTitle("Оплата прошла успешно");
    $APPLICATION->SetPageProperty("description", "Официальный сайт Перфом - производство лепнины и архитектурного декора");
    if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
        exit;
    }
	
//$orderID = $_GET['orderId'];
$trans_status = $_GET['dm']; // 1-ок, 0-not
$tranId = $_GET['tranId'];

$pay_status = false;
$err_out = 'Ошибка оплаты';
$err_out_local = '';
$pay_repeat = false; // разрешение на повтор в случае неуспеха

	// установка инфоблока keep_order
	$res = CIBlock::GetList( Array(), Array('CODE'=>'keep_order'));
	while($ar_res = $res->Fetch()) $iblock_id = $ar_res['ID'];

	// Проверка ExtID
	$res = CIBlockElement::GetList(Array(),Array("IBLOCK_ID"=>$iblock_id,'PROPERTY_UUID_PAY'=>$tranId), false, Array(), Array());
	while($ob = $res->GetNextElement()) $item = array_merge($ob->GetFields(), $ob->GetProperties());
	
if (isset($item)) { // Существует	
	
$orderID = $item['UUID']['VALUE'];

$d_info = ''; $d_time = '';
switch ($item["MAIL_DEALER"]['VALUE']) {
		case 'kdvor@decor-evroplast.ru' : { $d_info = 'г. Москва, Каширское ш., д. 19, корп.1, ТК "Каширский двор" 2-й этаж, павильон 2-С90'; $d_time = 'с 9:00 до 21:00'; break;  }
		case 'nahim@decor-evroplast.ru' : { $d_info = 'г. Москва, Нахимовский проспект, д. 24, «ЦДиИ Экспострой на Нахимовском» павильон №3 стенд 195'; $d_time = 'с 10:00 до 20:00'; break; }
		case 'shop@decor-evroplast.ru' : { $d_info = 'г. Москва, Нахимовский проспект, д. 24, «ЦДиИ Экспострой на Нахимовском» павильон №2 стенд 158'; $d_time = 'с 10:00 до 20:00'; break; }
		case 'salonn@decor-evroplast.ru' : { $d_info = 'г. Москва, Нахимовский проспект, д. 24, «ЦДиИ Экспострой на Нахимовском» павильон №3 стенд 49/2'; $d_time = 'с 10:00 до 20:00'; break; }
}

			// Количесво попыток не более 5
			if ($item['Count']['VALUE'] > 5) {
				$err_out_local = 'Количество попыток израсходовано, повтор оплаты невозможен';
			} elseif (strtotime(date('d.m.Y H:i:s')) > MakeTimeStamp($item['DeadlineEnd']['VALUE'], "DD.MM.YYYY HH:MI:SS")) {
				$err_out_local = 'Время предназначенное для оплаты вышло, повтор оплаты невозможен';
			} else { // можно!
			$pay_repeat = true;
			}
			
	if ($trans_status) {		
		
		$Trans_OUT = RequestTransactions($tranId);
		//echo '<pre>';
	    //print_r($Trans_OUT['out']);
		//echo '</pre>';
		
		/* Не актуально
		if (!$Trans_OUT['out']->Transactions[0]->Date) { // повтор запроса
				$Trans_OUT = RequestTransactions($tranId);
		}
		*/

		if ($Trans_OUT['out']->Transactions && $Trans_OUT['out']->Transactions[0]->Date){ // Оплата прошла
		
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
						}
					}
		}
	}
			
$pay_status = true;

} else {

	// установка инфоблока 1с
	$res = CIBlock::GetList( Array(), Array('CODE'=>'Doc1C'));
	while($ar_res = $res->Fetch()) $iblock_id = $ar_res['ID'];

	// Проверка ExtID
	$res_1C = CIBlockElement::GetList(Array(),Array("IBLOCK_ID"=>$iblock_id,'PROPERTY_ID_Trans'=>$tranId), false, Array(), Array());
	while($ob = $res_1C->GetNextElement()) $item_1C = array_merge($ob->GetFields(), $ob->GetProperties());

if (isset($item_1C)) { // Существует 1C	

$orderID = $item_1C['NAME'];

			// Количесво попыток не более 5
			if ($item_1C['Count']['VALUE'] > 5) {
				$err_out_local = 'Количество попыток израсходовано, повтор оплаты невозможен';
			} elseif (strtotime(date('d.m.Y H:i:s')) > MakeTimeStamp($item_1C['DeadlineEnd']['VALUE'], "DD.MM.YYYY HH:MI:SS")) {
				$err_out_local = 'Время предназначенное для оплаты вышло, повтор оплаты невозможен';
			} else { // можно!
			$pay_repeat = true;
			}
	
$pay_status = true;
}		
}

?>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="Content-Language" content="ru"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
	
	<link rel="stylesheet" href="/css/style-new.css?<?=$release?>" type="text/css"/>
  <style>
      body {
        height: auto;
        font-family: 'Open Sans', sans-serif;
        font-size: 14px;
        color: #4e4e4e;
      }
      .e-new-header-offset {
        margin-top: 0;
      }
      .order-resp-sec {
        margin-bottom: 0;
      }
      .order-resp-details-btns-wrap {
        display: flex;
        justify-content: center;
        flex-direction: column;
        align-items: center;
        margin-top: 20px;
      }
      .order-resp-details-btns-wrap a {
        margin-right: 0;
      }
      .order-resp-details-btns-wrap a:last-child {
        margin-right: 0;
        border-color: #4e4e4e;
        color: #4e4e4e;
      }
      .order-resp-details-btns-wrap a:last-child:hover {
        background-color: #4e4e4e;
        color: #fff;
      }
      .order-resp-sec {
        padding-top: 70px;
        margin-bottom: 50px;
      }
  </style>
</head>


<? if (($pay_status) && ($trans_status)) { ?>

    <div class="e-new-cont e-new-header-offset">
        <section class="order-resp-sec">
            <i class="new-icomoon icon-smiling-face"></i>
            <div class="order-resp-result">Оплата прошла успешно!</div>
            <div class="order-resp-desc">Спасибо, что выбрали нас!<br>
										Менеджер свяжется с Вами в течении 30 минут</div>
            <div class="order-resp-details">
			<? if (isset($item)) { ?> 
                <div class="order-resp-details-title">
                    Заказ № <?=$item["NAME"]?>
                </div>
                <div class="order-resp-details-dealer">
                    Принял: фирменный магазин «Перфом»<br>
					<?=$d_info?>
                    </div>
                <div class="order-resp-details-email">
				    Время работы: <?=$d_time?><br>
                    E-mail: <?=$item["MAIL_DEALER"]['VALUE']?><br>
					Телефон: <?=$item["PHONE_DEALER"]['VALUE']?><br>
                </div>
			<? } else { ?>
				<div class="order-resp-details-title">
                    Заказ № <?=$item_1C['OrderNumber']['VALUE']?>
                </div>
			<? } ?>
            </div>
        </section>
    </div>
	
<? } elseif (($pay_status) && (!$trans_status)) { // платеж не выполнен ?>
<?

$Trans_OUT = RequestTransactions($tranId);
		//echo '<pre>';
	    //print_r($Trans_OUT['out']);
		//echo '</pre>';

if ($Trans_OUT['out']->Transactions){ // Оплата прошла
	$err_out = 'Code: '.$Trans_OUT['out']->Transactions[0]->Result->Code.', Message: '.$Trans_OUT['out']->Transactions[0]->Result->Message;
}

?>

	<div class="e-new-cont e-new-header-offset">
        <section class="order-resp-sec">
            <i class="new-icomoon icon-sad-face"></i>
            <div class="order-resp-result">Что-то пошло не так...</div>
            <div class="order-resp-details">
              <div class="order-resp-details-dealer"><?=$err_out?></div>
			  <? if ($err_out_local) { ?>
			  <div class="order-resp-details-dealer"><?=$err_out_local?></div>
			  <? } ?>
              <div class="order-resp-details-dealer">Свяжитесь с менеджером.</div>
              <?/*<div class="order-resp-details-dealer">Чтобы повторить оплату, перезагрузите страницу.</div>*/?>
              <div class="order-resp-details-btns-wrap">
                  <? if ($pay_repeat) { ?>
                    <a class="order-resp-btn order-resp-btn-repeat" style="margin-bottom:15px;" href="/cart/pay.php?id=<?=$orderID?>">Повторить оплату</a>
                    <script>
                      const btn = document.querySelector('.order-resp-btn-repeat');
                      btn.addEventListener('click', setLink);
                      function setLink(e) {
                        e.preventDefault();
                        localStorage.setItem('payHref', e.target.getAttribute('href'));
                      }
                    </script>
                  <? } ?>
                <a class="order-resp-btn order-resp-btn-return" href="/cart">Вернуться в корзину</a>
              </div>
            </div>
        </section>
    </div>
  <script>
    const btnRet = document.querySelector('.order-resp-btn-return');
    btnRet.addEventListener('click', setLink);
    function setLink(e) {
      e.preventDefault();
      localStorage.setItem('payHref', e.target.getAttribute('href'));
    }
  </script>

<? } else { ?>

<div class="e-new-cont e-new-header-offset">
		<section class="order-resp-sec">
		<div class="order-resp-details">
		Ошибка системы. Требуется уточнение причины.
		</div>
		<a class="order-resp-btn" href="/catalogue">Назад в каталог</a>
		</section>
</div>
  <script>
    const btn = document.querySelector('.order-resp-btn');
    btn.addEventListener('click', setLink);
    function setLink(e) {
      e.preventDefault();
      localStorage.setItem('payHref', e.target.getAttribute('href'));
    }
  </script>
<? } ?>
<script>
  localStorage.setItem('hideTimer', 'y');
</script>
