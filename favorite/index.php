<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock')) exit;
$APPLICATION->SetTitle("Избранное");
$APPLICATION->SetPageProperty("description", "Избранное");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}
require($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");
$data_onpage = 10;
$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
$prev = ($page-1)*$data_onpage;
$next = $page*$data_onpage;

if($_REQUEST['all']) {
    $prev = 0;
    $next = count($wishlist);
}
?>

<div class="content-wrapper">
<div class="col-prod-tab active fav-content">

<?if( empty($favorite) ) { ?>
    <div class="wishlist-desc">
        <h1 class="hidden">Избранные товары</h1>
        <div class="no-fav">нет избранных товаров</div>
        <div class="no-fav-desc">подберите для&nbsp;себя подходящий товар у&nbsp;нас&nbsp;в&nbsp;каталоге</div>
        <a href="/karnizy/">в каталог</a>
    </div>
<? } else { ?>
    <div class="fav-wrap">
        <h1 class="main-blocks-title">Избранное</h1>
        <a class="clear-wishlist" data-type="clear-wishlist" data-user="<?=in_array(5,$user_group_arr ) ? 'user' : 'no-user'?>">Очистить список</a>
    </div>
    <div class="wishlist" data-type="items-list" data-val="fav">
        <img src="/img/preloader.gif" alt="wait">
    </div>

    <div class="pagination"<?if(count($favorite) == 0 || count($favorite) <= $data_onpage) echo ' style="display:none"'?>>
        <div class="pag-title">Страницы</div>
        <div class="pag-wrap"<?if($page == 'all') echo ' style="display:none"'?>>
            <div class="pag" data-items="<?=count($favorite)?>" data-onpage="<?=$data_onpage?>" data-type="pag" data-current="<?=$page?>"></div>
            <div class="show-all-btn" data-type="show-all">Показать все</div>
            <div class="show-wait">
                <img src="/img/preloader.gif" alt="loading">
            </div>
        </div>
        <div class="show-per-page<?if($page == 'all') echo ' active'?>">
            <div class="show-per-page-txt">
                Показаны все избранные товары
            </div>
            <div class="show-per-page-btn" data-type="per-page">
                Показать постранично
            </div>
        </div>
    </div>

<? } ?>
</div>
</div>
<script src="/personal/personal.js?<?=$random?>"></script>
    <script>
        $(document).ready(function() {
            pagination();
            ePagination(true);
        })
    </script>
<?
require($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");
if(defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED===true)
{
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog.php");
}
