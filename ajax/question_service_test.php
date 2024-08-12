<?require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}
//require_once($_SERVER["DOCUMENT_ROOT"] . "/ajax/region_mail.php");

$type = $_GET['type'];

$base_url = "https://perfom-decor.ru";
if($_SERVER['HTTP_HOST'] == 'eplast.loc') {
    $base_url = "http://eplast.loc";
}
if($_SERVER['HTTP_HOST'] == 'e.loc') {
    $base_url = "http://e.loc";
}
if($_SERVER['HTTP_HOST'] == 'dev-evroplast.ru') {
    $base_url = "https://dev-evroplast.ru";
}

//проверка авторизации и прав
global $USER;
$user_groups = Array();
$can_write = false;
if($USER->IsAuthorized()) {
    $user_id = $USER->GetID();
    $res = CUser::GetUserGroupList($user_id);
    while ($arGroup = $res->Fetch()) {
        $user_groups[] = $arGroup['GROUP_ID'];
    }
}
if(in_array("1",$user_groups)
    || in_array("9",$user_groups)
    || in_array("10",$user_groups)) {
    $can_write = true;
}


// обработка темы вопроса
function check_val($n) {
	switch ($n) {
		case '1': return 'Ассортимент и уточнение по размерам';
		case '2': return 'Монтаж изделий';
		case '3': return "Свойства изделий";
		case '4': return 'Претензии и вопросы по заказам и сервису';
		case '5': return "Гарантийные обязательства";
		case '6': return "Работа магазинов";
		case '7': return "Другое";
	}
}

// обратная обработка темы вопроса
function check_val_reverse($n) {
	switch ($n) {
		case 'Ассортимент и уточнение по размерам': return "1";
		case 'Монтаж изделий': return "2";
		case 'Свойства изделий': return "3";
		case 'Претензии и вопросы по заказам и сервису': return "4";
		case 'Гарантийные обязательства': return "5";
		case 'Работа магазинов': return "6";
		case 'Другое': return "7";
	}
}

//счетчик evroplast.ru
function counter() {
	$db_props = CIBlockElement::GetProperty(38, 41816, array("sort" => "asc"), Array("CODE"=>"COUNT_NUMBER"));
	if($ar_props = $db_props->Fetch()) {
		$d_number = IntVal($ar_props["VALUE"]);
		$number = $d_number + 1;
		CIBlockElement::SetPropertyValueCode(41816,"COUNT_NUMBER", $number);
	}
	return $number;
}

// автоопределение региона
$ip_city = $APPLICATION->get_cookie('ip_city');
$arFilter = Array('IBLOCK_ID' => 7, 'ACTIVE' => 'Y', 'ID' => $ip_city);
$db_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter);
$ip_loc = $db_list->GetNextElement();
if ($ip_loc) $ip_loc = array_merge($ip_loc->GetFields(), $ip_loc->GetProperties());

// определение выбранного региона
function get_choose_reg($my_city) {
	$new_arFilter = Array('IBLOCK_ID' => 7, 'ACTIVE' => 'Y', 'ID' => $my_city);
	$new_db_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $new_arFilter);
	$new_ip_loc = $new_db_list->GetNextElement();
	if ($new_ip_loc) $new_ip_loc = array_merge($new_ip_loc->GetFields(), $new_ip_loc->GetProperties());
	$reg_id = $new_ip_loc['discountregion']['VALUE'];
	$cur_loc = $new_ip_loc['NAME'];
	$arr = array("reg_id"=>$reg_id,"cur_loc"=>$cur_loc);
	return $arr;
}

//получаем email по региону
function get_email($reg_id) {
	$new_arFilter = Array('IBLOCK_ID' => 8, 'ACTIVE' => 'Y', 'ID' => $reg_id);
	$new_db_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $new_arFilter);
	$reg_loc = $new_db_list->GetNextElement();
	if ($reg_loc) $reg_loc = array_merge($reg_loc->GetFields(), $reg_loc->GetProperties());
	if($reg_loc['techmail']['VALUE']) {
		$reg_mail = $reg_loc['techmail']['VALUE'];
	}
	else {
	  //$reg_mail = "D.Rudykin@decor-evroplast.ru";
	  $reg_mail = "L.Osetrova@decor-evroplast.ru";
	  //$reg_mail = "V.Dudnikova@decor-evroplast.ru";
	}
	//if($reg_mail == "G.Groian@decor-evroplast.ru") $reg_mail = "D.Rudykin@decor-evroplast.ru";
	if($reg_mail == "G.Groian@decor-evroplast.ru" ||
        $reg_mail == "D.Rudykin@decor-evroplast.ru") {
        $reg_mail = "L.Osetrova@decor-evroplast.ru";
        //$reg_mail = "V.Dudnikova@decor-evroplast.ru";
    }

    //по 30.04.2021
    //$reg_mail = $reg_mail == "D.Rudykin@decor-evroplast.ru" ? "n.ovchinnikova@decor-evroplast.ru" : $reg_mail;

	return $reg_mail;
}

