<?
/*ini_set('display_errors', 1);
error_reporting(E_ALL);*/

global $APPLICATION; 
$comparison_ids = json_decode($_COOKIE['compare']); //сравнение
$favorite_ids = json_decode($_COOKIE['favorite']); //избранное
$cart = json_decode($_COOKIE['basket']); //корзина
$in_basket = false;
$product_qty = 1;
$cart_ids = Array();
foreach($cart as $cart_item) {
    $cart_ids[] = $cart_item->id;
    if($cart_item->id == $product_item->fields['ID']) {
        $in_basket = true;
        $product_qty = $cart_item->qty;
    }
}

function signTmp($signTmp_IBLOCK_SECTION_ID) {
    $arFilter = Array('IBLOCK_ID'=>IB_CATALOGUE, 'ACTIVE'=>'Y', 'ID'=>$signTmp_IBLOCK_SECTION_ID);
    $db_list = CIBlockSection::GetList(Array(), $arFilter, false, array('UF_*'));
    $signTmp_section = $db_list->GetNext();
    if ($signTmp_section['UF_H'] == 1) {
        $signTmp = ' sp-horizontal';
    } elseif ($signTmp_section['UF_V'] == 1) {
        $signTmp = ' sp-vertical';
    } else {
        $signTmp = '';
    }
    return $signTmp;
}

$glue_arr = get_glue_arr();
$glue_items = Array();
$arFilter = Array('IBLOCK_ID'=>IB_CATALOGUE, 'ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y', 'ID'=>$glue_arr);
$db_list = CIBlockElement::GetList(Array(), $arFilter);
while($glue_ob = $db_list->GetNextElement()) {
    if (!$glue_ob) continue;
    $glue_ob = array_merge($glue_ob->GetFields(), $glue_ob->GetProperties());
    $glue_items[] = $glue_ob;
}

$item_properties = $product_item->GetProperties();
$item = array_merge($product_item->GetFields(), $item_properties);

$arFilter = Array('IBLOCK_ID'=>IB_CATALOGUE, 'ID'=>$item['IBLOCK_SECTION_ID'], 'ACTIVE'=>'Y');
$db_list = CIBlockSection::GetList(Array(), $arFilter, false, array('UF_*'));
$last_section  = $db_list->GetNext();
$parent_section_code = '';

//print_r($last_section);

$is_flex = false;
$arFilter = Array('IBLOCK_ID'=>IB_CATALOGUE, 'CODE'=>$item['CODE'].'-f', 'ACTIVE'=>'Y', 'PROPERTY_FLEX'=>'Y');
if ($item['FLEX']['VALUE'] == 'Y') {
    $is_flex = true;
    $code = str_replace("-f", "", $item['CODE']);
    $arFilter = Array('IBLOCK_ID'=>IB_CATALOGUE, 'CODE'=>$code, 'ACTIVE'=>'Y', 'PROPERTY_FLEX'=>'N');
}
$db_list = CIBlockElement::GetList(Array(), $arFilter);
$item_flex = null;
if ($item_flex = $db_list->GetNextElement()) {
    $item_flex = array_merge($item_flex->GetFields(), $item_flex->GetProperties());
}

if ($is_flex) $back_catalogue = __get_product_link($item_flex);
else $back_catalogue = __get_product_link($item);

$back_catalogue = explode('?', $back_catalogue);
$back_catalogue = $back_catalogue[0];
$back_catalogue = explode('/', $back_catalogue);
$back_catalogue = array_diff($back_catalogue, array(''));

$back_catalogue_path = '';
for ($i = 0; $i <= count($back_catalogue)-1; $i++) {
    if($back_catalogue[$i] != $last_section['CODE']) $parent_section_code = $back_catalogue[$i];
    if ($back_catalogue[$i]) $back_catalogue_path .= '/'.$back_catalogue[$i];
}
$back_catalogue_path .= '/';

if($item['IBLOCK_SECTION_ID'] == 1587 && !in_array($item['ID'],$glue_arr) && ($item['ID'] != 6429) && ($item['ID'] != 160323)) { // Последняя проверка дать возможность зайти на страницу Е03 и старый Е13
    //LocalRedirect('/404.php'); exit;
}
$web_path = web_path($item);
$img_path = get_resized_img($web_path,713,713);
if($img_path == '' || !$img_path) $img_path = $web_path;

$images_path = $_SERVER["DOCUMENT_ROOT"]."/cron/catalog/data/images";
$images_web_path = "/cron/catalog/data/images";

foreach (array('100', '200', '300', '400', '410', '600') as $img_pre) {
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

}
 
// Доп сцены
foreach (array('31', '32', '33', '34', '35', '36', '37', '38', '39') as $img_pre) {
    $path = $images_path . "/39/" . $item['ARTICUL']['VALUE'] . '.' . $img_pre . '.png';
    $web_path = $images_web_path . "/39/" . $item['ARTICUL']['VALUE'] . '.' . $img_pre . '.png';

    if (file_exists($path)) {
        $files[] = $web_path;
        $files_by_type[$img_pre] = $web_path;
    }
}

$item['FILES_IMAGES'] = $files;

if (!isset($files_by_type['100']) && (!$is_flex)) {
    $files_by_type['100'] = $images_web_path."/nope.jpg";
}

if (!isset($files_by_type['600']) && ($is_flex)) {
    $files_by_type['600'] = $images_web_path."/nope.jpg";
}

$iscomp = 0;
if ($item['COMPOSITEPART']['VALUE']) $iscomp = 1;

if ((!$is_flex) && ($item_flex)) $item_param = array_merge($item, $item_flex);
else $item_param = $item;

$item_param_array = item_param($item_param);

$res_s = $item_param_array['res_s'];
$res_f = $item_param_array['res_f'];


