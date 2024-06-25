<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock')) {
    exit;
}
require_once($_SERVER["DOCUMENT_ROOT"] . "/include/phone.php");
ob_start();
?>
<section class="pacc-stat orders-list event-list no-margin">
    <?
    $arFilter = Array("IBLOCK_CODE"=>"dealer2023","ACTIVE"=>"Y");
    $res = CIBlockElement::GetList(Array(),$arFilter);
    ?>
    <div class="orders-list-table-wrapper">
        <table class="event-table">
        <tr class="order-table-title">
            <th>№</th>
            <th>ФИО</th>
            <th style="width:120px;">Город</th>
            <th>Компания</th>
            <th>Должность <br>(профессия)</th>
            <th style="width:160px;">Tелефон</th>
            <th>E-mail</th>
            <th>Экскурсия</th>
            <th>Транспорт</th>
            <th>Конференция</th>
            <th>Банкет</th>
        </tr>
        
        <? 
        $n = 1;
        $new_list = Array("0"=>array("ФИО","Город","Компания","Должность","Tелефон","E-mail","Экскурсия","Транспорт","Конференция","Банкет"));
        while($item = $res->GetNextElement()) {
            $item = array_merge($item->GetFields(), $item->GetProperties());
            //print_r($item);
            $stat_phone = ' '.str_phone($item['PHONE']['VALUE']);
            $transport = '';
            if($item['TRANSPORT']['VALUE'] == 'bus') $transport = 'Корпоративный автобус';
            if($item['TRANSPORT']['VALUE'] == 'auto') $transport = 'Личный автомобиль';
            $new_list[$n] = Array($item['~NAME'],$item['CITY']['VALUE'],$item['COMPANY']['~VALUE'],$item['POST']['VALUE'],$stat_phone,$item['EMAIL']['VALUE'],$item['EVENT_1']['VALUE']=="Y"?"Да":"Нет",$transport,$item['EVENT_2']['VALUE']=="Y"?"Да":"Нет",$item['EVENT_3']['VALUE']=="Y"?"Да":"Нет");
        ?>
            <tr>
                <td><?=$n++?></td>
                <td><?=$item['NAME']?></td>
                <td><?=$item['CITY']['VALUE']?></td>
                <td><?=$item['COMPANY']['VALUE']?></td>
                <td><?=$item['POST']['VALUE']?></td>
                <td><?=$stat_phone?></td>
                <td><?=$item['EMAIL']['VALUE']?></td>
                <td><?=$item['EVENT_1']['VALUE']=="Y"?"Да":"Нет"?></td>
                <td><?=$transport?></td>
                <td><?=$item['EVENT_2']['VALUE']=="Y"?"Да":"Нет"?></td>
                <td><?=$item['EVENT_3']['VALUE']=="Y"?"Да":"Нет"?></td>
            </tr>
        <? } ?>
    </table>
    </div>
</section>

<? $stat_html = ob_get_clean();

if($_REQUEST['type'] == 'show') {
    print json_encode($stat_html);
}
if($_REQUEST['type'] == 'dwnld') {
    //$new_list = Array('0'=>Array(1,2,3,4));
    $fp = fopen($_SERVER["DOCUMENT_ROOT"].'/moderation/dealer2023.csv', 'w');
    foreach ($new_list as $fields) {
        fputs($fp, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM
        fputcsv($fp, $fields, ';', ' ');
    }
    fclose($fp);
    print 'save';
}
?>