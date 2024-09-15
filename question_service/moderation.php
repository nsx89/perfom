<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
$APPLICATION->SetTitle("Задать вопрос | Модерация");

if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {

    exit;
}
require($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");?>

<link rel="stylesheet" href="/question_service/questionstyle.css?v=<?=$random?>">

<?if (!$USER->IsAuthorized()) {
    include_once($_SERVER["DOCUMENT_ROOT"] . "/personal/auth.php");
}

global $USER;

$stat = "user";

$user = $USER->GetFullName();
$user_id = $USER->GetID();

$res = CUser::GetUserGroupList($user_id);

while ($arGroup = $res->Fetch()){
   if($arGroup['GROUP_ID'] == '1') {
   	$stat = "admin";
   }
   if($arGroup['GROUP_ID'] == '9') {
   	$stat = "spec";
   }
    if($arGroup['GROUP_ID'] == '10') {
   	$stat = "mod";
   }
}
//     http://eplast.loc/question_service/moderation.php
//			o.gmirya
//			a.ryk
?>
<div class="content-wrapper">

<?	
	if ($USER->IsAuthorized() && $stat == "mod" || $USER->IsAuthorized() && $stat == "admin"):
?>
<?
	if(isset($_COOKIE['qm_mod_stat'])) {
		$sort = $_COOKIE['qm_mod_stat'];
	}
	else {
		$sort = "1";
	}

	if(isset($_COOKIE['qm_mod_date'])) {
		$sort_date = json_decode($_COOKIE['qm_mod_date']);
		$sort_date_from = $sort_date->from;
		$sort_date_to = $sort_date->to;
		$sort_date_val = $sort_date->val;
	}
	else {
		$sort_date_from = date('d.m.Y',time() - (6 * 24 * 60 * 60));
		$sort_date_to = "";
		$sort_date_val = "2";
	}

	if($sort == "1") {
		$arFilter = Array('IBLOCK_ID'=>37,'ACTIVE'=>'Y');
	}
	elseif($sort == "2") {
		$arFilter = Array('IBLOCK_ID'=>37,'ACTIVE'=>'Y','PROPERTY_QST_STATUS'=>'Вопрос просрочен');
	}
	elseif($sort == "3") {
		$arFilter = Array('IBLOCK_ID'=>37,'ACTIVE'=>'Y','PROPERTY_QST_STATUS'=>'Новый вопрос');
	}
	elseif($sort == "4") {
		$arFilter = Array('IBLOCK_ID'=>37,'ACTIVE'=>'Y','PROPERTY_QST_STATUS'=>'На модерации');
	}
	elseif($sort == "5") {
		$arFilter = Array('IBLOCK_ID'=>37,'ACTIVE'=>'Y','PROPERTY_QST_STATUS'=>'Ответ отправлен');
	}
	elseif($sort == "6") {
		$arFilter = Array('IBLOCK_ID'=>37,'ACTIVE'=>'Y','PROPERTY_QST_STATUS'=>'Вопрос отложен');
	}
	elseif($sort == "7") {
		$arFilter = Array('IBLOCK_ID'=>37,'ACTIVE'=>'Y','PROPERTY_QST_STATUS'=>'Вопрос прочитан');
	}
	elseif($sort == "8") {
		$arFilter = Array('IBLOCK_ID'=>37,'ACTIVE'=>'Y','PROPERTY_QST_STATUS'=>'Вопрос перенаправлен');
	}
	elseif($sort == "9") {
		$arFilter = Array('IBLOCK_ID'=>37,'ACTIVE'=>'Y','PROPERTY_QST_STATUS'=>'Вопрос переадресован дилеру');
	}

	if($sort_date_from != "") {
		$arFilter['>=DATE_CREATE'] = $sort_date_from." 00:00:00";
	}

	if($sort_date_to != "") {
		$arFilter['<=DATE_CREATE'] = $sort_date_to." 23:59:59";
	}
?>



<div class="middle e-question-moderation">
<div class="e-qs-user" data-user="<?=$user_id?>" style="display:none"></div>

	<h1>Сведения о поступивших вопросах в техподдержку</h1>

	<div class="e-qm-filter">

		<div class="e-qm-filter-period">
			<form>
				<div class="e-qm-filter-period-title">Период:</div>
				<div class="e-qm-filter-period-date">
					<div class="e-qm-period-item">
						<span>c</span>
						<input type="text" name="qm-from" class="tcal" value="<?if($sort_date_val=='0') echo $sort_date_from?>" id="qm-from" palceholder="дд.мм.гггг" />
						<label for="qm-from" class="qm-date-label"><i class="icon-calendar" palceholder="дд.мм.гггг"></i></label>
					</div>

					<div class="e-qm-period-item">
						<span>по</span>
						<input type="text" name="qm-to" class="tcal" value="<?if($sort_date_val=='0') echo $sort_date_to?>" id="qm-to"/>
						<label for="qm-to" class="qm-date-label"><i class="new-icomoon icon-calendar"></i></label>
					</div>
				</div>
				<div class="e-qm-filter-period-button">
					<button type="button" data-type="show-date" data-event="no-enter">Показать</button>
				</div>
				<ul class="e-qm-filter-period-segment">
					<li <?if($sort_date_val=="1") echo "class='active'"?> data-val="1" data-from="<?=date('d.m.Y')?>">День</li>
					<li <?if($sort_date_val=="2") echo "class='active'"?> data-val="2" data-from="<?=date('d.m.Y',time() - (6 * 24 * 60 * 60))?>">Неделя</li>
					<li <?if($sort_date_val=="3") echo "class='active'"?> data-val="3" data-from="<?=date('d.m.Y',strtotime("-1 month"))?>">Месяц</li>
					<li class="e-qm-filt-desc<?if($sort_date_val=="4") echo ' active'?>" data-val="4">Весь период</li>
					<li  class="e-qm-filt-mob<?if($sort_date_val=="4") echo ' active'?>" data-val="4">Вce</li>
				</ul>
			</form>
		</div>

		<div class="e-qm-filter-stat">
			<div class="e-qm-filter-period-title">Отображать:</div>
			<ul class="e-qm-filter-stat-list e-qm-filter-stat-list-wrap">
				<div>
					<li <?if($sort=="1") echo "class='active'"?> data-val="1">Все</li>
					<li <?if($sort=="2") echo "class='active'"?> data-val="2">ВОПРОС ПРОСРОЧЕН</li>
					<li <?if($sort=="3") echo "class='active'"?> data-val="3">Новый вопрос</li>		
				</div>
				<div>										
					<li <?if($sort=="4") echo "class='active'"?> data-val="4">На модерации</li>
					<li <?if($sort=="5") echo "class='active'"?> data-val="5">Ответ оправлен</li>
				</div>
				<div>		
					<li <?if($sort=="6") echo "class='active'"?> data-val="6">Вопрос отложен</li>
					<li <?if($sort=="7") echo "class='active'"?> data-val="7">Вопрос прочитан</li>
				</div>
				<div>
					<li <?if($sort=="8") echo "class='active'"?> data-val="8">Вопрос перенаправлен</li>
					<li <?if($sort=="9") echo "class='active'"?> data-val="9">Вопрос переадресован дилеру</li>
				</div>
			</ul>
            <ul class="e-qm-filter-stat-list-mobile e-qm-filter-stat-list-wrap">
                <li <?if($sort=="1") echo "class='active'"?> data-val="1">Все</li>
                <li <?if($sort=="2") echo "class='active'"?> data-val="2">Просрочен</li>
                <li <?if($sort=="3") echo "class='active'"?> data-val="3">Новый</li>
                <li <?if($sort=="4") echo "class='active'"?> data-val="4">На модерации</li>
                <li <?if($sort=="5") echo "class='active'"?> data-val="5">Отвечен</li>
                <li <?if($sort=="6") echo "class='active'"?> data-val="6">Отложен</li>
                <li <?if($sort=="8") echo "class='active'"?> data-val="8">Перенаправлен</li>
                <li <?if($sort=="9") echo "class='active'"?> data-val="9">Переадресован дилеру</li>
            </ul>
		</div>

	</div>

	<div class="e-qm-quest">
	<?
	$res = CIBlockElement::GetList(Array('NAME'=>'DESC'),$arFilter);
	$n = 0;
	while($item = $res->GetNextElement()) {
		$item = array_merge($item->GetFields(), $item->GetProperties());
		if ($item['SEND_DATE']['VALUE'] == "" || $item['SEND_DEALER']['VALUE'] != "") {
	?>
		<div class="e-qm-quest-item" data-type="question-wrap">
			<div class="e-qm-quest-item-number" data-type="ap-title" data-id="<?= $item['ID'] ?>">№ <?=$item['NAME']?></div>
			<div class="e-qm-quest-item-main">
				<div class="e-qm-quest-item-title"><span>Тема: </span><?=$item['QST_SUBJ']['VALUE']?></div>
				<div class="e-qm-quest-item-title"><span>Вопрос добавлен: </span><?=substr($item['QST_DATE']['VALUE'],0,-3)?></div>
				<div class="e-qm-quest-item-answ-title">Текст вопроса:</div>
				<div class="e-qm-quest-item-text"><?=htmlspecialchars_decode($item['QST']['~VALUE']['TEXT'])?></div>

				<div class="e-qm-quest-item-answ">
				<div class="e-qm-quest-item-answ-putoff">
                    пользователь: <?=$item['QST_NAME']['VALUE']?><br>
                    телефон: <?=$item['QST_PHONE']['VALUE']?><br>
                    e-mail: <?=$item['QST_MAIL']['VALUE']?><br>
                    город (местоположение): <?=$item['QST_LOC']['VALUE']?><br>
                    <?
                    if($item['MY_CITY']['VALUE'] != '') {
                        $new_arFilter = Array('IBLOCK_ID' => 7, 'ACTIVE' => 'Y', 'ID' => $item['MY_CITY']['VALUE']);
                        $new_db_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $new_arFilter);
                        $new_ip_loc = $new_db_list->GetNextElement();
                        if ($new_ip_loc) $new_ip_loc = array_merge($new_ip_loc->GetFields(), $new_ip_loc->GetProperties());
                        $cur_loc = $new_ip_loc['NAME'];
                    } else {
                        $cur_loc = 'не указан';
                    }
                    ?>
                    выбранный регион на сайте: <?=$cur_loc?>
				</div>
					<?	if($item['ANSW']['~VALUE']['TEXT'] != "") {?>
						<div class="e-qm-quest-item-answ-title">Текст ответа:</div>
						<div class="e-qm-quest-item-answ-text"><?=htmlspecialchars_decode($item['ANSW']['~VALUE']['TEXT'])?></div>
						<? $spec_name = $item['ANSW_NAME']['VALUE'] != "" ? $item['ANSW_NAME']['VALUE']."&nbsp;&nbsp;" : ""; ?>
						<div class="e-qm-quest-item-answ-sign">ответил: <?=$spec_name?><?=substr($item['ANSW_DATE']['VALUE'],0,-3)?></div>
					<? } ?>

					<?if($item['QST_SPEC']['VALUE'] != "" && $item['ANSW']['~VALUE']['TEXT'] == "" && $item['SEND_DEALER']['VALUE'] == "") {?>
					<? 
						$pers_arr = $item['QST_SPEC']['VALUE'];
						if(count($pers_arr) == 1) {
							$person = "ответственный: ".$pers_arr[0];
						} else {
							$n = 0;
							$person = "ответственные: ";
							foreach ($pers_arr as $pers) {
								$person .= $pers;
								if($n+1 != count($pers_arr)) {
									$person .= ", ";
								}
								$n++;
							}
						}
					?>
						<div class="e-qm-quest-item-answ-sign"><?=$person?></div>
					<? } ?>

					<?if($item['SEND_DEALER']['VALUE'] != ""): ?>
						<div class="e-qm-quest-item-answ-putoff">
							<span>вопрос переадресован дилеру</span><br>
							E-mail: <?=$item['SEND_DEALER']['VALUE']?><br>
							Переадресовал: <?=$item['SEND_WHO']['VALUE']?>, <?=substr($item['SEND_DATE']['VALUE'],0,-3)?><br>
						</div>
					<? endif; ?>

                    <?	if($item['DEALER_REPORT']['~VALUE']['TEXT'] != "") {?>
                        <div class="e-qm-quest-item-answ-title">Отчёт дилера:</div>
                        <div class="e-qm-quest-item-answ-text"><?=htmlspecialchars_decode($item['DEALER_REPORT']['~VALUE']['TEXT'])?></div>
                        <div class="e-qm-quest-item-answ-sign"><?=substr($item['DEALER_REPORT_DATE']['VALUE'],0,-3)?></div>
                    <? } ?>

					<? if($item['QST_SEND']['VALUE'] == "Y" && $item['SEND_DEALER']['VALUE'] == "") {
						$prev_id = $item['QST_SEND_ID']['VALUE'];
						$ar_res = CIBlockElement::GetList(Array('CREATED'=>'DESC'),Array('IBLOCK_ID'=>37,'ID'=>$prev_id,'ACTIVE'=>'Y'));
							while($prev_item = $ar_res->GetNextElement()) {
								$prev_item = array_merge($prev_item->GetFields(), $prev_item->GetProperties());
					?>
						<div class="e-qm-quest-item-answ-putoff">
                            <? if($prev_item['SEND_SUBJ']['VALUE'] != ""): ?>
                                вопрос перенаправлен из темы: <?=$prev_item['QST_SUBJ']['VALUE']?><br>
                            <? endif;?>
                            <? if($prev_item['SEND_SPEC']['VALUE'] != ""): ?>
                                вопрос перенаправлен другому менеджеру.<br>
                            <? endif;?>
                            <? if($prev_item['SEND_REG']['VALUE'] != ""): ?>
                                вопрос перенаправлен в регион: <?=$prev_item['SEND_REG']['VALUE']?>.<br>
                            <? endif;?>
                            <? $spec_name = $prev_item['SEND_WHO']['VALUE'] != "" ? $prev_item['SEND_WHO']['VALUE'].",&nbsp;&nbsp;" : ""; ?>
                            перенаправил: <?=$spec_name?><?=substr($prev_item['SEND_DATE']['VALUE'],0,-3)?>
						</div>
					<? } }?>
					
					<?
						$arRes = CIBlockElement::GetList(Array('CREATED'=>'ASC'),Array('IBLOCK_ID'=>42,'NAME'=>$item['NAME'],'ACTIVE'=>'Y'));
						$add_count = $arRes -> SelectedRowsCount();
					?>
					<? if($item['USEFUL']['VALUE'] !="" || $add_count > 0): ?>
						<div class="e-qm-user-score">
							<div class="e-qm-quest-item-answ-title" style="margin-bottom:10px;">Дополнительные вопросы:</div>
							<? 
								$arRes = CIBlockElement::GetList(Array('CREATED'=>'ASC'),Array('IBLOCK_ID'=>42,'NAME'=>$item['NAME'],'ACTIVE'=>'Y'));
								while($add = $arRes->GetNextElement()) {
									$add = array_merge($add->GetFields(), $add->GetProperties());
									if (isset($add)) { ?>
							<?
							$add_stat = $add['ADD_MESS_STAT']['VALUE'] == "Вопрос" ? "user" : "spec";
							?>
								<div class="e-ap-comment e-ap-comment-<?=$add_stat?>">
									<div class="e-ap-comment-name"><?=$add['ADD_MESS_STAT']['VALUE']?>,  <?=substr($add['ADD_MESS_DATE']['VALUE'],0,-3)?></div>
									<div class="e-ap-comment-text" data-type="add-answ"><?=htmlspecialchars_decode($add['ADD_MESS']['~VALUE']['TEXT'])?></div>
									<? if($add['ADD_MESS_FILE']['VALUE'] != "") { ?>
										<?
											$file_name = explode("/",$add['ADD_MESS_FILE']['VALUE']);
											$length = count($file_name);
											$file_name = $file_name[$length-1];
										?>
										<div class="e-ap-ask-file e-ap-ask-file-current">Прикрепленный файл: <a href="<?=$add['ADD_MESS_FILE']['VALUE']?>"><?=$file_name?></a></div>
									<? } ?>

									<? if($add['ADD_MESS_SPEC']['VALUE'] != "") { ?>
										<div class="e-ap-comment-for">ответил: <?=$add['ADD_MESS_SPEC']['VALUE']?></div>
									<? } ?>
									
								</div>
							<? }
						}
					?>


							<? if($item['USEFUL']['VALUE'] != ""):?>
								<?
								$score = $item['USEFUL']['VALUE'] == "Y" ? "Да" : "Нет";
								?>
								<div class="e-qm-quest-item-answ-text">Был ли ответ полезен? - <span class="<?=$score == 'Да' ? 'green' : 'red'?>"><?=$score?></span></div>
							<? endif; ?>
						</div>
					<? endif; ?>
					
					<div class="e-qm-quest-item-comments">
					<div class="e-qm-quest-item-answ-title" style="margin-bottom:10px;">
            Комментарии:
              <?/*запросить комментарий*/?>
              <? if($user_stat == "admin" || $user_stat == "mod") { ?>
                <div class="e-ap-need-comm-wrap">
                    <? if($item['REQ_COMM']['VALUE'] != 'Y') { ?>
                      <div class="e-ap-need-comm" data-type="need-comm">
                        <i class="icon-checked"></i>
                        <span data-type="ap-putoff-txt">Запросить комментарий</span>
                      </div>
                    <? } else { ?>
                      <p class="need-comm-send"><i class="icon-checked"></i>Запрошен комментарий</p>
                    <? } ?>
                </div>
              <? } ?>
          </div>
						<div class="e-qm-quest-item-comment">
						<? $hascomm = 0;
							$not_seen = "";
						?>		
						<? $arRes = CIBlockElement::GetList(Array('CREATED'=>'ASC'),Array('IBLOCK_ID'=>39,'NAME'=>$item['NAME'],'ACTIVE'=>'Y'));
							while($comment = $arRes->GetNextElement()) {
								$comment = array_merge($comment->GetFields(), $comment->GetProperties());						
								if (isset($comment)) {?>
									<div class="e-ap-comment e-ap-comment-<?=$comment['COMM_STAT']['VALUE']?>">
                                        <?if($comment['COMM_STAT']['VALUE'] == 'dealer') { ?>
                                            <div class="e-ap-comment-name">
                                                Дилер, <?= date('d.m.Y H:i', $comment['DATE_CREATE_UNIX']) ?>
                                            </div>
                                        <? } else { ?>
                                            <? $comm_who = $comment['COMM_NAME']['VALUE']!="" ? $comment['COMM_NAME']['VALUE'].", " : "";?>
                                            <div class="e-ap-comment-name">
                                                <?=$comm_who?> <?=date('d.m.Y H:i',$comment['DATE_CREATE_UNIX'])?>
                                            </div>
                                        <? } ?>

										<div class="e-ap-comment-text"><?=htmlspecialchars_decode($comment['COMM_TEXT']['~VALUE']['TEXT'])?></div>
										<? 
											$pers_arr = $comment['COMM_WHO_SEND']['VALUE'];
											if(!empty($pers_arr)) {
											$n = 0;
												$person = "Комментарий для: ";
												foreach ($pers_arr as $pers) {
													$person .= $pers;
													if($n+1 != count($pers_arr)) {
														$person .= ", ";
													}
													$n++;
												}
												echo '<div class="e-ap-comment-for">'.$person.'</div>';
											}
										?>
                                        <?if($comment['COMM_STAT']['VALUE'] == 'dealer') { ?>
                                            <div class="e-ap-comment-for">
                                                <span>Информация по дилеру:</span>
                                                <?if($comment['COMM_NAME']['VALUE'] != '') { ?>
                                                    <div><?=htmlspecialchars_decode($comment['COMM_NAME']['VALUE'])?></div>
                                                <? } ?>
                                                <?if($comment['COMM_EMAIL']['VALUE'] != '') { ?>
                                                    <div><?=$comment['COMM_EMAIL']['VALUE']?></div>
                                                <? } ?>
                                            </div>
                                        <? } ?>
									</div>

									<?if($comment['COMM_STAT']['VALUE'] == "spec" && $comment['COMM_SEEN']['VALUE'] == 'N') {
										$not_seen = "not-seen";
									}
									$hascomm++;
								}
							}
						?>
						</div>
						<div class="e-ap-mod-comment">
							<form class="apComm" data-pos="report" data-id="<?=$item['ID']?>" data-numb="<?=$item['NAME']?>">
								<input type="hidden" name="comm-stat" value="mod">			
								<div class="e-ap-mod-comment-wrapper" data-type="comment-text">
									<div class="e-ap-textarea-placeholder" data-type="comm-plchldr"><i class="new-icomoon icon-comment1"></i> Комментарий в Справочную</div>
									<textarea data-type="comm-text" name="comm-text" class="autogrow"></textarea>
								</div>
								<div class="e-ap-who-send no-margin">
									<span>Комментарий отправить:</span>
									<div class="e-ap-who-send-list">
										<?
											/*$arr_mod = Array();
											$n = 0;
											$filter = Array("GROUPS_ID" => Array(10),"ACTIVE"=>"Y");
											$rsUsers = CUser::GetList(($by = "NAME"), ($order = "desc"), $filter);
											while ($arUser = $rsUsers->Fetch()) {
											  $arr_mod[$n] = $arUser['NAME'];
											  $n++;
											}

											//исключаем повторно Рыка
											$arr_spec = $item['QST_SPEC']['VALUE'];
											if(($key = array_search('Ольга Гмыря',$arr_spec)) !== FALSE){
											     unset($arr_spec[$key]);
											}
											
											$arr_users = array_merge($arr_spec,$arr_mod);*/
                                            $arr_users = Array("Алексей Брук","Андрей Чиличихин","Сергей Авдеев","Александра Высоцкая");
                                            $arr_spec = $item['QST_SPEC']['VALUE'];
                                            foreach($arr_spec as $spec) {
                                                if(!in_array($spec,$arr_users)) {
                                                    $arr_users[] = $spec;
                                                }
                                            }
											$n = 0;
											foreach ($arr_users as $spec): ?>
											<? if( $spec != $user): ?>
												<?
													$filter = Array("NAME" => $spec);
													$rsUsers = CUser::GetList(($by = "NAME"), ($order = "desc"), $filter);
													while ($arUser = $rsUsers->Fetch()) {
													  $spec_id = $arUser['ID'];
													}
												?>
												<div>
													<input type="checkbox" name="send_who[]" value="<?=$spec_id?>" id="<?=$item['ID']?><?=$spec_id?><?=$n?>" class="e-ap-edit-input">
													<label for="<?=$item['ID']?><?=$spec_id?><?=$n?>" class="e-ap-edit-label"><?=$spec;?></label>
												</div>
												<? $n++;?>
											<? endif ?>
										<? endforeach ?>
									</div>
								</div>	
								<button type="button" class="e-ap-mod-comment-send" data-type="mod-comment-send" data-event="no-enter">Отправить</button>
								<button type="reset" class="e-ap-mod-comment-reset" data-type="mod-comment-reset" data-event="no-enter">Очистить</button>
							</form>
						</div>
					</div>			
				</div>
				<a class="e-qm-more">Подробнее <i class="icon-angle-down"></i></a>

				<a href="/question_service/answer.php?ext_id=<?=$item['EXTERNAL_ID']['VALUE']?>&stat=mod" class="e-qm-go">Перейти на страницу вопроса <i class="icon-arrow-right"></i></a>
				
				<a href="/question_service/answer.php?ext_id=<?=$item['EXTERNAL_ID']['VALUE']?>&stat=mod" class="e-qm-dialog <?=$not_seen?>"><i class="new-icomoon icon-bubble"></i> Комментарии: <?=$hascomm?></a>
			</div>

			<? if($item['QST_STATUS']['VALUE'] == "Ответ отправлен") {
					$class = "send";
				} elseif ($item['QST_STATUS']['VALUE'] == "Вопрос просрочен") {
					$class = "postdate";
				}
				else {
					$class = "";
				}
			?>			
			<div class="e-qm-quest-item-stat <?=$class?>"><?=$item['QST_STATUS']['VALUE']?></div>			
		
		</div>

	<? }
	$n++; 
	}
	if ($n == "") {
		echo "<div class='e-qm-quest-item'>Вопросов с&nbsp;такими параметрами не&nbsp;найдено.</div>";
	}
	?>	
	</div>


</div>


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
