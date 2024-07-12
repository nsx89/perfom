<?php
namespace Media;

use Custom\Admin;
use Media\MediaTypes;

class MediaConstructor
{
	static function getTable() {
		return 'm_media_constructor';
	}

	static function add(){
		global $DB; 
		global $USER; 

		$type_id = (int)Admin::processing($_POST['type_id']);
		$media_id = (int)Admin::processing($_POST['media_id']);

		if (empty($type_id) || empty($media_id)) exit;

		$user_id = $USER->GetID();
		
		$DB->query("INSERT INTO `".self::getTable()."` SET 
			`media_id` = '{$media_id}'
			, `type_id` = '{$type_id}'
			, `sort` = '500'
			, `active` = '1'
		");

		$id = $DB->LastID();

		echo self::item($id);
	}

	static function item_delete($id){
		global $DB; 

		if (empty($id)) return;

		$table = self::getTable();

		$res = $DB->query("SELECT * FROM `".$table."` WHERE `id` = '{$id}'");
		$row = $res->fetch();
		if (empty($row)) return;

		Admin::image_dir(MEDIA_FOLDER."/upload/{$table}/");
		if (!empty($row['image'])) unlink(trim($row['image']));
		if (!empty($row['image2'])) unlink(trim($row['image2']));
		if (!empty($row['image3'])) unlink(trim($row['image3']));

		Admin::image_dir(MEDIA_FOLDER."/files/{$table}/");
		if (!empty($row['video'])) unlink(trim($row['video']));

		$DB->query("DELETE FROM `".self::getTable()."` WHERE `id` = '{$id}'");
		$DB->query("DELETE FROM `m_media_constructor_markers` WHERE `constructor_id` = '{$id}'");

		$question_res = $DB->query("SELECT * FROM `m_media_question` WHERE `constructor_id` = '{$id}'");
		while ($question_row = $question_res->fetch()) {
			$question_id = $question_row['id'];
			$DB->query("DELETE FROM `m_media_answer` WHERE `question_id` = '{$question_id}'");
			$DB->query("DELETE FROM `m_media_question` WHERE `id` = '{$question_id}'");
		}
	}

	static function delete($id){
    	global $DB; 

		if (empty($id)) return;

		$res = $DB->query("SELECT * FROM `".MediaConstructor::getTable()."` WHERE `media_id` = '{$id}' ORDER BY sort ASC, id ASC");
		while ($row = $res->fetch()) {
			self::item_delete($row['id']);
	   	}	
    }

	static function item($id){
		global $DB; 

		$table = self::getTable();

		$res = $DB->query("SELECT * FROM `".$table."` WHERE `id` = '{$id}' LIMIT 1");
		$row = $res->fetch();
		if (empty($row)) return;

		$media_id = $row['media_id'];
		$type_id = $row['type_id'];

		$type_res = $DB->query("SELECT * FROM `".MediaTypes::getTable()."` WHERE `id` = '".$row['type_id']."' LIMIT 1");
		$type_row = $type_res->fetch();

		$html = '<div class="constructor-item">
			<div class="constructor-item-head">
				<div class="constructor-checkbox-active">'.self::checkbox('constructor_active__'.$id, 'Активность', $row['active']).'</div>
				Блок <span class="constructor-item-head-type">'.$type_row['name'].'</span><span class="constructor-item-head-id">ID:'.$id.'</span>
				<input class="constructor-item-sort" type="text" value="'.$row['sort'].'" name="constructor_sort__'.$id.'" title="Сортировка" />
				<div class="constructor-item-delete" data-id="'.$id.'" title="Удалить блок"></div>
			</div>
			<div class="constructor-item-content">';

			if ($type_id <> 15 && $type_id <> 1 && $type_id <> 18) $html .= self::head($row['head'], $id);
			if ($type_id == 14) $html .= self::short($row['short'], $id);

			switch ($type_id) {
				case 1:
					$html .= self::image_cropper('image__'.$id, 'Аватар редактора (225x225px):', $table, $row, 100, 100, 'image').
					'<div class="constructor-item-fields">
						<div class="constructor-item-field">
							<span class="constructor-label">ФИО:</span>
							<input class="constructor-input" type="text" value="'.$row['name'].'" name="constructor_name__'.$id.'">
						</div>
						<div class="constructor-item-field">
							<span class="constructor-label">Должность:</span>
							<input class="constructor-input" type="text" value="'.$row['short'].'" name="constructor_short__'.$id.'">
						</div>
					</div>';
					break;
				case 2:
					$html .= self::image('image__'.$id, 'Изображение (макс. ширина 1086px, высота любая):', $table, $row, 1086, 1086, 'image').'
					<div class="constructor-item-image-short">
						<span class="constructor-label">Описание фотографии:</span>
						<input class="constructor-input" type="text" value="'.$row['short'].'" name="constructor_short__'.$id.'">
					</div>';
					break;
				case 4:
					$html .= self::image('image__'.$id, 'Изображение (макс. ширина 899px, высота любая):', $table, $row, 899, 899, 'image').'
					<div class="constructor-item-image-short">
						<span class="constructor-label">Описание фотографии:</span>
						<input class="constructor-input" type="text" value="'.$row['short'].'" name="constructor_short__'.$id.'">
					</div>';
					break;
				case 3: case 5:
					$html .= self::video('video__'.$id, 'Видео (в формате .mp4):', $table, $row, 'video');
					break;
				case 6:
					$html .= '<div class="constructor-item-fields constructor-item-fields-double">
						<div class="constructor-item-field">
							'.self::image_cropper('image__'.$id, 'Изображение 1 (438x440px):', $table, $row, 438, 440, 'image').'
						</div>
						<div class="constructor-item-field">
							'.self::image_cropper('image2__'.$id, 'Изображение 2 (438x440px):', $table, $row, 438, 440, 'image2').'
						</div>
					</div>';
					break;
				case 7:
					$html .= '<div class="constructor-item-fields constructor-item-fields-double">
						<div class="constructor-item-field">
							'.self::image_cropper('image__'.$id, 'Изображение 1 (438x440px):', $table, $row, 1032, 466, 'image').'
						</div>
					</div>
					'.self::marker_create($media_id, $id, 1).'
					<div class="constructor-item-fields constructor-item-fields-double">
						<div class="constructor-item-field constructor-marker-compact">
							'.self::image_cropper('image2__'.$id, 'Изображение 2 (333x304px):', $table, $row, 333, 304, 'image2').'
							'.self::marker_create($media_id, $id, 2).'
						</div>
						<div class="constructor-item-field constructor-marker-compact">
							'.self::image_cropper('image3__'.$id, 'Изображение 3 (333x304px):', $table, $row, 333, 304, 'image3').'
							'.self::marker_create($media_id, $id, 3).'
						</div>
						<div class="constructor-item-field constructor-marker-compact">
							'.self::image_cropper('image4__'.$id, 'Изображение 4 (333x304px):', $table, $row, 333, 304, 'image4').'
							'.self::marker_create($media_id, $id, 4).'
						</div>
					</div>';
					break;
				case 8:
					$html .= '<div class="constructor-item-fields constructor-item-fields-center">
						<textarea class="constructor-textarea" maxlength="1000" name="constructor_text__'.$id.'">'.$row['text'].'</textarea>
					</div>'.
					self::image_cropper('image__'.$id, 'Фото автора цитаты (225x225px):', $table, $row, 100, 100, 'image').
					'<div class="constructor-item-fields">
						<div class="constructor-item-field">
							<span class="constructor-label">ФИО:</span>
							<input class="constructor-input" type="text" value="'.$row['name'].'" name="constructor_name__'.$id.'">
						</div>
						<div class="constructor-item-field">
							<span class="constructor-label">Должность:</span>
							<input class="constructor-input" type="text" value="'.$row['short'].'" name="constructor_short__'.$id.'">
						</div>
					</div>';
					break;
				case 9:
					$html .= '<div class="constructor-item-fields constructor-item-fields-textarea">
						<div class="constructor-item-field constructor-item-field-textarea">
							<span class="constructor-label">ID продуктов через запятую:</span>
						</div>
						<div class="constructor-item-field">
							<textarea class="typearea" maxlength="500" name="constructor_ids__'.$id.'">'.$row['ids'].'</textarea>
						</div>
					</div>';
					break;
				case 10:
					$html .= '<textarea class="constructor-textarea" maxlength="1000" name="constructor_text__'.$id.'">'.$row['text'].'</textarea>';
					break;
				case 11:
					$html .= self::multibox('', Media::getTable(), 'multi_ids', $row, "name ASC,", "AND `id` <> '{$media_id}'"); 
					break;
				case 12:
					$html .= '<div class="constructor-item-fields constructor-item-fields-center">
						<div class="constructor-item-field">
							<span class="constructor-label">
								Ссылка на страницу Pinterest:
								<span>(например, https://ru.pinterest.com/PITERRA_INTERIORS/)</span>
							</span>
							<div class="constructor-input-wrap">
								<input class="constructor-input" type="text" value="'.$row['name'].'" name="constructor_name__'.$id.'">
							</div>
						</div>
					</div>';
					break;
				case 13:
					$html .= '<div class="constructor-question-wrap">
						<div class="constructor-question-items">
							'.self::questions($id).'
						</div>
						<div class="constructor-question-create-wrap">
							<input type="button" class="button constructor-question-create" value="Добавить вопрос" data-media_id="'.$media_id.'" data-constructor_id="'.$id.'"  />
						</div>
					</div>';
					break;
				case 14:
					$html .= self::textbox('constructor_text__'.$id, $row['text']);
					break;
				case 15:
					$html .= '<div class="constructor-line"></div>';
					break;
				case 16:
					$html .= '<div class="constructor-item-fields">
						<div class="constructor-item-field constructor-item-field-audio">
							'.self::video('video__'.$id, 'Аудио (в формате .mp3):', $table, $row, 'video').'
						</div>
						<div class="constructor-item-field constructor-item-field-audio-image">
							'.self::image_cropper('image__'.$id, 'Изображение (необязательно, 225x225px):', $table, $row, 100, 100, 'image').'
						</div>
					</div>
					<div class="constructor-item-fields">
						<div class="constructor-item-field">
							<span class="constructor-label">Название композиции (необязательно):</span>
							<input class="constructor-input" type="text" value="'.$row['name'].'" name="constructor_name__'.$id.'">
						</div>
						<div class="constructor-item-field">
							<span class="constructor-label">Имя исполнителя (необязательно):</span>
							<input class="constructor-input" type="text" value="'.$row['short'].'" name="constructor_short__'.$id.'">
						</div>
					</div>';
					break;
				case 17:
					$html .= '<textarea class="constructor-textarea" maxlength="1000" name="constructor_code__'.$id.'">'.$row['code'].'</textarea>';
					break;
				case 18:
					$html .= '<div class="constructor-actions"></div>';
					break;
			}

		$html .= '</div>
		</div>';
		return $html;
	}

	static function items($media_id){
		global $DB; 

		$res = $DB->query("SELECT * FROM `".self::getTable()."` WHERE `media_id` = '{$media_id}' ORDER BY sort ASC, id ASC");
		$i = 1;
		while ($row = $res->fetch()) {
			$html .= self::item($row['id'], $i);
			$i++;
		}

		return $html;
	}

	static function image_cropper($name, $label, $table, $row, $width, $height, $field){

        $src = '';
        if (!empty($row[$field])) {
        	$src = MEDIA_FOLDER."/upload/{$table}/".$row[$field];
        }

        $w = $width;
        if ($w < 225) $w = 225;

        $alt = !empty($row[$field.'_alt']) ? $row[$field.'_alt'] : '';

        return '<div class="constructor-label">'.$label.'</div>
        <div class="constructor-item-image js-adm-cropper">
	        <div class="adm-fileinput-wrapper adm-fileinput-wrapper-single" style="width: '.$w.'px;">
	            <div class="adm-fileinput-area mode-pict mode-with-description adm-fileinput-drag-area" style="width: 100%; height: auto; /*min-height: '.$height.'px;*/ max-width: 100%;">
	                <input class="js-file-img-del" type="hidden" name="img_del_'.$name.'" value="0" data-id="'.$row['id'].'" /> 
	                <div class="js-adm-fileinput" style="'.(!empty($src) ? 'display: none;' : '').'">
	                    <div class="adm-fileinput-area-container"></div>
	                    <span class="adm-fileinput-drag-area-hint">
	                        Выбрать изображение
	                    </span>
	                    <input class="adm-fileinput-drag-area-input js-adm-fileinput-input" type="file" name="img_file_'.$name.'" multiple="false" accept="image/*" />
	                </div>
	                <div class="js-adm-fileview adm-fileinput-area-container" style="width: 100%; '.(!empty($src) ? '' : 'display: none;').'">
	                    <div class="adm-fileinput-item-wrapper bx-drag-draggable adm-fileinput-item-image" style="width: 100%;">
	                        <div class="bx-bxu-thumb-thumb" style="width: 100%;">
	                            <div class="adm-fileinput-item" style="width: 100%;">
	                            	<input type="hidden" name="'.$name.'_width_default" value="'.$width.'">
	                            	<input type="hidden" name="'.$name.'_height_default" value="'.$height.'">
	                                <input class="js-adm-cropper-x" type="hidden" name="'.$name.'_x">
	                                <input class="js-adm-cropper-y" type="hidden" name="'.$name.'_y">
	                                <input class="js-adm-cropper-width" type="hidden" name="'.$name.'_width">
	                                <input class="js-adm-cropper-height" type="hidden" name="'.$name.'_height">
	                                <input type="hidden" name="'.$name.'_cropper" value="1">
	                                <div class="cropper-image" style="width: 100%;">
	                                    <img class="js-adm-fileinput-img js-adm-fileinput-img-crop" id="js-adm-fileinput-img-crop'.$name.'" src="'.$src.'" data-crop1="'.$width.'" data-crop2="'.$height.'" style="max-width: 100%; margin: 0px auto;" />
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
	    </div>';
    }

    static function image($name, $label, $table, $row, $width, $height, $field){

        $src = '';
        if (!empty($row[$field])) {
        	$src = MEDIA_FOLDER."/upload/{$table}/".$row[$field];
        }

        $w = $width;
        if ($w < 225) $w = 225;

        $alt = !empty($row[$field.'_alt']) ? $row[$field.'_alt'] : '';

        return '<div class="constructor-label">'.$label.'</div>
        <div class="constructor-item-image js-adm-cropper">
	        <div class="adm-fileinput-wrapper adm-fileinput-wrapper-single" style="width: '.$w.'px;">
	            <div class="adm-fileinput-area mode-pict mode-with-description adm-fileinput-drag-area" style="width: 100%; height: auto; /*min-height: '.$height.'px;*/ max-width: 100%;">
	                <input class="js-file-img-del" type="hidden" name="img_del_'.$name.'" value="0" data-id="'.$row['id'].'" /> 
	                <div class="js-adm-fileinput" style="'.(!empty($src) ? 'display: none;' : '').'">
	                    <div class="adm-fileinput-area-container"></div>
	                    <span class="adm-fileinput-drag-area-hint">
	                        Выбрать изображение
	                    </span>
	                    <input class="adm-fileinput-drag-area-input js-adm-fileinput-input" type="file" name="img_file_'.$name.'" multiple="false" accept="image/*" />
	                </div>
	                <div class="js-adm-fileview adm-fileinput-area-container" style="width: 100%; '.(!empty($src) ? '' : 'display: none;').'">
	                    <div class="adm-fileinput-item-wrapper bx-drag-draggable adm-fileinput-item-image" style="width: 100%;">
	                        <div class="bx-bxu-thumb-thumb" style="width: 100%;">
	                            <div class="adm-fileinput-item" style="width: 100%;">
	                            	<input type="hidden" name="'.$name.'_width_default" value="'.$width.'">
	                            	<input type="hidden" name="'.$name.'_height_default" value="'.$height.'">
	                                <div class="cropper-image" style="width: 100%;">
	                                    <img class="js-adm-fileinput-img js-adm-fileinput-img-crop-off" id="js-adm-fileinput-img-image'.$name.'" src="'.$src.'" style="max-width: 100%; margin: 0px auto;" />
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
	    </div>';
    }

    static function video($name, $label, $table, $row, $field = null){

    	if (empty($field)) $field = $name;

        $video = '';
        if (!empty($row[$field])) $video = "<a href='".MEDIA_FOLDER."/files/{$table}/".$row[$field]."' target='_blank'>".$row[$field]."</a>";

        $id = $row['id'];

        $td = '<div class="constructor-label">'.$label.'</div>
        <div class="constructor-item-video">
	        <div class="js-adm-video adm-fileinput-area mode-pict adm-fileinput-drag-area adm-fileinput-drag-notification-count">
	        	<input class="js-file-video-del" type="hidden" name="video_del_'.$name.'" value="0" data-id="'.$row['id'].'" /> 
	        	<div class="js-adm-video-input" style="'.(!empty($video) ? 'display: none;' : '').'">
                    <div class="adm-fileinput-area-container"></div>
                    <span class="adm-fileinput-drag-area-hint">
                        Выбрать файл
                    </span>
                    <input class="adm-fileinput-drag-area-input js-adm-video-input-val" type="file" name="video_file_'.$name.'" multiple="false" accept="mp4" />
                </div>
	            <div class="js-adm-video-view adm-fileinput-area-container" style="'.(!empty($video) ? '' : 'display: none;').'">
	                <div class="adm-fileinput-item-wrapper bx-drag-draggable adm-fileinput-item-file">
	                    <div class="bx-bxu-thumb-thumb">
	                        <div class="adm-fileinput-item">
	                            <div class="adm-fileinput-item-preview">
	                                <div class="adm-fileinput-item-preview-icon">
	                                    <div class="bx-file-icon-container-medium icon-mp4">
	                                        <div class="bx-file-icon-cover">
	                                            <div class="bx-file-icon-corner"><div class="bx-file-icon-corner-fix"></div></div>
	                                            <div class="bx-file-icon-images"></div>
	                                        </div>
	                                        <div class="bx-file-icon-label"></div>
	                                    </div>
	                                    <span class="container-doc-title"><span class="bx-bxu-thumb-name js-adm-video-view-name">'.$video.'</span></span>
	                                </div>
	                            </div>
	                            <div class="adm-fileinput-item-panel">
                                    <span class="adm-fileinput-item-panel-btn adm-btn-del js-video-del" title="Убрать">&nbsp;</span>
                                </div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>';
        return $td;
    }

    static function checkbox($name, $label, $value){
        $checked = '';
        if ($value == 1) $checked = 'checked=""';
        return '<div class="constructor-checkbox">
        	<input type="hidden" name="'.$name.'" value="0" /> 
			<input type="checkbox" name="'.$name.'" value="1" '.$checked.' id="designed_checkbox_'.$name.'" class="adm-designed-checkbox" />
			<label class="adm-designed-checkbox-label" for="designed_checkbox_'.$name.'" title=""></label>
			<span>'.$label.'</span>
		</div>';
    }

    static function textbox_hide(){
    	return '<div style="display: none;">'.self::textbox('temp_editor').'</div>';
    }

    static function textbox($name, $value = ''){
    	if (!\CModule::IncludeModule("fileman")) return;
		$id = preg_replace("/[^a-z0-9]/i", '', $name);
		ob_start();
		$LHE = new \CHTMLEditor;
		$LHE->Show(array(
		    'name' => $name,
		    'id' => $id,
		    'inputName' => $name,
		    'content' => $value,
		    'width' => '100%',
		    'minBodyWidth' => 350,
		    'normalBodyWidth' => 555,
		    'height' => '250',
		    'bAllowPhp' => false,
		    'limitPhpAccess' => false,
		    'autoResize' => true,
		    'autoResizeOffset' => 40,
		    'useFileDialogs' => false,
		    'saveOnBlur' => true,
		    'showTaskbars' => false,
		    'showNodeNavi' => false,
		    'askBeforeUnloadPage' => true,
		    'bbCode' => false,
		    'siteId' => SITE_ID,
		    /*'controlsMap' => array(
		        array('id' => 'Bold', 'compact' => true, 'sort' => 80),
		        array('id' => 'Italic', 'compact' => true, 'sort' => 90),
		        array('id' => 'Underline', 'compact' => true, 'sort' => 100),
		        array('id' => 'Strikeout', 'compact' => true, 'sort' => 110),
		        array('id' => 'RemoveFormat', 'compact' => true, 'sort' => 120),
		        array('id' => 'Color', 'compact' => true, 'sort' => 130),
		        //array('id' => 'FontSelector', 'compact' => false, 'sort' => 135),
		        array('id' => 'FontSize', 'compact' => false, 'sort' => 140),
		        array('separator' => true, 'compact' => false, 'sort' => 145),
		        array('id' => 'OrderedList', 'compact' => true, 'sort' => 150),
		        array('id' => 'UnorderedList', 'compact' => true, 'sort' => 160),
		        //array('id' => 'AlignList', 'compact' => false, 'sort' => 190),
		        array('separator' => true, 'compact' => false, 'sort' => 200),
		        array('id' => 'InsertLink', 'compact' => true, 'sort' => 210),
		        //array('id' => 'InsertImage', 'compact' => false, 'sort' => 220),
		        //array('id' => 'InsertVideo', 'compact' => true, 'sort' => 230),
		        array('id' => 'InsertTable', 'compact' => false, 'sort' => 250),
		        array('separator' => true, 'compact' => false, 'sort' => 290),
		        array('id' => 'Fullscreen', 'compact' => false, 'sort' => 310),
		        array('id' => 'More', 'compact' => true, 'sort' => 400)
		    ),*/
		));
		$s = ob_get_contents();
		ob_end_clean();
		return $s;
    }

    static function save($id){
    	global $DB; 
		global $USER;

		if (empty($id)) return;

		$user_id = $USER->GetID(); 
		$table = MediaConstructor::getTable();

		$res = $DB->query("SELECT * FROM `".$table."` WHERE `media_id` = '{$id}' ORDER BY sort ASC, id ASC");
		while ($row = $res->fetch()) {
			$item_id = $row['id'];
			$sort = Admin::processing($_POST['constructor_sort__'.$item_id]);
			$name = Admin::processing($_POST['constructor_name__'.$item_id]);
			$short = Admin::processing($_POST['constructor_short__'.$item_id]);
			$active = Admin::processing($_POST['constructor_active__'.$item_id]);
			$ids = Admin::processing($_POST['constructor_ids__'.$item_id]);
			$text = Admin::processing_code($_POST['constructor_text__'.$item_id]);

			$width = (int)Admin::processing($_POST['image__'.$item_id.'_width_default']);
            $height = (int)Admin::processing($_POST['image__'.$item_id.'_height_default']);
			$image = Admin::image_upload('image__'.$item_id, $table, $width, $height, true, $item_id, 'image'); 

			$width = (int)Admin::processing($_POST['image2__'.$item_id.'_width_default']);
            $height = (int)Admin::processing($_POST['image2__'.$item_id.'_height_default']);
			$image2 = Admin::image_upload('image2__'.$item_id, $table, $width, $height, true, $item_id, 'image2');

			$width = (int)Admin::processing($_POST['image3__'.$item_id.'_width_default']);
            $height = (int)Admin::processing($_POST['image3__'.$item_id.'_height_default']);
			$image3 = Admin::image_upload('image3__'.$item_id, $table, $width, $height, true, $item_id, 'image3'); 

			$width = (int)Admin::processing($_POST['image4__'.$item_id.'_width_default']);
            $height = (int)Admin::processing($_POST['image4__'.$item_id.'_height_default']);
			$image4 = Admin::image_upload('image4__'.$item_id, $table, $width, $height, true, $item_id, 'image4'); 

			$video = Admin::video_upload('video__'.$item_id, $table, $item_id, 'video'); 

			$multi_ids = Admin::multisave('constructor_multi_ids__'.$item_id, 'multi_ids');

			$code = Admin::processing_code($_POST['constructor_code__'.$item_id]);

			$head = Admin::processing($_POST['constructor_head__'.$item_id]);

			$DB->query("UPDATE `".self::getTable()."` SET 
				`sort` = '{$sort}'
				, `name` = '{$name}'
				, `short` = '{$short}'
				, `active` = '{$active}'
				, `ids` = '{$ids}'
				, `text` = '{$text}'
				, `code` = '{$code}'
				, `head` = '{$head}'
				{$multi_ids}
				{$image}
				{$image2}
				{$image3}
				{$image4}
				{$video}
			WHERE `id` = '{$item_id}'");
	   	}

	   	//markers
		$mres = $DB->query("SELECT * FROM `m_media_constructor_markers` WHERE `media_id` = '{$id}'");
		while ($mrow = $mres->fetch()) {
			$marker_id = $mrow['id'];
			$point_top = Admin::processing($_POST['marker_point_top__'.$marker_id]);
			$point_left = Admin::processing($_POST['marker_point_left__'.$marker_id]);
			$product_id = Admin::processing($_POST['marker_product_id__'.$marker_id]);

			$DB->query("UPDATE `m_media_constructor_markers` SET 
				`point_top` = '{$point_top}'
				, `point_left` = '{$point_left}'
				, `product_id` = '{$product_id}'
			WHERE `id` = '{$marker_id}'");
		}

		//questions
		$qres = $DB->query("SELECT * FROM `m_media_question` WHERE `media_id` = '{$id}'");
		while ($qrow = $qres->fetch()) {
			$question_id = $qrow['id'];
			$name = Admin::processing($_POST['question_name__'.$question_id]);

			$DB->query("UPDATE `m_media_question` SET 
				`name` = '{$name}'
			WHERE `id` = '{$question_id}'");
		}

		//answer
		$qres = $DB->query("SELECT * FROM `m_media_answer` WHERE `media_id` = '{$id}'");
		while ($qrow = $qres->fetch()) {
			$answer_id = $qrow['id'];
			$name = Admin::processing($_POST['answer_name__'.$answer_id]);

			$DB->query("UPDATE `m_media_answer` SET 
				`name` = '{$name}'
			WHERE `id` = '{$answer_id}'");
		}

		//links
		$qres = $DB->query("SELECT * FROM `m_media_links` WHERE `media_id` = '{$id}'");
		while ($qrow = $qres->fetch()) {
			$links_id = $qrow['id'];
			$name = Admin::processing($_POST['links_name__'.$links_id]);
			$block_id = Admin::processing($_POST['links_block_id__'.$links_id]);

			$DB->query("UPDATE `m_media_links` SET 
				`name` = '{$name}'
				, `block_id` = '{$block_id}'
			WHERE `id` = '{$links_id}'");
		}
    }

    static function multibox($label, $table, $field, $row, $order = "", $where = "") {
        global $DB;
        $item_id = $row['id'];
        $fn = "<div class='constructor-multibox'>";

        	if (!empty($label)) $fn .= "<div class='adm-multi-head'>{$label}</div>";

	        $arr = explode("|", $row[$field]);

	        $rres = $DB->Query("SELECT * FROM ".$table." WHERE 1=1 {$where} ORDER BY {$order} id ASC");
	        if ($rres->SelectedRowsCount() <> 0) {
	            $fn .= "<div class='adm-multi multibox'>
	            	<input class='multibox-search' type='text' value='' placeholder='Поиск по названию или id'>";
	                while ($rrow = $rres->Fetch()) {

	                    $ch = "";
	                    if (in_array($rrow['id'], $arr)) $ch = "checked='checked'";

	                    $fn .= "<div class='multibox-item'>
	                        <label>
	                            <input type='checkbox' name='constructor_{$field}__{$item_id}[]' value='".$rrow['id']."' ".$ch.">
	                            	<span class='multibox-item-name'>".$rrow['name']."</span>
	                            	<span class='multibox-item-id'>ID: ".$rrow['id']."</span>
	                        </label>
	                    </div>";

	                }
	            $fn .= "<div class='multibox-search-not-found'>Ничего не найдено</div>
	            </div>";
	        }
	    $fn .= "</div>";

        return $fn;
    }

    static function markers($constructor_id, $number) {
    	global $DB;
    	$fn = '';
    	$res = $DB->Query("SELECT * FROM `m_media_constructor_markers` WHERE `constructor_id` = '{$constructor_id}' AND `number` = '{$number}' ORDER BY id ASC");
    	if ($res->SelectedRowsCount() <> 0) {
    		while ($row = $res->Fetch()) {
		    	$fn .= self::marker($row['id']);
			}
		}
		return $fn;
    }

    static function marker_create($media_id, $id, $number){
    	return '<div class="constructor-marker-wrap">
			<div class="constructor-marker-create-wrap">
				<input type="button" class="button constructor-marker-create" value="Создать маркер" data-media_id="'.$media_id.'" data-constructor_id="'.$id.'" data-number="'.$number.'" />
			</div>
			<div class="constructor-marker-items">
				'.self::markers($id, $number).'
			</div>
		</div>';
    }

    static function marker($id){
    	global $DB; 

    	$res = $DB->query("SELECT * FROM `m_media_constructor_markers` WHERE `id` = '{$id}' LIMIT 1");
		$row = $res->fetch();
		if (empty($row)) return;

    	return '<div class="constructor-marker-item">
    		<div class="constructor-marker-item-column">
				<span class="constructor-label">Позиция слева (в процентах):</span>
				<input class="js-input-number" type="text" value="'.$row['point_left'].'" name="marker_point_left__'.$row['id'].'">
			</div>
			<div class="constructor-marker-item-column">
				<span class="constructor-label">Позиция сверху (в процентах):</span>
				<input class="js-input-number" type="text" value="'.$row['point_top'].'" name="marker_point_top__'.$row['id'].'">
			</div>
			<div class="constructor-marker-item-column">
				<span class="constructor-label">ID товара:</span>
				<input class="js-input-number" type="text" value="'.$row['product_id'].'" name="marker_product_id__'.$row['id'].'">
			</div>
			<div class="constructor-marker-item-delete" data-id="'.$row['id'].'"></div>
		</div>';
    }

    static function marker_add(){
		global $DB; 

		$media_id = (int)Admin::processing($_POST['media_id']);
		$constructor_id = (int)Admin::processing($_POST['constructor_id']);
		$number = (int)Admin::processing($_POST['number']);

		if (empty($constructor_id)) exit;
		
		$DB->query("INSERT INTO `m_media_constructor_markers` SET 
			`media_id` = '{$media_id}'
			, `constructor_id` = '{$constructor_id}'
			, `number` = '{$number}'
		");

		$id = $DB->LastID();
		echo self::marker($id);
	}

	static function marker_delete($id){
    	global $DB; 
		if (empty($id)) return;
		$DB->query("DELETE FROM `m_media_constructor_markers` WHERE `id` = '{$id}'");
    }

    static function questions($constructor_id) {
    	global $DB;
    	$fn = '';
    	$res = $DB->Query("SELECT * FROM `m_media_question` WHERE `constructor_id` = '{$constructor_id}' ORDER BY id ASC");
    	if ($res->SelectedRowsCount() <> 0) {
    		while ($row = $res->Fetch()) {
		    	$fn .= self::question($row['id']);
			}
		}
		return $fn;
    }

    static function question($id){
    	global $DB; 

    	$res = $DB->query("SELECT * FROM `m_media_question` WHERE `id` = '{$id}' LIMIT 1");
		$row = $res->fetch();
		if (empty($row)) return;

		$media_id = $row['media_id'];

    	return '<div class="constructor-question-item">
			<div class="constructor-question-item-column">
				<span class="constructor-label">Вопрос:</span>
				<textarea name="question_name__'.$row['id'].'">'.$row['name'].'</textarea>
			</div>
			<div class="constructor-question-item-delete" data-id="'.$row['id'].'"></div>
			<div class="constructor-answer-wrap">
				<div class="constructor-answer-items">
					'.self::answers($id).'
				</div>
				<div class="constructor-answer-create-wrap">
					<input type="button" class="button constructor-answer-create" value="Добавить ответ" data-media_id="'.$media_id.'" data-question_id="'.$id.'"  />
				</div>
			</div>
		</div>';
    }

    static function question_add(){
		global $DB; 

		$media_id = (int)Admin::processing($_POST['media_id']);
		$constructor_id = (int)Admin::processing($_POST['constructor_id']);

		if (empty($media_id) || empty($constructor_id)) exit;
		
		$DB->query("INSERT INTO `m_media_question` SET 
			`media_id` = '{$media_id}'
			, `constructor_id` = '{$constructor_id}'
		");

		$id = $DB->LastID();

		echo self::question($id);
	}

	static function question_delete($id){
    	global $DB; 
		if (empty($id)) return;
		$DB->query("DELETE FROM `m_media_question` WHERE `id` = '{$id}'");
		$DB->query("DELETE FROM `m_media_answer` WHERE `question_id` = '{$id}'");
    }

	static function answers($question_id) {
    	global $DB;
    	$fn = '';
    	$res = $DB->Query("SELECT * FROM `m_media_answer` WHERE `question_id` = '{$question_id}' ORDER BY id ASC");
    	if ($res->SelectedRowsCount() <> 0) {
    		while ($row = $res->Fetch()) {
		    	$fn .= self::answer($row['id']);
			}
		}
		return $fn;
    }

    static function answer($id){
    	global $DB; 

    	$res = $DB->query("SELECT * FROM `m_media_answer` WHERE `id` = '{$id}' LIMIT 1");
		$row = $res->fetch();
		if (empty($row)) return;

    	return '<div class="constructor-answer-item">
			<div class="constructor-answer-item-column">
				<span class="constructor-label">Ответ:</span>
				<textarea name="answer_name__'.$row['id'].'">'.$row['name'].'</textarea>
			</div>
			<div class="constructor-answer-item-delete" data-id="'.$row['id'].'"></div>
		</div>';
    }

    static function answer_add(){
		global $DB; 

		$media_id = (int)Admin::processing($_POST['media_id']);
		$question_id = (int)Admin::processing($_POST['question_id']);

		if (empty($media_id) || empty($question_id)) exit;
		
		$DB->query("INSERT INTO `m_media_answer` SET 
			`media_id` = '{$media_id}'
			, `question_id` = '{$question_id}'
		");

		$id = $DB->LastID();

		echo self::answer($id);
	}

	static function answer_delete($id){
    	global $DB; 
		if (empty($id)) return;
		$DB->query("DELETE FROM `m_media_answer` WHERE `id` = '{$id}'");
    }

    static function head($head, $id){
    	return '<div class="constructor-head">
			<span class="constructor-label">Заголовок H2 <span>(необязательно):</span></span>
			<input class="constructor-input" type="text" value="'.$head.'" name="constructor_head__'.$id.'">
		</div>';
    }

    static function short($short, $id){
    	return;
    	return '<div class="constructor-head">
			<span class="constructor-label">Заголовок H3 <span>(необязательно):</span></span>
			<textarea class="constructor-input" name="constructor_short__'.$id.'">'.$short.'</textarea>
		</div>';
    }

    static function links($media_id) {
    	global $DB;
    	$fn = '';
    	$res = $DB->Query("SELECT * FROM `m_media_links` WHERE `media_id` = '{$media_id}' ORDER BY id ASC");
    	if ($res->SelectedRowsCount() <> 0) {
    		while ($row = $res->Fetch()) {
		    	$fn .= self::link($row['id']);
			}
		}
		return $fn;
    }

    static function link($id){
    	global $DB; 

    	$res = $DB->query("SELECT * FROM `m_media_links` WHERE `id` = '{$id}' LIMIT 1");
		$row = $res->fetch();
		if (empty($row)) return;

    	return '<div class="links-item">
			<div class="links-item-column">
				<span class="constructor-label">Название якоря:</span>
				<input class="links-item-name" type="text" name="links_name__'.$row['id'].'" value="'.$row['name'].'">
			</div>
			<div class="links-item-column">
				<span class="constructor-label">ID блока конструктора:</span>
				<input class="js-input-number" type="text" name="links_block_id__'.$row['id'].'" value="'.$row['block_id'].'">
			</div>
			<div class="links-item-delete" data-id="'.$row['id'].'"></div>
		</div>';
    }

    static function links_add(){
		global $DB; 

		$media_id = (int)Admin::processing($_POST['media_id']);
		if (empty($media_id)) exit;
		
		$DB->query("INSERT INTO `m_media_links` SET 
			`media_id` = '{$media_id}'
		");

		$id = $DB->LastID();

		echo self::link($id);
	}

	static function links_delete($id){
    	global $DB; 
		if (empty($id)) return;
		$DB->query("DELETE FROM `m_media_links` WHERE `id` = '{$id}'");
    }
}