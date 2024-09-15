<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock')) exit;
$APPLICATION->SetTitle("Условия возврата");
$APPLICATION->SetPageProperty("description", "Европласт - производство полиуретановых изделий, лидер на российском рынке");
require($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");
?>
    <div class="main-banner company-banner">
        <div class="main-slide-caption white">
            Условия возврата
        </div>
        <img src="/img/online-store/req.jpg" alt="Условия возврата">
    </div>
    <section class="dealers-main-txt online-store-txt return-txt">
        <div class="content-wrapper">
            <p class="online-store-title">ОБЩИЕ УСЛОВИЯ О&nbsp;ВОЗВРАТЕ</p>
            <p>
                <span>1.</span> Возврат Товара возможен в&nbsp;течение 14&nbsp;дней со&nbsp;дня его передачи Покупателю.
            </p>
            <p>
                <span>2.</span> Возврат Товара возможен при&nbsp;условии, если&nbsp;сохранен его&nbsp;внешний (товарный) вид и&nbsp;потребительские свойства, Товар находится в&nbsp;полной комплектации, имеется в&nbsp;наличии документ, подтверждающий факт и&nbsp;условия покупки указанного&nbsp;Товара.
            </p>
            <p>
                <span>3.</span> Возврат Товара производится непосредственно в&nbsp;указанном Продавцом&nbsp;месте.
            </p>
            <p>
                <span>4.</span> Продавец возвращает Покупателю уплаченную им&nbsp;денежную сумму за&nbsp;Товар за&nbsp;вычетом расходов Продавца на&nbsp;доставку Товара, не&nbsp;позднее чем&nbsp;через&nbsp;3&nbsp;(три) банковских дня с&nbsp;даты возврата Товара Покупателем в&nbsp;указанное Продавцом&nbsp;место.
            </p>
        </div>
    </section>
<?
require($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");
if(defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED===true)
{
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog.php");
}
