<?require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");

require_once($_SERVER["DOCUMENT_ROOT"] . "/catalogue/filter.php");

if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {

    exit;

}
$item_count = 8; //количество статей на странице

$page = $_GET['page'];
$offset = ($page-1)*$item_count;
$filter = $_GET['filter'];
$all = $_GET['all'];

$ar_filter = explode(',',$filter);

$new_filter = array();
foreach($ar_filter as $v) {
    switch ($v) {
        case '':
            $new_filter[] = 'Нет';
            break;
        case 1:
            $new_filter = array();
            break;
        default:
            $new_filter[] = $v;
            break;
    }
}

$arOrder = Array('PROPERTY_DATE'=>'desc');
$arFilter = Array("IBLOCK_CODE"=>"gallery_articles","ACTIVE"=>"Y",'PROPERTY_TAGS'=>$new_filter);
$arNavStartParams = Array("nPageSize"=>$item_count,"iNumPage" => $offset/$item_count+1);
if($all) $arNavStartParams = Array();//показать все
$arSelect = Array();
$ar_res = CIBlockElement::GetList($arOrder,$arFilter,false,$arNavStartParams,$arSelect);
$item_qty = $ar_res->SelectedRowsCount();

if ($item_qty == $offset || $item_qty == 0) {
    ob_start(); ?>
    <div class="no-gallery-item">
    Не найдено объектов с указанными параметрами.
    </div>
    <?
    $html = ob_get_clean();
    print json_encode($html);
    die(); // убийство :(
}
?>

<? ob_start(); ?>

<?$i = 1;?>
<?
//определяем порядковые номера узких статей
$gallery_order = Array(
    Array(1,3,6,7),
    Array(3,4,7,8),
    Array(1,2,4,6),
);
$current_order = $gallery_order[rand(0,count($gallery_order) - 1)];
?>
<? while ( $ob = $ar_res->GetNextElement() ) {
    $item = array_merge($ob->GetFields(), $ob->GetProperties());
    $name = $item['~NAME'];
    $pure_name = str_replace('<br>','',$name);
    $class = '';
    if(in_array($i,$current_order)) $class = ' narrow';
    $img_size = getimagesize($_SERVER["DOCUMENT_ROOT"]."/gallery/objects/".str_pad($item['NUMBER']['VALUE'], 3, '0', STR_PAD_LEFT)."/".$item['NUMBER']['VALUE']."_0.jpg");
    if($img_size[1] > $img_size[0]) $class .= ' vertical';
    ?>
    <div class="gallery-item<?=$class?>">
        <a href="<?=$item['DETAIL_PAGE_URL']?>"></a>
        <div class="img-wrap">
            <div class="img-wrap-cont">
                <?
                $tags = $item['TAGS']['VALUE_ENUM'];
                if($tags) { ?>
                    <div class="gallery-tags">
                        <? foreach ($tags as $tag) { ?>
                            <div class="gallery-tag"><?=$tag?></div>
                        <? } ?>
                    </div>
                <? } ?>
                <?$img_src = "/gallery/objects/".str_pad($item['NUMBER']['VALUE'], 3, '0', STR_PAD_LEFT)."/".$item['NUMBER']['VALUE']."_0.jpg";?>
                <img src="<?=$img_src?>" alt='<?=$pure_name?>'>
            </div>
        </div>
        <div class="item-gallery-desc">
            <h2><?=$pure_name?></h2>
            <span><?=$item['DATE']['VALUE']?></span>
        </div>
    </div>
    <?$i++;?>
    <?if($i > $item_count) $i = 1;?>
<? }  ?>

<?

$html = ob_get_clean();

print json_encode($html);

?>
