<?require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/catalogue/filter.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/catalogue/sort.php");

if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}

global $APPLICATION;
global $my_city;
$my_city = $APPLICATION->get_cookie('my_city');
require($_SERVER["DOCUMENT_ROOT"] . "/include/top-current-location.php");

$page = $_GET['page'];
$section_id = explode(',',$_GET['sections']);
$filter = $_GET['filter'];
$classes = $_GET['classes'];
$styles = $_GET['styles'];
$all = $_GET['all'];
$type = $_GET['type'];

$sec_desc = '';
if(count($section_id) == 1) {
    //раздел
    $arFilter = Array('IBLOCK_ID'=>IB_CATALOGUE, 'ID'=>$section_id, 'ACTIVE'=>'Y');
    $db_list = CIBlockSection::GetList(Array(), $arFilter, false, array("IBLOCK_ID","UF_DESCRIPTION_PERFOM","DESCRIPTION"));
    while($section_item = $db_list->GetNext()) {
        //print_r($section_item);
        if(!empty($section_item['UF_DESCRIPTION_PERFOM'])) {
            $sec_desc = htmlspecialchars_decode($section_item['UF_DESCRIPTION_PERFOM']);
        } elseif(!empty($section_item['~DESCRIPTION'])) {
            $sec_desc = $section_item['~DESCRIPTION'];
        }
    }
}

//сортировка новинки
$is_new_sort = false;
$sort_params = json_decode($_COOKIE['sort_params']);
if($sort_params->main_param == 5 && !$sort_params->prop_param) $is_new_sort = true;

$data_onpage = 12;
$arNavStartParams = Array("nPageSize"=>$data_onpage, "iNumPage" => $page);
if($all || $is_new_sort) $arNavStartParams = Array();//показать все //сортировка новинки


$CATALOG_FILTER = CatalogFilter($section_id, $filter, $classes, $styles);
$SORTING = getSort($section_id);

//сортировка новинки
if($is_new_sort) $CATALOG_FILTER['>DATE_CREATE'] = date('d.m.Y', strtotime('-150 days'));

$db_list = CIBlockElement::GetList($SORTING, $CATALOG_FILTER, false, $arNavStartParams);
$item_count = $db_list->SelectedRowsCount();
while($ob = $db_list->GetNextElement()) {
    $product_items[] = array_merge($ob->GetFields(), $ob->GetProperties());
}
//сортировка новинки
/*if($is_new_sort) {
    unset($CATALOG_FILTER['>DATE_CREATE']);
    $CATALOG_FILTER['<=DATE_CREATE'] = date('d.m.Y', strtotime('-150 days'));
    $db_list = CIBlockElement::GetList(array_merge(Array('sort'=>'asc'),$SORT), $CATALOG_FILTER, false, $arNavStartParams);
    $item_count += $db_list->SelectedRowsCount();
    while($ob = $db_list->GetNextElement()) {
        $product = array_merge($ob->GetFields(), $ob->GetProperties());
        $product_items[] = $product;
    }
}*/
//print_r($SORTING);
//print_r($CATALOG_FILTER);
//print_r($arNavStartParams);
//var_dump($my_city);
ob_start();

$from = ($page - 1) * $data_onpage;
$to = $page * $data_onpage - 1;
foreach ($product_items as $k=>$product) {
    if($is_new_sort) { //сортировка новинки
        if($all || $k >= $from && $k <= $to) {
            echo get_product_preview($product);
        }
    } else {
        echo get_product_preview($product);
    }
}
if($item_count == 0) { ?>
    <div class="e-new-item-empty">
        По результатам фильтрации ничего не&nbsp;найдено либо группа товаров не&nbsp;обслуживается в&nbsp;вашем&nbsp;регионе.        
    </div>
<? }

?>
<div style="display: none;"><? print_r($SORTING); ?></div>
<?

$html = ob_get_clean();

if(!$type) {
    //если не меняется категория
    print json_encode(Array('items'=>$html,'qty'=>$item_count,'desc'=>$sec_desc,'sorting'=>$SORTING));
} elseif($type == 'rebuild') {
    //меняется категория - меняем также фильтры и сортировку
    if(!$all) {
        $product_items_full = Array();
        $CATALOG_FILTER_FULL = CatalogFullFilter($section_id, $filter, $classes, $styles);
        $db_list_full = CIBlockElement::GetList($SORTING, $CATALOG_FILTER_FULL, false);
        while($ob = $db_list_full->GetNextElement()) {
            $product = array_merge($ob->GetFields(), $ob->GetProperties());
            $product_items_full[] = $product;
        }
    } else {
        $product_items_full = $product_items;
    }
    $sort = renderSort($section_id);
    $filters = renderFilters($section_id,$product_items_full);

    // меняем хлебные крошки
    $sec_list = CIBlockSection::GetNavChain(IB_CATALOGUE, $section_id[0], array('NAME','CODE'), true);
    $breadcrumbs_arr = get_breadcrumbs_arr($sec_list);
    ob_start();
    require_once($_SERVER["DOCUMENT_ROOT"] . "/include/breadcrumbs.php");
    $breadcumbs = ob_get_clean();

    print json_encode(Array('items'=>$html,'sort'=>$sort,'filters'=>$filters,'qty'=>$item_count,'desc'=>$sec_desc,'sorting'=>$SORTING, 'breadcrumbs' => $breadcumbs));
} elseif($type == 'newFilters') {
    print json_encode(Array('items'=>$html,'qty'=>$item_count,'desc'=>$sec_desc,'sorting'=>$SORTING));
}

