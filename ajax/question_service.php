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

//счетчик eplast.loc
/*function counter() {
	$db_props = CIBlockElement::GetProperty(38, 38243, array("sort" => "asc"), Array("CODE"=>"COUNT_NUMBER"));
	if($ar_props = $db_props->Fetch()) {
		$d_number = IntVal($ar_props["VALUE"]);
		$number = $d_number + 1;
		CIBlockElement::SetPropertyValueCode(38243,"COUNT_NUMBER", $number);
	}
	return $number;
}*/

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

function send_to_spec($spec,$spec_subj,$spec_mail,$templ) {
//$templ = "E_QST_SERV";
foreach($spec as $name) {
	if($name == "Ольга Гмыря") {

		//$fields = array('EMAIL'=>'o.gmirya@decor-evroplast.ru', 'SUBJ'=>$spec_subj,'TEXT'=>$spec_mail);
		//$fields = array('EMAIL'=>'a.chilichihin@decor-evroplast.ru', 'SUBJ'=>$spec_subj,'TEXT'=>$spec_mail);
      	//CEvent::SendImmediate($templ, s1, $fields, "N");

        $fields = array('EMAIL'=>'d.mescheryakova@decor-evroplast.ru', 'SUBJ'=>$spec_subj,'TEXT'=>$spec_mail);
        CEvent::SendImmediate($templ, s1, $fields, "N");
        $fields = array('EMAIL'=>'s.burova@decor-evroplast.ru', 'SUBJ'=>$spec_subj,'TEXT'=>$spec_mail);
        CEvent::SendImmediate($templ, s1, $fields, "N");
	}
	if($name == "Александра Высоцкая") {

		$fields = array('EMAIL'=>'A.Visotskaya@decor-evroplast.ru', 'SUBJ'=>$spec_subj,'TEXT'=>$spec_mail);
      	CEvent::SendImmediate($templ, s1, $fields, "N");
	}
	if($name == "Сергей Авдеев") {

		$fields = array('EMAIL'=>'S.Avdeev@decor-evroplast.ru', 'SUBJ'=>$spec_subj,'TEXT'=>$spec_mail);
      	CEvent::SendImmediate($templ, s1, $fields, "N");
	}
	if($name == "Наталья Овчинникова") {

		$fields = array('EMAIL'=>'n.ovchinnikova@decor-evroplast.ru', 'SUBJ'=>$spec_subj,'TEXT'=>$spec_mail);
      	CEvent::SendImmediate($templ, s1, $fields, "N");
	}
	if($name == "Ольга Кока") {

		$fields = array('EMAIL'=>'O.Koka@decor-evroplast.ru', 'SUBJ'=>$spec_subj,'TEXT'=>$spec_mail);
      	CEvent::SendImmediate($templ, s1, $fields, "N");
	}
	if($name == "Андрей Чиличихин") {

		$fields = array('EMAIL'=>'a.chilichihin@decor-evroplast.ru', 'SUBJ'=>$spec_subj,'TEXT'=>$spec_mail);
      	CEvent::SendImmediate($templ, s1, $fields, "N");
	}
	if($name == "Дмитрий Рудыкин") {
		$fields = array('EMAIL'=>'D.Rudykin@decor-evroplast.ru','SUBJ'=>$spec_subj,'TEXT'=>$spec_mail);
		//$fields = array('EMAIL'=>'L.Osetrova@decor-evroplast.ru','SUBJ'=>$spec_subj,'TEXT'=>$spec_mail);
        //$fields = array('EMAIL'=>'V.Dudnikova@decor-evroplast.ru','SUBJ'=>$spec_subj,'TEXT'=>$spec_mail);
      	CEvent::SendImmediate($templ, s1, $fields, "N");
	}
    if($name == "Любовь Осетрова") {
        $fields = array('EMAIL'=>'L.Osetrova@decor-evroplast.ru','SUBJ'=>$spec_subj,'TEXT'=>$spec_mail);
        //$fields = array('EMAIL'=>'V.Dudnikova@decor-evroplast.ru','SUBJ'=>$spec_subj,'TEXT'=>$spec_mail);
        CEvent::SendImmediate($templ, s1, $fields, "N");
    }
    if($name == "Наталья Рябчикова") {

        $fields = array('EMAIL'=>'N.Ryabchikova@decor-evroplast.ru','SUBJ'=>$spec_subj,'TEXT'=>$spec_mail);
        CEvent::SendImmediate($templ, s1, $fields, "N");
    }
    if($name == "Валентина Дудникова") {
        $fields = array('EMAIL'=>'V.Dudnikova@decor-evroplast.ru','SUBJ'=>$spec_subj,'TEXT'=>$spec_mail);
        CEvent::SendImmediate($templ, s1, $fields, "N");
    }
	if($name == "Алексей Брук") {
		$fields = array('EMAIL'=>'A.Bruk@decor-evroplast.ru', 'SUBJ'=>$spec_subj,'TEXT'=>$spec_mail);
      	CEvent::SendImmediate($templ, s1, $fields, "N");
	}
}

// дублирование
$fields = array('EMAIL'=>'nadida.hi@yandex.ru','SUBJ'=>$spec_subj,'TEXT'=>$spec_mail);
CEvent::SendImmediate($templ, s1, $fields, "N");
$fields = array('EMAIL'=>'d.portu.by@yandex.ru','SUBJ'=>$spec_subj,'TEXT'=>$spec_mail);
//CEvent::SendImmediate($templ, s1, $fields, "N");

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

	//Kill Bill

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

	/*if (empty($_POST['aqs-page'])||$_POST['aqs-email']=="romakoval@bk.ru"||$_POST['aqs-tel']=='89096267777') {
		$numb = rand ( 300 , 400);
		echo "Ваш вопрос зарегистирирован<br> под номером №".$numb;
		//счетчик спама
		$db_props = CIBlockElement::GetProperty(38, 44914, array("sort" => "asc"), Array("CODE"=>"COUNT_NUMBER"));
		if($ar_props = $db_props->Fetch()) {
			$d_number_spam = IntVal($ar_props["VALUE"]);
			$spam_number = $d_number_spam + 1;
			CIBlockElement::SetPropertyValueCode(44914,"COUNT_NUMBER", $spam_number);
		}
		die();
	}*/

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

//собираем данные для записи в БД
$ext_id = __random_number_order();
if(!isset($moderation)) $stat = "Новый вопрос";
$qst_subj = check_val($subj);
$ext_id = __random_number_order();
$spec = isset($moderation) ? "Ольга Гмыря" : choose_spec($subj,$reg_mail);
//$spec = isset($moderation) ? "Андрей Чиличихин" : choose_spec($subj,$reg_mail);
//$spec = choose_spec($subj,$reg_mail);
$date = date('d.m.Y H:i:s');

//сохраняем файл
if (!empty($_FILES['aqs-file']['tmp_name'])) {
	$dir_calc = $_SERVER["DOCUMENT_ROOT"].'/upload/question_service/user/';
	$file_name = $_FILES['aqs-file']['name'];
	$path = $dir_calc.$file_name;
	if (copy($_FILES['aqs-file']['tmp_name'], $path)) {
		$the_file = $path;
		$base_path = $base_url.'/upload/question_service/user/'.$file_name;
	}
	else {
		$the_file = "";
		$base_path = "";
	}
}
else {
	$the_file = "";
	$base_path = "";
}

$arFilter = Array("IBLOCK_ID"=>37, "ACTIVE"=>"Y", "NAME"=>$number);
$res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), Array());
while($ob = $res->GetNextElement()) {
    $item = array_merge($arFields = $ob->GetFields(),$arFields = $ob->GetProperties());
    if($item['QST_NAME']['VALUE'] == $name
        && $item['QST_PHONE']['VALUE'] == $phone
        && $item['QST_MAIL']['VALUE'] == $mail
        && $item['QST']['VALUE']['TEXT'] == $txt
    ) {
        die();
    }
}

//записываем в БД
$el = new CIBlockElement;

$PROP = array();
$PROP['EXTERNAL_ID'] = $ext_id;
$PROP['QST_STATUS'] = $stat;
$PROP['MY_CITY'] = $my_city;
$PROP['QST_SUBJ'] = $qst_subj;
$PROP['QST'] = $text;
$PROP['QST_NAME'] = $name;
$PROP['QST_PHONE'] = $phone;
$PROP['QST_MAIL'] = $mail;
$PROP['QST_FILE'] = $base_path;
$PROP['QST_SPEC'] = $spec;
$PROP['QST_DATE'] = $date;
$PROP['QST_PAGE'] = $_POST['aqs-page'];
$PROP['QST_LOC'] = $qst_loc;

$arLoadProductArray = Array(
  "IBLOCK_SECTION_ID" => false,
  "IBLOCK_ID"      => 37,
  "PROPERTY_VALUES"=> $PROP,
  "NAME"           => $number,
  "ACTIVE"         => "Y"
);


