<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule("iblock");

$el = new CIBlockElement;


exit;


$table = array();
if (($fp = fopen("files/all.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($fp, 0, ";")) !== FALSE) {
        $table[] = $data;
    }
    fclose($fp);
}

//echo '<pre>'; print_r($table); echo '</pre>';

$i = 1;

$ids = [];

foreach ($table AS $item) {
    $name = $item[1];
    $link = $item[2];

    $link_new = str_replace('evroplast.ru', 'perfom-decor.ru', $link);
    //if (empty($link)) continue;

    //echo '<pre>'; print_r($item); echo '</pre>';
    
    $explode = explode('/', $link);
    $explode_count = count($explode);
    $code = @$explode[$explode_count - 2];

    if (empty($code) && empty($name)) continue;

    if (empty($code) && !empty($name)) {
        $articul = trim(preg_replace('/[^0-9.-]/', '', $name));
        if (!empty($articul)) {
            $arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, 'PROPERTY_ARTICUL' => $articul);
            $res = CIBlockElement::GetList(Array(), $arFilter);
            if ($res->SelectedRowsCount() > 0) {
                $row = $res->GetNextElement();
                $item = $row->getFields();
                $prop = $row->getProperties();
                if (!empty($item['CODE'])) {
                    $code = $item['CODE'];
                }
            }
        }
    }

    if (empty($code)) continue;

    $arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, 'CODE' => $code);
    $res = CIBlockElement::GetList(Array(), $arFilter);
    if ($res->SelectedRowsCount() > 0) {
        while ($row = $res->GetNextElement()) {
            $item = $row->getFields();
            $prop = $row->getProperties();
            
            //echo '<pre>'; print_r($item); echo '</pre>';
            
            //echo 'ID:'.$item['ID'].'  =   Артикул: '.$prop['ARTICUL']['VALUE'].'                       =                               active ='.$item['ACTIVE'].'= no_order ='.$prop['NO_ORDER']['VALUE'].' = link = '.$link_new.'<br>';

            $ids[] = $item['ID'];

            //CIBlockElement::SetPropertyValueCode($item['ID'], "NO_ORDER", '');
        }
    }
    else {
        //echo 'NOT_FOUND'.$code.'<br>';
    }

    //exit;
}

$ids = array_unique($ids);

//echo count($ids);

//224 ids
/*
$arFilter = Array('IBLOCK_ID' => IB_CATALOGUE);
$res = CIBlockElement::GetList(Array(), $arFilter);
if ($res->SelectedRowsCount() > 0) {
    while ($row = $res->GetNextElement()) {
        $item = $row->getFields();
        $prop = $row->getProperties();
        $item_id = $item['ID'];
        if (!in_array($item_id, $ids)) {
            CIBlockElement::SetPropertyValueCode($item_id, "ACTIVE_EVROPLAST", $item['ACTIVE']);
            
            $el = new CIBlockElement;
            $el->Update($item_id, Array(
                "ACTIVE" => "N",   
            ));

            echo 'ID: '.$item['ID'].'  =   Артикул: '.$prop['ARTICUL']['VALUE'].'                       =                               active=N= no_order ='.$prop['NO_ORDER']['VALUE'].'<br>';

            //echo '<pre>'; print_r($item); echo '</pre>';
            //exit;
        }
        else {
            if ($item['ACTIVE'] <> 'Y') {
                $el = new CIBlockElement;
                $el->Update($item_id, Array(
                    "ACTIVE" => "Y",   
                ));

                echo 'ID: '.$item['ID'].'  =   Артикул: '.$prop['ARTICUL']['VALUE'].'                       =                               active=Y= no_order ='.$prop['NO_ORDER']['VALUE'].'<br>';
            }
        }
    }
}
*/

$arFilter = Array('IBLOCK_ID' => IB_CATALOGUE);
$res = CIBlockElement::GetList(Array(), $arFilter);
echo $res->SelectedRowsCount();

?>