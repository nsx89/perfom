<?
$id = $_GET['order'];
$arFilter = Array("IBLOCK_CODE"=>"keep_order","ID"=>$id);
$res = CIBlockElement::GetList(Array(),$arFilter);
$item = $res->GetNextElement();
$item = array_merge($item->GetFields(), $item->GetProperties());
if($item['ACTIVE'] == 'N') LocalRedirect('/404.php');
global $USER;
?>

<section class="pacc-nav pacc-nav-order" data-type="order-id" data-id="<?=$item['ID']?>">
    <div class="pacc-main-info">
        <div class="pac-main-info-tit">
            <div class="pacc-order-number">
                <a href="/personal/#orders" class="pacc-back"><i class="icon-arrow-left"></i></a>
                <span>Заказ №<?=$item['NAME']?></span>
            </div>
            <?
            $date = $item['DATE']['VALUE'];
            $date = explode(" ",$date);
            $time = explode(":",$date[1]);
            $time = $time[0].":".$time[1];
            ?>
            <div class="pacc-order-date"><?=$date[0]?> <span><?=$time?></span></div>
        </div>
        <div class="pacc-main-info-dealer">
            <?
            $dlr_name = 'OOO Декор';
            $dlr_info = get_dealer_phone($item['MAIL_DEALER']['VALUE']);
            if($item['ID_DEALER']['VALUE']!='') {
                $res = CIBlockElement::GetList(Array(),array("ID"=>$item['ID_DEALER']['VALUE']));
                if($ar_res = $res->GetNextElement()) {
                    $dlr = array_merge($ar_res->GetFields(), $ar_res->GetProperties());
                    $dlr_name = $dlr['organization']['VALUE'];
                    $dlr_info['phone'] = $item['PHONE_DEALER']['VALUE'];
                    $dlr_info['addr'] = '';
                }
            }
            ?>
            <? if($dlr_name!='') {?>
                <div class="pacc-dealer-item"><span>Принял:</span><?=$dlr_name?></div>
            <? } ?>
            <? if($item['MAIL_DEALER']['VALUE']!='') { ?>
                <div class="pacc-dealer-item"><span>email:</span><?=$item['MAIL_DEALER']['VALUE']?></div>
            <? } ?>
            <? if($dlr_info['phone'] && $dlr_info['phone']!= '') { ?>
              <div class="pacc-dealer-item"><span>Телефон:</span><?=$dlr_info['phone']?></div>
            <? } ?>
            <? if($item['DELIVERY']['VALUE']=='pickup' && $dlr_info['addr'] && $dlr_info['addr']!= '') { ?>
              <div class="pacc-dealer-item"><span>Адрес:</span><?=$dlr_info['addr']?></div>
            <? }?>
        </div>
      <div class="pacc-main-change">
        Для изменения заказа обратитесь к&nbsp;менеджеру <br>по&nbsp;контактам, указанным выше
      </div>
        <div class="pacc-main-info-details">
            <?if($item['DELIVERY']['VALUE']=='del') { ?>
                <div class="pacc-order-delivery">Доставка</div>
            <? } elseif(($item['DELIVERY']['VALUE']=='pickup')) { ?>
                <div class="pacc-order-delivery">Самовывоз</div>
            <? } ?>
            <?if($item['PAYMENT']['VALUE']=='online') { ?>
                <div class="pacc-order-delivery">Оплата онлайн</div>
            <? } elseif($item['PAYMENT']['VALUE']=='cash') { ?>
                <div class="pacc-order-delivery">Оплата при получении
                  <?if($item['RECEIVING']['VALUE']=='receiving-cash') echo ' (наличными)'?>
                  <?if($item['RECEIVING']['VALUE']=='receiving-card') echo ' (картой)'?>
                </div>
            <? } elseif($item['PAYMENT']['VALUE']=='prepayment') { ?>
              <div class="pacc-order-delivery">Предоплата</div>
            <? } ?>
        </div>
        <?
        $products = Array();
        ?>
        <div class="pacc-main-info-price">
            <div class="pacc-main-info-item">
                <div class="pacc-main-info-item-lbl">Статус заказа:</div>
                <div class="pacc-main-info-item-val">
                    <div data-type="stat-wrap">
                        <? if($item['STATUS']['VALUE'] != '') { ?>
                            <div class="order-stat<?if($item['STATUS']['VALUE'] == 'shipped') echo ' finished'?>"><?=get_order_status($item['STATUS']['VALUE'])?></div>
                        <? } ?>
                    </div>
                </div>
            </div>
            <div class="pacc-main-info-item">
                <div class="pacc-main-info-item-lbl">Валюта</div>
                <div class="pacc-main-info-item-val"><?=$item['CURR']['VALUE']?></div>
            </div>
            <div class="pacc-main-info-item">
                <div class="pacc-main-info-item-lbl">Всего товаров на сумму:</div>
                <div class="pacc-main-info-item-val"><?=__cost_format($item['TOTAL']['VALUE'],$item['CHOOSEN_REG']['VALUE'])?></div>
            </div>
            <? if($item['SALE']['VALUE'] != '' && $item['SALE']['VALUE'] != 0) {?>
                <div class="pacc-main-info-item">
                    <div class="pacc-main-info-item-lbl">Скидка – %</div>
                    <div class="pacc-main-info-item-val"><?=$item['SALE']['VALUE']?>%</div>
                </div>
            <? } ?>
            <? if($item['SALE_SUM']['VALUE'] != '' && $item['SALE_SUM']['VALUE'] != 0) {?>
                <div class="pacc-main-info-item">
                    <div class="pacc-main-info-item-lbl">Сумма скидки:</div>
                    <div class="pacc-main-info-item-val"><?=__cost_format($item['SALE_SUM']['VALUE'],$item['CHOOSEN_REG']['VALUE'])?></div>
                </div>
            <? } ?>
            <? if($item['DELIVERY_PRICE']['VALUE'] != '' && $item['DELIVERY_PRICE']['VALUE'] != 0) {?>
              <div class="pacc-main-info-item">
                <div class="pacc-main-info-item-lbl">Стоимость доставки:</div>
                <div class="pacc-main-info-item-val"><?=__cost_format($item['DELIVERY_PRICE']['VALUE'],$item['CHOOSEN_REG']['VALUE'])?></div>
              </div>
            <? } ?>
            <? if($item['TOTAL_SALE']['VALUE'] != '') {?>
                <div class="pacc-main-info-item pacc-main-info-item-final">
                    <div class="pacc-main-info-item-lbl">Итого, с учетом скидки:</div>
                    <div class="pacc-main-info-item-val"><?=__cost_format($item['TOTAL_SALE']['VALUE'],$item['CHOOSEN_REG']['VALUE'])?></div>
                </div>
            <? } else { ?>
                <div class="pacc-main-info-item pacc-main-info-item-final">
                    <div class="pacc-main-info-item-lbl">Итого:</div>
                    <div class="pacc-main-info-item-val"><?=__cost_format($item['TOTAL']['VALUE'],$item['CHOOSEN_REG']['VALUE'])?></div>
                </div>
            <? } ?>
        </div>
        <div class="pacc-main-info-payment">
          <div class="pacc-main-info-item">
            <div class="pacc-main-info-item-lbl">Оплачено:</div>
              <?
              $need_to_pay = $item['TOTAL_SALE']['VALUE'] != '' && $item['TOTAL_SALE']['VALUE'] != 0 ? $item['TOTAL_SALE']['VALUE'] : $item['TOTAL']['VALUE'];
              $paid = $item['PAY_TOTAL']['VALUE'] == '' ? 0 : $item['PAY_TOTAL']['VALUE'];
              $pay_difference = $need_to_pay - $paid;
              ?>
            <div class="pacc-main-info-item-val" data-type="paid" data-val="<?=$paid?>"><?=__cost_format($paid,'3109')?></div>
          </div>
          <div class="pacc-main-info-item">
            <div class="pacc-main-info-item-lbl"><?=$pay_difference < 0 ? 'Переплата:' : 'Долг:'?></div>
            <?if($pay_difference < 0) $pay_difference = -1*$pay_difference;?>
            <div class="pacc-main-info-item-val" data-type="payment-debt" data-val="<?=$pay_difference?>"><?=__cost_format($pay_difference,'3109')?></div>
          </div>
        <?/*if($item['PAY_STATUS']['VALUE'] == 'Y' && $item['PAYMENT']['VALUE'] == 'online' && $item['PAYMENT_STATUS']['VALUE'] != 'оплачено') { ?>
          <a href="<?=$item['PAY_URL']['VALUE']?>" target="_blank" class="user-online-payment">Онлайн оплата</a>
        <? } ?>
        <?if($item['PAYMENT']['VALUE'] == 'online' && $item['PAY_STATUS']['VALUE'] != 'Y' && $item['PAYMENT_STATUS']['VALUE'] != 'оплачено') { ?>
          <img src="/images/AjaxLoader.gif" class="send-online-preloader" data-type="online-preloader">
          <button type="button" data-type="pay-online" class="user-online-payment">Онлайн оплата</button>
          <div class="online-error-mess" data-type="online-err">Письмо с сылкой на оплату отправлено пользователю</div>
        <? } */?>
        <?

        if($item['CHOOSEN_REG']['VALUE'] == 3109 && $pay_difference > 0) { ?>
          <div class="pacc-main-info-btns">
            <a class="pacc-main-info-pay-online-btn" href="https://<?=$_SERVER['HTTP_HOST']?>/cart/pay.php?id=<?=$item['UUID']['VALUE']?>" data-type="link" target="_blank">Оплатить онлайн</a>
            <div class="pacc-main-info-copy-link" data-type="copy-link"><i class="icon-verified"></i><span>Скопировать ссылку на оплату</span></div>
            <div class="pacc-main-info-copy-link-wrap"><div class="pacc-main-info-copy-link" data-type="send-link"><i class="icon-arroba"></i><span>Отправить ссылку на оплату</span></div>
              <form data-type="send-link-form">
                <div class="form-wrap">
                  <input type="text" name="send-email" id="sendEmailInp" value="<?=$item['USER_MAIL']['VALUE']?>">
                  <button type="button" data-type="send-link-btn">Отправить</button>
                </div>
              </form>
            </div>

          </div>
        <? } ?>
    </div>
    </div>

    <div class="pacc-client-info">
        <div class="pacc-client-info-col">
            <div class="pacc-client-info-tit">Личные данные</div>
            <? if($item['USER_NAME']['VALUE'] != '') { ?>
            <div class="pacc-client-info-item">
                <span>имя </span><?=$item['USER_NAME']['VALUE']?>
            </div>
            <? } ?>
            <? if($item['USER_LAST_NAME']['VALUE'] != '') { ?>
            <div class="pacc-client-info-item">
                <span>фам. </span><?=$item['USER_LAST_NAME']['VALUE']?>
            </div>
            <? } ?>
            <? if($item['USER_PHONE']['VALUE'] != '') { ?>
            <div class="pacc-client-info-item">
                <span>тел. </span><?=str_phone($item['USER_PHONE']['VALUE'])?>
            </div>
            <? } ?>
            <? if($item['USER_MAIL']['VALUE'] != '') { ?>
            <div class="pacc-client-info-item">
                <span>email </span><?=$item['USER_MAIL']['VALUE']?>
            </div>
            <? } ?>
        </div>
        <? if($loc['ID'] == 3109) {?>
        <div class="pacc-client-info-col">
            <? if($item['USER_CITY']['VALUE']=='' && $item['USER_STREET']['VALUE']=='' && $item['USER_HOUSE']['VALUE']=='' && $item['USER_APRT']['VALUE']=='' && $item['USER_ADDR']['~VALUE']['TEXT']!='') { ?>
                <div class="pacc-client-info-tit">Адрес доставки</div>
                <div class="pacc-client-info-item pacc-client-val-old">
                    <div class="pacc-client-val"><?=htmlspecialchars_decode($item['USER_ADDR']['~VALUE']['TEXT'])?></div>
                </div>
            <? } elseif($item['USER_CITY']['VALUE']!='' || $item['USER_STREET']['VALUE']!='' || $item['USER_HOUSE']['VALUE']!='' || $item['USER_APRT']['VALUE']!='') { ?>
                <div class="pacc-client-info-tit">Адрес доставки</div>
                <? if($item['USER_CITY']['VALUE'] != '') { ?>
                <div class="pacc-client-info-item">
                    <span>г. </span><?=$item['USER_CITY']['VALUE']?>
                </div>
                <? } ?>
                <? if($item['USER_STREET']['VALUE'] != '') { ?>
                <div class="pacc-client-info-item">
                    <span>ул. </span><?=$item['USER_STREET']['VALUE']?>
                </div>
                <? } ?>
                <? if($item['USER_HOUSE']['VALUE'] != '') { ?>
                <div class="pacc-client-info-item">
                    <span>д. </span><?=$item['USER_HOUSE']['VALUE']?>
                </div>
                <? } ?>
                <? if($item['USER_APRT']['VALUE'] != '') { ?>
                <div class="pacc-client-info-item">
                    <span>кв. </span>
                    <?=$item['USER_APRT']['VALUE']?>
                </div>
                <? } ?>
            <? } ?>
            <?if($item['DELIVERY_KM']['VALUE'] != '' && $item['DELIVERY']['VALUE']=='del') { ?>
              <div class="pacc-client-info-item">
                  <span>км за МКАД </span><?=$item['DELIVERY_KM']['VALUE']?>
              </div>
            <? } ?>
        </div>
        <? } ?>
        <? if($item['USER_NOTE']['~VALUE']['TEXT'] != '' || $item['MOUNTING']['VALUE'] == 'Y') { ?>
        <div class="pacc-client-info-bottom">
            <?if($item['USER_NOTE']['~VALUE']['TEXT'] != '') { ?>
            <div class="pacc-client-info-item pacc-client-info-item-comm">
                <span>комментарий </span>
                <?=htmlspecialchars_decode($item['USER_NOTE']['~VALUE']['TEXT'])?>
            </div>
            <? } ?>
            <?if($item['MOUNTING']['VALUE'] == 'Y') { ?>
                <div class="pacc-client-mounting">Запрошен расчет монтажа</div>
            <? } ?>
        </div>
        <? } ?>
    </div>