if($PRODUCT_ID = $el->Add($arLoadProductArray)) {
  //собираем сообщение
	$from  = "<p style='font-size:16px;line-height:18px;margin:0;'><b>Тема: </b>".$qst_subj."<br>";
	$from .= "<b>Пользователь: </b>".$name."<br>";
	$from .= "<b>Телефон: </b>".$phone."<br>";
	$from .= "<b>E-mail: </b>".$mail."<br>";
	$from .= "<b>Город (местоположение): </b>".$qst_loc."<br>";
	$from_ryk = $from;  //собираем технические данные для Алексея Рыка
	$from_ryk .= "<b>Переход на вопрос со страницы: </b>".$_POST['aqs-page']."<br>";
	$from_ryk .= '<b>IP: </b><a href="http://whois.domaintools.com/'.GetIP().'">'.GetIP().'</a><br>';
	$from_ryk .= '<b>Браузер: </b>'.$_SERVER['HTTP_USER_AGENT'].'<br>';
	if($ip_loc) $from_ryk .= "<b>Авто-определение региона: </b>".$ip_loc['NAME']."<br>";
	$from_ryk .= "<b>Выбранный регион на сайте: </b>".$cur_loc."<br>";
	$from .= "<b>Выбранный регион на сайте: </b>".$cur_loc."<br>";
	$spec_row = '';
	foreach($spec as $person) {
        $spec_row .= $person.', ';
    }
    $spec_row = substr($spec_row, 0, -2);
	$from .= "<br><b>Ответственные исполнители: </b>".$spec_row."</p><br>";
	$from_ryk .= "<br><b>Ответственные исполнители: </b>".$spec_row."</p><br>";

  //приложение
  if( $base_path != "") {
  	$attach = "<br><b>Приложение: </b><a href='".$base_path."' style='color:#000;' download>".$file_name."</a>";
  }
  else {
  	$attach = "";
  }

	$spec_link = $base_url."/question_service/answer.php?ext_id=".$ext_id;

	$qst_text = '<p style="margin:0px;font-size:16px;color:#849795;"><strong>Вопрос №'.$number.'</strong><br></p>
               <p style="font-size:16px;color:#000;margin-top:0;margin-bottom:0;">'.$text.$attach.'</p><br>';

  //отправка сообщений

  //пользователю
   $user_subj = "Ваш вопрос принят";

	$attention = $subj == 2 ? "<p style='margin-bottom:0;font-size:16px;'>Просим принять во внимание, что ответ на вопрос по монтажу может занять дополнительное время.</p>" : "";

   $user_mail = '<p style="margin:0px;margin-top:35px;font-size:16px;color:#010101;"><strong>Здравствуйте! Вы оставили вопрос на сайте <a href="https://perfom-decor.ru" target="_blank" style="color:#849795;text-decoration: none;">perfom-decor.ru</a>.</strong></p><br>';
   $user_mail .= $qst_text;
   $user_mail .= $attention;

   $user_sign = '<strong>Благодарим вас за обращение.</strong><br>';
   $user_sign .= '<strong>Мы ответим на ваш вопрос в ближайшее время.</strong>';

  	$fields = array('EMAIL'=>$mail, 'SUBJ'=>$user_subj,'TEXT'=>$user_mail,'SIGN'=>$user_sign);
      CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");
   $fields = array('EMAIL'=>'nadida.hi@yandex.ru', 'SUBJ'=>$user_subj,'TEXT'=>$user_mail,'SIGN'=>$user_sign);
      	  	CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");

   //сотрудникам
      $spec_subj = "Вопрос №".$number.". Поступил новый вопрос.";
 		$spec_first = '<p style="margin:0px;margin-top:35px;font-size:16px;color:#010101;"><strong>Здравствуйте! В службу технической поддержки добавлен новый вопрос</strong></p><br>';
 		$spec_last = '<p style="font-size:16px;margin:0;">Для ответа на данный вопрос <a href="'.$spec_link.'" target="_blank" style="color:#849795;">перейдите по ссылке</a>.</p>';

  //на модерацию
  	if(isset($moderation)) {

  		$spec_subj = "Вопрос №".$number.". НА МОДЕРАЦИЮ!";
 		$spec_first = '<p style="margin:0px;margin-top:35px;font-size:16px;color:#010101;"><strong>Здравствуйте! Добавлен новый вопрос с пометкой НА МОДЕРАЦИЮ</strong></p><br>';
 		$spec_last = '<p style="font-size:16px;margin:0;">Чтобы перенаправить вопрос <a href="'.$spec_link.'" target="_blank" style="color:#849795;">перейдите по ссылке</a>.</p>';

 		$mod_mail = $spec_first.$from_ryk.$qst_text.$spec_last;

      /*$fields = array('EMAIL'=>$mail, 'SUBJ'=>$spec_subj,'TEXT'=>$mod_mail);
      	CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");*/

        //$fields = array('EMAIL'=>'o.gmirya@decor-evroplast.ru', 'SUBJ'=>$spec_subj,'TEXT'=>$mod_mail);
        //$fields = array('EMAIL'=>'a.chilichihin@decor-evroplast.ru', 'SUBJ'=>$spec_subj,'TEXT'=>$mod_mail);
        //CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");

        $fields = array('EMAIL'=>'s.burova@decor-evroplast.ru', 'SUBJ'=>$spec_subj,'TEXT'=>$mod_mail);
        CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");

        $fields = array('EMAIL'=>'d.mescheryakova@decor-evroplast.ru', 'SUBJ'=>$spec_subj,'TEXT'=>$mod_mail);
        CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");

   	// дублирование
   	$fields = array('EMAIL'=>'d.portu.by@yandex.ru', 'SUBJ'=>$spec_subj,'TEXT'=>$mod_mail);
   	  	CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");

   	$fields = array('EMAIL'=>'nadida.hi@yandex.ru', 'SUBJ'=>$spec_subj,'TEXT'=>$mod_mail);
   	  	CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");
    }
    else {

    		$spec_mail = $spec_first.$from.$qst_text.$spec_last;
    		$mod_mail = $spec_first.$from_ryk.$qst_text.$spec_last;

    		/*$fields = array('EMAIL'=>$mail, 'SUBJ'=>$spec_subj,'TEXT'=>$spec_mail);
      	CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");*/

        //$fields = array('EMAIL'=>'o.gmirya@decor-evroplast.ru', 'SUBJ'=>$spec_subj,'TEXT'=>$mod_mail);
        //$fields = array('EMAIL'=>'a.chilichihin@decor-evroplast.ru', 'SUBJ'=>$spec_subj,'TEXT'=>$mod_mail);
        //CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");

        $fields = array('EMAIL'=>'s.burova@decor-evroplast.ru', 'SUBJ'=>$spec_subj,'TEXT'=>$mod_mail);
        CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");

        $fields = array('EMAIL'=>'d.mescheryakova@decor-evroplast.ru', 'SUBJ'=>$spec_subj,'TEXT'=>$mod_mail);
        CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");

      	$fields = array('EMAIL'=>'A.Bruk@decor-evroplast.ru', 'SUBJ'=>$spec_subj,'TEXT'=>$spec_mail);
      	  	CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");

      	// дублирование
      	$fields = array('EMAIL'=>'d.portu.by@yandex.ru', 'SUBJ'=>$spec_subj,'TEXT'=>$mod_mail);
      	  	CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");

      	$fields = array('EMAIL'=>'nadida.hi@yandex.ru', 'SUBJ'=>$spec_subj,'TEXT'=>$mod_mail);
      	  	CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");

      	$templ = "E_QST_SERV";


    		send_to_spec($spec,$spec_subj,$spec_mail,$templ);

    }

  //echo "Ваш вопрос зарегистирирован под номером №".$number;
    echo '<div class="q-form-title">вопрос <br>отправлен</div>
            <div class="q-form-res">
                Ваш вопрос под номером <span>№'.$number.'</span> <br>на&nbsp;модерации, ожидайте ответа <br>на&nbsp;почте.
            </div>';

}
else {
  //echo "Произошла ошибка. Попробуйте еще раз позже.";
    echo '<div class="q-form-title">произошла <br>ошибка</div>
            <div class="q-form-res">
                Попробуйте еще раз позже.
            </div>';
}
}//-------------------------------



//ВОПРОС ПЕРЕНАПРАВЛЕН