foreach (array('max', 'gsm', 'obj', '3ds', 'dwg') as $m_pre) {
    $path = $_SERVER["DOCUMENT_ROOT"] . "/cron/catalog/data/models/" . $m_pre . "/" . $item['ARTICUL']['VALUE'] . '.'.$m_pre;
    $web_path = "/cron/catalog/data/models/" . $m_pre . "/" . $item['ARTICUL']['VALUE'] . '.'.$m_pre;
    if (!file_exists($path)) {
        continue;
    }
    if ($m_pre == 'max') {
        $model_files[$m_pre] = array('FILE'=>$web_path, 'NAME'=>'3d Max studio', 'type'=>$m_pre);
    } elseif ($m_pre == 'gsm') {
        $model_files[$m_pre] = array('FILE'=>$web_path, 'NAME'=>'Archicad', 'type'=>$m_pre);
    } elseif ($m_pre == 'dwg') {
        $model_files[$m_pre] = array('FILE'=>$web_path, 'NAME'=>'AutoCAD', 'type'=>$m_pre);
    } elseif ($m_pre == '3ds') {
        $model_files[$m_pre] = array('FILE'=>$web_path, 'NAME'=>'3d Max studio', 'type'=>$m_pre);
    } elseif ($m_pre == 'obj') {
        $model_files[$m_pre] = array('FILE'=>$web_path, 'NAME'=>'3D Object File', 'type'=>$m_pre);
    }
}

$article_foil = array('6.50.711', '6.50.712', '6.50.713', '6.50.714', '6.50.715', '6.50.716', '6.50.719', '6.51.710', );

if ($last_section['UF_H'] == 1) {
    $signTmp = ' prod-h';
} elseif ($last_section['UF_V'] == 1) {
    $signTmp = ' prod-v';
} else {
    $signTmp = '';
}

$sellout_class = $item['SELLOUT']['VALUE'] ? ' sellout' : '';
$prefix = 'prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# product: http://ogp.me/ns/product#"'; //чтобы исправить ОШИБКУ: префикс product неизвестен валидатору, укажите его явно атрибутом prefix

