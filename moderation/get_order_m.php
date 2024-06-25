<? // Отчет по региону Москва и МО

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog") || !CModule::IncludeModule("search")) {
    exit;
}

require_once($_SERVER["DOCUMENT_ROOT"] . '/include/php_excel/PHPExcel.php');
require_once($_SERVER["DOCUMENT_ROOT"] . "/include/phone.php");
//require_once($_SERVER["DOCUMENT_ROOT"] . '/include/php_excel/PHPExcel/Writer/Excel2007.php');


$data = $_REQUEST;

$arFilter = Array();

// ПРОДАЖИ ПО МОСКВЕ
if($data['type'] == 'order_mosc') {

    $arFilter['IBLOCK_CODE'] = "keep_order";
    $arFilter['ACTIVE'] = "y";
    //дата
    $period_title = '';
    if(!empty($data['date'])) {

        $period_title .= ' за период';

        $sort_date_from = $data['date']['from'];
        $sort_date_to = $data['date']['to'];

        if($sort_date_from!='') {
            $period_title .= ' с '.$sort_date_from;
            $sort_date_from = explode('.',$sort_date_from);
            $sort_date_from = $sort_date_from[2].'-'.$sort_date_from[1].'-'.$sort_date_from[0];
            $arFilter['>=PROPERTY_DATE'] = $sort_date_from." 00:00:00";
        }

        if($sort_date_to!='') {
            $period_title .= ' по '.$sort_date_to;
            $sort_date_to = explode('.',$sort_date_to);
            $sort_date_to = $sort_date_to[2].'-'.$sort_date_to[1].'-'.$sort_date_to[0];
            $arFilter['<=PROPERTY_DATE'] = $sort_date_to." 23:59:59";
        }
    } else {
        $period_title .= ' за весь период';
    }

//статус заказа
    if(!empty($data['status'])) {
        $property_arr = array("LOGIC" => "OR");
        foreach($data['status'] as $item) {
            if($item == 'no') {
                $property_arr[] = array('PROPERTY_STATUS' => false);
            } else {
                $property_arr[] = array('PROPERTY_STATUS' => $item);
            }
        }
        $arFilter[] = $property_arr;
    }

//менеджер
    if(!empty($data['manager'])) {
        $property_arr = array("LOGIC" => "OR");
        foreach($data['manager'] as $item) {
            if($item == 'no') {
                $property_arr[] = array('PROPERTY_MODERATOR' => false);
            } else {
                $property_arr[] = array('PROPERTY_MODERATOR' => $item);
            }
        }
        $arFilter[] = $property_arr;
    }

//способ оплаты
    if(!empty($data['payment'])) {
        $property_arr = array("LOGIC" => "OR");
        foreach($data['payment'] as $item) {
            if($item == 'online') {
                $property_arr[] = array('PROPERTY_PAYMENT' => $item);
            } else {
                $property_arr[] = array('PROPERTY_PAYMENT' => 'cash', 'PROPERTY_RECEIVING' => $item);
            }
        }
        $arFilter[] = $property_arr;
    }

//способ получения
    if(!empty($data['delivery'])) {
        $property_arr = array("LOGIC" => "OR");
        foreach($data['delivery'] as $item) {
            if($item == 'no') {
                $property_arr[] = array('PROPERTY_DELIVERY' => false);
            } else {
                $property_arr[] = array('PROPERTY_DELIVERY' => $item);
            }
        }
        $arFilter[] = $property_arr;
    }

    $arFilter[] = array('PROPERTY_CHOOSEN_REG' => 3109);//только Москва

    $file_order = $_SERVER["DOCUMENT_ROOT"] . '/moderation/reports/' . 'order_report.xlsx';
    $file_download = '/moderation/reports/' . 'order_report.xlsx';


// Создаем объект класса PHPExcel
    $xls = new PHPExcel();
// Устанавливаем индекс активного листа
    $xls->setActiveSheetIndex(0);
// Получаем активный лист
    $sheet = $xls->getActiveSheet();
// Подписываем лист
    $sheet->setTitle('Заказы');

    $sheet->setCellValue("A1", 'Отчет по заказам'.$period_title);
// Объединяем ячейки
    $sheet->mergeCells('A1:N1');

    $sheet->getStyle('A1')->getAlignment()->setHorizontal(
        PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


    $sheet->setCellValue("A2", 'Дата заказа');
    $sheet->setCellValue("B2", 'Номер заказа');
    $sheet->mergeCells('C2:D2');
    $sheet->mergeCells('E2:F2');
    $sheet->mergeCells('G2:H2');
    $sheet->mergeCells('I2:J2');
    $sheet->mergeCells('K2:L2');
    $sheet->mergeCells('M2:N2');

    $sheet->setCellValue("C2", 'Способ получения');
    $sheet->setCellValue("E2", 'Адрес получения');
    $sheet->setCellValue("G2", 'Способ оплаты');
    $sheet->setCellValue("I2", 'Сумма заказа');
    $sheet->setCellValue("K2", 'Статус заказа');
    $sheet->setCellValue("M2", 'ФИО менеджера');
    $sheet->setCellValue("O2", 'ФИО клиента');
    $sheet->setCellValue("P2", 'Телефон клиента');
    $sheet->setCellValue("Q2", 'E-mail клиента');
    $sheet->setCellValue("R2", 'Адрес клиента');

    $sheet->getStyle('A2:R2')->getFill()->setFillType(
        PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('A2:R2')->getFill()->getStartColor()->setRGB('EEEEEE');
    $sheet->getStyle('A2:R2')->getAlignment()->setHorizontal(
        PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $sheet->getColumnDimension('A')->setWidth(30);
    $sheet->getColumnDimension('B')->setWidth(20);
    $sheet->getColumnDimension('C')->setWidth(25);
    $sheet->getColumnDimension('D')->setWidth(8);
    $sheet->getColumnDimension('E')->setWidth(30);
    $sheet->getColumnDimension('F')->setWidth(8);
    $sheet->getColumnDimension('G')->setWidth(25);
    $sheet->getColumnDimension('H')->setWidth(8);
    $sheet->getColumnDimension('I')->setWidth(25);
    $sheet->getColumnDimension('J')->setWidth(15);
    $sheet->getColumnDimension('K')->setWidth(25);
    $sheet->getColumnDimension('L')->setWidth(8);
    $sheet->getColumnDimension('M')->setWidth(25);
    $sheet->getColumnDimension('N')->setWidth(8);
    $sheet->getColumnDimension('O')->setWidth(25);
    $sheet->getColumnDimension('P')->setWidth(25);
    $sheet->getColumnDimension('Q')->setWidth(35);
    $sheet->getColumnDimension('R')->setWidth(50);


    $total = array();
    $c_field = 3;
    $res = CIBlockElement::GetList(Array('id'=>'asc'),$arFilter,false, Array(), Array());
    while($item = $res->GetNextElement()) {
        $item = array_merge($item->GetFields(), $item->GetProperties());
        // Дата
        $sheet->setCellValue("A".$c_field, $item['DATE']['VALUE']); $sheet->getStyle("A".$c_field)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        // Номер
        $sheet->setCellValue("B".$c_field, $item['NAME']); $sheet->getStyle("B".$c_field)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        // Способ получения
        if($item['DELIVERY']['VALUE']=='del') $delivery_info = 'Доставка';
        elseif($item['DELIVERY']['VALUE']=='pickup') $delivery_info = 'Самовывоз';
        $sheet->setCellValue("C".$c_field, $delivery_info); $sheet->getStyle("C".$c_field)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); $sheet->mergeCells("C".$c_field.":D".$c_field);
        $total['DELIVERY'][$item['DELIVERY']['VALUE']]++;

        // Адрес получения
        if($item['DELIVERY']['VALUE']=='pickup') {
            if($item['MAIL_DEALER']['VALUE']=='kdvor@decor-evroplast.ru') {
                $dealer_info = 'ТК "Каширский двор"'; $total['DEALER'][1]++;
            } else  { $dealer_info = 'ТВК "ЭКСПОСТРОЙ"'; $total['DEALER'][2]++; }
            $sheet->setCellValue("E".$c_field, $dealer_info);
        } $sheet->getStyle("E".$c_field)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); $sheet->mergeCells("E".$c_field.":F".$c_field);

        // Способ оплаты
        if($item['PAYMENT']['VALUE']=='online') {
            $payment_info = 'Оплата онлайн';
        }
        elseif($item['PAYMENT']['VALUE']=='cash' && $item['RECEIVING']['VALUE']=='receiving-card')  {
            $payment_info = 'Оплата при получении картой';
        }
        elseif($item['PAYMENT']['VALUE']=='cash' && $item['RECEIVING']['VALUE']=='receiving-cash') {
            $payment_info = 'Оплата при получении наличными';
        }
        elseif($item['PAYMENT']['VALUE']=='cash')  {
            $payment_info = 'Оплата при получении';
        }
        elseif($item['PAYMENT']['VALUE']=='prepayment')  {
            $payment_info = 'Предоплата';
        }
        $sheet->setCellValue("G".$c_field, $payment_info); $sheet->getStyle("G".$c_field)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); $sheet->mergeCells("G".$c_field.":H".$c_field);
        $total['PAYMENT'][$item['PAYMENT']['VALUE']]++;

        // Сумма заказа
        if ($item["TOTAL_SALE"]["VALUE"]) $price = $item["TOTAL_SALE"]["VALUE"]; // нехорошее место нянется, надо учитывать
        else $price = $item["TOTAL"]["VALUE"];
        $sheet->setCellValue("I".$c_field, $price); $sheet->getStyle("I".$c_field)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); $sheet->mergeCells("I".$c_field.":J".$c_field);
        $sheet->getStyle("I".$c_field)->getNumberFormat()->setFormatCode('#,##0');
        if ($item['PAYMENT']['VALUE']=='online') $total['TOTAL']['online'] += $price;
        else $total['TOTAL']['cash'] += $price;

        // Статус
        $sheet->setCellValue("K".$c_field, get_order_status($item['STATUS']['VALUE'])); $sheet->getStyle("K".$c_field)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); $sheet->mergeCells("K".$c_field.":L".$c_field);
        $total['STATUS'][$item['STATUS']['VALUE']]++;

        // Менеджер
        $rsModUser = CUser::GetByID($item['MODERATOR']['VALUE']);
        $mod_user = $rsModUser->Fetch();
        $mod_name = $mod_user['LAST_NAME'] != '' ? $mod_user['LAST_NAME'].' '.mb_substr($mod_user['NAME'],0,1).'.' : $mod_user['NAME'];
        $sheet->setCellValue("M".$c_field, $mod_name); $sheet->getStyle("M".$c_field)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); $sheet->mergeCells("M".$c_field.":N".$c_field);
        $total['MODERATOR'][$item['MODERATOR']['VALUE']]++;

        // Клиент
        $sheet->setCellValue("O".$c_field, $item["USER_NAME"]["VALUE"].' '.$item["USER_LAST_NAME"]["VALUE"]); $sheet->getStyle("O".$c_field)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue("P".$c_field, strpos($item['USER_PHONE']['VALUE'],'+') === true ? $item['USER_PHONE']['VALUE'] : str_phone($item['USER_PHONE']['VALUE'])); $sheet->getStyle("P".$c_field)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue("Q".$c_field, $item["USER_MAIL"]["VALUE"]); $sheet->getStyle("Q".$c_field)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $addr = '';
        if($item["USER_ADDR"]["VALUE"] != '') {
            $addr = $item["USER_ADDR"]["VALUE"];
        } else {
            if($item["USER_CITY"]["VALUE"] != '') $addr .= 'г. '.$item["USER_CITY"]["VALUE"];
            if($item["USER_STREET"]["VALUE"] != '') $addr .= ' ул. '.$item["USER_STREET"]["VALUE"];
            if($item["USER_HOUSE"]["VALUE"] != '') $addr .= ' д. '.$item["USER_HOUSE"]["VALUE"];
        }
        $sheet->setCellValue("R".$c_field, $addr); $sheet->getStyle("R".$c_field)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


        $c_field++;
    }
