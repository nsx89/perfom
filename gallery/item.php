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

function get_objects_gallery($items = array()) {
    global $my_city;
    $arProps[] = array(
        "LOGIC" => "OR"
    );
    foreach($items as $key => $item) {
        $arProps[] = array("=PROPERTY_ARTICUL" => $key,"PROPERTY_FLEX" => $item['FLEX']);
    }
    $arFilterItems = Array('IBLOCK_ID' => IB_CATALOGUE, "ACTIVE" => "Y", "ACTIVE_DATE" => "Y", "!PROPERTY_HIDE_GENERAL" => "Y", $arProps);
    $db_list = CIBlockElement::GetList(array(), $arFilterItems, false);

    ob_start();

    $array_item_comp = array(); // убираем дубли
    while($ob = $db_list->GetNextElement()) {
        $item = array_merge($ob->GetFields(), $ob->GetProperties());

        if (in_array($item['ARTICUL']['VALUE'],$array_item_comp)) continue;
        $array_item_comp[] = $item['ARTICUL']['VALUE'];

        $iscomp = 0;
        if ($item['COMPOSITEPART']['VALUE']) $iscomp = 1;

        // Группа элемента с ID и Названием
        $res = CIBlockElement::GetByID($item['ID']);
        if($arRes = $res->Fetch()) {
            $res = CIBlockSection::GetByID($arRes["IBLOCK_SECTION_ID"]);
            if($arRes = $res->Fetch()) {
                $section_id = $arRes["ID"];
                $arFilterSection = Array('IBLOCK_ID'=>IB_CATALOGUE, 'ACTIVE'=>'Y', 'ID'=>$section_id);
                $db_list_section = CIBlockSection::GetList(Array(), $arFilterSection, false, array('UF_*'));
                $last_section = $db_list_section->GetNext();
            }
        }
        // если композит или нет цены, укороченная версия без линка
        $no_show = false;
        if($item['IBLOCK_SECTION_ID'] == 1614 || $item['IBLOCK_SECTION_ID'] == 1615 ||
            $item['IBLOCK_SECTION_ID'] == 1616 || $item['IBLOCK_SECTION_ID'] == 1617 || (_makeprice(CPrice::GetBasePrice($item['ID']))['PRICE'] <= 0)) $no_show = true;

        if ($no_show) $url_item = '#';
        else $url_item = __get_product_link($item,$test);

        $web_path = web_path($item);
        $img_path = get_resized_img($web_path);

// Логика изображений превью под вопросом
        $images_path = $_SERVER["DOCUMENT_ROOT"]."/cron/catalog/data/images";
        $images_web_path = "/cron/catalog/data/images";
        $files_by_type = array();

        $img_pre = 200; // Вторая сцена в приоритет - эксперементально

        $img_pre_old = substr($img_pre, 0, 2);

        $path = $images_path . "/" . $img_pre . "/" . $item['ARTICUL']['VALUE'] . '.' . $img_pre . '.png';
        $web_path = $images_web_path . "/" . $img_pre . "/" . $item['ARTICUL']['VALUE'] . '.' . $img_pre . '.png';

        if (!file_exists($path)) {
            $path = $images_path . "/" . $img_pre_old . "/" . $item['ARTICUL']['VALUE'] . '.' . $img_pre_old . '.png';
            $web_path = $images_web_path . "/" . $img_pre_old . "/" . $item['ARTICUL']['VALUE'] . '.' . $img_pre_old . '.png';
        }

        if (file_exists($path)) {
            $files[] = $web_path;
            $files_by_type[$img_pre] = $web_path;
        }

        if ($files_by_type['200']) $img_path =  get_resized_img($files_by_type['200']);

        // для каминов 100 отдельно, а то может мутить придеться :)))
        if (($item['ARTICUL']['VALUE'] == '1.64.801') || ($item['ARTICUL']['VALUE'] == '1.64.803')) {
            $web_path = web_path($item);
            $img_path = get_resized_img($web_path);
        }

        ?>

        <div class="obj-elem-size show-materials-item" style="left:<?=$items[$item['ARTICUL']['VALUE']]['X']?>%;top:<?=$items[$item['ARTICUL']['VALUE']]['Y']?>%;"
             data-type="prod-prev" data-id="<?=$item['ID']?>"
             data-name="<?=__get_product_name($item)?>"
             data-code="<?=$item['INNERCODE']['VALUE']?>"
             data-price="<?=_makeprice(CPrice::GetBasePrice($item['ID']))['PRICE']?>"
             data-curr="<?=getCurrency($my_city)?>"
             data-cat="<?=$last_section['ID']?>"
             data-cat-name="<?=$last_section['NAME']?>"
             data-iscomp="<?=$iscomp?>">

            <div class="element-number-item show-materials-point" data-type="gallery-number"><span><?=$items[$item['ARTICUL']['VALUE']]['NUMBER']?></span></div>
            <div class="show-materials-popup gallery">
                <div class="show-materials-popup-img">
                    <a href="<?=$url_item?>"></a>
                    <img src="<?=$img_path?>" alt="<?=__get_product_name($item)?>">
                </div>
                <div class="show-materials-popup-title">
                    <a href="<?=$url_item?>"><?=__get_product_name($item)?></a>
                </div>
                <div class="show-materials-popup-bottom">
                    <? if (!$no_show) { ?>
                        <div class="show-materials-popup-price"><?=__cost_format(_makeprice(CPrice::GetBasePrice($item['ID']))['PRICE'])?></div>
                        <div class="show-materials-popup-btns">
                            <div class="show-materials-popup-favorite" data-type="favorite" data-user="no-user">
                                <i class="icon-star"></i>
                            </div>
                            <div class="show-materials-popup-add" data-type="cart-add">
                                <i class="icon-plus"></i>
                            </div>
                        </div>
                    <? } ?>
                </div>

            </div>
        </div>


    <?	}

    $html = ob_get_clean();

    return $html;
}

