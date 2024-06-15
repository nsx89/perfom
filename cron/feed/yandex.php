<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule('iblock');

//set_time_limit(1000000000);
ini_set('memory_limit','2048M');

/*
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
*/

$server = 'https://'.$_SERVER['SERVER_NAME'];

$content = '<?xml version="1.0" encoding="UTF-8"?>
    <yml_catalog date="'.date('Y-m-d H:i').'">
    <shop>
        <name>Перфом</name>
        <company>ООО "Декор"</company>
        <url>https://'.$_SERVER['SERVER_NAME'].'/</url>
        <currencies>
            <currency id="RUR" rate="1"/>
        </currencies>';

        /* --- Категории --- */
        $content .= '<categories>';
            $content .= '<category id="189">Интерьерный декор</category>';
            $cat_ids = build_drop_categories_ids(); //id категорий для показа
            $arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, 'GLOBAL_ACTIVE' => 'Y', 'ACTIVE' => 'Y', '=UF_HIDECATALOG' => '0', 'ID' => $cat_ids);
            $db_list = CIBlockSection::GetList(Array("SORT" => "ASC"), $arFilter, false, array());
            while ($section_row = $db_list->GetNextElement()) {
                $section = array_merge($section_row->GetFields(), $section_row->GetProperties());
                $content .= "<category id='{$section['ID']}' parentId='{$section['IBLOCK_SECTION_ID']}'>{$section['NAME']}</category>";

            }
            $content .= "<category id='1587'>клей</category>";
        $content .= '</categories>';
        /* --- // --- */

        /* --- Товары --- */
        $content .= ' <offers>';
       
        //$CATALOG_FILTER = Array('IBLOCK_ID' => IB_CATALOGUE, "!TAGS" => "OFF"); //весь каталог
        $CATALOG_FILTER = Array('IBLOCK_ID' => IB_CATALOGUE, 'INCLUDE_SUBSECTIONS' => 'Y', "ACTIVE" => "Y", "ACTIVE_DATE" => "Y", "TAGS" => "Y");
        $product_res = CIBlockElement::GetList(Array('PROPERTY_SORT_FIRST' => 'DESC', 'PROPERTY_SORT1' => 'DESC', 'PROPERTY_SORT'=>'DESC'), $CATALOG_FILTER);
        $i = 0;
        while ($product_row = $product_res->GetNextElement()) {
            $item = array_merge($product_row->GetFields(), $product_row->GetProperties());
            
            $ARTICUL = $item['ARTICUL']['VALUE'];
            $PRICE = CPrice::GetBasePrice($item['ID'])['PRICE'];
            $is_flex = $item['FLEX']['VALUE'] == 'Y' ? true : false;

            $web_path = web_path($item);
            $img_path = get_resized_img($web_path,330,330);
            if($img_path == '' || !$img_path) $img_path = $web_path;

            $params = '';

            $art_val = explode('.',trim($ARTICUL));
            $mat_name = '';
            if($art_val[0] == 1 || $art_val[0] == 4) $mat_name = 'пенополиуретан';
            if($art_val[0] == 6) $mat_name = 'перфом';

            if (!empty($mat_name)) $params .= '<param name="Материал">'.$mat_name.'</param>'.PHP_EOL;

            /* --- Параметры --- */
            $res = item_param($item);
            $res_param = array_merge($res['res_s'],$res['res_f']);
            if($is_flex && $is_flex == 'flex') $res_param = $res['res_f'];
            if (count($res_param)) {
                foreach ($res_param as $pname => $pitem) {
                    $pitem = preg_replace('/[^0-9]/', '', $pitem);
                    switch ($pname) {
                        case 'Ширина по потолку':
                            $params .= '<param name="Ширина" unit="мм">'.$pitem.'</param>'.PHP_EOL;
                            break;
                        case 'Высота по стене':
                            $params .= '<param name="Высота" unit="мм">'.$pitem.'</param>'.PHP_EOL;
                            break;
                        case 'Длина детали':
                            $params .= '<param name="Длина детали" unit="мм">'.$pitem.'</param>'.PHP_EOL;
                            break;
                    }
                }
            }
            /* --- // --- */

            $content .= '<offer id="'.$item['ID'].'" available="true">
                <url>'.$server.$item['DETAIL_PAGE_URL'].'/</url>
                <price>'.$PRICE.'</price>
                <currencyId>RUB</currencyId>
                <categoryId>'.$item['IBLOCK_SECTION_ID'].'</categoryId>
                <picture>'.$server.$img_path.'</picture>
                <name>'.clear(__get_product_name($item)).'</name>
                <description/>
                '.$params.' 
            </offer>'.PHP_EOL;

            $i++;

            //if ($i > 2) break;
        }
        $content .= ' </offers>'.PHP_EOL;
        /* --- // --- */

    $content .= '</shop>
</yml_catalog>';


//echo $i.$content;


$dom_xml = new DomDocument();
$dom_xml->loadXML($content);
$dom_xml->save("yandex.xml");


function clear($value){
    $value = str_replace('&nbsp;', ' ', $value);
    $value = strip_tags($value, '<br>');
    $value = htmlspecialchars($value);
    //$value = trim($value);
    //$value = stripslashes($value);
    return $value;
}

//require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_after.php");
?>