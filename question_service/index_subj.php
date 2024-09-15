<?require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
LocalRedirect('/');
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {

    exit;
}

$subj_id = $_GET['subj'];

$subj_qty = CIBlockSection::GetSectionElementsCount($subj_id, Array("CNT_ACTIVE"=>"Y"));


$res = CIBlockSection::GetByID($subj_id);
if($ar_res = $res->GetNext())
  $subj_name = $ar_res['NAME'];


$APPLICATION->SetTitle($subj_name); 

$APPLICATION->AddChainItem("Задать вопрос","/question_service/");

$APPLICATION->AddChainItem($subj_name,"#");



require($_SERVER["DOCUMENT_ROOT"] . "/include/catalogue/header.php");
?>


<link rel="stylesheet" href="/question_service/questionstyle19.css?<?=$release?>" type="text/css"/>


<div id="middle">

<div class="e-aqs-left">
	<h2 class="e-aqs-title">Часто задаваемые вопросы в категории:</h2>

	<div class="e-aqs-subj">
		<div class="e-aqs-subj-title">
		<h2><?=$subj_name?></h2>
		<span><?=$subj_qty?></span>
		</div>
		<div class="e-aqs-subj-wrap e-aqs-one-subj">

		<? 
			$res = CIBlockElement::GetList(Array('PROPERTY_NEW_DATE'=>'desc'),Array('IBLOCK_CODE'=>'question_pattern','SECTION_ID'=>$subj_id,'ACTIVE'=>'Y'));
			while($item = $res->GetNextElement()) {	
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
				<div class="e-aqs-item-answ">
					<?=$item['~DETAIL_TEXT']?>
				</div>
			</div>

			<? } ?>


		</div>
	</div>


</div>

<div class="e-aqs-right">
	<h1 class="e-aqs-form-title">Задать вопрос</h1>

	<form class="aqs-main-form" data-type="aqs-main-form" id="aqsMainForm">
		<input type="hidden" name="aqs-city" value="<?=$my_city?>">
		<input type="hidden" name="aqs-page" value="" id="e-aqs-input-page">
		<input type="text" name="aqs-name" placeholder="Ваше имя*">
		<input type="text" name="aqs-email" placeholder="E-mail*">
		<input type="text" name="aqs-tel" placeholder="Контактный телефон*">
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
<?require($_SERVER["DOCUMENT_ROOT"] . "/include/catalogue/footer.php");
if(defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED===true)
{
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog.php");
}
?>