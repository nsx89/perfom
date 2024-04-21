<?
$arr_order = [];
$arFilter = Array("IBLOCK_CODE"=>"saved_orders","ACTIVE"=>'Y',"PROPERTY_CLIENT_ID"=>$user_id);
$res = CIBlockElement::GetList(Array("ID"=>"DESC"),$arFilter);
while ($item_order = $res->GetNextElement()) {
    $arr_order[] = array_merge($item_order->GetFields(), $item_order->GetProperties());
}
?>
<div class="orders-wrap">
  <div class="orders-lead">
    <p>Здесь вы можте увидеть все ваши сохранённые заказы.</p>
    <p>Для более подробной информации <br class="press-i-br">нажмите <i class="icon-arrow-right"></i></p>
  </div>
  <section class="orders-list personal-orders-list saved-orders-list">
    <? if(empty($arr_order)) { ?>
        <div class="empty-all-orders">
          У пользователя <span><?=$user['EMAIL']?></span> нет сохранённых заказов.<br>
        </div>
    <? } else { ?>
    <table class="personal-orders-table saved-orders-table">
      <tbody><tr class="order-table-title">
        <th>Номер</th>
        <th>Дата сохранения</th>
        <th>Сумма заказа</th>
        <th>Количество товаров</th>
        <th>Подробнее</th>
        <?/*<td>Удалить</td>*/?>
      </tr>

      <? $n = 1; ?>
      <? foreach($arr_order as $item) { ?>

        <tr data-id="<?=$item['ID']?>">
        <td>№ <?=$n?></td>
          <?
          $date = $item['DATE_CREATE'];
          $date = explode(" ",$date);
          $time = explode(":",$date[1]);
          $time = $time[0].":".$time[1];

          $products = json_decode($item['ORDER_JSON']['~VALUE']);

          $total_sum = 0;
          $total_qty = 0;

          foreach($products as $prod) {
              $citemId = $prod->id;
              if(strpos($prod->id,'s') !== false) {
                  $citemId = substr($prod->id, 1);
              }
              $arFilter = Array("IBLOCK_CODE"=>"tovar","ID"=>$citemId,"ACTIVE"=>"Y");
              $res = CIBlockElement::GetList(Array(),$arFilter);
              while($prod_item = $res->GetNextElement()) {
                  $prod_item = array_merge($prod_item->GetFields(), $prod_item->GetProperties());
                  $cost = __get_product_cost($prod_item);
                  $total_sum += $cost*$prod->qty;
                  $total_qty += $prod->qty;
              }
          }

          $discount = __discount_mob($total_sum,$products);
          $total = isset($discount['total']) ? $discount['total'] : $total_sum;

          ?>
            <td><?=$date[0]?> <span><?=$time?></span></td>
            <td><?=__cost_format($total)?></td>
            <td>
              <div><?=$total_qty?></div>
            </td>
            <td><a href="?saved_order=<?=$item['ID']?>#saved"><i class="icon-arrow-right"></i></a></td>
            <?/*<td style="remove-saved-order"><i class="new-icomoon icon-close pacc-close" data-type="remove-saved" title="Удалить сохраненный заказ"></i></td>*/?>
          </tr>
      <?
          $n++;
      }
      ?>
      </tbody>
    </table>
    <? } ?>

  </section>
</div>