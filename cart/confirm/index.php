<?
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock')) exit;
$APPLICATION->SetTitle("Получение письма подтверждено");
$APPLICATION->SetPageProperty("description", "Перфом - производство полиуретановых изделий, лидер на российском рынке");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}
require($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");

$subdomain = htmlspecialcharsbx($_GET['subdomain']);
$dealer = (int)$_GET['dealer'];

if (!empty($subdomain) && !empty($dealer)) {

    $DEALER_NAME = '';
    $arFilter = Array('IBLOCK_ID' => 6, 'ACTIVE' => 'Y', 'ID' => $dealer);
    $db_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter);
    if ($db_list) {
        $el = $db_list->GetNextElement();
        if ($el) {
            $el = array_merge($el->GetFields(), $el->GetProperties());
            $DEALER_NAME = $el['NAME'];
        }
    }
    $DEALER_NAME .= ' id:'.$dealer;

    $date_send = strtotime('18.06.2024 15:45');
    $date_now = time();
    $minutes = round(($date_now - $date_send) / 60); //период в минутах

    $arFilter = Array('IBLOCK_ID' => 69, 'ACTIVE' => 'Y', 'CODE' => $subdomain);
    $db_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter);
    if ($db_list) {
        $el = $db_list->GetNextElement();
        if (!$el) {
            $el = new CIBlockElement;
            $arLoadProductArray = Array(
                "DATE_CREATE"      => date("d.m.Y H:i:s"),
                "IBLOCK_ID"      => 69,
                "NAME"           => $DEALER_NAME,
                "CODE"           => $subdomain,
                "SORT"           => $minutes
            );
            $el->Add($arLoadProductArray);
        }
    }
}

?>

<div class="content-wrapper">
    <div class="cont-success cont-success-order-off">
            
        <div class="succ-header">Спасибо за подтверждение</div> 

        <div class="succ-desc">Получение письма с тестовым заказом<br> № 29999 успешно подтверждено</div>
        
    </div>

</div>




<?
require($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");
if(defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED===true)
{
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog.php");
}

