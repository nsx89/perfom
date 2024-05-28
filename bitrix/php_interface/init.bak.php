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
    $wishlist = json_decode($user['PERSONAL_NOTES']);
} else {
    $wishlist = json_decode($_COOKIE['favorite']);
}


$res = CIBlockElement::GetByID($product['ID']);
if($arRes = $res->Fetch()) {
    $res = CIBlockSection::GetByID($arRes["IBLOCK_SECTION_ID"]);
    if($arRes = $res->Fetch()) {
        $section_id = $arRes["ID"];
        $arFilter = Array('IBLOCK_ID'=>12, 'ACTIVE'=>'Y', 'ID'=>$section_id);
        $db_list = CIBlockSection::GetList(Array(), $arFilter, false, array('UF_*'));
        $last_section = $db_list->GetNext();
    }
}
if ($last_section['UF_H'] == 1) {
    $signTmp = ' e-new-catalogue-h';
} elseif ($last_section['UF_V'] == 1) {
    $signTmp = ' e-new-catalogue-v';
} else {
    $signTmp = '';
}
$uri = __get_product_link($product,$test);
//$uri = __get_product_link($product);
$cost = __get_product_cost($product);
$iscomp = 0;
if ($product['COMPOSITEPART']['VALUE']) $iscomp = 1;
$flx_class = '';
if($is_flex) $flx_class = ' e-new-catalogue-flex';
$article_foil = array('6.50.711', '6.50.712', '6.50.713', '6.50.714', '6.50.715', '6.50.716', '6.50.719', '6.51.710', );
ob_start(); ?>

<div <?if($is_gallery) echo 'id="item'.$is_gallery.'"';?>class="e-new-catalogue-item<?=$signTmp?><?=flexTmp($product)?><?=$flx_class?>"
     data-id="<?=$product['ID']?>"
     data-name="<?=__get_product_name($product)?>"
     data-code="<?=$product['INNERCODE']['VALUE']?>"
     data-price="<?=_makeprice(CPrice::GetBasePrice($product['ID']))['PRICE'];?>"
     data-currency="<?=getCurrency($my_city)?>"
     data-cat="<?=$section_id?>"
     data-cat-name="<?=$last_section['NAME']?>"
     data-iscomp="<?=$iscomp?>"
    <?if($calculate) echo ' data-articul="'.$product['ARTICUL']['VALUE'].'"'?>
    <?if($product['MAURITANIA_SPECIAL']['VALUE']=='Y') echo ' data-maur-spec="1"'?>>
    <?if(!$calculate && $product['COMING_SOON']['VALUE']=='N') {?><a href="<?=$uri?>"><? } ?>
        <?$datetime = date('d.m.Y H:i:s');
        $dateproduct = date($DB->DateFormatToPHP($product['DATE_CREATE']));
        $sub_date = ceil((strtotime($datetime)-strtotime($dateproduct))/86400);

        if ((($sub_date < 150) && ($product['TIME_NEW_OFF']['VALUE'] == "N")) && !$calculate || ($product['NEW_ON']['VALUE'] == "Y") && !$calculate) {
            if($product['ARTICUL']['VALUE'] != 'EB05.M.290' && $product['ARTICUL']['VALUE'] != 'EB06.S.80') {
            ?>
            <div class="new-prod">новинка</div>
        <? } } ?>
        <?if(!$calculate) {?>
        <div class="e-new-item-buttons">
            <?if($product['NO_ORDER']['VALUE'] == 'N' && $cost && $product['COMING_SOON']['VALUE']=='N') {?>
                <?if(($product['OUT_OF_STOCK']['VALUE'] == 'Y' && $my_city == '3109')) { // || ($product['NEW_ART_DECO']['VALUE'] == 'Y')?>
                    <div>
                        <p class="no-sale">Товар недоступен для заказа</p>
                        <p class="add-np-sale">Товар будет доступен с 04.04.2023</p>
                    </div>
                <? } else { ?>
                <div class="one-click-btn" data-type="one-click">купить в&nbsp;1&nbsp;клик</div>
                <?/* if($product['HAS_SAMPLE']['VALUE'] == 'Y') { ?>
                        <div class="sample-add-wrap">
                <div class="one-click-btn" data-type="buy-sample">купить образец</div>
                        <div class="sample-add-tooltip sample-add-tooltip-preview">
                            <div class="sample-add-tooltip-wrap">
                                <div class="sample-add-txt">Купить образец:</div>
                                <div class="sample-add-option">Длина: 250 мм</div>
                                <div class="sample-add-option">Цена: 100 RUB</div>
                            </div>
                        </div>
                    </div>
                <? } else { ?>
                        <div></div>
                <? } */?>
                    <div class="item-small-btns">
                        <i class="new-icomoon icon-like<?if(in_array($product['ID'],$wishlist)) echo ' active'?>" data-type="favorite" data-user="<?=in_array(5,$user_group_arr ) ? 'user' : 'no-user'?>" title="Добавить в избранное"></i>
                        <div class="item-buy-wrap">
                            <div class="e-new-item-buy<?=(in_cart($product['ID']))?' active':''?>" data-type="cart-add">
                                <i class="new-icomoon icon-cart"></i>
                            </div>
                            <?/*<div class="item-add-tooltip">
                            <div class="item-add-tooltip-wrap">
                                <div class="item-add-txt">Купить товар</div>
                            </div>
                        </div>*/?>
                        </div>
                    </div>
                <? } ?>
            <? } elseif($product['COMING_SOON']['VALUE']=='Y') { ?>
                <p class="coming-soon" style="color: #fe5000;">Скоро в продаже</p>
            <? } else { ?>
                <p class="no-sale">Товар недоступен для заказа</p>
            <? } ?>
        </div>
      <? } ?>
        <div class="e-new-item-add-mess">Товар добавлен в&nbsp;корзину</div>
        <div class="e-new-item-img">
            <?
            $web_path = web_path($product);
            $img_path = get_resized_img($web_path,287,287);
            if($img_path == '' || !$img_path) $img_path = $web_path;
            ?>
            <img src="<?=$img_path?>" alt="">
        </div>
        <div class="e-new-item-info">
            <div class="e-new-item-info-top">
                <div class="e-new-item-main">
                    <span class="e-new-item-title">
                        <?=__get_product_name($product)?>
                        <? if (in_array($product['ARTICUL']['VALUE'], $article_foil)) { ?>
                          <i class="new-icomoon icon-light"></i>
                        <? } ?>
                    </span>
                    <? if($cost) { ?>
                      <span class="e-new-item-price"><?=__cost_format($cost)?></span>
                    <? } ?>
                </div>
            </div>
            <div class="e-new-item-info-bottom">
                <? $res = item_param($product);
                $res_param = array_merge($res['res_s'],$res['res_f']);
                if($is_flex && $is_flex == 'flex') $res_param = $res['res_f'];
                if (count($res_param)) {
                    foreach ($res_param as $name => $pitem) { ?>
                      <div class="e-new-item-desc">
                        <span><?=$name?></span>
                        <span><?=$pitem?></span>
                      </div>
                <? }} ?>
            </div>
        </div>
      <? if($is_gallery) { ?>
      <div class="element-number">Элемент в интерьере <div class="element-number-item"><span><?=$is_gallery?></span></div></div>
      <? } ?>
        <?if(!$calculate) {?></a><? } ?>
