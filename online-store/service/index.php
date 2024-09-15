<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock')) exit;
$APPLICATION->SetTitle("Сервис");
$APPLICATION->SetPageProperty("description", "Европласт - производство полиуретановых изделий, лидер на российском рынке");
require($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");
?>
    <div class="main-banner company-banner">
        <div class="main-slide-caption white">
            Сервис
        </div>
        <img src="/img/online-store/serv.jpg" alt="сервис">
    </div>
    <section class="publishers-txt service-txt">
        <div class="publishers-top">
            <div class="content-wrapper">
                <div class="publishers-column">
                    <p>Мы постоянно стремимся улучшать
                        спектр и&nbsp;качество предоставляемых услуг.
                        Изучая практики лидеров в&nbsp;области электронной
                        коммерции и&nbsp;клиентского сервиса, команда
                        стремится сделать покупательский опыт лучшим
                        и&nbsp;максимально&nbsp;комфортным.
                    </p>
                </div>
            </div>
        </div>
        <div class="publishers-bottom">
            <div class="content-wrapper">
                <div class="publishers-column">
                    <p>Предложения по&nbsp;работе интернет-магазина <br>
                        вы&nbsp;можете&nbsp;направлять:
                    </p>
                    <p>Отдел маркетинга и&nbsp;рекламы</p>
                    <p>marketing@decor-evroplast.ru</p>
                    <p><a href="tel:+74953153040 ">+7&nbsp;495&nbsp;315&nbsp;30&nbsp;40</a> (c&nbsp;09:30&nbsp;до&nbsp;18:30&nbsp;по&nbsp;Мск)</p>
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
