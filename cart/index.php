<?
/*ini_set('display_errors', 1);
error_reporting(E_ALL);*/
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
$APPLICATION->SetTitle("Корзина");
$APPLICATION->SetPageProperty("description", "Европласт - производство полиуретановых изделий, лидер на российском рынке");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}
require($_SERVER["DOCUMENT_ROOT"] . "/cart/mounting/mounting_data.php");
require($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");
global $my_city;


if ($my_city == NULL) $my_city = $APPLICATION->get_cookie('my_city');
if($my_city == '3109') {
    $new_cart = array();
    $cart = json_decode($_COOKIE['basket']);
    foreach ($cart as $citem) {
        $citemId = $citem->id;
        $isSample = false;
        if(strpos($citem->id,'s') !== false) {
            $citemId = substr($citem->id, 1);
            $isSample = true;
        }
        $arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, 'ACTIVE' => 'Y', 'ID' => $citemId);
        $db_list = CIBlockElement::GetList(Array("SORT" => "ASC"), $arFilter);
        $ob = $db_list->GetNextElement();
        if (!$ob) continue;
        $ob = array_merge($ob->GetFields(), $ob->GetProperties());
        if($isSample && $ob['HAS_SAMPLE']['VALUE']!='Y') continue;
        if($ob['OUT_OF_STOCK']['VALUE']!='Y') $new_cart[] = $citem;
    }
    if(count($new_cart)!=count($cart)) {
        $new_cart = json_encode($new_cart);
        setcookie("basket", $new_cart,time()+60*60*24*BASKET_EXPIRES,'/','.'.$_SERVER['HTTP_HOST']);
        LocalRedirect('index.php');
    }
} else {
    $new_cart = array();
    $cart = json_decode($_COOKIE['basket']);
    foreach ($cart as $citem) {
        if(strpos($citem->id,'s') === false) {
            $new_cart[] = $citem;
        }
    }
    if(count($new_cart)!=count($cart)) {
        $new_cart = json_encode($new_cart);
        setcookie("basket", $new_cart,time()+60*60*24*BASKET_EXPIRES,'/','.'.$_SERVER['HTTP_HOST']);
        LocalRedirect('index.php');
    }
}
$cart = getObjectItems();
$money = $cart['sum'];
$cart = $cart['items'];

if(isset($_COOKIE['mount'])) {
    setcookie("calc", null,-1,'/',$_SERVER['HTTP_HOST']);
}


//клей
$glue_arr = get_glue_arr();

$adh_arr = array();
$arFilterAdh = Array("IBLOCK_ID"=>12,"ACTIVE"=>"Y","ID"=>get_glue_arr());
$ar_res_adh = CIBlockElement::GetList(Array(),$arFilterAdh,false,Array(),Array());
while ($ob = $ar_res_adh->GetNextElement()) {
    $adh = array_merge($ob->GetFields(), $ob->GetProperties());
    $cost = _makeprice(CPrice::GetBasePrice($adh));
    $adh['price'] = $cost['PRICE'];
    $adh['COUNT'] = 0;
    $adh = __get_product_images_new($adh);
    $adh_arr[] = $adh;
}

$new_cart = array();
$new_cart_id = array();
//убираем клей, который не должен светиться в данном регионе
foreach($cart as $citem) {
    if($citem['IBLOCK_SECTION_ID'] != 1587 || $citem['IBLOCK_SECTION_ID'] == 1587 && in_array($citem['ID'],$glue_arr)) {
        $new_cart[] = $citem;
        $new_cart_id[] = $citem['ID'];
    }
}
if(count($new_cart)!=count($cart)) {
    $basket = json_decode($_COOKIE['basket']);
    $new_basket = array();
    foreach($basket as $bitem) {
        if(in_array($bitem->id,$new_cart_id)) $new_basket[] = $bitem;
    }
    $new_basket = json_encode($new_basket);
    setcookie("basket", $new_basket,time()+60*60*24*BASKET_EXPIRES,'/','.'.$_SERVER['HTTP_HOST']);
    LocalRedirect('index.php');
}

