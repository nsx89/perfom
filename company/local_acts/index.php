<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock')) exit;
$APPLICATION->SetTitle("Локальные акты");
$APPLICATION->SetPageProperty("description", "Европласт - производство полиуретановых изделий, лидер на российском рынке");
require($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");
?>
<section class="dealers-main-txt">
    <div class="content-wrapper">
        <div class="local-acts">
            <div class="local-act-item">
                <a data-ed="cert-gallery" data-fancybox="cert4" data-caption="О возобновлении работы производства от 15.05.2020.pdf" href="/img/local_acts/loc-act-4-b-1.jpg" title="Увеличить">
                    <img src="/img/local_acts/loc-act-4-sm.jpg" alt="">
                </a>
                <a data-ed="cert-gallery" data-fancybox="cert4" data-caption="О возобновлении работы производства от 15.05.2020.pdf" href="/img/local_acts/loc-act-4-b-2.jpg" title="Увеличить"></a>
            </div>
        </div>
    </div>
</section>
<?
require($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");
if(defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED===true)
{
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog.php");
}
