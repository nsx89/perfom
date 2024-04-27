<?
    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/include/phone.php");

    $http_host_temp = explode(":",$_SERVER['HTTP_HOST']);
    $_SERVER['HTTP_HOST'] = $http_host_temp[0];

    if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
        exit;
    }

    $cart = getObjectItems();
    $money = $cart['sum'];
    $cart = $cart['items'];
    $dealer_email = '';

    global $my_dealer;
    global $my_city;
    $my_city = $APPLICATION->get_cookie('my_city');
    $phone = NULL;

    $request = Array('data' => '', 'err' => 0);


    $loc = null;
    $arFilter = Array('IBLOCK_ID' => 7, 'ACTIVE' => 'Y', 'ID' => $my_city);
    $db_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter);
    $loc = $db_list->GetNextElement();
    if (!$loc) {
        $arFilter = Array('IBLOCK_ID' => 7, 'ACTIVE' => 'Y', 'CODE' => 'moskva');
        $db_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter);
        $loc = $db_list->GetNextElement();
    }
    $loc = array_merge($loc->GetFields(), $loc->GetProperties());

    $my_dealer = null;
    $my_dealer_id = null;
    $arFilter = Array('IBLOCK_ID' => 7, 'ACTIVE' => 'Y', 'ID' => $my_city, 'PROPERTY_online' => 'N');
    $db_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter);
    if ($db_list) {
        $el = $db_list->GetNextElement();
        if ($el) {
            $arFilter = Array('IBLOCK_ID' => 6, 'ACTIVE' => 'Y', 'PROPERTY_city' => $my_city);
            $db_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter);
            if ($db_list) {
                $el = $db_list->GetNextElement();
                if ($el) {
                    $el = array_merge($el->GetFields(), $el->GetProperties());
                    $my_dealer = $el;
                    $my_dealer_id = $el['ID'];
                }
            }
        }
    }
	/* временное отключение 
    // Проверка спама рекламы
    if ((__link_search($_REQUEST['name'])) || (__link_search($_REQUEST['phone'])) || (__link_search($_REQUEST['email'])) || (__link_search($_REQUEST['comment']))) {
        $request['data'] = '<p class="e-final-text">Дорогой покупатель, спасибо за Ваш заказ!<br> В ближайшее время с Вами свяжется наш представитель.</p>';
        $request['err'] = 1;
        echo json_encode($request);
        die();
    }// -<проверка спама
	*/
		// Проверка на пустоту
		if (!$_REQUEST['name']||!$_REQUEST['phone']||!$_REQUEST['email']) {
            $request['data'] = '<p class="e-final-text">Дорогой покупатель, спасибо за&nbsp;Ваш заказ!<br> В ближайшее время с&nbsp;Вами свяжется наш&nbsp;представитель.</p>';
            $request['err'] = 1;
            echo json_encode($request);
            die();
		}

        //проверка на наличие товаров
        $basket_arr = getObjectItems();

        if(empty($basket_arr['items'])) {
            $request['data'] = '<p class="e-final-text  e-final-title">Что-то пошло не&nbsp;так</p>';
            $request['data'] .='<p class="e-final-text">Ваш заказ не&nbsp;был сформирован. <br>Пожалуйста, добавьте товары в&nbsp;корзину <br>заново и&nbsp;повторите заказ.</p>';
            $request['data'] .= '<p class="e-final-text">Или обратитесь в&nbsp;нашу <br><a data-type="q-popup-open">службу поддержки</a>.</p>';
            $request['err'] = 1;
            setcookie("basket", null,0,'/','.'.$_SERVER['HTTP_HOST']);
            echo json_encode($request);
            die();
        }

		// mb5
		$all_price_mb5 = 0;
        //$prod_arr_mb5= array();
        foreach ($cart as $citem_mb5) {
            $citem_mb5['price'] = round($citem_mb5['price']);
            $all_price_mb5 += $citem_mb5['price'] * $citem_mb5['COUNT'];
            //$prod_arr_mb5[] = array('price' => $citem_mb5['price'], 'quantity' => $citem_mb5['COUNT']);
        }
		
		$mb5_crc = ''.$_REQUEST['del'].$_REQUEST['payment'].$_REQUEST['name'].$_REQUEST['lastname'].$_REQUEST['email'].$_REQUEST['phone'].$_REQUEST['city'].$_REQUEST['street'].
					$_REQUEST['house'].$_REQUEST['aprt'].$_REQUEST['km'].$_REQUEST['payment'].$_REQUEST['comment'].$all_price_mb5;
					
		$mb5_crc = md5($mb5_crc);		
		// Проверка дубликата.
		$arFilter_mb5 = Array("IBLOCK_CODE"=>"keep_order","ACTIVE"=>'Y',array("LOGIC" => "AND", array("=PROPERTY_mb5"=>$mb5_crc),array(">=PROPERTY_DATE'"=>date('Y-m-d',time() - (6 * 24 * 60 * 60)).'  00:00:00')));
		$res_mb5 = CIBlockElement::GetList(Array(),$arFilter_mb5);
		if ($item_mb5 = $res_mb5->GetNextElement()) {
			$item_mb5 = array_merge($item_mb5->GetFields(), $item_mb5->GetProperties());

            $request['data'] = '<p class="e-final-text  e-final-title">Дорогой покупатель, <br>спасибо за&nbsp;Ваш&nbsp;заказ!</p>';
            $request['data'] .='<p class="e-final-text">Ваш заказ был сформирован.<br>Вся необходимая информация по&nbsp;заказу отправлена на&nbsp;указанный Вами адрес электронной&nbsp;почты.</p>';
            $request['data'] .='<p class="e-final-text">Ожидайте звонка нашей службы доставки.</p>';
            $request['err'] = 1;
            echo json_encode($request);
		die();
		}
		//проверка полноты данных
		if($loc['ID'] == '3109') {
		    if($_REQUEST['del'] == 'del' && $_REQUEST['km'] == '' || $_REQUEST['del'] == 'del' && $_REQUEST['city'] == '' || $_REQUEST['del'] == 'del' && $_REQUEST['street'] == '' || $_REQUEST['del'] == 'del' && $_REQUEST['house'] == '' || $_REQUEST['payment'] == '' || $_REQUEST['name'] == ''  || $_REQUEST['lastname'] == '' || $_REQUEST['phone'] == '' || $_REQUEST['email'] == '' ) {
                $request['data'] = '<p class="e-final-text  e-final-title">Что-то пошло не&nbsp;так</p>';
                $request['data'] .='<p class="e-final-text">Ваш заказ не был сформирован. <br>Повторите, пожалуйста, ваш&nbsp;заказ.</p>';
                $request['data'] .= '<p class="e-final-text">Или обратитесь в&nbsp;нашу <br><a data-type="q-popup-open">службу поддержки</a>.</p>';
                $request['err'] = 1;
                echo json_encode($request);
                die();
            }
        } else {
            if($_REQUEST['del'] == 'del' && $_REQUEST['city'] == '' || $_REQUEST['del'] == 'del' && $_REQUEST['street'] == '' || $_REQUEST['del'] == 'del' && $_REQUEST['house'] == '' || $_REQUEST['name'] == ''  || $_REQUEST['lastname'] == '' || $_REQUEST['phone'] == '' || $_REQUEST['email'] == '' ) {
                $request['data'] = '<p class="e-final-text  e-final-title">Что-то пошло не&nbsp;так</p>';
                $request['data'] .='<p class="e-final-text">Ваш заказ не&nbsp; был сформирован. <br>Повторите, пожалуйста, ваш&nbsp;заказ.</p>';
                $request['data'] .= '<p class="e-final-text">Или обратитесь в&nbsp;нашу <br><a data-type="q-popup-open">службу поддержки</a>.</p>';
                $request['err'] = 1;
                echo json_encode($request);
                die();
            }
        }
		
		//обрабатываем юзера
        $old_user_id = '';
        if($_REQUEST['user'] == 'save') {
        require_once($_SERVER["DOCUMENT_ROOT"] . "/cart/save_user.php"); }

        $pr_list = _get_email_product_list();
        $number_r = __number_order_sale(true);
        $number_l = "Перфом Ваш заказ № ".$number_r." - ".$loc['NAME'];
        $number_tr = $number_r." - ".$loc['NAME'];
        //$fields = array('EMAIL'=>$_REQUEST['email'], 'PRODUCT_LIST'=>$pr_list, 'DEALER_INFO'=>'Информация по дилеру');
        $from = "";
        $from = '<div style="font-size: 140%;">Здравствуйте, '. $_REQUEST['name'].'</div><br>';
        $from .= 'Спасибо за Ваш заказ №<b>'.$number_r.'</b> на сайте <a href="https://perfom-decor.ru/" target="_blank">perfom-decor.ru</a><br><br>';