$index_correct = 0;
$adh_in_cart = false;
foreach($cart as $k=>$item) {
    $citemId = $item['ID'];
    $isSample = false;
    if(strpos($item['ID'],'s') !== false) {
        $citemId = substr($item['ID'], 1);
        $isSample = true;
    }
    if(in_array($item['ID'],$glue_arr)) {
        foreach($adh_arr as $adh_k=>$adh) {
            if($item['ID'] == $adh['ID']) {
                $adh_in_cart = true;
                $adh_arr[$adh_k]['COUNT'] = $item['COUNT'];
                array_splice($cart,$k-$index_correct,1);
                $index_correct++;
            }
        }
    }
}
/*убираем из массива стыковочный 270 PU, если он не положен пользователем в корзину */
foreach($adh_arr as $k=>$adh) {
    if($adh['ID'] == 158449 && $adh['COUNT'] == 0) {
        array_splice($adh_arr,$k,1);
    }
}
/*убираем из массива стыковочный 270 SMP, если он не положен пользователем в корзину */
foreach($adh_arr as $k=>$adh) {
    if($adh['ID'] == 6107 && $adh['COUNT'] == 0) {
        array_splice($adh_arr,$k,1);
    }
}
/*убираем из массива стыковочный 60 SMP, если он не положен пользователем в корзину */
foreach($adh_arr as $k=>$adh) {
    if($adh['ID'] == 6429 && $adh['COUNT'] == 0) {
        array_splice($adh_arr,$k,1);
    }
}



if($_COOKIE['mount']) $mount = $_COOKIE['mount'];
$mount_list = getMountList($mount);
if(count($cart) <= 0 || $mount_list['total'] == 0) {
    setcookie("mount",null,-1,'/', $_SERVER['HTTP_HOST']);
}
if(isset($_COOKIE['mount']) && $mount_list['total'] > 0 || count($cart) <= 0) {
    setcookie("calc", null,-1,'/',$_SERVER['HTTP_HOST']);
}

