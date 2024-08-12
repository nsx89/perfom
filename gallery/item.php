<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock')) exit;
$APPLICATION->SetPageProperty("description", "Европласт - производство полиуретановых изделий, лидер на российском рынке");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}
$id = $_REQUEST['ID'];

$current_res = CIBlockElement::GetByID($id);
if ($ar_res = $current_res->GetNextElement()) {
    $item = array_merge($ar_res->GetFields(), $ar_res->GetProperties());
    /*print_r($item);*/
    $name = $item['~NAME'];
    $pure_name = str_replace('<br>','',$name);

    $APPLICATION->SetTitle($pure_name);
}

$images_path = $_SERVER["DOCUMENT_ROOT"]."/cron/catalog/data/images";
$images_web_path = "/cron/catalog/data/images";
$images_path_new = $_SERVER["DOCUMENT_ROOT"]."/cron_responsive/catalog/data/images";
$images_web_path_new = "/cron_responsive/catalog/data/images";

require_once($_SERVER["DOCUMENT_ROOT"] . "/gallery/img_size.php");
require($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");
$url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']; 

$breadcrumbs_arr = Array(
    Array(
        'name' => 'проекты',
        'link' => '/gallery/',
        'title' => 'проекты',
    ),
    Array(
        'name' => $pure_name,
        'link' => $item['DETAIL_PAGE_URL'],
        'title' => $pure_name,
    ),
);

?>
<section class="news-item">
    <div class="main-banner news-banner gallery-banner">
        <div class="news-banner-cont">
            <div class="main-slide-caption white">
                <h1><?=html_entity_decode($name)?></h1>
                <? if($item['AUTHOR']['VALUE']!='') {?>
                    <div class="gallery-designer">Дизайнер <?=$item['AUTHOR']['VALUE']?></div>
                <? } ?>
            </div>
            <img src="/gallery/objects/<?=str_pad($item['NUMBER']['VALUE'], 3, '0', STR_PAD_LEFT)?>/<?=$item['NUMBER']['VALUE']?>_0.jpg" alt='<?=$pure_name?>'>
            <div class="news-banner-info">
                <?
                $tags = $item['TAGS']['VALUE_ENUM'];
                if(count($tags) > 0) {
                echo '<div class="gallery-tags">';
                    foreach ($tags as $tag) { ?>
                        <div class="gallery-tag"><?=$tag?></div>
                    <? }
                echo '</div>';
                } ?>
                <div class="news-share-wrap">
                    <i class="icon-share" data-type="share"></i>
                    <div class="add-social-wrap" data-type="share-wrap">
                        <div class="ya-share2" id="my-share"></div>
                        <script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
                        <script src="//yastatic.net/share2/share.js"></script>
                        <script>
                            var myShare = document.getElementById('my-share');
                            var share = Ya.share2(myShare, {
                                content: {
                                    url: '<?=$url.$item['DETAIL_PAGE_URL']?>',
                                    title: '<?=$pure_name?>',
                                    description: "",
                                    image: "<?=$url.'/gallery/objects/'.str_pad($item['NUMBER']['VALUE'], 3, '0', STR_PAD_LEFT).'/'.$item['NUMBER']['VALUE'].'_0.jpg'?>",
                                },
                                theme: {
                                    services: 'vkontakte,odnoklassniki,whatsapp,telegram',
                                    lang: 'ru',
                                    size: 'm',
                                    bare: false,
                                    copy: 'extraItem',
                                }
                            });
                        </script>
                    </div>
                </div>
                <?
                $date = $item['DATE']['VALUE'];
                $date = explode('.',$date);
                $day = $date[0].'.'.$date[1];
                $year = $date[2];
                //var_dump($date);
                ?>
                <div class="news-item-date">
                    <?=$day?>.<?=$year?>
                </div>
            </div>
        </div>
    </div>
<div class="gallery-item-main">
<div class="content-wrapper">
    <?if($item['~DETAIL_TEXT']!='' || $item['SOURCE']['VALUE']!='') { ?>
        <div class="gallery-item-description">
            
            <? require_once($_SERVER["DOCUMENT_ROOT"] . "/include/breadcrumbs.php"); ?>

            <?if($item['~DETAIL_TEXT']!='') echo $item['~DETAIL_TEXT']?>
            <? if($item['SOURCE']['VALUE']!='') { ?>
                <p class="gallery-item-source">Источник фото - <?=$item['SOURCE']['VALUE']?></p>
            <? } ?>
        </div>
    <? } else {
        ?>
        <br><br>
        <? require_once($_SERVER["DOCUMENT_ROOT"] . "/include/breadcrumbs.php");
    } ?>
    <div class="gallery-img-wrapper">
        <?
        $arOrder = Array('ID'=>'asc');
        $arFilter = Array("IBLOCK_CODE"=>"gallery_points","PROPERTY_ART_NUMBER"=>$item['NUMBER']['VALUE'],"ACTIVE"=>"Y");
        $ar_res = CIBlockElement::GetList($arOrder,$arFilter,false,Array(),Array());
        $img = '';
        $n = 1;
        $prod_arr = array();
        while($point = $ar_res->GetNextElement()) {
            $point = array_merge($point->GetFields(), $point->GetProperties());

            //$arFilter = Array('IBLOCK_ID' => $iblockid, "ACTIVE" => "Y", "ACTIVE_DATE" => "Y", "PROPERTY_ARTICUL"=>$point['ARTICLE']['VALUE'], "PROPERTY_FLEX"=>$point['FLEX']['VALUE']);
            
            //$arFilter = Array('IBLOCK_ID' => $iblockid, "ACTIVE" => "Y", "ACTIVE_DATE" => "Y", "PROPERTY_ARTICUL"=>$point['ARTICLE']['VALUE']);
            
            $arFilter = Array('IBLOCK_ID' => $iblockid, "!TAGS" => "OFF", "PROPERTY_ARTICUL"=>$point['ARTICLE']['VALUE']);
            
            $db_list = CIBlockElement::GetList(Array("SORT" => "ASC"), $arFilter);
            if ($ob = $db_list->GetNextElement()) {
                $ob = array_merge($ob->GetFields(), $ob->GetProperties());
                $ob_name = __get_product_name($ob);
                $prod_arr[$point['NAME']] = $ob;
            }

            if($img != $point['IMG_NUMBER']['VALUE']) {

                if($img != '') { ?>
                        <div class="gallery-img-resize">
                            <a data-fancybox="gallery" data-src="#img<?=$n-1?>" class="fancybox-btn js-gallery-open" data-touch="false" 
                                data-obj_num="<?= $obj_num ?>"
                                data-img_num="<?= $img_num ?>"
                                data-obj_dir="<?= $obj_dir ?>"
                                data-flex="<?= $flex ?>"
                                >увеличить</a>
                            <span><?= serialize($obj_img) ?></span>
                        </div>
                    </div>
                    <div style="display: none;" id="img<?=$n-1?>" class="gallery-fancybox" data-type="gallery-slide">
                        <?
                        //echo imgSize($obj_num, $img_num, $obj_img, $obj_dir, $flex);
                        ?>
                    </div>
                <? }
                $img = $point['IMG_NUMBER']['VALUE'];
                $obj_num = $point['ART_NUMBER']['VALUE'];
                $img_num = $img;
                $obj_dir = str_pad($point['ART_NUMBER']['VALUE'], 3, '0', STR_PAD_LEFT);
                $obj_img = array();
                $obj_img[] = array($point['NAME'],$point['X']['VALUE'],$point['Y']['VALUE'],$ob_name);
                $flex = $point['FLEX']['VALUE'];
                $img_size = getimagesize($_SERVER["DOCUMENT_ROOT"].'/gallery/objects/'.str_pad($point['ART_NUMBER']['VALUE'], 3, '0', STR_PAD_LEFT).'/'.$point['ART_NUMBER']['VALUE'].'_'.$img.'.jpg');
                $class = 'horizontal';
                if($img_size[1] > $img_size[0]) $class = 'vertical';
                ?>
                    <div class="gallery-img <?=$class?>" data-type="gallery-slide">
                        <img src="/gallery/objects/<?=str_pad($point['ART_NUMBER']['VALUE'], 3, '0', STR_PAD_LEFT)?>/<?=$point['ART_NUMBER']['VALUE']?>_<?=$img?>.jpg" alt='<?=$pure_name?>'>
                        <?

                        $scale = $img_size[1]/520;
                        $left = round(100/$point['X']['VALUE'],2);
                        $top = round(100/$point['Y']['VALUE'],2);

                        // массив элементов X% left,Y% top
                        $items = array();
                        $items[$point['ARTICLE']['VALUE']] = array(
                            "FLEX" => $point['FLEX']['VALUE'],
                            "X" => $left,
                            "Y" => $top,
                            "NUMBER" => $point['NAME'],
                        );
                        echo get_objects_gallery($items);

                        $n++;
                    } else {
                        $obj_img[] = array($point['NAME'],$point['X']['VALUE'],$point['Y']['VALUE'],$ob_name);
                        $left = round(100/$point['X']['VALUE'],2);
                        $top = round(100/$point['Y']['VALUE'],2);
                        // массив элементов X% left,Y% top
                        $items = array();
                        $items[$point['ARTICLE']['VALUE']] = array(
                            "FLEX" => $point['FLEX']['VALUE'],
                            "X" => $left,
                            "Y" => $top,
                            "NUMBER" => $point['NAME'],
                        );
                        echo get_objects_gallery($items);
                    ?>
                <? }
        }
        ?>
        <div class="gallery-img-resize">
            <a data-fancybox="gallery" data-src="#img<?=$n-1?>" class="fancybox-btn js-gallery-open" data-touch="false" 
                data-obj_num="<?= $obj_num ?>"
                data-img_num="<?= $img_num ?>"
                data-obj_dir="<?= $obj_dir ?>"
                data-flex="<?= $flex ?>"
                >увеличить</a>
            <span><?= serialize($obj_img) ?></span>
        </div>
    </div>
    <div style="display: none;" id="img<?=$n-1?>" class="gallery-fancybox" data-type="gallery-slide">
        <?
        //echo imgSize($obj_num,$img_num,$obj_img,$obj_dir,$flex);
        ?>
    </div>
</div>
</div>
</div>
<? if (!empty($prod_arr)) { ?>
    <div class="gallery-products-wrapper">
        <div class="content-wrapper">
            <div class="gallery-products-title">Используемые материалы</div>
            <div class="gallery-products-slider col-prod-tab active" data-type="gallery-prod">
                <?ksort($prod_arr)?>
                <? foreach($prod_arr as $k=>$product) {
                    echo get_product_preview($product, $k);
                } ?>
            </div>

        </div>
    </div>
<? } else { ?>
    <br><br>
<? } ?>
</section>


<script src="/gallery/gallery.js?<?=$random?>"></script>

<?
require($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");
if(defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED===true)
{
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog.php");
}