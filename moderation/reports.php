<?
/**
 * Created by PhpStorm.
 * User: nadida
 * Date: 17.02.2020
 * Time: 11:36
 */
/*ini_set('display_errors', 1);
error_reporting(E_ALL);*/

?>
<div class="mod-rep-content">
    <section class="mod-reports">
        <div class="mod-reports-title">Выберите отчёт</div>
        <div class="mod-reports-wrap">
            <div class="mod-reports-item" data-val="moscow" data-type="choose-rep" data-tit="rep1">Продажи по <span style="text-transform:uppercase;">М</span>оскве</div>
            <div class="mod-reports-item active" data-val="other" data-type="choose-rep" data-tit="rep2">Заказы онлайн-подбора</div>
        </div>

    </section>

    <section class="mod-reports-tabs">

        <div class="mod-reports-tab" data-type="rep-tab" data-val="rep1">
            <div class="pacc-nav">
                <div class="pacc-nav-filt pacc-nav-filt-wrap pacc-nav-filt-period" data-type="date">
                    <div class="pacc-nav-filt-title">Отчетный период <i class="icon-filter-reset" data-type="remove-date-rep"></i></div>
                    <div class="pacc-nav-filt-params">
                        <div class="pacc-nav-filt-params-column">
                            <div class="pacc-period" data-val="1" data-type="rep-period">Все</div>
                            <div class="period-wrap">
                                <div class="pacc-period pacc-period-month active" data-val="2" data-type-val="period" data-type="rep-period">За месяц</div>
                                <div class="pacc-period-choose" data-type="rep-period-choose">
                                    <div class="e-qm-period-item e-qm-period-item-month">
                                        <input type="text" name="qm-from" class="ymcal" value="<?=date('m.Y')?>" id="mFrom" data-type="period-limit"/>
                                        <label for="mFrom" class="qm-date-label">
                                            <span></span>
                                            <i class="icon-new-calendar"></i>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pacc-nav-filt-params-column">
                            <div class="period-wrap">
                                <div class="pacc-period" data-val="3" data-type-val="period" style="margin-bottom:9px;" data-type="rep-period">Период</div>
                                <div class="pacc-period-choose unact" data-type="rep-period-choose">
                                    <div class="e-qm-period-item">

                                        <input type="text" name="qm-from" class="tcal" value="" id="per-from" data-type="period-limit"/>
                                        <label for="per-from" class="qm-date-label">
                                            <span>c</span>
                                            <i class="icon-new-calendar"></i>
                                        </label>
                                    </div>

                                    <div class="e-qm-period-item">
                                        <input type="text" name="qm-to" class="tcal" value="" id="per-to" data-type="period-limit"/>
                                        <label for="per-to" class="qm-date-label">
                                            <span>по</span>
                                            <i class="icon-new-calendar"></i>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pacc-nav-filt">
                    <div class="pacc-nav-filt-wrap pacc-nav-filt-wrap-manager" data-type="manager">
                        <div class="pacc-nav-filt-title">Менеджер <i class="icon-filter-reset" data-type="clear-filt"></i></div>
                        <div class="e-new-catalogue-filters e-new-catalogue-sort" data-type="sort-wrap">
                            <div class="e-new-fiters-act e-new-filters-sort-act" data-type="show-sort" data-act="show">
                                <div class="e-new-sort-act<?if($ordr_manager) echo ' active'?>" data-type="sort-act">
                                    <?
                                    $filter_manager = 'Выбрать, ';
                                    if($ordr_manager) {
                                        $filter_manager = '';
                                        foreach($ordr_manager as $manager) {
                                            if($manager == 'no') {
                                                $filter_manager .= 'Нет менеджера, ';
                                            } else {
                                                $rsUser = CUser::GetByID($manager);
                                                $arUser = $rsUser->Fetch();
                                                $filter_manager .= $arUser['LAST_NAME'] != '' ? $arUser['LAST_NAME'].' '.mb_substr($arUser['NAME'],0,1).'.' : $arUser['NAME'];
                                                $filter_manager .= ', ';
                                            }
                                        }
                                    }
                                    $filter_manager = substr($filter_manager,0,-2);
                                    $manager_groups = array(13);
                                    $rsUsers = CUser::GetList($by="id", $order="desc", array('GROUPS_ID' => $manager_groups, 'ACTIVE' => 'Y'));
                                    $count_user = $rsUsers->SelectedRowsCount();
                                    ?>
                                    <div class="e-new-act-name"><?=$filter_manager?></div>
                                </div>
                                <i class="icon-angle-down" data-type="open-sort"></i>
                            </div>
                            <div class="e-new-filters-wrap e-new-sort-wrap" data-type="sort">
                                <div class="e-new-filters-flex-wrap">
                                    <div class="e-new-sort" data-type="e-sort">
                                        <div class="e-new-sort-type e-new-sort-type-manager" data-type="manager-list">
                                            <div class="e-new-sort-type-item-wrap<?if(in_array('no',$ordr_manager)) echo ' active'?>">
                                                <div class="e-new-sort-type-item" data-val="no" data-type="sort-param">Нет менеджера</div>
                                            </div>
                                            <?
                                            while ($arUser = $rsUsers->Fetch()) { ?>
                                                <div class="e-new-sort-type-item-wrap<?if(in_array($arUser['ID'],$ordr_manager)) echo ' active'?>">
                                                    <div class="e-new-sort-type-item" data-val="<?=$arUser['ID']?>" data-type="sort-param"><?=$arUser['LAST_NAME'] != '' ? $arUser['LAST_NAME'].' '.mb_substr($arUser['NAME'],0,1).'.' : $arUser['NAME']?></div>
                                                </div>
                                            <? } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pacc-nav-filt-wrap" data-type="payment" style="z-index:3;">
                        <div class="pacc-nav-filt-title">Способ оплаты <i class="icon-filter-reset" data-type="clear-filt"></i></div>
                        <div class="e-new-catalogue-filters e-new-catalogue-sort" data-type="sort-wrap">
                            <div class="e-new-fiters-act e-new-filters-sort-act" data-type="show-sort" data-act="show">
                                <div class="e-new-sort-act mod-new-sort-act-last" data-type="sort-act">
                                    <div class="e-new-act-name">Выбрать</div>
                                </div>
                                <i class="icon-angle-down" data-type="open-sort"></i>
                            </div>
                            <div class="e-new-filters-wrap e-new-sort-wrap" data-type="sort" style="display: none;">
                                <div class="e-new-filters-flex-wrap">
                                    <div class="e-new-sort" data-type="e-sort">
                                        <div class="e-new-sort-type">
                                            <div class="e-new-sort-type-item-wrap">
                                                <div class="e-new-sort-type-item" data-val="receiving-card" data-type="sort-param">При получении картой</div>
                                            </div>
                                            <div class="e-new-sort-type-item-wrap">
                                                <div class="e-new-sort-type-item" data-val="receiving-cash" data-type="sort-param">При получении наличными</div>
                                            </div>
                                            <div class="e-new-sort-type-item-wrap">
                                                <div class="e-new-sort-type-item" data-val="online" data-type="sort-param">Онлайн</div>
                                            </div>
                                            <div class="e-new-sort-type-item-wrap">
                                                <div class="e-new-sort-type-item" data-val="prepayment" data-type="sort-param">Предоплата</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pacc-nav-filt">
                    <div class="pacc-nav-filt-wrap" data-type="status">
                        <div class="pacc-nav-filt-title">Статус заказа <i class="icon-filter-reset" data-type="clear-filt"></i></div>
                        <div class="e-new-catalogue-filters e-new-catalogue-sort" data-type="sort-wrap">
                            <div class="e-new-fiters-act e-new-filters-sort-act" data-type="show-sort" data-act="show">
                                <div class="e-new-sort-act" data-type="sort-act">
                                    <div class="e-new-act-name">Выбрать</div>
                                </div>
                                <i class="icon-angle-down" data-type="open-sort"></i>
                            </div>
                            <div class="e-new-filters-wrap e-new-sort-wrap" data-type="sort" style="display: none;">
                                <div class="e-new-filters-flex-wrap">
                                    <div class="e-new-sort" data-type="e-sort">
                                        <div class="e-new-sort-type">
                                            <? $res = CIBlockElement::GetList(Array('SORT'=>'ASC'), Array('IBLOCK_CODE'=>'order_status', 'ACTIVE'=>'Y'), false, Array(), Array());
                                            while($ob = $res->GetNextElement()) {
                                                $stat = array_merge($ob->GetFields(), $ob->GetProperties());
                                                ?>
                                                <div class="e-new-sort-type-item-wrap">
                                                    <div class="e-new-sort-type-item" data-val="<?=$stat['CODE']?>" data-type="sort-param"><?=$stat['NAME']?></div>
                                                </div>
                                            <? }?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pacc-nav-filt-wrap" data-type="delivery"  style="z-index:2;">
                        <div class="pacc-nav-filt-title">Способ получения <i class="icon-filter-reset" data-type="clear-filt"></i></div>
                        <div class="e-new-catalogue-filters e-new-catalogue-sort" data-type="sort-wrap">
                            <div class="e-new-fiters-act e-new-filters-sort-act" data-type="show-sort" data-act="show">
                                <div class="e-new-sort-act mod-new-sort-act-last" data-type="sort-act">
                                    <div class="e-new-act-name">Выбрать</div>
                                </div>
                                <i class="icon-angle-down" data-type="open-sort"></i>
                            </div>
                            <div class="e-new-filters-wrap e-new-sort-wrap" data-type="sort" style="display: none;">
                                <div class="e-new-filters-flex-wrap">
                                    <div class="e-new-sort" data-type="e-sort">
                                        <div class="e-new-sort-type">
                                            <div class="e-new-sort-type-item-wrap">
                                                <div class="e-new-sort-type-item" data-val="del" data-type="sort-param">Доставка</div>
                                            </div>
                                            <div class="e-new-sort-type-item-wrap">
                                                <div class="e-new-sort-type-item" data-val="pickup" data-type="sort-param">Самовывоз</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pacc-nav-filt-big">
                <a class="add-new-order add-new-order-show" data-type="show-report" data-val="order_mosc">Показать отчет</a>
                <a class="add-new-order add-new-order-dwnld" data-type="download-report" data-val="order_mosc">Скачать отчет</a>
            </div>
        </div>


        <div class="mod-reports-tab active" data-type="rep-tab" data-val="rep2">
            <div class="pacc-nav">
                <div class="pacc-nav-filt pacc-nav-filt-wrap pacc-nav-filt-period" data-type="date">
                <div class="pacc-nav-filt-title">Отчетный период <i class="icon-filter-reset" data-type="remove-date-rep"></i></div>
                <div class="pacc-nav-filt-params">
                    <div class="pacc-nav-filt-params-column">
                        <div class="pacc-period" data-val="1" data-type="rep-period">Все</div>
                        <div class="period-wrap">
                            <div class="pacc-period pacc-period-month" data-val="2" data-type-val="period" data-type="rep-period">За месяц</div>
                            <div class="pacc-period-choose" data-type="rep-period-choose">
                                <div class="e-qm-period-item e-qm-period-item-month">
                                    <input type="text" name="qm-from" class="ymcal" value="" id="mFromOnline" data-type="period-limit"/>
                                    <label for="mFromOnline" class="qm-date-label">
                                        <span></span>
                                        <i class="icon-new-calendar"></i>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pacc-nav-filt-params-column">
                        <div class="period-wrap">
                            <div class="pacc-period" data-val="3" data-type-val="period" style="margin-bottom:9px;" data-type="rep-period">Период</div>
                            <div class="pacc-period-choose unact" data-type="rep-period-choose">
                                <div class="e-qm-period-item">

                                    <input type="text" name="qm-from" class="tcal" value="" id="per-from-online" data-type="period-limit"/>
                                    <label for="per-from-online" class="qm-date-label">
                                        <span>c</span>
                                        <i class="icon-new-calendar"></i>
                                    </label>
                                </div>

                                <div class="e-qm-period-item">
                                    <input type="text" name="qm-to" class="tcal" value="" id="per-to-online" data-type="period-limit"/>
                                    <label for="per-to-online" class="qm-date-label">
                                        <span>по</span>
                                        <i class="icon-new-calendar"></i>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
            <div class="pacc-nav-filt-big pacc-nav-filt-big-online">
                <a class="add-new-order add-new-order-show" data-type="show-report" data-val="online">Показать отчет</a>
                <a class="add-new-order add-new-order-dwnld" data-type="download-report" data-val="online">Скачать отчет</a>
            </div>
        </div>

    </section>

    <section class="report-body" data-type="report-body"></section>
</div>

