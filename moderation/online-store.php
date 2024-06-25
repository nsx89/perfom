<?
if(isset($_COOKIE['order_mod_date'])) {
    $sort_date = json_decode($_COOKIE['order_mod_date']);
    //print_r($sort_date);
    $sort_date_from = $sort_date->from;
    $new_sort_date_from = $sort_date_from;
    if($sort_date_from!='') {
        $sort_date_from = explode('.',$sort_date_from);
        $sort_date_from = $sort_date_from[2].'-'.$sort_date_from[1].'-'.$sort_date_from[0];
    }
    $sort_date_to = $sort_date->to;
    $new_sort_date_to = $sort_date_to;
    if($sort_date_to!='') {
        $sort_date_to = explode('.',$sort_date_to);
        $sort_date_to = $sort_date_to[2].'-'.$sort_date_to[1].'-'.$sort_date_to[0];
    }
    $sort_date_val = $sort_date->val;
    switch($sort_date_val) {
        case '2': {
            $sort_date_from = date("Y-m-d");
            break;
        };
        case '3': {
            $sort_date_from = date('Y-m-d',time() - (6 * 24 * 60 * 60));
            break;
        };
        case '4': {
            $sort_date_from = date('Y-m-d',strtotime("-1 month"));
            break;
        };
    }
}
else {
    //$sort_date_from = date('d.m.Y',time() - (6 * 24 * 60 * 60));
    $sort_date_from = date('Y-m-d',time() - (6 * 24 * 60 * 60));
    $sort_date_to = "";
    $sort_date_val = "3";
}
$arFilter = Array("IBLOCK_CODE"=>"keep_order","ACTIVE"=>"Y");
if($sort_date_from != "") {
    $arFilter['>=PROPERTY_DATE'] = $sort_date_from." 00:00:00";
}

if($sort_date_to != "") {
    $arFilter['<=PROPERTY_DATE'] = $sort_date_to." 23:59:59";
}
$sort_geo = json_decode($_COOKIE['filt_reg']);
$filr_reg = 'Все';
if($sort_geo!='') {
    $resFiltReg = CIBlockElement::GetByID($sort_geo);
    $ar_filt_reg = $resFiltReg ->GetNext();
    $filr_reg = $ar_filt_reg['NAME'];
    $arFilter['PROPERTY_CHOOSEN_REG'] = $sort_geo;
}


?>
<div class="man-top-section">
    <div class="pacc-nav">
        <div class="pacc-nav-filt pacc-nav-filt-period">
            <div class="pacc-nav-filt-title pacc-nav-filt-title-period">Отчетный период <i class="icon-filter-reset" data-type="remove-date"></i></div>
            <div class="pacc-nav-filt-params">
                <div class="pacc-nav-filt-params-column">
                    <div class="pacc-period<?if($sort_date_val=="1") echo ' active'?>" data-val="1" data-type="pacc-period">Все</div>
                    <div class="pacc-period<?if($sort_date_val=="2") echo ' active'?>" data-from="<?=date('d.m.Y')?>" data-val="2" data-type="pacc-period">За день</div>
                    <div class="pacc-period<?if($sort_date_val=="3") echo ' active'?>" data-from="<?=date('d.m.Y',time() - (6 * 24 * 60 * 60))?>" data-val="3" data-type="pacc-period">За неделю</div>
                    <div class="pacc-period<?if($sort_date_val=="4") echo ' active'?>" data-from="<?=date('d.m.Y',strtotime("-1 month"))?>" data-val="4" data-type="pacc-period">За месяц</div>
                </div>
                <div class="pacc-nav-filt-params-column">
                    <div class="pacc-period<?if($sort_date_val=="0") echo ' active'?>" data-val="0" data-type-val="period" data-type="pacc-period">Период</div>
                    <div class="pacc-period-choose<?if($sort_date_val!="0") echo ' unact'?>" data-type="pacc-period-choose">
                        <div class="e-qm-period-item">
                            <input type="text" name="qm-from" class="tcal<?if($sort_date_val=='0' && $new_sort_date_from !='') echo ' active'?>" value="<?if($sort_date_val=='0') echo $new_sort_date_from?>" id="qm-from" data-type="period-limit"/>
                            <label for="qm-from" class="qm-date-label"><span>с</span> <i class="icon-new-calendar"></i></label>
                        </div>

                        <div class="e-qm-period-item">
                            <input type="text" name="qm-to" class="tcal<?if($sort_date_val=='0' && $new_sort_date_to != '') echo ' active'?>" value="<?if($sort_date_val=='0') echo $new_sort_date_to?>" id="qm-to" data-type="period-limit"/>
                            <label for="qm-to" class="qm-date-label"><span>по</span> <i class="icon-new-calendar"></i></label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="pacc-nav-filt">
            <div class="pacc-nav-filt-wrap">
                <div class="pacc-nav-filt-title">Выбранный регион <i class="icon-filter-reset" data-type="clear-filt-geo"></i></div>
                <div class="e-new-catalogue-filters e-new-catalogue-sort" data-type="sort-wrap">
                    <div class="e-new-fiters-act e-new-filters-sort-act"  data-type="filter-reg">
                        <div class="e-new-sort-act<?if($filr_reg != 'Все') echo ' active'?>" data-type="sort-act">
                            <div class="e-new-act-name"><?=$filr_reg?></div>
                        </div>
                        <i class="icon-angle-down" data-type="open-sort"></i>
                    </div>
                </div>
            </div>
        </div>
        <a class="add-new-order add-new-order-show" data-type="show-online-store-report">Показать отчет <i class="new-icomoon icon-manual"></i></a>
    </div>
</div>
<div class="man-bottom-section">
    <section class="pacc-nav">

    </section>
</div>