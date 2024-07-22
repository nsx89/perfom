<?
if (empty($menu)) {
  require_once("head.php");
  require_once("404.php"); exit;
}
?>

<div class="m-media-menu-wrap">
  <div class="m-media-menu m-media-menu-main">
    <a href="<?= MEDIA_FOLDER.'/' ?>" class="m-media-menu-link <?= empty($filter_active) ? 'm-media-menu-link-active' : '' ?>">
        <span class="m-media-menu-icon"><span style="background: url(<?= MEDIA_FOLDER ?>/img/icons_main/icon1.svg) no-repeat center center;"></span></span>
        <?= MEDIA_NAME ?>
    </a>          
    <? foreach($filters AS $filter) { ?>
      <a class="m-media-menu-link <?= $filter_active == $filter['code'] ? 'm-media-menu-link-active' : '' ?>" href="<?= MEDIA_FOLDER.'/'.$filter['code'] ?>/">
        <? if (!empty($filter['icon'])) { ?>
          <span class="m-media-menu-icon"><span style="background: url(<?= MEDIA_FOLDER ?>/upload/m_media_filter/<?= $filter['icon'] ?>) no-repeat center center;"></span></span>
        <? } else { ?>
          <span class="m-media-menu-icon"><span style="background: url(<?= MEDIA_FOLDER ?>/img/icons_main/icon1.svg) no-repeat center center;"></span></span>
        <? } ?>
        <?= $filter['name'] ?>
      </a>
    <? } ?>
  </div>
</div>
<div class="m-media-menu-wrap">
  <div class="m-media-menu m-media-menu-compact">
    <? foreach ($categories AS $item) { ?>
      <a class="m-media-menu-link <?= $item['hidden'] == 1 ? 'm-media-menu-link-hidden' : '' ?> <?= $category == $item['code'] ? 'm-media-menu-link-active' : '' ?>" href="<?= MEDIA_FOLDER.'/'.$item['code'] ?>/">
        <? if (!empty($item['icon'])) { ?>
          <span class="m-media-menu-icon"><span style="background: url(<?= MEDIA_FOLDER ?>/upload/m_media_category/<?= $item['icon'] ?>) no-repeat center center;"></span></span>
        <? } else { ?>
          <span class="m-media-menu-icon"><span style="background: url(<?= MEDIA_FOLDER ?>/img/icons/icon1.svg) no-repeat center center;"></span></span>
        <? } ?>
        <?= $item['name'] ?>
      </a>
    <? } ?>
  </div>
</div>