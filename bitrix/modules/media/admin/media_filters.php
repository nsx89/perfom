<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/iblock/prolog.php');

use Bitrix\Main\Loader;
use Bitrix\Main;
use Bitrix\Iblock;
use Bitrix\Catalog;

$APPLICATION->SetTitle('Медиа-центр');
$APPLICATION->SetAdditionalCSS("/media/css/admin/style.css");
?>

<div class="main-ui-filter-search main-ui-filter-theme-default main-ui-filter-set-inside main-ui-filter-search--active" id="tbl_iblock_list_243c5708df32671c24323e21ad3b5687_search_container">
    <input type="text" tabindex="1" value="" name="FIND" placeholder="Фильтр + поиск" class="main-ui-filter-search-filter" id="tbl_iblock_list_243c5708df32671c24323e21ad3b5687_search" autocomplete="off">
    <div class="main-ui-item-icon-block">
        <span class="main-ui-item-icon main-ui-search"></span>
        <span class="main-ui-item-icon main-ui-delete"></span>
    </div>
</div>


<table class="main-grid-table">
    <thead class="main-grid-header">
        <tr class="main-grid-row-head">
            <th
                class="main-grid-cell-head main-grid-cell-left">
                <div class="main-grid-cell-inner">
                    <span class="main-grid-cell-head-container">
                        <span class="main-grid-head-title">Название</span>
                    </span>
                </div>
            </th>
        </tr>
    </thead>
    <tbody>
        <tr class="main-grid-row main-grid-row-body">
            <td class="main-grid-cell main-grid-cell-left">
                <div class="main-grid-cell-inner">
                    <span class="main-grid-cell-content">
                        <a href="#" title="Редактировать элемент">1111111111</a>
                    </span>
                </div>
            </td>
        </tr>
    </tbody>
</table>



<?
/*
$arSelect = Array("ID", "NAME", "DATE_ACTIVE_FROM");
$arFilter = Array("IBLOCK_ID"=>'31', "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
while($ob = $res->GetNextElement())
{
    $arFields = $ob->GetFields();
    print_r($arFields);
}
*/
?>



<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");