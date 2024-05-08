<?
// Установка глобальных путей и линков
// Хороним порт
$http_host_temp = explode(":",$_SERVER['HTTP_HOST']);
$_SERVER['HTTP_HOST'] = $http_host_temp[0];

$G_HTTPS = $_SERVER['HTTPS']?'https://':'http://';
$G_DOMAIN = $G_HTTPS.$_SERVER['HTTP_HOST'];

$factory_link = '/factory'; // Для производства

if ($_SERVER['HTTP_HOST'] == 'evroplast.ru') $m_factory = $G_HTTPS.'decor.ru.com';
elseif ($_SERVER['HTTP_HOST'] == 'evroplast.ru.dem') $m_factory = $G_HTTPS.'decor.ru.com.dem';
else $m_factory = '/factory';
$m_factory = '/factory';

// Установка глобал по умолчанию с глобал корня
if ($_SERVER['HTTP_HOST'] == 'decor.ru.com') { 
	$G_DOMAIN = $G_HTTPS.'evroplast.ru'; 
	$factory_link = '';
}
if ($_SERVER['HTTP_HOST'] == 'decor.ru.com.dem') { 
	$G_DOMAIN = $G_HTTPS.'evroplast.ru.dem'; 
	$factory_link = '';	
}

define("DEFAULT_SAMPLE_PRICE", 100); // дефолтная цена образца
define("BASKET_EXPIRES", 30); // время жизни куков корзины

//id инфоблоков:

define("IB_CATALOGUE",12); // каталог

/* --- Основной Домен --- */

define("HTTP_HOST", 'perfom-decor.ru');

/* --- MEDIA --- */

define("MEDIA_NAME", 'Медиацентр'); // Медиа название
define("MEDIA_FOLDER", '/media'); // Медиа папка
define("MEDIA_IBLOCK_ID", '68'); // Медиа инфоблок

/* --- // --- */

//добавляем пароль в стандартное письмо о регистрации
//AddEventHandler("main", "OnAfterUserAdd", "OnAfterUserRegisterHandler");
AddEventHandler("main", "OnAfterUserRegister", "OnAfterUserRegisterHandler");

require_once( $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/autoload.php');
use \Bas\Pict;


if (!function_exists('mb_ucfirst') && extension_loaded('mbstring'))
{
    /**
     * mb_ucfirst - преобразует первый символ в верхний регистр
     * @param string $str - строка
     * @param string $encoding - кодировка, по-умолчанию UTF-8
     * @return string
     */
    function mb_ucfirst($str, $encoding='UTF-8')
    {
        $str = mb_ereg_replace('^[\ ]+', '', $str);
        $str = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding).
            mb_substr($str, 1, mb_strlen($str), $encoding);
        return $str;
    }
}

function main_phone_number() {
    return '+7 495 315 30 40';
}


/* --- Не показываем больше окно выбора --- */

function my_city_fixed() {
    global $APPLICATION;

    $time = time() + 60 * 60 * 2; //время жизни куки в секундах (2 часа)

    $APPLICATION->set_cookie('my_city_fixed', 1, $time, '/', '.'.HTTP_HOST);
}

/* --- // --- */


function OnAfterUserRegisterHandler(&$arFields)
{
    if (intval($arFields["ID"])>0)
    {
        $toSend = Array();
        //$toSend["PASSWORD"] = $arFields["CONFIRM_PASSWORD"];
        $toSend["PASSWORD"] = $arFields["CONFIRM_PASSWORD"];
        $toSend["EMAIL"] = $arFields["EMAIL"];
        $toSend["USER_ID"] = $arFields["ID"];
        $toSend["USER_IP"] = $arFields["USER_IP"];
        $toSend["USER_HOST"] = $arFields["USER_HOST"];
        $toSend["LOGIN"] = $arFields["LOGIN"];
        $toSend["NAME"] = (trim ($arFields["NAME"]) == "")? $toSend["NAME"] = htmlspecialchars('<Не указано>'): $arFields["NAME"];
        $toSend["LAST_NAME"] = (trim ($arFields["LAST_NAME"]) == "")? $toSend["LAST_NAME"] = htmlspecialchars('<Не указано>'): $arFields["LAST_NAME"];
        CEvent::SendImmediate ("MY_NEW_USER_CONFIRM", SITE_ID, $toSend);
    }
    return $arFields;
}

function getCurrency($my_city) {
    $arFilter_city = Array('IBLOCK_ID' => 7, 'ACTIVE' => 'Y', 'ID' => $my_city);
    $db_list_city = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter_city);
    $city_cur = $db_list_city->GetNextElement();
    $city_cur = array_merge($city_cur->GetFields(), $city_cur->GetProperties());
    switch ($city_cur['country']['VALUE']) {
        case '3241' : return 'BYN'; break; // Беларусь
        case '3246' : return 'KZT'; break; // Казахстан
		case '70966' : return 'GEL'; break; // Грузия
        case '3250' : return 'UAH'; break; // Украина
        case '3296' : return 'MDL'; break; // Молдова
        case '6690' : return 'AMD'; break; // Армения
		case '3670' : return 'UZS'; break; // Узбекистан
        case '3726' : return 'EUR'; break; // Латвия
        case '3840' : return 'EUR'; break; // Литва
        case '6347' : return 'EUR'; break; // Эстония
        case '6712' : return 'KGS'; break; // Кыргызстан
        default : return 'RUB'; break;
    }
}

//московские дилеры
function get_dealer_phone($mailDealer) {
    switch($mailDealer) {
        case 'kdvor@decor-evroplast.ru':
            $dealer['phone'] = '8-495-640-88-51, 8-495-730-48-84';
            $dealer['addr'] = 'ТК "Каширский двор" г. Москва, Каширское ш., д.19, корп.1, 2-й этаж, фирменный салон "Европласт" павильон 2-С90';
            $dealer['time'] = 'с 9:00 до 21:00';
            break;
        case 'nahim@decor-evroplast.ru':
            $dealer['phone'] = '8-495-116-55-39, 8-495-116-55-40';
            $dealer['addr'] = 'ТВК "ЭКСПОСТРОЙ на Нахимовском" г.Москва, Нахимовский проспект, д.24, павильон №3, фирменный стенд "Европласт" № 195';
            $dealer['time'] = 'с 10:00 до 20:00';
            break;
        case 'shop@decor-evroplast.ru':
            $dealer['phone'] = '8-495-116-55-41';
            $dealer['addr'] = 'ТВК "ЭКСПОСТРОЙ на Нахимовском" г.Москва, Нахимовский проспект, д.24, павильон №2, фирменный стенд "Европласт" № 158';
            $dealer['time'] = 'с 10:00 до 20:00';
            break;
        case 'salonn@decor-evroplast.ru':
            $dealer['phone'] = '8-495-116-55-37';
            $dealer['addr'] = 'ТВК "ЭКСПОСТРОЙ на Нахимовском" г.Москва, Нахимовский проспект, д.24, павильон №3, фирменный стенд "Европласт" № 49/2';
            $dealer['time'] = 'с 10:00 до 20:00';
            break;
    }
    return $dealer;
}

//расчет доставки и полной суммы с доставкой
function countDelivery($km,$price,$onlySamples = false) {
  /*$res = array();
  $delivery_price = 0;
  if($price < 30000) {
      $delivery_price += 800;
  }
  if($km > 0) {
      $delivery_price += ceil($km)*30;
  }
  $res['del'] = round($delivery_price,2);
  $res['total'] = round($delivery_price + $price,2);

  return $res;*/

  $res = array();
  $delivery_price = 0;

  if($onlySamples) { //если только образцы
      $delivery_price += 500;
      $delivery_price += ceil($km)*50;
  } else {
      if($price < 10000) {
          $delivery_price += 1000;
          if($km > 0) {
              $delivery_price += ceil($km)*50;
          }
      } else {
          if($km > 0) {
              $delivery_price += ceil($km)*30;
          }
      }
  }

  $res['del'] = round($delivery_price,2);
  $res['total'] = round($delivery_price + $price,2);

  return $res;
}

/**
* Функция возвращает окончание для множественного числа слова на основании числа и массива окончаний
* param  $number Integer Число на основе которого нужно сформировать окончание
* param  $endingsArray  Array Массив слов или окончаний для чисел (1, 4, 5),
 *         например array('яблоко', 'яблока', 'яблок')
* return String
*/
function getNumEnding($number, $endingArray) {
    $number = $number % 100;
    if ($number>=11 && $number<=19) {
        $ending=$endingArray[2];
    }
    else {
        $i = $number % 10;
        switch ($i)
        {
            case (1): $ending = $endingArray[0]; break;
            case (2):
            case (3):
            case (4): $ending = $endingArray[1]; break;
            default: $ending=$endingArray[2];
        }
    }
    return $ending;
}

//для responsive
define('BX_COMPRESSION_DISABLED',true);
include "prop_checkbox.php";

function get_order_status($stat = 'new') {
    $res = CIBlockElement::GetList(Array(), Array('IBLOCK_CODE'=>'order_status', 'ACTIVE'=>'Y', 'CODE'=>$stat), false, Array(), Array());
    while($ob = $res->GetNextElement()) $arFields = $ob->GetFields();
    return $arFields['NAME'];
}

function get_news_iblocks() {
    $iblock_id = array("LOGIC" => "OR");
    $res = CIBlock::GetList(
        Array(),
        Array(
            'TYPE'=>'news',
            'ACTIVE'=>'Y',
        ), true
    );
    while($ar_res = $res->Fetch())
    {
        $iblock_id[] = Array('IBLOCK_ID'=>$ar_res['ID']);
    }
    return $iblock_id;
}

function get_product_preview($product, $is_gallery = false, $is_flex = false, $calculate = false, $test = false) {
global $DB;
global $my_city;
global $APPLICATION;

if ($my_city == NULL) $my_city = $APPLICATION->get_cookie('my_city');

global $USER;
$user_id = $USER->GetID();
$user_group_arr = [];
$res = CUser::GetUserGroupList($user_id);
while ($arGroup = $res->Fetch()) {
    $user_group_arr[] = $arGroup['GROUP_ID'];
}
$rsUser = CUser::GetByID($user_id);
$user = $rsUser->Fetch();

if ($USER->IsAuthorized() && in_array(5,$user_group_arr)) {
    $favorite = json_decode($user['PERSONAL_NOTES']);
} else {
    $favorite = json_decode($_COOKIE['favorite']);
}


$res = CIBlockElement::GetByID($product['ID']);
if($arRes = $res->Fetch()) {
    $res = CIBlockSection::GetByID($arRes["IBLOCK_SECTION_ID"]);
    if($arRes = $res->Fetch()) {
        $section_id = $arRes["ID"];
        $arFilter = Array('IBLOCK_ID'=>IB_CATALOGUE, 'ACTIVE'=>'Y', 'ID'=>$section_id);
        $db_list = CIBlockSection::GetList(Array(), $arFilter, false, array('UF_*'));
        $last_section = $db_list->GetNext();
    }
}
if ($last_section['UF_H'] == 1) {
    $signTmp = ' prod-prev-h';
} elseif ($last_section['UF_V'] == 1) {
    $signTmp = ' prod-prev-v';
} else {
    $signTmp = '';
}
$uri = __get_product_link($product,$test);
//$uri = __get_product_link($product);
$cost = __get_product_cost($product);
$iscomp = 0;
if ($product['COMPOSITEPART']['VALUE']) $iscomp = 1;
$flx_class = '';
if($is_flex) $flx_class = ' flex';
$article_foil = array('6.50.711', '6.50.712', '6.50.713', '6.50.714', '6.50.715', '6.50.716', '6.50.719', '6.51.710', );
$sellout_class = $product['SELLOUT']['VALUE'] ? ' sellout' : '';
ob_start(); ?>

<div <?if($is_gallery) echo 'id="item'.$is_gallery.'"';?> class="prod-prev<?=$signTmp?><?=flexTmp($product)?><?=$flx_class?><?=$sellout_class?>"
    data-type="prod-prev"
    data-id="<?=$product['ID']?>"
    data-name="<?=__get_product_name($product)?>"
    data-code="<?=$product['INNERCODE']['VALUE']?>"
    data-price="<?=_makeprice(CPrice::GetBasePrice($product['ID']))['PRICE'];?>"
    data-curr="<?=getCurrency($my_city)?>"
    data-cat="<?=$section_id?>"
    data-cat-name="<?=$last_section['NAME']?>"
    data-iscomp="<?=$iscomp?>"
    <?if($calculate) echo ' data-articul="'.$product['ARTICUL']['VALUE'].'"'?>
    <?if($product['MAURITANIA_SPECIAL']['VALUE']=='Y') echo ' data-maur-spec="1"'?>>
    <?if(!$calculate && $product['COMING_SOON']['VALUE']!='Y') {?><a href="<?=$uri?>"></a><? } ?>
    <div class="prod-prev-top">
        <div class="prod-prev-title">
            <span class="prod-prev-name"><?=__get_product_name($product,true,false,false)?></span>
            <?if($last_section['CODE'] != 'klei-90') { ?>
                <span class="prod-prev-article">
                    <?=$product['ARTICUL']['VALUE']?>
                    <? if (in_array($product['ARTICUL']['VALUE'], $article_foil)) { ?>
                        <i class="icon-light"></i>
                    <? } ?>
                </span>
            <? } ?>
            <?$datetime = date('d.m.Y H:i:s');
            $dateproduct = date($DB->DateFormatToPHP($product['DATE_CREATE']));
            $sub_date = ceil((strtotime($datetime)-strtotime($dateproduct))/86400);
            if ((($sub_date < 150) && ($product['TIME_NEW_OFF']['VALUE'] != "Y")) && !$calculate || ($product['NEW_ON']['VALUE'] == "Y") && !$calculate) {
                if($product['ARTICUL']['VALUE'] != 'EB05.M.290' && $product['ARTICUL']['VALUE'] != 'EB06.S.80') {
                    ?>
                    <div class="new-prod">новинка</div>
                <? } } ?>
            <?if($product['SELLOUT']['VALUE'] == 'Y') { ?>
              <div class="new-prod sell-out">распродажа</div>
          <? } ?>
        </div>
        <div class="prod-prev-img">
            <?
            $web_path = web_path($product);
            $img_path = get_resized_img($web_path,330,330);
            if($img_path == '' || !$img_path) $img_path = $web_path;
            ?>
            <img src="<?=$img_path?>" alt="<?=__get_product_name($product)?>">
        </div>

        <?
        //чтобы не дублировать всю логику для мобильных превью, немного усложняем проверки
        $available_to_sell = false;
        $btns = '';
        ?>
        <?if($product['NO_ORDER']['VALUE'] != 'Y' && $cost && $product['COMING_SOON']['VALUE']!='Y') {?>
            <?if($product['OUT_OF_STOCK']['VALUE'] == 'Y' && $my_city == '3109') {
                $btns = '<div class="prod-prev-no">
                    <p>Товар недоступен для&nbsp;заказа</p>
                    <p>Будет доступен для&nbsp;покупки <br>после 31&nbsp;мая&nbsp;2020 г.</p>
                </div>';
             } else {
                $available_to_sell = true;
            }
         } elseif($product['COMING_SOON']['VALUE']=='Y') {
            $btns = '<p class="coming-soon">Скоро в&nbsp;продаже</p>';
         } else {
            $btns = '<p class="no-sale">Товар недоступен для&nbsp;заказа</p>';
         } ?>

        <div class="prod-prev-btns">
            <?// if($available_to_sell) { ?>
                <div class="prod-prev-icons">
                    <i class="icon-favorite<?if(in_array($product['ID'],$favorite)) echo ' active'?>" data-type="favorite" data-user="<?=in_array(5,$user_group_arr ) ? 'user' : 'no-user'?>" title="Добавить в избранное"></i>
                    <i class="icon-cart<?=(in_cart($product['ID']))?' active':''?><? if(!$available_to_sell) echo ' inactive' ?>" <? if($available_to_sell) echo 'data-type="cart-add"' ?> title="Добавить в корзину"></i>
                </div>
                <div class="prod-prev-one-click<? if(!$available_to_sell) echo ' inactive' ?>" <? if($available_to_sell) echo 'data-type="one-click"' ?>>купить в&nbsp;1&nbsp;клик</div>
                <div class="prod-prev-cart-mess prod-prev-mess">Товар добавлен в&nbsp;корзину</div>
                <div class="prod-prev-favorite-mess prod-prev-mess">Товар добавлен в&nbsp;избранное</div>
            <? //} else {
               //echo $btns;
             //} ?>
        </div>
    </div>
    <div class="prod-prev-bottom">
        <?if($product['SELLOUT']['VALUE'] == 'Y' && $product['OLD_PRICE']['VALUE'] != '' && $loc['country']['VALUE'] == '3111') {
            $old_price = _makeprice($product['OLD_PRICE']['VALUE']);
        ?>
            <div class="prod-prev-price prod-prev-price-old"><?=__cost_format($old_price)?></div>
        <? } ?>
        <? if($cost) { ?>
            <div class="prod-prev-price"><?=__cost_format($cost)?></div>
        <? } ?>
        <? if($btns != '') {?>
            <?if($cost) { ?>
                <div class="no-avail-wrap">
            <? } ?>
            <?=$btns?>
            <?if($cost) { ?>
                </div>
            <? } ?>

        <? } ?>
        <div class="prod-prev-params">
            <? $res = item_param($product);
            $res_param = array_merge($res['res_s'],$res['res_f']);
            if($is_flex && $is_flex == 'flex') $res_param = $res['res_f'];
            if (count($res_param)) {
                foreach ($res_param as $name => $pitem) { ?>
                    <div class="prod-prev-param">
                        <span><?=$name?></span>
                        <span><?=$pitem?></span>
                    </div>
                <? }} ?>
        </div>

        <div class="prod-prev-mob">
            <span class="prod-prev-name"><?=__get_product_name($product,true,false,false)?></span>
        <?if($last_section['CODE'] != 'klei-90') { ?>
            <span class="prod-prev-article">
                <?=$product['ARTICUL']['VALUE']?>
                <? if (in_array($product['ARTICUL']['VALUE'], $article_foil)) { ?>
                    <i class="icon-light"></i>
                <? } ?>
            </span>
        <? } ?>
    <? if($available_to_sell) { ?>
            <div class="prod-prev-mob-btns">
                <div class="prod-prev-cart<?=(in_cart($product['ID']))?' active':''?>" data-type="cart-add"><?=(in_cart($product['ID']))?'В корзине':'В корзину'?></div>
                <i class="icon-favorite<?if(in_array($product['ID'],$favorite)) echo ' active'?>" data-type="favorite" data-user="no-user" title="Добавить в избранное"></i>
            </div>
        <? } ?>
        </div>
    </div>
    <? if($is_gallery) { ?>
        <div class="element-number"><?=$is_gallery?></div>
    <? } ?>
</div>

<?
  $html = ob_get_clean();
  return($html);
}

