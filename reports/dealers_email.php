<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/include/phone.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog") || !CModule::IncludeModule("search")) {
    exit;
}
$arFilter = Array("IBLOCK_ID"=>9, "ACTIVE"=>"Y");
$res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, Array(), Array());
$n = 1;
$list = Array("0"=>array(
    "ID",
    "Страна",
    "Город",
    "Название",
    "E-mail для Справочной",
    "E-mail для заказа",
));
while($ob = $res->GetNextElement()) {
    $country = array_merge($arFields = $ob->GetFields(), $arFields = $ob->GetProperties());
    $country_name = $country['NAME'];
    $arCityFilter = array('IBLOCK_ID' => 7, 'ACTIVE' => 'Y', 'PROPERTY_country' => Array($country['ID']));
    $e_city_list = CIBlockElement::GetList(array('NAME' => 'ASC'), $arCityFilter,false, Array(), Array());
    while($e_city = $e_city_list->GetNextElement()) {
        $e_city = array_merge($e_city->GetFields(), $e_city->GetProperties());
        $city_name = $e_city['NAME'];
        $_GET['loc'] = $e_city['map']['VALUE'];

        $no_print = true;
        $my_city = $e_city['ID'];

        require($_SERVER["DOCUMENT_ROOT"] . "/ajax/getdealers.php");
       // print_r($items);

        //$dealer = Array();
        foreach ($items as $k => $v) {
            if ($k == 'city' || $k == 'discountregion' || $k == 'point') continue;
            $dealer = $v['point'][0];
            //print_r($items);
            //echo '<br>';
            break;
        }

        if ($dealer) {
            $orderemail = $dealer['orderemail']['VALUE'] != '' ? $dealer['orderemail']['VALUE'] : $dealer['email']['VALUE'];
            $qs_email = $dealer['qs_email']['VALUE'] != '' ? $dealer['qs_email']['VALUE'] : $orderemail;
            print_r($country_name. ' - '. $city_name . ' - '.$dealer['trade_point']['VALUE'].' ('.$dealer['organization']['VALUE'].')'. ' - '.$qs_email. ' - '.$orderemail);
            echo '<br>';
            $list[$n++] = Array(
                $dealer['ID'],
                str_replace(';',',',htmlspecialchars_decode($country_name)),
                str_replace(';',',',htmlspecialchars_decode($city_name)),
                str_replace(';',',',htmlspecialchars_decode($dealer['trade_point']['VALUE'].' ('.$dealer['organization']['VALUE'].')')),
                str_replace(';',',',htmlspecialchars_decode($qs_email)),
                str_replace(';',',',htmlspecialchars_decode($orderemail))
            );
        }
    }


};
$fp = fopen($_SERVER["DOCUMENT_ROOT"].'/reports/dealers_email.csv', 'w');
foreach ($list as $fields) {
    //fputs($fp, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM
    fputcsv($fp, $fields, ';', ' ');
}
fclose($fp);






















    /*$city_dealers = $item['dealers_list']['VALUE'];
    if($city_dealers != '') {
        foreach($city_dealers as $id) {
            $res_dealer = CIBlockElement::GetList(Array("SORT"=>"ASC"), Array("IBLOCK_ID"=>6, "ACTIVE"=>"Y","ID"=>$id), false, Array(), Array());
            while($ob_dealer = $res_dealer->GetNextElement()) {
                $dealer = array_merge($arFields = $ob_dealer->GetFields(),$arFields = $ob_dealer->GetProperties());
                $dealer_email = $dealer['orderemail']['~VALUE']?$dealer['orderemail']['~VALUE']:$dealer['email']['~VALUE'];
                $dealer_phones = '';
                $phones = explode(';',$dealer['phones']['~VALUE']);
                foreach($phones as $p=>$phone) {
                    $dealer_phones .= str_phone($phone);
                    if($p+1 < count($phones)) $dealer_phones .= ', ';
                }
                //echo $i++.' - '.$dealer['NAME'].' - '.$dealer['address']['~VALUE'].' - '.$dealer['trade_point']['~VALUE'].' - '.$dealer['organization']['~VALUE'].' - '.$dealer['phones']['~VALUE'].' - '.$dealer_email.'<br>';
                $list[$n++] = Array(
                    htmlspecialchars_decode($city_name),
                    str_replace(';',',',htmlspecialchars_decode($dealer['~NAME'])),
                    str_replace(';',',',htmlspecialchars_decode($dealer['address']['~VALUE'])),
                    str_replace(';',',',htmlspecialchars_decode($dealer['trade_point']['~VALUE'])),
                    str_replace(';',',',htmlspecialchars_decode($dealer['organization']['~VALUE'])),
                    str_replace(';',',',htmlspecialchars_decode($dealer_phones)),
                    str_replace(';',',',htmlspecialchars_decode($dealer_email))
                );
            }
        }
    } else {
        $res_dealer = CIBlockElement::GetList(Array("SORT"=>"ASC"), Array("IBLOCK_ID"=>6, "ACTIVE"=>"Y","PROPERTY_city"=>$item['ID']), false, Array("nPageSize"=>1), Array());
        while($ob_dealer = $res_dealer->GetNextElement()) {
            $dealer = array_merge($arFields = $ob_dealer->GetFields(),$arFields = $ob_dealer->GetProperties());
            $dealer_email = $dealer['orderemail']['~VALUE']?$dealer['orderemail']['~VALUE']:$dealer['email']['~VALUE'];
            $dealer_phones = '';
            $phones = explode(';',$dealer['phones']['~VALUE']);
            foreach($phones as $p=>$phone) {
                $dealer_phones .= str_phone($phone);
                if($p+1 < count($phones)) $dealer_phones .= ', ';
            }
            //echo $i++.' - '.$dealer['NAME'].' - '.$dealer['address']['~VALUE'].' - '.$dealer['trade_point']['~VALUE'].' - '.$dealer['organization']['~VALUE'].' - '.$dealer['phones']['~VALUE'].' - '.$dealer_email.'<br>';
            $list[$n++] = Array(
                htmlspecialchars_decode($city_name),
                str_replace(';',',',htmlspecialchars_decode($dealer['~NAME'])),
                str_replace(';',',',htmlspecialchars_decode($dealer['address']['~VALUE'])),
                str_replace(';',',',htmlspecialchars_decode($dealer['trade_point']['~VALUE'])),
                str_replace(';',',',htmlspecialchars_decode($dealer['organization']['~VALUE'])),
                str_replace(';',',',htmlspecialchars_decode($dealer_phones)),
                str_replace(';',',',htmlspecialchars_decode($dealer_email))
            );
        }
    }
}
$fp = fopen($_SERVER["DOCUMENT_ROOT"].'/upload/dealer2021.csv', 'w');
foreach ($list as $fields) {
    fputs($fp, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM
    fputcsv($fp, $fields, ';', ' ');
}
fclose($fp);*/?>


<a href="/reports/dealers_email.csv">Скачать</a>