//выбираем ответственного
function choose_spec($subj,$reg_mail) {
	if($subj == 1 || $subj == 3) {
        //$spec = Array("Ольга Гмыря");
        $spec = Array("Андрей Чиличихин");
	}
	elseif($subj == 2) {
		$spec = Array("Александра Высоцкая");
	}
	elseif($subj == 4 || $subj == 5) {
		$spec = Array("Сергей Авдеев","Наталья Овчинникова");
	}
	elseif($subj == 6 || $subj == 7) {
		$filter = Array("EMAIL" => $reg_mail);
		$order = array('sort' => 'asc');
		$tmp = 'sort';
		$rsUsers = CUser::GetList($order, $tmp,$filter);
		while ($arUser = $rsUsers->Fetch()) {
		  $spec = Array($arUser["NAME"]);
		}
	}
	return $spec;
}


//ВОПРОС ОТПРАВЛЕН ПОЛЬЗОВАТЕЛЕМ
if( $type == "qst") {

	$key = $_GET["new_val"];
	$rand = $_COOKIE['rand'];
	$str = $_POST['aqs-qst'];
	$str = str_replace(array("\r\n","\n\r","\r","\n","\\r","\\n","\\r\\n"),'',$str);
	preg_match_all('#.{1}#uis', $str, $out);
	$val = $out[0][2].$rand.$out[0][5].$rand.$out[0][8];
	$check_key = md5($val);
	//print_r(md5($val));

	if(( $key != $check_key) || ($str == 'Консультация' ) || (strripos($str, 'Вам предложение от генерального партнёра') !== false)){ // Надо подумать по фильтрации !!!
		$numb = rand ( 300 , 400);
		//echo "Ваш вопрос зарегистирирован под номером №".$numb;
		echo '<div class="q-form-title">вопрос <br>отправлен</div>
            <div class="q-form-res">
                Ваш вопрос под номером <span>№'.$numb.'</span> <br>на&nbsp;модерации, ожидайте ответа <br>на&nbsp;почте.
            </div>';
		//счетчик спама
		$db_props = CIBlockElement::GetProperty(38, 44914, array("sort" => "asc"), Array("CODE"=>"COUNT_NUMBER"));
		if($ar_props = $db_props->Fetch()) {
			$d_number_spam = IntVal($ar_props["VALUE"]);
			$spam_number = $d_number_spam + 1;
			CIBlockElement::SetPropertyValueCode(44914,"COUNT_NUMBER", $spam_number);
		}
		die();
	}

	if($_POST['aqs-city']) $my_city = $_POST['aqs-city'];
	if($_POST['aqs-name']) $name = $_POST['aqs-name'];
	if($_POST['aqs-email']) $mail = $_POST['aqs-email'];
	if($_POST['aqs-tel']) $phone = $_POST['aqs-tel'];
	if($_POST['aqs-subj']) $subj = $_POST['aqs-subj'];
	if($_POST['aqs-qst']) $text = $_POST['aqs-qst'];
	if($_POST['aqs-loc']) $qst_loc = $_POST['aqs-loc'];
	$text = str_replace(array("\r\n","\r","\n","\\r","\\n","\\r\\n"),"<br/>",$text);

	//проверка на наличие url
	if (preg_match("/(http|https?:\/\/.)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&\/\/=]*)/", $name)||preg_match("/(http|https?:\/\/.)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&\/\/=]*)/", $text)) {
	    $moderation = "На модерацию!";
	    $stat = "На модерации";
	}
	else {
	  $stat = "Новый вопрос";
	}


	//счетчик
	$number = counter();

	//выбранный регион
	$arr = get_choose_reg($my_city);
	$reg_id = $arr["reg_id"];
	$cur_loc = $arr["cur_loc"];
	$reg_mail = get_email($reg_id);//если вопросы по регионам

	echo $my_city;

}//-------------------------------

?>