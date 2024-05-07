<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}


exit;

/*

$el = new CIBlockElement;

$ITEM = array();
$file = file('products_import.txt');
foreach ($file AS $item) {
    $arr = explode('=', $item);

    if (substr($arr[0], 0, 1) == '~') continue;
    
    $ITEM[$arr[0]] = trim($arr[1]);
}


$PROP = array();
$file = file('products_import_prop.txt');
foreach ($file AS $item) {
    $arr = explode('=', $item);

    if (substr($arr[0], 0, 1) == '~') continue;
    
    $PROP[$arr[0]] = $arr[1];
}


$ARTICUL = trim($PROP['ARTICUL']);
if (empty($ARTICUL)) exit;

$arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, 'PROPERTY_ARTICUL' => $ARTICUL);
$res = CIBlockElement::GetList(Array(), $arFilter);
if ($res->SelectedRowsCount() > 0) {
    $row = $res->GetNextElement();
    $product = $row->getFields();
    $product_id = $product['ID'];
}

$arLoadProductArray = Array(  
    'MODIFIED_BY' => $ITEM['MODIFIED_BY'],
    'IBLOCK_SECTION_ID' => $ITEM['IBLOCK_SECTION_ID'],
    'IBLOCK_ID' => $ITEM['IBLOCK_ID'],
    'NAME' => $ITEM['NAME'],  
    'ACTIVE' => 'Y', 
    'PREVIEW_TEXT' => $ITEM['PREVIEW_TEXT'],  
    'DETAIL_TEXT' => $ITEM['DETAIL_TEXT'],  
    'PREVIEW_PICTURE' => $ITEM['PREVIEW_PICTURE'],
    'DETAIL_PICTURE' => $ITEM['DETAIL_PICTURE'],
    'SORT' => $ITEM['SORT'],
    'SEARCHABLE_CONTENT' => $ITEM['SEARCHABLE_CONTENT'],
    'WF_STATUS_ID' => $ITEM['WF_STATUS_ID'],
    'CODE' => $ITEM['CODE'],
    'TAGS' => $ITEM['TAGS'],
    'XML_ID' => $ITEM['XML_ID'],
);

/*
echo '<pre>';
print_r($ITEM);
echo '</pre>';

echo '<pre>';
print_r($arLoadProductArray);
echo '</pre>';
*/

if (empty($product_id)) {
    echo 'add:';
    $product_id = $el->Add($arLoadProductArray);
}
else {
    echo 'upd:';
    $el->Update($product_id, $arLoadProductArray);
}

if (empty($product_id)) exit;

foreach ($PROP AS $key => $property) {
    $property = trim($property);

    if (empty($property)) continue;

    //echo $key.'='.$property.'<br>';

    CIBlockElement::SetPropertyValueCode($product_id, $key, $property);
}


echo $product_id;




/* --- PRICE --- */
$PRICE = 2010;

if (!empty($PRICE)) {
    $arFields = Array(
        "PRODUCT_ID" => $product_id,
        "CATALOG_GROUP_ID" => 1,
        "PRICE" => $PRICE,
        "CURRENCY" => "RUB"
    );
    $res = CPrice::GetList(
        array(),
        array(
            "PRODUCT_ID" => $product_id,
            "CATALOG_GROUP_ID" => 1
        )
    );
    if ($arr = $res->Fetch()) {
        echo 'udddd';
        CPrice::Update($product_id, $arFields);
    }
    else {
        CPrice::Add($arFields);
        echo 'adddd';
    }
}

/* --- // --- */

/*
big/100
100
40
20
30
39


*/


?>