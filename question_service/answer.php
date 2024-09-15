<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if(!$_REQUEST['ext_id']) LocalRedirect('/');
$APPLICATION->SetTitle("Задать вопрос");

if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {

    exit;
}
require($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");
$stat = $_GET['stat'];//статус пользователя по ссылке
$ext_id = $_GET['ext_id'];//внешний id вопроса
?>


<?if (!$USER->IsAuthorized() && $stat != "user" && $stat != "dealer") {
    include_once($_SERVER["DOCUMENT_ROOT"] . "/personal/auth.php");
}

global $USER;

$user_stat = "user";


$user = $USER->GetFirstName();
$user_id = $USER->GetID();

$res = CUser::GetUserGroupList($user_id);

while ($arGroup = $res->Fetch()) {
    if ($arGroup['GROUP_ID'] == '9') {
        $user_stat = "spec";
    }
    if ($arGroup['GROUP_ID'] == '10') {
        $user_stat = "mod";
    }
    if ($arGroup['GROUP_ID'] == '1') {
        $user_stat = "admin";
    }
}

if ($stat == 'dealer') $user_stat = $stat;

$was_send_mess = '';
//проверка id спеца
    $res_spec = CIBlockElement::GetList(Array(), Array('IBLOCK_ID' => 37, 'PROPERTY_EXTERNAL_ID' => $ext_id, 'ACTIVE' => 'Y'));
    while ($res_spec_item = $res_spec->GetNextElement()) {
        $item_spec = array_merge($res_spec_item->GetFields(), $res_spec_item->GetProperties());
        $spec_names = $item_spec['QST_SPEC']['VALUE'];
    }
    $true_spec = 0;
    $stat_mod = 0;
    foreach ($spec_names as $spec_name) {

        if ($spec_name == $user) {
            $true_spec++;
        }
        //if ($spec_name == "Ольга Гмыря") {
        if ($spec_name == "Андрей Чиличихин") {
            $stat_mod++;
        }
    }

    //редирект на последнюю версию вопроса
    $new_res = CIBlockElement::GetList(Array('ID'=>'desc'), Array('IBLOCK_ID' => 37, 'NAME' => $item_spec['NAME'], 'ACTIVE' => 'Y'));
    if($item_spec['SEND_DATE']['VALUE']) {
        if($new_res->SelectedRowsCount() > 1) {
            $new_item = $new_res->GetNextElement();
            $new_item = array_merge($new_item->GetFields(), $new_item->GetProperties());
            if($new_item['ID'] != $item_spec['ID']) {
                LocalRedirect("answer.php?ext_id=".$new_item['EXTERNAL_ID']['VALUE']);
            }
        }
    } else {
        // сообщение о редиректе
       while ($new_item = $new_res->GetNextElement()) {
            $new_item = array_merge($new_item->GetFields(), $new_item->GetProperties());
            foreach($new_item['QST_SPEC']['VALUE'] as $new_item_spec) {
                if($new_item_spec == $user) {
                    $was_send_mess = '<div style="color: #000;margin-top:10px;">Вопрос был перенаправлен другому специалисту.</div>';
                }
            }
            //print_r($new_item['QST_SPEC']['VALUE']);
            //echo "<br>";
        }
    }

    function send_gender($pers)
    {
        if ($pers == 'Ольга Гмыря' || $pers == 'Александра Высоцкая' || $pers == 'Наталья Овчинникова' || $pers == 'Ольга Кока' || $pers == 'Галина Гроян') {
            return "а";
        }
    }

    ?>
    <div class="content-wrapper">
    <?
    if ($USER->IsAuthorized() && $user_stat == "mod" || $USER->IsAuthorized() && $user_stat == "admin" || $USER->IsAuthorized() && $user_stat == "spec" && $true_spec != 0 || $stat == "user" || $stat == "dealer"):
        ?>

        <?

        $res = CIBlockElement::GetList(Array(), Array('IBLOCK_ID' => 37, 'PROPERTY_EXTERNAL_ID' => $ext_id, 'ACTIVE' => 'Y'));

        ?>


      <link rel="stylesheet" href="/question_service/questionstyle.css?v=<?=$random?>">

      <div id="middle" class="answ-page" data-type="question-wrap">


      <div class="e-qs-user" data-user="<?= $user_id ?>" style="display:none"></div>

        <? if ($item = $res->GetNextElement()): ?>
        <? //while($item = $res->GetNextElement()) {
        $item = array_merge($item->GetFields(), $item->GetProperties());




        if ($item['QST_STATUS']['VALUE'] == "Новый вопрос") {
            $pers_arr = $item['QST_SPEC']['VALUE'];
            foreach ($pers_arr as $pers) {
                if ($pers == $user) {
                    CIBlockElement::SetPropertyValuesEX($item['ID'], 37, array("QST_SEEN" => 'Y', "QST_STATUS" => "Вопрос прочитан"));
                }
            }
        }
        ?>

        <? /*заголовки*/ ?>

      <div class="e-ap-headers">
        <h1 data-type="ap-title" data-id="<?= $item['ID'] ?>">Вопрос №<?= $item['NAME'] ?></h1>
        <h2>Тема: <?= $item['QST_SUBJ']['VALUE'] ?></h2>
        <p class="e-ap-ask-date"><span>Вопрос добавлен: <?= substr($item['QST_DATE']['VALUE'], 0, -3) ?></span></p>

      </div>

        <? /*верхние кнопки*/ ?>

        <? if ($user_stat == "spec" || $user_stat == "mod" || $user_stat == "admin") { ?>

        <div class="e-ap-headers-buttons">
          <p class="e-aqs-current-user">пользователь: <?= $user ?></p>
            <? /*статус*/ ?>
            <?
            if ($item['QST_STATUS']['VALUE'] == "Ответ отправлен") {
                $class = "green";
            } elseif ($item['QST_STATUS']['VALUE'] == "Вопрос просрочен") {
                $class = "red";
            } else {
                $class = "black";
            }
            ?>

          <p class="e-ap-headers-buttons-stat <?= $class ?>">
              <?= $item['QST_STATUS']['VALUE'] ?>
          </p>

            <? /*отложить вопрос*/ ?>

            <? if ($item['SEND_DATE']['VALUE'] == "" && $item['QST_STATUS']['VALUE'] != "Вопрос отложен" && $item['ANSW']['~VALUE']['TEXT'] == "") { ?>
              <div class="e-ap-putoff-btn" data-type="ap-putoff"><i class="icon-put-off"></i><span
                  data-type="ap-putoff-txt">Отложить вопрос</span></div>
            <? } ?>

            <? if ($item['SEND_DATE']['VALUE'] && $item['QST_STATUS']['VALUE'] == "Вопрос отложен" && $item['ANSW']['~VALUE']['TEXT'] == "") { ?>
              <div class="e-ap-putoff-btn e-ap-putoff-btn-act" style="cursor:default;" data-type="ap-putoff"><i
                  class="new-icomoon icon-put-off"></i><span
                  data-type="ap-putoff-txt">Ответ на вопрос отложен</span></div>
            <? } ?>

            <? /*перенаправить в тему*/ ?>
            <? if ($user_stat == "mod" && $item['SEND_DATE']['VALUE'] == "" || $user_stat == "admin" && $item['SEND_DATE']['VALUE'] == "" || $user_stat == "spec" && $item['SEND_DATE']['VALUE'] == "" && $item['ANSW']['~VALUE']['TEXT'] == "") { ?>

              <div class="e-ap-redirect-btn">

                  <? if ($item['QST_STATUS']['VALUE'] != "Вопрос отложен") { ?>
                    <div class="new-subj-title" data-type="new-subj">
                      Перенаправить для ответа <i></i>
                    </div>
                  <? } else { ?>
                    <div class="new-subj-title new-subj-title-notactive" data-type="new-subj">
                      Перенаправить для ответа <i></i>
                    </div>
                  <? } ?>


                <form data-type="new-subj-form" class="e-ap-new-subj">
                  <input type="hidden" name="ap-subj-id" value="<?= $item['ID'] ?>">
                  <div class="e-ap-new-subj-items">
                    <div class="e-ap-new-subj-items-title" data-type="new-subj-item">В другую тему <i></i></div>
                    <div class="e-ap-new-subj-items-value">
                      <div class="e-ap-new-subj-rad">
                        <input type="radio" name="ap-subj" value="2" id="rad-2" data-type="send-subj"> <label
                          for="rad-2">Монтаж изделий</label>
                      </div>
                      <div class="e-ap-new-subj-rad">
                        <input type="radio" name="ap-subj" value="3" id="rad-3" data-type="send-subj"> <label
                          for="rad-3">Свойства изделий</label>
                      </div>
                      <div class="e-ap-new-subj-rad">
                        <input type="radio" name="ap-subj" value="4" id="rad-4" data-type="send-subj"> <label
                          for="rad-4">Претензии и вопросы <br>по заказам и сервису</label>
                      </div>
                      <div class="e-ap-new-subj-rad">
                        <input type="radio" name="ap-subj" value="1" id="rad-1" data-type="send-subj"> <label
                          for="rad-1">Ассортимент и уточнение <br>по размерам</label>
                      </div>
                      <div class="e-ap-new-subj-rad">
                        <input type="radio" name="ap-subj" value="5" id="rad-5" data-type="send-subj"> <label
                          for="rad-5">Гарантийные обязательства</label>
                      </div>
                      <div class="e-ap-new-subj-rad">
                        <input type="radio" name="ap-subj" value="6" id="rad-6" data-type="send-subj"> <label
                          for="rad-6">Работа магазинов</label>
                      </div>
                      <div class="e-ap-new-subj-rad">
                        <input type="radio" name="ap-subj" value="7" id="rad-7" data-type="send-subj"> <label
                          for="rad-7">Другое</label>
                      </div>
                    </div>
                  </div>
                  <div class="e-ap-new-subj-items e-ap-new-subj-items-manager">
                    <div class="e-ap-new-subj-items-title" data-type="new-subj-item">Другому менеджеру <i></i></div>
                    <div class="e-ap-new-subj-items-value">
                      <div class="e-ap-new-subj-rad">
                        <input type="radio" name="ap-subj" value="8" id="rad-9" data-type="send-spec"> <label
                          for="rad-9">Алексей Брук</label>
                      </div>
                      <?/*<div class="e-ap-new-subj-rad">
                        <input type="radio" name="ap-subj" value="9" id="rad-10" data-type="send-spec"> <label
                          for="rad-10">Ольга Гмыря</label>
                      </div>*/?>
                      <div class="e-ap-new-subj-rad">
                        <input type="radio" name="ap-subj" value="11" id="rad-12" data-type="send-spec"> <label
                          for="rad-12">Александра Высоцкая</label>
                      </div>
                      <div class="e-ap-new-subj-rad">
                        <input type="radio" name="ap-subj" value="12" id="rad-13" data-type="send-spec"> <label
                          for="rad-13">Сергей Авдеев</label>
                      </div>
                      <div class="e-ap-new-subj-rad">
                        <input type="radio" name="ap-subj" value="13" id="rad-14" data-type="send-spec"> <label
                          for="rad-14">Наталья Овчинникова</label>
                      </div>
                      <div class="e-ap-new-subj-rad">
                        <input type="radio" name="ap-subj" value="14" id="rad-15" data-type="send-spec"> <label
                          for="rad-15">Ольга Кока</label>
                      </div>
                      <div class="e-ap-new-subj-rad">
                        <input type="radio" name="ap-subj" value="15" id="rad-16" data-type="send-spec"> <label
                          for="rad-16">Андрей Чиличихин</label>
                      </div>
                    <div class="e-ap-new-subj-rad">
                        <input type="radio" name="ap-subj" value="5039" id="rad-5039" data-type="send-spec"> <label
                                for="rad-5039">Наталья Рябчикова</label>
                    </div>
                    <div class="e-ap-new-subj-rad">
                        <input type="radio" name="ap-subj" value="6665" id="rad-6665" data-type="send-spec"> <label
                                for="rad-6665">Любовь Осетрова</label>
                    </div>
                    <div class="e-ap-new-subj-rad">
                        <input type="radio" name="ap-subj" value="6666" id="rad-6666" data-type="send-spec"> <label
                                for="rad-6666">Валентина Дудникова</label>
                    </div>
                        <div class="e-ap-new-subj-rad">
                            <input type="radio" name="ap-subj" value="17" id="rad-17" data-type="send-spec"> <label
                                    for="rad-17">Дмитрий Рудыкин</label>
                        </div>
                    </div>
                  </div>
                  <div class="e-ap-new-subj-items">
                    <div class="e-ap-new-subj-items-title" data-type="new-subj-reg">В другой регион</div>
                  </div>
                  <button type="reset" data-event="no-enter" data-type="dealer-send-reset">Отменить</button>
                  <button type="button" data-event="no-enter" data-type="dealer-send">Подтвердить</button>
                </form>
              </div>

            <? } ?>


            <? if ($item['QST_STATUS']['VALUE'] == "На модерации" && $user_stat == "mod" || $item['QST_STATUS']['VALUE'] == "На модерации" && $user_stat == "admin"): ?>

                <? /*удалить вопрос*/ ?>

              <div class="e-ap-del-btn" data-type="ap-del"><i class="new-icomoon icon-close"></i><span data-type="ap-putoff-txt">Удалить вопрос</span>
              </div>

                <? /*подтвердить вопрос*/ ?>

              <div class="e-ap-check-btn" data-type="ap-check"><i class="new-icomoon icon-check-1"></i><span
                  data-type="ap-putoff-txt">Подтвердить вопрос</span></div>

            <? endif; ?>

        </div>

        <? } ?>

        <? if ($item['QST_SEND']['VALUE'] == 'Y' && $stat != 'user' && $stat != 'dealer') { ?>
        <div class="e-ap-ask-log">
            <? if ($item['SEND_DEALER']['VALUE'] != "") { ?>
              <p>Вопрос дилеру на e-mail <?= $item['SEND_DEALER']['VALUE'] ?>
                переадресовал<?= send_gender($item['SEND_WHO']['VALUE']) ?> <?= $item['SEND_WHO']['VALUE'] ?> <?= substr($item['SEND_DATE']['VALUE'], 0, -3) ?></p>
            <? } ?>
            <?
            $prev_name = $item['NAME'];
            $ar_res = CIBlockElement::GetList(Array('CREATED' => 'DESC'), Array('IBLOCK_ID' => 37, 'NAME' => $prev_name, 'ACTIVE' => 'Y'));
            while ($prev_item = $ar_res->GetNextElement()) {
                $prev_item = array_merge($prev_item->GetFields(), $prev_item->GetProperties());
                ?>
                <? if ($prev_item['SEND_SUBJ']['VALUE'] != "") { ?>
                <p>Вопрос в другую тему
                  перенаправил<?= send_gender($prev_item['SEND_WHO']['VALUE']) ?> <?= $prev_item['SEND_WHO']['VALUE'] ?> <?= substr($prev_item['SEND_DATE']['VALUE'], 0, -3) ?>
                  из темы "<?= $prev_item['QST_SUBJ']['VALUE'] ?>"</p>
                <? } ?>
                <? if ($prev_item['SEND_SPEC']['VALUE'] != "") { ?>
                <p>Вопрос другому менеджеру
                  перенаправил<?= send_gender($prev_item['SEND_WHO']['VALUE']) ?> <?= $prev_item['SEND_WHO']['VALUE'] ?> <?= substr($prev_item['SEND_DATE']['VALUE'], 0, -3) ?></p>
                <? } ?>
                <? if ($prev_item['SEND_REG']['VALUE'] != "") { ?>
                <p>вопрос в регион <?= $prev_item['SEND_REG']['VALUE'] ?>
                  перенаправил<?= send_gender($prev_item['SEND_WHO']['VALUE']) ?> <?= $prev_item['SEND_WHO']['VALUE'] ?> <?= substr($prev_item['SEND_DATE']['VALUE'], 0, -3) ?></p>
                <? } ?>
            <? } ?>
        </div>
        <? } ?>


        <? /*вопрос*/ ?>

        <? if ($user_stat == "user" && $item['QST_STATUS']['VALUE'] != "Ответ отправлен") {
            $usr_class = "no-border";
        } else {
            $usr_class = "";
        }
        ?>

      <div class="e-ap-ask <?= $usr_class ?>">
        <div class="e-ap-ask-left">
          <i class="icon-aqs-user"></i>
        </div>
        <div class="e-ap-ask-right">
          <p class="e-ap-ask-attr"><span>Пользователь: </span><?= $item['QST_NAME']['VALUE'] ?></p>
          <p class="e-ap-ask-attr"><span>E-mail: </span><?= $item['QST_MAIL']['VALUE'] ?></p>
          <p class="e-ap-ask-attr"><span>Телефон: </span><?= $item['QST_PHONE']['VALUE'] ?></p>
          <p class="e-ap-ask-attr"><span>Город (местоположение): </span><?= $item['QST_LOC']['VALUE'] ?></p>
            <? if ($user_stat == "spec" || $user_stat == "mod" || $user_stat == "admin"): ?>
                <?
            if($item['MY_CITY']['VALUE'] != '') {
                $new_arFilter = Array('IBLOCK_ID' => 7, 'ACTIVE' => 'Y', 'ID' => $item['MY_CITY']['VALUE']);
                $new_db_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $new_arFilter);
                $new_ip_loc = $new_db_list->GetNextElement();
                if ($new_ip_loc) $new_ip_loc = array_merge($new_ip_loc->GetFields(), $new_ip_loc->GetProperties());
                $cur_loc = $new_ip_loc['NAME'];
            } else {
                $cur_loc = '<span style="font-weight: 600;color:red">не указан</span>';
            }

                ?>
              <p class="e-ap-ask-attr"><span>Выбранный регион на сайте: </span><?= $cur_loc ?></p>
            <? endif; ?>
            <? //print_r($item['QST'])?>
          <p class="e-ap-ask-text"><?= htmlspecialchars_decode($item['QST']['~VALUE']['TEXT']) ?></p>
          <p>
              <? if ($item['QST_FILE']['VALUE'] != "") {
              $file_name = explode("/", $item['QST_FILE']['VALUE']);
              $length = count($file_name);
              $file_name = $file_name[$length - 1];
              ?>
          <div class="e-ap-ask-file">
            прикрепленный файл:
            <a href="<?= $item['QST_FILE']['VALUE'] ?>" download class="e-ap-ask-file"> <?= $file_name ?></a>
          </div>
        <? } ?>
          </p>
        </div>
      </div>


        <? /*вопрос перенаправлен в тему*/ ?>
        <? if ($stat != "dealer") { ?>
            <? if ($item['SEND_SUBJ']['VALUE'] != "") {
                $new_subj = "Вопрос перенаправлен в тему: <span>" . $item['SEND_SUBJ']['VALUE'] . "</span>";
            } elseif ($item['SEND_SPEC']['VALUE'] != "") {
                $new_subj = "Вопрос перенаправлен менеджеру: <span>" . $item['SEND_SPEC']['VALUE'] . "</span>";
            } elseif ($item['SEND_DEALER']['VALUE'] != "") {
                $new_subj = "Вопрос переадресован дилеру на e-mail: <span>" . $item['SEND_DEALER']['VALUE'] . "</span>";
            } elseif ($item['SEND_REG']['VALUE'] != "") {
                $new_subj = "Вопрос перенаправлен в регион: <span>" . $item['SEND_REG']['VALUE'] . "</span>";
            } else {
                $new_subj = "";
            }
            ?>
        <div class="e-ap-new-subj-message"><?= $new_subj ?></div>
        <? } ?>




        <? /*ответ*/ ?>
        <? if ($user_stat == "spec" || $user_stat == "mod" || $user_stat == "admin") { ?>

            <? if ($item['SEND_DATE']['VALUE'] == "" && $item['ANSW']['~VALUE']['TEXT'] == "" && $item['DEALER_MAIL']['VALUE'] == ""): ?>
          <form data-type="ap-answ" id="apAnsw" class="answ-wrap">
              <? if ($item['QST_STATUS']['VALUE'] != "Вопрос отложен"){ ?>
            <div class="e-ap-answ">
                <? } else { ?>
              <div class="e-ap-answ e-ap-answ-putoff">
                  <? } ?>
                <div class="e-ap-answ-left">
                  <i class="new-icomoon icon-ava-spec"></i>
                </div>
                <div class="e-ap-answ-right">
                  <div class="e-ap-mod-comment-wrapper e-ap-mod-answer-wrapper">
                    <div class="e-ap-textarea-placeholder" data-type="answ-plchldr"><i class="icon-comment1"></i>ответить
                      покупателю
                    </div>
                    <textarea rows="4" name="ap-answ-text" class="autogrow"></textarea>
                  </div>
                  <div class="e-ap-answ-file">прикрепленный файл: <span></span></div>
                </div>
                <div class="e-ap-answ-panel">
                  <div class="e-ap-answ-panel-attach">
                    <label>
                      <i class="icon-add"></i>
                      <span data-type="ap-add-file">прикрепить файл</span>
                      <input type="file" name="ap-answ-file">
                    </label>
                  </div>
                </div>
                  <? if ($user_stat == "admin" || $user == "Андрей Чиличихин") { ?>
                    <input type="checkbox" name="edit" id="e-ap-edit" class="e-ap-edit-input" value="edit"><label
                      for="e-ap-edit" class="e-ap-edit-label">Редактировать ответ</label>
                  <? } ?>
                <button type="button" data-type="ap-answ-send" class="e-ap-answ-send" data-event="no-enter">Отправить
                  ответ
                </button>
                <button type="reset" data-type="ap-answ-reset" class="e-ap-answ-reset" data-event="no-enter">Отменить
                </button>
              </div>
          </form>
            <? endif ?>


            <? /*возобновить ответ*/ ?>
            <? if ($item['QST_STATUS']['VALUE'] != "Вопрос отложен") { ?>
          <div class="e-ap-renew" data-type="ap-renew">Возобновить ответ</div>
            <? } else { ?>
          <div class="e-ap-renew" style="display:block;" data-type="ap-renew">Возобновить ответ</div>
            <? } ?>

        <? } ?>

        <? /*if($item['QST_STATUS']['VALUE'] == "Вопрос отложен" && $stat == "mod"): ?>
		<div class="e-ap-new-subj-message">Ответ на вопрос отложен</div>
	<? endif; */ ?>


        <? /*ответ на вопрос*/ ?>

        <? if ($item['ANSW']['~VALUE']['TEXT'] != "") { ?>
        <div class="e-ap-spec-answ">
          <div class="e-ap-spec-answ-left">
            <i class="new-icomoon icon-ava-spec"></i>
          </div>
          <div class="e-ap-spec-answ-right">
            <h3>Ответ:</h3>
            <form id="editAnswer" class="edit-answer">
              <div class="e-ap-spec-answ-text"><?= htmlspecialchars_decode($item['ANSW']['~VALUE']['TEXT']) ?></div>
                <? if ($user_stat == "admin" || $user == "Андрей Чиличихин") { ?>
                  <textarea class="e-ap-spec-answ-edit autogrow" placeholder="Редактировать ответ"
                            name="edit-text"><?= htmlspecialchars_decode($item['ANSW']['~VALUE']['TEXT']) ?></textarea>
                <? } ?>
                <? if ($item['ANSW_FILE']['VALUE'] != "") { ?>
                    <?
                    $file_name = explode("/", $item['ANSW_FILE']['VALUE']);
                    $length = count($file_name);
                    $file_name = $file_name[$length - 1];
                    ?>
                  <div class="e-ap-spec-answ-file">прикрепленный файл: <a href="<?= $item['ANSW_FILE']['VALUE'] ?>"
                                                                          download><?= $file_name ?></a></div>

                    <? if ($user_stat == "admin" || $user == "Андрей Чиличихин") { ?>
                    <div class="e-ap-spec-answ-file-edit">прикрепленный файл: <a
                        href="<?= $item['ANSW_FILE']['VALUE'] ?>" download><?= $file_name ?></a></div>
                    <? } ?>

                <? } ?>

                <? if ($user_stat == "admin" || $user == "Андрей Чиличихин") { ?>
                  <div class="e-ap-answ-panel e-ap-answ-edit-panel">
                    <div class="e-ap-answ-panel-attach">
                      <label>
                        <i class="icon-add"></i>
                        <span data-type="ap-add-file">прикрепить файл</span>
                        <input type="file" name="ap-answ-file" value="">
                      </label>
                    </div>
                  </div>
                <? } ?>

              <div class="e-ap-spec-answ-attr">ответил: <?= $item['ANSW_NAME']['VALUE'] ?>
                , <?= substr($item['ANSW_DATE']['VALUE'], 0, -3) ?></div>
                <? if ($user_stat == "admin" || $user == "Андрей Чиличихин") { ?>
                  <button type="button" data-type="edit-answ-btn" class="e-ap-answ-send" data-event="no-enter">Сохранить
                    изменения
                  </button>
                  <button type="button" data-type="edit-answ-reset-btn" class="e-ap-answ-reset" data-event="no-enter">
                    Очистить
                  </button>
                <? } ?>
                <? if ($user_stat == "admin" || $user == "Андрей Чиличихин") { ?>
                  <input type="checkbox" name="edit-answ" id="e-ap-edit-answ"
                         class="e-ap-edit-input e-ap-edit-input-answ" value="edit-answ">
                  <label for="e-ap-edit-answ" class="e-ap-edit-label e-ap-edit-label-answ">Редактировать ответ</label>
                <? } ?>
            </form>
          </div>
        </div>
        <? } else { ?>

        <div class="e-ap-spec-answ for-ajax">
          <div class="e-ap-spec-answ-left">
            <i class="new-icomoon icon-ava-spec"></i>
          </div>
          <div class="e-ap-spec-answ-right">
            <h3>Ответ:</h3>
            <div class="e-ap-spec-answ-text"></div>
            <div class="e-ap-spec-answ-file" style="display:none;">прикрепленный файл: <a href=""></a></div>
            <div class="e-ap-spec-answ-attr"></div>
          </div>
        </div>

        <? } ?>


        <? /*фидбек*/ ?>

        <?
        $arRes = CIBlockElement::GetList(Array('CREATED' => 'ASC'), Array('IBLOCK_ID' => 42, 'NAME' => $item['NAME'], 'ACTIVE' => 'Y'));
        $add_count = $arRes->SelectedRowsCount();
        ?>

        <? //if($user_stat == "user" || $add_count > 0): ?>
        <? if ($item['ANSW']['~VALUE']['TEXT'] != "" ): ?>
        <?if($user_stat != "dealer" || $user_stat == "dealer" && $add_count > 0):?>
        <div class="e-ap-mod-comment e-ap-mod-feedback e-ap-mod-feedback-not-empty">
          <div class="e-ap-feedb-text-title">Дополнительные вопросы и ответы:</div>
          <div class="e-ap-comments" data-type="feedb-comm">
              <?
              $arRes = CIBlockElement::GetList(Array('CREATED' => 'ASC'), Array('IBLOCK_ID' => 42, 'NAME' => $item['NAME'], 'ACTIVE' => 'Y'));
              while ($add = $arRes->GetNextElement()) {
                  $add = array_merge($add->GetFields(), $add->GetProperties());
                  if (isset($add)) { ?>
                      <?
                      $add_stat = $add['ADD_MESS_STAT']['VALUE'] == "Вопрос" ? "user" : "spec";
                      ?>
                    <div class="e-ap-comment e-ap-comment-<?= $add_stat ?>">
                      <form class="editAdd" id="form<?= $add["ID"] ?>">
                        <div class="e-ap-comment-name"><?= $add['ADD_MESS_STAT']['VALUE'] ?>
                          , <?= substr($add['ADD_MESS_DATE']['VALUE'], 0, -3) ?></div>
                        <div class="e-ap-comment-text"
                             data-type="add-answ"><?= htmlspecialchars_decode($add['ADD_MESS']['~VALUE']['TEXT']) ?></div>
                          <? if ($user_stat == "admin" && $add_stat == "spec" || $user == "Андрей Чиличихин" && $add_stat == "spec") { ?>
                            <textarea class="e-ap-add-text-edit autogrow" placeholder="Редактировать ответ"
                                      name="edit-text-add"><?= htmlspecialchars_decode($add['ADD_MESS']['~VALUE']['TEXT']) ?></textarea>
                          <? } ?>
                          <? if ($add['ADD_MESS_FILE']['VALUE'] != "") { ?>
                              <?
                              $file_name = explode("/", $add['ADD_MESS_FILE']['VALUE']);
                              $length = count($file_name);
                              $file_name = $file_name[$length - 1];
                              ?>
                            <div class="e-ap-ask-file e-ap-ask-file-current">прикрепленный файл: <a
                                href="<?= $add['ADD_MESS_FILE']['VALUE'] ?>"><?= $file_name ?></a></div>
                              <? if ($user_stat == "admin" && $add_stat == "spec" || $user == "Андрей Чиличихин" && $add_stat == "spec") { ?>
                              <div class="e-ap-ask-file e-ap-ask-file-edit">прикрепленный файл: <a
                                  href="<?= $add['ADD_MESS_FILE']['VALUE'] ?>" download><?= $file_name ?></a></div>
                              <? } ?>
                          <? } ?>
                          <? if ($user_stat == "admin" || $user == "Андрей Чиличихин") { ?>
                            <div class="e-ap-answ-panel e-ap-add-edit-panel">
                              <div class="e-ap-answ-panel-attach">
                                <label>
                                  <i class="icon-add"></i>
                                  <span data-type="ap-add-file">прикрепить файл</span>
                                  <input type="file" name="ap-add-file" value="">
                                </label>
                              </div>
                            </div>
                          <? } ?>

                          <? if ($add['ADD_MESS_SPEC']['VALUE'] != "") { ?>
                            <div class="e-ap-comment-for">ответил: <?= $add['ADD_MESS_SPEC']['VALUE'] ?></div>
                          <? } ?>
                          <? if ($user_stat == "admin" && $add_stat == "spec" || $user == "Андрей Чиличихин" && $add_stat == "spec") { ?>
                            <input type="checkbox" name="edit" id="<?= $add["ID"] ?>"
                                   class="e-ap-edit-input e-ap-edit-input-add" value="add-edit">
                            <label for="<?= $add["ID"] ?>" class="e-ap-edit-label e-ap-edit-label-add">Редактировать
                              ответ</label>
                            <button type="button" data-type="edit-add-btn" class="e-ap-answ-send" data-event="no-enter">
                              Сохранить изменения
                            </button>
                            <button type="button" data-type="edit-add-reset-btn" class="e-ap-answ-reset"
                                    data-event="no-enter">Очистить
                            </button>
                          <? } ?>
                      </form>
                    </div>
                  <? }
              }
              ?>
          </div>

            <?if ($user_stat != "dealer") { ?>

            <? if ($user_stat == "user") {
                $f_stat = "user";
                $plchldr = "добавить вопрос";
            } else {
                $f_stat = "spec";
                $plchldr = "написать ответ";
            }
            ?>
          <form class="apFeedb answ-wrap" id="apFeedb">
            <input type="hidden" name="aqs-page" value="" id="e-aqs-input-page">
            <input type="hidden" name="aqs-stat" value="<?= $f_stat ?>">
            <div class="e-ap-mod-comment-wrapper">
              <div class="e-ap-textarea-placeholder" data-type="feedb-plchldr"><i
                  class="new-icomoon icon-comment1"></i> <?= $plchldr ?></div>
              <textarea data-type="feedb-text" name="feedb-text" class="autogrow"></textarea>
              <div class="e-ap-answ-file">прикрепленный файл: <span></span></div>
            </div>
            <div class="e-ap-answ-panel e-ap-answ-edit-panel">
              <div class="e-ap-answ-panel-attach">
                <label>
                  <i class="icon-add"></i>
                  <span data-type="ap-add-file">прикрепить файл</span>
                  <input type="file" name="ap-answ-file" value="">
                </label>
              </div>
            </div>
              <? if ($user_stat == "admin" || $user == "Андрей Чиличихин") { ?>
                <input type="checkbox" name="edit" id="answAdd" class="e-ap-edit-input e-ap-edit-input-add"
                       value="edit">
                <label for="answAdd" class="e-ap-edit-label e-ap-edit-label-add-answ">Редактировать ответ</label>
              <? } ?>
            <button type="button" class="e-ap-mod-comment-send" data-type="mod-feedb-send" data-event="no-enter">
              Отправить
            </button>
            <button type="reset" class="e-ap-mod-comment-reset" data-type="mod-feedb-reset" data-event="no-enter">
              Очистить
            </button>
          </form>
        </div>

        <? } ?>

        <? endif; ?>
        <? endif; ?>

        <? if ($item['USEFUL']['VALUE'] == "" && $user_stat == "user"): ?>
            <div class="e-ap-score">
                <div class="e-ap-score-label">Был ли ответ полезен?</div>
                <button type="button" class="e-ap-score-yes" data-type="feedb-but" data-val="yes" data-event="no-enter">Да
                </button>
                <button type="button" class="e-ap-score-no" data-type="feedb-but" data-val="no" data-event="no-enter">Нет
                </button>
            </div>
        <? elseif ($item['USEFUL']['VALUE'] != ""): ?>
            <div class="e-ap-score">
                <div class="e-ap-score-label">Был ли ответ полезен?</div>
                <?
                if ($item['USEFUL']['VALUE'] == "Y") {
                    $val = "Да";
                    $class = "green";
                } else {
                    $val = "Нет";
                    $class = "red";
                }
                ?>
                <div class="e-ap-score-value <?= $class ?>"><?= $val ?></div>
            </div>
        <? endif; ?>



        <? /*комментарии*/ ?>

        <? if ($user_stat == "mod" || $user_stat == "spec" || $user_stat == "admin" || $user_stat == "dealer") { ?>
        <div class="e-ap-comments e-ap-comments-not-empty" data-type="comm-wrap">
          <div class="e-ap-feedb-text-title">
              комментарии:
              <?/*запросить комментарий*/?>
              <?if($user_stat == "admin" || $user_stat == "mod" || $user_stat == "spec") { ?>
                <div class="e-ap-need-comm-wrap">
                    <? if($item['REQ_COMM']['VALUE'] != 'Y') { ?>
                      <div class="e-ap-need-comm" data-type="need-comm">
                        <i class="new-icomoon icon-check-1"></i>
                        <span data-type="ap-putoff-txt">запросить комментарий</span>
                      </div>
                    <? } else { ?>
                      <p class="need-comm-send"><i class="new-icomoon icon-check-1"></i>Запрошен комментарий</p>
                        <div class="need-comm-info">
                            <span>информация по дилеру:</span>
                            <?
                            $dealer_email = $item['REQ_COMM_EMAIL']['VALUE'];
                            $dealer_id = $item['REQ_COMM_ID']['VALUE'];
                            $dealer_name = '';

                            if($item['MY_CITY']['VALUE'] == 3109) {
                                $dealer_name = get_dealer_phone($dealer_email)['addr'];
                            } else {
                                if($dealer_id!= '') {
                                    $res_dealer = CIBlockElement::GetList(Array("SORT"=>"ASC"), Array("IBLOCK_ID"=>6, "ACTIVE"=>"Y","ID"=>$dealer_id), false, Array(), Array());
                                } else {
                                    $res_dealer = CIBlockElement::GetList(Array("SORT"=>"ASC"), Array("IBLOCK_ID"=>6, "ACTIVE"=>"Y","PROPERTY_city"=>$item['MY_CITY']['VALUE'],Array('LOGIC'=>'OR',Array('PROPERTY_qs_email'=>$dealer_email),Array('PROPERTY_email'=>$dealer_email))), false, Array(), Array());
                                }
                                $ob_dealer = $res_dealer->GetNextElement();
                                if($ob_dealer) {
                                    $dealer = array_merge($arFields = $ob_dealer->GetFields(),$arFields = $ob_dealer->GetProperties());
                                    $dealer_name = $dealer['~NAME'];
                                }
                            }
                            echo $dealer_name.'<br>'.$dealer_email;
                            ?>
                        </div>
                    <? } ?>
                </div>
              <? } ?>
          </div>
            <? $hascomm = 0; ?>
            <? $arRes = CIBlockElement::GetList(Array('CREATED' => 'ASC'), Array('IBLOCK_ID' => 39, 'NAME' => $item['NAME'], 'ACTIVE' => 'Y'));
            while ($comment = $arRes->GetNextElement()) {
                $comment = array_merge($comment->GetFields(), $comment->GetProperties());
                if (isset($comment)) {

                    if ($stat == "spec" && $comment['COMM_STAT']['VALUE'] == "mod" && $comment['COMM_SEEN']['VALUE'] == 'N' || $stat == "mod" && $comment['COMM_STAT']['VALUE'] == "spec" && $comment['COMM_SEEN']['VALUE'] == 'N') {
                        CIBlockElement::SetPropertyValuesEX($comment['ID'], 39, array("COMM_SEEN" => 'Y'));
                    }

                    ?>
                  <?if($user_stat != "dealer" || $user_stat == "dealer" && $comment['COMM_STAT']['VALUE'] == "dealer") { ?>
                  <div class="e-ap-comment e-ap-comment-<?= $comment['COMM_STAT']['VALUE'] ?>">
                      <?if($comment['COMM_STAT']['VALUE'] == 'dealer') { ?>
                          <div class="e-ap-comment-name">
                              Дилер, <?= date('d.m.Y H:i', $comment['DATE_CREATE_UNIX']) ?>
                          </div>
                        <? } else { ?>
                          <? $comm_who = $comment['COMM_NAME']['VALUE'] != "" ? $comment['COMM_NAME']['VALUE'] . ", " : ""; ?>
                          <div class="e-ap-comment-name">
                              <?= $comm_who ?> <?= date('d.m.Y H:i', $comment['DATE_CREATE_UNIX']) ?>
                          </div>
                        <? } ?>


                    <div
                      class="e-ap-comment-text"><?= htmlspecialchars_decode($comment['COMM_TEXT']['~VALUE']['TEXT']) ?></div>
                      <?
                      $pers_arr = $comment['COMM_WHO_SEND']['VALUE'];
                      if (!empty($pers_arr)) {
                          $n = 0;
                          $person = "комментарий для: ";
                          foreach ($pers_arr as $pers) {
                              $person .= $pers;
                              if ($n + 1 != count($pers_arr)) {
                                  $person .= ", ";
                              }
                              $n++;
                          }
                          echo '<div class="e-ap-comment-for">' . $person . '</div>';
                      }
                      ?>
                      <?if($comment['COMM_STAT']['VALUE'] == 'dealer') { ?>
                      <div class="e-ap-comment-for">
                          <span>информация по дилеру:</span>
                          <?if($comment['COMM_NAME']['VALUE'] != '') { ?>
                              <div><?=htmlspecialchars_decode($comment['COMM_NAME']['VALUE'])?></div>
                          <? } ?>
                          <?if($comment['COMM_EMAIL']['VALUE'] != '') { ?>
                              <div><?=$comment['COMM_EMAIL']['VALUE']?></div>
                          <? } ?>
                      </div>
                      <? } ?>
                  </div>
                  <? } ?>
                    <?
                    $hascomm++;
                }
            }
            ?>
        </div>
        <? } ?>


        <? if ($user_stat == "mod" || $user_stat == "admin" || $user_stat == "spec"): ?>
        <div class="e-ap-mod-comment">
          <form class="apComm" data-pos="answer">
            <input type="hidden" name="comm-stat" value="<?= $user_stat ?>">
            <div class="e-ap-mod-comment-wrapper" data-type="comment-text">
              <div class="e-ap-textarea-placeholder" data-type="comm-plchldr"><i class="new-icomoon icon-comment1"></i>
                  комментарий в Справочную
              </div>
              <textarea data-type="comm-text" name="comm-text" class="autogrow"></textarea>
            </div>
            <div class="e-ap-who-send">
              <span>комментарий отправить:</span>
              <div class="e-ap-who-send-list">
                  <?
                  $arr_users = Array("Алексей Брук","Андрей Чиличихин","Сергей Авдеев","Александра Высоцкая");
                  $arr_spec = $item['QST_SPEC']['VALUE'];
                  foreach($arr_spec as $spec) {
                    if(!in_array($spec,$arr_users)) {
                        $arr_users[] = $spec;
                    }
                  }
                  $n = 0;
                  foreach ($arr_users as $spec): ?>
                          <?
                          $filter = Array("NAME" => $spec, "ACTIVE" => 'Y');
                          $rsUsers = CUser::GetList(($by = "NAME"), ($order = "asc"), $filter);
                          while ($arUser = $rsUsers->Fetch()) {
                              $spec_id = $arUser['ID'];
                          }
                          ?>
                      <div>
                        <input type="checkbox" name="send_who[]" value="<?= $spec_id ?>"
                               id="<?= $item['ID'] ?><?= $spec_id ?><?= $n ?>" class="e-ap-edit-input"
                               data-type="who-send">
                        <label for="<?= $item['ID'] ?><?= $spec_id ?><?= $n ?>"
                               class="e-ap-edit-label"><?= $spec; ?></label>
                      </div>
                          <? $n++; ?>
                  <? endforeach ?>
              </div>
            </div>
              <? if ($user_stat == "mod" || $user_stat == "admin") { ?>
                <a href="/question_service/moderation.php" class="e-ap-mod-return"><i class="icon-arrow-down"></i>Вернуться на страницу модерации</a>
              <? } ?>
            <button type="button" class="e-ap-mod-comment-send" data-type="mod-comment-send" data-event="no-enter">
              Отправить
            </button>
            <button type="reset" class="e-ap-mod-comment-reset" data-type="mod-comment-reset" data-event="no-enter">
              Очистить
            </button>
          </form>
        </div>
        <? endif; ?>

        <? if ($stat == "dealer") { ?>
            <form data-type="ap-report" id="apReport" class="dealer-comment-wrap">
                <div class="e-ap-answ">
                    <div class="e-ap-answ-left">
                        <i class="icon-bubble" style="visibility: hidden;"></i>
                    </div>
                    <div class="e-ap-answ-right">
                        <div class="e-ap-mod-comment-wrapper e-ap-mod-answer-wrapper">
                            <div class="e-ap-textarea-placeholder" data-type="answ-plchldr"><i class="new-icomoon icon-comment1"></i>&nbsp;&nbsp;Оставить комментарий
                            </div>
                            <textarea rows="10" name="dealer-report" class="autogrow"></textarea>
                        </div>
                    </div>
                    <button type="button" data-type="report-send" class="e-ap-answ-send" data-event="no-enter">Сохранить комментарий
                    </button>
                    <button type="reset" data-type="report-reset" class="e-ap-answ-reset" data-event="no-enter">Отменить
                    </button>
                </div>
            </form>
        <? } ?>

        <? //} ?>




    <? else: ?>
      <div class="bx-qs-auth e-aqs-del-qst">По данной ссылке вопрос не найден.</div>
        <? if ($user_stat == "mod" || $user_stat == "admin"): ?>
        <a href="/question_service/moderation.php" class="e-ap-mod-return-del"><i class="new-icomoon icon-left-arr"></i>Перейти на
          страницу модерации</a>
        <? endif; ?>
    <? endif; ?>

      </div><? //end middle
        ?>

    <? elseif ($USER->IsAuthorized()):?>
            <div class="auth-wrapper">
                <div class="auth-img">
                    <img src="/img/personal/auth.jpg" alt="авторизация">
                </div>
                <div class="user-form-wrapper authorization-cont authorized err">
                    <div class="auth-data">
                        <div class="auth-data-txt">Доступ на данную страницу <span>закрыт</span> для&nbsp;пользователя</div>
                        <div class="auth-data-login"><?= CUser::GetLogin() ?></div>
                        <div class="auth-data-txt">Хотите выйти из&nbsp;учётной записи?</div>
                        <a href="?logout=yes" class="ok-btn user-form-btn">Выйти</a>
                    </div>
                </div>
            </div>
    <? endif; ?>
</div>
<script src="/personal/personal.js"></script>
<?
require($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");
if(defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED===true)
{
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog.php");
}