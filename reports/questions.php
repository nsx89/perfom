<?
/**
 * выгрузка заказов по регионам
 */
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}
$arFilter = Array(
    "IBLOCK_ID"=>37,
    "ACTIVE"=>"Y",
    "!PROPERTY_QST_STATUS"=>'Вопрос перенаправлен',
    array(
        "LOGIC" => "AND",
        array('>=PROPERTY_QST_DATE' => "2021-10-01 00:00:00"),
        array('<=PROPERTY_QST_DATE' => "2022-02-17 23:59:59"),
    ),);
$res = CIBlockElement::GetList(Array("NAME"=>"ASC"), $arFilter, false, Array(), Array());
$n = 1;
$list = Array("0"=>array(
    "Дата вопроса",
    "Вопрос",
    "Ответ",
    "Город"
));
while($ob = $res->GetNextElement()) {
    $item = array_merge($arFields = $ob->GetFields(),$arFields = $ob->GetProperties());
    //if($item['CHOOSEN_REG']['VALUE'] == 3109) continue;

    $new_arFilter = Array('IBLOCK_ID' => 7, 'ACTIVE' => 'Y', 'ID' => $item['MY_CITY']['VALUE']);
    $new_db_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $new_arFilter);
    $new_ip_loc = $new_db_list->GetNextElement();
    if ($new_ip_loc) $new_ip_loc = array_merge($new_ip_loc->GetFields(), $new_ip_loc->GetProperties());
    $cur_loc = $new_ip_loc['NAME'];

    //date
    $date = explode(' ',$item['QST_DATE']['VALUE'])[0];

    //echo $n++.' - '.$date.' - '.$item['QST']['~VALUE']['TEXT'].' - '.$item['ANSW']['~VALUE']['TEXT'].' - '.$reg['NAME'].'<br>';

    $list[$n++] = Array(
        str_replace(';',',',htmlspecialchars($date)),
        str_replace(Array(';','<br/>'),Array(',',' '),html_entity_decode($item['QST']['~VALUE']['TEXT'])),
        str_replace(Array(';','<br/>'),Array(',',' '),html_entity_decode($item['ANSW']['~VALUE']['TEXT'])),
        str_replace(';',',',htmlspecialchars($cur_loc))
    );

}
$fp = fopen($_SERVER["DOCUMENT_ROOT"].'/reports/questions.csv', 'w');
foreach ($list as $fields) {
    fputs($fp, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM
    fputcsv($fp, $fields, ';', ' ');
}
fclose($fp);

?>
<h1>Выгрузка заказов по регионам за период с 01.01.2020 по 30.04.2021</h1>
<a href="/reports/questions.csv">Скачать</a>