if ( $type=="rdrct" ) {

if(!$USER->IsAuthorized() || $USER->IsAuthorized() && !$USER->IsAuthorized()) {
    die();
}

// проверка на спам
$inbase = CIBlockElement::GetList(Array(),Array('IBLOCK_ID'=>37,'ID'=>$_POST['ap-subj-id'],'ACTIVE'=>'Y'));
if (intval($inbase->SelectedRowsCount()) == 0) {
    die();
}

$who = $_GET['name'];
$send = $_GET['send'];

if($_POST['ap-subj']) $subj = $_POST['ap-subj'];
if($_POST['ap-subj-id']) $prev_id = $_POST['ap-subj-id'];
if($_POST['reg']) $reg = $_POST['reg'];

if($send == 'subj') $new_subj = check_val($subj);
if($send == 'spec') {
    $rsUser = CUser::GetByID($subj);
    $arUser = $rsUser->Fetch();
    $new_spec = $arUser['NAME'];
}
if($send == 'reg') {
    $res = CIBlockElement::GetList(Array(),Array('IBLOCK_ID'=>7,'ID'=>$reg,'ACTIVE'=>'Y'));
    if($item = $res->GetNextElement()) {
        $item = array_merge($item->GetFields(), $item->GetProperties());
        $reg_name = $item['NAME'];
    }
}
$send_date = date('d.m.Y H:i:s');

//получаем имя спеца - кодировки зло!

	$rsUser = CUser::GetByID($who);
	$arUser = $rsUser->Fetch();
	$who = $arUser['NAME'];

//получаем данные для нового вопроса
$res = CIBlockElement::GetList(Array(),Array('IBLOCK_ID'=>37,'ID'=>$prev_id,'ACTIVE'=>'Y'));
while($item = $res->GetNextElement()) {
	$item = array_merge($item->GetFields(), $item->GetProperties());

	//собираем данные для записи в БД
	$ext_id = __random_number_order();
	$number = $item['NAME'];
	//$stat = $item['QST_STATUS']['VALUE'] == "Вопрос просрочен" ? "Вопрос просрочен" : "Вопрос перенаправлен" ;
	$stat = "Вопрос перенаправлен";
	$my_city = $item['MY_CITY']['VALUE'];
	$text = htmlspecialchars_decode($item['QST']['~VALUE']['TEXT']);
	$name = $item['QST_NAME']['VALUE'];
	$phone = $item['QST_PHONE']['VALUE'];
	$mail = $item['QST_MAIL']['VALUE'];
	$qst_loc = $item['QST_LOC']['VALUE'];
	$the_file = $item['QST_FILE']['VALUE'] != "" ? $item['QST_FILE']['VALUE'] : "";
	$date = $item['QST_DATE']['VALUE'];
	$old_subj = $item['QST_SUBJ']['VALUE'];
	$page = $item['QST_PAGE']['VALUE'];
	$answ = htmlspecialchars_decode($item['ANSW']['~VALUE']['TEXT']);
	$answ_date = $item['ANSW_DATE']['VALUE'];
	$answ_name = $item['ANSW_NAME']['VALUE'];
	$answ_file = $item['ANSW_FILE']['VALUE'];
}

$ARR = array();
$ARR['SEND_DATE'] = $send_date;
$ARR['SEND_WHO'] = $who;
$ARR['QST_STATUS'] = $stat;
if($send == 'subj') $ARR['SEND_SUBJ'] = $new_subj;
if($send == 'spec') $ARR['SEND_SPEC'] = $new_spec;
if($send == 'reg') $ARR['SEND_REG'] = $reg_name;


//записываем тему перенаправления
CIBlockElement::SetPropertyValuesEX($prev_id,37,$ARR);

if($send == 'subj') {
    if($subj == 6 || $subj == 7) {
        $arr = get_choose_reg($my_city);
        $reg_id = $arr["reg_id"];
        $cur_loc = $arr["cur_loc"];
        $reg_mail = get_email($reg_id);
    }
    else {
        $reg_mail = "";
    }
    $spec = choose_spec($subj,$reg_mail);
    $data = "Вопрос перенаправлен в тему: <span>".$new_subj."<span>";
}
if($send == 'spec') {
    $spec = Array($new_spec);
    $data = "Вопрос перенаправлен менеджеру: <span>".$new_spec."<span>";
    $new_subj = $old_subj;

}
if($send == 'reg') {
    $arr = get_choose_reg($reg);
    $reg_id = $arr["reg_id"];
    $cur_loc = $arr["cur_loc"];
    $reg_mail = get_email($reg_id);
    $spec = choose_spec(6,$reg_mail);
    $data = "Вопрос перенаправлен в: <span>".$reg_name."<span>";
    $new_subj = $old_subj;
    $my_city = $reg;

}

//записываем в БД
$el = new CIBlockElement;

$PROP = array();
$PROP['EXTERNAL_ID'] = $ext_id;
$PROP['QST_STATUS'] = $stat;
$PROP['MY_CITY'] = $my_city;
$PROP['QST_SUBJ'] = $new_subj;
$PROP['QST'] = $text;
$PROP['QST_NAME'] = $name;
$PROP['QST_PHONE'] = $phone;
$PROP['QST_MAIL'] = $mail;
$PROP['QST_LOC'] = $qst_loc;
$PROP['QST_FILE'] = $the_file;
$PROP['QST_SEND'] = 'Y';
$PROP['QST_SEND_ID'] = $prev_id;
$PROP['QST_SPEC'] = $spec;
$PROP['QST_DATE'] = $date;
$PROP['QST_PAGE'] = $page;
$PROP['ANSW'] = $answ;
$PROP['ANSW_DATE'] = $answ_date;
$PROP['ANSW_NAME'] = $answ_name;
$PROP['ANSW_FILE'] = $answ_file;

$arLoadProductArray = Array(
  "IBLOCK_SECTION_ID" => false,
  "IBLOCK_ID"      => 37,
  "PROPERTY_VALUES"=> $PROP,
  "NAME"           => $number,
  "ACTIVE"         => "Y"
);

if($PRODUCT_ID = $el->Add($arLoadProductArray)) {

	//отправка сообщений специалистам

	$arr = get_choose_reg($my_city);
	$reg_id = $arr["reg_id"];
	$cur_loc = $arr["cur_loc"];

	$from  = "<p style='font-size:16px;line-height:18px;margin:0;'><b>Тема: </b>".$new_subj."<br>";
	$from .= "<b>Пользователь: </b>".$name."<br>";
	$from .= "<b>Телефон: </b>".$phone."<br>";
	$from .= "<b>E-mail: </b>".$mail."<br>";
	$from .= "<b>Город (местоположение): </b>".$qst_loc."<br>";
	$from .= "<b>Выбранный регион на сайте: </b>".$cur_loc."<br>";
	$from .= "<b>Перенаправил: </b>".$who.", ".$send_date."</p><br>";

	if( $the_file != "") {
		$file_name = explode("/",$the_file);
		$length = count($file_name);
		$file_name = $file_name[$length-1];

		$attach = "<br><b>Приложение: </b><a href='".$the_file."' style='color:#000;' download>".$file_name."</a>";
	}
	else {
		$attach = "";
	}
	$spec_link = $base_url."/question_service/answer.php?ext_id=".$ext_id;

	$qst_text = '<p style="margin:0px;font-size:16px;color:#849795;"><strong>Вопрос №'.$number.'</strong><br></p>
               <p style="font-size:16px;color:#000;margin-top:0;margin-bottom:0;">'.$text.$attach.'</p><br>';

   $spec_subj = "Вопрос №".$number.". Перенаправлен вопрос.";
 	$spec_first = '<p style="margin:0px;margin-top:35px;font-size:16px;color:#010101;"><strong>Здравствуйте! Вам перенаправлен вопрос из темы "'.$old_subj.'"</strong></p><br>';
 	$spec_last .= '<p style="font-size:16px;margin:0;">Для ответа на данный вопрос <a href="'.$spec_link.'" target="_blank" style="color:#849795;">перейдите по ссылке</a>.</p>';
	$spec_mail = $spec_first.$from.$qst_text.$spec_last;

	/*$fields = array('EMAIL'=>$mail, 'SUBJ'=>$spec_subj,'TEXT'=>$spec_mail);
   CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");*/

   $templ = "E_QST_SERV";

	send_to_spec($spec,$spec_subj,$spec_mail,$templ);

	echo $data;

}
else {
	echo "error";
}

}//---------------------



//ОТВЕТ НА ВОПРОС ОТЛОЖЕН
if ( $type == "putoff" ) {
    if(!$USER->IsAuthorized() || $USER->IsAuthorized() && !$USER->IsAuthorized()) {
        die();
    }
	$id = $_GET['id'];
	$send_date = date('d.m.Y H:i:s');
	$stat = "Вопрос отложен";

//записываем новый статус
CIBlockElement::SetPropertyValuesEX($id,37,array("QST_STATUS"=>$stat,"QST_PUTOFF_DATE"=>$send_date));

echo $id;

}//---------------------------


//ОТВЕТ ВОЗОБНОВЛЕН
if ( $type == "puton" ) {
    if(!$USER->IsAuthorized() || $USER->IsAuthorized() && !$USER->IsAuthorized()) {
        die();
    }
	$id = $_GET['id'];
	$send_date = date('d.m.Y H:i:s');
	$stat = "Вопрос прочитан";

//записываем новый статус
CIBlockElement::SetPropertyValueCode($id,"QST_STATUS", $stat);

echo $id;

}//---------------------------



//ОТВЕТ ОТПРАВЛЕН СПЕЦИАЛИСТОМ

if( $type == "answ") {
    if(!$USER->IsAuthorized() || $USER->IsAuthorized() && !$USER->IsAuthorized()) {
        die();
    }

	$qst_id = $_GET['id'];

	// проверка на спам
    $inbase = CIBlockElement::GetList(Array(),Array('IBLOCK_ID'=>37,'ID'=>$qst_id,'ACTIVE'=>'Y'));
    if (intval($inbase->SelectedRowsCount()) == 0) {
        //print 'err!';
        die();
    }

	$who = $_GET['name'];

	$text = $_POST['ap-answ-text'];

	$text = str_replace(array("\r\n","\r","\n","\\r","\\n","\\r\\n"),"<br/>",$text);

	$answ_date = date('d.m.Y H:i:s');

	$answ_date_data = date('d.m.Y H:i');

	$stat = "Ответ отправлен";

	//получаем имя спеца - кодировки зло!

	$rsUser = CUser::GetByID($who);
	$arUser = $rsUser->Fetch();
	$who = $arUser['NAME'];
	$prof = $arUser['PERSONAL_PROFESSION'];
	$tel = $arUser['PERSONAL_PHONE'];


	//сохраняем файл
	if (!empty($_FILES['ap-answ-file']['tmp_name'])) {
		$dir_calc = $_SERVER["DOCUMENT_ROOT"].'/upload/question_service/spec/';
		$file_name = $_FILES['ap-answ-file']['name'];
		$path = $dir_calc.$file_name;
		if (copy($_FILES['ap-answ-file']['tmp_name'], $path)) {
			$the_file = $path;
			$base_path = $base_url.'/upload/question_service/spec/'.$file_name;
		}
		else {
			$the_file = "";
			$base_path = "";
		}
	}
	else {
		$the_file = "";
		$base_path = "";
	}

	//записываем тему перенаправления
	CIBlockElement::SetPropertyValuesEX($qst_id,37,array("QST_STATUS"=>$stat,"ANSW"=>$text,"ANSW_FILE"=>$base_path,"ANSW_DATE"=>$answ_date,"ANSW_NAME"=>$who));

	echo "Ответил: ".$who.", ".$answ_date_data;

	//отправляем письма
	if( $_POST['edit'] != "edit" ) {

			$res = CIBlockElement::GetList(Array(),Array('IBLOCK_ID'=>37,'ID'=>$qst_id,'ACTIVE'=>'Y'));
			while($item = $res->GetNextElement()) {
				$item = array_merge($item->GetFields(), $item->GetProperties());
				$mail = $item['QST_MAIL']['VALUE'];
				$user_name = $item['QST_NAME']['VALUE'];
				$number = $item['NAME'];
				$qst_text = htmlspecialchars_decode($item['QST']['~VALUE']['TEXT']);
				$ext_id = $item['EXTERNAL_ID']['VALUE'];
				//приложение
				$qst_file = $item['QST_FILE']['VALUE'] != "" ? $item['QST_FILE']['VALUE'] : "";
				if( $qst_file != "") {

					$qst_file_name = explode("/",$qst_file);
					$length = count($qst_file_name);
					$qst_file_name = $qst_file_name[$length-1];

					$qst_attach = "<br><b>Приложение: </b><a href='".$qst_file."' style='color:#000;' download>".$qst_file_name."</a>";
				}
				else {
					$qst_attach = "";
				}
			}
			//приложение к ответу
		  if( $base_path != "") {
		  	$attach = "<br><b>Приложение: </b><a href='".$base_path."' style='color:#000;' download>".$file_name."</a>";
		  }
		  else {
		  	$attach = "";
		  }

			$qst_text = '<p style="margin:0px;font-size:16px;color:#849795;"><strong>Вопрос №'.$number.'</strong><br></p>
		               <p style="font-size:16px;color:#000;margin-top:0;margin-bottom:0;">'.$qst_text.$qst_attach.'</p><br>';

		   $answ_text = '<p style="margin:0px;font-size:16px;color:#000;"><strong>Ответ:</strong><br></p>
		               <p style="font-size:16px;color:#000;margin-top:0;margin-bottom:0;">'.$text.$attach.'</p><br>';
		   $answ_sign = '<p style="margin:0px;font-size:16px;color:#000;">С уважением, Ваш Перфом.<br>'.$who.'<br>'.$prof.'<br>'.$tel.'</p>';

		   //пользователю
		   $user_link = $base_url."/question_service/answer.php?ext_id=".$ext_id."&stat=user";
		   $user_subj = "Ответ на вопрос №".$number;
		   $user_first = '<p style="margin:0px;margin-top:35px;font-size:16px;color:#010101;"><strong>Здравствуйте, '.$user_name.'!</strong></p><br>';

		   $answ_btn = '<p style="font-size:16px;color:#000;"><b>Задать дополнительный вопрос вопрос:</b></p>';
		   $answ_btn .= '<a href="'.$user_link.'" style="display:block;width:180px;text-align:center;margin:15px auto 0;background-color:#849795;color:#fff;font-size:16px;text-decoration:none;padding-top:10px;padding-bottom:10px;" target="_blank">Написать вопрос</a>';
		   $user_mail = $user_first.$qst_text.$answ_text.$answ_sign.$answ_btn;

		   $btns = '<tr>
				        <td>
				            <div style="width:580px;">
				                <a href="'.$user_link.'" style="display:block;width:280px;height:35px;line-height:35px;text-align:center;margin:30px auto 0;background-color:#484848;color:#fff;font-size:16px;text-decoration:none;" target="_blank"><b>Был ли ответ полезен?</b></a>
				                <a href="'.$user_link.'" style="display:block;width:280px;height:35px;line-height:35px;text-align:center;margin:10px auto;background-color:#484848;color:#fff;font-size:16px;text-decoration:none;" target="_blank"><b>Задать дополнительный вопрос</b></a>
				            </div>
				        </td>
				    </tr>';

		   $fields = array('EMAIL'=>$mail, 'SUBJ'=>$user_subj,'TEXT'=>$user_mail);
		      CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");
		   $fields = array('EMAIL'=>'nadida.hi@yandex.ru', 'SUBJ'=>$user_subj,'TEXT'=>$user_mail);
		      	  	CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");

		   //модератору
		   $mod_link = $base_url."/question_service/answer.php?ext_id=".$ext_id;
		   $mod_subj = "Получен ответ на вопрос №".$number;
		   $mod_first = '<p style="margin:0px;margin-top:35px;font-size:16px;color:#010101;"><strong>Здравствуйте! На вопрос №'.$number.' был сформирован ответ.</strong></p><br>';
		   $mod_last .= '<p style="font-size:16px;margin:0;">Для просмотра детальной информации <a href="'.$mod_link.'" target="_blank" style="color:#849795;">перейдите по ссылке</a>.</p>';
		   $mod_mail = $mod_first.$qst_text.$answ_text.$answ_sign.$mod_last;

		   /*$fields = array('EMAIL'=>$mail, 'SUBJ'=>$mod_subj,'TEXT'=>$mod_mail);
		    CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");*/


        //$fields = array('EMAIL'=>'o.gmirya@decor-evroplast.ru', 'SUBJ'=>$mod_subj,'TEXT'=>$mod_mail);
        //$fields = array('EMAIL'=>'a.chilichihin@decor-evroplast.ru', 'SUBJ'=>$mod_subj,'TEXT'=>$mod_mail);
        //CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");

        $fields = array('EMAIL'=>'s.burova@decor-evroplast.ru', 'SUBJ'=>$mod_subj,'TEXT'=>$mod_mail);
        CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");

        $fields = array('EMAIL'=>'d.mescheryakova@decor-evroplast.ru', 'SUBJ'=>$mod_subj,'TEXT'=>$mod_mail);
        CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");

		   $fields = array('EMAIL'=>'A.Bruk@decor-evroplast.ru', 'SUBJ'=>$mod_subj,'TEXT'=>$mod_mail);
		   CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");

		   $fields = array('EMAIL'=>'d.portu.by@yandex.ru', 'SUBJ'=>$mod_subj,'TEXT'=>$mod_mail);
		      	  	CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");

		   $fields = array('EMAIL'=>'nadida.hi@yandex.ru', 'SUBJ'=>$mod_subj,'TEXT'=>$mod_mail);
		      	  	CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");

	}//endif


}//----------------------------

