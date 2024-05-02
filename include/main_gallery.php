<?
//ini_set('error_reporting', E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);


$city = (int)$_GET['city'];

if (empty($my_city)) {
	$my_city = $city;
}

if ($city == 3196) {
	require_once 'cache/main_gallery.php';
	exit;
}

if (!empty($city)) {
	require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
	if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
	    exit;
	}
}

function get_objects_gallery($items = array()) {

	global $my_city;

	$arArticle = array();
	foreach($items as $key => $item) { 
		$arArticle[] = $key;	
	}
					
	//$arFilterItems = Array('IBLOCK_ID' => IB_CATALOGUE, "ACTIVE" => "Y", "ACTIVE_DATE" => "Y", "!PROPERTY_HIDE_GENERAL" => "Y", "PROPERTY_ARTICUL" => $arArticle);
	$arFilterItems = Array('IBLOCK_ID' => IB_CATALOGUE, "ACTIVE" => "Y", "ACTIVE_DATE" => "Y", "TAGS" => "Y", "PROPERTY_ARTICUL" => $arArticle);
	$db_list = CIBlockElement::GetList(array(), $arFilterItems, false);
	ob_start();	
	
	$array_item_comp = array(); // убираем дубли
	while($ob = $db_list->GetNextElement()) {
		$item = array_merge($ob->GetFields(), $ob->GetProperties()); 
		
		if (in_array($item['ARTICUL']['VALUE'],$array_item_comp)) continue;
					$array_item_comp[] = $item['ARTICUL']['VALUE'];
		
		$iscomp = 0;
		if ($product['COMPOSITEPART']['VALUE']) $iscomp = 1;
		
			// Группа элемента с ID и Названием
			$res = CIBlockElement::GetByID($item['ID']);
			if($arRes = $res->Fetch()) {
			$res = CIBlockSection::GetByID($arRes["IBLOCK_SECTION_ID"]);
				if($arRes = $res->Fetch()) {
					$section_id = $arRes["ID"];
					$arFilterSection = Array('IBLOCK_ID'=>IB_CATALOGUE, 'ACTIVE'=>'Y', 'ID'=>$section_id);
					$db_list_section = CIBlockSection::GetList(Array(), $arFilterSection, false, array('UF_*'));
					$last_section = $db_list_section->GetNext();
				}
			}
		// если композит или нет цены, укороченная версия без линка
		$no_show = false;
		if($item['IBLOCK_SECTION_ID'] == 1614 || $item['IBLOCK_SECTION_ID'] == 1615 || 
			$item['IBLOCK_SECTION_ID'] == 1616 || $item['IBLOCK_SECTION_ID'] == 1617 || (_makeprice(CPrice::GetBasePrice($item['ID']))['PRICE'] <= 0)) $no_show = true;
		
		if ($no_show) $url_item = '#';
		else $url_item = __get_product_link($item,$test);

$web_path = web_path($item);
$img_path = get_resized_img($web_path);

// Логика изображений превью под вопросом		
$images_path = $_SERVER["DOCUMENT_ROOT"]."/cron/catalog/data/images";
$images_web_path = "/cron/catalog/data/images";
$files_by_type = array();

$img_pre = 200; // Вторая сцена в приоритет - эксперементально

    $img_pre_old = substr($img_pre, 0, 2);

    $path = $images_path . "/" . $img_pre . "/" . $item['ARTICUL']['VALUE'] . '.' . $img_pre . '.png';
    $web_path = $images_web_path . "/" . $img_pre . "/" . $item['ARTICUL']['VALUE'] . '.' . $img_pre . '.png';

    if (!file_exists($path)) {
        $path = $images_path . "/" . $img_pre_old . "/" . $item['ARTICUL']['VALUE'] . '.' . $img_pre_old . '.png';
        $web_path = $images_web_path . "/" . $img_pre_old . "/" . $item['ARTICUL']['VALUE'] . '.' . $img_pre_old . '.png';
    }

    if (file_exists($path)) {
        $files[] = $web_path;
        $files_by_type[$img_pre] = $web_path;
    }

		if ($files_by_type['200']) $img_path =  get_resized_img($files_by_type['200']);
		
		// для каминов 100 отдельно, а то может мутить придеться :))) 
		if (($item['ARTICUL']['VALUE'] == '1.64.801') || ($item['ARTICUL']['VALUE'] == '1.64.803')) {
				$web_path = web_path($item);
				$img_path = get_resized_img($web_path);
		}
		
		?>

							<div class="show-materials-item" style="left:<?=$items[$item['ARTICUL']['VALUE']]['X']?>%;top:<?=$items[$item['ARTICUL']['VALUE']]['Y']?>%;" 
							data-type="prod-prev" data-id="<?=$item['ID']?>" 
							data-name="<?=__get_product_name($item)?>" 
							data-code="<?=$item['INNERCODE']['VALUE']?>" 
							data-price="<?=_makeprice(CPrice::GetBasePrice($item['ID']))['PRICE']?>" 
							data-curr="<?=getCurrency($my_city)?>" 
							data-cat="<?=$last_section['ID']?>" 
							data-cat-name="<?=$last_section['NAME']?>" 
							data-iscomp="<?=$iscomp?>">
                                
								<div class="show-materials-point"></div>
                                <div class="show-materials-popup">
                                    <div class="show-materials-popup-img">
                                        <a href="<?=$url_item?>"></a>
                                        <img src="<?=$img_path?>" alt="<?=__get_product_name($item)?>">
                                    </div>
                                    <div class="show-materials-popup-title">
                                        <a href="<?=$url_item?>"><?=__get_product_name($item)?></a>
                                    </div>
                                    <div class="show-materials-popup-bottom">
										<? if (!$no_show) { ?>
                                        <div class="show-materials-popup-price"><?=__cost_format(_makeprice(CPrice::GetBasePrice($item['ID']))['PRICE'])?></div>
                                        <div class="show-materials-popup-btns">
                                            <div class="show-materials-popup-favorite" data-type="favorite" data-user="no-user">
                                                <i class="icon-star"></i>
                                            </div>
                                            <div class="show-materials-popup-add" data-type="cart-add">
                                                <i class="icon-plus"></i>
                                            </div>
                                        </div>
										<? } ?>
                                    </div>

                                </div>
                            </div>
		
						
<?	}  
							
$html = ob_get_clean();
	
return $html;
}