function getCartPrev($citem, $isSample = false, $isAdh = false) {
    global $my_city;

    $arFilter = Array('IBLOCK_ID'=>IB_CATALOGUE, 'ACTIVE'=>'Y', 'ID'=>$citem["IBLOCK_SECTION_ID"]);
    $db_list = CIBlockSection::GetList(Array(), $arFilter, false, array('UF_*'));
    $last_section = $db_list->GetNext();

    $data_maur = false;
    $data_maur_4 = false;
    if($citem['MAURITANIA']['VALUE'] == 'Y' && $last_section['NAME'] == 'карнизы' || $citem['MAURITANIA']['VALUE'] == 'Y' && $last_section['NAME'] == 'плинтусы' || $citem['MAURITANIA']['VALUE'] == 'Y' && $last_section['NAME'] == 'молдинги') {
        $data_maur = true;
    }
    if($citem['ARTICUL']['VALUE'] == '1.59.503') {
        $data_maur_4 = true;
    }
    $cost = __get_product_cost($citem);
    if($isSample) $cost = $citem['SAMPLE_PRICE']['VALUE'] == '' ? DEFAULT_SAMPLE_PRICE : $citem['SAMPLE_PRICE']['VALUE'];
    if($isAdh && !$cost) return;
    $res = item_param($citem);
    $res_param = array_merge($res['res_s'],$res['res_f']);
    ?>
    <div class="cart-item<?if($isAdh || !count($res_param)) echo ' cart-item-adh'?>"
         <?if($isAdh) { ?>
             data-type="adh"
         <? } ?>
         data-id="<?=$citem['ID']?>"
         data-cost="<?=$cost?>"
         data-curr="<?=getCurrency($my_city)?>"
         data-name="<?=__get_product_name($citem)?>"
         data-code="<?=$citem['INNERCODE']['VALUE']?>"
         data-price="<?=_makeprice(CPrice::GetBasePrice($citem['ID']))['PRICE'];?>"
         data-cat-name="<?=$last_section['NAME']?>"
         <?if($citem['MOUNT_COST']['VALUE'] != '') { ?>data-m-price="<?=$citem['MOUNT_COST']['VALUE']?>"<? } ?>
        <?if($citem['MAURITANIA_SPECIAL']['VALUE']=='Y') echo ' data-maur-spec="1"'?><?if($data_maur) echo ' data-maur="1"'?><?if($data_maur_4) echo ' data-maur="4"'?> data-qty="<?=$citem['COUNT']?>">
        <div class="cart-item-img">
            <?if($isSample) { ?>
                <div class="sample-bg"></div>
            <? } ?>
            <a href="<?=___get_product_url($citem)?>" title="Перейти на страницу товара"></a>
            <img src="<?=$citem['FILES_IMAGES'][0]?>" alt="<?=__get_product_name($citem)?>">
        </div>
        <div class="cart-item-info">
            <h2>
                <span>
                    <?=__get_product_name($citem,true,false,false)?>
                    <?if($isSample) { ?>
                        <b>образец</b>
                    <? } ?>
                </span>
                <span class="cart-item-articul"><?=$citem['ARTICUL']['VALUE']?></span>
            </h2>
            <? if (count($res_param)) { ?>
                <div class="cart-item-params">
                    <? foreach ($res_param as $name => $pitem) { ?>
                        <div class="cart-item-desc">
                            <span><?=$name?></span>
                            <?if($isSample && $name == 'Длина детали') { ?>
                                <span>250 мм</span>
                            <? } else { ?>
                                <span><?=$pitem?></span>
                            <? } ?>
                        </div>
                    <? } ?>
                </div>
            <? } ?>

            <div class="cart-item-btns">
                <div class="prod-price">
                    <span class="cart-item-btn-tit">Цена</span>
                    <span class="prod-total" data-type="prod-total"><?=__cost_format($cost)?></span>
                </div>
                <div class="cart-item-info-qty">
                    <span class="cart-item-btn-tit">Количество:</span>
                    <?if($isSample) { ?>
                        <div class="prod-qty" style="pointer-events:none;">
                            <input type="text" value="<?=$citem['COUNT']?>" data-min="1" data-type="prod-page-qty">
                        </div>
                    <? } else { ?>
                        <div class="prod-qty">
                            <div class="prod-minus" data-type="prod-page-minus">-</div>
                            <input type="text" value="<?=$citem['COUNT']?>" data-min="1" data-type="prod-page-qty">
                            <div class="prod-plus" data-type="prod-page-plus">+</div>
                        </div>
                    <? } ?>
                </div>
            </div>
        </div>
        <i class="new-icomoon icon-delete" data-type="remove-item">
            <a title="Удалить"></a>
        </i>
    </div>
<?}
?>

