<?php
namespace Custom;

use Custom\Paginate;

class Admin
{
	static function th($name){
		return '<th class="main-grid-cell-head main-grid-cell-left">
            <div class="main-grid-cell-inner">
                <span class="main-grid-cell-head-container">
                    <span class="main-grid-head-title">'.$name.'</span>
                </span>
            </div>
        </th>';
	}

    static function td($name, $link = ''){
        $td = '<td class="main-grid-cell main-grid-cell-left">
            <div class="main-grid-cell-inner">
                <span class="main-grid-cell-content">';
                    if (!empty($link)) {
                        $td .= '<strong><a href="'.$link.'" title="Редактировать элемент">'.$name.'</a></strong>';
                    }
                    else {
                        $td .= $name;
                    }
                $td .= '</span>
            </div>
        </td>';
        return $td;
    }

    static function search(){
        $search = self::processing($_GET['search']);
        $m_media_category = (int)self::processing($_GET['m_media_category']);
        $date_from = self::processing($_GET['date_from']);
        $date_to = self::processing($_GET['date_to']);
        $delete_show = false;
        if (!empty($search) || !empty($m_media_category) || !empty($date_from) || !empty($date_to)) $delete_show = true;
        return '<form action="'.$_SERVER["PHP_SELF"].'" method="GET" class="adm-toolbar-panel-flexible-space" id="form-filter">
            <div class="main-ui-filter-search main-ui-filter-theme-default main-ui-filter-set-inside main-ui-filter-search--active">
                <input type="text" tabindex="1" value="'.$search.'" name="search" placeholder="Поиск" class="main-ui-filter-search-filter" autocomplete="off">
                <div class="main-ui-item-icon-block '.($delete_show ? 'main-ui-show' : '').'">
                    <span class="main-ui-item-icon main-ui-search"></span>
                    <span class="main-ui-item-icon main-ui-delete js-main-ui-delete"></span>
                </div>
            </div>
            <input type="hidden" name="lang" value="'.LANGUAGE_ID.'">
            <input type="submit" value="1" class="main-ui-filter-submit">
        </form>';
    }

    static function not_found($colspan = 0){
        return '<tr class="main-grid-row main-grid-row-empty main-grid-row-body">
           <td class="main-grid-cell main-grid-cell-center" colspan="'.$colspan.'">
              <div class="main-grid-empty-block">
                 <div class="main-grid-empty-inner">
                    <div class="main-grid-empty-image"></div>
                    <div class="main-grid-empty-text">Ничего не найдено</div>
                 </div>
              </div>
           </td>
        </tr>';
    }

    static function not_found_edit(){
        return '<div class="main-grid-empty-block">
            <div class="main-grid-empty-inner">
                <div class="main-grid-empty-image"></div>
                <div class="main-grid-empty-text">Страницы не найдено</div>
            </div>
        </div>';
    }

    static function add($button = 'Добавить элемент'){
        return '<div class="adm-toolbar-panel-align-right">         
            <a class="ui-btn ui-btn-primary" href="'.$_SERVER["PHP_SELF"].'?lang='.LANGUAGE_ID.'&add=1">'.$button.'</a>
        </div>';
    }

    static function add_link(){
        return $_SERVER["PHP_SELF"].'?lang='.LANGUAGE_ID.'&add=1';
    }

    static function edit_link($id = ''){
        return $_SERVER['PHP_SELF'].'?lang='.LANGUAGE_ID.'&edit='.$id;
    }

    static function del_link($id = ''){
        return $_SERVER["PHP_SELF"].'?lang='.LANGUAGE_ID.'&del='.$id;
    }

    static function processing($value){
        global $DB;
        $value = trim($value);
        $value = preg_replace("/[\r\n]{3,}/i","\r\n\r\n", $value);
        $value = stripslashes($value);
        $value = strip_tags($value);
        $value = htmlspecialchars($value);
        $value = $DB->ForSql($value);
        return $value;
    }

    static function processing_code($value){
        global $DB;
        $value = trim($value);
        $value = $DB->ForSql($value);
        return $value;
    }

