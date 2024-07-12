<?php
namespace Media;

class Media
{
	static function getTable() {
		return 'm_media';
	}

	static function siteLink($item){
		global $DB;
		if (!$item) return;
		$code = $item['code'];
		$category = $item['category'];
		$category_res = $DB->Query("SELECT * FROM `m_media_category` WHERE id='{$category}' LIMIT 1");
		$category_row = $category_res->fetch();
        return MEDIA_FOLDER.'/'.$category_row['code'].'/'.$code.'/';
    }

    static function siteLinkHtml($item){
    	if (!$item) return;
    	$code = $item['code'];
    	$url = self::siteLink($item);
    	return "<a target='_blank' href='{$url}'>".$code."</a>";
    }

    static function siteCategory($item){
		global $DB;
		if (!$item) return;
		$category = $item['category'];
		$res = $DB->Query("SELECT * FROM `m_media_category` WHERE id='{$category}' LIMIT 1");
		$row = $res->fetch();
        return $row;
    }

    static function siteDate($item){
		global $DB;
		if (!$item) return;
		$date = $item['date'];
		if (empty($date)) return;
		return date('d.m', $date);
    }

    static function siteDateFull($item){
		global $DB;
		if (!$item) return;
		$date = $item['date'];
		if (empty($date)) return;
		return date('d.m.Y', $date);
    }

    static function siteFav($item){
		global $DB;
		$fav = $item['likes'];
    	if (empty($fav)) $fav = '&nbsp;';
    	return $fav;
    }

    static function sitePreviewPicture($item){
		global $DB;
		$img = MEDIA_FOLDER.'/img/no-photo.jpg';
		if (!empty($item['preview_picture'])) $img = MEDIA_FOLDER.'/upload/m_media/'.$item['preview_picture'];
		$alt = $item['name'];
		if (!empty($item['preview_picture_alt'])) $alt = $item['preview_picture_alt'];
		return "<img src='{$img}' alt='".$alt."'>";
    }

    static function siteDetailPicture($item){
		global $DB;
		$img = MEDIA_FOLDER.'/img/no-photo.jpg';
		if (!empty($item['detail_picture'])) $img = MEDIA_FOLDER.'/upload/m_media/'.$item['detail_picture'];
		$alt = $item['name'];
		if (!empty($item['detail_picture_alt'])) $alt = $item['detail_picture_alt'];
		return "<img src='{$img}' alt='".$alt."' itemprop='image'>";
    }

    static function sitePreviewPictureLink($item){
		global $DB;
		$img = MEDIA_FOLDER.'/img/no-photo.jpg';
		if (!empty($item['preview_picture'])) $img = MEDIA_FOLDER.'/upload/m_media/'.$item['preview_picture'];
		return $img;
    }

    static function siteItem($item, $class = ''){

    	$item_id = $item['id'];

        $itemLink = self::siteLink($item);

        $siteCategory = self::siteCategory($item);

		$html = '<div class="m-media-item '.$class.'">
		  <a href="'.$itemLink.'" class="m-media-item-img">
		    '.self::sitePreviewPicture($item).'
		  </a>
		  <div class="m-media-item-box">
		    <div class="m-media-item-row">';
		    	if (!empty($siteCategory)) {
		      		$html .= '<a class="m-media-item-cat" href="'.MEDIA_FOLDER.'/'.$siteCategory['code'].'/" >'.$siteCategory['name'].'</a>';
		      	}
			    $html .= '<div class="m-media-item-date">
		          <span>'.self::siteDate($item).'</span>
		      </div>
		    </div>
		    <a href="'.$itemLink.'" class="m-media-item-name dotdotdot">
		      	'.$item['name'].'
		    </a>
		    <div class="m-media-item-short dotdotdot">
		      	'.$item['short'].'
		    </div>
		    <div class="m-media-item-icons">
		      <span class="m-media-item-icon m-media-item-icon-fav '.(Media::likes($item_id) ? 'm-media-item-icon-fav-active' : '').'" data-id="'.$item_id.'" title="Лайк">'.self::siteFav($item).'</span>
		      <span class="m-media-item-icon m-media-item-icon-flag '.(Media::flag($item_id) ? 'm-media-item-icon-flag-active' : '').'" data-id="'.$item_id.'" title="Сохранить в закладки"></span>
		      '.self::share($itemLink).'
		    </div>
		  </div>
		</div>';
		return $html;
	}

	static function siteItems($ids = []){
		global $DB;
		$html = '';
    	if (empty($ids)) return;
    	$where = ' AND id IN('.implode(',', $ids).')';
    	$items = self::list('', $where, 30);
    	foreach ($items AS $item) {
    		$html .= self::siteItem($item);
    	}
    	return $html;
    }

	static function list($order = '', $where = '', $limit = 11){
		global $DB;
		$items = [];
		
		$res = $DB->Query("SELECT * FROM `".self::getTable()."` WHERE `active`='1' AND `date` < '".time()."' {$where} ORDER BY {$order} sort ASC LIMIT {$limit}");
		while ($row = $res->fetch()) {
			$items[] = $row;
		}
		return $items;
	}