require_once($_SERVER["DOCUMENT_ROOT"] . "/gallery/img_size.php");
require($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");
$url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];

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
            <?if($item['~DETAIL_TEXT']!='') echo $item['~DETAIL_TEXT']?>
            <? if($item['SOURCE']['VALUE']!='') { ?>
                <p class="gallery-item-source">Источник фото - <?=$item['SOURCE']['VALUE']?></p>
            <? } ?>
        </div>
    <? } ?>
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

            $arFilter = Array('IBLOCK_ID' => $iblockid, "ACTIVE" => "Y", "ACTIVE_DATE" => "Y", "PROPERTY_ARTICUL"=>$point['ARTICLE']['VALUE'], "PROPERTY_FLEX"=>$point['FLEX']['VALUE']);
            $db_list = CIBlockElement::GetList(Array("SORT" => "ASC"), $arFilter);
            if ($ob = $db_list->GetNextElement()) {
                $ob = array_merge($ob->GetFields(), $ob->GetProperties());
                $ob_name = __get_product_name($ob);
                $prod_arr[$point['NAME']] = $ob;
            }

            if($img != $point['IMG_NUMBER']['VALUE']) {
                if($img != '') { ?>
                        <div class="gallery-img-resize">
                            <a data-fancybox="gallery" data-src="#img<?=$n-1?>" class="fancybox-btn" data-touch="false">увеличить</a>
                        </div>
                    </div>
                    <div style="display: none;" id="img<?=$n-1?>" class="gallery-fancybox" data-type="gallery-slide">
                        <?
                        $cont = imgSize($obj_num,$img_num,$obj_img,$obj_dir,$flex);
                        echo $cont;
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
            <a data-fancybox="gallery" data-src="#img<?=$n-1?>" class="fancybox-btn" data-touch="false">увеличить</a>
        </div>
    </div>
    <div style="display: none;" id="img<?=$n-1?>" class="gallery-fancybox" data-type="gallery-slide">
        <?
        $cont = imgSize($obj_num,$img_num,$obj_img,$obj_dir,$flex);
        echo $cont;
        ?>
    </div>
</div>
</div>
</div>
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
</section>


<script src="/gallery/gallery.js?<?=$random?>"></script>

<?
require($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");
if(defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED===true)
{
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog.php");
}