function web_path($ob_web_path) {
    $images_path = $_SERVER["DOCUMENT_ROOT"]."/cron/catalog/data/images";
    $images_web_path = "/cron/catalog/data/images";

    if ($ob_web_path['FLEX']['VALUE'] == 'Y') {
        $path = $images_path."/600/".$ob_web_path['ARTICUL']['VALUE'].'.600.png';
        $web_path = $images_web_path."/600/".$ob_web_path['ARTICUL']['VALUE'].'.600.png';
        if (!file_exists($path)) { // подтянуть старый контент
            $path = $images_path."/60/".$ob_web_path['ARTICUL']['VALUE'].'.60.png';
            $web_path = $images_web_path."/60/".$ob_web_path['ARTICUL']['VALUE'].'.60.png';
            if (!file_exists($path)) { // заглушка
                $path = $images_path."/nope.jpg";
                $web_path = $images_web_path."/nope.jpg";
            }
        }
    } else {
        $path = $images_path."/100/".$ob_web_path['ARTICUL']['VALUE'].'.100.png';
        $web_path = $images_web_path."/100/".$ob_web_path['ARTICUL']['VALUE'].'.100.png';
        if (!file_exists($path)) { // подтянуть старый контент
            $path = $images_path."/10/".$ob_web_path['ARTICUL']['VALUE'].'.10.png';
            $web_path = $images_web_path."/10/".$ob_web_path['ARTICUL']['VALUE'].'.10.png';
            if (!file_exists($path)) { // заглушка
                $path = $images_path."/nope.jpg";
                $web_path = $images_web_path."/nope.jpg";
            }
        }
    }
    return $web_path;
}
function flexTmp($product) {
    if ($product['FLEX']['VALUE'] == 'Y') {
        $flexAnalog = ' e-new-catalogue-f';
    } else {
        $flexAnalog = '';
    }
    return $flexAnalog;
}
function in_cart($id_in_cart) {
    $cart = json_decode($_COOKIE['basket']);
    $in_cart = false;
    foreach ($cart as $citem) {
        if ($citem->id == $id_in_cart) { $in_cart = true; break; }
    }
    return $in_cart;
}
function item_param($item_param) {
$res_s = array();
$res_f = array();
foreach ($item_param as $key => $val) {
    if (!preg_match("/S(\d+)/", $key)) continue;
        if (($val['FILTRABLE'] != 'Y') || ($val['USER_TYPE'] == 'Checkbox')) continue;

    if ((($val['CODE'] == 'S4') || ($val['CODE'] == 'S5') || ($val['CODE'] == 'S6')) && $val['VALUE']) { // flex param
        $name = explode(",", $val['NAME']);
            $dec = $name[count($name)-1];
        unset($name[count($name)-1]);
            $name = implode(",", $name);
        $res_f[$name] = $val['VALUE'].($dec?' '.$dec:'');
        if($val['VALUE']=='наборная') $res_f[$name] = $val['VALUE'];
        if($val['VALUE']=='нет') $res_f[$name] = $val['VALUE'];
    } elseif ($val['VALUE']) {
        $name = explode(",", $val['NAME']);
        $dec = $name[count($name)-1];
        unset($name[count($name)-1]);
        $name = implode(",", $name);
        $res_s[$name] = $val['VALUE'].($dec?' '.$dec:'');
        if($val['VALUE']=='наборная') $res_s[$name] = $val['VALUE'];
    }
}

return array('res_s' => $res_s,'res_f' => $res_f);
}
function __number_order_sale ($inc = false) {
	$number = null;
	$arFilter = Array('ACTIVE' => 'Y', 'CODE' => 'number-sale');
        $db_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter);  
	$number = $db_list->GetNextElement();
	$number = array_merge($number->GetFields(), $number->GetProperties());
	$number_sale = $number['number']['VALUE'];
	if ($inc) CIBlockElement::SetPropertyValueCode($number['ID'], 'number', $number_sale+1);
     return str_pad($number_sale,5,"0",STR_PAD_LEFT);
}
function __random_number_order() {
	$datetime = date('d.m.Y H:i:s');
	$format = "DD.MM.YYYY HH:MI:SS";
	$arr_n = ParseDateTime($datetime, $format);
	$number_r = $arr_n["DD"].$arr_n["MM"].$arr_n["YYYY"].'.'.$arr_n["HH"].$arr_n["MI"].$arr_n["SS"];
	
     return $number_r;
}
// Dem
function __get_product_cost($item) {
	$cost = 0;
	// составной
	if ($item['COMPOSITEPART']['VALUE']) {
	foreach ($item['COMPOSITEPART']['VALUE'] as $comp_item) {
        	$arFilter = Array('IBLOCK_ID'=>IB_CATALOGUE, 'ID'=>$comp_item);
        	$db_list = CIBlockElement::GetList(Array(), $arFilter);
        	$product_item_part = $db_list->GetNext();
        	$cost_ = _makeprice(CPrice::GetBasePrice($product_item_part['ID']));
        	$cost += $cost_['PRICE'];
    		}
	}
	else {
		$cost_ = _makeprice(CPrice::GetBasePrice($item['ID']));
		$cost  = $cost_['PRICE'];
	}
     return $cost;
}
function __get_product_name($product, $iname = true, $altoff = false, $articul = true) {
    $name = '';
    if ($product['ALTUSE']['VALUE'] == 'Y') {
        $name = $product['ALTNAME']['VALUE'];
    if ($product['ALTUSE']['VALUE'] == 'Y') {
	$name = $altoff?$product['NAME']:$product['ALTNAME']['VALUE'];
    }
    } else {
        if ($product['FLEX']['VALUE'] == 'Y') {
            $name = $iname?$product['NAME']:'';
            $name = trim(str_replace("FLEX", "", $name));
            if($articul) $name .= " ".$product['ARTICUL']['VALUE'];
            $name .= " гибкий";
        } else {
            $name = $iname?$product['NAME']:'';
            if($articul) $name .= " ".$product['ARTICUL']['VALUE'];
        }
    }
    if($name == "") {
        $name = $product['NAME'];
    }
    return $name;
}
function __get_product_images($product) {
    $images_path = $_SERVER["DOCUMENT_ROOT"]."/cron/catalog/data/images";
    //$images_web_path = "/cron/catalog/data/images";
    $images_web_path = "/cache";
    $files = array();
    $web_path = '';
    if ($product['FLEX']['VALUE'] == 'Y') {
        $path = $images_path."/60/".$product['ARTICUL']['VALUE'].'.60.png';
        $web_path = $images_web_path."/60/".$product['ARTICUL']['VALUE'].'.60.png';
        if (!file_exists($path)) {
            $path = $images_path."/nope.jpg";
            $web_path = $images_web_path."/nope.jpg";
        }
    } else {
        $path = $images_path."/10/".$product['ARTICUL']['VALUE'].'.10.png';
        $web_path = $images_web_path."/10/".$product['ARTICUL']['VALUE'].'.10.png';
        if (!file_exists($path)) {
            $path = $images_path."/nope.jpg";
            $web_path = $images_web_path."/nope.jpg";
        }
    }
    $files[] = $web_path;
    foreach (array('20', '30', '40', '50', '60') as $img_pre) {
        $path = $images_path."/".$img_pre."/".$product['ARTICUL']['VALUE'].'.'.$img_pre.'.png';
        $web_path = $images_web_path."/".$img_pre."/".$product['ARTICUL']['VALUE'].'.'.$img_pre.'.png';
        if (file_exists($path)) {
            $files[] = $web_path;
        }
    }
    $product['FILES_IMAGES'] = $files;
    return $product;
}

function __get_product_images_new($product) {
    $images_path = $_SERVER["DOCUMENT_ROOT"]."/cron/catalog/data/images";
    $images_web_path = "/cron/catalog/data/images";

    $files = array();

    
    if($product['FLEX']['VALUE'] == "N") {
        $path = $images_path."/100/".$product['ARTICUL']['VALUE'].'.100.png';
        $web_path = $images_web_path."/100/".$product['ARTICUL']['VALUE'].'.100.png';


        if (!file_exists($path)) { // подтянуть старый контент
            $path = $images_path."/10/".$product['ARTICUL']['VALUE'].'.10.png';
            $web_path = $images_web_path."/10/".$product['ARTICUL']['VALUE'].'.10.png';
            if (!file_exists($path)) { // заглушка
                $path = $images_path."/nope.jpg";
                $web_path = $images_web_path."/nope.jpg";
            }
        }
    }
    else {
        $path = $images_path."/600/".$product['ARTICUL']['VALUE'].'.600.png';
        $web_path = $images_web_path."/600/".$product['ARTICUL']['VALUE'].'.600.png';


        if (!file_exists($path)) { // подтянуть старый контент
            $path = $images_path."/60/".$product['ARTICUL']['VALUE'].'.60.png';
            $web_path = $images_web_path."/60/".$product['ARTICUL']['VALUE'].'.60.png';
            if (!file_exists($path)) { // заглушка
                $path = $images_path."/nope.jpg";
                $web_path = $images_web_path."/nope.jpg";
            }
        }
    }

    $files[] = $web_path;

    $product['FILES_IMAGES'] = $files;
    return $product;

}
function __get_product_link($item,$test = false) {
    if (!is_array($item)) {
        return "";
    }
    if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
        exit;
    }
    $arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, 'ACTIVE' => 'Y', 'ID' => $item['IBLOCK_SECTION_ID']);
    $db_list = CIBlockSection::GetList(Array("SORT" => "ASC"), $arFilter, false, array('UF_*'));
    $section = $db_list->GetNext();
    //обратное дерево
    $current = $section;
    $res = array($current['NAME']);
    $resall = array($current);
    $sections = array($current['CODE']);
    while ($current && $current['IBLOCK_SECTION_ID']) {
        $arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, 'GLOBAL_ACTIVE' => 'Y', 'ID' => $current['IBLOCK_SECTION_ID']);
        $db_list = CIBlockSection::GetList(Array("SORT" => "ASC"), $arFilter, false, array('UF_*'));
        $current = $db_list->GetNext();
        if ($current) {
            if($current['CODE'] == 'interernyj-dekor' || $current['CODE'] == 'fasadnyj-dekor') continue;
            $res[] = $current['NAME'];
            $resall[] = $current;
            $sections[] = $current['CODE'];
        }
    }
    $sections = array_reverse($sections);
    $product_link = "/".implode("/", $sections)."/".$item['CODE']."/";
    if($test) $product_link = "/".implode("/", $sections)."/".$item['CODE']."/";
    return $product_link;
}
function ___get_product_sections($item, $name = false) {
    if (!is_array($item)) {
        return "";
    }
    if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
        exit;
    }
    //обратное дерево
    $current = array('IBLOCK_SECTION_ID'=>$item['IBLOCK_SECTION_ID']);
    $res = array();
    $res_name = array();
    while ($current && $current['IBLOCK_SECTION_ID']) {
        $arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, 'GLOBAL_ACTIVE' => 'Y', 'ID' => $current['IBLOCK_SECTION_ID']);
        $db_list = CIBlockSection::GetList(Array(), $arFilter, false, array('IBLOCK_SECTION_ID', 'CODE', 'NAME'));
        $current = $db_list->GetNext();
        if ($current) {
            if($current['CODE'] == 'interernyj-dekor' || $current['CODE'] == 'fasadnyj-dekor') continue;
            $res[] = $current['CODE'];
            $res_name[] = $current['NAME'];
        }
    }
    if($name) {
        return $res_name;
    } else {
        return $res;
    }
}
function ___get_product_url($item) {
    if (!is_array($item)) {
        return "";
    }
    $res = ___get_product_sections($item);
    $res = array_reverse($res);
    return "/".implode("/", $res)."/".$item['CODE'];
}
function _applay_discount($price, $money) {
    $discount = 0;
    return array('price'=>round($price, 2), 'discount'=>$discount);
    if ($money >= 3000 && $money < 10000) {
        $discount = 3;
    }
    if ($money >= 10000 && $money < 15000) {
        $discount = 5;
    }
    if ($money >= 15000 && $money < 40000) {
        $discount = 7;
    }
    if ($money >= 40000 && $money < 75000) {
        $discount = 10;
    }
    if ($money >= 75000 && $money < 110000) {
        $discount = 13;
    }
    if ($money >= 110000 && $money < 150000) {
        $discount = 15;
    }
    if ($money >= 150000 && $money < 200000) {
        $discount = 18;
    }
    if ($money >= 200000) {
        $discount = 20;
    }
    $price = $price - ($price/100)*$discount;
    return array('price'=>round($price, 2), 'discount'=>$discount);
}
function __discount($money) {
	
	global $APPLICATION;
	$city = $APPLICATION->get_cookie('my_city');
	if ($city != '3109') return; // Временно проверка по Москве
    $discount = 0;
    if ($money >= 3000 && $money < 10000) {
        $discount = 3;
    }
    if ($money >= 10000 && $money < 15000) {
        $discount = 5;
    }
    if ($money >= 15000 && $money < 40000) {
        $discount = 7;
    }
    if ($money >= 40000 && $money < 75000) {
        $discount = 10;
    }
    if ($money >= 75000 && $money < 110000) {
        $discount = 13;
    }
    if ($money >= 110000 && $money < 150000) {
        $discount = 15;
    }
    if ($money >= 150000 && $money < 200000) {
        $discount = 18;
    }
    if ($money >= 200000) {
        $discount = 20;
    }
	// все сложнее, на каждую позицию скидка

$cart = json_decode($_COOKIE['basket']);
$total = 0;
	foreach ($cart as $citem) {
        if(strpos($citem->id,'s') !== false) continue;
        //$arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, 'ACTIVE' => 'Y', 'ID' => $citem->id);
        $arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, 'ID' => $citem->id);
        $db_list = CIBlockElement::GetList(Array("SORT" => "ASC"), $arFilter);
        $ob = $db_list->GetNextElement();
        if (!$ob) continue;
	$ob = array_merge($ob->GetFields(), $ob->GetProperties());
		if ($ob['COMPOSITEPART']['VALUE']) { 
		$ids = $ob['COMPOSITEPART']['VALUE'];
        	$ids['LOGIC'] = 'OR';
        	//$arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, 'ACTIVE' => 'Y', 'ID' => $ids);
            $arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, 'ID' => $ids);
        	$db_list = CIBlockElement::GetList(Array("SORT" => "ASC"), $arFilter);
        		while ($ob_comp = $db_list->GetNextElement()) {
        			$ob_comp = array_merge($ob_comp->GetFields(), $ob_comp->GetProperties());
			 	
				//if ($ob_comp['FLEX']['VALUE'] == 'Y') { // for flex
				//	$ob_cost = __get_product_cost($ob_comp)/2;
				//	$discount_cost = ceil($ob_cost - $ob_cost/100*$discount)*2;
				//	$total += $discount_cost*$citem->count;
				//} else {
				$ob_cost = __get_product_cost($ob_comp);
				$discount_cost = ceil($ob_cost - $ob_cost/100*$discount);
				$total += $discount_cost*$citem->count;
				//}
			}
		} else {
			//if ($ob['FLEX']['VALUE'] == 'Y') { // for flex
			//	$ob_cost = __get_product_cost($ob)/2;
			//	$discount_cost = ceil($ob_cost - $ob_cost/100*$discount)*2;
			//	$total += $discount_cost*$citem->count;
			//} else {
			$ob_cost = __get_product_cost($ob);
			$discount_cost = ceil($ob_cost - $ob_cost/100*$discount);
			$total += $discount_cost*$citem->count;
			//}
		}	
	}
    $discount_price = $money - $total;
    return array('total'=>$total, 'discount'=>$discount, 'discount_price' =>$discount_price);
}
function __discount_mob($money, $prod = false) {
    global $APPLICATION;
    $city = $APPLICATION->get_cookie('my_city');
    if ($city != '3109') return; // Временно проверка по Москве
    $discount = 0;
    if ($money >= 3000 && $money < 10000) {
        $discount = 3;
    }
    if ($money >= 10000 && $money < 15000) {
        $discount = 5;
    }
    if ($money >= 15000 && $money < 40000) {
        $discount = 7;
    }
    if ($money >= 40000 && $money < 75000) {
        $discount = 10;
    }
    if ($money >= 75000 && $money < 110000) {
        $discount = 13;
    }
    if ($money >= 110000 && $money < 150000) {
        $discount = 15;
    }
    if ($money >= 150000 && $money < 200000) {
        $discount = 18;
    }
    if ($money >= 200000) {
        $discount = 20;
    }
    // все сложнее, на каждую позицию скидка

    $cart = json_decode($_COOKIE['basket']);
    if($prod) $cart = $prod;
    $total = 0;
    foreach ($cart as $citem) {
        if(strpos($citem->id,'s') !== false) continue;
        //$arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, 'ACTIVE' => 'Y', 'ID' => $citem->id);
        $arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, 'ID' => $citem->id);
        $db_list = CIBlockElement::GetList(Array("SORT" => "ASC"), $arFilter);
        $ob = $db_list->GetNextElement();
        if (!$ob) continue;
        $ob = array_merge($ob->GetFields(), $ob->GetProperties());
        if ($ob['COMPOSITEPART']['VALUE']) {
            $ids = $ob['COMPOSITEPART']['VALUE'];
            $ids['LOGIC'] = 'OR';
            //$arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, 'ACTIVE' => 'Y', 'ID' => $ids);
            $arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, 'ID' => $ids);
            $db_list = CIBlockElement::GetList(Array("SORT" => "ASC"), $arFilter);
            while ($ob_comp = $db_list->GetNextElement()) {
                $ob_comp = array_merge($ob_comp->GetFields(), $ob_comp->GetProperties());
                //if ($ob_comp['FLEX']['VALUE'] == 'Y') { // for flex
                //    $ob_cost = __get_product_cost($ob_comp)/2;
                //    $discount_cost = ceil($ob_cost - $ob_cost/100*$discount)*2;
                //    $total += $discount_cost*$citem->qty;
                //} else {
                    $ob_cost = __get_product_cost($ob_comp);
                    $discount_cost = ceil($ob_cost - $ob_cost/100*$discount);
                    $total += $discount_cost*$citem->qty;
                //}
            }
        } else {
            //if ($ob['FLEX']['VALUE'] == 'Y') { // for flex
            //    $ob_cost = __get_product_cost($ob)/2;
            //    $discount_cost = ceil($ob_cost - $ob_cost/100*$discount)*2;
            //    $total += $discount_cost*$citem->qty;
            //} else {
                $ob_cost = __get_product_cost($ob);
                $discount_cost = ceil($ob_cost - $ob_cost/100*$discount);
                if($ob['ID'] == '6497' || $ob['ID'] == '6501') $discount_cost = $ob_cost; //если монтажный комплект
                $total += $discount_cost*$citem->qty;
            //}
        }
    }
    $discount_price = $money - $total;

    //если в корзине только монтажный комплект, скидка 0%
    // не забыть также изменить /order_managment/order_managment.js: function countSum
    $only_mount = true;
    foreach ($cart as $citem) {
        if($citem->id != '6497' &&  $citem->id != '6501') {
            $only_mount = false;
        }
    }
    if($only_mount) $discount = 0;

    return array('total'=>$total, 'discount'=>$discount, 'discount_price' =>$discount_price);
}