	static function list_all($order = '', $where = ''){
		global $DB;

		$res = $DB->Query("SELECT count(*) AS kol FROM `".self::getTable()."` WHERE `active`='1' AND `date` < '".time()."' {$where} ORDER BY {$order} sort ASC");
		$row = $res->fetch();

		return $row['kol'];
	}

	static function info($code){
		global $DB;

		if (!$code) return;

		$res = $DB->Query("SELECT * FROM `".self::getTable()."` WHERE `code` = '{$code}' LIMIT 1");
		$row = $res->fetch();

		return $row;
	}

	static function info_category($code){
		global $DB;

		if (!$code) return;

		$res = $DB->Query("SELECT * FROM `m_media_category` WHERE `code` = '{$code}' LIMIT 1");
		$row = $res->fetch();

		return $row;
	}

	static function info_filter($code){
		global $DB;

		if (!$code) return;

		$res = $DB->Query("SELECT * FROM `m_media_filter` WHERE `code` = '{$code}' LIMIT 1");
		$row = $res->fetch();

		return $row;
	}

	static function links($media_id){
		global $DB;
		$items = [];
		$res = $DB->Query("SELECT * FROM `m_media_links` WHERE `media_id` = '{$media_id}' ORDER BY id ASC");
		while ($row = $res->fetch()) {
			$items[] = $row;
		}
		return $items;
	}

	static function constructor($media_id){
		global $DB;
		$items = [];
		$res = $DB->Query("SELECT * FROM `m_media_constructor` WHERE `media_id` = '{$media_id}' AND `active` = 1 ORDER BY sort ASC, id ASC");
		while ($row = $res->fetch()) {
			$items[] = $row;
		}
		return $items;
	}

	static function quiz($media_id, $constructor_id){
		global $DB;
		$quiz = [];
		$res = $DB->Query("SELECT * FROM `m_media_question` WHERE `media_id` = '{$media_id}' AND `constructor_id` = '{$constructor_id}' ORDER BY id ASC");
		while ($row = $res->fetch()) {
			$id = $row['id'];
			$quiz[$id]['question'] = $row;

			$ares = $DB->Query("SELECT * FROM `m_media_answer` WHERE `media_id` = '{$media_id}' AND `question_id` = '{$id}' ORDER BY id ASC");
			while ($arow = $ares->fetch()) {
				$quiz[$id]['answers'][] = $arow;
			}
		}
		return $quiz;
	}

	static function markers($media_id, $constructor_id, $number){
		global $DB;
		$markers = '';
		$res = $DB->Query("SELECT * FROM `m_media_constructor_markers` WHERE `media_id` = '{$media_id}' AND `constructor_id` = '{$constructor_id}' AND `number` = '{$number}' ORDER BY id ASC");
		while ($row = $res->fetch()) {
			$left = $row['point_left'];
			$top = $row['point_top'];
			$product_id = $row['product_id'];
			if (empty($left) || empty($top) || empty($product_id)) continue;

			$product_res = \CIBlockElement::GetList(Array(), Array('IBLOCK_ID'=>IB_CATALOGUE, 'ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y', 'ID'=>$product_id));
			if ($product_res->SelectedRowsCount() == 0) continue;

			$product_row = $product_res->GetNextElement();
			$item = array_merge($product_row->GetFields(), $product_row->GetProperties()); 

			$product_name = __get_product_name($item);
			$url_item = __get_product_link($item);
			$files_by_type = array();
			$web_path = web_path($item);
			$img_path = get_resized_img($web_path);

			$markers .= '<div class="show-materials-item"
				style="left: '.$left.'%; top: '.$top.'%;"
				data-type="prod-prev"
				data-id="'.$item['ID'].'" 
				data-name="'.$product_name.'"
				data-code="'.$item['INNERCODE']['VALUE'].'"
				data-price="'._makeprice(\CPrice::GetBasePrice($item['ID']))['PRICE'].'"
				data-curr="'.getCurrency($my_city).'"
				data-cat=""
				data-cat-name=""
				data-iscomp="">
				<div class="show-materials-point"></div>
				<div class="show-materials-popup">
					<div class="show-materials-popup-img">
						<a href="'.$url_item.'" target="_blank" tabindex="0"></a>
						<img src="'.$img_path.'" alt="'.$product_name.'" />
					</div>
					<div class="show-materials-popup-title">
						<a href="'.$url_item.'" target="_blank" tabindex="0">'.$product_name.'</a>
					</div>
					<div class="show-materials-popup-bottom">
						<div class="show-materials-popup-price">'.__cost_format(_makeprice(\CPrice::GetBasePrice($item['ID']))['PRICE']).'</div>
						<div class="show-materials-popup-btns">
							<div class="show-materials-popup-favorite" data-type="favorite" data-user="no-user">
								<i class="icon-star"></i>
							</div>
							<div class="show-materials-popup-add" data-type="cart-add">
								<i class="icon-plus"></i>
							</div>
						</div>
					</div>
				</div>
			</div>';
		}
		return $markers;
	}

