<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
$APPLICATION->SetTitle("Администрирование поиска");
$APPLICATION->SetPageProperty("description", "Европласт - производство полиуретановых изделий, лидер на российском рынке");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog") || !CModule::IncludeModule("search")) {
    exit;
}
global $USER;
$stat = "user";

$user_name = $USER->GetFullName();
$user_id = $USER->GetID();
$rsUser = CUser::GetByID($user_id);
$user = $rsUser->Fetch();

$user_group_arr = [];
while ($arGroup = $rsUser->Fetch()) {
    $user_group_arr[] = $arGroup['GROUP_ID'];
}

require($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");

if (!$USER->IsAuthorized()) {
    include_once($_SERVER["DOCUMENT_ROOT"] . "/personal/auth.php");?>
    <script src="/personal/personal.js"></script>
<? }
?>
    <link rel="stylesheet" href="/order_managment/order_managment.css">
    <link rel="stylesheet" href="/smart_search/admin/style.css?<?=$random?>">
    <div class="content-wrapper">

        <? //можно админам, модераторам и специалистам по поиску ?>
        <? if ($USER->IsAuthorized() && in_array(1,$user_group_arr) || $USER->IsAuthorized() && in_array(10,$user_group_arr) || $USER->IsAuthorized() && in_array(16,$user_group_arr)): ?>

            <?
            if(isset($_COOKIE['ss_date'])) {
                $sort_date = json_decode($_COOKIE['ss_date']);
                $sort_date_from = $sort_date->from;
                $new_sort_date_from = $sort_date_from;
                /*if($sort_date_from!='') {
                    $sort_date_from = explode('.',$sort_date_from);
                    $sort_date_from = $sort_date_from[2].'-'.$sort_date_from[1].'-'.$sort_date_from[0];
                }*/
                $sort_date_to = $sort_date->to;
                $new_sort_date_to = $sort_date_to;
                /*if($sort_date_to!='') {
                    $sort_date_to = explode('.',$sort_date_to);
                    $sort_date_to = $sort_date_to[2].'-'.$sort_date_to[1].'-'.$sort_date_to[0];
                }*/
                $sort_date_val = $sort_date->val;
            }
            else {
                $sort_date_from = date('01.m.Y');
                $sort_date_to = "";
                $sort_date_val = "0";
                $new_sort_date_from = date('01.m.Y');
            }

            $ss_admin_arr = Array();
            $ss_res = Array();
            $ss_empty = 0;
            $ss_not_empty = 0;
            $arFilter = Array("IBLOCK_CODE"=>"smart_search_statistics","ACTIVE"=>"Y");
            if($sort_date_from != "") {
                $arFilter['>=DATE_CREATE'] = $sort_date_from." 00:00:00";
            }
            if($sort_date_to != "") {
                $arFilter['<=DATE_CREATE'] = $sort_date_to." 23:59:59";
            }
            $ss_admin_res = CIBlockElement::GetList(Array('PROPERTY_RES_QTY'=>'asc'), $arFilter, false, Array(), Array("NAME","PROPERTY_RES_QTY"));
            while($ss_admin_ob = $ss_admin_res->GetNextElement()) {
                $ss_admin_arr[] = array_merge($ss_admin_ob->GetFields(), $ss_admin_ob->GetProperties());
            }
            foreach($ss_admin_arr as $item) {
                if(array_key_exists($item['NAME'],$ss_res)) {
                    $n = $ss_res[$item['NAME']]['qty']+1;
                } else {
                    $n = 1;
                }
                $ss_res[$item['NAME']]['qty'] = $n;
                $ss_res[$item['NAME']]['res'] = $item['PROPERTY_RES_QTY_VALUE'];
                if($item['PROPERTY_RES_QTY_VALUE'] == 0) {
                    $ss_empty++;
                } else {
                    $ss_not_empty++;
                }
            }
            ?>

            <div data-type="main-tab-cont" class="e-tabs-content active">
                <div class="om-top-sections-wrap">
                    <section class="om-left-section">
                        <div class="pacc-nav pacc-nav-main">
                            <div class="pacc-nav-filt pacc-nav-filt-period">
                                <div class="pacc-nav-filt-title pacc-nav-filt-title-period">Отчетный период <i class="icon-filter-reset" data-type="remove-date" title="Сбросить"></i></div>
                                <div class="pacc-nav-filt-params">
                                    <div class="pacc-period pacc-period-all<?if($sort_date_val=="1") echo ' active'?>" data-val="1">Все</div>
                                    <div class="pacc-period pacc-period-per<?if($sort_date_val=="0") echo ' active'?>" data-val="0" data-type="period">Период</div>
                                    <div class="pacc-period-choose<?if($sort_date_val!="0") echo ' unact'?>">
                                        <div class="e-qm-period-item">
                                            <input type="text" name="qm-from" class="tcal<?if($sort_date_val=='0' && $new_sort_date_from !='') echo ' active'?>" value="<?if($sort_date_val=='0') echo $new_sort_date_from?>" id="qm-from" data-type="period-limit"/>
                                            <label for="qm-from" class="qm-date-label"><span>с</span> <i class="icon-new-calendar"></i></label>
                                        </div>

                                        <div class="e-qm-period-item">
                                            <input type="text" name="qm-to" class="tcal<?if($sort_date_val=='0' && $new_sort_date_to != '') echo ' active'?>" value="<?if($sort_date_val=='0') echo $new_sort_date_to?>" id="qm-to" data-type="period-limit"/>
                                            <label for="qm-to" class="qm-date-label"><span>по</span> <i class="icon-new-calendar"></i></label>
                                        </div>
                                    </div>
                                    <div class="pacc-nav-filt-btn" data-type="search-stat-btn">Применить</div>
                                </div>
                            </div>
                        </div>


                    </section>
                    <? require_once($_SERVER["DOCUMENT_ROOT"] . "/order_managment/manager_panel.php"); ?>
                </div>
                <section class="orders-list search-stat-list">
                    <?if(!empty($ss_admin_arr)) { ?>
                        <div class="search-used">Количество поисковых запросов: <span><?=count($ss_admin_arr)?></span></div>
                        <div class="ss-results-wrap">
                            <div class="ss-success-wrap">
                                <div class="ss-fail-wrap-title">Запросы с результатами</div>
                                <table>
                                    <tr class="order-table-title">
                                        <th>Поисковое слово</th>
                                        <th>Запросы</th>
                                        <th>Результаты</th>
                                    </tr>
                                    <?
                                    if($ss_not_empty > 0) {
                                    foreach($ss_res as $k=>$item) {?>
                                        <?if($item['res'] == 0) continue;?>
                                        <tr>
                                            <td><?=$k?></td>
                                            <td><?=$item['qty']?></td>
                                            <td><?=$item['res']?></td>
                                        </tr>
                                    <? }
                                    } else { ?>
                                        <tr>
                                            <td colspan="3">
                                                За отчетный период не&nbsp;найдено запросов с&nbsp;ненулевыми результатами.
                                            </td>
                                        </tr>
                                    <? } ?>
                                </table>
                            </div>
                            <div class="ss-fail-wrap">
                                <div class="ss-fail-wrap-title">Нулевые запросы</div>
                                <table>
                                    <tr class="order-table-title">
                                        <th>Поисковое слово</th>
                                        <th>Запросы</th>
                                    </tr>
                                    <?
                                    if($ss_empty > 0) {
                                    foreach($ss_res as $k=>$item) { ?>
                                        <?if($item['res'] != 0) continue; ?>
                                        <tr>
                                            <td><?=$k?></td>
                                            <td><?=$item['qty']?></td>
                                        </tr>
                                    <? }
                                    } else { ?>
                                        <tr>
                                            <td colspan="3">
                                                За отчетный период не&nbsp;найдено запросов с&nbsp;нулевыми результатами.
                                            </td>
                                        </tr>
                                    <? } ?>
                                </table>
                            </div>
                        </div>
                    <? } else { ?>
                        <p class="pacc-err">Не найдено поисковых запросов с&nbsp;такими&nbsp;параметрами</p>
                    <? } ?>
                </section>
            </div>

            <div class="dwnld-pricelist-mess dwnld-pricelist-mess-pacc" data-type="pacc-mess">
                <p><span>Внимание!</span> Изменение фильтров<br>может занять некоторое время</p>
            </div>

        <? elseif($USER->IsAuthorized()): ?>
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
        <? endif;?>

    </div>


    <script src="/smart_search/admin/script.js?<?=$random?>"></script>

<?
require($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");
if(defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED===true)
{
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog.php");
}