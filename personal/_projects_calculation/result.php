<?
/**
 * Created by PhpStorm.
 * User: nadida
 * Date: 09.10.2019
 * Time: 16:52
 */
require_once($_SERVER["DOCUMENT_ROOT"] . "/personal/projects_calculation/calculation.php");

$data_arr = array();

if($_GET['arr']) {

  $url = $_SERVER['REQUEST_URI'];
  $url = explode("?", $url);
  $url = $url[count($url)-1];
  $url = explode("&", $url, 2);
  $link_get = '?'.$url[1];
  $data_arr['calculate'] = $_GET['arr'];

} elseif($_GET['proj_numb']) {

    $link_get = '?proj_numb='.$_GET['proj_numb'];
    $resc = CIBlockElement::GetList(Array(), Array('IBLOCK_CODE'=>'corners','NAME'=>$_GET['proj_numb']));
    while($arr = $resc->GetNextElement()) $item = array_merge($arr->GetFields(), $arr->GetProperties());
    $data_arr['calculate'] = json_decode($item['~DETAIL_TEXT'],10);
    $data_arr['info']['number'] = $item['NAME'];
    $data_arr['info']['date'] = $item['DATE_CREATE'];
    $data_arr['info']['user'] = $item['USER_ID']['VALUE'];
}

if($_GET['arr'] && $_GET['proj_numb']) {
    $resc = CIBlockElement::GetList(Array(), Array('IBLOCK_CODE'=>'corners','NAME'=>$_GET['proj_numb']));
    while($arr = $resc->GetNextElement()) $item = array_merge($arr->GetFields(), $arr->GetProperties());
    $data_arr['info']['number'] = $item['NAME'];
    $data_arr['info']['date'] = $item['DATE_CREATE'];
    $data_arr['info']['user'] = $item['USER_ID']['VALUE'];
}

$is_first = false;
foreach($data_arr['calculate'] as $k => $room) {
    if($room['cornice_article'] == '1.50.501') $is_first = true;
    $corners = get_cornice_corners($room['cornice_article']);
    foreach($room['walls'] as $w=>$wall) {
        if($wall['wall_info']['corner_1']['type'] != 'trimming') {
            $data_arr['calculate'][$k]['walls'][$w]['wall_info']['corner_1']['length'] = $corners[$wall['wall_info']['corner_1']['type']][$wall['wall_info']['corner_1']['number']]['length'];
            $data_arr['calculate'][$k]['walls'][$w]['wall_info']['corner_1']['length_top'] = $corners[$wall['wall_info']['corner_1']['type']][$wall['wall_info']['corner_1']['number']]['length_top'];
        } else {
            $data_arr['calculate'][$k]['walls'][$w]['wall_info']['corner_1']['length'] = 0;
        }
        if($wall['wall_info']['corner_2']['type'] != 'trimming') {
            $data_arr['calculate'][$k]['walls'][$w]['wall_info']['corner_2']['length'] = $corners[$wall['wall_info']['corner_2']['type']][$wall['wall_info']['corner_2']['number']]['length'];
            $data_arr['calculate'][$k]['walls'][$w]['wall_info']['corner_2']['length_top'] = $corners[$wall['wall_info']['corner_2']['type']][$wall['wall_info']['corner_2']['number']]['length_top'];
        } else {
            $data_arr['calculate'][$k]['walls'][$w]['wall_info']['corner_2']['length'] = 0;
        }
    }
}
foreach($data_arr['calculate'] as $k => $room) {
    $result = get_calculation_result($data_arr,$room);
    $data_arr['rest'][$room['cornice_article']] = $result['rest'];
    $data_arr['calculate'][$k]['walls'] = $result['walls'];
}
print_r($data_arr);


$picture_link = '/personal/projects_calculation/img/';

require_once($_SERVER["DOCUMENT_ROOT"] . "/personal/projects_calculation/data.php");
?>
<link rel="stylesheet" href="/personal/projects_calculation/projects_calculation.css?<?=$random?>"/>

<section class="pc-about">
     <div class="left-block">
         <div class="pc-left-block-title">Общие правила распила:</div>
         <div class="pc-rules pc-rules-results">
             <div class="pc-rules-item">
               <div class="pc-rules-item-number">1.</div>
               <div class="pc-rules-item-content">Для установки целого количества орнаментов, от заводского карниза следует максимально аккуратно, под 90⁰ к стене и потолку, обрезать припуски на торцовку по линиям, проходящим через край раппорта.</div>
             </div>
             <div class="pc-rules-item">
               <div class="pc-rules-item-number">2.</div>
               <div class="pc-rules-item-content">Не забывайте учитывать толщину пильного диска. Толщина пильного диска должна смещаться от схематичной линии
                 распила в сторону неиспользуемого обрезка.</div>
             </div>
             <div class="pc-rules-item">
               <div class="pc-rules-item-number">3.</div>
               <div class="pc-rules-item-content">Практически все размеры считаются по нижней части карниза.</div>
             </div>
             <div class="pc-rules-item">
               <div class="pc-rules-item-number">4.</div>
               <div class="pc-rules-item-content">Если у вас в проекте есть нетипичные стены, возможен некорректный
                 расчет проекта. В таком случае, пересчитайте проект без нетипичных
                 стен и <span class="pc-rules-item-link" data-type="pc-unusuall">свяжитесь с нашей службой поддержки</span>, чтобы наши специалисты
                 могли сделать индивидуальный расчет для нетипичных стен отдельно.</div>
             </div>
             <div class="pc-rules-item">
               <div class="pc-rules-item-number">5.</div>
               <div class="pc-rules-item-content">Если при монтаже используется остаток от предыдущих карнизов,
                 он вставляется в между целыми краями двух карнизов либо в разрез
                 карниза, сделанный по краю раппорта.</div>
             </div>
           <div class="pc-rules-item">
             <div class="pc-rules-item-number">6.</div>
             <div class="pc-rules-item-content">Для точной подгонки длины изделия между углами помещения <br>с сохранением целостности орнамента требуется использовать участки <br>для подгонки длины. В данном сервисе используются два подгоночных участка, которы располагаются симметрично и ближе к углам помещения. Искажения орнамента, вызванные такой подгонкой, должны восприниматься органично, как усложнение орнаментального <br>решения угла.</div>
           </div>
         </div>
     </div>
    <div class="right-block">
        <div class="top-right-block">
            <div class="pc-project-title" style="<?if(!$_GET['proj_numb']) echo 'display:none;'?>">
              <div class="pc-project-number" data-number="<?if($_GET['proj_numb']) echo $_GET['proj_numb']?>">Проект №<?=$_GET['proj_numb']?></div>
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
        </div>
        <div class="bottom-right-block">
            <a href="/personal/<?=$link_get?>#projects_calculation" class="pc-unusuall-wall-btn" data-type="pc-back"><i class="new-icomoon icon-settings"></i>Вернуться к редактированию</a><?/*  no-active*/?>
        </div>

    </div>
</section>

