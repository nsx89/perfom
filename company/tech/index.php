<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock')) exit;
$APPLICATION->SetTitle("Технологии");
$APPLICATION->SetPageProperty("description", "Европласт - производство полиуретановых изделий, лидер на российском рынке");
require($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");
?>
    <div class="main-banner company-banner">
        <div class="main-slide-caption white">
            Инновации <br>в декоре
            <div class="main-slide-subcaption">
                Завод компании <span class="not-low">«Декор»</span>&nbsp;- один <br>
                из&nbsp;крупнейших в&nbsp;мире.
            </div>
        </div>
        <img src="/img/tech/banner.jpg" alt="технологии">
    </div>
    <section class="dealers-main-txt">
        <div class="content-wrapper">
            <h1 class="hidden">Инновации в декоре</h1>
            <p>
                «Европласт» производит архитектурный декор из пенополиуретана и перфома. Перфом – это новый материал, разработанный в России специалистами нашей компании. 
            </p>
            <p>
                Мы усовершенствовали технологию получения изделий, что позволило получить точное совпадение деталей по сечению независимо от партии. С изделиями из перфома стыки будут идеальными. Все изделия из перфома поражают своей четкой геометрией.
            </p>
            <p>
                Точное выдерживание параметров коэкструзионного слоя позволило добиться самой высокой поверхностной твердости на рынке изделий из экструзионного пенополистирола.
            </p>
<p>«Европласт» гордится тем, что предлагает продукцию, сочетающую в себе качественные материалы и передовые технологии. Перфом не только устанавливает новые стандарты качества в архитектурном декоре, но и демонстрирует нашу приверженность к постоянному развитию и стремлению к совершенству.</p>
<p>Использование перфома в ваших проектах гарантирует превосходство в каждой детали и долговечность каждого элемента, обеспечивая новый уровень качества и эстетики.</p>
        </div>
    </section>
<?
require($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");
if(defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED===true)
{
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog.php");
}
