<?
$_SERVER["DOCUMENT_ROOT"] = '/home/bitrix/www';

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS",true);

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
	if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
	    exit;
	}
	require_once($_SERVER["DOCUMENT_ROOT"] . "/ajax/question_service.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/include/handle_date.php");

	$aqs_base_url = "https://evroplast.ru";
	//$aqs_base_url = "http://eplast.loc";




    //проверка по вопросам
    if ($work_day == 'y' && date('H', $current_time) >= '9' && date('H', $current_time) < '18') {

        $aqs_arFilter = Array(
            'IBLOCK_ID' => 37,
            'ACTIVE' => 'Y',
            Array(
                "LOGIC" => "AND",
                array("!PROPERTY_QST_STATUS" => "Вопрос просрочен"),
                array("!PROPERTY_QST_STATUS" => "На модерации"),
                array("!PROPERTY_QST_STATUS" => "Вопрос переадресован дилеру"),
                array("!PROPERTY_QST_STATUS" => "Ответ отправлен"),
            ),
            "PROPERTY_ANSW" => false,
            "PROPERTY_SEND_DATE" => false
        );

        $aqs_res = CIBlockElement::GetList(Array(), $aqs_arFilter);
        while ($aqs_item = $aqs_res->GetNextElement()) {

            $aqs_item = array_merge($aqs_item->GetFields(), $aqs_item->GetProperties());

            //$item_date_create = $aqs_item['QST_DATE']['VALUE'];
            $item_date_create = $aqs_item['DATE_CREATE'];

            // if($aqs_item['QST_PUTOFF_DATE']['VALUE'] == "") {

            if ($aqs_item['QST_SUBJ']['VALUE'] == "Монтаж изделий" || $aqs_item['QST_PUTOFF_DATE']['VALUE'] != "") {
                //монтаж изделий
                $date_create = DateTime::createFromFormat('d.m.Y H:i:s', $item_date_create);
                $diff = $now->diff($date_create);
                $total = ($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h + $diff->i / 60;

                if ($total >= 24) {  //прошло > 24 часов

                    $date_create_unix = $date_create->format('U');
                    $closest_work_day = closest_work_day($date_create_unix);

                    if (date('d.m.Y', $closest_work_day) == date('d.m.Y', $date_create_unix)) {  //был рабочий день

                        $achtung_date = strtotime("+24 hours", $closest_work_day);

                        //если следующий выходной день, ищем ближайший рабочиий
                        if (check_work_day($achtung_date) == 'n') {

                            //if( $current_time >= $achtung_date) {

                            $achtung_date = closest_work_day($achtung_date);
                            //}
                        }

                        if ($current_time >= $achtung_date) {

                            //echo 'achtung-work!';

                            foreach ($aqs_item['QST_SPEC']['VALUE'] as $spec) {
                                $aqs_arr[$spec][$aqs_item['NAME']] = $aqs_item['EXTERNAL_ID']['VALUE'];
                            }

                            CIBlockElement::SetPropertyValuesEX($aqs_item['ID'], 37, array("QST_STATUS" => "Вопрос просрочен"));
                        }

                    } else {  //был выходной день

                        $achtung_date = strtotime("+24 hours", $closest_work_day);

                        if ($current_time >= $achtung_date) {
                            //echo 'achtung-holiday!';

                            foreach ($aqs_item['QST_SPEC']['VALUE'] as $spec) {
                                $aqs_arr[$spec][$aqs_item['NAME']] = $item_date_create;
                            }

                            CIBlockElement::SetPropertyValuesEX($aqs_item['ID'], 37, array("QST_STATUS" => "Вопрос просрочен"));
                        }

                    }

                }
            } else {
                //остальные
                $date_create = DateTime::createFromFormat('d.m.Y H:i:s', $item_date_create);
                $diff = $now->diff($date_create);
                $total = ($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h + $diff->i / 60;

                if ($total >= 2) {  //прошло > 2 часов

                    $date_create_unix = $date_create->format('U');
                    $closest_work_day = closest_work_day($date_create_unix);

                    if (date('d.m.Y', $closest_work_day) == date('d.m.Y', $date_create_unix)) {  //был рабочий день

                        if (date('H', $date_create_unix) >= '9' && date('H', $date_create_unix) <= '18') { //вопрос пришел в рабочее время

                            $end_work_day = date('H', $closest_work_day);
                            if ($end_work_day >= 16) { //до окончания рабочего дня меньше 2 ч.

                                $start_work_day = strtotime("+1 day", $closest_work_day);

                                if (check_work_day($start_work_day) == 'n') {

                                    //if( $current_time >= $achtung_date) {
                                    $start_work_day = closest_work_day($start_work_day);
                                    //}
                                }
                                $start_work_day = date('d.m.Y', $current_time) . ' 11:00:00';
                                $start_work_day = DateTime::createFromFormat('d.m.Y H:i:s', $start_work_day);
                                $start_work_day = $start_work_day->format('U');

                                if ($current_time >= $start_work_day) {
                                    //echo 'achtung-work < 2!';
                                    foreach ($aqs_item['QST_SPEC']['VALUE'] as $spec) {
                                        $aqs_arr[$spec][$aqs_item['NAME']] = $aqs_item['EXTERNAL_ID']['VALUE'];
                                    }

                                    CIBlockElement::SetPropertyValuesEX($aqs_item['ID'], 37, array("QST_STATUS" => "Вопрос просрочен"));
                                }

                            } else {  //до окончания рабочего дня больше 2 ч.
                                //echo 'achtung-work > 2!';

                                foreach ($aqs_item['QST_SPEC']['VALUE'] as $spec) {
                                    $aqs_arr[$spec][$aqs_item['NAME']] = $aqs_item['EXTERNAL_ID']['VALUE'];
                                }

                                CIBlockElement::SetPropertyValuesEX($aqs_item['ID'], 37, array("QST_STATUS" => "Вопрос просрочен"));
                            }
                        } else {  //вопрос пришел вне рабочее время

                            $start_work_day = date('d.m.Y', $current_time) . ' 11:00:00';
                            $start_work_day = DateTime::createFromFormat('d.m.Y H:i:s', $start_work_day);
                            $start_work_day = $start_work_day->format('U');

                            if ($current_time >= $start_work_day) {

                                //echo 'achtung-not-work-time!';

                                foreach ($aqs_item['QST_SPEC']['VALUE'] as $spec) {
                                    $aqs_arr[$spec][$aqs_item['NAME']] = $aqs_item['EXTERNAL_ID']['VALUE'];
                                }

                                CIBlockElement::SetPropertyValuesEX($aqs_item['ID'], 37, array("QST_STATUS" => "Вопрос просрочен"));
                            }

                        }
                    } else {  //был выходной день

                        $start_work_day = date('d.m.Y', $current_time) . ' 11:00:00';
                        $start_work_day = DateTime::createFromFormat('d.m.Y H:i:s', $start_work_day);
                        $start_work_day = $start_work_day->format('U');

                        if ($current_time >= $start_work_day) {
                            //echo 'achtung-holiday-2!';

                            foreach ($aqs_item['QST_SPEC']['VALUE'] as $spec) {
                                $aqs_arr[$spec][$aqs_item['NAME']] = $aqs_item['EXTERNAL_ID']['VALUE'];
                            }

                            CIBlockElement::SetPropertyValuesEX($aqs_item['ID'], 37, array("QST_STATUS" => "Вопрос просрочен"));
                        }

                    }

                }
            }
        }
    }





	if (isset($aqs_arr)) {
	//отправляем письма ответственным
	$subj = "Больше, чем предупреждение!";
	$templ = "E_QST_SERV_ATTENTION";
	//$text_ryk = "<p style='font-size:14px;line-height:18px;margin:0;color:#000;'>";

	foreach($aqs_arr as $spec=>$qst) {
		$spec_arr = Array($spec);
		$text = "";

		//убираем дублирование для Рыка
		if($spec == "Сергей Авдеев") {
			$text_ryk .= "<span style='font-size:16px;color:#000;'>Ответственный: <b>Сергей Авдеев, Наталья Овчинникова</b></span><br>";
		}
		elseif($spec != "Александра Высоцкая" && $spec != "Наталья Овчинникова") {
			$text_ryk .= "<span style='font-size:16px;color:#000;'>Ответственный: <b>".$spec."</b></span><br>";
		}
		

		foreach ($qst as $number=>$ext_id) {
			$text .= '<a href="'.$aqs_base_url.'/question_service/answer.php?ext_id='.$ext_id.'" style="color:#849795;font-size:16px;font-weight:500;">Вопрос №'.$number.'</a><br>';

			if($spec != "Александра Высоцкая" && $spec != "Наталья Овчинникова") {
				$text_ryk .= '<a href="'.$aqs_base_url.'/question_service/answer.php?ext_id='.$ext_id.'" style="font-size:16px;color:#000;">Вопрос №'.$number.'</a><br>';
			}
		}
		if ($spec != "Александра Высоцкая" && $spec != "Наталья Овчинникова") {
			$text_ryk .= "<br>";
		}

		send_to_spec($spec_arr,$subj,$text,$templ);
	}

	/*$fields = array('EMAIL'=>'nadida.hi@gmail.com', 'SUBJ'=>$subj,'TEXT'=>$text);
      	CEvent::SendImmediate($templ, s1, $fields, "N");
	$fields = array('EMAIL'=>'d.portu.by@yandex.ru', 'SUBJ'=>$subj,'TEXT'=>$text);
	CEvent::SendImmediate($templ, s1, $fields, "N");
	$fields = array('EMAIL'=>'o.gmirya@decor-evroplast.ru', 'SUBJ'=>$subj,'TEXT'=>$text);
	CEvent::SendImmediate($templ, s1, $fields, "N");*/

	if ($spec != "Александра Высоцкая" && $spec != "Наталья Овчинникова") {
		//$text_ryk .= "</p>";
	}
	

	//письмо модератору
	$subj = "Просроченные вопросы";
	$templ = "E_QST_SERV";
	//$spec = Array("Ольга Гмыря");
	$spec = Array("Андрей Чиличихин");

	$text_first = '<p style="margin:0px;margin-top:35px;font-size:16px;color:#000;"><strong>Здравствуйте! На сайте обнаружены просроченные вопросы.</strong></p><br>';
	$text_last = '<p style="font-size:16px;margin:0;color:#000;">Всем злосным нарушителям отправлены уведомления.</p><p style="font-size:16px;margin:0;color:#000;">Для оценки текущей ситуации <a href="'.$aqs_base_url.'/question_service/moderation.php" target="_blank" style="color:#849795;">перейдите по ссылке</a>.</p><br><p style="font-size:16px;margin:0;color:#000;"><b>С уважением, Справочная.</b></p>';

	$text = $text_first.$text_ryk.$text_last;

	$fields = array('EMAIL'=>'d.portu.by@yandex.ru', 'SUBJ'=>$subj,'TEXT'=>$text);
	CEvent::SendImmediate($templ, s1, $fields, "N");
	//$fields = array('EMAIL'=>'o.gmirya@decor-evroplast.ru', 'SUBJ'=>$subj,'TEXT'=>$text);
	//CEvent::SendImmediate($templ, s1, $fields, "N");
	$fields = array('EMAIL'=>'s.burova@decor-evroplast.ru', 'SUBJ'=>$subj,'TEXT'=>$text);
	CEvent::SendImmediate($templ, s1, $fields, "N");
    $fields = array('EMAIL'=>'d.mescheryakova@decor-evroplast.ru', 'SUBJ'=>$subj,'TEXT'=>$text);
    CEvent::SendImmediate($templ, s1, $fields, "N");
    $fields = array('EMAIL'=>'a.chilichihin@decor-evroplast.ru', 'SUBJ'=>$subj,'TEXT'=>$text);
    CEvent::SendImmediate($templ, s1, $fields, "N");
    $fields = array('EMAIL'=>'A.Dunaeva@decor-evroplast.ru', 'SUBJ'=>$subj,'TEXT'=>$text);
    CEvent::SendImmediate($templ, s1, $fields, "N");
	$fields = array('EMAIL'=>'A.Bruk@decor-evroplast.ru', 'SUBJ'=>$subj,'TEXT'=>$text);
	CEvent::SendImmediate($templ, s1, $fields, "N");

	//send_to_spec($spec,$subj,$text,$templ);
	
}

?>