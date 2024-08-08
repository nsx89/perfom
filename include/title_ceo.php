<?
$url = $_SERVER['REQUEST_URI']; 
$url = explode('?', $url);
$url = $url[0]; // основной путь без параметров.
// добавляем конечный слэш если отсутствует
if (strlen($url)>1) { // если не главная
        if (rtrim($url,'/')."/"!=$url) $url = $url.'/';
}

/*
$ipropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($product['IBLOCK_ID'], $product['ID']);
$IPROPERTY = $ipropValues->getValues();
//print_r($IPROPERTY);
if(isset($IPROPERTY['ELEMENT_META_TITLE'])) $title = $IPROPERTY['ELEMENT_META_TITLE'];
if(isset($IPROPERTY['ELEMENT_META_DESCRIPTION'])) $description = $IPROPERTY['ELEMENT_META_DESCRIPTION'];

$APPLICATION->SetTitle($title);
$APPLICATION->SetPageProperty("description", $description);
*/

$title = '';
$description = '';
$keywords = '';

switch ($url) {
    case '/media/': $title = 'Блог с полезной информацией от «Перфом»'; $description = 'Новости, события, мероприятия, тренды и другая интересная и полезная информация от компании «Перфом»'; $keywords = 'блог, полезная информация, полезные статьи'; break;
}

//Формрование для каталога (новое)

$iblockid = 12;

$sections = $_SERVER['REQUEST_URI'];
$sections = explode('?', $sections);
$sections = $sections[0];
$sections = explode('/', $sections);
$sections = array_diff($sections,array(''));

if(!empty($sections)) $sections = explode("|", implode("|", $sections));

if ($sections[count($sections)-1] == 'catalogue') {$sections[0] = 'interernyj-dekor'; $sections[1] = 'karnizy';}
if (($sections[count($sections)-1] == 'interernyj-dekor') ) {$sections[1] = 'karnizy';}
if (($sections[count($sections)-1] == 'fasadnyj-dekor')) {$sections[1] = 'antablementy'; $sections[2] = 'karnizi';}

for ($i = count($sections)-1; $i >= 0; $i--) {
    $arFilter = Array('IBLOCK_ID'=>$iblockid, 'CODE'=>$sections[$i], 'ACTIVE'=>'Y');
    $db_list = CIBlockSection::GetList(Array(), $arFilter, false, array('UF_*'));
    $section_item = $db_list->GetNext();
    if($section_item) {
        //если раздел
        $last_section = $section_item;
        $section_id = $section_item['ID'];
        break;
    } else {
        //проверим, может товар
        if (count($sections)-1 == $i) {
            $arFilter = Array('IBLOCK_ID'=>$iblockid, 'CODE'=>$sections[$i], 'ACTIVE'=>'Y');
            $db_list = CIBlockElement::GetList(Array('NAME'=>'ASC'), $arFilter);
            $product_item = $db_list->GetNextElement();
            if (!$product_item) {
                continue;
            }
            $is_product = array_merge($product_item->GetFields(), $product_item->GetProperties());
            $section_id = $is_product['IBLOCK_SECTION_ID'];
            break;
        } else {
            continue;
        }
    }
}

$sections_list = CIBlockSection::GetNavChain($iblockid, $section_id, array(), true);
if($sections_list[0]['CODE'] == 'fasadnyj-dekor') $mother_section = 'Фасадный декор';
if($sections_list[0]['CODE'] == 'interernyj-dekor') $mother_section = "Интерьерный декор";;

