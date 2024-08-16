<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}

exit;


//$COUNTRY = 'KZH';
//$COUNTRY = 'KGS';
$COUNTRY = 'UZS';

$table = array();
if (($fp = fopen("files/{$COUNTRY}.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($fp, 0, ";")) !== FALSE) {
        $table[] = $data;
    }
    fclose($fp);
}


foreach ($table AS $item) {
    
    $i = trim(iconv('windows-1251//IGNORE', 'UTF-8//IGNORE', $item[0]));
    $name = trim(iconv('windows-1251//IGNORE', 'UTF-8//IGNORE', $item[1]));
    $price = trim(iconv('windows-1251//IGNORE', 'UTF-8//IGNORE', $item[2]));
    $edin = trim(iconv('windows-1251//IGNORE', 'UTF-8//IGNORE', $item[3]));

    $price = (int)preg_replace('/\s+/', '', $price);
    $articul = preg_replace('/[^0-9.-]/', '', $name);

    $arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, 'PROPERTY_ARTICUL' => $articul);

    $flex = 'N';
    if (mb_strpos($name, 'ГИБКИЙ') !== false) {
        $flex = 'Y';
        $arFilter['PROPERTY_FLEX'] = 'Y';
    }
    else {
        $arFilter['!PROPERTY_FLEX'] = 'Y';
    }

    if (empty($price) || empty($articul)) continue;

    $ID = 'не найден';
    $PRICE_OLD = '';
    $res = CIBlockElement::GetList(Array(), $arFilter);
    if ($res->SelectedRowsCount() > 0) {
        $row = $res->GetNextElement();
        $item = $row->getFields();
        $prop = $row->getProperties();
        $ID = $item['ID'];
        
        //echo '<pre>'; print_r($prop); echo '</pre>'; exit;
        
        $PRICE_OLD = $prop[$COUNTRY]['VALUE'];
    }

    echo $name.' | '.$articul.' | '.$price.' | FLEX: '.$flex.' | ID: '.$ID.' | PRICE: '.$PRICE_OLD.' => '.$price.'<br>';

    if ($ID > 0) {
        //CIBlockElement::SetPropertyValueCode($ID, $COUNTRY, $price);
    }
    else {
        //echo $name.' | '.$articul.' | '.$price.' | FLEX: '.$flex.' | ID: '.$ID.' | PRICE: '.$PRICE_OLD.' => '.$price.'<br>';
    }

    exit;
}


?>