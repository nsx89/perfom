<?
/**
 * index.php - стартовая страница дилеров
 * point.php - страница отдельного дилера
 * get_dealer_list.php - ajax списка дилеров (по id региона или id дилера)
 * search_dealer.php - поиск дилеров (старое, сейчас не используется. Поиск идет через get_dealer_list.php)
 * ajax.php - прочие ajax запросы (сделать регионального дилера и др.)
 * img_uploader.php - работа с загрузкой файлов
 * clear_temp_imgs.php - чистка папки temp от старых файлов (больше суток)
 * parameters.php - переменные и константы, которые используются в кабинете
 * dealers.js - скрипты
 * dealers.css - стили
 */
require_once($_SERVER["DOCUMENT_ROOT"] . "/moderation/dealers/clear_temp_imgs.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/moderation/dealers/parameters.php");
$start_reg = 3109;
$md_reg = isset($_REQUEST['reg']) ? $_REQUEST['reg'] : $start_reg;

$arFilter = Array("IBLOCK_ID"=>7, "ID"=>$md_reg, 'ACTIVE'=>'Y');
$res = CIBlockElement::GetList(Array("sort"=>'ASC'), $arFilter, false, Array(), Array());
while($ob = $res->GetNextElement()) {
    $md_city = array_merge($ob->GetFields(), $ob->GetProperties());
}

// на модерации
$arFilterMod = Array("IBLOCK_ID"=>50, '!PROPERTY_temp'=>'Y', '!PROPERTY_accept'=>'Y', '!PROPERTY_reject'=>'Y', 'PROPERTY_dealer_id' => get_dependent_spec());
$mod_res = CIBlockElement::GetList(Array("sort"=>'ASC'), $arFilterMod, false, Array(), Array());
$mod_qty = intval($mod_res->SelectedRowsCount());

// промежуточное сохранение
$arFilterSaved = Array("IBLOCK_ID"=>50, 'PROPERTY_dealer_id'=>$user_id, 'PROPERTY_temp'=>'Y', '!PROPERTY_accept'=>'Y', '!PROPERTY_reject'=>'Y');
$saved_res = CIBlockElement::GetList(Array("sort"=>'ASC'), $arFilterSaved, false, Array(), Array());
$saved_qty = intval($saved_res->SelectedRowsCount());

?>

<link rel="stylesheet" href="/moderation/dealers/dealers.css?<?=$random?>">

<?
/*$rsUsers = CUser::GetList(($by="id"), ($order="asc"), Array("EMAIL"=>'ololo@ololo.lo'));
//if()
$arUser = $rsUsers->Fetch();
if($arUser) {
    print_r($arUser);
    $user_groups = CUser::GetUserGroup($arUser['ID']);
    print_r($user_groups);
} else {
    print_r('oh no');
}
while ($arUser = $rsUsers->Fetch()) {
    $user = $arUser
}*/
?>

<div class="md-panel pacc-nav">
    <div class="pacc-nav-filt">
        <div class="pacc-nav-filt-wrap">
            <div class="e-new-catalogue-filters e-new-catalogue-sort" data-type="sort-wrap">
                <div class="e-new-fiters-act e-new-filters-sort-act md-panel-btn"  data-type="md-reg">
                    <div class="e-new-sort-act active">
                        <div class="e-new-act-name"><span class="md-choose-reg-title">регион</span>
                            <span class="md-choose-reg-val" data-type="md-reg-val" data-default="<?=$md_reg?>" data-val="<?=$md_reg?>"><?=$md_city['NAME']?></span></div>
                    </div>
                    <i class="icon-angle-down" data-type="open-sort"></i>
                </div>
            </div>
        </div>
        <div class="nav-order-search-wrap">
            <form method="get" action="#" class="e-new-main-search-form md-search md-panel-btn">
                <input type="text" placeholder="поиск" name="md-search" autocomplete="off" value="<?=$_REQUEST['qwr']?>">
                <button type="button" data-type="md-btn-search"><i class="icon-search"></i></button>
            </form>
        </div>
    </div>

    <div class="search-filter-wrap md-panel-btn">
        <div class="search-filter-item">
            <input type="checkbox" name="retail" value="y" id="searchFilt1">
            <label for="searchFilt1">собственная розница</label>
        </div>
        <div class="search-filter-item">
            <input type="checkbox" name="subdealer" value="y" id="searchFilt2">
            <label for="searchFilt2">субдилерская сеть</label>
        </div>
        <div class="search-filter-item">
            <input type="checkbox" name="maindealer" id="searchFilt3" value="y">
            <label for="searchFilt3">главный дилер</label>
        </div>
        <div class="search-filter-item">
            <input type="checkbox" name="published" value="y" id="searchFilt4">
            <label for="searchFilt4">опубликованные</label>
        </div>
        <div class="search-filter-item">
            <input type="checkbox" name="nopublished" value="y" id="searchFilt5">
            <label for="searchFilt5">неопубликованные</label>
        </div>
    </div>

    <div class="md-view-wrap">
        <div class="md-view md-panel-btn" data-type="md-view" data-val="map">На карте</div>
        <div class="md-view md-panel-btn" data-type="md-view" data-val="list">Списком</div>
    </div>

<div class="md-section-btns">
    <?if(in_array($user_stat_dealer,array('mod','admin','moddealer','specdealer'))) { ?>
        <a href="/moderation/?type=edit&id=new#etc4" class="md-panel-btn md-new" data-val="dealer">Новая точка <i class="icon-plus"></i></a>
    <? } ?>
    <?if($mod_qty > 0 && in_array($user_stat_dealer,array('mod','admin','moddealer'))) { ?>
        <div class="md-panel-btn md-moder" data-val="moder" data-qty="<?=$mod_qty?>">На модерации <span><?=$mod_qty?></span></div>
    <? } ?>
    <?if(in_array($user_stat_dealer,array('specdealer'))) { ?>
        <div class="md-panel-btn md-moder md-moder-spec" data-val="moder-spec">Модерация</div>
    <? } ?>
    <?if($saved_qty > 0 && in_array($user_stat_dealer,array('specdealer'))) { ?>
        <div class="md-panel-btn md-saved" data-val="saved" data-qty="<?=$saved_qty?>">Сохранено <span><?=$saved_qty?></span></div>
    <? } ?>
</div>
</div>

<div class="md-bottom-panel">
    <div class="md-bottom-panel-reg-dealer">
        <i class="icon-warning"></i>
        <div class="md-bottom-panel-reg-dealer-txt">Для текущего региона установлен региональный дилер.</div>
        <?/*
        <div class="md-bottom-panel-reg-dealer-btn" data-type="reset-reg-dealer">Сбросить</div>
        */?>
    </div>
</div>








<?
//require_once($_SERVER["DOCUMENT_ROOT"] . "/moderation/dealers/get_dealer_list.php");
?>

<script src="/js/uploader-1.0.2/jquery.dm-uploader.min.js"></script>
<?/*<script src="/scripts/uploader-1.0.2/demo-ui.js"></script>
<script src="/scripts/uploader-1.0.2/demo-config.js"></script>*/?>
<script src="/moderation/dealers/dealers.js?<?=$random?>"></script>