</section>



<?if($item['MANAGER_COMMENT']['~VALUE'] != '') { ?>
  <section class="order-manager-comment">
    <div class="order-manager-comment-label">Комментарий менеджера к заказу:</div>
    <div class="order-manager-comment-text"><?=$item['MANAGER_COMMENT']['~VALUE']['TEXT']?></div>
  </section>
<? } ?>

<section class="orders-list order-item-list">
    <table>
        <tr class="order-table-title">
            <td>Номер</td>
            <td>Наименование товара</td>
            <td>Цена</td>
            <td>Количество</td>
            <td>Сумма</td>
        </tr>
        <?
        $arFilter = Array("IBLOCK_CODE"=>"order_products","PROPERTY_ORDER_NUMBER"=>$item['NAME'],"ACTIVE"=>"Y");
        $res = CIBlockElement::GetList(Array(),$arFilter);
        $n = 1;
        $sampleArr = Array();
        while($product = $res->GetNextElement()) {
            $product = array_merge($product->GetFields(), $product->GetProperties());
            if(strpos($product['NAME'],'s') !== false) {
                $sampleArr[] = $product;
                continue;
            }
            $arProdFilter = Array("IBLOCK_ID"=>12,"ID"=>$product['NAME'],"ACTIVE"=>"Y");
            $resProd = CIBlockElement::GetList(Array(),$arProdFilter);
            $prod_arr = $resProd->GetNextElement();
            $prod_arr = array_merge($prod_arr->GetFields(), $prod_arr->GetProperties());
            ?>
            <tr>
                <td>№ <?=$n++?></td>
                <td><a href="<?=__get_product_link($prod_arr)?>" target="_blank"><?=__get_product_name($prod_arr)?></a></td>
                <td><?=__cost_format($product['PRICE']['VALUE'],$item['CHOOSEN_REG']['VALUE'])?></td>
                <td><?=$product['QTY']['VALUE']?></td>
                <? $total = $product['PRICE']['VALUE']*$product['QTY']['VALUE'];?>
                <td><?=__cost_format($total,$item['CHOOSEN_REG']['VALUE'])?></td>
            </tr>
        <? } ?>
        <? if(count($sampleArr) > 0) {
            foreach($sampleArr as $product) {
                $arProdFilter = Array("IBLOCK_ID"=>12,"ID"=>substr($product['NAME'], 1),"ACTIVE"=>"Y");
                $resProd = CIBlockElement::GetList(Array(),$arProdFilter);
                $prod_arr = $resProd->GetNextElement();
                $prod_arr = array_merge($prod_arr->GetFields(), $prod_arr->GetProperties());
                ?>
                <tr>
                    <td><?=$n++?></td>
                    <td><a href="<?=__get_product_link($prod_arr)?>" target="_blank"><?=__get_product_name($prod_arr)?> <b>образец</b></a></td>
                    <td><?=__cost_format($product['PRICE']['VALUE'],$item['CHOOSEN_REG']['VALUE'])?></td>
                    <td><?=$product['QTY']['VALUE']?></td>
                    <? $total = $product['PRICE']['VALUE']*$product['QTY']['VALUE'];?>
                    <td><?=__cost_format($total,$item['CHOOSEN_REG']['VALUE'])?></td>
                </tr>
                <?
            }
        }?>
    </table>
</section>