	static function views_add($item_id){
		global $DB;
		$views_add = false;
		$media_views = (array)unserialize($_COOKIE['media_views']);
		if (empty($media_views)) {
		    $media_views = [$item_id];
		    setcookie('media_views', serialize($media_views), time() + 86400, '/', $_SERVER['HTTP_HOST']);
		    $views_add = true; 
		}
		else {
		    if (!in_array($item_id, $media_views)) $views_add = true;  
		    $media_views[] = $item_id;
		    $media_views = array_unique($media_views);
		    setcookie('media_views', serialize($media_views), time() + 86400, '/', $_SERVER['HTTP_HOST']);
		}
		if ($views_add) {
			$res = $DB->Query("SELECT views FROM `m_media` WHERE `id` = '{$item_id}' LIMIT 1");
			$row = $res->Fetch();
			$views = (int)$row['views'];
			$views++;
			$DB->Query("UPDATE `m_media` SET `views` = '{$views}' WHERE `id` = '{$item_id}'");
		}
	}

	static function likes($item_id){
		if (empty($item_id)) return false;

		$media_likes = (array)unserialize($_COOKIE['media_likes']);

		if (in_array($item_id, $media_likes)) return true;

		return false;
	}

	static function flag($item_id){
		if (empty($item_id)) return false;

		$media_flag = (array)unserialize($_COOKIE['media_flag']);

		if (in_array($item_id, $media_flag)) return true;

		return false;
	}

	static function flagIds(){
		$media_flag = (array)unserialize($_COOKIE['media_flag']);
		$ids = [];
		if (!empty($media_flag)) {
			foreach ($media_flag AS $flag) {
				if (empty($flag)) continue;
				$ids[] = $flag;
			}
		}
		if (empty($ids)) $ids[] = '-1';
		return $ids;
	}

	static function setMeta(){
		global $APPLICATION;

		$detail = processing($_GET['detail']);
		$category = processing($_GET['category']);

		$title = 'Media';
		$keywords = 'Европласт - Media';
		$description = 'Европласт - Media';

		$item = self::info($detail);
		if (!empty($item['id'])) {
			if (!empty($item['title'])) $title = $item['title'];
			else $title = $item['name'];

			if (!empty($item['keywords'])) $keywords = $item['keywords'];
			if (!empty($item['description'])) $description = $item['description'];

			//Закрыть от индексации
			if ($item['noindex'] == 1) $APPLICATION->SetPageProperty("robots", "noindex, nofollow");
		}
		else {
			$item = self::info_category($category);
			if (!empty($item['id'])) {
				if (!empty($item['title'])) $title = $item['title'];
				else $title = $item['name'];

				if (!empty($item['keywords'])) $keywords = $item['keywords'];
				if (!empty($item['description'])) $description = $item['description'];
			}
			else {
				$item = self::info_filter($category);
				if (!empty($item['id'])) {
					if (!empty($item['title'])) $title = $item['title'];
					else $title = $item['name'];

					if (!empty($item['keywords'])) $keywords = $item['keywords'];
					if (!empty($item['description'])) $description = $item['description'];
				}
			}
		}

		$APPLICATION->SetTitle($title);
		$APPLICATION->SetPageProperty("keywords", $keywords);
		$APPLICATION->SetPageProperty("description", $description);
	}

	static function share($link = ''){
		if (!empty($link)) $link = 'https://'.$_SERVER['SERVER_NAME'].$link; 
		return '<div class="m-media-item-icon m-media-item-icon-arrow m-media-item-icon-share" title="Поделиться">
			<div class="ya-share2" data-curtain data-shape="round" data-limit="0" data-more-button-type="short" data-services="telegram,whatsapp,vkontakte,odnoklassniki,moimir,pinterest,lj" data-url="'.$link.'"></div>
		</div>';
	}

	static function breads($breads = array()){
		if (empty($breads)) return;
		$i = 1;
		$html .= '<div itemscope itemtype="https://schema.org/BreadcrumbList" class="m-media-breads">';
		foreach ($breads AS $item) {
			$html .= '<span class="m-media-breads-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
				if (!empty($item['link'])) {
					$html .= '<a class="m-media-breads-item-name" itemprop="item" href="'.$item['link'].'">
						<span itemprop="name">'.$item['name'].'</span>
					</a>';
				}
				else {
					$html .= '<span class="m-media-breads-item-name" itemprop="item">
						<span itemprop="name">'.$item['name'].'</span>
					</span>';
				}
	          	$html .= '<meta itemprop="position" content="'.$i.'" />
	        </span>';
	        $i++;
		}
		$html .= '</div>';
	    return $html; 
	}
}