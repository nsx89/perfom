<?
/**
 * Created by PhpStorm.
 * User: nadida
 * Date: 09.10.2019
 * Time: 16:52
 */

$new = true;

if($_GET['create']) $new = false;

$cornice_id = $_GET['cornice'];
if($cornice_id != '') {
    $new = false;
    $res = CIBlockElement::GetList(Array(), Array('IBLOCK_ID' => 12, 'ID' => $cornice_id), false, Array(), Array());
    while ($ob = $res->GetNextElement())
        $current_cornice = array_merge($ob->GetFields(), $ob->GetProperties());
}
//print_r($current_cornice);

$data_arr = array();

if($_GET['arr']) {
    $data_arr['calculate'] = $_GET['arr'];
    $new = false;
} elseif($_GET['proj_numb']){
    $resc = CIBlockElement::GetList(Array(), Array('IBLOCK_CODE'=>'corners','NAME'=>$_GET['proj_numb']));
    if($resc->SelectedRowsCount() > 0) {
        $new = false;
        while($arr = $resc->GetNextElement()) $item = array_merge($arr->GetFields(), $arr->GetProperties());
        $data_arr['calculate'] = json_decode($item['~DETAIL_TEXT'],10);
        $data_arr['info']['number'] = $item['NAME'];
        $data_arr['info']['date'] = $item['DATE_CREATE'];
        $data_arr['info']['user'] = $item['USER_ID']['VALUE'];
    }
}

//print_r($data_arr);

$cornice_arr = Array();
$res = CIBlockElement::GetList(Array(), Array('IBLOCK_ID'=>12, 'PROPERTY_MAURITANIA'=>'Y','SUBSECTION'=>1542), false, Array(), Array());
while($ob = $res->GetNextElement())
{
    $arFields = array_merge($ob->GetFields(), $ob->GetProperties());
    $cornice_arr[] = $arFields;
}
require_once($_SERVER["DOCUMENT_ROOT"] . "/personal/projects_calculation/data.php");
?>
<link rel="stylesheet" href="/personal/projects_calculation/projects_calculation.css?<?=$random?>"/>

<section class="pc-about">
     <div class="left-block">
         <div class="pc-left-block-title">Как действует сервис расчета?</div>
         <div class="pc-rules">
             <div class="pc-rules-item">
                 Для начала нужно произвести замеры стен помещения, где планируется <br>
                 установить элементы декора, и внести полученные результаты <br>
                 в соответствующие графы сервиса.
             </div>
             <div class="pc-rules-item">
                 Затем следует выбрать рисунок стыковки углов или вариант торцовки <br>
                 (при необходимости).
             </div>
             <div class="pc-rules-item">
                 Сервис выполнит расчет после нажатия РАССЧИТАТЬ ПРОЕКТ.
             </div>
             <div class="pc-rules-item">
                 Результаты расчета включают в себя общее количество элементов, <br>
                 схему распила и технические указания по монтажу.
             </div>
             <div class="pc-rules-item">
                 При необходимости проект можно скачать, сохранить в личном кабинете <br>
                 или отправить на email.
             </div>
         </div>
     </div>
    <div class="right-block">
        <div class="top-right-block">
            <?if($data_arr['info']['number']) { ?>
                <div class="pc-project-title">
                  <div class="pc-project-number" data-number="<?=$data_arr['info']['number']?>">Проект №<?=$data_arr['info']['number']?></div>
                    <?
                    $date = '';
                    $time = '';
                    if($data_arr['info']['date']) {
                        $date_arr = explode(' ',$data_arr['info']['date']);
                        $date = $date_arr[0];
                        $time = explode(':',$date_arr['1']);
                        $time = $time[0].':'.$time['1'];
                    }
                    ?>
                    <div class="pc-project-date"><?=$date?> <span><?=$time?></span></div>
                </div>
            <? } ?>
            <?
            $res = CIBlockElement::GetList(Array(), Array('IBLOCK_CODE'=>'corners','PROPERTY_USER_ID'=>$USER->GetID(),"ACTIVE"=>"Y"));
            if($res->SelectedRowsCount() > 0) { ?>
                <div class="select-wrap">
                    <select name="pc-choose" class="pc-choose-project" data-type="pc-choose" data-placeholder="Сохраненные проекты">
                        <option></option>
                        <?
                        while($ob = $res->GetNextElement())
                        { $item = $ob->GetFields(); ?>
                            <option data-type="choose-proj" value="<?=$item['NAME']?>" <?if($item['NAME'] == $data_arr['info']['number']) echo ' class="sel selected"'?>><?=$item['NAME']?></option>
                        <? } ?>
                    </select>
                    <img src="/images/AjaxLoader.gif" alt="Ожидайте" data-type="loader">
                </div>
            <? } ?>
            <form method="get" action="/search" class="pc-search-form">
                <input type="text" name="pc-number" placeholder="Поиск по номеру проекта">
                <button type="button" data-type="pc-search"><i class="new-icomoon icon-search"></i></button>
                <div class="search-preloader"><img src="/images/ajax-loader.gif" alt="preloader"></div>
            </form>
        </div>
        <div class="bottom-right-block">
            <div class="pc-unusuall-wall-btn<?if($new) echo ' no-active'?>" data-type="pc-unusuall"><i class="new-icomoon icon-comment"></i>Сообщить о нетипичной стене</div>
            <div class="pc-unusuall-wall-btn pc-unusuall-clear-btn<?if($new) echo ' no-active'?>" data-type="pc-clear"><i class="new-icomoon icon-garbage"></i>Очистить поля</div>
        </div>

    </div>
