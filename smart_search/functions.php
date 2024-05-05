<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog") || !CModule::IncludeModule("search")) {
    exit;
}
set_time_limit(6000);
use \Bitrix\Main\Data\Cache;
use \Bitrix\Main\Application;

function createProductCache($count_id,$return = false) {

    global $DB;

    $glue_arr = get_glue_arr(false,$count_id);
    $products = Array();

    $cache = Cache::createInstance(); // Служба кеширования
    $taggedCache = Application::getInstance()->getTaggedCache(); // Служба пометки кеша тегами

    $cachePath = 'smart_search'; // папка, в которой лежит кеш
    //$cacheTtl = 60*60*24; // сутки - срок годности кеша (в секундах)
    $cacheTtl = 2;
    $cacheKey = $count_id.'products'; // имя кеша

    if ($cache->initCache($cacheTtl, $cacheKey, $cachePath))
    {
        if($return) {
            $products = $cache->getVars(); // Получаем переменные
        } else {
            CIBlock::clearIblockTagCache( 12 );
        }
    }
    elseif ($cache->startDataCache())
    {
        // Начинаем записывать теги
        $taggedCache->startTagCache($cachePath);

        // Добавляем теги
        // Кеш сбрасывать при изменении данных в инфоблоке с ID 12
        $taggedCache->registerTag('iblock_id_12');

        // Собираем данные в кеш

        //список разделов
        $section_list = Array();
        $sec_res = CIBlockSection::GetList(Array("SORT"=>"ASC"), Array("IBLOCK_ID" => IB_CATALOGUE,), false, Array('UF_*'));
        while ($sec_ob = $sec_res->GetNext()) {
            $section_list[$sec_ob['ID']] = $sec_ob;
        }

        //список товаров
        $arFilterSS = Array('IBLOCK_ID'=>IB_CATALOGUE, 'ACTIVE'=>'Y', "ACTIVE_DATE" => "Y", '!PROPERTY_SELLOUT' => 'Y');
        //$arFilterSS = Array('IBLOCK_ID'=>IB_CATALOGUE, 'ACTIVE'=>'Y', "ACTIVE_DATE" => "Y", '!SUBSECTION'=> 1614);
        $currency_infо_ss = get_currency_info($count_id);
        $arFilter_element_ss = $currency_infо_ss['filter'];
        if($arFilter_element_ss) $arFilterSS[] = array("LOGIC" => "OR", $arFilter_element_ss, array(">PROPERTY_COMPOSITEPART" => 0));
        
        /* --- Точный поиск по артикулу --- */
        $FIND_BY_ARTICUL = false;
        $FIND_BY_ARTICUL_VALUE = htmlspecialcharsbx($_REQUEST['FIND_BY_ARTICUL_VALUE']);
        if (!empty($FIND_BY_ARTICUL_VALUE)) {
            $ARTS_OFF = 
            $resSS = CIBlockElement::GetList(Array('NAME'=>'asc','PROPERTY_ARTICUL'=>'asc'), Array('IBLOCK_ID'=>IB_CATALOGUE, 'PROPERTY_ARTICUL' => $FIND_BY_ARTICUL_VALUE, '!TAGS' => 'OFF'), false, array(), array());
            $FIND_BY_ARTICUL = true;
        }
        else {
            $resSS = CIBlockElement::GetList(Array('NAME'=>'asc','PROPERTY_ARTICUL'=>'asc'), $arFilterSS, false, array(), array());
        }
        /* --- // --- */

        //print_r(intval($resSS->SelectedRowsCount()));
        while($obSS = $resSS->GetNextElement()) {
            $item = array_merge($obSS->GetFields(), $obSS->GetProperties());
            
            //исключаем клей
            if (!$FIND_BY_ARTICUL) {
                if($item['IBLOCK_SECTION_ID'] == 1587 && !in_array($item['ID'],$glue_arr)) continue;
            }

            $checkPrice = '';
            $checkPrice = __get_product_cost($item);
            
            // исключаем если нет цены
            if (!$FIND_BY_ARTICUL) {
                if (($checkPrice == 0)||($checkPrice == '')) continue;
            }

            $web_path = web_path($item);
            $img_path = get_resized_img($web_path,330,330);
            if($img_path == '' || !$img_path) $img_path = $web_path;
            $iscomp = 0;
            if ($item['COMPOSITEPART']['VALUE']) $iscomp = 1;
            //$article_foil = array('6.50.711', '6.50.712', '6.50.713', '6.50.714', '6.50.715', '6.50.716', '6.50.719', '6.51.710', );
            $article_foil = array();
            if ($section_list[$item['IBLOCK_SECTION_ID']]['UF_H'] == 1) {
                $signTmp = ' prod-prev-h';
            } elseif ($section_list[$item['IBLOCK_SECTION_ID']]['UF_V'] == 1) {
                $signTmp = ' prod-prev-v';
            } else {
                $signTmp = '';
            }
            $datetime = date('d.m.Y H:i:s');
            $dateproduct = date($DB->DateFormatToPHP($item['DATE_CREATE']));
            $sub_date = ceil((strtotime($datetime)-strtotime($dateproduct))/86400);
            $new = false;
            if ((($sub_date < 150) && ($item['TIME_NEW_OFF']['VALUE'] != "Y")) || ($item['NEW_ON']['VALUE'] == "Y")) {
                if($item['ARTICUL']['VALUE'] != 'EB05.M.290' && $item['ARTICUL']['VALUE'] != 'EB06.S.80') {
                    $new = true;
                }
            }
            $available_to_sell = true;
            $coming_soon = false;
            $available_date = '';
            if($item['NO_ORDER']['VALUE'] != 'Y' && $checkPrice && $item['COMING_SOON']['VALUE']!='Y') {
                if($item['OUT_OF_STOCK']['VALUE'] == 'Y' && $my_city == '3109') {
                    $available_to_sell = false;
                    $available_date = '31&nbsp;мая&nbsp;2020 г.';
                } else {
                    $available_to_sell = true;
                }
            } elseif($item['COMING_SOON']['VALUE']=='Y') {
                $coming_soon = true;
            } else {
                $available_to_sell = false;
            }
            $is_flex = $item['FLEX']['VALUE'] == 'Y' ? true : false;
            $res = item_param($item);
            $res_param = array_merge($res['res_s'],$res['res_f']);
            //if($is_flex) $res_param = $res['res_f'];

            $sellout = $item['SELLOUT']['VALUE'] == 'Y' ? true : false;
            $old_price = _makeprice($item['OLD_PRICE']['VALUE']);

            $products[] = Array(
                "name"              => __get_product_name($item),
                "id"                => $item['ID'],
                "price"             => $checkPrice,
                "link"              => __get_product_link($item),
                "img"               => $img_path,
                "iscomp"            => $iscomp,
                "code"              => $item['INNERCODE']['VALUE'],
                "catId"             => $item['IBLOCK_SECTION_ID'],
                "catName"           => $section_list[$item['IBLOCK_SECTION_ID']]['NAME'],
                "class"             => $signTmp.flexTmp($item),
                "maur"              => $item['MAURITANIA_SPECIAL']['VALUE']=='Y' ? '  maur-spec="1"' : "",
                "comingSoon"        => $item['COMING_SOON']['VALUE'],
                "article"           => $item['ARTICUL']['VALUE'],
                "foil"              => in_array($item['ARTICUL']['VALUE'], $article_foil),
                "new"               => $new,
                "availableToSell"   => $available_to_sell,
                "availableDate"     => $available_date,
                "comingSoon"        => $coming_soon,
                "flex"              => $is_flex,
                "params"            => $res_param,
                "sellout"           => $sellout,
                "old_price"         => $old_price, 
            );
        }

        // Если что-то пошло не так и решили кеш не записывать
        $cacheInvalid = false;
        if ($cacheInvalid)
        {
            $taggedCache->abortTagCache();
            $cache->abortDataCache();
        }

        // Всё хорошо, записываем кеш
        $taggedCache->endTagCache();
        $cache->endDataCache($products);


    }
    if($return) {
        return $products;
    }
}