<?
/* провки на исключения выборки - напоминалка
top-fix-region-new.php, header-new.php
if ((($my_city_fix) || ($getReg == 'select')) && (strpos($_SERVER['REQUEST_URI'],'pay.php') === false)) 
if(($loc['ID'] == 3109) && (strpos($_SERVER['REQUEST_URI'],'pay.php') === false))	
*/

//https://perfom-decor.ru/cart/pay.php?id=D05AF9AF-5D66-424A-BC80-3D13912673C0

ini_set('display_errors', 1);
//error_reporting(E_ALL);

    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/include/payment/vtb_connect.php");
	require_once($_SERVER["DOCUMENT_ROOT"] . "/include/payment/sbp_connect.php");

    if (!CModule::IncludeModule('iblock')) exit;
    $APPLICATION->SetTitle("Оплата товара");
    $APPLICATION->SetPageProperty("description", "Официальный сайт Перфом - производство лепнины и архитектурного декора");
    if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
        exit;
    }
	
if (isset($_GET['id'])) $UUID = $_GET['id'];
if (isset($_GET['pay'])) $pay_method = $_GET['pay'];
    
	// тест
	//$UUID = "fed7eb7b-acb6-40fe-88a9-44380fce40c3";
	//$UUID = "fd510426-f3dd-43b4-a84c-46121ba04cb0";

	if (!$UUID) die();

    global $headers;

/*
     // для отладки
    $scan_h = getallheaders();

    //echo '<pre>';
    //print_r($_SERVER);
    //echo '</pre>';

    $response = json_encode($_SERVER).PHP_EOL;
    $response .= json_encode($scan_h).PHP_EOL;
    $response .= json_encode($_GET).PHP_EOL;
    $response .= json_encode($_POST).PHP_EOL;
    $response .= json_encode($_REQUEST).PHP_EOL;
    $response .= file_get_contents("php://input").PHP_EOL;

    $fp = fopen($_SERVER["DOCUMENT_ROOT"].'/cart/response.log','a');
    fwrite($fp, $response);
    fclose($fp);
*/    

