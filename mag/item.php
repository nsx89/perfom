<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock')) exit;
$APPLICATION->SetTitle("Блог");
$APPLICATION->SetPageProperty("description", "Европласт - производство полиуретановых изделий, лидер на российском рынке");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}
require($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");

$id = $_REQUEST['ID'];
$current_res = CIBlockElement::GetByID($id);
if ($ar_res = $current_res->GetNextElement()) {
    $item = array_merge($ar_res->GetFields(), $ar_res->GetProperties());
    /*print_r($item);*/
}
$path = $_SERVER['HTTP_REFERER'] . $item['IBLOCK_CODE'] . '/' . $item['FOLDER']['VALUE'] . '/';
$additional = $item['ADDITIONAL']['~VALUE']['TEXT'];
$url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
?>


    <section class="news-item" itemscope itemtype="https://schema.org/Article">

        <?
        $banner_class = '';
        if($item['IMG_TOP']['VALUE'] == 'Y') $banner_class = ' top';
        if($item['IMG_BOTTOM']['VALUE'] == 'Y') $banner_class = ' bottom';
        ?>
        <div class="main-banner news-banner<?=$banner_class?><?if($item['HORIZONTAL_BANNER']['VALUE']!='') echo ' horizontal-banner'?>">
            <div class="news-banner-cont">
                <?
                if ($item['ART_TITLE']['VALUE']) {
                    $name = $item['ART_TITLE']['~VALUE'];
                } else {
                    $name = $item['~NAME'];
                }
                $banner_route = '/'.$item['THUMB']['VALUE'];
                if($item['HORIZONTAL_BANNER']['VALUE']!='') $banner_route = $item['HORIZONTAL_BANNER']['VALUE'];
                $date = $item['DATE']['VALUE'];
                $date = explode('.',$date);
                $day = $date[0].'.'.$date[1];
                $year = $date[2];
                ?>
                <div class="main-slide-caption white">
                    <h1><?=$name?></h1>
                </div>
                <img src="<?='/mag/'.$item['IBLOCK_CODE'].'/'.$item['FOLDER']['VALUE'].$banner_route?>?v=2" alt="<?=$item['NAME']?>">
                <div class="news-banner-info">
                    <?
                    $tags = $item['NEWS_TAGS']['VALUE'];
                    if(!(empty($tags))) {
                        echo '<div class="news-tag-wrap">';
                        foreach ($tags as $t=>$tag) { ?>
                            <div class="news-tag <?=$item['NEWS_TAGS']['VALUE_XML_ID'][$t]?>"><?=$tag?></div>
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
                                        title: '<?=$name?>',
                                        description: "<?= $item['LEAD']['~VALUE']['TEXT'] ?>",
                                        image: "<?=$url.'/mag/'.$item['IBLOCK_CODE'].'/'.$item['FOLDER']['VALUE'].$banner_route?>",
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
                    <div class="news-item-date">
                        <?=$day?>.<?=$year?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Schema Article -->
        <div style="display: none;">
            <link itemprop="mainEntityOfPage" href="<?= $url.$item['DETAIL_PAGE_URL'] ?>">
            <link itemprop="image" href="<?=$url.'/mag/'.$item['IBLOCK_CODE'].'/'.$item['FOLDER']['VALUE'].$banner_route?>?v=1">
            <meta itemprop="headline name" content="<?=$name?>">
            <meta itemprop="description" content="<?= !empty($item['LEAD']['~VALUE']['TEXT']) ? strip_tags($item['LEAD']['~VALUE']['TEXT']) : $name ?>">
            <meta itemprop="author" content="Европласт">
            <meta itemprop="datePublished" content="<?= date('Y-m-d', strtotime($item['DATE']['VALUE'])) ?>">
            <meta itemprop="dateModified" content="<?= date('Y-m-d', strtotime($item['TIMESTAMP_X'])) ?>">
        </div>
        <!-- // -->

        <div class="news-item-article">

            <div class="content-wrapper">
                <div class="news-item-lead">
                    <?= $item['LEAD']['~VALUE']['TEXT'] ?>
                </div>
                <div class="news-item-text" itemprop="articleBody">
                    <?=$item['~DETAIL_TEXT']?>
                </div>
                <?if($item['SOURCE']['VALUE'] != '') {?>
                    <div class="news-item-source">
                        Источник фото - <?=$item['SOURCE']['~VALUE']?>
                    </div>
                <? } ?>
            </div>


            <?if($item['SLIDER']['VALUE']=='Y'):?>
                <?
                $dir = $_SERVER["DOCUMENT_ROOT"].'/mag/'.$item['IBLOCK_CODE'].'/'.$item['FOLDER']['VALUE'].'/';
                $path = '/mag/'.$item['IBLOCK_CODE'].'/'.$item['FOLDER']['VALUE'].'/';
                if(is_dir($dir) && file_exists($dir)) {?>

                <div class="news-item-slider-section">
                    <div class="news-slider-wait" data-type="slider-wait">
                        <img src="/img/preloader.gif" alt="wait...">
                    </div>
                    <div class="main-slider news-item-slider" data-type="news-slider">
                        <?
                        $images = scandir($dir);
                        $images = preg_grep('~\.(jpeg|jpg|png)$~', $images);
                        $images = (array_values($images));
                        for($i=0; $i < count($images); $i++) {
                            if($images[$i] == $item['THUMB']['VALUE']) continue;
                            $image = $path.$images[$i];
                            ?>
                            <div>
                                <a href="<?=$path.$images[$i]?>" data-lightbox="new-slider">
                                    <img src="<?=$path.$images[$i]?>" alt="<?=$item['NAME']?>">
                                </a>
                            </div>
                       <? } ?>
                    </div>
                </div>
                <? } ?>
            <? endif;?>
            <?if($item['VIDEO']['VALUE'] == 'Y') {?>
            <div class="news-item-video-section">
                <iframe width="900" height="506" src="<?=$item['VIDEO']['~VALUE']?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                <?//=$item['VIDEO']['~VALUE']?>
            </div>
            <? } ?>

        </div>
    </section>

<section class="last-news-items">
    <div class="content-wrapper">
        <h1>Последние статьи</h1>
        <div data-type="last-news" class="last-news-slider">
            <?
            $res = CIBlock::GetList(Array('sort'=>'asc'),Array('TYPE'=>'news','ACTIVE'=>'Y'), true);
            $iblock_id = get_news_iblocks();
            $arOrder = Array('PROPERTY_CITY' => 'asc,nulls', 'PROPERTY_DATE' => 'desc');
            $arFilter = Array("SECTION_ID" => 'news-new', $iblock_id, "ACTIVE" => "Y", "ACTIVE_DATE"=>"Y", Array("LOGIC" => "OR", Array('PROPERTY_CITY.ID' => $loc['ID']), Array('PROPERTY_CITY.ID' => false)));
            $arNavStartParams = Array("nPageSize" => '5');
            $arSelect = Array();
            $ar_res = CIBlockElement::GetList($arOrder, $arFilter, false, $arNavStartParams, $arSelect);
            $item_count = $ar_res->SelectedRowsCount();
            $n = 1;
            while ($ob = $ar_res->GetNextElement()):
                $item_block = array_merge($ob->GetFields(), $ob->GetProperties());
                if ($item['ID'] != $item_block['ID'] && $n < 5):
                    ?>
                    <div class="new-main-new">
                        <?
                        if($item_block['NODETAIL']['VALUE']!='Y') {
                            if($item_block['LINK']['VALUE']!='') {
                                echo ' <a href="'.$G_DOMAIN.$item_block['LINK']['VALUE'].'">';
                            }
                            else {
                                echo ' <a href="'.$item_block['DETAIL_PAGE_URL'].'" class="e-pc-3w-elem-a">';
                            }
                        }
                        ?>

                        <div class="img-wrap<?if($item_block['HORIZONTAL']['VALUE']=='Y') echo ' img-wrap-h'?>">
                            <div class="img-wrap-cont">
                                <? if($item_block['UNIQUE']['VALUE']!=='Y'): ?>
                                    <img src="<?='/mag/'.$item_block['IBLOCK_CODE'].'/'.$item_block['FOLDER']['VALUE'].'/'.$item_block['THUMB']['VALUE']?>" alt="<?=$item_block['NAME']?>">
                                <? else: ?>
                                    <img src="<?='/mag/'.$item_block['IBLOCK_CODE'].'/'.$item_block['FOLDER']['VALUE'].'/images/'.$item_block['THUMB']['VALUE']?>" alt="<?=$item_block['NAME']?>">
                                <?endif;?>
                            </div>
                        </div>
                        <?
                        $tags = $item_block['NEWS_TAGS']['VALUE'];
                        if(!(empty($tags))) {
                            echo '<div class="news-tag-wrap">';
                            foreach ($tags as $t=>$tag) { ?>
                                <div class="news-tag <?=$item_block['NEWS_TAGS']['VALUE_XML_ID'][$t]?>"><?=$tag?></div>
                            <? }
                            echo '</div>';
                        } ?>
                        <h2 class="new-main-new-title"><?=$item_block['~NAME']?></h2>
                        <span class="new-main-new-date"><?=$item_block['DATE']['VALUE'];?></span>

                        <?//if($item_block['NODETAIL']['VALUE']=='N') {?>
                            </a>
                        <? //} ?>
                    </div>
                    <? $n++;
                endif;
            endwhile;
            ?>
        </div>
       </div>
</section>

<script src="/mag/news.js?<?=$random?>"></script>

<?
require($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");
if(defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED===true)
{
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog.php");
}