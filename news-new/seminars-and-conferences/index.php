<?

define("LEGIT_REQUEST", true);

require_once ($_SERVER["DOCUMENT_ROOT"] . "/responsive/include/ed/Mobile_Detect_Point.php");

$detectPoint = new Mobile_Detect_Point;

if ($detectPoint->isMobile()) {

    require_once($_SERVER["DOCUMENT_ROOT"] . "/responsive/news-new/seminars-and-conferences/index.php");

} else {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
    $APPLICATION->SetTitle("Новости");
    $APPLICATION->AddChainItem("Новости", "/news-new/");
    if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {

        exit;
    }

    require($_SERVER["DOCUMENT_ROOT"] . "/include/catalogue/header.php");
    ?>
    <? $request = empty($_SERVER['HTTPS']) ? 'http://' : 'https://'; ?>
    <? $server_path = '/news-new/'; ?>

    <link rel="stylesheet"
          href="<?= $server_path ?>seminars-and-conferences/seminars-and-conferences-style.css?<?= $release ?>"
          type="text/css"/>
    <link rel="stylesheet" href="<?= $server_path ?>style.css?<?= $release ?>" type="text/css"/>

    <?
    $id = $_REQUEST['ID'];
    $current_res = CIBlockElement::GetByID($id);
    if ($ar_res = $current_res->GetNextElement()) {
        $item = array_merge($ar_res->GetFields(), $ar_res->GetProperties());
    }
    $path = $_SERVER['HTTP_REFERER'] . $item['IBLOCK_CODE'] . '/' . $item['FOLDER']['VALUE'] . '/';
    $additional = $item['ADDITIONAL']['~VALUE']['TEXT'];
    ?>
    <? $APPLICATION->AddChainItem("Семинары и конференции", "#"); ?>
    <div id="middle">

    <div class="e-page-content e-pc-news">
        <div class="e-pc-left-column">
            <div class="e-pc-main-img">
                <div class="e-pct-title">
                    <h2><?= $item['IBLOCK_NAME']; ?></h2>
                </div>
                <img src="<?= $server_path . 'seminars-and-conferences/' . $item['FOLDER']['VALUE'] . '/' . $item['THUMB']['VALUE'] ?>"
                     alt="alt">
            </div>
            <?
            if ($item['ART_TITLE']['VALUE']) {
                $name = $item['ART_TITLE']['~VALUE'];
            } else {
                $name = $item['~NAME'];
            }
            ?>
            <h1 class="e-pc-article-title"><?= $name ?></h1>
            <p class="e-pc-article-lead"><?= $item['LEAD']['~VALUE']['TEXT'] ?></p>

            <? if ($item['TWOCOLUMNS']['VALUE'] == 'Y' && $item['SECONDCOLUMN']['VALUE']): ?>
                <div class="e-pc-left-text-block">
                    <?= $item['~DETAIL_TEXT'] ?>
                </div>
                <div class="e-pc-right-text-block">
                    <?= $item['SECONDCOLUMN']['~VALUE']['TEXT'] ?>
                </div>
            <? else: ?>
                <?= $item['~DETAIL_TEXT'] ?>
            <? endif; ?>
        </div>

        <div class="e-pc-right-column">
            <div class="e-pc-news-block">
                <h3>Последние новости:</h3>
                <?
                $arOrder = Array('PROPERTY_CITY' => 'asc,nulls', 'PROPERTY_DATE' => 'desc');
                $arFilter = Array("SECTION_ID" => 'news-new', Array("LOGIC" => "OR", Array('IBLOCK_ID' => '27'), Array('IBLOCK_ID' => '28'), Array('IBLOCK_ID' => '29'), Array('IBLOCK_ID' => '31'), Array('IBLOCK_ID' => '33')), "ACTIVE" => "Y", Array("LOGIC" => "OR", Array('PROPERTY_CITY.ID' => $loc['ID']), Array('PROPERTY_CITY.ID' => false)));
                $arNavStartParams = Array("nPageSize" => '4');
                $arSelect = Array();
                $ar_res = CIBlockElement::GetList($arOrder, $arFilter, false, $arNavStartParams, $arSelect);
                $item_count = $ar_res->SelectedRowsCount();
                ?>
                <? $n = 1; ?>
                <? while ($ob = $ar_res->GetNextElement()): ?>

                <? $item_block = array_merge($ob->GetFields(), $ob->GetProperties()); ?>

                <? if ($item['ID'] != $item_block['ID'] && $n < 4): ?>
                <div class="e-pc-3w-elem">

                    <?
                    if ($item_block['NODETAIL']['VALUE'] == 'N') {
                        if ($item_block['UNIQUE']['VALUE'] == 'Y') {
                            echo ' <a href="' . $server_path . $item_block['IBLOCK_CODE'] . '/' . $item_block['FOLDER']['VALUE'] . '" class="e-pc-3w-elem-a"></a>';
                        } else {
                            echo ' <a href="' . $item_block['DETAIL_PAGE_URL'] . '" class="e-pc-3w-elem-a"></a>';
                        }
                    }
                    ?>

                    <div class="e-pc-elem-top">

                        <div class="e-pct-title">

                            <h2><?= $item_block['IBLOCK_NAME'] ?></h2>

                        </div>

                        <? if ($item_block['UNIQUE']['VALUE'] == 'N'): ?>
                            <img src="<?= $server_path . $item_block['IBLOCK_CODE'] . '/' . $item_block['FOLDER']['VALUE'] . '/' . $item_block['THUMB']['VALUE'] ?>"/>
                        <? else: ?>
                            <img src="<?= $server_path . $item_block['IBLOCK_CODE'] . '/' . $item_block['FOLDER']['VALUE'] . '/images/' . $item_block['THUMB']['VALUE'] ?>"/>
                        <? endif; ?>


                    </div>

                    <div class="e-pc-elem-bot">

                        <div class="e-pceb-title">

                            <p><?= $item_block['~NAME'] ?></p>

                        </div>

                        <span></span>

                        <p><?= $item_block['PREVIEW_TEXT']; ?></p>

                        <? if ($item_block['NODETAIL']['VALUE'] == 'N'): ?>

                        <div class="e-pceb-actions">

                            <a>

                                <text><?= $item_block['DATE']['VALUE']; ?></text>
                                <text>Далее<i class="br-icon-Arrow-right"></i></text>

                                <? else: ?>

                                <div class="e-pceb-actions" style="cursor:default;">

                                    <a>

                                        <text><?= $item_block['DATE']['VALUE']; ?></text>

                                        <? endif; ?>

                                    </a>

                                </div>

                        </div>

                    </div>

                    <? $n++; ?>
                    <? endif; ?>



                    <? endwhile; ?>

                </div>
                <div class="e-pc-contacts-block">
                    <p>
                        <?
                        if ($additional) echo $additional;
                        ?>
                    </p>
                    <a href="/news/" class="e-pc-return-news"><i class="icon-left-arrow"><span
                                    class="path1"></span><span class="path2"></span><span class="path3"></span><span
                                    class="path4"></span></i>Вернуться к списку новостей</a>
                </div>
            </div>
        </div>

    </div>

    <script type="text/javascript">
        //  $(document).ready(function(){
        //     heightBlock = $('.e-pc-news-block').height()+$('.e-pc-contacts-block').height()+40;
        //     if($('.e-pc-left-column').height()<heightBlock) {
        //         $('.e-pc-news').attr('style','min-height:'+heightBlock+'px');
        //     }
        // })
    </script>

    <? require($_SERVER["DOCUMENT_ROOT"] . "/include/catalogue/footer.php");
    if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) {
        require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog.php");
    }
}
