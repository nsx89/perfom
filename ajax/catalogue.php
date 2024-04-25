<?require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/catalogue/filter.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/catalogue/sort.php");

if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}
require($_SERVER["DOCUMENT_ROOT"] . "/include/top-current-location.php");
global $my_city;

$my_city = $APPLICATION->get_cookie('my_city');

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
    $db_list = CIBlockSection::GetList(Array(), $arFilter, false, array('UF_*'));
    while($section_item = $db_list->GetNext()) {
        if($section_item['~DESCRIPTION']) {
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
$SORT = getSort($section_id);

/* --- СОРТИРОВКА НОВАЯ ДЛЯ 6. --- */

$SORT_FIRST = array('PROPERTY_SORT_FIRST'=>'DESC');
$SORT_SECOND = array('PROPERTY_SORT'=>'DESC');
$CATEGORY_ID = (int)$section_id;
switch ($CATEGORY_ID) {
    case '1542': 
        $SORT_CATEGORY = array('PROPERTY_SORT1'=>'DESC');
        break;
    case '1544': 
        $SORT_CATEGORY = array('PROPERTY_SORT2'=>'DESC');
        break;
    case '1546': 
        $SORT_CATEGORY = array('PROPERTY_SORT3'=>'DESC');
        break;
    case '1601': 
        $SORT_CATEGORY = array('PROPERTY_SORT4'=>'DESC');
        break;
    default: 
        $SORT_CATEGORY = array('PROPERTY_SORT1'=>'DESC');
        break;
}
$SORTING = array_merge($SORT_FIRST, $SORT_SECOND, $SORT_CATEGORY, $SORT);

//$SORTING = $SORT;

/* --- // --- */

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
$html = ob_get_clean();

if(!$type) {
    //если не меняется категория
    print json_encode(Array('items'=>$html,'qty'=>$item_count,'desc'=>$sec_desc));
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
    print json_encode(Array('items'=>$html,'sort'=>$sort,'filters'=>$filters,'qty'=>$item_count,'desc'=>$sec_desc));
} elseif($type == 'newFilters') {
    print json_encode(Array('items'=>$html,'qty'=>$item_count,'desc'=>$sec_desc));
}

