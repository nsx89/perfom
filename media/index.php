<? 
$class = 'm-media-body-list';
if (!empty($_GET['detail'])) $class = 'm-media-body-detail';

require_once("head.php");

use Media\Media;

$uri = $_SERVER['REQUEST_URI'];
$arr_uri = explode('?', $uri);
$uri = $arr_uri[0];

$category = processing($_GET['category']);
$detail = processing($_GET['detail']);

$page = (int)processing($_GET['page']);
$start = 11;
$step = 10;
$limit = $start;
if (!empty($page)) {
    $limit = $page.','.$step; //для поисковиков
    $start_new = $page + $step;
}
else {
    $start_new = $start;
}

//Карточка статьи
if (!empty($detail)) {
    require_once('detail.php'); 
}
else {

    //Материалы
    $materials = [];
    $materials_big = [];
    $i = 1;

    $order = $where = '';
    if (!empty($filter_info)) {
        switch ($filter_info['id']){
            case '1': $order = "date DESC,"; break;
            case '2': $order = "views DESC,"; break;
            case '3': 
                $ids = Media::flagIds();
                if (!empty($ids)) $where .= " AND `id` IN(".implode(',', $ids).")";
                break;
        }
    }
    else {
        $order = "date DESC,";
    }
    if (!empty($category_info)) {
        $where .= " AND `category` = ".$category_info['id']."";
    }

    $search = processing($_GET['search']);
    if (!empty($search)) {
        $where .= " AND `name` LIKE '%{$search}%'";
    }

    $all = Media::list_all($order, $where);
    $items = Media::list($order, $where, $limit);

    foreach ($items AS $item) {
        if ($i == 1) $materials_big[] = $item;
        else $materials[] = $item;
        $i++;
    }

    //Самое читаемое
    $top = Media::list('views DESC,', '', 5);

    //Хлебные крошки
    $breads = array(); 
    $breads[] = array('name' => 'Главная', 'link' => '/');
    if (!empty($category)) {
      $breads[] = array('name' => MEDIA_NAME, 'link' => MEDIA_FOLDER.'/');
      if (!empty($category_info)) {
          $breads[] = array('name' => $category_info['name']);
      } 
      if (!empty($filter_info)) {
          $breads[] = array('name' => $filter_info['name']);
      }  
    }
    else {
      $breads[] = array('name' => MEDIA_NAME);
    }
    ?>

    <div class="m-media-center">

        <?=  Media::breads($breads); ?>

        <div class="m-media-items">

            <? if (!empty($materials_big)) { ?>

              <? foreach ($materials_big AS $item) { ?>
                  <?= Media::siteItem($item, 'm-media-item-big'); ?>
              <? } ?>
            
            <? } else { ?>
            
              <div class="m-media-not-found">Ничего не найдено</div>

            <? } ?>

            <div class="m-media-right m-mobile-right">
              <div class="m-media-right-head">
                Самое читаемое:
              </div>
              <div class="m-media-right-items-wrap">
                <div class="m-media-right-items">
                  <? foreach ($top AS $item) { ?>
                    <div class="m-media-right-item">
                      <a href="<?= Media::siteLink($item) ?>" class="m-media-right-item-name dotdotdot">
                        <?= $item['name'] ?>
                      </a>
                      <div class="m-media-item-icons">
                        <span class="m-media-item-icon m-media-item-icon-fav <?= Media::likes($item['id']) ? 'm-media-item-icon-fav-active' : '' ?>" data-id="<?= $item['id'] ?>" title="Лайк"><?= Media::siteFav($item) ?></span>
                        <span class="m-media-item-icon m-media-item-icon-flag <?= Media::flag($item['id']) ? 'm-media-item-icon-flag-active' : '' ?>" data-id="<?= $item['id'] ?>" title="В закладки"></span>
                      </div>
                    </div>

                  <? } ?>
                </div>
              </div>
            </div>

            <div class="m-media-items-list">
              <? foreach ($materials AS $item) { ?>
                  <?= Media::siteItem($item); ?>
              <? } ?>
            </div>

            <? if ($all > count($items) && $all > 0) { ?>
              
              <? if (empty($page)) { ?>
                <div class="m-media-load js-m-media-load m-media-loading" 
                  data-all="<?= $all ?>" 
                  data-step="<?= $step ?>" 
                  data-start="<?= $start ?>" 
                  data-order="<?= base64_encode($order) ?>" 
                  data-where="<?= base64_encode($where) ?>" 
                  data-media="<?= MEDIA_FOLDER ?>"
                  ></div>
              <? } ?>
            
              <? if ($all > $start_new) { ?>  
                <a class="m-media-load-robots" href="<?= $uri ?>?page=<?= $start_new ?>"></a>
              <? } ?>
              
            <? } ?>

        </div>

    </div>
    <div class="m-media-right">
      <div class="m-media-right-head">
        Самое читаемое:
      </div>

      <? foreach($top AS $item) { ?>
        <div class="m-media-right-item">
          <a href="<?= Media::siteLink($item) ?>" class="m-media-right-item-name">
            <?= $item['name'] ?>
          </a>
          <div class="m-media-item-icons">
            <span class="m-media-item-icon m-media-item-icon-fav <?= Media::likes($item['id']) ? 'm-media-item-icon-fav-active' : '' ?>" data-id="<?= $item['id'] ?>" title="Лайк"><?= Media::siteFav($item) ?></span>
            <span class="m-media-item-icon m-media-item-icon-flag <?= Media::flag($item['id']) ? 'm-media-item-icon-flag-active' : '' ?>" data-id="<?= $item['id'] ?>" title="В закладки"></span>
          </div>
        </div>

      <? } ?>

    </div>

<? } ?>

<? require_once("foot.php"); ?>


  