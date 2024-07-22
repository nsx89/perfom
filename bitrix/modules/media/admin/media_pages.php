<? 
ob_start();
require_once('header.php');

use Custom\Admin;
use Media\Media;
use Media\MediaCategory;
use Media\MediaTypes;
use Media\MediaConstructor;

$user_id = $USER->GetID();

$title = 'Материалы';
$APPLICATION->SetTitle($title);

$table = Media::getTable();
global $DB; 
global $USER;

if (!empty($_POST['constructor_create'])) {
   ob_end_clean();
   MediaConstructor::add();
   exit;
}
elseif (!empty($_POST['constructor_item_delete'])) {
   ob_end_clean();
   $id = (int)Admin::processing($_POST['id']);
   MediaConstructor::item_delete($id);
   exit;
}
elseif (!empty($_POST['constructor_marker_create'])) {
   ob_end_clean();
   MediaConstructor::marker_add();
   exit;
}
elseif (!empty($_POST['constructor_marker_delete'])) {
   ob_end_clean();
   $id = (int)Admin::processing($_POST['id']);
   MediaConstructor::marker_delete($id);
   exit;
}
elseif (!empty($_POST['constructor_question_create'])) {
   ob_end_clean();
   MediaConstructor::question_add();
   exit;
}
elseif (!empty($_POST['constructor_question_delete'])) {
   ob_end_clean();
   $id = (int)Admin::processing($_POST['id']);
   MediaConstructor::question_delete($id);
   exit;
}
elseif (!empty($_POST['constructor_answer_create'])) {
   ob_end_clean();
   MediaConstructor::answer_add();
   exit;
}
elseif (!empty($_POST['constructor_answer_delete'])) {
   ob_end_clean();
   $id = (int)Admin::processing($_POST['id']);
   MediaConstructor::answer_delete($id);
   exit;
}
elseif (!empty($_POST['links_create'])) {
   ob_end_clean();
   MediaConstructor::links_add();
   exit;
}
elseif (!empty($_POST['links_delete'])) {
   ob_end_clean();
   $id = (int)Admin::processing($_POST['id']);
   MediaConstructor::links_delete($id);
   exit;
}
else if(isset($_GET['edit']) || isset($_GET['add'])) {
   $tab = (int)Admin::processing($_GET['tab']);
   if (empty($tab)) $tab = 1;
   
   $id = (int)Admin::processing($_GET['edit']);
   $res = $DB->Query("SELECT * FROM `{$table}` WHERE id='{$id}' LIMIT 1");
   if($res->SelectedRowsCount() > 0 || isset($_GET['add']))
   {
      $row = $res->Fetch();

      if (isset($_GET['add'])) {
         $row['sort'] = 500;
         $row['active'] = 1;
         $APPLICATION->SetTitle($title.': Элемент: Добавление');
         $row['date'] = time();
         $site_link = '';
      }
      else {
         $APPLICATION->SetTitle($title.': Элемент: '.$row['name'].' - Редактирование');
         $site_link = Media::siteLink($row);
      }  

      $user_add = Admin::info_user($row['user_add']);
      $user_edit = Admin::info_user($row['user_edit']);
      ?>
      
      <?= Admin::back($id, $site_link) ?>

      <form action='<?= $_SERVER['PHP_SELF'] ?>' method='POST' enctype='multipart/form-data'>
        <div class="adm-detail-tabs-block adm-detail-tabs-block-settings" style="left: 0px;">
            <span class="adm-detail-tab js-adm-detail-tab <?= $tab == 1 ? 'adm-detail-tab-active' : '' ?>" data-id="1">Элемент</span>
            <span class="adm-detail-tab js-adm-detail-tab <?= $tab == 2 ? 'adm-detail-tab-active' : '' ?>" data-id="2">Детальная страница</span>
            <span class="adm-detail-tab js-adm-detail-tab <?= $tab == 3 ? 'adm-detail-tab-active' : '' ?>" data-id="3">SEO</span>
            <span class="adm-detail-tab js-adm-detail-tab <?= $tab == 4 ? 'adm-detail-tab-active' : '' ?>" data-id="4">Статистика</span>
         </div>

         <div class="adm-detail-content-wrap">
            <div class="adm-detail-content" id="tab1" style="<?= $tab == 1 ? 'display: block;' : '' ?>">
               <div class="adm-detail-title">Элемент</div>
               <div class="adm-detail-content-item-block" style="height: auto; overflow-y: visible;">
                  <table class="adm-detail-content-table edit-table" id="edit1_edit_table" style="opacity: 1;">
                     <tbody>
                        <?= Admin::info('ID:', $id) ?>
                        <?= Admin::info('Создан:', !empty($row['date_add']) ? date('d.m.Y H:i:s', $row['date_add']).$user_add : '' ) ?>
                        <?= Admin::info('Изменён:', !empty($row['date_edit']) ? date('d.m.Y H:i:s', $row['date_edit']).$user_edit : '' ) ?>
                        <?= Admin::checkbox('active', 'Показывать на сайте:', $row['active']) ?>
                        <?= Admin::checkbox('noindex', 'Закрыть от индексации:', $row['noindex']) ?>
                        <?= Admin::input_date('date', 'Дата публикации:', $row['date'], true) ?>
                        <?= Admin::input('name', 'Название:', $row['name'], true, 'js-linked-name') ?>
                        <?= Admin::input('code', 'Ссылка (автоматически, если пусто):', $row['code'], false, 'js-linked-code', true) ?>
                        <?= Admin::select('Категория:', 'm_media_category', 'category', $row, ' sort ASC,', '', true) ?>
                        <? /*
                        <?= Admin::select('Тип материала:', 'm_media_filter', 'filter', $row, ' sort ASC,', ' AND id <> 3', false) ?>
                        */ ?>
                        <?= Admin::textarea('short', 'Краткое описание:', $row['short']) ?>
                        <?= Admin::image_cropper('preview_picture', 'Картинка превью (780x468px):', $table, $row, 780, 468) ?>
                        <?= Admin::input_sort('sort', 'Сортировка:', $row['sort']) ?>
                     </tbody>
                  </table>
               </div>
            </div>
            <div class="adm-detail-content" id="tab2" style="<?= $tab == 2 ? 'display: block;' : '' ?>">
               <?= MediaConstructor::textbox_hide(); ?>
               <div class="adm-detail-title">Детальная страница</div>
               <div class="adm-detail-content-item-block" style="height: auto; overflow-y: visible;">
                  <table class="adm-detail-content-table edit-table" id="edit1_edit_table" style="opacity: 1;">
                     <tbody>
                        <?= Admin::image('detail_picture', 'Баннер сверху (макс. ширина 1086px, высота любая):', $table, $row, 1086, 1086) ?>
                        <?= Admin::input('detail_picture_info', 'Описание фотографии сверху:', $row['detail_picture_info'], false, false, false, 155) ?>
                        <tr>
                           <td colspan="2">
                              <div class="links-wrap">
                                 <div class="links-items">
                                    <?= MediaConstructor::links($id); ?>
                                 </div>
                                 <div class="links-create-wrap">
                                    <input type="button" class="button links-create" value="Добавить якорь" data-media_id="<?= $id ?>" />
                                 </div>
                              </div>
                           </td>
                        </tr>
                        <tr><td colspan="2">&nbsp;</td></tr>
                        <tr class="heading constructor-heading"><td colspan="2">Конструктор блоков:</td></tr>
                        <? $types = MediaTypes::getList(); ?>
                        <tr>
                           <td colspan="2">
                              <div class="constructor-items">
                                 <?= MediaConstructor::items($id); ?>
                              </div>
                              <div class="constructor">
                                 <span class="constructor-label"><strong>Выберите тип блока:</strong></span>
                                 <select class="constructor-select">
                                    <option value="0">Не выбрано</option>
                                    <? foreach ($types AS $type) { ?>
                                       <option value="<?= $type['id'] ?>"><?= $type['name'] ?></option>
                                    <? } ?>
                                 </select>
                                 <div class="constructor-create-wrap">
                                    <input type="button" class="button constructor-create" value="Создать блок" data-media_id="<?= $id ?>" />
                                 </div>
                              </div>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </div>
            </div>
            <div class="adm-detail-content" id="tab3" style="<?= $tab == 3 ? 'display: block;' : '' ?>">
               <div class="adm-detail-title">Настройки SEO информации</div>
               <div class="adm-detail-content-item-block" style="height: auto; overflow-y: visible;">
                  <table class="adm-detail-content-table edit-table" id="edit1_edit_table" style="opacity: 1;">
                     <tbody>
                        <?= Admin::input('title', 'Title:', $row['title']) ?>
                        <?= Admin::input('keywords', 'Keywords:', $row['keywords']) ?>
                        <?= Admin::input('description', 'Description:', $row['description']) ?>
                     </tbody>
                  </table>
               </div>
            </div>
            <div class="adm-detail-content" id="tab4" style="<?= $tab == 4 ? 'display: block;' : '' ?>">
               <div class="adm-detail-title">Статистика</div>
               <div class="adm-detail-content-item-block" style="height: auto; overflow-y: visible;">
                  <table class="adm-detail-content-table edit-table" id="edit1_edit_table" style="opacity: 1;">
                     <tbody>
                        <?= Admin::input('views', 'Количество просмотров:', $row['views']) ?>
                        <?= Admin::input('likes', 'Количество лайков:', $row['likes']) ?>
                        <?= Admin::input('flag', 'Количество сохранений:', $row['flag']) ?>
                     </tbody>
                  </table>
               </div>
            </div>
            <div class="adm-detail-content-btns-wrap" style="left: 0px;">
               <div class="adm-detail-content-btns">
                  <input name='id' type='hidden' value='<?= $id ?>' />
                  <input type="hidden" name="lang" value="<?= LANGUAGE_ID ?>">
                  <input type="hidden" name="tab" value="<?= $tab ?>">
                  <input type="submit" class="adm-btn-save" name="save" value="Сохранить" /> 
                  <input type="submit" class="button" name="apply" value="Применить" />
               </div>
            </div>
         </div>

      </form>
      <div class="adm-btn-save adm-btn-save-fixed">Сохранить</div>

   <? } else { ?>

      <?= Admin::not_found_edit(); ?>

   <? } ?>

<? } else if(isset($_POST['save']) || isset($_POST['apply'])) { ?>

   <?
   $tab = (int)Admin::processing($_POST['tab']);
   $id = (int)Admin::processing($_POST['id']);

   $code = Admin::processing($_POST['code']);
   if (empty($code)) $code = Admin::rus_to_eng($name);

   $preview_picture = Admin::image_upload('preview_picture', $table, 780, 468, true, $id); 
   $detail_picture = Admin::image_upload('detail_picture', $table, 1086, 1086, true, $id); 

   if (!empty($_POST['date'])) $date = strtotime(Admin::processing($_POST['date']));
   else $date = time();

   $query = "`name` = '". Admin::processing($_POST['name'])."'
      , `code` = '{$code}'
      , `active` = '".Admin::processing($_POST['active'])."'
      , `noindex` = '".Admin::processing($_POST['noindex'])."'
      , `category` = '".Admin::processing($_POST['category'])."'
      , `filter` = '".Admin::processing($_POST['filter'])."'
      , `sort` = '".Admin::processing($_POST['sort'])."'
      , `title` = '".Admin::processing($_POST['title'])."'
      , `keywords` = '".Admin::processing($_POST['keywords'])."'
      , `description` = '".Admin::processing($_POST['description'])."'
      , `date` = '".$date."'
      , `short` = '".Admin::processing($_POST['short'])."'
      , `views` = '".Admin::processing($_POST['views'])."'
      , `likes` = '".Admin::processing($_POST['likes'])."'
      , `flag` = '".Admin::processing($_POST['flag'])."'
      , `detail_picture_info` = '".Admin::processing($_POST['detail_picture_info'])."'
      {$preview_picture}
      {$detail_picture}
   ";

   if (empty($id)) {
      $DB->Query("INSERT INTO `{$table}` SET
         {$query}
         , `date_add` = '".time()."'
         , `user_add` = '".$user_id."'
      ");
      $id = $DB->LastID();
   }
   else {
      $DB->Query("UPDATE `{$table}` SET
         {$query}
         , `date_edit` = '".time()."'
         , `user_edit` = '".$user_id."'
         WHERE id='{$id}'
      ");
   }

   MediaConstructor::save($id);

   $location = '';
   if (isset($_POST['apply'])) $location = "&edit=".$id.'&tab='.$tab;

   header("location: ".$_SERVER['PHP_SELF']."?lang=".LANGUAGE_ID.$location);
   exit;
   ?>

<? } else if(isset($_GET['del'])) { ?>

   <?
   $id = (int)Admin::processing($_GET['del']);

   Admin::image_dir(MEDIA_FOLDER."/upload/{$table}/");

   $res = $DB->Query("SELECT * FROM `{$table}` WHERE id='{$id}' LIMIT 1");
   $row = $res->Fetch();
   
   $name = 'preview_picture';
   if (!empty($row[$name])) {
       unlink(trim($row[$name]));
       $DB->Query("UPDATE {$table} SET `{$name}`='' WHERE `id`='{$del}'");
   }

   $name = 'detail_picture';
   if (!empty($row[$name])) {
       unlink(trim($row[$name]));
       $DB->Query("UPDATE {$table} SET `{$name}`='' WHERE `id`='{$del}'");
   }

   MediaConstructor::delete($id);

   $DB->Query("DELETE FROM `{$table}` WHERE id='".$id."'");

   header("location: ".$_SERVER['PHP_SELF']."?lang=".LANGUAGE_ID);
   exit;
   ?>

<? } else { ?>
   <?
   $data = Admin::getList($table);
   $list = $data['list'];
   $paginate = $data['paginate'];
   $total = $data['total'];

   $category = (int)$_GET['m_media_category'];
   $date_from = Admin::processing($_GET['date_from']);
   $date_to = Admin::processing($_GET['date_to']);
   ?>
   <div class="adm-toolbar-panel-container">
      <?= Admin::search(); ?>
      <?= Admin::add(); ?>
   </div>
   <div class="filter">
      <?= Admin::select_filter('m_media_category', $category, 'Категория:', '', 'chosen') ?>
      <?= Admin::input_date_filter('date_from', 'Дата от:', $date_from) ?>
      <?= Admin::input_date_filter('date_to', 'Дата до:', $date_to) ?>
   </div>

   <table class="main-grid-table">
       <thead class="main-grid-header">
           <tr class="main-grid-row-head">
               <?= Admin::th('Название'); ?>
               <?= Admin::th('Ссылка'); ?>
               <?= Admin::th('Картинка превью'); ?>
               <?= Admin::th('Категория'); ?>
               <?= Admin::th('Дата публикации'); ?>
               <?= Admin::th('Сортировка'); ?>
               <?= Admin::th('Просмотры'); ?>
               <?= Admin::th('Показывать на сайте'); ?>
               <?= Admin::th('ID'); ?>
           </tr>
       </thead>
       <tbody>
           <? if (!empty($list)) { ?>

               <? foreach ($list AS $item) { ?>
                  <tr class="main-grid-row main-grid-row-body" style="<?= $item['active'] == 1 ? '' : 'opacity: 0.8;' ?>">
                      <?= Admin::td($item['name'], Admin::edit_link($item['id'])); ?>
                      <?= Admin::td(Media::siteLinkHtml($item)); ?>
                      <?= Admin::td(Admin::image_view($item['preview_picture'], $table)); ?>
                      <?= Admin::td(Admin::multilist($item['category'])); ?>
                      <?= Admin::td(!empty($item['date']) ? date('d.m.Y H:i', $item['date']) : ''); ?>
                      <?= Admin::td($item['sort']); ?>
                      <?= Admin::td($item['views']); ?>
                      <?= Admin::td($item['active'] == 1 ? 'Да' : 'Нет'); ?>
                      <?= Admin::td($item['id'], Admin::edit_link($item['id'])); ?>
                  </tr>
               <? } ?>

           <? } else { ?>

               <?= Admin::not_found(9); ?>
           
           <? } ?>
           
       </tbody>
   </table>

<?= Admin::total($data) ?>

<? } ?>




<? require_once('footer.php'); ?>