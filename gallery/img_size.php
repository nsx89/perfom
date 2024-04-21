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
?>