    static function getList($table) {
        global $DB;

        $where = $order ='';

        $search = self::processing($_GET['search']);
        if (!empty($search)) {
            $where .= " AND `name` LIKE '%{$search}%'";
        }

        $m_media_category = (int)self::processing($_GET['m_media_category']);
        if (!empty($m_media_category)) $where .= " AND `category` = '{$m_media_category}'";

        $date_from = self::processing($_GET['date_from']);
        if (!empty($date_from)) $where .= " AND `date` >= '".strtotime($date_from)."'";

        $date_to = self::processing($_GET['date_to']);
        if (!empty($date_to)) $where .= " AND `date` <= '".strtotime($date_to)."'";

        $query = "SELECT * FROM {$table} WHERE 1=1 {$where} ORDER BY id DESC";

        return Paginate::getList($query);
    }

    static function total($data){

        $list = $data['list'];
        $paginate = $data['paginate'];
        $total = $data['total'];

        if (empty($list)) return;

        return '<div class="main-grid-bottom-panels">
          <div class="main-grid-nav-panel">
             <div class="main-grid-panel-wrap">
                <table class="main-grid-panel-table">
                   <tbody>
                      <tr>
                         <td class="main-grid-panel-total main-grid-panel-cell main-grid-cell-left">
                            <div class="main-grid-panel-content"><span class="main-grid-panel-content-title">Всего:</span>&nbsp;<span class="main-grid-panel-content-text">'.$total.'</span></div>
                         </td>
                         <td class="main-grid-panel-cell main-grid-panel-cell-pagination main-grid-cell-left">
                            '.$paginate.'
                         </td>
                      </tr>
                   </tbody>
                </table>
             </div>
          </div>
       </div>';
    }

    static function back($id, $site_link = null, $events_show = true){
        $add = self::add_link();
        $del = self::del_link($id);
        if (!empty($site_link)) {
            $site_link = '<a href="'.$site_link.'" class="adm-detail-toolbar-btn-text adm-site-link" target="_blank">Посмотреть на сайте</a>';
        }
        $events = '';
        if ($events_show) {
            $events .= '<div class="adm-detail-toolbar-right js-adm-detail-toolbar-events" style="top: 0px;" data-add="'.$add.'" data-del="'.$del.'">
                <a href="javascript:void(0)"
                   hidefocus="true"
                   class="adm-btn adm-btn-add adm-btn-menu"
                   title="Действия с элементом инфоблока">
                   Действия
                </a>
            </div>';
        }
        return '<div class="adm-detail-toolbar">
         <a href="'.$_SERVER['PHP_SELF'].'?lang='.LANGUAGE_ID.'" class="adm-detail-toolbar-btn" title="" id="btn_list">
            <span class="adm-detail-toolbar-btn-l"></span><span class="adm-detail-toolbar-btn-text">Вернуться к списку</span><span class="adm-detail-toolbar-btn-r"></span>
            '.$site_link.'
         </a>
         '.$events.'
        </div>';
    }

    static function info($label, $value){
        if (empty($value)) return;
        return '<tr>
           <td width="40%" class="adm-detail-content-cell-l">'.$label.'</td>
           <td width="60%" class="adm-detail-content-cell-r">'.$value.'</td>
        </tr>';
    }

    static function info_user($user_id){
        $res = \CUser::GetByID($user_id);
        $row = $res->Fetch();
        return '&nbsp;&nbsp;&nbsp;[<a target="_blank" href="user_edit.php?lang=ru&amp;ID='.$user_id.'">1</a>]&nbsp;'.$row['NAME'];
    }

    static function input($name, $label, $value, $required = false, $id = false, $button_show = false, $size = '70'){
        if ($button_show) $button = '<img title="Генерация кода из названия" class="linked js-linked" src="/bitrix/themes/.default/icons/iblock/unlink.gif">';
        else $button = '';
        return '<tr id="tr_'.$name.'">
           <td width="40%" class="adm-detail-content-cell-l">
            <span class="'.($required ? 'adm-required-field' : '').'">'.$label.'</span>
           </td>
           <td class="adm-detail-content-cell-r">
            <input id="'.$id.'" type="text" name="'.$name.'" value="'.$value.'" data-value="'.$value.'" size="'.$size.'" maxlength="255" '.($required ? 'required' : '').'>
            '.$button.'
           </td>
        </tr>';
    }

