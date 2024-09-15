<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}

$f_name = 'product_sort_update_4.csv';
echo 'stop'; exit;

if (($fp = fopen($f_name, "r")) !== FALSE) {
    while (($data = fgetcsv($fp, 0, ";")) !== FALSE) {
        $import_items[] = $data;
    }
    fclose($fp);
    echo 'import open<br>';
}

$i = 45;
foreach ($import_items as $import_item) {
//if ($i>48) break; //  временный стоп

$item_article = explode(' ', trim($import_item[0]));
$item_article = trim($item_article[count($item_article)-1]);

$arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, 'PROPERTY_ARTICUL' => $item_article, 'PROPERTY_FLEX' => 'N');
$db_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter);
while ($ob = $db_list->GetNextElement()) {
$item = $ob->GetFields();

$el = new CIBlockElement;
$res = $el->Update($item['ID'], array('SORT' => $i));

echo $item['SORT'].' '.$item_article.'<br>';

}


// echo $i.' - 0:'.$import_item[0].' 1:'.$import_item[1].' 2:'.$import_item[2].' 3:'.$import_item[3].' 4:'.$import_item[4].' 5:'.$import_item[5].' 6:'.$import_item[6].' 7:'.(trim($import_item[7])*1).'<br>';

$i++;
}




echo $i.' import close<br>';
?>