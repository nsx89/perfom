<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock')) exit;
$APPLICATION->SetTitle("Медиацентр");
$APPLICATION->SetPageProperty("description", "Европласт - производство полиуретановых изделий, лидер на российском рынке");
require($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");
$quant_news = 12; //количество статей на странице
$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
?>
    <div class="main-slider-wrap">
        <!--noindex-->
        <div class="main-slider-preloader"><img src="/img/preloader.gif" alt="Подождите..."></div>
        <!--/noindex-->
        <div class="main-slider" data-type="main-slider">
            <div class="main-slide">
                <div class="main-slide-caption">тренды <br>2024</div>
                <img src="/img/mag/1.jpg" alt="уют в каждой детали">
            </div>
            <div class="main-slide">
                <div class="main-slide-caption white">лепнина <br>в&nbsp;современном интерьере</div>
                <img src="/img/mag/2.jpg" alt="шире молдинг, шире возможности">
            </div>
            <div class="main-slide">
                <div class="main-slide-caption white">подбор <br>оригинального декора</div>
                <img src="/img/mag/3.jpg" alt="новое прочтение привычных вещей">
            </div>
        </div>
    </div>
    <section class="news-tabs">
        <div class="content-wrapper">
            <h1>медиацентр</h1>
            <ul data-type="news-tab-slider">
                <li data-type="news-tab" data-val="all" class="active"><a href="#all">Все</a></li>
                <?
                $iblock_id = get_news_iblocks();
                $property_enums = CIBlockPropertyEnum::GetList(Array(), Array("CODE"=>"NEWS_TAGS"));
                $tags_arr = Array();
                $all_count = 0;
                while($enum_fields = $property_enums->GetNext()) {
                    $arFilter = Array("SECTION_ID"=>'news',"ACTIVE"=>"Y","ACTIVE_DATE"=>"Y",Array("LOGIC" => "OR",Array('PROPERTY_CITY.ID'=>$loc['ID']),Array('PROPERTY_CITY.ID'=>false)),'PROPERTY_NEWS_TAGS_VALUE'=>$enum_fields["VALUE"]);
                    $count_res = CIBlockElement::GetList(Array(),$arFilter,false,Array(),Array());
                    $item_count = $count_res->SelectedRowsCount();
                    if($item_count > 0 && !array_key_exists($enum_fields["VALUE"],$tags_arr)) {
                        $tags_arr[$enum_fields["VALUE"]] = $item_count;
                        ?>
                        <li data-type="news-tab" data-val="<?=$enum_fields["XML_ID"];?>" data-count="<?=$item_count?>">
                            <a href="#<?=$enum_fields["XML_ID"]?>"><?=$enum_fields["VALUE"]?></a>
                        </li>
                        <?
                        $data_cat .= $enum_fields["XML_ID"].',';
                        $all_count += $item_count;
                    }
                }
                $data_cat = substr($data_cat,0,-1);?>
            </ul>
        </div>
    </section>
    <section class="news-tabs-wrap">
        <div class="content-wrapper" data-type="items-list" data-val="blog" data-city="<?=$loc['ID']?>" data-cat="<?=$data_cat?>">
            <div style="display: flex; justify-content: center; width: 100%; margin-bottom: 30px;">
                <img src="/img/preloader.gif" alt="Подождите...">
            </div>
        </div>
        <div class="pagination"<?if(!$item_count || $item_count<=12) echo ' style="display:none"'?>>
            <div class="pag-title">Страницы</div>
            <div class="pag-wrap"<?if($page == 'all') echo ' style="display:none"'?>>
                <div class="pag" data-items="<?=$all_count?>" data-onpage="<?=$quant_news?>" data-type="pag" data-current="<?=$page?>" data-all="<?=$all_count?>"></div>
                <div class="show-all-btn" data-type="show-all">Показать все</div>
                <div class="show-wait">
                    <img src="/img/preloader.gif" alt="Подождите...">
                </div>
            </div>
            <div class="show-per-page<?if($page == 'all') echo ' active'?>">
                <div class="show-per-page-txt">
                    Показаны все статьи
                </div>
                <div class="show-per-page-btn" data-type="per-page">
                    Показать постранично
                </div>
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