</div>
<?
  $html = ob_get_clean();
  return($html);
}

function web_path($ob_web_path) {
    $images_path = $_SERVER["DOCUMENT_ROOT"]."/cron/catalog/data/images";
    $images_web_path = "/cron/catalog/data/images";
    $images_path_new = $_SERVER["DOCUMENT_ROOT"]."/cron_responsive/catalog/data/images";
    $images_web_path_new = "/cron_responsive/catalog/data/images";

    if ($ob_web_path['FLEX']['VALUE'] == 'Y') {
        $path = $images_path_new."/600/".$ob_web_path['ARTICUL']['VALUE'].'.600.png';
        $web_path = $images_web_path_new."/600/".$ob_web_path['ARTICUL']['VALUE'].'.600.png';
        if (!file_exists($path)) { // подтянуть старый контент
            $path = $images_path."/60/".$ob_web_path['ARTICUL']['VALUE'].'.60.png';
            $web_path = $images_web_path."/60/".$ob_web_path['ARTICUL']['VALUE'].'.60.png';
            if (!file_exists($path)) { // заглушка
                $path = $images_path."/nope.jpg";
                $web_path = $images_web_path."/nope.jpg";
            }
        }
    } else {
        $path = $images_path_new."/100/".$ob_web_path['ARTICUL']['VALUE'].'.100.png';
        $web_path = $images_web_path_new."/100/".$ob_web_path['ARTICUL']['VALUE'].'.100.png';
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
        	$arFilter = Array('IBLOCK_ID'=>12, 'ID'=>$comp_item);
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
function __get_product_name($product, $iname = true, $altoff = false) {
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
            $name .= " ".$product['ARTICUL']['VALUE'];
            $name .= " гибкий";
        } else {
            $name = $iname?$product['NAME']:'';
            $name .= " ".$product['ARTICUL']['VALUE'];
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
    $images_path_new = $_SERVER["DOCUMENT_ROOT"]."/cron_responsive/catalog/data/images";
    $images_web_path_new = "/cron_responsive/catalog/data/images";

    $files = array();

    
    if($product['FLEX']['VALUE'] == "N") {
        $path = $images_path_new."/100/".$product['ARTICUL']['VALUE'].'.100.png';
        $web_path = $images_web_path_new."/100/".$product['ARTICUL']['VALUE'].'.100.png';


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
        $path = $images_path_new."/600/".$product['ARTICUL']['VALUE'].'.600.png';
        $web_path = $images_web_path_new."/600/".$product['ARTICUL']['VALUE'].'.600.png';


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
    $arFilter = Array('IBLOCK_ID' => 12, 'ACTIVE' => 'Y', 'ID' => $item['IBLOCK_SECTION_ID']);
    $db_list = CIBlockSection::GetList(Array("SORT" => "ASC"), $arFilter, false, array('UF_*'));
    $section = $db_list->GetNext();
    //обратное дерево
    $current = $section;
    $res = array($current['NAME']);
    $resall = array($current);
    $sections = array($current['CODE']);
    while ($current && $current['IBLOCK_SECTION_ID']) {
        $arFilter = Array('IBLOCK_ID' => 12, 'GLOBAL_ACTIVE' => 'Y', 'ID' => $current['IBLOCK_SECTION_ID']);
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
    $blockid = 12;
    $current = array('IBLOCK_SECTION_ID'=>$item['IBLOCK_SECTION_ID']);
    $res = array();
    $res_name = array();
    while ($current && $current['IBLOCK_SECTION_ID']) {
        $arFilter = Array('IBLOCK_ID' => $blockid, 'GLOBAL_ACTIVE' => 'Y', 'ID' => $current['IBLOCK_SECTION_ID']);
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
global $iblockid;
$iblockid = 12;
$cart = json_decode($_COOKIE['basket']);
$total = 0;
	foreach ($cart as $citem) {
        if(strpos($citem->id,'s') !== false) continue;
        $arFilter = Array('IBLOCK_ID' => $iblockid, 'ACTIVE' => 'Y', 'ID' => $citem->id);
        $db_list = CIBlockElement::GetList(Array("SORT" => "ASC"), $arFilter);
        $ob = $db_list->GetNextElement();
        if (!$ob) continue;
	$ob = array_merge($ob->GetFields(), $ob->GetProperties());
		if ($ob['COMPOSITEPART']['VALUE']) { 
		$ids = $ob['COMPOSITEPART']['VALUE'];
        	$ids['LOGIC'] = 'OR';
        	$arFilter = Array('IBLOCK_ID' => $iblockid, 'ACTIVE' => 'Y', 'ID' => $ids);
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
    global $iblockid;
    $iblockid = 12;
    $cart = json_decode($_COOKIE['basket']);
    if($prod) $cart = $prod;
    $total = 0;
    foreach ($cart as $citem) {
        if(strpos($citem->id,'s') !== false) continue;
        $arFilter = Array('IBLOCK_ID' => $iblockid, 'ACTIVE' => 'Y', 'ID' => $citem->id);
        $db_list = CIBlockElement::GetList(Array("SORT" => "ASC"), $arFilter);
        $ob = $db_list->GetNextElement();
        if (!$ob) continue;
        $ob = array_merge($ob->GetFields(), $ob->GetProperties());
        if ($ob['COMPOSITEPART']['VALUE']) {
            $ids = $ob['COMPOSITEPART']['VALUE'];
            $ids['LOGIC'] = 'OR';
            $arFilter = Array('IBLOCK_ID' => $iblockid, 'ACTIVE' => 'Y', 'ID' => $ids);
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
  
    $arFilter_Item = Array('IBLOCK_ID' => 12, 'ID' => $price['PRODUCT_ID']);
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
function get_glue_arr() {
    $arr = Array();

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
	} elseif($country == 3670) { //Узбекистан
        $arr = Array($m_290,$s_smp_290,$s_smp_60,$s_60,$b_m_290);
    } elseif($country == 3296 || $country == 3726 || $country == 3840 || $country == 6347 || $country == 3250 || $country == 3241) { // Молдова, Прибалтика (Латвия, Литва, Эстония), Украина, Беларусь
        $arr = Array($m_290,$s_290,$s_60);
    } else { //Россия (регионы), Абхазия
        $arr = Array($m_290,$u_290,$s_290,$s_60,$s_smp_290,$s_smp_60); // $s_290,$s_60
    }

    return $arr;
}
?>