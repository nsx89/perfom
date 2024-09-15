<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock')) exit;
$APPLICATION->SetTitle("Компания");
$APPLICATION->SetPageProperty("description", "Европласт - производство полиуретановых изделий, лидер на российском рынке");
require($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");
?>
    <div class="main-banner company-banner">
        <div class="main-slide-caption white">
            Лучшее <br>из лепнины
            <div class="main-slide-subcaption">
                Бренд «Европласт» представлен <br>
                в 18 странах мира.
            </div>
        </div>
        <img src="/img/company/banner.jpg" alt="компания">
    </div>
    <section class="dealers-main-txt">
        <div class="content-wrapper">
            <h1 class="hidden">Лучшее из лепнины</h1>
            <p>
                Компания «Декор», которой принадлежат бренды Европласт и Перфом, производит и продвигает лепнину и архитектурный декор из перфома и пенополиуретана. Компания основана более более 25 лет назад и сегодня является одной из ведущих на рынке декоративных материалов из пенополиуретана и перфома в мире. Перфом — это вспененный композиционный полимер высокой плотности на основе полистирола. Он отличается особой прочностью и надежностью.
            </p>
            <p>
                Компания осуществляет управление собственным производством, занимающим площадь более 20 тысяч квадратных метров. Оно расположено в городском округе Чехов Московской области. Также компания управляет логистическими комплексами общей площадью более 6 тысяч квадратных метров, находящимися в Чехове и Роттердаме (Голландия).
            </p>
            <p>
                Завод сертифицирован по системе ISO 9001.
            </p>
        </div>
    </section>
<?
require($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");
if(defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED===true)
{
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog.php");
}