//$from .= 'Номер заказа: <b>'.$number_r.'</b><br><br>';
        $from .= '<table style="width: 500px;">';
        $from .= '<tr><td style="width: 30%; padding: 0 6px 1px 0; vertical-align: top; border-bottom: 1px dotted #ccc;">Имя:</td>
	  <td style="text-align: left; border-bottom: 1px dotted #ccc;">'.$_REQUEST['name'].'</td></tr>';
        $from .= '<tr><td style="width: 30%; padding: 0 6px 1px 0; vertical-align: top; border-bottom: 1px dotted #ccc;">Фамилия:</td>
	  <td style="text-align: left; border-bottom: 1px dotted #ccc;">'.$_REQUEST['lastname'].'</td></tr>';
        $from .= '<tr><td style="width: 30%; padding: 0 6px 1px 0; vertical-align: top; border-bottom: 1px dotted #ccc;">Телефон:</td>
	  <td style="text-align: left; border-bottom: 1px dotted #ccc;">'.$_REQUEST['phone'].'</td></tr>';
        $from .= '<tr><td style="width: 30%; padding: 0 6px 1px 0; vertical-align: top; border-bottom: 1px dotted #ccc;">E-mail:</td>
	  <td style="text-align: left; border-bottom: 1px dotted #ccc;">'.$_REQUEST['email'].'</td></tr>';
        if($_REQUEST['del']) {
            $del = $_REQUEST['del'] == 'del' ? "доставка" : "самовывоз";
            $from .= '<tr><td style="width: 30%; padding: 0 6px 1px 0; vertical-align: top; border-bottom: 1px dotted #ccc;">Способ получения заказа:</td>
	  <td style="text-align: left; border-bottom: 1px dotted #ccc;">'.$del.'</td></tr>';
            if($_REQUEST['del'] == 'del') {
                $from .= '<tr><td style="width: 30%; padding: 0 6px 1px 0; vertical-align: top; border-bottom: 1px dotted #ccc;">Город:</td>
              <td style="text-align: left; border-bottom: 1px dotted #ccc;">'.$_REQUEST['city'].'</td></tr>';
                $from .= '<tr><td style="width: 30%; padding: 0 6px 1px 0; vertical-align: top; border-bottom: 1px dotted #ccc;">Улица:</td>
              <td style="text-align: left; border-bottom: 1px dotted #ccc;">'.$_REQUEST['street'].'</td></tr>';
                $from .= '<tr><td style="width: 30%; padding: 0 6px 1px 0; vertical-align: top; border-bottom: 1px dotted #ccc;">Дом:</td>
              <td style="text-align: left; border-bottom: 1px dotted #ccc;">'.$_REQUEST['house'].'</td></tr>';
                if($_REQUEST['aprt'] != '') $from .= '<tr><td style="width: 30%; padding: 0 6px 1px 0; vertical-align: top; border-bottom: 1px dotted #ccc;">Квартира:</td>
              <td style="text-align: left; border-bottom: 1px dotted #ccc;">'.$_REQUEST['aprt'].'</td></tr>';
                if($_REQUEST['km'] != '') $from .= '<tr><td style="width: 30%; padding: 0 6px 1px 0; vertical-align: top; border-bottom: 1px dotted #ccc;">км за МКАД:</td>
              <td style="text-align: left; border-bottom: 1px dotted #ccc;">'.$_REQUEST['km'].'</td></tr>';
            }
        }
        if($_REQUEST['payment']) {
          if($_REQUEST['payment'] == 'cash') $payment = "при получении";
          if($_REQUEST['payment'] == 'online') $payment = "онлайн";
          if($_REQUEST['payment'] == 'prepayment') $payment = "предоплата";
            $from .= '<tr><td style="width: 30%; padding: 0 6px 1px 0; vertical-align: top; border-bottom: 1px dotted #ccc;">Оплата заказа:</td>
	  <td style="text-align: left; border-bottom: 1px dotted #ccc;">'.$payment;
            if($_REQUEST['payment'] == 'cash' && $_REQUEST['receiving'] && $_REQUEST['receiving']!= '') {
                if($_REQUEST['receiving'] == 'receiving-cash') $receiving = "наличными";
                if($_REQUEST['receiving'] == 'receiving-card') $receiving = "картой";
                $from .= ' ('.$receiving.')';
            }
            $from .= '</td></tr>';
        }
        if ($_REQUEST['comment'] != '') $from .= '<tr><td style="width: 30%; padding: 0 6px 1px 0; vertical-align: top; border-bottom: 1px dotted #ccc;">Комментарии:</td>
	  		<td style="text-align: left; border-bottom: 1px dotted #ccc;">'.$_REQUEST['comment'].'</td></tr>';
        $from .= '</table>';

        if($_REQUEST['mounting'] == 'Y') {
            $from .= '<br><p style="color:#849795;padding:0;margin:0;"><b>Запрошен расчет монтажа</b></p>';
        }
        $from_plus = '';
        if(isset($_COOKIE['calc'])) $from_plus .= '<br><p style="color:#849795;padding:0;margin:0;"><b>Клиент интересовался калькулятором.</b></p>';
