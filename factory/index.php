<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock')) exit;
$APPLICATION->SetTitle("Производство");
$APPLICATION->SetPageProperty("description", "Европласт - производство полиуретановых изделий, лидер на российском рынке");
require($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");
?>

<?
/* --- Media News --- */
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/media/classes/media_pages.php');
global $DB;
use Media\Media;
?>
<script type="text/javascript" src="/js/dots.js?<?= time() ?>"></script>
<?
/* --- // --- */
?>

<div class="main-slider-wrap">
    <!--noindex--><div class="main-slider-preloader"><img src="/img/preloader.gif" alt="Подождите..."></div><!--/noindex-->
    <div class="main-slider" data-type="main-slider">
        <div class="main-slide">
            <div class="main-slide-caption">внимание <br>к деталям</div>
            <img class="img-load" src="/img/1x1.png" data-src="/img/factory/Perfom_poduction_01.jpg" alt="внимание к деталям">
        </div>
        <div class="main-slide">
            <div class="main-slide-caption white">уникальные <br>разработки</div>
            <img class="img-load" src="/img/1x1.png" data-src="/img/factory/Perfom_poduction_02.jpg" alt="уникальные разработки">
        </div>
        <div class="main-slide">
            <div class="main-slide-caption white">совершенные <br>технологии</div>
            <img class="img-load" src="/img/1x1.png" data-src="/img/factory/Perfom_poduction_03.jpg" alt="совершенные технологии">
        </div>
        <div class="main-slide">
            <div class="main-slide-caption white">непрерывное <br>развитие</div>
            <img class="img-load" src="/img/1x1.png" data-src="/img/factory/5.jpg" alt="непрерывное развитие">
        </div>
    </div>
</div>

<section class="main-pref factory-pref">
    <div class="content-wrapper">
        <div class="main-pref-txt">
            <h1>производство</h1>
            <p class="main-pref-annotation">
                Компания "Декор" является одним <br>
                из крупнейших производителей лепнины <br>
                и архитектурного декора <br>
                из перфома и пенополиуретана в мире.
            </p>
            <p>
                Собственный производственный комплекс полного цикла <br>
                площадью 22 тысячи квадратных метров располагается <br>
                в городском округе Чехов Московского региона.
            </p>
            <p>
        </div>
        <div class="main-pref-slider" data-type="factory-pref-slider">
            <div class="main-pref-slide factory-pref-slide">
                <span class="factory-pref-number">22000</span>
                <span class="factory-pref-txt">
                    кв. метров <br>
                    производственных <br>
                    площадей
                </span>
            </div>
            <div class="main-pref-slide factory-pref-slide">
                <span class="factory-pref-number">7000</span>
                <span class="factory-pref-txt">
                    квадратных метров <br>
                    складских площадей
                </span>
            </div>
            <div class="main-pref-slide factory-pref-slide">
                <span class="factory-pref-number">600</span>
                <span class="factory-pref-txt">
                    сотрудников <br>
                    в 5 странах
                </span>
            </div>
            <div class="main-pref-slide factory-pref-slide">
                <span class="factory-pref-number">40</span>
                <span class="factory-pref-txt">
                    стран где реализуется <br>
                    продукция компании
                </span>
            </div>
        </div>

    </div>
</section>

<section class="main-news main-section">
<div class="content-wrapper">
    <h2 class="main-blocks-title"><?= MEDIA_NAME ?></h2>
        <a href="<?= MEDIA_FOLDER ?>/" class="main-blocks-link"><span><? /* Смотреть все&nbsp;новости*/ ?>Перейти в <?= MEDIA_NAME ?></span> <i class="icon-long-arrow"></i></a>
        <div class="main-news-slider" data-type="main-news-slider">

            <?
            $total_count = 0; //общее количество всех статей
            $n = 2; 
            $n_slider = 1;
            $items = Media::list('date DESC,', '', 6);
            foreach ($items AS $item) {
                $item_link = Media::siteLink($item);
                $img_path = Media::sitePreviewPictureLink($item);
                ?>
                <div class="main-news-slide main-news-slide-<?=$n_slider?>">
                    <article>
                        <a href="<?= $item_link ?>"></a>
                        
                        <div class="main-news-slide-img-back" style="background: url(<?= $img_path ?>) no-repeat center center; background-size: cover;">
                            <img src="<?= MEDIA_FOLDER ?>/upload/v.png" alt="<?= $item['name'] ?>">
                        </div>
                        
                        <h3 class="main-news-slide-h3"><?= htmlspecialchars_decode($item['name']) ?></h3>
                        <span class="main-news-slide-date"><?= Media::siteDateFull($item) ?></span>
                    </article>
                </div>  
                <?   
                if ($n_slider++>3) $n_slider=1;
            } ?>

             <? 
             /*
                //$res = CIBlock::GetList(Array('sort'=>'asc'),Array('TYPE'=>'news','ACTIVE'=>'Y',"ACTIVE_DATE"=>"Y"), true);
                
                $total_count = 0; //общее количество всех статей
                $n = 2; 
                $n_slider = 1;
                $iblock_id = get_news_iblocks(); 
                
                $arOrder = Array('PROPERTY_CITY'=>'asc,nulls','PROPERTY_DATE'=>'desc');
                //выводим глобальные новости и региональные (если регион совпадает)
                $arFilter = Array("SECTION_ID"=>'news',$iblock_id,"ACTIVE"=>"Y","ACTIVE_DATE"=>"Y",Array("LOGIC" => "OR",Array('PROPERTY_CITY.ID'=>false),Array('PROPERTY_CITY.ID'=>$loc['ID'])), Array("LOGIC" => "OR",Array('PROPERTY_NEWS_TAGS_VALUE'=>'Новости партнеров'),Array('PROPERTY_NEWS_TAGS_VALUE'=>'Мероприятия'),Array('PROPERTY_NEWS_TAGS_VALUE'=>'Выставки'),Array('PROPERTY_NEWS_TAGS_VALUE'=>'Новость')));
                $arNavStartParams = Array("nPageSize"=>6);
                $arSelect = Array();
                $ar_res = CIBlockElement::GetList($arOrder,$arFilter,false,$arNavStartParams,$arSelect);
                
                while($ob = $ar_res->GetNextElement()): 
                    $item = array_merge($ob->GetFields(), $ob->GetProperties()); 
                    ?>
                <div class="main-news-slide main-news-slide-<?=$n_slider?>">
                    <article>
                         <?
                            if($item['NODETAIL']['VALUE']=='N') {
                                if($item['LINK']['VALUE']!='') {
                                    echo '<a href="'.$item['LINK']['VALUE'].'"></a>';
                                }
                                else {
                                    echo '<a href="'.$item['DETAIL_PAGE_URL'].'"></a>';
                                }
                            }
                        ?>
                        <? if($item['UNIQUE']['VALUE']=='N'): ?>
                                <?
                                $img_path = '/mag/'.$item['IBLOCK_CODE'].'/'.$item['FOLDER']['VALUE'].'/v.jpg';
                                //$img_path = '/mag/'.$item['IBLOCK_CODE'].'/'.$item['FOLDER']['VALUE'].'/'.$item['THUMB']['VALUE'];
                                //$img_path = get_resized_img($img_path,278,408);
                                ?>
                                <img class="img-load" src="/img/1.png" data-src="<?=$img_path?>" alt="<?=$item['NAME']?>">
                            <? else: ?>
                                <?
                                $img_path = '/mag/'.$item['IBLOCK_CODE'].'/'.$item['FOLDER']['VALUE'].'/images/v.jpg';
                                //$img_path = '/mag/'.$item['IBLOCK_CODE'].'/'.$item['FOLDER']['VALUE'].'/images/'.$item['THUMB']['VALUE'];
                                //$img_path = get_resized_img($img_path,278,408);
                                ?>
                                <img class="img-load" src="/img/1.png" data-src="<?=$img_path?>" alt="<?=$item['NAME']?>">
                         <? endif; ?>
                        
                        <h3><?=htmlspecialchars_decode($item['NAME'])?></h3>
                        <span><?=$item['DATE']['VALUE']?></span>
                    </article>
                </div>  
            <? 
                    if ($n_slider++>3) $n_slider=1;
                endwhile; 
            */
            ?>
        </div>
</div>
</section>

<?
require($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");
if(defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED===true)
{
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog.php");
}