<?
/**
 * Ежемесячный отчёт по Справочной (кол-во вопросов по темам)
 */
//ini_set('display_errors', 1);
//error_reporting(E_ALL);
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}

$arFilterQst = Array("IBLOCK_ID"=>38, "ACTIVE"=>"Y","CODE" => "qst_monthly");
$resQst = CIBlockElement::GetList(Array(), $arFilterQst, false, Array(), Array());
while($obQst = $resQst->GetNextElement()) {
    $itemQst = array_merge($arFieldsQst = $obQst->GetFields(),$arFieldsQst = $obQst->GetProperties());
    $prev_month_nmbr = $itemQst['COUNT_NUMBER']['VALUE'];
    $prev_month_id = $itemQst['ID'];
}

if($prev_month_nmbr != date('m')) {

    CIBlockElement::SetPropertyValuesEX($prev_month_id,38, array("COUNT_NUMBER" => date('m')));

    $now = new DateTime();
    $previousMonthFirst = $now->modify('first day of previous month');
    $previous_month = $previousMonthFirst->format('m');
    $previous_year = $previousMonthFirst->format('Y');
    $start_date = $previousMonthFirst->format('Y-m-d').' 00:00:00';

    $finish_date = date("Y-m-d", strtotime("last day of previous month")).' 23:59:59';

    function get_month_name($month) {
        $res = $month;
        switch ($month) {
            case "01": $res = "Январь"; break;
            case "02": $res = "Февраль"; break;
            case "03": $res = "Март"; break;
            case "04": $res = "Апрель"; break;
            case "05": $res = "Май"; break;
            case "06": $res = "Июнь"; break;
            case "07": $res = "Июль"; break;
            case "08": $res = "Август"; break;
            case "09": $res = "Сентябрь"; break;
            case "10": $res = "Октябрь"; break;
            case "11": $res = "Ноябрь"; break;
            case "12": $res = "Декабрь"; break;
        }
        return $res;
    }


    $list = Array("0"=>array(
        "Период",
        "Тема",
        "Количество",
    ));
    $n = 1;
    $subj_arr = Array(
        'Ассортимент и уточнение по размерам'=>0,
        'Монтаж изделий'=>0,
        "Свойства изделий"=>0,
        'Претензии и вопросы по заказам и сервису'=>0,
        "Гарантийные обязательства"=>0,
        "Работа магазинов"=>0,
        "Другое"=>0,
    );
    $arFilterQst = Array(
        "IBLOCK_ID"=>37,
        "ACTIVE"=>"Y",
        "!PROPERTY_QST_STATUS"=>'Вопрос перенаправлен',
        array(
            "LOGIC" => "AND",
            array('>=PROPERTY_QST_DATE' => $start_date),
            array('<=PROPERTY_QST_DATE' => $finish_date),
        ),);
    $resQst = CIBlockElement::GetList(Array("NAME"=>"ASC"), $arFilterQst, false, Array(), Array());

    while($obQst = $resQst->GetNextElement()) {
        $itemQst = array_merge($arFieldsQst = $obQst->GetFields(),$arFieldsQst = $obQst->GetProperties());

        if(array_key_exists($itemQst['QST_SUBJ']['VALUE'],$subj_arr)) {
            $subj_arr[$itemQst['QST_SUBJ']['VALUE']]++;
        }
    }

    $i = 0;
    foreach($subj_arr as $s=>$subj) {
        $period = $i++ == 0 ? get_month_name($previous_month).' '.$previous_year : '';
        $list[$n++] = Array(
            $period,
            $s,
            $subj
        );
    }


    $fp = fopen($_SERVER["DOCUMENT_ROOT"].'/reports/questions_stat_monthly.csv', 'w');
    foreach ($list as $fields) {
        fputs($fp, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM
        fputcsv($fp, $fields, ';', ' ');
    }
    fclose($fp);

    $spec_subj = 'Статистика по вопросам за '.mb_strtolower(get_month_name($previous_month));

    $txt = '<p>Здравствуйте! Во вложении статистика по вопросам за '.mb_strtolower(get_month_name($previous_month)).'.</p>';


    $email = 'n.denisova@decor-evroplast.ru';
    $hidden_email = 'nadida.hi@yandex.ru,d.portu.by@yandex.ru';

    $fields = array('EMAIL'=>$email, "HIDDEN_EMAIL"=>$hidden_email, 'SUBJ'=>$spec_subj,'TEXT'=>$txt);
    if($_SERVER['HTTP_HOST'] == 'eplast.loc') {
        CEvent::SendImmediate("NEED_COMMENT_REPORT_NEW", s1, $fields, "N");
    } else {
        CEvent::Send("NEED_COMMENT_REPORT_NEW", s1, $fields, "N","", array('/reports/questions_stat_monthly.csv'));
    }


}