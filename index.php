<?
/*ini_set('display_errors', 1);
error_reporting(E_ALL);*/

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}
$APPLICATION->SetTitle("Перфом - производство полиуретановых изделий, лидер на российском рынке");
$APPLICATION->SetPageProperty("description", "Перфом - производство полиуретановых изделий, лидер на российском рынке");

require($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");
?>
<div class="main-slider-wrap">
    <!--noindex--><div class="main-slider-preloader"><img src="/img/preloader.gif" alt="Подождите..."></div><!--/noindex-->
    <div class="main-slider" data-type="main-slider">

		 <? /*
        <div class="main-slide">
            <div class="main-slide-caption">Выиграй MacBook <br>
                в конкурсе интерьерных <br>
                решений с коллекцией <br>
                New Art Deco
                <a href="https://konkursevroplast.ru/?utm_source=site_ep" class="main-blocks-link" target="_blank"><span>принять участие</span> <i class="icon-long-arrow"></i></a>
            </div>
            <img src="/img/main-slider/konkurs.jpg" alt="Выиграй MacBook">
        </div>
	   
        <div class="main-slide">
            <div class="main-slide-caption white">Новая <br>декоративная <br>панель
                <a href="/dekorativnii-paneli/6-59-806/" class="main-blocks-link"><span>смотреть в каталоге</span> <i class="icon-long-arrow"></i></a>
            </div>
            <img src="/img/main-slider/Evroplast_slider_6.59.806.jpg" alt="Новая декоративная панель">
        </div>
        <div class="main-slide">
            <div class="main-slide-caption">Новая <br>декоративная <br>панель
                <a href="/dekorativnii-paneli/6-59-804/" class="main-blocks-link"><span>смотреть в каталоге</span> <i class="icon-long-arrow"></i></a>
            </div>
            <img src="/img/main-slider/Evroplast_slider_6.59.804.jpg" alt="Новая декоративная панель">
        </div>
		*/ ?>
        <?/*<div class="main-slide" style="justify-content: flex-end;">
            <img src="/img/main-slider/Evroplast_slider_newyear_24.jpg" alt="С Новым годом!">
        </div>*/?>

		<div class="main-slide">
            <div class="main-slide-caption white">Новая <br>декоративная <br>панель
                <a href="/collection/new_art_deco/#dekorativnii-paneli" class="main-blocks-link"><span>смотреть новинки</span> <i class="icon-long-arrow"></i></a>
            </div>
            <img src="/img/main-slider/Evroplast_slider_new_panels.jpg" alt="Новая декоративная панель">
        </div>
		<div class="main-slide">
            <div class="main-slide-caption white">Новая <br>коллекция <br>NEW ART DECO
			<a href="/collection/new_art_deco/" class="main-blocks-link"><span>смотреть коллекцию</span> <i class="icon-long-arrow"></i></a>
			</div>
            <img src="/img/main-slider/new_art_deco_1.jpg" alt="Новая коллекция NEW ART DECO">
        </div>
		<div class="main-slide">
            <div class="main-slide-caption">Новая <br>коллекция <br>NEW ART DECO
                <a href="/collection/new_art_deco/" class="main-blocks-link"><span>смотреть коллекцию</span> <i class="icon-long-arrow"></i></a>
            </div>
            <img src="/img/main-slider/new_art_deco_2.jpg" alt="Новая коллекция NEW ART DECO">
        </div>
        <div class="main-slide">
            <div class="main-slide-caption white">стиль,<br>отточенный<br>временем</div>
            <img src="/img/main-slider/Perfom_header_06.jpg" alt="стиль, отточенный временем">
        </div>
        <div class="main-slide">
            <div class="main-slide-caption" style="top: 115px">для&nbsp;красоты <br>стен и&nbsp;потолков</div>
            <img src="/img/main-slider/02.jpg" alt="для красоты стен и потолков">
        </div>
        <div class="main-slide">
            <div class="main-slide-caption" style="top: 115px">элегантность <br>в&nbsp;каждой детали</div>
            <img src="/img/main-slider/03.jpg" alt="элегантность в каждой детали">
        </div>
        <div class="main-slide">
            <div class="main-slide-caption">для воплощения <br>ваших идей</div>
            <img src="/img/main-slider/05.jpg" alt="для воплощения ваших идей">
        </div>
        <div class="main-slide">
            <div class="main-slide-caption">классика <br>в современном<br>дизайне</div>
            <img src="/img/main-slider/Perfom_header_05.jpg" alt="классика в современном дизайне">
        </div>
        
    </div>
</div>

<section class="main-pref">
    <div class="content-wrapper">
        <div class="main-pref-txt">
            <h2>Перфом</h2>
            <p class="main-pref-annotation">
                Перфом — это новый материал,<br>разработанный в России
                специалистами компании Европласт.
            </p>
            <p>
                Перфом – вспененный композиционный полимер высокой плотности<br>
                на основе полистирола. Он отличается особой прочностью и надежностью.<br>
Мы долго улучшали технологию работы с композитом,<br>
и перфом стал логичным завершением длительных исследований.
            </p>
            <p>
                Приобрести лепнину из перфома можно в розничных точках<br>
                в каждом городе России, СНГ и стран Балтии,<br>
                а также купить в интернет‑магазине.
            </p>
        </div>
        <div class="main-pref-slider" data-type="main-pref-slider">
            <div class="main-pref-slide">
                <i class="main-pref-icon main-pref-icon1"></i>
                идеальные<br>стыки
            </div>
            <div class="main-pref-slide">
                <i class="main-pref-icon main-pref-icon2"></i>
                особо прочный<br> и долговечный
            </div>
            <div class="main-pref-slide">
                <i class="main-pref-icon main-pref-icon3"></i>
                влагостойкость<br>&nbsp;
            </div>
            <div class="main-pref-slide">
                <i class="main-pref-icon main-pref-icon4"></i>
                простота<br>монтажа
            </div>
            <div class="main-pref-slide">
                <i class="icon-paint"></i>
                простота<br>покраски
            </div>
            <div class="main-pref-slide">
                <i class="icon-pattern"></i>
                самый четкий <br>рисунок
            </div>

            <? /*
            <div class="main-pref-slide">
                <i class="icon-brilliant"></i>
                повышенная <br>прочность
            </div>
            <div class="main-pref-slide">
                <i class="icon-thumb"></i>
                простой <br>монтаж
            </div>
            <div class="main-pref-slide">
                <i class="icon-snowflake"></i>
                выдающаяся <br>белизна
            </div>
            <div class="main-pref-slide">
                <i class="icon-paint"></i>
                красится <br>в любой цвет
            </div>
            <div class="main-pref-slide">
                <i class="icon-umbrella"></i>
                уникальная <br>влагостойкость
            </div>
            <div class="main-pref-slide">
                <i class="icon-pattern"></i>
                самый четкий <br>рисунок
            </div>
            */ ?>
        </div>

    </div>
</section>


<section class="main-gallery">
    <div class="content-wrapper">
        <div class="main-gallery-title-block">
            <h2 class="main-blocks-title">Сделайте интерьер лучше</h2>
            <a href="/karnizy/" class="main-blocks-link"><span>Перейти к&nbsp;каталогу</span> <i class="icon-long-arrow"></i></a>
        </div>
        <div class="main-gallery-slider main-gallery-slider-loading" data-type="main-gallery-slider" data-gallery="wrapper" data-city="<?= $my_city ?>">
		
			<? /* require_once($_SERVER["DOCUMENT_ROOT"] . "/include/main_gallery.php"); */ ?>
			
        </div>
    </div>
</section>

<div class="main-events-catalogues main-section">
    <div class="content-wrapper">

        <section class="main-events">
            <h2 class="main-blocks-title"><?= MEDIA_NAME ?></h2>
            <div class="main-events-wrap">
                <div class="main-events-slider" data-type="main-events-slider">
				 <? 
					$res = CIBlock::GetList(Array('sort'=>'asc'),Array('TYPE'=>'news','ACTIVE'=>'Y',"ACTIVE_DATE"=>"Y"), true);
                    $iblock_id = get_news_iblocks();
                    $arOrder = Array('PROPERTY_CITY'=>'asc,nulls','PROPERTY_DATE'=>'desc');
                    //выводим глобальные новости и региональные (если регион совпадает)
                    $arFilter = Array("SECTION_ID"=>'news',$iblock_id,"ACTIVE"=>"Y","ACTIVE_DATE"=>"Y",Array("LOGIC" => "OR",Array('PROPERTY_CITY.ID'=>false),Array('PROPERTY_CITY.ID'=>$loc['ID'])));
                    $arNavStartParams = Array("nPageSize"=>2);
                    $arSelect = Array();
                    $ar_res = CIBlockElement::GetList($arOrder,$arFilter,false,$arNavStartParams,$arSelect);
                while($ob = $ar_res->GetNextElement()):
                    $item = array_merge($ob->GetFields(), $ob->GetProperties()); 
					?>
                    <div class="main-events-slide">
                        <article>
                            <?
							if($item['NODETAIL']['VALUE']!='Y') {
								if($item['LINK']['VALUE']!='') {
									echo '<a href="'.$item['LINK']['VALUE'].'"></a>';
								}
								else {
									echo '<a href="'.$item['DETAIL_PAGE_URL'].'"></a>';
								}
							}
							?>
							<span><?=$item['DATE']['VALUE']?></span>
							<? if($item['UNIQUE']['VALUE']!='Y'): ?>
                                <?
                                $img_path = '/mag/'.$item['IBLOCK_CODE'].'/'.$item['FOLDER']['VALUE'].'/'.$item['THUMB']['VALUE'];
                                //$img_path = get_resized_img($img_path,251,130);
                                ?>
								<img src="<?=$img_path?>" alt="<?=$item['NAME']?>">
                            <? else: ?>
                                <?
                                $img_path = '/mag/'.$item['IBLOCK_CODE'].'/'.$item['FOLDER']['VALUE'].'/images/'.$item['THUMB']['VALUE'];
                                //$img_path = get_resized_img($img_path,251,130);
                                ?>
								<img src="/img/event-5.png" alt="ТВ-проект. «Фазенда Лайф». Амстердам">
                         
                         <? endif; ?>
							
                            
                       
                            <h2><?=htmlspecialchars_decode($item['NAME'])?></h2>
                        </article>
                    </div>
				<? endwhile; ?>
                </div>
                <a href="/mag/" class="main-events-catalogues-link"><span>Смотреть еще</span></a>
            </div>
        </section>

        <section class="main-catalogues">
            <div>
                <h2 class="main-blocks-title">каталоги</h2>
                <a href="/download/" class="main-catalogues-link main-blocks-link"><span>Смотреть все&nbsp;каталоги</span> <i class="icon-long-arrow"></i></a>
            </div>
            <div class="main-catalogue-slider" data-type="main-catalogue-slider">
                
    			<? require($_SERVER["DOCUMENT_ROOT"] . "/download/catalogs.php"); ?>
				
            </div>
        </section>
    </div>
</div>


<section class="main-news main-section">
    <div class="content-wrapper">
        <h2 class="main-blocks-title"><? /* новости */ ?>&nbsp;</h2>
        <a href="/mag/#all" class="main-blocks-link"><span><? /* Смотреть все&nbsp;новости*/ ?>Перейти в <?= MEDIA_NAME ?></span> <i class="icon-long-arrow"></i></a>
        <div class="main-news-slider" data-type="main-news-slider">
             <? 
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
                            if($item['NODETAIL']['VALUE']!='Y') {
                                if($item['LINK']['VALUE']!='') {
                                    echo '<a href="'.$item['LINK']['VALUE'].'"></a>';
                                }
                                else {
                                    echo '<a href="'.$item['DETAIL_PAGE_URL'].'"></a>';
                                }
                            }
                        ?>
                        <? if($item['UNIQUE']['VALUE']!='Y'): ?>
                                <?
                                $img_path = '/mag/'.$item['IBLOCK_CODE'].'/'.$item['FOLDER']['VALUE'].'/v.jpg?v=1';
                                //$img_path = '/mag/'.$item['IBLOCK_CODE'].'/'.$item['FOLDER']['VALUE'].'/'.$item['THUMB']['VALUE'];
                                //$img_path = get_resized_img($img_path,278,408);
                                ?>
                                <img src="<?=$img_path?>" alt="<?=$item['NAME']?>">
                            <? else: ?>
                                <?
                                $img_path = '/mag/'.$item['IBLOCK_CODE'].'/'.$item['FOLDER']['VALUE'].'/images/v.jpg?v=1';
                                //$img_path = '/mag/'.$item['IBLOCK_CODE'].'/'.$item['FOLDER']['VALUE'].'/images/'.$item['THUMB']['VALUE'];
                                //$img_path = get_resized_img($img_path,278,408);
                                ?>
                                <img src="<?=$img_path?>" alt="<?=$item['NAME']?>">
                         <? endif; ?>
                        
                        <h3><?=htmlspecialchars_decode($item['NAME'])?></h3>
                        <span><?=$item['DATE']['VALUE']?></span>
                    </article>
                </div>  
            <? 
                    if ($n_slider++>3) $n_slider=1;
                endwhile; ?>
        </div>
    </div>
</section>

<? 
/*
	$res = CIBlock::GetList(Array('sort'=>'asc'),Array('TYPE'=>'news','ACTIVE'=>'Y',"ACTIVE_DATE"=>"Y"), true);
    $total_count = 0; //общее количество всех статей
    $n = 2; 
	$n_slider = 1;
    $iblock_id = get_news_iblocks();
	$s_items = array();
    
    $arOrder = Array('PROPERTY_CITY'=>'asc,nulls','PROPERTY_DATE'=>'desc');
    //выводим глобальные новости и региональные (если регион совпадает)
    $arFilter = Array("SECTION_ID"=>'news',$iblock_id,"ACTIVE"=>"Y","ACTIVE_DATE"=>"Y",Array("LOGIC" => "OR",Array('PROPERTY_CITY.ID'=>false),Array('PROPERTY_CITY.ID'=>$loc['ID'])),'PROPERTY_NEWS_TAGS_VALUE'=>'Стили');
    $arNavStartParams = Array("nPageSize"=>7);
    $arSelect = Array();
    $ar_res = CIBlockElement::GetList($arOrder,$arFilter,false,$arNavStartParams,$arSelect);
	
    while($ob = $ar_res->GetNextElement()) { 
		$item = array_merge($ob->GetFields(), $ob->GetProperties()); 
			
		if($item['NODETAIL']['VALUE']=='N') 
          if($item['LINK']['VALUE']!='') $s_items[$n_slider]['LINK'] = $item['LINK']['VALUE'];
		  else  $s_items[$n_slider]['LINK'] = $item['DETAIL_PAGE_URL'];
  
		if($item['UNIQUE']['VALUE']=='N') {
            $img_path_v = '/mag/'.$item['IBLOCK_CODE'].'/'.$item['FOLDER']['VALUE'].'/v.jpg';
			$img_path_q = '/mag/'.$item['IBLOCK_CODE'].'/'.$item['FOLDER']['VALUE'].'/q.jpg';
            //$img_path = get_resized_img($img_path,294,179);
        } else {
     
            $img_path_v = '/mag/'.$item['IBLOCK_CODE'].'/'.$item['FOLDER']['VALUE'].'/images/v.jpg';
			$img_path_q = '/mag/'.$item['IBLOCK_CODE'].'/'.$item['FOLDER']['VALUE'].'/images/q.jpg';
            //$img_path = get_resized_img($img_path,294,179);
        }
		$s_items[$n_slider]['IMG_V'] = $img_path_v;
		$s_items[$n_slider]['IMG_Q'] = $img_path_q;
		$s_items[$n_slider]['NAME'] = $item['NAME'];
	
		$n_slider++;
	}
?>

<section class="main-articles main-section">
    <div class="content-wrapper">
        <h2 class="main-blocks-title">Статьи</h2>
        <a href="/mag/#styles" class="main-blocks-link"><span>Смотреть все&nbsp;статьи</span> <i class="icon-long-arrow"></i></a>
        <div class="main-articles-slider" data-type="main-articles-slider">
            <div class="main-articles-slide main-articles-slide-1">
                <article>
                    <a href="<?=$s_items[1]['LINK']?>"></a>
                    <img src="<?=$s_items[1]['IMG_Q']?>" alt="<?=$s_items[$n_slider]['NAME']?>">
                    <div>
                        <h3><?=$s_items[1]['NAME']?></h3>
                        <p></p>
                        <span>Подробнее</span>
                    </div>
                </article>
            </div>
            <div class="main-articles-slide main-articles-slide-4">
                <article style="background-image: url('<?=$s_items[2]['IMG_V']?>')">
                    <a href="<?=$s_items[2]['LINK']?>"></a>
                    <img class="article-base-vert" src="/img/article-4-base.png" alt="<?=$s_items[2]['NAME']?>">
                    <img class="article-base-hor" src="/img/article-1-base.png" alt="<?=$s_items[2]['NAME']?>">
                    <div>
                        <h3><?=$s_items[2]['NAME']?></h3>
                        <p></p>
                        <span>Подробнее</span>
                    </div>
                </article>
            </div>
            <div class="main-articles-slide main-articles-slide-4">
                <article style="background-image: url('<?=$s_items[3]['IMG_V']?>')">
                    <a href="<?=$s_items[3]['LINK']?>"></a>
                    <img class="article-base-vert" src="/img/article-4-base.png" alt="<?=$s_items[3]['NAME']?>">
                    <img class="article-base-hor" src="/img/article-1-base.png" alt="<?=$s_items[3]['NAME']?>">
                    <div>
                        <h3><?=$s_items[3]['NAME']?></h3>
                        <? //<p>Все больше людей задумываются об&nbsp;экологии и&nbsp;осознанном потреблении. И&nbsp;это&nbsp;уже не&nbsp;дань моде, а необходимость сохранить природные ресурсы.</p> ?>
                        <span>Подробнее</span>
                    </div>
                </article>
            </div>
            <div class="main-articles-slide main-articles-slide-2">
                <article style="background-image: url('<?=$s_items[4]['IMG_Q']?>')">
                    <a href="<?=$s_items[4]['LINK']?>"></a>
                    <img src="/img/article-2-base.png" alt="<?=$s_items[4]['NAME']?>">
                    <div>
                        <h3><?=$s_items[4]['NAME']?></h3>
                        <span>Подробнее</span>
                    </div>
                </article>
                <article style="background-image: url('<?=$s_items[5]['IMG_Q']?>')">
                    <a href="<?=$s_items[5]['LINK']?>"></a>
                    <img src="/img/article-3-base.png" alt="<?=$s_items[5]['NAME']?>">
                    <div>
                        <h3><?=$s_items[5]['NAME']?></h3>
                        <span>Подробнее</span>
                    </div>
                </article>
            </div>
            <div class="main-articles-slide main-articles-slide-2">
                <article style="background-image: url('<?=$s_items[6]['IMG_Q']?>')">
                    <a href="<?=$s_items[6]['LINK']?>"></a>
                    <img src="/img/article-2-base.png" alt="<?=$s_items[6]['NAME']?>">
                    <div>
                        <h3><?=$s_items[6]['NAME']?></h3>
                        <span>Подробнее</span>
                    </div>
                </article>
                <article style="background-image: url('<?=$s_items[7]['IMG_Q']?>')">
                    <a href="<?=$s_items[7]['LINK']?>"></a>
                    <img src="/img/article-3-base.png" alt="<?=$s_items[7]['NAME']?>">
                    <div>
                        <h3><?=$s_items[7]['NAME']?></h3>
                        <span>Подробнее</span>
                    </div>
                </article>
            </div>
       </div>
    </div>
</section>
*/
?>

<? /*
<section class="main-articles main-section">
    <div class="content-wrapper">
        <h2 class="main-blocks-title">Статьи</h2>
        <a href="/mag/#styles" class="main-blocks-link"><span>Смотреть все&nbsp;статьи</span> <i class="icon-long-arrow"></i></a>
        <div class="main-articles-slider" data-type="main-articles-slider">
            <div class="main-articles-slide main-articles-slide-1">
                <article>
                    <a href="#"></a>
                    <img src="/img/Ecostyle.jpg" alt="Экостиль в интерьере: единение с природой">
                    <div>
                        <h3>Экостиль в&nbsp;интерьере: единение с&nbsp;природой</h3>
                        <p>Все больше людей задумываются об&nbsp;экологии и&nbsp;осознанном потреблении. И&nbsp;это&nbsp;уже не&nbsp;дань моде, а необходимость сохранить природные ресурсы.</p>
                        <span>Подробнее</span>
                    </div>
                </article>
            </div>
            <div class="main-articles-slide main-articles-slide-4">
                <article style="background-image: url('/img/Khabarovsk.jpg')">
                    <a href="#"></a>
                    <img class="article-base-vert" src="/img/article-4-base.png" alt="Лекция Виктора Дембовского в Хабаровске">
                    <img class="article-base-hor" src="/img/article-1-base.png" alt="Лекция Виктора Дембовского в Хабаровске">
                    <div>
                        <h3>Лекция Виктора Дембовского в&nbsp;Хабаровске</h3>
                        <p>Все больше людей задумываются об&nbsp;экологии и&nbsp;осознанном потреблении. И&nbsp;это&nbsp;уже не&nbsp;дань моде, а необходимость сохранить природные ресурсы.</p>
                        <span>Подробнее</span>
                    </div>
                </article>
            </div>
            <div class="main-articles-slide main-articles-slide-4">
                <article style="background-image: url('/img/article-4.png')">
                    <a href="#"></a>
                    <img class="article-base-vert" src="/img/article-4-base.png" alt="Лекция Виктора Дембовского в Хабаровске">
                    <img class="article-base-hor" src="/img/article-1-base.png" alt="Лекция Виктора Дембовского в Хабаровске">
                    <div>
                        <h3>Лекция Виктора Дембовского в&nbsp;Хабаровске</h3>
                        <p>Все больше людей задумываются об&nbsp;экологии и&nbsp;осознанном потреблении. И&nbsp;это&nbsp;уже не&nbsp;дань моде, а необходимость сохранить природные ресурсы.</p>
                        <span>Подробнее</span>
                    </div>
                </article>
            </div>
            <div class="main-articles-slide main-articles-slide-2">
                <article style="background-image: url('/img/Blagoveshensk.jpg')">
                    <a href="#"></a>
                    <img src="/img/article-2-base.png" alt="Лекция Виктора Дембовского в Благовещенске">
                    <div>
                        <h3>Лекция Виктора Дембовского в&nbsp;Благовещенске</h3>
                        <p>Все больше людей задумываются об&nbsp;экологии и&nbsp;осознанном потреблении. И&nbsp;это&nbsp;уже не&nbsp;дань моде, а необходимость сохранить природные ресурсы.</p>
                        <span>Подробнее</span>
                    </div>
                </article>
                <article style="background-image: url('/img/Ufa.jpg')">
                    <a href="#"></a>
                    <img src="/img/article-3-base.png" alt="Форум дизайнеров и архитекторов в Уфе">
                    <div>
                        <h3>Форум дизайнеров и&nbsp;архитекторов в&nbsp;Уфе</h3>
                        <p>Все больше людей задумываются об&nbsp;экологии и&nbsp;осознанном потреблении. И&nbsp;это&nbsp;уже не&nbsp;дань моде, а необходимость сохранить природные ресурсы.</p>
                        <span>Подробнее</span>
                    </div>
                </article>
            </div>
            <div class="main-articles-slide main-articles-slide-2">
                <article style="background-image: url('/img/article-2.png')">
                    <a href="#"></a>
                    <img src="/img/article-2-base.png" alt="Лекция Виктора Дембовского в Благовещенске">
                    <div>
                        <h3>Лекция Виктора Дембовского в&nbsp;Благовещенске</h3>
                        <p>Все больше людей задумываются об&nbsp;экологии и&nbsp;осознанном потреблении. И&nbsp;это&nbsp;уже не&nbsp;дань моде, а необходимость сохранить природные ресурсы.</p>
                        <span>Подробнее</span>
                    </div>
                </article>
                <article style="background-image: url('/img/article-3.png')">
                    <a href="#"></a>
                    <img src="/img/article-3-base.png" alt="Форум дизайнеров и архитекторов в Уфе">
                    <div>
                        <h3>Форум дизайнеров и&nbsp;архитекторов в&nbsp;Уфе</h3>
                        <p>Все больше людей задумываются об&nbsp;экологии и&nbsp;осознанном потреблении. И&nbsp;это&nbsp;уже не&nbsp;дань моде, а необходимость сохранить природные ресурсы.</p>
                        <span>Подробнее</span>
                    </div>
                </article>
            </div>

            <div class="main-articles-slide main-articles-slide-3">
                <article style="background-image: url('/img/Ufa.jpg')">
                    <a href="#"></a>
                    <img src="/img/article-3-base.png" alt="Форум дизайнеров и архитекторов в Уфе">
                    <div>
                        <h3>Форум дизайнеров и&nbsp;архитекторов в&nbsp;Уфе</h3>
                        <p>Все больше людей задумываются об&nbsp;экологии и&nbsp;осознанном потреблении. И&nbsp;это&nbsp;уже не&nbsp;дань моде, а необходимость сохранить природные ресурсы.</p>
                        <span>Подробнее</span>
                    </div>
                </article>
            </div>

       </div>
    </div>
</section>
*/ ?>

<?
require($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");
if(defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED===true) {
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog.php");
}