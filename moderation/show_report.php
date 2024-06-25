<?
/**
 * Created by PhpStorm.
 * User: nadida
 * Date: 19.02.2020
 * Time: 17:51
 */
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog") || !CModule::IncludeModule("search")) {
    exit;
}
require_once($_SERVER["DOCUMENT_ROOT"] . "/include/phone.php");

$data = $_REQUEST;

$arFilter = Array();

$html = '';

// ПРОДАЖИ ПО МОСКВЕ
if($data['type'] == 'order_mosc') {

    $arFilter['IBLOCK_CODE'] = "keep_order";
    $arFilter['ACTIVE'] = "y";

//дата
$period_title = '';
if(!empty($data['date'])) {

    $period_title .= ' за&nbsp;период';

    $sort_date_from = $data['date']['from'];
    $sort_date_to = $data['date']['to'];

    if($sort_date_from!='') {
        $period_title .= ' с&nbsp;'.$sort_date_from;
        $sort_date_from = explode('.',$sort_date_from);
        $sort_date_from = $sort_date_from[2].'-'.$sort_date_from[1].'-'.$sort_date_from[0];
        $arFilter['>=PROPERTY_DATE'] = $sort_date_from." 00:00:00";
    }

    if($sort_date_to!='') {
        $period_title .= ' по&nbsp;'.$sort_date_to;
        $sort_date_to = explode('.',$sort_date_to);
        $sort_date_to = $sort_date_to[2].'-'.$sort_date_to[1].'-'.$sort_date_to[0];
        $arFilter['<=PROPERTY_DATE'] = $sort_date_to." 23:59:59";
    }
} else {
    $period_title .= ' за весь период';
}

//статус заказа
    if(!empty($data['status'])) {
        $property_arr = array("LOGIC" => "OR");
        foreach($data['status'] as $item) {
            if($item == 'no') {
                $property_arr[] = array('PROPERTY_STATUS' => false);
            } else {
                $property_arr[] = array('PROPERTY_STATUS' => $item);
            }
        }
        $arFilter[] = $property_arr;
    }

//менеджер
    if(!empty($data['manager'])) {
        $property_arr = array("LOGIC" => "OR");
        foreach($data['manager'] as $item) {
            if($item == 'no') {
                $property_arr[] = array('PROPERTY_MODERATOR' => false);
            } else {
                $property_arr[] = array('PROPERTY_MODERATOR' => $item);
            }
        }
        $arFilter[] = $property_arr;
    }

//способ оплаты
    if(!empty($data['payment'])) {
        $property_arr = array("LOGIC" => "OR");
        foreach($data['payment'] as $item) {
            if($item == 'online') {
                $property_arr[] = array('PROPERTY_PAYMENT' => $item);
            } else {
                $property_arr[] = array('PROPERTY_PAYMENT' => 'cash', 'PROPERTY_RECEIVING' => $item);
            }
        }
        $arFilter[] = $property_arr;
    }

//способ получения
    if(!empty($data['delivery'])) {
        $property_arr = array("LOGIC" => "OR");
        foreach($data['delivery'] as $item) {
            if($item == 'no') {
                $property_arr[] = array('PROPERTY_DELIVERY' => false);
            } else {
                $property_arr[] = array('PROPERTY_DELIVERY' => $item);
            }
        }
        $arFilter[] = $property_arr;
    }

    $arFilter[] = array('PROPERTY_CHOOSEN_REG' => 3109);//только Москва

    $total = array();

    $res = CIBlockElement::GetList(Array('id'=>'asc'),$arFilter,false, Array(), Array());
//print_r($res->SelectedRowsCount());
    ob_start(); ?>
    <section class="orders-list no-margin">
    <? if($res->SelectedRowsCount() > 0) { ?>
        <div class="order-table-caption">Отчёт по заказам<?=$period_title?></div>
        <div class="orders-list-table-wrapper">
            <table class="order-moscow">
            <tr class="order-table-title">
                <th>Дата заказа</th>
                <th>Номер заказа</th>
                <th>Способ получения</th>
                <th>Адрес получения</th>
                <th>Способ оплаты</th>
                <th>Сумма заказа</th>
                <th>Статус заказа</th>
                <th> ФИО менеджера</th>
            </tr>
            <?
            $total = array();
            while($item = $res->GetNextElement()) {
                $item = array_merge($item->GetFields(), $item->GetProperties()); ?>
                <tr>
                    <td><?=$item['DATE']['VALUE']?></td>
                    <td><?=$item['NAME']?></td>
                    <?
                    if($item['DELIVERY']['VALUE']=='del') $delivery_info = 'Доставка';
                    elseif($item['DELIVERY']['VALUE']=='pickup') $delivery_info = 'Самовывоз';
                    $total['DELIVERY'][$delivery_info]++;
                    ?>
                    <td><?=$delivery_info?></td>
                    <?
                    $total['DEALER'] = Array();
                    if($item['DELIVERY']['VALUE']=='pickup') {
                        if ($item['MAIL_DEALER']['VALUE'] == 'kdvor@decor-evroplast.ru') {
                            $dealer_info = 'ТК "Каширский двор"';
                        } else {
                            $dealer_info = 'ТВК "ЭКСПОСТРОЙ"';
                        }
                        $total['DEALER'][$dealer_info]++;
                    }
                    ?>
                    <td><?=$dealer_info?></td>
                    <?
                    if($item['PAYMENT']['VALUE']=='online') {
                        $payment_info = 'Оплата онлайн';
                        $payment_info_sum = 'Сумма онлайн оплат';
                    }
                    elseif($item['PAYMENT']['VALUE']=='cash' && $item['RECEIVING']['VALUE']=='receiving-card')  {
                        $payment_info = 'Оплата при получении картой';
                        $payment_info_sum = 'Сумма при получении картой';
                    }
                    elseif($item['PAYMENT']['VALUE']=='cash' && $item['RECEIVING']['VALUE']=='receiving-cash') {
                        $payment_info = 'Оплата при получении наличными';
                        $payment_info_sum = 'Сумма при получении картой';
                    }
                    elseif($item['PAYMENT']['VALUE']=='cash')  {
                        $payment_info = 'Оплата при получении';
                        $payment_info_sum = 'Сумма при получении';
                    }
                    elseif($item['PAYMENT']['VALUE']=='prepayment')  {
                        $payment_info = 'Предоплата';
                        $payment_info_sum = 'Сумма предоплаты';
                    }
                    $total['PAYMENT'][$payment_info]++;
                    ?>
                    <td><?=$payment_info?></td>
                    <?
                    if ($item["TOTAL_SALE"]["VALUE"]) $price = $item["TOTAL_SALE"]["VALUE"]; // нехорошее место нянется, надо учитывать
                    else $price = $item["TOTAL"]["VALUE"];
                    $total['TOTAL'][$payment_info_sum] += $price;
                    $t_price += $price;
                    ?>
                    <td><?=number_format($price, 0, '', ' ')?></td>
                    <td><?=get_order_status($item['STATUS']['VALUE'])?></td>
                    <?
                    $total['STATUS'][get_order_status($item['STATUS']['VALUE'])]++;
                    $rsModUser = CUser::GetByID($item['MODERATOR']['VALUE']);
                    $mod_user = $rsModUser->Fetch();
                    $mod_name = $mod_user['LAST_NAME'] != '' ? htmlspecialcharsBack($mod_user['LAST_NAME']).' '.mb_substr($mod_user['NAME'],0,1).'.' : htmlspecialcharsBack($mod_user['NAME']);
                    $total['MODERATOR'][$mod_name]++;
                    ?>
                    <td><?=$mod_name?></td>
                </tr>
            <? }
            $total['TOTAL']['Сумма ИТОГО'] += $t_price;
            ?>
            <tr class="order-moscow-final">
                <td colspan="2">ИТОГО:</td>
                <? foreach($total as $t_arr) {?>
                    <td class="total-td">
                        <? if(!empty($t_arr)) { ?>
                            <table>
                                <?
                                foreach($t_arr as $k => $v) { ?>
                                    <tr>
                                        <td><?=$k?></td>
                                        <td><?=number_format($v, 0, '', ' ')?></td>
                                    </tr>
                                <? }
                                ?>
                            </table>
                        <? } ?>
                    </td>
                <? } ?>
            </tr>
        </table>
        </div>
    <? } else { ?>
        <div class="pacc-err">Не&nbsp;найдено заказов с&nbsp;такими&nbsp;параметрами.</div>
    <? } ?>
    </section>

    <? $html = ob_get_clean();
	
}