// Итоги
    $sheet->setCellValue("B".$c_field, 'ИТОГО:'); $sheet->getStyle("B".$c_field)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    $sheet->getStyle('B'.$c_field.':N'.($c_field+1))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('B'.$c_field.':N'.($c_field+1))->getFill()->getStartColor()->setRGB('EEEEEE');
    $sheet->mergeCells('B'.$c_field.':N'.($c_field+1));

    $c_field++;

// Способ получения
    $sheet->setCellValue("C".($c_field+1), '  Доставка'); $sheet->setCellValue("D".($c_field+1), $total['DELIVERY']['del']);
    $sheet->setCellValue("C".($c_field+2), '  Самовывоз'); $sheet->setCellValue("D".($c_field+2), $total['DELIVERY']['pickup']);

// Адрес получения
    $sheet->setCellValue("E".($c_field+1), '  ТК "Каширский двор"'); $sheet->setCellValue("F".($c_field+1), $total['DEALER'][1]);
    $sheet->setCellValue("E".($c_field+2), '  ТВК "ЭКСПОСТРОЙ"'); $sheet->setCellValue("F".($c_field+2), $total['DEALER'][2]);

// Способ оплаты
    $sheet->setCellValue("G".($c_field+1), '  Оплата онлайн'); $sheet->setCellValue("H".($c_field+1), $total['PAYMENT']['online']);
    $sheet->setCellValue("G".($c_field+2), '  Оплата при получении'); $sheet->setCellValue("H".($c_field+2), $total['PAYMENT']['cash']);
    $sheet->setCellValue("G".($c_field+3), '  Предоплата'); $sheet->setCellValue("H".($c_field+3), $total['PAYMENT']['prepayment']);

