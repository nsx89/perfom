<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock')) exit;
$APPLICATION->SetTitle("Выходные данные");
$APPLICATION->SetPageProperty("description", "Европласт - производство полиуретановых изделий, лидер на российском рынке");
require($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");
?>
    <div class="main-banner company-banner">
        <div class="main-slide-caption white">
            выходные <br>данные
        </div>
        <img src="/img/publishers-imprint.jpg" alt="выходыне данные">
    </div>
    <section class="publishers-txt">
        <div class="publishers-top">
            <div class="content-wrapper">
                <h1 class="hidden">Выходные данные</h1>
                <div class="publishers-column">
                    <p>Сайт perfom-decor.ru и&nbsp;все&nbsp;материалы, <br>
                        размещенные на&nbsp;нем, принадлежат:</p>
                    <p>142350, Московская область, <br>
                        городской округ Чехов, деревня Ивачково, <br>
                        Лесная&nbsp;ул., вл.&nbsp;12 стр.&nbsp;7</p>
                </div>
                <div class="publishers-column">
                    <p><span class="not-low">ООО «Декор»</span></p>
                    <p>Тел: +7 (499) 789 62 70</p>
                    <p>E-mail: mainbox@decor-evroplast.ru</p>
                </div>
                <div class="publishers-column">
                    <p>ИНН 777609512</p>
                    <p>КПП 504801001</p>
                    <p>ОГРН 1067760097822</p>
                </div>
            </div>
        </div>
        <div class="publishers-bottom">
            <div class="content-wrapper">
                <div class="publishers-column">
                    <p>Ответственный за&nbsp;актуализацию материалов:</p>
                    <p>
                        Отдел маркетинга и&nbsp;рекламы <br>
                        marketing@decor-evroplast.ru <br>
                        +7&nbsp;495&nbsp;315&nbsp;30&nbsp;40 (c&nbsp;09:30&nbsp;до&nbsp;18:30&nbsp;по&nbsp;Мск)
                    </p>
                </div>
                <div class="publishers-column">
                    <p>По вопросам правообладателей <br>и&nbsp;другим правовым вопросам:</p>
                    <p>Юридическая служба <br>
                        marketing@decor-evroplast.ru
                    </p>
                </div>
                <div class="publishers-column">
                    <p>По&nbsp;оперативным вопросам работы сайта:</p>
                    <p>Отдел маркетинга и&nbsp;рекламы <br>
                        marketing@decor-evroplast.ru <br>
                        +7&nbsp;495&nbsp;315&nbsp;30&nbsp;40 (c&nbsp;09:30&nbsp;до&nbsp;18:30&nbsp;по&nbsp;Мск)
                    </p>
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
