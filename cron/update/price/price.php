<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}

exit;

$table = array();
if (($fp = fopen("files/price.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($fp, 0, ";")) !== FALSE) {
        $table[] = $data;
    }
    fclose($fp);
}


foreach ($table AS $item) {
    
    $i = trim(iconv('windows-1251//IGNORE', 'UTF-8//IGNORE', $item[0]));

    $articul = trim(iconv('windows-1251//IGNORE', 'UTF-8//IGNORE', $item[1]));
    $articul = preg_replace('/[^0-9.-]/', '', $articul);

    $price = trim(iconv('windows-1251//IGNORE', 'UTF-8//IGNORE', $item[6]));
    $price = (int)preg_replace('/\s+/', '', $price);

    $flex = trim(iconv('windows-1251//IGNORE', 'UTF-8//IGNORE', $item[2]));

    if (empty($articul)) continue;

    $arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, 'PROPERTY_ARTICUL' => $articul);
    if ($flex == 'F') {
        $arFilter['PROPERTY_FLEX'] = 'Y';
    }
    else {
        $arFilter['!PROPERTY_FLEX'] = 'Y';
    }
    $ID = 'не найден';
    $PRICE_OLD = '';
    $res = CIBlockElement::GetList(Array(), $arFilter);
    if ($res->SelectedRowsCount() > 0) {
        $row = $res->GetNextElement();
        $item = $row->getFields();
        $prop = $row->getProperties();
        $ID = $item['ID'];
        
        $PRICE_OLD = CPrice::GetBasePrice($ID)['PRICE'];
        $PRICE_OLD = (int)preg_replace('/[^0-9.-]/', '', $PRICE_OLD);

        if ($PRICE_OLD <> $price && !empty($price)) {
            $arFields = Array(
                "PRODUCT_ID" => $ID,
                "CATALOG_GROUP_ID" => 1,
                "PRICE" => $price,
                "CURRENCY" => "RUB"
            );
            $price_res = CPrice::GetList(
                array(),
                array(
                    "PRODUCT_ID" => $ID,
                    "CATALOG_GROUP_ID" => 1
                )
            );
            if ($price_row = $price_res->Fetch()) {
                CPrice::Update($price_row['ID'], $arFields);
            }
            else {
                CPrice::Add($arFields);
            }

            echo 'Артикул: '.$articul.' | ID: '.$ID.' | FLEX: '.$flex.' | '.$PRICE_OLD.' => '.$price.'<br>';
            exit;
        }
    }
    else {
        continue;
    }
}


?>