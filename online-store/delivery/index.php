<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock')) exit;
$APPLICATION->SetTitle("Доставка");
$APPLICATION->SetPageProperty("description", "Европласт - производство полиуретановых изделий, лидер на российском рынке");
require($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");
?>
    <div class="main-banner company-banner">
        <div class="main-slide-caption white">
            Доставка
        </div>
        <img src="/img/online-store/del1.jpg" alt="Доставка">
    </div>
    <section class="publishers-txt delivery-txt">
        <div class="publishers-top">
            <div class="content-wrapper">
                <p class="online-store-title">Условия доставки</p>
                <div class="publishers-column">
                    <p>
                        <span class="del-txt-tit">Бесплатно в&nbsp;черте&nbsp;МКАД</span>
                       <span class="del-txt-det">(если сумма заказа с&nbsp;учетом скидки более&nbsp;10&nbsp;000.00&nbsp;РУБ)</span>
                    </p>
                    <p>
                        <span class="del-txt-tit">1&nbsp;000.00&nbsp;руб в&nbsp;черте&nbsp;МКАД</span>
                        <span class="del-txt-det">(если сумма заказа с&nbsp;учетом скидки менее&nbsp;10&nbsp;000.00&nbsp;РУБ)</span>
                    </p>
                    <p>
                        <span class="del-txt-tit">50.00&nbsp;РУБ/км за&nbsp;МКАД (не&nbsp;далее 50&nbsp;км от&nbsp;МКАД)</span>
                        <span class="del-txt-det">(если сумма заказа с&nbsp;учетом скидки менее&nbsp;10&nbsp;000.00&nbsp;РУБ)</span>
                    </p>
                </div>
                <div class="publishers-column">
                    <p>
                        <span class="del-txt-tit">30.00&nbsp;РУБ/км за&nbsp;МКАД (не&nbsp;далее 50&nbsp;км от&nbsp;МКАД)</span>
                        <span class="del-txt-det">(если сумма заказа с&nbsp;учетом скидки более&nbsp;10&nbsp;000.00&nbsp;РУБ)</span>
                    </p>
                    <p>
                        <span class="del-txt-tit">Другие регионы России</span>
                        <span class="del-txt-det">Условия, стоимость и&nbsp;интервал доставки уточнит представитель
                        «Европласт» в&nbsp;вашем регионе после&nbsp;обработки&nbsp;заказа.</span>
                    </p>
                </div>
                <div class="publishers-column">
                    <p>
                        <span class="del-txt-tit">Страны СНГ</span>
                        <span class="del-txt-det">Условия, стоимость и&nbsp;интервал доставки уточнит представитель
                        «Европласт» в&nbsp;вашем регионе после&nbsp;обработки&nbsp;заказа.</span>
                    </p>
                    <p>
                        <span class="del-txt-tit" style="margin-bottom:0;">* Доставка образцов&nbsp;- 500.00&nbsp;РУБ в&nbsp;черте&nbsp;МКАД</span>
                    </p>

                </div>
            </div>
        </div>
        <div class="publishers-bottom">
            <div class="content-wrapper">
                <div class="publishers-column">
                    <p>Наши специалисты свяжутся с&nbsp;вами дополнительно для&nbsp;согласования даты&nbsp;доставки.</p>
                    <p>Доставка осуществляется с&nbsp;понедельника по&nbsp;субботу с&nbsp;9.00&nbsp;до&nbsp;18.00.</p>
                    <p><span class="del-txt-tit" style="margin-bottom:0;">Разгрузка товара производится силами&nbsp;Покупателя.</span></p>
                </div>
                <div class="publishers-column">
                    <p>Получатель при&nbsp;передаче заказа (от&nbsp;курьера либо&nbsp;самовывозом) должен предъявить
                        документ, удостоверяющий личность, ФИО, в&nbsp;котором должны соответствовать
                        ФИО получателя, указанные при&nbsp;оформлении&nbsp;заказа.</p>
                </div>
                <div class="publishers-column">
                    <p>Документы, удостоверяющие личность: Паспорт&nbsp;РФ, Загранпаспорт&nbsp;РФ, Паспорт&nbsp;моряка,
                        Удостоверение личности военнослужащего&nbsp;РФ, Военный билет, Паспорт иностранного
                        гражданина, Дипломатический&nbsp;паспорт.</p>
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
