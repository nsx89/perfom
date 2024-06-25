<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog") || !CModule::IncludeModule("search")) {
    exit;
}

set_time_limit(600);

if(isset($_COOKIE['order_mod_date'])) {
    $sort_date = json_decode($_COOKIE['order_mod_date']);
    //print_r($sort_date);
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
        case '2': {
            $sort_date_from = date("Y-m-d");
            break;
        };
        case '3': {
            $sort_date_from = date('Y-m-d',time() - (6 * 24 * 60 * 60));
            break;
        };
        case '4': {
            $sort_date_from = date('Y-m-d',strtotime("-1 month"));
            break;
        };
    }
}
else {
    //$sort_date_from = date('d.m.Y',time() - (6 * 24 * 60 * 60));
    $sort_date_from = date('Y-m-d',time() - (6 * 24 * 60 * 60));
    $sort_date_to = "";
    $sort_date_val = "3";
}
$arFilter = Array("IBLOCK_CODE"=>"keep_order","ACTIVE"=>"Y");
if($sort_date_from != "") {
    $arFilter['>=PROPERTY_DATE'] = $sort_date_from." 00:00:00";
}

if($sort_date_to != "") {
    $arFilter['<=PROPERTY_DATE'] = $sort_date_to." 23:59:59";
}
$sort_geo = json_decode($_COOKIE['filt_reg']);
$filr_reg = 'Все';
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

ob_start();?>

<div class="pacc-nav-stat">
    <div class="pacc-nav-stat-title">Статистика заказов</div>
    <div class="pacc-nav-stat-item">
        <div class="pacc-stat-lbl">Количество заказов</div>
        <div class="pacc-stat-val"><?=$qty?></div>
    </div>
    <div class="pacc-nav-stat-item">
        <div class="pacc-stat-lbl">Общая сумма заказов</div>
        <div class="pacc-stat-val"><?=__cost_format($total,3109)?></div>
    </div>
    <div class="pacc-nav-stat-item">
        <div class="pacc-stat-lbl">Средний чек по заказу</div>
        <? $middle_price = is_nan($middle_price) ? 0 : $middle_price ?>
        <div class="pacc-stat-val"><?=__cost_format($middle_price,3109)?></div>
    </div>
    <div class="pacc-nav-stat-item">
        <div class="pacc-stat-lbl">Среднее количество позиций в заказе</div>
        <? $middle_qty = is_nan($middle_qty) ? 0 : $middle_qty ?>
        <div class="pacc-stat-val"><?=$middle_qty?></div>
    </div>
    <div class="pacc-nav-stat-item">
        <div class="pacc-stat-lbl">Количество заказов с десктопа</div>
        <div class="pacc-stat-val"><?=$desc?></div>
    </div>
    <div class="pacc-nav-stat-item">
        <div class="pacc-stat-lbl">Количество заказов с мобильной версии</div>
        <div class="pacc-stat-val"><?=$mob?></div>
    </div>
    <div class="e-new-cart-order-dwnld pdf-dwn" data-type="save-pdf">
        <span>Скачать pdf</span>
        <i class="icon-download"></i>
    </div>
</div>
<div class="pacc-nav-bestsells">
    <div class="pacc-nav-bestsells-tit">Самые продаваемые позиции:</div>
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
            $arFilterProd = Array("IBLOCK_ID"=>12,"ID"=>$itemId,"ACTIVE"=>"Y");
            $resProd = CIBlockElement::GetList(Array(),$arFilterProd);
            if($product = $resProd->GetNextElement()) {
                $product = array_merge($product->GetFields(), $product->GetProperties());?>
                <div class="pacc-nav-bestsells-item">
                    <div class="pacc-bestsells-lbl"><a href="<?=__get_product_link($product)?>" target="_blank"><?=__get_product_name($product)?><?if($isSample) echo ' образец'?></a></div>
                    <div class="pacc-bestsells-val"><?=$v?> шт. (<?=round($v/$prod_qty*100,2)?>%)</div>
                </div>
            <? }
        }
        else {
            break;
        }

        ?>

    <? } ?>
</div>

<?
$html['stat'] = ob_get_clean();

ob_start();?>

<section class="orders-list">

    <? if($qty==0) {
        $html['stat'] = ''; ?>
        <p class="pacc-err">Не найдено заказов с такими параметрами!</p>
    <? } else {?>
        <div class="orders-list-table-wrapper">
            <table>
                <tr class="order-table-title">
                    <td>Номер</td>
                    <td>Дата поступления</td>
                    <td>Сумма заказа</td>
                    <td>Регион</td>
                    <td>Статус</td>
                    <td>Подробнее</td>
                </tr>
                <? foreach ($items as $item) { ?>
                    <tr>
                        <td>№ <?=$item['NAME']?></td>
                        <?
                        $date = $item['DATE']['VALUE'];
                        $date = explode(" ",$date);
                        $time = explode(":",$date[1]);
                        $time = $time[0].":".$time[1];
                        ?>
                        <td><?=$date[0]?> <span><?=$time?></span></td>
                        <td><?=$item['TOTAL_SALE']['VALUE']!=''?$item['TOTAL_SALE']['VALUE']:$item['TOTAL']['VALUE']?> <?=$item['CURR']['VALUE']?></td>
                        <?
                        $resReg = CIBlockElement::GetByID($item['CHOOSEN_REG']['VALUE']);
                        $ar_res = $resReg->GetNext();
                        ?>
                        <td><?=$ar_res['NAME'];?></td>
                        <td>
                            <? if($item['STATUS']['VALUE'] != '') { ?>
                                <div class="order-stat"><?=get_order_status($item['STATUS']['VALUE'])?></div>
                            <? } ?>
                        </td>
                        <td><a href="?order=<?=$item['ID']?>"><i class="icon-arrow-right"></i></a></td>
                    </tr>
                <? } ?>
            </table>
        </div>
    <? } ?>
</section>

<?
$html['report'] = ob_get_clean();
print json_encode($html);