//ЗАКАЗЫ ОНЛАЙН-ПОДБОРА
if($data['type'] == 'online') {

$arFilter['IBLOCK_CODE'] = "online_order";
$arFilter['ACTIVE'] = "y";

//дата
    $period_title = '';
    if(!empty($data['date'])) {

        $period_title .= ' за период';

        $sort_date_from = $data['date']['from'];
        $sort_date_to = $data['date']['to'];

        if($sort_date_from!='') {
            $period_title .= ' с '.$sort_date_from;
            //$sort_date_from = explode('.',$sort_date_from);
            //$sort_date_from = $sort_date_from[2].'-'.$sort_date_from[1].'-'.$sort_date_from[0];
            //$arFilter['>=DATE_CREATE'] = $sort_date_from." 00:00:00";
            $arFilter['>=DATE_CREATE'] = $data['date']['from']." 00:00:00";
        }

        if($sort_date_to!='') {
            $period_title .= ' по '.$sort_date_to;
            //$sort_date_to = explode('.',$sort_date_to);
            //$sort_date_to = $sort_date_to[2].'-'.$sort_date_to[1].'-'.$sort_date_to[0];
            //$arFilter['<=DATE_CREATE'] = $sort_date_to." 23:59:59";
            $arFilter['<=DATE_CREATE'] = $data['date']['to']." 23:59:59";
        }
    } else {
        $period_title .= ' за весь период';
    }

    $total = array();

    $res = CIBlockElement::GetList(Array('created'=>'desc'),$arFilter,false, Array(), Array());

    ob_start();?>
<section class="orders-list no-margin">
    <? if($res->SelectedRowsCount() > 0) { ?>
        <div class="order-table-caption">Отчёт по заказам онлайн-подбора<?=$period_title?></div>
        <div class="orders-list-table-wrapper">
            <table class="online-order">
                <tr class="order-table-title">
                    <th>Дата</th>
                    <th>Имя</th>
                    <th>Контактный телефон</th>
                    <th>Город</th>
                    <th>Время для&nbsp;звонка</th>
                    <th>E-mail дилера</th>
                </tr>
                <?
                $total = 0;
                while($item = $res->GetNextElement()) {
                    $item = array_merge($item->GetFields(), $item->GetProperties()); ?>
                    <tr>
                        <td><?=$item['DATE_CREATE']?></td>
                        <td><?=$item['NAME']?></td>
                        <td><?=strpos($item['PHONE']['VALUE'],'+') === true ? $item['PHONE']['VALUE'] : str_phone($item['PHONE']['VALUE'])?></td>
                        <td><?=$item['CITY']['VALUE']?></td>
                        <td><?=$item['TIME']['VALUE']?></td>
                        <td><?=$item['DEALER']['VALUE']?></td>
                    </tr>
                    <?
                    $total++;
                } ?>
                <tr class="order-table-total">
                    <td colspan="3" class="hidden-td"></td>
                    <td colspan="2" class="total-title total-title-online">ВСЕГО ЗАКАЗОВ:</td>
                    <td class="total-title total-value-online"><?=$total?></td>
                </tr>
            </table>
        </div>
    <? } else { ?>
        <div class="pacc-err">Не&nbsp;найдено заказов с&nbsp;такими&nbsp;параметрами.</div>
    <? } ?>
</section>

    <?$html = ob_get_clean();





} 

print json_encode($html);

?>
