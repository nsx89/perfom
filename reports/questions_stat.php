<?
/**
 * выгрузка статистики по вопросам
 */
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}
$date_arr = Array(
        Array('2021-01-01 00:00:00',"2021-01-31 23:59:59",'01.2021'),
        Array('2021-02-01 00:00:00',"2021-02-28 23:59:59",'02.2021'),
        Array('2021-03-01 00:00:00',"2021-03-31 23:59:59",'03.2021'),
        Array('2021-04-01 00:00:00',"2021-04-30 23:59:59",'04.2021'),
        Array('2021-05-01 00:00:00',"2021-05-31 23:59:59",'05.2021'),
        Array('2021-06-01 00:00:00',"2021-06-30 23:59:59",'06.2021'),
        Array('2021-07-01 00:00:00',"2021-07-31 23:59:59",'07.2021'),
        Array('2021-08-01 00:00:00',"2021-08-31 23:59:59",'08.2021'),
        Array('2021-09-01 00:00:00',"2021-09-30 23:59:59",'09.2021'),
        Array('2021-10-01 00:00:00',"2021-10-31 23:59:59",'10.2021'),
        Array('2021-11-01 00:00:00',"2021-11-30 23:59:59",'11.2021'),
        Array('2021-12-01 00:00:00',"2021-12-31 23:59:59",'12.2021'),
        Array('2022-01-01 00:00:00',"2022-01-31 23:59:59",'01.2022'),
        Array('2022-02-01 00:00:00',"2022-02-28 23:59:59",'02.2022'),
        Array('2022-03-01 00:00:00',"2022-03-31 23:59:59",'03.2022'),
        Array('2022-04-01 00:00:00',"2022-04-30 23:59:59",'04.2022'),
        Array('2022-05-01 00:00:00',"2022-05-31 23:59:59",'05.2022'),
        Array('2022-06-01 00:00:00',"2022-06-30 23:59:59",'06.2022'),
        Array('2022-07-01 00:00:00',"2022-07-31 23:59:59",'07.2022'),

);
$list = Array("0"=>array(
    "Период",
    "Тема",
    "Количество",
));
$n = 1;
foreach($date_arr as $date) {
    $subj_arr = Array(
        'Ассортимент и уточнение по размерам'=>0,
        'Монтаж изделий'=>0,
        "Свойства изделий"=>0,
        'Претензии и вопросы по заказам и сервису'=>0,
        "Гарантийные обязательства"=>0,
        "Работа магазинов"=>0,
        "Другое"=>0,
    );
    $arFilter = Array(
        "IBLOCK_ID"=>37,
        "ACTIVE"=>"Y",
        "!PROPERTY_QST_STATUS"=>'Вопрос перенаправлен',
        array(
            "LOGIC" => "AND",
            array('>=PROPERTY_QST_DATE' => $date[0]),
            array('<=PROPERTY_QST_DATE' => $date[1]),
        ),);
    $res = CIBlockElement::GetList(Array("NAME"=>"ASC"), $arFilter, false, Array(), Array());

    while($ob = $res->GetNextElement()) {
        $item = array_merge($arFields = $ob->GetFields(),$arFields = $ob->GetProperties());

        if(array_key_exists($item['QST_SUBJ']['VALUE'],$subj_arr)) {
            $subj_arr[$item['QST_SUBJ']['VALUE']]++;
        }
    }

    print_r($subj_arr);
    foreach($subj_arr as $s=>$subj) {
        $list[$n++] = Array(
            $date[2],
            $s,
            $subj
        );
    }

}



$fp = fopen($_SERVER["DOCUMENT_ROOT"].'/reports/questions_stat.csv', 'w');
foreach ($list as $fields) {
    //fputs($fp, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM
    fputcsv($fp, $fields, ';', ' ');
}
fclose($fp);

?>
<a href="/reports/questions_stat.csv">Скачать</a>
