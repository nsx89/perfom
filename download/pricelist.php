<?require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
$typelist = $_GET['typelist'];

if($typelist=="int") {
   $APPLICATION->SetTitle("Прайс-лист интрьерной коллекции");
   $APPLICATION->AddChainItem("Прайс-лист интрьерной коллекции","#");
   $tit = "Интерьерная коллекция";
   $type_id = 189; 
} 
if($typelist=="front") {
    $APPLICATION->SetTitle("Прайс-лист фасадной коллекции");
    $APPLICATION->AddChainItem("Прайс-лист фасадной коллекции","#");
    $tit = "Фасадная коллекция";
    $type_id = 1562; 
} 


if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {

    exit;

}
require($_SERVER["DOCUMENT_ROOT"] . "/include/catalogue/header.php");


global $my_city; // регион
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
    }

?>
<div id="middle" class="middle-price">
    <div class="pricelist-preloader">
    <img src="/images/AjaxLoader.gif">
    <p>Формирование прайса может занять некоторое время</p>        
    </div>
   <h1>Прайс-лист<br><?=$tit?></h1> 
   
   <a target="_blank" class="pricelist-link"><i class="icon-e-dpf-doc"></i>Скачать pdf</a>
   <div class="pricelist-attr-block">
       <p class="pricelist-attr"><span>Валюта:</span> <?=$curr?></p>
       <p class="pricelist-attr"><?=$loc['NAME']?></p>
   </div>

   <table class="pricelist-table">
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
            echo "<td>".$product['ARTICUL']."</td>";
            echo "<td>".$name."</td>";
            echo "<td>".number_format($product['PRICE'],2,'.',' ')."</td>";
            echo '</tr>';
            $prev_name = $short_name;
            $i++; 

        }
    }
    echo "</table>"
?>
</div>
<script>
    $('.pricelist-link').click(function() {
        $('.pricelist-preloader').show();
            $.get('/ajax/save_pdf_pricelist.php?typelist=<?=$typelist?>&city=<?=$my_city?>', function (data) {
                //console.log(data);
                if(data == "") {
                    $('.pricelist-preloader').hide();
                    alert("Произошла ошибка. Пожалуйста, повторите попытку.");
                }
                else {
                   window.location.href = data;
                   $('.pricelist-preloader').hide(); 
                } 
            });
            return false;
        });
</script>


<? require($_SERVER["DOCUMENT_ROOT"] . "/include/catalogue/footer.php"); ?>