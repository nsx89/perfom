<? 
global $USER;
global $APPLICATION;

if (empty($detail)) {
    require_once("404.php"); exit;
}

use Media\Media;

$item = Media::info($detail);
if (empty($item)) {
    require_once("404.php"); exit;
}

if (empty($item['active']) && !$USER->IsAdmin()) {
    //require_once("404.php"); exit;
}

$item_id = $item['id']; 

$siteCategory = Media::siteCategory($item);
$links = Media::links($item_id);
$constructor = Media::constructor($item_id);

$itemLink = Media::siteLink($item);

$actions = '<section class="m-media-detail-box m-media-detail-actions">
    <div class="m-media-detail-icons">
      <span class="m-media-item-icon m-media-item-icon-fav '.(Media::likes($item_id) ? 'm-media-item-icon-fav-active' : '').'" data-id="'.$item_id.'" title="Лайк">'.Media::siteFav($item).'</span>
      <span class="m-media-item-icon m-media-item-icon-flag '.(Media::flag($item_id) ? 'm-media-item-icon-flag-active' : '').'" data-id="'.$item_id.'" title="В закладки"></span>
      '.Media::share($itemLink).'
    </div>
    <div class="m-media-detail-actions-right">
      <div class="m-media-detail-views" title="Просмотров">'.$item['views'].'</div>
      <div class="m-media-detail-complain js-media-detail-complain" data-type="complain-popup-open">Пожаловаться</div>
    </div>
</section>';
$actions_show = false;
$other_show = false;

Media::views_add($item_id); //просмотры

//Хлебные крошки
$breads = array(); 
$breads[] = array('name' => 'Главная', 'link' => '/');
if (!empty($category)) {
  $breads[] = array('name' => MEDIA_NAME, 'link' => MEDIA_FOLDER.'/');
  if (!empty($category_info)) {
      $breads[] = array('name' => $category_info['name'], 'link' => MEDIA_FOLDER.'/'.$category_info['code'].'/');
  }
}
$breads[] = array('name' => $item['name']);
?>