// авто определение региона на первом этапе
        $ip_city = $APPLICATION->get_cookie('ip_city');
        $arFilter = Array('IBLOCK_ID' => 7, 'ACTIVE' => 'Y', 'ID' => $ip_city);
        $db_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter);
        $ip_loc = $db_list->GetNextElement();
        if ($ip_loc) $ip_loc = array_merge($ip_loc->GetFields(), $ip_loc->GetProperties());
        $from_plus .= '<br>'.'ip: <a href="http://whois.domaintools.com/'.GetIP().'">'.GetIP().'</a><br>';
        if ($ip_loc) $from_plus .= '<br>Авто-определение региона: '.$ip_loc['NAME'].'<br>';
        $from_plus .= 'Выбранный регион: '.$loc['NAME'].'<br>';
        $from_plus .= '<br>'.$_SERVER['HTTP_USER_AGENT'].'<br>';
        $from_plus .= '<br>Версия сайта: десктоп<br>';

        $request_city = mb_strtolower(trim($_REQUEST['city']));
// если Москва или МО
if (($loc['CODE'] == 'moskva') || ($loc['CODE'] == 'moskovskaya-oblast') || $request_city == 'москва' || stripos($request_city,'москва') !== false) {

    if(stripos($request_city,'москва') !== false && $loc['CODE'] != 'moskva' && $loc['CODE'] != 'moskovskaya-oblast') {
        $from_plus .= '<br><b>Произошла переадресация заказа на московский регион, т.к. в адресе доставки указан город Москва.</b><br>';
        $new_loc = '3109';
    }
    $email_manager = array( '',// 0 - пустой
        //!!!! если меняется массив, нужно проверить строки с комментом !кашир
        //'store@decor-evroplast.ru',
        'kdvor@decor-evroplast.ru',
        'nahim@decor-evroplast.ru',
        'salonn@decor-evroplast.ru',
        'shop@decor-evroplast.ru',
		// временно - 1 заказ shop (ниже таже история)
		'kdvor@decor-evroplast.ru',
        'nahim@decor-evroplast.ru',
        'salonn@decor-evroplast.ru',
        );
    $email_info = array( '',// 0 - пустой
        //'+7 (495) 640-88-51',
        '+7 (495) 640 88 51',
        '+7 (495) 116 55 40',
        '+7 (495) 116 55 37',
        '+7 (495) 116 55 41',
		
		'+7 (495) 640 88 51',
        '+7 (495) 116 55 40',
        '+7 (495) 116 55 37',
        );
    $point_info = array( '',// 0 - пустой
        //'+7 (495) 640-88-51',
        'ТК "Каширский двор" г. Москва, Каширское ш., д.19, корп.1, 2-й этаж, фирменный салон "Европласт" павильон 2-С90',
        'ТВК "ЭКСПОСТРОЙ на Нахимовском" г.Москва, Нахимовский проспект, д.24, павильон №3, фирменный стенд "Европласт" № 195',
        'ТВК "ЭКСПОСТРОЙ на Нахимовском" г.Москва, Нахимовский проспект, д.24, павильон №3, фирменный стенд "Европласт" № 49/2',
        'ТВК "ЭКСПОСТРОЙ на Нахимовском" г.Москва, Нахимовский проспект, д.24, павильон №2, фирменный стенд "Европласт" № 158',
		
		'ТК "Каширский двор" г. Москва, Каширское ш., д.19, корп.1, 2-й этаж, фирменный салон "Европласт" павильон 2-С90',
        'ТВК "ЭКСПОСТРОЙ на Нахимовском" г.Москва, Нахимовский проспект, д.24, павильон №3, фирменный стенд "Европласт" № 195',
        'ТВК "ЭКСПОСТРОЙ на Нахимовском" г.Москва, Нахимовский проспект, д.24, павильон №3, фирменный стенд "Европласт" № 49/2',
        );

    $dealer_counter_res = CIBlockElement::GetList(Array(), Array('IBLOCK_CODE' => 'order_counters', 'CODE' => 'dealer_rotation'));
    while($dealer_counter_ob = $dealer_counter_res->GetNextElement())
    {
        $dealer_counter = array_merge($dealer_counter_ob->GetFields(), $dealer_counter_ob->GetProperties());
    }
  if($_REQUEST['delpoint'] != '' && $_REQUEST['del'] == 'pickup') {
    $email_number = 1;
    if($_REQUEST['delpoint'] == 'nahim') {
        //print_r($dealer_counter);
        if($dealer_counter['COUNTER_VALUE']['VALUE'] == count($email_manager) - 1) {
            $email_number = 2;
        } else {
            $email_number = $dealer_counter['COUNTER_VALUE']['VALUE'] + 1;
        }
        if($email_number == 1 || $email_number == 5) $email_number++; //!кашир убираем каширский двор из нахимовской ротации
        CIBlockElement::SetPropertyValueCode($dealer_counter['ID'], 'COUNTER_VALUE', $email_number);

        //$email_number = rand(2,count($email_manager)-1);
    }
  } else {
      // Если ранее был заказ, фиксируем на конкретный емайл что бы небыло ротации одного клиента по разным точкам
		$arFilter_oldOrder = Array("IBLOCK_CODE"=>"keep_order","ACTIVE"=>'Y',array("LOGIC" => "AND", array("=PROPERTY_USER_MAIL"=>$_REQUEST['email']),array(">=PROPERTY_DATE'"=>date('Y-m-d',time() - (182 * 24 * 60 * 60)).'  00:00:00')));
		$res_oldOrder = CIBlockElement::GetList(Array("DATE_CREATE" => "desc,nulls"),$arFilter_oldOrder);
            if ($item_oldOrder = $res_oldOrder->GetNextElement()) {
                $item_oldOrder = array_merge($item_oldOrder->GetFields(), $item_oldOrder->GetProperties());
                $email_oldOrder = $item_oldOrder['MAIL_DEALER']['VALUE'];
			
			if ($email_number = array_search($email_oldOrder,$email_manager)) $f_oldOrder = false; // временно закрыто (нормальное состояние $f_oldOrder = true;)
		}
		
	  if (!$f_oldOrder) {
		$arFilter = Array('IBLOCK_ID' => 7, 'ACTIVE' => 'Y', 'CODE' => 'moskva');
		$db_list = CIBlockElement::GetList(Array("SORT" => "ASC"), $arFilter);
		$moskva = $db_list->GetNextElement();
		$moskva = array_merge($moskva->GetFields(), $moskva->GetProperties());
		if($moskva['email_number']['VALUE'] == count($email_manager) - 1) {
            $email_number = 1;
        } else {
            $email_number = $moskva['email_number']['VALUE'] + 1;
        }
		//если идет заказ на нахим, нужно менять ротацию в самовывозе тоже
        if($email_number != 1 && $email_number != 5) { //!кашир
            CIBlockElement::SetPropertyValueCode($dealer_counter['ID'], 'COUNTER_VALUE', $email_number);
        }
	  }
  }

    if ($email_number > 0) {

      //если номер больше длины массива
      if($email_number > count($email_manager) - 1) $email_number = count($email_manager) - 1;

        $dealer = '<br>Обслуживание заказа производит <b>фирменный магазин Европласт</b>.<br>Справочную информацию Вы можете получить по телефону: <b>'.$email_info[$email_number].'</b><br>E-mail: <b>'. $email_manager[$email_number].'</b><br>';
        $dealer_contacts = '<b>E-mail: </b>'.$email_manager[$email_number].'<br><b>Телефон: </b>'.$email_info[$email_number];

      if($_REQUEST['delpoint'] != '' && $_REQUEST['del'] == 'pickup') {
          $dealer .= '<p style="margin-top:20px;max-width:720px;"><b>Вы выбрали самовывоз товара.</b><br>';
          $dealer .= 'Адрес  магазина: '.$point_info[$email_number].'<br>';
          /*$from .= 'График работы: ежедневно, 10:00-22:00<br>';*/
          $dealer .= 'Телефон: '.$email_info[$email_number].'<br>';
          $dealer .= 'E-mail: '.$email_manager[$email_number].'</p>';
      }

        $dealer .= '<p style="margin-top:20px;max-width:720px;margin-bottom: 5px;">Все детали заказа Вы можете посмотреть по ссылке:</p>';
        $dealer .= '<a href="https://'.$_SERVER['HTTP_HOST'].'/personal/show_order?number='.$number_r.'" style="color:#849795;text-decoration:underline;" target="_blank">Посмотреть заказ</a><br>';

      /*if($_REQUEST['del'] == 'del') {
          $dealer .= '<p style="margin-top:20px;"><b>Вы выбрали доставку товара.</b><br>';
          $dealer .= 'Доставка осуществляется с 9.00 до 18.00. Водитель звонит за час до доставки.<br>';
          $dealer .= 'Разгрузку товара Покупатель осуществляет собственными силами.</p>';
      }*/

        /*
                $dealer = '<br>Обслуживание заказа производит фирменный магазин Европласт.<br>Справочную информацию Вы можете получить по телефону: <b>+7 (495) 640-88-51</b><br>E-mail: store@decor-evroplast.ru<br>';
        */

        require_once($_SERVER["DOCUMENT_ROOT"] . "/cart/save_order.php");

        $fields = array('EMAIL'=>$_REQUEST['email'], 'EMAIL_D'=>$email_manager[$email_number], 'PRODUCT_LIST'=>$pr_list, 'NUM_Z' => $number_l, 'DEALER_INFO'=>$dealer, 'CLIENT'=>$from, 'DEALER_CONTACTS'=>$dealer_contacts,'RESPOND'=>$email_manager[$email_number]);
        CEvent::SendImmediate("EUROPLAST_ORDER_SALE_CLIENT", s1, $fields, "N");
        $from .= $from_plus;

        $fields = array('EMAIL'=>$email_manager[$email_number], 'PRODUCT_LIST'=>$pr_list, 'NUM_Z' => $number_l, 'DEALER_INFO'=>$dealer, 'CLIENT'=>$from, 'DEALER_CONTACTS'=>$dealer_contacts,'RESPOND'=>$_REQUEST['email']);
        CEvent::SendImmediate("EUROPLAST_ORDER_SALE", s1, $fields, "N");

        $fields = array('EMAIL'=>'a.chilichihin@decor-evroplast.ru', 'PRODUCT_LIST'=>$pr_list, 'NUM_Z' => $number_l, 'DEALER_INFO'=>$dealer, 'CLIENT'=>$from, 'DEALER_CONTACTS'=>$dealer_contacts);
        CEvent::SendImmediate("EUROPLAST_ORDER_SALE", s1, $fields, "N");

        $fields = array('EMAIL'=>'d.portu.by@yandex.ru', 'PRODUCT_LIST'=>$pr_list, 'NUM_Z' => $number_l, 'DEALER_INFO'=>$dealer, 'CLIENT'=>$from, 'DEALER_CONTACTS'=>$dealer_contacts,'PAYMENT_MESS'=>$payment_mess);
        CEvent::SendImmediate("EUROPLAST_ORDER_SALE", s1, $fields, "N");

        //$fields = array('EMAIL'=>'nadida.hi@yandex.ru', 'PRODUCT_LIST'=>$pr_list, 'NUM_Z' => $number_l, 'DEALER_INFO'=>$dealer, 'CLIENT'=>$from, 'DEALER_CONTACTS'=>$dealer_contacts);
        //CEvent::SendImmediate("EUROPLAST_ORDER_SALE", s1, $fields, "N");

        $dealer_email = $email_manager[$email_number];


        CIBlockElement::SetPropertyValueCode(3109, 'email_number', $email_number);


    } else {

        require_once($_SERVER["DOCUMENT_ROOT"] . "/cart/save_order.php");

        $fields = array('EMAIL'=>$_REQUEST['email'], 'PRODUCT_LIST'=>$pr_list, 'NUM_Z' => $number_l, 'DEALER_INFO'=>'', 'CLIENT'=>$from, 'DEALER_CONTACTS'=>$dealer_contacts,'RESPOND'=>'store@decor-evroplast.ru');
        CEvent::SendImmediate("EUROPLAST_ORDER_SALE", s1, $fields, "N");
        $from .= $from_plus;
        $fields = array('EMAIL'=>'store@decor-evroplast.ru', 'PRODUCT_LIST'=>$pr_list, 'NUM_Z' => $number_l, 'DEALER_INFO'=>$dealer, 'CLIENT'=>$from, 'DEALER_CONTACTS'=>$dealer_contacts,'RESPOND'=>$_REQUEST['email']);
        CEvent::SendImmediate("EUROPLAST_ORDER_SALE", s1, $fields, "N");
        $fields = array('EMAIL'=>'a.chilichihin@decor-evroplast.ru', 'PRODUCT_LIST'=>$pr_list, 'NUM_Z' => $number_l, 'DEALER_INFO'=>'', 'CLIENT'=>$from, 'DEALER_CONTACTS'=>$dealer_contacts);
        CEvent::SendImmediate("EUROPLAST_ORDER_SALE", s1, $fields, "N");
        $fields = array('EMAIL'=>'d.portu.by@yandex.ru', 'PRODUCT_LIST'=>$pr_list, 'NUM_Z' => $number_l, 'DEALER_INFO'=>'', 'CLIENT'=>$from, 'DEALER_CONTACTS'=>$dealer_contacts);
        CEvent::SendImmediate("EUROPLAST_ORDER_SALE", s1, $fields, "N");
        //$fields = array('EMAIL'=>'nadida.hi@yandex.ru', 'PRODUCT_LIST'=>$pr_list, 'NUM_Z' => $number_l, 'DEALER_INFO'=>'', 'CLIENT'=>$from, 'DEALER_CONTACTS'=>$dealer_contacts);
        //CEvent::SendImmediate("EUROPLAST_ORDER_SALE", s1, $fields, "N");

        $dealer_email = 'store@decor-evroplast.ru';
    }
    if($_REQUEST['mounting'] == 'Y' || isset($_COOKIE['calc'])) {
        $subj = "Запрос на расчет монтажа";
        if($_REQUEST['mounting'] != 'Y') $subj = "Клиент интересовался калькулятором.";
        $fields = array('EMAIL'=>'G.Paskar@decor-evroplast.ru', 'PRODUCT_LIST'=>$pr_list, 'NUM_Z' => $subj, 'DEALER_INFO'=>$dealer, 'CLIENT'=>$from, 'DEALER_CONTACTS'=>$dealer_contacts);
        CEvent::SendImmediate("EUROPLAST_ORDER_SALE", s1, $fields, "N");
        $fields = array('EMAIL'=>'a.chilichihin@decor-evroplast.ru', 'PRODUCT_LIST'=>$pr_list, 'NUM_Z' => $subj, 'DEALER_INFO'=>$dealer, 'CLIENT'=>$from, 'DEALER_CONTACTS'=>$dealer_contacts);
        CEvent::SendImmediate("EUROPLAST_ORDER_SALE", s1, $fields, "N");
        $fields = array('EMAIL'=>'A.Visotskaya@decor-evroplast.ru', 'PRODUCT_LIST'=>$pr_list, 'NUM_Z' => $subj, 'DEALER_INFO'=>$dealer, 'CLIENT'=>$from, 'DEALER_CONTACTS'=>$dealer_contacts);
        CEvent::SendImmediate("EUROPLAST_ORDER_SALE", s1, $fields, "N");
    }
} else { // Другие регионы
    //дилер
    global $my_dealer;
    $dealer_contacts = "";
    $email = "";
    $my_dealer = null;
    $my_dealer_id = null;
    $arFilter = Array('IBLOCK_ID' => 7, 'ACTIVE' => 'Y', 'ID' => $my_city, 'PROPERTY_online' => 'N');
    $db_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter);
    if ($db_list) {
        $el = $db_list->GetNextElement();
        if ($el) {
            if($loc['reg_dealers']['VALUE']) { //Краснодарский вопрос
                $arFilter = Array('IBLOCK_ID' => 6, 'ACTIVE' => 'Y', 'ID' => $loc['reg_dealers']['VALUE']);
            } elseif($loc['dealers_list']['VALUE'] != '') { //помечен контакт для заказа
                $arFilter = Array('IBLOCK_ID' => 6, 'ACTIVE' => 'Y', 'ID' => $loc['dealers_list']['VALUE']);
            } else {//первый по сортировке
            $arFilter = Array('IBLOCK_ID' => 6, 'ACTIVE' => 'Y', 'PROPERTY_city' => $my_city);
			}
			$db_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter);
            if ($db_list) {
                $el = $db_list->GetNextElement();
                if ($el) {
                    $el = array_merge($el->GetFields(), $el->GetProperties());
                    $my_dealer = $el;
                    $my_dealer_id = $el['ID'];
                }
            }
        }
    }
