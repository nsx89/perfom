<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/include/phone.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog") || !CModule::IncludeModule("search")) {
    exit;
}
$arFilter = Array("IBLOCK_ID"=>7, "ACTIVE"=>"Y");
$res = CIBlockElement::GetList(Array("NAME"=>"ASC"), $arFilter, false, Array(), Array());
$n = 1;
$list = Array("0"=>array(
    "Город",
    "Название",
    "Адрес",
    "Точка продажи",
    "Организация",
    "Телефоны",
    "E-mail"
));
while($ob = $res->GetNextElement()) {
    $item = array_merge($arFields = $ob->GetFields(),$arFields = $ob->GetProperties());
    if($item['ID'] == 3109) continue;
    $city_name = $item['NAME'];
    $city_dealers = $item['dealers_list']['VALUE'];
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
fclose($fp);

?>
<a href="/upload/dealer2021.csv">Скачать</a>
