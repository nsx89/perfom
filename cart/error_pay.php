<?
ini_set('display_errors', 1);
//error_reporting(E_ALL);
    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
    if (!CModule::IncludeModule('iblock')) exit;
    $APPLICATION->SetTitle("Ошибка оплаты");
    $APPLICATION->SetPageProperty("description", "Европласт - производство полиуретановых изделий, лидер на российском рынке");
    if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
        exit;
    }
    require($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");
?>

    <div class="content-wrapper">
        <section class="order-resp-sec">
            <i class="new-icomoon icon-sad-face"></i>
            <div class="order-resp-result">Что-то пошло не так...</div>
            <div class="order-resp-details">
              <div class="order-resp-details-dealer">Возможно, закончилось время действия сессии.</div>
              <div class="order-resp-details-dealer">Пожалуйста свяжитесь с менеджером, <br>который отправил вам ссылку.</div>
            </div>
            <a class="order-resp-btn succ-cat" href="/cart">Вернуться в корзину</a>
        </section>
    </div>

<?
    require($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");
    if(defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED===true)
    {
        require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog.php");
    }