</section>

<section class="pc-project">
    <?if($new) { ?>
        <?if($_GET['proj_numb'] && empty($data_arr)) { ?>
            <div class="pc-proj-no-exists">Проект № <?=$_GET['proj_numb']?> не найден в базе</div>
        <? } ?>
        <div class="loader-wrap">
            <div class="pc-create-new-project pc-button" data-type="create-project">Создать новый проект</div>
            <img src="/images/AjaxLoader.gif" alt="Ожидайте" data-type="loader">
        </div>
    <? } else { ?>

  <? if(empty($data_arr)) { ?>
    <div class="pc-room-item" data-type="room-item">

      <div class="pc-room-tab active"><?/* active*/?>
        <div class="pc-room-tab-left no-active"><?/* no-active*/?>
          <input type="text" class="pc-room-title" name="title" value="Помещение 1" data-val="Помещение 1">
          <div class="pc-room-title-btn" data-type="title-save">Сохранить</div>
          <div class="pc-room-title-btn pc-room-title-btn-cancel" data-type="title-cancel">Отменить</div>
        </div>
        <div class="pc-room-tab-right">
          <div class="pc-room-title-change" data-type="change-room-name"><i class="new-icomoon icon-pencil"></i> Переименовать</div>
          <div class="pc-room-title-change" data-type="remove-room"><i class="new-icomoon icon-close" title="Удалить помещение" data-type="remove-room"></i> Удалить</div>
          <i class="new-icomoon icon-Angle-down pc-open-tab" data-type="open-tab"></i><?/* active*/?>
        </div>
          <?/*<div class="pc-room-item-remove"><i class="new-icomoon icon-close" title="Удалить помещение" data-type="remove-room"></i></div>*/?>
      </div>

      <div class="pc-room-content">

        <div class="pc-room-cornice-wrap">
          <div class="pc-room-cornice-title">
            1. Наименование элемента
          </div>
          <div class="pc-room-cornice-desc">Выберите карниз для помещения:</div>
          <div class="pc-cornice-choose">
              <?if($cornice_id != '') { ?>
                <div class="pc-room-cornice-active" data-val="<?=$current_cornice['ARTICUL']['VALUE']?>">
                  <img class = "cloudzoom" src="/cron_responsive/catalog/data/images/100/<?=$current_cornice['ARTICUL']['VALUE']?>.100.png" alt="" data-cloudzoom = "zoomImage: '/personal/projects_calculation/img/cloudzoom/<?=$current_cornice["ARTICUL"]["VALUE"]?>.png',zoomPosition:4,tintOpacity:0,hoverIntentDelay:500">
                  <div class="pc-room-cornice-label">Карниз <?=$current_cornice['ARTICUL']['VALUE']?></div>
                </div>
              <? } ?>
            <div class="pc-room-cornice-choose-btn" data-type="choose-cornice" data-btn="open-pc-window"><?// error?>
              <div class="choose-btn-title">Выбрать <br>другой элемент</div>
              <i class="new-icomoon icon-plus-symbol"></i>
            </div>
          </div>
        </div>

        <div class="pc-room-walls-wrap">
          <div class="pc-room-cornice-title">
            2. Введите размеры стен:
          </div>
          <div class="pc-room-cornice-desc">Последовательно вводите стены помещения. <br>Для каждой стены выберите из предложенных вид левого и правого углов и введите длину стены в мм. <br>Длина стены должна быть целым числом.</div>
          <div class="pc-wall-choose">
            <div class="pc-wall-item">
              <div class="pc-wall-title">Стена № 1 (мм)</div>
              <div class="pc-wall-choose-corner" data-type="left-corner" data-act="add-left">Выбрать угол <i class="new-icomoon icon-plus"></i></div><?// error?>
              <input class="pc-wall-length" data-type="wall-length" title="Длина стены"><?// error?>
              <div class="pc-wall-choose-corner" data-type="right-corner" data-act="add-right" data-val="">Выбрать угол <i class="new-icomoon icon-plus"></i></div>
              <div class="pc-remove-wall" data-type="pc-remove-wall" title="Удалить стену"><i class="new-icomoon icon-close"></i></div>
              <div class="pc-wall-item-err">Длина стены должна быть целым числом</div>
            </div>
            <div class="pc-wall-item no-active" data-type="add-wall"><?// no-active no-clickable?>
              <div class="pc-wall-title">Стена № 2 (мм)</div>
              <div class="pc-wall-choose-corner no-clickable" data-type="left-corner" data-act="add-left" data-val="">Выбрать угол <i class="new-icomoon icon-plus"></i></div><?// error?>
              <input class="pc-wall-length no-clickable" data-type="wall-length" title="Длина стены"><?// error?>
              <div class="pc-wall-choose-corner no-clickable" data-type="right-corner" data-act="add-right" data-val="">Выбрать угол <i class="new-icomoon icon-plus"></i></div>
              <div class="pc-wall-item-err">Длина стены должна быть целым числом</div>
            </div>
          </div>
        </div>

      </div>

    </div>
  <? } else { ?>
    <? foreach($data_arr['calculate'] as $r=>$room) { ?>
      <div class="pc-room-item" data-type="room-item">

        <div class="pc-room-tab<?if($r==0) echo ' active'?>"><?/* active*/?>
          <div class="pc-room-tab-left no-active"><?/* no-active*/?>
            <input type="text" class="pc-room-title" name="title" value="<?=$room['name']?>" data-val="<?=$room['name']?>">
            <div class="pc-room-title-btn" data-type="title-save">Сохранить</div>
            <div class="pc-room-title-btn pc-room-title-btn-cancel" data-type="title-cancel">Отменить</div>
          </div>
          <div class="pc-room-tab-right">
            <div class="pc-room-title-change" data-type="change-room-name"><i class="new-icomoon icon-pencil"></i> Переименовать</div>
            <div class="pc-room-title-change" data-type="remove-room"><i class="new-icomoon icon-close" title="Удалить помещение" data-type="remove-room"></i> Удалить</div>
            <i class="new-icomoon icon-Angle-down pc-open-tab" data-type="open-tab"></i><?/* active*/?>
          </div>
            <?/*<div class="pc-room-item-remove"><i class="new-icomoon icon-close" title="Удалить помещение" data-type="remove-room"></i></div>*/?>
        </div>

        <div class="pc-room-content" <?if($r!=0) echo ' style="display:none;"'?>>

          <div class="pc-room-cornice-wrap">
            <div class="pc-room-cornice-title">
              1. Наименование элемента
            </div>
            <div class="pc-room-cornice-desc">Выберите карниз для помещения:</div>
            <div class="pc-cornice-choose">
                <?
                $res = CIBlockElement::GetList(Array(), Array('IBLOCK_ID' => 12, 'PROPERTY_ARTICUL' => $room['cornice_article']), false, Array(), Array());
                while ($ob = $res->GetNextElement())
                    $current_cornice = array_merge($ob->GetFields(), $ob->GetProperties());
                ?>
                  <div class="pc-room-cornice-active" data-val="<?=$current_cornice['ARTICUL']['VALUE']?>">
                    <img class = "cloudzoom" src="/cron_responsive/catalog/data/images/100/<?=$current_cornice['ARTICUL']['VALUE']?>.100.png" alt="" data-cloudzoom = "zoomImage: '/personal/projects_calculation/img/cloudzoom/<?=$current_cornice["ARTICUL"]["VALUE"]?>.png',zoomPosition:4,tintOpacity:0,hoverIntentDelay:500">
                    <div class="pc-room-cornice-label">Карниз <?=$current_cornice['ARTICUL']['VALUE']?></div>
                  </div>
              <div class="pc-room-cornice-choose-btn" data-type="choose-cornice" data-btn="open-pc-window"><?// error?>
                <div class="choose-btn-title">Выбрать <br>другой элемент</div>
                <i class="new-icomoon icon-plus-symbol"></i>
              </div>
            </div>
          </div>

          <div class="pc-room-walls-wrap">
            <div class="pc-room-cornice-title">
              2. Введите размеры стен:
            </div>
            <div class="pc-room-cornice-desc">Последовательно вводите стены помещения. <br>Для каждой стены выберите из предложенных вид левого и правого углов и введите длину стены в мм. <br>Длина стены должна быть целым числом.</div>
            <div class="pc-wall-choose">
              <? foreach($room['walls'] as $w=>$wall) { ?>
                <div class="pc-wall-item">
                <div class="pc-wall-title">Стена № <?=$w+1?> (мм)</div>
                <div class="pc-wall-choose-corner" data-type="left-corner" data-act="add-left" data-corner-numb="<?=$wall['wall_info']['corner_1']['number']?>" data-corner-type="<?=$wall['wall_info']['corner_1']['type']?>" data-corner-title="<?=$wall['wall_info']['corner_1']['number'] + 1?>">
                  <? if($wall['wall_info']['corner_1']['type'] == 'trimming') {
                    echo 'Торцовка';
                  } else {
                    echo 'Угол '.($wall['wall_info']['corner_1']['number'] + 1);
                  }?>
                  <i class="new-icomoon icon-settings"></i></div>
                <input class="pc-wall-length" data-type="wall-length" title="Длина стены" value="<?=$wall['wall_info']['length']?>"><?// error?>
                  <div class="pc-wall-choose-corner" data-type="right-corner" data-act="add-right" data-corner-numb="<?=$wall['wall_info']['corner_2']['number']?>" data-corner-type="<?=$wall['wall_info']['corner_2']['type']?>" data-corner-title="<?=$wall['wall_info']['corner_2']['number'] + 1?>">
                      <? if($wall['wall_info']['corner_2']['type'] == 'trimming') {
                          echo 'Торцовка';
                      } else {
                          echo 'Угол '.($wall['wall_info']['corner_2']['number'] + 1);
                      }?>
                    <i class="new-icomoon icon-settings"></i></div>
                <div class="pc-remove-wall" data-type="pc-remove-wall" title="Удалить стену"><i class="new-icomoon icon-close"></i></div>
                <div class="pc-wall-item-err">Длина стены должна быть целым числом</div>
              </div>
              <? } ?>
              <div class="pc-wall-item no-active" data-type="add-wall"><?// no-active no-clickable?>
                <div class="pc-wall-title">Стена № 2 (мм)</div>
                <div class="pc-wall-choose-corner no-clickable" data-type="left-corner" data-act="add-left" data-val="">Выбрать угол <i class="new-icomoon icon-plus"></i></div><?// error?>
                <input class="pc-wall-length no-clickable" data-type="wall-length" title="Длина стены"><?// error?>
                <div class="pc-wall-choose-corner no-clickable" data-type="right-corner" data-act="add-right" data-val="">Выбрать угол <i class="new-icomoon icon-plus"></i></div>
                <div class="pc-wall-item-err">Длина стены должна быть целым числом</div>
              </div>
            </div>
          </div>

        </div>

      </div>
    <? } ?>
  <? } ?>


  <div class="pc-add-room" data-type="add-room">Добавить помещение<span></span></div>
   <div class="loader-wrap">
       <div class="pc-calculate-project pc-button" data-type="calculate-project">Расчитать проект</div><?/* no-active*/?>
       <img src="/images/AjaxLoader.gif" alt="Ожидайте" data-type="loader" style="margin-top: 70px;">
   </div>


  <div class="pc-window" data-val="choose-cornice">
    <i class="new-icomoon icon-close" data-type="close-pc-window"></i>
    <div class="pc-window-title">Выберите элемент декора</div>
    <div class="pc-window-content">
        <? foreach($cornice_arr as $item) {
            echo get_product_preview($item,false,false,true);
        }?>
    </div>
  </div>

  <div class="pc-window" data-val="corner">
    <i class="new-icomoon icon-close" data-type="close-pc-window"></i>
    <div class="pc-window-title">Выберите угол</div>
    <div data-type="window-content">
        <? if($cornice_id!= '') {
            $cornice_arr = get_cornice_corners($current_cornice['ARTICUL']['VALUE']);
            ?>
          <div class="pc-window-tabs">
              <? if(count($cornice_arr['inner']) != 0) { ?>
                <div class="pc-window-tabs-item active" data-type="inner" data-el="corner-tab">Внутренний</div>
              <? } ?>
              <? if(count($cornice_arr['outer']) != 0) { ?>
                <div class="pc-window-tabs-item" data-type="outer" data-el="corner-tab">Внешний</div>
              <? } ?>
            <div class="pc-window-tabs-item" data-type="trimming" data-el="corner-tab">Торцовка (без угла)</div>
          </div>
          <div class="pc-window-tabs-wrap">
              <? if(count($cornice_arr['inner']) != 0) { ?>
                <div class="pc-window-tab-content active" data-val="inner" data-el="corner-tab-wrap">
                  <div class="pc-window-content">
                      <? //for($n = 0; $n < 4; $n++) { ?>
                      <? foreach($cornice_arr['inner'] as $k => $corner) { ?>
                        <div class="corner-preview" data-type="corner-prew" data-numb="<?=$k?>" data-title="<?=$k+1?>">
                          <img class = "cloudzoom" src="/personal/projects_calculation/img/corners1/inner/<?=$current_cornice['ARTICUL']['VALUE']?>/<?=$corner['title']?>-b.png" alt="" data-cloudzoom = "zoomImage: '/personal/projects_calculation/img/corners3/inner/<?=$current_cornice['ARTICUL']['VALUE']?>/<?=$corner['title']?>-b.png',zoomPosition:4,tintOpacity:0,hoverIntentDelay:500">
                          <div class="choose-type-corner" data-type="choose-type-corner"><i class="new-icomoon icon-plus"></i>Выбрать</div>
                          <div class="corner-title">Угол <?=$k+1?></div>
                        </div>
                      <? } ?>
                      <? //} ?>
                  </div>
                </div>
              <? } ?>
              <? if(count($cornice_arr['outer']) != 0) { ?>
                <div class="pc-window-tab-content<?if(count($cornice_arr['outer']) == 0) echo ' active'?>" data-val="outer" data-el="corner-tab-wrap">
                  <div class="pc-window-content">
                      <? //for($n = 0; $n < 4; $n++) { ?>
                      <? foreach($cornice_arr['inner'] as $k => $corner) { ?>
                        <div class="corner-preview" data-type="corner-prew" data-numb="<?=$k?>" data-title="<?=$k+1?>">
                          <img class = "cloudzoom" src="/personal/projects_calculation/img/corners1/outer/<?=$current_cornice['ARTICUL']['VALUE']?>/<?=$corner['title']?>-b.png" alt="" data-cloudzoom = "zoomImage: '/personal/projects_calculation/img/corners3/outer/<?=$current_cornice['ARTICUL']['VALUE']?>/<?=$corner['title']?>-b.png',zoomPosition:4,tintOpacity:0,hoverIntentDelay:500">
                          <div class="choose-type-corner" data-type="choose-type-corner"><i class="new-icomoon icon-plus"></i>Выбрать</div>
                          <div class="corner-title">Угол <?=$k+1?></div>
                        </div>
                      <? } ?>
                      <? //} ?>
                  </div>
                </div>
              <? } ?>
            <div class="pc-window-tab-content" data-val="trimming" data-el="corner-tab-wrap">
              <div class="pc-window-content">
                <div class="pc-window-content-description">
                  Торцовка - это способ завершение карниза, когда карниз упирается в следующую стену без образования угла.
                </div>
                <div class="trimming-option-wrap"><?/* active*/?>
                  <div class="trimming-option" data-type="trimming-option" data-val="no">Без подгоночного участка</div>
                  <div class="trimming-option-desc">При монтаже карнизов не будут использоваться подгоночные участки</div>
                </div>
                <div class="trimming-option-wrap"> <?/* no-active*/?>
                  <div class="trimming-option"  data-type="trimming-option" data-val="yes">С подгоночным участком</div>
                  <select name="pc-choose-wall" class="pc-choose-wall" data-type="pc-choose-wall" data-placeholder="Как у стены">
                    <option></option>
                    <option value="0">Стена 1</option>
                    <option value="1">Стена 2</option>
                  </select>
                  <div class="pc-no-choose-wall">Выбор данной опции невозможен, т.к. нет стен, введенных в проект ранее.</div>
                  <div class="trimming-option-desc">При монтаже карнизов возле второго (правого) угла (в случае, если карниз завершается углом, а не торцовкой) будет использован такой же подгоночный участок, как у указанной стены данного помещения. Такая необходимость может возникнуть, если требуется сделать одинаковые углы, например, у противоположных стен. <br>Выбор торцовки с подгоночным участком как у определенной стены возможен только в том случае, если такая стена уже введена в расчет. Если нет, вам потребуется изменить порядок введения стен, начав со стены с углами без торцовки, подгоночный участок которой будет использован в качестве образца.</div>
                </div>
              </div>
            </div>
          </div>
        <? } ?>
    </div>
  </div>

  <div class="pc-alert-window" data-type="alert-window">
    <div class="pc-window-title">Обратите внимание!</div>
    <div class="pc-alert-window-content" data-type="alert-content">Данная стена выбрана вами в качестве подгонки для следующих стен: '+walls+'. <br>Совершаемые вами действия приведут к некорректному расчету. Пожалуйста, сначала измените тип угла для указанных стен, затем повторите операцию.'</div>
    <div class="pc-alert-window-btn" data-type="close-alert">Понятно!</div>
  </div>

    <? } ?>

  <div class="pc-alert-window" data-type="attention-window">
    <div class="pc-window-title">Обратите внимание</div>
    <div class="pc-alert-window-content" data-type="alert-content">Вы точно хотите удалить все данные, внесенные в проект?</div>
    <div class="pc-alert-btns">
      <div class="pc-alert-window-btn" data-type="att-yes">Да</div>
      <div class="pc-alert-window-btn" data-type="att-no">Нет</div>
    </div>
  </div>

  <div class="alert-overlay" data-type="alert-overlay"></div>

</section>

<script src="/personal/projects_calculation/script.js?<?=$random?>"></script>