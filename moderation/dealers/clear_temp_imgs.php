<?
$day = date('d');
$unixYesterday = date('U',mktime()-24*60*60);
//$unixYesterday = date('U',mktime()-0.1*60*60);

$arFilter = Array("IBLOCK_ID"=>35, "CODE"=>"clear_temp");
$res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), Array());
while($ob = $res->GetNextElement()) {
    $counter = array_merge($arFields = $ob->GetFields(),$arFields = $ob->GetProperties());
}

function delDir($dir) {
    $files = array_diff(scandir($dir), ['.','..']);
    foreach ($files as $file) {

        (is_dir($dir.'/'.$file)) ? delDir($dir.'/'.$file) : unlink($dir.'/'.$file);
    }
    return rmdir($dir);
}

if($counter['COUNT_VAL']['VALUE'] != $day) {
    $path = $_SERVER["DOCUMENT_ROOT"].'/upload/dealers/temp/';
    $dirs = array_diff(scandir($path), ['.','..']);
    foreach ($dirs as $dir) {
        $dirDate = date("U", filemtime($path.'/'.$dir));
        if($dirDate <= $unixYesterday) {
            delDir($path.'/'.$dir);

            //print_r($dir.' - '. date('F d Y H:i:s',filemtime($path.'/'.$dir)));
            //echo '<br>';
        }
    }
    CIBlockElement::SetPropertyValuesEX($counter['ID'],35,Array('COUNT_VAL'=>$day));
}