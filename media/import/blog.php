<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/media/classes/custom_admin.php');

use Custom\Admin;

if (!CModule::IncludeModule('iblock')) exit;


exit;

global $DB;

$dir = $_SERVER['DOCUMENT_ROOT'].MEDIA_FOLDER.'/upload/m_media/';
chdir($dir);

$i = 0;

$arSelect = Array();
$arFilter = Array("IBLOCK_ID"=>48, "ACTIVE"=>"Y", ">=DATE_CREATE"=>'01.01.2022 00:00:00');
$res = CIblockElement::GetList(Array("ID" => "DESC"), $arFilter, false, array(), $arSelect);
 while($ob = $res->GetNextElement()) {

    $i++;

    if ($i == 100) exit;

   $item = $ob->GetFields();
   $prop = $ob->GetProperties();
   
   $ITEM_ID = $item['ID'];
   $NAME = $item['NAME'];
   $CATEGORY_NAME = mb_strtolower($prop['NEWS_TAGS']['VALUE'][0] ?? '');
   $DATE = $prop['DATE']['VALUE'] ?? '';
   $DATE .= ' 00:00';
   $DATE = strtotime($DATE);
 
   $SHORT = $item['PREVIEW_TEXT'];
   $FOLDER = $prop['FOLDER']['VALUE'];
   $THUMB = $prop['THUMB']['VALUE'];
   $HORIZONTAL_BANNER = $prop['HORIZONTAL_BANNER']['VALUE'];

   $preview = 'https://evroplast.ru/mag/news_articles/';
   $preview .= $FOLDER;
   $preview .= '/'.$THUMB;

   $detail = '';
   if (!empty($HORIZONTAL_BANNER)) {
       $detail = 'https://evroplast.ru/mag/news_articles/';
       $detail .= $FOLDER;
       $detail .= $HORIZONTAL_BANNER;
   }
   else {
       $detail = $preview;
   }

   $text = $item['~DETAIL_TEXT'];

   //$CODE = $item['CODE'];
   $CODE = Admin::rus_to_eng($NAME);

   $date_add = $item['DATE_CREATE_UNIX'];
   $date_edit = $item['TIMESTAMP_X_UNIX'];

   /*echo '<pre>';
   print_r($item);
   echo '</pre>';*/

   //echo $NAME.'<br>';
   //if ($NAME <> '«Европласт» на выставке MosBuild 2023') continue;

    $category = 0;
    switch ($CATEGORY_NAME) {
        case 'советы': $category = 1; break;
        case 'мероприятия': $category = 4; break;
        case 'выставки': $category = 8; break;
        case 'новость': $category = 13; break;
    }
    if (empty($category)) {
        continue;
        /*$category_res = $DB->Query("SELECT * FROM `m_media_category` WHERE `name` = '{$CATEGORY_NAME)}' LIMIT 1");
        if ($category_res->SelectedRowsCount() > 0) {
            $category_row = $category_res->fetch();
            $category = $category_row['id'];
        }*/
    }
    //if (empty($category)) echo 'категория не найдена: '.$CATEGORY_NAME;

    $str = ", `short` = '{$SHORT}'
            , `date` = '{$DATE}'
            , `category` = '{$category}'
            , `sort` = '500'
            , `date_add` = '{$date_add}'
            , `user_add` = '1'
            , `user_edit` = '1'
    ";

   $add = true;
   $media_res = $DB->Query("SELECT * FROM `m_media` WHERE `code` = '{$CODE}' LIMIT 1");
   if ($media_res->SelectedRowsCount() == 0) {
        $query = "INSERT INTO `m_media` SET 
            `active` = '1'
            , `name` = '{$NAME}'
            , `code` = '{$CODE}'
            {$str}
        ";
        echo $query;
        echo '<br>';
        //$DB->Query($query);
        $id = $DB->LastID();
   }
   else {
        $media_row = $media_res->fetch();
        $id = $media_row['id'];
        $category_id = $media_row['category'];

        $section_code = '';
        $category_res = $DB->Query("SELECT * FROM `m_media_category` WHERE `id` = '{$category_id}' LIMIT 1");
        $category_row = $category_res->Fetch();
        $section_code = $category_row['code'];

        //РЕДИРЕКТЫ
        $old = 'mag/'.$ITEM_ID.'/';
        $new = MEDIA_FOLDER.'/'.$section_code.'/'.$media_row['code'].'/';
        //echo 'RewriteRule ^'.$old.'$ '.$new.' [R=301,L]<br>';

        $old = '/mag/'.$ITEM_ID.'/';
        $new = MEDIA_FOLDER.'/'.$section_code.'/'.$media_row['code'].'/';
        $new = trim($new, '/').'/';
        echo 'RewriteRule ^'.$new.'$ '.$old.' [R=301,L]<br>';

        if ($id >= 253 && 1 == 2) {
            $parser_link = 'https://evroplast.ru/mag/'.$ITEM_ID;
            $parser_content = file_get_contents($parser_link);
            preg_match_all('#<title>(.+?)</title>#is', $parser_content, $parser_arr);
            $title = $parser_arr[1][0];
            preg_match_all('#<meta name=\"description\" content=\"(.+?)\">#is', $parser_content, $parser_arr);
            $description = $parser_arr[1][0];
            preg_match_all('#<meta name=\"keywords\" content=\"(.+?)\">#is', $parser_content, $parser_arr);
            $keywords = $parser_arr[1][0];
        
            $str .= "
                , `title` = '{$title}'
                , `description` = '{$description}'
                , `keywords` = '{$keywords}'
            ";

            $query = "UPDATE `m_media` SET 
                `active` = '1'
                {$str}
            WHERE `id` = '{$id}'";
            echo $query;
            echo '<br>';
            $DB->Query($query);   
        }

        $add = false;
   }    
   if ($add) {

       $type_id = 14;
       $cons_res = $DB->Query("SELECT * FROM `m_media_constructor` WHERE `media_id` = '{$id}' AND `type_id` = '{$type_id}' LIMIT 1");
       if ($cons_res->SelectedRowsCount() == 0) {
            $DB->Query("INSERT INTO `m_media_constructor` SET 
                `media_id` = '{$id}'
                , `type_id` = '{$type_id}'
                , `sort` = '500'
                , `active` = '1'
                , `text` = '".$DB->ForSql($text)."'
            ");
       }

       //фотка превью
       if (!empty($preview)) {
            $pathinfo = pathinfo($preview);
            $extension = $pathinfo['extension'];
            $preview_picture = time().'_'.md5(uniqid(rand(), true)).'.'.$extension;
            if (copy($preview, $preview_picture)) {
                $DB->Query("UPDATE `m_media` SET 
                    `preview_picture` = '{$preview_picture}'
                WHERE `id` = '{$id}'");
            }
            unlink($media_row['preview_picture']);
            Admin::image_resize($preview_picture, 780, 468, false);
       }

       //фотка детальная
       if (!empty($detail)) {
            $pathinfo = pathinfo($preview);
            $extension = $pathinfo['extension'];
            $detail_picture = time().'_'.md5(uniqid(rand(), true)).'.'.$extension;
            if (copy($detail, $detail_picture)) {
                $DB->Query("UPDATE `m_media` SET 
                    `detail_picture` = '{$detail_picture}'
                WHERE `id` = '{$id}'");
            }
            unlink($media_row['detail_picture']);
            Admin::image_resize($detail_picture, 1086, 1086, true);
       }
    }
 }


