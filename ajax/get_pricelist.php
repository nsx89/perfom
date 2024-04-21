<?require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");

require_once($_SERVER["DOCUMENT_ROOT"] . "/catalogue/filter.php");

if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {

    exit;

}

$typelist = $_GET['typelist'];

if($typelist=="pricelist-int") {
    $tit = "Интерьерная коллекция";
    $type_id = 189;
    $type = "int";
}
if($typelist=="pricelist-fac") {
    $tit = "Фасадная коллекция";
    $type_id = 1562;
    $type = "front";
}

global $my_city;
$my_city = $APPLICATION->get_cookie('my_city');
$loc = null;
$arFilter = Array('IBLOCK_ID' => 7, 'ACTIVE' => 'Y', 'ID' => $my_city);
$db_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter);
$loc = $db_list->GetNextElement();
if (!$loc) {
    $arFilter = Array('IBLOCK_ID' => 7, 'ACTIVE' => 'Y', 'CODE' => 'moskva');
    $db_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter);
    $loc = $db_list->GetNextElement();
}
$loc = array_merge($loc->GetFields(), $loc->GetProperties());

$discreg = null;
$arFilter = Array('IBLOCK_ID' => 8, 'ACTIVE' => 'Y', 'ID' => $loc['discountregion']['VALUE']);
$db_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter);
$discreg = $db_list->GetNextElement();
$discreg = array_merge($discreg->GetFields(), $discreg->GetProperties());

$products = array();

$currency_infо = get_currency_info($loc['country']['VALUE']);
$arFilter_element = $currency_infо['filter'];
$curr = $currency_infо['curr'];
$curr_abbr = $currency_infо['abbr'];

$arFilter = Array('IBLOCK_ID' => 12, 'SECTION_ID'=> $type_id, 'ACTIVE' => 'Y', 'INCLUDE_SUBSECTIONS' => 'Y', $arFilter_element);
$_element = CIBlockElement::GetList(array('PROPERTY_ARTICUL'=>'ASC'), $arFilter);
$n = 0;
while ($item = $_element->GetNextElement()) {
    //if($n < 30) {
    $item = array_merge($item->GetFields(), $item->GetProperties());
    if ($item['IBLOCK_SECTION_ID'] != 1587) //исключаем клей
        $products[] = array('ARTICUL'=>$item['ARTICUL']['VALUE'],'NAME'=>$item['NAME'],'FLEX'=>$item['FLEX']['VALUE'],'COMPOSITEPART'=>$item['COMPOSITEPART']['VALUE'],'PRICE'=>__get_product_cost($item));
    $n++;
    //}
}?>

<? ob_start(); ?>

<div class="pricelist-info">
    <div class="pricelist-info-left">
        <p>Прайс-лист <?=$tit?></p>
        <p>Валюта: <?=$curr?></p>
        <div class="pricelist-reg"><i class="icon-geo"></i><?=$loc['NAME']?></div>
    </div>
    <a class="dwnld-btn dwnld-models-btn" data-type="pricelist-link" data-typelist="<?=$type?>" data-city="<?=$my_city?>" download target="_blank"><i class="icon-download"></i>скачать pdf</a>
</div>

<table class="pricelist-table-new">
    <tr>
        <th>Артикул</th>
        <th>Наименование</th>
        <th>Цена, <?=$curr_abbr?></th>
    </tr>
<?
    $prev_name = "";
    $i = 0;
    foreach($products as $product) {
        if($product['COMPOSITEPART'] == "") {
            //name
            if($product['FLEX'] == "Y") {
                $name = trim(str_replace("FLEX", "", $product['NAME']));
                $short_name = $name;
                $name .= " гибкий";
            }
            else {
                $name = $product['NAME'];
                $short_name = $name;
            }
            if ($prev_name != $short_name && $i != 0) echo "<tr><td></td><td></td><td></td></tr>";
            echo "<tr>";
            if($product['ARTICUL'] == 'm_comp_b201') {
                echo "<td><span>".$product['ARTICUL']."</span></td>";
            } else {
                echo "<td>".$product['ARTICUL']."</td>";
            }
            echo "<td>".$name."</td>";
            echo "<td>".number_format($product['PRICE'],2,'.',' ')."</td>";
            echo '</tr>';
            $prev_name = $short_name;
            $i++;

        }
    }
    echo "</table>"
?>

<?

$html = ob_get_clean();

print json_encode($html);

?>