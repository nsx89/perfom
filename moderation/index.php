<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock')) exit;
$APPLICATION->SetTitle("Модерация");
$APPLICATION->SetPageProperty("description", "Европласт - производство полиуретановых изделий, лидер на российском рынке");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}

global $USER;

$stat = "user";

$user_name = $USER->GetFullName();
$user_id = $USER->GetID();
$rsUser = CUser::GetByID($user_id);
$user = $rsUser->Fetch();

$user_groups = Array();
$res = CUser::GetUserGroupList($user_id);
while ($arGroup = $res->Fetch()) {
    $user_groups[] = $arGroup['GROUP_ID'];
}
$user_stat = 'spec';
if(in_array('1',$user_groups)) {
    $user_stat = "admin"; //админ - полные права
    $user_stat_dealer = "admin"; //админ - полные права
} elseif(in_array('10',$user_groups)) {
    $user_stat = "mod"; //модератор - полные права
}
if(in_array('12',$user_groups)) {
    $user_stat_dealer = "moddealer"; //модератор дилерского кабинета - полные права (доступ только в дилерскую часть)
} elseif(in_array('14',$user_groups)) {
    $user_stat_dealer = "specdealer"; //специалист дилерского кабинета - чтение/изменение через модерацию (доступ только в дилерскую часть)
} elseif(in_array('15',$user_groups)) {
    $user_stat_dealer = "userdealer"; //пользователь дилерского кабинета - чтение (доступ только в дилерскую часть)
}
require($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");

if (!$USER->IsAuthorized()) {
    include_once($_SERVER["DOCUMENT_ROOT"] . "/personal/auth.php");?>
    <script src="/personal/personal.js?<?=$random?>"></script>
<? } elseif(!in_array($user_stat_dealer,array('moddealer','specdealer','userdealer','mod','admin'))) { 
    //LocalRedirect('/personal/');
    ?>
    <div class="auth-wrapper">
        <div class="auth-img">
            <img src="/img/personal/auth.jpg" alt="авторизация">
        </div>
        <div class="user-form-wrapper authorization-cont authorized err">
            <div class="auth-data">
                <div class="auth-data-txt">Доступ на данную страницу <span>закрыт</span> для&nbsp;пользователя</div>
                <div class="auth-data-login"><?= CUser::GetLogin() ?></div>
                <div class="auth-data-txt">Хотите выйти из&nbsp;учётной записи?</div>
                <a href="?logout=yes" class="ok-btn user-form-btn">Выйти</a>
            </div>
        </div>
    </div>
    <script src="/personal/personal.js?<?=$random?>"></script>
<? } else { ?>

<link rel="stylesheet" href="/order_managment/order_managment.css?<?=$random?>">
<link rel="stylesheet" href="/moderation/moderation.css?<?=$random?>">
<div class="content-wrapper">

    <div class="main-sec-wrap">
        <?if(in_array($user_stat,array('mod','admin'))) { ?>
            <section class="main-sec-wrap-tabs" data-type="moder-tabs">
                <a href="#etc1" data-type="main-tab">Интернет-магазин</a>
                <a href="#etc3" data-type="main-tab">Отчёты</a>
                <a href="#etc2" data-type="main-tab" data-val="event-reg">Регистрация на&nbsp;мероприятия</a>
                <a href="/order_managment" target="_blank" class="main-tab-link">Администрирование заказов</a>
                <?if(in_array($user_stat_dealer,array('admin','moddealer','specdealer','userdealer'))) { ?>
                    <a href="#etc4" data-type="main-tab" data-val="dealers">Контрагенты</a>
                <? } ?>
            </section>
        <? } ?>


<? if (in_array($user_stat_dealer,array('moddealer','specdealer','userdealer')) || in_array($user_stat,array('admin','mod','admin'))): ?>
        <?include($_SERVER["DOCUMENT_ROOT"] . "/moderation/manager_panel.php");?>

        <section class="mod-tabs-cont">

            <?if(in_array($user_stat,array('mod','admin'))) { ?>
                  <div id="etc1" data-type="main-tab-cont" class="mod-tabs-cont-item">
                      <?
                      if($_GET['order']) {
                          require_once($_SERVER["DOCUMENT_ROOT"] . "/moderation/order.php");
                      } else {
                          require_once($_SERVER["DOCUMENT_ROOT"] . "/moderation/online-store.php");
                      }
                      ?>
                  </div>

                  <div id="etc3" data-type="main-tab-cont" class="mod-tabs-cont-item">
                      <? require_once($_SERVER["DOCUMENT_ROOT"] . "/moderation/reports.php"); ?>
                  </div>

                  <div id="etc2" data-type="main-tab-cont" class="mod-tabs-cont-item">
                      <? require_once($_SERVER["DOCUMENT_ROOT"] . "/moderation/statistics_new.php"); ?>
                  </div>
            <? } ?>

                <div id="etc4" data-type="main-tab-cont"  class="mod-tabs-cont-item<?if(in_array($user_stat_dealer,array('moddealer','specdealer','userdealer'))&& !in_array($user_stat,array('mod','admin'))) echo ' active'?>">
                    <?
                    if($_GET['type'] == 'edit' || $_GET['type'] == 'mod' || $_GET['type'] == 'saved' || $_GET['type'] == 'mod-spec-edit') {
                      require_once($_SERVER["DOCUMENT_ROOT"] . "/moderation/dealers/point.php");
                      } else {
                      require_once($_SERVER["DOCUMENT_ROOT"] . "/moderation/dealers/index.php");
                      }
                    ?>
                </div>

            <div class="dwnld-pricelist-mess dwnld-pricelist-mess-pacc" data-type="pacc-mess">
            <p><span>Внимание!</span> Изменение фильтров<br>может занять некоторое время</p>
        </div>
        </section>
    </div>
    <section class="main-sec-wrap mod-tabs-cont-res" data-type="mod-res">
        <?
        if(in_array($user_stat,array('mod','admin')) && $_GET['order']) {
            require_once($_SERVER["DOCUMENT_ROOT"] . "/moderation/order_products_table.php");
        }
        ?>
    </section>

        <? if ($_GET['type'] == 'edit' || $_GET['type'] == 'mod' || $_GET['type'] == 'saved' || $_GET['type'] == 'mod-spec-edit') {
                //require_once($_SERVER["DOCUMENT_ROOT"] . "/moderation/dealers/point.php");
            } else {?>
                <section class="main-sec-wrap mod-tabs-cont-res" data-type="md-dealer">
                   <? require_once($_SERVER["DOCUMENT_ROOT"] . "/moderation/dealers/index_bottom.php");?>
                </section>
            <? } ?>

<? endif;?>
</div>


<script src="/moderation/moderation.js?<?=$random?>"></script>

<? } ?>

<?
require($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");
if(defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED===true)
{
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog.php");
}