// порядок текущего дилера при наличии ротации
    $email_number = $loc['email_number']['VALUE'];
    if ($loc['dealers_list']['VALUE']) {
        if ($email_number == 0) { // старт первого дилера при начале
            $email_number = 1;
        }
// Операция по отправке
// Дилер
        $cart_dealer = $loc['dealers_list']['VALUE'][$email_number-1];
        $arFilter = Array('IBLOCK_ID' => 6, 'ACTIVE' => 'Y', 'ID' => $cart_dealer);
        $db_list = CIBlockElement::GetList(Array("SORT" => "ASC"), $arFilter);
        $cart_dealer = $db_list->GetNextElement();
        $dealer_contacts = "";
        if ($cart_dealer) {
            $cart_dealer = array_merge($cart_dealer->GetFields(), $cart_dealer->GetProperties());
            $email = $cart_dealer['orderemail']['VALUE']?$cart_dealer['orderemail']['VALUE']:$cart_dealer['email']['VALUE'];
            $dealer_name = $cart_dealer['organization']['VALUE'];
            if(stristr($dealer_name, 'Питерра')) $dealer_name = '"Питерра"';
            $dealer = '<br>Обслуживание заказа в вашем регионе производит наш официальный дилер <b>'. $dealer_name. '</b>.<br>';
            if ($cart_dealer['order_phone']['VALUE'] || $cart_dealer['phones']['VALUE']) {
                $phones = $cart_dealer['order_phone']['VALUE'] != '' ? $cart_dealer['order_phone']['VALUE'] : $cart_dealer['phones']['VALUE'];
                $phones = explode(';', $phones);
                $phone = phone(trim($phones[0]));
                $dealer .= 'Справочную информацию Вы можете получить по телефону: <b>'. $phone .'</b><br>';
                $dealer_contacts .= '<b>Телефон: </b>'. $phone;
            }
            if ($email) {
                $dealer .= 'E-mail: <b>'. $email . '</b><br>';
                $dealer_contacts .= '<br><b>E-mail: </b>'. $email;
                $fields = array('EMAIL'=>$email, 'PRODUCT_LIST'=>$pr_list, 'NUM_Z' => $number_l, 'DEALER_INFO'=> $dealer, 'CLIENT'=>$from, 'DEALER_CONTACTS'=>$dealer_contacts,'RESPOND'=>$_REQUEST['email']);
                CEvent::SendImmediate("EUROPLAST_ORDER_SALE", s1, $fields, "N");
            }
            $dealer_email = $email;
        }
// Ротация
        if (($email_number += 1) > count($loc['dealers_list']['VALUE'])) $email_number = 1;
        CIBlockElement::SetPropertyValueCode($loc['ID'], 'email_number', $email_number);
    } else { // 1 дилер в регионе по приоритету сортировки
        if ($email_number != 0) {
            $email_number = 0;
            CIBlockElement::SetPropertyValueCode($loc['ID'], 'email_number', $email_number);
        }

        $dealer = '';
        $cart_dealer = $my_dealer['ID'];
        $APPLICATION->set_cookie('cart_dealer', $cart_dealer, false, '/', '.'.$_SERVER['HTTP_HOST']);
        $arFilter = Array('IBLOCK_ID' => 6, 'ACTIVE' => 'Y', 'ID' => $cart_dealer);
        $db_list = CIBlockElement::GetList(Array("SORT" => "ASC"), $arFilter);
        $cart_dealer = $db_list->GetNextElement();
        if ($cart_dealer) {
            $cart_dealer = array_merge($cart_dealer->GetFields(), $cart_dealer->GetProperties());
            $email = $cart_dealer['orderemail']['VALUE']?$cart_dealer['orderemail']['VALUE']:$cart_dealer['email']['VALUE'];
            $dealer_name = $cart_dealer['organization']['VALUE'];
            if(stristr($dealer_name, 'Питерра')) $dealer_name = '"Питерра"';
            $dealer = '<br>Обслуживание заказа в вашем регионе производит наш официальный дилер <b>'. $dealer_name. '</b>.<br>';
            if ($cart_dealer['order_phone']['VALUE'] || $cart_dealer['phones']['VALUE']) {
                $phones = $cart_dealer['order_phone']['VALUE'] != '' ? $cart_dealer['order_phone']['VALUE'] : $cart_dealer['phones']['VALUE'];
                $phones = explode(';', $phones);
                $phone = phone(trim($phones[0]));
                $dealer .= 'Справочную информацию Вы можете получить по телефону: <b>'. $phone .'</b><br>';
                $dealer_contacts .= '<b>Телефон: </b>'. $phone;
            }
            if ($email) {
                $dealer .= 'E-mail: <b>'. $email . '</b><br>';
                $dealer_contacts .= '<br><b>E-mail: </b>'.$email;
                $fields = array('EMAIL'=>$email, 'PRODUCT_LIST'=>$pr_list, 'NUM_Z' => $number_l, 'DEALER_INFO'=> $dealer, 'CLIENT'=>$from, 'DEALER_CONTACTS'=>$dealer_contacts,'RESPOND'=>$_REQUEST['email']);
                CEvent::SendImmediate("EUROPLAST_ORDER_SALE", s1, $fields, "N");
            }
        }
        $dealer_email = $email;
    }

    require_once($_SERVER["DOCUMENT_ROOT"] . "/cart/save_order.php");

    $fields = array('EMAIL'=>$_REQUEST['email'], 'EMAIL_D'=>$email, 'PRODUCT_LIST'=>$pr_list, 'NUM_Z' => $number_l, 'DEALER_INFO'=>$dealer, 'CLIENT'=>$from, 'DEALER_CONTACTS'=>$dealer_contacts,'RESPOND'=>$email);
    CEvent::SendImmediate("EUROPLAST_ORDER_SALE", s1, $fields, "N");
    $from .= $from_plus;

    $fields = array('EMAIL'=>'d.portu.by@yandex.ru', 'PRODUCT_LIST'=>$pr_list, 'NUM_Z' => $number_l, 'DEALER_INFO'=>$dealer, 'CLIENT'=>$from, 'DEALER_CONTACTS'=>$dealer_contacts);
    CEvent::SendImmediate("EUROPLAST_ORDER_SALE", s1, $fields, "N");
    //$fields = array('EMAIL'=>'nadida.hi@yandex.ru', 'PRODUCT_LIST'=>$pr_list, 'NUM_Z' => $number_l, 'DEALER_INFO'=>$dealer, 'CLIENT'=>$from, 'DEALER_CONTACTS'=>$dealer_contacts);
    //CEvent::SendImmediate("EUROPLAST_ORDER_SALE", s1, $fields, "N");
	/*
	// дублирование заказов с комментами
	if ($_REQUEST['comment']) {
	
		$fields = array('EMAIL'=>'a.chilichihin@decor-evroplast.ru', 'PRODUCT_LIST'=>$pr_list, 'NUM_Z' => $number_l, 'DEALER_INFO'=>'', 'CLIENT'=>$from, 'DEALER_CONTACTS'=>$dealer_contacts);
		CEvent::SendImmediate("EUROPLAST_ORDER_SALE", s1, $fields, "N");	
		
		$fields = array('EMAIL'=>'o.gmirya@decor-evroplast.ru', 'PRODUCT_LIST'=>$pr_list, 'NUM_Z' => $number_l, 'DEALER_INFO'=>'', 'CLIENT'=>$from, 'DEALER_CONTACTS'=>$dealer_contacts);
		CEvent::SendImmediate("EUROPLAST_ORDER_SALE", s1, $fields, "N");	
	}
	*/
} // Дилер
        if($_COOKIE['mount']) {
            $mess = '<p style="font: 16px Arial, Helvetica, sans-serif;color: #4e4e4e;margin-bottom:0;">На сайте <a href="https://perfom-decor.ru/" target="_blank">evroplast.ru</a> пользователь воспользовался калькулятором расчета стоимости монтажа <br>во время оформления заказа № <b>'.$number_r.'</b>.</p><br>';
            $mess .= '<table>';
            $mess .= '<tr>';
            $mess .= '<td style="font: 16px Arial, Helvetica, sans-serif;color: #4e4e4e;padding-right:15px;">Имя:</td>';
            $mess .= '<td style="font: 16px Arial, Helvetica, sans-serif;color: #4e4e4e;"><b>'.$_REQUEST['name'].'</b></td>';
            $mess .= '</tr>';
            $mess .= '<tr>';
            $mess .= '<td style="font: 16px Arial, Helvetica, sans-serif;color: #4e4e4e;padding-right:15px;">Фамилия:</td>';
            $mess .= '<td style="font: 16px Arial, Helvetica, sans-serif;color: #4e4e4e;"><b>'.$_REQUEST['lastname'].'</b></td>';
            $mess .= '</tr>';
            $mess .= '<tr>';
            $mess .= '<td style="font: 16px Arial, Helvetica, sans-serif;color: #4e4e4e;padding-right:15px;">Телефон:</td>';
            $mess .= '<td style="font: 16px Arial, Helvetica, sans-serif;color: #4e4e4e;"><b>'.$_REQUEST['phone'].'</b></td>';
            $mess .= '</tr>';
            $mess .= '<tr>';
            $mess .= '<td style="font: 16px Arial, Helvetica, sans-serif;color: #4e4e4e;padding-right:15px;">E-mail:</td>';
            $mess .= '<td style="font: 16px Arial, Helvetica, sans-serif;color: #4e4e4e;"><b>'.$_REQUEST['email'].'</b></td>';
            $mess .= '</tr>';
            $mess .= '</table><br>';
            require_once($_SERVER["DOCUMENT_ROOT"] . "/cart/mounting/mounting_data.php");
            $mess .= getEmailList();

            $emails_arr = Array(
                'd.portu.by@yandex.ru',
                //'nadida.hi@yandex.ru',
                'a.chilichihin@decor-evroplast.ru',
                'A.Visotskaya@decor-evroplast.ru',
                'G.Paskar@decor-evroplast.ru',
                $dealer_email
            );
            $subj = 'Калькулятор стоимости монтажа № '.$number_r;
            foreach($emails_arr as $email) {
                $fields = array('EMAIL'=>$email, 'MESS'=>$mess, 'SUBJ' => $subj);
                CEvent::SendImmediate("EUROPLAST_MOUNT_CALC", s1, $fields, "N");
            }

            $mess = '<p style="font: 16px Arial, Helvetica, sans-serif;color: #4e4e4e;margin-bottom:0;">На сайте <a href="https://perfom-decor.ru/" target="_blank">perfom-decor.ru</a> вы воспользовались калькулятором расчета стоимости монтажа <br>во время оформления заказа № <b>'.$number_r.'</b>.</p><br>';
            $mess .= getEmailList();
            $fields = array('EMAIL'=>$_REQUEST['email'], 'MESS'=>$mess, 'SUBJ' => $subj);
            CEvent::SendImmediate("EUROPLAST_MOUNT_CALC", s1, $fields, "N");
        }
        ?>
        <?
        setcookie("order_send",1,time()+300,'/',$_SERVER['HTTP_HOST']); // установка фильтра на повторную отправку
        ?>
        <?
            /*$request = '<p class="e-final-text  e-final-title">Дорогой покупатель, <br>спасибо за Ваш заказ!</p>';
            $request .='<p class="e-final-text">Вся необходимая информация по заказу отправлена на указанный Вами адрес электронной почты.</p>';
            $request .= '<p class="e-final-text">Ожидайте звонка нашей службы доставки.</p>';*/
            if($phone) {
                /*$request .= '<p class="e-final-text e-final-text-normal">По всем вопросам вы можете обратиться <br>в службу поддержки потребителей:</p>';
                $request .= '<p class="e-final-text">'.$phone.'</p>';*/
            }
            //$payment_link определяется в save_order.php
            if(isset($payment_link)) {
              if ($payment_link != '') {
                  /*$request = '<p class="e-final-text  e-final-title">Дорогой покупатель, <br>спасибо за Ваш заказ!</p>';
                  $request .='<p class="e-final-text">Вы можете оплатить ваш заказ по ссылке:</p>';
                  $request .='<a href="'.$payment_link.'" target="_blank" class="e-final-button">Перейти к оплате</a>';
                  $request .='<p class="e-final-text">Данная ссылка отправлена вам письмом на указанный e-mail вместе <br>с информацией о заказе.</p>';*/
              } else {
                  /*$request = '<p class="e-final-text  e-final-title">Дорогой покупатель, <br>спасибо за Ваш заказ!</p>';
                  $request['data'] .='<p class="e-final-text">При формировании ссылки на оплату произошла ошибка. Для оплаты онлайн зайдите в ваш личный кабинет на сайте, выберите необходимый заказ и нажмите кнопку "Онлайн оплата"</p>';*/
              }
            }

        ?>

        <? require_once($_SERVER["DOCUMENT_ROOT"] . "/cart/save_order.php"); ?>



<?
$request['data'] = $number_r;
echo json_encode($request);?>
<?
$http_host_temp = explode(":",$_SERVER['HTTP_HOST']);
$_SERVER['HTTP_HOST'] = $http_host_temp[0];
setcookie("order_send",1,time()+300,'/', $_SERVER['HTTP_HOST']); // установка фильтра на повторную отправку
?>