<section class="pc-project pc-project-result">
  <div class="left-block" data-type="map-notes-wrap">
    <div class="pc-project-notes" data-type="map-notes">
      <div class="pc-project-notes-title">Обозначения <br>разметки на схемах:</div>
      <div class="pc-project-notes-item">
        <div class="pc-project-notes-item-mark montage"></div>
        <div class="pc-project-notes-item-expl">часть карниза, <br>используемая <br>при монтаже</div>
      </div>
      <div class="pc-project-notes-item">
        <div class="pc-project-notes-item-mark line"></div>
        <div class="pc-project-notes-item-expl">линия распила</div>
      </div>
      <div class="pc-project-notes-item">
        <div class="pc-project-notes-item-mark waste"></div>
        <div class="pc-project-notes-item-expl">часть карниза, <br>которая является <br>отходом</div>
      </div>
      <div class="pc-project-notes-item">
        <div class="pc-project-notes-item-mark rapport"></div>
        <div class="pc-project-notes-item-expl">раппорт и его <br>порядковое число</div>
      </div>
      <div class="pc-project-notes-item">
        <div class="pc-project-notes-item-mark rest"></div>
        <div class="pc-project-notes-item-expl">часть карниза <br>с целыми раппортами <br>(остаток)</div>
      </div>
      <? if($is_first) {?>
      <div class="pc-project-notes-item">
        <div class="pc-project-notes-item-mark corner"></div>
        <div class="pc-project-notes-item-expl">угловой и подгоночный <br>участки карниза <br>1.50.501</div>
      </div>
      <? } ?>
        <div class="pc-project-notes-item">
            <div class="pc-project-notes-item-mark rapport cornice"></div>
            <div class="pc-project-notes-item-expl">порядковый номер<br>стены и карниза</div>
        </div>
        <div class="pc-project-notes-item">
            <div class="pc-project-notes-item-mark line cutting"></div>
            <div class="pc-project-notes-item-expl">место подгоночного <br>участка и его <br>порядковый номер</div>
        </div>
    </div>
  </div>
  <div class="right-block">
    <div class="result-summary" data-type="general-info" data-val='<?=json_encode($data_arr)?>'>
      <div class="result-summary-title">Результаты расчета проекта:</div>
      <?
      $result_cornice_arr = array();
      $total_amount = 0;
      foreach($data_arr['calculate'] as $r=>$room) {
        foreach($room['walls'] as $w=>$wall) {
          $total_amount += $wall['calculation']['cornice_qty'];
          $result_cornice_arr[$room['cornice_article']] += $wall['calculation']['cornice_qty'];
        }
          $total_amount++; //запас
          $result_cornice_arr[$room['cornice_article']]++; //запас
      }
      //print_r($result_cornice_arr);
      ?>
      <div class="resul-summary-wrap">
        <div class="result-summary-info">
          <div class="result-summary-amount">
            <div class="result-summary-amount-caption">Всего карнизов</div>
            <div class="result-summary-amount-value"><?=$total_amount?> шт.</div>
          </div>
          <? foreach($result_cornice_arr as $cornice=>$amount) {
            $arFilter = Array('IBLOCK_ID'=>12, 'ACTIVE'=>'Y', 'PROPERTY_ARTICUL'=>$cornice);
            $db_list = CIBlockElement::GetList(Array('NAME'=>'ASC'), $arFilter);
            $product_item = $db_list->GetNextElement();
            $product = array_merge($product_item->GetFields(), $product_item->GetProperties());
            $cornice_link = __get_product_link($product);
          ?>
          <div class="result-summary-cornice">
            <div class="result-summary-cornice-info">
              <a class="result-summary-cornice-info-name" href="<?=$cornice_link?>" target="_blank">Карниз <?=$cornice?></a>
              <div class="result-summary-cornice-info-value"><?=$amount?> шт.</div>
            </div>
            <div class="result-summary-cornice-picture">
              <img src="/personal/projects_calculation/img/cornices/<?=$cornice?>/full.png" alt="Карниз <?=$cornice?>">
            </div>
          </div>
          <? } ?>
        </div>
        <div class="result-summary-btns">
          <div class="result-summary-btns-wrap">
            <div class="result-summary-btn<?if($_GET['proj_numb']) echo ' no-active'?>" data-type="save-proj"><i class="new-icomoon icon-manual"></i>
                Сохранить проект
                <img src="/images/AjaxLoader.gif" alt="Ожидайте" data-type="loader">
            </div>
            <div class="result-summary-btn<?if(!$_GET['proj_numb']) echo ' no-active'?>" data-type="save-changes"><i class="new-icomoon icon-manual"></i> Сохранить изменения</div>
            <div class="result-summary-btn"><i class="new-icomoon icon-mail"></i> Отправить на e-mail</div>
            <div class="result-summary-btn"><i class="new-icomoon icon-down-arr-select"></i> Скачать .pdf</div>
          </div>
        </div>
      </div>

    </div>

    <div class="result-rooms">

      <? foreach($data_arr['calculate'] as $r=>$room) { ?>

          <? $cornice_info = get_cornice_params($room['cornice_article']);?>

        <div class="pc-room-item" data-type="room-item">

          <div class="pc-room-tab<?if($r == 0) echo ' active'?>">
            <div class="pc-room-tab-left no-active">
              <input type="text" class="pc-room-title" name="title" value="<?=$room['name']?>" data-val="<?=$room['name']?>">
            </div>
            <div class="pc-room-tab-right">
              <i class="new-icomoon icon-Angle-down pc-open-tab" data-type="open-tab"></i><?/* active*/?>
            </div>
          </div>

          <div class="pc-room-content" style="display:<?=($r == 0) ? ' block' : 'none'?>;">

            <div class="result-room-summary">
              <div class="result-room-summary-info">
                <div class="result-room-summary-info-title">
                  <div class="result-room-summary-info-title-name" data-val="<?=$room['cornice_article']?>">Карниз <?=$room['cornice_article']?></div>
                  <?
                  $room_total_amount = 0;
                  $room_total_waste = 0;
                  $room_total_rest = 0;
                  foreach($room['walls'] as $wall) {
                      $room_total_amount += $wall['calculation']['cornice_qty'];
                      foreach($wall['calculation']['cutting'] as $cornice) {
                        foreach($cornice as $cut) {
                          if($cut['type'] == 'rest') {
                              $room_total_rest += $cut['end'] - $cut['start'] + 1;
                          }
                          if($cut['type'] == 'waste') {
                              $room_total_waste += ($cut['end'] - $cut['start'] + 1) * $cornice_info['rapport_length'];
                          }
                          if($cut['type'] == 'edge cutting' || $cut['type'] == 'corner_waste') {
                              $room_total_waste += $cut['end'] - $cut['start'];
                          }
                        }
                      }
                  }
                  ?>
                  <div class="result-room-summary-info-title-value"><?=$room_total_amount?> + 1 (запас)</div>
                </div>
                <table class="result-room-summary-info-table">
                  <? foreach($room['walls'] as $w=>$wall) {?>
                    <tr>
                      <td>Стена № <?=++$w?></td>
                      <td><?=$wall['wall_info']['length']?> мм</td>
                    </tr>
                  <? } ?>
                </table>
                <table class="result-room-summary-info-add">
                  <?/*<tr>
                    <td>Всего отходов</td>
                    <td><?=$room_total_waste?> мм</td>
                  </tr>*/?>
                  <tr>
                    <td>Всего остатков</td>
                    <td><?=$room_total_rest?> <?=getNumEnding($room_total_rest, array('раппорт', 'раппорта', 'раппортов'))?></td>
                  </tr>
                </table>
              </div>
              <div class="result-room-summary-rapport">
                <div class="result-room-summary-rapport-name">Вид раппорта карниза:</div>
                <div class="result-room-summary-rapport-img">
                  <img src="/personal/projects_calculation/img/cornices/<?=$room['cornice_article']?>/rapport-view.png" alt="">
                </div>
                <div class="result-room-summary-rapport-expl">
                  Раппорт – базовый элемент орнамента, часть узора,<br>
                  повторяющаяся многократно.
                </div>
              </div>
            </div>
              <? foreach($room['walls'] as $w=>$wall) {?>
            <div class="result-room-wall">
              <div class="result-room-wall-title">Стена № <?=++$w?></div>
              <div class="result-room-wall-info">
                <div class="result-room-wall-info-item" data-type="length" data-val="<?=$wall['wall_info']['length']?>">Длина стены: <?=$wall['wall_info']['length']?> мм</div>
                <? if(isset($wall['calculation']['error'])) { ?>
                  <div class="result-room-wall-info-item-error">
                    <div>Внимание!</div>
                      <?=$wall['calculation']['error']?>
                  </div>
                <? } else { ?>
                <div class="result-room-wall-info-item">Количество карнизов: <?=$wall['calculation']['cornice_qty']?></div>
                <? } ?>
              </div>
              <div class="result-room-wall-corners">
                <div class="result-room-wall-corners-item" data-type="corner_1">
                  <div class="result-room-wall-corners-item-title">Угол № 1</div>
                  <div class="result-room-wall-corners-item-wrap">
                      <? if($wall['wall_info']['corner_1']['type']!='trimming') { ?>
                    <div class="result-room-wall-corners-item-picture">
                      <img class = "cloudzoom" src="/personal/projects_calculation/img/corners1/<?=$wall['wall_info']['corner_1']['type']?>/<?=$room['cornice_article']?>/<?=$wall['wall_info']['corner_1']['number']+1?>.png" alt="Угол № 1" data-cloudzoom = "zoomImage: '/personal/projects_calculation/img/corners1/<?=$wall['wall_info']['corner_1']['type']?>/<?=$room['cornice_article']?>/<?=$wall['wall_info']['corner_1']['number']+1?>-b.png',zoomPosition:4,tintOpacity:0,hoverIntentDelay:500">
                    </div>
                    <? } ?>
                    <? $corner = get_corner_mounting_description($wall['wall_info']['corner_1']['type']);?>
                    <div class="result-room-wall-corners-item-desc">
                      <div class="result-room-wall-corner-name" data-type="corner-info" type="<?=$wall['wall_info']['corner_1']['type']?>" number="<?=$wall['wall_info']['corner_1']['number']?>" <?if($wall['wall_info']['corner_1']['type'] == 'trimming') echo 'trimming_fit="'.$wall['wall_info']['corner_1']['trimming_fit'].'" trimming_fit_wall="'.$wall['wall_info']['corner_1']['trimming_fit_wall'].'"'?>><span>Тип угла: </span>
                        <? if($wall['wall_info']['corner_1']['type'] == 'trimming') { ?>
                            Торцовка <?=$wall['wall_info']['corner_1']['trimming_fit'] == 'yes' ? '(Как у стены № '.($wall['wall_info']['corner_1']['trimming_fit_wall']+1).')' : '';?>
                        <? } else { ?>
                        Угол <?=($wall['wall_info']['corner_1']['number']+1)?> (<?=$corner['name']?>)
                        <? } ?>
                      </div>
                      <div class="result-room-wall-corner-subj">Монтаж и обрезка угла</div>
                      <div class="result-room-wall-corner-text"><?=$corner['text']?></div>
                    </div>
                  </div>
                </div>
                <div class="result-room-wall-corners-item" data-type="corner_2">
                  <div class="result-room-wall-corners-item-title">Угол № 2</div>
                  <div class="result-room-wall-corners-item-wrap">
                    <? if($wall['wall_info']['corner_2']['type']!='trimming') { ?>
                    <div class="result-room-wall-corners-item-picture">
                      <img class = "cloudzoom" src="/personal/projects_calculation/img/corners1/<?=$wall['wall_info']['corner_2']['type']?>/<?=$room['cornice_article']?>/<?=$wall['wall_info']['corner_2']['number']+1?>.png" alt="Угол № 2" data-cloudzoom = "zoomImage: '/personal/projects_calculation/img/corners1/<?=$wall['wall_info']['corner_2']['type']?>/<?=$room['cornice_article']?>/<?=$wall['wall_info']['corner_2']['number']+1?>-b.png',zoomPosition:4,tintOpacity:0,hoverIntentDelay:500">
                    </div>
                    <? } ?>
                      <? $corner = get_corner_mounting_description($wall['wall_info']['corner_2']['type']);?>
                    <div class="result-room-wall-corners-item-desc">
                      <div class="result-room-wall-corner-name" data-type="corner-info" type="<?=$wall['wall_info']['corner_2']['type']?>" number="<?=$wall['wall_info']['corner_2']['number']?>" <?if($wall['wall_info']['corner_2']['type'] == 'trimming') echo 'trimming_fit="'.$wall['wall_info']['corner_2']['trimming_fit'].'" trimming_fit_wall="'.$wall['wall_info']['corner_2']['trimming_fit_wall'].'"'?>><span>Тип угла: </span>
                          <? if($wall['wall_info']['corner_2']['type'] == 'trimming') { ?>
                            Торцовка <?=$wall['wall_info']['corner_2']['trimming_fit'] == 'yes' ? '(как у стены № '.($wall['wall_info']['corner_2']['trimming_fit_wall']+1).')' : '';?>
                          <? } else { ?>
                            Угол <?=($wall['wall_info']['corner_2']['number']+1)?> (<?=$corner['name']?>)
                          <? } ?>
                      </div>
                      <div class="result-room-wall-corner-subj">Монтаж и обрезка угла</div>
                      <div class="result-room-wall-corner-text"><?=$corner['text']?></div>
                    </div>
                  </div>
                </div>
              </div>
              <?
              $cutting = 0;
              $max_cut = false;
              if($room['cornice_article'] == '1.50.501') {
                  $cutting_max = 0;
                  $n = 0;
                  foreach($wall['calculation']['cutting'] as $cornice) {
                      foreach($cornice as $cut) {
                          if($cut['type'] == 'corner'){ //501
                              //$cutting += $cut['end'] - $cut['start'] - $cornice_info['rapport_length'];
                              //$cutting_max += $cornice_info['max_cutting'];
                              $n++;
                          }
                          if($cut['type'] == 'cutting waste'){ //501
                              $cutting += $cut['end'] - $cut['start'];
                              $cutting_max += $cornice_info['max_cutting'];
                          }
                      }
                  }
                  if($cutting_max == $cutting && $cutting != 0) $max_cut = true;
                  $cutting = ($cutting_max - $cutting)/$n;
              } else {
                  foreach($wall['calculation']['cutting'] as $cornice) {
                      foreach($cornice as $cut) {
                          if($cut['type'] == 'edge cutting') {
                              $cutting = $cut['end'] - $cut['start'];
                              break;
                          }
                      }
                  }
              }

              if($cutting != 0 || $max_cut) { ?>
                <div class="result-wall-cutting">
                  <div class="result-room-wall-corners-item-title">Вид подгоночного участка</div>
                  <div class="result-wall-cutting-wrap">
                      <?
                      $half_cutting = $cutting/2;
                      $cut_data = get_cutting_params($room['cornice_article']);
                      $shift = round($half_cutting*$cut_data['length_px']/$cut_data['length_mm']);
                      ?>
                    <div class="left-part-cutting">
                      <img src="<?=$picture_link?>cornices/<?=$room['cornice_article']?>/cutting-l.png" alt="Вид подгоночного участка" style="right:-<?=$shift?>px">
                    </div>
                    <div class="right-part-cutting">
                      <img src="<?=$picture_link?>cornices/<?=$room['cornice_article']?>/cutting-r.png" alt="Вид подгоночного участка" style="left:-<?=$shift?>px">
                    </div>
                  </div>
                </div>
              <? } ?>
              <div class="result-wall-scheme">
                <?
                  $empty_start = '';
                  $empty_end = '';
                  $general_arr = array();
                  $general_arr['common_length'] = 0;
                  foreach($wall['calculation']['cutting'] as $c=>$cornice) {
                  if(empty($cornice)) {
                      $general_arr[] = array('name'=>$c+1, 'type'=>'cornice', 'length'=>$cornice_info['rapport_qty']*$cornice_info['rapport_length']);
                      $general_arr['common_length'] += $cornice_info['rapport_qty']*$cornice_info['rapport_length'];
                  }
                  if(!empty($cornice)) {
                    if($empty_start != '' ) { ?>
                <div class="result-wall-scheme-cornice">
                    <div class="result-wall-scheme-title">
                      Параметры и схемы распила <br>Карниз <?=$empty_start?><?=$empty_end != '' ? ' - '.$empty_end : ''?>
                    </div>
                      <div class="cornice-scheme-title">
                        Целый карниз
                      </div>
                      <div class="scheme-wrapper">
                        <div class="cornice-wrapper">
                          <?
                          $rapport_width = 100/$cornice_info['rapport_qty'];
                          for($i = 0; $i < $cornice_info['rapport_qty']; $i++) {?>
                            <div class="rapport-block" style="left: <?=$i*$rapport_width?>%; width: <?=$rapport_width?>%;"></div>
                          <? } ?>
                        </div>
                      </div>
                      <div class="scheme-picture">
                        <img src="/personal/projects_calculation/img/cornices/<?=$room['cornice_article']?>/full.png" alt="">
                      </div>
                </div>
                <?
                        $empty_start = '';
                        $empty_end = '';
                    }
                ?>
                  <div class="result-wall-scheme-cornice">
                    <div class="result-wall-scheme-title">
                      Параметры и схемы распила <br>Карниз <?=$c+1?>
                    </div>
                    <table class="cornice-scheme">
                      <tr>
                        <th>№ карниза</th>
                        <th>№ вырезки</th>
                        <th>Тип вырезки</th>
                        <th>Действие</th>
                        <th>Координаты (от - до)*, мм</th>
                        <th>Длина вырезки*, мм</th>
                      </tr>
                      <?
                      $expl = '';

                      $g = count($general_arr);
                      $general_arr[$g]['name'] = $c+1;
                      $general_arr[$g]['type'] = 'cornice';
                      $general_arr[$g]['length'] = $cornice_info['rapport_qty']*$cornice_info['rapport_length'];
                      $general_arr['common_length'] += $cornice_info['rapport_qty']*$cornice_info['rapport_length'];

                      foreach($cornice as $u=>$cut) {
                          $type = '';
                          switch($cut['type']) {
                              case 'corner waste':
                                  $type = 'Отходы от обрезки угла';
                                  $action = 'Выбросить';
                                  break;
                              case 'waste':
                                  $type = 'Отходы от обрезки остатков**';
                                  $action = 'Выбросить';
                                  $expl = '** - данные отходы образуются из-за смещения толщины пильного диска от края реза в сторону крайнего раппорта из остатков, <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;из-за чего данный раппорт становится непригодным к использованию';
                                  break;
                              case 'edge cutting':
                              case 'center cutting':
                              case 'trimming cutting':
                              case 'cutting waste':
                                  $type = 'Отходы от подгонки';
                                  $action = 'Выбросить';
                                  break;
                              case 'rest':
                                  $type = 'Остаток - целые раппорты';
                                  $action = 'Сохранить';
                                  break;
                              case 'corner':
                                  $type = 'Угловой участок';
                                  $action = 'Отрезать по линии пила и оставить';
                                  break;
                              case 'cutting':
                                  $type = 'Подгоночный участок';
                                  $action = 'Отрезать по линии пила и оставить';
                                  break;
                          }
                      ?>
                      <tr>
                        <? if($u == 0) { ?>
                          <td rowspan="<?=count($cornice)?>"><?=$w?>-<?=$c+1?></td>
                        <? } ?>
                        <td><?=$w?>-<?=$c+1?>.<?=++$u?></td>
                        <td><?=$type?></td>
                        <td><?=$action?></td>
                          <? if($cut['type'] == 'rest' || $cut['type'] == 'waste') { ?>
                            <td>весь раппорт</td>
                            <td><?=($cut['end'] - $cut['start'] + 1)*$cornice_info['rapport_length']?></td>
                            <?
                              $general_arr[$g]['length'] -= ($cut['end'] - $cut['start'] + 1)*$cornice_info['rapport_length'];
                              $general_arr['common_length'] -= ($cut['end'] - $cut['start'] + 1)*$cornice_info['rapport_length'];
                            ?>
                          <? } else { ?>
                              <? if($cut['type'] == 'corner' || $cut['type'] == 'cutting' ) { ?>
                                  <? if($cut['start'] < 0) { ?>
                                      <td>
                                          <?=$cornice_info['max_cutting']+$cut['start']?> - <?=$cornice_info['max_cutting']?> (заводской край) <br>
                                        0 - <?=$cut['end']?> (первый раппорт)
                                      </td>
                                  <? } else { ?>
                                      <td>
                                        0 - <?=$cornice_info['rapport_length']?> (последний раппорт) <br>
                                        0 - <?=$cut['end'] - $cornice_info['rapport_length']?> (заводской край)
                                      </td>
                                  <? } ?>
                              <? } elseif($cut['type'] == 'cutting waste' && $cut['rapport'] == 0 || $cut['type'] == 'cutting waste' && $cut['rapport'] > $cornice_info['rapport_qty']) { ?>
                                  <? if($cut['start'] < 0) { ?>
                                    <td>
                                        0 - <?=$cut['end'] - $cut['start']?> (заводской край)
                                    </td>
                                      <? } else { ?>
                                    <td>
                                      <?=$cut['start'] - $cornice_info['rapport_length']?> - <?=$cornice_info['max_cutting']?> (заводской край)
                                    </td>
                                  <? } ?>
                              <? } elseif($cut['type'] == 'cutting') { ?>

                              <? } else { ?>
                              <td><?=$cut['start']?> - <?=$cut['end']?></td>
                              <? } ?>
                            <td><?=$cut['end'] - $cut['start'];?></td>
                              <?
                                  $general_arr[$g]['length'] -= $cut['end'] - $cut['start'];
                                  $general_arr['common_length'] -= $cut['end'] - $cut['start'];
                                  if($cut['type'] == 'edge cutting' || $cut['type'] == 'center cutting') {
                                      $general_arr[$g+1]['name'] = ($c+1).'.'.$u;
                                      $general_arr[$g+1]['type'] = 'cutting';
                                      $general_arr[$g+1]['length'] = (($cut['rapport'] - 1)*$cornice_info['rapport_length'] + $cut['start'])/($cornice_info['rapport_length']*$cornice_info['rapport_qty']);
                                      //$general_arr[$g+1]['length'] = (($cut['rapport'] - 1)*$cornice_info['rapport_length'] + $cut['start']);
                                  } elseif ($cut['type'] == 'corner' && $room['cornice_article'] == '1.50.501') {
                                      $corn_n = $cut['rapport'] == 1 ? 1 : 2;
                                      $general_arr['corners'][$corn_n]['name'] = ($c+1).'.'.$u;
                                      $general_arr['corners'][$corn_n]['type'] = 'corner';
                                      $general_arr['corners'][$corn_n]['length'] = $cut['end'] - $cut['start'];
                                      $general_arr['common_length'] += $cut['end'] - $cut['start'];
                                  } elseif ($cut['type'] == 'cutting' && $room['cornice_article'] == '1.50.501') {
                                      if($c == 1 && $cut['rapport'] == 1 || $c == 2 && $cut['rapport'] == $cornice_info['rapport_qty']) {
                                          $side = 'left';
                                      } else {
                                          $side = 'right';
                                      }
                                      $corn_n = $c == 1 ? 1 : 2;
                                      $general_arr['cutting'][$side][$corn_n]['name'] = ($c+1).'.'.$u;
                                      $general_arr['cutting'][$side][$corn_n]['type'] = 'corner';
                                      $general_arr['cutting'][$side][$corn_n]['length'] = $cut['end'] - $cut['start'];
                                      $general_arr['common_length'] += $cut['end'] - $cut['start'];
                                  }
                              ?>
                          <? } ?>
                      </tr>
                      <? } ?>
                    </table>

                    <div class="cornice-scheme-explanation">
                      *&nbsp;&nbsp;&nbsp;- без учета толщины пильного диска
                      <? if($expl != '') echo '<br>'.$expl;?>
                    </div>
                    <div class="cornice-scheme-title">
                      Стена № <?=$w?>, схема распила карниза № <?=$c+1?>
                    </div>
                    <div class="scheme-wrapper">
                      <?
                      $number = count($cornice)*2 - 1;
                      $cornice_length = $cornice_info['rapport_qty']*$cornice_info['rapport_length'];
                      if($cornice[0]['start'] < 0 && $room['cornice_article'] == '1.50.501') $cornice_length += $cornice_info['max_cutting'];
                      if($cornice[count($cornice)-1]['end'] > $cornice_info['rapport_length'] && $room['cornice_article'] == '1.50.501') $cornice_length += $cornice_info['max_cutting'];
                      if($cornice[0]['use_edge'] == 1) $cornice_length += $cornice_info['edge'];
                      if($cornice[count($cornice)-1]['use_edge'] == 1) $cornice_length += $cornice_info['edge'];
                      //print_r($number);
                      ?>
                      <div class="cornice-ruler">

                        <?
                        $height = 10;
                        $prev_length = 0;
                        foreach($cornice as $u => $cut) { ?>

                            <? if($cut['rapport'] != 1 && $cut['type'] != 'cutting waste' && $cut['use_edge'] != 1 || $cut['rapport'] == 1 && $cut['start'] != 0 && $cut['type'] != 'waste' && $cut['type'] != 'rest' || $cut['type'] == 'waste' && $cut['start'] != 1 || $cut['type'] == 'rest' && $cut['start'] != 1 || $cut['type'] == 'cutting waste' && $cut['start'] == 0 || $cut['use_edge'] == 1 && $cut['start'] != 0) {
                              if($cut['type'] == 'waste' || $cut['type'] == 'rest' ) {
                                  $item_start_length = ($cut['start'] - 1)*$cornice_info['rapport_length'];
                              } elseif($cut['type'] == 'cutting waste') {
                                 $item_start_length = $cut['end'] - $cut['start'];
                              } else {
                                 $item_start_length = ($cut['rapport'] - 1)*$cornice_info['rapport_length'] + $cut['start'];
                              }
                              //if($cut['use_edge'] == 1)
                              if($cornice[0]['start'] < 0 && $room['cornice_article'] == '1.50.501') $item_start_length += $cornice_info['max_cutting'];
                              if($cornice[0]['use_edge'] == 1) $item_start_length += $cornice_info['edge'];
                                $width = $item_start_length / $cornice_length * 100;
                                if($item_start_length != $prev_length) {
                                    $height += 25;
                                    $prev_length = $item_start_length;
                                    ?>
                                  <div class="cornice-ruler-item" style="order:<?=$number--?>; width:<?=$width?>%; height:<?=$height?>px;">
                                    <div class="cornice-ruler-item-length"><?=$item_start_length?></div>
                                    <i class="new-icomoon icon-Angle-left"></i>
                                    <i class="new-icomoon icon-Angle-right"></i>
                                  </div>
                                <? }

                             }?>

                            <? if($cut['rapport'] != $cornice_info['rapport_qty'] && $cut['type'] != 'cutting waste' && $cut['use_edge'] != 1 || $cut['rapport'] == $cornice_info['rapport_qty'] && $cut['end'] != $cornice_info['rapport_length'] && $cut['type'] != 'waste' && $cut['type'] != 'rest' || $cut['end'] != $cornice_info['rapport_qty'] && $cut['type'] == 'waste' || $cut['end'] != $cornice_info['rapport_qty'] && $cut['type'] == 'rest' || $cut['type'] == 'cutting waste' && $cut['rapport'] == $cornice_info['rapport_qty']+1 || $cut['use_edge'] == 1 && $cut['end'] != $cornice_info['edge']) {
                                if($cut['type'] == 'waste' || $cut['type'] == 'rest') {
                                    $item_end_length = ($cut['end']) * $cornice_info['rapport_length'];
                                } elseif($cut['type'] == 'cutting waste') {
                                    $item_end_length = ($cut['rapport'] - 2)*$cornice_info['rapport_length'] + $cut['start'];
                                } else {
                                    $item_end_length = ($cut['rapport'] - 1)*$cornice_info['rapport_length'] + $cut['end'];
                                }
                                if($cut['use_edge'] == 1) {
                                    $item_end_length = -$cornice_info['edge'] + $cut['end'];
                                }
                                if($cornice[0]['start'] < 0 && $room['cornice_article'] == '1.50.501') $item_end_length += $cornice_info['max_cutting'];
                                if($cornice[0]['use_edge'] == 1) $item_end_length += $cornice_info['edge'];
                                $width = $item_end_length / $cornice_length * 100;
                                if($item_end_length != $prev_length && $item_end_length != $cornice_length) {
                                    $height += 25;
                                    $prev_length = $item_end_length;
                                    ?>
                                  <div class="cornice-ruler-item" style="order:<?=$number--?>; width:<?=$width?>%; height:<?=$height?>px;">
                                    <div class="cornice-ruler-item-length"><?=$item_end_length?></div>
                                    <i class="new-icomoon icon-Angle-left"></i>
                                    <i class="new-icomoon icon-Angle-right"></i>
                                  </div>
                                <? }
                             }?>

                        <? } ?>

                        <div class="cornice-ruler-item" style="order:0; width:100%;height:100%;">
                          <div class="cornice-ruler-item-length"><?=$cornice_length?></div>
                          <i class="new-icomoon icon-Angle-left"></i>
                          <i class="new-icomoon icon-Angle-right"></i>
                        </div>

                        <div style="height:<?=$height+25?>px;"></div>

                      </div>
                      <div class="cornice-wrapper">
                        <?
                        $show_edge = '';
                        $step = 0;
                        $step_perc = 0;
                        //для карниза 501
                        //если надо показывать 2 заводских края
                        if($room['cornice_article'] == '1.50.501' && $c == 0 && $cornice[0]['type'] == 'corner' && $cornice[count($cornice)-1]['type'] == 'corner' && count($cornice) > 1 ||  $c == 1 && $room['cornice_article'] == '1.50.501' && $cornice[0]['type'] == 'cutting waste' && $cornice[count($cornice)-1]['type'] == 'cutting waste'  && count($cornice) > 1  || $c == 1 && $room['cornice_article'] == '1.50.501' && $cornice[0]['type'] == 'cutting' && $cornice[count($cornice)-1]['type'] == 'cutting' && count($cornice) > 1 || $c == 2 && $room['cornice_article'] == '1.50.501' && $cornice[0]['type'] == 'cutting waste' && $cornice[count($cornice)-1]['type'] == 'cutting waste'  && count($cornice) > 1  || $c == 2 && $room['cornice_article'] == '1.50.501' && $cornice[0]['type'] == 'cutting' && $cornice[count($cornice)-1]['type'] == 'cutting' && count($cornice) > 1 ) {
                          $show_edge = '-edge';
                          $step = $cornice_info['max_cutting'];
                          $rapport_width = $cornice_info['rapport_length']*100/($cornice_info['rapport_length']*$cornice_info['rapport_qty']+$step*2);
                          $step_perc = $step*100/($cornice_info['rapport_length']*$cornice_info['rapport_qty']+$step*2);
                          //если 1 заводской край слева
                        } elseif($room['cornice_article'] == '1.50.501' && $c == 0 && $cornice[0]['type'] == 'corner' && count($cornice) > 1 || $c == 1 && $room['cornice_article'] == '1.50.501' && $cornice[0]['type'] == 'cutting waste' && count($cornice) > 1 || $c == 1 && $room['cornice_article'] == '1.50.501' && $cornice[0]['type'] == 'cutting' && count($cornice) > 1) {
                          $show_edge = '-left';
                          $step = $cornice_info['max_cutting'];
                          $rapport_width = $cornice_info['rapport_length']*100/($cornice_info['rapport_length']*$cornice_info['rapport_qty']+$step);
                          $step_perc = $step*100/($cornice_info['rapport_length']*$cornice_info['rapport_qty']+$step);
                          //если 1 заводской край справа
                        } elseif($room['cornice_article'] == '1.50.501' && $c == 0 && $cornice[0]['type'] == 'corner' || $c == 1 && $room['cornice_article'] == '1.50.501' && $cornice[count($cornice)-1]['type'] == 'cutting waste' || $c == 1 && $room['cornice_article'] == '1.50.501' && $cornice[count($cornice)-1]['type'] == 'cutting') {
                          $show_edge = '-right';
                          $step = $cornice_info['edge'];
                          $rapport_width = $cornice_info['rapport_length']*100/($cornice_info['rapport_length']*$cornice_info['rapport_qty']+$step);
                          //$step_perc = $step*100/($cornice_info['rapport_length']*$cornice_info['rapport_qty']+$step);
                            //для карниза 524
                            //если надо показывать 2 заводских края
                        } elseif($cornice[0]['use_edge'] == 1 && $cornice[count($cornice)-1]['use_edge'] == 1) {
                            $show_edge = '-edge';
                            $step = $cornice_info['edge'];
                            $rapport_width = $cornice_info['rapport_length']*100/($cornice_info['rapport_length']*$cornice_info['rapport_qty']+$step*2);
                            $step_perc = $step*100/($cornice_info['rapport_length']*$cornice_info['rapport_qty']+$step*2);
                            //если 1 заводской край слева
                        } elseif($cornice[0]['use_edge'] == 1) {
                            $show_edge = '-left';
                            $step = $cornice_info['edge'];
                            $rapport_width = $cornice_info['rapport_length']*100/($cornice_info['rapport_length']*$cornice_info['rapport_qty']+$step);
                            $step_perc = $step*100/($cornice_info['rapport_length']*$cornice_info['rapport_qty']+$step);
                            //если 1 заводской край справа
                        } elseif($cornice[count($cornice)-1]['use_edge'] == 1) {
                            $show_edge = '-right';
                            $step = $cornice_info['edge'];
                            $rapport_width = $cornice_info['rapport_length']*100/($cornice_info['rapport_length']*$cornice_info['rapport_qty']+$step);
                            //$step_perc = $step*100/($cornice_info['rapport_length']*$cornice_info['rapport_qty']+$step);
                        }
                        else {
                            $rapport_width = 100/$cornice_info['rapport_qty'];
                        }
                        for($r = 0; $r < $cornice_info['rapport_qty']; $r++) {
                        $class = '';
                        ?>
                            <? if($r == 0 && $show_edge == '-edge' || $r == 0 && $show_edge == '-left') { ?>
                              <div class="rapport-block" style="left: <?=0?>%; width: <?=$step_perc?>%; z-index:1"></div>
                            <? } ?>
                            <div class="rapport-block" style="left: <?=$step_perc+$r*$rapport_width?>%; width: <?=$rapport_width?>%;">
                                <? foreach ($cornice as $u => $cut) {
                                    $cut_number = '';
                                    //print_r($r);
                                  if($cut['type'] == 'edge cutting' && $cut['rapport'] == $r + 1 || $cut['type'] == 'corner waste' && $cut['rapport'] == $r + 1 || $cut['type'] == 'rest' && $cut['start'] == $r + 1 || $cut['type'] == 'waste' && $cut['start'] == $r + 1 || $cut['type'] == 'corner' && $cut['rapport'] == $r + 1 || $cut['type'] == 'cutting waste' && $cut['rapport'] == $r || $cut['type'] == 'cutting waste' && $cut['rapport'] == $r+1 || $cut['type'] == 'cutting waste' && $cut['rapport'] == $r+1 || $cut['type'] == 'cutting' && $cut['rapport'] == $r + 1 || $cut['type'] == 'trimming cutting' && $cut['rapport'] == $r + 1 || $cut['type'] == 'cutting waste' && $cut['rapport'] == $r+2 || $cut['type'] == 'corner waste' && $cut['use_edge'] == 1 && $r == 0 && $cut['rapport'] == 0 || $cut['type'] == 'corner waste' && $cut['use_edge'] == 1 && $r == $cornice_info['rapport_qty'] - 1 && $cut['rapport'] == $cornice_info['rapport_qty']+1) {
                                    $cut_number = ++$u;
                                    if($cut['type'] == 'edge cutting' || $cut['type'] == 'corner waste' || $cut['type'] == 'trimming cutting') {
                                      $class = ' waste';
                                      $left = $cut['start']/$cornice_info['rapport_length']*100;
                                      $width = ($cut['end'] - $cut['start'])/$cornice_info['rapport_length']*100;
                                    }
                                    if($cut['type'] == 'rest') {
                                        $class = ' rest';
                                        $left = 0;
                                        $width = ($cut['end'] - $cut['start'] + 1)*100;
                                    }
                                    if($cut['type'] == 'waste') {
                                        $class = ' waste';
                                        $left = 0;
                                        $width = ($cut['end'] - $cut['start'] + 1)*100;
                                    }
                                    if($cut['type'] == 'corner') {
                                        $class = ' corner';
                                        $left = $cut['start']/$cornice_info['rapport_length']*100;
                                        $width = ($cut['end'] - $cut['start'])/$cornice_info['rapport_length']*100;
                                    }
                                    if($cut['type'] == 'cutting') {
                                        $class = ' corner';
                                        $left = $cut['start']/$cornice_info['rapport_length']*100;
                                        $width = ($cut['end'] - $cut['start'])/$cornice_info['rapport_length']*100;
                                    }
                                      if($cut['type'] == 'cutting waste') {
                                          $class = ' waste';
                                          $left = $cut['start']/$cornice_info['rapport_length']*100;
                                          $width = ($cut['end'] - $cut['start'])/$cornice_info['rapport_length']*100;
                                      }
                                      if($cut['use_edge'] == 1 && $cut['rapport'] == 0) {
                                          $left = -$cornice_info['edge']/$cornice_info['rapport_length']*100;
                                      }
                                      if($cut['use_edge'] == 1 && $cut['rapport'] == 0) {
                                          $left = -$cornice_info['edge']/$cornice_info['rapport_length']*100;
                                      }
                                      if($cut['use_edge'] == 1 && $cut['rapport'] == $cornice_info['rapport_qty']+1) {
                                          $left = ($cornice_info['rapport_length'] + $cut['start'])/$cornice_info['rapport_length']*100;
                                      }
                                    if($width < 0) $width *= -1;
                                  }
                                if($cut_number != '') {
                                    ?>
                                  <div class="rapport-cut<?=$class?>" style="left:<?=$left?>%;width:<?=$width?>%;">
                                    <span class="rapport-cut-number"><?=$w?>-<?=$c+1?>.<?=$cut_number?></span>
                                  </div>
                                <? } } ?>
                              <div class="rapport-number"><?=$r + 1;?></div>
                            </div>
                          <? } ?>
                          <? if($r == $cornice_info['rapport_qty'] && $show_edge == '-edge' || $r == $cornice_info['rapport_qty'] && $show_edge == '-right') { ?>
                            <div class="rapport-block" style="right: <?=0?>%; width: <?=$step_perc?>%; left:unset; z-index:1"></div>
                          <? } ?>
                      </div>
                    </div>
                    <div class="scheme-picture">
                      <img src="/personal/projects_calculation/img/cornices/<?=$room['cornice_article']?>/full<?=$show_edge?>.png" alt="">
                        <?
                        //$rapport_width = 100/$cornice_info['rapport_qty'];
                        for($r = 0; $r < $cornice_info['rapport_qty']; $r++) { ?>
                          <div class="rapport-block-img" style="left: <?=$step_perc+$r*$rapport_width?>%; width: <?=$rapport_width?>%;">
                              <? foreach ($cornice as $u => $cut) {
                                  $cut_number = '';
                                  if($cut['type'] == 'edge cutting' && $cut['rapport'] == $r + 1 || $cut['type'] == 'corner waste' && $cut['rapport'] == $r + 1 || $cut['type'] == 'rest' && $cut['start'] == $r + 1 || $cut['type'] == 'waste' && $cut['start'] == $r + 1 || $cut['type'] == 'cutting waste' && $cut['rapport'] == $r || $cut['type'] == 'cutting waste' && $cut['rapport'] == $r+2 || $cut['type'] == 'trimming cutting' && $cut['rapport'] == $r + 1 || $cut['type'] == 'corner waste' && $cut['use_edge'] == 1 && $r == 0 && $cut['rapport'] == 0 || $cut['type'] == 'corner waste' && $cut['use_edge'] == 1 && $r == $cornice_info['rapport_qty'] - 1 && $cut['rapport'] == $cornice_info['rapport_qty']+1) {
                                      $cut_number = ++$u;
                                      if($cut['type'] == 'edge cutting' || $cut['type'] == 'corner waste' || $cut['type'] == 'trimming cutting') {
                                          $left = $cut['start']/$cornice_info['rapport_length']*100;
                                          $width = ($cut['end'] - $cut['start'])/$cornice_info['rapport_length']*100;
                                      }
                                      if($cut['type'] == 'rest') {
                                          $left = 0;
                                          $width = ($cut['end'] - $cut['start'] +1)*100;
                                      }
                                      if($cut['type'] == 'waste') {
                                          $left = 0;
                                          $width = ($cut['end'] - $cut['start'] +1)*100;
                                      }
                                      if($cut['type'] == 'cutting waste') {
                                          $left = $cut['start']/$cornice_info['rapport_length']*100;
                                          $width = ($cut['end'] - $cut['start'])/$cornice_info['rapport_length']*100;
                                      }
                                      if($cut['use_edge'] == 1 && $cut['rapport'] == 0) {
                                          $left = -$cornice_info['edge']/$cornice_info['rapport_length']*100;
                                      }
                                      if($cut['use_edge'] == 1 && $cut['rapport'] == $cornice_info['rapport_qty']+1) {
                                          $left = ($cornice_info['rapport_length'] + $cut['start'])/$cornice_info['rapport_length']*100;
                                      }
                                      if($width < 0) $width *= -1;
                                  }
                                  if($cut_number != '') {
                                      ?>
                                    <div class="rapport-cut-img" style="left:<?=$left?>%;width:<?=$width?>%;"></div>
                                  <? } } ?>
                          </div>
                        <? } ?>
                    </div>
                  </div>
                <? } else {
                    if($empty_start == '') {
                        $empty_start = ++$c;
                    } else {
                        $empty_end = ++$c;
                    }
                    if($c == count($wall['calculation']['cutting'])) {?>
                        <div class="result-wall-scheme-cornice">
                          <div class="result-wall-scheme-title">
                            Параметры и схемы распила <br>Карниз <?=$empty_start?><?=$empty_end != '' ? ' - '.$empty_end : ''?>
                          </div>
                          <div class="cornice-scheme-title">
                            Целый карниз
                          </div>
                          <div class="scheme-wrapper">
                            <div class="cornice-wrapper">
                                <?
                                $rapport_width = 100/$cornice_info['rapport_qty'];
                                for($i = 0; $i < $cornice_info['rapport_qty']; $i++) {?>
                                  <div class="rapport-block" style="left: <?=$i*$rapport_width?>%; width: <?=$rapport_width?>%;"></div>
                                <? } ?>
                            </div>
                          </div>
                          <div class="scheme-picture">
                            <img src="/personal/projects_calculation/img/cornices/<?=$room['cornice_article']?>/full.png" alt="">
                          </div>
                        </div>
                        <?
                        $empty_start = '';
                        $empty_end = '';
                      ?>
                    <?}
                  }
                } ?>
                <?
                //остатки
                 $general_arr_rest = array();

                if(!empty($wall['calculation']['using_rest'])) { ?>

                 <? foreach($wall['calculation']['using_rest'] as $r => $rest) { ?>
                <?
                $cut_number = 0;
                $last_rapport = $rest['rapport'] - $rest['waste'];

                $g = count($general_arr);
                $general_arr_rest[$g]['name'] = '1-o';
                $general_arr_rest[$g]['type'] = 'rest';
                $general_arr_rest[$g]['length'] = ($rest['rapport'] - $rest['waste'])*$cornice_info['rapport_length'];
                $general_arr['common_length'] += ($rest['rapport'] - $rest['waste'])*$cornice_info['rapport_length'];
                ?>
                <div class="result-wall-scheme-cornice">
                  <div class="result-wall-scheme-title">
                    Параметры и схемы распила <br>остатков
                  </div>
                  <table class="cornice-scheme">
                    <tr>
                      <th>№ вырезки</th>
                      <th>Тип вырезки</th>
                      <th>Действие</th>
                      <th>Координаты (от - до)*</th>
                      <th>Длина вырезки*</th>
                    </tr>
                    <tr>
                      <td><?=++$cut_number?>-o</td>
                      <td>Остаток из предыдущих распилов <br> (комната <?=$rest['room']?>, стена <?=$rest['wall']?>, карниз № <?=$rest['cornice']?>)</td>
                      <td>Вставить</td>
                      <? if($rest['waste'] > 0) { ?>
                        <td>0 - <?=($rest['rapport'] - $rest['waste'])*$cornice_info['rapport_length']?></td>
                        <td><?=($rest['rapport'] - $rest['waste'])*$cornice_info['rapport_length']?></td>
                      <? } else { ?>
                        <td colspan="2">Целый отрезок</td>
                      <? }?>
                    </tr>
                      <? if($rest['waste'] > 0) { ?>
                        <tr>
                          <td><?=++$cut_number?>-o</td>
                          <td>Отходы от остатков</td>
                          <td>Выбросить</td>
                          <td><?=($rest['rapport'] - $rest['waste'])*$cornice_info['rapport_length']?> - <?=$rest['rapport']*$cornice_info['rapport_length']?></td>
                          <td><?=$rest['waste']*$cornice_info['rapport_length']?></td>
                        </tr>
                      <? } ?>
                  </table>
                  <div class="cornice-scheme-title">
                    <? $waste_name = $rest['waste'] > 0 ? 'схема распила остатка' : 'целый остаток'?>
                    Стена № <?=$w?>, <?=$waste_name?> (комната <?=$rest['room']?>, стена <?=$rest['wall']?>, карниз № <?=$rest['cornice']?>)
                  </div>
                  <div class="scheme-wrapper">
                    <? if($rest['waste'] > 0) {
                      $height = 10; ?>
                    <div class="cornice-ruler">
                      <?
                      $item_start_length = ($rest['rapport'] - $rest['waste'])*$cornice_info['rapport_length'];
                      $width = $item_start_length / ($cornice_info['rapport_length']*$cornice_info['rapport_qty']) * 100;
                      $height += 25;
                      ?>
                      <div class="cornice-ruler-item" style="order:1; width:<?=$width?>%; height:<?=$height?>px;">
                        <div class="cornice-ruler-item-length"><?=$item_start_length?></div>
                        <i class="new-icomoon icon-Angle-left"></i>
                        <i class="new-icomoon icon-Angle-right"></i>
                      </div>
                      <?
                      $item_start_length = $rest['rapport']*$cornice_info['rapport_length'];
                      $width = $item_start_length / ($cornice_info['rapport_length']*$cornice_info['rapport_qty']) * 100;
                      ?>
                      <div class="cornice-ruler-item" style="order:0; width:<?=$width?>%;height:100%;">
                        <div class="cornice-ruler-item-length"><?=$item_start_length?></div>
                        <i class="new-icomoon icon-Angle-left"></i>
                        <i class="new-icomoon icon-Angle-right"></i>
                      </div>

                      <div style="height:<?=$height+25?>px;"></div>

                    </div>
                    <? } ?>
                    <div class="cornice-wrapper" style="width:<?=$rest['rapport']/$cornice_info['rapport_qty']*100?>%">
                        <?
                        $rest_rapport_width = 100/$rest['rapport'];
                        for($i = 0; $i < $rest['rapport']; $i++) {?>
                          <div class="rapport-block" style="left: <?=$i*$rest_rapport_width?>%; width: <?=$rest_rapport_width?>%;">
                              <? if($i==0) { ?>
                                <div class="rapport-cut" style="left: 0;width:<?=($rest['rapport']-$rest['waste'])*100?>%;">
                                  <span class="rapport-cut-number"><?=$w?>-1-o</span>
                                </div>
                              <? } ?>
                            <? if($rest['waste'] > 0 && $i==$rest['rapport']-1) { ?>
                              <div class="rapport-cut waste" style="left: unset; right:0;width:<?=$rest['waste']*100?>%;">
                                <span class="rapport-cut-number"><?=$w?>-2-o</span>
                              </div>
                           <? } ?>
                            <div class="rapport-number"><?=$i + 1;?></div>
                          </div>
                        <? } ?>
                    </div>
                  </div>
                  <div class="scheme-picture" style="width:<?=$rest['rapport']/$cornice_info['rapport_qty']*100?>%">
                      <img src="/personal/projects_calculation/img/cornices/<?=$room['cornice_article']?>/rapport.png" alt="" style="width: <?=$rest_rapport_width;?>%; visibility:hidden;">
                    <? for($i = 0; $i < $rest['rapport']; $i++) {?>
                      <div class="rapport-block-img" style="left: <?=$i*$rest_rapport_width?>%; width: <?=$rest_rapport_width?>%;min-height:100%;height:auto;">
                        <img src="/personal/projects_calculation/img/cornices/<?=$room['cornice_article']?>/rapport.png" alt="" style="max-width:100%;">
                          <? if($rest['waste'] > 0 && $i==$rest['rapport']-1) { ?>
                            <div class="rapport-cut-img" style="left:unset;right:0;top:0;width:<?=$rest['waste']*100?>%;"></div>
                          <? } ?>
                      </div>
                    <? } ?>
                  </div>
                </div>

                 <? } ?>
                <? } ?>

                  <? ksort($general_arr) ?>
                <? //print_r($general_arr) ?>
                <div class="general-scheme">
                  <div class="result-wall-scheme-title">Общий вид раскладки на стене № <?=$w?></div>
                  <div class="cornice-wrapper cornice-wrapper-general">
                    <?
                        $left = 0;
                        $cutting_arr = array();

                        if($room['cornice_article'] == '1.50.501') {
                            //print_r()
                            if(!empty($general_arr['corners'][1])) {
                                $width = $general_arr['corners'][1]['length']/$general_arr['common_length']*100;
                                ?>
                                <div class="rapport-block" style="left: <?=$left?>%; width: <?=$width?>%;">
                                    <div class="rapport-number" style="right: 100%;left: unset;transform: none;"><?=$w?>-<?=$general_arr['corners'][1]['name']?></div>
                                </div>
                            <? $left +=$width; }
                            if(!empty($general_arr['cutting']['left'])) {
                                $cutting_arr[0] = '';
                                foreach($general_arr['cutting']['left'] as $li=>$left_item) {
                                    $width = $left_item['length']/$general_arr['common_length']*100;
                                    ?>
                                    <div class="rapport-block" style="left: <?=$left?>%; width: <?=$width?>%;">
                                        <div class="rapport-number<?if(count($general_arr['cutting']['left'])>1 && $li & 1) echo ' top'?>" style="<?if($li == count($general_arr['cutting']['left'])) echo 'left: 100%;transform: none;'?>"><?=$w?>-<?=$left_item['name']?></div>
                                    </div>
                                    <?
                                    $left +=$width;
                                    $cutting_arr[0] .= $left_item['name'].' - ';
                                }
                                $cutting_arr[0] = substr($cutting_arr[0], 0, -3);
                            }
                        }

                        foreach ($general_arr as $i=>$item) {
                            if($item['type'] == 'cornice') {
                                if(!empty($general_arr_rest) && $i == count($general_arr) - 2 && $general_arr[count($general_arr) - 2]['type'] == 'cornice' && $general_arr[count($general_arr) - 1]['type'] == 'cutting' || !empty($general_arr_rest) && $i == count($general_arr) - 1 && $general_arr[count($general_arr) - 1]['type'] == 'cornice') {
                                    foreach($general_arr_rest as $r => $item_rest) {
                                        $width = $item_rest['length']/$general_arr['common_length']*100; ?>
                                        <div class="rapport-block rest" style="left: <?=$left?>%; width: <?=$width?>%;">
                                            <div class="rapport-number"><?=$w?>-<?=$item_rest['name']?></div>
                                        </div>
                                    <? $left +=$width; }
                                }
                                $width = $item['length']/$general_arr['common_length']*100;
                                ?>
                                <div class="rapport-block" style="left: <?=$left?>%; width: <?=$width?>%;">
                                    <div class="rapport-number"><?=$w?>-<?=$item['name']?></div>
                                </div>
                                <?
                                $left +=$width; } elseif($item['type'] == 'cutting') {
                                $cutting_arr[] = $item['name'];
                                $cutting_left = $left-$general_arr[$i-1]['length']*(1 - $item['length'])/$general_arr['common_length']*100;
                                ?>
                                <div class="rapport-block-cutting" style="left: <?=$cutting_left?>%;">
                                    <div class="rapport-number"><?=$w?>-<?=$item['name']?></div>
                                </div>
                            <? }
                            }?>

                     <? if($room['cornice_article'] == '1.50.501') {
                      if(!empty($general_arr['cutting']['right'])) {
                          $cutting_arr[1] = '';
                          foreach($general_arr['cutting']['right'] as $li=>$right_item) {
                              $width = $right_item['length']/$general_arr['common_length']*100;
                              ?>
                              <div class="rapport-block" style="left: <?=$left?>%; width: <?=$width?>%;">
                                  <div class="rapport-number<?if(count($general_arr['cutting']['right'])>1 && $li & 2) echo ' top'?>" style="<?if($li & 1) echo 'right: 100%;left: unset;transform: none;'?>"><?=$w?>-<?=$right_item['name']?></div>
                              </div>
                              <?
                              $left +=$width;
                              $cutting_arr[1] .= $right_item['name'].' - ';
                          }
                          $cutting_arr[1] = substr($cutting_arr[1], 0, -3);
                      }
                         if(!empty($general_arr['corners'][2])) {
                             $width = $general_arr['corners'][2]['length']/$general_arr['common_length']*100;
                             ?>
                             <div class="rapport-block" style="left: <?=$left?>%; width: <?=$width?>%;">
                                 <div class="rapport-number" style="left: 100%;transform: none;"><?=$w?>-<?=$general_arr['corners'][2]['name']?></div>
                             </div>
                             <? $left +=$width; }
                      }?>
                  </div>
                    <? if(!empty($cutting_arr)) { ?>
                        <div class="result-wall-cutting">
                            <?
                            $cutting_name = '';
                            foreach($cutting_arr as $cutting_item) {
                                $cutting_name = $cutting_name.$cutting_item.' и ';
                            }
                            $cutting_name = substr($cutting_name, 0, -3); ?>
                            <div class="result-room-wall-corners-item-title">Вид подгоночного участка (<?=$cutting_name?>)</div>
                            <div class="result-wall-cutting-wrap">
                                <?
                                $half_cutting = $cutting/2;
                                $cut_data = get_cutting_params($room['cornice_article']);
                                $shift = round($half_cutting*$cut_data['length_px']/$cut_data['length_mm']);
                                ?>
                                <div class="left-part-cutting">
                                    <img src="<?=$picture_link?>cornices/<?=$room['cornice_article']?>/cutting-l.png" alt="Вид подгоночного участка" style="right:-<?=$shift?>px">
                                </div>
                                <div class="right-part-cutting">
                                    <img src="<?=$picture_link?>cornices/<?=$room['cornice_article']?>/cutting-r.png" alt="Вид подгоночного участка" style="left:-<?=$shift?>px">
                                </div>
                            </div>
                        </div>
                    <? } ?>

                </div>

              </div>
            </div>
            <? }?>

          </div>

        </div>

      <? } ?>


    </div>
  </div>
</section>

<script src="/personal/projects_calculation/script.js?<?=$random?>"></script>