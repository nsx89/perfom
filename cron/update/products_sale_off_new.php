<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}

exit;

$ARTS = array();

$table = array();
if (($fp = fopen("sale/sale_off.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($fp, 0, ";")) !== FALSE) {
        $table[] = $data;
    }
    fclose($fp);
}

foreach ($table AS $item) {
    $art = trim($item[2]);

    $art = trim(iconv('windows-1251//IGNORE', 'UTF-8//IGNORE', $art));

    if (empty($art)) continue;

    //echo $art.' = '.$plus.'<br>';

    if ($art == 'Артикул') continue;

    $ARTS[] = trim($art);
}
 


$IDS = array();
$arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, 'PROPERTY_ARTICUL' => $ARTS);
$res = CIBlockElement::GetList(Array(), $arFilter);
while ($row = $res->GetNextElement()) {

    $item = $row->getFields();
    $prop = $row->getProperties();

    //echo '<pre>'; print_r($item); exit;

    echo 'ID:'.$item['ID'].'  =   articul ='.$prop['ARTICUL']['VALUE'].'  =  TAGS '.$item['TAGS'].'<br>';

    /*$el = new CIBlockElement;
    $el->Update($item['ID'], Array(
        "TAGS" => 'OFF',   
    ));*/
    
    //exit;
}


//echo implode(',', $IDS);



?>