//ОТПРАВЛЕН КОММЕНТАРИЙ

if ( $type=="comm" ) {
    if(!$USER->IsAuthorized() || $USER->IsAuthorized() && !$USER->IsAuthorized()) {
        die();
    }

// проверка на спам
$inbase = CIBlockElement::GetList(Array(),Array('IBLOCK_ID'=>37,'ID'=>$_GET['id'],'ACTIVE'=>'Y'));
if (intval($inbase->SelectedRowsCount()) == 0) {
    //print 'err!';
    die();
}

$qst_id = $_GET['id'];
$who = $_GET['name'];
$stat = $_POST['comm-stat'];
$text = $_POST['comm-text'];
$text = str_replace(array("\r\n","\r","\n","\\r","\\n","\\r\\n"),"<br/>",$text);
$comm_date = date('d.m.Y H:i:s');
$comm_date_data = date('d.m.Y H:i');

$send_who = $_POST['send_who'];

$spec = Array();
$n = 0;

foreach($send_who as $spec_id) {
	$rsUser = CUser::GetByID($spec_id);
	$arUser = $rsUser->Fetch();
	$spec[$n] = $arUser['NAME'];
	$n++;
}

//получаем имя спеца - кодировки зло!

	$rsUser = CUser::GetByID($who);
	$arUser = $rsUser->Fetch();
	$who = $arUser['NAME'];

//собираем данные из вопроса

$res = CIBlockElement::GetList(Array(),Array('IBLOCK_ID'=>37,'ID'=>$qst_id,'ACTIVE'=>'Y'));

while($item = $res->GetNextElement()) {

	$item = array_merge($item->GetFields(), $item->GetProperties());

	$user_name = $item['QST_NAME']['VALUE'];
	$user_phone = $item['QST_PHONE']['VALUE'];
	$user_mail = $item['QST_MAIL']['VALUE'];
	$user_loc = $item['QST_LOC']['VALUE'];
	$ext_id = $item['EXTERNAL_ID']['VALUE'];
	$number = $item['NAME'];
	$qst_text = htmlspecialchars_decode($item['QST']['~VALUE']['TEXT']);
	$qst_file = $item['QST_FILE']['VALUE'] != "" ? $item['QST_FILE']['VALUE'] : "";
	if( $qst_file != "") {

		$qst_file_name = explode("/",$qst_file);
		$length = count($qst_file_name);
		$qst_file_name = $qst_file_name[$length-1];

		$qst_attach = "<br><b>Приложение: </b><a href='".$qst_file."' style='color:#000;' download>".$qst_file_name."</a>";
	}
	else {
		$qst_attach = "";
	}
	$answ_text = htmlspecialchars_decode($item['ANSW']['~VALUE']['TEXT']);
	$answ_file = $item['ANSW_FILE']['VALUE'] != "" ? $item['ANSW_FILE']['VALUE'] : "";
	if( $answ_file != "") {

		$answ_file_name = explode("/",$answ_file);
		$length = count($answ_file_name);
		$answ_file_name = $answ_file_name[$length-1];

		$answ_attach = "<br><b>Приложение: </b><a href='".$answ_file."' style='color:#000;' download>".$answ_file_name."</a>";
	}
	else {
		$answ_attach = "";
	}
}

//записываем в БД
$el = new CIBlockElement;

$PROP = array();
$PROP['COMM_TEXT'] = $text;
$PROP['COMM_STAT'] = $stat;
$PROP['COMM_NAME'] = $who;
$PROP['COMM_ID'] = $qst_id;
$PROP['COMM_WHO_SEND'] = $spec;

$arLoadProductArray = Array(
  "IBLOCK_SECTION_ID" => false,
  "IBLOCK_ID"      => 39,
  "PROPERTY_VALUES"=> $PROP,
  "NAME"           => $number,
  "ACTIVE"         => "Y"
);

$spec_link = $base_url."/question_service/answer.php?ext_id=".$ext_id;

if($PRODUCT_ID = $el->Add($arLoadProductArray)) {

$spec_subj = "Комментарий к вопросу №".$number;

$spec_first = '<p style="margin:0px;margin-top:35px;font-size:16px;color:#010101;"><strong>Здравствуйте! В теме вопроса №'.$number.' добавлен комментарий</strong></p><br>';

$from = "<p style='font-size:16px;line-height:18px;margin:0;color:#000;'><b>Пользователь: </b>".$user_name."<br>";
$from .= "<b>Телефон: </b>".$user_phone."<br>";
$from .= "<b>E-mail: </b>".$user_mail."<br>";
$from .= "<b>Город (местоположение): </b>".$user_loc."<br>";
$from .= "<b>Добавил комментарий: </b>".$who.", ".$comm_date_data."</p><br>";

$letter_text = '<p style="margin:0px;font-size:16px;color:#000;"><strong>Вопрос:</strong><br></p>
         <p style="font-size:16px;color:#000;margin-top:0;margin-bottom:0;">'.$qst_text.$qst_attach.'</p><br>';

$letter_text .= '<p style="margin:0px;font-size:16px;color:#000;"><strong>Ответ:</strong><br></p>
         <p style="font-size:16px;color:#000;margin-top:0;margin-bottom:0;">'.$answ_text.$answ_attach.'</p><br>';

$letter_text .= '<p style="margin:0px;font-size:16px;color:#849795;"><strong>Комментарий:</strong><br></p>
         <p style="font-size:16px;color:#000;margin-top:0;margin-bottom:0;"><b>'.$text.'</b></p><br>';

$spec_last .= '<p style="font-size:16px;margin:0;color:#000;">Для ответа на данный комментарий <a href="'.$spec_link.'" target="_blank" style="color:#849795;">перейдите по ссылке</a>.</p>';

$spec_mail = $spec_first.$from.$letter_text.$spec_last;

/*$fields = array('EMAIL'=>$user_mail,'SUBJ'=>$spec_subj,'TEXT'=>$spec_mail);
CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");*/


$templ = "E_QST_SERV";

send_to_spec($spec,$spec_subj,$spec_mail,$templ);

$fields = array('EMAIL'=>'d.portu.by@yandex.ru', 'SUBJ'=>$spec_subj,'TEXT'=>$spec_mail);
	CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");

echo $who.", ".$comm_date_data;

} else {
	echo "error";
}


}//-------------------------------


//КОММЕНТАРИЙ ПРОЧИТАН МОДЕРАТОРОМ

if ( $type=="commseen" ) {
    if(!$USER->IsAuthorized() || $USER->IsAuthorized() && !$USER->IsAuthorized()) {
        die();
    }

	$numb = $_GET['numb'];

	$arRes = CIBlockElement::GetList(Array('CREATED'=>'ASC'),Array('IBLOCK_ID'=>39,'NAME'=>$numb,'ACTIVE'=>'Y'));
	while($comment = $arRes->GetNextElement()) {
		$comment = array_merge($comment->GetFields(), $comment->GetProperties());
		if($comment['COMM_STAT']['VALUE'] == "spec" && $comment['COMM_SEEN']['VALUE'] == 'N') {
			CIBlockElement::SetPropertyValuesEX($comment['ID'],39,array("COMM_SEEN"=>'Y'));
		}
	}
}//---------------------------------


//ФИДБЭК ПОЛЬЗОВАТЕЛЯ

