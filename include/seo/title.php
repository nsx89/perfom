<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


exit;

$seo = array();
if (($fp = fopen($_SERVER['DOCUMENT_ROOT']."/include/seo/title/table.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($fp, 0, ";")) !== FALSE) {
        $seo[] = $data;
    }
    fclose($fp);
}
foreach ($seo AS $item) {
    $url = $item[0];
    $url = str_replace('https://perfom-decor.ru', '', $url);
    //$url .= '/';
    $title = $item[1];


    $title = iconv('windows-1251//IGNORE', 'UTF-8//IGNORE', $title);

    if (empty($title) || $title == 'Заголовок' || $title == 'Title') continue;

    echo "case '{$url}': 
        \$title = '{$title}'; 
        break;";
    echo "<br>";
}

?>