function __cost_format($cost, $city=null) {
    global $APPLICATION;
    if (!$city) {
        $city = $APPLICATION->get_cookie('my_city');
		if (!$city) {
		global $my_city;
		$city = $my_city;	
		}
    }
    $arFilter = Array('IBLOCK_ID' => 7, 'ACTIVE' => 'Y', 'ID' => is_array($city)?$city['ID']:$city);
    $db_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter);
    $city = $db_list->GetNextElement();
    if (!$city) {
        return $cost;
    }
    $city = array_merge($city->GetFields(), $city->GetProperties());
    if ($city['country']['VALUE'] == '3241') { // Беларусь
		$cost = number_format($cost,2,"."," ").' BYN';
		return $cost;
    }
    if ($city['country']['VALUE'] == '3246') { // Казахстан
		$cost = number_format($cost,0,"."," ").' KZT';
		return $cost;
    }
	if ($city['country']['VALUE'] == '70966') { // Грузия
		$cost = number_format($cost,2,"."," ").' GEL';
		return $cost;
    }
    if ($city['country']['VALUE'] == '3250') { // Украина
		$cost = number_format($cost,2,"."," ").' UAH';
		return $cost;
    }
    if ($city['country']['VALUE'] == '3296') { // Молдова
		$cost = number_format($cost,0,"."," ").' MDL';
		return $cost;
    }
    if ($city['country']['VALUE'] == '6690') { // Армения
		$cost = number_format($cost,0,"."," ").' AMD';
		return $cost;
    }
	if ($city['country']['VALUE'] == '3670') { // Узбекистан
		$cost = number_format($cost,0,"."," ").' UZS';
		return $cost;
    }
    if ($city['country']['VALUE'] == '6712') { // Кыргызстан
        $cost = number_format($cost,0,"."," ").' KGS';
        return $cost;
    }
    
    if (($city['country']['VALUE'] == '3726') || ($city['country']['VALUE'] == '3840') || ($city['country']['VALUE'] == '6347')) { // Латвия, Литва, Естония
		$cost = number_format($cost,2,"."," ").' EUR';
		return $cost;
    }
     
    return number_format($cost,2,"."," ").' RUB';
}
function _makeprice($price, $city=null) {
    global $APPLICATION;
    if (!$city) {
        $city = $APPLICATION->get_cookie('my_city');
		if (!$city) {
		global $my_city;
		$city = $my_city;	
		}
    }
    if (!CModule::IncludeModule('iblock')) {
        return $price;
    }
    $arFilter = Array('IBLOCK_ID' => 7, 'ACTIVE' => 'Y', 'ID' => is_array($city)?$city['ID']:$city);
    $db_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter);
    $city = $db_list->GetNextElement();
    if (!$city) {
        return $price;
    }
    $city = array_merge($city->GetFields(), $city->GetProperties());
  
    $arFilter_Item = Array('IBLOCK_ID' => IB_CATALOGUE, 'ID' => $price['PRODUCT_ID']);
    $db_list_Item = CIBlockElement::GetList(Array("SORT" => "ASC"), $arFilter_Item);
    $Item_Cost = $db_list_Item->GetNextElement();
    if ($Item_Cost) {
	$Item_Cost = array_merge($Item_Cost->GetFields(), $Item_Cost->GetProperties());
   	if ($city['country']['VALUE'] == '3241') { // Беларусь
		$price['PRICE'] =  str_replace(',', '.', $Item_Cost['BYN']['VALUE']);
		return $price;
    	}
	
	if ($city['country']['VALUE'] == '3246') { // Казахстан
		$price['PRICE'] =  str_replace(',', '.', $Item_Cost['KZH']['VALUE']);
		return $price;
    	}
	if ($city['country']['VALUE'] == '70966') { // Грузия
		$price['PRICE'] =  str_replace(',', '.', $Item_Cost['GEL']['VALUE']);
		return $price;
    	}
	if ($city['country']['VALUE'] == '3250') { // Украина
		$price['PRICE'] =  str_replace(',', '.', $Item_Cost['UAH']['VALUE']);
		return $price;
    	}
	if ($city['country']['VALUE'] == '3296') { // Молдова
		$price['PRICE'] =  str_replace(',', '.', $Item_Cost['MDL']['VALUE']);
		return $price;
    	}
	if ($city['country']['VALUE'] == '6690') { // Армения
		$price['PRICE'] =  str_replace(',', '.', $Item_Cost['AMD']['VALUE']);
		return $price;
    	}
	if ($city['country']['VALUE'] == '3670') { // Узбекистан
		$price['PRICE'] =  str_replace(',', '.', $Item_Cost['UZS']['VALUE']);
		return $price;
    	}
	if (($city['country']['VALUE'] == '3726') || ($city['country']['VALUE'] == '3840')) { // Латвия, Литва
		$price['PRICE'] =  str_replace(',', '.', $Item_Cost['EUR1']['VALUE']);
		return $price;
    	}
	if ($city['country']['VALUE'] == '6347') { // Эстония
		$price['PRICE'] =  str_replace(',', '.', $Item_Cost['EUR2']['VALUE']);
		return $price;
    	}
        if ($city['country']['VALUE'] == '6712') { // Кыргызстан
            $price['PRICE'] =  str_replace(',', '.', $Item_Cost['KGS']['VALUE']);
            return $price;
        }
	
	
    }
    if ($city['discountregion']['VALUE']) {
        $arFilter = Array('IBLOCK_ID' => 8, 'ACTIVE' => 'Y', 'ID' => $city['discountregion']['VALUE']);
        $db_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter);
        $region = $db_list->GetNextElement();
        if (!$region) {
            return $price;
        }
        $region = array_merge($region->GetFields(), $region->GetProperties());
        if ($region['discount']['VALUE']) {
            $percent = $region['discount']['VALUE'];
            $price['_PERCENT'] = $percent;
            $price['_OLD_PRICE'] = $price['PRICE'];
            $price['PRICE'] = round($price['PRICE'] - $price['PRICE']*$percent/100, 0);
            return $price;
        }
    }
    
    return $price;
}
function getDistanceBetweenPoints($latitude1, $longitude1, $latitude2, $longitude2)
{
    $theta = $longitude1 - $longitude2;
    $miles = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
    $miles = acos($miles);
    $miles = rad2deg($miles);
    $miles = $miles * 60 * 1.1515;
    $kilometers = $miles * 1.609344;
    $meters = $kilometers * 1000;
    return $meters;
}
function ucfirstutf($string)
{
    $string = mb_strtoupper(mb_substr($string, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($string, 1, mb_strlen($string), 'UTF-8');
    return $string;
}
function ANK_morphcount($count, $items = array('отделение', 'отделения', 'отделений'))
{
    if (1) {
        $_ = substr($count, -1);
        $__ = substr($count, -2);
        if ($_ <= 4 && $_ > 0) {
            $_ret = $items[1]; //поста
            if ($_ == 1 && $__ != 11) {
                $_ret = $items[0]; //пост
            }
            if (in_array($__, array(0, 11, 12, 13, 14))) {
                $_ret = $items[2]; //постов
            }
        } else {
            $_ret = $items[2]; //постов
        }
    }
    return $_ret;
}
function __declension($s) { // Склонение для Купить
	switch (trim($s)) {
    		case 'пилястра'		: return "пилястру"; break;
		    case 'колонна'		: return "колонну"; break;
		    case 'полуколонна'	: return "полуколонну"; break;
		    case 'балясина'		: return "балясину"; break;
		    case 'полубалясина'	: return "полубалясину"; break;
		    case 'ниша'	    	: return "нишу"; break;
		    case 'балюстрада'	: return "балюстраду"; break;
    		default: return $s; break;
	}
}
function __link_search($haystack) {
	if ((strripos($haystack, "http://") === false) && (strripos($haystack, "https://") === false) && (strripos($haystack, "href") === false)) return false;
	
return true;
}
function GetIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
function get_resized_img($path,$w=287,$h=287,$webp=false,$proportion_type=1) {
    $proportion = 'BX_RESIZE_IMAGE_PROPORTIONAL_ALT';
    if($proportion_type == 2) $proportion = 'BX_RESIZE_IMAGE_EXACT';
    if($proportion_type == 3) $proportion = 'BX_RESIZE_IMAGE_PROPORTIONAL';
    $res = $path;
    $subdir = explode('/',$path);
    $subdir = array_diff($subdir,array(''));
    $file_name = array_pop($subdir);
    $subdir = implode('/', $subdir);
    $file = $_SERVER["DOCUMENT_ROOT"].$path;
    $file_info = getimagesize($file);
    $arElement = Array(
        'FILE_NAME'=>$file_name,
        'SUBDIR'=>$subdir,//путь относительно корня сайта без "/" в начале и в конце
        'WIDTH'=>$file_info[0],
        'HEIGHT'=>$file_info[1],
        'CONTENT_TYPE' => $file_info['mime'],
    );
    $arSize = array('width'=>$w,'height'=>$h);
    /**
     * свой класс для переопределения метода стандартного ResizeImageGet()
     * для сохранения новых изображений в одной директории /upload/resize_cache
     * файл с классом - /local/php_interface/classes/CustomFile.php
     */
    $CustomFile = new CustomFile();
    $arPhotoSmall = $CustomFile->ResizeImageGet(
        $arElement,
        $arSize,
        $proportion,
        Array(
            "name" => "sharpen",
            "precision" => 0
        )
    );
    if(isset($arPhotoSmall['src'])) {
        $res = $arPhotoSmall['src'];
    }
    if($webp) {
        $arr = Array(
            'FILE_NAME' => $file_name,
            'SRC' => $path,
            'CONTENT_TYPE' => $file_info['mime']
        );
        $res = Pict::getWebp($arr, 100);
    }
    return $res;
}

function get_phone_mask($country) {
    $mask = '+7 (XXX) XXX-XX-XX'; //Россия, Абхазия, Казахстан
    switch ($country) {
        case '3286': //Азербайджан
            $mask = '+994 (XX) XXX-XX-XX';
            break;
        case '70966': //Грузия
            $mask = '+995 (XX) XXX-XX-XX';
            break;
        case '6712': //Кыргызстан
            $mask = '+996 (XXX) XX-XX-XX';
            break;
        case '3670': //Узбекистан
            $mask = '+998 (XX) XXX-XX-XX';
            break;
        case '3241': //Беларусь
            $mask = '+375 (XX) XXX-XX-XX';
            break;
        case '3296': //Молдова
            $mask = '+373 (XX) XX-XX-XX';
            break;
        case '3250': //Украина
            $mask = '+380 (XX) XXX-XX-XX';
            break;
        case '3726': //Латвия
            $mask = '+371 (XX) XX-XX-XX';
            break;
        case '3840': //Литва
            $mask = '+370 (XX) XXX-XX-XX';
            break;
        case '6347': //Эстония
            $mask = '+372 (XX) XX-XX-XX';
            break;
        case '6690': //Армения
            $mask = '+374 (XX) XX-XX-XX';
            break;

    }
    return $mask;
}
function get_currency_info($country) {
    $res = Array();
    switch ($country) {
        case '3241' : // Беларусь
            $res['filter'] = Array('>PROPERTY_BYN'=>0);
            $res['curr'] = "Белорусский рубль";
            $res['abbr'] = "BYN";
            $res['code'] = "BYN";
            $res['price'] = "Цена - BYN";
            break;

        case '3246' : // Казахстан
            $res['filter'] = Array('>PROPERTY_KZH'=>0);
            $res['curr'] = "Тенге";
            $res['abbr'] = "KZT";
            $res['code'] = "KZT";
            $res['price'] = "Цена - KZT";
            break;

        case '70966' : // Грузия
            $res['filter'] = Array('>PROPERTY_GEL'=>0);
            $res['curr'] = "Лари";
            $res['abbr'] = "GEL";
            $res['code'] = "GEL";
            $res['price'] = "Цена - GEL";
            break;

        case '3250' : // Украина
            $res['filter'] = Array('>PROPERTY_UAH'=>0);
            $res['curr'] = "Гривна";
            $res['abbr'] = "UAH";
            $res['code'] = "UAH";
            $res['price'] = "Цена - UAH";
            break;

        case '3296' : // Молдова
            $res['filter'] = Array('>PROPERTY_MDL'=>0);
            $res['curr'] = "Молдавский лей";
            $res['abbr'] = "MDL";
            $res['code'] = "MDL";
            $res['price'] = "Цена - MDL";
            break;

        case '3670' : // Узбекистан
            $res['filter'] = Array('>PROPERTY_UZS'=>0);
            $res['curr'] = "Сум узбекский";
            $res['abbr'] = "UZS";
            $res['code'] = "UZS";
            $res['price'] = "Цена - UZS";
            break;

        case '6690' : // Армения
            $res['filter'] = Array('>PROPERTY_AMD'=>0);
            $res['curr'] = "Армянский Драм";
            $res['abbr'] = "AMD";
            $res['code'] = "AMD";
            $res['price'] = "Цена - AMD";
            break;

        case '3726' : // Латвия
            $res['filter'] = Array('>PROPERTY_EUR1'=>0);
            $res['curr'] = "Евро";
            $res['abbr'] = "EUR";
            $res['code'] = "EUR1";
            $res['price'] = "Цена - EUR";
            break;

        case '3840' : // Литва
            $res['filter'] = Array('>PROPERTY_EUR1'=>0);
            $res['curr'] = "Евро";
            $res['abbr'] = "EUR";
            $res['code'] = "EUR1";
            $res['price'] = "Цена - EUR";
            break;

        case '6347' : // Эстония
            $res['filter'] = Array('>PROPERTY_EUR2'=>0);
            $res['curr'] = "Евро";
            $res['abbr'] = "EUR";
            $res['code'] = "EUR2";
            $res['price'] = "Цена - EUR";
            break;

        case '6712' : // Кыргызстан
            $res['filter'] = Array('>PROPERTY_KGS'=>0);
            $res['curr'] = "Киргизский сом";
            $res['abbr'] = "KGS";
            $res['code'] = "KGS";
            $res['price'] = "Цена - KGS";
            break;

        default :
            $res['curr'] = "Российский рубль";
            $res['abbr'] = "RUB";
            $res['code'] = "RUB";
            break;
    }
    return $res;
}
function get_glue_arr($for_menu = false) {
    $arr = Array();
    $menu_arr = Array(); //клей для выпадающего меню в хедере

    /*
     * Москва:
     * E01.M.290 / Клей Европласт Монтажный 290 мл
     * E04.U.290 / Клей Европласт универсальный 290 мл.
     * E02.S.290 / Клей Европласт стыковочный 290 мл.
     * E03.S.60 / Клей Европласт стыковочный 60 мл.
     *
     * Российские регионы, Абхазия:
     * E01.M.290 / Клей Европласт Монтажный 290 мл
     * Клей монтажный Европласт 290 мл.
     * Клей стыковочный Европласт 80 мл.
     *
     * Армения, Казахстан, Киргизия, Узбекистан:
     * E01.M.290 / Клей Европласт Монтажный 290 мл
     * E03.S.60 / Клей Европласт стыковочный 60 мл.
     * Клей монтажный Европласт 290 мл.
     *
     * Беларусь, Грузия:
     * E01.M.290 / Клей Европласт Монтажный 290 мл
     * E04.U.290 / Клей Европласт универсальный 290 мл.
     * E03.S.60 / Клей Европласт стыковочный 60 мл.
     *
     * Молдова, Прибалтика:
     * E01.M.290 / Клей Европласт Монтажный 290 мл.
     *
     *
     *
     * 6104     E01.M.290   клей монтажный 290 мл
     * 6105     E04.U.290   клей универсальный 290 мл
     * 6107     E02.S.290   клей стыковочный 290 мл
     * 6429     E03.S.60    клей стыковочный 60 мл
     * 147530   EB05.M.290  клей монтажный 290 мл (Бельгия)
     * 147531   EB06.S.80   клей стыковочный 80 мл (Бельгия)
	 * 158448   E13.S.60    клей стыковочный 60 мл (новый) замена E03.S.60
	 * 158449   E12.S.290   клей стыковочный 290 мл (новый) замена E02.S.290
     * */
    $m_290 = 6104;
    $u_290 = 6105;
    $s_290 = 158449;
    $s_60 = 158448;
	$s_smp_290 = 6107;
	$s_smp_60 = 6429;
    //$b_m_290 = 147530;
    //$b_s_80 = 147531;
    $b_m_290 = 150861;
    $b_s_80 = 150862;

    global $APPLICATION;
    $city = $APPLICATION->get_cookie('my_city');
    $arFilter = Array("IBLOCK_ID"=>7, "ACTIVE"=>"Y", "ID"=>$city);
    $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), Array("PROPERTY_country"));
    while($ob = $res->GetNextElement()) {
        $item = array_merge($ob->GetFields(),$ob->GetProperties());
        $country = $item['PROPERTY_COUNTRY_VALUE'];
    }

    $menu_arr = Array($m_290,$u_290,$s_60);
    if($city == '3109') { // Москва
        $arr = Array($m_290,$u_290,$s_290,$s_60,$s_smp_290,$s_smp_60);
    } elseif($country == 6712) { // Киргизия
        $arr = Array($m_290,$u_290,$s_290,$s_60,$s_smp_290,$s_smp_60);
	} elseif($country == 3246) { //Казахстан
        $arr = Array($m_290,$u_290,$s_290,$s_60,$s_smp_290,$s_smp_60,$b_m_290);
    } elseif($country == 70966) { //Грузия
        $arr = Array($m_290,$u_290,$s_290,$s_60);
	} elseif($country == 6690) { //Армения
        $arr = Array($m_290,$s_smp_290,$s_smp_60);
        $menu_arr = $arr;
	} elseif($country == 3670) { //Узбекистан
        $arr = Array($m_290,$s_smp_290,$s_smp_60,$s_60,$b_m_290);
    } elseif($country == 3296 || $country == 3726 || $country == 3840 || $country == 6347 || $country == 3250 || $country == 3241) { // Молдова, Прибалтика (Латвия, Литва, Эстония), Украина, Беларусь
        $arr = Array($m_290,$s_290,$s_60);
        $menu_arr = $arr;
    } else { //Россия (регионы), Абхазия
        $arr = Array($m_290,$u_290,$s_290,$s_60,$s_smp_290,$s_smp_60); // $s_290,$s_60
    }
    if($for_menu) {
        return $menu_arr;
    } else {
        return $arr;
    }
}

