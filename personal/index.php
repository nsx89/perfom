<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}
$APPLICATION->SetTitle("Личный кабинет");
$APPLICATION->SetPageProperty("description", "Европласт - производство полиуретановых изделий, лидер на российском рынке");
require($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");

global $USER;

$user_id = $USER->GetID();
$user_group_arr = [];
$res = CUser::GetUserGroupList($user_id);
while ($arGroup = $res->Fetch()) {
    $user_group_arr[] = $arGroup['GROUP_ID'];
}
$stat = 'spec';
if(in_array('1',$user_group_arr)) {
    $stat = "admin"; //админ - полные права
} elseif(in_array('10',$user_group_arr)) {
    $stat = "mod"; //модератор - полные права
} elseif(in_array('12',$user_group_arr)) {
    $stat = "moddealer"; //модератор дилерского кабинета - полные права (доступ только в дилерскую часть)
} elseif(in_array('14',$user_group_arr)) {
    $stat = "specdealer"; //специалист дилерского кабинета - чтение/изменение через модерацию (доступ только в дилерскую часть)
} elseif(in_array('15',$user_group_arr)) {
    $stat = "userdealer"; //пользователь дилерского кабинета - чтение (доступ только в дилерскую часть)
}

$currency_infо = get_currency_info($loc['country']['VALUE']);
$curr = $currency_infо['abbr'];

if ($USER->IsAuthorized()) { ?>

    <style>
        .content {
            background: #F2F2F2;
        }
        @media screen and (max-width: 600px) {
            .content {
                background: #fff;
            }
        }
    </style>

    <div class="profile-wrap">

        <div class="personal-tab-collection-wrap">
            <div class="tab-collection personal-tab-collection" data-type="personal-tabs">
                <div class="profile-nav-wrap">
                    <a href="#profile" data-type="main-tab" data-val="profile" class="personal-tab-collection-item active"><i class="icon-profile"></i>Профиль</a>
                    <div class="profile-nav profile-nav-desktop" data-type="profile-nav">
                        <div class="profile-nav-item" data-type="edit">Редактировать данные</div>
                        <div class="profile-nav-item" data-type="pass">Изменить пароль</div>
                        <a class="profile-nav-item" href="?logout=yes">Выйти</a>
                    </div>
                </div>
                <a href="#orders" data-type="main-tab" data-val="orders" class="personal-tab-collection-item"><i class="icon-cart"></i>Заказы</a>
                <a href="#saved" data-type="main-tab" data-val="saved" class="personal-tab-collection-item"><i class="icon-saved"></i>Сохранённое</a>
                <?/*<div class="pers-acc-top-bg"><img src="/img/main-pref-bg.svg" alt="bg"></div>*/?>
            </div>
        </div>


        <div class="profile-nav profile-nav-mob" data-type="profile-nav-mob">
            <div class="profile-nav-item" data-type="edit">Редактировать данные</div>
            <div class="profile-nav-item" data-type="pass">Изменить пароль</div>
            <a class="profile-nav-item" href="?logout=yes">Выйти</a>
        </div>

        <div class="personal-tab-content">
            <div data-type="main-tab-cont" id="profile" class="e-tabs-content active">
                <? require_once($_SERVER["DOCUMENT_ROOT"] . "/personal/profile.php"); ?>
            </div>
            <div data-type="main-tab-cont" id="orders" class="e-tabs-content">
                <? if($_GET['order']) {
                    require_once($_SERVER["DOCUMENT_ROOT"] . "/personal/order.php");
                } else {
                    require_once($_SERVER["DOCUMENT_ROOT"] . "/personal/all_orders.php");
                } ?>
            </div>
            <div data-type="main-tab-cont" id="saved" class="e-tabs-content">
                <? if($_GET['saved_order']) {
                    require_once($_SERVER["DOCUMENT_ROOT"] . "/personal/saved_order.php");
                } else {
                    require_once($_SERVER["DOCUMENT_ROOT"] . "/personal/all_saved_orders.php");
                } ?>
            </div>
        </div>

    </div>

<?
} else {
    include_once($_SERVER["DOCUMENT_ROOT"] . "/personal/auth.php");
} ?>

<script src="/personal/personal.js?<?=$random?>"></script>

<? require($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");
if(defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED===true) {
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog.php");
}