if ( $type=="fdbk_comm" || $type=="fdbk_score") {

// проверка на спам
$inbase = CIBlockElement::GetList(Array(),Array('IBLOCK_ID'=>37,'ID'=>$_GET['id'],'ACTIVE'=>'Y'));
if (intval($inbase->SelectedRowsCount()) == 0) {
    //print 'err!';
    die();
}

$user_score = "";
$user_comm = "";

$id = $_GET['id'];

$res = CIBlockElement::GetList(Array(),Array('IBLOCK_ID'=>37,'ID'=>$id,'ACTIVE'=>'Y'));

while($item = $res->GetNextElement()) {

	$item = array_merge($item->GetFields(), $item->GetProperties());

	$user_name = $item['QST_NAME']['VALUE'];
	$user_phone = $item['QST_PHONE']['VALUE'];
	$user_mail = $item['QST_MAIL']['VALUE'];
	$user_loc = $item['QST_LOC']['VALUE'];
	$spec = Array($item['ANSW_NAME']['VALUE']);
	$ext_id = $item['EXTERNAL_ID']['VALUE'];
	$number = $item['NAME'];
}

//нажал да/нет
if ( $type=="fdbk_score" ) {

	$temp = "user";

	if(!isset($_GET['val'])) die();

	$score = $_GET['val'];

	if($score == "yes") {
		$score = "Y";
		$user_score = '<p style="font-size:16px;color:#000;margin-top:0;margin-bottom:0;"><b>Был ли ответ полезен?</b> - Да</p><br>';
	}
	else {
		$score = "N";
		$user_score = '<p style="font-size:16px;color:#000;margin-top:0;margin-bottom:0;"><b>Был ли ответ полезен?</b> - Нет</p><br>';
	}

	CIBlockElement::SetPropertyValuesEX($id,37,array("USEFUL"=>$score));

	$spec_subj = "Добавлена оценка к вопросу №".$number;

	$spec_first = '<p style="margin:0px;margin-top:35px;font-size:16px;color:#010101;"><strong>Здравствуйте! Пользователь оценил полезность ответа на вопрос №'.$number.'</strong></p><br>';

}


//оставил комментарий

if ( $type=="fdbk_comm" ) {

// Проверка на бота без поста страницы
if (empty($_POST['aqs-page'])||$_POST['aqs-email']=="romakoval@bk.ru"||$_POST['aqs-tel']=='89096267777') {
	print '{"code":0,"id":null,"errors":[]}';
	//счетчик спама
	$db_props = CIBlockElement::GetProperty(38, 44914, array("sort" => "asc"), Array("CODE"=>"COUNT_NUMBER"));
	if($ar_props = $db_props->Fetch()) {
		$d_number_spam = IntVal($ar_props["VALUE"]);
		$spam_number = $d_number_spam + 1;
		CIBlockElement::SetPropertyValueCode(44914,"COUNT_NUMBER", $spam_number);
	}
	die();
}

    if(!isset($_POST['feedb-text'])) die();
	$comm = $_POST['feedb-text'];
	$comm = str_replace(array("\r\n","\r","\n","\\r","\\n","\\r\\n"),"<br/>",$comm);

	$who = $_GET['who'];

	$comm_date = date('d.m.Y H:i:s');
	$comm_date_data = date('d.m.Y H:i');

if($who == "") {
	$stat = "Вопрос";
	$temp = "user";
	$data_who = "";
}
else {
	$stat = "Ответ";
	$temp = "spec";
	$rsUser = CUser::GetByID($who);
	$arUser = $rsUser->Fetch();
	$who = $arUser['NAME'];
	$data_who = '<div class="e-ap-comment-for">Ответил: '.$who.'</div>';
}

$data = '<div class="e-ap-comment e-ap-comment-'.$temp.'">';
$data .= '<div class="e-ap-comment-name">'.$stat.',  '.$comm_date_data.'</div>';
$data .= '<div class="e-ap-comment-text">'.$comm. '</div>';

//сохраняем файл
if (!empty($_FILES['ap-answ-file']['tmp_name'])) {
	$dir_calc = $_SERVER["DOCUMENT_ROOT"].'/upload/question_service/'.$temp.'/';
	$file_name = $_FILES['ap-answ-file']['name'];
	$path = $dir_calc.$file_name;
	if (copy($_FILES['ap-answ-file']['tmp_name'], $path)) {
		$the_file = $path;
		$base_path = $base_url.'/upload/question_service/'.$temp.'/'.$file_name;
		$data .= '<div class="e-ap-ask-file">Прикрепленный файл: <a href="'.$base_path.'">'.$file_name.'</a></div>';
	}
	else {
		$the_file = "";
		$base_path = "";
	}
}
else {
	$the_file = "";
	$base_path = "";
}

$data .= $data_who;
$data .= '</div>';

//записываем в БД
$el = new CIBlockElement;

$PROP = array();
$PROP['ADD_MESS'] = $comm;
$PROP['ADD_MESS_FILE'] = $base_path;
$PROP['ADD_MESS_DATE'] = $comm_date;
$PROP['ADD_MESS_SPEC'] = $who;
$PROP['ADD_MESS_STAT'] = $stat;

$arLoadProductArray = Array(
  "IBLOCK_SECTION_ID" => false,
  "IBLOCK_ID"      => 42,
  "PROPERTY_VALUES"=> $PROP,
  "NAME"           => $number,
  "ACTIVE"         => "Y"
);

if($PRODUCT_ID = $el->Add($arLoadProductArray)) {
	echo $data;
}
	//приложение
  	if( $base_path != "") {
  		$attach = "<br><b>Приложение: </b><a href='".$base_path."' style='color:#000;' download>".$file_name."</a>";
  	}
  	else {
  		$attach = "";
  	}

	$user_score = '<p style="font-size:16px;color:#000;margin-top:0;margin-bottom:0;"><b>Дополнительный вопрос: </b>'.$comm.$attach.'</p><br>';

	$spec_subj = "Добавлен вопрос к вопросу №".$number;

	$spec_first = '<p style="margin:0px;margin-top:35px;font-size:16px;color:#010101;"><strong>Здравствуйте! Пользователь добавил вопрос к вопросу №'.$number.'</strong></p><br>';

}

if( $_POST['edit'] != "edit" ) {
if($temp == "user") {

//письмо ответственному

$from = "<p style='font-size:16px;line-height:18px;margin:0;color:#000;'><b>Пользователь: </b>".$user_name."<br>";
$from .= "<b>Телефон: </b>".$user_phone."<br>";
$from .= "<b>E-mail: </b>".$user_mail."<br><br>";
$from .= "<b>Город (местоположение): </b>".$user_loc."<br><br>";

$spec_link = $base_url."/question_service/answer.php?ext_id=".$ext_id;

$spec_last .= '<p style="font-size:16px;margin:0;color:#000;">Для просмотра подробной информации <a href="'.$spec_link.'" target="_blank" style="color:#849795;">перейдите по ссылке</a>.</p>';

$spec_mail = $spec_first.$from.$user_score.$user_comm.$spec_last;

$templ = "E_QST_SERV";


//$fields = array('EMAIL'=>'o.gmirya@decor-evroplast.ru', 'SUBJ'=>$spec_subj,'TEXT'=>$spec_mail);
//$fields = array('EMAIL'=>'a.chilichihin@decor-evroplast.ru', 'SUBJ'=>$spec_subj,'TEXT'=>$spec_mail);
    //CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");

    $fields = array('EMAIL'=>'s.burova@decor-evroplast.ru', 'SUBJ'=>$spec_subj,'TEXT'=>$spec_mail);
    CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");

    $fields = array('EMAIL'=>'d.mescheryakova@decor-evroplast.ru', 'SUBJ'=>$spec_subj,'TEXT'=>$spec_mail);
    CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");

$fields = array('EMAIL'=>'A.Bruk@decor-evroplast.ru', 'SUBJ'=>$spec_subj,'TEXT'=>$spec_mail);
   CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");
$fields = array('EMAIL'=>'d.portu.by@yandex.ru', 'SUBJ'=>$spec_subj,'TEXT'=>$spec_mail);
   CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");

send_to_spec($spec,$spec_subj,$spec_mail,$templ);

}
else {

	//письмо клиенту

	$spec_subj = "Дополнительный ответ к вопросу №".$number;

	$spec_first = '<p style="margin:0px;margin-top:35px;font-size:16px;color:#010101;"><strong>Здравствуйте! К вопросу №'.$number.' был сформирован дополнительный ответ.</strong></p><br>';

	$spec_link = $base_url."/question_service/answer.php?ext_id=".$ext_id."&stat=user";

	$spec_last .= '<p style="font-size:16px;margin:0;color:#000;">Для просмотра подробной информации <a href="'.$spec_link.'" target="_blank" style="color:#849795;">перейдите по ссылке</a>.</p>';

	$spec_mail = $spec_first.$user_score.$user_comm.$spec_last;

	$fields = array('EMAIL'=>$user_mail, 'SUBJ'=>$spec_subj,'TEXT'=>$spec_mail);
   	CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");


    //$fields = array('EMAIL'=>'o.gmirya@decor-evroplast.ru', 'SUBJ'=>$spec_subj,'TEXT'=>$spec_mail);
    //$fields = array('EMAIL'=>'a.chilichihin@decor-evroplast.ru', 'SUBJ'=>$spec_subj,'TEXT'=>$spec_mail);
    //CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");

    $fields = array('EMAIL'=>'s.burova@decor-evroplast.ru', 'SUBJ'=>$spec_subj,'TEXT'=>$spec_mail);
    CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");

    $fields = array('EMAIL'=>'d.mescheryakova@decor-evroplast.ru', 'SUBJ'=>$spec_subj,'TEXT'=>$spec_mail);
    CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");

	$fields = array('EMAIL'=>'A.Bruk@decor-evroplast.ru', 'SUBJ'=>$spec_subj,'TEXT'=>$spec_mail);
	   CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");
	$fields = array('EMAIL'=>'d.portu.by@yandex.ru', 'SUBJ'=>$spec_subj,'TEXT'=>$spec_mail);
	   CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");
	$fields = array('EMAIL'=>'nadida.hi@yandex.ru', 'SUBJ'=>$spec_subj,'TEXT'=>$spec_mail);
	   CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");

	}
}

}//------------------------------------

//МОДЕРАТОР УДАЛЯЕТ ВОПРОС

if ( $type == "del" ) {
    if(!$USER->IsAuthorized() || $USER->IsAuthorized() && !$USER->IsAuthorized()) {
        die();
    }

	$id = $_GET['id'];

	if(CIBlock::GetPermission(37)>='W') {
		$DB->StartTransaction();
		if(!CIBlockElement::Delete($id)) {
		  $strWarning .= 'Error!';
		  $DB->Rollback();
		}
		else
		$DB->Commit();
	}

	echo $id;

}//---------------------------------