// Суммы заказа
    $sheet->setCellValue("I".($c_field+1), '  Сумма онлайн оплат'); $sheet->setCellValue("J".($c_field+1), $total['TOTAL']['online']);
    $sheet->setCellValue("I".($c_field+2), '  Сумма при получении'); $sheet->setCellValue("J".($c_field+2), $total['TOTAL']['cash']);
    $sheet->setCellValue("I".($c_field+3), '  Сумма ИТОГО'); $sheet->setCellValue("J".($c_field+3), ($total['TOTAL']['online']+$total['TOTAL']['cash']));

// Статусы
    $c_field_add = 1;
    foreach ($total['STATUS'] as $key => $st) {
        $sheet->setCellValue("K".($c_field+$c_field_add), '  '.get_order_status($key)); $sheet->setCellValue("L".($c_field+$c_field_add), $st);
        $c_field_add++;
    }

// Менеджеры
    $c_field_add = 1;
    foreach ($total['MODERATOR'] as $key => $st) {
        $rsModUser = CUser::GetByID($key);
        $mod_user = $rsModUser->Fetch();
        $mod_name = $mod_user['LAST_NAME'] != '' ? $mod_user['LAST_NAME'].' '.mb_substr($mod_user['NAME'],0,1).'.' : $mod_user['NAME'];
        $sheet->setCellValue("M".($c_field+$c_field_add), '  '.$mod_name); $sheet->setCellValue("N".($c_field+$c_field_add), $st);
        $c_field_add++;
    }


