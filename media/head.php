<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock')) exit;

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/media/classes/media_pages.php');
use Media\Media;

Media::setMeta();

//Временно закрыть весь раздел от индексации
$APPLICATION->SetPageProperty("robots", "noindex, nofollow");

require($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");

$APPLICATION->SetAdditionalCSS(MEDIA_FOLDER."/css/plyr.css", true);
$APPLICATION->SetAdditionalCSS(MEDIA_FOLDER."/css/main.css", true);

$APPLICATION->AddHeadScript(MEDIA_FOLDER."/js/dotdotdot.js");
$APPLICATION->AddHeadScript(MEDIA_FOLDER."/js/plyr.js");
$APPLICATION->AddHeadScript(MEDIA_FOLDER."/js/main.js");

global $DB;

$category = processing($_GET['category']);

/* --- Фильтры --- */
$filters = [];
$filter_first = '';
$filter_info = null;
$filter_res = $DB->Query("SELECT * FROM `m_media_filter` WHERE `active`='1' ORDER BY SORT ASC");
while ($filter_row = $filter_res->fetch()) {
    if (empty($filter_first)) $filter_first = $filter_row['code'];
    $filters[] = $filter_row;

    if ($filter_row['code'] == $category) $filter_info = $filter_row;
}
$filter_active = $category;
/* --- // --- */

/* --- Категории --- */
$categories = [];
$category_info = null;
$category_res = $DB->Query("SELECT * FROM `m_media_category` WHERE `active`='1' ORDER BY SORT ASC");
while ($category_row = $category_res->fetch()) {
    $categories[] = $category_row;

    if ($category_row['code'] == $category) $category_info = $category_row;
}
/* --- // --- */

if (!empty($category) && empty($filter_info) && empty($category_info)) {
    require_once("404.php"); exit;
}
?>

<section data-url="<?= MEDIA_FOLDER ?>" class="m-media <?= !empty($class) ? $class : '' ?>">
  <div class="m-media-line">
    <div class="content-wrapper m-media-columns">
      <div class="m-media-left">&nbsp;</div>
      <div class="m-media-center">
        <form class="m-media-search" method="GET" action="<?= MEDIA_FOLDER ?>">
            <i class="m-media-search-icon js-m-media-search-icon" title="Искать"></i>
            <input type="text" name="search" class="m-media-input js-media-search" placeholder="Поиск" autocomplete="off" value="<?= processing($_GET['search']) ?>">
            <input type="submit" name="q" value="1">
            <div class="m-media-search-items"></div>
        </form>
      </div>
      <div class="m-media-right">
        <div class="m-media-icons">
          <? /* <span class="m-media-icon m-media-icon-notify"></span> */ ?>
          <a href="/media/sohranennoe/" class="m-media-icon m-media-icon-flag" title="Сохраненное"></a>
        </div>
      </div>
    </div>
  </div>

  <div class="m-mobile-menu-items">
    <a href="<?= MEDIA_FOLDER.'/' ?>" class="m-mobile-menu-item <?= empty($filter_active) ? 'm-mobile-menu-item-active' : '' ?>"><?= MEDIA_NAME ?></a>
    <? foreach($filters AS $filter) { ?>
      <a href="<?= MEDIA_FOLDER.'/'.$filter['code'].'/' ?>" class="m-mobile-menu-item <?= $filter_active == $filter['code'] ? 'm-mobile-menu-item-active' : '' ?>"><?= $filter['name'] ?></a>
    <? } ?>
    <div class="m-mobile-menu-dots"></div>
  </div>

  <div class="content-wrapper m-media-columns">
    <div class="m-media-left m-mobile-menu">
      <div class="m-media-mobile-close"></div>
      
      <? 
      $menu = 'main';
      require("menu.php"); 
      ?>

      <div class="m-media-menu-fixed">
        <?
        $menu = 'fixed';
        require("menu.php"); 
        ?>

        <div class="m-media-menu-fixed-search">
          <form class="m-media-search" method="GET" action="<?= MEDIA_FOLDER ?>">
              <i class="m-media-search-icon js-m-media-search-icon" title="Искать"></i>
              <input type="text" name="search" class="m-media-input js-media-search" placeholder="Поиск" autocomplete="off" value="<?= processing($_GET['search']) ?>">
              <input type="submit" name="q" value="1">
              <div class="m-media-search-items"></div>
          </form>
        </div>

      </div>
      
    </div>