<div class="content-wrapper cart">
    <div class="cart-title">
        <div class="cart-title-name">Корзина <span data-type="cart-qty"><?=$cart_qty?></span></div>
        <div class="cart-clear-all" data-type="clear-all">Очистить корзину</div>
    </div>
    <div class="cart-wrapper">
        <div class="cart-items">
    <?
    $sampleArr = Array();
    foreach ($cart as $citem) {
        if($citem['prodId'] != '') {
            $sampleArr[] = $citem;
            continue;
        }
        echo getCartPrev($citem);
    } ?>


    <?if(count($sampleArr) > 0 && $loc['ID'] == 3109) {
        foreach($sampleArr as $citem) {
            echo getCartPrev($citem, true, false);
    } } ?>

    <div class="cart-items-adh" data-type="adh-wrapper">
        <div class="cart-items-adh-title">
            <h3>Монтажные материалы</h3>
            <div class="adh-count-note">*Автоматически рассчитывает необходимое количество&nbsp;клея</div>
            <div class="adh-count-btn" data-type="adh-qty">Рассчитать количество*</div>
            <div class="adh-count-wait" data-type="adh-qty-wait"><img src="/img/preloader.gif" alt="wait..."></div>

        </div>
        <? foreach($adh_arr as $adh){
            echo getCartPrev($adh, false, true);
        }
        ?>
    </div>

    </div>
        <div class="cart-forms">
            <div data-type="order-fixed" class="order-fixed">
                <div class="cart-order" data-type="cart-order">
                    <div class="cart-order-first-step" data-type="order-first-step">
                        <div class="cart-order-main">
                            <? if($loc['ID'] != 3109) { ?>
                                <div class="cart-order-sum">
                                    <div class="cart-order-title">ваш заказ</div>
                                    <div class="cart-order-total-reg">
                                        <span class="cart-order-name no-sale">Итого:</span>
                                        <span data-type="total" data-without-del="0">0 RUB</span>
                                    </div>
                                    <div class="go-to-check-out-btn" data-type="go-to-check-out">Перейти к оформлению</div>
                                </div>
                                <div class="del-title-reg">Условия получения</div>
                                <div class="del-condition-reg">
                                    условия получения уточнит представитель Европласт в&nbsp;вашем регионе после&nbsp;обработки заказа.
                                </div>
                            <? } else {?>
                                <div class="cart-order-sum">
                                    <div class="cart-order-title">ваш заказ</div>
                                    <div class="cart-order-price no-hide">
                                        <span class="cart-order-name">Всего товаров на сумму:</span>
                                        <span class="cart-order-val" data-type="sum">0 RUB</span>
                                    </div>
                                    <div class="cart-order-price">
                                        <span class="cart-order-name">Ваша скидка - %</span>
                                        <span class="cart-order-val" data-type="discount">0%</span>
                                    </div>
                                    <div class="cart-order-price">
                                        <span class="cart-order-name">Сумма скидки:</span>
                                        <span class="cart-order-val" data-type="discount-sum">0 RUB</span>
                                    </div>
                                    <div class="cart-order-price" data-type="cart-order-del">
                                        <span class="cart-order-name">Стоимость доставки:</span>
                                        <span class="cart-order-val" data-type="delivery-sum" data-val="0">0 RUB</span>
                                    </div>
                                    <div class="cart-order-price no-hide cart-order-total">
                                        <span class="cart-order-name with-sale">Итого, с учетом скидки:</span>
                                        <span class="cart-order-name no-sale">Итого:</span>
                                        <span class="cart-order-val" data-type="total" data-without-del="0">0 RUB</span>
                                    </div>
                                    <div class="go-to-check-out-btn" data-type="go-to-check-out">Перейти к оформлению</div>
                                </div>
                            <?/*<div class="cart-mount-wrap cart-point" data-type="cart-point">
                                    <div class="cart-form-point" data-type="cart-point-name">
                                        1. Монтажные материалы
                                    </div>
                                    <div class="cart-form-point-wrap" data-type="cart-point-cont">*/?>
                                        <?if($mount !== undefined && $mount_list['total'] > 0) { ?>
                                            <div class="mount-calc-amount">предварительная стоимость монтажа - <span data-type="cart-mount-total"><?=__cost_format($mount_list['total'])?></span></div>
                                            <div class="att-mount-amount" data-type="mount-warn">Внимание! Вы произвели предварительный расчет монтажа до&nbsp;того, как&nbsp;была изменена ваша&nbsp;корзина. Если вы хотите, чтобы ассортимент и/или&nbsp;количество товара совпадали с&nbsp;актуальной корзиной, произведите расчет&nbsp;повторно.</div>
                                        <? } ?>
                                        <div class="mount-btns">
                                            <a class="mount-btn go-to-mount" href="/cart/mounting/" title="Перейти">Калькулятор стоимости монтажа <i class="icomoon icon-angle-right"></i></a>
                                            <div class="cart-mount-rbtn" data-type="mounting">
                                                Получить расчет стоимости монтажа специалистом
                                            </div>
                                        </div>
                                        <div class="mount-btns-desc">* Гарантия на монтаж 5 лет</div>
                                        <?/*</div>
                                </div>*/?>

                                <div class="cart-form" data-id="<?=$user_id;?>" data-type="user-info">
                                    <div class="cart-point" data-type="cart-point">
                                        <div class="cart-form-point" data-type="cart-point-name">
                                            Способ получения <i class="icon-question tooltip-icon" data-type="tooltip-icon" data-val="del-cond"></i>
                                        </div>
                                        <div class="cart-form-point-wrap" data-type="cart-point-cont">
                                            <div class="cart-form-rbtns" data-type="delivery-wrap" data-hide="0">
                                                <div class="cart-form-rbtn has-tooltip active" data-type="delivery" data-val="del" <?if($loc['ID'] == 3109 && !$USER->IsAuthorized() || $loc['ID'] == 3109 && $USER->IsAuthorized() && !in_array(1,$user_group_arr) && !in_array(10,$user_group_arr)) echo 'data-user="del"'?>>
                                                    Доставка
                                                    <div class="cart-tooltip cart-delivery-desc" data-id="del-cond">
                                                        <i class="icon-close"></i>
                                                        <div class="moscow-delivery">
                                                            <div class="del-title">Условия доставки:</div>
                                                            <div class="del-condition">
                                                                <span>БЕСПЛАТНО в&nbsp;черте&nbsp;МКАД</span><br>
                                                                (если сумма заказа с&nbsp;учетом скидки более&nbsp;10&nbsp;000.00&nbsp;руб)
                                                            </div>
                                                            <div class="del-condition">
                                                                <span>1&nbsp;000.00&nbsp;руб в&nbsp;черте&nbsp;МКАД </span><br>
                                                                (если сумма заказа с&nbsp;учетом скидки менее&nbsp;10&nbsp;000.00&nbsp;руб)
                                                            </div>
                                                            <div class="del-condition">
                                                                <span>50.00&nbsp;руб/км за&nbsp;МКАД (не&nbsp;далее 50&nbsp;км от&nbsp;МКАД) </span><br>
                                                                (если сумма заказа с&nbsp;учетом скидки менее&nbsp;10&nbsp;000.00&nbsp;руб)
                                                            </div>
                                                            <div class="del-condition">
                                                                <span>30.00&nbsp;руб/км за&nbsp;МКАД (не&nbsp;далее 50&nbsp;км от&nbsp;МКАД) </span><br>
                                                                (если сумма заказа с&nbsp;учетом скидки более&nbsp;10&nbsp;000.00&nbsp;руб)
                                                            </div>
                                                            <div class="del-condition-sample">
                                                                * доставка образцов&nbsp;- 500.00&nbsp;руб в&nbsp;черте&nbsp;МКАД
                                                            </div>
                                                            <div class="del-note">разгрузка товара осуществляется силами Покупателя</div>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="cart-form-rbtn" data-type="delivery" data-val="pickup" <?if($loc['ID'] == 3109 && !$USER->IsAuthorized() || $loc['ID'] == 3109 && $USER->IsAuthorized() && !in_array(1,$user_group_arr) && !in_array(10,$user_group_arr)) echo 'data-user="pickup"'?>>Самовывоз</div>
                                                <div class="del-tooltip top">
                                                    Доступна при&nbsp;заказе <br>на&nbsp;сумму не&nbsp;менее 10&nbsp;000&nbsp;руб.
                                                </div>
                                            </div>
                                            <div class="delivery-form active" data-type="del" data-class="del-desc" <?if($loc['ID'] == 3109 && !$USER->IsAuthorized() || $loc['ID'] == 3109 && $USER->IsAuthorized() && !in_array(1,$user_group_arr) && !in_array(10,$user_group_arr)) echo 'data-user="del-wrap"'?>>
                                                <div class="del-details del-form">
                                                    <?/*<div class="del-title">километраж:</div>*/?>
                                                    <div class="has-tooltip">
                                                        <label for="del-km">км за МКАД<span>*</span></label>
                                                        <div class="del-km-wrap">
                                                            <input type="text" id="del-km" data-type="del-km" value="0">
                                                            <div class="del-km-err" data-type="del-km-err">Не более 50 км</div>
                                                        </div>
                                                        <i class="icon-question tooltip-icon" data-type="tooltip-icon" data-val="km"></i>
                                                        <div class="cart-tooltip" data-id="km">
                                                            <i class="icon-close"></i>
                                                            <div class="del-condition">
                                                                укажите количество км за МКАД. <br>если вы находитесь в&nbsp;четре&nbsp;МКАД, оставьте&nbsp;0. <br>доставка производится только если расстояние за&nbsp;МКАД составляет менее&nbsp;50&nbsp;км.
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="del-form-title">Укажите адрес доставки <a data-type="clear-del-data">Очистить форму</a></div>
                                                <form action="#" class="del-form" data-type="addr-form">
                                                    <div>
                                                        <label for="del-city">Город<span>*</span></label>
                                                        <input type="text" id="del-city" data-type="city" value="<?=$user['PERSONAL_CITY']?>">
                                                    </div>
                                                    <div>
                                                        <label for="del-str">Улица<span>*</span></label>
                                                        <input type="text" id="del-str" data-type="street" value="<?=$user['PERSONAL_ZIP']?>">
                                                    </div>
                                                    <div>
                                                        <label for="del-house">Дом<span>*</span></label>
                                                        <input type="text" id="del-house" data-type="house" value="<?=$user['PERSONAL_STREET']?>">
                                                    </div>
                                                    <div class="mount-btns-desc">*доставка осуществляется до&nbsp;подъезда</div>
                                                </form>
                                                <?if($USER->IsAuthorized() && in_array(5,$user_group_arr)) { ?>
                                                    <div class="e-del-save has-tooltip" data-type="save-del">
                                                        Сохранить как адрес доставки по&nbsp;умолчанию
                                                        <i class="icon-question tooltip-icon" data-type="tooltip-icon" data-val="save-addr"></i>
                                                        <div class="cart-tooltip" data-id="save-addr">
                                                            <i class="icon-close"></i>
                                                            <div class="e-del-save-desc">адрес будет сохранен в&nbsp;личном кабинете <br>после оформления&nbsp;заказа</div>
                                                        </div>
                                                    </div>
                                                <? } ?>
                                            </div>
                                            <div class="delivery-form" data-type="pickup" data-class="del-desc" <?if(!$USER->IsAuthorized() || $USER->IsAuthorized() && !in_array(1,$user_group_arr) && !in_array(10,$user_group_arr)) echo 'data-user="pickup-wrap"'?>>
                                                <div class="del-title">Укажите пункт самовывоза, который вам&nbsp;подходит:</div>
                                                <div class="cart-form-rbtns cart-form-rbtns-wrap cart-form-rbtns-pickup" data-type="pickup-point-wrap">
                                                    <div class="cart-form-rbtn has-tooltip active" data-type="pickup-point" data-val="kdvor">
                                                        <div class="pickup-point-name">ТК "Каширский двор"</div>
                                                        <div class="cart-tooltip" data-id="kash-addr">
                                                            <i class="icon-close"></i>
                                                            <div class="pickup-point-addr">г. Москва, Каширское&nbsp;ш., д.19, корп.1, фирменный салон "Европласт"</div>
                                                        </div>
                                                        <i class="icon-question tooltip-icon" data-type="tooltip-icon" data-val="kash-addr"></i>
                                                    </div>

                                                    <div class="cart-form-rbtn has-tooltip" data-type="pickup-point" data-val="nahim">
                                                        <div class="pickup-point-name">ТВК "ЭКСПОСТРОЙ на Нахимовском"</div>
                                                        <div class="cart-tooltip" data-id="nach-addr">
                                                            <i class="icon-close"></i>
                                                            <div class="pickup-point-addr">г.Москва, Нахимовский проспект, д.24, фирменный салон "Европласт"</div>
                                                        </div>
                                                        <i class="icon-question tooltip-icon" data-type="tooltip-icon" data-val="nach-addr"></i>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="cart-point" data-type="cart-point">
                                        <div class="cart-form-point" data-type="cart-point-name">
                                            <span></span>Способ оплаты
                                        </div>
                                        <div class="cart-form-point-wrap" data-type="cart-point-cont">
                                            <div class="cart-form-rbtns cart-form-rbtns-pay" data-type="payment-wrap">
                                                <div class="cart-form-rbtn-payment cart-form-rbtn-receiving" data-type="payment" data-val="cash">
                                                    <span>При получении</span>
                                                    <div class="payment-banks">
                                                        <i class="icomoon icon-cashcard"></i>
                                                    </div>
                                                </div>
                                                <div class="cart-form-rbtn-payment cart-form-rbtn-pay active" data-type="payment" data-val="online">
                                                    <span>Онлайн</span>
                                                    <div class="payment-banks">
                                                        <i class="icon-mir"></i>
                                                        <i class="icon-visa"></i>
                                                        <i class="icon-mastercard"></i>
                                                        <i class="icon-sbp"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <? } ?>
                            <div class="cart-form-submit" data-type="form-submit" data-user="save">
                                Оформить заказ
                            </div>
                        </div>
                        <div class="form-note">Он-лайн заказ не&nbsp;является публичной&nbsp; офертой</div>
                        <div class="cart-order-dwnld" data-type="save-pdf"><i class="icomoon icon-download"></i>Скачать заказ</div>
                        <?if($USER->IsAuthorized()) { ?>
                            <div class="cart-save">
                                <div class="cart-save-btn">
                                    <button type="button" data-type="save-cart">
                                        <span>Сохранить заказ в&nbsp;личный&nbsp;кабинет*</span>
                                    </button>
                                    <div class="cart-save-btn-desc">*вы всегда сможете вернуться и&nbsp;отредактировать заказ</div>
                                </div>
                                <div class="save-mess" data-type="save-mess">Заказ сохранен в&nbsp;личном&nbsp;кабинете</div>
                            </div>
                        <? } ?>
                    </div>
                    <div class="personal-data-form" data-type="p-form">
                        <i class="icomoon icon-close" data-type="close-form"></i>
                        <a data-type="clear-pers-data" class="personal-form-clear">Очистить форму</a>
                        <form action="#" data-type="p-form-inputs">
                            <div class="left-column">
                                <input type="text" id="p-name" data-type="p-name" value="<?=$user['NAME']?>" placeholder="имя*">
                                <input type="text" id="p-last-name" data-type="p-last-name" value="<?=$user['LAST_NAME']?>" placeholder="фамилия*">
                                <div class="input-wrap input-wrap-no-margin" data-type="tel-wrap">
                                    <input type="tel" name="p-phone" id="p-phone" data-tel="yes" required="required" data-mask="<?=get_phone_mask($loc['country']['VALUE'])?>" placeholder="телефон*">
                                </div>
                                <div class="input-wrap input-wrap-format">
                                    <input type="checkbox" id="p_format" name="p_format" class="q-check" data-type="online-format">
                                    <label for="p_format">у меня другой формат <br>номера телефона</label>
                                </div>
                            </div>
                            <div class="right-column">
                                <input type="text" id="p-mail" data-type="p-mail" value="<?=$user['EMAIL']?>" placeholder="email*">
                                <textarea id="p-comment" data-type="p-comment" placeholder="комментарий"></textarea>
                            </div>
                        </form>
                        <?if(!$USER->IsAuthorized()) { ?>
                            <div class="pers-data" data-type="pers-data">я согласен на&nbsp;обработку <a href="/company/policies/#personal_data" target="_blank" data-type="pers-data">персональных данных</a></div>
                        <? } ?>
                        <div class="personal-buttons">
                            <button type="button" class="personal-submit" data-type="p-submit">Оформить заказ</button>
                            <button type="button" class="personal-reset" data-type="p-reset">Отмена</button>
                        </div>
                        <img src="/img/preloader.gif" alt="wait" class="order-loader">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="clear-all-window" data-type="clear-all-window">
    <i class="new-icomoon icon-close" data-type="close-clear-all-window"></i>
    <div class="clear-all-window-title">Подтвердите действие</div>
    <div class="clear-all-window-qst">Вы уверены, что хотите <br>удалить все товары из корзины?</div>
    <div class="clear-all-window-btns">
        <div class="clear-all-yes" data-type="clear-all-yes">Очистить корзину</div>
        <div class="clear-all-no" data-type="clear-all-no">Отмена</div>
    </div>
</div>
<div class="order-req" data-type="order-req">
    <i class="icomoon icon-close" data-type="order-req-close"></i>
    <div data-type="order-req-content">
    </div>
</div>
<script src="/cart/cart.js?<?=$random?>"></script>
<? require($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");
if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog.php");
}
