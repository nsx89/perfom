<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock')) exit;
$APPLICATION->SetTitle("Профессионалам");
$APPLICATION->SetPageProperty("description", "Европласт - производство полиуретановых изделий, лидер на российском рынке");
require($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");
?>
<div class="main-slider-wrap">
    <!--noindex--><div class="main-slider-preloader"><img src="/img/preloader.gif" alt="Подождите..."></div><!--/noindex-->
    <div class="main-slider" data-type="main-slider">
        <div class="main-slide">
            <div class="main-slide-caption white">лучший <br>сервис для <br>профессионалов</div>
            <img class="img-load" src="/img/1.png" data-src="/img/professional/01.jpg" alt="лучший сервис для профессионалов">
        </div>
        <div class="main-slide">
            <div class="main-slide-caption white">элегантное <br>оформление</div>
            <img class="img-load" src="/img/1.png" data-src="/img/professional/Perfom_builders_03.jpg" alt="элегантное оформление">
        </div>
        <div class="main-slide">
            <div class="main-slide-caption">декор <br>для любого <br>объекта</div>
            <img class="img-load" src="/img/1.png" data-src="/img/professional/Perfom_builders_02.jpg" alt="декор <br>для любого <br>объекта">
        </div>
        <div class="main-slide">
            <div class="main-slide-caption">подчеркиваем <br>лучшее</div>
            <img class="img-load" src="/img/1.png" data-src="/img/professional/Perfom_builders_01.jpg" alt="подчеркиваем лучшее">
        </div>
        <div class="main-slide">
            <div class="main-slide-caption white">надежный партнёр<br>для бизнеса</div>
            <img class="img-load" src="/img/1.png" data-src="/img/professional/05.jpg" alt="надежный партнёр для бизнеса">
        </div>
    </div>
</div>
<section class="main-pref professional-pref">
    <div class="content-wrapper">
        <div class="main-pref-txt">
            <h1 class="hidden">Лучший сервис для профессионалов</h1>
            <h2>строителям</h2>
            <p class="main-pref-annotation">
                Европласт предлагает девелоперам
                и строителям привлекательные условия 
                сотрудничества. в нашем ассортименте
                широкий спектр декоративных элементов
                для оформления интерьера и фасада, всегда
                находящиеся в наличии на нашем складе.
            </p>
            <p>
                Среди наших преимуществ, актуальный дизайн изделий, гибкие
                условия сотрудничества, персональный менеджер для каждого
                клиента, удобная доставка и высокий уровень сервиса.
                современные технологии, успешно применяемые
                на собственном производстве, одном из крупнейших в мире,
                гарантируют непревзойденное качество продукции.
            </p>
        </div>
        <div class="main-pref-slider" data-type="main-pref-slider">
            <div class="main-pref-slide professioanl-pref-slide">
                <i class="icon-3d"></i>
                Бесплатная 3d<br>
                визуализация
            </div>
            <div class="main-pref-slide professioanl-pref-slide">
                <i class="icon-perscent"></i>
                система <br>скидок
            </div>
            <div class="main-pref-slide professioanl-pref-slide">
                <i class="icon-in-stock"></i>
                все в наличии <br>на складе
            </div>
            <div class="main-pref-slide professioanl-pref-slide">
                <i class="icon-pers-manager"></i>
                персональный <br>менеджер
            </div>
            <div class="main-pref-slide professioanl-pref-slide">
                <i class="icon-return"></i>
                легко вернуть <br>остатки
            </div>
            <div class="main-pref-slide professioanl-pref-slide">
                <i class="icon-mount"></i>
                своя служба <br>монтажа
            </div>
        </div>

    </div>
</section>

<section class="main-gallery professional">
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

<?$page = 'professional';?>
<? require_once($_SERVER["DOCUMENT_ROOT"] . '/include/main_contact.php');?>

<?
require($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");
if(defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED===true)
{
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog.php");
}