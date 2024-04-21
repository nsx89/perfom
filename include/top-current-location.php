<?
global $my_city; // вся игра от региона

if (!isset($my_city) || !$my_city) { // Вот так не должно быть
    $arFilter = Array('IBLOCK_ID' => 7, 'ACTIVE' => 'Y', 'CODE' => 'moskva');
    $db_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter);
    $loc = $db_list->GetNextElement();
    $loc = array_merge($loc->GetFields(), $loc->GetProperties());
    $my_city = $loc['ID'];
} else {
    $arFilter = Array('IBLOCK_ID' => 7, 'ACTIVE' => 'Y', 'ID' => $my_city);
    $db_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter);
    $loc = $db_list->GetNextElement();
    if (!$loc) { // Вариант в случае отключения региона и прописанной позиции пользователя
        $arFilter = Array('IBLOCK_ID' => 7, 'ACTIVE' => 'Y', 'CODE' => 'moskva');
        $db_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter);
        $loc = $db_list->GetNextElement();
		$my_city_fix = true; // Дать перевыбрать регион или зависнет на Москве
    }
    $loc = array_merge($loc->GetFields(), $loc->GetProperties());
    $my_city = $loc['ID'];
}
?>

<? if ($loc) { ?>
    <?

    require_once($_SERVER["DOCUMENT_ROOT"] . "/include/phone.php");

    if($loc['reg_dealers']['VALUE']) { //Краснодарский вопрос
        $arFilter = Array('IBLOCK_ID' => 6, 'ACTIVE' => 'Y', 'ID' => $loc['reg_dealers']['VALUE']);
    } elseif($loc['dealers_list']['VALUE'] != '') { //помечен контакт для заказа
        $arFilter = Array('IBLOCK_ID' => 6, 'ACTIVE' => 'Y', 'ID' => $loc['dealers_list']['VALUE']); // установка на номер получателя
    } else { //первый по сортировке
        $arFilter = Array('IBLOCK_ID' => 6, 'ACTIVE' => 'Y', 'PROPERTY_city' => $loc);
    }

    $phone = "";
    $phone2 = "";
    $db_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter);
	if($loc['dealers_list']['VALUE'] != '') {
		$dealers = array();
		while ($dealer = $db_list->GetNextElement()) {
			$dealers[] = $dealer;	
		}
		shuffle($dealers); // случайный порядок
		$dealer = $dealers[0];
	} else {
		$dealer = $db_list->GetNextElement();
	}
    if ($dealer) {
        //if ($dealer) {
        $dealer = array_merge($dealer->GetFields(), $dealer->GetProperties());

        /*if (!empty($_GET['test'])) {
            echo '<pre>';print_r($loc);echo '</pre>';
        }*/
		
		//echo $dealer['NAME'];

        if ($loc['CODE'] == 'moskva') {
            //global $yg_direct;

            //if ($yg_direct) $phone = phone('74957791059');
            //        else $phone = '+7 (495) 640-88-51';

            //$phone = '+7 (495) 640-88-51';
            //$phone = '+7 (495) 116-55-36';
            //$phone = '+7 (495) 779-10-59';

            //меняем телефоны по дням
            /*function cur_phone($n)
            {
                switch ($n) {
                    case '1': return '+7 (495) 116-55-36';
                    case '2': return '+7 (495) 640-88-51';
                }
            }

            $numb = 2; //количество телефонов

            //текущий день
            $now = date("N");

            //записанный в бд день-час-порядковый номер телефона
            $db_props = CIBlockElement::GetProperty(22, 37484, array(), Array("CODE"=>"number"));
            if($ar_props = $db_props->Fetch()) {
                $current = $ar_props["VALUE"];
                $current = explode('-', $current);
            }

            if( $now != $current[0] || count($current) == 0 ) {
                if (count($current) == 0 || $current[1] == $numb) {
                    $n = 1;
                }
                else {
                    $n = $current[1];
                    $n++;
                }
                $phone = cur_phone($n);
                $val = $now.'-'.$n;
                CIBlockElement::SetPropertyValueCode(37484,"number", $val);
            }
            else {
                $phone = cur_phone($current[1]);
            }*/

            if($loc['ID'] == 3109) {
                $phone = '+7 495 315 31 10';
                $link_phone = $phone;
                $header_time = "время работы с&nbsp;9&nbsp;до&nbsp;20 <br>без&nbsp;выходных";
            }
			
		} elseif ($loc['CODE'] == 'sankt-peterburg') {
			if($loc['ID'] == 3196) { // Питер
                $phone = '+7(800)707-01-67';
                $link_phone = $phone;
                $header_time = "время работы &nbsp;10:00&nbsp;-&nbsp;22:00 <br>без&nbsp;выходных";
            }
        } elseif ($loc['notel']['VALUE'] != 'Y') {
            if($dealer['order_phone']['VALUE'] || $dealer['phones']['VALUE']) {
                $phones = $dealer['order_phone']['VALUE'] != '' ? $dealer['order_phone']['VALUE'] : $dealer['phones']['VALUE'];
                $phones = explode(';', $phones);
                $phone = phone(trim($phones[0]));
                $link_phone = $phone;
                $phone2 = phone(trim($phones[1]));
                $phone = 1 ? substr_replace ($phone, '<span style="display: none;">no skype!</span>', 6, 0) : $phone;

                //время работы
                $header_time = '';
                $weekend = '';
                if($dealer['without']['VALUE'] == 'Y') {
                    if(mb_strtolower($dealer['saturday']['VALUE']) == 'выходной' && mb_strtolower($dealer['sunday']['VALUE']) == 'выходной' && $dealer['weekend']['VALUE'] == '' || $dealer['workday']['VALUE'] == $dealer['saturday']['VALUE'] && $dealer['workday']['VALUE'] == $dealer['sunday']['VALUE'] && $dealer['weekend']['VALUE'] == '') {
                        $header_time = "время работы ".$dealer['workday']['VALUE']." <br>без выходных";
                    } else {
                        $header_time = "будни: ".$dealer['workday']['VALUE']." <br>";
                        if($dealer['saturday']['VALUE'] == $dealer['sunday']['VALUE']) {
                            $header_time .= "сб.-вс.: ".$dealer['saturday']['VALUE'];
                        } else {
                            if($dealer['saturday']['VALUE'] != 'Выходной') {
                                $header_time .= "сб.: ".$dealer['saturday']['VALUE']."<br>";
                            }
                            if($dealer['sunday']['VALUE'] != 'Выходной') $header_time .= "вс.: ".$dealer['sunday']['VALUE'];
                        }
                    }
                } else {
                    if(mb_strtolower($dealer['saturday']['VALUE']) == 'выходной' && mb_strtolower($dealer['sunday']['VALUE']) == 'выходной' && $dealer['weekend']['VALUE'] == '') {
                        $header_time = "время работы ".$dealer['workday']['VALUE']." <br>понедельник&nbsp;-&nbsp;пятница";
                    } elseif($dealer['weekend']['VALUE'] == '') {
                        $header_time = "будни: ".$dealer['workday']['VALUE'].' <br>';
                        if(mb_strtolower($dealer['saturday']['VALUE']) != 'выходной') {
                            if(mb_strtolower($dealer['sunday']['VALUE']) != 'выходной') {
                                $header_time .= "сб.: ".$dealer['saturday']['VALUE'];
                            } else {
                                $header_time .= "суббота: ".$dealer['saturday']['VALUE'];
                            }
                        }
                        if(mb_strtolower($dealer['sunday']['VALUE']) != 'выходной') {
                            if(mb_strtolower($dealer['saturday']['VALUE']) != 'Выходной') {
                                $header_time .= " <br>вс.: ".$dealer['sunday']['VALUE'];
                            } else {
                                $header_time .= "воскресенье: ".$dealer['sunday']['VALUE'];
                            }
                        }
                    } else {
                        $header_time = "будни: ".$dealer['workday']['VALUE'].' <br>';
                        $weekend = " <br>выходные: ";
                        if(mb_strtolower($dealer['saturday']['VALUE']) != 'выходной') {
                                $header_time .= "сб.: ".$dealer['saturday']['VALUE'];
                        } else {
                            $weekend .= "сб., ";
                        }
                        if(mb_strtolower($dealer['sunday']['VALUE']) != 'выходной') {
                                $header_time .= "вс.: ".$dealer['sunday']['VALUE'];
                        } else {
                            $weekend .= "вс., ";
                        }
                        if($dealer['weekend']['VALUE'] != '') {
                            $weekend .= $dealer['weekend']['VALUE'].", ";
                        }
                        $weekend = substr($weekend,0,count($weekend)-3);
                        $header_time .= $weekend;
                    }
                }
                if($dealer['workday']['VALUE'] == '' && $dealer['saturday']['VALUE'] == '' && $dealer['sunday']['VALUE'] == '' && $dealer['weekend']['VALUE'] == '' && $dealer['without']['VALUE'] != 'Y') $header_time = '';
            }
            //echo str_phone($dealer['phones']['VALUE']);
        }
        $link_phone= str_replace([' ', '(', ')', '-'], '', $link_phone);
    }

    ?>
<?}?>
