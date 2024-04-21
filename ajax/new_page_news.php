<?require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");

//require_once($_SERVER["DOCUMENT_ROOT"] . "/catalogue/filter.php");

if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {

    exit;

}
$quant_news = 12; //количество статей на странице

$section_id = $_GET['category'];
$page = $_GET['page'];
$offset = ($page-1)*$quant_news;
$city = $_GET['city'];
$all = $_GET['all'];



//выводим id категорий в виде массива, если категория не одна
$ar_cat = explode(',',$section_id);
$count = count($ar_cat);

$iblock_id = get_news_iblocks();

$property_enums = CIBlockPropertyEnum::GetList(Array(), Array("CODE"=>"NEWS_TAGS"));

$tags = Array("LOGIC" => "OR");
for($n = 0; $n < $count; $n++) {
    while($enum_fields = $property_enums->GetNext()) {
      if($enum_fields['XML_ID'] == $ar_cat[$n]) {
        $cat_name = $enum_fields['VALUE'];
        break;
      }
    }
    $tags[$n+1] = Array('PROPERTY_NEWS_TAGS_VALUE'=>$cat_name);
}
//print_r($tags);

$arOrder = Array('PROPERTY_CITY'=>'asc,nulls','PROPERTY_DATE'=>'desc');
$arFilter = Array("SECTION_ID"=>'news-new',$iblock_id,"ACTIVE"=>"Y","ACTIVE_DATE"=>"Y",Array("LOGIC" => "OR",Array('PROPERTY_CITY.ID'=>$city),Array('PROPERTY_CITY.ID'=>false)),$tags);
$arNavStartParams = Array("nPageSize"=>$quant_news,"iNumPage" => $offset/$quant_news+1);
if($all) $arNavStartParams = Array();//показать все
$arSelect = Array();

$ar_res = CIBlockElement::GetList($arOrder,$arFilter,false,$arNavStartParams,$arSelect);

$item_count = $ar_res->SelectedRowsCount();

if ($ar_res->SelectedRowsCount() == $offset) {
    echo json_encode('По вашему запросу ничего не найдено');
    die(); // убийство :(
}
?>

<? ob_start(); ?>
<? while($ob = $ar_res->GetNextElement()): ?>

<?$item = array_merge($ob->GetFields(), $ob->GetProperties()); ?>

<div class="new-main-new">
    <?
    if($item['NODETAIL']['VALUE']!='Y') {
        if($item['LINK']['VALUE']!='') {
            echo ' <a href="'.$item['LINK']['VALUE'].'" target="_blank">';
        }
        else {
            echo ' <a href="'.$G_DOMAIN.$item['DETAIL_PAGE_URL'].'" class="pc-3w-elem-a">';
        }
    }
    ?>

    <div class="img-wrap<?if($item['HORIZONTAL']['VALUE']=='Y') echo ' img-wrap-h'?>">
        <div class="img-wrap-cont">


        <? if($item['UNIQUE']['VALUE']!=='Y'): ?>
            <?
            $img_path = '/mag/'.$item['IBLOCK_CODE'].'/'.$item['FOLDER']['VALUE'].'/'.$item['THUMB']['VALUE'];
            $img_path = get_resized_img($img_path,407,273);
            ?>
            <img src="<?=$img_path?>?v=5" alt="<?=$item['NAME']?>" />
        <? else: ?>
            <?
            $img_path = '/mag/'.$item['IBLOCK_CODE'].'/'.$item['FOLDER']['VALUE'].'/images/'.$item['THUMB']['VALUE'];
            $img_path = get_resized_img($img_path,407,273);
            ?>
            <img src="<?=$img_path?>?v=5" alt="<?=$item['NAME']?> />
        <?endif;?>
        </div>
    </div>
    <?
    $tags = $item['NEWS_TAGS']['VALUE'];
    if(!(empty($tags))) {
        echo '<div class="news-tag-wrap">';
        foreach ($tags as $t => $tag) { ?>
          <div class="news-tag <?=$item['NEWS_TAGS']['VALUE_XML_ID'][$t]?>"><?=$tag?></div>
        <? }
        echo '</div>';
    } ?>
    <h2 class="new-main-new-title"><?=$item['~NAME']?></h2>
    <?/*<div class="new-main-new-desc"><?=$item['PREVIEW_TEXT'];?></div>*/?>
    <span class="new-main-new-date"><?=$item['DATE']['VALUE'];?></span>

    <?if($item['NODETAIL']['VALUE']=='N') {?>
    </a>
    <? } ?>
</div>

<? endwhile; ?>
<?

$html = ob_get_clean();

print json_encode($html);

?>
