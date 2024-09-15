<?
$_SERVER["DOCUMENT_ROOT"] = '/home/bitrix/www';

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS",true);

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}
require_once($_SERVER["DOCUMENT_ROOT"] . "/ajax/question_service.php");
require_once($_SERVER["DOCUMENT_ROOT"] . '/include/php_excel/PHPExcel.php');

$day = date("N");
$res = CIBlockElement::GetList(Array(),Array('IBLOCK_ID'=>38, 'ACTIVE'=>'Y',"CODE"=>'need_comment'));
while($item_counter = $res->GetNextElement()) $need_comment_counter = array_merge($item_counter->GetFields(), $item_counter->GetProperties());

if($day == '7' && $need_comment_counter['COUNT_NUMBER']['VALUE'] == '0') {

    CIBlockElement::SetPropertyValuesEX($need_comment_counter['ID'],38,array("COUNT_NUMBER"=>'1'));

} elseif ($day == '7' && $need_comment_counter['COUNT_NUMBER']['VALUE'] == '1') {

    CIBlockElement::SetPropertyValuesEX($need_comment_counter['ID'],38,array("COUNT_NUMBER"=>'0'));

    $period_title = ' с '.date('d.m.Y',strtotime("-7 days")).' по '.date('d.m.Y',strtotime("-1 days"));
    //$period_title = ' с 20.09.2021 по 26.09.2021';

    $file_order = $_SERVER["DOCUMENT_ROOT"] . '/question_service/' . 'dealer_comments_report.xlsx';
    $file_download = '/question_service/' . 'dealer_comments_report.xlsx';



    $txt = '';
    $res = CIBlockElement::GetList(Array(),Array('IBLOCK_ID'=>37, 'ACTIVE'=>'Y', "PROPERTY_QST_UPLOAD"=>'N',Array("LOGIC"=>"OR",Array("PROPERTY_REQ_COMM_CLOSED"=>'Y'),Array("PROPERTY_REQ_COMM"=>'Y'))));
    if(intval($res->SelectedRowsCount()) > 0) {
        $xls = new PHPExcel();
        $xls->setActiveSheetIndex(0);
        $sheet = $xls->getActiveSheet();
        $sheet->setTitle('Отчёт');
        $sheet->setCellValue("A1", 'Отчет по вопросам, к которым запрошены комментарии'.$period_title);
        $sheet->mergeCells('A1:H1');
        $sheet->getStyle("A1")->getFont()->setSize(14)->setBold(true);

        $sheet->setCellValue("A2", 'Номер вопроса');
        $sheet->setCellValue("B2", 'Дата вопроса');
        $sheet->setCellValue("C2", 'Вопрос');
        $sheet->setCellValue("D2", 'Ответ');
        $sheet->setCellValue("E2", 'Комментарий');
        $sheet->setCellValue("F2", 'Дата комментария');
        $sheet->setCellValue("G2", 'Информация о дилере');
        $sheet->setCellValue("H2", 'E-mail дилера');

        $sheet->getStyle('A2:H2')->getFill()->setFillType(
            PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyle('A2:H2')->getFill()->getStartColor()->setRGB('EEEEEE');
        $sheet->getStyle('A2:H2')->getAlignment()->setHorizontal(
            PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(50);
        $sheet->getColumnDimension('D')->setWidth(50);
        $sheet->getColumnDimension('E')->setWidth(50);
        $sheet->getColumnDimension('F')->setWidth(30);
        $sheet->getColumnDimension('G')->setWidth(50);
        $sheet->getColumnDimension('H')->setWidth(35);

        $c_field = 3;


        while($item = $res->GetNextElement()) {
            $item = array_merge($item->GetFields(), $item->GetProperties());
            CIBlockElement::SetPropertyValuesEX($item['ID'],37,array("QST_UPLOAD"=>'Y',"REQ_COMM_CLOSED"=>'Y'));
            $arRes = CIBlockElement::GetList(Array('CREATED' => 'ASC'), Array('IBLOCK_ID' => 39, 'NAME' => $item['NAME'], 'ACTIVE' => 'Y','PROPERTY_COMM_UPLOADED' => 'Y'));
            $i = 1;
            $comm_arr = Array();
            if(intval($arRes->SelectedRowsCount()) > 0) {
                while ($comment = $arRes->GetNextElement()) {
                    $comment = array_merge($comment->GetFields(), $comment->GetProperties());
                    if (isset($comment)) {

                        $comm_arr[$i]['date'] = date('d.m.Y H:i', $comment['DATE_CREATE_UNIX']);
                        $comm_arr[$i]['dealer'] = $comment['COMM_NAME']['~VALUE'];
                        $comm_arr[$i]['email'] = $comment['COMM_EMAIL']['VALUE'];
                        $comm_arr[$i]['comment'] = $comment['COMM_TEXT']['~VALUE']['TEXT'];
                    }
                    $i++;
                    CIBlockElement::SetPropertyValuesEX($comment['ID'],39,array("COMM_UPLOADED"=>'N'));
                }
            }
            if($item['REQ_COMM_EMAIL']['VALUE'] != '') {

                $dealer_email = $item['REQ_COMM_EMAIL']['VALUE'];
                $dealer_name = '';

                if($item['MY_CITY']['VALUE'] == 3109) {
                    $dealer_name = get_dealer_phone($dealer_email)['addr'];
                } else {
                    if($item['REQ_COMM_ID']['VALUE'] != '') {
                        $res_dealer = CIBlockElement::GetList(Array("SORT"=>"ASC"), Array("IBLOCK_ID"=>6, "ACTIVE"=>"Y","ID"=>$item['REQ_COMM_ID']['VALUE']), false, Array(), Array());
                    } else {
                        $res_dealer = CIBlockElement::GetList(Array("SORT"=>"ASC"), Array("IBLOCK_ID"=>6, "ACTIVE"=>"Y","PROPERTY_city"=>$item['MY_CITY']['VALUE'],Array('LOGIC'=>'OR',Array('PROPERTY_qs_email'=>$dealer_email),Array('PROPERTY_email'=>$dealer_email))), false, Array(), Array());
                    }

                    $ob_dealer = $res_dealer->GetNextElement();
                    if($ob_dealer) {
                        $dealer = array_merge($arFields = $ob_dealer->GetFields(),$arFields = $ob_dealer->GetProperties());
                        $dealer_name = $dealer['~NAME'];
                    }
                }

                $comm_arr[$i]['date'] = '';
                $comm_arr[$i]['dealer'] = $dealer_name;
                $comm_arr[$i]['email'] = $dealer_email;
                $comm_arr[$i]['comment'] = '';

            }

            if(count($comm_arr) > 1) {
                $sheet->mergeCells("A".$c_field.":A".($c_field+count($comm_arr)-1));
                $sheet->mergeCells("B".$c_field.":B".($c_field+count($comm_arr)-1));
                $sheet->mergeCells("C".$c_field.":C".($c_field+count($comm_arr)-1));
                $sheet->mergeCells("D".$c_field.":D".($c_field+count($comm_arr)-1));
            }
            //номер вопроса
            $sheet->setCellValue("A".$c_field, $item['NAME']);
            $sheet->getStyle("A".$c_field)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getCell("A".$c_field)->getHyperlink()->setUrl('https://'.$_SERVER['HTTP_HOST'].'/question_service/answer.php?ext_id='.$item['EXTERNAL_ID']['VALUE'].'');
            $sheet->getCell("A".$c_field)->getHyperlink()->setTooltip('Перейти');
            $sheet->getStyle("A".$c_field)->applyFromArray(
                array(
                    'font' => array(
                        'color' => array(
                            'rgb' => '0000FF'
                        ),
                        'underline' => 'single'
                    )
                )
            );
            //дата вопроса
            $sheet->setCellValue("B".$c_field, mb_strimwidth($item['QST_DATE']['VALUE'],0,10));
            $sheet->getStyle("B".$c_field)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
            //вопрос
            $sheet->setCellValue("C".$c_field, str_replace(Array(';','<br/>'),Array(',',' '),html_entity_decode($item['QST']['~VALUE']['TEXT'])));
            $sheet->getStyle("C".$c_field)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP)->setWrapText(true);
            //ответ
            $sheet->setCellValue("D".$c_field, str_replace(Array(';','<br/>'),Array(',',' '),html_entity_decode($item['ANSW']['~VALUE']['TEXT'])));
            $sheet->getStyle("D".$c_field)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP)->setWrapText(true);
            foreach($comm_arr as $c=>$comm) {
                //комментарий
                $sheet->setCellValue("E".($c_field+$c-1), str_replace(Array(';','<br/>'),Array(',',' '),html_entity_decode($comm['comment'])));
                $sheet->getStyle("E".($c_field+$c-1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP)->setWrapText(true);
                //дата комментария
                $sheet->setCellValue("F".($c_field+$c-1), $comm['date']);
                $sheet->getStyle("F".($c_field+$c-1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
                //информация о дилере
                $sheet->setCellValue("G".($c_field+$c-1), str_replace(';',',',htmlspecialchars_decode($comm['dealer'])));
                $sheet->getStyle("G".($c_field+$c-1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP)->setWrapText(true);
                //e-mail дилера
                $sheet->setCellValue("H".($c_field+$c-1), str_replace(';',',',htmlspecialchars_decode($comm['email'])));
                $sheet->getStyle("H".($c_field+$c-1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP)->setWrapText(true);
            }



            $c_field += count($comm_arr);

        }

        $objWriter = PHPExcel_IOFactory::createWriter($xls, 'Excel2007');
        $objWriter->save($file_order);

        $spec_subj = 'Отчет по вопросам, к которым запрошены комментарии';

        $txt = '<p>Здравствуйте! Во вложении отчёт по вопросам, к которым запрошены комментарии'.$period_title.'</p>';


        $send_arr = Array(
            //'d.portu.by@yandex.ru',
            'nadida.hi@yandex.ru',
            //'A.Bruk@decor-evroplast.ru',
            //'o.gmirya@decor-evroplast.ru',
            //'a.chilichihin@decor-evroplast.ru',
            's.burova@decor-evroplast.ru',
            'd.mescheryakova@decor-evroplast.ru',
            //'L.Osetrova@decor-evroplast.ru',
            //'n.ovchinnikova@decor-evroplast.ru'
        );
        //$email = 'A.Bruk@decor-evroplast.ru,o.gmirya@decor-evroplast.ru,s.burova@decor-evroplast.ru,d.mescheryakova@decor-evroplast.ru,L.Osetrova@decor-evroplast.ru,n.ovchinnikova@decor-evroplast.ru';
        $email = 'A.Bruk@decor-evroplast.ru,s.burova@decor-evroplast.ru,d.mescheryakova@decor-evroplast.ru,L.Osetrova@decor-evroplast.ru,n.ovchinnikova@decor-evroplast.ru';
        $hidden_email = 'nadida.hi@yandex.ru,d.portu.by@yandex.ru';

        //$email = 'o.gmirya@decor-evroplast.ru';
        //$hidden_email = 'nadida.hi@yandex.ru';

        $fields = array('EMAIL'=>$email, "HIDDEN_EMAIL"=>$hidden_email, 'SUBJ'=>$spec_subj,'TEXT'=>$txt);
        if($_SERVER['HTTP_HOST'] == 'eplast.loc') {
            CEvent::SendImmediate("NEED_COMMENT_REPORT_NEW", s1, $fields, "N");
        } else {
            CEvent::Send("NEED_COMMENT_REPORT_NEW", s1, $fields, "N","", array($file_download));
        }

   }

}

