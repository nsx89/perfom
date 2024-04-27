<?php
require($_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/main/include/prolog_before.php');
if (!CModule::IncludeModule('iblock')) exit;


$models_path = $_SERVER["DOCUMENT_ROOT"] . "/cron/catalog/data/models";
$models_web_path = "/cron/catalog/data/models";


$name = serialize($_POST);
$name = md5($name);
$zipName = $_SERVER["DOCUMENT_ROOT"] . '/upload/archive/custom-' . $name . '.zip';
$downloadName = '/upload/archive/custom-' . $name . '.zip';


if (!file_exists($zipName)) {
    $zip = new ZipArchive();
    $handle = $zip->open($zipName, ZipArchive::CREATE | ZipArchive::OVERWRITE | ZipArchive::CM_STORE);
    if (is_array($_POST['d3max'])) {
        foreach ($_POST['d3max'] as $fname => $on) {
            $file = $models_path . "/max/" . $fname;
            $zip->addFile($file, $fname);
        }
    }
    if (is_array($_POST['arch'])) {
        foreach ($_POST['arch'] as $fname => $on) {
            $file = $models_path . "/gsm/" . $fname;
            $zip->addFile($file, $fname);
        }
    }
    if (is_array($_POST['autocad'])) {
        foreach ($_POST['autocad'] as $fname => $on) {
            $file = $models_path . "/dwg/" . $fname;
            $zip->addFile($file, $fname);
        }
    }

    $zip->close();
}

LocalRedirect($downloadName);
?>
