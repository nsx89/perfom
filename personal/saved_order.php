<?
$id = $_GET['saved_order'];
$arFilter = Array("IBLOCK_CODE"=>"saved_orders","ID"=>$id);
$res = CIBlockElement::GetList(Array(),$arFilter);
$item = $res->GetNextElement();
$item = array_merge($item->GetFields(), $item->GetProperties());
if($item['ACTIVE'] == 'N') LocalRedirect('/404.php');
global $USER;
?>
<section class="pacc-nav pacc-nav-order saved-nav-order" data-type="order-id" data-id="<?=$item['ID']?>">

    <div class="pacc-main-info pacc-main-info-saved">
        <div class="pac-main-info-tit">
            <div class="pacc-order-number">
                <a href="/personal/#saved" class="pacc-back"><i class="icon-arrow-left"></i></a>
                <span>Сохраненный заказ</span>
            </div>
            <?
            $date = $item['DATE_CREATE'];
            $date = explode(" ",$date);
            $time = explode(":",$date[1]);
            $time = $time[0].":".$time[1];
            ?>
            <div class="pacc-order-date"><?=$date[0]?> <span style="margin-right:0;"><?=$time?></span></div>
        </div>
        <?
        $products = array();
        $order = json_decode($item['ORDER_JSON']['~VALUE']);
        $total_sum = 0;
        foreach($order as $prod) {
            $arFilter = Array("IBLOCK_CODE"=>"tovar","ID"=>$prod->id,"ACTIVE"=>"Y");
            $res = CIBlockElement::GetList(Array(),$arFilter);
            while($prod_item = $res->GetNextElement()) {
                $prod_item = array_merge($prod_item->GetFields(), $prod_item->GetProperties());
                $cost = __get_product_cost($prod_item);
                $total_sum += $cost*$prod->qty;
                $products[] = array_merge($prod_item,array('cost'=>$cost,'qty'=>$prod->qty));
            }
        }

        $discount = __discount_mob($total_sum,$order);
        ?>

        <div class="pacc-main-info-price pacc-main-info-price-saved">
            <div class="pacc-main-info-item">
                <div class="pacc-main-info-item-lbl">Валюта</div>
                <div class="pacc-main-info-item-val"><?=$curr?></div>
            </div>
          <? if($discount['discount'] != 0) { ?>
            <div class="pacc-main-info-item">
                <div class="pacc-main-info-item-lbl">Всего товаров на&nbsp;сумму:</div>
                <div class="pacc-main-info-item-val"><?=__cost_format($total_sum)?></div>
            </div>
                <div class="pacc-main-info-item">
                    <div class="pacc-main-info-item-lbl">Скидка – %</div>
                    <div class="pacc-main-info-item-val"><?=$discount['discount']?>%</div>
                </div>
                <div class="pacc-main-info-item">
                    <div class="pacc-main-info-item-lbl">Сумма скидки:</div>
                    <div class="pacc-main-info-item-val"><?=__cost_format($discount['discount_price'])?></div>
                </div>
                <div class="pacc-main-info-item pacc-main-info-item-final">
                    <div class="pacc-main-info-item-lbl">Итого, с&nbsp;учетом скидки:</div>
                    <div class="pacc-main-info-item-val"><?=__cost_format($discount['total'])?></div>
                </div>
            <? } else { ?>
                <div class="pacc-main-info-item pacc-main-info-item-final">
                    <div class="pacc-main-info-item-lbl">Итого:</div>
                    <div class="pacc-main-info-item-val"><?=__cost_format($total_sum)?></div>
                </div>
            <? } ?>
        </div>
    </div>

  <div class="pacc-client-info pacc-client-info-saved">
    <div class="saved-order-btns">
      <div class="restore-btn-mess"><span>ВНИМАНИЕ!</span><br> Если вы нажмёте кнопку "Восстановить корзину", данный заказ будет добавлен в&nbsp;корзину, при&nbsp;этом все находящиеся там на&nbsp;текущий момент товары будут&nbsp;удалены.</div>
      <button type="button" class="restore-btn" data-type="saved-restore">Восстановить корзину</button>
      <button type="button" class="remove-btn" data-type="saved-remove">Удалить заказ</button>
    </div>
  </div>
</section>

<section class="orders-list order-item-list" data-type="saved-prod-list">
    <table>
        <tr class="order-table-title">
            <td>№</td>
            <td>Наименование товара</td>
            <td>Цена</td>
            <td>Количество</td>
            <td>Сумма</td>
        </tr>
        <?
        $n = 1;
        foreach($products as $prod) { ?>
            <tr data-type="saved-prod" data-id="<?=$prod['ID']?>" data-qty="<?=$prod['qty']?>">
                <td><?=$n++?></td>
                <td><a href="<?=__get_product_link($prod)?>" target="_blank"><?=__get_product_name($prod)?></a></td>
                <td><?=__cost_format($prod['cost'])?></td>
                <td><?=$prod['qty']?></td>
                <? $total = $prod['cost']*$prod['qty'];?>
                <td><?=__cost_format($total)?></td>
            </tr>
        <? } ?>
    </table>
</section>