// Внешний ID проверка и отдача фиксации
    if (strcmp($_SERVER['REQUEST_METHOD'],'POST') == 0) { // Пост запрос
        $error_1c =''; // Ошибка
        $dns_scan = dns_get_record("pid.evroplast.ru", DNS_A); // доступ только по ip списку домена
		//echo '<pre>'; print_r($dns_scan); echo '</pre>';
//$ip_client = '85.140.49.98'; //$_SERVER['REMOTE_ADDR'];
        $ip_client = $_SERVER['REMOTE_ADDR'];
//$user_aut = 'Basic bWFuX3NpdGVAZGVrb3IuZGVtbzoxMjMxMjMxMjM='; //$_SERVER['REDIRECT_REMOTE_USER']; 
        $user_aut = $_SERVER['REMOTE_USER']; //$_SERVER['REDIRECT_REMOTE_USER'];
        $body_post = file_get_contents("php://input");


        /*
        $body_post = '{
        "TypeRequest":"Reg",
        "ID":"",
        "ExtID":"Doc1C_2377f1eb-1766-11eb-80ce-ac1f6bba5a52",
        "OrderNumber":"ХД30015337",
        "OrderDate":"2019-11-05",
        "Sum":236.00,
        "CustomerEmail":"n.stepin@decor-evroplast.ru",
        "CustomerName":"",
        "CustomerINN":"",
        "Date":""
        }'; // Структура тельца :)
        */


        // авторизация, кик 401
        if (strpos($user_aut, base64_encode($vtbUser.":".$vtbPassword)) === false) {
			header('HTTP/1.1 401 Unauthorized user');
            die;
        }
        // проверка ip
        $ip_ok = false;
        foreach($dns_scan as $p_ip) if ($ip_client == $p_ip['ip']) $ip_ok = true;
        if (!$ip_ok) {
            header('HTTP/1.1 401 Unauthorized ip');
            die;
        }
		
		
		/* // проверка ip
        $ip_ok = false;
        if ($ip_client == '195.16.123.89') $ip_ok = true;
        if (!$ip_ok) {
            header('HTTP/1.1 401 Unauthorized ip');
            die;
        }
		*/

        $body_post = json_decode($body_post,true);
        if (!$body_post) {
            $error_1c = 'Ошибка парсера';
            echo $error_1c;
            die;
        }

// установка инфоблока 1с
        $res = CIBlock::GetList( Array(), Array('CODE'=>'Doc1C'));
        while($ar_res = $res->Fetch()) $iblock_id = $ar_res['ID'];

        if (strcasecmp($body_post['TypeRequest'],'Reg') == 0) {

// Проверка ExtID
            $res = CIBlockElement::GetList(Array(),Array("IBLOCK_ID"=>$iblock_id,"NAME"=>$body_post['ExtID']), false, Array(), Array());
            while($ob = $res->GetNextElement()) $item = array_merge($ob->GetFields(), $ob->GetProperties());

            if ($body_post['Date']) $DateTrans = Date_IN_1C($body_post['Date']);
            else $DateTrans = date('d.m.Y H:i:s');

            $stmp = MakeTimeStamp($DateTrans, "DD.MM.YYYY HH:MI:SS");
            $stmp = AddToTimeStamp(array("HH" => 12), $stmp);
            $DeadlineEnd = date("d.m.Y H:i:s", $stmp); // 06.05.2005 11:32:00

            $ID_Trans = $body_post['ID'];
            $pay_url = '';

            if ($body_post['ID']) {

                // Проверка транзакции.
                $Trans_OUT = RequestTransactions($ID_Trans);

                if (!empty($Trans_OUT['out']->InProcess)) $pay_url = $Trans_OUT['out']->InProcess[0]->ExternalPayment->Link; // ссылка жива
            }

            $PROP = Array();
            $PROP['ID_Trans'] = $ID_Trans;
            $PROP['OrderNumber'] = $body_post['OrderNumber'];
            $PROP['OrderDate'] = Date_IN_1C($body_post['OrderDate']);
            $PROP['Sum'] = $body_post['Sum'];
            $PROP['Date'] = $DateTrans;
            $PROP['Count'] = 1;
            $PROP['DeadlineEnd'] = $DeadlineEnd;
            $PROP['CustomerEmail'] = $body_post['CustomerEmail'];
            $PROP['CustomerName'] = $body_post['CustomerName'];
            $PROP['CustomerINN'] = $body_post['CustomerINN'];
            $PROP['PAY_URL'] = $pay_url;
            if ($body_post['Deadline']) $PROP['Trans_DeadlineEnd'] = Date_OUT_PS($body_post['Deadline']);
            $PROP['Block'] = 'N';

            if (isset($item)) { // Существует

                $el = new CIBlockElement;
                $res = $el->Update( $item['ID'], Array("ACTIVE" => "Y","NAME" => $body_post['ExtID']));
                CIBlockElement::SetPropertyValuesEx(
                    $item['ID'],
                    $item['IBLOCK_ID'],
                    $PROP
                );

                echo json_encode(array('id' => $body_post['ExtID'], 'result' => true, 'Deadline' => Date_OUT_1C($DeadlineEnd)));
            } else { // Новая регистрация

                $el = new CIBlockElement;
                $save_member = Array(
                    "IBLOCK_SECTION_ID" => false,
                    "IBLOCK_ID"         => $iblock_id,
                    "PROPERTY_VALUES"   => $PROP,
                    "NAME"              => $body_post['ExtID'],
                    "ACTIVE"            => "Y"
                );

                if($el->Add($save_member)) {
                    echo json_encode(array('id' => $body_post['ExtID'], 'result' => true, 'Deadline' => Date_OUT_1C($DeadlineEnd)));
                } else {
                    echo json_encode(array('id' => $body_post['ExtID'], 'result' => false));
                }

            }
            die;
        } elseif (strcasecmp($body_post['TypeRequest'],'Check') == 0) {
            // Проверка ExtID
            $res = CIBlockElement::GetList(Array(),Array("IBLOCK_ID"=>$iblock_id,"NAME"=>$body_post['ExtID']), false, Array(), Array());
            while($ob = $res->GetNextElement()) $item = array_merge($ob->GetFields(), $ob->GetProperties());

            if (isset($item)) { // Существует
                $status_isLast = false;
                if (($item['Count']['VALUE'] > 5) || ($item['Block']['VALUE'] == 'Y')) $status_isLast = true;
                if (strtotime(date('d.m.Y H:i:s')) > MakeTimeStamp($item['DeadlineEnd']['VALUE'], "DD.MM.YYYY HH:MI:SS")) $status_isLast = true;

                if (!$item['ID_Trans']['VALUE']) echo json_encode(array('id' => '', 'Deadline' => '', 'isLast' => $status_isLast));
                else {

                    /* Убираем чтение транзакции по причине разного времени состояния
                            // Проверка транзакции.
                            $Trans_OUT = RequestTransactions($item['ID_Trans']['VALUE']);

                            if (empty($Trans_OUT['out']->InProcess)) $Deadline_check = $Trans_OUT['out']->Transactions[0]->Deadline;
                            else $Deadline_check = $Trans_OUT['out']->InProcess[0]->Deadline;
                            $DateTrans = Date_IN_1C($Deadline_check);
                            $stmp = MakeTimeStamp($DateTrans, "DD.MM.YYYY HH:MI:SS");
                            $stmp = AddToTimeStamp(array("HH" => 3), $stmp);
                            $Deadline_check = Date_OUT_1C(date("d.m.Y H:i:s", $stmp));
                            */
                    
					$stmp = MakeTimeStamp($item['PAY_DATE']['VALUE'], "DD.MM.YYYY HH:MI:SS");
					$stmp = AddToTimeStamp(array("MI" => 10), $stmp);
					$Deadline_check = Date_OUT_1C(date("d.m.Y H:i:s", $stmp));
					
					if ($item['QR_ID']['VALUE']) echo json_encode(array('qr' => $item['QR_ID']['VALUE'], 'Deadline' => $Deadline_check, 'isLast' => $status_isLast));
					else echo json_encode(array('id' => $item['ID_Trans']['VALUE'], 'Deadline' => $Deadline_check, 'isLast' => $status_isLast));
					
                    
                }


            }

            die;
        } elseif (strcasecmp($body_post['TypeRequest'],'Cancel') == 0) {
            // Проверка ExtID
            $res = CIBlockElement::GetList(Array(),Array("IBLOCK_ID"=>$iblock_id,"NAME"=>$body_post['ExtID']), false, Array(), Array());
            while($ob = $res->GetNextElement()) $item = array_merge($ob->GetFields(), $ob->GetProperties());

            if (isset($item)) { // Существует
                $Trans_OUT = RequestTransactions($item['ID_Trans']['VALUE']);
                if (($Trans_OUT['out']->Transactions) && ($Trans_OUT['out']->Transactions[0]->Result->Code == 0)) { // не возможно блокировать, оплата прошла
                    $deadline_cancel = Date_OUT_1C($item['DeadlineEnd']['VALUE']);
                    $status_result = false; $reason = 'Оплачено';
                } else { // блокировка транзакции
                    $deadline_cancel = $item['DeadlineEnd']['VALUE'];
                    if ($item['Block']['VALUE'] != 'Y') { // блокировка только в случае смены состояния
                        $deadline_cancel = date('d.m.Y H:i:s');
                        CIBlockElement::SetPropertyValueCode($item['ID'], 'DeadlineEnd', $deadline_cancel);
                        CIBlockElement::SetPropertyValueCode($item['ID'], 'Block', 'Y');
                    }

                    $deadline_cancel = Date_OUT_1C($deadline_cancel);
                    $status_result = true; $reason = '';
                }
				if ($item['QR_ID']['VALUE']) echo json_encode(array('qr' => $item['QR_ID']['VALUE'], 'Deadline' => $deadline_cancel, 'result' => $status_result, 'reason' => $reason));
                else echo json_encode(array('id' => $item['ID_Trans']['VALUE'], 'Deadline' => $deadline_cancel, 'result' => $status_result, 'reason' => $reason));
            }

            die;
        }
    } // Конец процедуры регистрации
