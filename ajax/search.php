<?
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog") || !CModule::IncludeModule("search")) {
exit;
}
require($_SERVER["DOCUMENT_ROOT"] . "/include/top-current-location.php"); 
global $loc;
$my_city = $APPLICATION->get_cookie('my_city');

$str_query = mb_convert_case($_REQUEST['q'], MB_CASE_LOWER, "UTF-8");
$obSearch = new CSearch;
$obSearch->Search(array('QUERY' => $str_query, 'SITE_ID' => LANG, 'MODULE_ID' => 'iblock', 'CHECK_DATES' => 'Y'), array("NAME"=>"ASC"));
$res = array();
$res_short = array();
$glue_arr = get_glue_arr();

$result = [];

while($arResult = $obSearch->GetNext()) {
//товары
if($arResult['PARAM2'] == IB_CATALOGUE) {

    $arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, "ACTIVE" => "Y", "ACTIVE_DATE" => "Y", 'ID'=>$arResult['ITEM_ID'], '!SUBSECTION'=>1614);
    $arFilter_city = Array('IBLOCK_ID' => 7, 'ACTIVE' => 'Y', 'ID' => $my_city);
    $db_list_city = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter_city);
    $city_cur = $db_list_city->GetNextElement();
    if ($city_cur) {
        $city_cur = array_merge($city_cur->GetFields(), $city_cur->GetProperties());
        $currency_infо = get_currency_info($city_cur['country']['VALUE']);
        $arFilter_element = $currency_infо['filter'];
        if($arFilter_element) $arFilter[] = array("LOGIC" => "OR", $arFilter_element, array(">PROPERTY_COMPOSITEPART" => 0));
    }
    $_db = CIBlockElement::GetList(array(), $arFilter);
    $_ob = $_db->GetNextElement();
    if (!$_ob) continue;
    $_ob = array_merge($_ob->GetFields(), $_ob->GetProperties());
    if($_ob['IBLOCK_SECTION_ID'] == 1587 && !in_array($_ob['ID'],$glue_arr)) continue;
    $res['prod'][] = $_ob;

    $prod = array();
    $prod['prod'] = $_ob;

    $res_short[] = array_merge($arResult,$prod);
}
//галерея, новости
/*if( $arResult['PARAM2'] == 46 || $arResult['PARAM2'] == 48 ) {
    $res['art'][] = $arResult;
    $res_short[] = $arResult;
}*/

}

$result['qty'] = count($res_short);

ob_start();

//ничего не найдено
if($result['qty'] == 0) {?>

<? } else { ?>

    <?/*<div class="search-tabs-wrap">
        <div class="search-tab<?if(count($res['prod']) != 0) echo ' active'?>" data-type="search-tab" data-id="#prod">Товары (<?=count($res['prod'])?>)</div>
        <div class="search-tab<?if(count($res['prod']) == 0 && count($res['art']) != 0) echo ' active'?>" data-type="search-tab" data-id="#art">Статьи (<?=count($res['art'])?>)</div>
    </div>*/?>
    <div class="search-tabs-cont-wrap">
        <div class="search-tab-cont<?if(count($res['prod']) != 0) echo ' active'?>" id="prod">
            <? if(count($res['prod']) == 0) { ?>
                <div class="search-not-found">По вашему запросу товаров не&nbsp;найдено.</div>
            <? } else { ?>
                <div class="col-prod-tab">
                    <div>
                    <?
                    $i = 0;
                    foreach($res['prod'] as $k => $item) {
                        $checkPrice = __get_product_cost($item);
                        if (($checkPrice == 0)||($checkPrice == '')) continue;
                        $i++;
                        echo get_product_preview($item);
                        if ($i == $_REQUEST['prod'] && $_REQUEST['all'] != 'y') break;
                    } ?>
                    </div>
                </div>
            <? } ?>
        </div>
        <div class="search-tab-cont<?if(count($res['prod']) == 0 && count($res['art']) != 0) echo ' active'?>" id="art">

            <? if(count($res['art']) == 0) { ?>
                <div class="search-not-found">По вашему запросу статей не&nbsp;найдено.</div>
            <? } else { ?>
                <div>
                <?
                $i = 0;
                foreach($res['art'] as $k => $item) {
                    //print_r($item);
                    $arFilter = Array("IBLOCK_ID"=>$item['PARAM2'],"ID"=>$item['ITEM_ID'], "ACTIVE"=>"Y");
                    $db_res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), Array());
                    while($ob = $db_res->GetNextElement()) {
                        $db_item = array_merge($arFields = $ob->GetFields(),$arFields = $ob->GetProperties());
                        //print_r($db_item);
                        if($item['PARAM2'] == 48) {
                            $tag = 'блог';
                            $tag_class = 'exhibitions';
                            $date = $db_item['DATE']['VALUE'];
                            $img_path = '/mag/'.$db_item['IBLOCK_CODE'].'/'.$db_item['FOLDER']['VALUE'].'/'.$db_item['THUMB']['VALUE'];
                            $img_path = get_resized_img($img_path,407,273);
                        }
                        if($item['PARAM2'] == 46) {
                            $tag = 'проекты';
                            $tag_class = 'trends';
                            $date = $db_item['DATE']['VALUE'];
                            $img_path = "/gallery/objects/".str_pad($db_item['NUMBER']['VALUE'], 3, '0', STR_PAD_LEFT)."/".$db_item['NUMBER']['VALUE']."_0.jpg";
                            $img_path = get_resized_img($img_path,407,273);
                        }
                        $i++;
                    ?>
                    <div class="new-main-new">
                        <a href="<?=$item['~URL']?>" class="pc-3w-elem-a">
                            <div class="img-wrap">
                                <div class="img-wrap-cont">
                                    <img src="<?=$img_path?>">
                                </div>
                            </div>
                            <div class="news-tag-wrap">
                                <div class="news-tag <?=$tag_class?>"><?=$tag?></div>
                            </div>
                            <div class="news-search-txt">
                                <h2 class="new-main-new-title"><?=$item['~TITLE']?></h2>
                                <div class="search-art-text">
                                    <?=htmlspecialchars_decode($item['~BODY_FORMATED'])?>
                                </div>
                                <span class="new-main-new-date"><?=$date?></span>
                            </div>
                        </a>
                    </div>
                    <? } ?>
                    <? if ($i == $_REQUEST['art'] && $_REQUEST['all'] != 'y') break;
                } ?>
                </div>
            <? } ?>
        </div>
    </div>
    <?if($_REQUEST['all'] != 'y') { ?>
        <a class="search-aqs-btn" href="/search/?q=<?=$_REQUEST['q']?>">Перейти на страницу поиска</a>
    <? } ?>
<?

}
$result['cont'] = ob_get_clean();



print json_encode( $result);
