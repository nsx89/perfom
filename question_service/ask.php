<?require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
LocalRedirect('/');
$APPLICATION->SetTitle("Задать вопрос");

$APPLICATION->AddChainItem("Задать вопрос","#");

if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {

    exit;

}
require($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");
?>


<link rel="stylesheet" href="/question_service/questionstyle19.css?<?=$release?>" type="text/css"/>


<div id="middle" class="cb-middle">

<div class="e-aqs-temp">
<img src="/images/qs-ask-eplast.png">
<div style="font-size: 16px;
    text-transform: uppercase;
    font-weight: bold;
    text-align: center;
    width: 90%;
    margin-top: 30px;">На этой странице Вы можете заказать консультацию по монтажу изделий</div>
</div>
<?/*?>
<div class="e-aqs-left">

	<h2 class="e-aqs-title">Часто задаваемые вопросы по категориям:</h2>

	<?
	//Вопрос-ответ
	//получаем разделы вопросов в массив
	$db_list = CIBlockSection::GetList(Array('SORT' => 'ASC'), Array('IBLOCK_CODE'=>'question_pattern','ACTIVE'=>'Y'), true);
	$ar_subj = Array();
	while ( $ar_result = $db_list->GetNext() )
	  { 
	    if ( $ar_result['ELEMENT_CNT']!= 0 ) {
	    	$qty = CIBlockSection::GetSectionElementsCount($ar_result['ID'], Array("CNT_ACTIVE"=>"Y"));
	        $arr = Array('id'=>$ar_result['ID'],'name'=>$ar_result['NAME'],'qty'=>$qty);
	        array_push($ar_subj, $arr);
	    }
	  }

	foreach($ar_subj as $subj) {
		$res = CIBlockElement::GetList(Array('PROPERTY_NEW_DATE'=>'desc'),Array('IBLOCK_CODE'=>'question_pattern','SECTION_ID'=>$subj['id'],'ACTIVE'=>'Y'));
	?>

	<div class="e-aqs-subj">
		<div class="e-aqs-subj-title" data-type="subj">
		<h2><?=$subj['name']?></h2>
		<span><?=$subj['qty']?></span>
		</div>
		<div class="e-aqs-subj-wrap">

		<? 
			$n = 0;
			while($item = $res->GetNextElement()) {
				if($n < 2) {				
				$item = array_merge($item->GetFields(), $item->GetProperties()); ?>

			<div class="e-aqs-item">
				<div class="e-aqs-item-title">Вопрос №<?=$item['NAME']?></div>
				<? if ($item['NEW_DATE']['VALUE']!="") {
						$date = substr($item['NEW_DATE']['VALUE'],0,-3);
					} else {
						$date = substr($item['DATE_CREATE'],0,-3);
					}
				?>
				<div class="e-aqs-item-date"><?=$date?></div>
				<div class="e-aqs-item-qst">
					<?=$item['~PREVIEW_TEXT']?>
				</div>
				<div class="e-aqs-item-answ-title">Ответ:</div>
				<div class="e-aqs-item-answ" data-type="short-answ">
					<?=$item['~DETAIL_TEXT']?>
				</div>
			</div>

			<? 	
				} 
				$n++; 
			} ?>


			<a href="/question_service/index_subj.php?subj=<?=$subj['id']?>" class="e-aqs-more">Показать больше вопросов <i class="icon-angle-down"></i></a>
		</div>
	</div>

	<? } ?>

</div>

<?*/?>

<div class="e-aqs-right">
<?/*<div class="call-back">
	<p class="call-back-title">Заказать обратный звонок</p>
	<p class="call-back-lead">Оставьте заявку прямо сейчас, мы перезвоним</p>
	<form id="callBackForm">
		<input type="hidden" name="cb-city" value="<?=$my_city?>">
		<input type="hidden" name="cb-page" value="" id="cb-page">
		<input type="text" name="cb-name" placeholder="Ваше имя*">
		<input type="text" name="cb-tel" placeholder="Контактный телефон*">
		<p class="e-aqs-form-exmpl">(пример: +74957808214)</p>
		<input type="checkbox" id="cb_policy" name="cb_policy">
		<label for="cb_policy" class="cb_policy_label">Я согласен(на) <span>на <a href="/company/policies" target="_blanc">обработку персональных данных</a></span></label>
		<div class="call-back-rqst">Спасибо за заявку!</div>
		<button type="button" data-type="cb-but" data-event="no-enter">Отправить заявку</button>
	</form>
</div>*/?>
	<h1 class="e-aqs-form-title">Задать вопрос</h1>

	<form class="aqs-main-form" data-type="aqs-main-form" id="aqsMainForm">
		<input type="hidden" name="aqs-city" value="<?=$my_city?>">
		<input type="hidden" name="aqs-page" value="" id="e-aqs-input-page">
		<input type="text" name="aqs-name" placeholder="Ваше имя*">
		<input type="text" name="aqs-email" placeholder="E-mail*">
		<input type="text" name="aqs-tel" placeholder="Контактный телефон">
		<p class="e-aqs-form-exmpl">(пример: +74957808214)</p>
		<div class="e-aqs-select-wrap">
			<select name="aqs-subj">
			<option value="" disabled selected style="display:none;">Выберите из списка</option>
			<option value="2">Монтаж изделий</option>
			<option value="3">Свойства изделий</option>
			<option value="4">Претензии и вопросы по заказам и сервису</option>
			<option value="1">Ассортимент и уточнение по размерам</option>
			<option value="5">Гарантийные обязательства</option>
			<option value="6">Работа магазинов</option>
			<option value="7">Другое</option>
		</select>
		</div>
		
		<textarea name="aqs-qst" placeholder="Ваш вопрос*:"></textarea>

		<div class="e-aqs-file-wrapper">
         <label>
           <i class="br-icon-paper-clip"></i>
           <span class="e-aqs-file-name" data-type="add-file">Прикрепить файл</span>
           <input type="file" name="aqs-file" accept="image/jpeg,image/png,.zip,.rar,.pdf">
           <span class="e-aqs-file-browse">Обзор</span>
         </label>
      </div>
      <p class="e-aqs-file-exmpl">
      Разрешены к отправке файлы: изображения JPEG, PNG, PDF,
		архивы RAR, ZIP, размер файла не должен превышать 10 МБ
		</p>
		<input type="checkbox" id="aqs_policy" name="aqs_policy">
		<label for="aqs_policy" class="aqs_policy_label">Я согласен(на) <span>на <a href="/company/policies" target="_blanc">обработку персональных данных</a></span></label>
		<button type="button" class="e-aqs-form-button">Отправить</button>
		<div class="e-aqs-form-loader"><img src="/images/AjaxLoader.gif"></div>

	</form>

	<p class="e-aqs-field-req">* – пункты, обязательные для заполнения</p>

	<div class="e-aqs-form-confirm" data-type="aqs-rqst"></div>

</div>


</div>

<script src="/scripts/phoneValidator.js"></script>
<script src="/question_service/questionService22.js?1"></script>
<?require($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");
?>