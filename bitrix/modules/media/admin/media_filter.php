<? require_once('header.php'); ?>

<?
use Custom\Admin;
use Media\Media;
use Media\MediaFilter;

$user_id = $USER->GetID();

$title = 'Фильтры';
$APPLICATION->SetTitle($title);

$table = MediaFilter::getTable();
global $DB;

if(isset($_GET['edit']) || isset($_GET['add']))  {
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
      }
      else {
         $APPLICATION->SetTitle($title.': Элемент: '.$row['name'].' - Редактирование');
      }  
      $events_show = false;
      if ($id > 3) $events_show = true;
      ?>
      
      <?= Admin::back($id, null, $events_show) ?>

      <form action='<?= $_SERVER['PHP_SELF'] ?>' method='POST' enctype='multipart/form-data'>
        <div class="adm-detail-tabs-block adm-detail-tabs-block-settings" style="left: 0px;">
            <span class="adm-detail-tab js-adm-detail-tab <?= $tab == 1 ? 'adm-detail-tab-active' : '' ?>" data-id="1">Элемент</span>
            <span class="adm-detail-tab js-adm-detail-tab <?= $tab == 2 ? 'adm-detail-tab-active' : '' ?>" data-id="2">SEO</span>
         </div>

         <div class="adm-detail-content-wrap">
            <div class="adm-detail-content" id="tab1" style="<?= $tab == 1 ? 'display: block;' : '' ?>">
               <div class="adm-detail-title">Элемент</div>
               <div class="adm-detail-content-item-block" style="height: auto; overflow-y: visible;">
                  <table class="adm-detail-content-table edit-table" id="edit1_edit_table" style="opacity: 1;">
                     <tbody>
                        <?= Admin::info('ID:', $id) ?>
                        <?= Admin::info('Создан:', !empty($row['date_add']) ? date('d.m.Y H:i:s', $row['date_add']) : '' ) ?>
                        <?= Admin::info('Изменен:', !empty($row['date_edit']) ? date('d.m.Y H:i:s', $row['date_edit']) : '' ) ?>
                        <?= Admin::checkbox('active', 'Показывать на сайте:', $row['active']) ?>
                        <?= Admin::input('name', 'Название:', $row['name'], true, 'js-linked-name') ?>
                        <?= Admin::input('code', 'Ссылка (автоматически, если пусто):', $row['code'], true, 'js-linked-code', true) ?>
                        <?= Admin::image('icon', 'Иконка (макс. ширина и высота 30px):', $table, $row) ?>
                        <?= Admin::input_sort('sort', 'Сортировка:', $row['sort']) ?>
                     </tbody>
                  </table>
               </div>
            </div>
            <div class="adm-detail-content" id="tab2" style="<?= $tab == 2 ? 'display: block;' : '' ?>">
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

   <? } ?>

<? } else if(isset($_POST['save']) || isset($_POST['apply'])) { ?>

   <?
   $tab = (int)Admin::processing($_POST['tab']);
   $id = (int)Admin::processing($_POST['id']);

   $code = Admin::processing($_POST['code']);
   if (empty($code)) $code = Admin::rus_to_eng($name);

   $image = Admin::image_upload('icon', $table, 30, 30, true, $id); 

   $query = "`name` = '". Admin::processing($_POST['name'])."'
      , `code` = '{$code}'
      , `active` = '".Admin::processing($_POST['active'])."'
      , `sort` = '".Admin::processing($_POST['sort'])."'
      , `title` = '".Admin::processing($_POST['title'])."'
      , `keywords` = '".Admin::processing($_POST['keywords'])."'
      , `description` = '".Admin::processing($_POST['description'])."'
      {$image}
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
   $name = 'icon';
   if (!empty($row[$name])) {
       unlink(trim($row[$name]));
       $DB->Query("UPDATE {$table} SET `{$name}`='' WHERE `id`='{$del}'");
   }

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
   ?>
   <div class="adm-toolbar-panel-container">
      <?= Admin::search(); ?>
      <?= Admin::add(); ?>
   </div>

   <table class="main-grid-table">
       <thead class="main-grid-header">
           <tr class="main-grid-row-head">
               <?= Admin::th('Название'); ?>
               <?= Admin::th('Ссылка'); ?>
               <?= Admin::th('Иконка'); ?>
               <?= Admin::th('Сортировка'); ?>
               <?= Admin::th('Показывать на сайте'); ?>
               <?= Admin::th('ID'); ?>
           </tr>
       </thead>
       <tbody>
           <? if (!empty($list)) { ?>

               <? foreach ($list AS $item) { ?>
                  <tr class="main-grid-row main-grid-row-body">
                      <?= Admin::td($item['name'], Admin::edit_link($item['id'])); ?>
                      <?= Admin::td(MediaFilter::siteLink($item['code'])); ?>
                      <?= Admin::td(Admin::image_view($item['icon'], $table)); ?>
                      <?= Admin::td($item['sort']); ?>
                      <?= Admin::td($item['active'] == 1 ? 'Да' : 'Нет'); ?>
                      <?= Admin::td($item['id'], Admin::edit_link($item['id'])); ?>
                  </tr>
               <? } ?>

           <? } else { ?>

               <?= Admin::not_found(6); ?>
           
           <? } ?>
           
       </tbody>
   </table>

<?= Admin::total($data) ?>

<? } ?>




<? require_once('footer.php'); ?>