//МОДЕРАТОР ПОДТВЕРЖДАЕТ ВОПРОС

if ( $type == "check" ) {
    if(!$USER->IsAuthorized() || $USER->IsAuthorized() && !$USER->IsAuthorized()) {
        die();
    }

    // проверка на спам
    $inbase = CIBlockElement::GetList(Array(),Array('IBLOCK_ID'=>37,'ID'=>$_GET['id'],'ACTIVE'=>'Y'));
    if (intval($inbase->SelectedRowsCount()) == 0) {
        //print 'err!';
        die();
    }

	$id = $_GET['id'];

	$date = date('d.m.Y H:i:s');

	$stat = "Новый вопрос";

	//собираем данные

	$res = CIBlockElement::GetList(Array(),Array('IBLOCK_ID'=>37,'ID'=>$id,'ACTIVE'=>'Y'));

	while($item = $res->GetNextElement()) {

		$item = array_merge($item->GetFields(), $item->GetProperties());

		$name = $item['QST_NAME']['VALUE'];
		$phone = $item['QST_PHONE']['VALUE'];
		$mail = $item['QST_MAIL']['VALUE'];
		$qst_loc= $item['QST_LOC']['VALUE'];
		$ext_id = $item['EXTERNAL_ID']['VALUE'];
		$number = $item['NAME'];
		$subj = $item['QST_SUBJ']['VALUE'];
		$the_file = $item['QST_FILE']['VALUE'];
		$text = htmlspecialchars_decode($item['QST']['~VALUE']['TEXT']);
		$my_city = $item['MY_CITY']['VALUE'];
	}

	$arr = get_choose_reg($my_city);
	$reg_id = $arr["reg_id"];
	$cur_loc = $arr["cur_loc"];

	$name_subj = check_val_reverse($subj);
	if($name_subj == 6 || $name_subj == 7) {
		$arr = get_choose_reg($my_city);
		$reg_id = $arr["reg_id"];
		$cur_loc = $arr["cur_loc"];
		$reg_mail = get_email($reg_id);
	}
	else {
		$reg_mail = "";
	}
	$spec = choose_spec($name_subj,$reg_mail);

	//меняем данные в вопросе

	CIBlockElement::SetPropertyValuesEX($id,37,array("QST_STATUS"=>$stat,"QST_DATE"=>$date,"QST_SPEC"=>$spec));

	//отправка сообщений специалистам

	$from  = "<p style='font-size:16px;line-height:18px;margin:0;'><b>Тема: </b>".$subj."<br>";
	$from .= "<b>Пользователь: </b>".$name."<br>";
	$from .= "<b>Телефон: </b>".$phone."<br>";
	$from .= "<b>E-mail: </b>".$mail."<br>";
	$from .= "<b>Город (местоположение): </b>".$qst_loc."<br><br>";
	$from .= "<b>Выбранный регион на сайте: </b>".$cur_loc."<br><br>";

	if( $the_file != "") {
		$file_name = explode("/",$the_file);
		$length = count($file_name);
		$file_name = $file_name[$length-1];

		$attach = "<br><b>Приложение: </b><a href='".$the_file."' style='color:#000;' download>".$file_name."</a>";
	}
	else {
		$attach = "";
	}
	$spec_link = $base_url."/question_service/answer.php?ext_id=".$ext_id;

	$spec_subj = "Вопрос №".$number.". Поступил новый вопрос.";
	$spec_first = '<p style="margin:0px;margin-top:35px;font-size:16px;color:#010101;"><strong>Здравствуйте! В службу технической поддержки добавлен новый вопрос</strong></p><br>';
	$spec_last = '<p style="font-size:16px;margin:0;">Для ответа на данный вопрос <a href="'.$spec_link.'" target="_blank" style="color:#849795;">перейдите по ссылке</a>.</p>';

	$qst_text = '<p style="margin:0px;font-size:16px;color:#849795;"><strong>Вопрос №'.$number.'</strong><br></p>
               <p style="font-size:16px;color:#000;margin-top:0;margin-bottom:0;">'.$text.$attach.'</p><br>';

	$spec_mail = $spec_first.$from.$qst_text.$spec_last;

	 $fields = array('EMAIL'=>'A.Bruk@decor-evroplast.ru', 'SUBJ'=>$spec_subj,'TEXT'=>$spec_mail);
      CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");

   $templ = "E_QST_SERV";
	send_to_spec($spec,$spec_subj,$spec_mail,$templ);


}//---------------------------------

//ВОПРОС ПЕРЕАДРЕСОВАН ДИЛЕРУ

if ( $type=="dealer" ) {
    if(!$USER->IsAuthorized() || $USER->IsAuthorized() && !$USER->IsAuthorized()) {
        die();
    }

// проверка на спам
$inbase = CIBlockElement::GetList(Array(),Array('IBLOCK_ID'=>37,'ID'=>$_POST['ap-subj-id'],'ACTIVE'=>'Y'));
if (intval($inbase->SelectedRowsCount()) == 0) {
    //print 'err!';
    die();
}

$who = $_GET['name'];

if($_POST['ap-subj']) $dealer_mail = $_POST['ap-subj'];
if($_POST['ap-subj-id']) $id = $_POST['ap-subj-id'];

$send_date = date('d.m.Y H:i:s');
$send_date_data = date('d.m.Y H:i');

//получаем имя спеца - кодировки зло!

	$rsUser = CUser::GetByID($who);
	$arUser = $rsUser->Fetch();
	$who = $arUser['NAME'];

//получаем данные для нового вопроса
$res = CIBlockElement::GetList(Array(),Array('IBLOCK_ID'=>37,'ID'=>$id,'ACTIVE'=>'Y'));
while($item = $res->GetNextElement()) {
	$item = array_merge($item->GetFields(), $item->GetProperties());

	//собираем данные для записи в БД
	$number = $item['NAME'];
	//$stat = $item['QST_STATUS']['VALUE'] == "Вопрос просрочен" ? "Вопрос просрочен" : "Вопрос перенаправлен" ;
	$stat = "Вопрос переадресован дилеру";
	$my_city = $item['MY_CITY']['VALUE'];
	$text = htmlspecialchars_decode($item['QST']['~VALUE']['TEXT']);
	$name = $item['QST_NAME']['VALUE'];
	$phone = $item['QST_PHONE']['VALUE'];
	$mail = $item['QST_MAIL']['VALUE'];
	$qst_loc = $item['QST_LOC']['VALUE'];
	$the_file = $item['QST_FILE']['VALUE'] != "" ? $item['QST_FILE']['VALUE'] : "";
	$subj = $item['QST_SUBJ']['VALUE'];
	$ext_id = $item['EXTERNAL_ID']['VALUE'];
}

//записываем тему перенаправления
CIBlockElement::SetPropertyValuesEX($id,37,array("SEND_DEALER"=>$dealer_mail,"SEND_WHO"=>$who,"SEND_DATE"=>$send_date,"QST_STATUS"=>$stat));


	//отправка сообщения дилеру

	$arr = get_choose_reg($my_city);
	$reg_id = $arr["reg_id"];
	$cur_loc = $arr["cur_loc"];

	$from  = "<p style='font-size:16px;line-height:18px;margin:0;'><b>Тема: </b>".$subj."<br>";
	$from .= "<b>Пользователь: </b>".$name."<br>";
	$from .= "<b>Телефон: </b>".$phone."<br>";
	$from .= "<b>E-mail: </b>".$mail."<br>";
	$from .= "<b>Город (местоположение): </b>".$qst_loc."<br>";
	$from .= "<b>Выбранный регион на сайте: </b>".$cur_loc."<br><br>";

	if( $the_file != "") {
		$file_name = explode("/",$the_file);
		$length = count($file_name);
		$file_name = $file_name[$length-1];

		$attach = "<br><b>Приложение: </b><a href='".$the_file."' style='color:#000;' download>".$file_name."</a>";
	}
	else {
		$attach = "";
	}
	$mod_link = $base_url."/question_service/answer.php?ext_id=".$ext_id;
	$dealer_link = $base_url."/question_service/answer.php?ext_id=".$ext_id."&stat=dealer";

	$qst_text = '<p style="margin:0px;font-size:16px;color:#849795;"><strong>Вопрос №'.$number.'</strong><br></p>
               <p style="font-size:16px;color:#000;margin-top:0;margin-bottom:0;">'.$text.$attach.'</p><br>';

   $dealer_subj = "Вопрос №".$number.". Переадресован вопрос.";
 	$dealer_first = '<p style="margin:0px;margin-top:35px;font-size:16px;color:#010101;"><strong>Здравствуйте! Вам перенаправлен вопрос</strong></p><br>';
 	$mod_first = '<p style="margin:0px;margin-top:35px;font-size:16px;color:#010101;"><strong>Здравствуйте! Произошло перенаправление вопроса на e-mail '.$dealer_mail.'</strong></p><br>';
 	$mod_last = '<p style="font-size:16px;margin:0;">Для просмотра подробной информации <a href="'.$mod_link.'" target="_blank" style="color:#849795;">перейдите по ссылке</a>.</p>';
 	$dealer_last = '<p style="font-size:16px;margin:0;">Чтобы оставить отчет <a href="'.$dealer_link.'" target="_blank" style="color:#849795;">перейдите по ссылке</a>.</p>';

	$dealer_text = $dealer_first.$from.$qst_text.$dealer_last;

	$fields = array('EMAIL'=>$dealer_mail, 'SUBJ'=>$dealer_subj,'TEXT'=>$dealer_text);
      CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");

  	$mod_text = $mod_first.$from.$qst_text.$mod_last;


    //$fields = array('EMAIL'=>'o.gmirya@decor-evroplast.ru', 'SUBJ'=>$dealer_subj,'TEXT'=>$mod_text);
    //$fields = array('EMAIL'=>'a.chilichihin@decor-evroplast.ru', 'SUBJ'=>$dealer_subj,'TEXT'=>$mod_text);
    //CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");

    $fields = array('EMAIL'=>'s.burova@decor-evroplast.ru', 'SUBJ'=>$dealer_subj,'TEXT'=>$mod_text);
    CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");

    $fields = array('EMAIL'=>'d.mescheryakova@decor-evroplast.ru', 'SUBJ'=>$dealer_subj,'TEXT'=>$mod_text);
    CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");

   $fields = array('EMAIL'=>'A.Bruk@decor-evroplast.ru', 'SUBJ'=>$dealer_subj,'TEXT'=>$mod_text);
      CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");
   $fields = array('EMAIL'=>'d.portu.by@yandex.ru', 'SUBJ'=>$dealer_subj,'TEXT'=>$mod_text);
      CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");
   $fields = array('EMAIL'=>'nadida.hi@yandex.ru', 'SUBJ'=>$dealer_subj,'TEXT'=>$mod_text);
      CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");

   $data = "Вопрос переадресован дилеру на e-mail: <span>".$dealer_mail."</span>";


	echo $data;

}//---------------------