    static function textarea($name, $label, $value, $required = false, $height = '80'){
       
        return '<tr id="tr_'.$name.'">
           <td width="40%" class="adm-detail-content-cell-l adm-detail-content-cell-l-top">
            <span class="'.($required ? 'adm-required-field' : '').'">'.$label.'</span>
           </td>
           <td class="adm-detail-content-cell-r">
                <textarea class="typearea" maxlength="500" style="width: 98.6%; height: '.$height.'px;" name="'.$name.'" '.($required ? 'required' : '').'>'.$value.'</textarea>
           </td>
        </tr>';
    }

    static function input_date($name, $label, $value, $required = false, $id = ''){
        if (!empty($value)) $value = date('d.m.Y H:i:s', $value);
        else $value = '';
        return '<tr id="tr_'.$name.'">
            <td class="adm-detail-content-cell-l">'.$label.'</td>
            <td class="adm-detail-content-cell-r">
                <div class="adm-input-wrap adm-input-wrap-calendar" onclick="BX.calendar({node:this, field:\''.$name.'\', form: \'\', bTime: true, bHideTime: false});">
                    <input class="adm-input adm-input-calendar" type="text" name="'.$name.'" size="22" value="'.$value.'">
                    <span class="adm-calendar-icon" title="Нажмите для выбора даты"></span>
                </div>
            </td>
        </tr>';
    }

    static function input_sort($name, $label, $value){
        return '<tr id="tr_'.$name.'">
           <td width="40%" class="adm-detail-content-cell-l">'.$label.'</td>
           <td class="adm-detail-content-cell-r"><input class="js-input-sort" type="text" name="'.$name.'" value="'.$value.'" size="7" maxlength="10" /></td>
        </tr>';
    }

    static function checkbox($name, $label, $value){
        $checked = '';
        if ($value == 1) $checked = 'checked=""';
        return '<tr id="tr_'.$name.'">
           <td width="40%" class="adm-detail-content-cell-l">'.$label.'</td>
           <td class="adm-detail-content-cell-r">
              <input type="hidden" name="'.$name.'" value="0" /> 
              <input type="checkbox" name="'.$name.'" value="1" '.$checked.' id="designed_checkbox_'.$name.'" class="adm-designed-checkbox" />
              <label class="adm-designed-checkbox-label" for="designed_checkbox_'.$name.'" title=""></label>
           </td>
        </tr>';
    }

    static function rus_to_eng($text) {
        $text = trim($text);
        $text = str_replace(array('А','а','Б','б','В','в','Г','г','Д','д','Е','е','Ё','ё','Ж','ж','З','з','И','и','Й','й','К','к','Л','л','М','м','Н','н','О','о','П','п','Р','р','С','с','Т','т','У','у','Ф','ф','Х','х','Ц','ц','Ч','ч','Ъ','ъ','Ы','ы','Ь','ь','Э','э','Ю','ю','Я','я','Ш','ш','Щ','щ'),
                    array('a','a','b','b','v','v','g','g','d','d','e','e','e','e','zh','zh','z','z','i','i','y','y','k','k','l','l','m','m','n','n','o','o','p','p','r','r','s','s','t','t','u','u','f','f','h','h','c','c','ch','ch','','','i','i','','','e','e','u','u','ya','ya','sh','sh','sch','sch'),
                   $text);
        $text = preg_replace("|[^a-z0-9\s]|i", "", $text);
        $text = preg_replace("|\s|", "-", $text);
        $text = str_replace("quot", "", $text);
        $text = mb_strtolower($text);
        return $text;
    }

