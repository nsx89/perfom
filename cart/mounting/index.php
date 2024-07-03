<?
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
$APPLICATION->SetTitle("Расчет монтажа");
$APPLICATION->SetPageProperty("description", "Перфом - производство полиуретановых изделий, лидер на российском рынке");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}
global $USER;
$user_id = $USER->GetID();
$rsUser = CUser::GetByID($user_id);
$user = $rsUser->Fetch();

require($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/cart/mounting/mounting_data.php");

$m_arr = Array('1542','1543','1544','1545','1546','1547');//погонаж: карнизы, плинтусы, молдинги и flex

$cart = getObjectItems();
$new_cart = Array();
foreach($cart['items'] as $item) {
    if(strpos($item['ID'],'s') === false) {
        $new_cart[] = $item;
    }
}
$cart['items'] = $new_cart;
//print_r($cart);
?>
<link rel="stylesheet" href="/cart/mounting/plintus-icomoon/style.css?<?=$random?>">
<link rel="stylesheet" href="/cart/mounting/icomoon/style.css?<?=$random?>">
<link rel="stylesheet" href="/cart/mounting/mounting.css?<?=$random?>">
<div class="content-wrapper">
    <h1 class="cart-title-name mount-calc-title">расчет монтажа</h1>
<?
$mount = json_decode($_COOKIE['mount']);
//print_r($mount);
//print_r(getMountList($_COOKIE['mount']));

?>
    <div class="dwnld-models-rules">
        <div class="dwnld-models-rules-title">Порядок расчета</div>
        <div class="dwnld-models-rules-cont">
            <?=getNotes()?>
        </div>
    </div>

    <section class="e-new-mount" data-type="mount-wrap">
        <div class="left-column" data-type="mount-list">
            <?if(count($cart['items']) > 0) { ?>
            <div class="mount-line"></div>
            <? } ?>

            <?
            $n = 0;
            foreach($cart['items'] as $item) {
                /**
                 * исключаем товары, у которых нет стоимости монтажа, и образцы
                 */
                if($item['MOUNT_COST']['VALUE'] == '') continue;
                if(strpos($item['ID'],'s') !== false) continue;

                $mountItem = false;
                $i = 0;
                foreach($mount as $m_item) {
                    if($m_item->id == $item['ID']) {
                        $mountItem = $m_item;
                        echo renderMountItem($item,$mountItem,$n,$i);
                        $n++;
                        $i++;
                        if($m_item->bqty != $item['COUNT']) break;
                    }
                }
                if(!$mountItem) {
                    echo renderMountItem($item,$mountItem,$n);
                    $n++;
                }
            } ?>

            <?if($n == 0) { ?>
                <div class="mount-list-empty">
                    <span class="mount-list-empty-note">!</span>
                    В вашей корзине не&nbsp;найдено товаров, монтаж которых может быть рассчитан с&nbsp;помощью данного&nbsp;сервиса.
                </div>
            <? } ?>

        </div>
        <div class="right-column" data-type="calc-column">
            <div class="right-column-wrap" data-type="calc-wrap">

                <div class="mount-total-wrap">
                    <div class="main-total-info">
                        <div class="mount-total-headline">Общий расчет проекта</div>
                        <div class="main-total-title">
                            <div>Изделие</div>
                            <div>Стоимость монтажа</div>
                        </div>
                        <div class="main-total-items">
                            <? foreach($cart['items'] as $item) {
                                $i = 0;
                               if($item['MOUNT_COST']['VALUE'] == '') continue;
                                $mountItem = false;
                                foreach($mount as $m_item) {
                                    if($m_item->id == $item['ID']) {
                                        $mountItem = $m_item;
                                        echo renderMountTotal($item,$i);
                                        $i++;
                                        if($m_item->bqty != $item['COUNT']) break;
                                    }
                                }
                                if(!$mountItem) {
                                    echo renderMountTotal($item,$i);
                                    $i++;
                                }
                            } ?>
                            <?/*<div class="main-total-item completed" data-type="add-mat">
                                <div>Расходные материалы для монтажа</div>
                                <div data-type="total-cost">0.00 RUB</div>
                            </div>*/?>
                        </div>

                        <div class="mount-total-sum" data-type="total">
                            <div>Итого:</div>
                            <div data-type="total-cost">0.00 RUB</div>
                        </div>
                    </div>
                    <div class="main-total-btn-wrap<?if($n == 0) echo ' not-active'?>">
                        <button type="button" class="main-total-save" data-type="total-save">
                            <span>Сохранить расчет и&nbsp;вернуться в&nbsp;корзину</span>
                            (точную информацию о&nbsp;стоимости монтажа сообщит менеджер)
                        </button>
                        <button type="button" class="main-total-pdf" data-type="save-pdf">
                            <span>pdf</span>
                            <i class="new-icomoon icon-more"></i>
                        </button>
                    </div>

                </div>
            </div>

        </div>
    </section>
</div>
<?
setcookie("calc", 'y',time()+60*60*24*BASKET_EXPIRES,'/','.'.HTTP_HOST);
?>
<script src="/cart/mounting/mounting.js?<?=$random?>" type="text/javascript"></script>

<?
require($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");
if(defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED===true)
{
    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog.php");
}