function build_drop_categories($sections,$collapse = false) {

    if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
        return;
    }


//$last_sections = $_SERVER['REQUEST_URI'];
// = explode('/',$last_sections);
//$last_sections = $last_sections[count($last_sections)-1];
    $last_sections = $_SERVER['REQUEST_URI'];
    $last_sections = explode('?',$last_sections);
    $last_sections = $path = $last_sections[0];
    $last_sections = explode('/',$last_sections);
    $last_sections = array_diff($last_sections,array(''));
    if(!empty($last_sections)) $last_sections = explode("|", implode("|", $last_sections));
//$last_sections = $last_sections[count($last_sections)-1];
    if($last_sections[count($last_sections)-1] == 'catalogue') $last_sections[] = 'karnizy';
    if($last_sections[count($last_sections)-1] == 'interernyj-dekor') $last_sections[] = 'karnizy';
    if($last_sections[count($last_sections)-1] == 'fasadnyj-dekor') $last_sections[] = 'karnizi';

    $last_sections = explode('_', $last_sections[count($last_sections)-1]);

    ob_start();
    $arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, 'GLOBAL_ACTIVE' => 'Y', 'ACTIVE' => 'Y', "DEPTH_LEVEL" => "1", 'CODE' => $sections);
    $db_list = CIBlockSection::GetList(Array("SORT" => "ASC"), $arFilter, false, array('UF_*'));
    $ob = $db_list->GetNextElement();
    /*if ($db_list->SelectedRowsCount() == 0 && $path == '/fasadnyj-dekor/') {
        header('Location: /404.php'); exit;
    }*/
    $l1 = array_merge($ob->GetFields(), $ob->GetProperties());

    $l2_ids = ['1542', '1544', '1546', '1550', '1601', '1622', '1552', '1548'];

    $arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, 'GLOBAL_ACTIVE' => 'Y', 'ACTIVE' => 'Y', "DEPTH_LEVEL" => "2", '=SECTION_ID' => $l1['ID'], '=UF_HIDECATALOG' => '0', 'ID' => $l2_ids);
    $db_list = CIBlockSection::GetList(Array("SORT" => "ASC"), $arFilter, false, array('UF_*'));

    $all = (int)$_GET['all'];

    if ($sections == 'interernyj-dekor') { ?>
        <div>
            <ul>
                <?
                $n = 0;
                while ($l2 = $db_list->GetNextElement()) {
                    /*if($n++ == 10) {
                        echo "</ul><ul>";
                    }*/
                    $l2 = array_merge($l2->GetFields(), $l2->GetProperties()); ?>
                    <? if($collapse && $n++ == 5) { ?>
                        </ul>
                        <ul class="cat-collapse-wrap" data-type="cat-collapse-wrap" style="<?= !empty($all) ? 'display: block;' : '' ?>">
                    <? } ?>
                    <li <?=(in_array($l2['CODE'],$last_sections))? ' class="active"':''?> <?if($collapse && $n <= 5) echo ' data-type="first-cat"'?>>
                        <a href="/<?=$l2['CODE']?>" data-id="<?=$l2['ID']?>">
                            <?=$l2['NAME']?>
                        </a>
                    </li>
 
                <? } ?>

            </ul>
            <? if($collapse) { ?>
                <? if (!empty($all)) { ?>
                    <div class="cat-collapse cat-reset-filters" data-type="cat-hide">Свернуть <i class="icon-angle-up"></i></div>
                <? } else { ?>
                    <div class="cat-collapse cat-reset-filters" data-type="cat-show">Показать все <i class="icon-angle-down"></i></div>
                <? } ?>
            <? } ?>
        </div>

    <? } else {
        //$item_separator = array(4); // сепаратор уровня 2
        $i = 0;
        ?>
        <div>
            <ul>
                <?
                while ($l2 = $db_list->GetNextElement()) {
                $l2 = array_merge($l2->GetFields(), $l2->GetProperties());
                $arFilterl2 = Array('IBLOCK_ID' => IB_CATALOGUE, 'GLOBAL_ACTIVE' => 'Y', 'ACTIVE' => 'Y', "DEPTH_LEVEL" => "3", '=SECTION_ID' => $l2['ID'], '=UF_HIDECATALOG' => '0');
                $db_listl2 = CIBlockSection::GetList(Array("SORT" => "ASC"), $arFilterl2, false, array('UF_*'));
                $l3list = array();
                while ($l3 = $db_listl2->GetNextElement()) {
                    $l3 = array_merge($l3->GetFields(), $l3->GetProperties());
                    $l3list[] = $l3;
                }
                $i++;
                    if (count($l3list)) { ?>
                        <li class="has-child first-line">
                            <span><?=$l2['NAME']?>:</span>
                            <ul>
                                <? foreach ($l3list as $l3) { ?>
                                    <li <?=(in_array($l3['CODE'],$last_sections))? ' class="active"':''?>>
                                        <a href="/<?=$l2['CODE']?>/<?=$l3['CODE']?>" data-id="<?=$l3['ID']?>"><?=$l3['NAME']?></a></li>
                                <? } ?>
                            </ul>
                        </li>
                    <? } else { ?>

                        <? if ($l1['ID'] == 1614) { ?>

                            <li class="first-line<?=(in_array($l2['CODE'],$last_sections))? ' active':''?>">
                                <a href="/<?=$l1['CODE']?>/<?=$l2['CODE']?>/" data-id="<?=$l2['ID']?>"><?=$l2['NAME']?></a>
                            </li>

                        <? } else { ?>
                        
                            <li class="first-line<?=(in_array($l2['CODE'],$last_sections))? ' active':''?>">
                                <a href="/<?=$l2['CODE']?>/" data-id="<?=$l2['ID']?>"><?=$l2['NAME']?></a>
                            </li>

                        <? } ?>

                    <? } ?>
                    <?/* if (in_array($i,$item_separator)) { ?>
                        </ul>
                        <ul>
                    <? } */?>
                <? } ?>
            </ul>
        </div>
    <? }
    $html = ob_get_clean();
    return $html;
}
function getObjectItems()
{
    $res = array();
    $adh_res = array();
    $sample_res = array();
    $cart = json_decode($_COOKIE['basket']);
    $money = 0;
    foreach ($cart as $citem) {
        $tempId = '';
        $citemId = $citem->id;
        $isSample = false;
        if(strpos($citem->id,'s') !== false ) {
            $citemId = substr($citem->id, 1);
            $tempId = $citem->id;
            $isSample = true;
        }
        //$arFilter = Array('IBLOCK_ID' => 12, 'ACTIVE' => 'Y', 'ID' => $citemId);
        $arFilter = Array('IBLOCK_ID' => 12, 'ID' => $citemId);
        $db_list = CIBlockElement::GetList(Array("SORT" => "ASC"), $arFilter);
        $ob = $db_list->GetNextElement();
        if (!$ob) continue;
        $ob = array_merge($ob->GetFields(), $ob->GetProperties());
        $cost = _makeprice(CPrice::GetBasePrice($citem->id));
        if($isSample) $cost['PRICE'] = $ob['SAMPLE_PRICE']['VALUE'] == '' ? DEFAULT_SAMPLE_PRICE : $ob['SAMPLE_PRICE']['VALUE'];
        if($isSample) $ob['sample'] = 'y';
        $money += $cost['PRICE'];
        $ob['price'] = $cost['PRICE'];
        $ob['COUNT'] = $citem->qty;
        if($isSample) {
            $ob['prodId'] = $ob['ID'];
            $ob['ID'] = $tempId;
        }
        $ob = __get_product_images_new($ob);
        if($isSample) {
            $sample_res[] = $ob;
        } else if($ob['IBLOCK_SECTION_ID'] == 1587) {
            $adh_res[] = $ob;
        } else {
            $res[] = $ob;
        }
    }
    $res = array_merge($res,$sample_res,$adh_res);
    return array('items' => $res, 'sum' => $money);
}
function generateUUID() {
    $uuid = '';
    $uuid .= sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),

        // 16 bits for "time_mid"
        mt_rand(0, 0xffff),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand(0, 0x0fff) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand(0, 0x3fff) | 0x8000,

        // 48 bits for "node"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
    $uuid = mb_strtoupper($uuid);
    return ($uuid);
}
function _itemParams($item = null,$flex = false,$show_flex = false)
{
    if (!item) return '';
    if (($item['FLEX']['VALUE'] != 'Y') && $flex) {
        //$arFilter = Array('IBLOCK_ID'=>IB_CATALOGUE, 'CODE'=>$item['CODE'].'-f', 'ACTIVE'=>'Y', 'PROPERTY_FLEX'=>'Y');
        $arFilter = Array('IBLOCK_ID'=>IB_CATALOGUE, 'CODE'=>$item['CODE'].'-f', 'PROPERTY_FLEX'=>'Y');
        $db_list = CIBlockElement::GetList(Array(), $arFilter);
        $item_flex = null;
        if ($item_flex = $db_list->GetNextElement()) {
            $item_flex = array_merge($item_flex->GetFields(), $item_flex->GetProperties());
            $item = array_merge($item, $item_flex);
        }
    }

    $res_s = array();
    $res_f = array();
    foreach ($item as $key => $val) {
        if (!preg_match("/S(\d+)/", $key)) continue;
        if ($val['FILTRABLE'] != 'Y') continue;

        if ($val['USER_TYPE'] == 'Checkbox' && $val['VALUE'] == 'Y') {
            $res_s[$val['NAME']] = "да";

        } elseif ((($val['CODE'] == 'S4') || ($val['CODE'] == 'S5') || ($val['CODE'] == 'S6')) && $val['VALUE'] && $item_flex)
        { // гибкий в конец в нестандарт
            $name = explode(",", $val['NAME']);
            $dec = $name[count($name)-1];
            if ($val['CODE'] == 'S4') $name = 'изгиб выпуклый';
            if ($val['CODE'] == 'S5') $name = 'изгиб вогнутый';
            if ($val['CODE'] == 'S6') $name = 'изгиб арочный';

            $res_f[$name] = '<b>'.$val['VALUE'].'</b>'.'<small>'.($dec?' '.$dec:'').'</small>';

        } elseif ($val['USER_TYPE'] != 'Checkbox' && $val['VALUE']) {
            $name = explode(",", $val['NAME']);
            $dec = $name[count($name)-1];
            unset($name[count($name)-1]);
            $name = implode(",", $name);
            $res_s[$name] = '<b>'.$val['VALUE'].'</b>'.'<small>'.($dec?' '.$dec:'').'</small>';
        }
    }
    ob_start();
    ?>
    <table>
        <? if (count($res_s)) { ?>
            <? foreach ($res_s as $name => $pitem) { ?>
                <tr>
                    <td colspan="2" style="width: auto; padding: 1px 0 0 0; border-bottom: 1px dotted #ccc;"><?=$name?>:</td>
                    <td style="width: 60px; text-align: right; padding: 1px 0 0 0; border-bottom: 1px dotted #ccc;"><?=$pitem?></td></tr>
            <? } ?>
        <? } ?>
        <? if ($show_flex) {
            echo '<tr><td colspan="2" style="padding: 6px 0 0 0;">Гибкий аналог: </td>';
            echo '<td style="text-align: center; padding: 6px 0 0 0;">';
            if ($item_flex) {echo 'Есть';} else {echo 'Нет';}
            echo '</td></tr>'; } ?>
        <? if (count($res_f)) { ?>
            <tr><td colspan="3" style="width: auto; padding: 6px 0 0 0; border-bottom: 0px;">Радиусы гибких аналогов:</td></tr>
            <? foreach ($res_f as $name => $pitem) { ?>
                <tr>
                    <td style="width: auto; padding: 1px 0 0 0; border-bottom: 0px;">&nbsp;&nbsp;&nbsp;&nbsp;&bull;</td>
                    <td style="width: auto; padding: 1px 0 0 0; border-bottom: 1px dotted #ccc;"><?=$name?>:</td>
                    <td style="width: 60px; text-align: right; padding: 1px 0 0 0; border-bottom: 1px dotted #ccc;"><?=$pitem?></td></tr>
            <? } ?>
        <? } ?>
    </table>

    <?
    $html = ob_get_clean();

    return $html;
}
function _get_email_product_list()
{
    ob_start();
    $res = getObjectItems();
    $money = $res['sum'];
    $cart = $res['items'];
    ?>
    <table class="cart_t" style="width: 720px; margin: 12px 0px;">
        <tbody>
        <tr>
            <th style="width: 15%; padding: 6px 12px;	text-align: left;font: bold 13px Arial, Helvetica, sans-serif;color: #fff;border: 1px #eaeaea solid;background: #849795;">
                Миниатюра
            </th>
            <th style=" width: 33%; padding: 6px 12px;	text-align: left;font: bold 13px Arial, Helvetica, sans-serif;color: #fff;border: 1px #eaeaea solid;background: #849795;">
                Наименование
            </th>
            <th style="width: 19%; padding: 6px 12px;	text-align: left;font: bold 13px Arial, Helvetica, sans-serif;color: #fff;border: 1px #eaeaea solid;background: #849795;">
                Цена
            </th>
            <th style="width: 10%; text-align: center; padding: 6px 12px;	text-align: left;font: bold 13px Arial, Helvetica, sans-serif;color: #fff;border: 1px #eaeaea solid;background: #849795;">
                Количество
            </th>
            <th style="width: 28%; padding: 6px 12px;	text-align: left;font: bold 13px Arial, Helvetica, sans-serif;color: #fff;border: 1px #eaeaea solid;background: #849795;">
                Сумма
            </th>
        </tr>
        <?
        $all_price = 0;
        $all_sample_price = 0;
        $all_number= 0;
        $sampleArr = Array();
        $i = 0;
        $samplesCount = 0;
        ?><?

        foreach ($cart as $citem) {
            $isSample = false;
            $citemId = $citem['ID'];
            if($citem['prodId'] != '') {
                $citemId = substr($citem['ID'],1);
                $isSample = true;
                $samplesCount++;
            }
            $res = CIBlockElement::GetByID($citemId);
            if($arRes = $res->Fetch())
            {
                $res = CIBlockSection::GetByID($arRes["IBLOCK_SECTION_ID"]);
                if($arRes = $res->Fetch())
                {
                    $section_id = $arRes["ID"];
                    $arFilter = Array('IBLOCK_ID'=>IB_CATALOGUE, 'ACTIVE'=>'Y', 'ID'=>$section_id);
                    $db_list = CIBlockSection::GetList(Array(), $arFilter, false, array('UF_*'));
                    $last_section = $db_list->GetNext();
                }
            }
            if ($last_section['UF_H'] == 1) {
                $signTmp = 'align="right"';
                $signStyle = 'width:100%;height:auto;';
            } else {
                $signTmp = 'align="center"';
                $signStyle = 'width:auto;height:90%;';
            }
            if ($citem['FLEX']['VALUE'] == "Y") {
                $signTmp = 'align="left"';
                $signStyle = 'width:100%;height:auto;';
            }
            $uri = __get_product_link($citem);
            $i++;
            $simple = true;
            $sections = array_reverse(___get_product_sections($citem));


            //$citem['price'] = round($citem['price']);
            if($isSample) {
                $all_sample_price += $citem['price']*$citem['COUNT'];
            } else {
                $all_price += $citem['price']*$citem['COUNT'];
            }

            $all_number+= $citem['COUNT'];
            ?>
            <tr class="cart_row">
                <td class="c_1" style="border: none; width:116px; height:104px; overflow:hidden; background-color:#4e4e4e;vertical-align:middle;" <?=$signTmp?>>
                    <?if($isSample) {?>
                        <div style="width:100%;min-width:116px;text-align: center;background-color: #a7a7a7;color: #4e4e4e;padding-top:2px;padding-bottom:2px;font-weight:bold;font-size:14px;">образец</div>
                    <? } ?>
                    <?if(!empty($citem['SELLOUT']['VALUE'])){ ?>
                        <div style="width:100%;min-width:116px;text-align: center;background-color: #E41C1C;color: #fff;padding-top:2px;padding-bottom:2px;font-weight:bold;font-size:14px;">распродажа</div>
                    <? } ?>
                    <a href="http://<?=$_SERVER['HTTP_HOST']?><?= __get_product_link($citem) ?>">
                        <img  style="<?=$signStyle?> max-height:104px;"  src="http://<?=$_SERVER['HTTP_HOST']?><?= $citem['FILES_IMAGES'][0] ?>" alt="<?= __get_product_name($citem) ?>"/>
                    </a>
                </td>

                <td style="border: 1px #eaeaea solid; padding: 2px 12px;">
                    <input type="hidden" class="data-item-id" value="<?= $citem['ID'] ?>"/>
                    <span class="number"><?= $i ?>.</span>&nbsp;
                    <a href="http://<?=$_SERVER['HTTP_HOST']?><?= __get_product_link($citem) ?>"><?= __get_product_name($citem) ?><?if($isSample) echo ' <b>образец</b>'?></a>
                    <? if ($citem['COMPLEX']['VALUE'] == 'Y') {?>
                        <hr style="border-top:1px dotted black;">
                        <div style="font-size: 80%;border: none;width: 100%;">
                            <?=$citem['~PREVIEW_TEXT']?>
                        </div>
                    <?}?>
                </td>
                <td style="border: 1px #eaeaea solid; padding: 2px 12px;">
			<span<?= $citem['price'] > 0 ? '' : ' style="color: #fff;"' ?>><span
                        class="cart_item_cost"><?= $citem['price'] > 0 ? __cost_format($citem['price']) : '' ?></span></span>
                    <?=($citem['NAME'] == 'угловой элемент')?"<br>за 1 шт.":""?></td>
                </td>
                <td class="cart_item_count" style="border: 1px #eaeaea solid; padding: 2px 12px;">
                    <span class="cart_item_count"><?= $citem['COUNT'] ?></span></td>
                <td style="border: 1px #eaeaea solid; padding: 2px 12px;">
                         <span<?= $citem['price'] > 0 ? '' : ' style="color: #fff;"' ?>><span
                                     class="cart_item_amount"><?= __cost_format(round($citem['price']*$citem['COUNT'],2)) ?></span></span></td>
            </tr>
            <?

        } ?>


        <?if(count($sampleArr) > 0) {
            foreach($sampleArr as $citem) {
                $uri = __get_product_link($citem);
                $i++;
                $simple = true;
                $sections = array_reverse(___get_product_sections($citem));
                $all_sample_price += $citem['price']*$citem['COUNT'];
                $all_number+= $citem['COUNT'];
                $res = CIBlockElement::GetByID($citem['prodId']);
                if($arRes = $res->Fetch())
                {
                    $res = CIBlockSection::GetByID($arRes["IBLOCK_SECTION_ID"]);
                    if($arRes = $res->Fetch())
                    {
                        $section_id = $arRes["ID"];
                        $arFilter = Array('IBLOCK_ID'=>IB_CATALOGUE, 'ACTIVE'=>'Y', 'ID'=>$section_id);
                        $db_list = CIBlockSection::GetList(Array(), $arFilter, false, array('UF_*'));
                        $last_section = $db_list->GetNext();
                    }
                }
                if ($last_section['UF_H'] == 1) {
                    $signTmp = 'align="right"';
                    $signStyle = 'width:100%;height:auto;';
                } else {
                    $signTmp = 'align="center"';
                    $signStyle = 'width:auto;height:90%;';
                }
                if ($citem['FLEX']['VALUE'] == "Y") {
                    $signTmp = 'align="left"';
                    $signStyle = 'width:100%;height:auto;';
                }
                ?>
                <td class="c_1" style="border: none; width:116px; height:104px; overflow:hidden; background-color:#4e4e4e;vertical-align:middle;" <?=$signTmp?>>
                    <div style="width:116px;text-align: center;background-color: #a7a7a7;color: #4e4e4e;padding-top:2px;padding-bottom:2px;font-weight:bold;font-size:14px;">образец</div>
                    <a href="http://<?=$_SERVER['HTTP_HOST']?><?= __get_product_link($citem) ?>">
                        <img  style="<?=$signStyle?> max-height:104px;"  src="http://<?=$_SERVER['HTTP_HOST']?><?= $citem['FILES_IMAGES'][0] ?>" alt="<?= __get_product_name($citem) ?>"/>
                    </a>
                </td>
                <td style="border: 1px #eaeaea solid; padding: 2px 12px;">
                    <input type="hidden" class="data-item-id" value="<?= $citem['ID'] ?>"/>
                    <span class="number"><?= $i ?>.</span>&nbsp;
                    <a href="http://<?=$_SERVER['HTTP_HOST']?><?= __get_product_link($citem) ?>"><?= __get_product_name($citem) ?> <b>образец</b></a>
                        <? if ($citem['COMPLEX']['VALUE'] == 'Y') {?>
                            <hr style="border-top:1px dotted black;">
                            <div style="font-size: 80%;border: none;width: 100%;">
                                <?=$citem['~PREVIEW_TEXT']?>
                            </div>
                        <?}?>
                </td>
                <td style="border: 1px #eaeaea solid; padding: 2px 12px;">
			            <span<?= $citem['price'] > 0 ? '' : ' style="color: #fff;"' ?>>
                            <span class="cart_item_cost">
                                <?= $citem['price'] > 0 ? __cost_format($citem['price']) : '' ?>
                            </span>
                        </span>
                    <?=($citem['NAME'] == 'угловой элемент')?"<br>за 1 шт.":""?>
                </td>
                <?//</td>?>
                <td class="cart_item_count" style="border: 1px #eaeaea solid; padding: 2px 12px;">
                    <span class="cart_item_count"><?= $citem['COUNT'] ?></span>
                </td>
                <td style="border: 1px #eaeaea solid; padding: 2px 12px;">
                         <span<?= $citem['price'] > 0 ? '' : ' style="color: #fff;"' ?>>
                             <span class="cart_item_amount">
                                 <?= __cost_format(round($citem['price']*$citem['COUNT'],2)) ?>
                             </span>
                         </span>
                </td>
                </tr>
                <?
            }
        }
        ?>
        <tr><td style="text-align: right; border: 1px #eaeaea solid; padding: 6px 12px;"
                colspan="3">Всего товаров</td>
            <td style="text-align: left; border: 1px #eaeaea solid; padding: 6px 12px;"
                colspan="1"> <span><b><?= $all_number ?></b></span> шт.</td>
            <td style="text-align: left; border: 1px #eaeaea solid; padding: 6px 12px;"
                colspan="1"> <span><b><?= __cost_format($all_price+$all_sample_price) ?></span></b></td></tr>

        <? $discount = __discount_mob($all_price);
        if ($discount != NULL) { ?>
            <tr><td style="text-align: right; border: 1px #eaeaea solid; padding: 6px 12px;"
                    colspan="3">Ваша скидка</td>
                <td style="text-align: left; border: 1px #eaeaea solid; padding: 6px 12px;"
                    colspan="1"><span><?=$discount['discount']?> %</span></td>
                <td style="text-align: left; border: 1px #eaeaea solid; padding: 6px 12px;"
                    colspan="1"><span><?=__cost_format($discount['discount_price'])?></span></td></tr>

            <?
            $total = $discount['total'];
            if($_REQUEST['km']!='' && $_REQUEST['del'] == 'del') {
                $onlySamples = $samplesCount == count($cart) ? true : false;
                $delivery = countDelivery($_REQUEST['km'],$discount['total'],$onlySamples);
                $total = $delivery['total'];
                ?>
                <tr><td style="text-align: right; border: 1px #eaeaea solid; padding: 6px 12px;"
                        colspan="3">Стоимость доставки:</td>
                    <td style="text-align: left; border: 1px #eaeaea solid; padding: 6px 12px;"
                        colspan="1"></td>
                    <td style="text-align: left; border: 1px #eaeaea solid; padding: 6px 12px;"
                        colspan="1"><span><?=__cost_format($delivery['del'])?></span></td></tr>
            <? } ?>

            <tr><td style="text-align: right; border: 1px #eaeaea solid; padding: 6px 12px;"
                    colspan="3"><b>Итого, с учетом скидки</b></td>
                <td style="text-align: left; border: 1px #eaeaea solid; padding: 6px 12px;"
                    colspan="1"></td>
                <td style="text-align: left; border: 1px #eaeaea solid; padding: 6px 12px;"
                    colspan="1"><span><b><?=__cost_format($total + $all_sample_price)?></b></span></td></tr>
        <? } elseif($_REQUEST['km']!='' && $_REQUEST['del'] == 'del') {
            $onlySamples = $samplesCount == count($cart) ? true : false;
            $delivery = countDelivery($_REQUEST['km'],$all_price,$onlySamples);
            $total = $delivery['total']; ?>
            <tr><td style="text-align: right; border: 1px #eaeaea solid; padding: 6px 12px;"
                    colspan="3">Стоимость доставки:</td>
                <td style="text-align: left; border: 1px #eaeaea solid; padding: 6px 12px;"
                    colspan="1"></td>
                <td style="text-align: left; border: 1px #eaeaea solid; padding: 6px 12px;"
                    colspan="1"><span><?=__cost_format($delivery['del'])?></span></td></tr>
            tr><td style="text-align: right; border: 1px #eaeaea solid; padding: 6px 12px;"
                   colspan="3"><b>Итого, с учетом доставки</b></td>
            <td style="text-align: left; border: 1px #eaeaea solid; padding: 6px 12px;"
                colspan="1"></td>
            <td style="text-align: left; border: 1px #eaeaea solid; padding: 6px 12px;"
                colspan="1"><span><b><?=__cost_format($total + $all_sample_price)?></b></span></td></tr>
        <? } ?>


        <td style="text-align: left; padding: 12px 12px; font: 16px Arial;border: 0px #eaeaea solid;"
            colspan="5"><small>Он-лайн заказ не является публичной офертой.</small><br><br>
        </td>
        </tr>
        <tr><td style="text-align: left; padding: 0px 12px; font: 18px Arial;border: 0px #eaeaea solid;"
                colspan="5">Ваш Перфом<br><br>
            </td></tr>
        </tbody>
    </table>

    <?

    $html = ob_get_clean();

    return $html;
}
function _get_email_product_list_pdf($my_city = null)
{

    $arFilter = Array('IBLOCK_ID' => 7, 'ACTIVE' => 'Y', 'ID' => $my_city);
    $db_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter);
    $loc = $db_list->GetNextElement();
    if (!$loc || ($my_city == 3741)) { // 3741 = московская область
        $arFilter = Array('IBLOCK_ID' => 7, 'ACTIVE' => 'Y', 'CODE' => 'moskva');
        $db_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter);
        $loc = $db_list->GetNextElement();
    }
    $loc = array_merge($loc->GetFields(), $loc->GetProperties());

    $my_city = $loc;

    $my_dealer = null;
    $city_f = null;
    $Reg_f = null;

    $arFilter = Array('IBLOCK_ID' => 7, 'ACTIVE' => 'Y', 'ID' => $my_city, 'PROPERTY_online' => 'N');
    $db_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter);
    if ($db_list) {
        $el = $db_list->GetNextElement();
        if ($el) {
            $arFilter = Array('IBLOCK_ID' => 6, 'ACTIVE' => 'Y', 'PROPERTY_city' => $my_city);
            $db_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter);
            if ($db_list) {
                $el = $db_list->GetNextElement();
                if ($el) {
                    $el = array_merge($el->GetFields(), $el->GetProperties());
                    $my_dealer = $el;

                }
            }

        }
    }