    static function image($name, $label, $table, $row, $width = '', $height = ''){

        $src = '';
        if (!empty($row[$name])) $src = self::image_view($row[$name], $table, true);

        if ($name == 'detail_picture') {
            $w = '100%';
        }

        $alt = !empty($row[$name.'_alt']) ? $row[$name.'_alt'] : '';

        $td = '<tr id="tr_'.$name.'" class="adm-detail-file-row adm-image">
            <td width="40%" class="adm-detail-valign-top adm-detail-content-cell-l">'.$label.'</td>
            <td width="60%" class="adm-detail-content-cell-r">
                <div class="adm-fileinput-wrapper adm-fileinput-wrapper-single" style="width: '.$w.';">
                    <div class="adm-fileinput-area mode-pict adm-fileinput-drag-area" style="width: 99.5%; height: auto; max-width: 100%;">

                        <input class="js-file-img-del" type="hidden" name="img_del_'.$name.'" value="0" data-id="'.$row['id'].'" /> 
                        
                        <div class="js-adm-fileinput" style="'.(!empty($src) ? 'display: none;' : '').'">
                            <div class="adm-fileinput-area-container"></div>
                            <span class="adm-fileinput-drag-area-hint">
                                Выбрать изображение
                            </span>
                            <input class="adm-fileinput-drag-area-input js-adm-fileinput-input" type="file" name="img_file_'.$name.'" multiple="false" accept="image/*" />
                        </div>

                        <div class="js-adm-fileview adm-fileinput-area-container" style="width: 100%; '.(!empty($src) ? '' : 'display: none;').'">
                            <div class="adm-fileinput-item-wrapper bx-drag-draggable adm-fileinput-item-image" style="width: 98%;">
                                <div class="bx-bxu-thumb-thumb" style="width: 100%;">
                                    <div class="adm-fileinput-item" style="width: '.$width.'px;">
                                        <div class="adm-fileinput-item-preview" style="width: 100%;">
                                            <div class="adm-fileinput-item-preview-img">
                                                <span class="bx-bxu-thumb-preview">
                                                    <span class="bx-bxu-canvas" style="width: 100%;">
                                                        <img class="js-adm-fileinput-img" src="'.$src.'" style="width: 100%; position: static;" />
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="adm-fileinput-item-panel">
                                            <div class="adm-fileinput-item-panel-alt">
                                                Alt: <input class="" type="text" name="img_alt_'.$name.'" value="'.$alt.'" size="20" maxlength="255">
                                            </div>
                                            <span class="adm-fileinput-item-panel-btn adm-btn-del js-file-del" title="Убрать">&nbsp;</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    

                </div>
            </td>
        </tr>';
        return $td;
    }

    static function image_cropper($name, $label, $table, $row, $width, $height){

        $src = '';
        if (!empty($row[$name])) $src = self::image_view($row[$name], $table, true);

        $proc1 = '40';
        $proc2 = '60';
        $w = '820px';
        if ($name == 'detail_picture') {
            $proc1 = '20';
            $proc2 = '80';
            $w = '100%';
        }
        $alt = !empty($row[$name.'_alt']) ? $row[$name.'_alt'] : '';

        $td = '<tr id="tr_'.$name.'" class="adm-detail-file-row adm-cropper js-adm-cropper">
            <td width="'.$proc1.'%" class="adm-detail-valign-top adm-detail-content-cell-l">
                '.$label.'
            </td>
            <td width="'.$proc2.'%" class="adm-detail-content-cell-r">
                <div class="adm-fileinput-wrapper adm-fileinput-wrapper-single" style="width: '.$w.';">
                    <div class="adm-fileinput-area mode-pict adm-fileinput-drag-area" style="width: 99.5%; height: auto; /*min-height: '.$height.'px;*/ max-width: 100%;">

                        <input class="js-file-img-del" type="hidden" name="img_del_'.$name.'" value="0" data-id="'.$row['id'].'" /> 
                        
                        <div class="js-adm-fileinput" style="'.(!empty($src) ? 'display: none;' : '').'">
                            <div class="adm-fileinput-area-container"></div>
                            <span class="adm-fileinput-drag-area-hint">
                                Выбрать изображение
                            </span>
                            <input class="adm-fileinput-drag-area-input js-adm-fileinput-input" type="file" name="img_file_'.$name.'" multiple="false" accept="image/*" />
                        </div>

                        <div class="js-adm-fileview adm-fileinput-area-container" style="width: 100%; '.(!empty($src) ? '' : 'display: none;').'">
                            <div class="adm-fileinput-item-wrapper bx-drag-draggable adm-fileinput-item-image" style="width: 98%;">
                                <div class="bx-bxu-thumb-thumb" style="width: 100%;">
                                    <div class="adm-fileinput-item" style="width: '.$width.'px;">
                                        <input class="js-adm-cropper-x" type="hidden" name="'.$name.'_x">
                                        <input class="js-adm-cropper-y" type="hidden" name="'.$name.'_y">
                                        <input class="js-adm-cropper-width" type="hidden" name="'.$name.'_width">
                                        <input class="js-adm-cropper-height" type="hidden" name="'.$name.'_height">
                                        <input type="hidden" name="'.$name.'_cropper" value="1">
                                        <div class="cropper-image" style="width: 100%;">
                                            <img class="js-adm-fileinput-img js-adm-fileinput-img-crop" id="js-adm-fileinput-img-crop'.$name.'" src="'.$src.'" data-crop1="'.$width.'" data-crop2="'.$height.'" style="width: 100%;" />
                                        </div>
                                        <div class="adm-fileinput-item-panel">
                                            <div class="adm-fileinput-item-panel-alt">
                                                Alt: <input class="" type="text" name="img_alt_'.$name.'" value="'.$alt.'" size="20" maxlength="255">
                                            </div>
                                            <span class="adm-fileinput-item-panel-btn adm-btn-del js-file-del" title="Убрать">&nbsp;</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    

                </div>
            </td>
        </tr>';
        return $td;
    }

