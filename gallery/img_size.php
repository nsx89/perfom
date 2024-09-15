<?

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}

function imgSize($obj_num,$img_num,$obj_img,$obj_dir,$flex=null) { 
    if (isset($flex)) $flex;
    else $flex = "N";

    $index_img_height = 700;
    $path_prew =$_SERVER["DOCUMENT_ROOT"].'/gallery/objects/'.$obj_dir.'/';
    list($img_width, $img_height) = getimagesize($path_prew.$obj_num.'_'.$img_num.'.jpg');
    if ($img_height > $index_img_height) {
        $img_alfa = $img_height/$index_img_height;
        $img_height = $index_img_height;
        $img_width = round($img_width/$img_alfa);
    }

    $cont = '<div class="img_size_form gallery-img" data-type="gallery-slide">';
    $cont .= '<img src="/gallery/objects/'.$obj_dir.'/'.$obj_num.'_'.$img_num.'.jpg" alt="фотогалерея'.$img_num.'">';

    foreach ($obj_img as $item_img) {
        $item_arr = explode(" ", $item_img[3]);
        $arr_leng = count($item_arr);
        if ($flex == 'Y') $item_article = $item_arr[$arr_leng - 2];
        else $item_article = $item_arr[$arr_leng - 1];
        /*$arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, "ACTIVE" => "Y", "ACTIVE_DATE" => "Y", array("PROPERTY_FLEX" => $flex, "PROPERTY_ARTICUL" => $item_article));
        $db_list = CIBlockElement::GetList(Array("SORT" => "ASC"), $arFilter);
        if ($ob_item = $db_list->GetNextElement()) {
            $ob_item = array_merge($ob_item->GetFields(), $ob_item->GetProperties());
        }*/
        $left = round(100/$item_img[1],2);
        $top = round(100/$item_img[2],2);
        $cont .= '<div class="content-wrapper-g gallery1">';
        $items = array();
        $items[$item_article] = array(
            "FLEX" => $flex,
            "X" => $left,
            "Y" => $top,
            "NUMBER" => $item_img[0],
        );
        $cont .= get_objects_gallery($items);
        /*$cont .= '<div class="obj-elem-size show-materials-item" style="left: '.$left.'%;top:'.$top.'%;">';
        $cont .= '<div class="element-number-item show-materials-point" data-type="gallery-number"><span>'.$item_img[0].'</span></div>';
        $cont .= '<div class="element-link-item">';

        $ob_item_link = __get_product_link($ob_item);

        $cont .= '<a href="'.$ob_item_link.'" target="_blank">'.$item_img[3].'</a>';
        $cont .= '</div>';
        $cont .= '</div>';*/
        $cont .= '</div>';

    }
    $cont .= '</div>';

    return $cont;
}


function get_objects_gallery($items = array()) {
    global $my_city;
    $arProps[] = array(
        "LOGIC" => "OR"
    );

    foreach($items as $key => $item) {
        //$arProps[] = array("TAGS" => "Y", "=PROPERTY_ARTICUL" => $key,"PROPERTY_FLEX" => $item['FLEX']);
        $arProps[] = array("=PROPERTY_ARTICUL" => $key);
    }

    //$arFilterItems = Array('IBLOCK_ID' => IB_CATALOGUE, "ACTIVE" => "Y", "ACTIVE_DATE" => "Y", "!PROPERTY_HIDE_GENERAL" => "Y", $arProps);
    
    //$arFilterItems = Array('IBLOCK_ID' => IB_CATALOGUE, "ACTIVE" => "Y", "ACTIVE_DATE" => "Y", $arProps);

    $arFilterItems = Array('IBLOCK_ID' => IB_CATALOGUE, "PROPERTY_SHOW_PERFOM" => "Y", $arProps);
    
    //$arFilterItems = Array('IBLOCK_ID' => IB_CATALOGUE, $arProps);
    $db_list = CIBlockElement::GetList(array(), $arFilterItems, false);

    ob_start();

    $array_item_comp = array(); // убираем дубли
    while($ob = $db_list->GetNextElement()) {
        $item = array_merge($ob->GetFields(), $ob->GetProperties());

        if (in_array($item['ARTICUL']['VALUE'],$array_item_comp)) continue;
        $array_item_comp[] = $item['ARTICUL']['VALUE'];

        $iscomp = 0;
        if ($item['COMPOSITEPART']['VALUE']) $iscomp = 1;
        // Группа элемента с ID и Названием
        $res = CIBlockElement::GetByID($item['ID']);
        if($arRes = $res->Fetch()) {
            $res = CIBlockSection::GetByID($arRes["IBLOCK_SECTION_ID"]);
            if($arRes = $res->Fetch()) {
                $section_id = $arRes["ID"];
                $arFilterSection = Array('IBLOCK_ID'=>IB_CATALOGUE, 'ACTIVE'=>'Y', 'ID'=>$section_id);
                $db_list_section = CIBlockSection::GetList(Array(), $arFilterSection, false, array());
                $last_section = $db_list_section->GetNext();
            }
        }

        $PRICE = _makeprice(CPrice::GetBasePrice($item['ID']))['PRICE'];
        $NAME = __get_product_name($item);

                // если композит или нет цены, укороченная версия без линка
        $no_show = false;
        if($item['IBLOCK_SECTION_ID'] == 1614 || $item['IBLOCK_SECTION_ID'] == 1615 ||
            $item['IBLOCK_SECTION_ID'] == 1616 || $item['IBLOCK_SECTION_ID'] == 1617 || $PRICE <= 0) $no_show = true;




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

        <div class="obj-elem-size show-materials-item" style="left:<?=$items[$item['ARTICUL']['VALUE']]['X']?>%;top:<?=$items[$item['ARTICUL']['VALUE']]['Y']?>%;"
             data-type="prod-prev" data-id="<?=$item['ID']?>"
             data-name="<?=$NAME?>"
             data-code="<?=$item['INNERCODE']['VALUE']?>"
             data-price="<?=$PRICE?>"
             data-curr="<?=getCurrency($my_city)?>"
             data-cat="<?=$last_section['ID']?>"
             data-cat-name="<?=$last_section['NAME']?>"
             data-iscomp="<?=$iscomp?>">

            <div class="element-number-item show-materials-point" data-type="gallery-number"><span><?=$items[$item['ARTICUL']['VALUE']]['NUMBER']?></span></div>
            <div class="show-materials-popup gallery">
                <div class="show-materials-popup-img">
                    <a href="<?=$url_item?>"></a>
                    <img src="<?=$img_path?>" alt="<?= $NAME ?>">
                </div>
                <div class="show-materials-popup-title">
                    <a href="<?=$url_item?>"><?= $NAME ?></a>
                </div>
                <div class="show-materials-popup-bottom">
                    <? if (!$no_show) { ?>
                        <div class="show-materials-popup-price"><?=__cost_format($PRICE)?></div>
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


    <?  }

    $html = ob_get_clean();

    return $html;
}
?>