require($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");
?>
<div class="content-wrapper product">
<section class="prod-info-wrap<?=$sellout_class?>">
        <div class="prod-info-prev">
            <div class="prod-preload" data-type="prod-preload">
                <img src="/img/preloader.gif" alt="Ожидайте">
            </div>
            <div class="prod-info-mob-title">
                <?=__get_product_name($item)?>
                <? if (in_array($item['ARTICUL']['VALUE'], $article_foil)) { ?>
                    <i class="icomoon icon-light"></i>
                <? } ?>
            </div>

            <div id="prodSlider" class="slider-prod">
                <div class="sp-slides">
                    <?
                    $article_cut = substr($item['ARTICUL']['VALUE'], 0, 4);
                    if ((strpos($back_catalogue_path, 'karnizy')) && (($article_cut == '6.53') || ($article_cut == '6.51') || ($article_cut == '1.53') || ($article_cut == '1.51'))) {
                        if ($is_flex)  $img_sort_sld = array('600', '31','32','33','34','35','36','37','38','39', '200', '300');
                        else $img_sort_sld = array('100', '31','32','33','34','35','36','37','38','39', '200', '300');
                    } else {
                        if ($is_flex)  $img_sort_sld = array('600', '200', '300', '31','32','33','34','35','36','37','38','39');
                        else $img_sort_sld = array('100', '200', '300', '31','32','33','34','35','36','37','38','39');
                    }
                    ?>
                    <? foreach ($img_sort_sld as $k=>$img_sld) { ?>

                    <? if ($files_by_type[$img_sld]) { ?>

                        <?
                        $img_big = '';
                        if($img_sld == '100' || $img_sld == '600') {
                            $path = $images_path . "/big/" . $img_sld . "/" . $item['ARTICUL']['VALUE'] . '.' . $img_sld . '-b.png';
                            $web_path = $images_web_path . "/big/" . $img_sld . "/" . $item['ARTICUL']['VALUE'] . '.' . $img_sld . '-b.png';
                            if(file_exists($path)){
                                if($item['FLEX']['VALUE'] == 'Y' && $img_sld == '600' || $item['FLEX']['VALUE'] == 'N' && $img_sld == '100') {
                                    $img_big = $web_path;
                                }
                            }
                        }
                        ?>
                        <?/* if($item['ARTICUL']['VALUE'] == 'E02.S.290' && $img_sld == '100') { ?>
                            <div class="sp-slide">
                                <img class="sp-image<?=$signTmp?>" src="<?=get_resized_img('/img/new-design-e02s290.png',713,713)?>" data-val="00" alt="sp-image">

                                <img class="sp-thumbnail" src="<?=get_resized_img('/img/new-design-e02s290.png',127,127)?>" alt="sp-thumbnail">
                            </div>
                        <? continue; } */?>

                        <div class="sp-slide">
                            <?if(!empty($item['SELLOUT']['VALUE'])) { ?>
                                <div class="new-prod sell-out">распродажа</div>
                            <? } ?>
                            <? if($img_big!='') { ?>
                                <a href="<?=$img_big?>" data-lightbox="big" class="sp-big" data-title="<?=__get_product_name($item)?>" tabindex="0">Увеличить</a>
                            <? } ?>
                            <? $curr_img = $img_big!='' ? $img_big : $files_by_type[$img_sld]; ?>
                            <img class="sp-image<?if($img_sld == '100' || $img_sld == '600') echo $signTmp?>" src="<?=get_resized_img($curr_img,713,713)?>" data-val="<?=$img_sld?>" alt="<?=__get_product_name($item)?> - превью <?= ($k + 1) ?>">

                            <img class="sp-thumbnail<?if($img_sld == '100' || $img_sld == '600') echo $signTmp?>" src="<?=get_resized_img($files_by_type[$img_sld],127,127)?>" alt="<?=__get_product_name($item)?> - фото <?= ($k + 1) ?>"> 
                        </div>

                        <?if($k == 0 && $item_flex) { ?>
                            <div class="sp-slide sp-slide-flex">
                                <div class="flex-slide">
                                    <div class="flex-slide-desc">
                                        <?if(!$is_flex) { ?>
                                            <div>Гибкий аналог:</div>
                                        <? } else {?>
                                            <div>Жесткий аналог:</div>
                                        <? } ?>
                                        <div class="flex-slide-title"><?=__get_product_name($item_flex)?></div>
                                        <div class="flex-slide-price"><?=__cost_format(__get_product_cost($item_flex))?></div>
                                    </div>
                                    <img class="sp-image" src="<?=get_resized_img(web_path($item_flex),713,713);?>" alt="<?=__get_product_name($item)?> - превью">
                                    <a href="<?=__get_product_link($item_flex)?>" class="sp-big">Перейти</a>
                                </div>
                                <img class="sp-thumbnail sp-thumbnail-flex" src="<?=get_resized_img(web_path($item_flex),127,127);?>" alt="<?=__get_product_name($item)?> - фото">
                            </div>
                        <? } ?>

                        <? if($k == 0 && isset($files_by_type['410']) || $k == 0 && isset($files_by_type['400'])) { ?>
                            <div class="sp-slide">
                                <div class="big-section">
                                    <? require($_SERVER["DOCUMENT_ROOT"] . "/catalogue/item_pic.php"); ?>
                                    <div class="big-section-note">мм, справочный размер</div>
                                </div>
                                <div class="sp-thumbnail sp-thumbnail-section">
                                    <?require($_SERVER["DOCUMENT_ROOT"] . "/catalogue/item_pic.php");?>
                                </div>
                            </div>
                        <? } ?>
                     <? } ?>

                        <? if($k == 0) {
                        if($item['ARTICUL']['VALUE'] == '1.51.510' || $item['ARTICUL']['VALUE'] == '1.51.503' || $item['ARTICUL']['VALUE'] == '1.51.504' || $item['ARTICUL']['VALUE'] == '1.51.518' || $item['ARTICUL']['VALUE'] == '1.50.167') {
                            ?>
                            <div class="sp-slide">
                                <div class="big-section-draw">
                                    <img src="/img/catalogue/<?=$item['ARTICUL']['VALUE']?>-b.jpg?v=3" data-val="00" alt="<?=__get_product_name($item)?> - фото">
                                    <div class="big-section-note">справочный размер</div>
                                    <a href="/img/catalogue/<?=$item['ARTICUL']['VALUE']?>-b.jpg?v=3" data-lightbox="big-draw" class="sp-big" data-title="<?=__get_product_name($item)?>" tabindex="0">Увеличить</a>
                                </div>
                                <img class="sp-thumbnail sp-thumbnail-draw" src="/img/catalogue/<?=$item['ARTICUL']['VALUE']?>-b.jpg?v=1" alt="<?=__get_product_name($item)?> - фото">
                            </div>
                        <? } ?>
                        <? } ?>


                    <? } ?>


                    <? // Дополнительный контент для Мавритании
                    if($item['MAURITANIA']['VALUE']=='Y') {
                        $mauritania_bars_path = $_SERVER["DOCUMENT_ROOT"]."/cron/catalog/data/images/mauritania_bars/";
                        $mauritania_bars_web = "/cron/catalog/data/images/mauritania_bars/";
                        // Чтение массива
                        if (($fp = fopen($mauritania_bars_path."mauritania_bars.csv", "r")) !== FALSE) {
                            while (($data_fp = fgetcsv($fp, 0, ";")) !== FALSE) {
                                $mauritania_bars[] = $data_fp;
                            }
                            fclose($fp);
                        }
                        // прогон дополнительных изображений
                        foreach ($mauritania_bars as $mb_img) {
                            if (in_array($item['ARTICUL']['VALUE'], $mb_img)) {
                                $mb_img_name = trim($mb_img[0]);
                                ?>
                                <div class="sp-slide">
                                    <img class="sp-image" src="<?=$mauritania_bars_web.$mb_img_name.'.jpg'?>" data-val="<?=$mb_img_name?>" alt="<?=__get_product_name($item)?> - превью">
                                    <img class="sp-thumbnail" src="<?=$mauritania_bars_web.$mb_img_name.'.jpg'?>" alt="<?=__get_product_name($item)?> - фото">
                                </div>
                                <?
                            }
                        }
                    }
                    ?>

                </div>
            </div>
        </div>

        <div class="prod-info" itemscope itemtype="http://schema.org/Product"
             data-type="prod-info"
             data-id="<?=$item['ID']?>"
             data-cat="<?=$last_section['ID']?>"
             data-name="<?=__get_product_name($item)?>"
             data-code="<?=$item['INNERCODE']['VALUE']?>"
             data-price="<?=_makeprice(CPrice::GetBasePrice($item['ID']))['PRICE'];?>"
             data-curr="<?=getCurrency($my_city)?>"
             data-cat-name="<?=$last_section['NAME']?>"
             data-iscomp="<?=$iscomp?>"
             data-qty="<?=$product_qty?>"
            <?if($item['MAURITANIA_SPECIAL']['VALUE']=='Y') echo ' data-maur-spec="1"'?>>
            <div class="prod-info-top prod-info-line">
                <h1 itemprop="name">
                    <?=__get_product_name($item)?>
                    <? if (in_array($item['ARTICUL']['VALUE'], $article_foil)) { ?>
                        <i class="icomoon icon-light"></i>
                    <? } ?>
                    <?
                    global $USER;
                    if ($USER->IsAdmin()) { ?>
                        <span style="color: #cdcdcd; font-size: 15px; font-weight: normal; margin-left: 18px; text-transform: uppercase;">id: <?=$item['ID']?></span>
                    <? } ?>
                </h1>
                <!-- Schema Product -->
                <div style="display: none;">
                    <span itemprop="brand">Европласт</span>
                    <span itemprop="description"><?= $APPLICATION->GetProperty("description")?></span>
                </div>
                <div class="prod-info-main" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                    <?if($item['SELLOUT']['VALUE'] == 'Y' && $item['OLD_PRICE']['VALUE'] != '' && $loc['country']['VALUE'] == '3111' ) {//print_r($loc);
                        $old_price = _makeprice($item['OLD_PRICE']['VALUE']); ?>
                    <? } ?>
                    <? if($item['COMING_SOON']['VALUE']!='Y') { ?>
                        <?$item_cost = __get_product_cost($item)?>
                        <? if($item_cost) { ?>
                            <?if(!empty($old_price)) {?>
                                <div class="prod-price-block">
                            <? } ?>
                                <h2 class="prod-info-price"><?=__cost_format($item_cost)?></h2>
                                    <?if(!empty($old_price)) {?>
                                        <div class="prod-info-price-old"><?=__cost_format($old_price)?></div>
                                    <? } ?>
                                <div style="display: none;">
                                    <span itemprop="priceCurrency" content="RUB">RUB</span>
                                    <span itemprop="price" content="<?= $item_cost ?>"><?= $item_cost ?></span>
                                    <link itemprop="availability" href="https://schema.org/InStock">
                                </div>
                            <?if(!empty($old_price)) {?>
                                </div>
                            <? } ?>
                        <? } ?>
                    <? } ?>
                    <div class="prod-info-btns">
                        <? if($item['COMING_SOON']['VALUE']=='Y') { ?>
                            <p class="no-sale">Скоро в&nbsp;продаже</p>
                        <? } else { ?>
                            <?if($item['NO_ORDER']['VALUE'] != 'Y' && $item_cost) {?>
                                <?if($item['OUT_OF_STOCK']['VALUE'] == 'Y' && $loc['ID'] == 3109) { ?>
                                <div class="out-of-stock">
                                    <p class="no-sale">Товар недоступен для&nbsp;заказа</p>
                                    <p class="add-np-sale">К сожалению, данная позиция будет <br>доступна для&nbsp;покупки после&nbsp;31&nbsp;мая&nbsp;2020&nbsp;г.</p>
                                    <p class="add-np-sale">Приносим свои извинения.</p>
                                </div>
                                <? } else { ?>
                                    <? if($in_basket) { ?>
                                        <a href="/cart/" class="prod-buy-link">перейти в&nbsp;корзину <span><?=$product_qty?></span></a>
                                    <? } else {?>
                                        <div class="prod-buy">
                                            <div class="prod-add-cart" data-type="prod-page-add">в корзину</div>
                                            <div class="prod-qty" data-inbasket="<?=$in_basket?>">
                                                <div class="prod-minus prod-qty-btn" data-type="prod-page-minus"><i class="icon-minus"></i></div>
                                                <input type="text" value="<?=$product_qty?>" data-min="1" data-type="prod-page-qty">
                                                <div class="prod-plus prod-qty-btn" data-type="prod-page-plus"><i class="icon-plus-squared"></i></div>
                                            </div>
                                        </div>
                                    <? } ?>
                                <? } ?>
                            <? } else { ?>
                                <p class="no-sale">Товар недоступен для&nbsp;заказа</p>
                            <? } ?>
                        <? } ?>

                        <?if(in_array($item['ID'],$comparison_ids)){ ?>
                            <div class="prod-compare-btn active">в сравнении <a href="/comparison/" title="перейти"></a></div>
                        <? } else {?>
                            <div class="prod-compare-btn" data-type="compare">сравнить</div>
                        <? } ?>

                        <i class="icon-favorite" data-type="favorite" data-user="no-user" title="Добавить в избранное"></i>
                        <div class="icon-share-wrap">
                            <i class="icon-share" data-type="share" title="Поделиться"></i>
                            <div class="add-social-wrap" data-type="share-wrap">
                                <div class="ya-share2" id="my-share"></div>
                                <script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
                                <script src="//yastatic.net/share2/share.js"></script>
                                <?$url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];?>
                                <script>
                                    var myShare = document.getElementById('my-share');
                                    var share = Ya.share2(myShare, {
                                        content: {
                                            url: '<?=$url.$_SERVER['REQUEST_URI']?>',
                                            title: '<?=__get_product_name($item)?>',
                                            description: "<?=_makeprice(CPrice::GetBasePrice($item['ID']))['PRICE'];?>",
                                            image: "<?=web_path($item)?>",
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

                    </div>
                </div>
                <?if($item['SELLOUT']['VALUE'] == 'Y') {?>
                    <div class="prod-sellout-info">доступно для&nbsp;заказа до&nbsp;17.06.2024. <br>количество ограничено!</div>
                <? } ?>
            </div>
            <? if($last_section['CODE'] != 'klei-90') { // если не клей ?>
            <? /*if($item['HAS_SAMPLE']['VALUE'] == 'Y' && $loc['ID'] == 3109) {
                $samplePrice = $item['SAMPLE_PRICE']['VALUE'] != '' ? $item['SAMPLE_PRICE']['VALUE'] : DEFAULT_SAMPLE_PRICE;
                ?>
                <div class="prod-info-sample prod-info-line">
                    <div class="sample-add-cart<?if(in_array('s'.$item['ID'],$cart_ids)) echo ' active'?>" data-type="buy-sample-page">Купить образец</div>
                    <div class="sample-add-wrap">
                        <div class="sample-add-option"><span>Длина:</span> 250 мм</div>
                        <div class="sample-add-option"><span>Цена:</span> <?=$samplePrice?> RUB</div>
                    </div>
                </div>
            <? } */ ?>
            <?
            $inst_link = '';
            $name_inst_link = 'Инструкция по монтажу';
            if($item['ARTICUL']['VALUE'] == '1.65.501' || $item['ARTICUL']['VALUE'] == '1.65.502' || $item['ARTICUL']['VALUE'] == '1.65.503') {
                if (file_exists($_SERVER["DOCUMENT_ROOT"] . '/download/Сборник_чертежей_по_использованию_' . $item['ARTICUL']['VALUE'] . '.pdf')) {
                    $inst_link = '/download/Сборник_чертежей_по_использованию_' . $item['ARTICUL']['VALUE'] . '.pdf';
                    $name_inst_link = 'Чертежи по использованию';
                }
            } else {
                $karnizy_l = array('1.50.229','1.50.216','1.50.228','1.50.225','1.50.226','1.50.214','1.50.213','1.50.261','1.50.222','1.50.221','1.50.220','1.50.212','1.50.211','1.50.210','1.50.209','1.50.208','1.50.135','1.50.132','1.50.215');

                if(in_array($item['ARTICUL']['VALUE'],$karnizy_l)) {
                    $inst_link = '/download/manual_karnizy_l.pdf';
                } elseif(file_exists($_SERVER["DOCUMENT_ROOT"].'/download/manual_'.$last_section['CODE'].'.pdf')) {
                    $inst_link = '/download/manual_'.$last_section['CODE'].'.pdf';
                } elseif(file_exists($_SERVER["DOCUMENT_ROOT"].'/download/manual_'.$parent_section_code.'.pdf')) {
                    $inst_link = '/download/manual_'.$parent_section_code.'.pdf';
                }
            }
            ?>
            <?if($item_flex || $inst_link != '') { ?>
            <div class="prod-info-type-wrap prod-info-line">
                <?if($item_flex) { ?>
                <div class="prod-info-type">
                    <div class="prod-info-type-name">тип</div>
                    <div class="prod-info-type-options">
                        <a class="prod-info-type-option<?if(!$is_flex) echo ' active'?>"<?if($is_flex) echo ' href="'.__get_product_link($item_flex).'" title="Перейти"'?>>жесткий</a>
                        <a class="prod-info-type-option<?if($is_flex) echo ' active'?>"<?if(!$is_flex) echo ' href="'.__get_product_link($item_flex).'" title="Перейти"'?>>гибкий</a>
                    </div>
                </div>
                <? } ?>
                <div class="prod-info-dwnld">
                    <? if($inst_link != '') {?>
                        <div class="prod-info-dwnld-name"><?=$name_inst_link?></div>
                        <a href="<?=$inst_link?>" class="prod-info-dwnld-btn" download><i class="icon-download"></i> скачать pdf</a>
                    <? } ?>
                </div>
            </div>
            <? } ?>
            <div class="prod-info-params prod-info-line" data-type="filt-item">
                <div class="prod-info-params-title" data-type="filt-title">Параметры <i class="icon-angle-down-2"></i></div>
                <div class="prod-info-params-cont" data-type="filt-cont">
                    <div class="prod-info-main-params">
                        <?if($item['ARTICUL']['VALUE'] == '1.60.019') { ?>
                            <div class="ornament-params">
                                <div class="prod-info-param-item">
                                    <span class="prod-info-param-name"> </span>
                                    <span class="prod-info-param-val">глубина&nbsp;x&nbsp;ширина&nbsp;x&nbsp;высота,мм</span>
                                </div>
                                <div class="prod-info-param-item">
                                    <span class="prod-info-param-name">Орнамент крайний левый/правый</span>
                                    <span class="prod-info-param-val">17&nbsp;x&nbsp;88&nbsp;x&nbsp;53</span>
                                </div>
                                <div class="prod-info-param-item">
                                    <span class="prod-info-param-name">Орнамент средний левый/правый</span>
                                    <span class="prod-info-param-val">16&nbsp;x&nbsp;74&nbsp;x&nbsp;117</span>
                                </div>
                                <div class="prod-info-param-item">
                                    <span class="prod-info-param-name">Орнамент центральный</span>
                                    <span class="prod-info-param-val">24&nbsp;x&nbsp;231&nbsp;x&nbsp;246</span>
                                </div>
                            </div>
                        <? } else { ?>
                            <? if (count($res_s)) { ?>
                                <div>
                                    <? foreach ($res_s as $name => $pitem) { ?>
                                        <div class="prod-info-param-item">
                                            <span class="prod-info-param-name"><?=$name?></span>
                                            <span class="prod-info-param-val"><?=$pitem?></span>
                                        </div>
                                    <? } ?>
                                    <?if($item['RAPPORT_LENGTH']['VALUE'] != '') { ?>
                                        <div class="prod-info-param-item">
                                            <span class="prod-info-param-name">Длина раппорта</span>
                                            <span class="prod-info-param-val"><?=$item['RAPPORT_LENGTH']['VALUE']?> мм</span>
                                        </div>
                                    <? } ?>
                                </div>
                            <? } ?>
                            <? if (count($res_f)) { ?>
                                <div>
                                    <? if (!$is_flex) { ?>
                                        <div class="prod-info-flex-params-title">Радиусы гибких аналогов</div>
                                    <? } ?>
                                    <? foreach ($res_f as $name => $pitem) { ?>
                                        <div class="prod-info-param-item">
                                            <span class="prod-info-param-name"><?=$name?></span>
                                            <span class="prod-info-param-val<?if($pitem == 'нет') echo ' param-val-no'?>"><?=$pitem?></span>
                                        </div>
                                    <? } ?>
                                </div>
                            <? } ?>

                        <? } ?>

                    </div>

                    <?if($res_f['Радиус изгиба вогнутый'] == 'нет' || $item['S5']['VALUE'] == 'нет') { ?>
                        <div class="desc-warning">
                            <i class="icomoon icon-warning"></i> <span>Использование на&nbsp;вогнутый радиус невозможно.</span>
                        </div>
                    <? } ?>

                    <div class="prod-info-ref">*Размеры справочные</div>
                    <? if (in_array($item['ARTICUL']['VALUE'], $article_foil) && ($item['ARTICUL']['VALUE'] != '6.51.710')) { ?>
                    <p style="padding-top: 18px"> * Данное изделие имеет нанесенный слой светоотражающей фольги </p>
                    <? } ?>
                    <?if($item['ARTICUL']['VALUE'] == '1.65.501' || $item['ARTICUL']['VALUE'] == '1.65.502' || $item['ARTICUL']['VALUE'] == '1.65.503') {?>
                        <div class="desc-warning">
                            <i class="icomoon icon-warning"></i> <span>Изделия продаются только как&nbsp;сопутствующий товар с&nbsp;элементами коллекции Мавритания.</span>
                        </div>
                    <? } ?>
					<?if($item['ARTICUL']['VALUE'] == '1.64.811' || $item['ARTICUL']['VALUE'] == '1.64.813' || $item['ARTICUL']['VALUE'] == '1.64.801' || $item['ARTICUL']['VALUE'] == '1.64.803') {?>
                         <div class="desc-warning">
                            <i class="icomoon icon-warning"></i> <span>Внимание! Для сборки готового камина необходимо приобрести 3 отдельных элемента камина.</span>
                        </div>             
                    <? } ?>
                    <?if(($item['IBLOCK_SECTION_ID'] == 1601) && ($item['NEW_ART_DECO']['VALUE'] == 'Y')) { // декоративная панель NAD?>
                        <div class="desc-warning">
                            <span class="decor-panel-warn"><i class="icon-very-long-arr-top"></i></span>
                            <span class="decor-panel-warn"><i class="icon-very-long-arr-top"></i></span>
                            <i class="icomoon icon-warning"></i>
                            <span>Внимание! При монтаже панели необходимо стыковать таким образом, чтобы стрелки на всех панелях смотрели в одну сторону.</span>
                        </div>
                    <? } ?>
                    <?
                    $art_val = explode('.',trim($item['ARTICUL']['VALUE']));
                    $mat_name = '';
                    if($art_val[0] == 1 || $art_val[0] == 4) $mat_name = 'пенополиуретан';
                    //if($art_val[0] == 6) $mat_name = 'вспененный композиционный полимер высокой плотности <br>на&nbsp;основе полистирола, изготовлено методом экструзии';
                    if($art_val[0] == 6) $mat_name = 'Перфом';
                    if($mat_name != '') {
                    ?>
                        <div class="prod-info-param-material">
                            <i class="icon-material"></i>
                            <div class="prod-info-material-wrap">
                                <div class="prod-info-param-name">Материал</div>
                                <div class="prod-info-param-val prod-info-param-val-mat"><?=$mat_name?></div>
                            </div>
                        </div>
                    <? } ?>
                </div>

            </div>
            <?
            $dir = $_SERVER["DOCUMENT_ROOT"].'/cron/catalog/data/images/40/pdf/'.$item['ARTICUL']['VALUE'].'.40_ru.pdf';
            $path = '/cron/catalog/data/images/40/pdf/'.$item['ARTICUL']['VALUE'].'.40_ru.pdf';
            ?>
            <? if(count($model_files) || file_exists($dir)) { ?>
                <div class="prod-info-models prod-info-line" data-type="filt-item">
                    <div class="prod-info-params-title" data-type="filt-title">Модели <i class="icon-angle-down-2"></i></div>
                    <div class="prod-info-params-cont" data-type="filt-cont">
                        <? if (count($model_files)) {?>
                            <div class="prod-info-models-wrap">
                                <div class="prod-info-models-title">3d модель</div>
                                <div class="prod-info-links">
                                    <? foreach ($model_files as $key=>$val) { ?>
                                        <a href="<?=$val['FILE']?>" class="prod-info-link"><i class="icon-download"></i> скачать <?=$val['type']?></a>
                                    <? } ?>
                                </div>
                            </div>
                        <? } ?>
                        <? if(file_exists($dir)) { ?>
                            <div class="prod-info-models-wrap">
                                <div class="prod-info-models-title">Дополнительный чертеж</div>
                                <div class="prod-info-links">
                                    <a href="<?=$path?>" class="prod-info-link" download><i class="icon-download"></i> скачать pdf</a>
                                </div>
                            </div>
                        <? } ?>
                    </div>
                </div>
            <? } ?>
        <? } else { //если клей?>
            <div class="prod-info-params prod-info-params-adh" data-type="filt-item">
                <div class="prod-info-params-title" data-type="filt-title">Параметры <i class="icon-angle-down-2"></i></div>
                <div class="prod-info-params-cont" data-type="filt-cont">
                    <div class="adh-prew-txt">
                        <?=$item['~PREVIEW_TEXT']?>
                    </div>
                </div>
            </div>
        <? } ?>
        </div>
    </section>


<? if($last_section['CODE'] != 'klei-90') { // если не клей ?>
<? // СОСТАВНЫЕ ЧАСТИ ?>
<?if (is_array($item['COMPOSITEPART']['VALUE']) && count($item['COMPOSITEPART']['VALUE'])) {?>
    <section class="prod-similar">
        <div class="prod-similar-title prod-related-title">Составные части</div>
        <div class="prod-similar-slider prod-prev-slider" data-type="similar-slider">
            <?
				$array_item_comp = array(); // убираем дубли Dem
                $arFilter = Array('IBLOCK_ID'=>IB_CATALOGUE, 'ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y', 'ID'=>$item['COMPOSITEPART']['VALUE']);
                $db_list = CIBlockElement::GetList(Array(), $arFilter);
                while($product_item_part = $db_list->GetNextElement()) {
                    $product_item_part = array_merge($product_item_part->GetFields(), $product_item_part->GetProperties());
					if (in_array($product_item_part['ARTICUL']['VALUE'],$array_item_comp)) continue;
					$array_item_comp[] = $product_item_part['ARTICUL']['VALUE'];
                    echo get_product_preview($product_item_part);
                }
            ?>
        </div>
    </section>
<? } ?>

<?//ЯВЛЯЕТСЯ СОСТАВНОЙ ЧАСТЬЮ ДЛЯ
$arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, "ACTIVE" => "Y", "ACTIVE_DATE" => "Y", "PROPERTY_COMPOSITEPART"=>$item['ID']);
$db_list = CIBlockElement::GetList(Array("SORT" => "ASC"), $arFilter);
$main_elements = array();
while ($main_element = $db_list->GetNextElement()) {
    $main_element = array_merge($main_element->GetFields(), $main_element->GetProperties());
    $main_elements[] = $main_element;
}
if ($main_elements && count($main_elements)) { ?>
    <section class="prod-similar">
        <div class="prod-similar-title prod-related-title">Является составной частью для</div>
        <div class="prod-similar-slider prod-prev-slider" data-type="similar-slider">
            <? foreach ($main_elements as $product_item_part) {
                echo get_product_preview($product_item_part);
            }?>
        </div>
    </section>
<? } ?>

<? //БАЛЮСТРАДЫ ?>
<?
$baljustrady_arr = Array('4.71.101','4.74.101','4.72.101','4.75.101','4.76.101','4.73.101','4.77.101','4.78.101','4.71.201','4.74.201','4.72.201','4.75.201','4.76.201','4.73.201','4.77.201','4.78.201');
?>
<? if(in_array($item['ARTICUL']['VALUE'],$baljustrady_arr)) {?>
    <?
    $dir = $_SERVER["DOCUMENT_ROOT"].'/img/baljustrady/';
    $path = '/img/baljustrady/';
    if(is_dir($dir) && file_exists($dir)) {?>
        <section class="prod-similar prod-similar-baljustrady">
            <div class="prod-similar-title prod-related-title">Применение</div>
            <div class="prod-similar-slider prod-prev-slider" data-type="similar-slider">
                <?
                $images = scandir($dir);
                $images = preg_grep('~\.(jpeg|jpg|png)$~', $images);
                $images = (array_values($images));
                for($i=0; $i < count($images); $i++) {
                    $image = $path.$images[$i];
                    ?>
                    <a class="prod-gallery-slide" style="background-image: url('<?=$path.$images[$i]?>')" href="<?=$path.$images[$i]?>" data-fancybox="prod-gallery">
                    </a>
                <? } ?>
            </div>
        </section>
    <? } ?>

<? } ?>

<? //ИСПОЛЬЗУЕТСЯ С ТОВАРОМ ?>
<?
$arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, "ACTIVE" => "Y", "ACTIVE_DATE" => "Y", "PROPERTY_CONFORMITY"=>$item['ID']);
$db_list = CIBlockElement::GetList(Array("SORT" => "ASC"), $arFilter);
$main_elements = array();
$main_elements_ids = array();
while ($main_element = $db_list->GetNextElement()) {
    $main_element = array_merge($main_element->GetFields(), $main_element->GetProperties());
    $main_elements[] = $main_element;
    $main_elements_ids[] = $main_element['ID'];
}
//добавляем бруски для погонажной мавритании
if($item['MAURITANIA']['VALUE'] == 'Y') {
    if($item['NAME'] == 'карниз' || $item['NAME'] == 'молдинг' || $item['NAME'] == 'плинтус' || $item['ARTICUL']['VALUE'] == '1.59.503') {
        $arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, "ACTIVE" => "Y", "ACTIVE_DATE" => "Y", "PROPERTY_MAURITANIA_SPECIAL"=>'Y');
        $db_list = CIBlockElement::GetList(Array("SORT" => "ASC"), $arFilter);
        while ($add_element = $db_list->GetNextElement()) {
            $add_element = array_merge($add_element->GetFields(), $add_element->GetProperties());
            $main_elements[] = $add_element;
            $main_elements_ids[] = $add_element['ID'];
        }
    }
}
foreach ($item['CONFORMITY']['VALUE'] as $comp_item) { // проверка на перекрестность id
    if (!in_array($comp_item, $main_elements_ids, true)) {
        $arFilter = Array('IBLOCK_ID'=>IB_CATALOGUE, 'ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y', 'ID'=>$comp_item);
        $db_list = CIBlockElement::GetList(Array(), $arFilter);
        $comp_element = $db_list->GetNextElement();
        if (!$comp_element) continue;
        $comp_element = array_merge($comp_element->GetFields(), $comp_element->GetProperties());

        if (in_array($comp_element['ID'], $main_elements_ids)) {
            continue;
        }

        $main_elements[] = $comp_element;
        $main_elements_ids[] = $comp_element['ID'];
    }
}
/* --- Блок "Сопутствующие товары" --- */
if (!empty($item['RELATED']['VALUE'])) {
    $RELATED = $item['RELATED']['VALUE'];
    $RELATED_ARR = explode(',', $RELATED);
    $RELATED_ARTICULS = array();
    foreach ($RELATED_ARR AS $RELATED_ARTICUL) {
        $RELATED_ARTICULS[] = trim($RELATED_ARTICUL);
    }
    if (!empty($RELATED_ARTICULS)) {
        $arFilter = Array('IBLOCK_ID'=>IB_CATALOGUE, 'PROPERTY_ARTICUL' => $RELATED_ARTICULS);
        $db_list = CIBlockElement::GetList(Array(), $arFilter);
        while ($add_element = $db_list->GetNextElement()) {
            $add_element = array_merge($add_element->GetFields(), $add_element->GetProperties());
            if (in_array($add_element['ID'], $main_elements_ids)) {
                continue;
            }
            if ($add_element['ID'] == $item['ID']) continue;

            $main_elements[] = $add_element;
            $main_elements_ids[] = $add_element['ID'];
        }
    }
}
/* --- // --- */
if (is_array($main_elements) && count($main_elements)) {
?>
<section class="prod-similar">
    <br>
    <div class="prod-similar-title prod-related-title">Используется с <?= count($main_elements) > 1 ? 'товарами' : 'товаром' ?></div>
    <div class="prod-similar-slider prod-prev-slider" data-type="similar-slider">
        <? 

        foreach ($main_elements as $comp_item) {
            echo get_product_preview($comp_item);
        } ?>
    </div>
</section>
<? } ?>

<? //ПРИМЕНЕНИЕ В ИНТЕРЬЕРЕ ?>
<? if($item['MAURITANIA']['VALUE'] == 'Y' || $item['LINES']['VALUE'] == 'Y') {
    if($item['MAURITANIA']['VALUE'] == 'Y') $folder = 'mauritania';
    if($item['LINES']['VALUE'] == 'Y') $folder = 'lines';
    $dir = $_SERVER["DOCUMENT_ROOT"].'/collection/img/interior/'.$folder.'/'.$item['ARTICUL']['VALUE'].'/';
    $path = '/collection/img/interior/'.$folder.'/'.$item['ARTICUL']['VALUE'].'/';
    if(is_dir($dir) && file_exists($dir)) { ?>
        <section class="prod-similar prod-similar-baljustrady">
            <div class="prod-similar-title prod-related-title">Применение в&nbsp;интерьере</div>
            <div class="prod-similar-slider prod-prev-slider" data-type="similar-slider">
                <?
                $images = scandir($dir);
                $images = preg_grep('~\.(jpeg|jpg|png)$~', $images);
                $images = (array_values($images));
                for($i=0; $i < count($images); $i++) {
                    $image = $path.$images[$i];
                    ?>
                    <a class="prod-gallery-slide" style="background-image: url('<?=$path.$images[$i]?>')" href="<?=$path.$images[$i]?>" data-fancybox="prod-gallery">
                    </a>
                <? } ?>
            </div>
        </section>
    <? } ?>
<? } ?>
<? //КЛЕЙ ?>
<div class="prod-similar prod-similar-glue">
    <div class="prod-similar-title prod-related-title">Клей для&nbsp;монтажа</div>
    <div class="prod-similar-slider prod-prev-slider" data-type="similar-slider">
        <? foreach($glue_items as $glue) {
            if($glue == 6107) continue; //пропускаем E02.S.290
            echo get_product_preview($glue);
        } ?>
    </div>
</div>

<? //СХОЖИЕ ПО СТИЛЮ
// временный рандом массив на понравиться внутри группы
$rand_items = array();
$max_rand_items = 5;
$cl_style = array('class_04','class_05','class_06','class_07','class_08','class_09','class_10','class_11');

$temp_styles['LOGIC'] = 'OR';
$temp_styles_flag = false;
foreach ($cl_style as $cl_item) {
    if ($item[$cl_item]['VALUE'] == 'Y') {
        $temp_styles['PROPERTY_'.$cl_item] = 'Y'; $temp_styles_flag = true;
    }
}

$CATALOG_FILTER_FULL = CatalogFullFilter($last_section['ID'],null,null);
if ($temp_styles_flag) $CATALOG_FILTER_FULL[] = $temp_styles;
$db_list_full = CIBlockElement::GetList(Array('PROPERTY_ARTICUL'=>'ASC'), $CATALOG_FILTER_FULL, false);

while($ob = $db_list_full->GetNextElement()) {
    $ob = array_merge($ob->GetFields(), $ob->GetProperties());
    if ($ob['ID'] == $item['ID']) continue;
    $rand_items[] = $ob;
}

if ((count($rand_items) > 0) && ($temp_styles_flag))  { ?>
<section class="prod-similar prod-similar-instyle">
    <div class="prod-similar-title prod-related-title">Схожие по стилю</div>
    <div class="prod-similar-slider prod-prev-slider" data-type="similar-slider">
        <?
        shuffle($rand_items);
        $i = 0;
        while ($i < $max_rand_items) {
            if (!$rand_items[$i]) break;
            echo get_product_preview($rand_items[$i]);
            $i++;
        } ?>
    </div>
</section>
<? } ?>

<? } else { // если клей?>
    <div class="prod-adh-main">
        <?=$item['~DETAIL_TEXT'];?>
    </div>
<? } ?>

