<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock')) exit; 
$APPLICATION->SetTitle("Дизайнерам");
$APPLICATION->SetPageProperty("description", "Европласт - производство полиуретановых изделий, лидер на российском рынке");
require($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");
?>
<div class="main-slider-wrap">
    <!--noindex--><div class="main-slider-preloader"><img src="/img/preloader.gif" alt="Подождите..."></div><!--/noindex-->
    <div class="main-slider" data-type="main-slider">
        <div class="main-slide">
            <div class="main-slide-caption white">уют в каждой <br>детали</div>
            <img class="img-load" src="/img/1.png" data-src="/img/designer/Perfom_header_01.jpg" alt="уют в каждой детали">
        </div>
        <div class="main-slide">
            <div class="main-slide-caption">шире молдинг, <br>шире возможности</div>
            <img class="img-load" src="/img/1.png" data-src="/img/designer/Perfom_header_02.jpg" alt="шире молдинг, шире возможности">
        </div>
        <div class="main-slide">
            <div class="main-slide-caption">новое прочтение <br>привычных вещей</div>
            <img class="img-load" src="/img/1.png" data-src="/img/designer/Perfom_header_03.jpg" alt="новое прочтение привычных вещей">
        </div>
    </div>
</div>
<section class="main-pref designer-pref">
    <div class="content-wrapper">
        <div class="main-pref-txt">
            <h2>дизайнерам и архитекторам</h2>
            <p class="main-pref-annotation" style="padding-right: 12px;">
                Сотрудничество с нами — это легкость и удобство на каждом этапе.
                Наш широкий ассортимент позволяет воплотить любые решения
                и удовлетворить запросы даже самых требовательных клиентов.
                Декоративные элементы нашего бренда легко монтируются,
                надежны и эстетичны.
            </p>
            <p>
                За годы существования архитектурный декор 
                от «Европласт» завоевал множество лестных откликов и 
                рекомендаций от профессионалов в области дизайна и архитектуры.
                Мы гордимся тем, что предлагаем высокий уровень 
                индивидуального сервиса и привлекательные условия для партнерства.
            </p>
            <p>
                Мы предоставляем услугу заводского монтажа с гарантией качества.
                Современный дизайн наших изделий открывает неисчерпаемые возможности 
                для создания уникальных интерьеров и фасадов, 
                воплощая в жизнь самые смелые
                идеи и концепции.
            </p>
        </div>
        <div class="main-pref-slider" data-type="main-pref-slider">
            <div class="main-pref-slide designer-pref-slide">
                <i class="icon-delivery"></i>
                удобная <br>доставка
            </div>
            <div class="main-pref-slide designer-pref-slide">
                <i class="icon-thumb"></i>
                привлекательные <br>условия
            </div>
            <div class="main-pref-slide designer-pref-slide">
                <i class="icon-in-stock"></i>
                все в наличии <br>на складе
            </div>
            <div class="main-pref-slide designer-pref-slide">
                <i class="icon-pers-manager"></i>
                персональный <br>менеджер
            </div>
            <div class="main-pref-slide designer-pref-slide">
                <i class="icon-assort"></i>
                широкий <br>ассортимент
            </div>
            <div class="main-pref-slide designer-pref-slide">
                <i class="icon-mount"></i>
                своя служба <br>монтажа
            </div>
        </div>

    </div>
</section>

    <section class="main-gallery designer">
        <div class="content-wrapper">
            <div class="main-gallery-title-block">
                <h2 class="main-blocks-title">Реализованные проекты</h2>

                <a href="/gallery/" class="main-blocks-link designer"><span>смотреть все проекты</span> <i class="icon-long-arrow"></i></a>
            </div>
            <div class="main-gallery-slider" data-type="main-gallery-slider">

				<? require_once($_SERVER["DOCUMENT_ROOT"] . "/include/main_gallery.php"); ?>

            </div>
        </div>
    </section>

<? require_once($_SERVER["DOCUMENT_ROOT"] . '/include/recommends.php');?>

<? require_once($_SERVER["DOCUMENT_ROOT"] . '/include/main_contact.php');?>

<?
require($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");
if(defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED===true)
{
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog.php");
}