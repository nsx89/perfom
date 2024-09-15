<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock')) exit;
$APPLICATION->SetTitle("Оплата");
$APPLICATION->SetPageProperty("description", "Европласт - производство полиуретановых изделий, лидер на российском рынке");
require($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");
?>
    <div class="main-banner company-banner">
        <div class="main-slide-caption white">
            Оплата
        </div>
        <img src="/img/online-store/pay.jpg" alt="Оплата">
    </div>
    <section class="dealers-main-txt online-store-txt pay-txt">
        <div class="content-wrapper">
            <div class="payment-wrap">
                <div class="left-part-payment">
                    <div class="payment-item">
                        <div class="payment-title">Оплата наличными,<br>банковской картой или&nbsp;СБП</div>
                        <div class="payment-desc">
                            <p>
                                Оплату наличными, банковской картой или&nbsp;СБП
                                Вы&nbsp;можете осуществить в&nbsp;фирменных магазинах,
                                на&nbsp;сайте или&nbsp;при&nbsp;получении товара у&nbsp;водителя-экспедитора.
                            </p>
                        </div>
                    </div>
                    <div class="payment-item">
                        <div class="payment-title">БЕЗНАЛИЧНЫЙ РАСЧЕТ</div>
                        <div class="payment-desc">
                            <p>
                                Вы также можете осуществить платеж по&nbsp;реквизитам компании. Уточните их у&nbsp;менеджера интернет-магазина.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="right-part-payment">
                    <div class="payment-item">
                        <div class="payment-title">Онлайн оплата</div>
                        <div class="payment-desc">
                            <p>
                                Оплата осуществляется картами МИР, VISA и MasterCard через платежную систему 2Can и Банк&nbsp;ПСБ&nbsp;(ПАО), а также Систему быстрых платежей (СБП) сразу после оформления заказа.
                            </p>
                            <p>При оформлении заказа необходимо обязательно указать верные ФИО получателя.<br>
                                Получатель при передаче заказа (от курьера либо самовывозом) должен предъявить документ, удостоверяющий личность, ФИО в котором должны соответствовать ФИО получателя, указанные при оформлении заказа. </p>
                            <p>Документы, удостоверяющие личность: Паспорт РФ, Загранпаспорт РФ, Паспорт моряка, Удостоверение личности военнослужащего РФ, Военный билет, Паспорт иностранного гражданина, Дипломатический паспорт.</p>
                            <div class="payment-online-desc">
                                <p>При оплате картой (для ввода реквизитов Вашей карты) Вы будете перенаправлены на платежный шлюз Банка ПСБ (ПАО) с помощью платежной системы 2Can.<br>
                                    Соединение с платёжным шлюзом и передача информации осуществляется в защищённом режиме с использованием протокола шифрования SSL. В случае если Ваш банк поддерживает технологию безопасного проведения интернет-платежей Verified By Visa, MasterCard SecureCode, MIR Accept, J-Secure для проведения платежа также может потребоваться ввод специального пароля.</p>
                                <p>Настоящий сайт поддерживает 256-битное шифрование. Конфиденциальность сообщаемой персональной информации обеспечивается Банк ПСБ (ПАО). Введённая информация не будет предоставлена третьим лицам за исключением случаев, предусмотренных законодательством РФ. Проведение платежей по банковским картам осуществляется в строгом соответствии с требованиями платёжных систем МИР, Visa Int., MasterCard Europe Sprl, JCB.</p>
                                <p>При оплате СБП Вам будет показан QR-код, который необходимо отсканировать мобильным приложением банка, поддерживающего оплату через СБП, установленном на Вашем смартфоне.<br>
                                    В мобильном приложении банка следует завершить действия по оплате и убедиться, что оплата прошла успешно. Подробно с необходимыми для оплаты действиями, требованиями Вы можете ознакомится на сайте Системы быстрых платежей (<a href="https://sbp.nspk.ru/" target="_blank">https://sbp.nspk.ru</a>)</p>
                                <div class="payment-online-desc-img">
                                    <img src="/img/online-store/mir.jpg" alt="МИР">
                                    <img src="/img/online-store/visa.jpg" alt="VISA">
                                    <img src="/img/online-store/mastercard.jpg" alt="MasterCard">
                                    <img src="/img/online-store/sbp.jpg" alt="СБП">
                                </div>
                            </div>
                        </div>
                    </div>
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