    static function image_upload($name, $table, $width, $height, $cropping, $id, $field = null) {
        global $DB;

        if (empty($field)) $field = $name;

        self::image_dir(MEDIA_FOLDER."/upload/{$table}/");

        $del = (int)$_POST['img_del_'.$name];
        if(!empty($del)) {
            $res = $DB->Query("SELECT * FROM `{$table}` WHERE id='{$del}' LIMIT 1");
            $row = $res->Fetch();
            if (!empty($row[$field])) {
                unlink(trim($row[$field]));
                $DB->Query("UPDATE {$table} SET `{$field}`='' WHERE `id`='{$del}'");
            }
        }
        
        $hash = time().'_'.md5(uniqid(rand(), true));

        $file = 'img_file_'.$name;

        $query = '';
        if (isset($_POST['img_alt_'.$name])) {
            $query .= ", `{$field}_alt` = '".Admin::processing($_POST['img_alt_'.$name])."'";
        }

        if (is_uploaded_file($_FILES[$file]['tmp_name'])) {
            $filename = $_FILES[$file]['name'];
            $path_info = pathinfo($filename);
            $extension = $path_info['extension'];

            $path = $hash.".".$extension;
            move_uploaded_file($_FILES[$file]['tmp_name'], $path);

            $name_cropper = Admin::processing($_POST[$name.'_cropper']);
            if (!empty($name_cropper)) {
                $x = (int)Admin::processing($_POST[$name.'_x']);
                $y = (int)Admin::processing($_POST[$name.'_y']);
                $crop_width = (int)Admin::processing($_POST[$name.'_width']);
                $crop_height = (int)Admin::processing($_POST[$name.'_height']);
                self::image_crop($path, $x, $y, $crop_width, $crop_height);
                self::image_resize($path, $width, $height, $cropping);
            }
            else {
                self::image_resize($path, $width, $height, $cropping);
            }
            
            if (!empty($id)) {
                $res = $DB->Query("SELECT * FROM `".$table."` WHERE id='{$id}' LIMIT 1");
                $row = $res->Fetch();
                if (!empty($row[$field])) {
                    unlink(trim($row[$field]));
                }
            }

            $query .= ", `{$field}` = '{$path}'";
        }
        return $query;
    }

    static function video_upload($name, $table, $id, $field = null) {
        global $DB;

        if (empty($field)) $field = $name;

        self::image_dir(MEDIA_FOLDER."/files/{$table}/");

        $del = (int)$_POST['video_del_'.$name];
        if(!empty($del)) {
            $res = $DB->Query("SELECT * FROM `{$table}` WHERE id='{$del}' LIMIT 1");
            $row = $res->Fetch();
            if (!empty($row[$field])) {
                unlink(trim($row[$field]));
                $DB->Query("UPDATE {$table} SET `{$field}`='' WHERE `id`='{$del}'");
            }
        }
        
        $hash = time().'_'.md5(uniqid(rand(), true));

        $file = 'video_file_'.$name;

        if (is_uploaded_file($_FILES[$file]['tmp_name'])) {
            $filename = $_FILES[$file]['name'];
            $path_info = pathinfo($filename);
            $extension = $path_info['extension'];

            $path = $hash.".".$extension;
            move_uploaded_file($_FILES[$file]['tmp_name'], $path);
            
            if (!empty($id)) {
                $res = $DB->Query("SELECT * FROM `".$table."` WHERE id='{$id}' LIMIT 1");
                $row = $res->Fetch();
                if (!empty($row[$field])) {
                    unlink(trim($row[$field]));
                }
            }

            return ", `{$field}` = '{$path}'";
        }
    }

