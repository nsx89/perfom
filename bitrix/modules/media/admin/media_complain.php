<? require_once('header.php'); ?>

<?
use Custom\Admin;

$user_id = $USER->GetID();

$title = 'Жалобы';
$APPLICATION->SetTitle($title);

$table = 'm_media_complain';
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
         $APPLICATION->SetTitle($title.': Элемент: Добавление');
      }
      else {
         $APPLICATION->SetTitle($title.': Элемент: '.$row['name'].' - Редактирование');
      }  
      ?>
      
      <?= Admin::back($id) ?>

      <form action='<?= $_SERVER['PHP_SELF'] ?>' method='POST' enctype='multipart/form-data'>
        <div class="adm-detail-tabs-block adm-detail-tabs-block-settings" style="left: 0px;">
            <span class="adm-detail-tab js-adm-detail-tab <?= $tab == 1 ? 'adm-detail-tab-active' : '' ?>" data-id="1">Элемент</span>
         </div>

         <div class="adm-detail-content-wrap">
            <div class="adm-detail-content" id="tab1" style="<?= $tab == 1 ? 'display: block;' : '' ?>">
               <div class="adm-detail-title">Элемент</div>
               <div class="adm-detail-content-item-block" style="height: auto; overflow-y: visible;">
                  <table class="adm-detail-content-table edit-table" id="edit1_edit_table" style="opacity: 1;">
                     <tbody>
                        <?= Admin::info('ID:', $id) ?>
                        <?= Admin::info('Создан:', !empty($row['date']) ? date('d.m.Y H:i:s', $row['date']) : '' ) ?>
                        <?= Admin::input('name', 'Имя:', $row['name'], true) ?>
                        <?= Admin::input('phone', 'Телефон:', $row['phone'], false) ?>
                        <?= Admin::input('email', 'Email:', $row['email'], false) ?>
                        <?= Admin::textarea('text', 'Жалоба:', $row['text'], false) ?>
                        <?= Admin::input('url', 'Ссылка:', $row['url'], false) ?>
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

   $query = "`name` = '". Admin::processing($_POST['name'])."'
      , `phone` = '".Admin::processing($_POST['phone'])."'
      , `email` = '".Admin::processing($_POST['email'])."'
      , `text` = '".Admin::processing_code($_POST['text'])."'
      , `url` = '".Admin::processing_code($_POST['url'])."'
   ";

   if (empty($id)) {
      $DB->Query("INSERT INTO `{$table}` SET
         {$query}
         , `date` = '".time()."'
      ");
      $id = $DB->LastID();
   }
   else {
      $DB->Query("UPDATE `{$table}` SET
         {$query}
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
               <?= Admin::th('Имя'); ?>
               <?= Admin::th('Телефон'); ?>
               <?= Admin::th('Email'); ?>
               <?= Admin::th('Дата'); ?>
               <?= Admin::th('Жалоба'); ?>
               <?= Admin::th('Ссылка'); ?>
               <?= Admin::th('ID'); ?>
           </tr>
       </thead>
       <tbody>
           <? if (!empty($list)) { ?>

               <? foreach ($list AS $item) { ?>
                  <tr class="main-grid-row main-grid-row-body">
                      <?= Admin::td($item['name'], Admin::edit_link($item['id'])); ?>
                      <?= Admin::td($item['phone']); ?>
                      <?= Admin::td($item['email']); ?>
                      <?= Admin::td(date('d.m.Y H:i', $item['date'])); ?>
                      <?= Admin::td(nl2br($item['text'])); ?>
                      <?= Admin::td($item['url']); ?>
                      <?= Admin::td($item['id'], Admin::edit_link($item['id'])); ?>
                  </tr>
               <? } ?>

           <? } else { ?>

               <?= Admin::not_found(7); ?>
           
           <? } ?>
           
       </tbody>
   </table>

<?= Admin::total($data) ?>

<? } ?>




<? require_once('footer.php'); ?>