<?
require_once("../head.php");

global $DB;

/*
$res = $DB->Query("SELECT * FROM `m_media` WHERE `category` IN(1)");
while ($row = $res->fetch()) {
    $id = (int)$row['id'];
    $views = (int)$row['views'];
    $likes = (int)$row['likes'];
    
    $views_default = rand(20, 50);
    $views_default += 100;

    $views += 100;

    //$likes_default = rand(5, 20);

    $query = "UPDATE `m_media` SET 
        `views_default` = '{$views_default}' 
        , `views` = '{$views}' 
    WHERE `id` = '{$id}'";
    $DB->Query($query);

    //echo $views_default.'=';
    //echo $id;

    echo $query;
    echo '<br>';

    //exit;
}
*/

exit;

$res = $DB->Query("SELECT * FROM `m_media`");
while ($row = $res->fetch()) {
    $id = (int)$row['id'];
    $views = (int)$row['views'];
    $likes = (int)$row['likes'];
    
    $views_default = rand(20, 50);
    $likes_default = rand(5, 20);

    $query = "UPDATE `m_media` SET 
        `views_default` = '{$views_default}' 
        , `views` = '{$views_default}' 
        , `likes_default` = '{$likes_default}' 
        , `likes` = '{$likes_default}' 
    WHERE `id` = '{$id}'";

    $DB->Query($query);

    //echo $views_default.'=';
    //echo $id;

    echo $query;
    echo '<br>';

    //exit;
}



