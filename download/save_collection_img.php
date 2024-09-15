<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock')) exit;

$collection = 'MAURITANIA';
$new_path = '/download/mauritania_05022024/';
$old_path = '/cron/catalog/data/images/';

//$folders = Array('20','30','39','40','41','100','big');
$old_dir = $_SERVER["DOCUMENT_ROOT"].$old_path;
$new_dir = $_SERVER["DOCUMENT_ROOT"].$new_path;
$folders = scandir($old_dir);
$folders[] = 'big/100';
$folders[] = 'big/600';


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
        if($fold == '39') {
            $pref = '.31';
            print_r($fold."<br>");
        }
        if($fold == 'big/100') $pref = '.100-b';
        if($fold == 'big/600') $pref = '.600-b';
        //print_r($pref."<br>");
        if(file_exists($old_dir.$fold)) {
            $name = $item['ARTICUL']['VALUE'].$pref.'.png';
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