if(isset($is_product)) {
    $fbq_ViewContent = true; // Для скрипта пикселя Фейсбука
    $f = '';
    if ($is_product['FLEX']['VALUE'] == 'Y') {
        $is_product['NAME'] = 'гибкий '.trim(str_replace('FLEX','',$is_product['NAME']));
        if (strpos($is_product['NAME'],'арочное') !== false) $is_product['NAME'] = str_replace('гибкий','гибкое',$is_product['NAME']);
        $f = '.f';
    }
    $ceo_prod_articul = $is_product['ARTICUL']['VALUE'] != 'EB06.S.80' &&  $is_product['ARTICUL']['VALUE'] != 'EB05.M.290'? ' '.$is_product['ARTICUL']['VALUE'] : '';
    $title = 'Купить '.$is_product['NAME'].$ceo_prod_articul.$f.' Перфом';
    //$title = $is_product['NAME'].' '.$is_product['ARTICUL']['VALUE'].$f;
    $description = $mother_section.': '.$is_product['NAME'].$ceo_prod_articul.$f.' - цена, размеры, 3D модель.';
    $fb_img = web_path($is_product);
    $fb_availability = $is_product['COMING_SOON']['VALUE']=='Y' ? 'out of stock' : 'in stock';
    $fb_cost = __get_product_cost($is_product);
    $fb_city = $APPLICATION->get_cookie('my_city');
    $fb_og = '<meta property="og:title" content="'.$is_product['NAME'].$ceo_prod_articul.$f.'">';
    $fb_og .= '<meta property="og:image" content="https://perfom-decor.ru'.$fb_img.'">';
    if($fb_cost) {
        $fb_og .= '<meta property="og:price:amount" content="'.$fb_cost.'">';
        $fb_og .= '<meta property="og:price:currency" content="'.getCurrency($fb_city).'">';
    }
    $fb_og .= '<meta property="product:brand" content="Перфом">';
    $fb_og .= '<meta property="product:availability" content="'.$fb_availability.'">';
    $fb_og .= '<meta property="product:condition" content="new">';
    if($fb_cost) {
        $fb_og .= '<meta property="product:price:amount" content="'.$fb_cost.'">';
        $fb_og .= '<meta property="product:price:currency" content="'.getCurrency($fb_city).'">';
    }
    $fb_og .= '<meta property="product:retailer_item_id" content="'.$is_product['ID'].'">';
    $fb_og .= '<meta property="product:item_group_id" content="'.$is_product['IBLOCK_SECTION_ID'].'">';
}
elseif(isset($last_section)) {
    // Технические заголовки для каталогов с фильтром
    // Кеширование
    $obCache = new CPHPCache;
    $life_Time = 86400;
    $cache_ID = "technical_title_001113";

    if ($obCache->InitCache($life_Time, $cache_ID, "/")) {
        $vars = $obCache->GetVars();
        $technical_title = $vars["TECHNICAL_TITLE"];
        //echo "КЕШШШШ";
    } elseif ($obCache->StartDataCache()) {
        $technical_title = array();
        if (($fp = fopen($_SERVER["DOCUMENT_ROOT"] . "/include/technical_title.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($fp, 0, ";")) !== FALSE) {
                $in_url = explode("?",$data[2]);
                if (substr($in_url[0], -1) != "/") {
                    $in_url = $in_url[0]."/?".$in_url[1];
                    $data[2] = $in_url;
                }
                $technical_title[] = $data;
            }
            fclose($fp);
        }
        // Буферизация TECHNICAL_TITLE
        $obCache->EndDataCache(array("TECHNICAL_TITLE" => $technical_title));
        //echo "НЕ КЕШШШ";
    }

    $c_url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    $key_title = array_search($c_url, array_column($technical_title, 2));

    if (false !== $key_title) {
        $title = $technical_title[$key_title][0];
        $description = $technical_title[$key_title][1];
    } else {
        switch ($url) {
        default: 
        // Переинование
        switch ($last_section['CODE']) {
            case 'dopolnitelnye-elementy-antablementy' : $last_section['NAME'] = 'антаблементы';
            case 'dopolnitelnye-elementy-baljustrady' : $last_section['NAME'] = 'балюстрады';
        }
        $title = 'Купить '.$last_section['NAME'].' Перфом';
        $description = $mother_section.': '.$last_section['NAME'].' Перфом.';
        }
    }

}

// Формирование для галлереи.
if (strpos($url, '/gallery/') !== false) {
$id_item = explode('/', $url);
$id_item = array_diff($id_item,array(''));
$id_item = explode("|", implode("|", $id_item));
$id_item = $id_item[count($id_item)-1];

            $arFilter = Array('IBLOCK_ID'=>46, 'ID'=>$id_item, 'ACTIVE'=>'Y');
            $db_list = CIBlockElement::GetList(Array('NAME'=>'ASC'), $arFilter);
            $product_item = $db_list->GetNextElement();
            if ($product_item) {
                $item = array_merge($product_item->GetFields(), $product_item->GetProperties());    
            
                $title = $item['NAME'];
                $description = 'Перфом - производство полиуретановых изделий, лидер на российском рынке';
            }
}

// Формирование для Блог.
if (strpos($url, '/mag/') !== false) {
$id_item = explode('/', $url);
$id_item = array_diff($id_item,array(''));
$id_item = explode("|", implode("|", $id_item));
$id_item = $id_item[count($id_item)-1];

            $arFilter = Array('IBLOCK_ID'=>48, 'ID'=>$id_item, 'ACTIVE'=>'Y');
            $db_list = CIBlockElement::GetList(Array('NAME'=>'ASC'), $arFilter);
            $product_item = $db_list->GetNextElement();
            if ($product_item) {
                $item = array_merge($product_item->GetFields(), $product_item->GetProperties());    
                
                //$title = $item['NEWS_TAGS']['VALUE'][0].': '.$item['NAME'];
                $title = $item['NAME'];
                $description = 'Перфом - производство полиуретановых изделий, лидер на российском рынке';
            }
}


/* --- NEW SEO LINKS --- */

include_once 'seo/new.php';

/* --- // --- */


//echo $url.'-'.$title;

/* --- SEO FOR SUBDOMAINS --- */

if (1 == 1) {
    $CITY_ID = $APPLICATION->get_cookie('my_city');
    $arFilter = Array('IBLOCK_ID'=>7, 'ID'=>$CITY_ID);
    $db_list = CIBlockElement::GetList(Array(), $arFilter);
    $city_item = $db_list->GetNextElement();
    if ($city_item) {
        $city_info = array_merge($city_item->GetFields(), $city_item->GetProperties());
        $CITY_NAME = $city_info['NAME'];
        $CITY_NAME_2=$city_info['NAME'];
        $PROP_NAME = $city_info['name']['VALUE'];
        if (!empty($PROP_NAME)) $CITY_NAME = $PROP_NAME;
        // echo $CITY_NAME;
        //echo '<pre>';print_r($city_info);echo '</pre>';
    }

    $subdomen = _get_city_loc($CITY_ID);

    $encoding = 'UTF8';
    if (!empty($subdomen) && !empty($CITY_NAME)) {

        //Если товар
        if(isset($is_product)) {
            $is_product_name = mb_strtoupper(mb_substr($is_product['NAME'].$ceo_prod_articul.$f, 0, 1, $encoding), $encoding) .
                mb_substr($is_product['NAME'].$ceo_prod_articul.$f, 1, mb_strlen($is_product['NAME'].$ceo_prod_articul.$f, $encoding), $encoding);
            $title =  $is_product_name." из полистирола купить в {$CITY_NAME} от Перфом";
            $description =$is_product_name." из пенополистирола купить по выгодной цене в {$CITY_NAME}, а также в других городах России от компании Перфом.";
            $keywords=$is_product_name.", купить, цена, стоимость, {$CITY_NAME_2}, заказ, заказать, оптом, розница, композит, полистирол, пенополистирол, ппс, композитный материал, фото, характеристики, 3d модель, инструкция, перфом";
        }
        //Если категория
        elseif(isset($last_section)) {
            $title = mb_strtoupper(mb_substr($last_section['NAME'], 0, 1, $encoding), $encoding) .
                mb_substr($last_section['NAME'], 1, mb_strlen($last_section['NAME'], $encoding), $encoding)." купить в {$CITY_NAME} от Перфом";
            $description = mb_strtoupper(mb_substr($last_section['NAME'], 0, 1, $encoding), $encoding) .
                mb_substr($last_section['NAME'], 1, mb_strlen($last_section['NAME'], $encoding), $encoding)." купить по выгодной цене в {$CITY_NAME}, а также в других городах России от компании Перфом.";
            $keywords =  mb_strtoupper(mb_substr($last_section['NAME'], 0, 1, $encoding), $encoding) .
                mb_substr($last_section['NAME'], 1, mb_strlen($last_section['NAME'], $encoding), $encoding).", купить, цена, стоимость, {$CITY_NAME_2}, заказ, заказать, оптом, розница, композит, полистирол, пенополистирол, ппс, композитный материал, перфом";
        }
        else {
            switch ($url) {
                case '/': $title = 'Официальный сайт Перфом - производство лепнины и архитектурного декора'; $description = 'Купить лепнину Перфом в интернет-магазине, а также в розничных точках по всей территории России, СНГ и стран Балтии.'; $keywords = "карнизы, молдинги, плинтусы, архитравы, угловые элементы, розетки, пилястры, колонны, полуколонны, обрамление арок, обрамление дверей, сандрики, ниши, кессоны, купола, кронштейны, орнаменты, перфом, {$CITY_NAME_2}"; break;
                case '/contact/': $title = 'Контактная информация компании «Перфом» в '.$CITY_NAME; $description = 'Контакты компании «Перфом» в ' .$CITY_NAME.': адрес, телефон, время работы, филиалы, e-mail'; $keywords = 'контакты, контактная информация, адрес, телефон, время работы, филиалы, e mail, электронная почта, главный офис, перфом, ' .$CITY_NAME_2; break;
                case '/wheretobuy/': $title = 'Где купить продукцию компании «Перфом» в '.$CITY_NAME; $description = 'Адреса, где можно купить продукцию компании «Перфом» в '.$CITY_NAME; $keywords = "где купить продукцию перфом в {$CITY_NAME}, где купить товар перфом в {$CITY_NAME}"; break;
            }
        }
    }

    //Отдельные индивидуальные Meta
    if (!empty($CITY_NAME)) {
        switch ($url) {
            case '/karnizy/': $title = 'Карнизы из полистирола купить в '.$CITY_NAME.' от Перфом'; $description = 'Карнизы из пенополистирола купить по выгодной цене в '.$CITY_NAME.', а также в других городах России от компании Перфом'; $keywords = 'Карнизы, потолочный карниз, карниз на потолок, купить, цена, стоимость, '.$CITY_NAME_2.', заказ, заказать, оптом, розница, композит, полистирол, пенополистирол, ппс, композитный материал, перфом'; break;
            case '/moldingi/': $title = 'Молдинги из полистирола купить в '.$CITY_NAME.' от Перфом'; $description = 'Молдинги из пенополистирола купить по выгодной цене в '.$CITY_NAME.', а также в других городах России от компании Перфом'; $keywords = 'Молдинги, молдинг на стену, купить, цена, стоимость, '.$CITY_NAME_2.', заказ, заказать, оптом, розница, композит, полистирол, пенополистирол, ппс, композитный материал, перфом'; break;
            case '/plintusy/': $title = 'Плинтусы из полистирола купить в '.$CITY_NAME.' от Перфом'; $description = 'Плинтусы из пенополистирола купить по выгодной цене в '.$CITY_NAME.', а также в других городах России от компании Перфом'; $keywords = 'Плинтусы, напольный плинтус, купить, цена, стоимость, '.$CITY_NAME_2.', заказ, заказать, оптом, розница, композит, полистирол, пенополистирол, ппс, композитный материал, перфом'; break;
            case '/rozetki/': $title = 'Розетки из полистирола купить в '.$CITY_NAME.' от Перфом'; $description = 'Розетки из пенополистирола купить по выгодной цене в '.$CITY_NAME.', а также в других городах России от компании Перфом'; $keywords = 'Розетки, потолочные розетки, розетки под люстру, купить, цена, стоимость, '.$CITY_NAME_2.', заказ, заказать, оптом, розница, композит, полистирол, пенополистирол, ппс, композитный материал, перфом'; break;
            case '/dekorativnii-paneli/': $title = 'Декоративные панели из полистирола купить в '.$CITY_NAME.' от Перфом'; $description = 'Декоративные панели из пенополистирола купить по выгодной цене в '.$CITY_NAME.', а также в других городах России от компании Перфом'; $keywords = 'Декоративные панели, декоративные панели на стену, стеновая декоративная панель, купить, цена, стоимость, '.$CITY_NAME_2.', заказ, заказать, оптом, розница, композит, полистирол, пенополистирол, ппс, композитный материал, перфом'; break;
            case '/obramlenie-proema/': $title = 'Обрамление проёма из полистирола купить в '.$CITY_NAME.' от Перфом'; $description = 'Обрамление проёма из пенополистирола купить по выгодной цене в '.$CITY_NAME.', а также в других городах России от компании Перфом'; $keywords = 'Обрамление проёма, обрамление арок, замковый камень, наличники, база, купить, цена, стоимость, '.$CITY_NAME_2.', заказ, заказать, оптом, розница, композит, полистирол, пенополистирол, ппс, композитный материал, перфом'; break;
            case '/kessony-i-kupola/': $title = 'Кессоны и купола из полистирола купить в '.$CITY_NAME.' от Перфом'; $description = 'Кессоны и купола из пенополистирола купить по выгодной цене в '.$CITY_NAME.', а также в других городах России от компании Перфом'; $keywords = 'Кессоны и купола, кессоны, купола, купить, цена, стоимость, '.$CITY_NAME_2.', заказ, заказать, оптом, розница, композит, полистирол, пенополистирол, ппс, композитный материал, перфом'; break;
            case '/uglovye-jelementy/': $title = 'Угловые элементы из полистирола купить в '.$CITY_NAME.' от Перфом'; $description = 'Угловые элементы из пенополистирола купить по выгодной цене в '.$CITY_NAME.', а также в других городах России от компании Перфом'; $keywords = 'Угловые элементы, декоративные угловые элементы, купить, цена, стоимость, '.$CITY_NAME_2.', заказ, заказать, оптом, розница, композит, полистирол, пенополистирол, ппс, композитный материал, перфом'; break;
            case '/podokonnye-elementy/': $title = 'Подоконные элементы из полистирола купить в '.$CITY_NAME.' от Перфом'; $description = 'Подоконные элементы из пенополистирола купить по выгодной цене в '.$CITY_NAME.', а также в других городах России от компании Перфом'; $keywords = 'Подоконные элементы, торцевые элементы, купить, цена, стоимость, '.$CITY_NAME_2.', заказ, заказать, оптом, розница, композит, полистирол, пенополистирол, ппс, композитный материал, перфом'; break;
            case '/nalichniki/': $title = 'Наличники из полистирола купить в '.$CITY_NAME.' от Перфом'; $description = 'Наличники из пенополистирола купить по выгодной цене в '.$CITY_NAME.', а также в других городах России от компании Перфом'; $keywords = 'Наличники, декоративные наличники, купить, цена, стоимость, '.$CITY_NAME_2.', заказ, заказать, оптом, розница, композит, полистирол, пенополистирол, ппс, композитный материал, перфом'; break;
        }
    }

    //META из админки (для категорий)
    if(isset($last_section)) {
        $ipropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues(12, $last_section['ID']);
        $seo = $ipropValues->getValues();
        if (!empty($seo['SECTION_META_TITLE'])) $title = $seo['SECTION_META_TITLE'];
        if (!empty($seo['SECTION_META_KEYWORDS'])) $keywords = $seo['SECTION_META_KEYWORDS'];
        if (!empty($seo['SECTION_META_DESCRIPTION'])) $description = $seo['SECTION_META_DESCRIPTION'];
    }
}

/* --- // --- */


if (!empty($title)) $APPLICATION->SetTitle($title);
if (!empty($description)) $APPLICATION->SetPageProperty("description", $description);
if (!empty($keywords)) $APPLICATION->SetPageProperty("keywords", $keywords);

?>