function get_project_number($n) {
	ob_start();	
	switch($n) {
	case '1': ?>
				<? // 1й слайд ?>
				<div data-type="gallery-slide" class="main-gallery-slide main-gallery-slide-1 main-gallery-slide-vertical">
                    <div class="main-gallery-item-vertical-row">
                        <div class="main-gallery-item show-materials">
                            <a href="#"></a>
                            <img src="/img/gallery/Project-01/Evroplast_main_project-01-1_420х590.jpg?v=1" alt="Проект 1">
						<? // массив элементов X% left,Y% top
						$items = array(
					
						"1.52.301" 	=> array(
										"FLEX"	=> "N",
										"X" 	=> "29",
										"Y"		=> "8",
										),
						"6.51.301" 	=> array(
										"FLEX"	=> "N",
										"X" 	=> "61",
										"Y"		=> "22",
										),
						"6.51.308" 	=> array(
										"FLEX"	=> "N",
										"X" 	=> "51",
										"Y"		=> "69",
										),
						);
					
						echo get_objects_gallery($items);
						?>							
                        </div>
                        <? /*<div class="main-gallery-item resize-bg" style="background-image: url('/img/gallery/Project-01/Evroplast_main_project-01-1_420х380.jpg?v=1')"> */ ?>
                        <div class="main-gallery-item show-materials">
							<a href="#"></a>
                            <img src="/img/gallery/Project-01/Evroplast_main_project-01-1_420х380.jpg?v=1" alt="Проект 1">
							<? // массив элементов X% left,Y% top
						$items = array(
					
						"6.51.308" 	=> array(
										"FLEX"	=> "N",
										"X" 	=> "56",
										"Y"		=> "19",
										),
						"6.51.384" 	=> array(
										"FLEX"	=> "N",
										"X" 	=> "64",
										"Y"		=> "38",
										),
						);
					
						echo get_objects_gallery($items);
						?>							
                        </div>
                    </div>
                    <div class="main-gallery-item show-materials">
                        <a href="#"></a>
                        <img src="/img/gallery/Project-01/Evroplast_main_project-01-1_420х990.jpg?v=1" alt="Проект 1">
						<? // массив элементов X% left,Y% top
						$items = array(
					
						"6.51.301" 	=> array(
										"FLEX"	=> "N",
										"X" 	=> "39",
										"Y"		=> "24",
										),
						"6.51.322" 	=> array(
										"FLEX"	=> "N",
										"X" 	=> "49",
										"Y"		=> "49",
										),
						"1.52.301" 	=> array(
										"FLEX"	=> "N",
										"X" 	=> "8",
										"Y"		=> "66",
										),
						"6.51.384" 	=> array(
										"FLEX"	=> "N",
										"X" 	=> "19",
										"Y"		=> "90",
										),
						);
					
						echo get_objects_gallery($items);
						?>
                    </div>
                </div>
<?	break; 
	case '2': ?>		
				<? // 2й слайд ?>
				<div data-type="gallery-slide" class="main-gallery-slide main-gallery-slide-2 main-gallery-slide-horizontal">
                    <div class="main-gallery-item show-materials">
                        <a href="#"></a>
                        <img src="/img/gallery/Project-02/Evroplast_main_project-02-1_860х990.jpg?v=1" alt="Проект 2">
						<? // массив элементов X% left,Y% top
						$items = array(
					
						"6.59.803" 	=> array(
										"FLEX"	=> "N",
										"X" 	=> "45",
										"Y"		=> "17",
										),
						);
					
						echo get_objects_gallery($items);
						?>
                    </div>
                    <div class="main-gallery-item-horizontal-row">
                        <div class="main-gallery-item show-materials">
                            <a href="#"></a>
                            <img src="/img/gallery/Project-02/Evroplast_main_project-02-1_420х380.jpg?v=1" alt="Проект 2">
						<? // массив элементов X% left,Y% top
						$items = array(
					
						"6.59.802" 	=> array(
										"FLEX"	=> "N",
										"X" 	=> "7",
										"Y"		=> "15",
										),
						);
					
						echo get_objects_gallery($items);
						?>		
                        </div>	
                        <div class="main-gallery-item show-materials">
                            <a href="#"></a>
                            <img src="/img/gallery/Project-02/Evroplast_main_project-02-2_420х380.jpg?v=1" alt="Проект 2"> 
						<? // массив элементов X% left,Y% top
						$items = array(
					
						"6.59.802" 	=> array(
										"FLEX"	=> "N",
										"X" 	=> "20",
										"Y"		=> "3",
										),
						);
					
						echo get_objects_gallery($items);
						?>		
                        </div>
                    </div>
                </div>
<?	break;
	case '3': ?>					
				<? // 3й слайд ?>
                <div data-type="gallery-slide" class="main-gallery-slide main-gallery-slide-3 main-gallery-slide-vertical">
                    <div class="main-gallery-item-vertical-row">
                        <div class="main-gallery-item show-materials">
                            <a href="#"></a>
                            <img src="/img/gallery/Project-03/Evroplast_main_project-03-1_420х590.jpg?v=1" alt="Проект 3">
						<? // массив элементов X% left,Y% top
						$items = array(
					
						"6.51.809" 	=> array(
										"FLEX"	=> "N",
										"X" 	=> "39",
										"Y"		=> "1",
										),
						"6.50.801" 	=> array(
										"FLEX"	=> "N",
										"X" 	=> "71",
										"Y"		=> "10",
										),
						);
					
						echo get_objects_gallery($items);
						?>		    
                        </div>
                        <div class="main-gallery-item show-materials">
                            <a href="#"></a>
                            <img src="/img/gallery/Project-03/Evroplast_main_project-03-1_420х380.jpg?v=1" alt="Проект 3">
                        <? // массив элементов X% left,Y% top
						$items = array(
					
						"6.51.810" 	=> array(
										"FLEX"	=> "N",
										"X" 	=> "9",
										"Y"		=> "9",
										),
						"6.53.804" 	=> array(
										"FLEX"	=> "N",
										"X" 	=> "88",
										"Y"		=> "71",
										),
						);
					
						echo get_objects_gallery($items);
						?>		    
                        </div>
                    </div>
                    <div class="main-gallery-item show-materials">
                        <a href="#"></a>
                        <img src="/img/gallery/Project-03/Evroplast_main_project-03-1_420х990.jpg?v=1" alt="Проект 3">
					<? // массив элементов X% left,Y% top
						$items = array(
					
						"1.52.810" 	=> array(
										"FLEX"	=> "N",
										"X" 	=> "17",
										"Y"		=> "10",
										),
						"1.62.803" 	=> array(
										"FLEX"	=> "N",
										"X" 	=> "61",
										"Y"		=> "10",
										),
						"1.64.803" 	=> array(
										"FLEX"	=> "N",
										"X" 	=> "29",
										"Y"		=> "61",
										),
						"6.50.801" 	=> array(
										"FLEX"	=> "N",
										"X" 	=> "38",
										"Y"		=> "2",
										),
						);
					
						echo get_objects_gallery($items);
						?>	
                    </div>
                </div>
<?	break;
	case '4': ?>					
				<? // 4й слайд ?>
				<div data-type="gallery-slide" class="main-gallery-slide main-gallery-slide-1 main-gallery-slide-vertical">
                    <div class="main-gallery-item-vertical-row">
                        <div class="main-gallery-item show-materials">
                            <a href="#"></a>
                            <img src="/img/gallery/Project-04/Evroplast_main_project-04-1_420х590.jpg?v=1" alt="Проект 4"> 
						<? // массив элементов X% left,Y% top
						$items = array(
					
						"6.59.803" 	=> array(
										"FLEX"	=> "N",
										"X" 	=> "64",
										"Y"		=> "18",
										),
						"6.51.808" 	=> array(
										"FLEX"	=> "N",
										"X" 	=> "86",
										"Y"		=> "55",
										),
						);
					
						echo get_objects_gallery($items);
						?>
                        </div>
                        <div class="main-gallery-item show-materials">
                            <a href="#"></a>
                            <img src="/img/gallery/Project-04/Evroplast_main_project-04-1_420х380.jpg?v=1" alt="Проект 4">
						<? // массив элементов X% left,Y% top
						$items = array(
					
						"6.59.803" 	=> array(
										"FLEX"	=> "N",
										"X" 	=> "66",
										"Y"		=> "7",
										),
						"6.51.808" 	=> array(
										"FLEX"	=> "N",
										"X" 	=> "57",
										"Y"		=> "30",
										),
						"6.53.803" 	=> array(
										"FLEX"	=> "N",
										"X" 	=> "30",
										"Y"		=> "73",
										),
						);
					
						echo get_objects_gallery($items);
						?>
                        </div>
                    </div>
                    <div class="main-gallery-item show-materials">
                        <a href="#"></a>
                        <img src="/img/gallery/Project-04/Evroplast_main_project-04-1_420х990.jpg?v=1" alt="Проект 4">
						<? // массив элементов X% left,Y% top
						$items = array(
					
						"1.51.805" 	=> array(
										"FLEX"	=> "N",
										"X" 	=> "75",
										"Y"		=> "6",
										),
						"6.59.803" 	=> array(
										"FLEX"	=> "N",
										"X" 	=> "32",
										"Y"		=> "34",
										),
						"6.51.808" 	=> array(
										"FLEX"	=> "N",
										"X" 	=> "80",
										"Y"		=> "46",
										),
						"6.53.803" 	=> array(
										"FLEX"	=> "N",
										"X" 	=> "23",
										"Y"		=> "72",
										),
						);
					
						echo get_objects_gallery($items);
						?>
                    </div>
                </div>
<?	break;
	case '5': ?>					
				<? // 5й слайд ?>
                <div data-type="gallery-slide" class="main-gallery-slide main-gallery-slide-3 main-gallery-slide-vertical">
                    <div class="main-gallery-item-vertical-row">
                        <div class="main-gallery-item show-materials">
                            <a href="#"></a>
                            <img src="/img/gallery/Project-05/Evroplast_main_project-05-1_420х590.jpg?v=1" alt="Проект 5">
						<? // массив элементов X% left,Y% top
						$items = array(
					
						"6.50.229" 	=> array(
										"FLEX"	=> "N",
										"X" 	=> "49",
										"Y"		=> "2",
										),
						"6.51.308" 	=> array(
										"FLEX"	=> "N",
										"X" 	=> "9",
										"Y"		=> "21",
										),
						"6.51.384" 	=> array(
										"FLEX"	=> "N",
										"X" 	=> "29",
										"Y"		=> "66",
										),
						);
					
						echo get_objects_gallery($items);
						?>	
                        </div>
                         <div class="main-gallery-item show-materials">
                            <a href="#"></a>
                            <img src="/img/gallery/Project-05/Evroplast_main_project-05-1_420х380.jpg?v=1" alt="Проект 5">
						<? // массив элементов X% left,Y% top
						$items = array(
					
						"6.51.323" 	=> array(
										"FLEX"	=> "N",
										"X" 	=> "9",
										"Y"		=> "32",
										),
						"6.51.301" 	=> array(
										"FLEX"	=> "N",
										"X" 	=> "50",
										"Y"		=> "35",
										),
						"6.51.308" 	=> array(
										"FLEX"	=> "N",
										"X" 	=> "43",
										"Y"		=> "59",
										),
						);
					
						echo get_objects_gallery($items);
						?>	
                        </div>
                    </div>
                    <div class="main-gallery-item show-materials">
                        <a href="#"></a>
                        <img src="/img/gallery/Project-05/Evroplast_main_project-05-1_420х990.jpg?v=1" alt="Проект 5">
					<? // массив элементов X% left,Y% top
						$items = array(
					
						"6.50.229" 	=> array(
										"FLEX"	=> "N",
										"X" 	=> "43",
										"Y"		=> "9",
										),
						"6.51.384" 	=> array(
										"FLEX"	=> "N",
										"X" 	=> "74",
										"Y"		=> "13",
										),
						"6.51.301" 	=> array(
										"FLEX"	=> "N",
										"X" 	=> "76",
										"Y"		=> "42",
										),
						"1.52.301" 	=> array(
										"FLEX"	=> "N",
										"X" 	=> "77",
										"Y"		=> "59",
										),
						"6.53.105" 	=> array(
										"FLEX"	=> "N",
										"X" 	=> "48",
										"Y"		=> "79",
										),
						);
					
						echo get_objects_gallery($items);
						?>	
                    </div>
                </div>	
<?	break;
		
	}

$html = ob_get_clean();

return $html;
}


$orders = array(1,3,4,5); 
shuffle($orders);
array_splice($orders,1,0,2);

foreach($orders as $n) { // Вывод проектов в последовательности (центральная 2я позиция)
	echo get_project_number($n);
}


?>