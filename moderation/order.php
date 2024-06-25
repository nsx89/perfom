<?
$id = $_GET['order'];
$item = array();
if($id != 'new') {
    $arFilter = Array("IBLOCK_CODE"=>"keep_order","ID"=>$id);
    $res = CIBlockElement::GetList(Array(),$arFilter);
    $item = $res->GetNextElement();
    $item = array_merge($item->GetFields(), $item->GetProperties());
    if($item['ACTIVE'] == 'N') LocalRedirect('/404.php');
}
$blocked = '';
if($id != 'new') $blocked = ' blocked';
if($item['M_Block']['VALUE'] == 'Y') {
    CIBlockElement::SetPropertyValueCode($item['ID'], 'M_Block', 'N');
}
?>
<form>


            <section class="pacc-nav pacc-nav-order<?=$blocked?>" data-type="order-id" data-id="<?=$item['ID']?>">
                <div class="pacc-main-info">
                    <div class="pac-main-info-tit">
                        <a href="/moderation/" class="pacc-back" data-type="back"><i class="icon-arrow-left"></i></a>
                        <div class="pacc-order-number" data-type="order-number" data-val="<?=$item['NAME']?>">Заказ <? if($id != 'new') { ?>№<?=$item['NAME']?><? } ?></div>
                        <?
                        if($id != 'new') {
                            $date = $item['DATE']['VALUE'];
                            $date = explode(" ",$date);
                            $time = explode(":",$date[1]);
                            $time = $time[0].":".$time[1];
                            $date = $date[0];
                        } else {
                            $date = date('d.m.Y');
                            $time = date('H:i');
                        }
                        ?>
                        <div class="pacc-order-date" data-type="order-date" data-val="<?=$item['DATE']['VALUE']?>"><?=$date?> <span style="margin-right:0;"><?=$time?></span><i class="icon-delete" data-type="order-hide"></i></div>
                    </div>
                    <div class="pacc-main-info-dealer">
                        <?
                        $dlr_name = 'OOO Декор';
                        if($item['ID_DEALER']['VALUE']!='') {
                            $res = CIBlockElement::GetList(Array(),array("ID"=>$item['ID_DEALER']['VALUE']));
                            if($ar_res = $res->GetNextElement()) {
                                $dlr = array_merge($ar_res->GetFields(), $ar_res->GetProperties());
                                $dlr_name = $dlr['organization']['VALUE'];
                            }
                        }
                        ?>
                        <? if($dlr_name!='') {?>
                            <div class="pacc-dealer-item">
                                <div class="pacc-accept">Принял </div>
                                <div><?=$dlr_name?></div>
                            </div>
                        <? } ?>
                        <div class="pacc-dealer-item">
                            <div class="choose-dealer-email-title">E-mail</div>
                            <div class="choose-input-wrap">
                                <input type="text" name="dlr-name" value="<?=$item['MAIL_DEALER']['VALUE']?>" class="choose-input<?if($item['MAIL_DEALER']['VALUE'] != '') echo ' active'?>" placeholder="Выберите e-mail">
                                <ul class="choose-input-variants" data-type="email-list">
                                    <li <?if($item['MAIL_DEALER']['VALUE']=='kdvor@decor-evroplast.ru') echo 'class="active"'?>>kdvor@decor-evroplast.ru</li>
                                    <li <?if($item['MAIL_DEALER']['VALUE']=='nahim@decor-evroplast.ru') echo 'class="active"'?>>nahim@decor-evroplast.ru</li>
                                    <li <?if($item['MAIL_DEALER']['VALUE']=='shop@decor-evroplast.ru') echo 'class="active"'?>>shop@decor-evroplast.ru</li>
                                    <li <?if($item['MAIL_DEALER']['VALUE']=='salonn@decor-evroplast.ru') echo 'class="active"'?>>salonn@decor-evroplast.ru</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="om-main-info-details">
                        <div class="radio-btn-group radio-btn-group-del" data-type="delivery">
                            <div class="radio-btn-wrap<? if($item['DELIVERY']['VALUE']=='del') echo ' active'?>">
                                <input type="radio" name="delivery" id="delivery-del" value="del"<? if($item['DELIVERY']['VALUE']=='del') echo ' checked'?>>
                                <label for="delivery-del">Доставка</label>
                            </div>
                            <div class="radio-btn-wrap<? if($item['DELIVERY']['VALUE']=='pickup') echo ' active'?>">
                                <input type="radio" name="delivery" id="delivery-pickup" value="pickup"<? if($item['DELIVERY']['VALUE']=='pickup') echo ' checked'?>>
                                <label for="delivery-pickup">Самовывоз</label>
                            </div>
                        </div>
                        <div class="radio-btn-group radio-btn-group-payment" data-type="payment">
                            <div class="radio-btn-wrap<? if($item['PAYMENT']['VALUE']=='online') echo ' active'?>">
                                <input type="radio" name="payment" id="payment-online" value="online"<? if($item['PAYMENT']['VALUE']=='online') echo ' checked'?>>
                                <label for="payment-online">Предоплата (онлайн)</label>
                            </div>
                            <div class="radio-btn-wrap<? if($item['PAYMENT']['VALUE']=='cash') echo ' active'?>">
                                <input type="radio" name="payment" id="payment-cash" value="cash"<? if($item['PAYMENT']['VALUE']=='cash') echo ' checked'?>>
                                <label for="payment-cash">При получении</label>
                            </div>
                        </div>
                        <div class="pacc-main-info-item pacc-main-info-item-status">
                            <div class="pacc-main-info-item-lbl">Статус заказа</div>
                            <div class="pacc-main-info-item-val">
                                <div data-type="stat-wrap">
                                    <div class="order-stat<?if($item['STATUS']['VALUE'] == 'shipped') echo ' finished'?>">
                                        <? if($item['STATUS']['VALUE'] != '') {
                                            echo get_order_status($item['STATUS']['VALUE']);
                                        } else {
                                            echo get_order_status();
                                        }?>
                                    </div>
                                </div>
                                <i class="icon-settings" title="Изменить статус" data-type="new-status"></i>
                                <ul class="pacc-status-list" data-type="status-list">
                                    <? $res = CIBlockElement::GetList(Array('SORT'=>'ASC'), Array('IBLOCK_CODE'=>'order_status', 'ACTIVE'=>'Y'), false, Array(), Array());
                                    while($ob = $res->GetNextElement()) {
                                        $stat = array_merge($ob->GetFields(), $ob->GetProperties());
                                        ?>
                                        <li <?if($item['STATUS']['VALUE'] == $stat['CODE'] || $stat['CODE'] == 'new' && $item['STATUS']['VALUE'] == '') echo 'class="active"'?> data-type="stat-val" data-val="<?=$stat['CODE']?>"><?=$stat['NAME']?></li>
                                    <? }?>
                                </ul>
                            </div>
                            <? //if($item['CANCEL_REASON']['~VALUE']['TEXT'] != '') { ?>
                            <div class="reason-of-cancel">
                                <div class="reason-of-cancel-title">Причина <br>отмены</div>
                                <textarea class="autogrow" name="reason-cancel"><?=str_replace("<br/>","\r\n",$item['CANCEL_REASON']['~VALUE']['TEXT'])?></textarea>
                            </div>
                            <? //} ?>
                        </div>
                    </div>
                    <?
                    $products = Array();
                    ?>
                    <div class="om-main-info-price-wrap">
                        <div class="pacc-main-info-price">

                            <div class="pacc-main-info-item">
                                <div class="pacc-main-info-item-lbl">Валюта:</div>
                                <div class="pacc-main-info-item-val">RUB</div>
                            </div>
                            <div class="pacc-main-info-item">
                                <div class="pacc-main-info-item-lbl">Всего товаров на сумму:</div>
                                <div class="pacc-main-info-item-val" data-type="totalSum">0.00 RUB</div>
                            </div>
                            <div class="pacc-main-info-item">
                                <div class="pacc-main-info-item-lbl">Скидка – %:</div>
                                <div class="pacc-main-info-item-val"><input class="sale-input" type="text" name="order-sale" value="<?=$item['SALE']['VALUE'] == ''? 0 : $item['SALE']['VALUE'];?>" style="margin-right:30px">%</div>
                            </div>
                            <div class="pacc-main-info-item">
                                <div class="pacc-main-info-item-lbl">Сумма скидки:</div>
                                <div class="pacc-main-info-item-val" data-type="saleSum">0.00 RUB</div>
                            </div>
                            <div class="pacc-main-info-item">
                                <div class="pacc-main-info-item-lbl">Стоимость доставки:</div>
                                <div class="pacc-main-info-item-val"><input class="sale-input" type="text" name="delivery-price" value="<?=$item['DELIVERY_PRICE']['VALUE'] == ''? 0 : $item['DELIVERY_PRICE']['VALUE'];?>"><?=$item['CURR']['VALUE']?></div>
                            </div>
                            <div class="pacc-main-info-item pacc-main-info-item-final">
                                <div class="pacc-main-info-item-lbl">Итого, с учетом скидки:</div>
                                <div class="pacc-main-info-item-val" data-type="totalSaleSum">0.00 RUB</div>
                            </div>
                        </div>
                        <div class="pacc-client-serv-info">
                            <div class="pacc-serv-info-item">
                                <?
                                $res = CIBlockElement::GetByID($item['AUTO_REG']['VALUE']);
                                $ar_res = $res->GetNext();
                                ?>
                                <span>Авто-определение региона </span><?=$ar_res['NAME']?>
                            </div>
                            <div class="pacc-serv-info-item">
                                <?
                                $res = CIBlockElement::GetByID($item['CHOOSEN_REG']['VALUE']);
                                $ar_res = $res->GetNext();
                                ?>
                                <span>Выбранный регион </span><?=$ar_res['NAME']?>
                            </div>
                            <div class="pacc-serv-info-item">
                                <span>ip </span><a href="http://whois.domaintools.com/<?=$item['IP_USER']['VALUE']?>" target="_blank">93.174.224.212</a>
                            </div>
                            <div class="pacc-serv-info-item">
                                <span>Версия сайта </span><?=$item['VERS']['VALUE']?>
                            </div>
                            <div class="pacc-serv-info-item">
                                <span>Браузер </span><?/*=$item['BROWSER']['VALUE']*/?>
                                Mozilla/5.0 (iPhone; CPU iPhone OS 14_8_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.1.2 Mobile/15E148 Safari/604.1
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pacc-right-info">
                    <div class="pacc-client-info">
                        <div class="pacc-client-info-col">
                            <div class="pacc-client-info-tit">Личные данные</div>
                            <input class="pacc-client-val pacc-client-info-item" name="user-name" value="<?=$item['USER_NAME']['VALUE']?>" data-type="required" placeholder="имя*">
                            <input class="pacc-client-val pacc-client-info-item" name="user-lastname" value="<?=$item['USER_LAST_NAME']['VALUE']?>" data-type="required" placeholder="фамилия*">
                            <?
                            $phone = '';
                            if($item['USER_PHONE']['VALUE'] != '') {
                                $phone = strpos($item['USER_PHONE']['VALUE'],'+') === true ? $item['USER_PHONE']['VALUE'] : str_phone($item['USER_PHONE']['VALUE']);
                            }
                            ?>
                            <input class="pacc-client-val pacc-client-info-item" name="user-phone" value="<?=$phone?>" data-type="required" placeholder="телефон*">
                            <input class="pacc-client-val pacc-client-info-item" name="user-mail" value="<?=$item['USER_MAIL']['VALUE']?>" data-type="required" placeholder="e-mail*">
                        </div>
                        <div class="pacc-client-info-col">
                            <div class="pacc-client-info-tit">Адрес доставки</div>
                            <? if($item['USER_CITY']['VALUE']=='' && $item['USER_STREET']['VALUE']=='' && $item['USER_HOUSE']['VALUE']=='' && $item['USER_APRT']['VALUE']=='' && $item['USER_ADDR']['~VALUE']['TEXT']!='') { ?>
                                <textarea class="pacc-client-val pacc-client-info-item addr" name="user-addr" placeholder="адрес"><?=str_replace("<br/>","\r\n",$item['USER_ADDR']['~VALUE']['TEXT'])?></textarea>
                            <? } else { ?>
                                <input class="pacc-client-val pacc-client-info-item" name="user-city" value="<?=$item['USER_CITY']['VALUE']?>" placeholder="город">
                                <input class="pacc-client-val pacc-client-info-item" name="user-street" value="<?=$item['USER_STREET']['VALUE']?>" placeholder="улица">
                                <div class="pacc-client-addr-small">
                                    <input class="pacc-client-val pacc-client-info-item" name="user-house" value="<?=$item['USER_HOUSE']['VALUE']?>" placeholder="дом">
                                    <input class="pacc-client-val pacc-client-info-item" name="user-aprt" value="<?=$item['USER_APRT']['VALUE']?>" placeholder="квартира">
                                </div>
                            <? } ?>
                            <input class="pacc-client-val pacc-client-info-item" name="user-km" value="<?=$item['DELIVERY_KM']['VALUE']?>" placeholder="км за МКАД">
                        </div>
                        <div class="pacc-client-info-bottom pacc-client-info-bottom-new">
                            <textarea class="pacc-client-val pacc-client-info-item autogrow" name="user-note" placeholder="комментарий"><?=str_replace("<br/>","\r\n",$item['USER_NOTE']['~VALUE']['TEXT'])?></textarea>
                            <div class="om-main-info-details om-main-info-details-mount" data-type="mounting">
                                <div class="radio-btn-wrap<?if($item['MOUNTING']['VALUE'] == 'Y') echo ' active'?>">
                                    <input type="checkbox" name="munting" id="mount" value="<?=$item['MOUNTING']['VALUE']?>">
                                    <label for="mount">Запрос на расчет монтажа</label>
                                </div>
                            </div>
                            <? $comment = str_replace("<br/>","\r\n",$item['MANAGER_COMMENT']['~VALUE']['TEXT']);?>
                        </div>
                    </div>
                </div>

            </section>


    <?/**/?>

</form>