// Выводим содержимое файла
    $objWriter = PHPExcel_IOFactory::createWriter($xls, 'Excel2007');
    $objWriter->save($file_order);

// echo 'файл данных сформирован ...'.'<br>';
    //echo '<a href="'.$file_download.'">загрузить</a>';
    echo $file_download;
}

//ЗАКАЗЫ ОНЛАЙН-ПОДБОРА
if($data['type'] == 'online') {
    $arFilter['IBLOCK_CODE'] = "online_order";
    $arFilter['ACTIVE'] = "y";

//дата
    $period_title = '';
    if(!empty($data['date'])) {

        $period_title .= ' за период';

        $sort_date_from = $data['date']['from'];
        $sort_date_to = $data['date']['to'];

        if($sort_date_from!='') {
            $period_title .= ' с '.$sort_date_from;
            $arFilter['>=DATE_CREATE'] = $data['date']['from']." 00:00:00";
        }

        if($sort_date_to!='') {
            $period_title .= ' по '.$sort_date_to;
            $arFilter['<=DATE_CREATE'] = $data['date']['to']." 23:59:59";
        }
    } else {
        $period_title .= ' за весь период';
    }

    $file_order = $_SERVER["DOCUMENT_ROOT"] . '/moderation/reports/' . 'online_consultation_report.xlsx';
    $file_download = '/moderation/reports/' . 'online_consultation_report.xlsx';


    $xls = new PHPExcel();
    $xls->setActiveSheetIndex(0);
    $sheet = $xls->getActiveSheet();
    $sheet->setTitle('Заказы онлайн-подбора');

    $sheet->setCellValue("A1", 'Отчет по заказам онлайн-подбора'.$period_title);
    $sheet->mergeCells('A1:F1');
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(
        PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


    $sheet->setCellValue("A2", 'Дата');
    $sheet->setCellValue("B2", 'Имя');
    $sheet->setCellValue("C2", 'Контактный телефон');
    $sheet->setCellValue("D2", 'Город');
    $sheet->setCellValue("E2", 'Время для звонка');
    $sheet->setCellValue("F2", 'E-mail дилера');

    $sheet->getStyle('A2:F2')->getFill()->setFillType(
        PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('A2:F2')->getFill()->getStartColor()->setRGB('EEEEEE');
    $sheet->getStyle('A2:F2')->getAlignment()->setHorizontal(
        PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $sheet->getColumnDimension('A')->setWidth(30);
    $sheet->getColumnDimension('B')->setWidth(30);
    $sheet->getColumnDimension('C')->setWidth(30);
    $sheet->getColumnDimension('D')->setWidth(30);
    $sheet->getColumnDimension('E')->setWidth(30);
    $sheet->getColumnDimension('F')->setWidth(35);



    $total = 0;
    $c_field = 3;

    $res = CIBlockElement::GetList(Array('created'=>'desc'),$arFilter,false, Array(), Array());

    while($item = $res->GetNextElement()) {
        $item = array_merge($item->GetFields(), $item->GetProperties());
        //дата
        $sheet->setCellValue("A".$c_field, $item['DATE_CREATE']);
        $sheet->getStyle("A".$c_field)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //имя
        $sheet->setCellValue("B".$c_field, $item['NAME']);
        $sheet->getStyle("B".$c_field)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //телефон
        $phone = strpos($item['PHONE']['VALUE'],'+') === true ? $item['PHONE']['VALUE'] : str_phone($item['PHONE']['VALUE']);
        $sheet->setCellValue("C".$c_field, $phone);
        $sheet->getStyle("C".$c_field)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //город
        $sheet->setCellValue("D".$c_field, $item['CITY']['VALUE']);
        $sheet->getStyle("D".$c_field)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //время
        $sheet->setCellValue("E".$c_field, $item['TIME']['VALUE']);
        $sheet->getStyle("E".$c_field)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //дилер
        $sheet->setCellValue("F".$c_field, $item['DEALER']['VALUE']);
        $sheet->getStyle("F".$c_field)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $c_field++;
        $total++;
    }
    //итого
    $sheet->setCellValue("E".$c_field, "ВСЕГО ЗАКАЗОВ");
    $sheet->getStyle("E".$c_field)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    $sheet->getStyle("E".$c_field)->getFont()->setBold(true);
    $sheet->setCellValue("F".$c_field, $total);
    $sheet->getStyle("F".$c_field)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    $sheet->getStyle("F".$c_field)->getFont()->setBold(true);
    $sheet->getStyle("E".$c_field.':'."F".$c_field)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle("E".$c_field.':'."F".$c_field)->getFill()->getStartColor()->setRGB('EEEEEE');




    $objWriter = PHPExcel_IOFactory::createWriter($xls, 'Excel2007');
    $objWriter->save($file_order);

    echo $file_download;
}



?>