<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock')) exit;
$APPLICATION->SetTitle("Реквизиты");
$APPLICATION->SetPageProperty("description", "Европласт - производство полиуретановых изделий, лидер на российском рынке");
require($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");
?>
    <div class="main-banner company-banner">
        <div class="main-slide-caption white">
            Реквизиты
        </div>
        <img src="/img/online-store/requisites.jpg" alt="реквизиты">
    </div>
    <section class="dealers-main-txt online-store-txt details-txt">
        <div class="content-wrapper">
            <p>
                ООО «Декор»
            </p>
            <p>
                ИНН 770<span></span>7609512
            </p>
            <p>
                КПП 504801001
            </p>
            <p>
                ОГРН 106776<span></span>0097822
            </p>
            <p>
                142350, Московская область, <br>
                городской округ Чехов, деревня Ивачково, <br>
                Лесная&nbsp;ул., вл.&nbsp;12 стр.&nbsp;7</p>
        </div>
    </section>
<?
require($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");
if(defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED===true)
{
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog.php");
}
