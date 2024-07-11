<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}




exit;




global $my_dealer;
global $my_city;
$my_city = $APPLICATION->get_cookie('my_city');
//$my_city = 3109;

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
    while($dealer_counter_ob = $dealer_counter_res->GetNextElement()) {
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
            //CIBlockElement::SetPropertyValueCode($dealer_counter['ID'], 'COUNTER_VALUE', $email_number);

            //$email_number = rand(2,count($email_manager)-1);
        }
    } 
    else {
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
                //CIBlockElement::SetPropertyValueCode($dealer_counter['ID'], 'COUNTER_VALUE', $email_number);
            }
    	  }
    }

    if ($email_number > 0) {
        //если номер больше длины массива
        if($email_number > count($email_manager) - 1) $email_number = count($email_manager) - 1;
        $dealer = '';
        if($_REQUEST['sellout'] == 'y') {
            $dealer .= '<br><span style="color:#E41C1C;display:block;max-width:720px;width:100%;">В Вашей корзине есть товары из&nbsp;«распродажи», их количество ограничено. <br>После оформления заказа с&nbsp;вами свяжется менеджер и&nbsp;уточнит возможность покупки данного количества выбранного товара.</span><br>';
        }
        $dealer .= '<br>Обслуживание заказа производит <b>фирменный магазин Европласт</b>.<br>Справочную информацию Вы можете получить по телефону: <b>'.$email_info[$email_number].'</b><br>E-mail: <b>'. $email_manager[$email_number].'</b><br>';
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

        //require_once($_SERVER["DOCUMENT_ROOT"] . "/cart/save_order.php");

        $fields = array('EMAIL'=>$_REQUEST['email'], 'EMAIL_D' => $email_manager[$email_number], 'PRODUCT_LIST' => $pr_list, 'NUM_Z' => $number_l, 'DEALER_INFO'=>$dealer, 'CLIENT'=>$from, 'DEALER_CONTACTS'=>$dealer_contacts,'RESPOND'=>$email_manager[$email_number]);
        //CEvent::SendImmediate("EUROPLAST_ORDER_SALE_CLIENT", s1, $fields, "N");
        $from .= $from_plus;

        $fields = array('EMAIL'=>$email_manager[$email_number], 'PRODUCT_LIST'=>$pr_list, 'NUM_Z' => $number_l, 'DEALER_INFO'=>$dealer, 'CLIENT'=>$from, 'DEALER_CONTACTS'=>$dealer_contacts,'RESPOND'=>$_REQUEST['email']);
        //CEvent::SendImmediate("EUROPLAST_ORDER_SALE", s1, $fields, "N");

        $fields = array('EMAIL'=>'a.chilichihin@decor-evroplast.ru', 'PRODUCT_LIST'=>$pr_list, 'NUM_Z' => $number_l, 'DEALER_INFO'=>$dealer, 'CLIENT'=>$from, 'DEALER_CONTACTS'=>$dealer_contacts);
        //CEvent::SendImmediate("EUROPLAST_ORDER_SALE", s1, $fields, "N");

        $fields = array('EMAIL'=>'d.portu.by@yandex.ru', 'PRODUCT_LIST'=>$pr_list, 'NUM_Z' => $number_l, 'DEALER_INFO'=>$dealer, 'CLIENT'=>$from, 'DEALER_CONTACTS'=>$dealer_contacts,'PAYMENT_MESS'=>$payment_mess);
        //CEvent::SendImmediate("EUROPLAST_ORDER_SALE", s1, $fields, "N");

        $dealer_email = $email_manager[$email_number];

        echo $email_manager[$email_number].'<br>';
        echo 'a.chilichihin@decor-evroplast.ru'.'<br>';
        echo 'd.portu.by@yandex.ru'.'<br>';

        //CIBlockElement::SetPropertyValueCode(3109, 'email_number', $email_number);

    } else {

        //require_once($_SERVER["DOCUMENT_ROOT"] . "/cart/save_order.php");

        $fields = array('EMAIL'=>$_REQUEST['email'], 'PRODUCT_LIST'=>$pr_list, 'NUM_Z' => $number_l, 'DEALER_INFO'=>'', 'CLIENT'=>$from, 'DEALER_CONTACTS'=>$dealer_contacts,'RESPOND'=>'store@decor-evroplast.ru');
        //CEvent::SendImmediate("EUROPLAST_ORDER_SALE", s1, $fields, "N");
        $from .= $from_plus;
        $fields = array('EMAIL'=>'store@decor-evroplast.ru', 'PRODUCT_LIST'=>$pr_list, 'NUM_Z' => $number_l, 'DEALER_INFO'=>$dealer, 'CLIENT'=>$from, 'DEALER_CONTACTS'=>$dealer_contacts,'RESPOND'=>$_REQUEST['email']);
        //CEvent::SendImmediate("EUROPLAST_ORDER_SALE", s1, $fields, "N");
        $fields = array('EMAIL'=>'a.chilichihin@decor-evroplast.ru', 'PRODUCT_LIST'=>$pr_list, 'NUM_Z' => $number_l, 'DEALER_INFO'=>'', 'CLIENT'=>$from, 'DEALER_CONTACTS'=>$dealer_contacts);
        //CEvent::SendImmediate("EUROPLAST_ORDER_SALE", s1, $fields, "N");
        $fields = array('EMAIL'=>'d.portu.by@yandex.ru', 'PRODUCT_LIST'=>$pr_list, 'NUM_Z' => $number_l, 'DEALER_INFO'=>'', 'CLIENT'=>$from, 'DEALER_CONTACTS'=>$dealer_contacts);
        //CEvent::SendImmediate("EUROPLAST_ORDER_SALE", s1, $fields, "N");
       
        echo 'store@decor-evroplast.ru'.'<br>';
        echo 'a.chilichihin@decor-evroplast.ru'.'<br>';
        echo 'd.portu.by@yandex.ru'.'<br>';

        $dealer_email = 'store@decor-evroplast.ru';
    }
    
    /*if($_REQUEST['mounting'] == 'Y' || isset($_COOKIE['calc'])) {
        $subj = "Запрос на расчет монтажа";
        if($_REQUEST['mounting'] != 'Y') $subj = "Клиент интересовался калькулятором.";
        $fields = array('EMAIL'=>'G.Paskar@decor-evroplast.ru', 'PRODUCT_LIST'=>$pr_list, 'NUM_Z' => $subj, 'DEALER_INFO'=>$dealer, 'CLIENT'=>$from, 'DEALER_CONTACTS'=>$dealer_contacts);
        //CEvent::SendImmediate("EUROPLAST_ORDER_SALE", s1, $fields, "N");
        $fields = array('EMAIL'=>'a.chilichihin@decor-evroplast.ru', 'PRODUCT_LIST'=>$pr_list, 'NUM_Z' => $subj, 'DEALER_INFO'=>$dealer, 'CLIENT'=>$from, 'DEALER_CONTACTS'=>$dealer_contacts);
        //CEvent::SendImmediate("EUROPLAST_ORDER_SALE", s1, $fields, "N");
        $fields = array('EMAIL'=>'A.Visotskaya@decor-evroplast.ru', 'PRODUCT_LIST'=>$pr_list, 'NUM_Z' => $subj, 'DEALER_INFO'=>$dealer, 'CLIENT'=>$from, 'DEALER_CONTACTS'=>$dealer_contacts);
        //CEvent::SendImmediate("EUROPLAST_ORDER_SALE", s1, $fields, "N");
    }*/

} else { // Другие регионы


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

    //print_r($loc['dealers_list']['VALUE']);

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
	        $dealer = '';
	        if($_REQUEST['sellout'] == 'y') {
	            $dealer .= '<br><span style="color: #E41C1C;">В Вашей корзине есть товары из&nbsp;«распродажи», их количество ограничено. После оформления заказа с&nbsp;вами свяжется менеджер и&nbsp;уточнит возможность покупки данного количества выбранного товара.</span><br>';
	        }
	        $dealer .= '<br>Обслуживание заказа в вашем регионе производит наш официальный дилер <b>'. $dealer_name. '</b>.<br>';
	        if ($cart_dealer['order_phone']['VALUE'] || $cart_dealer['phones']['VALUE']) {
	            $phones = $cart_dealer['order_phone']['VALUE'] != '' ? $cart_dealer['order_phone']['VALUE'] : $cart_dealer['phones']['VALUE'];
	            $phones = explode(';', $phones);
	            //$phone = phone(trim($phones[0]));
	            $dealer .= 'Справочную информацию Вы можете получить по телефону: <b>'. $phone .'</b><br>';
	            $dealer_contacts .= '<b>Телефон: </b>'. $phone;
	        }
	        if ($email) {
	            $dealer .= 'E-mail: <b>'. $email . '</b><br>';
	            $dealer_contacts .= '<br><b>E-mail: </b>'. $email;
	            $fields = array('EMAIL'=>$email, 'PRODUCT_LIST'=>$pr_list, 'NUM_Z' => $number_l, 'DEALER_INFO'=> $dealer, 'CLIENT'=>$from, 'DEALER_CONTACTS'=>$dealer_contacts,'RESPOND'=>$_REQUEST['email']);
	            //CEvent::SendImmediate("EUROPLAST_ORDER_SALE", s1, $fields, "N");
	        }
	        $dealer_email = $email;
	    }
	    // Ротация
	    if (($email_number += 1) > count($loc['dealers_list']['VALUE'])) $email_number = 1;
	    CIBlockElement::SetPropertyValueCode($loc['ID'], 'email_number', $email_number);
	} 
	else { // 1 дилер в регионе по приоритету сортировки
	    if ($email_number != 0) {
	        $email_number = 0;
	        //CIBlockElement::SetPropertyValueCode($loc['ID'], 'email_number', $email_number);
	    }

	    $dealer = '';
	    if($_REQUEST['sellout'] == 'y') {
	        $dealer .= '<br><span style="color: #E41C1C;">В Вашей корзине есть товары из&nbsp;«распродажи», их количество ограничено. После оформления заказа с&nbsp;вами свяжется менеджер и&nbsp;уточнит возможность покупки данного количества выбранного товара.</span><br>';
	    }
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
	        $dealer .= '<br>Обслуживание заказа в вашем регионе производит наш официальный дилер <b>'. $dealer_name. '</b>.<br>';
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
	            //CEvent::SendImmediate("EUROPLAST_ORDER_SALE", s1, $fields, "N");
	        }
	    }
	    $dealer_email = $email;
	}

	print_r($dealer);

	//require_once($_SERVER["DOCUMENT_ROOT"] . "/cart/save_order.php");

	$fields = array('EMAIL'=>$_REQUEST['email'], 'EMAIL_D'=>$email, 'PRODUCT_LIST'=>$pr_list, 'NUM_Z' => $number_l, 'DEALER_INFO'=>$dealer, 'CLIENT'=>$from, 'DEALER_CONTACTS'=>$dealer_contacts,'RESPOND'=>$email);
	//CEvent::SendImmediate("EUROPLAST_ORDER_SALE", s1, $fields, "N");
	$from .= $from_plus;

	$fields = array('EMAIL'=>'d.portu.by@yandex.ru', 'PRODUCT_LIST'=>$pr_list, 'NUM_Z' => $number_l, 'DEALER_INFO'=>$dealer, 'CLIENT'=>$from, 'DEALER_CONTACTS'=>$dealer_contacts);
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

}