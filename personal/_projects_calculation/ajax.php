<?php
/**
 * Created by PhpStorm.
 * User: nadida
 * Date: 22.10.2019
 * Time: 16:08
 */
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}
require_once($_SERVER["DOCUMENT_ROOT"] . "/include/catalogue/items.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/personal/projects_calculation/data.php");

$type = $_POST['type'];

if($type == 'change_cornice') {
    $cornice = $_REQUEST['cornice'];

    ob_start();?>

    <? if($cornice!= '') {
        $cornice_arr = get_cornice_corners($cornice);
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
                    <img class = "cloudzoom" src="/personal/projects_calculation/img/corners1/inner/<?=$cornice?>/<?=$corner['title']?>-b.png" alt="" data-cloudzoom = "zoomImage: '/personal/projects_calculation/img/corners1/inner/<?=$cornice?>/<?=$corner['title']?>-b.png',zoomPosition:4,tintOpacity:0,hoverIntentDelay:500">
                    <div class="choose-type-corner" data-type="choose-type-corner"><i class="new-icomoon icon-plus"></i>Выбрать</div>
                    <div class="corner-title">Угол <?=$k+1?></div>
                  </div>
                <? } ?>
                <? //} ?>
            </div>
          </div>
        <? } ?>
        <? if(count($cornice_arr['outer']) != 0) { ?>
          <div class="pc-window-tab-content<?if(count($cornice_arr['inner']) == 0) echo ' active'?>" data-val="outer" data-el="corner-tab-wrap">
            <div class="pc-window-content">
            <? //for($n = 0; $n < 4; $n++) { ?>
                <? foreach($cornice_arr['outer'] as $k => $corner) { ?>
                  <div class="corner-preview" data-type="corner-prew" data-numb="<?=$k?>" data-title="<?=$k+1?>">
                    <img class = "cloudzoom" src="/personal/projects_calculation/img/corners1/outer/<?=$cornice?>/<?=$corner['title']?>-b.png" alt="" data-cloudzoom = "zoomImage: '/personal/projects_calculation/img/corners1/outer/<?=$cornice?>/<?=$corner['title']?>-b.png',zoomPosition:4,tintOpacity:0,hoverIntentDelay:500">
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

    <? $html = ob_get_clean();
    print json_encode($html);
}

if($type == 'calculate') {

    $data = $_REQUEST['arr'];

    $data = json_decode($data);

    $data_arr = Array();
    $data_arr['calculate'] = Array();

    foreach($data as $r => $room) {

        //print_r($room); echo '<br>';

      $room_arr = Array();
      $room_arr['name'] = $room->name;
      $room_arr['cornice_article'] = $room->cornice_article;
      $room_arr['walls'] = Array();

      $cornice_corners = get_cornice_corners($room->cornice_article);

      foreach($room->walls as $w => $wall) {

        $wall_arr = Array();

        $corner_1 = $wall->wall_info->corner_1;
        $corner_2 = $wall->wall_info->corner_2;

        $wall_arr['wall_info'] = Array(
          'corner_1' => Array(
              'length' => $cornice_corners[$corner_1->type][$corner_1->number]['length'],
              'length_top' => $cornice_corners[$corner_1->type][$corner_1->number]['length_top'],
              'type' => $corner_1->type,
              'number' => $corner_1->number,
              'trimming_fit' => $corner_1->trimming_fit,
              'trimming_fit_wall' => $corner_1->trimming_fit_wall
          ),
          'corner_2' => Array(
              'length' => $cornice_corners[$corner_2->type][$corner_2->number]['length'],
              'length_top' => $cornice_corners[$corner_2->type][$corner_2->number]['length_top'],
              'type' => $corner_2->type,
              'number' => $corner_2->number,
              'trimming_fit' => $corner_2->trimming_fit,
              'trimming_fit_wall' => $corner_2->trimming_fit_wall
          ),
          'length' => $wall->wall_info->length
          );


          $room_arr['walls'][$w] = $wall_arr;

      }

      $data_arr['calculate'][$r] = $room_arr;
    }


    print_r($data_arr);
}
