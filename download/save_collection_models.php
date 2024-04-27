<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock')) exit;

$collection = 'NEW_ART_DECO';
$new_path = '/download/NAD_models/';
$old_path = '/cron/catalog/data/models/';

//$folders = Array('20','30','39','40','41','100','big');
$old_dir = $_SERVER["DOCUMENT_ROOT"].$old_path;
$new_dir = $_SERVER["DOCUMENT_ROOT"].$new_path;
$folders = scandir($old_dir);



//print_r($folders);

$db = CIBlockElement::GetList(Array(),Array('IBLOCK_ID'=>IB_CATALOGUE, 'PROPERTY_'.$collection=>'Y'));
print_r($db->SelectedRowsCount());
echo '<br>';
while($ob = $db->GetNextElement()) {
    $item = array_merge($ob->GetFields(), $ob->GetProperties());
    //print_r($item['ARTICUL']['VALUE']);
    //echo '<br>';
    foreach($folders as $fold) {
        //print_r($fold."<br>");
        $pref = '.'.$fold;
        //print_r($pref."<br>");
        if(file_exists($old_dir.$fold)) {
            $name = $item['ARTICUL']['VALUE'].$pref;
            //print_r($old_dir.$fold.'/'.$name."<br>");
            if(file_exists($old_dir.$fold.'/'.$name)) {
                if(!file_exists($new_dir.$fold)) mkdir($new_dir.$fold);
                if (!copy($old_dir.$fold.'/'.$name, $new_dir.$fold.'/'.$name)) {
                    echo "не удалось скопировать $old_dir$fold/$name...\n";
                }
            }
        }
    }
}
print_r('<br>Done!');