<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}


exit;

$dir = $_SERVER["DOCUMENT_ROOT"]."/cron/catalog/data/models2/pdf";
$files = scandir($dir);

unset($files[array_search('.', $files, true)]);
unset($files[array_search('..', $files, true)]);

$names = '';
foreach($files as $index => $file){
	$file_new = str_replace('.2d-Model-RU-Модель', '', $file);
	rename($dir.'/'.$file, $dir.'/'.$file_new);
	$names .= basename($file_new).', ';
}

$names = trim($names, ', ');

echo $names;



$dir = $_SERVER["DOCUMENT_ROOT"]."/cron/catalog/data/models2/dwg";
$files = scandir($dir);

unset($files[array_search('.', $files, true)]);
unset($files[array_search('..', $files, true)]);

$names = '';
foreach($files as $index => $file){
	$file_new = str_replace('.2d-Model-RU', '', $file);
	rename($dir.'/'.$file, $dir.'/'.$file_new);
	$names .= basename($file_new).', ';
}

$names = trim($names, ', ');

echo $names;

?>