<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock')) exit;
$APPLICATION->SetTitle("Коллекции");
$APPLICATION->SetPageProperty("description", "Европласт - производство полиуретановых изделий, лидер на российском рынке");

require($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");

$breadcrumbs_arr = Array(
    Array(
        'name' => 'коллекции',
        'link' => '/collection/',
        'title' => 'коллекции',
    ),
);
?>

<div class="content-wrapper">
    <? require_once($_SERVER["DOCUMENT_ROOT"] . "/include/breadcrumbs.php"); ?>
</div>

<section class="all-collections">
    <div class="content-wrapper">

        <h1 class="hidden">Коллекции</h1>

        <div class="collection-item">
            <a href="/collection/new_art_deco/" class="cover-link"></a>
            <div class="collection-img">
                <h2>new art deco</h2>
                <img src="/collection/img/index_page/new_art_deco.jpg" alt="new art deco">
            </div>
            <div class="collection-desc">Это коллекция-конструктор, позволяющая дизайнеру выразить себя через&nbsp;многообразие органичных сочетаний, отсутствие ограничений и&nbsp;полноту свободы&nbsp;творчества.</div>
            <a href="/collection/new_art_deco/" class="collection-see">смотреть коллекцию</a>
        </div>

        <? /*
        <div class="collection-item">
            <a href="/collection/mauritania/" class="cover-link"></a>
            <div class="collection-img">
                <h2>mauritania</h2>
                <img src="/collection/img/index_page/mauritania.jpg" alt="mauritania">
            </div>
            <div class="collection-desc">
                тонкие узоры переплетаются между собой и&nbsp;создают живописные полотна,
                наполняющие пространство волшебной атмосферой арабских&nbsp;сказок.
            </div>
            <a href="/collection/mauritania/" class="collection-see">смотреть коллекцию</a>
        </div>

        <div class="collection-item">
            <a href="/collection/lines/" class="cover-link"></a>
            <div class="collection-img">
                <h2>lines</h2>
                <img src="/collection/img/index_page/lines.jpg" alt="lines">
            </div>
            <div class="collection-desc">Lines — это&nbsp;универсальный конструктор для&nbsp;интерьеров любого актуального стиля от&nbsp;лофта до&nbsp;современной классики.</div>
            <a href="/collection/lines/" class="collection-see">смотреть коллекцию</a>
        </div>

        <div class="collection-item">
            <a href="/collection/2018/" class="cover-link"></a>
            <div class="collection-img">
                <h2>modern</h2>
                <img src="/collection/img/index_page/2018.jpg" alt="modern">
            </div>
            <div class="collection-desc">
                современный дизайн интерьера расширяет границы привычного. мы&nbsp;меняем ваше представление о&nbsp;лепнине&nbsp;— встречайте новые широкие карнизы и&nbsp;молдинги.
            </div>
            <a href="/collection/2018/" class="collection-see">смотреть коллекцию</a>
        </div>
        */ ?>

    </div>
</section>

<?
require($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");
if(defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED===true)
{
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog.php");
}