// название региона главного
    $arRegFilter = Array('IBLOCK_ID' => 8, 'ACTIVE' => 'Y', 'ID'=>$my_city['discountregion']['VALUE']);
    $db_Reg_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arRegFilter);
    $Reg_f = $db_Reg_list->GetNextElement();
    $Reg_f = array_merge($Reg_f->GetFields(), $Reg_f->GetProperties());

    $email_number = $loc['email_number']['VALUE'];
    if ($loc['dealers_list']['VALUE']) {
        if ($email_number == 0) { // старт первого дилера при начале
            $email_number = 1;
        }
// Операция по отправке
// Дилер
        $cart_dealer = $loc['dealers_list']['VALUE'][$email_number-1];
        $arFilter = Array('IBLOCK_ID' => 6, 'ACTIVE' => 'Y', 'ID' => $cart_dealer);
        $db_list = CIBlockElement::GetList(Array("SORT" => "ASC"), $arFilter);
        $cart_dealer = $db_list->GetNextElement();
        if ($cart_dealer) {
            $cart_dealer = array_merge($cart_dealer->GetFields(), $cart_dealer->GetProperties());
            $my_dealer = $cart_dealer;
        }
    }

    ob_start();
    $res = getObjectItems();
    $money = $res['sum'];
    $cart = $res['items'];

    require_once($_SERVER["DOCUMENT_ROOT"] . "/include/phone.php");
    ?>
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    </head>

    <body>
    <style>
        @import url('https://fonts.googleapis.com/css?family=Roboto+Condensed&subset=cyrillic');

        body {
            font-family: Roboto !important;
        }
    </style>
    <? // Лого ?>
    <a href="http://<?=$_SERVER['HTTP_HOST']?>"> <img src="<?=$_SERVER["DOCUMENT_ROOT"]?>/img/e-logo.jpg" width="157"/></a>

    <? // Контакт ?>

    <table class="cart_t" style="width: 720px; margin: 12px 0px;">
        <tr>
            <td style="text-align: left; padding: 12px 12px; font: 16px Roboto;color: #fff;border: 0px #eaeaea solid;background: #849795;">
                Регион, город, контактная информация:
            </td>
        </tr>
        <tr>
            <td style="border: 1px #d9d9d9 solid; padding: 12px 12px; font: 14px Roboto; line-height:7px;">

                <p style="padding: 0px 12px;"><?= $Reg_f['NAME'] ?>, <?= $my_city['NAME'] ?></p>

                <? if ($my_city['CODE'] == 'moskva'){ ?>
                    <p style="padding: 0px 12px; font-size: 18px;">Фирменный салон в ТЦ "Экспострой на Нахимовском"</p>
                    <p style="padding: 0px 12px;">адрес: Нахимовский пр-т, д. 24, Павильон N3, 2-этаж, салон 49/2,  вход через магазин Union</p>
                    <p style="padding: 0px 12px;">телефоны: +7 (495) 116-55-36; +7 (495) 116-55-37</p>
                    <p style="padding: 0px 12px;">e-mail: salonn@decor-evroplast.ru</p>

                <? } elseif ($my_dealer) { ?>

                    <? if ($my_dealer['organization']['VALUE']) { ?> <p style="padding: 0px 12px;"><?= $my_dealer['organization']['VALUE'] ?></p> <? } ?>
                    <? if ($my_dealer['address']['VALUE']) { ?> <p style="padding: 0px 12px;">адрес: <?= $my_dealer['address']['VALUE'] ?></p> <? } ?>
                    <? if ($my_dealer['phones']['VALUE']) { ?><p style="padding: 0px 12px;">телефон: <?= str_phone($my_dealer['phones']['VALUE']) ?></p> <? } ?>
                    <? if ($my_dealer['email']['VALUE']) { ?> <p style="padding: 0px 12px;">e-mail: <?= $my_dealer['email']['VALUE'] ?></p> <? } ?>
                <? } ?>

            </td>
        </tr>
    </table>


    <? // Шапка таблицы ?>
    <table class="cart_t" style="width: 720px; margin: 12px 0px;" class="font">
        <tr style="border: 1px solid #EAEAEA;">
            <th style="width: 15%; padding: 6px 12px;	text-align: left;font: 13px Roboto;color: #fff;border: 0px #d9d9d9 solid;background: #849795;">
                Миниатюра
            </th>
            <th style="width: 34%; padding: 6px 12px;	text-align: left;font: 13px Roboto;color: #fff;border: 0px #d9d9d9 solid;background: #849795;">
                Наименование, характеристики
            </th>
            <th style="width: 19%; padding: 6px 12px;	text-align: left;font: 13px Roboto;color: #fff;border: 0px #d9d9d9 solid;background: #849795;font-family: Roboto;">
                Цена
            </th>
            <th style="width: 10%; text-align: center; padding: 6px 12px;	text-align: left;font: 13px Roboto;color: #fff;border: 0px #d9d9d9 solid;background: #849795;">
                Кол-во
            </th>
            <th style="padding: 6px 12px; text-align: left;font:13px Roboto;color: #fff;border: 0px #d9d9d9 solid;background: #849795;">
                Сумма
            </th>
        </tr>

        <? // Таблица

        $all_price = 0;
        $all_sample_price = 0;
        $all_number = 0;
        $i = 0;


        foreach ($cart as $citem) {
            $isSample = false;
            $citemId = $citem['ID'];
            if(strpos($citem['ID'],'s') !== false) {
                $isSample = true;
                $citemId = substr($citem['ID'],1);
            }
            $res = CIBlockElement::GetByID($citemId);
            if($arRes = $res->Fetch())
            {
                $res = CIBlockSection::GetByID($arRes["IBLOCK_SECTION_ID"]);
                if($arRes = $res->Fetch())
                {
                    $section_id = $arRes["ID"];
                    $arFilter = Array('IBLOCK_ID'=>IB_CATALOGUE, 'ACTIVE'=>'Y', 'ID'=>$section_id);
                    $db_list = CIBlockSection::GetList(Array(), $arFilter, false, array('UF_*'));
                    $last_section = $db_list->GetNext();
                }
            }
            if ($last_section['UF_H'] == 1) {
                $signTmp = 'align="right"';
                $signStyle = 'width:100%;height:auto;';
            } else {
                $signTmp = 'align="center"';
                $signStyle = 'width:auto;height:90%;';
            }
            if ($citem['FLEX']['VALUE'] == "Y") {
                $signTmp = 'align="left"';
                $signStyle = 'width:100%;height:auto;';
            }

            $i++;

            // составной
            if ($citem['COMPOSITEPART']['VALUE']) {

                ?>
                <tr>
                    <td style="border: 1px #d9d9d9 solid; padding: 0px 12px; background-color: #3b3b3b;">
                        <img  style="width: 104px; height: 104px;"  src="<?=$_SERVER["DOCUMENT_ROOT"]?><?= $citem['FILES_IMAGES'][0] ?>" alt="<?= __get_product_name($citem) ?>"/>

                    </td>
                    <td style="vertical-align: Top; border: 1px #eaeaea solid; padding: 2px 12px; background: #F0F8FF;">
                        <input type="hidden" class="data-item-id" value="<?= $citemId ?>"/>
                        <span class="number" style="font: 16px Roboto;"><?= $i ?>.</span>&nbsp;
                        <span style="font: 16px Roboto;"><?= __get_product_name($citem,true,true) ?><?if($isSample) echo ' образец'?></span>
                        <hr style="border: 0; background-color: #849795; height: 1px;">
                        <span style="font: 16px Roboto;">конструкция</span>

                    <td style="border: 1px #d9d9d9 solid; padding: 2px 12px; font: 16px Roboto; background: #F0F8FF;">

                    </td>
                    <td style="border: 1px #d9d9d9 solid; padding: 2px 12px; font: 16px Roboto; background: #F0F8FF;">
                        <span class="cart_item_count"><?= $citem['COUNT'] ?></span> шт</td>
                    <td style="border: 1px #d9d9d9 solid; padding: 2px 12px; font: 16px Roboto; background: #F0F8FF;">

                    </td>
                </tr>
                <?
                $ids = $citem['COMPOSITEPART']['VALUE'];
                $ids['LOGIC'] = 'OR';
                //$arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, 'ACTIVE' => 'Y', 'ID' => $ids);
                $arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, 'ID' => $ids);
                $db_list = CIBlockElement::GetList(Array("SORT" => "ASC"), $arFilter);
                $k_i = $i;
                while ($ob = $db_list->GetNextElement()) {
                    $item_properties = $ob->GetProperties();
                    $ob = array_merge($ob->GetFields(), $ob->GetProperties());
                    $cost = _makeprice(CPrice::GetBasePrice($ob['ID']));
                    $ob['price'] = $cost['PRICE'];
                    //$ob['price'] = round($ob['price']);
                    $ob = __get_product_images($ob);
                    $i++;

                    ?>
                    <tr>
                        <td style="border: 1px #d9d9d9 solid; padding: 0px 12px;position: relative;">
                            <?if($isSample) { ?>
                                <div style="position: absolute;top: 0; left: 0;right: 0;bottom: 0;background: url('/img/sample.png') no-repeat center center;z-index: 1;width: 116px;height: 104px;-webkit-background-size: contain;background-size: contain;"></div>
                            <? } ?>
                            <img  style="width: 104px; height: 104px;"  src="<?=$_SERVER["DOCUMENT_ROOT"]?><?= $ob['FILES_IMAGES'][0] ?>" alt="<?= __get_product_name($ob) ?>"/>

                        </td>
                        <td style="vertical-align: Top; border: 1px #d9d9d9 solid; padding: 2px 12px;">
                            <input type="hidden" class="data-item-id" value="<?= $ob['ID'] ?>"/>
                            <span class="number" style="font: 16px Roboto;"><?= $i ?>.</span>&nbsp;
                            <span style="font: 16px Roboto;"><?= __get_product_name($ob) ?> </span>
                            <br><span style="font: 16px Roboto;"> ( конструкция № <?= $k_i ?> )</span>
                            <hr style="border: 0; background-color: #849795; height: 1px;">

                            <table style="width: 100%; padding: 0px 6px; font:11px Roboto; text-align: left;line-height:5px;">
                                <? //свойства
                                $res_s = array();
                                foreach ($item_properties as $key => $val) {
                                    if (!preg_match("/S(\d+)/", $key)) continue;
                                    if ($val['FILTRABLE'] != 'Y') continue;

                                    if ($val['USER_TYPE'] == 'Checkbox' && $val['VALUE'] == 'Y') {
                                        $res_s[$val['NAME']] = "Да";

                                    } elseif ($val['USER_TYPE'] != 'Checkbox' && $val['VALUE']) {
                                        $name = explode(",", $val['NAME']);
                                        $dec = $name[count($name)-1];
                                        unset($name[count($name)-1]);
                                        $name = implode(",", $name);
                                        $propVal = $val['VALUE'];
                                        if($isSample && $name == 'Длина детали') $propVal = '250';
                                        echo '<tr><td style="width: 70%; padding-bottom: 3px;">'.$name.':</td>';
                                        echo '<td style="width: 20%;">'.$propVal.'</td>';
                                        echo '<td style="width: 10%;">'.($dec?' '.$dec:'').'</td></tr>';
                                    }
                                }
                                ?>
                            </table>

                        </td>
                        <td style="border: 1px #d9d9d9 solid; padding: 2px 12px; font:14px Roboto;">
			<span<?= $ob['price'] > 0 ? '' : ' style="color: #fff;"' ?>><span
                        class="cart_item_cost"><?= $ob['price'] > 0 ? __cost_format($ob['price']) : '' ?></span></span>
                        </td>
                        <td style="border: 1px #d9d9d9 solid; padding: 2px 12px; font:14px Roboto;">
                            <span class="cart_item_count"><?= $citem['COUNT'] ?></span> шт</td>

                        <td style="border: 1px #d9d9d9 solid; padding: 2px 12px; font:14px Roboto;">
			<span<?= $ob['price'] > 0 ? '' : ' style="color: #fff;"' ?>><span
                        class="cart_item_amount"><?= __cost_format(round($ob['price']*$citem['COUNT'],2)) ?></span></span></td>
                    </tr>


                    <?
                    if($isSample) {
                        $all_sample_price += $ob['price']*$citem['COUNT'];
                    } else {
                        $all_price += $ob['price']*$citem['COUNT'];
                    }
                    $all_number+= $citem['COUNT'];
                }
                // Простой
            } else {
                if($isSample) {
                    $all_sample_price += $citem['price']*$citem['COUNT'];
                } else {
                    $all_price += $citem['price']*$citem['COUNT'];
                }
                $all_number+= $citem['COUNT'];
                ?>

                <tr>
                    <td style="border: 1px #d9d9d9 solid; width:116px; height:104px; overflow:hidden; background-color:#3b3b3b;vertical-align:middle;position:relative;" <?=$signTmp?>>
                        <?if($isSample) { ?>
                            <div style="position: absolute;top: 0; left: 1px;right: 0;bottom: 0;background: url('/img/sample.png') no-repeat center center;z-index: 1;width: 100%;height: 104px;-webkit-background-size: contain;background-size: contain;"></div>
                        <? } ?>
                        <img  style="<?=$signStyle?> max-height:104px;"  src="<?=$_SERVER["DOCUMENT_ROOT"]?><?= $citem['FILES_IMAGES'][0] ?>" alt="<?= __get_product_name($citem) ?>"/>

                    </td>
                    <td style="vertical-align: Top; border: 1px #d9d9d9 solid; padding: 2px 12px;">
                        <input type="hidden" class="data-item-id" value="<?= $citemId ?>"/>
                        <span class="number" style="font: 16px Roboto;"><?= $i ?>.</span>&nbsp;
                        <span style="font: 16px Roboto;line-height:14px;"><?= __get_product_name($citem) ?><?if($isSample) echo ' образец'?></span><hr style="border: 0; background-color: #849795; height: 1px;">

                        <table style="width: 100%; padding: 0px 6px; font: 11px Roboto; text-align: left;line-height:5px;">
                            <? //свойства
                            //$arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, "ACTIVE" => "Y", "ACTIVE_DATE" => "Y", "ID"=>$citemId);
                            $arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, "ID"=>$citemId);
                            $db_list = CIBlockElement::GetList(Array("SORT" => "ASC"), $arFilter);
                            $item_p = $db_list->GetNextElement();
                            $item_properties = $item_p->GetProperties();

                            //Свойства товара к показу
                            $res_s = array();
                            foreach ($item_properties as $key => $val) {
                                if (!preg_match("/S(\d+)/", $key)) continue;
                                if ($val['FILTRABLE'] != 'Y') continue;

                                if ($val['USER_TYPE'] == 'Checkbox' && $val['VALUE'] == 'Y') {
                                    $res_s[$val['NAME']] = "Да";

                                } elseif ($val['USER_TYPE'] != 'Checkbox' && $val['VALUE']) {
                                    $name = explode(",", $val['NAME']);
                                    $dec = $name[count($name)-1];
                                    unset($name[count($name)-1]);
                                    $name = implode(",", $name);
                                    $propVal = $val['VALUE'];
                                    if($isSample && $name == 'Длина детали') $propVal = '250';
                                    echo '<tr><td style="width: 70%; padding-bottom: 3px;">'.$name.':</td>';
                                    echo '<td style="width: 20%;">'.$propVal.'</td>';
                                    echo '<td style="width: 10%;">'.($dec?' '.$dec:'').'</td></tr>';
                                }
                            }
                            ?>
                        </table>
                        <? if ($citem['COMPLEX']['VALUE'] == 'Y') {?>
                            <hr style="border-top:1px dotted black;">
                            <div style="font: 12px Roboto;border: none;width: 100%;">
                                <?=$citem['~PREVIEW_TEXT']?>
                            </div>
                        <?}?>
                    </td>
                    <td style="border: 1px #d9d9d9 solid; padding: 2px 12px; font: 14px Roboto;">
			<span<?= $citem['price'] > 0 ? '' : ' style="color: #fff;"' ?>><span
                        class="cart_item_cost"><?= $citem['price'] > 0 ? __cost_format($citem['price']) : '' ?></span></span>
                        <?=($citem['NAME'] == 'угловой элемент')?"<br>за 1 шт.":""?></td>
                    </td>
                    <td style="border: 1px #d9d9d9 solid; padding: 2px 12px; font: 14px Roboto;">
                        <span class="cart_item_count"><?= $citem['COUNT'] ?></span> шт</td>

                    <td style="border: 1px #d9d9d9 solid; padding: 2px 12px; font: 14px Roboto;">
			<span<?= $citem['price'] > 0 ? '' : ' style="color: #fff;"' ?>><span
                        class="cart_item_amount"><?= __cost_format(round($citem['price']*$citem['COUNT'],2)) ?></span></span></td>
                </tr>
                <?
            }
        }?>

        <tr>
            <td style="font: 14px Roboto; text-align: right; border: 1px #d9d9d9 solid; padding: 12px 12px;"
                colspan="3">Всего товаров</td>
            <td style="font: 14px Roboto; text-align: left; border: 1px #d9d9d9 solid; padding: 12px 12px;"
                colspan="1"> <span><?= $all_number ?></span> шт.</td>
            <td style="font: 14px Roboto; text-align: left; border: 1px #d9d9d9 solid; padding: 12px 12px;"
                colspan="1"> <span><?= __cost_format($all_price + $all_sample_price) ?></span></td></tr>

        <? $discount = __discount_mob($all_price);
        if ($discount != NULL) { ?>
            <tr><td style="font: 14px Roboto; text-align: right; border: 1px #d9d9d9 solid; padding: 12px 12px;"
                    colspan="3">Ваша скидка</td>
                <td style=" font: 14px Roboto; text-align: left; border: 1px #d9d9d9 solid; padding: 12px 12px;"
                    colspan="1"><span><?=$discount['discount']?> %</span></td>
                <td style="font: 14px Roboto; text-align: left; border: 1px #d9d9d9 solid; padding: 12px 12px;"
                    colspan="1"><span><?=__cost_format($discount['discount_price'])?></span></td></tr>


            <tr><td style="font: 14px Roboto; text-align: right; border: 1px #d9d9d9 solid; padding: 12px 12px;"
                    colspan="3">Итого, с учетом скидки</td>
                <td style="font: 14px Roboto; text-align: left; border: 1px #d9d9d9 solid; padding: 12px 12px;"
                    colspan="1">
                <td style="font: 14px Roboto; text-align: left; border: 1px #d9d9d9 solid; padding: 12px 12px;"
                    colspan="1"><span><?=__cost_format($discount['total'] + $all_sample_price)?></span></td></tr>
        <? } ?>
        </tr>
        <tr>
            <td style="text-align: left; padding: 12px 12px; font: 14px Roboto;border: 0px #d9d9d9 solid;"
                colspan="5"><small>Не является офертой.</small><br>
                <? if ($discount == NULL) { ?>
                    Стоимость указана без учета скидки. Размер скидки уточняйте на торговой точке.
                <? } ?>
                <br><br>
            </td>

        </tr>
        </tbody>
    </table>
    <? /*
	<a href="http://<?=$_SERVER['HTTP_HOST']?>"> <img src="http://<?=$_SERVER['HTTP_HOST']?>/images/bottom_pdf.png" width="720"/></a>

*/?>
    </body>
    </html>
    <?

    $html = ob_get_clean();

    return $html;
}
function _get_pricelist_pdf($type,$my_city)
{
    if($type=="int") {
        $tit = "Интерьерная коллекция";
        $type_id = 189;
    }
    if($type=="front") {
        $tit = "Фасадная коллекция";
        $type_id = 1562;
    }

// регион
    $loc = null;
    $arFilter = Array('IBLOCK_ID' => 7, 'ACTIVE' => 'Y', 'ID' => $my_city);
    $db_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter);
    $loc = $db_list->GetNextElement();
    if (!$loc) {
        $arFilter = Array('IBLOCK_ID' => 7, 'ACTIVE' => 'Y', 'CODE' => 'moskva');
        $db_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter);
        $loc = $db_list->GetNextElement();
    }
    $loc = array_merge($loc->GetFields(), $loc->GetProperties());

    $discreg = null;
    $arFilter = Array('IBLOCK_ID' => 8, 'ACTIVE' => 'Y', 'ID' => $loc['discountregion']['VALUE']);
    $db_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter);
    $discreg = $db_list->GetNextElement();
    $discreg = array_merge($discreg->GetFields(), $discreg->GetProperties());

    $products = array();

    $currency_infо = get_currency_info($loc['country']['VALUE']);
    $arFilter_element = $currency_infо['filter'];
    $curr = $currency_infо['curr'];
    $curr_abbr = $currency_infо['abbr'];


    $arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, 'SECTION_ID'=> $type_id, 'ACTIVE' => 'Y', 'INCLUDE_SUBSECTIONS' => 'Y', $arFilter_element);
    $_element = CIBlockElement::GetList(array('PROPERTY_ARTICUL'=>'ASC'), $arFilter);
    $n = 0;
    while ($item = $_element->GetNextElement()) {
        //if($n<100) {
        $item = array_merge($item->GetFields(), $item->GetProperties());
        if ($item['IBLOCK_SECTION_ID'] != 1587) //исключаем клей
            $products[] = array('ARTICUL'=>$item['ARTICUL']['VALUE'],'NAME'=>$item['NAME'],'FLEX'=>$item['FLEX']['VALUE'],'COMPOSITEPART'=>$item['COMPOSITEPART']['VALUE'],'PRICE'=>__get_product_cost($item));
        $n++;
        //}
    }

    ob_start();
    ?>
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style>
            @import url('https://fonts.googleapis.com/css?family=Roboto&subset=cyrillic');
            body {
                font-family: 'Roboto', sans-serif !important;
                padding: 40px 20px 0;
                -webkit-box-sizing: border-box;
                -moz-box-sizing: border-box;
                box-sizing: border-box;
            }
            .logo {
                max-width:40%;
                float: left;
            }
            .header-title {
                margin: 0 0 15px 15%;
                float:right;
            }
            h1 {
                text-transform: uppercase;
                text-align: left;
                font-size: 20px;
                line-height: 20px;
                color: #000;
                font-family: 'Roboto', sans-serif;
                font-weight: normal;
                margin-top: -5px;
                padding: 0;
                margin: 0;
                margin-bottom: 10px;
            }
            .pricelist-attr {
                text-align: left;
                font-size: 14px;
                line-height: 14px;
                padding: 0;
                margin: 0;
            }
            .date {
                margin-top: 10px;
            }
            .pricelist-table {
                border-collapse: collapse;
                width: 100%;
                color: #000;
                clear: both;
                font-size: 12px;
            }
            .pricelist-table th {
                font-family: 'Roboto Condensed', sans-serif;
                font-weight:normal;
            }
            .pricelist-table td, .pricelist-table th {
                height: 20px;
                vertical-align: middle;
                line-height: 12px;
            }
            .pricelist-table th {
                color: #849795;
            }
            .articul-th {
                width:15%;
                text-align: center;
                border-bottom: 1px solid #9c9c9d;
                border-right: 2px solid #9c9c9d;
            }
            .name-th {
                width:70%;
                padding-left: 4%;
                border-bottom: 1px solid #9c9c9d;
                border-right: 2px solid #9c9c9d;
            }
            .price-th {
                width:15%;
                padding-right: 1%;
                border-bottom: 1px solid #9c9c9d;
                text-align: right;
            }
            .articul {
                width:15%;
                text-align: center;
                border-bottom: 1px solid #9c9c9d;
                border-right: 2px solid #9c9c9d;
            }
            .name {
                width:70%;
                padding-left: 4%;
                border-bottom: 1px solid #9c9c9d;
                border-right: 2px solid #9c9c9d;
            }
            .price {
                width:15%;
                padding-right: 1%;
                border-bottom: 1px solid #9c9c9d;
                text-align: right;
            }
            .pricelist-table tr:last-child>td {
                border-bottom: none;
            }
            .pricelist-footer {
                position: absolute;
                bottom: 0;
                font-size: 10px;
                width: 100%;
                margin-bottom: 10px;
            }
            .footer-title {
                width: 33%;
            }
            .footer-title span {
                color: #9c9c9d;
            }
            .footer-addr {
                font-size: 10px;
                color: #9c9c9d;
                width: 30%;
            }
            .footer-site {
                width: 30%;
                text-align: center;
            }
            .footer-site a{
                font-size: 12px;
                color: #849795;
                text-decoration: none;
                text-align: center;
            }
            .footer-page {
                text-align: right;
                font-size: 12px;
                color: #000;
                width: 7%;
            }
        </style>
    </head>

    <body>
    <img src="<?=$_SERVER["DOCUMENT_ROOT"]?>/img/e-logo.jpg" class="logo">

    <div class="header-title">
        <h1>Прайс-лист<br><?=$tit?></h1>
        <p class="pricelist-attr"><?=$curr?></p>
        <p class="pricelist-attr"><?=$loc['NAME']?></p>
        <p class="pricelist-attr date"><?=date("Y/n")?></p>
    </div>
    <table class="pricelist-table">
        <tr>
            <th class='articul-th'>Артикул</th>
            <th class='name-th'>Наименование</th>
            <th class='price-th'>Цена, <?=$curr_abbr?></th>
        </tr>
        <?
        $prev_name = "";
        $i = 0;
        $n = 1;
        foreach($products as $product) {
        if($product['COMPOSITEPART'] == "") {
            //name
            if($product['FLEX'] == "Y") {
                $name = trim(str_replace("FLEX", "", $product['NAME']));
                $short_name = $name;
                $name .= " гибкий";
            }
            else {
                $name = ucfirst($product['NAME']);
                $short_name = $name;
            }



            if ($prev_name != $short_name && $i != 0) {
                echo "<tr><td class='articul'></td><td class='name'></td><td class='price'></td></tr>";
                $i++;
            }
            echo "<tr>";
            echo "<td class='articul'>".$product['ARTICUL']."</td>";
            echo "<td class='name'>".$name."</td>";
            echo "<td class='price'>".number_format($product['PRICE'],2,'.',' ')."</td>";
            echo '</tr>';
            $prev_name = $short_name;
            $i++;

        }
        if ($i > 34) {?>
    </table>
    <table class="pricelist-footer">
        <tr>
            <td class="footer-title">
                OOO "Декор"<br>
                <span>
                            тел./факс: <?=main_phone_number()?>
                        </span>
            </td>
            <td class="footer-addr">
                142350, Московская обл., Чеховский р-н,<br>
                дер. Ивачково, ул. Лесная, вл. 12, стр. 7
            </td>
            <td class="footer-site">
                <a href="https://perfom-decor.ru/" target="_blank">www.perfom-decor.ru</a>
            </td>
            <td class="footer-page">
                [<?=$n?>]
            </td>
        </tr>
    </table>
    <img src="<?=$_SERVER["DOCUMENT_ROOT"]?>/images/e-logo.jpg" class="logo">

    <div class="header-title">
        <h1>Прайс-лист<br><?=$tit?></h1>
        <p class="pricelist-attr"><?=$curr?></p>
        <p class="pricelist-attr"><span><?=$loc['NAME']?></p>
        <p class="pricelist-attr date"><?=date("Y/n")?></p>
    </div>

    <table class="pricelist-table">
        <tr>
            <th class='articul-th'>Артикул</th>
            <th class='name-th'>Наименование</th>
            <th class='price-th'>Цена, <?=$curr_abbr?></th>
        </tr>
        <? $i = 0; $n++; }
        }
        echo "</table>";
        ?>
        <table class="pricelist-footer">
            <tr>
                <td class="footer-title">
                    OOO "Декор"<br>
                    <span>
            тел./факс: <?=main_phone_number()?>
        </span>
                </td>
                <td class="footer-addr">
                    142350, Московская обл., Чеховский р-н,<br>
                    дер. Ивачково, ул. Лесная, вл. 12, стр. 7
                </td>
                <td class="footer-site">
                    <a href="https://perfom-decor.ru/" target="_blank">www.perfom-decor.ru</a>
                </td>
                <td class="footer-page">
                    [<?=$n?>]
                </td>
            </tr>
        </table>

    </body>
    </html>
    <?

    $html = ob_get_clean();

    return $html;
}
function _get_stat_pdf()
{
    $http_host_temp = explode(":",$_SERVER['HTTP_HOST']);
    $http_host_w = $_SERVER['HTTP_HOST'];
    $_SERVER['HTTP_HOST'] = $http_host_temp[0];
    $from = '';
    if(isset($_COOKIE['order_mod_date'])) {
        $sort_date = json_decode($_COOKIE['order_mod_date']);
        //print_r($sort_date);
        $sort_date_from = $sort_date->from;
        $new_sort_date_from = $sort_date_from;
        if($sort_date_from!='') {
            $from .= 'с '.$sort_date_from;
            $sort_date_from = explode('.',$sort_date_from);
            $sort_date_from = $sort_date_from[2].'-'.$sort_date_from[1].'-'.$sort_date_from[0];
        }
        $sort_date_to = $sort_date->to;
        $new_sort_date_to = $sort_date_to;
        if($sort_date_to!='') {
            $from .= ' по '.$sort_date_to;
            $sort_date_to = explode('.',$sort_date_to);
            $sort_date_to = $sort_date_to[2].'-'.$sort_date_to[1].'-'.$sort_date_to[0];
        }
        $sort_date_val = $sort_date->val;
    }
    else {
        //$sort_date_from = date('d.m.Y',time() - (6 * 24 * 60 * 60));
        $sort_date_from = date('Y-m-d',time() - (6 * 24 * 60 * 60));
        $sort_date_to = "";
        $sort_date_val = "3";
    }
    switch($sort_date_val) {
        case '1': {
            $period = 'все';
            break;
        };
        case '2': {
            $period = 'день ('.date("d.m.Y").')';
            $sort_date_from = date("Y-m-d");
            break;
        };
        case '3': {
            $period = 'неделя ('.date('d.m.Y',time() - (6 * 24 * 60 * 60)).' - '.date("d.m.Y").')';
            $sort_date_from = date('Y-m-d',time() - (6 * 24 * 60 * 60));
            break;
        };
        case '4': {
            $period = 'месяц ('.date('d.m.Y',strtotime("-1 month")).' - '.date("d.m.Y").')';
            $sort_date_from = date('Y-m-d',strtotime("-1 month"));
            break;
        };
        case '0': {
            $period = $from;
            break;
        };
    }
    $arFilter = Array("IBLOCK_CODE"=>"keep_order","ACTIVE"=>"Y");
    if($sort_date_from != "") {
        $arFilter['>=PROPERTY_DATE'] = $sort_date_from." 00:00:00";
    }

    if($sort_date_to != "") {
        $arFilter['<=PROPERTY_DATE'] = $sort_date_to." 23:59:59";
    }
    $sort_geo = json_decode($_COOKIE['filt_reg']);
    $filr_reg = 'все';
    if($sort_geo!='') {
        $resFiltReg = CIBlockElement::GetByID($sort_geo);
        $ar_filt_reg = $resFiltReg ->GetNext();
        $filr_reg = $ar_filt_reg['NAME'];
        $arFilter['PROPERTY_CHOOSEN_REG'] = $sort_geo;
    }

    $res = CIBlockElement::GetList(Array('id'=>'desc'),$arFilter,false, Array(), Array());
    $items = Array();
    while($item = $res->GetNextElement()) {
        $item = array_merge($item->GetFields(), $item->GetProperties());
        $items[] = $item;
    }
    $qty = $res->SelectedRowsCount();
    $mob = 0;
    $desc = 0;
    $total = 0;
    $middle_price = 0;
    $middle_qty = 0;
    $prod_arr = Array();
    $prod_qty = 0;
    foreach ($items as $item) {
        if($item['VERS']['VALUE']=='desktop') {
            $desc++;
        }
        if($item['VERS']['VALUE']=='mobile') {
            $mob++;
        }
        if($item['CURR']['VALUE']=='RUB') {
            $price = $item['TOTAL_SALE']['VALUE']!='' ? $item['TOTAL_SALE']['VALUE'] : $item['TOTAL']['VALUE'];
            $total += $price;
        } else {
            $subtotal = 0;
            $arFilterProd = Array("IBLOCK_CODE"=>"order_products","PROPERTY_ORDER_NUMBER"=>$item['NAME'],"ACTIVE"=>"Y");
            $resProd = CIBlockElement::GetList(Array(),$arFilterProd);
            while($prod = $resProd->GetNextElement()) {
                $prod = array_merge($prod->GetFields(), $prod->GetProperties());
                $total += $prod['QTY']['VALUE']*$prod['BASE_PRICE']['VALUE'];
                $subtotal += $prod['QTY']['VALUE']*$prod['BASE_PRICE']['VALUE'];
            }
        }
        $arFilterProd = Array("IBLOCK_CODE"=>"order_products","PROPERTY_ORDER_NUMBER"=>$item['NAME'],"ACTIVE"=>"Y");
        $resProd = CIBlockElement::GetList(Array(),$arFilterProd);
        $middle_qty += $resProd->SelectedRowsCount();
        while($prod = $resProd->GetNextElement()) {
            $prod = array_merge($prod->GetFields(), $prod->GetProperties());
            $prod_arr[$prod['NAME']] = $prod_arr[$prod['NAME']] + $prod['QTY']['VALUE'];
            $prod_qty += $prod['QTY']['VALUE'];
        }
    }
    arsort($prod_arr,SORT_NUMERIC);
    $middle_price = $total/$qty;
    $middle_qty = round($middle_qty/$qty);

    ob_start();
    ?>
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style>
            @import url('https://fonts.googleapis.com/css?family=Roboto&subset=cyrillic');
            body {
                font-family: 'Roboto', sans-serif !important;
                padding: 20px 20px 0;
                -webkit-box-sizing: border-box;
                -moz-box-sizing: border-box;
                box-sizing: border-box;
                color: #000;
            }
            .logo {
                max-width:270px;
                float: left;
            }
            .header-title {
                margin: 0 0 15px 15%;
                float: right;
                width: 500px;
            }
            h1 {
                text-transform: uppercase;
                text-align: right;
                font-size: 18px;
                line-height: 20px;
                color: #000;
                font-family: 'Roboto', sans-serif;
                font-weight: normal;
                margin-top: -5px;
                padding: 0;
                margin: 0;
                margin-bottom: 10px;
            }
            .pricelist-attr {
                text-align: left;
                font-size: 14px;
                line-height: 14px;
                padding: 0;
                margin: 0;
            }
            .date {
                margin-top: 5px;
            }
            .header-attr {
                margin-top: 15px;
                margin-bottom: 20px;
                clear: both;
            }
            .header-attr tr td:first-child {
                padding-right: 5px;
            }
            .pacc-nav {
                clear: both;
                margin-bottom: 30px;
                width: 100%;
                font-size: 14px;
            }
            .pacc-nav tr td {
                height: 40px;
                border-bottom: 1px dotted #a9a9a9;
            }
            .pacc-nav tr td:last-child {
                text-align: right;
                font-size: 16px;
            }
            .pacc-nav tr:first-child {
                border-bottom: 1px solid #849795;
                color: #849795;
                text-transform: uppercase;
            }
            .pacc-nav tr:first-child td {
                border-bottom: 1px solid #849795;
                font-family: 'Roboto', sans-serif;
                text-align: left;
            }
            .pacc-nav-bestsells {
                background-color: #e6e6e6;
                padding: 20px 35px;
                -webkit-border-radius: 5px;
                -moz-border-radius: 5px;
                border-radius: 5px;
            }
            .pacc-nav-bestsells table {
                width: 100%;
                font-size: 14px;
            }
            .pacc-nav-bestsells tr td {
                height: 30px;
                border-bottom: 1px dotted #000;
            }
            .pacc-nav-bestsells tr:last-child td {
                border-bottom: none;
            }
            .pacc-nav-bestsells tr td:last-child {
                text-align: right;
            }
            .pacc-nav-bestsells table a {
                color: #000;
                text-decoration: none;
            }
            .pacc-nav-bestsells-tit {
                margin-bottom: 20px;
            }
            .pricelist-footer {
                position: absolute;
                bottom: 0;
                font-size: 10px;
                width: 100%;
                margin-bottom: 10px;
            }
            .footer-title {
                width: 25%;
                color: #9c9c9d;
            }
            .footer-title span {
                color: #9c9c9d;
            }
            .footer-addr {
                font-size: 10px;
                color: #9c9c9d;
                width: 49%;
                text-align: center;
            }
            .footer-site {
                width: 25%;
                text-align: right;
            }
            .footer-site a{
                font-size: 12px;
                color: #849795;
                text-decoration: none;
                text-align: center;
            }
            .footer-page {
                text-align: right;
                font-size: 12px;
                color: #000;
                width: 7%;
            }
        </style>
    </head>

    <body>
    <img src="<?=$_SERVER["DOCUMENT_ROOT"]?>/img/e-logo-mail.png" class="logo">

    <div class="header-title">
        <h1>Статистика по заказам<br>от <?=date("d.m.Y")?></h1>
    </div>

    <table class="header-attr">
        <tr>
            <td class="pricelist-attr date">Отчетный период:</td>
            <td class="pricelist-attr date"><?=$period?></td>
        </tr>
        <tr>
            <td class="pricelist-attr date">Выбранный регион:</td>
            <td class="pricelist-attr date"><?=$filr_reg?></td>
        </tr>
    </table>

    <table class="pacc-nav">
        <tr>
            <td colspan="2">Статистика заказов</td>
        </tr>
        <tr>
            <td class="pacc-stat-lbl">Количество заказов</td>
            <td class="pacc-stat-val"><?=$qty?></td>
        </tr>
        <tr>
            <td class="pacc-stat-lbl">Общая сумма заказов</td>
            <td class="pacc-stat-val"><?=__cost_format($total,3109)?></td>
        </tr>
        <tr>
            <td class="pacc-stat-lbl">Средний чек по заказу</td>
            <? $middle_price = is_nan($middle_price) ? 0 : $middle_price ?>
            <td class="pacc-stat-val"><?=__cost_format($middle_price,3109)?></td>
        </tr>
        <tr>
            <td class="pacc-stat-lbl">Среднее количество позиций в заказе</td>
            <td class="pacc-stat-val"><?=$middle_qty?><</td>
        </tr>
        <tr>
            <td class="pacc-stat-lbl">Количество заказов с десктопа</td>
            <td class="pacc-stat-val"><?=$desc?></td>
        </tr>
        <tr>
            <td class="pacc-stat-lbl">Количество заказов с мобильной версии</td>
            <td class="pacc-stat-val"><?=$mob?></td>
        </tr>
    </table>



    <div class="pacc-nav-bestsells">
        <div class="pacc-nav-bestsells-tit">Самые продаваемые позиции:</div>
        <table>
            <?
            $n = 0;
            foreach ($prod_arr as $k=>$v) {
                $isSample = false;
                $itemId = $k;
                if(strpos($k,'s') !== false) {
                    $isSample = true;
                    $itemId = substr($k,1);
                }
                if($n++ < 10) {
                    $arFilterProd = Array("IBLOCK_ID"=>IB_CATALOGUE,"ID"=>$itemId,"ACTIVE"=>"Y");
                    $resProd = CIBlockElement::GetList(Array(),$arFilterProd);
                    if($product = $resProd->GetNextElement()) {
                        $product = array_merge($product->GetFields(), $product->GetProperties());?>
                        <tr class="pacc-nav-bestsells-item">
                            <td class="pacc-bestsells-lbl"><a href="https://perfom-decor.ru<?=__get_product_link($product)?>" target="_blank"><?=__get_product_name($product)?><?if($isSample) echo ' образец'?></a></td>
                            <td class="pacc-bestsells-val"><?=$v?> шт. (<?=round($v/$prod_qty*100,2)?>%)</td>
                        </tr>
                    <? }
                }
                else {
                    break;
                }
                ?>
            <? } ?>
        </table>
    </div>
    </section>



    <table class="pricelist-footer">
        <tr>
            <td class="footer-title">
                OOO "Декор"<br>
                <span>
        тел./факс: +7 495 315-31-10
        </span>
            </td>
            <td class="footer-addr">
                142350, Московская обл., городской округ Чехов,<br>
                дер. Ивачково, ул. Лесная, вл. 12, стр. 7
            </td>
            <td class="footer-site">
                <a href="https://perfom-decor.ru/" target="_blank">www.perfom-decor.ru</a>
            </td>
        </tr>
    </table>

    </body>
    </html>
    <?

    $html = ob_get_clean();

    return $html;
}
function _get_stat_dealer_pdf($id)
{
    $http_host_temp = explode(":",$_SERVER['HTTP_HOST']);
    $http_host_w = $_SERVER['HTTP_HOST'];
    $_SERVER['HTTP_HOST'] = $http_host_temp[0];
    $dealer_id = Array("LOGIC" => "OR");
    $dealer_points = Array();
    $from = '';

    $dealer_res = CIBlockElement::GetList(Array(),Array("IBLOCK_ID" => 6, "ACTIVE" => "Y", "PROPERTY_dealer_id" => $id),false, Array(), Array());
    while($dealer_item = $dealer_res->GetNextElement()) {
        $dealer_item = array_merge($dealer_item->GetFields(), $dealer_item->GetProperties());
        $dealer_id[] = Array("PROPERTY_ID_DEALER" => $dealer_item['ID']);
        $dealer_points[] = $dealer_item;
    }

    if(isset($_COOKIE['dealer_mod_date'])) {
        $sort_date = json_decode($_COOKIE['dealer_mod_date']);
        $sort_date_from = $sort_date->from;
        $new_sort_date_from = $sort_date_from;
        if($sort_date_from!='') {
            $sort_date_from = explode('.',$sort_date_from);
            $sort_date_from = $sort_date_from[2].'-'.$sort_date_from[1].'-'.$sort_date_from[0];
        }
        $sort_date_to = $sort_date->to;
        $new_sort_date_to = $sort_date_to;
        if($sort_date_to!='') {
            $sort_date_to = explode('.',$sort_date_to);
            $sort_date_to = $sort_date_to[2].'-'.$sort_date_to[1].'-'.$sort_date_to[0];
        }
        $sort_date_val = $sort_date->val;
        switch($sort_date_val) {
            case '1': {
                $period = 'все';
                break;
            };
            case '2': {
                $period = 'день ('.date("d.m.Y").')';
                $sort_date_from = date("Y-m-d");
                break;
            };
            case '3': {
                $period = 'неделя ('.date('d.m.Y',time() - (6 * 24 * 60 * 60)).' - '.date("d.m.Y").')';
                $sort_date_from = date('Y-m-d',time() - (6 * 24 * 60 * 60));
                break;
            };
            case '4': {
                $period = 'месяц ('.date('d.m.Y',strtotime("-1 month")).' - '.date("d.m.Y").')';
                $sort_date_from = date('Y-m-d',strtotime("-1 month"));
                break;
            };
            case '0': {
                $period = $from;
                break;
            };
        }
    }
    else {
        $sort_date_val = "1";
        $period = 'все';
    }
    $arFilter = Array("IBLOCK_CODE"=>"keep_order","ACTIVE"=>"Y");
    if($sort_date_from != "") {
        $arFilter['>=PROPERTY_DATE'] = $sort_date_from." 00:00:00";
    }

    if($sort_date_to != "") {
        $arFilter['<=PROPERTY_DATE'] = $sort_date_to." 23:59:59";
    }
    if($dealer_id != Array("LOGIC" => "OR")) {
        $arFilter[] = $dealer_id;
    } else {
        $arFilter['PROPERTY_ID_DEALER'] = 'no';
    }

    $res = CIBlockElement::GetList(Array('id'=>'desc'),$arFilter,false, Array(), Array());
    $items = Array();
    while($item = $res->GetNextElement()) {
        $item = array_merge($item->GetFields(), $item->GetProperties());
        $items[] = $item;
    }
    $qty = $res->SelectedRowsCount();
    $total = 0;
    $middle_price = 0;
    $middle_qty = 0;
    $prod_arr = Array();
    $prod_qty = 0;


    foreach ($items as $item) {
        $price = $item['TOTAL_SALE']['VALUE']!='' ? $item['TOTAL_SALE']['VALUE'] : $item['TOTAL']['VALUE'];
        $total += $price;
        $arFilterProd = Array("IBLOCK_CODE"=>"order_products","PROPERTY_ORDER_NUMBER"=>$item['NAME'],"ACTIVE"=>"Y");
        $resProd = CIBlockElement::GetList(Array(),$arFilterProd);
        $middle_qty += $resProd->SelectedRowsCount();
        while($prod = $resProd->GetNextElement()) {
            $prod = array_merge($prod->GetFields(), $prod->GetProperties());
            $prod_arr[$prod['NAME']] = $prod_arr[$prod['NAME']] + $prod['QTY']['VALUE'];
            $prod_qty += $prod['QTY']['VALUE'];
        }
        $reg = $item['CHOOSEN_REG']['VALUE'];
    }
    arsort($prod_arr,SORT_NUMERIC);
    $middle_price = $total/$qty;
    $middle_qty = round($middle_qty/$qty);

    ob_start();
    ?>
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style>
            @import url('https://fonts.googleapis.com/css?family=Roboto&subset=cyrillic');
            body {
                font-family: 'Roboto', sans-serif !important;
                padding: 20px 20px 0;
                -webkit-box-sizing: border-box;
                -moz-box-sizing: border-box;
                box-sizing: border-box;
                color: #4e4e4e;
            }
            .logo {
                max-width:40%;
                float: left;
            }
            .header-title {
                margin: 0 0 15px 15%;
                float: right;
                width: 500px;
            }
            h1 {
                text-transform: uppercase;
                text-align: right;
                font-size: 18px;
                line-height: 20px;
                color: #4e4e4e;
                font-family: 'Roboto', sans-serif;
                font-weight: normal;
                margin-top: -5px;
                padding: 0;
                margin: 0;
                margin-bottom: 10px;
            }
            .pricelist-attr {
                text-align: left;
                font-size: 14px;
                line-height: 14px;
                padding: 0;
                margin: 0;
            }
            .date {
                margin-top: 5px;
            }
            .header-attr {
                margin-top: 15px;
                margin-bottom: 20px;
                clear: both;
            }
            .header-attr tr td:first-child {
                padding-right: 5px;
            }
            .pacc-nav {
                clear: both;
                margin-bottom: 30px;
                width: 100%;
                font-size: 14px;
            }
            .pacc-nav tr td {
                height: 40px;
                border-bottom: 1px dotted #4e4e4e;
            }
            .pacc-nav tr td:last-child {
                text-align: right;
                font-size: 16px;
            }
            .pacc-nav tr:first-child {
                border-bottom: 1px solid #849795;
                color: #849795;
                text-transform: uppercase;
            }
            .pacc-nav tr:first-child td {
                border-bottom: 1px solid #849795;
                font-family: 'Roboto', sans-serif;
                text-align: left;
            }
            .pacc-nav-bestsells {
                background-color: #efefef;
                padding: 20px 35px;
            }
            .pacc-nav-bestsells table {
                width: 100%;
                font-size: 14px;
            }
            .pacc-nav-bestsells tr td {
                height: 30px;
                border-bottom: 1px dotted #4e4e4e;
            }
            .pacc-nav-bestsells tr:last-child td {
                border-bottom: none;
            }
            .pacc-nav-bestsells tr td:last-child {
                text-align: right;
            }
            .pacc-nav-bestsells table a {
                color: #4e4e4e;
                text-decoration: none;
            }
            .pacc-nav-bestsells-tit {
                margin-bottom: 20px;
            }
            .pricelist-footer {
                position: absolute;
                bottom: 0;
                font-size: 10px;
                width: 100%;
                margin-bottom: 10px;
            }
            .footer-title {
                width: 25%;
            }
            .footer-title span {
                color: #9c9c9d;
            }
            .footer-addr {
                font-size: 10px;
                color: #9c9c9d;
                width: 49%;
                text-align: center;
            }
            .footer-site {
                width: 25%;
                text-align: right;
            }
            .footer-site a{
                font-size: 12px;
                color: #849795;
                text-decoration: none;
                text-align: center;
            }
            .footer-page {
                text-align: right;
                font-size: 12px;
                color: #000;
                width: 7%;
            }
        </style>
    </head>

    <body>
    <img src="<?=$_SERVER["DOCUMENT_ROOT"]?>/images/e-logo.jpg" class="logo">

    <div class="header-title">
        <h1>Статистика по заказам<br>от <?=date("d.m.Y")?></h1>
    </div>

    <table class="header-attr">
        <tr>
            <td class="pricelist-attr date">Отчетный период:</td>
            <td class="pricelist-attr date"><?=$period?></td>
        </tr>
    </table>

    <table class="pacc-nav">
        <tr>
            <td colspan="2">Статистика заказов</td>
        </tr>
        <tr>
            <td class="pacc-stat-lbl">Количество заказов</td>
            <td class="pacc-stat-val"><?=$qty?></td>
        </tr>
        <tr>
            <td class="pacc-stat-lbl">Общая сумма заказов</td>
            <td class="pacc-stat-val"><?=__cost_format($total,$reg)?></td>
        </tr>
        <tr>
            <td class="pacc-stat-lbl">Средний чек по заказу</td>
            <? $middle_price = is_nan($middle_price) ? 0 : $middle_price ?>
            <td class="pacc-stat-val"><?=__cost_format($middle_price,$reg)?></td>
        </tr>
        <tr>
            <td class="pacc-stat-lbl">Среднее количество позиций в заказе</td>
            <td class="pacc-stat-val"><?=$middle_qty?><</td>
        </tr>
    </table>



    <div class="pacc-nav-bestsells">
        <div class="pacc-nav-bestsells-tit">Самые продаваемые позиции:</div>
        <table>
            <?
            $n = 0;
            foreach ($prod_arr as $k=>$v) {
                if($n++ < 10) {
                    $arFilterProd = Array("IBLOCK_ID"=>IB_CATALOGUE,"ID"=>$k,"ACTIVE"=>"Y");
                    $resProd = CIBlockElement::GetList(Array(),$arFilterProd);
                    if($product = $resProd->GetNextElement()) {
                        $product = array_merge($product->GetFields(), $product->GetProperties());?>
                        <tr class="pacc-nav-bestsells-item">
                            <td class="pacc-bestsells-lbl"><a href="https://perfom-decor.ru<?=__get_product_link($product)?>" target="_blank"><?=__get_product_name($product)?></a></td>
                            <td class="pacc-bestsells-val"><?=$v?> шт. (<?=round($v/$prod_qty*100,2)?>%)</td>
                        </tr>
                    <? }
                }
                else {
                    break;
                }
                ?>
            <? } ?>
        </table>
    </div>
    </section>



    <table class="pricelist-footer">
        <tr>
            <td class="footer-title">
                OOO "Декор"<br>
                <span>
        тел./факс: +7 495 789-62-70
        </span>
            </td>
            <td class="footer-addr">
                142350, Московская обл., городской округ Чехов,<br>
                дер. Ивачково, ул. Лесная, вл. 12, стр. 7
            </td>
            <td class="footer-site">
                <a href="https://perfom-decor.ru/" target="_blank">www.perfom-decor.ru</a>
            </td>
        </tr>
    </table>

    </body>
    </html>
    <?

    $html = ob_get_clean();

    return $html;
}


$city_loc_id = array(
                '3196'  => 'spb.perfom-decor.ru',
                '3331'  => 'lipetsk.perfom-decor.ru',
                
                );

function _get_city_loc($id){
    global $city_loc_id;
    $result = isset($city_loc_id[$id]) ? $city_loc_id[$id] : null;
    return $result;
}