<div class="m-media-content">

  <?=  Media::breads($breads); ?>

  <div class="m-media-detail" itemscope itemtype="http://schema.org/Article">

    <? if ($USER->IsAdmin()) { ?>
        <div class='m-edits-wrap'>
          <div class='m-edits'>
            <a class='m-edit' target='_blank' href='/bitrix/admin/media_pages.php?lang=ru&edit=<?= $item_id ?>'>Редактировать</a>
          </div>
        </div>
    <? } ?>

    <section class="m-media-detail-box">
      <div class="m-media-detail-head">
        <div class="m-media-detail-row">
          <? if (!empty($siteCategory)) { ?>
              <a class="m-media-detail-cat" href="<?= MEDIA_FOLDER.'/'.$siteCategory['code'].'/' ?>" ><?= $siteCategory['name'] ?></a>
          <? } ?>
          <? if (!empty($item['date'])) { ?>
              <div class="m-media-detail-date">
                  <span><?= date('d.m', $item['date']) ?></span>
              </div>
          <? } ?>
        </div>
        <h1 itemprop="headline" class="m-media-detail-name">
            <?= $item['name'] ?>
        </h1>
        <? if (!empty($item['short'])) { ?>
            <div class="m-media-detail-short" itemprop="description">
                <?= $item['short'] ?>
            </div>
        <? } ?>
        <div style="display: none;">
            <span itemprop="datePublished"><?= date('Y-m-d', $item['date']) ?></span>
            <span itemprop="dateModified"><?= date('Y-m-d', $item['date_edit']) ?></span>
            <span itemprop="author">Европласт</span>
            <div itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
                <div itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
                  <img src="https://evroplast.ru/img/e-logo.png" alt="Европласт" itemprop="contentUrl" />
                </div>
                <meta itemprop="name" content="<?= $_SERVER['SERVER_NAME'] ?>">
                <span itemprop="telephone">+7 495 315 30 40</span>
                <div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
                  Адрес:
                  <span itemprop="streetAddress">1-й Дорожный проезд, д. 6, стр. 4</span>
                  <span itemprop="postalCode"> 117545</span>
                  <span itemprop="addressLocality">Москва, Россия</span>,
                </div>
            </div>
        </div>
      </div>
    </section>

    <? if (!empty($item['detail_picture'])) { ?>
        <section class="m-media-detail-img m-media-detail-img-full">
            <?= Media::siteDetailPicture($item) ?>
        </section>
    <? } ?>

    <? if (!empty($item['detail_picture_info'])) { ?>
        <section class="m-media-detail-box m-media-detail-img-info">
            <?= $item['detail_picture_info'] ?>
        </section>
    <? } ?>

    <? if (!empty($links)) { ?>
        <section class="m-media-detail-box m-media-detail-box-menu">
          <div class="m-media-detail-menu">
              <? foreach ($links AS $link) { ?>
                  <div class="m-media-detail-menu-link-wrap">
                    <div class="m-media-detail-menu-link" data-link="link<?= $link['block_id'] ?>">
                      <?= $link['name'] ?>
                    </div>
                  </div>
              <? } ?>
          </div>
        </section>
    <? } ?>

    <? foreach ($constructor AS $box) { ?>

      <? 
      $type_id = $box['type_id'];
      $box_id = $box['id'];

      //Заголовок
      if (!empty($box['head']) && $type_id <> 11) { ?>
          <section class="m-media-detail-box m-media-detail-text">
              <h2><?= $box['head'] ?></h2>
          </section>
      <? } ?>

      <?
      switch ($type_id) {
          case 1: 
              echo '<section class="m-media-detail-box m-media-detail-foot" id="link'.$box_id.'">
                <div class="m-media-author-wrap">
                  <div class="m-media-author-photo m-cover" style="background: url('.MEDIA_FOLDER.'/img/detail/author.jpg) no-repeat center center;"></div>
                  <div class="m-media-author-info">
                    <div class="m-media-author-info-name">Иван Сидоров</div>
                    <div class="m-media-author-info-short">Главный редактор</div>
                  </div>
                </div>
              </section>';
              break;
          case 3: case 5: //видео
              if (!empty($box['video'])) {
                  $video = MEDIA_FOLDER.'/files/m_media_constructor/'.$box['video'];
                  echo '<section class="m-media-video-wrap '.($type_id == 3 ? 'm-media-wrapper' : 'm-media-detail-box').'" id="link'.$box_id.'">
                    <div class="m-media-video" data-video="'.$video.'">
                        <div class="m-media-video-img m-cover"></div>
                    </div>
                  </section>';
              }
              break;
          case 2: case 4: //изображения
              if (!empty($box['image'])) {
                  $alt = (!empty($box['short']) ? $box['short'] : 'Фото');
                  if (!empty($box['image_alt'])) $alt = $box['image_alt'];
                  echo '<section class="'.($type_id == 2 ? 'm-media-detail-box-full' : 'm-media-detail-box').'" id="link'.$box_id.'">
                    <div class="m-media-detail-img  '.($type_id == 2 ? 'm-media-detail-img-full' : 'm-media-detail-img-small').'">
                      <img src="'.MEDIA_FOLDER.'/upload/m_media_constructor/'.$box['image'].'" alt="'.$alt.'">
                    </div>
                  </section>';
              }
              if (!empty($box['short'])) {
                  echo '<section class="m-media-detail-box m-media-detail-img-info">
                      '.$box['short'].'
                  </section>';
              }
              break;
          case 6: 
              if (!empty($box['image']) || !empty($box['image2'])) {
                  echo '<section class="m-media-detail-box m-media-detail-text" id="link'.$box_id.'">
                    <div class="m-media-detail-photo-items">';
                        if (!empty($box['image'])) {
                            $alt = 'Фото 1';
                            if (!empty($box['image_alt'])) $alt = $box['image_alt'];
                            $image = MEDIA_FOLDER.'/upload/m_media_constructor/'.$box['image'];
                            echo '<div class="m-media-detail-photo-item cover">
                              <img src="'.$image.'" alt="'.$alt.'">
                            </div>';
                        }
                        if (!empty($box['image2'])) {
                            $alt = 'Фото 2';
                            if (!empty($box['image2_alt'])) $alt = $box['image2_alt'];
                            $image = MEDIA_FOLDER.'/upload/m_media_constructor/'.$box['image2'];
                            echo '<div class="m-media-detail-photo-item cover">
                              <img src="'.$image.'" alt="'.$alt.'">
                            </div>';
                        }
                    echo ' </div>
                  </section>';
              }
              break;
          case 7: //Изображения с маркерами
              $image = '';
              if (!empty($box['image'])) $image = MEDIA_FOLDER.'/upload/m_media_constructor/'.$box['image'];
              $image2 = '';
              if (!empty($box['image2'])) $image2 = MEDIA_FOLDER.'/upload/m_media_constructor/'.$box['image2'];
              $image3 = '';
              if (!empty($box['image3'])) $image3 = MEDIA_FOLDER.'/upload/m_media_constructor/'.$box['image3'];
              $image4 = '';
              if (!empty($box['image4'])) $image4 = MEDIA_FOLDER.'/upload/m_media_constructor/'.$box['image4'];
              $alt = 'Фото 1';
              if (!empty($box['image_alt'])) $alt = $box['image_alt'];
              $alt2 = 'Фото 2';
              if (!empty($box['image2_alt'])) $alt2 = $box['image2_alt'];
              $alt3 = 'Фото 3';
              if (!empty($box['image3_alt'])) $alt3 = $box['image3_alt'];
              $alt4 = 'Фото 4';
              if (!empty($box['image4_alt'])) $alt4 = $box['image4_alt'];
              echo '<section class="m-media-wrapper" id="link'.$box_id.'">
                  <div class="m-media-gallery">';
                      if (!empty($image)) {
                          echo '<div class="m-media-gallery-item m-media-gallery-item-big">
                            <img src="'.$image.'" alt="'.$alt.'" />
                            '.Media::markers($item_id, $box_id, 1).'
                          </div>';
                      }
                      echo '<div class="m-media-gallery-items">';
                          if (!empty($image2)) {
                              echo '<div class="m-media-gallery-item m-media-gallery-item-small">
                                <img src="'.$image2.'" alt="'.$alt2.'" />
                                '.Media::markers($item_id, $box_id, 2).'
                              </div>';
                          }
                          if (!empty($image3)) {
                              echo '<div class="m-media-gallery-item m-media-gallery-item-small">
                                <img src="'.$image3.'" alt="'.$alt3.'" />
                                '.Media::markers($item_id, $box_id, 3).'
                              </div>';
                          }
                          if (!empty($image4)) {
                              echo '<div class="m-media-gallery-item m-media-gallery-item-small m-media-gallery-item-hide">
                                <img src="'.$image4.'" alt="'.$alt4.'" />
                                '.Media::markers($item_id, $box_id, 4).'
                              </div>';
                          }
                      echo '</div>
                      <div class="m-mobile-gallery-show-more">Показать еще</div>
                  </div>
              </section>';
              break;
          case 8: //цитата
              if (!empty($box['name']) && !empty($box['text'])) {
                  $photo = '';
                  if (!empty($box['image'])) $photo = MEDIA_FOLDER.'/upload/m_media_constructor/'.$box['image'];
                  $photo_img = '';
                  if (!empty($photo)) $photo_img = '<div class="m-media-quot-photo m-cover" style="background: url('.$photo.') no-repeat center center;"></div>';
                  echo '<section class="m-media-detail-box" id="link'.$box_id.'">
                    <div class="m-media-quot">
                      <div class="m-media-quot-text">
                          '.$box['text'].'
                      </div>
                      <div class="m-media-quot-wrap">
                        '.$photo_img.'
                        <div class="m-media-quot-info">
                          <div class="m-media-quot-info-name">'.$box['name'].'</div>
                          <div class="m-media-quot-info-short">'.$box['short'].'</div>
                        </div>
                      </div>
                    </div>
                  </section>';
              }
              break;
          case 9: //врезка продуктов
              if (!empty($box['ids'])) {
                  $ids = explode(',', $box['ids']);
                  $IDS = [];
                  foreach ($ids AS $product_id) {
                      $IDS[] = trim($product_id);
                  }
                  if (!empty($IDS)) {
                      echo '<section class="m-media-products m-media-products-loading" data-type="media-slider" id="link'.$box_id.'">';
                          $arFilter = Array('IBLOCK_ID'=>IB_CATALOGUE, 'ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y', 'ID'=>$IDS);
                          $db_list = CIBlockElement::GetList(Array(), $arFilter);
                          while($product_item = $db_list->GetNextElement()) {
                              $product_item = array_merge($product_item->GetFields(), $product_item->GetProperties());
                              echo get_product_preview($product_item);
                          }
                      echo '</section>';
                  }
              }
              break;
          case 10: 
              if (!empty($box['text'])) {
                  echo '<section class="m-media-detail-back" id="link'.$box_id.'">
                    <div class="m-media-detail-box">
                      <div class="m-media-detail-back-text">
                        '.$box['text'].'
                      </div>
                    </div>
                  </section>';
              }
              break;
          case 11: //рекомендации публикации
              if (!empty($box['multi_ids'])) {
                  $ids = explode('|', trim($box['multi_ids'], '|'));
                  if (!empty($ids)) {
                      $items = Media::siteItems($ids);
                      if (!empty($items)) {
                          echo '<div class="m-media-other-wrap" id="link'.$box_id.'">
                              <section class="m-media-other">
                                <div class="m-media-center">';
                                    if (!empty($box['head'])) echo '<h2 class="m-media-other-head">'.$box['head'].'</h2>';
                                    echo '<div class="m-media-items">
                                      '.Media::siteItems($ids).'
                                    </div>
                                </div>
                              </section>
                          </div>';
                      }
                      $other_show = true;
                  }
              }
              break;
          case 12: //Pinterest
              if (!empty($box['name'])) {
                  //https://developers.pinterest.com/docs/add-ons/profile-widget/
                  echo '<section class="m-media-detail-widget m-media-wrapper" id="link'.$box_id.'">
                    <a href="'.$box['name'].'" data-pin-do="embedUser"
                      data-pin-board-width="900px"
                      data-pin-scale-height="700px"
                      data-pin-scale-width="200px">
                    </a>
                  </section>';
              }
              break;
          case 13: //опрос
              $quiz_list = Media::quiz($item_id, $box['id']);
              if (!empty($quiz_list)) {
                echo '<section class="m-media-detail-box" id="link'.$box_id.'">';
                  foreach ($quiz_list AS $quiz) {
                      echo '<div class="m-media-quiz">
                        <div class="m-media-quiz-question">
                          '.$quiz['question']['name'].'
                        </div>
                        <div class="m-media-quiz-answers">';
                        $session_id = bitrix_sessid();
                        foreach ($quiz['answers'] AS $answer) {

                            $question_id = $quiz['question']['id'];
                            $ares = $DB->Query("SELECT * FROM `m_media_question_history` WHERE `session_id` = '{$session_id}' AND `answer_id` = '{$answer['id']}' AND `value` = 1 LIMIT 1");
                            $arow = $ares->Fetch();
                            $checked = '';
                            if (!empty($arow['id'])) $checked = 'checked=checked';

                            echo '<div class="m-media-radio-wrap">
                              <div class="m-media-radio">
                                  <input '.$checked.' class="js-media-answer" name="answer'.$quiz['question']['id'].'" value="'.$answer['id'].'" type="checkbox" id="radio'.$answer['id'].'" data-question_id="'.$question_id.'" data-media="'.MEDIA_FOLDER.'">
                                  <label for="radio'.$answer['id'].'">
                                      <span></span>
                                      '.$answer['name'].'
                                  </label>
                              </div>
                            </div>';
                        }
                        echo '</div>
                        <div class="m-media-quiz-result-btn js-media-answer-result" data-media="'.MEDIA_FOLDER.'" data-question_id="'.$quiz['question']['id'].'">Показать результаты</div>
                      </div>';
                  }
                echo '</section>';
              }
              break;
          case 14: //текстовый редактор
              if (!empty($box['text'])) {
                  echo '<section class="m-media-detail-box m-media-detail-text" id="link'.$box_id.'">
                      '.$box['text'].'
                  </section>';
              }
              break;
          case 15: 
              echo '<section class="m-media-detail-line" id="link'.$box_id.'"></section>';
              break;
          case 16: 
              if (!empty($box['video'])) {
                  $audio = MEDIA_FOLDER.'/files/m_media_constructor/'.$box['video'];
                  echo '<section class="m-media-detail-box m-media-detail-box-audio" id="link'.$box_id.'">
                    <div class="m-media-audio-wrap">
                      <div class="m-media-audio">
                        <audio class="m-media-audio-file" id="player1" controls>
                          <source src="'.$audio.'" type="audio/mp3" />
                        </audio>';
                        if (!empty($box['name'])) {
                            echo '<div class="m-media-audio-info">';
                              if (!empty($box['image'])) {
                                  echo '<span class="m-media-audio-info-img">
                                    <img src="'.MEDIA_FOLDER.'/upload/m_media_constructor/'.$box['image'].'" alt="'.$box['name'].'">
                                  </span>';
                              }
                              echo '<span class="m-media-audio-info-name">';
                                echo '<a href="'.$audio.'" download title="Скачать">'.$box['name'].'</a>';
                                if (!empty($box['short'])) echo ' © '.$box['short'];
                              echo '</span>';
                            echo '</div>';
                        }
                      echo '</div>
                    </div>
                  </section>';
              }
              break;
          case 17: //свой код
              echo '<section class="m-media-detail-box" id="link'.$box_id.'">'.$box['code'].'</section>';
              break;
          case 18:
              if (!$actions_show) {
                  echo $actions;
                  $actions_show = true;
              }
              break;
      }
      ?>

    <? } ?>

    <? if (!$actions_show) echo $actions; ?>

    <? if (!$other_show) {
         echo '<div class="m-media-other-wrap" id="link_other">
              <section class="m-media-other">
                <div class="m-media-center">
                    <h2 class="m-media-other-head">последние статьи</h2>';
                    $ids = array();
                    $media_items = Media::list(' date DESC, ', '', $limit = 4);
                    foreach ($media_items AS $media_item) {
                        $ids[] = $media_item['id'];
                    }
                    echo '<div class="m-media-items">
                      '.Media::siteItems($ids).'
                    </div>
                </div>
              </section>
          </div>';
    } ?>

    <div class="m-media-detail-box m-media-detail-box-end"></div>

  </div>

</div>

<? require_once("foot.php"); ?>