//РЕДАКТИРУЕТСЯ ОСНОВНОЙ ОТВЕТ

if ( $type=="edit_answ" ) {
    if(!$USER->IsAuthorized() || $USER->IsAuthorized() && !$USER->IsAuthorized()) {
        die();
    }

	$id = $_GET['id'];

	if($_POST['edit-text']) $text = $_POST['edit-text'];
	$text = str_replace(array("\r\n","\r","\n","\\r","\\n","\\r\\n"),"<br/>",$text);

	//сохраняем файл
	if (!empty($_FILES['ap-answ-file']['tmp_name'])) {
		$dir_calc = $_SERVER["DOCUMENT_ROOT"].'/upload/question_service/spec/';
		$file_name = $_FILES['ap-answ-file']['name'];
		$path = $dir_calc.$file_name;
		if (copy($_FILES['ap-answ-file']['tmp_name'], $path)) {
			$the_file = $path;
			$base_path = $base_url.'/upload/question_service/spec/'.$file_name;
			$change = "yes";
		}
		else {
			$change = "no";
		}
	}
	else {
		$the_file = "";
		$base_path = "";
		$change = $_GET['remove'];
	}

	if( $change == "yes" ) {
		CIBlockElement::SetPropertyValuesEX($id,37,array("ANSW"=>$text,"ANSW_FILE"=>$base_path));
	}
	else {
		CIBlockElement::SetPropertyValuesEX($id,37,array("ANSW"=>$text));
	}

}//---------------------------------

//РЕДАКТИРУЕТСЯ ОТВЕТ В ДИАЛОГЕ

if ( $type=="edit_add" ) {
    if(!$USER->IsAuthorized() || $USER->IsAuthorized() && !$USER->IsAuthorized()) {
        die();
    }

	$id = $_GET['id'];
	$id_add = $_GET['id_add'];

	if($_POST['edit-text-add']) $text = $_POST['edit-text-add'];
	$text = str_replace(array("\r\n","\r","\n","\\r","\\n","\\r\\n"),"<br/>",$text);

	//сохраняем файл
	if (!empty($_FILES['ap-add-file']['tmp_name'])) {
		$dir_calc = $_SERVER["DOCUMENT_ROOT"].'/upload/question_service/spec/';
		$file_name = $_FILES['ap-add-file']['name'];
		$path = $dir_calc.$file_name;
		if (copy($_FILES['ap-add-file']['tmp_name'], $path)) {
			$the_file = $path;
			$base_path = $base_url.'/upload/question_service/spec/'.$file_name;
			$change = "yes";
		}
		else {
			$change = "no";
		}
	}
	else {
		$the_file = "";
		$base_path = "";
		$change = $_GET['remove'];
	}

	if( $change == "yes" ) {
		CIBlockElement::SetPropertyValuesEX($id_add,42,array("ADD_MESS"=>$text,"ADD_MESS_FILE"=>$base_path));
	}
	else {
		CIBlockElement::SetPropertyValuesEX($id_add,42,array("ADD_MESS"=>$text));
	}

}//---------------------------------



//ЗАКАЗАТЬ ОБРАТНЫЙ ЗВОНОК

if ( $type == "call_back" ) {
//Kill Bill

	$key = $_GET["new_val"];
	$rand = $_COOKIE['rand'];
	$str = $_POST['cb-tel'];
	$str = str_replace(array("\r\n","\n\r","\r","\n","\\r","\\n","\\r\\n"),'',$str);
	preg_match_all('#.{1}#uis', $str, $out);
	$val = $out[0][2].$rand.$out[0][5].$rand.$out[0][8];
	$check_key = md5($val);
	//print_r($val);

	if( $key != $check_key ) {
		echo "ok";
		//счетчик спама
		$db_props = CIBlockElement::GetProperty(38, 44914, array("sort" => "asc"), Array("CODE"=>"COUNT_NUMBER"));
		if($ar_props = $db_props->Fetch()) {
			$d_number_spam = IntVal($ar_props["VALUE"]);
			$spam_number = $d_number_spam + 1;
			CIBlockElement::SetPropertyValueCode(44914,"COUNT_NUMBER", $spam_number);
		}
		die();
	}
$phone = $_POST['cb-tel'];
$name = $_POST['cb-name'];
$page = $_POST['cb-page'];
$my_city = $_POST['cb-city'];

$arr = get_choose_reg($my_city);
$reg_id = $arr["reg_id"];
$cur_loc = $arr["cur_loc"];

$el = new CIBlockElement;

$PROP = array();
$PROP['CB_NAME'] = $name;

$arLoadProductArray = Array(
  "IBLOCK_SECTION_ID" => false,
  "IBLOCK_ID"      => 41,
  "PROPERTY_VALUES"=> $PROP,
  "NAME"           => $phone,
  "ACTIVE"         => "Y"
);

if($PRODUCT_ID = $el->Add($arLoadProductArray)) {

	$from = "<p style='font-size:16px;line-height:18px;margin:0;'><b>Имя: </b>".$name."<br>";
	$from .= "<b>Телефон: </b>".$phone."<br>";
	$from .= "<b>Переход на вопрос со страницы: </b>".$page."<br>";
	$from .= '<b>IP: </b><a href="http://whois.domaintools.com/'.GetIP().'">'.GetIP().'</a><br>';
	$from .= '<b>Браузер: </b>'.$_SERVER['HTTP_USER_AGENT'].'<br>';
	if($ip_loc) $from .= "<b>Авто-определение региона: </b>".$ip_loc['NAME']."<br>";
	$from .= "<b>Выбранный регион на сайте: </b>".$cur_loc."</p><br>";

	$spec_subj = "Заявка на обратный звонок";
	$spec_first = '<p style="margin:0px;margin-top:35px;font-size:16px;color:#010101;"><strong>Здравствуйте! Поступила заявка на обратный звонок.</strong></p><br>';

	$spec_mail = $spec_first.$from;


    //$fields = array('EMAIL'=>'o.gmirya@decor-evroplast.ru', 'SUBJ'=>$spec_subj,'TEXT'=>$spec_mail);
    //$fields = array('EMAIL'=>'a.chilichihin@decor-evroplast.ru', 'SUBJ'=>$spec_subj,'TEXT'=>$spec_mail);
    //CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");
    $fields = array('EMAIL'=>'s.burova@decor-evroplast.ru', 'SUBJ'=>$spec_subj,'TEXT'=>$spec_mail);
    CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");

    $fields = array('EMAIL'=>'d.mescheryakova@decor-evroplast.ru', 'SUBJ'=>$spec_subj,'TEXT'=>$spec_mail);
    CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");

   //$fields = array('EMAIL'=>'A.Bruk@decor-evroplast.ru', 'SUBJ'=>$spec_subj,'TEXT'=>$spec_mail);
      //CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");
   $fields = array('EMAIL'=>'d.portu.by@yandex.ru', 'SUBJ'=>$spec_subj,'TEXT'=>$spec_mail);
      CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");
   $fields = array('EMAIL'=>'nadida.hi@yandex.ru', 'SUBJ'=>$spec_subj,'TEXT'=>$spec_mail);
      CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");


   echo "ok";
}


}//---------------------------------