<?
if(strpos($_SERVER['HTTP_REFERER'],'catalogue') !== false && strpos($_SERVER['HTTP_REFERER'],'?page=') !== false) { ?>
    <a href="<?=$_SERVER['HTTP_REFERER']?>" class="prod-back-cat">В каталог</a>
<? } else { ?>
    <? if ($back_catalogue_path == '/dekorativnye-elementy/') { ?>
        <br><br><br>
    <? } else { ?>
        <a href="<?=$back_catalogue_path?>" class="prod-back-cat">В каталог</a>
    <? } ?>
<? } ?>
</div>

<?
//электронная коммерция

$item['price'] = round($item_cost);
$categories = ___get_product_sections($item, true);
$prod_arr[] = array('id' => $item['INNERCODE']['VALUE'], 'name' => __get_product_name($item), 'category' => $categories[0], 'price' => $item['price']);

/*передача в google*/
$ga_arr = array('items'=>$prod_arr);
/*передача в yandex*/
$ya_cont = array(
    'ecommerce'=>array(
        'detail'=>array(
            'products'=>$prod_arr
        )
    )
);
?>
<script>
    $(document).ready(function() {
        window.dataLayer.push(<?=json_encode($ya_cont)?>);
        //gtag('event', 'view_item', <?=json_encode($ga_arr)?>);
    })
</script>