    static function image_dir($dir) {

        $dir = $_SERVER['DOCUMENT_ROOT'].$dir;

        if(!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        chdir($dir);
    }

    static function image_view($path, $table, $only_src = false) {
        $src = '';
        if (!empty($path)) {
            $src = MEDIA_FOLDER."/upload/{$table}/".$path;
            if ($only_src) return $src;
            return "<img src='{$src}' alt='' style='max-width: 110px; max-height: 60px;'>";
        }
        return $src;
    }

    static function image_resize($image, $w = false, $h = false, $cropping = false) {
        if (($w < 0) || ($h < 0)) {
            echo "Некорректные входные параметры"; return false;
        }
        list($w_i, $h_i, $type) = getimagesize($image); // Получаем размеры и тип изображения (число)
        $types = array("", "gif", "jpeg", "png"); // Массив с типами изображений
        $ext = $types[$type]; // Зная "числовой" тип изображения, узнаём название типа
        if ($ext) {
            $func = 'imagecreatefrom'.$ext; // Получаем название функции, соответствующую типу, для создания изображения
            $img_i = $func($image); // Создаём дескриптор для работы с исходным изображением
        } else {
            echo 'Некорректное изображение'; return false; // Выводим ошибку, если формат изображения недопустимый
        }
        /* Если указать только 1 параметр, то второй подстроится пропорционально */
        if ($cropping) {
            if ($w >= $h) $h = false;
            elseif ($h > $w) $w = false;
        }
        if (!$h) $h = $w / ($w_i / $h_i);
        if (!$w) $w = $h / ($h_i / $w_i);
        $img = imagecreatetruecolor($w, $h); // Создаём дескриптор для выходного изображения
        imagecopyresampled($img, $img_i, 0, 0, 0, 0, $w, $h, $w_i, $h_i); // Переносим изображение из исходного в выходное, масштабируя его
        $func = 'image'.$ext; // Получаем функция для сохранения результата
        return $func($img, $image); // Сохраняем изображение в тот же файл, что и исходное, возвращая результат этой операции
    }

    /*
    $x и $y - координаты левого верхнего угла выходного изображения на исходном
    $w и h - ширина и высота выходного изображения
    */
    static function image_crop($image, $x, $y, $w, $h) {
        if (($x < 0) || ($y < 0) || ($w < 0) || ($h < 0)) {
            echo "Некорректные входные параметры"; return false;
        }
        list($w_i, $h_i, $type) = getimagesize($image); // Получаем размеры и тип изображения (число)
        $types = array("", "gif", "jpeg", "png"); // Массив с типами изображений
        $ext = $types[$type]; // Зная "числовой" тип изображения, узнаём название типа
        if ($ext) {
            $func = 'imagecreatefrom'.$ext; // Получаем название функции, соответствующую типу, для создания изображения
            $img_i = $func($image); // Создаём дескриптор для работы с исходным изображением
        } else {
            echo 'Некорректное изображение'; return false; // Выводим ошибку, если формат изображения недопустимый 
        }
        if ($x + $w > $w_i) $w = $w_i - $x; // Если ширина выходного изображения больше исходного (с учётом x), то уменьшаем её
        if ($y + $h > $h_i) $h = $h_i - $y; // Если высота выходного изображения больше исходного (с учётом y), то уменьшаем её
        $img = imagecreatetruecolor($w, $h); // Создаём дескриптор для выходного изображения
        imagecopy($img, $img_i, 0, 0, $x, $y, $w, $h); // Переносим часть изображения из исходного в выходное
        $func = 'image'.$ext; // Получаем функция для сохранения результата
        return $func($img, $image); // Сохраняем изображение в тот же файл, что и исходное, возвращая результат этой операции
    }

    static function multibox($label, $table, $field, $row, $order = "", $where = "") {
        global $DB;
        $fn = "";

        $fn .= '<tr id="tr_'.$field.'">
            <td width="40%" class="adm-detail-content-cell-l adm-detail-content-cell-l-top">
                '.$label.'
            </td>
            <td class="adm-detail-content-cell-r">';

            $arr = explode("|", $row[$field]);

            $rres = $DB->Query("SELECT * FROM ".$table." WHERE 1=1 {$where} ORDER BY {$order} id ASC");
            if ($rres->SelectedRowsCount() <> 0) {
                $fn .= "<div class='adm-multi'>";
                    while ($rrow = $rres->Fetch()) {

                        $ch = "";
                        if (in_array($rrow['id'], $arr)) $ch = "checked='checked'";

                        $fn .= "<div class='adm-multi-item'>
                            <label>
                                <input type='checkbox' name='".$field."[]' value='".$rrow['id']."' ".$ch."><span>".$rrow['name']."</span>
                            </label>
                        </div>";
                    }
                $fn .= "</div>";
            }

        $fn .= "</td>
        </tr>";

        return $fn;
    }

    static function select($label, $table, $field, $row, $order = "", $where = "", $required = false) {
        global $DB;
        $fn = "";

        $fn .= '<tr id="tr_'.$field.'">
            <td width="40%" class="adm-detail-content-cell-l adm-detail-content-cell-l-top">
                 <span class="'.($required ? 'adm-required-field' : '').'">'.$label.'</span>
            </td>
            <td class="adm-detail-content-cell-r">';

            $value = $row[$field];

            $rres = $DB->Query("SELECT * FROM ".$table." WHERE 1=1 {$where} ORDER BY {$order} id ASC");
            if ($rres->SelectedRowsCount() <> 0) {
                $fn .= "<select name='{$field}' ".($required ? 'required' : '').">
                    <option value='0'>Не выбрано</option>";
                    while ($rrow = $rres->Fetch()) {

                        $ch = "";
                        if ($rrow['id'] == $value) $ch = "selected='selected'";

                        $fn .= "<option value='".$rrow['id']."' {$ch}>".$rrow['name']."</option>";
                    }
                $fn .= "</select>";
            }

        $fn .= "</td>
        </tr>";

        return $fn;
    }

    static function multilist($category){
        global $DB;
        $category_arr = [];
        $category_list = '';
        if (!empty($category)) {
            $category = trim($category, '|');
            $category = str_replace('|', ',', $category);
            $res = $DB->Query("SELECT * FROM `m_media_category` WHERE `id` IN ({$category})");
            while ($row = $res->Fetch()) {
                $category_arr[] = $row['name'];
            }
        }
        if (!empty($category_arr)) {
            $category_list = implode(', ', $category_arr);
        }
        return $category_list;
    }

    static function multisave($field, $column = null) {
        if (empty($column)) $column = $field;
        $str = '';
        $arr = $_POST[$field];
        if (!empty($arr)) {
            $str = '|'.implode('|', $arr).'|';
        }
        return ", `{$column}` = '{$str}'";
    }

    static function select_filter($table, $val, $label, $and = "", $class = "") {
        global $DB;
        $fn .= "<div class='filter-inline chosen-inline'>
            <div class='label'>{$label}</div>";
            $rres = $DB->Query("SELECT * FROM `{$table}` WHERE 1=1 {$and} ORDER BY name ASC");
            if ($rres->SelectedRowsCount() <> 0) {
                $fn .= "<select name='{$table}' class='input js-filter {$class}' form='form-filter'>
                    <option value=''>Не выбрано</option>";
                    while ($rrow = $rres->Fetch()) {
                        $sl = "";
                        if ($rrow['id'] == $val) $sl = "selected='selected'";

                        $name = $rrow['name'];

                        $fn .= "<option value='".$rrow['id']."' ".$sl.">".$name."</option>";
                    }
                $fn .= "</select>";
            }
            $fn .= "
        </div>"; 

        return $fn;
    }

    static function input_date_filter($name, $label, $value = "") {
        if (empty($value)) $value = '';
        return '<div class="filter-inline">
            <div class="label">'.$label.'</div>
            <div class="adm-input-wrap adm-input-wrap-calendar adm-filter-calendar" onclick="BX.calendar({node:this, field:\''.$name.'\', form: \'\', bTime: true, bHideTime: false});">
                <input class="adm-input adm-input-calendar js-filter" readonly type="text" name="'.$name.'" size="22" value="'.$value.'" form="form-filter">
                <span class="adm-calendar-icon" title="Нажмите для выбора даты"></span>
            </div>
        </div>';
    }
}