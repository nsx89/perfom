<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


exit;

$seo = array();
if (($fp = fopen($_SERVER['DOCUMENT_ROOT']."/include/seo/tables/seo_table.csv", "r")) !== FALSE) {
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
    $description = $item[2];
    $keywords = $item[3];
    $title = iconv('windows-1251//IGNORE', 'UTF-8//IGNORE', $title);
    $description = iconv('windows-1251//IGNORE', 'UTF-8//IGNORE', $description);
    $keywords = iconv('windows-1251//IGNORE', 'UTF-8//IGNORE', $keywords);

    if (empty($title) || $title == 'Заголовок') continue;

    echo "case '{$url}': 
        \$title = '{$title}'; 
        \$description = '{$description}'; 
        \$keywords = '{$keywords}'; 
        break;";
    echo "<br>";
}

?>