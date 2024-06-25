<?
$arFilter = Array("IBLOCK_CODE"=>"keep_order","ID"=>$_REQUEST['order']);
$res = CIBlockElement::GetList(Array(),$arFilter);
$item = $res->GetNextElement();
$item = array_merge($item->GetFields(), $item->GetProperties());
?>
<section class="orders-list order-item-list no-margin">
    <div class="orders-list-table-wrapper">
    <table>
        <tr class="order-table-title">
            <td>№</td>
            <td>Наименование товара</td>
            <td>Цена</td>
            <td>Количество</td>
            <td>Сумма</td>
        </tr>
        <?
        $arFilter = Array("IBLOCK_CODE"=>"order_products","PROPERTY_ORDER_NUMBER"=>$item['NAME'],"ACTIVE"=>"Y");
        $res = CIBlockElement::GetList(Array(),$arFilter);
        $n = 1;
        while($product = $res->GetNextElement()) {
            $product = array_merge($product->GetFields(), $product->GetProperties());
            $isSample = false;
            $productId = $product['NAME'];
            if(strpos($product['NAME'],'s') !== false) {
                $isSample = true;
                $productId = substr($product['NAME'],1);
            }
            $arProdFilter = Array("IBLOCK_ID"=>IB_CATALOGUE,"ID"=>$productId,"ACTIVE"=>"Y");
            $resProd = CIBlockElement::GetList(Array(),$arProdFilter);
            $prod_arr = $resProd->GetNextElement();
            $prod_arr = array_merge($prod_arr->GetFields(), $prod_arr->GetProperties());
            ?>
            <tr>
                <td><?=$n++?></td>
                <td><a href="<?=__get_product_link($prod_arr)?>" target="_blank"><?=__get_product_name($prod_arr)?><?if($isSample) echo ' образец'?></a></td>
                <td><?=__cost_format($product['PRICE']['VALUE'],$item['CHOOSEN_REG']['VALUE'])?></td>
                <td><?=$product['QTY']['VALUE']?></td>
                <? $total = $product['PRICE']['VALUE']*$product['QTY']['VALUE'];?>
                <td><?=__cost_format($total,$item['CHOOSEN_REG']['VALUE'])?></td>
            </tr>
        <? } ?>
    </table>
    </div>
</section>