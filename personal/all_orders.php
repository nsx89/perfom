<?
$arr_order = [];
$arFilter = Array("IBLOCK_CODE"=>"keep_order","ACTIVE"=>'Y',"PROPERTY_CLIENT_ID"=>$user_id);
$res = CIBlockElement::GetList(Array("ID"=>"DESC"),$arFilter);
while ($item_order = $res->GetNextElement()) {
    $arr_order[] = array_merge($item_order->GetFields(), $item_order->GetProperties());
}
?>
<div class="orders-wrap">
  <div class="orders-lead">
    <p>Здесь вы&nbsp;можте отследить свою историю заказов, а&nbsp;также статус их&nbsp;выполнения.</p>
    <p>Для более подробной информации <br class="press-i-br">нажмите&nbsp;<i class="icon-arrow-right"></i></p>
  </div>
  <section class="orders-list personal-orders-list">
    <? if(empty($arr_order)) { ?>
        <div class="empty-all-orders">
          История заказов пользователя <span><?=$user['EMAIL']?></span> пуста.<br>
        </div>
    <? } else { ?>
    <table class="personal-orders-table">
      <tbody>
      <tr class="order-table-title">
        <th>Номер</th>
        <th>Дата оформления</th>
          <? if($loc['ID'] == 3109) {?>
        <th>Оплата</th>
          <? } ?>
        <th>Сумма заказа</th>
        <th>Статус</th>
        <th>Подробнее</th>
      </tr>

      <? foreach($arr_order as $item) { ?>
          <?//print_r($item);?>
        <tr>
        <td>№ <?=$item['NAME']?></td>
          <?
          $date = $item['DATE']['VALUE'];
          $date = explode(" ",$date);
          $time = explode(":",$date[1]);
          $time = $time[0].":".$time[1];
          ?>
            <td><?=$date[0]?> <span><?=$time?></span></td>
            <? if($loc['ID'] == 3109) {?>
            <td>
                <?
                $stat = array();
                if($item['PAYMENT']['VALUE'] == 'cash') {
                    $stat = array('value'=>'при получении','class'=>'');
                    if($item['STATUS']['VALUE'] == 'shipped') $stat['class'] = ' paid';
                } else {
                    if($item['PAYMENT_STATUS']['VALUE'] != '') {
                        if($item['PAYMENT_STATUS']['VALUE'] == 'ожидание оплаты') $stat = array('value'=>'ожидание оплаты','class'=>' wait'); //#c4c4c4
                        if($item['PAYMENT_STATUS']['VALUE'] == 'оплачено') $stat = array('value'=>'оплачено','class'=>' paid'); //#009a2b
                        if($item['PAYMENT_STATUS']['VALUE'] == 'предоплата') $stat = array('value'=>'предоплата','class'=>' paid'); //#009a2b
                        if($item['PAYMENT_STATUS']['VALUE'] == 'не оплачено') $stat = array('value'=>'не оплачено','class'=>' expired'); //red
                        if($item['PAYMENT_STATUS']['VALUE'] == 'требуется доплата') $stat = array('value'=>'требуется доплата','class'=>' surcharge'); //#f9c100
                    }
                }
                if(!empty($stat)) { ?>
                  <div class="order-stat<?=$stat['class']?>"><?=$stat['value']?></div>
                <? } ?>
            </td>
            <? } ?>
            <td><?=$item['TOTAL_SALE']['VALUE']!=''?__cost_format($item['TOTAL_SALE']['VALUE'],$item['CHOOSEN_REG']['VALUE']):__cost_format($item['TOTAL']['VALUE'],$item['CHOOSEN_REG']['VALUE'])?></td>
            <td>
                <? if($item['STATUS']['VALUE'] != '') { ?>
                  <div class="order-stat<?if($item['STATUS']['VALUE'] == 'shipped') echo ' finished'?>"><?=get_order_status($item['STATUS']['VALUE'])?></div>
                <? } ?>
            </td>
            <td><a href="?order=<?=$item['ID']?>#orders"><i class="icon-arrow-right"></i></a></td>
          </tr>
      <? } ?>
      </tbody>
    </table>
    <? } ?>

  </section>
</div>