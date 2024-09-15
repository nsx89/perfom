<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock')) exit;
$APPLICATION->SetTitle("Результаты поиска");
$APPLICATION->SetPageProperty("description", "Результаты поиска");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")|| !CModule::IncludeModule("search")) {
    exit;
}
/*$section_list = Array();
$sec_res = CIBlockSection::GetList(Array("SORT"=>"ASC"), Array("IBLOCK_ID" => IB_CATALOGUE,), false, Array('UF_*'));
while ($sec_ob = $sec_res->GetNext()) {
    $section_list[$sec_ob['ID']] = $sec_ob;
}
print_r($section_list);*/
require($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");
?>
<div class="search-page active" data-type="search-page">
    <div class="content-wrapper">
        <form data-type="search-formSP" class="search-form">
            <input data-type="searchSP" type="text" placeholder="поиск" class="search-input" name="q" id="search-q" value="<?=$_REQUEST['q']?>" data-val="all">
            <button type="reset" data-type="search-resetSP" class="search-reset"><i class="icon-close"></i></button>
            <img src="/img/preloader.gif" alt="wait" class="search-wait">
            <i class="icon-search"></i>
        </form>
        <h1 class="hidden">Поиск</h1>
        <div class="search-mess" data-type="search-messSP">по данному запросу нет&nbsp;результатов</div>
        <div class="search-result" data-type="search-resSP-wrap">
            <div class="search-resSP-qty" data-type="search-resSP-qty">Результаты поиска: <span>0</span></div>
            <div class="search-resSP" data-type="search-resSP">
                <div class="search-tabs-cont-wrap">
                    <div class="search-tab-cont active" id="prod">
                        <div class="col-prod-tab">
                            <div data-type="search-resSP-prod"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?
require($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");
if(defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED===true)
{
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog.php");
}