<?
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}

$models_path = $_SERVER["DOCUMENT_ROOT"] . "/cron/catalog/data/models/";
$models_web_path = "/cron/catalog/data/models/";

set_time_limit(6000);

$arFilter = Array("IBLOCK_ID"=>12, "ACTIVE"=>"Y","DEPTH_LEVEL"=>2);
$db_list = CIBlockSection::GetList(Array($by=>$order), $arFilter, true, Array('UF_*'));

$extension_arr = Array('max','gsm','dwg','3ds','obj');

while($ar_result = $db_list->GetNext()) {
    if($ar_result['ID'] != 1542 && $ar_result['ID'] != 1544) continue; /*только карнизы и молдинги*/

    $sec_code = $ar_result['CODE'];

    $prod_arr = Array();
    $arFilter = Array("IBLOCK_ID"=>12, "ACTIVE"=>"Y", "IBLOCK_SECTION_ID"=>$ar_result['ID']);
    $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), Array());
    while($ob = $res->GetNextElement()) {
        $prod_arr[] = array_merge($arFields = $ob->GetFields(), $arFields = $ob->GetProperties());
    }

    foreach($extension_arr as $ext) {

        $zipName = $_SERVER["DOCUMENT_ROOT"] . '/upload/archive_full/evroplast_' . $sec_code . '_' . $ext . '.zip';
        $downloadName = '/upload/archive_full/evroplast_' . $sec_code . '_' . $ext . '.zip';

        $n = 0;

        $zip = new ZipArchive();
        $zip->open($zipName, ZipArchive::CREATE | ZipArchive::OVERWRITE | ZipArchive::CM_STORE);
        foreach($prod_arr as $prod) {
            $file = $models_path.$ext.'/'.$prod['ARTICUL']['VALUE'].'.'.$ext;
            $fname = $prod['ARTICUL']['VALUE'].'.'.$ext;
            if(file_exists($file)) {
                $zip->addFile($file, $fname);
                $n++;
            }
        }
        $zip->close();

        if($n == 0) {
            unlink($zipName);
        } else {
            echo '<a href="'.$downloadName.'">Скачать архив evroplast_' . $sec_code . '_' . $ext . '.zip ('.round(filesize($zipName)/1048576,2).' MB)</a>';
            echo '<br>';
        }
    }
}
