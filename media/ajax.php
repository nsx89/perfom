<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock')) exit;

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/media/classes/media_pages.php');

use Media\Media;
 
global $DB;
global $APPLICATION;

set_time_limit(2);

$method = processing($_POST['method']);
switch ($method) {
    case 'items_load':
        $start = processing($_POST['start']);
        $step = processing($_POST['step']);
        $limit = $start.','.$step;

        $order = processing_base(base64_decode($_POST['order']));
        $where = processing_base(base64_decode($_POST['where']));

        $items = Media::list($order, $where, $limit);
        foreach ($items AS $item) {
            echo Media::siteItem($item); 
        }
        break;
    case 'question_history':
        $session_id = bitrix_sessid();
        $question_id = (int)processing($_POST['question_id']);
        $answer_id = (int)processing($_POST['answer_id']);
        $value = (int)processing($_POST['value']);
        $res = $DB->Query("SELECT * FROM `m_media_question_history` WHERE 
            `session_id` = '{$session_id}' 
            AND `question_id` = '{$question_id }'
            AND `answer_id` = '{$answer_id }'
        ");
        if ($res->SelectedRowsCount() == 0) {
            $DB->Query("INSERT INTO `m_media_question_history` SET 
                `session_id` = '{$session_id}' 
                , `question_id` = '{$question_id}'
                , `answer_id` = '{$answer_id}'
                , `value` = '{$value}'
            ");
        }
        else {
            $row = $res->Fetch();
            $id = $row['id'];
            $DB->Query("UPDATE `m_media_question_history` SET 
                `value` = '{$value}'
            WHERE `id` = '{$id}'");
        }
        break;
    case 'question_result':  
        $question_id = (int)processing($_POST['question_id']);
        $res = $DB->Query("SELECT * FROM `m_media_answer` WHERE `question_id` = '{$question_id}' ORDER BY id ASC");
        while ($row = $res->Fetch()) {

            $answer_id = $row['id'];

            $kres = $DB->Query("SELECT count(*) AS kol FROM `m_media_question_history` WHERE `question_id` = '{$question_id}' AND `value` = 1 ORDER BY id ASC");
            $krow = $kres->Fetch();
            $kol = $krow['kol'];

            $vres = $DB->Query("SELECT count(*) AS checked FROM `m_media_question_history` WHERE `question_id` = '{$question_id}' AND `answer_id` = '{$answer_id}' AND `value` = 1 ORDER BY id ASC");
            $vrow = $vres->Fetch();
            $checked = $vrow['checked'];

            $proc = 0;
            if ($kol > 0) $proc = round($checked / $kol * 100);

            echo '<div class="m-media-quiz-answer"><span>'.$proc.'%</span> '.$row['name'].'</div>';
        }
        break;
    case 'media_likes':  
        $item_id = (int)processing($_POST['item_id']);
        $value = (int)processing($_POST['value']);

        $likes_add = false;
        $media_likes = (array)unserialize($_COOKIE['media_likes']);
        if (empty($media_likes)) {
            $media_likes = [$item_id];
            setcookie('media_likes', serialize($media_likes), time() + 86400 * 30, '/', $_SERVER['HTTP_HOST']);
            $likes_add = true; 
        }
        else {
            if (!in_array($item_id, $media_likes)) $likes_add = true;  
            $media_likes[] = $item_id;
            $media_likes = array_unique($media_likes);
            setcookie('media_likes', serialize($media_likes), time() + 86400 * 30, '/', $_SERVER['HTTP_HOST']);
        }
        if ($likes_add) {
            $res = $DB->Query("SELECT likes FROM `m_media` WHERE `id` = '{$item_id}' LIMIT 1");
            $row = $res->Fetch();
            $likes = (int)$row['likes'];
            $likes++;
            $DB->Query("UPDATE `m_media` SET `likes` = '{$likes}' WHERE `id` = '{$item_id}'");
        }
        //удаление
        elseif ($value == 0) {
            $res = $DB->Query("SELECT likes FROM `m_media` WHERE `id` = '{$item_id}' LIMIT 1");
            $row = $res->Fetch();
            $likes = (int)$row['likes'];
            $likes--;
            $DB->Query("UPDATE `m_media` SET `likes` = '{$likes}' WHERE `id` = '{$item_id}'");
            $media_likes = (array)unserialize($_COOKIE['media_likes']);
            $media_likes_new = array_diff($media_likes, array($item_id));
            setcookie('media_likes', serialize($media_likes_new), time() + 86400 * 30, '/', $_SERVER['HTTP_HOST']);
        }
        break;
    case 'media_flag':  
        $item_id = (int)processing($_POST['item_id']);
        $value = (int)processing($_POST['value']);

        $flag_add = false;
        $media_flag = (array)unserialize($_COOKIE['media_flag']);
        if (empty($media_flag)) {
            $media_flag = [$item_id];
            setcookie('media_flag', serialize($media_flag), time() + 86400 * 30, '/', $_SERVER['HTTP_HOST']);
            $flag_add = true; 
        }
        else {
            if (!in_array($item_id, $media_flag)) $flag_add = true;  
            $media_flag[] = $item_id;
            $media_flag = array_unique($media_flag);
            setcookie('media_flag', serialize($media_flag), time() + 86400 * 30, '/', $_SERVER['HTTP_HOST']);
        }
        if ($flag_add) {
            $res = $DB->Query("SELECT flag FROM `m_media` WHERE `id` = '{$item_id}' LIMIT 1");
            $row = $res->Fetch();
            $flag = (int)$row['flag'];
            $flag++;
            $DB->Query("UPDATE `m_media` SET `flag` = '{$flag}' WHERE `id` = '{$item_id}'");
        }
        //удаление
        elseif ($value == 0) {
            $res = $DB->Query("SELECT flag FROM `m_media` WHERE `id` = '{$item_id}' LIMIT 1");
            $row = $res->Fetch();
            $flag = (int)$row['flag'];
            $flag--;
            $DB->Query("UPDATE `m_media` SET `flag` = '{$flag}' WHERE `id` = '{$item_id}'");
            $media_flag = (array)unserialize($_COOKIE['media_flag']);
            $media_flag_new = array_diff($media_flag, array($item_id));
            setcookie('media_flag', serialize($media_flag_new), time() + 86400 * 30, '/', $_SERVER['HTTP_HOST']);
        }
        break;
    case 'search_items':
        set_time_limit(1);
        $search = processing($_POST['search']);
        if (!empty($search)) {
            if (strlen($search) <= 2) $where .= " AND `name` LIKE '".strtoupper($search)."%'";
            else $where .= " AND `name` LIKE '%{$search}%'";
        }
        $items = Media::list('', $where, 10);
        if (count($items) > 0) {
            foreach($items AS $item) {
                $link = Media::siteLink($item);
                $name = $item['name'];
                $name = str_ireplace($search, '<span>'.$search.'</span>', $name);
                echo '<a href="'.$link.'" class="m-media-search-item">'.$name.'</a>';
            }
            echo '<span class="m-media-search-item m-media-search-item-all">Перейти к результатам</span>';
        }
        else {
            echo '<span class="m-media-search-item m-media-search-item-not-found">Ничего не найдено</span>';
        }
        break;
    case 'complain':
        $name = processing($_POST['name']);
        $phone = processing($_POST['phone']);
        $email = processing($_POST['email']);
        $text = processing($_POST['text']);
        $url = processing($_POST['url']);
        if (empty($name) || empty($email) || empty($text)) exit;
        $res = $DB->Query("INSERT INTO `m_media_complain` SET
            `date` = '".time()."' 
            , `name` = '{$name}'
            , `phone` = '{$phone}'
            , `email` = '{$email}'
            , `text` = '{$text}'
            , `url` = '{$url}'
        ");
        CEvent::SendImmediate("E_COMPLAIN_SERV", 's1', array(
            'URL' => $url,
            'NAME' => $name,
            'PHONE' => $phone,
            'EMAIL' => $email,
            'TEXT' => $text
        ), "N");
        break;
    default:  
        LocalRedirect('/404.php');
        break;
}
?>