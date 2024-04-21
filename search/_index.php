<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock')) exit;
$APPLICATION->SetTitle("Результаты поиска");
$APPLICATION->SetPageProperty("description", "Результаты поиска");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")|| !CModule::IncludeModule("search")) {
    exit;
}
require($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");
?>
<div class="search-page active" data-type="search-page">
    <div class="content-wrapper">
        <form action="/search" data-type="search-formSP" class="search-form">
            <input data-type="searchSP" type="text" placeholder="поиск" class="search-input" name="q" id="q" value="<?=$_REQUEST['q']?>" data-val="all">
            <button type="reset" data-type="search-resetSP" class="search-reset"><i class="icon-close"></i></button>
            <img src="/img/preloader.gif" alt="wait" class="search-wait">
            <i class="icon-search"></i>
        </form>
        <div class="search-mess" data-type="search-messSP">по данному запросу нет&nbsp;результатов</div>
        <div class="search-result" data-type="search-resSP"></div>
    </div>
</div>

<?/**
 * развязываем поиск в хедере с поиском на странице,
 * костыль, но быстро
 **/ ?>
<script src="/search/search.js?<?=$random?>"></script>
<?
require($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");
if(defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED===true)
{
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog.php");
}