// -------------------------------------------------------------------------------------------------------------------------------
    /*    $res_order = CIBlockElement::GetList(Array(),Array("IBLOCK_ID"=>$iblock_id,'PROPERTY_UUID'=>$UUID), false, Array(), Array());
        while($ob_order = $res_order->GetNextElement()) $item_order = array_merge($ob_order->GetFields(), $ob_order->GetProperties());
    */
    // установка инфоблока keep_order
    $res = CIBlock::GetList( Array(), Array('CODE'=>'keep_order'));
    while($ar_res = $res->Fetch()) $iblock_id = $ar_res['ID'];

    // Проверка ExtID
    $res = CIBlockElement::GetList(Array(),Array("IBLOCK_ID"=>$iblock_id,'PROPERTY_UUID'=>$UUID), false, Array(), Array());
    while($ob = $res->GetNextElement()) $item_order = array_merge($ob->GetFields(), $ob->GetProperties());

    // установка инфоблока 1с
    $res = CIBlock::GetList( Array(), Array('CODE'=>'Doc1C'));
    while($ar_res = $res->Fetch()) $iblock_id = $ar_res['ID'];

    // Проверка ExtID
    $res_1C = CIBlockElement::GetList(Array(),Array("IBLOCK_ID"=>$iblock_id,"NAME"=>$UUID), false, Array(), Array());
    while($ob_1c = $res_1C->GetNextElement()) $item_order_1C = array_merge($ob_1c->GetFields(), $ob_1c->GetProperties());
	
	// проверка оплаты
	if ($item_order['PAYMENT_STATUS']['VALUE'] == 'оплачено') LocalRedirect('/cart/ok_pay.php');
	if ($item_order_1C['PAYMENT_STATUS']['VALUE'] == 'оплачено') LocalRedirect('/cart/ok_pay.php');
	
	// Количесво попыток не более 5
	if (isset($item_order)) {
		if ($item_order['Count']['VALUE'] > 5) {
		LocalRedirect('/cart/error_out.php');
		} elseif ((strtotime(date('d.m.Y H:i:s')) > MakeTimeStamp($item_order['DeadlineEnd']['VALUE'], "DD.MM.YYYY HH:MI:SS")) && $item_order['DeadlineEnd']['VALUE']) {
		LocalRedirect('/cart/error_out.php');
		}
	}
	
	if (isset($item_order_1C)) {
		if ($item_order_1C['Count']['VALUE'] > 5) {
		LocalRedirect('/cart/error_out.php');
		} elseif ((strtotime(date('d.m.Y H:i:s')) > MakeTimeStamp($item_order_1C['DeadlineEnd']['VALUE'], "DD.MM.YYYY HH:MI:SS")) && $item_order_1C['DeadlineEnd']['VALUE']) {
		LocalRedirect('/cart/error_out.php');
		}
	}
	
	// проверка состояния платежа
	if ((strtotime(date('d.m.Y H:i:s')) < MakeTimeStamp($item_order['Trans_DeadlineEnd']['VALUE'], "DD.MM.YYYY HH:MI:SS")) && !$pay_method) { // оплата сформирована, позиционирование на нужныю оплату
		if ($item_order['TYPE_PAY']['VALUE'] == 'S') {
			$r_url = $_SERVER['REQUEST_URI'].'&pay=2';
			LocalRedirect($r_url);
		} else {
			$r_url = $_SERVER['REQUEST_URI'].'&pay=1';
			// Уточнение состояния платеж ВТБ (возможно неоплата) сброс дедлайна транзакции
			$Trans_OUT = RequestTransactions($item_order['UUID_PAY']['VALUE']);
			if ($Trans_OUT['out']->InProcess) LocalRedirect($r_url);
			else {
					CIBlockElement::SetPropertyValueCode($item_order['ID'], 'TYPE_PAY', '');	
					CIBlockElement::SetPropertyValueCode($item_order['ID'], 'Trans_DeadlineEnd', '');
				 }
		}
	}
		
	// проверка состояния платежа 1C
	if ((strtotime(date('d.m.Y H:i:s')) < MakeTimeStamp($item_order_1C['Trans_DeadlineEnd']['VALUE'], "DD.MM.YYYY HH:MI:SS")) && !$pay_method) { // оплата сформирована, позиционирование на нужныю оплату
		if ($item_order_1C['TYPE_PAY']['VALUE'] == 'S') {
			$r_url = $_SERVER['REQUEST_URI'].'&pay=2';
			LocalRedirect($r_url);
		} else {
			$r_url = $_SERVER['REQUEST_URI'].'&pay=1';
			// Уточнение состояния платеж ВТБ (возможно неоплата) сброс дедлайна транзакции
			$Trans_OUT = RequestTransactions($item_order_1C['ID_Trans']['VALUE']);
			if ($Trans_OUT['out']->InProcess) LocalRedirect($r_url);
			else {
					CIBlockElement::SetPropertyValueCode($item_order_1C['ID'], 'TYPE_PAY', '');
					CIBlockElement::SetPropertyValueCode($item_order_1C['ID'], 'Trans_DeadlineEnd', '');
				 }
		}
	}

    require($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");
    ?>

    <? // выбор оплаты ?>
    <? if(!$pay_method) { ?>
    <style>
        .content {
            /*background-color: #D9D8D6;*/
            background-color: #F2F2F2;
        }
    </style>
        <div class="content-wrapper pay-cont">
            <div class="pay-cont-wrap">
                <div class="order-info-wrap order-info-wrap-start">
                    <? if ($item_order) { ?>
                        <div class="pay-order-info">
                            <?//print_r($item_order)?>
                            <?

                            // Проверка на оплату в конце таймера последней попытки оплаты (возможность повтора оплаты в случае если оплата запоздала или конец таймера)
                            if ($item_order['TYPE_PAY']['VALUE'] == 'V') {
                                $Trans_OUT = RequestTransactions($item_order['UUID_PAY']['VALUE']);
                                if (($Trans_OUT['out']->Transactions) && ($Trans_OUT['out']->Transactions[0]->Result->Code == 0) && ($Trans_OUT['out']->Transactions[0]->State == 400) && ($Trans_OUT['out']->Transactions[0]->Substate == 411)){ // Оплата прошла
                                    LocalRedirect('/cart/ok_pay.php');
                                }
                            } elseif ($item_order['TYPE_PAY']['VALUE'] == 'S') {
                                $check_out = check_QR_ID($item_order['QR_ID']['VALUE']);
                                if ($check_out['out']->NSPK_JSON_RESP_DATA->data[0]->status == 'ACWP') { // Оплата прошла
                                    LocalRedirect('/cart/ok_pay.php');
                                }
                            }

                            CIBlockElement::SetPropertyValueCode($item_order['ID'], 'TYPE_PAY', '');
                            CIBlockElement::SetPropertyValueCode($item_order['ID'], 'Trans_DeadlineEnd', '');

                            $date = $item_order['DATE']['VALUE'];
                            $date = explode(" ",$date);
                            // $sum = $item_order['TOTAL_SALE']['VALUE'] != '' ? $item_order['TOTAL_SALE']['VALUE'] : $item_order['TOTAL']['VALUE'];
                            if ($item_order["TOTAL_SALE"]["VALUE"]) $price = $item_order["TOTAL_SALE"]["VALUE"];
                            else $price = $item_order["TOTAL"]["VALUE"];
                            if ($price > $item_order["PAY_TOTAL"]["VALUE"]) $price = $price - $item_order["PAY_TOTAL"]["VALUE"];

                            ?>
                            <div class="pay-order-info-number">Заказ № <?=$item_order['NAME']?></div>
                            <div class="pay-order-info-date">от <?=$date[0]?></div>
                            <div class="pay-order-info-sum">К оплате: <span class="pay-order-sum"><?=number_format($price,0,'',' ')?> руб.</span></div>
                        </div>
                    <? } elseif ($item_order_1C) { ?>
                        <div class="pay-order-info">
                            <?//print_r($item_order_1C)?>
                            <?

                            // Проверка на оплату в конце таймера последней попытки оплаты (возможность повтора оплаты в случае если оплата запоздала или конец таймера)
                            if ($item_order_1C['TYPE_PAY']['VALUE'] == 'V') {
                                $Trans_OUT = RequestTransactions($item_order_1C['ID_Trans']['VALUE']);
                                if (($Trans_OUT['out']->Transactions) && ($Trans_OUT['out']->Transactions[0]->Result->Code == 0) && ($Trans_OUT['out']->Transactions[0]->State == 400) && ($Trans_OUT['out']->Transactions[0]->Substate == 411)){ // Оплата прошла
                                    LocalRedirect('/cart/ok_pay.php');
                                }
                            } elseif ($item_order_1C['TYPE_PAY']['VALUE'] == 'S') {
                                $check_out = check_QR_ID($item_order_1C['QR_ID']['VALUE']);
                                if ($check_out['out']->NSPK_JSON_RESP_DATA->data[0]->status == 'ACWP') { // Оплата прошла
                                    LocalRedirect('/cart/ok_pay.php');
                                }
                            }

                            CIBlockElement::SetPropertyValueCode($item_order_1C['ID'], 'TYPE_PAY', '');
                            CIBlockElement::SetPropertyValueCode($item_order_1C['ID'], 'Trans_DeadlineEnd', '');

                            $date = $item_order_1C['OrderDate']['VALUE'];
                            $date = explode(" ",$date);
                            $price = $item_order_1C["Sum"]["VALUE"];
                            ?>
                            <div class="pay-order-info-number">Заказ № <?=$item_order_1C['OrderNumber']['VALUE']?></div>
                            <div class="pay-order-info-date">от <?=$date[0]?></div>
                            <div class="pay-order-info-sum">К оплате: <span class="pay-order-sum"><?=number_format($price,0,'',' ')?> руб.</span></div>
                        </div>
                    <? } ?>

                    <div class="pay-method">
                        <p>Выберите способ оплаты:</p>
                        <div class="pay-method-item radio-btn active" data-val="1" data-type="pay-start">
                            <img src="/img/online-store/mir-cropped.jpg" alt="МИР">
                            <img src="/img/online-store/visa-cropped.jpg" alt="VISA">
                            <img src="/img/online-store/mastercard-cropped.jpg" alt="MasterCard" class="pay-method-item-msc">
                        </div>
                        <div class="pay-method-item radio-btn" data-val="2"data-type="pay-start">
                            <img src="/img/online-store/sbp.svg" alt="СБП" style="width:180px;">
                        </div>
                    </div>

                    <button class="order-pay-btn" data-type="pay-start-btn">Перейти к оплате</button>
                    <div class="order-pay-time">на оплату дается 10 минут</div>
                </div>
                <div class="order-info-wrap-txt">
                    <div class="order-info-column">
                        <div class="order-info-column-img">
                            <img src="/img/online-store/mir-cropped.png" alt="МИР">
                            <img src="/img/online-store/visa-cropped.png" alt="VISA">
                            <img src="/img/online-store/mastercard-cropped.png" alt="MasterCard" class="order-info-column-img-msc">
                        </div>
                        <ul class="order-info-column-txt">
                            <li>подготовьте данные карты: <br>номер, срок действия, <br>владелец,&nbsp;cvv</li>
                            <li>проверьте наличие денег <br>на&nbsp;карте</li>

                        </ul>
                        <div class="order-info-column-add">
                            сервис предоставлен 2Can и&nbsp;ПСБ
                            <div class="order-info-column-add-img">
                                <img src="/img/online-store/2can_logo.png" alt="2Can">
                                <img src="/img/online-store/psb_logo.png" alt="ПСБ">
                            </div>
                        </div>
                    </div>
                    <div class="order-info-column">
                        <div class="order-info-column-img" style="margin-bottom: 11px;">
                            <img src="/img/online-store/sbp.svg" alt="СБП" style="width:180px;">
                        </div>
                        <ul class="order-info-column-txt">
                            <li>на&nbsp;мобильном устройстве <br>должно&nbsp;быть установлено <br>приложение банка или&nbsp;СБПей</li>
                            <li>проверьте наличие денег <br>на&nbsp;счете</li>
                            <li>выполните действия по&nbsp;оплате <br>в&nbsp;приложении банка или&nbsp;СБПей <br>после сканирования QR-кода</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $('[data-type="pay-start"]').on('click',function() {
                $('[data-type="pay-start"]').each(function() {
                    $(this).removeClass('active');
                })
                $(this).addClass('active');
            })

            $('[data-type="pay-start-btn"]').on('click',function() {
                $(this).attr('disabled','disabled');
                let pay = $('.pay-method').find('.active').attr('data-val'),
                    href = window.location.href;
                // раскомментить после отладки
                window.location.href = href + "&pay=" + pay;
                //window.location.href = href + "?pay=" + pay;
            })
        </script>


    <? } ?>

    <? // оплата картой ?>
    <? if($pay_method == 1) { ?>

        <div class="content-wrapper pay-cont">

        <?/*<div class="timer">
          <div class="e-new-cont">
            <div class="timer-wrap">
              <div class="timer-desc">Время для оплаты:</div>
              <span data-type="timer">00:00</span>
            </div>
          </div>
        </div> */?>

        <div class="pay-cont-wrap pay-cont-wrap-second-step">
            <div class="order-info-wrap">
                <? if (isset($item_order)) { ?>
                    <div class="pay-order-info">
                        <?//print_r($item_order)?>
                        <?
                        // установка типа платежа
                        if (!$item_order['TYPE_PAY']['VALUE']) CIBlockElement::SetPropertyValueCode($item_order['ID'], 'TYPE_PAY', 'V');

                        $date = $item_order['DATE']['VALUE'];
                        $date = explode(" ",$date);
                        // $sum = $item_order['TOTAL_SALE']['VALUE'] != '' ? $item_order['TOTAL_SALE']['VALUE'] : $item_order['TOTAL']['VALUE'];
                        if ($item_order["TOTAL_SALE"]["VALUE"]) $price = $item_order["TOTAL_SALE"]["VALUE"];
                        else $price = $item_order["TOTAL"]["VALUE"];
                        if ($price > $item_order["PAY_TOTAL"]["VALUE"]) $price = $price - $item_order["PAY_TOTAL"]["VALUE"];

                        ?>
                        <div class="pay-order-info-number">Заказ № <?=$item_order['NAME']?></div>
                        <div class="pay-order-info-date">от <?=$date[0]?></div>
                        <div class="pay-order-info-sum">К оплате: <span class="pay-order-sum"><?=number_format($price,0,'',' ')?> руб.</span></div>
                    </div>
                <? } elseif (isset($item_order_1C)) { ?>
                    <div class="pay-order-info">
                        <?//print_r($item_order)?>
                        <?
                        // установка типа платежа
                        if (!$item_order_1C['TYPE_PAY']['VALUE']) CIBlockElement::SetPropertyValueCode($item_order_1C['ID'], 'TYPE_PAY', 'V');

                        $date = $item_order_1C['OrderDate']['VALUE'];
                        $date = explode(" ",$date);
                        $price = $item_order_1C["Sum"]["VALUE"];
                        ?>
                        <div class="pay-order-info-number">Заказ № <?=$item_order_1C['OrderNumber']['VALUE']?></div>
                        <div class="pay-order-info-date">от <?=$date[0]?></div>
                        <div class="pay-order-info-sum">К оплате: <span class="pay-order-sum"><?=number_format($price,0,'',' ')?> руб.</span></div>
                    </div>
                <? } ?>
                <div class="pay-order-info-timer">
                    <?/*<div class="timer"><div class="timer-wrap"><div class="timer-desc">Время для оплаты:</div><span data-type="timer">00:13</span></div><div class="sms-auth">После ввода кода из&nbsp;SMS 3D‑авторизации обязательно дождитесь результата</div></div>*/?>
                </div>
                <div class="pay-order-bank-info">
                    <p>Оплата осуществляется через&nbsp;платежную систему 2Can и&nbsp;банк-партнер ПСБ</p>
                    <div class="pay-order-bank-info-img">
                        <img src="/img/online-store/2can_logo.png" alt="2Can">
                        <img src="/img/online-store/psb_logo.png" alt="ПСБ">
                    </div>
                </div>
            </div>

            <div class="pay-iframe-wrap" data-type="wait-wrap" data-id="<?=$UUID?>">
                <div class="wait-block">
                    <p>Ожидайте, идёт обработка данных</p>
                    <img src="/img/preloader.gif" alt="Ожидайте">
                </div>
            </div>
        </div>


        </div>

        <script>

            let idData = $('[data-type="wait-wrap"]').attr('data-id');
            $.get( "/ajax/vtb.php", { id: idData }, function(data) {
                data = $.parseJSON(data);
				if (data['success'] == 'true') {
					window.location.href = '/cart/ok_pay.php';	
				}
				if (data['url']) {
					window.location.href = '/cart/pay.php?id='+idData+data['url'];	
				}
                $('[data-type="wait-wrap"]').html(data);
                if($('[data-type="wait-wrap"]').find('[data-type="iframe-wrap"]').length > 0) {
                    let payInt = $('[data-type="wait-wrap"]').find('[data-type="iframe-wrap"]').attr('data-interval');
                    if(payInt !== undefined && payInt !== "") {
                        let timer = '';
                        timer += '<div class="timer">';
                        timer += '<div class="timer-wrap">';
                        timer += '<div class="timer-desc">Время для оплаты:</div>';
                        timer += '<span data-type="timer"></span>';
                        timer += '</div>';
                        timer += '<div class="sms-auth">';
                        timer += 'После ввода кода из&nbsp;SMS 3D&#8209авторизации обязательно дождитесь результата';
                        timer += '</div>';
                        timer += '</div>';
                        localStorage.setItem('hideTimer', '');
                        $('.pay-order-info-timer').html(timer);
                        $('.pay-order-info-timer').find('.timer').show();
                        showTime(payInt);

                        /*setTimeout(function() {
                                                  $.post( "/ajax/vtb.php", { id: idData }, function(data) {
                                                    if(data == 1) {
                                                      let newMess = '';
                                                      newMess += '<div class="e-new-header-offset time-expired">';
                                                      newMess += '<section class="order-resp-sec">';
                                                      newMess += '<div class="order-resp-result">Время платежа истекло</div>';
                                                      newMess += '<div class="order-resp-details">';
                                                      newMess += '<div class="order-resp-details-dealer">Хотите повторить платёж?</div>';
                                                      newMess += '<div class="order-resp-details-btns">';
                                                      newMess += '<a href="/cart/pay.php?id='+idData+'" class="order-resp-details-ok" data-type="ok-pay-btn">Да</a>';
                                                      newMess += '<a href="/" class="order-resp-details-no" data-type="no-pay-btn">Нет</a>';
                                                      newMess += '</div>';
                                                      newMess += '</div>';
                                                      newMess += '</section>';
                                                      newMess += '</div>';

                                                      $('[data-type="wait-wrap"]').html(newMess);
                                                      $('.content').find('.timer').remove();

                                                    }
                                                  })
                                                }, parseInt(payInt)*1000);*/
                    }
                }
                if($('[data-type="wait-wrap"]').find('[data-val="error-timer"]').length > 0) {
                    let payInt = $('[data-type="wait-wrap"]').find('[data-val="error-timer"]').attr('data-interval');
                    if(payInt !== undefined && payInt !== "") {
                        showTime(payInt);
                    }
                }
			checkPayVTB();
            } )
			
			function checkPayVTB() {
                    let timer = setInterval(function () {
					let idData = $('[data-type="wait-wrap"]').attr('data-id');
					$.get( "/cart/ok_pay.php?checkPayVTB=yes&id="+idData, function(data) {
					data = $.parseJSON(data);	
					if (data['pay'] == 'true') window.location.href = '/cart/ok_pay.php';	
					if (data['interval']) $('[data-type="wait-wrap"]').find('[data-type="iframe-wrap"]').attr('data-interval',data["interval"]);
					} )	
					
                  }, 5000)
            }
			
                function showTime(timeSec) {
					let step_timer = timeSec;
                  let timer = setInterval(function () {
                    if(timeSec > 0) {
                      let min = timeSec/60 ^ 0,
                        sec = timeSec - min * 60;
                      if(min < 10) min = '0'+min;
                      if(sec < 10) sec = '0'+sec;

                      if (timeSec < 0) {
                        clearInterval(timer);
                      } else {
                        let strTimer = `${min}:${sec}`;
                        $('.content').find('[data-type="timer"]').html(strTimer);
                      }
                      --timeSec;
					  if (step_timer !== $('[data-type="wait-wrap"]').find('[data-type="iframe-wrap"]').attr('data-interval')) {
							timeSec = $('[data-type="wait-wrap"]').find('[data-type="iframe-wrap"]').attr('data-interval');
							step_timer = timeSec;
					  }
                    } else {
                      let newMess = '';
                      newMess += '<div class="e-new-header-offset time-expired">';
                      newMess += '<section class="order-resp-sec">';
                      newMess += '<div class="order-resp-result">Время платежа истекло</div>';
                      newMess += '<div class="order-resp-details">';
                      newMess += '<div class="order-resp-details-dealer">Хотите повторить платёж?</div>';
                      newMess += '<div class="order-resp-details-btns">';
                      newMess += '<a href="/cart/pay.php?id='+idData+'" class="order-resp-details-ok" data-type="ok-pay-btn">Да</a>';
                      newMess += '<a href="/" class="order-resp-details-no" data-type="no-pay-btn">Нет</a>';
                      newMess += '</div>';
                      newMess += '</div>';
                      newMess += '</section>';
                      newMess += '</div>';
                      $('[data-type="wait-wrap"]').html(newMess);
                      $('.content').find('.timer').remove();
                      clearInterval(timer);
                    }
                  }, 1000)
                }
                function checkLink() {
                  let timer = setInterval(function () {
                    if(localStorage.getItem('payHref') != undefined && localStorage.getItem('payHref') != '') {
                      let url = localStorage.getItem('payHref');
                      localStorage.setItem('payHref', '');
                      window.location.href = url;
                    }
                  }, 1000)
                }
                function checkTimer() {
                  let timer = setInterval(function () {
                    if(localStorage.getItem('hideTimer') != undefined && localStorage.getItem('hideTimer') != '') {
                      $('.e-new-cont').find('.timer').hide();
                    }
                  }, 1000)
                }
                localStorage.setItem('payHref', '');
                localStorage.setItem('hideTimer', '');
                checkLink();
                checkTimer();
        </script>
    <? } ?>

    <? // оплата QR код ?>
    <? if($pay_method == 2) { ?>

        <div class="pay-cont">
            <div class="pay-cont-wrap pay-cont-wrap-second-step-qr">
                <div class="order-info-wrap">
                    <? if (isset($item_order)) { ?>
                        <div class="pay-order-info">
                            <?//print_r($item_order)?>
                            <?
                            // установка типа платежа
                            if (!$item_order['TYPE_PAY']['VALUE']) CIBlockElement::SetPropertyValueCode($item_order['ID'], 'TYPE_PAY', 'S');

                            $date = $item_order['DATE']['VALUE'];
                            $date = explode(" ",$date);
                            // $sum = $item_order['TOTAL_SALE']['VALUE'] != '' ? $item_order['TOTAL_SALE']['VALUE'] : $item_order['TOTAL']['VALUE'];
                            if ($item_order["TOTAL_SALE"]["VALUE"]) $price = $item_order["TOTAL_SALE"]["VALUE"];
                            else $price = $item_order["TOTAL"]["VALUE"];
                            if ($price > $item_order["PAY_TOTAL"]["VALUE"]) $price = $price - $item_order["PAY_TOTAL"]["VALUE"];

                            ?>
                            <div class="pay-order-info-number">Заказ № <?=$item_order['NAME']?></div>
                            <div class="pay-order-info-date">от <?=$date[0]?></div>
                            <div class="pay-order-info-sum">К оплате: <span class="pay-order-sum"><?=number_format($price,0,'',' ')?> руб.</span></div>
                        </div>
                    <? } elseif (isset($item_order_1C)) { ?>
                        <div class="pay-order-info">
                            <?//print_r($item_order)?>
                            <?
                            // установка типа платежа
                            if (!$item_order_1C['TYPE_PAY']['VALUE']) CIBlockElement::SetPropertyValueCode($item_order_1C['ID'], 'TYPE_PAY', 'S');

                            $date = $item_order_1C['OrderDate']['VALUE'];
                            $date = explode(" ",$date);
                            $price = $item_order_1C["Sum"]["VALUE"];
                            ?>
                            <div class="pay-order-info-number">Заказ № <?=$item_order_1C['OrderNumber']['VALUE']?></div>
                            <div class="pay-order-info-date">от <?=$date[0]?></div>
                            <div class="pay-order-info-sum">К оплате: <span class="pay-order-sum"><?=number_format($price,0,'',' ')?> руб.</span></div>
                        </div>
                    <? } ?>
                    <div class="pay-order-info-timer"></div>
                    <div class="pay-order-bank-info">
                        <p>Оплата осуществляется через<br>систему быстрых платежей</p>
                        <div class="pay-order-bank-info-img">
                            <img src="/img/online-store/sbp.svg" alt="СБП" style="width: 220px;">
                        </div>
                    </div>
                </div>

                <div class="pay-iframe-wrap" data-type="wait-wrap" data-id="<?=$UUID?>">
                    <div class="wait-block">
                        <p>Ожидайте, идёт обработка данных</p>
                        <img src="/img/preloader.gif" alt="Ожидайте">
                    </div>
                    <?/*<section class="order-resp-sec">
                        <div class="order-resp-result">Время платежа истекло</div>
                        <div class="order-resp-details">
                            <div class="order-resp-details-dealer">Хотите повторить платёж?</div>
                            <div class="order-resp-details-btns">
                            <a href="/cart/pay.php?id='+idData+'" class="order-resp-details-ok" data-type="ok-pay-btn">Да</a>
                            <a href="/" class="order-resp-details-no" data-type="no-pay-btn">Нет</a>
                            </div>
                        </div>
                    </section>*/?>
                </div>
            </div>
        </div>

        <script>
            let idData = $('[data-type="wait-wrap"]').attr('data-id');
            $.get( "/ajax/sbp.php", { id: idData }, function(data) {
                data = $.parseJSON(data);
				if (data['success'] == 'true') {
					window.location.href = '/cart/ok_pay.php';	
				}
				if (data['url']) {
					window.location.href = '/cart/pay.php?id='+idData+data['url'];	
				}
                $('[data-type="wait-wrap"]').html(data);
                if($('[data-type="wait-wrap"]').find('[data-type="iframe-wrap"]').length > 0) {
                    let payInt = $('[data-type="wait-wrap"]').find('[data-type="iframe-wrap"]').attr('data-interval');
                    if(payInt !== undefined && payInt !== "") {
                        let timer = '';
                        timer += '<div class="timer">';
                        timer += '<div class="timer-wrap">';
                        timer += '<div class="timer-desc">Время для оплаты:</div>';
                        timer += '<span data-type="timer"></span>';
                        timer += '</div>';
                        timer += '<div class="sms-auth">';
                        timer += 'отсканируйте QR-код <br>в мобильном приложении банка <br>или штатной камерой телефона';
                        timer += '</div>';
                        timer += '</div>';
                        localStorage.setItem('hideTimer', '');
                        $('.pay-order-info-timer').html(timer);
                        $('.pay-order-info-timer').find('.timer').show();
                        showTime(payInt);
                    }
                }
                if($('[data-type="wait-wrap"]').find('[data-val="error-timer"]').length > 0) {
                    let payInt = $('[data-type="wait-wrap"]').find('[data-val="error-timer"]').attr('data-interval');
                    if(payInt !== undefined && payInt !== "") {
                        showTime(payInt);
                    }
                }
			checkPaySBP();
            } )
			
			function checkPaySBP() {
                let timer = setInterval(function () {
                let idData = $('[data-type="wait-wrap"]').attr('data-id');
                $.get( "/cart/ok_pay.php?checkPaySBP=yes&id="+idData, function(data) {
                data = $.parseJSON(data);
                if (data['pay'] == 'true') window.location.href = '/cart/ok_pay.php';
                if (data['interval']) $('[data-type="wait-wrap"]').find('[data-type="iframe-wrap"]').attr('data-interval',data["interval"]);
                } )
                }, 5000)
            }
			
			function showTime(timeSec) {
					let step_timer = timeSec;
                  let timer = setInterval(function () {
                    if(timeSec > 0) {
                      let min = timeSec/60 ^ 0,
                        sec = timeSec - min * 60;
                      if(min < 10) min = '0'+min;
                      if(sec < 10) sec = '0'+sec;

                      if (timeSec < 0) {
                        clearInterval(timer);
                      } else {
                        let strTimer = `${min}:${sec}`;
                        $('.content').find('[data-type="timer"]').html(strTimer);
                      }
                      --timeSec;
					  if (step_timer !== $('[data-type="wait-wrap"]').find('[data-type="iframe-wrap"]').attr('data-interval')) {
							timeSec = $('[data-type="wait-wrap"]').find('[data-type="iframe-wrap"]').attr('data-interval');
							step_timer = timeSec;
					  }
                    } else {
                      let newMess = '';
                      newMess += '<section class="order-resp-sec">';
                      newMess += '<div class="order-resp-result">Время платежа истекло</div>';
                      newMess += '<div class="order-resp-details">';
                      newMess += '<div class="order-resp-details-dealer">Хотите повторить платёж?</div>';
                      newMess += '<div class="order-resp-details-btns">';
                      newMess += '<a href="/cart/pay.php?id='+idData+'" class="order-resp-details-ok" data-type="ok-pay-btn">Да</a>';
                      newMess += '<a href="/" class="order-resp-details-no" data-type="no-pay-btn">Нет</a>';
                      newMess += '</div>';
                      newMess += '</div>';
                      newMess += '</section>';
                      $('[data-type="wait-wrap"]').html(newMess);
                      $('.content').find('.timer').remove();
                      clearInterval(timer);
                    }
                  }, 1000)
                }
            function checkLink() {
              let timer = setInterval(function () {
                if(localStorage.getItem('payHref') != undefined && localStorage.getItem('payHref') != '') {
                  let url = localStorage.getItem('payHref');
                  localStorage.setItem('payHref', '');
                  window.location.href = url;
                }
              }, 1000)
            }
            function checkTimer() {
              let timer = setInterval(function () {
                if(localStorage.getItem('hideTimer') != undefined && localStorage.getItem('hideTimer') != '') {
                  $('.e-new-cont').find('.timer').hide();
                }
              }, 1000)
            }
            localStorage.setItem('payHref', '');
            localStorage.setItem('hideTimer', '');
            checkLink();
            checkTimer();
        </script>

    <? } ?>





    <?
    require($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");
    if(defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED===true)
    {
        require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog.php");
    }