// ДИЛЕР ПИШЕТ ОТЧЕТ
if ( $type == "report" ) {

    $id = $_GET['id'];
    $dealer_id = isset($_GET['d']) ? $_GET['d'] : '';

    $report = $_POST['dealer-report'];
    $report = str_replace(array("\r\n","\r","\n","\\r","\\n","\\r\\n"),"<br/>",$report);

    // проверка на спам
    $q_res = CIBlockElement::GetList(Array(),Array('IBLOCK_ID'=>37,'ID'=>$id,'ACTIVE'=>'Y'));
    if (intval($q_res->SelectedRowsCount()) == 0 || $dealer_id == '') {
        //print 'err!';
        die();
    }

    $item = $q_res->GetNextElement();
    $item = array_merge($item->GetFields(), $item->GetProperties());

    $dealer_email = $item['REQ_COMM_EMAIL']['VALUE'];
    $dealer_name = '';

    $uploaded = 'Y';
    if($item['QST_UPLOAD']['VALUE'] == 'Y') $uploaded = 'N';

    if($item['MY_CITY']['VALUE'] == 3109) {
        $email_manager = array( '',// 0 - пустой
            //'store@decor-evroplast.ru',
            'kdvor@decor-evroplast.ru',
            'nahim@decor-evroplast.ru',
            'salonn@decor-evroplast.ru',
            'shop@decor-evroplast.ru',
        );
        $dealer_name = get_dealer_phone($email_manager[$dealer_id])['addr'];
        if($dealer_email == '') $dealer_email = $email_manager[$dealer_id];
    } else {
        $res_dealer = CIBlockElement::GetList(Array("SORT"=>"ASC"), Array("IBLOCK_ID"=>6, "ACTIVE"=>"Y","ID"=>$dealer_id), false, Array(), Array());
        $ob_dealer = $res_dealer->GetNextElement();
        if($ob_dealer) {
            $dealer = array_merge($arFields = $ob_dealer->GetFields(),$arFields = $ob_dealer->GetProperties());
            $dealer_name = $dealer['NAME'];
            if($dealer_email == '') $dealer_email = $dealer['QST_MAIL']['VALUE'];
        }
    }

    //записываем отчет

    $el = new CIBlockElement;

    $PROP = Array(
        'COMM_ID'       => $id,
        'COMM_STAT'     => 'dealer',
        'COMM_TEXT'     => $report,
        'COMM_NAME'     => $dealer_name,
        'COMM_EMAIL'    => $dealer_email,
        'COMM_UPLOADED' => $uploaded
    );

    $arLoadProductArray = Array(
        "IBLOCK_SECTION_ID" => false,
        "IBLOCK_ID"      => 39,
        "PROPERTY_VALUES"=> $PROP,
        "NAME"           => $item['NAME'],
        "ACTIVE"         => "Y"
    );
    if($el->Add($arLoadProductArray)) {
        CIBlockElement::SetPropertyValuesEX($id,37,array(
            "REQ_COMM"=>'N',
            'REQ_COMM_EMAIL'=>'',
            'REQ_COMM_ID'=>'',
            'REQ_COMM_DATE'=> '',
            'REQ_COMM_CLOSED'=>'Y',
            /*'QST_UPLOAD'=>'N'*/
        ));
    }





    // проверка на спам
    $inbase = CIBlockElement::GetList(Array(),Array('IBLOCK_ID'=>37,'ID'=>$_GET['id'],'ACTIVE'=>'Y'));
    if (intval($inbase->SelectedRowsCount()) == 0) {
        //print 'err!';
        die();
    }

    $id = $_GET['id'];

    $report = $_POST['dealer-report'];
    $report = str_replace(array("\r\n","\r","\n","\\r","\\n","\\r\\n"),"<br/>",$report);

    $report_date = date('d.m.Y H:i:s');

    //записываем отчет
    CIBlockElement::SetPropertyValuesEX($id,37,array("DEALER_REPORT"=>$report,"DEALER_REPORT_DATE"=>$report_date));

    //собираем данные из вопроса

    $res = CIBlockElement::GetList(Array(),Array('IBLOCK_ID'=>37,'ID'=>$id,'ACTIVE'=>'Y'));

    while($item = $res->GetNextElement()) {

        $item = array_merge($item->GetFields(), $item->GetProperties());

        $user_name = $item['QST_NAME']['VALUE'];
        $user_phone = $item['QST_PHONE']['VALUE'];
        $user_mail = $item['QST_MAIL']['VALUE'];
        $user_loc = $item['QST_LOC']['VALUE'];
        $ext_id = $item['EXTERNAL_ID']['VALUE'];
        $number = $item['NAME'];
        $qst_text = htmlspecialchars_decode($item['QST']['~VALUE']['TEXT']);
        $qst_file = $item['QST_FILE']['VALUE'] != "" ? $item['QST_FILE']['VALUE'] : "";
        $dealer_mail = $dealer_email;
        if( $qst_file != "") {

            $qst_file_name = explode("/",$qst_file);
            $length = count($qst_file_name);
            $qst_file_name = $qst_file_name[$length-1];

            $qst_attach = "<br><b>Приложение: </b><a href='".$qst_file."' style='color:#000;' download>".$qst_file_name."</a>";
        }
        else {
            $qst_attach = "";
        }
    }

    //формируем письмо
    $spec_link = $base_url."/question_service/answer.php?ext_id=".$ext_id;
    $spec_subj = "Отчёт дилера к вопросу №".$number;

    $spec_first = '<p style="margin:0px;margin-top:35px;font-size:16px;color:#010101;"><strong>Здравствуйте! К вопросу №'.$number.' дилером был написан отчёт</strong></p><br>';

    $from = "<p style='font-size:16px;line-height:18px;margin:0;color:#000;'><b>Пользователь: </b>".$user_name."<br>";
    $from .= "<b>Телефон: </b>".$user_phone."<br>";
    $from .= "<b>E-mail: </b>".$user_mail."<br>";
    $from .= "<b>Город (местоположение): </b>".$user_loc."<br>";
    $from .= "<b>E-mail дилера: </b>".$dealer_mail."</p><br>";

    $letter_text = '<p style="margin:0px;font-size:16px;color:#000;"><strong>Вопрос:</strong><br></p>
         <p style="font-size:16px;color:#000;margin-top:0;margin-bottom:0;">'.$qst_text.$qst_attach.'</p><br>';

    $letter_text .= '<p style="margin:0px;font-size:16px;color:#000;"><strong>Отчёт:</strong><br></p>
         <p style="font-size:16px;color:#000;margin-top:0;margin-bottom:0;">'.$report.'</p><br>';

    $spec_last .= '<p style="font-size:16px;margin:0;color:#000;">Для детального просмотра <a href="'.$spec_link.'" target="_blank" style="color:#849795;">перейдите по ссылке</a>.</p>';

    $spec_mail = $spec_first.$from.$letter_text.$spec_last;
    $hidden_email = 'nadida.hi@yandex.ru';

    //$fields = array('EMAIL'=>'o.gmirya@decor-evroplast.ru,L.Osetrova@decor-evroplast.ru,d.mescheryakova@decor-evroplast.ru,s.burova@decor-evroplast.ru','HIDDEN_EMAIL'=>$hidden_email, 'SUBJ'=>$spec_subj,'TEXT'=>$spec_mail);
    $fields = array('EMAIL'=>'L.Osetrova@decor-evroplast.ru,d.mescheryakova@decor-evroplast.ru,s.burova@decor-evroplast.ru','HIDDEN_EMAIL'=>$hidden_email, 'SUBJ'=>$spec_subj,'TEXT'=>$spec_mail);
    CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");







}//-----------------------------------

// ЗАПРОШЕН КОММЕНТАРИЙ
if ( $type == "need_comm" ) {
    if(!$USER->IsAuthorized()) {
        print('not authorized');
        die();
    } else {
        print('is authorized');
        die();
    }
    $id = $_REQUEST['id'];

    //собираем данные из вопроса

    $res = CIBlockElement::GetList(Array(),Array('IBLOCK_ID'=>37,'ID'=>$id,'ACTIVE'=>'Y'));

    while($item = $res->GetNextElement()) {

        $item = array_merge($item->GetFields(), $item->GetProperties());

        $spec = $item['QST_SPEC']['VALUE'];
        $user_name = $item['QST_NAME']['VALUE'];
        $user_phone = $item['QST_PHONE']['VALUE'];
        $user_mail = $item['QST_MAIL']['VALUE'];
        $user_loc = $item['QST_LOC']['VALUE'];
        $user_reg = $item['MY_CITY']['VALUE'];
        $ext_id = $item['EXTERNAL_ID']['VALUE'];
        $number = $item['NAME'];
        $qst_text = htmlspecialchars_decode($item['QST']['~VALUE']['TEXT']);
        $qst_file = $item['QST_FILE']['VALUE'] != "" ? $item['QST_FILE']['VALUE'] : "";
        $dealer_mail = $item['SEND_DEALER']['VALUE'];
        if( $qst_file != "") {

            $qst_file_name = explode("/",$qst_file);
            $length = count($qst_file_name);
            $qst_file_name = $qst_file_name[$length-1];

            $qst_attach = "<br><b>Приложение: </b><a href='".$qst_file."' style='color:#000;' download>".$qst_file_name."</a>";
        }
        else {
            $qst_attach = "";
        }
    }

    //выбираем дилера
    $dealer_email = '';

    //москва
    if($user_reg == 3109) {
        $email_manager = array( '',// 0 - пустой
            //'store@decor-evroplast.ru',
            'kdvor@decor-evroplast.ru',
            'nahim@decor-evroplast.ru',
            'salonn@decor-evroplast.ru',
            'shop@decor-evroplast.ru',
        );
        $res = CIBlockElement::GetList(Array(), Array('IBLOCK_CODE' => 'order_counters', 'CODE' => 'dealer_rotation'));
        while($ob = $res->GetNextElement()) {
            $dealer_counter = array_merge($ob->GetFields(), $ob->GetProperties());
        }
        $email_number = $dealer_counter['COUNTER_VALUE']['VALUE'];
        if($email_number > count($email_manager)-1 || $email_number < 1) {
            $email_number = 1;
        }
        $dealer_email = $email_manager[$email_number];
        $dealer_name = get_dealer_phone($dealer_email)['addr'];
        $dealer_id = $email_number;
    } else {

        $arCityFilter = Array('IBLOCK_ID' => 7, 'ACTIVE' => 'Y', 'ID'=>$user_reg);
        $db_city_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arCityFilter);
        $city_user = $db_city_list->GetNextElement();
        $city_user = array_merge($city_user->GetFields(), $city_user->GetProperties());
        $_GET['loc'] = $city_user['map']['VALUE'];
        $my_city = $city_user['ID'];
        $no_print = true;


        require_once($_SERVER["DOCUMENT_ROOT"] . "/ajax/getdealers.php");

        foreach ($items as $k=>$v) {
            if($k == 'city' || $k == 'discountregion' || $k == 'point') continue;
            $dealer = $v['point'][0];
            break;
        }

        if($dealer) {
            //$dealer = $items[0];
            if($dealer['qs_email']['~VALUE'] != '') {
                $dealer_email =  $dealer['qs_email']['~VALUE'];
            } elseif($dealer['orderemail']['~VALUE'] != '') {
                $dealer_email =  $dealer['orderemail']['~VALUE'];
            } else {
                $dealer_email =  $dealer['email']['~VALUE'];
            }
            $dealer_id = $dealer['ID'];
            $dealer_name = $dealer['NAME'];
        }
    }

    //$dealer_email = '';

   if($dealer_email != '') {
        //формируем письмо
        $spec_link = $base_url."/question_service/answer.php?ext_id=".$ext_id.'&d='.$dealer_id.'&stat=dealer';
        $spec_subj = "На сайте evroplast.ru запрошен комментарий к вопросу №".$number;

        $spec_first = '<p style="margin:0px;margin-top:35px;font-size:16px;color:#000;">Уважаемые партнёры, в&nbsp;службу технической поддержки сайта <a href="https://perfom-decor.ru/"  target="_blank"style="color: #000;">perfom-decor.ru</a> поступил запрос на&nbsp;продукцию Европласт в&nbsp;вашем регионе.</p>';

        $spec_last .= '<p style="font-size:16px;margin:0;color:#000;">Просьба связаться с&nbsp;клиентом и&nbsp;заполнить комментарий о&nbsp;проделанной работе <a href="'.$spec_link.'" target="_blank" style="color:#849795;">по&nbsp;ссылке</a>.</p>';

        $spec_mail = $spec_first.$spec_last;


        //$send_emails = $dealer_email.', o.gmirya@decor-evroplast.ru, L.Osetrova@decor-evroplast.ru,d.mescheryakova@decor-evroplast.ru,s.burova@decor-evroplast.ru';
        $send_emails = $dealer_email.', L.Osetrova@decor-evroplast.ru,d.mescheryakova@decor-evroplast.ru,s.burova@decor-evroplast.ru';
        $hidden_emails = 'd.portu.by@yandex.ru, nadida.hi@yandex.ru';
        $fields = array('EMAIL'=>$send_emails, 'HIDDEN_EMAIL'=>$hidden_emails, 'SUBJ'=>$spec_subj,'TEXT'=>$spec_mail);
        CEvent::SendImmediate("E_QST_SERV", s1, $fields, "N");

        //сохраняем в бд
        CIBlockElement::SetPropertyValuesEX($id,37,array("REQ_COMM"=>'Y','REQ_COMM_EMAIL'=>$dealer_email,'REQ_COMM_ID'=>$dealer_id,'REQ_COMM_DATE'=> date($DB->DateFormatToPHP(FORMAT_DATETIME)),'QST_UPLOAD'=>'N'));
    }




    print $dealer_name.'<br>'.$dealer_email;

}//-----------------------------------


?>